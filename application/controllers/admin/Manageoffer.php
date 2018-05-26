<?php
class Manageoffer extends G6_Admincontroller {
	public function __construct() {
		parent::__construct();
		$this->data['page'] = 'manageOffer';
		$this->aauth->check_page_access('manageOffer');
	}
	public function index() {
		$this->data['active'] = 'add_offer';
		$this->load->view('admin/addoffer', $this->data);
	}
	public function editOffer() {
		$this->data['active'] = 'edit_offer';
		$this->data['rows'] = $this->get_offer_list();
		$this->load->view('admin/editoffer', $this->data);
	}
	public function addReferral() {
		$this->data['active'] = 'add_referral';
		$this->load->view('admin/addreferral', $this->data);
	}
	public function editReferral() {
		$this->data['active'] = 'edit_referral';
		$this->data['rows'] = $this->get_referral_list();
		$this->load->view('admin/editreferral', $this->data);
	}
	public function delete_referral_offer() {
		if($_POST) {
			$this->load->model('fcoupons_m');
			$couponId = intval($this->input->post('delete_referral_coupon_id'));
			$this->fcoupons_m->delete($couponId);
			redirect(site_url('admin/manageOffer/editReferral'));
		}
	}
	public function delete_offer() {
		if($_POST) {
			$this->load->model('coupons_m');
			$couponId = intval($this->input->post('delete_offer_id'));
			$this->coupons_m->delete($couponId);
			redirect(site_url('admin/manageOffer/editOffer'));
		}
	}
	public function modify_referral() {
		if($_POST) {
			$this->load->model('fcoupons_m');
			$fields = array("referral_coupon_code", "referral_valid_till");
			$data_fields = array("CCode", "ValidTill");
			$count = 0;	$couponData = array(); $test = TRUE;
			foreach($fields as $field) {
				if(!(bool)$this->input->post($field)) {
					$test = FALSE;
				}
				$couponData[$data_fields[$count]] = $this->input->post($field);
				$count += 1;
			}
			if(!(bool)$this->input->post("referral_user_id")) {
				$couponData["UserId"] = NULL;
			}
			else {
				$couponData["UserId"] = $this->input->post("referral_user_id");
			}
			$couponData["CAmount"] = intval($this->input->post("referral_coupon_amount"));
			if($this->fcoupons_m->is_own_coupon_code($couponData['CCode'], intval($this->input->post('referral_coupon_code_id'))) && $test) {
				$this->fcoupons_m->save($couponData, intval($this->input->post('referral_coupon_code_id')));
			} else {
				$this->data['err_offer'] = "This Coupon Code already exsits. Please double check.";
			}
			redirect(site_url('admin/manageOffer/editReferral'));
		}
	}
	public function modify_offer() {
		if($_POST) {
			$this->load->model('coupons_m');
			$fields = array("coupon_code", "coupon_type", "validFrom", "validTill");
			$data_fields = array("CCode", "CType", "ValidFrom", "ValidTill");
			$count = 0;	$couponData = array(); $test = TRUE;
			foreach($fields as $field) {
				if(!(bool)$this->input->post($field)) {
					$test = FALSE;
				}
				$couponData[$data_fields[$count]] = $this->input->post($field);
				$count += 1;
			}
			if(!(bool)$this->input->post("user_id")) {
				$couponData["UserId"] = NULL;
			}
			else {
				$couponData["UserId"] = $this->input->post("user_id");
			}
			if(!(bool)$this->input->post("service_id")) {
				$couponData["ServiceId"] = NULL;
			}
			else {
				$couponData["ServiceId"] = $this->input->post("service_id");
			}
			$couponData["CAmount"] = intval($this->input->post("coupon_amount"));
			$couponData["MaxDiscount"] = intval($this->input->post("maximum_discount"));
			$couponData["MinPurchase"] = intval($this->input->post("minimum_purchase"));
			$couponData["MaxUses"] = intval($this->input->post("maximum_uses"));
			$couponData["PerUserLimit"] = intval($this->input->post("per_user_limit"));
			$couponData["isEnabled"] = intval($this->input->post("is_enabled"));
			if($this->coupons_m->is_own_coupon_code($couponData['CCode'], intval($this->input->post('coupon_code_id'))) && $test) {
				$this->coupons_m->save($couponData, intval($this->input->post('coupon_code_id')));
			} else {
				$this->data['err_offer'] = "This Coupon Code already exsits. Please double check.";
			}
			redirect(site_url('admin/manageOffer/editOffer'));
		}
	}
	public function create_offer() {
		if($_POST) {
			$this->load->model('coupons_m');
			$fields = array("coupon_code", "coupon_type", "validFrom", "validTill");
			$data_fields = array("CCode", "CType", "ValidFrom", "ValidTill");
			$count = 0;	$couponData = array(); $test = TRUE;
			foreach($fields as $field) {
				if(!(bool)$this->input->post($field)) {
					$test = FALSE;
				}
				$couponData[$data_fields[$count]] = $this->input->post($field);
				$count += 1;
			}
			if(!(bool)$this->input->post("user_id")) {
				$couponData["UserId"] = NULL;
			}
			else {
				$couponData["UserId"] = intval($this->input->post("user_id"));
			}
			if(!(bool)$this->input->post("service_id")) {
				$couponData["ServiceId"] = NULL;
			}
			else {
				$couponData["ServiceId"] = intval($this->input->post("service_id"));
			}
			$couponData["CAmount"] = intval($this->input->post("coupon_amount"));
			$couponData["MaxDiscount"] = intval($this->input->post("maximum_discount"));
			$couponData["MinPurchase"] = intval($this->input->post("minimum_purchase"));
			$couponData["MaxUses"] = intval($this->input->post("maximum_uses"));
			$couponData["PerUserLimit"] = intval($this->input->post("per_user_limit"));
			$couponData["isEnabled"] = intval($this->input->post("is_enabled"));
			if($this->coupons_m->is_unique_coupon_code($couponData['CCode']) && $test) {
				$this->coupons_m->save($couponData);
				redirect(site_url('admin/manageOffer/editOffer'));
			} else {
				if($test) {
					$this->data['err_offer'] = "This Coupon Code already exsits. Please double check.";
				} else {
					$this->data['err_offer'] = "Nah! You cannot do that";
				}
				redirect(site_url('admin/manageOffer'));
			}
		}
	}
	public function create_referral() {
		if($_POST) {
			$this->load->model('fcoupons_m');
			$fields = array("referral_coupon_code", "referral_valid_till");
			$data_fields = array("CCode", "ValidTill");
			$count = 0;	$couponData = array(); $test = TRUE;
			foreach($fields as $field) {
				if(!(bool)$this->input->post($field)) {
					$test = FALSE;
				}
				$couponData[$data_fields[$count]] = $this->input->post($field);
				$count += 1;
			}
			if(!(bool)$this->input->post("referral_user_id")) {
				$couponData["UserId"] = NULL;
			}
			else {
				$couponData["UserId"] = $this->input->post("referral_user_id");
			}
			$couponData["CAmount"] = intval($this->input->post("referral_coupon_amount"));
			if($this->fcoupons_m->is_unique_coupon_code($couponData['CCode']) && $test) {
				$this->fcoupons_m->save($couponData);
				redirect(site_url('admin/manageOffer/editReferral'));
			} else {
				if($test) {
					$this->data['err_offer'] = "This Coupon Code already exsits. Please double check.";
				} else {
					$this->data['err_offer'] = "Nah! You cannot do that";
				}
				redirect(site_url('admin/manageOffer/addReferral'));
			}
		}
	}
	private function get_offer_list() {
		$this->db->select('*');
		$this->db->from('coupons');
		$this->db->join('user', 'user.UserId = coupons.UserId', 'left');
		$this->db->join('service', 'service.ServiceId = coupons.ServiceId', 'left');
		$this->db->order_by('coupons.CouponId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach ($results as &$result) {
				if($result['UserId'] != NULL) {
					$result['UserId'] = trim($result['UserName']) . " (" . trim($result['Phone']) . ") (" . $result['UserId'] . ")";
				}
				if($result['ServiceId'] != NULL) {
					$result['ServiceId'] = trim($result['ServiceName']) . " (" . $result['ServiceId'] . ")";
				}
			}
			return $results;
		}
	}
	private function get_referral_list() {
		$this->db->select('*');
		$this->db->from('fcoupons');
		$this->db->join('user', 'user.UserId = fcoupons.UserId', 'left');
		$this->db->order_by('fcoupons.FCouponId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach ($results as &$result) {
				if($result['UserId'] != NULL) {
					$result['UserId'] = trim($result['UserName']) . " (" . trim($result['Phone']) . ") (" . $result['UserId'] . ")";
				}
			}
			return $results;
		}
	}
	public function get_user_ajax() {
		if($_POST) {
			$output = array();
			$this->db->select('*');
			$this->db->from('user');
			$this->db->like('UserName', $this->input->post('user_id'), 'both');
			$this->db->or_like('UserId', $this->input->post('user_id'), 'both');
			$this->db->or_like('Phone', $this->input->post('user_id'), 'both');
			$query = $this->db->get();
			$results = $query->result_array();
			if (count($results) > 0) {
				foreach($results as $result) {
					$output[] = $result['UserName'] . " (" . $result['Phone'] . ") (" . $result['UserId'] . ")";
				}
			}
			echo json_encode($output);
		}
	}
	public function get_service_ajax() {
		if($_POST) {
			$output = array();
			$this->db->select('*');
			$this->db->from('service');
			$this->db->like('ServiceName', $this->input->post('service_id'), 'both');
			$query = $this->db->get();
			$results = $query->result_array();
			if (count($results) > 0) {
				foreach($results as $result) {
					$output[] = $result['ServiceName'] . " (" . $result['ServiceId'] . ")";
				}
			}
			echo json_encode($output);
		}
	}
}