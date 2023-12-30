<template>
    <GoogleMap api-key="AIzaSyDKP-uIUcMVsEx-7V2B5lODXLu9_fX2jPA" style="width: 100%; height: 500px" :center="center"
        :zoom="15">
        <Marker :options="{ position: center }" />
    </GoogleMap>
</template>

<script>
import { defineComponent } from "vue";
import { GoogleMap, Marker } from "vue3-google-map";

export default defineComponent({
    props: {
        lat: Number,
        lng: Number,
    },
    components: { GoogleMap, Marker },
    setup(props) {
        console.log(props.lat);
        const center = { lat: props.lat, lng: props.lng };

        return { center };
    },
    watch: {
        lat: function() {            
            this.$forceUpdate();
    }},
});
</script>