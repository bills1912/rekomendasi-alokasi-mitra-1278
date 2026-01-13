<?php

namespace App\Http\Controllers;

use App\Models\AlokasiMitraSurvei;
use Illuminate\Support\Facades\Session;
use App\Models\DaftarMitra;
use App\Models\DaftarSurveiModel;
use App\Models\DistrictModel;
use App\Models\GusitBSModel;
use App\Models\RateHonor;
use App\Models\RegencyModel;
use App\Models\User;
use App\Models\VillageModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
// use Input;

class IPDSProjectController extends Controller
{
    // Core

    public function index()
    {
        $data_mitra_total = DaftarMitra::all();
        if (Auth::check() && Auth::user()->is_sm) {
            return view('map_table', [
                'data_mitra_total' => $data_mitra_total
            ]);
        } else {
            return view('mitra_view.container.mitra_index');
        }
    }

    // Admin Side

    public function alokasiMitra(Request $request)
    {
        return view('alokasi.alokasi-mitra-survei', [
            'test' => $request->file('file'),
        ]);
    }

    public function daftarMitraTeralokasi()
    {
        $jenis_pembayaran_utama = DaftarSurveiModel::where('jenis_pembayaran', '!=', 'Orang Bulan')->distinct()->get('jenis_pembayaran')->toArray();
        $jenis_pembayaran_lain = ['Orang Bulan'];
        $arr_jenis_pembayaran_utama = array();
        foreach ($jenis_pembayaran_utama  as $field => $value) {
            $arr_jenis_pembayaran_utama[] = $value['jenis_pembayaran'];
        }
        if (in_array(DaftarSurveiModel::where('daftar_kegiatan_survei', strtoupper(Session::get('sample')['nama_kegiatan_survei']))->first()->jenis_pembayaran, $arr_jenis_pembayaran_utama)) {
            $data_mitra_terpilih = AlokasiMitraSurvei::where('kegiatan_survei', '=', strtoupper(Session::get('sample')['nama_kegiatan_survei']))
                ->groupBy('id')->selectRaw('count(idbs_teralokasi) as total_bs, id')
                ->pluck('total_bs', 'id');

            $id_mitra_teralokasi = [];
            foreach ($data_mitra_terpilih as $field => $value) {
                $id_mitra_teralokasi[] = $field;
            }
        } else if (in_array(DaftarSurveiModel::where('daftar_kegiatan_survei', strtoupper(Session::get('sample')['nama_kegiatan_survei']))->first()->jenis_pembayaran, $jenis_pembayaran_lain)) {
            $data_mitra_terpilih = RateHonor::where('kegiatan', '=', strtoupper(Session::get('sample')['nama_kegiatan_survei']))->get('id_mitra');

            $id_mitra_teralokasi = [];
            foreach ($data_mitra_terpilih as $field => $value) {
                $id_mitra_teralokasi[] = $value->id_mitra;
            }
        }
        // dd($id_mitra_teralokasi);
        $daftar_mitra_teralokasi = DaftarMitra::whereIn('id', $id_mitra_teralokasi)->get();
        $idbs_mitra_terpilih = AlokasiMitraSurvei::where('kegiatan_survei', '=', strtoupper(Session::get('sample')['nama_kegiatan_survei']))->get();

        $daftar_mitra = [];
        foreach ($idbs_mitra_terpilih as $data => $value) {
            $daftar_mitra[$value['id']][] = $value->idbs_teralokasi;
        }

        $kelompok_mitra_teralokasi = [];
        foreach ($daftar_mitra as $data => $arr) {
            $kelompok_mitra_teralokasi[$data] = implode(";", $arr);
        }

        return view('alokasi.daftar-mitra-survei-teralokasi', [
            'list_mitra_terpilih' => $daftar_mitra_teralokasi,
            'kegiatan_mitra_terpilih' => strtoupper(Session::get('sample')['nama_kegiatan_survei']),
            'daftar_bs_teralokasi' => $kelompok_mitra_teralokasi
        ]);
    }

    public function ambilMitraTeralokasiPerKegiatan()
    {
        if ((DaftarSurveiModel::where('daftar_kegiatan_survei', $_POST['filter_kegiatan'])->first()->jenis_pembayaran == 'Dokumen') ||
            (DaftarSurveiModel::where('daftar_kegiatan_survei', $_POST['filter_kegiatan'])->first()->jenis_pembayaran == 'Blok Sensus')
        ) {
            $data_mitra_terpilih = AlokasiMitraSurvei::where('kegiatan_survei', '=', $_POST['filter_kegiatan'])
                ->groupBy('id')->selectRaw('count(idbs_teralokasi) as total_bs, id')
                ->pluck('total_bs', 'id');

            $id_mitra_teralokasi = [];
            foreach ($data_mitra_terpilih as $field => $value) {
                $id_mitra_teralokasi[] = $field;
            }
        } else if ((DaftarSurveiModel::where('daftar_kegiatan_survei', $_POST['filter_kegiatan'])->first()->jenis_pembayaran == 'Orang Bulan') ||
            (DaftarSurveiModel::where('daftar_kegiatan_survei', $_POST['filter_kegiatan'])->first()->jenis_pembayaran == 'Segmen')
        ) {
            $data_mitra_terpilih = RateHonor::where('kegiatan', '=', $_POST['filter_kegiatan'])->get('id_mitra');

            $id_mitra_teralokasi = [];
            foreach ($data_mitra_terpilih as $field => $value) {
                $id_mitra_teralokasi[] = $value->id_mitra;
            }
        }

        $daftar_mitra_teralokasi = DaftarMitra::whereIn('id', $id_mitra_teralokasi)->get();
        $idbs_mitra_terpilih = AlokasiMitraSurvei::where('kegiatan_survei', '=', $_POST['filter_kegiatan'])->get();

        $daftar_mitra = [];
        foreach ($idbs_mitra_terpilih as $data => $value) {
            $daftar_mitra[$value['id']][] = $value->idbs_teralokasi;
        }

        $kelompok_mitra_teralokasi = [];
        foreach ($daftar_mitra as $data => $arr) {
            $kelompok_mitra_teralokasi[$data] = $arr;
        }

        $arr_all_teralokasi = [
            $daftar_mitra_teralokasi, $kelompok_mitra_teralokasi, $_POST['filter_kegiatan']
        ];

        return $arr_all_teralokasi;
    }

    public function uploadSampleBlokSensus()
    {
        // dd($_COOKIE['ids']);
        return view('alokasi.upload-sample-bs');
    }

    public function ulangiUploadSample()
    {
        Session::forget('sample');
        Alert::success('Berhasil', 'Reset upload sampel survei berhasil');
        return redirect('/upload_sample_bs');
    }

    public function ambilSampelWilkerstat(Request $request)
    {
        $kegiatan_filter_bulan = DaftarSurveiModel::where('waktu_mulai', 'LIKE', '%' . Carbon::now()->translatedFormat('F') . '%')
            ->orWhere('waktu_mulai', 'LIKE', '%' . $_COOKIE['bulan-alokasi'] . '%')->get('daftar_kegiatan_survei')->toArray();
        if (!isset($_COOKIE['tanpa-bs'])) {
            $data = Excel::toArray([], $request->file('unggahSample'));
            foreach (array_slice($data[0], 1) as $id) {
                $insert_data[] = $id[0];
            };
        }

        if (!isset($_COOKIE['ids'])) {
            $data_mitra = Excel::toArray([], $request->file('unggahMitraTerpilih'));
            foreach (array_slice($data_mitra[0], 1) as $id) {
                $insert_data_mitra[] = $id[1];
            };
        }

        $test3 = AlokasiMitraSurvei::where('kegiatan_survei', '=', strtoupper($request->input('initializeSurvei')))->get('idbs_teralokasi')->toArray();
        $arr_test3 = [];
        foreach ($test3 as $field => $value) {
            $arr_test3[] = $value['idbs_teralokasi'];
        }

        if (!DaftarSurveiModel::where('daftar_kegiatan_survei', strtoupper($request->input('initializeSurvei')))->exists()) {
            DaftarSurveiModel::create([
                'daftar_kegiatan_survei' => strtoupper($request->input('initializeSurvei'))
            ]);
        }

        if (!isset($_COOKIE['ids'])) {
            Session::put('sample', [
                'id_bs_sample' => $insert_data,
                'mitra_sample' => $insert_data_mitra,
                'bs_sudah_teralokasi' => AlokasiMitraSurvei::where('kegiatan_survei', '=', strtoupper($request->input('initializeSurvei')))->get('idbs_teralokasi'),
                'nama_kegiatan_survei' => strtoupper($request->input('initializeSurvei')),
                'id_kegiatan_survei' => DaftarSurveiModel::where('daftar_kegiatan_survei', strtoupper($request->input('initializeSurvei')))->first()->id,
                'status_honor_kegiatan_survei' => DaftarSurveiModel::where('daftar_kegiatan_survei', strtoupper($request->input('initializeSurvei')))->first()->sudah_dialokasikan_honor,
                'total_honor_dialokasikan' => RateHonor::whereIn('kegiatan', $kegiatan_filter_bulan)->groupBy('id_mitra')->selectRaw('sum(honor) as total_honor, id_mitra')->pluck('total_honor', 'id_mitra'),
                'total_petugas_seharusnya' => DaftarSurveiModel::where('daftar_kegiatan_survei', strtoupper($request->input('initializeSurvei')))->first()->jumlah_petugas_kegiatan,
            ]);
        } else if (!isset($_COOKIE['tanpa-bs'])) {
            Session::put('sample', [
                'id_bs_sample' => $insert_data,
                'bs_sudah_teralokasi' => $arr_test3,
                'nama_kegiatan_survei' => strtoupper($request->input('initializeSurvei')),
                'wilkerstat_kegiatan_survei' => strtoupper($request->input('jenisWilkerstat')),
                'id_kegiatan_survei' => DaftarSurveiModel::where('daftar_kegiatan_survei', strtoupper($request->input('initializeSurvei')))->first()->id,
                'status_honor_kegiatan_survei' => DaftarSurveiModel::where('daftar_kegiatan_survei', strtoupper($request->input('initializeSurvei')))->first()->sudah_dialokasikan_honor,
                'total_honor_dialokasikan' => RateHonor::whereIn('kegiatan', $kegiatan_filter_bulan)->groupBy('id_mitra')->selectRaw('sum(honor) as total_honor, id_mitra')->pluck('total_honor', 'id_mitra'),
                'total_petugas_seharusnya' => DaftarSurveiModel::where('daftar_kegiatan_survei', strtoupper($request->input('initializeSurvei')))->first()->jumlah_petugas_kegiatan,
            ]);
        } else {
            Session::put('sample', [
                'nama_kegiatan_survei' => strtoupper($request->input('initializeSurvei')),
                'id_kegiatan_survei' => DaftarSurveiModel::where('daftar_kegiatan_survei', strtoupper($request->input('initializeSurvei')))->first()->id,
                'status_honor_kegiatan_survei' => DaftarSurveiModel::where('daftar_kegiatan_survei', strtoupper($request->input('initializeSurvei')))->first()->sudah_dialokasikan_honor,
                'total_honor_dialokasikan' => RateHonor::whereIn('kegiatan', $kegiatan_filter_bulan)->groupBy('id_mitra')->selectRaw('sum(honor) as total_honor, id_mitra')->pluck('total_honor', 'id_mitra'),
                'total_petugas_seharusnya' => DaftarSurveiModel::where('daftar_kegiatan_survei', strtoupper($request->input('initializeSurvei')))->first()->jumlah_petugas_kegiatan,
            ]);
        }
        // dd(Session::get('sample'));
        if (!isset($_COOKIE['tanpa-bs'])) {
            Alert::success('Selamat', 'Sample blok sensus dan data mitra terpilih sudah berhasil diunggah');
            return redirect()->route('alokasi_mitra.id_sls');
        } else {
            Alert::success('Berhasil', 'Mitra dan kegiatan berhasil dipilih');
            return redirect()->to('/rate_honor');
        }
    }

    public function pilihKako()
    {
        $ids_sample_kako = Session::get('sample')['id_bs_sample'];
        $id_kako_unique = array_unique(array_map(function ($s) {
            return substr($s, 0, 4);
        }, $ids_sample_kako));
        $data = RegencyModel::where('id', $id_kako_unique)->where('name', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }
    public function pilihKec($id_kec)
    {
        $ids_sample_kec = Session::get('sample')['id_bs_sample'];
        $id_kec_unique = array_unique(array_map(function ($s) {
            return substr($s, 0, 7);
        }, $ids_sample_kec));
        $data = DistrictModel::where('regency_id', $id_kec)->whereIn('id', $id_kec_unique)->where('name', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }
    public function pilihDesa($id_desa)
    {
        $ids_sample_desa = Session::get('sample')['id_bs_sample'];
        $id_desa_unique = array_unique(array_map(function ($s) {
            return substr($s, 0, 10);
        }, $ids_sample_desa));
        $data = VillageModel::where('district_id', $id_desa)->whereIn('id', $id_desa_unique)->where('name', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }
    public function pilihBS($id_bs)
    {
        $ids_sample_bs = Session::get('sample')['id_bs_sample'];
        $data = GusitBSModel::whereIn(trim('idbs'), $ids_sample_bs)->where(trim('iddesa'), '=', $id_bs)->where('nmsls', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }

    public function inisialisasiKegiatanSurvei()
    {
        $data = DaftarSurveiModel::where('daftar_kegiatan_survei', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }

    public function ambilIDBSMitra()
    {
        $kegiatan_filter_bulan = DaftarSurveiModel::where('waktu_mulai', 'LIKE', '%' . $_COOKIE['bulan-alokasi'] . '%')->get('daftar_kegiatan_survei')->toArray();
        $tresh = AlokasiMitraSurvei::where('kegiatan_survei', '=', strtoupper(Session::get('sample')['nama_kegiatan_survei']))
            ->groupBy('id')->selectRaw('count(idbs_teralokasi) as total_bs, id')
            ->pluck('total_bs', 'id');
        $ob_tresh = RateHonor::whereIn('kegiatan', $kegiatan_filter_bulan)->where('jenis_pembayaran_mitra', 'Orang Bulan')->get('id_mitra')->toArray();
        $daftar_mitra_maks = [];
        foreach ($tresh as $field => $value) {
            if ($value >= 2) {
                $daftar_mitra_maks[] = $field;
            }
        }
        $selected_mitra = DaftarMitra::whereIn("id", array_map('intval', json_decode($_COOKIE['ids'])))
            ->where(function ($query) use ($daftar_mitra_maks, $ob_tresh) {
                $query->whereNotIn('id', $ob_tresh);
                // $query->whereNotIn('id', $daftar_mitra_maks);
            })
            ->where("id_kec", '=', $_POST['id_kec'])
            ->get();
        return $selected_mitra;
    }

    public function pengalokasianMitraSurvei($id)
    {
        $kegiatan_filter_bulan = DaftarSurveiModel::where('waktu_mulai', 'LIKE', '%' . Carbon::now()->translatedFormat('F') . '%')
            ->orWhere('waktu_mulai', 'LIKE', '%' . $_COOKIE['bulan-alokasi'] . '%')->get('daftar_kegiatan_survei')->toArray();
        $test3 = AlokasiMitraSurvei::where('kegiatan_survei', '=', strtoupper(Session::get('sample')['nama_kegiatan_survei']))->get('idbs_teralokasi')->toArray();
        $arr_test3 = [];
        foreach ($test3 as $field => $value) {
            $arr_test3[] = $value['idbs_teralokasi'];
        }
        $mitraDialokasikan = DaftarMitra::where('id', $id)->get();
        foreach ($mitraDialokasikan as $data => $value) {
            AlokasiMitraSurvei::create([
                'id' => $value->id,
                'nama_mitra' => $value->nama,
                'alamat_mitra' => $value->alamat_detail,
                'kegiatan_survei' => strtoupper(Session::get('sample')['nama_kegiatan_survei']),
                'idbs_teralokasi' => $_COOKIE['bs-terpilih'],
            ]);
        }
        array_push($arr_test3, $_COOKIE['bs-terpilih']);
        Session::put('sample', [
            'id_bs_sample' => Session::get('sample')['id_bs_sample'],
            'bs_sudah_teralokasi' => $arr_test3,
            'nama_kegiatan_survei' => strtoupper(Session::get('sample')['nama_kegiatan_survei']),
            'wilkerstat_kegiatan_survei' => strtoupper(Session::get('sample')['wilkerstat_kegiatan_survei']),
            'id_kegiatan_survei' => DaftarSurveiModel::where('daftar_kegiatan_survei', strtoupper(Session::get('sample')['nama_kegiatan_survei']))->first()->id,
            'status_honor_kegiatan_survei' => DaftarSurveiModel::where('daftar_kegiatan_survei', strtoupper(Session::get('sample')['nama_kegiatan_survei']))->first()->sudah_dialokasikan_honor,
            'total_honor_dialokasikan' => RateHonor::whereIn('kegiatan', $kegiatan_filter_bulan)->groupBy('id_mitra')->selectRaw('sum(honor) as total_honor, id_mitra')->pluck('total_honor', 'id_mitra'),
            'total_petugas_seharusnya' => DaftarSurveiModel::where('daftar_kegiatan_survei', strtoupper(Session::get('sample')['nama_kegiatan_survei']))->first()->jumlah_petugas_kegiatan,
        ]);
        Alert::success('Berhasil', 'Mitra berhasil dialokasikan pada blok sensus');
        return redirect('/alokasi_mitra_survei');
    }

    public function editDataMitraSurveiTeralokasi(Request $request, $id)
    {
        AlokasiMitraSurvei::where('super_id', $id)
            ->update([
                'nama_mitra' => $request->input('namaMitra'),
                'alamat_mitra' => $request->input('alamatMitra'),
                'kegiatan_survei' => $request->input('kegiatanSurvei'),
                'idbs_teralokasi' => $request->input('idbsTeralokasi'),
            ]);

        Alert::success('Berhasil', 'Data mitra berhasil diperbaharui');
        return redirect('/list_mitra_survei_teralokasi');
    }

    public function hapusDataMitraSurveiTeralokasi($id)
    {
        $kegiatan_filter_bulan = DaftarSurveiModel::where('waktu_mulai', 'LIKE', '%' . Carbon::now()->translatedFormat('F') . '%')
            ->orWhere('waktu_mulai', 'LIKE', '%' . $_COOKIE['bulan-alokasi'] . '%')->get('daftar_kegiatan_survei')->toArray();
        $test3 = AlokasiMitraSurvei::where('kegiatan_survei', '=', strtoupper(Session::get('sample')['nama_kegiatan_survei']))->get('idbs_teralokasi')->toArray();
        $arr_test3 = [];
        foreach ($test3 as $field => $value) {
            $arr_test3[] = $value['idbs_teralokasi'];
        }
        $delete_idbs = AlokasiMitraSurvei::where('id', $id)->where('kegiatan_survei', '=', strtoupper(Session::get('sample')['nama_kegiatan_survei']))->get('idbs_teralokasi')->toArray();
        $daftar_deleted_idbs = array();
        foreach ($delete_idbs as $field => $value) {
            $daftar_deleted_idbs[] = $value['idbs_teralokasi'];
        }
        $final_arr_test3 = array_diff($arr_test3, $daftar_deleted_idbs);
        AlokasiMitraSurvei::where('id', $id)->where('kegiatan_survei', '=', strtoupper(Session::get('sample')['nama_kegiatan_survei']))->delete();
        RateHonor::where('id_mitra', $id)->where('kegiatan', '=', strtoupper(Session::get('sample')['nama_kegiatan_survei']))->delete();

        Session::put('sample', [
            'id_bs_sample' => Session::get('sample')['id_bs_sample'],
            'bs_sudah_teralokasi' => $final_arr_test3,
            'nama_kegiatan_survei' => strtoupper(Session::get('sample')['nama_kegiatan_survei']),
            'id_kegiatan_survei' => DaftarSurveiModel::where('daftar_kegiatan_survei', strtoupper(Session::get('sample')['nama_kegiatan_survei']))->first()->id,
            'status_honor_kegiatan_survei' => DaftarSurveiModel::where('daftar_kegiatan_survei', strtoupper(Session::get('sample')['nama_kegiatan_survei']))->first()->sudah_dialokasikan_honor,
            'total_honor_dialokasikan' => RateHonor::whereIn('kegiatan', $kegiatan_filter_bulan)->groupBy('id_mitra')->selectRaw('sum(honor) as total_honor, id_mitra')->pluck('total_honor', 'id_mitra'),
            'total_petugas_seharusnya' => DaftarSurveiModel::where('daftar_kegiatan_survei', strtoupper(Session::get('sample')['nama_kegiatan_survei']))->first()->jumlah_petugas_kegiatan,
        ]);
        Alert::success('Berhasil', 'Data Berhasil Dihapus');
        return redirect('/list_mitra_survei_teralokasi');
    }

    // Mitra Side

    public function daftarKegiatanMitra()
    {
        return view('mitra_view.container.mitra_daftar_kegiatan');
    }

    // Login with Google
    public function googleLoginPage()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleLoginAuthentication()
    {
        try {
            $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id', $user->id)->orWhere('email', $user->email)->first();
            if ($finduser) {
                Auth::login($finduser);
                $data_mitra_total = DaftarMitra::all();
                return view('map_table', [
                    'data_mitra_total' => $data_mitra_total
                ]);
            } else {
                $newUser = User::create([
                    'name' => ucwords($user->name),
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => encrypt('123456789')
                ]);
                Auth::login($newUser);
                return view('mitra_view.container.mitra_index');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function facebookLoginPage()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookLoginAuthentication()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $finduser = User::where('facebook_id', $user->id)->orWhere('email', $user->email)->first();
            if ($finduser) {
                Auth::login($finduser);
                $data_mitra_total = DaftarMitra::all();
                return view('map_table', [
                    'data_mitra_total' => $data_mitra_total
                ]);
            } else {
                $newUser = User::updateOrCreate(['email' => $user->email], [
                    'name' => ucwords($user->name),
                    'facebook_id' => $user->id,
                    'password' => encrypt('123456789')
                ]);
                Auth::login($newUser);
                return view('mitra_view.container.mitra_index');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function redirectPilihMitra()
    {
        Alert::toast('<strong>Pilih mitra terlebih dahulu!</strong>', 'warning');
        return redirect()->to('/daftar_mitra');
    }

    public function hapusCookies()
    {
        Session::forget('sample');
        unset($_COOKIE['ids']);
        unset($_COOKIE['tanpa-bs']);
        setcookie('ids', '', -1, '/');
        setcookie('tanpa-bs', '', -1, '/');

        return redirect()->to('/daftar_mitra');
    }
}
