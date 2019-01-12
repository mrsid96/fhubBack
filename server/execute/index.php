<?php
require_once '../UserManagement.php';
require_once '../SMSManagerClass.php';
require_once '../ProductClass.php';
require_once '../TransactionControl.php';
require_once '../HomeBasic.php';
require_once '../AddressClass.php';
require_once '../PointClass.php';

//$params = file_get_contents('php://input');
//echo getCurrentUri();
header("Content-type:application/json");
$params = json_decode(file_get_contents('php://input'), TRUE);
if($params!=null)
{
    if($params['class']=='usermgmt')
    {
        if($params['type']=='login')
        {
            $user_mgmt = new UserManagement();
            echo json_encode($user_mgmt->login($params['data']['username'],$params['data']['password']));
        }
        else if($params['type']=='register')
        {
            $user_mgmt = new UserManagement();
            echo json_encode($user_mgmt->register($params['data']['name'],$params['data']['phone'],$params['data']['password'],$params['data']['ref']));
        }
        else if($params['type']=='forgot')
        {
            $user_mgmt = new UserManagement();
            echo json_encode($user_mgmt->forgotPasswordRequest($params['data']['phone']));
        }
        else if($params['type']=='resendOTP')
        {
            $user_mgmt = new UserManagement();
            echo json_encode($user_mgmt->resendOTP($params['data']['phone']));
        }
        else if($params['type']=='passupd')
        {
            $user_mgmt = new UserManagement();
            echo json_encode($user_mgmt->forgotPasswordUpdate($params['data']['phone'],$params['data']['password']));
        }
        else if ($params['type']=='checkuser')
        {
            $user_mgmt = new UserManagement();
            echo json_encode($user_mgmt->checkNumber($params['data']['phone']));
        }
        else if($params['type']=='getReferal')
        {
            $user = new UserManagement();
            echo json_encode($user->getReferal($params['data']['phone']));
        }
        else if($params['type']=='sendMessage')
        {
            $user = new UserManagement();
            echo json_encode($user->addMessage($params['data']['phone'],$params['data']['body']));
        }
        else if ($params['type']=='pullAddress')
        {
            $address = new AddressClass();
            echo json_encode($address->fetchList($params['data']['phone']));
        }
        else if ($params['type']=='addAddress')
        {
            $address = new AddressClass();
            echo json_encode($address->addAddress($params['data']['phone'],$params['data']['address']));
        }
        else if ($params['type']=='delAddress')
        {
            $address = new AddressClass();
            echo json_encode($address->delAddress($params['data']['id']));
        }
        else {
            echo json_encode(array("response"=>"GTFO"));
        }
    }
    else if($params['class']=='smsmgmt')
    {
        if($params['type']=='verRegOTP')
        {
            $otp = new SMSManagerClass();
            echo json_encode($otp->verifyOTPRegister($params['data']['phone'],$params['data']['otp']));
        }

        else if($params['type']=='verCusOTP')
        {
            $otp = new SMSManagerClass();
            echo json_encode($otp->verifyOTPCustom($params['data']['phone'],$params['data']['otp']));
        }

        else {
            echo json_encode(array("response"=>"GTFO"));
        }
    }
    else if($params['class']=='normal')
    {
        if($params['type']=='pullBanner')
        {
            $home = new HomeBasic();
            echo json_encode($home->fetchBanner());
        }
        else {
            echo json_encode(array("response"=>"GTFO"));
        }
    }
    else if($params['class']=='product')
    {
        if($params['type']=='alldrinks')
        {
            $drink = new ProductClass();
            echo json_encode($drink->showAllDrink());
        }
        else if($params['type']=='alltrials')
        {
            $drink = new ProductClass();
            echo json_encode($drink->showTrail());
        }
        else if($params['type'] == 'individualDrink')
        {
            $drink = new ProductClass();
            echo json_encode($drink->showDrink($params['data']['id']));
        }
        else if($params['type'] == 'individualTrial')
        {
            $plan = new ProductClass();
            echo json_encode($plan->showTrailDetails($params['data']['id']));
        }//
        else if($params['type'] == 'individualPlan')
        {
            $plan = new ProductClass();
            echo json_encode($plan->showPlanDetails($params['data']['id']));
        }
        else if($params['type'] == 'weekly')
        {
            $drink = new ProductClass();
            echo json_encode($drink->showWeekly());
        }
        else if($params['type'] == 'monthly')
        {
            $plan = new ProductClass();
            echo json_encode($plan->showMonthly());
        }
        else {
            echo json_encode(array("response"=>"GTFO"));
        }
    }
    else if($params['class']=='push')
    {
        $data = array (
                      'interests' =>
                      array (
                        0 => $params['channel'],
                      ),
                      'webhookUrl' => 'http://mysite.com/push-webhook',
                      'fcm' =>
                      array (
                        'notification' =>
                        array (
                          'title' => $params['data']['title'],
                          'body' => $params['data']['message'],
                          'sound' => 'default'
                        ),
                        "data" => array(
                           "navigator" => $params['data']['navigator'],
                           "data" => $params['data']['id']
                           #"thumbnail" => $params['data']['thumbnail'],
                           #"attachment-url" => $params['data']['url'],
                         ),
                      ),
                  );
        $data_string = json_encode($data);
        $ch = curl_init('https://40347688-e8bc-4e43-86f5-1cfa742c125e.pushnotifications.pusher.com/publish_api/v1/instances/40347688-e8bc-4e43-86f5-1cfa742c125e/publishes');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer 464C427E34D1DB7E2BDCAAA2A973F09'
        ));
        echo curl_exec($ch);
    }
    else if($params['class']=='points')
    {
        if($params['type']=='fetchPoints')
        {
            $point = new PointCLass();
            echo json_encode($point->getPoints($params['data']['phone']));
        }
        if($params['type']=='fetchAllPoints')
        {
            $point = new PointCLass();
            echo json_encode($point->pointList($params['data']['phone']));
        }
        if($params['type']=='pointScheme')
        {
            $point = new PointCLass();
            echo json_encode($point->getPointScheme());
        }
    }
    else if($params['class']=='transaction')
    {
        if($params['type']=='checkVersion')
        {
        	$trans = new TransactionControl();
        	echo json_encode($trans->checkVersion());
        }
        else if($params['type']=='flush')
        {
          $trans = new TransactionControl();
          echo json_encode($trans->flush());
        }
        else if($params['type']=='showPlansToday')
        {
          $trans = new TransactionControl();
          echo json_encode($trans->showTodayPlans());
        }
        else if($params['type']=='showDrinksToday')
        {
          $trans = new TransactionControl();
          echo json_encode($trans->showTodayDrinks());
        }
        else if($params['type']=='addTransaction')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->addTransaction($params['data']['name'],$params['data']['address'],$params['data']['time'],$params['data']['uid'],$params['data']['phone'],$params['data']['status'],$params['data']['mode'],$params['data']['drinks'],$params['data']['trials']));
            //echo sizeof($params['data']['drinks']);
        }
        else if($params['type']=='pullSummary')
        {
          $trans = new TransactionControl();
          echo json_encode($trans->pullSummary($params['data']['tid'],$params['data']['type']));
        }
        else if($params['type']=='planTransaction')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->addPlan_trans($params['data']['name'], $params['data']['pid'], $params['data']['phone'], $params['data']['uid'], $params['data']['address'], $params['data']['time'], $params['data']['sdate'], $params['data']['edate'], $params['data']['amount'], $params['data']['quantity'], $params['data']['mode']));
        }
        else if($params['type']=='pullPlanTrans')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->getPlanTransDetails($params['data']['tid']));
        }
        else if($params['type']=='pullPlansByOrders')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->getPlansByStatus($params['data']['uid'],$params['data']['status']));
        }
        else if($params['type']=='showTransactionDetail')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->showTN($params['data']['tid'],$params['data']['type']));
        }
        else if($params['type']=='completeTransaction')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->completeTrans($params['data']['tid'],$params['data']['table']));
        }
        else if($params['type']=='pullOrdersBy')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->fetchOrders($params['data']['uid'],$params['data']['status']));
        }
        else if($params['type']=='showDrinksToday')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->fetchOrdersAll($_GET["fetchOrders"]));
        }
        else if($params['type']=='showDrinksToday')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->fetchPlans($_GET["fetchPlans"]));
        }
        else if($params['type']=='showDrinksToday')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->showTN($_GET["order_tid"],$_GET["table"]));
        }
        else if($params['type']=='showDrinksToday')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->showProTrans($_GET["showProTrans"]));
        }
        else if($params['type']=='showDrinksToday')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->showFutureTransactions($_GET["showFutureTransactions"]));
        }
        else if($params['type']=='showDrinksToday')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->deleteTrans($_GET["deleteTrans"],$_GET["table_del"]));
        }
        else if($params['type']=='showDrinksToday')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->expandTrans($_GET["expandTrans"]));
        }
        else if($params['type']=='showDrinksToday')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->showTranDets($_GET["showTranDets"]));
        }
        else if($params['type']=='showDrinksToday')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->showTranSummary($_GET["tid_dets"]));
        }
        else if($params['type']=='showDrinksToday')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->showPendingTrans($_GET["show_pending_trans"]));
        }
        else if($params['type']=='showDrinksToday')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->showAllTrans($_GET["show_all_trans"]));
        }
        else if($params['type']=='showDrinksToday')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->showPendingDash());
        }
        else if($params['type']=='showDrinksToday')
        {
            $trans = new TransactionControl();
            echo json_encode($trans->changeStatus($_GET["updTrans"],$_GET["updTransDet"],$_GET["table"]));
        }
    }
    else {
        echo json_encode(array("response"=>"GTFO"));
    }
}
else {
    echo json_encode(array("response"=>"GTFO"));
}
?>

