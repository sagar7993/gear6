<?php
class Userhome extends G6_Usercontroller {
	public function __construct() {
		parent::__construct();
		if($this->session->userdata('id') != NULL) {
			$this->data['current_user'] = $this->user_m->get_by(array('UserId' => intval($this->session->userdata('id'))), TRUE);
		} else {
			$this->data['current_user'] = NULL;
		}
	}
	public function index () {
		$this->check_visitor_count();
		if($this->input->cookie('is_referred_by_id') != "") {
			$this->data['ref_signup_flag'] = 1;
		}
		if(isset($_GET['referer']) && $_GET['referer'] == 'promo.gear6.in') {
			$this->set_query_cookie('referer', 'promo.gear6.in');
		}
		if(isset($_GET['updtsuc']) && $_GET['updtsuc'] == 1) {
			$this->data['show_succ'] = 1;
		}
		if (isset($this->data['city_id'])) {
			$this->data['city_row'] = $city_row = $this->city_m->get($this->data['city_id']);
			$this->data['adv_time'] = intval($city_row->AdvTime) / 24;
			$curr_hour = intval(date("H", time()));
			if($curr_hour >= 18) {
				$this->data['adv_time'] += 1;
			}
		}
		$this->load->view('user/nhome', $this->data);
	}
	public function fetch_services() {
		$this->output->set_content_type('application/json');
		$this->load->model('service_m');
		$data['services'] = $this->service_m->get_by('isEnabled = 1');
		echo json_encode($data);
	}
	public function get_bike_brands() {
		$this->output->set_content_type('application/json');
		$this->load->model('bikecompany_m');
		$data['bikecompanies'] = $this->bikecompany_m->get_by('isEnabled = 1');
		echo json_encode($data);
	}
	public function get_bike_list() {
		$this->output->set_content_type('application/json');
		if($this->input->post('company') != "") {
			$this->load->model('bikemodel_m');
			$data['bikemodels'] = $this->bikemodel_m->get_by('BikeCompanyId = ' . $this->input->post('company'));
		}
		echo json_encode($data);
	}
	public function placeEmgReq() {
		if($_POST) {
			$otp = $this->input->post('accotp');
			$emgdata['CityId'] = intval($this->input->cookie('CityId'));
			$emgdata['ODate'] = date('Y-m-d', strtotime($this->input->post('date_')));
			$emgdata['LocationName'] = $this->input->post('area');
			$emgdata['Latitude'] = $this->input->post('qlati');
			$emgdata['Longitude'] = $this->input->post('qlongi');
			$emgdata['BikeCompanyId'] = $this->input->post('company');
			$emgdata['BikeModelId'] = $this->input->post('model');
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
					echo "Failed";
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
				$adminNotifyFlag['ODate'] = date('Y-m-d', strtotime($this->input->post('date_')));
				$this->db->insert('admin_notification_flags', $adminNotifyFlag);
				$this->send_sms_request_to_api('8888083841', 'Emergency / Accidental Service Request Received From +91' . $emgdata['Phone'] . '. Message: ' . $emgdata['Description']);
				$this->send_sms_request_to_api('9494845111', 'Emergency / Accidental Service Request Received From +91' . $emgdata['Phone'] . '. Message: ' . $emgdata['Description']);
				echo "Success";
			}
		}
	}
	public function placePtReq() {
		if($_POST) {
			$otp = $this->input->post('ptotp');
			$ptdata['CityId'] = intval($this->input->cookie('CityId'));
			$ptdata['ODate'] = date('Y-m-d', strtotime($this->input->post('date_')));
			$ptdata['LocationName'] = $this->input->post('area');
			$ptdata['Latitude'] = $this->input->post('qlati');
			$ptdata['Longitude'] = $this->input->post('qlongi');
			$ptdata['BikeCompanyId'] = $this->input->post('company');
			$ptdata['BikeModelId'] = $this->input->post('model');
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
					echo "Failed";
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
				$adminNotifyFlag['ODate'] = date('Y-m-d', strtotime($this->input->post('date_')));
				$this->db->insert('admin_notification_flags', $adminNotifyFlag);
				$this->send_sms_request_to_api('8888083841', 'Emergency / Puncture Repair Service Request Received From +91' . $ptdata['Phone'] . '. Message: ' . $ptdata['Description']);
				$this->send_sms_request_to_api('9494845111', 'Emergency / Puncture Repair Request Received From +91' . $ptdata['Phone'] . '. Message: ' . $ptdata['Description']);
				echo "Success";
			}
		}
	}
	public function help() {
		$this->load->view('user/help', $this->data);
	}
	public function about() {
		$this->load->view('user/about', $this->data);
	}
	public function misc() {
		$this->load->view('user/misc', $this->data);
	}
	public function services() {
		$this->load->view('user/services', $this->data);
	}
	private function send_curl_post_request($url, $params) {
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $url);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($curl_handle, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl_handle, CURLOPT_POST, TRUE);
		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, http_build_query($params));
		curl_setopt($curl_handle, CURLOPT_USERAGENT, 'gear6.in');
		$content = curl_exec($curl_handle);
		curl_close($curl_handle);
		return json_decode($content, TRUE);
	}
	public function ucontactus() {
		if($_POST) {
			$recaptcha = array('secret' => '6LcsnBwTAAAAALGU3_cnAYo11Gtz32FFc5bMUiMX', 'response' => $this->input->post('g-recaptcha-response'));
			$verified_token = $this->send_curl_post_request("https://www.google.com/recaptcha/api/siteverify", $recaptcha);
			if($verified_token && $verified_token['success'] == TRUE) {
				$ucdata['CityId'] = intval($this->input->cookie('CityId'));
				$ucdata['Name'] = $this->input->post('name');
				$ucdata['Email'] = $this->input->post('email');
				$ucdata['Phone'] = $this->input->post('phone');
				$ucdata['Message'] = $this->input->post('message');
				$ucdata['UserIp'] = $this->input->ip_address();
				$this->load->library('user_agent', NULL, 'agent');
				if ($this->agent->is_mobile()) {
					$ucdata['UserDevice'] = 'mob';
				} else {
					$ucdata['UserDevice'] = 'pc';
				}
				$this->db->insert('ucontactus', $ucdata);
				$msg = 'UserName: ' . $ucdata['Name'] . '
Email Id: ' . $ucdata['Email'] . '
Mobile Number: ' . $ucdata['Phone'] . '
Message:
' . $ucdata['Message'];
				$adminNotifyFlag['new_user_contact_us'] = 1;
				$adminNotifyFlag['Name'] = $ucdata['Name'];
				$adminNotifyFlag['Phone'] = $ucdata['Phone'];
				$adminNotifyFlag['ODate'] = date('Y-m-d', time());
				$this->db->insert('admin_notification_flags', $adminNotifyFlag);
				$this->send_gear6_txt_email($ucdata['Email'], 'support@gear6.in', 'Customer #' . $this->db->insert_id() . ' Message', $msg);
				echo "1";
			} else {
				echo "0";
			}
		}
	}
	public function agregs() {
		if($_POST) {
			$recaptcha = array('secret' => '6LcsnBwTAAAAALGU3_cnAYo11Gtz32FFc5bMUiMX', 'response' => $this->input->post('g-recaptcha-response'));
			$verified_token = $this->send_curl_post_request("https://www.google.com/recaptcha/api/siteverify", $recaptcha);
			if($verified_token && $verified_token['success'] == TRUE) {
				$acdata['CityId'] = intval($this->input->cookie('CityId'));
				$acdata['ScName'] = $this->input->post('scname');
				$acdata['ContactPerson'] = $this->input->post('cperson');
				$acdata['Phone'] = $this->input->post('phone');
				$acdata['Email'] = $this->input->post('email');
				$acdata['ScType'] = $this->input->post('sctype');
				$acdata['UserIp'] = $this->input->ip_address();
				$this->load->library('user_agent', NULL, 'agent');
				if ($this->agent->is_mobile()) {
					$acdata['UserDevice'] = 'mob';
				} else {
					$acdata['UserDevice'] = 'pc';
				}
				$adminNotifyFlag['new_agent_contact_us'] = 1;
				$adminNotifyFlag['Name'] = $acdata['ScName'];
				$adminNotifyFlag['Phone'] = $acdata['Phone'];
				$adminNotifyFlag['ODate'] = date('Y-m-d', time());
				$this->db->insert('admin_notification_flags', $adminNotifyFlag);
				$this->db->insert('agregs', $acdata);
				echo "1";
			} else {
				echo "0";
			}
		}
	}
	public function login() {
		if ($this->input->post('fgl_type') == "Facebook" || $this->input->post('fgl_type') == 'Google') {
			$token_valid = FALSE;
			if($this->input->post('fgl_type') == 'Facebook') {
				$token_valid = $this->validate_fb_token($this->input->post('fgl_ac_token'), $this->input->post('fgl_id'));
			} elseif ($this->input->post('fgl_type') == 'Google') {
				$token_valid = $this->validate_gp_token($this->input->post('fgl_ac_token'), $this->input->post('fgl_id'));
			}
			if($token_valid) {
				$this->user_m->fg_social_login($this->input->post('fgl_id'), $this->input->post('fgl_type'));
				delete_cookie('phone');
				redirect($this->input->post('red_url'));
			} else {
				redirect(base_url());
			}
		} elseif($this->input->post('phone') != "") {
			$this->user_m->login();
			redirect($this->input->post('red_url'));
		} else {
			redirect($this->input->post('red_url'));
		}
	}
	public function isSocialSignedUp() {
		if($this->input->post('soc_id') != "" && $this->input->post('s_type') != "") {
			$user = $this->user_m->get_social_user($this->input->post('soc_id'), $this->input->post('s_type'));
			if($user && count($user) == 1) {
				echo 1;
			} else {
				echo 0;
			}
		} else {
			echo 2;
		}
	}
	public function resetPwd($afterLogin = 0) {
		if($_POST) {
			if($afterLogin == 0) {
				$this->load->model('otp_m');
				$otpcheck = $this->otp_m->check_otp($this->input->post('sfgot_otp'), $this->input->post('sfgot_phone'));
				if($otpcheck == 1) {
					if($this->input->post('sfgot_pwd') != "" && $this->input->post('sfgot_pwd1') != "") {
						$this->user_m->reset_password($this->input->post('sfgot_pwd'), $this->input->post('sfgot_pwd1'), $this->input->post('sfgot_phone'));
						redirect($this->input->post('red_url'));
					} else {
						redirect($this->input->post('red_url'));
					}
				} else {
					$this->set_query_cookie('pwd_reset_error', 'Invalid OTP Entered');
					$this->set_query_cookie('pwd_reset_phone', $this->input->post('sfgot_phone'));
					redirect($this->input->post('red_url'));
				}
			} elseif($afterLogin == 1) {
				if($this->input->post('sf_pswd1') != "" && $this->input->post('sf_pswd2') != "") {
					$this->user_m->reset_password($this->input->post('sf_pswd1'), $this->input->post('sf_pswd2'));
					$this->user_m->updtGendDob($this->input->post('sf_dob'), $this->input->post('sf_gender'));
					redirect($this->input->post('red_url'));
				} else {
					redirect($this->input->post('red_url'));
				}
			}
		}
	}
	public function signup() {
		if($_POST) {
			if($this->input->post('s_referral_coupon')) { $ref_code = $this->input->post('s_referral_coupon'); }
			if($this->input->post('fg_referral_coupon')) { $ref_code = $this->input->post('fg_referral_coupon'); }
			$ref_valid = FALSE; $ref_user = NULL; $_POST['referral_coupon'] = $ref_code;
			if($ref_code) {
				$referrer = $this->user_m->get_by(array('RefCode' => $ref_code), TRUE);
				if($referrer) {
					$ref_user = $referrer->UserId; $ref_valid = TRUE;
				} else {
					$ref_valid = FALSE; $ref_user = NULL;
				}
			} else {
				$ref_valid = TRUE; $ref_user = NULL;
			}
			if ($this->input->post('fg_type') == "Facebook" || $this->input->post('fg_type') == 'Google') {
				$token_valid = FALSE;
				if($this->input->post('fg_type') == 'Facebook') {
					$token_valid = $this->validate_fb_token($this->input->post('fg_ac_token'), $this->input->post('fg_id'));
				} elseif ($this->input->post('fg_type') == 'Google') {
					$token_valid = $this->validate_gp_token($this->input->post('fg_ac_token'), $this->input->post('fg_id'));
				}				
				if($token_valid && $ref_valid) {
					$this->user_m->signup_social_user($ref_user);
					$this->user_m->fg_social_login($this->input->post('fg_id'), $this->input->post('fg_type'));
					redirect($this->input->post('red_url'));
				} else {
					redirect(base_url());
				}
			} else {
				if($ref_valid) {
					$new_user = $this->user_m->signup_normal_user($ref_user);
					$this->user_m->login_after_signup($new_user);
					redirect($this->input->post('red_url'));
				} else {
					redirect(base_url());
				}
			}
		} else {
			redirect(base_url());
		}
	}
	public function insert_otp() {
		if($_POST) {
			$this->load->model('otp_m');
			if($this->user_m->is_unique_ph($this->input->post('phNum'))) {
				$otp = $this->otp_m->is_otp_inserted($this->input->post('phNum'));
				if(!$otp) {
					$otp = $this->otp_m->insert_otp($this->input->post('phNum'));
				}
				$this->send_sms_request_to_api($this->input->post('phNum'), "Your OTP is " . $otp . " for your mobile confirmation at gear6.in");
				echo TRUE;
			} else {
				echo FALSE;
			}
		}
	}
	public function send_otp_user() {
		if($_POST) {
			$this->load->model('otp_m');
			$otp = $this->otp_m->is_otp_inserted($this->input->post('phNum'));
			if(!$otp) {
				$otp = $this->otp_m->insert_otp($this->input->post('phNum'));
			}
			$this->send_sms_request_to_api($this->input->post('phNum'), "Your OTP is " . $otp . " for your mobile confirmation at gear6.in");
			echo TRUE;
		}
	}
	public function sendForgotOtp () {
		if($_POST) {
			$this->load->model('otp_m');
			$data['err'] = FALSE;
			if(!$this->user_m->is_unique_ph($this->input->post('phNum'))) {
				$login_type = $this->user_m->get_login_type($this->input->post('phNum'));
				if($login_type == 'Email') {
					$otp = $this->otp_m->is_otp_inserted($this->input->post('phNum'));
					if(!$otp) {
						$otp = $this->otp_m->insert_otp($this->input->post('phNum'));
					}
					$this->send_sms_request_to_api($this->input->post('phNum'), "Your OTP is " . $otp . " for your mobile confirmation at gear6.in");
				} else {
					$data['err'] = 'You have to login using ' . $login_type;
				}
			} else {
				$data['err'] = 'Sorry, this phone is not registered';
			}
			echo json_encode($data);
		}
	}
	public function check_otp() {
		if($_POST) {
			$this->load->model('otp_m');
			echo $this->otp_m->check_otp($this->input->post('otp'), $this->input->post('phNum'));
		}
	}
	public function check_referral() {
		if($_POST) {
			$ref_code = $this->input->post('ref_code');
			$referrer = $this->user_m->get_by(array('RefCode' => $ref_code), TRUE);
			if($referrer) { echo '1'; } else { echo '0'; }
		}
	}
	public function citySelect() {
		if($_POST) {
			if($this->input->post('city')) {
				$this->city_m->set_city();
				redirect(base_url());
			}
		}
	}
	public function bikeList() {
		if($this->input->post('company') != "") {
			$this->load->model('bikemodel_m');
			echo json_encode($this->bikemodel_m->get_bikes_by_company());
		}
	}
	public function referral_url($id = NULL, $ref_code = NULL) {
		if(isset($id) && isset($ref_code) && $this->data['is_logged_in'] == 0) {
			$user = $this->user_m->get_by(array('UserId' => intval($id), 'RefCode' => $ref_code), TRUE);
			if($user) {
				$this->set_query_cookie('is_referred_by_id', intval($id));
			}
		}
		redirect(base_url());
	}
	private function check_visitor_count() {
		if($this->input->cookie('g6data') == "" || $this->input->cookie('g6data') === NULL) {
			$cookie = array(
				'name'   => 'g6data',
				'value'  => 'iamhere',
				'expire' => '600',
				'secure' => FALSE
			);
			$this->input->set_cookie($cookie);
			$this->load->model('g6data_m');
			$count = intval($this->g6data_m->get(1)->SiteVisitCount);
			$ncount['SiteVisitCount'] = $count + 1;
			$this->db->where('G6DataId', 1);
			$this->db->update('g6data', $ncount);
			$this->data['visitor_count'] = $count + 1;
		}
	}
	private function validate_fb_token($token, $id) {
		$app_id = 819826911429226;
		$app_secret = "f0d092c2a94a194b43096f588e50e51b";
		$app_token = $this->get_curl_data("https://graph.facebook.com/oauth/access_token?client_id=" . $app_id . "&client_secret=" . $app_secret . "&grant_type=client_credentials");
		$verify_token = $this->get_curl_data("https://graph.facebook.com/debug_token?input_token=" . $token . "&" . $app_token);
		$verified_token = json_decode($verify_token, TRUE);
		if($verified_token != "" && intval($verified_token['data']['app_id']) == $app_id && $verified_token['data']['user_id'] == $id) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	private function validate_gp_token($token, $id) {
		$app_id = "452928637350-t9mvi5q8tbpbq3iuut153j38h6oba3e3.apps.googleusercontent.com";
		$app_secret = "j0DBLDKg_KZcsVX5Glt-XIix";
		$verify_token = $this->get_curl_data("https://www.googleapis.com/oauth2/v1/tokeninfo?id_token=" . $token);
		$verified_token = json_decode($verify_token, TRUE);
		if($verified_token != "" && $verified_token['audience'] == $app_id && $verified_token['user_id'] == $id) {
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
	private function set_query_cookie($name, $value) {
		$cookie = array(
			'name'   => $name,
			'value'  => $value,
			'expire' => '86500',
			'secure' => FALSE
		);
		$this->input->set_cookie($cookie);
	}
	public function get_holidays() {
		if($_POST) {
			$date = $this->input->post('date');
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
			echo json_encode($fresults);
		}
	}
	public function terms() {
		$this->load->view('user/terms', $this->data);
	}
	public function privacy() {
		$this->load->view('user/privacy', $this->data);
	}
	public function team() {
		$this->load->view('user/team', $this->data);
	}
	public function faqs() {
		$this->load->view('user/faqs', $this->data);
	}
	public function offers() {
		$this->load->view('user/offers', $this->data);
	}
	public function agent() {
		$this->load->view('user/agent', $this->data);
	}
	public function contact() {
		$this->load->view('user/contact', $this->data);
	}
	public function rating() {
		$this->load->view('user/rating');
	}
}