@extends('layout.layout')

@section('title', 'Data Obat, Alkes & BHP Medis')
@section('hide_content_header', 'true')

@section('content')
<style>
    /* Styling Persis Form SIMRS Khanza DlgDataBarang Sesuai Gambar Lampiran */
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
        height: 22px;
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
        width: 22px;
        height: 22px;
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
        cursor: pointer;
    }
    .table-khanza-grid tbody tr.selected-row {
        background-color: #e0f2fe !important;
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

    <div class="khanza-window">
        <!-- Header bar judul persis gambar lampiran -->
        <div class="khanza-title d-flex justify-content-between align-items-center">
            <span>:: [ Data Obat, Alkes & BHP Medis ] ::</span>
            <div class="d-flex gap-2">
                <a href="{{ route('farmasi.penerimaan.index') }}" class="btn btn-xs btn-outline-success font-weight-bold">
                    <i class="fas fa-truck mr-1"></i> Penerimaan Obat
                </a>
                <a href="{{ route('farmasi.mutasi.index') }}" class="btn btn-xs btn-outline-info font-weight-bold ml-1">
                    <i class="fas fa-dolly-flatbed mr-1"></i> Mutasi Antar Depo
                </a>
            </div>
        </div>

        <!-- FORM INPUT / EDIT DATA OBAT KHANZA (PERSIS GRID INPUT ATAS DI GAMBAR) -->
        <form action="{{ route('master.obat.store') }}" method="POST" id="formKhanzaObat">
            @csrf
            <input type="hidden" name="is_edit" id="input_is_edit" value="0">

            <div class="p-2 border-bottom" style="background: #fafafa;">
                <div class="row">
                    <!-- KOLOM 1: Kode, Nama, Kandungan, Satuan Besar, Satuan Kecil, Kategori, Golongan, Hrg Dasar, Hrg Beli -->
                    <div class="col-lg-5 col-md-12 pr-lg-2 mb-2 mb-lg-0">
                        <!-- Kode Barang & Industri Farmasi (I.F.) -->
                        <div class="d-flex align-items-center mb-1">
                            <span class="khanza-lbl mr-1" style="width: 85px; flex-shrink: 0;">Kode Barang :</span>
                            <input type="text" name="kode_brng" id="input_kode_brng" value="{{ $autoKode }}" class="khanza-input font-monospace font-weight-bold text-primary mr-2" style="width: 105px; flex-shrink: 0;" required>
                            <span class="khanza-lbl mr-1" style="flex-shrink: 0;">I.F. :</span>
                            <input type="text" name="kode_industri" id="input_kode_industri" value="-" class="khanza-input font-monospace font-weight-bold mr-1" style="width: 65px; flex-shrink: 0;" placeholder="Kode">
                            <input type="text" id="input_nama_industri" value="-" class="khanza-input flex-fill text-truncate" placeholder="Nama Industri Farmasi" readonly>
                            <button type="button" class="btn-edit-icon ml-1" title="Pilih Data Industri Farmasi" onclick="bukaModalPilihIndustri()"><i class="fas fa-edit text-primary"></i></button>
                        </div>

                        <!-- Nama Barang -->
                        <div class="d-flex align-items-center mb-1">
                            <span class="khanza-lbl mr-1" style="width: 85px; flex-shrink: 0;">Nama Barang :</span>
                            <input type="text" name="nama_brng" id="input_nama_brng" class="khanza-input font-weight-bold flex-fill" placeholder="Nama obat / alkes..." required>
                        </div>

                        <!-- Kandungan -->
                        <div class="d-flex align-items-center mb-1">
                            <span class="khanza-lbl mr-1" style="width: 85px; flex-shrink: 0;">Kandungan :</span>
                            <input type="text" name="kandungan" id="input_kandungan" class="khanza-input flex-fill" placeholder="Komposisi aktif / zat aktif...">
                        </div>

                        <!-- Satuan Besar & Isi -->
                        <div class="d-flex align-items-center mb-1">
                            <span class="khanza-lbl mr-1" style="width: 85px; flex-shrink: 0;">Satuan Besar :</span>
                            <input type="text" name="kode_satbesar" id="input_kode_satbesar" value="Box" class="khanza-input mr-1" style="width: 85px;">
                            <button type="button" class="btn-edit-icon mr-2" title="Pilih Satuan Besar" onclick="bukaModalPilihSatuan('besar')"><i class="fas fa-edit text-primary"></i></button>
                            <span class="khanza-lbl mr-1" style="flex-shrink: 0;">Isi :</span>
                            <input type="number" step="any" min="1" name="isi" id="input_isi" value="1" class="khanza-input font-monospace text-right" style="width: 55px;">
                        </div>

                        <!-- Satuan Kecil & Kapasitas -->
                        <div class="d-flex align-items-center mb-1">
                            <span class="khanza-lbl mr-1" style="width: 85px; flex-shrink: 0;">Satuan Kecil :</span>
                            <input type="text" name="kode_sat" id="input_kode_sat" value="Pcs" class="khanza-input mr-1" style="width: 85px;" required>
                            <button type="button" class="btn-edit-icon mr-2" title="Pilih Satuan Kecil" onclick="bukaModalPilihSatuan('kecil')"><i class="fas fa-edit text-primary"></i></button>
                            <span class="khanza-lbl mr-1" style="flex-shrink: 0;">Kapasitas :</span>
                            <input type="number" step="any" min="1" name="kapasitas" id="input_kapasitas" value="1" class="khanza-input font-monospace text-right" style="width: 55px;">
                        </div>

                        <!-- Kategori -->
                        <div class="d-flex align-items-center mb-1">
                            <span class="khanza-lbl mr-1" style="width: 85px; flex-shrink: 0;">Kategori :</span>
                            <input type="text" name="kategori" id="input_kategori" value="Obat Oral / Tablet" class="khanza-input flex-fill text-truncate" required>
                            <button type="button" class="btn-edit-icon ml-1" title="Pilih Kategori Barang" onclick="bukaModalPilihKategori()"><i class="fas fa-edit text-primary"></i></button>
                        </div>

                        <!-- Golongan -->
                        <div class="d-flex align-items-center mb-1">
                            <span class="khanza-lbl mr-1" style="width: 85px; flex-shrink: 0;">Golongan :</span>
                            <input type="text" name="golongan" id="input_golongan" value="Obat Bebas (Hijau)" class="khanza-input flex-fill text-truncate">
                            <button type="button" class="btn-edit-icon ml-1" title="Pilih Golongan Barang" onclick="bukaModalPilihGolongan()"><i class="fas fa-edit text-primary"></i></button>
                        </div>

                        <!-- Harga Dasar, Beli & Ralan -->
                        <div class="d-flex align-items-center mb-1">
                            <span class="khanza-lbl mr-1" style="width: 85px; flex-shrink: 0;">Harga Dasar :</span>
                            <input type="number" step="any" min="0" name="dasar" id="input_dasar" value="0" class="khanza-input font-monospace mr-2 text-right" style="width: 100px;" oninput="syncHargaBeli(this.value)">
                            <span class="khanza-lbl mr-1" style="flex-shrink: 0;">Harga Beli :</span>
                            <input type="number" step="any" min="0" name="h_beli" id="input_h_beli" value="0" class="khanza-input font-monospace text-right flex-fill">
                        </div>

                        <div class="d-flex align-items-center">
                            <span class="khanza-lbl mr-1" style="width: 85px; flex-shrink: 0;">Hrg Ralan :</span>
                            <input type="number" step="any" min="0" name="ralan" id="input_ralan" value="0" class="khanza-input font-monospace text-right font-weight-bold text-success mr-2" style="width: 100px;" required oninput="syncHargaJualLain(this.value)">
                            <span class="khanza-lbl mr-1" style="flex-shrink: 0;">Hrg Ranap K1 :</span>
                            <input type="number" step="any" min="0" name="kelas1" id="input_kelas1" value="0" class="khanza-input font-monospace text-right flex-fill">
                        </div>
                    </div>

                    <!-- KOLOM 2: Harga-Harga Rawat Inap & Tarif Khusus Khanza -->
                    <div class="col-lg-4 col-md-7 px-lg-2 border-left mb-2 mb-lg-0">
                        <div class="d-flex align-items-center mb-1">
                            <span class="khanza-lbl mr-1" style="width: 135px; flex-shrink: 0;">Hrg Rnp Kelas 2 : Rp.</span>
                            <input type="number" step="any" min="0" name="kelas2" id="input_kelas2" value="0" class="khanza-input font-monospace text-right flex-fill">
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <span class="khanza-lbl mr-1" style="width: 135px; flex-shrink: 0;">Hrg Rnp Kelas 3 : Rp.</span>
                            <input type="number" step="any" min="0" name="kelas3" id="input_kelas3" value="0" class="khanza-input font-monospace text-right flex-fill">
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <span class="khanza-lbl mr-1" style="width: 135px; flex-shrink: 0;">Hrg Utama/BPJS : Rp.</span>
                            <input type="number" step="any" min="0" name="utama" id="input_utama" value="0" class="khanza-input font-monospace text-right flex-fill">
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <span class="khanza-lbl mr-1" style="width: 135px; flex-shrink: 0;">Hrg Rnp Kelas VIP : Rp.</span>
                            <input type="number" step="any" min="0" name="vip" id="input_vip" value="0" class="khanza-input font-monospace text-right flex-fill">
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <span class="khanza-lbl mr-1" style="width: 135px; flex-shrink: 0;">Hrg Rnp Kelas VVIP: Rp.</span>
                            <input type="number" step="any" min="0" name="vvip" id="input_vvip" value="0" class="khanza-input font-monospace text-right flex-fill">
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <span class="khanza-lbl mr-1" style="width: 135px; flex-shrink: 0;">Hrg Apotek Luar : Rp.</span>
                            <input type="number" step="any" min="0" name="beliluar" id="input_beliluar" value="0" class="khanza-input font-monospace text-right flex-fill">
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <span class="khanza-lbl mr-1" style="width: 135px; flex-shrink: 0;">Hrg Jual Obat Bebas: Rp.</span>
                            <input type="number" step="any" min="0" name="jualbebas" id="input_jualbebas" value="0" class="khanza-input font-monospace text-right flex-fill">
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="khanza-lbl mr-1" style="width: 135px; flex-shrink: 0;">Hrg Karyawan : Rp.</span>
                            <input type="number" step="any" min="0" name="karyawan" id="input_karyawan" value="0" class="khanza-input font-monospace text-right flex-fill">
                        </div>
                    </div>

                    <!-- KOLOM 3: Stok Minimal & Kadaluarsa -->
                    <div class="col-lg-3 col-md-5 pl-lg-2 border-left">
                        <div class="d-flex align-items-center mb-2">
                            <span class="khanza-lbl mr-1" style="width: 100px; flex-shrink: 0;">Stok Minimal :</span>
                            <input type="number" step="any" min="0" name="stokminimal" id="input_stokminimal" value="10" class="khanza-input font-monospace text-right flex-fill">
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <input type="checkbox" id="check_kadaluarsa" class="mr-1" checked>
                            <span class="khanza-lbl mr-1" style="flex-shrink: 0;">Kadaluarsa :</span>
                            <input type="date" name="expire" id="input_expire" value="{{ date('Y-12-31', strtotime('+1 year')) }}" class="khanza-input flex-fill font-monospace">
                        </div>
                        <div class="p-2 border rounded bg-white small text-muted" style="line-height: 1.35;">
                            <i class="fas fa-info-circle text-primary mr-1"></i> Klik baris tabel di bawah untuk memilih dan mengedit data obat/alkes.
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABEL GRID PERSIS KOLOM GAMBAR SIMRS KHANZA -->
            <div style="height: calc(100vh - 355px); min-height: 320px; overflow: auto; background: #ffffff;">
                <table class="table-khanza-grid" id="tabelGridKhanzaObat">
                    <thead>
                        <tr>
                            <th style="width: 30px; text-align: center;">P</th>
                            <th style="width: 95px;">Kode Barang</th>
                            <th style="min-width: 190px;">Nama Barang</th>
                            <th style="width: 70px;">Kd.Sat Besar</th>
                            <th style="width: 85px;">Nm.Satuan Besar</th>
                            <th style="width: 45px; text-align: right;">Isi</th>
                            <th style="width: 65px;">Kd.Sat Kecil</th>
                            <th style="width: 85px;">Nm.Satuan Kecil</th>
                            <th style="width: 55px; text-align: right;">Kps</th>
                            <th style="min-width: 120px;">Kandungan</th>
                            <th style="width: 85px; text-align: right;">Hrg.Dasar(Rp)</th>
                            <th style="width: 85px; text-align: right;">Hrg.Beli(Rp)</th>
                            <th style="width: 85px; text-align: right; color: #15803d;">Ralan(Rp)</th>
                            <th style="width: 85px; text-align: right;">Ranap K1(Rp)</th>
                            <th style="width: 85px; text-align: right;">Ranap K2(Rp)</th>
                            <th style="width: 85px; text-align: right;">Ranap K3(Rp)</th>
                            <th style="width: 90px; text-align: right;">Kelas Utama/B...</th>
                            <th style="width: 85px; text-align: right;">Ranap VIP(Rp)</th>
                            <th style="width: 90px; text-align: right;">Ranap VVIP(Rp)</th>
                            <th style="width: 85px; text-align: right;">Beli Luar(Rp)</th>
                            <th style="width: 85px; text-align: right;">Jual Bebas(Rp)</th>
                            <th style="width: 85px; text-align: right;">Karyawan(Rp)</th>
                            <th style="width: 60px; text-align: center;">Stok Min</th>
                            <th style="width: 85px; text-align: center;">Kadaluarsa</th>
                            <th style="width: 60px; text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($obats as $idx => $ob)
                            <tr class="baris-obat-khanza-row" 
                                onclick="pilihBarisObat(this, {{ json_encode($ob) }})"
                                data-nama="{{ strtolower($ob->nama_brng) }}" 
                                data-kode="{{ strtolower($ob->kode_brng) }}">
                                <td style="text-align: center;">
                                    <input type="checkbox" class="cb-pilih-obat">
                                </td>
                                <td class="font-monospace font-weight-bold text-primary">{{ $ob->kode_brng }}</td>
                                <td style="font-weight: 600;">{{ $ob->nama_brng }}</td>
                                <td>{{ $ob->kode_satbesar ?? 'Box' }}</td>
                                <td>{{ $ob->kode_satbesar ?? 'Box' }}</td>
                                <td style="text-align: right;" class="font-monospace">{{ $ob->isi ?? 1 }}</td>
                                <td>{{ $ob->kode_sat ?: 'Pcs' }}</td>
                                <td>{{ $ob->kode_sat ?: 'Pcs' }}</td>
                                <td style="text-align: right;" class="font-monospace">{{ $ob->kapasitas ?? 1 }}</td>
                                <td style="color: #64748b;">{{ $ob->kandungan ?? '-' }}</td>
                                <td style="text-align: right;" class="font-monospace">{{ number_format($ob->dasar ?: $ob->h_beli, 0, ',', '.') }}</td>
                                <td style="text-align: right;" class="font-monospace">{{ number_format($ob->h_beli, 0, ',', '.') }}</td>
                                <td style="text-align: right; font-weight: bold; color: #15803d;" class="font-monospace">{{ number_format($ob->ralan, 0, ',', '.') }}</td>
                                <td style="text-align: right;" class="font-monospace">{{ number_format($ob->kelas1 ?: $ob->ralan, 0, ',', '.') }}</td>
                                <td style="text-align: right;" class="font-monospace">{{ number_format($ob->kelas2 ?: $ob->ralan, 0, ',', '.') }}</td>
                                <td style="text-align: right;" class="font-monospace">{{ number_format($ob->kelas3 ?: $ob->ralan, 0, ',', '.') }}</td>
                                <td style="text-align: right;" class="font-monospace">{{ number_format($ob->utama ?: $ob->ralan, 0, ',', '.') }}</td>
                                <td style="text-align: right;" class="font-monospace">{{ number_format($ob->vip ?: $ob->ralan, 0, ',', '.') }}</td>
                                <td style="text-align: right;" class="font-monospace">{{ number_format($ob->vvip ?: $ob->ralan, 0, ',', '.') }}</td>
                                <td style="text-align: right;" class="font-monospace">{{ number_format($ob->beliluar ?: $ob->ralan, 0, ',', '.') }}</td>
                                <td style="text-align: right;" class="font-monospace">{{ number_format($ob->jualbebas ?: $ob->ralan, 0, ',', '.') }}</td>
                                <td style="text-align: right;" class="font-monospace">{{ number_format($ob->karyawan ?: $ob->ralan, 0, ',', '.') }}</td>
                                <td style="text-align: center;" class="font-monospace">{{ $ob->stokminimal ?? 10 }}</td>
                                <td style="text-align: center; color: #64748b;">{{ $ob->expire ? date('d-m-Y', strtotime($ob->expire)) : '-' }}</td>
                                <td style="text-align: center;">
                                    <span class="badge {{ $ob->status == '1' ? 'badge-success' : 'badge-secondary' }}" style="font-size: 10px;">
                                        {{ $ob->status == '1' ? 'Aktif' : 'Non' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- BARIS FILTER KEYWORD & RECORD COUNT PERSIS GAMBAR -->
            <div class="px-3 py-1 border-top bg-light d-flex align-items-center" style="font-size: 11px;">
                <span class="khanza-lbl mr-1 font-weight-bold">Key Word :</span>
                <input type="text" id="quick_filter_obat_khanza" class="khanza-input" style="width: 240px;" placeholder="Ketik kode atau nama barang...">
                <button type="button" class="btn-edit-icon ml-1" id="btn_clear_filter_obat" title="Clear Filter"><i class="fas fa-check text-success"></i></button>
                <button type="button" class="btn-edit-icon ml-1" title="Cari Data"><i class="fas fa-search text-muted"></i></button>
                <span class="ml-4 font-monospace font-weight-bold text-muted">Record : {{ count($obats) }}</span>
            </div>

            <!-- FOOTER BAR AKSI TOMBOL KHANZA PERSIS GAMBAR BAWAH -->
            <div class="khanza-bottom-bar d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-1">
                    <button type="submit" class="btn-khanza-action text-primary font-weight-bold" id="btn_simpan_obat">
                        <i class="fas fa-save text-primary"></i> Simpan
                    </button>
                    <button type="button" class="btn-khanza-action text-danger" onclick="formBaruObat()">
                        <i class="fas fa-plus text-danger"></i> Baru
                    </button>
                    <button type="button" class="btn-khanza-action text-danger" onclick="hapusObatTerpilih()">
                        <i class="fas fa-trash text-danger"></i> Hapus
                    </button>
                    <button type="button" class="btn-khanza-action text-warning" onclick="gantiObatTerpilih()">
                        <i class="fas fa-edit text-warning"></i> Ganti
                    </button>
                    <button type="button" class="btn-khanza-action text-info" onclick="window.print()">
                        <i class="fas fa-print text-info"></i> Cetak
                    </button>
                    <button type="button" class="btn-khanza-action text-dark" onclick="window.location.reload()">
                        <i class="fas fa-bars text-dark"></i> Semua
                    </button>
                    <a href="{{ url('/') }}" class="btn-khanza-action text-danger ml-2">
                        <i class="fas fa-times text-danger"></i> Keluar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL POPUP PILIH INDUSTRI FARMASI (I.F.) KHANZA -->
<div class="modal fade" id="modalPilihIndustriFarmasi" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 100000;">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 8px;">
            <div class="modal-header bg-primary text-white py-2 px-3 align-items-center">
                <h6 class="modal-title font-weight-bold" style="font-size: 13px;">
                    :: [ Data Industri Farmasi ] ::
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0">
                <div class="p-2 border-bottom bg-light d-flex align-items-center">
                    <span class="small font-weight-bold mr-2 text-muted">Cari Industri:</span>
                    <input type="text" id="filter_industri_popup" class="khanza-input flex-fill" placeholder="Ketik nama industri farmasi / produsen...">
                </div>
                <div style="max-height: 420px; overflow-y: auto;">
                    <table class="table table-sm table-hover table-bordered m-0" style="font-size: 11px;">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th width="90px">Kode I.F.</th>
                                <th>Industri Farmasi</th>
                                <th>Alamat Industri Farmasi</th>
                                <th width="120px">Kota</th>
                                <th width="90px">No.Telp</th>
                                <th width="65px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($industriList as $ind)
                                <tr class="baris-industri-row" data-nama="{{ strtolower($ind->nama_industri) }}" data-kode="{{ strtolower($ind->kode_industri) }}">
                                    <td class="font-monospace font-weight-bold text-primary">{{ $ind->kode_industri }}</td>
                                    <td><strong>{{ $ind->nama_industri }}</strong></td>
                                    <td class="text-muted">{{ $ind->alamat ?? '-' }}</td>
                                    <td>{{ $ind->kota ?? '-' }}</td>
                                    <td>{{ $ind->no_telp ?? '-' }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-primary font-weight-bold px-2 py-0"
                                                onclick="pilihIndustriAksi('{{ $ind->kode_industri }}', '{{ addslashes($ind->nama_industri) }}')">
                                            Pilih
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
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

<!-- MODAL POPUP PILIH KATEGORI BARANG KHANZA -->
<div class="modal fade" id="modalPilihKategoriKhanza" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 100000;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 8px;">
            <div class="modal-header bg-primary text-white py-2 px-3 align-items-center">
                <h6 class="modal-title font-weight-bold" style="font-size: 13px;">
                    :: [ Data Kategori Barang / Obat ] ::
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0">
                <div class="p-2 border-bottom bg-light">
                    <input type="text" id="filter_kategori_popup" class="khanza-input w-100" placeholder="Cari kategori barang...">
                </div>
                <div style="max-height: 380px; overflow-y: auto;">
                    <table class="table table-sm table-hover table-bordered m-0" style="font-size: 11px;">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th width="85px">Kode</th>
                                <th>Kategori Barang</th>
                                <th width="65px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kategoriKhanza as $kat)
                                <tr class="baris-kat-row" data-nama="{{ strtolower($kat->nama) }}" data-kode="{{ strtolower($kat->kode) }}">
                                    <td class="font-monospace font-weight-bold text-primary">{{ $kat->kode }}</td>
                                    <td><strong>{{ $kat->nama }}</strong></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-primary font-weight-bold px-2 py-0"
                                                onclick="pilihKategoriAksi('{{ $kat->nama }}')">
                                            Pilih
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
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

<!-- MODAL POPUP PILIH GOLONGAN BARANG KHANZA -->
<div class="modal fade" id="modalPilihGolonganKhanza" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 100000;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 8px;">
            <div class="modal-header bg-success text-white py-2 px-3 align-items-center">
                <h6 class="modal-title font-weight-bold" style="font-size: 13px;">
                    :: [ Data Golongan Barang / Obat ] ::
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0">
                <div class="p-2 border-bottom bg-light">
                    <input type="text" id="filter_golongan_popup" class="khanza-input w-100" placeholder="Cari golongan obat...">
                </div>
                <div style="max-height: 380px; overflow-y: auto;">
                    <table class="table table-sm table-hover table-bordered m-0" style="font-size: 11px;">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th width="85px">Kode</th>
                                <th>Golongan Obat</th>
                                <th width="65px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($golonganKhanza as $gol)
                                <tr class="baris-gol-row" data-nama="{{ strtolower($gol->nama) }}" data-kode="{{ strtolower($gol->kode) }}">
                                    <td class="font-monospace font-weight-bold text-success">{{ $gol->kode }}</td>
                                    <td><strong>{{ $gol->nama }}</strong></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-success font-weight-bold px-2 py-0"
                                                onclick="pilihGolonganAksi('{{ $gol->nama }}')">
                                            Pilih
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
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

<!-- MODAL POPUP PILIH SATUAN BARANG KHANZA -->
<div class="modal fade" id="modalPilihSatuanKhanza" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 100000;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 8px;">
            <div class="modal-header bg-info text-white py-2 px-3 align-items-center">
                <h6 class="modal-title font-weight-bold" id="titleModalSatuan" style="font-size: 13px;">
                    :: [ Data Satuan Barang ] ::
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0">
                <div class="p-2 border-bottom bg-light">
                    <input type="text" id="filter_satuan_popup" class="khanza-input w-100" placeholder="Cari satuan barang...">
                </div>
                <div style="max-height: 380px; overflow-y: auto;">
                    <table class="table table-sm table-hover table-bordered m-0" style="font-size: 11px;">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th width="85px">Kode Sat</th>
                                <th>Nama Satuan</th>
                                <th width="65px" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($satuanKhanza as $sat)
                                <tr class="baris-sat-row" data-nama="{{ strtolower($sat->satuan) }}" data-kode="{{ strtolower($sat->kode_sat) }}">
                                    <td class="font-monospace font-weight-bold text-info">{{ $sat->kode_sat }}</td>
                                    <td><strong>{{ $sat->satuan }}</strong></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-info font-weight-bold px-2 py-0"
                                                onclick="pilihSatuanAksi('{{ $sat->satuan }}')">
                                            Pilih
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
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

<script>
    let selectedObat = null;
    let targetJenisSatuan = 'kecil';

    function bukaModalPilihIndustri() {
        $('#modalPilihIndustriFarmasi').appendTo('body').modal('show');
    }

    function pilihIndustriAksi(kd, nm) {
        document.getElementById('input_kode_industri').value = kd;
        document.getElementById('input_nama_industri').value = nm;
        $('#modalPilihIndustriFarmasi').modal('hide');
    }

    function bukaModalPilihKategori() {
        $('#modalPilihKategoriKhanza').appendTo('body').modal('show');
    }

    function pilihKategoriAksi(nm) {
        document.getElementById('input_kategori').value = nm;
        $('#modalPilihKategoriKhanza').modal('hide');
    }

    function bukaModalPilihGolongan() {
        $('#modalPilihGolonganKhanza').appendTo('body').modal('show');
    }

    function pilihGolonganAksi(nm) {
        document.getElementById('input_golongan').value = nm;
        $('#modalPilihGolonganKhanza').modal('hide');
    }

    function bukaModalPilihSatuan(jenis) {
        targetJenisSatuan = jenis;
        if (jenis === 'besar') {
            $('#titleModalSatuan').html('<i class="fas fa-box mr-1"></i> :: [ Data Satuan Besar ] ::');
        } else {
            $('#titleModalSatuan').html('<i class="fas fa-pills mr-1"></i> :: [ Data Satuan Kecil ] ::');
        }
        $('#modalPilihSatuanKhanza').appendTo('body').modal('show');
    }

    function pilihSatuanAksi(nm) {
        if (targetJenisSatuan === 'besar') {
            document.getElementById('input_kode_satbesar').value = nm;
        } else {
            document.getElementById('input_kode_sat').value = nm;
        }
        $('#modalPilihSatuanKhanza').modal('hide');
    }

    // Filter popup kategori
    document.getElementById('filter_kategori_popup').addEventListener('input', function() {
        const val = this.value.toLowerCase().trim();
        document.querySelectorAll('.baris-kat-row').forEach(r => {
            const nama = (r.getAttribute('data-nama') || '');
            const kode = (r.getAttribute('data-kode') || '');
            r.style.display = (nama.includes(val) || kode.includes(val)) ? '' : 'none';
        });
    });

    // Filter popup golongan
    document.getElementById('filter_golongan_popup').addEventListener('input', function() {
        const val = this.value.toLowerCase().trim();
        document.querySelectorAll('.baris-gol-row').forEach(r => {
            const nama = (r.getAttribute('data-nama') || '');
            const kode = (r.getAttribute('data-kode') || '');
            r.style.display = (nama.includes(val) || kode.includes(val)) ? '' : 'none';
        });
    });

    // Filter popup satuan
    document.getElementById('filter_satuan_popup').addEventListener('input', function() {
        const val = this.value.toLowerCase().trim();
        document.querySelectorAll('.baris-sat-row').forEach(r => {
            const nama = (r.getAttribute('data-nama') || '');
            const kode = (r.getAttribute('data-kode') || '');
            r.style.display = (nama.includes(val) || kode.includes(val)) ? '' : 'none';
        });
    });

    // Filter pencarian industri farmasi
    document.getElementById('filter_industri_popup').addEventListener('input', function() {
        const val = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.baris-industri-row');
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

    function pilihBarisObat(tr, ob) {
        document.querySelectorAll('.baris-obat-khanza-row').forEach(r => r.classList.remove('selected-row'));
        tr.classList.add('selected-row');
        selectedObat = ob;

        // Isi form input di atas persis seperti SIMRS Khanza saat baris diklik
        $('#input_is_edit').val('1');
        $('#input_kode_brng').val(ob.kode_brng).attr('readonly', true);
        $('#input_kode_industri').val(ob.kode_industri || '-');
        $('#input_nama_industri').val(ob.nama_industri || ob.kode_industri || '-');
        $('#input_nama_brng').val(ob.nama_brng);
        $('#input_letak_barang').val(ob.letak_barang || '-');
        $('#input_kandungan').val(ob.kandungan || '-');
        $('#input_kode_satbesar').val(ob.kode_satbesar || 'Box');
        $('#input_isi').val(ob.isi || 1);
        $('#input_kode_sat').val(ob.kode_sat || 'Pcs');
        $('#input_kapasitas').val(ob.kapasitas || 1);
        $('#input_kategori').val(ob.kategori || 'Obat Oral');
        $('#input_golongan').val(ob.golongan || 'Obat Bebas');
        $('#input_dasar').val(ob.dasar || ob.h_beli || 0);
        $('#input_h_beli').val(ob.h_beli || 0);
        $('#input_ralan').val(ob.ralan || 0);
        $('#input_kelas1').val(ob.kelas1 || ob.ralan || 0);
        $('#input_kelas2').val(ob.kelas2 || ob.ralan || 0);
        $('#input_kelas3').val(ob.kelas3 || ob.ralan || 0);
        $('#input_utama').val(ob.utama || ob.ralan || 0);
        $('#input_vip').val(ob.vip || ob.ralan || 0);
        $('#input_vvip').val(ob.vvip || ob.ralan || 0);
        $('#input_beliluar').val(ob.beliluar || ob.ralan || 0);
        $('#input_jualbebas').val(ob.jualbebas || ob.ralan || 0);
        $('#input_karyawan').val(ob.karyawan || ob.ralan || 0);
        $('#input_stokminimal').val(ob.stokminimal || 10);
        if (ob.expire) {
            $('#input_expire').val(ob.expire);
        }

        // Action URL update
        $('#formKhanzaObat').attr('action', "{{ url('/master/obat/update') }}/" + encodeURIComponent(ob.kode_brng));
    }

    function formBaruObat() {
        $('#input_is_edit').val('0');
        $('#input_kode_brng').val('{{ $autoKode }}').removeAttr('readonly');
        $('#input_kode_industri').val('-');
        $('#input_nama_industri').val('-');
        $('#input_nama_brng').val('').focus();
        $('#input_letak_barang').val('-');
        $('#input_kandungan').val('-');
        $('#input_dasar').val('0');
        $('#input_h_beli').val('0');
        $('#input_ralan').val('0');
        $('#input_kelas1').val('0');
        $('#input_kelas2').val('0');
        $('#input_kelas3').val('0');
        $('#input_utama').val('0');
        $('#input_vip').val('0');
        $('#input_vvip').val('0');
        $('#input_beliluar').val('0');
        $('#input_jualbebas').val('0');
        $('#input_karyawan').val('0');
        $('#formKhanzaObat').attr('action', "{{ route('master.obat.store') }}");
        document.querySelectorAll('.baris-obat-khanza-row').forEach(r => r.classList.remove('selected-row'));
        selectedObat = null;
    }

    function hapusObatTerpilih() {
        if (!selectedObat) {
            alert('Silakan klik dan pilih data obat pada tabel terlebih dahulu yang mau dihapus.');
            return;
        }
        if (confirm(`Yakin ingin menghapus obat ${selectedObat.nama_brng} (${selectedObat.kode_brng})?`)) {
            window.location.href = "{{ url('/master/obat/delete') }}/" + encodeURIComponent(selectedObat.kode_brng);
        }
    }

    function gantiObatTerpilih() {
        if (!selectedObat) {
            alert('Silakan klik dan pilih data obat pada tabel terlebih dahulu yang mau diganti/diubah.');
            return;
        }
        $('#formKhanzaObat').submit();
    }

    function syncHargaBeli(val) {
        const v = parseFloat(val) || 0;
        if (parseFloat($('#input_h_beli').val()) === 0) {
            $('#input_h_beli').val(v);
        }
    }

    function syncHargaJualLain(ralan) {
        const r = parseFloat(ralan) || 0;
        if (parseFloat($('#input_kelas1').val()) === 0) $('#input_kelas1').val(Math.round(r * 1.1));
        if (parseFloat($('#input_kelas2').val()) === 0) $('#input_kelas2').val(Math.round(r * 1.05));
        if (parseFloat($('#input_kelas3').val()) === 0) $('#input_kelas3').val(r);
        if (parseFloat($('#input_utama').val()) === 0) $('#input_utama').val(Math.round(r * 1.15));
        if (parseFloat($('#input_vip').val()) === 0) $('#input_vip').val(Math.round(r * 1.2));
        if (parseFloat($('#input_vvip').val()) === 0) $('#input_vvip').val(Math.round(r * 1.25));
        if (parseFloat($('#input_beliluar').val()) === 0) $('#input_beliluar').val(Math.round(r * 1.1));
        if (parseFloat($('#input_jualbebas').val()) === 0) $('#input_jualbebas').val(r);
        if (parseFloat($('#input_karyawan').val()) === 0) $('#input_karyawan').val(Math.round(r * 0.9));
    }

    // Quick filter pencarian keyword di baris bawah
    document.getElementById('quick_filter_obat_khanza').addEventListener('input', function() {
        const val = this.value.toLowerCase().trim();
        const rows = document.querySelectorAll('.baris-obat-khanza-row');
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

    document.getElementById('btn_clear_filter_obat').addEventListener('click', function() {
        document.getElementById('quick_filter_obat_khanza').value = '';
        document.querySelectorAll('.baris-obat-khanza-row').forEach(r => r.style.display = '');
    });
</script>
@endsection
