<script setup>
import { Map, View, Feature, Overlay } from 'ol'
import Tile from 'ol/layer/Tile'
import OSM from 'ol/source/OSM'
import { fromLonLat } from 'ol/proj'
import Point from 'ol/geom/Point'
import { Style } from 'ol/style'
import Icon from 'ol/style/Icon'
import VectorLayer from 'ol/layer/Vector'
import VectorSource from 'ol/source/Vector'
import { onMounted } from 'vue'

const props = defineProps({
    work_zones: Array // Change to Array for multiple work zones
})

const truncate = (input) => input.length > 15 ? input.substring(0, 15) + '...' : input;

onMounted(() => {
    // Initialize map with default settings
    const map = new Map({
        target: 'map',
        layers: [
            new Tile({
                source: new OSM()
            })
        ],
        view: new View({
            center: fromLonLat([-93.2650, 44.9778]),
            zoom: 5
        })
    });

    // Define style for icons once
    const iconStyle = new Style({
        image: new Icon({
            anchor: [0.5, 35],
            anchorXUnits: 'fraction',
            anchorYUnits: 'pixels',
            src: '/image/map/icons/pin.png'
        })
    });

    // Create a vector layer source to hold all features
    const vectorSource = new VectorSource();

    // Add each work zone as a feature to the vector source
    props.work_zones.forEach((item) => {
        const feature = new Feature({
            geometry: new Point(fromLonLat([item.longitude, item.latitude])),
            name: truncate(item.name),
            id: item.id
        });
        vectorSource.addFeature(feature);
    });

    // Add a single vector layer with all features to the map
    const vectorLayer = new VectorLayer({
        source: vectorSource,
        style: iconStyle
    });
    map.addLayer(vectorLayer);

    // Set up overlay for tooltip
    const tooltip = document.getElementById('tooltip');
    const overlay = new Overlay({
        element: tooltip,
        offset: [10, 0],
        positioning: 'bottom-left'
    });
    map.addOverlay(overlay);

    // Display tooltip with feature name on hover
    map.on('pointermove', (evt) => {
        const feature = map.forEachFeatureAtPixel(evt.pixel, (feat) => feat);
        tooltip.style.display = feature ? '' : 'none';
        if (feature) {
            overlay.setPosition(evt.coordinate);
            tooltip.innerHTML = feature.get('name');
        }
    });

    // Redirect on feature click
    map.on("click", (event) => {
        const feature = map.forEachFeatureAtPixel(event.pixel, (feat) => feat);
        if (feature) {
            const url = `${window.location.origin}/user/work-zones/${feature.get("id")}/dashboard`;
            document.location.href = url;
        }
    });
});
</script>

<script>
import UserLayout from '@/Layouts/UserLayout.vue';
export default {
    layout: UserLayout
}
</script>

<template>

    <Head title="Work zones map" />
    <div id="map"></div>
    <div id="tooltip" class="text-bold"></div>
</template>

<style>
@import 'ol/ol.css';
</style>
<style scoped>
#map {
    height: 100vh;
    width: 100%;
    /* Set to 100% for responsiveness */
}

#tooltip {
    background-color: white;
    padding: 5px;
    border: 1px solid black;
    border-radius: 3px;
}
</style>
