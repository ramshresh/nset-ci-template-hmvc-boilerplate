<template>
    <q-layout>
        <qa-header></qa-header>
        <qa-navigation></qa-navigation>
    </q-layout>
</template>

<script>
    import Header from './components/Header.vue'
    import Navigation from './components/Navigation.vue'
    import LeftDrawer from './components/LeftDrawer.vue'
    import RightDrawer from './components/RightDrawer.vue'
    import Footer from './components/Footer.vue'
    import RouteContent from './components/RouteContent.vue'
    import SubRouteContent from './components/SubRouteContent.vue'
    export default {
      data () {
        return {
        }
      },
      components: {
        'qa-header': Header,
        'qa-navigation': Navigation,
        'qa-left-drawer': LeftDrawer,
        'qa-right-drawer': RightDrawer,
        'qa-route-content': RouteContent,
        'qa-sub-route-content': SubRouteContent,
        'qa-footer': Footer
      }
    }
</script>
