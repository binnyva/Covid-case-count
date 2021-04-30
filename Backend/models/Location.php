<?php
use iframe\DB\DBTable;

class Location extends DBTable {
	private $sql;
	
	function __construct() {
		$this->sql = iframe\App::$db;
		parent::__construct("Location");
	}

    function get($location, $data_point = "all") {
        $data = $this->sql->getAssoc("SELECT * FROM Location WHERE name='$location'");

        if(isset($data[$data_point])) return $data[$data_point];

        return $data;
    }
}