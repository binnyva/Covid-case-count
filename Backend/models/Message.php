<?php
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class Message {
	private $sql;
	private $messaging;
	
	function __construct() {
		$this->sql = iframe\App::$db;

		$secret_file = realpath(__DIR__ . '/../secrets/covid-case-count-firebase-adminsdk-1bo6s-fcb9e54bd3.json');

		$factory = (new Factory)->withServiceAccount($secret_file);
		$this->messaging = $factory->createMessaging();
	}

	public function send($token, $title, $body) {
		$message = CloudMessage::fromArray([
			'token' => $token,
			'notification' => ['title' => $title, 'body' => $body]
		]);

		$response = $this->messaging->send($message);
		dump($response);
	}

	public function makeNotifications() {

	}

	public function sendAll() {
		// https://firebase-php.readthedocs.io/en/stable/cloud-messaging.html#send-multiple-messages-at-once
		$message = CloudMessage::fromArray([
			'token' => $token,
			'notification' => ['title' => $title, 'body' => $body]
		]);
		$all_messages = [ $message ];

		$response = $this->messaging->sendAll($message);

	}
}
