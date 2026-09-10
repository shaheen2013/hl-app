function lineAndBarStatistc(elementId)
{
    var ctx = document.getElementById(elementId).getContext('2d');
    var options = {
        scales: {
            yAxes: [{
                ticks: {
                    min: 0,
                    beginAtZero: true,
                    stepSize: 1
                }
            }]
        },
        maintainAspectRatio: false,
        legend: {
            display: false,
            position: "bottom"
        }
    };
    Chart.defaults.global.defaultFontFamily = "Lato,'Helvetica Neue',Arial,Helvetica,sans-serif";
    var line_and_bar = new Chart(ctx, {
        type: 'bar',
        data: {
            datasets: [{
                label: 'Bar Dataset',
                data: [10, 20, 30, 40]
            }, {
                label: 'Line Dataset',
                data: [50, 50, 50, 50],

                // Changes this dataset to become a line
                type: 'line'
            }],
            labels: ['January', 'February', 'March', 'April']
        },
        options: options
    });
}
