<?php $__env->startSection('title','Home'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        <div class="bs-glyphicons">
                            <ul class="bs-glyphicons-list">
                                <li><h3><span class="fa fa-money" aria-hidden="true"></span></h3> <span
                                            class="glyphicon-class"><h4><?php echo e(\App\Income::where('userId',Auth::user()->id)->sum('money')); ?></h4>Earning</span>
                                </li>
                                <li><h3><span class="fa fa-shopping-cart" aria-hidden="true"></span></h3> <span
                                            class="glyphicon-class"><h4><?php echo e(\App\Orders::where('userId',Auth::user()->id)->distinct()->get(['orderId'])->where('status','pending')->count()); ?></h4>Orders</span>
                                </li>
                                <li><h3><span class="fa fa-list" aria-hidden="true"></span></h3> <span
                                            class="glyphicon-class"><h4><?php echo e(\App\Products::where('userId',Auth::user()->id)->count()); ?></h4>Products</span>
                                </li>

                                <li><h3><span class="fa fa-user-secret" aria-hidden="true"></span></h3> <span
                                            class="glyphicon-class"><h4><?php echo e($customer = \App\Customers::count()); ?></h4><?php if($customer >= 1 ): ?>
                                            Customer <?php else: ?> Customers <?php endif; ?></span>
                                </li>
                                <li><h3><span class="fa fa-th" aria-hidden="true"></span></h3> <span
                                            class="glyphicon-class"><h4><?php echo e(\App\Catagories::where('userId',Auth::user()->id)->count()); ?></h4>Categories</span>
                                </li>
                                <li><h3><span class="fa fa-paypal" aria-hidden="true"></span></h3> <span
                                            class="glyphicon-class"><h4><?php echo e(\App\Payments::where('userId',Auth::user()->id)->sum('amount')); ?></h4>PayPal</span>
                                </li>
                                <li><h3><span class="fa fa-bell" aria-hidden="true"></span></h3> <span
                                            class="glyphicon-class"><h4><?php echo e(\App\Notifications::where('userId',Auth::user()->id)->count()); ?></h4>Notifications</span>
                                </li>
                            </ul>
                        </div>

                        <div class="chart-container">
                            <canvas id="myChart" width="200" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>

    <script>
        var labels = [
            <?php foreach(\App\Income::take(20)->get() as $income): ?>"<?php echo e($income->created_at); ?>", <?php endforeach; ?>,
        ]
        var money = [<?php foreach(\App\Income::take(20)->distinct()->get() as $money): ?><?php echo e($money->money); ?>, <?php endforeach; ?>]
        var data = {
            labels: labels,
            datasets: [
                {
                    label: "Earning ",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(75,192,192,0.4)",
                    borderColor: "rgba(75,192,192,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(75,192,192,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(75,192,192,1)",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: money,
                    spanGaps: false,
                }
            ]
        };
        var ctx = document.getElementById("myChart");
        var myChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });


    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>