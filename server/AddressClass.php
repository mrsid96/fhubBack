<?php
require_once 'config.php';

class AddressClass{

	public function fetchList($phone)
	{
		$con=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		$res=mysqli_query($con,"select * from address where phone = '$phone'");
		$arr = array();
		while($row = $res->fetch_assoc())
		{
			$arr[]= $row;
		}
		mysqli_close($con);
		return array("address"=>$arr);
	}

	public function addAddress($phone, $address)
	{
		$con=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		$res=mysqli_query($con,"insert into address (phone,address) values ('$phone','$address')");
		//echo "insert into address values ('$phone','$address')"."<br/>";
		if($res)
			$arr = array("response"=>"True");
		else
			$arr = array("response"=>"False");
		mysqli_close($con);
		return $arr;
	}

	public function delAddress($id)
	{
		$con=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
		$res=mysqli_query($con,"delete from address where aid = $id");
		if($res)
			$arr = array("response"=>"True");
		else
			$arr = array("response"=>"False");
		mysqli_close($con);
		return $arr;
	}
}
?>
