<script setup>
import { onMounted, ref, watch } from 'vue'
import { Chart, registerables } from 'chart.js'
import 'chartjs-adapter-date-fns'

Chart.register(...registerables)

// Props (e.g., from Inertia)
const props = defineProps({
    work_zone: Object,
    cord: Object,
    google_maps_api_key: String
})

// ----------------------
// 1) Reading Chart Setup
// ----------------------
let readingChart = null
const selectedReadingChart = ref('GpsX')

const readingChartData = {
    GpsX: { labels: [], data: [], color: 'purple' },
    GpsY: { labels: [], data: [], color: 'magenta' },
    IMUAccelerationX: { labels: [], data: [], color: 'gray' },
    IMUAccelerationY: { labels: [], data: [], color: 'brown' },
    IMUAccelerationZ: { labels: [], data: [], color: 'olive' },
    IMUAngularVelocityX: { labels: [], data: [], color: 'cyan' },
    IMUAngularVelocityY: { labels: [], data: [], color: 'pink' },
    IMUAngularVelocityZ: { labels: [], data: [], color: 'teal' },
}

// Listen for GPS ReadingUpdated events
Echo.private('gps-reading-updates').listen('GpsReadingUpdated', (e) => {
    const timestamp = e.gpsReading.time

    // Update GPS X data
    readingChartData.GpsX.labels.push(timestamp)
    readingChartData.GpsX.data.push(e.gpsReading.gps_x)
    if (readingChartData.GpsX.labels.length > 30) {
        readingChartData.GpsX.labels.shift()
        readingChartData.GpsX.data.shift()
    }

    // Update GPS Y data
    readingChartData.GpsY.labels.push(timestamp)
    readingChartData.GpsY.data.push(e.gpsReading.gps_y)
    if (readingChartData.GpsY.labels.length > 30) {
        readingChartData.GpsY.labels.shift()
        readingChartData.GpsY.data.shift()
    }

    if (readingChart) {
        updateReadingChart()
    }
})

// Listen for IMU ReadingUpdated events
Echo.private('imu-reading-updates').listen('ImuReadingUpdated', (e) => {
    const timestamp = e.imuReading.time

    // Update IMU Acceleration data
    readingChartData.IMUAccelerationX.labels.push(timestamp)
    readingChartData.IMUAccelerationX.data.push(e.imuReading.imu_acceleration_x)
    if (readingChartData.IMUAccelerationX.labels.length > 30) {
        readingChartData.IMUAccelerationX.labels.shift()
        readingChartData.IMUAccelerationX.data.shift()
    }

    readingChartData.IMUAccelerationY.labels.push(timestamp)
    readingChartData.IMUAccelerationY.data.push(e.imuReading.imu_acceleration_y)
    if (readingChartData.IMUAccelerationY.labels.length > 30) {
        readingChartData.IMUAccelerationY.labels.shift()
        readingChartData.IMUAccelerationY.data.shift()
    }

    readingChartData.IMUAccelerationZ.labels.push(timestamp)
    readingChartData.IMUAccelerationZ.data.push(e.imuReading.imu_acceleration_z)
    if (readingChartData.IMUAccelerationZ.labels.length > 30) {
        readingChartData.IMUAccelerationZ.labels.shift()
        readingChartData.IMUAccelerationZ.data.shift()
    }

    // Update IMU Angular Velocity data
    readingChartData.IMUAngularVelocityX.labels.push(timestamp)
    readingChartData.IMUAngularVelocityX.data.push(e.imuReading.imu_angular_velocity_x)
    if (readingChartData.IMUAngularVelocityX.labels.length > 30) {
        readingChartData.IMUAngularVelocityX.labels.shift()
        readingChartData.IMUAngularVelocityX.data.shift()
    }

    readingChartData.IMUAngularVelocityY.labels.push(timestamp)
    readingChartData.IMUAngularVelocityY.data.push(e.imuReading.imu_angular_velocity_y)
    if (readingChartData.IMUAngularVelocityY.labels.length > 30) {
        readingChartData.IMUAngularVelocityY.labels.shift()
        readingChartData.IMUAngularVelocityY.data.shift()
    }

    readingChartData.IMUAngularVelocityZ.labels.push(timestamp)
    readingChartData.IMUAngularVelocityZ.data.push(e.imuReading.imu_angular_velocity_z)
    if (readingChartData.IMUAngularVelocityZ.labels.length > 30) {
        readingChartData.IMUAngularVelocityZ.labels.shift()
        readingChartData.IMUAngularVelocityZ.data.shift()
    }

    if (readingChart) {
        updateReadingChart()
    }
})

// ----------------------------------------------------
// 2) Fused Measurements Chart Setup (NEW SECTION)
// ----------------------------------------------------
let fusedChart = null
const selectedFusedChart = ref('PositionX')

const fusedChartData = {
    PositionX: { labels: [], data: [], color: 'blueviolet' },
    PositionY: { labels: [], data: [], color: 'indigo' },
    VelocityX: { labels: [], data: [], color: 'limegreen' },
    VelocityY: { labels: [], data: [], color: 'tomato' },
}

// Listen for FusedMeasurementUpdated events
Echo.private('measurement-updates').listen('MeasurementUpdated', (e) => {
    const timestamp = e.fusedMeasurement.time

    fusedChartData.PositionX.labels.push(timestamp)
    fusedChartData.PositionX.data.push(e.fusedMeasurement.position_x)
    if (fusedChartData.PositionX.labels.length > 30) {
        fusedChartData.PositionX.labels.shift()
        fusedChartData.PositionX.data.shift()
    }

    fusedChartData.PositionY.labels.push(timestamp)
    fusedChartData.PositionY.data.push(e.fusedMeasurement.position_y)
    if (fusedChartData.PositionY.labels.length > 30) {
        fusedChartData.PositionY.labels.shift()
        fusedChartData.PositionY.data.shift()
    }

    fusedChartData.VelocityX.labels.push(timestamp)
    fusedChartData.VelocityX.data.push(e.fusedMeasurement.velocity_x)
    if (fusedChartData.VelocityX.labels.length > 30) {
        fusedChartData.VelocityX.labels.shift()
        fusedChartData.VelocityX.data.shift()
    }

    fusedChartData.VelocityY.labels.push(timestamp)
    fusedChartData.VelocityY.data.push(e.fusedMeasurement.velocity_y)
    if (fusedChartData.VelocityY.labels.length > 30) {
        fusedChartData.VelocityY.labels.shift()
        fusedChartData.VelocityY.data.shift()
    }

    if (fusedChart) {
        updateFusedChart()
    }
})

// -----------------------------------------------------
// 3) Location Chart Setup (existing code, unchanged)
// -----------------------------------------------------
let locationChart = null
const selectedLocationChart = ref('Latitude')

const chartData = {
    Latitude: { labels: [], data: [], color: 'red' },
    Longitude: { labels: [], data: [], color: 'blue' },
    Speed: { labels: [], data: [], color: 'green' },
    Bearing: { labels: [], data: [], color: 'orange' },
}

// Listen for LocationUpdated events
Echo.private('location-updates').listen('LocationUpdated', (e) => {
    const timestamp = e.location.timestamp

    chartData.Latitude.labels.push(timestamp)
    chartData.Latitude.data.push(e.location.latitude)
    if (chartData.Latitude.labels.length > 30) {
        chartData.Latitude.labels.shift()
        chartData.Latitude.data.shift()
    }

    chartData.Longitude.labels.push(timestamp)
    chartData.Longitude.data.push(e.location.longitude)
    if (chartData.Longitude.labels.length > 30) {
        chartData.Longitude.labels.shift()
        chartData.Longitude.data.shift()
    }

    chartData.Speed.labels.push(timestamp)
    chartData.Speed.data.push(e.location.speed)
    if (chartData.Speed.labels.length > 30) {
        chartData.Speed.labels.shift()
        chartData.Speed.data.shift()
    }

    chartData.Bearing.labels.push(timestamp)
    chartData.Bearing.data.push(e.location.bearing)
    if (chartData.Bearing.labels.length > 30) {
        chartData.Bearing.labels.shift()
        chartData.Bearing.data.shift()
    }

    if (locationChart) {
        updateLocationChart()
    }
})

// ---------------------
// 4) onMounted (Charts)
// ---------------------
onMounted(() => {
    // Reading chart
    const readCtx = document.getElementById('readingChart').getContext('2d')
    readingChart = new Chart(readCtx, {
        type: 'line',
        data: {
            labels: readingChartData[selectedReadingChart.value].labels,
            datasets: [{
                label: selectedReadingChart.value,
                data: readingChartData[selectedReadingChart.value].data,
                borderColor: readingChartData[selectedReadingChart.value].color,
                backgroundColor: readingChartData[selectedReadingChart.value].color,
                fill: false,
                tension: 0.1,
                pointRadius: 4,
                pointHoverRadius: 6
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
                    time: {
                        tooltipFormat: 'HH:mm:ss',
                        unit: 'second',
                        displayFormats: { second: 'HH:mm:ss' }
                    },
                    title: { display: true, text: 'Time (HH:MM:SS)' }
                },
                y: {
                    title: { display: true, text: selectedReadingChart.value },
                    beginAtZero: false
                }
            }
        }
    })

    // Fused Measurements chart (NEW)
    const fusedCtx = document.getElementById('fusedChart').getContext('2d')
    fusedChart = new Chart(fusedCtx, {
        type: 'line',
        data: {
            labels: fusedChartData[selectedFusedChart.value].labels,
            datasets: [{
                label: selectedFusedChart.value,
                data: fusedChartData[selectedFusedChart.value].data,
                borderColor: fusedChartData[selectedFusedChart.value].color,
                backgroundColor: fusedChartData[selectedFusedChart.value].color,
                fill: false,
                tension: 0.1,
                pointRadius: 4,
                pointHoverRadius: 6
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
                    time: {
                        tooltipFormat: 'HH:mm:ss',
                        unit: 'second',
                        displayFormats: { second: 'HH:mm:ss' }
                    },
                    title: { display: true, text: 'Time (HH:MM:SS)' }
                },
                y: {
                    title: { display: true, text: selectedFusedChart.value },
                    beginAtZero: false
                }
            }
        }
    })

    // Location chart
    const locCtx = document.getElementById('locationChart').getContext('2d')
    locationChart = new Chart(locCtx, {
        type: 'line',
        data: {
            labels: chartData[selectedLocationChart.value].labels,
            datasets: [{
                label: selectedLocationChart.value,
                data: chartData[selectedLocationChart.value].data,
                borderColor: chartData[selectedLocationChart.value].color,
                backgroundColor: chartData[selectedLocationChart.value].color,
                fill: false,
                tension: 0.1,
                pointRadius: 4,
                pointHoverRadius: 6
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
                    time: {
                        tooltipFormat: 'HH:mm:ss',
                        unit: 'second',
                        displayFormats: { second: 'HH:mm:ss' }
                    },
                    title: { display: true, text: 'Time (HH:MM:SS)' }
                },
                y: {
                    title: { display: true, text: selectedLocationChart.value },
                    beginAtZero: false
                }
            }
        }
    })
})

// ----------------------------------
// 5) Watchers for chart selections
// ----------------------------------
watch(selectedReadingChart, () => {
    if (readingChart) {
        updateReadingChart()
    }
})

watch(selectedFusedChart, () => {
    if (fusedChart) {
        updateFusedChart()
    }
})

watch(selectedLocationChart, () => {
    if (locationChart) {
        updateLocationChart()
    }
})

// -------------------------------
// 6) Update Chart Functions
// -------------------------------
function updateReadingChart() {
    const dataset = readingChartData[selectedReadingChart.value]
    readingChart.data.labels = dataset.labels
    readingChart.data.datasets[0].label = selectedReadingChart.value
    readingChart.data.datasets[0].data = dataset.data
    readingChart.data.datasets[0].borderColor = dataset.color
    readingChart.data.datasets[0].backgroundColor = dataset.color
    readingChart.options.scales.y.title.text = selectedReadingChart.value
    readingChart.update()
}

function updateFusedChart() {
    const dataset = fusedChartData[selectedFusedChart.value]
    fusedChart.data.labels = dataset.labels
    fusedChart.data.datasets[0].label = selectedFusedChart.value
    fusedChart.data.datasets[0].data = dataset.data
    fusedChart.data.datasets[0].borderColor = dataset.color
    fusedChart.data.datasets[0].backgroundColor = dataset.color
    fusedChart.options.scales.y.title.text = selectedFusedChart.value
    fusedChart.update()
}

function updateLocationChart() {
    const dataset = chartData[selectedLocationChart.value]
    locationChart.data.labels = dataset.labels
    locationChart.data.datasets[0].label = selectedLocationChart.value
    locationChart.data.datasets[0].data = dataset.data
    locationChart.data.datasets[0].borderColor = dataset.color
    locationChart.data.datasets[0].backgroundColor = dataset.color
    locationChart.options.scales.y.title.text = selectedLocationChart.value
    locationChart.update()
}
</script>

<script>
import WorkZoneLayout from '@/Layouts/WorkZoneLayout.vue';

export default {
    layout: (h, page) => h(
        WorkZoneLayout,
        { work_zone: page.props.work_zone },
        () => page
    )
}
</script>

<template>

    <Head title="Dashboard" />

    <div class="container-fluid py-3">
        <!-- Reading Chart -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h4 class="font-weight-normal">
                            Reading: {{ selectedReadingChart }}
                        </h4>
                    </div>
                    <div class="col-6 text-right">
                        <select class="form-control" v-model="selectedReadingChart">
                            <option value="GpsX">GPS X</option>
                            <option value="GpsY">GPS Y</option>
                            <option value="IMUAccelerationX">IMU Accel X</option>
                            <option value="IMUAccelerationY">IMU Accel Y</option>
                            <option value="IMUAccelerationZ">IMU Accel Z</option>
                            <option value="IMUAngularVelocityX">IMU AngVel X</option>
                            <option value="IMUAngularVelocityY">IMU AngVel Y</option>
                            <option value="IMUAngularVelocityZ">IMU AngVel Z</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas class="canvas" id="readingChart"></canvas>
            </div>
        </div>

        <!-- Fused Measurements Chart (NEW) -->
        <div class="card my-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h4 class="font-weight-normal">
                            Fused Measurement: {{ selectedFusedChart }}
                        </h4>
                    </div>
                    <div class="col-6 text-right">
                        <select class="form-control" v-model="selectedFusedChart">
                            <option value="PositionX">Position X</option>
                            <option value="PositionY">Position Y</option>
                            <option value="VelocityX">Velocity X</option>
                            <option value="VelocityY">Velocity Y</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas class="canvas" id="fusedChart"></canvas>
            </div>
        </div>

        <!-- Location Chart -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h4 class="font-weight-normal">
                            Location: {{ selectedLocationChart }}
                        </h4>
                    </div>
                    <div class="col-6 text-right">
                        <select class="form-control" v-model="selectedLocationChart">
                            <option value="Latitude">Latitude</option>
                            <option value="Longitude">Longitude</option>
                            <option value="Speed">Speed</option>
                            <option value="Bearing">Bearing</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas class="canvas" id="locationChart"></canvas>
            </div>
        </div>
    </div>
</template>

<style scoped>
.canvas {
    width: 100%;
    height: 30rem;
}
</style>
