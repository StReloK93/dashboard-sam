import { defineStore } from 'pinia'
import { ref, Ref } from 'vue'
import axios from 'axios'
export const Geofences = defineStore('Geofences', () => {
    const geofences: Ref = ref()
    async function getGeofenses(){
        const { data } = await axios.get('/api/geofences/all')
        return geofences.value = data
    }

    return { geofences, getGeofenses }
})