<?php
class Manageexecutive extends G6_Admincontroller {
	public function __construct() {
		parent::__construct();
		$this->data['page'] = 'manageExecutive';
		$this->aauth->check_page_access('manageExecutive');
	}
	public function index() {
		$this->data['active'] = 'add_executive';
		$this->load->model('city_m');
		$this->data['cities'] = $this->city_m->get_by(array('isEnabled' => 1));
		$this->load->view('admin/addexecutive', $this->data);
	}
	public function editExecutive() {
		$this->data['rows'] = $this->get_executive_list();
		$this->data['active'] = 'edit_executive';
		$this->load->model('city_m');
		$this->data['cities'] = $this->city_m->get_by(array('isEnabled' => 1));
		$this->load->view('admin/editexecutive', $this->data);
	}
	public function delete_executive() {
		if($_POST) {
			$this->load->model('executive_m');
			$executiveId = intval($this->input->post('delete_executive_id'));
			$this->executive_m->delete($executiveId);
			redirect(site_url('admin/manageexecutive/editexecutive'));
		}
	}
	public function modify_executive() {
		if($_POST) {
			$this->load->model('executive_m');
			$executiveData['ExecName'] = $this->input->post('new_name');
			$executiveData['Phone'] = $this->input->post('new_phone');
			$executiveData['Email'] = $this->input->post('new_email');
			$executiveData['DOB'] = $this->input->post('new_dob');
			$executiveData['Gender'] = $this->input->post('new_gender');
			if(intval($this->session->userdata('a_city_id')) > 0) {
				$executiveData['CityId'] = intval($this->session->userdata('a_city_id'));
			} else {
				$executiveData['CityId'] = $this->input->post('new_city_id');
			}
			$executiveData['isActive'] = intval($this->input->post('new_isActive'));
			if($this->input->post('new_password') && $this->input->post('new_password') != "" && $this->input->post('new_password') != NULL && strlen($this->input->post('new_password')) > 0) {
				$executiveData['Salt'] = generate_hash(generateUniqueString(8));
				$executiveData['Pwd'] = generate_salted_hash($this->input->post('new_password'), $executiveData['Salt']);
			}
			if (function_exists('date_default_timezone_set')) {
			  date_default_timezone_set('Asia/Kolkata');
			}
			$executiveData['Timestamp'] = date("Y-m-d h:i:s");
			if($this->executive_m->is_own_ph($executiveData['Phone'], intval($this->input->post('e_id')))) {
				$this->executive_m->save($executiveData, intval($this->input->post('e_id')));
			} else {
				$this->data['err_phone'] = "This phone is already registered with another executive. Please double check.";
			}
			redirect(site_url('admin/manageexecutive/editexecutive'));
		}
	}
	public function create_executive() {
		if($_POST) {
			$this->load->model('executive_m');
			$fields = array("city_id", "fname", "phone", "email", "dob", "gender");
			$data_fields = array('CityId', 'ExecName', 'Phone', 'Email', 'DOB', 'Gender');
			$count = 0;
			$executiveData = array();
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
				$executiveData[$data_fields[$count]] = $this->input->post($field);
				$count += 1;
			}
			$executiveData['isActive'] = intval($this->input->post('isActive'));
			$executiveData['Salt'] = generate_hash(generateUniqueString(8));
			$executiveData['Pwd'] = generate_salted_hash($this->input->post('password'), $executiveData['Salt']);
			if (function_exists('date_default_timezone_set')) {
				date_default_timezone_set('Asia/Kolkata');
			}
			$executiveData['Timestamp'] = date("Y-m-d h:i:s");
			if($this->executive_m->is_unique_ph($executiveData['Phone']) && $test) {
				$this->executive_m->save($executiveData);
				redirect(site_url('admin/manageexecutive/editexecutive'));
			} else {
				redirect(site_url('admin/manageexecutive'));
			}
		}
	}
	private function get_executive_list() {
		$sql = "SELECT executive.*, COALESCE(ROUND(payment.wallet, 2), 0) AS wallet, COALESCE(ROUND((COALESCE(rewardcredits.crewards, 0) - COALESCE(rewarddebits.drewards, 0)), 2), 0) AS rewards FROM executive ";
		$sql .= "LEFT JOIN (SELECT SUM(opaymtdetail.PaymtAmt) AS wallet, execbill.ExecId FROM executive ";
		$sql .= "LEFT JOIN execbill ON execbill.ExecId = executive.ExecId ";
		$sql .= "INNER JOIN opaymtdetail ON opaymtdetail.OId = execbill.OId WHERE execbill.isCashSubmitted = '0' AND opaymtdetail.PaymtId = '3' GROUP BY executive.ExecId) AS payment ON executive.ExecId = payment.ExecId ";
		$sql .= "LEFT JOIN (SELECT SUM(execrewards.Amount) AS crewards, executive.ExecId FROM executive ";
		$sql .= "INNER JOIN execrewards ON execrewards.ExecId = executive.ExecId WHERE execrewards.isCleared = '0' AND execrewards.Type = 'Credit' GROUP BY executive.ExecId) AS rewardcredits ON rewardcredits.ExecId = executive.ExecId ";
		$sql .= "LEFT JOIN (SELECT SUM(execrewards.Amount) AS drewards, executive.ExecId FROM executive ";
		$sql .= "INNER JOIN execrewards ON execrewards.ExecId = executive.ExecId WHERE execrewards.isCleared = '0' AND execrewards.Type = 'Debit' GROUP BY executive.ExecId) AS rewarddebits ON rewarddebits.ExecId = executive.ExecId";
		return $this->db->query($sql)->result_array();
	}
	public function petrol_claims() {
		if($_POST) {
			$this->data['startDate'] = $_POST['startDate']; $this->data['endDate'] = $_POST['endDate'];
			$this->data['rows'] = $this->get_petrol_claim_list($_POST['startDate'], $_POST['endDate']);
		} else {
			$this->data['rows'] = $this->get_petrol_claim_list(NULL, NULL);
		}
		$this->data['active'] = 'petrol_claims';
		$this->load->view('admin/petrolclaims', $this->data);
	}
	public function petrol_claim_status() {
		if($_POST) {
			$petrolBillsId = $_POST['PetrolBillsId'];
			$pb = $this->db->select('isApproved, Price, ExecId')->from('petrolbills')->where('PetrolBillsId', $petrolBillsId)->get()->result_array()[0];
			$status = intval($pb['isApproved']); $price = $pb['Price']; $ex = intval($pb['ExecId']);
			if($status == 0) {
				if($_POST['IsApproved'] == 1) {
					$reward = array(); $reward['ExecId'] = $ex; $reward['Amount'] = $price; $reward["Type"] = 'Credit';
					$reward['updated_at'] = date("Y-m-d", strtotime("now")); $reward["isCleared"] = 0; $reward["ClearFrequency"] = 1;
					if(isset($_POST['DeniedReason'])) { $reward['Description'] = $_POST['DeniedReason']; } else { $reward['Description'] = "Your rewards wallet is credited by Rs. " . $price; }
					$reward['UpdatedBy'] = $this->session->userdata('a_name'); $this->db->insert('execrewards', $reward);
					$phone = $this->db->select('Phone')->from('executive')->where('ExecId', $ex)->get()->result_array()[0]['Phone'];
					$this->send_sms_request_to_api($phone, $reward['Description']);
					$and_reg_ids = array(); $tempids = $this->db->select('executive.GCMId')->from('executive')->where('executive.GCMId <> ', NULL)->where('executive.ExecId', $ex)->get()->result();
					foreach($tempids as $t) { if(isset($t->GCMId)) { $and_reg_ids[] = strval($t->GCMId); } }
					$and_push_msg_data = array("title" => "Petrol Claim Was Approved", "message" => $reward['Description'], "screen" => "reward");
					$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
				} elseif($_POST['IsApproved'] == 2) {
					// Do Nothing
				}
			} elseif($status == 1) {
				if($_POST['IsApproved'] == 1) {
					// Do Nothing
				} elseif($_POST['IsApproved'] == 2) {
					$reward = array(); $reward['ExecId'] = $ex; $reward['Amount'] = $price; $reward["Type"] = 'Debit';
					$reward['updated_at'] = date("Y-m-d", strtotime("now")); $reward["isCleared"] = 0; $reward["ClearFrequency"] = 1;
					if(isset($_POST['DeniedReason'])) { $reward['Description'] = $_POST['DeniedReason']; } else { $reward['Description'] = "Your rewards wallet is debited by Rs. " . $price; }
					$reward['UpdatedBy'] = $this->session->userdata('a_name'); $this->db->insert('execrewards', $reward);
					$phone = $this->db->select('Phone')->from('executive')->where('ExecId', $ex)->get()->result_array()[0]['Phone'];
					$this->send_sms_request_to_api($phone, $reward['Description']);
					$and_reg_ids = array(); $tempids = $this->db->select('executive.GCMId')->from('executive')->where('executive.GCMId <> ', NULL)->where('executive.ExecId', $ex)->get()->result();
					foreach($tempids as $t) { if(isset($t->GCMId)) { $and_reg_ids[] = strval($t->GCMId); } }
					$and_push_msg_data = array("title" => "Approved Petrol Claim Rejected", "message" => $reward['Description'], "screen" => "reward");
					$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
				}
			} elseif($status == 2) {
				if($_POST['IsApproved'] == 1) {
					$reward = array(); $reward['ExecId'] = $ex; $reward['Amount'] = $price; $reward["Type"] = 'Credit';
					$reward['updated_at'] = date("Y-m-d", strtotime("now")); $reward["isCleared"] = 0; $reward["ClearFrequency"] = 1;
					if(isset($_POST['DeniedReason'])) { $reward['Description'] = $_POST['DeniedReason']; } else { $reward['Description'] = "Your rewards wallet is credited by Rs. " . $price; }
					$reward['UpdatedBy'] = $this->session->userdata('a_name'); $this->db->insert('execrewards', $reward);
					$phone = $this->db->select('Phone')->from('executive')->where('ExecId', $ex)->get()->result_array()[0]['Phone'];
					$this->send_sms_request_to_api($phone, $reward['Description']);
					$and_reg_ids = array(); $tempids = $this->db->select('executive.GCMId')->from('executive')->where('executive.GCMId <> ', NULL)->where('executive.ExecId', $ex)->get()->result();
					foreach($tempids as $t) { if(isset($t->GCMId)) { $and_reg_ids[] = strval($t->GCMId); } }
					$and_push_msg_data = array("title" => "Petrol Claim Was Approved", "message" => $reward['Description'], "screen" => "reward");
					$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
				} elseif($_POST['IsApproved'] == 2) {
					// Do Nothing
				}
			}
			$pclaim['isApproved'] = $_POST['IsApproved'];
			if(isset($_POST['DeniedReason'])) {
				$pclaim['DeniedReason'] = $_POST['DeniedReason'];
			}
			if($pclaim['isApproved'] == 1) {
				$pclaim['DeniedReason'] = NULL;
			}
			$this->db->where('PetrolBillsId', $petrolBillsId);
			$this->db->update('petrolbills', $pclaim);
		}
		echo 1;
		exit;
	}
	private function get_petrol_claim_list($startDate = NULL, $endDate = NULL) {
		if($startDate == NULL || $endDate == NULL) {
			$endDate = date("Y-m-d", strtotime("now"));
			$startDate = date("Y-m-d", strtotime("-200 day", strtotime($endDate)));
		}
		$this->db->select('PetrolBillsId, executive.ExecId, executive.ExecName, SLocation, ELocation, Kms, Price, StartTimestamp, EndTimestamp, Date, Purpose, isApproved, DeniedReason');
		$this->db->from('petrolbills');	$this->db->join('executive', 'executive.ExecId = petrolbills.ExecId');
		if(intval($this->session->userdata('a_city_id')) > 0) {
			$this->db->where('executive.CityId', intval($this->session->userdata('a_city_id')));
		}
		$this->db->where('Date >=', $startDate); $this->db->where('Date <=', $endDate);
		$this->db->order_by('Date', 'desc'); $query = $this->db->get();	$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach ($results as &$result) {
				$result['Date'] = date('Y-m-d', strtotime($result['Date']));
				$result['StartTimestamp'] = date('h:i', strtotime($result['StartTimestamp']));
				$result['EndTimestamp'] = date('h:i', strtotime($result['EndTimestamp']));
			}
			return $results;
		}
	}
	public function addExecutiveRewards() {
		$this->data['active'] = 'addExecutiveRewards';
		$this->load->model('city_m'); $this->load->model('executive_m');
		$this->data['cities'] = $this->city_m->get_by(array('isEnabled' => 1));
		$this->data['executives'] = $this->executive_m->get_by(array('isActive' => 1));
		$this->load->view('admin/addexecreward', $this->data);
	}
	public function create_reward() {
		if($_POST) {
			$this->load->model('executive_m'); $this->load->model('admin_m');
			$fields = array("ExecId", "Amount", "Type", "Description", "ClearFrequency");
			$count = 0; $data = array(); $test = TRUE;
			foreach($fields as $field) {
				if(!(bool)$this->input->post($field)) {
					$test = FALSE;
				}
				$data[$fields[$count]] = $this->input->post($field);
				$count += 1;
			}
			$data['UpdatedBy'] = $this->session->userdata('a_name');
			$data['updated_at'] = date("Y-m-d", strtotime("now"));
			if($this->input->post('OId') == NULL || $this->input->post('OId') == "" || !$this->admin_m->is_valid_oid($this->input->post('OId'))) {
				$data['OId'] = NULL;
			} else {
				$data['OId'] = $this->input->post('OId');
			}
			if($test == TRUE) {
				$this->db->insert('execrewards', $data);
				$exec = $this->db->select('Phone')->from('executive')->where('ExecId', $this->input->post('ExecId'))->get()->result_array()[0];
				$phone = $exec['Phone']; $and_reg_ids = array();
				$tempids = $this->db->select('executive.GCMId')->from('executive')->where('executive.GCMId <> ', NULL)->where('executive.ExecId', $this->input->post('ExecId'))->get()->result();
				foreach($tempids as $temp) {
					if(isset($temp->GCMId)) {
						$and_reg_ids[] = strval($temp->GCMId);
					}
				}
				if($this->input->post('Type') == 'Credit') {
					$this->send_sms_request_to_api($phone, "You have received Rs. " . $this->input->post('Amount') . " in your wallet for: " . $this->input->post('Description'));
					$and_push_msg_data = array("title" => "Rewards Credited", "message" => "You have received Rs. " . $this->input->post('Amount') . " in your wallet for: " . $this->input->post('Description'), "screen" => "reward");
				} elseif($this->input->post('Type') == 'Debit') {
					$and_push_msg_data = array("title" => "Rewards Debited", "message" => "You are debited with Rs. " . $this->input->post('Amount') . ", for: " . $this->input->post('Description'), "screen" => "reward");
					$this->send_sms_request_to_api($phone, "You are debited with Rs. " . $this->input->post('Amount') . ", for: " . $this->input->post('Description'));
				}
				$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
			}
		}
		redirect(site_url('admin/manageexecutive/viewExecutiveRewards'));
	}
	public function viewExecutiveRewards() {
		$this->data['active'] = 'viewExecutiveRewards';
		$this->data['rows'] = $this->getRewards();
		$this->load->view('admin/viewexecreward', $this->data);
	}
	private function getRewards() {
		$this->db->select('odetails.OId, executive.ExecName, executive.Phone, execrewards.Type, execrewards.ClearFrequency, execrewards.Amount, execrewards.Description, execrewards.UpdatedBy, execrewards.updated_at, execrewards.ExecRewardId, execrewards.isCleared');
		$this->db->from('execrewards');
		$this->db->join('executive', 'executive.ExecId = execrewards.ExecId', 'left');
		$this->db->join('odetails', 'odetails.OId = execrewards.OId', 'left');
		if($_POST) {
			$this->db->where('updated_at >=', date("Y-m-d", strtotime($_POST['startDate'])));
			$this->db->where('updated_at <=', date("Y-m-d", strtotime($_POST['endDate'])));
			$this->data['startDate'] = date("Y-m-d", strtotime($_POST['startDate']));
			$this->data['endDate'] = date("Y-m-d", strtotime($_POST['endDate']));
		} else {
			$this->db->where('updated_at >=', date("Y-m-d", strtotime("-30 day", strtotime("now"))));
			$this->db->where('updated_at <=', date("Y-m-d", strtotime("now")));
			$this->data['startDate'] = date("Y-m-d", strtotime("-30 day", strtotime("now")));
			$this->data['endDate'] = date("Y-m-d", strtotime("now"));
		}
		$this->db->order_by('updated_at', 'desc');
		$result = $this->db->get()->result_array();
		if(count($result) > 0) {
			foreach ($result as &$row) {
				if($row['ClearFrequency'] == '0') { $row['ClearFrequency'] =  "Daily"; } elseif($row['ClearFrequency'] == '1') { $row['ClearFrequency'] =  "Weekly"; } elseif($row['ClearFrequency'] == '2') { $row['ClearFrequency'] =  "Monthly"; }
			}
			return $result;
		} else {
			return array();
		}
	}
	public function executiveLeave() {
		$this->aauth->check_uri_access('executiveLeave');
		$this->data['active'] = 'executiveLeave';
		$this->data['rows'] = $this->getExecutiveLeave();
		$this->load->view('admin/executiveLeave', $this->data);
	}
	private function getExecutiveLeave() {
		$this->db->select('executive.ExecId, executive.ExecName, executive.Phone, executive.Email, execleave.updated_at, execleave.to_date, execleave.from_date, execleave.status, execleave.reason, execleave.updatedBy, execleave.id')->from('execleave');
		$this->db->join('executive', 'executive.ExecId = execleave.ExecId', 'left');
		$query = $this->db->order_by('execleave.updated_at')->get(); $results = $query->result_array();
		if(count($results) == 0) { return array(); } else { return $results; }
	}
	public function change_leave_status() {
		if($_POST) {
			$id = $_POST['id']; $status = $_POST['status']; $temp = array();
			if($status == 'Approved') {
				$temp['status']  = 'Approved'; $temp['reason'] = NULL; $temp['updatedBy'] = $this->session->userdata('a_name');
				$this->db->where('id', $id); $this->db->update('execleave', $temp);
			} elseif($status == 'Rejected') {
				$temp['status']  = 'Rejected'; $temp['reason'] = $_POST['reason']; $temp['updatedBy'] = $this->session->userdata('a_name');
				$this->db->where('id', $id); $this->db->update('execleave', $temp);
			}
			$leave = $this->db->select('executive.ExecId, executive.GCMId, executive.ExecName, executive.Phone, execleave.to_date, execleave.from_date, execleave.status, execleave.reason, execleave.updatedBy')->from('execleave')->join('executive', 'executive.ExecId = execleave.ExecId', 'left')->where('id', $id);
			$query = $this->db->order_by('execleave.updated_at')->get(); $results = $query->result_array();
			if(count($results) > 0) {
				$row = $results[0]; $and_reg_ids = array();
				$this->send_sms_request_to_api($row['Phone'], "Your leave request between " . $row["from_date"] . " and " . $row["to_date"] . " has been " . $status["status"] . " by " . $row["updatedBy"]);				
				$and_reg_ids[] = $row["GCMId"];					
				$and_push_msg_data = array("message" => "Your leave request between " . $row["from_date"] . " and " . $row["to_date"] . " has been " . $status["status"] . " by " . $row["updatedBy"], "tag" => "leave");
				$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
			}
		}
		redirect(site_url('admin/manageexecutive/executiveLeave'));
	}
	public function clear_rewards() {
		if($_POST) {
			$rewardJson = $this->input->post("reward"); $rewardJson = json_decode(urldecode($rewardJson), TRUE); $count = 0;
			foreach ($rewardJson as $rewardId => $reward) {
				$old = $reward['old']; $new = $reward['new'];
				if($old != $new) {
					$ex = $this->db->select('ExecId, Amount, Type')->from('execrewards')->where('ExecRewardId', $rewardId)->get()->result_array()[0];
					$exId = intval($ex['ExecId']); $exR = floatval($ex['Amount']); $exT = $ex['Type']; $execrewards[$count]['isCleared'] = intval($new);
					$execrewards[$count]['ExecRewardId'] = intval($rewardId); $count++;
				}
			}
			if($count > 0) {
				$this->db->update_batch('execrewards', $execrewards, 'ExecRewardId');
			}
		}
		redirect(site_url('admin/manageexecutive/viewExecutiveRewards'));
	}
	public function pettyCash() {
		$this->data['active'] = 'pettyCash';
		$this->data['rows'] = $this->getPettyCash();
		$this->load->view('admin/pettyCash', $this->data);
	}
	private function getPettyCash() {
		$this->db->select('odetails.OId, executive.ExecId, executive.ExecName, executive.Phone, pettycash.id, pettycash.date, pettycash.Amount, pettycash.Description, pettycash.status, pettycash.rejection_reason, pettycash.UpdatedBy, pettycash.created_at, pettycash.updated_at');
		$this->db->from('pettycash');
		$this->db->join('executive', 'executive.ExecId = pettycash.ExecId', 'left');
		$this->db->join('odetails', 'odetails.OId = pettycash.OId', 'left');
		if($_POST) {
			$this->db->where('date >=', date("Y-m-d", strtotime($_POST['startDate'])));
			$this->db->where('date <=', date("Y-m-d", strtotime($_POST['endDate'])));
			$this->data['startDate'] = date("Y-m-d", strtotime($_POST['startDate']));
			$this->data['endDate'] = date("Y-m-d", strtotime($_POST['endDate']));
		} else {
			$this->db->where('date >=', date("Y-m-d", strtotime("-30 day", strtotime("now"))));
			$this->db->where('date <=', date("Y-m-d", strtotime("now")));
			$this->data['startDate'] = date("Y-m-d", strtotime("-30 day", strtotime("now")));
			$this->data['endDate'] = date("Y-m-d", strtotime("now"));
		}
		$this->db->order_by('date', 'desc');
		$result = $this->db->get()->result_array();
		if(count($result) > 0) {
			return $result;
		} else {
			return array();
		}
	}
	public function petty_cash_status() {
		if($_POST) {
			$id = $this->input->post('id'); $new_status = $this->input->post('status'); $rejection_reason = $this->input->post('rejection_reason'); $response = 0; $rejectPending = 0;
			$pettyCash = $this->db->select('*')->from('pettycash')->where('id', $id)->limit(1)->get()->result_array();
			if($pettyCash) {
				$old_status = $pettyCash[0]['status']; $data['ExecId'] = $pettyCash[0]['ExecId']; $data['ClearFrequency'] = 0;
				$data['OId'] = $pettyCash[0]['OId']; $data['Amount'] = $pettyCash[0]['Amount']; $data['isCleared'] = 0;
				$data['UpdatedBy'] = $this->session->userdata('a_name'); $data['updated_at'] = date("Y-m-d", strtotime("now"));
				if($old_status == 'Pending') {
					if($new_status == 'Approved') {
						$data['Type'] = 'Credit'; $data['Description'] = 'Petty cash request approved for OId: ' . $data['OId'];
						$petty['status'] = 'Approved'; $petty['rejection_reason'] = NULL; $response = 1;
						$petty['UpdatedBy'] = $this->session->userdata('a_name');
					} elseif($new_status == 'Rejected') {
						$petty['status'] = 'Rejected'; $petty['rejection_reason'] = $this->input->post('rejection_reason'); $rejectPending = 1;
					}
				} elseif($old_status == 'Approved') {
					if($new_status == 'Rejected') {
						$data['Type'] = 'Debit'; $data['Description'] = 'Petty cash request rejected for OId: ' . $data['OId'];
						$petty['status'] = 'Rejected'; $petty['rejection_reason'] = $this->input->post('rejection_reason');
						$response = 1; $petty['UpdatedBy'] = $this->session->userdata('a_name');
					}
				} elseif ($old_status == 'Rejected') {
					if($new_status == 'Approved') {
						$data['Type'] = 'Credit'; $data['Description'] = 'Petty cash request approved for OId: ' . $data['OId'];
						$petty['status'] = 'Approved';  $petty['rejection_reason'] = NULL; $response = 1;
						$petty['UpdatedBy'] = $this->session->userdata('a_name');
					}
				}
			}
			if($response == 1) {
				$this->db->where('id', $id)->update('pettycash', $petty); $this->db->insert('execrewards', $data); $and_reg_ids = array();
				$tempids = $this->db->select('executive.GCMId')->from('executive')->where('executive.GCMId <> ', NULL)->where('executive.ExecId', $data['ExecId'])->get()->result();
				$phone = $this->db->select('Phone')->from('executive')->where('ExecId', $data['ExecId'])->get()->result_array()[0]['Phone'];
				foreach($tempids as $temp) {
					if(isset($temp->GCMId)) {
						$and_reg_ids[] = strval($temp->GCMId);
					}
				}
				if($data['Type'] == 'Credit') {
					$this->send_sms_request_to_api($phone, "You have received Rs. " . $data['Amount'] . " in your wallet due to : " . $data['Description']);
					$and_push_msg_data = array("title" => "Rewards Credited", "message" => "You have received Rs. " . $data['Amount'] . " in your wallet due to : " . $data['Description'], "screen" => "reward");
				} elseif($data['Type'] == 'Debit') {
					$and_push_msg_data = array("title" => "Rewards Debited", "message" => "You are debited with Rs. " . $data['Amount'] . ", due to : " . $data['Description'], "screen" => "reward");
					$this->send_sms_request_to_api($phone, "You are debited with Rs. " . $data['Amount'] . ", due to : " . $data['Description']);
				}
				$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
			}
			if($rejectPending == 1) { $this->db->where('id', $id)->update('pettycash', $petty); $response = 1; }
			echo $response;
			exit;
		}
	}
}