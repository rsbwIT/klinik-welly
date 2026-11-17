@extends('layout.layout')
@section('title', 'Laporan Rekap Kasir & Pendapatan')

@section('konten')
<div class="container-fluid pt-2">
    <!-- Header Halaman -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div>
            <h4 class="font-weight-bold mb-1 text-dark">
                <i class="fas fa-file-invoice-dollar text-success mr-2"></i> Laporan Rekap Kasir & Pendapatan
            </h4>
            <p class="text-muted small mb-0">Rekapitulasi transaksi pembayaran pasien rawat jalan & pembukuan kasir klinik</p>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="{{ route('laporan.kasir.cetak', ['from' => $from, 'to' => $to, 'status_bayar' => $statusBayar, 'cara_bayar' => $caraBayar]) }}" target="_blank" class="btn btn-primary font-weight-bold shadow-sm" style="border-radius: 8px;">
                <i class="fas fa-print mr-1"></i> Cetak Rekap Kasir (PDF)
            </a>
        </div>
    </div>

    <!-- Filter Periode & Cara Bayar -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body p-3">
            <form action="{{ route('laporan.kasir.index') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3 col-sm-6 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Dari Tanggal :</label>
                    <input type="date" name="from" value="{{ $from }}" class="form-control form-control-sm font-monospace font-weight-bold">
                </div>
                <div class="col-md-3 col-sm-6 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Sampai Tanggal :</label>
                    <input type="date" name="to" value="{{ $to }}" class="form-control form-control-sm font-monospace font-weight-bold">
                </div>
                <div class="col-md-2 col-sm-6 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Status Bayar :</label>
                    <select name="status_bayar" class="form-control form-control-sm">
                        <option value="Semua" {{ $statusBayar == 'Semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="Sudah" {{ $statusBayar == 'Sudah' ? 'selected' : '' }}>Lunas / Sudah</option>
                        <option value="Belum" {{ $statusBayar == 'Belum' ? 'selected' : '' }}>Belum Bayar</option>
                    </select>
                </div>
                <div class="col-md-2 col-sm-6 mb-2">
                    <label class="small font-weight-bold text-muted mb-1">Cara Bayar :</label>
                    <select name="cara_bayar" class="form-control form-control-sm">
                        <option value="Semua" {{ $caraBayar == 'Semua' ? 'selected' : '' }}>Semua Cara Bayar</option>
                        @foreach ($penjabList as $pj)
                            <option value="{{ $pj->png_jawab }}" {{ $caraBayar == $pj->png_jawab ? 'selected' : '' }}>{{ $pj->png_jawab }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 col-sm-12 mb-2">
                    <button type="submit" class="btn btn-success btn-sm btn-block font-weight-bold">
                        <i class="fas fa-filter mr-1"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Ringkasan Finansial Widget -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: linear-gradient(135deg, #059669, #10b981); color: #fff;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-uppercase font-weight-bold opacity-75">Total Pendapatan (Lunas)</div>
                            <div class="h4 font-weight-bold font-monospace mb-0 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                            <div class="small mt-1 opacity-75">{{ $transaksiLunas }} Nota Lunas</div>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(255,255,255,0.2);">
                            <i class="fas fa-wallet fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: #ffffff;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-uppercase font-weight-bold text-muted">Jasa Tindakan & Dokter</div>
                            <div class="h5 font-weight-bold text-primary font-monospace mb-0 mt-1">Rp {{ number_format($totalTindakan, 0, ',', '.') }}</div>
                            <div class="small text-muted mt-1">Biaya Regis: Rp {{ number_format($totalRegistrasi, 0, ',', '.') }}</div>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-light text-primary" style="width: 48px; height: 48px;">
                            <i class="fas fa-user-md fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: #ffffff;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-uppercase font-weight-bold text-muted">Pendapatan Obat / Farmasi</div>
                            <div class="h5 font-weight-bold text-success font-monospace mb-0 mt-1">Rp {{ number_format($totalObat, 0, ',', '.') }}</div>
                            <div class="small text-muted mt-1">Dari Resep Ralan Pasien</div>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-light text-success" style="width: 48px; height: 48px;">
                            <i class="fas fa-pills fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: #ffffff;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-uppercase font-weight-bold text-muted">Pendapatan Laboratorium</div>
                            <div class="h5 font-weight-bold text-info font-monospace mb-0 mt-1">Rp {{ number_format($totalLab, 0, ',', '.') }}</div>
                            <div class="small text-muted mt-1">Total {{ $totalTransaksi }} Transaksi Masuk</div>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-light text-info" style="width: 48px; height: 48px;">
                            <i class="fas fa-flask fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data Rekap Billing Kasir -->
    <div class="card border-0 shadow-sm" style="border-radius: 14px;">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold text-dark">
                <i class="fas fa-list text-primary mr-1"></i> Rincian Transaksi Kasir ({{ count($listBilling) }} Data)
            </h6>
            <span class="badge badge-light border font-monospace">Periode: {{ $from }} s/d {{ $to }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0" style="font-size: 12.5px;">
                    <thead class="thead-light">
                        <tr>
                            <th width="40" class="text-center">#</th>
                            <th>No. Nota</th>
                            <th>Tgl & Jam</th>
                            <th>No. Rawat & RM</th>
                            <th>Nama Pasien</th>
                            <th>Cara Bayar</th>
                            <th class="text-right">Registrasi</th>
                            <th class="text-right">Tindakan</th>
                            <th class="text-right">Obat</th>
                            <th class="text-right">Lab</th>
                            <th class="text-right">Total Bayar</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($listBilling as $b)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="font-monospace font-weight-bold text-danger">{{ $b->no_nota ?? '-' }}</td>
                                <td class="font-monospace text-muted">{{ $b->tgl_byr }} {{ $b->jam_byr ? substr($b->jam_byr, 0, 5) : '' }}</td>
                                <td>
                                    <div class="font-monospace text-primary font-weight-bold">{{ $b->no_rawat }}</div>
                                    <small class="text-muted font-monospace">RM: {{ $b->no_rkm_medis }}</small>
                                </td>
                                <td class="font-weight-bold text-dark">{{ $b->nm_pasien }}</td>
                                <td>
                                    <span class="badge {{ strtoupper($b->jenis_bayar ?? 'UMUM') == 'BPJS' ? 'badge-success' : 'badge-primary' }}">
                                        {{ strtoupper($b->jenis_bayar ?? 'UMUM') }}
                                    </span>
                                </td>
                                <td class="text-right font-monospace">{{ number_format($b->biaya_registrasi, 0, ',', '.') }}</td>
                                <td class="text-right font-monospace">{{ number_format($b->biaya_tindakan, 0, ',', '.') }}</td>
                                <td class="text-right font-monospace">{{ number_format($b->biaya_obat, 0, ',', '.') }}</td>
                                <td class="text-right font-monospace">{{ number_format($b->biaya_lab, 0, ',', '.') }}</td>
                                <td class="text-right font-monospace font-weight-bold text-success" style="font-size: 13px;">
                                    Rp {{ number_format($b->total_bayar, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $b->status_bayar == 'Sudah' ? 'badge-success' : 'badge-warning text-dark' }} px-2 py-1">
                                        {{ $b->status_bayar == 'Sudah' ? 'LUNAS' : 'BELUM' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ url('/pelayanan/billing/' . \App\Helpers\HashidsHelper::encode($b->id ?? 1)) }}" class="btn btn-xs btn-outline-info" title="Buka Kasir">
                                        <i class="fas fa-search"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Tidak ada data transaksi billing pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if (count($listBilling) > 0)
                        <tfoot class="bg-light font-weight-bold">
                            <tr>
                                <td colspan="6" class="text-right text-uppercase">Total Rekap Periode Ini :</td>
                                <td class="text-right font-monospace">{{ number_format($totalRegistrasi, 0, ',', '.') }}</td>
                                <td class="text-right font-monospace">{{ number_format($totalTindakan, 0, ',', '.') }}</td>
                                <td class="text-right font-monospace">{{ number_format($totalObat, 0, ',', '.') }}</td>
                                <td class="text-right font-monospace">{{ number_format($totalLab, 0, ',', '.') }}</td>
                                <td class="text-right font-monospace text-success font-weight-bold" style="font-size: 14px;">
                                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
