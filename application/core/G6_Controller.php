<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class G6_Controller extends CI_Controller {
	public $data = array();
	public function __construct() {
		parent::__construct();
		$this->data['meta_title'] = 'gear6.in';
		$this->load->helper('cookie');
		$this->load->driver('session');
		$this->data['errors'] = array();
		$this->data['site_name'] = config_item('site_name');
	}
	protected function generate_payment_form($usr_id, $OId, $price) {
		$this->load->model('opaymtdetail_m');
		$payu = array();
		$payu['surl'] = site_url('user/result/showStatus/' . $OId);
		$payu['furl'] = site_url('user/result/showStatus/' . $OId);
		$payu['curl'] = site_url('user/result/showStatus/' . $OId);
		if($this->input->post('paymt') != '' && $this->input->post('paymt') !== NULL) {
			$payu['pg'] = $this->input->post('paymt');
		} else {
			$payu['pg'] = 'CC';
		}
		$payu['productinfo'] = $OId;
		$payu['amount'] = floatval($price);
		$payu['key'] = "uKbmvv";
		$payu['salt'] = "KsWqIoqB";
		if ($this->data['is_logged_in'] == 0) {
			$payu['firstname'] = $this->input->post('full_name');
			$payu['email'] = $this->input->post('email');
			$payu['phone'] = $this->input->cookie('phone');
		} else {
			$payu['firstname'] = $this->session->userdata('name');
			$payu['email'] = $this->session->userdata('email');
			$payu['phone'] = $this->session->userdata('phone');
		}
		$payu['txnid'] = $this->opaymtdetail_m->create_trxn($usr_id, $OId, $price);
		$payu['hash'] = '';
		$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|||||";
		$hashVarsSeq = explode('|', $hashSequence);
		$hash_string = '';
		foreach($hashVarsSeq as $hash_var) {
			$hash_string .= isset($payu[$hash_var]) ? $payu[$hash_var] : '';
			$hash_string .= '|';
		}
		$hash_string .= $payu['salt'];
		$payu['hash'] = strtolower(generate_hash($hash_string));
		$secret_payu_form = '<form method="POST" action="https://secure.payu.in/_payment">
			<input type="hidden" name="key" value="' . $payu['key'] . '" />
			<input type="hidden" name="hash" value="' . $payu['hash'] . '"/>
			<input type="hidden" name="txnid" value="' . $payu['txnid'] . '" />
			<input type="hidden" name="amount" value="' . $payu['amount'] . '" />
			<input type="hidden" name="firstname" value="' . $payu['firstname'] . '" />
			<input type="hidden" name="email" value="' . $payu['email'] . '" />
			<input type="hidden" name="phone" value="' . $payu['phone'] . '" />
			<input type="hidden" name="productinfo" value="' . $payu['productinfo'] . '" />
			<input type="hidden" name="surl" value="' . $payu['surl'] . '" />
			<input type="hidden" name="furl" value="' . $payu['furl'] . '" />
			<input type="hidden" name="curl" value="' . $payu['curl'] . '" />
			<input type="hidden" name="pg" value="' . $payu['pg'] . '" />
			<input type="hidden" name="service_provider" value="payu_paisa" />
		</form>';
		return $secret_payu_form;
	}
	protected function is_transaction_valid($response) {
		$status = $response["status"];
		$firstname = $response["firstname"];
		$amount = $response["amount"];
		$txnid = $response["txnid"];
		$posted_hash = $response["hash"];
		$key = $response["key"];
		$productinfo = $response["productinfo"];
		$email = $response["email"];
		$salt = "KsWqIoqB";
		if (isset($response["additionalCharges"])) {
			$additionalCharges = $response["additionalCharges"];
			$retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
		} else {
			$retHashSeq = $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
		}
		$hash = strtolower(generate_hash($retHashSeq));
		if ($hash != $posted_hash) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	protected function send_gcm_request(&$gcm_ids, &$msg_array) {
		$apiKey = "AIzaSyCJAZ8XEe77EEImcMfeeWVyW7KTAG1CwAM";
		$url = 'https://android.googleapis.com/gcm/send';
		$fields = array(
			'registration_ids' => $gcm_ids,
			'data' => $msg_array,
		);
		$headers = array(
			'Authorization: key=' . $apiKey,
			'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		curl_exec($ch);
		curl_close($ch);
	}
	protected function get_all_active_admin_devices() {
		$tempids = $this->db->select('admin.GCMId')->from('admin')->where('admin.GCMId <> ', NULL)->get()->result();
		foreach($tempids as $temp) {
			if(isset($temp->GCMId)) {
				$gcmids[] = strval($temp->GCMId);
			}
		}
		return $gcmids;
	}
	protected function get_all_assigned_executive_devices($OId) {
		$this->db->select('executive.GCMId')->from('executive');
		$this->db->join('execassigns', 'execassigns.ExecId = executive.ExecId');
		$this->db->join('odetails', 'odetails.OId = execassigns.OId');
		$this->db->where('odetails.OId', $OId);
		$this->db->where('executive.GCMId <> ', NULL);
		$tempids = $this->db->get()->result(); $gcmids = array();
		foreach($tempids as $temp) {
			if(isset($temp->GCMId)) {
				$gcmids[] = strval($temp->GCMId);
			}
		}
		return $gcmids;
	}
	protected function get_all_assigned_executive_numbers($OId) {
		$this->db->select('executive.Phone')->from('executive');
		$this->db->join('execassigns', 'execassigns.ExecId = executive.ExecId');
		$this->db->join('odetails', 'odetails.OId = execassigns.OId');
		$this->db->where('odetails.OId', $OId);
		$tempids = $this->db->get()->result(); $phone = array();
		foreach($tempids as $temp) {
			if(isset($temp->Phone)) {
				$phone[] = strval($temp->Phone);
			}
		}
		return $phone;
	}
	protected function get_all_assigned_executive_ids($OId) {
		$this->db->select('executive.ExecId')->from('executive');
		$this->db->join('execassigns', 'execassigns.ExecId = executive.ExecId');
		$this->db->join('odetails', 'odetails.OId = execassigns.OId');
		$this->db->where('odetails.OId', $OId);
		$tempids = $this->db->get()->result(); $exec = array();
		foreach($tempids as $temp) {
			if(isset($temp->ExecId)) {
				$exec[] = strval($temp->ExecId);
			}
		}
		return $exec;
	}
	protected function send_gear6_email($to, $sub, $vname, $data) {
		/*$this->load->library('awssdk');
		$ses = $this->awssdk->get_ses_instance();
		$ses->sendEmail(array(
			'Source' => 'gear6.in <do-not-reply@gear6.in>',
			'Destination' => array(
				'ToAddresses' => array($to)
			),
			'Message' => array(
				'Subject' => array(
					'Data' => $sub,
					'Charset' => 'UTF-8',
				),
				'Body' => array(
					'Html' => array(
						'Data' => $this->load->view('emails/' . $vname, $data, TRUE),
						'Charset' => 'UTF-8',
					),
				),
			),
			'ReplyToAddresses' => array('support@gear6.in')
		));*/
	}
	protected function send_gear6_txt_email($from, $to, $sub, $msg) {
		/*$this->load->library('awssdk');
		$ses = $this->awssdk->get_ses_instance();
		$ses->sendEmail(array(
			'Source' => 'gear6.in <do-not-reply@gear6.in>',
			'Destination' => array(
				'ToAddresses' => array($to)
			),
			'Message' => array(
				'Subject' => array(
					'Data' => $sub,
					'Charset' => 'UTF-8',
				),
				'Body' => array(
					'Text' => array(
						'Data' => $msg,
						'Charset' => 'UTF-8',
					),
				),
			),
			'ReplyToAddresses' => array($from)
		));*/
	}
	protected function send_sms_request_to_api($ph, $msg) {
		/*$authKey = '92961AEtGLkzjq55febded';
		$api_url = 'https://control.msg91.com/api/sendhttp.php?authkey=' . $authKey . '&mobiles=' . $ph . '&message=' . urlencode($msg) . '&sender=GEARSX&route=4&country=91&unicode=0';
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $api_url);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl_handle, CURLOPT_POST, FALSE);
		curl_setopt($curl_handle, CURLOPT_HEADER, FALSE);
		curl_setopt($curl_handle, CURLOPT_USERAGENT, 'gear6.in');
		curl_exec($curl_handle);
		curl_close($curl_handle);*/
	}
	protected function send_hj_sms($ph, $msg) {
		/*$authKey = '92961AEtGLkzjq55febded';
		$api_url = 'https://control.msg91.com/api/sendhttp.php?authkey=' . $authKey . '&mobiles=' . $ph . '&message=' . urlencode($msg) . '&sender=HSEJOY&route=4&country=91&unicode=0';
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $api_url);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl_handle, CURLOPT_POST, FALSE);
		curl_setopt($curl_handle, CURLOPT_HEADER, FALSE);
		curl_setopt($curl_handle, CURLOPT_USERAGENT, 'gear6.in');
		curl_exec($curl_handle);
		curl_close($curl_handle);*/
	}
	protected function send_rzpay_api_request($uri, $params = array()) {
		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_USERPWD, "rzp_live_1SU5AHKZFVCfcO:qax2AnVR6bWgPO7zLRV2hnrU");
		curl_setopt($curl_handle, CURLOPT_URL, $uri);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($curl_handle, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, FALSE);
		if(count($params) > 0) {
			curl_setopt($curl_handle, CURLOPT_POST, TRUE);
			curl_setopt($curl_handle, CURLOPT_POSTFIELDS, http_build_query($params));
		} else {
			curl_setopt($curl_handle, CURLOPT_POST, FALSE);
		}
		curl_setopt($curl_handle, CURLOPT_USERAGENT, 'gear6.in');
		$content = curl_exec($curl_handle);
		curl_close($curl_handle);
		return json_decode($content, TRUE);
	}
	protected function initiate_download_headers($filename) {
		$now = gmdate("D, d M Y H:i:s");
		header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
		header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
		header("Last-Modified: {$now} GMT");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename={$filename}");
		header("Content-Transfer-Encoding: binary");
	}
}
class G6_Admincontroller extends G6_Controller {
	public function __construct() {
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		$this->load->model('admin_m');
		if (isset($_POST['login']) && $this->input->post('password') != '') {
			$this->admin_m->login();
			redirect(current_url());
		}
		if ($this->admin_m->loggedin() == TRUE) {
			$this->data['a_is_logged_in'] = 1;
			$this->data['admin_city_id'] = intval($this->session->userdata('a_city_id'));
			$this->data['sadmin_city_id'] = intval($this->admin_m->get(intval($this->session->userdata('a_id')))->CityId);
		} else {
			$this->data['a_is_logged_in'] = 0;
			$this->data['open_login_modal'] = 1;
			if ($this->input->cookie('login_errors') != '') {
				$this->data['login_error_message'] = $this->input->cookie('login_errors');
				delete_cookie('login_errors');
			}
		}
		$this->load->library('aauth');
		$this->data['denied_pages'] = $this->aauth->get_denied_pages();
		$this->data['denied_uris'] = $this->aauth->get_denied_uris();
		$this->data['denied_secs'] = $this->aauth->get_denied_sections();
	}
	protected function get_order_nav_counts() {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('FinalFlag', 0);
		$this->db->where('isGrievance', 1);
		$this->db->where('status.Order >=', 0);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_grievance_count'] = intval($result['COUNT(*)']);
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId');
		$this->db->join('ofupstatus', 'ofupstatus.OId = odetails.OId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.FeedbackCallReminderFlag', 0);
		$this->db->where('odetails.FinalFlag', 1);
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('ofupstatus.FupStatusId', 17);
		$this->db->where('DATE(ofupstatus.Timestamp) <=', date("Y-m-d", strtotime("now - 2 days")));
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_feedback_reminders_count'] = intval($result['COUNT(*)']);
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.LastFupStatusId !=', 22);
		$this->db->where('odetails.LastFupStatusId !=', 24);
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('DATE(odetails.service_reminder_date) <=', date("Y-m-d", strtotime("now + 15 days")));
		$query = $this->db->get();
		$result = $query->row_array();
		$service_reminder_count_1 = intval($result['COUNT(*)']);
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.LastFupStatusId !=', 22);
		$this->db->where('odetails.LastFupStatusId !=', 24);
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('DATE(odetails.insurance_renewal_date) <=', date("Y-m-d", strtotime("now + 15 days")));
		$query = $this->db->get();
		$result = $query->row_array();
		$service_reminder_count_2 = intval($result['COUNT(*)']);
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.LastFupStatusId !=', 22);
		$this->db->where('odetails.LastFupStatusId !=', 24);
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('DATE(odetails.puc_renewal_date) <=', date("Y-m-d", strtotime("now + 15 days")));
		$query = $this->db->get();
		$result = $query->row_array();
		$service_reminder_count_3 = intval($result['COUNT(*)']);
		$this->data['nav_service_reminders_count'] = $service_reminder_count_1 + $service_reminder_count_2 + $service_reminder_count_3;
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('status.Order', 1);
		$this->db->where('service.ServiceId !=', 3);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_allotted_count'] = $result['COUNT(*)'];
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('odetails.ServiceId', 3);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_queried_count'] = $result['COUNT(*)'];
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where("(odetails.FinalFlag = '0'", NULL, FALSE);
		$this->db->or_where("odetails.InvoiceUpdated = '0')", NULL, FALSE);
		$this->db->where("(oservicedetail.StatusId = '7'", NULL, FALSE);
		$this->db->or_where("oservicedetail.StatusId = '14')", NULL, FALSE);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_serviced_count'] = $result['COUNT(*)'];
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('status.Order', 0);
		$this->db->where('service.ServiceId !=', 3);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_unallotted_count'] = $result['COUNT(*)'];
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('service.ServiceId !=', 3);
		$this->db->where('odetails.ODate <=', date("Y-m-d", strtotime("now")));
		$this->db->where("((status.ServiceId = '1' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '2' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '4' AND status.Order < '3' AND status.Order >= '0'))", NULL, FALSE);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_upcoming_count'] = $result['COUNT(*)'];
	}
}
class G6_Appcontroller extends G6_Controller {
	public $appresponse = array();
	public function __construct() {
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		$this->output->set_header('Access-Control-Allow-Origin: *');
		$this->output->set_header('Access-Control-Expose-Headers: Access-Control-Allow-Origin');
		$this->output->set_header('Access-Control-Allow-Headers: origin, x-requested-with, x-source-ip, Accept, Authorization, User-Agent, Host, Accept-Language, Location, Referer, access-control-allow-origin, Access-Control-Allow-Headers, Content-Type');
		$this->output->set_header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
		$this->output->set_header('Access-Control-Allow-Credentials: true');
		$this->output->set_content_type('application/json');
		$this->load->helper('form');
		$this->load->model('city_m');
		$this->load->model('user_m');
		$this->load->model('appdata_m');
	}
}
class G6_Usercontroller extends G6_Controller {
	public function __construct() {
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		$this->load->helper('form');
		$this->load->model('city_m');
		$this->load->model('user_m');
		if($this->input->cookie('oid') != "" && $this->input->cookie('is_new_order') == 1) {
			if($this->uri->segment(3) != 'showStatus' && $this->uri->segment(3) != 'showRZPayStatus' && $this->uri->segment(3) != 'showInterruptedOrder' && $this->uri->segment(2) != 'unotify') {
				redirect(site_url('user/result/showInterruptedOrder'));
			}
		}
		if ($this->city_m->iscityset()) {
			$this->data['city_name'] = $this->city_m->get(intval($this->input->cookie('CityId')))->CityName;
			$this->data['city_id'] = intval($this->input->cookie('CityId'));
		} else {
			$this->city_m->set_city(1);
			$this->data['city_name'] = $this->city_m->get(1)->CityName;
			$this->data['city_id'] = 1;
		}
		if ($this->user_m->loggedin() == TRUE) {
			$this->data['is_logged_in'] = 1;
			if ($this->user_m->is_first_time()) {
				$this->data['is_first_login'] = 1;
			}
			if($this->input->cookie('is_referred_by_id') != "") {
				delete_cookie('is_referred_by_id');
			}
		} else {
			$this->data['is_logged_in'] = 0;
			if ($this->input->cookie('login_errors') != '') {
				$this->data['open_login_modal'] = 1;
				$this->data['login_error_message'] = $this->input->cookie('login_errors');
				delete_cookie('login_errors');
			}
		}
	}
}
class G6_Execcontroller extends G6_Controller {
	public function __construct() {
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		$this->load->helper('form');
		$this->load->model('executive_m');
		$exec_allowed_urls = array(
			'executive/exechome',
			'executive/exechome/elogin',
			'executive'
		);
		if ($this->executive_m->loggedin() == TRUE) {
			$this->data['ex_is_logged_in'] = 1;
		} else {
			$this->data['ex_is_logged_in'] = 0;
			if (in_array(uri_string(), $exec_allowed_urls) == FALSE) {
				redirect('/executive');
			}
		}
	}
}
class G6_Execappcontroller extends G6_Controller {
	public function __construct() {
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		$this->load->helper('form');
		$this->load->model('executive_m');
	}
}
class G6_Adminappcontroller extends G6_Controller {
	public function __construct() {
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		$this->load->helper('form');
		$this->load->model('admin_m');
	}
}
class G6_Vendorcontroller extends G6_Controller {
	public function __construct() {
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		$this->load->helper('form');
		$this->load->model('vendor_m');
		if ($this->vendor_m->loggedin() == TRUE) {
			$this->data['v_is_logged_in'] = 1;
			$this->data['v_sc_location'] = $this->vendor_m->get_vsc_location();
			$this->data['sc_logo'] = $this->session->userdata('v_sc_logo');
			if ($this->vendor_m->is_first_time()) {
				$this->data['v_is_first_time'] = 1;
			}
		} else {
			$this->data['v_is_logged_in'] = 0;
			redirect('/home/vlogin');
		}
		$this->load->library('auth');
		$vendor_dashboard_urls = array(
			'vendor',
			'vendor/vendorhome',
			'vendor/allotted',
			'vendor/archived',
			'vendor/queried',
			'vendor/serviced',
			'vendor/unallotted',
			'vendor/upcoming'
		);
		if (in_array(uri_string(), $vendor_dashboard_urls) == TRUE) {
			$this->get_nav_counts();
		}
		if ($this->vendor_m->loggedin() == TRUE && isset($_POST['pwdreset']) && $this->input->post('pswd1') != '') {
			$this->vendor_m->reset_password($this->input->post('pswd1'), $this->input->post('pswd2'));
			redirect(current_url());
		}
	}
	protected function get_nav_counts() {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('status.Order', 1);
		$this->db->where('service.ServiceId !=', 3);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_allotted_count'] = $result['COUNT(*)'];
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId');
		$this->db->where('oservicedetail.ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('odetails.ServiceId', 3);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_queried_count'] = $result['COUNT(*)'];
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where("(odetails.FinalFlag = '0')", NULL, FALSE);
		$this->db->where("(oservicedetail.StatusId = '7'", NULL, FALSE);
		$this->db->or_where("oservicedetail.StatusId = '14')", NULL, FALSE);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_serviced_count'] = $result['COUNT(*)'];
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('status.Order', 0);
		$this->db->where('service.ServiceId !=', 3);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_unallotted_count'] = $result['COUNT(*)'];
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('service.ServiceId !=', 3);
		$this->db->where('odetails.ODate <=', date("Y-m-d", strtotime("now")));
		$this->db->where("((status.ServiceId = '1' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '2' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '4' AND status.Order < '3' AND status.Order >= '0'))", NULL, FALSE);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_upcoming_count'] = $result['COUNT(*)'];
	}
}
class G6_Bizcontroller extends G6_Controller {
	public function __construct() {
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		$this->load->helper('form');
		$this->load->model('tieups_m');
		if ($this->tieups_m->loggedin() == TRUE) {
			$this->data['b_is_logged_in'] = 1;
			$this->data['b_logo'] = $this->session->userdata('b_logo');
			if ($this->tieups_m->is_first_time()) {
				$this->data['b_is_first_time'] = 1;
			}
		} else {
			$this->data['b_is_logged_in'] = 0;
			redirect('/home/blogin');
		}
		$this->load->library('bauth');
		$biz_dashboard_urls = array(
			'business',
			'business/bizhome',
			'business/allotted',
			'business/archived',
			'business/queried',
			'business/serviced',
			'business/unallotted',
			'business/upcoming'
		);
		if (in_array(uri_string(), $biz_dashboard_urls) == TRUE) {
			$this->get_nav_counts();
		}
		if ($this->tieups_m->loggedin() == TRUE && isset($_POST['pwdreset']) && $this->input->post('pswd1') != '') {
			$this->tieups_m->reset_password($this->input->post('pswd1'), $this->input->post('pswd2'));
			redirect(current_url());
		}
	}
	protected function get_nav_counts() {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where('TieupId', intval($this->session->userdata('biz_id')));
		$this->db->where('status.Order', 1);
		$this->db->where('service.ServiceId !=', 3);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_allotted_count'] = $result['COUNT(*)'];
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->where('TieupId', intval($this->session->userdata('biz_id')));
		$this->db->where('odetails.ServiceId', 3);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_queried_count'] = $result['COUNT(*)'];
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where('TieupId', intval($this->session->userdata('biz_id')));
		$this->db->where("(odetails.FinalFlag = '0')", NULL, FALSE);
		$this->db->where("(oservicedetail.StatusId = '7'", NULL, FALSE);
		$this->db->or_where("oservicedetail.StatusId = '14')", NULL, FALSE);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_serviced_count'] = $result['COUNT(*)'];
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where('TieupId', intval($this->session->userdata('biz_id')));
		$this->db->where('status.Order', 0);
		$this->db->where('service.ServiceId !=', 3);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_unallotted_count'] = $result['COUNT(*)'];
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where('TieupId', intval($this->session->userdata('biz_id')));
		$this->db->where('service.ServiceId !=', 3);
		$this->db->where('odetails.ODate <=', date("Y-m-d", strtotime("now")));
		$this->db->where("((status.ServiceId = '1' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '2' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '4' AND status.Order < '3' AND status.Order >= '0'))", NULL, FALSE);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_upcoming_count'] = $result['COUNT(*)'];
	}
}
class G6_Prvendorcontroller extends G6_Controller {
	public function __construct() {
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
		$this->load->helper('form');
		$this->load->model('prvendor_m');
		if ($this->prvendor_m->loggedin() == TRUE) {
			$this->data['prv_loggedin'] = 1;
			if ($this->prvendor_m->is_first_time()) {
				$this->data['prv_is_first_time'] = 1;
			}
		} else {
			$this->data['prv_loggedin'] = 0;
			redirect('/home/prlogin');
		}
		$this->load->library('prauth');
		$biz_dashboard_urls = array(
			'prvendor',
			'prvendor/bizhome',
			'prvendor/allotted',
			'prvendor/archived',
			'prvendor/queried',
			'prvendor/serviced',
			'prvendor/unallotted',
			'prvendor/upcoming'
		);
		if (in_array(uri_string(), $biz_dashboard_urls) == TRUE) {
			$this->get_nav_counts();
		}
		if ($this->prvendor_m->loggedin() == TRUE && isset($_POST['pwdreset']) && $this->input->post('pswd1') != '') {
			$this->prvendor_m->reset_password($this->input->post('pswd1'), $this->input->post('pswd2'));
			redirect(current_url());
		}
	}
	protected function get_nav_counts() {
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
		$this->db->where('status.Order', 1);
		$this->db->where('service.ServiceId !=', 3);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_allotted_count'] = $result['COUNT(*)'];
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
		$this->db->where('odetails.ServiceId', 3);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_queried_count'] = $result['COUNT(*)'];
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
		$this->db->where("(odetails.FinalFlag = '0')", NULL, FALSE);
		$this->db->where("(oservicedetail.StatusId = '7'", NULL, FALSE);
		$this->db->or_where("oservicedetail.StatusId = '14')", NULL, FALSE);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_serviced_count'] = $result['COUNT(*)'];
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
		$this->db->where('status.Order', 0);
		$this->db->where('service.ServiceId !=', 3);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_unallotted_count'] = $result['COUNT(*)'];
		$this->db->select('COUNT(*)');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->where_in('oservicedetail.ScId', explode(", ", $this->session->userdata('prv_sc_ids')));
		$this->db->where('service.ServiceId !=', 3);
		$this->db->where('odetails.ODate <=', date("Y-m-d", strtotime("now")));
		$this->db->where("((status.ServiceId = '1' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '2' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '4' AND status.Order < '3' AND status.Order >= '0'))", NULL, FALSE);
		$query = $this->db->get();
		$result = $query->row_array();
		$this->data['nav_upcoming_count'] = $result['COUNT(*)'];
	}
}