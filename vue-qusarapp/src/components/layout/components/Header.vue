<template>
    <div slot="header" class="toolbar">
        <!-- opens left-side drawer using its ref -->
        <button class="" @click="$refs.leftDrawer.open()">
            <i>menu</i>
        </button>
        <q-toolbar-title :padding="1">
            Baliyo Ghar - NSET
        </q-toolbar-title>
        <!-- opens right-side drawer using its ref -->
        <button class="" @click="$refs.rightDrawer.open()">
            <i>settings</i>
        </button>
    </div>
</template>

<script>
  export default {
    data () {
      return {
      }
    }
  }
</script>
