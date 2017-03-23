<template>
    <q-layout>
        <!-- Header -->
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
        <!-- Navigation Tabs -->
        <q-tabs slot="navigation" class="justify-center">
            <q-tab route="/" exact replace><i>home</i>Home</q-tab>
            <q-tab route="/admin/event" exact replace>Events</q-tab>
            <q-tab route="/admin/people" exact replace>People</q-tab>
            <q-tab route="/help" exact replace>Help</q-tab>
        </q-tabs>

        <!-- Left-side Drawer -->
        <q-drawer ref="leftDrawer" left-side >
            <div class="toolbar">
                <q-toolbar-title>
                   <i> event </i>  >>  Mason Training
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
        <!-- IF USING subRoutes only: -->
        <router-view class="layout-view">
            Sub Routes Content
        </router-view>
        <!-- OR ELSE, IF NOT USING subRoutes: -->
        <div class="layout-view">
           .<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>
           .<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>
           .<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>
           .<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>
           .<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>
           .<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>
           .<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>
           .<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>
           .<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>
           .<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>
           .<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>
           .<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>
           .<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>.<br/>
        </div>
        <!-- Right-side Drawer -->
        <q-drawer ref="rightDrawer" right-side swipe-only >
            Settings
        </q-drawer>
        <!-- Footer -->
        <!--<div slot="footer" class="toolbar">
            ....
        </div>-->
    </q-layout>
</template>

<script>
  export default {
    data () {
      return {
      }
    }
  }
</script>
