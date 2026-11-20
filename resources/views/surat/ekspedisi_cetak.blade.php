<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Buku Ekspedisi - Praktik Mandiri Keperawatan Welly</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.2cm;
        }
        * {
            box-sizing: border-box;
            color: #000;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #000;
        }
        .header-kop {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 14px;
        }
        .kop-logo-ppni {
            width: 60px;
            height: 60px;
            object-fit: contain;
            flex-shrink: 0;
        }
        .kop-logo-klinik {
            width: 60px;
            height: 60px;
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
        .kop-text h2 { margin: 0; font-size: 13pt; text-transform: uppercase; }
        .kop-text h3 { margin: 2px 0; font-size: 11pt; color: #0369a1; }
        .kop-text p { margin: 0; font-size: 8.5pt; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px 6px;
            font-size: 9pt;
        }
        th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 12px; text-align: right;">
        <button onclick="window.print()" style="padding: 6px 16px; background: #059669; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak Buku Ekspedisi
        </button>
    </div>

    <div class="header-kop">
        <img src="{{ asset('img/ppni.webp') }}" class="kop-logo-ppni" alt="Logo PPNI">
        <div class="kop-text">
            <h2>BUKU EKSPEDISI SURAT KELUAR</h2>
            <h3>PRAKTIK MANDIRI KEPERAWATAN WELLY</h3>
            <p>Welly Prestikasari, S.Kep., Ners., M.Kep. &bull; Nomor SIPP: 503/36/SIPP/IV.14/VIII/2021</p>
            <p>Dusun 3 RT/RW 015/004, Desa Sidodadi Kec. Teluk Pandan - Kab. Pesawaran &bull; Telp. 0813 6864 0404</p>
        </div>
        <img src="{{ asset('img/klinik.jpeg') }}" class="kop-logo-klinik" alt="Logo Klinik">
    </div>

    <table>
        <thead>
            <tr>
                <th width="35px">NO</th>
                <th width="100px">HARI / TGL</th>
                <th width="160px">NAMA PENERIMA</th>
                <th width="130px">JABATAN</th>
                <th width="170px">INSTANSI</th>
                <th>MAKSUD / TUJUAN EKSPEDISI</th>
                <th width="130px">TANDA TANGAN</th>
            </tr>
            <tr style="font-size: 8pt; background: #e2e8f0;">
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>
                <th>6</th>
                <th>7</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $row)
                <tr>
                    <td style="text-align: center;">{{ $i + 1 }}</td>
                    <td style="text-align: center;">{{ date('d/m/Y', strtotime($row->tanggal)) }}</td>
                    <td><strong>{{ $row->nama_penerima }}</strong></td>
                    <td>{{ $row->jabatan ?: '-' }}</td>
                    <td>{{ $row->instansi }}</td>
                    <td>{{ $row->maksud_tujuan }}</td>
                    <td style="text-align: center; font-style: italic;">{{ $row->tanda_tangan }}</td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align: center; padding: 15px;">Belum ada arsip ekspedisi</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
