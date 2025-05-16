<template>
    <div>
        <q-card>
             <q-card-section horizontal>
                <div class="row q-pl-md q-pt-sm q-pb-sm">
                    <div class="col-md-5">
                         <div class="col-md-12 q-pb-sm row">
                            <div class="col-md-6">
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
                                    />
                            </div>
                            <div class="col-md-6 q-pl-sm">
                                <q-select
                                    dense
                                    outlined
                                    :options="statuses"
                                    label="Set Status"
                                    v-model="data.status"
                                    @update:model-value="updateRequest"
                                    />
                            </div>
                        </div>
                        <div class="row col-12">
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
                        <q-input class="q-pb-sm" outlined dense v-model="data.title" label="Title" />
                        <q-editor :readonly="me.role != 1" class="my-editor" dense height="46vh" v-model="data.description" toolbar-text-color="white"
                        toolbar-toggle-color="yellow-8"
                        toolbar-bg="primary"
                        :definitions="{
                            bold: {label: 'Bold', icon: null, tip: 'My bold tooltip'}
                        }"></q-editor>
                        <q-btn outline dense color="primary" class="q-mt-sm" @click="updateRequest">Update Request</q-btn>  
                    </div>
                    <div class="col-md-4 q-pl-sm">
                        <div class="q-item__label q-item__label--header"> Feedbacks</div>
                        <q-scroll-area style="height: 357px;">
                            <div class="row items-start">
                                <q-card style="width: 100%;" bordered v-for="fb in data.feedbacks" :key="fb.id">
                                    <q-card-section class="row q-gutter-sm items-start q-pr-sm">
                                        <q-avatar color="teal" text-color="white" size="20px">
                                        {{ fb.user.name.charAt(0).toUpperCase() }}
                                        </q-avatar>

                                        <div class="col">
                                            <div class="text-subtitle2">{{ fb.user.name }}</div>
                                        </div>

                                        <div class="text-overline text-right" style="min-width: 90px;">
                                        {{ formatTanggalIndo(log.created_at) }}
                                        </div>
                                    </q-card-section>
                                    <q-card-section><div v-html="log.feedback" style="font-style: italic; text-wrap: wrap;" class="text-primary"></div></q-card-section>
                                </q-card>
                                <q-card bordered flat style="width: 100%;" v-if="data.feedbacks == null || data.feedbacks.len <= 0" >
                                    <q-card-section>
                                        <div class="text-center text-red text-italic text-weight-thin">Feedback Nothing to show</div>
                                    </q-card-section>
                                </q-card>

                            </div>
                        </q-scroll-area>
                        <q-editor class="my-editor q-mt-sm"  :dense="$q.screen.lt.md" height="10vh" v-model="feedback" toolbar-text-color="white"
                        toolbar-toggle-color="yellow-8"
                        toolbar-bg="primary"
                        :toolbar="[
                            [
                            {
                                label: $q.lang.editor.align,
                                icon: $q.iconSet.editor.align,
                                fixedLabel: true,
                                list: 'only-icons',
                                options: ['left', 'center', 'right', 'justify']
                            },
                            {
                                label: $q.lang.editor.align,
                                icon: $q.iconSet.editor.align,
                                fixedLabel: true,
                                options: ['left', 'center', 'right', 'justify']
                            }
                            ],
                            ['bold', 'italic', 'strike', 'underline'],
                            ['token', 'hr', 'link', 'custom_btn'],
                            ['fullscreen'],
                            [
                            {
                                label: $q.lang.editor.formatting,
                                icon: $q.iconSet.editor.formatting,
                                list: 'no-icons',
                                options: [
                                'p',
                                'h1',
                                'h2',
                                'h3',
                                'h4',
                                'h5',
                                'h6',
                                'code'
                                ]
                            },
                            {
                                label: $q.lang.editor.fontSize,
                                icon: $q.iconSet.editor.fontSize,
                                fixedLabel: true,
                                fixedIcon: true,
                                list: 'no-icons',
                                options: [
                                'size-1',
                                'size-2',
                                'size-3',
                                'size-4',
                                'size-5',
                                'size-6',
                                'size-7'
                                ]
                            },
                            {
                                label: $q.lang.editor.defaultFont,
                                icon: $q.iconSet.editor.font,
                                fixedIcon: true,
                                list: 'no-icons',
                                options: [
                                'default_font',
                                'arial',
                                'arial_black',
                                'comic_sans',
                                'courier_new',
                                'impact',
                                'lucida_grande',
                                'times_new_roman',
                                'verdana'
                                ]
                            },
                            'removeFormat'
                            ],
                            ['quote', 'unordered', 'ordered', 'outdent', 'indent'],
                            ['undo', 'redo'],
                            ['viewsource']
                        ]"
                        :fonts="{
                            arial: 'Arial',
                            arial_black: 'Arial Black',
                            comic_sans: 'Comic Sans MS',
                            courier_new: 'Courier New',
                            impact: 'Impact',
                            lucida_grande: 'Lucida Grande',
                            times_new_roman: 'Times New Roman',
                            verdana: 'Verdana'
                        }" >
                        </q-editor>
                        <q-btn outline dense color="primary" class="q-mt-sm">Create Feedback</q-btn>  
                    </div>
                    <div class="col-md-3 q-px-sm">
                        <div class="q-item__label q-item__label--header"> Request Logs</div>
                        <q-scroll-area style="height: 520px;">
                            <div class="row items-start">
                               
                                <q-card bordered flat style="width: 100%;" v-if="data.logs.length <= 0" >
                                    <q-card-section>
                                        <div class="text-center text-red text-italic text-weight-thin">This request have nothing history log(s)</div>
                                    </q-card-section>
                                </q-card>
                                <template v-else>
                                     <q-card flat style="width: 100%;" bordered v-for="log in data.logs" :key="log.id">
                                        <q-card-section class="row q-gutter-sm items-start q-pr-sm" :horizontal="true">
                                            <q-avatar color="teal" text-color="white" size="20px">
                                            {{ log.user.name.charAt(0).toUpperCase() }}
                                            </q-avatar>

                                            <div class="col"><div class="text-body2 text-grey">{{ log.user.name }}</div></div>

                                            <div class="text-sm text-weight-light text-right" style="min-width: 90px;">
                                            {{ formatTanggalIndo(log.created_at) }}
                                            </div>
                                        </q-card-section>
                                        <q-separator inset />
                                        <q-card-section><div v-html="log.description" style="text-wrap: wrap;" class="text-primary"></div></q-card-section>
                                    </q-card>
                                </template>
                            </div>
                        </q-scroll-area>
                        
                    </div>
                </div>
            </q-card-section>
            <q-separator />
        </q-card>
    </div>
</template>
<style>
.my-editor .q-editor__content {
  white-space: pre-wrap !important;
  word-break: break-word !important;
}
</style>
<script>
import _ from 'lodash'
import { mapActions, mapGetters } from "vuex";
export default {
    name: "DetailRequest",
    props: {
        data: {
            type: Object,
            default: () => ({}),
        },
        users: {
            type: Array,
            default: () => ([])
        }
    },
    data() {
        return {
            feedback: '',
            statuses: ['new', 'onprogress','review','done', 'reject'],
        }
    },
    computed: {
        ...mapGetters({
            me: 'auth/getMe'
        })
    },
    methods: {
        ...mapActions({
            update: 'request/updateRequest',
        }),
        async updateRequest() {
            console.log('update request', this.data)
            await this.update(this.data).then((res) => {
                if (res.status) {
                    this.$q.notify({
                        type: 'positive',
                        message: 'Request updated successfully'
                    })
                    this.$emit('load')
                } else {
                    this.$q.notify({
                        type: 'negative',
                        message: 'Failed to update request'
                    })
                }
                
            }).catch((err) => {
                console.log('update request error', err)
                this.$q.notify({
                    type: 'negative',
                    message: 'Failed to update request'
                })
            })
        },
        formatTanggalIndo(tgl) {
            const date = new Date(tgl)

            const tanggal = date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            })

            const jam = date.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            })

            return `${tanggal} ${jam}`
        }
    },
}
</script>