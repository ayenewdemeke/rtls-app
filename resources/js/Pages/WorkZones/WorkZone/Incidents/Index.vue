<script setup>
import List from '@/Components/List.vue'
defineProps({
    work_zone: Object,
    incidents: Object,
})
</script>

<script>
import WorkZoneLayout from '@/Layouts/WorkZoneLayout.vue';
export default {
    layout: (h, page) => h(WorkZoneLayout, { 'work_zone': page.props.work_zone }, () => page)
}
</script>

<template>

    <Head title="Incidents" />
    <div class="container-fluid">
        <List>
            <template v-slot:title>
                <div class="col-6">
                    <h4 class="font-weight-normal">Incidents</h4>
                </div>
            </template>
            <template v-slot:thead>
                <tr>
                    <th>#</th>
                    <th>Incident type</th>
                    <th>Date</th>
                    <th>Devices involved</th>
                </tr>
            </template>
            <template v-if="Object.keys(incidents).length" v-slot:tbody>
                <tr v-for="incident, index in incidents" :key="incident.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ incident.incident_type.name }}</td>
                    <td>{{ new Date(incident.date).toLocaleDateString() }}</td>
                    <td>{{ incident.devices.map((device) => device.device_id).join(', ') }}</td>
                </tr>
            </template>
            <template v-else v-slot:tbody>
                <tr>
                    <td colspan="6" class="text-center">
                        No incidents
                    </td>
                </tr>
            </template>
        </List>
    </div>
</template>
