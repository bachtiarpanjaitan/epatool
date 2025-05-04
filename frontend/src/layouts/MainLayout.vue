<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated class="bg-primary">
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title>
          EPA TOOL
        </q-toolbar-title>
        <div class="q-mx-md">Welcome <b>{{ me.name }}</b></div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
    >
      <q-list>
        <q-item-label
          header
        >
          Navigation
        </q-item-label>
          <EssentialLink v-for="link in essentialLinks" :key="link.title" v-bind="link" :show="link.role.includes(me.role)"/>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { mapGetters } from 'vuex'
import EssentialLink from 'components/EssentialLink.vue'

const linksList = [
  {
    title: 'Dashboard',
    caption: 'Visualization of your data',
    icon: 'dashboard',
    link: '/',
    role: [1,2]
  },
  {
    title: 'My Requests',
    caption: 'Show all your request',
    icon: 'content_paste_go',
    link: '/requests',
    role: [1]
  },
  {
    title: 'Users',
    caption: 'Show all your users',
    icon: 'people',
    link: '/users',
    role: [1]
  },
  {
    title: 'Projects',
    caption: 'Your Projects',
    icon: 'construction',
    link: '/projects',
    role: [1]
  },
  {
    title: 'My Task',
    caption: 'Show all your task',
    icon: 'assignment',
    link: '/tasks',
    role: [2]
  },
  {
    title: 'Report',
    caption: 'All needed report',
    icon: 'bar_chart',
    link: '/reports',
    role: [1]
  },
  {
    title: 'Logout',
    caption: '',
    icon: 'logout',
    link: '/auth/logout',
    role: [1,2]
  }
]

export default defineComponent({
  name: 'MainLayout',

  components: {
    EssentialLink
  },

  setup () {
    const leftDrawerOpen = ref(false)

    return {
      essentialLinks: linksList,
      leftDrawerOpen,
      toggleLeftDrawer () {
        leftDrawerOpen.value = !leftDrawerOpen.value
      }
    }
  },
  computed: {
    ...mapGetters({
      me: 'auth/getMe'
    })
  }
})
</script>
