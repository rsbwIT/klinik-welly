<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Pembelian Obat - {{ $pembelian->no_faktur }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Libre+Barcode+39+Text&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f1f5f9; padding: 25px; color: #1e293b; }
        .no-print { max-width: 650px; margin: 0 auto 20px auto; display: flex; gap: 10px; }
        .btn-print { background: #059669; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 700; cursor: pointer; }
        .btn-close { background: #64748b; color: #fff; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 700; cursor: pointer; }
        .sheet { background: #ffffff; max-width: 650px; margin: 0 auto; padding: 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); border-radius: 12px; border: 1px solid #cbd5e1; }
        .header-kop { display: flex; align-items: center; border-bottom: 2px solid #0f172a; padding-bottom: 12px; margin-bottom: 15px; }
        .logo-kop img { width: 65px; height: 65px; border-radius: 50%; object-fit: cover; margin-right: 15px; }
        .kop-text h2 { font-size: 1.15rem; font-weight: 800; color: #003b73; text-transform: uppercase; }
        .kop-text h3 { font-size: 0.95rem; font-weight: 700; color: #059669; }
        .kop-text p { font-size: 0.78rem; color: #475569; }
        .title-doc { text-align: center; font-size: 1.05rem; font-weight: 800; letter-spacing: 0.5px; margin: 15px 0; text-decoration: underline; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 0.83rem; }
        .info-table td { padding: 3px 0; }
        .info-label { width: 130px; color: #64748b; font-weight: 600; }
        .info-sep { width: 15px; font-weight: 700; text-align: center; }
        .info-val { font-weight: 700; color: #0f172a; }
        .table-item { width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 0.83rem; }
        .table-item th { background: #f8fafc; border: 1px solid #cbd5e1; padding: 8px; text-align: left; }
        .table-item td { border: 1px solid #cbd5e1; padding: 7px 8px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .ttd-box { margin-top: 30px; display: flex; justify-content: space-between; font-size: 0.83rem; }
        @media print { body { background: #fff; padding: 0; } .no-print { display: none !important; } .sheet { box-shadow: none; border: none; padding: 0; width: 100%; } }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()"><i class="fas fa-print"></i> Cetak Bukti Pembelian</button>
        <button class="btn-close" onclick="window.close()">Tutup</button>
    </div>

    <div class="sheet">
        <div class="header-kop">
            <div class="logo-kop">
                <img src="{{ asset('img/klinik.jpeg') }}" alt="Logo Klinik">
            </div>
            <div class="kop-text">
                <h2>Praktik Mandiri Perawat Luka Welly</h2>
                <h3>Welly Prestikasari, S.Kep., Ners., M.Kep.</h3>
                <p>Pelayanan Asuhan Keperawatan, Perawatan Luka Modern, & Farmasi Khusus</p>
                <p>Telp / WA: <strong>+62 813-6864-0404</strong> &bull; Bandar Lampung</p>
            </div>
        </div>

        <div class="title-doc">BUKTI PEMBELIAN OBAT & RESEP</div>

        <table class="info-table">
            <tr>
                <td class="info-label">No. Faktur</td>
                <td class="info-sep">:</td>
                <td class="info-val" style="color: #0284c7;">{{ $pembelian->no_faktur }}</td>
                <td class="info-label">Tanggal</td>
                <td class="info-sep">:</td>
                <td class="info-val">{{ date('d/m/Y', strtotime($pembelian->tgl_beli)) }} {{ $pembelian->jam_beli }}</td>
            </tr>
            <tr>
                <td class="info-label">No. Rekam Medis</td>
                <td class="info-sep">:</td>
                <td class="info-val">{{ $pasien->no_rkm_medis ?? '-' }}</td>
                <td class="info-label">Nama Pasien</td>
                <td class="info-sep">:</td>
                <td class="info-val">{{ $pasien->nama_lengkap ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">No. Rawat</td>
                <td class="info-sep">:</td>
                <td class="info-val">{{ $pembelian->no_rawat }}</td>
                <td class="info-label">Petugas</td>
                <td class="info-sep">:</td>
                <td class="info-val">{{ $pembelian->petugas }}</td>
            </tr>
        </table>

        <table class="table-item">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="45%">Nama Obat / Balutan Modern</th>
                    <th width="20%">Aturan Pakai</th>
                    <th width="10%" class="text-center">Qty</th>
                    <th width="20%" class="text-right">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $idx => $d)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td><strong>{{ $d->nama_brng }}</strong></td>
                        <td><small>{{ $d->aturan_pakai ?? '-' }}</small></td>
                        <td class="text-center">{{ $d->jumlah }} {{ $d->satuan }}</td>
                        <td class="text-right font-weight-bold">{{ number_format($d->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-right font-weight-bold" style="background: #f8fafc;">TOTAL PEMBAYARAN:</td>
                    <td class="text-right font-weight-bold" style="background: #f8fafc; font-size: 0.95rem; color: #059669;">
                        Rp {{ number_format($pembelian->total_bayar, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        @if($pembelian->catatan && $pembelian->catatan != '-')
            <div style="font-size: 0.78rem; color: #64748b; margin-top: 5px;">
                <em>Catatan: {{ $pembelian->catatan }}</em>
            </div>
        @endif

        <div class="ttd-box">
            <div style="text-align: center; width: 180px;">
                <p>Pasien / Keluarga</p>
                <div style="height: 50px;"></div>
                <p style="border-bottom: 1px solid #333; font-weight: 700;">{{ $pasien->nama_lengkap ?? 'Pasien' }}</p>
            </div>
            <div style="text-align: center; width: 220px;">
                <p>Bandar Lampung, {{ date('d/m/Y') }}<br>Petugas Farmasi / Pelayanan</p>
                <div style="height: 50px;"></div>
                <p style="border-bottom: 1px solid #333; font-weight: 700;">{{ $pembelian->petugas }}</p>
            </div>
        </div>
    </div>
</body>
</html>

