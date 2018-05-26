<?php
class Archived extends G6_Bizcontroller {
	public function __construct() {
		parent::__construct();
	}
	public function index () {
		$this->data['h1'] = 'History';
		$this->data['content'] = 'Exclusive List of Orders History';
		$this->data['bc'] = 'History';
		$this->data['active'] = 'history';
		$this->data['rows'] = $this->get_archived_orders();
		$this->load->view('business/archived', $this->data);
	}
	private function get_archived_orders() {
		$this->db->select('odetails.OId, BikeModelName, BikeCompanyName, ODate, Phone, service.ServiceId, UserName, SlotHour, ServiceName');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId', 'left');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId', 'left');
		$this->db->join('useraddr', 'useraddr.UserAddrId = odetails.UserAddrId', 'left');
		$this->db->join('user', 'user.UserId = useraddr.UserId', 'left');
		$this->db->where('service.ServiceId !=', 3);
		$this->db->where("(odetails.TieupId = '" . $this->session->userdata('biz_id') . "')", NULL, FALSE);
		$this->db->where("(odetails.FinalFlag = '1' OR status.Order = '-2' OR status.ServiceId = '4')", NULL, FALSE);
		$this->db->where("((status.ServiceId = '1' AND (status.Order = '4' OR status.Order = '-2'))", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '2' AND (status.Order = '4' OR status.Order = '-2'))", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '4' AND (status.Order = '3' OR status.Order = '-2')))", NULL, FALSE);
		$this->db->order_by('odetails.ODate', 'desc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$count = 0;
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
				$count += 1;
			}
			return $result_rows;
		}
	}
}