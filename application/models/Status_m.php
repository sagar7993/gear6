<?php
class Status_m extends G6_Model {
	protected $_table_name = 'status';
	protected $_primary_key = 'StatusId';
	protected $_order_by = 'Order';
	public function __construct() {
		parent::__construct();
	}
	public function get_init_status_service($serid = NULL) {
		$this->db->select('StatusId');
		$this->db->from('status');
		if(isset($serid)) {
			$this->db->where('ServiceId', intval($serid));
		} else {
			$this->db->where('ServiceId', intval($this->input->cookie('servicetype')));
		}
		$this->db->where('Order', 0);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0]['StatusId'];
		}
	}
	public function get_cancellation_status($ServiceId) {
		$this->db->select('StatusId');
		$this->db->from('status');
		$this->db->where('ServiceId', intval($ServiceId));
		$this->db->where('StatusName', 'Cancelled');
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return intval($result[0]['StatusId']);
		}
	}
	public function get_reschedule_status($ServiceId) {
		$this->db->select('StatusId');
		$this->db->from('status');
		$this->db->where('ServiceId', intval($ServiceId));
		$this->db->where('StatusName', 'ReScheduled');
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return intval($result[0]['StatusId']);
		}
	}
	public function get_statuses_for_service($s_id, $order_gt = 0) {
		$this->db->select('StatusId, StatusName, StatusDesc1, StatusDesc2, Order');
		$this->db->from('status');
		$this->db->where('ServiceId', intval($s_id));
		$this->db->where('Order >', $order_gt);
		$this->db->order_by('Order', 'asc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
}