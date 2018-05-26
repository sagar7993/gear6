<?php
class Location_m extends G6_Model {
	protected $_table_name = 'location';
	protected $_primary_key = 'LocationId';
	protected $_order_by = 'LocationName';
	public function __construct() {
		parent::__construct();
	}
	public function get_new() {
		$location = new stdClass();
		$location->LocationName = '';
		$location->CityId = '';
		$location->Latitude = '';
		$location->Longitude = '';
		return $location;
	}
	public function get_sc_location() {
		$this->db->select('location.LocationName');
		$this->db->from('scaddrsplit');
		$this->db->join('location', 'location.LocationId = scaddrsplit.LocationId');
		$this->db->where('scaddrsplit.ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return convert_to_camel_case($result[0]['LocationName']);
		}
	}
	public function locations_by_vendor() {
		$this->db->select('location.CityId');
		$this->db->from('scaddrsplit');
		$this->db->join('location', 'location.LocationId = scaddrsplit.LocationId');
		$this->db->where('scaddrsplit.ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $this->locations_for_sc(intval($result[0]['CityId']));
		}
	}
	public function locations_for_sc($city = NULL) {
		$this->db->select('LocationName');
		$this->db->from('location');
		if($city === NULL) {
			$this->db->where('CityId', intval($this->input->cookie('CityId')));
		} else {
			$this->db->where('CityId', intval($city));
		}
		$query = $this->db->get();
		$result = array();
		foreach ($query->result_array() as $row) {
			$result[] = $row['LocationName'];
		}
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
	public function location_row_by_name($lc_name = NULL) {
		$this->db->select('Latitude, Longitude, LocationId');
		$this->db->from('location');
		if(isset($lc_name)) {
			$this->db->where('LocationName', $lc_name);
		} else {
			$this->db->where('LocationName', $this->input->cookie('area'));
		}
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0];
		}
	}
	public function location_id_by_name($location) {
		$this->db->select('LocationId');
		$this->db->from('location');
		$this->db->where('LocationName', $location);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0];
		}
	}
	public function location_by_id($id) {
		$this->db->select('LocationName');
		$this->db->from('location');
		$this->db->where('LocationId', intval($id));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0]['LocationName'];
		}
	}
}