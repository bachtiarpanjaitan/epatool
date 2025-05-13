<template>
    <q-page >
        <div>
            <div class="q-pa-md">
                <div class="row q-gutter-sm q-pb-sm">
                    <div class="text-h5">
                        My Requests
                    </div>
                    <q-space></q-space>
                    <q-btn size="sm" color="primary" unelevated icon="add" label="Add" @click="showForm({})"/>
                </div>
                <q-table
                    flat bordered dense
                    :rows="rows"
                    :columns="columns"
                    row-key="id"
                    virtual-scroll
                    :loading="loading"
                    :rows-per-page-options="[15]"
                    :pagination.sync="pagination"
                    separator="horizontal"
                >
                    <template v-slot:body="props">
                        <q-tr :props="props" style="cursor: pointer;">
                            <q-td v-for="col in props.cols" :key="col.name" :props="props">
                                <template v-if="col.name === 'action'">
                                    <!-- <q-btn size="xs" outline @click="showDetail(props.row)" color="primary" icon="read_more" align="between"/> -->
                                    <!-- <q-btn size="xs" outline @click="showForm(props.row)" color="primary" icon="edit" align="between"/> -->
                                    <!-- <q-btn size="xs" outline @click="viewHistory(props.row)" color="primary" icon="list" align="between"/> -->
                                    <q-btn size="xs" outline @click="actDelete(props.row)" color="red" icon="delete" align="between"/>
                                </template>
                                <template v-if="col.name === 'title'">
                                    <a class="text-blue" style="cursor: pointer;" @click="props.expand = !props.expand" >{{ col.value }}</a>
                                </template>
                                <template v-else-if="col.name === 'description'">
                                    {{ col.value.length > 100 ? col.value.substring(0, 100) + '...' : col.value }}
                                </template>
                                <template v-else-if="col.name === 'status'">
                                    <q-badge outline color="primary">{{ col.value }}</q-badge>
                                </template>
                                <template v-else-if="col.name === 'viewmore'">
                                    <q-btn size="sm" color="primary" round outline dense @click="props.expand = !props.expand" :icon="props.expand ? 'unfold_less' : 'unfold_more'" />
                                </template>
                                <template v-else>
                                    {{ col.value }}
                                </template>
                            </q-td>
                        </q-tr>
                        <q-tr v-show="props.expand" :props="props">
                            <q-td colspan="100%">
                                <Detail :data="props.row" :users="workers" />
                            </q-td>
                        </q-tr>
                    </template>

                    <template v-slot:top>
                        <FilterTable :filter="filter" :columns="columns" @load="load()" @reset="reset()" :pagination="pagination" />
                    </template>

                    <template v-slot:pagination="scope">
                       <pagination :data="pagination"/>
                    </template>
                </q-table>
                <div>
                    <q-dialog
                        v-model="dialog"
                        persistent
                        :maximized="maximizedToggle"
                        transition-show="slide-up"
                        transition-hide="slide-down"
                        >
                            <Form @done="reload()" :loading="loading" :data="selectedRow" :maximizedToggle="maximizedToggle"/>
                    </q-dialog>
                </div>

            </div>
        </div>
    </q-page>
</template>
<script>
import { defineComponent } from "vue";
import { mapActions } from "vuex";
import Pagination from "src/components/Pagination.vue";
import FilterTable from "src/components/FilterTable.vue";
import Detail from "src/components/Grid/Detail.vue";
import Form from "./Form.vue";
export default defineComponent({
    name: "RequestIndex",
    components: {
        Pagination,
        FilterTable,
        Form,
        Detail
    },

    data() {
        return {
            loading:false,
            rows:[],
            columns:[],
            pagination: {
                page:1,
                rowsPerPage: 15,
                rowsNumber:0,
                descending: false,
                sortBy: 'id',
                max:0
            },
            filter: {},
            dialog: false,
            hDialog: false,
            maximizedToggle: false,
            users: [],
            workers: [],
            form: {
                name: '',
                branch_id: '',
                location: '',
                type: '',
                from: '',
                to: '',
                status: 0,
                timezone: 0,
                ref_project_id: 0
            },
            edit: false,
            selectedRow: {},
        }
    },
    methods: {
        ...mapActions({
            loadRequests: 'request/myrequest',
            loadWorkers: 'common/getusers'
        }),
        actDelete(row) { },
        showForm(row) {
            this.dialog = true;
        },
        reload(){
            this.load()
            this.dialog = false
        },
        reset() {
            this.filter = {}
            this.pagination.page = 1
            this.load()
        },
        async load() {
            this.loading = true
            let params = {
                _limit: this.pagination.rowsPerPage,
                _page: this.pagination.page,
                workspace: localStorage.getItem('workspace'),
            }

            if(this.pagination.sortBy != null){
                params._sort = `${this.pagination.sortBy}:${this.pagination.descending ? 'desc':'asc'}`
            }

            if(this.filter.operator != undefined){
                if(this.filter.operator.value == '_notnull' || this.filter.operator.value == '_null'){
                    this.filter.value = null
                }
            }

            if(this.filter.column != undefined){
                params[`${this.filter.column.name}${this.filter.operator.value}`] = this.filter.value
            }


            await this.loadRequests(params).then(({data}) => {
                let actionCol = {
                    name: 'action',
                    label: 'Action',
                    field: 'action',
                    align: 'center'
                }
                let viewmore = {
                    name: 'viewmore',
                    label: '',
                    field: 'viewmore',
                    align: 'center'
                }
                this.columns = [viewmore,...data.includes.columns,actionCol]
                this.rows = data.data
                this.pagination.rowsNumber = data.includes.total
                this.pagination.max = Math.ceil(data.includes.total/this.pagination.rowsPerPage);

            }).finally(() => {
                this.loading = false
            }).catch(err => {
                console.log(err)
                this.loading = false
            })

            this.loadWorkers().then(({data}) => {
                this.workers = data.data
            }).catch(err => {
                console.log(err)
            })
        }

    },
    mounted(){
        this.load()
    }
})
</script>