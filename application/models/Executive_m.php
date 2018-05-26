<?php
class Executive_m extends G6_Model {
	protected $_table_name = 'executive';
	protected $_primary_key = 'ExecId';
	protected $_order_by = 'ExecId';
	public function __construct() {
		parent::__construct();
	}
	public function login($is_from_app = FALSE) {
		$orig_user = $this->get_by(array(
			'Phone' => $this->input->post('phone', TRUE),
			'isActive' => 1
		), TRUE);
		if($orig_user) {
			$salt = $orig_user->Salt;
			if ($orig_user->Pwd == generate_salted_hash($this->input->post('password', TRUE), $salt)) {
				if(!$is_from_app) {
					$session_data = array(
						'ex_name' => $orig_user->ExecName,
						'ex_phone' => $orig_user->Phone,
						'ex_email' => $orig_user->Email,
						'ex_id' => $orig_user->ExecId,
						'ex_loggedin' => TRUE
					);
					$this->session->set_userdata($session_data);
				}
				return 1;
			} else {
				return 0;
			}
		} elseif($this->get_by(array('Phone' => $this->input->post('phone', TRUE)), TRUE)) {
			return 2;
		} else {
			return 0;
		}
	}
	public function get_ihcontacts($cityid = NULL) {
		if(intval($cityid) > 0) {
			return $this->db->where('ihcontacts.CityId', intval($cityid))->get('ihcontacts')->result_array();
		} else {
			return $this->db->get('ihcontacts')->result_array();
		}
	}
	public function get_latest_pbills($sdate = NULL, $edate = NULL, $exid = FALSE) {
		if(!isset($sdate) && !isset($edate)) {
			$edate = date('Y-m-d H:i:s', strtotime("now"));
			$sdate = date('Y-m-d H:i:s', strtotime("-7 days"));
		} else {
			$sdate = date('Y-m-d H:i:s', strtotime($sdate));
			$edate = date('Y-m-d H:i:s', strtotime($edate));
		}
		$this->db->select('*');
		$this->db->from('petrolbills');
		if(!$exid) {
			$this->db->where('ExecId', intval($this->session->userdata('ex_id')));
		} else {
			$this->db->where('ExecId', intval($exid));
		}
		$this->db->where('Date > ', $sdate);
		$this->db->where('Date < ', $edate);
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			foreach($results as &$result) {
				$result['SLocation'] = convert_to_camel_case($result['SLocation']);
				$result['ELocation'] = convert_to_camel_case($result['ELocation']);
				$result['Date'] = date('d/m', strtotime($result['Date']));
			}
			return $results;
		}
	}
	public function get_ex_fup_rtime_statuses($isHidden = 0) {
		$this->db->select('EFupStatusId, EFupStatusName');
		$this->db->from('exfupstatus');
		$this->db->where('isEnabled', 1);
		$this->db->where('isHidden', $isHidden);
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_ex_ps_updates($OId) {
		$this->db->select('Latitude, Longitude, LocationName, UpdatedBy, ScComments, CusComments, DATE_FORMAT(CONVERT_TZ(Timestamp,"+00:00","+05:30"), "%D %M %Y %h:%i %p") AS Timestamp, EstTime, EstPrice');
		$this->db->from('oexchkupstatus');
		$this->db->where('oexchkupstatus.OId', $OId);
		$this->db->order_by('oexchkupstatus.Timestamp', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			foreach($results as &$result) {
				$result['EstTime'] = date('d-m-Y h:i:s A', strtotime($result['EstTime']));
			}
			return $results;
		}
	}
	public function get_ex_ps_updates_web($OId) {
		$this->db->select('EstTime, EstPrice, LocationName, ScComments, CusComments, UpdatedBy, DATE_FORMAT(CONVERT_TZ(Timestamp,"+00:00","+05:30"), "%D %M %Y %h:%i %p") AS Timestamp');
		$this->db->from('oexchkupstatus');
		$this->db->where('oexchkupstatus.OId', $OId);
		$this->db->order_by('oexchkupstatus.Timestamp', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			foreach($results as &$result) {
				$result['EstTime'] = date('d-m-Y h:i:s A', strtotime($result['EstTime']));
				$result = array_values($result);
			}
			return $results;
		}
	}
	public function get_ex_fup_rtime_supdates($OId, $isHidden = 0) {
		$this->db->select('exfupstatus.EFupStatusId, EFupStatusName, Remarks, Latitude, Longitude, LocationName, UpdatedBy, Timestamp');
		$this->db->from('exfupstatus');
		$this->db->join('oexfupstatus', 'oexfupstatus.EFupStatusId = exfupstatus.EFupStatusId');
		$this->db->where('oexfupstatus.OId', $OId);
		$this->db->where('exfupstatus.isEnabled', 1);
		$this->db->where('exfupstatus.isHidden', $isHidden);
		$this->db->order_by('oexfupstatus.Timestamp', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			$count = 0;
			$output = array(array());
			foreach($results as $result) {
				if($count > 0 && $result['EFupStatusId'] == $output[$count - 1]['EFupStatusId']) {
					$output[$count - 1]['EFupStatusUpdates'][] = array('Remarks' => $result['Remarks'], 'LocationName' => $result['LocationName'], 'Latitude' => $result['Latitude'], 'Longitude' => $result['Longitude'], 'UpdatedBy' => $result['UpdatedBy'], 'Time' => date('h:i A', strtotime($result['Timestamp'] . ' UTC')), 'Date' => date('d/m', strtotime($result['Timestamp'] . ' UTC')));
				} else {
					$output[$count]['EFupStatusId'] = $result['EFupStatusId'];
					$output[$count]['EFupStatusName'] = $result['EFupStatusName'];
					$output[$count]['EFupStatusUpdates'][] = array('Remarks' => $result['Remarks'], 'LocationName' => $result['LocationName'], 'Latitude' => $result['Latitude'], 'Longitude' => $result['Longitude'], 'UpdatedBy' => $result['UpdatedBy'], 'Time' => date('h:i A', strtotime($result['Timestamp'] . ' UTC')), 'Date' => date('d/m', strtotime($result['Timestamp'] . ' UTC')));
					$count++;
				}
			}
			return $output;
		}
	}
	public function get_ex_fup_rtime_supdates_web($OId, $isHidden = 0) {
		$this->db->select('EFupStatusName AS Status, Remarks, LocationName AS Location, UpdatedBy, DATE_FORMAT(CONVERT_TZ(Timestamp,"+00:00","+05:30"), "%D %M %Y %h:%i %p") AS Timestamp');
		$this->db->from('exfupstatus');
		$this->db->join('oexfupstatus', 'oexfupstatus.EFupStatusId = exfupstatus.EFupStatusId');
		$this->db->where('oexfupstatus.OId', $OId);
		$this->db->where('exfupstatus.isEnabled', 1);
		$this->db->where('exfupstatus.isHidden', $isHidden);
		$this->db->order_by('oexfupstatus.Timestamp', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			foreach ($results as &$result) {
				$result = array_values($result);
			}
		    return $results;
		}
	}
	public function get_execjc_media($OId) {
		$this->db->select('FileName, FileType, TagName, FileImgView');
		$this->db->from('execmedia');
		$this->db->where('FileType', 'img');
		$this->db->where('OId', $OId);
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			$count = 0;
			foreach($results as $result) {
				if($count > 0 && $result['TagName'] == $output[$count - 1]['TagName']) {
					if(!$result['FileImgView']) {
						$output[$count - 1]['ImgData']['OtherImgs'][] = array('ImgUrl' => get_awss3_url($result['FileName']), 'FileType' => $result['FileType'], 'FileImgView' => $result['FileImgView']);
					} else {
						$output[$count - 1]['ImgData']['BikeImgs'][] = array('ImgUrl' => get_awss3_url($result['FileName']), 'FileType' => $result['FileType'], 'FileImgView' => $result['FileImgView']);
					}
				} else {
					$output[$count]['TagName'] = $result['TagName'];
					if(!$result['FileImgView']) {
						$output[$count]['ImgData']['OtherImgs'][] = array('ImgUrl' => get_awss3_url($result['FileName']), 'FileType' => $result['FileType'], 'FileImgView' => $result['FileImgView']);
						$output[$count]['ImgData']['BikeImgs'] = array();
					} else {
						$output[$count]['ImgData']['BikeImgs'][] = array('ImgUrl' => get_awss3_url($result['FileName']), 'FileType' => $result['FileType'], 'FileImgView' => $result['FileImgView']);
						$output[$count]['ImgData']['OtherImgs'] = array();
					}
					$count += 1;
				}
			}
			return $output;
		}
	}
	public function get_bill_media($OId) {
		$this->db->select('BillImgs');
		$this->db->from('jobcarddetails');
		$this->db->where('OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row();
		if(!isset($result)) {
			return array();
		} else {
			$results = unserialize($result->BillImgs);
			if($results && count($results) > 0) {
				foreach($results as $result) {
					$output[] = get_awss3_url('uploads/omedia/img/' . $result['name']);
				}
				return $output;
			} else {
				return array();
			}
		}
	}
	public function get_execcl_cats() {
		$this->db->select('CLCatId, CLCatName, CLCatIcon');
		$this->db->from('checklistcats');
		$this->db->where('checklistcats.isEnabled', 1);
		$this->db->order_by('checklistcats.CLOrder', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_execcl_subcats() {
		$this->db->select('CLSCatId, CLCatId, CLSCatName');
		$this->db->from('checklistsubcats');
		$this->db->where('checklistsubcats.isEnabled', 1);
		$this->db->order_by('checklistsubcats.CLSOrder', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			$count = 0;
			foreach($results as $result) {
				$output[intval($result['CLCatId'])][$count]['CLSCatName'] = $result['CLSCatName'];
				$output[intval($result['CLCatId'])][$count]['CLSCatId'] = $result['CLSCatId'];
				$count++;
			}
			return $output;
		}
	}
	public function get_app_execcl_cats() {
		$this->db->select('checklistcats.CLCatId, CLCatName, CLCatIcon, CLSCatId, CLSCatName');
		$this->db->from('checklistcats');
		$this->db->join('checklistsubcats', 'checklistsubcats.CLCatId = checklistcats.CLCatId');
		$this->db->where('checklistcats.isEnabled', 1);
		$this->db->where('checklistsubcats.isEnabled', 1);
		$this->db->order_by('checklistcats.CLOrder', 'asc');
		$this->db->order_by('checklistsubcats.CLSOrder', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			$count = 0;
			$output = array(array());
			foreach($results as $result) {
				if($count > 0 && $result['CLCatId'] == $output[$count - 1]['CLCatId']) {
					$output[$count - 1]['CLSubCats'][] = array('CLSCatId' => $result['CLSCatId'], 'CLSCatName' => $result['CLSCatName']);
				} else {
					$output[$count]['CLCatId'] = $result['CLCatId'];
					$output[$count]['CLCatName'] = $result['CLCatName'];
					$output[$count]['CLCatIcon'] = $result['CLCatIcon'];
					$output[$count]['CLSubCats'][] = array('CLSCatId' => $result['CLSCatId'], 'CLSCatName' => $result['CLSCatName']);
					$count++;
				}
			}
			return $output;
		}
	}
	public function get_jcard_cats() {
		$this->db->select('JCCatId, JCCatName, JCFormName, isMultiple');
		$this->db->from('jobcardcats');
		$this->db->where('jobcardcats.isEnabled', 1);
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_jcard_subcats() {
		$this->db->select('JCSCatId, JCCatId, JCSCatName');
		$this->db->from('jobcardscats');
		$this->db->where('jobcardscats.isEnabled', 1);
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			$count = 0;
			foreach($results as $result) {
				$output[intval($result['JCCatId'])][$count]['JCSCatName'] = $result['JCSCatName'];
				$output[intval($result['JCCatId'])][$count]['JCSCatId'] = $result['JCSCatId'];
				$count++;
			}
			return $output;
		}
	}
	public function get_app_jcard_selects($OId) {
		$this->db->select('*');
		$this->db->from('jobcarddetails');
		$this->db->where('OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		if(!$result) {
			return NULL;
		} else {
			$response['JCSelects'] = explode('||', $result['JCSelects']);
			$response['ChecklistVals'] = explode('||', $result['ChecklistVals']);
			return $response;
		}
	}
	public function get_app_jcard_cats() {
		$this->db->select('jobcardcats.JCCatId, jcpcats.JCPCatId, JCPName, JCCatName, JCFormName, isMultiple, isSingleCklist, isMandatory, JCSCatId, JCSCatName');
		$this->db->from('jobcardcats');
		$this->db->join('jcpcats', 'jcpcats.JCPCatId = jobcardcats.JCPCatId');
		$this->db->join('jobcardscats', 'jobcardscats.JCCatId = jobcardcats.JCCatId');
		$this->db->where('jobcardcats.isEnabled', 1);
		$this->db->where('jobcardscats.isEnabled', 1);
		$this->db->where('jcpcats.isEnabled', 1);
		$this->db->order_by('jcpcats.JCPCatId', 'asc');
		$this->db->order_by('jobcardcats.JCCatId', 'asc');
		$this->db->order_by('jobcardscats.JCSCatId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			$pcount = 0;
			foreach($results as $result) {
				if($pcount > 0 && $result['JCPCatId'] == $output[$pcount - 1]['JCPCatId']) {
					if(intval($result['isSingleCklist'] == 1)) {
						if($scount > 0 && $result['JCCatId'] == $output[$pcount - 1]['SJCCats'][$scount - 1]['JCCatId']) {
							$output[$pcount - 1]['SJCCats'][$scount - 1]['JCSubCats'][] = array('JCSCatId' => $result['JCSCatId'], 'JCSCatName' => $result['JCSCatName']);
						} else {
							$output[$pcount - 1]['SJCCats'][$scount]['JCCatId'] = $result['JCCatId'];
							$output[$pcount - 1]['SJCCats'][$scount]['JCCatName'] = $result['JCCatName'];
							$output[$pcount - 1]['SJCCats'][$scount]['JCFormName'] = $result['JCFormName'];
							$output[$pcount - 1]['SJCCats'][$scount]['isMultiple'] = $result['isMultiple'];
							$output[$pcount - 1]['SJCCats'][$scount]['isSingleCklist'] = $result['isSingleCklist'];
							$output[$pcount - 1]['SJCCats'][$scount]['isMandatory'] = $result['isMandatory'];
							$output[$pcount - 1]['SJCCats'][$scount]['JCSubCats'][] = array('JCSCatId' => $result['JCSCatId'], 'JCSCatName' => $result['JCSCatName']);
							$scount++;
						}
					} else {
						if($mcount > 0 && $result['JCCatId'] == $output[$pcount - 1]['MJCCats'][$mcount - 1]['JCCatId']) {
							$output[$pcount - 1]['MJCCats'][$mcount - 1]['JCSubCats'][] = array('JCSCatId' => $result['JCSCatId'], 'JCSCatName' => $result['JCSCatName']);
						} else {
							$output[$pcount - 1]['MJCCats'][$mcount]['JCCatId'] = $result['JCCatId'];
							$output[$pcount - 1]['MJCCats'][$mcount]['JCCatName'] = $result['JCCatName'];
							$output[$pcount - 1]['MJCCats'][$mcount]['JCFormName'] = $result['JCFormName'];
							$output[$pcount - 1]['MJCCats'][$mcount]['isMultiple'] = $result['isMultiple'];
							$output[$pcount - 1]['MJCCats'][$mcount]['isSingleCklist'] = $result['isSingleCklist'];
							$output[$pcount - 1]['MJCCats'][$mcount]['isMandatory'] = $result['isMandatory'];
							$output[$pcount - 1]['MJCCats'][$mcount]['JCSubCats'][] = array('JCSCatId' => $result['JCSCatId'], 'JCSCatName' => $result['JCSCatName']);
							$mcount++;
						}
					}
				} else {
					$output[$pcount]['JCPCatId'] = $result['JCPCatId'];
					$output[$pcount]['JCPName'] = $result['JCPName'];
					$scount = 0;
					$mcount = 0;
					if(intval($result['isSingleCklist'] == 1)) {
						if($scount > 0 && $result['JCCatId'] == $output[$pcount]['SJCCats'][$scount - 1]['JCCatId']) {
							$output[$pcount]['SJCCats'][$scount - 1]['JCSubCats'][] = array('JCSCatId' => $result['JCSCatId'], 'JCSCatName' => $result['JCSCatName']);
						} else {
							$output[$pcount]['SJCCats'][$scount]['JCCatId'] = $result['JCCatId'];
							$output[$pcount]['SJCCats'][$scount]['JCCatName'] = $result['JCCatName'];
							$output[$pcount]['SJCCats'][$scount]['JCFormName'] = $result['JCFormName'];
							$output[$pcount]['SJCCats'][$scount]['isMultiple'] = $result['isMultiple'];
							$output[$pcount]['SJCCats'][$scount]['isSingleCklist'] = $result['isSingleCklist'];
							$output[$pcount]['SJCCats'][$scount]['isMandatory'] = $result['isMandatory'];
							$output[$pcount]['SJCCats'][$scount]['JCSubCats'][] = array('JCSCatId' => $result['JCSCatId'], 'JCSCatName' => $result['JCSCatName']);
							$output[$pcount]['MJCCats'] = array();
							$scount++;
						}
					} else {
						if($mcount > 0 && $result['JCCatId'] == $output[$pcount]['MJCCats'][$mcount - 1]['JCCatId']) {
							$output[$pcount]['MJCCats'][$mcount - 1]['JCSubCats'][] = array('JCSCatId' => $result['JCSCatId'], 'JCSCatName' => $result['JCSCatName']);
						} else {
							$output[$pcount]['MJCCats'][$mcount]['JCCatId'] = $result['JCCatId'];
							$output[$pcount]['MJCCats'][$mcount]['JCCatName'] = $result['JCCatName'];
							$output[$pcount]['MJCCats'][$mcount]['JCFormName'] = $result['JCFormName'];
							$output[$pcount]['MJCCats'][$mcount]['isMultiple'] = $result['isMultiple'];
							$output[$pcount]['MJCCats'][$mcount]['isSingleCklist'] = $result['isSingleCklist'];
							$output[$pcount]['MJCCats'][$mcount]['isMandatory'] = $result['isMandatory'];
							$output[$pcount]['MJCCats'][$mcount]['JCSubCats'][] = array('JCSCatId' => $result['JCSCatId'], 'JCSCatName' => $result['JCSCatName']);
							$output[$pcount]['SJCCats'] = array();
							$mcount++;
						}
					}
					$pcount++;
				}
			}
			return $output;
		}
	}
	public function get_assigned_odetails($ex = FALSE) {
		$this->db->select('odetails.OId, odetails.ODate, odetails.TieupId, odetails.pickup_drop_flag AS otype, user.UserName, user.Phone, servicecenter.ScName, exfupstatus.EFupStatusName, ulocation.LocationName AS UserLocation, odetails.SlotHour, sclocation.LocationName AS ScLocation, odetails.ULatitude AS uolati, odetails.ULongitude AS uolongi, ulocation.Latitude AS ulati, ulocation.Longitude AS ulongi, jobcarddetails.Tag');
		$this->db->from('odetails');
		$this->db->join('execassigns', 'execassigns.OId = odetails.OId', 'left');
		$this->db->join('exfupstatus', 'exfupstatus.EFupStatusId = odetails.LastExFupStatusId', 'left');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('jobcarddetails', 'jobcarddetails.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('servicecenter', 'servicecenter.ScId = oservicedetail.ScId', 'left');
		$this->db->join('scaddrsplit', 'scaddrsplit.ScId = servicecenter.ScId', 'left');
		$this->db->join('useraddr', 'useraddr.UserAddrId = odetails.UserAddrId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		$this->db->join('location AS ulocation', 'ulocation.LocationId = useraddr.LocationId', 'left');
		$this->db->join('location AS sclocation', 'sclocation.LocationId = scaddrsplit.LocationId', 'left');
		if(!$ex) {
			$this->db->where('execassigns.ExecId', intval($this->session->userdata('ex_id')));
		} else {
			$this->db->where('execassigns.ExecId', intval($ex));
		}
		$this->db->where('execassigns.AssignedStatus', 1);
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('odetails.FinalFlag', 0);
		$this->db->where("((status.ServiceId = '1' AND status.Order <= '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '2' AND status.Order <='4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '4' AND status.Order <= '3' AND status.Order >= '0'))", NULL, FALSE);
		$this->db->group_by('execassigns.OId, execassigns.ExecId');
		$this->db->order_by('odetails.ODate', 'desc');
		$this->db->order_by('odetails.OId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			$scount = 0;
			foreach($results as &$result) {
				if(!isset($result['Tag'])) {
					$result['Tag'] = 1;
				}
				$result['SlotHour'] = floatval($result['SlotHour']);
				if ($result['SlotHour'] > 12) {
					$temp_hr = intval($result['SlotHour'] - 12);
					$temp = (intval($result['SlotHour'] * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$result['SlotHour'] = $temp_hr . ":" . $temp . " PM";
				} elseif ($result['SlotHour'] == 12) {
					$result['SlotHour'] = intval($result['SlotHour']) . ":00 PM";
				} else {
					$temp = (intval($result['SlotHour'] * 60) % 60);
					if($temp == 0) {
						$temp = '00';
					}
					$result['SlotHour'] = intval($result['SlotHour']) . ":" . $temp . " AM";
				}
				$odate = date('d/m', strtotime($result['ODate']));
				$result['SlotHour'] = $odate . ' - ' . $result['SlotHour'];
				if($result['uolati'] && $result['uolongi']) {
					$result['ulati'] = $result['uolati'];
					$result['ulongi'] = $result['uolongi'];
				}
				unset($result['uolati']);
				unset($result['uolongi']);
				if($scount > 0 && $result['ODate'] == $fresult[$scount - 1]['ODate']) {
					unset($result['ODate']);
					$fresult[$scount - 1]['Orders'][] = $result;
				} else {
					$fresult[$scount]['ODate'] = $result['ODate'];
					$fresult[$scount]['FODate'] = date('D, jS F', strtotime($result['ODate']));
					unset($result['ODate']);
					$fresult[$scount]['Orders'][] = $result;
					$scount++;
				}
			}
			return $fresult;
		}
	}
	public function logout() {
		$this->session->sess_destroy();
	}
	public function loggedin() {
		return (bool) $this->session->userdata('ex_loggedin');
	}
	public function is_unique_ph($ph) {
		$orig_user = $this->get_by(array(
			'Phone' => $ph,
		));
		if (count($orig_user) >= 1) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	public function is_own_ph($ph, $id) {
		$orig_user = $this->get_by(array(
			'Phone' => $ph
		));
		if (count($orig_user) >= 1) {
			if($orig_user[0]->ExecId == $id) {
				return TRUE;
			} else {
				return $this->is_unique_ph($ph);
			}
		} else {
			return $this->is_unique_ph($ph);
		}
	}
	public function get_active_executives($ODate) {
		$exec_id = array();
		$this->db->select('*')->from('execleave');
		$this->db->join('executive', 'executive.ExecId = execleave.ExecId');
		$this->db->where('executive.isActive', 1);
		$this->db->where('from_date <=', $ODate);
		$this->db->where('to_date >=', $ODate);
		// $this->db->where('status', 'Approved');
		$leaves = $this->db->get()->result_array();
		if($leaves) {			
			foreach ($leaves as $leave) {
				$exec_id[] = $leave['ExecId'];
			}
		}		
		$this->db->select('*')->from('executive');
		$this->db->where('isActive', 1);
		if($exec_id) {
			$this->db->where_not_in('ExecId', $exec_id);
		}
		$executives = $this->db->get()->result_array();
		return $executives;
	}
}