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
            type: "category",
        },
        yAxis: {
            title: {
                text: null,
            },
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
            headerFormat:
                '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat:
                '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>',
        },

        series: [
            {
                colorByPoint: true,
                data: chartData,
            },
        ],
    };
}
