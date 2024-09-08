<?php 
function TotalSales() {
  $total = 0;

  // Get all the rates in one go using a JOIN statement
  $query = "SELECT SUM(l.price) AS total_price 
            FROM bookings b
            JOIN lotteries l ON b.lid = l.lid";

  $result = DB::query($query);

  // Check if we got a result and return the total price
  if (!empty($result) && isset($result[0]['total_price'])) {
      return $result[0]['total_price'];
  }

  return 0;  // Return 0 if no bookings or an error occurs
}

function confirmSales() {
  $total = 0;

  // Get all the rates in one go using a JOIN statement
  $query = "SELECT SUM(l.price) AS total_price 
            FROM bookings b
            JOIN lotteries l ON b.lid = l.lid
            WHERE b.pstatus = '1'";

  $result = DB::query($query);

  // Check if we got a result and return the total price
  if (!empty($result) && isset($result[0]['total_price'])) {
      return $result[0]['total_price'];
  }

  return 0;  // Return 0 if no bookings or an error occurs
}

function reviewSales() {
  $total = 0;

  // Get all the rates in one go using a JOIN statement
  $query = "SELECT SUM(l.price) AS total_price 
            FROM bookings b
            JOIN lotteries l ON b.lid = l.lid
            WHERE b.pstatus = 'await'";

  $result = DB::query($query);

  // Check if we got a result and return the total price
  if (!empty($result) && isset($result[0]['total_price'])) {
      return $result[0]['total_price'];
  }

  return 0;  // Return 0 if no bookings or an error occurs
}


function generateAgentNames() {
  // Fetch all agent names from the database using the DB class
  $agents = DB::query("SELECT name FROM agents ORDER BY id ASC");

  $names = [];
  foreach ($agents as $row) {
      $names[] = $row['name'];
  }

  return $names;
}
function getAgentSaleNumd() {
  $dated = date("Y-m-d"); // Get today's date in "Y-m-d" format.

  // Use a single query to get the number of orders for each agent for today
  $sql = "SELECT a.aid, COUNT(o.id) as order_count
          FROM agents a
          LEFT JOIN orders o ON a.aid = o.agent AND o.dated = :dated
          GROUP BY a.aid
          ORDER BY a.id ASC";

  $agentsSales = DB::query($sql, [':dated' => $dated]);

  $salesCounts = [];
  foreach ($agentsSales as $row) {
      $salesCounts[] = $row['order_count'];
  }

  return $salesCounts;
}
function getAgentSaleNumt() {
  $dated = date("Y-m-d"); // Get today's date in "Y-m-d" format.

  // Use a single query to get the number of orders for each agent for today
  $sql = "SELECT a.aid, COUNT(o.id) as order_count
          FROM agents a
          LEFT JOIN bookings o ON a.aid = o.agent AND o.dated = :dated
          GROUP BY a.aid
          ORDER BY a.id ASC";

  $agentsSales = DB::query($sql, [':dated' => $dated]);

  $salesCounts = [];
  foreach ($agentsSales as $row) {
      $salesCounts[] = $row['order_count'];
  }

  return $salesCounts;
}


$salesToday =(float)Dashboard::salesToday();
$salesYesterday =(float) Dashboard::salesYesterday();

$salesWeek = (float)Dashboard::salesThisWeek();
$salesLastWeek = (float)Dashboard::salesLastWeek();

$salesMonth = (float)Dashboard::salesMonth();
$salesLastMonth = (float)Dashboard::salesLastMonth();

function showPercentageDaily($today, $yesterday) {
  $stat = [];

  if ($today == 0 || $yesterday == 0) {
      // If either today or yesterday is 0, percentage change cannot be calculated meaningfully
      $stat['today'] = 0;
      $stat['yesterday'] = 0;
  } else {
      // Calculate the percentage change from yesterday to today
      $todayChange = (($today - $yesterday) / $yesterday) * 100;
      
      // Calculate the percentage change from today to yesterday
      $yesterdayChange = (($yesterday - $today) / $today) * 100;
      
      // Store the results rounded to 0 decimal places
      $stat['today'] = round($todayChange, 0);
      $stat['yesterday'] = round($yesterdayChange, 0);
  }

  return $stat;
}


function showPercentageWeekly($today,$yesterday){
  $stat = [];

  if ($today == 0 || $yesterday == 0) {
      // If either today or yesterday is 0, percentage change cannot be calculated meaningfully
      $stat['today'] = 0;
      $stat['yesterday'] = 0;
  } else {
      // Calculate the percentage change from yesterday to today
      $todayChange = (($today - $yesterday) / $yesterday) * 100;
      
      // Calculate the percentage change from today to yesterday
      $yesterdayChange = (($yesterday - $today) / $today) * 100;
      
      // Store the results rounded to 0 decimal places
      $stat['today'] = round($todayChange, 0);
      $stat['yesterday'] = round($yesterdayChange, 0);
  }

  return $stat;
}

function showPercentageMonthly($today,$yesterday){
  $stat = [];

  if ($today == 0 || $yesterday == 0) {
      // If either today or yesterday is 0, percentage change cannot be calculated meaningfully
      $stat['today'] = 0;
      $stat['yesterday'] = 0;
  } else {
      // Calculate the percentage change from yesterday to today
      $todayChange = (($today - $yesterday) / $yesterday) * 100;
      
      // Calculate the percentage change from today to yesterday
      $yesterdayChange = (($yesterday - $today) / $today) * 100;
      
      // Store the results rounded to 0 decimal places
      $stat['today'] = round($todayChange, 0);
      $stat['yesterday'] = round($yesterdayChange, 0);
  }

  return $stat;
}
function displayInfoBox($title, $sales, $percentage, $icon, $bgClass) {
  $arrowIcon = $percentage >= 0 ? 'fa-caret-up text-success' : 'fa-caret-down text-danger';
  $percentageClass = $percentage >= 0 ? 'text-success' : 'text-danger';
  $formattedPercentage = abs(round($percentage, 0));
  
  echo '
  <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box mb-3">
          <span class="info-box-icon ' . $bgClass . ' elevation-1"><i class="fas ' . $icon . '"></i></span>
          <div class="info-box-content">
              <span class="info-box-text">' . $title . '</span>
              <span class="info-box-number">' . $sales . ' <small class="' . $percentageClass . '"><i class="fas ' . $arrowIcon . '"></i> ' . $formattedPercentage . ' %</small></span>
          </div>
      </div>
  </div>
  ';
}

$perday = showPercentageDaily($salesToday,$salesYesterday);
$perweek = showPercentageWeekly($salesWeek,$salesLastWeek);
$permonth = showPercentageMonthly($salesMonth,$salesLastMonth);

?>
<!-- jQuery Knob Chart -->
<script src="/resources/plugins/jquery-knob/jquery.knob.min.js"></script>
<script src="/resources/plugins/flot/jquery.flot.js"></script>
<!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
<script src="/resources/plugins/flot/plugins/jquery.flot.resize.js"></script>
<!-- FLOT PIE PLUGIN - also used to draw donut charts -->
<script src="/resources/plugins/flot/plugins/jquery.flot.pie.js"></script>
<script src="/resources/plugins/chart.js/Chart.min.js"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">


      
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div><!-- /.col -->


          <div class="col-md-12">
          <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">New Orders</span>
                <span class="info-box-number">
                  <?php echo Dashboard::newOrders(); ?>
                  <small class="text-danger">/<?php echo Dashboard::newBooks(); ?> Tickets</small>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <?php 
          displayInfoBox('Sales Today', $salesToday, $perday['today'], 'fa-shopping-cart', 'bg-success');
          displayInfoBox('Sales Yesterday', $salesYesterday, $perday['yesterday'], 'fa-shopping-cart', 'bg-success');
          displayInfoBox('Sales Week', $salesWeek, $perweek['today'], 'fa-users', 'bg-warning');
          displayInfoBox('Sales Last Week', $salesLastWeek, $perweek['yesterday'], 'fa-users', 'bg-warning');
          displayInfoBox('Sales Month', $salesMonth, $permonth['today'], 'fa-users', 'bg-primary');
          displayInfoBox('Sales Last Month', $salesLastMonth, $permonth['yesterday'], 'fa-users', 'bg-primary');
          ?>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        </div>

		  <section class="content">


		  <?php //Mino::warningUnpaid($person['aid']); ?> 
			<?php require_once("./app/view/extra/monthly.php");?>
			
				  <!-- solid sales graph -->
          <div class="card bg-gradient-info">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-th mr-1"></i>
                  Daily Sales Graph
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <canvas class="chart" id="line-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
              
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
			  
			  <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Daily Customers</h3>

            <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
            </div>
          </div>
          <div class="card-body">
            <div class="chart">
            <canvas id="barChart" style="min-height: 250px; height: 600px; max-height: 600px; max-width: 100%;"></canvas>
            </div>
          </div>
          
				<!-- /.card-body -->
			  </div>
		   </section>
        




        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    
</div>

<script>
//-------------
//- BAR CHART -
//-------------
var barChartCanvas = $('#barChart').get(0).getContext('2d');

var barChartData = {
  labels: <?php echo json_encode(generateAgentNames()); ?>,
  datasets: [
    {
      label: 'Customers',
      backgroundColor: 'rgba(60,141,188,0.9)',
      borderColor: 'rgba(60,141,188,0.8)',
      pointRadius: false,
      pointColor: '#3b8bba',
      pointStrokeColor: 'rgba(60,141,188,1)',
      pointHighlightFill: '#fff',
      pointHighlightStroke: 'rgba(60,141,188,1)',
      data: <?php echo json_encode(getAgentSaleNumd()); ?>
    },
	{
      label: 'Bookings',
      backgroundColor: 'rgba(40,141,110,0.9)',
      borderColor: 'rgba(60,141,188,0.8)',
      pointRadius: false,
      pointColor: '#3b8bba',
      pointStrokeColor: 'rgba(60,141,188,1)',
      pointHighlightFill: '#fff',
      pointHighlightStroke: 'rgba(60,141,188,1)',
      data: <?php echo json_encode(getAgentSaleNumt()); ?>
    }
  ]
};

var barChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  datasetFill: true,
  scales: {
    yAxes: [{
      ticks: {
        beginAtZero: true,
        mirror: false,
        padding: 10,
        callback: function(value) {
          return value.length > 10 ? value.substr(0, 10) + '...' : value;
        }
      },
      gridLines: {
        display: false
      }
    }],
    xAxes: [{
      ticks: {
        beginAtZero: true,
        maxTicksLimit: 10
      },
      gridLines: {
        display: true
      }
    }]
  },
  layout: {
    padding: {
      left: 0,
      right: 30,
      top: 10,
      bottom: 0
    }
  }
};

new Chart(barChartCanvas, {
  type: 'horizontalBar',
  data: barChartData,
  options: barChartOptions
});

  // Sales graph chart
  var salesGraphChartCanvas = $('#line-chart').get(0).getContext('2d');
  // $('#revenue-chart').get(0).getContext('2d');

  var salesGraphChartData = {
    labels: ['2011 Q1', '2011 Q2', '2011 Q3', '2011 Q4', '2012 Q1', '2012 Q2', '2012 Q3', '2012 Q4', '2013 Q1', '2013 Q2'],
    datasets: [
      {
        label: 'Digital Goods',
        fill: false,
        borderWidth: 2,
        lineTension: 0,
        spanGaps: true,
        borderColor: '#efefef',
        pointRadius: 3,
        pointHoverRadius: 7,
        pointColor: '#efefef',
        pointBackgroundColor: '#efefef',
        data: [2666, 2778, 4912, 3767, 6810, 5670, 4820, 15073, 10687, 8432]
      }
    ]
  }

  var salesGraphChartOptions = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        ticks: {
          fontColor: '#efefef'
        },
        gridLines: {
          display: false,
          color: '#efefef',
          drawBorder: false
        }
      }],
      yAxes: [{
        ticks: {
          stepSize: 5000,
          fontColor: '#efefef'
        },
        gridLines: {
          display: true,
          color: '#efefef',
          drawBorder: false
        }
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  // eslint-disable-next-line no-unused-vars
  var salesGraphChart = new Chart(salesGraphChartCanvas, { // lgtm[js/unused-local-variable]
    type: 'line',
    data: salesGraphChartData,
    options: salesGraphChartOptions
  });

 

</script>