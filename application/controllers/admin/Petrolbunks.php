<?php
class Petrolbunks extends G6_Admincontroller {
	public function __construct() {
		parent::__construct();
		$this->data['page'] = 'pbs';
		$this->aauth->check_page_access('pbs');
	}
	public function index() {
		$this->data['active'] = 'pb_oview';
		$this->load->view('admin/pboview', $this->data);
	}
	public function pblist() {
		$this->data['active'] = 'pb_list';
		$this->data['rows'] = $this->get_pblist();
		$this->load->view('admin/pblist', $this->data);
	}
	private function get_pblist() {
		$this->db->select('PBId, PBName, ServiceProvider, Phone, Email, LocationName');
		$this->db->from('petrolbunks');
		$this->db->join('location', 'location.LocationId = petrolbunks.LocationId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('petrolbunks.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('isVerified', 1);
		$this->db->order_by('petrolbunks.PBId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
}