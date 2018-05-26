<?php
class Service_m extends G6_Model {
	protected $_table_name = 'service';
	protected $_primary_key = 'ServiceId';
	protected $_order_by = 'Order';
	public function __construct() {
		parent::__construct();
	}
	public function get_new() {
		$service = new stdClass();
		$service->ServiceName = '';
		$service->isEnabled = 0;
		return $service;
	}
	public function get_services_by_sc() {
		$this->db->select('MapScService.ServiceId, ServiceName');
		$this->db->from('MapScService');
		$this->db->join('service', 'service.ServiceId = MapScService.ServiceId');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('isEnabled', 1);
		$this->db->group_by('service.ServiceId');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach($results as $result) {
				$new_results[] = intval($result['ServiceId']);
			}
			return $new_results;
		}
	}
	public function get_services_for_sc() {
		$this->db->select('MapScService.ServiceId, ServiceName');
		$this->db->from('MapScService');
		$this->db->join('service', 'service.ServiceId = MapScService.ServiceId');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('isEnabled', 1);
		$this->db->group_by('service.ServiceId');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_services_for_vendor_order() {
		$this->db->select('MapScService.ServiceId, ServiceName');
		$this->db->from('MapScService');
		$this->db->join('service', 'service.ServiceId = MapScService.ServiceId');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('isEnabled', 1);
		$this->db->where('service.ServiceId != ', 3);
		$this->db->where('service.ServiceId != ', 7);
		$this->db->where('service.ServiceId != ', 9);
		$this->db->where('service.ServiceId != ', 11);
		$this->db->group_by('service.ServiceId');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_priced_services() {
		$this->db->select('MapScService.ServiceId, ServiceName');
		$this->db->from('MapScService');
		$this->db->join('service', 'service.ServiceId = MapScService.ServiceId');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('isEnabled', 1);
		$this->db->where('PriceApply', 1);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_srprices() {
		$this->db->select('ServiceName, DATE_FORMAT(CONVERT_TZ(Timestamp,"+00:00","+05:30"), "%D %M %Y %h:%i %p") AS Timestamp, Price, SPriceId, BikeCompanyName, BikeModelName');
		$this->db->from('sprice');
		$this->db->join('service', 'service.ServiceId = sprice.ServiceId');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = sprice.BikeModelId');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_old_sprices($service_id) {
		$this->db->select('Price, SPriceId, BikeModelId');
		$this->db->from('sprice');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('ServiceId', intval($service_id));
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			$new_results = array(array());
			foreach($results as $result) {
				$new_results[intval($result['SPriceId'])] = intval($result['BikeModelId']);
			}
			return $new_results;
		}
	}
}