<script setup>
</script>

<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
export default {
    layout: GuestLayout
}
</script>

<template>

    <Head title="About" />

    <div class="container">
        <div class="my-md-5 py-3">
            <h2 class="mb-3 my-md-5 text-uppercase">About</h2>
            <p class="my-3">This system was developed specifically for MnDOT highway construction sites to enhance
                worker safety
                through real-time monitoring of hazards and events, such as near-miss situations and vehicle
                intrusions. By integrating advanced sensor fusion techniques, the system combines data from multiple
                sensors, including GPS and RSSI, to provide accurate and timely alerts for workers. These alerts are
                delivered through wearable devices, ensuring that workers are notified of potential dangers in time
                to take evasive actions.</p>

            <div class="row my-3">
                <div class="col-md-6 my-1">
                    <img class="img-fluid" :src="'image/about-1.jpg'" alt="" />
                </div>
                <div class="col-md-6 my-1">
                    <img class="img-fluid" :src="'image/about-2.jpg'" alt="" />
                </div>
            </div>

            <p class="my-3">
                The system was rigorously tested at the MnROAD test facility, a renowned research site operated by
                MnDOT. MnROAD serves as an ideal environment to test highway construction safety systems due to its
                controlled yet realistic setup, which includes sections for both highway and low-volume road testing. At
                MnROAD, various real-world scenarios were simulated, such as vehicle intrusions into work zones, wind
                displacement of traffic cones, and worker movements. These tests allowed for precise calibration of the
                system to detect true hazards while minimizing false alarms.</p>

            <p class="my-3">The system leverages low-cost IoT technology to create a mesh network of wearable devices
                and stationary
                sensors. It integrates sensor fusion algorithms, such as the Extended Kalman Filter (EKF) and Particle
                Filter (PF), to process data from GPS and RSSI measurements. This fusion improves the localization of
                workers and equipment, providing accurate position and movement information in real-time. The dynamic
                hazard zone model, developed as part of this system, adjusts in real-time based on the speed and
                movement of construction equipment, continuously monitoring the area around moving machinery to prevent
                accidents.</p>

            <p class="my-3">One of the key features of the system is its ability to adapt to the dynamic nature of
                highway work
                zones. Traditional hazard detection models often rely on static boundaries, which can be inadequate in
                complex environments where workers and machinery are constantly moving. This system, however, uses a
                dynamic hazard zone model that changes in size and shape based on real-time sensor data. For instance,
                when equipment accelerates, the system expands the hazard zone to account for the increased risk, and
                when the equipment slows down, the zone contracts, reducing unnecessary alerts.</p>

            <p class="my-3">Through its use of advanced algorithms and real-time data processing, the system enhances
                situational
                awareness for both workers and project managers. It not only improves safety by detecting hazards but
                also provides valuable insights into worker movements and equipment operations, which can be used to
                optimize site management. The system is designed to be low-cost, scalable, and easy to adopt, making it
                a practical solution for improving safety in highway work zones.</p>

            <p class="my-3">In summary, this system represents a significant advancement in construction site safety,
                offering MnDOT
                a robust solution for protecting workers in dynamic and potentially hazardous environments. The
                combination of sensor fusion, real-time monitoring, and dynamic hazard zone modeling ensures that the
                system provides accurate and timely alerts, ultimately reducing the risk of accidents and enhancing
                overall site safety.</p>

            <p class="my-3">Whether you're a geotechnical engineer working on a construction site, a construction
                manager
                overseeing a project, or a researcher studying the behavior of soil and rock, our geotechnical test data
                management system can help you streamline your workflow and achieve your goals.
            </p>
        </div>
    </div>
</template>
