<?php
    require '../server/config.php';
    date_default_timezone_set('Asia/Kolkata');
    $params="";
    $status = "processing";
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
    <title>Plan Delivery - FruizHUB</title>
    <!-- Bootstrap Core CSS -->
    <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="css/colors/default-dark.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="fix-header card-no-border fix-sidebar">
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
                      <li> <a class="waves-effect waves-dark" href="dashboard.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a></li>
                      <li> <a class="waves-effect waves-dark" href="delivery-drink.php" aria-expanded="false"><i class="mdi mdi-truck"></i><span class="hide-menu">Drink Delivery</span></a></li>
                      <li> <a class="waves-effect waves-dark active" href="delivery-planner.php" aria-expanded="false"><i class="mdi mdi-truck-delivery"></i><span class="hide-menu">Plan Delivery</span></a></li>
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
                        <h3 class="text-themecolor">Today</h3>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-4 col-xlg-3 col-md-5">
                        <div class="card">
                          <div class='card-body'>
                            <div class="d-flex">
                                <h4 class="card-title"><span class="lstick"></span>Select Person</h4>
                            </div>
                            <div class="message-box contact-box">
                                <div class="message-widget contact-widget">
                                <?php
                                //SELECT * FROM Product_sales WHERE sdate >= '2018-04-22' AND edate <= '2018-04-22'
                                    $res = executeQuery("select name, phone, tid from planTrans where DATE(DATE_FORMAT(STR_TO_DATE(sdate, '%d-%m-%y'), '%Y-%m-%d')) <= DATE(DATE_FORMAT(STR_TO_DATE('".date("d-m-Y")."', '%d-%m-%y'), '%Y-%m-%d')) and DATE(DATE_FORMAT(STR_TO_DATE(edate, '%d-%m-%y'), '%Y-%m-%d')) >= DATE(DATE_FORMAT(STR_TO_DATE('".date("d-m-Y")."', '%d-%m-%y'), '%Y-%m-%d'))");
                                    //$res = executeQuery("select name, phone, tid from planTrans where sdate <= '".date("d-m-Y")."' and edate >= '".date("d-m-Y")."'");
                                    while($row = $res->fetch_assoc())
                                    {
                                    	$json = json_decode(mysqli_fetch_array(executeQuery("select dids from plans where pid in (select pid from planTrans where tid = ".$row['tid'].")"))[0]);
                                        foreach ($json as $key => $value){
                                          if(strpos($value,date("D"))!== false)
                                          {
                                            echo '<a href="?tod_tid='.$row['tid'].'">
                                                    <div class="user-img"> <img src="../assets/images/users/1.jpg" alt="user" class="img-circle"></div>
                                                    <div class="mail-contnet">
                                                        <h5>'.$row['name'].'</h5> <span class="pull-right">'.$row['tid'].'</span><span class="mail-desc">'.$row['phone'].'</span></div>
                                                 </a>';
                                          }
                                        }
                                        
                                        //echo '<a href="?tod_tid='.$row['tid'].'">
                                          //      <div class="user-img"> <img src="../assets/images/users/1.jpg" alt="user" class="img-circle"></div>
                                            //    <div class="mail-contnet">
                                              //      <h5>'.$row['name'].'</h5> <span class="pull-right">'.$row['tid'].'</span><span class="mail-desc">'.$row['phone'].'</span></div>
                                             //</a>';
                                    }
                                ?>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-8 col-xlg-9 col-md-7">
                        <div class="card">
                            <div class="card-body">
                              <?php
                                  if(isset($_GET['tod_tid']))
                                  {
                                    $tid = $_GET['tod_tid'];
                                    $data = mysqli_fetch_array(executeQuery("select name, phone, address, time, type, mode, pid, sdate, edate from planTrans where tid=  $tid"));
                                    $type='';
                                    if($data[4]=='w')
                                      $type = 'Weekly';
                                    else if ($data[4]=='m')
                                      $type = 'Monthly';
                                    else
                                      $type = 'Trial Kit';

                                    echo '<div class="d-flex">
                                            <h4 class="card-title"><span class="lstick"></span>Plan Delivery Details [#'.$tid.']</h4>
                                        </div>';
                                        echo "<b>Name: </b>".$data[0]."<br/>
                                              <b>Phone: </b>".$data[1]."<br/>
                                              <b>Address: </b>".$data[2]."<br/>
                                              <b>Delivery Time: </b>".$data[3]."<br/>
                                              <b>Plan Type: </b>".$type."<br/>
                                              <b>Duration: </b>".$data[7]." - ".$data[8]."<br/>
                                              <b>Payment Mode: </b>".$data[5]."<br/>";
                                              $json = json_decode(mysqli_fetch_array(executeQuery("select dids from plans where pid = $data[6] "))[0]);
                                              foreach ($json as $key => $value){
                                                if(strpos($value,date("D"))!== false)
                                                {
                                                  $drink = mysqli_fetch_array(executeQuery("select name from drinks where did = $key"))[0];
                                                  echo "<b>Drink to deliver: </b>".$drink."<br/>";
                                                }
                                              }
                                    ?>
                                    <div class="table-responsive m-t-20">
                                        <table class="table vm no-th-brd no-wrap pro-of-month">
                                          <thead>
                                            <tr>
                                              <th>Plan Name</th>
                                              <th>Quantity</th>
                                              <th>Amount</th>
                                              <th>Delivery Time</th>
                                              <th>Status</th>
                                              <th>Mode</th>
                                            </tr>
                                          </thead>
                                          <?php
                                              $res = executeQuery("select plans.name, quantity, planTrans.amt, time, status, mode from planTrans join plans using(pid) where tid= $tid");
                                              while ($row = $res->fetch_assoc())
                                              {
                                                echo "<tr>".
                                                        "<td>".$row['name']."</td>".
                                                        "<td>".$row['quantity']."</td>".
                                                        "<td>".$row['amt']."</td>".
                                                        "<td>".$row['time']."</td>".
                                                        "<td>".$row['status']."</td>".
                                                        "<td>".$row['mode']."</td>".
                                                      "</tr>";
                                              }
                                           ?>
                                        </table>
                                      </div>
                                      <h4>Previously Delivered Drinks:</b>
                                      <div class="table-responsive m-t-20">
                                          <table class="table vm no-th-brd no-wrap pro-of-month">
                                            <thead>
                                              <tr>
                                                <th>Date</th>
                                                <th>Signature</th>
                                              </tr>
                                            </thead>
                                            <?php
                                                $res = executeQuery("select date,image from planSign where tid= $tid");
                                                while ($row = $res->fetch_assoc())
                                                {
                                                  echo "<tr>".
                                                          "<td>".$row['date']."</td>".
                                                          "<td><a href='".$row['image']."' target='_blank'>".$row['image']."</a></td>".
                                                        "</tr>";
                                                }
                                             ?>
                                          </table>
                                        </div>
                                    <?php
                                  }
                                  else {

                               ?>
                              <div class="d-flex">
                                  <h4 class="card-title"><span class="lstick"></span>Select User to see details!</h4>
                              </div>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>

                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h3 class="text-themecolor">Tomorrow</h3>
                    </div>
                </div>

                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-4 col-xlg-3 col-md-5">
                        <div class="card">
                          <div class='card-body'>
                            <div class="d-flex">
                                <h4 class="card-title"><span class="lstick"></span>Select Person</h4>
                            </div>
                            <div class="message-box contact-box">
                                <div class="message-widget contact-widget">
                                <?php
                                    //where sdate >= '".date("Y-m-d")."' and edate <= '".date("Y-m-d")."'"
                                    $res = executeQuery("select name, phone, tid from planTrans where DATE(DATE_FORMAT(STR_TO_DATE(sdate, '%d-%m-%y'), '%Y-%m-%d')) <= DATE(DATE_FORMAT(STR_TO_DATE('".date("d-m-Y",strtotime("+1 day"))."', '%d-%m-%y'), '%Y-%m-%d')) and DATE(DATE_FORMAT(STR_TO_DATE(edate, '%d-%m-%y'), '%Y-%m-%d')) >= DATE(DATE_FORMAT(STR_TO_DATE('".date("d-m-Y",strtotime("+1 day"))."', '%d-%m-%y'), '%Y-%m-%d'))");
                                    //$res = executeQuery("select name, phone, tid from planTrans where sdate <= '".date("d-m-Y",strtotime("+1 day"))."' and edate >= '".date("d-m-Y",strtotime("+1 day"))."'");
                                    while($row = $res->fetch_assoc())
                                    {
                                    	$json = json_decode(mysqli_fetch_array(executeQuery("select dids from plans where pid in (select pid from planTrans where tid = ".$row['tid'].")"))[0]);
                                        foreach ($json as $key => $value){
                                          if(strpos($value,date("D"))!== false)
                                          {
                                            echo '<a href="?tod_tid='.$row['tid'].'">
                                                    <div class="user-img"> <img src="../assets/images/users/1.jpg" alt="user" class="img-circle"></div>
                                                    <div class="mail-contnet">
                                                        <h5>'.$row['name'].'</h5> <span class="pull-right">'.$row['tid'].'</span><span class="mail-desc">'.$row['phone'].'</span></div>
                                                 </a>';
                                          }
                                        }
                                        //echo '<a href="?tom_tid='.$row['tid'].'">
                                          //      <div class="user-img"> <img src="../assets/images/users/1.jpg" alt="user" class="img-circle"></div>
                                            //    <div class="mail-contnet">
                                              //      <h5>'.$row['name'].'</h5> <span class="mail-desc">'.$row['phone'].'</span></div>
                                             //</a>';
                                    }
                                ?>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-8 col-xlg-9 col-md-7">
                        <div class="card">
                            <div class="card-body">
                              <?php
                                  if(isset($_GET['tom_tid']))
                                  {
                                    $tid = $_GET['tom_tid'];
                                    $data = mysqli_fetch_array(executeQuery("select name, phone, address, time, type, mode, pid, sdate, edate from planTrans where tid=  $tid"));
                                    $type='';
                                    if($data[4]=='w')
                                      $type = 'Weekly';
                                    else if ($data[4]=='m')
                                      $type = 'Monthly';
                                    else
                                      $type = 'Trial Kit';

                                    echo '<div class="d-flex">
                                            <h4 class="card-title"><span class="lstick"></span>Plan Delivery Details [#'.$tid.']</h4>
                                        </div>';
                                        echo "<b>Name: </b>".$data[0]."<br/>
                                              <b>Phone: </b>".$data[1]."<br/>
                                              <b>Address: </b>".$data[2]."<br/>
                                              <b>Delivery Time: </b>".$data[3]."<br/>
                                              <b>Plan Type: </b>".$type."<br/>
                                              <b>Duration: </b>".$data[7]." - ".$data[8]."<br/>
                                              <b>Payment Mode: </b>".$data[5]."<br/>";
                                              $json = json_decode(mysqli_fetch_array(executeQuery("select dids from plans where pid = $data[6] "))[0]);
                                              foreach ($json as $key => $value){
                                                if(strpos($value,date("D",strtotime("+1 day")))!== false)
                                                {
                                                  $drink = mysqli_fetch_array(executeQuery("select name from drinks where did = $key"))[0];
                                                  echo "<b>Drink to deliver: </b>".$drink."<br/>";
                                                }
                                              }
                                    ?>
                                    <div class="table-responsive m-t-20">
                                        <table class="table vm no-th-brd no-wrap pro-of-month">
                                          <thead>
                                            <tr>
                                              <th>Plan Name</th>
                                              <th>Quantity</th>
                                              <th>Amount</th>
                                              <th>Delivery Time</th>
                                              <th>Status</th>
                                              <th>Mode</th>
                                            </tr>
                                          </thead>
                                          <?php
                                              $res = executeQuery("select plans.name, quantity, planTrans.amt, time, status, mode from planTrans join plans using(pid) where tid= $tid");
                                              while ($row = $res->fetch_assoc())
                                              {
                                                echo "<tr>".
                                                        "<td>".$row['name']."</td>".
                                                        "<td>".$row['quantity']."</td>".
                                                        "<td>".$row['amt']."</td>".
                                                        "<td>".$row['time']."</td>".
                                                        "<td>".$row['status']."</td>".
                                                        "<td>".$row['mode']."</td>".
                                                      "</tr>";
                                              }
                                           ?>
                                        </table>
                                      </div>
                                      <h4>Previously Delivered Drinks:</b>
                                      <div class="table-responsive m-t-20">
                                          <table class="table vm no-th-brd no-wrap pro-of-month">
                                            <thead>
                                              <tr>
                                                <th>Date</th>
                                                <th>Signature</th>
                                              </tr>
                                            </thead>
                                            <?php
                                                $res = executeQuery("select date,image from planSign where tid= $tid");
                                                while ($row = $res->fetch_assoc())
                                                {
                                                  echo "<tr>".
                                                          "<td>".$row['date']."</td>".
                                                          "<td><a href='".$row['image']."' target='_blank'>".$row['image']."</a></td>".
                                                        "</tr>";
                                                }
                                             ?>
                                          </table>
                                        </div>
                                    <?php
                                  }
                                  else {

                               ?>
                              <div class="d-flex">
                                  <h4 class="card-title"><span class="lstick"></span>Select User to see details! </h4>
                              </div>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
                <!-- Row -->
                <!-- ============================================================== -->
                <!-- End PAge Content -->
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
    <!-- Bootstrap tether Core JavaScript -->
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

    <script>
      setInterval(function(){
        $.getJSON("../server/index.php?check_new", function(result){
            if(result.type=="drink")
              alert('You got a new drink order !');
            else if (result.type=="plan")
              alert('you got a new plan order !');
        });
      },2000);
    </script>
</body>

</html>
