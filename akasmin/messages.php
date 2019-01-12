<?php
    require '../server/config.php';
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
    <title>Messages - FruizHUB</title>
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
                      <li> <a class="waves-effect waves-dark" href="delivery-planner.php" aria-expanded="false"><i class="mdi mdi-truck-delivery"></i><span class="hide-menu">Plan Delivery</span></a></li>
                      <li> <a class="waves-effect waves-dark" href="drink-orders.php" aria-expanded="false"><i class="mdi mdi-cart-outline"></i><span class="hide-menu">Drink Orders</span></a></li>
                      <li> <a class="waves-effect waves-dark" href="plan-orders.php" aria-expanded="false"><i class="mdi mdi-cart"></i><span class="hide-menu">Planner Orders</span></a></li>
                      <li> <a class="waves-effect waves-dark" href="drinks.php" aria-expanded="false"><i class="mdi mdi-glass-mug"></i><span class="hide-menu">Drinks Manager</span></a></li>
                      <li> <a class="waves-effect waves-dark" href="planner.php" aria-expanded="false"><i class="mdi mdi-calendar-multiple"></i><span class="hide-menu">Plans Manager</span></a></li>
                      <li> <a class="waves-effect waves-dark" href="trial.php" aria-expanded="false"><i class="mdi mdi-calendar-multiple"></i><span class="hide-menu">Combo Kit Manager</span></a></li>
                      <li> <a class="waves-effect waves-dark active" href="messages.php" aria-expanded="false"><i class="mdi mdi-message-text"></i><span class="hide-menu">Messages</span></a></li>
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
                        <h3 class="text-themecolor">Messages</h3>
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
                                <h4 class="card-title"><span class="lstick"></span>My Contact</h4>
                            </div>
                            <div class="message-box contact-box">
                                <div class="message-widget contact-widget">
                                <?php
                                    $res = executeQuery("select distinct(name), phone from users join messages using (phone)");
                                    while($row = $res->fetch_assoc())
                                    {
                                        echo '<a href="?phone='.$row['phone'].'">
                                                <div class="user-img"> <img src="../assets/images/users/1.jpg" alt="user" class="img-circle"></div>
                                                <div class="mail-contnet">
                                                    <h5>'.$row['name'].'</h5> <span class="mail-desc">'.$row['phone'].'</span></div>
                                             </a>';
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
                                  if(isset($_GET['phone']))
                                  {
                                    $phone = $_GET['phone'];
                                    $name = mysqli_fetch_array(executeQuery("select name from users where phone='$phone'"))[0];
                                    $res = executeQuery("select message, mdate from messages where phone='$phone'");
                                    echo '<div class="d-flex">
                                            <h4 class="card-title"><span class="lstick"></span>Message From: '.$name.', Phone: '.$phone.'</h4>
                                        </div>';
                                    while($row = $res->fetch_assoc())
                                    {
                                      echo "<div class='card-body'>Message: <i>".$row['message']."</i><br/><span style='text-align: right!important;display: block;font-family: cursive;font-size: smaller;'>[".$row['mdate']."]</span></div><hr>";
                                    }
                                  }
                                  else {

                               ?>
                              <div class="d-flex">
                                  <h4 class="card-title"><span class="lstick"></span>Select User to see messages! </h4>
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
