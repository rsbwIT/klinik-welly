@extends('layout.layout')
@section('title', 'Rincian Pemeriksaan SOAP')

@section('konten')
    <!-- Header Navigasi -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-dark"><i class="fas fa-file-medical-alt text-primary me-2"></i>Rincian Rekam Medis (SOAP)</h4>
            <small class="text-muted">Pasien: <strong>{{ $soapPasien->nama_lengkap }}</strong> &bull; Tgl: {{ $soapPasien->tanggal_input_soap }} ({{ $soapPasien->jam_input_soap }})</small>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-warning text-dark px-3 fw-bold" data-remodal-target="EditSoap">
                <i class="fas fa-edit me-1"></i> Edit Catatan SOAP
            </button>
            <a href="{{ route('soapPasienDetail', $hashid->encodeHex($soapPasien->id)) }}" class="btn btn-outline-secondary px-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Riwayat
            </a>
        </div>
    </div>

    <div class="row">
        <!-- CATATAN SOAP UTAMA (KIRI) -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-notes-medical text-primary me-2"></i>Dokumentasi Asuhan Medis (S.O.A.P)</h6>
                    <span class="badge bg-light text-secondary border font-monospace">{{ $soapPasien->no_rawat }}</span>
                </div>
                <div class="card-body p-4">
                    <!-- S -->
                    <div class="mb-4 p-3 rounded-3 bg-light border-start border-4 border-primary">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-primary px-2 py-1">S</span>
                            <strong class="text-dark">Subjektif (Keluhan Pasien)</strong>
                        </div>
                        <div class="text-secondary ps-4">{{ $soapPasien->subjek ?? 'Tidak ada keluhan tertulis' }}</div>
                    </div>

                    <!-- O -->
                    <div class="mb-4 p-3 rounded-3 bg-light border-start border-4 border-info">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-info text-white px-2 py-1">O</span>
                            <strong class="text-dark">Objektif (Pemeriksaan Fisik & Penunjang)</strong>
                        </div>
                        <div class="text-secondary ps-4">{{ $soapPasien->objek ?? 'Tidak ada data objektif tertulis' }}</div>
                    </div>

                    <!-- A -->
                    <div class="mb-4 p-3 rounded-3 bg-light border-start border-4 border-warning">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-warning text-dark px-2 py-1">A</span>
                            <strong class="text-dark">Asesmen / Diagnosis</strong>
                        </div>
                        <div class="text-secondary ps-4 fw-bold text-dark">{{ $soapPasien->asesmen ?? 'Belum ada diagnosis' }}</div>
                    </div>

                    <!-- P -->
                    <div class="mb-4 p-3 rounded-3 bg-light border-start border-4 border-success">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-success px-2 py-1">P</span>
                            <strong class="text-dark">Plan (Rencana Tindakan / Terapi / Obat)</strong>
                        </div>
                        <div class="text-secondary ps-4">{{ $soapPasien->plan ?? 'Belum ada rencana terapi tertulis' }}</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 border bg-white">
                                <strong class="small text-muted text-uppercase d-block mb-1">Instruksi Medis:</strong>
                                <div class="text-dark small">{{ $soapPasien->intruksi ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 border bg-white">
                                <strong class="small text-muted text-uppercase d-block mb-1">Evaluasi Asuhan:</strong>
                                <div class="text-dark small">{{ $soapPasien->evaluasi ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TANDA VITAL & BIODATA (KANAN) -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-heartbeat text-danger me-2"></i>Tanda Vital (TTV) & Pasien</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3 pb-3 border-bottom text-center">
                        <h6 class="fw-bold text-dark mb-1">{{ $soapPasien->nama_lengkap }}</h6>
                        <small class="text-muted d-block">{{ $soapPasien->no_telpon ?? '-' }}</small>
                        <small class="text-muted">{{ $soapPasien->kabupaten ?? '-' }}, {{ $soapPasien->provinsi ?? '' }}</small>
                    </div>

                    <div class="list-group list-group-flush small">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Suhu Tubuh</span>
                            <strong class="text-dark">{{ $soapPasien->suhu ? $soapPasien->suhu . ' °C' : '-' }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Tekanan Darah (Tensi)</span>
                            <strong class="text-dark">{{ $soapPasien->tensi ? $soapPasien->tensi . ' mmHg' : '-' }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Nadi</span>
                            <strong class="text-dark">{{ $soapPasien->nadi ? $soapPasien->nadi . ' x/m' : '-' }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Respirasi</span>
                            <strong class="text-dark">{{ $soapPasien->respirasi ? $soapPasien->respirasi . ' x/m' : '-' }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">SpO2 (Saturasi)</span>
                            <strong class="text-dark">{{ $soapPasien->spo2 ? $soapPasien->spo2 . ' %' : '-' }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">GCS</span>
                            <strong class="text-dark">{{ $soapPasien->gcs ?? '-' }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Berat / Tinggi Badan</span>
                            <strong class="text-dark">{{ $soapPasien->berat_badan ?? '-' }} kg / {{ $soapPasien->tinggi_badan ?? '-' }} cm</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Kesadaran</span>
                            <strong class="text-dark">{{ $soapPasien->kesadaran ?? '-' }}</strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-danger fw-semibold">Riwayat Alergi</span>
                            <span class="badge bg-danger-subtle text-danger">{{ $soapPasien->alergi ?? 'Tidak Ada' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- REMODAL EDIT SOAP -->
    <div class="remodal" data-remodal-id="EditSoap" role="dialog" aria-labelledby="modal1Title">
        <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
        <div class="text-start">
            <h4 class="fw-bold mb-3"><i class="fas fa-edit text-primary me-2"></i>Edit Catatan SOAP</h4>
            <form action="{{ route('editSoapPasien', $hashid->encodeHex($soapPasien->id)) }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $soapPasien->id }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Suhu (&deg;C)</label>
                        <input type="text" name="suhu" class="form-control" value="{{ $soapPasien->suhu }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Tensi (mmHg)</label>
                        <input type="text" name="tensi" class="form-control" value="{{ $soapPasien->tensi }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Subjektif (S)</label>
                        <textarea name="subjek" class="form-control" rows="2">{{ $soapPasien->subjek }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Objektif (O)</label>
                        <textarea name="objek" class="form-control" rows="2">{{ $soapPasien->objek }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Asesmen (A)</label>
                        <textarea name="asesmen" class="form-control" rows="2">{{ $soapPasien->asesmen }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Plan (P)</label>
                        <textarea name="plan" class="form-control" rows="2">{{ $soapPasien->plan }}</textarea>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button data-remodal-action="cancel" class="btn btn-secondary px-3">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Catatan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
