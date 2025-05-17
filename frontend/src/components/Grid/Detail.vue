<template>
    <div>
        <q-card>
             <q-splitter
                :v-model="20"
                style="height: 600px"
                >

                <template v-slot:before>
                    <q-tabs
                    v-model="tab"
                    vertical
                    class="text-primary"
                    >
                    <q-tab name="request" icon="edit_note" label="Request" />
                    <q-tab name="feedback" icon="rate_review" label="Feedback" />
                    <q-tab name="history" icon="history" label="History" />
                    </q-tabs>
                </template>
                <template v-slot:after>
                    <q-tab-panels
                        v-model="tab"
                        animated
                        swipeable
                        vertical
                        transition-prev="jump-up"
                        transition-next="jump-up"
                    >
                    <q-tab-panel name="request">
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
                        <div class="row col-12 col-sm-12">
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
                        <q-editor :readonly="me.role != 1" class="my-editor" dense height="34vh" v-model="data.description" toolbar-text-color="white"
                        toolbar-toggle-color="yellow-8"
                        toolbar-bg="primary"
                        :toolbar="[
                            ['bold', 'italic', 'underline', 'strike'],
                            ['quote', 'unordered', 'ordered', 'outdent', 'indent'],
                            ['link', 'hr', 'print'],
                            ['undo', 'redo'],
                            ['viewsource', 'fullscreen'],
                            ['subscript', 'superscript'],
                            ['font', 'size', 'color', 'bgcolor'],
                            ['align', 'removeFormat']
                        ]"
                        :definitions="{
                            bold: {label: 'Bold', icon: null, tip: 'My bold tooltip'}
                        }"></q-editor>
                        <div>
                            <q-btn outline dense color="primary" class="q-mt-sm" @click="updateRequest">Update Request</q-btn>
                        </div>
                    </q-tab-panel>
                    <q-tab-panel name="feedback">
                            <q-scroll-area style="height: 280px">
                                <div class="row col-12">
                                    <q-card class="col-sm-12 q-mb-sm" bordered v-for="fb in data.feedbacks" :key="fb.id">
                                          <q-card-section class="q-gutter-sm items-start q-pr-sm" :horizontal="true">
                                            <q-avatar color="red" text-color="white" size="20px">
                                            {{ fb.user.name.charAt(0).toUpperCase() }}
                                            </q-avatar>

                                            <div class="col">
                                                <div class="text-body2 text-grey-9">{{ fb.user.name }} </div>
                                            </div>

                                            <div class="text-sm text-weight-bold text-right text-primary" style="min-width: 90px;">
                                                <q-icon :name="fb.is_read ? 'visibilty': 'visibility_off'"/>
                                                {{ formatTanggalIndo(fb.created_at) }}
                                            </div>
                                        </q-card-section>
                                        <q-separator inset />
                                        <div class="row q-ml-md q-mt-md">
                                            <q-badge outline class="text-caption text-primary">{{ fb.feedback_type }}</q-badge>
                                            <q-badge outline class="text-caption text-purple q-ml-sm" v-if="fb.status == 'open'">{{ fb.status }}</q-badge>
                                            <q-badge outline class="text-caption text-red q-ml-sm" v-if="fb.status == 'closed'">{{ fb.status }}</q-badge>
                                            <q-badge outline class="text-caption text-red q-ml-sm" v-if="fb.status == 'closed'">{{ fb.status }}</q-badge>
                                        </div>
                                        
                                        <q-card-section><div v-html="fb.feedback" style="text-wrap: wrap;" class="text-grey-9"></div></q-card-section>
                                    </q-card>
                                    <q-card bordered class="col-md-12" flat v-if="data.feedbacks == null || data.feedbacks.length <= 0" >
                                        <q-card-section>
                                            <div class="text-center text-red text-italic text-weight-bold">Feedback Nothing to show, add your feedback</div>
                                        </q-card-section>
                                        <q-card-section>
                                            <div class="q-gutter-y-md">
                                                <q-skeleton bordered type="circle" />
                                                <q-skeleton bordered />
                                                <q-skeleton bordered square />
                                                <q-skeleton square />
                                            </div>
                                        </q-card-section>
                                    </q-card>
                                </div>
                            </q-scroll-area>
                            <q-select
                                filled
                                v-model="feedback.type"
                                :options="feedbackTypes"
                                label="Select Type of Feedback"
                                dense
                                emit-value
                                map-options
                                style="width: 300px;"
                                class="q-mt-sm"
                            >
                            </q-select>
                            <q-editor class="my-editor q-mt-sm"  :dense="$q.screen.lt.md" height="8vh" v-model="feedback.feedback" toolbar-text-color="white"
                            toolbar-toggle-color="yellow-8"
                            toolbar-bg="primary"
                            :definitions="{
                                bold: {label: 'Bold', icon: null, tip: 'My bold tooltip'}
                            }"
                            :toolbar="[
                                ['bold', 'italic', 'underline', 'strike'],
                                ['quote', 'unordered', 'ordered', 'outdent', 'indent'],
                                ['link', 'hr', 'print'],
                                ['undo', 'redo'],
                                ['viewsource', 'fullscreen'],
                                ['subscript', 'superscript'],
                                ['font', 'size', 'color', 'bgcolor'],
                                ['align', 'removeFormat']
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
                        <q-btn outline dense color="primary" class="q-mt-sm" @click="createFeedback">Create Feedback</q-btn>  
                    </q-tab-panel>
                    <q-tab-panel name="history">
                        <q-scroll-area style="height: 500px">
                            <div class="row col-12">
                                <q-card bordered flat class="col-md-12" v-if="data.logs.length <= 0" >
                                    <q-card-section>
                                            <div class="text-center text-red text-italic text-weight-bold">This request have nothing history log(s)</div>
                                    </q-card-section>
                                    <q-card-section>
                                        <div class="q-gutter-y-md">
                                            <q-skeleton bordered type="circle" />
                                            <q-skeleton bordered />
                                            <q-skeleton bordered square />
                                            <q-skeleton square />
                                        </div>
                                    </q-card-section>
                                </q-card>
                                <template v-else>
                                    <q-card class="col-sm-12 q-mb-sm" bordered v-for="log in data.logs" :key="log.id">
                                        <q-card-section class="q-gutter-sm items-start q-pr-sm" :horizontal="true">
                                            <q-avatar color="teal" text-color="white" size="20px">
                                            {{ log.user.name.charAt(0).toUpperCase() }}
                                            </q-avatar>

                                            <div class="col"><div class="text-body2 text-grey">{{ log.user.name }}</div></div>

                                            <div class="text-sm text-weight-bold text-right text-primary" style="min-width: 90px;">
                                            {{ formatTanggalIndo(log.created_at) }}
                                            </div>
                                        </q-card-section>
                                        <q-separator inset />
                                        <q-card-section><div v-html="log.description" style="text-wrap: wrap;" class="text-primary"></div></q-card-section>
                                    </q-card>
                                </template>
                            </div>
                        </q-scroll-area>
                    </q-tab-panel>
                </q-tab-panels>
                </template>
            </q-splitter>
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
            tab: 'request',
            feedbackTypes: ['Adjustment','Recreate','Bugs', 'Rollback'],
            feedback: {
                feedback: '',
                type: ''
            },
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
            feedbackCreate: 'request/createFeedback'
        }),
        async updateRequest() {
            // console.log('update request', this.data)
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
                // console.log('update request error', err)
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
        },
        async createFeedback() {
            let payload = this.feedback
            payload.request_id = this.data.id
            await this.feedbackCreate(payload).then((res) => {
                if (res.status) {
                    this.$q.notify({
                        type: 'positive',
                        message: 'Feedback create successfully'
                    })
                    this.$emit('load')
                    this.feedback = {
                        feedback: '',
                        type: ''
                    }
                } else {
                    this.$q.notify({
                        type: 'negative',
                        message: 'Failed to create feedback'
                    })
                }
            }).catch((err) => {
                // console.log('update request error', err)
                this.$q.notify({
                    type: 'negative',
                    message: 'Failed to create feedback'
                })
            })
        }
    },
}
</script>