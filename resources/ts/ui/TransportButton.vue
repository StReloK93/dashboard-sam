<template>
   <button
      :class="[
         'inline-flex justify-between items-center rounded-2xl transition-all relative',
         '2xl:w-[80px] xl:w-[65px] w-[54px]',
         '2xl:px-1 xl:px-1 2xl:py-0.5 xl:py-0.5 px-1 py-0.5',
         'neomorph bg-zinc-800 hover:bg-zinc-900 active:shadow-md',
         { 'opacity-0 cursor-default': !hasTransport },
      ]"
   >
      <div
         :class="[
            'font-semibold text-left leading-[10px]',
            '2xl:pl-1 xl:pl-0.5 2xl:text-[14px] xl:text-[11px] text-[10px]',
            isMan ? 'text-sky-400' : buttonColor.text,
            { 'opacity-0': !hasTransport },
         ]"
      >
         <span class="xl:leading-3 leading-[10px]">
            {{ formattedName }}
         </span>

         <div class="xl:leading-3 leading-[8px] xl:ml-0.5">
            <span
               class="text-gray-500 2xl:text-[10px] xl:text-[9px] text-[8px]"
            >
               {{ state?.transport?.tonnage }}
            </span>
         </div>
      </div>
      <span
         v-if="state?.timer_type"
         :class="[
            colorLine,
            'absolute z-10',
            moment(state?.time) < moment() ? 'bg-rose-500' : '',
         ]"
         class="lg:w-2 w-[6px] h-[calc(100%+1px)] 2xl:right-[12px] xl:right-[11px] right-[10px]"
      ></span>
      <time
         :class="[
            'inline-flex justify-center items-center z-20 rounded-full font-bold text-zinc-900',
            '2xl:w-6 2xl:h-6 xl:w-[22px] xl:h-[22px] lg:w-[20px] lg:h-[20px] w-[17px] h-[17px]',
            '2xl:text-xs xl:text-[11px] text-[10px]',
            isMan ? 'bg-sky-400' : buttonColor.bg,
            moment(state?.time) < moment() ? 'bg-rose-500' : '',
         ]"
      >
         {{ state?.timer ?? 0 }}
      </time>
   </button>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { ITruckState } from "@/interfaces";
import moment from "moment";

// Ranglar konfiguratsiyasini tashqariga chiqaramiz
const COLORS_MAP: Record<string, { bg: string; text: string; border: string }> =
   {
      sky: { bg: "bg-sky-400", text: "text-sky-400", border: "border-sky-400" },
      green: {
         bg: "bg-green-500",
         text: "text-green-400",
         border: "border-green-400",
      },
      red: { bg: "bg-red-500", text: "text-red-500", border: "border-red-500" },
      yellow: {
         bg: "bg-yellow-500",
         text: "text-yellow-500",
         border: "border-yellow-500",
      },
      orange: {
         bg: "bg-orange-500",
         text: "text-orange-500",
         border: "border-orange-500",
      },
      gray: {
         bg: "bg-gray-500",
         text: "text-gray-500",
         border: "border-gray-500",
      },
      indigo: {
         bg: "bg-indigo-400",
         text: "text-indigo-400",
         border: "border-indigo-500",
      },
   };

const props = defineProps<{
   state: ITruckState | null;
   color: string;
}>();

const hasTransport = computed(() => !!props.state?.transport?.name);

const isMan = computed(
   () => props.state?.transport?.name?.toLowerCase().includes("man") ?? false,
);

const buttonColor = computed(() => COLORS_MAP[props.color] || COLORS_MAP.gray);

const colorLine = computed(() =>
   props.state?.timer_type === 2 ? "bg-gray-400" : "bg-yellow-400",
);

// Nomni formatlash mantiqi
const formattedName = computed(() => {
   const name = props.state?.transport?.name;
   if (!name) return "";

   // 1. Sky color holati (GM + raqamlar)
   if (props.color === "sky") {
      return `ГМ${name.replace(/\D/g, "")}`;
   }

   // 2. Pistali (7:50) holati yoki standart
   // settings.day_smena global bo'lsa, uni computed ichida tekshiramiz
   // @ts-ignore (agar settings global bo'lsa)
   const isPistali =
      typeof settings !== "undefined" && settings.day_smena === "07:50";

   if (isPistali) {
      const prefix = isMan.value ? "M" : "C";
      const match = name
         .replace(/\s/g, "")
         .replace(/\(.*?\)/g, "")
         .match(/№(\d+)/);
      return match ? prefix + match[1] : prefix + name.replace(/\D/g, "");
   }

   // 3. Standart holat (C + raqamlar)
   return `C${name.replace(/\D/g, "")}`;
});
</script>
