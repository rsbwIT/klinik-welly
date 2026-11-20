<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Merja Klien - {{ $merja->nama_klien }}</title>
    <style>
        @page { size: A4 portrait; margin: 1.2cm 1.5cm; }
        * { box-sizing: border-box; color: #000; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 10pt; line-height: 1.35; margin: 0; }
        .header-kop { display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 6px; margin-bottom: 10px; }
        .kop-logo-ppni { width: 65px; height: 65px; object-fit: contain; }
        .kop-logo-klinik { width: 65px; height: 65px; object-fit: cover; border-radius: 50%; border: 1px solid #0284c7; }
        .kop-text { text-align: center; flex: 1; padding: 0 10px; }
        .header-kop h2 { margin: 0; font-size: 13pt; font-weight: bold; }
        .header-kop h3 { margin: 2px 0; font-size: 11pt; font-weight: bold; color: #0284c7; }
        .header-kop p { margin: 0; font-size: 8.5pt; }
        .section-title { font-weight: bold; font-size: 10pt; background: #f0f9ff; padding: 3px 6px; margin-top: 8px; margin-bottom: 4px; border-left: 3px solid #0284c7; }
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
        <button onclick="window.print()" style="padding: 6px 16px; background: #0284c7; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak Buku Merja Klien
        </button>
    </div>

    <div class="header-kop">
        <img src="{{ asset('img/ppni.webp') }}" class="kop-logo-ppni" alt="Logo PPNI">
        <div class="kop-text">
            <h2>BUKU MERJA KLIEN / KARTU ASUHAN RAWAT LUKA</h2>
            <h3>PRAKTIK MANDIRI KEPERAWATAN WELLY</h3>
            <p>Welly Prestikasari, S.Kep., Ners., M.Kep. &bull; Nomor SIPP: 503/36/SIPP/IV.14/VIII/2021</p>
            <p>Dusun 3 RT/RW 015/004, Desa Sidodadi Kec. Teluk Pandan - Kab. Pesawaran &bull; Telp. 0813 6864 0404</p>
        </div>
        <img src="{{ asset('img/klinik.jpeg') }}" class="kop-logo-klinik" alt="Logo Klinik Welly">
    </div>

    <div class="section-title">1. IDENTITAS KLIEN & PERIODE PERAWATAN</div>
    <table class="tbl-identitas">
        <tr>
            <td width="160px">Nomor Rawat</td>
            <td width="10px">:</td>
            <td width="260px"><strong>{{ $merja->no_rawat ?: '-' }}</strong></td>
            <td width="160px">Tgl Mulai Rawat</td>
            <td width="10px">:</td>
            <td>{{ date('d-m-Y', strtotime($merja->tgl_mulai_rawat)) }}</td>
        </tr>
        <tr>
            <td>Nomor Rekam Medis</td>
            <td>:</td>
            <td><strong>{{ $merja->no_rkm_medis ?: '-' }}</strong></td>
            <td>Tgl Selesai / Evaluasi</td>
            <td>:</td>
            <td>{{ $merja->tgl_selesai_rawat ? date('d-m-Y', strtotime($merja->tgl_selesai_rawat)) : 'Masih Berjalan' }}</td>
        </tr>
        <tr>
            <td>Nama Lengkap Klien</td>
            <td>:</td>
            <td><strong>{{ $merja->nama_klien }}</strong></td>
            <td>Status Perawatan</td>
            <td>:</td>
            <td><strong>{{ $merja->status_perawatan }}</strong></td>
        </tr>
    </table>

    <div class="section-title">2. PENGKAJIAN KHUSUS & TARGET RAWAT LUKA</div>
    <table class="tbl-identitas">
        <tr>
            <td width="180px">Diagnosa Medis Klien</td>
            <td width="10px">:</td>
            <td>{{ $merja->diagnosa_medis ?: '-' }}</td>
        </tr>
        <tr>
            <td>Etiologi / Jenis Luka</td>
            <td>:</td>
            <td><strong>{{ $merja->jenis_luka ?: '-' }}</strong></td>
        </tr>
        <tr>
            <td>Derajat / Dimensi Luka</td>
            <td>:</td>
            <td>{{ $merja->derajat_luka ?: '-' }}</td>
        </tr>
        <tr>
            <td>Target Asuhan / Goals</td>
            <td>:</td>
            <td>{!! nl2br(e($merja->tujuan_target_perawatan ?: '-')) !!}</td>
        </tr>
        <tr>
            <td>Hasil Evaluasi Akhir</td>
            <td>:</td>
            <td>{!! nl2br(e($merja->evaluasi_akhir ?: '-')) !!}</td>
        </tr>
        @if(!empty($merja->catatan))
        <tr>
            <td>Catatan Khusus Perawat</td>
            <td>:</td>
            <td>{!! nl2br(e($merja->catatan)) !!}</td>
        </tr>
        @endif
    </table>

    <div class="ttd-box">
        Pesawaran, {{ date('d F Y', strtotime($merja->tgl_mulai_rawat)) }}<br>
        Perawat Penanggung Jawab Asuhan,<br>
        <div style="height: 55px;"></div>
        <strong style="text-decoration: underline;">{{ $merja->penanggung_jawab }}</strong><br>
        <span>SIPP: 503/36/SIPP/IV.14/VIII/2021</span>
    </div>
    <div class="clear"></div>
</body>
</html>
