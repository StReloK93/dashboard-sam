import { secondsToFormatTime } from "@/helpers/timeFormat";

export default function (chartData) {
    return {
        chart: {
            type: "column",
            backgroundColor: "transparent",
            plotBorderWidth: 1,
            plotBorderColor: "#222",
        },
        title: {
            align: "left",
            text: null,
        },
        accessibility: {
            enabled: false,
            announceNewData: {
                enabled: true,
            },
        },
        xAxis: {
            title: {
                text: "Smenalar",
            },
            type: "category",
            gridLineWidth: 1,
            gridLineColor: "#222",

            labels: {
                style: {
                    color: '#bbb'
                }
            }
        },
        yAxis: {
            title: {
                text: null,
            },
            labels: {
                style: {
                    color: '#bbb'
                }
            },
            gridLineWidth: 1,
            gridLineColor: "#222",
        },
        legend: {
            enabled: false,
        },
        plotOptions: {
            series: {
                borderWidth: 0,
            },
        },

        tooltip: {
            headerFormat: null,
            formatter: function () {
                const selected = chartData.find((seel) => seel.name == this.key)
                return `
                    <span">O'rtacha vaqt: <b> ${secondsToFormatTime(selected.y)}</b></span><br>
                    <span>Umumiy kutilgan vaqt: <b>${secondsToFormatTime(selected.totalTime)}</b></span><br>
                    <span>Smena soni: <b> ${selected.days}</b></span>
                `;
            },
        },

        series: [
            {
                colorByPoint: true,
                data: chartData,
            },
        ],
    };
}
