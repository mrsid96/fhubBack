<?php
require_once 'config.php';
class ProductClass
{
    public function addDrink($name, $desc, $price, $img)
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "insert into drinks(name,des,price,img) VALUES('$name','$desc','$price','$img')");
        mysqli_close($con);
        if($res)
            return array("response" => "True");
        else
            return array("response" => "False");
    }

    public function updateDrink($id, $name, $desc, $price)
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "update drinks set name = '$name', des = '$desc', price = '$price' where id = $id");
        mysqli_close($con);
        if($res)
            return array("response" => "True");
        else
            return array("response" => "False");
    }

    public function deleteDrink($id)
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "delete from drinks where did = '$id'");
        mysqli_close($con);
        if($res)
            return array("response" => "True");
        else
            return array("response" => "False");
    }

    public function showDrink($id)
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "select * from drinks where did = $id");
        mysqli_close($con);
        if($res)
        {
            while($row = $res->fetch_assoc())
            {
                $arr = $row;
            }
        }
        else{
            return array("response" => "False");
        }
        // echo sizeof($arr);
        // if(sizeof($arr)==0)
        //     return array("response"=>"False");
        return array("drinks" =>array($arr));
    }

    public function showWeekPlans()
    {
    	$con = $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "select * from plans where type = 'w'");
        $arr = array();
        while($row = $res->fetch_assoc())
        {
            $arr[] = $row;
        }
        return array("plan"=>$arr);
    }

    public function showMonthlyPlans()
    {
    	$con = $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "select * from plans where type = 'm'");
        $arr = array();
        while($row = $res->fetch_assoc())
        {
            $arr[] = $row;
        }
        return array("plans"=>$arr);
    }

    public function showWeekPlan($pid)
    {
    	$con = $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "select name,img,pdesc,amt,dids,pid from plans where type = 'w' and pid = $pid");
        $data = mysqli_fetch_array($res);
        $json = json_decode($data[4]);
        $arr['name']=$data[0];
        $arr['img']=$data[1];
        $arr['pdesc']=$data[2];
        $arr['pid']=$data[5];
        $arr['amt']=$data[3];

        foreach ($json as $key => $value){
			$res = mysqli_query($con, "select did, name, des, ben, nut, ing, price, img from drinks where did = $key");
            $data = mysqli_fetch_array($res);
            $arr['data'][] = array("did"=>$data[0],"name"=>$data[1],"des"=>$data[2],"ben"=>$data[3],"nut"=>$data[4],"ing"=>$data[5],"days"=>$value,"price"=>$data[6],"img"=>$data[7]);
		}

        return $arr;
    }

    public function showTrail()
    {
    	$con = $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "select name,img,pdesc,amt,dids,pid from plans where type = 't'");
        $ar=array();
        while($row = $res->fetch_assoc())
        {
            $arr = array();
            $str = explode(",",$row['dids']);
            $arr['name']=$row['name'];
            $arr['img']=$row['img'];
            $arr['pdesc']=$row['pdesc'];
            $arr['pid']=$row['pid'];
            $arr['amt']=$row['amt'];
            $unstr = array_unique($str);
            for($i=0;$i<sizeof($unstr);$i++)
    	    {
    	        $drink = mysqli_query($con, "select did, name, des, ben, nut, ing, price, img from drinks where did = '$str[$i]'");
                $data = mysqli_fetch_array($drink);
                $arr['drinks'][] = array("did"=>$data[0],"name"=>$data[1],"des"=>$data[2],"ben"=>$data[3],"nut"=>$data[4],"ing"=>$data[5],"price"=>$data[6],"img"=>$data[7]);
            }
            //array_push($ar,$arr);
            $ar[] = $arr;
        }
        return array("trail"=>$ar);
    }

    public function showMonthly()
    {
    	$con = $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "select name,img,pdesc,amt,dids,pid from plans where type = 'm'");
        $ar=array();
        while($row = $res->fetch_assoc())
        {
            $arr = array();
            $arr['name']=$row['name'];
            $arr['img']=$row['img'];
            $arr['pdesc']=$row['pdesc'];
            $arr['pid']=$row['pid'];
            $arr['amt']=$row['amt'];
            $json = json_decode($row['dids']);
            foreach ($json as $key => $value){
    			$res_drinks = mysqli_query($con, "select did, name, des, ben, nut, ing, price, img from drinks where did = $key");
                $data = mysqli_fetch_array($res_drinks);
                $arr['drinks'][] = array("did"=>$data[0],"name"=>$data[1],"des"=>$data[2],"days"=>$value,"ben"=>$data[3],"nut"=>$data[4],"ing"=>$data[5],"price"=>$data[6],"img"=>$data[7]);
    		}
            //array_push($ar,$arr);
            $ar[] = $arr;
        }
        return array("plans"=>$ar);
    }

    public function showWeekly()
    {
    	$con = $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "select name,img,pdesc,amt,dids,pid from plans where type = 'w'");
        $ar=array();
        while($row = $res->fetch_assoc())
        {
            $arr = array();
            $arr['name']=$row['name'];
            $arr['img']=$row['img'];
            $arr['pdesc']=$row['pdesc'];
            $arr['pid']=$row['pid'];
            $arr['amt']=$row['amt'];
            $json = json_decode($row['dids']);
            foreach ($json as $key => $value){
    			$res_drinks = mysqli_query($con, "select did, name, des, ben, nut, ing, price, img from drinks where did = $key");
                $data = mysqli_fetch_array($res_drinks);
                $arr['drinks'][] = array("did"=>$data[0],"name"=>$data[1],"des"=>$data[2],"days"=>$value,"ben"=>$data[3],"nut"=>$data[4],"ing"=>$data[5],"price"=>$data[6],"img"=>$data[7]);
    		}
            //array_push($ar,$arr);
            $ar[] = $arr;
        }
        return array("plans"=>$ar);
    }

    public function showPlanDetails($pid)
    {
    	$con = $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "select name,img,pdesc,amt,dids,pid from plans where pid = $pid");
        $data = mysqli_fetch_array($res);
        $json = json_decode($data[4]);
        $arr['name']=$data[0];
        $arr['img']=$data[1];
        $arr['pdesc']=$data[2];
        $arr['pid']=$data[5];
        $arr['amt']=$data[3];

        foreach ($json as $key => $value){
			$res = mysqli_query($con, "select did, name, des, ben, nut, ing, price, img from drinks where did = $key");
            $data = mysqli_fetch_array($res);
            $arr['drinks'][] = array("did"=>$data[0],"name"=>$data[1],"des"=>$data[2],"ben"=>$data[3],"nut"=>$data[4],"ing"=>$data[5],"days"=>$value,"price"=>$data[6],"img"=>$data[7]);
		}

        return array("plans"=>array($arr));
    }

    public function showTrailDetails($id)
    {
    	$con = $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "select name,img,pdesc,amt,dids,pid from plans where pid = $id");
        $ar=array();
        while($row = $res->fetch_assoc())
        {
            $arr = array();
            $str = explode(",",$row['dids']);
            $arr['name']=$row['name'];
            $arr['img']=$row['img'];
            $arr['pdesc']=$row['pdesc'];
            $arr['pid']=$row['pid'];
            $arr['amt']=$row['amt'];
            $unstr = array_unique($str);
            for($i=0;$i<sizeof($unstr);$i++)
    	    {
                $drink = mysqli_query($con, "select did, name, des, ben, nut, ing, price, img from drinks where did = '$str[$i]'");
                $data = mysqli_fetch_array($drink);
                $arr['drinks'][] = array("did"=>$data[0],"name"=>$data[1],"des"=>$data[2],"ben"=>$data[3],"nut"=>$data[4],"ing"=>$data[5],"price"=>$data[6],"img"=>$data[7]);
            }
            //array_push($ar,$arr);
            //$ar[] = $arr;
        }
        return array("trail"=>array($arr));
    }

    public function showMonthPlan($pid)
    {
    	$con = $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "select name,img,pdesc,amt,dids,pid from plans where type = 'm' and pid = $pid");
        $data = mysqli_fetch_array($res);
        $json = json_decode($data[4]);
        $arr['name']=$data[0];
        $arr['img']=$data[1];
        $arr['pdesc']=$data[2];
        $arr['pid']=$data[5];
        $arr['amt']=$data[3];

        foreach ($json as $key => $value){
			$res = mysqli_query($con, "select did, name, des, ben, nut, ing, price, img from drinks where did = $key");
            $data = mysqli_fetch_array($res);
            $arr['data'][] = array("did"=>$data[0],"name"=>$data[1],"des"=>$data[2],"ben"=>$data[3],"nut"=>$data[4],"ing"=>$data[5],"days"=>$value,"price"=>$data[6],"img"=>$data[7]);
			//$days[$key] = $value;
		}

        return $arr;
    }

    public function showPlan($pid)
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "select * from plans where pid = $pid");
        $arr = array();
        mysqli_close($con);
        while($row = $res->fetch_assoc())
        {
            $arr[] = $row;
        }
        return $arr;
    }

    public function showAllDrink()
    {
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $res = mysqli_query($con, "select * from drinks");
        $arr = array();
        mysqli_close($con);
        while ($row = $res->fetch_assoc()) {
            # code...
            $arr[] = $row;
        }
        return array("drinks" => $arr);
    }



    // public function addCategory(){}

    // public function showCategories(){}

    // public function addProduct(){}

    // public function showProducts(){}

    // public function updateCategory(){}

    // public function updateProduct(){}

    // public function deleteCategory(){}

    // public function deleteProduct(){}

    // public function addProductImages(){}

    // public function showProductImages(){}

    public function addReview(){}

    public function updateReview(){}

    public function deleteReveiw(){}

    public function showReview(){}
}

?>
