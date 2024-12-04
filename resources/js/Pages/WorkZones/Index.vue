<script setup>
import List from '@/Components/List.vue'
defineProps({
    work_zones: Object,
})
</script>

<script>
import UserLayout from '@/Layouts/UserLayout.vue';
export default {
    layout: UserLayout
}
</script>

<template>

    <Head title="Work zones" />
    <div class="container-fluid">
        <List>
            <template v-slot:title>
                <div class="col-6">
                    <h4 class="font-weight-normal">Work zones</h4>
                </div>
                <div class="col-6 text-right">
                    <Link class="btn btn-primary" :href="route('user.work_zones.create')">Add
                    work zone
                    </Link>
                </div>
            </template>
            <template v-slot:thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Date added</th>
                    <th>Location</th>
                    <th>System status</th>
                    <th></th>
                    <th></th>
                </tr>
            </template>
            <template v-if="Object.keys(work_zones).length" v-slot:tbody>
                <tr v-for="work_zone, index in work_zones" :key="work_zone.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ work_zone.name }}</td>
                    <td>{{ new Date(work_zone.start_date).toLocaleDateString() }}</td>
                    <td>{{ work_zone.location }}</td>
                    <td :class="{
                        'text-primary': work_zone.work_zone_status.name === 'Pending',
                        'text-success': work_zone.work_zone_status.name === 'Active',
                        'text-danger': work_zone.work_zone_status.name === 'Inactive'
                    }">
                        {{ work_zone.work_zone_status.name }}
                    </td>
                    <td>
                        <Link :href="route('user.work_zones.show', work_zone.id)">Details
                        </Link>
                    </td>
                    <td>
                        <a :href="route('user.work_zone.dashboard', work_zone.id)">Open
                        </a>
                    </td>
                </tr>
            </template>
            <template v-else v-slot:tbody>
                <tr>
                    <td colspan="6" class="text-center">
                        No work zones yet
                    </td>
                </tr>
            </template>
        </List>
    </div>
</template>