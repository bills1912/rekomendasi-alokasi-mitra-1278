@extends('new_home')

@section('container')
    <div class="container-fluid px4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h4 class="mt-4 mb-3">Alokasi Mitra</h4>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-6">
                <label class="required" for="periodePencairanAnggaran">Kegiatan:</label>
                <select class="form-select" name="filterKegiatanAlokasiMitra" id="filterKegiatanAlokasiMitra"></select>
            </div>
        </div>
        <div class="row justify-content-center tabel-mitra-survei-teralokasi">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        <span class="mt-3">Daftar Mitra yang Sudah Dialokasikan untuk Kegiatan</span>
                        <strong
                            class="nama-kegiatan-terpilih">{{ isset(session()->get('sample')['nama_kegiatan_survei']) ? strtoupper(session()->get('sample')['nama_kegiatan_survei']) : '' }}</strong>
                    </div>
                    <div class="card-body">
                        <table id="mitra-survei-teralokasi" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nama Mitra</th>
                                    <th>Alamat Mitra</th>
                                    <th>Kegiatan Survei yang Diikuti</th>
                                    <th>Lokasi BS Dialokasikan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list_mitra_terpilih as $mitra_survei_terpilih)
                                    <tr>
                                        <td>{{ $mitra_survei_terpilih->nama }}</td>
                                        <td>{{ $mitra_survei_terpilih->alamat_detail }}</td>
                                        <td>{{ $kegiatan_mitra_terpilih }}</td>
                                        <td>
                                            @if (isset($daftar_bs_teralokasi[$mitra_survei_terpilih->id]))
                                                @foreach (explode(';', $daftar_bs_teralokasi[$mitra_survei_terpilih->id]) as $row)
                                                    <li>{{ $row }}</li>
                                                @endforeach
                                            @else
                                                Tanpa Blok Sensus
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button"
                                                class="btn btn-outline-primary btn-sm editDataMitraSurveiTeralokasi"
                                                style="position:inline" data-bs-toggle="modal"
                                                data-bs-target="#exampleModalEdit{{ $mitra_survei_terpilih->super_id }}"><i
                                                    class="fa-regular fa-pen-to-square"></i>
                                            </button>
                                            <a href="{{ url('/list_mitra_survei_teralokasi', $mitra_survei_terpilih->id) }}"
                                                class="btn btn-outline-danger hapusDataMitraSurveiTeralokasi btn-sm"><i
                                                    class="fa-regular fa-trash-can"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @foreach ($list_mitra_terpilih as $mitra_survei_terpilih)
                            <div class="modal fade guest-modal-edit-responden"
                                id="exampleModalEdit{{ $mitra_survei_terpilih->super_id }}"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Data Mitra</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form
                                                action="/list_mitra_survei_teralokasi/{{ $mitra_survei_terpilih->super_id }}"
                                                method="post" id="editRespondenForm">
                                                @csrf
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label for="namaMitra">Nama Mitra</label>
                                                        <input type="text" class="form-control" id="namaMitra"
                                                            name="namaMitra"
                                                            value="{{ $mitra_survei_terpilih->nama_mitra }}" readonly>
                                                    </div>
                                                    <div class="col">
                                                        <label for="alamatMitra">Alamat Mitra</label>
                                                        <input type="text" class="form-control" id="alamatMitra"
                                                            name="alamatMitra"
                                                            value="{{ $mitra_survei_terpilih->alamat_mitra }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="row mb-4">
                                                    <div class="col">
                                                        <label for="kegiatanSurvei">Survei yang Diikuti</label>
                                                        <input type="text" class="form-control" id="kegiatanSurvei"
                                                            name="kegiatanSurvei"
                                                            value="{{ $mitra_survei_terpilih->kegiatan_survei }}" readonly>
                                                    </div>
                                                    <div class="col">
                                                        <label for="idbsTeralokasi">BS yang Dialokasikan</label>
                                                        <input type="text" class="form-control" id="idbsTeralokasi"
                                                            name="idbsTeralokasi"
                                                            value="{{ $mitra_survei_terpilih->idbs_teralokasi }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary"><i
                                                            class="fa-solid fa-floppy-disk pr-2"></i>Ubah</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
