<?php
class Appdata extends G6_Appcontroller {
	private $query_row = NULL;
	private $user_row = NULL;
	public function __construct() {
		parent::__construct();
		$this->check_existing_query();
	}
	public static function sortArrayByObjectProperty($a, $b) {
		return $a['Distance'] > $b['Distance'];
	}
	public function get_cities() {
		$cities = (array) $this->city_m->get_by(array('isEnabled' => 1));
		foreach($cities as &$city) {
			$city->AdvTime /= 24;
		}
		$this->appresponse['cities'] = $cities;
		$this->appresponse['status'] = 1;
		$this->load->model('g6data_m'); $g6data_row = $this->g6data_m->get(1);
		$this->appresponse['versionCode'] = intval($g6data_row->UserAndAppVC);
		echo json_encode($this->appresponse);
	}
	public function city_select() {
		$this->appresponse['status'] = 0;
		if($_POST) {
			if($this->input->post('city')) {
				$this->appresponse['query_token'] = $this->appdata_m->set_city();
				$this->appresponse['query_data']['city'] = $this->city_m->get($this->input->post('city'));
				$this->updateDeviceId();
				$this->appresponse['status'] = 1;
			}
		}
		if (isset($this->appresponse['query_data']['city'])) {
			$this->load->model('service_m');
			$this->appresponse['services'] = $this->service_m->get_by('isAppEnabled = 1');
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function get_services() {
		$this->appresponse['status'] = 0;
		$this->load->model('g6data_m'); $g6data_row = $this->g6data_m->get(1);
		$this->appresponse['versionCode'] = intval($g6data_row->UserAndAppVC);
		$this->appresponse['iVersionCode'] = $g6data_row->UserIOSAppVC;
		$this->appresponse['offertext'] = "No Offers Available";
		if(isset($this->appresponse['query_data']['city'])) {
			$this->load->model('service_m');
			$this->appresponse['services'] = $this->service_m->get_by('isAppEnabled = 1');
			$this->appresponse['offers'] = array(array("offid" => 1, "offname" => "Flat Rs.100 Off", "offsd" => "Now make your bike and your wallet happy!", "offld" => "Use coupon code <b>GSIX100</b> to get Flat Rs.100 off on any service!"), array("offid" => 2, "offname" => "Referral Offer", "offsd" => "You get flat Rs. 75 off on your service", "offld" => "Now make your bike and your friend happy at the same time. Just refer us to your friends and family."));
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function get_bike_list() {
		$this->appresponse['status'] = 0;
		if($this->input->post('company') != "") {
			$this->load->model('bikemodel_m');
			$this->appresponse['bikemodels'] = $this->bikemodel_m->get_by('BikeCompanyId = ' . $this->input->post('company'));
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function choose_sc_slot() {
		$this->appresponse['status'] = 0;
		if($_POST) {
			$servicetype = intval($this->query_row->ServiceId);
			if($servicetype == 1 || $servicetype == 2) {
				$slot = $this->input->post('slot');
				$scid = intval($this->input->post('scid'));
				if($servicetype == 1) {
					$this->load->model('servicecenter_m');
					$buffered_slot = $this->servicecenter_m->send_slot_to_buffer($scid, $this->query_row->ODate, $slot);
					$ins_data = array('SlotBufferId' => $buffered_slot, 'ScId' => $scid, 'SlotHour' => $slot);
				} else {
					$ins_data = array('ScId' => $scid, 'SlotHour' => $slot);
				}
			} elseif ($servicetype == 3) {
				$date = $this->query_row->ODate;
				$scids = $this->input->post('scids');
				$qtype = $this->input->post('qtype');
				$ins_data = array('ScIds' => $scids, 'QType' => $qtype);
			}
			$ins_data['Phone'] = $ph = $this->input->post('phone');
			$this->db->where('QueryToken', $this->query_row->QueryToken)->update('appdata', $ins_data);
			if($this->appresponse['is_logged_in'] == 0 && !$this->validate_phone($ph)) {
				$this->appresponse['login_required'] = 1;
			} else {
				$this->appresponse['login_required'] = 0;
			}
			if($this->appresponse['is_logged_in'] == 0 && $this->validate_phone($ph)) {
				$this->appresponse['otp_required'] = 1;
			} else {
				$this->appresponse['otp_required'] = 0;
			}
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function get_prereview_details() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->is_scdata_set()) {
			if($this->appresponse['is_logged_in'] == 1) {
				$user_details = $this->user_m->get_user_addresses($this->user_row->UserId)[0];
				$bikenumber = $this->user_m->get_user_bike_num($this->user_row->UserId);
				$user_details['RegNum'] = $bikenumber['RegNum'];
				$user_details['BikeNumber'] = $bikenumber['BikeNumber'];
				$this->appresponse['user_details'] = $user_details;
			}
			$servicetype = intval($this->query_row->ServiceId);
			$scid = intval($this->query_row->ScId);
			if($servicetype == 1 || $servicetype == 2 || $servicetype == 4) {
				if($servicetype == 1) {
					$this->load->model('aservice_m');
					$this->appresponse['aservices'] = $this->aservice_m->get_aservices_for_order($this->query_row->BikeModelId, $scid, 0);
					$this->appresponse['maservices'] = $this->aservice_m->get_aservices_for_order($this->query_row->BikeModelId, $scid, 1);
				}
				if($servicetype == 4) {
					$this->load->model('insurer_m');
					$this->appresponse['insurers'] = $this->insurer_m->get();
				}
				$this->load->model('amenity_m');
				$this->appresponse['amenities'] = $this->amenity_m->get_amenities_by_service($scid, $servicetype);
			}
			$this->load->model('regnum_m');
			$regnums = $this->regnum_m->get_all_regnumvals();
			if(count($regnums) > 0) {
				$this->appresponse['regnums'] = $regnums;
			}
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function is_user_registered() {
		$this->appresponse['status'] = 1;
		$ph = $this->input->post('phone');
		if($_POST && $this->validate_phone($ph)) {
			$this->appresponse['status'] = 0;
		}
		echo json_encode($this->appresponse);
	}
	public function send_otp($signup = FALSE) {
		$this->appresponse['status'] = 0;
		if(!$signup) {
			$ph = $this->query_row->Phone;
			$ref_valid = TRUE;
		} else {
			$ph = $this->input->post('phone');
			$ref_code = $this->input->post('referral_coupon'); $ref_valid = FALSE;
			if($ref_code != NULL && $ref_code != "") {
				$referrer = $this->user_m->get_by(array('RefCode' => $ref_code), TRUE);
				if($referrer) { $ref_valid = TRUE; }
			} else { $ref_valid = TRUE; }
		}
		if($_POST && $ref_valid && $this->appresponse['is_logged_in'] == 0 && $this->validate_phone($ph)) {
			$this->load->model('otp_m');
			$temp = $this->otp_m->is_otp_inserted($ph);
			if($temp) {
				$otp_val = $temp;
			} else {
				$otp_val = $this->otp_m->insert_otp($ph);
			}
			$this->send_sms_request_to_api($ph, "Your OTP is " . $otp_val . " for your mobile confirmation at gear6.in");
			$this->appresponse['status'] = 1;
		} else {
			if($ref_valid) {
				$this->appresponse['errmsg'] = 'This phone number is already registered with us.';
			} else {
				$this->appresponse['errmsg'] = 'You entered an invalid referral code.';
			}
		}
		echo json_encode($this->appresponse);
	}
	public function send_forgot_otp () {
		$this->appresponse['status'] = 0;
		if($_POST) {
			$this->load->model('otp_m');
			if(!$this->user_m->is_unique_ph($this->input->post('phone'))) {
				$login_type = $this->user_m->get_login_type($this->input->post('phone'));
				if($login_type == 'Email') {
					$otp = $this->otp_m->is_otp_inserted($this->input->post('phone'));
					if(!$otp) {
						$otp = $this->otp_m->insert_otp($this->input->post('phone'));
					}
					$this->send_sms_request_to_api($this->input->post('phone'), "Your OTP is " . $otp . " for your mobile confirmation at gear6.in");
					$this->appresponse['status'] = 1;
				} else {
					$this->appresponse['errmsg'] = 'You have to login using ' . $login_type;
				}
			} else {
				$this->appresponse['errmsg'] = 'Sorry, this phone is not registered';
			}
		}
		echo json_encode($this->appresponse);
	}
	public function reset_forgot_password() {
		$this->appresponse['status'] = 0;
		if($_POST) {
			$this->load->model('otp_m');
			$otpcheck = $this->otp_m->check_otp($this->input->post('otp'), $this->input->post('phone'));
			if($otpcheck == 1) {
				if($this->input->post('pswd1') != "" && $this->input->post('pswd2') != "") {
					$this->user_m->reset_password($this->input->post('pswd1'), $this->input->post('pswd2'), $this->input->post('phone'));
					$this->appresponse['status'] = 1;
				}
			} else {
				$this->appresponse['errmsg'] = 'Invalid OTP Entered';
			}
		}
		echo json_encode($this->appresponse);
	}
	public function check_otp($signup = FALSE) {
		$this->appresponse['status'] = 0;
		if(!$signup) {
			$ph = $this->query_row->Phone;
		} else {
			$ph = $this->input->post('phone');
		}
		if($_POST) {
			$this->load->model('otp_m');
			$this->appresponse['status'] = $this->otp_m->check_otp($this->input->post('otp'), $ph);
		}
		echo json_encode($this->appresponse);
	}
	public function set_loc_service() {
		$this->appresponse['status'] = 0;
		if($_POST) {
			if($this->is_valid_query_locataion($this->input->post('latitude'), $this->input->post('longitude'))) {
				$query_data['Latitude'] = $this->input->post('latitude');
				$query_data['Longitude'] = $this->input->post('longitude');
				$query_data['LocationName'] = $this->input->post('area');
				$query_data['ServiceId'] = intval($this->input->post('servicetype'));
				if($query_data['ServiceId'] == 4) {
					$query_data['ScId'] = NULL;
					$query_data['ScIds'] = NULL;
				} elseif($query_data['ServiceId'] == 3) {
					$query_data['UserAddrId'] = NULL;
					$query_data['AddrLine1'] = NULL;
					$query_data['AddrLine2'] = NULL;
					$query_data['Landmark'] = NULL;
					$query_data['LocationId'] = NULL;
				}
				$this->db->where('QueryToken', $this->appresponse['query_token'])->update('appdata', $query_data);
				$this->load->model('service_m');
				$this->load->model('bikecompany_m');
				$this->appresponse['query_data']['service'] = $this->service_m->get(intval($query_data['ServiceId']));
				$this->appresponse['query_data']['area'] = $query_data['LocationName'];
				$this->appresponse['bikecompanies'] = $this->bikecompany_m->get_by('isEnabled = 1');
				$this->appresponse['holidays'] = $this->get_holidays();
				$query_row = $this->query_row;
				$this->appresponse['adv_time'] = intval($this->city_m->get(intval($query_row->CityId))->AdvTime) / 24;
				$curr_hour = intval(date("H", time()));
				if($curr_hour >= 18) {
					$this->appresponse['adv_time'] += 1;
				}
				$this->appresponse['status'] = 1;
			} else {
				$this->appresponse['err_msg'] = 'Our services are not yet availble in your area.';
			}
		}
		echo json_encode($this->appresponse);
	}
	public function set_query_data() {
		$this->appresponse['status'] = 0;
		if($_POST) {
			$this->set_q_data();
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function getEmgDetails() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->query_row) {
			$this->load->model('servicecenter_m');
			$this->load->model('odetails_m');
			$sc_id = intval($this->input->post('scid'));
			$query_row = $this->query_row;
			$sc_array = $this->servicecenter_m->sc_details_for_slot($sc_id);
			$this->appresponse['amenities'] = $this->parse_amenities($this->servicecenter_m->amenities_by_id($sc_id));
			$this->appresponse['scdata'] = $this->servicecenter_m->get_sc_details($sc_id);
			$this->appresponse['offers'] = $this->servicecenter_m->get_offers_for_users($sc_id);
			$this->appresponse['exclusives'] = $this->servicecenter_m->get_excls_for_users($sc_id);
			$this->appresponse['lat'] = $sc_array['Latitude'];
			$this->appresponse['lon'] = $sc_array['Longitude'];
			$this->appresponse['distance'] = round($this->distance($query_row->Latitude, $query_row->Longitude, $sc_array['Latitude'], $sc_array['Longitude'], "K"), 2);
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function getPbDetails() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->query_row) {
			$this->load->model('petrolbunks_m');
			$pb_id = intval($this->input->post('pbid'));
			$query_row = $this->query_row;
			$pb_row = $this->petrolbunks_m->get_pbec_by_id($pb_id);
			unset($pb_row['Pwd']);
			unset($pb_row['Salt']);
			unset($pb_row['PwdCheck']);
			$this->appresponse['pbdetails'] = $pb_row;
			$this->appresponse['amenities'] = $this->petrolbunks_m->get_app_amenities_for_pb($pb_id);
			$this->appresponse['timings'] = $this->petrolbunks_m->get_app_pbec_timings($pb_id);
			$this->appresponse['lat'] = $pb_row['Latitude'];
			$this->appresponse['lon'] = $pb_row['Longitude'];
			$this->appresponse['distance'] = round($this->distance($query_row->Latitude, $query_row->Longitude, $pb_row['Latitude'], $pb_row['Longitude'], "K"), 2);
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function getPtDetails() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->query_row) {
			$ptsc_id = intval($this->input->post('sc_id'));
			$scptflag = intval($this->input->post('scptflag'));
			if($scptflag == 0) {
				$mod_type = 'punctures_m';
			} elseif($scptflag == 1) {
				$mod_type = 'servicecenter_m';
			}
			$query_row = $this->query_row;
			$this->load->model($mod_type);
			$ptsc_row = $this->$mod_type->get_ptsc_by_id($ptsc_id);
			unset($ptsc_row['Pwd']);
			unset($ptsc_row['Salt']);
			unset($ptsc_row['PwdCheck']);
			$this->appresponse['ptdetails'] = $ptsc_row;
			if($scptflag == 0) {
				$this->appresponse['ptprice'] = 'Minimum Exptected Price: INR ' . $ptsc_row['Price'];
				$this->appresponse['timings'] = $this->$mod_type->get_app_ptsc_timings($ptsc_id);
			} else {
				$this->appresponse['ptprice'] = NULL;
				$this->appresponse['timings'] = NULL;
			}
			$this->appresponse['lat'] = $ptsc_row['Latitude'];
			$this->appresponse['lon'] = $ptsc_row['Longitude'];
			$this->appresponse['distance'] = round($this->distance($query_row->Latitude, $query_row->Longitude, $ptsc_row['Latitude'], $ptsc_row['Longitude'], "K"), 2);
			$this->appresponse['status'] = 1;
			echo json_encode($this->appresponse);
		}
	}
	public function get_sc_data() {
		$dist = floatval($this->input->post('dist'));
		$this->appresponse['status'] = 0;
		if($this->input->post('date_') != "") {
			$this->db->where(array('QueryToken' => $this->input->post('query_token')))->update('appdata', array('ODate' => date('Y-m-d', strtotime($this->input->post('date_')))));
			$this->appresponse['query_data']['odate'] = date('d F, Y - l', strtotime($this->input->post('date_')));
		}
		if($this->is_query_set() && $dist > 0) {
			$this->query_row = $query_row = $this->appdata_m->get_by(array('QueryToken' => $this->input->post('query_token')), TRUE);
			$this->appresponse['serid'] = $serid = intval($query_row->ServiceId);
			$this->load->model('location_m');
			$scs = array(array());
			if($serid == 9) {
				$this->load->model('petrolbunks_m');
				$sc_locations = $this->petrolbunks_m->get_pb_locations($query_row->CityId);
			} elseif ($serid == 10) {
				$this->load->model('petrolbunks_m');
				$this->load->model('pucs_m');
				$sc_locations = $this->pucs_m->get_ec_locations($query_row->CityId);
				$pb_with_ec_amenities = $this->petrolbunks_m->get_pbwithec_locations($query_row->CityId);
				if(isset($pb_with_ec_amenities)) {
					foreach($pb_with_ec_amenities as &$pb) {
						$pb['pb_with_ec_flag'] = 1;
					}
					$sc_locations = array_merge($sc_locations, $pb_with_ec_amenities);
				}
			} elseif($serid == 11) {
				$this->load->model('scaddr_m');
				$this->load->model('punctures_m');
				$sc_locations = $this->punctures_m->get_pt_locations($query_row->CityId);
				$pb_locations = $this->scaddr_m->app_location_rows(TRUE, $query_row->CityId, $query_row->BikeCompanyId, $query_row->BikeModelId, $query_row->ServiceId);
				if(isset($pb_locations)) {
					foreach($pb_locations as &$sc) {
						$sc['pt_with_sc_flag'] = 1;
					}
					if(empty($sc_locations)) {
						$sc_locations = array();
					}
					$sc_locations = array_merge($sc_locations, $pb_locations);
				}
			} else {
				$this->load->model('scaddr_m');
				$sc_locations = $this->scaddr_m->app_location_rows(FALSE, $query_row->CityId, $query_row->BikeCompanyId, $query_row->BikeModelId, $query_row->ServiceId);
			}
			$scs_count = 0;
			if (count($sc_locations) > 0) {
				foreach ($sc_locations as $dest) {
					if($this->distance($query_row->Latitude, $query_row->Longitude, $dest['Latitude'], $dest['Longitude'], "M") <= $dist) {
						$scs[$scs_count]['lcid'] = $dest['LocationId'];
						$scs[$scs_count]['scid'] = $dest['ScId'];
						$scs[$scs_count]['dist'] = $this->distance($query_row->Latitude, $query_row->Longitude, $dest['Latitude'], $dest['Longitude'], "K");
						if(isset($dest['pb_with_ec_flag']) && $dest['pb_with_ec_flag'] == 1) {
							$scs[$scs_count]['pbecflag'] = $dest['pb_with_ec_flag'];
						}
						if(isset($dest['pt_with_sc_flag']) && $dest['pt_with_sc_flag'] == 1) {
							$scs[$scs_count]['ScPtFlag'] = $dest['pt_with_sc_flag'];
						}
						if($serid == 3) {
							$scs[$scs_count]['Latitude'] = $dest['Latitude'];
							$scs[$scs_count]['Longitude'] = $dest['Longitude'];
						}
						$scs_count += 1;
					}
				}
				if($scs_count > 0) {
					$scdata = $this->fetch_sc_data($scs);
					usort($scdata, array($this, "sortArrayByObjectProperty"));
					$this->appresponse['scdata'] = $scdata;
					$this->appresponse['adv_time'] = intval($this->appresponse['query_data']['city']->AdvTime) / 24;
					$curr_hour = intval(date("H", time()));
					if($curr_hour >= 18) {
						$this->appresponse['adv_time'] += 1;
					}
					$this->appresponse['status'] = 1;
				} else {
					$this->appresponse['err_msg'] = 'No Service Centers available for your query. Modify your query and try again.';
				}
			} else {
				$this->appresponse['err_msg'] = 'No Service Centers available for your query. Modify your query and try again.';
			}
		}
		echo json_encode($this->appresponse);
	}
	public function get_scdnslots() {
		$this->appresponse['status'] = 0;
		$query_row = $this->query_row;
		if($_POST && $query_row) {
			$this->load->model('servicecenter_m');
			$sc_id = intval($this->input->post('sc_id'));
			$date = $query_row->ODate;
			$sc_array = $this->servicecenter_m->sc_details_for_slot($sc_id);
			$sc_array['ScId'] = $sc_id;
			$serid = intval($query_row->ServiceId);
			if ($serid == 4 || $serid == 2) {
				$slots = $this->servicecenter_m->get_slots($sc_array['SlotDuration'], $sc_array['StartHour'], $sc_array['EndHour']);
			} else {
				$slots = $this->servicecenter_m->set_get_slots($sc_id, $date, $sc_array['DefaultSlots'], $sc_array['SlotDuration'], intval($sc_array['SlotType']), $sc_array['StartHour'], $sc_array['EndHour']);
			}
			$this->appresponse['slot_data'] = $this->fetch_slots($slots);
			$this->appresponse['amenities'] = $this->parse_amenities($this->servicecenter_m->amenities_by_id($sc_id));
			$this->appresponse['scdata'] = $this->servicecenter_m->get_sc_details($sc_id);
			$this->appresponse['offers'] = $this->servicecenter_m->get_offers_for_users($sc_id);
			$this->appresponse['exclusives'] = $this->servicecenter_m->get_excls_for_users($sc_id);
			$this->appresponse['lat'] = $sc_array['Latitude'];
			$this->appresponse['lon'] = $sc_array['Longitude'];
			$this->appresponse['distance'] = round($this->distance($query_row->Latitude, $query_row->Longitude, $sc_array['Latitude'], $sc_array['Longitude'], "K"), 2);
			$this->appresponse['price'] = round(floatval($this->servicecenter_m->price_by_id($sc_id, $query_row->BikeModelId, $query_row->ServiceId)), 2);
			$this->appresponse['query_data']['servicecenter'] = array();
			$this->appresponse['query_data']['servicecenter'][] = $this->servicecenter_m->get(intval($sc_id));
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function set_usernveh_info() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->query_row) {
			$ubdata['RegNum'] = $this->input->post('regnum');
			$ubdata['BikeNumber'] = $this->input->post('bnum');
			$ubdata['UserComments'] = $this->input->post('comments');
			$ubdata['ImgData'] = $this->temp_img_upload();
			if(intval($this->query_row->ServiceId) != 3) {
				$ubdata['Amenities'] = $this->input->post('amenities');
			}
			if(intval($this->query_row->ServiceId) == 1) {
				$ubdata['Aservices'] = $this->input->post('aservices');
				$ubdata['isAvail'] = intval($this->input->post('isAvail'));
				if($ubdata['isAvail'] == 1) {
					$this->load->model('servicecenter_m');
					$discount_amount = floatval($this->servicecenter_m->price_by_id($this->query_row->ScId, $this->query_row->BikeModelId, $this->query_row->ServiceId));
					$fservice_discount = $discount_amount + round(($discount_amount * 0.15), 2);
					$ubdata['FSDValue'] = $fservice_discount;
				} else {
					$ubdata['FSDValue'] = 0;
				}
			}
			if(intval($this->query_row->ServiceId) == 1 || intval($this->query_row->ServiceId) == 2) {
				$ubdata['isBreakdown'] = intval($this->input->post('isBreakdown'));
			}
			if(intval($this->query_row->ServiceId) == 4) {
				$ubdata['RegYear'] = $this->input->post('regYear');
				$ubdata['ExpiryDays'] = $this->input->post('expDate');
				$ubdata['PreviousInsurer'] = $this->input->post('prevIns');
				$ubdata['IsClaimedBefore'] = $this->input->post('isClaimed');
				$ubdata['SlotHour'] = $this->input->post('slot');
			} else {
				$this->insert_additional_locations();
			}
			if($this->appresponse['is_logged_in'] == 0) {
				$ubdata['UserName'] = $this->input->post('username');
				$ubdata['Email'] = $this->input->post('email');
			}
			$this->db->where('QueryToken', $this->query_row->QueryToken)->update('appdata', $ubdata);
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function get_revpage_info() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->query_row) {
			$this->appresponse['servicedate'] = date('F d, Y', strtotime($this->query_row->ODate));
			if (intval($this->query_row->ServiceId) != 3) {
				$slothour = floatval($this->query_row->SlotHour);
				if ($slothour > 12) {
					$temp_hr = intval($slothour - 12);
					$temp = (intval($slothour * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$this->appresponse['serviceslot'] = $temp_hr . ":" . $temp . " PM";
				} elseif ($slothour == 12) {
					$this->appresponse['serviceslot'] = intval($slothour) . ":00 PM";
				} else {
					$temp = (intval($slothour * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$this->appresponse['serviceslot'] = intval($slothour) . ":" . $temp . " AM";
				}
				$prices = $this->calculate_prices();
				if(intval($this->query_row->ServiceId) == 1 && intval($this->query_row->isAvail) == 1) {
					$this->appresponse['fservice_discount'] = $this->query_row->FSDValue;
				} else {
					$this->appresponse['fservice_discount'] = 0;
				}
				if($prices && count($prices) > 0) {
					foreach($prices as $price) {
						if(isset($price['ptotal'])) {
							$this->appresponse['total_price'] = $price['ptotal'];
							$this->appresponse['total_price'] -= $this->appresponse['fservice_discount'];
						} else {
							$this->appresponse['pricedetails'][] = $price;
						}
					}
				} else {
					$this->appresponse['pricedetails'] = NULL;
					$this->appresponse['total_price'] = 0;
				}
			}
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function get_all_user_addresses() {
		$this->appresponse['status'] = 0;
		if($this->appresponse['is_logged_in'] == 1) {
			$this->appresponse['addresses'] = $this->user_m->get_user_addresses(intval($this->user_row->UserId));
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function update_user_address() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->appresponse['is_logged_in'] == 1) {
			$data = array('isDefault' => 0);
			$this->db->where('UserId', intval($this->user_row->UserId));
			$this->db->where('isDefault', 1);
			$this->db->update('useraddr', $data);
			if($this->input->post('addr') != "" || intval($this->input->post('addr')) != 0) {
				$user_addr_id = intval($this->input->post('addr'));
				$data = array('isDefault' => 1);
				$this->db->where('UserAddrId', $user_addr_id);
				$this->db->update('useraddr', $data);
			} else {
				$user_addr_id = $this->user_m->updt_useraddr(intval($this->user_row->UserId), 1);
			}
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function get_user_addresses() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->query_row) {
			if($this->appresponse['is_logged_in'] == 1) {
				$this->appresponse['addresses'] = $this->user_m->get_user_address_by_location(intval($this->user_row->UserId), intval($this->query_row->CityId));
			} else {
				$this->appresponse['addresses'] = NULL;
			}
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function get_address_locations() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->query_row) {
			$this->load->model('location_m');
			$areas = $this->location_m->locations_for_sc($this->query_row->CityId);
			if (count($areas) > 0) {
				$this->appresponse['areas'] = $areas;
			}
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function emgptotp() {
		$this->appresponse['status'] = 0;
		if($_POST) {
			$this->load->model('otp_m');
			$otp = $this->otp_m->is_otp_inserted($this->input->post('phone'));
			if(!$otp) {
				$otp = $this->otp_m->insert_otp($this->input->post('phone'));
			}
			$this->send_sms_request_to_api($this->input->post('phone'), "Your OTP is " . $otp . " for your mobile confirmation at gear6.in");
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function placeEmgReq() {
		$this->appresponse['status'] = 0;
		if($_POST) {
			$otp = $this->input->post('accotp');
			if($this->query_row) {
				$emgdata['CityId'] = intval($this->query_row->CityId);
				$emgdata['LocationName'] = $this->query_row->LocationName;
				$emgdata['Latitude'] = $this->query_row->Latitude;
				$emgdata['Longitude'] = $this->query_row->Longitude;
				$emgdata['ODate'] = $this->query_row->ODate;
				$emgdata['BikeModelId'] = $this->query_row->BikeModelId;
				$emgdata['BikeCompanyId'] = $this->query_row->BikeCompanyId;
			}
			$emgdata['ServiceId'] = intval($this->input->post('servicetype'));
			$emgdata['Phone'] = $this->input->post('accphone');
			$emgdata['Email'] = $this->input->post('accemail');
			$emgdata['Description'] = $this->input->post('acctext');
			if(isset($otp) && $otp > 0) {
				$this->load->model('otp_m');
				$otpcheck = $this->otp_m->check_otp($otp, $this->input->post('accphone'));
				if($otpcheck == 1) {
					$success = TRUE;
				} else {
					$success = FALSE;
					$this->appresponse['status'] = 0;
				}
			} else {
				$success = TRUE;
			}
			if($success == TRUE) {
				$this->db->insert('emgorders', $emgdata);
				$id = $this->db->insert_id();
				$adminNotifyFlag['new_emergency_order'] = 1;
				$adminNotifyFlag['OId'] = 'emg_' . $id;
				$adminNotifyFlag['Phone'] = $this->input->post('accphone');
				$adminNotifyFlag['ODate'] = date('Y-m-d', strtotime("now"));
				$this->db->insert('admin_notification_flags', $adminNotifyFlag);
				$this->send_sms_request_to_api('8888083841', 'Emergency / Accidental Service Request Received From +91' . $emgdata['Phone'] . '. Message: ' . $emgdata['Description']);
				$this->send_sms_request_to_api('9494845111', 'Emergency / Accidental Service Request Received From +91' . $emgdata['Phone'] . '. Message: ' . $emgdata['Description']);
				$this->appresponse['status'] = 1;
				$this->clear_query_token();
			}
		}
		echo json_encode($this->appresponse);
	}
	public function placePtReq() {
		$this->appresponse['status'] = 0;
		if($_POST) {
			$otp = $this->input->post('ptotp');
			if($this->query_row) {
				$ptdata['CityId'] = intval($this->query_row->CityId);
				$ptdata['LocationName'] = $this->query_row->LocationName;
				$ptdata['Latitude'] = $this->query_row->Latitude;
				$ptdata['Longitude'] = $this->query_row->Longitude;
				$ptdata['ODate'] = $this->query_row->ODate;
				$ptdata['BikeModelId'] = $this->query_row->BikeModelId;
				$ptdata['BikeCompanyId'] = $this->query_row->BikeCompanyId;
			}
			$ptdata['ServiceId'] = intval($this->input->post('servicetype'));
			$ptdata['Phone'] = $this->input->post('ptphone');
			$ptdata['Email'] = $this->input->post('ptemail');
			$ptdata['Description'] = $this->input->post('pttext');
			$ptdata['TyreType'] = $this->input->post('pttype');
			$ptdata['PTyre'] = $this->input->post('pttyre');
			if(isset($otp) && $otp > 0) {
				$this->load->model('otp_m');
				$otpcheck = $this->otp_m->check_otp($otp, $this->input->post('ptphone'));
				if($otpcheck == 1) {
					$success = TRUE;
				} else {
					$success = FALSE;
					$this->appresponse['status'] = 0;
				}
			} else {
				$success = TRUE;
			}
			if($success == TRUE) {
				$this->db->insert('ptorders', $ptdata);
				$id = $this->db->insert_id();
				$adminNotifyFlag['new_puncture_order'] = 1;
				$adminNotifyFlag['OId'] = 'pt_' . $id;
				$adminNotifyFlag['Phone'] = $this->input->post('ptphone');
				$adminNotifyFlag['ODate'] = date('Y-m-d', strtotime("now"));
				$this->db->insert('admin_notification_flags', $adminNotifyFlag);
				$this->send_sms_request_to_api('8888083841', 'Emergency / Puncture Repair Service Request Received From +91' . $ptdata['Phone'] . '. Message: ' . $ptdata['Description']);
				$this->send_sms_request_to_api('9494845111', 'Emergency / Puncture Repair Request Received From +91' . $ptdata['Phone'] . '. Message: ' . $ptdata['Description']);
				$this->appresponse['status'] = 1;
			}
		}
		echo json_encode($this->appresponse);
	}
	public function select_user_address() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->query_row) {
			$useraddrid = $this->input->post('useraddrid');
			if($useraddrid) {
				$qudata['UserAddrId'] = intval($this->input->post('useraddrid'));
			} else {
				$this->load->model('location_m');
				$qudata['UserAddrId'] = NULL;
				$qudata['AddrLine1'] = $this->input->post('addrln1');
				$qudata['AddrLine2'] = $this->input->post('addrln2');
				$qudata['Landmark'] = $this->input->post('landmark');
				$qudata['LocationId'] = $this->location_m->location_id_by_name($this->input->post('area'))['LocationId'];
			}
			$this->db->where('QueryToken', $this->query_row->QueryToken)->update('appdata', $qudata);
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function get_ckout_data() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->query_row) {
			$prices = $this->calculate_prices();
			if(intval($this->query_row->ServiceId) == 1 && intval($this->query_row->isAvail) == 1) {
				$this->appresponse['fservice_discount'] = $this->query_row->FSDValue;
			} else {
				$this->appresponse['fservice_discount'] = 0;
			}
			if($prices && count($prices) > 0) {
				foreach($prices as $price) {
					if(isset($price['ptotal'])) {
						$this->appresponse['total_price'] = $price['ptotal'];
						$this->appresponse['total_price'] -= $this->appresponse['fservice_discount'];
					} else {
						$this->appresponse['pricedetails'][] = $price;
					}
				}
			} else {
				$this->appresponse['pricedetails'] = NULL;
				$this->appresponse['total_price'] = 0;
			}
			$this->db->where('QueryToken', $this->query_row->QueryToken)->update('appdata', array('ToPay' => $this->appresponse['total_price']));
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function check_coupon() {
		$this->load->model('coupons_m');
		$this->appresponse['status'] = 0;
		$this->appresponse['emsg'] = NULL;
		if($_POST && $this->query_row) {
			if($this->input->post('ccode') !== NULL && $this->input->post('ccode') != "") {
				$crow = $this->coupons_m->get_coupon_row($this->input->post('ccode'));
				if(isset($crow)) {
					$this->analyse_ccode($crow);
				} else {
					$this->appresponse['emsg'] = 'Invalid offer coupon';
				}
			} elseif($this->input->post('fccode') !== NULL && $this->input->post('fccode') != "") {
				$fcrow = $this->coupons_m->get_fcoupon_row($this->input->post('fccode'));
				if(isset($fcrow) && !empty($fcrow)) {
					$this->analyse_fccode($fcrow);
				} else {
					$this->appresponse['emsg'] = 'Invalid referral coupon / gift card';
				}
			}
			echo json_encode($this->appresponse);
		}
	}
	public function remove_fcoupon() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->query_row) {
			$this->calculate_remove_prices('f');
			$cdata['FCouponId'] = $this->query_row->FCouponId;
			$cdata['FDValue'] = $this->query_row->FDValue;
			$cdata['ToPay'] = $this->query_row->ToPay;
			$this->db->where('QueryToken', $this->query_row->QueryToken)->update('appdata', $cdata);
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function remove_coupon() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->query_row) {
			$this->calculate_remove_prices('c');
			$cdata['CouponId'] = $this->query_row->CouponId;
			$cdata['CDValue'] = $this->query_row->CDValue;
			$cdata['ToPay'] = $this->query_row->ToPay;
			$this->db->where('QueryToken', $this->query_row->QueryToken)->update('appdata', $cdata);
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function place_order() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->query_row) {
			$this->load->model('odetails_m');
			$this->load->model('amenity_m');
			$slot_test = TRUE;
			$serid = intval($this->query_row->ServiceId);
			if ($serid == 1) {
				$slot_test = $this->expire_buffered_slot();
			}
			if($slot_test) {
				if ($this->appresponse['is_logged_in'] == 0) {
					$_POST['full_name'] = $this->query_row->UserName;
					$_POST['phone'] = $this->query_row->Phone;
					$_POST['email'] = $this->query_row->Email;
					$usr = $this->user_m->create_user();
					$usr_id = $usr['UserId'];
					$_POST['adln1'] = $this->query_row->AddrLine1;
					$_POST['adln2'] = $this->query_row->AddrLine2;
					$_POST['landmark'] = $this->query_row->Landmark;
					if ($serid != 3) {
						$user_addr_id = $this->user_m->updt_useraddr($usr_id, 1, $this->query_row->LocationId);
					} else {
						$user_addr_id = NULL;
					}
					$this->send_sms_request_to_api($usr['Phone'], 'You are successfully registered at gear6.in. Below are your login details. Login Id: ' . $usr['Phone'] . ' Password: ' . $usr['Pwd']);
				} else {
					$usr_id = $this->user_row->UserId;
					$usr['Phone'] = $this->user_row->Phone;
					if ($this->query_row->UserAddrId) {
						$user_addr_id = intval($this->query_row->UserAddrId);
					} else {
						$_POST['adln1'] = $this->query_row->AddrLine1;
						$_POST['adln2'] = $this->query_row->AddrLine2;
						$_POST['landmark'] = $this->query_row->Landmark;
						if ($serid != 3) {
							$user_addr_id = $this->user_m->updt_useraddr($usr_id, 0, $this->query_row->LocationId);
						} else {
							$user_addr_id = NULL;
						}
					}
				}
				$amtys = '';
				$OId = $this->odetails_m->app_create_order($user_addr_id, $usr_id, $this->query_row);
				if ($serid != 3) {
					$_POST['amtys'] = $this->query_row->Amenities;
					$amtys = $this->odetails_m->insert_amenities($OId);
					if($serid == 1) {
						$_POST['asers'] = $this->query_row->Aservices;
						$this->odetails_m->insert_asers($OId);
					}
					$price = $this->amenity_m->insert_app_price_split($OId, $this->appresponse['query_data']['service']->ServiceName, $this->query_row);
					if($price < 0.01) {
						$price = 0;
					}
				} else {
					$price = 0;
				}
				if ($serid == 4) {
					$_POST['regYear'] = $this->query_row->RegYear;
					$_POST['expDate'] = $this->query_row->ExpiryDays;
					$_POST['prevIns'] = $this->query_row->PreviousInsurer;
					$_POST['isClaimed'] = $this->query_row->IsClaimedBefore;
					$this->odetails_m->insert_insurance($OId);
				}
				if(($serid == 2 || $serid == 3 || $serid == 1) && $this->query_row->ImgData != "") {
					$this->finalize_image_upload($this->query_row->ImgData, $OId);
				}
				$this->odetails_m->app_insert_oservicedetail($OId, $amtys, $price, $this->query_row);
				if($price >= 0.01 && $this->input->post('paymt') != "" && $this->input->post('paymt') != 'COD') {
					$this->appresponse['oid'] = $OId;
					$this->appresponse['amount'] = $price;
					$this->appresponse['status'] = 1;
					if($this->appresponse['is_logged_in'] == 0) {
						$this->appresponse['UserName'] = $this->query_row->UserName;
						$this->appresponse['Email'] = $this->query_row->Email;
						$this->appresponse['Phone'] = $this->query_row->Phone;
					}
				} else {
					$this->appresponse['oid'] = $OId;
					$this->appresponse['status'] = 1;
				}
				$this->clear_query_token();
			} else {
				$this->appresponse['errmsg'] = 'There are no slots available at this hour. Choose a different timeslot.';
			}
		}
		echo json_encode($this->appresponse);
	}
	public function cod_order_status() {
		$this->appresponse['status'] = 0;
		$OId = $this->input->post('oid');
		if($_POST && $OId) {
			$this->appresponse['OId'] = $OId;
			$this->get_order_details($OId);
			$this->appresponse['smsg'] = 'Your COD order '. $OId . ' was successfully placed';
			$this->appresponse['error'] = NULL;
			$this->send_gear6_email($this->appresponse['uemail'], 'Your Order '. $OId . ' was successfully placed', 'osuccess', $this->appresponse);
			if($this->appresponse['serid'] == 3) {
				$this->send_sms_request_to_api($this->appresponse['phone'], 'Your service request is successfully placed as Order ID : ' . $OId . ' for '. convert_to_camel_case($this->appresponse['stype']) . ' on ' . convert_to_camel_case($this->appresponse['bikemodel']) . '. Please wait for the Service Provider’s approval. Login to gear6.in to track your service progress.');
			} else {
				$this->send_sms_request_to_api($this->appresponse['phone'], 'Your service request is successfully placed as Order ID : ' . $OId . ' with ' . convert_to_camel_case($this->appresponse['scenter'][0]['ScName']) . ' for '. convert_to_camel_case($this->appresponse['stype']) . ' to ' . convert_to_camel_case($this->appresponse['bikemodel']) . ' on ' . $this->appresponse['timeslot'] . '. Please wait for the Service Provider’s approval. Login to gear6.in to track your service progress.');
			}
			$this->send_sms_request_to_api('9494845111', 'New order ' . $OId . ' placed by User: ' . $this->appresponse['uname'] . ', Phone ' . $this->appresponse['phone'] . ', Service Type: ' . convert_to_camel_case($this->appresponse['stype']) . ', Bike Model: ' . convert_to_camel_case($this->appresponse['bikemodel']) . ', Reg No: ' . $this->appresponse['bikenumber']);
			$this->send_sms_request_to_api('8888083841', 'New order ' . $OId . ' placed by User: ' . $this->appresponse['uname'] . ', Phone ' . $this->appresponse['phone'] . ', Service Type: ' . convert_to_camel_case($this->appresponse['stype']) . ', Bike Model: ' . convert_to_camel_case($this->appresponse['bikemodel']) . ', Reg No: ' . $this->appresponse['bikenumber']);
			$this->send_vendor_smses();
			$this->appresponse['status'] = 1;
			$this->appresponse['serid'] = strval($this->appresponse['serid']);
			if($this->appresponse['is_logged_in'] == 0) {
				$this->appresponse['login_result'] = $this->user_m->app_login($this->appresponse['phone'], $this->appresponse['pwd']);
				$this->appresponse['is_logged_in'] = 1;
				$this->user_row = $this->user_m->get_by(array('AuthToken' => $this->appresponse['login_result']['auth_token']), TRUE);
				$this->appresponse['userdetails']['UserId'] = $this->user_row->UserId;
				$this->appresponse['userdetails']['UserName'] = $this->user_row->UserName;
				$this->appresponse['userdetails']['Phone'] = $this->user_row->Phone;
				$this->appresponse['userdetails']['Email'] = $this->user_row->Email;
				$this->appresponse['userdetails']['DOB'] = $this->user_row->DOB;
				$this->appresponse['userdetails']['Gender'] = $this->user_row->Gender;
				$this->appresponse['userdetails']['RefCode'] = $this->user_row->RefCode;
			}
			unset($this->appresponse['pwd']);
			unset($this->appresponse['timeslot']);
			unset($this->appresponse['scaddress']);
			unset($this->appresponse['uaddress']);
		}
		echo json_encode($this->appresponse);
	}
	public function rp_order_status() {
		$this->appresponse['status'] = 0;
		if($_POST && $_POST['paymtid']) {
			$paymt_id = $this->input->post('paymtid');
			$OId = $this->input->post('oid');
			$amount_paid = intval($this->input->post('amount'));
			$get_paymt_status = $this->send_rzpay_api_request("https://api.razorpay.com/v1/payments/" . $paymt_id);
			$get_paymt_capture_status = $this->send_rzpay_api_request("https://api.razorpay.com/v1/payments/" . $paymt_id . "/capture", array('amount' => $amount_paid));
			$this->appresponse['OId'] = $OId;
			$this->get_order_details($OId);
			$usr_id = intval($this->user_m->get_by(array('Phone' => $this->appresponse['phone']), TRUE)->UserId);
			$paymt_row['UserId'] = $usr_id;
			$paymt_row['OId'] = $OId;
			$paymt_row['PaymtAmt'] = round($amount_paid / 100, 2);
			$paymt_row['PaymtResponse'] = serialize($get_paymt_capture_status);
			$paymt_row['PaymtId'] = 6;
			if(isset($get_paymt_status['status']) && $get_paymt_status['status'] == 'authorized') {
				$paymt_row['PaymtStatusId'] = 3;
				$check = TRUE;
			} else {
				$check = FALSE;
				$paymt_row['PaymtStatusId'] = 1;
			}
			$this->load->model('opaymtdetail_m');
			$this->appresponse['txnid'] = $this->opaymtdetail_m->create_custom_trxn($paymt_row);
			$this->send_gear6_email($this->appresponse['uemail'], 'Your Order '. $OId . ' was successfully placed', 'osuccess', $this->appresponse);
			if($this->appresponse['serid'] == 3) {
				$this->send_sms_request_to_api($this->appresponse['phone'], 'Your service request is successfully placed as Order ID : ' . $OId . ' for '. convert_to_camel_case($this->appresponse['stype']) . ' on ' . convert_to_camel_case($this->appresponse['bikemodel']) . '. Please wait for the Service Provider’s approval. Login to gear6.in to track your service progress.');
			} else {
				$this->send_sms_request_to_api($this->appresponse['phone'], 'Your service request is successfully placed as Order ID : ' . $OId . ' with ' . convert_to_camel_case($this->appresponse['scenter'][0]['ScName']) . ' for '. convert_to_camel_case($this->appresponse['stype']) . ' to ' . convert_to_camel_case($this->appresponse['bikemodel']) . ' on ' . $this->appresponse['timeslot'] . '. Please wait for the Service Provider’s approval. Login to gear6.in to track your service progress.');
			}
			$this->send_sms_request_to_api('9494845111', 'New order ' . $OId . ' placed by User: ' . $this->appresponse['uname'] . ', Phone ' . $this->appresponse['phone'] . ', Service Type: ' . convert_to_camel_case($this->appresponse['stype']) . ', Bike Model: ' . convert_to_camel_case($this->appresponse['bikemodel']) . ', Reg No: ' . $this->appresponse['bikenumber']);
			$this->send_sms_request_to_api('8888083841', 'New order ' . $OId . ' placed by User: ' . $this->appresponse['uname'] . ', Phone ' . $this->appresponse['phone'] . ', Service Type: ' . convert_to_camel_case($this->appresponse['stype']) . ', Bike Model: ' . convert_to_camel_case($this->appresponse['bikemodel']) . ', Reg No: ' . $this->appresponse['bikenumber']);
			$this->send_vendor_smses();
			$sms_suc_msg = "Your payment for " . $OId . " is successful with transaction ID " . $this->appresponse['txnid'] . ".";
			$sms_fail_msg = "Your payment for " . $OId . " with transaction ID " . $this->appresponse['txnid'] . " is not successful.";
			if($check) {
				$this->send_sms_request_to_api($this->appresponse['phone'], $sms_suc_msg);
				$this->appresponse['status'] = 1;
				$this->appresponse['smsg'] = 'Congratulations, Your payment with Transaction Id: ' . $this->appresponse['txnid'] . ' was successful. Your Service Order booking has been Successfully Completed';
				$this->db->where('admin_notification_flags.OId', $OId)->update('admin_notification_flags', array('new_payment' => 1));
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
			} elseif(isset($get_paymt_capture_status['error_description'])) {
				$this->send_sms_request_to_api($this->appresponse['phone'], $sms_fail_msg);
				$this->appresponse['error'] = 'Your payment with Transaction Id: ' . $this->appresponse['txnid'] . ' failed ' . 'with message: "' . $get_paymt_capture_status['error_description'] . '". If this is unexpected, please contact our customer support at support@gear6.in';
			}
			if($this->appresponse['is_logged_in'] == 0) {
				$this->appresponse['login_result'] = $this->user_m->app_login($this->appresponse['phone'], $this->appresponse['pwd']);
				$this->appresponse['is_logged_in'] = 1;
				$this->user_row = $this->user_m->get_by(array('AuthToken' => $this->appresponse['login_result']['auth_token']), TRUE);
				$this->appresponse['userdetails']['UserId'] = $this->user_row->UserId;
				$this->appresponse['userdetails']['UserName'] = $this->user_row->UserName;
				$this->appresponse['userdetails']['Phone'] = $this->user_row->Phone;
				$this->appresponse['userdetails']['Email'] = $this->user_row->Email;
				$this->appresponse['userdetails']['DOB'] = $this->user_row->DOB;
				$this->appresponse['userdetails']['Gender'] = $this->user_row->Gender;
				$this->appresponse['userdetails']['RefCode'] = $this->user_row->RefCode;
			}
			unset($this->appresponse['pwd']);
			unset($this->appresponse['timeslot']);
			unset($this->appresponse['scaddress']);
			unset($this->appresponse['uaddress']);
		}
		echo json_encode($this->appresponse);
	}
	public function rp_porder_status() {
		$this->appresponse['status'] = 0;
		if($_POST && $_POST['paymtid']) {
			$paymt_id = $this->input->post('paymtid');
			$OId = $this->input->post('oid');
			$amount_paid = intval($this->input->post('amount'));
			$get_paymt_status = $this->send_rzpay_api_request("https://api.razorpay.com/v1/payments/" . $paymt_id);
			$get_paymt_capture_status = $this->send_rzpay_api_request("https://api.razorpay.com/v1/payments/" . $paymt_id . "/capture", array('amount' => $amount_paid));
			$this->appresponse['OId'] = $OId;
			$this->get_order_details($OId);
			$usr_id = intval($this->user_row->UserId);
			$paymt_row['UserId'] = $usr_id;
			$paymt_row['OId'] = $OId;
			$paymt_row['PaymtAmt'] = round($amount_paid / 100, 2);
			$paymt_row['PaymtId'] = 6;
			$paymt_row['PaymtResponse'] = serialize($get_paymt_capture_status);
			if(isset($get_paymt_status['status']) && $get_paymt_status['status'] == 'authorized') {
				$paymt_row['PaymtStatusId'] = 3;
				$check = TRUE;
			} else {
				$check = FALSE;
				$paymt_row['PaymtStatusId'] = 1;
			}
			$this->load->model('opaymtdetail_m');
			$this->appresponse['txnid'] = $this->opaymtdetail_m->create_custom_trxn($paymt_row);
			$sms_suc_msg = "Your payment for " . $OId . " is successful with transaction ID " . $this->appresponse['txnid'] . ".";
			$sms_fail_msg = "Your payment for " . $OId . " with transaction ID " . $this->appresponse['txnid'] . " is not successful.";
			if($check) {
				$this->send_sms_request_to_api($this->appresponse['phone'], $sms_suc_msg);
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
				$this->db->where('admin_notification_flags.OId', $OId)->update('admin_notification_flags', array('new_payment' => 1));
				$this->appresponse['status'] = 1;
				$this->appresponse['smsg'] = 'Congratulations, Your payment with Transaction Id: ' . $this->appresponse['txnid'] . ' was successful. Your Service Order booking has been Successfully Completed';
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
			} elseif(isset($get_paymt_capture_status['error_description'])) {
				$this->send_sms_request_to_api($this->appresponse['phone'], $sms_fail_msg);
				$this->appresponse['error'] = 'Your payment with Transaction Id: ' . $this->appresponse['txnid'] . ' failed ' . 'with message: "' . $get_paymt_capture_status['error_description'] . '". If this is unexpected, please contact our customer support at support@gear6.in';
			}
			unset($this->appresponse['pwd']);
			unset($this->appresponse['timeslot']);
			unset($this->appresponse['scaddress']);
			unset($this->appresponse['uaddress']);
		}
		echo json_encode($this->appresponse);
	}
	public function get_user_orders() {
		$this->appresponse['status'] = 0;
		if($this->appresponse['is_logged_in'] == 1) {
			$this->get_active_orders();
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function get_order_history() {
		$this->appresponse['status'] = 0;
		if($this->appresponse['is_logged_in'] == 1) {
			$this->get_all_user_orders();
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function get_odetails() {
		$this->appresponse['status'] = 0;
		$OId = $this->input->post('oid');
		if($_POST && $OId && $this->appresponse['is_logged_in'] == 1 && $this->is_valid_user_order($OId)) {
			$this->get_user_odetails($OId);
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function user_logout() {
		if($this->appresponse['is_logged_in'] == 1) {
			$this->db->where('UserId', $this->user_row->UserId)->update('user', array('AuthToken' => NULL));
			$devicetype = $this->get_user_agent();
			$this->db->where('UserId', $this->user_row->UserId)->where('DeviceType', $devicetype)->update('appusers', array('UserId' => NULL));
		}
		$this->appresponse['status'] = 1;
		$this->appresponse['is_logged_in'] = 0;
		unset($this->appresponse['userdetails']);
		echo json_encode($this->appresponse);
	}
	public function user_login() {
		if($_POST) {
			$this->appresponse['login_result'] = $this->user_m->app_login($this->input->post('phone'), $this->input->post('password'));
			if($this->appresponse['login_result']['status'] == 1) {
				$this->appresponse['is_logged_in'] = 1;
				$this->user_row = $this->user_m->get_by(array('AuthToken' => $this->appresponse['login_result']['auth_token']), TRUE);
				$this->appresponse['userdetails']['UserId'] = $this->user_row->UserId;
				$this->appresponse['userdetails']['UserName'] = $this->user_row->UserName;
				$this->appresponse['userdetails']['Phone'] = $this->user_row->Phone;
				$this->appresponse['userdetails']['Email'] = $this->user_row->Email;
				$this->appresponse['userdetails']['DOB'] = $this->user_row->DOB;
				$this->appresponse['userdetails']['Gender'] = $this->user_row->Gender;
				$this->appresponse['userdetails']['RefCode'] = $this->user_row->RefCode;
				$this->updateDeviceId();
			}
		}
		echo json_encode($this->appresponse);
	}
	public function social_login() {
		if($_POST) {
			$token_valid = FALSE;
			if($this->input->post('ltype') == 'Facebook') {
				$token_valid = $this->validate_fb_token($this->input->post('token'), $this->input->post('fgid'));
			} elseif ($this->input->post('ltype') == 'Google') {
				$token_valid = $this->validate_gp_token($this->input->post('token'), $this->input->post('fgid'));
			}
			if($token_valid) {
				$this->appresponse['login_result'] = $this->user_m->app_social_login($this->input->post('fgid'), $this->input->post('ltype'));
				if($this->appresponse['login_result']['status'] == 1) {
					$this->appresponse['is_logged_in'] = 1;
					$this->user_row = $this->user_m->get_by(array('AuthToken' => $this->appresponse['login_result']['auth_token']), TRUE);
					$this->appresponse['userdetails']['UserId'] = $this->user_row->UserId;
					$this->appresponse['userdetails']['UserName'] = $this->user_row->UserName;
					$this->appresponse['userdetails']['Phone'] = $this->user_row->Phone;
					$this->appresponse['userdetails']['Email'] = $this->user_row->Email;
					$this->appresponse['userdetails']['DOB'] = $this->user_row->DOB;
					$this->appresponse['userdetails']['Gender'] = $this->user_row->Gender;
					$this->appresponse['userdetails']['RefCode'] = $this->user_row->RefCode;
					$this->updateDeviceId();
				}
			} else {
				$this->appresponse['login_result']['status'] = 0;
				$this->appresponse['login_result']['errmsg'] = "Invalid Auth Token Sent";
			}
		}
		echo json_encode($this->appresponse);
	}
	public function signup() {
		if($_POST) {
			$ref_code = $this->input->post('referral_coupon'); $ref_valid = FALSE; $ref_user = NULL;
			if($ref_code != NULL && $ref_code != "") {
				$referrer = $this->user_m->get_by(array('RefCode' => $ref_code), TRUE);
				if($referrer) { $ref_user = $referrer->UserId; $ref_valid = TRUE; }
			} else { $ref_valid = TRUE; }
			if ($this->input->post('stype') == "Facebook" || $this->input->post('stype') == 'Google') {
				$token_valid = FALSE;
				if($this->input->post('stype') == 'Facebook') {
					$token_valid = $this->validate_fb_token($this->input->post('token'), $this->input->post('fgid'));
				} elseif ($this->input->post('stype') == 'Google') {
					$token_valid = $this->validate_gp_token($this->input->post('token'), $this->input->post('fgid'));
				}
				if($token_valid && $ref_valid) {
					$_POST['fg_id'] = $this->input->post('fgid');
					$_POST['fg_type'] = $this->input->post('stype');
					$this->user_m->signup_social_user($ref_user);
					$this->appresponse['login_result'] = $this->user_m->app_social_login($this->input->post('fgid'), $this->input->post('stype'));
					$this->appresponse['is_logged_in'] = 1;
					$this->user_row = $this->user_m->get_by(array('AuthToken' => $this->appresponse['login_result']['auth_token']), TRUE);
					$this->appresponse['userdetails']['UserId'] = $this->user_row->UserId;
					$this->appresponse['userdetails']['UserName'] = $this->user_row->UserName;
					$this->appresponse['userdetails']['Phone'] = $this->user_row->Phone;
					$this->appresponse['userdetails']['Email'] = $this->user_row->Email;
					$this->appresponse['userdetails']['DOB'] = $this->user_row->DOB;
					$this->appresponse['userdetails']['Gender'] = $this->user_row->Gender;
					$this->appresponse['userdetails']['RefCode'] = $this->user_row->RefCode;
					$this->updateDeviceId();
				} else {
					$this->appresponse['login_result']['status'] = 0;
					if(!$ref_valid) {
						$this->appresponse['login_result']['errmsg'] = "Invalid Referral Code";
					} else {						
						$this->appresponse['login_result']['errmsg'] = "Invalid Auth Token Sent";
					}
				}
			} else {
				if($ref_valid) {
					$new_user = $this->user_m->signup_normal_user($ref_user);
					$this->appresponse['login_result'] = $this->user_m->app_login($this->input->post('s_phone'), $this->input->post('s_pwd'));
					$this->appresponse['is_logged_in'] = 1;
					$this->user_row = $this->user_m->get_by(array('AuthToken' => $this->appresponse['login_result']['auth_token']), TRUE);
					$this->appresponse['userdetails']['UserId'] = $this->user_row->UserId;
					$this->appresponse['userdetails']['UserName'] = $this->user_row->UserName;
					$this->appresponse['userdetails']['Phone'] = $this->user_row->Phone;
					$this->appresponse['userdetails']['Email'] = $this->user_row->Email;
					$this->appresponse['userdetails']['DOB'] = $this->user_row->DOB;
					$this->appresponse['userdetails']['Gender'] = $this->user_row->Gender;
					$this->appresponse['userdetails']['RefCode'] = $this->user_row->RefCode;
					$this->updateDeviceId();
				} else {
					$this->appresponse['login_result']['status'] = 0;
					$this->appresponse['login_result']['errmsg'] = "Invalid Referral Code";
				}
			}
		}
		echo json_encode($this->appresponse);
	}
	public function is_social_signedup() {
		$this->appresponse['status'] = 0;
		if($this->input->post('fgid') != "" && $this->input->post('stype') != "") {
			$user = $this->user_m->get_social_user($this->input->post('fgid'), $this->input->post('stype'));
			if($user && count($user) == 1) {
				$this->appresponse['status'] = 1;
			}
		}
		echo json_encode($this->appresponse);
	}
	public function first_time_update() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->appresponse['is_logged_in'] == 1) {
			$this->user_m->reset_password($this->input->post('pswd1'), $this->input->post('pswd2'), $this->user_row->Phone);
			$this->user_m->updtGendDob($this->input->post('dob'), $this->input->post('gender'), $this->user_row->Phone);
			$this->user_row = $this->user_m->get_by(array('Phone' => $this->user_row->Phone), TRUE);
			$this->appresponse['userdetails']['DOB'] = $this->user_row->DOB;
			$this->appresponse['userdetails']['Gender'] = $this->user_row->Gender;
			$this->updateDeviceId();
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function send_accemg_msg() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->query_row && intval($this->query_row->ServiceId) == 7) {
			$phone = $this->input->post('phone');
			$message = $this->input->post('message');
			$scid = $this->input->post('scid');
			$this->load->model('servicecenter_m');
			$scphone = $this->servicecenter_m->get_service_provider($scid);
			if($scphone['Phone']) {
				$this->send_sms_request_to_api($scphone['Phone'], 'Emergency / Accidental Service Request Received From +91' . $phone . '. Message: ' . $message);
			}
			$this->send_sms_request_to_api('8888083841', 'Emergency / Accidental Service Request Received From +91' . $phone . '. Message: ' . $message);
			$this->send_sms_request_to_api('9494845111', 'Emergency / Accidental Service Request Received From +91' . $phone . '. Message: ' . $message);
			$this->load->model('service_m');
			$this->appresponse['services'] = $this->service_m->get_by('isAppEnabled = 1');
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function update_udetails() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->appresponse['is_logged_in'] == 1) {
			$ndata['UserName'] = $this->input->post('username');
			$ndata['Email'] = $this->input->post('email');
			$ndata['DOB'] = date('Y-m-d', strtotime($this->input->post('dob')));
			$this->insert_acupdate_history();
			$this->db->where('UserId', intval($this->user_row->UserId))->update('user', $ndata);
			$this->appresponse['userdetails']['UserName'] = $ndata['UserName'];
			$this->appresponse['userdetails']['Email'] = $ndata['Email'];
			$this->appresponse['userdetails']['DOB'] = $ndata['DOB'];
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function update_password() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->appresponse['is_logged_in'] == 1) {
			$status = $this->user_m->app_reset_password($this->input->post('pswd1'), $this->input->post('pswd2'), $this->user_row->Phone, TRUE);
			if($status) {
				$this->appresponse['status'] = 1;
			} else {
				$this->appresponse['errmsg'] = 'Invalid current password!';
			}
		}
		echo json_encode($this->appresponse);
	}
	public function send_phchange_otp() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->appresponse['is_logged_in'] == 1) {
			$this->load->model('otp_m');
			if($this->user_m->is_unique_ph($this->input->post('phNum'))) {
				$otp = $this->otp_m->is_otp_inserted($this->input->post('phNum'));
				if(!$otp) {
					$otp = $this->otp_m->insert_otp($this->input->post('phNum'));
				}
				$this->send_sms_request_to_api($this->input->post('phNum'), "Your OTP is " . $otp . " for your mobile confirmation at gear6.in");
				$this->appresponse['status'] = 1;
			} else {
				$this->appresponse['errmsg'] = 'Sorry, this phone is already registered';
			}
		}
		echo json_encode($this->appresponse);
	}
	public function update_phone() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->appresponse['is_logged_in'] == 1) {
			$this->load->model('otp_m');
			$otpcheck = $this->otp_m->check_otp($this->input->post('otp'), $this->input->post('nphone'));
			if($otpcheck == 1) {
				$this->insert_acupdate_history();
				$this->user_m->update_phone($this->input->post('nphone'), $this->user_row->UserId);
				$this->appresponse['status'] = 1;
			} else {
				$this->appresponse['errmsg'] = 'Invalid OTP entered or OTP expired!';
			}
		}
		echo json_encode($this->appresponse);
	}
	public function getScRatings() {
		$this->appresponse['status'] = 0;
		if($_POST) {
			$sc_id = $_POST['ScId']; $rating = array();
			$this->db->select('servicecenter.ScName, FORMAT(servicecenter.Rating, "1") AS Rating, servicecenter.RatersCount')->from('servicecenter');
			$this->db->where('servicecenter.ScId', $sc_id);
			$overall = $this->db->get()->row_array();
			$this->db->select('user_feedback.ExecFbAnswer, odetails.user_feedback_rating AS admin_rating')->from('user_feedback');
			$this->db->join('odetails', 'odetails.OId = user_feedback.OId', 'left');
			$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
			$this->db->where('user_feedback.ExecFbQId', 3);
			$this->db->where('oservicedetail.ScId', $sc_id);
			$result = $this->db->get()->result_array();
			if(count($result) > 0) {
				foreach ($result as &$review) {
					if($review['admin_rating'] > 0) {
						$review['ExecFbAnswer'] = round((($review['ExecFbAnswer'] + $review['admin_rating']) / 2)); unset($review['admin_rating']);
					}
				}
				$rating['1'] = 0; $rating['2'] = 0; $rating['3'] = 0; $rating['4'] = 0; $rating['5'] = 0;
				foreach ($result as $row) {
					$rating[$row['ExecFbAnswer']] += 1;
				}
			} else {
				$rating = array();
			}
			$this->db->select('odetails.user_feedback_remarks AS remarks, odetails.ODate AS date, bikemodel.BikeModelName AS model, bikecompany.BikeCompanyName AS company, user.UserName AS name, user_feedback.ExecFbAnswer AS rating, odetails.user_feedback_rating AS admin_rating')->from('odetails');
			$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
			$this->db->join('user_feedback', 'user_feedback.OId = odetails.OId');
			$this->db->join('user', 'user.UserId = odetails.UserId');
			$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId');
			$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId');
			$this->db->where('oservicedetail.ScId', $sc_id);
			$this->db->where('user_feedback_remarks IS NOT NULL');
			$this->db->where('user_feedback_remarks != ', '');
			$this->db->where('ExecFbQId', 3);
			$reviews = $this->db->get()->result_array();
			if(count($reviews) == 0) {
				$reviews = array();
			} else {
				foreach ($reviews as &$review) {
					if($review['admin_rating'] > 0) {
						$review['rating'] = round((($review['rating'] + $review['admin_rating']) / 2), 1); unset($review['admin_rating']);
					}
				}
			}
			$this->appresponse["ratings"] = $rating;
			$this->appresponse["overall"] = $overall;
			// $this->appresponse["reviews"] = $reviews;
			$this->appresponse["reviews"] = array();
			$this->appresponse['status'] = 1;
			echo json_encode($this->appresponse);
		}
	}
	public function get_feedback_questions() {
		$this->appresponse['status'] = 0;
		if($_POST && $_POST['OId'] && $this->is_valid_user_order($_POST['OId']) && $this->appresponse['is_logged_in'] == 1) {
			$OId = $_POST['OId'];
			$sql = "SELECT execfbqs.*, user_feedback_oid.ExecFbAnswer FROM execfbqs ";
			$sql .= "LEFT JOIN (SELECT ExecFbAnswer, ExecFbQId FROM user_feedback WHERE user_feedback.OId = '" . $OId . "') AS user_feedback_oid ON (user_feedback_oid.ExecFbQId = execfbqs.ExecFbQId) ";
			$sql .= "WHERE execfbqs.isEnabled = '1' ORDER BY execfbqs.ExecFbQId ASC";
			$query = $this->db->query($sql);
			$result = $query->result_array();
			$this->appresponse['questions'] = $result;
			$this->db->select('user_feedback_remarks');
			$this->db->from('odetails');
			$this->db->where('OId', $OId);
			$query = $this->db->get();
			$remarks = $query->result_array();
			if($remarks == NULL) { $remarks = ""; } else { $remarks = $remarks[0]['user_feedback_remarks']; }
			$this->appresponse["remarks"] = $remarks;
			$this->appresponse['status'] = 1;
		}
		echo json_encode($this->appresponse);
	}
	public function updateUserFeedback() {
		$this->appresponse['status'] = 0;
		if($_POST && $this->appresponse['is_logged_in'] == 1) {
			$OId = $this->input->post('OId'); $remarks = $this->input->post('remarks');
			$feedbackArray = explode(",", $this->input->post('feedbackArray'));
			$questionArray = explode(",", $this->input->post('questionArray'));
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
					$this->appresponse['status'] = 1;
				}
			}
		}
		echo json_encode($this->appresponse);
	}
	private function clear_query_token() {
		$cdata['Latitude'] = NULL;
		$cdata['Longitude'] = NULL;
		$cdata['LocationName'] = NULL;
		$cdata['ServiceId'] = NULL;
		$cdata['ODate'] = NULL;
		$cdata['BikeCompanyId'] = NULL;
		$cdata['BikeModelId'] = NULL;
		$cdata['Phone'] = NULL;
		$cdata['ScId'] = NULL;
		$cdata['ScIds'] = NULL;
		$cdata['QType'] = NULL;
		$cdata['SlotHour'] = NULL;
		$cdata['SlotBufferId'] = NULL;
		$cdata['RegNum'] = NULL;
		$cdata['BikeNumber'] = NULL;
		$cdata['UserComments'] = NULL;
		$cdata['ImgData'] = NULL;
		$cdata['Amenities'] = NULL;
		$cdata['Aservices'] = NULL;
		$cdata['UserName'] = NULL;
		$cdata['Email'] = NULL;
		$cdata['UserAddrId'] = NULL;
		$cdata['AddrLine1'] = NULL;
		$cdata['AddrLine2'] = NULL;
		$cdata['Landmark'] = NULL;
		$cdata['LocationId'] = NULL;
		$cdata['CouponId'] = NULL;
		$cdata['CDValue'] = NULL;
		$cdata['FCouponId'] = NULL;
		$cdata['FDValue'] = NULL;
		$cdata['FSDValue'] = NULL;
		$cdata['ToPay'] = NULL;
		$cdata['RegYear'] = NULL;
		$cdata['ExpiryDays'] = NULL;
		$cdata['PreviousInsurer'] = NULL;
		$cdata['IsClaimedBefore'] = NULL;
		$cdata['UserIp'] = NULL;
		$this->db->where('QueryToken', $this->query_row->QueryToken)->update('appdata', $cdata);
	}
	private function insert_acupdate_history() {
		$uphdata['UserId'] = intval($this->user_row->UserId);
		$uphdata['Name'] = $this->user_row->UserName;
		$uphdata['Phone'] = $this->user_row->Phone;
		$uphdata['Email'] = $this->user_row->Email;
		$uphdata['UserIp'] = $this->input->ip_address();
		$this->load->library('user_agent', NULL, 'agent');
		if ($this->agent->is_mobile()) {
			$uphdata['UserDevice'] = 'mob';
		} else {
			$uphdata['UserDevice'] = 'pc';
		}
		$this->db->insert('uphistory', $uphdata);
	}
	private function validate_fb_token($token, $id) {
		$app_id = 819826911429226;
		$app_secret = "f0d092c2a94a194b43096f588e50e51b";
		$app_token = $this->get_curl_data("https://graph.facebook.com/oauth/access_token?client_id=" . $app_id . "&client_secret=" . $app_secret . "&grant_type=client_credentials");
		$verify_token = $this->get_curl_data("https://graph.facebook.com/debug_token?input_token=" . $token . "&" . $app_token);
		$verified_token = json_decode($verify_token, TRUE);
		if($verified_token && $verified_token['data'] && intval($verified_token['data']['app_id']) == $app_id && $verified_token['data']['user_id'] == $id) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	private function validate_gp_token($token, $id) {
		$app_ids = array('452928637350-ipro3sni73tel2r7liil3cfivlp6srh5.apps.googleusercontent.com',
			'452928637350-l2om65fvn2f2fqvu5v8iv9uhoohana26.apps.googleusercontent.com',
			'452928637350-f2893ifr1c8bs5pga4q7kntle2rr74aa.apps.googleusercontent.com',
			'452928637350-o9lt3ed525u6roa3o9jmeevej1j9ijqu.apps.googleusercontent.com',
			'452928637350-t9mvi5q8tbpbq3iuut153j38h6oba3e3.apps.googleusercontent.com'
		);
		$verify_token = $this->get_curl_data("https://www.googleapis.com/oauth2/v1/tokeninfo?id_token=" . $token);
		$verified_token = json_decode($verify_token, TRUE);
		if($verified_token && in_array($verified_token['audience'], $app_ids) && $verified_token['user_id'] == $id) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	private function get_curl_data($url) {
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $url);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl_handle, CURLOPT_POST, FALSE);
		curl_setopt($curl_handle, CURLOPT_HEADER, FALSE);
		curl_setopt($curl_handle, CURLOPT_USERAGENT, 'gear6.in');
		$data = curl_exec($curl_handle);
		curl_close($curl_handle);
		return $data;
	}
	private function send_vendor_smses() {
		$this->load->model('servicecenter_m');
		$sc_phone = $this->servicecenter_m->get_sc_phone_by_oid($this->appresponse['OId']);
		if(isset($sc_phone)) {
			if($this->appresponse['serid'] == 3) {
				foreach($sc_phone as $phone) {
					$this->send_sms_request_to_api($phone['Phone'], 'New query received as order ' . $this->appresponse['OId'] . ' realted to ' . $this->appresponse['scenter'][0]['ServiceDesc1'] . '. Login to your panel for more details.');
				}
			} else {
				$this->send_sms_request_to_api($sc_phone[0]['Phone'], 'New ' . $this->appresponse['stype'] . ' request received as order ' . $this->appresponse['OId'] . ' for ' . convert_to_camel_case($this->appresponse['bikemodel']) . ' Reg No.: ' . $this->appresponse['bikenumber'] . ' on ' . $this->appresponse['timeslot'] . '. Login to your panel for more details.');
			}
		}
	}
	private function get_order_details($OId) {
		$this->load->model('odetails_m');
		$this->load->model('amenity_m');
		$this->appresponse['OId'] = $OId;
		$service_details = $this->odetails_m->get_stype_by_oid($OId);
		$this->appresponse['stype'] = $service_details['ServiceName'];
		$this->appresponse['serid'] = intval($service_details['ServiceId']);
		$this->appresponse['serimg'] = $service_details['SerImg'];
		$sc_details = $this->odetails_m->get_scenter_by_oid($OId);
		$this->appresponse['scenter'] = $sc_details;
		$bike_model_details = $this->odetails_m->get_bm_by_oid($OId);
		$this->appresponse['bikenumber'] = $bike_model_details['BikeNumber'];
		$this->appresponse['bikemodel'] = $bike_model_details['BikeCompanyName'] . ' ' . $bike_model_details['BikeModelName'];
		$this->appresponse['timeslotpojo'] = $this->odetails_m->get_app_timeslot_by_oid($OId);
		$this->appresponse['timeslot'] = $this->odetails_m->get_timeslot_by_oid($OId);
		$this->appresponse['paymode'] = $this->odetails_m->get_paymode_by_oid($OId);
		$user_details = $this->odetails_m->get_user_address($OId);
		$this->appresponse['user_details'] = $this->odetails_m->get_app_user_address($OId);
		$this->appresponse['uname'] = $user_details['name'];
		$this->appresponse['uemail'] = $user_details['email'];
		$this->appresponse['phone'] = $user_details['Phone'];
		$this->appresponse['uaddress'] = $user_details['address'];
		if ($this->appresponse['is_logged_in'] == 0) {
			$this->appresponse['pwd'] = $user_details['pwd'];
		}
		if ($this->appresponse['serid'] != 3) {
			$this->appresponse['scaddresspojo'] = $this->odetails_m->get_app_sc_address($sc_details[0]['ScId']);
			$this->appresponse['scaddress'] = $this->odetails_m->get_sc_address($sc_details[0]['ScId']);
			$prices = $this->amenity_m->get_est_prices_by_oid($OId);
			if(isset($prices) && count($prices) > 0) {
				foreach($prices as $price) {
					if(isset($price['ptotal'])) {
						$this->appresponse['total_price'] = $price['ptotal'];
					} else {
						$price['attype'] = intval($price['attype']);
						unset($price['apid']);
						$this->appresponse['pricedetails'][] = $price;
					}
				}
			} else {
				$this->appresponse['pricedetails'] = NULL;
				$this->appresponse['total_price'] = 0;
			}
		}
	}
	private function is_valid_user_order($OId) {
		$valid_user = $this->db->select('odetails.UserId')->from('odetails')->where('odetails.OId', $OId)->limit(1)->get()->row_array();
		if($valid_user && $valid_user['UserId'] == $this->user_row->UserId) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	private function get_user_odetails($OId) {
		$this->load->model('odetails_m');
		$this->load->model('status_m');
		$this->load->model('statushistory_m');
		$this->appresponse['OId'] = $OId;
		$this->appresponse['omedia'] = $this->odetails_m->get_order_media($OId);
		if(isset($this->appresponse['omedia']) && count($this->appresponse['omedia']) > 0) {
			foreach($this->appresponse['omedia'] as &$tmp_img) {
				$tmp_img = get_awss3_url('uploads/omedia/' . $tmp_img['FileType'] . '/' . $tmp_img['FileData']);
			}
		}
		$service_details = $this->odetails_m->get_stype_by_oid($OId);
		$this->appresponse['stype'] = $service_details['ServiceName'];
		$this->appresponse['serid'] = $service_details['ServiceId'];
		$this->appresponse['serimg'] = $service_details['SerImg'];
		$this->appresponse['mr_remarks'] = $service_details['MRRemarks'];
		$this->appresponse['statuses'] = $this->status_m->get_statuses_for_service($service_details['ServiceId']);
		$sc_details = $this->odetails_m->get_scenter_by_oid($OId);
		$this->appresponse['scenter'] = $sc_details;
		$bike_model_details = $this->odetails_m->get_bm_by_oid($OId);
		$this->appresponse['bikenumber'] = $bike_model_details['BikeNumber'];
		$this->appresponse['bikemodel'] = $bike_model_details['BikeCompanyName'] . ' ' . $bike_model_details['BikeModelName'];
		$this->appresponse['timeslot'] = $this->odetails_m->get_app_timeslot_by_oid($OId);
		$this->appresponse['paymode'] = $this->odetails_m->get_paymode_by_oid($OId);
		$this->appresponse['uaddress'] = $this->odetails_m->get_app_user_address($OId);
		$resc_odetails = $this->odetails_m->get_odetails_for_reschedule($OId);
		if (intval($service_details['ServiceId']) == 4) {
			$this->appresponse['insren_details'] = $this->odetails_m->get_insren_details($OId);
		}
		if (intval($service_details['ServiceId']) != 3) {
			$this->load->model('amenity_m');
			$this->load->model('opaymtdetail_m');
			$this->appresponse['chosen_amenities'] = $this->amenity_m->get_chosen_amenities($OId);
			$this->appresponse['scaddress'] = $this->odetails_m->get_app_sc_address($sc_details[0]['ScId']);
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
						unset($price['attype']);
						unset($price['atdesc']);
						unset($price['atprice']);
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
			$this->appresponse['others_total'] = $oprices_total;
			$this->appresponse['stathists'] = $this->statushistory_m->get_status_history($OId, FALSE, $sc_details[0]['ScId']);
			$this->appresponse['ord_trans'] = $this->opaymtdetail_m->get_order_transactions($OId);
			$this->appresponse['total_paid'] = floatval($this->opaymtdetail_m->get_total_paid_amount($OId));
			$this->appresponse['total_price'] = floatval($estprices_total) + floatval($oprices_total);
			$this->appresponse['to_be_paid'] = round(floatval($this->appresponse['total_price'] - $discprices_total - $this->appresponse['total_paid']), 2);
			if($this->appresponse['to_be_paid'] < 0.01 && $this->appresponse['to_be_paid'] > -0.01) {
				$this->appresponse['to_be_paid'] = 0;
			}
		} else {
			$this->appresponse['stathists'] = $this->statushistory_m->get_status_history($OId, TRUE, NULL);
		}
	}
	private function get_all_user_orders() {
		$this->load->model('odetails_m');
		$this->load->model('amenity_m');
		$this->load->model('statushistory_m');
		$oids = $this->odetails_m->get_oids_user($this->user_row->UserId);
		$count = 0;
		if (count($oids) > 0) {
			foreach($oids as $oid) {
				$this->appresponse['orders'][$count]['OId'] = $oid['OId'];
				$service_details = $this->odetails_m->get_stype_by_oid($oid['OId']);
				$this->appresponse['orders'][$count]['stype'] = $service_details['ServiceName'];
				$this->appresponse['orders'][$count]['serid'] = intval($service_details['ServiceId']);
				$this->appresponse['orders'][$count]['serimg'] = $service_details['SerImg'];
				$this->appresponse['orders'][$count]['mr_remarks'] = $service_details['MRRemarks'];
				$sc_details = $this->odetails_m->get_scenter_by_oid($oid['OId']);
				$this->appresponse['orders'][$count]['scenter'] = $sc_details;
				$bike_model_details = $this->odetails_m->get_bm_by_oid($oid['OId']);
				$this->appresponse['orders'][$count]['bikemodel'] = $bike_model_details['BikeCompanyName'] . ' ' . $bike_model_details['BikeModelName'];
				$this->appresponse['orders'][$count]['timeslot'] = $this->odetails_m->get_timeslot_by_oid($oid['OId']);
				$this->appresponse['orders'][$count]['paymode'] = $this->odetails_m->get_paymode_by_oid($oid['OId']);
				$this->appresponse['orders'][$count]['uaddress'] = $this->odetails_m->get_app_user_address($oid['OId']);
				if (intval($service_details['ServiceId']) == 4) {
					$this->appresponse['orders'][$count]['insren_details'] = $this->odetails_m->get_insren_details($oid['OId']);
				}
				if (intval($service_details['ServiceId']) != 3) {
					$this->load->model('aservice_m');
					$this->appresponse['orders'][$count]['amenities'] = $this->amenity_m->get_chosen_amenities($oid['OId']);
					$this->appresponse['orders'][$count]['aservices'] = $this->aservice_m->get_chosen_aservices($oid['OId']);
					$this->appresponse['orders'][$count]['scaddress'] = $this->odetails_m->get_app_sc_address($sc_details[0]['ScId']);
					$estprices = $this->amenity_m->get_est_prices_by_oid($oid['OId']);
					$opriceses = $this->statushistory_m->get_oprices($oid['OId']);
					$this->appresponse['orders'][$count]['total_price'] = floatval($estprices[count($estprices) - 1]['ptotal']) + floatval($opriceses[count($opriceses) - 1]['ptotal']);
				}
				$count += 1;
			}
		}
	}
	private function get_holidays() {
		$date = date('Y-m-d', strtotime("now"));
		$this->db->select('Holiday');
		$this->db->from('service_center_holidays');
		$this->db->where('Holiday >=', $date);
		$this->db->where('ScId', -1);
		$this->db->order_by('service_center_holidays.ScHId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		$fresults = array();
		foreach($results as $result) {
			$fresults[] = $result['Holiday'];
		}
		return $fresults;
	}
	private function get_active_orders() {
		$this->load->model('odetails_m');
		$this->load->model('amenity_m');
		$this->load->model('statushistory_m');
		$oids = $this->odetails_m->get_active_oids_user(FALSE, $this->user_row->UserId);
		$count = 0;
		if (count($oids) > 0) {
			foreach($oids as $oid) {
				$this->appresponse['aorders'][$count]['oid'] = $oid['OId'];
				$service_details = $this->odetails_m->get_stype_by_oid($oid['OId']);
				$this->appresponse['aorders'][$count]['stype'] = $service_details['ServiceName'];
				$this->appresponse['aorders'][$count]['serid'] = $service_details['ServiceId'];
				$this->appresponse['aorders'][$count]['serimg'] = $service_details['SerImg'];
				$sc_details = $this->odetails_m->get_scenter_by_oid($oid['OId']);
				$this->appresponse['aorders'][$count]['scenter'] = $sc_details;
				$bike_model_details = $this->odetails_m->get_bm_by_oid($oid['OId']);
				$this->appresponse['aorders'][$count]['bikemodel'] = $bike_model_details['BikeCompanyName'] . ' ' . $bike_model_details['BikeModelName'];
				$this->appresponse['aorders'][$count]['timeslot'] = $this->odetails_m->get_timeslot_by_oid($oid['OId']);
				if(intval($service_details['ServiceId']) != 3) {
					$this->appresponse['aorders'][$count]['statusmsg'] = $this->odetails_m->get_ostatus_name($oid['OId']);
					$this->load->model('aservice_m');
					$this->appresponse['aorders'][$count]['amenities'] = $this->amenity_m->get_chosen_amenities($oid['OId']);
					$this->appresponse['aorders'][$count]['aservices'] = $this->aservice_m->get_chosen_aservices($oid['OId']);
					$this->appresponse['aorders'][$count]['scaddress'] = $this->odetails_m->get_app_sc_address($sc_details[0]['ScId']);
					$estprices = $this->amenity_m->get_est_prices_by_oid($oid['OId']);
					$opriceses = $this->statushistory_m->get_oprices($oid['OId']);
					$this->appresponse['aorders'][$count]['total_price'] = floatval($estprices[count($estprices) - 1]['ptotal']) + floatval($opriceses[count($opriceses) - 1]['ptotal']);
				}
				$count += 1;
			}
		}
	}
	private function finalize_image_upload($imgdata, $oid) {
		$uploaded_media = unserialize(stripslashes($imgdata));
		$count = 0;
		foreach($uploaded_media as $file) {
			$to_file = 'uploads/omedia/';
			$to_file .= $file['type'] . '/' . $file['name'];
			$this->load->library('awssdk');
			$s3 = $this->awssdk->get_s3_instance();
			try {
				$s3->copyObject(array(
					'Bucket'     => 'gear6cdn',
					'Key'        => $to_file,
					'CopySource' => 'gear6cdn/temp/' . $file['name'],
					'ACL'    => 'public-read',
				));
				$s3->deleteObject(array(
					'Bucket' => 'gear6cdn',
					'Key'    => 'temp/' . $file['name']
				));
			} catch (Aws\Exception\S3Exception $e) {
			}
			$data[$count]['OId'] = $oid;
			$data[$count]['FileType'] = $file['type'];
			$data[$count]['FileData'] = $file['name'];
			$data[$count]['MediaType'] = 'info';
			$count += 1;
		}
		$this->db->insert_batch('omedia', $data);
	}
	private function expire_buffered_slot() {
		$this->load->model('servicecenter_m');
		$remaining_slots = $this->servicecenter_m->check_if_slots_still_exists($this->query_row->ScId, $this->query_row->ODate, $this->query_row->SlotHour);
		$eff_slots = intval($remaining_slots['Slots']) - intval($remaining_slots['BufferedSlots']);
		$data['Status'] = 1;
		$this->db->where('SlotBufferId', intval($this->query_row->SlotBufferId));
		$this->db->update('slotsbuffer', $data);
		if($this->db->affected_rows() <= 0) {
			if($eff_slots > 0) {
				$data = array(
					'SlotId' => intval($remaining_slots['SlotId']),
					'Status' => 1
				);
				$this->db->insert('slotsbuffer', $data);
				return TRUE;
			} else {
				return FALSE;
			}
		}
		return TRUE;
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
			$this->appresponse['emsg'] = 'This coupon cannot be applied on this service type';
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
		$this->appresponse['fdvalue'] = $coupon_amount;
		$this->appresponse['to_pay'] = ($purchase_value - $coupon_amount);
		$this->query_row->FCouponId = $cdata['FCouponId'] = intval($fcrow['FCouponId']);
		$this->query_row->ToPay = $cdata['ToPay'] = $this->appresponse['to_pay'];
		$this->query_row->FDValue = $cdata['FDValue'] = $this->appresponse['fdvalue'];
		$this->db->where('QueryToken', $this->query_row->QueryToken)->update('appdata', $cdata);
		$this->finalize_calculations('f');
	}
	private function calculate_remove_prices($type) {
		if($type == 'c') {
			$this->appresponse['to_pay'] = floatval($this->query_row->ToPay) + floatval($this->query_row->CDValue);
			$this->query_row->CDValue = NULL;
			$this->query_row->CouponId = NULL;
			$this->query_row->ToPay = $this->appresponse['to_pay'];
			if($this->query_row->FCouponId != '' && $this->query_row->FCouponId !== NULL) {
				$this->appresponse['fdvalue'] = floatval($this->query_row->FDValue);
			}
		} elseif($type == 'f') {
			$this->appresponse['to_pay'] = floatval($this->query_row->ToPay) + floatval($this->query_row->FDValue);
			$this->query_row->FDValue = NULL;
			$this->query_row->FCouponId = NULL;
			$this->query_row->ToPay = $this->appresponse['to_pay'];
			if($this->query_row->CouponId != '' && $this->query_row->CouponId !== NULL) {
				$this->appresponse['cdvalue'] = floatval($this->query_row->CDValue);
			}
		}
		$this->query_row->ToPay = $this->appresponse['to_pay'];
	}
	private function finalize_calculations($type) {
		$this->appresponse['status'] = 1;
		if($type == 'c') {
			if($this->query_row->FCouponId != "") {
				$this->appresponse['fdvalue'] = floatval($this->query_row->FDValue);
			}
		} elseif($type == 'f') {
			if($this->query_row->CouponId != "") {
				$this->appresponse['cdvalue'] = floatval($this->query_row->CDValue);
			}
		}
	}
	private function check_fcoupon_validity($fcrow) {
		$today = strtotime("today");
		$to = strtotime($fcrow['ValidTill']);
		if($today > $to) {
			$this->appresponse['emsg'] = 'This coupon was expired';
			return FALSE;
		}
		return TRUE;
	}
	private function is_user_used_fcode($fcrow) {
		$this->load->model('coupons_m');
		$used_count = $this->coupons_m->get_user_fcoupon_usage(intval($fcrow['FCouponId']), intval($this->user_row->UserId));
		if($used_count >= 1) {
			$this->appresponse['emsg'] = 'You have already used this coupon';
			return FALSE;
		}
		return TRUE;
	}
	private function check_order_count($crow) {
		if($this->appresponse['is_logged_in'] == 0) {
			$order_no = 1;
			$is_old_user = FALSE;
		} else {
			$order_no = $this->coupons_m->get_orders_count(intval($this->user_row->UserId));
			$order_no += 1;
			$is_old_user = $this->coupons_m->is_old_user(intval($this->user_row->UserId));
		}
		if(!$is_old_user && intval($crow['CouponId']) <= 3) {
			$this->appresponse['emsg'] = 'These promotional coupons are expired now. Please check our other offers.';
			return FALSE;
		}
		if(intval($crow['CouponId']) <= 3 && $order_no != intval($crow['CouponId'])) {
			if($order_no < intval($crow['CouponId'])) {
				$this->appresponse['emsg'] = 'You can only apply this on your ' . $crow['CouponId'] . ' paid order';
			} elseif($order_no > intval($crow['CouponId'])) {
				$this->appresponse['emsg'] = 'You missed this. You can only use this on your ' . $crow['CouponId'] . ' paid order';
			}
			return FALSE;
		}
		return TRUE;
	}
	private function calculate_c_discount($crow) {
		$purchase_value = floatval($this->input->post('pprice'));
		$coupon_amount = floatval($crow['CAmount']);
		if($crow['CType'] == 'f') {
			$this->appresponse['cdvalue'] = $coupon_amount;
			$this->appresponse['to_pay'] = ($purchase_value - $coupon_amount);
		} elseif($crow['CType'] == 'p') {
			$coupon_amount = get_float_with_two_decimal_places(($purchase_value * $coupon_amount) / 100.0);
			if(isset($crow['MaxDiscount']) && !empty($crow['MaxDiscount']) && $coupon_amount > floatval($crow['MaxDiscount'])) {
				$this->appresponse['cdvalue'] = floatval($crow['MaxDiscount']);
				$this->appresponse['to_pay'] = $purchase_value - $this->appresponse['cdvalue'];
			} else {
				$this->appresponse['cdvalue'] = $coupon_amount;
				$this->appresponse['to_pay'] = ($purchase_value - $coupon_amount);
			}
		}
		$this->query_row->CouponId = $cdata['CouponId'] = intval($crow['CouponId']);
		$this->query_row->ToPay = $cdata['ToPay'] = $this->appresponse['to_pay'];
		$this->query_row->CDValue = $cdata['CDValue'] = $this->appresponse['cdvalue'];
		$this->db->where('QueryToken', $this->query_row->QueryToken)->update('appdata', $cdata);
		$this->finalize_calculations('c');
	}
	private function check_min_purchase($crow) {
		$purchase_value = floatval($this->input->post('pprice'));
		$min_limit = floatval($crow['MinPurchase']);
		if($min_limit > $purchase_value) {
			$this->appresponse['emsg'] = 'Minimum purchase value of Rs. ' . $min_limit . ' is required for this coupon';
			return FALSE;
		}
		return TRUE;
	}
	private function check_user_limit($crow) {
		if($this->appresponse['is_logged_in'] == 1) {
			$user_used_count = $this->coupons_m->get_user_coupon_usage(intval($crow['CouponId']), intval($this->user_row->UserId));
			if($user_used_count >= $crow['PerUserLimit']) {
				$this->appresponse['emsg'] = 'You have already used this coupon and reached the usage limit';
				return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}
	private function check_max_uses($crow) {
		$used_count = $this->coupons_m->get_total_coupon_usage(intval($crow['CouponId']));
		if($used_count >= $crow['MaxUses']) {
			$this->appresponse['emsg'] = 'This coupon usage reached its limit';
			return FALSE;
		}
		return TRUE;
	}
	private function check_validity($crow) {
		$today = strtotime("today");
		$from = strtotime($crow['ValidFrom']);
		$to = strtotime($crow['ValidTill']);
		if($today < $from) {
			$this->appresponse['emsg'] = 'This offer not yet started';
			return FALSE;
		} elseif($today > $to) {
			$this->appresponse['emsg'] = 'This offer was expired';
			return FALSE;
		}
		return TRUE;
	}
	private function check_user_id($crow) {
		if($crow['UserId'] !== NULL && $crow['UserId'] != '') {
			if($this->appresponse['is_logged_in'] == 0) {
				$this->appresponse['emsg'] = 'You have to be logged in to use this coupon';
				return FALSE;
			} else {
				if(intval($this->user_row->UserId) != intval($crow['UserId'])) {
					$this->appresponse['emsg'] = 'This coupon is not for you';
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
				if($this->query_row->ServiceId != '') {
					if(intval($cr['ServiceId']) == intval($this->query_row->ServiceId)) {
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
	private function calculate_prices() {
		if ($this->query_row->Amenities != '') {
			$amtys = explode(',', $this->query_row->Amenities);
		} else {
			$amtys = "";
		}
		if ($this->query_row->Aservices != '') {
			$asers = explode(',', $this->query_row->Aservices);
		} else {
			$asers = "";
		}
		$this->load->model('amenity_m');
		return $this->amenity_m->get_order_est_price($amtys, $this->appresponse['query_data']['service']->ServiceName, $asers, $this->query_row);
	}
	private function insert_additional_locations() {
		$serid = intval($this->query_row->ServiceId);
		if ($serid == 1 || $serid == 2 || $serid == 4) {
			$sc_id = $this->query_row->ScId;
		} elseif ($serid == 3) {
			$sc_ids = explode(',', $this->query_row->ScIds);
			$sc_id = $sc_ids[0];
		}
		$new_city = $this->db->select('CityId')->from('scaddrsplit')->where('ScId', intval($sc_id))->get()->row_array();
		$city_id = intval($new_city['CityId']);
		$this->db->where('QueryToken', $this->query_row->QueryToken)->update('appdata', array('CityId' => $city_id));
		$lc_row = $this->db->select('LocationId')->from('location')->where('LocationName', $this->query_row->LocationName)->where('CityId', $city_id)->get()->row_array();
		if(!$lc_row) {
			$new_lc['LocationName'] = $this->query_row->LocationName;
			$new_lc['Latitude'] = floatval($this->query_row->Latitude);
			$new_lc['Longitude'] = floatval($this->query_row->Longitude);
			$new_lc['CityId'] = $city_id;
			$this->db->insert('location', $new_lc);
		}
	}
	private function temp_img_upload() {
		$count = 0;
		for($i = 1; $i <= 4; $i++) {
			if($this->input->post('uploadImg_' . $i) != '') {
				$file_name = md5(uniqid(mt_rand())) . '.jpg';
				$temp_file_path = realpath(APPPATH . '../html/uploads/temp') . '/' . $file_name;
				file_put_contents($temp_file_path, base64_decode($this->input->post('uploadImg_' . $i)));
				$image_info = filesize($temp_file_path);
				if($image_info < 5242880) {
					$upload_data[$count]['name'] = $file_name;
					$upload_data[$count]['type'] = 'img';
					$count += 1;
				} else {
					unlink($temp_file_path);
				}
			}
		}
		if(isset($upload_data) && count($upload_data) > 0) {
			$this->upload_to_s3temp($upload_data);
			return serialize($upload_data);
		} else {
			return NULL;
		}
	}
	private function upload_to_s3temp($uploaded_media) {
		foreach($uploaded_media as $file) {
			$from_file = realpath(APPPATH . '../html/uploads/temp');
			$from_file = rtrim($from_file, '/').'/';
			$from_file .= $file['name'];
			$to_file = 'temp/';
			$to_file .= $file['name'];
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
	private function validate_phone($ph) {
		if($this->appresponse['is_logged_in'] == 0) {
			return $this->user_m->is_unique_ph($ph);
		} else {
			return FALSE;
		}
	}
	private function is_valid_query_locataion($lat, $lon) {
		$distance = $this->distance(floatval($lat), floatval($lon), 12.983193, 77.590342, 'M');
		if($distance > 50) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	private function distance ($lat1, $lon1, $lat2, $lon2, $unit) {
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);
		if ($unit == "K") {
			return ($miles * 1.609344);
		} elseif ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}
	private function is_query_set() {
		$query_token = $this->input->post('query_token');
		if($query_token) {
			$query_row = $this->query_row;
			if($query_row) {
				if(intval($query_row->ServiceId) == 9 || intval($query_row->ServiceId) == 10 || intval($query_row->ServiceId) == 11) {
					return (bool)$query_row->Latitude && (bool)$query_row->Longitude;
				} else {
					return (bool)$query_row->ServiceId && (bool)$query_row->BikeCompanyId && (bool)$query_row->BikeModelId && (bool)$query_row->ODate && (bool)$query_row->Latitude && (bool)$query_row->Longitude;
				}
			}
		}
		return FALSE;
	}
	private function is_scdata_set() {
		$query_token = $this->input->post('query_token');
		if($query_token) {
			$query_row = $this->query_row;
			if($query_row) {
				if($this->appresponse['is_logged_in'] == 1) {
					$query_data['Phone'] = $this->user_row->Phone;
					$this->db->where('QueryToken', $this->appresponse['query_token'])->update('appdata', $query_data);
					if(intval($query_row->ServiceId) == 3) {
						return (bool)$query_row->ScIds && (bool)$query_row->QType;
					} elseif(intval($query_row->ServiceId) == 4) {
						return TRUE;
					} else {
						return (bool)$query_row->ScId && (bool)$query_row->SlotHour;
					}
				} else {
					if(intval($query_row->ServiceId) == 3) {
						return (bool)$query_row->ScIds && (bool)$query_row->QType && (bool)$query_row->Phone;
					} elseif(intval($query_row->ServiceId) == 4) {
						return (bool)$query_row->Phone;
					} else {
						return (bool)$query_row->ScId && (bool)$query_row->Phone && (bool)$query_row->SlotHour;
					}
				}
			}
		}
		return FALSE;
	}
	private function set_q_data() {
		$this->load->model('bikecompany_m');
		$this->load->model('bikemodel_m');
		if($this->query_row->ServiceId != 9 && $this->query_row->ServiceId != 10) {
			$query_data['ODate'] = date('Y-m-d', strtotime($this->input->post('date_')));
			$query_data['BikeCompanyId'] = intval($this->input->post('company'));
			$query_data['BikeModelId'] = intval($this->input->post('model'));
		} else {
			$query_data['ODate'] = NULL;
			$query_data['BikeCompanyId'] = NULL;
			$query_data['BikeModelId'] = NULL;
		}
		$this->db->where('QueryToken', $this->appresponse['query_token'])->update('appdata', $query_data);
		$this->appresponse['query_data']['bikecompany'] = $this->bikecompany_m->get($query_data['BikeCompanyId']);
		$this->appresponse['query_data']['bikemodel'] = $this->bikemodel_m->get($query_data['BikeModelId']);
		$this->appresponse['query_data']['odate'] = date('d F, Y - l', strtotime($query_data['ODate']));
	}
	private function fetch_sc_data($scs) {
		$query_row = $this->query_row;
		$serid = intval($query_row->ServiceId);
		$table_rows = array(array());
		$row_count = 0;
		if($serid == 9) {
			$static_sc_key = 'petrolbunks_m';
		} elseif($serid == 10) {
			$static_sc_key = 'pucs_m';
		} elseif($serid == 11) {
			$static_sc_key = 'punctures_m';
		} else {
			$date = $query_row->ODate;
			$static_sc_key = 'servicecenter_m';
		}
		$this->load->model($static_sc_key);
		foreach ($scs as $sc) {
			$sc_h_id = $sc['scid'];
			if($this->appresponse['serid'] != 3 && $static_sc_key == 'servicecenter_m' && $this->servicecenter_m->isHoliday($sc_h_id)) {
				continue;
			}
			if(isset($sc['pbecflag']) && $sc['pbecflag'] == 1 && $serid == 10) {
				$sc_key = 'petrolbunks_m';
				$table_rows[$row_count]['pbecflag'] = 1;
				if(!isset($this->$sc_key)) {
					$this->load->model($sc_key);
				}
			} else {
				$sc_key = $static_sc_key;
			}
			if(isset($sc['ScPtFlag']) && $sc['ScPtFlag'] == 1 && $serid == 11) {
				$sc_key = 'servicecenter_m';
				$table_rows[$row_count]['ScPtFlag'] = 1;
				if(!isset($this->$sc_key)) {
					$this->load->model($sc_key);
				}
			} else {
				$sc_key = $static_sc_key;
			}
			$table_rows[$row_count]['ScId'] = $sc['scid'];
			$table_rows[$row_count]['LocationName'] = $this->location_m->location_by_id($sc['lcid']);
			if($serid != 9 && $serid != 10 && $serid != 11) {
				$name_rating = $this->$sc_key->get_name_rating($sc['scid']);
				$table_rows[$row_count]['ScName'] = $name_rating['ScName'];
				$table_rows[$row_count]['Rating'] = $name_rating['Rating'];
				$table_rows[$row_count]['RatersCount'] = $name_rating['RatersCount'];
			} elseif($serid == 11) {
				$name_contact = $this->$sc_key->get_service_provider($sc['scid']);
				$table_rows[$row_count]['ScName'] = convert_to_camel_case($name_contact['ScName']);
				$table_rows[$row_count]['Phone'] = $name_contact['Phone'];
			} else {
				$table_rows[$row_count]['ScName'] = convert_to_camel_case($this->$sc_key->get_service_provider($sc['scid']));
			}
			$table_rows[$row_count]['Distance'] = round((floatval($sc['dist'])), 2);
			if($serid == 3) {
				$table_rows[$row_count]['Latitude'] = $sc['Latitude'];
				$table_rows[$row_count]['Longitude'] = $sc['Longitude'];
				$this->load->model('odetails_m');
				$table_rows[$row_count]['Address'] = $this->odetails_m->get_app_sc_address($sc['scid']);
			}
			if($serid != 10 && $serid != 11) {
				$amenities_list = $this->$sc_key->amenities_by_id($sc['scid']);
				$table_rows[$row_count]['Amenities'] = $this->parse_amenities($amenities_list);
			} elseif($serid == 10) {
				if(isset($sc['pbecflag']) && $sc['pbecflag'] == 1 && $serid == 10) {
					$table_rows[$row_count]['Amenities'] = 'pbunk';
				} else {
					$amenities_list = $this->$sc_key->get_puc_type($sc['scid']);
					$table_rows[$row_count]['Amenities'] = $amenities_list;
				}
			}
			if ($serid == 1 || $serid == 4) {
				$table_rows[$row_count]['Price'] = floatval($this->$sc_key->price_by_id($sc['scid'], $this->query_row->BikeModelId, $this->query_row->ServiceId));
				$table_rows[$row_count]['SlotCount'] = $this->$sc_key->get_slot_count($sc['scid'], $date);
			}
			if($serid == 7) {
				$sc_contact = $this->$sc_key->get_sc_phone($sc['scid']);
				$table_rows[$row_count]['ScPhone'] = $sc_contact['CPhone'];
				$table_rows[$row_count]['ScLand'] = $sc_contact['Landline'];
			}
			if(empty($table_rows[$row_count]['ScPtFlag'])) {
				$table_rows[$row_count]['ScPtFlag'] = 0;
			}
			$table_rows[$row_count]['ScLogo'] = '';
			if ($serid >= 1 && $serid <= 4) {
				$this->load->model('vendor_m');
				$vend_logo = $this->vendor_m->get_vendor_sc_logo($sc['scid']);
				if(isset($vend_logo)) {
					$table_rows[$row_count]['ScLogo'] = get_awss3_url('uploads/scmedia/sc/img/' . $vend_logo);
				}
			}
			$row_count += 1;
		}
		return $table_rows;
	}
	private function check_existing_query() {
		$query_token = $this->input->post('query_token');
		$auth_token = $this->input->get_request_header('user_auth_token', TRUE);
		if($query_token) {
			$this->load->model('service_m');
			$this->load->model('bikecompany_m');
			$this->load->model('bikemodel_m');
			$query_row = $this->appdata_m->get_by(array('QueryToken' => $query_token), TRUE);
			$this->query_row = $query_row;
			if($query_row) {
				$this->appresponse['query_token'] = $query_token;
				$this->appresponse['query_data']['city'] = $this->city_m->get(intval($query_row->CityId));
				if($query_row->ServiceId) {
					$this->appresponse['query_data']['service'] = $this->service_m->get(intval($query_row->ServiceId));
					$this->appresponse['query_data']['area'] = $query_row->LocationName;
				}
				if($query_row->BikeCompanyId && $query_row->BikeModelId && $query_row->ODate) {
					$this->appresponse['query_data']['bikecompany'] = $this->bikecompany_m->get(intval($query_row->BikeCompanyId));
					$this->appresponse['query_data']['bikemodel'] = $this->bikemodel_m->get(intval($query_row->BikeModelId));
					$this->appresponse['query_data']['odate'] = date('d F, Y - l', strtotime($query_row->ODate));
				}
				$this->appresponse['query_data']['servicecenter'] = array();
				if($query_row->ScIds && $query_row->ServiceId == 3) {
					$this->load->model('servicecenter_m');
					$sc_ids = explode(',', $query_row->ScIds);
					foreach ($sc_ids as $sc_id) {
						$this->appresponse['query_data']['servicecenter'][] = $this->servicecenter_m->get(intval($sc_id));
					}
				} elseif ($query_row->ScId && $query_row->ServiceId != 3) {
					$this->load->model('servicecenter_m');
					$this->appresponse['query_data']['servicecenter'][] = $this->servicecenter_m->get(intval($query_row->ScId));
				}
			}
		}
		if($auth_token) {
			$this->user_row = $this->user_m->get_by(array('AuthToken' => $auth_token), TRUE);
			if($this->user_row) {
				$this->appresponse['is_logged_in'] = 1;
				$this->appresponse['userdetails']['UserId'] = $this->user_row->UserId;
				$this->appresponse['userdetails']['UserName'] = $this->user_row->UserName;
				$this->appresponse['userdetails']['Phone'] = $this->user_row->Phone;
				$this->appresponse['userdetails']['Email'] = $this->user_row->Email;
				$this->appresponse['userdetails']['DOB'] = $this->user_row->DOB;
				$this->appresponse['userdetails']['Gender'] = $this->user_row->Gender;
				$this->appresponse['userdetails']['RefCode'] = $this->user_row->RefCode;
			} else {
				$this->appresponse['is_logged_in'] = 0;
			}
		} else {
			$this->appresponse['is_logged_in'] = 0;
		}
	}
	private function parse_amenities($amenities) {
		foreach($amenities as &$amenity) {
			unset($amenity['AmIcon']);
		}
		return $amenities;
	}
	private function fetch_slots($slots) {
		foreach($slots as &$slot) {
			unset($slot['SlotId']);
			unset($slot['ScId']);
			unset($slot['Day']);
			if ($slot['Hour'] > 12) {
				$temp_hr = intval($slot['Hour'] - 12);
				$temp = (intval($slot['Hour'] * 60) % 60);
				if($temp == 0) {
					$temp = '00';
				}
				$slot['SHour'] = $temp_hr . ":" . $temp . " PM";
			} elseif ($slot['Hour'] == 12) {
				$slot['SHour'] = intval($slot['Hour']) . ":00 PM";
			} else {
				$temp = (intval($slot['Hour'] * 60) % 60);
				if($temp == 0) {
					$temp = '00';
				}
				$slot['SHour'] = intval($slot['Hour']) . ":" . $temp . " AM";
			}
			if(isset($slot['EHour']) && $slot['EHour'] != 0) {
				if ($slot['EHour'] > 12) {
					$temp_hr = intval($slot['EHour'] - 12);
					$temp = (intval($slot['EHour'] * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$slot['EHour'] = $temp_hr . ":" . $temp . " PM";
				} elseif ($slot['EHour'] == 12) {
					$slot['EHour'] = intval($slot['EHour']) . ":00 PM";
				} else {
					$temp = (intval($slot['EHour'] * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$slot['EHour'] = intval($slot['EHour']) . ":" . $temp . " AM";
				}
			}
		}
		return $slots;
	}
	private function get_user_feedback_rating_by_oid($OId) {
		$rating = $this->db->select('user_feedback_rating')->from('odetails')->where('OId', $OId)->get()->result_array();
		if(count($rating) > 0) { return intval($rating[0]['user_feedback_rating']); } else { return 0; }
	}
	private function get_user_feedback_rating_by_oid_question($OId) {
		$rating = $this->db->select('ExecFbAnswer')->from('user_feedback')->where('OId', $OId)->where('ExecFbQId', 3)->get()->result_array();
		if(count($rating) > 0) { return intval($rating[0]['ExecFbAnswer']); } else { return 0; }
	}
	private function updateDeviceId() {
		try {
			$iid = $this->input->post('iid'); $imei = $this->input->post('imei'); $cityid = intval($this->appresponse['query_data']['city']->CityId); $devicetype = $this->get_user_agent();
			if(empty($imei) || $imei == '' || $imei == NULL) {
				$imei = NULL;
			}
			if(isset($this->user_row->UserId)) {
				$userid = intval($this->user_row->UserId);
			} else { $userid = NULL; }
			$sql = 'INSERT INTO appusers (IMEI, IId, CityId, DeviceType, UserId) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE IMEI = VALUES(IMEI), IId = VALUES(IId), CityId = VALUES(CityId), DeviceType = VALUES(DeviceType), UserId = VALUES(UserId)';
			$query = $this->db->query($sql, array($imei, $iid, $cityid, $devicetype, $userid));
			if($query) { return TRUE; } else { return FALSE; }
		} catch (Exception $e) {
			return FALSE;
		}
	}
	private function get_user_agent() {
		$this->load->library('user_agent', NULL, 'agent');
		if($this->agent->is_mobile('iphone')) {
			return 'iphone';
		} elseif($this->agent->is_mobile('android')) {
			return 'android';
		} else {
			return 'mob';
		}
	}
}