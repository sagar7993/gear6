<?php
class Vendorhome extends G6_Vendorcontroller {
	public function __construct() {
		parent::__construct();
	}
	public function index () {
		$this->vendor_m->checkOrderDelays();
		$this->data['h1'] = 'Dashboard';
		$this->data['content'] = 'Overview and Analysis of all Orders, Slots, Status, Delays, Pick-Ups';
		$this->data['bc'] = 'Dashboard';
		$this->data['active'] = 'dashboard';
		$this->data['page'] = 'dashboard';
		$this->data['tot_orders'] = intval($this->get_tot_orders());
		$this->data['tot_processed'] = intval($this->get_tot_processed());
		$this->data['tot_queried'] = intval($this->get_tot_queried());
		$this->data['tot_delayed'] = intval($this->get_tot_delayed());
		$this->data['tot_orders_without_query'] = $this->data['tot_orders'] - $this->data['tot_queried'];
		$this->load->view('vendor/home', $this->data);
	}
	public function query_page_analysis() {
		if($_POST) {
			$adata['atype']['name'] = 'Exclusive Query - ' . $this->input->post('atype') . ' Analysis';
			if($this->input->post('atype') == 'Weekly') {
				$date = date("Y-m-d", strtotime("-6 day", strtotime("now")));
				for($i = 0; $i <= 6; $i++) {
					$adata['categories'][$i] = date("d/m", strtotime($date));
					$adata['queried'][$i] = intval($this->getQueried($date));
					$adata['answered'][$i] = intval($this->getQueried($date, 3));
					$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
				}
			} elseif($this->input->post('atype') == 'Monthly') {
				$date = date("Y-m-d", strtotime("-27 day", strtotime("now")));
				for($i = 0; $i <= 3; $i++) {
					$from_date = $date;
					$adata['categories'][$i] = date("d/m", strtotime($date)) . ' - ';
					$date = date("Y-m-d", strtotime("+7 day", strtotime($date)));
					$to_date = $date;
					$adata['categories'][$i] .= date("d/m", strtotime($date));
					$adata['queried'][$i] = intval($this->getQueriedRange($from_date, $to_date));
					$adata['answered'][$i] = intval($this->getQueriedRange($from_date, $to_date, 3));
				}
			}
			echo json_encode($adata);
		}
	}
	public function pick_up_analysis() {
		if($_POST) {
			$adata['atype']['name'] = 'Pick Up Ratio - ' . $this->input->post('atype') . ' Analysis';
			if($this->input->post('atype') == 'Weekly') {
				$date = date("Y-m-d", strtotime("+1 day", strtotime("now")));
				for($i = 0; $i <= 6; $i++) {
					$adata['categories'][$i] = date("d/m", strtotime($date));
					$adata['pick'][$i] = intval($this->getPickups($date, TRUE));
					$tot_pd_orders = intval($this->getPdOrders($date));
					$adata['nopick'][$i] = $tot_pd_orders - intval($this->getPickups($date));
					$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
				}
			} elseif($this->input->post('atype') == 'Monthly') {
				$date = date("Y-m-d", strtotime("+1 day", strtotime("now")));
				for($i = 0; $i <= 3; $i++) {
					$from_date = $date;
					$adata['categories'][$i] = date("d/m", strtotime($date)) . ' - ';
					$date = date("Y-m-d", strtotime("+7 day", strtotime($date)));
					$to_date = $date;
					$adata['categories'][$i] .= date("d/m", strtotime($date));
					$adata['pick'][$i] = intval($this->getPickupsRange($from_date, $to_date, TRUE));
					$tot_pd_orders = intval($this->getPdOrdersRange($from_date, $to_date));
					$adata['nopick'][$i] = $tot_pd_orders - intval($this->getPickupsRange($from_date, $to_date));
				}
			}
			echo json_encode($adata);
		}
	}
	public function status_type_analysis() {
		$adata['name1'] = 'Future Order Statuses - ' . $this->input->post('atype') . ' Analysis';
		$adata['name2'] = 'Overall Queries - ' . $this->input->post('atype') . ' Analysis';
		if($this->input->post('atype') == 'Weekly') {
			$from_date = date("Y-m-d", strtotime("+1 day", strtotime("now")));
			$to_date = date("Y-m-d", strtotime("+7 day", strtotime("now")));
			$allot = intval($this->getAllotmentsRange($from_date, $to_date, 1));
			$unallot = intval($this->getAllotmentsRange($from_date, $to_date, 0));
			if(($allot + $unallot) == 0) {
				$adata['allot'] = 100;
			} else {
				$adata['allot'] = floatval($allot * 100) / ($allot + $unallot);
			}
			$from_date = date("Y-m-d", strtotime("-6 day", strtotime("now")));
			$to_date = date("Y-m-d", strtotime("now"));
			$ans = intval($this->getQueriedRange($from_date, $to_date, 3));
			$unans = intval($this->getQueriedRange($from_date, $to_date, 0));
			if(($ans + $unans) == 0) {
				$adata['ans'] = 100;
			} else {
				$adata['ans'] = floatval($ans * 100) / ($ans + $unans);
			}
		} elseif($this->input->post('atype') == 'Monthly') {
			$from_date = date("Y-m-d", strtotime("+1 day", strtotime("now")));
			$to_date = date("Y-m-d", strtotime("+29 day", strtotime("now")));
			$allot = intval($this->getAllotmentsRange($from_date, $to_date, 1));
			$unallot = intval($this->getAllotmentsRange($from_date, $to_date, 0));
			if(($allot + $unallot) == 0) {
				$adata['allot'] = 100;
			} else {
				$adata['allot'] = floatval($allot * 100) / ($allot + $unallot);
			}
			$from_date = date("Y-m-d", strtotime("-27 day", strtotime("now")));
			$to_date = date("Y-m-d", strtotime("now"));
			$ans = intval($this->getQueriedRange($from_date, $to_date, 3));
			$unans = intval($this->getQueriedRange($from_date, $to_date, 0));
			if(($ans + $unans) == 0) {
				$adata['ans'] = 100;
			} else {
				$adata['ans'] = floatval($ans * 100) / ($ans + $unans);
			}
		}
		echo json_encode($adata);
	}
	public function future_orders_analysis() {
		$adata['atype']['name'] = 'Future Orders - ' . $this->input->post('atype') . ' Analysis';
		if($this->input->post('atype') == 'Weekly') {
			$date = date("Y-m-d", strtotime("+1 day", strtotime("now")));
			for($i = 0; $i <= 6; $i++) {
				$adata['categories'][$i] = date("d/m", strtotime($date));
				$adata['allot'][$i] = intval($this->getAllotments($date, 1));
				$adata['unallot'][$i] = intval($this->getAllotments($date, 0));
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
			}
		} elseif($this->input->post('atype') == 'Monthly') {
			$date = date("Y-m-d", strtotime("+1 day", strtotime("now")));
			for($i = 0; $i <= 3; $i++) {
				$from_date = $date;
				$adata['categories'][$i] = date("d/m", strtotime($date)) . ' - ';
				$date = date("Y-m-d", strtotime("+7 day", strtotime($date)));
				$to_date = $date;
				$adata['categories'][$i] .= date("d/m", strtotime($date));
				$adata['allot'][$i] = intval($this->getAllotmentsRange($from_date, $to_date, 1));
				$adata['unallot'][$i] = intval($this->getAllotmentsRange($from_date, $to_date, 0));
			}
		}
		echo json_encode($adata);
	}
	public function next_week_analysis() {
		$adata['atype']['name'] = 'Total Orders For Next 7 Days';
		$date = date("Y-m-d", strtotime("+1 day", strtotime("now")));
		for($i = 0; $i <= 6; $i++) {
			$adata['categories'][$i] = date("d/m", strtotime($date));
			$results = $this->getOrderByDate($date);
			$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
			$adata['reser'][$i] = 0;
			$adata['ins'][$i] = 0;
			if($results !== NULL) {
				foreach($results as $result) {
					if($result['ServiceId'] == 1 || $result['ServiceId'] == 2) {
						$adata['reser'][$i] += 1;
					} elseif ($result['ServiceId'] == 4) {
						$adata['ins'][$i] += 1;
					}
				}
			}
		}
		echo json_encode($adata);
	}
	public function order_summary_analysis() {
		$adata['atype']['name'] = 'Order Summary - ' . $this->input->post('atype') . ' Analysis';
		if($this->input->post('atype') == 'Weekly') {
			$date = date("Y-m-d", strtotime("-7 day", strtotime("now")));
			for($i = 0; $i <= 6; $i++) {
				$adata['categories'][$i] = date("d/m", strtotime($date));
				$results = $this->getOrderByDate($date);
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
				$adata['orders'][$i] = 0;
				if($results !== NULL) {
					foreach($results as $result) {
						if($result['ServiceId'] != 3) {
							$adata['orders'][$i] += 1;
						}
					}
				}
			}
		} elseif($this->input->post('atype') == 'Monthly') {
			$date = date("Y-m-d", strtotime("-28 day", strtotime("now")));
			for($i = 0; $i <= 3; $i++) {
				$from_date = $date;
				$adata['categories'][$i] = date("d/m", strtotime($date)) . ' - ';
				$date = date("Y-m-d", strtotime("+7 day", strtotime($date)));
				$to_date = $date;
				$adata['categories'][$i] .= date("d/m", strtotime($date));
				$results = $this->getOrderByDateRange($from_date, $to_date);
				$adata['orders'][$i] = 0;
				if($results !== NULL) {
					foreach($results as $result) {
						if($result['ServiceId'] != 3) {
							$adata['orders'][$i] += 1;
						}
					}
				}
			}
		}
		echo json_encode($adata);
	}
	public function delay_analysis() {
		$adata['atype']['name'] = 'Delay - ' . $this->input->post('atype') . ' Analysis';
		if($this->input->post('atype') == 'Weekly') {
			$date = date("Y-m-d", strtotime("-7 day", strtotime("now")));
			for($i = 0; $i <= 6; $i++) {
				$adata['categories'][$i] = date("d/m", strtotime($date));
				$adata['total_orders'][$i] = intval($this->getDelayed($date));
				$adata['total_delays'][$i] = intval($this->getDelayed($date, TRUE));
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
			}
		} elseif($this->input->post('atype') == 'Monthly') {
			$date = date("Y-m-d", strtotime("-28 day", strtotime("now")));
			for($i = 0; $i <= 3; $i++) {
				$from_date = $date;
				$adata['categories'][$i] = date("d/m", strtotime($date)) . ' - ';
				$date = date("Y-m-d", strtotime("+7 day", strtotime($date)));
				$to_date = $date;
				$adata['categories'][$i] .= date("d/m", strtotime($date));
				$adata['total_orders'][$i] = intval($this->getDelayedRange($from_date, $to_date));
				$adata['total_delays'][$i] = intval($this->getDelayedRange($from_date, $to_date, TRUE));
			}
		}
		echo json_encode($adata);
	}
	public function slot_analysis() {
		$adata['atype']['name'] = 'Slots - ' . $this->input->post('atype') . ' Analysis';
		if($this->input->post('atype') == 'Weekly') {
			$date = date("Y-m-d", strtotime("+1 day", strtotime("now")));
			for($i = 0; $i <= 6; $i++) {
				$adata['categories'][$i] = date("d/m", strtotime($date));
				$results = $this->getSlotsByDate($date);
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
				$adata['filled_slots'][$i] = $results['used'];
				$adata['ufilled_slots'][$i] = $results['left'];
			}
		} elseif($this->input->post('atype') == 'Monthly') {
			$date = date("Y-m-d", strtotime("+1 day", strtotime("now")));
			for($i = 0; $i <= 3; $i++) {
				$from_date = $date;
				$adata['categories'][$i] = date("d/m", strtotime($date)) . ' - ';
				$date = date("Y-m-d", strtotime("+7 day", strtotime($date)));
				$to_date = $date;
				$adata['categories'][$i] .= date("d/m", strtotime($date));
				$results = $this->getSlotsByDateRange($from_date, $to_date);
				$adata['filled_slots'][$i] = $results['used'];
				$adata['ufilled_slots'][$i] = $results['left'];
			}
		}
		echo json_encode($adata);
	}
	public function order_type_analysis() {
		$adata['atype']['name'] = 'Order Types - ' . $this->input->post('atype') . ' Analysis';
		if($this->input->post('atype') == 'Weekly') {
			$date = date("Y-m-d", strtotime("-7 day", strtotime("now")));
			for($i = 0; $i <= 6; $i++) {
				$adata['categories'][$i] = date("d/m", strtotime($date));
				$adata['servicing'][$i] = 0;
				$adata['repair'][$i] = 0;
				$adata['insurance'][$i] = 0;
				$adata['query'][$i] = 0;
				$adata['average'][$i] = 0;
				$results = $this->getOrderByDate($date);
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
				if($results !== NULL) {
					foreach($results as $result) {
						if($result['ServiceId'] == 1) {
							$adata['servicing'][$i] += 1;
						} elseif ($result['ServiceId'] == 2) {
							$adata['repair'][$i] += 1;
						} elseif ($result['ServiceId'] == 4) {
							$adata['insurance'][$i] += 1;
						} elseif($result['ServiceId'] == 3) {
							$adata['query'][$i] += 1;
						}
					}
					$adata['average'][$i] = floatval(intval($adata['servicing'][$i]) + intval($adata['repair'][$i]) + intval($adata['insurance'][$i]) + intval($adata['query'][$i])) / 4;
				}
			}
		} elseif($this->input->post('atype') == 'Monthly') {
			$date = date("Y-m-d", strtotime("-28 day", strtotime("now")));
			for($i = 0; $i <= 3; $i++) {
				$from_date = $date;
				$adata['categories'][$i] = date("d/m", strtotime($date)) . ' - ';
				$date = date("Y-m-d", strtotime("+7 day", strtotime($date)));
				$to_date = $date;
				$adata['categories'][$i] .= date("d/m", strtotime($date));
				$adata['servicing'][$i] = 0;
				$adata['repair'][$i] = 0;
				$adata['insurance'][$i] = 0;
				$adata['query'][$i] = 0;
				$adata['average'][$i] = 0;
				$results = $this->getOrderByDateRange($from_date, $to_date);
				if($results !== NULL) {
					foreach($results as $result) {
						if($result['ServiceId'] == 1) {
							$adata['servicing'][$i] += 1;
						} elseif ($result['ServiceId'] == 2) {
							$adata['repair'][$i] += 1;
						} elseif ($result['ServiceId'] == 4) {
							$adata['insurance'][$i] += 1;
						} elseif($result['ServiceId'] == 3) {
							$adata['query'][$i] += 1;
						}
					}
					$adata['average'][$i] = floatval(intval($adata['servicing'][$i]) + intval($adata['repair'][$i]) + intval($adata['insurance'][$i]) + intval($adata['query'][$i])) / 4;
				}
			}
		}
		echo json_encode($adata);
	}
	private function getSlotsByDate($date) {
		$this->load->model('servicecenter_m');
		$this->servicecenter_m->erase_expired_slots();
		$this->db->select('slots.SlotId, slots.Slots, COUNT(SlotBufferId) AS BufferedSlots');
		$this->db->from('slots');
		$this->db->join('slotsbuffer', 'slotsbuffer.SlotId = slots.SlotId', 'left');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('slots.Day', $date);
		$this->db->group_by('slots.SlotId');
		$this->db->limit(12);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			$default_slots = $this->servicecenter_m->get_by(array(
				'ScId' => intval($this->session->userdata('v_sc_id')),
			), TRUE)->DefaultSlots;
			$return_array['left'] = intval($default_slots) * 12;
			$return_array['used'] = 0;
			return $return_array;
		} else {
			$return_array['left'] = 0;
			$return_array['used'] = 0;
			foreach ($results as $result) {
				$return_array['left'] += intval($result['Slots']) - intval($result['BufferedSlots']);
				$return_array['used'] += intval($result['BufferedSlots']);
			}
			return $return_array;
		}
	}
	private function getSlotsByDateRange($from, $to) {
		$this->load->model('servicecenter_m');
		$this->servicecenter_m->erase_expired_slots();
		$this->db->select('slots.SlotId, slots.Slots, COUNT(SlotBufferId) AS BufferedSlots');
		$this->db->from('slots');
		$this->db->join('slotsbuffer', 'slotsbuffer.SlotId = slots.SlotId', 'left');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('slots.Day >=', $from);
		$this->db->where('slots.Day <', $to);
		$this->db->group_by('slots.SlotId');
		$query = $this->db->get();
		$results = $query->result_array();
		$default_slots = $this->servicecenter_m->get_by(array(
				'ScId' => intval($this->session->userdata('v_sc_id')),
			), TRUE)->DefaultSlots;
		if (count($results) == 0) {
			$return_array['left'] = intval($default_slots) * 12 * 7;
			$return_array['used'] = 0;
			return $return_array;
		} else {
			$return_array['left'] = 0;
			$return_array['used'] = 0;
			foreach ($results as $result) {
				$return_array['left'] += intval($result['Slots']) - intval($result['BufferedSlots']);
				$return_array['used'] += intval($result['BufferedSlots']);
			}
			if(count($results) < 84) {
				$return_array['left'] += intval($default_slots) * (84 - count($results));
			}
			return $return_array;
		}
	}
	private function getDelayed($date, $flag = FALSE) {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->where('oservicedetail.ScId', intval($this->session->userdata('v_sc_id')));
		if($flag) {
			$this->db->where('oservicedetail.DelayFlag', 1);
		}
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('odetails.ODate', $date);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function getDelayedRange($from, $to, $flag = FALSE) {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->where('oservicedetail.ScId', intval($this->session->userdata('v_sc_id')));
		if($flag) {
			$this->db->where('oservicedetail.DelayFlag', 1);
		}
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('odetails.ODate >=', $from);
		$this->db->where('odetails.ODate <', $to);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function getOrderByDate($date) {
		$this->db->select('odetails.OId, odetails.ODate, odetails.ServiceId');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->where('oservicedetail.ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('odetails.ODate', $date);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
	private function getOrderByDateRange($from, $to) {
		$this->db->select('odetails.OId, odetails.ODate, odetails.ServiceId');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->where('oservicedetail.ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('odetails.ODate >=', $from);
		$this->db->where('odetails.ODate <', $to);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
	private function getQueried($date, $status_order = 1) {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('odetails.ServiceId', 3);
		if($status_order == 0) {
			$this->db->where('status.Order <', 3);
		} elseif($status_order == 3) {
			$this->db->where('status.Order', 3);
		}
		$this->db->where('odetails.ODate', $date);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function getQueriedRange($from, $to, $status_order = 1) {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('odetails.ServiceId', 3);
		if($status_order == 0) {
			$this->db->where('status.Order <', 3);
		} elseif($status_order == 3) {
			$this->db->where('status.Order', 3);
		}
		$this->db->where('odetails.ODate >=', $from);
		$this->db->where('odetails.ODate <', $to);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function getAllotments($date, $status_order) {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('status.Order', $status_order);
		$this->db->where('odetails.ODate', $date);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function getAllotmentsRange($from, $to, $status_order) {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('status.Order', $status_order);
		$this->db->where('odetails.ODate >=', $from);
		$this->db->where('odetails.ODate <', $to);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function getPdOrdersRange($from, $to) {
		$this->db->select('COUNT(odetails.OId)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('odetails.ODate >=', $from);
		$this->db->where('odetails.ODate <', $to);
		$this->db->where_in('odetails.ServiceId', array(1, 2));
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(odetails.OId)'];
	}
	private function getPdOrders($date) {
		$this->db->select('COUNT(odetails.OId)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('odetails.ODate', $date);
		$this->db->where_in('odetails.ServiceId', array(1, 2));
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(odetails.OId)'];
	}
	private function getPickups($date, $flag = FALSE) {
		if($flag) {
			$this->db->select('COUNT(oamenitydetail.AmId) AS PickCount');
		} else {
			$this->db->select('COUNT(oamenitydetail.AmId) AS PickCount, odetails.OId');
		}
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('oamenitydetail', 'oamenitydetail.OId = odetails.OId', 'left');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('odetails.ODate', $date);
		$this->db->where_in('odetails.ServiceId', array(1, 2));
		$this->db->where_in('oamenitydetail.AmId', array(1, 2));
		if(!$flag) {
			$this->db->group_by('odetails.OId');
		}
		$query = $this->db->get();
		if($flag) {
			$result = $query->row_array();
			return $result['PickCount'];
		} else {
			$result = $query->result_array();
			return count($result);
		}
	}
	private function getPickupsRange($from, $to, $flag = FALSE) {
		if($flag) {
			$this->db->select('COUNT(oamenitydetail.AmId) AS PickCount');
		} else {
			$this->db->select('COUNT(oamenitydetail.AmId) AS PickCount, odetails.OId');
		}
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('oamenitydetail', 'oamenitydetail.OId = odetails.OId', 'left');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('odetails.ODate >=', $from);
		$this->db->where('odetails.ODate <', $to);
		$this->db->where_in('odetails.ServiceId', array(1, 2));
		$this->db->where_in('oamenitydetail.AmId', array(1, 2));
		if(!$flag) {
			$this->db->group_by('odetails.OId');
		}
		$query = $this->db->get();
		if($flag) {
			$result = $query->row_array();
			return $result['PickCount'];
		} else {
			$result = $query->result_array();
			return count($result);
		}
	}
	private function get_tot_orders() {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->where('oservicedetail.ScId', intval($this->session->userdata('v_sc_id')));
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function get_tot_processed() {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where("(oservicedetail.ScId = '" . $this->session->userdata('v_sc_id') . "')", NULL, FALSE);
		$this->db->where("((odetails.ServiceId = '1' AND status.Order = '4')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '2' AND status.Order = '4')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '3' AND status.Order = '3')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '4' AND status.Order = '3'))", NULL, FALSE);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function get_tot_queried() {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->where('oservicedetail.ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('odetails.ServiceId', 3);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function get_tot_delayed() {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('odetails.ODate <', date("Y-m-d", strtotime("now")));
		$this->db->where("(oservicedetail.ScId = '" . $this->session->userdata('v_sc_id') . "')", NULL, FALSE);
		$this->db->where("((odetails.ServiceId = '1' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '2' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '4' AND status.Order < '3' AND status.Order >= '0'))", NULL, FALSE);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
}