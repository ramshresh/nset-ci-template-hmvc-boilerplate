<template>
    <q-tabs slot="navigation" class="justify-center">
        <q-tab route="/" exact replace><i>home</i>Home</q-tab>
        <q-tab  exact replace>|</q-tab>
        <q-tab route="/layout" exact replace>Events</q-tab>
        <q-tab route="/layout/alarm" exact replace>People</q-tab>
        <q-tab  exact replace>|</q-tab>
        <q-tab route="/help" exact replace>Help<i>help</i></q-tab>
    </q-tabs>
</template>

<script>
  export default {
    data () {
      return {
      }
    }
  }
</script>
