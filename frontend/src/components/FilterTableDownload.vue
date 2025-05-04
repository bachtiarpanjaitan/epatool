<template>
    <div>
        <div class="row col-md-12 q-gutter-sm q-pb-sm" >
            <q-select style="min-width: 150px; max-width: 250px" clearable  outlined dense v-model="filter.column" label="Column" :options="filterColumns" />
            <q-select style="min-width: 150px; max-width: 250px" clearable outlined dense v-model="filter.operator" label="Operator" :options="operators" />
            <template v-if="value.type == 'select'">
                <q-select style="min-width: 100px; max-width: 250px"  :options="value.options"  outlined dense v-model="filter.value" label="Value" />
            </template>
            <template v-else>
                <q-input :type="value.type"  outlined dense v-model="filter.value" label="Value" />
            </template>
            <q-btn color="primary" outline @click="load()" icon="filter_alt"/>
            <q-btn color="primary" outline @click="reset()" icon="filter_alt_off"/>
            <q-btn color="primary" outline @click="downloadExcel()" :loading="loading" icon="download"/>
        </div>
    </div>
</template>
<script>
    import * as operator from './../constants/operator'
    import _ from 'lodash'
    export default {
        name: "FilterTableDownload",
        props: {
            filter: {
                type: Object,
                default: {},
            },
            columns: {
                type: Array,
                default: [],
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
                value: {
                    type: 'text',
                    options: []
                },
                filterColumns: []
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
            downloadExcel(){this.$emit('download')}
        }
    }
</script>