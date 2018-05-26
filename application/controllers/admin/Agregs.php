<?php
class Agregs extends G6_Admincontroller {
	public function __construct() {
		parent::__construct();
		$this->data['page'] = 'agregs';
		$this->aauth->check_page_access('agregs');
	}
	public function index() {
		$this->data['active'] = 'agrg_oview';
		$this->data['rows'] = $this->get_agrgreqs();
		$adminNotifyFlag['new_agent_contact_us_dismissed'] = 1;
		$this->db->where('new_agent_contact_us', 1);
		$this->db->update('admin_notification_flags', $adminNotifyFlag);
		$this->load->view('admin/agregs', $this->data);
	}
	private function get_agrgreqs() {
		$this->db->select('*');
		$this->db->from('agregs');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('agregs.CityId', intval($this->session->userdata('a_city_id')));
		}
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
}