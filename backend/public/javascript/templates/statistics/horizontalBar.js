function renderHorizontalBarStatistic(elementId, data, backgroundColor, labels, steps, container, absValues, numberElements) {
    absValues = absValues || false;
    var ctx = document.getElementById(elementId).getContext('2d');
    var data = {
        labels: labels,
        options: {
            tooltips: {
                enabled: false
            }
        },
        datasets: [{
            data: data,
            backgroundColor: backgroundColor,
            borderWidth: 0
        }]
    };
    var options = {
        scales: {
            yAxes: [{
                ticks: {
                    mirror: true,
                    z: 100,
                    fontColor: "black",
                    padding: -5,
                    labelOffset: -17
                
                },
                barThickness: 15,
            }],
            xAxes: [{
                ticks: {
                    min: 0,
                    beginAtZero: true,
                    stepSize: steps
                }
            }]
        },
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: false
        },
        tooltips: {
            callbacks: {
                label: function(tooltipItems,) { 
                    return tooltipItems.xLabel + "/" + numberElements;
                }
            }
        },
        legendCallback: function (chart) {
            var text = [];
            text.push('<ul class="' + chart.id + '-legend">');
            text.push('</ul>');
            return text.join("");
        },
        plugins: {
            datalabels: {
                display:false
            },
        },
    };
    Chart.defaults.global.defaultFontFamily = "Lato,'Helvetica Neue',Arial,Helvetica,sans-serif";
    var bar = new Chart(ctx, {
        type: 'horizontalBar',
        data: data,
        options: options,
    });
    $("#" + container).parent().append(bar.generateLegend());
}

function getAdaptativeSize() {
    return window.screen.width < 430 ? 8 : 12;
}