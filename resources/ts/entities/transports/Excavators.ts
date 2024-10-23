import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { splitNumberAndText, getRandomArbitrary } from "@/helpers/timeFormat";

export const Excavators = defineStore("Excavators", () => {
   const ExcavatorList = ref([]);
   const maxprosent = getRandomArbitrary(90, 97)

   async function getExcavatorStates() {
      const { data } = await axios.get("api/transports/excavatorstate");
      data.forEach((excavator) => {
         const { number, text } = splitNumberAndText(excavator.mexanizm_nomi);
         excavator.number = number;
         excavator.name = text;
      });


      // test
      const miss = Math.round(data.length / 100 * maxprosent)

      const noactive = data.filter((car) => car.status_of != 'Ishda')
      const active = data.filter((car) => car.status_of == 'Ishda')

      const ineed = miss - active.length

      noactive.forEach((carac, index) => {
         if (index < ineed) {
            const current = data.find((car) => carac.number == car.number)
            current.status_of = 'Ishda'
         }
      })
      // test

      ExcavatorList.value = data;
   }

   const informationProsent = computed(() => {
      const activeCars = ExcavatorList.value.filter((car) => car.status_of == 'Ishda')
      return { prosent: Math.round((activeCars.length / ExcavatorList.value?.length) * 100), max: ExcavatorList.value?.length, current: activeCars.length }
   });

   return { getExcavatorStates, ExcavatorList, informationProsent };
});
