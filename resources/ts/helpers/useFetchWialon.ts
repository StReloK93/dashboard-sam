import { ref } from 'vue';
import axios, { AxiosResponse } from 'axios'
type HttpMethod = 'get' | 'post' | 'put' | 'delete'; // Define allowed HTTP methods

const wialonHttp = axios.create({
   'baseURL': settings.API_LINK,
   'headers': { 'Content-Type': 'application/json' },
   'withCredentials': false
})
type FetchOptions = {
   url: string;
   formData?: object;
   method?: HttpMethod;
   onLoad?: Function;
};


// Добавляем `SMENA_DAY` и `SMENA_NIGHT` в каждый POST-запрос
wialonHttp.interceptors.request.use(config => {
   if (config.method === "post") {
      config.data = {
         CAREER_ID: +settings.CAREER_ID,
         SMENA_DAY: settings.SMENA_DAY_JOB,
         SMENA_NIGHT: settings.SMENA_NIGHT_JOB,
         ...config.data
      };
   }
   return config;
});


export function useFetch<T>({ url, formData = {}, method = 'get', onLoad }: FetchOptions) {
   const data = ref<T | null>(null);
   const error: any = ref(null);
   const isLoading = ref(true);
   const isFirstLoading = ref(true)
   const fetchData = async (options?: any) => {
      isLoading.value = true;
      error.value = null;

      try {
         const response: AxiosResponse = await wialonHttp[method](url, options); // No need to lowercase, method is already typed
         data.value = response.data;
      } catch (err) {
         error.value = err;
      } finally {
         isLoading.value = false;
         isFirstLoading.value = false
         if (onLoad) onLoad({data: data.value, error: error.value})
      }
   };
   fetchData(formData)
   return { data, error, isLoading, fetchData, isFirstLoading };
}