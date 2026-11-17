<?php

namespace App\Http\Controllers;

use Hashids\Hashids;
use App\Models\Padepokan;
use Illuminate\Http\Request;
use App\Helpers\TrackerHelper;

class PadepokanController extends Controller
{
    // View All data
    public function formPadepokan()
    {
        $hashid = new Hashids();
        $peserta = Padepokan::all();
        return view('padepokan.padepokan', ['peserta'=>$peserta, 'hashid'=>$hashid]);
    }

    // Tambah Data Peserta Padepokan
    public function addPesertaPadepokan(Request $request)
    {
        $peserta = Padepokan::create([
            'tanggal_masuk' => date('Y-m-d'),
            'nama_lengkap' => $request->nama_lengkap,
            'angkatan' => $request->angkatan,
            'jenjang' => $request->jenjang,
            'no_telpon' => $request->no_telpon,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'gol_darah' => $request->gol_darah,
            'kelurahan' => $request->kelurahan,
            'kecamatan' => $request->kecamatan,
            'kabupaten' => $request->kabupaten,
            'provinsi' => $request->provinsi,
            'alamat_lengkap' => $request->alamat_lengkap,
            'petugas' => $request->petugas,
        ]);

        TrackerHelper::log('Padepokan Cettol', 'Tambah Peserta', "Menambahkan peserta baru: {$peserta->nama_lengkap} (Angkatan: {$peserta->angkatan})");

        return redirect('/formPadepokan')->with('createSuccess','Data '.$request->nama_lengkap.' Berhasil disimpan!');
    }

    // EDIT PADEPOKAN
    public function editPeserta($id)
    {
        $hashid = new Hashids();
        $detailPeserta = Padepokan::find($hashid->decodeHex($id));
        if (Padepokan::where('id', $hashid->decodeHex($id))->exists()){
            return view('padepokan.formeditpadepokan', ['detailPeserta'=>$detailPeserta, 'hashid'=>$hashid]);
        }else{
            abort(404);
        }
    }
    public function editPesertaPadepokan(Request $request, $id)
    {
        $hashid = new Hashids();
        $realId = $hashid->decodeHex($id);
        $editPeserta = Padepokan::find($realId);
        if ($editPeserta) {
            $editPeserta->update($request->all());
            TrackerHelper::log('Padepokan Cettol', 'Ubah Peserta', "Mengubah data peserta: {$editPeserta->nama_lengkap}");
        }
        $targetId = $request->id ? $hashid->decodeHex($request->id) : $realId;
        return redirect('editPeserta/'.$hashid->encodeHex($targetId))->with('successEdit','Berhasil merubah data '.$request->nama_lengkap.'!!');
    }

    // DELETE PESERTA
    public function deletePeserta($id)
    {
        $hashid = new Hashids();
        $peserta = Padepokan::find($hashid->decodeHex($id));
        if ($peserta) {
            TrackerHelper::log('Padepokan Cettol', 'Hapus Peserta', "Menghapus peserta: {$peserta->nama_lengkap}");
            $peserta->delete();
        }
        return redirect('formPadepokan')->with('succsessDelete','Berhasil Menghapus Data Peserta');
    }
}
