import { defineStore } from 'pinia'
import axios from "axios"
import { iInProcess, iTransport } from './intercafes'
import { ref, computed, Ref } from 'vue'
const api = axios.create({})
api.defaults.baseURL = '/api/process'


export const inProcess = defineStore('inProcess', () => {
    const transports: Ref<iTransport[]> = ref()
    const summaFligts: Ref<number> = ref()


    function getAllTrips() {
        // api.get<iInProcess>('all').then(({ data }) => {
        //     data.transports.forEach(item => item.name = item.name.replace('ШКБ ', ''))

        //     const sorts = data.transports.sort((a, b) => {
        //         if (a.name > b.name) return 1
        //         if (a.name < b.name) return -1
        //         return 0
        //     })

        //     transports.value = sorts
        //     summaFligts.value = data.count

        // })
    }

    const summaTrips = computed(() => transports.value?.length)

    return { transports, summaTrips, summaFligts, getAllTrips }
})