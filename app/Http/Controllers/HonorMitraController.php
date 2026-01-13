<?php

namespace App\Http\Controllers;

date_default_timezone_set('Asia/Jakarta');
setlocale(LC_TIME, 'id_ID.utf8');
ini_set('max_execution_time', 3600); // 3600 seconds = 60 minutes
set_time_limit(3600);

use App\Models\AlokasiMitraSurvei;
use App\Models\DaftarMitra;
use App\Models\DaftarSurveiModel;
use App\Models\RateHonor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use NumberToWords\NumberToWords;
use PhpOffice\PhpWord\TemplateProcessor;
use RealRashid\SweetAlert\Facades\Alert;
use NumberFormatter;

class HonorMitraController extends Controller
{
    public function tabelRateHonor()
    {
        // dd($_COOKIE['ids']);
        $kegiatan_filter_bulan = DaftarSurveiModel::where('waktu_mulai', 'LIKE', '%' . Carbon::now()->translatedFormat('F') . '%')
            ->orWhere('waktu_mulai', 'LIKE', '%' . $_COOKIE['bulan-alokasi'] . '%')->get('daftar_kegiatan_survei')->toArray();
        $jenis_pembayaran_lain = ['Orang Bulan'];
        $tresh = RateHonor::whereIn('kegiatan', $kegiatan_filter_bulan)->whereIn('jenis_pembayaran_mitra', $jenis_pembayaran_lain)->get('id_mitra')->toArray();
        Carbon::setLocale('id');
        if (isset(Session::get('sample')['id_bs_sample'])) {
            $honor_mitra = RateHonor::groupBy('id_mitra')->selectRaw('sum(honor) as total_honor, id_mitra')->pluck('total_honor', 'id_mitra');
            $data_mitra_terpilih = AlokasiMitraSurvei::where('kegiatan_survei', '=', strtoupper(Session::get('sample')['nama_kegiatan_survei']))
                ->groupBy('id')->selectRaw('count(idbs_teralokasi) as total_bs, id')
                ->pluck('total_bs', 'id');

            $id_mitra_teralokasi = [];
            $total_volume_teralokasi = [];
            foreach ($data_mitra_terpilih as $field => $value) {
                $id_mitra_teralokasi[] = $field;
                $total_volume_teralokasi[$field] = $value;
            }
            if (isset($tresh)) {
                $mitra_teralokasi = DaftarMitra::whereIn('id', $id_mitra_teralokasi)->whereNotIn('id', $tresh)->get();
            } else {
                $mitra_teralokasi = DaftarMitra::whereIn('id', $id_mitra_teralokasi)->get();
            }
            $mitra_teralokasi_honor = RateHonor::where('kegiatan', strtoupper(Session::get('sample')['nama_kegiatan_survei']))->get();
            return view('rate_honor.tabel_rate_honor', [
                'mitra_teralokasi' => $mitra_teralokasi,
                'volume_pengalokasian' => $total_volume_teralokasi,
                'mitra_honor' => $mitra_teralokasi_honor,
                'total_honor_mitra' => $honor_mitra,
                'jenis_pembayaran_kegiatan' => DaftarSurveiModel::where('daftar_kegiatan_survei', Session::get('sample')['nama_kegiatan_survei'])->first()->jenis_pembayaran,
                'harga_satuan_kegiatan' => DaftarSurveiModel::where('daftar_kegiatan_survei', Session::get('sample')['nama_kegiatan_survei'])->first()->nominal_per_satuan,
                'status_honor_kegiatan' => DaftarSurveiModel::where('daftar_kegiatan_survei', Session::get('sample')['nama_kegiatan_survei'])->first()->sudah_dialokasikan_honor,
                'jenis_kegiatan_survei' => DaftarSurveiModel::where('daftar_kegiatan_survei', Session::get('sample')['nama_kegiatan_survei'])->first()->jenis_kegiatan,
                'jenis_survei_diikuti' => DaftarSurveiModel::where('daftar_kegiatan_survei', Session::get('sample')['nama_kegiatan_survei'])->first()->daftar_kegiatan_survei,
            ]);
        } else {
            $honor_mitra = RateHonor::groupBy('id_mitra')->selectRaw('sum(honor) as total_honor, id_mitra')->pluck('total_honor', 'id_mitra');
            $mitra_teralokasi = DaftarMitra::whereIn('id', json_decode($_COOKIE['ids']))->whereNotIn('id', $tresh)->get();
            // dd($mitra_teralokasi);
            $mitra_teralokasi_honor = RateHonor::whereIn('id_mitra', json_decode($_COOKIE['ids']))->get();
            return view('rate_honor.tabel_rate_honor', [
                'mitra_teralokasi' => $mitra_teralokasi,
                'mitra_honor' => $mitra_teralokasi_honor,
                'total_honor_mitra' => $honor_mitra,
                'jenis_pembayaran_kegiatan' => DaftarSurveiModel::where('daftar_kegiatan_survei', Session::get('sample')['nama_kegiatan_survei'])->first()->jenis_pembayaran,
                'harga_satuan_kegiatan_pendataan' => DaftarSurveiModel::where('daftar_kegiatan_survei', Session::get('sample')['nama_kegiatan_survei'])->first()->nominal_per_satuan,
                'harga_satuan_kegiatan_pengolahan' => DaftarSurveiModel::where('daftar_kegiatan_survei', Session::get('sample')['nama_kegiatan_survei'])->first()->nominal_per_satuan_pengolahan,
                'harga_satuan_kegiatan_pml' => DaftarSurveiModel::where('daftar_kegiatan_survei', Session::get('sample')['nama_kegiatan_survei'])->first()->nominal_per_satuan_pml,
                'status_honor_kegiatan' => DaftarSurveiModel::where('daftar_kegiatan_survei', Session::get('sample')['nama_kegiatan_survei'])->first()->sudah_dialokasikan_honor,
                'jenis_kegiatan_survei' => DaftarSurveiModel::where('daftar_kegiatan_survei', Session::get('sample')['nama_kegiatan_survei'])->first()->jenis_kegiatan,
                'jenis_survei_diikuti' => DaftarSurveiModel::where('daftar_kegiatan_survei', Session::get('sample')['nama_kegiatan_survei'])->first()->daftar_kegiatan_survei,
            ]);
        }
    }

    public function simpanRateHonor(Request $request)
    {
        $idr = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
        DaftarSurveiModel::where('id', Session::get('sample')['id_kegiatan_survei'])->update(['sudah_dialokasikan_honor' => 1]);
        $data_mitra_terpilih = AlokasiMitraSurvei::where('kegiatan_survei', '=', strtoupper(Session::get('sample')['nama_kegiatan_survei']))
            ->groupBy('id')->selectRaw('count(idbs_teralokasi) as total_bs, id')
            ->pluck('total_bs', 'id');

        $id_mitra_teralokasi = [];
        foreach ($data_mitra_terpilih as $field => $value) {
            $id_mitra_teralokasi[] = $field;
        }
        if (!isset(Session::get('sample')['id_bs_sample'])) {
            $mitra_teralokasi = DaftarMitra::whereIn('id', json_decode($_COOKIE['ids']))->get();
            foreach ($mitra_teralokasi as $data => $mitra_value) {
                RateHonor::updateOrCreate(['id_mitra' => $mitra_value->id, 'kegiatan' => DaftarSurveiModel::where('daftar_kegiatan_survei', Session::get('sample')['nama_kegiatan_survei'])->first()->daftar_kegiatan_survei,], [
                    'nama_mitra' => $mitra_value->nama,
                    'alamat_mitra' => $mitra_value->alamat_detail,
                    'jenis_pekerjaan' => DaftarSurveiModel::where('daftar_kegiatan_survei', Session::get('sample')['nama_kegiatan_survei'])->first()->jenis_kegiatan,
                    'jenis_pembayaran_mitra' => DaftarSurveiModel::where('daftar_kegiatan_survei', Session::get('sample')['nama_kegiatan_survei'])->first()->jenis_pembayaran,
                    'volume_pembayaran_mitra' => $request->volume[$mitra_value->id],
                    'honor' => (float) filter_var(substr_replace(str_replace('.', ',', $request->honor[$mitra_value->id]), '.', -4, 3), FILTER_SANITIZE_NUMBER_FLOAT),
                ]);
            }
        } else {
            $mitra_teralokasi = DaftarMitra::whereIn('id', $id_mitra_teralokasi)->get();
            foreach ($mitra_teralokasi as $data => $mitra_value) {
                // dd((float) filter_var(substr_replace(str_replace('.', ',', $request->honor), '.', -4, 3), FILTER_SANITIZE_NUMBER_FLOAT));
                RateHonor::updateOrCreate(['id_mitra' => $mitra_value->id, 'kegiatan' => DaftarSurveiModel::where('daftar_kegiatan_survei', Session::get('sample')['nama_kegiatan_survei'])->first()->daftar_kegiatan_survei,], [
                    'nama_mitra' => $mitra_value->nama,
                    'alamat_mitra' => $mitra_value->alamat_detail,
                    'jenis_pekerjaan' => $request->peranMitra[$mitra_value->id],
                    'jenis_pembayaran_mitra' => DaftarSurveiModel::where('daftar_kegiatan_survei', Session::get('sample')['nama_kegiatan_survei'])->first()->jenis_pembayaran,
                    'volume_pembayaran_mitra' => $request->volume[$mitra_value->id],
                    'honor' => (float) filter_var(substr_replace(str_replace('.', ',', $request->honor[$mitra_value->id]), '.', -4, 3), FILTER_SANITIZE_NUMBER_FLOAT),
                ]);
            }
        }

        Alert::success('Berhasil', 'Honor mitra berhasil dialokasikan');
        return redirect('/rate_honor');
    }

    public function generateSPK(Request $request)
    {
        Carbon::setLocale('id');
        $kegiatan_filter_bulan = DaftarSurveiModel::where('periode_pencairan_honor', 'LIKE', '%' . Carbon::createFromDate(
            (int)$request->input('tahunGenerateSPK'),
            (int)$request->input('copyBulanSPK'),
            (int)$request->input('copyTanggalGenerateSPK'),
            'Asia/Jakarta'
        )->translatedFormat('F') . '%')->get('daftar_kegiatan_survei')->toArray();
        $numberToWords = new NumberToWords();
        $data_mitra_spk = RateHonor::whereIn('kegiatan', $kegiatan_filter_bulan)->get();
        $data_mitra_group = [];
        foreach ($data_mitra_spk as $field => $value) {
            $data_mitra_group[$value->id_mitra] = [
                'id' => $value->id_mitra,
                'nama' => $value->nama_mitra,
                'alamat' => $value->alamat_mitra,
            ];
        }
        $total_honor_mitra = RateHonor::whereIn('kegiatan', $kegiatan_filter_bulan)->groupBy('id_mitra')->selectRaw('sum(honor) as total_honor, id_mitra')->pluck('total_honor', 'id_mitra');
        $arr_mitra = [];
        $idx = 0;
        foreach ($data_mitra_group as $field => $value) {
            $arr_mitra[] = [
                'hari_spk' => Carbon::createFromDate(
                    (int)$request->input('tahunGenerateSPK'),
                    (int)$request->input('copyBulanSPK'),
                    (int)$request->input('copyTanggalGenerateSPK'),
                    'Asia/Jakarta'
                )->translatedFormat('l'),
                'tanggal_spk' => $numberToWords->getNumberTransformer('id')->toWords(Carbon::createFromDate(
                    (int)$request->input('tahunGenerateSPK'),
                    (int)$request->input('copyBulanSPK'),
                    (int)$request->input('copyTanggalGenerateSPK'),
                    'Asia/Jakarta'
                )->translatedFormat('j')),
                'bulan_spk' => Carbon::createFromDate(
                    (int)$request->input('tahunGenerateSPK'),
                    (int)$request->input('copyBulanSPK'),
                    (int)$request->input('copyTanggalGenerateSPK'),
                    'Asia/Jakarta'
                )->translatedFormat('F'),
                'nama_mitra' => $value['nama'],
                'alamat_mitra' => $value['alamat'],
                'tahun_kegiatan' => date('Y'),
                'tahun_kata' => $numberToWords->getNumberTransformer('id')->toWords(date('Y')),
                'nomor_spk' => Carbon::createFromDate(
                    (int)$request->input('tahunGenerateSPK'),
                    (int)$request->input('copyBulanSPK'),
                    (int)$request->input('copyTanggalGenerateSPK'),
                    'Asia/Jakarta'
                )->translatedFormat('j') . "." .
                    Carbon::createFromDate(
                        (int)$request->input('tahunGenerateSPK'),
                        (int)$request->input('copyBulanSPK'),
                        (int)$request->input('copyTanggalGenerateSPK'),
                        'Asia/Jakarta'
                    )->translatedFormat('n') . "." .
                    ($idx + 1) . "/PPK/PPIS/SPK/" . date('Y'),
                'tanggal_mulai_spk' => Carbon::createFromDate(
                    (int)$request->input('tahunGenerateSPK'),
                    (int)$request->input('copyBulanSPK'),
                    (int)$request->input('copyTanggalGenerateSPK'),
                    'Asia/Jakarta'
                )->translatedFormat('j F Y'),
                'tanggal_berakhir_spk' => Carbon::createFromDate(
                    (int)$request->input('tahunGenerateSPK'),
                    (int)$request->input('copyBulanSPK'),
                    (int)$request->input('copyTanggalGenerateSPK'),
                    'Asia/Jakarta'
                )->endOfMonth()->translatedFormat('d F Y'),
                'honorMitraTotal' => "Rp" . number_format($total_honor_mitra[$value['id']], 0, ',', '.') . ",-",
                'terbilang_honor_total' => $numberToWords->getNumberTransformer('id')->toWords($total_honor_mitra[$value['id']]) . " rupiah",
                'no' => '${no_' . $idx . '}',
                'volume_satuan' => '${volume_satuan_' . $idx . '}',
                'jenis_pembayaran' => '${jenis_pembayaran_' . $idx . '}',
                'kegiatanMitra' => '${kegiatanMitra_' . $idx . '}',
                'hargaSatuan' => '${hargaSatuan_' . $idx . '}',
                'honorMitra' => '${honorMitra_' . $idx . '}',
                'bebanAnggaran' => '${bebanAnggaran_' . $idx . '}',
            ];
            $idx++;
        }
        $templateSPK = new TemplateProcessor('assets/doc_template/SPK_template.docx');
        $templateSPK->cloneBlock('spk', sizeof($data_mitra_spk), true, false, $arr_mitra);
        $i = 0;
        $groupData = [];
        foreach ($data_mitra_spk as $field => $value) {
            $groupData[$value->id_mitra][] = [
                'id' => $value->id_mitra,
                'kegiatan' => $value->kegiatan,
                'volume_pembayaran' => $value->volume_pembayaran_mitra,
                'jenis_pembayaran' => $value->jenis_pembayaran_mitra,
                'honor' => $value->honor,
            ];
        }
        foreach ($groupData as $group) {
            $arr_mitra_honor = array();
            $j = 1;
            foreach ($group as $row) {
                $arr_mitra_honor[] = array(
                    "no_{$i}" => $j,
                    "volume_satuan_{$i}" => $row['volume_pembayaran'],
                    "jenis_pembayaran_{$i}" => $row['jenis_pembayaran'],
                    "kegiatanMitra_{$i}" => $row['kegiatan'],
                    "hargaSatuan_{$i}" => "Rp" . number_format(DaftarSurveiModel::where('daftar_kegiatan_survei', $row['kegiatan'])->first()->nominal_per_satuan, 0, ',', '.') . ",-",
                    "honorMitra_{$i}" => "Rp" . number_format($row['honor'], 0, ',', '.') . ",-",
                    "bebanAnggaran_{$i}" => DaftarSurveiModel::where('daftar_kegiatan_survei', $row['kegiatan'])->first()->kode_beban_anggaran,
                );
                $j++;
            }
            $templateSPK->cloneRowAndSetValues("no_{$i}", $arr_mitra_honor);
            $i++;
        }
        $savePath = 'SPK-' . Carbon::createFromDate(
            (int)$request->input('tahunGenerateSPK'),
            (int)$request->input('copyBulanSPK'),
            (int)$request->input('copyTanggalGenerateSPK'),
            'Asia/Jakarta'
        )->translatedFormat('F') . '.docx';
        $templateSPK->saveAs($savePath);

        return response()->download($savePath);
    }

    public function generateBAST(Request $request)
    {
        Carbon::setLocale('id');
        $kegiatan_filter_bulan = DaftarSurveiModel::where('periode_pencairan_honor', 'LIKE', '%' . Carbon::createFromDate(
            (int)$request->input('tahunGenerateBAST'),
            (int)$request->input('copyBulanKegiatanBerakhir'),
            (int)$request->input('copyTanggalKegiatanBerakhir'),
            'Asia/Jakarta'
        )->translatedFormat('F') . '%')->get('daftar_kegiatan_survei')->toArray();
        $numberToWords = new NumberToWords();
        $data_mitra_bast = RateHonor::whereIn('kegiatan', $kegiatan_filter_bulan)->get();
        $data_mitra_group = [];
        foreach ($data_mitra_bast as $field => $value) {
            $data_mitra_group[$value->id_mitra] = [
                'id' => $value->id_mitra,
                'nama' => $value->nama_mitra,
                'alamat' => $value->alamat_mitra,
            ];
        }
        $arr_mitra = [];
        $idx_bast = 0;
        foreach ($data_mitra_group as $field => $value) {
            $arr_mitra[] = [
                'hari_bast' => Carbon::createFromDate(
                    (int)$request->input('tahunGenerateBAST'),
                    (int)$request->input('copyBulanKegiatanBerakhir'),
                    (int)$request->input('copyTanggalKegiatanBerakhir'),
                    'Asia/Jakarta'
                )->translatedFormat('l'),
                'tanggal_bast' => $numberToWords->getNumberTransformer('id')->toWords(Carbon::createFromDate(
                    (int)$request->input('tahunGenerateBAST'),
                    (int)$request->input('copyBulanKegiatanBerakhir'),
                    (int)$request->input('copyTanggalKegiatanBerakhir'),
                    'Asia/Jakarta'
                )->translatedFormat('j')),
                'bulan_bast' => Carbon::createFromDate(
                    (int)$request->input('tahunGenerateBAST'),
                    (int)$request->input('copyBulanKegiatanBerakhir'),
                    (int)$request->input('copyTanggalKegiatanBerakhir'),
                    'Asia/Jakarta'
                )->translatedFormat('F'),
                'nama_mitra' => $value['nama'],
                'alamat_mitra' => $value['alamat'],
                'nomor_bast' => Carbon::createFromDate(
                    (int)$request->input('tahunGenerateBAST'),
                    (int)$request->input('copyBulanKegiatanBerakhir'),
                    (int)$request->input('copyTanggalKegiatanBerakhir'),
                    'Asia/Jakarta'
                )->translatedFormat('d') . "." .
                    Carbon::createFromDate(
                        (int)$request->input('tahunGenerateBAST'),
                        (int)$request->input('copyBulanKegiatanBerakhir'),
                        (int)$request->input('copyTanggalKegiatanBerakhir'),
                        'Asia/Jakarta'
                    )->translatedFormat('m') . "." .
                    ($idx_bast + 1) . "/PPK/PPIS/BAST/" . Carbon::createFromDate(
                        (int)$request->input('tahunGenerateBAST'),
                        (int)$request->input('copyBulanKegiatanBerakhir'),
                        (int)$request->input('copyTanggalKegiatanBerakhir'),
                        'Asia/Jakarta'
                    )->translatedFormat('Y'),
                'tahun_kegiatan' => Carbon::createFromDate(
                    (int)$request->input('tahunGenerateBAST'),
                    (int)$request->input('copyBulanKegiatanBerakhir'),
                    (int)$request->input('copyTanggalKegiatanBerakhir'),
                    'Asia/Jakarta'
                )->translatedFormat('Y'),
                'tahun_kata' => $numberToWords->getNumberTransformer('id')->toWords(Carbon::createFromDate(
                    (int)$request->input('tahunGenerateBAST'),
                    (int)$request->input('copyBulanKegiatanBerakhir'),
                    (int)$request->input('copyTanggalKegiatanBerakhir'),
                    'Asia/Jakarta'
                )->translatedFormat('Y')),
                'nomor_spk' => Carbon::createFromDate(
                    (int)$request->input('tahunGenerateBAST'),
                    (int)$request->input('copyBulanKegiatanBerakhir'),
                    (int)$request->input('copyTanggalSPKinBAST'),
                    'Asia/Jakarta'
                )->translatedFormat('j') . "." .
                    Carbon::createFromDate(
                        (int)$request->input('tahunGenerateBAST'),
                        (int)$request->input('copyBulanKegiatanBerakhir'),
                        (int)$request->input('copyTanggalSPKinBAST'),
                        'Asia/Jakarta'
                    )->translatedFormat('n') . "." .
                    ($idx_bast + 1) . "/PPK/PPIS/SPK/" . Carbon::createFromDate(
                        (int)$request->input('tahunGenerateBAST'),
                        (int)$request->input('copyBulanKegiatanBerakhir'),
                        (int)$request->input('copyTanggalSPKinBAST'),
                        'Asia/Jakarta'
                    )->translatedFormat('Y'),
                'tanggal_spk' => Carbon::createFromDate(
                    (int)$request->input('tahunGenerateBAST'),
                    (int)$request->input('copyBulanKegiatanBerakhir'),
                    (int)$request->input('copyTanggalSPKinBAST'),
                    'Asia/Jakarta'
                )->translatedFormat('j F Y'),
                'tanggal_ttd' => Carbon::createFromDate(
                    (int)$request->input('tahunGenerateBAST'),
                    (int)$request->input('copyBulanKegiatanBerakhir'),
                    (int)$request->input('copyTanggalKegiatanBerakhir'),
                    'Asia/Jakarta'
                )->translatedFormat('j F Y '),
            ];
            $idx_bast++;
        }
        $templateBAST = new TemplateProcessor('assets/doc_template/BAST_template.docx');
        $templateBAST->cloneBlock('bast', sizeof($data_mitra_bast), true, false, $arr_mitra);
        $savePath = 'BAST ' . $request->input('pilihKegiatanGenerateBAST') . ' - ' . Carbon::createFromDate(
            (int)$request->input('tahunGenerateBAST'),
            (int)$request->input('copyBulanKegiatanBerakhir'),
            (int)$request->input('copyTanggalKegiatanBerakhir'),
            'Asia/Jakarta'
        )->translatedFormat('F Y') . '.docx';
        $templateBAST->saveAs($savePath);

        return response()->download($savePath);
    }
}
