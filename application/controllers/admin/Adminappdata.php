<?php
class Adminappdata extends G6_Adminappcontroller {
	private $a_row;
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
	public function asignout() {
		$this->db->where('AuthToken', $this->auth_token)->update('admin', array('AuthToken' => NULL, 'GCMId' => NULL));
		$this->appresponse['status'] = 1;
		$this->appresponse['a_is_logged_in'] = 0;
		echo json_encode($this->appresponse);
		exit;
	}
	public function set_gcmid() {
		if($_POST && $this->appresponse['a_is_logged_in'] == 1 && $this->input->post('gcmid') != '') {
			$this->db->where('AdminId', intval($this->a_row->AdminId))->update('admin', array('GCMId' => $this->input->post('gcmid')));
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function alogin() {
		if($_POST && $this->appresponse['a_is_logged_in'] == 0) {
			$this->appresponse['status'] = $this->admin_m->applogin();
			if($this->appresponse['status'] == 1) {
				$orig_user = $this->admin_m->get_by(array(
					'Phone' => $this->input->post('phone', TRUE),
					'isActive' => 1
				), TRUE);
				$hash = generate_hash($orig_user->AdminId . time());
				$this->db->where('AdminId', intval($orig_user->AdminId))->update('admin', array('AuthToken' => $hash));
				$this->appresponse['auth_token'] = $this->auth_token = $hash;
				$this->appresponse['a_is_logged_in'] = 1;
			}
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function aosummary() {
		$this->load->model('executive_m');
		$this->appresponse['curr_orders'] = $this->admin_m->app_get_todays_orders($this->a_row->CityId);
		$this->appresponse['contacts'] = $this->executive_m->get_ihcontacts($this->a_row->CityId);
		$this->load->model('g6data_m'); $g6data_row = $this->g6data_m->get(1);
		$this->appresponse['versionCode'] = intval($g6data_row->AdminAppVC);
		$this->appresponse['appUrl'] = $g6data_row->AdminAppURL;
		$this->appresponse['status'] = 1;
		echo json_encode($this->appresponse);
		exit;
	}
	public function unallotted_orders() {
		$this->load->model('executive_m');
		$this->appresponse['unallot_orders'] = $this->admin_m->get_unallotted_orders($this->a_row->CityId);
		$this->appresponse['contacts'] = $this->executive_m->get_ihcontacts($this->a_row->CityId);
		$this->appresponse['status'] = 1;
		echo json_encode($this->appresponse);
		exit;
	}
	public function allotted_orders() {
		$this->load->model('executive_m');
		$this->appresponse['allot_orders'] = $this->admin_m->get_allotted_orders($this->a_row->CityId);
		$this->appresponse['contacts'] = $this->executive_m->get_ihcontacts($this->a_row->CityId);
		$this->appresponse['status'] = 1;
		echo json_encode($this->appresponse);
		exit;
	}
	public function serviced_orders() {
		$this->load->model('executive_m');
		$this->appresponse['serviced_orders'] = $this->admin_m->get_serviced_orders($this->a_row->CityId);
		$this->appresponse['contacts'] = $this->executive_m->get_ihcontacts($this->a_row->CityId);
		$this->appresponse['status'] = 1;
		echo json_encode($this->appresponse);
		exit;
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
				$jcdata['UserComments'] = $this->input->post('us_comments');
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
	public function updt_checklist() {
		$oid = $this->input->post('oid');
		if($_POST && $this->is_valid_oid($oid)) {
			$execlvals = $this->input->post('execlvals');
			$sql = 'INSERT INTO jobcarddetails (OId, ChecklistVals) VALUES (?, ?) ON DUPLICATE KEY UPDATE ChecklistVals = VALUES(ChecklistVals)';
			$query = $this->db->query($sql, array($oid, $execlvals));
			if($query) {
				$this->appresponse['status'] = 1;
			}
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function jcimg_upload() {
		$this->load->library('upload', $this->upload_config());
		$oid = $this->input->post('oid');
		$tagname = $this->input->post('tagname');
		$fimgview = $this->input->post('imgview');
		if($this->is_valid_oid($oid) && $this->upload->do_upload('jc_image')) {
			$file_data = $this->upload->data();
			$upload_data['FileName'] = $file_data['file_name'];
			$upload_data['OId'] = $oid;
			$upload_data['TagName'] = $tagname;
			$upload_data['FileImgView'] = $fimgview;
			if(boolval($file_data['is_image'])) {
				$upload_data['FileType'] = 'img';
			} else {
				$upload_data['FileType'] = 'invalid';
			}
			$from_file = realpath(APPPATH . '../html/uploads/temp');
			$from_file = rtrim($from_file, '/').'/';
			$from_file .= $upload_data['FileName'];
			$to_file = 'uploads/omedia/';
			$to_file .= $upload_data['FileType'] . '/' . $upload_data['FileName'];
			$this->db->insert('execmedia', $upload_data);
			$this->appresponse['imgurl'] = get_awss3_url('uploads/omedia/img/' . $upload_data['FileName']);
			$this->load->library('awssdk');
			$s3 = $this->awssdk->get_s3_instance();
			try {
				$s3->putObject([
					'Bucket' => 'gear6cdn',
					'Key'    => $to_file,
					'Body'   => fopen($from_file, 'r'),
					'ACL'    => 'public-read',
				]);
				$this->appresponse['status'] = 1;
			} catch (Aws\Exception\S3Exception $e) {
			}
			unlink($from_file);
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function afup_status_update() {
		if($this->input->post('fup_status') != '') {
			$idata['OId'] = $oid = $this->input->post('oid');
			$idata['Remarks'] = $this->input->post('fup_remarks');
			$idata['FupStatusId'] = intval($this->input->post('fup_status'));
			$idata['UpdatedBy'] = $this->a_row->AdminName;
			$this->db->insert('ofupstatus', $idata);
			$this->db->where('OId', $idata['OId']);
			$this->db->update('odetails', array('LastFupStatusId' => $idata['FupStatusId']));
			$smstouser = intval($this->input->post('smstouser'));
			$smstoexec = intval($this->input->post('smstoexec'));
			if($smstouser == 1 && $idata['FupStatusId'] == 19) {
				$uphone = $this->uphone_by_oid($oid);
				$enc_oid = encrypt_oid($oid);
				$paymt_url = 'https://www.gear6.in/home/gpaymtlink/' . $enc_oid;
				$this->send_sms_request_to_api($uphone, 'Payment link for your gear6.in order ' . $oid . ' is ' . $paymt_url . ' . Kindly visit and make the payment.');
			}
			if($smstouser == 1 && $idata['FupStatusId'] == 13) {
				$uphone = $this->uphone_by_oid($oid);
				$this->send_sms_request_to_api($uphone, $idata['Remarks']);
			}
			if($smstoexec == 1 && $idata['FupStatusId'] == 14) {
				$ephones = $this->exphones_by_oid($oid);
				foreach($ephones as $ephone) {
					$this->send_sms_request_to_api($ephone, $idata['Remarks']);
				}
			}
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function assign_execs() {
		if($_POST) {
			$oid = $this->input->post('oid');
			$execids = explode('||', $this->input->post('execs'));
			$this->db->where('OId', $oid);
			$this->db->delete('execassigns');
			if(isset($oid) && isset($execids) && count($execids) > 0) {
				$count = 0;
				foreach($execids as $execid) {
					$idata[$count]['OId'] = $oid;
					$idata[$count]['ExecId'] = intval($execid);
					$count ++;
				}
				$this->db->insert_batch('execassigns', $idata);
				$sms_user_flag = intval($this->input->post('smsuser'));
				if($sms_user_flag == 1) {
					$execdata = $this->db->select('ExecName, Phone')->from('executive')->where_in('ExecId', $execids)->get()->result();
					if(count($execdata) > 1) {
						$exstr = 'executives';
					} elseif(count($execdata) == 1) {
						$exstr = 'executive';
					}
					foreach($execdata as $ex) {
						$exstrmsg[] = $ex->ExecName . ' (Ph: ' . $ex->Phone . ')';
						$this->send_sms_request_to_api($ex->Phone, 'A new gear6.in order ' . $oid . ' was assigned to you. Open your exective app (http://executive.gear6.in/) for more order details.');
					}
					$parsedexecdata = implode(', ', $exstrmsg);
					$this->load->model('odetails_m');
					$timeslot = $this->odetails_m->get_timeslot_by_oid($oid);
					$od_row = $this->odetails_m->get_by(array('OId' => $oid), TRUE);
					$ph = $this->odetails_m->get_user_ph_by_oid($oid);
					if($od_row->ServiceId == 4) {
						$smsg = 'Our ' . $exstr . ' ' . $parsedexecdata . ' will be coming to collect your documents on ' . $timeslot;
					} else {
						$smsg = 'Our ' . $exstr . ' ' . $parsedexecdata . ' will be coming to collect your bike on ' . $timeslot;
					}
					$this->send_sms_request_to_api($ph, $smsg);
				}
			}
			$this->appresponse['assigned_execs'] = $this->get_execs_assigned($oid);
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function aodetail() {
		$OId = $this->input->post('oid');
		if(isset($OId) && $this->is_valid_oid($OId)) {
			$this->load->model('odetails_m');
			$this->load->model('opaymtdetail_m');
			$this->load->model('amenity_m');
			$this->load->model('aservice_m');
			$this->load->model('servicecenter_m');
			$this->load->model('statushistory_m');
			$this->load->model('executive_m');
			$this->load->model('fupstatus_m');
			$this->appresponse['OId'] = $OId;
			$ODate = $this->odetails_m->get_odate_by_oid($OId);
			$this->appresponse['execs'] = $this->executive_m->get_active_executives($ODate);
			$this->appresponse['fup_statuses'] = $this->fupstatus_m->get_by(array('isEnabled' => 1));
			$this->appresponse['fupstathistory'] = $this->fupstatus_m->get_fupstat_history($OId);
			$sc_details = $this->odetails_m->get_scenter_by_oid($OId);
			$this->appresponse['scenter'] = $sc_details;
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
			$this->appresponse['fscdetails'] = $this->servicecenter_m->get_sc_details(intval($sc_details[0]['ScId']));
			$odetail_row = $this->odetails_m->get_by(array('OId' => $OId), TRUE);
			$this->appresponse['isBreakdown'] = intval($odetail_row->isBreakdown);
			$this->appresponse['transport_mode'] = intval($odetail_row->transportMode);
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
			$this->appresponse['jcimages'] = $this->executive_m->get_execjc_media($OId);
			$this->appresponse['billimages'] = $this->executive_m->get_bill_media($OId);
			$this->appresponse['execlcats'] = $this->executive_m->get_app_execcl_cats();
			$this->appresponse['jccats'] = $this->executive_m->get_app_jcard_cats();
			$this->get_jc_form_data($OId);
			$this->get_estimates($OId);
			$this->get_estimate_comments($OId);
			if($this->appresponse['to_be_paid'] < 0.01 && $this->appresponse['to_be_paid'] > -0.01) {
				$this->appresponse['to_be_paid'] = 0;
			}
			$temp_comments = $this->db->select('ServiceDesc1, ServiceDesc2')->from('oservicedetail')->where('OId', $OId)->limit(1)->get()->row();
			$this->appresponse['uo_comments'] = trim($temp_comments->ServiceDesc1 . ' ' . $temp_comments->ServiceDesc2);
			$this->appresponse['cs_comments'] = $this->get_cs_comments($OId);
			$this->appresponse['fb_questions'] = $this->get_fb_questions($OId);
			$this->appresponse['fb_remarks'] = $this->get_fb_remarks($OId);
			$this->appresponse['bikedetails'] = $this->odetails_m->get_bike_regnum_by_oid($OId);
			$this->appresponse['ex_fup_statuses'] = $this->executive_m->get_ex_fup_rtime_statuses();
			$this->appresponse['ex_rtime_updates'] = $this->executive_m->get_ex_fup_rtime_supdates($OId, 1);
			$this->appresponse['ex_pre_servicing_updates'] = $this->executive_m->get_ex_ps_updates($OId);
			$this->appresponse['ex_fup_updates'] = $this->executive_m->get_ex_fup_rtime_supdates($OId);
			$this->load->model('regnum_m');
			$this->appresponse['regnums'] = $this->regnum_m->get_all_regnumvals();
			$this->appresponse['assigned_execs'] = $this->get_execs_assigned($OId);
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function updt_fb_data() {
		if($_POST) {
			$oid = $this->input->post('oid');
			$tag = $this->db->select('Tag')->from('jobcarddetails')->where('OId', $oid)->get()->row();
			if($tag && $tag->Tag > 6) {
				$this->appresponse['status'] = 1;
			} else {
				$this->updateUserFeedback();
				$and_reg_ids = $this->get_all_active_admin_devices();
				if(count($and_reg_ids) > 0) {
					$and_push_msg_data = array("message" => "Admin taken feedback for OId: " . $oid, "tag" => "odetailwithjobcard", "oid" => $oid);
					$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
				}
				$this->updt_rtime_fup_status(10);
				$this->appresponse['status'] = 1;
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
				$updtData['UpdatedBy'] = $this->a_row->AdminName;
				$this->db->insert('oexchkupstatus', $updtData);
				$jobcarddetails['CPPhone'] = $this->input->post('CPPhone');
				$jobcarddetails['CPName'] = $this->input->post('CPName');
				$jobcarddetails['JcNum'] = $this->input->post('jcnum');
				$jobcarddetails['Tag'] = 4;
				$jobcarddetails['EstTaken'] = 1;
				$jckms = $this->input->post('kms');
				$jcnum = $this->input->post('jcnum');
				if($jckms && $jcnum) {
					$sql = 'INSERT INTO jobcarddetails (OId, JcKms, JcNum) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE JcKms = VALUES(JcKms), JcNum = VALUES(JcNum)';
					$query = $this->db->query($sql, array($oid, $jckms, $jcnum));
					$sms_flag = $this->updt_chkpdne_status($oid);
					if($sms_flag) {
						$uphone = $this->uphone_by_oid($oid);
						$esttime = date('d/m - h:i A', intval($this->input->post('esttime')));
						$this->$sms_flag($uphone, "Your bike's check up is done. The estimated service charge is INR " . $updtData['EstPrice'] . " (excluding convenience fees) and you can expect the delivery by " . $esttime);
					}
				}
				$this->updt_rtime_fup_status(6);
				$this->db->where('OId', $oid)->update('jobcarddetails', $jobcarddetails);
				$this->appresponse['status'] = 1;
			}
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
			$updtData['UpdatedBy'] = $this->a_row->AdminName;
			if($statusid == 2 || $updtData['EFupStatusId'] == 2) {
				$sql = 'INSERT INTO jobcarddetails (OId, Tag) VALUES (?, ?) ON DUPLICATE KEY UPDATE Tag = VALUES(Tag)';
				$query = $this->db->query($sql, array($oid, 2));
			}
			$this->db->insert('oexfupstatus', $updtData);
			if($statusid == 5 || $updtData['EFupStatusId'] == 5) {
				$idata['ULatitude'] = $this->input->post('lati');
				$idata['ULongitude'] = $this->input->post('longi');
				$this->db->where('OId', $oid)->update('odetails', $idata);
				$this->db->where('OId', $oid)->update('admin_notification_flags', array('new_pickup' => 1));
				$and_reg_ids = $this->get_all_active_admin_devices();
				if(count($and_reg_ids) > 0) {
					$and_push_msg_data = array("message" => "Bike picked from customer for OId: " . $oid, "tag" => "odetailwithjobcard", "oid" => $oid);
					$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
				}
				$uph = $this->uphone_by_oid($oid);
				$this->send_sms_request_to_api($uph, 'Your bike pick up is done and our executive(s) updated your jobcard - gear6.in');
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
				$this->db->where('OId', $oid)->update('jobcarddetails', $ijdata);
				$uph = $this->uphone_by_oid($oid);
				$this->send_sms_request_to_api($uph, 'Your bike was successfully delivered - gear6.in');
				$admin['new_bike_delivered'] = 1;
				$this->db->where('OId', $oid)->update('admin_notification_flags', $admin);
			}
			if($statusid == 10 || $updtData['EFupStatusId'] == 10) {
				$ijdata['Tag'] = 7;
				$this->db->where('OId', $oid)->update('jobcarddetails', $ijdata);
			}
			$this->db->where('odetails.OId', $oid)->update('odetails', array('LastExFupStatusId' => $updtData['EFupStatusId']));
			if(!$statusid) {
				$this->appresponse['status'] = 1;
			}
		}
		if(!$statusid) {
			echo json_encode($this->appresponse);
			exit;
		}
	}
	public function updt_billing_details() {
		$OId = $this->input->post('oid');
		if($_POST && $this->is_valid_oid($OId)) {
			$updtData['BillImgs'] = $this->bill_imgs_upload();
			$updtData['ScBillAmt'] = $this->input->post('bill_amt');
			$updtData['Tag'] = 6;
			$admin['new_bill_updated'] = 1; $admin['new_bill_updated_dismissed'] = 0;
			$this->db->where('OId', $OId)->update('admin_notification_flags', $admin);
			$and_reg_ids = $this->get_all_active_admin_devices();
			if(count($and_reg_ids) > 0) {
				$and_push_msg_data = array("message" => "Bill updated by executive for OId: " . $OId, "tag" => "odetailwithjobcard", "oid" => $OId);
				$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
			}
			$this->updt_rtime_fup_status(8);
			$this->updt_rtime_fup_status(7);
			$_POST['remarks'] = 'Billed Amount is Rs. ' . $this->input->post('bill_amt');
			$this->updt_rtime_fup_status(19);
			$this->db->where('OId', $OId)->update('jobcarddetails', $updtData);
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function create_leave() {
		if($_POST) {
			$leave = array("from_date" => $_POST["from_date"], "to_date" => $_POST["to_date"], "ExecId" => $_POST["ExecId"], "purpose" => $_POST["purpose"], "status" => $_POST["status"], "updatedBy" => $this->a_row->AdminName);
			if($this->db->insert('execleave', $leave)) {
				$this->appresponse['status'] = 1;
			}
		}
		echo json_encode($this->appresponse);
		exit;
	}
	public function get_executives() {
		$this->db->select('executive.ExecId, executive.ExecName, executive.Phone');
		$this->db->from('executive');
		$this->db->where('executive.isActive', 1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (!$result) {
			$this->appresponse['status'] = 0;
			$this->appresponse['executives'] = array();
		} else {
			$this->appresponse['status'] = 1;
			$this->appresponse['executives'] = $result;
		}
		echo json_encode($this->appresponse);
		exit;
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
			$this->appresponse['EstDate'] = NULL;
		} else {
			$this->appresponse['EstPrice'] = $result->EstPrice;
			$this->appresponse['EstTime'] = date('h:i A', strtotime($result->EstTime));
			$this->appresponse['EstDate'] = date('d M Y', strtotime($result->EstTime));
		}
	}
	private function updt_chkpdne_status($oid) {
		$odstatus = $this->db->select('odetails.ServiceId, oservicedetail.StatusId, oservicedetail.ScId, odetails.TieupId')->from('odetails')->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left')->where('odetails.OId', $oid)->limit(1)->get()->row_array();
		$changestatus = TRUE;
		if(intval($odstatus['ServiceId']) == 1 && intval($odstatus['StatusId']) == 1) {
			$statusid = 4;
		} elseif(intval($odstatus['ServiceId']) == 2 && intval($odstatus['StatusId']) == 8) {
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
			$data['ModifiedBy'] = $this->a_row->AdminName;
			$this->load->model('statushistory_m');
			$this->statushistory_m->save($data);
			$this->db->where('OId', $data['OId']);
			$this->db->limit(1);
			$this->db->update('oservicedetail', array('StatusId' => $data['StatusId']));
			if(intval($odstatus['TieupId']) == 1) {
				return 'send_sms_request_to_api';
			} elseif(intval($odstatus['TieupId']) == 2) {
				return 'send_hj_sms';
			}
		}
		return $changestatus;
	}
	private function get_execs_assigned($oid) {
		$this->db->select('executive.ExecId, executive.ExecName, executive.GCMId, executive.Phone, executive.Email, executive.DOB, DATE_FORMAT(CONVERT_TZ(execassigns.Timestamp,"+00:00","+05:30"), "%D %M %Y %h:%i %p") AS Timestamp');
		$this->db->from('execassigns');
		$this->db->join('executive', 'executive.ExecId = execassigns.ExecId');
		$this->db->where('execassigns.OId', $oid);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	private function uphone_by_oid($oid) {
		$rec = $this->db->select('user.Phone')->from('odetails')->join('user', 'user.UserId = odetails.UserId')->where('odetails.OId', $oid)->limit(1)->get()->row_array();
		return $rec['Phone'];
	}
	private function exphones_by_oid($oid) {
		$recs = $this->db->select('executive.Phone')->from('odetails')->join('execassigns', 'execassigns.OId = odetails.OId')->join('executive', 'executive.ExecId = execassigns.ExecId')->where('odetails.OId', $oid)->group_by('executive.ExecId')->get()->result_array();
		$execphs = array();
		foreach($recs as $rec) {
			$execphs[] = $rec['Phone'];
		}
		return $execphs;
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
			$this->appresponse['us_comments'] = explode('||', $result['UserComments']);
			if(isset($result['UserComments'])) {
				$this->appresponse['us_comments'] = explode('||', $result['UserComments']);
			} else {
				$this->appresponse['us_comments'] = array();
			}
			$this->appresponse['jc_kms'] = $result['JcKms'];
			$this->appresponse['jc_num'] = $result['JcNum'];
			$this->appresponse['cp_name'] = $result['CPName'];
			$this->appresponse['cp_phone'] = $result['CPPhone'];
			$this->appresponse['est_status'] = $result['EstTaken'];
			$this->appresponse['nchklists'] = $result['nChkLists'];
			$this->appresponse['tag'] = $result['Tag'];
			$this->appresponse['sc_bill_amt'] = $result['ScBillAmt'];
		}
	}
	private function get_cs_comments($oid) {
		$this->db->select('Remarks, Timestamp');
		$this->db->from('ofupstatus');
		$this->db->where('ofupstatus.OId', $oid);
		$this->db->where('ofupstatus.FupStatusId', 11);
		$this->db->order_by('ofupstatus.Timestamp', 'desc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
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
	private function is_valid_oid($OId) {
		$row = $this->db->select('COUNT(*) AS NoOfRows')->from('odetails')->where(array('OId' => $OId))->get()->row_array();
		if($row['NoOfRows'] == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	private function check_auth_token() {
		$this->appresponse['status'] = 0;
		$this->appresponse['a_is_logged_in'] = 0;
		$this->auth_token = $this->input->get_request_header('a_auth_token', TRUE);
		if($this->auth_token == '' || $this->auth_token == NULL) {
			$ph = (bool) $this->input->post('phone');
			$pwd = (bool) $this->input->post('password');
			if(!$ph || !$pwd) {
				echo json_encode($this->appresponse);
				exit;
			}
		} else {
			$aa = $this->admin_m->get_by(array('AuthToken' => $this->auth_token), TRUE);
			if($aa) {
				$this->appresponse['a_is_logged_in'] = 1;
				$this->a_row = $aa;
				$this->appresponse['a_row']['aid'] = $aa->AdminId;
				$this->appresponse['a_row']['aname'] = $aa->AdminName;
				$this->appresponse['a_row']['aphone'] = $aa->Phone;
				$this->appresponse['a_row']['aemail'] = $aa->Email;
			} else {
				echo json_encode($this->appresponse);
				exit;
			}
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
	public function is_breakdown_flag_update() {
		if($_POST) {
			$OId = $_POST['oid'];
			$this->load->model('odetails_m');
			$od_row = $this->odetails_m->get_by(array('OId' => $OId), TRUE);
			$old_transport_mode = $od_row->transportMode;
			$is_breakdown_flag = intval($this->input->post('isBreakdown'));
			if($is_breakdown_flag == 1) {
				$transport_mode = intval($this->input->post('transport_mode'));
			} else {
				$transport_mode = NULL;
			}
			if($old_transport_mode == 1 && $transport_mode != 1) {
				$execassigns = $this->get_execs_assigned($OId);
				if($execassigns && count($execassigns) > 0) {
					foreach ($execassigns as $ex) {
						$reward = array(); $reward['ExecId'] = $ex['ExecId']; $reward['Amount'] = 50;
						$reward["Type"] = 'Debit'; $reward['updated_at'] = date("Y-m-d", strtotime("now"));
						$reward["OId"] = $OId; $reward["isCleared"] = 0; $reward["ClearFrequency"] = 2;
						$reward['Description'] = "Your rewards wallet is debited by Rs. 50 for manual towing cancellation of gear6.in order " . $OId;
						$reward['UpdatedBy'] = $this->a_row->AdminName; $this->db->insert('execrewards', $reward);
						$this->send_sms_request_to_api($ex['Phone'], $reward['Description']);
						$and_push_msg_data = array("title" => "Towing Reward Debited", "message" => $reward['Description'], "screen" => "reward");
						$gcm = array(strval($ex['GCMId']));
						$this->send_gcm_request($gcm, $and_push_msg_data);
					}
				}
			} elseif($old_transport_mode != 1 && $transport_mode == 1) {
				$execassigns = $this->get_execs_assigned($OId);
				if($execassigns && count($execassigns) > 0) {
					foreach ($execassigns as $ex) {
						$reward = array(); $reward['ExecId'] = $ex['ExecId']; $reward['Amount'] = 50;
						$reward["Type"] = 'Credit'; $reward['updated_at'] = date("Y-m-d", strtotime("now"));
						$reward["OId"] = $OId; $reward["isCleared"] = 0; $reward["ClearFrequency"] = 2;
						$reward['Description'] = "Your rewards wallet is credited by Rs. 50 for manual towing of gear6.in order " . $OId;
						$reward['UpdatedBy'] = $this->a_row->AdminName; $this->db->insert('execrewards', $reward);
						$this->send_sms_request_to_api($ex['Phone'], $reward['Description']);
						$and_push_msg_data = array("title" => "Towing Reward Credited", "message" => $reward['Description'], "screen" => "reward");
						$gcm = array(strval($ex['GCMId']));
						$this->send_gcm_request($gcm, $and_push_msg_data);
					}
				}
			}
			$odetails['isBreakdown'] = $is_breakdown_flag;
			$odetails['transportMode'] = $transport_mode;
			$this->db->where('OId', $OId); $this->db->update('odetails', $odetails);
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse); exit;
	}
}