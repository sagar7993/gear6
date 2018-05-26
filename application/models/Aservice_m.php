<?php
class Aservice_m extends G6_Model {
	protected $_table_name = 'aservice';
	protected $_primary_key = 'AServiceId';
	protected $_order_by = 'AServiceName';
	public function __construct() {
		parent::__construct();
	}
	public function get_chosen_aservices($OId) {
		$this->db->select('AServiceName');
		$this->db->from($this->_table_name);
		$this->db->join('oaserdetail', 'oaserdetail.AServiceId = aservice.AServiceId');
		$this->db->where('oaserdetail.OId', $OId);
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			foreach($results as $result) {
				$final_result[] = $result['AServiceName'];
			}
			return implode(', ', $final_result);
		}
	}
	public function get_asrprices() {
		$this->db->select('AServiceName, DATE_FORMAT(CONVERT_TZ(Timestamp,"+00:00","+05:30"), "%D %M %Y %h:%i %p") AS Timestamp, Price, ASPriceId, TaxType, IsMand, BikeCompanyName, BikeModelName');
		$this->db->from('asprice');
		$this->db->join('aservice', 'aservice.AServiceId = asprice.AServiceId');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = asprice.BikeModelId');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach($results as &$result) {
				if(intval($result['IsMand']) == 0) {
					$result['IsMand'] = 'NO';
				} else {
					$result['IsMand'] = 'YES';
				}
				if(intval($result['TaxType']) == 1) {
					$result['TaxType'] = 'Service Charge';
				} elseif(intval($result['TaxType']) == 2) {
					$result['TaxType'] = 'VAT';
				}
			}
			return $results;
		}
	}
	public function get_aservices_for_order($bm_id = NULL, $sc_id = NULL, $ismand) {
		$this->db->select('aservice.AServiceName, aservice.AServiceId');
		$this->db->from('asprice');
		$this->db->join('aservice', 'aservice.AServiceId = asprice.AServiceId');
		if(isset($sc_id)) {
			$this->db->where('asprice.ScId', intval($sc_id));
		} else {
			$this->db->where('asprice.ScId', intval($this->input->cookie('sc_id')));
		}
		if(isset($bm_id)) {
			$this->db->where('asprice.BikeModelId', intval($bm_id));
		} else {
			$this->db->where('asprice.BikeModelId', intval($this->input->cookie('model')));
		}
		$this->db->where('aservice.isEnabled', 1);
		$this->db->where('asprice.IsMand', intval($ismand));
		$this->db->where('aservice.AScType', 'sc');
		$this->db->group_by('aservice.AServiceId');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_old_asprices($aserid) {
		$this->db->select('Price, ASPriceId, BikeModelId');
		$this->db->from('asprice');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('AServiceId', intval($aserid));
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			$new_results = array(array());
			foreach($results as $result) {
				$new_results[intval($result['ASPriceId'])] = intval($result['BikeModelId']);
			}
			return $new_results;
		}
	}
}