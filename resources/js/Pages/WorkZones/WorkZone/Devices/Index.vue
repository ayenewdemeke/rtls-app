<script setup>
import List from '@/Components/List.vue'
defineProps({
    work_zone: Object,
    devices: Object,
})
</script>

<script>
import WorkZoneLayout from '@/Layouts/WorkZoneLayout.vue';
export default {
    layout: (h, page) => h(WorkZoneLayout, { 'work_zone': page.props.work_zone }, () => page)
}
</script>

<template>

    <Head title="Devices" />
    <div class="container-fluid">
        <List>
            <template v-slot:title>
                <div class="col-6">
                    <h4 class="font-weight-normal">Devices</h4>
                </div>
                <div class="col-6 text-right">
                    <Link class="btn btn-primary" :href="route('user.work_zone.devices.create', work_zone.id)">Add
                    device
                    </Link>
                </div>
            </template>
            <template v-slot:thead>
                <tr>
                    <th>#</th>
                    <th>MAC Address</th>
                    <th>Device type</th>
                    <th>Device status</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th></th>
                </tr>
            </template>
            <template v-if="Object.keys(devices).length" v-slot:tbody>
                <tr v-for="device, index in devices" :key="device.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ device.mac_address }}</td>
                    <td>{{ device.device_type.name }}</td>
                    <td :class="{
                        'text-primary': device.device_status_id === 1, // Pending
                        'text-success': device.device_status_id === 2, // Working
                        'text-warning': device.device_status_id === 3, // Under maintenance
                        'text-danger': device.device_status_id === 4, // Not working
                    }">{{ device.device_status.name }}</td>
                    <td>{{ device.latitude }}</td>
                    <td>{{ device.longitude }}</td>
                    <td>
                        <Link :href="'#'">Details
                        </Link>
                    </td>
                </tr>
            </template>
            <template v-else v-slot:tbody>
                <tr>
                    <td colspan="6" class="text-center">
                        No devices
                    </td>
                </tr>
            </template>
        </List>
    </div>
</template>
