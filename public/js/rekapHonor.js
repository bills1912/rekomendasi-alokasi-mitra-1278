$('#rekapHonorPerKegiatan').select2({
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

$('#pilihKegiatanGenerateBAST').select2({
    theme: 'bootstrap-5',
    placeholder: 'Pilih Kegiatan',
    dropdownParent: $('#tetapkanNomorTanggalBAST'),
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

$('#rekap-honor-all').DataTable({
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'collection',
            text: 'Export',
            className: 'btn-primary',
            buttons: [
                { extend: 'copy', className: 'btn-primary' },
                { extend: 'csv', className: 'btn-primary' },
                { extend: 'excel', className: 'btn-primary' },
                { extend: 'pdf', className: 'btn-primary' },
                { extend: 'print', className: 'btn-primary' },
            ]
        }
    ],
    responsive: true,
    columnDefs: [
        { className: "dt-head-center", targets: '_all' },
        {
            'targets': 0,
            'render': function (data, type, row, meta) {
                if (type === 'display') {
                    data =
                        '<div class="form-check"><input type="checkbox" class="form-check-input dt-checkboxes"></div>';
                }

                return data;
            },
            'checkboxes': {
                'selectRow': true,
                'selectAllRender': '<div class="form-check"><input type="checkbox" class="form-check-input dt-checkboxes"></div>'
            }
        },
    ],
    'stateSave': true,
    'select': {
        'style': 'multi'
    },
});

$('input[type=radio][name=exampleRadios]').change(function () {
    if (this.value == 'filter-by-month-radios') {
        $('.filter-bulan-kegiatan').removeAttr('hidden');
        $('.filter-nama-kegiatan').attr('hidden', true);
    } else if (this.value == 'filter-by-event-radios') {
        $('.filter-nama-kegiatan').removeAttr('hidden');
        $('.filter-bulan-kegiatan').attr('hidden', true);
    }
});

$('#periodeAlokasiKegiatan').change(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '/daftar_ringkasan_honor',
        data: {
            filter_bulan: $('#periodeAlokasiKegiatan').val()
        },
        type: "POST",
        dataType: "json",
        success: function (result) {
            $('#rekap-honor-all').DataTable().destroy();
            $('#rekap-honor-all').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'collection',
                        text: 'Export',
                        className: 'btn-primary',
                        buttons: [
                            { extend: 'copy', className: 'btn-primary' },
                            { extend: 'csv', className: 'btn-primary' },
                            { extend: 'excel', className: 'btn-primary' },
                            { extend: 'pdf', className: 'btn-primary' },
                            { extend: 'print', className: 'btn-primary' },
                        ]
                    }
                ],
                data: result[1],
                responsive: true,
                columnDefs: [
                    { className: "dt-head-center", targets: '_all' },
                    {
                        'targets': 0,
                        'render': function (data, type, row, meta) {
                            if (type === 'display') {
                                data =
                                    '<div class="form-check"><input type="checkbox" class="form-check-input dt-checkboxes"></div>';
                            }

                            return data;
                        },
                        'checkboxes': {
                            'selectRow': true,
                            'selectAllRender': '<div class="form-check"><input type="checkbox" class="form-check-input dt-checkboxes"></div>'
                        }
                    },
                ],

                columns: [
                    { data: 'id' },
                    { data: 'nama' },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return result[3][row.id]
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            let text = "";
                            if (result[0].length != 0) {
                                for (let i = 0; i < result[0][row.id]
                                    .length; i++) {
                                    text += "<li>" + result[0][row.id][i] + "</li>"
                                }
                                return text
                            } else {
                                return 'Belum dialokasikan ke survei/sensus'
                            }
                        }
                    },
                    {
                        data: null,
                        render: function (data, type, row) {
                            return RupiahCurrency.format(result[2][row.id]);
                        }
                    },
                ],
                'stateSave': true,
                'select': {
                    'style': 'multi'
                },
            });
        },
    })
});

$('#rekapHonorPerKegiatan').change(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '/daftar_ringkasan_honor',
        data: {
            filter_kegiatan_rekap_honor: $('#rekapHonorPerKegiatan').val()
        },
        type: "POST",
        dataType: "json",
        success: function (result) {
            console.log(result)
        },
    })
});

$.fn.datepicker.dates['id'] = {
    days: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"],
    daysShort: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
    daysMin: ["Mi", "Se", "Se", "Ra", "Ka", "Ju", "Sa"],
    months: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
    monthsShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
    today: "Hari Ini",
    clear: "Bersihkan",
    format: "dd MM yyyy",
    titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
    weekStart: 0
};

$('#nomorTanggalSurat').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: "dd.mm",
    todayBtn: "linked",
    language: "id",
});