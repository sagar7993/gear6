<?php
class Feedback extends G6_Admincontroller {
	public function __construct() {
		parent::__construct();
		$this->data['page'] = 'admin';
	}
	public function index() {
		redirect(site_url('admin/feedback/vendors'));
	}
	private function get_feedback_questions($return) {
		$this->db->select('*');
		$this->db->from('execfbqs');
		$this->db->where('isEnabled', 1);
		$this->db->order_by('ExecFbQId', 'asc');
		$query = $this->db->get();
		$result = $query->result_array();
		if($return == "data") {
			$this->data['feedback'] = $result;
		} elseif($return == "return") {
			return $result;
		} elseif($return == "echo") {
			echo json_encode($result);
		}
	}
	public function vendors() {
		$this->data['active'] = 'vfeedback';
		$this->aauth->check_uri_access('vfeedback');
		$this->data['rows'] = $this->get_user_feedbacks();
		$this->load->view('admin/vfback', $this->data);
	}
	public function vfeedview($OId = NULL) {
		if($OId === NULL || !$this->admin_m->is_valid_oid($OId)) {
			redirect(site_url('admin/feedback/vendors'));
		} else {
			$adminNotifyFlag['new_feedback_dismissed'] = 1;
			$this->db->where('OId', $OId);
			$this->db->update('admin_notification_flags', $adminNotifyFlag);
			$this->data['active'] = 'vfeedback';
			$this->aauth->check_uri_access('vfeedback');
			$this->get_feedback_questions("data");
			$this->data['oid'] = $OId;
			$this->data['remarks'] = $this->db->select('user_feedback_remarks')->from('odetails')->where('OId', $OId)->get()->result_array()[0]['user_feedback_remarks'];
			$this->data['od_fback'] = $this->get_user_ratings($OId);
			$this->load->view('admin/vfview', $this->data);
		}
	}
	public function get_user_feedbacks() {
		$this->db->select('odetails.OId, odetails.user_feedback_remarks AS Feedback, user.UserId, user.UserName, user.Phone, servicecenter.ScName, FORMAT(AVG(ExecFbAnswer), "1") AS Rating');
		$this->db->from('user_feedback');
		$this->db->join('odetails', 'odetails.OId = user_feedback.OId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('servicecenter', 'servicecenter.ScId = oservicedetail.ScId', 'left');
		$this->db->group_by('user_feedback.OId');
		$result = $this->db->get()->result_array();
		return $result;
	}
	public function get_user_ratings($OId) {
		$this->db->select('*');
		$this->db->from('user_feedback');
		$this->db->where('OId', $OId);
		$this->db->order_by('ExecFbQId', 'asc');
		$result = $this->db->get()->result_array();
		if(count($result) > 0) {
			return $result;
		} else {
			return NULL;
		}
	}
}