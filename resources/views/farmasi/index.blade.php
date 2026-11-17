@extends('layout.layout')
@section('title', 'Pembelian Obat & Farmasi Khusus - ' . $pasien->nama_lengkap)

@section('konten')
    @if (Session::has('createSuccess'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            <div>
                <strong>Berhasil!</strong> {{ Session::get('createSuccess') }}
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- IDENTITAS PASIEN -->
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="clinic-header-icon mr-3" style="background: rgba(16, 185, 129, 0.1); color: #059669;">
                        <i class="fas fa-pills"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge badge-light border font-monospace">{{ $pasien->no_rawat }}</span>
                            @if (!empty($pasien->no_rkm_medis))
                                <span class="badge-rm ml-1">No. RM: {{ $pasien->no_rkm_medis }}</span>
                            @endif
                        </div>
                        <h4 class="font-weight-bold mb-0 text-dark">{{ $pasien->nama_lengkap }}</h4>
                        <small class="text-muted">
                            <i class="fas fa-venus-mars mr-1"></i>{{ $pasien->jenis_kelamin ?? '-' }} &bull; 
                            <i class="fas fa-phone mr-1"></i>{{ $pasien->no_telpon ?? '-' }} &bull; 
                            <i class="fas fa-map-marker-alt mr-1"></i>{{ $pasien->alamat_lengkap ?? '-' }}
                        </small>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-2 mt-md-0">
                    <a href="{{ route('soapPasienDetail', $hashId) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Riwayat Rekam Medis
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM PEMBELIAN / RESEP OBAT -->
    <div class="card card-outline card-success shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold mb-0 text-success">
                    <i class="fas fa-cart-plus mr-2"></i> Transaksi Obat, Salep, & Balutan Luka Modern
                </h6>
                <span class="badge badge-light border font-monospace text-primary font-weight-bold" style="font-size: 12px;">
                    No. Faktur: {{ $noFaktur }}
                </span>
            </div>
        </div>

        <form action="{{ route('farmasi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="no_faktur" value="{{ $noFaktur }}">
            <input type="hidden" name="no_rawat" value="{{ $pasien->no_rawat }}">

            <div class="card-body p-3">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-muted">Tanggal Transaksi :</label>
                        <input type="date" name="tgl_beli" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-muted">Jam :</label>
                        <input type="time" name="jam_beli" class="form-control form-control-sm" value="{{ date('H:i') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-muted">Petugas Farmasi :</label>
                        <input type="text" name="petugas" class="form-control form-control-sm font-weight-bold" value="{{ auth()->user()->name ?? 'Petugas Farmasi' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="small font-weight-bold text-muted">Catatan Khusus :</label>
                        <input type="text" name="catatan" class="form-control form-control-sm" placeholder="Contoh: Resep rawat luka berkala">
                    </div>
                </div>

                <!-- TABEL ITEM OBAT -->
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle" id="tableItemObat">
                        <thead class="thead-light">
                            <tr>
                                <th width="35%">Pilih Obat / Balutan Luka Modern</th>
                                <th width="15%">Kategori</th>
                                <th width="15%">Harga Satuan (Rp)</th>
                                <th width="10%">Jumlah</th>
                                <th width="15%">Aturan Pakai</th>
                                <th width="15%">Subtotal (Rp)</th>
                                <th width="5%" class="text-center"><i class="fas fa-trash"></i></th>
                            </tr>
                        </thead>
                        <tbody id="tbodyItemObat">
                            <!-- Baris 1 default -->
                            <tr class="item-row">
                                <td>
                                    <select class="form-control form-control-sm select-obat" name="items[0][kode_brng]" onchange="updateRowPrice(this)" required>
                                        <option value="">-- Pilih Obat / Alkes --</option>
                                        @foreach ($obatList as $ob)
                                            <option value="{{ $ob->kode_brng }}" data-harga="{{ $ob->ralan }}" data-satuan="{{ $ob->kode_sat }}" data-kategori="{{ $ob->kategori }}" data-stok="{{ $ob->stok }}">
                                                {{ $ob->nama_brng }} (Stok: {{ $ob->stok }} {{ $ob->kode_sat }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><span class="badge badge-light border text-muted row-kategori">-</span></td>
                                <td>
                                    <input type="number" name="items[0][biaya_obat]" class="form-control form-control-sm font-weight-bold text-right row-harga" value="0" readonly>
                                </td>
                                <td>
                                    <input type="number" name="items[0][jumlah]" class="form-control form-control-sm text-center row-jumlah" value="1" min="1" oninput="calculateRowTotal(this)">
                                </td>
                                <td>
                                    <input type="text" name="items[0][aturan_pakai]" class="form-control form-control-sm" value="Ganti Tiap 2 Hari" placeholder="Aturan / Dosis">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm font-weight-bold text-right text-success row-subtotal" value="0" readonly>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-xs btn-outline-danger" onclick="removeRow(this)" title="Hapus"><i class="fas fa-times"></i></button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3">
                                    <button type="button" class="btn btn-xs btn-outline-primary font-weight-bold" onclick="addNewRow()">
                                        <i class="fas fa-plus mr-1"></i> Tambah Baris Obat
                                    </button>
                                </td>
                                <td colspan="2" class="text-right font-weight-bold">TOTAL PEMBAYARAN :</td>
                                <td>
                                    <input type="text" id="grandTotal" class="form-control form-control-sm font-weight-bold text-right text-primary" style="font-size: 14px;" value="Rp 0" readonly>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white text-right py-3">
                <button type="submit" class="btn btn-success px-4 font-weight-bold shadow-sm">
                    <i class="fas fa-save mr-1"></i> Simpan Transaksi Pembelian Obat
                </button>
            </div>
        </form>
    </div>

    <!-- TABEL RIWAYAT TRANSAKSI OBAT PASIEN -->
    <div class="card card-outline card-secondary shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="font-weight-bold mb-0 text-dark">
                <i class="fas fa-history text-info mr-2"></i> Riwayat Pembelian Obat Pasien Ini
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0" style="font-size: 13px;">
                    <thead class="thead-light">
                        <tr>
                            <th width="40px" class="text-center">No</th>
                            <th>No. Faktur</th>
                            <th>No. Rawat</th>
                            <th>Tanggal & Jam</th>
                            <th>Total Tagihan</th>
                            <th>Petugas Farmasi</th>
                            <th>Catatan</th>
                            <th class="text-center" width="130px">Cetak Nota</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $noF = 1; ?>
                        @forelse ($riwayatBeli as $rb)
                            <tr>
                                <td class="text-center font-weight-bold text-muted">{{ $noF++ }}</td>
                                <td class="font-monospace font-weight-bold text-primary">{{ $rb->no_faktur }}</td>
                                <td class="font-monospace text-dark">{{ $rb->no_rawat }}</td>
                                <td>
                                    <strong>{{ date('d/m/Y', strtotime($rb->tgl_beli)) }}</strong>
                                    <div class="small text-muted">{{ $rb->jam_beli }} WIB</div>
                                </td>
                                <td class="font-weight-bold text-success">
                                    Rp {{ number_format($rb->total_bayar, 0, ',', '.') }}
                                </td>
                                <td>{{ $rb->petugas }}</td>
                                <td>{{ $rb->catatan ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('farmasi.cetakNota', $rb->no_faktur) }}" target="_blank" class="btn btn-sm btn-outline-primary font-weight-bold">
                                        <i class="fas fa-print mr-1"></i> Cetak Nota
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    Belum ada transaksi pembelian obat yang tercatat untuk pasien ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let rowIndex = 1;

        function updateRowPrice(selectEl) {
            const tr = selectEl.closest('tr');
            const selectedOpt = selectEl.options[selectEl.selectedIndex];
            const harga = parseFloat(selectedOpt.getAttribute('data-harga')) || 0;
            const kategori = selectedOpt.getAttribute('data-kategori') || '-';

            tr.querySelector('.row-kategori').textContent = kategori;
            tr.querySelector('.row-harga').value = harga;
            calculateRowTotal(tr.querySelector('.row-jumlah'));
        }

        function calculateRowTotal(jumlahInput) {
            const tr = jumlahInput.closest('tr');
            const harga = parseFloat(tr.querySelector('.row-harga').value) || 0;
            const jumlah = parseInt(jumlahInput.value) || 0;
            const subtotal = harga * jumlah;

            tr.querySelector('.row-subtotal').value = subtotal;
            recalculateGrandTotal();
        }

        function recalculateGrandTotal() {
            let total = 0;
            document.querySelectorAll('.row-subtotal').forEach(el => {
                total += parseFloat(el.value) || 0;
            });
            document.getElementById('grandTotal').value = 'Rp ' + total.toLocaleString('id-ID');
        }

        function addNewRow() {
            const tbody = document.getElementById('tbodyItemObat');
            const firstRow = tbody.querySelector('.item-row');
            const newRow = firstRow.cloneNode(true);

            // Update names
            newRow.querySelector('.select-obat').name = `items[${rowIndex}][kode_brng]`;
            newRow.querySelector('.select-obat').selectedIndex = 0;
            newRow.querySelector('.row-kategori').textContent = '-';
            newRow.querySelector('.row-harga').name = `items[${rowIndex}][biaya_obat]`;
            newRow.querySelector('.row-harga').value = 0;
            newRow.querySelector('.row-jumlah').name = `items[${rowIndex}][jumlah]`;
            newRow.querySelector('.row-jumlah').value = 1;
            newRow.querySelector('input[type="text"]').name = `items[${rowIndex}][aturan_pakai]`;
            newRow.querySelector('.row-subtotal').value = 0;

            tbody.appendChild(newRow);
            rowIndex++;
        }

        function removeRow(btn) {
            const tbody = document.getElementById('tbodyItemObat');
            if (tbody.querySelectorAll('.item-row').length > 1) {
                btn.closest('tr').remove();
                recalculateGrandTotal();
            } else {
                alert('Minimal harus ada 1 baris item obat!');
            }
        }
    </script>
@endsection

