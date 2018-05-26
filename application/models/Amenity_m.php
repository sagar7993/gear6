<?php
class Amenity_m extends G6_Model {
	protected $_table_name = 'amenity';
	protected $_primary_key = 'AmId';
	protected $_order_by = 'AmName';
	public function __construct() {
		parent::__construct();
	}
	public function get_new() {
		$amenity = new stdClass();
		$amenity->AmName = '';
		$amenity->AmCode = '';
		return $amenity;
	}
	public function get_chosen_amenities($oid) {
		$this->db->select('AmName');
		$this->db->from($this->_table_name);
		$this->db->join('oamenitydetail', 'oamenitydetail.AmId = amenity.AmId');
		$this->db->where('oamenitydetail.OId', $oid);
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) == 0) {
			return NULL;
		} else {
			foreach($results as $result) {
				$final_result[] = $result['AmName'];
			}
			return implode(', ', $final_result);
		}
	}
	public function get_pick_drop_price($pick_type, $sc_id) {
		$this->load->model('location_m');
		$lc_row = $this->location_m->location_row_by_name($this->input->post('user_lc'));
		$this->db->select('pickprice.Price');
		$this->db->from('pickprice');
		$this->db->where('pickprice.ScId', intval($sc_id));
		$this->db->where('pickprice.LocationId', intval($lc_row['LocationId']));
		$this->db->limit(1);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			$this->load->model('servicecenter_m');
			$desti = $this->servicecenter_m->sc_details_for_slot(intval($sc_id));
			$dest['Lati'] = $desti['Latitude'];
			$dest['Long'] = $desti['Longitude'];
			$src['Lati'] = $lc_row['Latitude'];
			$src['Long'] = $lc_row['Longitude'];
			$dist = $this->distance($src['Lati'], $src['Long'], $dest['Lati'], $dest['Long'], "K");
			$this->db->select('pickprice.Price');
			$this->db->from('pickprice');
			$this->db->where('pickprice.ScId', intval($sc_id));
			$this->db->where('pickprice.SDistance <= ', round($dist));
			$this->db->where('pickprice.EDistance > ', round($dist));
			$this->db->where("(pickprice.Type = 'both' OR pickprice.Type = '" . $pick_type . "')", NULL, FALSE);
			$this->db->limit(1);
			$query = $this->db->get();
			$results = $query->result_array();
			if(count($results) == 0) {
				return 0;
			} else {
				return floatval($results[0]['Price']);
			}
		} else {
			return floatval($results[0]['Price']);
		}
	}
	public function vendor_insert_price_split($OId, $sername) {
		$amtys = $this->input->post('oamenities');
		if (empty($amtys) || count($amtys) == 0) {
			$amtys = array();
		}
		$asers = $this->input->post('asers');
		if (empty($asers) || count($asers) == 0) {
			$asers = array();
		}
		$prices_array = $this->get_order_est_price($amtys, $sername, $asers);
		$price_value = 0;
		if($prices_array !== NULL && count($prices_array) > 0) {
			$count = 0;
			foreach($prices_array as $price) {
				if(!isset($price['ptotal'])) {
					$pinsert[$count]['OId'] = $OId;
					$pinsert[$count]['PriceDetails'] = $price['apdesc'];
					$pinsert[$count]['Price'] = $price['aprice'];
					$pinsert[$count]['isDiscount'] = 0;
					$pinsert[$count]['TaxPrice'] = $price['atprice'];
					$pinsert[$count]['TaxType'] = $price['attype'];
					$pinsert[$count]['TaxDesc'] = $price['atdesc'];
					$price_value += floatval($price['aprice']) + floatval($price['atprice']);
					$count += 1;
				}
			}
			$this->db->insert_batch('opricesplit', $pinsert);
		}
		return $price_value;
	}
	public function insert_app_price_split($OId, $sername, &$query_row) {
		if ($this->input->post('amtys') != '') {
			$amtys = explode(',', $this->input->post('amtys'));
		} else {
			$amtys = array();
		}
		if ($this->input->post('asers') != '') {
			$asers = explode(',', $this->input->post('asers'));
		} else {
			$asers = array();
		}
		$prices_array = $this->get_order_est_price($amtys, $sername, $asers, $query_row);
		$price_value = 0;
		$count = 0;
		if($prices_array !== NULL && count($prices_array) > 0) {
			foreach($prices_array as $price) {
				if(!isset($price['ptotal'])) {
					$pinsert[$count]['OId'] = $OId;
					$pinsert[$count]['PriceDetails'] = $price['apdesc'];
					$pinsert[$count]['Price'] = $price['aprice'];
					$pinsert[$count]['isDiscount'] = 0;
					$pinsert[$count]['TaxPrice'] = $price['atprice'];
					$pinsert[$count]['TaxType'] = $price['attype'];
					$pinsert[$count]['TaxDesc'] = $price['atdesc'];
					$price_value += floatval($price['aprice']) + floatval($price['atprice']);
					$count += 1;
				}
			}
		}
		if($query_row->CouponId) {
			$this->load->model('coupons_m');
			$ccode = $this->coupons_m->get(intval($query_row->CouponId))->CCode;
			$pinsert[$count]['OId'] = $OId;
			$pinsert[$count]['PriceDetails'] = 'Offer Coupon (' . $ccode . ')';
			$pinsert[$count]['Price'] = floatval($query_row->CDValue);
			$pinsert[$count]['isDiscount'] = 1;
			$pinsert[$count]['TaxPrice'] = 0;
			$pinsert[$count]['TaxType'] = 0;
			$pinsert[$count]['TaxDesc'] = NULL;
			$price_value -= $pinsert[$count]['Price'];
			$count += 1;
		}
		if($query_row->FCouponId) {
			$pinsert[$count]['OId'] = $OId;
			$pinsert[$count]['PriceDetails'] = 'Referral Coupon / Gift Card';
			$pinsert[$count]['Price'] = floatval($query_row->FDValue);
			$pinsert[$count]['isDiscount'] = 1;
			$pinsert[$count]['TaxPrice'] = 0;
			$pinsert[$count]['TaxType'] = 0;
			$pinsert[$count]['TaxDesc'] = NULL;
			$price_value -= $pinsert[$count]['Price'];
			$count += 1;
		}
		if(intval($query_row->isAvail) == 1) {
			$pinsert[$count]['OId'] = $OId;
			$pinsert[$count]['PriceDetails'] = 'Free Servicing Discount';
			$pinsert[$count]['Price'] = floatval($query_row->FSDValue);
			$pinsert[$count]['isDiscount'] = 1;
			$pinsert[$count]['TaxPrice'] = 0;
			$pinsert[$count]['TaxType'] = 0;
			$pinsert[$count]['TaxDesc'] = NULL;
			$price_value -= $pinsert[$count]['Price'];
			$count += 1;
		}
		if($count > 0) {
			$this->db->insert_batch('opricesplit', $pinsert);
		}
		return $price_value;
	}
	public function insert_price_split($OId, $sername) {
		if ($this->input->post('amtys') != '') {
			$amtys = explode(',', $this->input->post('amtys'));
		} else {
			$amtys = array();
		}
		if ($this->input->post('asers') != '') {
			$asers = explode(',', $this->input->post('asers'));
		} else {
			$asers = array();
		}
		$prices_array = $this->get_order_est_price($amtys, $sername, $asers);
		$price_value = 0;
		$count = 0;
		if($prices_array !== NULL && count($prices_array) > 0) {
			foreach($prices_array as $price) {
				if(!isset($price['ptotal'])) {
					$pinsert[$count]['OId'] = $OId;
					$pinsert[$count]['PriceDetails'] = $price['apdesc'];
					$pinsert[$count]['Price'] = $price['aprice'];
					$pinsert[$count]['isDiscount'] = 0;
					$pinsert[$count]['TaxPrice'] = $price['atprice'];
					$pinsert[$count]['TaxType'] = $price['attype'];
					$pinsert[$count]['TaxDesc'] = $price['atdesc'];
					$price_value += floatval($price['aprice']) + floatval($price['atprice']);
					$count += 1;
				}
			}
		}
		if($this->session->userdata('cid') != '' || $this->session->userdata('cid') !== NULL) {
			$this->load->model('coupons_m');
			$ccode = $this->coupons_m->get(intval($this->session->userdata('cid')))->CCode;
			$pinsert[$count]['OId'] = $OId;
			$pinsert[$count]['PriceDetails'] = 'Offer Coupon (' . $ccode . ')';
			$pinsert[$count]['Price'] = floatval($this->session->userdata('cdvalue'));
			$pinsert[$count]['isDiscount'] = 1;
			$pinsert[$count]['TaxPrice'] = 0;
			$pinsert[$count]['TaxType'] = 0;
			$pinsert[$count]['TaxDesc'] = NULL;
			$price_value -= $pinsert[$count]['Price'];
			$count += 1;
		}
		if($this->session->userdata('fcid') != '' || $this->session->userdata('fcid') !== NULL) {
			$pinsert[$count]['OId'] = $OId;
			$pinsert[$count]['PriceDetails'] = 'Referral Coupon / Gift Card';
			$pinsert[$count]['Price'] = floatval($this->session->userdata('fdvalue'));
			$pinsert[$count]['isDiscount'] = 1;
			$pinsert[$count]['TaxPrice'] = 0;
			$pinsert[$count]['TaxType'] = 0;
			$pinsert[$count]['TaxDesc'] = NULL;
			$price_value -= $pinsert[$count]['Price'];
			$count += 1;
		}
		if($this->session->userdata('fsvalue') != '' || $this->session->userdata('fsvalue') !== NULL) {
			$pinsert[$count]['OId'] = $OId;
			$pinsert[$count]['PriceDetails'] = 'Free Servicing Discount';
			$pinsert[$count]['Price'] = floatval($this->session->userdata('fsvalue'));
			$pinsert[$count]['isDiscount'] = 1;
			$pinsert[$count]['TaxPrice'] = 0;
			$pinsert[$count]['TaxType'] = 0;
			$pinsert[$count]['TaxDesc'] = NULL;
			$price_value -= $pinsert[$count]['Price'];
			$count += 1;
		}
		if($count > 0) {
			$this->db->insert_batch('opricesplit', $pinsert);
		}
		return $price_value;
	}
	public function get_est_prices_by_oid($oid, $dflag = FALSE, $convflag = FALSE) {
		$this->db->select('opricesplit.Price AS aprice, opricesplit.PriceDetails AS apdesc, opricesplit.TaxPrice AS atprice, opricesplit.TaxDesc AS atdesc, opricesplit.PriceSplitId AS apid, opricesplit.TaxType AS attype');
		$this->db->from('opricesplit');
		$this->db->where('opricesplit.OId', $oid);
		if($dflag) {
			$this->db->where('opricesplit.isDiscount', 1);
		} else {
			$this->db->where('opricesplit.isDiscount', 0);
		}
		if($convflag) {
			$this->db->where('opricesplit.isConvFee', 1);
			$this->db->limit(1);
		} else {
			$this->db->order_by('opricesplit.PriceSplitId', 'asc');
		}
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			$total_amount = 0;
			foreach($results as $result) {
				$total_amount += floatval($result['aprice']);
				$total_amount += floatval($result['atprice']);
			}
			$results[count($results)]['ptotal'] = $total_amount;
			return $results;
		}
	}
	public function get_order_est_price($amtys, $servicename, $asers = NULL, $mobile = FALSE) {
		if($this->input->post('user_service') != "") {
			$ser_id = intval($this->input->post('user_service'));
			$sc_id = intval($this->session->userdata('v_sc_id'));
			$bmid = intval($this->input->post('user_bikemodel'));
		} elseif($this->input->cookie('servicetype') != "") {
			$ser_id = intval($this->input->cookie('servicetype'));
			$sc_id = intval($this->input->cookie('sc_id'));
			$bmid = intval($this->input->cookie('model'));
		}
		if($mobile) {
			$ser_id = intval($mobile->ServiceId);
			$sc_id = intval($mobile->ScId);
			$bmid = intval($mobile->BikeModelId);
		}
		if($ser_id != 3) {
			$count = 0;
			$ptotal = 0;
			if($ser_id != 4) {
				$this->load->model('servicecenter_m');
				$temp = floatval($this->servicecenter_m->price_by_id($sc_id, $bmid, $ser_id));
				if($temp > 0.01) {
					$final_result[$count]['apdesc'] = convert_to_camel_case($servicename) . ' - Service Base Charge';
					$final_result[$count]['aprice'] = $temp; $ptotal += $final_result[$count]['aprice'];
					$final_result[$count]['atdesc'] = 'Service Tax (15 %)';
					$final_result[$count]['atprice'] = round(($temp * 0.15), 2);
					$final_result[$count]['attype'] = 1; $ptotal += $final_result[$count]['atprice'];
					$count++;
				}
			}
			if($ser_id == 1) {
				if($asers != '' && count($asers) > 0) {
					array_map('intval', $asers);
				} else {
					$asers = array();
				}
				$this->db->select('aservice.AServiceId, aservice.AServiceName, asprice.Price, asprice.IsMand, asprice.TaxType');
				$this->db->from('asprice');
				$this->db->join('aservice', 'aservice.AServiceId = asprice.AServiceId');
				$this->db->where('asprice.ScId', intval($sc_id));
				$this->db->where('asprice.BikeModelId', intval($bmid));
				$this->db->where('aservice.isEnabled', 1);
				$this->db->where('aservice.AScType', 'sc');
				$this->db->group_by('aservice.AServiceId');
				$query = $this->db->get();
				$results = $query->result_array();
				if (count($results) > 0) {
					foreach($results as $result) {
						if((intval($result['IsMand']) == 1) || in_array(intval($result['AServiceId']), $asers)) {
							$final_result[$count]['apdesc'] = convert_to_camel_case($result['AServiceName']);
							$final_result[$count]['aprice'] = floatval($result['Price']);
							$ptotal += $final_result[$count]['aprice'];
							if(floatval($result['Price']) > 0.01 && $result['TaxType'] == 1) {
								$final_result[$count]['atdesc'] = 'Service Tax (15 %)';
								$final_result[$count]['atprice'] = round(floatval($result['Price']) * 0.15, 2);
								$final_result[$count]['attype'] = $result['TaxType'];
								$ptotal += $final_result[$count]['atprice'];
								$count++;
							} elseif(floatval($result['Price']) > 0.01 && $result['TaxType'] == 2) {
								$final_result[$count]['atdesc'] = 'VAT (14.5 %)';
								$final_result[$count]['atprice'] = round(floatval($result['Price']) * 0.145, 2);
								$final_result[$count]['attype'] = $result['TaxType'];
								$ptotal += $final_result[$count]['atprice'];
								$count++;
							}
						}
					}
				}
			}
			if($amtys != '' && count($amtys) > 0) {
				array_map('intval', $amtys);
				$this->db->select('aprice.Price, amenity.AmId, amenity.AmName, scam.AmDesc, scam.AmTerms');
				$this->db->from('amenity');
				$this->db->join('scam', 'scam.AmId = amenity.AmId');
				$this->db->join('aprice', 'aprice.AmId = scam.AmId AND aprice.ScId = scam.ScId', 'left');
				$this->db->where('scam.ScId', intval($sc_id));
				$this->db->where_in('amenity.AmId', $amtys);
				$this->db->group_by('amenity.AmId');
				$query = $this->db->get();
				$results = $query->result_array();
				if (count($results) > 0) {
					foreach($results as $result) {
						$final_result[$count]['apdesc'] = $result['AmName'];
						if($result['Price'] == "" || $result['Price'] === NULL) {
							$final_result[$count]['aprice'] = 0;
						} else {
							$final_result[$count]['aprice'] = floatval($result['Price']);
							$ptotal += $final_result[$count]['aprice'];
						}
						$final_result[$count]['atdesc'] = NULL;
						$final_result[$count]['atprice'] = 0;
						$final_result[$count]['attype'] = 0;
						$count += 1;
					}
				}
			}
			if($count == 0) {
				return NULL;
			} else {
				$final_result[$count]['ptotal'] = $ptotal;
				return $final_result;
			}
		} else {
			return NULL;
		}
	}
	public function get_amenities_by_service($sc_id = NULL, $servicetype = NULL) {
		if(intval($this->input->cookie('servicetype')) == 3 || intval($servicetype) == 3) {
			return NULL;
		}
		$this->db->select('amenity.AmName, amenity.AmId, scam.AmDesc');
		$this->db->from('amenity');
		$this->db->join('scam', 'scam.AmId = amenity.AmId');
		$this->db->join('amservice', 'amservice.AmId = amenity.AmId');
		if(isset($sc_id)) {
			$this->db->where('scam.ScId', intval($sc_id));
		} else {
			$this->db->where('scam.ScId', intval($this->input->cookie('sc_id')));
		}
		$this->db->where('amenity.PriceApply', 1);
		if(isset($servicetype)) {
			$this->db->where('amservice.ServiceId', intval($servicetype));
		} else {
			$this->db->where('amservice.ServiceId', intval($this->input->cookie('servicetype')));
		}
		$this->db->where('amenity.AmCode', 1);
		$this->db->where('amenity.AmId != ', 1);
		$this->db->where('amenity.AmId != ', 2);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_old_apick_prices($wclause) {
		$this->db->select('LocationId, PickPriceId');
		$this->db->from('pickprice');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('Type', $wclause);
		$this->db->where('FixedFlag', 1);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach($results as $result) {
				$new_results[$result['PickPriceId']] = intval($result['LocationId']);
			}
			return $new_results;
		}
	}
	public function get_fix_area_prices() {
		$this->db->select('LocationName, PickPriceId, pickprice.Type, pickprice.Price, DATE_FORMAT(CONVERT_TZ(Timestamp,"+00:00","+05:30"), "%D %M %Y %h:%i %p") AS Timestamp');
		$this->db->from('pickprice');
		$this->db->join('location', 'location.LocationId = pickprice.LocationId');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('FixedFlag', 1);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach($results as &$result) {
				if($result['Type'] == 'pick') {
					$result['Type'] = 'PickUp';
				}
				if($result['Type'] == 'drop') {
					$result['Type'] = 'Drop';
				}
			}
			return $results;
		}
	}
	public function get_radii_prices() {
		$this->db->select('SDistance, EDistance, pickprice.Type, pickprice.Price');
		$this->db->from('pickprice');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('FixedFlag', 0);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_amenities_by_sc() {
		$this->db->select('scam.AmId, scam.AmDesc, ScAmId');
		$this->db->from('scam');
		$this->db->join('amenity', 'amenity.AmId = scam.AmId');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('AmCode', 1);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach($results as $result) {
				$new_results[intval($result['AmId'])]['id'] = intval($result['AmId']);
				$new_results[intval($result['AmId'])]['desc'] = $result['AmDesc'];
			}
			return $new_results;
		}
	}
	public function get_oldamenities_by_sc() {
		$this->db->select('scam.AmId, scam.AmDesc, ScAmId');
		$this->db->from('scam');
		$this->db->join('amenity', 'amenity.AmId = scam.AmId');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('AmCode', 1);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach($results as $result) {
				$new_results[0][] = intval($result['AmId']);
				$new_results[1][] = $result['AmDesc'];
				$new_results[2][] = intval($result['ScAmId']);
			}
			return $new_results;
		}
	}
	public function get_priced_amenities() {
		$this->db->select('scam.AmId, AmName');
		$this->db->from('scam');
		$this->db->join('amenity', 'amenity.AmId = scam.AmId');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$this->db->where('AmCode', 1);
		$this->db->where('PriceApply', 1);
		$this->db->where('amenity.AmId !=', 1);
		$this->db->where('amenity.AmId !=', 2);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_amprices() {
		$this->db->select('AmName, DATE_FORMAT(CONVERT_TZ(Timestamp,"+00:00","+05:30"), "%D %M %Y %h:%i %p") AS Timestamp, Price, APriceId');
		$this->db->from('aprice');
		$this->db->join('amenity', 'amenity.AmId = aprice.AmId');
		$this->db->where('ScId', intval($this->session->userdata('v_sc_id')));
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	private function get_amtys_for_order($oid) {
		$this->db->select('oamenitydetail.AmId');
		$this->db->from('oamenitydetail');
		$this->db->where('OId', $oid);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return array();
		} else {
			foreach($results as $result) {
				$amtys[] = $result['AmId'];
			}
			return $amtys;
		}
	}
	private function distance ($lat1, $lon1, $lat2, $lon2, $unit) {
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);
		if ($unit == "K") {
			return ($miles * 1.609344);
		} elseif ($unit == "N") {
			return ($miles * 0.8684);
		} else {
			return $miles;
		}
	}
}