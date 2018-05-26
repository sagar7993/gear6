<?php
class Mvendors extends G6_Admincontroller {
	public function __construct() {
		parent::__construct();
		$this->data['page'] = 'mvendor';
		$this->aauth->check_page_access('mvendor');
	}
	public function index() {
		$this->data['active'] = 'add_vendor';
		$this->load->model('city_m');
		$this->load->model('servicecenter_m');
		$this->data['cities'] = $this->city_m->get_by(array('isEnabled' => 1));
		$this->data['services'] = $this->servicecenter_m->get_sc_names();
		$this->load->view('admin/avendor', $this->data);
	}
	public function vedit() {
		$this->data['active'] = 'mod_vendor';
		$this->data['rows'] = $this->get_vlist();
		$this->data['bc_dict'] = $this->get_bc_dict();
		$this->load->view('admin/mvendors', $this->data);
	}
	public function modify_vendor() {
		if($_POST) {
			$vdata['UserPrivilege'] = $this->input->post('new_priv');
			$this->load->model('vendor_m');
			$this->vendor_m->save($vdata, intval($this->input->post('v_id')));
			redirect(site_url('admin/mvendors/vedit'));
		}
	}
	public function create_vendor() {
		if($_POST) {
			$this->load->model('vendor_m');
			$fields = array('sc_id', 'gender', 'upriv', 'fname', 'dob', 'phone', 'email', 'address');
			$data_fields = array('ScId', 'Gender', 'UserPrivilege', 'VendorName', 'DOB', 'Phone', 'Email', 'Address');
			$count = 0;
			$vdata = array();
			$test = TRUE;
			foreach($fields as $field) {
				if(!(bool)$this->input->post($field)) {
					$test = FALSE;
				}
				$vdata[$data_fields[$count]] = $this->input->post($field);
				$count += 1;
			}
			$vdata['AltPhone'] = $this->input->post('alt_ph');
			$vdata['Pwd'] = generateUniqueString(8);
			$vdata['Salt'] = generate_hash(generateUniqueString(8));
			if($this->vendor_m->is_unique_ph($vdata['Phone']) && $test) {
				$this->vendor_m->save($vdata);
				redirect(site_url('admin/mvendors/vedit'));
			} else {
				if($test) {
					$this->data['err_phone'] = "This phone is already registered with other vendor. Please double check.";
				} else {
					$this->data['err_phone'] = "Nah! You cannot do that";
				}
				$this->data['active'] = 'add_vendor';
				$this->load->model('city_m');
				$this->load->model('servicecenter_m');
				$this->data['cities'] = $this->city_m->get_by(array('isEnabled' => 1));
				$this->data['services'] = $this->servicecenter_m->get_sc_names();
				$this->load->view('admin/avendor', $this->data);
			}
		}
	}
	public function get_scs_for_city() {
		if($_POST) {
			$this->db->select('servicecenter.ScId, servicecenter.ScName');
			$this->db->from('servicecenter');
			$this->db->join('scaddrsplit', 'scaddrsplit.ScId = servicecenter.ScId', 'left');
			if(intval($this->input->post('city_id')) != -1) {
				$this->db->where('scaddrsplit.CityId', intval($this->input->post('city_id')));
			}
			$this->db->group_by('servicecenter.ScId');
			$this->db->order_by('servicecenter.ScId', 'asc');
			$query = $this->db->get();
			$results = $query->result_array();
			if (count($results) == 0) {
				echo NULL;
			} else {
				$data = '<option disabled selected style="display:none;" value="">Service Center</option>';
				foreach($results as $result) {
					$data .= '<option value="' . $result['ScId'] . '">' . convert_to_camel_case($result['ScName']) . '</option>';
				}
				echo $data;
			}
		}
	}
	private function get_vlist() {
		$this->db->select('vendor.VendorId, vendor.VendorName, vendor.Pwd, vendor.PwdCheck, vendor.UserPrivilege, servicecenter.ScId, servicecenter.ScName, vendor.Phone');
		$this->db->from('vendor');
		$this->db->join('servicecenter', 'servicecenter.ScId = vendor.ScId', 'left');
		$this->db->join('sccontact', 'sccontact.ScId = servicecenter.ScId', 'left');
		$this->db->group_by('vendor.VendorId');
		$this->db->order_by('vendor.VendorId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach($results as &$result) {
				if(intval($result['PwdCheck']) == 1) {
					$result['Phone'] .= ' (Pwd: ' . $result['Pwd'] . ')';
				}
			}
			return $results;
		}
	}
	private function get_bc_dict() {
		$sc_ids = $this->get_sc_companies();
		$results = array();
		foreach($sc_ids as $sc_id) {
			if(isset($results[intval($sc_id['ScId'])]) && $results[intval($sc_id['ScId'])] != '') {
				$results[intval($sc_id['ScId'])] .= ', ' . convert_to_camel_case($sc_id['BikeCompanyName']);
			} else {
				$results[intval($sc_id['ScId'])] = convert_to_camel_case($sc_id['BikeCompanyName']);
			}
		}
		return $results;
	}
	private function get_sc_companies() {
		$this->db->select('bikecompany.BikeCompanyName, MapScBc.ScId');
		$this->db->from('MapScBc');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = MapScBc.BikeCompanyId', 'left');
		$this->db->group_by('MapScBc.ScId, bikecompany.BikeCompanyName');
		$this->db->order_by('MapScBc.ScId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		return $results;
	}
}