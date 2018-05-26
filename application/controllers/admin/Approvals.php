<?php
class Approvals extends G6_Admincontroller {
	public function __construct() {
		parent::__construct();
		$this->data['page'] = 'apps';
		$this->aauth->check_page_access('apps');
	}
	public function index() {
	}
	public function scapps() {
		$this->data['active'] = 'scapps';
		$this->data['rows'] = $this->get_sclist();
		$this->load->view('admin/scapps', $this->data);
	}
	public function pbapps() {
		$this->data['active'] = 'pbapps';
		$this->data['rows'] = $this->get_pblist();
		$this->load->view('admin/pbapps', $this->data);
	}
	public function ecapps() {
		$this->data['active'] = 'ecapps';
		$this->data['rows'] = $this->get_eclist();
		$this->load->view('admin/ecapps', $this->data);
	}
	public function ptapps() {
		$this->data['active'] = 'ptapps';
		$this->data['rows'] = $this->get_ptlist();
		$this->load->view('admin/ptapps', $this->data);
	}
	public function scapprove() {
		if($_POST) {
			$sc_ids = array_map('intval', $this->input->post('sc_ids'));
			$count = 0;
			foreach($sc_ids as $sc_id) {
				$data[$count]['ScId'] = $sc_id;
				$data[$count]['isVerified'] = 1;
				$count += 1;
			}
			$this->db->update_batch('servicecenter', $data, 'ScId');
			$vdata = array('isVerified' => 1);
			$this->db->where_in('ScId', $sc_ids);
			$this->db->update('vendor', $vdata); 
		}
		redirect('/admin/approvals/scapps');
	}
	public function ecapprove() {
		if($_POST) {
			$sc_ids = $this->input->post('sc_ids');
			$count = 0;
			foreach($sc_ids as $sc_id) {
				$data[$count]['ECId'] = intval($sc_id);
				$data[$count]['isVerified'] = 1;
				$count += 1;
			}
			$this->db->update_batch('pucs', $data, 'ECId');
		}
		redirect('/admin/approvals/ecapps');
	}
	public function pbapprove() {
		if($_POST) {
			$sc_ids = $this->input->post('sc_ids');
			$count = 0;
			foreach($sc_ids as $sc_id) {
				$data[$count]['PBId'] = intval($sc_id);
				$data[$count]['isVerified'] = 1;
				$count += 1;
			}
			$this->db->update_batch('petrolbunks', $data, 'PBId');
		}
		redirect('/admin/approvals/pbapps');
	}
	public function ptapprove() {
		if($_POST) {
			$sc_ids = $this->input->post('sc_ids');
			$count = 0;
			foreach($sc_ids as $sc_id) {
				$data[$count]['PTScId'] = intval($sc_id);
				$data[$count]['isVerified'] = 1;
				$count += 1;
			}
			$this->db->update_batch('punctures', $data, 'PTScId');
		}
		redirect('/admin/approvals/ptapps');
	}
	private function get_sclist() {
		$this->db->select('COUNT(oservicedetail.OId) AS OCount, servicecenter.ScId, servicecenter.ScName, servicecenter.Rating, sccontact.Phone, location.LocationName, sccontact.Email');
		$this->db->from('servicecenter');
		$this->db->join('sccontact', 'sccontact.ScId = servicecenter.ScId', 'left');
		$this->db->join('scaddrsplit', 'scaddrsplit.ScId = servicecenter.ScId', 'left');
		$this->db->join('location', 'location.LocationId = scaddrsplit.LocationId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.ScId = servicecenter.ScId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('scaddrsplit.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('servicecenter.isVerified', 0);
		$this->db->group_by('servicecenter.ScId');
		$this->db->order_by('servicecenter.ScId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach($results as $result) {
				$sc_id_array[] = intval($result['ScId']);
			}
			$sc_comps = $this->get_sc_companies($sc_id_array);
			$sc_count = 0;
			$bc_count = 0;
			$bc_array = array();
			while($bc_count < count($sc_comps) && $sc_count < count($sc_id_array)) {
				if(intval($sc_comps[$bc_count]['ScId']) == intval($sc_id_array[$sc_count])) {
					$bc_array[] = convert_to_camel_case($sc_comps[$bc_count]['BikeCompanyName']);
					$bc_count += 1;
				} else {
					if(intval($results[$sc_count]['ScId']) == intval($sc_id_array[$sc_count]) && count($bc_array) > 0) {
						$results[$sc_count]['BikeCompany'] = implode(', ', $bc_array);
					} else {
						$results[$sc_count]['BikeCompany'] = NULL;
					}
					unset($bc_array);
					$bc_array = array();
					$sc_count += 1;
				}
			}
			if(intval($results[count($results) - 1]['ScId']) == intval($sc_id_array[count($sc_id_array) - 1]) && count($bc_array) > 0) {
				$results[count($results) - 1]['BikeCompany'] = implode(', ', $bc_array);
			} else {
				$results[count($results) - 1]['BikeCompany'] = NULL;
			}
			return $results;
		}
	}
	private function get_pblist() {
		$this->db->select('PBId, PBName, ServiceProvider, Phone, Email, LocationName');
		$this->db->from('petrolbunks');
		$this->db->join('location', 'location.LocationId = petrolbunks.LocationId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('petrolbunks.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('isVerified', 0);
		$this->db->order_by('petrolbunks.PBId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	private function get_eclist() {
		$this->db->select('ECId, ECName, LicenseExpiry, Phone, Email, LocationName');
		$this->db->from('pucs');
		$this->db->join('location', 'location.LocationId = pucs.LocationId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('pucs.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('isVerified', 0);
		$this->db->order_by('pucs.ECId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	private function get_ptlist() {
		$this->db->select('PTScId, ScName, Phone, Email, LocationName');
		$this->db->from('punctures');
		$this->db->join('location', 'location.LocationId = punctures.LocationId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('punctures.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('isVerified', 0);
		$this->db->order_by('punctures.PTScId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	private function get_sc_companies($sc_ids) {
		$this->db->select('bikecompany.BikeCompanyName, MapScBc.ScId');
		$this->db->from('MapScBc');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = MapScBc.BikeCompanyId', 'left');
		$this->db->where_in('MapScBc.ScId', $sc_ids);
		$this->db->group_by('MapScBc.ScId, bikecompany.BikeCompanyName');
		$this->db->order_by('MapScBc.ScId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		return $results;
	}
}