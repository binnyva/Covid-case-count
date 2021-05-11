<?php
require 'common.php';

$route = new iframe\Route;
$user_model = new User;
$subscription_model = new Subscription;
$location_model = new Location;
$data_model = new Data;
$jsend = new JSend;

header("Content-type: application/json");
header("Access-Control-Allow-Origin: *"); // Shouldn't be active online.
header("Access-Control-Allow-Methods: GET,POST,OPTIONS,DELETE");
header("Access-Control-Allow-Headers: *");

// Data is send in payload - move it to QUERY... 
$request_payload = file_get_contents('php://input');
if($request_payload) {
    $QUERY = json_decode($request_payload, true);
}

$route->post('/users/login', function(){
    global $user_model, $QUERY;
    $user = $QUERY;
    $user['name'] = $QUERY['displayName'];
    $data = $user_model->login($user);

    if($data) echo JSEND::success($data);
    else echo JSEND::fail("Invalid Login");
});

$route->get('/users/{user_id}', function($user_id) {
    global $user_model;
    $user = $user_model->fetch($user_id);
    if(!$user) return JSEND::fail();

    $return = [
        'uid'           => $user['uid'],
        'displayName'   => $user['name'],
        'email'         => $user['email'],
        'subscriptions' => $user['subscriptions'],
        'locations'     => $user['locations'],
    ];
    echo JSEND::success($return);
});

$route->get('/users/{user_id}/subscriptions', function($user_id) {
    global $subscription_model;
    $subs = $subscription_model->getByUser($user_id);

    echo JSEND::success($subs);
});

$route->post('/users/{user_id}/subscriptions', function($user_id) {
    global $subscription_model, $location_model, $QUERY;
    $subscription_model->updateSubscriptions($user_id, $QUERY['subscriptions']);

	$subs_data = $location_model->getByUser($user_id);

    echo JSEND::success($subs_data);
});

$route->post('/users/{user_id}/devices', function($user_id) {
    global $user_model, $QUERY;
    $token = $QUERY['token'];

    if($user_model->addDevice($user_id, $token)) {
        echo JSEND::success(['token' => $token]);
    } else {
        echo JSEND::error("Couldn't add new device to user ID: $user_id");
    }
});
$route->delete('/users/{user_id}/devices/{token}', function($user_id, $token) {
    global $user_model, $QUERY;

    if($user_model->removeDevice($user_id, $token)) {
        echo JSEND::success(['token' => $token]);
    } else {
        echo JSEND::error("Couldn't remove device from user ID: $user_id");
    }
});

$route->get('/cron', function() {
    global $data_model;

    $updated_count = $data_model->saveLatestData();

    if(date('H') == 10) {
        $message = new Message;
        $notification = $message->makeNotification(1); // Right now, sent message only to Binny
      
        $token = 'eTC_RaltDn5FDnUnLtH8im:APA91bGE_X1vMHfW8aW78y8Jw-5o0WMLGN-1a6NUHb2sQhBKSehRWImXs8-TeasiSF3O7RGh2BvC0c8ATlf0XvsdsDXf0BjeV5bLpoNklDE4oJsoqDHncXsnK3yhk31YGysw60oDbDSe';
        $message->send($token, "Covid Case Count", $notification);    
    }
    
    echo JSEND::success(['data' => "Updated $updated_count locations."]);
});

$route->get('/test', function() {
    $message = new Message;
    $notification = $message->makeNotification(1);
  
    $token = 'eTC_RaltDn5FDnUnLtH8im:APA91bGE_X1vMHfW8aW78y8Jw-5o0WMLGN-1a6NUHb2sQhBKSehRWImXs8-TeasiSF3O7RGh2BvC0c8ATlf0XvsdsDXf0BjeV5bLpoNklDE4oJsoqDHncXsnK3yhk31YGysw60oDbDSe';
    // $token = 'dU1R6jC_WdTRRAphkMDEh0:APA91bFNH64LNWswZy5kaSjE3mFDsfwrTrp0EkpZYgJpyYzBgq8lT415IntHeOfDL3G3_yVLd9g8VNFhO1isobTItb6A5iHNYhR05QKzed7uGhBgBLJ0nAF4HNRWRHgfqkeCJ-xiUECR';
    $response = $message->send($token, "Covid Case Count", $notification);

    echo JSEND::success($response);
});
$route->handle();

/*

	users/{user_id}/locations
	users/login
	users/register
*/
