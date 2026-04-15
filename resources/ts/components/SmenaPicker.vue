<template>
   <div class="calendar-container">
      <div class="calendar-nav">
         <main>
            <button type="button" @click="changeMonth(-1)">
               <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="currentColor"
                  class="w-3 h-3"
               >
                  <path
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     d="M15 19l-7-7 7-7"
                  />
               </svg>
            </button>
            <button type="button" @click="changeMonth(1)">
               <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="currentColor"
                  class="w-3 h-3"
               >
                  <path
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     d="M9 5l7 7-7 7"
                  />
               </svg>
            </button>
            <span class="calendar-title">
               {{
                  new Date(currentYear, currentMonth).toLocaleString("ru", {
                     month: "long",
                  })
               }}
            </span>
         </main>
         <main>
            <span class="calendar-title">
               {{
                  new Date(currentYear, currentMonth).toLocaleString("ru", {
                     year: "numeric",
                  })
               }}
            </span>
            <button type="button" @click="changeYear(-1)">
               <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="currentColor"
                  class="w-3 h-3"
               >
                  <path
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     d="M15 19l-7-7 7-7"
                  />
               </svg>
            </button>
            <button type="button" @click="changeYear(1)">
               <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="currentColor"
                  class="w-3 h-3"
               >
                  <path
                     stroke-linecap="round"
                     stroke-linejoin="round"
                     d="M9 5l7 7-7 7"
                  />
               </svg>
            </button>
         </main>
      </div>
      <!-- Таблица дней -->
      <table class="calendar">
         <thead>
            <tr>
               <th v-for="day in weekDays" :key="day">{{ day }}</th>
            </tr>
         </thead>
         <tbody>
            <tr
               v-for="(week, index) in Math.ceil(days.length / 7)"
               :key="index"
            >
               <td
                  v-for="(day, i) in days.slice(index * 7, index * 7 + 7)"
                  :key="i"
               >
                  <div
                     v-if="day.day"
                     :class="{
                        'calendar-day': day.day !== 0,
                        empty: day.day === 0,
                        'selected-day': selectedDate.day === day.date,
                     }"
                  >
                     <span
                        :class="{
                           '!text-gray-600': latestSelect?.day < day.date,
                        }"
                     >
                        {{ day.day }}
                     </span>

                     <button
                        type="button"
                        :disabled="
                           latestSelect?.day < day.date || props.disabled
                        "
                        :class="[
                           {
                              '!bg-yellow-500 !text-white':
                                 selectedDate.day === day.date &&
                                 selectedDate.smena == 1,
                           },
                           {
                              'bg-yellow-500/30 !text-white': isLightSmena(
                                 day.date,
                                 1,
                              ),
                           },
                           { ' !text-gray-600': latestSelect?.day < day.date },
                           { 'cursor-wait': props.disabled },
                        ]"
                        class="text-yellow-400"
                        @click="day.day !== 0 && selectDate(day.date, 1)"
                     >
                        <svg
                           xmlns="http://www.w3.org/2000/svg"
                           fill="none"
                           viewBox="0 0 24 24"
                           stroke-width="1.5"
                           stroke="currentColor"
                           class="w-[16px]"
                        >
                           <circle
                              cx="12"
                              cy="12"
                              r="4"
                              stroke="currentColor"
                              stroke-width="1.5"
                              fill="currentColor"
                           />
                           <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"
                           />
                        </svg>
                     </button>
                     <button
                        type="button"
                        :disabled="
                           latestSelect?.day < day.date ||
                           (latestSelect?.smena == 1 &&
                              latestSelect?.day == day.date) ||
                           props.disabled
                        "
                        :class="[
                           {
                              '!bg-sky-500 !text-white':
                                 selectedDate.day === day.date &&
                                 selectedDate.smena == 2,
                           },
                           {
                              'bg-sky-500/30 !text-white': isLightSmena(
                                 day.date,
                                 2,
                              ),
                           },
                           {
                              ' !text-gray-600':
                                 latestSelect?.day < day.date ||
                                 (latestSelect?.smena == 1 &&
                                    latestSelect?.day == day.date),
                           },
                           { 'cursor-wait': props.disabled },
                        ]"
                        class="text-sky-400"
                        @click="day.day !== 0 && selectDate(day.date, 2)"
                     >
                        <svg
                           xmlns="http://www.w3.org/2000/svg"
                           fill="currentColor"
                           viewBox="0 0 24 24"
                           stroke-width="2"
                           stroke="currentColor"
                           class="w-[13px]"
                        >
                           <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z"
                           />
                        </svg>
                     </button>
                  </div>
               </td>
            </tr>
         </tbody>
      </table>
   </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from "vue";
import { getLastSmenas } from "@/helpers/timeFormat";
const emit = defineEmits(["change"]);
const props = defineProps([
   "period",
   "latestSelect",
   "lightLastSmena",
   "disabled",
   "diapazone",
]);

const currentYear = ref(
   new Date(props.period?.day || new Date()).getFullYear(),
);
const currentMonth = ref(new Date(props.period?.day || new Date()).getMonth());

const selectedDate = ref<any>({
   day: props.period?.day,
   smena: props.period?.smena,
});

const model: any = defineModel();
if (model.value) {
   selectedDate.value.day = model.value?.day;
   selectedDate.value.smena = model.value?.smena;
}

const lastLightDays: any = ref(
   props.lightLastSmena ? getLastSmenas(model.value, props.lightLastSmena) : [],
);
function isLightSmena(day: string, smena: number) {
   const isset = lastLightDays.value.find(
      (currentDay: any) => currentDay.day == day && currentDay.smena == smena,
   );
   if (isset) return true;
   else return false;
}

watch(
   () => model.value,
   (current) => {
      selectedDate.value.day = current?.day;
      selectedDate.value.smena = current?.smena;
   },
);

// Дни недели (локализация)
const weekDays = ["Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"];

// Вычисляем число дней в месяце
const daysInMonth = computed(() =>
   new Date(currentYear.value, currentMonth.value + 1, 0).getDate(),
);

// Вычисляем первый день недели
const firstDayOfMonth = computed(() => {
   const day = new Date(currentYear.value, currentMonth.value, 1).getDay();
   return day === 0 ? 6 : day - 1; // Приводим к началу с понедельника
});

// Массив всех дней месяца
const days = computed(() => {
   let daysArray: Array<{ day: number; date: string }> = [];

   // Пустые ячейки перед первым днём месяца
   for (let i = 0; i < firstDayOfMonth.value; i++) {
      daysArray.push({ day: 0, date: "" });
   }

   // Заполняем дни месяца
   for (let day = 1; day <= daysInMonth.value; day++) {
      const dateStr = `${currentYear.value}-${String(currentMonth.value + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
      daysArray.push({ day, date: dateStr });
   }

   return daysArray;
});

// Переключение месяца
const changeMonth = (delta: number) => {
   currentMonth.value += delta;
   if (currentMonth.value < 0) {
      currentMonth.value = 11;
      currentYear.value--;
   } else if (currentMonth.value > 11) {
      currentMonth.value = 0;
      currentYear.value++;
   }
};

// Переключение года
const changeYear = (delta: number) => {
   currentYear.value += delta;
};

// Выбор даты
const selectDate = (date: string, smena: number) => {
   selectedDate.value.day = date;
   selectedDate.value.smena = smena;
   model.value = selectedDate.value;

   if (props.lightLastSmena)
      lastLightDays.value = getLastSmenas(
         selectedDate.value,
         props.lightLastSmena,
      );
   emit("change", selectedDate.value);
};
</script>

<style scoped>
.calendar tbody td {
   height: 55px;
   line-height: 0px;
}

.calendar-container {
   font-family: "Franklin Gothic Medium", "Arial Narrow", Arial, sans-serif;
   display: inline-flex;
   flex-direction: column;
   align-items: center;
   background: rgb(39 39 42 / var(--tw-bg-opacity));
   border: 1px solid black;
   padding: 8px 4px;
   border-radius: 5px;
}

.calendar-nav {
   width: 100%;
   display: flex;
   align-items: center;
   justify-content: space-between;
}

.calendar-nav main {
   display: flex;
   align-items: center;
}

.calendar-nav button {
   width: 25px;
   height: 25px;
   color: white;
   cursor: pointer;
   display: inline-flex;
   align-items: center;
   justify-content: center;
   border-radius: 100%;
}

.calendar-nav button:hover {
   border: 1px solid rgb(0, 184, 184);
   background: rgb(0, 75, 75);
}

.calendar-title {
   font-weight: bold;
   font-size: 13px;
   margin: 0px 5px;
}

.calendar {
   padding: 10px;
   border-radius: 10px;
   text-align: center;
   border-collapse: collapse;
}

.calendar thead {
   padding-bottom: 10px;
   text-transform: capitalize;
   font-size: 12px;
}

.calendar thead th {
   padding-bottom: 10px;
}

.calendar-day {
   transition: 0.2s;
   display: inline-flex;
   flex-direction: column;
   align-items: center;
   justify-content: start;
   cursor: pointer;
}

.calendar-day button {
   width: 30px;
   border: 1px solid transparent;
   display: inline-flex;
   justify-content: center;
   height: 20px;
   border-radius: 3px;
}

.calendar-day button:hover {
   opacity: 0.7;
}

.calendar-day span {
   font-size: 11px;
   line-height: 1em;
   padding: 1px 0;
}

.selected-day {
   /* border: 1px solid rgb(0, 148, 148) !important; */
   overflow: hidden;
}
</style>
