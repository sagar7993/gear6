<?php
class Pucs extends G6_Admincontroller {
	public function __construct() {
		parent::__construct();
		$this->data['page'] = 'ecs';
		$this->aauth->check_page_access('ecs');
	}
	public function index() {
		$this->data['active'] = 'ec_oview';
		$this->load->view('admin/ecoview', $this->data);
	}
	public function eclist() {
		$this->data['active'] = 'ec_list';
		$this->data['rows'] = $this->get_eclist();
		$this->load->view('admin/eclist', $this->data);
	}
	private function get_eclist() {
		$this->db->select('ECId, ECName, LicenseExpiry, Phone, Email, LocationName');
		$this->db->from('pucs');
		$this->db->join('location', 'location.LocationId = pucs.LocationId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('pucs.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('isVerified', 1);
		$this->db->order_by('pucs.ECId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
}