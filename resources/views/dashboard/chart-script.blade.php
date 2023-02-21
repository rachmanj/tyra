<script>
    $(function () {
        'use strict'
      
        var ticksStyle = {
          fontColor: '#495057',
          fontStyle: 'bold'
        }
      
        var mode = 'index'
        var intersect = true
    
        let hazards = {!! json_encode($hazard_by_project) !!};
        var projects = Object.keys(hazards);
        
        // make array of pending hazard
        const pendingArray = Object.entries(hazards).map(([key, value]) => value.pending);

        const closedArray = Object.entries(hazards).map(([key, value]) => value.closed);

        // BAR CHART OF HAZARD REPORT BY PROJECT
        var hazardChart1 = $('#hazard-chart')
      // eslint-disable-next-line no-unused-vars
      var hazardChart = new Chart(hazardChart1, {
        type: 'bar',
        data: {
          labels: projects,
          datasets: [
            {
              backgroundColor: '#007bff',
              borderColor: '#007bff',
              data: pendingArray
            },
            {
              backgroundColor: '#ced4da',
              borderColor: '#ced4da',
              data: closedArray
        }
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
                callback: function (value) {
                  if (value > 1000) {
                    value /= 1000
                    
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
      })

      //PIE CHART OF HAZARD REPORT BY DANGER CATEGORY
      let dangerTypes = {!! json_encode($danger_types) !!};
      var dangerTypeName = dangerTypes.map(function(obj) {
          return obj.danger_type_id;
      });
         
      var total_counts = dangerTypes.map(function(obj) {
          return obj.count;
      });

      var $categoriesChart = $('#categories-chart')
      // eslint-disable-next-line no-unused-vars
      var categoriesChart = new Chart($categoriesChart, {
        type: 'pie',
        data: {
          labels: dangerTypeName,
          datasets: [
            {
              data: total_counts,
              backgroundColor: ['#007bff', '#28a745', '#333333', '#c3e6cb', '#dc3545', '#6c757d'],
            }
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
            display: true
          },
          scales: {
            yAxes: [{
              display: false,
              gridLines: {
                display: true,
                lineWidth: '4px',
                color: 'rgba(0, 0, 0, .2)',
                zeroLineColor: 'transparent'
              },
              ticks: $.extend({
                beginAtZero: true,
    
                // Include a dollar sign in the ticks
                callback: function (value) {
                  if (value >= 1000000) {
                    value /= 1000000
                    value += 'Jt'
                  }
    
                  return '' + value
                }
              }, ticksStyle)
            }],
            xAxes: [{
              display: false,
              gridLines: {
                display: false
              },
              ticks: ticksStyle
            }]
          }
        }
        }) 
       
    })
</script>