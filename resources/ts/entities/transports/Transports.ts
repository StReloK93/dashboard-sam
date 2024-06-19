import { defineStore } from "pinia";
import { AuthStore } from "@/app/auth"
import { computed, ref } from "vue";
import { inZone, timeDiff, inExcavatorHelper, inSmenaTime, calculatePathLength , peresmenka } from "@/helpers/timeFormat";
import axios from "axios";
import moment from "moment";

export const Transports = defineStore("Transports", () => {
   const cars = ref([]);
   const summaSmenaCars = ref(0);
   const summaOilCars = ref(0);
   const auth = AuthStore()
   const waterTrucks = ref()
   

   async function getTransports() {
      const { data } = await axios.get("/api/transportstates");

      data.forEach((item) => {
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

      const withoutGM = data.filter((car) => car.name.includes('ГМ') == false)
      waterTrucks.value = data.filter((car) => car.name.includes('ГМ'))
      
      withoutGM.sort(
         (a, b) => +a.name.replace(/\D/g, "") - +b.name.replace(/\D/g, "")
      );

      cars.value = withoutGM;
   }

   const statesSumm = computed(() => {
      var reysCount = 0;
      var summSmenaTime = 0;
      var summOilTime = 0;
      var summExcavatorTime = 0;

      cars.value?.forEach((item) => {
         const filtered = item.in_smena.filter((transport) => {
            if (timeDiff(transport, "seconds") < 120) return false;
            const diffMinutes = timeDiff(transport, "minutes");

            if (
               inZone(transport, auth.information?.smena) &&
               inSmenaTime(transport) == false
            )
               summSmenaTime += diffMinutes;
            if (inZone(transport, auth.information?.oil)) summOilTime += diffMinutes;
            if (inExcavatorHelper(transport)) summExcavatorTime += diffMinutes;

            return inExcavatorHelper(transport)
         });

         reysCount += filtered.length;
      });

      return { reysCount, summSmenaTime, summOilTime, summExcavatorTime };
   });

   const inProcess = computed(() => {
      const process = cars.value?.filter((transport) => {
         const distance = calculatePathLength(transport.tracks);
         return distance >= 30 && transport.geozone == null;
      });

      process?.forEach((item) => {
         item.timer_type = 0;
         const filtered = item.in_smena.filter((transport) => {
            if (timeDiff(transport, "seconds") < 120) return false;
            return inExcavatorHelper(transport)
         });
         item.reys = filtered.length;
      });

      return process;
   });

   const isUnknown = computed(() => {
      return cars.value?.filter((car) => {
         const distance = calculatePathLength(car.tracks);
         return distance < 30 && car.geozone == null;
      });
   });

   const inATB = computed(() => cars.value?.filter((car) => inZone(car, auth.information?.uat) || inZone(car, "авто")));

   const inOilAll = computed(() =>
      cars.value?.filter((car) => inZone(car, auth.information?.oil))
   );
   const inSmenaAll = computed(() =>
      cars.value?.filter((car) => inZone(car, auth.information?.smena))
   );
   const inExcavator = computed(() =>
      cars.value?.filter(
         (car) => inExcavatorHelper(car)
      )
   );

   const inOilConflict = computed(() => {
      const allCars = [];
      cars.value?.forEach((car) => {
         car.in_smena.forEach((truck) => {
            if (
               (inZone(truck, auth.information?.oil) || inZone(truck, auth.information?.smena)) &&
               timeDiff(truck, "minutes") > 0
            )
               allCars.push(truck);
         });
      });

      // @ts-ignore
      allCars.sort((a, b) => new Date(a.geozone_in) - new Date(b.geozone_in));
      return allCars;
   });

   const inOIL = computed(() => {
      const oil = cars.value?.filter((car) => inZone(car, auth.information?.oil));
      const group: any = {};
      oil?.forEach((item) => {
         const zone = item.geozone;

         if (group[zone]) group[zone].cars.push(item);
         else group[zone] = { cars: [item], summTime: 0, counter: 0 };
      });

      cars.value?.forEach((car) => {
         car.in_smena.forEach((truck) => {
            if (inZone(truck, auth.information?.oil)) {
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

      var summa = 0;
      for (const iterator in group) {
         summa += group[iterator].counter;
      }
      summaOilCars.value = summa;

      return group;
   });

   const inSMENA = computed(() => {
      const smena = cars.value?.filter((car) => inZone(car, auth.information?.smena));

      const group: any = {};
      smena?.forEach((item) => {
         const zone = item.geozone;
         if (group[zone]) group[zone].cars.push(item);
         else group[zone] = { cars: [item], summTime: 0, counter: 0 };
      });

      cars.value?.forEach((car) => {
         car.in_smena.forEach((truck) => {
            if (inZone(truck, auth.information?.smena) && peresmenka(truck) == false) {
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

      var summa = 0;
      for (const iterator in group) {
         summa += group[iterator].counter;
      }
      summaSmenaCars.value = summa;
      return group;
   });

   function getFilterGroup(key, type) {
      const group: any = [];
      cars.value?.forEach((car) => {
         car.in_smena.forEach((truck) => {
            if (truck.geozone == key && timeDiff(truck, "minutes") > 0) {
               if (type == "indigo" && inSmenaTime(truck)) return;
               group.push(truck);
            }
         });
      });
      return group;
   }

   const summaTransports = computed(() => {
      const activeCars =
         inProcess.value?.length +
         inExcavator.value?.length +
         inOilAll.value?.length;
      return { prosent: Math.round((activeCars / cars.value?.length) * 100), max: cars.value?.length, current: activeCars }
   });



   return {
      getTransports,
      getFilterGroup,
      summaTransports,
      inOilConflict,
      waterTrucks,
      inATB,
      inOIL,
      inOilAll,
      inSMENA,
      inExcavator,
      isUnknown,
      inSmenaAll,
      inProcess,
      statesSumm,
      cars,
      summaSmenaCars,
      summaOilCars,
   };
});

// 8158
