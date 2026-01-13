@extends('new_home')

@section('container')
    <div class="container-fluid px4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h4 class="mt-4 mb-3">Rekap Honor Mitra</h4>
                <div class="pilih-filter-rekap-honor mb-3">
                    <div class="form-check-inline ml-0 required">
                        Pilih filter rekap honor mitra:
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1"
                            value="filter-by-month-radios">
                        <label class="form-check-label" for="exampleRadios1">
                            Berdasarkan bulan kegiatan
                        </label>
                    </div>
                </div>
                <div class="row filter-bulan-kegiatan" hidden>
                    <div class="col-sm-6">
                        <div class="pilih-bulan-kegiatan mb-3">
                            <label class="required" for="periodeAlokasiKegiatan">Periode Bulan Kegiatan:</label>
                            <select class="form-select" aria-label="Default select example" name="periodeAlokasiKegiatan"
                                id="periodeAlokasiKegiatan">
                                <option class="text-muted" selected disabled>Pilih Bulan Kegiatan
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
                    </div>
                </div>
                <div class="row filter-nama-kegiatan mb-3" hidden>
                    <div class="col-sm-6">
                        <label class="required" for="rekapHonorPerKegiatan">Kegiatan:</label>
                        <select class="form-select" name="rekapHonorPerKegiatan" id="rekapHonorPerKegiatan"></select>
                    </div>
                </div>
                <div class="row justify-content-center alokasi-honor-mitra">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                <span class="mt-3">Rekapitulasi Alokasi Honor Mitra Selama Bulan Januari Tahun 2024</span>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#tetapkanBulanSPK"
                                    class="btn btn-sm btn-primary btn-surtug float-right mr-2"><i
                                        class="fa-solid fa-print mr-1"></i>Cetak SPK</button>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#tetapkanNomorTanggalBAST"
                                    class="btn btn-sm btn-primary btn-surtug float-right mr-2"><i
                                        class="fa-solid fa-print mr-1"></i>Cetak BAST</button>
                            </div>
                            <div class="card-body">
                                <table id="rekap-honor-all"
                                    class="table table-striped table-bordered display dtr-inline collapsed"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Mitra</th>
                                            <th>Jenis Mitra</th>
                                            <th>Kegiatan yang Diikuti</th>
                                            <th>Total Honor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($semua_mitra as $mitra_each)
                                            <tr>
                                                <td>{{ $mitra_each->id }}</td>
                                                <td>{{ $mitra_each->nama }}</td>
                                                <td>{{ $mitra_each->posisi }}</td>
                                                <td>
                                                    @if (isset($kegiatan_diikuti[$mitra_each->id]))
                                                        @foreach (explode(';', $kegiatan_diikuti[$mitra_each->id]) as $row)
                                                            <li>{{ $row }}</li>
                                                        @endforeach
                                                    @else
                                                        {{ 'Belum dialokasikan ke survei/sensus' }}
                                                    @endif
                                                </td>
                                                <td>{{ !isset($total_honor_mitra[$mitra_each->id]) ? 'Honor Belum Dialokasikan' : 'Rp' . number_format($total_honor_mitra[$mitra_each->id], 0, ',', '.') . ',-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="tetapkanNomorTanggalBAST" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title col-sm-10" id="exampleModalLabel">Form Submit Nomor Tanggal BAST</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ url('/generate_surat_bast') }}" method="POST">
                                    @csrf
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="copyTanggalSPKinBAST">Tanggal
                                            Surat SPK:</label>
                                        <input class="form-control" type="text" name="copyTanggalSPKinBAST"
                                            id="copyTanggalSPKinBAST" placeholder="Tanggal SPK">
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="copyTanggalKegiatanBerakhir">Tanggal
                                            BAST:</label>
                                        <input class="form-control" type="text" name="copyTanggalKegiatanBerakhir"
                                            id="copyTanggalKegiatanBerakhir" placeholder="Salin Tanggal Kegiatan Berakhir">
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="copyBulanKegiatanBerakhir">Bulan
                                            BAST:</label>
                                        <select class="form-select" aria-label="Default select example"
                                            name="copyBulanKegiatanBerakhir" id="copyBulanKegiatanBerakhir"
                                            aria-readonly="true">
                                            <option selected muted disabled>Pilih Bulan Kegiatan Berakhir
                                            </option>
                                            <option value="1">Januari</option>
                                            <option value="2">Februari</option>
                                            <option value="3">Maret</option>
                                            <option value="4">April</option>
                                            <option value="5">Mei</option>
                                            <option value="6">Juni</option>
                                            <option value="7">Juli</option>
                                            <option value="8">Agustus</option>
                                            <option value="9">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="tahunGenerateBAST">Tahun BAST:</label>
                                        <input class="form-control" type="text" name="tahunGenerateBAST"
                                            id="tahunGenerateBAST" value="{{ date('Y') }}" readonly>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary"><i
                                                class="fa-solid fa-floppy-disk pr-1"></i>Generate BAST</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="tetapkanBulanSPK" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title col-sm-10" id="exampleModalLabel">Form Submit Bulan SPK
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ url('/generate_surat_spk') }}" method="POST">
                                    @csrf
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="copyTanggalGenerateSPK">Tanggal
                                            SPK:</label>
                                        <input class="form-control" type="text" name="copyTanggalGenerateSPK"
                                            id="copyTanggalGenerateSPK" placeholder="Salin Tanggal Kegiatan Berakhir">
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="copyBulanSPK">Bulan
                                            SPK:</label>
                                        <select class="form-select" aria-label="Default select example"
                                            name="copyBulanSPK" id="copyBulanSPK" aria-readonly="true">
                                            <option selected muted disabled>Pilih Bulan SPK
                                            </option>
                                            <option value="1">Januari</option>
                                            <option value="2">Februari</option>
                                            <option value="3">Maret</option>
                                            <option value="4">April</option>
                                            <option value="5">Mei</option>
                                            <option value="6">Juni</option>
                                            <option value="7">Juli</option>
                                            <option value="8">Agustus</option>
                                            <option value="9">September</option>
                                            <option value="10">Oktober</option>
                                            <option value="11">November</option>
                                            <option value="12">Desember</option>
                                        </select>
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="tahunGenerateSPK">Tahun SPK:</label>
                                        <input class="form-control" type="text" name="tahunGenerateSPK"
                                            id="tahunGenerateSPK" value="{{ date('Y') }}" readonly>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary"><i
                                                class="fa-solid fa-floppy-disk pr-1"></i>Generate SPK</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('autofillRentangKegiatan')
        <script>
            let daftarSemuaKegiatan = {!! json_encode($daftar_semua_kegiatan) !!};
            let monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober",
                "November", "Desember"
            ];
            let monthDict = {
                "Januari": 1,
                "Februari": 2,
                "Maret": 3,
                "April": 4,
                "Mei": 5,
                "Juni": 6,
                "Juli": 7,
                "Agustus": 8,
                "September": 9,
                "Oktober": 10,
                "November": 11,
                "Desember": 12
            }
            $('#pilihKegiatanGenerateBAST').change(function() {
                monthNames.forEach(element => {
                    if (daftarSemuaKegiatan[$('#pilihKegiatanGenerateBAST').val()][1].includes(element)) {
                        $('#copyBulanKegiatanBerakhir').val(monthDict[element])
                    }
                });
                $('#periodeAwalKegiatanTerpilihBAST').val(daftarSemuaKegiatan[$('#pilihKegiatanGenerateBAST').val()][0])
                $('#periodeAkhirKegiatanTerpilihBAST').val(daftarSemuaKegiatan[$('#pilihKegiatanGenerateBAST').val()][
                    1
                ])
                $('#copyTanggalKegiatanBerakhir').val((daftarSemuaKegiatan[$('#pilihKegiatanGenerateBAST').val()][1]
                    .substr(0, 2)))
            });
        </script>
    @endpush
@endsection
