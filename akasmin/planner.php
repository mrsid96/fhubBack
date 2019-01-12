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

    if(isset($_POST['plan_image']))
    {
        $pid = $_POST['plan_img_pid'];
        $target_path = "../img/";
        $target_path = $target_path . "img".date("YmdGis").basename( $_FILES['imageUpd']['name']);
        $imgPath = "http://api.fruizhub.com/img/"."img".date("YmdGis").basename( $_FILES['imageUpd']['name']);
        if(move_uploaded_file($_FILES['imageUpd']['tmp_name'], $target_path)){
            $res = executeQuery("update plans set img = '$imgPath' where pid = $pid");
            if($res)
                echo "<script>alert('Successfully Uploaded')</script>";
            else
                echo "<script>alert('Failed to update !')</script>";
        }
    }

    if(isset($_POST['plan_details']))
    {
        $pid = $_POST['plan_pid'];
        $name = $_POST['nameUpd'];
        $desc = $_POST['descUpd'];
        $price = $_POST['priceUpd'];
        $type = $_POST['typeUpd'];
        $res = executeQuery("update plans set name = '$name', pdesc = '$desc', amt = '$price', type = '$type' where pid = $pid");
        if($res)
            echo "<script>alert('Successfully Updated !')</script>";
        else
            echo "<script>alert('Failed to Update!')</script>";
    }

    if(isset($_POST['days_update']))
    {
        $pid=$_POST['pid_days_update'];
        $did=array();
        $drinks = executeQuery("select did, name from drinks");
        $data="";
        $flag = 0;
        while ($row = $drinks->fetch_assoc()) {
          if(!empty($_POST[$row['did']."drinkUpd"]))
          {
              $data="";
              foreach($_POST[$row['did']."drinkUpd"] as $selected) {
                  $data .= $selected.",";
              }
              $arr[$row['did']]=substr($data,0,-1);
              $res = executeQuery("update plans set dids = '".json_encode($arr)."' where pid = $pid");
              if($res)
                $flag = 1;
              else
               $flag = 0;
          }
        }
        if($flag == 1)
            echo "<script>alert('Successfully Updated !')</script>";
        else
            echo "<script>alert('Failed to Update!')</script>";

    }

    if(isset($_POST['file-upload']))
    {
      $target_path = "../img/";
      $target_path = $target_path . "img".date("YmdGis").basename( $_FILES['image']['name']);
      $imgPath = "http://api.fruizhub.com/img/"."img".date("YmdGis").basename( $_FILES['image']['name']);
      if(move_uploaded_file($_FILES['image']['tmp_name'], $target_path)){
          $name = $_POST['name'];
          $des = $_POST['desc'];
          $price = $_POST['price'];
          $type = $_POST['type'];
          $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
          $did=array();
          $res = executeQuery("select did, name from drinks");
          $data="";
          while ($row = $res->fetch_assoc()) {
            if(!empty($_POST[$row['did']."drink"]))
            {
                $data="";
                foreach($_POST[$row['did']."drink"] as $selected) {
                    $data .= $selected.",";
                }
                $arr[$row['did']]=substr($data,0,-1);
            }
          }
          $res = mysqli_query($con,"insert into plans (img,name,pdesc,dids,amt,type) values ('$imgPath','$name','$des','".json_encode($arr)."','$price','$type')");
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
    <title>Planner - FruizHUB</title>
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
        $md-checkbox-margin: 16px 0;
        $md-checkbox-checked-color: rgb(51, 122, 183);
        $md-checkbox-border-color: rgba(0, 0, 0, 0.54);
        $md-checkbox-border-color-disabled: rgba(0, 0, 0, 0.26);
        $md-checkbox-checked-color-disabled: rgba(0, 0, 0, 0.26);

        $md-checkbox-size: 20px;
        $md-checkbox-padding: 4px;

        $md-checkmark-width: 2px;
        $md-checkmark-color: #fff;

        .md-checkbox {
          position: relative;
          margin: $md-checkbox-margin;
          text-align: left;

          &.md-checkbox-inline {
            display: inline-block;
          }

          label {
            cursor: pointer;
            padding-right:24px;
            &:before, &:after {
              content: "";
              position: absolute;
              left:0;
              top: 0;
            }

            &:before {
              // box
              width: $md-checkbox-size;
              height: $md-checkbox-size;
              background: #fff;
              border: 2px solid $md-checkbox-border-color;
              border-radius: 2px;
              cursor: pointer;
              transition: background .3s;
            }

            &:after {
              // checkmark
            }
          }

          input[type="checkbox"] {
            outline: 0;
            margin-right: $md-checkbox-size - 10px;
            visibility: hidden;

            &:checked {
               + label:before{
                background: $md-checkbox-checked-color;
                border:none;
              }
              + label:after {

                $md-checkmark-size: $md-checkbox-size - 2*$md-checkbox-padding;

                transform: rotate(-45deg);

                top: ($md-checkbox-size / 2) - ($md-checkmark-size / 4) - $md-checkbox-size/10;
                left: $md-checkbox-padding;
                width: $md-checkmark-size;
                height: $md-checkmark-size / 2;

                border: $md-checkmark-width solid $md-checkmark-color;
                border-top-style: none;
                border-right-style: none;
              }
            }

            &:disabled {
              + label:before{
                border-color: $md-checkbox-border-color-disabled;
              }
              &:checked {
                + label:before{
                  background: $md-checkbox-checked-color-disabled;
                }
              }
            }
          }

        }

        // *************************************

        // *************************************
        *, *:before, *:after {
          box-sizing: border-box;
        }

        section {
          background:white;
          margin:0 auto;
          padding: 4em;
          max-width: 800px;
          h1 {
            margin: 0 0 2em;
          }
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
                      <li> <a class="waves-effect waves-dark" href="drinks.php" aria-expanded="false"><i class="mdi mdi-glass-mug"></i><span class="hide-menu">Drinks Manager</span></a></li>
                      <li> <a class="waves-effect waves-dark active" href="planner.php" aria-expanded="false"><i class="mdi mdi-calendar-multiple"></i><span class="hide-menu">Plans Manager</span></a></li>
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
                        <h3 class="text-themecolor">Plan Manager</h3>
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
                    <div class="col-lg-4 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title"><span class="lstick"></span>New Plan</h4>
                                <form submit = "planner.php" method="post" enctype="multipart/form-data" class="form-horizontal form-material">
                                    <div class="form-group">
                                        <label class="col-md-12">Plan Name</label>
                                        <div class="col-md-12">
                                            <input type="text" name = "name" placeholder="Delecious Plan" class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-md-12">Plan Description</label>
                                      <div class="col-md-12">
                                          <input type="text" name = "desc" placeholder="Description for Plan" class="form-control form-control-line">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-md-12">Plan Price</label>
                                      <div class="col-md-12">
                                          <input type="text" name = "price" placeholder="Price of Plan" class="form-control form-control-line">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-md-12">Plan Type</label>
                                      <div class="col-md-12">
                                          <select class="form-control" name="type">
                                            <option value="w">Weekly</option>
                                            <option value="m">Monthly</option>
                                          </select>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <div class="col-md-12">
                                        <?php
                                            $res = executeQuery("select did, name from drinks");
                                            while($row = $res -> fetch_assoc())
                                            {
                                                echo "<h5>Plan name: ".$row['name']."</h5>";
                                                echo "<div class='md-checkbox md-checkbox-inline'>".
                                                        "<input id='".$row['name']."0' name='".$row['did']."drink[]' type='checkbox' value='Mon'>".
                                                            "<label style='padding-right:4px' for='".$row['name']."0'>Mon</label>".
                                                        "<input id='".$row['name']."1' name='".$row['did']."drink[]' type='checkbox' value='Tue'>".
                                                            "<label style='padding-right:4px' for='".$row['name']."1'>Tue</label>".
                                                        "<input id='".$row['name']."2' name='".$row['did']."drink[]' type='checkbox' value='Wed'>".
                                                            "<label style='padding-right:4px' for='".$row['name']."2'>Web</label>".
                                                        "<input id='".$row['name']."3' name='".$row['did']."drink[]' type='checkbox' value='Thur'>".
                                                            "<label style='padding-right:4px' for='".$row['name']."3'>Thur</label></div>".
                                                     "<div class='md-checkbox md-checkbox-inline'>".
                                                        "<input id='".$row['name']."4' name='".$row['did']."drink[]' type='checkbox' value='Fri'>".
                                                            "<label style='padding-right:4px' for='".$row['name']."4'>Fri</label>".
                                                        "<input id='".$row['name']."5' name='".$row['did']."drink[]' type='checkbox' value='Sat'>".
                                                            "<label style='padding-right:4px' for='".$row['name']."5'>Sat</label>".
                                                        "<input id='".$row['name']."6' name='".$row['did']."drink[]' type='checkbox' value='Sun'>".
                                                            "<label style='padding-right:4px' for='".$row['name']."6'>Sun</label>".
                                                    "</div>";
                                                echo "<hr>";
                                            }
                                        ?>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="col-md-12">Plan Image</label>
                                      <div class="col-md-12">
                                          <input class="form-control" type="file" name="image">
                                      </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <input type="submit" name="file-upload" class="btn btn-success" value="Add Plan"/>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div>
                                        <h3 class="card-title m-b-5"><span class="lstick"></span>All Plans</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                                if(isset($_GET['update']))
                                {
                                    $res = executeQuery("select pid, img, name, pdesc, dids, amt, type from plans where pid =".$_GET['update']);
                                    $plan = mysqli_fetch_array($res);
                            ?>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title"><span class="lstick"></span>Edit Plan</h4>
                                        <form submit = "planner.php" method="post" enctype="multipart/form-data" class="form-horizontal form-material">
                                            <input type="hidden" value="<?php echo $_GET['update'];?>" name="plan_pid"/>
                                            <div class="form-group">
                                                <label class="col-md-12">Plan Name</label>
                                                <div class="col-md-12">
                                                    <input type="text" name = "nameUpd" placeholder="Delecious Plan" value="<?php echo $plan[2];?>" class="form-control form-control-line">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                              <label class="col-md-12">Plan Description</label>
                                              <div class="col-md-12">
                                                  <input type="text" name = "descUpd" placeholder="Description for Plan" value="<?php echo $plan[3];?>" class="form-control form-control-line">
                                              </div>
                                            </div>
                                            <div class="form-group">
                                              <label class="col-md-12">Plan Price</label>
                                              <div class="col-md-12">
                                                  <input type="text" name = "priceUpd" placeholder="Price of Plan" value="<?php echo $plan[5];?>" class="form-control form-control-line">
                                              </div>
                                            </div>
                                            <div class="form-group">
                                              <label class="col-md-12">Plan Type</label>
                                              <div class="col-md-12">
                                                  <select class="form-control" name="typeUpd">
                                                    <option value="w" <?php if ($plan[6]=="w") echo "selected='selected'";?> >Weekly</option>
                                                    <option value="m" <?php if ($plan[6]=="m") echo "selected='selected'";?> >Monthly</option>
                                                  </select>
                                              </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <input type="submit" name="plan_details" class="btn btn-success" value="Update Details"/>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title"><span class="lstick"></span>Edit plan Image</h4>
                                        <form submit = "planner.php" method="post" enctype="multipart/form-data" class="form-horizontal form-material">
                                            <input type="hidden" name="plan_img_pid" value="<?php echo $_GET['update']; ?>"/>
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <img src="<?php echo $plan[1];?>" style="width:100%"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                              <label class="col-md-12">Plan Image</label>
                                              <div class="col-md-12">
                                                  <input class="form-control" type="file" name="imageUpd">
                                              </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <input type="submit" name="plan_image" class="btn btn-success" value="Update Image"/>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title"><span class="lstick"></span>Edit plan days</h4>
                                        <?php
                                            echo "Current Delivery Scheme:<br/>";
                                            $dids = json_decode(mysqli_fetch_array(executeQuery("select dids from plans where pid = ".$_GET['update']))[0]);
                                            foreach($dids as $key => $val) {
                                                echo '<strong>'.mysqli_fetch_array(executeQuery("select name from drinks where did = ".$key))[0] . '</strong>: ' . $val;
                                                echo '<br>';
                                            }
                                            echo "<hr>"
                                         ?>

                                         <h4 class="card-title"><span class="lstick"></span>Change plan days</h4>
                                        <form submit = "planner.php" method="post" enctype="multipart/form-data" class="form-horizontal form-material">
                                            <input type="hidden" value="<?php echo $_GET['update']; ?>" name="pid_days_update"/>
                                            <div class="form-group">
                                              <div class="col-md-12">
                                                <?php
                                                    $res = executeQuery("select did, name from drinks");
                                                    while($row = $res -> fetch_assoc())
                                                    {
                                                        echo "<h5>Plan name: ".$row['name']."</h5>";
                                                        echo "<div class='md-checkbox md-checkbox-inline'>".
                                                                "<input id='".$row['name']."0upd' name='".$row['did']."drinkUpd[]' type='checkbox' value='Mon'>".
                                                                    "<label style='padding-right:4px' for='".$row['name']."0upd'>Mon</label>".
                                                                "<input id='".$row['name']."1upd' name='".$row['did']."drinkUpd[]' type='checkbox' value='Tue'>".
                                                                    "<label style='padding-right:4px' for='".$row['name']."1upd'>Tue</label>".
                                                                "<input id='".$row['name']."2upd' name='".$row['did']."drinkUpd[]' type='checkbox' value='Wed'>".
                                                                    "<label style='padding-right:4px' for='".$row['name']."2upd'>Web</label>".
                                                                "<input id='".$row['name']."3upd' name='".$row['did']."drinkUpd[]' type='checkbox' value='Thur'>".
                                                                    "<label style='padding-right:4px' for='".$row['name']."3upd'>Thur</label></div>".
                                                             "<div class='md-checkbox md-checkbox-inline'>".
                                                                "<input id='".$row['name']."4upd' name='".$row['did']."drinkUpd[]' type='checkbox' value='Fri'>".
                                                                    "<label style='padding-right:4px' for='".$row['name']."4upd'>Fri</label>".
                                                                "<input id='".$row['name']."5upd' name='".$row['did']."drinkUpd[]' type='checkbox' value='Sat'>".
                                                                    "<label style='padding-right:4px' for='".$row['name']."5upd'>Sat</label>".
                                                                "<input id='".$row['name']."6upd' name='".$row['did']."drinkUpd[]' type='checkbox' value='Sun'>".
                                                                    "<label style='padding-right:4px' for='".$row['name']."6upd'>Sun</label>".
                                                            "</div>";
                                                        echo "<hr>";
                                                    }
                                                ?>
                                              </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <input type="submit" name="days_update" class="btn btn-success" value="Update Days"/>
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
                        <div class="row">
                        <?php
                            $con = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
                            $res = mysqli_query($con, "select pid, name, pdesc, amt, img, type from plans where type in ('m','w')");
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
                                              <a href="?update=<?php echo $row['pid'];?>" style="width:100%;" class="btn btn-success">Update</a>
                                          </div>
                                          <div class="col-sm-8">
                                              <b>Name: </b><?php echo $row["name"]; ?><br/>
                                              <b>Price: </b><?php echo $row["amt"]; ?><br/>
                                              <b>Type: </b><?php if ($row["type"] == "w") echo "Weekly"; elseif ($row["type"] == "m") echo "Monthly"; else echo "Trial Pack"; ?><br/>
                                              <b>Description: </b><?php echo $row["pdesc"]; ?><br/><br/>
                                              <a href="?pid=<?php echo $row['pid'];?>" style="width:45%;" class="btn btn-primary pull-left">Details</a>
                                              <a href="?delete=<?php echo $row['pid'];?>" style="width:45%;" class="btn btn-danger pull-right">Delete</a>
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
                                                <a href="?update=<?php echo $row['pid'];?>" style="width:100%;" class="btn btn-success">Update</a>
                                            </div>
                                            <div class="col-sm-8">
                                              <b>Name: </b><?php echo $row["name"]; ?><br/>
                                              <b>Price: </b><?php echo $row["amt"]; ?><br/>
                                              <b>Type: </b><?php if ($row["type"] == "w") echo "Weekly"; elseif ($row["type"] == "m") echo "Monthly"; else echo "Trial Pack"; ?><br/>
                                              <b>Description: </b><?php echo $row["pdesc"]; ?><br/><br/>
                                              <a href="?pid=<?php echo $row['pid'];?>" style="width:45%;" class="btn btn-primary pull-left">Details</a>
                                              <a href="?delete=<?php echo $row['pid'];?>" style="width:45%;" class="btn btn-danger pull-right">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                  </div>
                                </div>
                              <?php
                              }
                              $count = $count + 1;
                            }
                            mysqli_close($con);
                        ?>
                      </div>
                <!-- </div>
                        </div> -->
                    </div>
                </div>
                <?php
                    if(isset($_GET['delete']))
                    {
                        $pid = $_GET['delete'];
                        $res = executeQuery("delete from plans where pid = $pid");
                        if($res)
                        {
                          echo "<script>alert('Successfully Deleted!');window.open('planner.php','_parent');</script>";
                        }
                        else {
                          # code...
                          echo "<script>alert('Failed to delete!');</script>";
                        }
                    }
                    if(isset($_GET['pid']))
                    {
                        $pid = $_GET['pid'];
                ?>
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h3 class="text-themecolor">Plan Details</h3>
                    </div>
                </div>
                <div class="row">
                  <?php
                      $json = json_decode(mysqli_fetch_array(executeQuery("select dids from plans where pid = $pid "))[0]);
                      $count = 0;
                      foreach ($json as $key => $value){
                        $data = mysqli_fetch_array(executeQuery("select name, price, img from drinks where did = $key"));
                        if($count %3 == 0 ) {
                            ?>
                            <div class="col-sm-4">
                              <div class = 'card'>
                                <div class="card-body">
                                  <div class="row">
                                      <div class="col-sm-4">
                                          <img class='' style="width: 100%;" src="<?php echo $data[2]; ?>"/>
                                      </div>
                                      <div class="col-sm-8">
                                            <b>Name: </b><?php echo $data[0]; ?><br/>
                                            <b>Price: </b><?php echo $data[1]; ?><br/>
                                            <b>Days: </b><?php echo $value; ?><br/>
                                      </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <?php
                        }
                        elseif ($count %3 ==1) {
                          ?>
                          <div class="col-sm-4">
                            <div class = 'card'>
                              <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <img class='' style="width: 100%;" src="<?php echo $data[2]; ?>"/>
                                    </div>
                                    <div class="col-sm-8">
                                          <b>Name: </b><?php echo $data[0]; ?><br/>
                                          <b>Price: </b><?php echo $data[1]; ?><br/>
                                          <b>Days: </b><?php echo $value; ?><br/>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php
                        }
                        else {
                          ?>
                          <div class="col-sm-4">
                            <div class = 'card'>
                              <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <img class='' style="width: 100%;" src="<?php echo $data[2]; ?>"/>
                                    </div>
                                    <div class="col-sm-8">
                                          <b>Name: </b><?php echo $data[0]; ?><br/>
                                          <b>Price: </b><?php echo $data[1]; ?><br/>
                                          <b>Days: </b><?php echo $value; ?><br/>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php
                        }
                        $count +=1;
                      }
                      //
                      /*while($row = mysqli_fetch_array($res))
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
                                        <b>Name: </b><?php echo $row["name"]; ?><br/>
                                        <b>Price: </b><?php echo $row["amt"]; ?><br/>
                                        <b>Type: </b><?php if ($row["type"] == "w") echo "Weekly"; elseif ($row["type"] == "m") echo "Monthly"; else echo "Trial Pack"; ?><br/>
                                        <b>Description: </b><?php echo $row["pdesc"]; ?><br/><br/>
                                        <a href="?pid=<?php echo $row['pid'];?>" style="width:45%;" class="btn btn-primary pull-left">Details</a>
                                        <a href="?delete=<?php echo $row['pid'];?>" style="width:45%;" class="btn btn-danger pull-right">Delete</a>
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
                                        <b>Name: </b><?php echo $row["name"]; ?><br/>
                                        <b>Price: </b><?php echo $row["amt"]; ?><br/>
                                        <b>Type: </b><?php if ($row["type"] == "w") echo "Weekly"; elseif ($row["type"] == "m") echo "Monthly"; else echo "Trial Pack"; ?><br/>
                                        <b>Description: </b><?php echo $row["pdesc"]; ?><br/><br/>
                                        <a href="?pid=<?php echo $row['pid'];?>" style="width:45%;" class="btn btn-primary pull-left">Details</a>
                                        <a href="?delete=<?php echo $row['pid'];?>" style="width:45%;" class="btn btn-danger pull-right">Delete</a>
                                      </div>
                                  </div>
                              </div>
                            </div>
                          </div>
                        <?php
                        }
                        $count = $count + 1;
                      }*/
                  ?>
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
