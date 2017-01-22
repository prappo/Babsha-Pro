<!DOCTYPE html>
<html lang="en" xmlns="https://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $__env->yieldContent('title'); ?></title>


    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(url('images/apple-icon-57x57.png')); ?>">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo e(url('images/apple-icon-60x60.png')); ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo e(url('images/apple-icon-72x72.png')); ?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo e(url('images/apple-icon-76x76.png')); ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo e(url('images/apple-icon-114x114.png')); ?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo e(url('images/apple-icon-120x120.png')); ?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo e(url('images/apple-icon-144x144.png')); ?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo e(url('images/apple-icon-152x152.png')); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(url('images/apple-icon-180x180.png')); ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo e(url('images/android-icon-192x192.png')); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(url('images/favicon-32x32.png')); ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo e(url('images/favicon-96x96.png')); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(url('images/favicon-16x16.png')); ?>">
    <link rel="manifest" href="<?php echo e(url('images/manifest.json')); ?>">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo e(url('images/ms-icon-144x144.png')); ?>">
    <meta name="theme-color" content="#ffffff">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css"
          integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
    <link rel="stylesheet" href="<?php echo e(url('/css/style.css')); ?>">
    <?php /* <link href="<?php echo e(elixir('css/app.css')); ?>" rel="stylesheet"> */ ?>
    <?php /*data table*/ ?>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }

    </style>


    <?php echo $__env->yieldContent('css'); ?>
</head>
<body id="app-layout">
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                <?php echo e(\App\Http\Controllers\Data::getShopTitle()); ?>

            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li><a href="<?php echo e(url('/home')); ?>"><b>Dashboard</b></a></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                <?php if(Auth::guest()): ?>
                    <li><a href="<?php echo e(url('/login')); ?>">Login</a></li>

                <?php else: ?>

                    <?php if(Auth::user()->type == 'admin'): ?>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                Site Settings <span
                                        class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo e(url('/settings/site')); ?>"><i class="fa fa-th"></i> Site Settings</a></li>

                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                Users <span class="badge"><?php echo e(\App\User::all()->count()); ?></span><span
                                        class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo e(url('/user/add')); ?>"><i class="fa fa-plus"></i> Add user</a></li>
                                <li><a href="<?php echo e(url('/user/list')); ?>"><i class="fa fa-users"></i> User List</a></li>

                            </ul>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                <?php echo e(Auth::user()->name); ?> <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">


                                <li><a href="<?php echo e(url('/profile')); ?>"><i class="fa fa-user"></i> Profile</a></li>
                                <li><a href="<?php echo e(url('/logout')); ?>"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
             <span
                     class="badge"><?php echo e(\App\Http\Controllers\Data::getUnit()); ?> <?php echo e(\App\Income::where('userId',Auth::user()->id)->sum('money')); ?></span>
                                <span class="caret"></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo e(url('/earning/history')); ?>"><i class="fa fa-money"></i> Earning
                                        History</a>
                                </li>
                                <li><a href="<?php echo e(url('/earning/history/paypal')); ?>"><i class="fa fa-paypal"></i> Paypal
                                        History</a>
                                </li>


                            </ul>

                        </li>

                        <li class="dropdown">
                            <a href="#" id="massMsg" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                <i class="fa fa-envelope"></i> Mass Message </span>
                            </a>


                        </li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                Orders <span class="badge"><?php echo e(\App\Orders::where('userId',Auth::user()->id)->where('status','pending')->count()); ?></span>
                                <span
                                        class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo e(url('/orders')); ?>"><i class="fa fa-tag"></i> View Orders</a></li>
                                <li><a href="<?php echo e(url('/orders/history')); ?>"><i class="fa fa-shopping-cart"></i> Orders
                                        History</a></li>

                            </ul>
                        </li>


                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                Products <span class="badge"><?php echo e(\App\Products::all()->count()); ?></span> <span
                                        class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo e(url('/showproducts')); ?>"><i class="fa fa-list"></i> View Products</a>
                                </li>

                                <li><a href="<?php echo e(url('/addproduct')); ?>"><i class="fa fa-btn fa-plus"></i> Add new Product</a>
                                <li><a href="<?php echo e(url('/showcategory')); ?>"><i class="fa fa-th"></i> View Categories</a>
                                </li>
                                <li><a href="<?php echo e(url('/addcategory')); ?>"><i class="fa fa-btn fa-plus"></i> Add new
                                        category</a></li>
                                </li>
                                <li><a href="<?php echo e(url('/showproducts/woo')); ?>"><i class="fa fa-wordpress"></i> View
                                        WooCommerce Products</a></li>
                                <li><a href="<?php echo e(url('/woo/addcategory')); ?>"><i class="fa fa-btn fa-wordpress"></i> Add
                                        WooCommerce
                                        category</a></li>
                                </li>
                                <li><a href="<?php echo e(url('/woo/showcategory')); ?>"><i class="fa fa-btn fa-wordpress"></i> View
                                        WooCommerce categories</a></li>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                Customers <span class="badge"><?php echo e(\App\Customers::all()->count()); ?></span><span
                                        class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo e(url('/customers')); ?>"><i class="fa fa-users"></i> Customers</a></li>

                            </ul>
                        </li>

                        <li class="dropdown">
                            <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                Bot <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo e(url('/bot')); ?>"><i class="fa fa-rocket"></i> Bot replies</a></li>

                                <li><a href="<?php echo e(url('/bot/settings')); ?>"><i class="fa fa-gear"></i> Bot Settings</a>
                            </ul>
                        </li>


                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                <?php echo e(Auth::user()->name); ?> <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">

                                <li><a href="<?php echo e(url('/settings')); ?>"><i class="fa fa-gear"></i> Settings</a></li>
                                <li><a href="<?php echo e(url('/profile')); ?>"><i class="fa fa-user"></i> Profile</a></li>
                                <li><a href="<?php echo e(url('/logout')); ?>"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a href="<?php echo e(url('/notifications')); ?>" id="massMsg" class="dropdown-toggle" role="button"
                            >
                                <i class="fa fa-bell"><sup><b><?php echo e(\App\Notifications::count()); ?></b></sup></i>
                            </a>


                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<?php echo $__env->yieldContent('content'); ?>

<!-- JavaScripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"
        integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<?php /* <script src="<?php echo e(elixir('js/app.js')); ?>"></script> */ ?>
<?php /*data table*/ ?>

<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js"></script>
<script src="<?php echo e(url('/js/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(url('/js/vfs_fonts.js')); ?>"></script>


<?php echo $__env->yieldContent('js'); ?>
<script>
    $(document).ready(function () {
        var table = $('#mytable').DataTable({
            dom: '<""flB>tip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<button class="btn btn-success btn-xs fak"><i class="fa fa-file-excel-o"></i> Export all to excel</button>'
                },
                {
                    extend: 'csv',
                    text: '<button class="btn btn-warning btn-xs fak"><i class="fa fa-file-o"></i> Export all to csv</button>'
                },
                {
                    extend: 'print',
                    text: '<button class="btn btn-default btn-xs fak"><i class="fa fa-print"></i> Print all</button>'
                },
            ]
        });

        $('#massMsg').click(function () {
            swal({
                title: "Mass Messaging!",
                text: "Send message to your customers",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "Write something",
                showLoaderOnConfirm: true,
            }, function (inputValue) {
                if (inputValue === false) return false;
                if (inputValue === "") {
                    swal.showInputError("You need to write something!");
                    return false
                }
                $.ajax({
                    type: 'POST',
                    url: '<?php echo e(url('/notify')); ?>',
                    data: {
                        'msg': inputValue
                    },
                    success: function (data) {
                        if (data.search('message_id') != -1) {
                            swal('Success', 'Message sent to all customers', 'success');
                        }
                        else {
                            swal('Error', data, 'error');
                        }
                    }
                });
            });
        })
    });

</script>

</body>
</html>
