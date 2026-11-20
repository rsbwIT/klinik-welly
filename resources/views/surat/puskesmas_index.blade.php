@extends('layout.layout')
@section('title', 'Form Pelaporan Pelayanan ke Puskesmas')
@section('hide_content_header', 'true')

@section('konten')
<div class="container-fluid px-2 pt-2 pb-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-2 shadow-2xs" role="alert" style="font-size: 12px;">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close p-2" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- FORM INPUT PELAPORAN PUSKESMAS -->
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 6px;">
        <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="font-monospace fw-bold text-success" style="font-size: 13px;">
                <i class="fas fa-clinic-medical mr-1"></i> :: [ FORM PELAPORAN DATA PELAYANAN KE PUSKESMAS ] ::
            </span>
            <div class="d-flex gap-2">
                <a href="{{ route('surat.index') }}" class="btn btn-xs btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Menu Persuratan
                </a>
            </div>
        </div>
        <div class="card-body p-3 bg-white">
            <form action="{{ route('surat.puskesmas.store') }}" method="POST">
                @csrf
                <div class="p-2 mb-2 rounded bg-light border">
                    <span class="small font-weight-bold text-dark text-uppercase d-block mb-2">1. Informasi Periode & Tujuan Laporan</span>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="small text-secondary mb-0">Nomor Laporan :</label>
                            <input type="text" name="no_laporan" value="{{ $defaultNoLaporan }}" class="form-control form-control-sm font-monospace fw-bold" required>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-secondary mb-0">Nama Puskesmas Pembina / Wilayah :</label>
                            <input type="text" name="nama_puskesmas" class="form-control form-control-sm fw-bold" placeholder="Contoh: Puskesmas Simpur / Gedong Air" required>
                        </div>
                        <div class="col-md-3">
                            <label class="small text-secondary mb-0">Periode Laporan (Bulan/Tahun) :</label>
                            <input type="text" name="bulan_tahun" value="{{ date('F Y') }}" class="form-control form-control-sm" placeholder="Contoh: November 2026">
                        </div>
                        <div class="col-md-2">
                            <label class="small text-secondary mb-0">Tanggal Kirim Lapor :</label>
                            <input type="date" name="tanggal_lapor" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" required>
                        </div>
                    </div>
                </div>

                <div class="p-2 mb-2 rounded bg-light border">
                    <span class="small font-weight-bold text-dark text-uppercase d-block mb-2">2. Rekapitulasi Pasien & Kunjungan (Bulan Ini)</span>
                    <div class="row g-2 mb-2">
                        <div class="col-md-4">
                            <label class="small text-secondary mb-0">Pasien Baru :</label>
                            <input type="number" name="total_pasien_baru" value="{{ $autoBaru }}" class="form-control form-control-sm font-weight-bold">
                        </div>
                        <div class="col-md-4">
                            <label class="small text-secondary mb-0">Pasien Lama :</label>
                            <input type="number" name="total_pasien_lama" value="{{ $autoLama }}" class="form-control form-control-sm font-weight-bold">
                        </div>
                        <div class="col-md-4">
                            <label class="small text-secondary mb-0">Total Seluruh Kunjungan :</label>
                            <input type="number" name="total_kunjungan" value="{{ $autoTotal }}" class="form-control form-control-sm font-weight-bold text-success">
                        </div>
                    </div>
                </div>

                <div class="p-2 mb-3 rounded bg-light border">
                    <span class="small font-weight-bold text-dark text-uppercase d-block mb-2">3. Distribusi Kasus Penyakit & Tindakan</span>
                    <div class="row g-2 mb-2">
                        <div class="col-md-2">
                            <label class="small text-secondary mb-0">DM & Rawat Luka :</label>
                            <input type="number" name="kasus_dm_luka" value="0" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label class="small text-secondary mb-0">Hipertensi :</label>
                            <input type="number" name="kasus_hipertensi" value="0" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label class="small text-secondary mb-0">Penyakit Infeksi :</label>
                            <input type="number" name="kasus_infeksi" value="0" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label class="small text-secondary mb-0">Bedah Minor :</label>
                            <input type="number" name="kasus_bedah_minor" value="0" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label class="small text-secondary mb-0">Kasus Lainnya :</label>
                            <input type="number" name="kasus_lainnya" value="0" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label class="small text-secondary mb-0">Pasien Dirujuk :</label>
                            <input type="number" name="pasien_rujuk" value="0" class="form-control form-control-sm text-danger font-weight-bold">
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-12">
                            <label class="small text-secondary mb-0">Keterangan / Catatan Evaluasi Puskesmas :</label>
                            <textarea name="keterangan" rows="2" class="form-control form-control-sm" placeholder="Catatan kegiatan penyuluhan, kendala, atau asuhan keperawatan komunitas..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-sm btn-success px-4 fw-bold shadow-sm">
                        <i class="fas fa-paper-plane mr-1"></i> Simpan & Siapkan Laporan Puskesmas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL REKAP PELAPORAN PUSKESMAS -->
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 6px;">
        <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="font-weight-bold text-dark" style="font-size: 13px;">
                <i class="fas fa-archive text-success mr-1"></i> ARSIP PELAPORAN KE PUSKESMAS
            </span>
            <form method="GET" class="d-flex align-items-center">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm mr-1" placeholder="Cari No. Laporan / Puskesmas..." style="width: 220px;">
                <button type="submit" class="btn btn-xs btn-primary px-2"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0 text-nowrap" style="font-size: 11.5px;">
                    <thead class="bg-light">
                        <tr>
                            <th>Tgl Lapor</th>
                            <th>No. Laporan</th>
                            <th>Puskesmas Pembina</th>
                            <th>Periode</th>
                            <th class="text-center">Total Kunjungan (Baru/Lama)</th>
                            <th class="text-center">DM & Luka</th>
                            <th class="text-center">Rujukan</th>
                            <th width="90px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($row->tanggal_lapor)) }}</td>
                                <td class="font-monospace text-primary fw-bold">{{ $row->no_laporan }}</td>
                                <td class="fw-bold text-dark">{{ $row->nama_puskesmas }}</td>
                                <td><span class="badge bg-light border text-dark">{{ $row->bulan_tahun }}</span></td>
                                <td class="text-center font-weight-bold">{{ $row->total_kunjungan }} <small class="text-muted">({{ $row->total_pasien_baru }}/{{ $row->total_pasien_lama }})</small></td>
                                <td class="text-center">{{ $row->kasus_dm_luka }}</td>
                                <td class="text-center text-danger font-weight-bold">{{ $row->pasien_rujuk }}</td>
                                <td class="text-center">
                                    <a href="{{ route('surat.puskesmas.cetak', $row->id) }}" target="_blank" class="btn btn-xs btn-outline-success px-2 mr-1" title="Cetak Lembar Laporan">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                    <a href="{{ route('surat.puskesmas.destroy', $row->id) }}" onclick="return confirm('Hapus berkas laporan ini?')" class="btn btn-xs btn-outline-secondary px-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada arsip pelaporan puskesmas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light py-2 px-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">Total Data: {{ $data->total() }} laporan</small>
            {{ $data->links() }}
        </div>
    </div>
</div>
@endsection
