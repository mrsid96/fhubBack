<?php
require_once 'config.php';

class HomeBasic{

	public function fetchBanner()
	{
		$con=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		$res=mysqli_query($con,"select img from banners");
		$arr = array();
		while($row = $res->fetch_assoc())
		{
			$arr[]= $row;
		}
		$res=mysqli_query($con,"select item from news");
		$arr2 = array();
		while($row = $res->fetch_assoc())
		{
			$arr2[]= $row;
		}
		mysqli_close($con);
		$retArr = array("banner"=>$arr,"news" => $arr2);
		return $retArr;
	}
}
?>