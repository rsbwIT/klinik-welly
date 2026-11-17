<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenjualanBebasController extends Controller
{
    /**
     * Tampilkan Form Transaksi Penjualan Obat Bebas (Standar mLITE / SIMRS Khanza)
     */
    public function index(Request $request)
    {
        // Format No. Nota Khanza: PJYYYYMMDDXXXX
        $todayPrefix = 'PJ' . date('Ymd');
        $lastNota = DB::table('penjualan_bebas')
            ->where('no_nota', 'LIKE', "{$todayPrefix}%")
            ->orderBy('no_nota', 'desc')
            ->value('no_nota');

        $nextSeq = 1;
        if ($lastNota) {
            $numPart = substr($lastNota, strlen($todayPrefix));
            if (is_numeric($numPart)) {
                $nextSeq = intval($numPart) + 1;
            }
        }
        $noNota = $todayPrefix . sprintf('%04d', $nextSeq);

        // Ambil data depo / bangsal farmasi
        $depoList = DB::table('bangsal')->where('status', '1')->orderBy('kd_bangsal')->get();
        $selectedDepo = $request->input('kd_bangsal', 'DepoRJ');
        if ($selectedDepo === 'SEMUA') {
            $depoAktif = (object) ['kd_bangsal' => 'SEMUA', 'nm_bangsal' => 'Semua Depo / Apotek'];
        } else {
            $depoAktif = DB::table('bangsal')->where('kd_bangsal', $selectedDepo)->first() 
                ?? DB::table('bangsal')->first();
            $selectedDepo = $depoAktif->kd_bangsal;
        }

        // Query obat dengan JOIN ke tabel gudangbarang per depo (Standar SIMRS Khanza)
        if ($selectedDepo === 'SEMUA') {
            $query = DB::table('databarang_obat as o')
                ->leftJoin('gudangbarang as g', 'g.kode_brng', '=', 'o.kode_brng')
                ->where('o.status', '1')
                ->select(
                    'o.kode_brng',
                    'o.nama_brng',
                    'o.kode_sat',
                    'o.h_beli',
                    'o.ralan',
                    'o.kategori',
                    'o.status',
                    DB::raw('COALESCE(SUM(g.stok), o.stok) as stok'),
                    DB::raw('MAX(g.no_batch) as no_batch'),
                    DB::raw('MAX(g.no_faktur) as no_faktur')
                )
                ->groupBy('o.kode_brng', 'o.nama_brng', 'o.kode_sat', 'o.h_beli', 'o.ralan', 'o.kategori', 'o.status', 'o.stok');
        } else {
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
                    'o.status',
                    DB::raw('COALESCE(g.stok, o.stok) as stok'),
                    DB::raw('COALESCE(g.no_batch, "-") as no_batch'),
                    DB::raw('COALESCE(g.no_faktur, "-") as no_faktur')
                );
        }

        if ($request->has('q') && !empty($request->q)) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('o.kode_brng', 'LIKE', "%{$q}%")
                  ->orWhere('o.nama_brng', 'LIKE', "%{$q}%")
                  ->orWhere('o.kategori', 'LIKE', "%{$q}%");
            });
        }
        $obatList = $query->orderBy('o.kategori', 'asc')->orderBy('o.nama_brng', 'asc')->get();

        // Riwayat transaksi penjualan bebas hari ini / terbaru
        $riwayat = DB::table('penjualan_bebas')
            ->orderBy('tgl_jual', 'desc')
            ->orderBy('jam_jual', 'desc')
            ->limit(30)
            ->get();

        return view('farmasi.penjualan_bebas', [
            'noNota' => $noNota,
            'obatList' => $obatList,
            'riwayat' => $riwayat,
            'depoList' => $depoList,
            'selectedDepo' => $selectedDepo,
            'depoAktif' => $depoAktif,
            'keyword' => $request->q ?? ''
        ]);
    }

    /**
     * Simpan Transaksi Penjualan Obat Bebas
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_nota' => 'required',
            'nama_pembeli' => 'required|string|max:100',
            'items' => 'required|array|min:1'
        ]);

        $noNota = $request->no_nota;
        $kdBangsal = $request->kd_bangsal ?? 'DepoRJ';
        $tglJual = $request->tgl_jual ?? date('Y-m-d');
        $jamJual = $request->jam_jual ?? date('H:i:s');
        $namaPembeli = $request->nama_pembeli;
        $alamatPembeli = $request->alamat_pembeli ?? '-';
        $noTelp = $request->no_telp ?? '-';
        $potongan = (float) ($request->potongan ?? 0);
        $jumlahBayar = (float) ($request->jumlah_bayar ?? 0);
        $caraBayar = $request->cara_bayar ?? 'Tunai';
        $petugas = Auth::user()->name ?? 'Petugas Farmasi';
        $catatan = $request->catatan ?? '-';

        $totalBiaya = 0;

        foreach ($request->items as $item) {
            if (empty($item['kode_brng']) || empty($item['jumlah']) || $item['jumlah'] <= 0) {
                continue;
            }

            $brng = DB::table('databarang_obat')->where('kode_brng', $item['kode_brng'])->first();
            if (!$brng) continue;

            $harga = (float) ($item['harga_jual'] ?? $brng->ralan);
            $qty = (int) $item['jumlah'];
            $subtotal = $harga * $qty;
            $totalBiaya += $subtotal;

            DB::table('penjualan_bebas_detail')->insert([
                'no_nota' => $noNota,
                'kode_brng' => $brng->kode_brng,
                'nama_brng' => $brng->nama_brng,
                'satuan' => $brng->kode_sat,
                'harga_jual' => $harga,
                'jumlah' => $qty,
                'subtotal' => $subtotal,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Pengurangan stok obat di depo terkait (gudangbarang)
            if ($kdBangsal && $kdBangsal !== 'SEMUA') {
                DB::table('gudangbarang')
                    ->where('kode_brng', $brng->kode_brng)
                    ->where('kd_bangsal', $kdBangsal)
                    ->decrement('stok', $qty);
            }

            // Pengurangan stok master obat global
            DB::table('databarang_obat')
                ->where('kode_brng', $brng->kode_brng)
                ->decrement('stok', $qty);
        }

        $harusBayar = max(0, $totalBiaya - $potongan);
        if ($jumlahBayar <= 0) {
            $jumlahBayar = $harusBayar;
        }
        $kembalian = max(0, $jumlahBayar - $harusBayar);

        // Header Penjualan Bebas
        DB::table('penjualan_bebas')->insert([
            'no_nota' => $noNota,
            'tgl_jual' => $tglJual,
            'jam_jual' => $jamJual,
            'nama_pembeli' => $namaPembeli,
            'alamat_pembeli' => $alamatPembeli,
            'no_telp' => $noTelp,
            'total_biaya' => $totalBiaya,
            'potongan' => $potongan,
            'harus_bayar' => $harusBayar,
            'jumlah_bayar' => $jumlahBayar,
            'kembalian' => $kembalian,
            'cara_bayar' => $caraBayar,
            'petugas' => $petugas,
            'catatan' => $catatan,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('penjualan.bebas.index')
            ->with('success', 'Transaksi penjualan bebas (' . $noNota . ') atas nama ' . $namaPembeli . ' berhasil disimpan!')
            ->with('last_nota', $noNota);
    }

    /**
     * Cetak Struk / Nota Penjualan Bebas
     */
    public function cetakNota($noNota)
    {
        $penjualan = DB::table('penjualan_bebas')->where('no_nota', $noNota)->first();
        if (!$penjualan) {
            abort(404, 'Data transaksi tidak ditemukan');
        }

        $items = DB::table('penjualan_bebas_detail')->where('no_nota', $noNota)->get();

        return view('farmasi.cetak_nota_bebas', [
            'penjualan' => $penjualan,
            'items' => $items
        ]);
    }

    /**
     * Hapus Riwayat Penjualan Bebas
     */
    public function destroy($id)
    {
        $penjualan = DB::table('penjualan_bebas')->where('id', $id)->first();
        if ($penjualan) {
            // Kembalikan stok obat
            $details = DB::table('penjualan_bebas_detail')->where('no_nota', $penjualan->no_nota)->get();
            foreach ($details as $d) {
                DB::table('databarang_obat')->where('kode_brng', $d->kode_brng)->increment('stok', $d->jumlah);
            }
            DB::table('penjualan_bebas_detail')->where('no_nota', $penjualan->no_nota)->delete();
            DB::table('penjualan_bebas')->where('id', $id)->delete();
        }

        return redirect()->route('penjualan.bebas.index')->with('success', 'Data penjualan berhasil dihapus dan stok barang dikembalikan.');
    }
}

