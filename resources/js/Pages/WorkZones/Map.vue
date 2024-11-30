<script setup>
import { onMounted } from 'vue';

const props = defineProps({
    workZones: Array,
    google_maps_api_key: String
});

const truncate = (input) => input.length > 15 ? input.substring(0, 15) + '...' : input;

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
        const map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: 44.9778, lng: -93.2650 },
            zoom: 5,
            gestureHandling: "greedy"
        });

        // Tooltip element for displaying work zone names
        const tooltip = document.getElementById('tooltip');

        // Add markers for each work zone
        props.workZones.forEach((zone) => {
            const marker = new google.maps.Marker({
                position: { lat: parseFloat(zone.latitude), lng: parseFloat(zone.longitude) },
                map,
                title: truncate(zone.name),
            });

            // Display tooltip on hover
            marker.addListener('mouseover', () => {
                tooltip.style.display = 'block';
                tooltip.innerHTML = marker.getTitle();
            });

            marker.addListener('mouseout', () => {
                tooltip.style.display = 'none';
            });

            // Redirect on click
            marker.addListener('click', () => {
                const url = `${window.location.origin}/user/work-zones/${zone.id}/dashboard`;
                window.location.href = url;
            });
        });
    };
});
</script>

<script>
import UserLayout from '@/Layouts/UserLayout.vue';
export default {
    layout: UserLayout
};
</script>

<template>

    <Head title="Work zones map" />
    <div id="map"></div>
    <div id="tooltip" class="text-bold"></div>
</template>

<style scoped>
#map {
    height: 100vh;
    width: 100%;
}

#tooltip {
    display: none;
    position: absolute;
    background-color: white;
    padding: 5px;
    border: 1px solid black;
    border-radius: 3px;
    z-index: 1;
}
</style>
