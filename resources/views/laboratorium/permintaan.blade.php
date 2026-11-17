@extends('layout.layout')
@section('title', 'Permintaan Pemeriksaan Laboratorium')

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

    <!-- IDENTITAS PASIEN & KEMBALI -->
    <div class="card card-outline card-info shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="d-flex align-items-center mb-2 mb-md-0">
                    <div class="clinic-header-icon mr-3 bg-info text-white">
                        <i class="fas fa-vial"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 font-weight-bold text-dark">{{ $pasien->nama_lengkap }}</h5>
                        <small class="text-muted">
                            No. Rawat: <strong class="text-primary">{{ $pasien->no_rawat }}</strong> &bull; 
                            No. RM: <strong class="badge-rm">{{ $pasien->no_rkm_medis ?? '-' }}</strong> &bull; 
                            JK/Umur: {{ $pasien->jenis_kelamin }} ({{ $pasien->umur ?? '-' }})
                        </small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('lab.periksa', $hashId) }}" class="btn btn-sm btn-success mr-2 font-weight-bold">
                        <i class="fas fa-microscope mr-1"></i> Langsung Isi Hasil Lab
                    </a>
                    <a href="{{ url('/') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Antrian
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- FORM PERMINTAAN LAB -->
        <div class="col-lg-7">
            <div class="card card-outline card-primary shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 font-weight-bold text-primary">
                        <i class="fas fa-file-medical-alt mr-2"></i> Buat Formulir Permintaan Laboratorium
                    </h6>
                </div>
                <form action="{{ route('lab.permintaan.simpan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="no_rawat" value="{{ $pasien->no_rawat }}">

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold text-muted">Nomor Order Permintaan</label>
                                <input type="text" name="noorder" class="form-control font-weight-bold bg-light" value="{{ $noorderBaru }}" readonly>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold text-muted">Tanggal & Jam Order</label>
                                <div class="input-group input-group-sm">
                                    <input type="date" name="tgl_permintaan" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    <input type="time" name="jam_permintaan" class="form-control" value="{{ date('H:i') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold text-muted">Dokter / Perawat Perujuk</label>
                                <input type="text" name="dokter_perujuk" class="form-control form-control-sm" value="Welly Prestikasari, S.Kep., Ners., M.Kep.">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold text-muted">Diagnosa Klinis</label>
                                <input type="text" name="diagnosa_klinis" class="form-control form-control-sm" placeholder="Contoh: Ulkus Diabetikum Grade II" value="{{ $pasien->keluhan_utama ?? '' }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold text-muted">Informasi Tambahan / Instruksi Khusus</label>
                            <input type="text" name="informasi_tambahan" class="form-control form-control-sm" placeholder="Contoh: Sampel swab luka kanan, puasa 10 jam">
                        </div>

                        <hr>
                        <label class="font-weight-bold text-dark mb-2">
                            <i class="fas fa-check-square text-success mr-1"></i> Pilih Paket / Pemeriksaan Laboratorium:
                        </label>
                        <div class="table-responsive border rounded">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="40px" class="text-center">#</th>
                                        <th width="100px">Kode</th>
                                        <th>Nama Paket Pemeriksaan</th>
                                        <th>Kategori</th>
                                        <th class="text-right">Tarif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jenisPerawatan as $jp)
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" name="kd_jenis_prw[]" value="{{ $jp->kd_jenis_prw }}" id="chk_{{ $jp->kd_jenis_prw }}" style="transform: scale(1.2); cursor: pointer;">
                                            </td>
                                            <td>
                                                <label for="chk_{{ $jp->kd_jenis_prw }}" class="mb-0 font-monospace font-weight-bold text-muted" style="cursor: pointer;">
                                                    {{ $jp->kd_jenis_prw }}
                                                </label>
                                            </td>
                                            <td>
                                                <label for="chk_{{ $jp->kd_jenis_prw }}" class="mb-0 font-weight-bold text-dark d-block" style="cursor: pointer;">
                                                    {{ $jp->nm_perawatan }}
                                                </label>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ $jp->kategori }}</span>
                                            </td>
                                            <td class="text-right font-weight-bold text-success">
                                                Rp {{ number_format($jp->total_byr, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-white text-right">
                        <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                            <i class="fas fa-paper-plane mr-1"></i> Kirim Permintaan Lab
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- RIWAYAT PERMINTAAN LAB -->
        <div class="col-lg-5">
            <div class="card card-outline card-secondary shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="fas fa-history mr-2 text-info"></i> Riwayat Permintaan Lab Pasien Ini
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>No. Order</th>
                                    <th>Tgl & Jam</th>
                                    <th>Status</th>
                                    <th class="text-center">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayatPermintaan as $rp)
                                    <tr>
                                        <td>
                                            <span class="font-weight-bold font-monospace text-primary">{{ $rp->noorder }}</span>
                                            <div class="small text-muted">{{ $rp->diagnosa_klinis }}</div>
                                        </td>
                                        <td class="small">
                                            <div>{{ date('d/m/Y', strtotime($rp->tgl_permintaan)) }}</div>
                                            <span class="text-muted">{{ $rp->jam_permintaan }}</span>
                                        </td>
                                        <td>
                                            @if ($rp->status_order == 'Selesai')
                                                <span class="badge badge-success">Selesai</span>
                                            @else
                                                <span class="badge badge-warning text-dark">Menunggu</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('lab.periksa', $hashId) }}?noorder={{ $rp->noorder }}" class="btn btn-xs btn-primary font-weight-bold" title="Input / Lihat Hasil">
                                                <i class="fas fa-edit mr-1"></i> Input Hasil
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted small">
                                            <i class="fas fa-info-circle mr-1"></i> Belum ada riwayat permintaan lab.
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

