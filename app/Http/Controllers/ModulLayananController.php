<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModulLayananController extends Controller
{
    /**
     * Tampilan modul dalam pengembangan / placeholder yang terintegrasi rapi
     */
    public function modulPlaceholder($kategori, $modul)
    {
        $meta = $this->getModulMeta($modul);
        return view('modul.placeholder', [
            'kategori' => $kategori,
            'modul' => $modul,
            'meta' => $meta
        ]);
    }

    /**
     * Metadata modul mLITE
     */
    private function getModulMeta($modul)
    {
        $map = [
            // Layanan
            'igd' => ['title' => 'Instalasi Gawat Darurat (IGD)', 'icon' => 'fas fa-ambulance', 'color' => 'danger', 'desc' => 'Pelayanan triase gawat darurat, observasi kritis & tindakan cepat.'],
            'dokter-igd' => ['title' => 'Dokter IGD', 'icon' => 'fas fa-user-md', 'color' => 'danger', 'desc' => 'Pemeriksaan medis khusus DPJP IGD, SOAP gawat darurat & disposisi pasien.'],
            'rawat-inap' => ['title' => 'Rawat Inap', 'icon' => 'fas fa-bed', 'color' => 'primary', 'desc' => 'Pengelolaan kamar bangsal, ranjang pasien inap, visit dokter & keperawatan.'],
            'dokter-ranap' => ['title' => 'Dokter Ranap', 'icon' => 'fas fa-stethoscope', 'color' => 'primary', 'desc' => 'CPPT / SOAP rawat inap, instruksi medis & perencanaan pemulangan pasien.'],
            'radiologi' => ['title' => 'Radiologi & Imaging', 'icon' => 'fas fa-x-ray', 'color' => 'info', 'desc' => 'Pemeriksaan foto rontgen, USG, hasil ekspertise radiolog & PACS imaging.'],
            'operasi' => ['title' => 'Kamar Operasi (OK / VK)', 'icon' => 'fas fa-procedures', 'color' => 'warning', 'desc' => 'Jadwal bedah/operasi, laporan operasi, anestesi & tindakan bedah minor.'],
            'oral-diagnostic' => ['title' => 'Oral Diagnostic / Poli Gigi', 'icon' => 'fas fa-tooth', 'color' => 'success', 'desc' => 'Pemeriksaan kesehatan gigi dan mulut, odontogram & tindakan dental.'],
            'utd' => ['title' => 'Unit Transfusi Darah (UTD)', 'icon' => 'fas fa-tint', 'color' => 'danger', 'desc' => 'Stok kantong darah, donor darah & permintaan transfusi darah pasien.'],

            // Rekam Medik
            'clinical-pathway' => ['title' => 'Clinical Pathway', 'icon' => 'fas fa-clipboard-check', 'color' => 'info', 'desc' => 'Panduan alur klinis standar asuhan terintegrasi bagi pasien terstandarisasi.'],
            'laporan-rm' => ['title' => 'Laporan Rekam Medis (RL)', 'icon' => 'fas fa-file-invoice', 'color' => 'primary', 'desc' => 'Statistik kunjungan pasien, 10 besar penyakit (morbiditas) & laporan RL Kemenkes.'],
            'mini-pacs' => ['title' => 'Mini PACS / DICOM Viewer', 'icon' => 'fas fa-photo-video', 'color' => 'secondary', 'desc' => 'Arsip digital dan viewer berkas foto klinis, rontgen & luka pasien.'],

            // Farmasi
            'apotek-ranap' => ['title' => 'Apotek Rawat Inap', 'icon' => 'fas fa-capsules', 'color' => 'success', 'desc' => 'Pemberian obat unit dose dispensing (UDD), retur obat inap & resep ranap.'],
            'penjualan-bebas' => ['title' => 'Penjualan Obat Bebas', 'icon' => 'fas fa-cash-register', 'color' => 'success', 'desc' => 'Transaksi langsung obat OTC, alkes & balutan luka tanpa registrasi periksa.'],

            // Keuangan
            'kasir-ranap' => ['title' => 'Kasir Rawat Inap', 'icon' => 'fas fa-file-invoice-dollar', 'color' => 'warning', 'desc' => 'Perhitungan billing rawat inap, deposit pasien & penyelesaian tagihan akhir.'],
            'jasa-medis' => ['title' => 'Jasa Medis & Paramedis', 'icon' => 'fas fa-hand-holding-usd', 'color' => 'primary', 'desc' => 'Perhitungan bagi hasil jasa medis dokter, perawat luka & petugas penunjang.'],
            'buku-kas' => ['title' => 'Keuangan & Jurnal Kas', 'icon' => 'fas fa-chart-line', 'color' => 'info', 'desc' => 'Pencatatan kas masuk, kas keluar, operasional klinik & pembukuan kasir.'],

            // Bridging
            'satu-sehat' => ['title' => 'Bridging Satu Sehat (Kemenkes)', 'icon' => 'fas fa-heartbeat', 'color' => 'danger', 'desc' => 'Integrasi FHIR ID pasien, nakes, encounter kunjungan, kondisi & resume medis.'],
            'bpjs-pcare' => ['title' => 'BPJS PCare / Antrean Faskes', 'icon' => 'fas fa-shield-alt', 'color' => 'success', 'desc' => 'Bridging PCare FKTP, validasi kepesertaan BPJS & bridging antrean online.'],
            'bpjs-vclaim' => ['title' => 'BPJS VClaim / E-MR', 'icon' => 'fas fa-id-card', 'color' => 'info', 'desc' => 'Penerbitan SEP BPJS, bridging rujukan E-MR & monitoring klaim JKN.'],
            'mobile-jkn' => ['title' => 'Mobile JKN Faskes', 'icon' => 'fas fa-mobile-alt', 'color' => 'primary', 'desc' => 'Penerimaan pendaftaran online pasien Mobile JKN BPJS Kesehatan.'],
            'wa-gateway' => ['title' => 'WhatsApp Gateway', 'icon' => 'fab fa-whatsapp', 'color' => 'success', 'desc' => 'Notifikasi pengingat kontrol, info antrean & pengiriman hasil lab via WhatsApp.'],

            // Anjungan
            'anjungan-antrian' => ['title' => 'Display Antrian Loket & Poli', 'icon' => 'fas fa-tv', 'color' => 'info', 'desc' => 'Layar anjungan mandiri cetak tiket & display TV panggilan nomor urut pasien.'],

            // Manajemen
            'kepegawaian' => ['title' => 'Kepegawaian (SDM)', 'icon' => 'fas fa-users-cog', 'color' => 'secondary', 'desc' => 'Data berkas pegawai, masa berlaku SIP/STR perawat & dokter, serta jenjang karir.'],
            'presensi' => ['title' => 'Presensi & Absensi Pegawai', 'icon' => 'fas fa-user-clock', 'color' => 'warning', 'desc' => 'Pencatatan jam masuk & pulang dinas perawat/petugas medis shift klinik.'],
            'inventaris' => ['title' => 'Inventaris & Aset Klinik', 'icon' => 'fas fa-boxes', 'color' => 'dark', 'desc' => 'Pendataan barang inventaris medis/non-medis, nomor seri & jadwal kalibrasi alat.'],
            'e-signature' => ['title' => 'E-Signature (TTE Digital)', 'icon' => 'fas fa-signature', 'color' => 'primary', 'desc' => 'Pengelolaan sertifikat tanda tangan elektronik dokumen medis & persuratan.']
        ];

        return $map[$modul] ?? [
            'title' => ucwords(str_replace('-', ' ', $modul)),
            'icon' => 'fas fa-folder',
            'color' => 'primary',
            'desc' => 'Modul terintegrasi mLITE.'
        ];
    }
}

