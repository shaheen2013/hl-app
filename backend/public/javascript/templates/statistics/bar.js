function renderBarStatistic(elementId, data, backgroundColor, label, labels, steps, container, absValues, decimals) {
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
            label: label,
            data: data,
            backgroundColor: backgroundColor,
            borderWidth: 0
        }]
    };
    var options = {
        scales: {
            yAxes: [{
                ticks: {
                    min: 0,
                    beginAtZero: true,
                    stepSize: steps,
                }
            }],
            xAxes: [{
                ticks : {
                    callback: function(value, index, values) {
                        return '';
                    }
                }
            }]
        },
        maintainAspectRatio: false,
        legend: {
            display: false
        },
        legendCallback: function (chart) {
            var text = [];
            text.push('<ul class="' + chart.id + '-legend">');
            for (var i = 0; i < chart.data.datasets[0].data.length; i++) {
                //
                text.push('<li><i class="ui icon circle" style="color:' + chart.data.datasets[0].backgroundColor[i] + '"></i> <span>');
                if (chart.data.labels[i]) {
                    text.push(chart.data.labels[i]);
                }
                text.push('</span></li>');
            }
            text.push('</ul>');
            return text.join("");
        },
        plugins: {
            datalabels: {
                backgroundColor: 'transparent',
                borderRadius: 4,
                color: 'black',
                font: {
                    weight: 'bold',
                    size: getAdaptativeSize(),
                },
                formatter: function (context) {
                    var multiplier = Math.pow(10, decimals || 2);
                    return (Math.round(context * multiplier ) / multiplier) + (absValues ? '' : '%');
                },
                anchor: 'start',
                clamp: true,
                align: 'start',
                offset: 2
            },
        },
    };
    Chart.defaults.global.defaultFontFamily = "Lato,'Helvetica Neue',Arial,Helvetica,sans-serif";
    var bar = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: options
    });
    $("#" + container).parent().append(bar.generateLegend());
}

function getAdaptativeSize() {
    return window.screen.width < 430 ? 8 : 12;
}