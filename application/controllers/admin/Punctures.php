<?php
class Punctures extends G6_Admincontroller {
	public function __construct() {
		parent::__construct();
		$this->data['page'] = 'pts';
		$this->aauth->check_page_access('pts');
	}
	public function index() {
		$this->data['active'] = 'pt_oview';
		$this->load->view('admin/ptoview', $this->data);
	}
	public function ptlist() {
		$this->data['active'] = 'pt_list';
		$this->data['rows'] = $this->get_ptlist();
		$this->load->view('admin/ptlist', $this->data);
	}
	private function get_ptlist() {
		$this->db->select('PTScId, ScName, Phone, Email, LocationName');
		$this->db->from('punctures');
		$this->db->join('location', 'location.LocationId = punctures.LocationId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('punctures.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('isVerified', 1);
		$this->db->order_by('punctures.PTScId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
}