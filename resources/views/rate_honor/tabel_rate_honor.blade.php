@extends('new_home')

@section('container')
    @php
        $idr = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
    @endphp
    <div class="container-fluid px4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h4 class="mt-4 mb-3">Daftar Mitra yang Sudah Dialokasikan</h4>
            </div>
        </div>
        @if (!$status_honor_kegiatan)
            <div class="row justify-content-center alokasi-honor-mitra">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            <span class="mt-3">Daftar Mitra yang Sudah Dialokasikan untuk Kegiatan</span>
                            <strong
                                class="">{{ isset(session()->get('sample')['nama_kegiatan_survei']) ? strtoupper(session()->get('sample')['nama_kegiatan_survei']) : '' }}</strong>
                        </div>
                        <div class="card-body">
                            @if (isset(Session::get('sample')['id_bs_sample']))
                                <form action="{{ url('/uploadRateHonorMitra') }}" method="POST"
                                    id="form-alokasi-honor-mitra" autocomplete="off">
                                    @csrf
                                    <input type="hidden" name="id_sm" value="{{ Auth::user()->id }}">
                                    <table id="rate-honor-mitra" class="table table-striped table-bordered"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nama Mitra</th>
                                                <th>Alamat Mitra</th>
                                                <th>Kegiatan Survei yang Diikuti</th>
                                                <th>Jenis Pekerjaan</th>
                                                <th>Jenis Pembayaran</th>
                                                <th>Volume</th>
                                                <th>Honor Dialokasikan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($mitra_teralokasi as $mitra_survei_terpilih)
                                                <tr>
                                                    <td>{{ $mitra_survei_terpilih->nama }}</td>
                                                    <td>{{ $mitra_survei_terpilih->alamat_detail }}</td>
                                                    <td>{{ $jenis_survei_diikuti }}</td>
                                                    <td>{{ $jenis_kegiatan_survei }}</td>
                                                    <td>{{ $jenis_pembayaran_kegiatan }}</td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="number"
                                                                name="volume[{{ $mitra_survei_terpilih->id }}]"
                                                                id="volumeHonor{{ $mitra_survei_terpilih->id }}"
                                                                class="form-control" placeholder="Masukkan Volume Satuan"
                                                                min="0"
                                                                value="{{ $volume_pengalokasian[$mitra_survei_terpilih->id] }}"
                                                                readonly />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="text"
                                                                name="honor[{{ $mitra_survei_terpilih->id }}]"
                                                                id="harga{{ $mitra_survei_terpilih->id }}"
                                                                class="form-control" placeholder="Rp" type-currency="IDR"
                                                                value="Rp.{{ number_format($volume_pengalokasian[$mitra_survei_terpilih->id] * $harga_satuan_kegiatan, 2, ',', '.') }}"
                                                                readonly />
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-outline-primary float-right mt-2"
                                        id="btn-alokasikan-honor">Simpan
                                        Honor</button>
                                </form>
                            @else
                                <form action="{{ url('/uploadRateHonorMitra') }}" method="POST"
                                    id="form-alokasi-honor-mitra" autocomplete="off">
                                    @csrf
                                    <input type="hidden" name="id_sm" value="{{ Auth::user()->id }}">
                                    <table id="rate-honor-mitra" class="table table-striped table-bordered"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nama Mitra</th>
                                                <th>Jenis Pembayaran</th>
                                                <th>Peran Mitra</th>
                                                <th>Volume</th>
                                                <th>Honor Dialokasikan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($mitra_teralokasi as $mitra_survei_terpilih)
                                                <tr>
                                                    <td>{{ $mitra_survei_terpilih->nama }}</td>
                                                    <td>{{ $jenis_pembayaran_kegiatan }}</td>
                                                    <td>
                                                        <div class="form-group">
                                                            <select name="peranMitra[{{ $mitra_survei_terpilih->id }}]"
                                                                id="peranUtamaMitra{{ $mitra_survei_terpilih->id }}"
                                                                class="form-select">
                                                                <option value="pcl">PCL Pendataan</option>
                                                                <option value="pengolahan">Pengolahan</option>
                                                                <option value="pml">PML</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="number"
                                                                name="volume[{{ $mitra_survei_terpilih->id }}]"
                                                                id="volumeHonor{{ $mitra_survei_terpilih->id }}"
                                                                class="form-control" placeholder="Masukkan Volume Satuan"
                                                                min="0" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <input type="text"
                                                                name="honor[{{ $mitra_survei_terpilih->id }}]"
                                                                id="harga{{ $mitra_survei_terpilih->id }}"
                                                                class="form-control" placeholder="Rp" type-currency="IDR"
                                                                readonly />
                                                        </div>
                                                    </td>
                                                </tr>
                                                @push('totalHonorDialokasikan')
                                                    <script>
                                                        $('#rate-honor-mitra').on('input', '#volumeHonor{{ $mitra_survei_terpilih->id }}', function() {
                                                            $('#harga{{ $mitra_survei_terpilih->id }}').val(RupiahCurrency.format($(
                                                                    '#volumeHonor{{ $mitra_survei_terpilih->id }}').val() *
                                                                ($('#peranUtamaMitra{{ $mitra_survei_terpilih->id }}').val() == 'pcl' ?
                                                                    '{{ $harga_satuan_kegiatan_pendataan }}' : ($(
                                                                            '#peranUtamaMitra{{ $mitra_survei_terpilih->id }}').val() == 'pengolahan' ?
                                                                        '{{ $harga_satuan_kegiatan_pengolahan }}' : '{{ $harga_satuan_kegiatan_pml }}'))
                                                            ));
                                                        });
                                                    </script>
                                                @endpush
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-outline-primary float-right mt-2"
                                        id="btn-alokasikan-honor">Simpan
                                        Honor</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row justify-content-center honor-mitra-datatable">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            <span class="mt-3">Daftar Mitra yang Sudah Dialokasikan untuk Kegiatan</span>
                            <strong
                                class="">{{ isset(session()->get('sample')['nama_kegiatan_survei']) ? strtoupper(session()->get('sample')['nama_kegiatan_survei']) : '' }}</strong>
                            <button class="btn btn-sm btn-warning float-right mr-2" id="btn-edit-honor">Edit</button>
                        </div>
                        <div class="card-body">
                            <table id="daftar-honor-mitra" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nama Mitra</th>
                                        <th>Alamat Mitra</th>
                                        <th>Kegiatan Survei yang Diikuti</th>
                                        <th>Jenis Pembayaran</th>
                                        <th>Volume</th>
                                        <th>Jenis Pekerjaan</th>
                                        <th>Honor Dialokasikan</th>
                                        <th>Total Honor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mitra_honor as $honor)
                                        <tr>
                                            <td>{{ $honor->nama_mitra }}</td>
                                            <td>{{ $honor->alamat_mitra }}</td>
                                            <td>{{ $honor->kegiatan }}</td>
                                            <td>{{ $honor->jenis_pembayaran_mitra }}</td>
                                            <td>{{ $honor->volume_pembayaran_mitra }}</td>
                                            <td>
                                                <small
                                                    class="{{ $honor->jenis_pekerjaan == 'Pendataan'
                                                        ? 'chips-pencacahan'
                                                        : ($honor->jenis_pekerjaan == 'Pengolahan'
                                                            ? 'chips-pengolahan'
                                                            : 'chips-pencacahan-pengolahan') }}">{{ $honor->jenis_pekerjaan }}</small>
                                            </td>
                                            <td>Rp{{ number_format($honor->honor, 0, ',', '.') }},-</td>
                                            <td>{{ $total_honor_mitra[$honor->id_mitra] <= 3526000 ? 'Rp' . number_format($total_honor_mitra[$honor->id_mitra], 0, ',', '.') . ',-' : 'Sudah Lewat SBML' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
