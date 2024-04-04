import { createPinia } from 'pinia'
import { createApp } from "vue"
import router from './app/router'
import App from '@/app/App.vue'
import '@/app/App.css'
import 'swiper/css'
import 'swiper/css/effect-cards'
import Echo from './app/echo'
import { useAuthStore } from './app/auth'
//@ts-ignore
window.echo = Echo

import Highcharts from 'highcharts'
import HighchartsMore from 'highcharts/highcharts-more'
import HighchartsSolidGauge from 'highcharts/modules/solid-gauge'
HighchartsMore(Highcharts)
HighchartsSolidGauge(Highcharts)

const app = createApp(App)
    .use(createPinia())

const store = useAuthStore()
async function init() {
	await store.getUser()
	app.use(router)
	app.mount("#app")
}
init()

