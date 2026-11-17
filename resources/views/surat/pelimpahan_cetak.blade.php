<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pelimpahan Wewenang Medis</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 2cm;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }
        .header-kop {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2.5px solid #000;
            padding-bottom: 8px;
            margin-bottom: 20px;
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
        .kop-text h2 { margin: 0; font-size: 14pt; font-weight: bold; }
        .kop-text h3 { margin: 2px 0; font-size: 11pt; font-weight: bold; color: #0369a1; }
        .kop-text p { margin: 0; font-size: 9pt; }
        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 13pt;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 2px;
        }
        .nomor-surat {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 25px;
        }
        table.tbl-data {
            width: 100%;
            margin-bottom: 12px;
        }
        table.tbl-data td {
            vertical-align: top;
            padding: 3px 4px;
        }
        ol {
            margin-top: 4px;
            margin-bottom: 10px;
            padding-left: 25px;
        }
        li {
            margin-bottom: 4px;
        }
        .ttd-container {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .ttd-col {
            width: 45%;
            text-align: center;
        }
        .materai-box {
            font-size: 9pt;
            color: #475569;
            border: 1px dashed #94a3b8;
            padding: 6px;
            width: 90px;
            margin: 15px auto;
            text-align: center;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 6px 16px; background: #d97706; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak Surat Pelimpahan Wewenang
        </button>
    </div>

    <!-- KOP RESMI DUA LOGO: PPNI (KIRI) & KLINIK (KANAN) -->
    <div class="header-kop">
        <img src="{{ asset('img/ppni.webp') }}" class="kop-logo-ppni" alt="Logo PPNI">
        <div class="kop-text">
            <h2>PRAKTIK MANDIRI KEPERAWATAN WELLY</h2>
            <h3>Welly Prestikasari, S.Kep., Ners., M.Kep., CWCCA</h3>
            <p>Nomor SIPP: 503/36/SIPP/IV.14/VIII/2021 &bull; Nomor STR: 180152120-3129482</p>
            <p>Alamat Praktik: Bandar Lampung &bull; Telp/WA: 0857-6843-1011</p>
        </div>
        <img src="{{ asset('img/klinik.jpeg') }}" class="kop-logo-klinik" alt="Logo Klinik Welly">
    </div>

    <div class="header-title">SURAT PELIMPAHAN WEWENANG {{ strtoupper($surat->jenis_pelimpahan) }} MEDIS KEPADA PERAWAT</div>
    <div class="nomor-surat">Nomor: {{ $surat->no_surat }}</div>

    <p style="margin-bottom: 8px;">Yang bertanda tangan di bawah ini:</p>
    <table class="tbl-data">
        <tr>
            <td width="220px">Nama Dokter</td>
            <td width="10px">:</td>
            <td><strong>{{ $surat->nama_dokter }}</strong></td>
        </tr>
        <tr>
            <td>SIP</td>
            <td>:</td>
            <td>{{ $surat->sip_dokter ?: '-' }}</td>
        </tr>
        <tr>
            <td>Alamat Tempat Praktik</td>
            <td>:</td>
            <td>{{ $surat->alamat_praktik_dokter ?: '-' }}</td>
        </tr>
    </table>

    <p style="margin-top: 14px; margin-bottom: 8px;">Dengan ini melimpahkan wewenang kepada:</p>
    <table class="tbl-data">
        <tr>
            <td width="220px">Nama Perawat</td>
            <td width="10px">:</td>
            <td><strong>{{ $surat->nama_perawat }}</strong></td>
        </tr>
        <tr>
            <td>Nomor STR</td>
            <td>:</td>
            <td>{{ $surat->str_perawat ?: '-' }}</td>
        </tr>
        <tr>
            <td>Nomor SIPP</td>
            <td>:</td>
            <td>{{ $surat->sipp_perawat ?: '-' }}</td>
        </tr>
    </table>

    <p style="margin-top: 14px; margin-bottom: 6px;">Untuk melaksanakan tindakan medis berupa:</p>
    <ol>
        <li>{{ $surat->tindakan_1 }}</li>
        @if($surat->tindakan_2)
            <li>{{ $surat->tindakan_2 }}</li>
        @endif
        @if($surat->tindakan_3)
            <li>{{ $surat->tindakan_3 }}</li>
        @endif
    </ol>

    <p style="text-indent: 28px; text-align: justify; margin-top: 12px;">
        Pelimpahan wewenang ini dilaksanakan sesuai dengan kompetensi perawat, ketentuan peraturan perundang-undangan yang berlaku, serta bentuk pelimpahan yang ditetapkan.
    </p>
    <p style="text-indent: 28px; margin-bottom: 25px;">
        Demikian surat ini dibuat untuk dipergunakan sebagaimana mestinya.
    </p>

    <div style="text-align: right; margin-bottom: 15px;">
        {{ $surat->tempat_surat ?: 'Bandar Lampung' }}, {{ date('d F Y', strtotime($surat->tgl_surat)) }}
    </div>

    <!-- TANDA TANGAN DUA PIHAK -->
    <div class="ttd-container">
        <div class="ttd-col">
            Dokter Pemberi Wewenang,
            <div class="materai-box">Materai bila dipersyaratkan</div>
            <strong style="text-decoration: underline;">({{ $surat->nama_dokter }})</strong>
        </div>

        <div class="ttd-col">
            Perawat Penerima Wewenang,
            <div style="height: 65px;"></div>
            <strong style="text-decoration: underline;">({{ $surat->nama_perawat }})</strong><br>
            <span>SIPP: {{ $surat->sipp_perawat }}</span>
        </div>
    </div>
</body>
</html>

