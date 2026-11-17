@extends('layout.layout')
@section('title', 'Ubah Data Petugas - ' . $user->name)

@section('konten')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-outline card-warning shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="clinic-header-icon mr-3 bg-warning text-dark">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 font-weight-bold text-dark">Ubah Data Petugas</h5>
                                <small class="text-muted">Perbarui data profil atau password akun: <strong>{{ $user->name }}</strong></small>
                            </div>
                        </div>
                        <a href="{{ route('addUser') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>

                <form id="form_edit_user" action="{{ route('editUserKlinik', $hashid->encodeHex($user->id)) }}" method="POST">
                    @csrf
                    <div class="card-body p-4">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold small text-muted">Nama Lengkap :</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm font-weight-bold" name="name" value="{{ $user->name }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold small text-muted">Email / Username :</label>
                            <div class="col-sm-9">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                                    </div>
                                    <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold small text-muted">Hak Akses (Role) :</label>
                            <div class="col-sm-9">
                                <select class="form-control form-control-sm font-weight-bold" name="role">
                                    <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas / Tenaga Medis</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator Sistem</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold small text-muted">No. Telepon / WA :</label>
                            <div class="col-sm-9">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="no_telpon" value="{{ $user->no_telpon }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold small text-muted">Password Baru :</label>
                            <div class="col-sm-9">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                                    </div>
                                    <input type="password" id="inputEditPassword" class="form-control" name="password" placeholder="Wajib masukkan password (baru / konfirmasi)" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('inputEditPassword', 'iconToggleEditPass')" title="Lihat/Sembunyikan Password">
                                            <i id="iconToggleEditPass" class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="text-danger mt-1 d-block"><i class="fas fa-info-circle mr-1"></i> Sistem mewajibkan input password baru setiap memperbarui data petugas.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold small text-muted">Alamat Domisili :</label>
                            <div class="col-sm-9">
                                <textarea class="form-control form-control-sm" name="alamat" rows="3">{{ $user->alamat }}</textarea>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger py-2 small mb-0">
                                <ul class="mb-0 pl-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="card-footer bg-white text-right py-3">
                        <a href="{{ route('addUser') }}" class="btn btn-secondary mr-2 font-weight-bold">Batal</a>
                        <button type="submit" class="btn btn-warning px-4 font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
