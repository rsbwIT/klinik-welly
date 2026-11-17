@extends('layout.layout')

@section('title', 'Resume Medis Pasien - ' . $pasien->nama_lengkap)
@section('hide_content_header', 'true')

@section('konten')

<style>
/* ================= CARD MODERN (BUNDLING2 STYLE) ================= */
.card-modern {
    border: none;
    border-radius: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,.04);
    background: #ffffff;
    margin-bottom: 20px;
}
.card-modern .card-header {
    background: #ffffff;
    border-bottom: 1px solid #eef1f4;
    font-weight: 600;
    font-size: 15px;
    color: #2c3e50;
    padding: 16px 20px;
    border-radius: 14px 14px 0 0;
}

/* ================= PREVIEW RESUME PAPER ================= */
.preview-sheet {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}

.table-preview th {
    background: #f8fafc;
    color: #475569;
    font-size: 12px;
    width: 25%;
    font-weight: 700;
    vertical-align: top;
    border-color: #e2e8f0;
}
.table-preview td {
    color: #0f172a;
    font-size: 13px;
    border-color: #e2e8f0;
    vertical-align: top;
}

.nav-tabs .nav-link {
    border-radius: 8px 8px 0 0;
    font-weight: 600;
    font-size: 13.5px;
    color: #64748b;
    padding: 10px 18px;
}
.nav-tabs .nav-link.active {
    color: #0284c7;
    font-weight: 700;
    border-bottom-color: #ffffff;
}
</style>

<div class="container-fluid pt-2">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm py-2 mb-3" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
        </div>
    @endif

    <!-- IDENTITAS PASIEN (GAYA BUNDLING2) -->
    <div class="card card-modern">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px; background: rgba(2, 132, 199, 0.1); color: #0284c7; font-size: 20px;">
                        <i class="fas fa-id-badge"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge badge-light border font-monospace px-2 py-1 mr-1">No. Rawat: <strong>{{ $pasien->no_rawat }}</strong></span>
                            <span class="badge badge-success px-2 py-1 font-monospace">No. RM: <strong>{{ $pasien->no_rkm_medis }}</strong></span>
                        </div>
                        <h5 class="font-weight-bold mb-0 text-dark">{{ $pasien->nama_lengkap }}</h5>
                        <small class="text-muted">
                            <i class="fas fa-venus-mars mr-1"></i>{{ $pasien->jenis_kelamin ?? '-' }} &bull;
                            <i class="fas fa-clinic-medical mr-1"></i>{{ $pasien->nm_poli ?? 'Poliklinik Umum' }} &bull;
                            <i class="fas fa-user-md mr-1"></i>{{ $pasien->dokter_dpjp ?? 'dr. Welly' }} &bull;
                            <i class="fas fa-calendar-alt mr-1"></i>{{ $pasien->tanggal_masuk ?? date('Y-m-d') }}
                        </small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                    <a href="{{ route('pelayanan.resume.cetak', $hashId) }}" target="_blank" class="btn btn-sm btn-outline-secondary shadow-2xs mr-1" style="border-radius: 8px;">
                        <i class="fas fa-print mr-1"></i> Cetak Lembar Resume (PDF)
                    </a>
                    <a href="{{ route('pelayanan.diagnosa', $hashId) }}" class="btn btn-sm btn-outline-primary shadow-2xs mr-1" style="border-radius: 8px;">
                        <i class="fas fa-diagnoses mr-1"></i> Diagnosa (ICD-10)
                    </a>
                    <a href="{{ route('rawatjalan.index') }}" class="btn btn-sm btn-light border shadow-2xs mr-2" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left mr-1"></i> Data Rawat Jalan
                    </a>
                    @if($resume)
                        <span class="badge badge-success px-3 py-2" style="font-size: 12.5px;">
                            <i class="fas fa-check-circle mr-1"></i> Resume Medis Tersimpan
                        </span>
                    @else
                        <span class="badge badge-warning text-dark px-3 py-2" style="font-size: 12.5px;">
                            <i class="fas fa-exclamation-circle mr-1"></i> Belum Dibuat
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- TAB PANEL (1. LIHAT HASIL RESUME, 2. EDIT / INPUT RESUME) -->
    <div class="card card-modern">
        <div class="card-header p-2 bg-light">
            <ul class="nav nav-tabs border-0" id="resumeTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab-lihat-tab" data-toggle="tab" href="#tab-lihat" role="tab">
                        <i class="fas fa-eye text-primary mr-1"></i> Lihat Data Resume Medis
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-form-tab" data-toggle="tab" href="#tab-form" role="tab">
                        <i class="fas fa-edit text-success mr-1"></i> {{ $resume ? 'Edit Isi Resume' : 'Input Formulir Resume Baru' }}
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="resumeTabsContent">

                <!-- TAB 1: LIHAT HASIL RESUME MEDIS (LIVE PREVIEW) -->
                <div class="tab-pane fade show active" id="tab-lihat" role="tabpanel">
                    @if($resume)
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <h6 class="font-weight-bold text-dark mb-0">
                                <i class="fas fa-file-alt text-info mr-1"></i> Ringkasan Resume Pelayanan Pasien
                            </h6>
                            <div class="d-flex gap-2 mt-2 mt-md-0">
                                <a href="{{ route('pelayanan.resume.cetak', $hashId) }}" target="_blank" class="btn btn-sm btn-primary font-weight-bold mr-2" style="border-radius: 6px;">
                                    <i class="fas fa-print mr-1"></i> Cetak Lembar Resmi
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-primary mr-2" onclick="$('#tab-form-tab').tab('show');" style="border-radius: 6px;">
                                    <i class="fas fa-edit mr-1"></i> Edit Data
                                </button>
                                <a href="{{ route('pelayanan.resume.hapus', $hashId) }}" class="btn btn-sm btn-outline-danger font-weight-bold" onclick="return confirm('Apakah Anda yakin ingin menghapus data resume medis pasien ini? Data yang dihapus tidak dapat dikembalikan.')" style="border-radius: 6px;">
                                    <i class="fas fa-trash-alt mr-1"></i> Hapus Resume
                                </a>
                            </div>
                        </div>

                        <div class="preview-sheet">
                            <table class="table table-bordered table-preview mb-0">
                                <tr>
                                    <th>1. Keluhan Utama & Riwayat Sakit</th>
                                    <td>{{ $resume->keluhan_utama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>2. Anamnesis / Jalannya Penyakit</th>
                                    <td>{{ $resume->jalannya_penyakit ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>3. Pemeriksaan Fisik & Vital Sign</th>
                                    <td>{!! nl2br(e($resume->pemeriksaan_fisik ?? '-')) !!}</td>
                                </tr>
                                <tr>
                                    <th>4. Diagnosa Utama (ICD-10)</th>
                                    <td>
                                        <strong class="text-danger font-monospace" style="font-size: 13.5px;">
                                            {{ $resume->diagnosa_utama ?? '-' }}
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th>5. Diagnosa Sekunder / Komorbid</th>
                                    <td>{{ $resume->diagnosa_sekunder ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>6. Prosedur & Tindakan Medis</th>
                                    <td>{!! nl2br(e($resume->prosedur_tindakan ?? '-')) !!}</td>
                                </tr>
                                <tr>
                                    <th>7. Terapi Obat Pulang (Farmasi)</th>
                                    <td>{!! nl2br(e($resume->terapi_obat ?? '-')) !!}</td>
                                </tr>
                                <tr>
                                    <th>8. Anjuran & Edukasi Lanjutan</th>
                                    <td>{{ $resume->anjuran_edukasi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>9. Kondisi Saat Pulang</th>
                                    <td>
                                        <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 12px;">
                                            {{ $resume->kondisi_pulang ?? 'Membaik' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>10. Dokter DPJP</th>
                                    <td>
                                        <strong>{{ $resume->dokter_dpjp ?? $pasien->dokter_dpjp ?? 'dr. Welly' }}</strong>
                                        <small class="text-muted d-block font-monospace">Terakhir diperbarui: {{ $resume->updated_at ?? date('Y-m-d H:i') }}</small>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-file-medical-alt fa-3x mb-3 text-secondary opacity-50"></i>
                            <h5 class="font-weight-bold text-dark">Resume Medis Belum Diinput</h5>
                            <p class="small text-muted mb-3">Silakan klik tab <strong>"Input Formulir Resume Baru"</strong> untuk mengisi ringkasan pelayanan rawat jalan pasien ini.</p>
                            <button type="button" class="btn btn-primary btn-sm px-3" onclick="$('#tab-form-tab').tab('show');">
                                <i class="fas fa-edit mr-1"></i> Mulai Isi Resume Sekarang
                            </button>
                        </div>
                    @endif
                </div>

                <!-- TAB 2: FORM EDIT / INPUT RESUME MEDIS -->
                <div class="tab-pane fade" id="tab-form" role="tabpanel">
                    <form action="{{ route('pelayanan.resume.simpan', $hashId) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary mb-1">Keluhan Utama & Riwayat Sakit :</label>
                                <textarea name="keluhan_utama" rows="3" class="form-control" placeholder="Tuliskan keluhan utama pasien saat datang...">{{ $resume->keluhan_utama ?? '' }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary mb-1">Jalannya Penyakit / Anamnesis Lanjutan :</label>
                                <textarea name="jalannya_penyakit" rows="3" class="form-control" placeholder="Riwayat penyakit sekarang, durasi sakit, dll...">{{ $resume->jalannya_penyakit ?? '' }}</textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="small font-weight-bold text-secondary mb-1">Pemeriksaan Fisik & Vital Sign :</label>
                                <textarea name="pemeriksaan_fisik" rows="2" class="form-control" placeholder="TD: ... mmHg, Nadi: ... x/m, Suhu: ... C, Respirasi: ... x/m, kondisi luka...">{{ $resume->pemeriksaan_fisik ?? '' }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary mb-1">Diagnosa Utama (ICD-10) :</label>
                                <input type="text" name="diagnosa_utama" class="form-control" value="{{ $resume->diagnosa_utama ?? ($diagnosas->where('prioritas', 'Utama')->first()->nm_penyakit ?? '') }}" placeholder="Diagnosa primer...">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary mb-1">Diagnosa Sekunder / Komorbid :</label>
                                <input type="text" name="diagnosa_sekunder" class="form-control" value="{{ $resume->diagnosa_sekunder ?? ($diagnosas->where('prioritas', 'Sekunder')->first()->nm_penyakit ?? '') }}" placeholder="Diagnosa sekunder jika ada...">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary mb-1">Prosedur / Tindakan Medis yang Dilakukan :</label>
                                <textarea name="prosedur_tindakan" rows="3" class="form-control" placeholder="Tindakan debridement, rawat luka modern, perban, dll...">{{ $resume->prosedur_tindakan ?? '' }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-secondary mb-1">Terapi Obat Pulang (Resep Farmasi) :</label>
                                <textarea name="terapi_obat" rows="3" class="form-control" placeholder="Daftar resep obat, antibiotik, salep, dan aturan pakai...">{{ $resume->terapi_obat ?? '' }}</textarea>
                            </div>

                            <div class="col-md-8 mb-3">
                                <label class="small font-weight-bold text-secondary mb-1">Anjuran, Edukasi & Rencana Tindak Lanjut :</label>
                                <input type="text" name="anjuran_edukasi" class="form-control" value="{{ $resume->anjuran_edukasi ?? 'Jaga kebersihan balutan luka, kontrol ulang bila belum membaik.' }}" placeholder="Anjuran dan edukasi kepada pasien...">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="small font-weight-bold text-secondary mb-1">Kondisi Saat Pulang :</label>
                                <select name="kondisi_pulang" class="form-control">
                                    <option value="Membaik" {{ ($resume->kondisi_pulang ?? 'Membaik') == 'Membaik' ? 'selected' : '' }}>Membaik</option>
                                    <option value="Sembuh" {{ ($resume->kondisi_pulang ?? '') == 'Sembuh' ? 'selected' : '' }}>Sembuh</option>
                                    <option value="Belum Sembuh" {{ ($resume->kondisi_pulang ?? '') == 'Belum Sembuh' ? 'selected' : '' }}>Belum Sembuh</option>
                                    <option value="Rujuk Lanjut" {{ ($resume->kondisi_pulang ?? '') == 'Rujuk Lanjut' ? 'selected' : '' }}>Rujuk Rumah Sakit</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 border-top pt-3">
                            <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm" style="border-radius: 8px;">
                                <i class="fas fa-save mr-1"></i> Simpan Resume Medis
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
