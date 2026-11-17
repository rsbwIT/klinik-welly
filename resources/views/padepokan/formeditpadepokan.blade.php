@extends('layout.layout')
@section('title', 'Detail Peserta - ' . $detailPeserta->nama_lengkap)

@section('konten')
    @if (Session::has('successEditPasien'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            <div>
                <strong>Sukses!</strong> {{ Session::get('successEditPasien') }}
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (Session::has('successEdit'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            <div>
                <strong>Sukses!</strong> {{ Session::get('successEdit') }}
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- PROFIL CARD (KIRI) -->
        <div class="col-lg-4 mb-4">
            <div class="card card-outline card-primary shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-light border d-inline-flex align-items-center justify-content-center text-primary mb-3 shadow-sm" style="width: 110px; height: 110px;">
                        <i class="fas fa-user-shield" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark mb-1">{{ $detailPeserta->nama_lengkap }}</h5>
                    <span class="badge badge-info px-3 py-1 mb-2">{{ $detailPeserta->jenjang ?? 'Umum' }} &bull; Angkatan {{ $detailPeserta->angkatan ?? '-' }}</span>
                    <p class="text-muted small mb-3">
                        <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                        {{ $detailPeserta->kelurahan ? $detailPeserta->kelurahan.', ' : '' }}{{ $detailPeserta->kecamatan ? $detailPeserta->kecamatan.', ' : '' }}{{ $detailPeserta->kabupaten ?? '-' }}
                    </p>

                    @if ($detailPeserta->no_telpon)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $detailPeserta->no_telpon) }}" target="_blank" class="btn btn-sm btn-success btn-block mb-3 font-weight-bold">
                            <i class="fab fa-whatsapp mr-1"></i> Hubungi WhatsApp ({{ $detailPeserta->no_telpon }})
                        </a>
                    @endif

                    <div class="d-flex justify-content-center gap-2 border-top pt-3">
                        <button type="button" class="btn btn-warning text-dark font-weight-bold mr-2 px-3" data-remodal-target="EditPeserta">
                            <i class="fas fa-edit mr-1"></i> Edit Data
                        </button>
                        @can('role', ['admin'])
                            <button type="button" class="btn btn-outline-danger font-weight-bold" data-remodal-target="HapusPeserta">
                                <i class="fas fa-trash-alt mr-1"></i> Hapus
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <!-- DETAIL BIODATA LENGKAP (KANAN) -->
        <div class="col-lg-8 mb-4">
            <div class="card card-outline card-secondary shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <h6 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-id-card text-primary mr-2"></i> Biodata Lengkap Anggota Padepokan
                    </h6>
                    <a href="{{ route('formPadepokan') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <tbody>
                                <tr>
                                    <td width="30%" class="font-weight-bold text-muted">Nama Lengkap</td>
                                    <td width="70%" class="font-weight-bold text-dark">: {{ $detailPeserta->nama_lengkap }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Angkatan</td>
                                    <td>: {{ $detailPeserta->angkatan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Jenjang / Korps</td>
                                    <td>: <span class="badge badge-info">{{ $detailPeserta->jenjang ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Jenis Kelamin</td>
                                    <td>: {{ $detailPeserta->jenis_kelamin ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Tempat & Tanggal Lahir</td>
                                    <td>: {{ $detailPeserta->tempat_lahir ?? '-' }}, {{ $detailPeserta->tgl_lahir ? date('d F Y', strtotime($detailPeserta->tgl_lahir)) : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Golongan Darah</td>
                                    <td>: <span class="badge badge-light border font-weight-bold">{{ $detailPeserta->gol_darah ?? '-' }}</span></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Agama</td>
                                    <td>: {{ $detailPeserta->agama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">No. Telepon / HP</td>
                                    <td>: {{ $detailPeserta->no_telpon ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Wilayah Domisili</td>
                                    <td>: {{ $detailPeserta->kelurahan ? $detailPeserta->kelurahan.', ' : '' }}{{ $detailPeserta->kecamatan ? $detailPeserta->kecamatan.', ' : '' }}{{ $detailPeserta->kabupaten ? $detailPeserta->kabupaten.', ' : '' }}{{ $detailPeserta->provinsi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Alamat Rinci</td>
                                    <td>: {{ $detailPeserta->alamat_lengkap ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- REMODAL POPUP EDIT PESERTA -->
    <div class="remodal" data-remodal-id="EditPeserta" role="dialog" style="max-width: 750px; text-align: left; border-radius: 12px; padding: 25px;">
        <button data-remodal-action="close" class="remodal-close"></button>
        <div class="border-bottom pb-2 mb-3">
            <h5 class="font-weight-bold text-primary mb-0">
                <i class="fas fa-user-edit mr-2"></i> Perbarui Data Peserta Padepokan
            </h5>
        </div>
        <form action="{{ route('editPesertaPadepokan', $detailPeserta->id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="small font-weight-bold">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control form-control-sm" value="{{ $detailPeserta->nama_lengkap }}" required>
                </div>
                <div class="col-md-3 form-group">
                    <label class="small font-weight-bold">Angkatan</label>
                    <input type="number" name="angkatan" class="form-control form-control-sm" value="{{ $detailPeserta->angkatan }}">
                </div>
                <div class="col-md-3 form-group">
                    <label class="small font-weight-bold">Jenjang</label>
                    <select name="jenjang" class="form-control form-control-sm">
                        <option value="Bintara" {{ $detailPeserta->jenjang == 'Bintara' ? 'selected' : '' }}>Bintara</option>
                        <option value="Tamtama" {{ $detailPeserta->jenjang == 'Tamtama' ? 'selected' : '' }}>Tamtama</option>
                        <option value="Perwira" {{ $detailPeserta->jenjang == 'Perwira' ? 'selected' : '' }}>Perwira</option>
                        <option value="Masyarakat Umum" {{ $detailPeserta->jenjang == 'Masyarakat Umum' ? 'selected' : '' }}>Masyarakat Umum</option>
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label class="small font-weight-bold">No. Telepon</label>
                    <input type="text" name="no_telpon" class="form-control form-control-sm" value="{{ $detailPeserta->no_telpon }}">
                </div>
                <div class="col-md-3 form-group">
                    <label class="small font-weight-bold">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control form-control-sm" value="{{ $detailPeserta->tempat_lahir }}">
                </div>
                <div class="col-md-3 form-group">
                    <label class="small font-weight-bold">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" class="form-control form-control-sm" value="{{ $detailPeserta->tgl_lahir }}">
                </div>
                <div class="col-md-4 form-group">
                    <label class="small font-weight-bold">Kelurahan</label>
                    <input type="text" name="kelurahan" class="form-control form-control-sm" value="{{ $detailPeserta->kelurahan }}">
                </div>
                <div class="col-md-4 form-group">
                    <label class="small font-weight-bold">Kecamatan</label>
                    <input type="text" name="kecamatan" class="form-control form-control-sm" value="{{ $detailPeserta->kecamatan }}">
                </div>
                <div class="col-md-4 form-group">
                    <label class="small font-weight-bold">Kabupaten</label>
                    <input type="text" name="kabupaten" class="form-control form-control-sm" value="{{ $detailPeserta->kabupaten }}">
                </div>
                <div class="col-md-12 form-group">
                    <label class="small font-weight-bold">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" class="form-control form-control-sm" rows="2">{{ $detailPeserta->alamat_lengkap }}</textarea>
                </div>
            </div>
            <div class="text-right border-top pt-3">
                <button type="button" data-remodal-action="cancel" class="btn btn-secondary mr-2 font-weight-bold">Batal</button>
                <button type="submit" class="btn btn-primary font-weight-bold">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <!-- REMODAL POPUP HAPUS -->
    <div class="remodal" data-remodal-id="HapusPeserta" role="dialog" style="border-radius: 12px; padding: 25px;">
        <button data-remodal-action="close" class="remodal-close"></button>
        <div class="text-center py-2">
            <i class="fas fa-exclamation-triangle text-danger mb-3" style="font-size: 3rem;"></i>
            <h5 class="font-weight-bold text-dark">Hapus Peserta Padepokan?</h5>
            <p class="text-muted small">Anda yakin ingin menghapus data <strong>{{ $detailPeserta->nama_lengkap }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="mt-4">
                <button data-remodal-action="cancel" class="btn btn-secondary px-3 mr-2 font-weight-bold">Batal</button>
                <a href="{{ route('deletePeserta', $hashid->encodeHex($detailPeserta->id)) }}" class="btn btn-danger px-4 font-weight-bold">
                    Ya, Hapus Data
                </a>
            </div>
        </div>
    </div>
@endsection
