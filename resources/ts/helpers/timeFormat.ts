import moment from "moment";
import { ref } from "vue";
export function minuteFormat(minutes) {
   const hours = Math.floor(minutes / 60);
   const minFloored = Math.floor(minutes % 60);
   const min = minFloored > 9 ? minFloored : `0${minFloored}`;
   const hour = hours > 9 ? hours : `0${hours}`;
   return `${hour}:${min}`;
}

export function inZone(car, zone) {
   if (car?.geozone) {
      return car?.geozone?.toLowerCase().includes(zone.toLowerCase());
   } else return false;
}

export function inZones(car, zones: []) {
   const array = zones.map((zone) => inZone(car, zone));
   return array.some((elem) => elem == true);
}

export function timeDiff(car, type) {
   return moment(car.geozone_out).diff(car.geozone_in, type);
}

export function getDifference(car) {
   const seconds = moment(car.geozone_out).diff(car.geozone_in, "second");
   return secondsToFormatTime(seconds);
}

export function inExcavatorHelper(transport) {
   return (
      inZone(transport, "экг") ||
      inZone(transport, "ex") ||
      inZone(transport, "эг") ||
      inZone(transport, "фп") ||
      inZone(transport, "фрп") ||
      inZone(transport, "liebherr")
   );
}

export function secondsToFormatTime(seconds) {
   var houre, minute, second;
   houre = Math.floor(seconds / 3600);
   const remainderOfHour = Math.floor(seconds % 3600);
   minute = Math.floor(remainderOfHour / 60);
   second = Math.floor(remainderOfHour % 60);

   const hour = houre > 9 ? houre : `0${houre}`;
   const minut = minute > 9 ? minute : `0${minute}`;
   const secund = second > 9 ? second : `0${second}`;
   return `${hour}:${minut}:${secund}`;
}

export function secondTimer() {
   const oldTime = ref(moment());
   const timer = ref();
   setInterval(() => {
      const minuteDiff = moment().diff(oldTime.value, "minute");
      const minutes = minuteDiff % 60;
      const secundDiff = moment().diff(oldTime.value, "second");
      const seconds = secundDiff % 60;

      const secund = seconds > 9 ? seconds : `0${seconds}`;
      const minute = minutes > 9 ? minutes : `0${minutes}`;
      timer.value = `${minute}:${secund}`;
   }, 1000);

   function reset() {
      oldTime.value = moment();
   }
   return { timer, reset };
}

export function getDateAndSmena(time = undefined) {
   const current = moment(time);
   const startToday = moment(
      moment().format(`YYYY-MM-DD ${settings.day_smena}`)
   );
   const endToday = moment(
      moment().format(`YYYY-MM-DD ${settings.night_smena}`)
   );

   if (startToday < current && endToday > current) {
      return { date: current.toDate(), smena: 1 };
   } else if (endToday < current) {
      return { date: current.toDate(), smena: 2 };
   } else {
      const currentClone = current.clone();
      return { date: currentClone.subtract(1, "day").toDate(), smena: 2 };
   }
}

export function inSmenaTime(transport) {
   const dayStart = moment(moment().format(`YYYY-MM-DD ${settings.day_smena}`));
   const dayEnd = moment(
      moment().format(`YYYY-MM-DD ${settings.day_smena_job}`)
   );
   const oneFirst = moment(transport.geozone_in).isBetween(dayStart, dayEnd);
   const twoFirst = moment(transport.geozone_out).isBetween(dayStart, dayEnd);

   const nightStart = moment(
      moment().format(`YYYY-MM-DD ${settings.night_smena}`)
   );
   const nightEnd = moment(
      moment().format(`YYYY-MM-DD ${settings.night_smena_job}`)
   );
   const oneSecond = moment(transport.geozone_in).isBetween(
      nightStart,
      nightEnd
   );
   const twoSecond = moment(transport.geozone_out).isBetween(
      nightStart,
      nightEnd
   );

   return (oneFirst && twoFirst) || (oneSecond && twoSecond);
}

export function calculateDistance(lat1, lon1, lat2, lon2) {
   const R = 6371e3; // Радиус Земли в метрах
   const φ1 = (lat1 * Math.PI) / 180; // Широта первой точки в радианах
   const φ2 = (lat2 * Math.PI) / 180; // Широта второй точки в радианах
   const Δφ = ((lat2 - lat1) * Math.PI) / 180; // Разница широт
   const Δλ = ((lon2 - lon1) * Math.PI) / 180; // Разница долгот

   // Формула гаверсинусов
   const a =
      Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
      Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
   const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

   // Расстояние между точками в метрах
   const distance = R * c;

   return distance;
}

export function calculatePathLength(points) {
   let pathLength = 0;

   // Проходим по всем точкам, начиная с второй
   for (let i = 1; i < points.length; i++) {
      const prevPoint = points[i - 1];
      const currentPoint = points[i];
      const distance = calculateDistance(
         prevPoint.y,
         prevPoint.x,
         currentPoint.y,
         currentPoint.x
      );
      pathLength += distance;
   }

   return pathLength;
}

export function UTCTime<Number>(time: string) {
   const date = new Date(time);
   return Date.UTC(
      date.getFullYear(),
      date.getMonth(),
      date.getDate(),
      date.getHours(),
      date.getMinutes()
   );
}

export function formatDate(date: any) {
   if (date.constructor === Array) {
      const dayStart = date[0].getDate();
      const monthStart = date[0].getMonth() + 1;
      const yearStart = date[0].getFullYear();

      const dayEnd = date[1].getDate();
      const monthEnd = date[1].getMonth() + 1;
      const yearEnd = date[1].getFullYear();

      return `${withZero(dayStart)}-${withZero(
         monthStart
      )}-${yearStart} - ${withZero(dayEnd)}-${withZero(monthEnd)}-${yearEnd}`;
   } else {
      const day = date.getDate();
      const month = date.getMonth() + 1;
      const year = date.getFullYear();

      return ` ${withZero(day)}-${withZero(month)}-${year}`;
   }
}

export function withZero(number) {
   return number > 9 ? number : `0${number}`;
}

export function calculatePauses(points: []) {
   let stops = [];
   let stopStart = null;

   for (let i = 1; i < points.length; i++) {
      const second: any = points[i];
      const first: any = points[i - 1];
      const distance = calculateDistance(second.y, second.x, first.y, first.x);

      if (distance < 15) {
         if (stopStart == null) {
            stopStart = first.created_at;
         }
      } else {
         if (stopStart) {
            stops.push({
               start: stopStart,
               end: first.created_at,
            });
            stopStart = null;
         }
      }
   }

   return stops;
}

export function getDaysArray(startDate, endDate) {
   const start = moment(startDate);
   const end = moment(endDate);
   const daysArray = [];

   while (start.isSameOrBefore(end)) {
      daysArray.push(start.format("YYYY-MM-DD"));
      start.add(1, "days");
   }

   return daysArray;
}

export function getToName(name: any) {
   const index = name?.replace(/\D/g, "");
   const arrayNames = [
      "TO-250 (1)",
      "TO-500 (2)",
      "TO-250 (3)",
      "TO-1000 (4)",
      "TO-250 (5)",
      "TO-500 (6)",
      "TO-250 (7)",
      "TO-2000 (8)",
      "TO-250 (9)",
      "TO-500 (10)",

      "TO-250 (11)",
      "TO-3000 (12)",
      "TO-250 (13)",
      "TO-500 (14)",
      "TO-250 (15)",
      "TO-4000 (16)",
   ];

   return arrayNames[+index - 1];
}

export function splitNumberAndText(str) {
   // Используем регулярное выражение для поиска числа в начале строки
   const match = str.match(/^(\d+)(.*)$/);
   // Если число найдено, возвращаем его и остальной текст
   if (match) {
      return {
         number: parseInt(match[1], 10),
         text: match[2].trim().slice(1),
      };
   }
   // Если числа нет, возвращаем null и исходный текст
   return {
      number: null,
      text: str,
   };
}

export function calculate(selectedCars) {
   const tableData = calculateConflictTime(selectedCars);
   const confictTime = tableData?.reduce(
      (summ, car) => summ + car.difference,
      0
   );
   const summaTrackStayTime = selectedCars.reduce(
      (summ, item) =>
         summ +
         Math.abs(moment(item.geozone_in).diff(item.geozone_out, "seconds")),
      0
   );

   const latest = selectedCars.reduce((latest, item) => {
      return new Date(latest.geozone_out) > new Date(item.geozone_out)
         ? latest
         : item;
   });

   const first = selectedCars.reduce((latest, item) => {
      return new Date(latest.geozone_in) < new Date(item.geozone_in)
         ? latest
         : item;
   });

   const maxTime = Math.abs(
      moment(first.geozone_in).diff(latest.geozone_out, "seconds")
   );

   return {
      oneCarTime: summaTrackStayTime - confictTime,
      moreCarTime: confictTime,
      moreCarCount: tableData.length,
      diapazone: maxTime,
      geozoneFreeTime: maxTime - summaTrackStayTime - confictTime,
   };
}

export function calculateConflictTime(selectedCars) {
   const tableData = [];
   selectedCars.forEach((selected, index, same) => {
      selected.bool = true;

      const startDate = moment(selected.geozone_in);
      const endDate = moment(selected.geozone_out);

      const conflicts = same.filter((car) =>
         moment(car.geozone_in).isBetween(startDate, endDate)
      );

      if (conflicts.length > 0) {
         conflicts.forEach((nagliCar) => {
            const endAlso = moment(nagliCar.geozone_out).isBetween(
               startDate,
               endDate
            );

            const difference = endAlso
               ? moment(nagliCar.geozone_out).diff(
                    nagliCar.geozone_in,
                    "seconds"
                 )
               : endDate.diff(nagliCar.geozone_in, "seconds");

            tableData.push({
               name: selected.name + " va " + nagliCar.name,
               difference: difference,
               start: nagliCar.geozone_in,
               end: endAlso ? nagliCar.geozone_out : selected.geozone_out,
            });
         });
      }
   });
   return tableData;
}

export function calculateChartDataPrices(chartInformation) {
   const mileena = chartInformation.reduce((accum, item) => {
      const selected = accum.find(
         (accumChild) =>
            accumChild.name == item.smena &&
            accumChild.smenaDate == item.smenaDate
      );

      if (selected) selected.difference += item.difference;
      else
         accum.push({
            name: item.smena,
            smenaDate: item.smenaDate,
            difference: item.difference,
         });
      return accum;
   }, []);

   const groupedData = {};

   mileena.forEach((item) => {
      if (!groupedData[item.name])
         groupedData[item.name] = { totalDifference: 0, count: 0 };
      groupedData[item.name].totalDifference += item.difference;
      groupedData[item.name].count += 1;
   });

   // Создаем массив с результатами и вычисляем среднее значение
   const chartData = Object.keys(groupedData).map((name) => {
      const { totalDifference, count } = groupedData[name];
      return { name: name, y: totalDifference / count };
   });

   chartData.sort((a, b) => a.name.charCodeAt(0) - b.name.charCodeAt(0));

   return chartData;
}

export function downloadExcel(array, filename) {
   // Создаем заголовок и строки для таблицы
   const header = Object.keys(array[0]);
   const rows = array
      .map((obj) => {
         return header.map((key) => obj[key] || "").join("\t");
      })
      .join("\n");

   // Создаем содержимое файла
   const worksheet = `
       <table>
           <tr><th>${header.join("</th><th>")}</th></tr>
           ${rows
              .split("\n")
              .map(
                 (row) =>
                    `<tr><td>${row.split("\t").join("</td><td>")}</td></tr>`
              )
              .join("")}
       </table>`;

   const excelFile = `
       <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
       <head><meta charset="UTF-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Sheet1</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>
       <body>${worksheet}</body></html>`;

   const blob = new Blob([excelFile], { type: "application/vnd.ms-excel" });

   // Создаем ссылку для скачивания файла
   const link = document.createElement("a");
   const url = URL.createObjectURL(blob);
   link.setAttribute("href", url);
   link.setAttribute("download", filename);

   // Добавляем ссылку в документ и кликаем по ней для начала скачивания
   link.style.visibility = "hidden";
   document.body.appendChild(link);
   link.click();
   document.body.removeChild(link);
}

export function formatterToExcel(allData) {
   const daySmena = [];
   const groupByDate = allData.reduce((summator, item) => {
      const param = `${item.smenaDate} ${item.smena}`;

      if (summator[param]) summator[param].push(item);
      else {
         summator[param] = [item];
         daySmena.push(param);
      }
      return summator;
   }, {});

   daySmena.sort((a, b) => {
      return (
         // @ts-ignore
         new Date(a.substring(0, 10)) -
         // @ts-ignore
         new Date(b.substring(0, 10)) +
         (a.substring(11, 12) - b.substring(11, 12))
      );
   });

   const totalArray = [];
   daySmena.forEach((elem) => {
      const groupByZone = groupByDate[elem].reduce((summator, item) => {
         if (summator[item.geozone]) summator[item.geozone].push(item);
         else summator[item.geozone] = [item];
         return summator;
      }, {});

      for (const key in groupByZone) {
         const result = calculate(groupByZone[key]);
         totalArray.push({
            day: elem,
            geozone: key,
            maxTime: secondsToFormatTime(result.oneCarTime),
            waitingTime: secondsToFormatTime(result.moreCarTime),
            countTrucksInWaiting: result.moreCarCount,
         });
      }
   });
   return totalArray;
}
