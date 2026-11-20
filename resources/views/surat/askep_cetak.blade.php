<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Asuhan Keperawatan - {{ $askep->nama_klien }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.2cm 1.5cm;
        }
        * {
            box-sizing: border-box;
            color: #000;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000;
            line-height: 1.35;
        }
        .header-kop {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .kop-logo-ppni {
            width: 65px;
            height: 65px;
            object-fit: contain;
            flex-shrink: 0;
        }
        .kop-logo-klinik {
            width: 65px;
            height: 65px;
            object-fit: cover;
            border-radius: 50%;
            border: 1px solid #0284c7;
            flex-shrink: 0;
        }
        .kop-text {
            text-align: center;
            flex: 1;
            padding: 0 10px;
        }
        .header-kop h2 { margin: 0; font-size: 13pt; font-weight: bold; }
        .header-kop h3 { margin: 2px 0; font-size: 11pt; font-weight: bold; color: #0369a1; }
        .header-kop p { margin: 0; font-size: 8.5pt; }
        .section-title {
            font-weight: bold;
            font-size: 10pt;
            background: #f1f5f9;
            padding: 3px 6px;
            margin-top: 8px;
            margin-bottom: 4px;
            border-left: 3px solid #0284c7;
        }
        table.tbl-identitas {
            width: 100%;
            margin-bottom: 4px;
        }
        table.tbl-identitas td {
            padding: 2px 4px;
            vertical-align: top;
            font-size: 9.5pt;
        }
        table.tbl-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        table.tbl-grid th, table.tbl-grid td {
            border: 1px solid #000;
            padding: 5px 6px;
            vertical-align: top;
            font-size: 8.5pt;
        }
        table.tbl-grid th {
            background-color: #f8fafc;
            text-align: center;
            font-weight: bold;
        }
        .ttd-box {
            margin-top: 20px;
            float: right;
            text-align: center;
            width: 250px;
            font-size: 9.5pt;
        }
        .clear { clear: both; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 12px; text-align: right;">
        <button onclick="window.print()" style="padding: 6px 16px; background: #0284c7; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak Formulir Askep
        </button>
    </div>

    <!-- KOP DUA LOGO: PPNI (KIRI) & KLINIK (KANAN) -->
    <div class="header-kop">
        <img src="{{ asset('img/ppni.webp') }}" class="kop-logo-ppni" alt="Logo PPNI">
        <div class="kop-text">
            <h2>FORMULIR ASUHAN KEPERAWATAN</h2>
            <h3>PRAKTIK MANDIRI KEPERAWATAN WELLY</h3>
            <p>Welly Prestikasari, S.Kep., Ners., M.Kep. &bull; Nomor SIPP: 503/36/SIPP/IV.14/VIII/2021</p>
            <p>Dusun 3 RT/RW 015/004, Desa Sidodadi Kec. Teluk Pandan - Kab. Pesawaran</p>
            <p>Telp. 0813 6864 0404</p>
        </div>
        <img src="{{ asset('img/klinik.jpeg') }}" class="kop-logo-klinik" alt="Logo Klinik Welly">
    </div>

    <div class="section-title">1. IDENTITAS KLIEN</div>
    <table class="tbl-identitas">
        <tr>
            <td width="180px">Nomor Rawat</td>
            <td width="10px">:</td>
            <td><strong class="font-monospace">{{ $askep->nomor_register ?: ($askep->no_rawat ?: '-') }}</strong></td>
        </tr>
        <tr>
            <td>Nama Klien</td>
            <td>:</td>
            <td><strong>{{ $askep->nama_klien }}</strong></td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ $askep->jenis_kelamin }}</td>
        </tr>
        <tr>
            <td>Tanggal Lahir</td>
            <td>:</td>
            <td>{{ $askep->tanggal_lahir ? date('d-m-Y', strtotime($askep->tanggal_lahir)) : '-' }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $askep->alamat ?: '-' }}</td>
        </tr>
        <tr>
            <td>No. HP / Kontak</td>
            <td>:</td>
            <td>{{ $askep->no_hp ?: '-' }}</td>
        </tr>
    </table>

    <div class="section-title">2. RIWAYAT KESEHATAN</div>
    <table class="tbl-identitas">
        <tr>
            <td width="180px">Penyakit Dahulu</td>
            <td width="10px">:</td>
            <td>{{ $askep->penyakit_dahulu ?: 'Tidak Ada' }}</td>
        </tr>
        <tr>
            <td>Alergi (Obat, Makanan, dll)</td>
            <td>:</td>
            <td>{{ $askep->alergi ?: 'Tidak Ada' }}</td>
        </tr>
        <tr>
            <td>Obat Rutin Yang Dikonsumsi</td>
            <td>:</td>
            <td>{{ $askep->obat_rutin ?: 'Tidak Ada' }}</td>
        </tr>
    </table>

    <div class="section-title">3. CATATAN PERKEMBANGAN & ASUHAN KEPERAWATAN</div>
    <table class="tbl-grid">
        <thead>
            <tr>
                <th width="75px">Tanggal</th>
                <th>Pengkajian, Diagnosa, Intervensi</th>
                <th>Implementasi</th>
                <th>Evaluasi</th>
                <th width="90px">TTD</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">{{ date('d/m/Y', strtotime($askep->tanggal)) }}</td>
                <td>
                    <strong>Keluhan :</strong> {{ $askep->keluhan }}<br>
                    <strong>Pemeriksaan Fisik :</strong> {{ $askep->pemeriksaan_fisik ?: '-' }}<br>
                    <strong>Tanda-tanda Vital :</strong><br>
                    &bull; TD: {{ $askep->td ?: '-' }} mmHg<br>
                    &bull; HR: {{ $askep->hr ?: '-' }} x/mnt<br>
                    &bull; RR: {{ $askep->rr ?: '-' }} x/mnt<br>
                    &bull; Suhu: {{ $askep->suhu ?: '-' }} &deg;C<br><br>
                    <strong>Dx Keperawatan :</strong><br>{!! nl2br(e($askep->dx_keperawatan ?: '-')) !!}<br><br>
                    <strong>Intervensi :</strong><br>{!! nl2br(e($askep->intervensi ?: '-')) !!}
                </td>
                <td>{!! nl2br(e($askep->implementasi ?: '-')) !!}</td>
                <td>{!! nl2br(e($askep->evaluasi ?: '-')) !!}</td>
                <td style="text-align: center; vertical-align: bottom;">
                    <div style="height: 50px;"></div>
                    <strong>({{ $askep->ttd_nama }})</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="ttd-box">
        Pesawaran, {{ date('d F Y', strtotime($askep->tanggal)) }}<br>
        Perawat Penanggung Jawab,<br>
        <div style="height: 55px;"></div>
        <strong style="text-decoration: underline;">{{ $askep->ttd_nama }}</strong><br>
        <span>SIPP: 503/36/SIPP/IV.14/VIII/2021</span>
    </div>
    <div class="clear"></div>
</body>
</html>
