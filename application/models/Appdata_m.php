<?php
class Appdata_m extends G6_Model {
	protected $_table_name = 'appdata';
	protected $_primary_key = 'AppDataId';
	protected $_order_by = 'AppDataId';
	public function __construct() {
		parent::__construct();
	}
	public function set_city() {
		$cityId = intval($this->input->post('city'));
		$this->db->insert($this->_table_name, array('CityId' => $cityId));
		$query_id = strval($this->db->insert_id());
		$query_token = generate_hash($query_id . strval(time()));
		$user_ip = $this->input->ip_address();
		$this->db->where('AppDataId', intval($query_id))->update($this->_table_name, array('QueryToken' => $query_token, 'UserIp' => $user_ip));
		return $query_token;
	}
}