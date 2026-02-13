<script>
    $(function () {
        'use strict'

        var ticksStyle = {
          fontColor: '#495057',
          fontStyle: 'bold'
        }

        var mode = 'index'
        var intersect = true

        let activeTyres = {!! json_encode($active_tyre_by_project) !!} || [];
        activeTyres = activeTyres.filter(function (item) { return item && item.current_project; });

        let projects = activeTyres.map(function (item) {
            return item.current_project;
        });

        let activeTyresCount = activeTyres.map(function (item) {
            return item.total;
        });

        // THE CHART
        let $activeTyresChart = $('#active-tyres-chart');
        if ($activeTyresChart.length) {
            new Chart($activeTyresChart, {
            type: 'bar',
            data: {
                labels: projects,
                datasets: [
                    {
                        backgroundColor: '#007bff',
                        borderColor: '#007bff',
                        data: activeTyresCount
                    },
                    // {
                    //     backgroundColor: '#ced4da',
                    //     borderColor: '#ced4da',
                    //     data: [28, 48, 40, 19, 86, 27, 90]
                    // }
                ]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect
                },
                hover: {
                    mode: mode,
                    intersect: intersect
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                    // display: false,
                    gridLines: {
                        display: true,
                        lineWidth: '4px',
                        color: 'rgba(0, 0, 0, .2)',
                        zeroLineColor: 'transparent'
                    },
                    ticks: $.extend({
                        beginAtZero: true,

                        // Include a dollar sign in the ticks
                        callback: function (value, index, values) {
                        if (value >= 1000) {
                            value /= 1000
                            value += 'k'
                        }
                        return value
                        }
                    }, ticksStyle)
                    }],
                    xAxes: [{
                    display: true,
                    gridLines: {
                        display: false
                    },
                    ticks: ticksStyle
                    }]
                }
            }
        });
        }
    })
</script>