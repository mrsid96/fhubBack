<?php
require_once 'config.php';
class PointCLass
{
    public function addPoint($phone, $point)
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "insert into user_points VALUES('$phone','$point') ON DUPLICATE KEY UPDATE point = '$point'");
        mysqli_close($con);
        if ($res) {
            return array("response" => "True");
        } else {
            return array("response" => "False");
        }
    }

    public function usePoint($phone, $point)
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "update user_points set point = point - $point");
        mysqli_close($con);
        if ($res) {
            return array("response" => "True");
        } else {
            return array("response" => "False");
        }
    }

    public function getPointScheme()
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "SELECT * FROM point_sch");
        mysqli_close($con);
        $data = mysqli_fetch_array($res);
        return array("new"=>$data[0], "reward"=>$data[1], "every"=>$data[2], "bonus" =>$data[3], "ten"=> $data[4]);
    }

    public function getPoints($phone)
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "select COALESCE(point,0) from user_points where phone = '$phone'");
        $points = mysqli_fetch_array($res)[0];
        $res = mysqli_query($con, "select ten_point from point_sch");
        $money = ($points / 10) * mysqli_fetch_array($res)[0];
        mysqli_close($con);
        if ($res) {
            return array("points" => $points, "money" => round($money));
        } else {
            return array("points" => "0");
        }
    }

    public function pointList($phone)
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "select point from user_points where phone = '$phone'");
        $data = mysqli_fetch_array($res);
        $res = mysqli_query($con, "select * from user_points_desc where phone = '$phone' order by pdate desc");
        $arr = array();
        while ($row = $res->fetch_assoc()) {
            $arr[]= $row;
        }
        mysqli_close($con);
        return array('point'=>$data[0],'details'=>$arr);
    }

    public function getPointsDesc($phone)
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "select point from user_points where phone = '$phone'");
        $points = mysqli_fetch_array($res)[0];
        $data = mysqli_fetch_array(mysqli_query($con, "select ten_point from point_sch"))[0];
        mysqli_close($con);
        if ($res) {
            return array("points" => $points, "per10" => $data);
        } else {
            return array("points" => "0");
        }
    }
}
