import { defineStore } from "pinia";
import { computed } from "vue";
import { Transports } from "@/entities/transports";
import { inZone, timeDiff } from "@/helpers/timeFormat";

export const useWaterTrucks = defineStore("waterTrucks", () => {
   const transports = Transports();



   const inGUSAK = computed(() => {
      const smena = transports.waterTrucks?.filter((state) => state.geozone_type == 8);
      
      const group: any = {};
      smena?.forEach((item) => {
         const zone = item.geozone;
         if (group[zone]) group[zone].cars.push(item);
         else group[zone] = { cars: [item], summTime: 0, counter: 0 };
      });

      
      transports.waterTrucks?.forEach((car) => {
         car.in_smena.forEach((truck) => {
            if (truck.geozone_type == 8) {
               const diff = timeDiff(truck, "minutes");
               const geozone = truck.geozone;

               if (group[geozone]) {
                  group[geozone].summTime += diff;
                  if (diff > 0) group[geozone].counter++;
               } else {
                  group[geozone] = {
                     cars: [],
                     summTime: diff,
                     counter: diff > 0 ? 1 : 0,
                  };
               }
            }
         });
      });

      return group;
   });



   return { inGUSAK };
});
