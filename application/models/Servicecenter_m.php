<?php
class Servicecenter_m extends G6_Model {
	protected $_table_name = 'servicecenter';
	protected $_primary_key = 'ScId';
	protected $_order_by = 'ScName';
	public function __construct() {
		parent::__construct();
	}
	public function get_new() {
		$servicecenter = new stdClass();
		$servicecenter->ScName = '';
		$servicecenter->Owner = '';
		$servicecenter->Rating = '';
		return $servicecenter;
	}
	public function get_sc_phone_by_oid($oid) {
		$this->db->select('sccontact.CPhone AS Phone');
		$this->db->from('oservicedetail');
		$this->db->join('sccontact', 'oservicedetail.ScId = sccontact.ScId');
		$this->db->where('oservicedetail.OId', $oid);
		$this->db->group_by('sccontact.ScId');
		$this->db->limit(3);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_sc_by_oid($oid) {
		$this->db->select('odetails.ODate, servicecenter.ScId');
		$this->db->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('servicecenter', 'servicecenter.ScId = oservicedetail.ScId', 'left');
		$this->db->where('odetails.OId', $oid);
		$this->db->limit(1);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results[0];
		}
	}
	public function get_ptsc_by_id($sc_id) {
		return $this->get_sc_details(intval($sc_id));
	}
	public function get_offers_for_users($sc_id) {
		$this->db->select('*');
		$this->db->from('offers');
		$this->db->where('ScId', $sc_id);
		$this->db->where('OFrom <= ', date('Y-m-d', strtotime('now')));
		$this->db->where('OTill >= ', date('Y-m-d', strtotime('now')));
		$this->db->order_by('OfferId', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			foreach($results as &$result) {
				$result['OTill'] = date('d F, Y', strtotime($result['OTill']));
			}
			return $results;
		}
	}
	public function get_excls_for_users($sc_id) {
		$this->db->select('*');
		$this->db->from('exclusives');
		$this->db->where('ScId', $sc_id);
		$this->db->order_by('ExclId', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_scmedia_for_users($sc_id) {
		$data['ScId'] = $sc_id;
		$data['ScType'] = 'sc';
		$data['MediaType'] = 'info';
		$data['FileType'] = 'img';
		$this->db->select('FileData');
		$this->db->where($data);
		$this->db->order_by('MediaOrder', 'asc');
		$query = $this->db->get('scmedia');
		$results = $query->result_array();
		if(count($results) > 0) {
			foreach($results as $result) {
				$final_results[] = get_awss3_url('uploads/scmedia/sc/img/' . $result['FileData']);
			}
			return $final_results;
		} else {
			return NULL;
		}
	}
	public function get_sc_media_details() {
		$data['ScId'] = intval($this->session->userdata('v_sc_id'));
		$data['ScType'] = $this->session->userdata('v_sc_type');
		$this->db->where($data);
		$this->db->order_by('MediaOrder', 'asc');
		$query = $this->db->get('scmedia');
		$results = $query->result_array();
		if(count($results) > 0) {
			foreach($results as $result) {
				$final_results[$result['MediaOrder']] = 'src="' . get_awss3_url('uploads/scmedia/' . $this->session->userdata('v_sc_type') . '/' . $result['FileType'] . '/' . $result['FileData']) . '"';
			}
			return $final_results;
		} else {
			return NULL;
		}
	}
	public function get_service_provider($id) {
		$this->db->select('servicecenter.ScName, sccontact.CPhone AS Phone');
		$this->db->from($this->_table_name);
		$this->db->join('sccontact', 'sccontact.ScId = servicecenter.ScId');
		$this->db->where('servicecenter.ScId', intval($id));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0];
		}
	}
	public function get_name_rating($id) {
		$this->db->select('ScName, Rating, RatersCount');
		$this->db->from($this->_table_name);
		$this->db->where('ScId', intval($id));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0];
		}
	}
	public function amenities_by_id($id) {
		$this->db->select('AmName, AmDesc, AmIcon, AmIcon1, AmIcon2, iAmIcon1, iAmIcon2');
		$this->db->from('amenity');
		$this->db->join('scam', 'scam.AmId = amenity.AmId');
		$this->db->where('ScId', intval($id));
		$this->db->where('AmCode', 1);
		$query = $this->db->get();
		$results = $query->result_array();
		return $results;
	}
	public function price_by_id($id, $bmid = NULL, $serid = NULL) {
		$this->db->select('Price');
		$this->db->from('sprice');
		$this->db->where('ScId', intval($id));
		if($serid !== NULL) {
			$this->db->where('ServiceId', intval($serid));
		} else {
			$this->db->where('ServiceId', intval($this->input->cookie('servicetype')));
		}
		if($bmid !== NULL) {
			$this->db->where('BikeModelId', intval($bmid));
		} else {
			$this->db->where('BikeModelId', intval($this->input->cookie('model')));
		}
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0]['Price'];
		}
	}
	public function get_service_price() {
		return $this->price_by_id($this->input->cookie('sc_id'));
	}
	public function sc_details_for_slot($id) {
		$this->db->select('ScName, Latitude, Longitude, DefaultSlots, SlotDuration, SlotType, StartHour, EndHour');
		$this->db->from('servicecenter AS A');
		$this->db->join('scaddrsplit AS C', 'C.ScId = A.ScId');
		$this->db->join('scaddr AS B', 'B.ScAddrSplitId = C.ScAddrSplitId');
		$this->db->where('A.ScId', intval($id));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0];
		}
	}
	public function get_sc_details($ScId = NULL) {
		$this->db->select('AddrLine1, AddrLine2, LocationName, Landmark, CityName, city.CityId, Pincode, sccontact.AltPhone, sccontact.Email, Landline, ScName, Owner, scaddr.Latitude, scaddr.Longitude, scaddr.ScAddrSplitId, sccontact.CPhone AS Phone, sccontact.CPerson, Rating');
		$this->db->from('servicecenter');
		$this->db->join('sccontact', 'sccontact.ScId = servicecenter.ScId', 'left');
		$this->db->join('scaddrsplit', 'scaddrsplit.ScId = servicecenter.ScId', 'left');
		$this->db->join('scaddr', 'scaddr.ScAddrSplitId = scaddrsplit.ScAddrSplitId', 'left');
		$this->db->join('location', 'location.LocationId = scaddrsplit.LocationId', 'left');
		$this->db->join('city', 'city.CityId = location.CityId', 'left');
		if($ScId === NULL) {
			$this->db->where('servicecenter.ScId', intval($this->session->userdata('v_sc_id')));
		} else {
			$this->db->where('servicecenter.ScId', $ScId);
		}
		$this->db->group_by('servicecenter.ScId');
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0];
		}
	}
	public function updt_scaddr() {
		$this->load->model('location_m');
		$data_scaddr = array();
		$data_scaddr['ScId'] = intval($this->session->userdata('v_sc_id'));
		$data_scaddr['AddrLine1'] = $this->input->post('addr1');
		$data_scaddr['AddrLine2'] = $this->input->post('addr2');
		$temp = $this->location_m->location_id_by_name($this->input->post('location'));
		$data_scaddr['LocationId'] = intval($temp['LocationId']);
		$data_scaddr['Landmark'] = $this->input->post('landmark');
		$data_scaddr['CityId'] = $this->input->post('city');
		$this->db->where('ScAddrSplitId', intval($this->input->post('addr')));
		$this->db->update('scaddrsplit', $data_scaddr);
		$data_sca['Addr'] = $data_scaddr['AddrLine1'] . ', ' . $data_scaddr['AddrLine2'] . ', ' . $data_scaddr['Landmark'] . ', ' . $this->input->post('location');
		$data_sca['Latitude'] = $this->input->post('lat_num');
		$data_sca['Longitude'] = $this->input->post('lon_num');
		$this->db->where('ScAddrSplitId', intval($this->input->post('addr')));
		$this->db->update('scaddr', $data_sca);
		$data = array('ScName' => $this->input->post('sc_name'), 'Owner' => $this->input->post('owner_name'));
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->update('servicecenter', $data);
		$this->session->set_userdata('v_sc_name', $this->input->post('full_name'));
		$data_scc['CPhone'] = $this->input->post('phNum');
		$data_scc['Landline'] = $this->input->post('landline');
		$data_scc['Email'] = $this->input->post('email');
		$data_scc['AltPhone'] = $this->input->post('altphNum');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->update('sccontact', $data_scc);
	}
	public function get_sc_ad_contact($ScId) {
		$this->db->select('AddrLine1, AddrLine2, LocationName, Landmark, CityName, Pincode, sccontact.Email, Landline, sccontact.CPhone AS Phone');
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
			$address[0] = '';
			$address[0] .= '<div>' . convert_to_camel_case($result[0]['AddrLine1']) . '</div>';
			$address[0] .= '<div>' . convert_to_camel_case($result[0]['AddrLine2']) . '</div>';
			$address[0] .= '<div>' . convert_to_camel_case($result[0]['LocationName']) . ', ';
			$address[0] .= convert_to_camel_case($result[0]['Landmark']) . '</div>';
			$address[0] .= '<div>' . convert_to_camel_case($result[0]['CityName']) . ' - ';
			$address[0] .= $result[0]['Pincode'] . '</div>';
			$address[0] .= '<div>Phone: ' . $result[0]['Phone'].'</div>';
			$address[1] = $result[0]['Phone'];
			$address[2] = $result[0]['Email'];
			$address[3] = $result[0]['Landline'];
			return $address;
		}
	}
	public function get_app_scad_contact($ScId) {
		$this->db->select('AddrLine1, AddrLine2, LocationName, Landmark, CityName, Pincode, sccontact.Email, Landline, sccontact.CPhone AS Phone');
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
	public function get_sc_phone($sc_id) {
		$this->db->select('sccontact.Phone, sccontact.Landline, sccontact.CPhone');
		$this->db->from('sccontact');
		$this->db->join('servicecenter', 'servicecenter.ScId = sccontact.ScId');
		$this->db->where('servicecenter.ScId', intval($sc_id));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0];
		}
	}
	public function get_slots_for_vendor($start, $end) {
		$this->erase_expired_slots();
		$this->db->select('slots.SlotId, Day, Hour, EHour, Slots, COUNT(SlotBufferId) BufferedSlots');
		$this->db->from('slots');
		$this->db->join('slotsbuffer', 'slotsbuffer.SlotId = slots.SlotId', 'left');
		$this->db->where('slots.ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('slots.Day >=', $start);
		$this->db->where('slots.Day <', $end);
		$this->db->group_by('slots.SlotId');
		$this->db->order_by('slots.Day', 'asc');
		$this->db->order_by('slots.Hour', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function slot_buffered_dates($start, $end) {
		$this->erase_expired_slots();
		$this->db->select('Day, COUNT(SlotBufferId) AS BufferedSlots');
		$this->db->from('slots');
		$this->db->join('slotsbuffer', 'slotsbuffer.SlotId = slots.SlotId');
		$this->db->where('slots.ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('slotsbuffer.Status', 0);
		$this->db->where('slots.Day >=', $start);
		$this->db->where('slots.Day <', $end);
		$this->db->group_by('slots.Day');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach($results as $result) {
				if($result['BufferedSlots'] > 0) {
					$fresult[] = $result['Day'];
				}
			}
			return $fresult;
		}
	}
	public function are_slots_inserted($date) {
		$this->db->select('*');
		$this->db->from('slots');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('Day', $date);
		$this->db->limit(12);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	public function get_inserted_bulk_slots($dates, $scid = NULL) {
		$this->erase_expired_slots();
		$this->db->select('slots.SlotId, slots.ScId, Day, Hour, EHour, Slots, COUNT(SlotBufferId) BufferedSlots');
		$this->db->from('slots');
		$this->db->join('slotsbuffer', 'slotsbuffer.SlotId = slots.SlotId', 'left');
		if(isset($scid)) {
			$this->db->where('slots.ScId', intval($scid));
		} else {
			$this->db->where('slots.ScId', intval($this->session->userdata('v_sc_id')));
		}
		$this->db->where_in('slots.Day', $dates);
		$this->db->group_by('slots.SlotId');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return array();
		} else {
			return $results;
		}
	}
	public function set_empty_slots($date) {
		$sc = $this->get_by(array(
			'ScId' => intval($this->session->userdata('v_sc_id')),
		), TRUE);
		$default_slots = $sc->DefaultSlots;
		$slot_duration = floatval($sc->SlotDuration);
		$slot_type = intval($sc->SlotType);
		$count = 0;
		$i = $sc->StartHour;
		if($slot_type == 1) {
			for(; $i <= 11; $i += 0.5) {
				$data[$count]['ScId'] = intval($this->session->userdata('v_sc_id'));
				$data[$count]['Day'] = $date;
				$data[$count]['Hour'] = $i;
				$data[$count]['EHour'] = $i + 5.0;
				$data[$count]['Slots'] = $default_slots;
				$count += 1;
			}
			$i += 0.5;
		}
		for(; $i <= $sc->EndHour; $i += $slot_duration) {
			$data[$count]['ScId'] = intval($this->session->userdata('v_sc_id'));
			$data[$count]['Day'] = $date;
			$data[$count]['Hour'] = $i;
			$data[$count]['EHour'] = 0;
			$data[$count]['Slots'] = $default_slots;
			$count += 1;
		}
		$this->db->insert_batch('slots', $data);
	}
	public function get_slot_and_buffer_data($slotids) {
		$this->erase_expired_slots();
		$this->db->select('slots.SlotId, Day, Hour, EHour, Slots, COUNT(SlotBufferId) BufferedSlots');
		$this->db->from('slots');
		$this->db->join('slotsbuffer', 'slotsbuffer.SlotId = slots.SlotId', 'left');
		$this->db->where_in('slots.SlotId', $slotids);
		$this->db->group_by('slots.SlotId');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_slot_count($sc_id, $date) {
		$this->erase_expired_slots();
		$this->db->select('slots.SlotId, slots.Slots, COUNT(SlotBufferId) AS BufferedSlots');
		$this->db->from('slots');
		$this->db->join('slotsbuffer', 'slotsbuffer.SlotId = slots.SlotId', 'left');
		$this->db->where('ScId', intval($sc_id));
		$this->db->where('slots.Day', $date);
		$this->db->group_by('slots.SlotId');
		$this->db->limit(12);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			$sc_row = $this->get_by(array(
				'ScId' => intval($sc_id)
			), TRUE);
			$def_slots = intval($sc_row->DefaultSlots);
			$def_slot_du = intval($sc_row->SlotDuration);
			$slot_type = intval($sc_row->SlotType);
			if($slot_type == 1) {
				$slot_count = ((((12 - $sc_row->StartHour) * 2 ) - 1) * $def_slots) + (1 + floor(($sc_row->EndHour - 12) / $def_slot_du)) * $def_slots;
				return $slot_count;
			} else {
				return $def_slots * (1 + floor(($sc_row->EndHour - $sc_row->StartHour) / $def_slot_du));
			}
		} else {
			$another_result = 0;
			foreach ($results as $result) {
				$another_result += intval($result['Slots']) - intval($result['BufferedSlots']);
			}
			return $another_result;
		}
	}
	public function set_get_slots($sc_id, $date, $default_slots, $slot_interval, $slot_type, $sHour, $eHour) {
		$this->erase_expired_slots();
		$this->db->select('slots.*, COUNT(SlotBufferId) AS BufferedSlots');
		$this->db->from('slots');
		$this->db->join('slotsbuffer', 'slotsbuffer.SlotId = slots.SlotId', 'left');
		$this->db->where('ScId', intval($sc_id));
		$this->db->where('slots.Day', $date);
		$this->db->group_by('slots.SlotId');
		$this->db->limit(12);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			$slot_interval = intval($slot_interval);
			$count = 0;
			$i = $sHour;
			if($slot_type == 1) {
				for(; $i <= 11; $i += 0.5) {
					$data[$count]['ScId'] = intval($sc_id);
					$data[$count]['Day'] = $date;
					$data[$count]['Hour'] = $i;
					$data[$count]['EHour'] = $i + 5.0;
					$data[$count]['Slots'] = $default_slots;
					$count += 1;
				}
				$i += 0.5;
			}
			for(; $i <= $eHour; $i += $slot_interval) {
				$data[$count]['ScId'] = intval($sc_id);
				$data[$count]['Day'] = $date;
				$data[$count]['Hour'] = $i;
				$data[$count]['EHour'] = 0;
				$data[$count]['Slots'] = $default_slots;
				$count += 1;
			}
			$this->db->insert_batch('slots', $data);
			return $data;
		} else {
			$another_result = array(array());
			$count = 0;
			foreach ($results as $result) {
				$another_result[$count]['SlotId'] = $result['SlotId'];
				$another_result[$count]['ScId'] = $result['ScId'];
				$another_result[$count]['Day'] = $result['Day'];
				$another_result[$count]['Hour'] = $result['Hour'];
				$another_result[$count]['EHour'] = $result['EHour'];
				if((intval($result['Slots']) - intval($result['BufferedSlots'])) <= 0) {
					$another_result[$count]['Slots'] = 0;
				} else {
					$another_result[$count]['Slots'] = intval($result['Slots']) - intval($result['BufferedSlots']);
				}
				$count++;
			}
			return $another_result;
		}
	}
	public function check_if_slots_still_exists($sc_id = FALSE, $date = FALSE, $slot = FALSE) {
		$this->erase_expired_slots();
		$this->db->select('slots.SlotId, slots.Slots, COUNT(SlotBufferId) AS BufferedSlots');
		$this->db->from('slots');
		$this->db->join('slotsbuffer', 'slotsbuffer.SlotId = slots.SlotId', 'left');
		if($sc_id) {
			$this->db->where('ScId', intval($sc_id));
		} else {
			$this->db->where('ScId', intval($this->input->cookie('sc_id')));
		}
		if($date) {
			$this->db->where('slots.Day', $date);
		} else {
			$this->db->where('slots.Day', $this->input->cookie('date'));
		}
		if($slot) {
			$this->db->where('slots.Hour', $slot);
		} else {
			$this->db->where('slots.Hour', intval($this->input->cookie('slot')));	
		}
		$this->db->group_by('slots.SlotId');
		$this->db->limit(1);
		$query = $this->db->get();
		$results = $query->row_array();
		return $results;
	}
	public function send_slot_to_buffer($sc_id, $date, $slot) {
		$this->db->select('SlotId');
		$this->db->from('slots');
		$this->db->where('ScId', $sc_id);
		$this->db->where('Day', $date);
		$this->db->where('Hour', $slot);
		$this->db->limit(1);
		$query = $this->db->get();
		$slot_id = $query->result_array()[0]['SlotId'];
		$data = array(
			'SlotId' => $slot_id,
			'Status' => 0
		);
		$this->db->insert('slotsbuffer', $data);
		$buffered_slot = $this->db->insert_id();
		return $buffered_slot;
	}
	public function get_slots($slot_interval, $sHour, $eHour) {
		$slot_interval = intval($slot_interval);
		$count = 0;
		for($i = $sHour; $i <= $eHour; $i += $slot_interval) {
			$data[$count]['Hour'] = $i;
			$count += 1;
		}
		return $data;
	}
	public function erase_expired_slots() {
		$buffer_time = time() - 15 * 60;
		$this->db->where('UNIX_TIMESTAMP(Timestamp) <', $buffer_time);
		$this->db->where('Status', 0);
		$this->db->delete('slotsbuffer');
	}
	public function isHoliday($sc_id) {
		$date = $this->input->cookie('date');
		$this->db->select('*');
		$this->db->from('service_center_holidays');
		$this->db->where('ScId', $sc_id);
		$this->db->where('Holiday', $date);
		$this->db->order_by('service_center_holidays.ScHId', 'asc');
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	public function get_sc_names() {
		$this->db->select('ScId, ScName');
		$this->db->from('servicecenter');
		$this->db->where('isVerified', 1);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return array();
		} else {
			return $results;
		}
	}
}