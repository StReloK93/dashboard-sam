import { defineStore } from 'pinia'
import { computed, ref, Ref } from 'vue'
import axios from 'axios'
import { inZones, timeDiff, inExcavatorHelper } from '@/helpers/timeFormat'


export const TransportStates = defineStore('TransportStates', () => {
    const transports: Ref = ref(null)
    const transportsAll: Ref = ref(null)

    async function getTransportState(transport_id) {
        const { data } = await axios.get(`/api/transportstates/${transport_id}`)
        transportsAll.value = data
        const filtered = data.filter((item) => {
            return timeDiff(item, 'seconds') > 10
        })
        transports.value = filtered


    }
    const isUnknown = computed(() => {
        return transports.value?.filter((transport) => transport.geozone == null)
    })

    const inATB = computed(() => {
        const filtered = transports.value?.filter((transport) => inZones(transport, settings.uat))
        
        filtered?.forEach((item) => item.bool = true)
        return filtered
    })

    const inOIL = computed(() => {
        return transports.value?.filter((transport) => inZones(transport, settings.oil))
    })

    const inSmenaAll = computed(() => {
        const filtered = transports.value?.filter((transport) => inZones(transport, settings.smena))
        filtered?.forEach((item) => item.bool = true)
        return filtered
    })
    const inSMENA = computed(() => {
        if (transports.value == null) return
        const filtered = transports.value?.filter((transport) => inZones(transport, settings.smena))

        const group: any = {}

        filtered.forEach(item => {
            if (group[item.geozone]) group[item.geozone].push(item)
            else group[item.geozone] = [item]
        })
        return group
    })

    const inExcavator = computed(() => {
        return transports.value?.filter((transport) => {
            if (timeDiff(transport, 'seconds') < 120) return false
            
            return inExcavatorHelper(transport)
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