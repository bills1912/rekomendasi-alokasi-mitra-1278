let RupiahCurrency = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR'
})
$(document).ready(function () {
    $('#namaKegiatanTanpaBS').select2({
        theme: 'bootstrap-5',
        tags: true,
        placeholder: 'Masukkan Nama Kegiatan',
        dropdownParent: $('#tambahDaftarKegiatan'),
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

    $('#namaKegiatan').select2({
        theme: 'bootstrap-5',
        tags: true,
        placeholder: 'Masukkan Nama Kegiatan',
        dropdownParent: $('#tambahDaftarKegiatan'),
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

    $('#bebanAnggaranKegiatan').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih KRO',
        dropdownParent: $('#tambahDaftarKegiatan'),
        ajax: {
            url: "kode_beban_anggaran",
            processResults: function ({ data }) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            id: item.kode,
                            text: "(" + item.kode + ") " + item.jenis_komponen
                        }
                    })
                }
            }
        }
    });

    $('#jenisKegiatanMitra').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Jenis Kegiatan Mitra',
        dropdownParent: $('#tambahDaftarMitra'),
        ajax: {
            url: "jenisKegiatanMitra",
            processResults: function ({ data }) {
                console.log(data)
                return {
                    results: $.map(data, function (item) {
                        return {
                            id: item.posisi,
                            text: item.posisi
                        }
                    })
                }
            }
        }
    });

    $('#jenisKelamin').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Jenis Kelamin Mitra',
        dropdownParent: $('#tambahDaftarMitra'),
        ajax: {
            url: "jenisKelamin",
            processResults: function ({ data }) {
                console.log(data)
                return {
                    results: $.map(data, function (item) {
                        return {
                            id: item.jenis_kegiatan,
                            text: item.jenis_kegiatan
                        }
                    })
                }
            }
        }
    });

    $('#namaKegiatanSBML').select2({
        theme: 'bootstrap-5',
        tags: true,
        placeholder: 'Pilih Nama Kegiatan',
        dropdownParent: $('#tambahDaftarSBML'),
        ajax: {
            url: "namaKegiatanSBML",
            processResults: function ({ data }) {
                console.log(data)
                return {
                    results: $.map(data, function (item) {
                        return {
                            id: item.id,
                            text: item.daftar_kegiatan_survei
                        }
                    })
                }
            }
        }
    });

    $('#namaKegiatanSBML').select2({
        theme: 'bootstrap-5',
        tags: true,
        placeholder: 'Pilih Nama Kegiatan',
        dropdownParent: $('#tambahDaftarSBML'),
        ajax: {
            url: "namaKegiatanSBML",
            processResults: function ({ data }) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            id: item.id,
                            text: item.daftar_kegiatan_survei
                        }
                    })
                }
            }
        }
    });
});

$('#periodeAlokasiBulanMitra').select2({
    theme: 'bootstrap-5'
});



$('#daftar-status-alokasi-kegiatan').DataTable({
    columnDefs: [
        {
            className: "dt-head-center",
            targets: '_all'
        },
        {
            className: "dt-body-center",
            targets: [1, 2]
        },
    ]
});

$('#btn-sbml-yearly').click(function () {
    $('#namaKegiatanSBML').attr('disabled', true);
    $('#namaKegiatanSBML').val(null).trigger('change');
    $('#jenisKegiatanSBML').val('');
    $('#jenisKegiatanSBML').removeAttr('readonly');
    $('#periodeAwalSBML').val('');
    $('#periodeAkhirSBML').val('');
});

$('#btn-sbml-monthly').click(function () {
    $('#namaKegiatanSBML').removeAttr('disabled')
    $('#jenisKegiatanSBML').attr('readonly', true);
});

$('#daftar-mitra-all').on('change', '.dt-checkboxes', function (e) {
    var checkAll = mitra_table.rows().nodes().to$().find('input[type="checkbox"][id="check-alokasi-mitra"]');
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

$('#jenisKegiatan').on('change', function () {
    if ($(this).val() == "Pendataan + Pengolahan") {
        $('#formNominalPerSatuanPengolahan').removeAttr('hidden');
        $('#formNominalPerSatuanPML').attr('hidden', true);
    } else if ($(this).val() == "Pendataan + PML Mitra") {
        $('#formNominalPerSatuanPML').removeAttr('hidden');
        $('#formNominalPerSatuanPengolahan').attr('hidden', true);
    } else if ($(this).val() == "Pendataan + Pengolahan + PML Mitra") {
        $('#formNominalPerSatuanPengolahan').removeAttr('hidden');
        $('#formNominalPerSatuanPML').removeAttr('hidden');
    } else {
        $('#formNominalPerSatuanPengolahan').attr('hidden', true);
        $('#formNominalPerSatuanPML').attr('hidden', true);
    }
});

$('#nominalperSatuan').on('change', function () {
    $('#totalAnggaranKegiatan').val(RupiahCurrency.format(parseInt($('#jumlahSatuan').val()) * (
        (parseFloat($('#nominalperSatuan').val().replace(/\./g, '').replace(/^\D+/g, ''))).toFixed(3)
    )))
});


$('#btnSimulasiHonorPerMitra').on('click', function () {
    $('#simulasiTotalHonorPerMitra').val(RupiahCurrency.format(parseFloat($('#totalAnggaranKegiatan').val().replace('.', '').replace('.', '')
        .replace(',', '.').replace(/^\D+/g, '')) / $('#jumlahPetugasKegiatan').val()))
});

$('#btn-bersihkan-checkbox').click(function () {
    mitra_table.rows().nodes().to$().find('input[type="checkbox"]').filter(':checked').removeAttr('checked')
});

$('#daftar-kegiatan-all').DataTable({
    responsive: true,
    columnDefs: [
        { className: "dt-head-center", targets: '_all' },
        { className: "dt-body-center", targets: [1, 2, 3, 4] },
    ]
});

$('#daftar-sbml-yearly-all').DataTable({
    responsive: true,
    columnDefs: [
        { className: "dt-head-center", targets: '_all' },
        { className: "dt-body-center", targets: [0, 1,] },
    ]
});

$('#daftar-sbml-monthly-all').DataTable({
    responsive: true,
    columnDefs: [
        { className: "dt-head-center", targets: '_all' },
        { className: "dt-body-center", targets: [1, 2,] },
    ]
});

$('#pengguna-all').DataTable({
    responsive: true,
    columnDefs: [
        { className: "dt-head-center", targets: '_all' },
        { className: "dt-body-center", targets: [2, 4] },
    ]
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

$('.input-daterange input').each(function () {
    $(this).datepicker({
        autoclose: true,
        todayHighlight: true,
        todayBtn: "linked",
        language: "id"
    })
});

const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-success mr-3",
        cancelButton: "btn btn-danger"
    },
    buttonsStyling: false
});

$('#daftar-kegiatan-all').on('click', '.hapusDataKegiatanSurveiSensus', function (e) {
    e.preventDefault();
    let href = $(this).attr('href');
    swalWithBootstrapButtons.fire({
        title: "Hapus Kegiatan",
        text: "Apakah anda yakin ingin menghapus kegiatan ini?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus",
        cancelButtonText: "Tidak",
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = href;
        }
    });
});