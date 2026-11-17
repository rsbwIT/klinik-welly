@extends('layout.layout')

@section('title', 'Billing & Pembayaran Pasien - ' . $pasien->nama_lengkap)
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
</style>

<div class="container-fluid pt-2">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm py-2 mb-3" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm py-2 mb-3" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
        </div>
    @endif

    <!-- IDENTITAS PASIEN (GAYA BUNDLING2 / SIMRS KHANZA) -->
    <div class="card card-modern">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.1); color: #10b981; font-size: 20px;">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <span class="badge badge-light border font-monospace px-2 py-1 mr-1">No. Rawat: <strong>{{ $pasien->no_rawat }}</strong></span>
                            <span class="badge badge-success px-2 py-1 font-monospace mr-1">No. RM: <strong>{{ $pasien->no_rkm_medis }}</strong></span>
                            @if (!empty($billing->no_nota) || !empty($pasien->no_nota))
                                <span class="badge badge-info px-2 py-1 font-monospace"><i class="fas fa-file-invoice mr-1"></i>No. Nota: <strong>{{ $billing->no_nota ?? $pasien->no_nota }}</strong></span>
                            @endif
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
                <div class="d-flex align-items-center gap-2 mt-2 mt-md-0 flex-wrap">
                    @if (($pasien->status_bayar ?? 'Belum') == 'Sudah' || !empty($billing->no_nota))
                        <a href="{{ route('pelayanan.billing.cetak', $hashId) }}" target="_blank" class="btn btn-sm btn-outline-success shadow-2xs mr-1 font-weight-bold" style="border-radius: 8px;">
                            <i class="fas fa-print mr-1"></i> Cetak Nota / Kwitansi
                        </a>
                        @if (strtolower(Auth::user()->role ?? '') === 'admin')
                            <button type="button" class="btn btn-sm btn-outline-danger shadow-2xs mr-1 font-weight-bold" data-toggle="modal" data-target="#modalHapusNota" style="border-radius: 8px;">
                                <i class="fas fa-trash-alt mr-1"></i> Hapus Nota Salah (Admin)
                            </button>
                        @endif
                    @endif
                    <a href="{{ route('rawatjalan.index') }}" class="btn btn-sm btn-light border shadow-2xs mr-2" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left mr-1"></i> Data Rawat Jalan
                    </a>
                    <span class="badge {{ ($pasien->status_bayar ?? 'Belum') == 'Sudah' ? 'badge-success' : 'badge-warning text-dark' }} px-3 py-2" style="font-size: 13px;">
                        {{ ($pasien->status_bayar ?? 'Belum') == 'Sudah' ? 'LUNAS / SUDAH BAYAR' : 'BELUM BAYAR' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informasi Rincian Pasien -->
        <div class="col-lg-4 mb-3">
            <div class="card card-modern">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-id-card text-primary mr-2"></i> Rincian Registrasi</h6>
                </div>
                <div class="card-body p-3">
                    <table class="table table-sm table-borderless mb-0" style="font-size: 13px;">
                        <tr>
                            <td class="text-secondary" width="110px">No. Rawat</td>
                            <td class="font-weight-bold font-monospace text-primary">: {{ $pasien->no_rawat }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">No. RM</td>
                            <td class="font-weight-bold font-monospace text-success">: {{ $pasien->no_rkm_medis }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Nama Pasien</td>
                            <td class="font-weight-bold text-dark">: {{ $pasien->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Jenis Kelamin</td>
                            <td>: {{ $pasien->jenis_kelamin ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Tgl. Periksa</td>
                            <td>: {{ $pasien->tanggal_masuk ?? date('Y-m-d') }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Poliklinik</td>
                            <td>: <span class="badge badge-light border">{{ $pasien->nm_poli ?? 'Poliklinik Umum' }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Dokter DPJP</td>
                            <td class="font-weight-bold">: {{ $pasien->dokter_dpjp ?? 'dr. Welly' }}</td>
                        </tr>
                        <tr>
                            <td class="text-secondary">Penjamin</td>
                            <td>: 
                                <span class="badge {{ strtoupper($pasien->jenis_bayar ?? 'UMUM') == 'BPJS' ? 'badge-success' : 'badge-primary' }}">
                                    {{ strtoupper($pasien->jenis_bayar ?? 'UMUM') }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Form Billing Kasir -->
        <div class="col-lg-8">
            <div class="card card-modern">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-calculator text-success mr-2"></i> Rincian Tagihan & Pembayaran</h6>
                    <span class="small text-muted font-monospace">Tgl Transaksi: {{ date('d-m-Y') }}</span>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('pelayanan.billing.simpan', $hashId) }}" method="POST">
                        @csrf
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="40px" class="text-center">#</th>
                                        <th>Uraian Komponen Biaya</th>
                                        <th width="220px" class="text-right">Jumlah (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center font-monospace">1</td>
                                        <td>
                                            <div class="font-weight-bold text-dark">Biaya Registrasi / Pendaftaran</div>
                                            <small class="text-muted">Biaya administrasi loket rawat jalan</small>
                                        </td>
                                        <td>
                                            <input type="number" step="1000" name="biaya_registrasi" id="biaya_registrasi" value="{{ $biayaReg }}" class="form-control text-right font-weight-bold font-monospace hitung-total">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center font-monospace">2</td>
                                        <td>
                                            <div class="font-weight-bold text-dark">Biaya Tindakan Medis / Dokter</div>
                                            <small class="text-muted">Pemeriksaan umum, konsultasi, & asuhan tindakan</small>
                                        </td>
                                        <td>
                                            <input type="number" step="1000" name="biaya_tindakan" id="biaya_tindakan" value="{{ $biayaTindakan }}" class="form-control text-right font-weight-bold font-monospace hitung-total">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center font-monospace">3</td>
                                        <td>
                                            <div class="font-weight-bold text-dark">Biaya Obat & Farmasi (Resep)</div>
                                            <small class="text-muted">Diambil dari modul resep & obat farmasi</small>
                                        </td>
                                        <td>
                                            <input type="number" step="1000" name="biaya_obat" id="biaya_obat" value="{{ $totalObat }}" class="form-control text-right font-weight-bold font-monospace hitung-total">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center font-monospace">4</td>
                                        <td>
                                            <div class="font-weight-bold text-dark">Pemeriksaan Laboratorium</div>
                                            <small class="text-muted">Total biaya pemeriksaan sampel lab</small>
                                        </td>
                                        <td>
                                            <input type="number" step="1000" name="biaya_lab" id="biaya_lab" value="{{ $totalLab }}" class="form-control text-right font-weight-bold font-monospace hitung-total">
                                        </td>
                                    </tr>
                                    <tr class="bg-light">
                                        <td colspan="2" class="text-right font-weight-bold" style="font-size: 15px;">TOTAL TAGIHAN :</td>
                                        <td class="text-right">
                                            <div class="font-weight-bold text-success font-monospace" style="font-size: 1.25rem;" id="label_total">Rp {{ number_format($totalBayar, 0, ',', '.') }}</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="small font-weight-bold text-secondary mb-1">Status Pembayaran :</label>
                                <div>
                                    @if (($pasien->status_bayar ?? 'Belum') == 'Sudah' || !empty($billing->no_nota))
                                        <span class="badge badge-success px-3 py-2 font-weight-bold" style="font-size: 13px;">
                                            <i class="fas fa-check-circle mr-1"></i> LUNAS / SUDAH BAYAR
                                        </span>
                                        @if (!empty($billing->no_nota))
                                            <div class="small text-muted mt-1 font-monospace">No. Nota: <strong>{{ $billing->no_nota }}</strong></div>
                                        @endif
                                    @else
                                        <span class="badge badge-warning text-dark px-3 py-2 font-weight-bold" style="font-size: 13px;">
                                            <i class="fas fa-clock mr-1"></i> BELUM DIBAYAR
                                        </span>
                                        <small class="text-muted d-block mt-1">Klik tombol <strong>"Bayar Sekarang"</strong> di bawah untuk memproses dan mencetak nota.</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="small font-weight-bold text-secondary mb-1">Petugas Kasir :</label>
                                <input type="text" class="form-control bg-light font-weight-bold" value="{{ $billing->petugas ?? Auth::user()->name ?? 'Petugas Kasir' }}" readonly>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3 flex-wrap gap-2">
                            <div>
                                @if ((($pasien->status_bayar ?? 'Belum') == 'Sudah' || !empty($billing->no_nota)) && strtolower(Auth::user()->role ?? '') === 'admin')
                                    <button type="button" class="btn btn-outline-danger btn-sm font-weight-bold" data-toggle="modal" data-target="#modalHapusNota">
                                        <i class="fas fa-trash-alt mr-1"></i> Hapus Nota Salah (Admin)
                                    </button>
                                @endif
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('rawatjalan.index') }}" class="btn btn-outline-secondary px-3 mr-2">Kembali</a>
                                <button type="submit" class="btn btn-success px-4 font-weight-bold shadow-sm" style="border-radius: 8px;">
                                    <i class="fas fa-receipt mr-1"></i> {{ ($pasien->status_bayar ?? 'Belum') == 'Sudah' ? 'Update & Cetak Ulang Nota' : 'Bayar Sekarang & Buat Nota' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if (strtolower(Auth::user()->role ?? '') === 'admin')
<!-- MODAL HAPUS / BATALKAN NOTA SALAH (KHUSUS ADMIN) -->
<div class="modal fade" id="modalHapusNota" tabindex="-1" role="dialog" aria-labelledby="modalHapusNotaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title font-weight-bold" id="modalHapusNotaLabel">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Hapus / Batalkan Nota Salah
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('pelayanan.billing.hapusNota', $hashId) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-warning py-2 mb-3">
                        <i class="fas fa-shield-alt mr-1"></i> <strong>Otoritas Administrator</strong>: Tindakan ini hanya dapat dilakukan oleh Admin.
                    </div>
                    <p class="text-secondary mb-2">Anda akan membatalkan dan menghapus nota pembayaran untuk pasien:</p>
                    <div class="p-3 bg-light rounded border mb-3">
                        <div><strong>Pasien:</strong> {{ $pasien->nama_lengkap }} ({{ $pasien->no_rkm_medis }})</div>
                        <div><strong>No. Rawat:</strong> {{ $pasien->no_rawat }}</div>
                        <div><strong>No. Nota:</strong> <span class="badge badge-danger">{{ $billing->no_nota ?? $pasien->no_nota ?? '-' }}</span></div>
                        <div><strong>Total Tagihan:</strong> Rp {{ number_format($billing->total_bayar ?? $totalBayar, 0, ',', '.') }}</div>
                    </div>
                    <p class="text-danger small font-weight-bold mb-0">
                        * Setelah dihapus, status pembayaran pasien akan dikembalikan menjadi <u>BELUM BAYAR</u> sehingga kasir dapat menginput ulang biaya yang benar.
                    </p>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4 font-weight-bold">
                        <i class="fas fa-trash-alt mr-1"></i> Ya, Hapus Nota Ini
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputs = document.querySelectorAll('.hitung-total');
        const labelTotal = document.getElementById('label_total');

        function updateTotal() {
            let total = 0;
            inputs.forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            labelTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        inputs.forEach(input => {
            input.addEventListener('input', updateTotal);
        });
    });
</script>
@endsection
