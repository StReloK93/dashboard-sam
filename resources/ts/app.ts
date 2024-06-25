import { createPinia } from "pinia"
import { createApp } from "vue"
import router from "./app/router"
import App from "@/app/App.vue"
import TruckIcon from '@/ui/TruckIcon.vue'
import "swiper/css"
import "swiper/css/effect-cards"
import PreLoader from '@/components/PreLoader.vue'
import MiniPreLoader from '@/components/MiniPreLoader.vue'
console.log(settings)

import { AuthStore } from "./app/auth"
import axios from "axios"
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import "@/app/App.css"

window.axios = axios
import Highcharts from "highcharts"
import HighchartsMore from "highcharts/highcharts-more"
import HighchartsSolidGauge from "highcharts/modules/solid-gauge"
import HighchartsGantt from "highcharts/modules/gantt"
HighchartsMore(Highcharts)
HighchartsGantt(Highcharts)
HighchartsSolidGauge(Highcharts)

const app = createApp(App).use(createPinia())
app.component('VueDatePicker', VueDatePicker)
app.component('TruckIcon', TruckIcon)
app.component('PreLoader', PreLoader)
app.component('MiniPreLoader', MiniPreLoader)
const store = AuthStore()
async function init() {
    await store.getUser()
    app.use(router)
    app.mount("#app")
}
init()

// 45-75