<?php
class City_m extends G6_Model {
	protected $_table_name = 'city';
	protected $_primary_key = 'CityId';
	protected $_order_by = 'CityName';
	public function __construct() {
		parent::__construct();
	}
	public function get_new() {
		$city = new stdClass();
		$city->CityName = '';
		$city->AdvTime = '';
		return $city;
	}
	public function set_city($cityId = NULL) {
		if(!$cityId) {
			$cityId = intval($this->input->post('city'));
		}
		$cookie = array(
			'name'   => 'CityId',
			'value'  => $cityId,
			'expire' => '2595000',
			'secure' => FALSE
		);
		$this->input->set_cookie($cookie);
	}
	public function remove_city() {
		delete_cookie('CityId');
	}
	public function iscityset() {
		return (bool) $this->input->cookie('CityId');
	}
	public function get_sc_advtime() {
		$this->db->select('city.AdvTime');
		$this->db->from('scaddrsplit');
		$this->db->join('location', 'location.LocationId = scaddrsplit.LocationId');
		$this->db->join('city', 'city.CityId = location.CityId');
		$this->db->where('scaddrsplit.ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return intval($result[0]['AdvTime']) / 24;
		}
	}
	public function get_enabled_cities() {
		$this->db->select('city.CityId, city.CityName');
		$this->db->from('city');
		$this->db->where('city.CityId !=', -1);
		$this->db->where('city.isEnabled', 1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return array();
		} else {
			return $result;
		}
	}
}