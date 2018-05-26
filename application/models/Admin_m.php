<?php
class Admin_m extends G6_Model {
	protected $_table_name = 'admin';
	protected $_primary_key = 'AdminId';
	protected $_order_by = 'AdminName';
	public function __construct() {
		parent::__construct();
	}
	public function applogin() {
		$orig_user = $this->get_by(array(
			'Phone' => $this->input->post('phone', TRUE),
			'isActive' => 1
		), TRUE);
		if($orig_user) {
			$salt = $orig_user->Salt;
			if ($orig_user->Pwd == generate_salted_hash($this->input->post('password', TRUE), $salt)) {
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
	public function login() {
		$orig_user = $this->get_by(array(
			'Phone' => $this->input->post('phone'),
		), TRUE);
		if($orig_user) {
			$this->load->model('city_m');
			$city = $this->city_m->get(intval($orig_user->CityId));
			$ad_city = $city->CityName;
			$ad_city_id = $city->CityId;
			$salt = $orig_user->Salt;
			if ($orig_user->Pwd == generate_salted_hash($this->input->post('password'), $salt)) {
				$session_data = array(
					'a_name' => $orig_user->AdminName,
					'a_phone' => $orig_user->Phone,
					'a_email' => $orig_user->Email,
					'a_id' => $orig_user->AdminId,
					'a_role' => $orig_user->UserPrivilege,
					'a_city' => $ad_city,
					'a_city_id' => $ad_city_id,
					'a_loggedin' => TRUE
				);
				$this->session->set_userdata($session_data);
			} else {
				$this->set_query_cookie('login_errors', 'Invalid Mobile / Password Combination');
			}
		} else {
			$this->set_query_cookie('login_errors', 'Invalid Mobile / Password Combination');
		}
	}
	public function logout() {
		$this->session->sess_destroy();
	}
	public function loggedin() {
		return (bool) $this->session->userdata('a_loggedin');
	}
	public function is_unique_ph($ph) {
		$orig_user = $this->get_by(array(
			'Phone' => $ph
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
			if($orig_user[0]->AdminId == $id) {
				return TRUE;
			} else {
				return $this->is_unique_ph($ph);
			}
		} else {
			return $this->is_unique_ph($ph);
		}
	}
	public function is_valid_oid($oid = NULL) {
		if(intval($this->session->userdata('a_city_id')) == -1) {
			return TRUE;
		} else {
			return intval($this->session->userdata('a_city_id')) === intval($this->db->select('odetails.CityId')->from('odetails')->where('OId', $oid)->limit(1)->get()->row()->CityId);
		}
	}
	public function is_sc_in_city($scid = NULL) {
		if(intval($this->session->userdata('a_city_id')) == -1) {
			return TRUE;
		} else {
			return intval($this->session->userdata('a_city_id')) === intval($this->db->select('scaddrsplit.CityId')->from('scaddrsplit')->where('scaddrsplit.ScId', $scid)->limit(1)->get()->row()->CityId);
		}
	}
	public function get_serviced_orders($cityid = NULL) {
		$this->db->select('odetails.OId, odetails.ODate, odetails.TieupId, user.UserName, user.Phone, servicecenter.ScName, fupstatus.FupStatusName AS EFupStatusName, ulocation.LocationName AS UserLocation, odetails.SlotHour, sclocation.LocationName AS ScLocation, odetails.ULatitude AS uolati, odetails.ULongitude AS uolongi, ulocation.Latitude AS ulati, ulocation.Longitude AS ulongi');
		$this->db->from('odetails');
		$this->db->join('fupstatus', 'fupstatus.FupStatusId = odetails.LastFupStatusId', 'left');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('servicecenter', 'servicecenter.ScId = oservicedetail.ScId', 'left');
		$this->db->join('scaddrsplit', 'scaddrsplit.ScId = servicecenter.ScId', 'left');
		$this->db->join('useraddr', 'useraddr.UserAddrId = odetails.UserAddrId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		$this->db->join('location AS ulocation', 'ulocation.LocationId = useraddr.LocationId', 'left');
		$this->db->join('location AS sclocation', 'sclocation.LocationId = scaddrsplit.LocationId', 'left');
		if(intval($cityid) > 0) {
			$this->db->where('odetails.CityId', intval($cityid));
		}
		$this->db->where("(odetails.FinalFlag = '0')", NULL, FALSE);
		$this->db->where("(oservicedetail.StatusId = '7'", NULL, FALSE);
		$this->db->or_where("oservicedetail.StatusId = '14')", NULL, FALSE);
		$this->db->group_by('odetails.OId');
		$this->db->order_by('odetails.ODate', 'desc');
		$this->db->order_by('odetails.OId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			$scount = 0;
			foreach($results as &$result) {
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
	public function get_allotted_orders($cityid = NULL) {
		$this->db->select('odetails.OId, odetails.ODate, odetails.TieupId, user.UserName, user.Phone, servicecenter.ScName, fupstatus.FupStatusName AS EFupStatusName, ulocation.LocationName AS UserLocation, odetails.SlotHour, sclocation.LocationName AS ScLocation, odetails.ULatitude AS uolati, odetails.ULongitude AS uolongi, ulocation.Latitude AS ulati, ulocation.Longitude AS ulongi');
		$this->db->from('odetails');
		$this->db->join('fupstatus', 'fupstatus.FupStatusId = odetails.LastFupStatusId', 'left');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('servicecenter', 'servicecenter.ScId = oservicedetail.ScId', 'left');
		$this->db->join('scaddrsplit', 'scaddrsplit.ScId = servicecenter.ScId', 'left');
		$this->db->join('useraddr', 'useraddr.UserAddrId = odetails.UserAddrId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		$this->db->join('location AS ulocation', 'ulocation.LocationId = useraddr.LocationId', 'left');
		$this->db->join('location AS sclocation', 'sclocation.LocationId = scaddrsplit.LocationId', 'left');
		if(intval($cityid) > 0) {
			$this->db->where('odetails.CityId', intval($cityid));
		}
		$this->db->where('status.Order', 1);
		$this->db->where('service.ServiceId !=', 3);
		$this->db->group_by('odetails.OId');
		$this->db->order_by('odetails.ODate', 'desc');
		$this->db->order_by('odetails.OId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			$scount = 0;
			foreach($results as &$result) {
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
	public function get_unallotted_orders($cityid = NULL) {
		$this->db->select('odetails.OId, odetails.ODate, odetails.TieupId, user.UserName, user.Phone, servicecenter.ScName, fupstatus.FupStatusName AS EFupStatusName, ulocation.LocationName AS UserLocation, odetails.SlotHour, sclocation.LocationName AS ScLocation, odetails.ULatitude AS uolati, odetails.ULongitude AS uolongi, ulocation.Latitude AS ulati, ulocation.Longitude AS ulongi');
		$this->db->from('odetails');
		$this->db->join('fupstatus', 'fupstatus.FupStatusId = odetails.LastFupStatusId', 'left');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('servicecenter', 'servicecenter.ScId = oservicedetail.ScId', 'left');
		$this->db->join('scaddrsplit', 'scaddrsplit.ScId = servicecenter.ScId', 'left');
		$this->db->join('useraddr', 'useraddr.UserAddrId = odetails.UserAddrId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		$this->db->join('location AS ulocation', 'ulocation.LocationId = useraddr.LocationId', 'left');
		$this->db->join('location AS sclocation', 'sclocation.LocationId = scaddrsplit.LocationId', 'left');
		if(intval($cityid) > 0) {
			$this->db->where('odetails.CityId', intval($cityid));
		}
		$this->db->where('status.Order', 0);
		$this->db->where('service.ServiceId !=', 3);
		$this->db->group_by('odetails.OId');
		$this->db->order_by('odetails.ODate', 'desc');
		$this->db->order_by('odetails.OId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			$scount = 0;
			foreach($results as &$result) {
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
	public function app_get_todays_orders($cityid = NULL) {
		$this->db->select('odetails.OId, odetails.ODate, odetails.TieupId, user.UserName, user.Phone, servicecenter.ScName, exfupstatus.EFupStatusName, ulocation.LocationName AS UserLocation, odetails.SlotHour, sclocation.LocationName AS ScLocation, odetails.ULatitude AS uolati, odetails.ULongitude AS uolongi, ulocation.Latitude AS ulati, ulocation.Longitude AS ulongi');
		$this->db->from('odetails');
		$this->db->join('exfupstatus', 'exfupstatus.EFupStatusId = odetails.LastExFupStatusId', 'left');
		$this->db->join('service', 'service.ServiceId = odetails.ServiceId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('status', 'status.StatusId = oservicedetail.StatusId', 'left');
		$this->db->join('servicecenter', 'servicecenter.ScId = oservicedetail.ScId', 'left');
		$this->db->join('scaddrsplit', 'scaddrsplit.ScId = servicecenter.ScId', 'left');
		$this->db->join('useraddr', 'useraddr.UserAddrId = odetails.UserAddrId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		$this->db->join('location AS ulocation', 'ulocation.LocationId = useraddr.LocationId', 'left');
		$this->db->join('location AS sclocation', 'sclocation.LocationId = scaddrsplit.LocationId', 'left');
		if(intval($cityid) > 0) {
			$this->db->where('odetails.CityId', intval($cityid));
		}
		$this->db->where('odetails.ServiceId !=', 3);
		$this->db->where('odetails.FinalFlag', 0);
		$this->db->where('odetails.ODate <=', date("Y-m-d", strtotime("now")));
		$this->db->where("((odetails.ServiceId = '1' AND status.Order < '4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '2' AND status.Order <'4' AND status.Order >= '0')", NULL, FALSE);
		$this->db->or_where("(odetails.ServiceId = '4' AND status.Order < '3' AND status.Order >= '0'))", NULL, FALSE);
		$this->db->group_by('odetails.OId');
		$this->db->order_by('odetails.ODate', 'desc');
		$this->db->order_by('odetails.OId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			$scount = 0;
			foreach($results as &$result) {
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
	private function set_query_cookie($name, $value) {
		$cookie = array(
			'name'   => $name,
			'value'  => $value,
			'expire' => '86500',
			'secure' => FALSE
		);
		$this->input->set_cookie($cookie);
	}
}