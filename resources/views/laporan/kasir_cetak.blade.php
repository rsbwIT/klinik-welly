<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Kasir & Pendapatan Pelayanan ({{ $from }} s/d {{ $to }})</title>
    <style>
        @page { size: A4 landscape; margin: 10mm 12mm; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1a1a1a; margin: 0; padding: 15px; }
        .kop { display: flex; align-items: center; border-bottom: 2px solid #000; padding-bottom: 6px; margin-bottom: 12px; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text h2 { margin: 0; font-size: 15px; text-transform: uppercase; }
        .kop-text h4 { margin: 2px 0; font-size: 11px; color: #0284c7; }
        .kop-text p { margin: 2px 0 0 0; font-size: 9px; color: #555; }
        .title { text-align: center; margin-bottom: 12px; }
        .title h3 { margin: 0; font-size: 13px; text-transform: uppercase; text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #333; padding: 5px 7px; font-size: 10px; }
        th { background: #f1f5f9; text-transform: uppercase; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: monospace; }
        .tfoot-total { background: #f8fafc; font-weight: bold; }
        .sign { width: 100%; margin-top: 25px; }
        .sign td { border: none; text-align: center; font-size: 10px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 6px 16px; background: #0284c7; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Cetak PDF / Printer
        </button>
    </div>

    <div class="kop">
        <div class="kop-text">
            <h2>PRAKTIK MANDIRI PERAWAT LUKA & RAWAT JALAN</h2>
            <h4>KLINIK PERAWATAN LUKA MODERN WELLY</h4>
            <p>Jl. Raya Kesehatan No. 12, Bandar Lampung &bull; Telp / WhatsApp: 0813-6864-0404</p>
        </div>
    </div>

    <div class="title">
        <h3>REKAPITULASI PENDAPATAN & PENERIMAAN KASIR RAWAT JALAN</h3>
        <small>Periode Transaksi: {{ date('d-m-Y', strtotime($from)) }} s/d {{ date('d-m-Y', strtotime($to)) }}</small>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30" class="text-center">No</th>
                <th>No. Nota</th>
                <th>Tgl Bayar</th>
                <th>No. Rawat</th>
                <th>No. RM</th>
                <th>Nama Pasien</th>
                <th>Cara Bayar</th>
                <th class="text-right">Registrasi (Rp)</th>
                <th class="text-right">Tindakan (Rp)</th>
                <th class="text-right">Obat / Resep (Rp)</th>
                <th class="text-right">Lab (Rp)</th>
                <th class="text-right">Total Bayar (Rp)</th>
                <th class="text-center">Status</th>
                <th>Petugas Kasir</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($listBilling as $b)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="font-mono text-center">{{ $b->no_nota ?? '-' }}</td>
                    <td class="font-mono text-center">{{ $b->tgl_byr }}</td>
                    <td class="font-mono">{{ $b->no_rawat }}</td>
                    <td class="font-mono text-center">{{ $b->no_rkm_medis }}</td>
                    <td>{{ $b->nm_pasien }}</td>
                    <td>{{ strtoupper($b->jenis_bayar ?? 'UMUM') }}</td>
                    <td class="text-right font-mono">{{ number_format($b->biaya_registrasi, 0, ',', '.') }}</td>
                    <td class="text-right font-mono">{{ number_format($b->biaya_tindakan, 0, ',', '.') }}</td>
                    <td class="text-right font-mono">{{ number_format($b->biaya_obat, 0, ',', '.') }}</td>
                    <td class="text-right font-mono">{{ number_format($b->biaya_lab, 0, ',', '.') }}</td>
                    <td class="text-right font-mono" style="font-weight: bold;">{{ number_format($b->total_bayar, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $b->status_bayar == 'Sudah' ? 'LUNAS' : 'BELUM' }}</td>
                    <td>{{ $b->petugas ?? 'Kasir' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center">Tidak ada data transaksi billing pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="tfoot-total">
                <td colspan="7" class="text-right">TOTAL PENDAPATAN (LUNAS) :</td>
                <td class="text-right font-mono">{{ number_format($totalRegistrasi, 0, ',', '.') }}</td>
                <td class="text-right font-mono">{{ number_format($totalTindakan, 0, ',', '.') }}</td>
                <td class="text-right font-mono">{{ number_format($totalObat, 0, ',', '.') }}</td>
                <td class="text-right font-mono">{{ number_format($totalLab, 0, ',', '.') }}</td>
                <td class="text-right font-mono" style="font-size: 11px; color: #0284c7;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <table class="sign">
        <tr>
            <td width="50%">
                Mengetahui,<br>
                Pimpinan Klinik,<br><br><br><br>
                ( <strong>Welly Prestikasari, S.Kep., Ners., M.Kep.</strong> )
            </td>
            <td width="50%">
                Bandar Lampung, {{ date('d F Y') }}<br>
                Petugas Kasir / Keuangan,<br><br><br><br>
                ( <strong>{{ Auth::user()->name ?? 'Petugas Kasir' }}</strong> )
            </td>
        </tr>
    </table>
</body>
</html>
