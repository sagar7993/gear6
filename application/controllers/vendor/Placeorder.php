<?php
class Placeorder extends G6_Vendorcontroller {
	public function __construct() {
		parent::__construct();
	}
	public function index () {
		$this->load->model('bikecompany_m');
		$this->load->model('service_m');
		$this->load->model('insurer_m');
		$this->data['city_row'] = $this->vendor_m->get_city_row_by_vendor();
		$this->data['insurers'] = $this->insurer_m->get();
		$this->data['bikecompanies'] = $this->bikecompany_m->get_bcompany_by_id();
		$this->data['bikemodels'] = $this->get_bikemodels($this->data['bikecompanies'][0]['BikeCompanyId']);
		$this->data['services'] = $this->service_m->get_services_for_vendor_order();
		$sclatlong = $this->getSCLocation();
		$this->data['SCLatitude'] = $sclatlong['Latitude'];
		$this->data['SCLongitude'] = $sclatlong['Longitude'];
		$this->load->view('vendor/neworder', $this->data);
	}
	public function get_bikemodels($id, $ajax = 0) {
		$this->load->model('bikemodel_m');
		if($ajax == 0) {
			return $this->bikemodel_m->get_bmodels_by_sc($id);
		} else {
			echo json_encode($this->bikemodel_m->get_bmodels_by_sc($id));
		}
	}
	public function get_slots() {
		if($_POST) {
			$this->load->model('servicecenter_m');
			$data['odate'] = $date = date('Y-m-d', strtotime($this->input->post('date')));
			$sc_id = intval($this->session->userdata('v_sc_id'));
			$servicetype = intval($this->input->post('service'));
			$sc_row = $this->servicecenter_m->get_by(array('ScId' => $sc_id), TRUE);
			if ($servicetype == 4 || $servicetype == 2) {
				$slots = $this->servicecenter_m->get_slots($sc_row->SlotDuration, $sc_row->StartHour, $sc_row->EndHour);
			} else {
				$slots = $this->servicecenter_m->set_get_slots($sc_id, $date, $sc_row->DefaultSlots, $sc_row->SlotDuration, intval($sc_row->SlotType), $sc_row->StartHour, $sc_row->EndHour);
			}
			$data['html'] = $this->fetch_slots($slots, $servicetype, intval($sc_row->SlotType));
			echo json_encode($data);
		}
	}
	public function fetch_amenities() {
		if($_POST) {
			$sc_id = $this->session->userdata('v_sc_id');
			$servicetype = $this->input->post('service');
			$this->load->model('amenity_m');
			$amenities = $this->amenity_m->get_amenities_by_service($sc_id, $servicetype);
			$output = '<div class="pickup-title teal-bold">Choose Amenities</div>';
			if(isset($amenities)) {
				foreach($amenities as $amenity) {
					$output .= '<div class="input-field col s4"><div class="checkbox" style=""><label class="pamenity"><input type="checkbox" name="oamenities[]" value="' . $amenity['AmId'] . '"><span style="margin-left:5px">' . convert_to_camel_case($amenity['AmName'] . ' - ' . $amenity['AmDesc']) . '</span></label></div></div>';
				}
				echo $output;
			} else {
				echo NULL;
			}
		}
	}
	public function fetch_asers() {
		if($_POST) {
			$sc_id = $this->session->userdata('v_sc_id');
			$bmid = $this->input->post('bmid');
			$this->load->model('aservice_m');
			$asers = $this->aservice_m->get_aservices_for_order($bmid, $sc_id, 0);
			$masers = $this->aservice_m->get_aservices_for_order($bmid, $sc_id, 1);
			$output = '';
			if(isset($asers) && count($asers) > 0) {
				$output .= '<div class="pickup-title teal-bold">Additional Services</div>';
			}
			if(isset($asers)) {
				foreach($asers as $aser) {
					$output .= '<div class="input-field col s4"><div class="checkbox" style=""><label class="asers"><input type="checkbox" name="asers[]" value="' . $aser['AServiceId'] . '"><span style="margin-left:5px">' . convert_to_camel_case($aser['AServiceName']) . '</span></label></div></div>';
				}
			}
			if(isset($masers)) {
				foreach($masers as $aser) {
					$output .= '<input type="hidden" name="asers[]" value="' . $aser['AServiceId'] . '" />';
				}
			}
			echo $output;
		}
	}
	public function fetch_user_details() {
		if($_POST) {
			$this->load->model('user_m');
			$user = $this->user_m->get_by(array('Phone' => $this->input->post('phone')), TRUE);
			if($user) {
				$output = $this->user_m->get_user_addresses($user->UserId)[0];
				$bikenumber = $this->user_m->get_user_bike_num($user->UserId);
				$output['BikeNumber'] = $bikenumber['BikeNumber'];
				$output['RegNum'] = $bikenumber['RegNum'];
			} else {
				$output = NULL;
			}
			echo json_encode($output);
		}
	}
	public function finalize_order() {
		if($_POST) {
			$this->load->model('odetails_m');
			$this->load->model('amenity_m');
			$this->load->model('service_m');
			$this->load->model('user_m');
			$this->insert_additional_locations();
			$servicetype = intval($this->input->post('user_service'));
			$servicename = $this->service_m->get($servicetype)->ServiceName;
			$user_row = $this->user_m->get_by(array('Phone' => $this->input->post('phone')), TRUE);
			if (!$user_row) {
				$usr = $this->user_m->create_user();
				$usr_id = $usr['UserId'];
				$user_addr_id = $this->user_m->updt_useraddr($usr_id);
				$this->send_sms_request_to_api($usr['Phone'], 'You are successfully registered at gear6.in. Below are your login details. Login Id: ' . $usr['Phone'] . ' Password: ' . $usr['Pwd']);
			} else {
				$usr_id = $user_row->UserId;
				$usr['Phone'] = $this->input->post('phone');
				if (intval($this->input->post('user_addr_id')) != 0) {
					$user_addr_id = intval($this->input->post('user_addr_id'));
				} else {
					$user_addr_id = $this->user_m->updt_useraddr($usr_id);
				}
			}
			$amtys = '';
			$OId = $this->odetails_m->vendor_create_order($user_addr_id, $usr_id);
			$amtys = $this->odetails_m->vendor_insert_amenities($OId);
			if($servicetype == 1) {
				$this->odetails_m->vendor_insert_asers($OId);
			}
			$price = $this->amenity_m->vendor_insert_price_split($OId, $servicename);
			if ($servicetype == 4) {
				$this->odetails_m->insert_insurance($OId);
			}
			$this->odetails_m->vendor_insert_oservicedetail($OId, $amtys, $price);
			$v_city_id = intval($this->vendor_m->get_city_row_by_vendor()->CityId);
			if($v_city_id == 1) {
				$this->send_sms_request_to_api('9494845111', 'New order placed by User: ' . $this->input->post('full_name') . ', Phone: ' . $this->input->post('phone') . '. Track ->> https://www.gear6.in/admin/orders/odetail/' . $OId);
			} elseif($v_city_id == 3) {
				$this->send_sms_request_to_api('9000117719', 'New order placed by User: ' . $this->input->post('full_name') . ', Phone: ' . $this->input->post('phone') . '. Track ->> https://www.gear6.in/admin/orders/odetail/' . $OId);
			}
			$this->send_sms_request_to_api($this->input->post('phone'), 'Your service request is successfully placed as Order ID : ' . $OId . ' with ' . convert_to_camel_case($this->session->userdata('v_sc_name')) . ' for '. convert_to_camel_case($servicename) . ' on ' . $this->input->post('user_date') . '. Login to gear6.in to track your service progress.');
			redirect(site_url('vendor/odetail/show/' . $OId));
		}
	}
	private function insert_additional_locations() {
		$sc_id = intval($this->session->userdata('v_sc_id'));
		$new_city = $this->db->select('CityId')->from('scaddrsplit')->where('ScId', intval($sc_id))->get()->row_array();
		$city_id = intval($new_city['CityId']);
		$lc_row = $this->db->select('LocationId')->from('location')->where('LocationName', $this->input->post('location'))->where('CityId', $city_id)->get()->row_array();
		if(!$lc_row) {
			$new_lc['LocationName'] = $this->input->post('location');
			$new_lc['Latitude'] = floatval($this->input->post('nulati'));
			$new_lc['Longitude'] = floatval($this->input->post('nulongi'));
			$new_lc['CityId'] = $city_id;
			$this->db->insert('location', $new_lc);
			return TRUE;
		} else {
			return FALSE;
		}
	}
	private function getSCLocation() {
		$this->db->select('scaddr.Latitude, scaddr.Longitude');
		$this->db->from('scaddr');
		$this->db->join('scaddrsplit', 'scaddrsplit.ScAddrSplitId = scaddr.ScAddrSplitId', 'left');
		$this->db->where('scaddrsplit.ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->limit(1);
		$query = $this->db->get();
		$results = $query->row_array();
		return $results;
	}
	private function fetch_slots($slots, $servicetype, $slot_type) {
		$html = '';
		foreach($slots as $slot) {
			if ($slot['Hour'] > 12) {
				$temp_hr = intval($slot['Hour'] - 12);
				$temp = (intval($slot['Hour'] * 60) % 60);
				if($temp == 0) {
					$temp = '00';
				}
				$slot_hour = $temp_hr . ":" . $temp . "&nbsp;PM";
			} elseif ($slot['Hour'] == 12) {
				$slot_hour = intval($slot['Hour']) . ":00&nbsp;PM";
			} else {
				$temp = (intval($slot['Hour'] * 60) % 60);
				if($temp == 0) {
					$temp = '00';
				}
				$slot_hour = intval($slot['Hour']) . ":" . $temp . "&nbsp;AM";
			}
			if ($servicetype == 4 || $servicetype == 2) {
				$html .= '<li class="col s6 sx l4 margin-bottom-10px margin-top-10px padding-right-0"><div class="slot-box"><label class="slot-label"><input type="radio" name="user_slot" class="slot_radio_event" value="' . $slot['Hour'] . '">&nbsp;&nbsp;' . $slot_hour . '&nbsp;</label></div></li>';
			} else {
				if ($slot['Slots'] <= 0) {
					$html .= '<li class="col s6 sx l4 margin-bottom-10px margin-top-10px padding-right-0"><div class="slot-box"><label class="slot-label"><input type="radio" name="user_slot" class="slot_radio_event" value="' . $slot['Hour'] . '" disabled="disabled"><span class="margin-left-5px">' . $slot_hour . ' - <b>Not Available</b></label></div></li>';
				} else {
					$html .= '<li class="col s6 sx l4 margin-bottom-10px margin-top-10px padding-right-0"><div class="slot-box"><label class="slot-label"><input type="radio" name="user_slot" class="slot_radio_event" value="' . $slot['Hour'] . '"><span class="margin-left-5px">' . $slot_hour . ' : <b>' . $slot['Slots'] . '</b> Slots</span></label></div></li>';
				}
			}
		}
		if($slot_type == 1) {
			$html .= '<li class="col s12 margin-bottom-10px margin-top-10px padding-right-0"><span style="font-size: 12px;"><span style="color:red;">Note:</span>Bikes picked in slots before 11 AM will be delivered in 5 hours and the rest in the next day.</span></li>';
		} elseif($slot_type == 2) {
			$html .= '<li class="col s12 margin-bottom-10px margin-top-10px padding-right-0"><span style="font-size: 12px;"><span style="color:red;">Note:</span>Bikes picked in slots before 11 AM will be delivered by the End of the day and the rest in the next day.</span></li>';
		} elseif($slot_type == 3) {
			$html .= '<li class="col s12 margin-bottom-10px margin-top-10px padding-right-0"><span style="font-size: 12px;"><span style="color:red;">Note:</span>Bikes picked in these slots will be delivered by the End of next day.</span></li>';
		}
		$html .= '';
		return $html;
	}
}