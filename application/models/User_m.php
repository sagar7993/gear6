<?php
class User_m extends G6_Model {
	protected $_table_name = 'user';
	protected $_primary_key = 'UserId';
	protected $_order_by = 'UserName';
	public function __construct() {
		parent::__construct();
	}
	public function login() {
		$orig_user = $this->get_by(array(
			'Phone' => $this->input->post('phone'),
		), TRUE);
		if($orig_user) {
			$salt = $orig_user->Salt;
			if ($this->input->post('password') != "" && $orig_user->Pwd == $this->input->post('password') && intval($orig_user->PwdCheck) == 1) {
				$session_data = array(
					'name' => $orig_user->UserName,
					'phone' => $orig_user->Phone,
					'email' => $orig_user->Email,
					'id' => $orig_user->UserId,
					'loggedin' => TRUE,
					'is_first_time' => TRUE,
					'mode' => $orig_user->LoginMode
				);
				$this->session->set_userdata($session_data);
				delete_cookie('phone');
			} elseif ($this->input->post('password') != "" && $orig_user->Pwd == generate_salted_hash($this->input->post('password'), $salt)) {
				$session_data = array(
					'name' => $orig_user->UserName,
					'phone' => $orig_user->Phone,
					'email' => $orig_user->Email,
					'id' => $orig_user->UserId,
					'loggedin' => TRUE,
					'is_first_time' => FALSE,
					'mode' => $orig_user->LoginMode
				);
				$this->session->set_userdata($session_data);
				delete_cookie('phone');
			} else {
				if($orig_user->LoginMode == 'Email') {
					$this->set_query_cookie('login_errors', 'Invalid Mobile / Password Combination');
				} else {
					$this->set_query_cookie('login_errors', 'You have to login using ' . $orig_user->LoginMode);
				}
			}
		} else {
			$this->set_query_cookie('login_errors', 'Invalid login attempt');
		}
	}
	public function app_login($phone, $password) {
		$orig_user = $this->get_by(array(
			'Phone' => $phone,
		), TRUE);
		if($orig_user) {
			$salt = $orig_user->Salt;
			if ($password != "" && $orig_user->Pwd == $password && intval($orig_user->PwdCheck) == 1) {
				$app_response['is_first_time'] = 1;
				$app_response['auth_token'] = generate_hash($orig_user->UserId . $orig_user->Salt . strval(time()));
				$this->db->where('UserId', intval($orig_user->UserId))->update('user', array('AuthToken' => $app_response['auth_token']));
				$app_response['status'] = 1;
			} elseif ($password != "" && $orig_user->Pwd == generate_salted_hash($password, $salt)) {
				$app_response['is_first_time'] = 0;
				$app_response['auth_token'] = generate_hash($orig_user->UserId . $orig_user->Salt . strval(time()));
				$this->db->where('UserId', intval($orig_user->UserId))->update('user', array('AuthToken' => $app_response['auth_token']));
				$app_response['status'] = 1;
			} else {
				if($orig_user->LoginMode == 'Email') {
					$app_response['status'] = 0;
					$app_response['errmsg'] = 'Invalid Mobile / Password Combination';
				} else {
					$app_response['status'] = 0;
					$app_response['errmsg'] = 'You have to login using ' . $orig_user->LoginMode;
				}
			}
		} else {
			$app_response['status'] = 0;
			$app_response['errmsg'] = 'Invalid login attempt';
		}
		return $app_response;
	}
	public function reset_password($pass1, $pass2, $phone = NULL, $cpcheck = FALSE) {
		if($phone === NULL) {
			$phone = $this->session->userdata('phone');
		}
		$user = $this->get_by(array(
			'Phone' => $phone,
		), TRUE);
		$salt = $user->Salt;
		if($cpcheck) {
			$pass = generate_salted_hash($this->input->post('pwd'), $salt);
			if($user->Pwd == $pass) {
				$test = TRUE;
			} else {
				$test = FALSE;
			}
		} else {
			$test = TRUE;
		}
		if ($pass1 == $pass2 && $test) {
			$update_pwd['Pwd'] = generate_salted_hash($pass1, $salt);
			$update_pwd['PwdCheck'] = 0;
			$this->db->where('Phone', $phone);
			$this->db->update($this->_table_name, $update_pwd);
			$this->session->set_userdata('is_first_time', FALSE);
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function app_reset_password($pass1, $pass2, $phone, $cpcheck = FALSE) {
		$user = $this->get_by(array(
			'Phone' => $phone,
		), TRUE);
		$salt = $user->Salt;
		if($cpcheck) {
			$pass = generate_salted_hash($this->input->post('pwd'), $salt);
			if($user->Pwd == $pass) {
				$test = TRUE;
			} else {
				$test = FALSE;
			}
		} else {
			$test = TRUE;
		}
		if ($pass1 == $pass2 && $test) {
			$update_pwd['Pwd'] = generate_salted_hash($pass1, $salt);
			$update_pwd['PwdCheck'] = 0;
			$this->db->where('Phone', $phone);
			$this->db->update($this->_table_name, $update_pwd);
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function get_user_addresses($UserId = NULL) {
		$this->db->select('useraddr.UserAddrId, location.CityId, user.UserId, user.Phone, user.Email, UserName, Email, AddrLine1, AddrLine2, LocationName, Landmark, BikeNumber');
		$this->db->from('user');
		$this->db->join('useraddr', 'useraddr.UserId = user.UserId', 'left');
		$this->db->join('location', 'location.LocationId = useraddr.LocationId', 'left');
		$this->db->join('odetails', 'odetails.UserId = user.UserId', 'left');
		if($UserId === NULL) {
			$this->db->where('user.UserId', intval($this->session->userdata('id')));
		} else {
			$this->db->where('user.UserId', intval($UserId));
		}
		$this->db->group_by('useraddr.UserAddrId');
		$this->db->order_by('useraddr.isDefault', 'desc');
		$this->db->order_by('odetails.Timestamp', 'desc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
	public function get_user_bikes($UserId = NULL) {
		$this->db->select('odetails.OId, odetails.ODate, odetails.BikeNumber, service.ServiceName, servicecenter.ScName, oservicedetail.FinalPrice, bikecompany.BikeCompanyName, bikemodel.BikeModelName, odetails.insurance_renewal_date, odetails.puc_renewal_date, odetails.service_reminder_date');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId', 'left');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('servicecenter', 'servicecenter.ScId = oservicedetail.ScId', 'left');
		if($UserId === NULL) {
			$this->db->where('odetails.UserId', intval($this->session->userdata('id')));
		} else {
			$this->db->where('odetails.UserId', intval($UserId));
		}
		$this->db->where('odetails.BikeNumber IS NOT NULL');
		$this->db->order_by('odetails.ODate', 'desc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return array();
		} else {
			return $result;
		}
	}
	public function get_user_bike_num($UserId = NULL) {
		$this->db->select('BikeNumber');
		$this->db->from('odetails');
		if($UserId === NULL) {
			$this->db->where('odetails.UserId', intval($this->session->userdata('id')));
		} else {
			$this->db->where('odetails.UserId', intval($UserId));
		}
		$this->db->order_by('odetails.Timestamp', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$bikenum = preg_split("/\s+/", $result[0]['BikeNumber'], 3);
			$output['BikeNumber'] = '';
			if(isset($bikenum[1])) {
				$output['BikeNumber'] .= $bikenum[1];
			}
			if(isset($bikenum[2])) {
				$output['BikeNumber'] .= $bikenum[2];
			}
			if(isset($bikenum[0])) {
				$output['RegNum'] = $bikenum[0];
			} else {
				$output['RegNum'] = '';
			}
			return $output;
		}
	}
	public function get_user_address_by_location($UserId = NULL, $CityId = NULL) {
		$this->db->select('useraddr.isDefault, useraddr.UserAddrId, location.CityId, user.UserId, user.Phone, user.Email, UserName, Email, AddrLine1, AddrLine2, LocationName, Landmark, BikeNumber');
		$this->db->from('user');
		$this->db->join('useraddr', 'useraddr.UserId = user.UserId', 'left');
		$this->db->join('location', 'location.LocationId = useraddr.LocationId', 'left');
		$this->db->join('odetails', 'odetails.UserId = user.UserId', 'left');
		if($UserId === NULL) {
			$this->db->where('user.UserId', intval($this->session->userdata('id')));
		} else {
			$this->db->where('user.UserId', intval($UserId));
		}
		if($CityId === NULL) {
			$this->db->where('location.CityId', intval($this->input->cookie('CityId')));
		} else {
			$this->db->where('location.CityId', intval($CityId));
		}
		$this->db->group_by('useraddr.UserAddrId');
		$this->db->order_by('useraddr.isDefault', 'desc');
		$this->db->order_by('odetails.Timestamp', 'desc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
	public function update_phone($ph, $user = FALSE) {
		$update_ph['Phone'] = $ph;
		if($user) {
			$this->db->where('UserId', intval($user));
		} else {
			$this->db->where('UserId', intval($this->session->userdata('id')));
		}
		$this->db->update($this->_table_name, $update_ph);
		$this->session->set_userdata('phone', $ph);
	}
	public function updtGendDob($dob, $gender, $phone = FALSE) {
		if(!$phone) {
			$phone = $this->session->userdata('phone');
		}
		$update_pwd['DOB'] = date("Y-m-d", strtotime($dob));
		$update_pwd['Gender'] = $gender;
		$this->db->where('Phone', $phone);
		$this->db->update($this->_table_name, $update_pwd);
		$user_id = $this->get_user_id_by_phone($phone);
		$this->create_birthday_reminder($user_id, $update_pwd['DOB']);
	}
	public function get_social_user($soc_id, $type) {
		$soc_user = $this->get_by(array(
			'SocialId' => $soc_id,
			'LoginMode' => $type
		), TRUE);
		return $soc_user;
	}
	public function get_login_type($phNum) {
		$orig_user = $this->get_by(array(
			'Phone' => $phNum
		), TRUE);
		return $orig_user->LoginMode;
	}
	public function fg_social_login($soc_id, $type) {
		$orig_user = $this->get_by(array(
			'SocialId' => $soc_id,
			'LoginMode' => $type
		), TRUE);
		if($orig_user) {
			$session_data = array(
				'name' => $orig_user->UserName,
				'phone' => $orig_user->Phone,
				'email' => $orig_user->Email,
				'id' => $orig_user->UserId,
				'loggedin' => TRUE,
				'is_first_time' => FALSE,
				'mode' => $orig_user->LoginMode
			);
			$this->session->set_userdata($session_data);
		}
	}
	public function app_social_login($soc_id, $type) {
		$orig_user = $this->get_by(array(
			'SocialId' => $soc_id,
			'LoginMode' => $type
		), TRUE);
		if($orig_user) {
			$app_response['is_first_time'] = 0;
			$app_response['auth_token'] = generate_hash($orig_user->UserId . $orig_user->Salt . strval(time()));
			$this->db->where('UserId', intval($orig_user->UserId))->update('user', array('AuthToken' => $app_response['auth_token']));
			$app_response['status'] = 1;
		} else {
			$app_response['status'] = 0;
			$app_response['errmsg'] = 'User Not Registered';
		}
		return $app_response;
	}
	public function login_after_signup($user) {
		$session_data = array(
			'name' => $user['UserName'],
			'phone' => $user['Phone'],
			'email' => $user['Email'],
			'id' => $user['UserId'],
			'loggedin' => TRUE,
			'is_first_time' => FALSE,
			'mode' => $orig_user->LoginMode
		);
		$this->session->set_userdata($session_data);
	}
	public function is_unique_ph($ph) {
		$orig_user = $this->get_by(array(
			'Phone' => $ph,
		));
		if (count($orig_user) >= 1) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	public function is_unique_em($em) {
		$orig_user = $this->get_by(array(
			'Email' => $em,
		));
		if (count($orig_user) >= 1) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	public function logout() {
		$this->session->sess_destroy();
	}
	public function loggedin() {
		return (bool) $this->session->userdata('loggedin');
	}
	public function is_first_time() {
		return (bool) $this->session->userdata('is_first_time');
	}
	public function create_user() {
		$data_user = array();
		$data_user['UserName'] = $this->input->post('full_name');
		if($this->input->post('phone') == '') {
			$data_user['Phone'] = $this->input->cookie('phone');
		} else {
			$data_user['Phone'] = $this->input->post('phone');
		}
		$data_user['Email'] = $this->input->post('email');
		$data_user['Pwd'] = generateNumericPassword(10);
		$data_user['Salt'] = generate_hash(generateUniqueString(8));
		$data_user['Gender'] = NULL;
		$data_user['DOB'] = NULL;
		$data_user['SocialId'] = NULL;
		$data_user['PwdCheck'] = 1;
		$data_user['LoginMode'] = 'Email';
		$data_user['UserPrivilege'] = 'Yellow';
		$this->db->insert($this->_table_name, $data_user);
		$data_user['UserId'] = $this->db->insert_id();
		$this->update_ref_code($data_user['UserId'], $data_user['UserName'], $data_user['Phone']);
		return $data_user;
	}
	public function signup_social_user($ref_user = NULL) {
		$data_user = array();
		$data_user['UserName'] = $this->input->post('fg_name');
		$data_user['Phone'] = $this->input->post('fg_phone');
		$data_user['Email'] = $this->input->post('fg_email');
		$data_user['Pwd'] = NULL;
		$data_user['Salt'] = generate_hash(generateUniqueString(8));
		$data_user['Gender'] = $this->input->post('fg_gender');
		$data_user['DOB'] = date("Y-m-d", strtotime($this->input->post('fg_dob')));
		$data_user['SocialId'] = $this->input->post('fg_id');
		$data_user['PwdCheck'] = 0;
		$data_user['LoginMode'] = $this->input->post('fg_type');
		$data_user['UserPrivilege'] = 'Yellow';
		if($ref_user) {
			$data_user['Referer'] = intval($ref_user);
		}
		$this->db->insert($this->_table_name, $data_user);
		$data_user['UserId'] = $this->db->insert_id();
		$this->update_ref_code($data_user['UserId'], $data_user['UserName'], $data_user['Phone']);
		$this->create_birthday_reminder($data_user['UserId'], $data_user['DOB']);
		return $data_user['UserId'];
	}
	public function signup_normal_user($ref_user = NULL) {
		$data_user = array();
		$data_user['UserName'] = $this->input->post('s_fname');
		$data_user['Phone'] = $this->input->post('s_phone');
		$data_user['Email'] = $this->input->post('s_email');
		$data_user['Salt'] = generate_hash(generateUniqueString(8));
		$data_user['Pwd'] = generate_salted_hash($this->input->post('s_pwd'), $data_user['Salt']);
		$data_user['Gender'] = $this->input->post('s_gender');
		$data_user['DOB'] = date("Y-m-d", strtotime($this->input->post('s_dob')));
		$data_user['PwdCheck'] = 0;
		$data_user['LoginMode'] = 'Email';
		$data_user['UserPrivilege'] = 'Yellow';
		if($ref_user) {
			$data_user['Referer'] = intval($ref_user);
		}
		$this->db->insert('user', $data_user);
		$data_user['UserId'] = $this->db->insert_id();
		$this->update_ref_code($data_user['UserId'], $data_user['UserName'], $data_user['Phone']);
		$this->create_birthday_reminder($data_user['UserId'], $data_user['DOB']);
		return $data_user;
	}
	public function updt_useraddr($usr_id, $isDefault = 0, $location_id = FALSE) {
		$this->load->model('location_m');
		$data_useraddr = array();
		$data_useraddr['UserId'] = intval($usr_id);
		$data_useraddr['AddrLine1'] = $this->input->post('adln1');
		$data_useraddr['AddrLine2'] = $this->input->post('adln2');
		if($location_id) {
			$data_useraddr['LocationId'] = intval($location_id);
		} else {
			$temp = $this->location_m->location_id_by_name($this->input->post('location'));
			$data_useraddr['LocationId'] = intval($temp['LocationId']);
		}
		$data_useraddr['Landmark'] = $this->input->post('landmark');
		$data_useraddr['isDefault'] = $isDefault;
		$this->db->insert('useraddr', $data_useraddr);
		return $this->db->insert_id();
	}
	public function get_referral_url() {
		$user = $this->get_by(array('UserId' => intval($this->session->userdata('id'))), TRUE);
		$url = site_url('user/userhome/referral_url/' . $user->UserId . '/' . urlencode($user->RefCode));
		return $url;
	}
	public function get_referral_code() {
		$user = $this->get_by(array('UserId' => intval($this->session->userdata('id'))), TRUE);
		$code = $user->RefCode;
		return $code;
	}
	public function give_him_a_coupon($user_id, $ph, $msg = NULL) {
		$data['CCode'] = generateCouponCode(10);
		$data['UserId'] = intval($user_id);
		$data['CAmount'] = floatval(75);
		$data['ValidTill'] = date('Y-m-d', strtotime('+1 year'));
		$query_string = $this->db->insert_string('fcoupons', $data);
		$query_string = str_replace('INSERT', 'INSERT IGNORE', $query_string);
		$this->db->query($query_string);
		if($this->db->affected_rows() == 1) {
			if(!isset($msg) || empty($msg)) {
				$msg = 'You got a coupon code for accepting the referral invitation.';
			}
			$coupon_text = $msg . ' Your code is ' . $data['CCode'] . ' and it is valid till ' . date('d F, Y', strtotime('+1 year'));
			$this->send_sms_request_to_api($ph, $coupon_text);
		} else {
			$this->give_him_a_coupon($user_id, $ph, $msg);
		}
	}
	private function update_ref_code($user_id, $user_name, $ph) {
		if($_POST && isset($_POST['referral_coupon'])) {
			$this->give_him_a_coupon($user_id, $ph);			
		}
		$refdata['RefCode'] = generate_referal_code($user_id, $user_name);
		$this->db->where('UserId', intval($user_id));
		$this->db->update('user', $refdata);
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
	public function create_birthday_reminder($user_id, $dob) {
		$this->db->select('daysBefore, daysAfter');
		$this->db->from('reminder_settings');
		$this->db->where('reminder_id', 4);
		$query = $this->db->get(); $results = $query->result_array();
		if (count($results) > 0) {
			$user_reminders['reminder_id'] = 4;	$user_reminders['UserId'] = $user_id;
			$user_reminders['Date'] = $dob; $user_reminders['isEnabled'] = 1;
			$this->db->delete('user_reminders', array('UserId' => $user_id, 'reminder_id' => 4));
			$this->db->insert('user_reminders', $user_reminders);
		}
	}
	public function get_user_id_by_phone($phone) {
		$this->db->select('UserId');
		$this->db->from('user');
		$this->db->where('Phone', $phone);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		if ($result) {
			return $result['UserId'];
		} else {
			return NULL;
		}
	}
}