@extends('layout.layout')
@section('title', 'Manajemen Petugas & Pengguna')

@section('konten')
    @if (Session::has('succsessAddUser'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            <div>
                <strong>Sukses!</strong> {{ Session::get('succsessAddUser') }}
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (Session::has('editSuccess'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            <div>
                <strong>Sukses!</strong> {{ Session::get('editSuccess') }}
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (Session::has('succsessDeleteUser'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-trash-alt mr-2"></i>
            <div>
                <strong>Dihapus!</strong> {{ Session::get('succsessDeleteUser') }}
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- FORM TAMBAH PETUGAS BARU -->
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="clinic-header-icon mr-3">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 font-weight-bold text-dark">Manajemen Akun Petugas & Tenaga Medis</h5>
                        <small class="text-muted">Kelola akun akses login perawat, dokter, dan admin sistem klinik</small>
                    </div>
                </div>
                <div>
                    <span class="badge badge-primary px-3 py-2 font-weight-bold">
                        <i class="fas fa-user-shield mr-1"></i> Total: {{ count($user) }} Akun Terdaftar
                    </span>
                </div>
            </div>
        </div>

        <form id="form_add_user" action="{{ route('addUserKlinik') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <!-- KOLOM 1: INFORMASI AKUN & LOGIN -->
                    <div class="col-lg-6 pr-lg-4 border-right">
                        <h6 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-key mr-1"></i> Kredensial Akun & Peran
                        </h6>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold small text-muted">Nama Lengkap :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm font-weight-bold" name="name" placeholder="Nama & Gelar Petugas" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold small text-muted">Email / Username :</label>
                            <div class="col-sm-8">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                                    </div>
                                    <input type="email" class="form-control" name="email" placeholder="email@klinik.com" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold small text-muted">Password Akun :</label>
                            <div class="col-sm-8">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                                    </div>
                                    <input type="password" id="inputPassword" class="form-control" name="password" placeholder="Minimal 6 karakter" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('inputPassword', 'iconTogglePassword')" title="Lihat/Sembunyikan Password">
                                            <i id="iconTogglePassword" class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold small text-muted">Hak Akses (Role) :</label>
                            <div class="col-sm-8">
                                <select class="form-control form-control-sm font-weight-bold" name="role">
                                    <option value="petugas">Petugas / Tenaga Medis</option>
                                    <option value="admin">Administrator Sistem</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM 2: KONTAK & ALAMAT -->
                    <div class="col-lg-6 pl-lg-4 mt-4 mt-lg-0">
                        <h6 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-address-card mr-1"></i> Kontak & Informasi Pribadi
                        </h6>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold small text-muted">No. Telepon / WA :</label>
                            <div class="col-sm-8">
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone text-muted"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="no_telpon" placeholder="Contoh: 08123456789">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold small text-muted">Alamat Domisili :</label>
                            <div class="col-sm-8">
                                <textarea class="form-control form-control-sm" name="alamat" rows="4" placeholder="Alamat lengkap tempat tinggal petugas..."></textarea>
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
                </div>
            </div>

            <div class="card-footer bg-white text-right py-3">
                <button type="button" onclick="document.getElementById('form_add_user').reset()" class="btn btn-outline-secondary mr-2 font-weight-bold">
                    <i class="fas fa-undo mr-1"></i> Reset
                </button>
                <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                    <i class="fas fa-user-plus mr-1"></i> Daftarkan Petugas Baru
                </button>
            </div>
        </form>
    </div>

    <!-- TABEL DAFTAR PENGGUNA / PETUGAS -->
    <div class="card card-outline card-secondary shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="font-weight-bold mb-0 text-dark">
                    <i class="fas fa-list-ul text-info mr-2"></i> Daftar Akun Petugas & Tenaga Medis
                </h6>
                <small class="text-muted">Akses resmi login aplikasi</small>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="zero_config" class="table table-hover table-striped align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th width="40px" class="text-center">No</th>
                            <th>Nama Petugas</th>
                            <th width="200px">Username / Email</th>
                            <th width="140px">Password Akun</th>
                            <th width="120px">Role Akses</th>
                            <th width="130px">No. Telepon</th>
                            <th>Alamat Domisili</th>
                            <th width="100px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($user as $data)
                            <tr>
                                <td class="text-center font-weight-bold text-muted">{{ $no++ }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center mr-2 text-primary" style="width: 32px; height: 32px;">
                                            <i class="fas fa-user-md"></i>
                                        </div>
                                        <div class="font-weight-bold text-dark">{{ $data->name ?: 'Administrator' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small font-weight-bold text-dark">{{ $data->email }}</div>
                                </td>
                                <td>
                                    @if ($data->raw_password)
                                        <div class="d-flex align-items-center">
                                            <span id="passText_{{ $data->id }}" class="font-monospace small font-weight-bold text-dark" style="letter-spacing: 1px;">••••••••</span>
                                            <button type="button" class="btn btn-xs btn-link text-muted ml-1 p-0" onclick="toggleTablePassword('{{ $data->id }}', '{{ $data->raw_password }}')" title="Lihat/Tutup Password">
                                                <i id="passIcon_{{ $data->id }}" class="fas fa-eye text-primary"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="badge badge-light border text-muted small" title="Password lama dienkripsi standar bcrypt">Terenkripsi</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($data->role == 'admin')
                                        <span class="badge badge-success px-2 py-1 text-uppercase font-weight-bold">
                                            <i class="fas fa-shield-alt mr-1"></i> Admin
                                        </span>
                                    @else
                                        <span class="badge badge-info px-2 py-1 text-uppercase font-weight-bold">
                                            <i class="fas fa-user-nurse mr-1"></i> Petugas
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($data->no_telpon)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $data->no_telpon) }}" target="_blank" class="text-success small font-weight-bold text-decoration-none">
                                            <i class="fab fa-whatsapp mr-1"></i>{{ $data->no_telpon }}
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small text-muted text-truncate" style="max-width: 220px;" title="{{ $data->alamat }}">
                                        {{ $data->alamat ?? '-' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('tracker.permissions', $hashid->encodeHex($data->id)) }}" class="btn btn-outline-info" title="Kelola Hak Akses">
                                            <i class="fas fa-user-shield"></i>
                                        </a>
                                        <a href="{{ route('editPetugas', $hashid->encodeHex($data->id)) }}" class="btn btn-outline-warning" title="Ubah Data Petugas">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(auth()->user()->id != $data->id)
                                            <a href="{{ route('hapuPetugas', $hashid->encodeHex($data->id)) }}" onclick="return confirm('Apakah Anda yakin ingin menghapus akun {{ $data->name }}?');" class="btn btn-outline-danger" title="Hapus Akun">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function toggleTablePassword(id, rawVal) {
            var span = document.getElementById('passText_' + id);
            var icon = document.getElementById('passIcon_' + id);
            if (!span) return;

            if (span.textContent === '••••••••') {
                span.textContent = rawVal;
                span.style.letterSpacing = 'normal';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            } else {
                span.textContent = '••••••••';
                span.style.letterSpacing = '1px';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        }
    </script>
@endsection
