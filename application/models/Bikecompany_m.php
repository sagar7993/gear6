<?php
class Bikecompany_m extends G6_Model {
	protected $_table_name = 'bikecompany';
	protected $_primary_key = 'BikeCompanyId';
	protected $_order_by = 'BikeCompanyName';
	public function __construct() {
		parent::__construct();
	}
	public function get_new() {
		$bikecompany = new stdClass();
		$bikecompany->BikeCompanyName = '';
		return $bikecompany;
	}
	public function ins_bc_ifnot_exists($bc_id) {
		$this->db->select('MapScBcId');
		$this->db->from('MapScBc');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('BikeCompanyId', $bc_id);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if(count($result) == 1) {
			return TRUE;
		} else {
			$data['ScId'] = intval($this->session->userdata('v_sc_id'));
			$data['BikeCompanyId'] = $bc_id;
			$this->db->insert('MapScBc', $data);
			return TRUE;
		}
	}
	public function del_bc_for_sc($bc_id) {
		$this->db->select('MapScBcId');
		$this->db->from('MapScBc');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('BikeCompanyId', $bc_id);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if(count($result) == 1) {
			$this->db->where('MapScBcId', intval($result[0]['MapScBcId']));
			$this->db->delete('MapScBc');
			return TRUE;
		} else {
			return TRUE;
		}
	}
	public function get_bcompany_by_id() {
		$this->db->select('BikeCompanyName, MapScBc.BikeCompanyId');
		$this->db->from('MapScBc');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = MapScBc.BikeCompanyId');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
	public function get_sc_companies() {
		$this->db->select('BikeCompanyName AS text, BikeCompanyId AS id');
		$this->db->from($this->_table_name);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
}