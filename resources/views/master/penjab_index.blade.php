@extends('layout.layout')
@section('title', 'Master Jenis Bayar & Penjamin')
@section('hide_content_header', 'true')

@section('konten')
<div class="row pt-2">
    <div class="col-12">
        <div class="d-flex justify-content-end align-items-center mb-3">
            <div>
                <button type="button" class="btn btn-warning text-dark px-3 shadow-sm fw-bold" data-toggle="modal" data-target="#modalTambahPenjab">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Penjamin / Asuransi
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="60px" class="text-center">No</th>
                                <th width="120px">Kode PJ</th>
                                <th>Nama Penjamin / Cara Bayar</th>
                                <th>Nama Perusahaan / Keterangan</th>
                                <th class="text-center" width="100px">Status</th>
                                <th class="text-center" width="130px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penjabs as $index => $pj)
                                <tr>
                                    <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                            {{ $pj->kd_pj }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-dark">{{ $pj->png_jawab }}</td>
                                    <td>{{ $pj->nama_perusahaan ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">Aktif</span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-warning me-1" data-toggle="modal" data-target="#modalEditPenjab{{ $pj->id }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="{{ route('master.penjab.destroy', $pj->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data penjamin {{ $pj->png_jawab }}?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal Edit Penjab -->
                                <div class="modal fade" id="modalEditPenjab{{ $pj->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('master.penjab.update', $pj->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Edit Penjamin / Asuransi</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Kode PJ</label>
                                                        <input type="text" class="form-control font-monospace" value="{{ $pj->kd_pj }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Nama Penjamin <span class="text-danger">*</span></label>
                                                        <input type="text" name="png_jawab" class="form-control" value="{{ $pj->png_jawab }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Perusahaan / Keterangan</label>
                                                        <input type="text" name="nama_perusahaan" class="form-control" value="{{ $pj->nama_perusahaan }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-warning text-dark fw-bold">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada penjamin terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Penjab -->
<div class="modal fade" id="modalTambahPenjab" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('master.penjab.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Penjamin / Asuransi</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kode PJ <span class="text-danger">*</span></label>
                        <input type="text" name="kd_pj" class="form-control font-monospace" placeholder="Contoh: PJ006" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Penjamin / Cara Bayar <span class="text-danger">*</span></label>
                        <input type="text" name="png_jawab" class="form-control" placeholder="Contoh: BPJS KETENAGAKERJAAN" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Perusahaan / Keterangan</label>
                        <input type="text" name="nama_perusahaan" class="form-control" placeholder="Contoh: Jaminan Kecelakaan Kerja">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold">Simpan Penjamin</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

