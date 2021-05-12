<?php
use iframe\DB\DBTable;

class User extends DBTable {
	public $id = 0;
	private $sql;
	
	function __construct() {
		$this->sql = iframe\App::$db;
		parent::__construct("User");
	}

	public function fetch($user_id, $fetch_subscriptions = true, $fetch_data = true) {
		$user = $this->getByAny($user_id);

		if(!$user) {
			throw new Exception("Can't find any user by given ID($user_id).");
            return false;
		}

		if($fetch_subscriptions) {
			$subscription_model = new Subscription;
			$user['subscriptions'] = $subscription_model->getByUser($user['id']);
		}

		if($fetch_data) {
			$location_model = new Location;
			$user['locations'] = $location_model->getByUser($user['id']);
		}

		return $user;
	}

	// $u_id means it can be either $user_id or $uid.
	public function getByAny($u_id) {
		if(!is_numeric($u_id)) {
			$user = $this->sql->getAssoc("SELECT * FROM User WHERE uid='$u_id'");
		} else {
			$user = $this->sql->getAssoc("SELECT * FROM User WHERE id=$u_id");
		}

		return $user;
	}

	public function getBothIds($u_id) {
		$user = $this->getByAny($u_id);
		return [$user['id'], $user['uid']];
	}

	public function login($data) {
		if(isset($data['uid'])) {
			$user = $this->getByAny($data['uid']);

			if(!$user) { // First Login. Register the user.
				$user['id'] = $this->register($data);
			} else {
				$this->sql->update("User", ['last_logged_on'=>'NOW()'], ['uid' => $data['uid']]);
			}

			return $this->fetch($user['id']);
		}

		return false;
	}

	public function register($data) {
		return $this->sql->insert("User", [
			'uid'	=> $data['uid'],
			'name'	=> $data['name'],
			'email'	=> $data['email'],
			'added_on'=>'NOW()',
			'last_logged_on'=>'NOW()'
		]);
	}

	// :TODO: Shouldn't these bin in Device Model?
	public function addDevice($u_id, $token) {
		list($user_id, $uid) = $this->getBothIds($u_id);

		$existing_device = $this->deviceExists($user_id, $token);
		if(!$existing_device) {
			return $this->sql->insert("Device", [
				'user_id'	=> $user_id,
				'token'		=> $token,
				'added_on'	=> 'NOW()',
				'updated_on'=> 'NOW()',
				'status'	=> '1',
			]);
		} else {
			if(!$existing_device['status']) {
				$this->sql->update("Device", ['status' => '1'], ['id' => $existing_device['id']]);
			}

			return $existing_device['id'];
		}
	}

	public function removeDevice($u_id, $token)
	{
		list($user_id, $uid) = $this->getBothIds($u_id);

		$existing_device = $this->deviceExists($user_id, $token);
		if($existing_device and $existing_device['status']) {
			$this->sql->update("Device", ['status' => '0', 'updated_on' => 'NOW()'], ['user_id' => $user_id, 'token' => $token]);
		}

		return true;
	}

	public function deviceExists($user_id, $token) {
		return $this->sql->getOne("SELECT id,status FROM Device WHERE user_id=$user_id AND token='$token'");
	}

}
