<template>
    <div></div>
</template>
<script>
    import { defineComponent } from "vue";
    import { mapActions } from "vuex";

    export default defineComponent({
    name: "Logout",
    data(){
        return {}
    },
    methods: {
        ...mapActions({
            logout: 'auth/logout'
        }),
        async load(){
            await this.logout().then(resp => {
                if(resp.status){
                    this.$router.push('/auth/login')
                }
            })
        }
    },
    mounted(){
        this.load()
    }
    })
</script>