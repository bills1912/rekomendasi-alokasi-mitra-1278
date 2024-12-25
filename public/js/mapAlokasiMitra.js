let mapAlokasiMitra = L.map('map-alokasi-mitra');
let layerGroup = L.featureGroup();

let osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
})

osm.addTo(mapAlokasiMitra);

// setTimeout(function () {
//     window.dispatchEvent(new Event("resize"));
// }, 500);