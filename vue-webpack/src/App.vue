<template>
    <div class="container">
        <app-navbar></app-navbar>
        <!--<d3-line-chart></d3-line-chart>-->
        <tabular-input></tabular-input>
        <!--<app-query></app-query>-->
    </div>
</template>
<script type="text/babel">

   var _ = require('lodash');
    import Navbar from './components/Navbar.vue';
    import TabularInput from './components/TabularInput.vue';
    import Query from './components/Query.vue';
    import D3LineChart from './components/D3LineChart.vue';
    export default{
        components: {
            'app-navbar': Navbar,
            'app-query': Query,
            'tabular-input': TabularInput,
            'd3-line-chart': D3LineChart,
        }
    }
</script>
<style>
.align-center{
    text-align: center;
}
</style>
