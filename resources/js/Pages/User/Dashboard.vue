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
import { Chart, registerables } from 'chart.js';

const props = defineProps({
    work_zones: Array,
})

Chart.register(...registerables);

onMounted(() => {
    const ctx1 = document.getElementById('trendChart').getContext('2d');

    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Near Misses',
                    data: [20, 18, 19, 17, 18, 16, 18, 17, 16, 17, 18, 15],
                    borderColor: 'orange',
                    backgroundColor: 'orange',
                    fill: false,
                    tension: 0.1,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Intrusions',
                    data: [16, 15, 14, 15, 16, 15, 14, 15, 14, 15, 16, 14],
                    borderColor: 'red',
                    backgroundColor: 'red',
                    fill: false,
                    tension: 0.1,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: 'False Alarms',
                    data: [14, 13, 14, 13, 14, 13, 12, 13, 12, 13, 14, 13],
                    borderColor: 'black',
                    backgroundColor: 'black',
                    fill: false,
                    tension: 0.1,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Trend of Near Misses, Intrusions, and False Alarms'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Count'
                    },
                    beginAtZero: false,
                    min: 12
                }
            }
        }
    });

    const ctx2 = document.getElementById('workZoneChart').getContext('2d');

    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['WZ 1', 'WZ 2', 'WZ 3', 'WZ 4'],
            datasets: [
                {
                    label: 'Near Misses',
                    data: [12, 10, 15, 13], // Dummy data for Near Misses
                    backgroundColor: '#5bc0de'
                },
                {
                    label: 'Intrusions',
                    data: [8, 7, 10, 9], // Dummy data for Intrusions
                    backgroundColor: 'orange'
                }
            ]
        },
        options: {
            indexAxis: 'y',
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Number of Incidents per Work Zone'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Number of Incidents'
                    },
                    beginAtZero: true
                },
                y: {
                    title: {
                        display: true,
                        text: 'Work Zones'
                    }
                }
            }
        }
    });

    const ctx3 = document.getElementById('sensorChart').getContext('2d');

    new Chart(ctx3, {
        type: 'doughnut',
        data: {
            labels: ['Equipment', 'Traffic Cones', 'Workers'],
            datasets: [
                {
                    label: 'Total Number of Sensors',
                    data: [25, 34, 50], // Dummy data for the count of sensors
                    backgroundColor: ['#FF9F40', '#36A2EB', '#4BC0C0'], // Colors for each segment
                    hoverOffset: 4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Total Number of Sensors'
                }
            }
        }
    });
});

onMounted(() => {
    // Initialize map
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
        }),
    })

    // Define style for the icons
    const myStyle = new Style({
        image: new Icon({
            anchor: [0.5, 35],
            anchorXUnits: 'fraction',
            anchorYUnits: 'pixels',
            src: '/image/map/icons/pin.png'
        })
    })

    // Create a Vector Source to hold all work zone features
    const vectorSource = new VectorSource()

    // Add each work zone as a feature to the vector source
    props.work_zones.forEach(zone => {
        const feature = new Feature({
            geometry: new Point(fromLonLat([zone.longitude, zone.latitude]))
        })
        vectorSource.addFeature(feature)
    })

    // Create a Vector Layer with all work zone features
    const layer = new VectorLayer({
        source: vectorSource,
        style: myStyle
    })

    // Add the vector layer to the map
    map.addLayer(layer)

    // Tooltip for displaying info on hover
    const tooltip = document.getElementById('tooltip')
    const overlay = new Overlay({
        element: tooltip,
        offset: [10, 0],
        positioning: 'bottom-left'
    })
    map.addOverlay(overlay)

    // Display tooltip on hover
    function displayTooltip(evt) {
        const pixel = evt.pixel
        const feature = map.forEachFeatureAtPixel(pixel, function (feature) {
            return feature
        })
        tooltip.style.display = feature ? '' : 'none'
        if (feature) {
            overlay.setPosition(evt.coordinate)
            tooltip.innerHTML = 'Work zone location'
        }
    }

    map.on('pointermove', displayTooltip)
})
</script>

<script>
import UserLayout from '@/Layouts/UserLayout.vue';
export default {
    layout: (h, page) => h(UserLayout, { 'work_zone': page.props.work_zone }, () => page)
}
</script>

<template>

    <Head title="Dashboard" />

    <div class="container-fluid py-3">
        <h1 class="header my-3">Dashboard</h1>
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner py-1">
                        <h3>10</h3>
                        <p class="pb-1">Work zones</p>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner py-1">
                        <h3>10</h3>
                        <p class="pb-1">Systems working</p>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner py-1">
                        <h3>0</h3>
                        <p class="pb-1">Systems not working</p>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner py-1">
                        <h3>5</h3>
                        <p class="pb-1">Near miss and intrusion incidents</p>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7">
                <div class="card p-3">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card p-3">
                    <canvas id="workZoneChart"></canvas>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7">
                <div class="card p-3">
                    <div id="map"></div>
                    <div id="tooltip" class="tooltip"
                        style="display: none; position: absolute; background-color: white; padding: 5px; border: 1px solid black; border-radius: 3px;">
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card p-3">
                    <canvas id="sensorChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
@import 'ol/ol.css';
</style>
<style scoped>
#map {
    height: 30rem;
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