@extends('layout.layout')
@section('title', 'Formulir Asuhan Keperawatan (Askep)')
@section('hide_content_header', 'true')

@section('konten')
<div class="container-fluid px-2 pt-2 pb-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-2 shadow-2xs" role="alert" style="font-size: 12px;">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close p-2" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- FORM INPUT ASKEP -->
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 6px;">
        <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="font-monospace fw-bold text-info" style="font-size: 13px;">
                <i class="fas fa-file-medical-alt mr-1"></i> :: [ FORMULIR ASUHAN KEPERAWATAN ] ::
            </span>
            <button type="button" class="btn btn-xs btn-outline-info" onclick="bukaModalCariPasienAskep()">
                <i class="fas fa-search mr-1"></i> Tarik Data Pasien
            </button>
        </div>
        <div class="card-body p-3 bg-white">
            @if(isset($selectedPasien) && $selectedPasien)
                <div class="alert alert-info py-2 px-3 mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-user-check mr-1"></i> Data Pasien Terpilih dari Rawat Jalan: 
                        <strong>{{ $selectedPasien->nama_lengkap }}</strong> 
                        (No. Rawat: <span class="font-monospace fw-bold">{{ $selectedPasien->no_rawat }}</span>, 
                        No. RM: <span class="font-monospace fw-bold">{{ $selectedPasien->no_rkm_medis }}</span>)
                    </div>
                    <a href="{{ route('surat.askep.index') }}" class="btn btn-xs btn-outline-secondary">Reset / Buat Baru</a>
                </div>
            @endif

            <form action="{{ route('surat.askep.store') }}" method="POST">
                @csrf
                <input type="hidden" name="pasien_id" id="askep_pasien_id" value="{{ $selectedPasien->id ?? '' }}">
                <input type="hidden" name="no_rawat" id="askep_no_rawat" value="{{ $selectedPasien->no_rawat ?? '' }}">

                <!-- BAGIAN 1: IDENTITAS KLIEN -->
                <div class="p-2 mb-2 rounded bg-light border">
                    <span class="small font-weight-bold text-dark text-uppercase d-block mb-2">1. Identitas Klien</span>
                    <div class="row g-2 mb-2">
                        <div class="col-md-3">
                            <label class="small text-secondary mb-0">Nomor Rawat :</label>
                            <input type="text" name="nomor_register" id="askep_nomor_register" class="form-control form-control-sm font-monospace" placeholder="00001/11/11/2026" value="{{ $selectedPasien->no_rawat ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="small text-secondary mb-0">Nama Klien :</label>
                            <input type="text" name="nama_klien" id="askep_nama_klien" class="form-control form-control-sm fw-bold" placeholder="Nama Pasien" value="{{ $selectedPasien->nama_lengkap ?? '' }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="small text-secondary mb-0">Jenis Kelamin :</label>
                            <select name="jenis_kelamin" id="askep_jenis_kelamin" class="form-select form-select-sm">
                                <option value="Laki-laki" {{ (isset($selectedPasien) && in_array(strtolower($selectedPasien->jenis_kelamin ?? ''), ['laki-laki', 'l'])) ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ (isset($selectedPasien) && in_array(strtolower($selectedPasien->jenis_kelamin ?? ''), ['perempuan', 'p'])) ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="small text-secondary mb-0">Tanggal Lahir :</label>
                            <input type="date" name="tanggal_lahir" id="askep_tanggal_lahir" class="form-control form-control-sm" value="{{ $selectedPasien->tgl_lahir ?? '' }}">
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-8">
                            <label class="small text-secondary mb-0">Alamat :</label>
                            <input type="text" name="alamat" id="askep_alamat" class="form-control form-control-sm" placeholder="Alamat Klien" value="{{ $selectedPasien->alamat_lengkap ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="small text-secondary mb-0">No. HP :</label>
                            <input type="text" name="no_hp" id="askep_no_hp" class="form-control form-control-sm" placeholder="08xxxxxxxxxx" value="{{ $selectedPasien->no_telpon ?? '' }}">
                        </div>
                    </div>
                </div>

                <!-- BAGIAN 2: RIWAYAT KESEHATAN -->
                <div class="p-2 mb-2 rounded bg-light border">
                    <span class="small font-weight-bold text-dark text-uppercase d-block mb-2">2. Riwayat Kesehatan</span>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="small text-secondary mb-0">Penyakit Dahulu :</label>
                            <textarea name="penyakit_dahulu" rows="2" class="form-control form-control-sm" placeholder="Contoh: Diabetes Mellitus, Hipertensi..."></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-secondary mb-0">Alergi (Obat, Makanan, dll) :</label>
                            <textarea name="alergi" rows="2" class="form-control form-control-sm" placeholder="Contoh: Alergi Penisilin, Seafood / Tidak Ada"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-secondary mb-0">Obat Rutin Yang Dikonsumsi :</label>
                            <textarea name="obat_rutin" rows="2" class="form-control form-control-sm" placeholder="Contoh: Metformin 500mg, Amlodipine 10mg..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- BAGIAN 3: PENGKAJIAN, DIAGNOSA, INTERVENSI, IMPLEMENTASI, EVALUASI -->
                <div class="p-2 mb-3 rounded bg-light border">
                    <span class="small font-weight-bold text-dark text-uppercase d-block mb-2">3. Asuhan Keperawatan Terpadu</span>
                    <div class="row g-2 mb-2">
                        <div class="col-md-3">
                            <label class="small text-secondary mb-0">Tanggal Pengkajian :</label>
                            <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-9 d-flex align-items-center gap-2">
                            <span class="small fw-bold text-secondary">Tanda Vital:</span>
                            <input type="text" name="td" class="form-control form-control-sm" style="max-width: 90px;" placeholder="TD: 120/80">
                            <input type="text" name="hr" class="form-control form-control-sm" style="max-width: 80px;" placeholder="HR: 80">
                            <input type="text" name="rr" class="form-control form-control-sm" style="max-width: 80px;" placeholder="RR: 20">
                            <input type="text" name="suhu" class="form-control form-control-sm" style="max-width: 80px;" placeholder="Suhu: 36.5">
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label class="small text-secondary mb-0">Keluhan & Pemeriksaan Fisik :</label>
                            <textarea name="keluhan" rows="2" class="form-control form-control-sm" placeholder="Keluhan utama dan kondisi fisik luka..." required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-secondary mb-0">Diagnosa Keperawatan :</label>
                            <textarea name="dx_keperawatan" rows="2" class="form-control form-control-sm" placeholder="Contoh: Nyeri akut b.d agen pencedera fisik, Kerusakan integritas jaringan..."></textarea>
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-md-12">
                            <label class="small text-secondary mb-0 fw-bold text-info"><i class="fas fa-tasks mr-1"></i> Intervensi / Rencana Keperawatan :</label>
                            <textarea name="intervensi" rows="2" class="form-control form-control-sm" placeholder="Rencana intervensi keperawatan (Kaji skala nyeri, teknik relaksasi, perawatan luka steril, kolaborasi analgetik)..."></textarea>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="small text-secondary mb-0">Implementasi Tindakan :</label>
                            <textarea name="implementasi" rows="2" class="form-control form-control-sm" placeholder="Tindakan keperawatan yang dilakukan..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-secondary mb-0">Evaluasi (SOAP) :</label>
                            <textarea name="evaluasi" rows="2" class="form-control form-control-sm" placeholder="Hasil evaluasi perkembangan..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-sm btn-info px-4 fw-bold shadow-sm text-white">
                        <i class="fas fa-save mr-1"></i> Simpan Formulir Askep
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL REKAP ASKEP -->
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 6px;">
        <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="font-weight-bold text-dark" style="font-size: 13px;">
                <i class="fas fa-list-alt text-info mr-1"></i> DAFTAR FORMULIR ASUHAN KEPERAWATAN
            </span>
            <form method="GET" class="d-flex align-items-center">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm mr-1" placeholder="Cari Nama Klien..." style="width: 220px;">
                <button type="submit" class="btn btn-xs btn-primary px-2"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0 text-nowrap" style="font-size: 11.5px;">
                    <thead class="bg-light">
                        <tr>
                            <th width="100px">Tanggal</th>
                            <th>No. Rawat</th>
                            <th>Nama Klien</th>
                            <th>JK / Umur</th>
                            <th>Pengkajian & Diagnosa</th>
                            <th>Intervensi</th>
                            <th>Implementasi</th>
                            <th>Evaluasi</th>
                            <th width="90px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($row->tanggal)) }}</td>
                                <td class="font-monospace text-primary fw-bold">{{ $row->nomor_register ?: '-' }}</td>
                                <td class="fw-bold text-dark">{{ $row->nama_klien }}</td>
                                <td>{{ $row->jenis_kelamin }}</td>
                                <td><span class="d-inline-block text-truncate" style="max-width: 170px;" title="{{ $row->dx_keperawatan }}">{{ $row->dx_keperawatan ?: $row->keluhan }}</span></td>
                                <td><span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ $row->intervensi }}">{{ $row->intervensi ?: '-' }}</span></td>
                                <td><span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ $row->implementasi }}">{{ $row->implementasi ?: '-' }}</span></td>
                                <td><span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ $row->evaluasi }}">{{ $row->evaluasi ?: '-' }}</span></td>
                                <td class="text-center">
                                    <a href="{{ route('surat.askep.cetak', $row->id) }}" target="_blank" class="btn btn-xs btn-outline-info px-2 mr-1" title="Cetak Lembar Askep">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                    <a href="{{ route('surat.askep.destroy', $row->id) }}" onclick="return confirm('Hapus formulir asuhan keperawatan ini?')" class="btn btn-xs btn-outline-danger px-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center py-4 text-muted">Belum ada catatan asuhan keperawatan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light py-2 px-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">Total Data: {{ $data->total() }} formulir</small>
            {{ $data->links() }}
        </div>
    </div>
</div>

<!-- MODAL CARI PASIEN ASKEP -->
<div class="modal fade" id="modalPilihPasienAskep" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow border-0" style="border-radius: 8px;">
            <div class="modal-header bg-info text-white py-2 px-3">
                <h6 class="modal-title font-weight-bold"><i class="fas fa-user-check mr-2"></i> Pilih Pasien Untuk Form Askep</h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-3">
                <input type="text" id="cariPasienAskepInput" class="form-control form-control-sm mb-3" placeholder="Ketik No. Rawat, No. RM, atau Nama Pasien...">
                <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                    <table class="table table-sm table-hover table-striped mb-0 text-nowrap" style="font-size: 11.5px;">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th>No. Rawat</th>
                                <th>No. RM</th>
                                <th>Nama Pasien</th>
                                <th>Poli</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyCariPasienAskep">
                            <tr><td colspan="5" class="text-center py-3 text-muted">Memuat...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function bukaModalCariPasienAskep() {
        $('#modalPilihPasienAskep').modal('show');
        loadDataPasienAskep('');
    }

    function loadDataPasienAskep(q) {
        $('#tbodyCariPasienAskep').html('<tr><td colspan="5" class="text-center py-3 text-muted"><i class="fas fa-spinner fa-spin mr-1"></i> Memuat...</td></tr>');
        $.getJSON("{{ route('api.pelayanan.cari-pasien') }}", { q: q }, function(data) {
            var html = '';
            $.each(data, function(i, item) {
                html += '<tr style="cursor: pointer;" onclick="pilihPasienAskep(' + JSON.stringify(item).replace(/"/g, '&quot;') + ')">' +
                    '<td class="font-monospace text-primary fw-bold">' + item.no_rawat + '</td>' +
                    '<td class="font-monospace text-success fw-bold">' + item.no_rkm_medis + '</td>' +
                    '<td class="fw-bold">' + item.nama_lengkap + '</td>' +
                    '<td><span class="badge badge-light border">' + item.unit_poli + '</span></td>' +
                    '<td class="text-center"><button type="button" class="btn btn-xs btn-info text-white px-2 font-weight-bold">Pilih</button></td>' +
                '</tr>';
            });
            $('#tbodyCariPasienAskep').html(html || '<tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada data pasien.</td></tr>');
        });
    }

    $('#cariPasienAskepInput').on('keyup', function() {
        loadDataPasienAskep($(this).val());
    });

    function pilihPasienAskep(p) {
        $('#askep_pasien_id').val(p.id);
        $('#askep_no_rawat').val(p.no_rawat);
        $('#askep_nomor_register').val(p.no_rawat);
        $('#askep_nama_klien').val(p.nama_lengkap);
        $('#modalPilihPasienAskep').modal('hide');
    }
</script>
@endsection
