@extends('new_home')

@section('container')
    <div class="container-fluid px4">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <h4 class="mt-4 mb-3">Daftar Seluruh Kegiatan</h4>
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                    data-bs-target="#tambahDaftarKegiatan">
                    <i class="fa-solid fa-plus pr-1"></i>Tambah Kegiatan
                </button>
                <div class="row justify-content-center alokasi-honor-mitra">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                <span class="mt-3">Daftar Kegiatan Survei dan Sensus yang Dilakukan</span>
                            </div>
                            <div class="card-body">
                                <form action="{{ url('/tambah_daftar_kegiatan') }}" method="POST"
                                    id="form-alokasi-honor-mitra" autocomplete="off">
                                    @csrf
                                    <input type="hidden" name="id_sm" value="{{ Auth::user()->id }}">
                                    <table id="daftar-kegiatan-all"
                                        class="table table-striped table-bordered display nowrap dtr-inline collapsed"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nama Kegiatan</th>
                                                <th>Periode Kegiatan</th>
                                                <th>Jenis Kegiatan</th>
                                                <th>Jenis Pembayaran</th>
                                                <th>Jumlah Satuan</th>
                                                <th>Nominal per Satuan</th>
                                                <th>Total Anggaran</th>
                                                <th>Jumlah Petugas</th>
                                                <th>Periode Pencaiarn Anggaran</th>
                                                <th>Beban Anggaran</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($semua_kegiatan as $kegiatan)
                                                <tr>
                                                    <td>{{ $kegiatan->daftar_kegiatan_survei }}</td>
                                                    <td>{{ $kegiatan->waktu_mulai }} - {{ $kegiatan->waktu_berakhir }}</td>
                                                    <td>{{ $kegiatan->jenis_kegiatan }}</td>
                                                    <td>{{ $kegiatan->jenis_pembayaran }}</td>
                                                    <td>{{ $kegiatan->jumlah_satuan }}</td>
                                                    <td>Rp{{ number_format($kegiatan->nominal_per_satuan, 0, ',', '.') }},-
                                                    </td>
                                                    <td>Rp{{ number_format($kegiatan->total_anggaran, 0, ',', '.') }},-</td>
                                                    <td>{{ $kegiatan->jumlah_petugas_kegiatan . ' orang' }}</td>
                                                    <td>{{ $kegiatan->periode_pencairan_honor }}</td>
                                                    <td>{{ $kegiatan->kode_beban_anggaran }}</td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-outline-warning btn-sm editDataMitraSurveiTeralokasi"
                                                            style="position:inline" data-bs-toggle="modal"
                                                            data-bs-target="#editDaftarKegiatan{{ $kegiatan->id }}"><i
                                                                class="fa-regular fa-pen-to-square"></i>
                                                        </button>
                                                        <a href="{{ url('/hapus_kegiatan', $kegiatan->id) }}"
                                                            class="btn btn-outline-danger hapusDataKegiatanSurveiSensus btn-sm"><i
                                                                class="fa-regular fa-trash-can"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="tambahDaftarKegiatan" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header mb-3">
                                <h5 class="modal-title col-sm-6" id="exampleModalLabel">Form Tambah Kegiatan Survei/Sensus
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ url('/tambah_daftar_kegiatan') }}" method="POST">
                                    @csrf
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="namaKegiatan">Nama Kegiatan
                                            Survei/Sensus:</label>
                                        <select class="form-select" name="namaKegiatan" id="namaKegiatan" required></select>
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required">Periode Kegiatan:</label>
                                        <div class="input-group input-daterange">
                                            <input type="text" class="form-control" id="periodeKegiatanAwal"
                                                name="periodeKegiatanAwal" placeholder="Kegiatan Dimulai"
                                                aria-describedby="periodeAwal" autocomplete="off" required>
                                            <div class="input-group-text">s/d</div>
                                            <input type="text" class="form-control" id="periodeKegiatanAkhir"
                                                name="periodeKegiatanAkhir" placeholder="Kegiatan Berakhir"
                                                aria-describedby="periodeAkhir" autocomplete="off" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="periodeAwal"><i
                                                        class="fa-regular fa-calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="jenisKegiatan">Jenis Kegiatan:</label>
                                        <select class="form-select" aria-label="Default select example" name="jenisKegiatan"
                                            id="jenisKegiatan" required>
                                            <option selected muted disabled>Pilih Jenis Kegiatan
                                            </option>
                                            <option value="Pendataan">Pendataan</option>
                                            <option value="Pengolahan">Pengolahan</option>
                                            <option value="Pendataan + Pengolahan">Pendataan + Pengolahan</option>
                                            <option value="Pendataan + PML Mitra">Pendataan + PML Mitra</option>
                                            <option value="Pendataan + Pengolahan + PML Mitra">Pendataan + Pengolahan + PML Mitra</option>
                                        </select>
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="jenisPembayaran">Jenis Pembayaran
                                            Mitra:</label>
                                        <select class="form-select" aria-label="Default select example"
                                            name="jenisPembayaran" id="jenisPembayaran" required>
                                            {{-- <option selected muted disabled>Pilih Jenis Pembayaran
                                            </option> --}}
                                            <option value="Dokumen">Dokumen</option>
                                            <option value="Blok Sensus">Blok Sensus</option>
                                            <option value="Segmen">Segmen</option>
                                            <option value="Pasar">Pasar</option>
                                            <option value="Orang Bulan">O-B</option>
                                        </select>
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="jumlahSatuan">Volume:</label>
                                        <input class="form-control" type="number" name="jumlahSatuan" id="jumlahSatuan"
                                            placeholder="Masukan Jumlah Satuan" autocomplete="off" required>
                                    </div>
                                    <div class="form-group flex-group" id="formNominalPerSatuanPML" hidden>
                                        <label class="col-sm-4 required" for="nominalperSatuanPML">Harga Satuan
                                            PML:</label>
                                        <input class="form-control" type="text" name="nominalperSatuanPML"
                                            id="nominalperSatuanPML" type-currency="IDR" placeholder="Rp" required>
                                    </div>
                                    <div class="form-group flex-group" id="formNominalPerSatuanPengolahan" hidden>
                                        <label class="col-sm-4 required" for="nominalperSatuanPengolahan">Harga Satuan
                                            Pengolahan:</label>
                                        <input class="form-control" type="text" name="nominalperSatuanPengolahan"
                                            id="nominalperSatuanPengolahan" type-currency="IDR" placeholder="Rp"
                                            required>
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="nominalperSatuan">Harga Satuan PCL:</label>
                                        <input class="form-control" type="text" name="nominalperSatuan"
                                            id="nominalperSatuan" type-currency="IDR" placeholder="Rp" required>
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="totalAnggaranKegiatan">Total
                                            Anggaran:</label>
                                        <input class="form-control" type="text" name="totalAnggaranKegiatan"
                                            id="totalAnggaranKegiatan" type-currency="IDR" placeholder="Rp" readonly>
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="jumlahPetugasKegiatan">Jumlah
                                            Petugas:</label>
                                        <input class="form-control" type="number" name="jumlahPetugasKegiatan"
                                            id="jumlahPetugasKegiatan" placeholder="Masukkan Jumlah Petugas"
                                            min="0" required>
                                        <button type="button" class="btn btn-sm btn-primary ml-2"
                                            id="btnSimulasiHonorPerMitra">Simulasikan Honor</button>
                                        <div class="col-md-3">
                                            <input class="form-control" type="text" name="simulasiTotalHonorPerMitra"
                                                id="simulasiTotalHonorPerMitra" type-currency="IDR" placeholder="Rp"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="form-group flex-group mb-4">
                                        <label class="col-sm-4 required" for="periodePencairanAnggaran">Periode Pencairan
                                            Honor:</label>
                                        <select class="form-select" aria-label="Default select example"
                                            name="periodePencairanAnggaran" id="periodePencairanAnggaran" required>
                                            <option selected muted disabled>Pilih Periode Pencairan
                                            </option>
                                            <option value="Januari">Januari</option>
                                            <option value="Februari">Februari</option>
                                            <option value="Maret">Maret</option>
                                            <option value="April">April</option>
                                            <option value="Mei">Mei</option>
                                            <option value="Juni">Juni</option>
                                            <option value="Juli">Juli</option>
                                            <option value="Agustus">Agustus</option>
                                            <option value="September">September</option>
                                            <option value="Oktober">Oktober</option>
                                            <option value="November">November</option>
                                            <option value="Desember">Desember</option>
                                        </select>
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="bebanAnggaranKegiatan">KRO:</label>
                                        <select class="form-select" name="bebanAnggaranKegiatan"
                                            id="bebanAnggaranKegiatan" required></select>
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
                @foreach ($semua_kegiatan as $kegiatan)
                    <div class="modal fade" id="editDaftarKegiatan{{ $kegiatan->id }}" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header mb-3">
                                    <h5 class="modal-title col-sm-6" id="exampleModalLabel">Form Tambah Kegiatan
                                        Survei/Sensus
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ url('/edit_data_kegiatan', $kegiatan->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group flex-group">
                                            <label class="col-sm-4 required" for="namaKegiatan">Nama Kegiatan
                                                Survei/Sensus:</label>
                                            <input class="form-control" type="text" name="namaKegiatan"
                                                id="namaKegiatan" value="{{ $kegiatan->daftar_kegiatan_survei }}">
                                        </div>
                                        <div class="form-group flex-group">
                                            <label class="col-sm-4 required">Periode Kegiatan:</label>
                                            <div class="input-group input-daterange">
                                                <input type="text" class="form-control" id="periodeKegiatanAwal"
                                                    name="periodeKegiatanAwal" placeholder="Kegiatan Dimulai"
                                                    aria-describedby="periodeAwal" autocomplete="off"
                                                    value="{{ $kegiatan->waktu_mulai }}">
                                                <div class="input-group-text">s/d</div>
                                                <input type="text" class="form-control" id="periodeKegiatanAkhir"
                                                    name="periodeKegiatanAkhir" placeholder="Kegiatan Berakhir"
                                                    aria-describedby="periodeAkhir" autocomplete="off"
                                                    value="{{ $kegiatan->waktu_berakhir }}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="periodeAwal"><i
                                                            class="fa-regular fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group flex-group">
                                            <label class="col-sm-4 required" for="jenisKegiatan">Jenis Kegiatan:</label>
                                            <select class="form-select" aria-label="Default select example"
                                                name="jenisKegiatan" id="jenisKegiatan">
                                                <option selected muted disabled>Pilih Jenis Kegiatan
                                                </option>
                                                <option value="Pendataan"
                                                    {{ $kegiatan->jenis_kegiatan == 'Pendataan' ? 'selected' : '' }}>
                                                    Pendataan</option>
                                                <option value="Pengolahan"
                                                    {{ $kegiatan->jenis_kegiatan == 'Pengolahan' ? 'selected' : '' }}>
                                                    Pengolahan</option>
                                            </select>
                                        </div>
                                        <div class="form-group flex-group">
                                            <label class="col-sm-4 required" for="jenisPembayaran">Jenis Pembayaran
                                                Mitra:</label>
                                            <select class="form-select" aria-label="Default select example"
                                                name="jenisPembayaran" id="jenisPembayaran">
                                                <option selected muted disabled>Pilih Jenis Pembayaran
                                                </option>
                                                <option value="Dokumen"
                                                    {{ $kegiatan->jenis_pembayaran == 'Dokumen' ? 'selected' : '' }}>
                                                    Dokumen</option>
                                                <option value="Blok Sensus"
                                                    {{ $kegiatan->jenis_pembayaran == 'Blok Sensus' ? 'selected' : '' }}>
                                                    Blok Sensus</option>
                                                <option value="Blok Sensus"
                                                    {{ $kegiatan->jenis_pembayaran == 'Segmen' ? 'selected' : '' }}>
                                                    Segmen</option>
                                                <option value="Orang Bulan"
                                                    {{ $kegiatan->jenis_pembayaran == 'Orang Bulan' ? 'selected' : '' }}>
                                                    O-B</option>
                                            </select>
                                        </div>
                                        <div class="form-group flex-group">
                                            <label class="col-sm-4 required" for="jumlahSatuan">Volume:</label>
                                            <input class="form-control" type="number" name="jumlahSatuan"
                                                id="jumlahSatuan" placeholder="Masukan Jumlah Satuan" autocomplete="off"
                                                value="{{ $kegiatan->jumlah_satuan }}">
                                        </div>
                                        <div class="form-group flex-group">
                                            <label class="col-sm-4 required" for="nominalperSatuan">Harga Satuan:</label>
                                            <input class="form-control" type="text" name="nominalperSatuan"
                                                id="nominalperSatuan" type-currency="IDR" placeholder="Rp"
                                                value="{{ $kegiatan->nominal_per_satuan }}">
                                        </div>
                                        <div class="form-group flex-group mb-4">
                                            <label class="col-sm-4 required" for="periodePencairanAnggaran">Periode
                                                Pencairan
                                                Honor:</label>
                                            <select class="form-select" aria-label="Default select example"
                                                name="periodePencairanAnggaran" id="periodePencairanAnggaran">
                                                <option selected muted disabled>Pilih Periode Pencairan
                                                </option>
                                                <option value="Januari"
                                                    {{ $kegiatan->periode_pencairan_honor == 'Januari' ? 'selected' : '' }}>
                                                    Januari</option>
                                                <option value="Februari"
                                                    {{ $kegiatan->periode_pencairan_honor == 'Februari' ? 'selected' : '' }}>
                                                    Februari</option>
                                                <option value="Maret"
                                                    {{ $kegiatan->periode_pencairan_honor == 'Maret' ? 'selected' : '' }}>
                                                    Maret</option>
                                                <option value="April"
                                                    {{ $kegiatan->periode_pencairan_honor == 'April' ? 'selected' : '' }}>
                                                    April</option>
                                                <option value="Mei"
                                                    {{ $kegiatan->periode_pencairan_honor == 'Mei' ? 'selected' : '' }}>
                                                    Mei</option>
                                                <option value="Juni"
                                                    {{ $kegiatan->periode_pencairan_honor == 'Juni' ? 'selected' : '' }}>
                                                    Juni</option>
                                                <option value="Juli"
                                                    {{ $kegiatan->periode_pencairan_honor == 'Juli' ? 'selected' : '' }}>
                                                    Juli</option>
                                                <option value="Agustus"
                                                    {{ $kegiatan->periode_pencairan_honor == 'Agustus' ? 'selected' : '' }}>
                                                    Agustus</option>
                                                <option value="September"
                                                    {{ $kegiatan->periode_pencairan_honor == 'September' ? 'selected' : '' }}>
                                                    September</option>
                                                <option value="Oktober"
                                                    {{ $kegiatan->periode_pencairan_honor == 'Oktober' ? 'selected' : '' }}>
                                                    Oktober</option>
                                                <option value="November"
                                                    {{ $kegiatan->periode_pencairan_honor == 'November' ? 'selected' : '' }}>
                                                    November</option>
                                                <option value="Desember"
                                                    {{ $kegiatan->periode_pencairan_honor == 'Desember' ? 'selected' : '' }}>
                                                    Desember</option>
                                            </select>
                                        </div>
                                        <div class="form-group flex-group">
                                            <label class="col-sm-4 required" for="bebanAnggaranKegiatan">Beban Anggaran
                                                Kegiatan:</label>
                                            <input class="form-control" type="text" name="bebanAnggaranKegiatan"
                                                id="bebanAnggaranKegiatan" value="{{ $kegiatan->kode_beban_anggaran }}">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary"><i
                                                    class="fa-solid fa-floppy-disk pr-1"></i>Edit Data Kegiatan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @push('daftarKegiatanBebanAnggaran')
                        <script>
                            $('#jenisPembayaran').change(function() {
                                if ($('#jenisPembayaran').val() == 'Orang Bulan') {
                                    $('#jumlahPetugasKegiatan').attr('readonly', true);
                                } else {
                                    $('#jumlahPetugasKegiatan').removeAttr('readonly');
                                }
                            });

                            $('#jumlahSatuan').change(function() {
                                if ($('#jenisPembayaran').val() == 'Orang Bulan') {
                                    $('#jumlahPetugasKegiatan').val(parseInt($('#jumlahSatuan').val()));
                                } else {
                                    $('#jumlahPetugasKegiatan').val('');
                                }
                            })

                            $('body').on('shown.bs.modal', '#editDaftarKegiatan{{ $kegiatan->id }}', function() {
                                $(this).find('select[name="namaKegiatan"]').each(function() {
                                    $(this).select2({
                                        theme: 'bootstrap-5',
                                        placeholder: 'Masukkan Nama Kegiatan',
                                        dropdownParent: $('#editDaftarKegiatan{{ $kegiatan->id }}'),
                                        ajax: {
                                            url: "initializeSurvei",
                                            processResults: function({
                                                data
                                            }) {
                                                return {
                                                    results: $.map(data, function(item) {
                                                        return {
                                                            id: item.daftar_kegiatan_survei,
                                                            text: item.daftar_kegiatan_survei
                                                        }
                                                    })
                                                }
                                            }
                                        }
                                    });
                                });
                            });
                            $('body').on('shown.bs.modal', '#editDaftarKegiatan{{ $kegiatan->id }}', function() {
                                $(this).find('select[name="bebanAnggaranKegiatan"]').each(function() {
                                    $(this).select2({
                                        theme: 'bootstrap-5',
                                        placeholder: 'Pilih Kode Komponen',
                                        dropdownParent: $('#editDaftarKegiatan{{ $kegiatan->id }}'),
                                        ajax: {
                                            url: "kode_beban_anggaran",
                                            processResults: function({
                                                data
                                            }) {
                                                return {
                                                    results: $.map(data, function(item) {
                                                        return {
                                                            id: item.kode,
                                                            text: "(" + item.kode + ") " + item
                                                                .jenis_komponen
                                                        }
                                                    })
                                                }
                                            }
                                        }
                                    });
                                });
                            });
                        </script>
                    @endpush
                @endforeach
            </div>
        </div>
    </div>
@endsection
