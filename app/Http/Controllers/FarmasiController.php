<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hashids\Hashids;
use App\Helpers\TrackerHelper;

class FarmasiController extends Controller
{
    private $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids();
    }

    private function resolvePasienId($id)
    {
        $realId = $this->hashids->decodeHex($id);
        if (!$realId && is_numeric($id)) {
            $realId = $id;
        }
        return $realId;
    }

    /**
     * Tampilkan Halaman Pembelian / Penjualan Obat untuk Pasien Terpilih
     */
    public function index($id)
    {
        $realId = $this->resolvePasienId($id);
        $pasien = DB::table('pasiens')->where('id', $realId)->first();
        if (!$pasien) {
            abort(404, 'Data kunjungan pasien tidak ditemukan');
        }

        // Ambil daftar master obat/balutan luka modern
        $obatList = DB::table('databarang_obat')
            ->where('status', '1')
            ->orderBy('kategori', 'asc')
            ->orderBy('nama_brng', 'asc')
            ->get();

        // Ambil riwayat pembelian obat pasien ini (berdasarkan No. Rawat atau No. RM)
        $query = DB::table('pembelian_obat')->where('no_rawat', $pasien->no_rawat);
        if (!empty($pasien->no_rkm_medis)) {
            $query->orWhere('no_rkm_medis', $pasien->no_rkm_medis);
        }
        $riwayatBeli = $query->orderBy('tgl_beli', 'desc')->orderBy('jam_beli', 'desc')->get();

        // Generate No. Faktur Otomatis: FKT-YYYYMMDD-XXXX
        $todayPrefix = 'FKT-' . date('Ymd') . '-';
        $lastFaktur = DB::table('pembelian_obat')
            ->where('no_faktur', 'LIKE', "{$todayPrefix}%")
            ->orderBy('no_faktur', 'desc')
            ->value('no_faktur');

        $nextSeq = 1;
        if ($lastFaktur) {
            $parts = explode('-', $lastFaktur);
            $nextSeq = intval(end($parts)) + 1;
        }
        $noFaktur = $todayPrefix . sprintf('%04d', $nextSeq);

        return view('farmasi.index', [
            'pasien' => $pasien,
            'obatList' => $obatList,
            'riwayatBeli' => $riwayatBeli,
            'noFaktur' => $noFaktur,
            'hashId' => $id,
            'hashids' => $this->hashids
        ]);
    }

    /**
     * Simpan Transaksi Pembelian Obat / Balutan Luka Pasien
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_faktur' => 'required',
            'no_rawat' => 'required',
            'items' => 'required|array|min:1'
        ]);

        $noFaktur = $request->no_faktur;
        $noRawat = $request->no_rawat;
        $pasien = DB::table('pasiens')->where('no_rawat', $noRawat)->first();

        $tglBeli = $request->tgl_beli ?? date('Y-m-d');
        $jamBeli = $request->jam_beli ?? date('H:i:s');
        $petugas = $request->petugas ?? (auth()->user()->name ?? 'Petugas Farmasi');
        $catatan = $request->catatan ?? '-';

        $totalBayar = 0;

        foreach ($request->items as $item) {
            if (empty($item['kode_brng']) || empty($item['jumlah']) || $item['jumlah'] <= 0) {
                continue;
            }

            $brng = DB::table('databarang_obat')->where('kode_brng', $item['kode_brng'])->first();
            if (!$brng) continue;

            $harga = $item['biaya_obat'] ?? $brng->ralan;
            $subtotal = $harga * $item['jumlah'];
            $totalBayar += $subtotal;

            DB::table('detail_pembelian_obat')->insert([
                'no_faktur' => $noFaktur,
                'kode_brng' => $brng->kode_brng,
                'nama_brng' => $brng->nama_brng,
                'satuan' => $brng->kode_sat,
                'aturan_pakai' => $item['aturan_pakai'] ?? '3 x 1 Hari',
                'jumlah' => $item['jumlah'],
                'biaya_obat' => $harga,
                'total' => $subtotal
            ]);

            // Potong stok obat
            DB::table('databarang_obat')
                ->where('kode_brng', $brng->kode_brng)
                ->decrement('stok', $item['jumlah']);
        }

        // Simpan Header Pembelian Obat
        DB::table('pembelian_obat')->insert([
            'no_faktur' => $noFaktur,
            'no_rawat' => $noRawat,
            'no_rkm_medis' => $pasien->no_rkm_medis ?? null,
            'tgl_beli' => $tglBeli,
            'jam_beli' => $jamBeli,
            'total_bayar' => $totalBayar,
            'petugas' => $petugas,
            'catatan' => $catatan,
            'created_at' => now()
        ]);

        TrackerHelper::log('Farmasi & Obat', 'Pembelian Obat', "Transaksi obat ($noFaktur) untuk pasien: " . ($pasien->nama_lengkap ?? '-') . " Total: Rp " . number_format($totalBayar, 0, ',', '.'));

        return redirect()->back()->with('createSuccess', "Nota pembelian obat ($noFaktur) berhasil disimpan!");
    }

    /**
     * Cetak Lembar Nota / Bukti Pembelian Obat & Resep
     */
    public function cetakNota($fakturId)
    {
        $pembelian = DB::table('pembelian_obat')->where('no_faktur', $fakturId)->first();
        if (!$pembelian) {
            abort(404, 'Data transaksi faktur obat tidak ditemukan');
        }

        $pasien = DB::table('pasiens')->where('no_rawat', $pembelian->no_rawat)->first();
        $details = DB::table('detail_pembelian_obat')->where('no_faktur', $fakturId)->get();

        return view('farmasi.cetak_nota', [
            'pembelian' => $pembelian,
            'pasien' => $pasien,
            'details' => $details
        ]);
    }
}

