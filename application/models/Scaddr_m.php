<?php
class Scaddr_m extends G6_Model {
	protected $_table_name = 'scaddr';
	protected $_primary_key = 'ScAddrId';
	protected $_order_by = 'ScAddrSplitId';
	public function __construct() {
		parent::__construct();
	}
	public function get_new() {
		$scaddr = new stdClass();
		$scaddr->Addr = '';
		$scaddr->ScAddrSplitId = '';
		$scaddr->Latitude = '';
		$scaddr->Longitude = '';
		return $scaddr;
	}
	public function location_rows($pt_flag = FALSE) {
		$this->db->select('scaddrsplit.ScId, LocationId, Latitude, Longitude');
		$this->db->from('servicecenter');
		$this->db->join('scaddrsplit', 'scaddrsplit.ScId = servicecenter.ScId');
		$this->db->join('scaddr', 'scaddr.ScAddrSplitId = scaddrsplit.ScAddrSplitId');
		if(!$pt_flag) {
			$this->db->join('MapScBc', 'servicecenter.ScId = MapScBc.ScId');
			$this->db->join('MapScBm', 'servicecenter.ScId = MapScBm.ScId');
			$this->db->join('MapScService', 'servicecenter.ScId = MapScService.ScId');
		}
		$this->db->where('servicecenter.isVerified', 1);
		$this->db->where('CityId', intval($this->input->cookie('CityId')));
		if(!$pt_flag) {
			$this->db->where('BikeCompanyId', intval($this->input->cookie('company')));
			$this->db->where('BikeModelId', intval($this->input->cookie('model')));
			$this->db->where('ServiceId', intval($this->input->cookie('servicetype')));
		}
		$this->db->group_by('servicecenter.ScId');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
	public function app_location_rows($pt_flag = FALSE, $city_id, $company, $model, $serviceid) {
		$this->db->select('scaddrsplit.ScId, LocationId, Latitude, Longitude');
		$this->db->from('servicecenter');
		$this->db->join('scaddrsplit', 'scaddrsplit.ScId = servicecenter.ScId');
		$this->db->join('scaddr', 'scaddr.ScAddrSplitId = scaddrsplit.ScAddrSplitId');
		if(!$pt_flag) {
			$this->db->join('MapScBc', 'servicecenter.ScId = MapScBc.ScId');
			$this->db->join('MapScBm', 'servicecenter.ScId = MapScBm.ScId');
			$this->db->join('MapScService', 'servicecenter.ScId = MapScService.ScId');
		}
		$this->db->where('servicecenter.isVerified', 1);
		$this->db->where('CityId', intval($city_id));
		if(!$pt_flag) {
			$this->db->where('BikeCompanyId', intval($company));
			$this->db->where('BikeModelId', intval($model));
			$this->db->where('ServiceId', intval($serviceid));
		}
		$this->db->group_by('servicecenter.ScId');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
}