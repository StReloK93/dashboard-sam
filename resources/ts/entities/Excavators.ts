import { defineStore } from "pinia";

const useExcavators = defineStore("useExcavators", () => {
   // const excavatorStates = ref(null);
   // async function getExcavatorsStates() {
   //    const { data } = await axios.get("/api/transports/excavatorstate");
   //    // excavatorStates.value = data?.filter((car) => car.msg_dt != null)

   //    data.forEach((car) => {
   //       car.tech_name = car.tech_name
   //          .replace("HITACHI ", "")
   //          .replace("(обратная лопата)", "");
   //    });
   //    data.sort((a, b) => +a.garage - +b.garage);
   //    excavatorStates.value = data;
   // }

   // const summaExcavators = computed(() => {
   //    if (excavatorStates.value == null) return null;
   //    const activeCars = excavatorStates.value?.filter(
   //       (exv) => +exv.status == 1
   //    ).length;
   //    return {
   //       prosent: Math.round((activeCars / cars.value?.length) * 100),
   //       max: excavatorStates.value?.length,
   //       current: activeCars,
   //    };
   // });

   return {
      // summaExcavators,
      // excavatorStates,
      // timer,
   };
});
