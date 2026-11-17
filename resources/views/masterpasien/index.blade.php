@extends('layout.layout')
@section('title', 'Master Pasien (Rekam Medis)')

@section('konten')
    @if (Session::has('createSuccess'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            <div>
                <strong>Sukses!</strong> {{ Session::get('createSuccess') }}
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (Session::has('deleteSuccess'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-trash-alt mr-2"></i>
            <div>
                <strong>Dihapus!</strong> {{ Session::get('deleteSuccess') }}
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Card Header Master Pasien -->
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="d-flex align-items-center mb-2 mb-md-0">
                    <div class="clinic-header-icon mr-3">
                        <i class="fas fa-address-book"></i>
                    </div>
                    <div>
                        <h5 class="font-weight-bold mb-0 text-dark">Master Pasien / Rekam Medis (RM)</h5>
                        <small class="text-muted">Database induk pasien klinik (Total: <strong>{{ number_format($totalPasien) }}</strong> pasien terdaftar)</small>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <a href="{{ route('masterPasien.create') }}" class="btn btn-primary font-weight-bold mr-2 shadow-sm">
                        <i class="fas fa-plus-circle mr-1"></i> RM Baru
                    </a>
                    <a href="{{ route('registrasi.pasien') }}" class="btn btn-outline-success font-weight-bold">
                        <i class="fas fa-user-check mr-1"></i> Registrasi Pasien
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body bg-light py-2 border-bottom">
            <!-- Form Pencarian Master Pasien -->
            <form action="{{ route('masterPasien.index') }}" method="GET" class="row align-items-center m-0">
                <div class="col-md-7 pl-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" name="keyword" class="form-control" placeholder="Cari No. RM, Nama Pasien, NIK, No. Telepon..." value="{{ request('keyword') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary font-weight-bold px-3">Cari Pasien</button>
                            @if (request('keyword'))
                                <a href="{{ route('masterPasien.index') }}" class="btn btn-outline-secondary">Reset</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-5 text-md-right text-muted small mt-2 mt-md-0 pr-0">
                    <i class="fas fa-keyboard mr-1 text-primary"></i> Tekan <strong>Enter</strong> untuk mencari pasien
                </div>
            </form>
        </div>

        <!-- Tabel Master Pasien -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th width="40px" class="text-center">No</th>
                            <th width="110px">No. RM</th>
                            <th>Nama Pasien</th>
                            <th class="text-center" width="70px">L/P</th>
                            <th>Tgl Lahir / Umur</th>
                            <th>Kontak Pasien</th>
                            <th>Alamat Domisili</th>
                            <th>Tgl Daftar</th>
                            <th class="text-center" width="130px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($masterPasien as $index => $p)
                            <tr>
                                <td class="text-center text-muted font-weight-bold">{{ $masterPasien->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge badge-light border font-monospace text-success font-weight-bold px-2 py-1" style="font-size: 13px;">
                                        {{ $p->no_rkm_medis }}
                                    </span>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $p->nm_pasien }}</div>
                                    <small class="text-muted">
                                        @if($p->no_ktp) NIK: {{ $p->no_ktp }} &bull; @endif
                                        @if($p->nm_ibu) Ibu: {{ $p->nm_ibu }} &bull; @endif
                                        Gol: <span class="badge badge-light border font-weight-bold">{{ $p->gol_darah ?? '-' }}</span>
                                    </small>
                                </td>
                                <td class="text-center">
                                    @if ($p->jk == 'L')
                                        <span class="badge badge-primary px-2">L</span>
                                    @else
                                        <span class="badge badge-danger px-2">P</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small font-weight-bold text-dark">{{ $p->tgl_lahir ? date('d/m/Y', strtotime($p->tgl_lahir)) : '-' }}</div>
                                    <small class="text-muted">{{ $p->tmp_lahir ?? '' }}</small>
                                </td>
                                <td>
                                    @if ($p->no_tlp)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->no_tlp) }}" target="_blank" class="text-decoration-none text-success small font-weight-bold">
                                            <i class="fab fa-whatsapp mr-1"></i>{{ $p->no_tlp }}
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small text-dark text-truncate" style="max-width: 200px;" title="{{ $p->alamat }}">
                                        {{ $p->alamat ?? '-' }}
                                    </div>
                                    <small class="text-muted">{{ $p->kabupaten ?? '' }}</small>
                                </td>
                                <td>
                                    <small class="text-muted font-weight-bold">{{ $p->tgl_daftar ? date('d/m/Y', strtotime($p->tgl_daftar)) : '-' }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('masterPasien.cetakKartu', $hashid->encodeHex($p->id)) }}" target="_blank" class="btn btn-outline-info" title="Cetak Kartu Berobat (Barcode RM)">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <a href="{{ route('masterPasien.edit', $hashid->encodeHex($p->id)) }}" class="btn btn-outline-warning" title="Edit Master Pasien">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @can('role', ['admin'])
                                            <form action="{{ route('masterPasien.destroy', $hashid->encodeHex($p->id)) }}" method="POST" onsubmit="return confirm('Hapus data master pasien {{ $p->nm_pasien }}?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Hapus Master Pasien">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fas fa-user-slash d-block mb-3 text-secondary" style="font-size: 2.5rem; opacity: 0.3;"></i>
                                    <h6 class="font-weight-bold mb-1">Data Pasien Tidak Ditemukan</h6>
                                    <p class="small mb-3">Tidak ada data master pasien yang cocok dengan kata kunci pencarian.</p>
                                    <a href="{{ route('masterPasien.create') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus-circle mr-1"></i> Buat RM Baru
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center p-3 flex-wrap border-top">
                <small class="text-muted">Menampilkan <strong>{{ $masterPasien->firstItem() ?? 0 }}</strong> - <strong>{{ $masterPasien->lastItem() ?? 0 }}</strong> dari <strong>{{ number_format($masterPasien->total()) }}</strong> data pasien</small>
                <div>
                    {{ $masterPasien->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection
