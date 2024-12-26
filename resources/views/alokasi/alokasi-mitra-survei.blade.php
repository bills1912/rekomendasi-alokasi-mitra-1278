@extends('new_home')

@section('container')
    <div class="container-fluid px4">
        <div class="row justify-content-center">
            <div class="col-md-11">
                @if (session()->has('sample'))
                    @include('sweetalert::alert')
                @endif
                <input type="hidden" id="session-id-bs"
                    value="{{ isset(session()->get('sample')['id_bs_sample']) ? json_encode(session()->get('sample')['id_bs_sample']) : '' }}">
                <input type="hidden" id="session-wilkerstat"
                    value="{{ isset(session()->get('sample')['wilkerstat_kegiatan_survei']) ? json_encode(session()->get('sample')['wilkerstat_kegiatan_survei']) : '' }}">
                <input type="hidden" id="bs-teralokasi-session"
                    value="{{ isset(session()->get('sample')['bs_sudah_teralokasi']) ? json_encode(session()->get('sample')['bs_sudah_teralokasi']) : '' }}">
                <input type="hidden" id="session-total-honor-mitra"
                    value="{{ isset(session()->get('sample')['total_honor_dialokasikan']) ? json_encode(session()->get('sample')['total_honor_dialokasikan']) : '' }}">
                <h4 class="mt-4 mb-2">Rekomendasi Alokasi Mitra Kegiatan
                    <strong>{{ isset(session()->get('sample')['nama_kegiatan_survei']) ? strtoupper(session()->get('sample')['nama_kegiatan_survei']) : '' }}</strong>
                </h4>
                @if (session()->has('sample'))
                    <a href="{{ url('/resetUploadSample') }}" class="btn btn-outline-success"><i
                            class="fa-solid fa-rotate-right pr-2"></i>Ulangi Upload Sample</a>
                @endif
                <div class="row justify-content-center mt-3 overview-survey-location">
                    <div class="col-xl-12">
                        @if (isset(session()->get('sample')['id_bs_sample']))
                            <div class="progress-alokasi-bs mb-2">
                                Dialokasikan:
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                                        role="progressbar"
                                        aria-valuenow="{{ count(session()->get('sample')['bs_sudah_teralokasi']) }}"
                                        aria-valuemin="0"
                                        aria-valuemax="{{ count(session()->get('sample')['id_bs_sample']) }}"
                                        style="width: {{ (count(session()->get('sample')['bs_sudah_teralokasi']) / count(session()->get('sample')['id_bs_sample'])) * 100 }}%">
                                        <strong
                                            style="font-size: 16px;">{{ count(session()->get('sample')['bs_sudah_teralokasi']) }}
                                            {{ session()->get('sample')['wilkerstat_kegiatan_survei'] == 'DESA' ? 'Desa' : 'BS' }}</strong>
                                    </div>
                                </div>
                                Dari Total: {{ count(session()->get('sample')['id_bs_sample']) }}
                                {{ session()->get('sample')['wilkerstat_kegiatan_survei'] == 'DESA' ? 'Desa' : 'Blok Sensus' }}
                            </div>
                        @endif
                        <div class="card mb-4">
                            <div class="card-header" id="map-alokasi-wilkerstat">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <i class="fa-solid fa-map"></i>
                                        Overview Peta Sampel BS
                                        <strong>{{ isset(session()->get('sample')['nama_kegiatan_survei']) ? strtoupper(session()->get('sample')['nama_kegiatan_survei']) : '' }}</strong>
                                        BPS Kota Gunungsitoli
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" id="overview-survey"><canvas id="overview-survey" width="100%"
                                    height="40"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="spinner-grow loader text-secondary loading-alokasi-survei" role="status" hidden>
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="row justify-content-center mt-3 mitra-sesuai-wilkerstat" hidden>
            <div class="col-xl-11">
                <div class="card mb-4">
                    <div class="card-header" id="map-alokasi-wilkerstat">
                        <i class="fa-solid fa-map"></i>
                        Peta Persebaran Mitra BPS Kota Gunungsitoli (1278)
                    </div>
                    <div class="card-body" id="map-alokasi-mitra"><canvas id="map-alokasi-mitra" width="100%"
                            height="40"></canvas></div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center tabel-daftar-mitra" hidden>
            <div class="col-xl-11">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Daftar Mitra yang Direkomendasikan
                    </div>
                    <div class="card-body">
                        <table id="data-table-mitra" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nama Mitra</th>
                                    <th>Alamat Mitra</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Jenis Pekerjaan</th>
                                    <th>Jarak Mitra ke BS</th>
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
