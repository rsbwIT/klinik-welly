<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pasien;

class LaporanKasirController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->get('from', date('Y-m-d'));
        $to = $request->get('to', date('Y-m-d'));
        $statusBayar = $request->get('status_bayar', 'Semua');
        $caraBayar = $request->get('cara_bayar', 'Semua');

        $query = DB::table('billing_pasien')
            ->leftJoin('pasiens', 'billing_pasien.no_rawat', '=', 'pasiens.no_rawat')
            ->select(
                'billing_pasien.*',
                'pasiens.jenis_bayar',
                'pasiens.unit_poli as nm_poli',
                'pasiens.dokter_dituju as dokter_dpjp',
                'pasiens.tanggal_masuk'
            )
            ->whereBetween('billing_pasien.tgl_byr', [$from, $to]);

        if ($statusBayar !== 'Semua') {
            $query->where('billing_pasien.status_bayar', $statusBayar);
        }

        if ($caraBayar !== 'Semua') {
            $query->where('pasiens.jenis_bayar', $caraBayar);
        }

        $listBilling = $query->orderBy('billing_pasien.id', 'desc')->get();

        // Ringkasan Finansial
        $totalPendapatan = $listBilling->where('status_bayar', 'Sudah')->sum('total_bayar');
        $totalRegistrasi = $listBilling->where('status_bayar', 'Sudah')->sum('biaya_registrasi');
        $totalTindakan   = $listBilling->where('status_bayar', 'Sudah')->sum('biaya_tindakan');
        $totalObat       = $listBilling->where('status_bayar', 'Sudah')->sum('biaya_obat');
        $totalLab        = $listBilling->where('status_bayar', 'Sudah')->sum('biaya_lab');
        $totalTransaksi  = $listBilling->count();
        $transaksiLunas  = $listBilling->where('status_bayar', 'Sudah')->count();
        $transaksiBelum  = $listBilling->where('status_bayar', 'Belum')->count();

        // Daftar cara bayar unik untuk filter
        $penjabList = DB::table('penjab')->select('kd_pj', 'png_jawab')->get();

        return view('laporan.kasir_index', [
            'listBilling'     => $listBilling,
            'from'            => $from,
            'to'              => $to,
            'statusBayar'     => $statusBayar,
            'caraBayar'       => $caraBayar,
            'totalPendapatan' => $totalPendapatan,
            'totalRegistrasi' => $totalRegistrasi,
            'totalTindakan'   => $totalTindakan,
            'totalObat'       => $totalObat,
            'totalLab'        => $totalLab,
            'totalTransaksi'  => $totalTransaksi,
            'transaksiLunas'  => $transaksiLunas,
            'transaksiBelum'  => $transaksiBelum,
            'penjabList'      => $penjabList,
        ]);
    }

    public function cetak(Request $request)
    {
        $from = $request->get('from', date('Y-m-d'));
        $to = $request->get('to', date('Y-m-d'));
        $statusBayar = $request->get('status_bayar', 'Semua');
        $caraBayar = $request->get('cara_bayar', 'Semua');

        $query = DB::table('billing_pasien')
            ->leftJoin('pasiens', 'billing_pasien.no_rawat', '=', 'pasiens.no_rawat')
            ->select(
                'billing_pasien.*',
                'pasiens.jenis_bayar',
                'pasiens.unit_poli as nm_poli',
                'pasiens.dokter_dituju as dokter_dpjp',
                'pasiens.tanggal_masuk'
            )
            ->whereBetween('billing_pasien.tgl_byr', [$from, $to]);

        if ($statusBayar !== 'Semua') {
            $query->where('billing_pasien.status_bayar', $statusBayar);
        }

        if ($caraBayar !== 'Semua') {
            $query->where('pasiens.jenis_bayar', $caraBayar);
        }

        $listBilling = $query->orderBy('billing_pasien.id', 'asc')->get();

        $totalPendapatan = $listBilling->where('status_bayar', 'Sudah')->sum('total_bayar');
        $totalRegistrasi = $listBilling->where('status_bayar', 'Sudah')->sum('biaya_registrasi');
        $totalTindakan   = $listBilling->where('status_bayar', 'Sudah')->sum('biaya_tindakan');
        $totalObat       = $listBilling->where('status_bayar', 'Sudah')->sum('biaya_obat');
        $totalLab        = $listBilling->where('status_bayar', 'Sudah')->sum('biaya_lab');

        return view('laporan.kasir_cetak', [
            'listBilling'     => $listBilling,
            'from'            => $from,
            'to'              => $to,
            'totalPendapatan' => $totalPendapatan,
            'totalRegistrasi' => $totalRegistrasi,
            'totalTindakan'   => $totalTindakan,
            'totalObat'       => $totalObat,
            'totalLab'        => $totalLab,
        ]);
    }
}
