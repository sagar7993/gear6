<?php
class Bikemodel_m extends G6_Model {
	protected $_table_name = 'bikemodel';
	protected $_primary_key = 'BikeModelId';
	protected $_order_by = 'BikeModelName';
	public function __construct() {
		parent::__construct();
	}
	public function get_new() {
		$bikemodel = new stdClass();
		$bikemodel->BikeModelName = '';
		$bikemodel->BikeCompanyId = '';
		return $bikemodel;
	}
	public function get_bmodels_by_sc($bm_id = FALSE) {
		$this->db->select('BikeModelName, MapScBm.BikeModelId');
		$this->db->from('MapScBm');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = MapScBm.BikeModelId');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		if($bm_id) {
			$this->db->where('BikeCompanyId', intval($bm_id));
		} else {
			$this->db->where('BikeCompanyId', intval($this->input->post('company')));
		}
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_selected_bm_ids() {
		$this->db->select('BikeModelName, MapScBm.BikeModelId');
		$this->db->from('MapScBm');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = MapScBm.BikeModelId');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('bikemodel.BikeCompanyId', intval($this->input->post('company')));
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach($results as $result) {
				$new_results[] = intval($result['BikeModelId']);
			}
			return $new_results;
		}
	}
	public function get_bikes_by_company() {
		$this->db->select('BikeModelName, BikeModelId');
		$this->db->from($this->_table_name);
		$this->db->where('BikeCompanyId', intval($this->input->post('company')));
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
}