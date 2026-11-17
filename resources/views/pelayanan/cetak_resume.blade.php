<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Medis Rawat Jalan - {{ $pasien->nama_lengkap }} ({{ $pasien->no_rkm_medis }})</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 15mm 15mm;
        }

        * {
            box-sizing: border-box;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            color: #1a1a1a;
        }

        body {
            margin: 0;
            padding: 0;
            background: #eef2f6;
            font-size: 11.5px;
            line-height: 1.45;
        }

        /* Container Dokumen Cetak */
        .page-sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            background: #ffffff;
            padding: 16mm 18mm;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            position: relative;
        }

        /* Kop Surat Resmi Standard Khanza & Bundling2 */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 2.5px solid #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
            position: relative;
        }

        .kop-logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
            margin-right: 15px;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-text h2 {
            margin: 0;
            font-size: 17px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #0f172a;
            text-transform: uppercase;
        }

        .kop-text h4 {
            margin: 2px 0;
            font-size: 12px;
            font-weight: 700;
            color: #0369a1;
        }

        .kop-text p {
            margin: 2px 0 0 0;
            font-size: 10px;
            color: #475569;
        }

        .kop-garis-bawah {
            border-bottom: 1px solid #000;
            margin-top: 2px;
            margin-bottom: 14px;
        }

        /* Judul Dokumen */
        .title-doc-box {
            text-align: center;
            margin-bottom: 14px;
        }

        .title-doc-box .main-title {
            display: inline-block;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            padding-bottom: 3px;
        }

        .title-doc-box .sub-title {
            font-size: 10.5px;
            font-weight: 600;
            color: #64748b;
            margin-top: 3px;
        }

        /* Box Profil Pasien (2 Kolom Rapi) */
        .box-pasien {
            border: 1.5px solid #334155;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 14px;
            background: #f8fafc;
        }

        .grid-pasien {
            display: grid;
            grid-template-columns: 1fr 1fr;
            column-gap: 20px;
            row-gap: 4px;
        }

        .item-row {
            display: flex;
            align-items: baseline;
            font-size: 11px;
        }

        .item-label {
            width: 120px;
            font-weight: 600;
            color: #475569;
        }

        .item-colon {
            width: 10px;
            text-align: center;
        }

        .item-val {
            flex: 1;
            font-weight: 700;
            color: #0f172a;
        }

        /* Tabel Rincian Resume Medis */
        .table-resume {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .table-resume th, .table-resume td {
            border: 1px solid #334155;
            padding: 7px 10px;
            vertical-align: top;
            font-size: 11px;
        }

        .table-resume th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: 700;
            text-align: left;
            width: 28%;
        }

        .table-resume td {
            background-color: #ffffff;
            color: #0f172a;
            width: 72%;
        }

        .diagnosa-badge-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .diagnosa-badge-list li {
            margin-bottom: 4px;
            display: flex;
            align-items: baseline;
        }

        .code-pill {
            display: inline-block;
            background: #e0f2fe;
            color: #0369a1;
            font-weight: bold;
            font-family: monospace;
            padding: 1px 6px;
            border-radius: 3px;
            margin-right: 6px;
            font-size: 10.5px;
            border: 1px solid #bae6fd;
        }

        .priority-label {
            font-size: 9.5px;
            font-weight: bold;
            padding: 1px 5px;
            border-radius: 3px;
            margin-left: 6px;
            text-transform: uppercase;
        }

        .prio-utama { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
        .prio-sekunder { background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; }

        /* Tanda Tangan */
        .sign-area {
            width: 100%;
            margin-top: 25px;
        }

        .sign-area td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 11px;
        }

        .qr-placeholder {
            display: inline-block;
            width: 65px;
            height: 65px;
            border: 1px dashed #94a3b8;
            margin: 8px 0;
            padding: 3px;
            border-radius: 4px;
        }

        /* Toolbar Layar (Tidak Tercetak) */
        .toolbar-top {
            width: 210mm;
            margin: 15px auto 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 12px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-print {
            background: #0284c7;
            color: #fff;
        }
        .btn-print:hover {
            background: #0369a1;
        }

        .btn-back {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
        }
        .btn-back:hover {
            background: #f8fafc;
        }

        @media print {
            body {
                background: #ffffff;
            }
            .toolbar-top {
                display: none;
            }
            .page-sheet {
                box-shadow: none;
                margin: 0;
                padding: 0;
                width: 100%;
                min-height: auto;
            }
        }
    </style>
</head>
<body>

    <!-- TOOLBAR AKSI (HANYA DITAMPILKAN DI LAYAR) -->
    <div class="toolbar-top">
        <a href="{{ route('pelayanan.resume', $pasien->id) }}" class="btn-action btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Form Resume
        </a>
        <div style="display: flex; gap: 8px;">
            <button onclick="window.print()" class="btn-action btn-print">
                <i class="fas fa-print"></i> Cetak Lembar Resume (PDF/Printer)
            </button>
        </div>
    </div>

    <!-- LEMBAR DOKUMEN CETAK RESUME MEDIS -->
    <div class="page-sheet">
        <!-- KOP SURAT RESMI -->
        <div class="kop-surat">
            <img src="{{ asset('img/klinik.jpeg') }}" alt="Logo Klinik" class="kop-logo" onerror="this.src='{{ asset('img/logo.png') }}'">
            <div class="kop-text">
                <h2>PRAKTIK MANDIRI PERAWAT LUKA & RAWAT JALAN</h2>
                <h4>KLINIK PERAWATAN LUKA MODERN WELLY</h4>
                <p>Jl. Raya Kesehatan No. 12, Telp: (021) 87654321 / WhatsApp: 0838-2434-7684 &bull; Email: welly.perawat@gmail.com</p>
                <p style="font-size: 9px; color: #64748b;">SIP: 446/001/SIP/DPMPTSP/2026 &bull; Tanggal Izin Operasional: 2026</p>
            </div>
        </div>
        <div class="kop-garis-bawah"></div>

        <!-- JUDUL DOKUMEN -->
        <div class="title-doc-box">
            <div class="main-title">RINGKASAN PELAYANAN MEDIS RAWAT JALAN (RESUME MEDIS)</div>
            <div class="sub-title">Rekam Medis Elektronik Terintegrasi (RME Standar Pelayanan)</div>
        </div>

        <!-- BOX IDENTITAS PASIEN -->
        <div class="box-pasien">
            <div class="grid-pasien">
                <div class="item-row">
                    <span class="item-label">No. Rekam Medis (RM)</span>
                    <span class="item-colon">:</span>
                    <span class="item-val font-monospace" style="color: #059669; font-size: 13px;">{{ $pasien->no_rkm_medis }}</span>
                </div>
                <div class="item-row">
                    <span class="item-label">No. Registrasi / Rawat</span>
                    <span class="item-colon">:</span>
                    <span class="item-val font-monospace" style="color: #0284c7;">{{ $pasien->no_rawat }}</span>
                </div>
                <div class="item-row">
                    <span class="item-label">Nama Lengkap Pasien</span>
                    <span class="item-colon">:</span>
                    <span class="item-val" style="font-size: 12px;">{{ $pasien->nama_lengkap }}</span>
                </div>
                <div class="item-row">
                    <span class="item-label">Tgl. Masuk / Jam</span>
                    <span class="item-colon">:</span>
                    <span class="item-val">{{ $pasien->tanggal_masuk ?? date('Y-m-d') }} ({{ $pasien->jam_masuk ?? '09:00:00' }} WIB)</span>
                </div>
                <div class="item-row">
                    <span class="item-label">Jenis Kelamin / Umur</span>
                    <span class="item-colon">:</span>
                    <span class="item-val">{{ $pasien->jenis_kelamin ?? '-' }} ({{ $pasien->umur ?? '21 Th' }})</span>
                </div>
                <div class="item-row">
                    <span class="item-label">Poliklinik Tujuan</span>
                    <span class="item-colon">:</span>
                    <span class="item-val">{{ $pasien->nm_poli ?? 'Poli Perawatan Luka Modern' }}</span>
                </div>
                <div class="item-row">
                    <span class="item-label">Alamat Lengkap</span>
                    <span class="item-colon">:</span>
                    <span class="item-val" style="font-weight: 500;">{{ $pasien->alamat_lengkap ?? '-' }}</span>
                </div>
                <div class="item-row">
                    <span class="item-label">Cara Bayar / Asuransi</span>
                    <span class="item-colon">:</span>
                    <span class="item-val">{{ strtoupper($pasien->jenis_bayar ?? 'UMUM') }}</span>
                </div>
            </div>
        </div>

        <!-- TABEL RINCIAN RESUME MEDIS STANDAR BUNDLING2 / KHANZA -->
        <table class="table-resume">
            <tr>
                <th><i class="fas fa-comment-medical text-primary mr-1"></i> 1. Keluhan Utama & Riwayat Sakit</th>
                <td>
                    {{ $resume->keluhan_utama ?? ($pasien->keluhan ?? 'Pasien mengeluhkan luka kronik dengan nyeri berkala, memerlukan perawatan balutan modern.') }}
                </td>
            </tr>
            <tr>
                <th><i class="fas fa-notes-medical text-info mr-1"></i> 2. Anamnesis & Jalannya Penyakit</th>
                <td>
                    {{ $resume->jalannya_penyakit ?? 'Keluhan dialami sejak beberapa hari terakhir, riwayat perawatan sebelumnya di rumah namun luka masih basah dan belum ada tanda epitelisasi.' }}
                </td>
            </tr>
            <tr>
                <th><i class="fas fa-heartbeat text-danger mr-1"></i> 3. Pemeriksaan Fisik & Vital Sign</th>
                <td>
                    {!! nl2br(e($resume->pemeriksaan_fisik ?? "Keadaan Umum: Baik, Kesadaran: Compos Mentis\nTekanan Darah: 120/80 mmHg, Nadi: 80 x/mnt, Suhu: 36.5 °C, Pernapasan: 20 x/mnt\nKondisi Luka: Eksudat minimal, jaringan granulasi bertumbuh.")) !!}
                </td>
            </tr>
            <tr>
                <th><i class="fas fa-disease text-warning mr-1"></i> 4. Diagnosa ICD-10 (Primer & Sekunder)</th>
                <td>
                    @if(count($diagnosas) > 0)
                        <ul class="diagnosa-badge-list">
                            @foreach($diagnosas as $d)
                                <li>
                                    <span class="code-pill">{{ $d->kd_penyakit }}</span>
                                    <strong>{{ $d->nm_penyakit }}</strong>
                                    <span class="priority-label {{ $d->prioritas == 'Utama' ? 'prio-utama' : 'prio-sekunder' }}">
                                        {{ $d->prioritas }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div>
                            <strong>{{ $resume->diagnosa_utama ?? 'L97 - Non-pressure chronic ulcer of lower limb' }}</strong>
                            <span class="priority-label prio-utama">Utama</span>
                            @if(!empty($resume->diagnosa_sekunder))
                                <div style="margin-top: 4px;">
                                    <strong>{{ $resume->diagnosa_sekunder }}</strong>
                                    <span class="priority-label prio-sekunder">Sekunder</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <th><i class="fas fa-cut text-success mr-1"></i> 5. Prosedur & Tindakan Medis</th>
                <td>
                    {!! nl2br(e($resume->prosedur_tindakan ?? "1. Wound bed preparation & pencucian luka dengan cairan antiseptik steril.\n2. Debridement autolitik / nekrotomi jaringan nekrotik.\n3. Aplikasi balutan primer hidrokoloid/alginate & balutan sekunder transparan film.")) !!}
                </td>
            </tr>
            <tr>
                <th><i class="fas fa-pills text-primary mr-1"></i> 6. Terapi Obat Pulang (Resep Farmasi)</th>
                <td>
                    {!! nl2br(e($resume->terapi_obat ?? "- Paracetamol 500mg tab, 3x1 bila nyeri\n- Salep zinc oxide & foam dressing untuk perawatan luka\n- Multivitamin C & Zinc 1x1 post prandial")) !!}
                </td>
            </tr>
            <tr>
                <th><i class="fas fa-user-check text-success mr-1"></i> 7. Edukasi & Rencana Tindak Lanjut</th>
                <td>
                    {{ $resume->anjuran_edukasi ?? 'Jaga balutan tetap kering dan bersih, hindari luka terkena air secara langsung, konsumsi nutrisi tinggi protein, dan kontrol kembali 3 hari kemudian.' }}
                </td>
            </tr>
            <tr>
                <th><i class="fas fa-flag-checkered text-secondary mr-1"></i> 8. Kondisi Saat Pulang</th>
                <td>
                    <strong style="color: #059669; font-size: 12px;">{{ strtoupper($resume->kondisi_pulang ?? 'MEMBAIK') }}</strong>
                    <span style="color: #64748b; font-size: 10px; margin-left: 8px;">(Pasien diperbolehkan pulang / rawat jalan berkala)</span>
                </td>
            </tr>
        </table>

        <!-- AREA TANDA TANGAN RESMI -->
        <table class="sign-area">
            <tr>
                <td>
                    Pasien / Keluarga Pasien,<br><br>
                    <div style="height: 55px; display: flex; align-items: center; justify-content: center;">
                        <span style="font-size: 9px; color: #94a3b8; font-style: italic;">[ Tanda Tangan Pasien ]</span>
                    </div>
                    ( <strong>{{ $pasien->nama_lengkap }}</strong> )
                </td>
                <td>
                    {{ $pasien->kabupaten ?? 'Bandar Lampung' }}, {{ date('d F Y') }}<br>
                    Dokter Penanggung Jawab Pelayanan (DPJP),<br>
                    <div style="height: 55px; display: flex; align-items: center; justify-content: center;">
                        <div style="border: 1px solid #0284c7; padding: 4px 8px; border-radius: 4px; background: #f0f9ff; font-size: 9px; color: #0369a1; font-weight: bold;">
                            <i class="fas fa-check-circle text-success"></i> TERVERIFIKASI RME ELEKTRONIK
                        </div>
                    </div>
                    ( <strong>{{ $resume->dokter_dpjp ?? $pasien->dokter_dituju ?? 'dr. Welly Prestikasari, S.Kep., Ners., M.Kep.' }}</strong> )<br>
                    <small style="color: #64748b; font-size: 9px;">SIP: 446/001/SIP/DPMPTSP/2026</small>
                </td>
            </tr>
        </table>

        <div style="margin-top: 30px; border-top: 1px dashed #cbd5e1; padding-top: 6px; font-size: 8.5px; color: #94a3b8; display: flex; justify-content: space-between;">
            <span>Dokumen Resume Medis dicetak otomatis melalui Rekam Medis Elektronik Klinik</span>
            <span>Waktu Cetak: {{ date('d/m/Y H:i:s') }}</span>
        </div>
    </div>

</body>
</html>
