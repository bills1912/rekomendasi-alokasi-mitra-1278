$(document).ready(function () {
    $('#initializeSurvei').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Kegiatan Survei',
        dropdownParent: $('#survey-sample-uploader'),
        ajax: {
            url: "initializeSurvei",
            processResults: function ({ data }) {
                console.log(data)
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
    $('#initializeSurveiBAST').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Kegiatan Survei',
        dropdownParent: $('#bast-generator'),
        ajax: {
            url: "initializeSurveiBAST",
            processResults: function ({ data }) {
                console.log(data)
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
});