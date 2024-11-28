<script setup>
import { onMounted } from 'vue';

const props = defineProps({
    google_maps_api_key: String
});

let map;
let busMarkers = {};

const busColors = ['#FF0000', '#00FF00', '#0000FF']; // Red, Green, Blue
const busIndices = {};

onMounted(() => {
    // Load the Google Maps script dynamically
    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=${props.google_maps_api_key}&libraries=places`;
    script.async = true;
    document.head.appendChild(script);

    script.onload = () => {
        // Initialize the map centered around 2243 86th St, Brooklyn, NY 11214
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: 40.5999, lng: -73.9940 },
            zoom: 15,
            gestureHandling: "greedy",
            styles: [
                {
                    featureType: "poi",
                    stylers: [{ visibility: "off" }] // Hide all POIs
                },
                {
                    featureType: "transit.station",
                    stylers: [{ visibility: "off" }] // Optionally hide transit stations
                }
            ]
        });


        // Listen for bus location updates
        Echo.private('bus-location-updates')
            .listen('BusLocationUpdated', (e) => {
                const bus = e.busLocation;
                const bus_id = bus.bus_id;
                const position = { lat: parseFloat(bus.latitude), lng: parseFloat(bus.longitude) };

                // Assign an index to each bus
                if (busIndices[bus_id] === undefined) {
                    const currentBusCount = Object.keys(busIndices).length;
                    if (currentBusCount < 3) {
                        busIndices[bus_id] = currentBusCount;
                    } else {
                        // Ignore buses beyond the first three
                        return;
                    }
                }

                const busIndex = busIndices[bus_id];
                const color = busColors[busIndex];

                if (busMarkers[bus_id]) {
                    // Update existing marker position
                    busMarkers[bus_id].setPosition(position);
                } else {
                    // Create a new marker with the assigned color
                    busMarkers[bus_id] = new google.maps.Marker({
                        position,
                        map,
                        title: `Bus ${bus_id}`,
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 6,
                            fillColor: color,
                            fillOpacity: 1,
                            strokeColor: 'white',
                            strokeWeight: 2,
                        },
                    });
                }
            });
    };
});
</script>

<script>
import WorkZoneLayout from '@/Layouts/WorkZoneLayout.vue';
export default {
    layout: WorkZoneLayout
};
</script>

<template>

    <Head title="MTA Bus Locations Map" />
    <div id="map"></div>
</template>

<style scoped>
#map {
    height: 100vh;
    width: 100%;
}
</style>
