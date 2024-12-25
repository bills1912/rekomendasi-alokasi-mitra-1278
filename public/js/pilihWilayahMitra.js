$(document).ready(function () {
    // $('#selectProv').select2({
    //     theme: 'bootstrap-5',
    //     placeholder: 'Pilih Provinsi',
    //     ajax: {
    //         url: "selectProv",
    //         processResults: function ({ data }) {
    //             return {
    //                 results: $.map(data, function (item) {
    //                     return {
    //                         id: item.id,
    //                         text: "(" + item.id + ")" + " " + item.name
    //                     }
    //                 })
    //             }
    //         }
    //     }
    // });
    $('#selectRegency').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Kabupaten/Kota',
        ajax: {
            url: "selectRegency",
            processResults: function ({ data }) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            id: item.id,
                            text: "(" + item.id + ")" + " " + item.name
                        }
                    })
                }
            }
        }
    });
    $('#selectDistrict').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Kecamatan',
    });
    $('#selectRegency').change(function () {
        let id_kako = $('#selectRegency').val();
        $('#selectDistrict').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih Kecamatan',
            ajax: {
                url: "selectDistrict/" + id_kako,
                processResults: function ({ data }) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: "(" + item.id + ")" + " " + item.name
                            }
                        })
                    }
                }
            }
        });
    });
    $('#selectVillage').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Desa'
    });
    $('#selectDistrict').change(function () {
        let id_kec = $('#selectDistrict').val();
        $('#selectVillage').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih Desa',
            ajax: {
                url: "selectVillage/" + id_kec,
                processResults: function ({ data }) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.id,
                                text: "(" + item.id + ")" + " " + item.name
                            }
                        })
                    }
                }
            }
        });
    });
    $('#selectBS').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Blok Sensus'
    });
    $('#selectVillage').change(function () {
        let id_desa = $('#selectVillage').val();
        $('#selectBS').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih Blok Sensus',
            ajax: {
                url: "selectBS/" + id_desa,
                processResults: function ({ data }) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.idbs,
                                text: "(" + item.idbs + ")" + " " + item.nmdesa
                            }
                        })
                    }
                }
            }
        });
    });
    $('#selectSLS').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih SLS'
    });
    $('#selectVillage').change(function () {
        let id_desa = $('#selectVillage').val();
        $('#selectSLS').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#staticBackdrop'),
            placeholder: 'Pilih SLS',
            ajax: {
                url: "selectBS/" + id_desa,
                processResults: function ({ data }) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                id: item.idsls,
                                text: "(" + item.idsls + ")" + " " + item.nmsls
                            }
                        })
                    }
                }
            }
        });
    });
});