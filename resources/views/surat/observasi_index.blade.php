@extends('layout.layout')
@section('title', 'Lembar Observasi Khusus Pasien')
@section('hide_content_header', 'true')

@section('konten')
<div class="container-fluid px-2 pt-2 pb-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-2 shadow-2xs" role="alert" style="font-size: 12px;">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close p-2" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- FORM INPUT LEMBAR OBSERVASI KHUSUS -->
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 6px;">
        <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="font-monospace fw-bold text-danger" style="font-size: 13px;">
                <i class="fas fa-heartbeat mr-1"></i> :: [ LEMBAR OBSERVASI KHUSUS PASIEN ] ::
            </span>
            <div class="d-flex gap-2">
                <a href="{{ route('surat.index') }}" class="btn btn-xs btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Menu Persuratan
                </a>
            </div>
        </div>
        <div class="card-body p-3 bg-white">
            @if(isset($selectedPasien) && $selectedPasien)
                <div class="alert alert-info py-2 px-3 mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-user-check mr-1"></i> Pasien Terpilih dari Rawat Jalan: 
                        <strong>{{ $selectedPasien->nama_lengkap }}</strong> 
                        (No. Rawat: <span class="font-monospace fw-bold">{{ $selectedPasien->no_rawat }}</span>, 
                        No. RM: <span class="font-monospace fw-bold">{{ $selectedPasien->no_rkm_medis }}</span>)
                    </div>
                    <a href="{{ route('surat.observasi.index') }}" class="btn btn-xs btn-outline-secondary">Reset / Buat Baru</a>
                </div>
            @endif

            <form action="{{ route('surat.observasi.store') }}" method="POST">
                @csrf
                <input type="hidden" name="pasien_id" value="{{ $selectedPasien->id ?? '' }}">

                <!-- 1. IDENTITAS -->
                <div class="p-2 mb-2 rounded bg-light border">
                    <span class="small font-weight-bold text-dark text-uppercase d-block mb-2">1. Identitas Pasien</span>
                    <div class="row g-2 mb-2">
                        <div class="col-md-3">
                            <label class="small text-secondary mb-0">Nomor Rawat :</label>
                            <input type="text" name="no_rawat" class="form-control form-control-sm font-monospace" value="{{ $selectedPasien->no_rawat ?? '' }}" placeholder="00001/11/11/2026">
                        </div>
                        <div class="col-md-3">
                            <label class="small text-secondary mb-0">Nomor RM :</label>
                            <input type="text" name="no_rkm_medis" class="form-control form-control-sm font-monospace" value="{{ $selectedPasien->no_rkm_medis ?? '' }}" placeholder="000001">
                        </div>
                        <div class="col-md-4">
                            <label class="small text-secondary mb-0">Nama Pasien :</label>
                            <input type="text" name="nama_pasien" class="form-control form-control-sm fw-bold" value="{{ $selectedPasien->nama_lengkap ?? '' }}" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="col-md-2">
                            <label class="small text-secondary mb-0">Jenis Kelamin :</label>
                            <select name="jenis_kelamin" class="form-select form-select-sm">
                                <option value="Laki-laki" {{ (isset($selectedPasien) && in_array(strtolower($selectedPasien->jenis_kelamin ?? ''), ['laki-laki', 'l'])) ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ (isset($selectedPasien) && in_array(strtolower($selectedPasien->jenis_kelamin ?? ''), ['perempuan', 'p'])) ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-2">
                            <label class="small text-secondary mb-0">Umur :</label>
                            <input type="text" name="umur" class="form-control form-control-sm" placeholder="Contoh: 45 Th">
                        </div>
                        <div class="col-md-10">
                            <label class="small text-secondary mb-0">Alamat :</label>
                            <input type="text" name="alamat" class="form-control form-control-sm" value="{{ $selectedPasien->alamat_lengkap ?? '' }}" placeholder="Alamat Tempat Tinggal">
                        </div>
                    </div>
                </div>

                <!-- 2. WAKTU OBSERVASI & TANDA VITAL -->
                <div class="p-2 mb-2 rounded bg-light border">
                    <span class="small font-weight-bold text-dark text-uppercase d-block mb-2">2. Parameter Pemantauan & Tanda Vital</span>
                    <div class="row g-2 mb-2">
                        <div class="col-md-3">
                            <label class="small text-secondary mb-0">Tanggal Observasi :</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-2">
                            <label class="small text-secondary mb-0">Jam :</label>
                            <input type="time" name="jam" value="{{ date('H:i') }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="small text-secondary mb-0">Tingkat Kesadaran :</label>
                            <input type="text" name="kesadaran" value="Compos Mentis" class="form-control form-control-sm" placeholder="Compos Mentis / Somnolen / Apatis">
                        </div>
                        <div class="col-md-3">
                            <label class="small text-secondary mb-0">GCS (E-M-V) :</label>
                            <input type="text" name="gcs" value="E4M6V5 (15)" class="form-control form-control-sm" placeholder="E4M6V5">
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-2">
                            <label class="small text-secondary mb-0">Tekanan Darah (TD) :</label>
                            <input type="text" name="td" class="form-control form-control-sm" placeholder="120/80 mmHg">
                        </div>
                        <div class="col-md-2">
                            <label class="small text-secondary mb-0">Nadi / HR :</label>
                            <input type="text" name="hr" class="form-control form-control-sm" placeholder="80 x/mnt">
                        </div>
                        <div class="col-md-2">
                            <label class="small text-secondary mb-0">Respirasi / RR :</label>
                            <input type="text" name="rr" class="form-control form-control-sm" placeholder="20 x/mnt">
                        </div>
                        <div class="col-md-2">
                            <label class="small text-secondary mb-0">Suhu Tubuh :</label>
                            <input type="text" name="suhu" class="form-control form-control-sm" placeholder="36.5 &deg;C">
                        </div>
                        <div class="col-md-2">
                            <label class="small text-secondary mb-0">SpO2 :</label>
                            <input type="text" name="spo2" class="form-control form-control-sm" placeholder="98 %">
                        </div>
                        <div class="col-md-2">
                            <label class="small text-secondary mb-0">Urine Output :</label>
                            <input type="text" name="urine_output" class="form-control form-control-sm" placeholder="Contoh: 300 cc">
                        </div>
                    </div>
                </div>

                <!-- 3. PEMANTAUAN CAIRAN & LUKA -->
                <div class="p-2 mb-3 rounded bg-light border">
                    <span class="small font-weight-bold text-dark text-uppercase d-block mb-2">3. Observasi Klinis, Cairan, & Catatan Tindakan</span>
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="small text-secondary mb-0">Cairan Infus / Terapi :</label>
                            <input type="text" name="cairan_infus" class="form-control form-control-sm" placeholder="Contoh: NaCl 0.9% / RL 500ml">
                        </div>
                        <div class="col-md-6">
                            <label class="small text-secondary mb-0">Tetesan Infus (TPM) :</label>
                            <input type="text" name="tetesan_infus" class="form-control form-control-sm" placeholder="Contoh: 20 tetes/menit makro">
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="small text-secondary mb-0">Kondisi Luka / Area Tindakan :</label>
                            <textarea name="kondisi_luka" rows="2" class="form-control form-control-sm" placeholder="Eksudat, warna dasar luka, jaringan nekrotik, perdarahan rembes..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-secondary mb-0">Tindakan / Terapi Yang Diberikan :</label>
                            <textarea name="tindakan_terapi" rows="2" class="form-control form-control-sm" placeholder="Balut tekan, pembersihan luka NaCl, injeksi analgetik..."></textarea>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-12">
                            <label class="small text-secondary mb-0">Catatan Perkembangan Pasien :</label>
                            <textarea name="catatan_perkembangan" rows="2" class="form-control form-control-sm" placeholder="Respon pasien pasca tindakan, observasi tanda-tanda syok / alergi..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-sm btn-danger px-4 fw-bold shadow-sm">
                        <i class="fas fa-save mr-1"></i> Simpan Lembar Observasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL REKAP OBSERVASI -->
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 6px;">
        <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="font-weight-bold text-dark" style="font-size: 13px;">
                <i class="fas fa-list text-danger mr-1"></i> RIWAYAT LEMBAR OBSERVASI KHUSUS
            </span>
            <form method="GET" class="d-flex align-items-center">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm mr-1" placeholder="Cari Nama Pasien / No. Rawat..." style="width: 220px;">
                <button type="submit" class="btn btn-xs btn-primary px-2"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0 text-nowrap" style="font-size: 11.5px;">
                    <thead class="bg-light">
                        <tr>
                            <th>Waktu</th>
                            <th>No. Rawat</th>
                            <th>Nama Pasien</th>
                            <th>Kesadaran / GCS</th>
                            <th>Tanda Vital (TD / Nadi / Suhu / SpO2)</th>
                            <th>Kondisi Luka & Terapi</th>
                            <th>Petugas</th>
                            <th width="90px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($row->tanggal)) }} {{ substr($row->jam, 0, 5) }}</td>
                                <td class="font-monospace text-primary fw-bold">{{ $row->no_rawat ?: '-' }}</td>
                                <td class="fw-bold text-dark">{{ $row->nama_pasien }}</td>
                                <td>{{ $row->kesadaran }} ({{ $row->gcs ?: '-' }})</td>
                                <td>
                                    <span class="badge bg-light border text-dark">{{ $row->td ?: '-' }}</span>
                                    <span class="badge bg-light border text-dark">{{ $row->hr ?: '-' }}</span>
                                    <span class="badge bg-light border text-dark">{{ $row->suhu ?: '-' }}</span>
                                    <span class="badge bg-light border text-success">{{ $row->spo2 ?: '-' }}</span>
                                </td>
                                <td><span class="d-inline-block text-truncate" style="max-width: 180px;" title="{{ $row->kondisi_luka }}">{{ $row->kondisi_luka ?: '-' }}</span></td>
                                <td><small class="text-muted">{{ $row->petugas }}</small></td>
                                <td class="text-center">
                                    <a href="{{ route('surat.observasi.cetak', $row->id) }}" target="_blank" class="btn btn-xs btn-outline-danger px-2 mr-1" title="Cetak Lembar Observasi">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                    <a href="{{ route('surat.observasi.destroy', $row->id) }}" onclick="return confirm('Hapus lembar observasi ini?')" class="btn btn-xs btn-outline-secondary px-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada riwayat lembar observasi khusus.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light py-2 px-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">Total Data: {{ $data->total() }} lembar observasi</small>
            {{ $data->links() }}
        </div>
    </div>
</div>
@endsection
