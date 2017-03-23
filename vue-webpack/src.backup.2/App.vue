<template>
    <div class="wrapper">
        <div class="container">
            <app-navbar></app-navbar>
            <app-query></app-query>
        </div>
    </div>
</template>
<script type="text/babel">
    import Navbar from './components/Navbar.vue';
    import Query from './components/Query.vue';
    export default{
        components: {
            'app-navbar': Navbar,
            'app-query': Query,
        },
        data(){
            return {}
        }
    }
</script>
<style>
    .body {
        background-color: whitesmoke;
    }

    .wrapper {
        padding: 10px;
        margin: 10px;
        background-color: white;
    }
</style>
