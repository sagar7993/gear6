<?php
class Unallotted extends G6_Prvendorcontroller {
	public function __construct() {
		parent::__construct();
	}
	public function index () {
		$this->data['h1'] = 'UnAllotted';
		$this->data['content'] = 'Exclusive Analysis and List of UnAllotted Orders';
		$this->data['bc'] = 'UnAllotted';
		$this->data['active'] = 'unallot';
		$this->data['rows'] = $this->get_unallotted_orders();
		$this->load->view('prvendor/unallotted', $this->data);
	}
	private function get_unallotted_orders() {
		$this->db->select('odetails.OId, BikeModelName, BikeCompanyName, ODate, Phone, service.ServiceId, UserName, SlotHour, ServiceName');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId', 'left');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId', 'left');
		$this->db->join('useraddr', 'useraddr.UserAddrId = odetails.UserAddrId', 'left');
		$this->db->join('user', 'user.UserId = useraddr.UserId', 'left');
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
		$this->db->where('status.Order', 0);
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
			$this->data['de_count'] = 0;
			foreach($result as $row) {
				$date = $row['ODate'];
				if ((strtotime($date) - (24 * 60 * 60)) <= strtotime(date("Y-m-d", time()))) {
					$this->data['de_count'] += 1;
				}
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