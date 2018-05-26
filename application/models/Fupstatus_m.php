<?php
class Fupstatus_m extends G6_Model {
	protected $_table_name = 'fupstatus';
	protected $_primary_key = 'FupStatusId';
	protected $_order_by = 'FupStatusId';
	public function __construct() {
		parent::__construct();
	}
	public function get_fupstat_history($OId) {
		$this->db->select('Remarks, FupStatusName, UpdatedBy, Timestamp');
		$this->db->from($this->_table_name);
		$this->db->join('ofupstatus', 'ofupstatus.FupStatusId = fupstatus.FupStatusId');
		$this->db->where('ofupstatus.OId', $OId);
		$this->db->order_by('Timestamp', 'DESC');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
}