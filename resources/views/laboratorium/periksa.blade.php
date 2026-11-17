@extends('layout.layout')
@section('title', 'Pemeriksaan & Pengisian Hasil Laboratorium')

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

    <!-- IDENTITAS PASIEN -->
    <div class="card card-outline card-success shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <div class="d-flex align-items-center mb-2 mb-md-0">
                    <div class="clinic-header-icon mr-3 bg-success text-white">
                        <i class="fas fa-microscope"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 font-weight-bold text-dark">{{ $pasien->nama_lengkap }}</h5>
                        <small class="text-muted">
                            No. Rawat: <strong class="text-primary">{{ $pasien->no_rawat }}</strong> &bull; 
                            No. RM: <strong class="badge-rm">{{ $pasien->no_rkm_medis ?? '-' }}</strong> &bull; 
                            JK: {{ $pasien->jenis_kelamin }} ({{ $pasien->umur ?? '-' }})
                            @if ($noorder)
                                &bull; <span class="badge badge-warning text-dark font-monospace">Order: {{ $noorder }}</span>
                            @endif
                        </small>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('lab.permintaan', $hashId) }}" class="btn btn-sm btn-info mr-2">
                        <i class="fas fa-file-medical-alt mr-1"></i> Form Permintaan
                    </a>
                    <a href="{{ url('/') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Antrian
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM INPUT HASIL LAB DENGAN TEMPLATE TINDAKAN -->
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="mb-0 font-weight-bold text-primary">
                    <i class="fas fa-vial mr-2"></i> Form Pengisian Nilai Hasil Laboratorium (Template Sesuai Tindakan)
                </h6>
                <span class="badge badge-light border text-muted">
                    Sistem Otomatis Nilai Rujukan (Laki-laki / Perempuan)
                </span>
            </div>
        </div>

        <form action="{{ route('lab.periksa.simpan') }}" method="POST">
            @csrf
            <input type="hidden" name="no_rawat" value="{{ $pasien->no_rawat }}">
            @if ($noorder)
                <input type="hidden" name="noorder" value="{{ $noorder }}">
            @endif

            <div class="card-body">
                <!-- Info Petugas & Waktu Periksa -->
                <div class="row bg-light p-3 rounded mb-3 border">
                    <div class="col-md-3 form-group mb-2 mb-md-0">
                        <label class="small font-weight-bold text-muted">Tanggal Periksa</label>
                        <input type="date" name="tgl_periksa" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-2 form-group mb-2 mb-md-0">
                        <label class="small font-weight-bold text-muted">Jam</label>
                        <input type="time" name="jam_periksa" class="form-control form-control-sm" value="{{ date('H:i') }}" required>
                    </div>
                    <div class="col-md-4 form-group mb-2 mb-md-0">
                        <label class="small font-weight-bold text-muted">Dokter / Perawat Penanggung Jawab</label>
                        <input type="text" name="dokter_perujuk" class="form-control form-control-sm" value="Welly Prestikasari, S.Kep., Ners., M.Kep.">
                    </div>
                    <div class="col-md-3 form-group mb-0">
                        <label class="small font-weight-bold text-muted">Petugas Analis / Lab</label>
                        <input type="text" name="petugas_lab" class="form-control form-control-sm" value="{{ auth()->user()->name ?? 'Petugas Laboratorium' }}">
                    </div>
                </div>

                <!-- DAFTAR TINDAKAN LAB DENGAN TEMPLATE FORM MASING-MASING -->
                <div class="accordion" id="accordionLab">
                    @foreach ($jenisPerawatan as $index => $jp)
                        @php
                            $isSelected = !empty($orderPemeriksaan) ? in_array($jp->kd_jenis_prw, $orderPemeriksaan) : ($index === 0);
                            $itemTemplates = $templates[$jp->kd_jenis_prw] ?? collect([]);
                            $jk = $pasien->jenis_kelamin == 'Perempuan' ? 'P' : 'L';
                        @endphp

                        <div class="card border mb-3 shadow-none">
                            <div class="card-header bg-white py-2 d-flex align-items-center justify-content-between">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="kd_jenis_prw[]" value="{{ $jp->kd_jenis_prw }}" 
                                        class="custom-control-input chk-pemeriksaan" 
                                        id="periksa_{{ $jp->kd_jenis_prw }}"
                                        {{ $isSelected ? 'checked' : '' }}
                                        onchange="togglePemeriksaan('{{ $jp->kd_jenis_prw }}')">
                                    <label class="custom-control-label font-weight-bold text-dark" for="periksa_{{ $jp->kd_jenis_prw }}" style="cursor: pointer; font-size: 15px;">
                                        [{{ $jp->kd_jenis_prw }}] {{ $jp->nm_perawatan }}
                                        <span class="badge badge-info ml-2 font-weight-normal">{{ $jp->kategori }}</span>
                                    </label>
                                </div>
                                <div class="text-right">
                                    <span class="text-success font-weight-bold mr-3">Rp {{ number_format($jp->total_byr, 0, ',', '.') }}</span>
                                    <button class="btn btn-xs btn-outline-secondary" type="button" data-toggle="collapse" data-target="#collapse_{{ $jp->kd_jenis_prw }}">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>

                            <div id="collapse_{{ $jp->kd_jenis_prw }}" class="collapse {{ $isSelected ? 'show' : '' }}">
                                <div class="card-body p-3">
                                    @if ($itemTemplates->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered table-hover mb-2">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th width="35%">Item Pemeriksaan Laboratorium</th>
                                                        <th width="25%">Hasil Pemeriksaan</th>
                                                        <th width="15%">Satuan</th>
                                                        <th width="25%">Nilai Rujukan Normal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($itemTemplates as $t)
                                                        @php
                                                            // Nilai rujukan otomatis disesuaikan jenis kelamin pasien
                                                            $nilaiRef = $jk == 'P' ? ($t->nilai_rujukan_pd ?: $t->nilai_rujukan_ld) : $t->nilai_rujukan_ld;
                                                        @endphp
                                                        <tr>
                                                            <td class="align-middle font-weight-bold">
                                                                {{ $t->Pemeriksaan }}
                                                            </td>
                                                            <td>
                                                                <input type="text" name="nilai[{{ $jp->kd_jenis_prw }}][{{ $t->id_template }}]" 
                                                                    class="form-control form-control-sm font-weight-bold" 
                                                                    placeholder="Ketik hasil..." 
                                                                    style="font-size: 13px;">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="satuan[{{ $jp->kd_jenis_prw }}][{{ $t->id_template }}]" 
                                                                    class="form-control form-control-sm bg-light" 
                                                                    value="{{ $t->satuan }}" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="nilai_rujukan[{{ $jp->kd_jenis_prw }}][{{ $t->id_template }}]" 
                                                                    class="form-control form-control-sm bg-light" 
                                                                    value="{{ $nilaiRef }}" readonly>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-warning py-2 small mb-2">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Belum ada template item parameter untuk pemeriksaan ini.
                                        </div>
                                    @endif

                                    <div class="form-group mb-0 mt-2">
                                        <label class="small font-weight-bold text-muted">Catatan Tambahan Analis untuk {{ $jp->nm_perawatan }}:</label>
                                        <input type="text" name="catatan[{{ $jp->kd_jenis_prw }}]" class="form-control form-control-sm" placeholder="Catatan hasil jika ada...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card-footer bg-white text-right py-3">
                <button type="submit" class="btn btn-success px-4 font-weight-bold shadow-sm">
                    <i class="fas fa-save mr-1"></i> Simpan Hasil Pemeriksaan Laboratorium
                </button>
            </div>
        </form>
    </div>

    <!-- RIWAYAT HASIL LAB YANG PERNAH DICETAK / DISIMPAN -->
    @if ($riwayatHasil->count() > 0)
        <div class="card card-outline card-secondary shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 font-weight-bold text-dark">
                    <i class="fas fa-file-signature text-success mr-2"></i> Riwayat Hasil Laboratorium Pasien Ini
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Tanggal & Jam</th>
                                <th>Pemeriksaan</th>
                                <th>Petugas Lab</th>
                                <th>Biaya</th>
                                <th class="text-center">Cetak</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($riwayatHasil as $idx => $rh)
                                @php
                                    $nm = DB::table('jns_perawatan_lab')->where('kd_jenis_prw', $rh->kd_jenis_prw)->value('nm_perawatan');
                                @endphp
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>
                                        <strong>{{ date('d/m/Y', strtotime($rh->tgl_periksa)) }}</strong>
                                        <div class="small text-muted">{{ $rh->jam }} WIB</div>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold text-dark">{{ $nm ?? $rh->kd_jenis_prw }}</span>
                                        @if($rh->noorder)
                                            <div class="small text-muted font-monospace">Order: {{ $rh->noorder }}</div>
                                        @endif
                                    </td>
                                    <td>{{ $rh->petugas_lab ?? '-' }}</td>
                                    <td class="font-weight-bold text-success">Rp {{ number_format($rh->biaya, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('lab.cetakHasil', $rh->id) }}" target="_blank" class="btn btn-sm btn-outline-primary font-weight-bold">
                                            <i class="fas fa-print mr-1"></i> Cetak Lembar Hasil
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
<script>
    function togglePemeriksaan(kd) {
        var chk = document.getElementById('periksa_' + kd);
        var collapseEl = $('#collapse_' + kd);
        if (chk.checked) {
            collapseEl.collapse('show');
        }
    }
</script>
@endsection

