<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pasien;
use App\Models\MasterDokter;
use App\Models\SoapPasien;
use Hashids\Hashids;
use App\Helpers\TrackerHelper;

class SuratController extends Controller
{
    protected $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids();
    }

    /**
     * Dashboard Utama Modul Persuratan
     */
    public function index()
    {
        $totalRujukan = DB::table('surat_rujukan')->count();
        $totalEkspedisi = DB::table('buku_ekspedisi')->count();
        $totalAskep = DB::table('asuhan_keperawatan')->count();
        $totalPelimpahan = DB::table('pelimpahan_wewenang')->count();
        $totalSakit = DB::table('surat_sakit')->count();
        $totalSehat = DB::table('surat_sehat')->count();
        $totalObservasi = DB::table('lembar_observasi_khusus')->count();
        $totalPuskesmas = DB::table('pelaporan_puskesmas')->count();
        $totalBukuMerja = DB::table('buku_merja_klien')->count();

        $latestRujukan = DB::table('surat_rujukan')->orderBy('id', 'desc')->limit(5)->get();
        $latestEkspedisi = DB::table('buku_ekspedisi')->orderBy('id', 'desc')->limit(5)->get();
        $latestAskep = DB::table('asuhan_keperawatan')->orderBy('id', 'desc')->limit(5)->get();
        $latestPelimpahan = DB::table('pelimpahan_wewenang')->orderBy('id', 'desc')->limit(5)->get();
        $latestSakit = DB::table('surat_sakit')->orderBy('id', 'desc')->limit(5)->get();
        $latestSehat = DB::table('surat_sehat')->orderBy('id', 'desc')->limit(5)->get();

        return view('surat.index', [
            'totalRujukan' => $totalRujukan,
            'totalEkspedisi' => $totalEkspedisi,
            'totalAskep' => $totalAskep,
            'totalPelimpahan' => $totalPelimpahan,
            'totalSakit' => $totalSakit,
            'totalSehat' => $totalSehat,
            'totalObservasi' => $totalObservasi,
            'totalPuskesmas' => $totalPuskesmas,
            'totalBukuMerja' => $totalBukuMerja,
            'latestRujukan' => $latestRujukan,
            'latestEkspedisi' => $latestEkspedisi,
            'latestAskep' => $latestAskep,
            'latestPelimpahan' => $latestPelimpahan,
            'latestSakit' => $latestSakit,
            'latestSehat' => $latestSehat
        ]);
    }

    // ==========================================
    // 1. SURAT RUJUKAN PASIEN
    // ==========================================
    public function rujukanIndex(Request $request)
    {
        $query = DB::table('surat_rujukan');
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('no_surat', 'LIKE', "%{$q}%")
                  ->orWhere('nama_pasien', 'LIKE', "%{$q}%")
                  ->orWhere('tujuan_faskes', 'LIKE', "%{$q}%");
            });
        }
        $data = $query->orderBy('id', 'desc')->paginate(15);

        // Auto nomor surat
        $countThisYear = DB::table('surat_rujukan')->whereYear('created_at', date('Y'))->count() + 1;
        $noSuratOtomatis = sprintf("%03d", $countThisYear) . '/PMK-W/' . date('m') . '/' . date('Y');

        return view('surat.rujukan_index', [
            'data' => $data,
            'noSuratOtomatis' => $noSuratOtomatis
        ]);
    }

    public function rujukanStore(Request $request)
    {
        $request->validate([
            'nama_pasien' => 'required',
            'tujuan_faskes' => 'required'
        ]);

        $id = DB::table('surat_rujukan')->insertGetId([
            'no_surat' => $request->no_surat,
            'pasien_id' => $request->pasien_id,
            'no_rawat' => $request->no_rawat,
            'no_rkm_medis' => $request->no_rkm_medis,
            'nama_pasien' => $request->nama_pasien,
            'umur' => $request->umur,
            'tgl_lahir' => $request->tgl_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'tujuan_faskes' => $request->tujuan_faskes,
            'tgl_pemeriksaan' => $request->tgl_pemeriksaan ?: date('Y-m-d'),
            'keluhan_utama' => $request->keluhan_utama,
            'riwayat_penyakit' => $request->riwayat_penyakit,
            'tensi' => $request->tensi,
            'nadi' => $request->nadi,
            'respirasi' => $request->respirasi,
            'suhu' => $request->suhu,
            'spo2' => $request->spo2,
            'pemeriksaan_lainnya' => $request->pemeriksaan_lainnya,
            'alasan_rujukan' => $request->alasan_rujukan,
            'tindakan_diberikan' => $request->tindakan_diberikan,
            'tgl_surat' => $request->tgl_surat ?: date('Y-m-d'),
            'nama_perawat' => $request->nama_perawat ?: 'Welly Prestikasari, S.Kep., Ners., M.Kep.',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        TrackerHelper::log('Persuratan', 'Buat Surat Rujukan', "Membuat surat rujukan pasien {$request->nama_pasien} ke {$request->tujuan_faskes}");

        return redirect()->route('surat.rujukan.index')->with('success', 'Surat rujukan berhasil disimpan!');
    }

    public function rujukanCetak($id)
    {
        $surat = DB::table('surat_rujukan')->where('id', $id)->first();
        if (!$surat) abort(404);

        return view('surat.rujukan_cetak', ['surat' => $surat]);
    }

    public function rujukanDestroy($id)
    {
        DB::table('surat_rujukan')->where('id', $id)->delete();
        return redirect()->route('surat.rujukan.index')->with('success', 'Surat rujukan berhasil dihapus!');
    }

    // ==========================================
    // 2. BUKU EKSPEDISI SURAT
    // ==========================================
    public function ekspedisiIndex(Request $request)
    {
        $query = DB::table('buku_ekspedisi');
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('nama_penerima', 'LIKE', "%{$q}%")
                  ->orWhere('instansi', 'LIKE', "%{$q}%")
                  ->orWhere('maksud_tujuan', 'LIKE', "%{$q}%");
            });
        }
        $data = $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->paginate(20);

        return view('surat.ekspedisi_index', ['data' => $data]);
    }

    public function ekspedisiStore(Request $request)
    {
        $request->validate([
            'nama_penerima' => 'required',
            'instansi' => 'required',
            'maksud_tujuan' => 'required'
        ]);

        DB::table('buku_ekspedisi')->insert([
            'tanggal' => $request->tanggal ?: date('Y-m-d'),
            'nama_penerima' => $request->nama_penerima,
            'jabatan' => $request->jabatan,
            'instansi' => $request->instansi,
            'maksud_tujuan' => $request->maksud_tujuan,
            'tanda_tangan' => $request->tanda_tangan ?: 'Sudah Diterima',
            'keterangan' => $request->keterangan,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        TrackerHelper::log('Persuratan', 'Catat Ekspedisi', "Mencatat ekspedisi kepada {$request->nama_penerima} ({$request->instansi})");

        return redirect()->route('surat.ekspedisi.index')->with('success', 'Catatan buku ekspedisi berhasil disimpan!');
    }

    public function ekspedisiCetak(Request $request)
    {
        $data = DB::table('buku_ekspedisi')->orderBy('tanggal', 'asc')->get();
        return view('surat.ekspedisi_cetak', ['data' => $data]);
    }

    public function ekspedisiDestroy($id)
    {
        DB::table('buku_ekspedisi')->where('id', $id)->delete();
        return redirect()->route('surat.ekspedisi.index')->with('success', 'Catatan ekspedisi berhasil dihapus!');
    }

    // ==========================================
    // 3. FORMULIR ASUHAN KEPERAWATAN (ASKEP)
    // ==========================================
    public function askepIndex(Request $request)
    {
        $query = DB::table('asuhan_keperawatan');
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('nama_klien', 'LIKE', "%{$q}%")
                  ->orWhere('nomor_register', 'LIKE', "%{$q}%")
                  ->orWhere('dx_keperawatan', 'LIKE', "%{$q}%");
            });
        }
        $data = $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->paginate(15);

        // Cek jika diarahkan dari Klik Kanan Pasien (query param pasien_id / hashid)
        $selectedPasien = null;
        if ($request->filled('pasien_id')) {
            $pId = $request->pasien_id;
            if (!is_numeric($pId)) {
                $dec = $this->hashids->decodeHex($pId);
                $pId = $dec ? $dec : $pId;
            }
            $selectedPasien = Pasien::find($pId);
        }

        return view('surat.askep_index', [
            'data' => $data,
            'selectedPasien' => $selectedPasien
        ]);
    }

    public function askepStore(Request $request)
    {
        $request->validate([
            'nama_klien' => 'required',
            'keluhan' => 'required'
        ]);

        DB::table('asuhan_keperawatan')->insert([
            'pasien_id' => $request->pasien_id,
            'no_rawat' => $request->no_rawat,
            'nomor_register' => $request->nomor_register,
            'nama_klien' => $request->nama_klien,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'penyakit_dahulu' => $request->penyakit_dahulu,
            'alergi' => $request->alergi,
            'obat_rutin' => $request->obat_rutin,
            'tanggal' => $request->tanggal ?: date('Y-m-d'),
            'keluhan' => $request->keluhan,
            'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
            'td' => $request->td,
            'hr' => $request->hr,
            'rr' => $request->rr,
            'suhu' => $request->suhu,
            'dx_keperawatan' => $request->dx_keperawatan,
            'intervensi' => $request->intervensi,
            'implementasi' => $request->implementasi,
            'evaluasi' => $request->evaluasi,
            'ttd_nama' => $request->ttd_nama ?: 'Welly Prestikasari, S.Kep., Ners., M.Kep.',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        TrackerHelper::log('Persuratan', 'Catat Asuhan Keperawatan', "Mencatat asuhan keperawatan klien: {$request->nama_klien}");

        return redirect()->route('surat.askep.index')->with('success', 'Formulir Asuhan Keperawatan berhasil disimpan!');
    }

    public function askepCetak($id)
    {
        $askep = DB::table('asuhan_keperawatan')->where('id', $id)->first();
        if (!$askep) abort(404);

        return view('surat.askep_cetak', ['askep' => $askep]);
    }

    public function askepDestroy($id)
    {
        DB::table('asuhan_keperawatan')->where('id', $id)->delete();
        return redirect()->route('surat.askep.index')->with('success', 'Data asuhan keperawatan berhasil dihapus!');
    }

    // ==========================================
    // 4. SURAT PELIMPAHAN WEWENANG MEDIS
    // ==========================================
    public function pelimpahanIndex(Request $request)
    {
        $query = DB::table('pelimpahan_wewenang');
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('nama_dokter', 'LIKE', "%{$q}%")
                  ->orWhere('no_surat', 'LIKE', "%{$q}%");
            });
        }
        $data = $query->orderBy('id', 'desc')->paginate(15);
        $dokters = DB::table('master_dokter')->where('status', '1')->get();

        return view('surat.pelimpahan_index', [
            'data' => $data,
            'dokters' => $dokters
        ]);
    }

    public function pelimpahanStore(Request $request)
    {
        $request->validate([
            'nama_dokter' => 'required',
            'tindakan_1' => 'required'
        ]);

        DB::table('pelimpahan_wewenang')->insert([
            'no_surat' => $request->no_surat ?: ('PW-' . date('Ymd') . '-' . rand(100, 999)),
            'jenis_pelimpahan' => $request->jenis_pelimpahan ?: 'Delegatif/Mandat',
            'nama_dokter' => $request->nama_dokter,
            'sip_dokter' => $request->sip_dokter,
            'alamat_praktik_dokter' => $request->alamat_praktik_dokter,
            'nama_perawat' => $request->nama_perawat ?: 'Welly Prestikasari, S.Kep., Ners., M.Kep.',
            'str_perawat' => $request->str_perawat ?: '180152120-3129482',
            'sipp_perawat' => $request->sipp_perawat ?: '503/36/SIPP/IV.14/VIII/2021',
            'tindakan_1' => $request->tindakan_1,
            'tindakan_2' => $request->tindakan_2,
            'tindakan_3' => $request->tindakan_3,
            'tgl_surat' => $request->tgl_surat ?: date('Y-m-d'),
            'tempat_surat' => $request->tempat_surat ?: 'Bandar Lampung',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        TrackerHelper::log('Persuratan', 'Buat Pelimpahan Wewenang', "Mencatat surat pelimpahan wewenang medis dari {$request->nama_dokter}");

        return redirect()->route('surat.pelimpahan.index')->with('success', 'Surat Pelimpahan Wewenang berhasil disimpan!');
    }

    public function pelimpahanCetak($id)
    {
        $surat = DB::table('pelimpahan_wewenang')->where('id', $id)->first();
        if (!$surat) abort(404);

        return view('surat.pelimpahan_cetak', ['surat' => $surat]);
    }

    public function pelimpahanDestroy($id)
    {
        DB::table('pelimpahan_wewenang')->where('id', $id)->delete();
        return redirect()->route('surat.pelimpahan.index')->with('success', 'Surat pelimpahan wewenang berhasil dihapus!');
    }

    // ==========================================
    // 5. SURAT KETERANGAN SAKIT
    // ==========================================
    public function sakitIndex(Request $request)
    {
        $query = DB::table('surat_sakit');
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('no_surat', 'LIKE', "%{$q}%")
                  ->orWhere('nama_pasien', 'LIKE', "%{$q}%")
                  ->orWhere('diagnosa', 'LIKE', "%{$q}%");
            });
        }
        $listSakit = $query->orderBy('id', 'desc')->paginate(15);
        $pasienList = Pasien::orderBy('id', 'desc')->limit(100)->get();

        // Cek jika diarahkan dari Klik Kanan Pasien (query param pasien_id / hashid)
        $selectedPasien = null;
        if ($request->filled('pasien_id')) {
            $pId = $request->pasien_id;
            if (!is_numeric($pId)) {
                $dec = $this->hashids->decodeHex($pId);
                $pId = $dec ? $dec : $pId;
            }
            $selectedPasien = Pasien::find($pId);
        }

        // Generate nomor surat default: SKI/YYYY/MM/0001
        $count = DB::table('surat_sakit')->whereYear('created_at', date('Y'))->count() + 1;
        $defaultNoSurat = sprintf("SKS/PMKW/%s/%04d", date('Y/m'), $count);

        return view('surat.sakit_index', [
            'listSakit' => $listSakit,
            'pasienList' => $pasienList,
            'defaultNoSurat' => $defaultNoSurat,
            'selectedPasien' => $selectedPasien
        ]);
    }

    public function sakitStore(Request $request)
    {
        $request->validate([
            'no_surat' => 'required',
            'nama_pasien' => 'required',
            'lama_istirahat_hari' => 'required|numeric',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
        ]);

        $id = DB::table('surat_sakit')->insertGetId([
            'no_surat' => $request->no_surat,
            'pasien_id' => $request->pasien_id,
            'nama_pasien' => $request->nama_pasien,
            'umur' => $request->umur,
            'jenis_kelamin' => $request->jenis_kelamin,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
            'diagnosa' => $request->diagnosa,
            'lama_istirahat_hari' => $request->lama_istirahat_hari,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'nama_pemeriksa' => $request->nama_pemeriksa ?: 'Welly Prestikasari, S.Kep., Ners., M.Kep., CWCCA',
            'sipp_pemeriksa' => $request->sipp_pemeriksa ?: '503/36/SIPP/IV.14/VIII/2021',
            'lokasi_surat' => $request->lokasi_surat ?: 'Bandar Lampung',
            'tanggal_surat' => $request->tanggal_surat ?: date('Y-m-d'),
            'catatan' => $request->catatan,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        TrackerHelper::log('Persuratan', 'Buat Surat Sakit', "Membuat surat keterangan sakit no {$request->no_surat} untuk {$request->nama_pasien}");

        return redirect()->route('surat.sakit.index')->with('success', 'Surat Keterangan Sakit berhasil diterbitkan!');
    }

    public function sakitCetak($id)
    {
        $surat = DB::table('surat_sakit')->where('id', $id)->first();
        if (!$surat) abort(404);

        return view('surat.sakit_cetak', ['surat' => $surat]);
    }

    public function sakitDestroy($id)
    {
        DB::table('surat_sakit')->where('id', $id)->delete();
        return redirect()->route('surat.sakit.index')->with('success', 'Surat keterangan sakit berhasil dihapus!');
    }

    // ==========================================
    // 6. SURAT KETERANGAN SEHAT
    // ==========================================
    public function sehatIndex(Request $request)
    {
        $query = DB::table('surat_sehat');
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('no_surat', 'LIKE', "%{$q}%")
                  ->orWhere('nama_pasien', 'LIKE', "%{$q}%")
                  ->orWhere('keperluan', 'LIKE', "%{$q}%");
            });
        }
        $listSehat = $query->orderBy('id', 'desc')->paginate(15);
        $pasienList = Pasien::orderBy('id', 'desc')->limit(100)->get();

        // Cek jika diarahkan dari Klik Kanan Pasien (query param pasien_id / hashid)
        $selectedPasien = null;
        if ($request->filled('pasien_id')) {
            $pId = $request->pasien_id;
            if (!is_numeric($pId)) {
                $dec = $this->hashids->decodeHex($pId);
                $pId = $dec ? $dec : $pId;
            }
            $selectedPasien = Pasien::find($pId);
        }

        // Generate nomor surat default: SKH/YYYY/MM/0001
        $count = DB::table('surat_sehat')->whereYear('created_at', date('Y'))->count() + 1;
        $defaultNoSurat = sprintf("SKH/PMKW/%s/%04d", date('Y/m'), $count);

        return view('surat.sehat_index', [
            'listSehat' => $listSehat,
            'pasienList' => $pasienList,
            'defaultNoSurat' => $defaultNoSurat,
            'selectedPasien' => $selectedPasien
        ]);
    }

    public function sehatStore(Request $request)
    {
        $request->validate([
            'no_surat' => 'required',
            'nama_pasien' => 'required',
            'keperluan' => 'required',
        ]);

        $id = DB::table('surat_sehat')->insertGetId([
            'no_surat' => $request->no_surat,
            'pasien_id' => $request->pasien_id,
            'nama_pasien' => $request->nama_pasien,
            'umur' => $request->umur,
            'jenis_kelamin' => $request->jenis_kelamin,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
            'tinggi_badan' => $request->tinggi_badan,
            'berat_badan' => $request->berat_badan,
            'golongan_darah' => $request->golongan_darah,
            'tensi' => $request->tensi,
            'nadi' => $request->nadi,
            'respirasi' => $request->respirasi,
            'suhu' => $request->suhu,
            'buta_warna' => $request->buta_warna ?: 'Tidak Buta Warna',
            'kesimpulan' => $request->kesimpulan ?: 'SEHAT',
            'keperluan' => $request->keperluan,
            'nama_pemeriksa' => $request->nama_pemeriksa ?: 'Welly Prestikasari, S.Kep., Ners., M.Kep., CWCCA',
            'sipp_pemeriksa' => $request->sipp_pemeriksa ?: '503/36/SIPP/IV.14/VIII/2021',
            'lokasi_surat' => $request->lokasi_surat ?: 'Bandar Lampung',
            'tanggal_surat' => $request->tanggal_surat ?: date('Y-m-d'),
            'catatan' => $request->catatan,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        TrackerHelper::log('Persuratan', 'Buat Surat Sehat', "Membuat surat keterangan sehat no {$request->no_surat} untuk {$request->nama_pasien}");

        return redirect()->route('surat.sehat.index')->with('success', 'Surat Keterangan Sehat berhasil diterbitkan!');
    }

    public function sehatCetak($id)
    {
        $surat = DB::table('surat_sehat')->where('id', $id)->first();
        if (!$surat) abort(404);

        return view('surat.sehat_cetak', ['surat' => $surat]);
    }

    public function sehatDestroy($id)
    {
        DB::table('surat_sehat')->where('id', $id)->delete();
        return redirect()->route('surat.sehat.index')->with('success', 'Surat keterangan sehat berhasil dihapus!');
    }

    // ==========================================
    // 7. LEMBAR OBSERVASI KHUSUS
    // ==========================================
    public function observasiIndex(Request $request)
    {
        $query = DB::table('lembar_observasi_khusus');
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('nama_pasien', 'LIKE', "%{$q}%")
                  ->orWhere('no_rawat', 'LIKE', "%{$q}%");
            });
        }
        $data = $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->paginate(15);

        $selectedPasien = null;
        if ($request->filled('pasien_id')) {
            $pId = $request->pasien_id;
            if (!is_numeric($pId)) {
                $dec = $this->hashids->decodeHex($pId);
                $pId = $dec ? $dec : $pId;
            }
            $selectedPasien = Pasien::find($pId);
        }

        return view('surat.observasi_index', [
            'data' => $data,
            'selectedPasien' => $selectedPasien
        ]);
    }

    public function observasiStore(Request $request)
    {
        $request->validate(['nama_pasien' => 'required']);

        $id = DB::table('lembar_observasi_khusus')->insertGetId([
            'pasien_id' => $request->pasien_id,
            'no_rawat' => $request->no_rawat,
            'no_rkm_medis' => $request->no_rkm_medis,
            'nama_pasien' => $request->nama_pasien,
            'jenis_kelamin' => $request->jenis_kelamin,
            'umur' => $request->umur,
            'alamat' => $request->alamat,
            'tanggal' => $request->tanggal ?: date('Y-m-d'),
            'jam' => $request->jam ?: date('H:i:s'),
            'kesadaran' => $request->kesadaran ?: 'Compos Mentis',
            'gcs' => $request->gcs,
            'td' => $request->td,
            'hr' => $request->hr,
            'rr' => $request->rr,
            'suhu' => $request->suhu,
            'spo2' => $request->spo2,
            'cairan_infus' => $request->cairan_infus,
            'tetesan_infus' => $request->tetesan_infus,
            'urine_output' => $request->urine_output,
            'kondisi_luka' => $request->kondisi_luka,
            'tindakan_terapi' => $request->tindakan_terapi,
            'catatan_perkembangan' => $request->catatan_perkembangan,
            'petugas' => $request->petugas ?: 'Welly Prestikasari, S.Kep., Ners., M.Kep.',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        TrackerHelper::log('Persuratan', 'Catat Observasi Khusus', "Mencatat lembar observasi khusus pasien: {$request->nama_pasien}");
        return redirect()->route('surat.observasi.index')->with('success', 'Lembar Observasi Khusus berhasil disimpan!');
    }

    public function observasiCetak($id)
    {
        $obs = DB::table('lembar_observasi_khusus')->where('id', $id)->first();
        if (!$obs) abort(404);
        return view('surat.observasi_cetak', ['obs' => $obs]);
    }

    public function observasiDestroy($id)
    {
        DB::table('lembar_observasi_khusus')->where('id', $id)->delete();
        return redirect()->route('surat.observasi.index')->with('success', 'Lembar observasi berhasil dihapus!');
    }

    // ==========================================
    // 8. FORM PELAPORAN KE PUSKESMAS
    // ==========================================
    public function puskesmasIndex(Request $request)
    {
        $query = DB::table('pelaporan_puskesmas');
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('no_laporan', 'LIKE', "%{$q}%")
                  ->orWhere('nama_puskesmas', 'LIKE', "%{$q}%");
            });
        }
        $data = $query->orderBy('tanggal_lapor', 'desc')->orderBy('id', 'desc')->paginate(15);

        // Auto hitung statistik bulan berjalan
        $startMonth = date('Y-m-01');
        $endMonth = date('Y-m-t');
        $autoBaru = Pasien::whereBetween('tanggal_masuk', [$startMonth, $endMonth])->where('status_poli', 'Baru')->count();
        $autoLama = Pasien::whereBetween('tanggal_masuk', [$startMonth, $endMonth])->where('status_poli', 'Lama')->count();
        $autoTotal = Pasien::whereBetween('tanggal_masuk', [$startMonth, $endMonth])->count();

        $defaultNoLaporan = sprintf("LAP-PKM/%s/%03d", date('Y/m'), DB::table('pelaporan_puskesmas')->count() + 1);

        return view('surat.puskesmas_index', [
            'data' => $data,
            'autoBaru' => $autoBaru,
            'autoLama' => $autoLama,
            'autoTotal' => $autoTotal,
            'defaultNoLaporan' => $defaultNoLaporan
        ]);
    }

    public function puskesmasStore(Request $request)
    {
        $request->validate(['nama_puskesmas' => 'required', 'no_laporan' => 'required']);

        $id = DB::table('pelaporan_puskesmas')->insertGetId([
            'no_laporan' => $request->no_laporan,
            'nama_puskesmas' => $request->nama_puskesmas,
            'bulan_tahun' => $request->bulan_tahun ?: date('F Y'),
            'tanggal_lapor' => $request->tanggal_lapor ?: date('Y-m-d'),
            'total_pasien_baru' => (int) $request->total_pasien_baru,
            'total_pasien_lama' => (int) $request->total_pasien_lama,
            'total_kunjungan' => (int) $request->total_kunjungan,
            'kasus_dm_luka' => (int) $request->kasus_dm_luka,
            'kasus_hipertensi' => (int) $request->kasus_hipertensi,
            'kasus_infeksi' => (int) $request->kasus_infeksi,
            'kasus_bedah_minor' => (int) $request->kasus_bedah_minor,
            'kasus_lainnya' => (int) $request->kasus_lainnya,
            'pasien_rujuk' => (int) $request->pasien_rujuk,
            'keterangan' => $request->keterangan,
            'penanggung_jawab' => $request->penanggung_jawab ?: 'Welly Prestikasari, S.Kep., Ners., M.Kep.',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        TrackerHelper::log('Persuratan', 'Laporan Puskesmas', "Menyimpan laporan bulanan puskesmas: {$request->no_laporan}");
        return redirect()->route('surat.puskesmas.index')->with('success', 'Formulir Pelaporan Puskesmas berhasil disimpan!');
    }

    public function puskesmasCetak($id)
    {
        $lap = DB::table('pelaporan_puskesmas')->where('id', $id)->first();
        if (!$lap) abort(404);
        return view('surat.puskesmas_cetak', ['lap' => $lap]);
    }

    public function puskesmasDestroy($id)
    {
        DB::table('pelaporan_puskesmas')->where('id', $id)->delete();
        return redirect()->route('surat.puskesmas.index')->with('success', 'Laporan puskesmas berhasil dihapus!');
    }

    // ==========================================
    // 9. BUKU MERJA KLIEN
    // ==========================================
    public function bukuMerjaIndex(Request $request)
    {
        $query = DB::table('buku_merja_klien');
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($w) use ($q) {
                $w->where('nama_klien', 'LIKE', "%{$q}%")
                  ->orWhere('no_rawat', 'LIKE', "%{$q}%")
                  ->orWhere('jenis_luka', 'LIKE', "%{$q}%");
            });
        }
        $data = $query->orderBy('tgl_mulai_rawat', 'desc')->orderBy('id', 'desc')->paginate(15);

        $selectedPasien = null;
        if ($request->filled('pasien_id')) {
            $pId = $request->pasien_id;
            if (!is_numeric($pId)) {
                $dec = $this->hashids->decodeHex($pId);
                $pId = $dec ? $dec : $pId;
            }
            $selectedPasien = Pasien::find($pId);
        }

        return view('surat.bukumerja_index', [
            'data' => $data,
            'selectedPasien' => $selectedPasien
        ]);
    }

    public function bukuMerjaStore(Request $request)
    {
        $request->validate(['nama_klien' => 'required', 'tgl_mulai_rawat' => 'required']);

        $id = DB::table('buku_merja_klien')->insertGetId([
            'pasien_id' => $request->pasien_id,
            'no_rawat' => $request->no_rawat,
            'no_rkm_medis' => $request->no_rkm_medis,
            'nama_klien' => $request->nama_klien,
            'tgl_mulai_rawat' => $request->tgl_mulai_rawat,
            'tgl_selesai_rawat' => $request->tgl_selesai_rawat,
            'diagnosa_medis' => $request->diagnosa_medis,
            'jenis_luka' => $request->jenis_luka,
            'derajat_luka' => $request->derajat_luka,
            'tujuan_target_perawatan' => $request->tujuan_target_perawatan,
            'evaluasi_akhir' => $request->evaluasi_akhir,
            'status_perawatan' => $request->status_perawatan ?: 'Aktif / Masih Rawat',
            'catatan' => $request->catatan,
            'penanggung_jawab' => $request->penanggung_jawab ?: 'Welly Prestikasari, S.Kep., Ners., M.Kep.',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        TrackerHelper::log('Persuratan', 'Buku Merja Klien', "Mencatat buku merja klien: {$request->nama_klien}");
        return redirect()->route('surat.bukumerja.index')->with('success', 'Catatan Buku Merja Klien berhasil disimpan!');
    }

    public function bukuMerjaCetak($id)
    {
        $merja = DB::table('buku_merja_klien')->where('id', $id)->first();
        if (!$merja) abort(404);
        return view('surat.bukumerja_cetak', ['merja' => $merja]);
    }

    public function bukuMerjaDestroy($id)
    {
        DB::table('buku_merja_klien')->where('id', $id)->delete();
        return redirect()->route('surat.bukumerja.index')->with('success', 'Data buku merja klien berhasil dihapus!');
    }
}

