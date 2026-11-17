<?php

namespace App\Http\Controllers;

use Hashids\Hashids;
use App\Models\Pasien;
use App\Models\SoapPasien;
use App\Models\MasterPasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    /**
     * Dashboard Utama (Polosan Bersih & Logo Klinik)
     */
    public function dashboard()
    {
        $today = date('Y-m-d');
        $totalPasien = Pasien::count();
        $totalSoap = SoapPasien::count();
        $totalMaster = MasterPasien::count();
        $totalPeserta = DB::table('peserta_padepokan')->count();
        $totalLab = DB::table('periksa_lab')->count();
        $pasienHariIni = Pasien::where('tanggal_masuk', $today)->count();

        return view('index', [
            'totalPasien' => $totalPasien,
            'totalSoap' => $totalSoap,
            'totalMaster' => $totalMaster,
            'totalPeserta' => $totalPeserta,
            'totalLab' => $totalLab,
            'pasienHariIni' => $pasienHariIni,
        ]);
    }

    /**
     * Data Pasien & Registrasi Rawat Jalan
     */
    public function Index(Request $request)
    {
        $hashid = new Hashids();
        $query = Pasien::query();

        $from = $request->get('from', date('Y-m-d'));
        $to = $request->get('to', date('Y-m-d'));

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('tanggal_masuk', [$from, $to]);
        } else {
            $query->where('tanggal_masuk', date('Y-m-d'));
        }

        if ($request->filled('status_poli') && $request->status_poli != 'Semua') {
            $query->where('status_poli', $request->status_poli);
        }

        if ($request->filled('status_bayar') && $request->status_bayar != 'Semua') {
            if ($request->status_bayar == 'Sudah') {
                $query->where('status_bayar', 'Sudah');
            } else {
                $query->where(function($q) {
                    $q->whereNull('status_bayar')->orWhere('status_bayar', 'Belum');
                });
            }
        }

        if ($request->filled('kd_dokter')) {
            $query->where('kd_dokter', $request->kd_dokter);
        }

        if ($request->filled('kd_poli')) {
            $query->where('kd_poli', $request->kd_poli);
        }

        if ($request->filled('keyword')) {
            $kw = $request->keyword;
            $query->where(function($q) use ($kw) {
                $q->where('nama_lengkap', 'LIKE', "%{$kw}%")
                  ->orWhere('no_rawat', 'LIKE', "%{$kw}%")
                  ->orWhere('no_rkm_medis', 'LIKE', "%{$kw}%")
                  ->orWhere('no_telpon', 'LIKE', "%{$kw}%");
            });
        }

        $pasien = $query->orderBy('no_reg', 'desc')->orderBy('jam_masuk', 'desc')->get();
        if ($pasien->isEmpty() && !$request->has('from')) {
            $pasien = Pasien::orderBy('tanggal_masuk', 'desc')->orderBy('id', 'desc')->take(50)->get();
        }

        $totalPasien = Pasien::count();
        $totalSoap = SoapPasien::count();
        $totalMaster = MasterPasien::count();

        return view('pasien_index', [
            'pasien' => $pasien, 
            'hashid' => $hashid,
            'totalPasien' => $totalPasien,
            'totalSoap' => $totalSoap,
            'totalMaster' => $totalMaster
        ]);
    }

    public function IndexCari(Request $request)
    {
        $hashid = new Hashids();
        $from = $request->from;
        $to = $request->to;
        $pasien = Pasien::whereBetween('tanggal_masuk', [$from, $to])
                        ->orderBy('tanggal_masuk', 'desc')
                        ->get();

        $totalPasien = Pasien::count();
        $totalSoap = SoapPasien::count();
        $totalMaster = MasterPasien::count();

        return view('pasien_index', [
            'pasien' => $pasien, 
            'hashid' => $hashid,
            'totalPasien' => $totalPasien,
            'totalSoap' => $totalSoap,
            'totalMaster' => $totalMaster
        ]);
    }
}
