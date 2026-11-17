<?php

namespace App\Http\Controllers;

use Hashids\Hashids;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PelayananMedisController extends Controller
{
    protected function decodeId($hashId)
    {
        $hashids = new Hashids();
        // Coba decodeHex (sesuai format hashid bawaan sistem ini)
        $hexDecoded = $hashids->decodeHex($hashId);
        if ($hexDecoded && is_numeric($hexDecoded)) {
            $pasien = Pasien::find($hexDecoded);
            if ($pasien) return $pasien;
        }

        // Coba decode biasa
        $decoded = $hashids->decode($hashId);
        if (!empty($decoded)) {
            $pasien = Pasien::find($decoded[0]);
            if ($pasien) return $pasien;
        }

        // Coba langsung sebagai integer ID
        if (is_numeric($hashId)) {
            $pasien = Pasien::find($hashId);
            if ($pasien) return $pasien;
        }

        abort(404, 'Data pasien tidak ditemukan');
    }

    /**
     * ==========================================
     * 1. BILLING & KASIR RAWAT JALAN
     * ==========================================
     */
    public function billing($hashId)
    {
        $pasien = $this->decodeId($hashId);

        // Cari billing yang sudah ada atau siapkan data billing baru
        $billing = DB::table('billing_pasien')->where('no_rawat', $pasien->no_rawat)->first();

        // Biaya registrasi default 40.000 (sesuai standar Khanza) atau dari database pasien
        $biayaReg = $billing ? $billing->biaya_registrasi : ($pasien->biaya_reg ?? 40000);

        // Hitung total tindakan pasien dari tabel tindakan_pasien
        $sumTindakan = DB::table('tindakan_pasien')->where('no_rawat', $pasien->no_rawat)->sum('biaya');
        $biayaTindakan = $billing ? $billing->biaya_tindakan : ($sumTindakan ?? 0);
        if ($billing && $billing->biaya_tindakan == 0 && $sumTindakan > 0) {
            $biayaTindakan = $sumTindakan;
        }

        // Hitung total obat/resep dari farmasi jika ada (kolom: total_bayar) atau dari resep_dokter_detail
        $totalObatFarmasi = DB::table('pembelian_obat')
            ->where('no_rawat', $pasien->no_rawat)
            ->sum('total_bayar') ?? 0;

        $resep = DB::table('resep_obat')->where('no_rawat', $pasien->no_rawat)->first();
        $totalObatResep = 0;
        if ($resep) {
            $totalObatResep = DB::table('resep_dokter_detail')->where('no_resep', $resep->no_resep)->sum('total') ?? 0;
        }
        $totalObat = $billing ? $billing->biaya_obat : max($totalObatFarmasi, $totalObatResep);
        if ($billing && $billing->biaya_obat == 0 && max($totalObatFarmasi, $totalObatResep) > 0) {
            $totalObat = max($totalObatFarmasi, $totalObatResep);
        }

        // Hitung total lab jika ada (kolom: biaya)
        $totalLab = DB::table('periksa_lab')
            ->where('no_rawat', $pasien->no_rawat)
            ->sum('biaya') ?? 0;

        $totalBayar = $billing ? $billing->total_bayar : ($biayaReg + $biayaTindakan + $totalObat + $totalLab);
        if ($billing && $billing->status_bayar !== 'Sudah') {
            $totalBayar = $biayaReg + $biayaTindakan + $totalObat + $totalLab;
        }

        return view('pelayanan.billing', [
            'pasien' => $pasien,
            'billing' => $billing,
            'biayaReg' => $biayaReg,
            'biayaTindakan' => $biayaTindakan,
            'totalObat' => $totalObat,
            'totalLab' => $totalLab,
            'totalBayar' => $totalBayar,
            'hashId' => $hashId
        ]);
    }

    public function simpanBilling(Request $request, $hashId)
    {
        $pasien = $this->decodeId($hashId);

        $biayaReg = (float) $request->input('biaya_registrasi', 0);
        $biayaTindakan = (float) $request->input('biaya_tindakan', 0);
        $biayaObat = (float) $request->input('biaya_obat', 0);
        $biayaLab = (float) $request->input('biaya_lab', 0);
        $totalBayar = $biayaReg + $biayaTindakan + $biayaObat + $biayaLab;

        // Otomatis tercatat Lunas / Sudah bayar saat tombol Bayar diklik
        $statusBayar = 'Sudah';

        $exists = DB::table('billing_pasien')->where('no_rawat', $pasien->no_rawat)->first();

        // Generate Nomor Nota Otomatis (Format: NT-YYYYMMDD-XXXX) jika belum ada
        $noNota = $exists && !empty($exists->no_nota) ? $exists->no_nota : null;
        if (!$noNota) {
            $prefixNota = 'NT' . date('Ymd');
            $lastNota = DB::table('billing_pasien')
                ->where('no_nota', 'like', $prefixNota . '%')
                ->orderBy('no_nota', 'desc')
                ->value('no_nota');

            if ($lastNota) {
                $lastNum = (int) substr($lastNota, strlen($prefixNota));
                $noNota = $prefixNota . str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $noNota = $prefixNota . '0001';
            }
        }

        if ($exists) {
            DB::table('billing_pasien')->where('no_rawat', $pasien->no_rawat)->update([
                'no_nota' => $noNota,
                'biaya_registrasi' => $biayaReg,
                'biaya_tindakan' => $biayaTindakan,
                'biaya_obat' => $biayaObat,
                'biaya_lab' => $biayaLab,
                'total_bayar' => $totalBayar,
                'status_bayar' => $statusBayar,
                'tgl_byr' => date('Y-m-d'),
                'jam_byr' => date('H:i:s'),
                'petugas' => Auth::user()->name ?? 'Kasir',
                'updated_at' => now(),
            ]);
        } else {
            DB::table('billing_pasien')->insert([
                'no_rawat' => $pasien->no_rawat,
                'no_nota' => $noNota,
                'no_rkm_medis' => $pasien->no_rkm_medis,
                'nm_pasien' => $pasien->nama_lengkap,
                'tgl_byr' => date('Y-m-d'),
                'jam_byr' => date('H:i:s'),
                'biaya_registrasi' => $biayaReg,
                'biaya_tindakan' => $biayaTindakan,
                'biaya_obat' => $biayaObat,
                'biaya_lab' => $biayaLab,
                'total_bayar' => $totalBayar,
                'status_bayar' => $statusBayar,
                'petugas' => Auth::user()->name ?? 'Kasir',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Update status_bayar, status_periksa, no_nota pada tabel pasien
        $pasien->status_bayar = $statusBayar;
        $pasien->status_periksa = 'Selesai';
        $pasien->biaya_reg = $biayaReg;
        $pasien->no_nota = $noNota;
        $pasien->save();

        return redirect()->route('pelayanan.billing', $hashId)
            ->with('success', 'Pembayaran berhasil disimpan dan Nota Pembayaran No. ' . $noNota . ' telah dibuat!');
    }

    public function hapusNotaBilling(Request $request, $hashId)
    {
        // Hanya user dengan role admin yang boleh menghapus nota billing
        $user = Auth::user();
        if (!$user || strtolower($user->role ?? '') !== 'admin') {
            return redirect()->back()->with('error', 'Akses ditolak! Hanya Administrator yang berhak membatalkan / menghapus Nota Billing yang salah.');
        }

        $pasien = $this->decodeId($hashId);

        $billing = DB::table('billing_pasien')->where('no_rawat', $pasien->no_rawat)->first();
        $notaDihapus = $billing->no_nota ?? ($pasien->no_nota ?? 'Nota');

        // Hapus data billing dari billing_pasien
        DB::table('billing_pasien')->where('no_rawat', $pasien->no_rawat)->delete();

        // Kembalikan status pasien menjadi Belum Bayar dan kosongkan nomor nota
        $pasien->status_bayar = 'Belum';
        $pasien->status_periksa = 'Belum Bayar';
        $pasien->no_nota = null;
        $pasien->save();

        return redirect()->route('pelayanan.billing', $hashId)
            ->with('success', 'Nota pembayaran (' . $notaDihapus . ') berhasil dihapus/dibatalkan oleh Admin. Status pembayaran telah dikembalikan menjadi BELUM BAYAR.');
    }

    public function cetakBilling($hashId)
    {
        $pasien = $this->decodeId($hashId);
        $billing = DB::table('billing_pasien')->where('no_rawat', $pasien->no_rawat)->first();

        return view('pelayanan.cetak_billing', [
            'pasien' => $pasien,
            'billing' => $billing
        ]);
    }

    /**
     * ==========================================
     * 2. DIAGNOSA PASIEN (ICD-10)
     * ==========================================
     */
    public function diagnosa($hashId)
    {
        $pasien = $this->decodeId($hashId);
        $diagnosas = DB::table('diagnosa_pasien')->where('no_rawat', $pasien->no_rawat)->get();
        $masterIcd = DB::table('master_icd10')->orderBy('kd_penyakit')->get();

        return view('pelayanan.diagnosa', [
            'pasien' => $pasien,
            'diagnosas' => $diagnosas,
            'masterIcd' => $masterIcd,
            'hashId' => $hashId
        ]);
    }

    public function cariIcd10(Request $request)
    {
        $search = $request->get('q', '');
        $query = DB::table('master_icd10')->select('kd_penyakit', 'nm_penyakit');

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('kd_penyakit', 'like', "%{$search}%")
                  ->orWhere('nm_penyakit', 'like', "%{$search}%");
            });
        }
        $data = $query->limit(50)->get();
        return response()->json($data);
    }

    public function simpanDiagnosa(Request $request, $hashId)
    {
        $pasien = $this->decodeId($hashId);

        DB::table('diagnosa_pasien')->insert([
            'no_rawat' => $pasien->no_rawat,
            'kd_penyakit' => $request->kd_penyakit,
            'nm_penyakit' => $request->nm_penyakit,
            'status' => $request->status ?? 'Ralan',
            'prioritas' => $request->prioritas ?? 'Utama',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Diagnosa ICD-10 berhasil ditambahkan.');
    }

    public function hapusDiagnosa($id)
    {
        DB::table('diagnosa_pasien')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Diagnosa berhasil dihapus.');
    }

    /**
     * ==========================================
     * 3. RESUME MEDIS RAWAT JALAN
     * ==========================================
     */
    public function resume($hashId)
    {
        $pasien = $this->decodeId($hashId);
        $resume = DB::table('resume_pasien')->where('no_rawat', $pasien->no_rawat)->first();
        $diagnosas = DB::table('diagnosa_pasien')->where('no_rawat', $pasien->no_rawat)->get();

        return view('pelayanan.resume', [
            'pasien' => $pasien,
            'resume' => $resume,
            'diagnosas' => $diagnosas,
            'hashId' => $hashId
        ]);
    }

    public function simpanResume(Request $request, $hashId)
    {
        $pasien = $this->decodeId($hashId);

        $data = [
            'keluhan_utama' => $request->keluhan_utama,
            'jalannya_penyakit' => $request->jalannya_penyakit,
            'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
            'diagnosa_utama' => $request->diagnosa_utama,
            'diagnosa_sekunder' => $request->diagnosa_sekunder,
            'prosedur_tindakan' => $request->prosedur_tindakan,
            'terapi_obat' => $request->terapi_obat,
            'anjuran_edukasi' => $request->anjuran_edukasi,
            'kondisi_pulang' => $request->kondisi_pulang ?? 'Membaik',
            'dokter_dpjp' => $request->dokter_dpjp ?? $pasien->dokter_dpjp ?? 'dr. Welly',
            'updated_at' => now(),
        ];

        $exists = DB::table('resume_pasien')->where('no_rawat', $pasien->no_rawat)->first();
        if ($exists) {
            DB::table('resume_pasien')->where('no_rawat', $pasien->no_rawat)->update($data);
        } else {
            $data['no_rawat'] = $pasien->no_rawat;
            $data['created_at'] = now();
            DB::table('resume_pasien')->insert($data);
        }

        return redirect()->back()->with('success', 'Resume medis pasien berhasil disimpan.');
    }

    public function hapusResume($hashId)
    {
        $pasien = $this->decodeId($hashId);
        DB::table('resume_pasien')->where('no_rawat', $pasien->no_rawat)->delete();

        return redirect()->route('pelayanan.resume', $hashId)->with('success', 'Resume medis pasien ' . $pasien->nama_lengkap . ' berhasil dihapus.');
    }

    public function cetakResume($hashId)
    {
        $pasien = $this->decodeId($hashId);
        $resume = DB::table('resume_pasien')->where('no_rawat', $pasien->no_rawat)->first();
        $diagnosas = DB::table('diagnosa_pasien')->where('no_rawat', $pasien->no_rawat)->get();

        return view('pelayanan.cetak_resume', [
            'pasien' => $pasien,
            'resume' => $resume,
            'diagnosas' => $diagnosas
        ]);
    }

    /**
     * API Pencarian Pasien untuk Modal Cepat Medis, Lab & Farmasi
     */
    public function apiPencarianPasien(Request $request)
    {
        $q = trim($request->get('q', ''));
        $hashids = new Hashids();

        $query = DB::table('pasiens')
            ->select('id', 'no_rawat', 'no_rkm_medis', 'nama_lengkap', 'unit_poli', 'dokter_dituju', 'tanggal_masuk', 'jenis_kelamin', 'tgl_lahir', 'alamat_lengkap', 'gol_darah')
            ->orderBy('id', 'desc');

        if (!empty($q)) {
            $query->where(function($w) use ($q) {
                $w->where('no_rawat', 'LIKE', "%{$q}%")
                  ->orWhere('no_rkm_medis', 'LIKE', "%{$q}%")
                  ->orWhere('nama_lengkap', 'LIKE', "%{$q}%")
                  ->orWhere('unit_poli', 'LIKE', "%{$q}%")
                  ->orWhere('dokter_dituju', 'LIKE', "%{$q}%");
            });
        }

        $pasiens = $query->limit(25)->get();

        $data = $pasiens->map(function($p) use ($hashids) {
            // Hitung umur perkiraan dari tgl_lahir jika ada
            $umur = '-';
            if (!empty($p->tgl_lahir)) {
                $tglLahir = \Carbon\Carbon::parse($p->tgl_lahir);
                $umur = $tglLahir->age . ' Th';
            }

            return [
                'id' => $p->id,
                'hashid' => $hashids->encodeHex($p->id),
                'no_rawat' => $p->no_rawat,
                'no_rkm_medis' => $p->no_rkm_medis ?? '-',
                'nama_lengkap' => $p->nama_lengkap,
                'jenis_kelamin' => $p->jenis_kelamin ?? 'Laki-laki',
                'umur' => $umur,
                'tgl_lahir' => $p->tgl_lahir ?? '',
                'alamat_lengkap' => $p->alamat_lengkap ?? '',
                'gol_darah' => $p->gol_darah ?? '-',
                'unit_poli' => $p->unit_poli ?? 'Poli Umum',
                'dokter_dituju' => $p->dokter_dituju ?? '-',
                'tanggal_masuk' => $p->tanggal_masuk ?? '-'
            ];
        });

        return response()->json($data);
    }
}

