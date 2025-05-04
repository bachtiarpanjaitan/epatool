<template>
  <q-page class="flex flex-center">
    <q-page class="window-height window-width row justify-center items-center">
      <div class="row">
        <q-card flat bordered>
            <q-card-section>
              <q-form class="q-px-sm">
                <q-input
                  ref="username"
                  v-model="username"
                  type="username"
                  lazy-rules
                  :rules="[
                    val => (val && val.length > 0) || 'Please insert username'
                  ]"
                  label="Username"
                >
                  <template v-slot:prepend>
                    <q-icon name="email" />
                  </template>
                </q-input>
                <q-input
                  ref="password"
                  v-model="password"
                  :type="passwordFieldType"
                  lazy-rules
                  @keyup.enter="submit"
                  :rules="[
                    val => (val && val.length > 0) || 'Please insert password'
                  ]"
                  label="Password"
                >
                  <template v-slot:prepend>
                    <q-icon name="lock" />
                  </template>
                  <template v-slot:append>
                    <q-icon
                      :name="visibilityIcon"
                      @click="switchVisibility"
                      class="cursor-pointer"
                    />
                  </template>
                </q-input>
              </q-form>
            </q-card-section>
            <q-card-actions class="q-px-lg">
              <q-btn
                unelevated
                size="lg"
                color="secondary"
                @click="submit"
                class="full-width"
                label="Login"
                :loading="loading"
              />
            </q-card-actions>
          </q-card>
      </div>
    </q-page>
  </q-page>
</template>

<script>
import { defineComponent } from "vue";
import { mapActions } from "vuex";

export default defineComponent({
  name: "IndexPage",
  data(){
    return {
      loading: false,
      title: 'EPA TOOL',
      email: '',
      username: '',
      password: '',
      passwordFieldType: 'password',
      visibilityIcon: 'visibility'
    }
  },
  methods: {
    ...mapActions({
      actionLogin: 'auth/login'
    }),
    switchVisibility() {
      this.visibility = !this.visibility
      this.passwordFieldType = this.visibility ? 'text' : 'password'
      this.visibilityIcon =  this.visibility ? 'visibility_off' : 'visibility'
    },
    load(){
      
    },
    async submit(){
      try {
          this.loading = true
          const payload = {
            username: this.username,
            password: this.password
          }
        await this.actionLogin(payload).then((data) => {
          if (data.status == false) {
            this.$q.notify({
              type: 'negative',
              icon: 'cancel',
              message: data.error?.response?.data?.message,
              timeout: 1000
            })
          } else {
            if(data.data != null){
              this.$router.push('/')
            } else {
              this.$q.notify({
                  type: 'negative',
                  icon: 'cancel',
                  message: data.message,
                  timeout: 1000
              })
            }
          }
          this.loading = false
        })
      } catch (err) {
          console.log('ERROR: ',err)
          localStorage.removeItem('token')
          this.$q.notify({
            type: 'negative',
            icon: 'cancel',
            message: err.response.data.message[0].messages[0].message,
            timeout: 1000
          })
          this.loading = false
          this.$store.commit('auth/isLoggedIn', false)
        }
    }
  },
  mounted(){
    this.load()
  }
});
</script>
