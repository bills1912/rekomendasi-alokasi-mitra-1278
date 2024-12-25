@extends('mitra_view.mitra_home')

@section('mitra-container')
    <h4 class="mt-4 mb-4">Alokasi Rate Honor Mitra</h4>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Daftar Mitra
        </div>
        <div class="card-body">
            <table id="daftar-kegiatan-mitra" class="table table-striped table-bordered" style="width:100%;">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kegiatan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Berakhir</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection
