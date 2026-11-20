<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Sehat - {{ $surat->nama_pasien }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 1.5cm 2cm;
        }
        * {
            box-sizing: border-box;
            color: #000;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            line-height: 1.45;
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
            margin-bottom: 18px;
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
        }
        .kop-text h3 {
            margin: 2px 0;
            font-size: 11pt;
            font-weight: bold;
            color: #0369a1;
        }
        .kop-text p {
            margin: 0;
            font-size: 8.5pt;
            line-height: 1.3;
        }
        .title-surat {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin-top: 15px;
            margin-bottom: 2px;
        }
        .nomor-surat {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 22px;
        }
        table.tbl-data {
            width: 100%;
            margin-bottom: 12px;
        }
        table.tbl-data td {
            padding: 3px 5px;
            vertical-align: top;
        }
        table.tbl-fisik {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 18px 0;
        }
        table.tbl-fisik td, table.tbl-fisik th {
            border: 1px solid #000;
            padding: 5px 8px;
            font-size: 11pt;
        }
        table.tbl-fisik th {
            background-color: #f8fafc;
            text-align: left;
        }
        .paragraf {
            text-align: justify;
            text-indent: 30px;
            margin-bottom: 12px;
        }
        .kesimpulan-box {
            border: 2px solid #000;
            padding: 8px 15px;
            margin: 15px 0;
            text-align: center;
            background: #fafafa;
        }
        .ttd-box {
            margin-top: 35px;
            float: right;
            width: 280px;
            text-align: center;
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
        <button onclick="window.print()" style="padding: 7px 18px; background: #059669; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 12px;">
            🖨️ Cetak Surat Keterangan Sehat
        </button>
    </div>

    <!-- KOP RESMI DUA LOGO: LOGO PPNI (KIRI) & LOGO KLINIK (KANAN) -->
    <div class="header-kop">
        <img src="{{ asset('img/ppni.webp') }}" class="kop-logo-ppni" alt="Logo PPNI">
        <div class="kop-text">
            <h2>PRAKTIK MANDIRI KEPERAWATAN WELLY</h2>
            <h3>WELLY PRESTIKASARI, S.Kep., Ners., M.Kep., CWCCA</h3>
            <p>Spesialis Perawatan Luka Modern, Terapi Luka Kronis/Diabetes & Asuhan Keperawatan</p>
            <p>Nomor SIPP: 503/36/SIPP/IV.14/VIII/2021 &bull; Nomor STR: 180152120-3129482</p>
            <p>Dusun 3 RT/RW 015/004, Desa Sidodadi Kec. Teluk Pandan - Kab. Pesawaran &bull; Telp. 0813 6864 0404</p>
        </div>
        <img src="{{ asset('img/klinik.jpeg') }}" class="kop-logo-klinik" alt="Logo Klinik Welly">
    </div>

    <!-- JUDUL SURAT -->
    <div class="title-surat">SURAT KETERANGAN KESEHATAN</div>
    <div class="nomor-surat">Nomor: {{ $surat->no_surat }}</div>

    <p style="margin-bottom: 8px;">Yang bertanda tangan di bawah ini menerangkan dengan sesungguhnya bahwa:</p>

    <table class="tbl-data">
        <tr>
            <td width="180px">Nama Pasien / Pemohon</td>
            <td width="15px">:</td>
            <td><strong>{{ $surat->nama_pasien }}</strong></td>
        </tr>
        <tr>
            <td>Umur</td>
            <td>:</td>
            <td>{{ $surat->umur ?: '-' }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ $surat->jenis_kelamin ?: '-' }}</td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>:</td>
            <td>{{ $surat->pekerjaan ?: '-' }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $surat->alamat ?: '-' }}</td>
        </tr>
    </table>

    <p style="margin-bottom: 5px; margin-top: 10px;">
        Pada hari ini telah kami lakukan pemeriksaan fisik dan tanda vital dengan rincian sebagai berikut:
    </p>

    <table class="tbl-fisik">
        <tr>
            <td width="25%"><strong>Tinggi Badan</strong></td>
            <td width="25%">: {{ $surat->tinggi_badan ? $surat->tinggi_badan . ' cm' : '-' }}</td>
            <td width="25%"><strong>Tekanan Darah (TD)</strong></td>
            <td width="25%">: {{ $surat->tensi ? $surat->tensi . ' mmHg' : '-' }}</td>
        </tr>
        <tr>
            <td><strong>Berat Badan</strong></td>
            <td>: {{ $surat->berat_badan ? $surat->berat_badan . ' kg' : '-' }}</td>
            <td><strong>Denyut Nadi</strong></td>
            <td>: {{ $surat->nadi ? $surat->nadi . ' x/menit' : '-' }}</td>
        </tr>
        <tr>
            <td><strong>Golongan Darah</strong></td>
            <td>: {{ $surat->golongan_darah ?: '-' }}</td>
            <td><strong>Suhu Tubuh</strong></td>
            <td>: {{ $surat->suhu ? $surat->suhu . ' °C' : '-' }}</td>
        </tr>
        <tr>
            <td><strong>Tes Buta Warna</strong></td>
            <td>: {{ $surat->buta_warna ?: 'Tidak Buta Warna' }}</td>
            <td><strong>Respirasi (Pernafasan)</strong></td>
            <td>: {{ $surat->respirasi ? $surat->respirasi . ' x/menit' : '-' }}</td>
        </tr>
    </table>

    <div class="kesimpulan-box">
        <span style="font-size: 11pt;">Berdasarkan hasil pemeriksaan tersebut saat ini dinyatakan:</span><br>
        <span style="font-size: 15pt; font-weight: bold; letter-spacing: 1px;">"{{ strtoupper($surat->kesimpulan) }}"</span>
    </div>

    <p class="paragraf">
        Surat keterangan ini diterbitkan atas permintaan yang bersangkutan untuk keperluan: <strong>{{ $surat->keperluan }}</strong>.
    </p>
    <p class="paragraf">
        Demikian surat keterangan ini kami buat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.
    </p>

    <div class="ttd-box">
        <p style="margin-bottom: 2px;">{{ $surat->lokasi_surat }}, {{ date('d F Y', strtotime($surat->tanggal_surat)) }}</p>
        <p style="margin-top: 0; font-weight: bold;">Pemeriksa / Praktisi Keperawatan,</p>
        <div style="height: 75px;"></div>
        <p style="margin-bottom: 0; font-weight: bold; text-decoration: underline;">{{ $surat->nama_pemeriksa }}</p>
        <p style="margin-top: 2px; font-size: 10pt;">SIPP: {{ $surat->sipp_pemeriksa }}</p>
    </div>

    <div class="clear"></div>
</body>
</html>

