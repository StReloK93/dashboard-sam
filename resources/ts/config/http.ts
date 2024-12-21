import { ref } from "vue"


export function useHttp(request) {
   const loading = ref(false)
   const response = ref(null)




   async function refresh(){
      loading.value = true
      response.value = await request()
      loading.value = false
   }
   refresh()

   return { loading, response, refresh }
} 