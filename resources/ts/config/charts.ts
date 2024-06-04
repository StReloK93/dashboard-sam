import { UTCTime } from "@/helpers/timeFormat";

export function transportsTimeLine(data, yAxis, smena) {
    console.log(data);
    
    return {
        series: [
            {
                data: data,
            },
        ],
        accessibility: { enabled: false },
        chart: {
            zoomType: 'x',
            backgroundColor: "transparent",
            plotBorderWidth: 1,
            plotBorderColor: "#222",
        },
        title: { text: null },
        subtitle: { text: null },
        xAxis: [
            {
                type: "datetime",
                labels: {
                    style: {
                        color: '#ccc', // Устанавливаем цвет текста по оси X
                        padding: 0,
                    },
                    x: -8,
                    align: 'left',
                    format: '{value:%H}'
                },
                min: UTCTime(smena.start),
                max: UTCTime(smena.end),
                opposite: false,
                grid: {
                    cellHeight: 20,
                    borderWidth: 0,
                    borderColor: "#222",
                },
                gridLineColor: "#222",
                gridLineWidth: 1,
            },
            {
                opposite: false,
                labels: {
                    enabled: false,
                },
                grid: {
                    borderWidth: 0,
                },
                gridLineColor: "#111",
                gridLineWidth: 1,
            },
        ],
        tooltip: { enabled: true },
        yAxis: {
            labels: {
                style: {
                    color: '#ccc', // Устанавливаем цвет текста по оси X
                    padding: 0,
                }
            },
            // visible: false,
            categories: yAxis,
            grid: {
                borderWidth: 1,
                enabled: true,
                cellHeight: 10,
                borderColor: "#222",
            },
            gridLineWidth: 1,
            gridLineColor: "#222",
            staticScale: 25,
        },
        rangeSelector: {
            selected: 1,
            allButtonsEnabled: true,
        },
        plotOptions: {
            series: {
                borderColor: null,
                borderRadius: "0%",
                connectors: {
                    dashStyle: "ShortDot",
                    lineWidth: 2,
                    radius: 1,
                    startMarker: {
                        enabled: false,
                    },
                },
                pointPadding: 0.09,
                groupPadding: 0,
                plotBorderWidth: 0,
            },
        },
    };
}
