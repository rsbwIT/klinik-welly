@extends('layout.layout')
@section('title', 'Riwayat Perawatan Pasien - ' . $pasien->nama_lengkap)

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
    @if (Session::has('successDelete'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-trash-alt mr-2"></i>
            <div>
                <strong>Dihapus!</strong> {{ Session::get('successDelete') }}
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Card Header Info Pasien SOAP -->
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="d-flex align-items-center mb-2 mb-md-0">
                    <div class="clinic-header-icon mr-3">
                        <i class="fas fa-history"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge badge-light border font-monospace">{{ $pasien->no_rawat }}</span>
                            @if (!empty($pasien->no_rkm_medis))
                                <span class="badge-rm ml-1">No. RM: {{ $pasien->no_rkm_medis }}</span>
                            @endif
                        </div>
                        <h4 class="font-weight-bold mb-0 text-dark">{{ $pasien->nama_lengkap }}</h4>
                        <small class="text-muted">
                            <i class="fas fa-venus-mars mr-1"></i>{{ $pasien->jenis_kelamin ?? '-' }} &bull; 
                            <i class="fas fa-tint text-danger mr-1"></i>Gol: {{ $pasien->gol_darah ?? '-' }} &bull; 
                            <i class="fas fa-phone mr-1"></i>{{ $pasien->no_telpon ?? '-' }} &bull;
                            <i class="fas fa-map-marker-alt mr-1"></i>{{ $pasien->alamat_lengkap ?? '-' }}
                        </small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('soap', $hashid->encodeHex($pasien->id)) }}" class="btn btn-primary font-weight-bold mr-2 shadow-sm">
                        <i class="fas fa-plus-circle mr-1"></i> Input SOAP Baru
                    </a>
                    <a href="{{ route('lab.periksa', $hashid->encodeHex($pasien->id)) }}" class="btn btn-success font-weight-bold mr-2 shadow-sm">
                        <i class="fas fa-microscope mr-1"></i> Periksa Lab
                    </a>
                    <a href="{{ route('farmasi.index', $hashid->encodeHex($pasien->id)) }}" class="btn btn-warning font-weight-bold text-dark mr-2 shadow-sm">
                        <i class="fas fa-pills mr-1"></i> Beli Obat / Resep
                    </a>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Antrean
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB NAVIGASI REKAM MEDIS ALA SIMRS KHANZA -->
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header p-2 bg-white">
            <ul class="nav nav-tabs card-header-tabs border-bottom-0 font-weight-bold" id="khanzaRecordTabs" role="tablist" style="font-size: 13.5px;">
                <li class="nav-item">
                    <a class="nav-link active text-primary" id="tab-kunjungan-btn" data-toggle="tab" href="#tab-kunjungan" role="tab" aria-controls="tab-kunjungan" aria-selected="true">
                        <i class="fas fa-calendar-check mr-1 text-info"></i> Riwayat Kunjungan
                        <span class="badge badge-info ml-1">{{ count($riwayatKunjungan ?? []) }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" id="tab-soap-btn" data-toggle="tab" href="#tab-soap" role="tab" aria-controls="tab-soap" aria-selected="false">
                        <i class="fas fa-notes-medical mr-1 text-primary"></i> Riwayat S.O.A.P.I.E
                        <span class="badge badge-primary ml-1">{{ count($soapPasien) }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" id="tab-lab-btn" data-toggle="tab" href="#tab-lab" role="tab" aria-controls="tab-lab" aria-selected="false">
                        <i class="fas fa-flask mr-1 text-success"></i> Riwayat Laboratorium
                        <span class="badge badge-success ml-1">{{ count($riwayatLab ?? []) }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" id="tab-obat-btn" data-toggle="tab" href="#tab-obat" role="tab" aria-controls="tab-obat" aria-selected="false">
                        <i class="fas fa-pills mr-1 text-danger"></i> Pembelian Obat
                        <span class="badge badge-danger ml-1">{{ count($riwayatObat ?? []) }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-muted" href="javascript:void(0)" onclick="alert('Modul Piutang Obat Pasien')">
                        <i class="fas fa-file-invoice-dollar mr-1 text-warning"></i> Piutang Obat
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-muted" href="javascript:void(0)" onclick="alert('Retensi Berkas Rekam Medis Elektronik Aktif')">
                        <i class="fas fa-archive mr-1 text-secondary"></i> Retensi Berkas
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body p-0">
            <div class="tab-content" id="khanzaTabContent">

                <!-- 1. TAB RIWAYAT KUNJUNGAN (PERSIS GAMBAR KHANZA) -->
                <div class="tab-pane fade show active" id="tab-kunjungan" role="tabpanel" aria-labelledby="tab-kunjungan-btn">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0" style="font-size: 13px;">
                            <thead class="thead-light">
                                <tr>
                                    <th width="40px" class="text-center">No.</th>
                                    <th>No. Rawat</th>
                                    <th>Tanggal</th>
                                    <th>Jam</th>
                                    <th>Kd.Dokter</th>
                                    <th>Dokter Dituju/DPJP</th>
                                    <th>Umur</th>
                                    <th>Poliklinik / Kamar</th>
                                    <th>Jenis Bayar</th>
                                    <th class="text-center" width="120px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $noK = 1; ?>
                                @forelse ($riwayatKunjungan as $kunjungan)
                                    <tr class="{{ $kunjungan->no_rawat == $pasien->no_rawat ? 'table-warning' : '' }}">
                                        <td class="text-center font-weight-bold text-muted">{{ $noK++ }}</td>
                                        <td class="font-monospace font-weight-bold text-primary">
                                            {{ $kunjungan->no_rawat }}
                                            @if($kunjungan->no_rawat == $pasien->no_rawat)
                                                <span class="badge badge-warning text-dark ml-1" style="font-size: 10px;">Aktif</span>
                                            @endif
                                        </td>
                                        <td>{{ $kunjungan->tanggal_masuk ?? '-' }}</td>
                                        <td>{{ $kunjungan->jam_masuk ?? '-' }}</td>
                                        <td class="font-monospace text-muted">D0000030</td>
                                        <td class="font-weight-bold text-dark">
                                            {{ $kunjungan->petugas ?: 'Welly Prestikasari, S.Kep., Ners., M.Kep.' }}
                                        </td>
                                        <td>
                                            @if(!empty($kunjungan->tgl_lahir))
                                                @php
                                                    $birthDate = new \DateTime($kunjungan->tgl_lahir);
                                                    $today = new \DateTime($kunjungan->tanggal_masuk ?: 'now');
                                                    $diff = $today->diff($birthDate);
                                                    echo $diff->y . ' Th';
                                                @endphp
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-light border text-dark font-weight-normal px-2 py-1">
                                                Poli Perawatan Luka Modern
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success px-2 py-1">UMUM</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('soap', $hashid->encodeHex($kunjungan->id)) }}" class="btn btn-outline-primary" title="Input SOAP Rawat Ini">
                                                    <i class="fas fa-stethoscope"></i>
                                                </a>
                                                <a href="{{ route('detailPasien', $hashid->encodeHex($kunjungan->id)) }}" class="btn btn-outline-info" title="Biodata Pasien">
                                                    <i class="fas fa-user"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4 text-muted">
                                            Belum ada riwayat kunjungan tercatat untuk pasien ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 2. TAB RIWAYAT S.O.A.P.I.E -->
                <div class="tab-pane fade" id="tab-soap" role="tabpanel" aria-labelledby="tab-soap-btn">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0" style="font-size: 13px;">
                            <thead class="thead-light">
                                <tr>
                                    <th width="40px" class="text-center">No</th>
                                    <th width="150px">Tgl & Jam Input</th>
                                    <th>Subjektif (S)</th>
                                    <th>Objektif (O)</th>
                                    <th>Asesmen (A)</th>
                                    <th>Plan & Terapi (P)</th>
                                    <th class="text-center" width="110px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $noS = 1; ?>
                                @forelse ($soapPasien as $data)
                                    <tr>
                                        <td class="text-center font-weight-bold text-muted">{{ $noS++ }}</td>
                                        <td>
                                            <div class="font-weight-bold text-dark small">
                                                <i class="far fa-calendar-alt mr-1 text-primary"></i>{{ $data->tanggal_input_soap ?? '-' }}
                                            </div>
                                            <small class="text-muted"><i class="far fa-clock mr-1"></i>{{ $data->jam_input_soap ?? '-' }} WIB</small>
                                            @if($data->no_rawat != $pasien->no_rawat)
                                                <div class="mt-1"><span class="badge badge-light border text-muted" style="font-size: 10px;">{{ $data->no_rawat }}</span></div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-dark small" style="max-width: 220px; white-space: pre-line;">{{ \Illuminate\Support\Str::limit($data->subjek ?? '-', 110) }}</div>
                                        </td>
                                        <td>
                                            <div class="text-dark small" style="max-width: 220px; white-space: pre-line;">
                                                @if($data->suhu || $data->tensi)
                                                    <span class="badge badge-light border text-dark mb-1">
                                                        T: {{ $data->tensi ?? '-' }} &bull; S: {{ $data->suhu ? $data->suhu.'°C' : '-' }} &bull; N: {{ $data->nadi ?? '-' }}
                                                    </span><br>
                                                @endif
                                                {{ \Illuminate\Support\Str::limit($data->objek ?? '-', 100) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-warning text-dark font-weight-bold px-2 py-1 small">
                                                {{ \Illuminate\Support\Str::limit($data->asesmen ?? 'Tanpa Diagnosa', 70) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-dark small" style="max-width: 200px;">
                                                {{ \Illuminate\Support\Str::limit($data->plan ?? '-', 90) }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('detailSoapPasien', $hashid->encodeHex($data->id)) }}" class="btn btn-sm btn-outline-primary font-weight-bold px-2" title="Lihat Lembar Rekam Medis">
                                                <i class="fas fa-eye mr-1"></i> Rincian
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fas fa-folder-open d-block mb-3 text-secondary" style="font-size: 2.5rem; opacity: 0.3;"></i>
                                            <h6 class="font-weight-bold mb-1">Belum Ada Riwayat Rekam Medis (SOAP)</h6>
                                            <p class="small mb-3">Pasien ini belum memiliki catatan riwayat tindakan SOAP di sistem.</p>
                                            <a href="{{ route('soap', $hashid->encodeHex($pasien->id)) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-stethoscope mr-1"></i> Input SOAP Pertama Sekarang
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. TAB RIWAYAT LABORATORIUM -->
                <div class="tab-pane fade" id="tab-lab" role="tabpanel" aria-labelledby="tab-lab-btn">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="font-size: 13px;">
                            <thead class="thead-light">
                                <tr>
                                    <th width="40px" class="text-center">No</th>
                                    <th>No. Rawat</th>
                                    <th>Tanggal & Jam</th>
                                    <th>Pemeriksaan</th>
                                    <th>Petugas Lab</th>
                                    <th>Biaya</th>
                                    <th class="text-center">Cetak Lembar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayatLab as $idx => $rh)
                                    @php
                                        $nm = \Illuminate\Support\Facades\DB::table('jns_perawatan_lab')->where('kd_jenis_prw', $rh->kd_jenis_prw)->value('nm_perawatan');
                                    @endphp
                                    <tr>
                                        <td class="text-center font-weight-bold text-muted">{{ $idx + 1 }}</td>
                                        <td class="font-monospace text-primary font-weight-bold">{{ $rh->no_rawat }}</td>
                                        <td>
                                            <strong>{{ date('d/m/Y', strtotime($rh->tgl_periksa)) }}</strong>
                                            <div class="small text-muted">{{ $rh->jam }} WIB</div>
                                        </td>
                                        <td>
                                            <span class="font-weight-bold text-dark">{{ $nm ?? $rh->kd_jenis_prw }}</span>
                                        </td>
                                        <td>{{ $rh->petugas_lab ?? '-' }}</td>
                                        <td class="font-weight-bold text-success">Rp {{ number_format($rh->biaya, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('lab.cetakHasil', $rh->id) }}" target="_blank" class="btn btn-sm btn-outline-primary font-weight-bold">
                                                <i class="fas fa-print mr-1"></i> Cetak Lembar Hasil
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            Belum ada riwayat hasil laboratorium tercatat untuk pasien ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 4. TAB RIWAYAT PEMBELIAN OBAT / FARMASI -->
                <div class="tab-pane fade" id="tab-obat" role="tabpanel" aria-labelledby="tab-obat-btn">
                    <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                        <span class="small font-weight-bold text-dark">
                            <i class="fas fa-pills text-danger mr-1"></i> Data Pembelian Obat & Balutan Luka Pasien
                        </span>
                        <a href="{{ route('farmasi.index', $hashid->encodeHex($pasien->id)) }}" class="btn btn-sm btn-success font-weight-bold shadow-sm">
                            <i class="fas fa-plus-circle mr-1"></i> Transaksi Obat / Resep Baru
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0" style="font-size: 13px;">
                            <thead class="thead-light">
                                <tr>
                                    <th width="40px" class="text-center">No</th>
                                    <th>No. Faktur</th>
                                    <th>No. Rawat</th>
                                    <th>Tanggal & Jam</th>
                                    <th>Petugas Farmasi</th>
                                    <th>Total Bayar</th>
                                    <th>Catatan</th>
                                    <th class="text-center" width="130px">Cetak Nota</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $noO = 1; ?>
                                @forelse ($riwayatObat as $ro)
                                    <tr>
                                        <td class="text-center font-weight-bold text-muted">{{ $noO++ }}</td>
                                        <td class="font-monospace text-primary font-weight-bold">{{ $ro->no_faktur }}</td>
                                        <td class="font-monospace">{{ $ro->no_rawat }}</td>
                                        <td>
                                            <strong>{{ date('d/m/Y', strtotime($ro->tgl_beli)) }}</strong>
                                            <div class="small text-muted">{{ $ro->jam_beli }} WIB</div>
                                        </td>
                                        <td>{{ $ro->petugas }}</td>
                                        <td class="font-weight-bold text-success">Rp {{ number_format($ro->total_bayar, 0, ',', '.') }}</td>
                                        <td>{{ $ro->catatan ?? '-' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('farmasi.cetakNota', $ro->no_faktur) }}" target="_blank" class="btn btn-sm btn-outline-primary font-weight-bold">
                                                <i class="fas fa-print mr-1"></i> Cetak Nota
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            Belum ada transaksi pembelian obat yang tercatat untuk pasien ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
