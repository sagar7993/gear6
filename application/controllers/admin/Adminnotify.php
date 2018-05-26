<?php
class Adminnotify extends G6_Admincontroller {
	public function __construct() {
		parent::__construct();		
		header('Cache-Control: no-cache');
		header("Access-Control-Allow-Methods: *");
		header("Access-Control-Allow-Headers: *");
	}
	private function get_results($flag, $column) {
		$this->db->select('*');
		$this->db->from('admin_notification_flags');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->join('odetails', 'odetails.OId = admin_notification_flags.OId', 'left');
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where($column, $flag);
		$this->db->where($column . '_dismissed != ', $flag);
  		if($column === 'new_delayed_order') {
  			$this->db->where('admin_notification_flags.ODate <', date("Y-m-d", strtotime("now")));
  		}
  		$this->db->order_by('updated_at', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		return $results;
	}
	public function get_notifications() {		
		header('Content-Type: text/event-stream');
		$results = $this->get_results(1, 'new_feedback');
		$text['feedback']['url'] = $text['feedback']['general_url'] = site_url('admin/feedback/vendors');
		if(count($results) > 0) {
			$text['feedback']['count'] = count($results);
			$text['feedback']['html'] = '<li align="center" style="color:black;"><b>' . strval(count($results)) . '</b> New User Feedback</li>';
			foreach($results as $result) {
				$text['feedback']['html'] .= '<li><ul class="menu"><li><a target="_blank" href="' . site_url('admin/feedback/vfeedview/' . $result['OId']) . '"><i class="nav-icon1 material-icons left">local_activity</i>';
				$text['feedback']['html'] .= 'New User Feedback For Order : ' . $result['OId'];
				$text['feedback']['html'] .= '</a></li></ul></li>';
			}
			$text['feedback']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/feedback/vendors">View all User Feedbacks</a></li>';
			$text['feedback']['url'] = site_url('admin/feedback/vfeedview/' . $results[0]['OId']);
		} else {
			$text['feedback']['count'] = 0;
			$text['feedback']['html'] = '<li align="center" style="color:black;">No new User Feedbacks</li>';
			$text['feedback']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/feedback/vendors">View all User Feedbacks</a></li>';
		}
		$results = $this->get_results(1, 'new_user_contact_us');
		$text['usercontactus']['url'] = site_url('admin/ucontactus');
		if(count($results) > 0) {
			$text['usercontactus']['count'] = count($results);
			$text['usercontactus']['html'] = '<li align="center" style="color:black;">' . strval(count($results)) . ' New User Contact Us Requests</li>';
			foreach($results as $result) {
				$text['usercontactus']['html'] .= '<li><ul class="menu"><li><a target="_blank" href="' . site_url('admin/ucontactus') . '"><i class="nav-icon1 material-icons left">local_activity</i>';
				$text['usercontactus']['html'] .= 'New request from : ' . $result['Name'] . ' (' . $result['Phone'] . ')';
				$text['usercontactus']['html'] .= '</a></li></ul></li>';
			}
			$text['usercontactus']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/ucontactus">View all User Contact Us Requests</a></li>';
		} else {
			$text['usercontactus']['count'] = 0;
			$text['usercontactus']['html'] = '<li align="center" style="color:black;">No new user contact us requests</li>';
			$text['usercontactus']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/ucontactus">View all User Contact Us Requests</a></li>';
		}
		$results = $this->get_results(1, 'new_agent_contact_us');
		$text['agentcontactus']['url'] = site_url('admin/agregs');
		if(count($results) > 0) {
			$text['agentcontactus']['count'] = count($results);
			$text['agentcontactus']['html'] = '<li align="center" style="color:black;">' . strval(count($results)) . ' New Agent Contact Us Requests</li>';
			foreach($results as $result) {
				$text['agentcontactus']['html'] .= '<li><ul class="menu"><li><a target="_blank" href="' . site_url('admin/agregs') . '"><i class="nav-icon1 material-icons left">local_activity</i>';
				$text['agentcontactus']['html'] .= 'New request from : ' . $result['Name'] . ' (' . $result['Phone'] . ')';
				$text['agentcontactus']['html'] .= '</a></li></ul></li>';
			}
			$text['agentcontactus']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/agregs">View all Agent Contact Us Requests</a></li>';
		} else {
			$text['agentcontactus']['count'] = 0;
			$text['agentcontactus']['html'] = '<li align="center" style="color:black;">No new agent contact us requests</li>';
			$text['agentcontactus']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/agregs">View all Agent Contact Us Requests</a></li>';
		}
		$results = $this->get_results(1, 'new_payment');
		$text['payment']['url'] = $text['payment']['general_url'] = site_url('admin/orders');
		if(count($results) > 0) {
			$text['payment']['count'] = count($results);
			$text['payment']['html'] = '<li align="center" style="color:black;"><b>' . strval(count($results)) . '</b> New Payment</li>';
			foreach($results as $result) {
				$text['payment']['html'] .= '<li><ul class="menu"><li><a target="_blank" href="' . site_url('admin/orders/odetail/' . $result['OId']) . '"><i class="nav-icon1 material-icons left">local_activity</i>';
				$text['payment']['html'] .= 'New Payment For Order: ' . $result['OId'];
				$text['payment']['html'] .= '</a></li></ul></li>';
			}
			$text['payment']['url'] = site_url('admin/orders/odetail/' . $results[0]['OId']);
		} else {
			$text['payment']['count'] = 0;
			$text['payment']['html'] = '<li align="center" style="color:black;">No new payments</li>';
			$text['payment']['html'] .= '<li class="divider"></li>';
		}
		$results = $this->get_results(1, 'new_bill_updated');
		if(count($results) > 0) {
			$text['payment']['count'] += count($results);
			$text['payment']['html'] .= '<li align="center" style="color:black;"><b>' . strval(count($results)) . '</b> Bill Updated</li>';
			foreach($results as $result) {
				$text['payment']['html'] .= '<li><ul class="menu"><li><a target="_blank" href="' . site_url('admin/orders/odetail/' . $result['OId']) . '"><i class="nav-icon1 material-icons left">local_activity</i>';
				$text['payment']['html'] .= 'Bill Updated For : ' . $result['OId'];
				$text['payment']['html'] .= '</a></li></ul></li>';
			}
			$text['payment']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/orders">View all Orders</a></li>';
			$text['payment']['url'] = site_url('admin/orders/odetail/' . $results[0]['OId']);
		} else {
			$text['payment']['count'] += 0;
			$text['payment']['html'] .= '<li align="center" style="color:black;">No new bill updates</li>';
			$text['payment']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/orders">View all Orders</a></li>';
		}
		$results = $this->get_results(1, 'bike_checkup');
		$text['pickup']['url'] = $text['pickup']['general_url'] = site_url('admin/orders');
		if(count($results) > 0) {
			$text['pickup']['count'] = count($results);
			$text['pickup']['html'] = '<li align="center" style="color:black;"><b>' . strval(count($results)) . '</b> New Bike Checkups</li>';
			foreach($results as $result) {
				$text['pickup']['html'] .= '<li><ul class="menu"><li><a target="_blank" href="' . site_url('admin/orders/odetail/' . $result['OId']) . '"><i class="nav-icon1 material-icons left">local_activity</i>';
				$text['pickup']['html'] .= 'New Bike Checkup : ' . $result['OId'];
				$text['pickup']['html'] .= '</a></li></ul></li>';
			}
			$text['pickup']['url'] = site_url('admin/orders/odetail/' . $results[0]['OId']);
		} else {
			$text['pickup']['count'] = 0;
			$text['pickup']['html'] = '<li align="center" style="color:black;">No new bike checkups</li>';
			$text['pickup']['html'] .= '<li class="divider"></li>';
		}
		$results = $this->get_results(1, 'new_pickup');
		if(count($results) > 0) {
			$text['pickup']['count'] += count($results);
			$text['pickup']['html'] .= '<li align="center" style="color:black;"><b>' . strval(count($results)) . '</b> New Bike Pickup From Customer</li>';
			foreach($results as $result) {
				$text['pickup']['html'] .= '<li><ul class="menu"><li><a target="_blank" href="' . site_url('admin/orders/odetail/' . $result['OId']) . '"><i class="nav-icon1 material-icons left">local_activity</i>';
				$text['pickup']['html'] .= 'New Bike Pickup From Customer : ' . $result['OId'];
				$text['pickup']['html'] .= '</a></li></ul></li>';
			}
			$text['pickup']['url'] = site_url('admin/orders/odetail/' . $results[0]['OId']);
		} else {
			$text['pickup']['count'] += 0;
			$text['pickup']['html'] .= '<li align="center" style="color:black;">No new bike pickups from cusomers</li>';
			$text['pickup']['html'] .= '<li class="divider"></li>';
		}
		$results = $this->get_results(1, 'new_pickup_sc');
		if(count($results) > 0) {
			$text['pickup']['count'] += count($results);
			$text['pickup']['html'] .= '<li align="center" style="color:black;"><b>' . strval(count($results)) . '</b> New Bike Pickup From Service Center</li>';
			foreach($results as $result) {
				$text['pickup']['html'] .= '<li><ul class="menu"><li><a target="_blank" href="' . site_url('admin/orders/odetail/' . $result['OId']) . '"><i class="nav-icon1 material-icons left">local_activity</i>';
				$text['pickup']['html'] .= 'New Bike Pickup From Service Center : ' . $result['OId'];
				$text['pickup']['html'] .= '</a></li></ul></li>';
			}
			$text['pickup']['url'] = site_url('admin/orders/odetail/' . $results[0]['OId']);
		} else {
			$text['pickup']['count'] += 0;
			$text['pickup']['html'] .= '<li align="center" style="color:black;">No new bike pickups from service centers</li>';
			$text['pickup']['html'] .= '<li class="divider"></li>';
		}
		$results = $this->get_results(1, 'new_bike_delivered');
		if(count($results) > 0) {
			$text['pickup']['count'] += count($results);
			$text['pickup']['html'] .= '<li align="center" style="color:black;"><b>' . strval(count($results)) . '</b> New Bike Delivered</li>';
			foreach($results as $result) {
				$text['pickup']['html'] .= '<li><ul class="menu"><li><a target="_blank" href="' . site_url('admin/orders/odetail/' . $result['OId']) . '"><i class="nav-icon1 material-icons left">local_activity</i>';
				$text['pickup']['html'] .= 'New Bike Delivered For : ' . $result['OId'];
				$text['pickup']['html'] .= '</a></li></ul></li>';
			}
			$text['pickup']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/orders">View all Orders</a></li>';
			$text['pickup']['url'] = site_url('admin/orders/odetail/' . $results[0]['OId']);
		} else {
			$text['pickup']['count'] += 0;
			$text['pickup']['html'] .= '<li align="center" style="color:black;">No new bike delivered</li>';
			$text['pickup']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/orders">View all Orders</a></li>';
		}
		$results = $this->get_results(1, 'new_order');
		$text['neworder']['url'] = $text['neworder']['general_url'] = site_url('admin/orders');
		if(count($results) > 0) {
			$text['neworder']['count'] = count($results);
			$text['neworder']['html'] = '<li align="center" style="color:black;"><b>' . strval(count($results)) . '</b> New Order</li>';
			foreach($results as $result) {
				$text['neworder']['html'] .= '<li><ul class="menu"><li><a target="_blank" href="' . site_url('admin/orders/odetail/' . $result['OId']) . '"><i class="nav-icon1 material-icons left">local_activity</i>';
				$text['neworder']['html'] .= 'New Order : ' . $result['OId'];
				$text['neworder']['html'] .= '</a></li></ul></li>';
			}
			$text['neworder']['url'] = site_url('admin/orders/odetail/' . $results[0]['OId']);
		} else {
			$text['neworder']['count'] += 0;
			$text['neworder']['html'] = '<li align="center" style="color:black;">No new orders</li>';
			$text['neworder']['html'] .= '<li class="divider"></li>';
		}
		$results = $this->get_results(1, 'new_breakdown_order');
		if(count($results) > 0) {
			$text['neworder']['count'] += count($results);
			$text['neworder']['html'] .= '<li align="center" style="color:black;"><b>' . strval(count($results)) . '</b> Breakdown Order</li>';
			foreach($results as $result) {
				$text['neworder']['html'] .= '<li><ul class="menu"><li><a target="_blank" href="' . site_url('admin/orders/odetail/' . $result['OId']) . '"><i class="nav-icon1 material-icons left">local_activity</i>';
				$text['neworder']['html'] .= 'Breakdown Order : ' . $result['OId'];
				$text['neworder']['html'] .= '</a></li></ul></li>';
			}
			$text['neworder']['url'] = site_url('admin/orders/odetail/' . $results[0]['OId']);
		} else {
			$text['neworder']['count'] += 0;
			$text['neworder']['html'] .= '<li align="center" style="color:black;">No breakdown orders</li>';
			$text['neworder']['html'] .= '<li class="divider"></li>';
		}
		$results = $this->get_results(1, 'new_order_reschedule');
		if(count($results) > 0) {
			$text['neworder']['count'] += count($results);
			$text['neworder']['html'] .= '<li align="center" style="color:black;"><b>' . strval(count($results)) . '</b> Rescheduled Order</li>';
			foreach($results as $result) {
				$text['neworder']['html'] .= '<li><ul class="menu"><li><a target="_blank" href="' . site_url('admin/orders/odetail/' . $result['OId']) . '"><i class="nav-icon1 material-icons left">local_activity</i>';
				$text['neworder']['html'] .= 'Rescheduled Order : ' . $result['OId'];
				$text['neworder']['html'] .= '</a></li></ul></li>';
			}
			$text['neworder']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/orders">View all Orders</a></li>';
			$text['neworder']['url'] = site_url('admin/orders/odetail/' . $results[0]['OId']);
		} else {
			$text['neworder']['count'] += 0;
			$text['neworder']['html'] .= '<li align="center" style="color:black;">No rescheduled orders</li>';
			$text['neworder']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/orders">View all Orders</a></li>';
		}
		$results = $this->get_results(1, 'new_delayed_order');
		$text['delayedorder']['url'] = $text['delayedorder']['general_url'] = site_url('admin/orders');
		if(count($results) > 0) {
			$text['delayedorder']['count'] = count($results);
			$text['delayedorder']['html'] = '<li align="center" style="color:black;"><b>' . strval(count($results)) . '</b> New Delayed Order</li>';
			foreach($results as $result) {
				$text['delayedorder']['html'] .= '<li><ul class="menu"><li><a target="_blank" href="' . site_url('admin/orders/odetail/' . $result['OId']) . '"><i class="nav-icon1 material-icons left">local_activity</i>';
				$text['delayedorder']['html'] .= 'Delayed Order : ' . $result['OId'];
				$text['delayedorder']['html'] .= '</a></li></ul></li>';
			}
			$text['delayedorder']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/orders">View all Delayed Orders</a></li>';
			$text['delayedorder']['url'] = site_url('admin/orders/odetail/' . $results[0]['OId']);
		} else {
			$text['delayedorder']['count'] = 0;
			$text['delayedorder']['html'] = '<li align="center" style="color:black;">No new delayed orders</li>';
			$text['delayedorder']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/orders">View all Delayed Orders</a></li>';
		}
		$results = $this->get_results(1, 'new_emergency_order');
		$text['emergency']['url'] = site_url('admin/orders/emgorders');
		if(count($results) > 0) {
			$text['emergency']['count'] = count($results);
			$text['emergency']['html'] = '<li align="center" style="color:black;"><b>' . strval(count($results)) . '</b> New Emergency Order</li>';
			foreach($results as $result) {
				$text['emergency']['html'] .= '<li><ul class="menu"><li><a target="_blank" href="' . site_url('admin/orders/emgorders') . '"><i class="nav-icon1 material-icons left">local_activity</i>';
				$text['emergency']['html'] .= 'New Emergency From : ' . $result['Phone'];
				$text['emergency']['html'] .= '</a></li></ul></li>';
			}
			$text['emergency']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/orders/emgorders">View all Emergency Orders</a></li>';
		} else {
			$text['emergency']['count'] = 0;
			$text['emergency']['html'] = '<li align="center" style="color:black;">No new emergency orders</li>';
			$text['emergency']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/orders/emgorders">View all Emergency Orders</a></li>';
		}
		$results = $this->get_results(1, 'new_puncture_order');
		$text['puncture']['url'] = site_url('admin/orders/ptorders');
		if(count($results) > 0) {
			$text['puncture']['count'] = count($results);
			$text['puncture']['html'] = '<li align="center" style="color:black;"><b>' . strval(count($results)) . '</b> New Puncture Order</li>';
			foreach($results as $result) {
				$text['puncture']['html'] .= '<li><ul class="menu"><li><a target="_blank" href="' . site_url('admin/orders/ptorders') . '"><i class="nav-icon1 material-icons left">local_activity</i>';
				$text['puncture']['html'] .= 'New Puncture From : ' . $result['Phone'];
				$text['puncture']['html'] .= '</a></li></ul></li>';
			}
			$text['puncture']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/orders/ptorders">View all Puncture Orders</a></li>';
		} else {
			$text['puncture']['count'] = 0;
			$text['puncture']['html'] = '<li align="center" style="color:black;">No new puncture orders</li>';
			$text['puncture']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/orders/ptorders">View all Puncture Orders</a></li>';
		}
		$results = $this->get_results(1, 'new_renewal_date');
		$text['renewal']['url'] = $text['renewal']['general_url'] = site_url('admin/orders');
		if(count($results) > 0) {
			$text['renewal']['count'] = count($results);
			$text['renewal']['html'] = '<li align="center" style="color:black;">Update Renewal Dates for <b>' . strval(count($results)) . '</b> Orders</li>';
			foreach($results as $result) {
				$text['renewal']['html'] .= '<li><ul class="menu"><li><a target="_blank" href="' . site_url('admin/orders/odetail/' . $result['OId']) . '"><i class="nav-icon1 material-icons left">local_activity</i>';
				$text['renewal']['html'] .= 'Update Renewal Date for : ' . $result['OId'];
				$text['renewal']['html'] .= '</a></li></ul></li>';
			}
			$text['renewal']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/orders">View all Orders</a></li>';
			$text['renewal']['url'] = site_url('admin/orders/odetail/' . $results[0]['OId']);
		} else {
			$text['renewal']['count'] = 0;
			$text['renewal']['html'] = '<li align="center" style="color:black;">No new renewal dates</li>';
			$text['renewal']['html'] .= '<li class="footer vnav-footer"><a target="_blank" href="' . base_url() . 'admin/orders">View all Orders</a></li>';
		}
		$text = json_encode($text);
		echo "data: $text\n\n";
		flush();
	}
}