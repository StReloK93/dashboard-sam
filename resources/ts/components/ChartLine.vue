<template>
    <main class="h-64" ref="chartLine"></main>
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue'
import Highcharts from 'highcharts'
import { UTCTime } from "@/helpers/timeFormat"
import { useI18n } from 'vue-i18n'

const { t, locale } = useI18n({ useScope: 'global' })
function getChartData() {
    // axios.post('api/speeds-by-hour', { startDate: null, endDate: null }).then(({ data }) => {
    //     chartConstructor.value.series[0].setData(data.map((byHour) => {
    //         return { y: byHour.average_speed, x: UTCTime(byHour.hour) }
    //     }));
    // })
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
        type: 'datetime',
        labels: {
            overflow: 'justify',

        },
    },
    yAxis: {
        labels: {
            style: {
                color: '#bbb'
            }
        },
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
        name:  t('chartspeed'),
        data: [],
        label: {
            enabled: false,
            color: 'red'
        }
    }],
    legend: {
        labelFormatter: function() {
            return '<span style="color: #2caffe;">' + this.name + '</span>';
        }
    },
    navigation: {
        menuItemStyle: {
            fontSize: '10px'
        }
    }
}

watch(() => locale.value, () => {
	chartConstructor.value.series[0].update({
		name: t('chartspeed')
	});
})

onMounted(() => {
    getChartData()
    chartConstructor.value = Highcharts.chart(chartLine.value, chartOptions)

    setInterval(() => {
        getChartData()
    }, 60 * 1000 * 30)
})
</script>