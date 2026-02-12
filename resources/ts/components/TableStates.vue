<template>
   <section class="h-full w-full overflow-y-auto px-1.5 scroll">
      <table class="w-full xl:text-base text-xs">
         <tr class="border-b-4 border-zinc-900">
            <td :class="props.headerColor" class="py-1 px-2 w-10">№</td>
            <td :class="props.headerColor" class="py-1 px-2 w-28">
               {{ $t("transport") }}
            </td>
            <td :class="props.headerColor" class="py-1 px-2 max-w-60">
               {{ $t("geozone") }}
            </td>
            <td :class="props.headerColor" class="py-1 px-2 w-36">
               {{ $t("timein") }}
            </td>
            <td :class="props.headerColor" class="py-1 px-2 w-36">
               {{ $t("timeout") }}
            </td>
            <td :class="props.headerColor" class="py-1 px-2 w-20">
               {{ $t("timeall") }}
            </td>

            <td
               :class="props.headerColor"
               class="py-1 px-2 max-w-56 w-56"
               v-if="['inSmenaAll', 'inATB'].includes(props.edit)"
            >
               {{ $t("cause") }}
            </td>

            <td
               :class="props.headerColor"
               class="py-1 px-2 max-w-56"
               v-if="['inSmenaAll', 'inATB'].includes(props.edit)"
            >
               {{ $t("time") }}
            </td>
         </tr>
         <tr
            v-for="(transport, index) in states"
            class="bg-zinc-800 border-y-4 border-zinc-900"
         >
            <td class="py-1 px-2 w-10">{{ (index as number) + 1 }}</td>
            <td class="py-1 px-2 w-28">{{ transport.transport.name }}</td>
            <td class="py-1 px-2 max-w-60">
               {{ transport.geozone != "stopped" ? transport.geozone : "---" }}
            </td>
            <td class="py-1 px-2 w-36">
               {{ moment(transport.geozone_in).format("YYYY-MM-DD HH:mm") }}
            </td>
            <td class="py-1 px-2 w-36">
               {{ moment(transport.geozone_out).format("YYYY-MM-DD HH:mm") }}
            </td>
            <td class="py-1 px-2 w-20">
               {{ getDifference(transport) }}
            </td>

            <td
               class="py-1 px-2 max-w-56 w-56"
               v-if="['inSmenaAll', 'inATB'].includes(props.edit)"
            >
               <EditTransportCause
                  :transport="transport"
                  :causes="causes"
                  :type="props.edit"
               />
            </td>
            <td
               class="py-1 px-2 max-w-56 w-56"
               v-if="['inSmenaAll', 'inATB'].includes(props.edit)"
            >
               <form
                  @submit.prevent="timeChange($event, transport)"
                  class="flex items-center justify-between"
               >
                  <input
                     :disabled="
                        [false, null, undefined].includes(transport.edit_time)
                     "
                     name="time"
                     :value="transport.time"
                     type="datetime-local"
                     class="disabled:bg-transparent bg-zinc-900 flex-grow rounded text-white focus:outline w-24"
                  />
                  <main v-if="transport.edit_time == true" class="flex gap-2">
                     <button
                        @click="transport.edit_time = false"
                        type="button"
                        class="min-w-10 h-10 py-0.5 text-indigo-500 hover:text-indigo-700 active:text-indigo-400"
                     >
                        <i class="fa fa-close"> </i>
                     </button>
                     <button
                        type="submit"
                        class="min-w-10 h-10 py-0.5 text-indigo-500 hover:text-indigo-700 active:text-indigo-400"
                     >
                        <i class="fa fa-save"> </i>
                     </button>
                  </main>
                  <button
                     v-else-if="
                        (auth.user?.level == 1 && props.edit == 'inSmenaAll') ||
                        (auth.user?.level == 0 && props.edit == 'inATB')
                     "
                     @click="transport.edit_time = true"
                     type="button"
                     class="min-w-10 h-10 py-0.5 text-indigo-500 hover:text-indigo-700 active:text-indigo-400"
                  >
                     <i class="fa-solid fa-pen-nib"></i>
                  </button>
               </form>
            </td>
         </tr>
      </table>
   </section>
</template>

<script setup lang="ts">
import { useTruckState } from "@/entities/transports/truckstate/TruckStateStore";
import EditTransportCause from "./EditTransportCause.vue";
import moment from "moment";
import { getDifference } from "@/helpers/timeFormat";

import TruckStateRepository from "@/entities/transports/truckstate/TruckStateRepository";
import { inject } from "vue";
import { AuthStore } from "@/app/auth";
const auth = AuthStore();
const props = defineProps(["edit", "headerColor", "states"]);
const causes = inject("causes");

const truckStateStore = useTruckState();

function timeChange(event: Event, transport_state: any) {
   const form = event.target as HTMLFormElement;
   const formData = new FormData(form);

   const timeValue = formData.get("time") as string;

   TruckStateRepository.saveTime({
      transport_state_id: transport_state.id,
      time: timeValue,
   });

   const currentstate = truckStateStore.data?.states.find(
      (state) => state.id == transport_state.id,
   );

   if (currentstate) {
      currentstate.time = timeValue;
   }

   transport_state.time = timeValue;
   transport_state.edit_time = false;
}
</script>
