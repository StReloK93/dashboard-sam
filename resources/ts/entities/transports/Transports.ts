import { defineStore } from "pinia";
import { computed, ref } from "vue";
import { inZones, timeDiff, inSmenaTime } from "@/helpers/timeFormat";
import moment from "moment";
import { useTruckState } from "./truckstate/TruckStateStore";
export const Transports = defineStore("Transports", () => {
   const summaSmenaCars = ref(0);
   const summaOilCars = ref(0);

   const useTrucks = useTruckState();

   const DumpTrucks = computed(
      () =>
         useTrucks.truckStates.filter((truckState) =>
            useTrucks.groups[settings.DUMPTRUCKS]?.includes(
               truckState.transport_id,
            ),
         ) || [],
   );
   const waterTrucks = computed(
      () =>
         useTrucks.truckStates.filter((truckState) =>
            useTrucks.groups[settings.WATERTRUCKS]?.includes(
               truckState.transport_id,
            ),
         ) || [],
   );

   const statesSumm = computed(() => {
      var reysCount = 0;
      var summSmenaTime = 0;
      var summOilTime = 0;
      var summExcavatorTime = 0;

      useTrucks.truckStates?.forEach((item) => {
         const filtered = item.in_smena.filter((transport) => {
            const diffMinutes = timeDiff(transport, "minutes");

            if (
               inZones(transport, settings.smena) &&
               inSmenaTime(transport) == false
            )
               summSmenaTime += diffMinutes;
            if (inZones(transport, settings.oil)) summOilTime += diffMinutes;
            if (transport.geozone_type == 2) summExcavatorTime += diffMinutes;

            return transport.geozone_type == 2;
         });

         reysCount += filtered.length;
      });

      return { reysCount, summSmenaTime, summOilTime, summExcavatorTime };
   });

   const inProcess = computed(() => {
      const process = DumpTrucks.value?.filter(
         (transport) =>
            transport.geozone == null || transport.geozone_type == 4,
      );

      process?.forEach((item) => {
         item.timer_type = 0;
         item.timer = item.in_smena.filter(
            (transport) => transport.geozone_type == 2,
         ).length;
      });

      return process;
   });

   const isUnknown = computed(() =>
      DumpTrucks.value?.filter((car) => car.geozone == "stopped"),
   );
   const inExcavator = computed(() =>
      DumpTrucks.value?.filter(
         (car) => car.geozone_type == 2 || car.geozone_type == 3,
      ),
   );

   const inOIL = computed(() => {
      const oil = DumpTrucks.value?.filter((car) => inZones(car, settings.oil));
      const group: any = {};
      oil?.forEach((item) => {
         const zone = item.geozone;

         if (group[zone]) group[zone].cars.push(item);
         else group[zone] = { cars: [item], summTime: 0, counter: 0 };
      });

      DumpTrucks.value?.forEach((car) => {
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
      const carsATB = DumpTrucks.value?.filter((car) =>
         inZones(car, settings.uat),
      );
      carsATB?.sort(
         (a, b) =>
            time.diff(a.geozone_in, "minutes") -
            time.diff(b.geozone_in, "minutes"),
      );

      const group: any = {};

      carsATB?.forEach((car) => {
         const zone =
            car.transport?.tonnage != null ? car.transport?.tonnage : "90";
         if (group[zone]) group[zone].cars.push(car);
         else group[zone] = { cars: [car], summTime: 0, counter: 0 };
      });

      DumpTrucks.value?.forEach((car) => {
         car.in_smena.forEach((truck) => {
            if (inZones(truck, settings.uat)) {
               const diff = timeDiff(truck, "minutes");
               const geozone =
                  car.transport?.tonnage != null
                     ? car.transport?.tonnage
                     : "90";
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
      const smena = DumpTrucks.value?.filter((car) =>
         inZones(car, settings.smena),
      );

      const group: any = {};
      smena?.forEach((item) => {
         const zone = item.geozone;
         if (group[zone]) group[zone].cars.push(item);
         else group[zone] = { cars: [item], summTime: 0, counter: 0 };
      });

      DumpTrucks.value?.forEach((car) => {
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

   const inDPP = computed(() => {
      const smena = DumpTrucks.value?.filter((car) => {
         return inZones(car, settings.BASE_DPP);
      });

      const group: any = {};
      smena?.forEach((item) => {
         const zone = item.geozone;
         if (group[zone]) group[zone].cars.push(item);
         else group[zone] = { cars: [item], summTime: 0, counter: 0 };
      });

      DumpTrucks.value?.forEach((car) => {
         car.in_smena.forEach((truck) => {
            if (
               inZones(truck, settings.BASE_DPP) &&
               inSmenaTime(truck) == false
            ) {
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

   const inREMONT = computed(() => {
      const smena = DumpTrucks.value?.filter((car) =>
         inZones(car, settings.BASE_REMONT),
      );

      const group: any = {};
      smena?.forEach((item) => {
         const zone = item.geozone;
         if (group[zone]) group[zone].cars.push(item);
         else group[zone] = { cars: [item], summTime: 0, counter: 0 };
      });

      DumpTrucks.value?.forEach((car) => {
         car.in_smena.forEach((truck) => {
            if (
               inZones(truck, settings.BASE_REMONT) &&
               inSmenaTime(truck) == false
            ) {
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
      DumpTrucks.value?.forEach((car) => {
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
      const inOilAll = DumpTrucks.value?.filter((car) =>
         inZones(car, settings.oil),
      );
      if (settings.pistali_mans) {
         const onlyTrucks = DumpTrucks.value?.filter(
            (car) => car.name.toLowerCase().indexOf("man") > 0 == false,
         );
         const onlyProcess = inProcess.value?.filter(
            (car) => car.name.toLowerCase().indexOf("man") > 0 == false,
         );
         const onlyExcavator = inExcavator.value?.filter(
            (car) => car.name.toLowerCase().indexOf("man") > 0 == false,
         );
         const onlyOil = inOilAll.filter(
            (car) => car.name.toLowerCase().indexOf("man") > 0 == false,
         );

         const activeCars =
            onlyProcess?.length + onlyExcavator?.length + onlyOil?.length;

         return {
            prosent: Math.round((activeCars / onlyTrucks?.length) * 100),
            max: onlyTrucks?.length,
            current: activeCars,
         };
      } else {
         const activeCars =
            inProcess.value?.length +
            inExcavator.value?.length +
            inOilAll.length;

         return {
            prosent: Math.round((activeCars / DumpTrucks.value?.length) * 100),
            max: DumpTrucks.value?.length,
            current: activeCars,
         };
      }
   });

   const summaMans = computed(() => {
      const inOilAll = DumpTrucks.value?.filter((car) =>
         inZones(car, settings.oil),
      );
      const onlyTrucks = DumpTrucks.value?.filter(
         (car) => car.name.toLowerCase().indexOf("man") > 0,
      );

      const onlyProcess = inProcess.value?.filter(
         (car) => car.name.toLowerCase().indexOf("man") > 0,
      );
      const onlyExcavator = inExcavator.value?.filter(
         (car) => car.name.toLowerCase().indexOf("man") > 0,
      );
      const onlyOil = inOilAll.filter(
         (car) => car.name.toLowerCase().indexOf("man") > 0,
      );

      const activeCars =
         onlyProcess?.length + onlyExcavator?.length + onlyOil?.length;

      return {
         prosent: Math.round((activeCars / onlyTrucks?.length) * 100),
         max: onlyTrucks?.length,
         current: activeCars,
      };
   });

   return {
      getFilterGroup,
      summaTransports,
      summaMans,
      waterTrucks,
      inOIL,
      inSMENA,
      inExcavator,
      isUnknown,
      inProcess,
      statesSumm,
      DumpTrucks,
      summaSmenaCars,
      summaOilCars,
      inATBGroup,
      inREMONT,
      inDPP,
   };
});

// 8158
