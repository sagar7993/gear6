<?php
class Tieups_m extends G6_Model {
	protected $_table_name = 'tieupusers';
	protected $_primary_key = 'TUserId';
	protected $_order_by = 'TUserName';
	public function __construct() {
		parent::__construct();
	}
	public function get_businesses() {
		return $this->db->select('*')->from('tieups')->where('isEnabled', 1)->get()->result();
	}
	public function login() {
		$orig_user = $this->get_by(array(
			'Phone' => $this->input->post('phone'),
			'isVerified' => 1
		), TRUE);
		if($orig_user) {
			$biz = $this->db->select('*')->from('tieups')->where('TieupId', $orig_user->TieupId)->where('isEnabled', 1)->get()->row();
			$salt = $orig_user->Salt;
			if ($biz && $orig_user->Pwd == $this->input->post('password', TRUE) && intval($orig_user->PwdCheck) == 1) {
				$session_data = array(
					'b_id' => $orig_user->TUserId,
					'b_name' => $orig_user->TUserName,
					'b_phone' => $orig_user->Phone,
					'b_email' => $orig_user->Email,
					'biz_id' => $orig_user->TieupId,
					'b_role' => $orig_user->UserPrivilege,
					'biz_name' => $biz->TieupName,
					'b_loggedin' => TRUE,
					'b_is_first_time' => TRUE,
					'b_logo' => $biz->Logo
				);
				$this->session->set_userdata($session_data);
				return 1;
			} elseif ($biz && $orig_user->Pwd == generate_salted_hash($this->input->post('password', TRUE), $salt)) {
				$session_data = array(
					'b_id' => $orig_user->TUserId,
					'b_name' => $orig_user->TUserName,
					'b_phone' => $orig_user->Phone,
					'b_email' => $orig_user->Email,
					'biz_id' => $orig_user->TieupId,
					'b_role' => $orig_user->UserPrivilege,
					'biz_name' => $biz->TieupName,
					'b_loggedin' => TRUE,
					'b_is_first_time' => FALSE,
					'b_logo' => $biz->Logo
				);
				$this->session->set_userdata($session_data);
				return 1;
			} elseif ($orig_user->Pwd == generate_salted_hash($this->input->post('password', TRUE), $salt) && $this->db->select('*')->from('tieups')->where('TieupId', $orig_user->TieupId)->get()->row()) {
				return 2;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}
	public function reset_password($pass1, $pass2, $phone = NULL, $cpcheck = FALSE) {
		if($phone === NULL) {
			$phone = $this->session->userdata('b_phone');
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
			$this->session->set_userdata('b_is_first_time', FALSE);
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function get_business_by_oid($OId) {
		$this->db->select('*');
		$this->db->from('odetails');
		$this->db->join('tieups', 'tieups.TieupId = odetails.TieupId');
		$this->db->where('odetails.OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return FALSE;
		} else {
			return $result[0];
		}
	}
	public function logout() {
		$this->session->sess_destroy();
	}
	public function loggedin() {
		return (bool) $this->session->userdata('b_loggedin');
	}
	public function is_first_time() {
		return (bool) $this->session->userdata('b_is_first_time');
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