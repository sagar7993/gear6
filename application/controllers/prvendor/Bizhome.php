<?php
class Bizhome extends G6_Prvendorcontroller {
	public function __construct() {
		parent::__construct();
	}
	public function index () {
		$this->data['h1'] = 'Dashboard';
		$this->data['content'] = 'Overview and Analysis of all Orders, Status, Delays, Pick-Ups';
		$this->data['bc'] = 'Dashboard';
		$this->data['active'] = 'dashboard';
		$this->data['page'] = 'dashboard';
		$this->data['tot_orders'] = intval($this->get_tot_orders());
		$this->data['tot_processed'] = intval($this->get_tot_processed());
		$this->data['tot_delayed'] = intval($this->get_tot_delayed());
		$this->load->view('prvendor/home', $this->data);
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
	public function order_type_analysis() {
		$adata['atype']['name'] = 'Order Types - ' . $this->input->post('atype') . ' Analysis';
		if($this->input->post('atype') == 'Weekly') {
			$date = date("Y-m-d", strtotime("-7 day", strtotime("now")));
			for($i = 0; $i <= 6; $i++) {
				$adata['categories'][$i] = date("d/m", strtotime($date));
				$adata['servicing'][$i] = 0;
				$adata['repair'][$i] = 0;
				$adata['insurance'][$i] = 0;
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
						}
					}
					$adata['average'][$i] = floatval(intval($adata['servicing'][$i]) + intval($adata['repair'][$i]) + intval($adata['insurance'][$i])) / 4;
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
						}
					}
					$adata['average'][$i] = floatval(intval($adata['servicing'][$i]) + intval($adata['repair'][$i]) + intval($adata['insurance'][$i])) / 4;
				}
			}
		}
		echo json_encode($adata);
	}
	private function getDelayed($date, $flag = FALSE) {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
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
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
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
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
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
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
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
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
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
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
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
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
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
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
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
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
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
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
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
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
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
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
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
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function get_tot_processed() {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
		$this->db->where("((odetails.ServiceId = '1' AND status.Order = '4')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '2' AND status.Order = '4')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '3' AND status.Order = '3')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '4' AND status.Order = '3'))", NULL, FALSE);
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
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
		$this->db->where("((odetails.ServiceId = '1' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '2' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '4' AND status.Order < '3' AND status.Order >= '0'))", NULL, FALSE);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	public function sc_order_type_analysis() {
		$this->db->select('servicecenter.ScId, servicecenter.ScName');
		$this->db->from('servicecenter');
		$this->db->where_in('servicecenter.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
		$service_center = $this->db->get()->result_array();
		$adata['name'] = 'Service Center Order Types - ' . $this->input->get('atype') . ' Analysis';
		$adata['services'] = array('Periodic Servicing', 'Repair', 'Insurance Renewal');
		if($this->input->post('atype') == 'Weekly') {
			$days = 7;
		} elseif($this->input->post('atype') == 'Monthly') {
			$days = 30;
		} elseif($this->input->post('atype') == 'All') {
			$days = NULL;
		}
		foreach ($service_center as $sc) {
			$adata['categories'][] = $sc['ScName']; $adata['scid'][] = $sc['ScId'];
			$adata['Periodic Servicing'][] = $this->getVendorOrders($sc['ScId'], 1, $days);
			$adata['Repair'][] = $this->getVendorOrders($sc['ScId'], 2, $days);
			$adata['Insurance Renewal'][] = $this->getVendorOrders($sc['ScId'], 4, $days);
		}
		echo json_encode($adata);
	}
	private function getVendorOrders($ScId, $ServiceId, $days) {
		$startDate = date("Y-m-d", strtotime("-" . $days . " day", strtotime("now"))); $endDate = date("Y-m-d", strtotime("now"));
		$this->db->select('COUNT(odetails.OId) AS count')->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId');
		$this->db->join('servicecenter', 'servicecenter.ScId = oservicedetail.ScId');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		if($days != NULL && $days > 0) {
			$this->db->where('odetails.ODate >=', $startDate);
			$this->db->where('odetails.ODate <=', $endDate);
		}
		$this->db->where('odetails.ServiceId', $ServiceId);
		$this->db->where('servicecenter.ScId', $ScId);
		$this->db->where('servicecenter.isVerified', 1);
		$this->db->group_by('servicecenter.ScId');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) > 0) {
			return intval($results[0]['count']);
		} else {
			return 0;
		}
	}
	public function sc_breakdown_analysis() {
		$this->db->select('servicecenter.ScId, servicecenter.ScName');
		$this->db->from('servicecenter');
		$this->db->where_in('servicecenter.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
		$service_center = $this->db->get()->result_array();
		$adata['name'] = 'Service Center Order Types - ' . $this->input->get('atype') . ' Analysis';
		$adata['services'] = array('Breakdown', 'Normal');
		if($this->input->post('atype') == 'Weekly') {
			$days = 7;
		} elseif($this->input->post('atype') == 'Monthly') {
			$days = 30;
		} elseif($this->input->post('atype') == 'All') {
			$days = NULL;
		}
		foreach ($service_center as $sc) {
			$adata['categories'][] = $sc['ScName']; $adata['scid'][] = $sc['ScId'];
			$adata['Breakdown'][] = $this->getBreakdownOrders($sc['ScId'], 1, $days);
			$adata['Normal'][] = $this->getBreakdownOrders($sc['ScId'], 0, $days);
		}
		echo json_encode($adata);
	}
	private function getBreakdownOrders($ScId, $isBreakdown, $days) {
		$startDate = date("Y-m-d", strtotime("-" . $days . " day", strtotime("now"))); $endDate = date("Y-m-d", strtotime("now"));
		$this->db->select('COUNT(odetails.OId) AS count')->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId');
		$this->db->join('servicecenter', 'servicecenter.ScId = oservicedetail.ScId');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		if($days != NULL && $days > 0) {
			$this->db->where('odetails.ODate >=', $startDate);
			$this->db->where('odetails.ODate <=', $endDate);
		}
		$this->db->where('odetails.isBreakdown', $isBreakdown);
		$this->db->where('servicecenter.ScId', $ScId);
		$this->db->where('servicecenter.isVerified', 1);
		$this->db->group_by('servicecenter.ScId');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) > 0) {
			return intval($results[0]['count']);
		} else {
			return 0;
		}
	}
}