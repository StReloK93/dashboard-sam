<template>
   <section
      @mouseup="store.close"
      class="fixed inset-0 bg-zinc-950/70 flex justify-center items-center"
   >
      <aside
         @mouseup.stop
         class="xl:w-[1340px] xl:h-[540px] w-[768px] h-[490px] relative"
      >
         <PreLoader v-if="loading" class="neomorph bg-zinc-900 rounded-sm" />
         <Swiper
            v-else
            :initial-slide="store.mode!"
            :effect="'cards'"
            class="h-full"
            :slides-per-view="1"
            :modules="[EffectCards]"
         >
            <SwiperSlide v-for="slide in slides" class="slider-item neomorph">
               <main class="flex p-1.5 pt-0">
                  <div
                     class="relative group bg-white rounded-sm px-4 py-1 cursor-pointer"
                  >
                     <span class="text-black font-semibold">
                        {{ transport.smena.day }} - {{ transport.smena.smena }}
                     </span>
                     <div
                        class="absolute opacity-0 hidden group-hover:block group-hover:opacity-100 z-0 group-hover:z-50 transition-opacity duration-300 left-1 top-[98%]"
                     >
                        <SmenaPicker
                           @change="changeSmena"
                           :period="transport.smena"
                        />
                     </div>
                  </div>
               </main>

               <TableStates
                  :states="transport[slide.name]"
                  :headerColor="slide.color"
                  :edit="slide.name"
               />
            </SwiperSlide>
         </Swiper>
      </aside>
   </section>
</template>

<script setup lang="ts">
import SmenaPicker from "./SmenaPicker.vue";
import TableStates from "./TableStates.vue";
import { TransportStates, TransportModal } from "@/entities/transports";
import { Swiper, SwiperSlide } from "swiper/vue";
import { EffectCards } from "swiper/modules";
import { ref, onMounted, provide, reactive } from "vue";
import { useFetch } from "@/helpers/useFetchWialon";
import PreLoader from "@/ui/PreLoader.vue";
import { formatDate, getDateAndSmena } from "@/helpers/timeFormat";
import moment from "moment";
const transport: any = TransportStates();
const causes = ref([]);
const store = TransportModal();

const loading = ref(true);

const pickers = reactive(getDateAndSmena());

function changeSmena(smena: { day: string; smena: number }) {
   getData(smena);
}

const handleDate = (modelData: Date) => {
   pickers.date = moment(modelData).format(`YYYY-MM-DD`);
   getData(pickers);
};

function getData(formdata?: any) {
   loading.value = true;
   transport.getTransportState(
      store.transport!.transport_id,
      function () {
         loading.value = false;
      },
      formdata,
   );
}

const slides = [
   { name: "inExcavator", color: "bg-green-700" },
   { name: "inExcavator", color: "bg-yellow-600" },
   { name: "inOIL", color: "bg-orange-600" },
   { name: "inSmenaAll", color: "bg-indigo-600" },
   { name: "isUnknown", color: "bg-red-600" },
   { name: "inATB", color: "bg-gray-600" },
];

provide("causes", causes);
onMounted(async () => {
   getData();
   useFetch({
      url: "cause",
      onLoad: ({ data }: any) => {
         causes.value = data;
      },
   });
});
</script>
