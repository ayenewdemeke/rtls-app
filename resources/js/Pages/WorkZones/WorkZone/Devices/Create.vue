<script setup>
import Req from '@/Components/RequiredIndicator.vue'
import InputCard from '@/Components/InputCard.vue'
import InputError from '@/Components/InputError.vue'
import { useForm } from '@inertiajs/vue3'

defineProps({
    work_zone: Object,
    device_types: Object,
    device_statuses: Object
})

const form = useForm({
    device_id: '',
    device_type_id: '',
    device_status_id: '',
    description: ''
})

const submit = (id) => {
    form.post(route('user.work_zone.devices.store', id), {
        onFinish: () => {
            form.reset()
        },
    })
}
</script>
<script>
import WorkZoneLayout from '@/Layouts/WorkZoneLayout.vue';
export default {
    layout: (h, page) => h(WorkZoneLayout, { 'work_zone': page.props.work_zone }, () => page)
}
</script>

<template>

    <Head title="Add device" />

    <h3 class="mb-4 ms-3 pt-4 font-weight-normal">Add device</h3>
    <div class="container-fluid">
        <form @submit.prevent="submit(work_zone.id)">
            <InputCard title="Basic data">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="container-fluild form-group">
                            <label for="device_id">Device ID
                                <Req />
                            </label>
                            <div class="input-group mb-3">
                                <input id="device_id" type="text" class="form-control" v-model="form.device_id"
                                    name="device_id" placeholder="Device ID" required autocomplete="device_id">
                            </div>
                            <InputError :message="form.errors.device_id"></InputError>
                        </div>
                        <div>
                            <label for="device_type_id">Device type
                                <Req />
                            </label>
                            <div class="input-group mb-3">
                                <select id="device_type_id" name="device_type_id" class="form-control"
                                    v-model="form.device_type_id" required>
                                    <option value="">Type</option>
                                    <option v-for="device_type in device_types" :value="device_type.id"
                                        :key="device_type.id">{{ device_type.name }}</option>
                                </select>
                            </div>
                            <InputError :message="form.errors.device_type_id"></InputError>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div>
                            <label for="device_type_id">Device status
                                <Req />
                            </label>
                            <div class="input-group mb-3">
                                <select id="device_status_id" name="device_status_id" class="form-control"
                                    v-model="form.device_status_id" required>
                                    <option value="">Status</option>
                                    <option v-for="device_status in device_statuses" :value="device_status.id"
                                        :key="device_status.id">{{ device_status.name }}</option>
                                </select>
                            </div>
                            <InputError :message="form.errors.device_status_id"></InputError>
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
                <Link :href="route('user.work_zone.devices.index', work_zone.id)" class="btn btn-outline-primary mr-2">
                Back</Link>
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