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
          <q-select
            filled
            v-model="wsselected"
            :options="wsoptions"
            label="Select Workspace"
            dense
            emit-value
            map-options
            @update:model-value="changeWorkspace"
          >
            <template v-slot:append>
              <q-btn round dense flat icon="add" @click="addws"/>
            </template>
          </q-select>
        </q-item-label>
        <EssentialLink v-for="link in essentialLinks" :key="link.title" v-bind="link" :show="link.role.includes(me.role) && wsselected != null"/>
        <EssentialLink title="Logout" link="/auth/logout" icon="logout" :role="[1,2]" :show="true"/>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { mapActions, mapGetters } from 'vuex'
import EssentialLink from 'components/EssentialLink.vue'

const linksList = [
  {
    title: 'Dashboard',
    caption: 'Visualization of your data',
    icon: 'dashboard',
    link: '/',
    role: [1,2],
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
  }
]

export default defineComponent({
  name: 'MainLayout',

  components: {
    EssentialLink
  },

  data () {
    const leftDrawerOpen = ref(false)
    return {
      essentialLinks: linksList,
      leftDrawerOpen,
      wsoptions: [],
      wsselected:null,
      toggleLeftDrawer () {
        leftDrawerOpen.value = !leftDrawerOpen.value
      }
    }
  },
  methods: {
    ...mapActions({
      setWorkspace: 'common/addworkspace',
      getWorkspace: 'common/getworkspace'
    }),
    addws () {
      this.$q.dialog({
        title: 'Add Workspace',
        message: 'Fill the form to add a new workspace',
        prompt: {
          model: '',
          type: 'text',
          dense: true,
          outlined: true,
          label: 'Workspace Name',
        },
        cancel: true,
        persistent: true,
        ok: {
          label: 'Save'
        }
      }).onOk(async data => {
        if(data == null || data == '') {
          this.$q.notify({
            type: 'negative',
            message: 'Workspace name cannot be empty'
          })
          throw new Error('Invalid input')
        } else {
          await this.setWorkspace({ name: data }).then((res) => { 
            if (res.code == 200 ) {
              this.getWorkspaces()
              this.$q.notify({
                type: 'positive',
                message: 'Workspace added successfully'
              })
            }
          })
        }
       
      }).onCancel(() => {
        // console.log('>>>> Cancel')
      })
    },
    async getWorkspaces() {
      this.wsoptions = []
      await this.getWorkspace().then(({data}) => {
        data.data.forEach((ws) => {
          this.wsoptions.push({
            label: ws.name,
            value: ws.id
          })
        })
        this.wsselected = this.wsoptions.find(x => x.value == localStorage.getItem('workspace')) || data.data[0].id
      })
    },
    changeWorkspace(value) {
      localStorage.setItem('workspace', value)
    }
  },
  computed: {
    ...mapGetters({
      me: 'auth/getMe'
    })
  },
  mounted () {
    this.getWorkspaces()
  }
})
</script>
