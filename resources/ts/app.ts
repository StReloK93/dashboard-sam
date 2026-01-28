import { createPinia } from "pinia";
import { createApp } from "vue";
import router from "./app/router";
import App from "@/app/App.vue";
import TruckIcon from "@/ui/TruckIcon.vue";
import "swiper/css";
import "swiper/css/effect-cards";
import "tippy.js/dist/tippy.css";
import PreLoader from "@/ui/PreLoader.vue";
import { TippyDirective, Tippy, TippySingleton } from "tippy.vue";
import lang from "./lang";

import { AuthStore } from "./app/auth";
import axios from "axios";
import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import "@/app/App.css";

window.axios = axios;
import Highcharts from "highcharts";
import HighchartsMore from "highcharts/highcharts-more";
import HighchartsSolidGauge from "highcharts/modules/solid-gauge";
import HighchartsGantt from "highcharts/modules/gantt";
HighchartsMore(Highcharts);
HighchartsGantt(Highcharts);
HighchartsSolidGauge(Highcharts);

if (localStorage.getItem("locale")) {
   //@ts-ignore
   lang.global.locale = localStorage.getItem("locale");
}

const app = createApp(App);
app.use(lang);
app.use(createPinia());
app.directive("tippy", TippyDirective);
app.component("tippy", Tippy);
app.component("tippy-singleton", TippySingleton);
app.component("VueDatePicker", VueDatePicker);
app.component("TruckIcon", TruckIcon);
app.component("PreLoader", PreLoader);

const store = AuthStore();
async function init() {
   await store.getUser();
   app.use(router);
   app.mount("#app");
   console.log(settings);
}
init();

// 45-75
