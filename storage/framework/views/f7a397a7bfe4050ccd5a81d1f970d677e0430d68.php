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
                                <li><h3><span class="fa fa-users" aria-hidden="true"></span></h3> <span
                                            class="glyphicon-class"><h4><?php echo e(\App\User::all()->count()); ?></h4>Users</span>
                                </li>
                                <li><h3><span class="fa fa-facebook-official" aria-hidden="true"></span></h3> <span
                                            class="glyphicon-class"><h4><?php echo e(\App\FacebookPages::all()->count()); ?></h4>Pages</span>
                                </li>

                                <li><h3><span class="fa fa-file" aria-hidden="true"></span></h3> <span
                                            class="glyphicon-class"><h4><?php echo e(\App\Products::all()->count()); ?></h4>Products</span>
                                </li>

                                <li><h3><span class="fa fa-star" aria-hidden="true"></span></h3> <span
                                            class="glyphicon-class"><h4><?php echo e(\App\Catagories::all()->count()); ?></h4>Categories</span>
                                </li>

                            </ul>
                        </div>

                        <?php /*<div class="chart-container">*/ ?>
                            <?php /*<canvas id="myChart" width="200" height="100"></canvas>*/ ?>
                        <?php /*</div>*/ ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>

    <?php /*<script>*/ ?>
        <?php /*var labels = [*/ ?>
            <?php /*<?php foreach(\App\Income::take(20)->get() as $income): ?>"<?php echo e($income->created_at); ?>", <?php endforeach; ?>,*/ ?>
        <?php /*]*/ ?>
        <?php /*var money = [<?php foreach(\App\Income::take(20)->distinct()->get() as $money): ?><?php echo e($money->money); ?>, <?php endforeach; ?>]*/ ?>
        <?php /*var data = {*/ ?>
            <?php /*labels: labels,*/ ?>
            <?php /*datasets: [*/ ?>
                <?php /*{*/ ?>
                    <?php /*label: "Earning ",*/ ?>
                    <?php /*fill: false,*/ ?>
                    <?php /*lineTension: 0.1,*/ ?>
                    <?php /*backgroundColor: "rgba(75,192,192,0.4)",*/ ?>
                    <?php /*borderColor: "rgba(75,192,192,1)",*/ ?>
                    <?php /*borderCapStyle: 'butt',*/ ?>
                    <?php /*borderDash: [],*/ ?>
                    <?php /*borderDashOffset: 0.0,*/ ?>
                    <?php /*borderJoinStyle: 'miter',*/ ?>
                    <?php /*pointBorderColor: "rgba(75,192,192,1)",*/ ?>
                    <?php /*pointBackgroundColor: "#fff",*/ ?>
                    <?php /*pointBorderWidth: 1,*/ ?>
                    <?php /*pointHoverRadius: 5,*/ ?>
                    <?php /*pointHoverBackgroundColor: "rgba(75,192,192,1)",*/ ?>
                    <?php /*pointHoverBorderColor: "rgba(220,220,220,1)",*/ ?>
                    <?php /*pointHoverBorderWidth: 2,*/ ?>
                    <?php /*pointRadius: 1,*/ ?>
                    <?php /*pointHitRadius: 10,*/ ?>
                    <?php /*data: money,*/ ?>
                    <?php /*spanGaps: false,*/ ?>
                <?php /*}*/ ?>
            <?php /*]*/ ?>
        <?php /*};*/ ?>
        <?php /*var ctx = document.getElementById("myChart");*/ ?>
        <?php /*var myChart = new Chart(ctx, {*/ ?>
            <?php /*type: 'line',*/ ?>
            <?php /*data: data,*/ ?>
            <?php /*options: {*/ ?>
                <?php /*scales: {*/ ?>
                    <?php /*yAxes: [{*/ ?>
                        <?php /*ticks: {*/ ?>
                            <?php /*beginAtZero: true*/ ?>
                        <?php /*}*/ ?>
                    <?php /*}]*/ ?>
                <?php /*}*/ ?>
            <?php /*}*/ ?>
        <?php /*});*/ ?>


    <?php /*</script>*/ ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>