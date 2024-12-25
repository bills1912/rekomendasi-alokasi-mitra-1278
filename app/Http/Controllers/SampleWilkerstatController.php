<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\GusitBSModel;

class SampleWilkerstatController extends Controller
{
    public function ambilSampelWilkerstat(Request $request)
    {
        // $this->validate($request, [
        //     'unggahSample'  => 'required|mimes:xls,xlsx'
        // ]);
        if ($request->has("submit-sample-wilkerstat")) {
            $data = Excel::toArray([], $request->file('unggahSample'));
            foreach (array_slice($data[0], 1) as $id) {
                $insert_data[] = $id[0];
            }
            Session::put('id_bs_sample', $insert_data);
            // $match = GusitBSModel::whereIn(trim('idbs'), $insert_data)->get();
            // dd($match);
            // return redirect()->route('sample_wilkerstat');
        }
    }
}
