<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat Rujukan - {{ $surat->nama_pasien }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.2cm 1.8cm;
        }
        * {
            box-sizing: border-box;
            color: #000;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            line-height: 1.35;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }
        .header-kop {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2.5px solid #000;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }
        .kop-logo-ppni {
            width: 75px;
            height: 75px;
            object-fit: contain;
            flex-shrink: 0;
        }
        .kop-logo-klinik {
            width: 75px;
            height: 75px;
            object-fit: cover;
            border-radius: 50%;
            border: 1px solid #0284c7;
            flex-shrink: 0;
        }
        .kop-text {
            text-align: center;
            flex: 1;
            padding: 0 12px;
        }
        .kop-text h2 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .kop-text h3 {
            margin: 2px 0;
            font-size: 12pt;
            font-weight: bold;
            color: #0369a1;
        }
        .kop-text p {
            margin: 0;
            font-size: 9pt;
            line-height: 1.3;
        }
        .title-surat {
            text-align: center;
            font-weight: bold;
            font-size: 12.5pt;
            text-decoration: underline;
            margin-top: 6px;
            margin-bottom: 2px;
            text-transform: uppercase;
        }
        .nomor-surat {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 14px;
        }
        .section-title {
            font-weight: bold;
            font-size: 11pt;
            margin-top: 10px;
            margin-bottom: 4px;
            text-transform: uppercase;
            text-decoration: underline;
        }
        table.tbl-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        table.tbl-data td {
            vertical-align: top;
            padding: 2px 4px;
            font-size: 10.5pt;
        }
        .ttd-box {
            margin-top: 20px;
            float: right;
            text-align: center;
            width: 280px;
            font-size: 10.5pt;
        }
        .clear {
            clear: both;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="background: #f1f5f9; padding: 10px 20px; border-bottom: 1px solid #cbd5e1; margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 7px 18px; background: #0284c7; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 12px;">
            🖨️ Cetak Surat Rujukan
        </button>
    </div>

    <!-- KOP RESMI: LOGO PPNI (KIRI) & LOGO KLINIK (KANAN) -->
    <div class="header-kop">
        <img src="{{ asset('img/ppni.webp') }}" class="kop-logo-ppni" alt="Logo PPNI">
        <div class="kop-text">
            <h2>PRAKTIK MANDIRI KEPERAWATAN WELLY</h2>
            <h3>WELLY PRESTIKASARI, S.Kep., Ners., M.Kep.</h3>
            <p>Spesialis Perawatan Luka Modern, Terapi Luka Kronis/Diabetes & Asuhan Keperawatan</p>
            <p>Nomor SIPP: 503/36/SIPP/IV.14/VIII/2021</p>
            <p>Dusun 3 RT/RW 015/004, Desa Sidodadi Kec. Teluk Pandan - Kab. Pesawaran &bull; Telp. 0813 6864 0404</p>
        </div>
        <img src="{{ asset('img/klinik.jpeg') }}" class="kop-logo-klinik" alt="Logo Klinik Welly">
    </div>

    <!-- JUDUL SURAT -->
    <div class="title-surat">SURAT RUJUKAN PASIEN</div>
    <div class="nomor-surat">Nomor: {{ $surat->no_surat }}</div>

    <p style="margin-bottom: 3px;">Kepada Yth.</p>
    <p style="margin: 0; font-weight: bold;">Dokter / Petugas Kesehatan</p>
    <p style="margin: 0; font-weight: bold;">RS / Puskesmas {{ $surat->tujuan_faskes }}</p>
    <p style="margin-top: 0;">Di Tempat</p>

    <p style="text-indent: 28px; margin-top: 8px; margin-bottom: 6px;">
        Dengan hormat,<br>
        Dengan ini kami merujuk pasien:
    </p>

    <div class="section-title">IDENTITAS PASIEN</div>
    <table class="tbl-data">
        <tr>
            <td width="200px">Nama</td>
            <td width="10px">:</td>
            <td><strong>{{ $surat->nama_pasien }}</strong></td>
        </tr>
        <tr>
            <td>Umur / Tanggal Lahir</td>
            <td>:</td>
            <td>{{ $surat->umur ?: '-' }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ $surat->jenis_kelamin }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $surat->alamat ?: '-' }}</td>
        </tr>
    </table>

    <div class="section-title">HASIL PENGKAJIAN KEPERAWATAN</div>
    <table class="tbl-data">
        <tr>
            <td width="200px">Tanggal pemeriksaan</td>
            <td width="10px">:</td>
            <td>{{ date('d-m-Y', strtotime($surat->tgl_pemeriksaan ?: $surat->tgl_surat)) }}</td>
        </tr>
        <tr>
            <td>Keluhan utama</td>
            <td>:</td>
            <td>{{ $surat->keluhan_utama ?: '-' }}</td>
        </tr>
        <tr>
            <td>Riwayat penyakit</td>
            <td>:</td>
            <td>{{ $surat->riwayat_penyakit ?: '-' }}</td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 4px;">
                <strong>Hasil pemeriksaan tanda-tanda vital:</strong>
                <div style="margin-left: 15px; margin-top: 2px;">
                    - Tekanan darah : {{ $surat->tensi ?: '-' }} mmHg<br>
                    - Nadi : {{ $surat->nadi ?: '-' }} x/menit<br>
                    - Pernapasan : {{ $surat->respirasi ?: '-' }} x/menit<br>
                    - Suhu : {{ $surat->suhu ?: '-' }} &deg;C<br>
                    - SpO₂ : {{ $surat->spo2 ?: '-' }} %
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 4px;">
                <strong>Hasil pemeriksaan/pengkajian lainnya:</strong><br>
                <div style="margin-left: 15px; margin-top: 2px;">{{ $surat->pemeriksaan_lainnya ?: '-' }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">ALASAN RUJUKAN</div>
    <p style="margin: 2px 0 6px 15px;">{{ $surat->alasan_rujukan }}</p>

    <div class="section-title">TINDAKAN KEPERAWATAN YANG TELAH DIBERIKAN</div>
    <p style="margin: 2px 0 10px 15px;">{{ $surat->tindakan_diberikan ?: '-' }}</p>

    <p style="text-indent: 28px; margin-top: 10px;">
        Demikian surat rujukan ini kami sampaikan. Atas perhatian dan kerja sama sejawat, kami ucapkan terima kasih.
    </p>

    <!-- TANDA TANGAN -->
    <div class="ttd-box">
        Pesawaran, {{ date('d F Y', strtotime($surat->tgl_surat)) }}<br>
        Hormat kami,<br>
        <strong>Praktik Mandiri Keperawatan Welly</strong><br>
        <div style="height: 60px;"></div>
        <strong style="text-decoration: underline;">{{ $surat->nama_perawat }}</strong><br>
        <span>SIPP: 503/36/SIPP/IV.14/VIII/2021</span>
    </div>
    <div class="clear"></div>
</body>
</html>
