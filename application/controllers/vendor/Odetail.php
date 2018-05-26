<?php
class Odetail extends G6_Vendorcontroller {
	public function __construct() {
		parent::__construct();
	}
	public function index($oid = NULL) {
		redirect(site_url('vendor/odetail/show/' . $oid));
	}
	public function show($oid = NULL) {
		if($oid === NULL || !$this->is_valid_oid($oid)) {
			redirect(site_url('vendor'));
		} else {
			$this->get_nav_counts();
			$this->data['is_cancelled'] = $this->is_order_cancelled($oid);
			$this->populate_odetails($oid);
			$this->release_notify($oid);
			$temp = $this->db->select('FinalFlag')->from('odetails')->where('OId', $oid)->get()->row_array();
			$this->data['send_final_message'] = intval($temp['FinalFlag']);
			$this->load->model('vendor_m');
			$this->data['city_row'] = $this->vendor_m->get_city_row_by_vendor();
			$this->load->view('vendor/odetail', $this->data);
		}
	}
	public function send_thankyou() {
		if($_POST) {
			$oid = $this->input->post('oid');
			$this->load->model('odetails_m');
			$this->db->where('OId', $oid)->update('odetails', array('FinalFlag' => 1));
			$ph = $this->odetails_m->get_user_ph_by_oid($oid);
			$this->send_sms_request_to_api($ph, $this->input->post('thank_sms_txt'));
			redirect('/vendor/odetail/show/' . $oid);
		}
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
		redirect('/vendor/odetail/show/' . $this->input->post('opoid'));
	}
	public function changeStatus() {
		if(isset($_POST['changeStatus'])) {
			$data['OId'] = $this->input->post('oid');
			$data['StatusId'] = intval($this->input->post('servicetype'));
			$data['ScId'] = intval($this->session->userdata('v_sc_id'));
			$data['StatusDescription'] = $this->input->post('stat_desc_o') . $this->input->post('stat_desc_c');
			$data['ModifiedBy'] = $this->session->userdata('v_name');
			$this->load->model('statushistory_m');
			$this->statushistory_m->save($data);
			$this->db->where('OId', $data['OId']);
			$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
			$this->db->limit(1);
			$this->db->update('oservicedetail', array('StatusId' => $data['StatusId']));
			$this->load->model('tieups_m');
			$tieup = intval($this->tieups_m->get_business_by_oid($data['OId'])['TieupId']);
			$this->load->model('odetails_m');
			$ph = $this->odetails_m->get_user_ph_by_oid($data['OId']);
			if($data['StatusId'] == 1 || $data['StatusId'] == 8 || $data['StatusId'] == 15 || $data['StatusId'] == 18) {
				$this->db->where('OId', $data['OId']);
				$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
				$this->db->limit(1);
				$this->db->update('oservicedetail', array('isNotified' => 4));
				if($tieup != 2) {
					$this->send_sms_request_to_api($ph, 'Your service request ' . $data['OId'] . ' is approved by ' . convert_to_camel_case($this->session->userdata('v_sc_name')));
				} elseif($tieup == 2) {
					$this->send_hj_sms($ph, 'Your service request ' . $data['OId'] . ' is approved - housejoy.in');
				}
			}
			if($tieup != 2) {
				if($data['StatusId'] == 4) {
					$this->send_sms_request_to_api($ph, 'Your bike\'s maintenance checkup is done and the servicing will start soon.');
				}
				if($data['StatusId'] == 3) {
					$this->send_sms_request_to_api($ph, 'Your bike\'s Servicing has been started. Bike is setup on the rack to repair and revive.');
				}
				if($data['StatusId'] == 7) {
					$this->send_sms_request_to_api($ph, 'Your bike\'s Servicing has been finished. Bike is ready to be delivered.');
				}
				if($data['StatusId'] == 10) {
					$this->send_sms_request_to_api($ph, 'Your bike\'s checkup is done and the repair will be initiated soon.');
				}
				if($data['StatusId'] == 11) {
					$this->send_sms_request_to_api($ph, 'Your Bike\'s repair has been initiated, we will let you know when it is done.');
				}
				if($data['StatusId'] == 14) {
					$this->send_sms_request_to_api($ph, 'Your bike\'s repair has been finished. Bike is ready to be delivered.');
				}
				if($data['StatusId'] == 17) {
					$this->send_sms_request_to_api($ph, 'Your Query is answered, Hope you have got your answers. Thanks for Querying - gear6.in');
				}
				if($data['StatusId'] == 25) {
					$this->send_sms_request_to_api($ph, 'Your details checking is successfully done and they\'ll be updated for the renewal soon');
				}
				if($data['StatusId'] == 20) {
					$this->send_sms_request_to_api($ph, 'Your Bike\'s Insurance has been renewed Successfully, Thanks for booking the request - gear6.in');
				}
			} elseif($tieup == 2) {
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
			redirect(site_url('vendor/odetail/show/' . $data['OId']));
		} else {
			$data['OId'] = $this->input->post('oid');
			redirect(site_url('vendor/odetail/show/' . $data['OId']));
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
				$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
				$this->db->update('oservicedetail', $data);
				$this->load->model('odetails_m');
				$ph = $this->odetails_m->get_user_ph_by_oid($oid);
				$this->send_sms_request_to_api($ph, "Additional charges has been updated by the vendor for your order " . $oid . ". Login to gear6.in to approve the price.");
			}
			redirect(site_url('vendor/odetail/show/' . $oid));
		}
	}
	private function release_notify($oid) {
		$data = array('isNotified' => 1);
		$this->db->where('OId', $oid);
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('(isNotified = "0" OR isNotified = "3")');
		$this->db->update('oservicedetail', $data);
	}
	private function is_valid_oid($oid) {
		$this->db->select('COUNT(*)');
		$this->db->from('oservicedetail');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('OId', $oid);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		if($result['COUNT(*)'] == 1) {
			return TRUE;
		} else {
			return FALSE;
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
	private function populate_odetails($OId) {
		$this->load->model('odetails_m');
		$this->load->model('status_m');
		$this->load->model('statushistory_m');
		$this->data['stathists'] = $this->statushistory_m->get_status_history($OId);
		$this->data['OId'] = $OId;
		$this->data['omedia'] = $this->odetails_m->get_order_media($OId);
		$service_details = $this->odetails_m->get_stype_by_oid($OId);
		$this->data['stype'] = $service_details['ServiceName'];
		$this->data['serid'] = intval($service_details['ServiceId']);
		$sc_details = $this->odetails_m->get_scenter_by_oid($OId, TRUE);
		$sclatlong = $this->getSCLocation($sc_details[0]['ScId']);
		$this->data['SCLatitude'] = $sclatlong['Latitude'];
		$this->data['SCLongitude'] = $sclatlong['Longitude'];
		$this->data['scenter'] = $sc_details;
		$this->data['statuses'] = $this->status_m->get_statuses_for_service($service_details['ServiceId']);
		$this->data['rest_statuses'] = $this->status_m->get_statuses_for_service($service_details['ServiceId'], intval($sc_details[0]['Order']));
		$bike_model_details = $this->odetails_m->get_bm_by_oid($OId);
		$this->data['bikenumber'] = $bike_model_details['BikeNumber'];
		$this->data['bikemodel'] = $bike_model_details['BikeCompanyName'] . ' ' . $bike_model_details['BikeModelName'];
		$this->data['timeslot'] = $this->odetails_m->get_timeslot_by_oid($OId);
		$this->data['paymode'] = $this->odetails_m->get_paymode_by_oid($OId);
		$user_details = $this->odetails_m->get_user_address($OId);
		$this->data['uaddress'] = $user_details['address'];
		$this->data['uname'] = $user_details['name'];
		$this->data['uemail'] = $user_details['email'];
		$this->data['uphone'] = $user_details['Phone'];
		if ($this->data['serid'] == 4) {
			$this->data['insren_details'] = $this->odetails_m->get_insren_details($OId);
		}
		if ($this->data['serid'] != 3) {
			$this->load->model('amenity_m');
			$this->load->model('opaymtdetail_m');
			$this->data['chosen_amenities'] = $this->amenity_m->get_chosen_amenities($OId);
			$this->data['scaddress'] = $this->odetails_m->get_sc_address($sc_details[0]['ScId']);
			$this->data['estprices'] = $this->amenity_m->get_est_prices_by_oid($OId);
			$this->data['discprices'] = $this->amenity_m->get_est_prices_by_oid($OId, TRUE);
			$this->data['is_amt_cfmd'] = $this->odetails_m->is_amt_confirmed($OId);
			$this->data['oprices'] = $this->statushistory_m->get_oprices($OId);
			$this->data['ord_trans'] = $this->opaymtdetail_m->get_order_transactions($OId);
			$this->data['tot_paid'] = floatval($this->opaymtdetail_m->get_total_paid_amount($OId));
			$this->data['tot_billed'] = floatval($this->data['estprices'][count($this->data['estprices']) - 1]['ptotal']) + floatval($this->data['oprices'][count($this->data['oprices']) - 1]['ptotal']) - floatval($this->data['discprices'][count($this->data['discprices']) - 1]['ptotal']);
			$this->data['to_be_paid'] = round(floatval($this->data['tot_billed'] - $this->data['tot_paid']), 2);
			if($this->data['to_be_paid'] < 0.01 && $this->data['to_be_paid'] > -0.01) {
				$this->data['to_be_paid'] = 0;
			}
		} else {
			$this->data['scaddress'] = $this->odetails_m->get_sc_address($this->session->userdata('v_sc_id'));
		}
	}
}