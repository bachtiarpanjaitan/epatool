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
                    <div class="col-12">
                        <div >
                            <q-select
                                dense
                                outlined
                                :options="users"
                                label="Assign user"
                                v-model="data.assign_users"
                                multiple
                                use-chips
                                option-label="name"
                                option-value="id"
                                :rules="inputRules"
                                />
                        </div>
                        <div class="row col-12 q-pt-sm">
                             <div class="col-md-6">
                                <q-input label="From Date" filled dense v-model="data.from_date" mask="date" :rules="['date']">
                                    <template v-slot:append>
                                        <q-icon name="event" class="cursor-pointer">
                                        <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                            <q-date v-model="data.from_date" mask="YYYY-MM-DD">
                                            <div class="row items-center justify-end">
                                                <q-btn v-close-popup label="Close" color="primary" flat />
                                            </div>
                                            </q-date>
                                        </q-popup-proxy>
                                        </q-icon>
                                    </template>
                                </q-input>
                            </div>
                            <div class="col-md-6 q-pl-sm">
                                <q-input label="To Date" filled dense v-model="data.to_date" mask="date" :rules="['date']">
                                    <template v-slot:append>
                                        <q-icon name="event" class="cursor-pointer">
                                        <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                            <q-date v-model="data.to_date" mask="YYYY-MM-DD">
                                            <div class="row items-center justify-end">
                                                <q-btn v-close-popup label="Close" color="primary" flat />
                                            </div>
                                            </q-date>
                                        </q-popup-proxy>
                                        </q-icon>
                                    </template>
                                </q-input>
                            </div>
                        </div>
                        <q-input class="q-pb-sm" outlined dense v-model="data.title" :rules="inputRules" label="Title" />
                        <q-editor class="my-editor" dense height="46vh" v-model="data.description" :rules="inputRules" toolbar-text-color="white"
                        toolbar-toggle-color="yellow-8"
                        toolbar-bg="primary"
                        :definitions="{
                            bold: {label: 'Bold', icon: null, tip: 'My bold tooltip'}
                        }"></q-editor>
                    </div>
                </q-form>
            </q-card-section>

            <q-card-actions>
                <q-space></q-space>
                <div class="py-md">
                    <q-btn v-close-popup color="primary" @click="this.$emit('done')" icon="cancel" class="q-mr-sm" outline>Close</q-btn>
                </div>
                <div class="py-md">
                    <q-btn color="primary" type="submit" :loading="loading" icon="save" class="q-mr-sm" @click="save()" outline>Create</q-btn>
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
            users: {
                type: Array,
                default: () => []
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
                // data: {
                //     assign_users: [],
                //     title: '',
                //     description: '',
                //     from_date: '',
                //     to_date: '',
                //     workspace_id: 0,
                // },
                data: {"assign_users":[{"id":2,"name":"Worker"}],"title":"Test","description":"Test","from_date":"2025/05/01","to_date":"2025/05/13","workspace_id":"6"},
                inputRules: [
                    (v) => !!v || `Cannot be empty`,
                ],
                loading: false
            }
        },
        methods: {
            ...mapActions({
                saveRequest: 'request/createRequest',
            }),
            async save(){
                this.$refs.form.validate().then(async v => {
                    if(v){
                        this.loading = true
                        try {
                            this.data.workspace_id = localStorage.getItem('workspace')
                            await this.saveRequest(this.data).then(({data}) => {
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
                        } catch (error) {
                            console.log(error)
                            this.loading = false
                            if (error.response?.data?.error != undefined) {
                                this.$q.notify({
                                    type: 'negative',
                                    icon: 'cancel',
                                    message: error.response.data.error.message,
                                    timeout: 1000
                                }) 
                            }else  this.$q.notify({
                                    type: 'negative',
                                    icon: 'cancel',
                                    message: 'Error saving request',
                                    timeout: 1000
                                }) 
                        }
                    }
                })
            }
        },
    })
</script>