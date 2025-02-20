import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { splitNumberAndText, getRandomArbitrary } from "@/helpers/timeFormat";

export const Excavators = defineStore("Excavators", () => {
   const ExcavatorList = ref([]);
   async function getExcavatorStates() {
      const { data } = await axios.get("api/transports/excavatorstate");
      data.forEach((excavator) => {
         const { number, text } = splitNumberAndText(excavator.mexanizm_nomi);
         excavator.number = number;
         excavator.name = text;
      });
      const uniqueData = data.filter((item, index, self) =>
         index === self.findIndex((t) => t.number === item.number)
      );

      ExcavatorList.value = uniqueData;
   }

   const informationProsent = computed(() => {
      const activeCars = ExcavatorList.value.filter((car) => car.status_of == 'Ishda')
      return { prosent: Math.round((activeCars.length / ExcavatorList.value?.length) * 100), max: ExcavatorList.value?.length, current: activeCars.length }
   });

   return { getExcavatorStates, ExcavatorList, informationProsent };
});
