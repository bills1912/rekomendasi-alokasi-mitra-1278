@extends('new_home')

@section('container')
    <div class="container-fluid px4">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <h4 class="mt-4 mb-3">Status Alokasi Kegiatan</h4>
                <div class="row justify-content-center alokasi-honor-mitra">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                <span class="mt-3"><strong>Tahunan (Dokumen, Blok Sensus)</strong></span>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="id_sm" value="{{ Auth::user()->id }}">
                                <table id="daftar-status-alokasi-kegiatan"
                                    class="table table-striped table-bordered display nowrap dtr-inline collapsed"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Nama Kegiatan</th>
                                            <th>Status Alokasi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($semua_kegiatan as $kegiatan)
                                            <tr>
                                                <td>{{ $kegiatan->daftar_kegiatan_survei }}</td>
                                                <td>
                                                    <small
                                                        class="{{ $kegiatan->sudah_dialokasikan_honor == 0 ? 'chips-belum-teralokasi' : 'chips-sudah-teralokasi' }}">
                                                        {{ $kegiatan->sudah_dialokasikan_honor == 0 ? 'Belum Dialokasikan' : 'Sudah Dialokasikan' }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <button type="button"
                                                        class="btn btn-outline-primary btn-sm editStatusAlokasiHonorKegiatan"
                                                        style="position:inline" data-bs-toggle="modal"
                                                        data-bs-target="#editStatusKegiatan{{ $kegiatan->id }}"><i
                                                            class="fa-regular fa-pen-to-square"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach ($semua_kegiatan as $kegiatan)
                    <div class="modal fade" id="editStatusKegiatan{{ $kegiatan->id }}" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title col-sm-10" id="exampleModalLabel">Form Ubah Status Alokasi Honor Kegiatan
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ url('/ubahStatusAlokasiHonorKegiatan', $kegiatan->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group flex-group">
                                            <label class="col-sm-4 required" for="updateStatusAlokasiHonorKegiatan">Status Alokasi:</label>
                                            <select class="form-select" aria-label="Default select example"
                                                name="updateStatusAlokasiHonorKegiatan" id="updateStatusAlokasiHonorKegiatan"
                                                aria-placeholder="Lorem Ipsum">
                                                <option selected muted disabled>Pilih Status Alokasi
                                                </option>
                                                <hr>
                                                <option value="0" {{ ($kegiatan->sudah_dialokasikan_honor == 0) ? 'selected' : '' }}>
                                                    Belum Dialokasikan</option>
                                                <option value="1" {{ ($kegiatan->sudah_dialokasikan_honor == 1) ? 'selected' : '' }}>
                                                    Sudah Dialokasikan</option>
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary"><i
                                                    class="fa-solid fa-floppy-disk pr-1"></i>Ubah Status Alokasi Honor</button>
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
@endsection
