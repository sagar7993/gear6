<?php
class Orders extends G6_Admincontroller {
	public function __construct() {
		parent::__construct();
		$this->data['page'] = 'order';
		if($this->data['a_is_logged_in'] == 1) {
			$this->get_order_nav_counts();
		}
	}
	public function index () {
		$this->data['active'] = 'dashboard';
		$this->data['tot_orders'] = intval($this->get_tot_orders());
		$this->data['tot_processed'] = intval($this->get_tot_processed());
		$this->data['tot_queried'] = intval($this->get_tot_queried());
		$this->data['tot_delayed'] = intval($this->get_tot_delayed());
		$this->data['tot_orders_without_query'] = $this->data['tot_orders'] - $this->data['tot_queried'];
		$this->load->view('admin/odashboard', $this->data);
	}
	public function od_assign_execs() {
		if($_POST) {
			$oid = $this->input->post('oid');
			$execids = $this->input->post('execs');
			$this->db->where('OId', $oid);
			$this->db->delete('execassigns');
			if(isset($oid) && isset($execids) && count($execids) > 0) {
				$count = 0;
				foreach($execids as $execid) {
					$idata[$count]['OId'] = $oid;
					$idata[$count]['ExecId'] = intval($execid);
					$count ++;
				}
				$this->db->insert_batch('execassigns', $idata);
				$sms_user_flag = intval($this->input->post('smsuser'));
				if($sms_user_flag == 1) {
					if(intval($this->session->userdata('a_city_id')) > 0) {
						$execdata = $this->db->select('ExecName, Phone')->from('executive')->where('executive.CityId', intval($this->session->userdata('a_city_id')))->where_in('ExecId', $execids)->get()->result();
					} else {
						$execdata = $this->db->select('ExecName, Phone')->from('executive')->where_in('ExecId', $execids)->get()->result();
					}
					if(count($execdata) > 1) {
						$exstr = 'executives';
					} elseif(count($execdata) == 1) {
						$exstr = 'executive';
					}
					foreach($execdata as $ex) {
						$exstrmsg[] = $ex->ExecName . ' (Ph: ' . $ex->Phone . ')';
						$this->send_sms_request_to_api($ex->Phone, 'A new gear6.in order ' . $oid . ' was assigned to you. Open your exective app (http://executive.gear6.in/) for more order details.');
					}
					$parsedexecdata = implode(', ', $exstrmsg);
					$this->load->model('odetails_m');
					$timeslot = $this->odetails_m->get_timeslot_by_oid($oid);
					$od_row = $this->odetails_m->get_by(array('OId' => $oid), TRUE);
					$ph = $this->odetails_m->get_user_ph_by_oid($oid);
					if($od_row->ServiceId == 4) {
						$smsg = 'Our ' . $exstr . ' ' . $parsedexecdata . ' will be coming to collect your documents on ' . $timeslot;
					} else {
						$smsg = 'Our ' . $exstr . ' ' . $parsedexecdata . ' will be coming to collect your bike on ' . $timeslot;
					}
					$this->send_sms_request_to_api($ph, $smsg);
				}
			}
		}
		redirect(site_url('admin/orders/odetail/' . $oid));
	}
	public function eodetail($OId = NULL) {
		if(isset($OId) && $this->admin_m->is_valid_oid($OId)) {
			$this->load->model('odetails_m');
			$this->load->model('executive_m');
			$this->load->model('opaymtdetail_m');
			$this->load->model('amenity_m');
			$this->load->model('aservice_m');
			$this->load->model('servicecenter_m');
			$this->load->model('statushistory_m');
			$this->data['OId'] = $OId;
			$sc_details = $this->odetails_m->get_scenter_by_oid($OId);
			$this->data['scenter'] = $sc_details;
			$this->data['scaddress'] = $this->odetails_m->get_sc_address($sc_details[0]['ScId']);
			$service_details = $this->odetails_m->get_stype_by_oid($OId);
			$this->data['stype'] = $service_details['ServiceName'];
			$bike_model_details = $this->odetails_m->get_bm_by_oid($OId);
			$this->data['bikenumber'] = $bike_model_details['BikeNumber'];
			$this->data['bikemodel'] = $bike_model_details['BikeCompanyName'] . ' ' . $bike_model_details['BikeModelName'];
			$this->data['timeslot'] = $this->odetails_m->get_timeslot_by_oid($OId);
			$this->data['tot_paid'] = floatval($this->opaymtdetail_m->get_total_paid_amount($OId));
			$user_details = $this->odetails_m->get_user_address($OId);
			$this->data['csaddress'] = $user_details['address'];
			$this->data['uphone'] = $user_details['Phone'];
			$this->data['chosen_amenities'] = $this->amenity_m->get_chosen_amenities($OId);
			$this->data['fscdetails'] = $this->servicecenter_m->get_sc_details(intval($sc_details[0]['ScId']));
			$odetail_row = $this->odetails_m->get_by(array('OId' => $OId), TRUE);
			$this->data['u_loc_pin'] = $odetail_row->ULatitude . ',' . $odetail_row->ULongitude;
			$this->data['estprices'] = $this->amenity_m->get_est_prices_by_oid($OId);
			$this->data['discprices'] = $this->amenity_m->get_est_prices_by_oid($OId, TRUE);
			$this->data['oprices'] = $this->statushistory_m->get_oprices($OId);
			$this->data['tot_paid'] = floatval($this->opaymtdetail_m->get_total_paid_amount($OId));
			$this->data['tot_billed'] = floatval($this->data['estprices'][count($this->data['estprices']) - 1]['ptotal']) + floatval($this->data['oprices'][count($this->data['oprices']) - 1]['ptotal']) - floatval($this->data['discprices'][count($this->data['discprices']) - 1]['ptotal']);
			$this->data['to_be_paid'] = round(floatval($this->data['tot_billed'] - $this->data['tot_paid']), 2);
			$this->data['jcimages'] = $this->executive_m->get_execjc_media($OId);
			$this->data['billimages'] = $this->executive_m->get_bill_media($OId);
			$this->data['execlcats'] = $this->executive_m->get_execcl_cats();
			$this->data['execlscats'] = $this->executive_m->get_execcl_subcats();
			$this->data['jccats'] = $this->executive_m->get_app_jcard_cats();
			$this->data['jcselects'] = $this->executive_m->get_app_jcard_selects($OId);
			$this->data['is_est_updated'] = $this->is_aexfupstatus_updated($OId, 20, '');
			$this->get_order_estimates($OId);
			$this->get_jc_form_data($OId);
			if($this->data['to_be_paid'] < 0.01 && $this->data['to_be_paid'] > -0.01) {
				$this->data['to_be_paid'] = 0;
			}
			$this->load->view('admin/jobcard', $this->data);
		} else {
			redirect(site_url('admin/orders'));
		}
	}
	private function get_order_estimates($OId) {
		$this->db->select('*');
		$this->db->from('jobcarddetails');
		$this->db->where('OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		if(!$result) {
			return NULL;
		} else {
			$this->data['jc_bike_estdate'] = $result['EstDate'];
			$this->data['jc_bike_esttime'] = $result['EstTime'];
			$this->data['jc_bike_estprice'] = 'INR ' . $result['EstPrice'];
			$remarks = $this->db->select('Remarks')->from('ofupstatus')->where('ofupstatus.OId', $OId)->where('ofupstatus.FupStatusId', 20)->order_by('Timestamp', 'desc')->limit(1)->get()->row();
			if($remarks) {
				$this->data['jc_bike_estremarks'] = $remarks->Remarks;
			} else {
				$this->data['jc_bike_estremarks'] = NULL;
			}
			$this->data['CPName'] = $result['CPName'];
			$this->data['CPPhone'] = $result['CPPhone'];
		}
	}
	private function is_aexfupstatus_updated($OId, $statusid, $tablestr) {
		$this->db->select('COUNT(*) AS isExists');
		if($tablestr == 'ex') {
			$this->db->from('oexfupstatus');
			$this->db->where('oexfupstatus.EFupStatusId', $statusid);
		} else {
			$this->db->from('ofupstatus');
			$this->db->where('ofupstatus.FupStatusId', $statusid);
		}
		$this->db->where('OId', $OId);
		if($this->db->limit(1)->get()->row()->isExists) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function place_order() {
		$this->data['active'] = 'neworder';
		$this->load->model('tieups_m');
		$this->data['businesses'] = $this->tieups_m->get_businesses();
		if((bool) $this->session->userdata('v_sc_id')) {
			$this->load->model('bikecompany_m');
			$this->load->model('service_m');
			$this->load->model('vendor_m');
			$this->load->model('regnum_m');
			$this->load->model('insurer_m');
			$this->data['city_row'] = $this->vendor_m->get_city_row_by_vendor();
			$this->data['scname'] = $this->session->userdata('v_sc_name');
			$this->data['insurers'] = $this->insurer_m->get();
			$regnums = $this->regnum_m->get_all_regnumvals();
			if (count($regnums) > 0) {
				$this->data['regnums'] = '"' . implode('", "', $regnums) . '"';
			}
			$this->data['bikecompanies'] = $this->bikecompany_m->get_bcompany_by_id();
			$this->data['bikemodels'] = $this->get_bikemodels($this->data['bikecompanies'][0]['BikeCompanyId']);
			$this->data['services'] = $this->service_m->get_services_for_vendor_order();
			$this->data['sc_chosen'] = 1;
		}
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$temp_scs = $this->db->select('servicecenter.ScName')->from('servicecenter')->join('scaddrsplit', 'scaddrsplit.ScId = servicecenter.ScId')->where('scaddrsplit.CityId', intval($this->session->userdata('a_city_id')))->get()->result();
		} else {
			$this->load->model('servicecenter_m');
			$temp_scs = $this->servicecenter_m->get();
		}
		foreach($temp_scs as $sc) {
			$scnames[] = $sc->ScName;
		}
		$this->data['scnames'] = '"' . implode('", "', $scnames) . '"';
		$this->load->view('admin/neworder', $this->data);
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
			$sms_flag = intval($this->input->post('sendsmsflag'));
			$user_row = $this->user_m->get_by(array('Phone' => $this->input->post('phone')), TRUE);
			if (!$user_row) {
				$usr = $this->user_m->create_user();
				$usr_id = $usr['UserId'];
				$user_addr_id = $this->user_m->updt_useraddr($usr_id);
				if($sms_flag == 1) {
					$this->send_sms_request_to_api($usr['Phone'], 'You are successfully registered at gear6.in. Below are your login details. Login Id: ' . $usr['Phone'] . ' Password: ' . $usr['Pwd']);
				}
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
			$isGrievance['isGrievance'] = intval($this->input->post('isGrievance')); $this->db->where('OId', $OId); $this->db->update('odetails', $isGrievance);
			$biz_type = intval($this->input->post('business'));
			if($sms_flag == 1 && $biz_type != 2) {
				$this->send_sms_request_to_api($this->input->post('phone'), 'Your service request is successfully placed as Order ID : ' . $OId . ' with ' . convert_to_camel_case($this->session->userdata('v_sc_name')) . ' for '. convert_to_camel_case($servicename) . ' on ' . $this->input->post('user_date') . '. Login to gear6.in to track your service progress.');
				$this->load->model('servicecenter_m');
				$sc_phone = $this->servicecenter_m->get_sc_phone_by_oid($OId);
				if(isset($sc_phone)) {
					$bike_model_details = $this->odetails_m->get_bm_by_oid($OId);
					$this->data['bikenumber'] = $bike_model_details['BikeNumber'];
					$this->data['bikemodel'] = $bike_model_details['BikeCompanyName'] . ' ' . $bike_model_details['BikeModelName'];
					$this->data['timeslot'] = $this->odetails_m->get_timeslot_by_oid($OId);
					$this->send_sms_request_to_api($sc_phone[0]['Phone'], 'New ' . convert_to_camel_case($servicename) . ' request received as order ' . $OId . ' for ' . convert_to_camel_case($this->data['bikemodel']) . ' Reg No.: ' . $this->data['bikenumber'] . ' on ' . $this->data['timeslot'] . '. Login to your panel for more details.');
				}
			}
			redirect(site_url('admin/orders/odetail/' . $OId));
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
					$output .= '<div class="input-field col-xs-4"><div class="checkbox" style=""><label class="pamenity"><input type="checkbox" name="oamenities[]" value="' . $amenity['AmId'] . '"><span style="margin-left:5px">' . convert_to_camel_case($amenity['AmName'] . ' - ' . $amenity['AmDesc']) . '</span></label></div></div>';
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
				$output .= '<div class="pickup-title teal-bold">Recommended Services</div>';
			}
			if(isset($asers)) {
				foreach($asers as $aser) {
					$output .= '<div class="input-field col-xs-4"><div class="checkbox" style=""><label class="asers"><input type="checkbox" name="asers[]" value="' . $aser['AServiceId'] . '"><span style="margin-left:5px">' . convert_to_camel_case($aser['AServiceName']) . '</span></label></div></div>';
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
	public function get_bikemodels($id, $ajax = 0) {
		$this->load->model('bikemodel_m');
		if($ajax == 0) {
			return $this->bikemodel_m->get_bmodels_by_sc($id);
		} else {
			echo json_encode($this->bikemodel_m->get_bmodels_by_sc($id));
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
	public function fetch_sc_details() {
		if($_POST) {
			$sc_row = $this->db->select('ScId, ScName')->from('servicecenter')->where('ScName', $this->input->post('sc_name'))->limit(1)->get()->row_array();
			if($sc_row) {
				$session_data = array(
					'v_sc_id' => intval($sc_row['ScId']),
					'v_sc_name' => $sc_row['ScName']
				);
				$this->session->unset_userdata('v_loggedin');
				$this->session->set_userdata($session_data);
			}
			redirect(site_url('admin/orders/place_order'));
		}
	}
	public function emgorders() {
		$adminNotifyFlag['new_emergency_order_dismissed'] = 1;
		$this->db->where('new_emergency_order', 1);
		$this->db->update('admin_notification_flags', $adminNotifyFlag);
		$this->data['active'] = 'emgorders';
		if($_POST) {
			$this->data['startDate'] = $_POST['startDate']; $this->data['endDate'] = $_POST['endDate'];
			$this->data['rows'] = $this->get_emgorders($_POST['startDate'], $_POST['endDate']);
		} else {
			$this->data['rows'] = $this->get_emgorders(NULL, NULL);
		}
		$this->load->view('admin/emgorders', $this->data);
	}
	private function get_emgorders($startDate = NULL, $endDate = NULL) {
		if($startDate == NULL || $endDate == NULL) {
			$endDate = date("Y-m-d", strtotime("now + 200 days"));
			$startDate = date("Y-m-d", strtotime("now - 30 days"));
		}
		$this->db->select('EmgOrderId, LocationName, BikeModelName, BikeCompanyName, ODate, Phone, Email, Description, Timestamp, isCleared');
		$this->db->from('emgorders');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = emgorders.BikeModelId');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = emgorders.BikeCompanyId');
		$this->db->where('ODate >=', $startDate); $this->db->where('ODate <=', $endDate);
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('emgorders.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->order_by('ODate', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach($results as &$result) {
				$result['ODate'] = date('d-m-Y', strtotime($result['ODate']));
				$result['Timestamp'] = date('D M d Y, h:i A', strtotime($result['Timestamp']));
			}
			return $results;
		}
	}
	public function ptorders() {
		$adminNotifyFlag['new_puncture_order_dismissed'] = 1;
		$this->db->where('new_puncture_order', 1);
		$this->db->update('admin_notification_flags', $adminNotifyFlag);
		$this->data['active'] = 'ptorders';
		if($_POST) {
			$this->data['startDate'] = $_POST['startDate']; $this->data['endDate'] = $_POST['endDate'];
			$this->data['rows'] = $this->get_ptorders($_POST['startDate'], $_POST['endDate']);
		} else {
			$this->data['rows'] = $this->get_ptorders(NULL, NULL);
		}
		$this->load->view('admin/ptorders', $this->data);
	}
	private function get_ptorders($startDate = NULL, $endDate = NULL) {
		if($startDate == NULL || $endDate == NULL) {
			$endDate = date("Y-m-d", strtotime("now + 200 days"));
			$startDate = date("Y-m-d", strtotime("now - 30 days"));
		}
		$this->db->select('PtOrderId, LocationName, BikeModelName, BikeCompanyName, ODate, Phone, Email, Description, TyreType, PTyre, Timestamp, isCleared');
		$this->db->from('ptorders');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = ptorders.BikeModelId');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = ptorders.BikeCompanyId');
		$this->db->where('ODate >=', $startDate); $this->db->where('ODate <=', $endDate);
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('ptorders.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->order_by('ODate', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach($results as &$result) {
				$result['ODate'] = date('d-m-Y', strtotime($result['ODate']));
				$result['Timestamp'] = date('D M d Y, h:i A', strtotime($result['Timestamp']));
			}
			return $results;
		}
	}
	public function ptportal() {
		$this->data['active'] = 'ptportal';
		$this->load->view('admin/ptportal', $this->data);
	}
	public function pbportal() {
		$this->data['active'] = 'pbportal';
		$this->load->view('admin/pbportal', $this->data);
	}
	public function get_ptlist() {
		if($_POST) {
			$latitude = $_POST['latitude']; $longitude = $_POST['longitude'];
			$this->db->select('PTScId, ScName, Phone, Email, location.LocationName, location.Latitude, location.Longitude');
			$this->db->from('punctures');
			$this->db->join('location', 'location.LocationId = punctures.LocationId', 'left');
			$this->db->where('isVerified', 1); $this->db->limit(10); $query = $this->db->get();
			$results = $query->result_array();
			if (count($results) == 0) {
				 $this->data['rows'] = array(); $this->data['active'] = 'ptportal';	$this->load->view('admin/ptportal', $this->data);
			} else {
				$output = array();
				foreach($results as $result) {
					$distance = $this->distance($latitude, $longitude, $result["Latitude"], $result["Longitude"]);
					$result['distance'] = $distance; $output[] = array('row' => $result, 'distance' => $distance);
				}
				usort($output, array($this, "sortOutputByDistance")); $output = array_column($output, 'row'); $this->data['rows'] = $output;
				$this->data['active'] = 'ptportal';	$this->load->view('admin/ptportal', $this->data);
			}
		} else {
			redirect(site_url('admin/orders/ptportal'));
		}
	}
	public function get_pblist() {
		if($_POST) {
			$latitude = $_POST['latitude']; $longitude = $_POST['longitude'];
			$this->db->select('PBId, PBName, Phone, Email, location.LocationName, location.Latitude, location.Longitude');
			$this->db->from('petrolbunks');
			$this->db->join('location', 'location.LocationId = petrolbunks.LocationId', 'left');
			$this->db->where('isVerified', 1); $this->db->limit(10); $query = $this->db->get();
			$results = $query->result_array();
			if (count($results) == 0) {
				 $this->data['rows'] = array(); $this->data['active'] = 'pbportal';	$this->load->view('admin/pbportal', $this->data);
			} else {
				$output = array();
				foreach($results as $result) {
					$distance = $this->distance($latitude, $longitude, $result["Latitude"], $result["Longitude"]);
					$result['distance'] = $distance; $output[] = array('row' => $result, 'distance' => $distance);
				}
				usort($output, array($this, "sortOutputByDistance")); $output = array_column($output, 'row'); $this->data['rows'] = $output;
				$this->data['active'] = 'pbportal';	$this->load->view('admin/pbportal', $this->data);
			}
		} else {
			redirect(site_url('admin/orders/pbportal'));
		}
	}
	public function droppedorders() {
		$this->data['active'] = 'drpdorders';
		$this->data['rows'] = $this->get_abndedorders();
		$this->load->view('admin/dropped', $this->data);
	}
	public function droppedorderstatus() {
		if($_POST) {
			$drpdorderId = $_POST['dropped_order_id'];
			$drpdorders['Reason'] = $_POST['Reason'];
			$drpdorders['isVisible'] = 0;
			$this->db->where('dropped_order_id', $drpdorderId);
			$this->db->update('dropped_orders', $drpdorders);
		}
		redirect(site_url('admin/orders/droppedorders'));
	}
	private function dismiss_admin_order_notifications($oid) {
		$this->db->select('*');	$this->db->from('admin_notification_flags'); $this->db->where('OId', $oid);
		$query = $this->db->get(); $results = $query->result_array(); $adminNotifyFlag = array(); $count = 0;
		if (count($results) > 0) {
			if($results[0]['new_order'] == 1) {
				$adminNotifyFlag['new_order_dismissed'] = 1;
				$count++;
			}
			if($results[0]['new_order_reschedule'] == 1) {
				$adminNotifyFlag['new_order_reschedule_dismissed'] = 1;
				$count++;
			}
			if($results[0]['new_payment'] == 1) {
				$adminNotifyFlag['new_payment_dismissed'] = 1;
				$count++;
			}
			if($results[0]['new_delayed_order'] == 1) {
				$adminNotifyFlag['new_delayed_order_dismissed'] = 1;
				$count++;
			}
			if($results[0]['new_breakdown_order'] == 1) {
				$adminNotifyFlag['new_breakdown_order_dismissed'] = 1;
				$count++;
			}
			if($results[0]['new_pickup'] == 1) {
				$adminNotifyFlag['new_pickup_dismissed'] = 1;
				$count++;
			}
			if($results[0]['new_pickup_sc'] == 1) {
				$adminNotifyFlag['new_pickup_sc_dismissed'] = 1;
				$count++;
			}
			if($results[0]['new_bill_updated'] == 1) {
				$adminNotifyFlag['new_bill_updated_dismissed'] = 1;
				$count++;
			}
			if($results[0]['new_bike_delivered'] == 1) {
				$adminNotifyFlag['new_bike_delivered_dismissed'] = 1;
				$count++;
			}
			if($results[0]['bike_checkup'] == 1) {
				$adminNotifyFlag['bike_checkup_dismissed'] = 1;
				$count++;
			}
			if($count > 0) {
				$this->db->where('OId', $oid);
				$this->db->update('admin_notification_flags', $adminNotifyFlag);
			}
		}
	}
	public function odetail($oid = NULL) {
		if($oid === NULL || !$this->admin_m->is_valid_oid($oid)) {
			redirect(site_url('admin'));
		} else {
			$this->dismiss_admin_order_notifications($oid);
			$this->data['is_cancelled'] = $this->is_order_cancelled($oid);
			$this->populate_odetails($oid);
			$temp = $this->db->select('FinalFlag')->from('odetails')->where('OId', $oid)->get()->row_array();
			$this->data['send_final_message'] = intval($temp['FinalFlag']);
			$this->data['isBreakdown'] = $this->db->select('isBreakdown')->from('odetails')->where('OId', $oid)->get()->row_array()['isBreakdown'];
			$this->load->view('admin/odetail', $this->data);
		}
	}
	public function get_aprice_guesses() {
		if($_POST) {
			$output = array();
			$this->db->distinct();
			$this->db->select('PriceDetails');
			$this->db->from('opricesplit');
			$this->db->like('PriceDetails', $this->input->post('query'), 'after'); 
			$query = $this->db->get();
			$results = $query->result_array();
			if (count($results) > 0) {
				foreach($results as $result) {
					$output[] = $result['PriceDetails'];
				}
			}
			$this->db->distinct();
			$this->db->select('PriceDescription');
			$this->db->from('OPrice');
			$this->db->like('PriceDescription', $this->input->post('query'), 'after'); 
			$query = $this->db->get();
			$results = $query->result_array();
			if (count($results) > 0) {
				foreach($results as $result) {
					$output[] = $result['PriceDescription'];
				}
			}
			echo json_encode($output);
		}
	}
	public function ulocation_pin_update() {
		if($_POST) {
			$this->db->where('OId', $this->input->post('oid'));
			$this->db->update('odetails', array('ULatitude' => $this->input->post('u_lati'), 'ULongitude' => $this->input->post('u_longi')));
		}
		redirect('/admin/orders/odetail/' . $this->input->post('oid'));
	}
	public function delete_discount($OId = NULL, $apid = NULL) {
		if(isset($OId) && isset($apid)) {
			$this->db->where('OId', $OId);
			$this->db->where('PriceSplitId', intval($apid));
			$this->db->delete('opricesplit');
		}
		redirect('/admin/orders/odetail/' . $OId);
	}
	public function delete_estimate($OId = NULL, $apid = NULL) {
		if(isset($OId) && isset($apid)) {
			$this->db->where('OId', $OId);
			$this->db->where('PriceSplitId', intval($apid));
			$this->db->delete('opricesplit');
		}
		redirect('/admin/orders/odetail/' . $OId);
	}
	public function delete_additional($OId = NULL, $apid = NULL) {
		if(isset($OId) && isset($apid)) {
			$this->db->where('OId', $OId);
			$this->db->where('OPID', intval($apid));
			$this->db->delete('OPrice');
		}
		redirect('/admin/orders/odetail/' . $OId);
	}
	public function send_thankyou() {
		if($_POST) {
			$oid = $this->input->post('oid');
			$this->db->where('OId', $oid)->update('odetails', array('FinalFlag' => 1, 'PaymentMade' => 1));
			$sms_flag = intval($this->input->post('sendsmsflag'));
			$this->load->model('tieups_m');
			$tieup = intval($this->tieups_m->get_business_by_oid($oid)['TieupId']);
			if($sms_flag == 1 && $tieup != 2) {
				$this->load->model('odetails_m');
				$ph = $this->odetails_m->get_user_ph_by_oid($oid);
				$this->send_sms_request_to_api($ph, $this->input->post('thank_sms_txt'));
			}
			if($sms_flag == 1 && $tieup == 2) {
				$this->load->model('odetails_m');
				$ph = $this->odetails_m->get_user_ph_by_oid($oid);
				$this->send_hj_sms($ph, $this->input->post('thank_sms_txt'));
			}
			$this->load->model('amenity_m');
			$this->load->model('statushistory_m');
			$this->load->model('opaymtdetail_m');
			$estprices = $this->amenity_m->get_est_prices_by_oid($oid);
			$discprices = $this->amenity_m->get_est_prices_by_oid($oid, TRUE);
			$oprices = $this->statushistory_m->get_oprices($oid);
			$tot_paid = floatval($this->opaymtdetail_m->get_total_paid_amount($oid));
			$tot_billed = floatval($estprices[count($estprices) - 1]['ptotal']) + floatval($oprices[count($oprices) - 1]['ptotal']) - floatval($discprices[count($discprices) - 1]['ptotal']);
			$to_be_paid = round(floatval($tot_billed - $tot_paid), 2);
			if($to_be_paid > 0.01) {
				$this->load->model('odetails_m');
				$user_id = $this->odetails_m->get_user_id_by_oid($oid);
				$this->opaymtdetail_m->create_trxn($user_id, $oid, $to_be_paid, TRUE);
			}
			$followUpHistory['OId'] = $oid;
			$followUpHistory['FupStatusId'] = 17;
			$followUpHistory['Remarks'] = $this->input->post('thank_sms_txt');
			$followUpHistory['UpdatedBy'] = $this->session->userdata('a_name');
			$this->db->insert('ofupstatus', $followUpHistory);
			$this->check_for_referral_order($oid);
			redirect('/admin/orders/odetail/' . $oid);
		}
	}
	private function check_for_referral_order($oid) {
		$this->db->select('odetails.UserId');
		$this->db->from('odetails');
		$this->db->where('odetails.OId', $oid);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		$user_id = intval($result['UserId']);
		$this->load->model('user_m');
		$user = $this->user_m->get_by(array(
			'UserId' => $user_id,
		), TRUE);
		if($user && isset($user->Referer)) {
			$this->db->select('odetails.OId');
			$this->db->from('odetails');
			$this->db->where('odetails.UserId', intval($user_id));
			$this->db->where_in('odetails.ServiceId', array(1, 2, 4));
			$query = $this->db->get();
			$results = $query->result_array();
			if(count($results) == 1) {
				$ref_user = $this->user_m->get_by(array(
					'UserId' => intval($user->Referer),
				), TRUE);
				$coupon_text = 'Your referral invitation was accepted by the other person.';
				$this->user_m->give_him_a_coupon($ref_user->UserId, $ref_user->Phone, $coupon_text);
			}
		}
	}
	public function payment_releases() {
		$this->aauth->check_uri_access('preleases');
		$this->data['active'] = 'preleases';
		$this->load->model('opaymtdetail_m');
		$this->data['rows'] = $this->opaymtdetail_m->get_unpaid_payments();
		$this->load->view('admin/preleases', $this->data);
	}
	public function update_preleases() {
		$this->aauth->check_uri_access('preleases');
		if($_POST) {
			$tids = $this->input->post('tids');
			if(count($tids) > 0) {
				$count = 0;
				foreach($tids as $tid) {
					$data[$count]['TId'] = $tid;
					$data[$count]['isWithVendor'] = 1;
					$count += 1;
				}
				$this->db->update_batch('opaymtdetail', $data, 'TId');
			}
		}
		redirect('/admin/orders/payment_releases');
	}
	public function update_opricedetail() {
		if($_POST) {
			$ptype = $this->input->post('ptype');
			if($ptype == 'op') {
				$data['Price'] = floatval($this->input->post('oprice'));
				$data['PriceDescription'] = $this->input->post('opdesc');
				$this->db->where('OPID', intval($this->input->post('opid')));
				$this->db->update('OPrice', $data);
			} elseif($ptype == 'ap') {
				$ttype = intval($this->input->post('ttype'));
				$data['Price'] = floatval($this->input->post('oprice'));
				$data['PriceDetails'] = $this->input->post('opdesc');
				if($ttype == 1) {
					$data['TaxPrice'] = round(($data['Price'] * 0.15), 2);
				} elseif($ttype == 2) {
					$data['TaxPrice'] = round(($data['Price'] * 0.145), 2);
				}
				$this->db->where('PriceSplitId', intval($this->input->post('opid')));
				$this->db->update('opricesplit', $data);
			}
		}
		redirect('/admin/orders/odetail/' . $this->input->post('opoid'));
	}
	public function allotted() {
		$this->data['active'] = 'allot';
		$this->data['rows'] = $this->get_allotted_orders();
		$this->data['trows'] = $this->get_min_upc_allotted_orders();
		$this->load->model('executive_m');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->data['serexs'] = $this->executive_m->get_by(array('isActive' => 1, 'CityId' => intval($this->session->userdata('a_city_id'))));
		} else {
			$this->data['serexs'] = $this->executive_m->get_by(array('isActive' => 1));
		}
		$this->load->view('admin/allotted', $this->data);
	}
	public function archived() {
		$this->data['active'] = 'history';
		$this->data['rows'] = $this->get_archived_orders();
		$checkbox = array('Service Center' => 'servicecenter.ScName AS ServiceCenter', 'Order Date' => 'odetails.ODate AS OrderDate', 'Time' => 'odetails.SlotHour AS Time', 'City' => 'city.CityName AS City', 'Tieup' => 'tieups.TieupName AS Tieup', 'Insurance Renewal Date' => 'odetails.insurance_renewal_date AS InsuranceRenewalDate', 'PUC Renewal Date' => 'odetails.puc_renewal_date AS PUCRenewalDate', 'Service Reminder Date' => 'odetails.service_reminder_date AS ServiceReminderDate', 'Service' => 'service.ServiceName AS Service', 'User' => 'user.UserName AS User', 'Mobile' => 'user.Phone AS Mobile', 'Location' => 'location.LocationName AS Location', 'Bike Number' => 'odetails.BikeNumber AS BikeNumber', 'Device' => 'odetails.UserDevice AS Device', 'Admin Remarks' => 'ofupstatus.Remarks AS AdminRemarks', 'Executive Remarks' => 'oexfupstatus.Remarks AS ExecutiveRemarks', 'Coupon Code' => 'coupons.CCode AS CouponCode', 'Referral Code' => 'fcoupons.CCode AS ReferralCode');
		$dropdown = array('Admin Follow Up', 'Executive Follow Up', 'Service Center', 'Tieup');
		$this->data['parameters'] = array('checkbox' => $checkbox, 'dropdown' => $dropdown);
		$this->db->select('ScId, ScName'); $this->db->from('servicecenter'); $this->db->where('isVerified', 1);
		$query = $this->db->get(); $results = $query->result_array(); $this->data['service_center'] = $results;
		$this->db->select('TieupId, TieupName'); $this->db->from('tieups'); $this->db->where('isEnabled', 1);
		$query = $this->db->get(); $results = $query->result_array(); $this->data['tie_up'] = $results;
		$this->db->select('FupStatusId, FupStatusName'); $this->db->from('fupstatus');
		$query = $this->db->get(); $results = $query->result_array(); $this->data['admin_followup'] = $results;
		$this->db->select('EFupStatusId, EFupStatusName'); $this->db->from('exfupstatus');
		$query = $this->db->get(); $results = $query->result_array(); $this->data['executive_followup'] = $results;
		$this->load->view('admin/archived', $this->data);
	}
	public function queried() {
		$this->data['active'] = 'queried';
		$this->data['rows'] = $this->get_queried_orders();
		$this->load->view('admin/queried', $this->data);
	}
	public function serviced() {
		$this->data['active'] = 'serviced';
		$this->data['rows'] = $this->get_serviced_orders();
		$this->load->view('admin/serviced', $this->data);
	}
	public function unallotted() {
		$this->data['active'] = 'unallot';
		$this->data['rows'] = $this->get_unallotted_orders();
		$this->load->view('admin/unallotted', $this->data);
	}
	public function upcoming() {
		$this->data['active'] = 'upcoming';
		$this->data['rows'] = $this->get_upcoming_orders();
		$this->data['trows'] = $this->get_min_upc_allotted_orders();
		$this->load->model('executive_m');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->data['serexs'] = $this->executive_m->get_by(array('isActive' => 1, 'CityId' => intval($this->session->userdata('a_city_id'))));
		} else {
			$this->data['serexs'] = $this->executive_m->get_by(array('isActive' => 1));
		}
		$this->load->view('admin/upcoming', $this->data);
	}
	public function grievance() {
		$this->data['active'] = 'grievance';
		$this->data['rows'] = $this->get_grievance_orders();
		$this->load->view('admin/grievance', $this->data);
	}
	public function assign_execs() {
		if($_POST) {
			$oids = $this->input->post('ea_oids');
			$execids = $this->input->post('ea_serexs');
			$this->db->where_in('OId', $oids);
			$this->db->delete('execassigns');
			if(isset($oids) && isset($execids) && count($oids) > 0 && count($execids) > 0) {
				$count = 0;
				foreach($oids as $oid) {
					foreach($execids as $execid) {
						$idata[$count]['OId'] = $oid;
						$idata[$count]['ExecId'] = intval($execid);
						$count ++;
					}
				}
			}
			$this->db->insert_batch('execassigns', $idata);
		}
		redirect('/admin/orders/upcoming');
	}
	private function get_nps_rating_by_oid($OId) {
		$rating = $this->db->select('rating')->from('g6rating')->where('OId', $OId)->get()->result_array();
		if(count($rating) > 0) { return intval($rating[0]['rating']); } else { return 0; }
	}
	private function get_user_feedback_rating_by_oid($OId) {
		$rating = $this->db->select('user_feedback_rating')->from('odetails')->where('OId', $OId)->get()->result_array();
		if(count($rating) > 0) { return intval($rating[0]['user_feedback_rating']); } else { return 0; }
	}
	private function get_user_feedback_rating_by_oid_question($OId) {
		$rating = $this->db->select('ExecFbAnswer')->from('user_feedback')->where('OId', $OId)->where('ExecFbQId', 3)->get()->result_array();
		if(count($rating) > 0) { return intval($rating[0]['ExecFbAnswer']); } else { return 0; }
	}
	private function update_user_feedback_rating($oid) {
		$old_rating_admin = $this->get_user_feedback_rating_by_oid($oid);
		$old_rating_user = $this->get_user_feedback_rating_by_oid_question($oid);
		$o = array(); $o['user_feedback_rating'] = floatval($this->input->post('user_feedback_rating'));
		$this->db->where('OId', $oid); $this->db->update('odetails', $o);
		if($old_rating_admin == 0) {
			if($old_rating_user == 0) { $old = 0; } else { $old = $old_rating_user;	}
		} else {
			if($old_rating_user == 0) {	$old = $old_rating_admin; } else { $old = ($old_rating_admin + $old_rating_user) / 2; }
		}
		$this->load->model('odetails_m'); $sc_id = $this->odetails_m->get_scid_by_oid($oid);
		if($sc_id != NULL) {
			$this->load->model('servicecenter_m');
			$rating = $this->servicecenter_m->get_name_rating($sc_id);
			$ratersCount = intval($rating['RatersCount']); $rating = floatval($rating['Rating']);
			$totalRating = $ratersCount * $rating;
			if($old != 0) { $ratersCount -= 1; $totalRating -= $old; }
			if($old_rating_user != 0) {
				$totalRating += round(((floatval($this->input->post('user_feedback_rating')) + floatval($old_rating_user)) / 2), 2);
			} else { $totalRating += floatval($this->input->post('user_feedback_rating')); }
			$ratersCount += 1; $new_rating = round(($totalRating / $ratersCount), 2);
			$this->db->where('ScId', $sc_id); $this->db->update('servicecenter', array("Rating" => $new_rating, "RatersCount" => $ratersCount));
		}
	}
	private function update_nps_rating($oid) {
		$sql = 'INSERT INTO g6rating (OId, rating) VALUES (?, ?) ON DUPLICATE KEY UPDATE rating = VALUES(rating)';
		$query = $this->db->query($sql, array($oid, intval($this->input->post('nps'))));
	}
	public function fup_status_update() {
		$idata['OId'] = $oid = $this->input->post('oid');
		if($this->input->post('nps') && intval($this->input->post('nps')) > 0) { $this->update_nps_rating($oid); }
		if($this->input->post('user_feedback_rating') && intval($this->input->post('user_feedback_rating')) > 0) {
			$this->update_user_feedback_rating($oid);
		}
		if($this->input->post('fup_status') != '') {
			$idata['Remarks'] = $this->input->post('fup_remarks');
			$idata['FupStatusId'] = intval($this->input->post('fup_status'));
			$idata['UpdatedBy'] = $this->session->userdata('a_name');
			$this->db->insert('ofupstatus', $idata);
			$this->db->where('OId', $idata['OId']);
			$this->db->update('odetails', array('LastFupStatusId' => $idata['FupStatusId']));
			$smstouser = intval($this->input->post('smstouser'));
			$smstoexec = intval($this->input->post('smstoexec'));
			if($idata['FupStatusId'] == 16) {
				$this->db->where('odetails.OId', $oid)->update('odetails', array('FeedbackCallReminderFlag' => 1));
				if($this->input->post('feedback_remarks') && trim($this->input->post('feedback_remarks')) != '') {
					$msg = trim($this->input->post('feedback_remarks'));
					$this->send_gear6_txt_email('support@gear6.in', "rakesh@gear6.in", 'New Feedback Suggestion Received # ' . $oid, $msg);
					$this->send_gear6_txt_email('support@gear6.in', "srikanth@gear6.in", 'New Feedback 
						Suggestion Received # ' . $oid, $msg);
					$this->send_gear6_txt_email('support@gear6.in', "jude@gear6.in", 'New Feedback Suggestion Received # ' . $oid, $msg);
				}
			}
			if($smstouser == 1 && $idata['FupStatusId'] == 19) {
				$uphone = $this->uphone_by_oid($oid);
				$enc_oid = encrypt_oid($oid);
				$paymt_url = 'https://www.gear6.in/home/gpaymtlink/' . $enc_oid;
				$this->send_sms_request_to_api($uphone, 'Payment link for your gear6.in order ' . $oid . ' is ' . $paymt_url . ' . Kindly visit and make the payment.');
			}
			if($smstouser == 1 && $idata['FupStatusId'] == 13) {
				$uphone = $this->uphone_by_oid($oid);
				$this->send_sms_request_to_api($uphone, $idata['Remarks']);
			}
			if($smstoexec == 1 && $idata['FupStatusId'] == 14) {
				$ephones = $this->exphones_by_oid($oid);
				foreach($ephones as $ephone) {
					$this->send_sms_request_to_api($ephone, $idata['Remarks']);
				}
			}
			if($idata['FupStatusId'] == 20) {
				if(isset($_POST['estimated_date']) && $_POST['estimated_date'] != '' && $_POST['estimated_date'] != NULL && isset($_POST['estimated_time']) && $_POST['estimated_time'] != '' && $_POST['estimated_time'] != NULL && isset($_POST['estimated_price']) && $_POST['estimated_price'] != '' && $_POST['estimated_price'] != NULL) {
					$jobcarddetails['EstDate'] = $this->input->post('estimated_date');
					$jobcarddetails['EstTime'] = $this->input->post('estimated_time');
					$jobcarddetails['EstPrice'] = $this->input->post('estimated_price');
					$this->db->where('OId', $idata['OId']);
					$this->db->update('jobcarddetails', $jobcarddetails);
				}
			}
			if($idata['FupStatusId'] == 21) {
				$this->db->where('OId', $idata['OId']);
				$this->db->update('odetails', array('InvoiceUpdated' => 1));
			}
			if($idata['FupStatusId'] == 23) {
				$followup_reminder_date = $this->input->post("followup_reminder_date");
				$followup_reminder_type = $this->input->post("followup_reminder_type");
				if($followup_reminder_date != NULL && $followup_reminder_date != "" && $followup_reminder_type != NULL && $followup_reminder_type != "") {
					$this->db->where('OId', $idata['OId']);
					$this->db->update('odetails', array($followup_reminder_type => date("Y-m-d", strtotime($followup_reminder_date))));
				}
			}
		}
		redirect(site_url('admin/orders/odetail/' . $this->input->post('oid')));
	}
	public function emailinvoice($OId = NULL) {
		if(isset($OId)) {
			$this->data['OId'] = $OId;
			$this->load->model('odetails_m');
			$this->load->model('amenity_m');
			$this->load->model('statushistory_m');
			$this->load->model('opaymtdetail_m');
			$invoiceDate = $this->odetails_m->getInvoiceDate($OId);
			if($invoiceDate == NULL) {
				$this->odetails_m->setInvoiceDate($OId, date('Y-m-d', time()));
				$this->data['InvoiceDate'] = date('Y-m-d', time());
			} else {
				$this->data['InvoiceDate'] = $invoiceDate;
			}
			$this->db->where('OId', $OId)->update('odetails', array('InvoiceSent' => 1));
			$service_details = $this->odetails_m->get_stype_by_oid($OId);
			$this->data['InvoiceDate']= $this->odetails_m->getInvoiceDate($OId);
			$this->data['stype'] = $service_details['ServiceName'];
			$user_details = $this->odetails_m->get_user_address($OId);
			$this->data['uaddress'] = $user_details['address'];
			$this->data['uname'] = $user_details['name'];
			$this->data['uemail'] = $user_details['email'];
			$this->data['uphone'] = $user_details['Phone'];
			$this->data['timeslot'] = $this->odetails_m->get_timeslot_by_oid($OId);
			$this->data['paymode'] = $this->odetails_m->get_paymode_by_oid($OId);
			$bike_model_details = $this->odetails_m->get_bm_by_oid($OId);
			$this->data['bikenumber'] = $bike_model_details['BikeNumber'];
			$this->data['bikemodel'] = $bike_model_details['BikeCompanyName'] . ' ' . $bike_model_details['BikeModelName'];
			$this->data['estprices'] = $this->amenity_m->get_est_prices_by_oid($OId);
			$this->data['discprices'] = $this->amenity_m->get_est_prices_by_oid($OId, TRUE);
			$this->data['oprices'] = $this->statushistory_m->get_oprices($OId);
			$this->data['tot_paid'] = floatval($this->opaymtdetail_m->get_total_paid_amount($OId));
			$this->data['tot_billed'] = floatval($this->data['estprices'][count($this->data['estprices']) - 1]['ptotal']) + floatval($this->data['oprices'][count($this->data['oprices']) - 1]['ptotal']) - floatval($this->data['discprices'][count($this->data['discprices']) - 1]['ptotal']);
			$this->data['to_be_paid'] = round(floatval($this->data['tot_billed'] - $this->data['tot_paid']), 2);
			if($this->data['to_be_paid'] < 0.01 && $this->data['to_be_paid'] > -0.01) {
				$this->data['to_be_paid'] = 0;
			}
			$this->load->library('m_pdf');
			$this->m_pdf->send_invoice('emails/invoice_pdf', $this->data, $OId);
			$followUpHistory['OId'] = $OId;
			$followUpHistory['FupStatusId'] = 18;
			$followUpHistory['Remarks'] = NULL;
			$followUpHistory['UpdatedBy'] = $this->session->userdata('a_name');
			$this->db->insert('ofupstatus', $followUpHistory);
			redirect(site_url('admin/orders/odetail/' . $OId));
		} else {
			redirect("admin/orders");
		}
	}
	public function invoice($OId = NULL) {
		if(isset($OId)) {
			$this->data['OId'] = $OId;
			$this->load->model('odetails_m');
			$this->load->model('amenity_m');
			$this->load->model('statushistory_m');
			$this->load->model('opaymtdetail_m');
			$invoiceDate = $this->odetails_m->getInvoiceDate($OId);
			if($invoiceDate == NULL) {
				$this->odetails_m->setInvoiceDate($OId, date('Y-m-d', time()));
				$this->data['InvoiceDate'] = date('Y-m-d', time());
			} else {
				$this->data['InvoiceDate'] = $invoiceDate;
			}
			$service_details = $this->odetails_m->get_stype_by_oid($OId);
			$this->data['stype'] = $service_details['ServiceName'];
			$user_details = $this->odetails_m->get_user_address($OId);
			$this->data['uaddress'] = $user_details['address'];
			$this->data['uname'] = $user_details['name'];
			$this->data['uemail'] = $user_details['email'];
			$this->data['uphone'] = $user_details['Phone'];
			$this->data['timeslot'] = $this->odetails_m->get_timeslot_by_oid($OId);
			$this->data['paymode'] = $this->odetails_m->get_paymode_by_oid($OId);
			$bike_model_details = $this->odetails_m->get_bm_by_oid($OId);
			$this->data['bikenumber'] = $bike_model_details['BikeNumber'];
			$this->data['bikemodel'] = $bike_model_details['BikeCompanyName'] . ' ' . $bike_model_details['BikeModelName'];
			$this->data['estprices'] = $this->amenity_m->get_est_prices_by_oid($OId);
			$this->data['discprices'] = $this->amenity_m->get_est_prices_by_oid($OId, TRUE);
			$this->data['oprices'] = $this->statushistory_m->get_oprices($OId);
			$this->data['tot_paid'] = floatval($this->opaymtdetail_m->get_total_paid_amount($OId));
			$this->data['tot_billed'] = floatval($this->data['estprices'][count($this->data['estprices']) - 1]['ptotal']) + floatval($this->data['oprices'][count($this->data['oprices']) - 1]['ptotal']) - floatval($this->data['discprices'][count($this->data['discprices']) - 1]['ptotal']);
			$this->data['to_be_paid'] = round(floatval($this->data['tot_billed'] - $this->data['tot_paid']), 2);
			if($this->data['to_be_paid'] < 0.01 && $this->data['to_be_paid'] > -0.01) {
				$this->data['to_be_paid'] = 0;
			}
			$this->load->view('admin/invoice', $this->data);
		} else {
			redirect("admin/orders");
		}
	}
	public function jobcard($OId) {
		if(isset($OId)) {
			$this->data['OId'] = $OId;
			$this->load->model('odetails_m');
			$this->load->model('aservice_m');
			$this->load->model('opaymtdetail_m');
			$service_details = $this->odetails_m->get_stype_by_oid($OId);
			$this->data['stype'] = $service_details['ServiceName'];
			$user_details = $this->odetails_m->get_user_address($OId);
			$this->data['csaddress'] = str_replace("</div><div>", ", ", $user_details['address']);
			$this->data['csaddress'] = str_replace("<div>", "", $this->data['csaddress']);
			$this->data['uname'] = $user_details['name'];
			$this->data['uemail'] = $user_details['email'];
			$this->data['uphone'] = $user_details['Phone'];
			$this->data['timeslot'] = $this->odetails_m->get_timeslot_by_oid($OId);
			$this->data['paymode'] = $this->odetails_m->get_paymode_by_oid($OId);
			$bike_model_details = $this->odetails_m->get_bm_by_oid($OId);
			$this->data['bikenumber'] = $bike_model_details['BikeNumber'];
			$this->data['bikemodel'] = $bike_model_details['BikeCompanyName'] . ' ' . $bike_model_details['BikeModelName'];
			$sc_details = $this->odetails_m->get_scenter_by_oid($OId);
			$this->data['scenter'] = $sc_details;
			$this->load->model('amenity_m');
			$this->data['chosen_amenities'] = $this->amenity_m->get_chosen_amenities($OId);
			$this->data['chosen_aservices'] = $this->aservice_m->get_chosen_aservices($OId);
			$this->data['tot_paid'] = floatval($this->opaymtdetail_m->get_total_paid_amount($OId));
			$this->load->view('admin/jcard', $this->data);
		} else {
			redirect('admin/orders');
		}
	}
	public function reschedule_order() {
		if($_POST) {
			$oid = $this->input->post('oid'); $date = $this->input->post('res_date'); $time = $this->input->post('res_time');
			$this->db->where('OId', $oid)->update('odetails', array('ODate' => $date, 'SlotHour' => $time));
			$sms_flag = intval($this->input->post('sendsmsflag'));
			$admin['new_order_reschedule'] = 1; $admin['new_order_reschedule_dismissed'] = 0;
			$this->db->where('OId', $oid)->update('admin_notification_flags', $admin);
			$and_reg_ids = $this->get_all_active_admin_devices();
			if(count($and_reg_ids) > 0) {
				$and_push_msg_data = array("message" => 'As requested, your gear6.in order ' . $oid . ' was Rescheduled to Date: ' . date("l, F d, Y", strtotime($date)), "tag" => "odetailwithjobcard", "oid" => $oid);
				$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
			}
			if($sms_flag == 1) {
				$this->load->model('odetails_m');
				$ph = $this->odetails_m->get_user_ph_by_oid($oid);
				$this->send_sms_request_to_api($ph, 'As requested, your gear6.in order ' . $oid . ' was Rescheduled to Date: ' . date("l, F d, Y", strtotime($date)));
			}
		}
		redirect(site_url('admin/orders/odetail/' . $oid));
	}
	public function cancel_order($oid = NULL, $from_details = NULL, $sms_flag = NULL) {
		if(isset($oid)) {
			$this->db->select('status.StatusId');
			$this->db->from('odetails');
			$this->db->join('status', 'status.ServiceId = odetails.ServiceId');
			$this->db->where('odetails.OId', $oid);
			$this->db->where('status.Order', -2);
			$this->db->limit(1);
			$query = $this->db->get();
			$result = $query->result_array();
			if (count($result) == 0) {
				return NULL;
			} else {
				$this->db->where('OId', $oid);
				$this->db->limit(1);
				$this->db->update('oservicedetail', array('StatusId' => intval($result[0]['StatusId'])));
				if(isset($sms_flag) && intval($sms_flag) == 1) {
					$this->load->model('odetails_m');
					$ph = $this->odetails_m->get_user_ph_by_oid($oid);
					$this->send_sms_request_to_api($ph, 'Based on the telephone conversation, your gear6.in order ' . $oid . ' was Cancelled. Thank you for stopping by, we hope to see you again soon.');
				}
			}
		}
		if(isset($from_details)) {
			redirect(site_url('admin/orders/odetail/' . $oid));
		} else {
			redirect('/admin/orders/unallotted');
		}
	}
	public function changeStatus() {
		if(isset($_POST['changeStatus'])) {
			$data['OId'] = $oid = $this->input->post('oid');
			$this->load->model('odetails_m');
			$serid = intval($this->odetails_m->get_by(array('OId' => $oid), TRUE)->ServiceId);
			$data['StatusId'] = intval($this->input->post('servicetype'));
			$data['ScId'] = intval($this->input->post('sc_id'));
			$data['StatusDescription'] = $this->input->post('stat_desc_o') . $this->input->post('stat_desc_c');
			$data['AdminNotes'] = $this->input->post('admin_notes') . $this->input->post('admin_notes1');
			$data['ModifiedBy'] = $this->session->userdata('a_name');
			$this->load->model('statushistory_m');
			$this->statushistory_m->save($data);
			$this->db->where('OId', $data['OId']);
			if($serid != 4) {
				$this->db->where('ScId', $data['ScId']);
			}
			$this->db->limit(1);
			$this->db->update('oservicedetail', array('StatusId' => $data['StatusId']));
			$sms_flag = intval($this->input->post('sendsmsflag'));
			$this->load->model('tieups_m');
			$tieup = intval($this->tieups_m->get_business_by_oid($data['OId'])['TieupId']);
			if($sms_flag == 1) {
				$this->load->model('odetails_m');
				$ph = $this->odetails_m->get_user_ph_by_oid($data['OId']);
			}
			if($data['StatusId'] == 1 || $data['StatusId'] == 8 || $data['StatusId'] == 15 || $data['StatusId'] == 18) {
				$this->db->where('OId', $data['OId']);
				$this->db->where('ScId', $data['ScId']);
				$this->db->limit(1);
				$this->db->update('oservicedetail', array('isNotified' => 4));
				if($sms_flag == 1 && $tieup != 2) {
					$this->send_sms_request_to_api($ph, 'Your service request ' . $data['OId'] . ' is approved - gear6.in');
				}
				if($sms_flag == 1 && $tieup == 2) {
					$this->send_hj_sms($ph, 'Your service request ' . $data['OId'] . ' is approved - housejoy.in');
				}
			}
			if($sms_flag == 1 && $tieup != 2) {
				if($data['StatusId'] == 4) {
					$this->send_sms_request_to_api($ph, 'Your bike\'s maintenance checkup is done and the servicing will start soon.');
				}
				if($data['StatusId'] == 3) {
					$this->send_sms_request_to_api($ph, 'Your bike service has been started. We will update you as soon as its done');
				}
				if($data['StatusId'] == 7) {
					$emdata['oid'] = $oid;
					$emdata['odetails'] = $this->db->select('odetails.*, user.UserName, user.Email, user.Phone')->from('odetails')->join('user', 'user.UserId = odetails.UserId')->where('odetails.OId', $oid)->limit(1)->get()->row();
					$emdata['disc_amount'] = $this->get_total_discount_amount($oid);
					$emdata['tot_conv'] = $this->get_total_conv_amount($oid);
					$emdata['tot_billed'] = $this->get_total_billed_amount($oid) - $emdata['tot_conv'];
					$emdata['tot_paid'] = $this->get_total_paid_amount($oid);
					$emdata['to_be_paid'] = $emdata['tot_billed'] + $emdata['tot_conv'] - $emdata['tot_paid'] - $emdata['disc_amount'];
					if($emdata['to_be_paid'] < 0.01) {
						$emdata['to_be_paid'] = 0;
					}
					$enc_oid = encrypt_oid($oid);
					$emdata['paymturl'] = $url = 'https://www.gear6.in/home/gpaymtlink/' . $enc_oid;
					$tot_billed = $emdata['tot_billed'] + $emdata['tot_conv'];
					$to_be_paid = $emdata['to_be_paid'];
					if($to_be_paid > 0.01) {
						$this->send_gear6_email($emdata['odetails']->Email, 'gear6.in - Payment link for ' . $oid, 'pinvemail', $emdata);
						$this->send_sms_request_to_api($ph, 'Your bike has been serviced. The final bill amount is INR ' . $tot_billed . ' and the amount payable is INR ' . $to_be_paid . '. To make the payment, visit ' . $url);
					}
				}
				if($data['StatusId'] == 10) {
					$this->send_sms_request_to_api($ph, 'Your bike\'s checkup is done and the repair will be initiated soon.');
				}
				if($data['StatusId'] == 11) {
					$this->send_sms_request_to_api($ph, 'Your bike repair has been started. We will update you as soon as its done');
				}
				if($data['StatusId'] == 14) {
					$emdata['oid'] = $oid;
					$emdata['odetails'] = $this->db->select('odetails.*, user.UserName, user.Email, user.Phone')->from('odetails')->join('user', 'user.UserId = odetails.UserId')->where('odetails.OId', $oid)->limit(1)->get()->row();
					$emdata['disc_amount'] = $this->get_total_discount_amount($oid);
					$emdata['tot_conv'] = $this->get_total_conv_amount($oid);
					$emdata['tot_billed'] = $this->get_total_billed_amount($oid) - $emdata['tot_conv'];
					$emdata['tot_paid'] = $this->get_total_paid_amount($oid);
					$emdata['to_be_paid'] = $emdata['tot_billed'] + $emdata['tot_conv'] - $emdata['tot_paid'] - $emdata['disc_amount'];
					if($emdata['to_be_paid'] < 0.01) {
						$emdata['to_be_paid'] = 0;
					}
					$enc_oid = encrypt_oid($oid);
					$emdata['paymturl'] = $url = 'https://www.gear6.in/home/gpaymtlink/' . $enc_oid;
					$tot_billed = $emdata['tot_billed'] + $emdata['tot_conv'];
					$to_be_paid = $emdata['to_be_paid'];
					if($to_be_paid > 0.01) {
						$this->send_gear6_email($emdata['odetails']->Email, 'gear6.in - Payment link for ' . $oid, 'pinvemail', $emdata);
						$this->send_sms_request_to_api($ph, 'Your bike has been repaired. The final bill amount is INR ' . $tot_billed . ' and the amount payable is INR ' . $to_be_paid . '. To make the payment, visit ' . $url);
					}
				}
				if($data['StatusId'] == 17) {
					$this->send_sms_request_to_api($ph, 'Your Query is answered, Hope you have got your answers. Thanks for Querying - gear6.in');
				}
				if($data['StatusId'] == 25) {
					$this->send_sms_request_to_api($ph, 'Your details checking is successfully done and they\'ll be updated for the renewal soon');
				}
				if($data['StatusId'] == 20) {
					$emdata['oid'] = $oid;
					$emdata['odetails'] = $this->db->select('odetails.*, user.UserName, user.Email, user.Phone')->from('odetails')->join('user', 'user.UserId = odetails.UserId')->where('odetails.OId', $oid)->limit(1)->get()->row();
					$emdata['disc_amount'] = $this->get_total_discount_amount($oid);
					$emdata['tot_conv'] = $this->get_total_conv_amount($oid);
					$emdata['tot_billed'] = $this->get_total_billed_amount($oid) - $emdata['tot_conv'];
					$emdata['tot_paid'] = $this->get_total_paid_amount($oid);
					$emdata['to_be_paid'] = $emdata['tot_billed'] + $emdata['tot_conv'] - $emdata['tot_paid'] - $emdata['disc_amount'];
					if($emdata['to_be_paid'] < 0.01) {
						$emdata['to_be_paid'] = 0;
					}
					$enc_oid = encrypt_oid($oid);
					$emdata['paymturl'] = $url = 'https://www.gear6.in/home/gpaymtlink/' . $enc_oid;
					$tot_billed = $emdata['tot_billed'] + $emdata['tot_conv'];
					$to_be_paid = $emdata['to_be_paid'];
					if($to_be_paid > 0.01) {
						$this->send_gear6_email($emdata['odetails']->Email, 'gear6.in - Payment link for ' . $oid, 'pinvemail', $emdata);
						$this->send_sms_request_to_api($ph, 'Your bike has been serviced. The final bill amount is INR ' . $tot_billed . ' and the amount payable is INR ' . $to_be_paid . '. To make the payment, visit ' . $url);
					}
				}
			}
			if($sms_flag == 1 && $tieup == 2) {
				if($data['StatusId'] == 4) {
					$this->send_hj_sms($ph, 'Your bike\'s maintenance checkup is done and the servicing will start soon.');
				}
				if($data['StatusId'] == 3) {
					$this->send_hj_sms($ph, 'Your bike\'s Servicing has been started. Bike is setup on the rack to repair and revive.');
				}
				if($data['StatusId'] == 7) {
					$this->send_hj_sms($ph, 'Your bike\'s Servicing has been finished. Bike is ready to be delivered.');
				}
				if($data['StatusId'] == 10) {
					$this->send_hj_sms($ph, 'Your bike\'s checkup is done and the repair will be initiated soon.');
				}
				if($data['StatusId'] == 11) {
					$this->send_hj_sms($ph, 'Your Bike\'s repair has been initiated, we will let you know when it is done.');
				}
				if($data['StatusId'] == 14) {
					$this->send_hj_sms($ph, 'Your bike\'s repair has been finished. Bike is ready to be delivered.');
				}
				if($data['StatusId'] == 17) {
					$this->send_hj_sms($ph, 'Your Query is answered, Hope you have got your answers. Thanks for Querying - housejoy.in');
				}
				if($data['StatusId'] == 25) {
					$this->send_hj_sms($ph, 'Your details checking is successfully done and they\'ll be updated for the renewal soon');
				}
				if($data['StatusId'] == 20) {
					$this->send_hj_sms($ph, 'Your Bike\'s Insurance has been renewed Successfully, Thanks for booking the request - housejoy.in');
				}
			}
			if($data['StatusId'] == 7 || $data['StatusId'] == 14 || $data['StatusId'] == 20) {
				$order = $this->odetails_m->get_by(array('OId' => $data['OId']), TRUE);
				$this->db->select('*');	$this->db->from('admin_notification_flags'); $this->db->where('OId', $data['OId']);
				$query = $this->db->get(); $results = $query->result_array();
				if($order) {
					if(!$order->puc_renewal_date || !$order->insurance_renewal_date || !$order->service_reminder_date) {
						$adminNotifyFlag['new_renewal_date'] = 1;
						if (count($results) > 0) {
							$this->db->where('OId', $data['OId']);
							$this->db->update('admin_notification_flags', $adminNotifyFlag);
						}
					}
				}
				$and_reg_ids = $this->get_all_assigned_executive_devices($oid);
				$tag = $this->db->select('Tag')->from('jobcarddetails')->where('OId', $oid)->get()->result_array()[0]['Tag'];
				if(count($and_reg_ids) > 0) {
					$and_push_msg_data = array("message" => "Bike is ready to be delivered for order " . $oid, "tag" => $tag, "oid" => $oid);
					$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
				}
				$phones = $this->get_all_assigned_executive_numbers($oid);
				if(count($phones) > 0) {
					foreach ($phones as $phone) {
						$this->send_sms_request_to_api($phone, 'Bike is ready to be delivered for order ' . $oid);
					}
				}
			}
			redirect(site_url('admin/orders/odetail/' . $data['OId']));
		} else {
			$data['OId'] = $this->input->post('oid');
			redirect(site_url('admin/orders/odetail/' . $data['OId']));
		}
	}
	public function updatePrices() {
		if($_POST) {
			$spdetails = explode('||', $this->input->post('spdetails'));
			$sps = explode('||', $this->input->post('sps'));
			$spttypes = explode('||', $this->input->post('spttypes'));
			$oid = $this->input->post('oid');
			$count = 0;
			$amdata = array(array());
			$omdata = array(array());
			$count1 = 0;
			$count2 = 0;
			$count = 0;
			foreach ($spttypes as $spttype) {
				if(intval($spttype) == 0) {
					$omdata[$count1]['OId'] = $oid;
					$omdata[$count1]['Price'] = $sps[$count];
					$omdata[$count1]['PriceDescription'] = $spdetails[$count];
					$count1 += 1;
				} else {
					$amdata[$count2]['OId'] = $oid;
					$amdata[$count2]['PriceDetails'] = $spdetails[$count];
					$amdata[$count2]['Price'] = $sps[$count];
					$amdata[$count2]['isDiscount'] = 0;
					$amdata[$count2]['isConvFee'] = 0;
					$amdata[$count2]['TaxPrice'] = 0;
					$amdata[$count2]['TaxType'] = 0;
					$amdata[$count2]['TaxDesc'] = NULL;
					if(intval($spttype) == 1) {
						$amdata[$count2]['TaxPrice'] = round(floatval($sps[$count]) * 0.15, 2);
						$amdata[$count2]['TaxType'] = 1;
						$amdata[$count2]['TaxDesc'] = 'Service Tax (15 %)';
					} elseif(intval($spttype) == 2) {
						$amdata[$count2]['TaxPrice'] = round(floatval($sps[$count]) * 0.145, 2);
						$amdata[$count2]['TaxType'] = 2;
						$amdata[$count2]['TaxDesc'] = 'VAT (14.5 %)';
					} elseif(intval($spttype) == 3) {
						$amdata[$count2]['isDiscount'] = 1;
					} elseif(intval($spttype) == 4) {
						$amdata[$count2]['PriceDetails'] = 'gear6.in Convenience Fee';
						$amdata[$count2]['TaxPrice'] = round(floatval($sps[$count]) * 0.15, 2);
						$amdata[$count2]['TaxType'] = 1;
						$amdata[$count2]['TaxDesc'] = 'Service Tax (15 %)';
						$amdata[$count2]['isConvFee'] = 1;
					}
					$count2 += 1;
				}
				$count += 1;
			}
			if($count1 > 0) {
				$this->db->insert_batch('OPrice', $omdata);
			}
			if($count2 > 0) {
				$this->db->insert_batch('opricesplit', $amdata);
			}
			if(count($count) > 0) {
				$data = array('isAmtCfmd' => 1, 'isNotified' => 2);
				$this->db->where('OId', $oid);
				$this->db->where('ScId', intval($this->input->post('sc_id')));
				$this->db->update('oservicedetail', $data);
			}
			redirect(site_url('admin/orders/odetail/' . $oid));
		}
	}
	public function feedbackReminders() {
		$this->data['active'] = 'feedbackReminders';
		$this->data['rows'] = $this->get_feedback_reminders();
		$this->load->view('admin/feedbackReminders', $this->data);
	}
	public function insurance_puc_date_update() {
		if($_POST) {
			$count = 0; $OId = $_POST['OId']; $this->load->model('odetails_m');
			$user_id = $this->odetails_m->get_user_id_by_oid($OId); $reminder_array = array();
			$bikenumber = $_POST['bikenumber']; $ODate = $_POST['ODate']; $sameDateFlag = 0;
			if(isset($_POST['insurance_renewal_date']) && strlen($_POST['insurance_renewal_date']) >= 8) {
				$insurance_renewal_date = $_POST['insurance_renewal_date'];
				$odetails['insurance_renewal_date'] = $insurance_renewal_date;
				$count += 1; array_push($reminder_array, 2);
			}
			if(isset($_POST['puc_renewal_date']) && strlen($_POST['puc_renewal_date']) >= 8) {
				$puc_renewal_date = $_POST['puc_renewal_date'];
				$odetails['puc_renewal_date'] = $puc_renewal_date;
				$count += 1; array_push($reminder_array, 3);
			}
			if(isset($_POST['service_reminder_date']) && strlen($_POST['service_reminder_date']) >= 8) {
				$service_reminder_date = $_POST['service_reminder_date'];
				$odetails['service_reminder_date'] = $service_reminder_date;
				$count += 1; array_push($reminder_array, 1);
			}
			if($count > 0) {
				$this->db->where('OId', $OId); $this->db->update('odetails', $odetails);
				$order = $this->odetails_m->get_by(array('OId' => $OId), TRUE);
				$this->db->select('*');	$this->db->from('admin_notification_flags'); $this->db->where('OId', $OId);
				$query = $this->db->get(); $results = $query->result_array();
				if($order) {
					if(!$order->puc_renewal_date || !$order->insurance_renewal_date || !$order->service_reminder_date) {
						$adminNotifyFlag['new_renewal_date'] = 1;						
						if (count($results) > 0) {
							$this->db->where('OId', $OId);
							$this->db->update('admin_notification_flags', $adminNotifyFlag);
						}
					} else {
						$adminNotifyFlag['new_renewal_date_dismissed'] = 1;
						if (count($results) > 0) {
							$this->db->where('OId', $OId);
							$this->db->update('admin_notification_flags', $adminNotifyFlag);
						}
					}
				}
				redirect(site_url('admin/orders/odetail/' . $OId));
			}
		}
		redirect(site_url('admin/orders'));
	}
	public function pickup_drop_flag_update() {
		if($_POST) {
			$count = 0;
			$OId = $_POST['oid'];
			$pickup_drop_flag = $_POST['pick-type'];
			$odetails['pickup_drop_flag'] = $pickup_drop_flag;
			$this->db->where('OId', $OId);
			$this->db->update('odetails', $odetails);
			if($pickup_drop_flag == 2) {
				$sql = 'INSERT INTO jobcarddetails (OId, Tag) VALUES (?, ?) ON DUPLICATE KEY UPDATE Tag = VALUES(Tag)';
				$query = $this->db->query($sql, array($OId, 4));
			}
			redirect(site_url('admin/orders/odetail/' . $OId));
		} else {
			redirect(site_url('admin/orders'));
		}
	}
	public function is_breakdown_flag_update() {
		if($_POST) {
			$OId = $_POST['oid'];
			$this->load->model('odetails_m');
			$od_row = $this->odetails_m->get_by(array('OId' => $OId), TRUE);
			$old_transport_mode = $od_row->transportMode;
			$is_breakdown_flag = intval($this->input->post('isBreakdown'));
			if($is_breakdown_flag == 1) {
				$transport_mode = intval($this->input->post('transport_mode'));
			} else {
				$transport_mode = NULL;
			}
			if($old_transport_mode == 1 && $transport_mode != 1) {
				$execassigns = $this->get_execs_assigned($OId);
				if($execassigns && count($execassigns) > 0) {
					foreach ($execassigns as $ex) {
						$reward = array(); $reward['ExecId'] = $ex['ExecId']; $reward['Amount'] = 50;
						$reward["Type"] = 'Debit'; $reward['updated_at'] = date("Y-m-d", strtotime("now"));
						$reward["OId"] = $OId; $reward["isCleared"] = 0; $reward["ClearFrequency"] = 2;
						$reward['Description'] = "Your rewards wallet is debited by Rs. 50 for manual towing cancellation of gear6.in order " . $OId;
						$reward['UpdatedBy'] = $this->session->userdata('a_name'); $this->db->insert('execrewards', $reward);
						$this->send_sms_request_to_api($ex['Phone'], $reward['Description']);
						$and_push_msg_data = array("title" => "Towing Reward Debited", "message" => $reward['Description'], "screen" => "reward");
						$gcm = array(strval($ex['GCMId']));
						$this->send_gcm_request($gcm, $and_push_msg_data);
					}
				}
			} elseif($old_transport_mode != 1 && $transport_mode == 1) {
				$execassigns = $this->get_execs_assigned($OId);
				if($execassigns && count($execassigns) > 0) {
					foreach ($execassigns as $ex) {
						$reward = array(); $reward['ExecId'] = $ex['ExecId']; $reward['Amount'] = 50;
						$reward["Type"] = 'Credit'; $reward['updated_at'] = date("Y-m-d", strtotime("now"));
						$reward["OId"] = $OId; $reward["isCleared"] = 0; $reward["ClearFrequency"] = 2;
						$reward['Description'] = "Your rewards wallet is credited by Rs. 50 for manual towing of gear6.in order " . $OId;
						$reward['UpdatedBy'] = $this->session->userdata('a_name'); $this->db->insert('execrewards', $reward);
						$this->send_sms_request_to_api($ex['Phone'], $reward['Description']);
						$and_push_msg_data = array("title" => "Towing Reward Credited", "message" => $reward['Description'], "screen" => "reward");
						$gcm = array(strval($ex['GCMId']));
						$this->send_gcm_request($gcm, $and_push_msg_data);
					}
				}
			}
			$odetails['isBreakdown'] = $is_breakdown_flag;
			$odetails['transportMode'] = $transport_mode;
			$this->db->where('OId', $OId); $this->db->update('odetails', $odetails);
			redirect(site_url('admin/orders/odetail/' . $OId));
		} else {
			redirect(site_url('admin/orders'));
		}
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
	public function query_page_analysis() {
		if($_POST) {
			$adata['atype']['name'] = 'Exclusive Query - ' . $this->input->post('atype') . ' Analysis';
			if($this->input->post('atype') == 'Weekly') {
				$date = date("Y-m-d", strtotime("-6 day", strtotime("now")));
				for($i = 0; $i <= 6; $i++) {
					$adata['categories'][$i] = date("d/m", strtotime($date));
					$adata['queried'][$i] = intval($this->getQueried($date));
					$adata['answered'][$i] = intval($this->getQueried($date, 3));
					$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
				}
			} elseif($this->input->post('atype') == 'Monthly') {
				$date = date("Y-m-d", strtotime("-27 day", strtotime("now")));
				for($i = 0; $i <= 3; $i++) {
					$from_date = $date;
					$adata['categories'][$i] = date("d/m", strtotime($date)) . ' - ';
					$date = date("Y-m-d", strtotime("+7 day", strtotime($date)));
					$to_date = $date;
					$adata['categories'][$i] .= date("d/m", strtotime($date));
					$adata['queried'][$i] = intval($this->getQueriedRange($from_date, $to_date));
					$adata['answered'][$i] = intval($this->getQueriedRange($from_date, $to_date, 3));
				}
			}
			echo json_encode($adata);
		}
	}
	public function pick_up_analysis() {
		if($_POST) {
			$adata['atype']['name'] = 'Pick Up Ratio - ' . $this->input->post('atype') . ' Analysis';
			if($this->input->post('atype') == 'Weekly') {
				$date = date("Y-m-d", strtotime("-7 day", strtotime("now")));
				for($i = 0; $i <= 6; $i++) {
					$adata['categories'][$i] = date("d/m", strtotime($date));
					$adata['pick'][$i] = intval($this->getPickups($date));
					$adata['drop'][$i] = intval($this->getDrops($date));
					$adata['pickup_drop'][$i] = intval($this->getPickupsDrops($date));
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
					$adata['pick'][$i] = intval($this->getPickupsRange($from_date, $to_date));
					$adata['drop'][$i] = intval($this->getDropsRange($from_date, $to_date));
					$adata['pickup_drop'][$i] = intval($this->getPickupsDropsRange($from_date, $to_date));
				}
			}
			echo json_encode($adata);
		}
	}
	public function status_type_analysis() {
		$adata['name1'] = 'Future Order Statuses - ' . $this->input->post('atype') . ' Analysis';
		$adata['name2'] = 'Overall Queries - ' . $this->input->post('atype') . ' Analysis';
		if($this->input->post('atype') == 'Weekly') {
			$from_date = date("Y-m-d", strtotime("+1 day", strtotime("now")));
			$to_date = date("Y-m-d", strtotime("+7 day", strtotime("now")));
			$allot = intval($this->getAllotmentsRange($from_date, $to_date, 1));
			$unallot = intval($this->getAllotmentsRange($from_date, $to_date, 0));
			if(($allot + $unallot) == 0) {
				$adata['allot'] = 100;
			} else {
				$adata['allot'] = floatval($allot * 100) / ($allot + $unallot);
			}
			$from_date = date("Y-m-d", strtotime("-6 day", strtotime("now")));
			$to_date = date("Y-m-d", strtotime("now"));
			$ans = intval($this->getQueriedRange($from_date, $to_date, 3));
			$unans = intval($this->getQueriedRange($from_date, $to_date, 0));
			if(($ans + $unans) == 0) {
				$adata['ans'] = 100;
			} else {
				$adata['ans'] = floatval($ans * 100) / ($ans + $unans);
			}
		} elseif($this->input->post('atype') == 'Monthly') {
			$from_date = date("Y-m-d", strtotime("+1 day", strtotime("now")));
			$to_date = date("Y-m-d", strtotime("+29 day", strtotime("now")));
			$allot = intval($this->getAllotmentsRange($from_date, $to_date, 1));
			$unallot = intval($this->getAllotmentsRange($from_date, $to_date, 0));
			if(($allot + $unallot) == 0) {
				$adata['allot'] = 100;
			} else {
				$adata['allot'] = floatval($allot * 100) / ($allot + $unallot);
			}
			$from_date = date("Y-m-d", strtotime("-27 day", strtotime("now")));
			$to_date = date("Y-m-d", strtotime("now"));
			$ans = intval($this->getQueriedRange($from_date, $to_date, 3));
			$unans = intval($this->getQueriedRange($from_date, $to_date, 0));
			if(($ans + $unans) == 0) {
				$adata['ans'] = 100;
			} else {
				$adata['ans'] = floatval($ans * 100) / ($ans + $unans);
			}
		}
		echo json_encode($adata);
	}
	public function future_orders_analysis() {
		$adata['atype']['name'] = 'Future Orders - ' . $this->input->post('atype') . ' Analysis';
		if($this->input->post('atype') == 'Weekly') {
			$date = date("Y-m-d", strtotime("+1 day", strtotime("now")));
			for($i = 0; $i <= 6; $i++) {
				$adata['categories'][$i] = date("d/m", strtotime($date));
				$adata['allot'][$i] = intval($this->getAllotments($date, 1));
				$adata['unallot'][$i] = intval($this->getAllotments($date, 0));
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
			}
		} elseif($this->input->post('atype') == 'Monthly') {
			$date = date("Y-m-d", strtotime("+1 day", strtotime("now")));
			for($i = 0; $i <= 3; $i++) {
				$from_date = $date;
				$adata['categories'][$i] = date("d/m", strtotime($date)) . ' - ';
				$date = date("Y-m-d", strtotime("+7 day", strtotime($date)));
				$to_date = $date;
				$adata['categories'][$i] .= date("d/m", strtotime($date));
				$adata['allot'][$i] = intval($this->getAllotmentsRange($from_date, $to_date, 1));
				$adata['unallot'][$i] = intval($this->getAllotmentsRange($from_date, $to_date, 0));
			}
		}
		echo json_encode($adata);
	}
	public function next_week_analysis() {
		$adata['atype']['name'] = 'Total Orders For Next 7 Days';
		$date = date("Y-m-d", strtotime("+1 day", strtotime("now")));
		for($i = 0; $i <= 6; $i++) {
			$adata['categories'][$i] = date("d/m", strtotime($date));
			$results = $this->getOrderByDate($date);
			$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
			$adata['reser'][$i] = 0;
			$adata['ins'][$i] = 0;
			if($results !== NULL) {
				foreach($results as $result) {
					if($result['ServiceId'] == 1 || $result['ServiceId'] == 2) {
						$adata['reser'][$i] += 1;
					} elseif ($result['ServiceId'] == 4) {
						$adata['ins'][$i] += 1;
					}
				}
			}
		}
		echo json_encode($adata);
	}
	public function order_summary_analysis() {
		$adata['atype']['name'] = 'Order Summary - ' . $this->input->post('atype') . ' Analysis';
		if($this->input->post('atype') == 'Weekly') {
			$date = date("Y-m-d", strtotime("-7 day", strtotime("now")));
			for($i = 0; $i <= 6; $i++) {
				$adata['categories'][$i] = date("d/m", strtotime($date));
				$results = $this->getOrderByDate($date);
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
				$adata['orders'][$i] = 0;
				if($results !== NULL) {
					foreach($results as $result) {
						if($result['ServiceId'] != 3) {
							$adata['orders'][$i] += 1;
						}
					}
				}
			}
		} elseif($this->input->post('atype') == 'Monthly') {
			$date = date("Y-m-d", strtotime("-28 day", strtotime("now")));
			for($i = 0; $i <= 3; $i++) {
				$from_date = $date;
				$adata['categories'][$i] = date("d/m", strtotime($date)) . ' - ';
				$date = date("Y-m-d", strtotime("+7 day", strtotime($date)));
				$to_date = $date;
				$adata['categories'][$i] .= date("d/m", strtotime($date));
				$results = $this->getOrderByDateRange($from_date, $to_date);
				$adata['orders'][$i] = 0;
				if($results !== NULL) {
					foreach($results as $result) {
						if($result['ServiceId'] != 3) {
							$adata['orders'][$i] += 1;
						}
					}
				}
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
	public function order_type_analysis() {
		$adata['atype']['name'] = 'Order Types - ' . $this->input->post('atype') . ' Analysis';
		if($this->input->post('atype') == 'Weekly') {
			$date = date("Y-m-d", strtotime("-7 day", strtotime("now")));
			for($i = 0; $i <= 6; $i++) {
				$adata['categories'][$i] = date("d/m", strtotime($date));
				$adata['servicing'][$i] = 0;
				$adata['repair'][$i] = 0;
				$adata['insurance'][$i] = 0;
				$adata['query'][$i] = 0;
				$adata['average'][$i] = 0;
				$results = $this->getOrderByDate($date);
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
				if($results !== NULL) {
					foreach($results as $result) {
						if($result['ServiceId'] == 1) {
							$adata['servicing'][$i] += 1;
						} elseif ($result['ServiceId'] == 2) {
							$adata['repair'][$i] += 1;
						} elseif ($result['ServiceId'] == 4) {
							$adata['insurance'][$i] += 1;
						} elseif($result['ServiceId'] == 3) {
							$adata['query'][$i] += 1;
						}
					}
					$adata['average'][$i] = floatval(intval($adata['servicing'][$i]) + intval($adata['repair'][$i]) + intval($adata['insurance'][$i]) + intval($adata['query'][$i])) / 4;
				}
			}
		} elseif($this->input->post('atype') == 'Monthly') {
			$date = date("Y-m-d", strtotime("-28 day", strtotime("now")));
			for($i = 0; $i <= 3; $i++) {
				$from_date = $date;
				$adata['categories'][$i] = date("d/m", strtotime($date)) . ' - ';
				$date = date("Y-m-d", strtotime("+7 day", strtotime($date)));
				$to_date = $date;
				$adata['categories'][$i] .= date("d/m", strtotime($date));
				$adata['servicing'][$i] = 0;
				$adata['repair'][$i] = 0;
				$adata['insurance'][$i] = 0;
				$adata['query'][$i] = 0;
				$adata['average'][$i] = 0;
				$results = $this->getOrderByDateRange($from_date, $to_date);
				if($results !== NULL) {
					foreach($results as $result) {
						if($result['ServiceId'] == 1) {
							$adata['servicing'][$i] += 1;
						} elseif ($result['ServiceId'] == 2) {
							$adata['repair'][$i] += 1;
						} elseif ($result['ServiceId'] == 4) {
							$adata['insurance'][$i] += 1;
						} elseif($result['ServiceId'] == 3) {
							$adata['query'][$i] += 1;
						}
					}
					$adata['average'][$i] = floatval(intval($adata['servicing'][$i]) + intval($adata['repair'][$i]) + intval($adata['insurance'][$i]) + intval($adata['query'][$i])) / 4;
				}
			}
		}
		echo json_encode($adata);
	}
	public function spareParts() {
		$this->data['active'] = 'spareParts';
		$this->load->view('admin/spareParts', $this->data);
	}
	public function getBikeModels() {
		if($_POST) {
			$output = array();
			$this->db->select('bikecompany.BikeCompanyId, bikecompany.BikeCompanyName, bikemodel.BikeModelId, bikemodel.BikeModelName');
			$this->db->from('bikecompany');
			$this->db->join('bikemodel', 'bikemodel.BikeCompanyId = bikecompany.BikeCompanyId', 'left');
			$this->db->like('bikecompany.BikeCompanyName', $this->input->post('text'), 'both');
			$this->db->or_like('bikemodel.BikeModelName', $this->input->post('text'), 'both');
			$query = $this->db->get();
			$results = $query->result_array();
			if (count($results) > 0) {
				foreach($results as $result) {
					$output[] = $result['BikeCompanyName'] . " " . $result['BikeModelName'] . " (" . $result['BikeCompanyId'] . ")(" . $result['BikeModelId'] . ")";
				}
			}
			echo json_encode($output);
		}
	}
	public function getLocations() {
		if($_POST) {
			$output = array();
			$this->db->select('*');
			$this->db->from('location');
			$this->db->like('location.LocationName', $this->input->post('text'), 'both');
			$query = $this->db->get();
			$results = $query->result_array();
			if (count($results) > 0) {
				foreach($results as $result) {
					$output[] = $result['LocationName'] . " (" . $result['LocationId'] . ")";
				}
			}
			echo json_encode($output);
		}
	}
	public function getSparePartsByServiceCenter() {
		if($_POST) {
			$output = array(); $serviceCenter = $this->input->post('serviceCenter');
			$this->db->select('PriceDescription AS sparePart, Price');
			$this->db->from('OPrice');
			$this->db->join('odetails', 'odetails.OId = OPrice.OId', 'left');
			$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
			$this->db->where('oservicedetail.ScId', $serviceCenter);
			$query = $this->db->get();
			$results1 = $query->result_array();
			$this->db->select('PriceDetails AS sparePart, Price');
			$this->db->from('opricesplit');
			$this->db->join('odetails', 'odetails.OId = opricesplit.OId', 'left');
			$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
			$this->db->where('oservicedetail.ScId', $serviceCenter);
			$query = $this->db->get();
			$results2 = $query->result_array();
			if ((count($results1) + count($results2)) > 0) {
				$results = array_merge($results1, $results2);
				foreach($results as $result) {
					$output[] = $result['sparePart'] . " (" . $result['Price'] . " Rs.)";
				}
			}
			echo json_encode($output);
		}
	}
	public function getServiceCenterByLocation() {
		if($_POST) {
			$output = array(); $latitude = $this->input->post('latitude'); $longitude = $this->input->post('longitude');
			$this->db->select('servicecenter.ScId, servicecenter.ScName, scaddr.Latitude, scaddr.Longitude'); $this->db->from('scaddr');
			$this->db->join('scaddrsplit', 'scaddrsplit.ScAddrSplitId = scaddr.ScAddrSplitId', 'left');
			$this->db->join('servicecenter', 'servicecenter.ScId = scaddrsplit.ScId', 'left');
			$this->db->join('MapScBm', 'MapScBm.ScId = servicecenter.ScId', 'left');
			$this->db->where('MapScBm.BikeModelId', $this->input->post('BikeModelId'));
			$query = $this->db->get(); $results = $query->result_array();
			if (count($results) > 0) {
				foreach($results as $result) {
					$distance = $this->distance($latitude, $longitude, $result["Latitude"], $result["Longitude"]);
					if($distance <= 20) {
						$output[] = array('text' => $result['ScName'] . " (" . $distance . " Kms)(" . $result['ScId'] . ")", 'distance' => $distance);
					}
				}
			}
			usort($output, array($this, "sortOutputByDistance"));
			$output = array_column($output, 'text');
			echo json_encode($output);
		}
	}
	public static function sortOutputByDistance($a, $b) {
		return $a['distance'] > $b['distance'];
	}
	public function getPricing() {
		if($_POST) {
			$bikeModel = $this->input->post('bikeModel');
			$serviceCenter = $this->input->post('serviceCenter');
			$sparePart = $this->input->post('sparePart');
			if(!isset($sparePart) || $sparePart == NULL) {
				$this->getLastOrderPricing($bikeModel, $serviceCenter);
			} else {
				$this->db->select('Price'); $this->db->from('OPrice');
				$this->db->join('odetails', 'odetails.OId = OPrice.OId', 'left');
				$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
				$this->db->where('odetails.BikeModelId', $bikeModel);
				$this->db->where('oservicedetail.ScId', $serviceCenter);
				$this->db->like('OPrice.PriceDescription', $sparePart, 'both');
				$query = $this->db->get(); $results1 = $query->result_array();
				$this->db->select('Price');	$this->db->from('opricesplit');
				$this->db->join('odetails', 'odetails.OId = opricesplit.OId', 'left');
				$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
				$this->db->where('odetails.BikeModelId', $bikeModel);
				$this->db->where('oservicedetail.ScId', $serviceCenter);
				$this->db->like('opricesplit.PriceDetails', $sparePart, 'both');
				$query = $this->db->get(); $results2 = $query->result_array();
				if ((count($results1) + count($results2)) > 0) {
					$results = array_merge($results1, $results2);
					foreach($results as $result) {
						$sparePartPriceRange[] = ($result['Price']);
					}
					$this->data['sparePartPriceRange'] = $sparePartPriceRange;
					$this->data['sparePartPriceMin'] = min($sparePartPriceRange);
					$this->data['sparePartPriceMax'] = max($sparePartPriceRange);
				} else {
					$this->data['nothingToShowForSparePart'] = "No data available for this spare part. Showing the last successfully completed order for this service center and bike model.";
					$this->getLastOrderPricing($bikeModel, $serviceCenter);
				}
			}
			$this->data['active'] = 'spareParts';
			$this->load->view('admin/spareParts', $this->data);
		} else {
			redirect(site_url('admin/orders/spareParts'));
		}
	}
	public function emg_pt_order_analysis() {
		$adata['atype']['name'] = 'Emergency / Puncture Orders - ' . $this->input->post('atype') . ' Analysis';
		if($this->input->post('atype') == 'Weekly') {
			$date = date("Y-m-d", strtotime("-7 day", strtotime("now")));
			for($i = 0; $i <= 6; $i++) {
				$adata['categories'][$i] = date("d/m", strtotime($date));
				$adata['emergency'][$i] = 0;
				$adata['puncture'][$i] = 0;
				$results = $this->getemgptordersgraph($date);
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
				if($results !== NULL) {
					if(isset($results['emgorders']) && count($results['emgorders'])>0) {
						foreach($results['emgorders'] as $result) {
							$adata['emergency'][$i] += 1;
						}
					}
					if(isset($results['ptorders']) && count($results['ptorders'])>0) {
						foreach($results['ptorders'] as $result) {
							$adata['puncture'][$i] += 1;
						}
					}
				}
			}
		} elseif($this->input->post('atype') == 'Monthly') {
			$date = date("Y-m-d", strtotime("-28 day", strtotime("now")));
			for($i = 0; $i <= 3; $i++) {
				$from_date = $date;
				$adata['categories'][$i] = date("d/m", strtotime($date)) . ' - ';
				$date = date("Y-m-d", strtotime("+7 day", strtotime($date)));
				$to_date = $date;
				$adata['categories'][$i] .= date("d/m", strtotime($date));
				$adata['emergency'][$i] = 0;
				$adata['puncture'][$i] = 0;
				$results = $this->getemgptordersgraphByDateRange($from_date, $to_date);
				if($results !== NULL) {
					if(isset($results['emgorders']) && count($results['emgorders'])>0) {
						foreach($results['emgorders'] as $result) {
							$adata['emergency'][$i] += 1;
						}
					}
					if(isset($results['ptorders']) && count($results['ptorders'])>0) {
						foreach($results['ptorders'] as $result) {
							$adata['puncture'][$i] += 1;
						}
					}
				}
			}
		}
		echo json_encode($adata);
	}
	public function getOrderHistoryCSV() {
		$this->initiate_download_headers('orders_' . date('d-m-Y') . '.csv');
		if($_POST) {
			$selectQuery = 'odetails.OId AS OrderId, '; $parameters = $this->input->post('parameters');
			foreach ($parameters as $parameter) {
				$selectQuery .= $parameter . ', ';
			}
			$selectQuery = substr($selectQuery, 0, -2);
			$this->db->select($selectQuery);
			$this->db->from('odetails');
			$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
			$this->db->join('servicecenter', 'servicecenter.ScId = oservicedetail.ScId', 'left');
			$this->db->join('city', 'city.CityId = odetails.CityId', 'left');
			$this->db->join('tieups', 'tieups.TieupId = odetails.TieupId', 'left');
			$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
			$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
			$this->db->join('useraddr', 'useraddr.UserAddrId = odetails.UserAddrId', 'left');
			$this->db->join('location', 'location.LocationId = useraddr.LocationId', 'left');
			$this->db->join('ofupstatus', 'ofupstatus.OId = odetails.OId', 'left');
			$this->db->join('oexfupstatus', 'oexfupstatus.OId = odetails.OId', 'left');
			$this->db->join('coupons', 'coupons.CouponId = odetails.CouponId', 'left');
			$this->db->join('fcoupons', 'fcoupons.FCouponId = odetails.FCouponId', 'left');
			$this->db->where('odetails.ServiceId != ', 3);
			if(intval($this->input->post('admin_followup')) != 0) {
				$this->db->where_in('ofupstatus.FupStatusId', array(intval($this->input->post('admin_followup')), NULL, ''));
			}
			if(intval($this->input->post('executive_followup')) != 0) {
				$this->db->where_in('oexfupstatus.EFupStatusId', array(intval($this->input->post('executive_followup')), NULL, ''));
			}
			if(intval($this->input->post('service_center')) != 0) {
				$this->db->where('servicecenter.ScId', intval($this->input->post('service_center')));
			}
			if(intval($this->input->post('tie_up')) != 0) {
				$this->db->where('tieups.TieupId', intval($this->input->post('tie_up')));
			}
			$this->db->order_by('odetails.ODate', 'desc');
			$this->db->order_by('ofupstatus.Timestamp', 'desc');
			$this->db->order_by('oexfupstatus.Timestamp', 'desc');
			$this->db->group_by('odetails.OId');
			$query = $this->db->get(); $results = $query->result_array();
			foreach($results as &$result) {
				if($result['Time'] > 12) {
					$temp_hr = intval($result['Time'] - 12);
					$temp = (intval($result['Time'] * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$slot_hour = $temp_hr . " : " . $temp . "PM";
				} elseif($result['Time'] == 12) {
					$slot_hour = intval($result['Time']) . " : 00 PM";
				} else {
					$temp = (intval($result['Time'] * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$slot_hour = intval($result['Time']) . " : " . $temp . "AM";
				}
				$result['Time'] = $slot_hour;
			}
			echo $this->array2csv($results);
			die();
		} else {
			redirect(site_url('admin/orders'));
		}
	}
	private function getemgptordersgraph($date) {
		$this->db->select('EmgOrderId, ODate');
		$this->db->from('emgorders');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('emgorders.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('ODate', $date);
		$query = $this->db->get();
		$result = $query->result_array();
		$emgpt['emgorders'] = $result;
		$this->db->select('PtOrderId, ODate');
		$this->db->from('ptorders');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('ptorders.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('ODate', $date);
		$query = $this->db->get();
		$result = $query->result_array();
		$emgpt['ptorders'] = $result;
		return $emgpt;
	}
	private function getemgptordersgraphByDateRange($from, $to) {
		$this->db->select('EmgOrderId, ODate');
		$this->db->from('emgorders');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('emgorders.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('ODate >=', $from);
		$this->db->where('ODate <', $to);
		$query = $this->db->get();
		$result = $query->result_array();
		$emgpt['emgorders'] = $result;
		$this->db->select('PtOrderId, ODate');
		$this->db->from('ptorders');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('ptorders.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('ODate >=', $from);
		$this->db->where('ODate <', $to);
		$query = $this->db->get();
		$result = $query->result_array();
		$emgpt['ptorders'] = $result;
		return $emgpt;
	}
	private function uphone_by_oid($oid) {
		$rec = $this->db->select('user.Phone')->from('odetails')->join('user', 'user.UserId = odetails.UserId')->where('odetails.OId', $oid)->limit(1)->get()->row_array();
		return $rec['Phone'];
	}
	private function exphones_by_oid($oid) {
		$recs = $this->db->select('executive.Phone')->from('odetails')->join('execassigns', 'execassigns.OId = odetails.OId')->join('executive', 'executive.ExecId = execassigns.ExecId')->where('odetails.OId', $oid)->group_by('executive.ExecId')->get()->result_array();
		$execphs = array();
		foreach($recs as $rec) {
			$execphs[] = $rec['Phone'];
		}
		return $execphs;
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
	private function getOrderByDate($date) {
		$this->db->select('odetails.OId, odetails.ODate, odetails.ServiceId');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ODate', $date);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
	private function getOrderByDateRange($from, $to) {
		$this->db->select('odetails.OId, odetails.ODate, odetails.ServiceId');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ODate >=', $from);
		$this->db->where('odetails.ODate <', $to);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
	private function getQueried($date, $status_order = 1) {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ServiceId', 3);
		if($status_order == 0) {
			$this->db->where('status.Order <', 3);
		} elseif($status_order == 3) {
			$this->db->where('status.Order', 3);
		}
		$this->db->where('odetails.ODate', $date);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function getQueriedRange($from, $to, $status_order = 1) {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ServiceId', 3);
		if($status_order == 0) {
			$this->db->where('status.Order <', 3);
		} elseif($status_order == 3) {
			$this->db->where('status.Order', 3);
		}
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
	private function getAllotments($date, $status_order) {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('status.Order', $status_order);
		$this->db->where('odetails.ODate', $date);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function getAllotmentsRange($from, $to, $status_order) {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('status.Order', $status_order);
		$this->db->where('odetails.ODate >=', $from);
		$this->db->where('odetails.ODate <', $to);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function getPdOrdersRange($from, $to) {
		$this->db->select('COUNT(odetails.OId)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ODate >=', $from);
		$this->db->where('odetails.ODate <', $to);
		$this->db->where_in('odetails.ServiceId', array(1, 2));
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(odetails.OId)'];
	}
	private function getPdOrders($date) {
		$this->db->select('COUNT(odetails.OId)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ODate', $date);
		$this->db->where_in('odetails.ServiceId', array(1, 2));
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(odetails.OId)'];
	}
	private function getPickups($date) {
		$this->db->select('COUNT(*) AS PickCount');
		$this->db->from('odetails');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ODate', $date);
		$this->db->where_in('odetails.ServiceId', array(1, 2));
		$this->db->where('odetails.pickup_drop_flag', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['PickCount'];
	}
	private function getPickupsRange($from, $to, $flag = FALSE) {
		$this->db->select('COUNT(*) AS PickCount');
		$this->db->from('odetails');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ODate >=', $from);
		$this->db->where('odetails.ODate <', $to);
		$this->db->where_in('odetails.ServiceId', array(1, 2));
		$this->db->where('pickup_drop_flag', 1);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['PickCount'];
	}
	private function getDrops($date, $flag = FALSE) {
		$this->db->select('COUNT(*) AS PickCount');
		$this->db->from('odetails');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ODate', $date);
		$this->db->where_in('odetails.ServiceId', array(1, 2));
		$this->db->where('odetails.pickup_drop_flag', 2);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['PickCount'];
	}
	private function getDropsRange($from, $to, $flag = FALSE) {
		$this->db->select('COUNT(*) AS PickCount');
		$this->db->from('odetails');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ODate >=', $from);
		$this->db->where('odetails.ODate <', $to);
		$this->db->where_in('odetails.ServiceId', array(1, 2));
		$this->db->where('pickup_drop_flag', 2);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['PickCount'];
	}
	private function getPickupsDrops($date, $flag = FALSE) {
		$this->db->select('COUNT(*) AS PickCount');
		$this->db->from('odetails');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ODate', $date);
		$this->db->where_in('odetails.ServiceId', array(1, 2));
		$this->db->where('odetails.pickup_drop_flag', 3);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['PickCount'];
	}
	private function getPickupsDropsRange($from, $to, $flag = FALSE) {
		$this->db->select('COUNT(*) AS PickCount');
		$this->db->from('odetails');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ODate >=', $from);
		$this->db->where('odetails.ODate <', $to);
		$this->db->where_in('odetails.ServiceId', array(1, 2));
		$this->db->where('pickup_drop_flag', 3);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['PickCount'];
	}
	private function get_tot_orders() {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function get_tot_processed() {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where("((odetails.ServiceId = '1' AND status.Order = '4')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '2' AND status.Order = '4')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '3' AND status.Order = '3')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '4' AND status.Order = '3'))", NULL, FALSE);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function get_tot_queried() {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ServiceId', 3);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function get_tot_delayed() {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('odetails.ODate <', date("Y-m-d", strtotime("now")));
		$this->db->where("((odetails.ServiceId = '1' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '2' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '4' AND status.Order < '3' AND status.Order >= '0'))", NULL, FALSE);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result['COUNT(*)'];
	}
	private function getOrdersForDate($date) {
		$this->db->select('odetails.OId, odetails.ODate, SlotHour, ULatitude, ULongitude, DLatitude, DLongitude, user.UserId, UserAddrId, servicecenter.ScId, user.UserName, servicecenter.ScName');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('servicecenter', 'servicecenter.ScId = oservicedetail.ScId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		$this->db->where("((odetails.ServiceId = '1' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '2' AND status.Order < '4' AND status.Order >= '0'))", NULL, FALSE);
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('odetails.ServiceId !=', 4);
		$this->db->where('odetails.ODate', $date);
		$query = $this->db->get();
		$results = $query->result_array();
		for($i = 8; $i <= 17; $i += 0.5) {
			if ($i > 12) {
				$temp_hr = intval($i - 12);
				$temp = (intval($i * 60) % 60);
				if($temp == 0) {
					$temp = '00';
				}
				$slotHour = $temp_hr . " : " . $temp . " PM";
			} elseif ($i == 12) {
				$slotHour = intval($i) . ":00 PM";
			} else {
				$temp = (intval($i * 60) % 60);
				if($temp == 0) {
					$temp = '00';
				}
				$slotHour = intval($i) . " : " . $temp . " AM";
			}
			$orderPlan[$slotHour] = array();
		}
		foreach ($results as $result) {
			if(!isset($result['ULatitude']) || !isset($result['ULongitude']) || $result['ULatitude'] == NULL || $result['ULongitude'] == NULL) {
				$result['ULatitude'] = $this->getUserLocation($result['UserAddrId'])['Latitude'];
				$result['ULongitude'] = $this->getUserLocation($result['UserAddrId'])['Longitude'];
			}
			if(!isset($result['DLatitude']) || !isset($result['DLongitude']) || $result['DLatitude'] == NULL || $result['DLongitude'] == NULL) {
				$result['DLatitude'] = $this->getSCLocation($result['ScId'])['Latitude'];
				$result['DLongitude'] = $this->getSCLocation($result['ScId'])['Longitude'];
			}
			if ($result['SlotHour'] > 12) {
				$temp_hr = intval($result['SlotHour'] - 12);
				$temp = (intval($result['SlotHour'] * 60) % 60);
				if($temp == 0) {
					$temp = '00';
				}
				$slotHour = $temp_hr . " : " . $temp . " PM";
			} elseif ($result['SlotHour'] == 12) {
				$slotHour = intval($result['SlotHour']) . ":00 PM";
			} else {
				$temp = (intval($result['SlotHour'] * 60) % 60);
				if($temp == 0) {
					$temp = '00';
				}
				$slotHour = intval($result['SlotHour']) . " : " . $temp . " AM";
			}
			$orderPlan[$slotHour][] = $result;
		}
		return $orderPlan;
	}
	private function getUserLocation($UserAddrId) {
		$this->db->select('location.Latitude, location.Longitude');
		$this->db->from('useraddr');
		$this->db->join('location', 'location.LocationId = useraddr.LocationId', 'left');
		$this->db->where('useraddr.UserAddrId', $UserAddrId);
		$this->db->limit(1);
		$query = $this->db->get();
		$results = $query->row_array();
		return $results;
	}
	private function getSCLocation($ScId) {
		$this->db->select('scaddr.Latitude, scaddr.Longitude');
		$this->db->from('scaddr');
		$this->db->join('scaddrsplit', 'scaddrsplit.ScAddrSplitId = scaddr.ScAddrSplitId', 'left');
		$this->db->where('scaddrsplit.ScId', $ScId);
		$this->db->limit(1);
		$query = $this->db->get();
		$results = $query->row_array();
		return $results;
	}
	private function getLastOrderPricing($bikeModel, $serviceCenter) {
		$this->db->select('odetails.OId'); $this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->where('odetails.BikeModelId', $bikeModel);
		$this->db->where('oservicedetail.ScId', $serviceCenter);
		$this->db->limit(1); $query = $this->db->get(); $results = $query->result_array();
		if(count($results) > 0) {
			$OId = $results[0]["OId"]; $this->data['OId'] = $OId;
			$this->load->model('amenity_m'); $this->load->model('odetails_m');
			$this->load->model('statushistory_m'); $this->load->model('opaymtdetail_m');
			$this->data['estprices'] = $this->amenity_m->get_est_prices_by_oid($OId);
			$this->data['discprices'] = $this->amenity_m->get_est_prices_by_oid($OId, TRUE);
			$this->data['is_amt_cfmd'] = $this->odetails_m->is_amt_confirmed($OId);
			$this->data['oprices'] = $this->statushistory_m->get_oprices($OId);
			$this->data['stathists'] = $this->statushistory_m->get_status_history($OId, FALSE, $serviceCenter);
			$this->data['ord_trans'] = $this->opaymtdetail_m->get_order_transactions($OId);
			$this->data['tot_paid'] = floatval($this->opaymtdetail_m->get_total_paid_amount($OId));
			$this->data['tot_billed'] = floatval($this->data['estprices'][count($this->data['estprices']) - 1]['ptotal']) + floatval($this->data['oprices'][count($this->data['oprices']) - 1]['ptotal']) - floatval($this->data['discprices'][count($this->data['discprices']) - 1]['ptotal']);
			$this->data['to_be_paid'] = round(floatval($this->data['tot_billed'] - $this->data['tot_paid']), 2);
			if($this->data['to_be_paid'] < 0.01 && $this->data['to_be_paid'] > -0.01) {
				$this->data['to_be_paid'] = 0;
			}
		} else {
			$this->data['nothingToShow'] = "No Data Available. Please try another service center / bike model / spare part";
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
	private function get_total_billed_amount($oid) {
		$this->load->model('amenity_m');
		$this->load->model('statushistory_m');
		$estprices = $this->amenity_m->get_est_prices_by_oid($oid);
		$oprices = $this->statushistory_m->get_oprices($oid);
		$tot_billed = round(floatval($estprices[count($estprices) - 1]['ptotal']) + floatval($oprices[count($oprices) - 1]['ptotal']), 2);
		return $tot_billed;
	}
	private function get_total_conv_amount($oid) {
		$this->load->model('amenity_m');
		$conv_fee = $this->amenity_m->get_est_prices_by_oid($oid, FALSE, TRUE);
		$tot_conv = round(floatval($conv_fee[count($conv_fee) - 1]['ptotal']), 2);
		return $tot_conv;
	}
	private function get_total_paid_amount($oid) {
		$this->load->model('opaymtdetail_m');
		$tot_paid = round(floatval($this->opaymtdetail_m->get_total_paid_amount($oid)), 2);
		return $tot_paid;
	}
	private function get_total_discount_amount($oid) {
		$this->load->model('amenity_m');
		$discprices = $this->amenity_m->get_est_prices_by_oid($oid, TRUE);
		return round(floatval($discprices[count($discprices) - 1]['ptotal']), 2);
	}
	private function get_abndedorders() {
		$this->db->select('dropped_order_id, LocationName, BikeModelName, BikeCompanyName, Date, Phone, ScName, ServiceName');
		$this->db->from('dropped_orders');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = dropped_orders.BikeModelId');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = dropped_orders.BikeCompanyId');
		$this->db->join('servicecenter', 'servicecenter.ScId = dropped_orders.ScId');
		$this->db->join('service', 'service.ServiceId = dropped_orders.ScId');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('dropped_orders.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('dropped_orders.isVisible', 1);
		$this->db->order_by('Date', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach($results as &$result) {
				$result['Date'] = date('d-m-Y', strtotime($result['Date']));
			}
			return $results;
		}
	}
	private function is_order_cancelled($oid) {
		$this->db->select('status.Order');
		$this->db->from('oservicedetail');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId');
		$this->db->where('oservicedetail.OId', $oid);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return FALSE;
		} else {
			if(intval($result[0]['Order']) == -2) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}
	private function get_execs_assigned($oid) {
		$this->db->select('executive.ExecId, executive.GCMId, executive.ExecName, executive.Phone, executive.Email, executive.DOB, DATE_FORMAT(CONVERT_TZ(execassigns.Timestamp,"+00:00","+05:30"), "%D %M %Y %h:%i %p") AS Timestamp');
		$this->db->from('execassigns');
		$this->db->join('executive', 'executive.ExecId = execassigns.ExecId', 'left');
		$this->db->where('execassigns.OId', $oid);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	private function populate_odetails($OId) {
		$this->load->model('odetails_m');
		$this->load->model('status_m');
		$this->load->model('statushistory_m');
		$this->load->model('fupstatus_m');
		$this->load->model('executive_m');
		$this->data['OId'] = $OId;
		$execassigns = $this->get_execs_assigned($OId);
		if($execassigns && count($execassigns) > 0) { $this->data['execassigns'] = $execassigns; }
		$this->data['nps'] = $this->get_nps_rating_by_oid($OId);
		$this->data['user_feedback_rating'] = $this->get_user_feedback_rating_by_oid($OId);
		$ODate = $this->odetails_m->get_odate_by_oid($OId);
		$this->data['execs'] = $this->executive_m->get_active_executives($ODate);
		$this->data['ex_rtime_updates'] = $this->executive_m->get_ex_fup_rtime_supdates_web($OId, 1);
		$this->data['ex_fup_updates'] = $this->executive_m->get_ex_fup_rtime_supdates_web($OId);
		$this->data['ex_pre_servicing_updates'] = $this->executive_m->get_ex_ps_updates_web($OId);
		$this->data['fup_statuses'] = $this->fupstatus_m->get_by(array('isEnabled' => 1));
		$this->data['fupstathistory'] = $this->fupstatus_m->get_fupstat_history($OId);
		$odetail_row = $this->odetails_m->get_by(array('OId' => $OId), TRUE);
		$bike_model_details = $this->odetails_m->get_bm_by_oid($OId);
		$this->data['ScId'] = $this->odetails_m->get_scid_by_oid($OId);
		$this->data['ODate'] = $odetail_row->ODate;
		$this->data['CityId'] = $odetail_row->CityId;
		$this->data['isGrievance'] = $odetail_row->isGrievance;
		$this->data["UserId"] = $odetail_row->UserId;
		$this->data["UserAddrId"] = $odetail_row->UserAddrId;
		$this->data["HappayTId"] = $odetail_row->HappayTId;
		$this->data["HappayAmount"] = $odetail_row->HappayAmount;
		$this->data['pickup_drop_flag'] = $odetail_row->pickup_drop_flag;
		$this->data['puc_renewal_date'] = $odetail_row->puc_renewal_date;
		$this->data['service_reminder_date'] = $odetail_row->service_reminder_date;
		$this->data['insurance_renewal_date'] = $odetail_row->insurance_renewal_date;
		$this->data['is_breakdown_flag'] = intval($odetail_row->isBreakdown);
		$this->data['transport_mode'] = $odetail_row->transportMode;
		$renewal_dates = $this->odetails_m->get_renewal_dates($bike_model_details['BikeNumber'], $OId);
		if($renewal_dates && $renewal_dates |= NULL) {
			if($renewal_dates['puc_renewal_date'] && $renewal_dates['puc_renewal_date'] != NULL && $this->data['puc_renewal_date'] == NULL) {
				$this->data['puc_renewal_date'] = $renewal_dates['puc_renewal_date'];
			}
			if($renewal_dates['service_reminder_date'] && $renewal_dates['service_reminder_date'] != NULL && $this->data['service_reminder_date'] == NULL) {
				$this->data['service_reminder_date'] = $renewal_dates['service_reminder_date'];
			}
			if($renewal_dates['insurance_renewal_date'] && $renewal_dates['insurance_renewal_date'] != NULL && $this->data['insurance_renewal_date'] == NULL) {
				$this->data['insurance_renewal_date'] = $renewal_dates['insurance_renewal_date'];
			}
		}
		$this->data['isinvoicesent'] = intval($odetail_row->InvoiceSent);
		$this->data['u_lati'] = $odetail_row->ULatitude;
		$this->data['u_longi'] = $odetail_row->ULongitude;
		$this->data['omedia'] = $this->odetails_m->get_order_media($OId);
		$service_details = $this->odetails_m->get_stype_by_oid($OId);
		$this->data['stype'] = $service_details['ServiceName'];
		$this->data['serid'] = intval($service_details['ServiceId']);
		$sc_details = $this->odetails_m->get_scenter_by_oid($OId);
		$this->data['scenter'] = $sc_details;
		$this->data['statuses'] = $this->status_m->get_statuses_for_service($service_details['ServiceId']);
		foreach($sc_details as $sc_detail) {
			$this->data['rest_statuses'][] = $this->status_m->get_statuses_for_service($service_details['ServiceId'], intval($sc_detail['Order']));
		}
		$this->data['bikenumber'] = $bike_model_details['BikeNumber'];
		$this->data['bikemodel'] = $bike_model_details['BikeCompanyName'] . ' ' . $bike_model_details['BikeModelName'];
		$this->data['timeslot'] = $this->odetails_m->get_timeslot_by_oid($OId);
		$this->data['timestamp'] = $this->odetails_m->get_timestamp_by_oid($OId);
		$this->data['paymode'] = $this->odetails_m->get_paymode_by_oid($OId);
		$user_details = $this->odetails_m->get_user_address($OId);
		if(isset($user_details['address']) && $user_details['address'] != '') {
			$this->data['uaddress'] = $user_details['address'];
			$this->data['AddrLine1'] = $user_details['AddrLine1'];
			$this->data['AddrLine2'] = $user_details['AddrLine2'];
			$this->data['LocationName'] = $user_details['LocationName'];
			$this->data['Landmark'] = $user_details['Landmark'];
			$this->data['Latitude'] = $user_details['Latitude'];
			$this->data['Longitude'] = $user_details['Longitude'];
			$this->data['uname'] = $user_details['name'];
			$this->data['uemail'] = $user_details['email'];
			$this->data['uphone'] = $user_details['Phone'];
		} else {
			$this->data['uaddress'] = NULL;
			$this->data['AddrLine1'] = NULL;
			$this->data['AddrLine2'] = NULL;
			$this->data['LocationName'] = NULL;
			$this->data['Landmark'] = NULL;
			$this->data['Latitude'] = NULL;
			$this->data['Longitude'] = NULL;
			$this->data['uname'] = NULL;
			$this->data['uemail'] = NULL;
			$this->data['uphone'] = NULL;
		}
		$this->load->model('tieups_m');
		$this->data['tieup'] = $this->tieups_m->get_business_by_oid($OId);
		$this->data['estimations'] = $this->get_jc_estimations_by_oid($OId);
		if ($this->data['serid'] == 4) {
			$this->data['insren_details'] = $this->odetails_m->get_insren_details($OId);
		}
		if ($this->data['serid'] != 3) {
			$this->load->model('amenity_m');
			$this->load->model('opaymtdetail_m');
			$this->data['scaddress'] = $this->odetails_m->get_sc_address($sc_details[0]['ScId']);
			$this->data['estprices'] = $this->amenity_m->get_est_prices_by_oid($OId);
			$this->data['discprices'] = $this->amenity_m->get_est_prices_by_oid($OId, TRUE);
			$this->data['is_amt_cfmd'] = $this->odetails_m->is_amt_confirmed($OId);
			$this->data['oprices'] = $this->statushistory_m->get_oprices($OId);
			$this->data['stathists'] = $this->statushistory_m->get_status_history($OId, FALSE, $sc_details[0]['ScId']);
			$this->data['ord_trans'] = $this->opaymtdetail_m->get_order_transactions($OId);
			$this->data['tot_paid'] = floatval($this->opaymtdetail_m->get_total_paid_amount($OId));
			$this->data['tot_billed'] = floatval($this->data['estprices'][count($this->data['estprices']) - 1]['ptotal']) + floatval($this->data['oprices'][count($this->data['oprices']) - 1]['ptotal']) - floatval($this->data['discprices'][count($this->data['discprices']) - 1]['ptotal']);
			$this->data['to_be_paid'] = round(floatval($this->data['tot_billed'] - $this->data['tot_paid']), 2);
			if($this->data['to_be_paid'] < 0.01 && $this->data['to_be_paid'] > -0.01) {
				$this->data['to_be_paid'] = 0;
			}
		} else {
			$this->data['stathists'] = $this->statushistory_m->get_status_history($OId, TRUE, NULL);
		}
	}
	private function get_jc_estimations_by_oid($OId) {
		$this->db->select('EstTime, EstPrice');
		$this->db->from('oexchkupstatus');
		$this->db->where('oexchkupstatus.OId', $OId);
		$this->db->order_by('oexchkupstatus.Timestamp', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			$result = $results[0];
			$datetime = $result['EstTime'];
			$datetime = explode(" ", $datetime);
			$est['EstDate'] = $datetime[0];
			$est['EstTime'] = $datetime[1];
			$est['EstTime'] = date('h:i A', strtotime($est['EstTime']));
			$est['EstPrice'] = $result['EstPrice'];
			return $est;
		}
	}
	private function get_upcoming_orders() {
		$this->db->select('odetails.OId, BikeModelName, BikeCompanyName, ODate, user.Phone, service.ServiceId, UserName, SlotHour, ServiceName, fupstatus.FupStatusName, executive.ExecName, status.Order');
		$this->db->from('odetails');
		$this->db->join('execassigns', 'execassigns.OId = odetails.OId', 'left');
		$this->db->join('executive', 'executive.ExecId = execassigns.ExecId', 'left');
		$this->db->join('fupstatus', 'fupstatus.FupStatusId = odetails.LastFupStatusId', 'left');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId', 'left');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('service.ServiceId !=', 3);
		$this->db->where('odetails.ODate <=', date("Y-m-d", strtotime("now")));
		$this->db->where("((status.ServiceId = '1' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '2' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '4' AND status.Order < '3' AND status.Order >= '0'))", NULL, FALSE);
		$this->db->order_by('odetails.ODate', 'asc');
		$this->db->order_by('odetails.OId', 'asc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$count = 0;
			$this->data['ps_count'] = 0;
			$this->data['r_count'] = 0;
			$this->data['ir_count'] = 0;
			$this->data['de_count'] = 0;
			$prev_oid = '';
			foreach($result as $row) {
				if($row['OId'] != $prev_oid) {
					$prev_oid = $row['OId'];
					$date = $row['ODate'];
					if (strtotime($date) < strtotime(date("Y-m-d", time()))) {
						$this->data['de_count'] += 1;
					}
					$result_rows[$count]['odate'] = date("F d, Y - l", strtotime($date));
					if ($row['SlotHour'] > 12) {
						$temp_hr = intval($row['SlotHour'] - 12);
						$temp = (intval($row['SlotHour'] * 60) % 60);
						if($temp == 0) {
							$temp = '00';
						}
						$result_rows[$count]['odate'] .= ' - ' . $temp_hr . ":" . $temp . "&nbsp;PM";
					} elseif ($row['SlotHour'] == 12) {
						$result_rows[$count]['odate'] .= ' - ' . intval($row['SlotHour']) . ":00&nbsp;PM";
					} else {
						$temp = (intval($row['SlotHour'] * 60) % 60);
						if($temp == 0) {
							$temp = '00';
						}
						$result_rows[$count]['odate'] .= ' - ' . intval($row['SlotHour']) . ":" . $temp . "&nbsp;AM";
					}
					$result_rows[$count]['oid'] = $row['OId'];
					$result_rows[$count]['bmodel'] = convert_to_camel_case($row['BikeCompanyName'] . ' ' . $row['BikeModelName']);
					$result_rows[$count]['otype'] = $row['ServiceName'];
					$result_rows[$count]['phone'] = $row['Phone'];
					$result_rows[$count]['username'] = convert_to_camel_case($row['UserName']);
					$result_rows[$count]['fupsname'] = $row['FupStatusName'];
					$result_rows[$count]['sorder'] = $row['Order'];
					if($row['ServiceId'] == 1) {
						$this->data['ps_count'] += 1;
					} elseif($row['ServiceId'] == 2) {
						$this->data['r_count'] += 1;
					} elseif ($row['ServiceId'] == 4) {
						$this->data['ir_count'] += 1;
					}
					$result_rows[$count]['execnames'] = $row['ExecName'];
					$count++;
				} else {
					$result_rows[$count - 1]['execnames'] .= ', ' . $row['ExecName'];
				}
			}
			return $result_rows;
		}
	}
	private function get_grievance_orders() {
		$this->db->select('odetails.OId, BikeModelName, BikeCompanyName, ODate, user.Phone, service.ServiceId, UserName, SlotHour, ServiceName, fupstatus.FupStatusName, executive.ExecName, status.Order');
		$this->db->from('odetails');
		$this->db->join('execassigns', 'execassigns.OId = odetails.OId', 'left');
		$this->db->join('executive', 'executive.ExecId = execassigns.ExecId', 'left');
		$this->db->join('fupstatus', 'fupstatus.FupStatusId = odetails.LastFupStatusId', 'left');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId', 'left');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('service.ServiceId !=', 3);
		$this->db->where('FinalFlag', 0);
		$this->db->where('isGrievance', 1);
		$this->db->where('status.Order >=', 0);
		$this->db->order_by('odetails.ODate', 'asc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$count = 0;
			$this->data['ps_count'] = 0;
			$this->data['r_count'] = 0;
			$this->data['ir_count'] = 0;
			$this->data['de_count'] = 0;
			$prev_oid = '';
			foreach($result as $row) {
				if($row['OId'] != $prev_oid) {
					$prev_oid = $row['OId'];
					$date = $row['ODate'];
					if (strtotime($date) < strtotime(date("Y-m-d", time()))) {
						$this->data['de_count'] += 1;
					}
					$result_rows[$count]['odate'] = date("F d, Y - l", strtotime($date));
					if ($row['SlotHour'] > 12) {
						$temp_hr = intval($row['SlotHour'] - 12);
						$temp = (intval($row['SlotHour'] * 60) % 60);
						if($temp == 0) {
							$temp = '00';
						}
						$result_rows[$count]['odate'] .= ' - ' . $temp_hr . ":" . $temp . "&nbsp;PM";
					} elseif ($row['SlotHour'] == 12) {
						$result_rows[$count]['odate'] .= ' - ' . intval($row['SlotHour']) . ":00&nbsp;PM";
					} else {
						$temp = (intval($row['SlotHour'] * 60) % 60);
						if($temp == 0) {
							$temp = '00';
						}
						$result_rows[$count]['odate'] .= ' - ' . intval($row['SlotHour']) . ":" . $temp . "&nbsp;AM";
					}
					$result_rows[$count]['oid'] = $row['OId'];
					$result_rows[$count]['bmodel'] = convert_to_camel_case($row['BikeCompanyName'] . ' ' . $row['BikeModelName']);
					$result_rows[$count]['otype'] = $row['ServiceName'];
					$result_rows[$count]['phone'] = $row['Phone'];
					$result_rows[$count]['username'] = convert_to_camel_case($row['UserName']);
					$result_rows[$count]['fupsname'] = $row['FupStatusName'];
					$result_rows[$count]['sorder'] = $row['Order'];
					if($row['ServiceId'] == 1) {
						$this->data['ps_count'] += 1;
					} elseif($row['ServiceId'] == 2) {
						$this->data['r_count'] += 1;
					} elseif ($row['ServiceId'] == 4) {
						$this->data['ir_count'] += 1;
					}
					$result_rows[$count]['execnames'] = $row['ExecName'];
					$count++;
				} else {
					$result_rows[$count - 1]['execnames'] .= ', ' . $row['ExecName'];
				}
			}
			return $result_rows;
		}
	}
	private function get_unallotted_orders() {
		$this->db->select('odetails.OId, BikeModelName, BikeCompanyName, ODate, Phone, service.ServiceId, UserName, SlotHour, ServiceName');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId', 'left');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('status.Order', 0);
		$this->db->where('service.ServiceId !=', 3);
		$this->db->order_by('odetails.ODate', 'asc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$count = 0;
			$this->data['ps_count'] = 0;
			$this->data['r_count'] = 0;
			$this->data['ir_count'] = 0;
			$this->data['de_count'] = 0;
			foreach($result as $row) {
				$date = $row['ODate'];
				if ((strtotime($date) - (24 * 60 * 60)) <= strtotime(date("Y-m-d", time()))) {
					$this->data['de_count'] += 1;
				}
				$result_rows[$count]['odate'] = date("F d, Y - l", strtotime($date));
				if ($row['SlotHour'] > 12) {
					$temp_hr = intval($row['SlotHour'] - 12);
					$temp = (intval($row['SlotHour'] * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$result_rows[$count]['odate'] .= ' - ' . $temp_hr . ":" . $temp . "&nbsp;PM";
				} elseif ($row['SlotHour'] == 12) {
					$result_rows[$count]['odate'] .= ' - ' . intval($row['SlotHour']) . ":00&nbsp;PM";
				} else {
					$temp = (intval($row['SlotHour'] * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$result_rows[$count]['odate'] .= ' - ' . intval($row['SlotHour']) . ":" . $temp . "&nbsp;AM";
				}
				$result_rows[$count]['oid'] = $row['OId'];
				$result_rows[$count]['bmodel'] = convert_to_camel_case($row['BikeCompanyName'] . ' ' . $row['BikeModelName']);
				$result_rows[$count]['otype'] = $row['ServiceName'];
				$result_rows[$count]['phone'] = $row['Phone'];
				$result_rows[$count]['username'] = convert_to_camel_case($row['UserName']);
				if($row['ServiceId'] == 1) {
					$this->data['ps_count'] += 1;
				} elseif($row['ServiceId'] == 2) {
					$this->data['r_count'] += 1;
				} elseif ($row['ServiceId'] == 4) {
					$this->data['ir_count'] += 1;
				}
				$count += 1;
			}
			return $result_rows;
		}
	}
	private function get_serviced_orders() {
		$this->db->select('odetails.OId, BikeModelName, BikeCompanyName, ODate, Phone, service.ServiceId, UserName, SlotHour, ServiceName');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId', 'left');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where("(odetails.FinalFlag = '0'", NULL, FALSE);
		$this->db->or_where("odetails.InvoiceUpdated = '0')", NULL, FALSE);
		$this->db->where("(oservicedetail.StatusId = '7'", NULL, FALSE);
		$this->db->or_where("oservicedetail.StatusId = '14')", NULL, FALSE);
		$this->db->order_by('odetails.ODate', 'asc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$count = 0;
			$this->data['d_count'] = 0;
			foreach($result as $row) {
				$date = $row['ODate'];
				if (strtotime($date) < strtotime(date("Y-m-d", time()))) {
					$this->data['d_count'] += 1;
				}
				$result_rows[$count]['odate'] = date("F d, Y - l", strtotime($date));
				if ($row['SlotHour'] > 12) {
					$temp_hr = intval($row['SlotHour'] - 12);
					$temp = (intval($row['SlotHour'] * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$result_rows[$count]['odate'] .= ' - ' . $temp_hr . ":" . $temp . "&nbsp;PM";
				} elseif ($row['SlotHour'] == 12) {
					$result_rows[$count]['odate'] .= ' - ' . intval($row['SlotHour']) . ":00&nbsp;PM";
				} else {
					$temp = (intval($row['SlotHour'] * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$result_rows[$count]['odate'] .= ' - ' . intval($row['SlotHour']) . ":" . $temp . "&nbsp;AM";
				}
				$result_rows[$count]['oid'] = $row['OId'];
				$result_rows[$count]['bmodel'] = convert_to_camel_case($row['BikeCompanyName'] . ' ' . $row['BikeModelName']);
				$result_rows[$count]['otype'] = $row['ServiceName'];
				$result_rows[$count]['phone'] = $row['Phone'];
				$result_rows[$count]['username'] = convert_to_camel_case($row['UserName']);
				$count += 1;
			}
			$this->data['s_count'] = $count;
			return $result_rows;
		}
	}
	private function get_queried_orders() {
		$this->db->select('odetails.OId, odetails.TimeStamp, BikeModelName, status.Order, oservicedetail.ServiceDesc1, oservicedetail.ServiceDesc2, BikeCompanyName, ODate, Phone, service.ServiceId, UserName, SlotHour, ServiceName');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId', 'left');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('service.ServiceId =', 3);
		$this->db->order_by('odetails.ODate', 'asc');
		$this->db->group_by("odetails.OId");
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$count = 0;
			$this->data['n_query'] = 0;
			$this->data['d_query'] = 0;
			$this->data['a_query'] = 0;
			foreach($result as $row) {
				$result_rows[$count]['oid'] = $row['OId'];
				$result_rows[$count]['bmodel'] = convert_to_camel_case($row['BikeCompanyName'] . ' ' . $row['BikeModelName']);
				$result_rows[$count]['phone'] = $row['Phone'];
				$result_rows[$count]['username'] = convert_to_camel_case($row['UserName']);
				$result_rows[$count]['TimeStamp'] = date('D M d Y, h:i A', strtotime($row['TimeStamp']));
				$result_rows[$count]['query_desc'] = convert_to_camel_case($row['ServiceDesc1']) . ' - ' . convert_to_camel_case($row['ServiceDesc2']);
				if ($row['Order'] < 3 && (strtotime($row['ODate']) + (24 * 60 * 60)) >= strtotime(date("Y-m-d", time()))) {
					$this->data['n_query'] += 1; $result_rows[$count]['status'] = 0;
				}
				if ($row['Order'] < 3 && ((strtotime($row['ODate']) + (2 * 24 * 60 * 60)) <= strtotime(date("Y-m-d", time())))) {
					$this->data['d_query'] += 1; $result_rows[$count]['status'] = 0;
				}
				if($row['Order'] == 3) {
					$this->data['a_query'] += 1; $result_rows[$count]['status'] = 1;
				}
				$count += 1;
			}
			return $result_rows;
		}
	}
	private function get_archived_orders() {
		$this->db->select('odetails.OId, BikeModelName, BikeCompanyName, ODate, user.Phone, service.ServiceId, UserName, SlotHour, ServiceName, executive.ExecName');
		$this->db->from('odetails');
		$this->db->join('execassigns', 'execassigns.OId = odetails.OId', 'left');
		$this->db->join('executive', 'executive.ExecId = execassigns.ExecId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId', 'left');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('service.ServiceId !=', 3);
		$this->db->where("(odetails.FinalFlag = '1' OR status.Order = '-2' OR status.ServiceId = '4')", NULL, FALSE);
		$this->db->where("((status.ServiceId = '1' AND (status.Order = '4' OR status.Order = '-2'))", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '2' AND (status.Order = '4' OR status.Order = '-2'))", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '4' AND (status.Order = '3' OR status.Order = '-2')))", NULL, FALSE);
		$this->db->order_by('odetails.ODate', 'desc');
		$this->db->order_by('odetails.OId', 'asc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$count = 0;
			$prev_oid = '';
			foreach($result as $row) {
				if($row['OId'] != $prev_oid) {
					$prev_oid = $row['OId'];
					$date = $row['ODate'];
					$result_rows[$count]['odate'] = date("F d, Y - l", strtotime($date));
					if ($row['SlotHour'] > 12) {
						$temp_hr = intval($row['SlotHour'] - 12);
						$temp = (intval($row['SlotHour'] * 60) % 60);
						if($temp == 0) {
							$temp = '00';
						}
						$result_rows[$count]['odate'] .= ' - ' . $temp_hr . ":" . $temp . "&nbsp;PM";
					} elseif ($row['SlotHour'] == 12) {
						$result_rows[$count]['odate'] .= ' - ' . intval($row['SlotHour']) . ":00&nbsp;PM";
					} else {
						$temp = (intval($row['SlotHour'] * 60) % 60);
						if($temp == 0) {
							$temp = '00';
						}
						$result_rows[$count]['odate'] .= ' - ' . intval($row['SlotHour']) . ":" . $temp . "&nbsp;AM";
					}
					$result_rows[$count]['oid'] = $row['OId'];
					$result_rows[$count]['bmodel'] = convert_to_camel_case($row['BikeCompanyName'] . ' ' . $row['BikeModelName']);
					$result_rows[$count]['otype'] = $row['ServiceName'];
					$result_rows[$count]['phone'] = $row['Phone'];
					$result_rows[$count]['username'] = convert_to_camel_case($row['UserName']);
					$result_rows[$count]['execname'] = convert_to_camel_case($row['ExecName']);
					$count++;
				} else {
					$result_rows[$count - 1]['execname'] .= ', ' . convert_to_camel_case($row['ExecName']);
				}
			}
			return $result_rows;
		}
	}
	private function get_min_upc_allotted_orders() {
		$this->db->select('odetails.OId, BikeModelName, BikeCompanyName, UserName');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId', 'left');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where("(odetails.CityId = '" . $this->session->userdata('a_city_id') . "')", NULL, FALSE);
		}
		$this->db->where("((service.ServiceId != '3')", NULL, FALSE);
		$this->db->where("((odetails.ODate <= '" . date("Y-m-d", strtotime("now")) . "')", NULL, FALSE);
		$this->db->where("((status.ServiceId = '1' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '2' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '4' AND status.Order < '3' AND status.Order >= '0')))", NULL, FALSE);
		$this->db->or_where("(status.Order = '1'))", NULL, FALSE);
		$this->db->order_by('odetails.ODate', 'asc');
		$this->db->group_by('odetails.OId');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$count = 0;
			foreach($result as $row) {
				$result_rows[$count]['oid'] = $row['OId'];
				$result_rows[$count]['bmodel'] = convert_to_camel_case($row['BikeCompanyName'] . ' ' . $row['BikeModelName']);
				$result_rows[$count]['username'] = convert_to_camel_case($row['UserName']);
				$count++;
			}
			return $result_rows;
		}
	}
	private function get_allotted_orders() {
		$this->db->select('odetails.OId, BikeModelName, BikeCompanyName, ODate, user.Phone, service.ServiceId, UserName, SlotHour, ServiceName, fupstatus.FupStatusName, executive.ExecName');
		$this->db->from('odetails');
		$this->db->join('execassigns', 'execassigns.OId = odetails.OId', 'left');
		$this->db->join('executive', 'executive.ExecId = execassigns.ExecId', 'left');
		$this->db->join('fupstatus', 'fupstatus.FupStatusId = odetails.LastFupStatusId', 'left');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId', 'left');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('status.Order', 1);
		$this->db->where('service.ServiceId !=', 3);
		$this->db->order_by('odetails.ODate', 'asc');
		$this->db->order_by('odetails.OId', 'asc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$count = 0;
			$this->data['ps_count'] = 0;
			$this->data['r_count'] = 0;
			$this->data['ir_count'] = 0;
			$prev_oid = '';
			foreach($result as $row) {
				if($row['OId'] != $prev_oid) {
					$prev_oid = $row['OId'];
					$date = $row['ODate'];
					$result_rows[$count]['odate'] = date("F d, Y - l", strtotime($date));
					if ($row['SlotHour'] > 12) {
						$temp_hr = intval($row['SlotHour'] - 12);
						$temp = (intval($row['SlotHour'] * 60) % 60);
						if($temp == 0) {
							$temp = '00';
						}
						$result_rows[$count]['odate'] .= ' - ' . $temp_hr . ":" . $temp . "&nbsp;PM";
					} elseif ($row['SlotHour'] == 12) {
						$result_rows[$count]['odate'] .= ' - ' . intval($row['SlotHour']) . ":00&nbsp;PM";
					} else {
						$temp = (intval($row['SlotHour'] * 60) % 60);
						if($temp == 0) {
							$temp = '00';
						}
						$result_rows[$count]['odate'] .= ' - ' . intval($row['SlotHour']) . ":" . $temp . "&nbsp;AM";
					}
					$result_rows[$count]['oid'] = $row['OId'];
					$result_rows[$count]['bmodel'] = convert_to_camel_case($row['BikeCompanyName'] . ' ' . $row['BikeModelName']);
					$result_rows[$count]['otype'] = $row['ServiceName'];
					$result_rows[$count]['phone'] = $row['Phone'];
					$result_rows[$count]['username'] = convert_to_camel_case($row['UserName']);
					if($row['ServiceId'] == 1) {
						$this->data['ps_count'] += 1;
					} elseif($row['ServiceId'] == 2) {
						$this->data['r_count'] += 1;
					} elseif ($row['ServiceId'] == 4) {
						$this->data['ir_count'] += 1;
					}
					$result_rows[$count]['fupsname'] = $row['FupStatusName'];
					$result_rows[$count]['execnames'] = $row['ExecName'];
					$count++;
				} else {
					$result_rows[$count - 1]['execnames'] .= ', ' . $row['ExecName'];
				}
			}
			return $result_rows;
		}
	}
	private function get_feedback_reminders() {
		$this->db->select('odetails.OId, BikeModelName, BikeCompanyName, ODate, user.Phone, service.ServiceId, UserName, SlotHour, ServiceName, fupstatus.FupStatusName');
		$this->db->from('odetails');
		$this->db->join('fupstatus', 'fupstatus.FupStatusId = odetails.LastFupStatusId', 'left');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId');
		$this->db->join('ofupstatus', 'ofupstatus.OId = odetails.OId', 'left');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId');
		$this->db->join('user', 'user.UserId = odetails.UserId');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.FeedbackCallReminderFlag', 0);
		$this->db->where('odetails.FinalFlag', 1);
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('ofupstatus.FupStatusId', 17);
		$this->db->where('DATE(ofupstatus.Timestamp) <=', date("Y-m-d", strtotime("now - 2 days")));
		$this->db->group_by('odetails.OId');
		$this->db->order_by('ofupstatus.Timestamp', 'desc');
		$this->db->order_by('odetails.ODate', 'asc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$count = 0;
			foreach($result as $row) {
				$result_rows[$count]['odate'] = date("F d, Y - l", strtotime($row['ODate']));
				if ($row['SlotHour'] > 12) {
					$temp_hr = intval($row['SlotHour'] - 12);
					$temp = (intval($row['SlotHour'] * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$result_rows[$count]['odate'] .= ' - ' . $temp_hr . ":" . $temp . "&nbsp;PM";
				} elseif ($row['SlotHour'] == 12) {
					$result_rows[$count]['odate'] .= ' - ' . intval($row['SlotHour']) . ":00&nbsp;PM";
				} else {
					$temp = (intval($row['SlotHour'] * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$result_rows[$count]['odate'] .= ' - ' . intval($row['SlotHour']) . ":" . $temp . "&nbsp;AM";
				}
				$result_rows[$count]['oid'] = $row['OId'];
				$result_rows[$count]['bmodel'] = convert_to_camel_case($row['BikeCompanyName'] . ' ' . $row['BikeModelName']);
				$result_rows[$count]['otype'] = $row['ServiceName'];
				$result_rows[$count]['phone'] = $row['Phone'];
				$result_rows[$count]['username'] = convert_to_camel_case($row['UserName']);
				$result_rows[$count]['fupsname'] = $row['FupStatusName'];
				$count++;
			}
			return $result_rows;
		}
	}
	private function get_jc_form_data($OId) {
		$this->db->select('*');
		$this->db->from('jobcarddetails');
		$this->db->where('OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		if(!$result) {
			return NULL;
		} else {
			$this->data['cr_bikecolor'] = $result['BikeColor'];
			$this->data['cr_kms'] = $result['BikeKms'];
			$this->data['cs_fuelrange'] = $result['FuelRange'];
			$this->data['us_comments'] = $result['UserComments'];
			$this->data['JcKms'] = $result['JcKms'];
			$this->data['JcNum'] = $result['JcNum'];
		}
	}
	private function get_user_id_by_usraddrid($UserAddrId) {
		$this->db->select('user.UserId');
		$this->db->from('user');
		$this->db->join('useraddr', 'useraddr.UserId = user.UserId');
		$this->db->where('useraddr.UserAddrId', $UserAddrId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		if ($result) {
			return $result['UserId'];
		} else {
			return NULL;
		}
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
				$html .= '<li class="col-xs-6 col-lg-4 margin-bottom-10px margin-top-10px padding-right-0"><div class="slot-box"><label class="slot-label"><input type="radio" name="user_slot" class="slot_radio_event" value="' . $slot['Hour'] . '">&nbsp;&nbsp;' . $slot_hour . '&nbsp;</label></div></li>';
			} else {
				if ($slot['Slots'] <= 0) {
					$html .= '<li class="col-xs-6 col-lg-4 margin-bottom-10px margin-top-10px padding-right-0"><div class="slot-box"><label class="slot-label"><input type="radio" name="user_slot" class="slot_radio_event" value="' . $slot['Hour'] . '" disabled="disabled"><span class="margin-left-5px">' . $slot_hour . ' - <b>Not Available</b></label></div></li>';
				} else {
					$html .= '<li class="col-xs-6 col-lg-4 margin-bottom-10px margin-top-10px padding-right-0"><div class="slot-box"><label class="slot-label"><input type="radio" name="user_slot" class="slot_radio_event" value="' . $slot['Hour'] . '"><span class="margin-left-5px">' . $slot_hour . ' : <b>' . $slot['Slots'] . '</b> Slots</span></label></div></li>';
				}
			}
		}
		if($slot_type == 1) {
			$html .= '<li class="col-xs-12 margin-bottom-10px margin-top-10px padding-right-0"><span style="font-size: 12px;"><span style="color:red;">Note:</span>Bikes picked in slots before 11 AM will be delivered in 5 hours and the rest in the next day.</span></li>';
		} elseif($slot_type == 2) {
			$html .= '<li class="col-xs-12 margin-bottom-10px margin-top-10px padding-right-0"><span style="font-size: 12px;"><span style="color:red;">Note:</span>Bikes picked in slots before 11 AM will be delivered by the End of the day and the rest in the next day.</span></li>';
		} elseif($slot_type == 3) {
			$html .= '<li class="col-xs-12 margin-bottom-10px margin-top-10px padding-right-0"><span style="font-size: 12px;"><span style="color:red;">Note:</span>Bikes picked in these slots will be delivered by the End of next day.</span></li>';
		}
		$html .= '';
		return $html;
	}
	private function distance($lat1, $lon1, $lat2, $lon2) {
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		return round($dist * 111.18957596, 2);
	}
	private function array2csv(&$results) {
		if (count($results) == 0) {
			return null;
		}
		ob_start();
		$df = fopen("php://output", 'w');
		fputcsv($df, array_keys(reset($results)));
		foreach ($results as $row) {
			fputcsv($df, $row);
		}
		fclose($df);
		return ob_get_clean();
	}
	private function get_all_bike_brands() {
		$this->db->select('bikecompany.BikeCompanyId'); $this->db->from('bikecompany');
		$query = $this->db->get(); $results = $query->result_array(); $return = array();
		foreach ($results as $result) {
			$return[] = $result['BikeCompanyId'];
		}
		return $return;
	}
	public function orderDemography() {
		$this->data['active'] = 'orderDemography'; $endDate = date("Y-m-d", strtotime("now"));
		$startDate = date("Y-m-d", strtotime("-30 day", strtotime($endDate))); $bikeBrands = NUll; $serviceCenters = NUll;
		$endDateFormat = date("d F, Y", strtotime("now")); $startDateFormat = date("d F, Y", strtotime("-30 day", strtotime($endDate)));
		if($this->input->post('startDate') && $this->input->post('startDate') != NULL && $this->input->post('startDate') != "" && $this->input->post('endDate') && $this->input->post('endDate') != NULL && $this->input->post('endDate') != "") {
			if($this->input->post('bikeBrands') && $this->input->post('bikeBrands') != NULL && $this->input->post('bikeBrands') != "") {
				$bikeBrands = $this->input->post('bikeBrands'); $bikeBrands = explode(',', $bikeBrands);
			} else {
				$bikeBrands = $this->get_all_bike_brands();
			}
			$startDate = date("Y-m-d", strtotime($this->input->post('startDate')));
			$endDate = date("Y-m-d", strtotime($this->input->post('endDate')));
			$startDateFormat = date("d F, Y", strtotime($this->input->post('startDate')));
			$endDateFormat = date("d F, Y", strtotime($this->input->post('endDate')));
			$serviceCenters = $this->input->post('serviceCenters'); $serviceCenters = explode(',', $serviceCenters);
			if(empty($serviceCenters[0])) {
				$serviceCenters = $this->get_service_centers_by_bike_brands($bikeBrands);
			}
		}
		$orders = $this->getOrderDemography($startDate, $endDate, $bikeBrands, $serviceCenters);
		$this->data['orderMap'] = $orders["orderMap"]; $this->data['orderCount'] = $orders["orderCount"]; $this->data['locationMap'] = $orders["locationMap"]; $this->data['serviceCenterLocationMap'] = $orders["serviceCenterLocationMap"];
		$this->data['serviceCenterMap'] = $orders["serviceCenterMap"]; $this->data['bike_brands'] = $this->get_bike_brands();
		$this->data['dateRange'] = "<div style='margin-left:40px;'>Showing <b>" . $this->data["orderCount"] . "</b> orders between &nbsp;<b>" . $startDateFormat . "</b>&nbsp; and &nbsp;<b>" . $endDateFormat . "</b></div>";
		$bikeBrandsRange = $this->get_bike_brands_names($bikeBrands); $serviceCentersRange = $this->get_service_centers_names($serviceCenters);
		$this->data['bikeBrandsRange'] = "<ol>";
		foreach ($bikeBrandsRange as $bikeBrand) {
			$this->data['bikeBrandsRange'] .= "<li><b>" . $bikeBrand . "</b></li>";
		}
		$this->data['bikeBrandsRange'] .= "</ol>";
		$this->data['bikeBrandsRange'] = "<div style='margin-left:40px;'><b>" . count($bikeBrandsRange) . "</b> Bike Companies : </div>" . $this->data['bikeBrandsRange'];
		$this->data['locationRange'] = "<ol>";
		foreach ($orders["locationMap"] as $locationMap => $locationOrderCount) {
			$this->data['locationRange'] .= "<li><b>" . $locationMap . " - " . $locationOrderCount . " Orders</b></li>";
		}
		$this->data['locationRange'] .= "</ol>";
		$this->data['locationRange'] = "<div style='margin-left:40px;'><b>" . count($orders["locationMap"]) . "</b> Locations : </div>" . $this->data['locationRange'];
		$this->data['serviceCentersRange'] = "<ol>";
		foreach ($serviceCentersRange as $serviceCenter) {
			$serviceCenterCount = 0;
			foreach ($orders["serviceCenterMap"] as $key => $serviceCenterMap) {
				if(trim($key) == trim($serviceCenter)) {
					$serviceCenterCount = $serviceCenterMap;
				}
			}
			$this->data['serviceCentersRange'] .= "<li><b>" . $serviceCenter . " : " . $serviceCenterCount . " Orders</b></li>";
		}
		$this->data['serviceCentersRange'] .= "</ol>";
		$this->data['serviceCentersRange'] = "<div style='margin-left:40px;'><b>" . count($serviceCentersRange) . "</b> Service Centers : </div>" . $this->data['serviceCentersRange'];
		$this->load->view('admin/orderDemography', $this->data);
	}
	private function getOrderDemography($startDate = NULL, $endDate = NULL, $bikeBrands = NULL, $serviceCenters = NULL) {
		$this->db->select('location.LocationName, location.Latitude, location.Longitude, user.UserName, servicecenter.ScName, odetails.ULatitude, odetails.ULongitude, scaddr.Latitude AS SCLatitude, scaddr.Longitude AS SCLongitude, SCLocation.LocationName AS SCLocationName');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId');
		$this->db->join('servicecenter', 'servicecenter.ScId = oservicedetail.ScId');
		$this->db->join('scaddrsplit', 'scaddrsplit.ScId = servicecenter.ScId');
		$this->db->join('location AS SCLocation', 'SCLocation.LocationId = scaddrsplit.LocationId');
		$this->db->join('scaddr', 'scaddr.ScAddrSplitId = scaddrsplit.ScAddrSplitId');
		$this->db->join('user', 'user.UserId = odetails.UserId');
		$this->db->join('useraddr', 'useraddr.UserAddrId = odetails.UserAddrId');
		$this->db->join('location', 'location.LocationId = useraddr.LocationId');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ODate >=', $startDate);
		$this->db->where('odetails.ODate <=', $endDate);
		if(isset($bikeBrands[0])) {
			$this->db->where_in('bikecompany.BikeCompanyId', $bikeBrands);
		}
		if(isset($serviceCenters[0])) {
			$this->db->where_in('servicecenter.ScId', $serviceCenters);
		}
		$query = $this->db->get(); $results = $query->result_array(); $orderMap = array();
		$serviceCenterLocationMap = array(); $locationMap = array(); $serviceCenterMap = array();
		if (count($results) == 0) {
			return array("orderMap" => array(), "orderCount" => 0, "serviceCenterMap" => array(), "locationMap" => array(), "serviceCenterLocationMap" => array());
		} else {
			foreach ($results as $result) {
				if($result['ULatitude'] != NULL && $result['ULongitude'] != NULL) {
					$result['Latitude'] = $result['ULatitude']; $result['Longitude'] = $result['ULongitude'];
				}
				$orderMap[$result['Latitude'] . ',' . $result['Longitude'] . ',' . $result['LocationName'] . ',' . $result['UserName']][] = $result;
				$serviceCenterLocationMap[$result['SCLatitude'] . ',' . $result['SCLongitude'] . ',' . $result['SCLocationName'] . ',' . $result['ScName']][] = $result;
				$serviceCenterMap[$result['ScName']][] = $result; $locationMap[$result['LocationName']][] = $result;
			}
			foreach ($serviceCenterMap as &$serviceCenter) {
				$serviceCenter = count($serviceCenter);
			}
			foreach ($locationMap as &$location) {
				$location = count($location);
			}
			foreach ($serviceCenterLocationMap as &$serviceCenterLocation) {
				$serviceCenterLocation = count($serviceCenterLocation);
			}
			return array("orderMap" => $orderMap, "orderCount" => count($results), "serviceCenterMap" => $serviceCenterMap, "locationMap" => $locationMap, "serviceCenterLocationMap" => $serviceCenterLocationMap);
		}
	}
	private function get_bike_brands() {
		$this->db->select('bikecompany.*'); $this->db->from('bikecompany');
		$query = $this->db->get(); $results = $query->result_array(); return $results;
	}
	private function get_bike_brands_names($array = NULL) {
		$this->db->select('BikeCompanyName'); $this->db->from('bikecompany');
		if($array != NULL) {
			$this->db->where_in('BikeCompanyId', $array);
		}
		$query = $this->db->get(); $results = $query->result_array(); $return = array();
		foreach ($results as $result) {
			$return[] = $result['BikeCompanyName'];
		}
		return $return;
	}
	private function get_service_centers_names($array = NULL) {
		$this->db->select('ScName'); $this->db->from('servicecenter');
		if($array != NULL) {
			$this->db->where_in('ScId', $array);
		}
		$query = $this->db->get(); $results = $query->result_array(); $return = array();
		foreach ($results as $result) {
			$return[] = $result['ScName'];
		}
		return $return;
	}
	public function get_service_centers_by_bike_brands($bikeBrands = NULL) {
		if($bikeBrands == NULL) {
			$bikeBrands = $_POST['bikeBrands']; $bikeBrands = explode(",", $bikeBrands); $echo = TRUE;
		} else { $echo = FALSE; }
		$this->db->select('servicecenter.ScId, servicecenter.ScName'); $this->db->from('MapScBc');
		$this->db->join('servicecenter', 'servicecenter.ScId = MapScBc.ScId');
		$this->db->where_in('MapScBc.BikeCompanyId', $bikeBrands);
		$this->db->group_by('MapScBc.ScId');
		$query = $this->db->get(); $results = $query->result_array();
		if($echo === FALSE) { return array_column($results, 'ScId'); } else { echo json_encode($results); }
	}
	public function orderPlan() {
		if($_POST && $_POST["startDate"] && $_POST["startDate"] != NULL && $_POST["startDate"] != "") {
			$startDate = date("Y-m-d", strtotime($_POST["startDate"]));
		} else {
			$startDate = date("Y-m-d", strtotime("now"));
		}
		$this->data['active'] = 'orderPlan';
		$this->data['orderPlan'] = $this->getOrderPlan($startDate);
		$this->load->view('admin/orderPlan', $this->data);
	}
	public function getOrderPlan($date) {
		$orders = array();
		for ($i=0; $i < 8; $i++) {
			$orders[$date] = $this->getOrdersForDate($date);
			$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
		}
		return $orders;
	}
	public function serviceReminders() {
		$this->data['active'] = 'serviceReminders'; $this->load->model('odetails_m');
		$service_reminder_date = $this->odetails_m->get_all_reminders("service_reminder_date", FALSE);
		$insurance_renewal_date = $this->odetails_m->get_all_reminders("insurance_renewal_date", FALSE);
		$puc_renewal_date = $this->odetails_m->get_all_reminders("puc_renewal_date", FALSE);
		$this->data['rows'] = array_merge($service_reminder_date, $insurance_renewal_date, $puc_renewal_date);
		$this->load->view('admin/serviceReminders', $this->data);
	}
	
	public function repeat_order($OId, $ScId) {		
		if($this->session->userdata('v_sc_id') == $ScId) {
			$this->data['active'] = 'repeatorder';
			$this->load->model('tieups_m');
			$this->load->model('bikecompany_m');
			$this->load->model('service_m');
			$this->load->model('vendor_m');
			$this->load->model('regnum_m');
			$this->load->model('insurer_m');
			$this->load->model('odetails_m');
			$this->data['businesses'] = $this->tieups_m->get_businesses();
			$this->data['user_details'] = $this->odetails_m->get_user_address($OId);
			$this->data['city_row'] = $this->vendor_m->get_city_row_by_vendor();
			$this->data['scname'] = $this->session->userdata('v_sc_name');
			$this->data['insurers'] = $this->insurer_m->get();
			$bike_service = $this->get_bike_service_by_oid($OId);
			$this->data['service_id'] = $bike_service["ServiceId"];
			if($bike_service["ServiceId"] != 1 && $bike_service["ServiceId"] != 2) {
				redirect("admin/orders/odetail/" . $OId);
			}
			$this->data['BikeModelId'] = $bike_service["BikeModelId"];
			$bikeNumber = trim($bike_service["BikeNumber"]);
			$bikeNumber = explode(" ", $bikeNumber);
			if(count($bikeNumber) > 0) {
				$this->data['RegNumber'] = trim($bikeNumber[0]); $this->data['BikeNumber'] = trim($bikeNumber[1]);
			} else {
				$this->data['RegNumber'] = ""; $this->data['BikeNumber'] = "";
			}
			$regnums = $this->regnum_m->get_all_regnumvals();
			if (count($regnums) > 0) {
				$this->data['regnums'] = '"' . implode('", "', $regnums) . '"';
			}
			$this->data['bikecompanies'] = $this->bikecompany_m->get_bcompany_by_id();
			$this->data['bikemodels'] = $this->get_bikemodels($this->data['bikecompanies'][0]['BikeCompanyId']);
			$this->data['services'] = $this->service_m->get_services_for_vendor_order();
			$this->data['sc_chosen'] = 1;
			if(intval($this->session->userdata('a_city_id')) > 0) {
				$temp_scs = $this->db->select('servicecenter.ScName')->from('servicecenter')->join('scaddrsplit', 'scaddrsplit.ScId = servicecenter.ScId')->where('scaddrsplit.CityId', intval($this->session->userdata('a_city_id')))->get()->result();
			} else {
				$this->load->model('servicecenter_m');
				$temp_scs = $this->servicecenter_m->get();
			}
			foreach($temp_scs as $sc) {
				$scnames[] = $sc->ScName;
			}
			$this->data['scnames'] = '"' . implode('", "', $scnames) . '"';
			$this->load->view('admin/repeatorder', $this->data);
		} else {
			$this->load->model('servicecenter_m');
			$this->session->set_userdata('v_sc_id', $ScId);
			$this->session->set_userdata('v_sc_name', $this->servicecenter_m->get($ScId)->ScName);
			redirect('admin/orders/repeat_order/' . $OId . '/' . $ScId);
		}
	}
	private function get_bike_service_by_oid($OId) {
		$this->db->select('ServiceId, BikeModelId, BikeNumber'); $this->db->from('odetails'); $this->db->where('OId', $OId);
		$query = $this->db->get(); $results = $query->result_array();
		return $results[0];
	}
	public function orderBills() {
		$this->data['active'] = 'orderBills';
		$this->data['rows'] = $this->get_order_bills();
		$this->load->view('admin/orderBills', $this->data);
	}
	private function get_order_bills() {
		$sql = "SELECT executive.ExecId, executive.ExecName, executive.Phone, executive.Email, ROUND(execwallet.wallet, 2) AS wallet, Latest, Oldest FROM executive ";
		$sql .= "LEFT JOIN (SELECT DATE_FORMAT(MAX(created_at),'%D %M, %Y') AS Latest, ExecId FROM execbill WHERE execbill.isCashSubmitted = 0 GROUP BY execbill.ExecId) AS execbillLatest ON (execbillLatest.ExecId = executive.ExecId) ";
		$sql .= "LEFT JOIN (SELECT DATE_FORMAT(MIN(created_at),'%D %M, %Y') AS Oldest, ExecId FROM execbill WHERE execbill.isCashSubmitted = 0 GROUP BY execbill.ExecId) AS execbillOldest ON (execbillOldest.ExecId = executive.ExecId) ";
		$sql .= "LEFT JOIN (SELECT SUM(opaymtdetail.PaymtAmt) AS wallet, execbill.ExecId FROM executive LEFT JOIN execbill ON execbill.ExecId = executive.ExecId INNER JOIN opaymtdetail ON opaymtdetail.OId = execbill.OId WHERE execbill.isCashSubmitted = '0' AND opaymtdetail.PaymtId = '3' GROUP BY executive.ExecId) AS execwallet ON execwallet.ExecId = executive.ExecId;";
		$query = $this->db->query($sql); $result = $query->result_array();
		return $result;
	}
	public function executiveBills($ExecId = NULL) {
		if(!isset($ExecId)) { $ExecId = 0; }
		$this->data['active'] = 'executiveBills';
		$this->data['rows'] = $this->get_executive_bills($ExecId);
		$this->data['executive'] = $ExecId;
		$this->load->view('admin/executiveBills', $this->data);
	}
	public function update_exec_bill() {
		if($_POST) {
			$this->load->model("executive_m");
			$this->load->model("opaymtdetail_m");
			$ExecBillId = intval($_POST['ExecBillId']); $ExecId = intval($_POST['ExecId']);
			$flag = intval($_POST['flag']); $column = $_POST['column'];
			$cod = round(floatval($this->opaymtdetail_m->get_cod_by_execbillid($ExecBillId)), 2);
			$execbill[$column] = $flag; $this->db->where('ExecBillId', $ExecBillId); $this->db->update('execbill', $execbill);
			redirect(site_url('admin/orders/executiveBills/' . $ExecId));
		}
	}
	public function get_executive_bills($ExecId) {
		$sql = "SELECT odetailsTable.OId, userTable.UserId, userTable.UserName, userTable.Phone, odetailsTable.ODate, execbill.isCashSubmitted, execbill.isBillSubmitted, execbill.ExecId, execbill.ExecBillId, ROUND(COALESCE(totalAmountTable.totalAmount, 0), 2) AS totalAmount, ROUND(COALESCE(cashAmountTable.cashAmount, 0), 2) AS cashAmount, ROUND(COALESCE(onlineAmountTable.onlineAmount, 0), 2) AS onlineAmount FROM execbill ";
		$sql .= "LEFT JOIN (SELECT OId, ODate, UserId FROM odetails) AS odetailsTable ON (odetailsTable.OId = execbill.OId) ";
		$sql .= "LEFT JOIN (SELECT UserId, UserName, Phone FROM user) AS userTable ON (userTable.UserId = odetailsTable.UserId) ";
		$sql .= "LEFT JOIN (SELECT odetails.OId, SUM(opaymtdetail.PaymtAmt) AS totalAmount FROM odetails LEFT JOIN opaymtdetail ON odetails.OId = opaymtdetail.OId WHERE opaymtdetail.PaymtStatusId = 3 GROUP BY odetails.OId) AS totalAmountTable ON (totalAmountTable.OId = odetailsTable.OId) ";
		$sql .= "LEFT JOIN (SELECT odetails.OId, SUM(opaymtdetail.PaymtAmt) AS cashAmount FROM odetails LEFT JOIN opaymtdetail ON odetails.OId = opaymtdetail.OId WHERE opaymtdetail.PaymtStatusId = 3 AND opaymtdetail.PaymtId = 3 GROUP BY odetails.OId) AS cashAmountTable ON (cashAmountTable.OId = odetailsTable.OId) ";
		$sql .= "LEFT JOIN (SELECT odetails.OId, SUM(opaymtdetail.PaymtAmt) AS onlineAmount FROM odetails LEFT JOIN opaymtdetail ON odetails.OId = opaymtdetail.OId WHERE opaymtdetail.PaymtStatusId = 3 AND (opaymtdetail.PaymtId = 6 || opaymtdetail.PaymtId = 7) GROUP BY odetails.OId) AS onlineAmountTable ON (onlineAmountTable.OId = odetailsTable.OId) ";
		$sql .= "WHERE execbill.ExecId = $ExecId AND (execbill.isCashSubmitted != 1 OR execbill.isBillSubmitted != 1) GROUP BY odetailsTable.OId ORDER BY odetailsTable.ODate";
		$query = $this->db->query($sql); $result = $query->result_array();
		return $result;
	}
	public function get_approved_executive_bills() {
		$ExecId = $this->input->post('ExecId'); $results = array();
		if($_POST && $_POST['startDate'] && $_POST['endDate']) {
			$startDate = date("Y-m-d", strtotime($_POST['startDate'])); $endDate = date("Y-m-d", strtotime($_POST['endDate']));
		} else {
			$startDate = date("Y-m-d", strtotime("-30 day", strtotime("now"))); $endDate = date("Y-m-d", strtotime("now"));
		}
		$sql = "SELECT execbill.isCashSubmitted, execbill.isBillSubmitted, execbill.ExecBillId, odetailsTable.OId, userTable.UserName, userTable.Phone, odetailsTable.ODate, ROUND(COALESCE(onlineAmountTable.onlineAmount, 0), 2) AS onlineAmount, ROUND(COALESCE(cashAmountTable.cashAmount, 0), 2) AS cashAmount, ROUND(COALESCE(totalAmountTable.totalAmount, 0), 2) AS totalAmount FROM execbill ";
		$sql .= "LEFT JOIN (SELECT OId, ODate, UserId FROM odetails) AS odetailsTable ON (odetailsTable.OId = execbill.OId) ";
		$sql .= "LEFT JOIN (SELECT UserId, UserName, Phone FROM user) AS userTable ON (userTable.UserId = odetailsTable.UserId) ";
		$sql .= "LEFT JOIN (SELECT odetails.OId, SUM(opaymtdetail.PaymtAmt) AS totalAmount FROM odetails LEFT JOIN opaymtdetail ON odetails.OId = opaymtdetail.OId WHERE opaymtdetail.PaymtStatusId = 3 GROUP BY odetails.OId) AS totalAmountTable ON (totalAmountTable.OId = odetailsTable.OId) ";
		$sql .= "LEFT JOIN (SELECT odetails.OId, SUM(opaymtdetail.PaymtAmt) AS cashAmount FROM odetails LEFT JOIN opaymtdetail ON odetails.OId = opaymtdetail.OId WHERE opaymtdetail.PaymtStatusId = 3 AND opaymtdetail.PaymtId = 3 GROUP BY odetails.OId) AS cashAmountTable ON (cashAmountTable.OId = odetailsTable.OId) ";
		$sql .= "LEFT JOIN (SELECT odetails.OId, SUM(opaymtdetail.PaymtAmt) AS onlineAmount FROM odetails LEFT JOIN opaymtdetail ON odetails.OId = opaymtdetail.OId WHERE opaymtdetail.PaymtStatusId = 3 AND (opaymtdetail.PaymtId = 6 || opaymtdetail.PaymtId = 7) GROUP BY odetails.OId) AS onlineAmountTable ON (onlineAmountTable.OId = odetailsTable.OId) ";
		$sql .= "WHERE odetailsTable.ODate >= '$startDate' AND odetailsTable.ODate <= '$endDate' AND execbill.ExecId = $ExecId AND execbill.isCashSubmitted = 1 AND execbill.isBillSubmitted = 1 GROUP BY odetailsTable.OId ORDER BY odetailsTable.ODate";
		$query = $this->db->query($sql); $result = $query->result_array();
		foreach ($result as $row) {
			$isCashSubmitted = '<div class="checkbox" style=""><label class="paymode">';
			$isCashSubmitted .= '<input type="checkbox" checked id="cash_submitted_approved_' . $row["ExecBillId"] . '" name="cash_submitted_approved" value="' . $row["ExecBillId"] . '"></input></label></div>';
			$isBillSubmitted = '<div class="checkbox" style=""><label class="paymode">';
			$isBillSubmitted .= '<input type="checkbox" checked id="bill_submitted_approved_' . $row["ExecBillId"] . '" name="bill_submitted_approved" value="' . $row["ExecBillId"] . '"></input></label></div>';
			$results[] = array($row['OId'], $row['UserName'], $row['Phone'], $row['ODate'], $row['onlineAmount'], $row['cashAmount'], $row['totalAmount'], $isCashSubmitted, $isBillSubmitted);
		}
		echo json_encode($results);
	}
	public function get_executive_bills_csv() {
		$this->initiate_download_headers('order_bills_' . date('d-m-Y') . '.csv');
		if($_POST && $_POST['startDate'] && $_POST['endDate']) {
			$startDate = date("Y-m-d", strtotime($_POST['startDate'])); $endDate = date("Y-m-d", strtotime($_POST['endDate']));
		} else {
			$startDate = date("Y-m-d", strtotime("-30 day", strtotime("now"))); $endDate = date("Y-m-d", strtotime("now"));
		}
		$sql = "SELECT odetails.OId, odetails.ODate AS OrderDate, odetails.ODate AS DeliveryDate, odetails.InvoiceDate, odetails.HappayTId AS HappYTransactionID, ROUND(COALESCE(odetails.HappayAmount, 0), 2) AS HappayAmount, tieups.TieupName, BikeModelName, servicecenter.ScName, BikeCompanyName, Phone, UserName, ServiceName, ROUND(COALESCE(opricesplitnodisc.BilledAmount, 0), 2) AS BilledAmount, ROUND(COALESCE(opricesplitnodisc.BilledAmountTax, 0), 2) AS BilledAmountTax, ROUND(COALESCE(OPriceF.OtherBilledAmount, 0), 2) AS OtherBilledAmount, ROUND(COALESCE(opaymtdetailsuccCOD.PaidAmountCOD, 0), 2) AS PaidAmountCOD, ROUND(COALESCE(opaymtdetailsuccOnline.PaidAmountOnline, 0), 2) AS PaidAmountOnline, ROUND(COALESCE(opaymtdetailsuccTotal.PaidAmountTotal, 0), 2) AS PaidAmountTotal, ROUND(COALESCE(opricesplitdisc.DiscountAmount, 0), 2) AS DiscountAmount, ROUND(COALESCE(opricesplitconv.ConvenienceAmount, 0), 2) AS ConvenienceAmount, ROUND(COALESCE(ConvenienceAmountTax, 0), 2) AS ConvenienceAmountTax, status.StatusName FROM odetails ";
		$sql .= "LEFT JOIN opricesplit ON (opricesplit.OId = odetails.OId) ";
		$sql .= "LEFT JOIN tieups ON (tieups.TieupId = odetails.TieupId) ";
		$sql .= "LEFT JOIN oservicedetail ON (oservicedetail.OId = odetails.OId) ";
		$sql .= "LEFT JOIN service ON (service.ServiceId = odetails.ServiceId) ";
		$sql .= "LEFT JOIN bikemodel ON (bikemodel.BikeModelId = odetails.BikeModelId) ";
		$sql .= "LEFT JOIN bikecompany ON (bikecompany.BikeCompanyId = bikemodel.BikeCompanyId) ";
		$sql .= "LEFT JOIN servicecenter ON (servicecenter.ScId = oservicedetail.ScId) ";
		$sql .= "LEFT JOIN user ON (user.UserId = odetails.UserId) ";
		$sql .= "LEFT JOIN status ON (status.StatusId = oservicedetail.StatusId) ";
		$sql .= "LEFT JOIN (SELECT odetails.OId, SUM(opricesplit.Price) AS BilledAmount, SUM(opricesplit.TaxPrice) AS BilledAmountTax FROM odetails INNER JOIN opricesplit ON odetails.OId = opricesplit.OId WHERE opricesplit.isDiscount = 0 GROUP BY odetails.OId) AS opricesplitnodisc ON (opricesplitnodisc.OId = odetails.OId) ";
		$sql .= "LEFT JOIN (SELECT odetails.OId, SUM(opricesplit.Price) AS DiscountAmount FROM odetails INNER JOIN opricesplit ON odetails.OId = opricesplit.OId WHERE opricesplit.isDiscount = 1 GROUP BY odetails.OId) AS opricesplitdisc ON (opricesplitdisc.OId = odetails.OId) ";
		$sql .= "LEFT JOIN (SELECT odetails.OId, SUM(opricesplit.Price) AS ConvenienceAmount FROM odetails INNER JOIN opricesplit ON odetails.OId = opricesplit.OId WHERE opricesplit.isConvFee = 1 GROUP BY odetails.OId) AS opricesplitconv ON (opricesplitconv.OId = odetails.OId) ";
		$sql .= "LEFT JOIN (SELECT odetails.OId, SUM(opricesplit.TaxPrice) AS ConvenienceAmountTax FROM odetails INNER JOIN opricesplit ON odetails.OId = opricesplit.OId WHERE opricesplit.isConvFee = 1 GROUP BY odetails.OId) AS opricesplitconvtax ON (opricesplitconvtax.OId = odetails.OId) ";
		$sql .= "LEFT JOIN (SELECT odetails.OId, SUM(OPrice.Price) AS OtherBilledAmount FROM odetails INNER JOIN OPrice ON odetails.OId = OPrice.OId GROUP BY odetails.OId) AS OPriceF ON OPriceF.OId = odetails.OId ";
		$sql .= "LEFT JOIN (SELECT odetails.OId, SUM(opaymtdetail.PaymtAmt) AS PaidAmountTotal FROM odetails INNER JOIN opaymtdetail ON odetails.OId = opaymtdetail.OId WHERE opaymtdetail.PaymtStatusId = 3 GROUP BY odetails.OId) AS opaymtdetailsuccTotal ON (opaymtdetailsuccTotal.OId = odetails.OId) ";
		$sql .= "LEFT JOIN (SELECT odetails.OId, SUM(opaymtdetail.PaymtAmt) AS PaidAmountCOD FROM odetails INNER JOIN opaymtdetail ON odetails.OId = opaymtdetail.OId WHERE opaymtdetail.PaymtStatusId = 3 AND opaymtdetail.PaymtId = 3 GROUP BY odetails.OId) AS opaymtdetailsuccCOD ON (opaymtdetailsuccCOD.OId = odetails.OId) ";
		$sql .= "LEFT JOIN (SELECT odetails.OId, SUM(opaymtdetail.PaymtAmt) AS PaidAmountOnline FROM odetails INNER JOIN opaymtdetail ON odetails.OId = opaymtdetail.OId WHERE opaymtdetail.PaymtStatusId = 3 AND (opaymtdetail.PaymtId = 6 OR opaymtdetail.PaymtId = 7) GROUP BY odetails.OId) AS opaymtdetailsuccOnline ON (opaymtdetailsuccOnline.OId = odetails.OId) ";
		$sql .= "WHERE odetails.ODate >= '$startDate' AND odetails.ODate <= '$endDate' AND odetails.ServiceId != 3 GROUP BY odetails.OId ORDER BY odetails.ODate DESC";
		$query = $this->db->query($sql); $result = $query->result_array();
		echo $this->array2csv($result); die();
	}
	public function update_happay() {
		if($_POST) {
			$odetails['HappayTId'] = $_POST['HappayTId']; $odetails['HappayAmount'] = $_POST['HappayAmount'];
			$this->db->where('OId', $_POST['oid']);
			$this->db->update('odetails', $odetails);
			redirect(site_url('admin/orders/odetail/' . $_POST['oid']));
		}
	}
	public function rating() {
		$this->data['active'] = 'rating';
		if($_POST) {
			$this->data['startDate'] = date("Y-m-d", strtotime($_POST['startDate']));;
			$startDate = date("Y-m-d 00:00:00", strtotime($_POST['startDate']));
			$this->data['endDate'] = date("Y-m-d", strtotime($_POST['endDate']));;
			$endDate = date("Y-m-d 00:00:00", strtotime($_POST['endDate']));
		} else {
			$startDate = date("Y-m-d H:i:s", strtotime("-200 day", strtotime("now")));
			$endDate = date("Y-m-d H:i:s", strtotime("now"));
		}
		$this->data['rows'] = $this->get_rating($startDate, $endDate);
		$this->load->view('admin/rating', $this->data);
	}
	private function get_rating($startDate, $endDate) {
		$this->db->select('g6rating.rating, g6rating.rating, DATE_FORMAT(g6rating.updated_at, "%D %M %Y %h:%i %p") AS updated_at, odetails.OId, user.UserName, user.UserId, user.Phone, user.Email');
		$this->db->from('g6rating');
		$this->db->join('odetails', 'odetails.OId = g6rating.OId');
		$this->db->join('user', 'user.UserId = odetails.UserId');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('updated_at >=', $startDate); $this->db->where('updated_at <=', $endDate);
		$this->db->order_by('updated_at', 'desc');
		$query = $this->db->get(); $results = $query->result_array(); return $results;
	}
	public function executiveOrderTracking() {
		$this->data['active'] = 'executiveOrderTracking';
		if($_POST) {
			$this->data['startDatePicker'] = $startDate = date("Y-m-d", strtotime($_POST['startDate']));
			$this->data['endDatePicker'] = $endDate = date("Y-m-d", strtotime($_POST['endDate']));
		} else {
			$this->data['startDatePicker'] = $startDate = date("Y-m-d", strtotime("-6 day", strtotime("now")));
			$this->data['endDatePicker'] = $endDate = date("Y-m-d", strtotime("now"));
		}
		$this->data['startDate'] = date("l, F d, Y", strtotime($startDate));
		$this->data['endDate'] = date("l, F d, Y", strtotime($endDate));
		$this->data['rows'] = $this->get_executive_order_tracking($startDate, $endDate);
		$this->load->view('admin/executiveOrderTracking', $this->data);
	}
	public function getPickupDetails($type, $ExecId, $date) {
		$this->data['active'] = 'getPickupDetails';
		$this->data['rows'] = $this->getExecutivePickups($type, $ExecId, $date);
		$this->load->view('admin/getPickupDetails', $this->data);
	}
	private function getExecutivePickups($type, $ExecId, $date) {
		$this->db->select('execordertrack.OId, user.UserId, user.UserName, user.Phone, DATE_FORMAT(CONVERT_TZ(execordertrack.Timestamp,"+00:00","+05:30"), "%D %M %Y %h:%i %p") AS Timestamp');
		$this->db->from('execordertrack');
		$this->db->join('odetails', 'odetails.OId = execordertrack.OId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		$this->db->where('execordertrack.ExecId', $ExecId);
		$this->db->where('execordertrack.date', $date);
		if($type == 'pickup') {
			$this->db->where('execordertrack.isPicked', 1);
		} elseif($type == 'delivered') {
			$this->db->where('execordertrack.isDelivered', 1);
		}
		$query = $result = $this->db->get();
		$result = $query->result_array();
		if(count($result) > 0) {
			return $result;
		} else {
			return array();
		}
	}
	private function get_executive_order_tracking($startDate, $endDate) {
		$result = array(); $date = $startDate;
		$diff = date_diff(date_create($startDate), date_create($endDate));
		$diff = intval($diff->format("%a"));
		for($i = 0; $i <= $diff; $i++) {
			$dateFormat = date("l, F d, Y", strtotime($date));
			$bikes = $this->get_bikes_picked_dropped($date);
			$result[] = array("date" => $date, "dateFormat" => $dateFormat, "bikes" => $bikes);
			$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
		}
		return $result;
	}
	private function get_bikes_picked_dropped($date) {
		$this->db->select('executive.ExecId, executive.ExecName, executive.Phone, SUM(execordertrack.isPicked) AS picked, SUM(execordertrack.isDelivered) AS delivered');
		$this->db->from('execordertrack');
		$this->db->join('executive', 'executive.ExecId = execordertrack.ExecId', 'left');
		$this->db->join('odetails', 'odetails.OId = execordertrack.OId', 'left');
		$this->db->where('execordertrack.date', $date);
		$this->db->group_by('execordertrack.ExecId');
		$query = $result = $this->db->get();
		$result = $query->result_array();
		if(count($result) > 0) {
			return $result;
		} else {
			return array();
		}
	}
	public function clear_emg_order() {
		if($_POST) {
			$emg['EmgOrderId'] = $_POST['EmgOrderId'];
			$emg['isCleared']= $_POST['isCleared'];
			$this->db->where('EmgOrderId', $emg['EmgOrderId']); $this->db->update('emgorders', $emg);
		}
	}
	public function clear_pt_order() {
		if($_POST) {
			$pt['PtOrderId'] = $_POST['PtOrderId'];
			$pt['isCleared']= $_POST['isCleared'];
			$this->db->where('PtOrderId', $pt['PtOrderId']); $this->db->update('ptorders', $pt);
		}
	}
	public function updateUser() {
		$this->load->model('user_m'); $response = array(); $response['status'] = 0; $response['phone'] = 0; $response['email'] = 0;
		if($_POST) {
			$user['Phone'] = $_POST['Phone']; $user['Email'] = $_POST['Email']; $UserId = intval($_POST['UserId']);
			$original_user = $this->user_m->get_by(array('UserId' => $UserId), TRUE);
			if($original_user) {
				if($original_user->Phone == $user['Phone']) {
					$response['phone'] = 1;
				}
				else {
					if($this->user_m->is_unique_ph($user['Phone'])) {
						$response['phone'] = 1;
					} else {
						$response['phone'] = 2;
					}
				}
				if($original_user->Email == $user['Email']) {
					$response['email'] = 1;
				}
				else {
					if($this->user_m->is_unique_em($user['Email'])) {
						$response['email'] = 1;
					} else {
						$response['email'] = 2;
					}
				}
			}
		}
		if($response['phone'] == 1 && $response['email'] == 1) {
			$this->db->where('UserId', $UserId); $this->db->update('user', $user);
			$response['status'] = 1;
		}
		echo json_encode($response);
	}
	public function updateUserAddress() {
		if($_POST) {
			$AddrLine1 = $this->input->post('AddrLine1'); $AddrLine2 = $this->input->post('AddrLine2'); $new_location = array();
			$UserId = $this->input->post('UserId'); $UserAddrId = $this->input->post('UserAddrId'); $OId = $this->input->post('OId');
			$CityId = $this->input->post('CityId'); $location = trim($this->input->post('location')); $landmark = $this->input->post('landmark');
			$latitude = $this->input->post('latitude'); $longitude = $this->input->post('longitude'); $new_address = array();
			$old_location = $this->db->select('LocationId')->from('location')->where('LocationName', $location)->where('CityId', $CityId)->get()->row_array();
			if(!$old_location) {
				$new_location['LocationName'] = trim($location); $new_location['Latitude'] = floatval($latitude);
				$new_location['Longitude'] = floatval($longitude); $new_location['CityId'] = $CityId;
				$this->db->insert('location', $new_location); $LocationId = $this->db->insert_id();
			} else {
				$LocationId = $old_location['LocationId'];
			}
			if(!(bool)$landmark) { $landmark = NULL; }
				$new_address['AddrLine1'] = trim($AddrLine1); $new_address['AddrLine2'] = trim($AddrLine2);
				$new_address['LocationId'] = $LocationId; $new_address['Landmark'] = trim($landmark);
			if(!$UserAddrId) {
				$new_address['UserId'] = $UserId; $this->db->insert('useraddr', $new_address); $UserAddrId = $this->db->insert_id();
				$odetails = array(); $odetails['UserAddrId'] = $UserAddrId; $this->db->where('OId', $OId); $this->db->update('odetails', $odetails);
			} else {
				$this->db->where('UserAddrId', $UserAddrId); $this->db->update('useraddr', $new_address);
			}
			redirect(site_url('admin/orders/odetail/' . $OId));
		}
		redirect(site_url('admin/orders'));
	}
}