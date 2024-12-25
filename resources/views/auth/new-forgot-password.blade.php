@extends('layouts.new-auth')

@section('auth-container')
    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-6 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="p-5">
                                <div class="text-center">
                                    <img class="logo-bps-gusit mb-3" src="{{ url('/') }}/img/bps-gusitv2.png" alt="">
                                    <h1 class="h4 text-gray-900 mb-2">Anda Lupa Password?</h1>
                                    <p class="mb-4">Tidak apa-apa, tenang saja. Masukkan saja alamat email anda di bawah
                                        ini dan kami akan mengirimkan link ganti password ke email anda!</p>
                                </div>
                                <form class="user" method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" id="exampleInputEmail"
                                            name="email" aria-describedby="emailHelp" placeholder="Masukkan Email">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Ganti Password
                                    </button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="{{ route('register') }}">Buat Akun!</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="{{ route('login') }}">Sudah punya akun? Login!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
