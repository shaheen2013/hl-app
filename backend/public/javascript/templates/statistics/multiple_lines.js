function renderMultipleLinesStatistic(elementId, arrayData, array_labels, steps) {

    var ctx = document.getElementById(elementId).getContext('2d');

    array_datasets = [];

    arrayData.forEach(function (item) {
        array_datasets.push({
            label: item.label,
            data: item.data,
            backgroundColor: item.backgroundColor,
            borderColor: item.borderColor,
            pointBackgroundColor: item.pointBackgroundColor,
            pointRadius:item.pointRadius,
            borderDash:item.borderDash,
            borderWidth: 2,
            fill: 'origin'
        })
    });

    var data = {
        labels: array_labels,
        datasets: array_datasets
    };

    var options = {
        scales: {
            yAxes: [{
                ticks: {
                    min: 0,
                    beginAtZero: true,
                    stepSize: steps
                }
            }],
            xAxes: [{
                ticks: {
                    autoSkip: true,
                    maxTicksLimit: 24,
                },
            }],
        },
        maintainAspectRatio: false,
        legend: {
            display: false,
            position: "bottom"
        },
        plugins: {
            datalabels: {
                display: false
            },
        },
    };
    Chart.defaults.global.defaultFontFamily = "Lato,'Helvetica Neue',Arial,Helvetica,sans-serif";
    new Chart(ctx, {
        type: 'line',
        data: data,
        options: options
    });
}
