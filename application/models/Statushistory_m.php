<?php
class Statushistory_m extends G6_Model {
	protected $_table_name = 'statushistory';
	protected $_primary_key = 'StatusSerial';
	protected $_order_by = 'ModifiedDate';
	public function __construct() {
		parent::__construct();
	}
	public function get_oprices($oid) {
		$this->db->select('OPrice.Price, OPrice.PriceDescription, OPrice.OPID');
		$this->db->from('OPrice');
		$this->db->where('OPrice.OId', $oid);
		$this->db->order_by('OPrice.OPID', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			$count = 0;
			$final_results = array();
			$total_amount = 0;
			foreach($results as $result) {
				$final_results[$count]['oprice'] = $result['Price'];
				$final_results[$count]['opdesc'] = $result['PriceDescription'];
				$final_results[$count]['opid'] = $result['OPID'];
				$total_amount += floatval($result['Price']);
				$count += 1;
			}
			$final_results[$count]['ptotal'] = $total_amount;
			return $final_results;
		}
	}
	public function get_status_history($oid, $is_query = FALSE, $sc_id = NULL) {
		$this->db->select('statushistory.StatusDescription, statushistory.ScId, statushistory.StatusSerial, statushistory.AdminNotes, statushistory.ModifiedBy, status.StatusName, DATE_FORMAT(CONVERT_TZ(statushistory.ModifiedDate,"+00:00","+05:30"), "%D %M %Y %h:%i %p") AS ModifiedDate');
		$this->db->from('statushistory');
		$this->db->join('status', 'status.StatusId = statushistory.StatusId');
		$this->db->join('oservicedetail', 'oservicedetail.ScId = statushistory.ScId AND oservicedetail.OId = statushistory.OId');
		$this->db->where('statushistory.OId', $oid);
		if($sc_id === NULL) {
			if($is_query) {
				$this->db->order_by('statushistory.ScId', 'asc');
			} else {
				$this->db->where('statushistory.ScId', intval($this->session->userdata('v_sc_id')));
			}
		} else {
			$this->db->where('statushistory.ScId', intval($sc_id));
		}
		$this->db->order_by('status.Order', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			$count = 0;
			$final_results = array();
			foreach($results as $result) {
				$final_results[$count]['sc_id'] = $result['ScId'];
				$final_results[$count]['sdesc'] = $result['StatusDescription'];
				$final_results[$count]['admin_notes'] = $result['AdminNotes'];
				$final_results[$count]['sname'] = convert_to_camel_case($result['StatusName']);
				$final_results[$count]['date'] = $result['ModifiedDate'];
				$final_results[$count]['statserial'] = $result['StatusSerial'];
				$final_results[$count]['modified_by'] = $result['ModifiedBy'];
				$count += 1;
			}
			return $final_results;
		}
	}
}