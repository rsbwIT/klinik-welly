@extends('layout.layout')

@section('title', 'Diagnosa Penyakit (ICD-10) - ' . $pasien->nama_lengkap)
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

/* ================= FILTER / SUMMARY ================= */
.summary-box {
    background: #ffffff;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
    border: 1px solid #eef1f4;
    transition: all .2s ease;
}
.summary-box:hover {
    transform: translateY(-2px);
}
.summary-box h6 {
    font-size: 11px;
    color: #6c757d;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: .5px;
    margin-bottom: 6px;
}
.summary-box h3 {
    font-weight: 700;
    margin: 0;
    color: #2c3e50;
}
.summary-utama { border-left: 4px solid #c92a2a; }
.summary-sekunder { border-left: 4px solid #364fc7; }
.summary-total { border-left: 4px solid #099268; }

/* ================= TABLE ================= */
.table-modern {
    font-size: 13.5px;
}
.table-modern thead {
    background: #f8f9fa;
    font-size: 11.5px;
    text-transform: uppercase;
    letter-spacing: .4px;
}
.table-modern thead th {
    border-bottom: 1px solid #e9ecef !important;
    font-weight: 600;
    color: #495057;
}
.table-modern tbody tr {
    border-bottom: 1px solid #f1f3f5;
    transition: all .15s ease;
}
.table-modern tbody tr:hover {
    background: #f4f6fb;
}
.table-modern td {
    vertical-align: middle;
}

/* ================= BADGES ================= */
.badge-utama {
    background: #fff5f5;
    color: #c92a2a;
    border: 1px solid #ffc9c9;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.badge-sekunder {
    background: #edf2ff;
    color: #364fc7;
    border: 1px solid #bac8ff;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.badge-komplikasi {
    background: #fff9db;
    color: #f08c00;
    border: 1px solid #ffec99;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.badge-ralan {
    background: #e6fcf5;
    color: #099268;
    border: 1px solid #b2f2bb;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 600;
}
</style>

<div class="container-fluid pt-2">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm py-2 mb-3" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
        </div>
    @endif

    <!-- IDENTITAS PASIEN (GAYA BUNDLING2 / SIMRS KHANZA) -->
    <div class="card card-modern">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px; background: rgba(2, 132, 199, 0.1); color: #0284c7; font-size: 20px;">
                        <i class="fas fa-user-injured"></i>
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
                    <a href="{{ route('rawatjalan.index') }}" class="btn btn-sm btn-light border shadow-2xs mr-2" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left mr-1"></i> Data Rawat Jalan
                    </a>
                    <span class="badge {{ strtoupper($pasien->jenis_bayar ?? 'UMUM') == 'BPJS' ? 'badge-success' : 'badge-primary' }} px-3 py-2" style="font-size: 13px;">
                        {{ strtoupper($pasien->jenis_bayar ?? 'UMUM') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- SUMMARY KARTU DIAGNOSA (GAYA BUNDLING2) -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="summary-box summary-utama">
                <h6>Diagnosa Utama</h6>
                <h3>{{ $diagnosas->where('prioritas', 'Utama')->count() }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-box summary-sekunder">
                <h6>Diagnosa Sekunder</h6>
                <h3>{{ $diagnosas->where('prioritas', 'Sekunder')->count() }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-box summary-total">
                <h6>Total Diagnosa Tercatat</h6>
                <h3>{{ $diagnosas->count() }}</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- FORM INPUT DIAGNOSA -->
        <div class="col-lg-5 mb-3">
            <div class="card card-modern">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-plus-circle text-primary mr-1"></i> Input Diagnosa ICD-10</span>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalPilihIcd" style="border-radius: 8px;">
                        <i class="fas fa-search mr-1"></i> Cari Master ICD-10
                    </button>
                </div>
                <div class="card-body p-3">
                    <form action="{{ route('pelayanan.diagnosa.simpan', $hashId) }}" method="POST" id="formDiagnosa">
                        @csrf
                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-secondary mb-1">Kode ICD-10 :</label>
                            <div class="input-group">
                                <input type="text" name="kd_penyakit" id="input_kd_penyakit" class="form-control form-control-sm font-monospace font-weight-bold bg-light" placeholder="Pilih dari tombol cari..." required readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalPilihIcd">
                                        <i class="fas fa-book-medical mr-1"></i> Pilih
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="small font-weight-bold text-secondary mb-1">Nama Diagnosa / Deskripsi Penyakit :</label>
                            <textarea name="nm_penyakit" id="input_nm_penyakit" rows="2" class="form-control form-control-sm" placeholder="Nama penyakit lengkap..." required></textarea>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="small font-weight-bold text-secondary mb-1">Prioritas :</label>
                                <select name="prioritas" class="form-control form-control-sm">
                                    <option value="Utama">Utama (Primer)</option>
                                    <option value="Sekunder" selected>Sekunder</option>
                                    <option value="Komplikasi">Komplikasi</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="small font-weight-bold text-secondary mb-1">Status Rawat :</label>
                                <select name="status" class="form-control form-control-sm">
                                    <option value="Ralan" selected>Rawat Jalan</option>
                                    <option value="Ranap">Rawat Inap</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block font-weight-bold shadow-sm" style="border-radius: 8px;">
                            <i class="fas fa-save mr-1"></i> Simpan ke Diagnosa Pasien
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- TABEL DAFTAR DIAGNOSA TERCATAT -->
        <div class="col-lg-7">
            <div class="card card-modern">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-list-check text-success mr-1"></i> Diagnosa Pasien Saat Ini</span>
                    <span class="badge bg-light text-dark px-2 py-1 font-monospace border">{{ $diagnosas->count() }} Diagnosa</span>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table table-modern table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="40" class="text-center">#</th>
                                <th width="90">Kode</th>
                                <th>Nama Penyakit</th>
                                <th width="100" class="text-center">Prioritas</th>
                                <th width="90" class="text-center">Status</th>
                                <th width="50" class="text-center"><i class="fas fa-cog"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($diagnosas as $item)
                                <tr>
                                    <td class="text-center text-muted font-monospace">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge badge-light border font-monospace text-primary font-weight-bold px-2 py-1">
                                            {{ $item->kd_penyakit }}
                                        </span>
                                    </td>
                                    <td class="font-weight-bold text-dark">{{ $item->nm_penyakit }}</td>
                                    <td class="text-center">
                                        @if($item->prioritas == 'Utama')
                                            <span class="badge-utama">Utama</span>
                                        @elseif($item->prioritas == 'Sekunder')
                                            <span class="badge-sekunder">Sekunder</span>
                                        @else
                                            <span class="badge-komplikasi">{{ $item->prioritas }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-ralan">{{ $item->status ?? 'Ralan' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('pelayanan.diagnosa.hapus', $item->id) }}" class="btn btn-sm btn-outline-danger py-0 px-2" onclick="return confirm('Hapus diagnosa {{ $item->kd_penyakit }} ini?')" title="Hapus Diagnosa">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-notes-medical fa-2x mb-2 d-block opacity-50"></i>
                                        Belum ada diagnosa ICD-10 yang diinput untuk pasien ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL POPUP MASTER ICD-10 (GAYA BUNDLING2 DENGAN LIVE SEARCH) -->
<div class="modal fade" id="modalPilihIcd" tabindex="-1" role="dialog" aria-labelledby="modalPilihIcdLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content card-modern border-0">
            <div class="modal-header bg-primary text-white py-3">
                <h6 class="modal-title font-weight-bold" id="modalPilihIcdLabel">
                    <i class="fas fa-book-medical mr-2"></i> Kamus Diagnosa Penyakit (ICD-10)
                </h6>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-3">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                    </div>
                    <input type="text" id="cari_icd_modal" class="form-control" placeholder="Ketik kode ICD atau nama penyakit (contoh: ISPA, luka, diabetes, diare, J06)..." autofocus>
                </div>

                <div class="table-responsive" style="max-height: 360px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 8px;">
                    <table class="table table-hover table-sm align-middle mb-0" id="tableMasterIcd">
                        <thead class="bg-light sticky-top">
                            <tr>
                                <th width="100">Kode ICD-10</th>
                                <th>Nama Penyakit / Deskripsi</th>
                                <th width="80" class="text-center">Pilih</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyIcdModal">
                            @foreach ($masterIcd as $icd)
                                <tr>
                                    <td class="font-monospace font-weight-bold text-primary">{{ $icd->kd_penyakit }}</td>
                                    <td>{{ $icd->nm_penyakit }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary px-3 py-1 font-weight-bold" onclick="pilihIcd('{{ $icd->kd_penyakit }}', '{{ addslashes($icd->nm_penyakit) }}')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function pilihIcd(kode, nama) {
        $('#input_kd_penyakit').val(kode);
        $('#input_nm_penyakit').val(nama);
        $('#modalPilihIcd').modal('hide');
    }

    let timeoutSearch = null;
    $('#cari_icd_modal').on('input', function() {
        let keyword = $(this).val();
        clearTimeout(timeoutSearch);
        timeoutSearch = setTimeout(function() {
            cariIcdAjax(keyword);
        }, 300);
    });

    function cariIcdAjax(keyword) {
        $('#tbodyIcdModal').html('<tr><td colspan="3" class="text-center py-3 text-muted"><i class="fas fa-spinner fa-spin mr-1"></i> Mencari diagnosa...</td></tr>');
        $.ajax({
            url: "{{ route('api.cari.icd10') }}",
            type: "GET",
            data: { q: keyword },
            success: function(data) {
                let html = '';
                if (data.length > 0) {
                    data.forEach(function(item) {
                        html += `<tr>
                            <td class="font-monospace font-weight-bold text-primary">${item.kd_penyakit}</td>
                            <td>${item.nm_penyakit}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-primary px-3 py-1 font-weight-bold" onclick="pilihIcd('${item.kd_penyakit}', '${item.nm_penyakit.replace(/'/g, "\\'")}')">
                                    <i class="fas fa-check"></i>
                                </button>
                            </td>
                        </tr>`;
                    });
                } else {
                    html = `<tr>
                        <td colspan="3" class="text-center text-muted py-3">
                            Diagnosa tidak ditemukan di kamus. 
                            <button type="button" class="btn btn-xs btn-outline-secondary ml-2" onclick="gunakanInputManual('${keyword}')">Gunakan "${keyword}"</button>
                        </td>
                    </tr>`;
                }
                $('#tbodyIcdModal').html(html);
            }
        });
    }

    function gunakanInputManual(kw) {
        $('#input_kd_penyakit').val('Z99');
        $('#input_nm_penyakit').val(kw);
        $('#modalPilihIcd').modal('hide');
    }
</script>

@endsection
