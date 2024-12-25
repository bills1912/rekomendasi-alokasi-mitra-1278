let mitraInfo = L.control();

let gusitMapData = L.geoJSON(sls_1278, {
    style: gusitMapStyle,
    onEachFeature: gusitOnEachFeature
}).addTo(mapMitra);

mitraInfo.onAdd = function () {
    this._div = L.DomUtil.create('div', 'info');
    return this._div;
};

mitraInfo.update = function (props) {
    this._div.innerHTML = '<h6>Jumlah Mitra Setiap Wilayah<h6>' + (props ? '<b>' + props.nmdesa + " " + props.nmsls + '</b><br />' + props.kk + ' orang' : 'Arahkan mouse untuk melihat info jumlah mitra')
}

mitraInfo.addTo(mapMitra);

function gusitMapStyle() {
    return {
        fillColor: "#ffcccb",
        fillOpacity: 0.5,
        color: '#fff',
        opacity: 1,
        weight: 1,
    }
};

function gusitOnEachFeature(feature, layer) {
    layer.on({
        mouseover: function () {
            this.setStyle({
                'weight': 5,
                'fillColor': '#fca5af',
                'color': '#666'
            });
            this.bringToFront();
            mitraInfo.update(feature.properties);
        },
        mouseout: function () {
            this.setStyle({
                'fillColor': '#ffcccb',
                'color': 'white',
                'weight': 1
            });
            mitraInfo.update();
        }
    });
}