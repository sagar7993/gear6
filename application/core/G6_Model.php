<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class G6_Model extends CI_Model {
	protected $_table_name = '';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = '';
	public $rules = array();
	public function __construct() {
		parent::__construct();
	}
	public function get($id = NULL, $single = FALSE, $isArray = FALSE) {
		if ($id != NULL) {
			$filter = $this->_primary_filter;
			$id = $filter($id);
			$this->db->where($this->_primary_key, $id);
			$method = 'row';
		} elseif($single == TRUE) {
			$method = 'row';
		} else {
			$method = 'result';
		}
		if($isArray) {
			$method .= '_array';
		}
		$this->db->order_by($this->_order_by);
		return $this->db->get($this->_table_name)->$method();
	}
	public function get_by($where, $single = FALSE, $isArray = FALSE) {
		$this->db->where($where);
		return $this->get(NULL, $single, $isArray);
	}
	public function get_batch_by($attr, $array, $single = FALSE, $isArray = FALSE) {
		$this->db->where_in($attr, $array);
		return $this->get(NULL, $single, $isArray);
	}
	public function save($data, $id = NULL) {
		if ($id === NULL) {
			!isset($data[$this->_primary_key]) || $data[$this->_primary_key] = NULL;
			$this->db->set($data);
			$this->db->insert($this->_table_name);
			$id = $this->db->insert_id();
		} else {
			$filter = $this->_primary_filter;
			$id = $filter($id);
			$this->db->set($data);
			$this->db->where($this->_primary_key, $id);
			$this->db->update($this->_table_name);
		}		
		return $id;
	}
	public function delete($id) {
		$filter = $this->_primary_filter;
		$id = $filter($id);		
		if (!$id) {
			return FALSE;
		}
		$this->db->where($this->_primary_key, $id);
		$this->db->limit(1);
		$this->db->delete($this->_table_name);
	}
	protected function send_sms_request_to_api($ph, $msg) {
		/*$authKey = '92961AEtGLkzjq55febded';
		$api_url = 'https://control.msg91.com/api/sendhttp.php?authkey=' . $authKey . '&mobiles=' . $ph . '&message=' . urlencode($msg) . '&sender=GEARSX&route=4&country=91&unicode=0';
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $api_url);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl_handle, CURLOPT_POST, FALSE);
		curl_setopt($curl_handle, CURLOPT_HEADER, FALSE);
		curl_setopt($curl_handle, CURLOPT_USERAGENT, 'gear6.in');
		curl_exec($curl_handle);
		curl_close($curl_handle);*/
	}
	protected function send_gcm_request(&$gcm_ids, &$msg_array) {
		$apiKey = "AIzaSyCJAZ8XEe77EEImcMfeeWVyW7KTAG1CwAM";
		$url = 'https://android.googleapis.com/gcm/send';
		$fields = array(
			'registration_ids' => $gcm_ids,
			'data' => $msg_array,
		);
		$headers = array(
			'Authorization: key=' . $apiKey,
			'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		curl_exec($ch);
		curl_close($ch);
	}
	protected function get_all_active_admin_devices() {
		$tempids = $this->db->select('admin.GCMId')->from('admin')->where('admin.GCMId <> ', NULL)->get()->result();
		$gcmids = array();
		foreach($tempids as $temp) {
			if(isset($temp->GCMId)) {
				$gcmids[] = strval($temp->GCMId);
			}
		}
		return $gcmids;
	}
}