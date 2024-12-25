@extends('new_home')

@section('container')
    <div class="container-fluid px4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @if (session()->has('mitra_sensus'))
                    @include('sweetalert::alert')
                @elseif (session()->has('data_mitra'))
                    @dd(session()->get('data_mitra'))
                @endif
                <h4 class="mt-4 mb-2">Rekomendasi Alokasi Mitra Kegiatan Sensus</h4>
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fa-solid fa-location-crosshairs"></i>
                        Alokasi Wilayah Kerja
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Provinsi</label>
                                    <select class="form-select" id="selectProvSensus"></select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Kota/Kabupaten</label>
                                    <select class="form-select" id="selectRegencySensus"></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>Kecamatan</label>
                                    <select class="form-select" id="selectDistrictSensus"></select>
                                </div>
                            </div>
                            <div class="col">
                                <p class="mb-2 text-muted">Informasi</p>
                                <p class="info-text small text-muted justify-content-center">Aplikasi ini dibangun untuk
                                    membantu subject matter
                                    dalam mengalokasikan mitra pada kegiatan survei maupun sensus.</p>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-success" id="tombol-alokasi-sensus"
                            data-bs-toggle="modal" data-bs-target="#staticBackdrop" hidden><i
                                class="fa-regular fa-square-check pr-1"></i>Pilih
                            Wilayah Kerja</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Pengalokasian Mitra</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('/alokasi_mitra_sensus') }}" method="post" id="form-mitra-sensus">
                            @csrf
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Desa</label>
                                        <select class="form-select" id="selectVillageSensus"
                                            name="selectVillageSensus"></select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Pilih SLS</label>
                                        <select class="form-select" id="selectSLS" name="selectSLS"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="lihat-daftar-mitra"
                                    data-bs-dismiss="modal" name="submit-match-bs"><i
                                        class="fa-solid fa-list pr-1"></i>Lihat
                                    Daftar Mitra</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- @dd($data_mitra) --}}
        <div class="row justify-content-center mt-3 mitra-sensus-wilkerstat" hidden>
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header" id="map-alokasi-wilkerstat">
                        <i class="fa-solid fa-map"></i>
                        Peta SLS Terpilih (1278)
                    </div>
                    <div class="card-body" id="map-alokasi-mitra-sensus"><canvas id="map-alokasi-mitra-sensus"
                            width="100%" height="40"></canvas></div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center tabel-mitra-sensus" hidden>
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Daftar SLS Lain dalam Desa yang Sama
                    </div>
                    <div class="card-body">
                        <table id="datatable-mitra-sensus" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID Desa</th>
                                    <th>ID SLS Tetangga</th>
                                    <th>Jumlah KK SLS Tetangga</th>
                                    <th>Jarak SLS Terpilih ke Tetangga</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center tabel-mitra-sensus" hidden>
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Daftar Mitra yang Direkomendasikan
                    </div>
                    <div class="card-body">
                        <table id="rekomendasi-mitra-sensus" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID Desa</th>
                                    <th>ID SLS</th>
                                    <th>Nama Mitra</th>
                                    <th>Alamat Mitra</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Jarak Mitra ke SLS</th>
                                    <th>Lokasi Mitra</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
