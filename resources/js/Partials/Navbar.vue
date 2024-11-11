<script setup>
</script>
<template>
    <nav class="navbar navbar-expand fixed-top navbar-light border-bottom" aria-label="Main navigation">
        <div class="container">
            <Link class="navbar-brand logo-text" :href="route('welcome')">
            <img class="d-none d-md-block" src="/image/logo.jpg" height="50" alt="">
            <img class="d-md-none" src="/image/logo.jpg" height="40" alt="">
            </Link>

            <ul class="navbar-nav ms-auto navbar-nav-scroll">
                <li class="nav-item me-md-3">
                    <Link class="nav-link" :href="route('welcome')" :class="{ 'active': $page.url == '/' }">Home
                    </Link>
                </li>
                <li class="nav-item me-md-4">
                    <Link class="nav-link" :href="route('about')" :class="{ 'active': $page.url.startsWith('/about') }">
                    About</Link>
                </li>
                <template v-if="$page.props.auth.user">
                    <li class="nav-item dropdown">
                        <div class="btn-group">
                            <a data-bs-toggle="dropdown" href="#" data-bs-display="static" aria-expanded="false">
                                <img v-if="$page.props.auth.user.image"
                                    :src="'/storage/user/image/' + $page.props.auth.user.image" class="rounded-circle"
                                    height="30" alt="img" loading="lazy" />
                                <img v-else :src="'/storage/user/image/default.png'" class="rounded-circle" height="35"
                                    alt="img" loading="lazy" />
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" :href="route('user.work_zones.index')">Work zones</a>
                                </li>
                                <li>
                                    <div class=" dropdown-divider">
                                    </div>
                                </li>
                                <li>
                                    <Link class="dropdown-item" :href="route('logout')" method="post">
                                    Logout
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </li>
                </template>
                <template v-else>
                    <span class="nav-item">
                        <Link class="btn-solid-sm" :href="route('login')">Sign in</Link>
                    </span>
                </template>
            </ul>
        </div>
    </nav>
</template>

<style scoped>
.btn-solid-sm {
    display: inline-block;
    padding: 1rem 1.5rem 1rem 1.5rem;
    border: 1px solid #0092ff;
    border-radius: 30px;
    background-color: #0092ff;
    color: #ffffff;
    font-weight: 600;
    font-size: 0.875rem;
    line-height: 0;
    text-decoration: none;
    transition: all 0.2s;
}

.active {
    text-decoration: underline;
}
</style>