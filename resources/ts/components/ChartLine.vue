<template>
    <main class="h-80" ref="chartLine"></main>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
//@ts-ignore
import Highcharts from 'highcharts'

const chartLine = ref()

const chartOptions:any = {
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
        gridLineColor: 'transparent',
        type: 'datetime',
        labels: {
            overflow: 'justify'
        }
    },
    yAxis: {
        title: null,
        minorGridLineWidth: 0,
        gridLineWidth: 0,
        alternateGridColor: null,
    },
    tooltip: {
        valueSuffix: ' m/s'
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
        name: 'Hestavollane',
        data: [5.4, 5.2, 5.7, 6.3, 5.2, 5.6, 6.1, 5.6, 5.9, 7.1, 8.6, 7.8, 8.6, 8.0, 9.7, 11.2, 12.5, 13.1, 10.6, 10.9, 8.9, 9.5, 7.5, 3.5, 4.2]

    }],
    navigation: {
        menuItemStyle: {
            fontSize: '10px'
        }
    }
}

onMounted(() => {
    Highcharts.chart(chartLine.value, chartOptions)

})
</script>