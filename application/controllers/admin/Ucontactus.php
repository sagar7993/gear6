<?php
class Ucontactus extends G6_Admincontroller {
	public function __construct() {
		parent::__construct();
		$this->data['page'] = 'ucus';
	}
	public function index() {
		$this->data['active'] = 'ucus_oview';
		$this->data['rows'] = $this->get_ucusreqs();
		$adminNotifyFlag['new_user_contact_us_dismissed'] = 1;
		$this->db->where('new_user_contact_us', 1);
		$this->db->update('admin_notification_flags', $adminNotifyFlag);
		$this->load->view('admin/ucus', $this->data);
	}
	private function get_ucusreqs() {
		$this->db->select('*');
		$this->db->from('ucontactus');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('ucontactus.CityId', intval($this->session->userdata('a_city_id')));
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