@extends('layout.layout')
@section('title', 'Data Pasien Rawat Jalan')
@section('hide_content_header', 'true')

@section('konten')
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

    <!-- CARD UTAMA GAYA SIMRS KHANZA PERSIS GAMBAR LAMPIRAN -->
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 8px;">
        <div class="card-header bg-white py-2 px-3 border-bottom">
            <span class="font-monospace fw-bold text-dark" style="font-size: 13.5px;">:: [ Registrasi Pasien Rawat Jalan Hari Ini ] ::</span>
        </div>

        <!-- TABEL DATA PASIEN (PERSIS KOLOM & FORMAT SIMRS KHANZA) -->
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 520px; overflow-y: auto; overflow-x: auto; min-height: 380px;">
                <table class="table table-bordered table-hover mb-0 text-nowrap align-middle" style="font-size: 11.5px;">
                    <thead class="bg-light sticky-top" style="border-bottom: 2px solid #dee2e6;">
                        <tr class="text-secondary">
                            <th width="80px">Kode Dokter</th>
                            <th>Dokter Dituju</th>
                            <th width="85px">No.RM</th>
                            <th>Pasien</th>
                            <th>Poliklinik</th>
                            <th>Penanggung Jawab</th>
                            <th>Alamat P.J.</th>
                            <th>Hubungan P.J.</th>
                            <th class="text-end" width="80px">Biaya Reg</th>
                            <th class="text-center" width="80px">Jenis Bayar</th>
                            <th class="text-center" width="70px">Status</th>
                            <th width="140px">No.Rawat</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th width="50px" class="text-center">No.Reg</th>
                            <th class="text-center">Status Periksa</th>
                            <th class="text-center">Stts.Poli</th>
                            <th>No.Telp Pasien</th>
                            <th>No.SEP</th>
                            <th>Potensi PRB</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pasien as $data)
                            @php
                                $isRedAlert = false;
                                if (($data->status_bayar ?? 'Belum') == 'Sudah' && ($data->status_poli ?? 'Baru') == 'Baru') {
                                    $isRedAlert = true;
                                }
                                $umur = '-';
                                if ($data->tgl_lahir) {
                                    $birthDate = new \DateTime($data->tgl_lahir);
                                    $today = new \DateTime();
                                    $diff = $today->diff($birthDate);
                                    $umur = $diff->y . ' Th';
                                }
                            @endphp
                            <tr class="row-pasien {{ $isRedAlert ? 'bg-danger text-white' : '' }}" 
                                data-id="{{ $hashid->encodeHex($data->id) }}"
                                data-nama="{{ $data->nama_lengkap }}"
                                data-norawat="{{ $data->no_rawat }}"
                                data-norm="{{ $data->no_rkm_medis ?? '-' }}"
                                style="cursor: pointer;">
                                <td class="font-monospace {{ $isRedAlert ? 'text-white' : 'text-primary' }}">{{ $data->kd_dokter ?? 'D0000001' }}</td>
                                <td class="fw-bold">{{ $data->dokter_dituju ?? 'dr. Welly' }}</td>
                                <td class="font-monospace fw-bold {{ $isRedAlert ? 'text-white' : 'text-success' }}">{{ $data->no_rkm_medis ?? '-' }}</td>
                                <td>
                                    <strong>{{ $data->nama_lengkap }}</strong> 
                                    @if($umur != '-') <span class="{{ $isRedAlert ? 'text-white-50' : 'text-muted' }}">({{ $umur }})</span> @endif
                                </td>
                                <td>{{ $data->unit_poli ?? 'Poli Perawatan Luka Modern' }}</td>
                                <td>{{ $data->p_jawab ?? '-' }}</td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 180px;" title="{{ $data->almt_pj ?? $data->alamat_lengkap }}">
                                        {{ $data->almt_pj ?? $data->alamat_lengkap ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $data->hubunganpj ?? 'DIRI SENDIRI' }}</td>
                                <td class="text-end font-monospace">40,000</td>
                                <td class="text-center">
                                    <span class="badge {{ $isRedAlert ? 'badge-light text-danger' : 'badge-primary' }} px-2">
                                        {{ strtoupper($data->jenis_bayar ?? 'UMUM') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ ($data->status_bayar ?? 'Belum') == 'Sudah' ? 'badge-success' : 'badge-warning text-dark' }} px-2">
                                        {{ $data->status_bayar ?? 'Belum' }}
                                    </span>
                                    @if (!empty($data->no_nota))
                                        <div class="font-monospace text-muted" style="font-size: 10px;">{{ $data->no_nota }}</div>
                                    @endif
                                </td>
                                <td class="font-monospace">{{ $data->no_rawat }}</td>
                                <td>{{ $data->tanggal_masuk ?? date('Y-m-d') }}</td>
                                <td>{{ $data->jam_masuk ?? '-' }}</td>
                                <td class="text-center font-monospace fw-bold">{{ $data->no_reg ?? sprintf('%03d', $loop->iteration) }}</td>
                                <td class="text-center">{{ $data->status_periksa ?? 'Belum Bayar' }}</td>
                                <td class="text-center">{{ $data->status_poli ?? 'Baru' }}</td>
                                <td>
                                    @if ($data->no_telpon)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $data->no_telpon) }}" target="_blank" class="{{ $isRedAlert ? 'text-white text-decoration-underline' : 'text-success font-weight-bold text-decoration-none' }}">
                                            <i class="fab fa-whatsapp mr-1"></i>{{ $data->no_telpon }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $data->no_sep ?? '-' }}</td>
                                <td>{{ $data->potensi_prb ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="20" class="text-center py-5 text-muted">
                                    <i class="fas fa-procedures fa-2x mb-2 d-block opacity-50"></i>
                                    Tidak ada data kunjungan / registrasi rawat jalan pada filter periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TOOLBAR BAWAH GAYA SIMRS KHANZA (PERSIS GAMBAR LAMPIRAN) -->
        <div class="card-footer bg-white border-top py-2 px-3">
            <form action="{{ route('rawatjalan.index') }}" method="GET" id="filterKhanzaForm">
                <!-- Baris 1: Filter Periode, Status Periksa, Status Bayar -->
                <div class="row g-2 align-items-center mb-2">
                    <div class="col-md-5 d-flex align-items-center gap-2">
                        <label class="small text-secondary fw-semibold mb-0" style="min-width: 60px;">Periode :</label>
                        <input type="date" name="from" value="{{ request('from', date('Y-m-d')) }}" class="form-control form-control-sm" style="border-radius: 6px; max-width: 145px;">
                        <span class="small text-muted">s.d</span>
                        <input type="date" name="to" value="{{ request('to', date('Y-m-d')) }}" class="form-control form-control-sm" style="border-radius: 6px; max-width: 145px;">
                    </div>
                    <div class="col-md-3 d-flex align-items-center gap-2">
                        <label class="small text-secondary fw-semibold mb-0" style="min-width: 95px;">Status Periksa :</label>
                        <select name="status_poli" class="form-select form-select-sm" style="border-radius: 6px;" onchange="document.getElementById('filterKhanzaForm').submit()">
                            <option value="Semua" {{ request('status_poli') == 'Semua' ? 'selected' : '' }}>Semua</option>
                            <option value="Baru" {{ request('status_poli') == 'Baru' ? 'selected' : '' }}>Baru</option>
                            <option value="Lama" {{ request('status_poli') == 'Lama' ? 'selected' : '' }}>Lama</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-center gap-2">
                        <label class="small text-secondary fw-semibold mb-0" style="min-width: 85px;">Status Bayar :</label>
                        <select name="status_bayar" class="form-select form-select-sm" style="border-radius: 6px;" onchange="document.getElementById('filterKhanzaForm').submit()">
                            <option value="Semua" {{ request('status_bayar') == 'Semua' ? 'selected' : '' }}>Semua</option>
                            <option value="Sudah" {{ request('status_bayar') == 'Sudah' ? 'selected' : '' }}>Sudah Bayar</option>
                            <option value="Belum" {{ request('status_bayar') == 'Belum' ? 'selected' : '' }}>Belum Bayar</option>
                        </select>
                    </div>
                </div>

                <!-- Baris 2: Input Dokter & Poliklinik dengan tombol Pensil -->
                <div class="row g-2 align-items-center mb-2">
                    <div class="col-md-6 d-flex align-items-center">
                        <label class="small text-secondary fw-semibold mb-0 text-nowrap mr-2" style="width: 75px; min-width: 75px;">Dokter :</label>
                        <div class="input-group input-group-sm flex-nowrap">
                            <input type="text" name="kd_dokter" id="filter_kd_dokter" value="{{ request('kd_dokter') }}" class="form-control font-monospace bg-light border-end-0" style="max-width: 85px; border-radius: 6px 0 0 6px;" placeholder="Kode" readonly>
                            <input type="text" id="filter_nm_dokter" class="form-control bg-white border-start-0 border-end-0" placeholder="Semua Dokter (Pilih...)" readonly>
                            <button type="button" class="btn btn-light border border-start-0" data-remodal-target="modalMasterDokter" style="border-radius: 0 6px 6px 0;">
                                <i class="fas fa-pencil-alt text-secondary"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <label class="small text-secondary fw-semibold mb-0 text-nowrap mr-2" style="width: 75px; min-width: 75px;">Poliklinik :</label>
                        <div class="input-group input-group-sm flex-nowrap">
                            <input type="text" name="kd_poli" id="filter_kd_poli" value="{{ request('kd_poli') }}" class="form-control font-monospace bg-light border-end-0" style="max-width: 75px; border-radius: 6px 0 0 6px;" placeholder="Kode" readonly>
                            <input type="text" id="filter_nm_poli" class="form-control bg-white border-start-0 border-end-0" placeholder="Semua Poli (Pilih...)" readonly>
                            <button type="button" class="btn btn-light border border-start-0" data-remodal-target="modalMasterPoli" style="border-radius: 0 6px 6px 0;">
                                <i class="fas fa-pencil-alt text-secondary"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Baris 3: Key Word, Centang, Reset, Record, Cetak, Status SEP -->
                <div class="row align-items-center pt-2 border-top g-2">
                    <div class="col-auto">
                        <label class="small text-secondary fw-semibold mb-0 text-nowrap">Key Word :</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control form-control-sm" placeholder="Cari nama, RM, no rawat, no telp..." style="width: 250px; border-radius: 6px;">
                    </div>
                    <div class="col-auto d-flex align-items-center gap-1">
                        <button type="submit" class="btn btn-sm btn-light border px-2 text-success fw-bold" style="border-radius: 6px; height: 31px;" title="Cari Data">
                            <i class="fas fa-check"></i>
                        </button>
                        <a href="{{ route('rawatjalan.index') }}" class="btn btn-sm btn-light border px-2 text-secondary" style="border-radius: 6px; height: 31px; display: inline-flex; align-items: center;" title="Reset Filter">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                    <div class="col-auto">
                        <span class="small font-monospace text-muted px-1 text-nowrap">Record : <strong>{{ count($pasien) }}</strong></span>
                    </div>
                    <div class="col-auto">
                        <!-- Tombol Cetak -->
                        <button type="button" class="btn btn-sm btn-light border px-3 fw-semibold shadow-2xs" onclick="window.print()" style="border-radius: 6px; height: 31px;">
                            <i class="fas fa-print text-secondary me-1"></i> Cetak
                        </button>
                    </div>
                    <div class="col-auto ms-auto ml-auto text-end">
                        <span class="badge bg-light text-secondary border px-2 py-1 small">
                            <i class="fas fa-check-circle text-success mr-1"></i> Belum SEP : 0
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- CONTEXT MENU KLIK KANAN (SIMRS KHANZA STYLE) -->
    <div id="customContextMenu" class="dropdown-menu shadow-lg border p-0" style="display: none; position: absolute; z-index: 99999; min-width: 250px; max-height: 360px; overflow-y: auto; overflow-x: hidden; border-radius: 8px;">
        <div class="px-3 py-2 bg-light border-bottom sticky-top" style="z-index: 10;">
            <div class="font-weight-bold text-dark text-truncate" id="ctxNamaPasien">Nama Pasien</div>
            <small class="text-muted">No. Rawat: <span id="ctxNoRawat" class="font-weight-bold text-primary">-</span> &bull; RM: <span id="ctxNoRM" class="font-weight-bold text-success">-</span></small>
        </div>
        <div class="p-1">
            <div class="dropdown-header text-uppercase text-muted font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">
                <i class="fas fa-flask mr-1 text-info"></i> Tindakan Laboratorium
            </div>
            <a class="dropdown-item py-2 d-flex align-items-center text-dark" id="ctxLinkPermintaanLab" href="#">
                <div class="rounded bg-info text-white p-1 mr-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 11px;">
                    <i class="fas fa-file-medical-alt"></i>
                </div>
                <div>
                    <div class="font-weight-bold" style="font-size: 13px;">Permintaan Lab</div>
                    <small class="text-muted" style="font-size: 10.5px;">Order paket & tindakan lab</small>
                </div>
            </a>
            <a class="dropdown-item py-2 d-flex align-items-center text-dark" id="ctxLinkPeriksaLab" href="#">
                <div class="rounded bg-success text-white p-1 mr-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 11px;">
                    <i class="fas fa-microscope"></i>
                </div>
                <div>
                    <div class="font-weight-bold" style="font-size: 13px;">Pengisian Hasil Lab</div>
                    <small class="text-muted" style="font-size: 10.5px;">Input nilai hasil template tindakan</small>
                </div>
            </a>

            <div class="dropdown-divider my-1"></div>

            <div class="dropdown-header text-uppercase text-muted font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">
                <i class="fas fa-file-invoice-dollar mr-1 text-success"></i> Billing & Rekam Medis
            </div>
            <a class="dropdown-item py-2 d-flex align-items-center text-dark" id="ctxLinkBilling" href="#">
                <div class="rounded bg-success text-white p-1 mr-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 11px;">
                    <i class="fas fa-cash-register"></i>
                </div>
                <div>
                    <div class="font-weight-bold" style="font-size: 13px;">Billing / Kasir Pasien</div>
                    <small class="text-muted" style="font-size: 10.5px;">Rincian tagihan & pembayaran lunas</small>
                </div>
            </a>
            <a class="dropdown-item py-2 d-flex align-items-center text-dark" id="ctxLinkDiagnosa" href="#">
                <div class="rounded bg-primary text-white p-1 mr-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 11px;">
                    <i class="fas fa-diagnoses"></i>
                </div>
                <div>
                    <div class="font-weight-bold" style="font-size: 13px;">Diagnosa Pasien (ICD-10)</div>
                    <small class="text-muted" style="font-size: 10.5px;">Input kode diagnosa primer/sekunder</small>
                </div>
            </a>
            <a class="dropdown-item py-2 d-flex align-items-center text-dark" id="ctxLinkResume" href="#">
                <div class="rounded bg-info text-white p-1 mr-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 11px;">
                    <i class="fas fa-file-medical"></i>
                </div>
                <div>
                    <div class="font-weight-bold" style="font-size: 13px;">Resume Medis Rawat Jalan</div>
                    <small class="text-muted" style="font-size: 10.5px;">Ringkasan pelayanan & cetak resume</small>
                </div>
            </a>

            <div class="dropdown-divider my-1"></div>

            <div class="dropdown-header text-uppercase text-muted font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">
                <i class="fas fa-notes-medical mr-1 text-primary"></i> Pelayanan Medis & Farmasi
            </div>
            <a class="dropdown-item py-2 d-flex align-items-center text-dark" id="ctxLinkSoap" href="#">
                <i class="fas fa-stethoscope text-primary mr-2" style="width: 20px;"></i>
                <span style="font-size: 13px;">Input SOAP Medis</span>
            </a>
            <a class="dropdown-item py-2 d-flex align-items-center text-dark" id="ctxLinkResep" href="#">
                <i class="fas fa-prescription text-success mr-2" style="width: 20px;"></i>
                <span style="font-size: 13px; font-weight: 600;">E-Resep Dokter (R/)</span>
            </a>
            <a class="dropdown-item py-2 d-flex align-items-center text-dark" id="ctxLinkTindakan" href="#">
                <i class="fas fa-procedures text-info mr-2" style="width: 20px;"></i>
                <span style="font-size: 13px; font-weight: 600;">Tindakan & Rawat Luka</span>
            </a>
            <a class="dropdown-item py-2 d-flex align-items-center text-dark" id="ctxLinkFarmasi" href="#">
                <i class="fas fa-pills text-danger mr-2" style="width: 20px;"></i>
                <span style="font-size: 13px; font-weight: 600;">Pembelian Obat / Resep</span>
            </a>
            <a class="dropdown-item py-2 d-flex align-items-center text-dark" id="ctxLinkDetailSoap" href="#">
                <i class="fas fa-history text-info mr-2" style="width: 20px;"></i>
                <span style="font-size: 13px;">Riwayat Rekam Medis (SOAP)</span>
            </a>
            <a class="dropdown-item py-2 d-flex align-items-center text-dark" id="ctxLinkDetailPasien" href="#">
                <i class="fas fa-id-card text-success mr-2" style="width: 20px;"></i>
                <span style="font-size: 13px;">Biodata Pasien</span>
            </a>

            <div class="dropdown-divider my-1"></div>

            <div class="dropdown-header text-uppercase text-muted font-weight-bold" style="font-size: 10px; letter-spacing: 0.5px;">
                <i class="fas fa-envelope-open-text mr-1 text-danger"></i> Surat - Surat Medis & Askep
            </div>
            <a class="dropdown-item py-2 d-flex align-items-center text-dark" id="ctxLinkAskep" href="#">
                <div class="rounded bg-info text-white p-1 mr-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 11px;">
                    <i class="fas fa-file-medical-alt"></i>
                </div>
                <div>
                    <div class="font-weight-bold text-info" style="font-size: 13px;">Asuhan Keperawatan (Askep)</div>
                    <small class="text-muted" style="font-size: 10.5px;">Formulir askep, diagnosa & intervensi</small>
                </div>
            </a>
            <a class="dropdown-item py-2 d-flex align-items-center text-dark" id="ctxLinkObservasi" href="#">
                <div class="rounded bg-primary text-white p-1 mr-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 11px;">
                    <i class="fas fa-notes-medical"></i>
                </div>
                <div>
                    <div class="font-weight-bold text-primary" style="font-size: 13px;">Lembar Observasi Khusus</div>
                    <small class="text-muted" style="font-size: 10.5px;">Observasi TTV, infus & luka berkala</small>
                </div>
            </a>
            <a class="dropdown-item py-2 d-flex align-items-center text-dark" id="ctxLinkBukuMerja" href="#">
                <div class="rounded bg-warning text-white p-1 mr-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 11px;">
                    <i class="fas fa-book"></i>
                </div>
                <div>
                    <div class="font-weight-bold text-warning" style="font-size: 13px;">Buku Merja Klien</div>
                    <small class="text-muted" style="font-size: 10.5px;">Registrasi rekam jejak rawat klien</small>
                </div>
            </a>
            <a class="dropdown-item py-2 d-flex align-items-center text-dark" id="ctxLinkSuratSakit" href="#">
                <div class="rounded bg-danger text-white p-1 mr-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 11px;">
                    <i class="fas fa-bed"></i>
                </div>
                <div>
                    <div class="font-weight-bold" style="font-size: 13px;">Bikin Surat Keterangan Sakit</div>
                    <small class="text-muted" style="font-size: 10.5px;">Auto isi data & tentukan lama istirahat</small>
                </div>
            </a>
            <a class="dropdown-item py-2 d-flex align-items-center text-dark" id="ctxLinkSuratSehat" href="#">
                <div class="rounded bg-success text-white p-1 mr-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 11px;">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div>
                    <div class="font-weight-bold" style="font-size: 13px;">Bikin Surat Keterangan Sehat</div>
                    <small class="text-muted" style="font-size: 10.5px;">Pemeriksaan fisik, TTV & kesimpulan</small>
                </div>
            </a>
        </div>
    </div>

    <!-- REMODAL POPUP MASTER DOKTER UNTUK FILTER -->
    <div class="remodal" data-remodal-id="modalMasterDokter" role="dialog" style="max-width: 800px; text-align: left; padding: 24px;">
        <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
        <div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary text-white p-2 rounded">
                        <i class="fas fa-user-md fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Filter Berdasarkan Dokter</h5>
                        <small class="text-muted">Pilih dokter untuk memfilter pasien rawat jalan</small>
                    </div>
                </div>
            </div>
            <div id="dokterListContainer" class="table-responsive border rounded" style="max-height: 320px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th width="120px">Kode Dokter</th>
                            <th>Nama Dokter</th>
                            <th>Spesialisasi</th>
                            <th class="text-center" width="90px">Pilih</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyFilterDokter">
                        <tr>
                            <td colspan="4" class="text-center py-3 text-muted"><i class="fas fa-spinner fa-spin mr-1"></i> Memuat master dokter...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-3 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" onclick="resetFilterDokter()">Reset Dokter (Semua)</button>
                <button data-remodal-action="cancel" class="btn btn-secondary px-4">Tutup</button>
            </div>
        </div>
    </div>

    <!-- REMODAL POPUP MASTER POLI UNTUK FILTER -->
    <div class="remodal" data-remodal-id="modalMasterPoli" role="dialog" style="max-width: 750px; text-align: left; padding: 24px;">
        <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
        <div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-success text-white p-2 rounded">
                        <i class="fas fa-clinic-medical fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Filter Berdasarkan Poliklinik</h5>
                        <small class="text-muted">Pilih unit poli tujuan untuk memfilter data</small>
                    </div>
                </div>
            </div>
            <div class="table-responsive border rounded" style="max-height: 300px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th width="120px">Kode Poli</th>
                            <th>Nama Poliklinik</th>
                            <th class="text-center" width="90px">Pilih</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyFilterPoli">
                        <tr>
                            <td colspan="3" class="text-center py-3 text-muted"><i class="fas fa-spinner fa-spin mr-1"></i> Memuat master poli...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-3 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" onclick="resetFilterPoli()">Reset Poli (Semua)</button>
                <button data-remodal-action="cancel" class="btn btn-secondary px-4">Tutup</button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const contextMenu = document.getElementById('customContextMenu');
        const rows = document.querySelectorAll('.row-pasien');

        rows.forEach(row => {
            row.addEventListener('contextmenu', function (e) {
                e.preventDefault();

                const hashId = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const noRawat = this.getAttribute('data-norawat');
                const noRM = this.getAttribute('data-norm');

                // Isi header context menu
                document.getElementById('ctxNamaPasien').textContent = nama;
                document.getElementById('ctxNoRawat').textContent = noRawat;
                document.getElementById('ctxNoRM').textContent = noRM;

                // Set Link URL
                document.getElementById('ctxLinkPermintaanLab').href = "{{ url('/laboratorium/permintaan') }}/" + hashId;
                document.getElementById('ctxLinkPeriksaLab').href = "{{ url('/laboratorium/periksa') }}/" + hashId;
                document.getElementById('ctxLinkBilling').href = "{{ url('/pelayanan/billing') }}/" + hashId;
                document.getElementById('ctxLinkDiagnosa').href = "{{ url('/pelayanan/diagnosa') }}/" + hashId;
                document.getElementById('ctxLinkResume').href = "{{ url('/pelayanan/resume') }}/" + hashId;
                document.getElementById('ctxLinkSoap').href = "{{ url('/soap') }}/" + hashId;
                document.getElementById('ctxLinkResep').href = "{{ url('/pelayanan/resep') }}/" + hashId;
                document.getElementById('ctxLinkTindakan').href = "{{ url('/pelayanan/tindakan') }}/" + hashId;
                document.getElementById('ctxLinkFarmasi').href = "{{ url('/farmasi/pembelian') }}/" + hashId;
                document.getElementById('ctxLinkDetailSoap').href = "{{ url('/soapPasienDetail') }}/" + hashId;
                document.getElementById('ctxLinkDetailPasien').href = "{{ url('/detailPasien') }}/" + hashId;
                document.getElementById('ctxLinkSuratSakit').href = "{{ route('surat.sakit.index') }}?pasien_id=" + hashId;
                document.getElementById('ctxLinkSuratSehat').href = "{{ route('surat.sehat.index') }}?pasien_id=" + hashId;
                document.getElementById('ctxLinkAskep').href = "{{ route('surat.askep.index') }}?pasien_id=" + hashId;
                document.getElementById('ctxLinkObservasi').href = "{{ route('surat.observasi.index') }}?pasien_id=" + hashId;
                document.getElementById('ctxLinkBukuMerja').href = "{{ route('surat.bukumerja.index') }}?pasien_id=" + hashId;

                // Tandai baris terpilih
                rows.forEach(r => r.classList.remove('table-primary'));
                this.classList.add('table-primary');

                // Tentukan tinggi maksimum dan buka tepat di bawah kursor (tanpa menimpa baris ke atas)
                contextMenu.style.visibility = 'hidden';
                contextMenu.style.display = 'block';

                const menuWidth = 260;
                let posX = e.pageX;
                if (posX + menuWidth > window.innerWidth) {
                    posX = window.innerWidth - menuWidth - 20;
                }

                // Posisi Y persis di bawah kursor klik
                let posY = e.pageY + 2;

                // Batasi tinggi dropdown agar pas dengan sisa ruang layar bawah
                const ruangBawah = window.innerHeight - e.clientY - 20;
                const batasTinggi = Math.min(360, Math.max(220, ruangBawah));
                contextMenu.style.maxHeight = batasTinggi + 'px';

                contextMenu.style.left = posX + 'px';
                contextMenu.style.top = posY + 'px';
                contextMenu.style.visibility = 'visible';
            });
        });

        // Sembunyikan context menu saat klik di luar
        document.addEventListener('click', function (e) {
            if (!contextMenu.contains(e.target)) {
                contextMenu.style.display = 'none';
            }
        });

        // Load data Master Dokter & Poli via AJAX untuk popup filter
        $.getJSON("{{ route('api.master.dokter') }}", function(data) {
            var html = '';
            $.each(data, function(i, doc) {
                html += '<tr>' +
                    '<td><span class="badge bg-light text-primary border font-monospace px-2 py-1">' + doc.kd_dokter + '</span></td>' +
                    '<td class="fw-bold">' + doc.nm_dokter + '</td>' +
                    '<td><small class="text-muted">' + (doc.spesialis || '-') + '</small></td>' +
                    '<td class="text-center"><button type="button" class="btn btn-sm btn-primary px-3 fw-bold" onclick="selectFilterDokter(\'' + doc.kd_dokter + '\', \'' + doc.nm_dokter + '\')"><i class="fas fa-check"></i></button></td>' +
                '</tr>';
            });
            $('#tbodyFilterDokter').html(html || '<tr><td colspan="4" class="text-center py-2">Tidak ada data dokter</td></tr>');
        });

        $.getJSON("{{ route('api.master.poli') }}", function(data) {
            var html = '';
            $.each(data, function(i, poli) {
                html += '<tr>' +
                    '<td><span class="badge bg-light text-success border font-monospace px-2 py-1">' + poli.kd_poli + '</span></td>' +
                    '<td class="fw-bold">' + poli.nm_poli + '</td>' +
                    '<td class="text-center"><button type="button" class="btn btn-sm btn-success px-3 fw-bold" onclick="selectFilterPoli(\'' + poli.kd_poli + '\', \'' + poli.nm_poli + '\')"><i class="fas fa-check"></i></button></td>' +
                '</tr>';
            });
            $('#tbodyFilterPoli').html(html || '<tr><td colspan="3" class="text-center py-2">Tidak ada data poliklinik</td></tr>');
        });
    });

    function selectFilterDokter(kd, nm) {
        $('#filter_kd_dokter').val(kd);
        $('#filter_nm_dokter').val(nm);
        $('[data-remodal-id=modalMasterDokter]').remodal().close();
        document.getElementById('filterKhanzaForm').submit();
    }

    function resetFilterDokter() {
        $('#filter_kd_dokter').val('');
        $('#filter_nm_dokter').val('');
        $('[data-remodal-id=modalMasterDokter]').remodal().close();
        document.getElementById('filterKhanzaForm').submit();
    }

    function selectFilterPoli(kd, nm) {
        $('#filter_kd_poli').val(kd);
        $('#filter_nm_poli').val(nm);
        $('[data-remodal-id=modalMasterPoli]').remodal().close();
        document.getElementById('filterKhanzaForm').submit();
    }

    function resetFilterPoli() {
        $('#filter_kd_poli').val('');
        $('#filter_nm_poli').val('');
        $('[data-remodal-id=modalMasterPoli]').remodal().close();
        document.getElementById('filterKhanzaForm').submit();
    }
</script>
@endsection
