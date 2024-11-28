<script setup>
import { onMounted, ref, watch } from 'vue'
import { Chart, registerables } from 'chart.js'
import 'chartjs-adapter-date-fns'

Chart.register(...registerables)

const props = defineProps({
    work_zone: Object,
    cord: Object
})

let chart;  // Existing chart for selectable data
let positionChart;  // New chart for current position

const selectedChart = ref('Latitude');  // Default selected chart

// Data storage for each chart
const chartData = {
    Latitude: { labels: [], data: [], color: 'red' },
    Longitude: { labels: [], data: [], color: 'blue' },
    Speed: { labels: [], data: [], color: 'green' },
    Bearing: { labels: [], data: [], color: 'orange' },
};

// Data for position chart
const positionData = ref({ x: null, y: null });

Echo.private('location-updates')
    .listen('LocationUpdated', (e) => {
        // Update data for each chart
        const timestamp = e.location.timestamp;

        chartData.Latitude.labels.push(timestamp);
        chartData.Latitude.data.push(e.location.latitude);
        if (chartData.Latitude.labels.length > 30) {
            chartData.Latitude.labels.shift();
            chartData.Latitude.data.shift();
        }

        chartData.Longitude.labels.push(timestamp);
        chartData.Longitude.data.push(e.location.longitude);
        if (chartData.Longitude.labels.length > 30) {
            chartData.Longitude.labels.shift();
            chartData.Longitude.data.shift();
        }

        chartData.Speed.labels.push(timestamp);
        chartData.Speed.data.push(e.location.speed);
        if (chartData.Speed.labels.length > 30) {
            chartData.Speed.labels.shift();
            chartData.Speed.data.shift();
        }

        chartData.Bearing.labels.push(timestamp);
        chartData.Bearing.data.push(e.location.bearing);
        if (chartData.Bearing.labels.length > 30) {
            chartData.Bearing.labels.shift();
            chartData.Bearing.data.shift();
        }

        // Update main chart if it's the selected one
        if (chart) {
            updateChart();
        }

        // Update position data
        positionData.value = {
            x: e.location.longitude,
            y: e.location.latitude
        };

        // Update position chart
        if (positionChart) {
            updatePositionChart();
        }
    });

onMounted(() => {
    // Initialize the main chart
    const ctx = document.getElementById('lineChart').getContext('2d');
    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData[selectedChart.value].labels,
            datasets: [{
                label: selectedChart.value,
                data: chartData[selectedChart.value].data,
                borderColor: chartData[selectedChart.value].color,
                backgroundColor: chartData[selectedChart.value].color,
                fill: false,
                tension: 0.1,
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' }
            },
            scales: {
                x: {
                    type: 'time',
                    time: { tooltipFormat: 'HH:mm:ss', unit: 'second', displayFormats: { second: 'HH:mm:ss' } },
                    title: { display: true, text: 'Time (HH:MM:SS)' }
                },
                y: { title: { display: true, text: selectedChart.value }, beginAtZero: false }
            }
        }
    });

    // Initialize position chart
    const ctxPosition = document.getElementById('positionChart').getContext('2d');
    positionChart = new Chart(ctxPosition, {
        type: 'scatter',
        data: {
            datasets: [{
                label: 'Current Position',
                data: [],
                backgroundColor: 'red',
                pointRadius: 8,  // Adjust the size to match Google Maps
                pointHoverRadius: 10,
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                x: {
                    type: 'linear',
                    position: 'bottom',
                    title: {
                        display: true,
                        text: 'Longitude'
                    },
                    min: -96.796,
                    max: -96.7952
                },
                y: {
                    title: {
                        display: true,
                        text: 'Latitude'
                    },
                    min: 46.90225,
                    max: 46.90235
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

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

// Watch for changes in selectedChart
watch(selectedChart, () => {
    if (chart) {
        updateChart();
    }
});

function updateChart() {
    chart.data.labels = chartData[selectedChart.value].labels;
    chart.data.datasets[0].label = selectedChart.value;
    chart.data.datasets[0].data = chartData[selectedChart.value].data;
    chart.data.datasets[0].borderColor = chartData[selectedChart.value].color;
    chart.data.datasets[0].backgroundColor = chartData[selectedChart.value].color;
    chart.options.scales.y.title.text = selectedChart.value;
    chart.update();
}

function updatePositionChart() {
    positionChart.data.datasets[0].data = [positionData.value];
    positionChart.update();
}
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
                        <img v-else :src="'/storage/work_zone/image/default.jpg'" style="width: 100%" alt="image">
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

        <!-- Position Chart Card -->
        <div class="card">
            <div class="card-header">
                <h4 class="font-weight-normal">Current Position</h4>
            </div>
            <div class="card-body">
                <canvas class="canvas-location" id="positionChart"></canvas>
            </div>
        </div>

        <!-- Notifications Card -->
        <div class="row">
            <div class="col-md-7">
                <div class="card my-3">
                    <div class="card-header">
                        <h4 class="font-weight-normal">Event Log</h4>
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
                        <h4 class="font-weight-normal">Number of Sensor Devices</h4>
                    </div>
                    <div class="card-body">
                        <canvas class="canvas-device" id="sensorDevicesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Combined Chart Card -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h4 class="font-weight-normal">{{ selectedChart }}</h4>
                    </div>
                    <div class="col-6 text-right">
                        <select class="form-control" v-model="selectedChart">
                            <option value="Latitude">Latitude</option>
                            <option value="Longitude">Longitude</option>
                            <option value="Speed">Speed</option>
                            <option value="Bearing">Bearing</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas class="canvas-location" id="lineChart"></canvas>
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
</style>