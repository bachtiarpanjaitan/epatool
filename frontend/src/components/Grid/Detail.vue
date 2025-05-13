<template>
    <div>
        <q-card>
            <q-card-section>
                <div class="col-md-12 row">
                    <div class="col-md-3">
                        <q-select
                            dense
                            outlined
                            :options="users"
                            label="Assign user"
                            v-model="form.assign_users"
                            multiple
                            use-chips
                            option-label="name"
                            option-value="id"
                            />
                    </div>
                     <div class="col-md-2 q-pl-sm">
                        <q-select
                            dense
                            outlined
                            :options="statuses"
                            label="Set Status"
                            v-model="data.status"
                            />
                    </div>
                </div>
            </q-card-section>
             <q-card-section horizontal>
                <div class="row q-pl-md q-pb-sm">
                    <div class="col-md-5">
                        <q-input class="q-pb-sm" outlined dense v-model="data.title" label="Title" />
                        <q-editor :readonly="me.role != 1" class="my-editor" dense height="46vh" v-model="data.description" toolbar-text-color="white"
                        toolbar-toggle-color="yellow-8"
                        toolbar-bg="primary"
                        :definitions="{
                            bold: {label: 'Bold', icon: null, tip: 'My bold tooltip'}
                        }"></q-editor>
                        <q-btn outline dense color="primary" class="q-mt-sm">Update Request</q-btn>  
                    </div>
                    <div class="col-md-4 q-pl-sm">
                        <q-scroll-area style="height: 290px;">
                            <q-list dense bordered>
                                <q-item-label header color="primary">Feedbacks</q-item-label>
                                <q-item clickable v-ripple>
                                    <q-item-section>
                                    <q-item-label caption>
                                        Set the content filtering level to restrict
                                        apps that can be downloaded
                                    </q-item-label>
                                    </q-item-section>
                                </q-item>
                                <q-item clickable v-ripple>
                                    <q-item-section>
                                    <q-item-label caption>
                                        Require password for purchase or use
                                        password to restrict purchase
                                    </q-item-label>
                                    </q-item-section>
                                </q-item>
                            </q-list>
                        </q-scroll-area>
                        <q-editor class="my-editor q-mt-sm"  :dense="$q.screen.lt.md" height="10vh" v-model="form.feedback" toolbar-text-color="white"
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
                        <q-btn outline dense color="primary" class="q-mt-sm">Send Feedback</q-btn>  
                    </div>
                    <div class="col-md-3 q-px-sm">
                        <q-scroll-area style="height: 350px; max-width: 400px;">
                             <q-list bordered class="rounded-borders" style="max-width: 350px">
                                <q-item-label header color="primary">History</q-item-label>
                                <q-item clickable v-ripple>
                                    <q-item-section avatar>
                                    <q-avatar>
                                        <img src="https://cdn.quasar.dev/img/avatar2.jpg">
                                    </q-avatar>
                                    </q-item-section>

                                    <q-item-section>
                                    <q-item-label lines="1">Brunch this weekend?</q-item-label>
                                    <q-item-label caption lines="2">
                                        <span class="text-weight-bold">Janet</span>
                                        -- I'll be in your neighborhood doing errands this
                                        weekend. Do you want to grab brunch?
                                    </q-item-label>
                                    </q-item-section>

                                    <q-item-section side top>
                                    1 min ago
                                    </q-item-section>
                                </q-item>

                                <q-separator inset="item" />

                                <q-item clickable v-ripple>
                                    <q-item-section avatar>
                                    <q-avatar>
                                        <img src="https://cdn.quasar.dev/img/avatar4.jpg">
                                    </q-avatar>
                                    </q-item-section>

                                    <q-item-section>
                                    <q-item-label lines="1">Linear Project</q-item-label>
                                    <q-item-label caption lines="2">
                                        <span class="text-weight-bold">John</span>
                                        -- Can we schedule a call for tomorrow?
                                    </q-item-label>
                                    </q-item-section>

                                    <q-item-section side top>
                                    1 min ago
                                    </q-item-section>
                                </q-item>
                                </q-list>
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
            form: {
                feedback: '',
                assign_users: []
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
        // ...mapActions({
        //     loadUsers: 'common/getusers'
        // }),
    },
}
</script>