<?php

namespace App\Http\Controllers;

use Hashids\Hashids;
use App\Models\Pasien;
use App\Models\SoapPasien;
use Illuminate\Http\Request;
use App\Helpers\TrackerHelper;
date_default_timezone_set('Asia/Jakarta');

class SoapController extends Controller
{
    // View Form SoapPasien
    public function soapPasien($id)
    {
       $hashid = new Hashids();
       $realId = $hashid->decodeHex($id);
       if (!$realId && is_numeric($id)) {
           $realId = $id;
       }
       $soapPasien = Pasien::find($realId);
       if (!$soapPasien) {
           abort(404, 'Data pasien tidak ditemukan');
       }

       // Ambil riwayat SOAP untuk pasien ini (bisa berkali-kali)
       $riwayatSoap = SoapPasien::where('no_rawat', $soapPasien->no_rawat)
           ->orderBy('tanggal_input_soap', 'desc')
           ->orderBy('jam_input_soap', 'desc')
           ->orderBy('id', 'desc')
           ->get();

       $dokters = \Illuminate\Support\Facades\DB::table('master_dokter')->where('status', '1')->get();
       $petugasList = \App\Models\User::select('id', 'name', 'email', 'role')->get();

       return view('formaddsoappasien', [
           'soapPasien' => $soapPasien, 
           'hashid' => $hashid,
           'riwayatSoap' => $riwayatSoap,
           'dokters' => $dokters,
           'petugasList' => $petugasList
       ]);
    }

    // Mesin Create Soap Pasien
    public function createSoapPasien(Request $request)
    {
        $request->validate([
            'no_rawat' => 'required'
        ]);
        $hashid = new Hashids();

        $tgl = $request->tanggal_input_soap ?: date('Y-m-d');
        $jam = $request->jam_input_soap ?: date('H:i:s');
        if (strlen($jam) == 5) {
            $jam .= ':00';
        }

        $soap = SoapPasien::create([
            'tanggal_input_soap' => $tgl,
            'jam_input_soap' => $jam,
            'no_rawat' => $request->no_rawat,
            'nip' => $request->nip,
            'nama_petugas' => $request->nama_petugas,
            'profesi' => $request->profesi,
            'suhu' => $request->suhu,
            'tensi' => $request->tensi,
            'respirasi' => $request->respirasi,
            'spo2' => $request->spo2,
            'gcs' => $request->gcs,
            'kesadaran' => $request->kesadaran ?: 'Compos Mentis',
            'alergi' => $request->alergi,
            'lingkar_perut' => $request->lingkar_perut,
            'nadi' => $request->nadi,
            'tinggi_badan' => $request->tinggi_badan,
            'berat_badan' => $request->berat_badan,
            'subjek' => $request->subjek,
            'objek' => $request->objek,
            'asesmen' => $request->asesmen,
            'plan' => $request->plan,
            'intruksi' => $request->intruksi,
            'evaluasi' => $request->evaluasi,
            'petugas' => $request->petugas ?: (auth()->check() ? auth()->user()->email : 'Admin'),
        ]);

        $pasien = Pasien::select('id', 'no_rawat', 'nama_lengkap')->where('no_rawat', $request->no_rawat)->first();
        TrackerHelper::log('SOAP / Pemeriksaan', 'Input SOAP', "Input catatan SOAP/SOAPIE pasien: " . ($pasien->nama_lengkap ?? '-') . " (No. Rawat: {$request->no_rawat})");

        return redirect()->route('soap', $hashid->encodeHex($pasien->id))->with('success', 'Catatan SOAP/SOAPIE berhasil disimpan.');
    }
    // View/get All Soap by Pasien (Riwayat Perawatan Lengkap Rekam Medis)
    public function soapPasienDetail($id)
    {
        $hashid = new Hashids();
        $realId = $hashid->decodeHex($id);
        if (!$realId && is_numeric($id)) {
            $realId = $id;
        }

        $pasien = Pasien::find($realId);
        if (!$pasien) {
            abort(404, 'Data pasien tidak ditemukan');
        }

        // Cari riwayat SOAP dan riwayat kunjungan berdasarkan No. RM pasien jika ada, atau fallback ke No. Rawat
        if (!empty($pasien->no_rkm_medis)) {
            $riwayatKunjungan = Pasien::where('no_rkm_medis', $pasien->no_rkm_medis)
                ->orderBy('tanggal_masuk', 'desc')
                ->orderBy('jam_masuk', 'desc')
                ->get();

            $soapPasien = SoapPasien::whereIn('no_rawat', function($query) use ($pasien) {
                $query->select('no_rawat')
                      ->from('pasiens')
                      ->where('no_rkm_medis', $pasien->no_rkm_medis);
            })
            ->orWhere('no_rawat', $pasien->no_rawat)
            ->orderBy('tanggal_input_soap', 'desc')
            ->orderBy('jam_input_soap', 'desc')
            ->get();
        } else {
            $riwayatKunjungan = Pasien::where('no_rawat', $pasien->no_rawat)->get();

            $soapPasien = SoapPasien::where('no_rawat', $pasien->no_rawat)
                ->orderBy('tanggal_input_soap', 'desc')
                ->orderBy('jam_input_soap', 'desc')
                ->get();
        }

        // Ambil riwayat hasil laboratorium pasien jika ada
        if (!empty($pasien->no_rkm_medis)) {
            $riwayatLab = \Illuminate\Support\Facades\DB::table('periksa_lab')
                ->whereIn('no_rawat', function($query) use ($pasien) {
                    $query->select('no_rawat')
                          ->from('pasiens')
                          ->where('no_rkm_medis', $pasien->no_rkm_medis);
                })
                ->orWhere('no_rawat', $pasien->no_rawat)
                ->orderBy('tgl_periksa', 'desc')
                ->get();
        } else {
            $riwayatLab = \Illuminate\Support\Facades\DB::table('periksa_lab')
                ->where('no_rawat', $pasien->no_rawat)
                ->orderBy('tgl_periksa', 'desc')
                ->get();
        }

        // Ambil riwayat pembelian obat pasien jika ada
        if (!empty($pasien->no_rkm_medis)) {
            $riwayatObat = \Illuminate\Support\Facades\DB::table('pembelian_obat')
                ->whereIn('no_rawat', function($query) use ($pasien) {
                    $query->select('no_rawat')
                          ->from('pasiens')
                          ->where('no_rkm_medis', $pasien->no_rkm_medis);
                })
                ->orWhere('no_rawat', $pasien->no_rawat)
                ->orderBy('tgl_beli', 'desc')
                ->get();
        } else {
            $riwayatObat = \Illuminate\Support\Facades\DB::table('pembelian_obat')
                ->where('no_rawat', $pasien->no_rawat)
                ->orderBy('tgl_beli', 'desc')
                ->get();
        }

        return view('soappasien', [
            'soapPasien' => $soapPasien, 
            'pasien' => $pasien, 
            'hashid' => $hashid,
            'riwayatKunjungan' => $riwayatKunjungan,
            'riwayatLab' => $riwayatLab,
            'riwayatObat' => $riwayatObat
        ]);
    }
    // Detail Soap From All Soap
    public function detailSoapPasien($id)
    {
        $hashid = new Hashids();
        $soapPasien = SoapPasien::select('pasiens.*',
                                        'soappasien.*',
                                        'pasiens.id AS id_pasein',
                                        'soappasien.id AS id_soap',
                                        'soappasien.petugas AS petugas_soap')
                        ->Join("pasiens", function($join){
                            $join->on('soappasien.no_rawat', '=', 'pasiens.no_rawat');})
                        ->where('soappasien.id', $hashid->decodeHex($id))->first();
        if (SoapPasien::where('id', $hashid->decodeHex($id))->exists()){
            return view('detailsoappasien', ['soapPasien'=>$soapPasien, 'hashid'=>$hashid]);
        }else{
            abort(404);
        }

    }
    // Delete 1 Soap From All soappasien
    public function deleteSoap($id)
    {
        $hashid = new Hashids();
        $realId = $hashid->decodeHex($id);
        if (!$realId && is_numeric($id)) {
            $realId = $id;
        }

        $deleteSoap = SoapPasien::find($realId);
        if ($deleteSoap) {
            $noRawat = $deleteSoap->no_rawat;
            TrackerHelper::log('SOAP / Pemeriksaan', 'Hapus SOAP', "Menghapus catatan SOAP tanggal: {$deleteSoap->tanggal_input_soap} (No. Rawat: {$noRawat})");
            $deleteSoap->delete();
            $pasien = Pasien::select('id', 'no_rawat')->where('no_rawat', $noRawat)->first();
            return redirect()->back()->with('successDelete', 'Berhasil menghapus catatan SOAP.');
        }
        return redirect()->back();
    }
    public function editSoapPasien(Request $request, $id)
    {
        $hashid = new Hashids();
        $realId = $hashid->decodeHex($id);
        $editSoapPasien = SoapPasien::find($realId);
        if ($editSoapPasien) {
            $editSoapPasien->update($request->all());
            TrackerHelper::log('SOAP / Pemeriksaan', 'Ubah SOAP', "Memperbarui catatan SOAP ID {$realId} (No. Rawat: {$editSoapPasien->no_rawat})");
        }
        $targetId = $request->id ? $hashid->decodeHex($request->id) : $realId;
        return redirect('detailSoapPasien/'.$hashid->encodeHex($targetId));
    }
}
