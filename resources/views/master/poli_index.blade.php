@extends('layout.layout')
@section('title', 'Master Poliklinik & Unit')
@section('hide_content_header', 'true')

@section('konten')
<div class="row pt-2">
    <div class="col-12">
        <div class="d-flex justify-content-end align-items-center mb-3">
            <div>
                <button type="button" class="btn btn-success px-3 shadow-sm fw-bold" data-toggle="modal" data-target="#modalTambahPoli">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Unit Poli
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
                                <th width="140px">Kode Unit</th>
                                <th>Nama Poliklinik / Unit Layanan</th>
                                <th class="text-center" width="100px">Status</th>
                                <th class="text-center" width="130px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($polis as $index => $poli)
                                <tr>
                                    <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-light text-success border font-monospace px-2 py-1">
                                            {{ $poli->kd_poli }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-dark">{{ $poli->nm_poli }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">Aktif</span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-warning me-1" data-toggle="modal" data-target="#modalEditPoli{{ $poli->id }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="{{ route('master.poli.destroy', $poli->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus unit poli {{ $poli->nm_poli }}?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal Edit Poli -->
                                <div class="modal fade" id="modalEditPoli{{ $poli->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('master.poli.update', $poli->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Edit Data Poliklinik</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Kode Unit</label>
                                                        <input type="text" class="form-control font-monospace" value="{{ $poli->kd_poli }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Nama Poliklinik / Unit <span class="text-danger">*</span></label>
                                                        <input type="text" name="nm_poli" class="form-control" value="{{ $poli->nm_poli }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success fw-bold">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada unit poliklinik terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Poli -->
<div class="modal fade" id="modalTambahPoli" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('master.poli.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Unit Poliklinik</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kode Unit <span class="text-danger">*</span></label>
                        <input type="text" name="kd_poli" class="form-control font-monospace" placeholder="Contoh: U003" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Poliklinik / Unit Layanan <span class="text-danger">*</span></label>
                        <input type="text" name="nm_poli" class="form-control" placeholder="Contoh: Poli Fisioterapi" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-bold">Simpan Poli</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

