<script setup>
import { Map, View, Feature } from 'ol'
import Tile from 'ol/layer/Tile'
import OSM from 'ol/source/OSM'
import { Point } from 'ol/geom'
import { fromLonLat } from 'ol/proj'
import VectorLayer from 'ol/layer/Vector'
import VectorSource from 'ol/source/Vector'
import { Style, Icon } from 'ol/style'
import { onMounted } from 'vue'
import ShowCard from '@/Components/ShowCard.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    work_zone: Object
})

const deleteForm = useForm({
    email: ''
})

const deleteWorkZone = (id) => {
    document.getElementById('close-delete').click()
    deleteForm.delete(route('user.work_zones.destroy', id), {
        onFinish: () => {
            deleteForm.reset()
        },
    })
}

let map

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
                center: fromLonLat([props.work_zone.longitude, props.work_zone.latitude]), // Center on work zone
                zoom: 6
            }),
        })

        // Create a point feature for the work zone location
        const pointFeature = new Feature({
            geometry: new Point(fromLonLat([props.work_zone.longitude, props.work_zone.latitude]))
        });

        // Style the marker
        pointFeature.setStyle(new Style({
            image: new Icon({
                anchor: [0.5, 1],
                src: 'https://cdn-icons-png.flaticon.com/512/684/684908.png', // Marker icon URL
                scale: 0.05
            })
        }))

        // Create a vector layer for the marker
        const vectorLayer = new VectorLayer({
            source: new VectorSource({
                features: [pointFeature]
            })
        })

        // Add the marker layer to the map
        map.addLayer(vectorLayer)
    }
})
</script>
<script>
import UserLayout from '@/Layouts/UserLayout.vue';
export default {
    layout: UserLayout
}
</script>

<template>

    <Head title="Work zone details" />

    <h3 class="mb-4 ms-3 pt-4 font-weight-normal">Work zone details</h3>
    <ShowCard title="Basic data">
        <div class="row">
            <div class="col-lg-6">
                <div class="container-fluild form-group">
                    <label for="work_zone_id">Work zone ID</label>
                    <div>
                        {{ work_zone.work_zone_id }}
                    </div>
                </div>
                <div class="container-fluild form-group">
                    <label for="name">Name</label>
                    <div>
                        {{ work_zone.name }}
                    </div>
                </div>
                <div>
                    <label for="work_zone_status_id">Status</label>
                    <div>
                        {{ work_zone.work_zone_status.name }}
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="container-fluild form-group">
                    <label for="start_date">Start date</label>
                    <div>
                        {{ work_zone.start_date }}
                    </div>
                </div>
                <div class="container-fluild form-group">
                    <label for="location">Location</label>
                    <div>
                        {{ work_zone.location }}
                    </div>
                </div>
            </div>
        </div>
    </ShowCard>
    <ShowCard title="Map and image">
        <div class="row">
            <div class="col-md-6">
                <div>Please zoom the map well and place the location accurately</div>
                <div id="map"></div>
            </div>
            <div class="col-md-6">
                <img v-if="work_zone.image" :src="'/storage/work_zone/image/' + work_zone.image" class="pt-4"
                    alt="image">
                <img v-else :src="'/storage/work_zone/image/no-image.jpg'" class="pt-4" alt="image">
            </div>
        </div>
    </ShowCard>
    <ShowCard title="Description">
        <div class="row">
            <div class="col">
                <div class="container-fluild form-group">
                    {{ work_zone.description }}
                </div>
            </div>
        </div>
    </ShowCard>
    <div class="text-right py-3">
        <Link :href="route('user.work_zones.index')" class="btn btn-outline-primary mr-2">Back</Link>
        <button type="button" class="btn btn-danger mr-2" data-bs-toggle="modal" data-bs-target="#deleteModal">
            Delete
        </button>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete confirmation</h5>
                </div>
                <form @submit.prevent="deleteWorkZone(work_zone.id)">
                    <div class="modal-body">
                        <p>Are you sure you want to delete the work zone? This action cannot be undone.</p>
                        <div class="mb-3">
                            <label for="emailInput" class="form-label">Enter your email to confirm deletion</label>
                            <input type="email" id="emailInput" v-model="deleteForm.email" class="form-control"
                                placeholder="Enter your email" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="close-delete" class="btn btn-outline-primary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
#imageview {
    height: 5rem;
}

#map {
    height: 23rem;
    width: 100%;
}

img {
    height: 23rem !important;
    width: 100%;
}

button {
    width: 6rem;
}
</style>
<style>
@import "ol/ol.css";
</style>