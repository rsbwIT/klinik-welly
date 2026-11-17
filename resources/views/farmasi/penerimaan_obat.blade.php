@extends('layout.layout')

@section('title', 'Transaksi Penerimaan Obat, Alkes & BHP Medis')
@section('hide_content_header', 'true')

@section('content')
<style>
    /* Styling Persis Form SIMRS Khanza DlgPembelian Sesuai Gambar Lampiran */
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
        width: 48px;
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
    .input-grid-compact {
        height: 19px;
        font-size: 10.5px;
        border: 1px solid #cbd5e1;
        border-radius: 2px;
        padding: 0 4px;
        background: #ffffff;
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

    <form action="{{ route('farmasi.penerimaan.store') }}" method="POST" id="formKhanzaPenerimaan">
        @csrf

        <div class="khanza-window">
            <!-- Header bar judul persis gambar lampiran -->
            <div class="khanza-title d-flex justify-content-between align-items-center">
                <span>:: [ Transaksi Penerimaan Obat, Alkes & BHP Medis ] ::</span>
                <span class="badge badge-light border text-muted" style="font-size: 10px;">Versi Standar SIMRS Khanza (DlgPembelian)</span>
            </div>

            <!-- Area Parameter Input Header (Persis 3 Baris Khanza di Gambar) -->
            <div class="p-2 border-bottom" style="background: #fafafa;">
                <!-- Baris 1: No.Faktur, Supplier -->
                <div class="row align-items-center mb-1">
                    <div class="col-auto d-flex align-items-center" style="width: 250px;">
                        <span class="khanza-lbl mr-1" style="width: 70px;">No. Faktur :</span>
                        <input type="text" name="no_faktur" value="{{ $noFaktur }}" class="khanza-input font-monospace font-weight-bold flex-fill" required>
                    </div>

                    <div class="col d-flex align-items-center">
                        <span class="khanza-lbl mr-1" style="width: 55px;">Supplier :</span>
                        <input type="text" id="input_supplier_kd" name="kode_suplier" value="{{ $defaultSuplier->kode_industri ?? 'PBF01' }}" class="khanza-input khanza-input-disabled font-monospace font-weight-bold mr-1" style="width: 75px;" readonly>
                        <input type="text" id="input_supplier_nm" name="nama_suplier" value="{{ $defaultSuplier->nama_industri ?? 'PT. Kimia Farma Trading' }}" class="khanza-input khanza-input-disabled font-weight-bold flex-fill mr-1" readonly>
                        <button type="button" class="btn-edit-icon" title="Pilih Supplier / PBF" onclick="bukaModalPilihSupplier()"><i class="fas fa-edit text-primary"></i></button>
                    </div>
                </div>

                <!-- Baris 2: Tgl.Datang, Tgl.Faktur, Petugas -->
                <div class="row align-items-center mb-1">
                    <div class="col-auto d-flex align-items-center" style="width: 175px;">
                        <span class="khanza-lbl mr-1" style="width: 70px;">Tgl.Datang :</span>
                        <input type="date" name="tgl_datang" value="{{ date('Y-m-d') }}" class="khanza-input flex-fill">
                    </div>

                    <div class="col-auto d-flex align-items-center" style="width: 175px;">
                        <span class="khanza-lbl mr-1" style="width: 65px;">Tgl.Faktur :</span>
                        <input type="date" name="tgl_faktur" value="{{ date('Y-m-d') }}" class="khanza-input flex-fill">
                    </div>

                    <div class="col d-flex align-items-center">
                        <span class="khanza-lbl mr-1" style="width: 55px;">Petugas :</span>
                        <input type="text" value="01091999" class="khanza-input khanza-input-disabled font-monospace mr-1" style="width: 75px;" readonly>
                        <input type="text" name="petugas" value="{{ Auth::user()->name ?? 'Petugas Farmasi' }}" class="khanza-input khanza-input-disabled font-weight-bold flex-fill" readonly>
                    </div>
                </div>

                <!-- Baris 3: SP/Order, Jth.Tempo, Lokasi -->
                <div class="row align-items-center">
                    <div class="col-auto d-flex align-items-center" style="width: 175px;">
                        <span class="khanza-lbl mr-1" style="width: 70px;">SP / Order :</span>
                        <input type="text" name="no_order" value="ORD{{ date('Ymd') }}" class="khanza-input font-monospace flex-fill">
                    </div>

                    <div class="col-auto d-flex align-items-center" style="width: 175px;">
                        <span class="khanza-lbl mr-1" style="width: 65px;">Jth.Tempo :</span>
                        <input type="date" name="jth_tempo" value="{{ date('Y-m-d', strtotime('+30 days')) }}" class="khanza-input flex-fill">
                    </div>

                    <div class="col d-flex align-items-center">
                        <span class="khanza-lbl mr-1" style="width: 55px;">Lokasi :</span>
                        <input type="text" id="input_lokasi_kd" name="kd_bangsal" value="{{ $depoAktif->kd_bangsal ?? 'GUDANG' }}" class="khanza-input khanza-input-disabled font-monospace font-weight-bold mr-1" style="width: 75px;" readonly>
                        <input type="text" id="input_lokasi_nm" value="{{ $depoAktif->nm_bangsal ?? 'Gudang Farmasi Utama' }}" class="khanza-input khanza-input-disabled font-weight-bold flex-fill mr-1" readonly>
                        <button type="button" class="btn-edit-icon" title="Pilih Lokasi Depo Penerimaan" onclick="bukaModalPilihLokasi()"><i class="fas fa-edit text-success"></i></button>
                    </div>
                </div>
            </div>

            <!-- Tabel Grid Persis Kolom Gambar Penerimaan Khanza -->
            <div style="height: calc(100vh - 275px); min-height: 400px; overflow-y: auto; background: #ffffff;">
                <table class="table-khanza-grid" id="tabelGridPenerimaan">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">Jml</th>
                            <th style="width: 75px;">Satuan Beli</th>
                            <th style="width: 95px;">Kode Barang</th>
                            <th>Nama Barang</th>
                            <th style="width: 60px; text-align: center;">Satuan</th>
                            <th style="width: 30px; text-align: center;">G</th>
                            <th style="width: 85px;">Kadaluarsa</th>
                            <th style="width: 85px; text-align: right;">Harga(Rp)</th>
                            <th style="width: 90px; text-align: right;">Subtotal(Rp)</th>
                            <th style="width: 55px; text-align: right;">Disk(%)</th>
                            <th style="width: 75px; text-align: right;">Diskon(Rp)</th>
                            <th style="width: 95px; text-align: right;">Total</th>
                            <th style="width: 85px;">No.Batch</th>
                            <th style="width: 45px; text-align: center;">Isi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($obatList as $idx => $ob)
                            <tr class="baris-obat-penerimaan" data-nama="{{ strtolower($ob->nama_brng) }}" data-kode="{{ strtolower($ob->kode_brng) }}">
                                <!-- Jml -->
                                <td style="text-align: center; padding: 1px 3px;">
                                    <input type="number" 
                                           step="any" 
                                           min="0" 
                                           name="items[{{ $idx }}][jml]" 
                                           class="input-jml-khanza input-jml-terima" 
                                           data-index="{{ $idx }}"
                                           data-harga="{{ $ob->h_beli }}"
                                           oninput="hitungBarisPenerimaan({{ $idx }})">
                                    <input type="hidden" name="items[{{ $idx }}][kode_brng]" value="{{ $ob->kode_brng }}">
                                    <input type="hidden" name="items[{{ $idx }}][h_beli]" value="{{ $ob->h_beli }}">
                                </td>
                                <!-- Satuan Beli -->
                                <td style="color: #64748b;">{{ $ob->kode_sat ?: 'Pcs' }}</td>
                                <!-- Kode Barang -->
                                <td class="font-monospace font-weight-bold text-primary">{{ $ob->kode_brng }}</td>
                                <!-- Nama Barang -->
                                <td style="white-space: normal; line-height: 1.2;">
                                    <strong>{{ $ob->nama_brng }}</strong>
                                </td>
                                <!-- Satuan -->
                                <td style="text-align: center; color: #64748b;">{{ $ob->kode_sat ?: 'Pcs' }}</td>
                                <!-- G (Gudang Check) -->
                                <td style="text-align: center;">
                                    <input type="checkbox" checked disabled>
                                </td>
                                <!-- Kadaluarsa -->
                                <td>
                                    <input type="date" name="items[{{ $idx }}][tgl_kadaluarsa]" class="input-grid-compact" style="width: 100px;">
                                </td>
                                <!-- Harga (Rp) -->
                                <td style="text-align: right;" class="font-monospace text-muted">
                                    {{ number_format($ob->h_beli, 0, ',', '.') }}
                                </td>
                                <!-- Subtotal (Rp) -->
                                <td style="text-align: right; color: #334155;" class="font-monospace" id="subtotal_terima_{{ $idx }}">
                                    0
                                </td>
                                <!-- Disk (%) -->
                                <td style="text-align: right;">
                                    <input type="number" step="any" min="0" max="100" name="items[{{ $idx }}][diskon_persen]" 
                                           class="input-grid-compact text-right font-monospace" style="width: 45px;" value="0" 
                                           data-index="{{ $idx }}" oninput="hitungBarisPenerimaan({{ $idx }})">
                                </td>
                                <!-- Diskon (Rp) -->
                                <td style="text-align: right; color: #dc2626;" class="font-monospace" id="diskon_rp_terima_{{ $idx }}">
                                    0
                                </td>
                                <!-- Total -->
                                <td style="text-align: right; font-weight: bold; color: #0284c7;" class="font-monospace" id="total_terima_{{ $idx }}">
                                    0
                                </td>
                                <!-- No. Batch -->
                                <td>
                                    <input type="text" name="items[{{ $idx }}][no_batch]" class="input-grid-compact" style="width: 80px;" placeholder="-">
                                </td>
                                <!-- Isi -->
                                <td style="text-align: center; color: #64748b;">1</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Footer Parameter Biaya & Tombol Persis Gambar Khanza -->
            <div class="p-2 border-top" style="background: #fafafa;">
                <div class="row align-items-center text-nowrap" style="font-size: 11px;">
                    <!-- Total 1 -->
                    <div class="col-auto d-flex align-items-center">
                        <span class="khanza-lbl mr-1">Total 1 :</span>
                        <span class="font-weight-bold font-monospace text-dark" id="disp_total1">0</span>
                    </div>

                    <!-- Potongan -->
                    <div class="col-auto d-flex align-items-center ml-3">
                        <span class="khanza-lbl mr-1">Potongan :</span>
                        <input type="number" step="any" min="0" name="potongan" id="input_potongan" class="khanza-input text-right font-monospace" style="width: 70px;" value="0" oninput="hitungGrandTotalPenerimaan()">
                    </div>

                    <!-- Total 2 -->
                    <div class="col-auto d-flex align-items-center ml-3">
                        <span class="khanza-lbl mr-1">Total 2 :</span>
                        <span class="font-weight-bold font-monospace text-dark" id="disp_total2">0</span>
                    </div>

                    <!-- PPN -->
                    <div class="col-auto d-flex align-items-center ml-3">
                        <span class="khanza-lbl mr-1">PPN :</span>
                        <input type="number" step="any" min="0" max="100" name="ppn_persen" id="input_ppn_persen" class="khanza-input text-right font-monospace mr-1" style="width: 45px;" value="11" oninput="hitungGrandTotalPenerimaan()">
                        <span class="mr-1">%</span>
                        <input type="number" step="any" min="0" name="ppn_rp" id="input_ppn_rp" class="khanza-input text-right font-monospace" style="width: 85px;" value="0" oninput="hitungGrandTotalPenerimaan(true)">
                    </div>

                    <!-- Meterai -->
                    <div class="col-auto d-flex align-items-center ml-3">
                        <span class="khanza-lbl mr-1">Meterai :</span>
                        <input type="number" step="any" min="0" name="meterai" id="input_meterai" class="khanza-input text-right font-monospace" style="width: 75px;" value="0" oninput="hitungGrandTotalPenerimaan()">
                    </div>

                    <!-- Jumlah Tagihan -->
                    <div class="col-auto d-flex align-items-center ml-3">
                        <span class="khanza-lbl mr-1 font-weight-bold text-dark">Jumlah Tagihan :</span>
                        <span class="font-weight-bold font-monospace text-danger" style="font-size: 14px;" id="disp_jumlah_tagihan">0</span>
                    </div>
                </div>
            </div>

            <!-- Footer Bar Aksi Persis Khanza (Simpan, Key Word, Cari, Keluar) -->
            <div class="khanza-bottom-bar d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <button type="submit" class="btn-khanza-action text-primary font-weight-bold" id="btn_simpan_terima">
                        <i class="fas fa-save text-primary"></i> Simpan
                    </button>
                    <div class="d-flex align-items-center ml-3">
                        <span class="khanza-lbl mr-1">Key Word :</span>
                        <input type="text" id="quick_filter_keyword_terima" class="khanza-input" style="width: 220px;" placeholder="Ketik nama atau kode barang...">
                        <button type="button" class="btn-edit-icon ml-1" id="btn_clear_filter_terima" title="Clear Filter"><i class="fas fa-check text-success"></i></button>
                        <button type="button" class="btn-edit-icon ml-1" title="Cari Data"><i class="fas fa-search text-muted"></i></button>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('master.obat.index') }}" class="btn-khanza-action text-danger">
                        <i class="fas fa-times text-danger"></i> Keluar
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- MODAL PILIH SUPPLIER -->
<div class="modal fade" id="modalPilihSupplier" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 100000;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 8px;">
            <div class="modal-header bg-primary text-white py-2 px-3 align-items-center">
                <h6 class="modal-title font-weight-bold" style="font-size: 13px;">
                    <i class="fas fa-truck mr-2"></i> Pilih Supplier / Industri Farmasi
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-3">
                <div class="list-group">
                    @foreach($suplierList as $sup)
                        <a href="javascript:void(0)" 
                           onclick="setSupplierAksi('{{ $sup->kode_industri }}', '{{ $sup->nama_industri }}')" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3">
                            <div>
                                <span class="badge badge-light border font-monospace mr-2 text-dark">{{ $sup->kode_industri }}</span>
                                <strong>{{ $sup->nama_industri }}</strong>
                                <div class="text-muted small">{{ $sup->alamat ?? '-' }} ({{ $sup->kota ?? '-' }})</div>
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

<!-- MODAL PILIH LOKASI DEPO PENERIMAAN -->
<div class="modal fade" id="modalPilihLokasiPenerimaan" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 100000;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 8px;">
            <div class="modal-header bg-success text-white py-2 px-3 align-items-center">
                <h6 class="modal-title font-weight-bold" style="font-size: 13px;">
                    <i class="fas fa-warehouse mr-2"></i> Pilih Lokasi Gudang / Depo Penerimaan
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-3">
                <div class="list-group">
                    @foreach($depoList as $dp)
                        <a href="javascript:void(0)" 
                           onclick="setLokasiAksi('{{ $dp->kd_bangsal }}', '{{ $dp->nm_bangsal }}')" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3 {{ ($selectedDepo ?? 'GUDANG') == $dp->kd_bangsal ? 'active font-weight-bold' : '' }}">
                            <div>
                                <span class="badge badge-light border font-monospace mr-2 text-dark">{{ $dp->kd_bangsal }}</span>
                                <strong>{{ $dp->nm_bangsal }}</strong>
                            </div>
                            @if(($selectedDepo ?? 'GUDANG') == $dp->kd_bangsal)
                                <span class="badge badge-light text-success font-weight-bold"><i class="fas fa-check"></i> Aktif</span>
                            @else
                                <span class="badge badge-secondary">Pilih</span>
                            @endif
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
    function bukaModalPilihSupplier() {
        $('#modalPilihSupplier').appendTo('body').modal('show');
    }

    function setSupplierAksi(kd, nm) {
        document.getElementById('input_supplier_kd').value = kd;
        document.getElementById('input_supplier_nm').value = nm;
        $('#modalPilihSupplier').modal('hide');
    }

    function bukaModalPilihLokasi() {
        $('#modalPilihLokasiPenerimaan').appendTo('body').modal('show');
    }

    function setLokasiAksi(kd, nm) {
        document.getElementById('input_lokasi_kd').value = kd;
        document.getElementById('input_lokasi_nm').value = nm;
        $('#modalPilihLokasiPenerimaan').modal('hide');

        window.location.href = "{{ route('farmasi.penerimaan.index') }}?kd_bangsal=" + encodeURIComponent(kd);
    }

    function hitungBarisPenerimaan(idx) {
        const inpJml = document.querySelector(`input[name="items[${idx}][jml]"]`);
        const jml = parseFloat(inpJml.value) || 0;
        const harga = parseFloat(inpJml.getAttribute('data-harga')) || 0;
        const subtotal = jml * harga;

        if (jml > 0) {
            inpJml.classList.add('filled');
        } else {
            inpJml.classList.remove('filled');
        }

        const diskPersen = parseFloat(document.querySelector(`input[name="items[${idx}][diskon_persen]"]`).value) || 0;
        const diskRp = (subtotal * diskPersen) / 100;
        const total = Math.max(0, subtotal - diskRp);

        document.getElementById(`subtotal_terima_${idx}`).innerText = subtotal > 0 ? subtotal.toLocaleString('id-ID') : '0';
        document.getElementById(`diskon_rp_terima_${idx}`).innerText = diskRp > 0 ? diskRp.toLocaleString('id-ID') : '0';
        document.getElementById(`total_terima_${idx}`).innerText = total > 0 ? total.toLocaleString('id-ID') : '0';

        hitungGrandTotalPenerimaan();
    }

    function hitungGrandTotalPenerimaan(isManualPpn = false) {
        let total1 = 0;
        document.querySelectorAll('.input-jml-terima').forEach((inp, idx) => {
            const jml = parseFloat(inp.value) || 0;
            const harga = parseFloat(inp.getAttribute('data-harga')) || 0;
            const subtotal = jml * harga;
            const diskPersen = parseFloat(document.querySelector(`input[name="items[${idx}][diskon_persen]"]`).value) || 0;
            const diskRp = (subtotal * diskPersen) / 100;
            total1 += Math.max(0, subtotal - diskRp);
        });

        const potongan = parseFloat(document.getElementById('input_potongan').value) || 0;
        const total2 = Math.max(0, total1 - potongan);

        const ppnPersen = parseFloat(document.getElementById('input_ppn_persen').value) || 0;
        let ppnRp = 0;

        if (isManualPpn) {
            ppnRp = parseFloat(document.getElementById('input_ppn_rp').value) || 0;
        } else {
            ppnRp = (total2 * ppnPersen) / 100;
            document.getElementById('input_ppn_rp').value = Math.round(ppnRp);
        }

        const meterai = parseFloat(document.getElementById('input_meterai').value) || 0;
        const grandTotal = total2 + ppnRp + meterai;

        document.getElementById('disp_total1').innerText = total1.toLocaleString('id-ID');
        document.getElementById('disp_total2').innerText = total2.toLocaleString('id-ID');
        document.getElementById('disp_jumlah_tagihan').innerText = grandTotal.toLocaleString('id-ID');
    }

    // Quick filter keyword
    document.getElementById('quick_filter_keyword_terima').addEventListener('input', function() {
        const val = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.baris-obat-penerimaan');
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

    document.getElementById('btn_clear_filter_terima').addEventListener('click', function() {
        document.getElementById('quick_filter_keyword_terima').value = '';
        document.querySelectorAll('.baris-obat-penerimaan').forEach(r => r.style.display = '');
    });
</script>
@endsection
