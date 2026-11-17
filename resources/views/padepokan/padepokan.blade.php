@extends('layout.layout')
@section('title', 'Padepokan Cettol')

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
    @if (Session::has('succsessDelete'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-trash-alt mr-2"></i>
            <div>
                <strong>Dihapus!</strong> {{ Session::get('succsessDelete') }}
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- FORM INPUT PESERTA PADEPOKAN -->
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="clinic-header-icon mr-3">
                        <i class="fas fa-clinic-medical"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 font-weight-bold text-dark">Pendaftaran Peserta Padepokan Cettol</h5>
                        <small class="text-muted">Formulir registrasi keanggotaan dan layanan khusus Padepokan Cettol</small>
                    </div>
                </div>
                <div>
                    <span class="badge badge-warning px-3 py-2 text-dark font-weight-bold">
                        <i class="fas fa-users mr-1"></i> Total: {{ count($peserta) }} Peserta
                    </span>
                </div>
            </div>
        </div>

        <form id="form_add_pasien" action="{{ route('addPesertaPadepokan') }}" method="POST">
            @csrf
            <input type="hidden" value="{{ auth()->user()->email }}" name="petugas">

            <div class="card-body">
                <div class="row">
                    <!-- KOLOM 1: DATA DIRI & KEPANGKATAN -->
                    <div class="col-lg-6 pr-lg-4 border-right">
                        <h6 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-user mr-1"></i> Data Identitas & Jenjang
                        </h6>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold small text-muted">Nama Lengkap :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm font-weight-bold" name="nama_lengkap" placeholder="Nama Lengkap Peserta" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold small text-muted">Angkatan :</label>
                            <div class="col-sm-4">
                                <input type="number" name="angkatan" min="1950" max="2099" placeholder="YYYY" required class="form-control form-control-sm" value="{{ date('Y') }}">
                            </div>
                            <label class="col-sm-4 col-form-label small text-muted">Tahun Angkatan</label>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold small text-muted">Jenjang Pangkat :</label>
                            <div class="col-sm-8">
                                <select class="form-control form-control-sm" name="jenjang">
                                    <option value="Lainnya">-- Pilih Jenjang --</option>
                                    <option value="Bintara">Bintara</option>
                                    <option value="Tamtama">Tamtama</option>
                                    <option value="Perwira">Perwira</option>
                                    <option value="Masyarakat Umum">Masyarakat Umum</option>
                                </select>
                            </div>
                        </div>

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
                            <label class="col-sm-4 col-form-label font-weight-bold small text-muted">Tempat & Tgl Lahir :</label>
                            <div class="col-sm-4 mb-2 mb-sm-0">
                                <input type="text" class="form-control form-control-sm" name="tempat_lahir" placeholder="Tempat Lahir">
                            </div>
                            <div class="col-sm-4">
                                <input type="date" class="form-control form-control-sm" name="tgl_lahir">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold small text-muted">Jenis Kelamin :</label>
                            <div class="col-sm-8">
                                <select class="form-control form-control-sm" name="jenis_kelamin">
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold small text-muted">Agama & Gol Darah :</label>
                            <div class="col-sm-4 mb-2 mb-sm-0">
                                <select class="form-control form-control-sm" name="agama">
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Khonghucu">Khonghucu</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control form-control-sm" name="gol_darah" placeholder="Gol: O/A/B/AB">
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM 2: DATA WILAYAH & ALAMAT -->
                    <div class="col-lg-6 pl-lg-4 mt-4 mt-lg-0">
                        <h6 class="font-weight-bold text-primary mb-3">
                            <i class="fas fa-map-marked-alt mr-1"></i> Data Alamat & Domisili
                        </h6>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold small text-muted">Kelurahan :</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" name="kelurahan" placeholder="Kelurahan / Desa">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold small text-muted">Kecamatan :</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" name="kecamatan" placeholder="Kecamatan">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold small text-muted">Kab / Kota :</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" name="kabupaten" placeholder="Kabupaten / Kota">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold small text-muted">Provinsi :</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" name="provinsi" placeholder="Provinsi" value="Lampung">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label font-weight-bold small text-muted">Alamat Rinci :</label>
                            <div class="col-sm-9">
                                <textarea class="form-control form-control-sm" name="alamat_lengkap" rows="3" placeholder="Nama Jalan, No Rumah, RT/RW..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white text-right py-3">
                <button type="button" onclick="document.getElementById('form_add_pasien').reset()" class="btn btn-outline-secondary mr-2 font-weight-bold">
                    <i class="fas fa-undo mr-1"></i> Reset Form
                </button>
                <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                    <i class="fas fa-save mr-1"></i> Simpan Data Peserta
                </button>
            </div>
        </form>
    </div>

    <!-- TABEL DAFTAR PESERTA PADEPOKAN -->
    <div class="card card-outline card-secondary shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="font-weight-bold mb-0 text-dark">
                    <i class="fas fa-list-alt text-info mr-2"></i> Daftar Anggota Peserta Padepokan Cettol
                </h6>
                <small class="text-muted">Data terdaftar di sistem klinik</small>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="zero_config" class="table table-hover table-striped align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th width="40px" class="text-center">No</th>
                            <th>Nama Lengkap</th>
                            <th width="120px">Angkatan</th>
                            <th width="130px">Jenjang</th>
                            <th width="140px">No. Telepon</th>
                            <th width="80px" class="text-center">Gol</th>
                            <th>Alamat Domisili</th>
                            <th width="110px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @forelse ($peserta as $data)
                            <tr>
                                <td class="text-center font-weight-bold text-muted">{{ $no++ }}</td>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $data->nama_lengkap }}</div>
                                    <small class="text-muted">
                                        {{ $data->tempat_lahir ? $data->tempat_lahir.', ' : '' }}{{ $data->tgl_lahir ?? '' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge badge-light border font-weight-bold px-2 py-1">
                                        {{ $data->angkatan ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-info px-2 py-1">
                                        {{ $data->jenjang ?? 'Umum' }}
                                    </span>
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
                                <td class="text-center">
                                    <span class="badge badge-light border font-weight-bold">{{ $data->gol_darah ?? '-' }}</span>
                                </td>
                                <td>
                                    <div class="small text-muted text-truncate" style="max-width: 250px;" title="{{ $data->alamat_lengkap }}">
                                        {{ $data->alamat_lengkap ?? ($data->kelurahan ? $data->kelurahan.', '.$data->kecamatan : '-') }}
                                        @if($data->kabupaten), {{ $data->kabupaten }} @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-xs btn-outline-primary dropdown-toggle font-weight-bold px-2" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Aksi
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right shadow-sm border py-1">
                                            <a class="dropdown-item py-1" href="{{ route('editPeserta', $hashid->encodeHex($data->id)) }}">
                                                <i class="fas fa-id-card text-info mr-2"></i> Rincian & Edit
                                            </a>
                                            @can('role', ['admin'])
                                                <div class="dropdown-divider my-1"></div>
                                                <a class="dropdown-item py-1 text-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data peserta {{ $data->nama_lengkap }}?');" href="{{ route('deletePeserta', $hashid->encodeHex($data->id)) }}">
                                                    <i class="fas fa-trash-alt mr-2"></i> Hapus Peserta
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-users-slash d-block mb-3 text-secondary" style="font-size: 2.5rem; opacity: 0.3;"></i>
                                    <h6 class="font-weight-bold mb-1">Belum Ada Peserta Padepokan</h6>
                                    <p class="small mb-0">Silakan gunakan formulir di atas untuk mendaftarkan peserta baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
