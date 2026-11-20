@extends('layout.layout')
@section('title', 'Surat Keterangan Sakit')
@section('hide_content_header', 'true')

@section('konten')
<div class="container-fluid px-2 pt-2 pb-4">
    <!-- Header Page -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 p-3 bg-white border rounded shadow-sm">
        <div class="d-flex align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.1); color: #dc2626; font-size: 20px;">
                <i class="fas fa-bed"></i>
            </div>
            <div>
                <h5 class="font-weight-bold mb-0 text-dark">Surat Keterangan Sakit (Istirahat)</h5>
                <small class="text-muted">Penerbitan surat istirahat medis untuk pasien yang membutuhkan waktu pemulihan</small>
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
        <!-- FORM INPUT SURAT SAKIT -->
        <div class="col-lg-5 mb-4">
            <div class="card card-outline card-danger shadow-sm h-100">
                <div class="card-header bg-white">
                    <h6 class="card-title font-weight-bold text-danger mb-0">
                        <i class="fas fa-edit mr-1"></i> Formulir Surat Keterangan Sakit
                    </h6>
                </div>
                <form action="{{ route('surat.sakit.store') }}" method="POST">
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
                        @endphp

                        <!-- Tarik Pasien Cepat (Modal Pencarian Pasien) -->
                        <div class="p-2 mb-3 bg-light rounded border d-flex justify-content-between align-items-center">
                            <div>
                                <span class="small font-weight-bold text-dark d-block">
                                    <i class="fas fa-user-check text-danger mr-1"></i> Data Pasien Terpilih
                                </span>
                                <small class="text-muted" id="label_pasien_terpilih">
                                    @if($selectedPasien)
                                        <span class="text-success font-weight-bold"><i class="fas fa-check-circle"></i> {{ $selectedPasien->nama_lengkap }} ({{ $selectedPasien->no_rkm_medis ?? '-' }})</span>
                                    @else
                                        Belum ada pasien yang ditarik
                                    @endif
                                </small>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger font-weight-bold" onclick="bukaModalCariPasienSakit()">
                                <i class="fas fa-search mr-1"></i> Cari Pasien
                            </button>
                        </div>

                        <input type="hidden" name="pasien_id" id="pasien_id" value="{{ $selectedPasien->id ?? '' }}">

                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-dark">Nomor Surat <span class="text-danger">*</span></label>
                            <input type="text" name="no_surat" class="form-control form-control-sm font-weight-bold text-danger" value="{{ $defaultNoSurat }}" required>
                        </div>

                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-dark">Nama Pasien <span class="text-danger">*</span></label>
                            <input type="text" name="nama_pasien" id="nama_pasien" class="form-control form-control-sm" required placeholder="Nama lengkap pasien" value="{{ $initNama }}">
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label class="small font-weight-bold text-dark">Umur</label>
                                    <input type="text" name="umur" id="umur" class="form-control form-control-sm" placeholder="Cth: 28 Tahun" value="{{ $initUmur }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label class="small font-weight-bold text-dark">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control form-control-sm">
                                        <option value="Laki-laki" {{ $initJk == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ $initJk == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-dark">Pekerjaan</label>
                            <input type="text" name="pekerjaan" id="pekerjaan" class="form-control form-control-sm" placeholder="Cth: Karyawan Swasta, PNS, Wiraswasta">
                        </div>

                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-dark">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control form-control-sm" rows="2" placeholder="Alamat lengkap pasien">{{ $initAlamat }}</textarea>
                        </div>

                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-dark">Diagnosa Penyakit / Keluhan</label>
                            <input type="text" name="diagnosa" class="form-control form-control-sm" placeholder="Cth: Febris / Vulnus Laceratum / ISPA">
                        </div>

                        <!-- Periode Sakit -->
                        <div class="p-2 mb-2 bg-light rounded border border-danger">
                            <label class="small font-weight-bold text-danger mb-1">
                                <i class="fas fa-calendar-alt mr-1"></i> Masa Istirahat Sakit:
                            </label>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group mb-1">
                                        <label class="small text-muted font-weight-bold">Lama (Hari)</label>
                                        <input type="number" name="lama_istirahat_hari" id="lama_hari" class="form-control form-control-sm text-center font-weight-bold" value="3" min="1" max="14" onchange="hitungTanggalSelesai()">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group mb-1">
                                        <label class="small text-muted font-weight-bold">Dari Tgl</label>
                                        <input type="date" name="tanggal_mulai" id="tgl_mulai" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" onchange="hitungTanggalSelesai()">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group mb-1">
                                        <label class="small text-muted font-weight-bold">Sampai Tgl</label>
                                        <input type="date" name="tanggal_selesai" id="tgl_selesai" class="form-control form-control-sm" value="{{ date('Y-m-d', strtotime('+2 days')) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label class="small font-weight-bold text-dark">Tanggal Terbit Surat</label>
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
                            <label class="small font-weight-bold text-dark">Nama Pemeriksa / Nakes</label>
                            <input type="text" name="nama_pemeriksa" class="form-control form-control-sm" value="Welly Prestikasari, S.Kep., Ners., M.Kep., CWCCA">
                        </div>

                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-dark">Nomor SIPP / STR</label>
                            <input type="text" name="sipp_pemeriksa" class="form-control form-control-sm" value="503/36/SIPP/IV.14/VIII/2021">
                        </div>

                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-dark">Catatan Tambahan (Opsional)</label>
                            <input type="text" name="catatan" class="form-control form-control-sm" placeholder="Cth: Kontrol kembali bila belum membaik">
                        </div>
                    </div>
                    <div class="card-footer bg-white text-right">
                        <button type="submit" class="btn btn-sm btn-danger font-weight-bold">
                            <i class="fas fa-save mr-1"></i> Terbitkan Surat Sakit
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- RIWAYAT & ARSIP SURAT SAKIT -->
        <div class="col-lg-7 mb-4">
            <div class="card card-outline card-secondary shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="card-title font-weight-bold text-dark mb-0">
                        <i class="fas fa-list-alt mr-1"></i> Arsip Surat Keterangan Sakit
                    </h6>
                    <form action="{{ route('surat.sakit.index') }}" method="GET" class="form-inline m-0">
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
                                <th>Lama Istirahat</th>
                                <th>Periode Sakit</th>
                                <th>Diagnosa</th>
                                <th width="120px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listSakit as $key => $s)
                                <tr>
                                    <td class="text-center">{{ $listSakit->firstItem() + $key }}</td>
                                    <td>
                                        <span class="font-weight-bold text-danger">{{ $s->no_surat }}</span><br>
                                        <small class="text-muted">{{ date('d/m/Y', strtotime($s->tanggal_surat)) }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $s->nama_pasien }}</strong><br>
                                        <small class="text-muted">{{ $s->umur ?: '-' }} | {{ $s->jenis_kelamin }}</small>
                                    </td>
                                    <td class="text-center font-weight-bold">
                                        <span class="badge badge-danger p-1">{{ $s->lama_istirahat_hari }} Hari</span>
                                    </td>
                                    <td class="small">
                                        {{ date('d/m/Y', strtotime($s->tanggal_mulai)) }}<br>
                                        s/d {{ date('d/m/Y', strtotime($s->tanggal_selesai)) }}
                                    </td>
                                    <td>
                                        <span class="badge badge-light border">{{ $s->diagnosa ?: '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('surat.sakit.cetak', $s->id) }}" target="_blank" class="btn btn-xs btn-primary font-weight-bold" title="Cetak Surat">
                                            <i class="fas fa-print"></i> Cetak
                                        </a>
                                        <a href="{{ route('surat.sakit.destroy', $s->id) }}" class="btn btn-xs btn-danger" onclick="return confirm('Yakin ingin menghapus arsip surat ini?')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-info-circle mr-1"></i> Belum ada data surat keterangan sakit yang diterbitkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($listSakit->hasPages())
                    <div class="card-footer bg-white py-2">
                        {{ $listSakit->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- MODAL CARI PASIEN SURAT SAKIT -->
<div class="modal fade" id="modalPilihPasienSakit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow border-0" style="border-radius: 8px;">
            <div class="modal-header bg-danger text-white py-2 px-3">
                <h6 class="modal-title font-weight-bold"><i class="fas fa-user-check mr-2"></i> Pilih Pasien Untuk Surat Sakit</h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-3">
                <input type="text" id="cariPasienSakitInput" class="form-control form-control-sm mb-3" placeholder="Ketik No. Rawat, No. RM, atau Nama Pasien...">
                <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                    <table class="table table-sm table-hover table-striped mb-0 text-nowrap" style="font-size: 11.5px;">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th>No. Rawat</th>
                                <th>No. RM</th>
                                <th>Nama Pasien</th>
                                <th>Jenis Kelamin</th>
                                <th>Umur</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyCariPasienSakit">
                            <tr><td colspan="6" class="text-center py-3 text-muted">Memuat data pasien...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function bukaModalCariPasienSakit() {
    $('#modalPilihPasienSakit').modal('show');
    loadDataPasienSakit('');
}

function loadDataPasienSakit(q) {
    $('#tbodyCariPasienSakit').html('<tr><td colspan="6" class="text-center py-3 text-muted"><i class="fas fa-spinner fa-spin mr-1"></i> Memuat...</td></tr>');
    $.getJSON("{{ route('api.pelayanan.cari-pasien') }}", { q: q }, function(data) {
        var html = '';
        $.each(data, function(i, item) {
            html += '<tr style="cursor: pointer;" onclick="pilihPasienSakit(' + JSON.stringify(item).replace(/"/g, '&quot;') + ')">' +
                '<td class="font-monospace text-primary fw-bold">' + item.no_rawat + '</td>' +
                '<td class="font-monospace text-success fw-bold">' + item.no_rkm_medis + '</td>' +
                '<td class="fw-bold">' + item.nama_lengkap + '</td>' +
                '<td>' + (item.jenis_kelamin || '-') + '</td>' +
                '<td>' + (item.umur || '-') + '</td>' +
                '<td class="text-center"><button type="button" class="btn btn-xs btn-danger px-2 font-weight-bold">Pilih</button></td>' +
            '</tr>';
        });
        $('#tbodyCariPasienSakit').html(html || '<tr><td colspan="6" class="text-center py-3 text-muted">Tidak ada data pasien ditemukan.</td></tr>');
    });
}

$('#cariPasienSakitInput').on('keyup', function() {
    loadDataPasienSakit($(this).val());
});

function pilihPasienSakit(p) {
    $('#pasien_id').val(p.id);
    $('#nama_pasien').val(p.nama_lengkap);
    $('#umur').val(p.umur && p.umur !== '-' ? p.umur : '');
    if (p.jenis_kelamin) {
        var jkVal = (p.jenis_kelamin.toLowerCase().indexOf('l') === 0) ? 'Laki-laki' : 'Perempuan';
        $('#jenis_kelamin').val(jkVal);
    }
    $('#alamat').val(p.alamat_lengkap || '');
    $('#label_pasien_terpilih').html('<span class="text-success font-weight-bold"><i class="fas fa-check-circle"></i> ' + p.nama_lengkap + ' (' + p.no_rkm_medis + ')</span>');
    $('#modalPilihPasienSakit').modal('hide');
}

function hitungTanggalSelesai() {
    var hari = parseInt(document.getElementById('lama_hari').value) || 1;
    var tglMulai = document.getElementById('tgl_mulai').value;
    if (tglMulai) {
        var d = new Date(tglMulai);
        d.setDate(d.getDate() + (hari - 1));
        var yyyy = d.getFullYear();
        var mm = String(d.getMonth() + 1).padStart(2, '0');
        var dd = String(d.getDate()).padStart(2, '0');
        document.getElementById('tgl_selesai').value = yyyy + '-' + mm + '-' + dd;
    }
}
</script>
@endsection

