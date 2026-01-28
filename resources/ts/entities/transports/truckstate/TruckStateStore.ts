import { defineStore } from "pinia";
import TruckStateRepository from "./TruckStateRepository";
import { computed, ref, Ref } from "vue";
import moment from "moment";

export const useTruckState = defineStore("counter", () => {
   const trucks = [];
   trucks.push(settings.DUMPTRUCKS);
   if (settings.WATERTRUCKS) trucks.push(settings.WATERTRUCKS);
   const formData = { group_ids: trucks };

   const firstLoadPage: Ref<boolean> = ref(true);

   const { data, isLoading, fetchData, isFirstLoading } =
      TruckStateRepository.index(formData, () => (firstLoadPage.value = false));

   const groups = computed(() => data.value?.groups);

   const truckStates = computed(() => {
      if (data.value == undefined) return [];
      const copyArray = JSON.parse(JSON.stringify(data.value?.states));

      copyArray?.forEach((item: any) => {
         const time = moment();
         const diffMinutes = time.diff(item.geozone_in, "minutes");
         if (diffMinutes > 1439) {
            item.timer = time.diff(item.geozone_in, "days");
            item.timer_type = 2;
         } else if (diffMinutes > 59) {
            item.timer = time.diff(item.geozone_in, "hours");
            item.timer_type = 1;
         } else {
            item.timer = diffMinutes;
            item.timer_type = 0;
         }
      });

      return copyArray;
   });

   return {
      data,
      firstLoadPage,
      truckStates,
      isLoading,
      isFirstLoading,
      fetchData: () => fetchData(formData),
      groups,
   };
});
