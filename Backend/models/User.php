<?php
use iframe\DB\DBTable;

class User extends DBTable {
	public $id = 0;
	private $sql;
	
	function __construct() {
		$this->sql = iframe\App::$db;
		parent::__construct("User");
	}

	public function fetch($user_id, $fetch_subscriptions = true) {
		$user = $this->getByAny($user_id);

		if($fetch_subscriptions) {
			$subscription_model = new Subscription;
			$user['subscriptions'] = $subscription_model->getByUser($user['id']);
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

			return $user['id'];
		}
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

}
