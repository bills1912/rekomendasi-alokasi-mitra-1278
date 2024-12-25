$(document).ready(function () {
    $('#selectProvSensus').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Provinsi',
        ajax: {
            url: "selectProvSensus",
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
    $('#selectRegencySensus').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Kabupaten/Kota'
    });
    $('#selectProvSensus').change(function () {
        let id_prov = $('#selectProvSensus').val();
        $('#selectRegencySensus').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih Kabupaten/Kota',
            ajax: {
                url: "selectRegencySensus/" + id_prov,
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
    $('#selectDistrictSensus').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Kecamatan'
    });
    $('#selectRegencySensus').change(function () {
        let id_kako = $('#selectRegencySensus').val();
        $('#selectDistrictSensus').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih Kecamatan',
            ajax: {
                url: "selectDistrictSensus/" + id_kako,
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
    $('#selectVillageSensus').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih Desa'
    });
    $('#selectDistrictSensus').change(function () {
        let id_kec = $('#selectDistrictSensus').val();
        $('#selectVillageSensus').select2({
            theme: 'bootstrap-5',
            placeholder: 'Pilih Desa',
            ajax: {
                url: "selectVillageSensus/" + id_kec,
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
    $('#selectSLS').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih SLS'
    });
    $('#selectVillageSensus').change(function () {
        let id_desa = $('#selectVillageSensus').val();
        $('#selectSLS').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#staticBackdrop'),
            placeholder: 'Pilih SLS',
            ajax: {
                url: "selectSLS/" + id_desa,
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