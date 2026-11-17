<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\TrackerHelper;

class MutasiObatController extends Controller
{
    /**
     * Tampilkan Halaman Mutasi Stok Obat & Alkes Antar Depo / Bangsal
     * Persis 100% Interface SIMRS Khanza DlgMutasiBarang
     */
    public function index(Request $request)
    {
        $depoList = DB::table('bangsal')->where('status', '1')->orderBy('kd_bangsal')->get();
        
        $dariDepo = $request->input('dari', 'GUDANG');
        $keDepo   = $request->input('ke', 'DepoRJ');

        $depoDariObj = DB::table('bangsal')->where('kd_bangsal', $dariDepo)->first() ?? $depoList->first();
        $depoKeObj   = DB::table('bangsal')->where('kd_bangsal', $keDepo)->first() ?? $depoList->last();

        // Query daftar obat beserta stok di depo asal dan depo tujuan
        $query = DB::table('databarang_obat as o')
            ->leftJoin('gudangbarang as g_dari', function($join) use ($dariDepo) {
                $join->on('g_dari.kode_brng', '=', 'o.kode_brng')
                     ->where('g_dari.kd_bangsal', '=', $dariDepo);
            })
            ->leftJoin('gudangbarang as g_ke', function($join) use ($keDepo) {
                $join->on('g_ke.kode_brng', '=', 'o.kode_brng')
                     ->where('g_ke.kd_bangsal', '=', $keDepo);
            })
            ->where('o.status', '1')
            ->select(
                'o.kode_brng',
                'o.nama_brng',
                'o.kode_sat',
                'o.h_beli',
                'o.ralan',
                'o.kategori',
                DB::raw('COALESCE(g_dari.stok, 0) as stok_asal'),
                DB::raw('COALESCE(g_ke.stok, 0) as stok_tujuan'),
                DB::raw('COALESCE(g_dari.no_batch, "-") as no_batch'),
                DB::raw('COALESCE(g_dari.no_faktur, "-") as no_faktur')
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

        return view('farmasi.mutasi_obat', compact('obatList', 'depoList', 'dariDepo', 'keDepo', 'depoDariObj', 'depoKeObj'));
    }

    /**
     * Proses Simpan Mutasi Stok Obat Batch (Persis DlgMutasiBarang Khanza)
     */
    public function store(Request $request)
    {
        $request->validate([
            'kd_bangsaldari' => 'required',
            'kd_bangsalke' => 'required|different:kd_bangsaldari',
            'items' => 'required|array|min:1',
        ]);

        $dari = $request->kd_bangsaldari;
        $ke   = $request->kd_bangsalke;
        $ket  = $request->keterangan ?: 'Mutasi Antar Gudang / Bangsal Farmasi';
        $user = Auth::user()->name ?? 'Petugas Farmasi';
        $tgl  = $request->tanggal ? date('Y-m-d H:i:s', strtotime($request->tanggal)) : now();

        DB::beginTransaction();
        try {
            $jumlahTermutasi = 0;
            $mutasiLog = [];

            foreach ($request->items as $item) {
                $jml = floatval($item['jml'] ?? 0);
                if ($jml <= 0) continue;

                $kode = $item['kode_brng'];
                $harga = floatval($item['harga'] ?? 0);
                $batch = $item['no_batch'] ?? '-';
                $faktur = $item['no_faktur'] ?? '-';

                // Cek stok asal di gudangbarang
                $stokAsalObj = DB::table('gudangbarang')
                    ->where('kode_brng', $kode)
                    ->where('kd_bangsal', $dari)
                    ->first();

                $stokAsal = $stokAsalObj ? floatval($stokAsalObj->stok) : 0;
                if ($stokAsal < $jml) {
                    DB::rollBack();
                    return redirect()->back()->with('error', "Gagal mutasi obat {$kode}! Stok di {$dari} tidak mencukupi (Tersedia: {$stokAsal}, Dimutasi: {$jml}).");
                }

                // 1. Kurangi stok di depo asal
                DB::table('gudangbarang')
                    ->where('kode_brng', $kode)
                    ->where('kd_bangsal', $dari)
                    ->decrement('stok', $jml);

                // 2. Tambah stok di depo tujuan
                $adaTujuan = DB::table('gudangbarang')
                    ->where('kode_brng', $kode)
                    ->where('kd_bangsal', $ke)
                    ->first();

                if ($adaTujuan) {
                    DB::table('gudangbarang')
                        ->where('kode_brng', $kode)
                        ->where('kd_bangsal', $ke)
                        ->increment('stok', $jml);
                } else {
                    DB::table('gudangbarang')->insert([
                        'kode_brng' => $kode,
                        'kd_bangsal' => $ke,
                        'stok' => $jml,
                        'no_batch' => $batch,
                        'no_faktur' => $faktur,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                // 3. Catat di tabel mutasibarang standar SIMRS Khanza
                DB::table('mutasibarang')->insert([
                    'kode_brng' => $kode,
                    'jml' => $jml,
                    'harga' => $harga,
                    'kd_bangsaldari' => $dari,
                    'kd_bangsalke' => $ke,
                    'tanggal' => $tgl,
                    'keterangan' => $ket,
                    'no_batch' => $batch,
                    'no_faktur' => $faktur,
                    'petugas' => $user,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $jumlahTermutasi++;
                $mutasiLog[] = "{$kode} ({$jml})";
            }

            if ($jumlahTermutasi === 0) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Silakan isi jumlah obat pada kolom [Jml] yang ingin dimutasi terlebih dahulu.');
            }

            DB::commit();

            TrackerHelper::log('Farmasi', 'Mutasi Obat Khanza', "Mutasi {$jumlahTermutasi} jenis obat dari {$dari} ke {$ke}");
            return redirect()->route('farmasi.mutasi.index', ['dari' => $dari, 'ke' => $ke])
                ->with('success', "Mutasi {$jumlahTermutasi} item obat dari {$dari} ke {$ke} berhasil disimpan!");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
