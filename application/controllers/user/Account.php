<?php
class Account extends G6_Usercontroller {
	public function __construct() {
		parent::__construct();
		if ($this->user_m->loggedin() == FALSE) {
			redirect('/');
		}
	}
	public function bprofile() {
		$this->data['bprofile'] = 1;
		$this->get_user_addresses();
		$this->get_user_bikes();
		$this->load->view('user/bprofile', $this->data);
	}
	public function initiatePorderTrxn() {
		if($_POST) {
			$paymtgtw = intval($this->input->post('paymtgtw'));
			$OId = $this->input->post('oid');
			$this->set_query_cookie('oid', $OId);
			$this->set_query_cookie('is_porder_trxn', 1);
			if($paymtgtw == 1) {
				echo json_encode(array('name' => convert_to_camel_case($this->session->userdata('name')), 'email' => $this->session->userdata('email'), 'phone' => $this->session->userdata('phone')));
			} elseif($paymtgtw == 2) {
				$usr_id = $this->session->userdata('id');
				$price = floatval($this->input->post('to_be_paid'));
				if($price > 0.01) {
					echo json_encode(array('html' => $this->generate_payment_form($usr_id, $OId, $price)));
				}
			}
		}
	}
	public function rescheduleOrder() {
		if($_POST) {
			$this->load->model('odetails_m');
			$odetails = $this->odetails_m->get_odetails_for_reschedule($this->input->post('rs_order_id'));
			if($odetails) {
				$this->check_if_resc_available($odetails['ODate']);
			} else {
				$this->data['is_canres_enabled'] = FALSE;
			}
			if($this->data['is_canres_enabled']) {
				$this->set_query_cookie('area', $odetails['LocationName']);
				$this->set_query_cookie('servicetype', $odetails['ServiceId']);
				$this->set_query_cookie('res_service_id', $odetails['ServiceId']);
				$this->set_query_cookie('date_', $this->input->post('rs_date'));
				$this->set_query_cookie('date', date('Y-m-d', strtotime($this->input->post('rs_date'))));
				$this->set_query_cookie('company', $odetails['BikeCompanyId']);
				$this->set_query_cookie('model', $odetails['BikeModelId']);
				$this->set_query_cookie('res_order', $this->input->post('rs_order_id'));
				$crdata['UserRemarks'] = $this->input->post('rs_reason');
				$this->db->where('OId', $this->input->post('rs_order_id'));
				$this->db->update('odetails', $crdata);
				redirect(site_url('user/book'));
			} else {
				redirect(site_url('user/account/corders'));
			}
		}
	}
	public function corders() {
		$this->data['myorders'] = 1;
		if ($this->city_m->iscityset()) {
			$this->data['adv_time'] = intval($this->city_m->get($this->input->cookie('CityId'))->AdvTime) / 24;
		}
		if($_POST) {
			$OId = $this->input->post('oid');
			$this->set_query_cookie('active_corder', $OId);
			redirect(site_url('user/account/corders'));
		} else {
			if($this->input->cookie('active_corder') != "") {
				$OId = $this->input->cookie('active_corder');
			} else {
				$this->load->model('odetails_m');
				$OId = $this->odetails_m->get_latest_user_oid();
			}
		}
		$this->release_notify($OId);
		$this->get_current_user_order($OId);
		$this->get_active_orders($OId);
		$this->get_feedback_questions("data");
		$this->load->model('executive_m');
		$this->data['ex_rtime_updates'] = $this->executive_m->get_ex_fup_rtime_supdates_web($OId, 1);
		$this->load->view('user/corders', $this->data);
	}
	private function get_jc_form_data($OId) {
		$this->db->select('*');
		$this->db->from('jobcarddetails');
		$this->db->where('OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		if(!$result) {
			return NULL;
		} else {
			$this->data['cr_bikecolor'] = $result['BikeColor'];
			$this->data['cr_kms'] = $result['BikeKms'];
			$this->data['cs_fuelrange'] = $result['FuelRange'];
			$this->data['us_comments'] = $result['UserComments'];
			$this->data['JcKms'] = $result['JcKms'];
			$this->data['JcNum'] = $result['JcNum'];
		}
	}
	private function getJobCard($OId) {
		$this->load->model('odetails_m');
		$this->load->model('aservice_m');
		$this->load->model('amenity_m');
		$this->load->model('executive_m');
		$this->data['OId'] = $OId;
		$sc_details = $this->odetails_m->get_scenter_by_oid($OId);		
		$this->data['scenter'] = $sc_details;
		$this->data['chosen_amenities'] = $this->amenity_m->get_chosen_amenities($OId);
		$this->data['chosen_aservices'] = $this->aservice_m->get_chosen_aservices($OId);
		$this->data['jccats'] = $this->executive_m->get_app_jcard_cats();
		$this->data['jcselects'] = $this->executive_m->get_app_jcard_selects($OId);
	}
	public function porders() {
		$this->data['ohistory'] = 1;
		$this->get_all_user_orders();
		$this->get_feedback_questions("data");
		$this->load->view('user/porders', $this->data);
	}
	public function update_phone_otp() {
		if($_POST) {
			$this->load->model('otp_m');
			$data['err'] = FALSE;
			if($this->user_m->is_unique_ph($this->input->post('phNum'))) {
				$otp = $this->otp_m->is_otp_inserted($this->input->post('phNum'));
				if(!$otp) {
					$otp = $this->otp_m->insert_otp($this->input->post('phNum'));
				}
				$this->send_sms_request_to_api($this->input->post('phNum'), "Your OTP is " . $otp . " for your mobile confirmation at gear6.in");
			} else {
				$data['err'] = 'Sorry, this phone is already registered';
			}
			echo json_encode($data);
		}
	}
	public function change_account_Phone() {
		if($_POST) {
			$this->load->model('user_m');
			$this->load->model('otp_m');
			$otpcheck = $this->otp_m->check_otp($this->input->post('chacphotp'), $this->input->post('chacphone'));
			if($otpcheck == 1) {
				$this->insert_acupdate_history();
				$this->user_m->update_phone($this->input->post('chacphone'));
				if($this->input->cookie('from_booking_redirect') == 1) {
					delete_cookie('from_booking_redirect');
					redirect('/user/book');
				} else {
					redirect('/user/account/uprofile');
				}
			} else {
				redirect('/user/account/uprofile/2');
			}
		}
	}
	public function uprofile($errors = NULL) {
		if(!empty($errors) && $errors == 1) {
			$this->data['pwd_errors'] = "You have entered invalid current password.";
		} elseif(!empty($errors) && $errors == 2) {
			$this->data['pwd_errors'] = "You have entered invalid OTP.";
		} elseif(!empty($errors) && $errors == 3) {
			$this->set_query_cookie('from_booking_redirect', 1);
		}
		$this->data['uprofile'] = 1;
		$this->data['cities'] = $this->city_m->get_by(array('isEnabled' => 1));
		$this->get_user_addresses();
		$this->data['referral_code'] = $this->user_m->get_referral_code();
		$this->load->view('user/uprofile', $this->data);
	}
	public function approvePrice() {
		if($_POST) {
			$data = array('isAmtCfmd' => $this->input->post('status'), 'isNotified' => 3);
			$this->db->where('OId', $this->input->post('oid'));
			$this->db->update('oservicedetail', $data);
			$this->load->model('servicecenter_m');
			$sc_phone = $this->servicecenter_m->get_sc_phone_by_oid($this->input->post('oid'));
			if(isset($sc_phone)) {
				$this->send_sms_request_to_api($sc_phone[0]['Phone'], 'Additional price details for the order ' . $this->input->post('oid') . ' has been approved by the user.');
			}
			redirect(site_url('user/account/corders'));
		}
	}
	public function getCityLocations() {
		if($this->input->post('city') != "") {
			$this->load->model('location_m');
			echo json_encode($this->location_m->locations_for_sc($this->input->post('city')));
		}
	}
	public function updateDefaultAddress() {
		if($_POST) {
			$data = array('isDefault' => 0);
			$this->db->where('UserId', intval($this->session->userdata('id')));
			$this->db->where('isDefault', 1);
			$this->db->update('useraddr', $data);
			if($this->input->post('addr') != "" || intval($this->input->post('addr')) != 0) {
				$user_addr_id = intval($this->input->post('addr'));
				$data = array('isDefault' => 1);
				$this->db->where('UserAddrId', $user_addr_id);
				$this->db->update('useraddr', $data);
			} else {
				$user_addr_id = $this->user_m->updt_useraddr(intval($this->session->userdata('id')), 1);
			}
			if($this->input->post('full_name') != convert_to_camel_case($this->session->userdata('name'))) {
				$this->insert_acupdate_history();
				$data = array('UserName' => $this->input->post('full_name'));
				$this->db->where('UserId', intval($this->session->userdata('id')));
				$this->db->update('user', $data);
				$this->session->set_userdata('name', $this->input->post('full_name'));
			}
			redirect(site_url('user/account/uprofile'));
		}
	}
	public function updateEmailPwd() {
		if(isset($_POST['email']) && $this->input->post('email') != "" && $this->input->post('email') != $this->session->userdata('email')) {
			$this->insert_acupdate_history();
			$data = array('Email' => $this->input->post('email'));
			$this->db->where('UserId', intval($this->session->userdata('id')));
			$this->db->update('user', $data);
			$this->session->set_userdata('email', $this->input->post('email'));
		}
		if(isset($_POST['pwd']) && $this->input->post('pwd') != "" && $this->user_m->reset_password($this->input->post('pswd1'), $this->input->post('pswd2'), NULL, TRUE)) {
			redirect(site_url('user/account/uprofile'));
		} elseif(isset($_POST['pwd']) && $this->input->post('pwd') != "") {
			redirect(site_url('user/account/uprofile/1'));
		} else {
			redirect(site_url('user/account/uprofile'));
		}
	}
	public function updateUserFeedback() {
		$OId = $this->input->post('OId'); $remarks = $this->input->post('remarks');
		$feedbackArray = explode(", ", $this->input->post('feedbackArray'));
		$questionArray = explode(", ", $this->input->post('questionArray'));
		$old_rating_admin = floatval($this->get_user_feedback_rating_by_oid($OId));
		$old_rating_user = floatval($this->get_user_feedback_rating_by_oid_question($OId));
		$new_rating_user = floatval($feedbackArray[2]);
		$this->db->where('OId', $OId); $this->db->delete('user_feedback');
		$odetails['user_feedback_remarks'] = $remarks; $count = 0;
		foreach ($feedbackArray as $feedback) {
			if(intval($questionArray[$count]) != 0 && floatval($feedback) > 0.05) {
				$user_feedback[$count]['OId'] = $OId;
				$user_feedback[$count]['ExecFbQId'] = intval($questionArray[$count]);
				$user_feedback[$count]['ExecFbAnswer'] = floatval($feedback);
				$count++;
			}
		}
		if($count > 0) {
			$insert_batch = $this->db->insert_batch('user_feedback', $user_feedback);
			if($remarks != NULL && $remarks != "") {
				$this->db->where('OId', $OId); $this->db->update('odetails', $odetails);
			}
			$this->load->model('servicecenter_m');
			$sc = $this->servicecenter_m->get_sc_by_oid($OId);
			$sql = 'INSERT INTO admin_notification_flags (OId, ScId, ODate, new_feedback, new_feedback_dismissed) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE ScId = VALUES(ScId), ODate = VALUES(ODate), new_feedback = VALUES(new_feedback), new_feedback_dismissed = VALUES(new_feedback_dismissed)';
			$query = $this->db->query($sql, array($OId, $sc['ScId'], $sc['ODate'], 1, 0));
			if($old_rating_admin == 0) {
				if($old_rating_user == 0) { $old = 0; } else { $old = $old_rating_user;	}
			} else {
				if($old_rating_user == 0) {	$old = $old_rating_admin; } else { $old = ($old_rating_admin + $old_rating_user) / 2; }
			}
			$this->load->model('servicecenter_m'); $this->load->model('odetails_m');
			$sc_id = $this->odetails_m->get_scid_by_oid($OId);
			if($sc_id != NULL) {
				$rating = $this->servicecenter_m->get_name_rating($sc_id);
				$ratersCount = intval($rating['RatersCount']); $rating = floatval($rating['Rating']);
				$totalRating = $ratersCount * $rating;
				if($old != 0) { $ratersCount -= 1; $totalRating -= $old; }
				if($old_rating_admin != 0) {
					$totalRating += round((($old_rating_admin + $new_rating_user) / 2), 2);
				} else {
					$totalRating += $new_rating_user;
				}
				$ratersCount += 1; $new_rating = round(($totalRating / $ratersCount), 2);
				$this->db->where('ScId', $sc_id); $this->db->update('servicecenter', array("Rating" => $new_rating, "RatersCount" => $ratersCount));
			}
			if($insert_batch == TRUE && $query == TRUE) {
				echo json_encode(array("status" => "1"));
			} else {
				echo json_encode(array("status" => "0"));
			}
		} else {
			echo json_encode(array("status" => "0"));
		}
	}
	private function get_user_feedback_rating_by_oid($OId) {
		$rating = $this->db->select('user_feedback_rating')->from('odetails')->where('OId', $OId)->get()->result_array();
		if(count($rating) > 0) { return intval($rating[0]['user_feedback_rating']); } else { return 0; }
	}
	private function get_user_feedback_rating_by_oid_question($OId) {
		$rating = $this->db->select('ExecFbAnswer')->from('user_feedback')->where('OId', $OId)->where('ExecFbQId', 3)->get()->result_array();
		if(count($rating) > 0) { return intval($rating[0]['ExecFbAnswer']); } else { return 0; }
	}
	public function get_order_ratings() {
		$OId = $this->input->post('OId');
		$this->db->select('*');
		$this->db->from('user_feedback');
		$this->db->join('execfbqs', 'execfbqs.ExecFbQId = user_feedback.ExecFbQId');
		$this->db->where('execfbqs.isEnabled', 1);
		$this->db->where('OId', $OId);
		$this->db->order_by('execfbqs.ExecFbQId', 'asc');
		$query = $this->db->get();
		$ratings = $query->result_array();
		if(count($ratings) == 0) { $ratings = array(); }
		$this->db->select('user_feedback_remarks');
		$this->db->from('odetails');
		$this->db->where('OId', $OId);
		$query = $this->db->get();
		$remarks = $query->result_array();
		if($remarks == NULL) { $remarks = ""; } else { $remarks = $remarks[0]['user_feedback_remarks']; }
		$return["remarks"] = $remarks;
		$return["ratings"] = $ratings;
		echo json_encode($return);
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
	public function updateFeedback() {
		if($_POST) {
			$this->load->model('ratingcategory_m');
			$this->load->model('servicecenter_m');
			$this->load->model('odetails_m');
			$rcats = $this->ratingcategory_m->get();
			$fb_order_id = $this->input->post('fb_order_id');
			$fb_scenter_id = intval($this->input->post('fb_scenter_id'));
			$fb_desc = $this->input->post('fb_desc');
			$rating_array = array($this->input->post('serviceRating'), $this->input->post('offersRating'), $this->input->post('amenitiesRating'), $this->input->post('priceRating'), $this->input->post('custcareRating'));
			$total_sc_rating = $this->servicecenter_m->get($fb_scenter_id);
			$catwise_ratings = $this->ratingcategory_m->get_catwise_ratings($fb_scenter_id);
			$total_present_rating = 0;
			$count = 0;
			foreach ($rcats as $rcat) {
				$fdata[$count]['OId'] = $fb_order_id;
				$fdata[$count]['ScId'] = $fb_scenter_id;
				$fdata[$count]['RcId'] = intval($rcat->RcId);
				$fdata[$count]['RatingValue'] = intval($rating_array[$count]);
				$total_present_rating += floatval($rcat->Weight) * intval($rating_array[$count]);
				$count += 1;
			}
			$tfdata['RatersCount'] = intval($total_sc_rating->RatersCount) + 1;
			$tfdata['Rating'] = ((intval($total_sc_rating->RatersCount) * floatval($total_sc_rating->Rating)) + $total_present_rating) / ($tfdata['RatersCount']);
			$count = 0;
			if($catwise_ratings !== NULL) {
				foreach ($catwise_ratings as $catwise_rating) {
					if(intval($catwise_rating['RcId']) == intval($rcats[$count]->RcId)) {
						$catwise_ratings[$count]['NetCatRating'] = ((intval($total_sc_rating->RatersCount) * floatval($catwise_rating['NetCatRating'])) + $rating_array[$count]) / ($tfdata['RatersCount']);
					}
					$count += 1;
				}
			} else {
				foreach($rcats as $rcat) {
					$ncfdata[$count]['RcId'] = intval($rcat->RcId);
					$ncfdata[$count]['ScId'] = intval($fb_scenter_id);
					$ncfdata[$count]['NetCatRating'] = intval($rating_array[$count]);
					$count += 1;
				}
			}
			if($fb_desc !== NULL && !empty($fb_desc) && isset($fb_desc)) {
				$fbddata['OId'] = $fb_order_id;
				$fbddata['ScId'] = $fb_scenter_id;
				$fbddata['Feedback'] = $fb_desc;
				$this->db->insert('feedbacksc', $fbddata);
				$sql = 'INSERT INTO admin_notification_flags (OId, ScId, ODate, new_feedback) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE ScId = VALUES(ScId), ODate = VALUES(ODate), new_feedback = VALUES(new_feedback)';
				$query = $this->db->query($sql, array($fb_order_id, $fb_scenter_id, $this->odetails_m->get_odate_by_oid($fb_order_id), 1));
			}
			if(count($ncfdata) > 0 && $catwise_ratings === NULL) {
				$this->db->insert_batch('rating', $ncfdata);
			} else {
				$this->db->update_batch('rating', $catwise_ratings, 'RtId');
			}
			$this->servicecenter_m->save($tfdata, $fb_scenter_id);
			$this->db->insert_batch('ratingsplit', $fdata);
			$this->db->where('OId', $fb_order_id);
			$this->db->where('ScId', $fb_scenter_id);
			$this->db->update('oservicedetail', array('isFbNotified' => 1));
			redirect(site_url('user/account/corders'));
		}
	}
	private function release_notify($oid) {
		$data = array('isNotified' => 1);
		$this->db->where('OId', $oid);
		$this->db->where('(isNotified = "2" OR isNotified = "4")');
		$this->db->update('oservicedetail', $data);
	}
	private function insert_acupdate_history() {
		$uphdata['UserId'] = intval($this->session->userdata('id'));
		$uphdata['Name'] = $this->session->userdata('name');
		$uphdata['Phone'] = $this->session->userdata('phone');
		$uphdata['Email'] = $this->session->userdata('email');
		$uphdata['UserIp'] = $this->input->ip_address();
		$this->load->library('user_agent', NULL, 'agent');
		if ($this->agent->is_mobile()) {
			$uphdata['UserDevice'] = 'mob';
		} else {
			$uphdata['UserDevice'] = 'pc';
		}
		$this->db->insert('uphistory', $uphdata);
	}
	private function get_active_orders($active_oid) {
		$this->load->model('odetails_m');
		$oids = $this->odetails_m->get_active_oids_user($active_oid);
		$count = 0;
		if (count($oids) > 0) {
			foreach($oids as $oid) {
				$this->data['aorders']['OIds'][$count] = $oid['OId'];
				$service_details = $this->odetails_m->get_stype_by_oid($oid['OId']);
				$this->data['aorders']['stypes'][$count] = $service_details['ServiceName'];
				$this->data['aorders']['serid'][$count] = intval($service_details['ServiceId']);
				$sc_details = $this->odetails_m->get_scenter_by_oid($oid['OId']);
				$this->data['aorders']['scenters'][$count] = $sc_details;
				$bike_model_details = $this->odetails_m->get_bm_by_oid($oid['OId']);
				$this->data['aorders']['bikemodels'][$count] = $bike_model_details['BikeCompanyName'] . ' ' . $bike_model_details['BikeModelName'];
				$this->data['aorders']['timeslots'][$count] = $this->odetails_m->get_timeslot_by_oid($oid['OId']);
				$count += 1;
			}
		}
	}
	private function get_user_addresses() {
		$this->data['user_addresses'] = $this->user_m->get_user_addresses();
	}
	private function get_user_bikes() {
		$reg_nums = $this->_group_by($this->user_m->get_user_bikes(), 'BikeNumber');
		if(isset($reg_nums) && count($reg_nums) > 0) {
			foreach ($reg_nums as $key => $reg_num) {
				foreach ($reg_num as $order) {
					$bikes[$order['BikeCompanyName'] . ' ' . $order['BikeModelName'] . ' ' . $key][$order['ServiceName']][] = $order;
				}
			}
		}
		$this->data['reg_nums'] = $bikes;
	}
	private function _group_by($array, $key) {
	    $return = array();
	    foreach($array as $val) {
	        $return[$val[$key]][] = $val;
	    }
	    return $return;
	}
	private function get_current_user_order($OId) {
		$this->load->model('odetails_m');
		$this->load->model('status_m');
		$this->load->model('statushistory_m');
		$this->data['OId'] = $OId;
		$this->data['omedia'] = $this->odetails_m->get_order_media($OId);
		$service_details = $this->odetails_m->get_stype_by_oid($OId);
		$this->data['stype'] = $service_details['ServiceName'];
		$this->data['serid'] = intval($service_details['ServiceId']);
		$this->data['mr_remarks'] = $service_details['MRRemarks'];
		$this->data['statuses'] = $this->status_m->get_statuses_for_service($service_details['ServiceId']);
		$sc_details = $this->odetails_m->get_scenter_by_oid($OId);
		$this->data['scenter'] = $sc_details;
		$bike_model_details = $this->odetails_m->get_bm_by_oid($OId);
		$this->data['bikenumber'] = $bike_model_details['BikeNumber'];
		$this->data['bikemodel'] = $bike_model_details['BikeCompanyName'] . ' ' . $bike_model_details['BikeModelName'];
		$this->data['timeslot'] = $this->odetails_m->get_timeslot_by_oid($OId);
		$this->data['paymode'] = $this->odetails_m->get_paymode_by_oid($OId);
		$user_details = $this->odetails_m->get_user_address($OId);
		$this->data['uaddress'] = $user_details['address'];
		$this->data['user_addresses'] = $this->user_m->get_user_addresses();
		$this->data['uname'] = $user_details['name'];
		$this->data['uphone'] = $user_details['Phone'];
		$this->data['uemail'] = $user_details['email'];
		$resc_odetails = $this->odetails_m->get_odetails_for_reschedule($OId);
		$this->check_if_resc_available($resc_odetails['ODate']);
		if (intval($service_details['ServiceId']) == 4) {
			$this->data['insren_details'] = $this->odetails_m->get_insren_details($OId);
		}
		if (intval($service_details['ServiceId']) != 3) {
			$this->load->model('amenity_m');
			$this->load->model('opaymtdetail_m');
			$this->data['chosen_amenities'] = $this->amenity_m->get_chosen_amenities($OId);
			$this->data['scaddress'] = $this->odetails_m->get_sc_address($sc_details[0]['ScId']);
			$this->data['estprices'] = $this->amenity_m->get_est_prices_by_oid($OId);
			$this->data['discprices'] = $this->amenity_m->get_est_prices_by_oid($OId, TRUE);
			$this->data['is_amt_cfmd'] = $this->odetails_m->is_amt_confirmed($OId);
			$this->data['oprices'] = $this->statushistory_m->get_oprices($OId);
			$this->data['stathists'] = $this->statushistory_m->get_status_history($OId, FALSE, $sc_details[0]['ScId']);
			$this->data['ord_trans'] = $this->opaymtdetail_m->get_order_transactions($OId);
			$this->data['tot_paid'] = floatval($this->opaymtdetail_m->get_total_paid_amount($OId));
			$this->data['tot_billed'] = floatval($this->data['estprices'][count($this->data['estprices']) - 1]['ptotal']) + floatval($this->data['oprices'][count($this->data['oprices']) - 1]['ptotal']) - floatval($this->data['discprices'][count($this->data['discprices']) - 1]['ptotal']);
			$this->data['to_be_paid'] = round(floatval($this->data['tot_billed'] - $this->data['tot_paid']), 2);
			$this->data['is_jc_updated'] = $this->is_aexfupstatus_updated($OId, 5, 'ex');
			$this->data['is_est_updated'] = $this->is_aexfupstatus_updated($OId, 20, '');
			if($this->data['is_jc_updated']) {
				$this->getJobCard($OId);
				$this->get_jc_form_data($OId);
			}
			if($this->data['is_est_updated']) {
				$this->get_order_estimates($OId);
			}
			if($this->data['to_be_paid'] < 0.01 && $this->data['to_be_paid'] > -0.01) {
				$this->data['to_be_paid'] = 0;
			}
		} else {
			$this->data['stathists'] = $this->statushistory_m->get_status_history($OId, TRUE, NULL);
		}
	}
	private function get_order_estimates($OId) {
		$this->db->select('*');
		$this->db->from('jobcarddetails');
		$this->db->where('OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		if(!$result) {
			return NULL;
		} else {
			$this->data['jc_bike_estdate'] = $result['EstDate'];
			$this->data['jc_bike_esttime'] = $result['EstTime'];
			$this->data['jc_bike_estprice'] = 'INR ' . $result['EstPrice'];
			$this->data['jc_bike_estremarks'] = $this->db->select('Remarks')->from('ofupstatus')->where('ofupstatus.OId', $OId)->where('ofupstatus.FupStatusId', 20)->order_by('Timestamp', 'desc')->limit(1)->get()->row()->Remarks;			
			$this->data['CPName'] = $result['CPName'];
			$this->data['CPPhone'] = $result['CPPhone'];
		}
	}
	private function is_aexfupstatus_updated($OId, $statusid, $tablestr) {
		$this->db->select('COUNT(*) AS isExists');
		if($tablestr == 'ex') {
			$this->db->from('oexfupstatus');
			$this->db->where('oexfupstatus.EFupStatusId', $statusid);
		} else {
			$this->db->from('ofupstatus');
			$this->db->where('ofupstatus.FupStatusId', $statusid);
		}
		$this->db->where('OId', $OId);
		if($this->db->limit(1)->get()->row()->isExists) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	private function check_if_resc_available($odate) {
		$ctime = strtotime("today");
		$otime = strtotime('-2 days', strtotime($odate));
		if($ctime < $otime) {
			$this->data['is_canres_enabled'] = TRUE;
		} else {
			$this->data['is_canres_enabled'] = FALSE;
		}
	}
	private function get_all_user_orders() {
		$this->load->model('odetails_m');
		$this->load->model('amenity_m');
		$this->load->model('statushistory_m');
		$this->data['user_addresses'] = $this->user_m->get_user_addresses();
		$oids = $this->odetails_m->get_oids_user();
		$count = 0;
		if (count($oids) > 0) {
			foreach($oids as $oid) {
				$this->data['OIds'][$count] = $oid['OId'];
				$service_details = $this->odetails_m->get_stype_by_oid($oid['OId']);
				$this->data['stypes'][$count] = $service_details['ServiceName'];
				$this->data['serid'][$count] = intval($service_details['ServiceId']);
				$this->data['mr_remarks'][$count] = $service_details['MRRemarks'];
				$sc_details = $this->odetails_m->get_scenter_by_oid($oid['OId']);
				$this->data['scenters'][$count] = $sc_details;
				$bike_model_details = $this->odetails_m->get_bm_by_oid($oid['OId']);
				$this->data['bikemodels'][$count] = $bike_model_details['BikeCompanyName'] . ' ' . $bike_model_details['BikeModelName'];
				$this->data['timeslots'][$count] = $this->odetails_m->get_timeslot_by_oid($oid['OId']);
				$this->data['paymodes'][$count] = $this->odetails_m->get_paymode_by_oid($oid['OId']);
				$user_details = $this->odetails_m->get_user_address($oid['OId']);
				$this->data['uaddresses'][$count] = $user_details['address'];
				if (intval($service_details['ServiceId']) == 4) {
					$this->data['insren_details'][$count] = $this->odetails_m->get_insren_details($oid['OId']);
				}
				if (intval($service_details['ServiceId']) != 3) {
					$this->data['scaddresses'][$count] = $this->odetails_m->get_sc_address($sc_details[0]['ScId']);
					$this->data['estprices'][$count] = $this->amenity_m->get_est_prices_by_oid($oid['OId']);
					$this->data['opriceses'][$count] = $this->statushistory_m->get_oprices($oid['OId']);
				}
				$count += 1;
			}
		}
	}
	private function set_query_cookie($name, $value) {
		$cookie = array(
			'name'   => $name,
			'value'  => $value,
			'expire' => '86500',
			'secure' => FALSE
		);
		$this->input->set_cookie($cookie);
	}
}