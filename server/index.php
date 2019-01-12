<?php
require_once 'UserManagement.php';
require_once 'SMSManagerClass.php';
require_once 'ProductClass.php';
require_once 'TransactionControl.php';
require_once 'HomeBasic.php';
require_once 'AddressClass.php';
require_once 'PointClass.php';

if(isset($_GET["name"]) && isset($_GET["phone"]) && isset($_GET["pass"]) && isset($_GET["ref"]))
{
    $user_mgmt = new UserManagement();
    echo json_encode($user_mgmt->register($_GET["name"],$_GET["phone"],$_GET["pass"],$_GET["ref"]));
}
else if(isset($_GET["check_new"]))
{
	$trans = new TransactionControl();
	echo json_encode($trans->checkNew());
}
else if(isset($_GET["check_version"]))
{
	$trans = new TransactionControl();
	echo json_encode($trans->checkVersion());
}
else if(isset($_POST["signTID"]) && isset($_POST["signIMG"]))
{
	$trans = new TransactionControl();
	echo json_encode($trans->addPhoto($_POST["signTID"],$_POST["signIMG"]));
}
else if (isset($_GET['flush']))
{
  $trans = new TransactionControl();
  echo json_encode($trans->flush());
}
else if (isset($_GET['fetchPlansTod']))
{
  $trans = new TransactionControl();
  echo json_encode($trans->showTodayPlans());
}
else if (isset($_GET['fetchDrinksTod']))
{
  $trans = new TransactionControl();
  echo json_encode($trans->showTodayDrinks());
}
else if(isset($_GET['forget_request']))
{
    $user_mgmt = new UserManagement();
    echo json_encode($user_mgmt->forgotPasswordRequest($_GET['forget_request']));
}//resendOTP
else if(isset($_GET['resendOTP']))
{
    $user_mgmt = new UserManagement();
    echo json_encode($user_mgmt->resendOTP($_GET['resendOTP']));
}
else if(isset($_GET['forget_username']) && isset($_GET['forget_pass']))
{
    $user_mgmt = new UserManagement();
    echo json_encode($user_mgmt->forgotPasswordUpdate($_GET['forget_username'],$_GET['forget_pass']));
}
else if(isset($_GET["phone"]) && isset($_GET["pass"]))
{
    $user_mgmt = new UserManagement();
    echo json_encode($user_mgmt->login($_GET["phone"],$_GET["pass"]));
}
else if(isset($_GET["register_otp_phone"]) && isset($_GET["register_otp"]))
{
    $otp = new SMSManagerClass();
    echo json_encode($otp->verifyOTPRegister($_GET["register_otp_phone"],$_GET["register_otp"]));
}
else if(isset($_GET["custom_otp_phone"]) && isset($_GET["custom_otp"]))
{
    $otp = new SMSManagerClass();
    echo json_encode($otp->verifyOTPCustom($_GET["custom_otp_phone"],$_GET["custom_otp"]));
}
else if (isset($_GET['checkPhone']))
{
    $user_mgmt = new UserManagement();
    echo json_encode($user_mgmt->checkNumber($_GET["checkPhone"]));
}
//addDrink
else if(isset($_GET['drink_name']) && isset($_GET['drink_desc']) && isset($_GET['drink_price']) && isset($_GET['drink_img']))
{
    $drink = new ProductClass();
    echo json_encode($drink->addDrink($_GET['drink_name'],$_GET['drink_desc'],$_GET['drink_price'],$_GET['drink_img']));
}
//updateDrink(id, name, desc, price)
else if(isset($_GET['upd_drink_name']) && isset($_GET['upd_drink_desc']) && isset($_GET['upd_drink_price']) && isset($_GET['upd_drink_id']))
{
    $drink = new ProductClass();
    echo json_encode($drink->updateDrink($_GET['upd_drink_id'],$_GET['upd_drink_name'],$_GET['upd_drink_desc'],$_GET['upd_drink_price']));
}
//deleteDrink(id)
else if (isset($_GET['delete_drink']))
{
    $drink = new ProductClass();
    echo json_encode($drink->deleteDrink($_GET["delete_drink"]));
}//showWeekPlans
else if (isset($_GET['showWeekPlans']))
{
    $drink = new ProductClass();
    echo json_encode($drink->showWeekPlans());
}
else if (isset($_GET['showTrail']))
{
    $drink = new ProductClass();
    echo json_encode($drink->showTrail());
}
else if (isset($_GET['showMonthlyPlans']))
{
    $drink = new ProductClass();
    echo json_encode($drink->showMonthlyPlans());
}
else if (isset($_GET['showWeekPlan']))
{
    $drink = new ProductClass();
    echo json_encode($drink->showWeekPlan($_GET["showWeekPlan"]));
}

else if (isset($_GET['showMonthPlan']))
{
    $drink = new ProductClass();
    echo json_encode($drink->showMonthPlan($_GET["showMonthPlan"]));
}
//showDrink
else if (isset($_GET['show_drink']))
{
    $drink = new ProductClass();
    echo json_encode($drink->showDrink($_GET["show_drink"]));
}//
else if (isset($_GET['showPlan']))
{
    $drink = new ProductClass();
    echo json_encode($drink->showPlan($_GET["showPlan"]));
}
else if (isset($_GET['showAllPlan']))
{
    $drink = new ProductClass();
    echo json_encode($drink->showAllPlan());
}
//showAllDrink()
else if (isset($_GET['show_drink_all']))
{
    $drink = new ProductClass();
    echo json_encode($drink->showAllDrink());
}
else if(isset($_GET["tran_name"]) && isset($_GET["tran_add"]) && isset($_GET["tran_uid"]) && isset($_GET["tran_time"]) && isset($_GET["tran_phone"]) && isset($_GET["data"]) && isset($_GET["date"]))
{
    $trans = new TransactionControl();
    echo json_encode($trans->addTransaction($_GET["tran_name"],$_GET["tran_add"],$_GET["tran_uid"],$_GET["tran_time"],$_GET["tran_phone"],$_GET["data"],$_GET["date"]));
}
//addPlan_trans($name,$pid,$phone,$address,$time,$sdate,$endate,$amt,$quantity)
else if(isset($_GET["plan_name"]) && isset($_GET["plan_pid"]) && isset($_GET["plan_phone"]) && isset($_GET["plan_uid"]) && isset($_GET["plan_add"]) && isset($_GET["plan_time"]) && isset($_GET["plan_stdate"]) && isset($_GET["plan_endate"]) && isset($_GET["plan_amt"]) && isset($_GET["plan_quantity"]))
{
    $trans = new TransactionControl();
    echo json_encode($trans->addPlan_trans($_GET["plan_name"],$_GET["plan_pid"],$_GET["plan_phone"],$_GET["plan_uid"],$_GET["plan_add"],$_GET["plan_time"],$_GET["plan_stdate"],$_GET["plan_endate"],$_GET["plan_amt"],$_GET["plan_quantity"]));
}
else if(isset($_GET["tran_dets"]))
{
    $trans = new TransactionControl();
    echo json_encode($trans->showTransaction($_GET["tran_dets"]));
}//
else if(isset($_GET["completeTrans"]) && isset($_GET["completeTransTab"]))
{
    $trans = new TransactionControl();
    echo json_encode($trans->completeTrans($_GET["completeTrans"],$_GET["completeTransTab"]));
}
else if(isset($_GET["fetchOrders"]) && isset($_GET["status"]))
{
    $trans = new TransactionControl();
    echo json_encode($trans->fetchOrders($_GET["fetchOrders"],$_GET["status"]));
}
else if(isset($_GET["fetchOrders"]))
{
    $trans = new TransactionControl();
    echo json_encode($trans->fetchOrdersAll($_GET["fetchOrders"]));
}
else if(isset($_GET["fetchPlans"]))
{
    $trans = new TransactionControl();
    echo json_encode($trans->fetchPlans($_GET["fetchPlans"]));
}
else if(isset($_GET["order_tid"]) && isset($_GET["table"]))
{
    $trans = new TransactionControl();
    echo json_encode($trans->showTN($_GET["order_tid"],$_GET["table"]));
}
else if(isset($_GET["showProTrans"]))
{
    $trans = new TransactionControl();
    echo json_encode($trans->showProTrans($_GET["showProTrans"]));
}
else if(isset($_GET["showFutureTransactions"]))
{
    $trans = new TransactionControl();
    echo json_encode($trans->showFutureTransactions($_GET["showFutureTransactions"]));
}
else if(isset($_GET["deleteTrans"]) && isset($_GET["table_del"]))
{
    $trans = new TransactionControl();
    echo json_encode($trans->deleteTrans($_GET["deleteTrans"],$_GET["table_del"]));
}
else if(isset($_GET["expandTrans"]))
{
    $trans = new TransactionControl();
    echo json_encode($trans->expandTrans($_GET["expandTrans"]));
}
else if(isset($_GET["showTranDets"]))
{
    $trans = new TransactionControl();
    echo json_encode($trans->showTranDets($_GET["showTranDets"]));
}
else if(isset($_GET["tid_dets"]))
{
    $trans = new TransactionControl();
    echo json_encode($trans->showTranSummary($_GET["tid_dets"]));
}
else if(isset($_GET["show_pending_trans"]))
{
    $trans = new TransactionControl();
    echo json_encode($trans->showPendingTrans($_GET["show_pending_trans"]));
}
else if(isset($_GET["show_all_trans"]))
{
    $trans = new TransactionControl();
    echo json_encode($trans->showAllTrans($_GET["show_all_trans"]));
}
else if(isset($_GET["showPendingDash"]))
{
    $trans = new TransactionControl();
    echo json_encode($trans->showPendingDash());
}
else if(isset($_GET["updTrans"]) && isset($_GET["updTransDet"]) && isset($_GET["table"]))
{
    $trans = new TransactionControl();
    echo json_encode($trans->changeStatus($_GET["updTrans"],$_GET["updTransDet"],$_GET["table"]));
}
else if(isset($_GET["showMessage"]))
{
    $user = new UserManagement();
    echo json_encode($user->getMessages($_GET["showMessage"]));
}

else if(isset($_GET["showReferal"]))
{
    $user = new UserManagement();
    echo json_encode($user->getReferal($_GET["showReferal"]));
}

else if(isset($_GET["msg_phone"]) && isset($_GET["msg_msg"]))
{
    $user = new UserManagement();
    echo json_encode($user->addMessage($_GET["msg_phone"],$_GET["msg_msg"]));
}
else if (isset($_GET["fetchBanner"]))
{
    $home = new HomeBasic();
    echo json_encode($home->fetchBanner());
}
else if (isset($_GET["fetchNews"]))
{
    $home = new HomeBasic();
    echo json_encode($home->fetchNews());
}
else if (isset($_GET["fetchAddress"]))
{
    $address = new AddressClass();
    echo json_encode($address->fetchList($_GET["fetchAddress"]));
}
else if (isset($_GET["addPhone"]) && isset($_GET["addAddress"]))
{
    $address = new AddressClass();
    echo json_encode($address->addAddress($_GET["addPhone"],$_GET["addAddress"]));
}//veiwPoint
else if (isset($_GET["veiwPoint"]))
{
    $point = new PointClass();
    echo json_encode($point->getPoints($_GET["veiwPoint"]));
}//
else if (isset($_GET["getPointsDesc"]))
{
    $point = new PointClass();
    echo json_encode($point->getPointsDesc($_GET["getPointsDesc"]));
}
else if (isset($_GET["pointList"]))
{
    $point = new PointClass();
    echo json_encode($point->pointList($_GET["pointList"]));
}
else {
    echo "##GTFO - THIS IS API SERVER ##";
}
?>
