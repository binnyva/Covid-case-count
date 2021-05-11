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
		return ['response' => $response, 'message' => $message];
	}

	public function makeNotification($user_id) {
		$user_model = new User;
		$data = $user_model->fetch($user_id);
		
		$line_format = "%LOCATION%: %CASE_COUNT% cases(%NEW_CASES% new), %NEW_DEATHS% deaths";

		$lines = [];
		foreach($data['locations'] as $loc) {
			$replaces = [
				'%LOCATION%'	=> $loc['name'],
				'%CASE_COUNT%'	=> $this->number($loc['confirmed']),
				'%NEW_CASES%'	=> $this->number($loc['confirmed_delta']),
				'%DEATH_COUNT%'	=> $this->number($loc['deceased']),
				'%NEW_DEATHS%'	=> $this->number($loc['deceased_delta']),
				
			];
			$lines[] = str_replace(array_keys($replaces), array_values($replaces), $line_format);
		}

		$body = implode("\n", $lines);
		return $body;
	}

	public function sendAll() {
		// https://firebase-php.readthedocs.io/en/stable/cloud-messaging.html#send-multiple-messages-at-once
		$message = CloudMessage::fromArray([
			'token' => $token,
			'notification' => ['title' => $title, 'body' => $body]
		]);
		$all_messages = [ $message ];

		$response = $this->messaging->sendAll($all_messages);

	}

	/**
	 * Function that converts a numeric value into an exact abbreviation
	 * https://ourcodeworld.com/articles/read/786/how-to-create-the-abbreviation-of-a-number-in-php , edited for indian context(L,C intead of M,B)
	 */
	private function number( $n, $precision = 1 ) {
		if ($n < 900) {
			// 0 - 900
			$n_format = number_format($n, $precision);
			$suffix = '';
		} else if ($n < 90000) {
			// 0.9k-850k
			$n_format = number_format($n / 1000, $precision);
			$suffix = 'K';
		} else if ($n < 9000000) {
			// 0.9l-850l
			$n_format = number_format($n / 100000, $precision);
			$suffix = 'L';
		} else  {
			// 0.9c-850c
			$n_format = number_format($n / 10000000, $precision);
			$suffix = 'C';
		}

  	    // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
	    // Intentionally does not affect partials, eg "1.50" -> "1.50"
		if ( $precision > 0 ) {
			$dotzero = '.' . str_repeat( '0', $precision );
			$n_format = str_replace( $dotzero, '', $n_format );
		}
		return $n_format . $suffix;
	}
	
}
