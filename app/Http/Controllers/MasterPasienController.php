<?php

namespace App\Http\Controllers;

use Hashids\Hashids;
use App\Models\MasterPasien;
use Illuminate\Http\Request;
use App\Helpers\TrackerHelper;

class MasterPasienController extends Controller
{
    // Halaman Index Master Pasien (Data Rekam Medis)
    public function index(Request $request)
    {
        $hashid = new Hashids();
        $query = MasterPasien::query();

        if ($request->filled('keyword')) {
            $kw = $request->keyword;
            $query->where(function($q) use ($kw) {
                $q->where('nm_pasien', 'LIKE', "%{$kw}%")
                  ->orWhere('no_rkm_medis', 'LIKE', "%{$kw}%")
                  ->orWhere('no_ktp', 'LIKE', "%{$kw}%")
                  ->orWhere('no_tlp', 'LIKE', "%{$kw}%");
            });
        }

        $masterPasien = $query->orderBy('no_rkm_medis', 'desc')->paginate(25);
        $totalPasien = MasterPasien::count();

        return view('masterpasien.index', compact('masterPasien', 'hashid', 'totalPasien'));
    }

    // Form Tambah Pasien Baru ke Master RM (Khanza Style)
    public function create(Request $request)
    {
        $hashid = new Hashids();
        // Auto generate No RM 6 digit
        $lastRm = MasterPasien::max('no_rkm_medis');
        $nextRmNum = $lastRm ? (intval($lastRm) + 1) : 1;
        $no_rkm_medis = sprintf("%06d", $nextRmNum);

        $limit = $request->get('limit', 100);
        $query = MasterPasien::query();

        if ($request->filled('keyword')) {
            $kw = $request->keyword;
            $query->where(function($q) use ($kw) {
                $q->where('nm_pasien', 'LIKE', "%{$kw}%")
                  ->orWhere('no_rkm_medis', 'LIKE', "%{$kw}%")
                  ->orWhere('no_ktp', 'LIKE', "%{$kw}%")
                  ->orWhere('no_tlp', 'LIKE', "%{$kw}%")
                  ->orWhere('alamat', 'LIKE', "%{$kw}%");
            });
        }

        if ($request->filled('alamat')) {
            $query->where('alamat', 'LIKE', "%{$request->alamat}%");
        }

        $listPasien = $query->orderBy('no_rkm_medis', 'desc')->take($limit)->get();
        $totalPasien = MasterPasien::count();

        return view('masterpasien.create', compact('no_rkm_medis', 'listPasien', 'hashid', 'totalPasien', 'limit'));
    }

    // Simpan Pasien Baru ke Master
    public function store(Request $request)
    {
        $request->validate([
            'no_rkm_medis' => 'required|unique:master_pasien,no_rkm_medis',
            'nm_pasien' => 'required|string|max:100',
            'jk' => 'required|in:L,P',
        ]);

        $data = $request->all();
        if (empty($data['tgl_daftar'])) {
            $data['tgl_daftar'] = date('Y-m-d');
        }

        $master = MasterPasien::create($data);
        TrackerHelper::log('Master Rekam Medis', 'Tambah Master Pasien', "Mendaftarkan Master Pasien baru: {$master->nm_pasien} (No. RM: {$master->no_rkm_medis})");

        return redirect()->route('masterPasien.index')->with('createSuccess', 'Master Rekam Medis Pasien: '.$request->nm_pasien.' (No. RM: '.$request->no_rkm_medis.') berhasil didaftarkan!');
    }

    // Cetak Kartu Pasien (Barcode Khanza Style)
    public function cetakKartu($id)
    {
        $hashid = new Hashids();
        $pasienId = is_numeric($id) ? $id : $hashid->decodeHex($id);
        $pasien = MasterPasien::findOrFail($pasienId);

        return view('masterpasien.kartu', compact('pasien'));
    }

    // Detail Pasien Master
    public function show($id)
    {
        $hashid = new Hashids();
        $pasienId = is_numeric($id) ? $id : $hashid->decodeHex($id);
        $pasien = MasterPasien::findOrFail($pasienId);

        return view('masterpasien.show', compact('pasien', 'hashid'));
    }

    // Form Edit Master Pasien
    public function edit($id)
    {
        $hashid = new Hashids();
        $pasienId = is_numeric($id) ? $id : $hashid->decodeHex($id);
        $pasien = MasterPasien::findOrFail($pasienId);

        return view('masterpasien.edit', compact('pasien', 'hashid'));
    }

    // Update Master Pasien
    public function update(Request $request, $id)
    {
        $hashid = new Hashids();
        $pasienId = is_numeric($id) ? $id : $hashid->decodeHex($id);
        $pasien = MasterPasien::findOrFail($pasienId);

        $request->validate([
            'no_rkm_medis' => 'required|unique:master_pasien,no_rkm_medis,'.$pasien->id,
            'nm_pasien' => 'required|string|max:100',
            'jk' => 'required|in:L,P',
        ]);

        $pasien->update($request->all());
        TrackerHelper::log('Master Rekam Medis', 'Ubah Master Pasien', "Mengubah Master Pasien: {$pasien->nm_pasien} (No. RM: {$pasien->no_rkm_medis})");

        return redirect()->route('masterPasien.index')->with('createSuccess', 'Data Master RM Pasien: '.$pasien->nm_pasien.' berhasil diperbarui!');
    }

    // Hapus Master Pasien
    public function destroy($id)
    {
        $hashid = new Hashids();
        $pasienId = is_numeric($id) ? $id : $hashid->decodeHex($id);
        $pasien = MasterPasien::findOrFail($pasienId);
        TrackerHelper::log('Master Rekam Medis', 'Hapus Master Pasien', "Menghapus Master Pasien: {$pasien->nm_pasien} (No. RM: {$pasien->no_rkm_medis})");
        $pasien->delete();

        return redirect()->route('masterPasien.index')->with('deleteSuccess', 'Data Master Pasien berhasil dihapus!');
    }

    // API JSON untuk Registrasi Kunjungan Pasien
    public function apiSearch(Request $request)
    {
        $q = $request->get('q', '');
        $query = MasterPasien::query();

        if (!empty($q)) {
            $query->where(function($builder) use ($q) {
                $builder->where('nm_pasien', 'LIKE', "%{$q}%")
                        ->orWhere('no_rkm_medis', 'LIKE', "%{$q}%")
                        ->orWhere('no_ktp', 'LIKE', "%{$q}%")
                        ->orWhere('no_tlp', 'LIKE', "%{$q}%");
            });
        }

        $results = $query->orderBy('no_rkm_medis', 'desc')->take(25)->get();
        return response()->json($results);
    }

    public function apiDetail($id)
    {
        $pasien = MasterPasien::find($id);
        if (!$pasien) {
            return response()->json(['error' => 'Pasien tidak ditemukan'], 404);
        }
        return response()->json($pasien);
    }
}

