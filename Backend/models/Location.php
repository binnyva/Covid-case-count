<?php
use iframe\DB\DBTable;

class Location extends DBTable {
	private $sql;
	
	function __construct() {
		$this->sql = iframe\App::$db;
        $this->user_model = new User;
		parent::__construct("Location");
	}

    function fetch($location, $data_point = "all") {
        $data = $this->sql->getAssoc("SELECT * FROM Location WHERE name='$location'");

        if(isset($data[$data_point])) return $data[$data_point];

        return $data;
    }

    function getByUser($u_id) {
        list($user_id, $uid) = $this->user_model->getBothIds($u_id);
        if($user_id) {
            $location_data = $this->sql->getAll("SELECT L.* FROM Location L 
                INNER JOIN Subscription S ON S.location_name=L.name 
                WHERE S.user_id=$user_id");
            return $location_data;
        } else {
            throw new Exception("Can't find any user by given ID($u_id).");
            return false;
        }
    }
}