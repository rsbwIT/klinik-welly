@extends('layout.layout')
@section('title', 'Surat Keterangan Sehat')
@section('hide_content_header', 'true')

@section('konten')
<div class="container-fluid px-2 pt-2 pb-4">
    <!-- Header Page -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 p-3 bg-white border rounded shadow-sm">
        <div class="d-flex align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.1); color: #059669; font-size: 20px;">
                <i class="fas fa-heartbeat"></i>
            </div>
            <div>
                <h5 class="font-weight-bold mb-0 text-dark">Surat Keterangan Sehat</h5>
                <small class="text-muted">Pemeriksaan fisik dan tanda vital untuk keperluan kerja, studi, atau administrasi lainnya</small>
            </div>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('surat.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Persuratan
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row">
        <!-- FORM INPUT SURAT SEHAT -->
        <div class="col-lg-5 mb-4">
            <div class="card card-outline card-success shadow-sm h-100">
                <div class="card-header bg-white">
                    <h6 class="card-title font-weight-bold text-success mb-0">
                        <i class="fas fa-user-check mr-1"></i> Formulir Surat Keterangan Sehat
                    </h6>
                </div>
                <form action="{{ route('surat.sehat.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @php
                            $initNama = $selectedPasien ? $selectedPasien->nama_lengkap : '';
                            $initUmur = '';
                            if ($selectedPasien && $selectedPasien->tgl_lahir) {
                                $initUmur = \Carbon\Carbon::parse($selectedPasien->tgl_lahir)->age . ' Th';
                            }
                            $initJk = $selectedPasien ? (($selectedPasien->jenis_kelamin == 'Perempuan' || $selectedPasien->jenis_kelamin == 'P') ? 'Perempuan' : 'Laki-laki') : 'Laki-laki';
                            $initAlamat = $selectedPasien ? $selectedPasien->alamat_lengkap : '';
                            $initGolDarah = $selectedPasien ? ($selectedPasien->gol_darah ?? '-') : '-';
                        @endphp

                        <!-- Tarik Pasien Cepat (Modal Pencarian Pasien) -->
                        <div class="p-2 mb-3 bg-light rounded border d-flex justify-content-between align-items-center">
                            <div>
                                <span class="small font-weight-bold text-dark d-block">
                                    <i class="fas fa-user-check text-success mr-1"></i> Data Pasien Terpilih
                                </span>
                                <small class="text-muted" id="label_pasien_sehat_terpilih">
                                    @if($selectedPasien)
                                        <span class="text-success font-weight-bold"><i class="fas fa-check-circle"></i> {{ $selectedPasien->nama_lengkap }} ({{ $selectedPasien->no_rkm_medis ?? '-' }})</span>
                                    @else
                                        Belum ada pasien yang ditarik
                                    @endif
                                </small>
                            </div>
                            <button type="button" class="btn btn-sm btn-success font-weight-bold" onclick="bukaModalCariPasienSehat()">
                                <i class="fas fa-search mr-1"></i> Cari Pasien
                            </button>
                        </div>

                        <input type="hidden" name="pasien_id" id="sehat_pasien_id" value="{{ $selectedPasien->id ?? '' }}">

                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-dark">Nomor Surat <span class="text-danger">*</span></label>
                            <input type="text" name="no_surat" class="form-control form-control-sm font-weight-bold text-success" value="{{ $defaultNoSurat }}" required>
                        </div>

                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-dark">Nama Pemohon / Pasien <span class="text-danger">*</span></label>
                            <input type="text" name="nama_pasien" id="sehat_nama_pasien" class="form-control form-control-sm" required placeholder="Nama lengkap" value="{{ $initNama }}">
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label class="small font-weight-bold text-dark">Umur</label>
                                    <input type="text" name="umur" id="sehat_umur" class="form-control form-control-sm" placeholder="Cth: 24 Tahun" value="{{ $initUmur }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label class="small font-weight-bold text-dark">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" id="sehat_jenis_kelamin" class="form-control form-control-sm">
                                        <option value="Laki-laki" {{ $initJk == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ $initJk == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-dark">Pekerjaan</label>
                            <input type="text" name="pekerjaan" id="sehat_pekerjaan" class="form-control form-control-sm" placeholder="Cth: Mahasiswa / Pelajar / Karyawan">
                        </div>

                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-dark">Alamat</label>
                            <textarea name="alamat" id="sehat_alamat" class="form-control form-control-sm" rows="2" placeholder="Alamat lengkap">{{ $initAlamat }}</textarea>
                        </div>

                        <!-- Pemeriksaan Fisik & TTV -->
                        <div class="p-2 mb-2 bg-light rounded border border-success">
                            <label class="small font-weight-bold text-success mb-2">
                                <i class="fas fa-stethoscope mr-1"></i> Hasil Pemeriksaan Fisik & Vital Sign:
                            </label>
                            <div class="row">
                                <div class="col-6 col-md-3">
                                    <div class="form-group mb-1">
                                        <label class="small text-muted font-weight-bold">Tinggi (cm)</label>
                                        <input type="text" name="tinggi_badan" class="form-control form-control-sm" placeholder="165 cm">
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="form-group mb-1">
                                        <label class="small text-muted font-weight-bold">Berat (kg)</label>
                                        <input type="text" name="berat_badan" class="form-control form-control-sm" placeholder="60 kg">
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="form-group mb-1">
                                        <label class="small text-muted font-weight-bold">Gol. Darah</label>
                                        <select name="golongan_darah" class="form-control form-control-sm">
                                            <option value="-" {{ $initGolDarah == '-' ? 'selected' : '' }}>-</option>
                                            <option value="A" {{ $initGolDarah == 'A' ? 'selected' : '' }}>A</option>
                                            <option value="B" {{ $initGolDarah == 'B' ? 'selected' : '' }}>B</option>
                                            <option value="AB" {{ $initGolDarah == 'AB' ? 'selected' : '' }}>AB</option>
                                            <option value="O" {{ $initGolDarah == 'O' ? 'selected' : '' }}>O</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="form-group mb-1">
                                        <label class="small text-muted font-weight-bold">Tensi (TD)</label>
                                        <input type="text" name="tensi" class="form-control form-control-sm" placeholder="120/80">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-4">
                                    <div class="form-group mb-1">
                                        <label class="small text-muted font-weight-bold">Nadi</label>
                                        <input type="text" name="nadi" class="form-control form-control-sm" placeholder="80 x/mnt">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group mb-1">
                                        <label class="small text-muted font-weight-bold">Suhu</label>
                                        <input type="text" name="suhu" class="form-control form-control-sm" placeholder="36.5 °C">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group mb-1">
                                        <label class="small text-muted font-weight-bold">Respirasi</label>
                                        <input type="text" name="respirasi" class="form-control form-control-sm" placeholder="20 x/mnt">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-6">
                                    <div class="form-group mb-1">
                                        <label class="small text-muted font-weight-bold">Tes Buta Warna</label>
                                        <select name="buta_warna" class="form-control form-control-sm">
                                            <option value="Tidak Buta Warna">Tidak Buta Warna</option>
                                            <option value="Buta Warna Parsial">Buta Warna Parsial</option>
                                            <option value="Buta Warna Total">Buta Warna Total</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-1">
                                        <label class="small text-muted font-weight-bold">Kesimpulan</label>
                                        <select name="kesimpulan" class="form-control form-control-sm font-weight-bold text-success">
                                            <option value="SEHAT" selected>SEHAT</option>
                                            <option value="SEHAT DENGAN CATATAN">SEHAT DENGAN CATATAN</option>
                                            <option value="KURANG SEHAT">KURANG SEHAT</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-dark">Keperluan Surat <span class="text-danger">*</span></label>
                            <input type="text" name="keperluan" class="form-control form-control-sm" required placeholder="Cth: Persyaratan Melamar Pekerjaan / Masuk Kuliah / SIM">
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label class="small font-weight-bold text-dark">Tanggal Terbit</label>
                                    <input type="date" name="tanggal_surat" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label class="small font-weight-bold text-dark">Kota Terbit</label>
                                    <input type="text" name="lokasi_surat" class="form-control form-control-sm" value="Pesawaran">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-dark">Nama Pemeriksa / Praktisi</label>
                            <input type="text" name="nama_pemeriksa" class="form-control form-control-sm" value="Welly Prestikasari, S.Kep., Ners., M.Kep., CWCCA">
                        </div>

                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-dark">Nomor SIPP</label>
                            <input type="text" name="sipp_pemeriksa" class="form-control form-control-sm" value="503/36/SIPP/IV.14/VIII/2021">
                        </div>
                    </div>
                    <div class="card-footer bg-white text-right">
                        <button type="submit" class="btn btn-sm btn-success font-weight-bold">
                            <i class="fas fa-save mr-1"></i> Terbitkan Surat Sehat
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- RIWAYAT & ARSIP SURAT SEHAT -->
        <div class="col-lg-7 mb-4">
            <div class="card card-outline card-secondary shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="card-title font-weight-bold text-dark mb-0">
                        <i class="fas fa-list-alt mr-1"></i> Arsip Surat Keterangan Sehat
                    </h6>
                    <form action="{{ route('surat.sehat.index') }}" method="GET" class="form-inline m-0">
                        <div class="input-group input-group-sm">
                            <input type="text" name="q" class="form-control" placeholder="Cari nama / no surat..." value="{{ request('q') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover table-striped table-bordered text-sm m-0">
                        <thead class="bg-light">
                            <tr class="text-center">
                                <th width="40px">No</th>
                                <th>No. Surat</th>
                                <th>Nama Pasien</th>
                                <th>Hasil Fisik</th>
                                <th>Keperluan</th>
                                <th>Kesimpulan</th>
                                <th width="120px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listSehat as $key => $s)
                                <tr>
                                    <td class="text-center">{{ $listSehat->firstItem() + $key }}</td>
                                    <td>
                                        <span class="font-weight-bold text-success">{{ $s->no_surat }}</span><br>
                                        <small class="text-muted">{{ date('d/m/Y', strtotime($s->tanggal_surat)) }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $s->nama_pasien }}</strong><br>
                                        <small class="text-muted">{{ $s->umur ?: '-' }} | {{ $s->jenis_kelamin }}</small>
                                    </td>
                                    <td class="small">
                                        TD: <strong>{{ $s->tensi ?: '-' }}</strong><br>
                                        TB/BB: {{ $s->tinggi_badan ?: '-' }} / {{ $s->berat_badan ?: '-' }}
                                    </td>
                                    <td>
                                        <span class="small font-weight-bold text-dark">{{ $s->keperluan }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-success px-2 py-1">{{ $s->kesimpulan }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('surat.sehat.cetak', $s->id) }}" target="_blank" class="btn btn-xs btn-primary font-weight-bold" title="Cetak Surat">
                                            <i class="fas fa-print"></i> Cetak
                                        </a>
                                        <a href="{{ route('surat.sehat.destroy', $s->id) }}" class="btn btn-xs btn-danger" onclick="return confirm('Yakin ingin menghapus arsip surat ini?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle mr-1"></i> Belum ada data surat keterangan sehat yang diterbitkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($listSehat->hasPages())
                    <div class="card-footer bg-white py-2">
                        {{ $listSehat->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- MODAL CARI PASIEN SURAT SEHAT -->
<div class="modal fade" id="modalPilihPasienSehat" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow border-0" style="border-radius: 8px;">
            <div class="modal-header bg-success text-white py-2 px-3">
                <h6 class="modal-title font-weight-bold"><i class="fas fa-user-check mr-2"></i> Pilih Pasien Untuk Surat Keterangan Sehat</h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-3">
                <input type="text" id="cariPasienSehatInput" class="form-control form-control-sm mb-3" placeholder="Ketik No. Rawat, No. RM, atau Nama Pasien...">
                <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                    <table class="table table-sm table-hover table-striped mb-0 text-nowrap" style="font-size: 11.5px;">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th>No. Rawat</th>
                                <th>No. RM</th>
                                <th>Nama Pasien</th>
                                <th>Jenis Kelamin</th>
                                <th>Gol. Darah</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyCariPasienSehat">
                            <tr><td colspan="6" class="text-center py-3 text-muted">Memuat data pasien...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function bukaModalCariPasienSehat() {
    $('#modalPilihPasienSehat').modal('show');
    loadDataPasienSehat('');
}

function loadDataPasienSehat(q) {
    $('#tbodyCariPasienSehat').html('<tr><td colspan="6" class="text-center py-3 text-muted"><i class="fas fa-spinner fa-spin mr-1"></i> Memuat...</td></tr>');
    $.getJSON("{{ route('api.pelayanan.cari-pasien') }}", { q: q }, function(data) {
        var html = '';
        $.each(data, function(i, item) {
            html += '<tr style="cursor: pointer;" onclick="pilihPasienSehat(' + JSON.stringify(item).replace(/"/g, '&quot;') + ')">' +
                '<td class="font-monospace text-primary fw-bold">' + item.no_rawat + '</td>' +
                '<td class="font-monospace text-success fw-bold">' + item.no_rkm_medis + '</td>' +
                '<td class="fw-bold">' + item.nama_lengkap + '</td>' +
                '<td>' + (item.jenis_kelamin || '-') + '</td>' +
                '<td>' + (item.gol_darah || '-') + '</td>' +
                '<td class="text-center"><button type="button" class="btn btn-xs btn-success px-2 font-weight-bold">Pilih</button></td>' +
            '</tr>';
        });
        $('#tbodyCariPasienSehat').html(html || '<tr><td colspan="6" class="text-center py-3 text-muted">Tidak ada data pasien ditemukan.</td></tr>');
    });
}

$('#cariPasienSehatInput').on('keyup', function() {
    loadDataPasienSehat($(this).val());
});

function pilihPasienSehat(p) {
    $('#sehat_pasien_id').val(p.id);
    $('#sehat_nama_pasien').val(p.nama_lengkap);
    $('#sehat_umur').val(p.umur && p.umur !== '-' ? p.umur : '');
    if (p.jenis_kelamin) {
        var jkVal = (p.jenis_kelamin.toLowerCase().indexOf('l') === 0) ? 'Laki-laki' : 'Perempuan';
        $('#sehat_jenis_kelamin').val(jkVal);
    }
    $('#sehat_alamat').val(p.alamat_lengkap || '');
    if (p.gol_darah && p.gol_darah !== '-') {
        $('select[name="golongan_darah"]').val(p.gol_darah);
    }
    $('#label_pasien_sehat_terpilih').html('<span class="text-success font-weight-bold"><i class="fas fa-check-circle"></i> ' + p.nama_lengkap + ' (' + p.no_rkm_medis + ')</span>');
    $('#modalPilihPasienSehat').modal('hide');
}
</script>
@endsection

