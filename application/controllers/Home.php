<?php
class Home extends G6_Controller {
	public function __construct() {
		parent::__construct();
	}
	public function index () {
		redirect('/user');
	}
	public function user_logout($url) {
		$code = 'user_m';
		$this->logout_user($code, $url);
	}
	public function vendor_logout($url) {
		$code = 'vendor_m';
		$this->logout_user($code, $url);
	}
	public function biz_logout($url) {
		$code = 'tieups_m';
		$this->logout_user($code, $url);
	}
	public function prv_logout($url) {
		$code = 'prvendor_m';
		$this->logout_user($code, $url);
	}
	public function admin_logout($url) {
		$code = 'admin_m';
		$this->logout_user($code, $url);
	}
	public function gpaymtlink($OId = NULL) {
		$OId = decrypt_oid($OId);
		if($this->is_payment_not_done($OId)) {
			$cookie1 = array(
				'name'   => 'oid',
				'value'  => $OId,
				'expire' => '3600',
				'secure' => FALSE
			);
			$this->input->set_cookie($cookie1);
			$cookie2 = array(
				'name'   => 'is_porder_trxn',
				'value'  => 1,
				'expire' => '3600',
				'secure' => FALSE
			);
			$this->input->set_cookie($cookie2);
			$this->data['oid'] = $OId;
			$this->data['odetails'] = $this->db->select('odetails.*, user.UserName, user.Phone, user.Email')->from('odetails')->join('user', 'user.UserId = odetails.UserId')->where('odetails.OId', $OId)->limit(1)->get()->row();
			$this->data['disc_amount'] = $this->get_total_discount_amount($OId);
			$this->data['tot_conv'] = $this->get_total_conv_amount($OId);
			$this->data['tot_billed'] = $this->get_total_billed_amount($OId) - $this->data['tot_conv'];
			$this->data['tot_paid'] = $this->get_total_paid_amount($OId);
			$this->data['to_be_paid'] = $this->data['tot_billed'] + $this->data['tot_conv'] - $this->data['tot_paid'] - $this->data['disc_amount'];
			if($this->data['to_be_paid'] < 0.01) {
				$this->data['to_be_paid'] = 0;
			}
			$this->load->view('user/pinview', $this->data);
		} else {
			redirect('https://www.gear6.in/');
		}
	}
	public function vlogin($reg_flag = 0) {
		$this->load->model('vendor_m');
		if($this->vendor_m->loggedin()) {
			redirect('/vendor');
		} else {
			if ($_POST && $this->input->post('password') != '' && $this->input->post('phone')) {
				$login_vendor = $this->vendor_m->login();
				if($login_vendor  == 0) {
					$this->data['login_error_message'] = "Incorrect Vendor Type / Username / Password combination";
					$this->load->view('vendor/vlogin', $this->data);
				} elseif($login_vendor == 2) {
					$this->data['login_error_message'] = "Your account is not yet activated.<br>Please wait for the verification process to complete.";
					$this->load->view('vendor/vlogin', $this->data);
				} elseif($login_vendor == 1) {
					redirect('/vendor');
				}
			} elseif($_POST) {
				$this->data['login_error_message'] = "All fields are mandatory";
				$this->load->view('vendor/vlogin', $this->data);
			} else {
				if($reg_flag == 1) {
					$this->data['reg_success_msg'] = "You have successfully registered.<br>Your credentials have been sent to your mobile and email.<br>Your can access your account only after verification.";
				}
				$this->load->view('vendor/vlogin', $this->data);
			}
		}
	}
	public function blogin() {
		$this->load->model('tieups_m');
		if($this->tieups_m->loggedin()) {
			redirect('/business');
		} else {
			if ($_POST && $this->input->post('password') != '' && $this->input->post('phone') != '') {
				$login_vendor = $this->tieups_m->login();
				if($login_vendor  == 0) {
					$this->data['login_error_message'] = "Incorrect Username / Password combination";
					$this->load->view('business/blogin', $this->data);
				} elseif($login_vendor == 2) {
					$this->data['login_error_message'] = "Your account is not yet activated.<br>Please wait for the verification process to complete.";
					$this->load->view('business/blogin', $this->data);
				} elseif($login_vendor == 1) {
					redirect('/business');
				}
			} elseif($_POST) {
				$this->data['login_error_message'] = "All fields are mandatory";
				$this->load->view('business/blogin', $this->data);
			} else {
				$this->load->view('business/blogin', $this->data);
			}
		}
	}
	public function prlogin() {
		$this->load->model('prvendor_m');
		if($this->prvendor_m->loggedin()) {
			redirect('/prvendor');
		} else {
			if ($_POST && $this->input->post('password') != '' && $this->input->post('phone') != '') {
				$login_vendor = $this->prvendor_m->login();
				if($login_vendor  == 0) {
					$this->data['login_error_message'] = "Incorrect Username / Password combination";
					$this->load->view('prvendor/prlogin', $this->data);
				} elseif($login_vendor == 1) {
					redirect('/prvendor');
				}
			} elseif($_POST) {
				$this->data['login_error_message'] = "All fields are mandatory";
				$this->load->view('prvendor/prlogin', $this->data);
			} else {
				$this->load->view('prvendor/prlogin', $this->data);
			}
		}
	}
	public function vregister() {
		$this->load->model('vendor_m');
		if($this->vendor_m->loggedin()) {
			redirect('/vendor');
		} else {
			$this->load->model('amenity_m');
			$this->load->model('bikecompany_m');
			$this->load->model('city_m');
			$this->data['cities'] = $this->city_m->get_by(array('isEnabled' => 1));
			$this->data['amenities'] = $this->amenity_m->get_by(array('AmCode' => 2));
			$this->data['sc_companies'] = json_encode($this->bikecompany_m->get_sc_companies());
			$this->load->view('vendor/vregister', $this->data);
		}
	}
	public function getCityLocations() {
		if($this->input->post('city') != "") {
			$this->load->model('location_m');
			echo json_encode($this->location_m->locations_for_sc($this->input->post('city')));
		}
	}
	public function send_fgototp_vendor() {
		if($_POST) {
			$this->load->model('otp_m');
			$this->load->model('vendor_m');
			$data['err'] = FALSE;
			if(!$this->vendor_m->is_unique_ph($this->input->post('phNum'))) {
				$otp = $this->otp_m->is_otp_inserted($this->input->post('phNum'));
				if(!$otp) {
					$otp = $this->otp_m->insert_otp($this->input->post('phNum'));
				}
				$this->send_sms_request_to_api($this->input->post('phNum'), "Your OTP is " . $otp . " for your mobile confirmation at gear6.in");
			} else {
				$data['err'] = 'Sorry, this phone is not registered';
			}
			echo json_encode($data);
		}
	}
	public function send_fgototp_biz() {
		if($_POST) {
			$this->load->model('otp_m');
			$this->load->model('tieups_m');
			$data['err'] = FALSE;
			if(!$this->tieups_m->is_unique_ph($this->input->post('phNum'))) {
				$otp = $this->otp_m->is_otp_inserted($this->input->post('phNum'));
				if(!$otp) {
					$otp = $this->otp_m->insert_otp($this->input->post('phNum'));
				}
				$this->send_sms_request_to_api($this->input->post('phNum'), "Your OTP is " . $otp . " for your mobile confirmation at gear6.in");
			} else {
				$data['err'] = 'Sorry, this phone is not registered';
			}
			echo json_encode($data);
		}
	}
	public function send_fgototp_prv() {
		if($_POST) {
			$this->load->model('otp_m');
			$this->load->model('prvendor_m');
			$data['err'] = FALSE;
			if(!$this->prvendor_m->is_unique_ph($this->input->post('phNum'))) {
				$otp = $this->otp_m->is_otp_inserted($this->input->post('phNum'));
				if(!$otp) {
					$otp = $this->otp_m->insert_otp($this->input->post('phNum'));
				}
				$this->send_sms_request_to_api($this->input->post('phNum'), "Your OTP is " . $otp . " for your mobile confirmation at gear6.in");
			} else {
				$data['err'] = 'Sorry, this phone is not registered';
			}
			echo json_encode($data);
		}
	}
	public function check_fgototp_vendor() {
		if($_POST) {
			$this->load->model('otp_m');
			echo $this->otp_m->check_otp($this->input->post('otp'), $this->input->post('phNum'));
		}
	}
	public function check_fgototp_biz() {
		if($_POST) {
			$this->load->model('otp_m');
			echo $this->otp_m->check_otp($this->input->post('otp'), $this->input->post('phNum'));
		}
	}
	public function check_fgototp_prv() {
		if($_POST) {
			$this->load->model('otp_m');
			echo $this->otp_m->check_otp($this->input->post('otp'), $this->input->post('phNum'));
		}
	}
	public function resetpwd_vendor() {
		if($_POST) {
			$this->load->model('vendor_m');
			$this->vendor_m->reset_password($this->input->post('rp_pwd1'), $this->input->post('rp_pwd2'), $this->input->post('rp_phone'));
			redirect('/vendor');
		}
	}
	public function resetpwd_biz() {
		if($_POST) {
			$this->load->model('tieups_m');
			$this->tieups_m->reset_password($this->input->post('rp_pwd1'), $this->input->post('rp_pwd2'), $this->input->post('rp_phone'));
			redirect('/business');
		}
	}
	public function resetpwd_prv() {
		if($_POST) {
			$this->load->model('prvendor_m');
			$this->prvendor_m->reset_password($this->input->post('rp_pwd1'), $this->input->post('rp_pwd2'), $this->input->post('rp_phone'));
			redirect('/prvendor');
		}
	}
	public function validate_phones() {
		if($_POST) {
			$this->load->model('vendor_m');
			$output = FALSE;
			if($this->vendor_m->is_unique_ph($this->input->post('phone'))) {
				$output = TRUE;
			}
			echo $output;
		}
	}
	public function reg_vendor() {
		if($_POST) {
			$this->load->model('location_m');
			if($this->input->post('sctype') == 'sc') {
				$data['ScName'] = $this->input->post('scname');
				$data['Owner'] = $this->input->post('oname');
				$data['Rating'] = 4.5;
				$data['RatersCount'] = 0;
				$data['DefaultSlots'] = intval($this->input->post('defslots'));
				$data['SlotDuration'] = intval($this->input->post('slotInterval'));
				$data['SlotType'] = intval($this->input->post('slottype'));
				$this->db->insert('servicecenter', $data);
				$scid = $this->db->insert_id();
				$data = array();
				$data['BikeCompanyId'] = $this->input->post('company');
				$data['ScId'] = $scid;
				$this->db->insert('MapScBc', $data);
				$data = array();
				$data['ScId'] = $scid;
				$data['Phone'] = $this->input->post('phone');
				$data['CPhone'] = $this->input->post('vphone');
				$data['CPerson'] = $this->input->post('cperson');
				$data['AltPhone'] = $this->input->post('altphone');
				$data['Landline'] = $this->input->post('landline');
				$data['Email'] = $this->input->post('email');
				$this->db->insert('sccontact', $data);
				$data = array();
				$data['ScId'] = $scid;
				$data['AddrLine1'] = $this->input->post('adln1');
				$data['AddrLine2'] = $this->input->post('adln2');
				$data['CityId'] = $this->input->post('city');
				$data['LocationId'] = intval($this->location_m->location_id_by_name($this->input->post('area'))['LocationId']);
				$data['Landmark'] = $this->input->post('landmark');
				$data['PinCode'] = $this->input->post('pincode');
				$this->db->insert('scaddrsplit', $data);
				$addrsplitid = $this->db->insert_id();
				$data = array();
				$data['ScAddrSplitId'] = $addrsplitid;
				$data['Latitude'] = $this->input->post('lati');
				$data['Longitude'] = $this->input->post('longi');
				$data['Addr'] = $merged_addr = $this->input->post('adln1') . ' ' . $this->input->post('adln2') . ' ' . $this->input->post('landmark') . ' ' . $this->input->post('area');
				$this->db->insert('scaddr', $data);
				$vdata = array(array());
				$vdata[0]['ScId'] = $scid;
				$vdata[0]['VendorName'] = $this->input->post('oname');
				$vdata[0]['Phone'] = $this->input->post('phone');
				$vdata[0]['AltPhone'] = $this->input->post('altphone');
				$vdata[0]['Landline'] = $this->input->post('landline');
				$vdata[0]['Email'] = $this->input->post('email');
				$vdata[0]['Pwd'] = generateUniqueString(8);
				$vdata[0]['Salt'] = generate_hash(generateUniqueString(8));
				$vdata[0]['UserPrivilege'] = 'Admin';
				$vdata[0]['Address'] = $merged_addr;
				$vdata[1]['ScId'] = $scid;
				$vdata[1]['VendorName'] = $this->input->post('cperson');
				$vdata[1]['Phone'] = $this->input->post('vphone');
				$vdata[1]['AltPhone'] = $this->input->post('valtphone');
				$vdata[1]['Landline'] = $this->input->post('vlandline');
				$vdata[1]['Email'] = $this->input->post('vemail');
				$vdata[1]['Pwd'] = generateUniqueString(8);
				$vdata[1]['Salt'] = generate_hash(generateUniqueString(8));
				$vdata[1]['UserPrivilege'] = 'Super User';
				$vdata[1]['Address'] = $merged_addr;
				$this->db->insert_batch('vendor', $vdata);
				$this->send_sms_request_to_api($vdata[0]['Phone'], 'You are successfully registered at gear6.in/vendor. Below are your login details. Login Id: ' . $vdata[0]['Phone'] . ' Password: ' . $vdata[0]['Pwd']);
				$this->send_sms_request_to_api($vdata[1]['Phone'], 'You are successfully registered at gear6.in/vendor. Below are your login details. Login Id: ' . $vdata[1]['Phone'] . ' Password: ' . $vdata[1]['Pwd']);
				$this->upload_scmedia($scid, 'sc', 'info');
				redirect('/home/vlogin/1');
			} elseif($this->input->post('sctype') == 'pb') {
				$data['PBName'] = $this->input->post('scname');
				$data['ServiceProvider'] = $this->input->post('company');
				$data['Owner'] = $this->input->post('oname');
				$data['License'] = $this->input->post('plicnum');
				$data['Phone'] = $this->input->post('phone');
				$data['AltPhone'] = $this->input->post('altphone');
				$data['Landline'] = $this->input->post('landline');
				$data['Email'] = $this->input->post('email');
				$data['AddrLine1'] = $this->input->post('adln1');
				$data['AddrLine2'] = $this->input->post('adln2');
				$data['CityId'] = $this->input->post('city');
				$data['Pwd'] = generateUniqueString(8);
				$data['Salt'] = generate_hash(generateUniqueString(8));
				$data['LocationId'] = intval($this->location_m->location_id_by_name($this->input->post('area'))['LocationId']);
				$data['Landmark'] = $this->input->post('landmark');
				$data['PinCode'] = $this->input->post('pincode');
				$data['Latitude'] = $this->input->post('lati');
				$data['Longitude'] = $this->input->post('longi');
				if($this->input->post('pprice') != '') {
					$data['PetrolPrice'] = floatval($this->input->post('pprice'));
				}
				if($this->input->post('dprice') != '') {
					$data['DieselPrice'] = floatval($this->input->post('dprice'));
				}
				if($this->input->post('lprice') != '') {
					$data['LPGPrice'] = floatval($this->input->post('lprice'));
				}
				$this->db->insert('petrolbunks', $data);
				$pbid = $this->db->insert_id();
				$amtys = $this->input->post('amenity');
				if(count($amtys) > 0) {
					$count = 0;
					foreach($amtys as $amty) {
						$amdata[$count]['PBId'] = $pbid;
						$amdata[$count]['AmId'] = intval($amty);
						if($this->input->post('aprice_' . $amty)) {
							$amdata[$count]['Price'] = $this->input->post('aprice_' . $amty);
						} else {
							$amdata[$count]['Price'] = NULL;
						}
						$amdata[$count]['AmDesc'] = $this->input->post('amdesc_' . $amty);
						$count += 1;
					}
					$this->db->insert_batch('pbamprice', $amdata);
				}
				$data = array();
				$timings = $this->input->post('tmngdays');
				$count = 0;
				foreach($timings as $timing) {
					$data[$count]['ScId'] = $pbid;
					$data[$count]['Day'] = $timing;
					$data[$count]['STime'] = $this->parseTime(floatval($this->input->post('stime_' . $timing)));
					$data[$count]['ETime'] = $this->parseTime(floatval($this->input->post('etime_' . $timing)));
					$data[$count]['ScType'] = 'pb';
					$count += 1;
				}
				$this->db->insert_batch('pbectimings', $data);
				$this->upload_scmedia($pbid, 'pb', 'info');
				redirect('/vendor');
			} elseif($this->input->post('sctype') == 'ec') {
				$data['ECName'] = $this->input->post('scname');
				$data['ContactPerson'] = $this->input->post('oname');
				$data['License'] = $this->input->post('plicnum');
				$data['Phone'] = $this->input->post('phone');
				$data['AltPhone'] = $this->input->post('altphone');
				$data['Landline'] = $this->input->post('landline');
				$data['Email'] = $this->input->post('email');
				$data['AddrLine1'] = $this->input->post('adln1');
				$data['AddrLine2'] = $this->input->post('adln2');
				$data['CityId'] = $this->input->post('city');
				$data['Pwd'] = generateUniqueString(8);
				$data['Salt'] = generate_hash(generateUniqueString(8));
				$data['LocationId'] = intval($this->location_m->location_id_by_name($this->input->post('area'))['LocationId']);
				$data['Landmark'] = $this->input->post('landmark');
				$data['PinCode'] = $this->input->post('pincode');
				$data['Latitude'] = $this->input->post('lati');
				$data['Longitude'] = $this->input->post('longi');
				$data['ECType'] = $this->input->post('ectype');
				$data['FuelType'] = $this->input->post('ftype');
				$data['Code'] = $this->input->post('estncode');
				$data['License'] = $this->input->post('elicnum');
				$data['LicenseExpiry'] = date("Y-m-d", strtotime($this->input->post('elicdate')));
				$this->db->insert('pucs', $data);
				$ecid = $this->db->insert_id();
				$data = array();
				$count = 0;
				if($this->input->post('2w_p') != '') {
					$data[$count]['ScId'] = $ecid;
					$data[$count]['FuelType'] = 'petrol';
					$data[$count]['VehicleType'] = '2w';
					$data[$count]['Price'] = floatval($this->input->post('2w_p'));
					$data[$count]['ScType'] = 'ec';
					$count += 1;
				}
				if($this->input->post('4w_p') != '') {
					$data[$count]['ScId'] = $ecid;
					$data[$count]['FuelType'] = 'petrol';
					$data[$count]['VehicleType'] = '4w';
					$data[$count]['Price'] = floatval($this->input->post('4w_p'));
					$data[$count]['ScType'] = 'ec';
					$count += 1;
				}
				if($this->input->post('4w_d') != '') {
					$data[$count]['ScId'] = $ecid;
					$data[$count]['FuelType'] = 'diesel';
					$data[$count]['VehicleType'] = '4w';
					$data[$count]['Price'] = floatval($this->input->post('4w_d'));
					$data[$count]['ScType'] = 'ec';
					$count += 1;
				}
				if($count != 0) {
					$this->db->insert_batch('ecprice', $data);
				}
				$data = array();
				$timings = $this->input->post('tmngdays');
				$count = 0;
				foreach($timings as $timing) {
					$data[$count]['ScId'] = $ecid;
					$data[$count]['Day'] = $timing;
					$data[$count]['STime'] = $this->parseTime(floatval($this->input->post('stime_' . $timing)));
					$data[$count]['ETime'] = $this->parseTime(floatval($this->input->post('etime_' . $timing)));
					$data[$count]['ScType'] = 'ec';
					$count += 1;
				}
				$this->db->insert_batch('pbectimings', $data);
				$this->upload_scmedia($ecid, 'ec', 'info');
				redirect('/vendor');
			} elseif($this->input->post('sctype') == 'pt') {
				$data['ScName'] = $this->input->post('scname');
				$data['OwnerName'] = $this->input->post('oname');
				$data['Phone'] = $this->input->post('phone');
				$data['AltPhone'] = $this->input->post('altphone');
				$data['Landline'] = $this->input->post('landline');
				$data['Email'] = $this->input->post('email');
				$data['AddrLine1'] = $this->input->post('adln1');
				$data['AddrLine2'] = $this->input->post('adln2');
				$data['CityId'] = $this->input->post('city');
				$data['Pwd'] = generateUniqueString(8);
				$data['Salt'] = generate_hash(generateUniqueString(8));
				$data['LocationId'] = intval($this->location_m->location_id_by_name($this->input->post('area'))['LocationId']);
				$data['Landmark'] = $this->input->post('landmark');
				$data['PinCode'] = $this->input->post('pincode');
				$data['Latitude'] = $this->input->post('lati');
				$data['Longitude'] = $this->input->post('longi');
				$data['Price'] = $this->input->post('ptprice');
				$this->db->insert('punctures', $data);
				$ptid = $this->db->insert_id();
				$data = array();
				$timings = $this->input->post('tmngdays');
				$count = 0;
				foreach($timings as $timing) {
					$data[$count]['ScId'] = $ptid;
					$data[$count]['Day'] = $timing;
					$data[$count]['STime'] = $this->parseTime(floatval($this->input->post('stime_' . $timing)));
					$data[$count]['ETime'] = $this->parseTime(floatval($this->input->post('etime_' . $timing)));
					$data[$count]['ScType'] = 'pt';
					$count += 1;
				}
				$this->db->insert_batch('pbectimings', $data);
				$this->upload_scmedia($ptid, 'pt', 'info');
				redirect('/vendor');
			} else {
				redirect('/home/vregister');
			}
		}
	}
	private function logout_user($code, $url) {
		$this->load->model($code);
		$this->$code->logout();
		$redirect_url = base64_decode($url);
		redirect($redirect_url);
	}
	private function upload_scmedia($scid, $sctype, $mediatype) {
		$this->load->library('upload', $this->upload_config());
		$count = 0;
		for($i = 0; $i <= 6; $i++) {
			if($this->upload->do_upload('uploadImage_' . $i)) {
				$file_data = $this->upload->data();
				$upload_data[$count]['ScId'] = $scid;
				$upload_data[$count]['ScType'] = $sctype;
				if($i == 0) {
					$upload_data[$count]['MediaType'] = 'logo';
				} else {
					$upload_data[$count]['MediaType'] = $mediatype;
				}
				$upload_data[$count]['MediaOrder'] = $i;
				$upload_data[$count]['FileData'] = $file_data['file_name'];
				if(boolval($file_data['is_image'])) {
					$upload_data[$count]['FileType'] = 'img';
				} else {
					$upload_data[$count]['FileType'] = 'vid';
				}
				$this->move_uploaded_file($file_data['file_name'], $upload_data[$count]['FileType'], $sctype);
				$count += 1;
			}
		}
		if(isset($upload_data) && count($upload_data) > 0) {
			$this->db->insert_batch('scmedia', $upload_data);
		}
	}
	private function upload_config() {
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['upload_path'] = realpath(APPPATH . '../html/uploads/temp');
		$config['encrypt_name'] = TRUE;
		$config['remove_spaces'] = TRUE;
		$config['max_size'] = '5120';
		$config['overwrite'] = FALSE;
		return $config;
	}
	private function move_uploaded_file($fname, $ftype, $path) {
		$from_file = realpath(APPPATH . '../html/uploads/temp');
		$from_file = rtrim($from_file, '/').'/';
		$from_file .= $fname;
		$to_file = 'uploads/scmedia/' . $path . '/';
		$to_file .= $ftype . '/' . $fname;
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
	private function parseTime($i) {
		$int_part = floor($i);
		$decimal_part = $i - ($int_part);
		if($int_part < 12) {
			if($int_part == 0) {
				$int_part = 12;
			}
			if($int_part < 10) {
				$time = '0' . $int_part . ':';
			} else {
				$time = $int_part . ':';
			}
			if ($decimal_part < 0.01) {
				$time .= '00 AM';
			} else {
				$time .= '30 AM';
			}
		} elseif($int_part == 12) {
			$time = '12:';
			if ($decimal_part < 0.01) {
				$time .= '00 PM';
			} else {
				$time .= '30 PM';
			}
		} else {
			if(($int_part - 12) < 10) {
				$time = '0' . ($int_part - 12) . ':';
			} else {
				$time = ($int_part - 12) . ':';
			}
			if ($decimal_part < 0.01) {
				$time .= '00 PM';
			} else {
				$time .= '30 PM';
			}
		}
		return $time;
	}
	public function sendCronAdminReminder() {
		$this->db->select('*')->from('admin_reminder');
		$this->db->where('is_enabled', 1);
		$this->db->where('timestamp >= ', date("Y-m-d H:i:s", (strtotime("now") - 300)));
		$this->db->where('timestamp <= ', date("Y-m-d H:i:s", strtotime("now")));
		$result = $this->db->get()->result_array();
		$reminder = array(); $gcm = array(); $phone = array(); $disable = array();
		if (count($result) > 0) {
			foreach ($result as $row) {
				$disable[] = $row['id']; $remind_to = explode(", ", $row['remind_to']);
				if($row['user_type'] == 'Admin') {
					$reminder = $this->getAdminsByArray($remind_to);
					$tag = 'reminder';
					$tagname = 'tag';
				} elseif ($row['user_type'] == 'Executive') {
					$reminder = $this->getExecutivesByArray($remind_to);
					$tag = 'home';
					$tagname = 'screen';
				}
				foreach ($reminder as $remind) {
					if($remind['GCMId'] != NULL) {
						$gcm[] = $remind['GCMId'];
					}
					$phone[] = $remind['Phone'];
				}
				if(count($gcm) > 0) {
					$message = array($tagname => $tag, "title" => "Reminder - DO THIS NOW", "message" => $row['description']);
					$this->send_gcm_request($gcm, $message);
				}
				if($row['send_sms'] == '1' && count($phone) > 0) {
					foreach ($phone as $ph) {
						$this->send_sms_request_to_api($ph, $row['description']);
					}
				}
			}
			$this->db->where_in('id', $disable); $admin_reminder['is_enabled'] = 0;
			$this->db->update('admin_reminder', $admin_reminder);
		}
		echo TRUE;
		exit;
	}
	private function getAdminsByArray($remind_to) {
		$result = $this->db->select('Phone, GCMId')->from('admin')->where_in('AdminId', $remind_to)->get()->result_array();
		if (count($result) > 0) { return $result; } else { return array(); }
	}
	private function getExecutivesByArray($remind_to) {
		$result = $this->db->select('Phone, GCMId')->from('executive')->where_in('ExecId', $remind_to)->get()->result_array();
		if (count($result) > 0) { return $result; } else { return array(); }
	}
	public function sendCronNotificationToAdminForDelayedOrder() {
		$this->db->select('odetails.OId, odetails.ODate, oservicedetail.ScId');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where('odetails.ODate <', date("Y-m-d", strtotime("now")));
		$this->db->where("((odetails.ServiceId = '1' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '2' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '4' AND status.Order < '3' AND status.Order >= '0'))", NULL, FALSE);
		$query = $this->db->get(); $oids = array(); $result = $query->result_array();
		if (count($result) > 0) {
			foreach($result as $row) {
				$oids[] = $row['OId'];
			}
			$adminNotifyFlag['new_delayed_order'] = 1; $adminNotifyFlag['new_delayed_order_dismissed'] = 0;
			$this->db->where_in('OId', $oids); $this->db->update('admin_notification_flags', $adminNotifyFlag);
		}
		echo TRUE;
		exit;
	}
	public function sendCronMessageToUser() {
		$this->db->select('user.Phone AS userPhone, user.UserName, odetails.OId, odetails.odate, service.ServiceName, bikecompany.BikeCompanyName, bikemodel.BikeModelName, executive.ExecName, executive.Phone AS executivePhone, odetails.SlotHour');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId');
		$this->db->join('user', 'user.UserId = odetails.UserId');
		$this->db->join('execassigns', 'execassigns.OId = odetails.OId');
		$this->db->join('executive', 'executive.ExecId = execassigns.ExecId');
		$this->db->where('odate', date("Y-m-d", strtotime("now")));
		$this->db->order_by('odetails.OId', 'asc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			foreach($result as $row) {
				$execData[$row["userPhone"]][] = $row;
			}
			foreach($execData as $ph=>$user) {
				$userName = $user[0]["UserName"];
				$serviceName = $user[0]["ServiceName"];
				$bikeCompanyName = $user[0]["BikeCompanyName"];
				$bikeModelName = $user[0]["BikeModelName"];
				$date = $user[0]["odate"];
				$orderId = $user[0]["OId"];
				$slot = $user[0]["SlotHour"];				
				if ($slot > 12) {
					$temp_hr = intval($slot - 12);
					$temp = (intval($slot * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$slot_hour = $temp_hr . ":" . $temp . " PM";
				} elseif ($slot == 12) {
					$slot_hour = intval($slot) . ":00 PM";
				} else {
					$temp = (intval($slot * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$slot_hour = intval($slot) . ":" . $temp . " AM";
				}
				$text = "Hi " . $userName . "! Your Gear6 Order (" . $orderId . ") for " . $serviceName . " of " . 
				$bikeCompanyName . " " . $bikeModelName . " on " . $date . " will be picked up today for service by our executives ";
				foreach($user as $exec) {
					$text = $text . $exec["ExecName"] . " (" . $exec["executivePhone"] . "), ";
				}
				$text = substr($text, 0, strlen($text) - 2);
				$text = $text . " at " . $slot_hour;
				$this->send_sms_request_to_api($ph, $text);
			}
		}
		echo TRUE;
		exit;
	}
	public function sendCronMessageToExecutive() {
		$this->db->select('user.Phone AS userPhone, user.UserName, odetails.OId, odetails.odate, service.ServiceName, bikecompany.BikeCompanyName, bikemodel.BikeModelName, executive.ExecName, executive.Phone AS executivePhone, odetails.SlotHour');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId');
		$this->db->join('user', 'user.UserId = odetails.UserId');
		$this->db->join('execassigns', 'execassigns.OId = odetails.OId');
		$this->db->join('executive', 'executive.ExecId = execassigns.ExecId');
		$this->db->where('odate', date("Y-m-d", strtotime("now")));
		$this->db->order_by('odetails.OId', 'asc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			foreach($result as $row) {
				$execData[$row["executivePhone"]][] = $row;
			}
			foreach($execData as $ph => $exec) {
				$executiveName = $exec[0]["ExecName"];
				$serviceName = $exec[0]["ServiceName"];
				$bikeCompanyName = $exec[0]["BikeCompanyName"];
				$bikeModelName = $exec[0]["BikeModelName"];
				$date = $exec[0]["odate"];
				$orderId = $exec[0]["OId"];
				$slot = $exec[0]["SlotHour"];
				if ($slot > 12) {
					$temp_hr = intval($slot - 12);
					$temp = (intval($slot * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$slot_hour = $temp_hr . ":" . $temp . " PM";
				} elseif ($slot == 12) {
					$slot_hour = intval($slot) . ":00 PM";
				} else {
					$temp = (intval($slot * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$slot_hour = intval($slot) . ":" . $temp . " AM";
				}
				$text = "Hi " . $executiveName . "! You will pick up Gear6 Order (" . $orderId . ") for " . $serviceName . " of " . 
				$bikeCompanyName . " " . $bikeModelName . " today (" . $date . ") from ";
				foreach($exec as $user) {
					$text = $text . $user["UserName"] . " (" . $user["userPhone"] . ") at " . $slot_hour . ", ";
				}
				$text = substr($text, 0, strlen($text) - 2);
				$this->send_sms_request_to_api($ph, $text);
			}
		}
		echo TRUE;
		exit;
	}
	public function sendVendorEmail() {
		if($_POST) {
			$subject = $_POST['message_type'] . 'from Gear6';
			$this->db->select('Email, CPerson');
			$this->db->from('sccontact');
			$this->db->where_in('ScId', $_POST['service_centers']);
			$query = $this->db->get();
			$result = $query->result_array();
			if(count($result) > 0) {
				foreach ($result as $row) {
					unset($this->data);
					$email = $row["Email"];
					$this->data['name'] = $row["CPerson"];
					$this->data['comments'] = $_POST['comments'];
					$this->send_gear6_email($email, $subject, 'writeToVendor', $this->data);
				}
			}
			redirect('/admin/vendors/writeto');
		}
	}
	public function sendVendorSMS() {
		if($_POST) {
			$this->db->select('Phone');
			$this->db->from('sccontact');
			$this->db->where_in('ScId', $_POST['service_centers']);
			$query = $this->db->get();
			$result = $query->result_array();
			if(count($result) > 0) {
				foreach ($result as $row) {
					unset($this->data);
					$phone = $row["Phone"];
					$this->send_sms_request_to_api($phone, $_POST['comments']);
				}
			}
			redirect('/admin/vendors/writeto');
		}
	}
	public function sendUserEmail() {
		if($_POST) {
			$subject = $_POST['message_type'] . 'from Gear6';
			$user_id = $_POST['user_id'];
			$this->db->select('Email');
			$this->db->from('user');
			if(!$user_id || $user_id == "" || strlen($user_id) == 0) {
				//Do Nothing
			} else {
				$this->db->where('UserId', $user_id);	
			}
			$query = $this->db->get();
			$result = $query->result_array();
			if(count($result) > 0) {
				foreach ($result as $row) {
					unset($this->data);
					$email = $row["Email"];
					$this->data['name'] = $row["UserName"];
					$this->data['comments'] = $_POST['comments'];
					$this->send_gear6_email($email, $subject, 'writeToUser', $this->data);
				}
			}
			redirect('/admin/users/writeto');
		}
	}
	public function sendUserSMS() {
		if($_POST) {
			$user_id = $_POST['user_id'];
			$this->db->select('Phone');
			$this->db->from('user');
			if(!$user_id || $user_id == "" || strlen($user_id) == 0) {
				//Do Nothing
			} else {
				$this->db->where('UserId', $user_id);	
			}
			$query = $this->db->get();
			$result = $query->result_array();
			if(count($result) > 0) {
				foreach ($result as $row) {
					unset($this->data);
					$phone = $row["Phone"];
					$this->send_sms_request_to_api($phone, $_POST['comments']);
				}
			}
			redirect('/admin/users/writeto');
		}
	}
	public function sendCronUserReminders() {
		$this->db->select('user.UserName, user.Phone, user.Email, user_reminders.BikeRegNum, user_reminders.Date, reminder_settings.*');
		$this->db->from('user_reminders');
		$this->db->join('user', 'user.UserId = user_reminders.UserId', 'left');
		$this->db->join('reminder_types', 'reminder_types.reminder_id = user_reminders.reminder_id', 'left');
		$this->db->join('reminder_settings', 'reminder_types.reminder_id = user_reminders.reminder_id', 'left');
		$this->db->where('user_reminders.isEnabled', 1);
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) > 0) {
			foreach ($results as $result) {
				if((!isset($result['daysBefore']) || $result['daysBefore'] == NULL) && (isset($result['daysAfter']) && $result['daysAfter'] != NULL)) {
					$date = date("Y-m-d", strtotime('-' . $result['daysBefore'] . " day", strtotime($result['Date'])));
					if($date == date("Y-m-d", strtotime("now"))) {
						unset($this->data);	$subject = 'Reminder from Gear6'; $email = $result["Email"];
						$this->data['name'] = $result["UserName"]; $phone = $result["Phone"]; $this->data['comments'] = $result['emailMessage'];
						$this->send_gear6_email($email, $subject, 'writeToUser', $this->data);
						$this->send_sms_request_to_api($phone, $result['smsMessage']);
					}
				}
				if((!isset($result['daysAfter']) || $result['daysAfter'] == NULL) && (isset($result['daysBefore']) && $result['daysBefore'] != NULL)) {
					$date = date("Y-m-d", strtotime('+' . $result['daysBefore'] . " day", strtotime($result['Date'])));
					if($date == date("Y-m-d", strtotime("now"))) {
						unset($this->data);	$subject = 'Reminder from Gear6'; $email = $result["Email"];
						$this->data['name'] = $result["UserName"]; $phone = $result["Phone"]; $this->data['comments'] = $result['emailMessage'];
						$this->send_gear6_email($email, $subject, 'writeToUser', $this->data);
						$this->send_sms_request_to_api($phone, $result['smsMessage']);
					}
				}
				if((!isset($result['daysBefore']) || $result['daysBefore'] == NULL) && (!isset($result['daysAfter']) || $result['daysAfter'] == NULL)) {
					if($result['Date'] == date("Y-m-d", strtotime("now"))) {
						unset($this->data);	$subject = 'Reminder from Gear6'; $email = $result["Email"];
						$this->data['name'] = $result["UserName"]; $phone = $result["Phone"]; $this->data['comments'] = $result['emailMessage'];
						$this->send_gear6_email($email, $subject, 'writeToUser', $this->data);
						$this->send_sms_request_to_api($phone, $result['smsMessage']);
					}
				}
			}
		}
		echo TRUE;
		exit;
	}
	public function sendCronBirthdayReminders() {
		$query = $this->db->query("SELECT user.Phone, user.UserName FROM user WHERE MONTH(DOB) = MONTH(NOW()) AND DAY(DOB) = DAY(NOW()) AND DOB <> '1970-01-01' AND DOB IS NOT NULL");
		$result = $query->result_array(); foreach ($result as $row) {
			$this->send_sms_request_to_api($row['Phone'], "Happy Birthday " . convert_to_camel_case($row['UserName']) . "! Thank you for being our most loyal customer. - gear6.in");
		}
		echo TRUE;
		exit;
	}
	public function sendCronServiceReminders() {
		$this->load->model('odetails_m');
		$service_reminder_date = $this->odetails_m->get_all_reminders("service_reminder_date", TRUE);
		$insurance_renewal_date = $this->odetails_m->get_all_reminders("insurance_renewal_date", TRUE);
		$puc_renewal_date = $this->odetails_m->get_all_reminders("puc_renewal_date", TRUE);
		foreach ($service_reminder_date as $reminder) {
			$phone = $reminder['phone']; $msg = "Hi " . $reminder['username'] . ", This is a service reminder."; $this->send_sms_request_to_api($phone, $msg);
			$odetails['service_reminder_date_flag'] = 1; $this->db->where('OId', $reminder['OId']); $this->db->update('odetails', $odetails);
		}
		foreach ($insurance_renewal_date as $reminder) {
			$phone = $reminder['phone']; $msg = "Hi " . $reminder['username'] . ", This is a insurance renewal reminder."; $this->send_sms_request_to_api($phone, $msg);
			$odetails['insurance_renewal_date_flag'] = 1; $this->db->where('OId', $reminder['OId']); $this->db->update('odetails', $odetails);
		}
		foreach ($puc_renewal_date as $reminder) {
			$phone = $reminder['phone']; $msg = "Hi " . $reminder['username'] . ", This is a puc renewal reminder."; $this->send_sms_request_to_api($phone, $msg);
			$odetails['puc_renewal_date_flag'] = 1; $this->db->where('OId', $reminder['OId']); $this->db->update('odetails', $odetails);
		}
		echo TRUE;
		exit;
	}
	private function is_payment_not_done($oid) {
		$this->load->model('odetails_m');
		$od_row = $this->odetails_m->get_by(array('OId' => $oid), TRUE);
		if($od_row) {
			$paymentdone = intval($od_row->PaymentMade);
			if($paymentdone == 1) {
				return FALSE;
			} elseif($paymentdone == 0) {
				return TRUE;
			}
		}
		return FALSE;
	}
	private function get_total_billed_amount($oid) {
		$this->load->model('amenity_m');
		$this->load->model('statushistory_m');
		$estprices = $this->amenity_m->get_est_prices_by_oid($oid);
		$oprices = $this->statushistory_m->get_oprices($oid);
		$tot_billed = round(floatval($estprices[count($estprices) - 1]['ptotal']) + floatval($oprices[count($oprices) - 1]['ptotal']), 2);
		return $tot_billed;
	}
	private function get_total_conv_amount($oid) {
		$this->load->model('amenity_m');
		$conv_fee = $this->amenity_m->get_est_prices_by_oid($oid, FALSE, TRUE);
		$tot_conv = round(floatval($conv_fee[count($conv_fee) - 1]['ptotal']), 2);
		return $tot_conv;
	}
	private function get_total_paid_amount($oid) {
		$this->load->model('opaymtdetail_m');
		$tot_paid = round(floatval($this->opaymtdetail_m->get_total_paid_amount($oid)), 2);
		return $tot_paid;
	}
	private function get_total_discount_amount($oid) {
		$this->load->model('amenity_m');
		$discprices = $this->amenity_m->get_est_prices_by_oid($oid, TRUE);
		return round(floatval($discprices[count($discprices) - 1]['ptotal']), 2);
	}
	public function rating($OId, $rating) {
		$OId = decrypt_oid($OId); $rating = intval($rating);
		$data = array('OId' => $OId, 'rating' => $rating);
		$result = $this->db->select('*')->from('g6rating')->where('OId', $OId)->limit(1)->get()->row();
		if($result != NULL && count($result) > 0) {
			$this->db->where('OId', $OId); $this->db->update('g6rating', array("rating" => $rating));			
		} else {
			$this->db->insert('g6rating', $data);
		}
		redirect('/user/userhome/rating');
	}
	public function get_rating() {
		$this->data = array("OId" => 'GR0234946527', 'uname' => 'Sagar Jain', 'uemail' => 'sagar7993@gmail.com');
		$this->load->view('emails/rating', $this->data);
	}
}