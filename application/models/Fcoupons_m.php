<?php
class FCoupons_m extends G6_Model {
	protected $_table_name = 'fcoupons';
	protected $_primary_key = 'FCouponId';
	protected $_order_by = 'FCouponId';
	public function __construct() {
		parent::__construct();
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
			if($orig_user[0]->FCouponId == $couponCodeId) {
				return TRUE;
			} else {
				return $this->is_unique_coupon_code($couponCode);
			}
		} else {
			return $this->is_unique_coupon_code($couponCode);
		}
	}
}