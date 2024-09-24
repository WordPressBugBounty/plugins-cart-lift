<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://rextheme.com/
 * @since      1.0.0
 *
 * @package    Wp_Abandoned_Cart
 * @subpackage Wp_Abandoned_Cart/admin/partials
 */

?>
<?php
global $wpdb;
$cl_cart_table = $wpdb->prefix . CART_LIFT_CART_TABLE;
$abandoned_obj = $wpdb->get_results( "SELECT COUNT(status) as count, DATE(time) as time FROM wp_cl_abandon_cart WHERE status = 'abandoned' GROUP BY DATE(time)");
$abandoned = array();
foreach ($abandoned_obj as $ab_value) {
    $abandoned[$ab_value->time] = $ab_value->count;
}

$prepared_statement = $wpdb->prepare( "SELECT time FROM $cl_cart_table");
$data_dates = $wpdb->get_col( $prepared_statement );
$Label_dates = array();
$Label_date_time = array();
foreach ($data_dates as $dtvalue) {
    $extract_dates = explode(" ",$dtvalue);
    $extracted_date = $extract_dates[0];
    $Label_dates[] = $extracted_date;
    $Label_date_time[] = $dtvalue;
}
asort($Label_dates);
asort($Label_date_time);
$labels = array_unique($Label_dates);
$date_time = array_unique($Label_date_time);
$datasets_abandoned = array();
$datasets_recovered = array();
/*foreach ($labels as $lbvalue) {
     $cart_counter = $wpdb->get_results( "SELECT cart_contents FROM  $cl_cart_table WHERE time =  DATE('$lbvalue') AND status = 'abandoned' ");
     echo "<pre>";
     echo $lbvalue;
     echo "</pre>";
     $length = count($cart_counter);
     $datasets_abandoned[] = $length;
}*/

foreach ($date_time as $lbvalue) {
    $cart_counter = $wpdb->get_results( "SELECT cart_contents FROM  $cl_cart_table WHERE time =  '$lbvalue' AND status = 'recovered' ");
    $length = count($cart_counter);
    $datasets_recovered[] = $length;
}

?>
<div id="cl-analytics">
    <label for="chart-option"><?php echo __( 'Choose an option:', 'cart-lift' ); ?></label>

    <select id="chart-option">
      <option value="all" selected><?php echo __( 'All', 'cart-lift' ); ?></option>
      <option value="weekly"><?php echo __( 'Last 7 Days', 'cart-lift' ); ?></option>
      <option value="monthly"><?php echo __( 'Last 30 Days', 'cart-lift' ); ?></option>
    </select>
    <div class="testststs">
      <canvas id="myChart" width="400" height="400"></canvas>
    </div>

  <script>
  var label = [<?php echo '"'.implode('","', $labels).'"' ?>];
  var datasets_abandoned = [<?php echo '"'.implode('","', $datasets_abandoned).'"' ?>];
  var datasets_recovered = [<?php echo '"'.implode('","', $datasets_recovered).'"' ?>];

  var config = {
    type: 'line',
    data: {
      labels:label,

      datasets: [{
        label: 'Totall abandon cart',
        backgroundColor: '#fd5e60',
        borderColor: '#fd5e60',
        data: datasets_abandoned,
        fill: false,
      },{
        label: 'Totall recovered cart',
        backgroundColor: '#0bb7ec',
        borderColor: '#0bb7ec',
        data: datasets_recovered,
        fill: false,
      }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      title: {
        display: true,
        text: 'Cart Lift Analytics'
      },
      tooltips: {
        mode: 'index',
        intersect: false,
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
            // labelString: 'Month'
          }
        }],
        yAxes: [{
          display: true,
          ticks: {
              stepSize: 1
           },
          scaleLabel: {
            display: true,
            // labelString: 'Value'
          }
        }]
      }
    }
  };
  var ctx = document.getElementById('myChart').getContext('2d');
  var myChart = new Chart(ctx, config);
  </script>
</div>
