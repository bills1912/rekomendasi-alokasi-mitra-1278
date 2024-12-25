let mitraInfo = L.control();

let gusitMapData = L.geoJSON(gusit_kec_geom, {
    style: gusitMapStyle,
    onEachFeature: gusitOnEachFeature
}).addTo(mapMitra);

mitraInfo.onAdd = function () {
    this._div = L.DomUtil.create('div', 'info');
    return this._div;
};

mitraInfo.update = function (props) {
    this._div.innerHTML = (props ? '<h5><strong>' + props.ADM3_EN + 
                            '</strong></h5><br /><h6>' + props.ADM2_EN + 
                            '</h6><h6><strong>' + props.ADM1_EN + '</strong></h6>' : '<h6>Arahkan mouse untuk melihat informasi wilayah</h6>')
}

mitraInfo.addTo(mapMitra);

function getColor(kec) {
    return kec == "Gunungsitoli" ? '#2B616D' :
        kec == "Gunungsitoli Alo Oa" ? '#440154FF' :
            kec == "Gunungsitoli Utara" ? '#A01D26' :
                kec == "Gunungsitoli Barat" ? '#217CA3' :
                    kec == "Gunungsitoli Selatan" ? '#A57C65' : '#2C3882';
}

function gusitMapStyle(feature) {
    return {
        fillColor: getColor(feature.properties.ADM3_EN),
        fillOpacity: 0.9,
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
                'fillColor': getColor(feature.properties.ADM3_EN),
                'color': '#666'
            });
            this.bringToFront();
            mitraInfo.update(feature.properties);
        },
        mouseout: function () {
            this.setStyle({
                'fillColor': getColor(feature.properties.ADM3_EN),
                'color': 'white',
                'weight': 2
            });
            mitraInfo.update();
        }
    });
}
