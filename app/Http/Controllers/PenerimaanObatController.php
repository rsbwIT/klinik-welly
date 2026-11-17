<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\TrackerHelper;

class PenerimaanObatController extends Controller
{
    /**
     * Tampilkan Halaman Pengadaan & Penerimaan Obat (Persis 100% Interface SIMRS Khanza DlgPembelian)
     * Header No. Faktur PB..., Suplier, Tgl.Datang, Tgl.Faktur, SP/Order, Jth.Tempo, Petugas, Lokasi
     */
    public function index(Request $request)
    {
        // Format No Faktur Khanza: PBYYYYMMDDXXXX (misal: PB202611120001)
        $todayPrefix = 'PB' . date('Ymd');
        $lastFaktur = DB::table('pengadaan_penerimaan_obat')
            ->where('no_faktur', 'LIKE', "{$todayPrefix}%")
            ->orderBy('no_faktur', 'desc')
            ->value('no_faktur');

        $nextSeq = 1;
        if ($lastFaktur) {
            $numPart = substr($lastFaktur, strlen($todayPrefix));
            if (is_numeric($numPart)) {
                $nextSeq = intval($numPart) + 1;
            }
        }
        $noFaktur = $todayPrefix . sprintf('%04d', $nextSeq);

        // Depo / Bangsal Penerimaan (Default: GUDANG Farmasi Utama)
        $depoList = DB::table('bangsal')->where('status', '1')->orderBy('kd_bangsal')->get();
        $selectedDepo = $request->input('kd_bangsal', 'GUDANG');
        $depoAktif = DB::table('bangsal')->where('kd_bangsal', $selectedDepo)->first() ?? $depoList->first();

        // Suplier / PBF
        $suplierList = DB::table('industrifarmasi')->orderBy('nama_industri')->get();
        $defaultSuplier = $suplierList->first();

        // Query obat master dengan stok di depo penerimaan terkait
        $query = DB::table('databarang_obat as o')
            ->leftJoin('gudangbarang as g', function($join) use ($selectedDepo) {
                $join->on('g.kode_brng', '=', 'o.kode_brng')
                     ->where('g.kd_bangsal', '=', $selectedDepo);
            })
            ->where('o.status', '1')
            ->select(
                'o.kode_brng',
                'o.nama_brng',
                'o.kode_sat',
                'o.h_beli',
                'o.ralan',
                'o.kategori',
                DB::raw('COALESCE(g.stok, 0) as stok_depo'),
                'o.stok as total_stok_master'
            );

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('o.kode_brng', 'LIKE', "%{$q}%")
                  ->orWhere('o.nama_brng', 'LIKE', "%{$q}%")
                  ->orWhere('o.kategori', 'LIKE', "%{$q}%");
            });
        }

        $obatList = $query->orderBy('o.nama_brng', 'asc')->get();

        return view('farmasi.penerimaan_obat', compact('noFaktur', 'depoList', 'selectedDepo', 'depoAktif', 'suplierList', 'defaultSuplier', 'obatList'));
    }

    /**
     * Simpan Transaksi Penerimaan Obat (Stok Utama Bertambah)
     * Mengikuti Algoritma SIMRS Khanza DlgPembelian
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_faktur' => 'required',
            'kd_bangsal' => 'required',
            'items' => 'required|array|min:1',
        ]);

        $noFaktur = $request->no_faktur;
        $kdBangsal = $request->kd_bangsal; // Biasanya 'GUDANG'
        $petugas = Auth::user()->name ?? 'Petugas Farmasi';
        $kodeSuplier = $request->kode_suplier ?: 'PBF01';
        $namaSuplier = $request->nama_suplier ?: 'Distributor Farmasi';

        DB::beginTransaction();
        try {
            $totalBruto = 0;
            $itemsToInsert = [];

            foreach ($request->items as $item) {
                $jml = floatval($item['jml'] ?? 0);
                if ($jml <= 0) continue;

                $kodeBrng = $item['kode_brng'];
                $hBeli = floatval($item['h_beli'] ?? 0);
                $subtotal = $jml * $hBeli;
                $diskPersen = floatval($item['diskon_persen'] ?? 0);
                $diskRp = floatval($item['diskon_rp'] ?? 0);
                if ($diskPersen > 0 && $diskRp == 0) {
                    $diskRp = ($subtotal * $diskPersen) / 100;
                }
                $netto = max(0, $subtotal - $diskRp);
                $totalBruto += $netto;

                $noBatch = !empty($item['no_batch']) ? $item['no_batch'] : '-';
                $kadaluarsa = !empty($item['tgl_kadaluarsa']) ? $item['tgl_kadaluarsa'] : null;

                $itemsToInsert[] = [
                    'no_faktur' => $noFaktur,
                    'kode_brng' => $kodeBrng,
                    'jumlah' => $jml,
                    'h_beli' => $hBeli,
                    'subtotal' => $subtotal,
                    'diskon_persen' => $diskPersen,
                    'diskon_rp' => $diskRp,
                    'total' => $netto,
                    'no_batch' => $noBatch,
                    'tgl_kadaluarsa' => $kadaluarsa,
                    'created_at' => now(),
                ];

                // 1. Tambah stok fisik di lokasi depo (gudangbarang)
                $cekGudang = DB::table('gudangbarang')
                    ->where('kode_brng', $kodeBrng)
                    ->where('kd_bangsal', $kdBangsal)
                    ->first();

                if ($cekGudang) {
                    DB::table('gudangbarang')
                        ->where('kode_brng', $kodeBrng)
                        ->where('kd_bangsal', $kdBangsal)
                        ->increment('stok', $jml);
                } else {
                    DB::table('gudangbarang')->insert([
                        'kode_brng' => $kodeBrng,
                        'kd_bangsal' => $kdBangsal,
                        'stok' => $jml,
                        'no_batch' => $noBatch,
                        'no_faktur' => $noFaktur,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // 2. Tambah total akumulasi stok master (databarang_obat) & update harga beli jika ada
                DB::table('databarang_obat')
                    ->where('kode_brng', $kodeBrng)
                    ->increment('stok', $jml, [
                        'h_beli' => $hBeli > 0 ? $hBeli : DB::raw('h_beli')
                    ]);
            }

            if (empty($itemsToInsert)) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Silakan masukkan jumlah penerimaan pada kolom [Jml] di tabel barang terlebih dahulu.');
            }

            // Simpan detail penerimaan
            DB::table('detail_pengadaan_penerimaan')->insert($itemsToInsert);

            // Simpan header faktur penerimaan
            $potongan = floatval($request->potongan ?? 0);
            $totalNetto = max(0, $totalBruto - $potongan);
            $ppnPersen = floatval($request->ppn_persen ?? 11);
            $ppnRp = floatval($request->ppn_rp ?? 0);
            if ($ppnRp == 0 && $ppnPersen > 0) {
                $ppnRp = ($totalNetto * $ppnPersen) / 100;
            }
            $meterai = floatval($request->meterai ?? 0);
            $tagihan = $totalNetto + $ppnRp + $meterai;

            DB::table('pengadaan_penerimaan_obat')->insert([
                'no_faktur' => $noFaktur,
                'no_order' => $request->no_order ?: '-',
                'kode_suplier' => $kodeSuplier,
                'nama_suplier' => $namaSuplier,
                'nip' => '01091999',
                'petugas' => $petugas,
                'tgl_beli' => $request->tgl_faktur ?: date('Y-m-d'),
                'total_bruto' => $totalBruto,
                'potongan' => $potongan,
                'total_netto' => $totalNetto,
                'ppn' => $ppnRp,
                'tagihan' => $tagihan,
                'kd_bangsal' => $kdBangsal,
                'catatan' => $request->catatan ?: "Penerimaan faktur masuk ke {$kdBangsal}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            TrackerHelper::log('Farmasi', 'Penerimaan Obat Khanza', "Penerimaan Faktur {$noFaktur} masuk ke {$kdBangsal} Total: Rp " . number_format($tagihan));
            return redirect()->route('farmasi.penerimaan.index')
                ->with('success', "Penerimaan obat No. Faktur {$noFaktur} berhasil disimpan! Stok masuk ke lokasi {$kdBangsal}.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses penerimaan: ' . $e->getMessage());
        }
    }
}
