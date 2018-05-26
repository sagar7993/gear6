<?php
class Result extends G6_Usercontroller {
	public function __construct() {
		parent::__construct();
		$this->data['order_placed_for_city'] = intval($this->input->cookie('CityId'));
	}
	public function showInterruptedOrder() {
		if ($this->input->cookie('oid') == '' || $this->input->cookie('oid') === NULL) {
			redirect('/user/account/corders');
		} else {
			$OId = $this->input->cookie('oid');
			$this->get_order_details($OId);
			if($this->input->cookie('is_new_order') == 1) {
				$this->send_gear6_email($this->data['uemail'], 'Your Order '. $OId . ' was successfully placed', 'osuccess', $this->data);
				if($this->data['serid'] == 3) {
					$this->send_sms_request_to_api($this->data['phone'], 'Your service request is successfully placed as Order ID : ' . $OId . ' for '. convert_to_camel_case($this->data['stype']) . ' on ' . convert_to_camel_case($this->data['bikemodel']) . '. Please wait for the Service Provider’s approval. Login to gear6.in to track your service progress.');
				} else {
					$this->send_sms_request_to_api($this->data['phone'], 'Your service request is successfully placed as Order ID : ' . $OId . ' with ' . convert_to_camel_case($this->data['scenter'][0]['ScName']) . ' for '. convert_to_camel_case($this->data['stype']) . ' to ' . convert_to_camel_case($this->data['bikemodel']) . ' on ' . $this->data['timeslot'] . '. Please wait for the Service Provider’s approval. Login to gear6.in to track your service progress.');
				}
				if($this->data['order_placed_for_city'] == 1) {
					$this->send_sms_request_to_api('9494845111', 'New order placed by User: ' . $this->data['uname'] . ', Phone: ' . $this->data['phone'] . '. Track ->> https://www.gear6.in/admin/orders/odetail/' . $OId);
				} elseif($this->data['order_placed_for_city'] == 3) {
					$this->send_sms_request_to_api('9000117719', 'New order placed by User: ' . $this->data['uname'] . ', Phone: ' . $this->data['phone'] . '. Track ->> https://www.gear6.in/admin/orders/odetail/' . $OId);
				}
				$this->send_sms_request_to_api('8888083841', 'New order ' . $OId . ' placed by User: ' . $this->data['uname'] . ', Phone ' . $this->data['phone'] . ', Service Type: ' . convert_to_camel_case($this->data['stype']) . ', Bike Model: ' . convert_to_camel_case($this->data['bikemodel']) . ', Reg No: ' . $this->data['bikenumber']);
				$this->send_vendor_smses();
			}
			delete_cookie('oid');
			delete_cookie('is_new_order');
			$this->load->view('user/success', $this->data);
		}
	}
	public function showRZPayStatus() {
		if($_POST && (bool) $this->input->cookie('oid')) {
			$this->load->model('opaymtdetail_m');
			$paymt_id = $this->input->post('paymt_txn_id');
			$OId = $this->input->post('oid');
			$amount_paid = intval($this->input->post('amount'));
			$get_paymt_status = $this->send_rzpay_api_request("https://api.razorpay.com/v1/payments/" . $paymt_id);
			$get_paymt_capture_status = $this->send_rzpay_api_request("https://api.razorpay.com/v1/payments/" . $paymt_id . "/capture", array('amount' => $amount_paid));
			$this->data['OId'] = $OId;
			$this->get_order_details($OId);
			$usr_id = intval($this->user_m->get_by(array('Phone' => $this->data['phone']), TRUE)->UserId);
			$paymt_row['UserId'] = $usr_id;
			$paymt_row['OId'] = $OId;
			$paymt_row['PaymtAmt'] = round($amount_paid / 100, 2);
			$paymt_row['PaymtId'] = 6;
			$paymt_row['PaymtResponse'] = serialize($get_paymt_capture_status);
			if($get_paymt_status['status'] == 'authorized') {
				$paymt_row['PaymtStatusId'] = 3;
				$check = TRUE;
				$this->db->where('admin_notification_flags.OId', $OId)->update('admin_notification_flags', array('new_payment' => 1));
				if($this->input->cookie('is_porder_trxn') == 1) {
					$and_reg_ids = $this->get_all_active_admin_devices();
					if(count($and_reg_ids) > 0) {
						$and_push_msg_data = array("message" => "New payment for OId: " . $OId, "tag" => "odetailwithjobcard", "oid" => $OId);
						$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
					}
					$and_reg_ids = $this->get_all_assigned_executive_devices($OId);
					$tag = $this->db->select('Tag')->from('jobcarddetails')->where('OId', $OId)->get()->result_array()[0]['Tag'];
					if(count($and_reg_ids) > 0) {
						$and_push_msg_data = array("message" => "Customer has made the online payment for order " . $OId, "tag" => $tag, "oid" => $OId);
						$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
					}
					$phones = $this->get_all_assigned_executive_numbers($OId);
					if(count($phones) > 0) {
						foreach ($phones as $phone) {
							$this->send_sms_request_to_api($phone, 'Customer has made the online payment for order ' . $OId . '. Track ->> https://www.gear6.in/admin/orders/odetail/' . $OId);
						}
					}
				}
				$billed_amount = $this->opaymtdetail_m->get_total_billed_amount($OId);
				if($billed_amount >= 3000) {
					$gateway = array(); $gateway['Price'] = round(($billed_amount * 0.02), 2); $gateway['OId'] = $paymt_row['OId'];
					$gateway['PriceDescription'] = "Payment Gateway Charges";
					$test = $this->db->select('*')->from('OPrice')->where('OId', $OId)->where('PriceDescription', "Payment Gateway Charges")->get()->row();
					ìf($test) {
						$this->db->where('OPID', $test->OPID);
						$this->db->update('OPrice', $gateway);
					} else {
						$this->db->insert('OPrice', $gateway);
					}
				}
			} else {
				$check = FALSE;
				$paymt_row['PaymtStatusId'] = 1;
			}
			$this->data['txnid'] = $this->opaymtdetail_m->create_custom_trxn($paymt_row);
			if($this->input->cookie('is_new_order') == 1) {
				$this->send_gear6_email($this->data['uemail'], 'Your Order '. $OId . ' was successfully placed', 'osuccess', $this->data);
				if($this->data['serid'] == 3) {
					$this->send_sms_request_to_api($this->data['phone'], 'Your service request is successfully placed as Order ID : ' . $OId . ' for '. convert_to_camel_case($this->data['stype']) . ' on ' . convert_to_camel_case($this->data['bikemodel']) . '. Please wait for the Service Provider’s approval. Login to gear6.in to track your service progress.');
				} else {
					$this->send_sms_request_to_api($this->data['phone'], 'Your service request is successfully placed as Order ID : ' . $OId . ' with ' . convert_to_camel_case($this->data['scenter'][0]['ScName']) . ' for '. convert_to_camel_case($this->data['stype']) . ' to ' . convert_to_camel_case($this->data['bikemodel']) . ' on ' . $this->data['timeslot'] . '. Please wait for the Service Provider’s approval. Login to gear6.in to track your service progress.');
				}
				if($this->data['order_placed_for_city'] == 1) {
					$this->send_sms_request_to_api('9494845111', 'New order placed by User: ' . $this->data['uname'] . ', Phone: ' . $this->data['phone'] . '. Track ->> https://www.gear6.in/admin/orders/odetail/' . $OId);
				} elseif($this->data['order_placed_for_city'] == 3) {
					$this->send_sms_request_to_api('9000117719', 'New order placed by User: ' . $this->data['uname'] . ', Phone: ' . $this->data['phone'] . '. Track ->> https://www.gear6.in/admin/orders/odetail/' . $OId);
				}
				$this->send_sms_request_to_api('8888083841', 'New order ' . $OId . ' placed by User: ' . $this->data['uname'] . ', Phone ' . $this->data['phone'] . ', Service Type: ' . convert_to_camel_case($this->data['stype']) . ', Bike Model: ' . convert_to_camel_case($this->data['bikemodel']) . ', Reg No: ' . $this->data['bikenumber']);
				$this->send_vendor_smses();
			}
			$sms_suc_msg = "Your payment for " . $OId . " is successful with transaction ID " . $this->data['txnid'] . ".";
			$sms_fail_msg = "Your payment for " . $OId . " with transaction ID " . $this->data['txnid'] . " is not successful.";
			if($check) {
				$this->send_sms_request_to_api($this->data['phone'], $sms_suc_msg);
				$this->data['status'] = 'Success';
				$this->data['smsg'] = 'Congratulations, Your payment with Transaction Id: ' . $this->data['txnid'] . ' was <strong>successful</strong>. Your Service Order booking has been Successfully Completed';
			} else {
				$this->send_sms_request_to_api($this->data['phone'], $sms_fail_msg);
				$this->data['status'] = 'Failure';
				$this->data['error'] = 'Your payment with Transaction Id: ' . $this->data['txnid'] . ' <strong>failed</strong> ' . 'with message: "' . $get_paymt_capture_status['error_description'] . '". If this is unexpected, please contact our customer support at support@gear6.in';
			}
			delete_cookie('oid');
			delete_cookie('is_new_order');
			if($this->input->cookie('is_porder_trxn') == 1) {
				delete_cookie('is_porder_trxn');
				$this->get_feedback_questions("data");
				$this->load->view('user/psuccess', $this->data);
			} else {
				$this->load->view('user/success', $this->data);
			}
		} else {
			redirect('/user/account/corders');
		}
	}
	public function showStatus($OId) {
		if ($OId == '' || $this->input->cookie('oid') != $OId) {
			redirect('/user/account/corders');
		} else {
			$this->data['OId'] = $OId;
			$check = $this->checkTransaction();
			$this->get_order_details($OId);
			if($this->input->cookie('is_new_order') == 1) {
				$this->send_gear6_email($this->data['uemail'], 'Your Order '. $OId . ' was successfully placed', 'osuccess', $this->data);
				if($this->data['serid'] == 3) {
					$this->send_sms_request_to_api($this->data['phone'], 'Your service request is successfully placed as Order ID : ' . $OId . ' for '. convert_to_camel_case($this->data['stype']) . ' on ' . convert_to_camel_case($this->data['bikemodel']) . '. Please wait for the Service Provider’s approval. Login to gear6.in to track your service progress.');
				} else {
					$this->send_sms_request_to_api($this->data['phone'], 'Your service request is successfully placed as Order ID : ' . $OId . ' with ' . convert_to_camel_case($this->data['scenter'][0]['ScName']) . ' for '. convert_to_camel_case($this->data['stype']) . ' to ' . convert_to_camel_case($this->data['bikemodel']) . ' on ' . $this->data['timeslot'] . '. Please wait for the Service Provider’s approval. Login to gear6.in to track your service progress.');
				}
				if($this->data['order_placed_for_city'] == 1) {
					$this->send_sms_request_to_api('9494845111', 'New order placed by User: ' . $this->data['uname'] . ', Phone: ' . $this->data['phone'] . '. Track ->> https://www.gear6.in/admin/orders/odetail/' . $OId);
				} elseif($this->data['order_placed_for_city'] == 3) {
					$this->send_sms_request_to_api('9000117719', 'New order placed by User: ' . $this->data['uname'] . ', Phone: ' . $this->data['phone'] . '. Track ->> https://www.gear6.in/admin/orders/odetail/' . $OId);
				}
				$this->send_sms_request_to_api('8888083841', 'New order ' . $OId . ' placed by User: ' . $this->data['uname'] . ', Phone ' . $this->data['phone'] . ', Service Type: ' . convert_to_camel_case($this->data['stype']) . ', Bike Model: ' . convert_to_camel_case($this->data['bikemodel']) . ', Reg No: ' . $this->data['bikenumber']);
				$this->send_vendor_smses();
			}
			if(isset($this->data['txnid'])) {
				$sms_suc_msg = "Your payment for " . $OId . " is successful with transaction ID " . $this->data['txnid'] . ".";
				$sms_fail_msg = "Your payment for " . $OId . " with transaction ID " . $this->data['txnid'] . " is not successful.";
				if($check) {
					$this->send_sms_request_to_api($this->data['phone'], $sms_suc_msg);
				} else {
					$this->send_sms_request_to_api($this->data['phone'], $sms_fail_msg);
				}
			}
			delete_cookie('oid');
			delete_cookie('is_new_order');
			if($this->input->cookie('is_porder_trxn') == 1) {
				delete_cookie('is_porder_trxn');
				$this->get_feedback_questions("data");
				$this->load->view('user/psuccess', $this->data);
			} else {
				$this->load->view('user/success', $this->data);
			}
		}
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
	private function send_vendor_smses() {
		$this->load->model('servicecenter_m');
		$sc_phone = $this->servicecenter_m->get_sc_phone_by_oid($this->data['OId']);
		if(isset($sc_phone)) {
			if($this->data['serid'] == 3) {
				foreach($sc_phone as $phone) {
					$this->send_sms_request_to_api($phone['Phone'], 'New query received as order ' . $this->data['OId'] . ' realted to ' . $this->data['scenter'][0]['ServiceDesc1'] . '. Login to your panel for more details.');
				}
			} else {
				$this->send_sms_request_to_api($sc_phone[0]['Phone'], 'New ' . $this->data['stype'] . ' request received as order ' . $this->data['OId'] . ' for ' . convert_to_camel_case($this->data['bikemodel']) . ' Reg No.: ' . $this->data['bikenumber'] . ' on ' . $this->data['timeslot'] . '. Login to your panel for more details.');
			}
		}
	}
	private function checkTransaction() {
		$response = $_POST;
		$this->load->model('opaymtdetail_m');
		if(isset($response['txnid']) && !$this->is_transaction_valid($response)) {
			$pdata['PaymtResponse'] = serialize($response);
			$pdata['PaymtStatusId'] = 4;
			$this->db->where('TId', $response['txnid']);
			$this->db->update('opaymtdetail', $pdata);
			redirect('/user');
		}
		if(isset($response['txnid']) && $response['status'] == 'success') {
			$this->data['txnid'] = $response['txnid'];
			$this->data['status'] = convert_to_camel_case($response['status']);
			$this->data['smsg'] = 'Congratulations, Your payment with Transaction Id: ' . $response['txnid'] . ' was <strong>successful</strong>. Your Service Order booking has been Successfully Completed';
			$pdata['PaymtResponse'] = serialize($response);
			$pdata['PaymtStatusId'] = 3;
			$pdata['PaymtId'] = 7;
			$this->db->where('TId', $response['txnid']);
			$this->db->update('opaymtdetail', $pdata);
			$oid = $response['productinfo'];
			$this->db->where('admin_notification_flags.OId', $oid)->update('admin_notification_flags', array('new_payment' => 1));
			if($this->input->cookie('is_porder_trxn') == 1) {
				$and_reg_ids = $this->get_all_active_admin_devices();
				if(count($and_reg_ids) > 0) {
					$and_push_msg_data = array("message" => "New payment for OId: " . $oid, "tag" => "odetailwithjobcard", "oid" => $oid);
					$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
				}
				$and_reg_ids = $this->get_all_assigned_executive_devices($oid);
				$tag = $this->db->select('Tag')->from('jobcarddetails')->where('OId', $oid)->get()->result_array()[0]['Tag'];
				if(count($and_reg_ids) > 0) {
					$and_push_msg_data = array("message" => "Customer has made the online payment for order " . $oid, "tag" => $tag, "oid" => $oid);
					$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
				}
				$phones = $this->get_all_assigned_executive_numbers($oid);
				if(count($phones) > 0) {
					foreach ($phones as $phone) {
						$this->send_sms_request_to_api($phone, 'Customer has made the online payment for order ' . $oid . '. Track ->> https://www.gear6.in/admin/orders/odetail/' . $oid);
					}
				}
			}
			return TRUE;
		} elseif(isset($response['txnid']) && ($response['status'] == 'failure' || $response['status'] == 'pending')) {
			$this->data['txnid'] = $response['txnid'];
			$this->data['status'] = convert_to_camel_case($response['status']);
			$this->data['error_Message'] = $response['error_Message'];
			$this->data['error'] = 'Your payment via ' . $response['mode'] . ' with Transaction Id: ' . $response['txnid'] . ' <strong>failed</strong> ' . 'with message: "' . $response['error_Message'] . '" If the amount is debited from your account, It will be refunded back with in 4 - 7 working days. However, your order was placed and you can pay the amount anytime from \'My Orders\' section.';
			$pdata['PaymtResponse'] = serialize($response);
			$pdata['PaymtStatusId'] = 2;
			$this->db->where('TId', $response['txnid']);
			$this->db->update('opaymtdetail', $pdata);
			return FALSE;
		}
	}
	private function get_order_details($OId) {
		$this->load->model('odetails_m');
		$this->load->model('amenity_m');
		$this->data['OId'] = $OId;
		$service_details = $this->odetails_m->get_stype_by_oid($OId);
		$this->data['stype'] = $service_details['ServiceName'];
		$this->data['serid'] = intval($service_details['ServiceId']);
		$sc_details = $this->odetails_m->get_scenter_by_oid($OId);
		$this->data['scenter'] = $sc_details;
		$bike_model_details = $this->odetails_m->get_bm_by_oid($OId);
		$this->data['bikenumber'] = $bike_model_details['BikeNumber'];
		$this->data['bikemodel'] = $bike_model_details['BikeCompanyName'] . ' ' . $bike_model_details['BikeModelName'];
		$this->data['timeslot'] = $this->odetails_m->get_timeslot_by_oid($OId);
		$this->data['paymode'] = $this->odetails_m->get_paymode_by_oid($OId);
		$user_details = $this->odetails_m->get_user_address($OId);
		$this->data['uaddress'] = $user_details['address'];
		$this->data['uname'] = $user_details['name'];
		$this->data['uemail'] = $user_details['email'];
		$this->data['phone'] = $user_details['Phone'];
		$this->remove_converted_phone($this->data['phone']);
		if ($this->data['is_logged_in'] == 0) {
			$this->data['pwd'] = $user_details['pwd'];
		}
		if ($this->data['serid'] != 3) {
			$this->data['scaddress'] = $this->odetails_m->get_sc_address($sc_details[0]['ScId']);
			$this->data['estprices'] = $this->amenity_m->get_est_prices_by_oid($OId);
		}
	}
	private function remove_converted_phone($ph) {
		$this->db->where('Phone', $ph)->delete('dropped_orders');
	}
}