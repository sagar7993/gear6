<?php
class Users extends G6_Admincontroller {
	public function __construct() {
		parent::__construct();
		$this->data['page'] = 'user';
		$this->aauth->check_page_access('user');
	}
	public function index() {
		$this->data['active'] = 'dashboard';
		$this->load->view('admin/uoverview', $this->data);
	}
	public function ulist() {
		$this->data['active'] = 'user_list';
		$this->data['rows'] = $this->get_ulist();
		$this->load->view('admin/ulist', $this->data);
	}
	public function reminders() {
		$this->data['active'] = 'reminders';
		$this->data['reminders'] = $this->getReminderTypes();
		$this->load->view('admin/reminders', $this->data);
	}
	public function udetails($UserId = NULL) {
		if($UserId === NULL) {
			redirect(site_url('admin/users/ulist'));
		} else {
			$this->data['active'] = 'user_list';
			$this->data['UserId'] = $UserId;
			$this->load->model('city_m');
			$this->data['cities'] = $this->city_m->get_by(array('isEnabled' => 1));
			$this->get_user_addresses($UserId);
			$this->load->view('admin/udetails', $this->data);
		}
	}
	public function writeto() {
		$this->data['active'] = 'feedback_user';
		$this->load->model('servicecenter_m');
		$this->data['scs'] = $this->servicecenter_m->get();
		$this->load->view('admin/awriteto', $this->data);
	}
	public function get_service_names() {
		$this->db->select('service.ServiceName');
		$this->db->from('service');
		$this->db->where('service.isEnabled', 1);
		$this->db->where('service.ServiceId <=', 4);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return array();
		} else {
			return $results;
		}
	}
	public function uodetails($UserId = NULL) {
		if($UserId === NULL) {
			redirect(site_url('admin/users/ulist'));
		} else {
			$this->data['active'] = 'user_list';
			$this->load->model('odetails_m');
			$this->load->model('amenity_m');
			$this->load->model('statushistory_m');
			$this->load->model('executive_m');
			$this->load->model('user_m');
			$this->load->model('fupstatus_m');
			$this->load->model('opaymtdetail_m');
			$this->data['userinfo'] = $this->user_m->get(intval($UserId));
			$oids = $this->odetails_m->get_oids_user($UserId);
			$this->data['service_name'] = $this->get_service_names();
			for ($i = 0; $i < count($this->data['service_name']); $i++) {
				$counter[$this->data['service_name'][$i]['ServiceName']] = 0;
			}
			if (count($oids) > 0) {
				foreach($oids as $oid) {
					$service_details = $this->odetails_m->get_stype_by_oid($oid['OId']);
					$sername = $service_details['ServiceName'];
					$this->data['sname'][$sername][$counter[$sername]] = $sername;
					$this->data['odates'][$sername][$counter[$sername]] = $oid['ODate'];
					$this->data['stypes'][$sername][$counter[$sername]] = $sername;
					$this->data['serid'][$sername][$sername][$counter[$sername]] = intval($service_details['ServiceId']);
					$this->data['ex_rtime_updates'][$sername][$counter[$sername]] = $this->executive_m->get_ex_fup_rtime_supdates_web($oid['OId'], 1);
					$this->data['OIds'][$sername][$counter[$sername]] = $oid['OId'];
					$this->data['ex_fup_updates'][$sername][$counter[$sername]] = $this->executive_m->get_ex_fup_rtime_supdates_web($oid['OId']);
					$this->data['ex_pre_servicing_updates'][$sername][$counter[$sername]] = $this->executive_m->get_ex_ps_updates_web($oid['OId']);
					$this->data['fupstathistory'][$sername][$counter[$sername]] = $this->fupstatus_m->get_fupstat_history($oid['OId']);
					$this->data['ord_trans'][$sername][$counter[$sername]] = $this->opaymtdetail_m->get_order_transactions($oid['OId']);
					$this->data['mr_remarks'][$sername][$counter[$sername]] = $service_details['MRRemarks'];
					$sc_details = $this->odetails_m->get_scenter_by_oid($oid['OId']);
					$this->data['scenters'][$sername][$counter[$sername]] = $sc_details;
					$bike_model_details = $this->odetails_m->get_bm_by_oid($oid['OId']);
					$this->data['bikemodels'][$sername][$counter[$sername]] = $bike_model_details['BikeCompanyName'] . ' ' . $bike_model_details['BikeModelName'];
					$this->data['timeslots'][$sername][$counter[$sername]] = $this->odetails_m->get_timeslot_by_oid($oid['OId']);
					$this->data['paymodes'][$sername][$counter[$sername]] = $this->odetails_m->get_paymode_by_oid($oid['OId']);
					$user_details = $this->odetails_m->get_user_address($oid['OId']);
					$this->data['uaddresses'][$sername][$counter[$sername]] = $user_details['address'];
					if (intval($service_details['ServiceId']) == 4) {
						$this->data['insren_details'][$sername][$counter[$sername]] = $this->odetails_m->get_insren_details($oid['OId']);
					}
					if (intval($service_details['ServiceId']) != 3) {
						$this->data['scaddresses'][$sername][$counter[$sername]] = $this->odetails_m->get_sc_address($sc_details[0]['ScId']);
						$this->data['estprices'][$sername][$counter[$sername]] = $this->amenity_m->get_est_prices_by_oid($oid['OId']);
						$this->data['opriceses'][$sername][$counter[$sername]] = $this->statushistory_m->get_oprices($oid['OId']);
					}
					$counter[$sername] += 1;
				}
			}
			$this->load->view('admin/uodetails', $this->data);
		}
	}
	private function get_user_addresses($UserId) {
		$this->load->model('user_m');
		$this->data['user_addresses'] = $this->user_m->get_user_addresses($UserId);
	}
	private function get_ulist() {
		$this->db->select('COUNT(odetails.OId) AS OCount, user.UserId, user.UserName, user.Email, user.Phone, location.LocationName, user.DOB, user.Timestamp');
		$this->db->from('user');
		$this->db->join('odetails', 'odetails.UserId = user.UserId', 'left');
		$this->db->join('useraddr', 'useraddr.UserId = user.UserId', 'left');
		$this->db->join('location', 'location.LocationId = useraddr.LocationId', 'left');
		$this->db->group_by('user.UserId');
		$this->db->order_by('user.Timestamp', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach($results as &$result) {
				$result['DOB'] = date('d-m-Y', strtotime($result['DOB']));
			}
			return $results;
		}
	}
	public function save_reminder() {
		if($_POST) {
			$reminders = $_POST['reminder'];
			$reminders = json_decode(urldecode($reminders), TRUE);
			$this->db->insert_batch('reminder_settings', $reminders);
		}
		redirect(site_url('admin/users/reminders'));
	}
	public function top_ten_customers_analysis() {
		$udata['utype']['name'] = 'Top 10 Customers - ' . $this->input->post('utype') . ' Analysis';
		if($this->input->post('utype') == 'Weekly') {
			$date = date("Y-m-d", strtotime("-7 day", strtotime("now")));
			for($i = 0; $i <= 6; $i++) {
				$udata['categories'][$i] = date("d/m", strtotime($date));
				$orders = $this->getCustomerOrders($date);
				foreach ($orders as &$order) {
					$order['actualDate'] = $order['date'];
					$order['date'] = date("d/m", strtotime($order['date']));
					$udata['user_ids'][$order['user_id']] = $order['user_id'];
				}
				$udata['user'][$i] = $orders;
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
			}
		} elseif($this->input->post('utype') == 'Monthly') {
			$date = date("Y-m-d", strtotime("-28 day", strtotime("now")));
			for($i = 0; $i <= 3; $i++) {
				$from_date = $date;
				$udata['categories'][$i] = date("d/m", strtotime($date)) . ' - ';
				$date = date("Y-m-d", strtotime("+7 day", strtotime($date)));
				$to_date = $date;
				$udata['categories'][$i] .= date("d/m", strtotime($date));
				$orders = $this->getCustomerOrdersRange($from_date, $to_date);
				foreach ($orders as &$order) {
					$order['actualDate'] = $order['date'];
					$order['date'] = date("d/m", strtotime($order['date']));
					$udata['user_ids'][$order['user_id']] = $order['user_id'];
				}
				$udata['user'][$i] = $orders;
			}
		}
		$udata['user_ids'] = array_values($udata['user_ids']);
		echo json_encode($udata);
	}
	private function getCustomerOrders($date) {
		header("Content-type:application/json");
		$this->db->select('COUNT(odetails.OId) AS count, user.UserId AS user_id, user.UserName AS name, odetails.ODate AS date');
		$this->db->from('odetails');
		$this->db->join('user', 'user.UserId = odetails.UserId');
		$this->db->where('odetails.ODate', $date);
		$this->db->group_by('user.UserId');
		$this->db->order_by('count', 'desc');
		$this->db->limit(10);
		$query = $this->db->get();
		$results = $query->result_array();
		return $results;
	}
	private function getCustomerOrdersRange($from, $to) {
		header("Content-type:application/json");
		$this->db->select('COUNT(odetails.OId) AS count, user.UserId AS user_id, user.UserName AS name, odetails.ODate AS date');
		$this->db->from('odetails');
		$this->db->join('user', 'user.UserId = odetails.UserId');
		$this->db->where('odetails.ODate >=', $from);
		$this->db->where('odetails.ODate <', $to);
		$this->db->group_by('user.UserId');
		$this->db->order_by('count', 'desc');
		$this->db->limit(10);
		$query = $this->db->get();
		$results = $query->result_array();
		return $results;
	}
	private function getReminderTypes() {
		$this->db->select('*');
		$this->db->from('reminder_types');
		$query = $this->db->get();
		$results = $query->result_array();
		return json_encode($results);
	}
}