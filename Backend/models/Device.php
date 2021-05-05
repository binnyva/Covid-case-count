<?php
use iframe\DB\DBTable;

class Device extends DBTable {
	private $sql;
	
	function __construct() {
		$this->sql = iframe\App::$db;
        $this->user_model = new User;
		parent::__construct("Device");
	}

    public function getAll() {
        return $this->sql->getAll("SELECT user_id, token FROM Device WHERE status='1'");
    }

    public function getByUser($user_id) {
        return $this->sql->getCol("SELECT token FROM Device WHERE status='1' AND user_id=$user_id");
    }
}
