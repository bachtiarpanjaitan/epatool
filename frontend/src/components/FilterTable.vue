<template>
    <div>
        <div class="row col-12 q-gutter-sm" >
            <q-select style="min-width: 150px; max-width: 250px" clearable  outlined dense v-model="filter.column" label="Column" :options="filterColumns" />
            <q-select style="min-width: 150px; max-width: 250px" clearable outlined dense v-model="filter.operator" label="Operator" :options="operators" />
            <template v-if="value.type == 'select'">
                <q-select style="min-width: 100px; max-width: 250px"  :options="value.options"  outlined dense v-model="filter.value" label="Value" />
            </template>
            <template v-else>
                <q-input :type="value.type"  outlined dense v-model="filter.value" label="Value" />
            </template>
            <q-btn color="primary" outline @click="load()" icon="filter_alt"/>
            <q-btn color="red-10" outline @click="reset()" icon="filter_alt_off">
                <q-tooltip class="bg-primary text-body2" :offset="[10, 10]">
                    Clear Filter
                </q-tooltip>
            </q-btn>
            
            <template v-if="u">
                <q-file ref="ufile" accept=".xlsx,.xls" color="purple-12" style="display:none"  @update:model-value="upload" label="Label"/>
                <q-btn label="Import" color="primary" :loading="loading" outline @click="pickFile()" icon="cloud_upload">
                    <q-tooltip class="bg-primary text-body2" :offset="[10, 10]">
                        Import Data
                    </q-tooltip>
                </q-btn>
            </template>
            <template v-if="d">
                <q-btn color="green-5" outline @click="downloadExcel()" :loading="loading" icon="download">
                    <q-tooltip class="bg-primary text-body2" :offset="[10, 10]">
                        Download Raw Report
                    </q-tooltip>
                </q-btn>
            </template>
            <template v-if="t">
                <q-btn label="Template" :loading="loading" color="primary" outline @click="template()" icon="file_copy">
                     <q-tooltip class="bg-primary text-body2" :offset="[10, 10]">
                        Download Template
                    </q-tooltip>
                </q-btn>
            </template>
            <template v-if="r">
                <q-btn :loading="loading" color="yellow-10" outline @click="dialog=true" tooltip="Report" icon="cloud_download">
                    <q-tooltip class="bg-primary text-body2" :offset="[10, 10]">
                        Download Summary Report
                    </q-tooltip>
                </q-btn>
            </template>
        </div>
        <div>
            <q-dialog
                v-model="dialog"
                transition-show="slide-up"
                transition-hide="slide-down"
                >
                <q-card color="primary" style="width: 350px">
                    <q-bar>
                        <q-space />
                        <q-btn dense flat icon="close" v-close-popup>
                            <q-tooltip class="bg-white text-primary">Close</q-tooltip>
                        </q-btn>
                    </q-bar>
                    <q-card-section>
                        <q-form ref="form" lazy-validation>
                            <div class="py-md">
                                <div class="q-pb-sm col-md-12">
                                    <label for="">From</label>
                                    <q-input
                                        v-model="form.from"
                                        dense
                                        outlined
                                        type="date"
                                        :rules="inputRules"
                                    />
                                </div>
                                <div class="q-pb-sm col-md-12">
                                    <label for="">To</label>
                                    <q-input
                                        v-model="form.to"
                                        dense
                                        outlined
                                        type="date"
                                        :rules="inputRules"
                                    />
                                </div>
                            </div>
                        </q-form>
                        <small class="text-red">This process takes a little longer, please wait !!!</small>
                    </q-card-section>
                    
                    <q-card-actions>
                        
                        <q-space></q-space>
                        <div class="py-md">
                            <q-btn v-close-popup color="grey" @click="this.$emit('done')" icon="cancel" class="q-mr-sm" outline>Close</q-btn>
                        </div>
                        <div class="py-md">
                            <q-btn color="primary" type="submit" :loading="loading" class="q-mr-sm" @click="downloadReport()" outline>Process</q-btn>
                        </div>
                    </q-card-actions>
                </q-card>
            </q-dialog>
        </div>
    </div>
</template>
<script>
    import * as operator from './../constants/operator'
    import _ from 'lodash'
    export default {
        name: "FilterTable",
        props: {
            filter: {
                type: Object,
                default: {},
            },
            columns: {
                type: Array,
                default: [],
            },
            config: {
                type: Object,
                default: {
                    upload: false,
                    template: false,
                    download: false,
                    report: false
                }
            },
            loading: {
                type: Boolean,
                default: false
            },
            pagination: {
                type: Object,
                default: {},
            },
            
        },
        watch : {
            'filter.column' (n,o){
                if(n != null){
                    this.value.type = n.type
                    this.value.options = n.options
                }
            },
            'filter.value' (n,o){
                if(typeof(n) == 'object'){
                    this.filter.value = n.value
                }
            },
            'columns' (n,o) {
                this.filterColumns = _.filter(n, x => x.show == true)
            }
        },
        data() {
            return {
                operators: operator.Operator,
                u: this.config.upload,
                t: this.config.template,
                d: this.config.download,
                r: this.config.report,
                value: {
                    type: 'text',
                    options: []
                },
                filterColumns: [],
                dialog: false,
                form: {
                    form: null,
                    to: null
                },
                inputRules: [
                    (v) => !!v || `Data ini tidak boleh kosong`,
                ],
            };
        },
        methods: {
            load(){
                this.pagination.page = 1
                this.$emit('load')
            },
            reset(){ 
                this.pagination.page = 1
                this.$emit('reset')
            },
            pickFile(){ 
                this.$refs.ufile.pickFiles();
            },
            upload(value){  
                this.$refs.ufile.value = null;
                this.$emit('upload',value) 
            },
            template(){ this.$emit('template') },
            downloadExcel(){this.$emit('download')},
            downloadReport(){
                this.$refs.form.validate().then(async v => {
                    this.$emit('report', this.form)
                })
            }
        }
    }
</script>