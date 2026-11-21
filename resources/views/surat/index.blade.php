@extends('layout.layout')
@section('title', 'Modul Persuratan & Dokumentasi Klinis')
@section('hide_content_header', 'true')

@section('konten')
<div class="container-fluid px-2 pt-2 pb-4">
    <!-- HEADER MODUL SURAT -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 p-3 bg-white border rounded shadow-sm">
        <div class="d-flex align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px; background: rgba(2, 132, 199, 0.1); color: #0284c7; font-size: 22px;">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <div>
                <h5 class="font-weight-bold mb-0 text-dark">Modul Persuratan & Administrasi Medis</h5>
                <small class="text-muted">Praktik Mandiri Keperawatan Welly &bull; Kelola Surat Rujukan, Ekspedisi, Askep, dan Pelimpahan Wewenang</small>
            </div>
        </div>
        <div class="d-flex gap-2 mt-2 mt-md-0 flex-wrap">
            <a href="{{ route('surat.rujukan.index') }}" class="btn btn-sm btn-danger font-weight-bold shadow-2xs mr-1 mb-1">
                <i class="fas fa-paper-plane mr-1"></i> Buat Surat Rujukan
            </a>
            <a href="{{ route('surat.askep.index') }}" class="btn btn-sm btn-info font-weight-bold shadow-2xs mr-1 mb-1">
                <i class="fas fa-file-medical-alt mr-1"></i> Form Askep
            </a>
            <a href="{{ route('surat.observasi.index') }}" class="btn btn-sm btn-primary font-weight-bold shadow-2xs mr-1 mb-1">
                <i class="fas fa-notes-medical mr-1"></i> Observasi Khusus
            </a>
            <a href="{{ route('surat.bukumerja.index') }}" class="btn btn-sm btn-warning font-weight-bold shadow-2xs mr-1 mb-1 text-white">
                <i class="fas fa-book mr-1"></i> Buku Kerja Klien
            </a>
            <a href="{{ route('surat.puskesmas.index') }}" class="btn btn-sm btn-success font-weight-bold shadow-2xs mr-1 mb-1">
                <i class="fas fa-hospital mr-1"></i> Laporan Puskesmas
            </a>
            <a href="{{ route('surat.pelimpahan.index') }}" class="btn btn-sm btn-secondary font-weight-bold shadow-2xs mr-1 mb-1">
                <i class="fas fa-file-signature mr-1"></i> Pelimpahan
            </a>
            <a href="{{ route('surat.ekspedisi.index') }}" class="btn btn-sm btn-dark font-weight-bold shadow-2xs mb-1">
                <i class="fas fa-book-reader mr-1"></i> Ekspedisi
            </a>
        </div>
    </div>

    <!-- STATISTIK KARTU SURAT -->
    <div class="row mb-3">
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Lembar Observasi Khusus</span>
                        <h3 class="font-weight-bold text-primary mb-0 mt-1">{{ $totalObservasi }}</h3>
                        <small class="text-muted">Monitoring TTV & Cairan Infus</small>
                    </div>
                    <div class="p-3 rounded-circle bg-primary bg-opacity-10 text-primary" style="font-size: 20px;">
                        <i class="fas fa-notes-medical"></i>
                    </div>
                </div>
                <hr class="my-2">
                <a href="{{ route('surat.observasi.index') }}" class="small text-primary font-weight-bold text-decoration-none">
                    Buka Observasi Khusus <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Buku Kerja Klien</span>
                        <h3 class="font-weight-bold text-warning mb-0 mt-1">{{ $totalBukuMerja }}</h3>
                        <small class="text-muted">Rekam Jejak Evaluasi Klien Luka</small>
                    </div>
                    <div class="p-3 rounded-circle bg-warning bg-opacity-10 text-warning" style="font-size: 20px;">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
                <hr class="my-2">
                <a href="{{ route('surat.bukumerja.index') }}" class="small text-warning font-weight-bold text-decoration-none">
                    Buka Buku Kerja <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Laporan Puskesmas</span>
                        <h3 class="font-weight-bold text-success mb-0 mt-1">{{ $totalPuskesmas }}</h3>
                        <small class="text-muted">Rekap Bulanan Kunjungan & Kasus</small>
                    </div>
                    <div class="p-3 rounded-circle bg-success bg-opacity-10 text-success" style="font-size: 20px;">
                        <i class="fas fa-hospital"></i>
                    </div>
                </div>
                <hr class="my-2">
                <a href="{{ route('surat.puskesmas.index') }}" class="small text-success font-weight-bold text-decoration-none">
                    Kelola Laporan Puskesmas <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Formulir Askep</span>
                        <h3 class="font-weight-bold text-info mb-0 mt-1">{{ $totalAskep }}</h3>
                        <small class="text-muted">Asuhan Keperawatan Terpadu</small>
                    </div>
                    <div class="p-3 rounded-circle bg-info bg-opacity-10 text-info" style="font-size: 20px;">
                        <i class="fas fa-file-medical-alt"></i>
                    </div>
                </div>
                <hr class="my-2">
                <a href="{{ route('surat.askep.index') }}" class="small text-info font-weight-bold text-decoration-none">
                    Kelola Form Askep <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Surat Rujukan Pasien</span>
                        <h3 class="font-weight-bold text-danger mb-0 mt-1">{{ $totalRujukan }}</h3>
                        <small class="text-muted">Rujukan RS / PKM</small>
                    </div>
                    <div class="p-3 rounded-circle bg-danger bg-opacity-10 text-danger" style="font-size: 20px;">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                </div>
                <hr class="my-2">
                <a href="{{ route('surat.rujukan.index') }}" class="small text-danger font-weight-bold text-decoration-none">
                    Kelola Rujukan <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Pelimpahan Wewenang</span>
                        <h3 class="font-weight-bold text-warning mb-0 mt-1">{{ $totalPelimpahan }}</h3>
                        <small class="text-muted">Mandat Medis Dokter</small>
                    </div>
                    <div class="p-3 rounded-circle bg-warning bg-opacity-10 text-warning" style="font-size: 20px;">
                        <i class="fas fa-file-signature"></i>
                    </div>
                </div>
                <hr class="my-2">
                <a href="{{ route('surat.pelimpahan.index') }}" class="small text-warning font-weight-bold text-decoration-none">
                    Kelola Pelimpahan <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Buku Ekspedisi Surat</span>
                        <h3 class="font-weight-bold text-success mb-0 mt-1">{{ $totalEkspedisi }}</h3>
                        <small class="text-muted">Tanda Terima Surat Keluar</small>
                    </div>
                    <div class="p-3 rounded-circle bg-success bg-opacity-10 text-success" style="font-size: 20px;">
                        <i class="fas fa-book-reader"></i>
                    </div>
                </div>
                <hr class="my-2">
                <a href="{{ route('surat.ekspedisi.index') }}" class="small text-success font-weight-bold text-decoration-none">
                    Buka Buku Ekspedisi <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 4 PANEL AKSES CEPAT PERSURATAN -->
    <div class="row">
        <!-- 1. SURAT RUJUKAN TERAKHIR -->
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 8px;">
                <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold text-dark" style="font-size: 13px;">
                        <i class="fas fa-paper-plane text-danger mr-1"></i> 5 Surat Rujukan Terkini
                    </span>
                    <a href="{{ route('surat.rujukan.index') }}" class="btn btn-xs btn-outline-danger">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0" style="font-size: 12px;">
                            <thead class="bg-light">
                                <tr>
                                    <th>No. Surat</th>
                                    <th>Pasien</th>
                                    <th>Tujuan Faskes</th>
                                    <th>Tanggal</th>
                                    <th class="text-center">Cetak</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestRujukan as $r)
                                    <tr>
                                        <td class="font-monospace text-primary fw-bold">{{ $r->no_surat }}</td>
                                        <td class="fw-bold">{{ $r->nama_pasien }}</td>
                                        <td>{{ $r->tujuan_faskes }}</td>
                                        <td>{{ $r->tgl_surat }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('surat.rujukan.cetak', $r->id) }}" target="_blank" class="btn btn-xs btn-outline-danger py-0 px-2" title="Cetak Surat">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-3 text-muted">Belum ada surat rujukan dibuat.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. BUKU EKSPEDISI TERAKHIR -->
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 8px;">
                <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold text-dark" style="font-size: 13px;">
                        <i class="fas fa-book-reader text-success mr-1"></i> Buku Ekspedisi Terkini
                    </span>
                    <a href="{{ route('surat.ekspedisi.index') }}" class="btn btn-xs btn-outline-success">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0" style="font-size: 12px;">
                            <thead class="bg-light">
                                <tr>
                                    <th>Hari/Tgl</th>
                                    <th>Penerima</th>
                                    <th>Instansi</th>
                                    <th>Maksud/Tujuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestEkspedisi as $e)
                                    <tr>
                                        <td>{{ $e->tanggal }}</td>
                                        <td class="fw-bold">{{ $e->nama_penerima }}</td>
                                        <td>{{ $e->instansi }}</td>
                                        <td><span class="d-inline-block text-truncate" style="max-width: 180px;">{{ $e->maksud_tujuan }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-3 text-muted">Belum ada catatan ekspedisi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

