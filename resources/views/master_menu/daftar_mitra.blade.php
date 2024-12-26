@extends('new_home')

@section('container')
    <div class="container-fluid px4">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <h4 class="mt-4 mb-3">Daftar Seluruh Mitra</h4>
                @if (Auth::user()->is_admin)
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                        data-bs-target="#tambahDaftarMitra">
                        <i class="fa-solid fa-plus pr-1"></i>Tambah Mitra
                    </button>
                @endif
                <div class="row filter-bulan-mitra">
                    <div class="col-sm-6">
                        <div class="pilih-bulan-kegiatan mb-3">
                            <label class="required" for="periodeAlokasiBulanMitra">Bulan Pengalokasian:</label>
                            <select class="form-select" aria-label="Default select example" name="periodeAlokasiBulanMitra"
                                id="periodeAlokasiBulanMitra">
                                <option class="text-muted" selected disabled>Pilih Bulan Pengalokasian
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
                <div class="row justify-content-center alokasi-honor-mitra">
                    <div class="col-xl-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                <span class="mt-3">Daftar Mitra yang akan Dialokasikan Untuk Kegiatan Survei/Sensus</span>
                                <p class="total-dipilih float-right" hidden>Pengalokasian: <span
                                        id="banyak-mitra-dipilih"></span> orang dipilih dari <span
                                        id="total-mitra-keseluruhan"></span></p>
                            </div>
                            <div class="card-body">
                                <form name="frm-example" id="frm-example" autocomplete="off">
                                    @csrf
                                    <table id="daftar-mitra-all"
                                        class="table table-striped table-bordered display dtr-inline collapsed"
                                        style="width:100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nama Mitra</th>
                                                <th>Alamat</th>
                                                <th>Status O-B</th>
                                                <th>Posisi Kecamatan</th>
                                                <th>Jenis Mitra</th>
                                                <th>Jenis Kelamin</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($semua_mitra as $mitra_each)
                                                <tr>
                                                    <td>{{ $mitra_each->id }}</td>
                                                    <td>{{ $mitra_each->nama }}</td>
                                                    <td>{{ $mitra_each->alamat_detail }}</td>
                                                    <td>
                                                        @if (isset($id_mitra_ob))
                                                            @if (in_array($mitra_each->id, $id_mitra_ob))
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="flexCheckChecked" checked
                                                                        disabled>
                                                                </div>
                                                            @else
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="flexCheckChecked" disabled>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="" id="flexCheckChecked" disabled>
                                                            </div>
                                                        @endif

                                                    </td>
                                                    <td>{{ $mitra_each->id_kec == '1278010'
                                                        ? '(' . $mitra_each->id_kec . ') Gunungsitoli Idanoi'
                                                        : ($mitra_each->id_kec == '1278020'
                                                            ? '(' . $mitra_each->id_kec . ') Gunungsitoli Selatan'
                                                            : ($mitra_each->id_kec == '1278030'
                                                                ? '(' . $mitra_each->id_kec . ') Gunungsitoli Barat'
                                                                : ($mitra_each->id_kec == '1278040'
                                                                    ? '(' . $mitra_each->id_kec . ') Gunungsitoli'
                                                                    : ($mitra_each->id_kec == '1278050'
                                                                        ? '(' . $mitra_each->id_kec . ") Gunungsitoli Alo'oa"
                                                                        : '(' . $mitra_each->id_kec . ') Gunungsitoli Utara')))) }}
                                                    <td>{{ $mitra_each->posisi }}</td>
                                                    <td>{{ $mitra_each->jenis_kelamin == 0 ? 'Perempuan' : 'Laki-Laki' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-outline-success mt-2" id="btn-kirim-data-mitra"
                                        hidden>Lakukan Pengalokasian dengan BS</button>
                                    <a href="{{ url('/upload_sample_bs') }}" class="btn btn-outline-primary mt-2 ml-2"
                                        id="btn-alokasikan-tanpa-bs" hidden>Lakukan Pengalokasian tanpa BS</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="tambahDaftarMitra" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header mb-3">
                                <h5 class="modal-title col-sm-6" id="exampleModalLabel">Form Tambah Mitra</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ url('/tambah_daftar_mitra') }}" method="POST">
                                    @csrf
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="namaKegiatanTanpaBS">Nama Kegiatan:</label>
                                        <select class="form-select" name="namaKegiatanTanpaBS"
                                            id="namaKegiatanTanpaBS"></select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="tambahDaftarMitra" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header mb-3">
                                <h5 class="modal-title col-sm-6" id="exampleModalLabel">Form Tambah Mitra</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ url('/tambah_daftar_mitra') }}" method="POST">
                                    @csrf
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="namaMitra">Nama Mitra:</label>
                                        <input class="form-control" type="text" name="namaMitra" id="namaMitra"
                                            placeholder="Masukkan Nama Mitra">
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="jenisKegiatanMitra">Jenis Kegiatan:</label>
                                        <select class="form-select" name="jenisKegiatanMitra"
                                            id="jenisKegiatanMitra"></select>
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="longitudeMitra">Longitude:</label>
                                        <input class="form-control" type="text" name="longitudeMitra"
                                            id="longitudeMitra" placeholder="Masukkan Posisi Longitude">
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="latitudeMitra">Latitude:</label>
                                        <input class="form-control" type="text" name="latitudeMitra"
                                            id="latitudeMitra" placeholder="Masukkan Posisi Latitude">
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="jenisKelamin">Jenis Kelamin:</label>
                                        <select class="form-select" name="jenisKelamin" id="jenisKelamin"></select>
                                    </div>
                                    <div class="form-group flex-group">
                                        <label class="col-sm-4 required" for="alamatLengkapMitra">Alamat Mitra:</label>
                                        <textarea class="form-control" name="alamatLengkapMitra" id="alamatLengkapMitra"
                                            placeholder="Masukkan Alamat Lengkap"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary"><i
                                                class="fa-solid fa-floppy-disk pr-1"></i>Tambah Mitra</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @push('IDMitraDipilih')
                    <script>
                        let test = [<?php echo '"' . implode('","', $id_mitra_ob) . '"'; ?>]
                        let mitra_table = $('#daftar-mitra-all').DataTable({
                            select: true,
                            responsive: true,
                            initComplete: function(settings) {
                                var api = this.api();

                                api.cells(
                                    api.rows(function(idx, data, node) {
                                        return (test.includes(data[0])) ? true : false;
                                    }).indexes(),
                                    0
                                ).checkboxes.disable();
                            },
                            columnDefs: [{
                                    width: 200,
                                    targets: [2]
                                },
                                {
                                    className: "dt-head-center",
                                    targets: '_all'
                                },
                                {
                                    className: "dt-body-center",
                                    targets: [3, 4, 5]
                                },
                                {
                                    'targets': 0,
                                    'render': function(data, type, row, meta) {
                                        if (type === 'display') {
                                            data =
                                                '<div class="form-check"><input type="checkbox" class="form-check-input dt-checkboxes" id="check-alokasi-mitra"></div>';
                                        }

                                        return data;
                                    },
                                    'checkboxes': {
                                        'selectRow': true,
                                        'selectAllRender': '<div class="form-check"><input type="checkbox" class="form-check-input dt-checkboxes" id="check-alokasi-mitra"></div>'
                                    }
                                },
                            ],
                            // 'stateSave': true,
                            'select': {
                                'style': 'multi'
                            },
                        });
                        $('#periodeAlokasiBulanMitra').change(function() {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });

                            $.ajax({
                                url: '/daftar_mitra',
                                data: {
                                    filter_bulan: $('#periodeAlokasiBulanMitra').val()
                                },
                                type: "POST",
                                dataType: "json",
                                success: function(result) {
                                    $('#daftar-mitra-all').DataTable().destroy();
                                    let filtered_mitra_table = $('#daftar-mitra-all').DataTable({
                                        select: true,
                                        responsive: true,
                                        data: result[0],
                                        initComplete: function(settings) {
                                            var api = this.api();

                                            api.cells(
                                                api.rows(function(idx, data, node) {
                                                    if (result[1].length != 0) {
                                                        return (result[1].includes(data['id'])) ?
                                                            true : false;
                                                    }
                                                }).indexes(),
                                                0
                                            ).checkboxes.disable();
                                        },
                                        columnDefs: [{
                                                width: 200,
                                                targets: [2]
                                            },
                                            {
                                                className: "dt-head-center",
                                                targets: '_all'
                                            },
                                            {
                                                className: "dt-body-center",
                                                targets: [3, 4, 5]
                                            },
                                            {
                                                'targets': 0,
                                                'render': function(data, type, row, meta) {
                                                    if (type === 'display') {
                                                        data =
                                                            '<div class="form-check"><input type="checkbox" class="form-check-input dt-checkboxes" id="check-alokasi-mitra"></div>';
                                                    }

                                                    return data;
                                                },
                                                'checkboxes': {
                                                    'selectRow': true,
                                                    'selectAllRender': '<div class="form-check"><input type="checkbox" class="form-check-input dt-checkboxes" id="check-alokasi-mitra"></div>'
                                                }
                                            },
                                        ],
                                        // 'stateSave': true,
                                        'select': {
                                            'style': 'multi'
                                        },
                                        columns: [{
                                                data: 'id'
                                            },
                                            {
                                                data: 'nama'
                                            },
                                            {
                                                data: 'alamat_detail'
                                            },
                                            {
                                                data: null,
                                                render: function(data, type, row) {
                                                    if (result[1].length != 0) {
                                                        if (result[1].includes(row.id)) {
                                                            return '<div class="form-check">' +
                                                                '<input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked disabled>' +
                                                                '</div>'
                                                        } else {
                                                            return '<div class="form-check">' +
                                                                '<input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" disabled>' +
                                                                '</div>'
                                                        }
                                                    } else {
                                                        return '<div class="form-check">' +
                                                            '<input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" disabled>' +
                                                            '</div>'
                                                    }
                                                }
                                            },
                                            {
                                                data: null,
                                                render: function(data, type, row) {
                                                    if (row.id_kec == '1278010') {
                                                        return '(' + row.id_kec +
                                                            ') Gunungsitoli Idanoi'
                                                    } else if (row.id_kec == '1278020') {
                                                        return '(' + row.id_kec +
                                                            ') Gunungsitoli Selatan'
                                                    } else if (row.id_kec == '1278030') {
                                                        return '(' + row.id_kec + ') Gunungsitoli Barat'
                                                    } else if (row.id_kec == '1278040') {
                                                        return '(' + row.id_kec + ') Gunungsitoli'
                                                    } else if (row.id_kec == '1278050') {
                                                        return '(' + row.id_kec +
                                                            ") Gunungsitoli Alo'oa"
                                                    } else {
                                                        return '(' + row.id_kec + ') Gunungsitoli Utara'
                                                    }
                                                }
                                            },
                                            {
                                                data: 'posisi'
                                            },
                                            {
                                                data: null,
                                                render: function(data, type, row) {
                                                    if (row.jenis_kelamin == 0) {
                                                        return 'Perempuan'
                                                    } else {
                                                        return 'Laki-Laki'
                                                    }
                                                }
                                            },
                                        ]
                                    });

                                    $('#daftar-mitra-all').on('change', '.dt-checkboxes', function(e) {
                                        var checkAll = filtered_mitra_table.rows().nodes().to$().find(
                                            'input[type="checkbox"][id="check-alokasi-mitra"]');
                                        var checked = checkAll.filter(':checked').length;
                                        let total = checkAll.length;
                                        if (checked != 0) {
                                            $('#banyak-mitra-dipilih').html(checked)
                                            $('#total-mitra-keseluruhan').html(total)
                                            $('.total-dipilih').removeAttr('hidden')
                                            $('#btn-kirim-data-mitra').removeAttr('hidden')
                                            $('#btn-alokasikan-tanpa-bs').removeAttr('hidden')
                                        } else if (checked == 0) {
                                            $('.total-dipilih').attr('hidden', true)
                                            $('#btn-kirim-data-mitra').attr('hidden', true)
                                            $('#btn-alokasikan-tanpa-bs').attr('hidden', true)
                                        }
                                    });

                                    $('#frm-example').on('submit', function(e) {
                                        e.preventDefault();
                                        var form = this;

                                        var rows_selected = filtered_mitra_table.columns().checkboxes
                                            .selected()[0];
                                        if (rows_selected.some(r => result[1].includes(r))) {
                                            Swal.fire({
                                                icon: "error",
                                                title: "Oops...",
                                                html: "Ada mitra yang <strong>sudah dialokasikan O-B!</strong>",
                                            })
                                        } else {
                                            Cookies.set('ids', JSON.stringify(rows_selected), {
                                                expires: 365
                                            });
                                            Cookies.set('bulan-alokasi', $('#periodeAlokasiBulanMitra').val(), {
                                                expires: 365
                                            });
                                            Cookies.set('jumlah-mitra-terpilih', rows_selected.length, {
                                                expires: 365
                                            });
                                            window.location.href =
                                                'http://127.0.0.1:8000/upload_sample_bs'
                                        };

                                    });
                                    $('#btn-alokasikan-tanpa-bs').click(function() {
                                        var rows_selected = filtered_mitra_table.columns().checkboxes
                                            .selected()[0];
                                        Cookies.set('tanpa-bs', true);
                                        Cookies.set('ids', JSON.stringify(rows_selected), {
                                            expires: 365
                                        });
                                        Cookies.set('bulan-alokasi', $('#periodeAlokasiBulanMitra').val(), {
                                            expires: 365
                                        });
                                        Cookies.set('jumlah-mitra-terpilih', rows_selected.length, {
                                            expires: 365
                                        });
                                    });
                                }
                            })
                        });
                    </script>
                @endpush
            </div>
        </div>
    </div>
@endsection
