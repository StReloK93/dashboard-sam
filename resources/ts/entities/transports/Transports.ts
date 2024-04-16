import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { inZone, timeDiff, secondTimer, inSmenaTime } from '@/helpers/timeFormat'
import axios from 'axios'
import moment from 'moment'

export const Transports = defineStore('Transports', () => {
   const cars = ref([])
   const summaSmenaCars = ref(0)
   const summaOilCars = ref(0)


   async function getTransports() {
      getExcavatorsStates()
      const { data } = await axios.get('/api/transportstates')
      // Filter Manlarni olib tashlaydi
      const sakasayana = data.filter((item) => item.name.includes('MAN') == false)
      // Sortirovka
      sakasayana.sort((a, b) => +a.name.replace('ШКБ С', '') - +b.name.replace('ШКБ С', ''))
      

      sakasayana.forEach(item => {
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

      cars.value = sakasayana
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
            if (timeDiff(transport, 'seconds') < 120) return false
            const diffMinutes = timeDiff(transport, 'minutes')


            if (inZone(transport, 'пересменка') && inSmenaTime(transport) == false) summSmenaTime += diffMinutes
            if (inZone(transport, 'заправочный')) summOilTime += diffMinutes


            const ekg = inZone(transport, 'экг')
            const gusenitsa = inZone(transport, 'эг')
            const ex = inZone(transport, 'ex')
            const fp = inZone(transport, 'фп')
            if (ex || ekg || gusenitsa || fp) summExcavatorTime += diffMinutes

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
            if (timeDiff(transport, 'seconds') < 120) return false
            return inZone(transport, 'экг') || inZone(transport, 'ex') || inZone(transport, 'эг') || inZone(transport, 'фп')
         })
         item.reys = filtered.length
      })

      return process
   })


   const isUnknown = computed(() => cars.value?.filter((car) => timeDiff(car, 'minutes') >= 45 && car.geozone == null))
   const inATB = computed(() => cars.value?.filter((car) => inZone(car, 'уат') || inZone(car, 'авто')))
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
                  if (diff > 0) group[geozone].counter++
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
            if (inZone(truck, 'пересменка') && inSmenaTime(truck) == false) {
               const diff = timeDiff(truck, 'minutes')
               const geozone = truck.geozone

               if (group[geozone]) {

                  group[geozone].summTime += diff
                  if (diff > 0) group[geozone].counter++
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



   function getFilterGroup(key, type) {
      const group: any = []
      cars.value?.forEach(car => {
         car.current_day.forEach((truck) => {
            if (truck.geozone == key && timeDiff(truck, 'minutes') > 0) {
               if (type == 'indigo' && inSmenaTime(truck)) return
               group.push(truck)
            }
         })
      })
      return group
   }



   const summaTransports = computed(() => {
      const activeCars = inProcess.value?.length + inExcavator.value?.length + inOilAll.value?.length
      return Math.round((activeCars / cars.value?.length) * 100)
   })

   const excavatorStates = ref(null)
   async function getExcavatorsStates() {
      const { data } = await axios.get('/api/transports/excavatorstate')
      // excavatorStates.value = data?.filter((car) => car.msg_dt != null)
      console.log(data)
      
      data.sort((a, b) => +a.garage - +b.garage)
      excavatorStates.value = data
   }

   const summaExcavators = computed(() => {
      const activeCars = excavatorStates.value?.filter((exv) => +exv.status == 1)
      return Math.round((activeCars?.length / excavatorStates.value?.length) * 100)
   })




   return {
      getTransports,
      getFilterGroup,
      summaTransports,
      summaExcavators,
      excavatorStates,
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