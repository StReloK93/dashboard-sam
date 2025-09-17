import { useFetch } from "@/helpers/useFetchWialon"
import type { ITruckState } from "@/interfaces"
const baseUrl = 'truck-states'



function index(params: any, onload?: Function) {
   return useFetch<{states: ITruckState[], groups: object}>({
      url: `${baseUrl}/last-states-group`,
      method: 'post',
      formData: params,
      onLoad: onload
   })
}

function clean(params: any, onload?: Function) {
   return useFetch<ITruckState[]>({
      url: `${baseUrl}/last-states-group/clean`,
      method: 'post',
      formData: params,
      onLoad: onload
   })
}




function show(transport_id: number, onload?: Function) {
   return useFetch<any[]>({
      url: `${baseUrl}/one/${transport_id}`,
      method: 'post',
      onLoad: onload
   })
}


function selectSmena(date: any, onload?: Function) {
   return useFetch<any[]>({
      url: `${baseUrl}/select-smena`,
      method: 'post',
      formData: date,
      onLoad: onload
   })
}

function updateCause(formData: { id: number, causes: any[] }, onload?: Function) {
   return useFetch<any[]>({
      url: `cause`,
      method: 'post',
      formData: formData,
      onLoad: onload
   })
}




export default { index, clean, show, selectSmena, updateCause }