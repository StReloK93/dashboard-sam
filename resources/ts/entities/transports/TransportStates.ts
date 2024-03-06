import { defineStore } from 'pinia'
import { computed, ref, Ref } from 'vue'
import axios from 'axios'
import moment from 'moment'

export const TransportStates = defineStore('TransportStates', () => {
    const transports: Ref = ref(null)
    const transportsAll: Ref = ref(null)

    async function getTransportState(transport_id) {
        const { data } = await axios.get(`/api/transportstates/${transport_id}`)
        transportsAll.value = data
        const filtered = data.filter((item) => {
            return moment(item.geozone_out).diff(item.geozone_in, 'seconds') > 10
        })
        transports.value = filtered
    }
    const isUnknown = computed(() => {
        return transports.value?.filter((transport) => transport.geozone == null)
    })

    const inATB = computed(() => {
        return transports.value?.filter((transport) => transport.geozone == "УАТ")
    })

    const inOIL = computed(() => {
        return transports.value?.filter((transport) => transport.geozone?.toLowerCase().includes('заправочный'))
    })

    const inSmenaAll = computed(() => {
        return transports.value?.filter((transport) => transport.geozone?.toLowerCase().includes('пересменка'))
    })
    const inSMENA = computed(() => {
        if (transports.value == null) return
        const filtered = transports.value?.filter((transport) => transport.geozone?.toLowerCase().includes('пересменка'))

        const group: any = {}

        filtered.forEach(item => {
            if (group[item.geozone]) group[item.geozone].push(item)
            else group[item.geozone] = [item]
        })
        return group
    })

    const inExcavator = computed(() => {
        return transports.value?.filter((transport) => {
            const ekg = transport.geozone?.toLowerCase().includes('экг')
            const ex = transport.geozone?.toLowerCase().includes('ex')
            const gues = transport.geozone?.toLowerCase().includes('эг')
            return ex || ekg || gues
        })
    })

    return { transports,transportsAll, getTransportState, inATB, inOIL, inSMENA, inExcavator, isUnknown , inSmenaAll}
})


export const TransportModal = defineStore('TransportModal', () => {
    const mode = ref(null)
    const transport = ref(null)

    function close() {
        mode.value = null
        transport.value = null
    }

    function openModal(selectedMode, selectedTransport) {
        mode.value = selectedMode
        transport.value = selectedTransport
    }
    return { mode, transport, close, openModal }
})