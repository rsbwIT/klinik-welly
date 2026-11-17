<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Kasir & Billing Pasien - {{ $pasien->nama_lengkap }} ({{ $pasien->no_rkm_medis }})</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm 15mm 15mm;
        }

        * {
            box-sizing: border-box;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            color: #1a1a1a;
        }

        body {
            margin: 0;
            padding: 0;
            background: #eef2f6;
            font-size: 11.5px;
            line-height: 1.45;
        }

        /* Container Dokumen Cetak */
        .page-sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            background: #ffffff;
            padding: 16mm 18mm;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            position: relative;
        }

        /* Kop Surat Resmi Standard Khanza & Bundling2 */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 2.5px solid #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
            position: relative;
        }

        .kop-logo {
            width: 75px;
            height: 75px;
            object-fit: contain;
            margin-right: 15px;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-text h2 {
            margin: 0;
            font-size: 17px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #0f172a;
            text-transform: uppercase;
        }

        .kop-text h4 {
            margin: 2px 0;
            font-size: 12px;
            font-weight: 700;
            color: #0369a1;
        }

        .kop-text p {
            margin: 2px 0 0 0;
            font-size: 10px;
            color: #475569;
        }

        .kop-garis-bawah {
            border-bottom: 1px solid #000;
            margin-top: 2px;
            margin-bottom: 14px;
        }

        /* Judul Dokumen */
        .title-doc-box {
            text-align: center;
            margin-bottom: 14px;
        }

        .title-doc-box .main-title {
            display: inline-block;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            padding-bottom: 3px;
        }

        .title-doc-box .sub-title {
            font-size: 10.5px;
            font-weight: 600;
            color: #64748b;
            margin-top: 3px;
        }

        /* Box Profil Pasien (2 Kolom Rapi) */
        .box-pasien {
            border: 1.5px solid #334155;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 14px;
            background: #f8fafc;
        }

        .grid-pasien {
            display: grid;
            grid-template-columns: 1fr 1fr;
            column-gap: 20px;
            row-gap: 4px;
        }

        .item-row {
            display: flex;
            align-items: baseline;
            font-size: 11px;
        }

        .item-label {
            width: 120px;
            font-weight: 600;
            color: #475569;
        }

        .item-colon {
            width: 10px;
            text-align: center;
        }

        .item-val {
            flex: 1;
            font-weight: 700;
            color: #0f172a;
        }

        /* Tabel Rincian Billing */
        .table-billing {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .table-billing th, .table-billing td {
            border: 1px solid #334155;
            padding: 8px 12px;
            font-size: 11px;
        }

        .table-billing th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge-lunas {
            display: inline-block;
            background: #dcfce7;
            color: #15803d;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 4px;
            border: 1px solid #bbf7d0;
        }

        .total-row {
            background: #f8fafc;
            font-size: 13px;
            font-weight: bold;
        }

        /* Tanda Tangan */
        .sign-area {
            width: 100%;
            margin-top: 35px;
        }

        .sign-area td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 11px;
        }

        /* Toolbar Layar */
        .toolbar-top {
            width: 210mm;
            margin: 15px auto 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 12px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-print {
            background: #0284c7;
            color: #fff;
        }
        .btn-print:hover {
            background: #0369a1;
        }

        .btn-back {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
        }
        .btn-back:hover {
            background: #f8fafc;
        }

        @media print {
            body {
                background: #ffffff;
            }
            .toolbar-top {
                display: none;
            }
            .page-sheet {
                box-shadow: none;
                margin: 0;
                padding: 0;
                width: 100%;
                min-height: auto;
            }
        }
    </style>
</head>
<body>

    <!-- TOOLBAR AKSI (HANYA DITAMPILKAN DI LAYAR) -->
    <div class="toolbar-top">
        <a href="{{ route('pelayanan.billing', $pasien->id) }}" class="btn-action btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Kasir & Billing
        </a>
        <div style="display: flex; gap: 8px;">
            <button onclick="window.print()" class="btn-action btn-print">
                <i class="fas fa-print"></i> Cetak Kwitansi Kasir (PDF/Printer)
            </button>
        </div>
    </div>

    <!-- LEMBAR DOKUMEN CETAK KWITANSI / BUKTI PEMBAYARAN -->
    <div class="page-sheet">
        <!-- KOP SURAT RESMI -->
        <div class="kop-surat">
            <img src="{{ asset('img/klinik.jpeg') }}" alt="Logo Klinik" class="kop-logo" onerror="this.src='{{ asset('img/logo.png') }}'">
            <div class="kop-text">
                <h2>PRAKTIK MANDIRI PERAWAT LUKA & RAWAT JALAN</h2>
                <h4>KLINIK PERAWATAN LUKA MODERN WELLY</h4>
                <p>Jl. Raya Kesehatan No. 12, Telp: (021) 87654321 / WhatsApp: 0838-2434-7684 &bull; Email: welly.perawat@gmail.com</p>
                <p style="font-size: 9px; color: #64748b;">SIP: 446/001/SIP/DPMPTSP/2026 &bull; Tanggal Izin Operasional: 2026</p>
            </div>
        </div>
        <div class="kop-garis-bawah"></div>

        <!-- JUDUL DOKUMEN -->
        <div class="title-doc-box">
            <div class="main-title">KWITANSI / BUKTI PEMBAYARAN RAWAT JALAN</div>
            <div class="sub-title">Sistem Informasi Billing & Kasir Pelayanan Rawat Jalan Terintegrasi</div>
        </div>

        <!-- BOX IDENTITAS PASIEN -->
        <div class="box-pasien">
            <div class="grid-pasien">
                <div class="item-row">
                    <span class="item-label">No. Rekam Medis (RM)</span>
                    <span class="item-colon">:</span>
                    <span class="item-val font-monospace" style="color: #059669; font-size: 13px;">{{ $pasien->no_rkm_medis }}</span>
                </div>
                <div class="item-row">
                    <span class="item-label">No. Registrasi / Rawat</span>
                    <span class="item-colon">:</span>
                    <span class="item-val font-monospace" style="color: #0284c7;">{{ $pasien->no_rawat }}</span>
                </div>
                <div class="item-row">
                    <span class="item-label">Nama Lengkap Pasien</span>
                    <span class="item-colon">:</span>
                    <span class="item-val" style="font-size: 12px;">{{ $pasien->nama_lengkap }}</span>
                </div>
                <div class="item-row">
                    <span class="item-label">No. Nota Kasir</span>
                    <span class="item-colon">:</span>
                    <span class="item-val font-monospace" style="font-weight: 800; color: #b91c1c;">{{ $billing->no_nota ?? $pasien->no_nota ?? '-' }}</span>
                </div>
                <div class="item-row">
                    <span class="item-label">Tgl. & Jam Bayar</span>
                    <span class="item-colon">:</span>
                    <span class="item-val">{{ $billing->tgl_byr ?? date('Y-m-d') }} {{ $billing->jam_byr ?? '' }}</span>
                </div>
                <div class="item-row">
                    <span class="item-label">Poliklinik Tujuan</span>
                    <span class="item-colon">:</span>
                    <span class="item-val">{{ $pasien->nm_poli ?? 'Poli Perawatan Luka Modern' }}</span>
                </div>
                <div class="item-row">
                    <span class="item-label">Dokter DPJP</span>
                    <span class="item-colon">:</span>
                    <span class="item-val">{{ $pasien->dokter_dpjp ?? 'dr. Welly' }}</span>
                </div>
                <div class="item-row">
                    <span class="item-label">Penjamin / Cara Bayar</span>
                    <span class="item-colon">:</span>
                    <span class="item-val">{{ strtoupper($pasien->jenis_bayar ?? 'UMUM') }}</span>
                </div>
                <div class="item-row">
                    <span class="item-label">Status Pembayaran</span>
                    <span class="item-colon">:</span>
                    <span class="item-val">
                        <span class="badge-lunas">
                            <i class="fas fa-check-circle mr-1"></i> {{ ($pasien->status_bayar ?? 'Sudah') == 'Sudah' ? 'LUNAS / SUDAH DIBAYAR' : 'BELUM DIBAYAR' }}
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <!-- TABEL RINCIAN BIAYA TRANSAKSI -->
        <table class="table-billing">
            <thead>
                <tr>
                    <th width="40" class="text-center">No</th>
                    <th>Uraian Komponen Pelayanan & Tindakan</th>
                    <th width="180" class="text-right">Subtotal (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center font-monospace">1</td>
                    <td>
                        <strong>Biaya Pendaftaran / Registrasi Rawat Jalan</strong><br>
                        <small style="color: #64748b;">Administrasi loket pendaftaran rekam medis</small>
                    </td>
                    <td class="text-right font-monospace" style="font-weight: 600;">
                        {{ number_format($billing->biaya_registrasi ?? 40000, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center font-monospace">2</td>
                    <td>
                        <strong>Biaya Jasa Dokter & Asuhan Tindakan Medis</strong><br>
                        <small style="color: #64748b;">Konsultasi medis, tindakan luka modern, dan asuhan keperawatan</small>
                    </td>
                    <td class="text-right font-monospace" style="font-weight: 600;">
                        {{ number_format($billing->biaya_tindakan ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center font-monospace">3</td>
                    <td>
                        <strong>Biaya Obat, Salep & Balutan Modern (Farmasi)</strong><br>
                        <small style="color: #64748b;">Rincian resep obat pulang dan balutan luka modern</small>
                    </td>
                    <td class="text-right font-monospace" style="font-weight: 600;">
                        {{ number_format($billing->biaya_obat ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td class="text-center font-monospace">4</td>
                    <td>
                        <strong>Pemeriksaan Laboratorium</strong><br>
                        <small style="color: #64748b;">Pemeriksaan sampel spesimen klinis</small>
                    </td>
                    <td class="text-right font-monospace" style="font-weight: 600;">
                        {{ number_format($billing->biaya_lab ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
                <tr class="total-row">
                    <td colspan="2" class="text-right" style="font-size: 13px;">TOTAL BIAYA PELAYANAN :</td>
                    <td class="text-right font-monospace" style="font-size: 15px; color: #0284c7;">
                        Rp {{ number_format($billing->total_bayar ?? 40000, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- AREA TANDA TANGAN -->
        <table class="sign-area">
            <tr>
                <td>
                    Pasien / Keluarga,<br><br>
                    <div style="height: 55px; display: flex; align-items: center; justify-content: center;">
                        <span style="font-size: 9px; color: #94a3b8; font-style: italic;">[ Tanda Tangan Penerima ]</span>
                    </div>
                    ( <strong>{{ $pasien->nama_lengkap }}</strong> )
                </td>
                <td>
                    {{ $pasien->kabupaten ?? 'Bandar Lampung' }}, {{ date('d F Y') }}<br>
                    Petugas Kasir / Bagian Keuangan,<br>
                    <div style="height: 55px; display: flex; align-items: center; justify-content: center;">
                        <div style="border: 1px solid #059669; padding: 4px 8px; border-radius: 4px; background: #ecfdf5; font-size: 9px; color: #047857; font-weight: bold;">
                            <i class="fas fa-check-double text-success"></i> TRANSAKSI LUNAS & TERCATAT
                        </div>
                    </div>
                    ( <strong>{{ $billing->petugas ?? Auth::user()->name ?? 'Kasir Klinik' }}</strong> )<br>
                    <small style="color: #64748b; font-size: 9px;">Klinik Perawatan Luka Modern Welly</small>
                </td>
            </tr>
        </table>

        <div style="margin-top: 40px; border-top: 1px dashed #cbd5e1; padding-top: 6px; font-size: 8.5px; color: #94a3b8; display: flex; justify-content: space-between;">
            <span>Kwitansi pembayaran ini sah diterbitkan melalui Sistem Informasi Kasir & Billing Rawat Jalan Klinik</span>
            <span>Waktu Cetak: {{ date('d/m/Y H:i:s') }}</span>
        </div>
    </div>

</body>
</html>
