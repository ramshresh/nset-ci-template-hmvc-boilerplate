<template>
    <div class="container">
        <app-navbar></app-navbar>
    </div>
</template>

<script type="text/babel">
    var _ = require('lodash');
    import Navbar from './components/Navbar.vue';
    export default{
        components: {
            'app-navbar': Navbar,
        }
    }
</script>
<style>
    .align-center{
        text-align: center;
    }
</style>