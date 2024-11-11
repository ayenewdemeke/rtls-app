<script setup>
</script>
<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
export default {
    layout: GuestLayout
}
</script>

<template>

    <Head title="Home" />

    <div class="d-none d-lg-block">
        <div class="container vh-100 d-flex align-items-center">
            <div class="row m-2">
                <div class="col-lg-6">
                    <h1 class="h1-large text-uppercase mb-4">MnDOT's Work Zone Safety Alert System</h1>
                    <p>This system is developed specifically for MnDOT highway construction sites to enhance worker
                        safety through real-time monitoring and alerts. It detects both near-miss and intrusion events
                        using wearable sensors that notify workers immediately in case of hazardous situations.</p>
                    <p>Designed as an IoT-enabled solution, it provides a cost-effective and fault-tolerant system that
                        ensures continuous communication even when some sensor nodes fail. The system supports efficient
                        hazard detection and risk assessment, improving the overall safety of highway construction
                        zones. This system is intended for use by MnDOT safety officers, project managers, and highway
                        workers to manage work zone safety more effectively.</p>
                </div>
                <div class="col-lg-6 d-flex align-items-center">
                    <img class="img-fluid mx-2" :src="'image/header.jpg'" alt="" />
                </div>
            </div>
        </div>
    </div>
    <div class="d-lg-none">
        <div class="container">
            <div class="row m-2 py-3">
                <div class="col-lg-6 my-2">
                    <img class="img-fluid mx-2" :src="'image/header.jpg'" alt="" />
                </div>
                <div class="col-lg-6 order-lg-1">
                    <h2 class="h1-large text-uppercase my-3">MnDOT's Work Zone Safety Alert System</h2>
                    <p>This system is developed specifically for MnDOT highway construction sites to enhance worker
                        safety through real-time monitoring and alerts. It detects both near-miss and intrusion events
                        using wearable sensors that notify workers immediately in case of hazardous situations.</p>
                    <p>Designed as an IoT-enabled solution, it provides a cost-effective and fault-tolerant system that
                        ensures continuous communication even when some sensor nodes fail. The system supports efficient
                        hazard detection and risk assessment, improving the overall safety of highway construction
                        zones. This system is intended for use by MnDOT safety officers, project managers, and highway
                        workers to manage work zone safety more effectively.</p>
                </div>
            </div>
        </div>
    </div>
</template>
