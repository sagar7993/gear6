<?php
class Prvendor_m extends G6_Model {
	protected $_table_name = 'prvendor';
	protected $_primary_key = 'VendorId';
	protected $_order_by = 'VendorName';
	public function __construct() {
		parent::__construct();
	}
	public function login() {
		$orig_user = $this->get_by(array(
			'Phone' => $this->input->post('phone'),
			'isVerified' => 1
		), TRUE);
		if($orig_user) {
			$salt = $orig_user->Salt;
			if ($orig_user->Pwd == $this->input->post('password', TRUE) && intval($orig_user->PwdCheck) == 1) {
				$session_data = array(
					'prv_id' => $orig_user->VendorId,
					'prv_name' => $orig_user->VendorName,
					'prv_phone' => $orig_user->Phone,
					'prv_email' => $orig_user->Email,
					'prv_sc_ids' => $orig_user->ScId,
					'prv_role' => $orig_user->UserPrivilege,
					'prv_loggedin' => TRUE,
					'prv_is_first_time' => TRUE
				);
				$this->session->set_userdata($session_data);
				return 1;
			} elseif ($orig_user->Pwd == generate_salted_hash($this->input->post('password', TRUE), $salt)) {
				$session_data = array(
					'prv_id' => $orig_user->VendorId,
					'prv_name' => $orig_user->VendorName,
					'prv_phone' => $orig_user->Phone,
					'prv_email' => $orig_user->Email,
					'prv_sc_ids' => $orig_user->ScId,
					'prv_role' => $orig_user->UserPrivilege,
					'prv_loggedin' => TRUE,
					'prv_is_first_time' => FALSE
				);
				$this->session->set_userdata($session_data);
				return 1;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	public function reset_password($pass1, $pass2, $phone = NULL, $cpcheck = FALSE) {
		if($phone === NULL) {
			$phone = $this->session->userdata('prv_phone');
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
			$this->session->set_userdata('prv_is_first_time', FALSE);
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function logout() {
		$this->session->sess_destroy();
	}
	public function loggedin() {
		return (bool) $this->session->userdata('prv_loggedin');
	}
	public function is_first_time() {
		return (bool) $this->session->userdata('prv_is_first_time');
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