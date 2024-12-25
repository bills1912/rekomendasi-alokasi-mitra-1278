/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
// 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});

$('#mitra-survei-teralokasi').DataTable({
    responsive: true,
    columnDefs: [{
        width: 230,
        targets: [0]
    },
    {
        width: 330,
        targets: [2]
    },
    {
        width: 180,
        targets: [1, 3]
    },
    {
        className: "dt-head-center",
        targets: '_all'
    },
    {
        className: "dt-body-center",
        targets: [2, 3, 4]
    },
    ]
});

$('#filterKegiatanAlokasiMitra').change(function () {
    $('.nama-kegiatan-terpilih').html($('#filterKegiatanAlokasiMitra').val())
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/list_mitra_survei_teralokasi',
        data: {
            filter_kegiatan: $('#filterKegiatanAlokasiMitra').val()
        },
        type: "POST",
        dataType: "json",
        success: function (result) {
            $('#mitra-survei-teralokasi').DataTable().destroy();
            $('#mitra-survei-teralokasi').DataTable({
                data: result[0],
                responsive: true,
                columnDefs: [{
                    width: 230,
                    targets: [0]
                },
                {
                    width: 330,
                    targets: [2]
                },
                {
                    width: 180,
                    targets: [1, 3]
                },
                {
                    className: "dt-head-center",
                    targets: '_all'
                },
                {
                    className: "dt-body-center",
                    targets: [2, 3, 4]
                },
                ],
                columns: [{
                    data: "nama"
                },
                {
                    data: "alamat_detail"
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return result[2]
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        let text = "";
                        if (result[1].length != 0) {
                            for (let i = 0; i < result[1][row.id]
                                .length; i++) {
                                text += "<li>" + result[1][row.id][i] + "</li>"
                            }
                            return text
                        } else {
                            return 'Tanpa Blok Sensus'
                        }
                    }
                },
                {
                    data: null,
                    render: function (data, type, row) {
                        return '<button type="button" class="btn btn-outline-primary btn-sm editDataMitraSurveiTeralokasi"' +
                            'style="position:inline" data-bs-toggle="modal" data-bs-target="#exampleModalEdit' +
                            row.id +
                            '"><i class="fa-regular fa-pen-to-square"></i></button>' +
                            '<a href="/list_mitra_survei_teralokasi/' + row
                                .id +
                            '"class="btn btn-outline-danger hapusDataMitraSurveiTeralokasi btn-sm"><i class="fa-regular fa-trash-can"></i></a>'
                    }
                },
                ]
            });
        }
    });
});

$('#mitra-survei-teralokasi').on('click', '.hapusDataMitraSurveiTeralokasi', function (e) {
    e.preventDefault();
    // alert('halo');
    let href = $(this).attr('href');
    Swal.fire({
        title: "Hapus Data!",
        text: "Apakah kamu yakin ingin menghapus data?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, hapus data",
        cancelButtonText: "Tidak",
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = href;
        }
    });
});

$('#filterKegiatanAlokasiMitra').select2({
    theme: 'bootstrap-5',
    placeholder: 'Pilih Kegiatan',
    ajax: {
        url: "initializeSurvei",
        processResults: function ({ data }) {
            return {
                results: $.map(data, function (item) {
                    return {
                        id: item.daftar_kegiatan_survei,
                        text: item.daftar_kegiatan_survei
                    }
                })
            }
        }
    }
});

$('#periodeAlokasiKegiatan').select2({
    theme: 'bootstrap-5',
    placeholder: 'Pilih Bulan Kegiatan',
});