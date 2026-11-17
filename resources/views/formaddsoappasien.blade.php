@extends('layout.layout')
@section('title', 'Input Pemeriksaan SOAP / SOAPIE Pasien')
@section('hide_content_header', 'true')

@section('konten')
<style>
    /* Styling SIMRS Khanza Desktop Theme */
    .khanza-form-box {
        background-color: #f7fdf7;
        border: 1px solid #bce1bc;
        border-radius: 4px;
        font-size: 11.5px;
    }
    .khanza-header {
        background: linear-gradient(to bottom, #f2fbf2 0%, #e1f5e1 100%);
        border-bottom: 1px solid #c2e5c2;
        padding: 5px 12px;
        font-weight: bold;
        color: #275227;
        font-family: Tahoma, Arial, sans-serif;
        font-size: 12px;
    }
    .form-khanza {
        font-size: 11px;
        font-family: Tahoma, Arial, sans-serif;
    }
    .form-khanza .form-control, .form-khanza .custom-select {
        height: 25px !important;
        font-size: 11px !important;
        padding: 2px 6px !important;
        border-radius: 3px !important;
        border: 1px solid #9dc79d !important;
        background-color: #ffffff;
    }
    .form-khanza textarea.form-control {
        height: auto !important;
    }
    .form-khanza label {
        font-size: 11px;
        font-weight: 600;
        color: #333;
        margin-bottom: 2px;
    }
    .btn-khanza {
        font-size: 11px !important;
        font-weight: 600 !important;
        padding: 3px 12px !important;
        border-radius: 4px !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .table-khanza {
        font-size: 11px;
        font-family: Tahoma, Arial, sans-serif;
    }
    .table-khanza th {
        background-color: #eef7ee;
        color: #2e4d2e;
        border-color: #d1ebd1 !important;
        padding: 5px 6px !important;
        font-weight: bold;
        white-space: nowrap;
    }
    .table-khanza td {
        padding: 4px 6px !important;
        border-color: #e4f2e4 !important;
        vertical-align: middle;
        white-space: nowrap;
    }
    .table-khanza tr:hover {
        background-color: #f0faf0;
    }
    .khanza-field-badge {
        background: #eef7ee;
        border: 1px solid #bce1bc;
        border-radius: 3px;
        padding: 1px 6px;
        font-size: 10.5px;
        font-family: monospace;
    }
</style>

<div class="container-fluid px-2 pt-1 pb-3">

    {{-- ALERT NOTIFIKASI --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show py-1 px-3 mb-2 shadow-sm font-monospace" style="font-size: 12px;" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close p-1" data-dismiss="alert">&times;</button>
        </div>
    @endif

    {{-- IDENTITAS PASIEN SINGKAT & JELAS --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 px-2 py-1 bg-white border rounded shadow-2xs">
        <div class="d-flex align-items-center flex-wrap gap-2">
            <span class="badge badge-primary px-2 py-1 font-monospace" style="font-size: 11.5px;">No. Rawat: {{ $soapPasien->no_rawat }}</span>
            <span class="badge badge-success px-2 py-1 font-monospace" style="font-size: 11.5px;">No. RM: {{ $soapPasien->no_rkm_medis ?? '-' }}</span>
            <span class="fw-bold text-dark ml-2" style="font-size: 13px;">{{ $soapPasien->nama_lengkap }}</span>
            <span class="text-muted" style="font-size: 11.5px;">
                ({{ $soapPasien->jenis_kelamin ?? '-' }} &bull; {{ $soapPasien->unit_poli ?? 'Poli Umum' }} &bull; DPJP: {{ $soapPasien->dokter_dituju ?? '-' }})
            </span>
        </div>
        <div class="d-flex align-items-center gap-1">
            <a href="{{ route('soapPasienDetail', $hashid->encodeHex($soapPasien->id)) }}" class="btn btn-outline-info btn-khanza">
                <i class="fas fa-history text-info"></i> Rekap Perawatan Pasien
            </a>
            <a href="{{ route('kunjungan.index') }}" class="btn btn-outline-secondary btn-khanza">
                <i class="fas fa-sign-out-alt text-danger"></i> Keluar
            </a>
        </div>
    </div>

    {{-- KOTAK FORM INPUT SOAP PERSIS GAMBAR LAMPIRAN KHANZA --}}
    <div class="khanza-form-box shadow-sm mb-3">
        <div class="khanza-header d-flex justify-content-between align-items-center">
            <span>:: [ Catatan Perkembangan Pasien Terintegrasi (CPPT / S.O.A.P.I.E) ] ::</span>
            <small class="text-muted font-italic">Bisa diinput lebih dari 1 kali per kunjungan rawat jalan</small>
        </div>

        <form action="{{ route('createSoapPasien') }}" method="POST" id="formKhanzaSoap" class="p-3 form-khanza">
            @csrf
            <input type="hidden" name="no_rawat" value="{{ $soapPasien->no_rawat }}">
            <input type="hidden" name="petugas" value="{{ auth()->check() ? auth()->user()->name : 'Petugas' }}">

            {{-- BARIS 1: DOKTER / PETUGAS & LINGKAR PERUT & ALERGI --}}
            <div class="row align-items-center mb-2">
                <div class="col-md-5 d-flex align-items-center">
                    <label class="mb-0 text-nowrap mr-2" style="width: 140px;">Dilakukan :</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="nip" id="nip" class="form-control font-monospace" style="max-width: 95px;" value="{{ auth()->check() ? auth()->user()->id : '01091999' }}" placeholder="NIP/Kode">
                        <input type="text" name="nama_petugas" id="nama_petugas" class="form-control fw-bold" value="{{ auth()->check() ? auth()->user()->name : 'Welly Prestikasari' }}" placeholder="Nama Petugas / Dokter">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-light border py-0 px-2" data-toggle="modal" data-target="#modalPilihDokterSoap" title="Pilih Dokter / Petugas">
                                <i class="fas fa-paperclip text-secondary"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-center">
                    <label class="mb-0 text-nowrap mr-2" style="width: 80px;">L.P. (Cm) :</label>
                    <input type="text" name="lingkar_perut" id="lingkar_perut" class="form-control" style="max-width: 90px;" placeholder="0">
                </div>
                <div class="col-md-4 d-flex align-items-center">
                    <label class="mb-0 text-nowrap mr-2" style="width: 60px;">Alergi :</label>
                    <input type="text" name="alergi" id="alergi" class="form-control" placeholder="Tuliskan jika ada alergi obat / makanan...">
                </div>
            </div>

            {{-- BARIS 2: PROFESI / JABATAN / DEPARTEMEN --}}
            <div class="row align-items-center mb-2">
                <div class="col-md-5 d-flex align-items-center">
                    <label class="mb-0 text-nowrap mr-2" style="width: 140px;">Profesi / Jabatan :</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="profesi" id="profesi" class="form-control" value="Perawat / Dokter Penanggung Jawab" placeholder="Profesi / Jabatan">
                        <div class="input-group-append">
                            <span class="input-group-text bg-light py-0 px-2"><i class="fas fa-user-tag text-muted"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 d-flex align-items-center">
                    <label class="mb-0 text-nowrap mr-2" style="width: 80px;">Tgl & Jam :</label>
                    <input type="date" name="tanggal_input_soap" class="form-control mr-1" style="max-width: 130px;" value="{{ date('Y-m-d') }}" required>
                    <input type="time" name="jam_input_soap" class="form-control" style="max-width: 100px;" value="{{ date('H:i') }}" required>
                </div>
            </div>

            <hr class="my-2" style="border-top: 1px dashed #cde5cd;">

            {{-- FORM GRID 2 KOLOM (KIRI: SUBJEK, OBJEK, TTV. KANAN: ASESMEN, PLAN, INSTRUKSI, EVALUASI) --}}
            <div class="row">
                {{-- KOLOM KIRI --}}
                <div class="col-md-6 pr-md-2">
                    <div class="form-group mb-2 d-flex align-items-start">
                        <label class="text-nowrap mr-2 pt-1" style="width: 80px;">Subjek :</label>
                        <textarea name="subjek" id="subjek" rows="2" class="form-control" placeholder="Keluhan utama pasien..."></textarea>
                    </div>

                    <div class="form-group mb-2 d-flex align-items-start">
                        <label class="text-nowrap mr-2 pt-1" style="width: 80px;">Objek :</label>
                        <textarea name="objek" id="objek" rows="2" class="form-control" placeholder="Pemeriksaan fisik / luka..."></textarea>
                    </div>

                    {{-- VITAL SIGNS BARIS 1 --}}
                    <div class="row align-items-center mb-2">
                        <div class="col-4 d-flex align-items-center px-1">
                            <label class="mb-0 text-nowrap mr-1" style="font-size: 10.5px;">Suhu (&deg;C):</label>
                            <input type="text" name="suhu" id="suhu" class="form-control" placeholder="36.5">
                        </div>
                        <div class="col-4 d-flex align-items-center px-1">
                            <label class="mb-0 text-nowrap mr-1" style="font-size: 10.5px;">Tensi:</label>
                            <input type="text" name="tensi" id="tensi" class="form-control" placeholder="120/80">
                        </div>
                        <div class="col-4 d-flex align-items-center px-1">
                            <label class="mb-0 text-nowrap mr-1" style="font-size: 10.5px;">Berat (Kg):</label>
                            <input type="text" name="berat_badan" id="berat_badan" class="form-control" placeholder="60">
                        </div>
                    </div>

                    {{-- VITAL SIGNS BARIS 2 --}}
                    <div class="row align-items-center mb-2">
                        <div class="col-4 d-flex align-items-center px-1">
                            <label class="mb-0 text-nowrap mr-1" style="font-size: 10.5px;">TB (Cm):</label>
                            <input type="text" name="tinggi_badan" id="tinggi_badan" class="form-control" placeholder="165">
                        </div>
                        <div class="col-4 d-flex align-items-center px-1">
                            <label class="mb-0 text-nowrap mr-1" style="font-size: 10.5px;">RR (/mnt):</label>
                            <input type="text" name="respirasi" id="respirasi" class="form-control" placeholder="20">
                        </div>
                        <div class="col-4 d-flex align-items-center px-1">
                            <label class="mb-0 text-nowrap mr-1" style="font-size: 10.5px;">Nadi (/mnt):</label>
                            <input type="text" name="nadi" id="nadi" class="form-control" placeholder="80">
                        </div>
                    </div>

                    {{-- VITAL SIGNS BARIS 3 --}}
                    <div class="row align-items-center mb-2">
                        <div class="col-4 d-flex align-items-center px-1">
                            <label class="mb-0 text-nowrap mr-1" style="font-size: 10.5px;">SpO2 (%):</label>
                            <input type="text" name="spo2" id="spo2" class="form-control" placeholder="98">
                        </div>
                        <div class="col-4 d-flex align-items-center px-1">
                            <label class="mb-0 text-nowrap mr-1" style="font-size: 10.5px;">GCS (E,V,M):</label>
                            <input type="text" name="gcs" id="gcs" class="form-control" placeholder="15">
                        </div>
                        <div class="col-4 d-flex align-items-center px-1">
                            <label class="mb-0 text-nowrap mr-1" style="font-size: 10.5px;">Kesadaran:</label>
                            <select name="kesadaran" id="kesadaran" class="custom-select">
                                <option value="Compos Mentis">Compos Mentis</option>
                                <option value="Apatis">Apatis</option>
                                <option value="Somnolen">Somnolen</option>
                                <option value="Sopor">Sopor</option>
                                <option value="Coma">Coma</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN --}}
                <div class="col-md-6 pl-md-2">
                    <div class="form-group mb-2 d-flex align-items-start">
                        <label class="text-nowrap mr-2 pt-1" style="width: 80px;">Asesmen :</label>
                        <textarea name="asesmen" id="asesmen" rows="2" class="form-control" placeholder="Diagnosa keperawatan / medis..."></textarea>
                    </div>

                    <div class="form-group mb-2 d-flex align-items-start">
                        <label class="text-nowrap mr-2 pt-1" style="width: 80px;">Plan :</label>
                        <textarea name="plan" id="plan" rows="2" class="form-control" placeholder="Rencana pengobatan & intervensi luka..."></textarea>
                    </div>

                    <div class="form-group mb-2 d-flex align-items-start">
                        <label class="text-nowrap mr-2 pt-1" style="width: 80px;">Inst/Impl :</label>
                        <textarea name="intruksi" id="intruksi" rows="2" class="form-control" placeholder="Instruksi medis / implementasi keperawatan..."></textarea>
                    </div>

                    <div class="form-group mb-2 d-flex align-items-start">
                        <label class="text-nowrap mr-2 pt-1" style="width: 80px;">Evaluasi :</label>
                        <textarea name="evaluasi" id="evaluasi" rows="2" class="form-control" placeholder="Hasil evaluasi perkembangan pasien..."></textarea>
                    </div>
                </div>
            </div>

            {{-- TOMBOL AKSI BAWAH PERSIS SIMRS KHANZA --}}
            <div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-khanza">
                        <i class="fas fa-save text-white"></i> Simpan
                    </button>
                    <button type="button" onclick="resetFormSoap()" class="btn btn-light border btn-khanza">
                        <i class="fas fa-file text-danger"></i> Baru
                    </button>
                    <button type="button" onclick="hapusSoapTerpilih()" id="btnHapusSoap" class="btn btn-light border btn-khanza" disabled>
                        <i class="fas fa-trash-alt text-danger"></i> Hapus
                    </button>
                </div>
                <div class="d-flex align-items-center text-muted" style="font-size: 11px;">
                    <span class="mr-2"><i class="fas fa-database mr-1"></i> Total Record Input: <strong>{{ count($riwayatSoap) }}</strong> data</span>
                </div>
            </div>
        </form>
    </div>

    {{-- TABEL INPUT DATA / RIWAYAT SOAP PASIEN (PERSIS GAMBAR LAMPIRAN) --}}
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 4px;">
        <div class="card-header bg-white py-1 px-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="font-monospace fw-bold text-dark" style="font-size: 12px;">:: Input Data (Riwayat SOAPIE Pasien) ::</span>
            <small class="text-muted">Klik baris tabel di bawah untuk melihat rincian atau mengubah data</small>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                <table class="table table-bordered table-khanza table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="30px" class="text-center">P</th>
                            <th>No.Rawat</th>
                            <th>No.R.M.</th>
                            <th>Nama Pasien</th>
                            <th>Tgl.Rawat</th>
                            <th>Jam</th>
                            <th>Suhu(&deg;C)</th>
                            <th>Tensi</th>
                            <th>Nadi(/menit)</th>
                            <th>Respirasi(/menit)</th>
                            <th>Tinggi(Cm)</th>
                            <th>Berat(Kg)</th>
                            <th>SpO2(%)</th>
                            <th>GCS(E,V,M)</th>
                            <th>Kesadaran</th>
                            <th>Subjek</th>
                            <th>Objek</th>
                            <th>Alergi</th>
                            <th>L.P. (Cm)</th>
                            <th>Plan</th>
                            <th>Asesmen</th>
                            <th>Instruksi</th>
                            <th>Evaluasi</th>
                            <th>Petugas/Dokter</th>
                            <th width="40px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbodySoapKhanza">
                        @forelse ($riwayatSoap as $soap)
                            <tr style="cursor: pointer;" onclick="loadDataKeForm({{ json_encode($soap) }})">
                                <td class="text-center"><i class="far fa-square text-secondary"></i></td>
                                <td class="font-monospace text-primary fw-bold">{{ $soap->no_rawat }}</td>
                                <td class="font-monospace text-success fw-bold">{{ $soapPasien->no_rkm_medis ?? '-' }}</td>
                                <td class="fw-bold">{{ $soapPasien->nama_lengkap }}</td>
                                <td>{{ $soap->tanggal_input_soap }}</td>
                                <td>{{ $soap->jam_input_soap }}</td>
                                <td>{{ $soap->suhu ?? '-' }}</td>
                                <td>{{ $soap->tensi ?? '-' }}</td>
                                <td>{{ $soap->nadi ?? '-' }}</td>
                                <td>{{ $soap->respirasi ?? '-' }}</td>
                                <td>{{ $soap->tinggi_badan ?? '-' }}</td>
                                <td>{{ $soap->berat_badan ?? '-' }}</td>
                                <td>{{ $soap->spo2 ?? '-' }}</td>
                                <td>{{ $soap->gcs ?? '-' }}</td>
                                <td>{{ $soap->kesadaran ?? 'Compos Mentis' }}</td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ $soap->subjek }}">
                                        {{ $soap->subjek ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ $soap->objek }}">
                                        {{ $soap->objek ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $soap->alergi ?? '-' }}</td>
                                <td>{{ $soap->lingkar_perut ?? '-' }}</td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ $soap->plan }}">
                                        {{ $soap->plan ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ $soap->asesmen }}">
                                        {{ $soap->asesmen ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 130px;" title="{{ $soap->intruksi }}">
                                        {{ $soap->intruksi ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="d-inline-block text-truncate" style="max-width: 130px;" title="{{ $soap->evaluasi }}">
                                        {{ $soap->evaluasi ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $soap->nama_petugas ?: ($soap->petugas ?: '-') }}</td>
                                <td class="text-center" onclick="event.stopPropagation();">
                                    <a href="{{ route('deleteSoap', $hashid->encodeHex($soap->id)) }}" class="btn btn-xs btn-outline-danger p-1" onclick="return confirm('Apakah Anda yakin ingin menghapus catatan SOAP tanggal {{ $soap->tanggal_input_soap }} ini?')" title="Hapus SOAP">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="25" class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle mr-1"></i> Belum ada catatan SOAP/SOAPIE untuk pasien ini. Silakan input pada form di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PILIH DOKTER / PETUGAS --}}
<div class="modal fade" id="modalPilihDokterSoap" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content shadow border-0" style="border-radius: 6px; font-size: 12px;">
            <div class="modal-header bg-success text-white py-2 px-3">
                <h6 class="modal-title font-weight-bold"><i class="fas fa-user-md mr-1"></i> Pilih Dokter / Petugas Medis</h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-2">
                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-sm table-hover table-bordered mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="90px">Kode/NIP</th>
                                <th>Nama Dokter / Petugas</th>
                                <th width="60px" class="text-center">Pilih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($dokters))
                                @foreach ($dokters as $doc)
                                    <tr>
                                        <td class="font-monospace text-primary fw-bold">{{ $doc->kd_dokter }}</td>
                                        <td class="fw-bold">{{ $doc->nm_dokter }} <span class="badge badge-light border text-muted ml-1">Dokter</span></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-xs btn-success px-2 py-1 font-weight-bold" onclick="pilihPetugas('{{ $doc->kd_dokter }}', '{{ $doc->nm_dokter }}', 'Dokter')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            @if(isset($petugasList))
                                @foreach ($petugasList as $p)
                                    <tr>
                                        <td class="font-monospace text-secondary">{{ sprintf('%05d', $p->id) }}</td>
                                        <td class="fw-bold">{{ $p->name }} <span class="badge badge-info ml-1">{{ ucfirst($p->role) }}</span></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-xs btn-success px-2 py-1 font-weight-bold" onclick="pilihPetugas('{{ sprintf('%05d', $p->id) }}', '{{ $p->name }}', '{{ ucfirst($p->role) }}')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var selectedSoapId = null;

    function pilihPetugas(nip, nama, profesi) {
        document.getElementById('nip').value = nip;
        document.getElementById('nama_petugas').value = nama;
        if (profesi) {
            document.getElementById('profesi').value = profesi;
        }
        $('#modalPilihDokterSoap').modal('hide');
    }

    function loadDataKeForm(data) {
        selectedSoapId = data.id;
        document.getElementById('btnHapusSoap').removeAttribute('disabled');

        if (data.nip) document.getElementById('nip').value = data.nip;
        if (data.nama_petugas) document.getElementById('nama_petugas').value = data.nama_petugas;
        if (data.profesi) document.getElementById('profesi').value = data.profesi;
        if (data.lingkar_perut) document.getElementById('lingkar_perut').value = data.lingkar_perut;
        if (data.alergi) document.getElementById('alergi').value = data.alergi;
        
        if (data.subjek) document.getElementById('subjek').value = data.subjek;
        if (data.objek) document.getElementById('objek').value = data.objek;
        if (data.asesmen) document.getElementById('asesmen').value = data.asesmen;
        if (data.plan) document.getElementById('plan').value = data.plan;
        if (data.intruksi) document.getElementById('intruksi').value = data.intruksi;
        if (data.evaluasi) document.getElementById('evaluasi').value = data.evaluasi;

        if (data.suhu) document.getElementById('suhu').value = data.suhu;
        if (data.tensi) document.getElementById('tensi').value = data.tensi;
        if (data.berat_badan) document.getElementById('berat_badan').value = data.berat_badan;
        if (data.tinggi_badan) document.getElementById('tinggi_badan').value = data.tinggi_badan;
        if (data.respirasi) document.getElementById('respirasi').value = data.respirasi;
        if (data.nadi) document.getElementById('nadi').value = data.nadi;
        if (data.spo2) document.getElementById('spo2').value = data.spo2;
        if (data.gcs) document.getElementById('gcs').value = data.gcs;
        if (data.kesadaran) document.getElementById('kesadaran').value = data.kesadaran;
    }

    function resetFormSoap() {
        selectedSoapId = null;
        document.getElementById('btnHapusSoap').setAttribute('disabled', 'disabled');
        document.getElementById('subjek').value = '';
        document.getElementById('objek').value = '';
        document.getElementById('asesmen').value = '';
        document.getElementById('plan').value = '';
        document.getElementById('intruksi').value = '';
        document.getElementById('evaluasi').value = '';
        document.getElementById('suhu').value = '';
        document.getElementById('tensi').value = '';
        document.getElementById('berat_badan').value = '';
        document.getElementById('tinggi_badan').value = '';
        document.getElementById('respirasi').value = '';
        document.getElementById('nadi').value = '';
        document.getElementById('spo2').value = '';
        document.getElementById('gcs').value = '';
        document.getElementById('lingkar_perut').value = '';
        document.getElementById('alergi').value = '';
    }

    function hapusSoapTerpilih() {
        if (!selectedSoapId) {
            alert('Pilih data SOAP dari tabel terlebih dahulu!');
            return;
        }
        if (confirm('Apakah Anda yakin ingin menghapus data SOAP yang dipilih ini?')) {
            window.location.href = "{{ url('/deleteSoap') }}/" + selectedSoapId;
        }
    }
</script>
@endsection
