<?php
class Regnum_m extends G6_Model {
	protected $_table_name = 'regnum';
	protected $_primary_key = 'RegNumId';
	protected $_order_by = 'RegNumVal';
	public function __construct() {
		parent::__construct();
	}
	public function get_new() {
		$regnum = new stdClass();
		$regnum->RegNumVal = '';
		$regnum->State = '';
		$regnum->Region = '';
		return $regnum;
	}
	public function get_all_regnumvals() {
		$this->db->distinct();
		$this->db->select('RegNumVal');
		$this->db->from('regnum');
		$query = $this->db->get();
		$result = array();
		foreach ($query->result_array() as $row) {
			$result[] = $row['RegNumVal'];
		}
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
}