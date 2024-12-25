<?php

namespace App\Http\Controllers;

date_default_timezone_set('Asia/Jakarta');
setlocale(LC_TIME, 'id_ID.utf8');

use App\Models\DaftarMitra;
use App\Models\DaftarSurveiModel;
use App\Models\RateHonor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class KeuanganAlokasiMitraController extends Controller
{
    public function tabelRingkasanHonorPage()
    {
        Carbon::setLocale('id');
        $kegiatan_diikuti = RateHonor::all();
        $semua_kegiatan = DaftarSurveiModel::all();
        $daftar_kegiatan = [];
        foreach ($kegiatan_diikuti as $data => $value) {
            $daftar_kegiatan[$value['id_mitra']][] = $value->kegiatan . " (" . $value->volume_pembayaran_mitra . " " . $value->jenis_pembayaran_mitra . " x Rp" . number_format($value->honor/$value->volume_pembayaran_mitra, 0, ",", ".") . ",-),";
        }

        $kelompok_kegiatan = [];
        foreach ($daftar_kegiatan as $data => $arr) {
            $kelompok_kegiatan[$data] = implode(";", $arr);
        }

        $arr_semua_kegiatan = array();
        foreach($semua_kegiatan as $field=>$value) {
            $arr_semua_kegiatan[$value->daftar_kegiatan_survei] = [$value->waktu_mulai, $value->waktu_berakhir];
        }
        // dd($arr_semua_kegiatan);
        $daftar_mitra = DaftarMitra::all();
        $honor_mitra = RateHonor::groupBy('id_mitra')->selectRaw('sum(honor) as total_honor, id_mitra')->pluck('total_honor', 'id_mitra')->toArray();
        return view('keuangan.ringkasan_honor_mitra', [
            'semua_mitra' => $daftar_mitra,
            'total_honor_mitra' => $honor_mitra,
            'kegiatan_diikuti' => $kelompok_kegiatan,
            'bulan_spk' => Carbon::now()->startOfMonth()->translatedFormat('F'),
            'bulan_bast' => Carbon::now()->subMonthsNoOverflow()->endOfMonth()->translatedFormat('F'),
            'daftar_semua_kegiatan' => $arr_semua_kegiatan,
        ]);
    }

    public function filterRateHonorPerBulan()
    {
        $kegiatan_filter_bulan = DaftarSurveiModel::where('periode_pencairan_honor', 'LIKE', '%' . $_POST['filter_bulan'] . '%')->get('daftar_kegiatan_survei')->toArray();
        $id_mitra_filter_bulan = RateHonor::whereIn('kegiatan', $kegiatan_filter_bulan)->get('id_mitra')->toArray();
        $jenis_kegiatan_mitra_filter = DaftarMitra::whereIn('id', $id_mitra_filter_bulan)->get();
        $arr_jenis_kegiatan_mitra_filter = array();
        foreach ($jenis_kegiatan_mitra_filter as $field => $value) {
            $arr_jenis_kegiatan_mitra_filter[$value->id] = $value->posisi;
        }
        $kegiatan_diikuti = RateHonor::all();
        $daftar_kegiatan = [];
        foreach ($kegiatan_diikuti as $data => $value) {
            $daftar_kegiatan[$value['id_mitra']][] = $value->kegiatan . " (" . $value->volume_pembayaran_mitra . " " . $value->jenis_pembayaran_mitra . " x Rp" . number_format($value->honor/$value->volume_pembayaran_mitra, 0, ",", ".") . ",-), ";
        }
        $kelompok_kegiatan = [];
        foreach ($daftar_kegiatan as $data => $arr) {
            $kelompok_kegiatan[$data] = $arr;
        }

        if (isset($_POST['filter_bulan'])) {
            $daftar_mitra = DaftarMitra::whereIn('id', $id_mitra_filter_bulan)->get();
        } else {
            $daftar_mitra = DaftarMitra::all();
        }
        $honor_mitra = RateHonor::groupBy('id_mitra')->selectRaw('sum(honor) as total_honor, id_mitra')->pluck('total_honor', 'id_mitra')->toArray();

        $arr_rekap_mitra = [
            $kelompok_kegiatan, $daftar_mitra, $honor_mitra, $arr_jenis_kegiatan_mitra_filter
        ];

        return $arr_rekap_mitra;
    }

    public function filterRateHonorPerKegiatan()
    {
        // $kegiatan_filter_bulan = DaftarSurveiModel::where('waktu_mulai', 'LIKE', '%' . $_POST['filter_bulan'] . '%')->get('daftar_kegiatan_survei')->toArray();
        // $id_mitra_filter_kegiatan = RateHonor::where('kegiatan', $_POST['filter_kegiatan_rekap_honor'])->get('id_mitra')->toArray();
        // $id_mitra_filter_bulan = RateHonor::whereIn('kegiatan', $kegiatan_filter_bulan)->get('id_mitra')->toArray();
        // $jenis_kegiatan_mitra_filter = DaftarMitra::whereIn('id', $id_mitra_filter_bulan)->get();
        // $arr_jenis_kegiatan_mitra_filter = array();
        // foreach ($jenis_kegiatan_mitra_filter as $field=>$value) {
        //     $arr_jenis_kegiatan_mitra_filter[$value->id] = $value->posisi;
        // }
        // $kegiatan_diikuti = RateHonor::all();
        // $daftar_kegiatan = [];
        // foreach ($kegiatan_diikuti as $data => $value) {
        //     $daftar_kegiatan[$value['id_mitra']][] = $value->kegiatan . " (Rp" . number_format($value->honor, 0, ",", ".") . ",-)";
        // }
        // $kelompok_kegiatan = [];
        // foreach ($daftar_kegiatan as $data => $arr) {
        //     $kelompok_kegiatan[$data] = $arr;
        // }

        // if (isset($_POST['filter_bulan'])) {
        //     $daftar_mitra = DaftarMitra::whereIn('id', $id_mitra_filter_bulan)->get();
        // } else {
        //     $daftar_mitra = DaftarMitra::all();
        // }
        // $honor_mitra = RateHonor::groupBy('id_mitra')->selectRaw('sum(honor) as total_honor, id_mitra')->pluck('total_honor', 'id_mitra')->toArray();

        // $arr_rekap_mitra = [
        //     $kelompok_kegiatan, $daftar_mitra, $honor_mitra, $arr_jenis_kegiatan_mitra_filter
        // ];

        return $_POST['filter_kegiatan_rekap_honor'];
    }
}
