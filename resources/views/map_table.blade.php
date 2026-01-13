@extends('new_home')

@section('container')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        {{-- <div class="row justify-content-center">
            <div class="col-xl-11">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fa-solid fa-map"></i>
                        Peta Persebaran Mitra BPS Kota Gunungsitoli (1278)
                    </div>
                    <div class="card-body" id="map-mitra"><canvas id="map-mitra" width="100%" height="40"></canvas></div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                DataTable Example
            </div>
            <div class="card-body">
                <table id="datatable-mitra-total" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID SLS</th>
                            <th>Nama Mitra</th>
                            <th>Alamat Mitra</th>
                            <th>Cari Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_mitra_total as $mitra)
                            <tr>
                                <td>{{ $mitra->id_kec }}</td>
                                <td>{{ $mitra->nama }}</td>
                                <td>{{ $mitra->alamat_detail }}</td>
                                <td>
                                    <button class="btn btn-outline-primary cari-lokasi">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div> --}}
    </div>
@endsection
