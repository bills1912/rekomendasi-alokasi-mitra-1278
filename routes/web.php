<?php

use App\Http\Controllers\HonorMitraController;
use App\Http\Controllers\IPDSProjectController;
use App\Http\Controllers\IPDSProjectSLSController;
use App\Http\Controllers\KeuanganAlokasiMitraController;
use App\Http\Controllers\MenuMasterController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Menu Master
// New UI Kit
Route::get('/new_ui', [MenuMasterController::class, 'newUIPage'])->name("new_ui");
// Daftar Kegiatan
Route::post('/tambah_daftar_kegiatan', [MenuMasterController::class, 'tambahKegiatanSurveiSensus']);
Route::post('/edit_data_kegiatan/{id}', [MenuMasterController::class, 'editKegiatanSurveiSensus']);
Route::get('/hapus_kegiatan/{id}', [MenuMasterController::class, 'hapusKegiatanSurveiSensus']);
Route::get('kode_beban_anggaran', [MenuMasterController::class, 'pilihKodeBebanAnggaran']);

// Daftar Pengguna
Route::get('statusSubjectMatter', [MenuMasterController::class, 'pilihStatusSMPengguna']);
Route::get('statusAdmin', [MenuMasterController::class, 'pilihStatusAdminPengguna']);
Route::post('/editDataPengguna/{id}', [MenuMasterController::class, 'editDataPenggunaSeemitra']);

// Status Alokasi Honor Kegiatan
Route::post('/ubahStatusAlokasiHonorKegiatan/{id}', [MenuMasterController::class, 'ubahStatusAlokasiHonorKegiatan']);

// Daftar Mitra
Route::get('jenisKegiatanMitra', [MenuMasterController::class, 'pilihJenisKegiatanMitra']);
Route::get('jenisKelamin', [MenuMasterController::class, 'pilihJenisKelamin']);
Route::get('/daftar_sbml/{id}', [MenuMasterController::class, 'autofill'])->name('autofill');
Route::post('/tambah_daftar_mitra', [MenuMasterController::class, 'tambahDaftarMitra']);
Route::post('/daftar_mitra', [MenuMasterController::class, 'ambilMitraDenganOB']);

// Daftar SBML
Route::get('namaKegiatanSBML', [MenuMasterController::class, 'pilihNamaKegiatanSBML']);
Route::get('jenisKegiatanSBML', [MenuMasterController::class, 'pilihJenisKegiatanSBML']);
Route::post('/tambah_data_sbml', [MenuMasterController::class, 'tambahDataSBML']);

// Keuangan Alokasi Mitra
Route::get('/daftar_ringkasan_honor', [KeuanganAlokasiMitraController::class, 'tabelRingkasanHonorPage']);

// Pengalokasian Mitra
Route::get('selectRegency', [IPDSProjectController::class, 'pilihKako']);
Route::get('selectDistrict/{id_kec}', [IPDSProjectController::class, 'pilihKec']);
Route::get('selectVillage/{id_desa}', [IPDSProjectController::class, 'pilihDesa']);
Route::get('selectBS/{id_bs}', [IPDSProjectController::class, 'pilihBS']);
Route::get('selectSurvei', [IPDSProjectController::class, 'pilihKegiatanSurvei']);

Route::post('/list_mitra_survei_teralokasi', [IPDSProjectController::class, 'ambilMitraTeralokasiPerKegiatan']);
Route::post('/list_mitra_survei_teralokasi/{id}', [IPDSProjectController::class, 'editDataMitraSurveiTeralokasi']);
Route::get('/list_mitra_survei_teralokasi/{id}', [IPDSProjectController::class, 'hapusDataMitraSurveiTeralokasi']);

Route::get('initializeSurvei', [IPDSProjectController::class, 'inisialisasiKegiatanSurvei']);

Route::get('selectProvSensus', [IPDSProjectSLSController::class, 'pilihProvinsiSensus']);
Route::get('selectRegencySensus/{id_kako}', [IPDSProjectSLSController::class, 'pilihKakoSensus']);
Route::get('selectDistrictSensus/{id_kec}', [IPDSProjectSLSController::class, 'pilihKecSensus']);
Route::get('selectVillageSensus/{id_desa}', [IPDSProjectSLSController::class, 'pilihDesaSensus']);
Route::get('selectSLS/{id_sls}', [IPDSProjectSLSController::class, 'pilihSLS']);
Route::post('/alokasi_mitra_survei', [IPDSProjectController::class, 'ambilIDBSMitra']);
Route::post('/alokasi_mitra_sensus', [IPDSProjectSLSController::class, 'ambilIDSLSMitra']);
Route::post('/upload-sample-bs', [IPDSProjectController::class, 'ambilSampelWilkerstat']);
Route::post('/upload-mitra-terpilih', [IPDSProjectSLSController::class, 'ambilMitraSensusTerpilih']);
Route::get('/alokasi_mitra_survei/{id}', [IPDSProjectController::class, 'pengalokasianMitraSurvei']);

Route::get('/pilih_mitra_dahulu', [IPDSProjectController::class, 'redirectPilihMitra']);

// Rate Honor
Route::post('/uploadRateHonorMitra', [HonorMitraController::class, 'simpanRateHonor']);
Route::post('/generate_surat_bast', [HonorMitraController::class, 'generateBAST']);
Route::post('/generate_surat_spk', [HonorMitraController::class, 'generateSPK']);


// Login with Google
Route::get('auth/google', [IPDSProjectController::class, 'googleLoginPage']);
Route::get('auth/google/callback', [IPDSProjectController::class, 'googleLoginAuthentication']);

// Login with Facebook
Route::get('auth/facebook', [IPDSProjectController::class, 'facebookLoginPage']);
Route::get('auth/facebook/callback', [IPDSProjectController::class, 'facebookLoginAuthentication']);

Route::get('/resetUploadSample', [IPDSProjectController::class, 'ulangiUploadSample']);
Route::get('/resetAllCookies', [IPDSProjectController::class, 'hapusCookies']);

// Daftar Ringkasan Honor
Route::post('/daftar_ringkasan_honor', [KeuanganAlokasiMitraController::class, 'filterRateHonorPerBulan']);

Route::middleware('auth')->group(function () {

    // Master Menu
    Route::get('/daftar_kegiatan', [MenuMasterController::class, 'daftarKegiatanPage']);
    Route::get('/daftar_pengguna', [ProfileController::class, 'listUser']);
    Route::get('/daftar_mitra', [MenuMasterController::class, 'daftarMitraAllPage']);
    Route::get('/daftar_sbml', [MenuMasterController::class, 'daftarSBMLPage']);
    Route::get('/status_alokasi_kegiatan', [MenuMasterController::class, 'statusAlokasiKegiatanPage']);

    // Survei
    Route::get('/', [IPDSProjectController::class, 'index'])->name("map_table");
    Route::get('/alokasi_mitra_survei', [IPDSProjectController::class, 'alokasiMitra'])->name("alokasi_mitra.id_sls");
    Route::get('/upload_sample_bs', [IPDSProjectController::class, 'uploadSampleBlokSensus'])->name("sample_wilkerstat");
    Route::get('/list_mitra_survei_teralokasi', [IPDSProjectController::class, 'daftarMitraTeralokasi']);

    // Sensus
    Route::get('/alokasi_mitra_sensus', [IPDSProjectSLSController::class, 'alokasiMitraSensus'])->name("mitra_sensus");
    Route::get('/upload_mitra_terpilih', [IPDSProjectSLSController::class, 'uploadMitraSensusTerpilih'])->name("mitra_sensus_terpilih");

    // Rate Honor
    Route::get('/rate_honor', [HonorMitraController::class, 'tabelRateHonor']);

    // Mitra Side
    Route::get('/mitra_daftar_kegiatan', [IPDSProjectController::class, 'daftarKegiatanMitra']);
});

require __DIR__ . '/auth.php';
