<script>
import $ from 'jquery';
export default {
    mounted() {
        $('[data-widget="treeview"]').Treeview('init');
    }
}
</script>
<script setup>
import { ref } from 'vue'
defineProps({
    work_zone: Object
})
const hideSidebar = () => {
    if (ref(window.innerWidth).value <= 768) {
        document.body.classList.add('sidebar-closed')
        document.body.classList.add('sidebar-collapse')
        document.body.classList.remove('sidebar-open')
    }
}
</script>

<template>
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                </li>
                <li class="nav-item">
                    <Link :href="route('welcome')" class="nav-link">Home</Link>
                </li>
                <li class="nav-item d-none d-md-block">
                    <Link :href="route('about')" class="nav-link">About</Link>
                </li>
                <li class="nav-item">
                    <Link :href="route('user.work_zones.index')" class="nav-link">Work zones</Link>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <div class="btn-group dropstart">
                    <a class="me-2" data-bs-toggle="dropdown" href="#" data-bs-display="static" aria-expanded="false">
                        <img v-if="$page.props.auth.user.image"
                            :src="'/storage/user/image/' + $page.props.auth.user.image" class="rounded-circle"
                            height="30" alt="img" loading="lazy" />
                        <img v-else :src="'/storage/user/image/default.png'" class="rounded-circle" height="35"
                            alt="img" loading="lazy" />
                    </a>
                    <ul class="dropdown-menu dropdown-menu-start dropdown-menu-lg-start">
                        <li>
                            <Link class="dropdown-item d-flex" :href="route('user.profile.show')">
                            <img v-if="$page.props.auth.user.image"
                                :src="'/storage/user/image/' + $page.props.auth.user.image" class="rounded-circle"
                                height="45" alt="img" loading="lazy" />
                            <img v-else :src="'/storage/user/image/default.png'" class="rounded-circle" height="45"
                                alt="img" loading="lazy" />
                            <div class="ms-2">
                                <div>{{ $page.props.auth.user.name }}</div>
                                <small>{{ $page.props.auth.user.email }}</small>
                            </div>
                            </Link>
                            <hr>
                        </li>
                        <li>
                            <Link class="dropdown-item" :href="route('logout')" method="post">
                            Logout
                            </Link>
                        </li>
                    </ul>
                </div>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-gray elevation-3">

            <!-- Sidebar -->
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div v-if="work_zone.thumbnail" class="image">
                        <img :src="'/storage/work_zone/thumbnail/' + work_zone.thumbnail" class="img-circle elevation-2"
                            alt="">
                    </div>
                    <div v-else class="image">
                        <img :src="'/storage/work_zone/thumbnail/default.jpg'" class="img-circle elevation-2" alt="">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ work_zone.name }}</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item" @click="hideSidebar">
                            <Link :href="route('user.work_zone.dashboard', work_zone.id)" class="nav-link"
                                :class="{ 'active': $page.url.includes(`/${work_zone.id}/dashboard`) }">
                            <i class="nav-icon fa-solid fa-list"></i>
                            <p>Dashboard</p>
                            </Link>
                        </li>
                        <!-- <li class="nav-item" @click="hideSidebar">
                            <Link :href="route('user.work_zone.map', work_zone.id)" class="nav-link"
                                :class="{ 'active': $page.url.includes(`/${work_zone.id}/map`) }">
                            <i class="nav-icon fa-solid fa-map-location-dot"></i>
                            <p>Real time map</p>
                            </Link>
                        </li> -->
                        <li class="nav-item" @click="hideSidebar">
                            <Link :href="route('user.work_zone.incidents.index', work_zone.id)" class="nav-link"
                                :class="{ 'active': $page.url.includes(`/${work_zone.id}/incidents`) }">
                            <i class="nav-icon fa-solid fa-triangle-exclamation"></i>
                            <p>Incidents</p>
                            </Link>
                        </li>
                        <li class="nav-item" @click="hideSidebar">
                            <Link :href="route('user.work_zone.devices.index', work_zone.id)" class="nav-link"
                                :class="{ 'active': $page.url.includes(`/${work_zone.id}/devices`) }">
                            <i class="nav-icon fa-solid fa-gear"></i>
                            <p>Devices</p>
                            </Link>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->

        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <slot />
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">

            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; {{ new Date().getFullYear() }} <a href="#" target="_blank">---</a>.</strong> All
            rights
            reserved.
        </footer>
    </div>
</template>

<style scoped>
hr {
    border-color: #666666;
}
</style>
