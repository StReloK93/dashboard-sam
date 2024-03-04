import moment from "moment"

export function minuteFormat(minutes) {
   const hours = Math.floor(minutes/60)
   const minFloored = Math.floor(minutes%60)
   const min = minFloored > 9 ? minFloored : `0${minFloored}`
   const hour = hours > 9 ? hours : `0${hours}`
   return `${hour}:${min}`
}

export function inZone(transport, zone) {
   return transport?.geozone?.toLowerCase().includes(zone)
}

export function timeDiff(transport, type) {
   return moment(transport.geozone_out).diff(transport.geozone_in, type)
}