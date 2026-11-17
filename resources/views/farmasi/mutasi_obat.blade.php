@extends('layout.layout')

@section('title', 'Mutasi Antar Gudang Obat, Alkes & BHP Medis')
@section('hide_content_header', 'true')

@section('content')
<style>
    /* Styling Persis Form SIMRS Khanza DlgMutasiBarang Sesuai Gambar Lampiran */
    .khanza-window {
        background: #ffffff;
        border: 1px solid #c5cdd7;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
        font-size: 11px;
        color: #1e293b;
        margin-top: -5px;
    }
    .khanza-title {
        font-size: 12px;
        font-family: monospace;
        font-weight: 700;
        color: #1e293b;
        padding: 5px 10px;
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
    }
    .khanza-input {
        height: 23px;
        padding: 1px 6px;
        font-size: 11px;
        border: 1px solid #b8c4d1;
        border-radius: 2px;
        background-color: #ffffff;
        color: #0f172a;
    }
    .khanza-input:focus {
        border-color: #0284c7;
        outline: none;
        box-shadow: 0 0 0 1px #0284c7;
    }
    .khanza-input-disabled {
        background-color: #f8fafc;
        border-color: #cbd5e1;
        color: #334155;
    }
    .khanza-lbl {
        font-size: 11px;
        color: #334155;
        white-space: nowrap;
        margin: 0;
        font-weight: 500;
    }
    .btn-edit-icon {
        width: 23px;
        height: 23px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        border-radius: 2px;
        color: #64748b;
        cursor: pointer;
        font-size: 11px;
    }
    .table-khanza-grid {
        width: 100%;
        border-collapse: collapse;
        font-size: 10.5px;
        background: #ffffff;
    }
    .table-khanza-grid thead th {
        background: #ffffff;
        color: #334155;
        border: 1px solid #d8e0e8;
        padding: 3px 5px;
        font-weight: 600;
        text-align: left;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .table-khanza-grid tbody td {
        border: 1px solid #e9edf2;
        padding: 2.5px 5px;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table-khanza-grid tbody tr:hover {
        background-color: #f1f8fe !important;
    }
    .input-jml-khanza {
        width: 50px;
        height: 19px;
        font-size: 11px;
        text-align: center;
        font-weight: bold;
        border: 1px solid #94a3b8;
        border-radius: 2px;
        background: #ffffff;
        padding: 0;
    }
    .input-jml-khanza.filled {
        background: #fef08a !important;
        border-color: #ca8a04 !important;
        color: #854d0e !important;
    }
    .khanza-bottom-bar {
        background: #f8fafc;
        border-top: 1px solid #c5cdd7;
        padding: 5px 10px;
    }
    .btn-khanza-action {
        height: 25px;
        padding: 1px 12px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 3px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        cursor: pointer;
    }
    .btn-khanza-action:hover {
        background: #f1f5f9;
    }
</style>

<div class="container-fluid px-2">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show py-1 px-3 mb-1 shadow-sm d-flex align-items-center justify-content-between" style="font-size: 11.5px;" role="alert">
            <div>
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
            <button type="button" class="close p-1" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-1 px-3 mb-1 shadow-sm d-flex align-items-center justify-content-between" style="font-size: 11.5px;" role="alert">
            <div>
                <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
            </div>
            <button type="button" class="close p-1" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <form action="{{ route('farmasi.mutasi.store') }}" method="POST" id="formKhanzaMutasi">
        @csrf

        <div class="khanza-window">
            <!-- Header bar judul persis gambar -->
            <div class="khanza-title d-flex justify-content-between align-items-center">
                <span>:: [ Mutasi Antar Gudang Obat, Alkes & BHP Medis ] ::</span>
                <span class="badge badge-light border text-muted" style="font-size: 10px;">Versi Standar SIMRS Khanza (DlgMutasiBarang)</span>
            </div>

            <!-- Area Parameter Input Header (Persis 2 Baris Khanza di Gambar) -->
            <div class="p-2 border-bottom" style="background: #fafafa;">
                <div class="row align-items-center mb-1">
                    <!-- Dari (Depo Asal) -->
                    <div class="col-auto d-flex align-items-center" style="width: 380px;">
                        <span class="khanza-lbl mr-1" style="width: 45px;">Dari :</span>
                        <input type="text" id="input_dari_kd" name="kd_bangsaldari" value="{{ $depoDariObj->kd_bangsal ?? 'GUDANG' }}" class="khanza-input khanza-input-disabled font-monospace font-weight-bold mr-1" style="width: 75px;" readonly>
                        <input type="text" id="input_dari_nm" value="{{ $depoDariObj->nm_bangsal ?? 'Gudang Farmasi Utama' }}" class="khanza-input khanza-input-disabled font-weight-bold flex-fill mr-1" readonly>
                        <button type="button" class="btn-edit-icon" title="Pilih Depo Asal" onclick="bukaModalPilihDepo('dari')"><i class="fas fa-edit text-primary"></i></button>
                    </div>

                    <!-- Tanggal -->
                    <div class="col-auto d-flex align-items-center" style="width: 280px;">
                        <span class="khanza-lbl mr-1" style="width: 65px;">Tanggal :</span>
                        <input type="datetime-local" name="tanggal" value="{{ date('Y-m-d\TH:i:s') }}" class="khanza-input font-monospace flex-fill">
                    </div>
                </div>

                <div class="row align-items-center">
                    <!-- Ke (Depo Tujuan) -->
                    <div class="col-auto d-flex align-items-center" style="width: 380px;">
                        <span class="khanza-lbl mr-1" style="width: 45px;">Ke :</span>
                        <input type="text" id="input_ke_kd" name="kd_bangsalke" value="{{ $depoKeObj->kd_bangsal ?? 'DepoRJ' }}" class="khanza-input khanza-input-disabled font-monospace font-weight-bold mr-1" style="width: 75px;" readonly>
                        <input type="text" id="input_ke_nm" value="{{ $depoKeObj->nm_bangsal ?? 'Depo Rawat Jalan' }}" class="khanza-input khanza-input-disabled font-weight-bold flex-fill mr-1" readonly>
                        <button type="button" class="btn-edit-icon" title="Pilih Depo Tujuan" onclick="bukaModalPilihDepo('ke')"><i class="fas fa-edit text-success"></i></button>
                    </div>

                    <!-- Keterangan -->
                    <div class="col-auto d-flex align-items-center flex-fill" style="max-width: 500px;">
                        <span class="khanza-lbl mr-1" style="width: 75px;">Keterangan :</span>
                        <input type="text" name="keterangan" value="Mutasi rutin dari {{ $depoDariObj->kd_bangsal }} ke {{ $depoKeObj->kd_bangsal }}" class="khanza-input flex-fill" placeholder="Keterangan mutasi barang...">
                    </div>
                </div>
            </div>

            <!-- Tabel Grid Persis Kolom Gambar Khanza -->
            <div style="height: calc(100vh - 235px); min-height: 440px; overflow-y: auto; background: #ffffff;">
                <table class="table-khanza-grid" id="tabelGridMutasi">
                    <thead>
                        <tr>
                            <th style="width: 55px; text-align: center;">Jml</th>
                            <th style="width: 85px; text-align: right;">Harga</th>
                            <th style="width: 95px; text-align: right;">Total</th>
                            <th style="width: 100px;">Kode Barang</th>
                            <th>Nama Barang</th>
                            <th style="width: 70px; text-align: center;">Satuan</th>
                            <th style="width: 75px; text-align: center; background: #fee2e2;">Stok Asal</th>
                            <th style="width: 75px; text-align: center; background: #dcfce7;">Stok Tujuan</th>
                            <th style="width: 90px;">No.Batch</th>
                            <th style="width: 90px;">No.Faktur</th>
                            <th style="width: 85px;">Kadaluarsa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($obatList as $idx => $ob)
                            <tr class="baris-obat-khanza" data-nama="{{ strtolower($ob->nama_brng) }}" data-kode="{{ strtolower($ob->kode_brng) }}">
                                <!-- Jml Input -->
                                <td style="text-align: center; padding: 1px 3px;">
                                    <input type="number" 
                                           step="any"
                                           min="0" 
                                           max="{{ $ob->stok_asal }}"
                                           name="items[{{ $idx }}][jml]" 
                                           class="input-jml-khanza input-jml-baris" 
                                           data-index="{{ $idx }}"
                                           data-harga="{{ $ob->h_beli }}"
                                           data-stokasal="{{ $ob->stok_asal }}"
                                           oninput="hitungTotalMutasiBaris({{ $idx }})">
                                    <input type="hidden" name="items[{{ $idx }}][kode_brng]" value="{{ $ob->kode_brng }}">
                                    <input type="hidden" name="items[{{ $idx }}][harga]" value="{{ $ob->h_beli }}">
                                    <input type="hidden" name="items[{{ $idx }}][no_batch]" value="{{ $ob->no_batch }}">
                                    <input type="hidden" name="items[{{ $idx }}][no_faktur]" value="{{ $ob->no_faktur }}">
                                </td>
                                <!-- Harga -->
                                <td style="text-align: right; color: #475569;" class="font-monospace">
                                    {{ number_format($ob->h_beli, 0, ',', '.') }}
                                </td>
                                <!-- Total Baris -->
                                <td style="text-align: right; font-weight: bold; color: #0284c7;" class="font-monospace subtotal-baris" id="total_baris_{{ $idx }}">
                                    0
                                </td>
                                <!-- Kode Barang -->
                                <td class="font-monospace font-weight-bold text-primary">{{ $ob->kode_brng }}</td>
                                <!-- Nama Barang -->
                                <td style="white-space: normal; line-height: 1.2;">
                                    <strong>{{ $ob->nama_brng }}</strong>
                                </td>
                                <!-- Satuan -->
                                <td style="text-align: center; color: #64748b;">{{ $ob->kode_sat ?: 'Pcs' }}</td>
                                <!-- Stok Asal -->
                                <td style="text-align: center; font-weight: bold; font-family: monospace; color: {{ $ob->stok_asal > 0 ? '#b91c1c' : '#94a3b8' }};">
                                    {{ $ob->stok_asal }}
                                </td>
                                <!-- Stok Tujuan -->
                                <td style="text-align: center; font-weight: bold; font-family: monospace; color: #15803d;">
                                    {{ $ob->stok_tujuan }}
                                </td>
                                <!-- No. Batch -->
                                <td style="color: #64748b;">{{ $ob->no_batch }}</td>
                                <!-- No. Faktur -->
                                <td style="color: #64748b;">{{ $ob->no_faktur }}</td>
                                <!-- Kadaluarsa -->
                                <td style="color: #64748b;">-</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Footer Bar Persis Gambar Khanza -->
            <div class="khanza-bottom-bar d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <button type="submit" class="btn-khanza-action text-primary font-weight-bold" id="btn_simpan_mutasi">
                        <i class="fas fa-save text-primary"></i> Simpan
                    </button>
                    <div class="d-flex align-items-center ml-3">
                        <span class="khanza-lbl mr-1">Key Word :</span>
                        <input type="text" id="quick_filter_keyword" class="khanza-input" style="width: 220px;" placeholder="Ketik nama atau kode barang...">
                        <button type="button" class="btn-edit-icon ml-1" id="btn_clear_filter" title="Clear Filter"><i class="fas fa-check text-success"></i></button>
                        <button type="button" class="btn-edit-icon ml-1" title="Cari Data"><i class="fas fa-search text-muted"></i></button>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div class="mr-3 small text-muted">
                        Total Nilai Mutasi: <b class="text-primary font-monospace font-weight-bold" style="font-size: 13px;" id="display_total_mutasi">Rp 0</b>
                    </div>
                    <a href="{{ route('master.obat.index') }}" class="btn-khanza-action text-danger">
                        <i class="fas fa-times text-danger"></i> Keluar
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- MODAL PILIH LOKASI DEPO / BANGSAL -->
<div class="modal fade" id="modalPilihDepoMutasi" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 100000;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 8px;">
            <div class="modal-header bg-primary text-white py-2 px-3 align-items-center">
                <h6 class="modal-title font-weight-bold" id="modalDepoTitle" style="font-size: 13px;">
                    <i class="fas fa-warehouse mr-2"></i> Pilih Lokasi Depo
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-3">
                <div class="list-group">
                    @foreach($depoList as $dp)
                        <a href="javascript:void(0)" 
                           onclick="pilihDepoAksi('{{ $dp->kd_bangsal }}', '{{ $dp->nm_bangsal }}')" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3">
                            <div>
                                <span class="badge badge-light border font-monospace mr-2 text-dark">{{ $dp->kd_bangsal }}</span>
                                <strong>{{ $dp->nm_bangsal }}</strong>
                            </div>
                            <span class="badge badge-secondary">Pilih</span>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light">
                <button type="button" class="btn btn-sm btn-secondary font-weight-bold" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    let modePilihDepo = 'dari';

    function bukaModalPilihDepo(mode) {
        modePilihDepo = mode;
        if (mode === 'dari') {
            $('#modalDepoTitle').html('<i class="fas fa-sign-out-alt mr-2 text-warning"></i> Pilih Depo Sumber (Dari)');
        } else {
            $('#modalDepoTitle').html('<i class="fas fa-sign-in-alt mr-2 text-success"></i> Pilih Depo Tujuan (Ke)');
        }
        $('#modalPilihDepoMutasi').appendTo('body').modal('show');
    }

    function pilihDepoAksi(kd, nm) {
        $('#modalPilihDepoMutasi').modal('hide');
        var dari = document.getElementById('input_dari_kd').value;
        var ke = document.getElementById('input_ke_kd').value;

        if (modePilihDepo === 'dari') {
            dari = kd;
        } else {
            ke = kd;
        }

        if (dari === ke) {
            alert('Depo asal dan depo tujuan tidak boleh sama!');
            return;
        }

        window.location.href = "{{ route('farmasi.mutasi.index') }}?dari=" + encodeURIComponent(dari) + "&ke=" + encodeURIComponent(ke);
    }

    function hitungTotalMutasiBaris(idx) {
        const inp = document.querySelector(`input[name="items[${idx}][jml]"]`);
        const jml = parseFloat(inp.value) || 0;
        const harga = parseFloat(inp.getAttribute('data-harga')) || 0;
        const stokAsal = parseFloat(inp.getAttribute('data-stokasal')) || 0;

        if (jml > stokAsal) {
            alert(`Jumlah mutasi (${jml}) melebihi stok yang tersedia di depo asal (${stokAsal})!`);
            inp.value = stokAsal;
        }

        if (jml > 0) {
            inp.classList.add('filled');
        } else {
            inp.classList.remove('filled');
        }

        const subtotal = (parseFloat(inp.value) || 0) * harga;
        document.getElementById(`total_baris_${idx}`).innerText = subtotal > 0 ? subtotal.toLocaleString('id-ID') : '0';

        hitungAkumulasiSeluruhMutasi();
    }

    function hitungAkumulasiSeluruhMutasi() {
        let total = 0;
        document.querySelectorAll('.input-jml-baris').forEach(inp => {
            const jml = parseFloat(inp.value) || 0;
            const harga = parseFloat(inp.getAttribute('data-harga')) || 0;
            total += (jml * harga);
        });
        document.getElementById('display_total_mutasi').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    // Quick filter keyword di baris bawah
    document.getElementById('quick_filter_keyword').addEventListener('input', function() {
        const val = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.baris-obat-khanza');
        rows.forEach(r => {
            const nama = (r.getAttribute('data-nama') || '');
            const kode = (r.getAttribute('data-kode') || '');
            if (nama.includes(val) || kode.includes(val)) {
                r.style.display = '';
            } else {
                r.style.display = 'none';
            }
        });
    });

    document.getElementById('btn_clear_filter').addEventListener('click', function() {
        document.getElementById('quick_filter_keyword').value = '';
        document.querySelectorAll('.baris-obat-khanza').forEach(r => r.style.display = '');
    });
</script>
@endsection
