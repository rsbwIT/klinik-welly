@extends('layout.layout')
@section('title', 'RM Baru - Data Pasien')
@section('hide_content_header', 'true')

@section('konten')
<div class="row">
    <div class="col-12">
        @if (Session::has('createSuccess'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm py-2 mb-2" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ Session::get('createSuccess') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
        @if (Session::has('deleteSuccess'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm py-2 mb-2" role="alert">
                <i class="fas fa-trash-alt mr-1"></i> {{ Session::get('deleteSuccess') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <!-- PANEL UTAMA GAYA SIMRS KHANZA (TAB INPUT PASIEN & DATA PASIEN) -->
        <div class="card shadow-sm border-0 mb-3" style="border-radius: 8px;">
            <!-- HEADER TABS KHANZA -->
            <div class="card-header bg-white p-2 border-bottom d-flex justify-content-between align-items-center">
                <span class="font-monospace fw-bold text-dark px-2" style="font-size: 13px;">:: [ Data Pasien ] ::</span>

                <!-- Nav Tabs Khanza -->
                <ul class="nav nav-pills" id="khanzaPasienTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link px-3 py-1 font-weight-bold active" id="tab-data-pasien" data-toggle="tab" href="#content-data-pasien" role="tab">
                            Data Pasien
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 py-1 font-weight-bold" id="tab-input-pasien" data-toggle="tab" href="#content-input-pasien" role="tab">
                            Input Pasien
                        </a>
                    </li>
                </ul>
            </div>

            <div class="tab-content" id="khanzaPasienTabContent">
                <!-- ================= TAB 1: DATA PASIEN (TABEL KHANZA) ================= -->
                <div class="tab-pane fade show active" id="content-data-pasien" role="tabpanel">
                    <div class="table-responsive" style="max-height: 520px; overflow-y: auto; overflow-x: auto; min-height: 380px;">
                        <table class="table table-bordered table-hover table-striped mb-0 text-nowrap align-middle" style="font-size: 11.5px;">
                            <thead class="bg-light sticky-top" style="border-bottom: 2px solid #dee2e6;">
                                <tr class="text-secondary">
                                    <th width="30px" class="text-center">P</th>
                                    <th width="85px">No.R.M</th>
                                    <th>Nama Pasien</th>
                                    <th>No.SIM/KTP</th>
                                    <th width="40px" class="text-center">J.K.</th>
                                    <th>Tmp.Lahir</th>
                                    <th>Tgl.Lahir</th>
                                    <th>Nama Ibu</th>
                                    <th>Alamat</th>
                                    <th width="40px" class="text-center">G.D.</th>
                                    <th>Pekerjaan</th>
                                    <th>Stts.Nikah</th>
                                    <th>Agama</th>
                                    <th>Tgl.Daftar</th>
                                    <th>No.Telp/HP</th>
                                    <th width="60px" class="text-center">Umur</th>
                                    <th>Pendidikan</th>
                                    <th>Penanggung ...</th>
                                    <th>Nama Penanggung Jawab</th>
                                    <th>Asuransi/Askes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($listPasien as $p)
                                    @php
                                        $umur = '-';
                                        if ($p->tgl_lahir) {
                                            $birthDate = new \DateTime($p->tgl_lahir);
                                            $today = new \DateTime();
                                            $diff = $today->diff($birthDate);
                                            $umur = $diff->y . ' Th';
                                        }
                                    @endphp
                                    <tr class="row-select-pasien" data-id="{{ $p->id }}" data-rm="{{ $p->no_rkm_medis }}" data-nama="{{ $p->nm_pasien }}" style="cursor: pointer;">
                                        <td class="text-center">
                                            <input type="radio" name="selected_pasien_id" value="{{ $p->id }}">
                                        </td>
                                        <td class="font-monospace fw-bold text-success">{{ $p->no_rkm_medis }}</td>
                                        <td class="fw-bold text-dark">{{ $p->nm_pasien }}</td>
                                        <td>{{ $p->no_ktp ?? '-' }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $p->jk == 'L' ? 'bg-primary' : 'bg-danger' }} px-2">{{ $p->jk }}</span>
                                        </td>
                                        <td>{{ $p->tmp_lahir ?? '-' }}</td>
                                        <td>{{ $p->tgl_lahir ? date('d-m-Y', strtotime($p->tgl_lahir)) : '-' }}</td>
                                        <td>{{ $p->nm_ibu ?? '-' }}</td>
                                        <td>
                                            <span class="d-inline-block text-truncate" style="max-width: 250px;" title="{{ $p->alamat }}">
                                                {{ $p->alamat ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center fw-bold">{{ $p->gol_darah ?? '-' }}</td>
                                        <td>{{ $p->pekerjaan ?? '-' }}</td>
                                        <td>{{ $p->stts_nikah ?? 'BELUM MENIKAH' }}</td>
                                        <td>{{ $p->agama ?? 'Islam' }}</td>
                                        <td>{{ $p->tgl_daftar ? date('d-m-Y', strtotime($p->tgl_daftar)) : '-' }}</td>
                                        <td>
                                            @if($p->no_tlp)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->no_tlp) }}" target="_blank" class="text-success fw-bold text-decoration-none">
                                                    {{ $p->no_tlp }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center fw-bold">{{ $umur }}</td>
                                        <td>{{ $p->pnd ?? '-' }}</td>
                                        <td>{{ $p->keluarga ?? '-' }}</td>
                                        <td>{{ $p->namakeluarga ?? '-' }}</td>
                                        <td>{{ $p->kd_pj ?? ($p->no_peserta ? 'BPJS: '.$p->no_peserta : 'UMUM') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="20" class="text-center py-5 text-muted">
                                            <i class="fas fa-user-slash fa-2x mb-2 d-block opacity-50"></i>
                                            Tidak ada data pasien yang ditampilkan. Silakan klik tombol <strong>[ Baru ]</strong> atau <strong>[ Input Pasien ]</strong> untuk menambah data.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ================= TAB 2: INPUT PASIEN ================= -->
                <div class="tab-pane fade" id="content-input-pasien" role="tabpanel">
                    <form id="form_input_pasien" action="{{ route('masterPasien.store') }}" method="POST" class="p-3 bg-white">
                        @csrf
                        
                        <!-- BARIS 1: No. Rekam Medis, Nama Pasien, No. KTP / NIK -->
                        <div class="row g-2 mb-2 align-items-center">
                            <div class="col-md-4">
                                <div class="row g-1 align-items-center">
                                    <div class="col-sm-5 text-sm-end">
                                        <label class="small text-secondary fw-semibold mb-0">No. Rekam Medis :</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="no_rkm_medis" id="in_no_rkm_medis" value="{{ $no_rkm_medis }}" class="form-control form-control-sm font-monospace fw-bold text-success" style="border-radius: 12px;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row g-1 align-items-center">
                                    <div class="col-sm-4 text-sm-end">
                                        <label class="small text-secondary fw-semibold mb-0">Nama Pasien :</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="nm_pasien" id="in_nm_pasien" class="form-control form-control-sm" placeholder="Nama lengkap pasien" style="border-radius: 12px;" required autofocus>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row g-1 align-items-center">
                                    <div class="col-sm-4 text-sm-end">
                                        <label class="small text-secondary fw-semibold mb-0">No. KTP / NIK :</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="no_ktp" id="in_no_ktp" class="form-control form-control-sm font-monospace" placeholder="16 digit NIK" style="border-radius: 12px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BARIS 2: J.K., Tempat Lahir, Tgl Lahir, Nama Ibu -->
                        <div class="row g-2 mb-2 align-items-center">
                            <div class="col-md-4">
                                <div class="row g-1 align-items-center">
                                    <div class="col-sm-5 text-sm-end">
                                        <label class="small text-secondary fw-semibold mb-0">Jenis Kelamin :</label>
                                    </div>
                                    <div class="col-sm-7 d-flex align-items-center gap-1">
                                        <select name="jk" id="in_jk" class="form-select form-select-sm" style="border-radius: 12px;" required>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                        <span class="small text-muted px-1">G.D:</span>
                                        <select name="gol_darah" id="in_gol_darah" class="form-select form-select-sm" style="max-width: 75px; border-radius: 12px;">
                                            <option value="-">-</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="AB">AB</option>
                                            <option value="O">O</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row g-1 align-items-center">
                                    <div class="col-sm-4 text-sm-end">
                                        <label class="small text-secondary fw-semibold mb-0">Tempat Lahir :</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="tmp_lahir" id="in_tmp_lahir" class="form-control form-control-sm" placeholder="Kota lahir" style="border-radius: 12px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row g-1 align-items-center">
                                    <div class="col-sm-4 text-sm-end">
                                        <label class="small text-secondary fw-semibold mb-0">Tgl. Lahir :</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="date" name="tgl_lahir" id="in_tgl_lahir" class="form-control form-control-sm" style="border-radius: 12px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BARIS 3: Nama Ibu, Agama, Status Nikah -->
                        <div class="row g-2 mb-2 align-items-center">
                            <div class="col-md-4">
                                <div class="row g-1 align-items-center">
                                    <div class="col-sm-5 text-sm-end">
                                        <label class="small text-secondary fw-semibold mb-0">Nama Ibu :</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="nm_ibu" id="in_nm_ibu" class="form-control form-control-sm" placeholder="Nama ibu kandung" style="border-radius: 12px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row g-1 align-items-center">
                                    <div class="col-sm-4 text-sm-end">
                                        <label class="small text-secondary fw-semibold mb-0">Agama :</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select name="agama" id="in_agama" class="form-select form-select-sm" style="border-radius: 12px;">
                                            <option value="Islam">Islam</option>
                                            <option value="Kristen">Kristen</option>
                                            <option value="Katolik">Katolik</option>
                                            <option value="Hindu">Hindu</option>
                                            <option value="Buddha">Buddha</option>
                                            <option value="Khonghucu">Khonghucu</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row g-1 align-items-center">
                                    <div class="col-sm-4 text-sm-end">
                                        <label class="small text-secondary fw-semibold mb-0">Status Nikah :</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select name="stts_nikah" id="in_stts_nikah" class="form-select form-select-sm" style="border-radius: 12px;">
                                            <option value="BELUM MENIKAH">BELUM MENIKAH</option>
                                            <option value="MENIKAH">MENIKAH</option>
                                            <option value="JANDA">JANDA</option>
                                            <option value="DUDA">DUDA</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BARIS 4: No. Telp, Pekerjaan, Pendidikan -->
                        <div class="row g-2 mb-2 align-items-center">
                            <div class="col-md-4">
                                <div class="row g-1 align-items-center">
                                    <div class="col-sm-5 text-sm-end">
                                        <label class="small text-secondary fw-semibold mb-0">No. Telp / WA :</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="text" name="no_tlp" id="in_no_tlp" class="form-control form-control-sm" placeholder="Contoh: 08123456789" style="border-radius: 12px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row g-1 align-items-center">
                                    <div class="col-sm-4 text-sm-end">
                                        <label class="small text-secondary fw-semibold mb-0">Pekerjaan :</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="pekerjaan" id="in_pekerjaan" class="form-control form-control-sm" placeholder="Pekerjaan" style="border-radius: 12px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row g-1 align-items-center">
                                    <div class="col-sm-4 text-sm-end">
                                        <label class="small text-secondary fw-semibold mb-0">Pendidikan :</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select name="pnd" id="in_pnd" class="form-select form-select-sm" style="border-radius: 12px;">
                                            <option value="-">-</option>
                                            <option value="SD">SD</option>
                                            <option value="SMP">SMP</option>
                                            <option value="SMA/SMK">SMA/SMK</option>
                                            <option value="D3">D3</option>
                                            <option value="S1">S1</option>
                                            <option value="S2">S2</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BARIS 5: Penanggung Jawab, Nama PJ, Askes / Cara Bayar -->
                        <div class="row g-2 mb-2 align-items-center">
                            <div class="col-md-4">
                                <div class="row g-1 align-items-center">
                                    <div class="col-sm-5 text-sm-end">
                                        <label class="small text-secondary fw-semibold mb-0">Penanggung Jwb :</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <select name="keluarga" id="in_keluarga" class="form-select form-select-sm" style="border-radius: 12px;">
                                            <option value="DIRI SENDIRI">DIRI SENDIRI</option>
                                            <option value="ORANG TUA">ORANG TUA</option>
                                            <option value="SUAMI">SUAMI</option>
                                            <option value="ISTRI">ISTRI</option>
                                            <option value="ANAK">ANAK</option>
                                            <option value="SAUDARA">SAUDARA</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row g-1 align-items-center">
                                    <div class="col-sm-4 text-sm-end">
                                        <label class="small text-secondary fw-semibold mb-0">Nama P.J. :</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" name="namakeluarga" id="in_namakeluarga" class="form-control form-control-sm" placeholder="Nama penanggung jawab" style="border-radius: 12px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row g-1 align-items-center">
                                    <div class="col-sm-4 text-sm-end">
                                        <label class="small text-secondary fw-semibold mb-0">Asuransi/Askes :</label>
                                    </div>
                                    <div class="col-sm-8 d-flex align-items-center gap-1">
                                        <select name="kd_pj" id="in_kd_pj" class="form-select form-select-sm" style="border-radius: 12px;">
                                            <option value="UMUM">UMUM / MANDIRI</option>
                                            <option value="BPJS">BPJS KESEHATAN</option>
                                            <option value="ASURANSI">ASURANSI SWASTA</option>
                                            <option value="PERUSAHAAN">PERUSAHAAN</option>
                                            <option value="GRATIS">GRATIS</option>
                                        </select>
                                        <input type="text" name="no_peserta" id="in_no_peserta" class="form-control form-control-sm font-monospace" placeholder="No. Ka" style="max-width: 100px; border-radius: 12px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BARIS 6: Tanggal Daftar & Alamat Lengkap -->
                        <div class="row g-2 mb-2 align-items-center">
                            <div class="col-md-4">
                                <div class="row g-1 align-items-center">
                                    <div class="col-sm-5 text-sm-end">
                                        <label class="small text-secondary fw-semibold mb-0">Tgl. Daftar :</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="date" name="tgl_daftar" id="in_tgl_daftar" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" style="border-radius: 12px;">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row g-1 align-items-center">
                                    <div class="col-sm-2 text-sm-end">
                                        <label class="small text-secondary fw-semibold mb-0">Alamat :</label>
                                    </div>
                                    <div class="col-sm-10">
                                        <input type="text" name="alamat" id="in_alamat" class="form-control form-control-sm" placeholder="Jalan, RT/RW, Dusun, Kelurahan, Kecamatan..." style="border-radius: 12px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 pt-2 border-top text-end">
                            <button type="submit" class="btn btn-sm btn-primary px-4 fw-bold shadow-sm" style="border-radius: 10px;">
                                <i class="fas fa-save me-1"></i> Simpan Data Pasien
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- FOOTER TOOLBAR ALA SIMRS KHANZA (PERSIS GAMBAR LAMPIRAN) -->
            <div class="card-footer bg-white border-top py-2 px-3">
                <form action="{{ route('masterPasien.create') }}" method="GET" id="filterForm">
                    <!-- Baris 1: Filter Alamat, Key Word, Centang, Limit Data -->
                    <div class="row g-2 align-items-center mb-2">
                        <div class="col-md-4 d-flex align-items-center gap-2">
                            <label class="small text-secondary fw-semibold mb-0" style="min-width: 60px;">Alamat :</label>
                            <input type="text" name="alamat" value="{{ request('alamat') }}" class="form-control form-control-sm" placeholder="Filter alamat..." style="border-radius: 12px;">
                        </div>
                        <div class="col-md-5 d-flex align-items-center gap-2">
                            <label class="small text-secondary fw-semibold mb-0" style="min-width: 75px;">Key Word :</label>
                            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control form-control-sm" placeholder="Cari nama, RM, no telp, NIK..." style="border-radius: 12px;">
                            <button type="submit" class="btn btn-sm btn-light border p-1 px-2 text-success fw-bold" style="border-radius: 10px;" title="Cari Data">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-md-end gap-2">
                            <label class="small text-secondary fw-semibold mb-0">Limit Data :</label>
                            <select name="limit" class="form-select form-select-sm" style="max-width: 90px; border-radius: 12px;" onchange="document.getElementById('filterForm').submit()">
                                <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('limit', 100) == 100 ? 'selected' : '' }}>100</option>
                                <option value="250" {{ request('limit') == 250 ? 'selected' : '' }}>250</option>
                                <option value="500" {{ request('limit') == 500 ? 'selected' : '' }}>500</option>
                            </select>
                        </div>
                    </div>
                </form>

                <!-- Baris 2: Tombol Aksi Simpan, Baru, Hapus, Ganti, Cetak, Semua, Record, Keluar -->
                <div class="d-flex flex-wrap align-items-center justify-content-between pt-2 border-top gap-2">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <!-- Tombol Simpan -->
                        <button type="button" class="btn btn-sm btn-light border px-3 fw-semibold shadow-2xs" onclick="triggerSimpan()" style="border-radius: 8px;">
                            <i class="fas fa-save text-primary me-1"></i> Simpan
                        </button>

                        <!-- Tombol Baru -->
                        <button type="button" class="btn btn-sm btn-light border px-3 fw-semibold shadow-2xs" onclick="triggerBaru()" style="border-radius: 8px;">
                            <i class="fas fa-plus-circle text-danger me-1"></i> Baru
                        </button>

                        <!-- Tombol Hapus -->
                        <button type="button" class="btn btn-sm btn-light border px-3 fw-semibold shadow-2xs" onclick="triggerHapus()" style="border-radius: 8px;">
                            <i class="fas fa-trash-alt text-danger me-1"></i> Hapus
                        </button>

                        <!-- Tombol Ganti -->
                        <button type="button" class="btn btn-sm btn-light border px-3 fw-semibold shadow-2xs" onclick="triggerGanti()" style="border-radius: 8px;">
                            <i class="fas fa-pencil-alt text-warning me-1"></i> Ganti
                        </button>

                        <!-- Tombol Cetak -->
                        <button type="button" class="btn btn-sm btn-light border px-3 fw-semibold shadow-2xs" onclick="triggerCetak()" style="border-radius: 8px;">
                            <i class="fas fa-print text-secondary me-1"></i> Cetak
                        </button>

                        <!-- Tombol Semua -->
                        <a href="{{ route('masterPasien.create') }}" class="btn btn-sm btn-light border px-3 fw-semibold shadow-2xs" style="border-radius: 8px;">
                            <i class="fas fa-search text-info me-1"></i> Semua
                        </a>

                        <!-- Label Record -->
                        <span class="small font-monospace text-muted px-2">Record : <strong>{{ count($listPasien) }}</strong> / {{ $totalPasien }}</span>
                    </div>

                    <!-- Tombol Keluar -->
                    <div>
                        <a href="{{ route('rawatjalan.index') }}" class="btn btn-sm btn-light border px-3 fw-bold text-danger" style="border-radius: 8px;">
                            <i class="fas fa-times-circle text-danger me-1"></i> Keluar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var selectedPasienId = null;

    // Klik Baris Tabel Data Pasien
    $(document).on('click', '.row-select-pasien', function () {
        $('.row-select-pasien').removeClass('table-primary');
        $(this).addClass('table-primary');
        $(this).find('input[type="radio"]').prop('checked', true);
        selectedPasienId = $(this).data('id');
    });

    function triggerBaru() {
        $('#khanzaPasienTab a[href="#content-input-pasien"]').tab('show');
        $('#form_input_pasien')[0].reset();
        $('#in_nm_pasien').focus();
    }

    function triggerSimpan() {
        // Jika sedang di tab input, submit form input
        if ($('#content-input-pasien').hasClass('active')) {
            $('#form_input_pasien').submit();
        } else {
            triggerBaru();
        }
    }

    function triggerGanti() {
        if (!selectedPasienId) {
            alert('Pilih data pasien di tabel terlebih dahulu!');
            return;
        }
        window.location.href = "/masterPasien/edit/" + selectedPasienId;
    }

    function triggerCetak() {
        if (!selectedPasienId) {
            alert('Pilih data pasien di tabel terlebih dahulu untuk mencetak kartu!');
            return;
        }
        window.open("/masterPasien/cetakKartu/" + selectedPasienId, '_blank');
    }

    function triggerHapus() {
        if (!selectedPasienId) {
            alert('Pilih data pasien di tabel terlebih dahulu!');
            return;
        }
        if (confirm('Yakin ingin menghapus data pasien terpilih?')) {
            var form = $('<form action="/masterPasien/destroy/' + selectedPasienId + '" method="POST">' +
                '@csrf' +
                '@method("DELETE")' +
                '</form>');
            $('body').append(form);
            form.submit();
        }
    }
</script>
@endsection
