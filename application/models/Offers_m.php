<?php
class Offers_m extends G6_Model {
	protected $_table_name = 'offers';
	protected $_primary_key = 'OfferId';
	protected $_order_by = 'OfferId';
	public function __construct() {
		parent::__construct();
	}
	public function get_offers_by_sc() {
		$this->db->select('*');
		$this->db->from($this->_table_name);
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->order_by('OfferId', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
}