<template>
    <main class="h-64" ref="chartLine"></main>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import Highcharts from 'highcharts'
import moment from 'moment'
import { UTCTime } from "@/helpers/timeFormat"



const startToday = moment().set({
    hour: 9,
    minute: 10,
    second: 0,
    millisecond: 0,
});
const endToday = moment().set({
    hour: 21,
    minute: 10,
    second: 0,
    millisecond: 0,
});

function getChartData() {
    axios.post('api/speeds-by-hour', { startDate: null, endDate: null }).then(({ data }) => {
        chartConstructor.value.series[0].setData(data.map((byHour) => {
            return {y: byHour.average_speed, x: UTCTime(byHour.hour)}
        }));
    })
}    


const chartLine = ref()
const chartConstructor = ref()
const chartOptions: any = {
    accessibility: {
        enabled: false
    },
    chart: {
        backgroundColor: "transparent",
        type: 'spline',
        scrollablePlotArea: {
            // minWidth: 600,
            // scrollPositionX: 1
        }
    },
    title: null,
    subtitle: null,
    xAxis: {
        // visible: false,
        gridLineColor: 'transparent',
        gridLineWidth: 0,

        lineWidth: 0,
        minorGridLineWidth: 10,

        // min: UTCTime(startToday.format('YYYY-MM-DD HH:mm')),
        // max: UTCTime(endToday.format('YYYY-MM-DD HH:mm')),
        type: 'datetime',
        labels: {
            overflow: 'justify'
        },
    },
    yAxis: {
        min: 10,
        max: 35,
        title: null,
        minorGridLineWidth: 0,
        gridLineWidth: 0,
        alternateGridColor: null,
    },
    tooltip: {
        valueSuffix: ' Km/s'
    },
    plotOptions: {
        spline: {
            lineWidth: 4,
            states: {
                hover: {
                    lineWidth: 5
                }
            },
            marker: {
                enabled: false
            },
            pointInterval: 3600000, // one hour
            pointStart: Date.UTC(2020, 3, 15, 0, 0, 0)
        }
    },
    series: [{
        name: "Tezlik smena bo'yicha",
        data: []

    }],
    navigation: {
        menuItemStyle: {
            fontSize: '10px'
        }
    }
}

onMounted(() => {
    getChartData()
    chartConstructor.value = Highcharts.chart(chartLine.value, chartOptions)

    setInterval(() => {
        getChartData()
    }, 60*1000*30)
})
</script>