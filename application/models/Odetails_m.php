<?php
class Odetails_m extends G6_Model {
	protected $_table_name = 'odetails';
	protected $_primary_key = 'OrderId';
	protected $_order_by = 'ODate';
	public function __construct() {
		parent::__construct();
	}
	public function get_bike_regnum_by_oid($OId) {
		$this->db->select('BikeNumber');
		$this->db->from('odetails');
		$this->db->where('odetails.OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		if (!$result) {
			return NULL;
		} else {
			$bikenum = preg_split("/\s+/", $result['BikeNumber'], 3);
			$output['BikeNumber'] = '';
			if(isset($bikenum[1])) {
				$output['BikeNumber'] .= $bikenum[1];
			}
			if(isset($bikenum[2])) {
				$output['BikeNumber'] .= $bikenum[2];
			}
			if(isset($bikenum[0])) {
				$output['RegNum'] = $bikenum[0];
			} else {
				$output['RegNum'] = '';
			}
			return $output;
		}
	}
	public function get_user_id_by_usraddrid($UserAddrId) {
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
	public function get_user_ph_by_oid($OId) {
		$this->db->select('user.Phone');
		$this->db->from('odetails');
		$this->db->join('user', 'user.UserId = odetails.UserId');
		$this->db->where('OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		if ($result) {
			return $result['Phone'];
		} else {
			return NULL;
		}
	}
	public function get_user_id_by_oid($OId) {
		$this->db->select('odetails.UserId');
		$this->db->from('odetails');
		$this->db->where('OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		if ($result) {
			return $result['UserId'];
		} else {
			return NULL;
		}
	}
	public function get_user_address($OId) {
		$this->db->select('UserName, Phone, AddrLine1, AddrLine2, LocationName, Landmark, CityName, Pwd, user.Email, location.Latitude, location.Longitude');
		$this->db->from('odetails');
		$this->db->join('user', 'user.UserId = odetails.UserId');
		$this->db->join('useraddr', 'useraddr.UserAddrId = odetails.UserAddrId', 'left');
		$this->db->join('location', 'location.LocationId = useraddr.LocationId', 'left');
		$this->db->join('city', 'city.CityId = location.CityId', 'left');
		$this->db->where('OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$address['Phone'] = $result[0]['Phone'];
			$address['name'] = convert_to_camel_case($result[0]['UserName']);
			$address['pwd'] = $result[0]['Pwd'];
			$address['email'] = $result[0]['Email'];
			$address['address'] = '';
			if($result[0]['AddrLine1'] && $result[0]['AddrLine2']) {
				$address['address'] .= '<div>' . convert_to_camel_case($result[0]['AddrLine1']) . '</div>';
				$address['address'] .= '<div>' . convert_to_camel_case($result[0]['AddrLine2']) . '</div>';
				$address['address'] .= '<div>' . convert_to_camel_case($result[0]['LocationName']) . ', ';
				$address['address'] .= convert_to_camel_case($result[0]['Landmark']) . '</div>';
				$address['address'] .= '<div>' . convert_to_camel_case($result[0]['CityName']) . '</div>';
				$address['address'] .= '<div>Phone: ' . $result[0]['Phone'] . '</div>';
				$address['AddrLine1'] = $result[0]['AddrLine1']; $address['AddrLine2'] = $result[0]['AddrLine2'];
				$address['LocationName'] = $result[0]['LocationName']; $address['Landmark'] = $result[0]['Landmark'];
				$address['Latitude'] = $result[0]['Latitude']; $address['Longitude'] = $result[0]['Longitude'];
			}
			return $address;
		}
	}
	public function get_app_user_address($OId) {
		$this->db->select('UserName, Phone, AddrLine1, AddrLine2, LocationName, Landmark, CityName, Pwd, user.Email, location.Latitude, location.Longitude, ULatitude, ULongitude');
		$this->db->from('odetails');
		$this->db->join('user', 'user.UserId = odetails.UserId');
		$this->db->join('useraddr', 'useraddr.UserAddrId = odetails.UserAddrId', 'left');
		$this->db->join('location', 'location.LocationId = useraddr.LocationId', 'left');
		$this->db->join('city', 'city.CityId = location.CityId', 'left');
		$this->db->where('OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$address['Phone'] = $result[0]['Phone'];
			$address['UserName'] = convert_to_camel_case($result[0]['UserName']);
			$address['Email'] = $result[0]['Email'];
			$address['AddrLine1'] = convert_to_camel_case($result[0]['AddrLine1']);
			$address['AddrLine2'] = convert_to_camel_case($result[0]['AddrLine2']);
			$address['LocationName'] = convert_to_camel_case($result[0]['LocationName']);
			$address['Landmark'] = convert_to_camel_case($result[0]['Landmark']);
			$address['CityName'] = convert_to_camel_case($result[0]['CityName']);
			$address['Latitude'] = $result[0]['Latitude']; $address['Longitude'] = $result[0]['Longitude'];
			if(isset($result[0]['ULatitude']) && $result[0]['ULatitude'] != NULL && isset($result[0]['ULongitude']) && $result[0]['ULongitude'] != NULL) {
				$address['Latitude'] = $result[0]['ULatitude']; $address['Longitude'] = $result[0]['ULongitude'];
			}
			return $address;
		}
	}
	public function get_sc_address($ScId) {
		$this->db->select('AddrLine1, AddrLine2, LocationName, Landmark, CityName, Pincode, CPhone');
		$this->db->from('scaddrsplit');
		$this->db->join('sccontact', 'sccontact.ScId = scaddrsplit.ScId');
		$this->db->join('location', 'location.LocationId = scaddrsplit.LocationId');
		$this->db->join('city', 'city.CityId = location.CityId');
		$this->db->where('scaddrsplit.ScId', $ScId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$address = '';
			$address .= '<div>' . convert_to_camel_case($result[0]['AddrLine1']) . '</div>';
			$address .= '<div>' . convert_to_camel_case($result[0]['AddrLine2']) . '</div>';
			$address .= '<div>' . convert_to_camel_case($result[0]['LocationName']) . ', ';
			$address .= convert_to_camel_case($result[0]['Landmark']) . '</div>';
			$address .= '<div>' . convert_to_camel_case($result[0]['CityName']) . ' - ';
			$address .= $result[0]['Pincode'] . '</div>';
			$address .= '<div>Phone: ' . $result[0]['CPhone'].'</div>';
			return $address;
		}
	}
	public function get_app_sc_address($ScId) {
		$this->db->select('AddrLine1, AddrLine2, LocationName, Landmark, CityName, Pincode, CPhone AS Phone');
		$this->db->from('scaddrsplit');
		$this->db->join('sccontact', 'sccontact.ScId = scaddrsplit.ScId');
		$this->db->join('location', 'location.LocationId = scaddrsplit.LocationId');
		$this->db->join('city', 'city.CityId = location.CityId');
		$this->db->where('scaddrsplit.ScId', $ScId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0];
		}
	}
	public function get_order_media($OId) {
		$this->db->select('FileData, FileType');
		$this->db->from('omedia');
		$this->db->where('FileType', 'img');
		$this->db->where('MediaType', 'info');
		$this->db->where('OId', $OId);
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_oids_user($user = NULL) {
		$this->db->select('OId, ODate');
		$this->db->from('odetails');
		if($user) {
			$this->db->where('odetails.UserId', intval($user));
		} else {
			$this->db->where('odetails.UserId', $this->session->userdata('id'));
		}
		$this->db->group_by('odetails.OId');
		$this->db->order_by('odetails.ServiceId', 'asc');
		$this->db->order_by('odetails.Timestamp', 'desc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
	public function get_active_oids_user($act_oid, $userid = FALSE) {
		$this->db->select('odetails.OId');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		if($userid) {
			$this->db->where('odetails.UserId', $userid);
		} else {
			$this->db->where('odetails.UserId', $this->session->userdata('id'));
		}
		if($act_oid) {
			$this->db->where('odetails.OId !=', $act_oid);
		}
		$this->db->where("((status.ServiceId = '1' AND status.Order >= '0' AND (status.Order < '4' OR odetails.FinalFlag = '0'))", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '2' AND status.Order >= '0' AND (status.Order < '4' OR odetails.FinalFlag = '0'))", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '3' AND status.Order >= '0' AND status.Order < '3')", NULL, FALSE);
		$this->db->or_where("(status.ServiceId = '4' AND status.Order >= '0' AND (status.Order < '3' OR odetails.FinalFlag = '0')))", NULL, FALSE);
		$this->db->group_by('odetails.OId');
		$this->db->order_by('odetails.Timestamp', 'desc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	public function get_latest_user_oid($userid = FALSE) {
		$this->db->select('OId');
		$this->db->from('odetails');
		if($userid) {
			$this->db->where('odetails.UserId', $userid);
		} else {
			$this->db->where('odetails.UserId', $this->session->userdata('id'));
		}
		$this->db->group_by('odetails.OId');
		$this->db->order_by('odetails.Timestamp', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0]['OId'];
		}
	}
	public function get_odetails_for_cancellation($OId) {
		$this->db->select('service.ServiceName, status.Order, service.ServiceId');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId');
		$this->db->where('odetails.OId', $OId);
		$this->db->where('odetails.UserId', intval($this->session->userdata('id')));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return FALSE;
		} else {
			return $result[0];
		}
	}
	public function get_odetails_for_reschedule($OId) {
		$this->db->select('odetails.ODate, service.ServiceName, status.Order, service.ServiceId, bikemodel.BikeModelId, bikemodel.BikeCompanyId, location.LocationName');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId');
		$this->db->join('scaddrsplit', 'scaddrsplit.ScId = oservicedetail.ScId');
		$this->db->join('location', 'location.LocationId = scaddrsplit.LocationId');
		$this->db->where('odetails.OId', $OId);
		$this->db->where('odetails.UserId', intval($this->session->userdata('id')));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return FALSE;
		} else {
			return $result[0];
		}
	}
	public function get_stype_by_oid($OId) {
		$this->db->select('service.ServiceName, service.ServiceId, odetails.MRRemarks, service.SerImg');
		$this->db->from('service');
		$this->db->join('odetails', 'odetails.ServiceId = service.ServiceId');
		$this->db->where('OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0];
		}
	}
	public function get_scenter_by_oid($OId, $limit_to_sc = FALSE) {
		$this->db->select('ScName, servicecenter.ScId, sccontact.CPhone AS Phone, status.StatusId, Order, oservicedetail.ServiceDesc1, oservicedetail.ServiceDesc2, oservicedetail.isFbNotified, AddrLine1, AddrLine2, LocationName, Landmark, CityName, city.CityId, Pincode, sccontact.AltPhone, sccontact.Email, Landline, ScName, Owner, scaddr.Latitude, scaddr.Longitude, scaddr.ScAddrSplitId, sccontact.CPhone AS Phone, sccontact.CPerson, Rating');
		$this->db->from('oservicedetail');
		$this->db->join('servicecenter', 'servicecenter.ScId = oservicedetail.ScId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('sccontact', 'sccontact.ScId = oservicedetail.ScId', 'left');		
		$this->db->join('scaddrsplit', 'scaddrsplit.ScId = servicecenter.ScId', 'left');
		$this->db->join('scaddr', 'scaddr.ScAddrSplitId = scaddrsplit.ScAddrSplitId', 'left');
		$this->db->join('location', 'location.LocationId = scaddrsplit.LocationId', 'left');
		$this->db->join('city', 'city.CityId = location.CityId', 'left');
		$this->db->where('oservicedetail.OId', $OId);
		if($limit_to_sc) {
			$this->db->where('oservicedetail.ScId', intval($this->session->userdata('v_sc_id')));
			$this->db->limit(1);
		} else {
			$this->db->order_by('servicecenter.ScId', 'asc');
		}
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
	public function get_ostatus_name($OId) {
		$this->db->select('status.StatusName');
		$this->db->from('oservicedetail');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId');
		$this->db->where('OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		if ($result) {
			return $result['StatusName'];
		} else {
			return NULL;
		}
	}
	public function get_insren_details($OId) {
		$this->db->select('insurer.InsurerName, oinsurancedetail.RegYear, oinsurancedetail.ExpiryDays, oinsurancedetail.isClaimedBefore, oinsurancedetail.PreviousInsurer');
		$this->db->from('oinsurancedetail');
		$this->db->join('insurer', 'insurer.InsurerId = oinsurancedetail.PreviousInsurer');
		$this->db->where('oinsurancedetail.OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0];
		}
	}
	public function get_estprice_by_oid($OId) {
		$this->db->select('EstPrice');
		$this->db->from('oservicedetail');
		$this->db->where('OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return 0;
		} else {
			return intval($result[0]['EstPrice']);
		}
	}
	public function is_amt_confirmed($oid) {
		$this->db->select('isAmtCfmd');
		$this->db->from('oservicedetail');
		$this->db->where('OId', $oid);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return 0;
		} else {
			return intval($result[0]['isAmtCfmd']);
		}
	}
	public function get_bm_by_oid($OId) {
		$this->db->select('BikeModelName, BikeCompanyName, odetails.BikeModelId, odetails.BikeNumber');
		$this->db->from('odetails');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId');
		$this->db->where('OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0];
		}
	}
	public function get_timestamp_by_oid($OId) {
		$this->db->select('DATE_FORMAT(CONVERT_TZ(odetails.TimeStamp,"+00:00","+05:30"), "%D %M %Y %h:%i %p") AS TimeStamp');
		$this->db->from('odetails');
		$this->db->where('OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0]['TimeStamp'];
		}
	}
	public function get_timeslot_by_oid($OId) {
		$this->db->select('ODate, SlotHour');
		$this->db->from('odetails');
		$this->db->where('OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$date = $result[0]['ODate'];
			$timeslot = date("l, F d, Y",strtotime($date));
			if ($result[0]['SlotHour'] > 12) {
				$temp_hr = intval($result[0]['SlotHour'] - 12);
				$temp = (intval($result[0]['SlotHour'] * 60) % 60);
				if($temp == 0) {
					$temp = '00';
				}
				$timeslot .= ' - ' . $temp_hr . ":" . $temp . " PM";
			} elseif ($result[0]['SlotHour'] == 12) {
				$timeslot .= ' - ' . intval($result[0]['SlotHour']) . ":00 PM";
			} else {
				$temp = (intval($result[0]['SlotHour'] * 60) % 60);
				if($temp == 0) {
					$temp = '00';
				}
				$timeslot .= ' - ' . intval($result[0]['SlotHour']) . ":" . $temp . " AM";
			}
			return $timeslot;
		}
	}
	public function get_app_timeslot_by_oid($OId) {
		$this->db->select('ODate, SlotHour');
		$this->db->from('odetails');
		$this->db->where('OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			$date = $result[0]['ODate'];
			$timeslot['date'] = date("l, F d, Y",strtotime($date));
			if ($result[0]['SlotHour'] > 12) {
				$temp_hr = intval($result[0]['SlotHour'] - 12);
				$temp = (intval($result[0]['SlotHour'] * 60) % 60);
				if($temp == 0) {
					$temp = '00';
				}
				$timeslot['time'] = $temp_hr . ":" . $temp . " PM";
			} elseif ($result[0]['SlotHour'] == 12) {
				$timeslot['time'] = intval($result[0]['SlotHour']) . ":00 PM";
			} else {
				$temp = (intval($result[0]['SlotHour'] * 60) % 60);
				if($temp == 0) {
					$temp = '00';
				}
				$timeslot['time'] = intval($result[0]['SlotHour']) . ":" . $temp . " AM";
			}
			return $timeslot;
		}
	}
	public function get_paymode_by_oid($OId) {
		$this->db->select('paymt.PaymtMode');
		$this->db->from('opaymtdetail');
		$this->db->join('paymt', 'paymt.PaymtId = opaymtdetail.PaymtId');
		$this->db->where('opaymtdetail.OId', $OId);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return 'No Payment Associated';
		} else {
			foreach($results as $result) {
				$return[] = $result['PaymtMode'];
			}
			return implode(", ", $return);
		}
	}
	public function create_order($user_addr_id, $usr_id) {
		$data_odetails = array();
		$data_odetails['ODate'] = $this->input->cookie('date');
		$data_odetails['ServiceId'] = intval($this->input->cookie('servicetype'));
		$data_odetails['UserAddrId'] = intval($user_addr_id);
		$data_odetails['UserId'] = intval($usr_id);
		$data_odetails['CityId'] = intval($this->input->cookie('CityId'));
		$data_odetails['BikeModelId'] = intval($this->input->cookie('model'));
		$data_odetails['isBreakdown'] = intval($this->input->post('isBreakdown'));
		if ($this->input->cookie('servicetype') == 1 || $this->input->cookie('servicetype') == 2 || $this->input->cookie('servicetype') == 4) {
			$data_odetails['SlotHour'] = floatval($this->input->cookie('slot'));
		}
		$data_odetails['UserIp'] = $this->input->ip_address();
		$data_odetails['UserDevice'] = $this->get_user_agent();
		if($this->session->userdata('cid') != '' || $this->session->userdata('cid') !== NULL) {
			$data_odetails['CouponId'] = intval($this->session->userdata('cid'));
		}
		if($this->session->userdata('fcid') != '' || $this->session->userdata('fcid') !== NULL) {
			$data_odetails['FCouponId'] = intval($this->session->userdata('fcid'));
		}
		$latLng = $this->getUserLocation($usr_id);
		if($latLng == NULL) {
		} else {
			$data_odetails['ULatitude'] = $latLng[0]["ULatitude"];
			$data_odetails['ULongitude'] = $latLng[0]["ULongitude"];
		}
		$this->db->insert($this->_table_name, $data_odetails);
		$OrderId = $this->db->insert_id();
		$OId = $this->insert_order_id($OrderId);
		if($this->input->cookie('res_order') != "" && $this->input->cookie('res_service_id') != "") {
			$this->load->model('status_m');
			$csdata['StatusId'] = $this->status_m->get_reschedule_status(intval($this->input->cookie('res_service_id')));
			$this->db->where('OId', $this->input->cookie('res_order'));
			$this->db->update('oservicedetail', $csdata);
			$crdata['MRRemarks'] = "This order was ReScheduled / Modified to " . $OId;
			$this->db->where('OId', $this->input->cookie('res_order'));
			$this->db->update('odetails', $crdata);
			$paymtdata['OId'] = $OId;
			$this->db->where('OId', $this->input->cookie('res_order'));
			$this->db->update('opaymtdetail', $paymtdata);
			delete_cookie('res_order');
			delete_cookie('res_service_id');
			delete_cookie('active_corder');
		}
		return $OId;
	}
	public function app_create_order($user_addr_id, $usr_id, &$query_row) {
		$data_odetails = array();
		$data_odetails['ODate'] = $query_row->ODate;
		$data_odetails['ServiceId'] = $servicetype = intval($query_row->ServiceId);
		$data_odetails['UserId'] = intval($usr_id);
		$data_odetails['CityId'] = intval($query_row->CityId);
		$data_odetails['UserAddrId'] = intval($user_addr_id);
		$data_odetails['BikeModelId'] = intval($query_row->BikeModelId);
		$data_odetails['isBreakdown'] = intval($query_row->isBreakdown);
		$data_odetails['SlotHour'] = floatval($query_row->SlotHour);
		$data_odetails['UserIp'] = $this->input->ip_address();
		$data_odetails['UserDevice'] = $this->get_user_agent();
		if($query_row->CouponId) {
			$data_odetails['CouponId'] = intval($query_row->CouponId);
		}
		if($query_row->FCouponId) {
			$data_odetails['FCouponId'] = intval($query_row->FCouponId);
		}
		$latLng = $this->getUserLocation($usr_id);
		if($latLng == NULL) {
		} else {
			$data_odetails['ULatitude'] = $latLng[0]["ULatitude"];
			$data_odetails['ULongitude'] = $latLng[0]["ULongitude"];
		}
		$this->db->insert($this->_table_name, $data_odetails);
		$OrderId = $this->db->insert_id();
		$OId = $this->insert_order_id($OrderId, $servicetype);
		return $OId;
	}
	public function vendor_create_order($user_addr_id, $usr_id) {
		$data_odetails = array();
		$data_odetails['ODate'] = date('Y-m-d', strtotime($this->input->post('user_date')));
		$data_odetails['ServiceId'] = $servicetype = intval($this->input->post('user_service'));
		$data_odetails['UserId'] = intval($usr_id);
		$data_odetails['CityId'] = intval($this->db->select('scaddrsplit.CityId')->from('scaddrsplit')->where('scaddrsplit.ScId', intval($this->session->userdata('v_sc_id')))->limit(1)->get()->row()->CityId);
		$data_odetails['UserAddrId'] = intval($user_addr_id);
		$data_odetails['BikeModelId'] = intval($this->input->post('user_bikemodel'));
		$data_odetails['BikeNumber'] = trim($this->input->post('reg_num') . ' ' . $this->input->post('bike_num'));
		$data_odetails['SlotHour'] = floatval($this->input->post('user_slot'));
		$data_odetails['UserIp'] = $this->input->ip_address();
		$data_odetails['TieupId'] = intval($this->input->post('business'));
		$data_odetails['isGrievance'] = intval($this->input->post('isGrievance'));
		if($data_odetails['TieupId'] == 0) {
			$data_odetails['TieupId'] = 1;
		}
		$data_odetails['UserDevice'] = $this->get_user_agent();
		$this->db->insert($this->_table_name, $data_odetails);
		$OrderId = $this->db->insert_id();		
		$OId = $this->insert_order_id($OrderId, $servicetype);
		if ($servicetype == 1) {
			$this->db->select('SlotId');
			$this->db->from('slots');
			$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
			$this->db->where('Day', date('Y-m-d', strtotime($this->input->post('user_date'))));
			$this->db->where('Hour', $this->input->post('user_slot'));
			$this->db->limit(1);
			$query = $this->db->get();
			$slot_id = $query->result_array()[0]['SlotId'];
			$data = array(
				'SlotId' => $slot_id,
				'Status' => 1
			);
			$this->db->insert('slotsbuffer', $data);
		}
		return $OId;
	}
	public function insert_amenities($OId) {
		if ($this->input->post('amtys') != '') {
			$amtys = explode(',', $this->input->post('amtys'));
		}
		if (isset($amtys) && count($amtys) > 0) {
			$count = 0;
			foreach ($amtys as $amty) {
				$mdata[$count]['OId'] = $OId;
				$mdata[$count]['AmId'] = intval($amty);
				$count += 1;
			}
			$this->db->insert_batch('oamenitydetail', $mdata);
		} else {
			$amtys = '';
		}
		return $amtys;
	}
	public function insert_asers($OId) {
		if ($this->input->post('asers') != '') {
			$asers = explode(',', $this->input->post('asers'));
		}
		if (isset($asers) && count($asers) > 0) {
			$count = 0;
			foreach ($asers as $aser) {
				$mdata[$count]['OId'] = $OId;
				$mdata[$count]['AServiceId'] = intval($aser);
				$count += 1;
			}
			$this->db->insert_batch('oaserdetail', $mdata);
		} else {
			$asers = '';
		}
	}
	public function vendor_insert_amenities($OId) {
		$amtys = $this->input->post('oamenities');
		if (isset($amtys) && count($amtys) > 0) {
			$count = 0;
			foreach ($amtys as $amty) {
				$mdata[$count]['OId'] = $OId;
				$mdata[$count]['AmId'] = intval($amty);
				$count += 1;
			}
			$this->db->insert_batch('oamenitydetail', $mdata);
		} else {
			$amtys = '';
		}
		return $amtys;
	}
	public function vendor_insert_asers($OId) {
		$asers = $this->input->post('asers');
		if (isset($asers) && count($asers) > 0) {
			$count = 0;
			foreach ($asers as $aser) {
				$mdata[$count]['OId'] = $OId;
				$mdata[$count]['AServiceId'] = intval($aser);
				$count += 1;
			}
			$this->db->insert_batch('oaserdetail', $mdata);
		} else {
			$asers = '';
		}
	}
	public function insert_insurance($OId) {
		$data_insurance['OId'] = $OId;
		$data_insurance['RegYear'] = intval($this->input->post('regYear'));
		$data_insurance['ExpiryDays'] = intval($this->input->post('expDate'));
		$data_insurance['PreviousInsurer'] = intval($this->input->post('prevIns'));
		if ($this->input->post('isClaimed') != '') {
			$data_insurance['IsClaimedBefore'] = intval($this->input->post('isClaimed'));
		}
		$this->db->insert('oinsurancedetail', $data_insurance);
	}
	public function app_insert_oservicedetail($OId, $amtys, $price, &$query_row) {
		$this->load->model('status_m');
		if ($query_row->ServiceId != 3) {
			$data_oservice['OId'] = $OId;
			$data_oservice['ServiceDesc2'] = $query_row->UserComments;
			$data_oservice['ScId'] = intval($query_row->ScId);
			$this->load->model('servicecenter_m');
			$this->load->model('amenity_m');
			$this->load->model('opaymtdetail_m');
			$data_oservice['EstPrice'] = floatval($price);
			$data_oservice['StatusId'] = $this->status_m->get_init_status_service($query_row->ServiceId);
			$this->db->insert('oservicedetail', $data_oservice);
			$this->db->select('odetails.ServiceId, odetails.ODate');
			$this->db->from('odetails');
			$this->db->join('service', 'service.ServiceId = odetails.ServiceId');
			$this->db->where('odetails.OId', $OId);
			$this->db->limit(1);
			$query = $this->db->get();
			$result = $query->result_array();
			if (count($result) > 0) {
				$adminNotifyFlag['OId'] = $OId;
				$adminNotifyFlag['ODate'] = $result[0]['ODate'];
				$adminNotifyFlag['new_order'] = 1;
				$adminNotifyFlag['ScId'] = intval($query_row->ScId);
				$this->db->insert('admin_notification_flags', $adminNotifyFlag);
				$and_reg_ids = $this->get_all_active_admin_devices();
				if(count($and_reg_ids) > 0) {
					$and_push_msg_data = array("message" => "New order " . $OId . " received", "tag" => "odetailwithoutjobcard", "oid" => $OId);
					$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
				}
			}
		} else {
			$sc_ids = explode(',', $query_row->ScIds);
			$status_id = $this->status_m->get_init_status_service($query_row->ServiceId);
			$count = 0;
			foreach($sc_ids as $sc_id) {
				$data_oservice[$count]['OId'] = $OId;
				$data_oservice[$count]['ServiceDesc1'] = $query_row->QType;
				$data_oservice[$count]['ServiceDesc2'] = $query_row->UserComments;
				$data_oservice[$count]['ScId'] = intval($sc_id);
				$data_oservice[$count]['StatusId'] = $status_id;
				$count += 1;
			}
			$this->db->insert_batch('oservicedetail', $data_oservice);
		}
	}
	public function insert_oservicedetail($OId, $amtys, $price) {
		$this->load->model('status_m');
		if ($this->input->cookie('servicetype') != 3) {
			$data_oservice['OId'] = $OId;
			$data_oservice['ServiceDesc2'] = $this->input->post('comments');
			$data_oservice['ScId'] = intval($this->input->cookie('sc_id'));
			$this->load->model('servicecenter_m');
			$this->load->model('amenity_m');
			$this->load->model('opaymtdetail_m');
			$data_oservice['EstPrice'] = floatval($price);
			$data_oservice['StatusId'] = $this->status_m->get_init_status_service();
			$this->db->insert('oservicedetail', $data_oservice);
			$this->db->select('service.ServiceId, ODate');
			$this->db->from('odetails');
			$this->db->join('service', 'service.ServiceId = odetails.ServiceId');
			$this->db->where('odetails.OId', $OId);
			$this->db->limit(1);
			$query = $this->db->get();
			$result = $query->result_array();
			if (count($result) > 0) {
				$adminNotifyFlag['OId'] = $OId;
				$adminNotifyFlag['ODate'] = $result[0]['ODate'];
				$adminNotifyFlag['new_order'] = 1;
				$adminNotifyFlag['ScId'] = intval($this->input->cookie('sc_id'));
				$this->db->insert('admin_notification_flags', $adminNotifyFlag);
				$and_reg_ids = $this->get_all_active_admin_devices();
				if(count($and_reg_ids) > 0) {
					$and_push_msg_data = array("message" => "New order " . $OId . " received", "tag" => "odetailwithoutjobcard", "oid" => $OId);
					$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
				}
			}
		} else {
			$sc_ids = explode(',', $this->input->cookie('sc_ids'));
			$status_id = $this->status_m->get_init_status_service();
			$count = 0;
			foreach($sc_ids as $sc_id) {
				$data_oservice[$count]['OId'] = $OId;
				$data_oservice[$count]['ServiceDesc1'] = $this->input->cookie('qtype');
				$data_oservice[$count]['ServiceDesc2'] = $this->input->post('comments');
				$data_oservice[$count]['ScId'] = intval($sc_id);
				$data_oservice[$count]['StatusId'] = $status_id;
				$count += 1;
			}
			$this->db->insert_batch('oservicedetail', $data_oservice);
		}
	}
	public function vendor_insert_oservicedetail($OId, $amtys, $price) {
		$this->load->model('status_m');
		$data_oservice['OId'] = $OId;
		$data_oservice['ServiceDesc1'] = 'Order Placed by Vendor';
		$data_oservice['ServiceDesc2'] = $this->input->post('comments');
		$data_oservice['ScId'] = intval($this->session->userdata('v_sc_id'));
		$this->load->model('servicecenter_m');
		$this->load->model('amenity_m');
		$this->load->model('opaymtdetail_m');
		$data_oservice['EstPrice'] = floatval($price);
		$data_oservice['StatusId'] = $this->status_m->get_init_status_service(intval($this->input->post('user_service')));
		$this->db->insert('oservicedetail', $data_oservice);
		$this->db->select('service.ServiceId, ODate');
		$this->db->from('odetails');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId');
		$this->db->where('odetails.OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) > 0) {
			$adminNotifyFlag['OId'] = $OId;
			$adminNotifyFlag['ODate'] = $result[0]['ODate'];
			$adminNotifyFlag['new_order'] = 1;
			$adminNotifyFlag['ScId'] = intval($this->session->userdata('v_sc_id'));
			$this->db->insert('admin_notification_flags', $adminNotifyFlag);
			$and_reg_ids = $this->get_all_active_admin_devices();
			if(count($and_reg_ids) > 0) {
				$and_push_msg_data = array("message" => "New order " . $OId . " received", "tag" => "odetailwithoutjobcard", "oid" => $OId);
				$this->send_gcm_request($and_reg_ids, $and_push_msg_data);
			}
		}
	}
	private function get_user_agent() {
		$this->load->library('user_agent', NULL, 'agent');
		if ($this->agent->is_mobile()) {
			if($this->agent->is_mobile('iphone')) {
				return 'iphone';
			} elseif($this->agent->is_mobile('android')) {
				return 'android';
			} else {
				return 'mob';
			}
		} else {
			return 'pc';
		}
	}
	private function insert_order_id($OrderId, $servicetype = NULL) {
		if(!isset($servicetype) || empty($servicetype)) {
			$servicetype = intval($this->input->cookie('servicetype'));
		}
		$data['OId'] = generateOrderId(intval($OrderId), $servicetype);
		$where = "OrderId = " . intval($OrderId);
		$query_string = $this->db->update_string($this->_table_name, $data, $where);
		$query_string = str_replace('UPDATE', 'UPDATE IGNORE', $query_string);
		$this->db->query($query_string);
		if($this->db->affected_rows() == 1) {
			return $data['OId'];
		} else {
			$this->insert_order_id($OrderId, $servicetype);
		}
	}
	private function getUserLocation($userId) {
		$this->db->select('ULatitude, ULongitude');
		$this->db->from('odetails');
		$this->db->where('odetails.UserId', $userId);
		$this->db->order_by('odetails.TimeStamp', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
	public function getInvoiceDate($OId) {
		$this->db->select('InvoiceDate');
		$this->db->from('odetails');
		$this->db->where('OId', $OId);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0]["InvoiceDate"];
		}
	}
	public function setInvoiceDate($OId, $InvoiceDate) {
		$odetails['InvoiceDate'] = $InvoiceDate;
		$this->db->where('OId', $OId);
		$this->db->update('odetails', $odetails);
	}	
	public function get_odate_by_oid($oid) {
		$this->db->select('odetails.ODate');
		$this->db->from('odetails');
		$this->db->where('odetails.OId', $oid);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		if ($result) {
			return $result['ODate'];
		} else {
			return NULL;
		}
	}
	public function get_scid_by_oid($oid) {
		$this->db->select('oservicedetail.ScId');
		$this->db->from('oservicedetail');
		$this->db->where('oservicedetail.OId', $oid);
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->row_array();
		if ($result) {
			return $result['ScId'];
		} else {
			return NULL;
		}
	}
	public function get_renewal_dates($bikeregnum, $OId) {
		$this->db->select('odetails.*');
		$this->db->from('odetails');
		$this->db->where('odetails.BikeNumber', $bikeregnum);
		$this->db->order_by('odetails.TimeStamp', 'desc');
		$this->db->limit(1);
		$query = $this->db->get();
		$results = $query->row_array();
		if ($results) {
			$odetails['insurance_renewal_date'] = $results['insurance_renewal_date'];
			$odetails['puc_renewal_date'] = $results['puc_renewal_date'];
			$odetails['service_reminder_date'] = $results['service_reminder_date'];
			return $odetails;
		} else {
			return NULL;
		}
	}
	public function get_all_reminders($field, $isCron = FALSE) {
		$this->db->select('odetails.OId, BikeModelName, BikeCompanyName, ODate, user.Phone, service.ServiceId, UserName, SlotHour, ServiceName, fupstatus.FupStatusName, odetails.service_reminder_date, odetails.insurance_renewal_date, odetails.puc_renewal_date');
		$this->db->from('odetails');
		$this->db->join('fupstatus', 'fupstatus.FupStatusId = odetails.LastFupStatusId', 'left');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('statushistory', 'statushistory.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId', 'left');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		if(!$isCron) {
			if(intval($this->session->userdata('a_city_id')) > 0) {
				$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
			}
		} else {
			$this->db->where('odetails.' . $field . '_flag', 0);
		}
		$this->db->where('odetails.LastFupStatusId !=', 22);
		$this->db->where('odetails.LastFupStatusId !=', 24);
		$this->db->where('service.ServiceId !=', 3);
		$this->db->where('DATE(odetails.' . $field . ') <=', date("Y-m-d", strtotime("now + 15 days")));
		$this->db->group_by('odetails.OId');
		$this->db->order_by('odetails.service_reminder_date', 'desc');
		$this->db->order_by('odetails.insurance_renewal_date', 'desc');
		$this->db->order_by('odetails.puc_renewal_date', 'desc');
		$this->db->order_by('odetails.ODate', 'desc');
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return array();
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
				$result_rows[$count]['reminder_type'] = $field;
				$result_rows[$count]['reminder_date'] = date("F d, Y - l", strtotime($row[$field]));
				$count++;
			}
			return $result_rows;
		}
	}
}