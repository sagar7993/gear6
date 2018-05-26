<?php
class Feedback extends G6_Vendorcontroller {
	public function __construct() {
		parent::__construct();
	}
	public function index () {
		$this->load->model('ratingcategory_m');
		$this->data['active'] = 'feedback';
		$this->data['rows'] = $this->ratingcategory_m->get_all_feedbacks();
		$this->load->view('vendor/feedbacks', $this->data);
	}
	public function feedview($OId = NULL) {
		if($OId === NULL || !$this->is_valid_oid($OId)) {
			redirect(site_url('/vendor'));
		} else {
			$this->load->model('ratingcategory_m');
			$this->data['active'] = 'feedback';
			$this->data['oid'] = $OId;
			$this->data['od_fback'] = $this->ratingcategory_m->get_fback_by_oid($OId);
			if($this->data['od_fback'] !== NULL) {
				$this->rel_fb_notif($OId);
			}
			$this->load->view('vendor/feedview', $this->data);
		}
	}
	private function is_valid_oid($oid) {
		$this->db->select('COUNT(*)');
		$this->db->from('oservicedetail');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('OId', $oid);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		if($result['COUNT(*)'] == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	private function rel_fb_notif($oid) {
		$data = array('isFbNotified' => 2);
		$this->db->where('OId', $oid);
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('isFbNotified', 1);
		$this->db->update('oservicedetail', $data);
	}
}