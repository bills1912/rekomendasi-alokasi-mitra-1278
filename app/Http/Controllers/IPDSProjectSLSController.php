<?php

namespace App\Http\Controllers;

use App\Models\DistrictModel;
use App\Models\GusitSLSModel;
use App\Models\ProvinceModel;
use App\Models\RegencyModel;
use App\Models\VillageModel;
use Illuminate\Http\Request;
use App\Models\DaftarMitra;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class IPDSProjectSLSController extends Controller
{
    public function alokasiMitraSensus()
    {
        return view('alokasi.alokasi-mitra-sensus');
    }

    public function uploadMitraSensusTerpilih()
    {
        return view('alokasi.upload-mitra-terpilih');
    }

    public function ambilMitraSensusTerpilih(Request $request)
    {
        $data_wilayah = Excel::toArray([], $request->file('mitraSensusTerpilih'));
        foreach (array_slice($data_wilayah[0], 1) as $id) {
            $insert_data_wilayah[] = $id[1];
        };
        $data_mitra = Excel::toArray([], $request->file('mitraSensusTerpilih'));
        foreach (array_slice($data_mitra[0], 1) as $id) {
            $insert_data_mitra[] = $id[0];
        };
        Session::put('mitra_sensus', ['identitas_wilayah' => $insert_data_wilayah, 'mitra_terpilih' => $insert_data_mitra]);
        Alert::success('Selamat', 'Data mitra terpilih sudah berhasil diunggah');
        return redirect()->route('mitra_sensus');
    }

    public function pilihProvinsiSensus()
    {
        $ids_sample_prov = Session::get('mitra_sensus')['identitas_wilayah'];
        $id_prov_unique = array_unique(array_map(function ($s)
        {
            return substr($s, 0, 2);
        }, $ids_sample_prov));
        $data = ProvinceModel::where('id', $id_prov_unique)->where('name', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }

    public function pilihKakoSensus($id_kako)
    {
        $ids_sample_kako = Session::get('mitra_sensus')['identitas_wilayah'];
        $id_kako_unique = array_unique(array_map(function ($s)
        {
            return substr($s, 0, 4);
        }, $ids_sample_kako));
        $data = RegencyModel::where('province_id', $id_kako)->where('id', $id_kako_unique)->where('name', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }
    public function pilihKecSensus($id_kec)
    {
        $data = DistrictModel::where('regency_id', $id_kec)->where('name', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }
    public function pilihDesaSensus($id_desa)
    {
        $data = VillageModel::where('district_id', $id_desa)->where('name', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }

    public function pilihSLS($id_sls)
    {
        $data = GusitSLSModel::where(trim('iddesa'), $id_sls)->where('nmsls', 'LIKE', '%' . request('q') . '%')->paginate(10);
        return response()->json($data);
    }

    public function ambilIDSLSMitra(Request $request)
    {
        $wilayah_mitra_terpilih = Session::get('mitra_sensus')['identitas_wilayah'];
        $id_desa = $request->input('selectVillageSensus');
        $selected_mitra = DaftarMitra::whereIn('id_desa_mitra', $wilayah_mitra_terpilih)->where("id_desa_mitra", $id_desa)->get();
        return $selected_mitra;
    }
}
