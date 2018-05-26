<?php
class Petrolbunks_m extends G6_Model {
	protected $_table_name = 'petrolbunks';
	protected $_primary_key = 'PBId';
	protected $_order_by = 'PBName';
	public function __construct() {
		parent::__construct();
	}
	public function get_sp_companies() {
		$this->db->distinct();
		$this->db->select('petrolbunks.ServiceProvider');
		$this->db->from('petrolbunks');
		$this->db->where('petrolbunks.isVerified', 1);
		$this->db->where('petrolbunks.CityId', intval($this->input->cookie('CityId')));
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			foreach($results as $result) {
				$final_result[] = $result['ServiceProvider'];
			}
			return $final_result;
		}
	}
	public function get_pbwithec_locations($city_id = NULL) {
		$this->db->select('petrolbunks.PBId as ScId, LocationId, Latitude, Longitude');
		$this->db->from('petrolbunks');
		$this->db->join('pbamprice', 'pbamprice.PBId = petrolbunks.PBId');
		$this->db->where('petrolbunks.isVerified', 1);
		if(isset($city_id)) {
			$this->db->where('petrolbunks.CityId', intval($city_id));
		} else {
			$this->db->where('petrolbunks.CityId', intval($this->input->cookie('CityId')));
		}
		$this->db->where('pbamprice.AmId', 6);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
	public function get_pb_locations($city_id = NULL) {
		$this->db->select('PBId as ScId, LocationId, Latitude, Longitude');
		$this->db->from('petrolbunks');
		$this->db->where('petrolbunks.isVerified', 1);
		if(isset($city_id)) {
			$this->db->where('CityId', intval($city_id));
		} else {
			$this->db->where('CityId', intval($this->input->cookie('CityId')));
		}
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result;
		}
	}
	public function get_service_provider($PBId) {
		$this->db->select('PBName, ServiceProvider');
		$this->db->from('petrolbunks');
		$this->db->where('PBId', intval($PBId));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0]['PBName'] . ' - ' . $result[0]['ServiceProvider'];
		}
	}
	public function amenities_by_id($PBId) {
		$this->db->select('AmName, AmDesc, AmIcon, AmIcon1, AmIcon2, iAmIcon1, iAmIcon2');
		$this->db->from('amenity');
		$this->db->join('pbamprice', 'pbamprice.AmId = amenity.AmId');
		$this->db->where('PBId', intval($PBId));
		$this->db->where('AmCode', 2);
		$query = $this->db->get();
		$results = $query->result_array();
		return $results;
	}
	public function get_pbec_timings($pb_id){
		$this->db->select('*');
		$this->db->from('pbectimings');
		$this->db->where('ScType', 'pb');
		$this->db->where('ScId', intval($pb_id));
		$this->db->limit(7);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			$final_result = '<li class="col s12 m3 collection-item left"><div class="left-align">Timings : </div></li>';
			foreach($results as $result) {
				$final_result .= '<li class="col s12 m3 collection-item left"><div class="left-align">' . convert_to_camel_case($result['Day']) . ' :<span class="secondary-content">' . $result['STime'] . ' - ' . $result['ETime'] . '</span></div></li>';
			}
			return $final_result;
		}
	}
	public function get_app_pbec_timings($pb_id) {
		$this->db->select('*');
		$this->db->from('pbectimings');
		$this->db->where('ScType', 'pb');
		$this->db->where('ScId', intval($pb_id));
		$this->db->limit(7);
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			return $results;
		}
	}
	public function get_amenities_for_tdrow($pb_id) {
		$this->db->select('AmName, AmDesc, AmIcon, Price');
		$this->db->from('amenity');
		$this->db->join('pbamprice', 'pbamprice.AmId = amenity.AmId');
		$this->db->where('PBId', intval($pb_id));
		$this->db->where('AmCode', 2);
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) > 0) {
			$results = array_chunk($results, ceil(count($results) / 2));
			$final_result[0] = '';
			$final_result[1] = '';
			$count = 0;
			foreach($results as $result) {
				foreach($result as $amty) {
					$amty_icon = $this->parseAmenityIcon($amty['AmName'], $amty['AmDesc'], $amty['AmIcon']);
					if(isset($amty['Price']) && $amty['Price'] != '' && $amty['Price'] !== NULL && floatval($amty['Price'] > 0.1)) {
						$price = ' @' . $amty['Price'] . ' INR';
					} else {
						$price = '';
					}
					$final_result[$count] .= '<li class="collection-item"><div class="left-align">' . $amty_icon . ' - ' . $amty['AmName'] . '<span class="secondary-content">Available' . $price . ' : ' . $amty['AmDesc'] . '</span></div></li>';
				}
				$count += 1;
			}
			return $final_result;
		} else {
			return NULL;
		}
	}
	public function get_app_amenities_for_pb($pb_id) {
		$this->db->select('AmName, AmDesc, AmIcon, AmIcon1, AmIcon2, iAmIcon1, iAmIcon2, Price');
		$this->db->from('amenity');
		$this->db->join('pbamprice', 'pbamprice.AmId = amenity.AmId');
		$this->db->where('PBId', intval($pb_id));
		$this->db->where('AmCode', 2);
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) > 0) {
			return $results;
		} else {
			return NULL;
		}
	}
	public function get_pbec_by_id($pb_id) {
		$this->db->select('petrolbunks.*, LocationName, CityName');
		$this->db->from('petrolbunks');
		$this->db->join('location', 'location.LocationId = petrolbunks.LocationId');
		$this->db->join('city', 'city.CityId = petrolbunks.CityId');
		$this->db->where('PBId', intval($pb_id));
		$this->db->limit(1);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) == 0) {
			return NULL;
		} else {
			return $result[0];
		}
	}
	public function get_ec_prices($pb_id) {
		$this->db->select('*');
		$this->db->from('ecprice');
		$this->db->where('ScType', 'pb');
		$this->db->where('ScId', intval($pb_id));
		$query = $this->db->get();
		$results = $query->result_array();
		if (count($results) == 0) {
			return NULL;
		} else {
			$final_result = '';
			foreach($results as $result) {
				if($result['VehicleType'] == '2w') {
					$final_result .= '<li class="col s12 m6 collection-item left"><div class="left-align">' . convert_to_camel_case($result['FuelType']) . ' Price (2 - Wheeler) : <span class="secondary-content">@' . $result['Price'] . ' INR</span></div></li>';
				}
			}
			return $final_result;
		}
	}
	private function parseAmenityIcon($amname, $amdesc, $amicon) {
		$temp = str_replace("{AmName}", $amname, $amicon);
		$temp = str_replace("{AmDesc}", $amdesc, $temp);
		$temp = $temp . '<span style=\"display:none\">' . $amname . '</span>';
		return stripslashes($temp);
	}
}