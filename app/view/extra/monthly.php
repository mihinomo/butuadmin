<?php

function generate_dates($val) {
  $ar = [];
  $m = date("m");
  $de = date("d");
  $y = date("Y");

  for ($i = 0; $i <= $val; $i++) {
      $d = date('Y-m-d', mktime(0, 0, 0, $m, ($de - $i), $y));
      $ar[] = $d;
  }
  return array_reverse($ar);
}

function chartSalesAgent($val) {
  $data = [];
  $dates = generate_dates($val);
  foreach ($dates as $d) {
      $params = ['dated' => $d];
      $result = DB::query("SELECT COUNT(*) AS count FROM bookings WHERE dated = :dated", $params);
      $data[] = $result ? $result[0]['count'] : 0; // Assumes DB::query returns an array of arrays or an empty array if no results
  }
  return $data;
}



?>
  <div class="col-md-12">
          <div class="row">
          
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Recap</h5>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-8">
                    <p class="text-center">
                      <strong>Last 15 Days</strong>
                    </p>

                    <div class="chart">
                      <!-- Sales Chart Canvas -->
                      <canvas id="salesChart" height="180" style="height: 180px;"></canvas>
                    </div>
                    <!-- /.chart-responsive -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-4">
                    <p class="text-center">
                      <strong>Goal Completion | <a href="/progresslottery/?loti=all">View All</a></strong>
                    </p>

                    <?php Dashboard::makeProgressLotteryDash(); ?>
                    
                    <!-- /.progress-group -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- ./card-body -->
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <h5 class="description-header">₹ <?php echo TotalSales(); ?></h5>
                      <span class="description-text">TOTAL SALES</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <h5 class="description-header">₹ <?php echo confirmSales(); ?></h5>
                      <span class="description-text">Confirmed Sales</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <h5 class="description-header">₹ <?php echo reviewSales(); ?></h5>
                      <span class="description-text">Review Sales</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
<script>
$(function () {
  'use strict'

  /* ChartJS
   * -------
   * Here we will create a few charts using ChartJS
   */

  //-----------------------
  // - MONTHLY SALES CHART -
  //-----------------------

  // Get context with jQuery - using jQuery's .get() method.
  var salesChartCanvas = $('#salesChart').get(0).getContext('2d')

  var salesChartData = {
    labels: <?php echo json_encode(generate_dates(15)); ?>,
    datasets: [
      {
        label: 'Bookings',
        backgroundColor: 'rgba(60,141,188,0.9)',
        borderColor: 'rgba(60,141,188,0.8)',
        pointRadius: false,
        pointColor: '#3b8bba',
        pointStrokeColor: 'rgba(60,141,188,1)',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data: <?php echo json_encode(chartSalesAgent(15)); ?>
      }
    ]
  }

  var salesChartOptions = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        gridLines: {
          display: false
        }
      }],
      yAxes: [{
        gridLines: {
          display: false
        }
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart(salesChartCanvas, {
    type: 'line',
    data: salesChartData,
    options: salesChartOptions
  }
  )

  //---------------------------
  // - END MONTHLY SALES CHART -
  //---------------
});
</script>