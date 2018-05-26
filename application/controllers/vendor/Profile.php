<?php
class Profile extends G6_Vendorcontroller {
	public function __construct() {
		parent::__construct();
		$this->data['page'] = 'profile';
		$this->data['denied_tabs'] = $this->auth->get_denied_tabs();
	}
	public function index() {
		$this->auth->check_access('contact');
		$this->data['active'] = 'contact';
		$this->load->model('city_m');
		$this->load->model('servicecenter_m');
		$this->data['cities'] = $this->city_m->get_by(array('isEnabled' => 1));
		$this->data['sc_details'] = $this->servicecenter_m->get_sc_details();
		$this->data['sc_media_data'] = $this->servicecenter_m->get_sc_media_details();
		$this->load->view('vendor/scinfo', $this->data);
	}
	public function payments() {
		$this->auth->check_access('payment');
		$this->data['active'] = 'payment';
		$this->load->model('opaymtdetail_m');
		$this->data['rows'] = $this->opaymtdetail_m->get_success_transactions_by_vendor();
		$this->load->view('vendor/payments', $this->data);
	}
	public function offersnexclusives() {
		$this->auth->check_access('onexcl');
		$this->data['active'] = 'onexcl';
		$this->load->model('offers_m');
		$this->load->model('exclusives_m');
		$this->data['offers'] = $this->offers_m->get_offers_by_sc();
		$this->data['exclusives'] = $this->exclusives_m->get_exclusives_by_sc();
		$this->load->view('vendor/onexcl', $this->data);
	}
	public function create_offer() {
		$this->auth->check_access('onexcl');
		if($_POST) {
			$odata['OTitle'] = $this->input->post('otitle');
			$odata['ODesc'] = $this->input->post('odesc');
			$odata['OFrom'] = date('Y-m-d', strtotime($this->input->post('osdate')));
			$odata['OTill'] = date('Y-m-d', strtotime($this->input->post('oedate')));
			$odata['Price'] = $this->input->post('oprice');
			$odata['ScId'] = intval($this->session->userdata('v_sc_id'));
			$this->db->insert('offers', $odata);
		}
		redirect('/vendor/profile/offersnexclusives');
	}
	public function create_exclusive() {
		$this->auth->check_access('onexcl');
		if($_POST) {
			$odata['ETitle'] = $this->input->post('etitle');
			$odata['EDesc'] = $this->input->post('edesc');
			$odata['ScId'] = intval($this->session->userdata('v_sc_id'));
			$this->db->insert('exclusives', $odata);
		}
		redirect('/vendor/profile/offersnexclusives');
	}
	public function delete_exclusive($id) {
		$this->auth->check_access('onexcl');
		if($id != "" && $id !== NULL) {
			$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
			$this->db->where('ExclId', intval($id));
			$this->db->delete('exclusives');
		}
		redirect('/vendor/profile/offersnexclusives');
	}
	public function delete_offer($id) {
		$this->auth->check_access('onexcl');
		if($id != "" && $id !== NULL) {
			$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
			$this->db->where('OfferId', intval($id));
			$this->db->delete('offers');
		}
		redirect('/vendor/profile/offersnexclusives');
	}
	public function settings() {
		$this->data['active'] = 'settings';
		if($_POST) {
			$is_changed = $this->vendor_m->reset_password($this->input->post('fu_pwd'), $this->input->post('rf_pwd'), NULL, TRUE);
			if(!$is_changed) {
				$this->data['cp_error'] = 1;
			} else {
				$this->data['cp_error'] = 0;
			}
		}
		$this->load->view('vendor/settings', $this->data);
	}
	public function manage_users($id = NULL) {
		$this->auth->check_access('musers');
		if($_POST) {
			$fields = array('gender', 'upriv', 'fname', 'alt_ph', 'email', 'address');
			$data_fields = array('Gender', 'UserPrivilege', 'VendorName', 'AltPhone', 'Email', 'Address');
			$count = 0;
			$vdata = array();
			foreach($fields as $field) {
				$vdata[$data_fields[$count]] = $this->input->post($field);
				$count += 1;
			}
			$vdata['ScId'] = intval($this->session->userdata('v_sc_id'));
			$vdata['DOB'] = date("Y-m-d", strtotime($this->input->post('dob')));
			$vdata['isVerified'] = 1;
			if($this->input->post('vid') != "" && $this->auth->check_vmod($this->input->post('vid'))) {
				$this->vendor_m->save($vdata, intval($this->input->post('vid')));
			} elseif($this->vendor_m->is_unique_ph($this->input->post('p_phone')) && $this->auth->check_privilege($this->input->post('upriv'))) {
				$vdata['Phone'] = $this->input->post('p_phone');
				$vdata['Pwd'] = generateUniqueString(8);
				$vdata['Salt'] = generate_hash(generateUniqueString(8));
				$this->vendor_m->save($vdata);
			} else {
				$this->data['err_phone'] = "This phone is already registered with us";
			}
		}
		$this->data['active'] = 'musers';
		if($id && $this->auth->check_vmod($id)) {
			$vendor = $this->vendor_m->get_by(array('VendorId' => intval($id), 'ScId' => intval($this->session->userdata('v_sc_id'))), TRUE);
			if($vendor) {
				$this->data['evendor'] = $vendor;
				$date = new DateTime($this->data['evendor']->DOB);
				$this->data['evendor']->DOB = $date->format('d F, Y');
			}
		}
		$this->data['rows'] = $this->get_ulist($this->session->userdata('v_role') == 'Super User');
		$this->load->view('vendor/musers', $this->data);
	}
	public function delete_user($id = NULL) {
		$this->auth->check_access('musers');
		if($id && is_numeric($id) && $id != $this->session->userdata('v_id') && $this->auth->check_vmod($id)) {
			$this->vendor_m->delete(intval($id));
		}
		redirect('/vendor/profile/manage_users');
	}
	public function updateDefaultSlots() {
		$this->auth->check_access('slotm');
		if($_POST) {
			$data['DefaultSlots'] = intval($this->input->post('def_slot_val'));
			$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
			$this->db->update('servicecenter', $data);
			redirect('vendor/profile/slotMgmt');
		}
	}
	public function dialog_slots_data() {
		$this->auth->check_access('slotm');
		if($_POST) {
			$date = date('Y-m-d', strtotime($this->input->post('date')));
			$this->load->model('servicecenter_m');
			$sc_row = $this->servicecenter_m->get_by(array('ScId' => intval($this->session->userdata('v_sc_id'))), TRUE);
			$blah = $this->servicecenter_m->set_get_slots(intval($sc_row->ScId), $date, $sc_row->DefaultSlots, $sc_row->SlotDuration, intval($sc_row->SlotType), $sc_row->StartHour, $sc_row->EndHour);
			$this->data['slots'] = $this->servicecenter_m->set_get_slots(intval($sc_row->ScId), $date, $sc_row->DefaultSlots, $sc_row->SlotDuration, intval($sc_row->SlotType), $sc_row->StartHour, $sc_row->EndHour);
			$this->load->view('vendor/components/_slots', $this->data);
		}
	}
	public function getSlotEventsData() {
		$this->auth->check_access('slotm');
		if($_POST) {
			$this->load->model('servicecenter_m');
			$slot_dump = $this->servicecenter_m->get_slots_for_vendor(date('Y-m-d', strtotime($this->input->post('start'))), date('Y-m-d', strtotime($this->input->post('end'))));
			$event_array = array();
			foreach($slot_dump as $slot) {
				if ($slot['Hour'] > 12) {
					$temp_hr = intval($slot['Hour'] - 12);
					$temp = (intval($slot['Hour'] * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$slot_hour = $temp_hr . ":" . $temp . " PM";
				} elseif ($slot['Hour'] == 12) {
					$slot_hour = intval($slot['Hour']) . ":00 PM";
				} else {
					$temp = (intval($slot['Hour'] * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$slot_hour = intval($slot['Hour']) . ":" . $temp . " AM";
				}
				if(isset($slot['EHour']) && $slot['EHour'] != 0) {
					if ($slot['EHour'] > 12) {
						$slot_hour .= " - ";
						$temp_hr = intval($slot['EHour'] - 12);
						$temp = (intval($slot['EHour'] * 60) % 60);
						if($temp == 0) {
							$temp = '00';
						}
						$slot_hour .= $temp_hr . ":" . $temp . " PM";
					} elseif ($slot['EHour'] == 12) {
						$slot_hour .= " - ";
						$slot_hour .= intval($slot['EHour']) . ":00 PM";
					} else {
						$slot_hour .= " - ";
						$temp = (intval($slot['EHour'] * 60) % 60);
						if($temp == 0) {
							$temp = '00';
						}
						$slot_hour .= intval($slot['EHour']) . ":" . $temp . " AM";
					}
				}
				$event_array[]  = array(
						'id' => (int) $slot['SlotId'],
						'title' => $slot_hour . ' - ' . strval($slot['Slots'] - $slot['BufferedSlots']),
						'start' => strval($slot['Day'])
				);
			}
			echo json_encode($event_array);
		}
	}
	public function set_slots() {
		$this->auth->check_access('slotm');
		if($_POST) {
			$this->load->model('servicecenter_m');
			$num_selected_slots = intval($this->input->post('num_slots'));
			$sel_date = date('Y-m-d', strtotime($this->input->post('selected_date')));
			$slotids = array_map('intval', $this->input->post('slotids'));
			$old_slots = $this->servicecenter_m->get_slot_and_buffer_data($slotids);
			$count = 0;
			foreach($old_slots as $slots) {
				$new_slots[$count]['SlotId'] = intval($slots['SlotId']);
				$new_slots[$count]['ScId'] = intval($this->session->userdata('v_sc_id'));
				$new_slots[$count]['Day'] = $slots['Day'];
				$new_slots[$count]['Hour'] = $slots['Hour'];
				$new_slots[$count]['EHour'] = $slots['EHour'];
				$new_slots[$count]['Slots'] = $num_selected_slots + intval($slots['BufferedSlots']);
				$count += 1;
			}
			$this->db->update_batch('slots', $new_slots, 'SlotId');
			redirect('vendor/profile/slotMgmt');
		}
	}
	public function bulkUpdateSlots() {
		$this->auth->check_access('slotm');
		if($_POST) {
			$this->load->model('servicecenter_m');
			$bul_num_slots = intval($this->input->post('bul_num_slots'));
			$bul_dates = explode(", ", $this->input->post('bul_dates'));
			$slot_checks = $this->servicecenter_m->get_inserted_bulk_slots($bul_dates);
			$sc = $this->servicecenter_m->get_by(array(
				'ScId' => intval($this->session->userdata('v_sc_id')),
			), TRUE);
			$slot_duration = $sc->SlotDuration;
			$slot_type = intval($sc->SlotType);
			$update_days = array();
			foreach($slot_checks as &$slot_check) {
				$slot_check['Slots'] = $bul_num_slots + intval($slot_check['BufferedSlots']);
				$update_days[] = $slot_check['Day'];
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
							$new_slots[$count]['ScId'] = intval($this->session->userdata('v_sc_id'));
							$new_slots[$count]['Day'] = $new_dates[$i];
							$new_slots[$count]['Hour'] = $j;
							$new_slots[$count]['EHour'] = $j + 5.0;
							$new_slots[$count]['Slots'] = $bul_num_slots;
							$count += 1;
						}
						$j += 0.5;
					}
					for(; $j <= $sc->EndHour; $j += $slot_duration) {
						$new_slots[$count]['ScId'] = intval($this->session->userdata('v_sc_id'));
						$new_slots[$count]['Day'] = $new_dates[$i];
						$new_slots[$count]['Hour'] = $j;
						$new_slots[$count]['EHour'] = 0;
						$new_slots[$count]['Slots'] = $bul_num_slots;
						$count += 1;
					}
				}
				$this->db->insert_batch('slots', $new_slots);
			}
			redirect('vendor/profile/slotMgmt');
		}
	}
	public function slotMgmt() {
		$this->auth->check_access('slotm');
		$this->load->model('servicecenter_m');
		$this->data['active'] = 'slotm';
		$sc_row = $this->servicecenter_m->get_by(array(
			'ScId' => intval($this->session->userdata('v_sc_id'))
		), TRUE);
		$this->data['def_slots'] = $sc_row->DefaultSlots;
		$this->data['def_slot_du'] = $sc_row->SlotDuration;
		$bulk_disabled_dates = $this->servicecenter_m->slot_buffered_dates(date('Y-m-d', strtotime("now")), date('Y-m-d', strtotime("now +45 days")));
		if (count($bulk_disabled_dates) > 0) {
			$this->data['dis_dates'] = '["' . implode('", "', $bulk_disabled_dates) . '"]';
		}
		$this->load->view('vendor/slotm', $this->data);
	}
	public function services() {
		$this->auth->check_access('services');
		$this->data['active'] = 'services';
		$this->load->model('service_m');
		$this->load->model('bikecompany_m');
		$this->load->model('amenity_m');
		$this->data['services'] = $this->service_m->get_by(array('isEnabled' => 1, 'ScType' => 'sc'));
		$this->data['sel_services'] = $this->service_m->get_services_by_sc();
		$this->data['bcompanies'] = $this->bikecompany_m->get();
		$this->data['amenities'] = $this->amenity_m->get_by('AmCode = 1');
		$this->data['sel_amenities'] = $this->amenity_m->get_amenities_by_sc();
		$this->load->view('vendor/services', $this->data);
	}
	public function rpPrices() {
		$this->auth->check_access('price-chart');
		if(isset($_POST)) {
			$this->load->model('amenity_m');
			$radfroms = explode(',', $this->input->post('radfroms'));
			$radtos = explode(',', $this->input->post('radtos'));
			$rprices = explode(',', $this->input->post('rprices'));
			$rtype = $this->input->post('rtype');
			$radfroms = array_map('intval', $radfroms);
			$radtos = array_map('intval', $radtos);
			$rprices = array_map('floatval', $rprices);
			$radfroms[] = intval($this->input->post('lfrom'));
			$radtos[] = NULL;
			$rprices[] = floatval($this->input->post('lprice'));
			$rdata = array();
			$rcount = 0;
			foreach($rprices as $rprice) {
				$rdata[$rcount]['ScId'] = intval($this->session->userdata('v_sc_id'));
				$rdata[$rcount]['SDistance'] = $radfroms[$rcount];
				$rdata[$rcount]['EDistance'] = $radtos[$rcount];
				$rdata[$rcount]['Type'] = $rtype;
				$rdata[$rcount]['Price'] = $rprices[$rcount];
				$rdata[$rcount]['FixedFlag'] = 0;
				$rcount += 1;
			}
			if(count($rdata) > 0) {
				$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
				$this->db->where('FixedFlag', 0);
				$this->db->delete('pickprice');
				$this->db->insert_batch('pickprice', $rdata);
			}
			redirect('vendor/profile/pickareas');
		} else {
			redirect('vendor/profile/pickareas');
		}
	}
	public function apPrices() {
		$this->auth->check_access('price-chart');
		if(isset($_POST)) {
			$this->load->model('location_m');
			$this->load->model('amenity_m');
			$nanames = explode(',', $this->input->post('anames'));
			$natypes = explode(',', $this->input->post('atypes'));
			$naprices = explode(',', $this->input->post('aprices'));
			$count = 0;
			foreach($natypes as $natype) {
				if($natype == 'pick') {
					$pnanames[] = $nanames[$count];
					$pnaprices[] = intval($naprices[$count]);
				} elseif($natype == 'drop') {
					$dnanames[] = $nanames[$count];
					$dnaprices[] = intval($naprices[$count]);
				} elseif($natype == 'both') {
					$pnanames[] = $nanames[$count];
					$dnanames[] = $nanames[$count];
					$pnaprices[] = intval($naprices[$count]);
					$dnaprices[] = intval($naprices[$count]);
				}
				$count += 1;
			}
			if(isset($pnanames)) {
				foreach($pnanames as $pnaname) {
					$pnalcids[] = intval($this->location_m->location_id_by_name($pnaname)['LocationId']);
				}
				$poalcids = $this->amenity_m->get_old_apick_prices('pick');
				$additions = array(array());
				$updations = array(array());
				$ucount = 0;
				$acount = 0;
				$count = 0;
				foreach($pnalcids as $pnalcid) {
					$pickidifany = array_search($pnalcid, $poalcids);
					if($pickidifany) {
						$updations[$ucount]['PickPriceId'] = intval($pickidifany);
						$updations[$ucount]['LocationId'] = $pnalcid;
						$updations[$ucount]['Type'] = 'pick';
						$updations[$ucount]['ScId'] = intval($this->session->userdata('v_sc_id'));
						$updations[$ucount]['Price'] = floatval($pnaprices[$count]);
						$updations[$ucount]['FixedFlag'] = 1;
						$ucount += 1;
					} else {
						$additions[$acount]['LocationId'] = $pnalcid;
						$additions[$acount]['Type'] = 'pick';
						$additions[$acount]['ScId'] = intval($this->session->userdata('v_sc_id'));
						$additions[$acount]['Price'] = floatval($pnaprices[$count]);
						$additions[$acount]['FixedFlag'] = 1;
						$acount += 1;
					}
					$count += 1;
				}
				if(count($additions[0]) > 0) {
					$this->db->insert_batch('pickprice', $additions); 
				}
				if(count($updations[0]) > 0) {
					$this->db->update_batch('pickprice', $updations, 'PickPriceId');
				}
			}
			if(isset($dnanames)) {
				foreach($dnanames as $dnaname) {
					$dnalcids[] = intval($this->location_m->location_id_by_name($dnaname)['LocationId']);
				}
				$doalcids = $this->amenity_m->get_old_apick_prices('drop');
				$additions = array(array());
				$updations = array(array());
				$ucount = 0;
				$acount = 0;
				$count = 0;
				foreach($dnalcids as $dnalcid) {
					$dropidifany = array_search($dnalcid, $doalcids);
					if($dropidifany) {
						$updations[$ucount]['PickPriceId'] = intval($dropidifany);
						$updations[$ucount]['LocationId'] = $dnalcid;
						$updations[$ucount]['Type'] = 'drop';
						$updations[$ucount]['ScId'] = intval($this->session->userdata('v_sc_id'));
						$updations[$ucount]['Price'] = floatval($dnaprices[$count]);
						$updations[$ucount]['FixedFlag'] = 1;
						$ucount += 1;
					} else {
						$additions[$acount]['LocationId'] = $dnalcid;
						$additions[$acount]['Type'] = 'drop';
						$additions[$acount]['ScId'] = intval($this->session->userdata('v_sc_id'));
						$additions[$acount]['Price'] = floatval($dnaprices[$count]);
						$additions[$acount]['FixedFlag'] = 1;
						$acount += 1;
					}
					$count += 1;
				}
				if(count($additions[0]) > 0) {
					$this->db->insert_batch('pickprice', $additions); 
				}
				if(count($updations[0]) > 0) {
					$this->db->update_batch('pickprice', $updations, 'PickPriceId');
				}
			}
			redirect('vendor/profile/pickareas');
		} else {
			redirect('vendor/profile/pickareas');
		}
	}
	public function pricechart() {
		$this->auth->check_access('price-chart');
		$this->data['active'] = 'price-chart';
		$this->load->model('amenity_m');
		$this->load->model('service_m');
		$this->load->model('bikecompany_m');
		$this->data['bcompanies'] = $this->bikecompany_m->get_bcompany_by_id();
		$this->data['pservices'] = $this->service_m->get_priced_services();
		$this->data['pamenities'] = $this->amenity_m->get_priced_amenities();
		$this->data['amprices'] = $this->amenity_m->get_amprices();
		$this->data['srprices'] = $this->service_m->get_srprices();
		$this->load->view('vendor/pricechart', $this->data);
	}
	public function aservices() {
		$this->auth->check_access('aservices');
		$this->data['active'] = 'aservices';
		$this->load->model('aservice_m');
		$this->load->model('bikecompany_m');
		$this->data['bcompanies'] = $this->bikecompany_m->get_bcompany_by_id();
		$this->data['aservices'] = $this->aservice_m->get_by(array('isEnabled' => 1, 'AScType' => 'sc'));
		$this->data['asrprices'] = $this->aservice_m->get_asrprices();
		$this->load->view('vendor/aservices', $this->data);
	}
	public function delete_asprice($asp_id = NULL) {
		if(isset($asp_id)) {
			$this->auth->check_access('aservices');
			$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
			$this->db->where('ASPriceId', intval($asp_id));
			$this->db->limit(1);
			$this->db->delete('asprice');
		}
		redirect('vendor/profile/aservices');
	}
	public function save_asrprice() {
		if(isset($_POST)) {
			$this->auth->check_access('aservices');
			$this->load->model('aservice_m');
			$bmodels = $this->input->post('bmodels');
			$bmodels = array_map('intval', $bmodels);
			$count = 0;
			$old_bm_dict = $this->aservice_m->get_old_asprices($this->input->post('astype'));
			$additions = array(array());
			$updations = array(array());
			$ucount = 0;
			$acount = 0;
			foreach($bmodels as $bmodel) {
				if(isset($old_bm_dict) && $spidifany = array_search($bmodel, $old_bm_dict)) {
					$updations[$ucount]['ASPriceId'] = intval($spidifany);
					$updations[$ucount]['AServiceId'] = intval($this->input->post('astype'));
					$updations[$ucount]['BikeModelId'] = $bmodel;
					$updations[$ucount]['ScId'] = intval($this->session->userdata('v_sc_id'));
					$updations[$ucount]['Price'] = floatval($this->input->post('asr_price'));
					$updations[$ucount]['TaxType'] = intval($this->input->post('taxtype'));
					$updations[$ucount]['IsMand'] = intval($this->input->post('ismandatory'));
					$ucount += 1;
				} else {
					$additions[$acount]['AServiceId'] = intval($this->input->post('astype'));
					$additions[$acount]['BikeModelId'] = $bmodel;
					$additions[$acount]['ScId'] = intval($this->session->userdata('v_sc_id'));
					$additions[$acount]['Price'] = floatval($this->input->post('asr_price'));
					$additions[$acount]['TaxType'] = intval($this->input->post('taxtype'));
					$additions[$acount]['IsMand'] = intval($this->input->post('ismandatory'));
					$acount += 1;
				}
			}
			if(count($additions[0]) > 0) {
				$this->db->insert_batch('asprice', $additions); 
			}
			if(count($updations[0]) > 0) {
				$this->db->update_batch('asprice', $updations, 'ASPriceId');
			}
			redirect(site_url('vendor/profile/aservices'));
		} else {
			redirect(site_url('vendor/profile/aservices'));
		}
	}
	public function save_amenities() {
		$this->auth->check_access('services');
		if(isset($_POST)) {
			$this->load->model('amenity_m');
			$amtys = $this->input->post('amenity');
			if($amtys == "") {
				$amtys = array();
			}
			$new_amt_ids = array_map('intval', $amtys);
			foreach($new_amt_ids as $new_amt_id) {
				$new_amt_descs[] = $this->input->post('am_desc_' . $new_amt_id);
			}
			$oamtys = $this->amenity_m->get_oldamenities_by_sc();
			$old_amt_ids = $oamtys[0];
			$old_amt_descs = $oamtys[1];
			$old_scams = $oamtys[2];
			$additions = array(array());
			$deletions = array();
			$updates = array(array());
			$adcount = 0;
			$udcount = 0;
			$count = 0;
			foreach($new_amt_ids as $new_amt_id) {
				if(empty($old_amt_ids) || !in_array($new_amt_id, $old_amt_ids)) {
					$additions[$adcount]['AmId'] = $new_amt_id;
					$additions[$adcount]['ScId'] = intval($this->session->userdata('v_sc_id'));
					$additions[$adcount]['AmDesc'] = $new_amt_descs[$count];
					$additions[$adcount]['AmTerms'] = "http://www.google.com";
					$adcount += 1;
				} else {
					$old_amt_index = array_search($new_amt_id, $old_amt_ids);
					if($old_amt_descs[$old_amt_index] != $new_amt_descs[$count]) {
						$updates[$udcount]['ScAmId'] = $old_scams[$old_amt_index];
						$updates[$udcount]['AmId'] = $new_amt_id;
						$updates[$udcount]['ScId'] = intval($this->session->userdata('v_sc_id'));
						$updates[$udcount]['AmDesc'] = $new_amt_descs[$count];
						$udcount += 1;
					}
				}
				$count += 1;
			}
			$count = 0;
			if(isset($old_amt_ids)) {
				foreach($old_amt_ids as $old_amt_id) {
					if(!in_array($old_amt_id, $new_amt_ids)) {
						$deletions[] = $old_scams[$count];
						$del_am_ids[] = $old_amt_id;
					}
					$count += 1;
				}
			}
			if($adcount > 0) {
				$this->db->insert_batch('scam', $additions);
			}
			if($udcount > 0) {
				$this->db->update_batch('scam', $updates, 'ScAmId');
			}
			if(count($deletions) > 0) {
				$this->db->where_in('ScAmId', $deletions);
				$this->db->delete('scam');
				$this->db->where_in('AmId', $del_am_ids);
				$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
				$this->db->delete('aprice');
			}
			redirect('vendor/profile/services');
		} else {
			redirect('vendor/profile/services');
		}
	}
	public function save_bikemodels() {
		$this->auth->check_access('services');
		if(isset($_POST)) {
			$bc = intval($this->input->post('company'));
			$bms = $this->input->post('bmodels');
			if($bms == "") {
				$bms = array();
			}
			$new_bms = array_map('intval', $bms);
			$this->load->model('bikecompany_m');
			if($bc != 0 && count($new_bms) == 0) {
				$this->bikecompany_m->del_bc_for_sc($bc);
			}
			if($bc != 0) {
				$this->load->model('bikemodel_m');
				$old_bms = $this->bikemodel_m->get_selected_bm_ids();
				if($old_bms === NULL) {
					$old_bms = array();
				}
				$additions = array();
				$deletions = array();
				foreach($new_bms as $new_bm) {
					if(!in_array($new_bm, $old_bms)) {
						$additions[] = $new_bm;
					}
				}
				foreach($old_bms as $old_bm) {
					if(!in_array($old_bm, $new_bms)) {
						$deletions[] = $old_bm;
					}
				}
				if(count($additions) > 0) {
					$count = 0;
					$adata = array(array());
					foreach($additions as $addition) {
						$adata[$count]['ScId'] = intval($this->session->userdata('v_sc_id'));
						$adata[$count]['BikeModelId'] = $addition;
						$count += 1;
					}
					$this->db->insert_batch('MapScBm', $adata);
					$this->bikecompany_m->ins_bc_ifnot_exists($bc);
				}
				if(count($deletions) > 0) {
					$this->db->where_in('BikeModelId', $deletions);
					$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
					$this->db->delete('MapScBm');
					$this->db->where_in('BikeModelId', $deletions);
					$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
					$this->db->delete('sprice');
				}
				redirect('vendor/profile/services');
			} else {
				redirect('vendor/profile/services');
			}
		} else {
			redirect('vendor/profile/services');
		}
	}
	public function save_services() {
		$this->auth->check_access('services');
		if(isset($_POST)) {
			$new_services = $this->input->post('service');
			if($new_services == "") {
				$new_services = array();
			}
			$this->load->model('service_m');
			$new_services = array_map('intval', $new_services);
			$old_services = $this->service_m->get_services_by_sc();
			if($old_services === NULL) {
				$old_services = array();
			}
			$additions = array();
			$deletions = array();
			foreach($new_services as $new_service) {
				if(!in_array($new_service, $old_services)) {
					$additions[] = $new_service;
				}
			}
			foreach($old_services as $old_service) {
				if(!in_array($old_service, $new_services)) {
					$deletions[] = $old_service;
				}
			}
			if(count($additions) > 0) {
				$count = 0;
				$adata = array(array());
				foreach($additions as $addition) {
					$adata[$count]['ScId'] = intval($this->session->userdata('v_sc_id'));
					$adata[$count]['ServiceId'] = $addition;
					$count += 1;
				}
				$this->db->insert_batch('MapScService', $adata);
			}
			if(count($deletions) > 0) {
				$this->db->where_in('ServiceId', $deletions);
				$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
				$this->db->delete('MapScService');
			}
			redirect('vendor/profile/services');
		} else {
			redirect('vendor/profile/services');
		}
	}
	public function save_srprice() {
		$this->auth->check_access('price-chart');
		if(isset($_POST)) {
			$this->load->model('service_m');
			$bmodels = $this->input->post('bmodels');
			$bmodels = array_map('intval', $bmodels);
			$count = 0;
			$old_bm_dict = $this->service_m->get_old_sprices($this->input->post('stype'));
			$additions = array(array());
			$updations = array(array());
			$ucount = 0;
			$acount = 0;
			foreach($bmodels as $bmodel) {
				if(isset($old_bm_dict) && $spidifany = array_search($bmodel, $old_bm_dict)) {
					$updations[$ucount]['SPriceId'] = intval($spidifany);
					$updations[$ucount]['ServiceId'] = intval($this->input->post('stype'));
					$updations[$ucount]['BikeModelId'] = $bmodel;
					$updations[$ucount]['ScId'] = intval($this->session->userdata('v_sc_id'));
					$updations[$ucount]['Price'] = floatval($this->input->post('sr_price'));
					$ucount += 1;
				} else {
					$additions[$acount]['ServiceId'] = intval($this->input->post('stype'));
					$additions[$acount]['BikeModelId'] = $bmodel;
					$additions[$acount]['ScId'] = intval($this->session->userdata('v_sc_id'));
					$additions[$acount]['Price'] = floatval($this->input->post('sr_price'));
					$acount += 1;
				}
			}
			if(count($additions[0]) > 0) {
				$this->db->insert_batch('sprice', $additions); 
			}
			if(count($updations[0]) > 0) {
				$this->db->update_batch('sprice', $updations, 'SPriceId');
			}
			redirect(site_url('vendor/profile/pricechart'));
		} else {
			redirect(site_url('vendor/profile/pricechart'));
		}
	}
	public function save_amprice() {
		$this->auth->check_access('price-chart');
		if(isset($_POST)) {
			$data['AmId'] = intval($this->input->post('am_id'));
			$data['Price'] = floatval($this->input->post('am_price'));
			$data['ScId'] = intval($this->session->userdata('v_sc_id'));
			$this->db->select('APriceId');
			$this->db->from('aprice');
			$this->db->where('AmId', $data['AmId']);
			$this->db->where('ScId', $data['ScId']);
			$this->db->limit(1);
			$query = $this->db->get();
			$result = $query->result_array();
			if (count($result) == 0) {
				$this->db->insert('aprice', $data);
				redirect(site_url('vendor/profile/pricechart'));
			} else {
				$this->db->where('APriceId', $result[0]['APriceId']);
				$this->db->update('aprice', $data);
				redirect(site_url('vendor/profile/pricechart'));
			}
		} else {
			redirect(site_url('vendor/profile/pricechart'));
		}
	}
	public function updateScDetails() {
		$this->auth->check_access('contact');
		$this->load->model('servicecenter_m');
		$this->servicecenter_m->updt_scaddr();
		redirect(site_url('vendor/profile'));
	}
	public function updateScMedia() {
		$this->upload_scmedia($this->session->userdata('v_sc_id'), $this->session->userdata('v_sc_type'), 'info');
		redirect(site_url('vendor/profile'));
	}
	public function getCityLocations() {
		if($_POST) {
			if($this->input->post('city') != "") {
				$this->load->model('location_m');
				echo json_encode($this->location_m->locations_for_sc($this->input->post('city')));
			}
		}
	}
	public function bikeList() {
		if($_POST) {
			if($this->input->post('company') != "") {
				$this->load->model('bikemodel_m');
				echo json_encode($this->bikemodel_m->get_bmodels_by_sc());
			}
		}
	}
	public function scBikeModels() {
		if($this->input->post('company') != "") {
			$this->load->model('bikemodel_m');
			$data = array();
			$data['bcs'] = $this->bikemodel_m->get_bikes_by_company();
			$data['selbcs'] = $this->bikemodel_m->get_selected_bm_ids();
			echo json_encode($data);
		}
	}
	private function upload_scmedia($scid, $sctype, $mediatype) {
		$this->load->library('upload', $this->upload_config());
		for($i = 0; $i <= 6; $i++) {
			if($this->upload->do_upload('uploadImage_' . $i)) {
				$file_data = $this->upload->data();
				$upload_data['ScId'] = $scid;
				$upload_data['ScType'] = $sctype;
				if($i == 0) {
					$upload_data['MediaType'] = 'logo';
					$this->session->set_userdata('v_sc_logo', $file_data['file_name']);
				} else {
					$upload_data['MediaType'] = $mediatype;
				}
				$upload_data['MediaOrder'] = $i;
				$old_scmedia_id = $this->get_delete_old_scmedia($upload_data);
				$upload_data['FileData'] = $file_data['file_name'];
				if(boolval($file_data['is_image'])) {
					$upload_data['FileType'] = 'img';
				} else {
					$upload_data['FileType'] = 'vid';
				}
				$this->move_uploaded_file($file_data['file_name'], $upload_data['FileType'], $sctype);
				if($old_scmedia_id) {
					$this->db->where('SCMediaId', intval($old_scmedia_id));
					$this->db->update('scmedia', $upload_data);
				} else {
					$this->db->insert('scmedia', $upload_data);
				}
			}
		}
	}
	private function get_delete_old_scmedia($data) {
		$this->db->where($data);
		$this->db->limit(1);
		$query = $this->db->get('scmedia');
		$result = $query->row_array();
		if($result) {
			$file_path = 'uploads/scmedia/' . $data['ScType'] . '/' . $result['FileType'] . '/' . $result['FileData'];
			$this->load->library('awssdk');
			$s3 = $this->awssdk->get_s3_instance();
			$s3->deleteObject(array(
				'Bucket' => 'gear6cdn',
				'Key'    => $file_path
			));
			return $result['SCMediaId'];
		} else {
			return FALSE;
		}
	}
	private function upload_config() {
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['upload_path'] = realpath(APPPATH . '../html/uploads/temp');
		$config['encrypt_name'] = TRUE;
		$config['remove_spaces'] = TRUE;
		$config['max_size'] = '5120';
		$config['overwrite'] = FALSE;
		return $config;
	}
	private function move_uploaded_file($fname, $ftype, $path) {
		$from_file = realpath(APPPATH . '../html/uploads/temp');
		$from_file = rtrim($from_file, '/').'/';
		$from_file .= $fname;
		$to_file = 'uploads/scmedia/' . $path . '/';
		$to_file .= $ftype . '/' . $fname;
		$this->load->library('awssdk');
		$s3 = $this->awssdk->get_s3_instance();
		try {
			$s3->putObject([
				'Bucket' => 'gear6cdn',
				'Key'    => $to_file,
				'Body'   => fopen($from_file, 'r'),
				'ACL'    => 'public-read',
			]);
		} catch (Aws\Exception\S3Exception $e) {
		}
		unlink($from_file);
	}
	private function get_ulist($su_flag = FALSE) {
		$this->db->select('vendor.VendorId, vendor.VendorName, vendor.Pwd, vendor.PwdCheck, vendor.UserPrivilege, vendor.ScId, vendor.Phone');
		$this->db->from('vendor');
		$this->db->where('vendor.ScId', intval($this->session->userdata('v_sc_id')));
		if($su_flag) {
			$this->db->where('vendor.UserPrivilege', 'User');
		}
		$this->db->order_by('vendor.VendorId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach($results as &$result) {
				if(intval($result['PwdCheck']) == 1) {
					$result['Phone'] .= ' (One time pwd: ' . $result['Pwd'] . ')';
				}
			}
			return $results;
		}
	}
}