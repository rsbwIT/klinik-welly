<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Etiket Obat - {{ $pasien->nama_lengkap }} ({{ $pasien->no_rkm_medis }})</title>
    <style>
        @page { size: auto; margin: 5mm; }
        body { font-family: Arial, sans-serif; margin: 0; padding: 10px; background: #f1f5f9; font-size: 11px; }
        .etiket-wrapper { display: flex; flex-wrap: wrap; gap: 15px; max-width: 800px; margin: 0 auto; }
        
        /* Box Etiket Obat Standar Rumah Sakit / Apotek */
        .etiket-card {
            width: 75mm;
            min-height: 48mm;
            background: #fff;
            padding: 8px 10px;
            box-sizing: border-box;
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            position: relative;
        }

        /* Etiket Putih (Obat Dalam/Minum) */
        .etiket-putih {
            border: 2px solid #000;
        }

        /* Etiket Biru (Obat Luar/Salep) */
        .etiket-biru {
            border: 2.5px solid #0284c7;
            background: #f0f9ff;
        }

        .etiket-header {
            text-align: center;
            border-bottom: 1.5px solid #000;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .etiket-biru .etiket-header {
            border-bottom: 1.5px solid #0284c7;
        }
        .etiket-header h4 {
            margin: 0;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 800;
        }
        .etiket-header p {
            margin: 1px 0 0 0;
            font-size: 8px;
            color: #444;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            margin-bottom: 4px;
            font-weight: bold;
        }

        .pasien-box {
            margin-bottom: 6px;
            font-size: 9.5px;
            line-height: 1.3;
        }

        .signa-box {
            text-align: center;
            border: 1px dashed #444;
            padding: 6px;
            margin: 6px 0;
            border-radius: 4px;
            background: #fff;
        }
        .signa-title {
            font-size: 12px;
            font-weight: 800;
            color: #000;
        }
        .signa-sub {
            font-size: 9px;
            color: #555;
            margin-top: 2px;
        }

        .obat-title {
            font-size: 10px;
            font-weight: 800;
            color: #0f172a;
        }

        .footer-note {
            text-align: center;
            font-size: 8px;
            font-style: italic;
            margin-top: 4px;
            color: #64748b;
        }

        .badge-tipe {
            display: inline-block;
            font-size: 8px;
            font-weight: bold;
            padding: 1px 4px;
            border-radius: 3px;
            text-transform: uppercase;
        }
        .badge-putih { background: #e2e8f0; color: #1e293b; }
        .badge-biru { background: #0284c7; color: #fff; }

        @media print {
            body { background: transparent; padding: 0; }
            .no-print { display: none; }
            .etiket-wrapper { gap: 10px; }
            .etiket-card { page-break-inside: avoid; box-shadow: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="max-width: 800px; margin: 0 auto 15px auto; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <strong>Preview Label & Etiket Obat Resep: {{ $resep->no_resep ?? '-' }}</strong><br>
            <small style="color: #64748b;">Ukuran label disesuaikan untuk stiker/etiket botol & plastik obat</small>
        </div>
        <button onclick="window.print()" style="padding: 6px 16px; background: #059669; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Cetak Semua Etiket (Printer)
        </button>
    </div>

    <div class="etiket-wrapper">
        @forelse ($detailResep as $item)
            @php
                $isBiru = str_contains(strtolower($item->jenis_etiket), 'biru') || str_contains(strtolower($item->jenis_etiket), 'luar');
            @endphp
            <div class="etiket-card {{ $isBiru ? 'etiket-biru' : 'etiket-putih' }}">
                <div class="etiket-header">
                    <h4>KLINIK RAWAT JALAN & LUKA WELLY</h4>
                    <p>Jl. Raya Kesehatan No. 12, Telp: 0813-6864-0404</p>
                </div>

                <div class="meta-row">
                    <span>No: {{ $resep->no_resep ?? '-' }}</span>
                    <span>Tgl: {{ $resep->tgl_peresepan ?? date('Y-m-d') }}</span>
                </div>

                <div class="pasien-box">
                    <div><strong>Pasien:</strong> {{ $pasien->nama_lengkap }} ({{ $pasien->no_rkm_medis }})</div>
                    <div><strong>Obat:</strong> <span class="obat-title">{{ $item->nama_brng }}</span> &bull; <strong>Jml:</strong> {{ $item->jml }}</div>
                </div>

                <div class="signa-box">
                    <div class="signa-title">{{ $item->aturan_pakai }}</div>
                    <div class="signa-sub">
                        @if ($isBiru)
                            <span class="badge-tipe badge-biru">OBAT LUAR - TIDAK BOLEH DITELAN</span>
                        @else
                            <span class="badge-tipe badge-putih">OBAT DALAM / MINUM</span>
                        @endif
                    </div>
                </div>

                <div class="footer-note">
                    Semoga Lekas Sembuh &bull; Simpan di tempat sejuk & kering
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 20px; width: 100%;">
                Tidak ada item obat dalam resep ini.
            </div>
        @endforelse
    </div>

</body>
</html>
