<?php
    require '../server/config.php';
    $params="";
    $status = "pending";
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

    if(isset($_POST['submit']))
    {
      $type = $_POST['updval'];
      $tid = $_POST['tid'];
      if($type!="complete")
      {
        $res = executeQuery("update transactions set tstatus = '$type' where tid = $tid");
        if($res)
          echo "<script>alert('Successfully Updated !');</script>";
        else
          echo "<script>alert('Please try again!');</script>";
      }
      else {
        # code...
        $con=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    		$cust = mysqli_fetch_array(mysqli_query($con,"select name, tid, sum(amt) from transactions join trandets using (tid) where tid = $tid group by tid"));
    		$res = mysqli_query($con,"update transactions set tstatus ='complete' where tid = $tid");
    		if($res)
    		{
    				$res = mysqli_query($con,"select reward_bonus, every_purchase, purchase_bonus from point_sch");
    				$rew = mysqli_fetch_array($res);
    				$phone = mysqli_fetch_array(mysqli_query($con,"select phone from transactions where tid = $tid"))[0];
    				$firstPur = mysqli_fetch_array(mysqli_query($con,"select firstbuy from users where phone = '$phone'"));
    				if($firstPur[0]=="0")
    				{
    					$res = mysqli_query($con,"update users set firstbuy = 1 where phone = '$phone'");
    					$res = mysqli_query($con,"update user_points set point = point + $rew[0] where phone = '$phone'");
    					$res = mysqli_query($con,"insert into user_points_desc values('$phone','".date("Y-m-d")."','credited','$rew[0]','Referal reward bonus')");
    					$refPhone = mysqli_fetch_array(mysqli_query($con, "select phone from users where ref = (select ref from referals where phone = '$phone')"))[0];
    					$res = mysqli_query($con,"update user_points set point = point + $rew[0] where phone = '$refPhone'");
    					$res = mysqli_query($con,"insert into user_points_desc values('$refPhone','".date("Y-m-d")."','credited','$rew[0]','Referal reward bonus')");
    					//echo "<br/>"."insert into user_points_desc values('$phone','".date("Y-m-d")."','credited','$rew[0]','Referal reward bonus'";
    				}
    				$total = mysqli_fetch_array(mysqli_query($con, "select sum(amt) from trandets where tid = $tid"))[0];
    				//echo "<br/>".$total." ".$rew[1]." <br/>";
    				if($total > $rew[1])
    				{
    					$res = mysqli_query($con,"update user_points set point = point + $rew[2] where phone = '$phone'");
    					$res = mysqli_query($con,"insert into user_points_desc values('$phone','".date("Y-m-d")."','credited','$rew[2]','Higher value purchase.')");
    				}
            echo "<script>alert('Successfully Updated!');</script>";
    		}
    		else
    			echo "<script>alert('Failed to Updated!');</script>";
    		mysqli_close($con);
      }
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
    <title>Drink Orders - FruizHUB</title>
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
    <style>
        .navi{
          display:flex;
        }
        .navi a {
          padding: 5px 15px 5px 15px;
          margin: 5px;
          background: aliceblue;
        }
        .navi a:hover{
          background: bisque;
          box-shadow: 0px 1px black;
        }
    </style>
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
                      <li> <a class="waves-effect waves-dark" href="dashboard.php" aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">Dashboard</span></a></li>
                      <li> <a class="waves-effect waves-dark" href="delivery-drink.php" aria-expanded="false"><i class="mdi mdi-truck"></i><span class="hide-menu">Drink Delivery</span></a></li>
                      <li> <a class="waves-effect waves-dark" href="delivery-planner.php" aria-expanded="false"><i class="mdi mdi-truck-delivery"></i><span class="hide-menu">Plan Delivery</span></a></li>
                      <li> <a class="waves-effect waves-dark active" href="drink-orders.php" aria-expanded="false"><i class="mdi mdi-cart-outline"></i><span class="hide-menu">Drink Orders</span></a></li>
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
                        <h3 class="text-themecolor">Drink Orders</h3>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Sales overview chart -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- ============================================================== -->
                    <!-- visit charts-->
                    <!-- ============================================================== -->
                    <div class="col-lg-3 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><span class="lstick"></span>Transaction Status</h4>
                                <table class="table vm font-14">
                                  <?php
                                      $params = "?status=";
                                      $res = executeQuery("select distinct(tstatus), count(tstatus)cnt from transactions GROUP by tstatus");
                                      while ($row = $res->fetch_assoc()) {
                                        # code...
                                        echo "<tr>".
                                                "<td><a href='$params".$row['tstatus']."'>".$row['tstatus']."</a></td>".
                                                "<td class='text-right font-medium'><i class='mdi mdi-cart-plus'></i> ".$row['cnt']."</td>".
                                              "</tr>";
                                      }
                                   ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div>
                                        <h3 class="card-title m-b-5"><span class="lstick"></span>All Transactions [<?php if(isset($_GET['status'])) echo $_GET['status']; else echo $status;?>]</h3>
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
                                          $params .= $status;
                                          if(isset($_GET["status"]))
                                          {
                                            $status = $_GET["status"];
                                            $params = "?status=".$status;
                                          }

                                          $pages = mysqli_fetch_array(executeQuery("select count(tid) from transactions where tstatus = '$status'"))[0];
                                          $pages = ceil($pages/5);

                                          // if(strpos($params, '?')!==false)
                                          //   $params = $params."&page=";
                                          // else
                                          //   $params = "?page=";
                                          $params = $params."&page=";
                                          echo "<div class='navi'>";
                                          for($i=0;$i<$pages;$i++)
                                          {
                                            echo "<a href='$params$i'>".($i+1)."</a>";
                                          }
                                          echo "</div>";

                                          if(isset($_GET['page']))
                                          {
                                            $i = $_GET['page'];
                                            $params .=$i;
                                            $res = executeQuery("select tid,name, dtime, phone, tmode, tdate from transactions where tstatus = '$status' order by tdate desc limit ".($i*5).",5");
                                            while ($row = $res->fetch_assoc())
                                            {
                                              echo "<tr>".
                                                      "<td><a href='$params&tid=".$row['tid']."'>".$row['tid']."</a></td>".
                                                      "<td>".$row['name']."</td>".
                                                      "<td>".$row['phone']."</td>".
                                                      "<td>".$row['dtime']."</td>".
                                                      "<td>".$row['tmode']."</td>".
                                                      "<td>".$row['tdate']."</td>".
                                                    "</tr>";
                                            }
                                          }
                                          else {
                                            # code...
                                            $res = executeQuery("select tid, name, dtime, phone, tmode, tdate from transactions where tstatus = '$status' order by tdate desc limit 0, 5");
                                            while ($row = $res->fetch_assoc())
                                            {
                                              echo "<tr>".
                                                      "<td><a href='$params&tid=".$row['tid']."'>".$row['tid']."</a></td>".
                                                      "<td>".$row['name']."</td>".
                                                      "<td>".$row['phone']."</td>".
                                                      "<td>".$row['dtime']."</td>".
                                                      "<td>".$row['tmode']."</td>".
                                                      "<td>".$row['tdate']."</td>".
                                                    "</tr>";
                                            }
                                          }
                                          // $res = executeQuery("select name, dtime, phone, tmode, tdate from transactions where tstatus = '$status' order by tdate desc");
                                          // while ($row = $res->fetch_assoc())
                                          // {
                                          //   echo "<tr>".
                                          //           "<td>".$row['name']."</td>".
                                          //           "<td>".$row['phone']."</td>".
                                          //           "<td>".$row['dtime']."</td>".
                                          //           "<td>".$row['tmode']."</td>".
                                          //           "<td>".$row['tdate']."</td>".
                                          //         "</tr>";
                                          // }
                                       ?>
                                    </table>
                                  </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    if(isset($_GET['tid']))
                    {
                        $tid = $_GET['tid'];
                ?>
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h3 class="text-themecolor">Transaction Details</h3>
                    </div>
                </div>
                <div class="row">
                    <!-- ============================================================== -->
                    <!-- visit charts-->
                    <!-- ============================================================== -->
                    <div class="col-lg-3 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><span class="lstick"></span>Update Transaction</h4>
                                <form submit = "<?php $_PHP_SELF?>" method="POST" class="form-horizontal form-material">
                                    <div class="form-group">
                                      <label for="sel1">Select status:</label>
                                      <select class="form-control" name="updval">
                                        <option value="pending">Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="canceled">Canceled</option>
                                        <option value="complete">Delivered</option>
                                      </select>
                                    </div>
                                    <input type="hidden" value="<?php echo $tid?>" name="tid"/>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input type="submit" value="Update !" name='submit' class="pull-right btn btn-success"/>
                                        </div>
                                    </div>
                              </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div>
                                        <h3 class="card-title m-b-5"><span class="lstick"></span>User: <?php $res = executeQuery("select name, phone, tdate,address from transactions where tid = $tid"); $data = mysqli_fetch_array($res); echo $data[0].", Phone: ".$data[1]." | Ordered On Date: ".$data[2].".<br/>Address: ".$data[3];?></h3>
                                    </div>
                                </div>
                                <div class="table-responsive m-t-20">
                                    <table class="table vm no-th-brd no-wrap pro-of-month">
                                      <thead>
                                        <tr>
                                          <th>Name</th>
                                          <th>Quantity</th>
                                          <th>Amount</th>
                                          <th>Delivery Time</th>
                                          <th>Status</th>
                                          <th>Mode</th>
                                        </tr>
                                      </thead>
                                      <?php

                                            # code...
                                          $res = executeQuery("select drinks.name, cnt, amt, dtime, tstatus, tmode from transactions join trandets using (tid) join drinks using (did) where tid = $tid");
                                          while ($row = $res->fetch_assoc())
                                          {
                                            echo "<tr>".
                                                    "<td>".$row['name']."</td>".
                                                    "<td>".$row['cnt']."</td>".
                                                    "<td>".$row['amt']."</td>".
                                                    "<td>".$row['dtime']."</td>".
                                                    "<td>".$row['tmode']."</td>".
                                                    "<td>".$row['tstatus']."</td>".
                                                  "</tr>";
                                          }

                                          // $res = executeQuery("select name, dtime, phone, tmode, tdate from transactions where tstatus = '$status' order by tdate desc");
                                          // while ($row = $res->fetch_assoc())
                                          // {
                                          //   echo "<tr>".
                                          //           "<td>".$row['name']."</td>".
                                          //           "<td>".$row['phone']."</td>".
                                          //           "<td>".$row['dtime']."</td>".
                                          //           "<td>".$row['tmode']."</td>".
                                          //           "<td>".$row['tdate']."</td>".
                                          //         "</tr>";
                                          // }
                                       ?>
                                    </table>
                                  </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                ?>
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
    </script>
    
</body>

</html>
