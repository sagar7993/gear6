<?php
class Vendor_m extends G6_Model {
	protected $_table_name = 'vendor';
	protected $_primary_key = 'VendorId';
	protected $_order_by = 'VendorName';
	public function __construct() {
		parent::__construct();
	}
	public function login() {
		$this->load->model('servicecenter_m');
		$orig_user = $this->get_by(array(
			'Phone' => $this->input->post('phone', TRUE),
			'isVerified' => 1
		), TRUE);
		if($orig_user) {
			$sc_name = $this->servicecenter_m->get_name_rating($orig_user->ScId);
			$salt = $orig_user->Salt;
			if ($orig_user->Pwd == $this->input->post('password', TRUE) && intval($orig_user->PwdCheck) == 1) {
				$vend_logo = $this->get_vendor_sc_logo($orig_user->ScId);
				$session_data = array(
					'v_name' => $orig_user->VendorName,
					'v_phone' => $orig_user->Phone,
					'v_email' => $orig_user->Email,
					'v_id' => $orig_user->VendorId,
					'v_sc_id' => $orig_user->ScId,
					'v_role' => $orig_user->UserPrivilege,
					'v_sc_name' => $sc_name['ScName'],
					'v_loggedin' => TRUE,
					'v_is_first_time' => TRUE,
					'v_sc_type' => 'sc',
					'v_sc_logo' => $vend_logo
				);
				$this->session->set_userdata($session_data);
				return 1;
			} elseif ($orig_user->Pwd == generate_salted_hash($this->input->post('password', TRUE), $salt)) {
				$vend_logo = $this->get_vendor_sc_logo($orig_user->ScId);
				$session_data = array(
					'v_name' => $orig_user->VendorName,
					'v_phone' => $orig_user->Phone,
					'v_email' => $orig_user->Email,
					'v_id' => $orig_user->VendorId,
					'v_sc_id' => $orig_user->ScId,
					'v_role' => $orig_user->UserPrivilege,
					'v_sc_name' => $sc_name['ScName'],
					'v_loggedin' => TRUE,
					'v_sc_type' => 'sc',
					'v_sc_logo' => $vend_logo
				);
				$this->session->set_userdata($session_data);
				return 1;
			} else {
				return 0;
			}
		} elseif($this->get_by(array('Phone' => $this->input->post('phone', TRUE)), TRUE)) {
			return 2;
		} else {
			return 0;
		}
	}
	public function reset_password($pass1, $pass2, $phone = NULL, $cpcheck = FALSE) {
		if($phone === NULL) {
			$phone = $this->session->userdata('v_phone');
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
			$this->session->set_userdata('v_is_first_time', FALSE);
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function get_vendor_sc_logo($scid) {
		$data['ScId'] = intval($scid);
		$data['ScType'] = 'sc';
		$data['MediaType'] = 'logo';
		$data['MediaOrder'] = '0';
		$data['FileType'] = 'img';
		$this->db->where($data);
		$this->db->limit(1);
		$query = $this->db->get('scmedia');
		$result = $query->row_array();
		if($result) {
			return $result['FileData'];
		} else {
			return NULL;
		}
	}
	public function get_vsc_location() {
		$this->db->select('location.LocationName');
		$this->db->from('scaddrsplit');
		$this->db->join('location', 'location.LocationId = scaddrsplit.LocationId', 'left');
		$this->db->where('scaddrsplit.ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0]['LocationName'];
		}
	}
	public function logout() {
		$this->session->sess_destroy();
	}
	public function loggedin() {
		return (bool) $this->session->userdata('v_loggedin');
	}
	public function is_first_time() {
		return (bool) $this->session->userdata('v_is_first_time');
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
	public function get_city_row_by_vendor() {
		$this->db->select('location.CityId');
		$this->db->from('scaddrsplit');
		$this->db->join('location', 'location.LocationId = scaddrsplit.LocationId', 'left');
		$this->db->where('scaddrsplit.ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$this->load->model('city_m');
			return $this->city_m->get(intval($result[0]['CityId']));
		}
	}
	public function checkOrderDelays() {
		$this->db->select('oservicedetail.OServiceId');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where('odetails.ODate <', date("Y-m-d", strtotime("now")));
		$this->db->where("((odetails.ServiceId = '1' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '2' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '3' AND status.Order < '3' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '4' AND status.Order < '3' AND status.Order >= '0'))", NULL, FALSE);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) != 0) {
			foreach($results as &$result) {
				$result['DelayFlag'] = 1;
			}
			$this->db->update_batch('oservicedetail', $results, 'OServiceId');
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