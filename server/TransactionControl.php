<?php
require_once 'config.php';
date_default_timezone_set('Asia/Kolkata');
class TransactionControl
{
    /* Class Documentation
    *  addTransaction - This function is used add a new Transaction
    *  updateTransaction - This function is used to update the label for transaction
    */
    // public function addTransaction($name, $address, $uid, $dtime, $phone, $data, $date)
    // {
    //     $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    //     if ($date=="no") {
    //         $res=mysqli_query($con, "insert into transactions (name,address,dtime,uid,phone,tdate,tstatus) values ('$name','$address','$dtime',$uid,'$phone','".date("Y-m-d")."','pending')");
    //     } else {
    //         $res=mysqli_query($con, "insert into transactions (name,address,dtime,uid,phone,tdate,tstatus) values('$name','$address','$dtime',$uid,'$phone','$date','pending')");
    //     }
    //     if ($res) {
    //         $tid = mysqli_insert_id($con);
    //         $amt = 0;
    //         for ($i=0;$i<count($data);$i++) {
    //             $amt += $data[$i]['amt'];
    //             $res = mysqli_query($con, "insert into trandets (tid, did, cnt, amt) values ($tid,".$data[$i]['did'].",".$data[$i]['cnt'].",'".$data[$i]['amt']."')");
    //         }
    //         $arr=array('check' => 'True', 'tid' => $tid, 'amt' => $amt);
    //     } else {
    //         $arr=array('check' => 'False');
    //     }
    //     mysqli_close($con);
    //     return $arr;
    // }

    public function pullSummary($tid, $type)
    {
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if($type == 'drink'){
            $res = mysqli_query($con, "select drinks, trials, name, address, phone, total, mode, tdate from transholder where drinks like '%".$tid."%'");
            $data = mysqli_fetch_array($res);
        }
        else{
            $res = mysqli_query($con, "select drinks, trials, name, address, phone, total, mode, tdate from transholder where trials like '%".$tid."%'");
            $data = mysqli_fetch_array($res);
        }

        $arr['response'] = "True";
        $arr['name'] = $data[2];
        $arr['address'] = $data[3];
        $arr['phone'] = $data[4];
        $arr['mode'] = $data[6];
        $arr['date'] = $data[7];
        $arr['total'] = (int)$data[5];

        //For drinks
        $arr["drinks"] = array();
        $res = mysqli_query($con, "select tid from transactions where tid in (".$data[0].")");
        while ($row = $res->fetch_assoc()) {
            // code...
            $res_drink = mysqli_query($con, "select tid, drinks.name name, img, cnt, amt, tstatus from trandets join transactions using (tid) join drinks using (did) where tid = ".$row['tid']);

            while ($row_drink = $res_drink->fetch_assoc()) {
                $arr["drinks"][]= array("tid"=>(int)$row_drink['tid'],"name"=>$row_drink['name'],"image"=>$row_drink['img'],"status"=>$row_drink['tstatus'],"quantity"=>$row_drink['cnt'],"amount"=>$row_drink['amt']);
            }
        }

        //For Trials
        $arr["trials"] = array();
        $res = mysqli_query($con, "select tid from planTrans where tid in (".$data[1].")");
        while ($row = $res->fetch_assoc()) {
            // code...
            $res_trial = mysqli_query($con, "SELECT plans.name name, plans.img img, quantity, status, planTrans.amt amt from planTrans join plans using (pid) where tid = ".$row['tid']);
            $row_drink = mysqli_fetch_array($res_trial);
            $arr["trials"][]= array("tid"=>(int)$row['tid'],"name"=>$row_drink['name'],"image"=>$row_drink['img'],"status"=>$row_drink['status'],"quantity"=>$row_drink['quantity'],"amount"=>$row_drink['amt']);

        }

        mysqli_close($con);
        return $arr;
    }

    public function addTransaction($name, $address, $time, $uid, $phone, $status, $mode, $drinks, $trials)
    {
        //for $drinks
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $total =10;
        $drink_holder = array();
        $trial_holder = array();

        //for drinks
        if (sizeof($drinks)!=0) {
            $res = mysqli_query($con, "insert into transactions (name, address, dtime, uid, phone, tdate, tmode, tstatus) values ('$name','$address','$time','$uid','$phone','".date("Y-m-d")."','$mode','$status')");
            if ($res) {
                $tid = mysqli_insert_id($con);
                for ($i=0;$i<sizeof($drinks);$i++) {
                    $res = mysqli_query($con, "insert into trandets (tid, did, cnt, amt) values ($tid,".$drinks[$i]['id'].",".$drinks[$i]['count'].",'".$drinks[$i]['amount']."')");
                    $total = $total + ($drinks[$i]['count'] * $drinks[$i]['amount']);
                    $drink_data = mysqli_fetch_array(mysqli_query($con, "select name, img from drinks where did = ".$drinks[$i]['id']));
                    $drink[] = array("tid"=>$tid, "name"=>$drink_data[0], "status"=>"pending", "image" => $drink_data[1],"quantity" => $drinks[$i]['count'], "amount" => $drinks[$i]['amount']);
                    array_push($drink_holder, $tid);
                }
            }
        }

        //print_r ($drink_holder);
        //echo "hi".implode(",",$$drink_holder);

        //for trials
        if (sizeof($trials)!=0) {
            for ($i=0;$i<sizeof($trials);$i++) {
                #echo "insert into planTrans (pid,name,phone,uid,address,time,tdate,sdate,edate,type,amt,quantity,mode,status,delivery) VALUES (".$trials[$i]['id'].",'$name','$phone','$uid','$address','no','".date("Y-m-d")."','no','no','t','".$trials[$i]['amount']."','".$trials[$i]['count']."','$mode','$status','0')\n";
                $res = mysqli_query($con, "insert into planTrans (pid,name,phone,uid,address,time,tdate,sdate,edate,type,amt,quantity,mode,status,delivery) VALUES (".$trials[$i]['id'].",'$name','$phone','$uid','$address','no','".date("Y-m-d")."','no','no','t','".$trials[$i]['amount']."','".$trials[$i]['count']."','$mode','$status','0')");
                $tid = mysqli_insert_id($con);
                $total = $total + ($trials[$i]['amount'] * $trials[$i]['count']);
                $trial_data = mysqli_fetch_array(mysqli_query($con, "select name, img, amt from plans where pid = ".$trials[$i]['id']));
                $trial[] = array("tid"=>$tid, "name"=>$trial_data[0], "status"=>"pending", "image"=>$trial_data[1], "quantity"=>$trials[$i]['count'],"amount"=>$trial_data[2]);
                array_push($trial_holder, $tid);
            }
        }

        if ($drink == null) {
            $drink[0] = array("tid"=>"0");
            $drink_holder[0] ="0";
        }

        if ($trial == null) {
            $trial[0] = array("tid"=>"0");
            $trial_holder[0] = "0";
        }
        $data = array("drinks"=>$drink_holder,"trials"=>$trial_holder);
        #print_r($data);

        if($mode == "points")
        {
            $per10 = mysqli_fetch_array(mysqli_query($con,"select ten_point from point_sch"))[0];
            $res = mysqli_query($con,"update user_points set point = point - ".round(($total*10)/$per10)." where phone = $phone");
            if($res)
                $res = mysqli_query($con, "insert into user_points_desc values('$phone','".date("Y-m-d")."','debited','".round(($total*10)/$per10)."','For order no $tid')");
        }

        $res = mysqli_query($con, "insert into transholder (name, address, phone, total, mode, tdate, drinks, trials) values ('$name','$address','$phone','$total','$mode','".date("Y-m-d")."','".implode(",", $drink_holder)."','".implode(",", $trial_holder)."')");
        mysqli_close($con);
        return array("response" => "True","name"=>$name,"total"=>$total,"phone"=>$phone,"mode"=>$mode, "date"=> date("Y-m-d"), "address"=>$address,"drinks"=>$drink, "trials"=>$trial);
    }

    public function addPlan_trans($name, $pid, $phone, $uid, $address, $stime, $sdate, $edate, $amt, $quantity, $mode)
    {
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $type = mysqli_fetch_array(mysqli_query($con, "select type from plans where pid = $pid"))[0];
        $res = mysqli_query($con, "insert into planTrans (name,pid,phone,uid,address,time,tdate,sdate,edate,type,amt,quantity,mode,status,delivery) values ('$name',$pid,'$phone','$uid','$address','$stime','".date("Y-m-d")."','$sdate','$edate','$type','$amt',$quantity,'$mode','pending',0)");
        if ($res) {
            $tid = mysqli_insert_id($con);
            $data = mysqli_fetch_array(mysqli_query($con,"select img, name from plans where pid = $pid"));
            $res = mysqli_query($con, "select image, date, time from planSign where tid = $tid");
            $delivery = array();
            while ($row = $res->fetch_assoc())
            {
                $delivery[] = $row;
            }
            $arr=array('response' => 'True', 'tid' => $tid, 'amount' => $amt, "mode" => $mode, "type"=>$type, "customer"=>$name, "phone"=> $phone, "status"=>"pending", "name"=>$data[1], "image"=>$data[0], "address" => $address, "sdate" => $sdate, "edate"=>$edate,"days"=>$delivery);
        } else {
            $arr=array('response' => 'False');
        }
        mysqli_close($con);
        return $arr;
    }

    public function getPlanTransDetails($tid)
    {
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "select pid, type, amt, address, sdate, edate, name, phone, status, mode from planTrans where tid = $tid");
        $data = mysqli_fetch_array($res);
        $plandata = mysqli_fetch_array(mysqli_query($con,"select img, name from plans where pid =".$data[0]));
        $res = mysqli_query($con, "select image, date, time from planSign where tid = $tid");
        $delivery = array();
        while ($row = $res->fetch_assoc())
        {
            $delivery[] = $row;
        }
        $arr=array('response' => 'True', 'tid' => $tid, 'amount' => $data[2], "mode"=>$data[9], "type"=>$data[1], "customer"=>$data[6], "phone"=> $data[7], "status"=> $data[8], "name"=>$plandata[1], "image"=>$plandata[0], "address" => $data[3], "sdate" => $data[4], "edate" =>$data[5],"days"=>$delivery);
        mysqli_close($con);
        return $arr;
    }

    public function getPlansByStatus($uid, $status)
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        //$res = mysqli_query($con, "select plans.name, plans.type, plans.img, planTrans.status, planTrans.tdate, planTrans.amt, tid, planTrans.pid from plans join planTrans using(pid) where tid = $tid and status = '$status'")
        $res = mysqli_query($con, "select plans.name, plans.type, plans.img, planTrans.status, planTrans.mode, sdate, edate, quantity, planTrans.tdate, planTrans.amt, tid, planTrans.pid from plans join planTrans using(pid) where uid = $uid and status = '$status' and plans.type in ('w','m')");
        while ($row = $res->fetch_assoc()) {
            // code...
            $arr[] = $row;
        }
        mysqli_close($con);
        return array('plans'=>$arr);
    }

    public function addPhoto($tid, $img)
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        $target_dir="../signature";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $link_dir="http://139.59.40.169/signature/sign_TID_$tid-".date('d-m-Y-h-i-s').".jpg";
        $res = mysqli_query($con, "insert into planSign(tid,image,date) values ($tid,'$link_dir','".date('d-m-Y')."','".date('h-i')."')");

        file_put_contents($target_dir."/sign_TID_$tid-".date('d-m-Y-h-i-s').".jpg", base64_decode($img));

        // Create new imagick object
        $im = new Imagick($target_dir."/sign_TID_$tid-".date('d-m-Y-h-i-s').".jpg");

        // Optimize the image layers
        $im->optimizeImageLayers();

        // Compression and quality
        $im->setImageCompression(Imagick::COMPRESSION_JPEG);
        $im->setImageCompressionQuality(25);

        // Write the image back
        $im->writeImages($target_dir."/sign_TID_$tid-".date('d-m-Y-h-i-s').".jpg", true);

        if ($res) {
            $edate = mysqli_fetch_array(mysqli_query($con, "select edate from planTrans where tid = $tid"))[0];
            if ($edate == date('d-m-Y')) {
                $res = mysqli_query($con, "update planTrans set delivery = 1, status = 'completed' where tid = $tid");
            } else {
                $res = mysqli_query($con, "update planTrans set delivery = 1, status = 'active' where tid = $tid");
            }
            $arr=array('check' => 'True', 'path' => $link_dir);
        } else {
            $arr=array('check' => 'False');
        }
        mysqli_close($con);
        return $arr;
    }

    public function flush()
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $check = mysqli_fetch_array(mysqli_query($con, "select count(date) from planDelivery where date = '".date('d-m-Y')."'"))[0];
        if ($check == 0) {
            $res = mysqli_query($con, "delete from planTrans where mode = ''");
            $res = mysqli_query($con, "delete from transactions where tmode = ''");
            $ins = mysqli_query($con, "insert into planDelivery values('".date('d-m-Y')."')");
            if ($ins) {
                $res = mysqli_query($con, "update planTrans set delivery=0");
            }
        }
        mysqli_close($con);
        return array('response'=>'True');
    }

    public function checkNew()
    {
        if (strpos(file_get_contents("../notification.txt"), "newt")!== false) {
            $myfile = fopen("../notification.txt", "w");
            $txt = "";
            fwrite($myfile, $txt);
            return array("type"=>"drink");
        }
        if (strpos(file_get_contents("../notification.txt"), "newp")!== false) {
            $myfile = fopen("../notification.txt", "w");
            $txt = "";
            fwrite($myfile, $txt);
            return array("type"=>"plan");
        } else {
            return array("type"=>"no");
        }
    }

    public function checkVersion()
    {
        $ver = file_get_contents("../version.txt");
        $arr = array("version" => $ver);
        $myfile = fopen("../version.txt", "w") or die("Unable to open file!");
        fwrite($myfile, "");
        return $arr;
    }

    public function showTN($tid, $table)
    {
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($table == "drink") {
            $res = mysqli_query($con, "select tstatus, tid, tmode, name, address, phone, tdate, dtime from transactions where tid = $tid");
            $data = mysqli_fetch_array($res);
            $arr['status']=$data[0];
            $arr['ord_no'] = $data[1];
            $arr['mode'] = $data[2];
            $arr['name'] = $data[3];
            $arr['address'] = $data[4];
            $arr['phone'] = $data[5];
            $arr['date'] = $data[6];
            $arr['time'] = $data[7];
            $res = mysqli_query($con, "select name, cnt, amt from trandets join drinks using (did) where tid =$tid");
            $arr["orders"] = array();
            while ($row = $res->fetch_assoc()) {
                $arr["orders"][]= $row;
            }
            mysqli_close($con);
            return $arr;
        } else {
            $res = mysqli_query($con, "select status, tid, mode, planTrans.name, address, phone, tdate, sdate, edate, time, planTrans.type, plans.name, quantity, planTrans.amt from planTrans join plans using (pid) where tid = $tid");
            $data = mysqli_fetch_array($res);
            $arr['status']=$data[0];
            $arr['ord_no'] = $data[1];
            $arr['mode'] = $data[2];
            $arr['name'] = $data[3];
            $arr['address'] = $data[4];
            $arr['phone'] = $data[5];
            $arr['date'] = $data[6];
            $arr['stdate'] = $data[7];
            $arr['etdate'] = $data[8];
            $arr['time'] = $data[9];
            $arr['type'] = $data[10];
            $arr['pname'] = $data[11];
            $arr['quantity'] = $data[12];
            $arr['amt'] = $data[13];
            mysqli_close($con);
            return $arr;
        }
    }

    public function showTodayPlans()
    {
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res=mysqli_query($con, "select name, phone, address, time, type, mode, pid, tid, amt, quantity from planTrans where DATE(DATE_FORMAT(STR_TO_DATE(sdate, '%d-%m-%y'), '%Y-%m-%d')) <= DATE(DATE_FORMAT(STR_TO_DATE('".date("d-m-Y")."', '%d-%m-%y'), '%Y-%m-%d')) and DATE(DATE_FORMAT(STR_TO_DATE(edate, '%d-%m-%y'), '%Y-%m-%d')) >= DATE(DATE_FORMAT(STR_TO_DATE('".date("d-m-Y")."', '%d-%m-%y'), '%Y-%m-%d')) and delivery = 0 order by tid");
        $arr = array();
        while ($row = $res->fetch_assoc()) {
            # code...
            //$arr[]=$row;
            $json = json_decode(mysqli_fetch_array(mysqli_query($con, "select dids from plans where pid = ".$row['pid']))[0]);
            foreach ($json as $key => $value) {
                if (strpos($value, date("D"))!== false) {
                    $ar['name']=$row['name'];
                    $ar['phone']=$row['phone'];
                    $ar['address']=$row['address'];
                    $ar['time']=$row['time'];
                    $ar['type']=$row['type'];
                    $ar['mode']=$row['mode'];
                    $ar['pid']=$row['pid'];
                    $ar['tid']=$row['tid'];
                    $ar['amt']=$row['amt'];
                    $ar['quantity']=$row['quantity'];
                    $ar['drink'] = mysqli_fetch_array(mysqli_query($con, "select name from drinks where did = $key"))[0];
                    $arr[]=$ar;
                }
            }
        }
        mysqli_close($con);
        return $arr;
    }

    public function showTodayDrinks()
    {
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res=mysqli_query($con, "select name, phone, address, dtime, tmode, tid from transactions where tstatus='processing' order by tid");
        $ret = array();
        $arr = array();

        while ($row = $res->fetch_assoc()) {
            # code...
            $money = 0;
            $res2 = mysqli_query($con, "select name, amt, cnt from trandets join drinks using (did) where tid = ".$row['tid']);
            $drink=array();
            while ($row2 = $res2->fetch_assoc()) {
                $drink[]=$row2;
                $money += $row2['amt'];
            }
            $ar['name']=$row['name'];
            $ar['phone']=$row['phone'];
            $ar['address']=$row['address'];
            $ar['time']=$row['dtime'];
            $ar['mode']=$row['tmode'];
            $ar['tid']=$row['tid'];
            $ar['amt']=$money;
            $ar['drink']=$drink;
            $arr[] = $ar;
        }
        $ret['drinks'] = $arr;
        //SELECT plans.name,plans.img,tid, planTrans.amt, tdate, status, plans.type FROM plans JOIN planTrans USING ( pid ) where phone = '7205502260' ORDER BY tid DESC
        $res=mysqli_query($con, "select planTrans.name, phone, address, time, mode, tid, pid, quantity, planTrans.amt FROM plans JOIN planTrans USING ( pid ) where status='processing' and planTrans.type='t' order by tid");
        $arr = array();
        while ($row = $res->fetch_assoc()) {
            # code...
            $dids = mysqli_fetch_array(mysqli_query($con, "select dids from plans where pid = ".$row['pid']))[0];
            $res2 = mysqli_query($con, "select name from drinks where did in ($dids)");
            $drink=array();
            while ($row2 = $res2->fetch_assoc()) {
                $drink[]=$row2;
            }
            $ar['name']=$row['name'];
            $ar['phone']=$row['phone'];
            $ar['address']=$row['address'];
            $ar['time']=$row['time'];
            $ar['mode']=$row['mode'];
            $ar['tid']=$row['tid'];
            $ar['drink']=$drink;
            $ar['quantity'] = $row['quantity'];
            $ar['amt'] = $row['amt'];
            $arr[] = $ar;
        }
        $ret['trial'] = $arr;
        mysqli_close($con);
        return $ret;
    }

    public function fetchOrders($uid, $type)
    {
        //
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        //$res=mysqli_query($con,"select drinks.name, drinks.img, did, tid, cnt, amt, phone, tdate, tstatus from drinks join trandets using (did) join transactions using (tid) where uid = $uid");
        $arr = array();

        $res = mysqli_query($con, "select drinks.name, drinks.img, tid, amt, tdate, tstatus, cnt from drinks join trandets using (did) join transactions using (tid) where uid = $uid and tstatus = '$type' order by tid desc");
        $drink = array();

        while ($row = $res->fetch_assoc()) {
            $drink[]= $row;
        }
        //SELECT plans.name,plans.img,tid, planTrans.amt, tdate, status, plans.type FROM plans JOIN planTrans USING ( pid ) where phone = '7205502260' ORDER BY tid DESC
        $res = mysqli_query($con, "select plans.name, plans.img, tid, planTrans.amt, tdate, status, quantity from  plans JOIN planTrans USING ( pid ) where uid = $uid and status = '$type' and sdate='no' order by tid desc");
        $trial = array();

        while ($row = $res->fetch_assoc()) {
            $trial[]= $row;
        }
        mysqli_close($con);
        $arr[0] = $drink;
        $arr[1]=$trial;
        return array('drinks' => $arr[0], 'trial' => $arr[1]);
    }

    public function fetchOrdersAll($uid)
    {
        //
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        //$res=mysqli_query($con,"select drinks.name, drinks.img, did, tid, cnt, amt, phone, tdate, tstatus from drinks join trandets using (did) join transactions using (tid) where uid = $uid");
        $res = mysqli_query($con, "select drinks.name, drinks.img, tid, amt, tdate, tstatus from drinks join trandets using (did) join transactions using (tid) where uid = $uid order by tdate desc");
        $arr = array();
        while ($row = $res->fetch_assoc()) {
            $arr[]= $row;
        }
        mysqli_close($con);
        $ret = array();
        $ret[0]=$arr;
        return $ret;
    }

    public function fetchPlans($uid)
    {
        //
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        //$res=mysqli_query($con,"select drinks.name, drinks.img, did, tid, cnt, amt, phone, tdate, tstatus from drinks join trandets using (did) join transactions using (tid) where uid = $uid");
        $res = mysqli_query($con, "SELECT plans.name,plans.img,tid, planTrans.amt, tdate, status, plans.type FROM plans JOIN planTrans USING ( pid ) where uid = '$uid' ORDER BY tid DESC ");
        $arr = array();
        while ($row = $res->fetch_assoc()) {
            $arr[]= $row;
        }
        mysqli_close($con);
        return $arr;
    }

    public function showTransaction($tid)
    {
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res=mysqli_query($con, "select name, cnt, amt from trandets join drinks using(did) where tid = $tid");
        $arr = array();
        while ($row = $res->fetch_assoc()) {
            $arr[]= $row;
        }
        mysqli_close($con);
        return $arr;
    }

    public function showPendingTrans($phone)
    {
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res=mysqli_query($con, "select * from transactions where status = 'pending' and phone = '$phone'");
        $arr = array();
        while ($row = $res->fetch_assoc()) {
            $arr[]= $row;
        }
        mysqli_close($con);
        return $arr;
    }

    public function changeStatus($tid, $tstatus, $tab)
    {
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($tab=="cart") {
            $res = mysqli_query($con, "update transactions set tmode ='$tstatus' where tid = $tid");
        } else {
            $res = mysqli_query($con, "update planTrans set mode ='$tstatus' where tid = $tid");
        }

        if ($res) {
            if ($tstatus == 'online') {
                $res = mysqli_query($con, "select reward_bonus, every_purchase, purchase_bonus, ten_point from point_sch");
                $rew = mysqli_fetch_array($res);
                $phone = mysqli_fetch_array(mysqli_query($con, "select phone from transactions where tid = $tid"))[0];
                $firstPur = mysqli_fetch_array(mysqli_query($con, "select firstbuy from users where phone = '$phone'"));
                if ($firstPur[0]=="0") {
                    $res = mysqli_query($con, "update users set firstbuy = 1 where phone = '$phone'");
                    $res = mysqli_query($con, "update user_points set point = point + $rew[0] where phone = '$phone'");
                    $res = mysqli_query($con, "insert into user_points_desc values('$phone','".date("Y-m-d")."','credited','$rew[0]','Referal reward bonus')");
                    $refPhone = mysqli_fetch_array(mysqli_query($con, "select phone from users where ref = (select ref from referals where phone = '$phone')"))[0];
                    $res = mysqli_query($con, "update user_points set point = point + $rew[0] where phone = '$refPhone'");
                    $res = mysqli_query($con, "insert into user_points_desc values('$refPhone','".date("Y-m-d")."','credited','$rew[0]','Referal reward bonus')");
                    //echo "<br/>"."insert into user_points_desc values('$phone','".date("Y-m-d")."','credited','$rew[0]','Referal reward bonus'";
                }
                $total = mysqli_fetch_array(mysqli_query($con, "select sum(amt) from trandets where tid = $tid"))[0];
                //echo "<br/>".$total." ".$rew[1]." <br/>";
                if ($total > $rew[1]) {
                    $res = mysqli_query($con, "update user_points set point = point + $rew[2] where phone = '$phone'");
                    $res = mysqli_query($con, "insert into user_points_desc values('$phone','".date("Y-m-d")."','credited','$rew[2]','Higher value purchase.')");
                }
                $arr=array('response' => 'True');
            }
            if ($tstatus == 'points') {
                if ($tab=="plan") {
                    $amount = mysqli_fetch_array(mysqli_query($con, "select amt from planTrans where tid = $tid"))[0];
                    $phone = mysqli_fetch_array(mysqli_query($con, "select phone from users where uid in (select uid from planTrans where tid = $tid)"))[0];
                } else {
                    $amount = mysqli_fetch_array(mysqli_query($con, "select sum(amt) from trandets join transactions using (tid) where tid = $tid"))[0];
                    $phone = mysqli_fetch_array(mysqli_query($con, "select phone from users where uid in (select uid from transactions where tid = $tid)"))[0];
                }
                $res = mysqli_query($con, "select reward_bonus, every_purchase, purchase_bonus, ten_point from point_sch");
                $rew = mysqli_fetch_array($res);
                $amount = ($amount *10)/$rew[3];
                $res = mysqli_query($con, "update user_points set point = point - $amount where phone = '$phone'");
                $res = mysqli_query($con, "insert into user_points_desc values('$phone','".date("Y-m-d")."','debited','".$amount."','For order no $tid')");
            }
            if ($tab=="cart") {
                $myfile = fopen("../notification.txt", "w") or die("Unable to open file!");
                $txt = "newt";
                fwrite($myfile, $txt);
            } else {
                $myfile = fopen("../notification.txt", "w") or die("Unable to open file!");
                $txt = "newp";
                fwrite($myfile, $txt);
            }
            $arr=array('response' => 'True');
        } else {
            $arr=array('response' => 'False');
        }
        mysqli_close($con);
        return $arr;
    }

    public function completeTrans($tid, $table)
    {
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $cust = mysqli_fetch_array(mysqli_query($con, "select name, tid, sum(amt) from transactions join trandets using (tid) where tid = $tid group by tid"));
        if ($table=="cart") {
            $res = mysqli_query($con, "update transactions set tstatus ='complete' where tid = $tid");
        } else {
            $res = mysqli_query($con, "update planTrans set status ='complete' where tid = $tid");
        }
        if ($res) {
            $res = mysqli_query($con, "select reward_bonus, every_purchase, purchase_bonus from point_sch");
            $rew = mysqli_fetch_array($res);
            $phone = mysqli_fetch_array(mysqli_query($con, "select phone from transactions where tid = $tid"))[0];
            $firstPur = mysqli_fetch_array(mysqli_query($con, "select firstbuy from users where phone = '$phone'"));
            if ($firstPur[0]=="0") {
                $res = mysqli_query($con, "update users set firstbuy = 1 where phone = '$phone'");
                $res = mysqli_query($con, "update user_points set point = point + $rew[0] where phone = '$phone'");
                $res = mysqli_query($con, "insert into user_points_desc values('$phone','".date("Y-m-d")."','credited','$rew[0]','Referal reward bonus')");
                $refPhone = mysqli_fetch_array(mysqli_query($con, "select phone from users where ref = (select ref from referals where phone = '$phone')"))[0];
                $res = mysqli_query($con, "update user_points set point = point + $rew[0] where phone = '$refPhone'");
                $res = mysqli_query($con, "insert into user_points_desc values('$refPhone','".date("Y-m-d")."','credited','$rew[0]','Referal reward bonus')");
                //echo "<br/>"."insert into user_points_desc values('$phone','".date("Y-m-d")."','credited','$rew[0]','Referal reward bonus'";
            }
            $total = mysqli_fetch_array(mysqli_query($con, "select sum(amt) from trandets where tid = $tid"))[0];
            //echo "<br/>".$total." ".$rew[1]." <br/>";
            if ($total > $rew[1]) {
                $res = mysqli_query($con, "update user_points set point = point + $rew[2] where phone = '$phone'");
                $res = mysqli_query($con, "insert into user_points_desc values('$phone','".date("Y-m-d")."','credited','$rew[2]','Higher value purchase.')");
            }
            if ($table="cart") {
                $arr=array('response' => 'True','name'=>$cust[0],'tid'=>$cust[1],'amt'=>$cust[2]);
            } else {
                $arr=array('response' => 'True');
            }
        } else {
            $arr=array('response' => 'False');
        }
        mysqli_close($con);
        return $arr;
    }

    public function showAllTrans($phone)
    {
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res=mysqli_query($con, "select * from transactions where phone = '$phone'");
        $arr = array();
        while ($row = $res->fetch_assoc()) {
            $arr[]= $row;
        }
        mysqli_close($con);
        return $arr;
    }

    public function showTranSummary($tid)
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "select phone, tstatus, tdate, count(did), sum(amt) from transactions join trandets using(tid) where tid = $tid");
        $data = mysqli_fetch_array($res);
        if ($res) {
            $arr = array("phone"=>$data[0],"tstatus"=>$data[1],"tdate"=>$data[2],"count"=>$data[3],"amount"=>$data[4]);
        } else {
            $arr = array("response"=>"False");
        }
        mysqli_close($con);
        return $arr;
    }

    public function showPendingDash()
    {
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res=mysqli_query($con, "select tid, phone, name, tstatus, tdate FROM users join transactions using (phone) limit 10");
        $arr = array();
        while ($row = $res->fetch_assoc()) {
            $arr[]= $row;
        }
        mysqli_close($con);
        return $arr;
    }

    public function showFutureTransactions($phone)
    {
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res=mysqli_query($con, "select tdate, tstatus, transactions.tid, sum(amt) amount from transactions join trandets using (tid) where phone = '$phone' and tdate > '".date("Y-m-d")."' group by tid");
        $arr = array();
        while ($row = $res->fetch_assoc()) {
            $arr[]= $row;
        }
        mysqli_close($con);
        return $arr;
    }

    public function showProTrans($phone)
    {
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res=mysqli_query($con, "select tdate, tstatus, transactions.tid, sum(amt) amount from transactions join trandets using (tid) where phone = '$phone' and tstatus = 'process' group by tid");
        $arr = array();
        while ($row = $res->fetch_assoc()) {
            $arr[]= $row;
        }
        mysqli_close($con);
        return $arr;
    }

    public function showTranDets($phone)
    {
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res=mysqli_query($con, "select tdate, tstatus, transactions.tid, sum(amt) amount from transactions join trandets using (tid) where phone = '$phone' and tdate <= '".date("Y-m-d")."' group by tid order by tdate desc");
        $arr = array();
        while ($row = $res->fetch_assoc()) {
            $arr[]= $row;
        }
        mysqli_close($con);
        return $arr;
    }

    public function expandTrans($tid)
    {
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res=mysqli_query($con, "select name, img, cnt, amt paid from trandets join drinks using (did) where tid = $tid");
        $arr = array();
        while ($row = $res->fetch_assoc()) {
            $arr[]= $row;
        }
        mysqli_close($con);
        return $arr;
    }

    public function deleteTrans($tid, $table)
    {
        $con=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($table=="cart") {
            $mode = mysqli_fetch_array(mysqli_query($con, "select tmode from transactions where tid = $tid"))[0];
            if ($mode == "points") {
                $amt = mysqli_fetch_array(mysqli_query($con, "select sum(amt) from trandets join transactions using (tid) where tid = $tid"))[0];
                $point = mysqli_fetch_array(mysqli_query($con, "select ten_point from point_sch"))[0];
                $phone = mysqli_fetch_array(mysqli_query($con, "select phone from transactions where tid = $tid"))[0];
                $res = mysqli_query($con, "insert into user_points_desc values('$phone','".date("Y-m-d")."','credited','".($amt*$point)."','Refund for points deduction.')");
                $res = mysqli_query($con, "update user_points set point = point + ".($amt*$point)." where phone = '$phone'");
            }
            $res=mysqli_query($con, "delete from transactions where tid = $tid");
            $res=mysqli_query($con, "delete from trandets where tid = $tid");
            mysqli_close($con);
            if ($res) {
                return array("response"=>"True");
            } else {
                return array("response"=>"False");
            }
        } else {
            $mode = mysqli_fetch_array(mysqli_query($con, "select mode from planTrans where tid = $tid"))[0];
            if ($mode == "points") {
                $amt = mysqli_fetch_array(mysqli_query($con, "select amt from planTrans where tid = $tid"))[0];
                $point = mysqli_fetch_array(mysqli_query($con, "select ten_point from point_sch"))[0];
                $phone = mysqli_fetch_array(mysqli_query($con, "select phone from planTrans where tid = $tid"))[0];
                $res = mysqli_query($con, "insert into user_points_desc values('$phone','".date("Y-m-d")."','credited','".($amt*$point)."','Refund for points deduction.')");
                $res = mysqli_query($con, "update user_points set point = point + ".($amt*$point)." where phone = '$phone'");
            }
            $res=mysqli_query($con, "delete from planTrans where tid = $tid");
            mysqli_close($con);
            if ($res) {
                return array("response"=>"True");
            } else {
                return array("response"=>"False");
            }
        }
    }
}
