<?php
class Unotify extends G6_Usercontroller {
	public function __construct() {
		parent::__construct();
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');
	}
	public function nocount() {
		$results1 = $this->get_not_orders('isNotified', 2);
		$results2 = $this->get_not_orders('isNotified', 4);
		$text = strval(count($results1) + count($results2));
		echo "data: $text\n\n";
		flush();
	}
	public function nofeed() {
		$results1 = $this->get_not_orders('isNotified', 2);
		$results2 = $this->get_not_orders('isNotified', 4);
		$count = count($results1);
		$count1 = count($results2);
		if($count > 0 || $count1 > 0) {
			$text = '<li class="header">You have ' . strval($count + $count1) . ' new notifications</li>';
			if($count > 0) {
				foreach($results1 as $result) {
					$text .= '<li><ul class="menu"><li class="height30px"><a href="#" data-oid="' . $result['OId'] . '" class="set_active_oid_cookie"><i class="nav-icon1 material-icons left">local_activity</i>';
					$text .= 'Additional price details updated for your order ' . $result['OId'];
					$text .= '</a></li></ul></li>';
				}
			}
			if($count1 > 0) {
				foreach($results2 as $result) {
					$text .= '<li><ul class="menu"><li class="height30px"><a href="#" data-oid="' . $result['OId'] . '" class="set_active_oid_cookie"><i class="nav-icon1 material-icons left">local_activity</i>';
					$text .= 'Your order ' . $result['OId'] . ' was approved';
					$text .= '</a></li></ul></li>';
				}
			}
			$text .= '<li class="footer unav-footer"><a href="' . base_url() . 'user/account/corders">View all active orders</a></li>';
		} else {
			$text = '<li class="header">You have no new notifications</li>';
			$text .= '<li class="footer unav-footer"><a href="' . base_url() . 'user/account/corders">View all active orders</a></li>';
		}
		echo "data: $text\n\n";
		flush();
	}
	private function get_not_orders($ntype, $nval) {
		$this->db->select('oservicedetail.OId');
		$this->db->from('oservicedetail');
		$this->db->join('odetails', 'odetails.OId = oservicedetail.OId');
		$this->db->where('odetails.UserId', intval($this->session->userdata('id'))) ;
		$this->db->where($ntype, $nval);
		$this->db->order_by('OServiceId', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		return $results;
	}
}