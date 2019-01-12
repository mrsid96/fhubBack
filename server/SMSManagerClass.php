<?php
require_once 'config.php';
class SMSManagerClass {
  public function sendOTPRegister($name, $phone)
  {
    $otp = mt_rand(213547, 926397);
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    //insert into user_points VALUES('$phone','$point') ON DUPLICATE KEY UPDATE point = '$point'
    $res = mysqli_query($con, "insert into user_otp values('$phone','$otp') ON DUPLICATE KEY UPDATE otp = '$otp'");
    $text = "Hi ".explode(' ',trim($name))[0].", Welcome to FruizHUB. Your OTP is $otp. Dont share with anyone. Happy Fruizing !";
    $smsAPI = "https://api.textlocal.in/send/?username=fruizhub@gmail.com&password=Fruiz@123&sender=FRZHUB&numbers=$phone&message=$text";
    $response = file_get_contents(str_replace(" ","%20",$smsAPI));
    if($res)
        return true;
    else
        return false;
  }

  public function customOTP($msg, $name, $phone)
  {
    $otp = mt_rand(213547, 926397);
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $res = mysqli_query($con, "insert into user_otp values('$phone','$otp') ON DUPLICATE KEY UPDATE otp = '$otp'");
    $text = "Hey ".explode(' ',trim($name))[0].", $msg. Your OTP is $otp. It's confidential.";
    $smsAPI = "https://api.textlocal.in/send/?username=fruizhub@gmail.com&password=Fruiz@123&sender=FRZHUB&numbers=$phone&message=$text";
    $response = file_get_contents(str_replace(" ","%20",$smsAPI));
    if($res)
        return true;
    else
        return false;
  }

  public function sendOTPForgot($name, $phone)
  {
    $otp = mt_rand(213547, 926397);
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $res = mysqli_query($con, "insert into user_otp values('$phone','$otp') ON DUPLICATE KEY UPDATE otp = '$otp'");
    $text = "Hey ".explode(' ',trim($name))[0].", you have requested to change your password. Your OTP is $otp. Dont share with anyone. Happy Fruizing !";
    $smsAPI = "https://api.textlocal.in/send/?username=fruizhub@gmail.com&password=Fruiz@123&sender=FRZHUB&numbers=$phone&message=$text";
    $response = file_get_contents(str_replace(" ","%20",$smsAPI));
    if($res)
        return true;
    else
        return false;
  }

  public function resendOTP($name, $phone)
  {
    $otp = mt_rand(213547, 926397);
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $res = mysqli_query($con, "insert into user_otp values('$phone','$otp') ON DUPLICATE KEY UPDATE otp = '$otp'");
    $text = "Hey ".explode(' ',trim($name))[0].", you have requested for a new OTP. Your OTP is $otp. Dont share with anyone. Happy Fruizing !";
    $smsAPI = "https://api.textlocal.in/send/?username=fruizhub@gmail.com&password=Fruiz@123&sender=FRZHUB&numbers=$phone&message=$text";
    $response = file_get_contents(str_replace(" ","%20",$smsAPI));
    if($res)
        return true;
    else
        return false;
  }

  public function verifyOTPRegister($phone, $otp)
  {
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $res = mysqli_query($con, "select otp from user_otp where phone = '$phone'");
    $data = mysqli_fetch_array($res)[0];
    $arr = array();
    echo $data." & ".$otp;
    if($otp == $data)
    {
        $res = mysqli_query($con, "update users set active = 'yes' where phone = '$phone'");
        $del = mysqli_query($con, "delete from user_otp where phone = '$phone'");
        if($res && $del)
          $arr = array("response" => "True");
        else
          $arr = array("response" => "False");
    }
    else
      $arr = array("response" => "False");

    mysqli_close($con);
    return $arr;
  }

  public function verifyOTPCustom($phone, $otp)
  {
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $res = mysqli_query($con, "select otp from user_otp where phone = '$phone'");
    $data = mysqli_fetch_array($res)[0];
    $arr = array();
    if($otp == $data)
    {
        $res = mysqli_query($con, "delete from user_otp where phone = '$phone'");
        if($res)
          $arr = array("response" => "True");
        else
          $arr = array("response" => "False");
    }
    else
      $arr = array("response" => "False");

    mysqli_close($con);
    return $arr;
  }
}
?>
