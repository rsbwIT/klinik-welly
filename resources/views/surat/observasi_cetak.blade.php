<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Observasi Khusus - {{ $obs->nama_pasien }}</title>
    <style>
        @page { size: A4 portrait; margin: 1.2cm 1.5cm; }
        * { box-sizing: border-box; color: #000; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 10pt; line-height: 1.35; margin: 0; }
        .header-kop { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 6px; margin-bottom: 10px; }
        .kop-logo-ppni { width: 65px; height: 65px; object-fit: contain; }
        .kop-logo-klinik { width: 65px; height: 65px; object-fit: cover; border-radius: 50%; border: 1px solid #0284c7; }
        .kop-text { text-align: center; flex: 1; padding: 0 10px; }
        .header-kop h2 { margin: 0; font-size: 13pt; font-weight: bold; }
        .header-kop h3 { margin: 2px 0; font-size: 11pt; font-weight: bold; color: #b91c1c; }
        .header-kop p { margin: 0; font-size: 8.5pt; }
        .section-title { font-weight: bold; font-size: 10pt; background: #fef2f2; padding: 3px 6px; margin-top: 8px; margin-bottom: 4px; border-left: 3px solid #b91c1c; }
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
        <button onclick="window.print()" style="padding: 6px 16px; background: #b91c1c; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak Lembar Observasi
        </button>
    </div>

    <div class="header-kop">
        <img src="{{ asset('img/ppni.webp') }}" class="kop-logo-ppni" alt="Logo PPNI">
        <div class="kop-text">
            <h2>LEMBAR OBSERVASI KHUSUS PASIEN</h2>
            <h3>PRAKTIK MANDIRI KEPERAWATAN WELLY</h3>
            <p>Welly Prestikasari, S.Kep., Ners., M.Kep. &bull; Nomor SIPP: 503/36/SIPP/IV.14/VIII/2021</p>
            <p>Dusun 3 RT/RW 015/004, Desa Sidodadi Kec. Teluk Pandan - Kab. Pesawaran &bull; Telp. 0813 6864 0404</p>
        </div>
        <img src="{{ asset('img/klinik.jpeg') }}" class="kop-logo-klinik" alt="Logo Klinik Welly">
    </div>

    <div class="section-title">1. IDENTITAS PASIEN</div>
    <table class="tbl-identitas">
        <tr>
            <td width="160px">Nomor Rawat</td>
            <td width="10px">:</td>
            <td width="260px"><strong>{{ $obs->no_rawat ?: '-' }}</strong></td>
            <td width="140px">Tanggal Pemantauan</td>
            <td width="10px">:</td>
            <td>{{ date('d-m-Y', strtotime($obs->tanggal)) }} {{ substr($obs->jam, 0, 5) }} WIB</td>
        </tr>
        <tr>
            <td>Nomor Rekam Medis</td>
            <td>:</td>
            <td><strong>{{ $obs->no_rkm_medis ?: '-' }}</strong></td>
            <td>Jenis Kelamin / Umur</td>
            <td>:</td>
            <td>{{ $obs->jenis_kelamin ?: '-' }} / {{ $obs->umur ?: '-' }}</td>
        </tr>
        <tr>
            <td>Nama Pasien</td>
            <td>:</td>
            <td><strong>{{ $obs->nama_pasien }}</strong></td>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $obs->alamat ?: '-' }}</td>
        </tr>
    </table>

    <div class="section-title">2. PEMANTAUAN TANDA VITAL & KONDISI FISIK</div>
    <table class="tbl-grid">
        <thead>
            <tr>
                <th>Kesadaran / GCS</th>
                <th>Tekanan Darah</th>
                <th>Nadi / HR</th>
                <th>Respirasi</th>
                <th>Suhu</th>
                <th>SpO2</th>
                <th>Urine Output</th>
            </tr>
        </thead>
        <tbody>
            <tr style="text-align: center;">
                <td><strong>{{ $obs->kesadaran }}</strong><br><small>GCS: {{ $obs->gcs ?: '-' }}</small></td>
                <td>{{ $obs->td ?: '-' }}</td>
                <td>{{ $obs->hr ?: '-' }}</td>
                <td>{{ $obs->rr ?: '-' }}</td>
                <td>{{ $obs->suhu ?: '-' }}</td>
                <td>{{ $obs->spo2 ?: '-' }}</td>
                <td>{{ $obs->urine_output ?: '-' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">3. OBSERVASI KHUSUS, CAIRAN INFUS & CATATAN TERAPI</div>
    <table class="tbl-identitas" style="margin-top: 6px;">
        <tr>
            <td width="180px">Terapi Cairan Infus</td>
            <td width="10px">:</td>
            <td>{{ $obs->cairan_infus ?: '-' }} (Kecepatan: {{ $obs->tetesan_infus ?: '-' }})</td>
        </tr>
        <tr>
            <td>Kondisi Luka / Area Tindakan</td>
            <td>:</td>
            <td>{!! nl2br(e($obs->kondisi_luka ?: '-')) !!}</td>
        </tr>
        <tr>
            <td>Tindakan Terapi Diberikan</td>
            <td>:</td>
            <td>{!! nl2br(e($obs->tindakan_terapi ?: '-')) !!}</td>
        </tr>
        <tr>
            <td>Catatan Perkembangan Pasien</td>
            <td>:</td>
            <td>{!! nl2br(e($obs->catatan_perkembangan ?: '-')) !!}</td>
        </tr>
    </table>

    <div class="ttd-box">
        Pesawaran, {{ date('d F Y', strtotime($obs->tanggal)) }}<br>
        Perawat Penanggung Jawab,<br>
        <div style="height: 55px;"></div>
        <strong style="text-decoration: underline;">{{ $obs->petugas }}</strong><br>
        <span>SIPP: 503/36/SIPP/IV.14/VIII/2021</span>
    </div>
    <div class="clear"></div>
</body>
</html>
