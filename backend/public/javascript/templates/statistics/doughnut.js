function renderDoughnutStatistic(elementId, data, backgroundColor, labels, container, absValues, showLegend) {
    absValues = absValues || false;
    showLegend = showLegend || false;
    var hoverColors = [
        'rgba(0, 0, 0, .6)',
        'rgba(163, 230, 53, .6)',
        'rgba(56, 247, 234, .6)',
        'rgba(113, 90, 255, .6)',
        'rgba(0, 0, 0, .6)',
        'rgba(0, 0, 0, .4)',
        'rgba(0, 0, 0, .2)',
    ];

    var ctx = document.getElementById(elementId);
    var data = {
        datasets: [{
            data: data,
            backgroundColor: backgroundColor,
            borderWidth: 0,
            hoverBackgroundColor: hoverColors,
        }],
        labels: labels
    };
    var options = {
        'cutoutPercentage': showLegend ? 55 : 70,
        tooltips: {
            callbacks: {
                label: function (item, data) {
                    var datasetLabel = data.labels[item.index] || '';
                    return datasetLabel;
                },

            },
        },
        legend: {
            display: false,
            position: 'bottom',
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
        plugins: {
            datalabels: {
                backgroundColor: 'transparent',
                color: 'white',
                font: {
                    weight: 'bold',
                },
                formatter: function (context) {
                    return showLegend ? ((Math.round(context * 100) / 100) + (absValues ? '' : '%')) : '';
                },
            },
        },
    };
    var doughnut = new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: options,
    });
    $('#' + container).parent().append(doughnut.generateLegend());
}
