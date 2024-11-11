<script setup>
import { onMounted, ref } from 'vue'
import { Chart, registerables } from 'chart.js'
import 'chartjs-adapter-date-fns'

Chart.register(...registerables)

const props = defineProps({
    work_zone: Object,
    cord: Object
})

const liveData = ref([])

let chartLat, chartLon, chartSpeed, chartBear;

Echo.private('location-updates')
    .listen('LocationUpdated', (e) => {

        // // Push new data into liveData array
        // liveData.value.push({
        //     timestamp: e.location.timestamp,
        //     latitude: e.location.latitude,
        //     longitude: e.location.longitude,
        //     speed: e.location.speed,
        //     bearing: e.location.bearing
        // });

        // // Keep only the last 15 rows
        // if (liveData.value.length > 15) {
        //     liveData.value.shift();
        // }

        // Update latitude chart
        if (chartLat) {
            chartLat.data.labels.push(e.location.timestamp);
            chartLat.data.datasets[0].data.push(e.location.latitude);

            if (chartLat.data.labels.length > 30) {
                chartLat.data.labels.shift();
                chartLat.data.datasets[0].data.shift();
            }
            chartLat.update();
        }

        // Update longitude chart
        if (chartLon) {
            chartLon.data.labels.push(e.location.timestamp);
            chartLon.data.datasets[0].data.push(e.location.longitude);

            if (chartLon.data.labels.length > 30) {
                chartLon.data.labels.shift();
                chartLon.data.datasets[0].data.shift();
            }
            chartLon.update();
        }

        // Update speed chart
        if (chartSpeed) {
            chartSpeed.data.labels.push(e.location.timestamp);
            chartSpeed.data.datasets[0].data.push(e.location.speed);

            if (chartSpeed.data.labels.length > 30) {
                chartSpeed.data.labels.shift();
                chartSpeed.data.datasets[0].data.shift();
            }
            chartSpeed.update();
        }

        // Update bearing chart
        if (chartBear) {
            chartBear.data.labels.push(e.location.timestamp);
            chartBear.data.datasets[0].data.push(e.location.bearing);

            if (chartBear.data.labels.length > 30) {
                chartBear.data.labels.shift();
                chartBear.data.datasets[0].data.shift();
            }
            chartBear.update();
        }
    });

onMounted(() => {
    // Initialize latitude chart
    const ctxLat = document.getElementById('lineChartLat').getContext('2d');
    chartLat = new Chart(ctxLat, {
        type: 'line',
        data: {
            labels: [],  // Start with empty labels
            datasets: [{
                label: 'Latitude',
                data: [],
                borderColor: 'red',
                backgroundColor: 'red',
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
                y: { title: { display: true, text: 'Latitude' }, beginAtZero: false }
            }
        }
    });

    // Initialize longitude chart
    const ctxLon = document.getElementById('lineChartLon').getContext('2d');
    chartLon = new Chart(ctxLon, {
        type: 'line',
        data: {
            labels: [],  // Start with empty labels
            datasets: [{
                label: 'Longitude',
                data: [],
                borderColor: 'blue',
                backgroundColor: 'blue',
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
                y: { title: { display: true, text: 'Longitude' }, beginAtZero: false }
            }
        }
    })

    // Initialize speed chart
    const ctxSpeed = document.getElementById('lineChartSpeed').getContext('2d');
    chartSpeed = new Chart(ctxSpeed, {
        type: 'line',
        data: {
            labels: [],  // Start with empty labels
            datasets: [{
                label: 'Speed',
                data: [],
                borderColor: 'green',
                backgroundColor: 'green',
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
                y: { title: { display: true, text: 'Speed' }, beginAtZero: false }
            }
        }
    })

    // Initialize bearing chart
    const ctxBear = document.getElementById('lineChartBear').getContext('2d');
    chartBear = new Chart(ctxBear, {
        type: 'line',
        data: {
            labels: [],  // Start with empty labels
            datasets: [{
                label: 'Bearing',
                data: [],
                borderColor: 'orange',
                backgroundColor: 'orange',
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
                y: { title: { display: true, text: 'Bearing' }, beginAtZero: false }
            }
        }
    })
})
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
        <!-- <div class="card">
            <div class="card-header">
                <h4 class="font-weight-normal">Live Location Data</h4>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Timestamp</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Speed</th>
                            <th>Bearing</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="entry in liveData" :key="entry.timestamp">
                            <td>{{ entry.timestamp }}</td>
                            <td>{{ entry.latitude }}</td>
                            <td>{{ entry.longitude }}</td>
                            <td>{{ entry.speed }}</td>
                            <td>{{ entry.bearing }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h4 class="font-weight-normal">Latitude</h4>
                    </div>
                    <div class="col-6 text-right">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="lineChartLat"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h4 class="font-weight-normal">Longitude</h4>
                    </div>
                    <div class="col-6 text-right">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="lineChartLon"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h4 class="font-weight-normal">Speed</h4>
                    </div>
                    <div class="col-6 text-right">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="lineChartSpeed"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h4 class="font-weight-normal">Bearing</h4>
                    </div>
                    <div class="col-6 text-right">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="lineChartBear"></canvas>
            </div>
        </div>
    </div>
</template>

<style>
@import 'ol/ol.css';
</style>
<style scoped>
#map {
    height: 25rem;
    width: 100%;
}

.header {
    font-family: 'Roboto', Arial, Helvetica, sans-serif;
    font-size: 1.7rem;
    font-weight: 400;
}

canvas {
    width: 100%;
    height: 30rem;
}
</style>