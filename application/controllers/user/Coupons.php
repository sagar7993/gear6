<?php
class Coupons extends G6_Usercontroller {
	public $json_data = array();
	public function __construct() {
		parent::__construct();
		$this->load->model('coupons_m');
		$this->output->set_content_type('application/json');
	}
	public function check_coupon() {
		if($_POST) {
			if($this->input->post('ccode') !== NULL && $this->input->post('ccode') != "") {
				$crow = $this->coupons_m->get_coupon_row($this->input->post('ccode'));
				if(isset($crow)) {
					$this->analyse_ccode($crow);
				} else {
					$this->json_data['emsg'] = 'Invalid offer coupon';
				}
			} elseif($this->input->post('fccode') !== NULL && $this->input->post('fccode') != "") {
				$fcrow = $this->coupons_m->get_fcoupon_row($this->input->post('fccode'));
				if(isset($fcrow) && !empty($fcrow)) {
					$this->analyse_fccode($fcrow);
				} else {
					$this->json_data['emsg'] = 'Invalid referral coupon / gift card';
				}
			}
			echo json_encode($this->json_data);
		}
	}
	public function remove_fcoupon() {
		$this->calculate_remove_prices('f');
		$this->session->unset_userdata('fcid');
		$this->session->unset_userdata('fdvalue');
		echo json_encode($this->json_data);
	}
	public function remove_coupon() {
		$this->calculate_remove_prices('c');
		$this->session->unset_userdata('cid');
		$this->session->unset_userdata('cdvalue');
		echo json_encode($this->json_data);
	}
	private function analyse_ccode($crow) {
		$crow = $this->check_service_id($crow);
		if($crow) {
			if($this->check_validity($crow)) {
				if($this->check_user_id($crow)) {
					if($this->check_max_uses($crow)) {
						if($this->check_user_limit($crow)) {
							if($this->check_order_count($crow)) {
								if($this->check_min_purchase($crow)) {
									$this->calculate_c_discount($crow);
								}
							}
						}
					}
				}
			}
		} else {
			$this->json_data['emsg'] = 'This coupon cannot be applied on this service type';
		}
	}
	private function analyse_fccode($fcrow) {
		if($this->check_fcoupon_validity($fcrow)) {
			if($this->check_user_id($fcrow)) {
				if($this->is_user_used_fcode($fcrow)) {
					$this->calculate_fc_discount($fcrow);
				}
			}
		}
	}
	private function calculate_fc_discount($fcrow) {
		$purchase_value = floatval($this->input->post('pprice'));
		$coupon_amount = floatval($fcrow['CAmount']);
		$this->json_data['fdvalue'] = $coupon_amount;
		$this->json_data['to_pay'] = ($purchase_value - $coupon_amount);
		$this->session->set_userdata('fcid', intval($fcrow['FCouponId']));
		$this->session->set_userdata('to_pay', $this->json_data['to_pay']);
		$this->session->set_userdata('fdvalue', $this->json_data['fdvalue']);
		$this->finalize_calculations('f');
	}
	private function calculate_remove_prices($type) {
		if($type == 'c') {
			$this->json_data['to_pay'] = floatval($this->session->userdata('to_pay')) + floatval($this->session->userdata('cdvalue'));
			if($this->session->userdata('fcid') != '' && $this->session->userdata('fcid') !== NULL) {
				$this->json_data['fdvalue'] = floatval($this->session->userdata('fdvalue'));
			}
		} elseif($type == 'f') {
			$this->json_data['to_pay'] = floatval($this->session->userdata('to_pay')) + floatval($this->session->userdata('fdvalue'));
			if($this->session->userdata('cid') != '' && $this->session->userdata('cid') !== NULL) {
				$this->json_data['cdvalue'] = floatval($this->session->userdata('cdvalue'));
			}
		}
		$this->session->set_userdata('to_pay', $this->json_data['to_pay']);
	}
	private function finalize_calculations($type) {
		$this->json_data['emsg'] = 0;
		if($type == 'c') {
			if($this->session->userdata('fcid') != "") {
				$this->json_data['fdvalue'] = floatval($this->session->userdata('fdvalue'));
			}
		} elseif($type == 'f') {
			if($this->session->userdata('cid') != "") {
				$this->json_data['cdvalue'] = floatval($this->session->userdata('cdvalue'));
			}
		}
	}
	private function check_fcoupon_validity($fcrow) {
		$today = strtotime("today");
		$to = strtotime($fcrow['ValidTill']);
		if($today > $to) {
			$this->json_data['emsg'] = 'This coupon was expired';
			return FALSE;
		}
		return TRUE;
	}
	private function is_user_used_fcode($fcrow) {
		$used_count = $this->coupons_m->get_user_fcoupon_usage(intval($fcrow['FCouponId']));
		if($used_count >= 1) {
			$this->json_data['emsg'] = 'You have already used this coupon';
			return FALSE;
		}
		return TRUE;
	}
	private function check_order_count($crow) {
		if($this->data['is_logged_in'] == 0) {
			$order_no = 1;
			$is_old_user = FALSE;
		} else {
			$order_no = $this->coupons_m->get_orders_count();
			$order_no += 1;
			$is_old_user = $this->coupons_m->is_old_user();
		}
		if(!$is_old_user && intval($crow['CouponId']) <= 3) {
			$this->json_data['emsg'] = 'These promotional coupons are expired now. Please check our other offers.';
			return FALSE;
		}
		if(intval($crow['CouponId']) <= 3 && $order_no != intval($crow['CouponId'])) {
			if($order_no < intval($crow['CouponId'])) {
				$this->json_data['emsg'] = 'You can only apply this on your ' . $crow['CouponId'] . ' paid order';
			} elseif($order_no > intval($crow['CouponId'])) {
				$this->json_data['emsg'] = 'You missed this. You can only use this on your ' . $crow['CouponId'] . ' paid order';
			}
			return FALSE;
		}
		return TRUE;
	}
	private function calculate_c_discount($crow) {
		$purchase_value = floatval($this->input->post('pprice'));
		$coupon_amount = floatval($crow['CAmount']);
		if($crow['CType'] == 'f') {
			$this->json_data['cdvalue'] = $coupon_amount;
			$this->json_data['to_pay'] = ($purchase_value - $coupon_amount);
		} elseif($crow['CType'] == 'p') {
			$coupon_amount = get_float_with_two_decimal_places(($purchase_value * $coupon_amount) / 100.0);
			if(isset($crow['MaxDiscount']) && !empty($crow['MaxDiscount']) && $coupon_amount > floatval($crow['MaxDiscount'])) {
				$this->json_data['cdvalue'] = floatval($crow['MaxDiscount']);
				$this->json_data['to_pay'] = $purchase_value - $this->json_data['cdvalue'];
			} else {
				$this->json_data['cdvalue'] = $coupon_amount;
				$this->json_data['to_pay'] = ($purchase_value - $coupon_amount);
			}
		}
		$this->session->set_userdata('cid', intval($crow['CouponId']));
		$this->session->set_userdata('cdvalue', $this->json_data['cdvalue']);
		$this->session->set_userdata('to_pay', $this->json_data['to_pay']);
		$this->finalize_calculations('c');
	}
	private function check_min_purchase($crow) {
		$purchase_value = floatval($this->input->post('pprice'));
		$min_limit = floatval($crow['MinPurchase']);
		if($min_limit > $purchase_value) {
			$this->json_data['emsg'] = 'Minimum purchase value of Rs. ' . $min_limit . ' is required for this coupon';
			return FALSE;
		}
		return TRUE;
	}
	private function check_user_limit($crow) {
		$user_used_count = $this->coupons_m->get_user_coupon_usage(intval($crow['CouponId']));
		if($user_used_count >= $crow['PerUserLimit']) {
			$this->json_data['emsg'] = 'You have already used this coupon and reached the usage limit';
			return FALSE;
		}
		return TRUE;
	}
	private function check_max_uses($crow) {
		$used_count = $this->coupons_m->get_total_coupon_usage(intval($crow['CouponId']));
		if($used_count >= $crow['MaxUses']) {
			$this->json_data['emsg'] = 'This coupon usage reached its limit';
			return FALSE;
		}
		return TRUE;
	}
	private function check_validity($crow) {
		$today = strtotime("today");
		$from = strtotime($crow['ValidFrom']);
		$to = strtotime($crow['ValidTill']);
		if($today < $from) {
			$this->json_data['emsg'] = 'This offer not yet started';
			return FALSE;
		} elseif($today > $to) {
			$this->json_data['emsg'] = 'This offer was expired';
			return FALSE;
		}
		return TRUE;
	}
	private function check_user_id($crow) {
		if($crow['UserId'] !== NULL && $crow['UserId'] != '') {
			if($this->data['is_logged_in'] == 0) {
				$this->json_data['emsg'] = 'You have to be logged in to use this coupon';
				return FALSE;
			} else {
				if(intval($this->session->userdata('id')) != intval($crow['UserId'])) {
					$this->json_data['emsg'] = 'This coupon is not for you';
					return FALSE;
				}
			}
		}
		return TRUE;
	}
	private function check_service_id($crow) {
		$check = TRUE;
		foreach($crow as $cr) {
			if($cr['ServiceId'] !== NULL && $cr['ServiceId'] != '') {
				if($this->input->cookie('servicetype') != '') {
					if(intval($cr['ServiceId']) == intval($this->input->cookie('servicetype'))) {
						return $cr;
					} else {
						$check = FALSE;
					}
				}
			}
		}
		if($check) {
			return $crow[0];
		} else {
			return $check;
		}
	}
}