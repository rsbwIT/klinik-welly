@extends('layout.layout')
@section('title', 'Surat Rujukan Pasien')
@section('hide_content_header', 'true')

@section('konten')
<div class="container-fluid px-2 pt-2 pb-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-2 shadow-2xs" role="alert" style="font-size: 12px;">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close p-2" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- CARD FORM PEMBUATAN SURAT RUJUKAN -->
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 6px;">
        <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="font-monospace fw-bold text-danger" style="font-size: 13px;">
                <i class="fas fa-paper-plane mr-1"></i> :: [ Formulir Surat Rujukan Pasien ] ::
            </span>
            <small class="text-muted">Standar Praktik Mandiri Keperawatan Welly</small>
        </div>
        <div class="card-body p-3 bg-white">
            <form action="{{ route('surat.rujukan.store') }}" method="POST" id="formRujukan">
                @csrf
                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <label class="small fw-semibold text-secondary mb-1">Nomor Surat Rujukan :</label>
                        <input type="text" name="no_surat" value="{{ $noSuratOtomatis }}" class="form-control form-control-sm font-monospace fw-bold text-danger" required>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-semibold text-secondary mb-1">Tgl Surat :</label>
                        <input type="date" name="tgl_surat" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-semibold text-secondary mb-1">Tujuan Rujukan (RS / Puskesmas) :</label>
                        <input type="text" name="tujuan_faskes" class="form-control form-control-sm fw-bold" placeholder="Contoh: RSUD Dr. H. Abdul Moeloek" required>
                    </div>
                </div>

                <div class="p-2 mb-2 rounded bg-light border">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small font-weight-bold text-dark text-uppercase"><i class="fas fa-user-injured mr-1 text-primary"></i> Identitas Pasien Yang Dirujuk</span>
                        <button type="button" class="btn btn-xs btn-outline-primary" onclick="bukaModalCariPasienRujukan()">
                            <i class="fas fa-search mr-1"></i> Tarik Data Pasien
                        </button>
                    </div>
                    <input type="hidden" name="pasien_id" id="rujukan_pasien_id">
                    <input type="hidden" name="no_rawat" id="rujukan_no_rawat">
                    <input type="hidden" name="no_rkm_medis" id="rujukan_no_rkm_medis">

                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="small text-secondary mb-0">Nama Pasien :</label>
                            <input type="text" name="nama_pasien" id="rujukan_nama_pasien" class="form-control form-control-sm fw-bold" placeholder="Nama Pasien" required>
                        </div>
                        <div class="col-md-3">
                            <label class="small text-secondary mb-0">Umur / Tgl Lahir :</label>
                            <input type="text" name="umur" id="rujukan_umur" class="form-control form-control-sm" placeholder="Contoh: 35 Tahun">
                        </div>
                        <div class="col-md-2">
                            <label class="small text-secondary mb-0">Jenis Kelamin :</label>
                            <select name="jenis_kelamin" id="rujukan_jenis_kelamin" class="form-select form-select-sm">
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="small text-secondary mb-0">Alamat Pasien :</label>
                            <input type="text" name="alamat" id="rujukan_alamat" class="form-control form-control-sm" placeholder="Alamat Lengkap">
                        </div>
                    </div>
                </div>

                <div class="p-2 mb-2 rounded bg-light border">
                    <span class="small font-weight-bold text-dark text-uppercase d-block mb-2"><i class="fas fa-notes-medical mr-1 text-danger"></i> Hasil Pengkajian Keperawatan & Vital Signs</span>
                    <div class="row g-2 mb-2">
                        <div class="col-md-2 col-4">
                            <label class="small text-secondary mb-0">Tensi (mmHg):</label>
                            <input type="text" name="tensi" id="rujukan_tensi" class="form-control form-control-sm" placeholder="120/80">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="small text-secondary mb-0">Nadi (x/mnt):</label>
                            <input type="text" name="nadi" id="rujukan_nadi" class="form-control form-control-sm" placeholder="80">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="small text-secondary mb-0">RR (x/mnt):</label>
                            <input type="text" name="respirasi" id="rujukan_respirasi" class="form-control form-control-sm" placeholder="20">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="small text-secondary mb-0">Suhu (&deg;C):</label>
                            <input type="text" name="suhu" id="rujukan_suhu" class="form-control form-control-sm" placeholder="36.5">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="small text-secondary mb-0">SpO2 (%):</label>
                            <input type="text" name="spo2" id="rujukan_spo2" class="form-control form-control-sm" placeholder="98">
                        </div>
                        <div class="col-md-2 col-4">
                            <label class="small text-secondary mb-0">Tgl Periksa:</label>
                            <input type="date" name="tgl_pemeriksaan" value="{{ date('Y-m-d') }}" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="small text-secondary mb-0">Keluhan Utama :</label>
                            <textarea name="keluhan_utama" id="rujukan_keluhan" rows="2" class="form-control form-control-sm" placeholder="Keluhan utama saat ini..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-secondary mb-0">Riwayat Penyakit :</label>
                            <textarea name="riwayat_penyakit" id="rujukan_riwayat" rows="2" class="form-control form-control-sm" placeholder="Riwayat penyakit / pengobatan dahulu..."></textarea>
                        </div>
                    </div>
                    <div class="mt-2">
                        <label class="small text-secondary mb-0">Hasil Pemeriksaan / Pengkajian Lainnya :</label>
                        <textarea name="pemeriksaan_lainnya" rows="2" class="form-control form-control-sm" placeholder="Pemeriksaan luka, kondisi jaringan, atau penunjang lab..."></textarea>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="small fw-semibold text-secondary mb-1">Alasan Rujukan :</label>
                        <textarea name="alasan_rujukan" rows="2" class="form-control form-control-sm" placeholder="Perlu penanganan lanjutan spesialis bedah / penyakit dalam..." required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="small fw-semibold text-secondary mb-1">Tindakan Keperawatan Yang Telah Diberikan :</label>
                        <textarea name="tindakan_diberikan" rows="2" class="form-control form-control-sm" placeholder="Debridement luka, pembalutan modern dressing, resusitasi cairan..."></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center border-top pt-2">
                    <span class="small text-muted font-italic">Perawat Pengirim: <strong>Welly Prestikasari, S.Kep., Ners., M.Kep.</strong></span>
                    <button type="submit" class="btn btn-sm btn-danger px-4 fw-bold shadow-sm">
                        <i class="fas fa-save mr-1"></i> Simpan Surat Rujukan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL DAFTAR SURAT RUJUKAN -->
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 6px;">
        <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="font-weight-bold text-dark" style="font-size: 13px;">
                <i class="fas fa-history text-danger mr-1"></i> Daftar Arsip Surat Rujukan Pasien
            </span>
            <form method="GET" class="d-flex align-items-center">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm mr-1" placeholder="Cari No. Surat / Pasien..." style="width: 220px;">
                <button type="submit" class="btn btn-xs btn-primary px-2"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 text-nowrap" style="font-size: 11.5px;">
                    <thead class="bg-light">
                        <tr>
                            <th width="140px">Nomor Surat</th>
                            <th>Tgl Surat</th>
                            <th>Nama Pasien</th>
                            <th>Umur / JK</th>
                            <th>Tujuan Rujukan</th>
                            <th>Alasan Rujukan</th>
                            <th width="90px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <td class="font-monospace text-danger fw-bold">{{ $row->no_surat }}</td>
                                <td>{{ $row->tgl_surat }}</td>
                                <td class="fw-bold">{{ $row->nama_pasien }}</td>
                                <td>{{ $row->umur ?? '-' }} ({{ $row->jenis_kelamin }})</td>
                                <td><span class="badge badge-light border text-dark">{{ $row->tujuan_faskes }}</span></td>
                                <td><span class="d-inline-block text-truncate" style="max-width: 200px;" title="{{ $row->alasan_rujukan }}">{{ $row->alasan_rujukan }}</span></td>
                                <td class="text-center">
                                    <a href="{{ route('surat.rujukan.cetak', $row->id) }}" target="_blank" class="btn btn-xs btn-outline-danger px-2 mr-1" title="Cetak Surat Rujukan">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                    <a href="{{ route('surat.rujukan.destroy', $row->id) }}" onclick="return confirm('Apakah Anda yakin ingin menghapus surat rujukan ini?')" class="btn btn-xs btn-outline-secondary px-1" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada arsip surat rujukan pasien.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light py-2 px-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">Total Arsip: {{ $data->total() }} surat</small>
            {{ $data->links() }}
        </div>
    </div>
</div>

<!-- MODAL TARIK PASIEN UNTUK RUJUKAN -->
<div class="modal fade" id="modalPilihPasienRujukan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow border-0" style="border-radius: 8px;">
            <div class="modal-header bg-danger text-white py-2 px-3">
                <h6 class="modal-title font-weight-bold"><i class="fas fa-user-check mr-2"></i> Pilih Pasien Untuk Surat Rujukan</h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-3">
                <input type="text" id="cariPasienRujukanInput" class="form-control form-control-sm mb-3" placeholder="Ketik No. Rawat, No. RM, atau Nama Pasien...">
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
                        <tbody id="tbodyCariPasienRujukan">
                            <tr><td colspan="5" class="text-center py-3 text-muted">Memuat data pasien...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function bukaModalCariPasienRujukan() {
        $('#modalPilihPasienRujukan').modal('show');
        loadDataPasienRujukan('');
    }

    function loadDataPasienRujukan(q) {
        $('#tbodyCariPasienRujukan').html('<tr><td colspan="5" class="text-center py-3 text-muted"><i class="fas fa-spinner fa-spin mr-1"></i> Memuat...</td></tr>');
        $.getJSON("{{ route('api.pelayanan.cari-pasien') }}", { q: q }, function(data) {
            var html = '';
            $.each(data, function(i, item) {
                html += '<tr style="cursor: pointer;" onclick="pilihPasienRujukan(' + JSON.stringify(item).replace(/"/g, '&quot;') + ')">' +
                    '<td class="font-monospace text-primary fw-bold">' + item.no_rawat + '</td>' +
                    '<td class="font-monospace text-success fw-bold">' + item.no_rkm_medis + '</td>' +
                    '<td class="fw-bold">' + item.nama_lengkap + '</td>' +
                    '<td><span class="badge badge-light border">' + item.unit_poli + '</span></td>' +
                    '<td class="text-center"><button type="button" class="btn btn-xs btn-danger px-2 font-weight-bold">Pilih</button></td>' +
                '</tr>';
            });
            $('#tbodyCariPasienRujukan').html(html || '<tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada data pasien ditemukan.</td></tr>');
        });
    }

    $('#cariPasienRujukanInput').on('keyup', function() {
        loadDataPasienRujukan($(this).val());
    });

    function pilihPasienRujukan(p) {
        $('#rujukan_pasien_id').val(p.id);
        $('#rujukan_no_rawat').val(p.no_rawat);
        $('#rujukan_no_rkm_medis').val(p.no_rkm_medis);
        $('#rujukan_nama_pasien').val(p.nama_lengkap);
        $('#modalPilihPasienRujukan').modal('hide');
    }
</script>
@endsection

