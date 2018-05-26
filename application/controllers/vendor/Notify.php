<?php
class Notify extends G6_Vendorcontroller {
	public function __construct() {
		parent::__construct();
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');
	}
	public function nocount() {
		$results1 = $this->get_not_orders('isNotified', 0);
		$results2 = $this->get_not_orders('isNotified', 3);
		$text = strval(count($results1) + count($results2));
		echo "data: $text\n\n";
		flush();
	}
	public function nfcount() {
		$results = $this->get_not_fbacks();
		$text = strval(count($results));
		echo "data: $text\n\n";
		flush();
	}
	public function nffeed() {
		$results = $this->get_not_fbacks();
		if(count($results) > 0) {
			$text = '<li class="header">You have ' . strval(count($results)) . ' new messages</li>';
			foreach($results as $result) {
				$text .= '<li><ul class="menu"><li><a href="' . site_url('vendor/feedback/feedview/' . $result['OId']) . '"><i class="nav-icon1 material-icons left">local_activity</i>';
				$text .= 'Feedback received for: ' . $result['OId'];
				$text .= '</a></li></ul></li>';
			}
			$text .= '<li class="footer vnav-footer"><a href="' . base_url() . 'vendor/feedback">View all Messages</a></li>';
		} else {
			$text = '<li class="header">You have no new messages</li>';
			$text .= '<li class="footer vnav-footer"><a href="' . base_url() . 'vendor/feedback">View all Messages</a></li>';
		}
		echo "data: $text\n\n";
		flush();
	}
	public function nofeed() {
		$results1 = $this->get_not_orders('isNotified', 0);
		$results2 = $this->get_not_orders('isNotified', 3);
		$count = count($results1);
		$count1 = count($results2);
		if($count > 0 || $count1 > 0) {
			$text = '<li class="header">You have ' . strval($count + $count1) . ' new notifications</li>';
			if($count1 > 0) {
				foreach($results2 as $result) {
					$text .= '<li><ul class="menu"><li><a href="' . site_url('vendor/odetail/show/' . $result['OId']) . '"><i class="nav-icon1 material-icons left">local_activity</i>';
					$text .= 'User confirmed amount of ' . $result['OId'];
					$text .= '</a></li></ul></li>';
				}
			}
			if($count > 0) {
				foreach($results1 as $result) {
					$text .= '<li><ul class="menu"><li class="height30px"><a href="' . site_url('vendor/odetail/show/' . $result['OId']) . '"><i class="nav-icon1 material-icons left">local_activity</i>';
					$text .= 'A new order ' . $result['OId'] . ' was placed';
					$text .= '</a></li></ul></li>';
				}
			}
			$text .= '<li class="footer vnav-footer"><a href="' . base_url() . 'vendor/unallotted">View all Orders</a></li>';
		} else {
			$text = '<li class="header">You have no new notifications</li>';
			$text .= '<li class="footer vnav-footer"><a href="' . base_url() . 'vendor/unallotted">View all Orders</a></li>';
		}
		echo "data: $text\n\n";
		flush();
	}
	private function get_not_fbacks() {
		$this->db->select('OId');
		$this->db->from('oservicedetail');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('isFbNotified', 1);
		$this->db->order_by('OServiceId', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		return $results;
	}
	private function get_not_orders($ntype, $nval) {
		$this->db->select('OId');
		$this->db->from('oservicedetail');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where($ntype, $nval);
		$this->db->order_by('OServiceId', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		return $results;
	}
}