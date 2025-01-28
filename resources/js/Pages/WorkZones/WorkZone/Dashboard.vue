<script setup>
import { onMounted, watch, ref } from 'vue'
import { Chart, registerables } from 'chart.js'
import 'chartjs-adapter-date-fns'

Chart.register(...registerables)

const props = defineProps({
    work_zone: Object,
    cord: Object,
    google_maps_api_key: String
})

let chart;  // Existing chart for selectable data
let incidentChart;  // New incident chart

// Placeholder data for incident chart
const incidentData = ref({
    labels: [
        '2024-01-01T08:00:00',
        '2024-03-01T09:00:00',
        '2024-05-01T10:00:00',
        '2024-07-01T11:00:00',
        '2024-09-01T12:00:00',
        '2024-11-01T13:00:00',
        '2024-12-01T14:00:00',
    ],
    datasets: [
        {
            label: 'Near miss',
            data: [2, 3, 3, 4, 6, 8, 9],
            borderColor: 'rgba(255, 99, 132, 1)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            fill: false,
            tension: 0.1,
        },
        {
            label: 'Vehicle intrusion',
            data: [1, 2, 3, 3, 3, 4, 6],
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            fill: false,
            tension: 0.1,
        }
    ]
});

// Initialize Google Maps related variables
let map;
let deviceMarker;

// Store the device's current location
const deviceLocation = ref({ lat: null, lng: null });

onMounted(() => {
    // Initialize the incident chart
    const ctxIncident = document.getElementById('incidentChart').getContext('2d');
    incidentChart = new Chart(ctxIncident, {
        type: 'line',
        data: incidentData.value,
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' }
            },
            scales: {
                x: {
                    type: 'time',
                    time: { tooltipFormat: 'yyyy-MM-dd HH:mm', unit: 'month', displayFormats: { hour: 'HH:mm' } },
                    title: { display: true, text: 'Time' }
                },
                y: {
                    title: { display: true, text: 'Number of incidents' },
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Dynamically load Google Maps script
    const existingScript = document.querySelector('script[src*="maps.googleapis.com"]');
    if (existingScript) {
        existingScript.remove();
    }

    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=${props.google_maps_api_key}&libraries=places`;
    script.async = true;
    document.head.appendChild(script);

    script.onload = () => {
        // Initialize the map
        map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: parseFloat(props.work_zone.latitude),
                lng: parseFloat(props.work_zone.longitude)
            },
            zoom: 15,
            gestureHandling: "greedy"
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

    // Number of sensor devices chart
    const ctxSensorDevices = document.getElementById('sensorDevicesChart').getContext('2d');
    new Chart(ctxSensorDevices, {
        type: 'bar',
        data: {
            labels: ['Equipment', 'Workers', 'Traffic Cones'],
            datasets: [{
                label: 'Number of Sensor Devices',
                data: [3, 6, 10],  // Static demo data
                backgroundColor: ['#FF6384', '#36A2EB', '#4BC0C0']
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>

<script>
import WorkZoneLayout from '@/Layouts/WorkZoneLayout.vue';
export default {
    layout: (h, page) => h(WorkZoneLayout, { 'work_zone': page.props.work_zone }, () => page)
}
</script>

<template>

    <Head title="Dashboard" />

    <div class="container-fluid py-3">
        <h1 class="header my-3">Dashboard</h1>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-lg-4 my-auto">
                        <img v-if="work_zone.image" :src="'/storage/work_zone/image/' + work_zone.image"
                            style="width: 100%" alt="image">
                        <img v-else :src="'/image/work_zone/image/default.jpg'" style="width: 100%" alt="image">
                    </div>

                    <div class="col-md-6 col-lg-8 my-auto">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                Name <a class="float-right">{{ work_zone.name }}</a>
                            </li>
                            <li class="list-group-item">
                                Status <a class="float-right">{{ work_zone.work_zone_status.name }}</a>
                            </li>
                            <li class="list-group-item">
                                Start date <a class="float-right">{{ work_zone.start_date }}</a>
                            </li>
                            <li class="list-group-item">
                                Location <a class="float-right">{{ work_zone.location }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Position Map Card -->
        <div class="card">
            <div class="card-header">
                <h4 class="font-weight-normal">Current position</h4>
            </div>
            <div class="card-body">
                <div id="map" class="map-container"></div>
            </div>
        </div>

        <!-- Notifications Card -->
        <div class="row">
            <div class="col-md-7">
                <div class="card my-3">
                    <div class="card-header">
                        <h4 class="font-weight-normal">Event log</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger pb-0 mb-2" role="alert">
                            <h6 class="alert-heading"><i class="nav-icon fa-solid fa-ban"></i> Critical incident alert!
                            </h6>
                            <p>A severe intrusion has been detected in the work zone. Immediate action is required to
                                ensure the safety of all workers. Please assess the situation and follow safety
                                protocols.</p>
                        </div>
                        <div class="alert border-secondary pb-0 mb-2" role="alert">
                            <h6 class="alert-heading"><i class="nav-icon fa-solid fa-info"></i> System update
                            </h6>
                            <p>All systems are running smoothly. No anomalies detected in the last 24 hours.</p>
                        </div>
                        <div class="alert alert-warning pb-0 mb-2" role="alert">
                            <h6 class="alert-heading"><i class="nav-icon fa-solid fa-warning"></i> Caution: Sensor
                                Malfunction!
                            </h6>
                            <p>One of the proximity sensors is malfunctioning. Please verify the sensor status to avoid
                                false detections.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card my-3">
                    <div class="card-header">
                        <h4 class="font-weight-normal">Number of sensor devices</h4>
                    </div>
                    <div class="card-body">
                        <canvas class="canvas-device" id="sensorDevicesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Incident vs Time Chart Card -->
        <div class="card">
            <div class="card-header">
                <h4 class="font-weight-normal">Incidents over time</h4>
            </div>
            <div class="card-body">
                <canvas class="canvas-location" id="incidentChart"></canvas>
            </div>
        </div>

    </div>
</template>

<style scoped>
.header {
    font-family: 'Roboto', Arial, Helvetica, sans-serif;
    font-size: 1.7rem;
    font-weight: 400;
}

.canvas-location {
    width: 100%;
    height: 30rem;
}

.canvas-device {
    width: 100%;
    height: 19.5rem;
}

.map-container {
    width: 100%;
    height: 30rem;
    /* Adjust height as needed */
}
</style>
