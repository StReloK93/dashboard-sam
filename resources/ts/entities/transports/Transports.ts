import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { inZones, timeDiff, inExcavatorHelper, inSmenaTime, calculatePathLength } from "@/helpers/timeFormat";
import axios from "axios";
import moment from "moment";

export const Transports = defineStore("Transports", () => {

   const cars = ref([]);
   const summaSmenaCars = ref(0);
   const summaOilCars = ref(0);
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

      const excludedWords = ['гм', 'вод', "по"]

      const withoutGM = data.filter(item => {
         const lowerName = item.name.toLowerCase();
         return !excludedWords.some(word => lowerName.includes(word));
      });
      // waterTrucks.value = data.filter((car) => car.name.includes('ГМ'))
      withoutGM.sort(
         (a, b) => +a.name.replace(/\D/g, "") - +b.name.replace(/\D/g, "")
      );

      waterTrucks.value = data.filter(item => {
         const lowerName = item.name.toLowerCase();
         return excludedWords.some(word => lowerName.includes(word));
      });

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
               inZones(transport, settings.smena) &&
               inSmenaTime(transport) == false
            )
               summSmenaTime += diffMinutes;
            if (inZones(transport, settings.oil)) summOilTime += diffMinutes;
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
      return cars.value?.filter((car) => calculatePathLength(car.tracks) < 30 && car.geozone == null);
   });

   const inATB = computed(() => cars.value?.filter((car) => inZones(car, settings.uat)));

   const inOilAll = computed(() =>
      cars.value?.filter((car) => inZones(car, settings.oil))
   );
   const inSmenaAll = computed(() =>
      cars.value?.filter((car) => inZones(car, settings.smena))
   );
   const inExcavator = computed(() =>
      cars.value?.filter((car) => inExcavatorHelper(car))
   );

   const inOilConflict = computed(() => {
      const allCars = [];
      cars.value?.forEach((car) => {
         car.in_smena.forEach((truck) => {
            if (
               (inZones(truck, settings.oil.concat(settings.smena))) && timeDiff(truck, "minutes") > 0
            )
               allCars.push(truck);
         });
      });

      // @ts-ignore
      allCars.sort((a, b) => new Date(a.geozone_in) - new Date(b.geozone_in));
      return allCars;
   });

   const inOIL = computed(() => {
      const oil = cars.value?.filter((car) => inZones(car, settings.oil));
      const group: any = {};
      oil?.forEach((item) => {
         const zone = item.geozone;

         if (group[zone]) group[zone].cars.push(item);
         else group[zone] = { cars: [item], summTime: 0, counter: 0 };
      });

      cars.value?.forEach((car) => {
         car.in_smena.forEach((truck) => {
            if (inZones(truck, settings.oil)) {
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


   const inATBGroup = computed(() => {
      const time = moment();
      const carsATB = cars.value?.filter((car) => inZones(car, settings.uat))
      carsATB.sort(
         (a, b) => time.diff(a.geozone_in, "minutes") - time.diff(b.geozone_in, "minutes")
      );

      const group: any = {};

      carsATB?.forEach((car) => {
         const zone = car.truck != null ? car.truck?.type : '90';

         if (group[zone]) group[zone].cars.push(car);
         else group[zone] = { cars: [car], summTime: 0, counter: 0 };
      });

      cars.value?.forEach((car) => {
         car.in_smena.forEach((truck) => {
            if (inZones(truck, settings.uat)) {
               const diff = timeDiff(truck, "minutes");
               const geozone = car.truck != null ? car.truck?.type : '90';
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
      const smena = cars.value?.filter((car) => inZones(car, settings.smena));

      const group: any = {};
      smena?.forEach((item) => {
         const zone = item.geozone;
         if (group[zone]) group[zone].cars.push(item);
         else group[zone] = { cars: [item], summTime: 0, counter: 0 };
      });

      cars.value?.forEach((car) => {
         car.in_smena.forEach((truck) => {
            if (inZones(truck, settings.smena) && inSmenaTime(truck) == false) {
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
      if (settings.pistali_mans) {
         const onlyTrucks = cars.value?.filter((car) => car.name.toLowerCase().indexOf("man") > 0 == false)

         const onlyProcess = inProcess.value?.filter((car) => car.name.toLowerCase().indexOf("man") > 0 == false)
         const onlyExcavator = inExcavator.value?.filter((car) => car.name.toLowerCase().indexOf("man") > 0 == false)
         const onlyOil = inOilAll.value?.filter((car) => car.name.toLowerCase().indexOf("man") > 0 == false)

         const activeCars = onlyProcess?.length + onlyExcavator?.length + onlyOil?.length

         return { prosent: Math.round((activeCars / onlyTrucks?.length) * 100), max: onlyTrucks?.length, current: activeCars }
      }
      else {
         const activeCars =
            inProcess.value?.length +
            inExcavator.value?.length +
            inOilAll.value?.length;
         return { prosent: Math.round((activeCars / cars.value?.length) * 100), max: cars.value?.length, current: activeCars }
      }
   });

   const summaMans = computed(() => {
      const onlyTrucks = cars.value?.filter((car) => car.name.toLowerCase().indexOf("man") > 0)

      const onlyProcess = inProcess.value?.filter((car) => car.name.toLowerCase().indexOf("man") > 0)
      const onlyExcavator = inExcavator.value?.filter((car) => car.name.toLowerCase().indexOf("man") > 0)
      const onlyOil = inOilAll.value?.filter((car) => car.name.toLowerCase().indexOf("man") > 0)

      const activeCars = onlyProcess?.length + onlyExcavator?.length + onlyOil?.length

      return { prosent: Math.round((activeCars / onlyTrucks?.length) * 100), max: onlyTrucks?.length, current: activeCars }
   });


   return {
      getTransports,
      getFilterGroup,
      summaTransports,
      summaMans,
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
      inATBGroup
   };
});

// 8158
