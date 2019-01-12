<?php
require_once 'config.php';
require_once 'SMSManagerClass.php';
date_default_timezone_set('Asia/Kolkata');

class UserManagement
{

    public static function generate($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function checkNumber($phone)
    {
      $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
      $res = mysqli_query($con, "select name from users where phone = '$phone'");
      $data = mysqli_fetch_array($res);
      mysqli_close($con);
      if($data[0]!="")
        return array("response" => "True");
      else
        return array("response" => "False");
    }

    public function resendOTP($phone)
    {
    	$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "select name from users where phone = '$phone'");
	mysqli_close($con);
        $name = mysqli_fetch_array($res)[0];
        if($name !=null)
        {
            $sms = new SMSManagerClass();
            $sms->resendOTP($name, $phone);
            return array("response" => "True");
        }
        else
            return array("response" => "False");
    }

    public function login($phone, $pass){
      $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
      $res = mysqli_query($con,"select pass,name,role,uid,active from users where phone = '$phone'");
      $data = mysqli_fetch_array($res);
      if($data[0]!=null)
      {
        $_pass = $data[0];
        // extract the hashing method, number of rounds, and salt from the stored hash
        // and hash the password string accordingly
        $parts = explode('$', $_pass);
        $genPass = crypt($pass, sprintf('$%s$%s$%s$', $parts[1], $parts[2], $parts[3]));
        if($genPass == $_pass)
          {
          	if($data[4]=="no")
          	{
          		$sms = new SMSManagerClass();
          		$sms->sendOTPRegister($data[1], $phone);
          	}
            $res = mysqli_query($con,"select ref from users where phone='$phone'");
            $ref = mysqli_fetch_array($res)[0];
            mysqli_close($con);
          	return array("auth" => "True", "name" => $data[1], "role" => $data[2], "uid" => $data[3],"active"=>$data[4],"referral"=>$ref);
          }
        else
          return array("auth" => "False");
        //return array("auth" => "True");
      }
      else
        return array("auth" => "False");
    }

    public function register ($name, $phone, $pass, $ref)
    {
      $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
      // generate a 16-character salt string
      $salt = substr(str_replace('+','.',base64_encode(md5(mt_rand(), true))),0,16);
      // how many times the string will be hashed
      $rounds = 10000;
      // pass in the password, the number of rounds, and the salt
      // $5$ specifies SHA256-CRYPT, use $6$ if you really want SHA512
      $_pass = crypt($pass, sprintf('$5$rounds=%d$%s$', $rounds, $salt));
      $gid=$this->generate(5);
      $res = mysqli_query($con,"insert into users (name, phone, pass, role, active,ref,firstbuy) values('$name','$phone','$_pass','normal','no','$gid',0)");
      $newPoint = mysqli_fetch_array(mysqli_query($con,"select new_user from point_sch"))[0];
      $res = mysqli_query($con,"insert into user_points value('$phone',$newPoint)");
      $arr = array();
      if($res)
      {
          $sms = new SMSManagerClass();
          if ($sms->sendOTPRegister($name, $phone))
          {
             if($ref!='none')
              $res = mysqli_query($con, "insert into referals values('$phone', '$ref')");
          }
          $arr = array("response" => "True");
      }
      else
              $arr = array("response" => "False");
      mysqli_close($con);
      return $arr;
    }

    public function alterUser($phone, $pass, $alter_user, $role)
    {
      $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
      $res = mysqli_query($con,"select pass from users where phone = '$username' and role = 'admin'");
      $_pass = mysqli_fetch_array($res)[0];
      $parts = explode('$', $_pass);
      $genPass = crypt($pass, sprintf('$%s$%s$%s$', $parts[1], $parts[2], $parts[3]));
      if($genPass == $_pass)
      {
          $res = mysqli_query($con, "update users set role = '$role' where phone = '$alter_user'");
          mysqli_close($con);
          if($res)
            return array("response" => "True");
          else
            return array("response" => "False");
      }
      else
          return array("response" => "False");
    }

    public function forgotPasswordRequest($phone)
    {
      $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
      $res = mysqli_query($con,"select name from users where phone = '$phone'");
      $data = mysqli_fetch_array($res);
      $sms = new SMSManagerClass();
      if($sms->sendOTPForgot($data[0],$phone))
        return array("response" => "True");
      else
        return array("response" => "False");
    }

    public function forgotPasswordUpdate($username, $pass)
    {
      $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
      // generate a 16-character salt string
      $salt = substr(str_replace('+','.',base64_encode(md5(mt_rand(), true))),0,16);
      // how many times the string will be hashed
      $rounds = 10000;
      // pass in the password, the number of rounds, and the salt
      // $5$ specifies SHA256-CRYPT, use $6$ if you really want SHA512
      $_pass = crypt($pass, sprintf('$5$rounds=%d$%s$', $rounds, $salt));
      $res = mysqli_query($con, "update users set pass = '$_pass' where phone = '$username'");
      if($res)
        return array("response" => "True");
      else
        return array("response" => "False");
    }

    public function getMessages($phone)
    {
        //select message, mdate from messages where phone='7205502260'
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con,"select message, mdate from messages where phone='$phone'");
        $arr = array();
        mysqli_close($con);
        while($row = $res->fetch_assoc())
        {
            $arr[] = $row;
        }
        return $arr;
    }

    public function addMessage($phone, $msg)
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con,"insert into messages values('$phone', '$msg', '".date("Y-m-d")."')");
        mysqli_close($con);
        if($res)
          return array("response"=>"True");
        else
          return array("response"=>"True");
    }

    public function getReferal($phone)
    {
        //SELECT * FROM referals WHERE phone ='7008267103'
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con,"select ref from users where phone='$phone'");
        $data = mysqli_fetch_array($res);
        mysqli_close($con);
        if($data[0]!="")
          return array("response" => "True","Referal" => $data[0]);
        else
          return array("response" => "False");
      }
}
?>
