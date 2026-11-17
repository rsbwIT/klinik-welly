<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penjualan Bebas - {{ $penjualan->no_nota }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #f1f5f9; padding: 25px; color: #1e293b; }
        .no-print { max-width: 580px; margin: 0 auto 15px auto; display: flex; gap: 10px; }
        .btn-print { background: #059669; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 700; cursor: pointer; }
        .btn-close { background: #64748b; color: #fff; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 700; cursor: pointer; }
        .sheet { background: #ffffff; max-width: 580px; margin: 0 auto; padding: 25px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); border-radius: 12px; border: 1px solid #cbd5e1; }
        .header-kop { display: flex; align-items: center; border-bottom: 2px solid #0f172a; padding-bottom: 12px; margin-bottom: 15px; }
        .logo-kop img { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; margin-right: 15px; }
        .kop-text h2 { font-size: 1.1rem; font-weight: 800; color: #003b73; text-transform: uppercase; }
        .kop-text h3 { font-size: 0.9rem; font-weight: 700; color: #059669; }
        .kop-text p { font-size: 0.75rem; color: #475569; }
        .title-doc { text-align: center; font-size: 1.05rem; font-weight: 800; letter-spacing: 0.5px; margin: 12px 0; text-decoration: underline; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 0.83rem; }
        .info-table td { padding: 3px 0; }
        .info-label { width: 120px; color: #64748b; font-weight: 600; }
        .info-sep { width: 15px; font-weight: 700; text-align: center; }
        .info-val { font-weight: 700; color: #0f172a; }
        .table-item { width: 100%; border-collapse: collapse; margin: 12px 0; font-size: 0.83rem; }
        .table-item th { background: #f8fafc; border: 1px solid #cbd5e1; padding: 8px; text-align: left; }
        .table-item td { border: 1px solid #cbd5e1; padding: 7px 8px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer-total { margin-top: 10px; width: 100%; font-size: 0.85rem; }
        .footer-total td { padding: 4px 0; }
        .sign-area { display: flex; justify-content: space-between; margin-top: 25px; padding-top: 10px; font-size: 0.82rem; }
        .sign-box { text-align: center; width: 180px; }
        .sign-space { height: 50px; }

        @media print {
            body { background: #ffffff; padding: 0; }
            .sheet { box-shadow: none; border: none; padding: 0; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()"><i class="fas fa-print"></i> Cetak Struk</button>
        <button class="btn-close" onclick="window.close()"><i class="fas fa-times"></i> Tutup</button>
    </div>

    <div class="sheet">
        <!-- KOP STRUK -->
        <div class="header-kop">
            <div class="logo-kop">
                <img src="{{ asset('img/klinik.jpeg') }}" alt="Logo">
            </div>
            <div class="kop-text">
                <h2>KLINIK UTAMA RAWAT INAP & RAWAT JALAN</h2>
                <h3>PRAKTIK MANDIRI PERAWAT LUKA WELLY</h3>
                <p>Jl. Raya Utama No. 12, Jawa Timur &bull; Telp / WA: 0812-3456-7890</p>
            </div>
        </div>

        <div class="title-doc">STRUK PENJUALAN OBAT BEBAS</div>

        <!-- INFO TRANSAKSI -->
        <table class="info-table">
            <tr>
                <td class="info-label">No. Nota</td>
                <td class="info-sep">:</td>
                <td class="info-val">{{ $penjualan->no_nota }}</td>
                <td class="info-label">Tanggal</td>
                <td class="info-sep">:</td>
                <td class="info-val">{{ $penjualan->tgl_jual }} {{ substr($penjualan->jam_jual, 0, 5) }}</td>
            </tr>
            <tr>
                <td class="info-label">Nama Pembeli</td>
                <td class="info-sep">:</td>
                <td class="info-val">{{ $penjualan->nama_pembeli }}</td>
                <td class="info-label">Metode Bayar</td>
                <td class="info-sep">:</td>
                <td class="info-val">{{ $penjualan->cara_bayar }}</td>
            </tr>
            <tr>
                <td class="info-label">No. Telepon</td>
                <td class="info-sep">:</td>
                <td class="info-val">{{ $penjualan->no_telp ?? '-' }}</td>
                <td class="info-label">Kasir / Petugas</td>
                <td class="info-sep">:</td>
                <td class="info-val">{{ $penjualan->petugas ?? 'Petugas Farmasi' }}</td>
            </tr>
        </table>

        <!-- ITEM TRANSAKSI -->
        <table class="table-item">
            <thead>
                <tr>
                    <th width="30" class="text-center">No</th>
                    <th>Nama Barang / Balutan</th>
                    <th width="50" class="text-center">Qty</th>
                    <th width="100" class="text-right">Harga</th>
                    <th width="110" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $idx => $it)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td><strong>{{ $it->nama_brng }}</strong> <small>({{ $it->satuan }})</small></td>
                        <td class="text-center font-weight-bold">{{ $it->jumlah }}</td>
                        <td class="text-right">Rp {{ number_format($it->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-right font-weight-bold">Rp {{ number_format($it->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- TOTAL & KEMBALIAN -->
        <table class="footer-total">
            <tr>
                <td width="60%"></td>
                <td width="20%">Total Biaya :</td>
                <td width="20%" class="text-right font-weight-bold">Rp {{ number_format($penjualan->total_biaya, 0, ',', '.') }}</td>
            </tr>
            @if ($penjualan->potongan > 0)
                <tr>
                    <td></td>
                    <td>Potongan / Diskon :</td>
                    <td class="text-right text-danger font-weight-bold">- Rp {{ number_format($penjualan->potongan, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr style="border-top: 1px solid #cbd5e1; font-size: 0.95rem;">
                <td></td>
                <td style="font-weight: 800; color: #003b73;">Harus Bayar :</td>
                <td class="text-right" style="font-weight: 800; color: #003b73;">Rp {{ number_format($penjualan->harus_bayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Jumlah Bayar :</td>
                <td class="text-right font-weight-bold">Rp {{ number_format($penjualan->jumlah_bayar, 0, ',', '.') }}</td>
            </tr>
            <tr style="border-top: 1px dashed #cbd5e1;">
                <td></td>
                <td style="font-weight: 800; color: #059669;">Kembalian :</td>
                <td class="text-right" style="font-weight: 800; color: #059669;">Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div style="margin-top: 15px; font-size: 0.75rem; color: #64748b; text-align: center; border-top: 1px dashed #cbd5e1; padding-top: 8px;">
            Terima kasih atas kunjungan dan kepercayaan Anda.<br>
            Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan kecuali ada perjanjian sebelumnya.
        </div>

        <div class="sign-area">
            <div class="sign-box">
                Pembeli,
                <div class="sign-space"></div>
                <strong>{{ $penjualan->nama_pembeli }}</strong>
            </div>
            <div class="sign-box">
                Kasir / Petugas Farmasi,
                <div class="sign-space"></div>
                <strong>{{ $penjualan->petugas ?? 'Petugas Farmasi' }}</strong>
            </div>
        </div>
    </div>

</body>
</html>

