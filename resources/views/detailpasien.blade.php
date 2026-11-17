@extends('layout.layout')
@section('title', 'Detail Biodata Pasien')

@section('konten')
    @if (Session::has('successEditPasien'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center mb-4" role="alert">
            <i class="fas fa-check-circle fs-4 me-3 text-success"></i>
            <div class="flex-grow-1">
                <strong>Sukses!</strong> {{ Session::get('successEditPasien') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Navigasi -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-id-card text-primary me-2"></i>Biodata Lengkap Pasien</h4>
            <small class="text-muted">Informasi identitas demografi dan riwayat kunjungan pasien</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('soapPasienDetail', $hashid->encodeHex($detailPasien->id)) }}" class="btn btn-outline-primary px-3 shadow-sm">
                <i class="fas fa-stethoscope me-1"></i> Riwayat SOAP
            </a>
            <a href="{{ url('/') }}" class="btn btn-outline-secondary px-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Antrean
            </a>
        </div>
    </div>

    <div class="row">
        <!-- PROFIL CARD KIRI -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm text-center p-4 h-100">
                <div class="card-body">
                    <div class="rounded-circle bg-light border p-1 mx-auto mb-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                        <img src="{{ asset('img/klinik.jpeg') }}" alt="Logo" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <span class="badge bg-light text-dark border font-monospace px-3 py-1 mb-2">{{ $detailPasien->no_rawat }}</span>
                    <h5 class="fw-bold text-dark mb-1">{{ $detailPasien->nama_lengkap }}</h5>
                    <p class="text-muted small mb-3">
                        <i class="fas fa-map-marker-alt me-1 text-danger"></i>{{ $detailPasien->kabupaten ?? '-' }}, {{ $detailPasien->provinsi ?? '' }}
                    </p>

                    <div class="d-flex justify-content-center gap-2 mb-3">
                        @if ($detailPasien->no_telpon)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $detailPasien->no_telpon) }}" target="_blank" class="btn btn-sm btn-success px-3">
                                <i class="fab fa-whatsapp me-1"></i> Hubungi WA
                            </a>
                        @endif
                        <button type="button" class="btn btn-sm btn-warning text-dark px-3" data-remodal-target="EditPasien">
                            <i class="fas fa-edit me-1"></i> Edit Data
                        </button>
                    </div>

                    @can('role', ['admin'])
                        <button type="button" class="btn btn-sm btn-outline-danger px-3 w-100" data-remodal-target="HapusPasien">
                            <i class="fas fa-trash-alt me-1"></i> Hapus Pasien Ini
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <!-- DETAIL TABEL KANAN -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-user-circle text-primary me-2"></i>Rincian Identitas Kependudukan</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row py-2 border-bottom">
                        <div class="col-sm-4 text-muted small fw-bold text-uppercase">Nama Lengkap</div>
                        <div class="col-sm-8 fw-semibold text-dark">{{ $detailPasien->nama_lengkap }}</div>
                    </div>
                    <div class="row py-2 border-bottom">
                        <div class="col-sm-4 text-muted small fw-bold text-uppercase">Nomor Rawat / Kunjungan</div>
                        <div class="col-sm-8 font-monospace fw-bold text-primary">{{ $detailPasien->no_rawat }}</div>
                    </div>
                    <div class="row py-2 border-bottom">
                        <div class="col-sm-4 text-muted small fw-bold text-uppercase">Jenis Kelamin / Gol. Darah</div>
                        <div class="col-sm-8 text-dark">
                            {{ $detailPasien->jenis_kelamin ?? '-' }} (Gol. Darah: <strong>{{ $detailPasien->gol_darah ?? '-' }}</strong>)
                        </div>
                    </div>
                    <div class="row py-2 border-bottom">
                        <div class="col-sm-4 text-muted small fw-bold text-uppercase">Tempat, Tanggal Lahir</div>
                        <div class="col-sm-8 text-dark">{{ $detailPasien->tempat_lahir ?? '-' }}, {{ $detailPasien->tgl_lahir ?? '-' }}</div>
                    </div>
                    <div class="row py-2 border-bottom">
                        <div class="col-sm-4 text-muted small fw-bold text-uppercase">Agama</div>
                        <div class="col-sm-8 text-dark">{{ $detailPasien->agama ?? '-' }}</div>
                    </div>
                    <div class="row py-2 border-bottom">
                        <div class="col-sm-4 text-muted small fw-bold text-uppercase">Nama Ibu Kandung</div>
                        <div class="col-sm-8 text-dark">{{ $detailPasien->nama_ibu ?? '-' }}</div>
                    </div>
                    <div class="row py-2 border-bottom">
                        <div class="col-sm-4 text-muted small fw-bold text-uppercase">Nomor Telepon / Kontak</div>
                        <div class="col-sm-8 text-dark">{{ $detailPasien->no_telpon ?? '-' }}</div>
                    </div>
                    <div class="row py-2 border-bottom">
                        <div class="col-sm-4 text-muted small fw-bold text-uppercase">Domisili Wilayah</div>
                        <div class="col-sm-8 text-dark">
                            Kel. {{ $detailPasien->kelurahan ?? '-' }}, Kec. {{ $detailPasien->kecamatan ?? '-' }}, {{ $detailPasien->kabupaten ?? '-' }}, Prov. {{ $detailPasien->provinsi ?? '-' }}
                        </div>
                    </div>
                    <div class="row py-2">
                        <div class="col-sm-4 text-muted small fw-bold text-uppercase">Alamat Lengkap</div>
                        <div class="col-sm-8 text-dark">{{ $detailPasien->alamat_lengkap ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- REMODAL EDIT PASIEN -->
    <div class="remodal" data-remodal-id="EditPasien" role="dialog" aria-labelledby="modal1Title">
        <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
        <div>
            <h4 class="fw-bold mb-3">Edit Biodata Pasien</h4>
            <form action="{{ route('editPasien', $hashid->encodeHex($detailPasien->id)) }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $hashid->encodeHex($detailPasien->id) }}">
                <div class="row g-2 text-start">
                    <div class="col-12 mb-2">
                        <label class="form-label small fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="{{ $detailPasien->nama_lengkap }}" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">No. Telepon</label>
                        <input type="text" name="no_telpon" class="form-control" value="{{ $detailPasien->no_telpon }}">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Golongan Darah</label>
                        <input type="text" name="gol_darah" class="form-control" value="{{ $detailPasien->gol_darah }}">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" value="{{ $detailPasien->tempat_lahir }}">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" class="form-control" value="{{ $detailPasien->tgl_lahir }}">
                    </div>
                    <div class="col-12 mb-2">
                        <label class="form-label small fw-bold">Alamat Lengkap</label>
                        <textarea name="alamat_lengkap" class="form-control" rows="2">{{ $detailPasien->alamat_lengkap }}</textarea>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button data-remodal-action="cancel" class="btn btn-secondary px-3">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- REMODAL HAPUS PASIEN -->
    <div class="remodal" data-remodal-id="HapusPasien" role="dialog" aria-labelledby="modal1Title">
        <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
        <div class="p-2">
            <i class="fas fa-exclamation-triangle text-danger fs-1 mb-3"></i>
            <h4 class="fw-bold">Konfirmasi Hapus</h4>
            <p class="text-muted">Apakah Anda yakin ingin menghapus data pasien <strong>{{ $detailPasien->nama_lengkap }}</strong>? Semua data SOAP yang terhubung juga akan ikut terhapus.</p>
            <div class="d-flex justify-content-center gap-2 mt-4">
                <button data-remodal-action="cancel" class="btn btn-light border px-4">Batal</button>
                <a href="{{ route('deletePasien', $hashid->encodeHex($detailPasien->id)) }}" class="btn btn-danger px-4 fw-bold">Ya, Hapus</a>
            </div>
        </div>
    </div>
@endsection
