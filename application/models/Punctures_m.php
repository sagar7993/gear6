<?php
class Punctures_m extends G6_Model {
	protected $_table_name = 'punctures';
	protected $_primary_key = 'PTScId';
	protected $_order_by = 'ScName';
	public function __construct() {
		parent::__construct();
	}
	public function get_pt_locations($city_id = NULL) {
		$this->db->select('PTScId as ScId, LocationId, Latitude, Longitude');
		$this->db->from('punctures');
		$this->db->where('punctures.isVerified', 1);
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
	public function get_service_provider($PBId) {
		$this->db->select('ScName, Phone');
		$this->db->from('punctures');
		$this->db->where('PTScId', intval($PBId));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0];
		}
	}
	public function get_ptsc_timings($pt_id) {
		$this->db->select('*');
		$this->db->from('pbectimings');
		$this->db->where('ScType', 'pt');
		$this->db->where('ScId', intval($pt_id));
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
	public function get_app_ptsc_timings($pt_id) {
		$this->db->select('*');
		$this->db->from('pbectimings');
		$this->db->where('ScType', 'pt');
		$this->db->where('ScId', intval($pt_id));
		$this->db->limit(7);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_ptsc_by_id($pt_id) {
		$this->db->select('punctures.*, LocationName, CityName');
		$this->db->from('punctures');
		$this->db->join('location', 'location.LocationId = punctures.LocationId');
		$this->db->join('city', 'city.CityId = punctures.CityId');
		$this->db->where('PTScId', intval($pt_id));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0];
		}
	}
}