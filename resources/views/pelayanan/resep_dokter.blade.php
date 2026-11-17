@extends('layout.layout')

@section('title', 'E-Resep Dokter & Terapi Obat - ' . $pasien->nama_lengkap)
@section('hide_content_header', 'true')

@section('konten')
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

    <!-- IDENTITAS PASIEN -->
    <div class="card card-modern mb-3" style="border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.04);">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px; background: rgba(16, 185, 129, 0.1); color: #10b981; font-size: 20px;">
                        <i class="fas fa-prescription"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <span class="badge badge-light border font-monospace px-2 py-1 mr-1">No. Rawat: <strong>{{ $pasien->no_rawat }}</strong></span>
                            <span class="badge badge-success px-2 py-1 font-monospace mr-1">No. RM: <strong>{{ $pasien->no_rkm_medis }}</strong></span>
                            @if ($resep)
                                <span class="badge badge-info px-2 py-1 font-monospace"><i class="fas fa-receipt mr-1"></i>No. Resep: <strong>{{ $resep->no_resep }}</strong></span>
                            @endif
                        </div>
                        <h5 class="font-weight-bold mb-0 text-dark">{{ $pasien->nama_lengkap }}</h5>
                        <small class="text-muted">
                            <i class="fas fa-clinic-medical mr-1"></i>{{ $pasien->unit_poli ?? 'Poli Umum' }} &bull;
                            <i class="fas fa-user-md mr-1"></i>{{ $pasien->dokter_dituju ?? 'Dokter Pemeriksa' }} &bull;
                            <i class="fas fa-calendar-alt mr-1"></i>{{ $pasien->tanggal_masuk ?? date('Y-m-d') }}
                        </small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 mt-2 mt-md-0 flex-wrap">
                    @if ($resep && count($detailResep) > 0)
                        <a href="{{ route('pelayanan.resep.cetakEtiket', $hashId) }}" target="_blank" class="btn btn-sm btn-outline-success font-weight-bold shadow-2xs mr-1" style="border-radius: 8px;">
                            <i class="fas fa-print mr-1"></i> Cetak Etiket Obat (Label)
                        </a>
                    @endif
                    <a href="{{ url('/pelayanan/billing/' . $hashId) }}" class="btn btn-sm btn-outline-primary font-weight-bold shadow-2xs mr-1" style="border-radius: 8px;">
                        <i class="fas fa-cash-register mr-1"></i> Kasir & Billing Pasien
                    </a>
                    <a href="{{ route('rawatjalan.index') }}" class="btn btn-sm btn-light border shadow-2xs" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left mr-1"></i> Rawat Jalan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM INPUT RESEP OBAT -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 14px;">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="fas fa-pills text-success mr-2"></i> Lembar Resep Elektronik (R/) Dokter
                    </h6>
                    <button type="button" class="btn btn-sm btn-primary font-weight-bold" id="btnTambahBarisObat" style="border-radius: 6px;">
                        <i class="fas fa-plus mr-1"></i> Tambah Item Obat
                    </button>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('pelayanan.resep.simpan', $hashId) }}" method="POST">
                        @csrf
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered align-middle" id="tableResep">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="35%">Nama Obat & Stok</th>
                                        <th width="12%">Jumlah</th>
                                        <th width="28%">Aturan Pakai / Signa</th>
                                        <th width="20%">Jenis Etiket</th>
                                        <th width="5%" class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyResep">
                                    @if (count($detailResep) > 0)
                                        @foreach ($detailResep as $row)
                                            <tr>
                                                <td>
                                                    <select name="kode_brng[]" class="form-control form-control-sm select-obat" required>
                                                        <option value="">-- Pilih Obat --</option>
                                                        @foreach ($daftarObat as $ob)
                                                            <option value="{{ $ob->kode_brng }}" data-harga="{{ $ob->harga_jual }}" {{ $row->kode_brng == $ob->kode_brng ? 'selected' : '' }}>
                                                                {{ $ob->nama_brng }} (Stok: {{ $ob->stok }} {{ $ob->kode_sat }}) - Rp {{ number_format($ob->harga_jual, 0, ',', '.') }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" step="1" min="1" name="jml[]" value="{{ $row->jml }}" class="form-control form-control-sm text-center font-weight-bold" required>
                                                </td>
                                                <td>
                                                    <input type="text" name="aturan_pakai[]" value="{{ $row->aturan_pakai }}" placeholder="Cth: 3 x 1 tablet sesudah makan" class="form-control form-control-sm" required>
                                                </td>
                                                <td>
                                                    <select name="jenis_etiket[]" class="form-control form-control-sm">
                                                        <option value="Putih (Obat Dalam)" {{ $row->jenis_etiket == 'Putih (Obat Dalam)' ? 'selected' : '' }}>Putih (Obat Dalam/Minum)</option>
                                                        <option value="Biru (Obat Luar)" {{ $row->jenis_etiket == 'Biru (Obat Luar)' ? 'selected' : '' }}>Biru (Obat Luar/Salep/Tetes)</option>
                                                    </select>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-baris"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>
                                                <select name="kode_brng[]" class="form-control form-control-sm select-obat" required>
                                                    <option value="">-- Pilih Obat --</option>
                                                    @foreach ($daftarObat as $ob)
                                                        <option value="{{ $ob->kode_brng }}" data-harga="{{ $ob->harga_jual }}">
                                                            {{ $ob->nama_brng }} (Stok: {{ $ob->stok }} {{ $ob->kode_sat }}) - Rp {{ number_format($ob->harga_jual, 0, ',', '.') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" step="1" min="1" name="jml[]" value="10" class="form-control form-control-sm text-center font-weight-bold" required>
                                            </td>
                                            <td>
                                                <input type="text" name="aturan_pakai[]" value="3 x 1 Tablet Sesudah Makan" placeholder="Cth: 3 x 1 tablet sesudah makan" class="form-control form-control-sm" required>
                                            </td>
                                            <td>
                                                <select name="jenis_etiket[]" class="form-control form-control-sm">
                                                    <option value="Putih (Obat Dalam)">Putih (Obat Dalam/Minum)</option>
                                                    <option value="Biru (Obat Luar)">Biru (Obat Luar/Salep/Tetes)</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-baris"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <span class="small text-muted">
                                <i class="fas fa-info-circle text-info mr-1"></i> Resep yang disimpan otomatis terhubung ke sistem kasir billing dan bagian farmasi.
                            </span>
                            <div>
                                <a href="{{ route('rawatjalan.index') }}" class="btn btn-outline-secondary px-3 mr-2">Kembali</a>
                                <button type="submit" class="btn btn-success px-4 font-weight-bold shadow-sm" style="border-radius: 8px;">
                                    <i class="fas fa-paper-plane mr-1"></i> Simpan & Kirim E-Resep
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnTambah = document.getElementById('btnTambahBarisObat');
        const tbody = document.getElementById('tbodyResep');

        btnTambah.addEventListener('click', function () {
            const firstRow = tbody.querySelector('tr');
            if (firstRow) {
                const clone = firstRow.cloneNode(true);
                clone.querySelectorAll('input').forEach(i => i.value = '');
                clone.querySelector('input[type="number"]').value = '10';
                clone.querySelector('input[name="aturan_pakai[]"]').value = '3 x 1 Tablet Sesudah Makan';
                clone.querySelector('.btn-hapus-baris').addEventListener('click', function () {
                    if (tbody.querySelectorAll('tr').length > 1) clone.remove();
                });
                tbody.appendChild(clone);
            }
        });

        tbody.addEventListener('click', function (e) {
            if (e.target.closest('.btn-hapus-baris')) {
                if (tbody.querySelectorAll('tr').length > 1) {
                    e.target.closest('tr').remove();
                } else {
                    alert('Minimal 1 item obat dalam resep!');
                }
            }
        });
    });
</script>
@endsection
