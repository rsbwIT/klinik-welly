@extends('layout.layout')

@section('title', 'Master Jadwal Praktek Dokter')
@section('hide_content_header', 'true')

@section('konten')

<style>
    /* Khanza Styling Presisi */
    .khanza-card {
        background: #ffffff;
        border: 1px solid #d2d6de;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        margin-bottom: 15px;
    }
    .khanza-header {
        background: #f4f6f9;
        border-bottom: 1px solid #d2d6de;
        padding: 8px 12px;
        font-weight: bold;
        font-size: 13px;
        color: #333;
    }
    .form-khanza label {
        font-size: 12px;
        font-weight: 600;
        color: #444;
        margin-bottom: 0;
    }
    .form-khanza input, .form-khanza select {
        border-radius: 3px;
        font-size: 12px;
        height: 28px;
        padding: 2px 8px;
    }
    .table-khanza {
        font-size: 12px;
        margin-bottom: 0;
    }
    .table-khanza thead th {
        background: #f9fafb;
        color: #333;
        font-weight: bold;
        border-bottom: 2px solid #dee2e6 !important;
        padding: 6px 8px;
        vertical-align: middle;
    }
    .table-khanza tbody td {
        padding: 5px 8px;
        vertical-align: middle;
        border-top: 1px solid #f1f3f5;
    }
    .table-khanza tbody tr:hover {
        background-color: #f1f7fd;
        cursor: pointer;
    }
    .table-khanza tbody tr.selected {
        background-color: #ffeaa7 !important;
    }
    .btn-khanza {
        border-radius: 3px;
        font-size: 11.5px;
        font-weight: bold;
        padding: 4px 14px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
</style>

<div class="container-fluid pt-2">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 mb-2" role="alert" style="font-size: 12px;">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close py-2" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="khanza-card">
        <!-- HEADER ::[ Jadwal Praktek ]:: -->
        <div class="khanza-header d-flex justify-content-between align-items-center">
            <span>::[ Jadwal Praktek Dokter ]::</span>
        </div>

        <div class="p-3">
            <!-- FORM INPUT JADWAL GAYA KHANZA (PERSIS GAMBAR LAMPIRAN) -->
            <form action="{{ route('master.jadwal.store') }}" method="POST" id="formJadwal" class="form-khanza">
                @csrf
                <!-- Baris 1: Dokter (Kode + Nama + Tombol Pensil) -->
                <div class="row align-items-center mb-2">
                    <div class="col-md-1" style="max-width: 90px;">
                        <label>Dokter :</label>
                    </div>
                    <div class="col-md-8">
                        <div class="input-group input-group-sm">
                            <input type="text" name="kd_dokter" id="in_kd_dokter" class="form-control font-monospace font-weight-bold bg-light" style="max-width: 100px;" placeholder="Kode" readonly required>
                            <input type="text" id="in_nm_dokter" class="form-control bg-white font-weight-bold" placeholder="Pilih dokter dari tombol di samping..." readonly required onclick="bukaModalPilihDokter()">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-default border" onclick="bukaModalPilihDokter()" title="Pilih Dokter">
                                    <i class="fas fa-pencil-alt text-primary"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Baris 2: Hari Kerja, Jam Mulai s.d Jam Selesai -->
                <div class="row align-items-center mb-2">
                    <div class="col-md-1" style="max-width: 90px;">
                        <label>Hari Kerja :</label>
                    </div>
                    <div class="col-md-2" style="max-width: 130px;">
                        <select name="hari_kerja" id="in_hari_kerja" class="form-control form-control-sm font-weight-bold">
                            <option value="SENIN">SENIN</option>
                            <option value="SELASA">SELASA</option>
                            <option value="RABU">RABU</option>
                            <option value="KAMIS">KAMIS</option>
                            <option value="JUMAT">JUMAT</option>
                            <option value="SABTU">SABTU</option>
                            <option value="AKHAD">AKHAD</option>
                        </select>
                    </div>
                    <div class="col-md-1 text-right" style="max-width: 60px;">
                        <label>Jam :</label>
                    </div>
                    <div class="col-md-6 d-flex align-items-center gap-1">
                        <!-- Jam Mulai -->
                        <select id="jm_jam" class="form-control form-control-sm" style="width: 55px;"></select>
                        <span>:</span>
                        <select id="jm_menit" class="form-control form-control-sm" style="width: 55px;"></select>
                        <span>:</span>
                        <select id="jm_detik" class="form-control form-control-sm" style="width: 55px;"></select>

                        <span class="mx-2 text-muted font-weight-bold">s.d.</span>

                        <!-- Jam Selesai -->
                        <select id="js_jam" class="form-control form-control-sm" style="width: 55px;"></select>
                        <span>:</span>
                        <select id="js_menit" class="form-control form-control-sm" style="width: 55px;"></select>
                        <span>:</span>
                        <select id="js_detik" class="form-control form-control-sm" style="width: 55px;"></select>

                        <!-- Hidden Real Values -->
                        <input type="hidden" name="jam_mulai" id="in_jam_mulai" value="08:00:00">
                        <input type="hidden" name="jam_selesai" id="in_jam_selesai" value="14:00:00">
                    </div>
                </div>

                <!-- Baris 3: Kuota & Poliklinik -->
                <div class="row align-items-center mb-3">
                    <div class="col-md-1" style="max-width: 90px;">
                        <label>Kuota :</label>
                    </div>
                    <div class="col-md-2" style="max-width: 90px;">
                        <input type="number" name="kuota" id="in_kuota" class="form-control form-control-sm font-monospace text-center font-weight-bold" value="40" min="1" required>
                    </div>
                    <div class="col-md-1 text-right" style="max-width: 80px;">
                        <label>Poliklinik :</label>
                    </div>
                    <div class="col-md-7">
                        <div class="input-group input-group-sm">
                            <input type="text" name="kd_poli" id="in_kd_poli" class="form-control font-monospace font-weight-bold bg-light" style="max-width: 80px;" placeholder="Kode" readonly required>
                            <input type="text" id="in_nm_poli" class="form-control bg-white font-weight-bold" placeholder="Pilih poliklinik..." readonly required onclick="bukaModalPilihPoli()">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-default border" onclick="bukaModalPilihPoli()" title="Pilih Poliklinik">
                                    <i class="fas fa-pencil-alt text-success"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- TABEL JADWAL PRAKTEK DOKTER -->
            <div class="table-responsive border" style="max-height: 420px; overflow-y: auto;">
                <table class="table table-khanza table-bordered table-striped align-middle mb-0" id="tableJadwal">
                    <thead class="sticky-top">
                        <tr>
                            <th width="30" class="text-center">P</th>
                            <th width="100">Kode Dokter</th>
                            <th>Nama Dokter</th>
                            <th width="90" class="text-center">Hari Kerja</th>
                            <th width="90" class="text-center">Jam Mulai</th>
                            <th width="90" class="text-center">Jam Selesai</th>
                            <th>Poliklinik</th>
                            <th width="70" class="text-center">Kuota</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwals as $j)
                            <tr class="row-jadwal" 
                                onclick="pilihBaris(this)"
                                data-kddokter="{{ $j->kd_dokter }}" 
                                data-nmdokter="{{ $j->nm_dokter }}"
                                data-hari="{{ $j->hari_kerja }}" 
                                data-jammulai="{{ $j->jam_mulai }}" 
                                data-jamselesai="{{ $j->jam_selesai }}" 
                                data-kdpoli="{{ $j->kd_poli }}" 
                                data-nmpoli="{{ $j->nm_poli }}" 
                                data-kuota="{{ $j->kuota }}">
                                <td class="text-center">
                                    <input type="checkbox" class="cb-jadwal">
                                </td>
                                <td class="font-monospace text-primary font-weight-bold">{{ $j->kd_dokter }}</td>
                                <td class="font-weight-bold text-dark">{{ $j->nm_dokter ?? '-' }}</td>
                                <td class="text-center font-weight-bold">
                                    <span class="badge badge-light border">{{ $j->hari_kerja }}</span>
                                </td>
                                <td class="text-center font-monospace">{{ $j->jam_mulai }}</td>
                                <td class="text-center font-monospace">{{ $j->jam_selesai }}</td>
                                <td>{{ $j->nm_poli ?? '-' }}</td>
                                <td class="text-center font-monospace font-weight-bold text-success">{{ $j->kuota }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    Tidak ada data jadwal praktek dokter yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- FOOTER TOOLBAR: Key Word, Cari, Record, Tombol Aksi (Simpan, Baru, Hapus, Ganti, Cetak, Keluar) -->
            <div class="d-flex flex-wrap align-items-center justify-content-between pt-3 mt-2 border-top">
                <form action="{{ route('master.jadwal.index') }}" method="GET" class="d-flex align-items-center gap-2 mb-2 mb-md-0">
                    <label class="small font-weight-bold text-secondary mb-0" style="min-width: 65px;">Key Word :</label>
                    <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control form-control-sm" placeholder="Cari nama, hari, poli..." style="min-width: 220px; border-radius: 4px;">
                    <button type="submit" class="btn btn-sm btn-light border px-2 text-success font-weight-bold" title="Cari Data">
                        <i class="fas fa-check"></i>
                    </button>
                    <a href="{{ route('master.jadwal.index') }}" class="btn btn-sm btn-light border px-2 text-secondary" title="Reset Filter">
                        <i class="fas fa-undo"></i>
                    </a>
                    <span class="small font-monospace text-muted px-2">Record : <strong>{{ count($jadwals) }}</strong></span>
                </form>

                <div class="d-flex gap-1 flex-wrap">
                    <!-- Tombol Simpan -->
                    <button type="button" class="btn btn-light border btn-khanza text-primary" onclick="submitFormJadwal()">
                        <i class="fas fa-save text-primary"></i> Simpan
                    </button>

                    <!-- Tombol Baru -->
                    <button type="button" class="btn btn-light border btn-khanza text-dark" onclick="resetFormJadwal()">
                        <i class="fas fa-file text-danger"></i> Baru
                    </button>

                    <!-- Tombol Hapus -->
                    <button type="button" class="btn btn-light border btn-khanza text-danger" onclick="hapusJadwalTerpilih()">
                        <i class="fas fa-trash-alt text-info"></i> Hapus
                    </button>

                    <!-- Tombol Ganti -->
                    <button type="button" class="btn btn-light border btn-khanza text-warning" onclick="submitFormJadwal()">
                        <i class="fas fa-edit text-warning"></i> Ganti
                    </button>

                    <!-- Tombol Cetak -->
                    <button type="button" class="btn btn-light border btn-khanza text-secondary" onclick="window.print()">
                        <i class="fas fa-print text-secondary"></i> Cetak
                    </button>

                    <!-- Tombol Keluar -->
                    <a href="{{ route('rawatjalan.index') }}" class="btn btn-light border btn-khanza text-danger">
                        <i class="fas fa-times text-danger"></i> Keluar
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- FORM POST UNTUK DELETE JADWAL -->
<form id="formDeleteJadwal" action="{{ route('master.jadwal.destroy') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="kd_dokter" id="del_kd_dokter">
    <input type="hidden" name="hari_kerja" id="del_hari_kerja">
    <input type="hidden" name="jam_mulai" id="del_jam_mulai">
</form>

<!-- MODAL PILIH DOKTER -->
<div class="modal fade" id="modalPilihDokter" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 bg-light">
                <h6 class="modal-title font-weight-bold"><i class="fas fa-user-md mr-1 text-primary"></i> Pilih Dokter</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-2 table-responsive" style="max-height: 350px;">
                <table class="table table-hover table-sm table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th width="100">Kode</th>
                            <th>Nama Dokter</th>
                            <th>Spesialis</th>
                            <th width="60" class="text-center">Pilih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dokters as $doc)
                            <tr>
                                <td class="font-monospace text-primary font-weight-bold">{{ $doc->kd_dokter }}</td>
                                <td class="font-weight-bold">{{ $doc->nm_dokter }}</td>
                                <td>{{ $doc->spesialis ?? '-' }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-primary font-weight-bold" onclick="selectDokter('{{ $doc->kd_dokter }}', '{{ addslashes($doc->nm_dokter) }}')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PILIH POLIKLINIK -->
<div class="modal fade" id="modalPilihPoli" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header py-2 bg-light">
                <h6 class="modal-title font-weight-bold"><i class="fas fa-clinic-medical mr-1 text-success"></i> Pilih Poliklinik</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-2 table-responsive" style="max-height: 350px;">
                <table class="table table-hover table-sm table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th width="80">Kode</th>
                            <th>Nama Poli</th>
                            <th width="60" class="text-center">Pilih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($polis as $poli)
                            <tr>
                                <td class="font-monospace text-success font-weight-bold">{{ $poli->kd_poli }}</td>
                                <td class="font-weight-bold">{{ $poli->nm_poli }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-success font-weight-bold" onclick="selectPoli('{{ $poli->kd_poli }}', '{{ addslashes($poli->nm_poli) }}')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Inisialisasi dropdown Jam:Menit:Detik
    document.addEventListener('DOMContentLoaded', function() {
        populateTimeSelects();
        syncTimeInputs();
    });

    function padZero(n) {
        return n < 10 ? '0' + n : n;
    }

    function populateTimeSelects() {
        let optJam = '', optMenit = '';
        for (let i = 0; i < 24; i++) {
            optJam += `<option value="${padZero(i)}">${padZero(i)}</option>`;
        }
        for (let i = 0; i < 60; i++) {
            optMenit += `<option value="${padZero(i)}">${padZero(i)}</option>`;
        }

        $('#jm_jam, #js_jam').html(optJam);
        $('#jm_menit, #js_menit, #jm_detik, #js_detik').html(optMenit);

        // Nilai awal
        $('#jm_jam').val('08');
        $('#jm_menit').val('00');
        $('#jm_detik').val('00');

        $('#js_jam').val('14');
        $('#js_menit').val('00');
        $('#js_detik').val('00');

        // Set default dokter & poli pertama
        @if(count($dokters) > 0)
            selectDokter('{{ $dokters[0]->kd_dokter }}', '{{ addslashes($dokters[0]->nm_dokter) }}');
        @endif
        @if(count($polis) > 0)
            selectPoli('{{ $polis[0]->kd_poli }}', '{{ addslashes($polis[0]->nm_poli) }}');
        @endif
    }

    function syncTimeInputs() {
        let jamMulai = $('#jm_jam').val() + ':' + $('#jm_menit').val() + ':' + $('#jm_detik').val();
        let jamSelesai = $('#js_jam').val() + ':' + $('#js_menit').val() + ':' + $('#js_detik').val();

        $('#in_jam_mulai').val(jamMulai);
        $('#in_jam_selesai').val(jamSelesai);
    }

    $('#jm_jam, #jm_menit, #jm_detik, #js_jam, #js_menit, #js_detik').on('change', function() {
        syncTimeInputs();
    });

    function bukaModalPilihDokter() {
        $('#modalPilihDokter').appendTo('body').modal('show');
    }

    function bukaModalPilihPoli() {
        $('#modalPilihPoli').appendTo('body').modal('show');
    }

    function selectDokter(kd, nm) {
        $('#in_kd_dokter').val(kd);
        $('#in_nm_dokter').val(nm);
        $('#modalPilihDokter').modal('hide');
    }

    function selectPoli(kd, nm) {
        $('#in_kd_poli').val(kd);
        $('#in_nm_poli').val(nm);
        $('#modalPilihPoli').modal('hide');
    }

    function pilihBaris(tr) {
        $('.row-jadwal').removeClass('selected');
        $('.cb-jadwal').prop('checked', false);

        $(tr).addClass('selected');
        $(tr).find('.cb-jadwal').prop('checked', true);

        let kdDokter = $(tr).data('kddokter');
        let nmDokter = $(tr).data('nmdokter');
        let hari = $(tr).data('hari');
        let jamMulai = String($(tr).data('jammulai'));
        let jamSelesai = String($(tr).data('jamselesai'));
        let kdPoli = $(tr).data('kdpoli');
        let nmPoli = $(tr).data('nmpoli');
        let kuota = $(tr).data('kuota');

        $('#in_kd_dokter').val(kdDokter);
        $('#in_nm_dokter').val(nmDokter);
        $('#in_hari_kerja').val(hari);
        $('#in_kuota').val(kuota);
        $('#in_kd_poli').val(kdPoli);
        $('#in_nm_poli').val(nmPoli);

        // Parse jam mulai
        if (jamMulai) {
            let partM = jamMulai.split(':');
            $('#jm_jam').val(partM[0] || '08');
            $('#jm_menit').val(partM[1] || '00');
            $('#jm_detik').val(partM[2] || '00');
        }

        // Parse jam selesai
        if (jamSelesai) {
            let partS = jamSelesai.split(':');
            $('#js_jam').val(partS[0] || '12');
            $('#js_menit').val(partS[1] || '00');
            $('#js_detik').val(partS[2] || '00');
        }

        syncTimeInputs();
    }

    function submitFormJadwal() {
        if (!$('#in_kd_dokter').val()) {
            alert('Silakan pilih dokter terlebih dahulu.');
            return;
        }
        if (!$('#in_kd_poli').val()) {
            alert('Silakan pilih poliklinik terlebih dahulu.');
            return;
        }
        syncTimeInputs();
        $('#formJadwal').submit();
    }

    function resetFormJadwal() {
        $('.row-jadwal').removeClass('selected');
        $('.cb-jadwal').prop('checked', false);

        $('#in_hari_kerja').val('SENIN');
        $('#in_kuota').val(40);
        $('#jm_jam').val('08'); $('#jm_menit').val('00'); $('#jm_detik').val('00');
        $('#js_jam').val('14'); $('#js_menit').val('00'); $('#js_detik').val('00');
        syncTimeInputs();
    }

    function hapusJadwalTerpilih() {
        let kdDokter = $('#in_kd_dokter').val();
        let hari = $('#in_hari_kerja').val();
        let jamMulai = $('#in_jam_mulai').val();

        if (!kdDokter) {
            alert('Pilih jadwal dokter di tabel yang ingin dihapus.');
            return;
        }

        if (confirm(`Hapus jadwal praktek dokter ${$('#in_nm_dokter').val()} hari ${hari} jam ${jamMulai}?`)) {
            $('#del_kd_dokter').val(kdDokter);
            $('#del_hari_kerja').val(hari);
            $('#del_jam_mulai').val(jamMulai);
            $('#formDeleteJadwal').submit();
        }
    }
</script>

@endsection

