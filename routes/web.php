<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoapController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PadepokanController;
use App\Http\Controllers\MasterPasienController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/mesinlogin', [LoginController::class, 'mesinLogin'])->name('mesinLogin');
});

Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', [LoginController::class, 'mesinLogout'])->name('mesinLogout');
    Route::group(['middleware' => 'can:role, "admin"'], function () {
        Route::get('/addUser', [LoginController::class, 'addUser'])->name('addUser');
        Route::post('/addUserKlinik', [LoginController::class, 'addUserKlinik'])->name('addUserKlinik');
        Route::get('/editPetugas/{id}', [LoginController::class, 'editPetugas'])->name('editPetugas');
        Route::post('/editUserKlinik/{id}', [LoginController::class, 'editUserKlinik'])->name('editUserKlinik');
        Route::get('/hapuPetugas/{id}', [LoginController::class, 'hapuPetugas'])->name('hapuPetugas');
    });
    // Route untuk Master Pasien (Rekam Medis - Khanza Style)
    Route::get('/masterPasien', function() {
        return redirect()->route('masterPasien.create');
    })->name('masterPasien.index');
    Route::get('/rm-baru', [MasterPasienController::class, 'create'])->name('masterPasien.create');
    Route::get('/masterPasien/create', function() {
        return redirect()->route('masterPasien.create');
    });
    Route::post('/masterPasien/store', [MasterPasienController::class, 'store'])->name('masterPasien.store');
    Route::get('/masterPasien/edit/{id}', [MasterPasienController::class, 'edit'])->name('masterPasien.edit');
    Route::put('/masterPasien/update/{id}', [MasterPasienController::class, 'update'])->name('masterPasien.update');
    Route::delete('/masterPasien/destroy/{id}', [MasterPasienController::class, 'destroy'])->name('masterPasien.destroy');
    Route::get('/masterPasien/cetakKartu/{id}', [MasterPasienController::class, 'cetakKartu'])->name('masterPasien.cetakKartu');
    Route::get('/api/masterPasien/search', [MasterPasienController::class, 'apiSearch'])->name('masterPasien.apiSearch');
    Route::get('/api/masterPasien/detail/{id}', [MasterPasienController::class, 'apiDetail'])->name('masterPasien.apiDetail');

    // Route untuk Master Dokter, Poliklinik & Penjab (Khanza Style)
    Route::get('/master/dokter', [\App\Http\Controllers\MasterKhanzaController::class, 'dokterIndex'])->name('master.dokter.index');
    Route::post('/master/dokter/store', [\App\Http\Controllers\MasterKhanzaController::class, 'dokterStore'])->name('master.dokter.store');
    Route::post('/master/dokter/update/{id}', [\App\Http\Controllers\MasterKhanzaController::class, 'dokterUpdate'])->name('master.dokter.update');
    Route::get('/master/dokter/delete/{id}', [\App\Http\Controllers\MasterKhanzaController::class, 'dokterDestroy'])->name('master.dokter.destroy');

    Route::get('/master/poli', [\App\Http\Controllers\MasterKhanzaController::class, 'poliIndex'])->name('master.poli.index');
    Route::post('/master/poli/store', [\App\Http\Controllers\MasterKhanzaController::class, 'poliStore'])->name('master.poli.store');
    Route::post('/master/poli/update/{id}', [\App\Http\Controllers\MasterKhanzaController::class, 'poliUpdate'])->name('master.poli.update');
    Route::get('/master/poli/delete/{id}', [\App\Http\Controllers\MasterKhanzaController::class, 'poliDestroy'])->name('master.poli.destroy');

    Route::get('/master/penjab', [\App\Http\Controllers\MasterKhanzaController::class, 'penjabIndex'])->name('master.penjab.index');
    Route::post('/master/penjab/store', [\App\Http\Controllers\MasterKhanzaController::class, 'penjabStore'])->name('master.penjab.store');
    Route::post('/master/penjab/update/{id}', [\App\Http\Controllers\MasterKhanzaController::class, 'penjabUpdate'])->name('master.penjab.update');
    Route::get('/master/penjab/delete/{id}', [\App\Http\Controllers\MasterKhanzaController::class, 'penjabDestroy'])->name('master.penjab.destroy');

    // Route untuk Master Jadwal Praktek Dokter (SIMRS Khanza Style)
    Route::get('/master/jadwal', [\App\Http\Controllers\MasterKhanzaController::class, 'jadwalIndex'])->name('master.jadwal.index');
    Route::post('/master/jadwal/store', [\App\Http\Controllers\MasterKhanzaController::class, 'jadwalStore'])->name('master.jadwal.store');
    Route::post('/master/jadwal/delete', [\App\Http\Controllers\MasterKhanzaController::class, 'jadwalDestroy'])->name('master.jadwal.destroy');

    // Route untuk Master Data Obat & Alkes (Farmasi)
    Route::get('/master/obat', [\App\Http\Controllers\MasterKhanzaController::class, 'obatIndex'])->name('master.obat.index');
    Route::post('/master/obat/store', [\App\Http\Controllers\MasterKhanzaController::class, 'obatStore'])->name('master.obat.store');
    Route::post('/master/obat/update/{kode}', [\App\Http\Controllers\MasterKhanzaController::class, 'obatUpdate'])->name('master.obat.update');
    Route::get('/master/obat/delete/{kode}', [\App\Http\Controllers\MasterKhanzaController::class, 'obatDestroy'])->name('master.obat.destroy');

    // Route Master Tindakan & Tarif Pelayanan
    Route::get('/master/tindakan', [\App\Http\Controllers\PelayananKlinikController::class, 'masterTindakanIndex'])->name('master.tindakan.index');
    Route::post('/master/tindakan/store', [\App\Http\Controllers\PelayananKlinikController::class, 'masterTindakanStore'])->name('master.tindakan.store');
    Route::get('/master/obat/get-stok-depo/{kode}', [\App\Http\Controllers\MasterKhanzaController::class, 'getStokDepo'])->name('master.obat.getStokDepo');
    Route::post('/master/obat/update-stok-depo', [\App\Http\Controllers\MasterKhanzaController::class, 'updateStokDepo'])->name('master.obat.updateStokDepo');

    // API Master untuk Form Registrasi
    Route::get('/api/master/dokter', [\App\Http\Controllers\MasterKhanzaController::class, 'apiDokter'])->name('api.master.dokter');
    Route::get('/api/master/poli', [\App\Http\Controllers\MasterKhanzaController::class, 'apiPoli'])->name('api.master.poli');
    Route::get('/api/master/penjab', [\App\Http\Controllers\MasterKhanzaController::class, 'apiPenjab'])->name('api.master.penjab');

    // Route untuk Penjualan Obat Bebas (Standar mLITE / SIMRS Khanza)
    Route::get('/modul/farmasi/penjualan-bebas', [\App\Http\Controllers\PenjualanBebasController::class, 'index'])->name('penjualan.bebas.index');
    Route::post('/modul/farmasi/penjualan-bebas/store', [\App\Http\Controllers\PenjualanBebasController::class, 'store'])->name('penjualan.bebas.store');
    Route::get('/modul/farmasi/penjualan-bebas/cetak/{noNota}', [\App\Http\Controllers\PenjualanBebasController::class, 'cetakNota'])->name('penjualan.bebas.cetak');
    Route::get('/modul/farmasi/penjualan-bebas/delete/{id}', [\App\Http\Controllers\PenjualanBebasController::class, 'destroy'])->name('penjualan.bebas.delete');

    // Route untuk Mutasi Stok Obat & Alkes Antar Depo (Standar SIMRS Khanza & mLITE)
    Route::get('/modul/farmasi/mutasi', [\App\Http\Controllers\MutasiObatController::class, 'index'])->name('farmasi.mutasi.index');
    Route::post('/modul/farmasi/mutasi/store', [\App\Http\Controllers\MutasiObatController::class, 'store'])->name('farmasi.mutasi.store');

    // Route untuk Penerimaan & Pengadaan Obat Masuk (Standar SIMRS Khanza DlgPembelian)
    Route::get('/modul/farmasi/penerimaan', [\App\Http\Controllers\PenerimaanObatController::class, 'index'])->name('farmasi.penerimaan.index');
    Route::post('/modul/farmasi/penerimaan/store', [\App\Http\Controllers\PenerimaanObatController::class, 'store'])->name('farmasi.penerimaan.store');

    // Route Modul Standar mLITE (Layanan, Farmasi, Keuangan, Bridging, Rekam Medik, Manajemen) - Placeholder Fallback
    Route::get('/modul/{kategori}/{modul}', [\App\Http\Controllers\ModulLayananController::class, 'modulPlaceholder'])->name('modul.placeholder');

    // Route untuk Controller = IndexController (Dashboard Polosan + Logo)
    Route::get('/', [IndexController::class, 'dashboard'])->name('dashboard');
    Route::get('/rawat-jalan', [IndexController::class, 'Index'])->name('rawatjalan.index');
    Route::get('/kunjungan', function() {
        return redirect()->route('rawatjalan.index');
    })->name('kunjungan.index');
    Route::get('/cariTanggal', [IndexController::class, 'IndexCari'])->name('cariTanggal');

    // Route untuk Controller = PasienController
    Route::get('/registrasi-pasien', [PasienController::class, 'formPasien'])->name('registrasi.pasien');
    Route::get('/formPasien', function() {
        return redirect()->route('registrasi.pasien');
    })->name('formPasien');
    Route::get('/api/cariMasterPasien',[PasienController::class, 'cariMasterPasien'])->name('cariMasterPasien');
    Route::get('/api/getDetailMasterPasien/{id}',[PasienController::class, 'getDetailMasterPasien'])->name('getDetailMasterPasien');
    Route::Post('/createPasien',[PasienController::class, 'createPasien'])->name('createPasien');
    Route::get('/detailPasien/{id}',[PasienController::class, 'detailPasien'])->name('detailPasien');
    Route::get('/delete/{id}',[PasienController::class, 'deletePasien'])->name('deletePasien');
    Route::Post('/editPasien/{id}',[PasienController::class, 'editPasien'])->name('editPasien');

    // Route untuk Controller = SoapController
    Route::get('/soap/{id}',[SoapController::class, 'soapPasien'])->name('soap');
    Route::Post('/createSoapPasien',[SoapController::class, 'createSoapPasien'])->name('createSoapPasien');
    Route::Post('/editSoapPasien/{id}',[SoapController::class, 'editSoapPasien'])->name('editSoapPasien');
    Route::get('/soapPasienDetail/{id}',[SoapController::class, 'soapPasienDetail'])->name('soapPasienDetail');
    Route::get('/detailSoapPasien/{id}',[SoapController::class, 'detailSoapPasien'])->name('detailSoapPasien');
    Route::get('/deleteSoap/{id}',[SoapController::class, 'deleteSoap'])->name('deleteSoap')->middleware('can:role, "admin"');

    // Router Untuk Controller = PadepokanController
    Route::get('/formPadepokan',[PadepokanController::class, 'formPadepokan'])->name('formPadepokan');
    Route::get('/editPeserta/{id}',[PadepokanController::class, 'editPeserta'])->name('editPeserta');
    Route::Post('/addPesertaPadepokan',[PadepokanController::class, 'addPesertaPadepokan'])->name('addPesertaPadepokan');
    Route::Post('/editPesertaPadepokan/{id}',[PadepokanController::class, 'editPesertaPadepokan'])->name('editPesertaPadepokan');
    Route::get('/deletePeserta/{id}',[PadepokanController::class, 'deletePeserta'])->name('deletePeserta');

    // Route untuk Pemeriksaan & Permintaan Laboratorium (SIMRS Khanza Style)
    Route::get('/laboratorium/permintaan/{id}', [\App\Http\Controllers\LaboratoriumController::class, 'permintaanLab'])->name('lab.permintaan');
    Route::post('/laboratorium/permintaan/simpan', [\App\Http\Controllers\LaboratoriumController::class, 'simpanPermintaanLab'])->name('lab.permintaan.simpan');
    Route::get('/laboratorium/periksa/{id}', [\App\Http\Controllers\LaboratoriumController::class, 'periksaLab'])->name('lab.periksa');
    Route::post('/laboratorium/periksa/simpan', [\App\Http\Controllers\LaboratoriumController::class, 'simpanHasilLab'])->name('lab.periksa.simpan');
    Route::get('/laboratorium/cetak/{periksaId}', [\App\Http\Controllers\LaboratoriumController::class, 'cetakHasilLab'])->name('lab.cetakHasil');

    // Route untuk Pembelian Obat & Farmasi Khusus
    Route::get('/farmasi/pembelian/{id}', [\App\Http\Controllers\FarmasiController::class, 'index'])->name('farmasi.index');
    Route::post('/farmasi/pembelian/store', [\App\Http\Controllers\FarmasiController::class, 'store'])->name('farmasi.store');
    Route::get('/farmasi/cetak-nota/{fakturId}', [\App\Http\Controllers\FarmasiController::class, 'cetakNota'])->name('farmasi.cetakNota');

    // Route untuk Kasir Billing, Diagnosa ICD-10 & Resume Medis Rawat Jalan (SIMRS Khanza Style)
    Route::get('/pelayanan/billing/{id}', [\App\Http\Controllers\PelayananMedisController::class, 'billing'])->name('pelayanan.billing');
    Route::post('/pelayanan/billing/{id}', [\App\Http\Controllers\PelayananMedisController::class, 'simpanBilling'])->name('pelayanan.billing.simpan');
    Route::get('/pelayanan/billing/cetak/{id}', [\App\Http\Controllers\PelayananMedisController::class, 'cetakBilling'])->name('pelayanan.billing.cetak');
    Route::post('/pelayanan/billing/hapus-nota/{id}', [\App\Http\Controllers\PelayananMedisController::class, 'hapusNotaBilling'])->name('pelayanan.billing.hapusNota');

    // Route untuk E-Resep Dokter & Etiket Obat
    Route::get('/pelayanan/resep/{id}', [\App\Http\Controllers\PelayananKlinikController::class, 'resepPasien'])->name('pelayanan.resep');
    Route::post('/pelayanan/resep/{id}', [\App\Http\Controllers\PelayananKlinikController::class, 'simpanResep'])->name('pelayanan.resep.simpan');
    Route::get('/pelayanan/resep/cetak-etiket/{id}', [\App\Http\Controllers\PelayananKlinikController::class, 'cetakEtiket'])->name('pelayanan.resep.cetakEtiket');

    // Route untuk Input Tindakan Medis Pasien
    Route::get('/pelayanan/tindakan/{id}', [\App\Http\Controllers\PelayananKlinikController::class, 'tindakanPasien'])->name('pelayanan.tindakan');
    Route::post('/pelayanan/tindakan/{id}', [\App\Http\Controllers\PelayananKlinikController::class, 'simpanTindakan'])->name('pelayanan.tindakan.simpan');
    Route::get('/pelayanan/tindakan/hapus/{id}/{hashId}', [\App\Http\Controllers\PelayananKlinikController::class, 'hapusTindakan'])->name('pelayanan.tindakan.hapus');

    // Route untuk Laporan Rekapitulasi Kasir & Pendapatan Klinik
    Route::get('/laporan/kasir', [\App\Http\Controllers\LaporanKasirController::class, 'index'])->name('laporan.kasir.index');
    Route::get('/laporan/kasir/cetak', [\App\Http\Controllers\LaporanKasirController::class, 'cetak'])->name('laporan.kasir.cetak');

    Route::get('/pelayanan/diagnosa/{id}', [\App\Http\Controllers\PelayananMedisController::class, 'diagnosa'])->name('pelayanan.diagnosa');
    Route::get('/api/cari-icd10', [\App\Http\Controllers\PelayananMedisController::class, 'cariIcd10'])->name('api.cari.icd10');
    Route::post('/pelayanan/diagnosa/{id}', [\App\Http\Controllers\PelayananMedisController::class, 'simpanDiagnosa'])->name('pelayanan.diagnosa.simpan');
    Route::get('/pelayanan/diagnosa/hapus/{id}', [\App\Http\Controllers\PelayananMedisController::class, 'hapusDiagnosa'])->name('pelayanan.diagnosa.hapus');

    Route::get('/pelayanan/resume/{id}', [\App\Http\Controllers\PelayananMedisController::class, 'resume'])->name('pelayanan.resume');
    Route::post('/pelayanan/resume/{id}', [\App\Http\Controllers\PelayananMedisController::class, 'simpanResume'])->name('pelayanan.resume.simpan');
    Route::get('/pelayanan/resume/hapus/{id}', [\App\Http\Controllers\PelayananMedisController::class, 'hapusResume'])->name('pelayanan.resume.hapus');
    Route::get('/pelayanan/resume/cetak/{id}', [\App\Http\Controllers\PelayananMedisController::class, 'cetakResume'])->name('pelayanan.resume.cetak');
    Route::get('/api/pelayanan/cari-pasien', [\App\Http\Controllers\PelayananMedisController::class, 'apiPencarianPasien'])->name('api.pelayanan.cari-pasien');

    // ==========================================
    // ROUTE MODUL PERSURATAN KLINIS & KEPERAWATAN
    // ==========================================
    Route::prefix('surat')->name('surat.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuratController::class, 'index'])->name('index');

        // 1. Surat Rujukan Pasien
        Route::get('/rujukan', [\App\Http\Controllers\SuratController::class, 'rujukanIndex'])->name('rujukan.index');
        Route::post('/rujukan/store', [\App\Http\Controllers\SuratController::class, 'rujukanStore'])->name('rujukan.store');
        Route::get('/rujukan/cetak/{id}', [\App\Http\Controllers\SuratController::class, 'rujukanCetak'])->name('rujukan.cetak');
        Route::get('/rujukan/delete/{id}', [\App\Http\Controllers\SuratController::class, 'rujukanDestroy'])->name('rujukan.destroy');

        // 2. Buku Ekspedisi
        Route::get('/ekspedisi', [\App\Http\Controllers\SuratController::class, 'ekspedisiIndex'])->name('ekspedisi.index');
        Route::post('/ekspedisi/store', [\App\Http\Controllers\SuratController::class, 'ekspedisiStore'])->name('ekspedisi.store');
        Route::get('/ekspedisi/cetak', [\App\Http\Controllers\SuratController::class, 'ekspedisiCetak'])->name('ekspedisi.cetak');
        Route::get('/ekspedisi/delete/{id}', [\App\Http\Controllers\SuratController::class, 'ekspedisiDestroy'])->name('ekspedisi.destroy');

        // 3. Formulir Asuhan Keperawatan (Askep)
        Route::get('/askep', [\App\Http\Controllers\SuratController::class, 'askepIndex'])->name('askep.index');
        Route::post('/askep/store', [\App\Http\Controllers\SuratController::class, 'askepStore'])->name('askep.store');
        Route::get('/askep/cetak/{id}', [\App\Http\Controllers\SuratController::class, 'askepCetak'])->name('askep.cetak');
        Route::get('/askep/delete/{id}', [\App\Http\Controllers\SuratController::class, 'askepDestroy'])->name('askep.destroy');

        // 4. Surat Pelimpahan Wewenang Medis
        Route::get('/pelimpahan', [\App\Http\Controllers\SuratController::class, 'pelimpahanIndex'])->name('pelimpahan.index');
        Route::post('/pelimpahan/store', [\App\Http\Controllers\SuratController::class, 'pelimpahanStore'])->name('pelimpahan.store');
        Route::get('/pelimpahan/cetak/{id}', [\App\Http\Controllers\SuratController::class, 'pelimpahanCetak'])->name('pelimpahan.cetak');
        Route::get('/pelimpahan/delete/{id}', [\App\Http\Controllers\SuratController::class, 'pelimpahanDestroy'])->name('pelimpahan.destroy');

        // 5. Surat Keterangan Sakit
        Route::get('/sakit', [\App\Http\Controllers\SuratController::class, 'sakitIndex'])->name('sakit.index');
        Route::post('/sakit/store', [\App\Http\Controllers\SuratController::class, 'sakitStore'])->name('sakit.store');
        Route::get('/sakit/cetak/{id}', [\App\Http\Controllers\SuratController::class, 'sakitCetak'])->name('sakit.cetak');
        Route::get('/sakit/delete/{id}', [\App\Http\Controllers\SuratController::class, 'sakitDestroy'])->name('sakit.destroy');

        // 6. Surat Keterangan Sehat
        Route::get('/sehat', [\App\Http\Controllers\SuratController::class, 'sehatIndex'])->name('sehat.index');
        Route::post('/sehat/store', [\App\Http\Controllers\SuratController::class, 'sehatStore'])->name('sehat.store');
        Route::get('/sehat/cetak/{id}', [\App\Http\Controllers\SuratController::class, 'sehatCetak'])->name('sehat.cetak');
        Route::get('/sehat/delete/{id}', [\App\Http\Controllers\SuratController::class, 'sehatDestroy'])->name('sehat.destroy');

        // 7. Lembar Observasi Khusus
        Route::get('/observasi', [\App\Http\Controllers\SuratController::class, 'observasiIndex'])->name('observasi.index');
        Route::post('/observasi/store', [\App\Http\Controllers\SuratController::class, 'observasiStore'])->name('observasi.store');
        Route::get('/observasi/cetak/{id}', [\App\Http\Controllers\SuratController::class, 'observasiCetak'])->name('observasi.cetak');
        Route::get('/observasi/delete/{id}', [\App\Http\Controllers\SuratController::class, 'observasiDestroy'])->name('observasi.destroy');

        // 8. Form Pelaporan ke Puskesmas
        Route::get('/laporan-puskesmas', [\App\Http\Controllers\SuratController::class, 'puskesmasIndex'])->name('puskesmas.index');
        Route::post('/laporan-puskesmas/store', [\App\Http\Controllers\SuratController::class, 'puskesmasStore'])->name('puskesmas.store');
        Route::get('/laporan-puskesmas/cetak/{id}', [\App\Http\Controllers\SuratController::class, 'puskesmasCetak'])->name('puskesmas.cetak');
        Route::get('/laporan-puskesmas/delete/{id}', [\App\Http\Controllers\SuratController::class, 'puskesmasDestroy'])->name('puskesmas.destroy');

        // 9. Buku Kerja Klien
        Route::get('/buku-kerja', [\App\Http\Controllers\SuratController::class, 'bukuMerjaIndex'])->name('bukumerja.index');
        Route::get('/buku-merja', [\App\Http\Controllers\SuratController::class, 'bukuMerjaIndex']); // alias kompatibilitas
        Route::post('/buku-kerja/store', [\App\Http\Controllers\SuratController::class, 'bukuMerjaStore'])->name('bukumerja.store');
        Route::post('/buku-merja/store', [\App\Http\Controllers\SuratController::class, 'bukuMerjaStore']);
        Route::get('/buku-kerja/cetak/{id}', [\App\Http\Controllers\SuratController::class, 'bukuMerjaCetak'])->name('bukumerja.cetak');
        Route::get('/buku-merja/cetak/{id}', [\App\Http\Controllers\SuratController::class, 'bukuMerjaCetak']);
        Route::get('/buku-kerja/delete/{id}', [\App\Http\Controllers\SuratController::class, 'bukuMerjaDestroy'])->name('bukumerja.destroy');
        Route::get('/buku-merja/delete/{id}', [\App\Http\Controllers\SuratController::class, 'bukuMerjaDestroy']);
    });

    // Route untuk Tracker Aktivitas & Pengaturan Hak Akses (Khusus Admin)
    Route::group(['middleware' => 'can:role, "admin"'], function () {
        Route::get('/tracker', [\App\Http\Controllers\TrackerController::class, 'index'])->name('tracker.index');
        Route::get('/user/permissions/{id}', [\App\Http\Controllers\TrackerController::class, 'permissions'])->name('tracker.permissions');
        Route::post('/user/permissions/{id}', [\App\Http\Controllers\TrackerController::class, 'updatePermissions'])->name('tracker.permissions.update');
    });

});
