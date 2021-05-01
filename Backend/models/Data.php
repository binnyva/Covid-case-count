<?php
use iframe\DB\DBTable;

class Data extends DBTable {
	private $sql;
    private $data_api_url = "https://api.covid19india.org/v4/min/data.min.json";
	
	function __construct() {
		$this->sql = iframe\App::$db;
		parent::__construct("Data");
	}

    function fetchDataFromApi() {
        $response = load($this->data_api_url, ['cache' => true]);
        $data = json_decode($response, true);
        return $data;
    }

    function saveLatestData() {
        global $state_codes_names;

        $data = $this->fetchDataFromApi();
        $save_type = 'UPDATE';
        $update_count = 0;

        foreach($data as $state_code => $state) {
            $state_name = $state_codes_names[$state_code];

            if($this->saveDetails($state_name, $state, $save_type)) $update_count++;

            if(isset($state['districts'])) {
                foreach($state['districts'] as $district_name => $district) {
                    if($this->saveDetails($district_name, $district, $save_type)) $update_count++;
                }
            }
        }

        return $update_count;
    }

    function saveDetails($location, $details, $save_type = "UPDATE") {
        $data = [
            "name"              => $location,
            "confirmed"         => isset($details['total']['confirmed'])    ? $details['total']['confirmed']    : null,
            "confirmed_delta"   => isset($details['delta']['confirmed'])    ? $details['delta']['confirmed']    : null,
            "deceased"          => isset($details['total']['deceased'])     ? $details['total']['deceased']     : null,
            "deceased_delta"    => isset($details['delta']['deceased'])     ? $details['delta']['deceased']     : null,
            "recovered"         => isset($details['total']['recovered'])    ? $details['total']['recovered']    : null,
            "recovered_delta"   => isset($details['delta']['recovered'])    ? $details['delta']['recovered']    : null,
            "tested"            => isset($details['total']['tested'])       ? $details['total']['tested']       : null,
            "tested_delta"      => isset($details['delta']['tested'])       ? $details['delta']['tested']       : null,
            "vaccinated"        => isset($details['total']['vaccinated'])   ? $details['total']['vaccinated']   : null,
            "vaccinated_delta"  => isset($details['delta']['vaccinated'])   ? $details['delta']['vaccinated']   : null,
            "updated_on"        => isset($details['meta']['last_updated'])  ? date('Y-m-d H:i:s', strtotime($details['meta']['last_updated'])) : date('Y-m-d H:i:s')
        ];

        if($save_type === "INSERT") {
            $this->sql->insert("Location", $data);
        } else {
            $this->sql->update("Location", $data, ['name' => $location]);
        }

        // :NOTE: Rest of the code is not perticualrly useful. We are not using the Data table anywhere. Its just gathering up data.

        // Remove things not in the Data table. Data table has all updates that we have fetched - Location table only has the latest data.
        $data['added_on'] = $data['updated_on'];
        $data['location_name'] = $data['name'];
        unset($data["confirmed_delta"]);
        unset($data["deceased_delta"]);
        unset($data["recovered_delta"]);
        unset($data["tested_delta"]);
        unset($data["vaccinated_delta"]);
        unset($data['name']);
        unset($data['updated_on']);

        $new_data = false;
        $latest_data_added_on = $this->sql->getOne("SELECT added_on FROM Data WHERE location_name = '$location' ORDER BY added_on DESC LIMIT 0,1");
        if(!$latest_data_added_on or ($latest_data_added_on < $data['added_on'])) {
            $this->sql->insert("Data", $data);
            $new_data = true;
        }

        return $new_data;
    }
}
