@extends('new_home')

@section('container')
    <div class="container-fluid px4">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <h4 class="mt-4 mb-3">Standar Biaya Maksimal</h4>
                @if (Auth::user()->is_admin)
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                        data-bs-target="#tambahDaftarSBML">
                        <i class="fa-solid fa-plus pr-1"></i>Tetapkan SBML
                    </button>
                @endif
                <div class="row justify-content-center alokasi-honor-mitra">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                <span class="mt-3"><strong>Tahunan (Dokumen, Blok Sensus)</strong></span>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="id_sm" value="{{ Auth::user()->id }}">
                                <table id="daftar-sbml-yearly-all"
                                    class="table table-striped table-bordered display nowrap dtr-inline collapsed"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Jenis Kegiatan</th>
                                            <th>Periode Kegiatan</th>
                                            <th>SBML</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sbml_kegiatan_yearly as $sbml_year)
                                            <tr>
                                                <td>{{ $sbml_year->jenis_kegiatan }}</td>
                                                <td>{{ $sbml_year->periode_sbml }}</td>
                                                <td>Rp{{ number_format($sbml_year->nominal_sbml, 0, ',', '.') }},-</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="tambahDaftarSBML" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title col-sm-10" id="exampleModalLabel">Form Penetapan SBML Kegiatan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                {{-- <div class="btn-sbml-type col-sm-4 mb-3">
                                    <button type="button" class="btn btn-outline-secondary" id="btn-sbml-monthly"><i
                                            class="fa-solid fa-list-check mr-2"></i>Bulanan</button>
                                    <button type="button" class="btn btn-outline-secondary" id="btn-sbml-yearly"><i
                                            class="fa-solid fa-calendar-days mr-2"></i>Tahunan</button>
                                </div> --}}
                                <form action="{{ url('/tambah_data_sbml') }}" method="POST">
                                    @csrf
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="jenisKegiatanSBML">Jenis Kegiatan:</label>
                                        <select class="form-select" aria-label="Default select example"
                                            name="jenisKegiatanSBML" id="jenisKegiatanSBML" aria-placeholder="Lorem Ipsum">
                                            <option selected muted disabled>Pilih Jenis Kegiatan
                                            </option>
                                            <hr>
                                            <option value="Pendataan"
                                                {{ isset($arr_sbml_yearly[0]) ? (in_array('Pendataan', $arr_sbml_yearly[0], true) ? 'disabled' : '') : '' }}>
                                                Pendataan</option>
                                            <option value="Pengolahan"
                                                {{ isset($arr_sbml_yearly[0]) ? (in_array('Pengolahan', $arr_sbml_yearly[0], true) ? 'disabled' : '') : '' }}>
                                                Pengolahan</option>
                                        </select>
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="periodeSBML">Periode SBML:</label>
                                        <input class="form-control" type="text" name="periodeSBML" id="periodeSBML" value="Tahunan" readonly>
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="nominalSBML">Nominal SBML:</label>
                                        <input class="form-control" type="text" name="nominalSBML" id="nominalSBML"
                                            placeholder="Rp" type-currency="IDR">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary"><i
                                                class="fa-solid fa-floppy-disk pr-1"></i>Tambah Kegiatan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
