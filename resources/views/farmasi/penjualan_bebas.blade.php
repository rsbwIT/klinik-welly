@extends('layout.layout')

@section('title', 'Transaksi Penjualan Obat Bebas, Alkes & BHP Medis')
@section('hide_content_header', 'true')

@section('content')
<style>
    /* Styling Persis Form SIMRS Khanza Sesuai Gambar Lampiran */
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
            @if (session('last_nota'))
                <div>
                    <a href="{{ route('penjualan.bebas.cetak', session('last_nota')) }}" target="_blank" class="btn btn-xs btn-light font-weight-bold ml-2">
                        <i class="fas fa-print mr-1"></i> Cetak Struk
                    </a>
                </div>
            @endif
            <button type="button" class="close p-1" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <form action="{{ route('penjualan.bebas.store') }}" method="POST" id="formKhanzaPenjualan">
        @csrf

        <div class="khanza-window">
            <!-- Header bar judul -->
            <div class="khanza-title d-flex justify-content-between align-items-center">
                <span>:: [ Transaksi Penjualan Obat, Alkes & BHP Medis ] ::</span>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-xs btn-primary font-weight-bold shadow-2xs" onclick="bukaModalRiwayatCetakNota()">
                        <i class="fas fa-print mr-1"></i> Cetak Struk / Nota
                    </button>
                    <span class="badge badge-light border text-muted ml-1" style="font-size: 10px;">Versi Standar SIMRS Khanza</span>
                </div>
            </div>

            <!-- Area Parameter Input Header (Persis 3 Kolom Khanza di Gambar) -->
            <div class="p-2 border-bottom" style="background: #fafafa;">
                <div class="row align-items-center mb-1">
                    <!-- No. Nota -->
                    <div class="col-auto d-flex align-items-center" style="width: 250px;">
                        <span class="khanza-lbl" style="width: 70px;">No. Nota :</span>
                        <input type="text" name="no_nota" value="{{ $noNota }}" class="khanza-input khanza-input-disabled font-monospace font-weight-bold text-primary flex-fill" readonly>
                    </div>

                    <!-- Pasien / Pembeli -->
                    <div class="col d-flex align-items-center">
                        <span class="khanza-lbl mr-1" style="width: 55px;">Pasien :</span>
                        <input type="text" name="no_rm_pembeli" class="khanza-input khanza-input-disabled mr-1" style="width: 60px;" placeholder="-" readonly>
                        <input type="text" name="nama_pembeli" id="nama_pembeli" class="khanza-input font-weight-bold flex-fill mr-1" placeholder="Ketik nama pembeli..." required autocomplete="off" autofocus>
                        <button type="button" class="btn-edit-icon" title="Cari Pasien"><i class="fas fa-search"></i></button>
                    </div>

                    <!-- No. Telp -->
                    <div class="col-auto d-flex align-items-center" style="width: 200px;">
                        <span class="khanza-lbl mr-1">No. Telp :</span>
                        <input type="text" name="no_telp" class="khanza-input flex-fill" placeholder="08xxx" autocomplete="off">
                    </div>
                </div>

                <div class="row align-items-center mb-1">
                    <!-- Catatan -->
                    <div class="col-auto d-flex align-items-center" style="width: 250px;">
                        <span class="khanza-lbl" style="width: 70px;">Catatan :</span>
                        <input type="text" name="catatan" placeholder="Catatan transaksi" class="khanza-input flex-fill" autocomplete="off">
                    </div>

                    <!-- Petugas -->
                    <div class="col d-flex align-items-center">
                        <span class="khanza-lbl mr-1" style="width: 55px;">Petugas :</span>
                        <input type="text" value="01091999" class="khanza-input khanza-input-disabled font-monospace mr-1" style="width: 75px;" readonly>
                        <input type="text" name="petugas" value="{{ Auth::user()->name ?? 'Petugas Farmasi' }}" class="khanza-input khanza-input-disabled font-weight-bold flex-fill mr-1" readonly>
                        <button type="button" class="btn-edit-icon" title="Pilih Petugas"><i class="fas fa-user-edit"></i></button>
                    </div>
                </div>

                <div class="row align-items-center">
                    <!-- Tanggal -->
                    <div class="col-auto d-flex align-items-center" style="width: 200px;">
                        <span class="khanza-lbl" style="width: 70px;">Tanggal :</span>
                        <input type="date" name="tgl_jual" value="{{ date('Y-m-d') }}" class="khanza-input flex-fill">
                    </div>

                    <!-- Jenis Jual -->
                    <div class="col-auto d-flex align-items-center" style="width: 170px;">
                        <span class="khanza-lbl mr-1">Jenis Jual :</span>
                        <select name="cara_bayar" class="khanza-input font-weight-bold flex-fill">
                            <option value="Jual Bebas">Jual Bebas</option>
                            <option value="Tunai">Tunai</option>
                            <option value="Transfer">Transfer</option>
                            <option value="QRIS">QRIS</option>
                        </select>
                    </div>

                    <!-- Lokasi Depo (Mapping Bangsal / Gudang Obat) -->
                    <div class="col d-flex align-items-center">
                        <span class="khanza-lbl mr-1" style="width: 55px;">Lokasi :</span>
                        <input type="text" id="input_kd_bangsal" name="kd_bangsal" value="{{ $depoAktif->kd_bangsal ?? 'DepoRJ' }}" class="khanza-input khanza-input-disabled font-monospace font-weight-bold mr-1" style="width: 65px;" readonly>
                        <input type="text" id="input_nm_bangsal" value="{{ $depoAktif->nm_bangsal ?? 'Depo Rawat Jalan' }}" class="khanza-input khanza-input-disabled font-weight-bold flex-fill mr-1" readonly>
                        <button type="button" class="btn-edit-icon" id="btn_modal_pilih_depo" title="Pilih / Ganti Lokasi Depo Obat" data-toggle="modal" data-target="#modalPilihDepo"><i class="fas fa-edit text-primary"></i></button>
                    </div>
                </div>
            </div>

            <!-- Tab Indikator: Umum vs Racikan -->
            <div class="d-flex justify-content-between align-items-center px-3 py-1 border-bottom" style="background: #ffffff;">
                <div class="d-flex gap-2">
                    <span class="font-weight-bold text-primary" style="font-size: 11px; border-bottom: 2px solid #0284c7; padding-bottom: 2px;">
                        Umum
                    </span>
                    <span class="text-muted ml-3" style="font-size: 11px; cursor: pointer;">
                        Racikan
                    </span>
                </div>
                <div class="d-flex align-items-center text-muted" style="font-size: 10.5px;">
                    <i class="fas fa-info-circle text-info mr-1"></i> Masukkan angka di kolom <strong>Jml</strong> untuk langsung memilih obat
                </div>
            </div>

            <!-- GRID TABEL KATALOG OBAT PERSIS KHANZA -->
            <div class="table-responsive" style="max-height: 460px; overflow-y: auto; overflow-x: auto; background: #ffffff;">
                <table class="table-khanza-grid" id="tabelKhanzaObat">
                    <thead>
                        <tr>
                            <th width="55px" class="text-center" style="background: #f8fafc;">Jml</th>
                            <th width="90px">Kode Barang</th>
                            <th>Nama Barang</th>
                            <th width="110px">Kategori</th>
                            <th width="50px">Satuan</th>
                            <th width="75px" class="text-right">Harga(Rp)</th>
                            <th width="85px" class="text-right">Subtotal(Rp)</th>
                            <th width="50px" class="text-center">Ptg(%)</th>
                            <th width="65px" class="text-right">Ptg(Rp)</th>
                            <th width="55px" class="text-right">Tmb(Rp)</th>
                            <th width="55px" class="text-right">Emb(Rp)</th>
                            <th width="55px" class="text-right">Tsl(Rp)</th>
                            <th width="110px">Aturan Pakai</th>
                            <th width="85px" class="text-right" style="background: #f8fafc;">Total(Rp)</th>
                            <th width="50px" class="text-center">Stok</th>
                            <th width="70px">No.Batch</th>
                            <th width="75px">No.Faktur</th>
                            <th width="75px">Kadaluarsa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($obatList as $idx => $ob)
                            <tr class="row-obat-khanza" data-kode="{{ $ob->kode_brng }}" data-nama="{{ $ob->nama_brng }}">
                                <td class="text-center" style="background: #fafafa;">
                                    <input type="number" 
                                           min="0" 
                                           max="{{ $ob->stok }}"
                                           name="items[{{ $idx }}][jumlah]" 
                                           id="qty_{{ $ob->kode_brng }}" 
                                           class="input-jml-khanza input-qty-hitung" 
                                           data-kode="{{ $ob->kode_brng }}" 
                                           data-harga="{{ $ob->ralan }}" 
                                           placeholder="0"
                                           autocomplete="off">
                                    <input type="hidden" name="items[{{ $idx }}][kode_brng]" value="{{ $ob->kode_brng }}">
                                    <input type="hidden" name="items[{{ $idx }}][nama_brng]" value="{{ $ob->nama_brng }}">
                                    <input type="hidden" name="items[{{ $idx }}][satuan]" value="{{ $ob->kode_sat }}">
                                    <input type="hidden" name="items[{{ $idx }}][harga_jual]" value="{{ $ob->ralan }}">
                                    <input type="hidden" name="items[{{ $idx }}][subtotal]" id="subtotal_input_{{ $ob->kode_brng }}" value="0">
                                </td>
                                <td class="font-monospace text-primary font-weight-bold">{{ $ob->kode_brng }}</td>
                                <td class="font-weight-bold text-dark">{{ $ob->nama_brng }}</td>
                                <td><span class="text-uppercase">{{ $ob->kategori ?? 'OBAT ORAL' }}</span></td>
                                <td class="text-center font-monospace">{{ $ob->kode_sat }}</td>
                                <td class="text-right font-monospace">{{ number_format($ob->ralan, 0, ',', '.') }}</td>
                                <td class="text-right font-monospace text-muted" id="subtotal_txt_{{ $ob->kode_brng }}">0</td>
                                <td class="text-center font-monospace text-muted">0</td>
                                <td class="text-right font-monospace text-muted">0</td>
                                <td class="text-right font-monospace text-muted">0</td>
                                <td class="text-right font-monospace text-muted">0</td>
                                <td class="text-right font-monospace text-muted">0</td>
                                <td>
                                    <input type="text" name="items[{{ $idx }}][aturan_pakai]" class="khanza-input" placeholder="3x1 Hari" style="width: 100px; height: 19px; font-size: 10px;">
                                </td>
                                <td class="text-right font-monospace font-weight-bold text-primary" id="total_txt_{{ $ob->kode_brng }}">0</td>
                                <td class="text-center font-monospace font-weight-bold {{ $ob->stok <= 5 ? 'text-danger' : 'text-success' }}">{{ $ob->stok }}</td>
                                <td class="font-monospace text-muted">{{ $ob->no_batch ?? '-' }}</td>
                                <td class="font-monospace text-muted">{{ $ob->no_faktur ?? '-' }}</td>
                                <td class="font-monospace text-muted">-</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="18" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle mr-1"></i> Tidak ada data katalog obat / balutan luka.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- FOOTER BAR PERHITUNGAN PERSIS KHANZA -->
            <div class="khanza-bottom-bar">
                <div class="row align-items-center">
                    <!-- Grand Total & PPN -->
                    <div class="col-md-3 col-sm-6">
                        <div class="d-flex align-items-center mb-1">
                            <span class="khanza-lbl" style="width: 85px;">Grand Total :</span>
                            <span class="font-monospace font-weight-bold text-dark ml-1" id="lbl_grand_total">0</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="khanza-lbl" style="width: 85px;">PPN Obat(%) :</span>
                            <input type="number" id="ppn_persen" value="11" class="khanza-input text-center font-monospace mr-1" style="width: 35px;" readonly>
                            <input type="text" value="0" class="khanza-input text-right font-monospace" style="width: 60px;" readonly>
                        </div>
                    </div>

                    <!-- Tagihan + PPN & Ongkos Kirim -->
                    <div class="col-md-3 col-sm-6">
                        <div class="d-flex align-items-center mb-1">
                            <span class="khanza-lbl font-weight-bold" style="width: 85px;">Tagihan + PPN:</span>
                            <span class="font-monospace font-weight-bold text-primary ml-1" id="lbl_tagihan">0</span>
                            <input type="hidden" name="harus_bayar" id="input_harus_bayar" value="0">
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="khanza-lbl" style="width: 85px;">Ongkos Kirim :</span>
                            <input type="number" id="ongkos_kirim" value="0" class="khanza-input text-right font-monospace" style="width: 80px;">
                        </div>
                    </div>

                    <!-- Akun Bayar, Jumlah Bayar & Kembali -->
                    <div class="col-md-6 col-sm-12">
                        <div class="d-flex align-items-center mb-1">
                            <span class="khanza-lbl" style="width: 75px;">Akun Bayar :</span>
                            <select class="khanza-input font-weight-bold mr-1" style="width: 140px;">
                                <option value="Kasir">Bayar Cash / BSI</option>
                                <option value="Mandiri">Bank Mandiri</option>
                            </select>
                            <input type="text" value="0.0" class="khanza-input font-monospace mr-1" style="width: 45px;" readonly>
                            <input type="text" value="0" class="khanza-input font-monospace" style="width: 45px;" readonly>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="khanza-lbl font-weight-bold" style="width: 75px;">Jumlah Bayar:</span>
                            <input type="number" name="jumlah_bayar" id="input_jumlah_bayar" value="0" class="khanza-input text-right font-weight-bold font-monospace mr-3" style="width: 90px;" placeholder="0">
                            <span class="khanza-lbl font-weight-bold">Kembali :</span>
                            <span class="font-monospace font-weight-bold text-success ml-2" id="lbl_kembali">0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACTION BAR BAWAH: NOTA, SIMPAN, CARI KEY WORD & KELUAR PERSIS KHANZA -->
            <div class="p-2 border-top d-flex flex-wrap justify-content-between align-items-center" style="background: #ffffff;">
                <div class="d-flex align-items-center gap-1 mb-2 mb-md-0">
                    <button type="button" class="btn btn-sm btn-outline-primary font-weight-bold px-3 mr-1" id="btn_cetak_nota_bawah" title="Cetak Struk / Nota Transaksi Terakhir">
                        <i class="fas fa-print mr-1"></i> Cetak Struk
                    </button>
                    <button type="button" class="btn btn-sm btn-light border text-dark font-weight-bold px-2 mr-2" onclick="bukaModalRiwayatCetakNota()" title="Pilih Struk Transaksi Lain">
                        <i class="fas fa-history mr-1"></i> Riwayat Struk
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary font-weight-bold px-3" id="btn_simpan_bawah">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>

                <!-- Input Pencarian Cepat Obat / Key Word -->
                <div class="d-flex align-items-center gap-1">
                    <span class="khanza-lbl mr-1 font-weight-bold">Key Word :</span>
                    <input type="text" id="quick_filter_obat" class="khanza-input mr-1" placeholder="Cari nama barang..." style="width: 220px;" autocomplete="off" value="{{ $keyword ?? '' }}">
                    <button type="button" class="btn-khanza-action p-1" id="btn_apply_filter" title="Filter"><i class="fas fa-check text-success"></i></button>
                    <button type="button" class="btn-khanza-action p-1" id="btn_clear_filter" title="Reset"><i class="fas fa-undo text-secondary"></i></button>
                    <button type="button" class="btn-khanza-action ml-1"><i class="fas fa-search text-info mr-1"></i> Cari</button>
                    <a href="{{ route('rawatjalan.index') }}" class="btn-khanza-action text-danger ml-1 text-decoration-none">
                        <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const qtyInputs = document.querySelectorAll('.input-qty-hitung');
        const lblGrandTotal = document.getElementById('lbl_grand_total');
        const lblTagihan = document.getElementById('lbl_tagihan');
        const inputHarusBayar = document.getElementById('input_harus_bayar');
        const inputJumlahBayar = document.getElementById('input_jumlah_bayar');
        const lblKembali = document.getElementById('lbl_kembali');
        const quickFilter = document.getElementById('quick_filter_obat');
        const rows = document.querySelectorAll('.row-obat-khanza');

        function hitungSemua() {
            let grandTotal = 0;

            qtyInputs.forEach(input => {
                const qty = parseInt(input.value) || 0;
                const harga = parseFloat(input.getAttribute('data-harga')) || 0;
                const kode = input.getAttribute('data-kode');

                const subtotal = qty * harga;
                if (qty > 0) {
                    input.classList.add('filled');
                    grandTotal += subtotal;
                    document.getElementById('subtotal_txt_' + kode).textContent = subtotal.toLocaleString('id-ID');
                    document.getElementById('total_txt_' + kode).textContent = subtotal.toLocaleString('id-ID');
                    document.getElementById('subtotal_input_' + kode).value = subtotal;
                } else {
                    input.classList.remove('filled');
                    document.getElementById('subtotal_txt_' + kode).textContent = '0';
                    document.getElementById('total_txt_' + kode).textContent = '0';
                    document.getElementById('subtotal_input_' + kode).value = 0;
                }
            });

            lblGrandTotal.textContent = grandTotal.toLocaleString('id-ID');
            lblTagihan.textContent = grandTotal.toLocaleString('id-ID');
            inputHarusBayar.value = grandTotal;

            // Jumlah Bayar & Kembali
            let bayar = parseFloat(inputJumlahBayar.value) || 0;
            if (bayar === 0 && grandTotal > 0) {
                bayar = grandTotal;
                inputJumlahBayar.value = bayar;
            }
            const kembali = Math.max(0, bayar - grandTotal);
            lblKembali.textContent = kembali.toLocaleString('id-ID');
        }

        qtyInputs.forEach(input => {
            input.addEventListener('input', hitungSemua);
        });

        inputJumlahBayar.addEventListener('input', function () {
            const grandTotal = parseFloat(inputHarusBayar.value) || 0;
            const bayar = parseFloat(this.value) || 0;
            const kembali = Math.max(0, bayar - grandTotal);
            lblKembali.textContent = kembali.toLocaleString('id-ID');
        });

        // Key Word realtime filter
        quickFilter.addEventListener('input', function () {
            const val = this.value.toLowerCase().trim();
            rows.forEach(r => {
                const nama = (r.getAttribute('data-nama') || '').toLowerCase();
                const kode = (r.getAttribute('data-kode') || '').toLowerCase();
                if (nama.includes(val) || kode.includes(val)) {
                    r.style.display = '';
                } else {
                    r.style.display = 'none';
                }
            });
        });

        document.getElementById('btn_clear_filter').addEventListener('click', function () {
            quickFilter.value = '';
            rows.forEach(r => r.style.display = '');
        });

        document.getElementById('btn_cetak_nota_bawah').addEventListener('click', function() {
            @if (session('last_nota'))
                window.open("{{ route('penjualan.bebas.cetak', session('last_nota')) }}", "_blank");
            @elseif(isset($riwayat) && count($riwayat) > 0)
                window.open("{{ route('penjualan.bebas.cetak', $riwayat->first()->no_nota) }}", "_blank");
            @else
                bukaModalRiwayatCetakNota();
            @endif
        });

        // Pindahkan elemen modal ke body dokumen agar tidak terhalang backdrop form z-index
        $('#modalPilihDepo').appendTo('body');
    });

    function setDepoLokasi(kd, nm) {
        document.getElementById('input_kd_bangsal').value = kd;
        document.getElementById('input_nm_bangsal').value = nm;
        $('#modalPilihDepo').modal('hide');

        // Arahkan ke URL dengan parameter depo
        var targetUrl = "{{ route('penjualan.bebas.index') }}?kd_bangsal=" + encodeURIComponent(kd);
        window.location.href = targetUrl;
    }
    function bukaModalRiwayatCetakNota() {
        $('#modalRiwayatCetakNota').appendTo('body').modal('show');
    }
</script>

<!-- MODAL RIWAYAT CETAK STRUK NOTA -->
<div class="modal fade" id="modalRiwayatCetakNota" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 100005;">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 8px;">
            <div class="modal-header bg-primary text-white py-2 px-3 align-items-center">
                <h6 class="modal-title font-weight-bold" style="font-size: 13px;">
                    <i class="fas fa-print mr-2"></i> Pilih Struk / Nota Penjualan untuk Dicetak
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                    <table class="table table-sm table-hover table-bordered m-0" style="font-size: 11px;">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th width="120px">No. Nota</th>
                                <th width="90px">Tanggal</th>
                                <th>Nama Pembeli / Pasien</th>
                                <th width="110px" class="text-right">Total Bayar</th>
                                <th width="85px" class="text-center">Petugas</th>
                                <th width="100px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $rw)
                                <tr>
                                    <td class="font-monospace font-weight-bold text-primary">{{ $rw->no_nota }}</td>
                                    <td>{{ date('d/m/Y', strtotime($rw->tgl_jual)) }}</td>
                                    <td><strong>{{ $rw->nama_pembeli }}</strong></td>
                                    <td class="text-right font-monospace font-weight-bold text-success">
                                        Rp {{ number_format($rw->harus_bayar ?? $rw->total_biaya, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center text-muted">{{ $rw->petugas }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('penjualan.bebas.cetak', $rw->no_nota) }}" target="_blank" class="btn btn-xs btn-primary font-weight-bold px-2 py-1">
                                            <i class="fas fa-print mr-1"></i> Cetak
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle mr-1"></i> Belum ada riwayat transaksi penjualan bebas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light">
                <button type="button" class="btn btn-sm btn-secondary font-weight-bold" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PILIH LOKASI DEPO / BANGSAL FARMASI -->
<div class="modal fade" id="modalPilihDepo" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 100000;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 8px;">
            <div class="modal-header bg-primary text-white py-2 px-3 align-items-center">
                <h6 class="modal-title font-weight-bold" style="font-size: 13px;">
                    <i class="fas fa-warehouse mr-2"></i> Pilih Lokasi Depo / Bangsal Obat
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-3">
                <p class="text-muted small mb-2">
                    Pilih lokasi depo farmasi tempat stok obat dan BHP medis dikeluarkan:
                </p>
                <div class="list-group">
                    @foreach($depoList as $dp)
                        <a href="javascript:void(0)" 
                           onclick="setDepoLokasi('{{ $dp->kd_bangsal }}', '{{ $dp->nm_bangsal }}')" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3 {{ ($selectedDepo ?? 'DepoRJ') == $dp->kd_bangsal ? 'active font-weight-bold' : '' }}">
                            <div>
                                <span class="badge badge-light border font-monospace mr-2 text-dark">{{ $dp->kd_bangsal }}</span>
                                <strong>{{ $dp->nm_bangsal }}</strong>
                            </div>
                            @if(($selectedDepo ?? 'DepoRJ') == $dp->kd_bangsal)
                                <span class="badge badge-light text-primary font-weight-bold"><i class="fas fa-check"></i> Aktif</span>
                            @else
                                <span class="badge badge-secondary">Pilih</span>
                            @endif
                        </a>
                    @endforeach
                    <a href="javascript:void(0)" 
                       onclick="setDepoLokasi('SEMUA', 'Semua Depo / Apotek')" 
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3 {{ ($selectedDepo ?? '') == 'SEMUA' ? 'active font-weight-bold' : '' }}">
                        <div>
                            <span class="badge badge-light border font-monospace mr-2 text-dark">ALL</span>
                            <strong>Semua Depo / Apotek (Tampilkan Seluruh Obat)</strong>
                        </div>
                        <span class="badge badge-secondary">Pilih</span>
                    </a>
                </div>
            </div>
            <div class="modal-footer py-2 px-3 bg-light d-flex justify-content-between align-items-center">
                <a href="{{ route('master.obat.index') }}" target="_blank" class="btn btn-sm btn-outline-info font-weight-bold">
                    <i class="fas fa-boxes mr-1"></i> Kelola & Isi Stok Depo di Master Obat
                </a>
                <button type="button" class="btn btn-sm btn-secondary font-weight-bold" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection
