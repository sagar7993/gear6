<?php
class Pucs_m extends G6_Model {
	protected $_table_name = 'pucs';
	protected $_primary_key = 'ECId';
	protected $_order_by = 'ECName';
	public function __construct() {
		parent::__construct();
	}
	public function get_ec_locations($city_id = NULL) {
		$this->db->select('ECId as ScId, LocationId, Latitude, Longitude');
		$this->db->from('pucs');
		$this->db->where('pucs.isVerified', 1);
		if(isset($city_id)) {
			$this->db->where('CityId', intval($city_id));
		} else {
			$this->db->where('CityId', intval($this->input->cookie('CityId')));
		}
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
	public function get_service_provider($ECId) {
		$this->db->select('ECName');
		$this->db->from('pucs');
		$this->db->where('ECId', intval($ECId));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0]['ECName'];
		}
	}
	public function get_puc_type($ECId) {
		$this->db->select('ECType');
		$this->db->from('pucs');
		$this->db->where('ECId', intval($ECId));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0]['ECType'];
		}
	}
	public function get_pbec_timings($ec_id){
		$this->db->select('*');
		$this->db->from('pbectimings');
		$this->db->where('ScType', 'ec');
		$this->db->where('ScId', intval($ec_id));
		$this->db->limit(7);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			$final_result = '<li class="col s12 m3 collection-item left"><div class="left-align">Timings : </div></li>';
			foreach($results as $result) {
				$final_result .= '<li class="col s12 m3 collection-item left"><div class="left-align">' . convert_to_camel_case($result['Day']) . ' :<span class="secondary-content">' . $result['STime'] . ' - ' . $result['ETime'] . '</span></div></li>';
			}
			return $final_result;
		}
	}
	public function get_pbec_by_id($ec_id) {
		$this->db->select('pucs.*, LocationName, CityName');
		$this->db->from('pucs');
		$this->db->join('location', 'location.LocationId = pucs.LocationId');
		$this->db->join('city', 'city.CityId = pucs.CityId');
		$this->db->where('ECId', intval($ec_id));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0];
		}
	}
	public function get_ec_prices($ec_id) {
		$this->db->select('*');
		$this->db->from('ecprice');
		$this->db->where('ScType', 'ec');
		$this->db->where('ScId', intval($ec_id));
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			$final_result = '';
			foreach($results as $result) {
				if($result['VehicleType'] == '2w') {
					$final_result .= '<li class="col s12 m6 collection-item left"><div class="left-align">' . convert_to_camel_case($result['FuelType']) . ' Price (2 - Wheeler) : <span class="secondary-content">@' . $result['Price'] . ' INR</span></div></li>';
				}
			}
			return $final_result;
		}
	}
}