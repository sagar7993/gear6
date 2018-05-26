<?php
class Otp_m extends G6_Model {
	protected $_table_name = 'otp';
	protected $_primary_key = 'OTPId';
	protected $_order_by = 'Timestamp';
	public function __construct() {
		parent::__construct();
	}
	public function insert_otp($phone = FALSE) {
		if($phone) {
			$data = array(
				'OTPVal' => get_otp_key(),
				'Phone' => intval($phone)
			);
		} else {
			$data = array(
				'OTPVal' => get_otp_key(),
				'Phone' => intval($this->input->cookie('phone'))
			);
		}
		$this->db->insert('otp', $data);
		return $data['OTPVal'];
	}
	public function is_otp_inserted($phone = FALSE) {
		$this->expire_otp();
		$this->db->select('OTPVal, Phone');
		$this->db->from($this->_table_name);
		if($phone) {
			$this->db->where('Phone', intval($phone));
		} else {
			$this->db->where('Phone', intval($this->input->cookie('phone')));
		}
		$this->db->where('IsExpired', 0);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return FALSE;
		} elseif (count($result) == 1) {
			return $result[0]['OTPVal'];
		}
	}
	public function check_otp($otp, $phone = FALSE) {
		$this->expire_otp();
		$this->db->select('OTPId, IsExpired');
		$this->db->from($this->_table_name);
		if($phone) {
			$this->db->where('Phone', intval($phone));
		} else {
			$this->db->where('Phone', intval($this->input->cookie('phone')));
		}
		$this->db->where('IsExpired', 0);
		$this->db->where('OTPVal', $otp);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return 0;
		} elseif (count($result) == 1) {
			if ($result[0]['IsExpired'] == 0) {
				if($phone) {
					$this->db->where('Phone', intval($phone));
				} else {
					$this->db->where('Phone', intval($this->input->cookie('phone')));
				}
				$this->db->where('IsExpired', 0);
				$this->db->where('OTPVal', $otp);
				$this->db->update($this->_table_name, array('IsExpired' => 1)); 
			}
			return 1;
		}
	}
	private function expire_otp() {
		$buffer_time = time() - 10 * 60;
		$data = array('IsExpired' => 1);
		$this->db->where('UNIX_TIMESTAMP(Timestamp) <', $buffer_time);
		$this->db->delete('otp');
	}
}