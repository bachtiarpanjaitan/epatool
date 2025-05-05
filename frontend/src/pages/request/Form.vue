<template>
    <div>
        <q-card color="primary" style="width: 550px">
            <q-bar class="bg-primary">
                <div class="text-white">Add Request</div>
                <q-space />
                <q-btn dense flat icon="close" v-close-popup>
                    <q-tooltip class="text-primary">Close</q-tooltip>
                </q-btn>
            </q-bar>
            <q-card-section>
                <q-form ref="form" lazy-validation>
                    <div class="py-md">
                        <div class="q-pb-sm col-md-12">
                            <label for="">Title</label>
                            <q-input
                            v-model="form.title"
                            dense
                            outlined
                            type="text"
                            :rules="inputRules"
                            />
                        </div>
                    </div>
                </q-form>
            </q-card-section>

            <q-card-actions>
                <q-space></q-space>
                <div class="py-md">
                    <q-btn v-close-popup color="primary" @click="this.$emit('done')" icon="cancel" class="q-mr-sm" outline>Close</q-btn>
                </div>
                <div class="py-md">
                    <q-btn color="primary" type="submit" :loading="loading" icon="save" class="q-mr-sm" @click="save()" outline>Save</q-btn>
                </div>
            </q-card-actions>
           
        </q-card>

    </div>
</template>
<script>
    import { defineComponent } from "vue";
    import { mapActions } from "vuex";
    import { mapSettings } from "./../../constants/mapSettings";
    import _ from 'lodash'

    export default defineComponent({
        name: "ProjectForm",
        components:{
           
        },
        computed: {
           
        },
        watch: {
          
        },
        props: {
            data: {
                type: Object,
                default: {},
            },
            dialog: {
                type: Boolean,
                default: false
            },
            maximizedToggle: {
                type: Boolean,
                default: false
            }
        },
        data(){
            return {
                form: {
                    title: ''
                },
                inputRules: [
                    (v) => !!v || `Cannot be empty`,
                ],
                loading: false
            }
        },
        methods: {
            ...mapActions({
                
            }),
            async load(){
                this.form = this.data
                
            },
            async save(){
                this.$refs.form.validate().then(async v => {
                    if(v){
                        this.loading = true
                       

                        let payload = {
                            title: this.form.title
                        }
                        if(this.form.id != undefined) payload.id = this.form.id

                        try {
                            await this.saveRequest(payload).then(({data}) => {
                                if(data.status){
                                    this.$q.notify({
                                        type: 'positive',
                                        icon: 'cancel',
                                        message: 'Request Saved',
                                        timeout: 1000
                                    }) 
                                }
                                this.$emit('done')
                            }).finally(() => {
                                this.loading = false
                            })
                        }catch (error) {
                            this.loading = false
                            if (error.response.data.error != undefined) {
                                this.$q.notify({
                                    type: 'negative',
                                    icon: 'cancel',
                                    message: error.response.data.error.message,
                                    timeout: 1000
                                }) 
                            }else  this.$q.notify({
                                    type: 'negative',
                                    icon: 'cancel',
                                    message: error.response.data.message,
                                    timeout: 1000
                                }) 
                        }
                    }
                })
            }
        },
        mounted(){
            this.load()
        },
    })
</script>