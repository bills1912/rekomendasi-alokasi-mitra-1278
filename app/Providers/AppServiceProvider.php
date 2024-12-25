<?php

namespace App\Providers;

use App\Models\RateHonor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $data_mitra = DB::select('select * from daftar_mitra_final');
        View::share('data_mitra', $data_mitra);

        // $id_mitra_ob = RateHonor::where('jenis_pembayaran_mitra', 'Orang Bulan')->get('id_mitra')->toArray();
        // $ids_mitra = array();
        // foreach ($id_mitra_ob as $field=>$value){
        //     $ids_mitra[] = $value['id_mitra'];
        // }
        // if (isset($id_mitra_ob)) {
        //     View::share('mitra_teralokasi_ob', $ids_mitra);
        // }
    }
}
