<?php
use iframe\DB\DBTable;

class Subscription extends DBTable {
	public $id = 0;
	private $sql;
    private $user_model;
	
	function __construct() {
		$this->sql = iframe\App::$db;
        $this->user_model = new User;
		parent::__construct("Subscription");
	}

	public function getByUser($u_id) {
        list($user_id, $uid) = $this->user_model->getBothIds($u_id);
        if($user_id) {
            return $this->sql->getCol("SELECT location_name FROM Subscription WHERE user_id=$user_id");
        } else {
            throw new Exception("Can't find any user by given ID($u_id).");
            return false;
        }
	}

	public function updateSubscriptions($u_id, $location_list) {
        list($user_id, $uid) = $this->user_model->getBothIds($u_id);
        if(!$user_id) {
            throw new Exception("Can't find any user by given ID($u_id).");
            return false;
        }
        
        // :TODO: Obtimize this a bit?
        $return = [];
        $this->sql->execQuery("DELETE FROM Subscription WHERE user_id=$user_id");
        foreach($location_list as $loc) {
            $status = $this->sql->insert("Subscription", [
                'location_name' => $loc, 
                'user_id' => $user_id, 
                'added_on' => 'NOW()'
            ]);
            if($status) {
                $return[] = $loc;
            }
        }
        return $return;
	}
}
