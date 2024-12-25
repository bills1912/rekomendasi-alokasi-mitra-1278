let slsCentroid = [];
let coords_mitra_sensus = [];
let jarakMitraSLS = [];
let filteredSLS = [];
let mitraSensusMarkerGroup = L.markerClusterGroup();
let mapAlokasiMitraSensus = L.map('map-alokasi-mitra-sensus');
let layerGroupDesa = L.featureGroup();
let layerGroupSLS = L.featureGroup();

let osmSensus = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
})

osmSensus.addTo(mapAlokasiMitraSensus);
$(document).ready(function () {
    $("#selectDistrictSensus").change(function () {
        $("#tombol-alokasi-sensus").removeAttr('hidden');
    });
    $('#form-mitra-sensus').submit(function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        slsCentroid = [];
        filteredSLS = [];
        coords_mitra_sensus = [];
        layerGroupSLS.clearLayers();
        layerGroupDesa.clearLayers();
        let idDesa = $("#selectVillageSensus").val();
        let idSLS = $("#selectSLS").val();
        fetch("js/1278_sls.geojson")
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                try {
                    for (let i = 0; i <= data['features'].length; i++) {
                        if (data['features'][i].properties.iddesa == idDesa) {
                            let desa_geom = L.geoJSON(data['features'][i], {
                                onEachFeature: bsOnEachFeature
                            });
                            layerGroupDesa.addTo(mapAlokasiMitraSensus);
                            layerGroupDesa.addLayer(desa_geom);
                            function bsOnEachFeature(feature, layer) {
                                layer.on({
                                    click: function () {
                                        layer.bindPopup(
                                            "<h6 class='text-dark'>" + feature.properties.nmdesa + " " + feature.properties.nmsls + "</h6>" +
                                            "<br> Luas BS: " + feature.properties.luas + " m" + '<sup>2</sup>' +
                                            "<br> Muatan KK: " + feature.properties.kk, {
                                            maxHeight: 300,
                                            minWidth: 200,
                                            maxWidth: 600
                                        }).openPopup();
                                    }
                                });
                            };
                        };
                        if (data['features'][i].properties.idsls == idSLS) {
                            let sls_geom = L.geoJSON(data['features'][i], {
                                style: slsStyle,
                                onEachFeature: bsOnEachFeature
                            });
                            layerGroupSLS.addTo(mapAlokasiMitraSensus);
                            layerGroupSLS.addLayer(sls_geom);
                            slsCentroid.push([layerGroupSLS.getBounds().getCenter()['lat'], layerGroupSLS.getBounds().getCenter()['lng']]);
                            layerGroupSLS.addLayer(L.marker(slsCentroid[0]));
                            mapAlokasiMitraSensus.setView(slsCentroid[0], 16);
                            $.ajax({
                                method: "get",
                                url: window.location.protocol + "//" + window.location.hostname + "/js/test.geojson",
                                dataType: "json",
                                success: function (response) {
                                    let slsData = response
                                    $.each(slsData.features, function (idx, sls) {
                                        if ((sls.properties.iddesa == idDesa) && (sls.properties.idsls != idSLS)) {
                                            filteredSLS.push(sls)
                                        }
                                    })
                                    $("#datatable-mitra-sensus").DataTable().destroy();
                                    try {
                                        $("#datatable-mitra-sensus").DataTable({
                                            data: filteredSLS,
                                            responsive: true,
                                            // rowReorder: true,
                                            order: [[3, "asc"]],
                                            columnDefs: [
                                                // { targets: [3, 4], visible: false },
                                                { className: "dt-head-center", targets: '_all' },
                                                { className: "dt-body-center", targets: [2, 3] },
                                                { orderable: false, targets: '_all' }
                                            ],
                                            columns: [
                                                {
                                                    data: "properties.iddesa"
                                                },
                                                {
                                                    data: "properties.idsls"
                                                },
                                                {
                                                    data: "properties.kk"
                                                },
                                                {
                                                    data: null,
                                                    render: function (data, type, row) {
                                                        return Math.acos(Math.sin(row.geometry.coordinates[1]) * Math.sin(slsCentroid[0][0]) + Math.cos(row.geometry.coordinates[1]) * Math.cos(slsCentroid[0][0]) * Math.cos(slsCentroid[0][1] - row.geometry.coordinates[0])) * 6371;
                                                        // return slsCentroid;
                                                    }
                                                },
                                            ]
                                        });
                                    } catch (e) {
                                        console.log(e);
                                    }
                                }
                            });
                            $.ajax({
                                url: "/alokasi_mitra_sensus",
                                data: $("#form-mitra-sensus").serialize(),
                                type: "POST",
                                dataType: "json",
                                success: function (result) {
                                    let data_mitra = result
                                    console.log(data_mitra);
                                    $("#rekomendasi-mitra-sensus").DataTable().destroy();
                                    try {
                                        $("#rekomendasi-mitra-sensus").DataTable({
                                            data: data_mitra,
                                            responsive: true,
                                            // rowReorder: true,
                                            order: [[6, "asc"]],
                                            columnDefs: [
                                                // { targets: [3, 4], visible: false },
                                                { className: "dt-head-center", targets: '_all' },
                                                { className: "dt-body-center", targets: [4, 5, 6, 7] },
                                                { orderable: false, targets: '_all' }
                                            ],
                                            columns: [
                                                { data: "id_desa_mitra" },
                                                { data: "id_sls_mitra" },
                                                { data: "nama_mitra" },
                                                { data: "alamat_mitra" },
                                                { data: "latitude" },
                                                { data: "longitude" },
                                                {
                                                    data: null,
                                                    render: function (row) {
                                                        return Math.acos(Math.sin(row.latitude) * Math.sin(slsCentroid[0][0]) + Math.cos(row.latitude) * Math.cos(slsCentroid[0][0]) * Math.cos(slsCentroid[0][1] - row.longitude)) * 6371;
                                                    }
                                                },
                                                {
                                                    data: null,
                                                    render: function (row) {
                                                        return '<button class="btn btn-outline-primary btn-sm cari-mitra-sensus mr-1"><i class="fa-solid fa-magnifying-glass"></i></button>' +
                                                            '<button class="btn btn-outline-warning btn-sm btn-alokasi-mitra-sensus" data-bs-toggle="modal" data-bs-target="#alokasiMitraSensusModal' + row.id + '"><i class="fa-solid fa-thumbtack"></i></button>' +
                                                            '<div class="modal fade" id="alokasiMitraSensusModal'+row.id+'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">\
                                                                <div class="modal-dialog">\
                                                                    <div class="modal-content">\
                                                                        <div class="modal-header">\
                                                                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>\
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>\
                                                                        </div>\
                                                                        <div class="modal-body">'+
                                                                            row.nama_mitra +
                                                                        '</div>\
                                                                        <div class="modal-footer">\
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>\
                                                                            <button type="button" class="btn btn-primary">Save changes</button>\
                                                                        </div>\
                                                                    </div>\
                                                                </div>\
                                                            </div>'
                                                    }
                                                }
                                            ]
                                        });
                                    } catch (e) {
                                        console.log(e);
                                    }
                                    mitraSensusMarkerGroup.clearLayers();
                                    try {
                                        for (let i = 0; i <= data_mitra.length; i++) {
                                            coords_mitra_sensus.push([data_mitra[i].latitude, data_mitra[i]['longitude']]);
                                            mitraSensusMarkerGroup.addLayer(L.marker(coords_mitra_sensus[i], {
                                                icon: L.icon({
                                                    iconUrl: window.location.protocol + "//" + window.location.host + '/img/man.png',
                                                    iconSize: [30, 30]
                                                })
                                            }).bindPopup('lorem ipsum'));
                                            mapAlokasiMitraSensus.addLayer(mitraSensusMarkerGroup)
                                            $("#rekomendasi-mitra-sensus").on("click", ".cari-mitra-sensus", function () {
                                                var mitraIdx = $(this).closest('tr').index();
                                                mapAlokasiMitraSensus.flyTo([parseFloat(document.getElementById("rekomendasi-mitra-sensus").rows[mitraIdx + 1].childNodes[4].innerHTML.replace("'", "")),
                                                parseFloat(document.getElementById("rekomendasi-mitra-sensus").rows[mitraIdx + 1].childNodes[5].innerHTML.replace("'", ""))], 17)
                                            });
                                        };
                                    } catch (e) {
                                        console.log(e);
                                    }
                                }
                            });
                            function slsStyle() {
                                return {
                                    fillColor: "#ffcccb",
                                    fillOpacity: 0,
                                    opacity: 1,
                                    weight: 1,
                                }
                            };
                            function bsOnEachFeature(feature, layer) {
                                layer.on({
                                    click: function () {
                                        layer.bindPopup(
                                            "<h6 class='text-dark'>" + feature.properties.nmdesa + " " + feature.properties.nmsls + "</h6>" +
                                            "<br> Luas BS: " + feature.properties.luas + " m" + '<sup>2</sup>' +
                                            "<br> Muatan KK: " + feature.properties.kk, {
                                            maxHeight: 300,
                                            minWidth: 200,
                                            maxWidth: 600
                                        }).openPopup();
                                    }
                                });
                            };
                        };
                    };
                }
                catch (e) {
                    console.log(e);
                }
            });
        $(".mitra-sensus-wilkerstat").removeAttr("hidden");
        setTimeout(function () {
            mapAlokasiMitraSensus.invalidateSize();
        }, 1);
        $(".tabel-mitra-sensus").removeAttr('hidden');
        return false;
    });
});