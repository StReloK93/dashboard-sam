import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { inZone, timeDiff, secondTimer } from '@/helpers/timeFormat'
import axios from 'axios'
import moment from 'moment'

export const Transports = defineStore('Transports', () => {
   const cars = ref(null)
   const summaSmenaCars = ref(0)
   const summaOilCars = ref(0)
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
   const { timer, reset } = secondTimer()
   echo.channel('cars').listen('RefreshEvent', () => {
      getTransports()
      reset()
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
            const gusenitsa = transport.geozone?.toLowerCase().includes('эг')
            const ex = transport.geozone?.toLowerCase().includes('ex')
            if (ex || ekg || gusenitsa) summExcavatorTime += diffMinutes

            return ex || ekg || gusenitsa
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
            return inZone(transport, 'экг') || inZone(transport, 'ex') || inZone(transport, 'эг') || inZone(transport, 'фп')
         })
         item.reys = filtered.length
      })
      return process
   })


   const isUnknown = computed(() => cars.value?.filter((car) => timeDiff(car, 'minutes') >= 45 && car.geozone == null))
   const inATB = computed(() => cars.value?.filter((car) => car.geozone == "УАТ" || inZone(car, 'авто')))
   const inOilAll = computed(() => cars.value?.filter((car) => inZone(car, 'заправочный')))
   const inSmenaAll = computed(() => cars.value?.filter((car) => inZone(car, 'пересменка')))
   const inExcavator = computed(() => cars.value?.filter((car) => inZone(car, 'экг') || inZone(car, 'ex') || inZone(car, 'эг') || inZone(car, 'фп')))
   
   

   const inOIL = computed(() => {
      const oil = cars.value?.filter((car) => inZone(car, 'заправочный'))
      const group: any = {}
      oil?.forEach(item => {
         const zone = item.geozone

         if (group[zone]) group[zone].cars.push(item)
         else group[zone] = { cars: [item], summTime: 0, counter: 0 }

      })

      cars.value?.forEach(car => {
         car.current_day.forEach((truck) => {
            if (inZone(truck, 'заправочный')) {
               const diff = timeDiff(truck, 'minutes')
               const geozone = truck.geozone
               if (group[geozone]) {
                  group[geozone].summTime += diff
                  if(diff > 0) group[geozone].counter++
               }
               else {
                  group[geozone] = { cars: [], summTime: diff, counter: diff > 0 ? 1 : 0 }
               }
               
            }
         })
      })

      var summa = 0
      for (const iterator in group) {
         summa += group[iterator].counter
      }
      summaOilCars.value = summa

      return group
   })


   const inSMENA = computed(() => {
      const smena = cars.value?.filter((car) => inZone(car, 'пересменка'))

      const group: any = {}
      smena?.forEach(item => {
         const zone = item.geozone
         if (group[zone]) group[zone].cars.push(item)
         else group[zone] = { cars: [item], summTime: 0, counter: 0 }

      })

      cars.value?.forEach(car => {
         car.current_day.forEach((truck) => {
            if (inZone(truck, 'пересменка')) {
               const diff = timeDiff(truck, 'minutes')
               const geozone = truck.geozone
               if (group[geozone]) {
                  group[geozone].summTime += diff
                  if(diff > 0) group[geozone].counter++
               }
               else {
                  group[geozone] = { cars: [], summTime: diff, counter: diff > 0 ? 1 : 0 }
               }
            }
         })
      })

      var summa = 0
      for (const iterator in group) {
         summa += group[iterator].counter
      }
      summaSmenaCars.value = summa
      return group
   })



   function getFilterGroup(key) {
      const group: any = []
      cars.value?.forEach(car => {
         car.current_day.forEach((truck) => {
            if (truck.geozone == key && timeDiff(truck, 'minutes') > 0) group.push(truck)
         })
      })
      return group
   }


   return {
      getTransports,
      getFilterGroup,
      timer,
      inATB,
      inOIL,
      inOilAll,
      inSMENA,
      inExcavator,
      isUnknown,
      inSmenaAll,
      inProcess,
      statesSumm,
      cars,
      summaSmenaCars,
      summaOilCars
   }
})

// 8158