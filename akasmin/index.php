<?php
    require '../server/config.php';
    session_start();
    //cheking if already logged in
    if(isset($_SESSION["login"]))
    {
      if($_SESSION["login"]=="True")
          header("Location: dashboard.php");
    }
    if(isset($_POST["user"]) && isset($_POST["pass"]))
    {
        $email = $_POST["user"];
        $pass = $_POST["pass"];
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con,"select name, password from admin where email = '$email'");
        $data = mysqli_fetch_array($res);
        mysqli_close($con);
        if(($pass == $data[1]) && strlen($pass)!=0)
        {
          $_SESSION["login"] = "True";
          $_SESSION["name"] = $data[1];
          header("Location: dashboard.php");
        }
        else
          echo "<script>alert('Authentication Failed!');</script>";
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
     <title>FruizHUB ADMIN</title>
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
             </nav>
         </header>
         <!-- ============================================================== -->
         <!-- End Topbar header -->
         <!-- ============================================================== -->
         <!-- ============================================================== -->
         <!-- Left Sidebar - style you can find in sidebar.scss  -->
         <!-- ============================================================== -->

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
                         <h3 class="text-themecolor">Admin Login</h3>
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
                             <div class="card-body">
                                 <center class="m-t-30"> <img src="../assets/images/avatar.png" class="img-circle" width="150" />
                                     <h4 class="card-title m-t-10">Control Panel</h4>
                                     <h6 class="card-subtitle">Manage Drinks and Transactions</h6>
                                 </center>
                             </div>
                         </div>
                     </div>
                     <!-- Column -->
                     <!-- Column -->
                     <div class="col-lg-8 col-xlg-9 col-md-7">
                         <div class="card">
                             <div class="card-body">
                                 <form submit = "<?php $_PHP_SELF?>" method="POST" class="form-horizontal form-material">
                                     <div class="form-group">
                                         <label for="example-email" class="col-md-12">Email</label>
                                         <div class="col-md-12">
                                             <input type="email" placeholder="user@mail.com" class="form-control form-control-line" name="user">
                                         </div>
                                     </div>
                                     <div class="form-group">
                                         <label class="col-md-12">Password</label>
                                         <div class="col-md-12">
                                             <input name="pass" type="password" placeholder="**********" class="form-control form-control-line">
                                         </div>
                                     </div>
                                     <div class="form-group">
                                         <div class="col-sm-12">
                                             <input type="submit" value="Login Now" class="pull-right btn btn-success"/>
                                         </div>
                                     </div>
                                 </form>
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
             <footer class="footer">
                 © 2018 Fruizhub Admin Panel by ServiQuik
             </footer>
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
 </body>

 </html>
