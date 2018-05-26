<?php
class Exclusives_m extends G6_Model {
	protected $_table_name = 'exclusives';
	protected $_primary_key = 'ExclId';
	protected $_order_by = 'ExclId';
	public function __construct() {
		parent::__construct();
	}
	public function get_exclusives_by_sc() {
		$this->db->select('*');
		$this->db->from($this->_table_name);
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->order_by('ExclId', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
}