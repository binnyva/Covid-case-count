<?php
require 'common.php';
$message = new Message;
$notification = $message->makeNotification(1);

$token = 'eTC_RaltDn5FDnUnLtH8im:APA91bGE_X1vMHfW8aW78y8Jw-5o0WMLGN-1a6NUHb2sQhBKSehRWImXs8-TeasiSF3O7RGh2BvC0c8ATlf0XvsdsDXf0BjeV5bLpoNklDE4oJsoqDHncXsnK3yhk31YGysw60oDbDSe';
// $token = 'dU1R6jC_WdTRRAphkMDEh0:APA91bFNH64LNWswZy5kaSjE3mFDsfwrTrp0EkpZYgJpyYzBgq8lT415IntHeOfDL3G3_yVLd9g8VNFhO1isobTItb6A5iHNYhR05QKzed7uGhBgBLJ0nAF4HNRWRHgfqkeCJ-xiUECR';
$response = $message->send($token, "Covid Case Count", $notification);

dump($response);
