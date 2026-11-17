@extends('layout.layout')

@section('title', 'Master Tarif & Tindakan Medis')

@section('konten')
<div class="container-fluid pt-2">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm py-2 mb-3" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="font-weight-bold mb-1 text-dark">
                <i class="fas fa-hand-holding-medical text-primary mr-2"></i> Master Tarif Tindakan Medis
            </h4>
            <p class="text-muted small mb-0">Daftar tarif resmi pelayanan, rawat luka, dan tindakan medis klinik</p>
        </div>
        <button type="button" class="btn btn-primary font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalTambahTindakan" style="border-radius: 8px;">
            <i class="fas fa-plus mr-1"></i> Tambah Tindakan Baru
        </button>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 14px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                    <thead class="thead-light">
                        <tr>
                            <th width="50" class="text-center">#</th>
                            <th>Kode Tindakan</th>
                            <th>Nama Tindakan / Prosedur</th>
                            <th>Kategori</th>
                            <th class="text-right">Tarif Pasien (Rp)</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tindakan as $td)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="font-monospace font-weight-bold text-primary">{{ $td->kd_jenis_prw }}</td>
                                <td class="font-weight-bold text-dark">{{ $td->nm_perawatan }}</td>
                                <td><span class="badge badge-light border">{{ $td->kategori }}</span></td>
                                <td class="text-right font-monospace font-weight-bold text-success" style="font-size: 13.5px;">
                                    Rp {{ number_format($td->total_byr, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-success px-2 py-1">Aktif</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada master tindakan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Tindakan -->
<div class="modal fade" id="modalTambahTindakan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-plus mr-1"></i> Tambah Master Tindakan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('master.tindakan.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small font-weight-bold text-muted mb-1">Kode Tindakan :</label>
                        <input type="text" name="kd_jenis_prw" class="form-control font-monospace font-weight-bold text-uppercase" placeholder="Cth: TK011" required>
                    </div>
                    <div class="mb-3">
                        <label class="small font-weight-bold text-muted mb-1">Nama Tindakan Medis :</label>
                        <input type="text" name="nm_perawatan" class="form-control" placeholder="Cth: Jahit Luka 1-3 Simpul" required>
                    </div>
                    <div class="mb-3">
                        <label class="small font-weight-bold text-muted mb-1">Kategori :</label>
                        <select name="kategori" class="form-control">
                            <option value="Rawat Luka">Rawat Luka Modern</option>
                            <option value="Tindakan Medis">Tindakan Medis</option>
                            <option value="Tindakan Bedah Minor">Tindakan Bedah Minor</option>
                            <option value="Konsultasi">Konsultasi Dokter</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="small font-weight-bold text-muted mb-1">Total Biaya / Tarif (Rp) :</label>
                        <input type="number" step="1000" name="total_byr" class="form-control font-monospace font-weight-bold" placeholder="50000" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Simpan Tindakan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
