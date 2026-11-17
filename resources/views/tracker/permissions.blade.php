@extends('layout.layout')
@section('title', 'Hak Akses Pengguna - ' . $user->name)

@section('konten')
    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            <div>
                <strong>Sukses!</strong> {{ Session::get('success') }}
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card card-outline card-primary shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="clinic-header-icon mr-3">
                                <i class="fas fa-user-lock"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 font-weight-bold text-dark">Pengaturan Hak Akses Pengguna</h5>
                                <small class="text-muted">Tentukan menu dan fitur yang diizinkan untuk: <strong>{{ $user->name }}</strong> ({{ $user->email }})</small>
                            </div>
                        </div>
                        <a href="{{ route('addUser') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar User
                        </a>
                    </div>
                </div>

                <form action="{{ route('tracker.permissions.update', $hashId) }}" method="POST">
                    @csrf
                    <div class="card-body p-4">
                        <div class="alert alert-info py-2 small mb-4">
                            <i class="fas fa-info-circle mr-1"></i> <strong>Perhatian:</strong> Akun dengan role <strong>Admin</strong> memiliki izin penuh ke seluruh modul sistem secara otomatis. Centang opsi di bawah ini untuk mengatur batas akses bagi petugas.
                        </div>

                        <h6 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-bars mr-1"></i> Izin Akses Menu & Modul Utama
                        </h6>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="sw_master" name="menu_master_pasien" value="1" {{ $permissions->menu_master_pasien ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold text-dark" for="sw_master">
                                        Modul Master Pasien (No. RM)
                                    </label>
                                    <small class="form-text text-muted">Melihat dan menambah data master rekam medis tetap pasien</small>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="sw_reg" name="menu_registrasi" value="1" {{ $permissions->menu_registrasi ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold text-dark" for="sw_reg">
                                        Modul Registrasi & Kunjungan Rawat Jalan
                                    </label>
                                    <small class="form-text text-muted">Input pendaftaran pasien berobat harian di meja pelayanan</small>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="sw_soap" name="menu_soap" value="1" {{ $permissions->menu_soap ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold text-dark" for="sw_soap">
                                        Modul Pemeriksaan SOAP Medis
                                    </label>
                                    <small class="form-text text-muted">Input dan melihat catatan asuhan keperawatan / SOAP pasien</small>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="sw_lab" name="menu_lab" value="1" {{ $permissions->menu_lab ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold text-dark" for="sw_lab">
                                        Modul Laboratorium & Hasil Pemeriksaan
                                    </label>
                                    <small class="form-text text-muted">Formulir permintaan lab dan pengisian hasil template tindakan</small>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="sw_padepokan" name="menu_padepokan" value="1" {{ $permissions->menu_padepokan ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold text-dark" for="sw_padepokan">
                                        Modul Padepokan Cettol
                                    </label>
                                    <small class="form-text text-muted">Akses pendaftaran dan daftar peserta layanan Padepokan Cettol</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="font-weight-bold text-danger mb-3">
                            <i class="fas fa-shield-alt mr-1"></i> Izin Operasional Kritis & Hak Khusus
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="sw_hapus_pasien" name="hapus_pasien" value="1" {{ $permissions->hapus_pasien ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold text-danger" for="sw_hapus_pasien">
                                        Dapat Menghapus Data Kunjungan Pasien
                                    </label>
                                    <small class="form-text text-muted">Menghapus baris registrasi kunjungan pasien di halaman utama</small>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="sw_hapus_soap" name="hapus_soap" value="1" {{ $permissions->hapus_soap ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold text-danger" for="sw_hapus_soap">
                                        Dapat Menghapus Catatan SOAP
                                    </label>
                                    <small class="form-text text-muted">Menghapus dokumentasi rekam medis yang telah diinput</small>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="sw_kelola_user" name="kelola_user" value="1" {{ $permissions->kelola_user ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold text-dark" for="sw_kelola_user">
                                        Dapat Menambah & Mengubah Akun Petugas
                                    </label>
                                    <small class="form-text text-muted">Membuka menu Manajemen Petugas</small>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="sw_lihat_tracker" name="lihat_tracker" value="1" {{ $permissions->lihat_tracker ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold text-dark" for="sw_lihat_tracker">
                                        Dapat Melihat Log Tracker Aktivitas
                                    </label>
                                    <small class="form-text text-muted">Membuka catatan jejak audit trail seluruh petugas</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white text-right py-3">
                        <a href="{{ route('addUser') }}" class="btn btn-secondary mr-2 font-weight-bold">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Hak Akses
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

