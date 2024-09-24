<div id="cl-analytics" class="cl-analytics">
    <div class="cl-chart">
        <canvas id="cl-chart" width="400" height="400"></canvas>
    </div>

    <script>
        var label = [<?php echo '"'.implode('","', $analytics_data['chart_data']['labels']).'"' ?>];
        var datasets_abandoned = [<?php echo '"'.implode('","', $analytics_data['chart_data']['abandoned']).'"' ?>];
        var datasets_recovered = [<?php echo '"'.implode('","', $analytics_data['chart_data']['recovered']).'"' ?>];
        let direction = document.documentElement.dir;
        var config = {
            type: 'line',
            data: {
                labels:label,
                datasets: [{
                    label: 'rtl' !== direction ? '<?php echo __('Abandoned', 'cart-lift');?>': '<?php echo __('Recovered', 'cart-lift');?>',
                    backgroundColor:'rtl' !== direction ?  '#ee8033' : '#6d41d3',
                    borderColor: 'rtl' !== direction ?  '#ee8033' : '#6d41d3',
                    data: 'rtl' !== direction ?  datasets_abandoned : datasets_recovered,
                    fill: false,
                },{
                    label: 'rtl' !== direction ? '<?php echo __('Recovered', 'cart-lift');?>': '<?php echo __('Abandoned', 'cart-lift');?>',
                    backgroundColor: 'rtl' !== direction ? '#6d41d3' : '#ee8033',
                    borderColor: 'rtl' !== direction ? '#6d41d3' : '#ee8033',
                    data: 'rtl' !== direction ? datasets_recovered : datasets_abandoned,
                    fill: false,
                }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                title: {
                    display: true,
                    text: '<?php echo __('Cart Overview', 'cart-lift');?>',
                    fontSize: 18,
                    fontColor: '#363b4e',
                    rtl: 'rtl' === direction ? true : false,
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                    bodySpacing: 12,
                    titleMarginBottom : 10,
                    xPadding : 7,
                    yPadding : 7,
                    rtl: 'rtl' === direction ? true : false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            rtl: 'rtl' === direction ? true : false,
                        },
                        ticks: {
                            reverse: 'rtl' === direction ? true : false
                        }
                    }],
                    yAxes: [{
                        display: true,
                        position: 'rtl' === direction ? 'right' : 'left',
                        ticks: {
                            stepSize: 1
                        },
                        scaleLabel: {
                            display: true,
                            rtl: 'rtl' === direction ? true : false,
                        }
                    }]
                }
            }
        };
        var ctx = document.getElementById('cl-chart').getContext('2d');
        var cl_chart = new Chart(ctx, config);
    </script>
</div>
