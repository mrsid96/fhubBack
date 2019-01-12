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

    if(isset($_POST['data-update']))
    {
        $did = $_POST['did'];
        $name = $_POST['name'];
        $des = $_POST['des'];
        $ben = $_POST['ben'];
        $nut = $_POST['nut'];
        $ing = $_POST['ing'];
        $price = $_POST['price'];
        $res = executeQuery("update drinks set name = '$name', des = '$des', ben ='$ben', nut='$nut', ing='$ing', price='$price' where did = $did");
        if($res)
          echo "<script>alert('Successfully Updated!');window.open('drinks.php','_parent');</script>";
        else
          echo "<script>alert('Failed to Update!');</script>";
    }

    if(isset($_POST['img-update']))
    {
      $did = $_POST['did'];
      $target_path = "../img/";
      $target_path = $target_path . "img".date("YmdGis").basename( $_FILES['image']['name']);
      $imgPath = "http://api.fruizhub.com/img/"."img".date("YmdGis").basename( $_FILES['image']['name']);
      if(move_uploaded_file($_FILES['image']['tmp_name'], $target_path)){
          $res = executeQuery("update drinks set img = '$imgPath' where did = $did");
          //$res = mysqli_query($con,"insert into drinks (name,des,ben,nut,ing,price,img) values ('$name','$des','$ben','$nut','$ing','$price','$imgPath') ");
          if($res)
            echo "<script>alert('Successfully Updated!');window.open('drinks.php','_parent');</script>";
          else
            echo "<script>alert('Failed to Update!');</script>";
      }
  }

    if(isset($_POST['file-upload']))
    {
      $target_path = "../img/";
      $target_path = $target_path . "img".date("YmdGis").basename( $_FILES['image']['name']);
      $imgPath = "http://api.fruizhub.com/img/"."img".date("YmdGis").basename( $_FILES['image']['name']);
      if(move_uploaded_file($_FILES['image']['tmp_name'], $target_path)){
          $name = $_POST['name'];
          $des = $_POST['desc'];
          $ben = $_POST['ben'];
          $nut = $_POST['nut'];
          $ing = $_POST['ing'];
          $price = $_POST['price'];
          $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
          $res = mysqli_query($con,"insert into drinks (name,des,ben,nut,ing,price,img) values ('$name','$des','$ben','$nut','$ing','$price','$imgPath') ");
          if($res)
          {
              echo "<script>alert('Successfully Uploaded!');</script>";
          }
      } else {
          echo "<script>alert('There was an error uploading the file, please try again!');</script>";
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
    <title>Drinks - FruizHUB</title>
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
                      <li> <a class="waves-effect waves-dark" href="drink-orders.php" aria-expanded="false"><i class="mdi mdi-cart-outline"></i><span class="hide-menu">Drink Orders</span></a></li>
                      <li> <a class="waves-effect waves-dark" href="plan-orders.php" aria-expanded="false"><i class="mdi mdi-cart"></i><span class="hide-menu">Planner Orders</span></a></li>
                      <li> <a class="waves-effect waves-dark active" href="drinks.php" aria-expanded="false"><i class="mdi mdi-glass-mug"></i><span class="hide-menu">Drinks Manager</span></a></li>
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
                        <h3 class="text-themecolor">Drinks Manager</h3>
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
                                <h4 class="card-title"><span class="lstick"></span>New Drink</h4>
                                <form submit = "drinks.php" method="post" enctype="multipart/form-data" class="form-horizontal form-material">
                                    <div class="form-group">
                                        <label class="col-md-12">Drink Name</label>
                                        <div class="col-md-12">
                                            <input type="text" name = "name" placeholder="Delecious Drink" class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-md-12">Drink Description</label>
                                      <div class="col-md-12">
                                          <input type="text" name = "desc" placeholder="Description for drink" class="form-control form-control-line">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-md-12">Drink Benifits</label>
                                      <div class="col-md-12">
                                          <input type="text" name = "ben" placeholder="Benifits -> Drink" class="form-control form-control-line">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-md-12">Drink Nutrients</label>
                                      <div class="col-md-12">
                                          <input type="text" name = "nut" placeholder="Nutrients -> Drink" class="form-control form-control-line">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-md-12">Drink Ingredients</label>
                                      <div class="col-md-12">
                                          <input type="text" name = "ing" placeholder="Ingredients -> Drink" class="form-control form-control-line">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-md-12">Drink Price</label>
                                      <div class="col-md-12">
                                          <input type="text" name = "price" placeholder="Price of Drink" class="form-control form-control-line">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-md-12">Drink Image</label>
                                      <div class="col-md-12">
                                          <input class="form-control" type="file" name="image">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input type="submit" name="file-upload" class="btn btn-success" value="Add Drink"/>
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
                                        <h3 class="card-title m-b-5"><span class="lstick"></span>All Drinks</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="drinkEdit">
                            <?php
                                if(isset($_GET['update']))
                                {
                                    $con = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
                                    $res = mysqli_query($con, "select name, des, ben, nut, ing, price, img from drinks where did = ".$_GET['update']);//.$_POST['edit_id']);
                                    $drink = mysqli_fetch_array($res);
                                    ?>

                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title"><span class="lstick"></span>Edit Drink Details</h4>
                                                <form submit = "drinks.php" method="post" enctype="multipart/form-data" class="form-horizontal form-material">
                                                    <input type="hidden" name="did" value="<?php echo $_GET['update']; ?>"/>
                                                    <div class="form-group">
                                                        <label class="col-md-12">Drink Name</label>
                                                        <div class="col-md-12">
                                                            <input type="text" name = "name" placeholder="Delecious Drink" value="<?php echo $drink[0];?>" class="form-control form-control-line">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                      <label class="col-md-12">Drink Description</label>
                                                      <div class="col-md-12">
                                                          <input type="text" name = "desc" placeholder="Description for drink" value="<?php echo $drink[1];?>" class="form-control form-control-line">
                                                      </div>
                                                    </div>
                                                    <div class="form-group">
                                                      <label class="col-md-12">Drink Benifits</label>
                                                      <div class="col-md-12">
                                                          <input type="text" name = "ben" placeholder="Benifits -> Drink" value="<?php echo $drink[2];?>" class="form-control form-control-line">
                                                      </div>
                                                    </div>
                                                    <div class="form-group">
                                                      <label class="col-md-12">Drink Nutrients</label>
                                                      <div class="col-md-12">
                                                          <input type="text" name = "nut" placeholder="Nutrients -> Drink" value="<?php echo $drink[3];?>" class="form-control form-control-line">
                                                      </div>
                                                    </div>
                                                    <div class="form-group">
                                                      <label class="col-md-12">Drink Ingredients</label>
                                                      <div class="col-md-12">
                                                          <input type="text" name = "ing" placeholder="Ingredients -> Drink" value="<?php echo $drink[4];?>" class="form-control form-control-line">
                                                      </div>
                                                    </div>
                                                    <div class="form-group">
                                                      <label class="col-md-12">Drink Price</label>
                                                      <div class="col-md-12">
                                                          <input type="text" name = "price" placeholder="Price of Drink" value="<?php echo $drink[5];?>" class="form-control form-control-line">
                                                      </div>
                                                    </div>
                                                    <div class="form-group">
                                                      <label class="col-md-12">Drink Image</label>
                                                      <div class="col-md-12">
                                                          <input class="form-control" type="file" name="image">
                                                      </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <input type="submit" name="data-update" class="btn btn-success" value="Update Drink Details"/>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title"><span class="lstick"></span>Edit Drink Image </h4>
                                                <form submit = "drinks.php" method="post" enctype="multipart/form-data" class="form-horizontal form-material">
                                                    <input type="hidden" name="did" value="<?php echo $_GET['update']; ?>"/>
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <img src="<?php echo $drink[6];?>" style="width:100%"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                      <label class="col-md-12">Drink Image</label>
                                                      <div class="col-md-12">
                                                          <input class="form-control" type="file" name="image">
                                                      </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <input type="submit" name="img-update" class="btn btn-success" value="Update Drink Image"/>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                               }
                            ?>
                        </div>
                        <div class="row" id="drinkHolder">
                        <?php
                            $con = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
                            $res = mysqli_query($con, "select did, name, des, ben, nut, ing, price, img from drinks");
                            $count = 0;
                            while($row = mysqli_fetch_array($res))
                            {
                              if($count%2 == 0)
                              {
                              ?>
                                <div class="col-sm-6">
                                  <div class = 'card'>
                                    <div class="card-body">
                                      <div class="row">
                                          <div class="col-sm-4">
                                              <img class='' style="width: 100%;" src="<?php echo $row["img"]; ?>"/>
                                          </div>
                                          <div class="col-sm-8">
                                              <b>Name: </b><?php echo $row["name"]; ?>, <b>Price: </b><?php echo $row["price"];?><br/>
                                              <b>Description: </b><?php echo $row["des"]; ?><br/>
                                              <b>Benifits: </b><?php echo $row["ben"]; ?><br/>
                                              <b>Nutrients: </b><?php echo $row["nut"]; ?><br/>
                                              <b>Ingredients: </b><?php echo $row["ing"]; ?></br/>
                                              <a href="?delete=<?php echo $row["did"];?>" style="width:45%;" class="btn btn-danger pull-right">Delete</a>
                                              <a href="?update=<?php echo $row["did"];?>" style="width:45%;" class="btn btn-primary pull-left">Update</a>
                                          </div>
                                      </div>
                                  </div>
                                </div>
                              </div>
                              <?php
                              }
                              else {
                              ?>
                                  <div class="col-sm-6">
                                    <div class = 'card'>
                                      <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <img class='' style="width: 100%;" src="<?php echo $row["img"]; ?>"/>
                                            </div>
                                            <div class="col-sm-8">
                                                <b>Name: </b><?php echo $row["name"]; ?>, <b>Price: </b><?php echo $row["price"];?><br/>
                                                <b>Description: </b><?php echo $row["des"]; ?><br/>
                                                <b>Benifits: </b><?php echo $row["ben"]; ?><br/>
                                                <b>Nutrients: </b><?php echo $row["nut"]; ?><br/>
                                                <b>Ingredients: </b><?php echo $row["ing"]; ?></br/>
                                                <a href="?delete=<?php echo $row["did"];?>" style="width:45%;" class="btn btn-danger pull-right">Delete</a>
                                                <a href="?update=<?php echo $row["did"];?>" style="width:45%;" class="btn btn-primary pull-left">Update</a>
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                                </div>
                              <?php
                              }
                              $count = $count + 1;
                            }
                        ?>
                      </div>
                <!-- </div>
                        </div> -->
                    </div>
                </div>
                <?php
                    if(isset($_GET['delete']))
                    {
                        $did = $_GET['delete'];
                        $res = executeQuery("delete from drinks where did = '$did'");
                        if($res)
                          echo "<script>alert('Successfully Deleted!');window.open('drinks.php','_parent');</script>";
                        else
                          echo "<script>alert('Failed to Deleted!');</script>";
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
