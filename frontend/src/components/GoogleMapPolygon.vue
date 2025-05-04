<script>
export default {
  props: {
    google: {
      type: Object,
      required: true,
    },
    map: {
      type: Object,
      required: true,
    },
    coords: {
        type: Array,
        required:true
    },
    drawing: {
      type: Boolean,
      required: false,
      default: true
    }
  },

  beforeMount() {
    const { Polygon } = this.google.maps;
    new Polygon({
      paths: this.coords,
      map: this.map
    });
  },

  render() {},
};
</script>
