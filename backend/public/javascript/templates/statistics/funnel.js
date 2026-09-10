function renderFunnelStatistic(data, backgroundColor, labels) {
    var config = {
        type: 'funnel',
        data: {
            datasets: [{
                data: data,
                backgroundColor: backgroundColor
            }],
            labels: labels
        },
        options: {
            responsive: false,
            sort: 'desc',
            legend: {
                display: false,
                position: 'right'
            },
            legendCallback: function (chart) {
                var text = [];
                text.push('<ul class="funnel-legend">');
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
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    };
    var ctx = document.getElementById("funnel").getContext("2d");
    window.myDoughnut = new Chart(ctx, config);
    $("#funnel-container").append(window.myDoughnut.generateLegend());
}
