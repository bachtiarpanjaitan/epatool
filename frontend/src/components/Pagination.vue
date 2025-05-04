<template>
  <div>
    <q-btn
      color="primary"
      dense
      flat
      disabled
      :label="calcData"
    />
    <q-btn
      icon="first_page"
      color="grey-8"
      round
      dense
      flat
      @click="this.data.page = 1"
    />

    <q-btn
      icon="chevron_left"
      color="grey-8"
      round
      dense
      flat
      :disabled="this.data.page == 1"
      @click="this.data.page--"
    />

    <q-btn
      icon="chevron_right"
      color="grey-8"
      round
      dense
      flat
      :disabled="this.data.page == this.data.max"
      @click="this.data.page++"
    />

    <q-btn
      icon="last_page"
      color="grey-8"
      round
      dense
      flat
      @click="this.data.page = this.data.max"
    />
  </div>
</template>
<script>
export default {
  name: "Pagination",
  props: {
    data: {
      type: Object,
      default: {},
    },
  },
  computed:{
    calcData(){
        
        if(this.data.max <= 1) {
            return `${this.data.rowsNumber}/${this.data.rowsNumber}`
        }

        if (this.data.max > 1 && this.data.page < this.data.max) {
            let rows = this.data.rowsNumber - (this.data.rowsNumber - (this.data.rowsPerPage * (this.data.page)))

            return `${rows}/${this.data.rowsNumber}`
        }

        if(this.data.page >= this.data.max) {
            return `${this.data.rowsNumber}/${this.data.rowsNumber}`
        }
    }
  },
  data() {
    return {};
  },
};
</script>
