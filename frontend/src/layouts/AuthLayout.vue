<template>
    <q-layout view="lHh Lpr lFf">
      <q-header elevated class="bg-primary">
        <q-toolbar>
          <q-toolbar-title>
            EPA TOOL
          </q-toolbar-title>
        </q-toolbar>
      </q-header>
      <q-page-container>
        <router-view />
      </q-page-container>
    </q-layout>
  </template>
  
  <script>
  import { defineComponent, ref } from 'vue'
  
  export default defineComponent({
    name: 'AuthLayout',
    components: {},
    setup () {
        const leftDrawerOpen = ref(false)
        return {
            leftDrawerOpen,
            toggleLeftDrawer () {
            leftDrawerOpen.value = !leftDrawerOpen.value
            }
        }
    }
  })
  </script>
  