<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Pemeriksaan Laboratorium - {{ $pasien->nama_lengkap ?? '-' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Libre+Barcode+39+Text&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: #f1f5f9;
            padding: 25px;
            color: #1e293b;
        }

        .no-print {
            max-width: 800px;
            margin: 0 auto 20px auto;
            display: flex;
            gap: 10px;
        }

        .btn-print {
            background: linear-gradient(135deg, #0077c0 0%, #059669 100%);
            color: #ffffff;
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(0, 119, 192, 0.3);
        }

        .btn-close {
            background: #64748b;
            color: #ffffff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        .sheet {
            max-width: 800px;
            background: #ffffff;
            margin: 0 auto;
            padding: 35px 45px;
            border-radius: 6px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
        }

        /* KOP KLINIK */
        .header-kop {
            display: flex;
            align-items: center;
            gap: 20px;
            border-bottom: 3px double #003b73;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .logo-kop {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            border: 2px solid #0077c0;
        }

        .logo-kop img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .kop-text h2 {
            font-size: 1.25rem;
            font-weight: 800;
            color: #003b73;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .kop-text h3 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #059669;
            margin-bottom: 4px;
        }

        .kop-text p {
            font-size: 0.75rem;
            color: #475569;
            line-height: 1.3;
        }

        .title-doc {
            text-align: center;
            font-size: 1.05rem;
            font-weight: 800;
            color: #003b73;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 15px 0 20px 0;
            text-decoration: underline;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            column-gap: 24px;
            row-gap: 8px;
            margin-bottom: 25px;
            font-size: 0.84rem;
            background: #f8fafc;
            padding: 14px 18px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 3px 0;
            vertical-align: top;
            border: none;
        }

        .info-table td.info-label {
            width: 140px;
            min-width: 140px;
            font-weight: 600;
            color: #475569;
            white-space: nowrap;
        }

        .info-table td.info-sep {
            width: 16px;
            min-width: 16px;
            text-align: center;
            font-weight: 700;
            color: #0f172a;
            padding-right: 6px;
        }

        .info-table td.info-value {
            font-weight: 700;
            color: #0f172a;
            word-break: break-word;
        }

        /* TABEL HASIL */
        .table-hasil {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 0.85rem;
        }

        .table-hasil th {
            background: #f1f5f9;
            color: #003b73;
            font-weight: 700;
            text-align: left;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            text-transform: uppercase;
            font-size: 0.78rem;
        }

        .table-hasil td {
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
        }

        .table-hasil tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .ttd-container {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            font-size: 0.85rem;
        }

        .ttd-box {
            text-align: center;
            width: 250px;
        }

        .ttd-space {
            height: 70px;
        }

        .ttd-name {
            font-weight: 700;
            border-bottom: 1px solid #0f172a;
            padding-bottom: 2px;
            display: inline-block;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .sheet {
                box-shadow: none;
                border: none;
                padding: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak Lembar Hasil Laboratorium
        </button>
        <button class="btn-close" onclick="window.close()">
            Tutup Jendela
        </button>
    </div>

    <div class="sheet">
        <!-- KOP -->
        <div class="header-kop">
            <div class="logo-kop">
                <img src="{{ asset('img/klinik.jpeg') }}" alt="Logo Klinik">
            </div>
            <div class="kop-text">
                <h2>Praktik Mandiri Perawat Luka Welly</h2>
                <h3>Welly Prestikasari, S.Kep., Ners., M.Kep.</h3>
                <p>Pelayanan Asuhan Keperawatan, Perawatan Luka Modern, & Layanan Laboratorium Sederhana</p>
                <p><i class="fab fa-whatsapp text-success"></i> Telp / WA: <strong>0813 6864 0404</strong> &bull; Dusun 3 RT/RW 015/004, Desa Sidodadi Kec. Teluk Pandan - Kab. Pesawaran &bull; <em>"Merawat Luka, Mengembalikan Harapan"</em></p>
            </div>
        </div>

        <div class="title-doc">LEMBAR HASIL PEMERIKSAAN LABORATORIUM</div>

        <!-- IDENTITAS PASIEN (TABEL RAPI SEJAJAR) -->
        <div class="info-grid">
            <div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">No. Rekam Medis</td>
                        <td class="info-sep">:</td>
                        <td class="info-value font-monospace" style="color: #0077c0;">{{ $pasien->no_rkm_medis ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">No. Rawat</td>
                        <td class="info-sep">:</td>
                        <td class="info-value font-monospace">{{ $pasien->no_rawat }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Nama Pasien</td>
                        <td class="info-sep">:</td>
                        <td class="info-value">{{ $pasien->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Jenis Kelamin / Umur</td>
                        <td class="info-sep">:</td>
                        <td class="info-value">{{ $pasien->jenis_kelamin }} ({{ $pasien->umur ?? '-' }})</td>
                    </tr>
                </table>
            </div>
            <div>
                <table class="info-table">
                    <tr>
                        <td class="info-label">Tanggal Periksa</td>
                        <td class="info-sep">:</td>
                        <td class="info-value">{{ date('d/m/Y', strtotime($periksa->tgl_periksa)) }} &ndash; {{ $periksa->jam }} WIB</td>
                    </tr>
                    <tr>
                        <td class="info-label">Pemeriksaan</td>
                        <td class="info-sep">:</td>
                        <td class="info-value" style="color: #003b73;">{{ $jns->nm_perawatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Dokter Pengirim</td>
                        <td class="info-sep">:</td>
                        <td class="info-value">{{ $periksa->dokter_perujuk ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Petugas Analis</td>
                        <td class="info-sep">:</td>
                        <td class="info-value">{{ $periksa->petugas_lab ?? 'Petugas Laboratorium' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- TABEL DETAIL PARAMETER HASIL -->
        <table class="table-hasil">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="40%">Item Pemeriksaan</th>
                    <th width="20%">Hasil</th>
                    <th width="15%">Satuan</th>
                    <th width="20%">Nilai Rujukan Normal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($details as $idx => $d)
                    <tr>
                        <td align="center">{{ $idx + 1 }}</td>
                        <td><strong>{{ $d->item_pemeriksaan }}</strong></td>
                        <td><strong style="color: #003b73;">{{ $d->nilai }}</strong></td>
                        <td>{{ $d->satuan ?? '-' }}</td>
                        <td>{{ $d->nilai_rujukan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" align="center" style="padding: 20px; color: #64748b;">
                            Tidak ada parameter rincian hasil laboratorium.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if (!empty($periksa->catatan))
            <div style="font-size: 0.85rem; margin-bottom: 25px; background: #fffbeb; padding: 10px 14px; border-left: 4px solid #f59e0b; border-radius: 4px;">
                <strong>Catatan Analis:</strong> {{ $periksa->catatan }}
            </div>
        @endif

        <!-- TANDA TANGAN -->
        <div class="ttd-container">
            <div class="ttd-box">
                <p>Dokter / Petugas Pengirim,</p>
                <div class="ttd-space"></div>
                <p class="ttd-name">{{ $periksa->dokter_perujuk ?? 'Welly Prestikasari, S.Kep., Ners., M.Kep.' }}</p>
            </div>
            <div class="ttd-box">
                <p>Bandar Lampung, {{ date('d F Y', strtotime($periksa->tgl_periksa)) }}<br>Petugas Laboratorium,</p>
                <div class="ttd-space"></div>
                <p class="ttd-name">{{ $periksa->petugas_lab ?? 'Petugas Analis' }}</p>
            </div>
        </div>
    </div>

</body>
</html>

