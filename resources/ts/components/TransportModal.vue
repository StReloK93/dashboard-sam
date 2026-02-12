<template>
   <section
      @mouseup="store.close"
      class="fixed inset-0 bg-zinc-950/70 flex justify-center items-center"
   >
      <aside
         @mouseup.stop
         class="xl:w-[1280px] xl:h-[540px] w-[768px] h-[490px] relative"
      >
         <h3 class="flex gap-1 px-1.5 font-semibold xl:h-9 h-7 mb-2">
            <div class="w-40 xl:h-9 h-7">
               <VueDatePicker
                  @update:model-value="handleDate"
                  v-model="pickers.date"
                  :format="formatDate"
                  auto-apply
                  placeholder="Kunni tanlang"
               />
            </div>
            <div class="flex gap-1">
               <button
                  @click="changeSmena(1)"
                  :class="setColor(pickers.smena == 1)"
                  class="xl:w-24 w-20 rounded shadow xl:text-base text-xs"
               >
                  1 - {{ $t("change") }}
               </button>
               <button
                  @click="changeSmena(2)"
                  :class="setColor(pickers.smena == 2)"
                  class="xl:w-24 w-20 rounded shadow xl:text-base text-xs"
               >
                  2 - {{ $t("change") }}
               </button>
            </div>
         </h3>
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

function setColor(boolean: boolean) {
   if (boolean) return `bg-teal-600 text-white`;
   else return "bg-white text-gray-600";
}

const pickers = reactive(getDateAndSmena());

function changeSmena(smena: number) {
   pickers.smena = smena;
   getData(pickers);
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
