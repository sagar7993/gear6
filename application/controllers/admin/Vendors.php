<?php
class Vendors extends G6_Admincontroller {
	public function __construct() {
		parent::__construct();
		$this->data['page'] = 'vendor';
		$this->aauth->check_page_access('vendor');
	}
	public function index() {
		$this->data['active'] = 'vendor_oview';
		$this->load->view('admin/voverview', $this->data);
	}
	public function create_prvendor() {
		if($_POST) {
			$this->load->model('prvendor_m');
			$fields = array('sc_id', 'gender', 'fname', 'phone', 'email', 'address');
			$data_fields = array('ScId', 'Gender', 'VendorName', 'Phone', 'Email', 'Address');
			$count = 0; $vdata = array(); $test = TRUE;
			foreach($fields as $field) {
				if(!(bool)$this->input->post($field)) {
					$test = FALSE;
				}
				$vdata[$data_fields[$count]] = $this->input->post($field);
				$count += 1;
			}
			$vdata['AltPhone'] = $this->input->post('alt_ph');
			$vdata['Salt'] = generate_hash(generateUniqueString(8));
			$vdata['Pwd'] = generate_salted_hash($this->input->post('password'), $vdata['Salt']);
			$vdata['PwdCheck'] = 0; $vdata['isVerified'] = 1;
			if($this->prvendor_m->is_unique_ph($vdata['Phone']) && $test) {
				$this->prvendor_m->save($vdata);
				redirect(site_url('admin/vendors/addPrivilegedVendor'));
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
				$this->load->view('admin/addPrivilegedVendor', $this->data);
			}
		}
	}
	public function holidayadd() {
		$this->data['active'] = 'vholidayadd';
		$this->load->model('servicecenter_m');
		$this->data['scs'] = $this->servicecenter_m->get();
		$this->load->view('admin/vholidayadd', $this->data);
	}
	public function holidaymgmt() {
		$this->data['active'] = 'vholidaymgmt';
		$this->load->model('servicecenter_m');
		$this->data['scs'] = $this->servicecenter_m->get();
		$this->data['holidays'] = $this->get_sc_holidays();
		$categories = array();
		foreach($this->data['holidays'] as $holiday) {
		    $categories[$holiday["Holiday"]][] = $holiday;
		}
		$this->data['holidays'] = $categories;
		$this->load->view('admin/vholidaymgmt', $this->data);
	}
	public function slotmgmt() {
		$this->data['active'] = 'vslotmgmt';
		$this->load->model('servicecenter_m');
		$this->data['scs'] = $this->servicecenter_m->get();
		$this->load->view('admin/vslotm', $this->data);
	}
	public function bulk_update_holidays() {
		if($_POST) {
			$schData = array(array()); $count = 0;
			$holidays = $this->input->post('holidays');
			$service_centers = $this->input->post('service_centers');
			$holidays = explode(",", $holidays); $service_centers = explode(",", $service_centers);
			$this->load->model('servicecenterholiday_m');
			foreach ($service_centers as &$service_center) {
				$service_center = intval(trim($service_center));
				foreach ($holidays as &$holiday) {
					$holiday = trim($holiday);
					$schData[$count]["ScId"] = $service_center;
					$schData[$count]["Holiday"] = $holiday;
					$count++;
				}
			}
			$this->db->insert_batch('service_center_holidays', $schData);
		}
		redirect(site_url('admin/vendors/holidaymgmt'));
	}
	public function bulk_update_slots() {
		if($_POST) {
			$this->load->model('servicecenter_m');
			$sc_ids = array_map('intval', $this->input->post('sc_ids'));
			$slot_hours = array_map('floatval', $this->input->post('slot_hours'));
			$bul_num_slots = intval($this->input->post('slot_count'));
			$bul_dates = explode(", ", $this->input->post('bul_dates'));
			foreach($sc_ids as $scid) {
				$slot_checks = $this->servicecenter_m->get_inserted_bulk_slots($bul_dates, $scid);
				$sc = $this->servicecenter_m->get_by(array(
					'ScId' => intval($scid),
				), TRUE);
				$slot_duration = $sc->SlotDuration;
				$slot_type = intval($sc->SlotType);
				$update_days = array();
				foreach($slot_checks as &$slot_check) {
					if(in_array(floatval($slot_check['Hour']), $slot_hours)) {
						$slot_check['Slots'] = $bul_num_slots + intval($slot_check['BufferedSlots']);
						$update_days[] = $slot_check['Day'];
					}
					unset($slot_check['BufferedSlots']);
				}
				if(count($slot_checks) > 0) {
					$this->db->update_batch('slots', $slot_checks, 'SlotId');
				}
				$new_dates = array_values(array_diff($bul_dates, $update_days));
				if(count($new_dates) > 0) {
					$count = 0;
					for($i = 0; $i < count($new_dates); $i++) {
						$j = $sc->StartHour;
						if($slot_type == 1) {
							for(; $j <= 11; $j += 0.5) {
								$new_slots[$count]['ScId'] = intval($scid);
								$new_slots[$count]['Day'] = $new_dates[$i];
								$new_slots[$count]['Hour'] = $j;
								$new_slots[$count]['EHour'] = $j + 5.0;
								$new_slots[$count]['Slots'] = $bul_num_slots;
								$count += 1;
							}
							$j += 0.5;
						}
						for(; $j <= $sc->EndHour; $j += $slot_duration) {
							$new_slots[$count]['ScId'] = intval($scid);
							$new_slots[$count]['Day'] = $new_dates[$i];
							$new_slots[$count]['Hour'] = $j;
							$new_slots[$count]['EHour'] = 0;
							$new_slots[$count]['Slots'] = $bul_num_slots;
							$count += 1;
						}
					}
					$this->db->insert_batch('slots', $new_slots);
				}
			}
		}
		redirect(site_url('admin/vendors/slotmgmt'));
	}
	public function vlist() {
		$this->data['active'] = 'vendor_list';
		$this->data['rows'] = $this->get_vlist();
		$this->load->view('admin/vlist', $this->data);
	}
	public function prvlist() {
		$this->data['active'] = 'prvuser_list';
		$this->data['rows'] = $this->get_prvuser_list();
		$this->load->view('admin/prvulist', $this->data);
	}
	public function userlist() {
		$this->data['active'] = 'vuser_list';
		$this->data['rows'] = $this->get_vuser_list();
		$this->load->view('admin/vulist', $this->data);
	}
	public function writeto() {
		$this->data['active'] = 'feedback';
		$this->load->model('servicecenter_m');
		$this->data['scs'] = $this->servicecenter_m->get();
		$this->load->view('admin/vwriteto', $this->data);
	}
	public function scdetails($ScId = NULL) {
		if($ScId === NULL) {
			redirect(site_url('admin/vendors/vlist'));
		} else {
			$this->data['active'] = 'vendor_list';
			$this->load->model('servicecenter_m');
			$this->data['sc_details'] = $this->servicecenter_m->get_sc_details($ScId);
			$this->load->view('admin/scinfo', $this->data);
		}
	}
	public function loginasvendor($ScId = NULL) {
		if($ScId === NULL || !$this->admin_m->is_sc_in_city($ScId)) {
			redirect(site_url('admin/vendors/vlist'));
		} else {
			$this->load->model('servicecenter_m');
			$sc = $this->servicecenter_m->get(intval($ScId));
			if($sc) {
				$this->load->model('vendor_m');
				$vend_logo = $this->vendor_m->get_vendor_sc_logo($ScId);
				$session_data = array(
					'v_name' => 'Gear6 Admin',
					'v_phone' => '9494845111',
					'v_email' => 'kumud@gear6.in',
					'v_id' => -1,
					'v_sc_id' => intval($ScId),
					'v_role' => 'Admin',
					'v_sc_name' => convert_to_camel_case($sc->ScName),
					'v_loggedin' => TRUE,
					'v_sc_type' => 'sc',
					'v_sc_logo' => $vend_logo
				);
				$this->session->set_userdata($session_data);
				redirect(site_url('vendor'));
			} else {
				redirect(site_url('admin/vendors/vlist'));
			}
		}
	}
	private function get_vuser_list() {
		$this->db->select('vendor.VendorName, vendor.Phone, vendor.VendorId, vendor.UserPrivilege, servicecenter.ScName, location.LocationName');
		$this->db->from('vendor');
		$this->db->join('servicecenter', 'vendor.ScId = servicecenter.ScId', 'left');
		$this->db->join('scaddrsplit', 'scaddrsplit.ScId = servicecenter.ScId', 'left');
		$this->db->join('location', 'location.LocationId = scaddrsplit.LocationId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('scaddrsplit.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->group_by('vendor.VendorId');
		$this->db->order_by('servicecenter.ScId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	private function get_prvuser_list() {
		$this->db->select('prvendor.VendorName, prvendor.Phone, prvendor.VendorId, prvendor.UserPrivilege, prvendor.ScId');
		$this->db->from('prvendor');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach ($results as &$result) {
				$sc_ids = explode(", ", $result['ScId']); $sc_name = array(); $l_name = array();
				foreach ($sc_ids as $sc_id) {
					$this->db->select('ScName, LocationName');
					$this->db->from('servicecenter');
					$this->db->join('scaddrsplit', 'scaddrsplit.ScId = servicecenter.ScId', 'left');
					$this->db->join('location', 'location.LocationId = scaddrsplit.LocationId', 'left');
					$this->db->where('servicecenter.ScId', $sc_id);
					$temp = $this->db->get()->result_array()[0];
					$sc_name[] = $temp['ScName']; $l_name[] = $temp['LocationName'];
				}
				$result['ScName'] = implode(", ", $sc_name);
				$result['LocationName'] = implode(", ", $l_name);
			}
			return $results;
		}
	}
	private function get_vlist() {
		$this->db->select('COUNT(oservicedetail.OId) AS OCount, servicecenter.ScId, servicecenter.ScName, servicecenter.Rating, sccontact.Phone, location.LocationName');
		$this->db->from('servicecenter');
		$this->db->join('sccontact', 'sccontact.ScId = servicecenter.ScId', 'left');
		$this->db->join('scaddrsplit', 'scaddrsplit.ScId = servicecenter.ScId', 'left');
		$this->db->join('location', 'location.LocationId = scaddrsplit.LocationId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.ScId = servicecenter.ScId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('scaddrsplit.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('servicecenter.isVerified', 1);
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
	private function get_sc_holidays() {
		$this->db->select('*');
		$this->db->from('service_center_holidays');
		$this->db->join('servicecenter', 'servicecenter.ScId = service_center_holidays.ScId', 'left');
		$this->db->order_by('service_center_holidays.ScHId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		return $results;
	}
	public function deleteHoliday() {
		if($_POST) {
			$holidayDate = $this->input->post('holidayDate');
			$this->db->where('Holiday', $holidayDate);
			$this->db->delete('service_center_holidays');
		}
		redirect(site_url('admin/vendors/holidaymgmt'));
	}
	public function deleteServiceCenterHoliday() {
		if($_POST) {
			$this->load->model('admin_m');
			$holidayDate = $this->input->post('holidayDate');
			$serviceCenterId = $this->input->post('serviceCenterId');
			$this->db->where('Holiday', $holidayDate);
			$this->db->where('ScId', $serviceCenterId);
			$this->db->delete('service_center_holidays');
		}
		redirect(site_url('admin/vendors/holidaymgmt'));
	}
	public function dropped_analysis() {
		$adata['atype']['name'] = 'Dropped Orders - ' . $this->input->post('atype') . ' Analysis';
		if($this->input->post('atype') == 'Weekly') {
			$date = date("Y-m-d", strtotime("-7 day", strtotime("now")));
			for($i = 0; $i <= 6; $i++) {
				$adata['categories'][$i] = date("d/m", strtotime($date));
				$adata['total_orders'][$i] = intval($this->getDelayed($date));
				$adata['total_dropped'][$i] = intval($this->getDropped($date));
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
			}
		} elseif($this->input->post('atype') == 'Monthly') {
			$date = date("Y-m-d", strtotime("-28 day", strtotime("now")));
			for($i = 0; $i <= 3; $i++) {
				$from_date = $date;
				$adata['categories'][$i] = date("d/m", strtotime($date)) . ' - ';
				$date = date("Y-m-d", strtotime("+7 day", strtotime($date)));
				$to_date = $date;
				$adata['categories'][$i] .= date("d/m", strtotime($date));
				$adata['total_orders'][$i] = intval($this->getDelayedRange($from_date, $to_date));
				$adata['total_dropped'][$i] = intval($this->getDroppedRange($from_date, $to_date, TRUE));
			}
		}
		echo json_encode($adata);
	}
	public function delay_analysis() {
		$adata['atype']['name'] = 'Delay - ' . $this->input->post('atype') . ' Analysis';
		if($this->input->post('atype') == 'Weekly') {
			$date = date("Y-m-d", strtotime("-7 day", strtotime("now")));
			for($i = 0; $i <= 6; $i++) {
				$adata['categories'][$i] = date("d/m", strtotime($date));
				$adata['total_orders'][$i] = intval($this->getDelayed($date));
				$adata['total_delays'][$i] = intval($this->getDelayed($date, TRUE));
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
			}
		} elseif($this->input->post('atype') == 'Monthly') {
			$date = date("Y-m-d", strtotime("-28 day", strtotime("now")));
			for($i = 0; $i <= 3; $i++) {
				$from_date = $date;
				$adata['categories'][$i] = date("d/m", strtotime($date)) . ' - ';
				$date = date("Y-m-d", strtotime("+7 day", strtotime($date)));
				$to_date = $date;
				$adata['categories'][$i] .= date("d/m", strtotime($date));
				$adata['total_orders'][$i] = intval($this->getDelayedRange($from_date, $to_date));
				$adata['total_delays'][$i] = intval($this->getDelayedRange($from_date, $to_date, TRUE));
			}
		}
		echo json_encode($adata);
	}
	public function top_ten_vendors_analysis() {
		$adata['atype']['name'] = 'Top 10 Vendors - ' . $this->input->post('atype') . ' Analysis';
		if($this->input->post('atype') == 'Weekly') {
			$date = date("Y-m-d", strtotime("-7 day", strtotime("now")));
			for($i = 0; $i <= 6; $i++) {
				$adata['categories'][$i] = date("d/m", strtotime($date));
				$orders = $this->getVendorOrders($date, 'desc');
				foreach ($orders as &$order) {
					$order['actualDate'] = $order['date'];
					$order['date'] = date("d/m", strtotime($order['date']));
					$adata['sc_names'][$order['name']] = $order['name'];
				}
				$adata['service_center'][$i] = $orders;
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
			}
		} elseif($this->input->post('atype') == 'Monthly') {
			$date = date("Y-m-d", strtotime("-28 day", strtotime("now")));
			for($i = 0; $i <= 3; $i++) {
				$from_date = $date;
				$adata['categories'][$i] = date("d/m", strtotime($date)) . ' - ';
				$date = date("Y-m-d", strtotime("+7 day", strtotime($date)));
				$to_date = $date;
				$adata['categories'][$i] .= date("d/m", strtotime($date));
				$orders = $this->getVendorOrdersRange($from_date, $to_date, 'desc');
				foreach ($orders as &$order) {
					$order['actualDate'] = $order['date'];
					$order['date'] = date("d/m", strtotime($order['date']));
					$adata['sc_names'][$order['name']] = $order['name'];
				}
				$adata['service_center'][$i] = $orders;
			}
		}
		$adata['sc_names'] = array_values($adata['sc_names']);
		echo json_encode($adata);
	}
	public function last_ten_vendors_analysis() {
		$adata['atype']['name'] = 'Bottom 10 Vendors - ' . $this->input->post('atype') . ' Analysis';
		if($this->input->post('atype') == 'Weekly') {
			$date = date("Y-m-d", strtotime("-7 day", strtotime("now")));
			for($i = 0; $i <= 6; $i++) {
				$adata['categories'][$i] = date("d/m", strtotime($date));
				$orders = $this->getVendorOrders($date, 'asc');
				foreach ($orders as &$order) {
					$order['actualDate'] = $order['date'];
					$order['date'] = date("d/m", strtotime($order['date']));
					$adata['sc_names'][$order['name']] = $order['name'];
				}
				$adata['service_center'][$i] = $orders;
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
			}
		} elseif($this->input->post('atype') == 'Monthly') {
			$date = date("Y-m-d", strtotime("-28 day", strtotime("now")));
			for($i = 0; $i <= 3; $i++) {
				$from_date = $date;
				$adata['categories'][$i] = date("d/m", strtotime($date)) . ' - ';
				$date = date("Y-m-d", strtotime("+7 day", strtotime($date)));
				$to_date = $date;
				$adata['categories'][$i] .= date("d/m", strtotime($date));
				$orders = $this->getVendorOrdersRange($from_date, $to_date, 'desc');
				foreach ($orders as &$order) {
					$order['actualDate'] = $order['date'];
					$order['date'] = date("d/m", strtotime($order['date']));
					$adata['sc_names'][$order['name']] = $order['name'];
				}
				$adata['service_center'][$i] = $orders;
			}
		}
		$adata['sc_names'] = array_values($adata['sc_names']);
		echo json_encode($adata);
	}
	private function getDelayed($date, $flag = FALSE) {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		if($flag) {
			$this->db->where('oservicedetail.DelayFlag', 1);
		}
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('odetails.ODate', $date);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function getDelayedRange($from, $to, $flag = FALSE) {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		if($flag) {
			$this->db->where('oservicedetail.DelayFlag', 1);
		}
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('odetails.ODate >=', $from);
		$this->db->where('odetails.ODate <', $to);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function getDropped($date) {
		$this->db->select('COUNT(*)');
		$this->db->from('dropped_orders');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('dropped_orders.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('dropped_orders.Date', $date);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function getDroppedRange($from, $to) {
		$this->db->select('COUNT(*)');
		$this->db->from('dropped_orders');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('dropped_orders.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('dropped_orders.Date >=', $from);
		$this->db->where('dropped_orders.Date <', $to);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function getVendorOrders($date, $sort) {
		$this->db->select('COUNT(oservicedetail.OId) AS count, servicecenter.ScName AS name, odetails.ODate AS date');
		$this->db->from('oservicedetail');
		$this->db->join('servicecenter', 'servicecenter.ScId = oservicedetail.ScId', 'left');
		$this->db->join('odetails', 'odetails.OId = oservicedetail.OId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ODate', $date);
		$this->db->where('servicecenter.isVerified', 1);
		$this->db->group_by('servicecenter.ScId');
		$this->db->order_by('count', $sort);
		$this->db->limit(10);
		$query = $this->db->get();
		$results = $query->result_array();
		return $results;
	}
	private function getVendorOrdersRange($from, $to, $sort) {
		$this->db->select('COUNT(oservicedetail.OId) AS count, servicecenter.ScName AS name, odetails.ODate AS date');
		$this->db->from('oservicedetail');
		$this->db->join('servicecenter', 'servicecenter.ScId = oservicedetail.ScId', 'left');
		$this->db->join('odetails', 'odetails.OId = oservicedetail.OId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ODate >=', $from);
		$this->db->where('odetails.ODate <', $to);
		$this->db->where('servicecenter.isVerified', 1);
		$this->db->group_by('servicecenter.ScId');
		$this->db->order_by('count', $sort);
		$this->db->limit(10);
		$query = $this->db->get();
		$results = $query->result_array();
		return $results;
	}
	public function addPrivilegedVendor() {
		$this->data['active'] = 'addPrivilegedVendor';
		$this->load->model('city_m');
		$this->data['cities'] = $this->city_m->get_by(array('isEnabled' => 1));
		$this->data['service_centers'] = $this->get_scs();
		$this->load->view('admin/addPrivilegedVendor', $this->data);
	}
	private function get_scs() {
		$this->db->select('servicecenter.ScId, servicecenter.ScName, city.CityName');
		$this->db->from('servicecenter');
		$this->db->join('scaddrsplit', 'scaddrsplit.ScId = servicecenter.ScId', 'left');
		$this->db->join('city', 'city.CityId = scaddrsplit.CityId', 'left');
		$this->db->order_by('city.CityName', 'asc');
		$this->db->order_by('servicecenter.ScName', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return array();
		} else {
			return $results;
		}
	}
}