<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Pasien;
use Hashids\Hashids;

class PelayananKlinikController extends Controller
{
    private function decodeId($hashId)
    {
        $hashids = new Hashids();

        // 1. Coba decodeHex (sesuai format hashid bawaan pasien)
        $hexDecoded = $hashids->decodeHex($hashId);
        if ($hexDecoded && is_numeric($hexDecoded)) {
            $pasien = Pasien::find($hexDecoded);
            if ($pasien) return $pasien;
        }

        // 2. Coba decode integer biasa
        $decoded = $hashids->decode($hashId);
        if (!empty($decoded)) {
            $pasien = Pasien::find($decoded[0]);
            if ($pasien) return $pasien;
        }

        // 3. Coba cari langsung berdasarkan ID angka
        if (is_numeric($hashId)) {
            $pasien = Pasien::find($hashId);
            if ($pasien) return $pasien;
        }

        // 4. Coba cari berdasarkan no_rawat
        $pasien = Pasien::where('no_rawat', $hashId)->first();
        if ($pasien) return $pasien;

        abort(404, 'Data pasien tidak ditemukan.');
    }

    /**
     * ==========================================
     * 1. E-RESEP DOKTER & APOTEK
     * ==========================================
     */
    public function resepPasien($hashId)
    {
        $pasien = $this->decodeId($hashId);

        // Ambil data resep pasien jika sudah ada
        $resep = DB::table('resep_obat')->where('no_rawat', $pasien->no_rawat)->first();
        $detailResep = [];
        if ($resep) {
            $detailResep = DB::table('resep_dokter_detail')
                ->where('no_resep', $resep->no_resep)
                ->get();
        }

        // Ambil daftar master obat untuk pilihan
        $daftarObat = DB::table('databarang_obat')
            ->select('kode_brng', 'nama_brng', 'kode_sat', 'ralan as harga_jual', 'stok', 'kategori')
            ->where('status', '1')
            ->orderBy('nama_brng', 'asc')
            ->get();

        return view('pelayanan.resep_dokter', [
            'pasien' => $pasien,
            'resep' => $resep,
            'detailResep' => $detailResep,
            'daftarObat' => $daftarObat,
            'hashId' => $hashId
        ]);
    }

    public function simpanResep(Request $request, $hashId)
    {
        $pasien = $this->decodeId($hashId);

        $obatList = $request->input('kode_brng', []);
        $jmlList = $request->input('jml', []);
        $aturanList = $request->input('aturan_pakai', []);
        $etiketList = $request->input('jenis_etiket', []);

        if (empty($obatList) || count($obatList) === 0) {
            return redirect()->back()->with('error', 'Silakan tambahkan minimal 1 item obat ke dalam resep.');
        }

        // Cari atau generate no_resep (Format: RSP-YYYYMMDD-XXXX)
        $resep = DB::table('resep_obat')->where('no_rawat', $pasien->no_rawat)->first();
        if ($resep) {
            $noResep = $resep->no_resep;
            DB::table('resep_obat')->where('no_resep', $noResep)->update([
                'tgl_peresepan' => date('Y-m-d'),
                'jam_peresepan' => date('H:i:s'),
                'dokter_peresep' => $pasien->dokter_dituju ?? (Auth::user()->name ?? 'Dokter'),
                'status' => 'Menunggu',
                'updated_at' => now(),
            ]);
            // Hapus detail lama untuk diganti baru
            DB::table('resep_dokter_detail')->where('no_resep', $noResep)->delete();
        } else {
            $prefix = 'RSP' . date('Ymd');
            $last = DB::table('resep_obat')->where('no_resep', 'like', $prefix . '%')->orderBy('no_resep', 'desc')->value('no_resep');
            $num = $last ? ((int) substr($last, strlen($prefix)) + 1) : 1;
            $noResep = $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);

            DB::table('resep_obat')->insert([
                'no_resep' => $noResep,
                'tgl_peresepan' => date('Y-m-d'),
                'jam_peresepan' => date('H:i:s'),
                'no_rawat' => $pasien->no_rawat,
                'kd_dokter' => $pasien->kd_dokter ?? '',
                'dokter_peresep' => $pasien->dokter_dituju ?? (Auth::user()->name ?? 'Dokter'),
                'status' => 'Menunggu',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $totalBiayaObat = 0;
        foreach ($obatList as $i => $kd) {
            if (empty($kd)) continue;
            $obat = DB::table('databarang_obat')->where('kode_brng', $kd)->first();
            $nama = $obat ? $obat->nama_brng : $kd;
            $harga = $obat ? ($obat->ralan > 0 ? $obat->ralan : $obat->h_beli) : 0;
            $jml = (float) ($jmlList[$i] ?? 1);
            $aturan = $aturanList[$i] ?? '3 x 1 Sesudah Makan';
            $etiket = $etiketList[$i] ?? 'Putih (Obat Dalam)';
            $subtotal = $harga * $jml;
            $totalBiayaObat += $subtotal;

            DB::table('resep_dokter_detail')->insert([
                'no_resep' => $noResep,
                'kode_brng' => $kd,
                'nama_brng' => $nama,
                'jml' => $jml,
                'aturan_pakai' => $aturan,
                'jenis_etiket' => $etiket,
                'harga_jual' => $harga,
                'total' => $subtotal,
                'created_at' => now(),
            ]);
        }

        // Otomatis sinkronkan total biaya obat ke tabel billing_pasien jika ada
        $billing = DB::table('billing_pasien')->where('no_rawat', $pasien->no_rawat)->first();
        if ($billing) {
            $newTotal = $billing->biaya_registrasi + $billing->biaya_tindakan + $totalBiayaObat + $billing->biaya_lab;
            DB::table('billing_pasien')->where('no_rawat', $pasien->no_rawat)->update([
                'biaya_obat' => $totalBiayaObat,
                'total_bayar' => $newTotal,
                'updated_at' => now()
            ]);
        }

        return redirect()->route('pelayanan.resep', $hashId)
            ->with('success', 'E-Resep Dokter No. ' . $noResep . ' berhasil disimpan dan otomatis terkirim ke Apotek & Kasir!');
    }

    public function cetakEtiket($hashId)
    {
        $pasien = $this->decodeId($hashId);
        $resep = DB::table('resep_obat')->where('no_rawat', $pasien->no_rawat)->first();
        $detailResep = [];
        if ($resep) {
            $detailResep = DB::table('resep_dokter_detail')->where('no_resep', $resep->no_resep)->get();
        }

        return view('pelayanan.cetak_etiket', [
            'pasien' => $pasien,
            'resep' => $resep,
            'detailResep' => $detailResep
        ]);
    }

    /**
     * ==========================================
     * 2. TINDAKAN MEDIS & PERAWATAN LUKA PASIEN
     * ==========================================
     */
    public function tindakanPasien($hashId)
    {
        $pasien = $this->decodeId($hashId);

        $riwayatTindakan = DB::table('tindakan_pasien')
            ->where('no_rawat', $pasien->no_rawat)
            ->orderBy('id', 'desc')
            ->get();

        $masterTindakan = DB::table('master_tindakan')
            ->where('status', '1')
            ->orderBy('nm_perawatan', 'asc')
            ->get();

        $daftarDokter = DB::table('master_dokter')
            ->where('status', '1')
            ->orderBy('nm_dokter', 'asc')
            ->get();

        $daftarPetugas = DB::table('users')
            ->select('id', 'name', 'role')
            ->orderBy('name', 'asc')
            ->get();

        $totalTindakan = $riwayatTindakan->sum('biaya');

        return view('pelayanan.tindakan_pasien', [
            'pasien' => $pasien,
            'riwayatTindakan' => $riwayatTindakan,
            'masterTindakan' => $masterTindakan,
            'daftarDokter' => $daftarDokter,
            'daftarPetugas' => $daftarPetugas,
            'totalTindakan' => $totalTindakan,
            'hashId' => $hashId
        ]);
    }

    public function simpanTindakan(Request $request, $hashId)
    {
        $pasien = $this->decodeId($hashId);

        $kdJenisList = $request->input('kd_jenis_prw', []);
        $biayaList = $request->input('biaya', []);
        $tipePenanganan = $request->input('tipe_penanganan', 'Dokter');
        $kdDokter = $request->input('kd_dokter', $pasien->kd_dokter ?? '');
        $nmDokter = $request->input('nm_dokter', $pasien->dokter_dituju ?? 'Dokter Pemeriksa');

        if (empty($kdJenisList)) {
            return redirect()->back()->with('error', 'Pilih minimal satu tindakan yang dicentang!');
        }

        $count = 0;
        foreach ($kdJenisList as $kd) {
            $master = DB::table('master_tindakan')->where('kd_jenis_prw', $kd)->first();
            if (!$master) continue;

            $biaya = isset($biayaList[$kd]) ? (float) $biayaList[$kd] : (float) $master->total_byr;

            DB::table('tindakan_pasien')->insert([
                'no_rawat' => $pasien->no_rawat,
                'kd_jenis_prw' => $master->kd_jenis_prw,
                'nm_perawatan' => $master->nm_perawatan,
                'tipe_penanganan' => $tipePenanganan,
                'kd_dokter' => $kdDokter,
                'nm_dokter' => $nmDokter,
                'tgl_tindakan' => date('Y-m-d'),
                'jam_tindakan' => date('H:i:s'),
                'biaya' => $biaya,
                'petugas' => Auth::user()->name ?? 'Petugas Medis',
                'created_at' => now(),
            ]);
            $count++;
        }

        // Sinkronisasi otomatis total tindakan ke kasir billing
        $sumTindakan = DB::table('tindakan_pasien')->where('no_rawat', $pasien->no_rawat)->sum('biaya');
        $billing = DB::table('billing_pasien')->where('no_rawat', $pasien->no_rawat)->first();
        if ($billing) {
            $newTotal = $billing->biaya_registrasi + $sumTindakan + $billing->biaya_obat + $billing->biaya_lab;
            DB::table('billing_pasien')->where('no_rawat', $pasien->no_rawat)->update([
                'biaya_tindakan' => $sumTindakan,
                'total_bayar' => $newTotal,
                'updated_at' => now(),
            ]);
        } else {
            // Jika record billing belum dibuat di kasir, otomatis buatkan record billing
            $biayaReg = $pasien->biaya_reg ?? 40000;
            $newTotal = $biayaReg + $sumTindakan;
            DB::table('billing_pasien')->insert([
                'no_rawat' => $pasien->no_rawat,
                'no_rkm_medis' => $pasien->no_rkm_medis,
                'nm_pasien' => $pasien->nama_lengkap,
                'tgl_byr' => date('Y-m-d'),
                'jam_byr' => date('H:i:s'),
                'biaya_registrasi' => $biayaReg,
                'biaya_tindakan' => $sumTindakan,
                'biaya_obat' => 0,
                'biaya_lab' => 0,
                'total_bayar' => $newTotal,
                'status_bayar' => 'Belum',
                'petugas' => Auth::user()->name ?? 'Kasir',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('pelayanan.tindakan', $hashId)
            ->with('success', $count . ' Tindakan berhasil disimpan dan otomatis ditambahkan ke Kasir Billing!');
    }

    public function hapusTindakan($id, $hashId)
    {
        $pasien = $this->decodeId($hashId);
        DB::table('tindakan_pasien')->where('id', $id)->where('no_rawat', $pasien->no_rawat)->delete();

        // Update billing kasir
        $sumTindakan = DB::table('tindakan_pasien')->where('no_rawat', $pasien->no_rawat)->sum('biaya');
        $billing = DB::table('billing_pasien')->where('no_rawat', $pasien->no_rawat)->first();
        if ($billing) {
            $newTotal = $billing->biaya_registrasi + $sumTindakan + $billing->biaya_obat + $billing->biaya_lab;
            DB::table('billing_pasien')->where('no_rawat', $pasien->no_rawat)->update([
                'biaya_tindakan' => $sumTindakan,
                'total_bayar' => $newTotal,
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('pelayanan.tindakan', $hashId)
            ->with('success', 'Tindakan medis berhasil dihapus.');
    }

    /**
     * Master Tindakan & Tarif Manajemen
     */
    public function masterTindakanIndex()
    {
        $tindakan = DB::table('master_tindakan')->orderBy('nm_perawatan', 'asc')->get();
        return view('master.tindakan_index', ['tindakan' => $tindakan]);
    }

    public function masterTindakanStore(Request $request)
    {
        $request->validate([
            'kd_jenis_prw' => 'required|unique:master_tindakan,kd_jenis_prw',
            'nm_perawatan' => 'required',
            'total_byr' => 'required|numeric',
        ]);

        DB::table('master_tindakan')->insert([
            'kd_jenis_prw' => strtoupper($request->kd_jenis_prw),
            'nm_perawatan' => $request->nm_perawatan,
            'kategori' => $request->kategori ?? 'Tindakan Medis',
            'total_byr' => $request->total_byr,
            'status' => '1',
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Master tindakan medis berhasil ditambahkan!');
    }
}
