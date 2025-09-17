import { defineStore } from 'pinia'
import { computed, ref, Ref } from 'vue'
import { inZones } from '@/helpers/timeFormat'
import TruckStateRepository from './truckstate/TruckStateRepository'

export const TransportStates = defineStore('TransportStates', () => {
    const transports: Ref = ref(null)
    const transportsAll: Ref = ref(null)

    async function getTransportState(transport_id) {
        TruckStateRepository.show(transport_id, ({data}) => {
            transportsAll.value = data
            transports.value = data
        })

    }
    const isUnknown = computed(() => {
        return transports.value?.filter((transport) => transport.geozone == 'stopped')
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
        return transports.value?.filter((transport) => transport.geozone_type == 2)
    })

    return { transports, transportsAll, getTransportState, inATB, inOIL, inSMENA, inExcavator, isUnknown, inSmenaAll }
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