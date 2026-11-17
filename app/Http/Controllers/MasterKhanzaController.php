<?php

namespace App\Http\Controllers;

use App\Models\MasterDokter;
use App\Models\MasterPoli;
use App\Models\MasterPenjab;
use App\Helpers\TrackerHelper;
use Illuminate\Http\Request;

class MasterKhanzaController extends Controller
{
    // ================= DOKTER =================
    public function dokterIndex()
    {
        $dokters = MasterDokter::orderBy('id', 'asc')->get();
        return view('master.dokter_index', compact('dokters'));
    }

    public function dokterStore(Request $request)
    {
        $request->validate([
            'kd_dokter' => 'required|unique:master_dokter,kd_dokter',
            'nm_dokter' => 'required',
        ]);

        $dokter = MasterDokter::create([
            'kd_dokter' => $request->kd_dokter,
            'nm_dokter' => $request->nm_dokter,
            'jk' => $request->jk ?? 'L',
            'spesialis' => $request->spesialis,
            'no_telp' => $request->no_telp,
            'status' => '1',
        ]);

        TrackerHelper::log('Master Data', 'Tambah Dokter', "Menambahkan dokter baru: {$dokter->nm_dokter} ({$dokter->kd_dokter})");
        return redirect()->back()->with('success', 'Data dokter berhasil ditambahkan!');
    }

    public function dokterUpdate(Request $request, $id)
    {
        $dokter = MasterDokter::findOrFail($id);
        $dokter->update([
            'nm_dokter' => $request->nm_dokter,
            'jk' => $request->jk ?? 'L',
            'spesialis' => $request->spesialis,
            'no_telp' => $request->no_telp,
            'status' => $request->status ?? '1',
        ]);

        TrackerHelper::log('Master Data', 'Edit Dokter', "Mengubah data dokter: {$dokter->nm_dokter} ({$dokter->kd_dokter})");
        return redirect()->back()->with('success', 'Data dokter berhasil diperbarui!');
    }

    public function dokterDestroy($id)
    {
        $dokter = MasterDokter::findOrFail($id);
        $nama = $dokter->nm_dokter;
        $dokter->delete();

        TrackerHelper::log('Master Data', 'Hapus Dokter', "Menghapus dokter: {$nama}");
        return redirect()->back()->with('success', 'Data dokter berhasil dihapus!');
    }

    // ================= POLIKLINIK =================
    public function poliIndex()
    {
        $polis = MasterPoli::orderBy('id', 'asc')->get();
        return view('master.poli_index', compact('polis'));
    }

    public function poliStore(Request $request)
    {
        $request->validate([
            'kd_poli' => 'required|unique:master_poli,kd_poli',
            'nm_poli' => 'required',
        ]);

        $poli = MasterPoli::create([
            'kd_poli' => $request->kd_poli,
            'nm_poli' => $request->nm_poli,
            'status' => '1',
        ]);

        TrackerHelper::log('Master Data', 'Tambah Poliklinik', "Menambahkan poli baru: {$poli->nm_poli} ({$poli->kd_poli})");
        return redirect()->back()->with('success', 'Data poliklinik berhasil ditambahkan!');
    }

    public function poliUpdate(Request $request, $id)
    {
        $poli = MasterPoli::findOrFail($id);
        $poli->update([
            'nm_poli' => $request->nm_poli,
            'status' => $request->status ?? '1',
        ]);

        return redirect()->back()->with('success', 'Data poliklinik berhasil diperbarui!');
    }

    public function poliDestroy($id)
    {
        $poli = MasterPoli::findOrFail($id);
        $poli->delete();
        return redirect()->back()->with('success', 'Data poliklinik berhasil dihapus!');
    }

    // ================= PENJAB / ASURANSI =================
    public function penjabIndex()
    {
        $penjabs = MasterPenjab::orderBy('id', 'asc')->get();
        return view('master.penjab_index', compact('penjabs'));
    }

    public function penjabStore(Request $request)
    {
        $request->validate([
            'kd_pj' => 'required|unique:master_penjab,kd_pj',
            'png_jawab' => 'required',
        ]);

        MasterPenjab::create([
            'kd_pj' => $request->kd_pj,
            'png_jawab' => $request->png_jawab,
            'nama_perusahaan' => $request->nama_perusahaan,
            'status' => '1',
        ]);

        return redirect()->back()->with('success', 'Data penjamin/asuransi berhasil ditambahkan!');
    }

    public function penjabUpdate(Request $request, $id)
    {
        $penjab = MasterPenjab::findOrFail($id);
        $penjab->update([
            'png_jawab' => $request->png_jawab,
            'nama_perusahaan' => $request->nama_perusahaan,
            'status' => $request->status ?? '1',
        ]);

        return redirect()->back()->with('success', 'Data penjamin berhasil diperbarui!');
    }

    public function penjabDestroy($id)
    {
        $penjab = MasterPenjab::findOrFail($id);
        $penjab->delete();
        return redirect()->back()->with('success', 'Data penjamin berhasil dihapus!');
    }

    // ================= API UNTUK FORM REGISTRASI =================
    public function apiDokter()
    {
        $dokters = MasterDokter::where('status', '1')->get();
        return response()->json($dokters);
    }

    public function apiPoli()
    {
        $polis = MasterPoli::where('status', '1')->get();
        return response()->json($polis);
    }

    public function apiPenjab()
    {
        $penjabs = MasterPenjab::where('status', '1')->get();
        return response()->json($penjabs);
    }

    // ================= JADWAL PRAKTEK DOKTER =================
    public function jadwalIndex(Request $request)
    {
        $query = \Illuminate\Support\Facades\DB::table('jadwal as j')
            ->leftJoin('master_dokter as d', 'j.kd_dokter', '=', 'd.kd_dokter')
            ->leftJoin('master_poli as p', 'j.kd_poli', '=', 'p.kd_poli')
            ->select('j.*', 'd.nm_dokter', 'p.nm_poli');

        if ($request->filled('keyword')) {
            $kw = $request->keyword;
            $query->where(function($q) use ($kw) {
                $q->where('j.kd_dokter', 'like', "%{$kw}%")
                  ->orWhere('d.nm_dokter', 'like', "%{$kw}%")
                  ->orWhere('p.nm_poli', 'like', "%{$kw}%")
                  ->orWhere('j.hari_kerja', 'like', "%{$kw}%");
            });
        }

        $jadwals = $query->orderByRaw("FIELD(j.hari_kerja, 'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'AKHAD')")
            ->orderBy('j.jam_mulai', 'asc')
            ->get();

        $dokters = MasterDokter::where('status', '1')->get();
        $polis = MasterPoli::where('status', '1')->get();

        return view('master.jadwal_index', compact('jadwals', 'dokters', 'polis'));
    }

    public function jadwalStore(Request $request)
    {
        $request->validate([
            'kd_dokter' => 'required',
            'hari_kerja' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kd_poli' => 'required',
        ]);

        \Illuminate\Support\Facades\DB::table('jadwal')->updateOrInsert(
            [
                'kd_dokter' => $request->kd_dokter,
                'hari_kerja' => $request->hari_kerja,
                'jam_mulai' => $request->jam_mulai,
            ],
            [
                'jam_selesai' => $request->jam_selesai,
                'kd_poli' => $request->kd_poli,
                'kuota' => $request->kuota ?? 40,
            ]
        );

        TrackerHelper::log('Master Data', 'Simpan Jadwal', "Simpan jadwal dokter: {$request->kd_dokter} hari {$request->hari_kerja}");
        return redirect()->back()->with('success', 'Jadwal praktek dokter berhasil disimpan!');
    }

    public function jadwalDestroy(Request $request)
    {
        $kd_dokter = $request->kd_dokter;
        $hari_kerja = $request->hari_kerja;
        $jam_mulai = $request->jam_mulai;

        \Illuminate\Support\Facades\DB::table('jadwal')
            ->where('kd_dokter', $kd_dokter)
            ->where('hari_kerja', $hari_kerja)
            ->where('jam_mulai', $jam_mulai)
            ->delete();

        TrackerHelper::log('Master Data', 'Hapus Jadwal', "Hapus jadwal dokter: {$kd_dokter} hari {$hari_kerja}");
        return redirect()->back()->with('success', 'Jadwal praktek dokter berhasil dihapus!');
    }

    // ================= MASTER OBAT & ALKES =================
    public function obatIndex(Request $request)
    {
        $query = \Illuminate\Support\Facades\DB::table('databarang_obat');
        
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('kode_brng', 'LIKE', "%{$q}%")
                  ->orWhere('nama_brng', 'LIKE', "%{$q}%")
                  ->orWhere('kategori', 'LIKE', "%{$q}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $obats = $query->orderBy('kategori', 'asc')->orderBy('nama_brng', 'asc')->get();

        // Ambil data stok per depo untuk setiap obat yang ditampilkan di halaman ini
        $kodeList = $obats->pluck('kode_brng')->toArray();
        $stokGudang = \Illuminate\Support\Facades\DB::table('gudangbarang')
            ->whereIn('kode_brng', $kodeList)
            ->get();
        
        $stokPerDepo = [];
        foreach ($stokGudang as $sg) {
            $stokPerDepo[$sg->kode_brng][$sg->kd_bangsal] = $sg->stok;
        }

        // Ambil daftar depo bangsal aktif
        $depoList = \Illuminate\Support\Facades\DB::table('bangsal')
            ->where('status', '1')
            ->orderBy('kd_bangsal', 'asc')
            ->get();

        // Ambil daftar kategori unik
        $kategoriList = \Illuminate\Support\Facades\DB::table('databarang_obat')
            ->distinct()
            ->pluck('kategori')
            ->filter();

        // Generate kode barang baru otomatis: OBT001, OBT002...
        $lastObat = \Illuminate\Support\Facades\DB::table('databarang_obat')
            ->where('kode_brng', 'LIKE', 'OBT%')
            ->orderBy('kode_brng', 'desc')
            ->first();

        $nextNum = 1;
        if ($lastObat && preg_match('/OBT(\d+)/', $lastObat->kode_brng, $matches)) {
            $nextNum = (int)$matches[1] + 1;
        }
        $autoKode = sprintf("OBT%03d", $nextNum);

        // Ambil daftar industri farmasi (I.F.)
        $industriList = \Illuminate\Support\Facades\DB::table('industrifarmasi')
            ->orderBy('nama_industri', 'asc')
            ->get();

        // Ambil daftar kategori barang
        $kategoriKhanza = \Illuminate\Support\Facades\DB::table('kategori_barang')
            ->orderBy('nama', 'asc')
            ->get();

        // Ambil daftar golongan barang
        $golonganKhanza = \Illuminate\Support\Facades\DB::table('golongan_barang')
            ->orderBy('nama', 'asc')
            ->get();

        // Ambil daftar kode satuan
        $satuanKhanza = \Illuminate\Support\Facades\DB::table('kodesatuan')
            ->orderBy('satuan', 'asc')
            ->get();

        return view('master.obat_index', compact('obats', 'kategoriList', 'autoKode', 'depoList', 'stokPerDepo', 'industriList', 'kategoriKhanza', 'golonganKhanza', 'satuanKhanza'));
    }

    public function obatStore(Request $request)
    {
        $request->validate([
            'kode_brng' => 'required',
            'nama_brng' => 'required',
            'ralan' => 'required|numeric',
        ]);

        // Cek apakah kode barang sudah ada
        $exists = \Illuminate\Support\Facades\DB::table('databarang_obat')->where('kode_brng', $request->kode_brng)->first();
        if ($exists) {
            return redirect()->back()->with('error', "Kode obat {$request->kode_brng} sudah digunakan!");
        }

        $stokAwal = $request->stok ?: 0;

        \Illuminate\Support\Facades\DB::table('databarang_obat')->insert([
            'kd_bangsal' => 'AP',
            'kode_brng' => $request->kode_brng,
            'nama_brng' => $request->nama_brng,
            'kode_satbesar' => $request->kode_satbesar ?: 'Box',
            'kode_sat' => $request->kode_sat ?: 'Pcs',
            'letak_barang' => $request->letak_barang ?: '-',
            'isi' => $request->isi ?: 1,
            'kapasitas' => $request->kapasitas ?: 1,
            'kandungan' => $request->kandungan ?: '-',
            'golongan' => $request->golongan ?: 'Obat Bebas',
            'dasar' => $request->dasar ?: ($request->h_beli ?: 0),
            'h_beli' => $request->h_beli ?: 0,
            'ralan' => $request->ralan ?: 0,
            'kelas1' => $request->kelas1 ?: $request->ralan,
            'kelas2' => $request->kelas2 ?: $request->ralan,
            'kelas3' => $request->kelas3 ?: $request->ralan,
            'utama' => $request->utama ?: $request->ralan,
            'vip' => $request->vip ?: $request->ralan,
            'vvip' => $request->vvip ?: $request->ralan,
            'beliluar' => $request->beliluar ?: $request->ralan,
            'jualbebas' => $request->jualbebas ?: $request->ralan,
            'karyawan' => $request->karyawan ?: $request->ralan,
            'stokminimal' => $request->stokminimal ?: 10,
            'stok' => $stokAwal,
            'kategori' => $request->kategori ?: 'Obat Oral',
            'kode_industri' => $request->kode_industri ?: '-',
            'expire' => $request->expire ?: date('Y-12-31', strtotime('+1 year')),
            'status' => '1',
        ]);

        // Inisialisasi stok awal di gudangbarang per depo
        $depoList = \Illuminate\Support\Facades\DB::table('bangsal')->where('status', '1')->get();
        foreach ($depoList as $dp) {
            $stokDepo = ($dp->kd_bangsal === 'DepoRJ' || $dp->kd_bangsal === 'AP') ? $stokAwal : 0;
            \Illuminate\Support\Facades\DB::table('gudangbarang')->updateOrInsert(
                ['kode_brng' => $request->kode_brng, 'kd_bangsal' => $dp->kd_bangsal],
                ['stok' => $stokDepo, 'no_batch' => '-', 'no_faktur' => '-']
            );
        }

        TrackerHelper::log('Master Data', 'Tambah Obat', "Menambahkan obat baru: {$request->nama_brng} ({$request->kode_brng}) Rp " . number_format($request->ralan));
        return redirect()->back()->with('success', "Obat {$request->nama_brng} berhasil ditambahkan dan dialokasikan ke depo!");
    }

    public function obatUpdate(Request $request, $kode)
    {
        $request->validate([
            'nama_brng' => 'required',
            'ralan' => 'required|numeric',
        ]);

        \Illuminate\Support\Facades\DB::table('databarang_obat')
            ->where('kode_brng', $kode)
            ->update([
                'nama_brng' => $request->nama_brng,
                'kode_satbesar' => $request->kode_satbesar ?: 'Box',
                'kode_sat' => $request->kode_sat ?: 'Pcs',
                'letak_barang' => $request->letak_barang ?: '-',
                'isi' => $request->isi ?: 1,
                'kapasitas' => $request->kapasitas ?: 1,
                'kandungan' => $request->kandungan ?: '-',
                'golongan' => $request->golongan ?: 'Obat Bebas',
                'dasar' => $request->dasar ?: ($request->h_beli ?: 0),
                'h_beli' => $request->h_beli ?: 0,
                'ralan' => $request->ralan ?: 0,
                'kelas1' => $request->kelas1 ?: $request->ralan,
                'kelas2' => $request->kelas2 ?: $request->ralan,
                'kelas3' => $request->kelas3 ?: $request->ralan,
                'utama' => $request->utama ?: $request->ralan,
                'vip' => $request->vip ?: $request->ralan,
                'vvip' => $request->vvip ?: $request->ralan,
                'beliluar' => $request->beliluar ?: $request->ralan,
                'jualbebas' => $request->jualbebas ?: $request->ralan,
                'karyawan' => $request->karyawan ?: $request->ralan,
                'stokminimal' => $request->stokminimal ?: 10,
                'kode_industri' => $request->kode_industri ?: '-',
                'kategori' => $request->kategori ?: 'Obat Oral',
                'expire' => $request->expire ?: null,
                'status' => $request->status ?? '1',
            ]);

        // Jika user juga menginput stok depo langsung di modal edit
        if ($request->has('stok_depo') && is_array($request->stok_depo)) {
            $totalStok = 0;
            foreach ($request->stok_depo as $kdBangsal => $stk) {
                $stkVal = max(0, floatval($stk));
                $totalStok += $stkVal;
                \Illuminate\Support\Facades\DB::table('gudangbarang')->updateOrInsert(
                    ['kode_brng' => $kode, 'kd_bangsal' => $kdBangsal],
                    ['stok' => $stkVal]
                );
            }
            \Illuminate\Support\Facades\DB::table('databarang_obat')
                ->where('kode_brng', $kode)
                ->update(['stok' => $totalStok]);
        }

        TrackerHelper::log('Master Data', 'Update Obat', "Mengupdate obat: {$request->nama_brng} ({$kode})");
        return redirect()->back()->with('success', "Data obat {$request->nama_brng} berhasil diperbarui!");
    }

    /**
     * Ambil data stok obat di semua depo (AJAX)
     */
    public function getStokDepo($kode)
    {
        $obat = \Illuminate\Support\Facades\DB::table('databarang_obat')->where('kode_brng', $kode)->first();
        if (!$obat) {
            return response()->json(['status' => 'error', 'message' => 'Obat tidak ditemukan'], 404);
        }

        $depoList = \Illuminate\Support\Facades\DB::table('bangsal')->where('status', '1')->orderBy('kd_bangsal')->get();
        $gudang = \Illuminate\Support\Facades\DB::table('gudangbarang')->where('kode_brng', $kode)->get()->keyBy('kd_bangsal');

        $result = [];
        foreach ($depoList as $dp) {
            $result[] = [
                'kd_bangsal' => $dp->kd_bangsal,
                'nm_bangsal' => $dp->nm_bangsal,
                'stok' => isset($gudang[$dp->kd_bangsal]) ? $gudang[$dp->kd_bangsal]->stok : 0,
                'no_batch' => isset($gudang[$dp->kd_bangsal]) ? $gudang[$dp->kd_bangsal]->no_batch : '-',
                'no_faktur' => isset($gudang[$dp->kd_bangsal]) ? $gudang[$dp->kd_bangsal]->no_faktur : '-',
            ];
        }

        return response()->json([
            'status' => 'success',
            'kode_brng' => $obat->kode_brng,
            'nama_brng' => $obat->nama_brng,
            'satuan' => $obat->kode_sat ?: 'Pcs',
            'total_stok' => $obat->stok,
            'depos' => $result,
        ]);
    }

    /**
     * Simpan pembaruan stok obat per depo (AJAX / Form)
     */
    public function updateStokDepo(Request $request)
    {
        $request->validate([
            'kode_brng' => 'required',
            'stok' => 'required|array',
        ]);

        $kode = $request->kode_brng;
        $totalStok = 0;

        foreach ($request->stok as $kdBangsal => $val) {
            $stokVal = max(0, floatval($val));
            $totalStok += $stokVal;

            \Illuminate\Support\Facades\DB::table('gudangbarang')->updateOrInsert(
                ['kode_brng' => $kode, 'kd_bangsal' => $kdBangsal],
                ['stok' => $stokVal]
            );
        }

        // Sinkronkan ke total stok master obat
        \Illuminate\Support\Facades\DB::table('databarang_obat')
            ->where('kode_brng', $kode)
            ->update(['stok' => $totalStok]);

        TrackerHelper::log('Master Data', 'Update Stok Depo', "Memperbarui stok obat per depo untuk: {$kode} (Total: {$totalStok})");

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Stok obat per depo berhasil diperbarui!',
                'total_stok' => $totalStok,
            ]);
        }

        return redirect()->back()->with('success', 'Stok obat per masing-masing depo berhasil disimpan!');
    }

    public function obatDestroy($kode)
    {
        $obat = \Illuminate\Support\Facades\DB::table('databarang_obat')->where('kode_brng', $kode)->first();
        if ($obat) {
            \Illuminate\Support\Facades\DB::table('databarang_obat')->where('kode_brng', $kode)->delete();
            TrackerHelper::log('Master Data', 'Hapus Obat', "Menghapus obat: {$obat->nama_brng} ({$kode})");
            return redirect()->back()->with('success', "Obat {$obat->nama_brng} berhasil dihapus!");
        }
        return redirect()->back()->with('error', 'Data obat tidak ditemukan!');
    }
}

