@extends('new_home')

@section('container')
    <div class="container-fluid px4">
        <div class="row justify-content-center">
            <div class="col-md-11">
                @include('sweetalert::alert')
                <h4 class="mt-4 mb-3">Upload Sample Mitra</h4>
                <div class="alert alert-warning mt-2" role="alert">
                    <h4 class="alert-heading">Informasi!</h4>
                    <p>
                        Halaman ini merupakan halaman yang khusus ditujukan untuk melakukan pengunggahan sample dari survei
                        yang sedang berlangsung. Mohon untuk diperhatikan bahwa bentuk sampel survei yang diunggah adalah
                        <strong>ID Blok Sensus</strong> dari sample. Jika sample yang dikirimkan dari pusat masih belum
                        menyertakan ID Blok Sensus, maka <i>Subject Matter</i> sebisa mungkin <strong>menyediakan ID Blok
                            Sensus</strong>
                        dari sampel tersebut guna untuk diunggah melalui halaman ini. Atau bisa dengan men-<i>download</i>
                        contoh file (sekaligus <i>template</i>)
                        dengan menekan tombol di bawah ini.
                    </p>
                    <a href="{{ url('/assets') }}/contoh/DSBS Sakernas 2024.xlsx" download class="btn btn-success">Contoh
                        Sampel BS</a>
                    <a href="{{ url('/assets') }}/contoh/mitra_sample.xlsx" download class="btn btn-success ml-2">Contoh
                        Mitra Terpilih</a>
                    <hr>
                    <p class="mb-0">Atas perhatiannya, diucapkan terimakasih.
                    </p>
                </div>
                <form action="{{ url('/upload-sample-bs') }}" method="post" enctype="multipart/form-data"
                    id="survey-sample-uploader">
                    @csrf
                    @if (isset($_COOKIE['ids']) && isset($_COOKIE['tanpa-bs']))
                        <div class="form-group mb-2">
                            <label for="namaKegiatanSurvei">Kegiatan Survei</label>
                            <select class="form-select" id="initializeSurvei" name="initializeSurvei"></select>
                        </div>
                    @elseif (isset($_COOKIE['ids']))
                        <div class="row">
                            <div class="col">
                                <div class="form-group mb-2">
                                    <label class="required" for="namaKegiatanSurvei">Kegiatan Survei</label>
                                    <select class="form-select" id="initializeSurvei" name="initializeSurvei"></select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="col">
                                    <label class="required" for="unggahSample">File Excel Wilkerstat</label>
                                    <input type="file" class="form-control" name="unggahSample" id="unggahSample"
                                        value="{{ old('unggahSample') }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="col">
                                    <div class="form-group">
                                        <label class="required" for="jenisWilkerstat">Jenis Wilkerstat</label>
                                        <select class="form-select" aria-label="Default select example" name="jenisWilkerstat"
                                            id="jenisWilkerstat" aria-readonly="true">
                                            <option selected muted disabled>Pilih Jenis Wilkerstat
                                            </option>
                                            <option value="Blok Sensus">Blok Sensus</option>
                                            <option value="Desa">Desa</option>
                                            <option value="Kecamatan">Kecamatan</option>
                                            <option value="Kabupaten/Kota">Kabupaten/Kota</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="form-group mb-2">
                            <label for="namaKegiatanSurvei">Kegiatan Survei</label>
                            <select class="form-select" id="initializeSurvei" name="initializeSurvei"></select>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="unggahSample">File Excel Sampel Blok Sensus</label>
                                <input type="file" class="form-control" name="unggahSample" id="unggahSample"
                                    value="{{ old('unggahSample') }}">
                            </div>
                            <div class="col">
                                <label for="unggahMitraTerpilih">File Excel Mitra Terpilih</label>
                                <input type="file" class="form-control" name="unggahMitraTerpilih"
                                    id="unggahMitraTerpilih">
                            </div>
                        </div>
                    @endif
                    <button type="submit" class="btn btn-success mt-2" name="submit-sample-wilkerstat"><i
                            class="fa-solid fa-file pr-2"></i>Alokasikan</button>
                    <input type="hidden" id="id-bs-session"
                        value="{{ isset(session()->get('sample')['id_bs_sample'][0]) ? session()->get('sample')['id_bs_sample'][0] : '' }}">
                </form>
            </div>
        </div>
    </div>
@endsection
