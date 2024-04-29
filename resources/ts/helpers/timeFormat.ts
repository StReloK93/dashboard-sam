import moment from "moment"
import { ref } from "vue"
export function minuteFormat(minutes) {
   const hours = Math.floor(minutes/60)
   const minFloored = Math.floor(minutes%60)
   const min = minFloored > 9 ? minFloored : `0${minFloored}`
   const hour = hours > 9 ? hours : `0${hours}`
   return `${hour}:${min}`
}

export function inZone(transport, zone) {
   return transport?.geozone?.toLowerCase().includes(zone.toLowerCase())
}

export function timeDiff(transport, type) {
   return moment(transport.geozone_out).diff(transport.geozone_in, type)
}

export function getDifference(transport) {
   const minutes = moment(transport.geozone_out).diff(transport.geozone_in, 'minutes', true)
   const seconds = moment(transport.geozone_out).diff(transport.geozone_in, 'second', true) % 60
   const hours = Math.floor(minutes/60)
   const minFloored = Math.floor(minutes%60)
   const secFloored = Math.floor(seconds)
   const min = minFloored > 9 ? minFloored : `0${minFloored}`
   const sec = secFloored > 9 ? secFloored : `0${secFloored}`
   const hour = hours > 9 ? hours : `0${hours}`
   return `${hour}:${min}:${sec}`
}


export function secondTimer() {
   const oldTime = ref(moment())
   const timer = ref()
   setInterval(() => {
      const minuteDiff = moment().diff(oldTime.value, 'minute')
      const minutes = minuteDiff%60 
      const secundDiff = moment().diff(oldTime.value, 'second')
      const seconds = secundDiff%60 
      
      const secund = seconds > 9 ? seconds : `0${seconds}`
      const minute = minutes > 9 ? minutes : `0${minutes}`
      timer.value = `${minute}:${secund}`
   }, 1000)
   
   function reset() {
      oldTime.value = moment()
   }
   return { timer , reset}
}


export function inSmenaTime(transport) {
   const oneStart = moment().set({ hour: 9, minute: 0, second: 0 })
   const oneEnd = moment().set({ hour: 9, minute: 40, second: 0 })

   const twoStart = moment().set({ hour: 21, minute: 0, second: 0 })
   const twoEnd = moment().set({ hour: 21, minute: 40, second: 0 })

   const one = moment(transport.geozone_in).isBetween(oneStart, oneEnd)
   const two = moment(transport.geozone_out).isBetween(oneStart, oneEnd)
   // console.log(one && two, transport.geozone_in, transport.geozone_out);
   
   return one && two
}


export function calculateDistance(lat1, lon1, lat2, lon2) {
   const R = 6371e3; // Радиус Земли в метрах
   const φ1 = lat1 * Math.PI / 180; // Широта первой точки в радианах
   const φ2 = lat2 * Math.PI / 180; // Широта второй точки в радианах
   const Δφ = (lat2 - lat1) * Math.PI / 180; // Разница широт
   const Δλ = (lon2 - lon1) * Math.PI / 180; // Разница долгот

   // Формула гаверсинусов
   const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
             Math.cos(φ1) * Math.cos(φ2) *
             Math.sin(Δλ/2) * Math.sin(Δλ/2);
   const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

   // Расстояние между точками в метрах
   const distance = R * c;
   
   return distance;
}

export function calculatePathLength(points) {
   let pathLength = 0;

   // Проходим по всем точкам, начиная с второй
   for (let i = 1; i < points.length; i++) {
       const prevPoint = points[i - 1];
       const currentPoint = points[i];
       const distance = calculateDistance(prevPoint.y, prevPoint.x, currentPoint.y, currentPoint.x);
       pathLength += distance;
   }

   return pathLength;
}

export function UTCTime<Number>(time: string) {
   const date = new Date(time)
   return Date.UTC(date.getFullYear(), date.getMonth(), date.getUTCDate(), date.getHours(), date.getUTCMinutes())
}
