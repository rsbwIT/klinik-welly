<?php

namespace App\Http\Controllers;

use App\Models\MasterDokter;
use App\Models\MasterPoli;
use App\Models\MasterPenjab;
use Hashids\Hashids;
use App\Models\Pasien;
use App\Models\SoapPasien;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\TrackerHelper;
date_default_timezone_set('Asia/Jakarta');
class PasienController extends Controller
{
    public function formPasien(Request $request)
    {
        $hashid = new Hashids();
        $no_rawat = Pasien::max('id');
        $no_rawatCount = $no_rawat + 1;
        $IDPengunjung = sprintf("%05s", $no_rawatCount).'/'.date('d/m/Y');

        // Hitung nomor registrasi urut hari ini
        $hariIni = date('Y-m-d');
        $countHariIni = Pasien::whereDate('tanggal_masuk', $hariIni)->count();
        $noReg = sprintf("%03d", $countHariIni + 1);

        $dokters = MasterDokter::where('status', '1')->get();
        $polis = MasterPoli::where('status', '1')->get();
        $penjabs = MasterPenjab::where('status', '1')->get();

        // Ambil daftar registrasi pasien (Khanza Input Data)
        $tglAwal = $request->get('tgl_awal', date('Y-m-d'));
        $tglAkhir = $request->get('tgl_akhir', date('Y-m-d'));
        $keyword = $request->get('keyword', '');

        $query = Pasien::query();
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $query->whereBetween('tanggal_masuk', [$tglAwal, $tglAkhir]);
        } else {
            $query->whereDate('tanggal_masuk', date('Y-m-d'));
        }

        if (!empty($keyword)) {
            $query->where(function($w) use ($keyword) {
                $w->where('no_rawat', 'LIKE', "%{$keyword}%")
                  ->orWhere('no_rkm_medis', 'LIKE', "%{$keyword}%")
                  ->orWhere('nama_lengkap', 'LIKE', "%{$keyword}%")
                  ->orWhere('dokter_dituju', 'LIKE', "%{$keyword}%")
                  ->orWhere('unit_poli', 'LIKE', "%{$keyword}%");
            });
        }

        $daftarRegistrasi = $query->orderBy('id', 'desc')->paginate(50);

        return view('formaddpasien', [
            'IDPengunjung' => $IDPengunjung,
            'noReg' => $noReg,
            'dokters' => $dokters,
            'polis' => $polis,
            'penjabs' => $penjabs,
            'daftarRegistrasi' => $daftarRegistrasi,
            'hashid' => $hashid,
            'tglAwal' => $tglAwal,
            'tglAkhir' => $tglAkhir,
            'keyword' => $keyword
        ]);
    }

    public function cariMasterPasien(Request $request)
    {
        $q = $request->get('q', '');
        if (empty($q)) {
            // Ambil 10 pasien terbaru unik
            $pasien = Pasien::select('id', 'nama_lengkap', 'no_rawat', 'no_telpon', 'jenis_kelamin', 'alamat_lengkap')
                ->whereNotNull('nama_lengkap')
                ->where('nama_lengkap', '!=', '')
                ->orderBy('id', 'desc')
                ->take(15)
                ->get();
        } else {
            $pasien = Pasien::select('id', 'nama_lengkap', 'no_rawat', 'no_telpon', 'jenis_kelamin', 'alamat_lengkap')
                ->where('nama_lengkap', 'LIKE', "%{$q}%")
                ->orWhere('no_rawat', 'LIKE', "%{$q}%")
                ->orWhere('no_telpon', 'LIKE', "%{$q}%")
                ->orderBy('id', 'desc')
                ->take(25)
                ->get();
        }
        return response()->json($pasien);
    }

    public function getDetailMasterPasien($id)
    {
        $pasien = Pasien::find($id);
        if (!$pasien) {
            return response()->json(['error' => 'Pasien tidak ditemukan'], 404);
        }
        return response()->json($pasien);
    }

    public function createPasien(Request $request)
    {
        $request->validate([
            'no_rawat' => 'required'
        ]);
        $pasien = Pasien::create([
            'tanggal_masuk' => $request->tanggal_masuk,
            'jam_masuk' => $request->jam_masuk,
            'no_reg' => $request->no_reg,
            'no_rkm_medis' => $request->no_rkm_medis,
            'no_rawat' => $request->no_rawat,
            'kd_dokter' => $request->kd_dokter,
            'dokter_dituju' => $request->dokter_dituju,
            'kd_poli' => $request->kd_poli,
            'unit_poli' => $request->unit_poli,
            'p_jawab' => $request->p_jawab,
            'almt_pj' => $request->almt_pj,
            'hubunganpj' => $request->hubunganpj,
            'kd_pj' => $request->kd_pj,
            'jenis_bayar' => $request->jenis_bayar ?? 'UMUM',
            'no_kartu' => $request->no_kartu,
            'status_poli' => $request->status_poli ?? 'Baru',
            'asal_rujukan' => $request->asal_rujukan,
            'nama_lengkap' => $request->nama_lengkap,
            'no_telpon' => $request->no_telpon,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'agama' => $request->agama,
            'nama_ibu' => $request->nama_ibu,
            'gol_darah' => $request->gol_darah,
            'kelurahan' => $request->kelurahan,
            'kecamatan' => $request->kecamatan,
            'kabupaten' => $request->kabupaten,
            'provinsi' => $request->provinsi,
            'alamat_lengkap' => $request->alamat_lengkap,
            'petugas' => $request->petugas,
        ]);

        TrackerHelper::log('Registrasi Pasien', 'Pendaftaran Baru', "Registrasi kunjungan pasien: {$pasien->nama_lengkap} (No. Rawat: {$pasien->no_rawat}, No. RM: {$pasien->no_rkm_medis})");

        return redirect()->route('rawatjalan.index')->with('createSuccess', 'Registrasi Rawat Jalan '.$request->nama_lengkap.' Berhasil disimpan!');
    }
    public function detailPasien($id)
    {
        $hashid = new Hashids();
        $detailPasien = Pasien::find($hashid->decodeHex($id));
        if (Pasien::where('id', $hashid->decodeHex($id))->exists()){
            return view('detailpasien', ['detailPasien'=>$detailPasien, 'hashid'=>$hashid]);
        }else{
            abort(404);
        }
    }

    public function deletePasien($id)
    {
        $hashid = new Hashids();
        $realId = $hashid->decodeHex($id);
        if (!$realId && is_numeric($id)) {
            $realId = $id;
        }

        $deletePasien = Pasien::find($realId);
        if ($deletePasien) {
            $noRawat = $deletePasien->no_rawat;
            $nama = $deletePasien->nama_lengkap;
            TrackerHelper::log('Registrasi Pasien', 'Hapus Kunjungan', "Menghapus data kunjungan pasien: {$nama} (No. Rawat: {$noRawat})");
            SoapPasien::where('no_rawat', $noRawat)->delete();
            \Illuminate\Support\Facades\DB::table('diagnosa_pasien')->where('no_rawat', $noRawat)->delete();
            \Illuminate\Support\Facades\DB::table('resume_pasien')->where('no_rawat', $noRawat)->delete();
            $deletePasien->delete();
            return redirect()->back()->with('deleteSuccess', 'Data registrasi kunjungan pasien ' . $nama . ' berhasil dihapus!');
        }
        return redirect()->back()->with('deleteSuccess', 'Data Berhasil dihapus!');
    }

    public function editPasien(Request $request, $id)
    {
        $hashid = new Hashids();
        $realId = $hashid->decodeHex($id);
        if (!$realId && is_numeric($id)) {
            $realId = $id;
        }

        $editPasien = Pasien::find($realId);
        if ($editPasien) {
            $editPasien->update($request->all());
            TrackerHelper::log('Registrasi Pasien', 'Ubah Biodata', "Mengubah data pasien: {$editPasien->nama_lengkap} (No. Rawat: {$editPasien->no_rawat})");
        }

        if ($request->has('from_registrasi')) {
            return redirect()->route('registrasi.pasien')->with('createSuccess', 'Data registrasi pasien ' . $request->nama_lengkap . ' berhasil diperbarui!');
        }

        return redirect('detailPasien/'.$hashid->encodeHex($realId))->with('successEditPasien','Berhasil merubah data pasien '.$request->nama_lengkap.'!!');
    }
}
