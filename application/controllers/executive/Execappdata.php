<?php
class Execappdata extends G6_Execappcontroller {
	private $ex_row;
	private $auth_token;
	public function __construct() {
		parent::__construct();
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Expose-Headers: Access-Control-Allow-Origin');
		$this->output->set_header('Access-Control-Allow-Headers: origin, x-requested-with, x-source-ip, Accept, Authorization, User-Agent, Host, Accept-Language, Location, Referer, access-control-allow-origin, Access-Control-Allow-Headers, Content-Type');
		$this->output->set_header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
		$this->output->set_header('Access-Control-Allow-Credentials: true');
		$this->output->set_content_type('application/json');
		$this->check_auth_token();
	}
	public function set_gcmid() {
		if($_POST && $this->appresponse['ex_is_logged_in'] == 1 && $this->input->post('gcmid') != '') {
			$this->db->where('ExecId', intval($this->ex_row->ExecId))->update('executive', array('GCMId' => $this->input->post('gcmid')));
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function esignout() {
		$this->db->where('AuthToken', $this->auth_token)->update('executive', array('AuthToken' => NULL));
		$this->appresponse['status'] = 1;
		$this->appresponse['ex_is_logged_in'] = 0;
		echo json_encode($this->appresponse);
		exit;
	}
	public function elogin() {
		if($_POST && $this->appresponse['ex_is_logged_in'] == 0) {
			$this->appresponse['status'] = $this->executive_m->login(TRUE);
			if($this->appresponse['status'] == 1) {
				$orig_user = $this->executive_m->get_by(array(
					'Phone' => $this->input->post('phone', TRUE),
					'isActive' => 1
				), TRUE);
				$hash = generate_hash($orig_user->ExecId . time());
				$this->db->where('ExecId', intval($orig_user->ExecId))->update('executive', array('AuthToken' => $hash));
				$this->appresponse['auth_token'] = $this->auth_token = $hash;
				$this->appresponse['ex_is_logged_in'] = 1;
			}
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function eosummary() {
		$this->load->model('executive_m');
		$this->appresponse['curr_orders'] = $this->executive_m->get_assigned_odetails($this->ex_row->ExecId);
		$this->appresponse['contacts'] = $this->executive_m->get_ihcontacts();
		$this->load->model('g6data_m'); $g6data_row = $this->g6data_m->get(1);
		$this->appresponse['versionCode'] = intval($g6data_row->ExecAppVC);
		$this->appresponse['appUrl'] = $g6data_row->ExecAppURL;
		$this->appresponse['status'] = 1;
		echo json_encode($this->appresponse);
		exit;
	}
	public function get_order_details() {
		$OId = $this->input->post('oid');
		if(isset($OId) && $this->is_valid_oid($OId)) {
			$this->get_common_order_details($OId);
			$this->appresponse['status'] = 1;
			echo json_encode($this->appresponse);
		}
	}
	public function get_pre_servicing_details() {
		$OId = $this->input->post('oid');
		if(isset($OId) && $this->is_valid_oid($OId)) {
			$this->get_common_order_details($OId);
			$temp = $this->db->select('CPPhone, CPName, JcNum')->from('jobcarddetails')->where('OId', $OId)->limit(1)->get()->row();
			if(isset($temp->CPName) && isset($temp->CPPhone) && isset($temp->JcNum)) {
				$this->appresponse['CPPhone'] = $temp->CPPhone;
				$this->appresponse['CPName'] = $temp->CPName;
				$this->appresponse['JcNum'] = $temp->JcNum;
			} else {
				$this->appresponse['CPPhone'] = '';
				$this->appresponse['CPName'] = '';
				$this->appresponse['JcNum'] = '';
			}
			$this->appresponse['status'] = 1;
			echo json_encode($this->appresponse);
		}
	}
	public function get_job_card() {
		$OId = $this->input->post('oid');
		if(isset($OId) && $this->is_valid_oid($OId)) {
			$this->load->model('odetails_m');
			$this->load->model('opaymtdetail_m');
			$this->load->model('amenity_m');
			$this->load->model('aservice_m');
			$this->load->model('servicecenter_m');
			$this->load->model('statushistory_m');
			$this->load->model('executive_m');
			$this->appresponse['OId'] = $OId;
			$this->get_estimates($OId);
			$sc_details = $this->odetails_m->get_scenter_by_oid($OId);
			$service_details = $this->odetails_m->get_stype_by_oid($OId);
			$this->appresponse['scenter'] = $sc_details;
			$this->appresponse['service_name'] = $service_details['ServiceName'];
			$this->appresponse['scaddress'] = $this->odetails_m->get_app_sc_address($sc_details[0]['ScId']);
			$service_details = $this->odetails_m->get_stype_by_oid($OId);
			$this->appresponse['stype'] = $service_details['ServiceName'];
			$bike_model_details = $this->odetails_m->get_bm_by_oid($OId);
			$this->appresponse['bikenumber'] = $bike_model_details['BikeNumber'];
			$this->appresponse['bikemodel'] = $bike_model_details['BikeCompanyName'] . ' ' . $bike_model_details['BikeModelName'];
			$this->appresponse['timeslot'] = $this->odetails_m->get_timeslot_by_oid($OId);
			$this->appresponse['tot_paid'] = floatval($this->opaymtdetail_m->get_total_paid_amount($OId));
			$this->appresponse['csaddress'] = $this->odetails_m->get_app_user_address($OId);
			$this->appresponse['chosen_amenities'] = $this->amenity_m->get_chosen_amenities($OId);
			$this->appresponse['chosen_aservices'] = $this->aservice_m->get_chosen_aservices($OId);
			$odetail_row = $this->odetails_m->get_by(array('OId' => $OId), TRUE);
			$this->appresponse['total_paid'] = floatval($this->opaymtdetail_m->get_total_paid_amount($OId));
			$this->appresponse['jcimages'] = $this->executive_m->get_execjc_media($OId);
			$this->appresponse['execlcats'] = $this->executive_m->get_app_execcl_cats();
			$this->appresponse['jccats'] = $this->executive_m->get_app_jcard_cats();
			$this->get_jc_form_data($OId);
			$this->appresponse['cs_comments'] = $this->get_cs_comments($OId);
			$this->appresponse['bikedetails'] = $this->odetails_m->get_bike_regnum_by_oid($OId);
			$this->appresponse['ex_fup_statuses'] = $this->executive_m->get_ex_fup_rtime_statuses();
			$this->appresponse['customer'] = $this->odetails_m->get_app_user_address($OId);
			$this->appresponse['service_center'] = $this->servicecenter_m->get_sc_details(intval($sc_details[0]['ScId']));
			$this->load->model('regnum_m');
			$this->appresponse['regnums'] = $this->regnum_m->get_all_regnumvals();
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function updt_rtime_fup_status($statusid = FALSE) {
		if($_POST) {
			$updtData['Remarks'] = $this->input->post('remarks');
			$updtData['OId'] = $oid = $this->input->post('oid');
			if(!$statusid) {
				$updtData['EFupStatusId'] = intval($this->input->post('statusid'));
			} else {
				$updtData['EFupStatusId'] = $statusid;
			}
			if($this->input->post('lati') != '' && $this->input->post('longi') != '') {
				$updtData['Latitude'] = floatval($this->input->post('lati'));
				$updtData['Longitude'] = floatval($this->input->post('longi'));
				$updtData['LocationName'] = $this->reverse_geocode_latlong($updtData['Latitude'], $updtData['Longitude']);
			} else {
				$updtData['Latitude'] = NULL;
				$updtData['Longitude'] = NULL;
				$updtData['LocationName'] = NULL;
			}
			$updtData['UpdatedBy'] = $this->ex_row->ExecName;
			if($statusid == 2 || $updtData['EFupStatusId'] == 2) {
				$sql = 'INSERT INTO jobcarddetails (OId, Tag) VALUES (?, ?) ON DUPLICATE KEY UPDATE Tag = VALUES(Tag)';
				$query = $this->db->query($sql, array($oid, 2));
			}
			if($statusid == 5 || $updtData['EFupStatusId'] == 5) {
				$idata['ULatitude'] = $this->input->post('lati');
				$idata['ULongitude'] = $this->input->post('longi');
				$idata['isBreakdown'] = intval($this->input->post('isBreakdown'));
				$this->db->where('OId', $oid)->update('odetails', $idata);
				$this->db->where('OId', $oid)->update('admin_notification_flags', array('new_pickup' => 1));
				$and_reg_ids = $this->get_all_active_admin_devices();
				if(count($and_reg_ids) > 0) {
					$and_push_msg_data = array("message" => "Bike picked from customer for OId: " . $oid, "tag" => "odetailwithjobcard", "oid" => $oid);
					$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
				}
				if($idata['isBreakdown'] == 1) {
					$and_push_msg_data = array("message" => "Select Mode of Transport for breakdown order " . $oid, "tag" => "odetailwithjobcard", "oid" => $oid);
					$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
					$adminNotifyFlag['new_breakdown_order'] = 1; $adminNotifyFlag['new_breakdown_order_dismissed'] = 0;
					$this->db->where('OId', $oid)->update('admin_notification_flags', $adminNotifyFlag);
				}
				$uph = $this->uphone_by_oid($oid);
				$this->send_sms_request_to_api($uph, 'Your bike pick up is done and our executive(s) updated your jobcard - gear6.in');
				$execids = $this->get_all_assigned_executive_ids($oid); $track = array();
				if(count($execids) > 0) {
					foreach ($execids as $exec) {
						$track['OId'] = $oid; $track['ExecId'] = $exec; $track['isDelivered'] = 0;
						$track['isPicked'] = 1; $track['date'] = date('Y-m-d', strtotime("now"));
						$this->db->insert('execordertrack', $track);
					}
				}
			}
			if($statusid == 7 || $updtData['EFupStatusId'] == 7) {
				$this->db->where('OId', $oid)->update('admin_notification_flags', array('new_pickup_sc' => 1));
				$and_reg_ids = $this->get_all_active_admin_devices();
				if(count($and_reg_ids) > 0) {
					$and_push_msg_data = array("message" => "Bike picked from Service Center for OId: " . $oid, "tag" => "odetailwithjobcard", "oid" => $oid);
					$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
				}
			}
			if($statusid == 9 || $updtData['EFupStatusId'] == 9) {
				$idata['DLatitude'] = $this->input->post('lati');
				$idata['DLongitude'] = $this->input->post('longi');
				$this->db->where('OId', $oid)->update('odetails', $idata);
				$ijdata['PaymentMode'] = $this->input->post('payment');
				$ijdata['Tag'] = 7;
				$ijdata['nChkLists'] = $this->input->post('nchklists');
				$updtData['Remarks'] .= ' Payment Mode: ' . $ijdata['PaymentMode'];
				$uph = $this->uphone_by_oid($oid);
				$this->send_sms_request_to_api($uph, 'Your bike was successfully delivered - gear6.in');
				$this->updateUserFeedback();
				$this->updt_rtime_fup_status(10);
				if($this->input->post('payment') == 'cash') {
					$admin['new_payment'] = 1;
					$this->load->model('amenity_m');
					$this->load->model('statushistory_m');
					$this->load->model('opaymtdetail_m');
					$estprices = $this->amenity_m->get_est_prices_by_oid($oid);
					$discprices = $this->amenity_m->get_est_prices_by_oid($oid, TRUE);
					$oprices = $this->statushistory_m->get_oprices($oid);
					$tot_paid = floatval($this->opaymtdetail_m->get_total_paid_amount($oid));
					$tot_billed = floatval($estprices[count($estprices) - 1]['ptotal']) + floatval($oprices[count($oprices) - 1]['ptotal']) - floatval($discprices[count($discprices) - 1]['ptotal']);
					$to_be_paid = round(floatval($tot_billed - $tot_paid), 2);
					if($to_be_paid > 0.01) {
						$this->load->model('odetails_m');
						$user_id = $this->odetails_m->get_user_id_by_oid($oid);
						$this->opaymtdetail_m->create_trxn($user_id, $oid, $to_be_paid, TRUE);
					}
				}
				$admin['new_bike_delivered'] = 1;
				$this->db->where('OId', $oid)->update('admin_notification_flags', $admin);
				$this->db->insert('execbill', array('ExecId' => $this->ex_row->ExecId, 'OId' => $oid));
				$and_reg_ids = $this->get_all_active_admin_devices();
				if(count($and_reg_ids) > 0) {
					$and_push_msg_data = array("message" => "Executive taken feedback for OId: " . $oid, "tag" => "odetailwithjobcard", "oid" => $oid);
					$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
				}
				$execids = $this->get_all_assigned_executive_ids($oid); $track = array();
				if(count($execids) > 0) {
					foreach ($execids as $exec) {
						$track['OId'] = $oid; $track['ExecId'] = $exec; $track['isDelivered'] = 1;
						$track['isPicked'] = 0; $track['date'] = date('Y-m-d', strtotime("now"));
						$this->db->insert('execordertrack', $track);
					}
				}
				$this->db->where('OId', $oid)->update('jobcarddetails', $ijdata);
			}
			if($statusid == 11 || $updtData['EFupStatusId'] == 11) {
				$uph = $this->uphone_by_oid($oid);
				$this->send_sms_request_to_api($uph, 'Hi, gear6.in executive (' . $this->ex_row->ExecName . ' - ' . $this->ex_row->Phone . ') tried calling you to pick your bike. Please callback when you are available.');
			}
			$this->db->where('odetails.OId', $oid)->update('odetails', array('LastExFupStatusId' => $updtData['EFupStatusId']));
			$this->db->insert('oexfupstatus', $updtData);
			if(!$statusid) {
				$this->appresponse['status'] = 1;
			}
		}
		if(!$statusid) {
			echo json_encode($this->appresponse);
			exit;
		}
	}
	public function updt_jobcard() {
		$oid = $this->input->post('oid');
		$tag = $this->db->select('Tag')->from('jobcarddetails')->where('OId', $oid)->get()->row();
		if($tag && $tag->Tag > 2) {
			$this->appresponse['status'] = 1;
		} else {
			if($_POST && $this->is_valid_oid($oid)) {
				$jcdata['JCSelects'] = $this->input->post('jcvals');
				$jcdata['BikeColor'] = $this->input->post('cr_bikecolor');
				$jcdata['BikeKms'] = $this->input->post('cr_kms');
				$jcdata['FuelRange'] = $this->input->post('cs_fuelrange');
				$jcdata['UserComments'] = trim($this->input->post('us_comments'));
				if($jcdata['UserComments'] == '') {
					$jcdata['UserComments'] = NULL;
				}
				$estPrice['EstPrice'] = floatval($this->input->post('EstPrice'));
				$this->db->where('OId', $oid)->update('oexchkupstatus', $estPrice);
				$sql = 'INSERT INTO jobcarddetails (OId, JCSelects, BikeColor, BikeKms, FuelRange, UserComments) VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE JCSelects = VALUES(JCSelects), BikeColor = VALUES(BikeColor), BikeKms = VALUES(BikeKms), FuelRange = VALUES(FuelRange), UserComments = VALUES(UserComments)';
				$query = $this->db->query($sql, array($oid, $jcdata['JCSelects'], $jcdata['BikeColor'], $jcdata['BikeKms'], $jcdata['FuelRange'], $jcdata['UserComments']));
				$rbnumdata['BikeNumber'] = $this->input->post('regnum') . ' ' . $this->input->post('bikenum');
				$this->db->where('OId', $oid)->update('odetails', $rbnumdata);
				$this->updt_rtime_fup_status(3);
				$this->updt_rtime_fup_status(5);
				$this->db->where('OId', $oid)->update('jobcarddetails', array('Tag' => 3));
				if($query) {
					$this->appresponse['status'] = 1;
				}
			}
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function updt_pre_servicing_data() {
		if($_POST) {
			$oid = $this->input->post('oid');
			$tag = $this->db->select('Tag')->from('jobcarddetails')->where('OId', $oid)->get()->row();
			if($tag && $tag->Tag > 3) {
				$this->appresponse['status'] = 1;
			} else {
				$updtData['EstPrice'] = $this->input->post('price');
				$updtData['EstTime'] = date('Y-m-d H:i:s', intval($this->input->post('esttime')));
				$updtData['ScComments'] = $this->input->post('sccomments');
				$updtData['CusComments'] = $this->input->post('ps_ucomments');
				$updtData['OId'] = $oid;
				if($this->input->post('lati') != '' && $this->input->post('longi') != '') {
					$updtData['Latitude'] = floatval($this->input->post('lati'));
					$updtData['Longitude'] = floatval($this->input->post('longi'));
					$updtData['LocationName'] = $this->reverse_geocode_latlong($updtData['Latitude'], $updtData['Longitude']);
				} else {
					$updtData['Latitude'] = NULL;
					$updtData['Longitude'] = NULL;
					$updtData['LocationName'] = NULL;
				}
				$updtData['UpdatedBy'] = $this->ex_row->ExecName;
				$this->db->insert('oexchkupstatus', $updtData);
				$pickup_drop_flag = intval($this->db->select('pickup_drop_flag')->from('odetails')->where('OId', $oid)->get()->result_array()[0]['pickup_drop_flag']);
				$jobcarddetails['CPPhone'] = $this->input->post('CPPhone');
				$jobcarddetails['CPName'] = $this->input->post('CPName');
				if($pickup_drop_flag == 1) {
					$jobcarddetails['Tag'] = 7;
				} else {
					$jobcarddetails['Tag'] = 4;
				}
				$jobcarddetails['EstTaken'] = 1;
				$jckms = $this->input->post('kms');
				$jcnum = $this->input->post('jcnum');
				$sql = 'INSERT INTO jobcarddetails (OId, JcKms, JcNum) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE JcKms = VALUES(JcKms), JcNum = VALUES(JcNum)';
				$query = $this->db->query($sql, array($oid, $jckms, $jcnum));
				if($jckms && $jcnum) {
					$sms_flag = $this->updt_chkpdne_status($oid);
					if($sms_flag) {
						$uphone = $this->uphone_by_oid($oid);
						$esttime = date('d/m - h:i A', intval($this->input->post('esttime')));
						$this->$sms_flag($uphone, "Your bike's check up is done. The estimated service charge is INR " . $updtData['EstPrice'] . " (excluding convenience fees) and you can expect the delivery by " . $esttime);
					}
					$and_reg_ids = $this->get_all_active_admin_devices();
					if(count($and_reg_ids) > 0) {
						$and_push_msg_data = array("message" => 'Bike check up done for gear6.in order ' . $oid, "tag" => "odetailwithjobcard", "oid" => $oid);
						$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
					}
					$admin['bike_checkup'] = 1; $admin['bike_checkup_dismissed'] = 0;
					$this->db->where('OId', $oid)->update('admin_notification_flags', $admin);
				}
				$this->updt_rtime_fup_status(6);
				$this->db->where('OId', $oid)->update('jobcarddetails', $jobcarddetails);
				$this->appresponse['status'] = 1;
			}
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function estimates_later() {
		if($_POST) {
			$oid = $this->input->post('oid');
			$tag = $this->db->select('Tag')->from('jobcarddetails')->where('OId', $oid)->get()->row();
			if($tag && $tag->Tag > 3) {
				$this->appresponse['status'] = 1;
			} else {
				$updtData['EstPrice'] = $this->input->post('price');
				$updtData['EstTime'] = date('Y-m-d H:i:s', intval($this->input->post('esttime')));
				$updtData['ScComments'] = $this->input->post('sccomments');
				$updtData['CusComments'] = $this->input->post('ps_ucomments');
				$updtData['OId'] = $oid;
				if($this->input->post('lati') != '' && $this->input->post('longi') != '') {
					$updtData['Latitude'] = floatval($this->input->post('lati'));
					$updtData['Longitude'] = floatval($this->input->post('longi'));
					$updtData['LocationName'] = $this->reverse_geocode_latlong($updtData['Latitude'], $updtData['Longitude']);
				} else {
					$updtData['Latitude'] = NULL;
					$updtData['Longitude'] = NULL;
					$updtData['LocationName'] = NULL;
				}
				$updtData['UpdatedBy'] = $this->ex_row->ExecName;
				$this->db->insert('oexchkupstatus', $updtData);
				$jobcarddetails['CPPhone'] = $this->input->post('CPPhone');
				$jobcarddetails['CPName'] = $this->input->post('CPName');
				$jckms = $this->input->post('kms');
				$jcnum = $this->input->post('jcnum');
				$sql = 'INSERT INTO jobcarddetails (OId, JcKms, JcNum) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE JcKms = VALUES(JcKms), JcNum = VALUES(JcNum)';
				$query = $this->db->query($sql, array($oid, $jckms, $jcnum));
				$this->db->where('OId', $oid)->update('jobcarddetails', $jobcarddetails);
				$this->appresponse['status'] = 1;
			}
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function get_billing_details() {
		$OId = $this->input->post('oid');
		if($_POST && $this->is_valid_oid($OId)) {
			$this->get_common_order_details($OId);
			$this->load->model('amenity_m');
			$this->load->model('aservice_m');
			$this->appresponse['chosen_amenities'] = $this->amenity_m->get_chosen_amenities($OId);
			$this->appresponse['chosen_aservices'] = $this->aservice_m->get_chosen_aservices($OId);
			$temp_us_comments = $this->db->select('UserComments')->from('jobcarddetails')->where('OId', $OId)->limit(1)->get()->row()->UserComments;
			if(isset($temp_us_comments)) {
				$this->appresponse['us_comments'] = explode('||', $temp_us_comments);
			} else {
				$this->appresponse['us_comments'] = array();
			}
			$this->get_estimates($OId);
			$this->get_estimate_comments($OId);
			$this->getAccessoryDocuments($OId);
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function updt_billing_details() {
		$OId = $this->input->post('oid');
		if($_POST && $this->is_valid_oid($OId)) {
			$updtData['ScBillAmt'] = $this->input->post('bill_amt');
			$updtData['Tag'] = 5;
			$admin['new_bill_updated'] = 1; $admin['new_bill_updated_dismissed'] = 0;
			$this->db->where('OId', $OId)->update('admin_notification_flags', $admin);
			$and_reg_ids = $this->get_all_active_admin_devices();
			if(count($and_reg_ids) > 0) {
				$and_push_msg_data = array("message" => "Bill updated by executive for OId: " . $OId, "tag" => "odetailwithjobcard", "oid" => $OId);
				$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
			}
			$this->updt_rtime_fup_status(8);
			$_POST['remarks'] = 'Billed Amount is Rs. ' . $this->input->post('bill_amt');
			$this->updt_rtime_fup_status(19);
			$this->db->where('OId', $OId)->update('jobcarddetails', $updtData);
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function updt_billing_image() {
		$OId = $this->input->post('oid');
		if($_POST && $this->is_valid_oid($OId)) {
			$updtData['Tag'] = 6; $updtData['bill_comments'] = $this->input->post('bill_comments');
			$updtData['BillImgs'] = $this->bill_imgs_upload();
			$this->db->where('OId', $OId)->update('jobcarddetails', $updtData);
			$this->updt_rtime_fup_status(7);
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function get_bike_delivery_details() {
		$OId = $this->input->post('oid');
		if($_POST && $this->is_valid_oid($OId)) {
			$this->get_common_order_details($OId);
			$this->load->model('amenity_m');
			$this->load->model('statushistory_m');
			$this->load->model('aservice_m');
			$this->load->model('opaymtdetail_m');
			$this->appresponse['chosen_amenities'] = $this->amenity_m->get_chosen_amenities($OId);
			$this->appresponse['chosen_aservices'] = $this->aservice_m->get_chosen_aservices($OId);
			$temp_us_comments = $this->db->select('UserComments')->from('jobcarddetails')->where('OId', $OId)->limit(1)->get()->row()->UserComments;
			if(isset($temp_us_comments)) {
				$this->appresponse['us_comments'] = explode('||', $temp_us_comments);
			} else {
				$this->appresponse['us_comments'] = array();
			}
			$this->get_estimates($OId);
			$this->get_estimate_comments($OId);
			$estprices = $this->amenity_m->get_est_prices_by_oid($OId);
			if($estprices && count($estprices) > 0) {
				foreach($estprices as $price) {
					if(isset($price['ptotal'])) {
						$estprices_total = $price['ptotal'];
					} else {
						$price['attype'] = intval($price['attype']);
						unset($price['apid']);
						$this->appresponse['estprices'][] = $price;
					}
				}
			} else {
				$this->appresponse['estprices'] = NULL;
				$estprices_total = 0;
			}
			$this->appresponse['est_total'] = $estprices_total;
			$discprices = $this->amenity_m->get_est_prices_by_oid($OId, TRUE);
			if($discprices && count($discprices) > 0) {
				foreach($discprices as $price) {
					if(isset($price['ptotal'])) {
						$discprices_total = $price['ptotal'];
					} else {
						unset($price['apid']);
						$this->appresponse['discprices'][] = $price;
					}
				}
			} else {
				$this->appresponse['discprices'] = NULL;
				$discprices_total = 0;
			}
			$this->appresponse['disc_total'] = $discprices_total;
			$this->appresponse['is_amt_cfmd'] = $this->odetails_m->is_amt_confirmed($OId);
			$oprices = $this->statushistory_m->get_oprices($OId);
			if($oprices && count($oprices) > 0) {
				foreach($oprices as $price) {
					if(isset($price['ptotal'])) {
						$oprices_total = $price['ptotal'];
					} else {
						unset($price['opid']);
						$this->appresponse['oprices'][] = $price;
					}
				}
			} else {
				$this->appresponse['oprices'] = NULL;
				$oprices_total = 0;
			}
			$this->appresponse['oprice_total'] = $oprices_total;
			$this->appresponse['total_paid'] = floatval($this->opaymtdetail_m->get_total_paid_amount($OId));
			$this->appresponse['total_price'] = floatval($estprices_total) + floatval($oprices_total);
			$this->appresponse['to_be_paid'] = round(floatval($this->appresponse['total_price'] - $discprices_total - $this->appresponse['total_paid']), 2);
			if($this->appresponse['to_be_paid'] < 0.01 && $this->appresponse['to_be_paid'] > -0.01) {
				$this->appresponse['to_be_paid'] = 0;
			}
			$this->appresponse['fb_questions'] = $this->get_fb_questions($OId);
			$this->appresponse['fb_remarks'] = $this->get_fb_remarks($OId);
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function updt_delivery_details() {
		$oid = $this->input->post('oid');
		$tag = $this->db->select('Tag')->from('jobcarddetails')->where('OId', $oid)->get()->row();
		if($tag && $tag->Tag > 6) {
			$this->appresponse['status'] = 1;
		} else {
			if($_POST && $this->is_valid_oid($oid)) {
				$this->updt_rtime_fup_status(9);
				$this->appresponse['status'] = 1;
			}
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function updt_pbclaim() {
		if($_POST) {
			$idata['SLatitude'] = $this->input->post('slati');
			$idata['SLongitude'] = $this->input->post('slongi');
			$idata['ELatitude'] = $this->input->post('elati');
			$idata['ELongitude'] = $this->input->post('elongi');
			$jdata = $this->get_google_distance_matrix_data($idata['SLatitude'], $idata['SLongitude'], $idata['ELatitude'], $idata['ELongitude']);
			if($jdata['status'] == 'OK') {
				$idata['SLocation'] = implode(', ', $jdata['origin_addresses']);
				$idata['ELocation'] = implode(', ', $jdata['destination_addresses']);
				$idata['Purpose'] = $this->input->post('purpose');
				$idata['Kms'] = round(floatval($jdata['rows'][0]['elements'][0]['distance']['value']) / 1000.0, 2);
				$idata['Price'] = $idata['Kms'] * 2.5;
				$idata['ExecId'] = intval($this->ex_row->ExecId);
				$idata['StartTimestamp'] = date('Y-m-d H:i:s', intval($this->input->post('stime')));
				$idata['EndTimestamp'] = date('Y-m-d H:i:s', intval($this->input->post('etime')));
				$idata['Date'] = date('Y-m-d', intval($this->input->post('etime')));
				$this->db->insert('petrolbills', $idata);
				$this->appresponse['status'] = 1;
			}
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function petrolbills() {
		$this->appresponse['ltstpbills'] = $this->executive_m->get_latest_pbills(NULL, NULL, $this->ex_row->ExecId);
		$this->appresponse['status'] = 1;
		echo json_encode($this->appresponse);
		exit;
	}
	public function getRewards() {
		$this->db->select('odetails.OId, executive.ExecName, executive.Phone, execrewards.Type, execrewards.Amount, execrewards.Description, execrewards.UpdatedBy, execrewards.updated_at AS Date, execrewards.ExecRewardId, execrewards.isCleared');
		$this->db->from('execrewards');
		$this->db->join('executive', 'executive.ExecId = execrewards.ExecId', 'left');
		$this->db->join('odetails', 'odetails.OId = execrewards.OId', 'left');
		if(isset($_POST['startDate']) && isset($_POST['endDate'])) {
			$this->db->where('updated_at >=', date("Y-m-d", strtotime($_POST['startDate'])));
			$this->db->where('updated_at <=', date("Y-m-d", strtotime($_POST['endDate'])));
		} else {
			$this->db->where('updated_at >=', date("Y-m-d", strtotime("now -7 days")));
			$this->db->where('updated_at <=', date("Y-m-d", strtotime("now")));
		}
		$this->db->where('executive.ExecId', intval($this->ex_row->ExecId));
		$this->db->order_by('execrewards.updated_at', 'desc');
		$result = $this->db->get()->result_array();
		$sql = "SELECT COALESCE(ROUND((COALESCE(rewardcredits.crewards, 0) - COALESCE(rewarddebits.drewards, 0)), 2), 0) AS rewards FROM executive LEFT JOIN (SELECT SUM(execrewards.Amount) AS crewards, executive.ExecId FROM executive INNER JOIN execrewards ON execrewards.ExecId = executive.ExecId WHERE execrewards.isCleared = '0' AND execrewards.Type = 'Credit' GROUP BY executive.ExecId) AS rewardcredits ON rewardcredits.ExecId = executive.ExecId LEFT JOIN (SELECT SUM(execrewards.Amount) AS drewards, executive.ExecId FROM executive INNER JOIN execrewards ON execrewards.ExecId = executive.ExecId WHERE execrewards.isCleared = '0' AND execrewards.Type = 'Debit' GROUP BY executive.ExecId) AS rewarddebits ON rewarddebits.ExecId = executive.ExecId";
		$return = $this->db->query($sql)->row_array();
		if($return) {
			$this->appresponse['totalReward'] = $return['rewards'];
		} else {
			$this->appresponse['totalReward'] = 0;
		}

		if(count($result) > 0) {
			$this->appresponse['rewards'] = $result;
		} else {
			$this->appresponse['rewards'] = array();
		}
		$this->appresponse['status'] = 1;
		echo json_encode($this->appresponse);
		exit;
	}
	private function updateUserFeedback() {
		if($_POST) {
			$OId = $this->input->post('oid'); $remarks = $this->input->post('remarks');
			$feedbackArray = explode("||", $this->input->post('feedbackArray'));
			$questionArray = explode("||", $this->input->post('questionArray'));
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
			}
		}
	}
	private function get_fb_questions($OId) {
		$sql = "SELECT execfbqs.*, user_feedback_oid.ExecFbAnswer FROM execfbqs ";
		$sql .= "LEFT JOIN (SELECT ExecFbAnswer, ExecFbQId FROM user_feedback WHERE user_feedback.OId = '" . $OId . "') AS user_feedback_oid ON (user_feedback_oid.ExecFbQId = execfbqs.ExecFbQId) ";
		$sql .= "WHERE execfbqs.isEnabled = '1' ORDER BY execfbqs.ExecFbQId ASC";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		return $result;
	}
	private function get_fb_remarks($OId) {
		$this->db->select('user_feedback_remarks');
		$this->db->from('odetails');
		$this->db->where('OId', $OId);
		$query = $this->db->get();
		$remarks = $query->result_array();
		if($remarks == NULL && count($remarks) == 0) { $remarks = ""; } else { $remarks = $remarks[0]['user_feedback_remarks']; }
		return $remarks;
	}
	private function get_google_distance_matrix_data($slati, $slongi, $elati, $elongi) {
		$apiKey = 'AIzaSyCJAZ8XEe77EEImcMfeeWVyW7KTAG1CwAM';
		$api_url = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $slati . ',' . $slongi . '&destinations=' . $elati . ',' . $elongi . '&mode=driving&units=metric&language=en&key=' . $apiKey;
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $api_url);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl_handle, CURLOPT_POST, FALSE);
		curl_setopt($curl_handle, CURLOPT_HEADER, FALSE);
		curl_setopt($curl_handle, CURLOPT_USERAGENT, 'gear6.in');
		$content = curl_exec($curl_handle);
		curl_close($curl_handle);
		$content = json_decode($content, TRUE);
		return $content;
	}
	private function bill_imgs_upload() {
		$count = intval($this->input->post('img_count'));
		$pcount = 0;
		for($i = 1; $i <= $count; $i++) {
			if($this->input->post('billimg_' . $i) != '') {
				$file_name = md5(uniqid(mt_rand())) . '.jpg';
				$temp_file_path = realpath(APPPATH . '../html/uploads/temp') . '/' . $file_name;
				file_put_contents($temp_file_path, base64_decode($this->input->post('billimg_' . $i)));
				$image_info = filesize($temp_file_path);
				if($image_info < 5242880) {
					$upload_data[$pcount]['name'] = $file_name;
					$upload_data[$pcount]['type'] = 'img';
					$pcount += 1;
				} else {
					unlink($temp_file_path);
				}
			}
		}
		if(isset($upload_data) && count($upload_data) > 0) {
			$this->upload_bills_to_s3($upload_data);
			return serialize($upload_data);
		} else {
			return NULL;
		}
	}
	private function upload_bills_to_s3(&$uploaded_media) {
		foreach($uploaded_media as $file) {
			$from_file = realpath(APPPATH . '../html/uploads/temp');
			$from_file = rtrim($from_file, '/').'/';
			$from_file .= $file['name'];
			$to_file = 'uploads/omedia/';
			$to_file .= $file['type'] . '/' . $file['name'];
			$this->load->library('awssdk');
			$s3 = $this->awssdk->get_s3_instance();
			try {
				$s3->putObject([
					'Bucket' => 'gear6cdn',
					'Key'    => $to_file,
					'Body'   => fopen($from_file, 'r'),
					'ACL'    => 'public-read',
				]);
			} catch (Aws\Exception\S3Exception $e) {
			}
			unlink($from_file);
		}
	}
	private function get_common_order_details($OId) {
		$this->load->model('odetails_m');
		$this->load->model('opaymtdetail_m');
		$this->load->model('servicecenter_m');
		$this->load->model('statushistory_m');
		$this->load->model('executive_m');
		$this->appresponse['OId'] = $OId;
		$sc_details = $this->odetails_m->get_scenter_by_oid($OId);
		$service_details = $this->odetails_m->get_stype_by_oid($OId);
		$this->appresponse['otype'] = intval($this->db->select('pickup_drop_flag')->from('odetails')->where('OId', $OId)->get()->result_array()[0]['pickup_drop_flag']);
		$this->appresponse['scenter'] = $sc_details;
		$this->appresponse['service_name'] = $service_details['ServiceName'];
		$bike_model_details = $this->odetails_m->get_bm_by_oid($OId);
		$this->appresponse['bikenumber'] = $bike_model_details['BikeNumber'];
		$this->appresponse['bikemodel'] = $bike_model_details['BikeCompanyName'] . ' ' . $bike_model_details['BikeModelName'];
		$this->appresponse['timeslot'] = $this->odetails_m->get_timeslot_by_oid($OId);
		$this->appresponse['total_paid'] = floatval($this->opaymtdetail_m->get_total_paid_amount($OId));
		$this->appresponse['customer'] = $this->odetails_m->get_app_user_address($OId);
		$this->appresponse['service_center'] = $this->servicecenter_m->get_sc_details(intval($sc_details[0]['ScId']));
		$this->appresponse['ex_fup_statuses'] = $this->executive_m->get_ex_fup_rtime_statuses();
		$this->appresponse['cs_comments'] = $this->get_cs_comments($OId);
		$this->appresponse['isBreakdown'] = intval($this->odetails_m->get_by(array('OId' => $OId), TRUE)->isBreakdown);
		$temp_comments = $this->db->select('ServiceDesc1, ServiceDesc2')->from('oservicedetail')->where('OId', $OId)->limit(1)->get()->row();
		$this->appresponse['uo_comments'] = trim($temp_comments->ServiceDesc1 . ' ' . $temp_comments->ServiceDesc2);
	}
	private function is_valid_oid($OId) {
		$row = $this->db->select('COUNT(*) AS NoOfRows')->from('execassigns')->where(array('ExecId' => intval($this->ex_row->ExecId), 'OId' => $OId))->get()->row_array();
		if($row['NoOfRows'] == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	private function check_auth_token() {
		$this->appresponse['status'] = 0;
		$this->auth_token = $this->input->get_request_header('ex_auth_token', TRUE);
		$this->appresponse['ex_is_logged_in'] = 0;
		if($this->auth_token == '' || $this->auth_token == NULL) {
			$ph = (bool) $this->input->post('phone');
			$pwd = (bool) $this->input->post('password');
			if(!$ph || !$pwd) {
				echo json_encode($this->appresponse);
				exit;
			}
		} else {
			$ex = $this->executive_m->get_by(array('AuthToken' => $this->auth_token), TRUE);
			if($ex) {
				$this->appresponse['ex_is_logged_in'] = 1;
				$this->ex_row = $ex;
				$this->appresponse['ex_row']['exid'] = $ex->ExecId;
				$this->appresponse['ex_row']['exname'] = $ex->ExecName;
				$this->appresponse['ex_row']['exphone'] = $ex->Phone;
				$this->appresponse['ex_row']['exemail'] = $ex->Email;
			} else {
				echo json_encode($this->appresponse);
				exit;
			}
		}
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
			$this->appresponse['execlscatsckd'] = explode('||', $result['ChecklistVals']);
			$this->appresponse['jcselects'] = explode('||', $result['JCSelects']);
			$this->appresponse['cr_bikecolor'] = $result['BikeColor'];
			$this->appresponse['cr_kms'] = $result['BikeKms'];
			$this->appresponse['cs_fuelrange'] = $result['FuelRange'];
			$this->appresponse['payment'] = $result['PaymentMode'];
			if(isset($result['UserComments'])) {
				$this->appresponse['us_comments'] = explode('||', $result['UserComments']);
			} else {
				$this->appresponse['us_comments'] = array();
			}
			$this->appresponse['jc_kms'] = $result['JcKms'];
			$this->appresponse['jc_num'] = $result['JcNum'];
		}
	}
	private function get_cs_comments($oid) {
		$this->db->select('Remarks, Timestamp');
		$this->db->from('ofupstatus');
		$this->db->where('ofupstatus.OId', $oid);
		$this->db->where('ofupstatus.FupStatusId', 14);
		$this->db->order_by('ofupstatus.Timestamp', 'desc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
	private function get_estimates($oid) {
		$this->db->select('oexchkupstatus.EstPrice, oexchkupstatus.EstTime');
		$this->db->from('oexchkupstatus');
		$this->db->where('oexchkupstatus.OId', $oid);
		$this->db->order_by('oexchkupstatus.Timestamp', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row();
		if (!$result) {
			$this->appresponse['EstPrice'] = 0;
			$this->appresponse['EstTime'] = NULL;
		} else {
			$this->appresponse['EstPrice'] = $result->EstPrice;
			$this->appresponse['EstTime'] = date('d M, h:i A', strtotime($result->EstTime));
		}
	}
	private function get_estimate_comments($oid) {
		$this->db->select('oexchkupstatus.ScComments AS EstScComments, oexchkupstatus.CusComments AS EstUserComments');
		$this->db->from('oexchkupstatus');
		$this->db->where('oexchkupstatus.OId', $oid);
		$this->db->order_by('oexchkupstatus.Timestamp', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row();
		if (!$result) {
			$this->appresponse['EstScComments'] = NULL;
			$this->appresponse['EstUserComments'] = NULL;
		} else {
			$this->appresponse['EstScComments'] = $result->EstScComments;
			$this->appresponse['EstUserComments'] = $result->EstUserComments;
		}
	}
	private function updt_chkpdne_status($oid) {
		$odstatus = $this->db->select('odetails.ServiceId, oservicedetail.StatusId, oservicedetail.ScId, odetails.TieupId')->from('odetails')->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left')->where('odetails.OId', $oid)->limit(1)->get()->row_array();
		$changestatus = TRUE;
		if(intval($odstatus['ServiceId']) == 1) {
			$statusid = 4;
		} elseif(intval($odstatus['ServiceId']) == 2) {
			$statusid = 10;
		} else {
			$changestatus = FALSE;
		}
		if($changestatus) {
			$data['OId'] = $oid;
			$data['StatusId'] = $statusid;
			$data['ScId'] = intval($odstatus['ScId']);
			$data['StatusDescription'] = NULL;
			$data['AdminNotes'] = NULL;
			$data['ModifiedBy'] = $this->ex_row->ExecName;
			$this->load->model('statushistory_m');
			$this->statushistory_m->save($data);
			$this->db->where('OId', $data['OId']);
			$this->db->limit(1);
			$this->db->update('oservicedetail', array('StatusId' => $data['StatusId']));
			if(intval($odstatus['TieupId']) == 2) {
				return 'send_hj_sms';
			} else {
				return 'send_sms_request_to_api';
			}
		}
		return $changestatus;
	}
	private function getAccessoryDocuments($OId) {
		$this->appresponse['documents'] = NULL; $this->appresponse['accessories'] = NULL;
		$JCSelects = $this->db->select('JCSelects')->from('jobcarddetails')->where('OId', $OId)->get()->result_array();
		if($JCSelects != NULL && count($JCSelects) > 0) {
			if($JCSelects[0]['JCSelects'] != NULL && $JCSelects[0]['JCSelects'] != "" && count($JCSelects[0]['JCSelects']) > 0) {
				$JCSelects = explode("||", $JCSelects[0]['JCSelects']);
				$documents = $this->db->select('JCSCatName')->from('jobcardscats')->where_in('JCSCatId', $JCSelects)->where('JCCatId', 23)->get()->result_array();
				$accessories = $this->db->select('JCSCatName')->from('jobcardscats')->where_in('JCSCatId', $JCSelects)->where('JCCatId', 24)->get()->result_array();
				if(count($documents) > 0) {
					$temp = array();
					foreach ($documents as $document) {
						$temp[] = $document['JCSCatName'];
					}
					$this->appresponse['documents'] = implode(" ", $temp);
				}
				if(count($accessories) > 0) {
					$temp = array();
					foreach ($accessories as $accessory) {
						$temp[] = $accessory['JCSCatName'];
					}
					$this->appresponse['accessories'] = implode(" ", $temp);
				}
			}
		}
	}
	private function reverse_geocode_latlong($lati, $longi) {
		if(isset($lati) && isset($longi)) {
			$apiKey = 'AIzaSyCJAZ8XEe77EEImcMfeeWVyW7KTAG1CwAM';
			$api_url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lati . ',' . $longi . '&language=en&key=' . $apiKey;
			$curl_handle = curl_init();
			curl_setopt($curl_handle, CURLOPT_URL, $api_url);
			curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 3);
			curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($curl_handle, CURLOPT_POST, FALSE);
			curl_setopt($curl_handle, CURLOPT_HEADER, FALSE);
			curl_setopt($curl_handle, CURLOPT_USERAGENT, 'gear6.in');
			$content = curl_exec($curl_handle);
			curl_close($curl_handle);
			$content = json_decode($content, TRUE);
			return $content['results'][0]['formatted_address'];
		} else {
			return NULL;
		}
	}
	private function uphone_by_oid($oid) {
		$rec = $this->db->select('user.Phone')->from('odetails')->join('user', 'user.UserId = odetails.UserId')->where('odetails.OId', $oid)->limit(1)->get()->row_array();
		return $rec['Phone'];
	}
	private function get_user_feedback_rating_by_oid($OId) {
		$rating = $this->db->select('user_feedback_rating')->from('odetails')->where('OId', $OId)->get()->result_array();
		if(count($rating) > 0) { return intval($rating[0]['user_feedback_rating']); } else { return 0; }
	}
	private function get_user_feedback_rating_by_oid_question($OId) {
		$rating = $this->db->select('ExecFbAnswer')->from('user_feedback')->where('OId', $OId)->where('ExecFbQId', 3)->get()->result_array();
		if(count($rating) > 0) { return intval($rating[0]['ExecFbAnswer']); } else { return 0; }
	}
	public function get_petty_cash() {
		$this->db->select('*')->from('pettycash')->where('ExecId', intval($this->ex_row->ExecId));
		if($this->input->post('startDate') && $this->input->post('endDate')) {
			$this->db->where('date >=', date("Y-m-d", strtotime($this->input->post('startDate'))));
			$this->db->where('date <=', date("Y-m-d", strtotime($this->input->post('endDate'))));
		} else {
			$this->db->where('date >=', date("Y-m-d", strtotime("now -7 days")));
			$this->db->where('date <=', date("Y-m-d", strtotime("now")));
		}
		$petty_cash = $this->db->get()->result_array();
		if($petty_cash) { $this->appresponse['petty_cash'] = $petty_cash; } else { $this->appresponse['petty_cash'] = array(); }
		$this->appresponse['status'] = 1; echo json_encode($this->appresponse); exit;
	}
	public function create_petty_cash() {
		if($_POST) {
			$pettycash['Amount'] = $this->input->post('Amount');
			$pettycash['date'] = date('Y-m-d', strtotime("now"));
			$pettycash['ExecId'] = intval($this->ex_row->ExecId);
			$pettycash['Description'] = $this->input->post('Description');
			$pettycash['UpdatedBy'] = $this->ex_row->ExecName;
			if($this->input->post('OId') && $this->is_valid_oid($this->input->post('OId'))) { $pettycash['OId'] = $this->input->post('OId'); } else { $pettycash['OId'] = NULL; }
			$this->db->insert('pettycash', $pettycash); $this->appresponse['status'] = 1;
			echo json_encode($this->appresponse); exit;
		}
	}
}