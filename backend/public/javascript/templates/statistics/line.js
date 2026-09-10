function renderLineStatistic(elementId, data, backgroundColor, label, labels, steps) {
    var ctx = document.getElementById(elementId).getContext('2d');
    var data = {
        labels: labels,
        datasets: [{
            label: label,
            data: data,
            backgroundColor: backgroundColor,
            borderColor: 'rgba(0, 0, 0, 1)',
            pointBackgroundColor: 'rgba(0, 0, 0, 1)',
            borderWidth: 2,
            fill: 'origin',
        }],
    };
    var options = {
        scales: {
            yAxes: [{
                ticks: {
                    min: 0,
                    beginAtZero: true,
                    stepSize: steps,
                },
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
            position: 'bottom',
        },
        plugins: {
            datalabels: {
                display: false
            },
        },
        responsive: true,
    };
    Chart.defaults.global.defaultFontFamily = 'Lato,\'Helvetica Neue\',Arial,Helvetica,sans-serif';
    new Chart(ctx, {
        type: 'line',
        data: data,
        options: options,
    });
}
