@extends('layout.layout')

@section('title', 'Tindakan Rawat Jalan - ' . $pasien->nama_lengkap)
@section('hide_content_header', 'true')

@section('konten')
<style>
/* ================= KHANZA HMS THEME TABS & FORM ================= */
.khanza-tabs-header {
    display: flex;
    flex-wrap: wrap;
    background: #f8fafc;
    border-bottom: 2px solid #0284c7;
    margin-bottom: 8px;
}
.khanza-tab-link {
    padding: 7px 15px;
    font-size: 11.5px;
    font-weight: 600;
    color: #475569;
    text-decoration: none;
    border-top: 3px solid transparent;
    transition: all 0.2s;
    cursor: pointer;
}
.khanza-tab-link:hover {
    color: #0284c7;
    background: #e2e8f0;
}
.khanza-tab-link.active {
    color: #0284c7;
    background: #ffffff;
    border-top: 3px solid #0284c7;
    border-left: 1px solid #cbd5e1;
    border-right: 1px solid #cbd5e1;
    margin-bottom: -2px;
    font-weight: 700;
}

.khanza-sub-tabs {
    display: flex;
    gap: 15px;
    border-bottom: 1px solid #cbd5e1;
    margin-bottom: 8px;
    padding-bottom: 2px;
}
.khanza-sub-tab {
    font-size: 11.5px;
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
    text-decoration: none;
    padding-bottom: 4px;
}
.khanza-sub-tab.active {
    color: #059669;
    border-bottom: 2.5px solid #059669;
    font-weight: 700;
}

/* Tabel Khanza */
.tabel-khanza th {
    background: #f8fafc;
    color: #1e293b;
    font-size: 11px;
    font-weight: 700;
    border: 1px solid #cbd5e1 !important;
    padding: 5px 6px;
    vertical-align: middle;
}
.tabel-khanza td {
    font-size: 11px;
    border: 1px solid #e2e8f0 !important;
    padding: 4px 6px;
    vertical-align: middle;
}
.tabel-khanza tbody tr:hover {
    background: #f0fdf4;
}
.tabel-khanza tbody tr.selected-row {
    background: #dcfce7;
}

/* Tombol Bawah Khanza (Simpan, Hapus, Ganti, Cetak, Semua, Keluar) */
.btn-khanza-bottom {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 12px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    transition: all 0.15s;
    color: #334155;
}
.btn-khanza-bottom:hover {
    background: #f1f5f9;
}
</style>

<div class="container-fluid pt-2">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm py-2 mb-2" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm py-2 mb-2" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 6px;">
        <div class="card-body p-2">

            <!-- TAB UTAMA ATAS PERSIS SIMRS KHANZA -->
            <div class="khanza-tabs-header">
                <a href="javascript:void(0)" class="khanza-tab-link active" id="tabTopDokter" onclick="gantiTabPenanganan('Dokter')">Penanganan Dokter</a>
                <a href="javascript:void(0)" class="khanza-tab-link" id="tabTopPetugas" onclick="gantiTabPenanganan('Petugas')">Penanganan Petugas</a>
                <a href="javascript:void(0)" class="khanza-tab-link" id="tabTopDokterPetugas" onclick="gantiTabPenanganan('Dokter & Petugas')">Penanganan Dokter & Petugas</a>
                <a href="{{ url('/soap/' . $hashId) }}" class="khanza-tab-link">Pemeriksaan</a>
                <a href="{{ url('/soap/' . $hashId) }}" class="khanza-tab-link">Pemeriksaan Obstetri</a>
                <a href="{{ url('/soap/' . $hashId) }}" class="khanza-tab-link">Pemeriksaan Ginekologi</a>
                <a href="{{ url('/pelayanan/diagnosa/' . $hashId) }}" class="khanza-tab-link">Diagnosa</a>
                <a href="{{ url('/pelayanan/resume/' . $hashId) }}" class="khanza-tab-link">Catatan Dokter</a>
            </div>

            <!-- FORM TINDAKAN KHANZA -->
            <form action="{{ route('pelayanan.tindakan.simpan', $hashId) }}" method="POST" id="formTindakanKhanza">
                @csrf
                <input type="hidden" name="tipe_penanganan" id="input_tipe_penanganan" value="Dokter">

                <!-- BARIS INPUT DOKTER (Hanya tampil pada Penanganan Dokter & Penanganan Dokter & Petugas) -->
                <div class="d-flex align-items-center mb-1 flex-wrap" id="rowDokter" style="gap: 6px;">
                    <span class="small font-weight-bold text-dark" style="min-width: 65px;">Dokter :</span>
                    <input type="text" name="kd_dokter" id="kd_dokter" value="{{ $pasien->kd_dokter ?? 'D0000061' }}" class="form-control form-control-sm font-monospace text-center" style="width: 100px;" readonly>
                    <input type="text" name="nm_dokter" id="nm_dokter" value="{{ $pasien->dokter_dituju ?? 'drg. Lukna Alinda Putri' }}" class="form-control form-control-sm" style="width: 320px;">
                    <button type="button" class="btn btn-sm btn-light border p-1" style="width: 28px; height: 28px;" onclick="bukaModalDokter()" title="Pilih Dokter">
                        <i class="fas fa-pencil-alt text-secondary" style="font-size: 11px;"></i>
                    </button>
                    <div class="ml-auto d-flex align-items-center gap-2">
                        <span class="badge badge-light border font-monospace px-2 py-1">No. Rawat: <strong>{{ $pasien->no_rawat }}</strong></span>
                        <span class="badge badge-success font-monospace px-2 py-1">Nama: <strong>{{ $pasien->nama_lengkap }}</strong></span>
                    </div>
                </div>

                <!-- BARIS INPUT PETUGAS (Hanya tampil pada Penanganan Petugas & Penanganan Dokter & Petugas) -->
                <div class="d-flex align-items-center mb-2 flex-wrap" id="rowPetugas" style="gap: 6px; display: none !important;">
                    <span class="small font-weight-bold text-dark" style="min-width: 65px;">Petugas :</span>
                    <input type="text" name="kd_petugas" id="kd_petugas" value="01091999" class="form-control form-control-sm font-monospace text-center" style="width: 100px;" readonly>
                    <input type="text" name="nm_petugas" id="nm_petugas" value="(IT)Reza Alamsyah Rapi" class="form-control form-control-sm" style="width: 320px;">
                    <button type="button" class="btn btn-sm btn-light border p-1" style="width: 28px; height: 28px;" onclick="bukaModalPetugas()" title="Pilih Petugas">
                        <i class="fas fa-pencil-alt text-secondary" style="font-size: 11px;"></i>
                    </button>
                </div>

                <!-- SUB-TABS: Daftar Tindakan/Tagihan vs Tindakan Dilakukan -->
                <div class="khanza-sub-tabs">
                    <a href="javascript:void(0)" class="khanza-sub-tab active" id="tabNavDaftar" onclick="switchSubTab('daftar')">Daftar Tindakan/Tagihan</a>
                    <a href="javascript:void(0)" class="khanza-sub-tab" id="tabNavDilakukan" onclick="switchSubTab('dilakukan')">
                        Tindakan Dilakukan (<span id="countDilakukan">{{ count($riwayatTindakan) }}</span>)
                    </a>
                </div>

                <!-- VIEW 1: DAFTAR TINDAKAN/TAGIHAN -->
                <div id="viewDaftarTindakan">
                    <div class="table-responsive" style="max-height: 440px; overflow-y: auto; border: 1px solid #cbd5e1;">
                        <table class="table table-sm table-bordered tabel-khanza mb-0" id="tableDaftarTindakan">
                            <thead class="sticky-top">
                                <tr>
                                    <th width="30" class="text-center">P</th>
                                    <th width="180">Kode</th>
                                    <th>Nama Perawatan</th>
                                    <th width="240">Kategori Perawatan</th>
                                    <th width="130" class="text-right">Tarif/Biaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($masterTindakan as $td)
                                    <tr class="row-tindakan-item" data-pelaksana="{{ $td->pelaksana ?? 'Dokter' }}" onclick="toggleRowCheckbox(this, event)">
                                        <td class="text-center">
                                            <input type="checkbox" name="kd_jenis_prw[]" value="{{ $td->kd_jenis_prw }}" class="cb-tindakan" onchange="syncRowSelect(this)">
                                        </td>
                                        <td class="font-monospace text-muted">{{ $td->kd_jenis_prw }}</td>
                                        <td class="font-weight-bold text-dark">{{ $td->nm_perawatan }}</td>
                                        <td class="text-muted">{{ $td->kategori ?? 'Tindakan Medis' }}</td>
                                        <td class="text-right font-monospace font-weight-bold text-dark">
                                            {{ number_format($td->total_byr, 0, ',', '.') }}
                                            <input type="hidden" name="biaya[{{ $td->kd_jenis_prw }}]" value="{{ $td->total_byr }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- VIEW 2: TINDAKAN DILAKUKAN -->
                <div id="viewTindakanDilakukan" style="display: none;">
                    <div class="table-responsive" style="border: 1px solid #cbd5e1;">
                        <table class="table table-sm table-bordered tabel-khanza mb-0">
                            <thead>
                                <tr>
                                    <th width="35" class="text-center">#</th>
                                    <th width="160">Kode</th>
                                    <th>Nama Tindakan / Prosedur</th>
                                    <th width="160">Tipe / Pelaksana</th>
                                    <th width="130">Waktu</th>
                                    <th width="140" class="text-right">Biaya (Rp)</th>
                                    <th width="50" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayatTindakan as $td)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="font-monospace text-primary">{{ $td->kd_jenis_prw }}</td>
                                        <td class="font-weight-bold text-dark">{{ $td->nm_perawatan }}</td>
                                        <td>
                                            <span class="badge badge-light border">{{ $td->tipe_penanganan ?? 'Petugas' }}</span>
                                            <small class="text-muted d-block">{{ $td->nm_dokter ?? $td->petugas }}</small>
                                        </td>
                                        <td class="small font-monospace text-muted">{{ $td->tgl_tindakan }} {{ substr($td->jam_tindakan, 0, 5) }}</td>
                                        <td class="text-right font-monospace font-weight-bold text-primary">
                                            {{ number_format($td->biaya, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('pelayanan.tindakan.hapus', ['id' => $td->id, 'hashId' => $hashId]) }}" onclick="return confirm('Hapus tindakan ini?')" class="btn btn-xs btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            Belum ada tindakan yang disimpan untuk pasien ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if (count($riwayatTindakan) > 0)
                                <tfoot class="bg-light font-weight-bold">
                                    <tr>
                                        <td colspan="5" class="text-right text-uppercase">Total Biaya Tindakan :</td>
                                        <td class="text-right font-monospace text-success font-weight-bold" style="font-size: 13px;">
                                            Rp {{ number_format($totalTindakan, 0, ',', '.') }}
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- BARIS CARI TANGGAL & KEY WORD -->
                <div class="d-flex align-items-center mt-2 pt-2 border-top flex-wrap" style="gap: 8px;">
                    <input type="date" class="form-control form-control-sm font-monospace" value="{{ date('Y-m-d') }}" style="width: 130px;">
                    <span class="small font-weight-bold text-muted">No.RM :</span>
                    <input type="text" class="form-control form-control-sm font-monospace text-center" value="{{ $pasien->no_rkm_medis }}" style="width: 100px;" readonly>
                    <button type="button" class="btn btn-sm btn-light border p-1" style="width: 26px; height: 26px;">
                        <i class="fas fa-pencil-alt text-secondary" style="font-size: 10px;"></i>
                    </button>
                    
                    <span class="small font-weight-bold text-muted ml-2">Key Word :</span>
                    <input type="text" id="keywordTindakan" placeholder="Ketik kata kunci tindakan..." class="form-control form-control-sm" style="width: 240px;" onkeyup="filterTindakanKhanza()">
                    <button type="button" class="btn btn-sm btn-light border text-success p-1" style="width: 28px; height: 28px;" onclick="filterTindakanKhanza()" title="Cari">
                        <i class="fas fa-check"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-light border text-danger p-1" style="width: 28px; height: 28px;" onclick="document.getElementById('keywordTindakan').value=''; filterTindakanKhanza()" title="Reset">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- FOOTER TOMBOL AKSI KHANZA -->
                <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top flex-wrap" style="gap: 6px;">
                    <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
                        <button type="submit" class="btn-khanza-bottom text-primary font-weight-bold">
                            <i class="fas fa-save text-primary"></i> Simpan
                        </button>
                        <button type="button" class="btn-khanza-bottom text-danger" onclick="switchSubTab('dilakukan')">
                            <i class="fas fa-trash text-danger"></i> Hapus
                        </button>
                        <button type="button" class="btn-khanza-bottom text-warning" onclick="switchSubTab('daftar')">
                            <i class="fas fa-edit text-warning"></i> Ganti
                        </button>
                        <button type="button" class="btn-khanza-bottom text-info" onclick="window.print()">
                            <i class="fas fa-print text-info"></i> Cetak
                        </button>
                        <button type="button" class="btn-khanza-bottom text-dark" onclick="resetFilterSemua()">
                            <i class="fas fa-search text-dark"></i> Semua
                        </button>
                        <span class="small font-weight-bold text-muted ml-2">Record : <span id="labelRecordCount">0</span></span>
                    </div>

                    <div>
                        <a href="{{ route('rawatjalan.index') }}" class="btn-khanza-bottom text-danger">
                            <i class="fas fa-times text-danger"></i> Keluar
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Modal Pilih Dokter / Petugas -->
<div class="modal fade" id="modalPilihPelaksana" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title font-weight-bold" id="modalTitlePelaksana"><i class="fas fa-user-check mr-1"></i> Pilih Petugas</h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive" style="max-height: 380px;">
                    <!-- Tabel Petugas -->
                    <table class="table table-hover table-sm mb-0" id="tableListPetugas">
                        <thead class="thead-light">
                            <tr>
                                <th>NIP / ID</th>
                                <th>Nama Petugas</th>
                                <th>Role</th>
                                <th class="text-center">Pilih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($daftarPetugas as $p)
                                <tr>
                                    <td class="font-monospace">{{ sprintf('%08d', $p->id) }}</td>
                                    <td class="font-weight-bold">{{ $p->name }}</td>
                                    <td><span class="badge badge-light border">{{ strtoupper($p->role) }}</span></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-primary font-weight-bold" onclick="setPelaksana('{{ sprintf('%08d', $p->id) }}', '{{ $p->name }}')">Pilih</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Tabel Dokter -->
                    <table class="table table-hover table-sm mb-0" id="tableListDokter" style="display: none;">
                        <thead class="thead-light">
                            <tr>
                                <th>Kode Dokter</th>
                                <th>Nama Dokter</th>
                                <th>Spesialis</th>
                                <th class="text-center">Pilih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($daftarDokter as $dr)
                                <tr>
                                    <td class="font-monospace">{{ $dr->kd_dokter }}</td>
                                    <td class="font-weight-bold">{{ $dr->nm_dokter }}</td>
                                    <td>{{ $dr->spesialis ?? 'Umum' }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-primary font-weight-bold" onclick="setPelaksana('{{ $dr->kd_dokter }}', '{{ $dr->nm_dokter }}')">Pilih</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let tipeSekarang = 'Dokter';

    function gantiTabPenanganan(tipe) {
        tipeSekarang = tipe;
        document.querySelectorAll('.khanza-tab-link').forEach(a => a.classList.remove('active'));

        const rowDokter = document.getElementById('rowDokter');
        const rowPetugas = document.getElementById('rowPetugas');

        if (tipe === 'Dokter') {
            document.getElementById('tabTopDokter').classList.add('active');
            rowDokter.style.setProperty('display', 'flex', 'important');
            rowPetugas.style.setProperty('display', 'none', 'important');
        } else if (tipe === 'Petugas') {
            document.getElementById('tabTopPetugas').classList.add('active');
            rowDokter.style.setProperty('display', 'none', 'important');
            rowPetugas.style.setProperty('display', 'flex', 'important');
        } else {
            // Penanganan Dokter & Petugas -> Muncul Keduanya
            document.getElementById('tabTopDokterPetugas').classList.add('active');
            rowDokter.style.setProperty('display', 'flex', 'important');
            rowPetugas.style.setProperty('display', 'flex', 'important');
        }

        document.getElementById('input_tipe_penanganan').value = tipe;

        // Filter isi daftar tindakan sesuai tab penanganan
        filterTindakanKhanza();
    }

    function switchSubTab(tab) {
        if (tab === 'daftar') {
            document.getElementById('viewDaftarTindakan').style.display = 'block';
            document.getElementById('viewTindakanDilakukan').style.display = 'none';
            document.getElementById('tabNavDaftar').classList.add('active');
            document.getElementById('tabNavDilakukan').classList.remove('active');
        } else {
            document.getElementById('viewDaftarTindakan').style.display = 'none';
            document.getElementById('viewTindakanDilakukan').style.display = 'block';
            document.getElementById('tabNavDaftar').classList.remove('active');
            document.getElementById('tabNavDilakukan').classList.add('active');
        }
    }

    function toggleRowCheckbox(tr, event) {
        if (event.target.tagName.toLowerCase() === 'input') return;
        const cb = tr.querySelector('.cb-tindakan');
        cb.checked = !cb.checked;
        syncRowSelect(cb);
    }

    function syncRowSelect(cb) {
        const tr = cb.closest('tr');
        if (cb.checked) {
            tr.classList.add('selected-row');
        } else {
            tr.classList.remove('selected-row');
        }
    }

    function filterTindakanKhanza() {
        const filterKeyword = document.getElementById('keywordTindakan').value.toLowerCase();
        const rows = document.querySelectorAll('.row-tindakan-item');
        let count = 0;

        rows.forEach(tr => {
            const pelaksana = tr.getAttribute('data-pelaksana');
            const text = tr.textContent.toLowerCase();

            // Cek kecocokan pelaksana dengan tab aktif
            let matchPelaksana = (pelaksana === tipeSekarang);
            if (tipeSekarang === 'Dokter & Petugas' && (pelaksana === 'Dokter & Petugas' || !pelaksana)) {
                matchPelaksana = true;
            }

            // Cek kata kunci pencarian
            const matchKeyword = text.includes(filterKeyword);

            if (matchPelaksana && matchKeyword) {
                tr.style.display = '';
                count++;
            } else {
                tr.style.display = 'none';
            }
        });

        document.getElementById('labelRecordCount').textContent = count;
    }

    function resetFilterSemua() {
        document.getElementById('keywordTindakan').value = '';
        filterTindakanKhanza();
        switchSubTab('daftar');
    }

    function bukaModalDokter() {
        document.getElementById('modalTitlePelaksana').innerHTML = '<i class="fas fa-user-md mr-1"></i> Pilih Dokter Pemeriksa';
        document.getElementById('tableListDokter').style.display = '';
        document.getElementById('tableListPetugas').style.display = 'none';
        $('#modalPilihPelaksana').modal('show');
    }

    function bukaModalPetugas() {
        document.getElementById('modalTitlePelaksana').innerHTML = '<i class="fas fa-user-nurse mr-1"></i> Pilih Petugas Rawat Luka / Paramedis';
        document.getElementById('tableListDokter').style.display = 'none';
        document.getElementById('tableListPetugas').style.display = '';
        $('#modalPilihPelaksana').modal('show');
    }

    function setPelaksana(kd, nm) {
        if (document.getElementById('tableListDokter').style.display !== 'none') {
            document.getElementById('kd_dokter').value = kd;
            document.getElementById('nm_dokter').value = nm;
        } else {
            document.getElementById('kd_petugas').value = kd;
            document.getElementById('nm_petugas').value = nm;
        }
        $('#modalPilihPelaksana').modal('hide');
    }

    // Default aktifkan mode Penanganan Dokter
    document.addEventListener('DOMContentLoaded', function () {
        gantiTabPenanganan('Dokter');
    });
</script>
@endsection
