<template>
    <q-drawer ref="leftDrawer" left-side swipe-only>
        <div class="toolbar">
            <q-toolbar-title>
                <i> event </i>  >>  Mason Training
                <button class="pull-right" @click="$refs.leftDrawer.close()"><i >close</i>
                </button>
            </q-toolbar-title>
        </div>
        <div class="list no-border platform-delimiter">
            <q-drawer-link icon="dashboard" :to="{path: '/events/mason-trainings/dashboard', exact: true}">
                Dashboard
            </q-drawer-link>
            <q-drawer-link icon="list" :to="{path: '/events/mason-trainings/trainings', exact: true}">
                Trainings
            </q-drawer-link>
            <q-drawer-link icon="people" :to="{path: '/events/mason-trainings/participants', exact: true}">
                Participants
            </q-drawer-link>
            <q-drawer-link icon="settings" :to="{path: '/events/mason-trainings/admin', exact: true}">
                Manage
            </q-drawer-link>
        </div>
    </q-drawer>
</template>

<script>
  export default {
    data () {
      return {
      }
    }
  }
</script>
