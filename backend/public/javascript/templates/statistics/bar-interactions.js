function renderBarInteractionStatistic(elementId, data, backgroundColor, label, labels, steps, container, simple_bar, onClick) {
    var onClickEvent = onClick || null;
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
                        if (simple_bar) {
                            return value;
                        }

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
            if (simple_bar) {
                return;
            }

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
        onClick: onClickEvent,
        hover: {
            onHover: function (e) {
                if (onClickEvent) {
                    var point = this.getElementAtEvent(e);
                    e.target.style.cursor = point.length ? 'pointer' : 'default';
                }
            }
        }
    };
    Chart.defaults.global.defaultFontFamily = "Lato,'Helvetica Neue',Arial,Helvetica,sans-serif";
    var bar = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: options
    });
    $("#" + container).parent().append(bar.generateLegend());
}
