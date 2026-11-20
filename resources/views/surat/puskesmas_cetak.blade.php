<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Pelaporan Puskesmas - {{ $lap->no_laporan }}</title>
    <style>
        @page { size: A4 portrait; margin: 1.2cm 1.5cm; }
        * { box-sizing: border-box; color: #000; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 10pt; line-height: 1.35; margin: 0; }
        .header-kop { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 6px; margin-bottom: 10px; }
        .kop-logo-ppni { width: 65px; height: 65px; object-fit: contain; }
        .kop-logo-klinik { width: 65px; height: 65px; object-fit: cover; border-radius: 50%; border: 1px solid #0284c7; }
        .kop-text { text-align: center; flex: 1; padding: 0 10px; }
        .header-kop h2 { margin: 0; font-size: 13pt; font-weight: bold; }
        .header-kop h3 { margin: 2px 0; font-size: 11pt; font-weight: bold; color: #047857; }
        .header-kop p { margin: 0; font-size: 8.5pt; }
        .section-title { font-weight: bold; font-size: 10pt; background: #ecfdf5; padding: 3px 6px; margin-top: 8px; margin-bottom: 4px; border-left: 3px solid #047857; }
        table.tbl-identitas { width: 100%; margin-bottom: 6px; }
        table.tbl-identitas td { padding: 2px 4px; vertical-align: top; font-size: 9.5pt; }
        table.tbl-grid { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table.tbl-grid th, table.tbl-grid td { border: 1px solid #000; padding: 6px 8px; vertical-align: top; font-size: 9pt; }
        table.tbl-grid th { background-color: #f8fafc; text-align: center; font-weight: bold; }
        .ttd-box { margin-top: 25px; float: right; text-align: center; width: 250px; font-size: 9.5pt; }
        .clear { clear: both; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 12px; text-align: right;">
        <button onclick="window.print()" style="padding: 6px 16px; background: #047857; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak Laporan Puskesmas
        </button>
    </div>

    <div class="header-kop">
        <img src="{{ asset('img/ppni.webp') }}" class="kop-logo-ppni" alt="Logo PPNI">
        <div class="kop-text">
            <h2>LAPORAN DATA PELAYANAN PRAKTIK MANDIRI</h2>
            <h3>PRAKTIK MANDIRI KEPERAWATAN WELLY</h3>
            <p>Welly Prestikasari, S.Kep., Ners., M.Kep. &bull; Nomor SIPP: 503/36/SIPP/IV.14/VIII/2021</p>
            <p>Dusun 3 RT/RW 015/004, Desa Sidodadi Kec. Teluk Pandan - Kab. Pesawaran &bull; Telp. 0813 6864 0404</p>
        </div>
        <img src="{{ asset('img/klinik.jpeg') }}" class="kop-logo-klinik" alt="Logo Klinik Welly">
    </div>

    <div class="section-title">1. INFORMASI PELAPORAN</div>
    <table class="tbl-identitas">
        <tr>
            <td width="160px">Nomor Laporan</td>
            <td width="10px">:</td>
            <td width="260px"><strong>{{ $lap->no_laporan }}</strong></td>
            <td width="140px">Tanggal Lapor</td>
            <td width="10px">:</td>
            <td>{{ date('d-m-Y', strtotime($lap->tanggal_lapor)) }}</td>
        </tr>
        <tr>
            <td>Tujuan Puskesmas</td>
            <td>:</td>
            <td><strong>{{ $lap->nama_puskesmas }}</strong></td>
            <td>Periode Pelayanan</td>
            <td>:</td>
            <td><strong>{{ $lap->bulan_tahun }}</strong></td>
        </tr>
    </table>

    <div class="section-title">2. REKAPITULASI KUNJUNGAN PASIEN</div>
    <table class="tbl-grid">
        <thead>
            <tr>
                <th>Total Pasien Baru</th>
                <th>Total Pasien Lama</th>
                <th>Total Seluruh Kunjungan</th>
                <th>Pasien Dirujuk</th>
            </tr>
        </thead>
        <tbody>
            <tr style="text-align: center; font-size: 11pt; font-weight: bold;">
                <td>{{ $lap->total_pasien_baru }}</td>
                <td>{{ $lap->total_pasien_lama }}</td>
                <td style="color: #047857;">{{ $lap->total_kunjungan }}</td>
                <td style="color: #b91c1c;">{{ $lap->pasien_rujuk }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">3. DISTRIBUSI KASUS & ASPEK PELAYANAN</div>
    <table class="tbl-grid">
        <thead>
            <tr>
                <th width="40px">No</th>
                <th>Jenis Kasus / Pelayanan</th>
                <th width="120px">Jumlah Kasus</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">1</td>
                <td>Diabetes Mellitus (DM) & Asuhan Perawatan Luka Modern</td>
                <td style="text-align: center; font-weight: bold;">{{ $lap->kasus_dm_luka }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">2</td>
                <td>Penyakit Hipertensi & Kardiovaskular</td>
                <td style="text-align: center; font-weight: bold;">{{ $lap->kasus_hipertensi }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">3</td>
                <td>Penyakit Infeksi Akut</td>
                <td style="text-align: center; font-weight: bold;">{{ $lap->kasus_infeksi }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">4</td>
                <td>Tindakan Bedah Minor (Hecting, Aff Hecting, dll)</td>
                <td style="text-align: center; font-weight: bold;">{{ $lap->kasus_bedah_minor }}</td>
            </tr>
            <tr>
                <td style="text-align: center;">5</td>
                <td>Kasus Klinis Lainnya</td>
                <td style="text-align: center; font-weight: bold;">{{ $lap->kasus_lainnya }}</td>
            </tr>
        </tbody>
    </table>

    @if(!empty($lap->keterangan))
        <div class="section-title">4. CATATAN & KETERANGAN TAMBAHAN</div>
        <p style="margin: 4px 6px; font-size: 9.5pt;">{!! nl2br(e($lap->keterangan)) !!}</p>
    @endif

    <div class="ttd-box">
        Pesawaran, {{ date('d F Y', strtotime($lap->tanggal_lapor)) }}<br>
        Pimpinan Praktik Mandiri,<br>
        <div style="height: 55px;"></div>
        <strong style="text-decoration: underline;">{{ $lap->penanggung_jawab }}</strong><br>
        <span>SIPP: 503/36/SIPP/IV.14/VIII/2021</span>
    </div>
    <div class="clear"></div>
</body>
</html>
