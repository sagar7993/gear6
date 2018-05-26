<?php
class Opaymtdetail_m extends G6_Model {
	protected $_table_name = 'opaymtdetail';
	protected $_primary_key = 'TrId';
	protected $_order_by = 'TrId';
	public function __construct() {
		parent::__construct();
	}
	public function get_success_transactions_by_vendor() {
		$this->db->select('user.UserName, opaymtdetail.TId, opaymtdetail.OId, DATE_FORMAT(CONVERT_TZ(opaymtdetail.TimeStamp,"+00:00","+05:30"), "%D %M %Y %h:%i %p") AS TimeStamp, opaymtdetail.isWithVendor, opaymtdetail.PaymtAmt');
		$this->db->from('opaymtdetail');
		$this->db->join('user', 'user.UserId = opaymtdetail.UserId');
		$this->db->join('oservicedetail', 'oservicedetail.OId = opaymtdetail.OId');
		$this->db->where('opaymtdetail.PaymtStatusId', 3);
		$this->db->where('oservicedetail.ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->order_by('opaymtdetail.TimeStamp', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach($results as &$result) {
				$result['UserName'] = convert_to_camel_case($result['UserName']);
				$result['PaymtAmt'] = number_format((float)$result['PaymtAmt'], 2, '.', '');
				if($result['isWithVendor'] == 0) {
					$result['isWithVendor'] = 'gear6.in';
				} else {
					$result['isWithVendor'] = convert_to_camel_case($this->session->userdata('v_sc_name'));
				}
			}
			return $results;
		}
	}
	public function get_unpaid_payments() {
		$this->db->select('servicecenter.ScName, user.UserName, opaymtdetail.TId, opaymtdetail.OId, DATE_FORMAT(CONVERT_TZ(opaymtdetail.TimeStamp,"+00:00","+05:30"), "%D %M %Y %h:%i %p") AS TimeStamp, opaymtdetail.PaymtAmt');
		$this->db->from('opaymtdetail');
		$this->db->join('user', 'user.UserId = opaymtdetail.UserId');
		$this->db->join('oservicedetail', 'oservicedetail.OId = opaymtdetail.OId');
		$this->db->join('servicecenter', 'servicecenter.ScId = oservicedetail.ScId');
		$this->db->join('odetails', 'odetails.OId = opaymtdetail.OId');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('opaymtdetail.PaymtStatusId', 3);
		$this->db->where('opaymtdetail.isWithVendor', 0);
		$this->db->order_by('opaymtdetail.TimeStamp', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach($results as &$result) {
				$result['UserName'] = convert_to_camel_case($result['UserName']);
				$result['ScName'] = convert_to_camel_case($result['ScName']);
				$result['PaymtAmt'] = number_format((float)$result['PaymtAmt'], 2, '.', '');
			}
			return $results;
		}
	}
	public function get_total_paid_amount($OId) {
		$this->db->select('opaymtdetail.PaymtAmt');
		$this->db->from('opaymtdetail');
		$this->db->where('opaymtdetail.OId', $OId);
		$this->db->where('opaymtdetail.PaymtStatusId', 3);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			$temp_price = 0;
			foreach($results as $result) {
				$temp_price += floatval($result['PaymtAmt']);
			}
			return $temp_price;
		}
	}
	public function get_total_billed_amount($OId) {
		$this->load->model('amenity_m');
		$this->load->model('statushistory_m');
		$data['estprices'] = $this->amenity_m->get_est_prices_by_oid($OId);
		$data['discprices'] = $this->amenity_m->get_est_prices_by_oid($OId, TRUE);
		$data['oprices'] = $this->statushistory_m->get_oprices($OId);
		return floatval($data['estprices'][count($data['estprices']) - 1]['ptotal']) + floatval($data['oprices'][count($data['oprices']) - 1]['ptotal']) - floatval($data['discprices'][count($data['discprices']) - 1]['ptotal']);
	}
	public function get_order_transactions($OId) {
		$this->db->select('opaymtdetail.TId, opaymtdetail.PaymtAmt, DATE_FORMAT(CONVERT_TZ(opaymtdetail.TimeStamp,"+00:00","+05:30"), "%D %M %Y %h:%i %p") AS TimeStamp, paymtstatus.PaymtStatus, opaymtdetail.PaymtId, paymt.PaymtMode');
		$this->db->from('opaymtdetail');
		$this->db->join('paymtstatus', 'paymtstatus.PaymtStatusId = opaymtdetail.PaymtStatusId');
		$this->db->join('paymt', 'paymt.PaymtId = opaymtdetail.PaymtId');
		$this->db->where('opaymtdetail.OId', $OId);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_paymt_types() {
		$this->db->select('*');
		$this->db->from('paymt');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
	public function create_trxn($usr_id, $OId, $price, $is_cod = FALSE) {
		$tdata = array();
		$tdata['UserId'] = intval($usr_id);
		$tdata['OId'] = $OId;
		$tdata['PaymtAmt'] = floatval($price);
		if($is_cod) {
			$tdata['PaymtStatusId'] = 3;
			$tdata['PaymtResponse'] = 'COD';
			$tdata['PaymtId'] = 3;
		} else {
			$tdata['PaymtId'] = 7;
		}
		$this->db->insert($this->_table_name, $tdata);
		$TrId = $this->db->insert_id();
		$TId = $this->insert_trxn_id($TrId);
		return $TId;
	}
	public function create_custom_trxn($trxn_array) {
		if(isset($trxn_array)) {
			$this->db->insert($this->_table_name, $trxn_array);
			$TrId = $this->db->insert_id();
			$TId = $this->insert_trxn_id($TrId);
			return $TId;
		}
	}
	public function get_paymt_id_by_code($paymt_code) {
		$this->db->select('PaymtId');
		$this->db->from('paymt');
		$this->db->where('PaymtCode', $paymt_code);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return intval($result[0]['PaymtId']);
		}
	}
	public function get_cod_by_execbillid($execbillid) {
		$this->db->select('SUM(opaymtdetail.PaymtAmt)');
		$this->db->from('opaymtdetail');
		$this->db->join('execbill', 'execbill.OId = opaymtdetail.OId');
		$this->db->where('execbill.ExecBillId', intval($execbillid));
		$this->db->where('opaymtdetail.PaymtId', 3);
		$this->db->where('opaymtdetail.PaymtStatusId', 3);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		if (count($result) == 0) {
			return 0;
		} else {
			return round(floatval($result['SUM(opaymtdetail.PaymtAmt)']), 2);
		}
	}
	public function get_cod_by_oid($oid) {
		$this->db->select('SUM(opaymtdetail.PaymtAmt)');
		$this->db->from('opaymtdetail');
		$this->db->where('opaymtdetail.OId', $oid);
		$this->db->where('opaymtdetail.PaymtId', 3);
		$this->db->where('opaymtdetail.PaymtStatusId', 3);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		if (count($result) == 0) {
			return 0;
		} else {
			return round(floatval($result['SUM(opaymtdetail.PaymtAmt)']), 2);
		}
	}
	private function insert_trxn_id($TrId) {
		$data['TId'] = generateTrxnId(intval($TrId));
		$where = "TrId = " . intval($TrId);
		$query_string = $this->db->update_string($this->_table_name, $data, $where);
		$query_string = str_replace('UPDATE', 'UPDATE IGNORE', $query_string);
		$this->db->query($query_string);
		if($this->db->affected_rows() == 1) {
			return $data['TId'];
		} else {
			$this->insert_trxn_id($TrId);
		}
	}
}