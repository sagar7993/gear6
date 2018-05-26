<?php
class Adminhome extends G6_Admincontroller {
	public function __construct() {
		parent::__construct();
		$this->data['page'] = 'admin';
	}
	public function index () {
		$this->data['active'] = 'admin_dashboard';
		$this->load->model('city_m');
		$this->data['cities'] = $this->city_m->get(NULL, FALSE, TRUE);
		$rating = $this->get_rating();
		$this->data['rating'] = $rating['rating'];
		$this->data['total_rating'] = $rating['total_rating'];
		$this->load->view('admin/home', $this->data);
	}
	public function change_admin_city() {
		if($_POST) {
			if($this->input->post('CityId') && $this->input->post('CityName')) {
				$this->session->set_userdata('a_city', $this->input->post('CityName'));
				$this->session->set_userdata('a_city_id', $this->input->post('CityId'));
			}
		}
		redirect('/admin');
	}
	private function get_rating() {
		$sql = "SELECT COUNT(*) AS total_rating, ROUND(COALESCE(AVG(rating), 0), 1) AS rating FROM g6rating";
		$query = $this->db->query($sql); $results = $query->result_array();
		$data['rating'] = $results[0]['rating'];
		$data['total_rating'] = $results[0]['total_rating'];
		return $data;
	}
}