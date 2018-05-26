<?php
class Coupons_m extends G6_Model {
	protected $_table_name = 'coupons';
	protected $_primary_key = 'CouponId';
	protected $_order_by = 'CouponId';
	public function __construct() {
		parent::__construct();
	}
	public function get_coupon_row($ccode) {
		$this->db->select('*');
		$this->db->from('coupons');
		$this->db->where('CCode', $ccode);
		$this->db->where('isEnabled', 1);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_fcoupon_row($fccode) {
		$this->db->select('*');
		$this->db->from('fcoupons');
		$this->db->where('CCode', $fccode);
		$this->db->limit(1);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results[0];
		}
	}
	public function get_total_coupon_usage($cid) {
		$this->db->select('COUNT(odetails.OId) AS UCount');
		$this->db->from('odetails');
		$this->db->where('CouponId', $cid);
		$query = $this->db->get();
		$result = $query->row_array();
		return intval($result['UCount']);
	}
	public function get_user_coupon_usage($cid, $UserId = FALSE) {
		$this->db->select('COUNT(odetails.OId) AS UCount');
		$this->db->from('odetails');
		if($UserId) {
			$this->db->where('odetails.UserId', $UserId);
		} else {
			$this->db->where('odetails.UserId', intval($this->session->userdata('id')));
		}
		$this->db->where('CouponId', $cid);
		$this->db->group_by('odetails.OId');
		$query = $this->db->get();
		$result = $query->row_array();
		return intval($result['UCount']);
	}
	public function get_user_fcoupon_usage($cid, $UserId = FALSE) {
		$this->db->select('COUNT(odetails.OId) AS UCount');
		$this->db->from('odetails');
		if($UserId) {
			$this->db->where('odetails.UserId', $UserId);
		} else {
			$this->db->where('odetails.UserId', intval($this->session->userdata('id')));
		}
		$this->db->where('FCouponId', $cid);
		$this->db->group_by('odetails.OId');
		$query = $this->db->get();
		$result = $query->row_array();
		return intval($result['UCount']);
	}
	public function get_orders_count($UserId = FALSE) {
		$this->db->select('COUNT(odetails.OId) AS UCount');
		$this->db->from('odetails');
		if($UserId) {
			$this->db->where('odetails.UserId', $UserId);
		} else {
			$this->db->where('odetails.UserId', intval($this->session->userdata('id')));
		}
		$this->db->where_in('odetails.ServiceId', array(1, 2, 4));
		$this->db->group_by('odetails.OId');
		$query = $this->db->get();
		$result = $query->row_array();
		return intval($result['UCount']);
	}
	public function is_old_user($UserId = FALSE) {
		$this->load->model('user_m');
		if($UserId) {
			$user = $this->user_m->get($UserId);
		} else {
			$user = $this->user_m->get(intval($this->session->userdata('id')));
		}
		if(intval($user->isOldUser) == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function is_unique_coupon_code($couponCode) {
		$orig_user = $this->get_by(array(
			'CCode' => $couponCode
		));
		if (count($orig_user) >= 1) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	public function is_own_coupon_code($couponCode, $couponCodeId) {
		$orig_user = $this->get_by(array(
			'CCode' => $couponCode
		));
		if (count($orig_user) >= 1) {
			if($orig_user[0]->CouponId == $couponCodeId) {
				return TRUE;
			} else {
				return $this->is_unique_coupon_code($couponCode);
			}
		} else {
			return $this->is_unique_coupon_code($couponCode);
		}
	}
}