<template>
    <div>
      <!-- <div id="floating-panel">
        <q-btn id="delete-markers" color="primary" outlined size="sm" label="Delete Marker" value="Delete Marker" />
      </div> -->
      <div class="google-map" ref="googleMap" ></div>
      <template v-if="Boolean(this.google) && Boolean(this.map)">
        <slot :google="google" :map="map" />
      </template>
    </div>
</template>
  <style>
    #floating-panel {
      position: absolute;
      top: 25px;
      left: 40%;
      z-index: 5;
      padding: 5px;
      text-align: center;
      font-family: "Roboto", "sans-serif";
      line-height: 30px;
      padding-left: 5px;
    }
  </style>
  <script>
    import { Loader } from '@googlemaps/js-api-loader';
    import { POINT_MARKER_ICON_CONFIG } from 'src/constants/mapSettings';
    export default {
        props: {
          mapConfig: Object,
          apiKey: String,
          draw: {
            type: Boolean,
            default: true
          },
          marker: {
            type: Object,
            default: null
          }
        },
    
        data() {
          return {
              google: null,
              map: null,
              mapContainer: null,
              loader: null,
              results: this.coords,
              icon: POINT_MARKER_ICON_CONFIG
          };
        },
    
        mounted() {
            this.loader = new Loader({
                  apiKey: this.apiKey,
                  libraries: ['drawing','marker']
            });
            this.initializeMap();
        },
    
        methods: {
          initializeMap() {
            this.mapContainer = this.$refs.googleMap;
            this.map = this.loader.load().then((google) => {
              this.google = google
              let map = new google.maps.Map(this.mapContainer, this.mapConfig);
              if(this.draw){

                /** Create Marker */
                if(this.marker != null){
                  const marker = new google.maps.Marker({
                    position: this.marker.position,
                    marker: this.marker,
                    map: this.map,
                    icon: POINT_MARKER_ICON_CONFIG,
                    label: this.marker.title,
                    label: this.marker.title,
                    optimized: true,
                    draggable:true,
                  });
                  marker.setMap(map)

                  google.maps.event.addListener(marker, 'dragend', function(e) {
                    let position = e.latLng
                    let c = [position.lat(),position.lng()];
                    sessionStorage.setItem('coords',c)
                  });
                  
                }

                /** Create Drawing */
                const drawing = new google.maps.drawing.DrawingManager({
                  drawingMode: google.maps.drawing.OverlayType.MARKER,
                  drawingControl: true,
                  drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [
                      // google.maps.drawing.OverlayType.CIRCLE,
                      google.maps.drawing.OverlayType.MARKER,
                      // google.maps.drawing.OverlayType.POLYGON,
                      // google.maps.drawing.OverlayType.POLYLINE,
                      // google.maps.drawing.OverlayType.RECTANGLE,
                    ],
                  },
                  markerOptions: {
                    icon: this.icon,
                    clickable: true,
                    draggable: true,
                    title: 'Selected Area',
                    label: 'Selected Area',
                  },
                  circleOptions: {
                    fillColor: "#F59E0B",
                    fillOpacity: 1,
                    strokeWeight: 2,
                    clickable: true,
                    editable: true,
                    zIndex: 1,
                  },
                })
                drawing.setMap(map)

                if(this.marker != null)  drawing.setMap(null)

                /**
                 * Event
                 */
                google.maps.event.addListener(drawing, 'markercomplete', function(e) {
                  if(e.type != 'marker'){
                    console.log('CLICK',e)
                    let position = e.position
                    let c = [position.lat(),position.lng()];
                    sessionStorage.setItem('coords',c)
                  }
                  drawing.setMap(null)
                });

                google.maps.event.addListener(drawing, 'dragend', function(e) {
                  console.log('DRAG',e)
                  let position = e.latLng
                  let c = [position.lat(),position.lng()];
                  sessionStorage.setItem('coords',c)
                });
              }
            }).catch(e => {
              console.log(e)
            });


          }
        }
    };
  </script>
  
  <style scoped>
  .google-map {
    width: 100%;
    min-height: 100%;
  }
  </style>
  