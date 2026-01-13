<?php

namespace App\Http\Controllers;

use App\Models\AdminStatusModel;
use App\Models\DaftarMitra;
use App\Models\DaftarSurveiModel;
use App\Models\DataSBML;
use App\Models\KodeBebanAnggaran;
use App\Models\RateHonor;
use App\Models\SMStatus;
use App\Models\User;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;


class MenuMasterController extends Controller
{
    // New UI
    public function newUIPage()
    {
        if (Auth::check() && Auth::user()->is_sm) {
            return view('new-home-ui');
        } else {
            return view('mitra_view.container.mitra_index');
        }
    }
    // Daftar Kegiatan
    public function daftarKegiatanPage()
    {
        $data_daftar_kegiatan = DaftarSurveiModel::all();
        return view('master_menu.daftar_kegiatan', [
            'semua_kegiatan' => $data_daftar_kegiatan
        ]);
    }

    public function pilihKodeBebanAnggaran()
    {
        $data = KodeBebanAnggaran::where('jenis_komponen', 'LIKE', '%' . request('q') . '%')->orWhere('kode', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }

    public function tambahKegiatanSurveiSensus(Request $request)
    {
        DaftarSurveiModel::create([
            'daftar_kegiatan_survei' => strtoupper($request->input('namaKegiatan')),
            'waktu_mulai' => $request->input('periodeKegiatanAwal'),
            'waktu_berakhir' => $request->input('periodeKegiatanAkhir'),
            'jenis_kegiatan' => $request->input('jenisKegiatan'),
            'jenis_pembayaran' => $request->input('jenisPembayaran'),
            'jumlah_satuan' => $request->input('jumlahSatuan'),
            'nominal_per_satuan_pml' => (float)filter_var($request->input('nominalperSatuanPML'), FILTER_SANITIZE_NUMBER_FLOAT),
            'nominal_per_satuan_pengolahan' => (float)filter_var($request->input('nominalperSatuanPengolahan'), FILTER_SANITIZE_NUMBER_FLOAT),
            'nominal_per_satuan' => (float)filter_var($request->input('nominalperSatuan'), FILTER_SANITIZE_NUMBER_FLOAT),
            'total_anggaran' => $request->input('jumlahSatuan') * (float)filter_var($request->input('nominalperSatuan'), FILTER_SANITIZE_NUMBER_FLOAT),
            'jumlah_petugas_kegiatan' => $request->input('jumlahPetugasKegiatan'),
            'periode_pencairan_honor' => $request->input('periodePencairanAnggaran'),
            'kode_beban_anggaran' => "054.01.GG." . $request->input('bebanAnggaranKegiatan') . ".005.A.521213",
        ]);

        Alert::success('Berhasil', 'Kegiatan berhasil ditambahkan');
        return back();
    }

    public function editKegiatanSurveiSensus(Request $request, $id)
    {
        DaftarSurveiModel::where('id', $id)->update([
            'daftar_kegiatan_survei' => strtoupper($request->input('namaKegiatan')),
            'waktu_mulai' => $request->input('periodeKegiatanAwal'),
            'waktu_berakhir' => $request->input('periodeKegiatanAkhir'),
            'jenis_kegiatan' => $request->input('jenisKegiatan'),
            'jenis_pembayaran' => $request->input('jenisPembayaran'),
            'jumlah_satuan' => $request->input('jumlahSatuan'),
            'nominal_per_satuan' => (float)filter_var($request->input('nominalperSatuan'), FILTER_SANITIZE_NUMBER_FLOAT),
            'total_anggaran' => $request->input('jumlahSatuan') * (float)filter_var($request->input('nominalperSatuan'), FILTER_SANITIZE_NUMBER_FLOAT),
            'periode_pencairan_honor' => $request->input('periodePencairanAnggaran'),
            'kode_beban_anggaran' => $request->input('bebanAnggaranKegiatan'),
        ]);

        Alert::success('Berhasil', 'Data Kegiatan Berhasil Diedit');
        return back();
    }

    public function hapusKegiatanSurveiSensus($id)
    {
        DaftarSurveiModel::where('id', $id)->delete();
        Alert::success('Berhasil', 'Data Kegiatan Berhasil Dihapus');
        return back();
    }

    // Daftar Pengguna
    public function pilihStatusSMPengguna()
    {
        $data = SMStatus::select('jenis_sm')->where('jenis_sm', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }

    public function pilihStatusAdminPengguna()
    {
        $data = AdminStatusModel::select('status_admin')->where('status_admin', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }

    public function editDataPenggunaSeemitra(Request $request, $id)
    {
        User::where('id', $id)->update([
            'name' => $request->input('namaPenggunaSeemitra'),
            'email' => $request->input('emailPenggunaSeemitra'),
            'is_sm' => $request->input('statusSubjectMatter'),
            'is_admin' => $request->input('statusAdmin'),
        ]);

        Alert::success('Berhasil', 'Data pengguna berhasil diperbaharui');
        return back();
    }

    // Status Alokasi Kegiatan
    public function statusAlokasiKegiatanPage()
    {
        $daftar_semua_kegiatan = DaftarSurveiModel::all();
        return view('master_menu.status_alokasi_kegiatan', [
            'semua_kegiatan' => $daftar_semua_kegiatan
        ]);
    }

    public function ubahStatusAlokasiHonorKegiatan(Request $request, $id)
    {
        DaftarSurveiModel::where('id', $id)->update([
            'sudah_dialokasikan_honor' => $request->input('updateStatusAlokasiHonorKegiatan')
        ]);

        Alert::success('Berhasil', 'Status alokasi honor kegiatan berhasil diubah');
        return back();
    }

    // Daftar Mitra
    public function daftarMitraAllPage()
    {
        $id_mitra_ob = RateHonor::where('jenis_pembayaran_mitra', 'Orang Bulan')->get('id_mitra')->toArray();
        $ids_mitra = array();
        foreach ($id_mitra_ob as $field => $value) {
            $ids_mitra[] = $value['id_mitra'];
        }
        // dd($ids_mitra);
        $daftar_mitra = DaftarMitra::all();
        return view('master_menu.daftar_mitra', [
            'semua_mitra' => $daftar_mitra,
            'id_mitra_ob' => $ids_mitra,
        ]);
    }

    public function ambilMitraDenganOB()
    {
        $kegiatan_filter_bulan = DaftarSurveiModel::where('periode_pencairan_honor', 'LIKE', '%' . $_POST['filter_bulan'] . '%')->get('daftar_kegiatan_survei')->toArray();
        $id_mitra_ob = RateHonor::whereIn('kegiatan', $kegiatan_filter_bulan)->where('jenis_pembayaran_mitra', 'Orang Bulan')->get('id_mitra')->toArray();
        $ids_mitra = array();
        foreach ($id_mitra_ob as $field => $value) {
            $ids_mitra[] = $value['id_mitra'];
        }
        // dd($ids_mitra);
        $daftar_mitra = DaftarMitra::all();
        $arr_mitra = [
            $daftar_mitra, $ids_mitra
        ];
        return $arr_mitra;
    }

    public function pilihJenisKegiatanMitra()
    {
        $data = DaftarMitra::select('posisi')->distinct()->where('posisi', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }

    public function pilihJenisKelamin()
    {
        $data = DaftarMitra::select('jenis_kelamin')->distinct()->where('jenis_kelamin', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }

    public function tambahDaftarMitra(Request $request)
    {
        DaftarMitra::create([
            'nama' => ucwords($request->input('namaMitra')),
            'posisi' => $request->input('jenisKegiatanMitra'),
            'alamat_detail' => ucwords($request->input('alamatLengkapMitra')),
            'jenis_kelamin' => $request->input('jenisKelamin'),
            'longitude' => str_replace(",", ".", $request->input('longitudeMitra')),
            'latitude' => str_replace(",", ".", $request->input('latitudeMitra')),
        ]);

        Alert::success('Berhasil', 'Mitra berhasil ditambahkan');
        return back();
    }

    // Daftar SBML
    public function pilihNamaKegiatanSBML()
    {
        $data = DaftarSurveiModel::select('id', 'daftar_kegiatan_survei')->distinct()->where('daftar_kegiatan_survei', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }

    public function pilihJenisKegiatanSBML()
    {
        $data = DaftarSurveiModel::select('jenis_kegiatan')->distinct()->where('jenis_kegiatan', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }

    public function autofill($id = 0)
    {
        $data = DaftarSurveiModel::where('id', $id)->first();
        return response()->json($data);
    }

    public function daftarSBMLPage()
    {
        $daftar_kegiatan_dengan_sbml_yearly = DataSBML::all();
        return view('master_menu.daftar_sbml', [
            'sbml_kegiatan_yearly' => $daftar_kegiatan_dengan_sbml_yearly,
            'arr_sbml_yearly' => $daftar_kegiatan_dengan_sbml_yearly->toArray(),
        ]);
    }

    public function tambahDataSBML(Request $request)
    {
        DataSBML::create([
            'jenis_kegiatan' => $request->input('jenisKegiatanSBML'),
            'periode_sbml' => $request->input('periodeSBML'),
            'nominal_sbml' => (float)filter_var($request->input('nominalSBML'), FILTER_SANITIZE_NUMBER_FLOAT),
        ]);

        Alert::success('Berhasil', 'SBML berhasil ditetapkan');
        return back();
    }
}
