@extends('new_home')

@section('container')
    <div class="container-fluid px4">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <h4 class="mt-4 mb-3">Upload Daftar Mitra Terpilih</h4>
                <div class="alert alert-warning mt-2" role="alert">
                    <h4 class="alert-heading">Informasi!</h4>
                    <p>
                        Halaman ini merupakan halaman yang khusus ditujukan untuk melakukan pengunggahan mitra terpilih
                        untuk kegiatan sensus. Mohon untuk diperhatikan bahwa yang diunggah adalah
                        <strong>ID identitas wilayah dari provinsi sampai kota dan NIK</strong> dari mitra yang dipilih
                        dalam kegiatan sensus. Atau bisa dengan men-<i>download</i> contoh file (sekaligus <i>template</i>)
                        dengan menekan tombol di bawah ini.
                    </p>
                    <hr>
                    <p class="mb-0">Atas perhatiannya, diucapkan terimakasih.
                    </p>
                </div>
                <form action="{{ url('/upload-mitra-terpilih') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <input type="file" class="form-control" name="mitraSensusTerpilih" id="mitraSensusTerpilih">
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-success " name="submit-mitra-terpilih"><i
                                    class="fa-solid fa-file pr-2"></i>Unggah File</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
