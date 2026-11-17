<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Pasien - {{ $pasien->nm_pasien }} ({{ $pasien->no_rkm_medis }})</title>
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
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .no-print {
            margin-bottom: 20px;
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
            font-size: 0.9rem;
            box-shadow: 0 4px 12px rgba(0, 119, 192, 0.3);
        }

        .btn-close-window {
            background: #64748b;
            color: #ffffff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
        }

        /* UKURAN KARTU PASIEN STANDAR (ID CARD CR80 / 85.6mm x 54mm) */
        .card-container {
            width: 86mm;
            height: 54mm;
            background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
            border-radius: 10px;
            border: 1.5px solid #0077c0;
            box-shadow: 0 15px 35px rgba(3, 43, 77, 0.14);
            padding: 10px 13px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .card-container::before {
            content: '';
            position: absolute;
            top: -25px;
            right: -25px;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(16, 185, 129, 0.09);
        }

        /* HEADER KARTU */
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #0077c0;
            padding-bottom: 5px;
        }

        .clinic-brand {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .clinic-logo {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #0077c0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .clinic-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .clinic-name {
            font-size: 0.68rem;
            font-weight: 800;
            color: #003b73;
            line-height: 1.1;
            letter-spacing: 0.2px;
        }

        .clinic-sub {
            font-size: 0.52rem;
            font-weight: 700;
            color: #059669;
            line-height: 1.1;
        }

        .card-tag {
            font-size: 0.55rem;
            font-weight: 800;
            color: #003b73;
            background: rgba(0, 119, 192, 0.12);
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid rgba(0, 119, 192, 0.2);
        }

        /* BODY KARTU */
        .card-body {
            margin-top: 3px;
        }

        .patient-name {
            font-size: 0.92rem;
            font-weight: 800;
            color: #032b4d;
            letter-spacing: 0.2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 2px;
        }

        .patient-info-table {
            width: 100%;
            font-size: 0.55rem;
            color: #334155;
            line-height: 1.35;
        }

        .patient-info-table td {
            vertical-align: top;
            padding: 1px 0;
        }

        /* FOOTER KARTU & BARCODE */
        .card-footer {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            padding-top: 3px;
            border-top: 1px dashed #94a3b8;
        }

        .barcode-box {
            display: flex;
            flex-direction: column;
        }

        .barcode-font {
            font-family: 'Libre Barcode 39 Text', cursive;
            font-size: 1.75rem;
            line-height: 1;
            color: #032b4d;
        }

        .rm-number-pill {
            font-family: monospace;
            font-size: 0.82rem;
            font-weight: 800;
            color: #0077c0;
            letter-spacing: 1px;
        }

        .card-notice {
            font-size: 0.45rem;
            color: #64748b;
            text-align: right;
            line-height: 1.25;
            max-width: 120px;
        }

        @media print {
            body {
                background: none;
                padding: 0;
                margin: 0;
                min-height: auto;
            }
            .no-print {
                display: none !important;
            }
            .card-container {
                box-shadow: none;
                border: 1px solid #0077c0;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i> Cetak Kartu Pasien
        </button>
        <button onclick="window.close()" class="btn-close-window">
            Tutup Jendela
        </button>
    </div>

    <div class="card-container">
        <!-- HEADER KARTU BEROBAT -->
        <div class="card-header">
            <div class="clinic-brand">
                <div class="clinic-logo">
                    <img src="{{ asset('img/klinik.jpeg') }}" alt="Logo Klinik">
                </div>
                <div>
                    <div class="clinic-name">PRAKTIK MANDIRI PERAWAT LUKA</div>
                    <div class="clinic-sub">Welly Prestikasari, S.Kep., Ners., M.Kep.</div>
                </div>
            </div>
            <div class="card-tag">KARTU PASIEN</div>
        </div>

        <!-- BODY IDENTITAS PASIEN -->
        <div class="card-body">
            <div class="patient-name">{{ $pasien->nm_pasien }}</div>
            <table class="patient-info-table">
                <tr>
                    <td width="55px"><strong>TTL</strong></td>
                    <td width="8px">:</td>
                    <td>{{ $pasien->tmp_lahir ? $pasien->tmp_lahir.', ' : '' }}{{ $pasien->tgl_lahir ? date('d/m/Y', strtotime($pasien->tgl_lahir)) : '-' }}</td>
                </tr>
                <tr>
                    <td><strong>JK / Gol</strong></td>
                    <td>:</td>
                    <td>{{ $pasien->jk == 'L' ? 'Laki-laki' : 'Perempuan' }} (Gol: {{ $pasien->gol_darah ?? '-' }})</td>
                </tr>
                <tr>
                    <td><strong>Alamat</strong></td>
                    <td>:</td>
                    <td>{{ \Illuminate\Support\Str::limit($pasien->alamat ?? ($pasien->kabupaten ?? '-'), 42) }}</td>
                </tr>
            </table>
        </div>

        <!-- FOOTER & BARCODE NO. RM -->
        <div class="card-footer">
            <div class="barcode-box">
                <span class="barcode-font">*{{ $pasien->no_rkm_medis }}*</span>
                <span class="rm-number-pill">RM: {{ $pasien->no_rkm_medis }}</span>
            </div>
            <div class="card-notice">
                <em>"Merawat Luka, Mengembalikan Harapan."</em><br>
                Harap membawa kartu ini setiap kali berobat.
            </div>
        </div>
    </div>

</body>
</html>
