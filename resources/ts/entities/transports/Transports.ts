import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { inZone , timeDiff } from '@/helpers/timeFormat'
import axios from 'axios'
import moment from 'moment'
export const Transports = defineStore('Transports', () => {
   const cars = ref(null)
   async function getTransports() {
      const { data } = await axios.get('/api/transportstates')

      data.forEach(item => {
         item.name = item.name.replace('ШКБ ', '')
         const time = moment()
         const diffMinutes = time.diff(item.geozone_in, 'minutes')

         if (diffMinutes > 1439) {
            item.timer = time.diff(item.geozone_in, 'days')
            item.timer_type = 2
         }
         else if (diffMinutes > 59) {
            item.timer = time.diff(item.geozone_in, 'hours')
            item.timer_type = 1
         }
         else {
            item.timer = diffMinutes
            item.timer_type = 0
         }
      })
      cars.value = data
   }
   
   getTransports()
   echo.channel('cars').listen('RefreshEvent', () => getTransports())


   const isUnknown = computed(() => {
      return cars.value?.filter((transport) => timeDiff(transport, 'minutes') >= 45 && transport.geozone == null)
   })

   const statesSumm = computed(() => {
      var reysCount = 0
      var summSmenaTime = 0
      var summOilTime = 0
      var summExcavatorTime = 0

      
      cars.value?.forEach((item) => {

         const filtered = item.current_day.filter((transport) => {
            if (moment(transport.geozone_out).diff(transport.geozone_in, 'seconds') < 11) return false
            
            const diffMinutes = moment(transport.geozone_out).diff(transport.geozone_in, 'minutes')

            if (transport.geozone?.toLowerCase().includes('пересменка')) summSmenaTime += diffMinutes
            if (transport.geozone?.toLowerCase().includes('заправочный')) summOilTime += diffMinutes

            const ekg = transport.geozone?.toLowerCase().includes('экг')
            const ex = transport.geozone?.toLowerCase().includes('ex')
            if (ex || ekg) summExcavatorTime += diffMinutes

            return ex || ekg
         })

         reysCount += filtered.length
      })

      return { reysCount, summSmenaTime, summOilTime, summExcavatorTime }
   })

   const inProcess = computed(() => {
      const process = cars.value?.filter((transport) => timeDiff(transport, 'minutes') < 45 && transport.geozone == null)
      
      process?.forEach((item) => {
         const filtered = item.current_day.filter((transport) => {
            if (timeDiff(transport, 'seconds') < 11) return false

            return inZone(transport, 'экг') || inZone(transport, 'ex')
         })
         item.reys = filtered.length
      })
      return process
   })



   const inATB = computed(() => {
      return cars.value?.filter((transport) => transport.geozone == "УАТ")
   })

   const inOilAll = computed(() => {
      return cars.value?.filter((transport) => inZone(transport, 'заправочный'))
   })

   const inOIL = computed(() => {
      const filtered = cars.value?.filter((transport) => inZone(transport, 'заправочный'))
      
      const group: any = {}
      filtered?.forEach(item => {
         if (group[item.geozone]) {
            group[item.geozone].cars.push(item)
         }
         
         else {
            group[item.geozone] = {cars: [item], summTime: 0}
         }
      })

      cars.value?.forEach(car => {
         car.current_day.forEach((truck) => {
            if (inZone(truck, 'заправочный')) {
               const diff = timeDiff(truck, 'minutes')

               if (group[truck.geozone]) {
                  group[truck.geozone].summTime += diff
               }
               else {
                  group[truck.geozone] = {cars: [], summTime: diff}
               }
            }
         }) 
      })
      
      return group
   })

   const inSmenaAll = computed(() => {
      return cars.value?.filter((transport) => inZone(transport, 'пересменка'))
   })

   const inSMENA = computed(() => {
      const filtered = cars.value?.filter((transport) => inZone(transport, 'пересменка'))
      
      const group: any = {}
      filtered?.forEach(item => {
         if (group[item.geozone]) {
            group[item.geozone].cars.push(item)
         }
         
         else {
            group[item.geozone] = {cars: [item], summTime: 0}
         }
      })

      cars.value?.forEach(car => {
         car.current_day.forEach((truck) => {
            if (inZone(truck, 'пересменка')) {
               const diff = timeDiff(truck, 'minutes')

               if (group[truck.geozone]) {
                  group[truck.geozone].summTime += diff
               }
               else {
                  group[truck.geozone] = {cars: [], summTime: diff}
               }
            }
         }) 
      })

      return group
   })

   const inExcavator = computed(() => {
      return cars.value?.filter((transport) => inZone(transport, 'экг') || inZone(transport, 'ex'))
   })


   return { getTransports, inATB, inOIL,inOilAll, inSMENA, inExcavator, isUnknown, inSmenaAll, inProcess, statesSumm , cars}
})

// 8158