jQuery(function ($) {
    if ($('.wpbs-chart').length) {
        $('.wpbs-chart').each(function () {

            var $chart = $(this);

            var chart_data = $chart.data('chart');
            var ctx = $chart.get(0).getContext('2d');
            new Chart(ctx, {
                type: 'line',
                maintainAspectRatio: false,
                data: chart_data,
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function (tooltipItem, data) {
                                var label = data.datasets[tooltipItem.datasetIndex].label || '';

                                if (label) {
                                    label += ': ';
                                }
                                label += Math.round(tooltipItem.yLabel * 100) / 100;

                                if ($chart.data('currency')) {
                                    label += ' ' + $chart.data('currency');
                                }
                                return label;
                            },
                            footer: function (tooltipItems, data) {
                                var sum = 0;
                                var footer;

                                tooltipItems.forEach(function (tooltipItem) {
                                    sum += data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                });

                                footer = 'Total ' + $chart.data('tooltip') + ': ' + Math.round(sum * 100 ) / 100;

                                if ($chart.data('currency')) {
                                    footer += ' ' + $chart.data('currency');
                                }

                                return footer;
                            },
                        },
                    },
                }
            });

        })
    }

    $("#wpbs-reporting-date-interval-selector").change(function () {

        $select = $(this);

        const params = new URLSearchParams({
            'wpbs-reporting-start-date': $select.val(),
            'wpbs-reporting-end-date': ($select.find('option:selected').data('end-date') ? $select.find('option:selected').data('end-date') : ''),
            'wpbs-reporting-interval': $select.find('option:selected').data('interval')

        });

        window.location.href = $select.data('url') + '&' + params.toString();

    })
})