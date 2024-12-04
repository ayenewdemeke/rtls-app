<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
    work_zone: Object,
    google_maps_api_key: String
});

console.log(props.work_zone.latitude)

let map;
let deviceMarker;

// Store the device's current location
const deviceLocation = ref({ lat: null, lng: null });

onMounted(() => {
    // Check if Google Maps script already exists and remove it
    const existingScript = document.querySelector('script[src*="maps.googleapis.com"]');
    if (existingScript) {
        existingScript.remove();
    }

    // Load the Google Maps script dynamically
    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=${props.google_maps_api_key}&libraries=places`;
    script.async = true;
    document.head.appendChild(script);

    script.onload = () => {
        // Initialize the map
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: parseFloat(props.work_zone.latitude), lng: parseFloat(props.work_zone.longitude) },
            zoom: 20,
            gestureHandling: "greedy",
            mapTypeId: google.maps.MapTypeId.SATELLITE,
            tilt: 0,
            styles: [
                {
                    featureType: "all",
                    elementType: "labels",
                    stylers: [{ visibility: "off" }]
                }
            ]
        });

        // Initialize the device marker
        deviceMarker = new google.maps.Marker({
            map,
            title: 'Device Location',
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 10,
                fillColor: 'red',
                fillOpacity: 1,
                strokeColor: 'white',
                strokeWeight: 3
            }
        });

        // Listen for location updates
        Echo.private('location-updates')
            .listen('LocationUpdated', (e) => {
                const lat = parseFloat(e.location.latitude);
                const lng = parseFloat(e.location.longitude);

                // Update device location
                deviceLocation.value = { lat, lng };

                // Update the marker position
                deviceMarker.setPosition(deviceLocation.value);
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

    <Head title="Device Location Map" />
    <div id="map"></div>
</template>

<style scoped>
#map {
    height: 100vh;
    width: 100%;
}
</style>
