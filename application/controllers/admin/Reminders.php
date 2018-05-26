<?php
class Reminders extends G6_Admincontroller {
	public function __construct() {
		parent::__construct();
		$this->data['page'] = 'reminders';
		$this->aauth->check_page_access('reminders');
	}
	public function index() {
		$this->data['active'] = 'reminders_service';
		$this->load->view('admin/reminders', $this->data);
	}
	public function service() {
		$this->data['active'] = 'reminders_service';
		$this->load->view('admin/reminders', $this->data);
	}
	public function insurance() {
		$this->data['active'] = 'reminders_insurance';
		$this->load->view('admin/reminders', $this->data);
	}
	public function puc() {
		$this->data['active'] = 'reminders_puc';
		$this->load->view('admin/reminders', $this->data);
	}
	public function birthday() {
		$this->data['active'] = 'reminders_birthday';
		$this->load->view('admin/reminders', $this->data);
	}
	public function save_reminder() {
		if($_POST) {
			$reminders = $_POST['reminder'];
			$reminders = json_decode(urldecode($reminders), TRUE);
			$this->db->insert_batch('user_reminders', $reminders);
		}
		redirect(site_url('admin/reminders'));
	}
}