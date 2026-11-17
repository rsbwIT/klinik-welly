@extends('layout.layout')
@section('title', 'Pelimpahan Wewenang Medis')
@section('hide_content_header', 'true')

@section('konten')
<div class="container-fluid px-2 pt-2 pb-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-2 shadow-2xs" role="alert" style="font-size: 12px;">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close p-2" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- FORM INPUT SURAT PELIMPAHAN WEWENANG -->
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 6px;">
        <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="font-monospace fw-bold text-warning text-dark" style="font-size: 13px;">
                <i class="fas fa-file-signature text-warning mr-1"></i> :: [ SURAT PELIMPAHAN WEWENANG DELEGATIF / MANDAT MEDIS KEPADA PERAWAT ] ::
            </span>
            <small class="text-muted">Standar Regulasi Praktik Keperawatan Mandiri</small>
        </div>
        <div class="card-body p-3 bg-white">
            <form action="{{ route('surat.pelimpahan.store') }}" method="POST">
                @csrf
                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <label class="small fw-semibold text-secondary mb-1">Nomor Surat Pelimpahan :</label>
                        <input type="text" name="no_surat" value="PW-{{ date('Ymd') }}-{{ rand(100, 999) }}" class="form-control form-control-sm font-monospace fw-bold text-warning text-dark">
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-semibold text-secondary mb-1">Jenis Pelimpahan :</label>
                        <select name="jenis_pelimpahan" class="form-select form-select-sm fw-bold">
                            <option value="Delegatif">Pelimpahan Wewenang Delegatif</option>
                            <option value="Mandat">Pelimpahan Wewenang Mandat</option>
                            <option value="Delegatif/Mandat" selected>Delegatif / Mandat Medis</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-semibold text-secondary mb-1">Tanggal Surat :</label>
                        <input type="date" name="tgl_surat" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" required>
                    </div>
                </div>

                <!-- DOKTER PEMBERI WEWENANG -->
                <div class="p-2 mb-2 rounded bg-light border">
                    <span class="small font-weight-bold text-dark text-uppercase d-block mb-2">
                        <i class="fas fa-user-md text-primary mr-1"></i> Dokter Pemberi Wewenang
                    </span>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="small text-secondary mb-0">Nama Dokter :</label>
                            <input type="text" name="nama_dokter" id="pelimpahan_nama_dokter" class="form-control form-control-sm fw-bold" placeholder="dr. ...." required>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-secondary mb-0">Nomor SIP Dokter :</label>
                            <input type="text" name="sip_dokter" id="pelimpahan_sip_dokter" class="form-control form-control-sm font-monospace" placeholder="SIP: 503/....">
                        </div>
                        <div class="col-md-4">
                            <label class="small text-secondary mb-0">Alamat Tempat Praktik Dokter :</label>
                            <input type="text" name="alamat_praktik_dokter" class="form-control form-control-sm" placeholder="Alamat Tempat Praktik / RS">
                        </div>
                    </div>
                </div>

                <!-- PERAWAT PENERIMA WEWENANG -->
                <div class="p-2 mb-2 rounded bg-light border">
                    <span class="small font-weight-bold text-dark text-uppercase d-block mb-2">
                        <i class="fas fa-user-nurse text-success mr-1"></i> Perawat Penerima Wewenang
                    </span>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="small text-secondary mb-0">Nama Perawat :</label>
                            <input type="text" name="nama_perawat" value="Welly Prestikasari, S.Kep., Ners., M.Kep." class="form-control form-control-sm fw-bold bg-white" required>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-secondary mb-0">Nomor STR Perawat :</label>
                            <input type="text" name="str_perawat" value="180152120-3129482" class="form-control form-control-sm font-monospace">
                        </div>
                        <div class="col-md-4">
                            <label class="small text-secondary mb-0">Nomor SIPP Perawat :</label>
                            <input type="text" name="sipp_perawat" value="503/36/SIPP/IV.14/VIII/2021" class="form-control form-control-sm font-monospace">
                        </div>
                    </div>
                </div>

                <!-- TINDAKAN MEDIS YANG DILIMPAHKAN -->
                <div class="p-2 mb-3 rounded bg-light border">
                    <span class="small font-weight-bold text-dark text-uppercase d-block mb-2">
                        <i class="fas fa-syringe text-danger mr-1"></i> Tindakan Medis Yang Dilimpahkan:
                    </span>
                    <div class="mb-2">
                        <label class="small text-secondary mb-0">Tindakan Medis 1 :</label>
                        <input type="text" name="tindakan_1" value="Pemberian terapi obat antibiotik dan injeksi analgesik sesuai advis dokter" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="small text-secondary mb-0">Tindakan Medis 2 :</label>
                        <input type="text" name="tindakan_2" value="Tindakan debridement luka nekrotik dan pemasangan balutan modern dressing" class="form-control form-control-sm">
                    </div>
                    <div>
                        <label class="small text-secondary mb-0">Tindakan Medis 3 :</label>
                        <input type="text" name="tindakan_3" value="Pemasangan infus, kateter urine, serta penjahitan luka (hecting) bedah minor" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-sm btn-warning px-4 fw-bold shadow-sm text-dark">
                        <i class="fas fa-save mr-1"></i> Simpan Surat Pelimpahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL DAFTAR PELIMPAHAN WEWENANG -->
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 6px;">
        <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="font-weight-bold text-dark" style="font-size: 13px;">
                <i class="fas fa-history text-warning mr-1"></i> ARSIP SURAT PELIMPAHAN WEWENANG
            </span>
            <form method="GET" class="d-flex align-items-center">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm mr-1" placeholder="Cari Dokter..." style="width: 220px;">
                <button type="submit" class="btn btn-xs btn-primary px-2"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0 text-nowrap" style="font-size: 11.5px;">
                    <thead class="bg-light">
                        <tr>
                            <th width="140px">Nomor Surat</th>
                            <th>Tanggal</th>
                            <th>Dokter Pemberi</th>
                            <th>SIP Dokter</th>
                            <th>Perawat Penerima</th>
                            <th>Tindakan Dilimpahkan</th>
                            <th width="90px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                            <tr>
                                <td class="font-monospace text-primary fw-bold">{{ $row->no_surat }}</td>
                                <td>{{ date('d/m/Y', strtotime($row->tgl_surat)) }}</td>
                                <td class="fw-bold">{{ $row->nama_dokter }}</td>
                                <td class="font-monospace">{{ $row->sip_dokter ?: '-' }}</td>
                                <td>{{ $row->nama_perawat }}</td>
                                <td><span class="d-inline-block text-truncate" style="max-width: 220px;" title="{{ $row->tindakan_1 }}">{{ $row->tindakan_1 }}</span></td>
                                <td class="text-center">
                                    <a href="{{ route('surat.pelimpahan.cetak', $row->id) }}" target="_blank" class="btn btn-xs btn-outline-warning text-dark px-2 mr-1" title="Cetak Surat">
                                        <i class="fas fa-print"></i> Cetak
                                    </a>
                                    <a href="{{ route('surat.pelimpahan.destroy', $row->id) }}" onclick="return confirm('Hapus surat pelimpahan wewenang ini?')" class="btn btn-xs btn-outline-danger px-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada arsip surat pelimpahan wewenang.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light py-2 px-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">Total Data: {{ $data->total() }} surat</small>
            {{ $data->links() }}
        </div>
    </div>
</div>
@endsection

