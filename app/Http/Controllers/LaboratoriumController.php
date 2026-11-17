<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Hashids\Hashids;
use App\Helpers\TrackerHelper;

class LaboratoriumController extends Controller
{
    private $hashids;

    public function __construct()
    {
        $this->hashids = new Hashids();
    }

    /**
     * Decode hashid secara fleksibel (mendukung new Hashids() default atau angka langsung)
     */
    private function resolvePasienId($id)
    {
        $realId = $this->hashids->decodeHex($id);
        if (!$realId && is_numeric($id)) {
            $realId = $id;
        }
        return $realId;
    }

    /**
     * Tampilkan Halaman Permintaan Laboratorium untuk Pasien Terpilih
     */
    public function permintaanLab($id)
    {
        $realId = $this->resolvePasienId($id);
        $pasien = DB::table('pasiens')->where('id', $realId)->first();
        if (!$pasien) {
            abort(404, 'Data kunjungan pasien tidak ditemukan');
        }

        // Ambil daftar paket jenis pemeriksaan laboratorium aktif
        $jenisPerawatan = DB::table('jns_perawatan_lab')
            ->where('status', '1')
            ->get();

        // Ambil riwayat permintaan lab untuk pasien ini
        $riwayatPermintaan = DB::table('permintaan_lab')
            ->where('no_rawat', $pasien->no_rawat)
            ->orderBy('tgl_permintaan', 'desc')
            ->orderBy('jam_permintaan', 'desc')
            ->get();

        // Generate noorder baru (PLYYYYMMDD0001)
        $today = date('Ymd');
        $lastOrder = DB::table('permintaan_lab')
            ->where('noorder', 'like', 'PL' . $today . '%')
            ->orderBy('noorder', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNum = (int) substr($lastOrder->noorder, -4);
            $nextNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNum = '0001';
        }
        $noorderBaru = 'PL' . $today . $nextNum;

        return view('laboratorium.permintaan', [
            'pasien' => $pasien,
            'hashId' => $id,
            'jenisPerawatan' => $jenisPerawatan,
            'riwayatPermintaan' => $riwayatPermintaan,
            'noorderBaru' => $noorderBaru
        ]);
    }

    /**
     * Simpan Permintaan Laboratorium Baru
     */
    public function simpanPermintaanLab(Request $request)
    {
        $request->validate([
            'noorder' => 'required',
            'no_rawat' => 'required',
            'kd_jenis_prw' => 'required|array|min:1',
        ], [
            'kd_jenis_prw.required' => 'Pilih minimal satu jenis pemeriksaan laboratorium',
            'kd_jenis_prw.min' => 'Pilih minimal satu jenis pemeriksaan laboratorium'
        ]);

        $noorder = $request->noorder;
        $no_rawat = $request->no_rawat;
        $tgl = $request->tgl_permintaan ?? date('Y-m-d');
        $jam = $request->jam_permintaan ?? date('H:i:s');
        $dokter = $request->dokter_perujuk ?? 'Welly Prestikasari, S.Kep., Ners., M.Kep.';
        $diagnosa = $request->diagnosa_klinis ?? '-';
        $info = $request->informasi_tambahan ?? '-';

        // Ambil no_rkm_medis pasien
        $pasien = DB::table('pasiens')->where('no_rawat', $no_rawat)->first();

        // Insert header permintaan_lab
        DB::table('permintaan_lab')->insert([
            'noorder' => $noorder,
            'no_rawat' => $no_rawat,
            'no_rkm_medis' => $pasien->no_rkm_medis ?? null,
            'tgl_permintaan' => $tgl,
            'jam_permintaan' => $jam,
            'tgl_sampel' => $tgl,
            'jam_sampel' => $jam,
            'dokter_perujuk' => $dokter,
            'status' => 'ralan',
            'status_order' => 'Menunggu',
            'informasi_tambahan' => $info,
            'diagnosa_klinis' => $diagnosa
        ]);

        // Insert detail permintaan pemeriksaan lab
        foreach ($request->kd_jenis_prw as $kd) {
            DB::table('permintaan_pemeriksaan_lab')->insert([
                'noorder' => $noorder,
                'kd_jenis_prw' => $kd,
                'stts_bayar' => 'Belum'
            ]);
        }

        TrackerHelper::log('Laboratorium', 'Order Permintaan Lab', "Membuat order lab ($noorder) untuk pasien: {$pasien->nama_lengkap} (No. Rawat: {$no_rawat})");

        return redirect()->back()->with('createSuccess', "Permintaan Laboratorium ($noorder) berhasil disimpan!");
    }

    /**
     * Tampilkan Halaman Pengisian / Input Hasil Laboratorium
     */
    public function periksaLab($id, Request $request)
    {
        $realId = $this->resolvePasienId($id);
        $pasien = DB::table('pasiens')->where('id', $realId)->first();
        if (!$pasien) {
            abort(404, 'Data kunjungan pasien tidak ditemukan');
        }

        $noorder = $request->query('noorder');

        // Jika ada order tertentu, ambil pemeriksaan yang di-order
        $orderPemeriksaan = [];
        if ($noorder) {
            $orderPemeriksaan = DB::table('permintaan_pemeriksaan_lab')
                ->where('noorder', $noorder)
                ->pluck('kd_jenis_prw')
                ->toArray();
        }

        // Ambil semua jenis perawatan lab aktif beserta template itemnya
        $jenisPerawatan = DB::table('jns_perawatan_lab')
            ->where('status', '1')
            ->get();

        $templates = DB::table('template_laboratorium')
            ->orderBy('kd_jenis_prw')
            ->orderBy('urut')
            ->get()
            ->groupBy('kd_jenis_prw');

        // Ambil riwayat hasil pemeriksaan lab yang sudah pernah diinput untuk pasien ini
        $riwayatHasil = DB::table('periksa_lab')
            ->where('no_rawat', $pasien->no_rawat)
            ->orderBy('tgl_periksa', 'desc')
            ->orderBy('jam', 'desc')
            ->get();

        return view('laboratorium.periksa', [
            'pasien' => $pasien,
            'hashId' => $id,
            'noorder' => $noorder,
            'orderPemeriksaan' => $orderPemeriksaan,
            'jenisPerawatan' => $jenisPerawatan,
            'templates' => $templates,
            'riwayatHasil' => $riwayatHasil
        ]);
    }

    /**
     * Simpan Hasil Pengisian Pemeriksaan Laboratorium
     */
    public function simpanHasilLab(Request $request)
    {
        $request->validate([
            'no_rawat' => 'required',
            'kd_jenis_prw' => 'required|array|min:1',
            'nilai' => 'required|array',
        ]);

        $no_rawat = $request->no_rawat;
        $noorder = $request->noorder ?? null;
        $tgl = $request->tgl_periksa ?? date('Y-m-d');
        $jam = $request->jam_periksa ?? date('H:i:s');
        $dokter = $request->dokter_perujuk ?? 'Welly Prestikasari, S.Kep., Ners., M.Kep.';
        $petugas = $request->petugas_lab ?? 'Petugas Laboratorium';

        foreach ($request->kd_jenis_prw as $kd) {
            $jns = DB::table('jns_perawatan_lab')->where('kd_jenis_prw', $kd)->first();
            $biaya = $jns->total_byr ?? 0;

            // Header periksa_lab
            $periksaLabId = DB::table('periksa_lab')->insertGetId([
                'no_rawat' => $no_rawat,
                'noorder' => $noorder,
                'nip' => $petugas,
                'kd_jenis_prw' => $kd,
                'tgl_periksa' => $tgl,
                'jam' => $jam,
                'dokter_perujuk' => $dokter,
                'petugas_lab' => $petugas,
                'biaya' => $biaya,
                'status' => 'Ralan',
                'kategori' => $jns->kategori ?? 'PK',
                'catatan' => $request->catatan[$kd] ?? null
            ]);

            // Detail periksa_lab (dari form template)
            if (isset($request->nilai[$kd]) && is_array($request->nilai[$kd])) {
                foreach ($request->nilai[$kd] as $idTemplate => $val) {
                    if ($val === null || $val === '') continue;

                    $tmpl = DB::table('template_laboratorium')->where('id_template', $idTemplate)->first();
                    $nilaiRujukan = $request->nilai_rujukan[$kd][$idTemplate] ?? ($tmpl->nilai_rujukan_ld ?? '');
                    $satuan = $request->satuan[$kd][$idTemplate] ?? ($tmpl->satuan ?? '');
                    $keterangan = $request->keterangan[$kd][$idTemplate] ?? '';

                    DB::table('detail_periksa_lab')->insert([
                        'periksa_lab_id' => $periksaLabId,
                        'no_rawat' => $no_rawat,
                        'kd_jenis_prw' => $kd,
                        'tgl_periksa' => $tgl,
                        'jam' => $jam,
                        'id_template' => $idTemplate,
                        'nilai' => $val,
                        'nilai_rujukan' => $nilaiRujukan,
                        'satuan' => $satuan,
                        'keterangan' => $keterangan
                    ]);
                }
            }
        }

        // Update status_order jika berasal dari order permintaan
        if ($noorder) {
            DB::table('permintaan_lab')
                ->where('noorder', $noorder)
                ->update([
                    'status_order' => 'Selesai',
                    'tgl_hasil' => $tgl,
                    'jam_hasil' => $jam
                ]);
        }

        TrackerHelper::log('Laboratorium', 'Simpan Hasil Lab', "Mengisi hasil pemeriksaan lab untuk No. Rawat: {$no_rawat} (Petugas: {$petugas})");

        return redirect()->back()->with('createSuccess', 'Hasil pemeriksaan laboratorium berhasil disimpan!');
    }

    /**
     * Cetak Lembar Hasil Pemeriksaan Laboratorium Pasien
     */
    public function cetakHasilLab($periksaId)
    {
        $periksa = DB::table('periksa_lab')->where('id', $periksaId)->first();
        if (!$periksa) {
            abort(404, 'Hasil pemeriksaan tidak ditemukan');
        }

        $pasien = DB::table('pasiens')->where('no_rawat', $periksa->no_rawat)->first();
        $jns = DB::table('jns_perawatan_lab')->where('kd_jenis_prw', $periksa->kd_jenis_prw)->first();

        $details = DB::table('detail_periksa_lab as d')
            ->join('template_laboratorium as t', 'd.id_template', '=', 't.id_template')
            ->where('d.periksa_lab_id', $periksaId)
            ->select('d.*', 't.Pemeriksaan as item_pemeriksaan')
            ->orderBy('t.urut')
            ->get();

        return view('laboratorium.cetak_hasil', [
            'periksa' => $periksa,
            'pasien' => $pasien,
            'jns' => $jns,
            'details' => $details
        ]);
    }
}

