@extends('layout.layout')
@section('title', 'Edit Master Pasien')

@section('konten')
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <!-- Header Navigasi -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="fw-bold mb-1 text-dark"><i class="fas fa-user-edit text-warning me-2"></i>Edit Master Rekam Medis Pasien</h3>
                    <p class="text-muted small mb-0">Perbarui identitas induk pasien {{ $pasien->nm_pasien }}</p>
                </div>
                <a href="{{ route('masterPasien.index') }}" class="btn btn-outline-secondary px-3">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Master Pasien
                </a>
            </div>

            <form action="{{ route('masterPasien.update', $hashid->encodeHex($pasien->id)) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- KOLOM 1: IDENTITAS REKAM MEDIS -->
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100 shadow-sm border-0 rounded-3">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-user-circle me-2"></i>Data Induk Rekam Medis</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-secondary">Nomor RM (Rekam Medis) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-hashtag text-muted"></i></span>
                                            <input type="text" name="no_rkm_medis" value="{{ old('no_rkm_medis', $pasien->no_rkm_medis) }}" class="form-control fw-bold font-monospace" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-secondary">Tanggal Daftar</label>
                                        <input type="date" name="tgl_daftar" value="{{ old('tgl_daftar', $pasien->tgl_daftar) }}" class="form-control">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-secondary">Nama Lengkap Pasien <span class="text-danger">*</span></label>
                                    <input type="text" name="nm_pasien" class="form-control" required value="{{ old('nm_pasien', $pasien->nm_pasien) }}">
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-secondary">Nomor KTP / NIK</label>
                                        <input type="text" name="no_ktp" class="form-control" value="{{ old('no_ktp', $pasien->no_ktp) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-secondary">No. BPJS / Asuransi</label>
                                        <input type="text" name="no_peserta" class="form-control" value="{{ old('no_peserta', $pasien->no_peserta) }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-secondary">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select class="form-select" name="jk" required>
                                            <option value="L" {{ $pasien->jk == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                            <option value="P" {{ $pasien->jk == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-secondary">Golongan Darah</label>
                                        <select class="form-select" name="gol_darah">
                                            @foreach(['-', 'A', 'B', 'AB', 'O'] as $g)
                                                <option value="{{ $g }}" {{ $pasien->gol_darah == $g ? 'selected' : '' }}>{{ $g }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-secondary">Tempat Lahir</label>
                                        <input type="text" name="tmp_lahir" class="form-control" value="{{ old('tmp_lahir', $pasien->tmp_lahir) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-secondary">Tanggal Lahir</label>
                                        <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir', $pasien->tgl_lahir) }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-secondary">Nama Ibu Kandung</label>
                                        <input type="text" name="nm_ibu" class="form-control" value="{{ old('nm_ibu', $pasien->nm_ibu) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-secondary">Status Pernikahan</label>
                                        <select class="form-select" name="stts_nikah">
                                            @foreach(['BELUM MENIKAH', 'MENIKAH', 'JANDA', 'DUDHA'] as $s)
                                                <option value="{{ $s }}" {{ $pasien->stts_nikah == $s ? 'selected' : '' }}>{{ $s }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM 2: KONTAK & ALAMAT -->
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100 shadow-sm border-0 rounded-3">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-map-marked-alt me-2"></i>Kontak & Domisili</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-secondary">No. Telepon / WA</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
                                            <input type="text" name="no_tlp" class="form-control" value="{{ old('no_tlp', $pasien->no_tlp) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-secondary">Agama</label>
                                        <select class="form-select" name="agama">
                                            @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'] as $ag)
                                                <option value="{{ $ag }}" {{ $pasien->agama == $ag ? 'selected' : '' }}>{{ $ag }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-secondary">Pekerjaan</label>
                                    <input type="text" name="pekerjaan" class="form-control" value="{{ old('pekerjaan', $pasien->pekerjaan) }}">
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-secondary">Provinsi</label>
                                        <input type="text" name="provinsi" class="form-control" value="{{ old('provinsi', $pasien->provinsi) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-secondary">Kabupaten / Kota</label>
                                        <input type="text" name="kabupaten" class="form-control" value="{{ old('kabupaten', $pasien->kabupaten) }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-secondary">Kecamatan</label>
                                        <input type="text" name="kecamatan" class="form-control" value="{{ old('kecamatan', $pasien->kecamatan) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold text-secondary">Kelurahan / Desa</label>
                                        <input type="text" name="kelurahan" class="form-control" value="{{ old('kelurahan', $pasien->kelurahan) }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-secondary">Alamat Lengkap</label>
                                    <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $pasien->alamat) }}</textarea>
                                </div>
                            </div>

                            <div class="card-footer bg-white border-top py-3 d-flex justify-content-end gap-2">
                                <a href="{{ route('masterPasien.index') }}" class="btn btn-light px-4 border">Batal</a>
                                <button type="submit" class="btn btn-primary px-4 fw-bold">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

