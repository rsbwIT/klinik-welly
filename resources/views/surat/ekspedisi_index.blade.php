@extends('layout.layout')
@section('title', 'Buku Ekspedisi Surat')
@section('hide_content_header', 'true')

@section('konten')
<div class="container-fluid px-2 pt-2 pb-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-2 shadow-2xs" role="alert" style="font-size: 12px;">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close p-2" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- FORM INPUT EKSPEDISI -->
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 6px;">
        <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="font-monospace fw-bold text-success" style="font-size: 13px;">
                <i class="fas fa-book-reader mr-1"></i> :: [ Formulir Pencatatan Buku Ekspedisi ] ::
            </span>
            <div class="d-flex gap-2">
                <a href="{{ route('surat.ekspedisi.cetak') }}" target="_blank" class="btn btn-xs btn-outline-success">
                    <i class="fas fa-print mr-1"></i> Cetak Lembar Buku Ekspedisi
                </a>
            </div>
        </div>
        <div class="card-body p-3 bg-white">
            <form action="{{ route('surat.ekspedisi.store') }}" method="POST">
                @csrf
                <div class="row g-2 mb-2">
                    <div class="col-md-3">
                        <label class="small fw-semibold text-secondary mb-1">Hari / Tanggal :</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-semibold text-secondary mb-1">Nama Penerima :</label>
                        <input type="text" name="nama_penerima" class="form-control form-control-sm fw-bold" placeholder="Nama Petugas Penerima" required>
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-semibold text-secondary mb-1">Jabatan :</label>
                        <input type="text" name="jabatan" class="form-control form-control-sm" placeholder="Contoh: Petugas Rekam Medis / Resepsionis">
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-semibold text-secondary mb-1">Instansi / Faskes Tujuan :</label>
                        <input type="text" name="instansi" class="form-control form-control-sm fw-bold text-dark" placeholder="Contoh: RSUD Dr. A. Dadi Tjokrodipo" required>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-8">
                        <label class="small fw-semibold text-secondary mb-1">Maksud / Tujuan Ekspedisi Surat :</label>
                        <input type="text" name="maksud_tujuan" class="form-control form-control-sm" placeholder="Pengiriman Surat Rujukan Pasien Ny. Siti / Dokumen Medis" required>
                    </div>
                    <div class="col-md-4">
                        <label class="small fw-semibold text-secondary mb-1">Status / Tanda Tangan Penerima :</label>
                        <input type="text" name="tanda_tangan" value="Sudah Diterima" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-sm btn-success px-4 fw-bold shadow-sm">
                        <i class="fas fa-save mr-1"></i> Simpan Catatan Ekspedisi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL BUKU EKSPEDISI RESMI -->
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 6px;">
        <div class="card-header bg-white py-2 px-3 border-bottom d-flex justify-content-between align-items-center">
            <span class="font-weight-bold text-dark" style="font-size: 13px;">
                <i class="fas fa-list-alt text-success mr-1"></i> TABEL BUKU EKSPEDISI SURAT KELUAR
            </span>
            <form method="GET" class="d-flex align-items-center">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm mr-1" placeholder="Cari Penerima / Instansi..." style="width: 220px;">
                <button type="submit" class="btn btn-xs btn-primary px-2"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0 text-nowrap" style="font-size: 12px;">
                    <thead class="bg-light text-center">
                        <tr>
                            <th width="40px">NO</th>
                            <th width="120px">HARI / TGL</th>
                            <th>NAMA PENERIMA</th>
                            <th>JABATAN</th>
                            <th>INSTANSI</th>
                            <th>MAKSUD / TUJUAN EKSPEDISI</th>
                            <th width="140px">TANDA TANGAN</th>
                            <th width="50px">AKSI</th>
                        </tr>
                        <tr class="text-muted" style="font-size: 10.5px;">
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
                            <th>6</th>
                            <th>7</th>
                            <th>-</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $row)
                            <tr>
                                <td class="text-center font-monospace">{{ $data->firstItem() + $index }}</td>
                                <td class="text-center font-monospace">{{ date('d/m/Y', strtotime($row->tanggal)) }}</td>
                                <td class="fw-bold">{{ $row->nama_penerima }}</td>
                                <td>{{ $row->jabatan ?: '-' }}</td>
                                <td class="fw-bold text-dark">{{ $row->instansi }}</td>
                                <td>{{ $row->maksud_tujuan }}</td>
                                <td class="text-center">
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> {{ $row->tanda_tangan }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('surat.ekspedisi.destroy', $row->id) }}" onclick="return confirm('Hapus baris ekspedisi ini?')" class="btn btn-xs btn-outline-danger p-1">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada catatan buku ekspedisi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light py-2 px-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">Total Entri: {{ $data->total() }} catatan</small>
            {{ $data->links() }}
        </div>
    </div>
</div>
@endsection

