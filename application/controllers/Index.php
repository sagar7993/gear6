<?php
class Index extends G6_Usercontroller {
	public function __construct() {
		parent::__construct();
		if($this->session->userdata('id') != NULL) {
			$this->load->model('user_m');
			$this->data['current_user'] = $this->user_m->get_by(array('UserId' => intval($this->session->userdata('id'))), TRUE);
		} else {
			$this->data['current_user'] = NULL;
		}
	}
	public function index () {
		$this->check_visitor_count();
		if($this->input->cookie('is_referred_by_id') != "") {
			$this->data['ref_signup_flag'] = 1;
		}
		if(isset($_GET['referer']) && $_GET['referer'] == 'promo.gear6.in') {
			$this->set_query_cookie('referer', 'promo.gear6.in');
		}
		if(isset($_GET['updtsuc']) && $_GET['updtsuc'] == 1) {
			$this->data['show_succ'] = 1;
		}
		if (isset($this->data['city_id'])) {
			$this->data['city_row'] = $city_row = $this->city_m->get($this->data['city_id']);
			$this->data['adv_time'] = intval($city_row->AdvTime) / 24;
			$curr_hour = intval(date("H", time()));
			if($curr_hour >= 18) {
				$this->data['adv_time'] += 1;
			}
		}
		$this->load->view('user/nhome', $this->data);
	}
	private function check_visitor_count() {
		if($this->input->cookie('g6data') == "" || $this->input->cookie('g6data') === NULL) {
			$cookie = array(
				'name'   => 'g6data',
				'value'  => 'iamhere',
				'expire' => '600',
				'secure' => FALSE
			);
			$this->input->set_cookie($cookie);
			$this->load->model('g6data_m');
			$count = intval($this->g6data_m->get(1)->SiteVisitCount);
			$ncount['SiteVisitCount'] = $count + 1;
			$this->db->where('G6DataId', 1);
			$this->db->update('g6data', $ncount);
			$this->data['visitor_count'] = $count + 1;
		}
	}
	private function set_query_cookie($name, $value) {
		$cookie = array(
			'name'   => $name,
			'value'  => $value,
			'expire' => '86500',
			'secure' => FALSE
		);
		$this->input->set_cookie($cookie);
	}
	public function appdownload() {
		$os = $this->get_user_agent();
		if($os == 'android') {
			redirect('https://play.google.com/store/apps/details?id=in.gear6');
		} elseif($os == 'iphone') {
			redirect('https://itunes.apple.com/ca/app/gear6/id1101618850?mt=8');
		} else {
			redirect('https://www.gear6.in');
		}
	}
	private function get_user_agent() {
		$this->load->library('user_agent', NULL, 'agent');
		if($this->agent->is_mobile('iphone')) {
			return 'iphone';
		} elseif($this->agent->is_mobile('android')) {
			return 'android';
		} else {
			return 'mob';
		}
	}
}