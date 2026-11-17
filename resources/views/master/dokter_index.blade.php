@extends('layout.layout')
@section('title', 'Master Data Dokter')
@section('hide_content_header', 'true')

@section('konten')
<div class="row pt-2">
    <div class="col-12">
        <div class="d-flex justify-content-end align-items-center mb-3">
            <div>
                <button type="button" class="btn btn-primary px-3 shadow-sm fw-bold" data-toggle="modal" data-target="#modalTambahDokter">
                    <i class="fas fa-plus-circle me-1"></i> Tambah Dokter Baru
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
                                <th width="140px">Kode Dokter</th>
                                <th>Nama Lengkap & Gelar</th>
                                <th class="text-center" width="80px">L/P</th>
                                <th>Spesialisasi / Keahlian</th>
                                <th>No. Telepon / WA</th>
                                <th class="text-center" width="100px">Status</th>
                                <th class="text-center" width="130px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dokters as $index => $doc)
                                <tr>
                                    <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-light text-primary border font-monospace px-2 py-1">
                                            {{ $doc->kd_dokter }}
                                        </span>
                                    </td>
                                    <td class="fw-bold text-dark">{{ $doc->nm_dokter }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $doc->jk == 'L' ? 'bg-primary' : 'bg-danger' }}">
                                            {{ $doc->jk }}
                                        </span>
                                    </td>
                                    <td>{{ $doc->spesialis ?? '-' }}</td>
                                    <td>{{ $doc->no_telp ?? '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">Aktif</span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-warning me-1" data-toggle="modal" data-target="#modalEditDokter{{ $doc->id }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="{{ route('master.dokter.destroy', $doc->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus dokter {{ $doc->nm_dokter }}?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal Edit Dokter -->
                                <div class="modal fade" id="modalEditDokter{{ $doc->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('master.dokter.update', $doc->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Edit Data Dokter</h5>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Kode Dokter</label>
                                                        <input type="text" class="form-control font-monospace" value="{{ $doc->kd_dokter }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Nama Dokter <span class="text-danger">*</span></label>
                                                        <input type="text" name="nm_dokter" class="form-control" value="{{ $doc->nm_dokter }}" required>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <div class="col-6">
                                                            <label class="form-label small fw-bold">Jenis Kelamin</label>
                                                            <select name="jk" class="form-control">
                                                                <option value="L" {{ $doc->jk == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                                <option value="P" {{ $doc->jk == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label small fw-bold">No. Telp</label>
                                                            <input type="text" name="no_telp" class="form-control" value="{{ $doc->no_telp }}">
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Spesialisasi / Keahlian</label>
                                                        <input type="text" name="spesialis" class="form-control" value="{{ $doc->spesialis }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary fw-bold">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">Belum ada data dokter terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Dokter -->
<div class="modal fade" id="modalTambahDokter" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('master.dokter.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Dokter Baru</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kode Dokter <span class="text-danger">*</span></label>
                        <input type="text" name="kd_dokter" class="form-control font-monospace" placeholder="Contoh: D0000004" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Dokter & Gelar <span class="text-danger">*</span></label>
                        <input type="text" name="nm_dokter" class="form-control" placeholder="Contoh: dr. Welly" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Jenis Kelamin</label>
                            <select name="jk" class="form-control">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">No. Telp</label>
                            <input type="text" name="no_telp" class="form-control" placeholder="0812...">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Spesialisasi / Keahlian</label>
                        <input type="text" name="spesialis" class="form-control" placeholder="Contoh: Dokter Umum / Bedah / dll">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">Simpan Dokter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

