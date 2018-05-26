<?php
class Queried extends G6_Vendorcontroller {
	public function __construct() {
		parent::__construct();
	}
	public function index () {
		$this->data['h1'] = 'Queried';
		$this->data['content'] = 'Exclusive Analysis and List of Queried Orders';
		$this->data['bc'] = 'Queried';
		$this->data['active'] = 'queried';
		$this->data['rows'] = $this->get_queried_orders();
		$this->load->view('vendor/queried', $this->data);
	}
	private function get_queried_orders() {
		$this->db->select('odetails.OId, BikeModelName, status.Order, oservicedetail.ServiceDesc1, oservicedetail.ServiceDesc2, BikeCompanyName, ODate, Phone, service.ServiceId, UserName, SlotHour, ServiceName');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId', 'left');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('service.ServiceId =', 3);
		$this->db->order_by('odetails.ODate', 'asc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$count = 0;
			$this->data['n_query'] = 0;
			$this->data['d_query'] = 0;
			$this->data['a_query'] = 0;
			foreach($result as $row) {
				$result_rows[$count]['oid'] = $row['OId'];
				$result_rows[$count]['bmodel'] = convert_to_camel_case($row['BikeCompanyName'] . ' ' . $row['BikeModelName']);
				$result_rows[$count]['phone'] = $row['Phone'];
				$result_rows[$count]['username'] = convert_to_camel_case($row['UserName']);
				$result_rows[$count]['query_desc'] = convert_to_camel_case($row['ServiceDesc1']) . ' - ' . convert_to_camel_case($row['ServiceDesc2']);
				if ($row['Order'] < 3 && (strtotime($row['ODate']) + (24 * 60 * 60)) >= strtotime(date("Y-m-d", time()))) {
					$this->data['n_query'] += 1;
				}
				if ($row['Order'] < 3 && ((strtotime($row['ODate']) + (2 * 24 * 60 * 60)) <= strtotime(date("Y-m-d", time())))) {
					$this->data['d_query'] += 1;
				}
				if($row['Order'] == 3) {
					$this->data['a_query'] += 1;
				}
				$count += 1;
			}
			return $result_rows;
		}
	}
}