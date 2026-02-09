import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { splitNumberAndText, getRandomArbitrary } from "@/helpers/timeFormat";
import { useFetch } from "@/helpers/useFetchWialon";
export const Excavators = defineStore("Excavators", () => {
   const ExcavatorList = ref<any[]>([]);

   async function getExcavatorStates() {
      useFetch({
         url: "data/excavators-state",
         method: "post",
         onLoad: handleData,
      });
   }

   async function handleData({ data }: any) {
      data.forEach((excavator) => {
         const { number, text } = splitNumberAndText(excavator.mexanizm_nomi);
         excavator.number = number;
         excavator.name = text;
      });
      const uniqueData = data.filter(
         (item, index, self) =>
            index === self.findIndex((t) => t.number === item.number),
      );

      ExcavatorList.value = uniqueData;
   }

   const informationProsent = computed(() => {
      const activeCars = ExcavatorList.value.filter(
         (car) => car.status_of == "Ishda",
      );
      return {
         prosent: Math.round(
            (activeCars.length / ExcavatorList.value?.length) * 100,
         ),
         max: ExcavatorList.value?.length,
         current: activeCars.length,
      };
   });

   return { getExcavatorStates, ExcavatorList, informationProsent };
});
