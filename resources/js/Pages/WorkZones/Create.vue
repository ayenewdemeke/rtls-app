<script setup>
import { Map, View, Feature } from 'ol'
import Tile from 'ol/layer/Tile'
import OSM from 'ol/source/OSM'
import { fromLonLat, transform } from 'ol/proj'
import Point from 'ol/geom/Point'
import { Style } from 'ol/style'
import Icon from 'ol/style/Icon'
import VectorLayer from 'ol/layer/Vector'
import VectorSource from 'ol/source/Vector'
import { onMounted, watch } from 'vue'
import Req from '@/Components/RequiredIndicator.vue'
import InputCard from '@/Components/InputCard.vue'
import InputError from '@/Components/InputError.vue'
import { useForm } from '@inertiajs/vue3'

defineProps({
    work_zone_statuses: Object,
    system_statuses: Object
})

const form = useForm({
    work_zone_id: '',
    name: '',
    latitude: '',
    longitude: '',
    location: '',
    start_date: '',
    work_zone_status_id: '',
    description: '',
    image: '',
    system_id: '',
    system_status_id: '',
    system_start_date: ''
})

const acceptImage = (event) => {
    form.image = event.target.files[0]
    document.getElementById('imageview').src = window.URL.createObjectURL(event.target.files[0])
}

const submit = () => {
    form.post(route('user.work_zones.store'), {
        onFinish: () => {
            form.reset()
        },
    })
}

let layer;
let map;

const updatePinLocation = () => {
    const cord = [parseFloat(form.longitude), parseFloat(form.latitude)];
    if (!isNaN(cord[0]) && !isNaN(cord[1])) {
        map.removeLayer(layer);
        const myStyle = new Style({
            image: new Icon({
                anchor: [0.5, 35],
                anchorXUnits: 'fraction',
                anchorYUnits: 'pixels',
                src: '/image/map/icons/pin.png'
            })
        });
        layer = new VectorLayer({
            source: new VectorSource({
                features: [
                    new Feature({
                        geometry: new Point(fromLonLat(cord))
                    })
                ]
            }),
            style: myStyle
        });
        map.addLayer(layer);
    }
}

watch(() => form.latitude, updatePinLocation);
watch(() => form.longitude, updatePinLocation);

onMounted(() => {
    if (!document.getElementById('map').firstChild) {
        map = new Map({
            target: 'map',
            layers: [
                new Tile({
                    source: new OSM()
                })
            ],
            view: new View({
                center: fromLonLat([-94.64, 46.39]),
                zoom: 5
            }),
        })
    }
    map.on('singleclick', function (evt) {
        const cord = transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326')
        form.latitude = cord[1]
        form.longitude = cord[0]
        updatePinLocation()
    })
})
</script>
<script>
import UserLayout from '@/Layouts/UserLayout.vue';
export default {
    layout: UserLayout
}
</script>

<template>

    <Head title="Add work zone" />

    <h3 class="mb-4 ms-3 pt-4 font-weight-normal">Add work zone</h3>
    <div class="container-fluid">
        <form @submit.prevent="submit">
            <InputCard title="Basic data">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="container-fluild form-group">
                            <label for="work_zone_id">Work zone ID
                                <Req />
                            </label>
                            <div class="input-group mb-3">
                                <input id="work_zone_id" type="text" class="form-control" v-model="form.work_zone_id"
                                    name="work_zone_id" placeholder="Work zone ID" required autocomplete="work_zone_id">
                            </div>
                            <InputError :message="form.errors.work_zone_id"></InputError>
                        </div>
                        <div class="container-fluild form-group">
                            <label for="name">Name
                                <Req />
                            </label>
                            <div class="input-group mb-3">
                                <input id="name" type="text" class="form-control" v-model="form.name" name="name"
                                    placeholder="Name" required autocomplete="name">
                            </div>
                            <InputError :message="form.errors.name"></InputError>
                        </div>
                        <div>
                            <label for="work_zone_status_id">Status
                                <Req />
                            </label>
                            <div class="input-group mb-3">
                                <select id="work_zone_status_id" name="work_zone_status_id" class="form-control"
                                    v-model="form.work_zone_status_id" required>
                                    <option value="">Status</option>
                                    <option v-for="work_zone_status in work_zone_statuses" :value="work_zone_status.id"
                                        :key="work_zone_status.id">{{ work_zone_status.name }}</option>
                                </select>
                            </div>
                            <InputError :message="form.errors.work_zone_status_id"></InputError>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="container-fluild form-group">
                            <label for="start_date">Start date
                                <Req />
                            </label>
                            <div class="input-group mb-3">
                                <input id="start_date" type="date" class="form-control" v-model="form.start_date"
                                    name="start_date" placeholder="Start date" autocomplete="start_date" required>
                            </div>
                            <InputError :message="form.errors.start_date"></InputError>
                        </div>
                        <div class="container-fluild form-group">
                            <label for="image">Image</label>
                            <div class="input-group mb-3">
                                <input type="file" accept="image/*" @input="acceptImage" />
                                <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                    {{ form.progress.percentage }}%
                                </progress>
                            </div>
                            <InputError :message="form.errors.image"></InputError>
                            <img id="imageview" src="">
                        </div>
                    </div>
                </div>
            </InputCard>
            <InputCard title="Coordinates">
                <div class="row">
                    <div class="col-md-8">
                        <div>Please zoom the map well and place the location accurately</div>
                        <div id="map"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="container-fluild form-group">
                            <label for="location">Location</label>
                            <div class="input-group mb-3">
                                <input id="location" type="text" class="form-control" v-model="form.location"
                                    name="location" placeholder="Location" autocomplete="location" required>
                            </div>
                            <InputError :message="form.errors.location"></InputError>
                        </div>
                        <div class="container-fluild form-group">
                            <label for="latitude">Latitude (deg)
                                <Req />
                            </label>
                            <div class="input-group mb-3">
                                <input id="latitude" type="number" step="0.00000000000000001" class="form-control"
                                    v-model="form.latitude" name="latitude" placeholder="Latitude" required
                                    autocomplete="latitude">
                            </div>
                            <InputError :message="form.errors.latitude"></InputError>
                        </div>
                        <div class="container-fluild form-group">
                            <label for="longitude">Longitude (deg)
                                <Req />
                            </label>
                            <div class="input-group mb-3">
                                <input id="longitude" type="number" step="0.00000000000000001" class="form-control"
                                    v-model="form.longitude" name="longitude" placeholder="Longitude" required
                                    autocomplete="longitude">
                            </div>
                            <InputError :message="form.errors.longitude"></InputError>
                        </div>
                    </div>
                </div>
            </InputCard>
            <InputCard title="System">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="container-fluild form-group">
                            <label for="system_id">System ID</label>
                            <div class="input-group mb-3">
                                <input id="system_id" type="text" class="form-control" v-model="form.system_id"
                                    name="system_id" placeholder="System ID" autocomplete="system_id">
                            </div>
                            <InputError :message="form.errors.system_id"></InputError>
                        </div>
                        <div>
                            <label for="system_status_id">System status</label>
                            <div class="input-group mb-3">
                                <select id="system_status_id" name="system_status_id" class="form-control"
                                    v-model="form.system_status_id">
                                    <option value="">Status</option>
                                    <option v-for="system_status in system_statuses" :value="system_status.id"
                                        :key="system_status.id">{{ system_status.name }}</option>
                                </select>
                            </div>
                            <InputError :message="form.errors.system_status_id"></InputError>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="container-fluild form-group">
                            <label for="system_start_date">System start date</label>
                            <div class="input-group mb-3">
                                <input id="system_start_date" type="date" class="form-control"
                                    v-model="form.system_start_date" name="system_start_date" placeholder="Start date"
                                    autocomplete="system_start_date">
                            </div>
                            <InputError :message="form.errors.system_start_date"></InputError>
                        </div>
                    </div>
                </div>
            </InputCard>
            <InputCard title="Description">
                <div class="row">
                    <div class="col">
                        <div class="container-fluild form-group">
                            <div class="input-group">
                                <textarea placeholder="Description" v-model="form.description" class="form-control"
                                    name="description" id="description" autocomplete="description" rows="5"></textarea>
                            </div>
                            <InputError :message="form.errors.description"></InputError>
                        </div>
                    </div>
                </div>
            </InputCard>
            <div class="text-right py-3">
                <Link :href="route('user.work_zones.index')" class="btn btn-outline-primary mr-2">Back</Link>
                <button type="submit" class="btn btn-primary mr-2">Add
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped>
#imageview {
    height: 5rem;
}

#map {
    height: 400px;
    width: 100%;
}

button {
    width: 6rem;
}
</style>
<style>
@import "ol/ol.css";
</style>