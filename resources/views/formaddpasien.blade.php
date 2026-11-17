@extends('layout.layout')
@section('title', 'Registrasi Pasien')
@section('hide_content_header', 'true')

@section('konten')
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <form id="form_add_pasien" action="{{ route('createPasien') }}" method="POST">
                @csrf
                <input type="hidden" value="{{ auth()->user()->email }}" name="petugas">

                <!-- CARD FORM REGISTRASI PERIKSA (ALA SIMRS KHANZA) -->
                <div class="card shadow-sm border-0 rounded-3 mb-4">
                    <div class="card-header bg-white py-2 border-bottom">
                        <span class="fw-bold text-dark font-monospace" style="font-size: 13.5px;">:: [ Registrasi Periksa Hari Ini ] ::</span>
                    </div>
                    <div class="card-body p-3 bg-white">
                        <div class="row g-2 align-items-center mb-2">
                            <!-- No. Reg -->
                            <div class="col-md-2 col-sm-4 text-md-end">
                                <label class="small text-secondary fw-semibold mb-0">No. Reg. :</label>
                            </div>
                            <div class="col-md-4 col-sm-8 d-flex align-items-center gap-2">
                                <input type="text" name="no_reg" id="no_reg" value="{{ $noReg ?? '001' }}" class="form-control form-control-sm font-monospace fw-bold text-primary" style="max-width: 130px; border-radius: 12px;">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="checkbox" id="checkAutoReg" checked title="Nomor urut otomatis">
                                </div>
                            </div>

                            <!-- No. Rekam Medik -->
                            <div class="col-md-2 col-sm-4 text-md-end">
                                <label class="small text-secondary fw-semibold mb-0">No.Rekam Medik :</label>
                            </div>
                            <div class="col-md-4 col-sm-8">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="no_rkm_medis" id="no_rkm_medis" class="form-control font-monospace fw-bold text-success" style="border-radius: 14px 0 0 14px;" placeholder="Pilih dari Master RM..." readonly required>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control fw-bold text-dark bg-light" placeholder="Nama Pasien" readonly required>
                                    <button type="button" class="btn btn-light border border-start-0" data-remodal-target="modalMasterPasien" style="border-radius: 0 14px 14px 0;" title="Pilih Pasien dari Master RM">
                                        <i class="fas fa-pencil-alt text-secondary"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="no_telpon" id="no_telpon">
                                <input type="hidden" name="jenis_kelamin" id="jenis_kelamin">
                                <input type="hidden" name="tempat_lahir" id="tempat_lahir">
                                <input type="hidden" name="tgl_lahir" id="tgl_lahir">
                                <input type="hidden" name="agama" id="agama">
                                <input type="hidden" name="nama_ibu" id="nama_ibu">
                                <input type="hidden" name="gol_darah" id="gol_darah">
                                <input type="hidden" name="kelurahan" id="kelurahan">
                                <input type="hidden" name="kecamatan" id="kecamatan">
                                <input type="hidden" name="kabupaten" id="kabupaten">
                                <input type="hidden" name="provinsi" id="provinsi">
                                <input type="hidden" name="alamat_lengkap" id="alamat_lengkap">
                            </div>
                        </div>

                        <div class="row g-2 align-items-center mb-2">
                            <!-- No. Rawat -->
                            <div class="col-md-2 col-sm-4 text-md-end">
                                <label class="small text-secondary fw-semibold mb-0">No. Rawat :</label>
                            </div>
                            <div class="col-md-4 col-sm-8">
                                <input type="text" name="no_rawat" id="no_rawat" value="{{ $IDPengunjung }}" class="form-control form-control-sm font-monospace fw-bold text-dark bg-light" readonly style="border-radius: 12px;">
                            </div>

                            <!-- Penanggung Jawab & Hubungan -->
                            <div class="col-md-2 col-sm-4 text-md-end">
                                <label class="small text-secondary fw-semibold mb-0">Penanggung Jawab :</label>
                            </div>
                            <div class="col-md-4 col-sm-8 d-flex align-items-center gap-1">
                                <input type="text" name="p_jawab" id="p_jawab" class="form-control form-control-sm" placeholder="Nama P.J." style="border-radius: 12px;">
                                <span class="small text-muted px-1">Hubungan:</span>
                                <select name="hubunganpj" id="hubunganpj" class="form-select form-select-sm" style="max-width: 130px; border-radius: 12px;">
                                    <option value="DIRI SENDIRI">DIRI SENDIRI</option>
                                    <option value="ORANG TUA">ORANG TUA</option>
                                    <option value="SUAMI">SUAMI</option>
                                    <option value="ISTRI">ISTRI</option>
                                    <option value="ANAK">ANAK</option>
                                    <option value="SAUDARA">SAUDARA</option>
                                    <option value="LAIN-LAIN">LAIN-LAIN</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-2 align-items-center mb-2">
                            <!-- Tgl & Jam Reg -->
                            <div class="col-md-2 col-sm-4 text-md-end">
                                <label class="small text-secondary fw-semibold mb-0">Tgl. Reg. :</label>
                            </div>
                            <div class="col-md-4 col-sm-8 d-flex align-items-center gap-2">
                                <input type="date" name="tanggal_masuk" id="tanggal_masuk" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" style="border-radius: 12px; max-width: 150px;">
                                <span class="small text-muted">Jam :</span>
                                <input type="time" step="1" name="jam_masuk" id="jam_masuk" value="{{ date('H:i:s') }}" class="form-control form-control-sm" style="border-radius: 12px; max-width: 130px;">
                            </div>

                            <!-- Alamat P.J. & Status Poli -->
                            <div class="col-md-2 col-sm-4 text-md-end">
                                <label class="small text-secondary fw-semibold mb-0">Alamat P.J. :</label>
                            </div>
                            <div class="col-md-4 col-sm-8 d-flex align-items-center gap-1">
                                <input type="text" name="almt_pj" id="almt_pj" class="form-control form-control-sm" placeholder="Alamat penanggung jawab" style="border-radius: 12px;">
                                <span class="small text-muted px-1">Status:</span>
                                <select name="status_poli" id="status_poli" class="form-select form-select-sm" style="max-width: 110px; border-radius: 12px;">
                                    <option value="Baru">Baru</option>
                                    <option value="Lama">Lama</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-2 align-items-center mb-2">
                            <!-- Dr Dituju -->
                            <div class="col-md-2 col-sm-4 text-md-end">
                                <label class="small text-secondary fw-semibold mb-0">Dr Dituju :</label>
                            </div>
                            <div class="col-md-4 col-sm-8">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="kd_dokter" id="kd_dokter" value="D0000001" class="form-control font-monospace bg-light border-end-0" style="max-width: 95px; border-radius: 14px 0 0 14px;" placeholder="Kode" readonly>
                                    <input type="text" name="dokter_dituju" id="dokter_dituju" value="dr. Welly" class="form-control fw-bold text-dark bg-white border-start-0 border-end-0" style="border-radius: 0;" placeholder="Nama Dokter" readonly>
                                    <button type="button" class="btn btn-light border border-start-0" data-remodal-target="modalMasterDokter" style="border-radius: 0 14px 14px 0;" title="Pilih Dokter dari Master">
                                        <i class="fas fa-pencil-alt text-secondary"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Jenis Bayar & No. Kartu -->
                            <div class="col-md-2 col-sm-4 text-md-end">
                                <label class="small text-secondary fw-semibold mb-0 text-primary">Jenis Bayar :</label>
                            </div>
                            <div class="col-md-4 col-sm-8 d-flex align-items-center gap-1">
                                <div class="input-group input-group-sm">
                                    <input type="hidden" name="kd_pj" id="kd_pj" value="PJ001">
                                    <select name="jenis_bayar" id="jenis_bayar" class="form-select form-select-sm fw-bold border-primary border-end-0" style="border-radius: 14px 0 0 14px;">
                                        @if(isset($penjabs) && count($penjabs) > 0)
                                            @foreach($penjabs as $pj)
                                                <option value="{{ $pj->png_jawab }}" data-kdpj="{{ $pj->kd_pj }}" {{ $pj->png_jawab == 'UMUM' ? 'selected' : '' }}>
                                                    {{ $pj->png_jawab }} ({{ $pj->nama_perusahaan }})
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="UMUM" selected>UMUM / MANDIRI</option>
                                            <option value="BPJS">BPJS KESEHATAN</option>
                                            <option value="ASURANSI">ASURANSI SWASTA</option>
                                            <option value="PERUSAHAAN">PERUSAHAAN</option>
                                            <option value="GRATIS">GRATIS / DOKTER</option>
                                        @endif
                                    </select>
                                    <button type="button" class="btn btn-light border border-start-0" data-remodal-target="modalMasterPenjab" style="border-radius: 0 14px 14px 0;" title="Master Jenis Bayar">
                                        <i class="fas fa-pencil-alt text-secondary"></i>
                                    </button>
                                </div>
                                <span class="small text-muted px-1">No.Ka:</span>
                                <input type="text" name="no_kartu" id="no_kartu" class="form-control form-control-sm font-monospace" placeholder="Nomor Kartu" style="border-radius: 14px;">
                            </div>
                        </div>

                        <div class="row g-2 align-items-center">
                            <!-- Unit / Poli -->
                            <div class="col-md-2 col-sm-4 text-md-end">
                                <label class="small text-secondary fw-semibold mb-0">Unit :</label>
                            </div>
                            <div class="col-md-4 col-sm-8">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="kd_poli" id="kd_poli" value="U001" class="form-control font-monospace bg-light border-end-0" style="max-width: 75px; border-radius: 14px 0 0 14px;" placeholder="Kode" readonly>
                                    <input type="text" name="unit_poli" id="unit_poli" value="Poli Perawatan Luka Modern & Bedah Minor" class="form-control fw-bold text-dark bg-white border-start-0 border-end-0" style="border-radius: 0;" placeholder="Nama Unit / Poliklinik" readonly>
                                    <button type="button" class="btn btn-light border border-start-0" data-remodal-target="modalMasterPoli" style="border-radius: 0 14px 14px 0;" title="Pilih Unit / Poli dari Master">
                                        <i class="fas fa-pencil-alt text-secondary"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Asal Rujukan -->
                            <div class="col-md-2 col-sm-4 text-md-end">
                                <label class="small text-secondary fw-semibold mb-0">Asal Rujukan :</label>
                            </div>
                            <div class="col-md-4 col-sm-8">
                                <input type="text" name="asal_rujukan" id="asal_rujukan" class="form-control form-control-sm" placeholder="Contoh: Datang Sendiri, Faskes 1, RS Lain" style="border-radius: 14px;">
                            </div>
                        </div>
                    </div>

                    <!-- FOOTER TOMBOL AKSI FORM KHANZA -->
                    <div class="card-footer bg-light border-top py-2 px-3 d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <span id="labelModeForm" class="badge badge-primary px-2 py-1 mr-1">Mode: Input Baru</span>
                            Petugas: <strong>{{ auth()->user()->name ?? 'Admin' }}</strong> ({{ auth()->user()->email }})
                        </small>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary px-3" onclick="resetFormPasien()">
                                <i class="fas fa-file text-danger me-1"></i> Baru
                            </button>
                            <button type="submit" id="btnSimpanRegistrasi" class="btn btn-sm btn-primary px-4 fw-bold shadow-sm">
                                <i class="fas fa-save me-1"></i> <span id="textBtnSimpan">Simpan</span>
                            </button>
                            <button type="button" id="btnHapusRegistrasi" class="btn btn-sm btn-outline-danger px-3" onclick="hapusRegistrasiTerpilih()" style="display: none;">
                                <i class="fas fa-trash-alt me-1"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- TABEL INPUT DATA REGISTRASI PASIEN (PERSIS GAMBAR KHANZA) -->
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 6px;">
                <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-home text-primary mr-2"></i>
                        <span class="font-monospace fw-bold text-dark" style="font-size: 12.5px;">:: Input Data Registrasi Pasien ::</span>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge badge-light border text-secondary font-monospace px-2 py-1">Registrasi Awal</span>
                        <span class="badge badge-light border text-muted font-monospace px-2 py-1">Rujukan Internal Poli</span>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 480px; overflow-y: auto; overflow-x: auto;">
                        <table class="table table-bordered table-hover mb-0 text-nowrap align-middle" style="font-size: 11px; font-family: Tahoma, Arial, sans-serif;">
                            <thead class="bg-light sticky-top" style="border-bottom: 2px solid #dee2e6;">
                                <tr class="text-secondary">
                                    <th width="30px" class="text-center">P</th>
                                    <th width="50px" class="text-center">No.Reg</th>
                                    <th width="140px">No.Rawat</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th width="80px">Kode Dokter</th>
                                    <th>Dokter Dituju</th>
                                    <th width="80px">Nomor RM</th>
                                    <th>Pasien</th>
                                    <th class="text-center" width="40px">J.K.</th>
                                    <th width="60px">Umur</th>
                                    <th>Poliklinik</th>
                                    <th class="text-center" width="75px">Jenis Bayar</th>
                                    <th>Penanggung Jawab</th>
                                    <th>Alamat P.J.</th>
                                    <th>Hubungan P.J.</th>
                                    <th class="text-end" width="80px">Biaya Registrasi</th>
                                    <th class="text-center" width="60px">Status</th>
                                    <th>No. Telp</th>
                                    <th class="text-center" width="60px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($daftarRegistrasi as $reg)
                                    @php
                                        $umur = '-';
                                        if ($reg->tgl_lahir) {
                                            $birthDate = new \DateTime($reg->tgl_lahir);
                                            $today = new \DateTime();
                                            $diff = $today->diff($birthDate);
                                            $umur = $diff->y . ' Th';
                                        }
                                        $jk = ($reg->jenis_kelamin == 'Laki-laki' || $reg->jenis_kelamin == 'L') ? 'L' : 'P';
                                        $encodedId = $hashid->encodeHex($reg->id);
                                    @endphp
                                    <tr style="cursor: pointer;" onclick="loadRegistrasiKeForm({{ json_encode(array_merge($reg->toArray(), ['hashid' => $encodedId])) }})">
                                        <td class="text-center"><i class="far fa-square text-secondary"></i></td>
                                        <td class="text-center font-monospace font-weight-bold text-primary">{{ $reg->no_reg ?? '-' }}</td>
                                        <td class="font-monospace fw-bold">{{ $reg->no_rawat }}</td>
                                        <td>{{ $reg->tanggal_masuk ?? '-' }}</td>
                                        <td>{{ $reg->jam_masuk ?? '-' }}</td>
                                        <td class="font-monospace text-primary">{{ $reg->kd_dokter ?? 'D0000001' }}</td>
                                        <td class="fw-bold">{{ $reg->dokter_dituju ?? '-' }}</td>
                                        <td class="font-monospace text-success fw-bold">{{ $reg->no_rkm_medis ?? '-' }}</td>
                                        <td class="fw-bold text-dark">{{ $reg->nama_lengkap }}</td>
                                        <td class="text-center fw-bold {{ $jk == 'L' ? 'text-primary' : 'text-danger' }}">{{ $jk }}</td>
                                        <td>{{ $umur }}</td>
                                        <td><span class="badge badge-light border text-dark">{{ $reg->unit_poli ?? 'Poli Umum' }}</span></td>
                                        <td class="text-center">
                                            <span class="badge {{ ($reg->jenis_bayar == 'BPJS') ? 'badge-success' : 'badge-primary' }} px-2">
                                                {{ strtoupper($reg->jenis_bayar ?? 'UMUM') }}
                                            </span>
                                        </td>
                                        <td>{{ $reg->p_jawab ?? '-' }}</td>
                                        <td>
                                            <span class="d-inline-block text-truncate" style="max-width: 170px;" title="{{ $reg->almt_pj ?? $reg->alamat_lengkap }}">
                                                {{ $reg->almt_pj ?? $reg->alamat_lengkap ?? '-' }}
                                            </span>
                                        </td>
                                        <td>{{ $reg->hubunganpj ?? 'DIRI SENDIRI' }}</td>
                                        <td class="text-end font-monospace">40,000</td>
                                        <td class="text-center">
                                            <span class="badge {{ ($reg->status_poli == 'Baru') ? 'badge-danger' : 'badge-info' }} px-2">
                                                {{ $reg->status_poli ?? 'Baru' }}
                                            </span>
                                        </td>
                                        <td class="font-monospace">{{ $reg->no_telpon ?? '-' }}</td>
                                        <td class="text-center" onclick="event.stopPropagation();">
                                            <div class="dropdown">
                                                <button class="btn btn-xs btn-light border py-0 px-2" type="button" data-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v text-secondary"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 py-1" style="font-size: 11.5px;">
                                                    <a class="dropdown-item py-1" href="{{ url('/soap/' . $hashid->encodeHex($reg->id)) }}">
                                                        <i class="fas fa-stethoscope text-danger mr-2"></i> Input SOAP
                                                    </a>
                                                    <a class="dropdown-item py-1" href="{{ url('/pelayanan/diagnosa/' . $hashid->encodeHex($reg->id)) }}">
                                                        <i class="fas fa-diagnoses text-warning mr-2"></i> Diagnosa ICD-10
                                                    </a>
                                                    <a class="dropdown-item py-1" href="{{ url('/pelayanan/billing/' . $hashid->encodeHex($reg->id)) }}">
                                                        <i class="fas fa-file-invoice-dollar text-success mr-2"></i> Kasir Billing
                                                    </a>
                                                    <a class="dropdown-item py-1" href="{{ url('/pelayanan/resume/' . $hashid->encodeHex($reg->id)) }}">
                                                        <i class="fas fa-file-medical-alt text-primary mr-2"></i> Resume Medis
                                                    </a>
                                                    <div class="dropdown-divider my-1"></div>
                                                    <a class="dropdown-item py-1 text-danger font-weight-bold" href="{{ route('surat.sakit.index') }}?pasien_id={{ $hashid->encodeHex($reg->id) }}">
                                                        <i class="fas fa-bed mr-2"></i> Buat Surat Sakit
                                                    </a>
                                                    <a class="dropdown-item py-1 text-success font-weight-bold" href="{{ route('surat.sehat.index') }}?pasien_id={{ $hashid->encodeHex($reg->id) }}">
                                                        <i class="fas fa-heartbeat mr-2"></i> Buat Surat Sehat
                                                    </a>
                                                    <div class="dropdown-divider my-1"></div>
                                                    <a class="dropdown-item py-1 text-danger" href="{{ url('/delete/' . $hashid->encodeHex($reg->id)) }}" onclick="return confirm('Apakah Anda yakin ingin menghapus registrasi kunjungan pasien {{ $reg->nama_lengkap }}?')">
                                                        <i class="fas fa-trash-alt mr-2"></i> Hapus Registrasi
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="20" class="text-center py-4 text-muted">
                                            <i class="fas fa-info-circle mr-1"></i> Belum ada pasien registrasi periksa pada tanggal ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- FILTER & PENCARIAN BAWAH PERSIS SIMRS KHANZA -->
                    <div class="card-footer bg-light border-top py-2 px-3">
                        <form method="GET" action="{{ route('registrasi.pasien') }}" class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <label class="small fw-semibold text-secondary mb-0">Periode :</label>
                                <input type="date" name="tgl_awal" value="{{ $tglAwal }}" class="form-control form-control-sm" style="max-width: 135px; border-radius: 8px;">
                                <span class="small text-muted">s.d.</span>
                                <input type="date" name="tgl_akhir" value="{{ $tglAkhir }}" class="form-control form-control-sm" style="max-width: 135px; border-radius: 8px;">
                                
                                <span class="small fw-semibold text-secondary ml-2 mb-0">Key Word :</span>
                                <div class="input-group input-group-sm" style="max-width: 250px;">
                                    <input type="text" name="keyword" value="{{ $keyword }}" class="form-control" placeholder="Cari No.RM / Nama / No.Rawat..." style="border-radius: 8px 0 0 8px;">
                                    <button type="submit" class="btn btn-primary px-3" style="border-radius: 0 8px 8px 0;" title="Terapkan Filter">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                @if($keyword || $tglAwal != date('Y-m-d') || $tglAkhir != date('Y-m-d'))
                                    <a href="{{ route('registrasi.pasien') }}" class="btn btn-sm btn-outline-secondary px-2" title="Reset Filter">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                @endif
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <span class="small text-muted font-monospace"><i class="fas fa-database mr-1"></i> Record: <strong>{{ $daftarRegistrasi->total() }}</strong></span>
                                <span class="badge badge-success px-2 py-1 font-monospace" style="font-size: 11px;">
                                    <i class="fas fa-check-circle mr-1"></i> Belum SEP : 0
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- REMODAL POPUP CARI MASTER PASIEN -->
    <div class="remodal" data-remodal-id="modalMasterPasien" role="dialog" aria-labelledby="modalTitle" style="max-width: 850px; text-align: left; padding: 24px;">
        <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
        <div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary bg-opacity-10 text-primary p-2 rounded">
                        <i class="fas fa-database fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark" id="modalTitle">Pilih Data dari Master Rekam Medis</h5>
                        <small class="text-muted">Cari pasien terdahulu untuk auto-fill biodata kunjungan baru</small>
                    </div>
                </div>
                <a href="{{ route('masterPasien.index') }}" target="_blank" class="btn btn-sm btn-success fw-bold shadow-sm">
                    <i class="fas fa-user-plus me-1"></i> Bikin RM Baru
                </a>
            </div>

            <div class="mb-3">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" id="inputCariMaster" class="form-control" placeholder="Ketik No. RM atau Nama Pasien (misal: Rasya, Jovani, Gatot, dll)..." autofocus>
                    <button type="button" class="btn btn-primary px-3" id="btnCariMaster">
                        <i class="fas fa-search me-1"></i> Cari
                    </button>
                </div>
            </div>

            <div class="table-responsive border rounded" style="max-height: 380px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th width="110px">No. RM</th>
                            <th>Nama Pasien</th>
                            <th width="60px">L/P</th>
                            <th>No. Telepon</th>
                            <th>Alamat Domisili</th>
                            <th class="text-center" width="90px">Pilih</th>
                        </tr>
                    </thead>
                    <tbody id="tabelMasterPasienBody">
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-spinner fa-spin me-2"></i> Memuat data master pasien...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-3 text-end">
                <button data-remodal-action="cancel" class="btn btn-secondary px-4">Tutup</button>
            </div>
        </div>
    </div>

    <!-- REMODAL POPUP MASTER DOKTER -->
    <div class="remodal" data-remodal-id="modalMasterDokter" role="dialog" style="max-width: 800px; text-align: left; padding: 24px;">
        <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
        <div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary text-white p-2 rounded">
                        <i class="fas fa-user-md fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Master Data Dokter (DPJP)</h5>
                        <small class="text-muted">Pilih dokter yang dituju untuk registrasi kunjungan</small>
                    </div>
                </div>
                <a href="{{ route('master.dokter.index') }}" target="_blank" class="btn btn-sm btn-outline-primary fw-bold">
                    <i class="fas fa-cog me-1"></i> Kelola Master Dokter
                </a>
            </div>

            <div class="table-responsive border rounded" style="max-height: 320px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th width="120px">Kode Dokter</th>
                            <th>Nama Dokter</th>
                            <th>Spesialisasi / Keahlian</th>
                            <th class="text-center" width="90px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($dokters) && count($dokters) > 0)
                            @foreach($dokters as $doc)
                                <tr>
                                    <td><span class="badge bg-light text-primary border font-monospace px-2 py-1">{{ $doc->kd_dokter }}</span></td>
                                    <td class="fw-bold text-dark">{{ $doc->nm_dokter }}</td>
                                    <td><small class="text-muted">{{ $doc->spesialis ?? '-' }}</small></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary px-3 fw-bold btn-pilih-dokter" 
                                            data-kode="{{ $doc->kd_dokter }}" 
                                            data-nama="{{ $doc->nm_dokter }}">
                                            <i class="fas fa-check me-1"></i> Pilih
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td><span class="badge bg-light text-primary border font-monospace px-2 py-1">D0000001</span></td>
                                <td class="fw-bold text-dark">dr. Welly</td>
                                <td><small class="text-muted">Praktisi Medis & Dokter Penanggung Jawab</small></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary px-3 fw-bold btn-pilih-dokter" 
                                        data-kode="D0000001" 
                                        data-nama="dr. Welly">
                                        <i class="fas fa-check me-1"></i> Pilih
                                    </button>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="mt-3 text-end">
                <button data-remodal-action="cancel" class="btn btn-secondary px-4">Tutup</button>
            </div>
        </div>
    </div>

    <!-- REMODAL POPUP MASTER POLIKLINIK / UNIT -->
    <div class="remodal" data-remodal-id="modalMasterPoli" role="dialog" style="max-width: 750px; text-align: left; padding: 24px;">
        <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
        <div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-success text-white p-2 rounded">
                        <i class="fas fa-clinic-medical fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Master Poliklinik / Unit</h5>
                        <small class="text-muted">Pilih unit pelayanan / poli tujuan</small>
                    </div>
                </div>
                <a href="{{ route('master.poli.index') }}" target="_blank" class="btn btn-sm btn-outline-success fw-bold">
                    <i class="fas fa-cog me-1"></i> Kelola Master Poli
                </a>
            </div>

            <div class="table-responsive border rounded" style="max-height: 300px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th width="120px">Kode Unit</th>
                            <th>Nama Poliklinik / Layanan</th>
                            <th class="text-center" width="90px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($polis) && count($polis) > 0)
                            @foreach($polis as $pl)
                                <tr>
                                    <td><span class="badge bg-light text-success border font-monospace px-2 py-1">{{ $pl->kd_poli }}</span></td>
                                    <td class="fw-bold text-dark">{{ $pl->nm_poli }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-success px-3 fw-bold btn-pilih-poli" 
                                            data-kode="{{ $pl->kd_poli }}" 
                                            data-nama="{{ $pl->nm_poli }}">
                                            <i class="fas fa-check me-1"></i> Pilih
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="mt-3 text-end">
                <button data-remodal-action="cancel" class="btn btn-secondary px-4">Tutup</button>
            </div>
        </div>
    </div>

    <!-- REMODAL POPUP MASTER PENJAB / ASURANSI -->
    <div class="remodal" data-remodal-id="modalMasterPenjab" role="dialog" style="max-width: 800px; text-align: left; padding: 24px;">
        <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
        <div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-warning text-dark p-2 rounded">
                        <i class="fas fa-file-contract fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Master Jenis Bayar & Penjamin</h5>
                        <small class="text-muted">Kelola kategori asuransi / cara bayar pasien</small>
                    </div>
                </div>
                <a href="{{ route('master.penjab.index') }}" target="_blank" class="btn btn-sm btn-outline-warning text-dark fw-bold">
                    <i class="fas fa-cog me-1"></i> Kelola Master Penjab
                </a>
            </div>

            <div class="table-responsive border rounded" style="max-height: 300px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th width="100px">Kode PJ</th>
                            <th>Nama Penjamin</th>
                            <th>Keterangan / Perusahaan</th>
                            <th class="text-center" width="90px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($penjabs) && count($penjabs) > 0)
                            @foreach($penjabs as $pj)
                                <tr>
                                    <td><span class="badge bg-light text-dark border font-monospace px-2 py-1">{{ $pj->kd_pj }}</span></td>
                                    <td class="fw-bold text-dark">{{ $pj->png_jawab }}</td>
                                    <td><small class="text-muted">{{ $pj->nama_perusahaan }}</small></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-warning text-dark px-3 fw-bold btn-pilih-penjab" 
                                            data-kode="{{ $pj->kd_pj }}" 
                                            data-nama="{{ $pj->png_jawab }}">
                                            <i class="fas fa-check me-1"></i> Pilih
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="mt-3 text-end">
                <button data-remodal-action="cancel" class="btn btn-secondary px-4">Tutup</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function resetFormPasien() {
        document.getElementById('badgeStatusPasien').className = 'badge bg-primary-subtle text-primary';
        document.getElementById('badgeStatusPasien').innerText = 'Registrasi Baru';
    }

    $(document).ready(function () {
        var inputCari = $('#inputCariMaster');
        var btnCari = $('#btnCariMaster');
        var tbody = $('#tabelMasterPasienBody');

        function loadMasterPasien(query) {
            tbody.html('<tr><td colspan="6" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i> Mencari di master rekam medis...</td></tr>');

            $.ajax({
                url: "{{ route('masterPasien.apiSearch') }}",
                type: "GET",
                data: { q: query },
                dataType: "json",
                success: function (data) {
                    if (!data || data.length === 0) {
                        tbody.html('<tr><td colspan="6" class="text-center py-4 text-muted">' +
                            '<div class="mb-2"><i class="fas fa-user-slash text-secondary" style="font-size: 2rem; opacity: 0.4;"></i></div>' +
                            '<div class="fw-bold text-dark mb-1">Pasien Tidak Ditemukan</div>' +
                            '<p class="small text-muted mb-3">Tidak ada data pasien di Master RM dengan kata kunci pencarian tersebut.</p>' +
                            '<a href="{{ route("masterPasien.index") }}" target="_blank" class="btn btn-sm btn-success fw-bold px-3 shadow-sm">' +
                                '<i class="fas fa-plus-circle me-1"></i> Bikin RM Baru Sekarang' +
                            '</a>' +
                        '</td></tr>');
                        return;
                    }

                    var rows = '';
                    $.each(data, function (index, p) {
                        var genderBadge = p.jk === 'L' 
                            ? '<span class="badge bg-light text-primary border">L</span>' 
                            : (p.jk === 'P' ? '<span class="badge bg-light text-danger border">P</span>' : '-');

                        rows += '<tr>' +
                            '<td><span class="badge bg-light text-success border border-success-subtle font-monospace px-2 py-1">' + (p.no_rkm_medis || '-') + '</span></td>' +
                            '<td><div class="fw-bold text-dark">' + (p.nm_pasien || '-') + '</div><small class="text-muted">' + (p.nm_ibu ? 'Ibu: ' + p.nm_ibu : '') + '</small></td>' +
                            '<td>' + genderBadge + '</td>' +
                            '<td><small class="text-muted">' + (p.no_tlp || '-') + '</small></td>' +
                            '<td><small class="text-muted">' + (p.alamat || '-') + '</small></td>' +
                            '<td class="text-center">' +
                                '<button type="button" class="btn btn-sm btn-primary px-3 fw-bold btn-pilih-pasien" data-id="' + p.id + '">' +
                                    '<i class="fas fa-check me-1"></i> Pilih' +
                                '</button>' +
                            '</td>' +
                        '</tr>';
                    });

                    tbody.html(rows);

                    // Event Klik Tombol Pilih
                    $('.btn-pilih-pasien').off('click').on('click', function () {
                        var id = $(this).data('id');
                        tarikDataPasien(id);
                    });
                },
                error: function () {
                    tbody.html('<tr><td colspan="6" class="text-center py-4 text-danger"><i class="fas fa-exclamation-triangle me-1"></i> Gagal memuat data master pasien.</td></tr>');
                }
            });
        }

        // Tarik detail data pasien terpilih
        function tarikDataPasien(id) {
            $.ajax({
                url: "/api/masterPasien/detail/" + id,
                type: "GET",
                dataType: "json",
                success: function (p) {
                    $('#no_rkm_medis').val(p.no_rkm_medis || '');
                    $('#nama_lengkap').val(p.nm_pasien || '');
                    $('#no_telpon').val(p.no_tlp || '');
                    $('#tempat_lahir').val(p.tmp_lahir || '');
                    $('#tgl_lahir').val(p.tgl_lahir || '');
                    $('#nama_ibu').val(p.nm_ibu || '');
                    $('#provinsi').val(p.provinsi || '');
                    $('#kabupaten').val(p.kabupaten || '');
                    $('#kecamatan').val(p.kecamatan || '');
                    $('#kelurahan').val(p.kelurahan || '');
                    $('#alamat_lengkap').val(p.alamat || '');

                    if (p.jk) {
                        $('#jenis_kelamin').val(p.jk === 'L' ? 'Laki-laki' : 'Perempuan');
                    }
                    if (p.gol_darah) $('#gol_darah').val(p.gol_darah);
                    if (p.agama) $('#agama').val(p.agama);

                    // Update Badge Status Pasien
                    $('#badgeStatusPasien')
                        .removeClass('bg-primary-subtle text-primary')
                        .addClass('bg-success-subtle text-success')
                        .html('<i class="fas fa-check-circle me-1"></i> Diambil dari Master RM: ' + p.no_rkm_medis + ' (' + p.nm_pasien + ')');

                    // Tutup Remodal
                    var inst = $('[data-remodal-id=modalMasterPasien]').remodal();
                    inst.close();

                    $('#nama_lengkap').focus();
                },
                error: function () {
                    alert('Gagal mengambil data detail pasien.');
                }
            });
        }

        // Event saat Remodal dibuka
        $(document).on('opening', '[data-remodal-id=modalMasterPasien]', function () {
            loadMasterPasien('');
            setTimeout(function () {
                $('#inputCariMaster').focus();
            }, 300);
        });

        // Trigger Pencarian
        btnCari.on('click', function () {
            loadMasterPasien(inputCari.val());
        });

        inputCari.on('keyup', function (e) {
            if (e.key === 'Enter') {
                loadMasterPasien(inputCari.val());
            }
        });

        // Event Pilih Dokter dari Modal
        $(document).on('click', '.btn-pilih-dokter', function () {
            var kd = $(this).data('kode');
            var nama = $(this).data('nama');
            $('#kd_dokter').val(kd);
            $('#dokter_dituju').val(nama);
            var inst = $('[data-remodal-id=modalMasterDokter]').remodal();
            inst.close();
        });

        // Event Pilih Poliklinik dari Modal
        $(document).on('click', '.btn-pilih-poli', function () {
            var kd = $(this).data('kode');
            var nama = $(this).data('nama');
            $('#kd_poli').val(kd);
            $('#unit_poli').val(nama);
            var inst = $('[data-remodal-id=modalMasterPoli]').remodal();
            inst.close();
        });

        // Event Pilih Penjab / Jenis Bayar dari Modal
        $(document).on('click', '.btn-pilih-penjab', function () {
            var kd = $(this).data('kode');
            var nama = $(this).data('nama');
            $('#kd_pj').val(kd);
            $('#jenis_bayar').val(nama);
            var inst = $('[data-remodal-id=modalMasterPenjab]').remodal();
            inst.close();
        });

        // Sinkronkan kode PJ saat select jenis bayar diubah
        $('#jenis_bayar').on('change', function () {
            var selectedOpt = $(this).find('option:selected');
            var kd = selectedOpt.data('kdpj') || 'PJ001';
            $('#kd_pj').val(kd);
        });
    });

    var activeEditHashId = null;

    function loadRegistrasiKeForm(reg) {
        activeEditHashId = reg.hashid || reg.id;

        // Ubah form action ke route editPasien
        var editUrl = "{{ url('/editPasien') }}/" + activeEditHashId;
        $('#form_add_pasien').attr('action', editUrl);

        // Tambahkan input hidden from_registrasi jika belum ada
        if ($('#inputFromRegistrasi').length === 0) {
            $('#form_add_pasien').append('<input type="hidden" name="from_registrasi" id="inputFromRegistrasi" value="1">');
        }

        // Ubah label & tombol aksi
        $('#labelModeForm').removeClass('badge-primary').addClass('badge-warning text-dark').text('Mode: Edit Data (No. Rawat: ' + reg.no_rawat + ')');
        $('#textBtnSimpan').text('Ganti / Update');
        $('#btnSimpanRegistrasi').removeClass('btn-primary').addClass('btn-warning font-weight-bold text-dark');
        $('#btnHapusRegistrasi').show();

        if (reg.no_reg) $('#no_reg').val(reg.no_reg);
        if (reg.no_rawat) $('#no_rawat').val(reg.no_rawat);
        if (reg.no_rkm_medis) $('#no_rkm_medis').val(reg.no_rkm_medis);
        if (reg.nama_lengkap) $('#nama_lengkap').val(reg.nama_lengkap);
        if (reg.p_jawab) $('#p_jawab').val(reg.p_jawab);
        if (reg.hubunganpj) $('#hubunganpj').val(reg.hubunganpj);
        if (reg.almt_pj) $('#almt_pj').val(reg.almt_pj);
        if (reg.status_poli) $('#status_poli').val(reg.status_poli);
        if (reg.kd_dokter) $('#kd_dokter').val(reg.kd_dokter);
        if (reg.dokter_dituju) $('#dokter_dituju').val(reg.dokter_dituju);
        if (reg.kd_poli) $('#kd_poli').val(reg.kd_poli);
        if (reg.unit_poli) $('#unit_poli').val(reg.unit_poli);
        if (reg.jenis_bayar) $('#jenis_bayar').val(reg.jenis_bayar);
        if (reg.no_kartu) $('#no_kartu').val(reg.no_kartu);
        if (reg.asal_rujukan) $('#asal_rujukan').val(reg.asal_rujukan);
        if (reg.tanggal_masuk) $('#tanggal_masuk').val(reg.tanggal_masuk);
        if (reg.jam_masuk) $('#jam_masuk').val(reg.jam_masuk);

        // Scroll halus ke form atas
        $('html, body').animate({
            scrollTop: $("#form_add_pasien").offset().top - 80
        }, 300);
    }

    function hapusRegistrasiTerpilih() {
        if (!activeEditHashId) {
            alert('Pilih data pasien terlebih dahulu!');
            return;
        }
        var nama = $('#nama_lengkap').val();
        if (confirm('Apakah Anda yakin ingin menghapus data registrasi pasien ' + nama + ' ini?')) {
            window.location.href = "{{ url('/delete') }}/" + activeEditHashId;
        }
    }

    function resetFormPasien() {
        window.location.href = "{{ route('registrasi.pasien') }}";
    }
</script>
@endsection
