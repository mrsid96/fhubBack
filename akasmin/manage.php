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

    if(isset($_POST['submit-point']))
    {
        $con = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        $res = mysqli_query($con, "update point_sch set new_user = '".$_POST['new_user']."', reward_bonus = '".$_POST['reward']."', every_purchase= '".$_POST['every']."', purchase_bonus='".$_POST['point']."', ten_point='".$_POST['rupees']."'");
        mysqli_close($con);
        if($res)
            echo "<script>alert('Updated Successfully');</script>";
        else
            echo "<script>alert('Failed to change');</script>";
    }

    if(isset($_POST['submit-news'])){
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con,"insert into news (item) values('".$_POST['item']."')");
        mysqli_close($con);
        if($res)
            echo "<script>alert('Successfully Uploaded!');window.open('manage.php','_parent');</script>";
        else
            echo "<script>alert('Please try again!');window.open('manage.php','_parent');</script>";
    }

    if(isset($_GET['delete-news']))
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con,"delete from news where nid = '".$_GET['delete-news']."' ");
        mysqli_close($con);
        if($res)
            echo "<script>alert('Successfully Deleted!');window.open('manage.php','_parent');</script>";
        else
            echo "<script>alert('Please try again!');window.open('manage.php','_parent');</script>";
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
    <title>Manage - FruizHUB</title>
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
                      <li> <a class="waves-effect waves-dark" href="messages.php" aria-expanded="false"><i class="mdi mdi-message-text"></i><span class="hide-menu">Messages</span></a></li>
                      <li> <a class="waves-effect waves-dark" href="banner.php" aria-expanded="false"><i class="mdi mdi-calendar-multiple"></i><span class="hide-menu">Banner Manager</span></a></li>
                      <li> <a class="waves-effect waves-dark active" href="manage.php" aria-expanded="false"><i class="mdi mdi-desktop-mac"></i><span class="hide-menu">Manage System</span></a></li>
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
                        <h3 class="text-themecolor">Manage System</h3>
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
                    <div class="col-lg-6 col-xlg-6 col-md-6">
                        <div class="card">
                          <div class='card-body'>
                            <div class="d-flex">
                                <h4 class="card-title"><span class="lstick"></span>Points System</h4>
                            </div>
                            <center><i>-- Existing System --</i></center><hr style="margin-top: 10px; margin-bottom: 10px;" />
                              <table class="table table-hover">
                                  <?php
                                      $con = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
                                      $res = mysqli_query($con, "select * from point_sch");
                                      $row = mysqli_fetch_array($res);
                                      ?>
                                          <tr><td><b>New User Bonus:</b> <?php echo $row[0]; ?> Points</td></tr>
                                          <tr><td><b>Reward Bonus:</b> <?php echo $row[1]; ?> Points</td></tr>
                                          <tr><td><b>For Every </b> <?php echo $row[2]; ?> Rs, <b><?php echo $row[3]; ?></b> Points</td></tr>
                                          <tr><td><b>For Every 10 Points, </b> <?php echo $row[4]; ?> Rs</td></tr>
                                      <?php
                                  ?>
                              </table>

                              <center><i>-- Update Point System --</i></center><hr style="margin-top: 10px; margin-bottom: 10px;" />
                              <form action="manage.php" method="post" enctype="multipart/form-data"  class="form-horizontal form-material">
                                  <div class="form-group">
                                      <label class="col-md-12">New user registration reward</label>
                                      <div class="col-md-12">
                                          <input type="text" class="form-control form-control-line" name='new_user' value="<?php echo $row[0];?>" placeholder="New user registration reward">
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label class="col-md-12">Referal Rewards</label>
                                      <div class="col-md-12">
                                          <input type="text" class="form-control form-control-line" name='reward' value="<?php echo $row[1];?>" placeholder="Referal Rewards">
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label class="col-md-12">For each minimum amount</label>
                                      <div class="col-md-12">
                                          <input type="text" class="form-control form-control-line" name='every' value="<?php echo $row[2];?>" placeholder="For each minimum amount">
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label class="col-md-12">Respective points for above purchase</label>
                                      <div class="col-md-12">
                                          <input type="text" class="form-control form-control-line" name='point' value="<?php echo $row[3];?>" placeholder="Respective points for above purchase">
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label class="col-md-12">Ruppes per 10 points</label>
                                      <div class="col-md-12">
                                          <input type="text" class="form-control form-control-line" name='rupees' value="<?php echo $row[4];?>" placeholder="Ruppes per 10 points">
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <div class="col-sm-12">
                                          <input type="submit" name="submit-point" class="btn btn-success" value="Update Point Scheme"/>
                                      </div>
                                  </div>
                              </form>
                          </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-6 col-xlg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                              <div class="d-flex">
                                  <h4 class="card-title"><span class="lstick"></span>News System </h4>
                              </div>
                              <center><i>-- News Article (160 chars) --</i></center><hr style="margin-top: 10px; margin-bottom: 10px;" />
                              <form action="manage.php" method="post">
                                  <div class="form-group">
                                      <textarea class="form-control" maxlength="160" rows="5" name="item"></textarea>
                                  </div>
                                  <div class="form-group">
                                      <input class="btn btn-success" style="width:100%" type="submit" value="Add news" name="submit-news">
                                  </div>
                              </form>
                            </div>
                        </div>

                        <center><i>-- Latest news for users --</i></center><hr style="margin-top: 10px; margin-bottom: 10px;" />
                        <?php
                              $con = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
                              $res = mysqli_query($con, "select nid, item from news");
                              mysqli_close($con);
                              while($row = mysqli_fetch_array($res))
                              {
                              ?>
                                  <div class='card'>
                                      <div class='card-body'>
                                          <p>"<i><?php echo $row["item"]; ?>"</i></p>
                                          Delete News Item : <span class="pull-right"><a href="?delete-news=<?php echo $row["nid"];?>">Yes</a></span>
                                      </div>
                                  </div>
                              <?php
                              }
                          ?>

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
