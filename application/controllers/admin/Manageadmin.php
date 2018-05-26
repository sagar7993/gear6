<?php
class Manageadmin extends G6_Admincontroller {
	public function __construct() {
		parent::__construct();
		$this->data['page'] = 'manageAdmin';
		$this->aauth->check_page_access('manageAdmin');
	}
	public function index() {
		$this->data['active'] = 'add_admin';
		$this->load->model('city_m');
		$this->data['cities'] = $this->city_m->get();
		$this->load->view('admin/addadmin', $this->data);
	}
	public function editAdmin() {
		$this->load->model('city_m');
		$this->data['cities'] = $this->city_m->get();
		$this->data['rows'] = $this->get_admin_list();
		$this->data['active'] = 'edit_admin';
		$this->load->view('admin/editadmin', $this->data);
	}
	public function delete_admin() {
		if($_POST) {
			$this->load->model('admin_m');
			$adminId = intval($this->input->post('delete_admin_id'));
			$this->admin_m->delete($adminId);
			redirect(site_url('admin/manageadmin/editadmin'));
		}
	}
	public function modify_admin() {
		if($_POST) {
			$this->load->model('admin_m');
			$adminData['UserPrivilege'] = $this->input->post('new_priv');
			$adminData['AdminName'] = $this->input->post('new_name');
			$adminData['Phone'] = $this->input->post('new_phone');
			$adminData['Email'] = $this->input->post('new_email');
			if(intval($this->session->userdata('a_city_id')) > 0) {
				$adminData['CityId'] = intval($this->session->userdata('a_city_id'));
			} else {
				$adminData['CityId'] = $this->input->post('new_city_id');
			}
			if($this->input->post('new_password') && $this->input->post('new_password') != "" && $this->input->post('new_password') != NULL && strlen($this->input->post('new_password')) > 0) {
				$adminData['Salt'] = generate_hash(generateUniqueString(8));
				$adminData['Pwd'] = generate_salted_hash($this->input->post('new_password'), $adminData['Salt']);
			}
			$adminData['PwdCheck'] = 0;
			$adminData['LoginMode'] = "Email";
			if($this->admin_m->is_own_ph($adminData['Phone'], intval($this->input->post('a_id')))) {
				$this->admin_m->save($adminData, intval($this->input->post('a_id')));
			} else {
				$this->data['err_phone'] = "This phone is already registered with another admin. Please double check.";
			}
			redirect(site_url('admin/manageadmin/editadmin'));
		}
	}
	public function create_admin() {
		if($_POST) {
			$this->load->model('admin_m');
			$fields = array("city_id", "fname", "phone", "email", "upriv");
			$data_fields = array('CityId', 'AdminName', 'Phone', 'Email', 'UserPrivilege');
			$count = 0;
			$adminData = array();
			$test = TRUE;
			if(intval($this->session->userdata('a_city_id')) > 0) {
				$_POST['city_id'] = intval($this->session->userdata('a_city_id'));
			} else {
				$_POST['city_id'] = intval($this->input->post('city_id'));
			}
			foreach($fields as $field) {
				if(!(bool)$this->input->post($field)) {
					$test = FALSE;
				}
				$adminData[$data_fields[$count]] = $this->input->post($field);
				$count += 1;
			}
			$adminData['Salt'] = generate_hash(generateUniqueString(8));
			$adminData['Pwd'] = generate_salted_hash($this->input->post('password'), $adminData['Salt']);
			$adminData['PwdCheck'] = 0;
			$adminData['LoginMode'] = "Email";
			if($this->admin_m->is_unique_ph($adminData['Phone']) && $test) {
				$this->admin_m->save($adminData);
				redirect(site_url('admin/manageadmin/editadmin'));
			} else {
				redirect(site_url('admin/manageadmin'));
			}
		}
	}
	private function get_admin_list() {
		$this->db->select('*');
		$this->db->from('admin');
		$this->db->join('city', 'city.CityId = admin.CityId');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('admin.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->order_by('admin.AdminId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	private function get_admin_reminder_list() {
		$date = date("Y-m-d H:i:s", strtotime("now"));
		$this->db->select('admin_reminder.*');
		$this->db->from('admin_reminder');
		$this->db->where('timestamp >', $date);
		$this->db->order_by('timestamp', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return array();
		} else {
			foreach ($results as &$result) {
				$remind_to = explode(", ", $result['remind_to']); $names = array();
				if($result['user_type'] == 'Admin') {
					$temp = $this->db->select('AdminName')->from('admin')->where_in('AdminId', $remind_to)->get()->result_array();
					foreach ($temp as $name) {
						$names[] = $name['AdminName'];
					}
				} elseif($result['user_type'] == 'Executive') {
					$temp = $this->db->select('ExecName')->from('executive')->where_in('ExecId', $remind_to)->get()->result_array();
					foreach ($temp as $name) {
						$names[] = $name['ExecName'];
					}
				}
				if($result['send_sms'] == '1') {
					$result['send_sms'] = 'Yes';
				} elseif($result['send_sms'] == 0) {
					$result['send_sms'] = 'No';
				}
				$result['remind_to'] = implode(", ", $names);
				$result['timestamp'] = date('F j, Y, g:i a', strtotime($result['timestamp']));
			}
			return $results;
		}
	}
	public function addadminreminder() {
		$this->data['active'] = 'add_admin_reminder';
		$this->load->model('admin_m');
		$this->data['admin'] = $this->admin_m->get();
		$this->load->model('executive_m');
		$this->data['executive'] = $this->executive_m->get();
		$this->load->view('admin/addadminreminder', $this->data);
	}
	public function editadminreminder() {
		$this->load->model('city_m');
		$this->data['cities'] = $this->city_m->get();
		$this->data['rows'] = $this->get_admin_reminder_list();
		$this->data['active'] = 'editadminreminder';
		$this->load->view('admin/editadminreminder', $this->data);
	}
	public function create_admin_reminder() {
		if($_POST) {
			$reminder = array(); $reminder['timestamp'] = $_POST['reminder_date'] . " " . $_POST['reminder_time'];
			$reminder['description'] = $_POST['description']; $reminder['user_type'] = $_POST['user_type'];
			$reminder['remind_to'] = $_POST['remind_to']; $reminder['send_sms'] = $_POST['send_sms'];
			$reminder['updatedBy'] = $this->session->userdata('a_name'); $reminder['createdBy'] = $this->session->userdata('a_name');
			$this->db->insert('admin_reminder', $reminder);
		}
		redirect(site_url('admin/manageadmin/editadminreminder'));
	}
	public function update_admin_reminder() {
		if($_POST) {
			$reminder = array(); $reminder['updatedBy'] = $this->session->userdata('a_name'); $reminder['is_enabled'] = $_POST['is_enabled'];
			if($_POST['reason'] != NULL && $_POST['reason'] != "") { $reminder['reason'] = $_POST['reason']; } else { $reminder['reason'] = NULL; }
			$this->db->where('id', $_POST['id']); $this->db->update('admin_reminder', $reminder);
		}
		redirect(site_url('admin/manageadmin/editadminreminder'));
	}
}