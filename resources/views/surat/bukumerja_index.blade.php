@extends('layout.layout')
@section('title', 'Buku Merja Klien (Perjalanan & Evaluasi Rawat Luka)')
@section('hide_content_header', 'true')

@section('konten')
<div class="container-fluid px-2 pt-2 pb-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-2 shadow-2xs" role="alert" style="font-size: 12px;">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close p-2" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- FORM INPUT BUKU MERJA KLIEN -->
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 6px;">
        <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="font-monospace fw-bold text-primary" style="font-size: 13px;">
                <i class="fas fa-book-medical mr-1"></i> :: [ BUKU MERJA KLIEN / KARTU KONTROL PERAWATAN LUKA ] ::
            </span>
            <div class="d-flex gap-2">
                <a href="{{ route('surat.index') }}" class="btn btn-xs btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Menu Persuratan
                </a>
            </div>
        </div>
        <div class="card-body p-3 bg-white">
            @if(isset($selectedPasien) && $selectedPasien)
                <div class="alert alert-info py-2 px-3 mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-user-check mr-1"></i> Pasien Terpilih dari Rawat Jalan: 
                        <strong>{{ $selectedPasien->nama_lengkap }}</strong> 
                        (No. Rawat: <span class="font-monospace fw-bold">{{ $selectedPasien->no_rawat }}</span>, 
                        No. RM: <span class="font-monospace fw-bold">{{ $selectedPasien->no_rkm_medis }}</span>)
                    </div>
                    <a href="{{ route('surat.bukumerja.index') }}" class="btn btn-xs btn-outline-secondary">Reset / Buat Baru</a>
                </div>
            @endif

            <form action="{{ route('surat.bukumerja.store') }}" method="POST">
                @csrf
                <input type="hidden" name="pasien_id" value="{{ $selectedPasien->id ?? '' }}">

                <!-- 1. IDENTITAS KLIEN -->
                <div class="p-2 mb-2 rounded bg-light border">
                    <span class="small font-weight-bold text-dark text-uppercase d-block mb-2">1. Identitas Klien & Periode Perawatan</span>
                    <div class="row g-2 mb-2">
                        <div class="col-md-3">
                            <label class="small text-secondary mb-0">Nomor Rawat :</label>
                            <input type="text" name="no_rawat" class="form-control form-control-sm font-monospace" value="{{ $selectedPasien->no_rawat ?? '' }}" placeholder="00001/11/11/2026">
                        </div>
                        <div class="col-md-3">
                            <label class="small text-secondary mb-0">Nomor RM :</label>
                            <input type="text" name="no_rkm_medis" class="form-control form-control-sm font-monospace" value="{{ $selectedPasien->no_rkm_medis ?? '' }}" placeholder="000001">
                        </div>
                        <div class="col-md-6">
                            <label class="small text-secondary mb-0">Nama Klien :</label>
                            <input type="text" name="nama_klien" class="form-control form-control-sm fw-bold" value="{{ $selectedPasien->nama_lengkap ?? '' }}" placeholder="Nama Pasien" required>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="small text-secondary mb-0">Tgl. Mulai Perawatan :</label>
                            <input type="date" name="tgl_mulai_rawat" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-3">
                            <label class="small text-secondary mb-0">Tgl. Estimasi Selesai :</label>
                            <input type="date" name="tgl_selesai_rawat" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="small text-secondary mb-0">Status Perawatan :</label>
                            <select name="status_perawatan" class="form-select form-select-sm font-weight-bold">
                                <option value="Aktif / Masih Rawat" selected>Aktif / Masih Rawat</option>
                                <option value="Sembuh / Selesai">Sembuh / Selesai</option>
                                <option value="Rujuk">Dirujuk ke RS</option>
                                <option value="Meninggal">Meninggal Dunia</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="small text-secondary mb-0">Diagnosa Medis :</label>
                            <input type="text" name="diagnosa_medis" class="form-control form-control-sm" placeholder="Contoh: Ulkus Diabetikum Pedis Dextra">
                        </div>
                    </div>
                </div>

                <!-- 2. KARAKTERISTIK LUKA & TARGET ASUHAN -->
                <div class="p-2 mb-3 rounded bg-light border">
                    <span class="small font-weight-bold text-dark text-uppercase d-block mb-2">2. Karakteristik Luka, Target & Evaluasi</span>
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="small text-secondary mb-0">Jenis / Etiologi Luka :</label>
                            <input type="text" name="jenis_luka" class="form-control form-control-sm" placeholder="Contoh: Luka Diabetes, Luka Bakar, Luka Pasca Operasi, Dekubitus">
                        </div>
                        <div class="col-md-6">
                            <label class="small text-secondary mb-0">Derajat / Stadium Luka :</label>
                            <input type="text" name="derajat_luka" class="form-control form-control-sm" placeholder="Contoh: Stadium 2, Wagner Grade 3, Luas 5x4 cm kedalaman 1 cm">
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="small text-secondary mb-0">Tujuan & Target Perawatan Luka (Goals) :</label>
                            <textarea name="tujuan_target_perawatan" rows="2" class="form-control form-control-sm" placeholder="Target autolytic debridement, manajemen eksudat, pertumbuhan jaringan granulasi..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-secondary mb-0">Evaluasi Hasil Perawatan :</label>
                            <textarea name="evaluasi_akhir" rows="2" class="form-control form-control-sm" placeholder="Epitelisasi tepi luka maju, luka menutup sempurna, bebas infeksi..."></textarea>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-12">
                            <label class="small text-secondary mb-0">Catatan Khusus Klien / Kepatuhan Diet :</label>
                            <textarea name="catatan" rows="2" class="form-control form-control-sm" placeholder="Pendidikan kesehatan, pemakaian alas kaki khusus, kontrol gula darah rutin..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-sm btn-primary px-4 fw-bold shadow-sm">
                        <i class="fas fa-save mr-1"></i> Simpan Buku Merja Klien
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL REKAP BUKU MERJA -->
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 6px;">
        <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="font-weight-bold text-dark" style="font-size: 13px;">
                <i class="fas fa-list-alt text-primary mr-1"></i> ARSIP BUKU MERJA / KARTU KONTROL KLIEN
            </span>
            <form method="GET" class="d-flex align-items-center">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm mr-1" placeholder="Cari Nama Klien / No. Rawat..." style="width: 220px;">
                <button type="submit" class="btn btn-xs btn-primary px-2"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0 text-nowrap" style="font-size: 11.5px;">
                    <thead class="bg-light">
                        <tr>
                            <th>Mulai Rawat</th>
                            <th>No. Rawat</th>
                            <th>Nama Klien</th>
                            <th>Diagnosa & Jenis Luka</th>
                            <th>Target / Tujuan Perawatan</th>
                            <th>Status Rawat</th>
                            <th width="90px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($row->tgl_mulai_rawat)) }}</td>
                                <td class="font-monospace text-primary fw-bold">{{ $row->no_rawat ?: '-' }}</td>
                                <td class="fw-bold text-dark">{{ $row->nama_klien }}</td>
                                <td><strong>{{ $row->jenis_luka ?: '-' }}</strong><br><small class="text-muted">{{ $row->diagnosa_medis }} ({{ $row->derajat_luka }})</small></td>
                                <td><span class="d-inline-block text-truncate" style="max-width: 220px;" title="{{ $row->tujuan_target_perawatan }}">{{ $row->tujuan_target_perawatan ?: '-' }}</span></td>
                                <td>
                                    @if($row->status_perawatan == 'Sembuh / Selesai')
                                        <span class="badge bg-success">Sembuh / Selesai</span>
                                    @elseif($row->status_perawatan == 'Rujuk')
                                        <span class="badge bg-warning text-dark">Dirujuk</span>
                                    @else
                                        <span class="badge bg-primary">Aktif / Masih Rawat</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('surat.bukumerja.cetak', $row->id) }}" target="_blank" class="btn btn-xs btn-outline-primary px-2 mr-1" title="Cetak Kartu Merja">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                    <a href="{{ route('surat.bukumerja.destroy', $row->id) }}" onclick="return confirm('Hapus berkas buku merja ini?')" class="btn btn-xs btn-outline-secondary px-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada catatan buku merja klien.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light py-2 px-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">Total Data: {{ $data->total() }} catatan klien</small>
            {{ $data->links() }}
        </div>
    </div>
</div>
@endsection
