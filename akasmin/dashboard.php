<?php
    require '../server/config.php';
    session_start();
    if($_SESSION["login"]!="True")
          header("Location: index.php");

    function executeQuery($sql)
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con,$sql);
        mysqli_close($con);
        return $res;
    }

    if(isset($_POST['submit-news'])){
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con,"insert into news (item) values('".$_POST['news']."')");
        mysqli_close($con);
        if($res)
        {
            $data = array (
                          'interests' =>
                          array (
                            0 => 'normal',
                          ),
                          'webhookUrl' => 'http://mysite.com/push-webhook',
                          'fcm' =>
                          array (
                            'notification' =>
                            array (
                              'title' => 'FruizHub News',
                              'body' => $_POST['news'],
                              'sound' => 'default'
                            ),
                            "data" => array(
                               "navigator" => 'promotions',
                               "data" => ''
                               #"thumbnail" => $params['data']['thumbnail'],
                               #"attachment-url" => $params['data']['url'],
                             ),
                          ),
                      );
            $data_string = json_encode($data);
            $ch = curl_init('https://40347688-e8bc-4e43-86f5-1cfa742c125e.pushnotifications.pusher.com/publish_api/v1/instances/40347688-e8bc-4e43-86f5-1cfa742c125e/publishes');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer 464C427E34D1DB7E2BDCAAA2A973F09'
            ));
            curl_exec($ch);
            echo "<script>alert('Successfully Posted !');</script>";
        }
        else
            echo "<script>alert('Please try again!');</script>";
    }

    if(isset($_POST['submit-offer']))
    {
        if($_POST['product-type']=="p")
        {
            $title = "Plan Offer";
            $navigate = "plans";
            if ($_POST['plan-type'] == 'm')
                $id = $_POST['product-month-plan'];
            else
                $id = $_POST['product-week-plan'];
        }
        else if($_POST['product-type']=="k")
        {
            $title = "Combo Kit Offer";
            $navigate = "trials";
            $id = $_POST['product-kit'];
        }
        else if($_POST['product-type']=="d")
        {
            $title = "Drink Offer";
            $navigate = "drinks";
            $id = $_POST['product-drink'];
        }

        $data = array (
                      'interests' =>
                      array (
                        0 => 'normal',
                      ),
                      'webhookUrl' => 'http://mysite.com/push-webhook',
                      'fcm' =>
                      array (
                        'notification' =>
                        array (
                          'title' => $title,
                          'body' => $_POST['offer'],
                          'sound' => 'default'
                        ),
                        "data" => array(
                           "navigator" => $navigate,
                           "data" => $id
                           #"thumbnail" => $params['data']['thumbnail'],
                           #"attachment-url" => $params['data']['url'],
                         ),
                      ),
                  );
        $data_string = json_encode($data);
        $ch = curl_init('https://40347688-e8bc-4e43-86f5-1cfa742c125e.pushnotifications.pusher.com/publish_api/v1/instances/40347688-e8bc-4e43-86f5-1cfa742c125e/publishes');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer 464C427E34D1DB7E2BDCAAA2A973F09'
        ));
        curl_exec($ch);
        echo "<script>alert('Pushed Offer!');</script>";
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
    <title>Dashboard - FruizHUB</title>
    <!-- Bootstrap Core CSS -->
    <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- This page CSS -->
    <!-- chartist CSS -->
    <link href="../assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="../assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <!--c3 CSS -->
    <link href="../assets/plugins/c3-master/c3.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="css/pages/dashboard.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="css/colors/default-dark.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="fix-header fix-sidebar card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label">FruizHUB ADMIN</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php">
                        <!-- Logo icon --><b>
                            <img src="../assets/images/logo-icon.png" alt="homepage" class="dark-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span>
                            <img src="../assets/images/logo-text.png" alt="homepage" class="dark-logo" />
                        </span>
                    </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li> <a class="waves-effect waves-dark active" href="dashboard.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a></li>
                        <li> <a class="waves-effect waves-dark" href="delivery-drink.php" aria-expanded="false"><i class="mdi mdi-truck"></i><span class="hide-menu">Drink Delivery</span></a></li>
                        <li> <a class="waves-effect waves-dark" href="delivery-planner.php" aria-expanded="false"><i class="mdi mdi-truck-delivery"></i><span class="hide-menu">Plan Delivery</span></a></li>
                        <li> <a class="waves-effect waves-dark" href="drink-orders.php" aria-expanded="false"><i class="mdi mdi-cart-outline"></i><span class="hide-menu">Drink Orders</span></a></li>
                        <li> <a class="waves-effect waves-dark" href="plan-orders.php" aria-expanded="false"><i class="mdi mdi-cart"></i><span class="hide-menu">Planner Orders</span></a></li>
                        <li> <a class="waves-effect waves-dark" href="drinks.php" aria-expanded="false"><i class="mdi mdi-glass-mug"></i><span class="hide-menu">Drinks Manager</span></a></li>
                        <li> <a class="waves-effect waves-dark" href="planner.php" aria-expanded="false"><i class="mdi mdi-calendar-multiple"></i><span class="hide-menu">Plans Manager</span></a></li>
                        <li> <a class="waves-effect waves-dark" href="trial.php" aria-expanded="false"><i class="mdi mdi-calendar-multiple"></i><span class="hide-menu">Combo Kit Manager</span></a></li>
                        <li> <a class="waves-effect waves-dark" href="messages.php" aria-expanded="false"><i class="mdi mdi-message-text"></i><span class="hide-menu">Messages</span></a></li>
                        <li> <a class="waves-effect waves-dark" href="banner.php" aria-expanded="false"><i class="mdi mdi-calendar-multiple"></i><span class="hide-menu">Banner Manager</span></a></li>
                        <li> <a class="waves-effect waves-dark" href="addpage.php" aria-expanded="false"><i class="mdi mdi-file-document"></i><span class="hide-menu">Page System</span></a></li>
                        <li> <a class="waves-effect waves-dark" href="manage.php" aria-expanded="false"><i class="mdi mdi-desktop-mac"></i><span class="hide-menu">Manage System</span></a></li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h3 class="text-themecolor">Dashboard</h3>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Sales overview chart -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-lg-9 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div>
                                        <h3 class="card-title m-b-5"><span class="lstick"></span>Sales Overview [Last 10 Transactions]</h3>
                                    </div>
                                </div>
                                <div class="table-responsive m-t-20">
                                    <table class="table vm no-th-brd no-wrap pro-of-month">
                                      <thead>
                                        <tr>
                                          <th>TID</th>
                                          <th>Name</th>
                                          <th>Phone</th>
                                          <th>Time</th>
                                          <th>Mode</th>
                                          <th>Date</th>
                                        </tr>
                                      </thead>
                                      <?php
                                          // Query to get last 10 transactions
                                          $res = executeQuery("select tid, name, dtime, phone, tmode, tdate from transactions order by tdate desc limit 10");
                                          while ($row = $res->fetch_assoc())
                                          {
                                            echo "<tr>".
                                                    "<td><a href='drink-orders.php?tid=".$row['tid']."'>".$row['tid']."</a></td>".
                                                    "<td>".$row['name']."</td>".
                                                    "<td>".$row['phone']."</td>".
                                                    "<td>".$row['dtime']."</td>".
                                                    "<td>".$row['tmode']."</td>".
                                                    "<td>".$row['tdate']."</td>".
                                                  "</tr></a>";
                                          }
                                       ?>
                                    </table>
                                  </div>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- visit charts-->
                    <!-- ============================================================== -->
                    <div class="col-lg-3 col-md-12">
                        <!-- <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><span class="lstick"></span>Vital Stats</h4>
                                <table class="table vm font-14">
                                  <?php
                                      $res = executeQuery("select distinct(tmode), count(tmode)cnt from transactions GROUP by tmode");
                                      while ($row = $res->fetch_assoc()) {
                                        # code...
                                        echo "<tr>".
                                                "<td>".$row['tmode']."</td>".
                                                "<td class='text-right font-medium'><i class='mdi mdi-cart-plus'></i> ".$row['cnt']."</td>".
                                              "<tr>";
                                      }
                                   ?>
                                </table>
                            </div>
                        </div> -->
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><span class="lstick"></span>Publish Insta News</h4>
                                <form submit = "<?php $_PHP_SELF?>" method="POST" class="form-horizontal form-material">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" placeholder="Your short news!" class="form-control form-control-line" name="news">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input type="submit" value="Publish News" name='submit-news' class="pull-right btn btn-success"/>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><span class="lstick"></span>Push Instant Offer</h4>
                                <form submit = "<?php $_PHP_SELF?>" method="POST" class="form-horizontal form-material">
                                    <div class="form-group">
                                      <div class="col-md-12">
                                          <select id="products" class="form-control" name="product-type">
                                            <option>Select Product</option>
                                            <option value="p">Plans</option>
                                            <option value="k">Combo Kit</option>
                                            <option value="d">Drinks</option>
                                          </select>
                                      </div>
                                    </div>

                                    <div class="form-group" id="planChooser" style="display:none;">
                                      <div class="col-md-12">
                                          <select id="planPicker" class="form-control" name="plan-type">
                                            <option>Select Type</option>
                                            <option value="w">Weekly Plans</option>
                                            <option value="m">Monthly Plans</option>
                                          </select>
                                      </div>
                                    </div>

                                    <div class="form-group" id="planMonthly" style="display:none;">
                                      <label class="col-md-12">Choose Plans</label>
                                      <div class="col-md-12">
                                          <select class="form-control" name="product-month-plan">
                                              <?php
                                                 $res = executeQuery("select pid,name from plans where type = 'm'");
                                                 while($row = $res->fetch_assoc())
                                                 {
                                                     echo "<option value='".$row['pid']."'>".$row['name']."</option>";
                                                 }
                                              ?>
                                          </select>
                                      </div>
                                    </div>

                                    <div class="form-group" id="planWeekly" style="display:none;">
                                      <label class="col-md-12">Choose Plans</label>
                                      <div class="col-md-12">
                                          <select class="form-control" name="product-week-plan">
                                              <?php
                                                 $res = executeQuery("select pid,name from plans where type = 'w'");
                                                 while($row = $res->fetch_assoc())
                                                 {
                                                     echo "<option value='".$row['pid']."'>".$row['name']."</option>";
                                                 }
                                              ?>
                                          </select>
                                      </div>
                                    </div>

                                    <div class="form-group" id="kitChooser" style="display:none;">
                                      <label class="col-md-12">Choose Kits</label>
                                      <div class="col-md-12">
                                          <select class="form-control" name="product-kit">
                                              <?php
                                                 $res = executeQuery("select pid, name from plans where type in ('t')");
                                                 while($row = $res->fetch_assoc())
                                                 {
                                                     echo "<option value='".$row['pid']."'>".$row['name']."</option>";
                                                 }
                                              ?>
                                          </select>
                                      </div>
                                    </div>
                                    <div class="form-group" id="drinkChooser" style="display:none;">
                                      <label class="col-md-12">Choose Drinks</label>
                                      <div class="col-md-12">
                                          <select class="form-control" name="product-drink">
                                              <?php
                                                 $res = executeQuery("select did, name from drinks");
                                                 while($row = $res->fetch_assoc())
                                                 {
                                                     echo "<option value='".$row['did']."'>".$row['name']."</option>";
                                                 }
                                              ?>
                                          </select>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" placeholder="Your short offer!" class="form-control form-control-line" name="offer">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input type="submit" value="Push Offer" name='submit-offer' class="pull-right btn btn-success"/>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Projects of the month -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div>
                                        <h4 class="card-title"><span class="lstick"></span>Top Buyers [All Time]</h4></div>
                                </div>
                                <div class="table-responsive m-t-20">
                                    <table class="table vm no-th-brd no-wrap pro-of-month">
                                        <thead>
                                          <th></th>
                                          <th>Name / Phone</th>
                                          <th class='text-right'>Total Purchased</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $res = executeQuery("select name, uid, phone, sum(amt) from transactions join trandets using (tid) group by uid, phone, name order by sum(amt) desc limit 5");
                                                while ($row = $res->fetch_assoc()) {
                                                  # code...
                                                  echo "<tr>".
                                                          "<td style='width:50px;'><span class='round'><img src='../assets/images/users/1.jpg' alt='user' width='50'></span></td>".
                                                          "<td><h6>".$row['name']."</h6><small class='text-muted'>".$row['phone']."</small></td>".
                                                          "<td class='text-right font-medium'><i class='mdi mdi-currency-inr'></i> ".$row['sum(amt)']."</td>".
                                                        "</tr>";
                                                }
                                             ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- contact -->
                    <div class="col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <h4 class="card-title"><span class="lstick"></span>Top Buyers [This Month]</h4>
                                </div>
                                <div class="table-responsive m-t-20">
                                    <table class="table vm no-th-brd no-wrap pro-of-month">
                                        <thead>
                                          <th></th>
                                          <th>Name / Phone</th>
                                          <th class='text-right'>Total Purchased</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $res = executeQuery("select name, uid, phone, sum(amt) from transactions join trandets using (tid) where tdate like '%".date("m")."%' group by uid, phone, name order by sum(amt) desc limit 5");
                                                while ($row = $res->fetch_assoc()) {
                                                  # code...
                                                  echo "<tr>".
                                                          "<td style='width:50px;'><span class='round'><img src='../assets/images/users/1.jpg' alt='user' width='50'></span></td>".
                                                          "<td><h6>".$row['name']."</h6><small class='text-muted'>".$row['phone']."</small></td>".
                                                          "<td class='text-right font-medium'><i class='mdi mdi-currency-inr'></i> ".$row['sum(amt)']."</td>".
                                                        "</tr>";
                                                }
                                             ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Blog and website visit -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-lg-4 col-xlg-3">
                        <div class="card">
                            <div class="card-body">
                              <div class="d-flex">
                                  <h4 class="card-title"><span class="lstick"></span>Incoming Messages</h4>
                              </div>
                              <div class="message-box contact-box">
                                  <div class="message-widget contact-widget">
                                      <?php
                                        $res = executeQuery("SELECT name, phone from messages join users using (phone) group by name, phone limit 5");
                                        while ($row = $res->fetch_assoc()) {
                                          # code...
                                          echo "<!-- Message -->
                                          <a href='messages.php?phone=".$row['phone']."'>
                                              <div class='user-img'> <img src='../assets/images/users/1.jpg' alt='user' class='img-circle'></div>
                                              <div class='mail-contnet'>
                                                  <h5>".$row['name']."</h5> <span class='mail-desc'>".$row['phone']."</span></div>
                                          </a>";
                                        }
                                      ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-xlg-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <h4 class="card-title"><span class="lstick"></span>Planner Transactions</h4>
                                </div>
                                <div class="table-responsive m-t-20">
                                  <table class="table vm no-th-brd no-wrap pro-of-month">
                                      <thead>
                                          <th>TID</th>
                                          <th>Name</th>
                                          <th>Phone</th>
                                          <th>Plan Name</th>
                                          <th>Start</th>
                                          <th>End</th>
                                          <th>Mode</th>
                                      </thead>
                                      <tbody>
                                        <?php
                                          $res = executeQuery("select plans.name as pname, tid, planTrans.name, phone, sdate, edate, mode from planTrans join plans using (pid) order by tdate desc limit 10");
                                          while ($row = $res->fetch_assoc()) {
                                            # code...
                                            echo "<tr>".
                                                    "<td><a href='plan-orders.php?tid=".$row['tid']."'>".$row['tid']."</a></td>".
                                                    "<td>".$row['name']."</td>".
                                                    "<td>".$row['phone']."</td>".
                                                    "<td>".$row['pname']."</td>".
                                                    "<td>".$row['sdate']."</td>".
                                                    "<td>".$row['edate']."</td>".
                                                    "<td>".$row['mode']."</td>".
                                                  "</tr>";
                                          }
                                        ?>
                                      </tbody>
                                  </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Page Content -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer"> © 2018 Fruizhub Admin Panel by <a target="_blank" href="http://serviquik.com/">ServiQuik</a> </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap popper Core JavaScript -->
    <script src="../assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="js/custom.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <script src="../assets/plugins/chartist-js/dist/chartist.min.js"></script>
    <script src="../assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
    <!--c3 JavaScript -->
    <script src="../assets/plugins/d3/d3.min.js"></script>
    <script src="../assets/plugins/c3-master/c3.min.js"></script>
    <!-- Chart JS -->
    <script src="js/dashboard.js"></script>
    <script>
      setInterval(function(){
        $.getJSON("../server/index.php?check_new", function(result){
            if(result.type=="drink")
              alert('You got a new drink order !');
            else if (result.type=="plan")
              alert('you got a new plan order !');
        });
    },2000);

    $(document).ready(function() {

      $("#products").change(function() {

        var el = $(this) ;

        if(el.val() === "p" ) {
        $("#planChooser").css("display", "block");
        $("#drinkChooser").css("display", "none");
        $("#kitChooser").css("display", "none");
        }
        else if(el.val() === "k" ) {
            $("#kitChooser").css("display", "block");
            $("#drinkChooser").css("display", "none");
            $("#planChooser").css("display", "none");
        }
        else if(el.val() === "d" ) {
            $("#drinkChooser").css("display", "block");
            $("#kitChooser").css("display", "none");
            $("#planChooser").css("display", "none");
        }
      });

      $("#planPicker").change(function() {

        var el = $(this) ;

        if(el.val() === "m" ) {
            $("#planMonthly").css("display", "block");
            $("#planWeekly").css("display", "none");
        }
        else if(el.val() === "w" ) {
            $("#planMonthly").css("display", "none");
            $("#planWeekly").css("display", "block");
        }
      });

    });
    </script>

</body>

</html>
