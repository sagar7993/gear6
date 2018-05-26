<?php
class Odetail extends G6_Prvendorcontroller {
	public function __construct() {
		parent::__construct();
	}
	public function index($oid = NULL) {
		redirect(site_url('prvendor/odetail/show/' . $oid));
	}
	public function show($oid = NULL) {
		if($oid === NULL || !$this->is_valid_oid($oid)) {
			redirect(site_url('prvendor'));
		} else {
			$this->get_nav_counts();
			$this->data['is_cancelled'] = $this->is_order_cancelled($oid);
			$this->populate_odetails($oid);
			$temp = $this->db->select('FinalFlag')->from('odetails')->where('OId', $oid)->get()->row_array();
			$this->data['send_final_message'] = intval($temp['FinalFlag']);
			$this->load->view('prvendor/odetail', $this->data);
		}
	}
	public function send_thankyou() {
		if($_POST) {
			$oid = $this->input->post('oid');
			$this->load->model('odetails_m');
			$this->db->where('OId', $oid)->update('odetails', array('FinalFlag' => 1));
			$ph = $this->odetails_m->get_user_ph_by_oid($oid);
			$this->send_sms_request_to_api($ph, $this->input->post('thank_sms_txt'));
			redirect('/prvendor/odetail/show/' . $oid);
		}
	}
	private function is_valid_oid($oid) {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
		$this->db->where('odetails.OId', $oid);
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
	private function populate_odetails($OId) {
		$this->load->model('odetails_m');
		$this->load->model('status_m');
		$this->load->model('statushistory_m');
		$this->data['OId'] = $OId;
		$this->data['omedia'] = $this->odetails_m->get_order_media($OId);
		$service_details = $this->odetails_m->get_stype_by_oid($OId);
		$this->data['stype'] = $service_details['ServiceName'];
		$this->data['serid'] = intval($service_details['ServiceId']);
		$sc_details = $this->odetails_m->get_scenter_by_oid($OId);
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
			$this->data['scaddress'] = $this->odetails_m->get_sc_address($this->session->userdata('v_sc_id'));
		}
	}
}