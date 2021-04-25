<?php
require 'common.php';

$route = new iframe\Route;
$user_model = new User;
$subscription_model = new Subscription;
$jsend = new JSend;

header("Content-type: application/json");

$route->post('/users/login', function(){
    global $user_model;
    $user = $QUERY;
    $user['name'] = $QUERY['displayName'];
    $user_model->login($user);
});

$route->get('/users/{user_id}', function($user_id) {
    global $user_model;
    $user = $user_model->fetch($user_id);

    $return = [
        'uid'           => $user['uid'],
        'displayName'   => $user['name'],
        'email'         => $user['email'],
        'subscriptions' => $user['subscriptions']
    ];
    echo JSEND::success($return);
});

$route->get('/users/{user_id}/subscriptions', function($user_id) {
    global $subscription_model;
    $subs = $subscription_model->getByUser($user_id);

    echo JSEND::success($subs);
});

$route->post('/users/{user_id}/subscriptions', function($user_id) {
    global $subscription_model;
    $subscription_model->updateSubscriptions($user_id, $QUERY['subscriptions']);

    echo JSEND::success($subs);
});
$route->handle();

/*

	users/{user_id}/locations
	users/login
	users/register
*/
