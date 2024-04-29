import { UTCTime } from "@/helpers/timeFormat";

export function transportsTimeLine(data, yAxis, smena) {
    return {
        series: [
            {
                data: data,
            },
        ],
        accessibility: { enabled: false },
        chart: {
            backgroundColor: "transparent",
            plotBorderWidth: 1,
            plotBorderColor: "#222",
        },
        title: { text: null },
        subtitle: { text: null },
        xAxis: [
            {
                type: "datetime",
                min: UTCTime(smena.start),
                max: UTCTime(smena.end),
                opposite: false,
                grid: {
                    borderWidth: 0,
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
            categories: yAxis,
            grid: {
                borderWidth: 0,
                enabled: true,
                cellHeight: 10,
            },
            gridLineWidth: 0,
            staticScale: 25,
        },
        rangeSelector: {
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
