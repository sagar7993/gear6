<?php
class Allotted extends G6_Vendorcontroller {
	public function __construct() {
		parent::__construct();
	}
	public function index () {
		$this->data['h1'] = 'Allotted';
		$this->data['content'] = 'Exclusive Analysis and List of Allotted Orders';
		$this->data['bc'] = 'Allotted';
		$this->data['active'] = 'allot';
		$this->data['rows'] = $this->get_allotted_orders();
		$this->load->view('vendor/allotted', $this->data);
	}
	private function get_allotted_orders() {
		$this->db->select('odetails.OId, BikeModelName, BikeCompanyName, ODate, Phone, service.ServiceId, UserName, SlotHour, ServiceName');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId', 'left');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('status.Order', 1);
		$this->db->where('service.ServiceId !=', 3);
		$this->db->order_by('odetails.ODate', 'asc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$count = 0;
			$this->data['ps_count'] = 0;
			$this->data['r_count'] = 0;
			$this->data['ir_count'] = 0;
			foreach($result as $row) {
				$date = $row['ODate'];
				$result_rows[$count]['odate'] = date("l, F d, Y", strtotime($date));
				if ($row['SlotHour'] > 12) {
					$temp_hr = intval($row['SlotHour'] - 12);
					$temp = (intval($row['SlotHour'] * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$result_rows[$count]['odate'] .= ' - ' . $temp_hr . ":" . $temp . "&nbsp;PM";
				} elseif ($row['SlotHour'] == 12) {
					$result_rows[$count]['odate'] .= ' - ' . intval($row['SlotHour']) . ":00&nbsp;PM";
				} else {
					$temp = (intval($row['SlotHour'] * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$result_rows[$count]['odate'] .= ' - ' . intval($row['SlotHour']) . ":" . $temp . "&nbsp;AM";
				}
				$result_rows[$count]['oid'] = $row['OId'];
				$result_rows[$count]['bmodel'] = convert_to_camel_case($row['BikeCompanyName'] . ' ' . $row['BikeModelName']);
				$result_rows[$count]['otype'] = $row['ServiceName'];
				$result_rows[$count]['phone'] = $row['Phone'];
				$result_rows[$count]['username'] = convert_to_camel_case($row['UserName']);
				if($row['ServiceId'] == 1) {
					$this->data['ps_count'] += 1;
				} elseif($row['ServiceId'] == 2) {
					$this->data['r_count'] += 1;
				} elseif ($row['ServiceId'] == 4) {
					$this->data['ir_count'] += 1;
				}
				$count += 1;
			}
			return $result_rows;
		}
	}
}