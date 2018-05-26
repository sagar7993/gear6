<?php
class Offers extends G6_Admincontroller {
	public function __construct() {
		parent::__construct();
		$this->data['page'] = 'manageOffer';
		$this->aauth->check_page_access('manageOffer');
	}
	public function index() {
		$this->data['active'] = 'offers_oview';
		$this->data['rows1'] = $this->get_all_coffers();
		$this->data['rows2'] = $this->get_all_foffers();
		$this->load->view('admin/off_oview', $this->data);
	}
	public function add_offers($id = NULL, $type = NULL) {
		$this->data['active'] = 'add_offer';
		$this->load->view('admin/add_offer', $this->data);
	}
	private function get_all_coffers() {
		$this->db->select('*');
		$this->db->from('ucontactus');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	private function get_all_foffers() {
		$this->db->select('*');
		$this->db->from('ucontactus');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
}