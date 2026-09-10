function renderBarStatistic(elementId, data, backgroundColor, label, labels, steps, container) {
    var ctx = document.getElementById(elementId).getContext('2d');
    var data = {
        labels: labels,
        options: {
            tooltips: {
                enabled: false,
            },
        },
        datasets: [{
            label: label,
            data: data,
            backgroundColor: backgroundColor,
            borderWidth: 0,
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
                    callback: function (value, index, values) {
                        return '';
                    },
                },
            }],
        },
        maintainAspectRatio: false,
        legend: {
            display: false,
        },
        legendCallback: function (chart) {
            var text = [];
            text.push('<ul class="' + chart.id + '-legend">');
            for (var i = 0; i < chart.data.datasets[0].data.length; i ++) {
                //
                text.push('<li><i class="ui icon circle" style="color:' + chart.data.datasets[0].backgroundColor[i] + '"></i> <span>');
                if (chart.data.labels[i]) {
                    text.push(chart.data.labels[i]);
                }
                text.push('</span></li>');
            }
            text.push('</ul>');

            return text.join('');
        },
        animation: {
            duration: 0,
            onComplete: function () {
                //https://stackoverflow.com/questions/42556835/show-values-on-top-of-bars-in-chart-js/42562284
                var chartInstance = this.chart,
                ctx = chartInstance.ctx;

                ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                ctx.textAlign = 'center';
                ctx.textBaseline = 'bottom';

                this.data.datasets.forEach(function (dataset, i) {
                    var meta = chartInstance.controller.getDatasetMeta(i);
                    meta.data.forEach(function (bar, index) {
                        var data = dataset.data[index];
                        ctx.fillText(data + '%', bar._model.x, bar._model.y);
                    });
                });
            },
        },
    };
    Chart.defaults.global.defaultFontFamily = 'Lato,\'Helvetica Neue\',Arial,Helvetica,sans-serif';
    var bar = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: options,
    });
    $('#' + container).parent().append(bar.generateLegend());
}
