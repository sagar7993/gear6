<?php
class Book extends G6_Usercontroller {
	public function __construct() {
		parent::__construct();
		$this->load->model('bikecompany_m');
		$this->load->model('bikemodel_m');
		$this->load->model('service_m');
		$this->load->model('location_m');
		if (isset($_POST['book'])) {
			$this->set_query_data();
			if ($this->input->post('servicetype') == '4') {
				redirect('/user/review/insReview/');
			} else {
				redirect('/user/book/');
			}
		} elseif ($this->is_query_set()) {
			$this->get_query_data();
		} else {
			redirect('/');
		}
	}
	public function index() {
		$this->check_visitor_count();
		$this->data['cities'] = $this->city_m->get_by(array('isEnabled' => 1));
		$this->data['bikecompanies'] = $this->bikecompany_m->get_by('isEnabled = 1');
		$this->data['services'] = $this->service_m->get_by('isEnabled = 1');
		if ($this->city_m->iscityset()) {
			$areas = $this->location_m->locations_for_sc();
			$this->load->model('amenity_m');
			if($this->data['serid'] == 9) {
				$this->load->model('petrolbunks_m');
				$this->data['amenities'] = $this->amenity_m->get_by(array('AmCode' => 2));
				$this->data['spcompanies'] = $this->petrolbunks_m->get_sp_companies();
			} elseif($this->data['serid'] != 10) {
				$this->data['amenities'] = $this->amenity_m->get_by(array('AmCode' => 1));
			}
			$this->data['data_filter_counter'] = count($areas) + 1;
			$this->data['city_row'] = $city_row = $this->city_m->get($this->data['city_id']);
			$this->data['adv_time'] = intval($city_row->AdvTime) / 24;
			$curr_hour = intval(date("H", time()));
			if($curr_hour >= 18) {
				$this->data['adv_time'] += 1;
			}
		}
		if($this->data['is_logged_in'] == 0 && $this->input->cookie('referer') !== NULL && $this->input->cookie('referer') == "promo.gear6.in") {
			$this->data['open_blklogin_modal'] = 1;
		} else {
			delete_cookie('referer');
		}
		$this->load->view('user/book', $this->data);
	}
	public function get_show_room_data() {
		if($_POST) {
			$scs = array(array());
			if($this->data['serid'] == 9) {
				$this->load->model('petrolbunks_m');
				$sc_locations = $this->petrolbunks_m->get_pb_locations();
			} elseif ($this->data['serid'] == 10) {
				$this->load->model('petrolbunks_m');
				$this->load->model('pucs_m');
				$sc_locations = $this->pucs_m->get_ec_locations();
				$pb_with_ec_amenities = $this->petrolbunks_m->get_pbwithec_locations();
				if(isset($pb_with_ec_amenities)) {
					foreach($pb_with_ec_amenities as &$pb) {
						$pb['pb_with_ec_flag'] = 1;
					}
					$sc_locations = array_merge($sc_locations, $pb_with_ec_amenities);
				}
			} elseif($this->data['serid'] == 11) {
				$this->load->model('scaddr_m');
				$this->load->model('punctures_m');
				$sc_locations = $this->punctures_m->get_pt_locations();
				$pb_locations = $this->scaddr_m->location_rows(TRUE);
				if(isset($pb_locations)) {
					foreach($pb_locations as &$sc) {
						$sc['pt_with_sc_flag'] = 1;
					}
					$sc_locations = array_merge($sc_locations, $pb_locations);
				}
			} else {
				$this->load->model('scaddr_m');
				$sc_locations = $this->scaddr_m->location_rows();
			}
			$src['Latitude'] = $this->input->cookie('qlati');
			$src['Longitude'] = $this->input->cookie('qlongi');
			$scs_count = 0;
			if (count($sc_locations) > 0) {
				foreach ($sc_locations as $dest) {
					if($this->distance($src['Latitude'], $src['Longitude'], $dest['Latitude'], $dest['Longitude'], "M") < intval($this->input->post('dist'))) {
						$scs[$scs_count]['lcid'] = $dest['LocationId'];
						$scs[$scs_count]['scid'] = $dest['ScId'];
						$scs[$scs_count]['dist'] = $this->distance($src['Latitude'], $src['Longitude'], $dest['Latitude'], $dest['Longitude'], "K");
						if(isset($dest['pb_with_ec_flag']) && $dest['pb_with_ec_flag'] == 1) {
							$scs[$scs_count]['pbecflag'] = $dest['pb_with_ec_flag'];
						}
						if(isset($dest['pt_with_sc_flag']) && $dest['pt_with_sc_flag'] == 1) {
							$scs[$scs_count]['ptscflag'] = $dest['pt_with_sc_flag'];
						}
						$scs_count += 1;
					}
				}
			}
			if ($scs_count == 0) {
				echo '';
			} else {
				echo $this->fetch_show_room_data($scs);
			}
		}
	}
	public function getSlots() {
		if($_POST) {
			$this->load->model('servicecenter_m');
			$sc_id = intval($this->input->post('sc_id'));
			$date = $this->input->cookie('date');
			$sc_array = $this->servicecenter_m->sc_details_for_slot($sc_id);
			$sc_array['ScId'] = $sc_id;
			if ($this->input->cookie('servicetype') == 4 || $this->input->cookie('servicetype') == 2) {
				$slots = $this->servicecenter_m->get_slots($sc_array['SlotDuration'], $sc_array['StartHour'], $sc_array['EndHour']);
			} else {
				$slots = $this->servicecenter_m->set_get_slots($sc_id, $date, $sc_array['DefaultSlots'], $sc_array['SlotDuration'], intval($sc_array['SlotType']), $sc_array['StartHour'], $sc_array['EndHour']);
			}
			$data['html'] = $this->fetch_slots($sc_array, $slots, intval($sc_array['SlotType']));
			$data['lat'] = $sc_array['Latitude'];
			$data['lon'] = $sc_array['Longitude'];
			$data['sc_media'] = $this->servicecenter_m->get_scmedia_for_users($sc_id);
			$data['offers'] = $this->servicecenter_m->get_offers_for_users($sc_id);
			$data['exclusives'] = $this->servicecenter_m->get_excls_for_users($sc_id);
			echo json_encode($data);
		}
	}
	public function getPbDetails() {
		if($_POST) {
			$this->load->model('petrolbunks_m');
			$pb_id = intval($this->input->post('pb_id'));
			$pb_row = $this->petrolbunks_m->get_pbec_by_id($pb_id);
			$data['lat'] = $pb_row['Latitude'];
			$data['lon'] = $pb_row['Longitude'];
			$data['address'] = $this->getPbEcAddress($pb_row);
			$data['cdetails'] = $this->getPbEcCDetails($pb_row);
			$data['pdlprices'] = '';
			if(isset($pb_row['PetrolPrice']) && $pb_row['PetrolPrice'] != "" && $pb_row['PetrolPrice'] !== NULL) {
				$data['pdlprices'] .= '<li class="col s12 m6 collection-item left"><div class="left-align">Petrol Price :<span class="secondary-content">@' . $pb_row['PetrolPrice'] . '/ltr</span></div></li>';
			}
			if(isset($pb_row['DieselPrice']) && $pb_row['DieselPrice'] != "" && $pb_row['DieselPrice'] !== NULL) {
				$data['pdlprices'] .= '<li class="col s12 m6 collection-item left"><div class="left-align">Diesel Price :<span class="secondary-content">@' . $pb_row['DieselPrice'] . '/ltr</span></div></li>';
			}
			if(isset($pb_row['LPGPrice']) && $pb_row['LPGPrice'] != "" && $pb_row['LPGPrice'] !== NULL) {
				$data['pdlprices'] .= '<li class="col s12 m6 collection-item left"><div class="left-align">LPG Price :<span class="secondary-content">@' . $pb_row['LPGPrice'] . '/ltr</span></div></li>';
			}
			$amenities = $this->petrolbunks_m->get_amenities_for_tdrow($pb_id);
			if(isset($amenities)) {
				$data['amenities1'] = $amenities[0];
				$data['amenities2'] = $amenities[1];
			} else {
				$data['amenities1'] = NULL;
				$data['amenities2'] = NULL;
			}
			$data['timings'] = $this->petrolbunks_m->get_pbec_timings($pb_id);
			echo json_encode($data);
		}
	}
	public function getEcDetails() {
		if($_POST) {
			$pbec_id = intval($this->input->post('ec_id'));
			$serid = intval($this->input->post('serid'));
			if($serid == 9) {
				$mod_type = 'petrolbunks_m';
			} else {
				$mod_type = 'pucs_m';
			}
			$this->load->model($mod_type);
			$pbec_row = $this->$mod_type->get_pbec_by_id($pbec_id);
			$data['lat'] = $pbec_row['Latitude'];
			$data['lon'] = $pbec_row['Longitude'];
			$data['address'] = $this->getPbEcAddress($pbec_row);
			$data['cdetails'] = $this->getPbEcCDetails($pbec_row);
			$data['pdlprices'] = $this->$mod_type->get_ec_prices($pbec_id);
			$data['timings'] = $this->$mod_type->get_pbec_timings($pbec_id);
			echo json_encode($data);
		}
	}
	public function getPtDetails() {
		if($_POST) {
			$ptsc_id = intval($this->input->post('sc_id'));
			$serid = intval($this->input->post('serid'));
			if($serid == 11) {
				$mod_type = 'punctures_m';
			} else {
				$mod_type = 'servicecenter_m';
			}
			$this->load->model($mod_type);
			$ptsc_row = $this->$mod_type->get_ptsc_by_id($ptsc_id);
			$data['lat'] = $ptsc_row['Latitude'];
			$data['lon'] = $ptsc_row['Longitude'];
			$data['address'] = $this->getPbEcAddress($ptsc_row);
			$data['cdetails'] = $this->getPbEcCDetails($ptsc_row);
			if($serid == 11) {
				$data['pdlprices'] = 'Minimum Exptected Price: INR ' . $ptsc_row['Price'];
				$data['timings'] = $this->$mod_type->get_ptsc_timings($ptsc_id);
			} else {
				$data['pdlprices'] = 0;
				$data['timings'] = 0;
			}
			echo json_encode($data);
		}
	}
	public function getEmgDetails() {
		if($_POST) {
			$this->load->model('servicecenter_m');
			$sc_id = intval($this->input->post('sc_id'));
			$sc_contact = $this->servicecenter_m->get_sc_ad_contact($sc_id);
			$sc_array = $this->servicecenter_m->sc_details_for_slot($sc_id);
			$data['html'] = '<section class="content">
				<div class="em-detail row">
					<div class="col s12 em-detail-badge">
						Location ,Address and Contact details of ' . convert_to_camel_case($sc_array['ScName']) . '
					</div>
					<div class="col s12">
						<div class="col s12 m4">
							<div id="googleMap_' . $sc_id . '" class="map"></div>
						</div>
						<div class="col s12 m8">
							<div class="col s12 m5 em-detail-box">
								<div class="addr-header">
									Address Details
								</div>
								<div class="addr-detail">' . $sc_contact[0] . '</div>
							</div>
							<div class="col s12 m5 em-detail-box">
								<div class="contact-header">
									Contact Details
								</div>
								<div class="contact-detail">
									<div><span class="green-text"><strong>Mobile :</strong></span> ' . $sc_contact[1] . '</div>
									<div><span class="green-text"><strong>Landline :</strong></span> ' . $sc_contact[3] . '</div>
									<div><span class="green-text"><strong>Email Id :</strong></span> ' . $sc_contact[2] . '</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>';
			$data['lati'] = $sc_array['Latitude'];
			$data['longi'] = $sc_array['Longitude'];
			echo json_encode($data);
		}
	}
	public function book_order() {
		if($_POST) {
			if ($this->input->cookie('servicetype') == 1 || $this->input->cookie('servicetype') == 2 || $this->input->cookie('servicetype') == 4) {
				$slot_dump = $this->input->post('slot');
				$parsed_dump = explode('_', $slot_dump);
				$this->set_query_cookie('slot', $parsed_dump['2']);
				$this->set_query_cookie('sc_id', $parsed_dump['1']);
				if ($this->input->cookie('servicetype') == 1) {
					$this->load->model('servicecenter_m');
					$buffered_slot = $this->servicecenter_m->send_slot_to_buffer($parsed_dump['1'], $this->input->cookie('date'), $parsed_dump['2']);
					$this->set_query_cookie('buffered_slot', $buffered_slot);
				}
				if ($this->data['is_logged_in'] == 0) {
					$this->set_query_cookie('phone', $this->input->post('phone'));
				}
				if ($this->input->cookie('servicetype') == 4) {
					redirect('/user/review/insReview/');
				} else {
					redirect('/user/review/');
				}
			} elseif ($this->input->cookie('servicetype') == 3) {
				$date = $this->input->cookie('date');
				$this->set_query_cookie('sc_ids', $this->input->post('sc_ids'));
				$this->set_query_cookie('qtype', $this->input->post('qtype'));
				if ($this->data['is_logged_in'] == 0) {
					$this->set_query_cookie('phone', $this->input->post('phone'));
				}
				redirect('/user/review/');
			}
		}
	}
	private function check_visitor_count() {
		if($this->input->cookie('g6data1') == "" || $this->input->cookie('g6data1') === NULL) {
			$cookie = array(
				'name'   => 'g6data1',
				'value'  => 'iamhere1',
				'expire' => '600',
				'secure' => FALSE
			);
			$this->input->set_cookie($cookie);
			$this->load->model('g6data_m');
			$count = intval($this->g6data_m->get(1)->BookVisitCount);
			$ncount['BookVisitCount'] = $count + 1;
			$this->db->where('G6DataId', 1);
			$this->db->update('g6data', $ncount);
		}
	}
	private function getPbEcAddress($pbec_row) {
		$address = '<span class="card-title">Address Details</span>';
		$address .= '<div class="left-align">' . $pbec_row['AddrLine1'] . '</div>';
		$address .= '<div class="left-align">' . $pbec_row['AddrLine2'] . '</div>';
		$address .= '<div class="left-align">' . $pbec_row['LocationName'] . '</div>';
		$address .= '<div class="left-align">' . $pbec_row['CityName'] . '</div>';
		return $address;
	}
	private function getPbEcCDetails($pbec_row) {
		$cdetails = '<span class="card-title">Contact Details</span>';
		$cdetails .= '<div class="left-align margin-bottom-20px"><i class="material-icons">phonelink_ring</i><span class="center-align icon-align-text">' . $pbec_row['Phone'] . '</span></div>';
		if(isset($pbec_row['AltPhone']) && $pbec_row['AltPhone'] != "" && $pbec_row['AltPhone'] !== NULL) {
			$cdetails .= '<div class="left-align margin-bottom-20px"><i class="material-icons">phonelink_ring</i><span class="center-align icon-align-text">' . $pbec_row['AltPhone'] . '</span></div>';
		}
		if(isset($pbec_row['Landline']) && $pbec_row['Landline'] != "" && $pbec_row['Landline'] !== NULL) {
			$cdetails .= '<div class="left-align margin-bottom-20px"><i class="material-icons">settings_phone</i><span class="center-align icon-align-text">' . $pbec_row['Landline'] . '</span></div>';
		}
		return $cdetails;
	}
	private function fetch_slots($sc_array, $slots, $slot_type) {
		$html = '<div class="col s12 margin-bottom-20px">You have selected <strong>'. convert_to_camel_case($sc_array['ScName']) .'</strong><i><strong>&nbsp;-&nbsp;</strong></i>Choose Your Slot...</div>';
		foreach($slots as $slot) {
			if ($slot['Hour'] > 12) {
				$temp_hr = intval($slot['Hour'] - 12);
				$temp = (intval($slot['Hour'] * 60) % 60);
				if($temp == 0) {
					$temp = '00';
				}
				$slot_hour = $temp_hr . ":" . $temp . "&nbsp;PM";
			} elseif ($slot['Hour'] == 12) {
				$slot_hour = intval($slot['Hour']) . ":00&nbsp;PM";
			} else {
				$temp = (intval($slot['Hour'] * 60) % 60);
				if($temp == 0) {
					$temp = '00';
				}
				$slot_hour = intval($slot['Hour']) . ":" . $temp . "&nbsp;AM";
			}
			if ($this->input->cookie('servicetype') == 4 || $this->input->cookie('servicetype') == 2) {
				$html .= '<li class="col s6 sx l4 margin-bottom-10px margin-top-10px padding-right-0"><div class="slot-box"><label class="slot-label"><input type="radio" name="slot" class = "slot_' . $sc_array['ScId'] . ' slot_radio_event" value="sc_' . $sc_array['ScId'] . '_' . $slot['Hour'] . '">&nbsp;&nbsp;' . $slot_hour . '&nbsp;</label></div></li>';
			} else {
				if ($slot['Slots'] <= 0) {
					$html .= '<li class="col s6 sx l4 margin-bottom-10px margin-top-10px padding-right-0"><div class="slot-box"><label class="slot-label"><input type="radio" name="slot" class = "slot_' . $sc_array['ScId'] . ' slot_radio_event" value="sc_' . $sc_array['ScId'] . '_' . $slot['Hour'] . '" disabled="disabled"><span class="margin-left-5px">' . $slot_hour . ' - <b>Not Available</b></label></div></li>';
				} else {
					$html .= '<li class="col s6 sx l4 margin-bottom-10px margin-top-10px padding-right-0"><div class="slot-box"><label class="slot-label"><input type="radio" name="slot" class = "slot_' . $sc_array['ScId'] . ' slot_radio_event" value="sc_' . $sc_array['ScId'] . '_' . $slot['Hour'] . '"><span class="margin-left-5px">' . $slot_hour . ' : <b>' . $slot['Slots'] . '</b> Slots</span></label></div></li>';
				}
			}
		}
		if($slot_type == 1) {
			$html .= '<li class="col s12 margin-bottom-10px margin-top-10px padding-right-0"><span style="font-size: 12px;"><span style="color:red;">Note:</span>Bikes picked in slots before 11 AM will be delivered in 5 hours and the rest in the next day.</span></li>';
		} elseif($slot_type == 2) {
			$html .= '<li class="col s12 margin-bottom-10px margin-top-10px padding-right-0"><span style="font-size: 12px;"><span style="color:red;">Note:</span>Bikes picked in slots before 11 AM will be delivered by the End of the day and the rest in the next day.</span></li>';
		} elseif($slot_type == 3) {
			$html .= '<li class="col s12 margin-bottom-10px margin-top-10px padding-right-0"><span style="font-size: 12px;"><span style="color:red;">Note:</span>Bikes picked in these slots will be delivered by the End of next day.</span></li>';
		}
		$html .= '';
		return $html;
	}
	private function fetch_show_room_data($scs) {
		$row_count = 0;
		if($this->data['serid'] == 9) {
			$static_sc_key = 'petrolbunks_m';
		} elseif($this->data['serid'] == 10) {
			$static_sc_key = 'pucs_m';
		} elseif($this->data['serid'] == 11) {
			$static_sc_key = 'punctures_m';
		} else {
			$date = $this->input->cookie('date');
			$static_sc_key = 'servicecenter_m';
		}
		$this->load->model($static_sc_key);
		foreach ($scs as $sc) {
			$sc_h_id = $sc['scid'];
			if($this->data['serid'] != 3 && $static_sc_key == 'servicecenter_m' && $this->servicecenter_m->isHoliday($sc_h_id)) {
				continue;
			}
			if(isset($sc['pbecflag']) && $sc['pbecflag'] == 1 && $this->data['serid'] == 10) {
				$sc_key = 'petrolbunks_m';
				$table_rows[$row_count]['pbecflag'] = 1;
				if(!isset($this->$sc_key)) {
					$this->load->model($sc_key);
				}
			} else {
				$sc_key = $static_sc_key;
			}
			if(isset($sc['ptscflag']) && $sc['ptscflag'] == 1 && $this->data['serid'] == 11) {
				$sc_key = 'servicecenter_m';
				$table_rows[$row_count]['ptscflag'] = 1;
				if(!isset($this->$sc_key)) {
					$this->load->model($sc_key);
				}
			} else {
				$sc_key = $static_sc_key;
			}
			$table_rows[$row_count]['ScId'] = $sc['scid'];
			$table_rows[$row_count]['LocationName'] = $this->location_m->location_by_id($sc['lcid']);
			if($this->data['serid'] != 9 && $this->data['serid'] != 10 && $this->data['serid'] != 11) {
				$name_rating = $this->$sc_key->get_name_rating($sc['scid']);
				$table_rows[$row_count]['ScName'] = $name_rating['ScName'];
				$table_rows[$row_count]['Rating'] = $name_rating['Rating'];
				$table_rows[$row_count]['RatersCount'] = $name_rating['RatersCount'];
			} elseif($this->data['serid'] == 11) {
				$name_contact = $this->$sc_key->get_service_provider($sc['scid']);
				$table_rows[$row_count]['ScName'] = convert_to_camel_case($name_contact['ScName']);
				$table_rows[$row_count]['Phone'] = $name_contact['Phone'];
			} else {
				$table_rows[$row_count]['ScName'] = convert_to_camel_case($this->$sc_key->get_service_provider($sc['scid']));
			}
			$table_rows[$row_count]['Distance'] = round((floatval($sc['dist'])), 2);
			if($this->data['serid'] != 10 && $this->data['serid'] != 11) {
				$amenities_list = $this->$sc_key->amenities_by_id($sc['scid']);
				$table_rows[$row_count]['Amenities'] = $amenities_list;
			} elseif($this->data['serid'] == 10) {
				if(isset($sc['pbecflag']) && $sc['pbecflag'] == 1 && $this->data['serid'] == 10) {
					$table_rows[$row_count]['Amenities'] = 'pbunk';
				} else {
					$table_rows[$row_count]['Amenities'] = $this->$sc_key->get_puc_type($sc['scid']);
				}
			}
			if ($this->input->cookie('servicetype') == 1 || $this->input->cookie('servicetype') == 4) {
				$temp_price = $this->$sc_key->price_by_id($sc['scid']);
				if(!$temp_price) {
					$temp_price = 0;
				}
				$table_rows[$row_count]['Price'] = ' &#8377;&nbsp;' . $temp_price;
				$table_rows[$row_count]['SlotCount'] = $this->$sc_key->get_slot_count($sc['scid'], $date);
			}
			if($this->input->cookie('servicetype') == 7) {
				$sc_contact = $this->$sc_key->get_sc_phone($sc['scid']);
				$table_rows[$row_count]['ScPhone'] = $sc_contact['CPhone'];
				$table_rows[$row_count]['ScLand'] = $sc_contact['Landline'];
			}
			$row_count += 1;
		}
		$am_count = 0;
		$table_content = '';
		if(isset($table_rows)) {
			foreach ($table_rows as $table_row) {
				if(isset($table_row['pbecflag']) && $table_row['pbecflag'] == 1 && $this->data['serid'] == 10) {
					$serid = 9;
				} else {
					$serid = $this->data['serid'];
				}
				if(isset($table_row['ptscflag']) && $table_row['ptscflag'] == 1 && $this->data['serid'] == 11) {
					$serid = 1;
				} else {
					$serid = $this->data['serid'];
				}
				$am_icon_list = '';
				$am_icon_list .= '<div>';
				if($this->data['serid'] != 10 && $this->data['serid'] != 11) {
					foreach ($table_row['Amenities'] as $am_sc) {
						$am_icon_list .= $this->parseAmenityIcon($am_sc['AmName'], $am_sc['AmDesc'], $am_sc['AmIcon']);
					}
				} elseif($this->data['serid'] == 10) {
					if($table_row['Amenities'] == 'office') {
						$am_icon_list .= '<i class="material-icons am-icons tooltipped cGrey" data-position="bottom" data-delay="50" data-tooltip="Office or Static PUC">store</i><span style="display:none">Office PUC</span>';
					} elseif($table_row['Amenities'] == 'mobile') {
						$am_icon_list .= '<i class="material-icons am-icons tooltipped cGrey" data-position="bottom" data-delay="50" data-tooltip="Mobile PUC">local_shipping</i><span style="display:none">Mobile PUC</span>';
					} else {
						$am_icon_list .= '<i class="material-icons am-icons tooltipped cGrey" data-position="bottom" data-delay="50" data-tooltip="PetrolBunk PUC">local_gas_station</i><span style="display:none">Petrol Bunk PUC</span>';
					}
				}
				$am_icon_list .= '</div>';
				if ($this->input->cookie('servicetype') != 9 && $this->input->cookie('servicetype') != 10 && $this->input->cookie('servicetype') != 11) {
					$table_content .= '<tr class="details" id="sc_' . $table_row['ScId'] . '_' . $serid . '">
						<td>' . convert_to_camel_case($table_row['ScName']) . $am_icon_list . '</td>
						<td><div id="r_'.$table_row['ScId'].'x" hidden>' . $table_row['Rating'] . '</div><div id="r_'.$table_row['ScId'].'" type="number" class="rate_it_i_say" >' . $table_row['Rating'] . '</div>';
					if(intval($table_row['RatersCount']) > 0) {
						$table_content .= '<div style="color:#028cbc;text-decoration:underline;cursor:pointer;" onclick="getRatings(' . $table_row['ScId'] . ');">' . $table_row['RatersCount'] . ' Ratings</div>';
					}
					$table_content .= '</td>';
				} elseif($this->input->cookie('servicetype') == 11) {
					$table_content .= '<tr class="details" id="sc_' . $table_row['ScId'] . '_' . $serid . '">
					<td>' . convert_to_camel_case($table_row['ScName']) . '</td>';
				} else {
					$table_content .= '<tr class="details" id="sc_' . $table_row['ScId'] . '_' . $serid . '">
					<td>' . convert_to_camel_case($table_row['ScName']) . '</td><td>' . $am_icon_list . '</td>';
				}
				if ($this->input->cookie('servicetype') == 11) {
					$table_content .= '<td>' . $table_row['Phone'] . '</td>';
				}
				$table_content .= '<td data-sort="' . $table_row['Distance'] . '">' . convert_to_camel_case($table_row['LocationName']);
				if($this->input->cookie('servicetype') != 7) {
					$table_content .= '<div>
						<i class="fa fa-street-view margin-top-5px cGrey">&nbsp;<span class="dist-map">' . $table_row['Distance'] . ' Km</span></i>
					</div>';
				}
				$table_content .= '</td>';
				if ($this->input->cookie('servicetype') == 1 || $this->input->cookie('servicetype') == 4) {
					$table_content .= '<td><div class="price-tag">&nbsp;'.$table_row['Price'].'</div></td>';
				}
				if ($this->input->cookie('servicetype') == 3) {
					$table_content .= '<td><div class="checkbox query_cb1" style=""><label class="query_cb"><input type="checkbox" class="query_checkb" name="query[]" value="' . $table_row['ScId'] . '" style="margin-right:5px"></label></div></td></tr>';
				} elseif($this->input->cookie('servicetype') == 7) {
					$table_content .= '<td class="locate" data-sort="' . $table_row['Distance'] . '"><i class="fa fa-map-marker map-click">&nbsp;' . $table_row['Distance'] . '</i>
					<div style="font-size: 10px;">(More Details..)</div></td>';
					$table_content .= '<td class="last">
						<button type="submit" name="submitform" id="call_1" data-target="call_' . $table_row['ScId'] . '" class="btn waves-effect waves-light modal-trigger"><i class="fa fa-phone"></i></button>
						<button type="submit" name="submitform" id="sms_1" data-target="sms_' . $table_row['ScId'] . '" class="btn waves-effect waves-light modal-trigger"><i class="fa fa-comments"></i></button>
					</td>';
					$table_content .= '<div class="modal" id="call_' . $table_row['ScId'] . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content flat-modal">
								<div class="modal-header custom-modal-header">
									<a class="close sClose modal-close" id="closeM" aria-label="Close"><span aria-hidden="true">X</span></a>
									<h4 class="modal-title">Call - ' . convert_to_camel_case($table_row['ScName']) . '</h4>
								</div>
								<div class="modal-body signup-modal-body">
								<div class="row">
									<div class="col s12 ">
										<div class="col s12 emModalTitle"><div><span class="white-font"><strong>Mobile :</strong></span> ' . $table_row['ScPhone'] . '</div></div>
										<div class="col s12 emModalTitle"><div><span class="white-font"><strong>Landline :</strong></span> ' . $table_row['ScLand'] . '</div></div>
									</div>
								</div>
								</div>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->';
					$table_content .= '<div class="modal" id="sms_' . $table_row['ScId'] . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content flat-modal">
								<div class="modal-header custom-modal-header">
									<a class="close sClose modal-close" id="closeM" aria-label="Close"><span aria-hidden="true">X</span></a>
									<h4 class="modal-title">SMS - ' . convert_to_camel_case($table_row['ScName']) . '</h4>
								</div>
								<div class="modal-body signup-modal-body">
								<div class="row">
									<div class="col s12 ">
										<div class="col s12 emModalTitle"><div><span class="white-font"><strong>Mobile :</strong></span> ' . $table_row['ScPhone'] . '</div></div>
									</div>
									<div class="col s12">
										<textarea class="col-xs-12 text-area white-bg" name="comments" id="comments" placeholder="Type your message here"></textarea>
									</div>
								</div>
								</div>
								<div class="col s12">
									<button type="button" class="btn waves-effect waves-light">Send</button>
								</div>
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->';
				} elseif($this->input->cookie('servicetype') == 9 || $this->input->cookie('servicetype') == 10 || $this->input->cookie('servicetype') == 11) {
					$table_content .= '<td><div><button type="submit" name="submitform" id="" class="details btn btn-primary">More ..</button></div></td>';
				} else {
					$table_content .= '<td>
						<div>
							<button type="submit" name="submitform" id="" class="details btn waves-effect waves-light hide-on-small-only" >
								Select';
					if ($this->input->cookie('servicetype') == 1) {
						$table_content .= '<span class="slotsVal sActive">' . $table_row['SlotCount'] . '</span>';
					}
					$table_content .= '</button>
						</div>
					</td></tr>';
				}
				$am_count += 1;
			}
		}
		return $table_content;
	}
	private function parseAmenityIcon($amname, $amdesc, $amicon) {
		$temp = str_replace("{AmName}", $amname, $amicon);
		$temp = str_replace("{AmDesc}", $amdesc, $temp);
		$temp = $temp . '<span style="display:none">' . $amname . '</span>';
		return $temp;
	}
	private function distance($lat1, $lon1, $lat2, $lon2, $unit) {
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
	private function set_query_data() {
		$this->set_query_cookie('area', $this->input->post('area'));
		$this->set_query_cookie('qlati', $this->input->post('qlati'));
		$this->set_query_cookie('qlongi', $this->input->post('qlongi'));
		$this->set_query_cookie('servicetype', $this->input->post('servicetype'));
		if($this->input->post('servicetype') != '9' && $this->input->post('servicetype') != '10' && $this->input->post('servicetype') != '11') {
			if($this->input->post('servicetype') == '3' || $this->input->post('servicetype') == '7') {
				$this->set_query_cookie('date_', $this->input->post('date_query'));
				$this->set_query_cookie('date', date('Y-m-d', strtotime($this->input->post('date_query'))));
			} else {
				$this->set_query_cookie('date_', $this->input->post('date_'));
				$this->set_query_cookie('date', date('Y-m-d', strtotime($this->input->post('date_'))));
			}
			$this->set_query_cookie('company', $this->input->post('company'));
			$this->set_query_cookie('model', $this->input->post('model'));
		} else {
			delete_cookie('date_');
			delete_cookie('company');
			delete_cookie('model');
		}
	}
	private function get_query_data() {
		$this->data['area'] = $this->input->cookie('area');
		$this->data['servicetype'] = $this->service_m->get($this->input->cookie('servicetype'))->ServiceName;
		$this->data['serid'] = intval($this->input->cookie('servicetype'));
		if(intval($this->input->cookie('servicetype')) != 9 && intval($this->input->cookie('servicetype')) != 10 && intval($this->input->cookie('servicetype')) != 11) {
			$this->data['servicedate'] = $this->input->cookie('date_');
			$this->data['company'] = $this->bikecompany_m->get($this->input->cookie('company'))->BikeCompanyName;
			$this->data['bikemodel'] = $this->bikemodel_m->get($this->input->cookie('model'))->BikeModelName;
		}
	}
	private function is_query_set() {
		if((bool)$this->input->cookie('servicetype')) {
			if(intval($this->input->cookie('servicetype')) == 9 || intval($this->input->cookie('servicetype')) == 10 || intval($this->input->cookie('servicetype')) == 11) {
				return (bool)$this->input->cookie('area') && (bool)$this->input->cookie('qlati') && (bool)$this->input->cookie('qlongi');
			} else {
				$cookies = array('area', 'date_', 'company', 'model', 'date', 'qlati', 'qlongi');
			}
			foreach ($cookies as $cookie) {
				if (!(bool)$this->input->cookie($cookie)) {
					return FALSE;
				}
			}
			return TRUE;
		} else {
			return FALSE;
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
	public function getScRatings() {
		$sc_id = $_POST['ScId']; $rating = array();
		$this->db->select('servicecenter.ScName, FORMAT(servicecenter.Rating, "1") AS Rating, servicecenter.RatersCount')->from('servicecenter');
		$this->db->where('servicecenter.ScId', $sc_id);
		$overall = $this->db->get()->row_array();
		$this->db->select('user_feedback.ExecFbAnswer, odetails.user_feedback_rating AS admin_rating')->from('user_feedback');
		$this->db->join('odetails', 'odetails.OId = user_feedback.OId', 'left');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->where('user_feedback.ExecFbQId', 3);
		$this->db->where('oservicedetail.ScId', $sc_id);
		$result = $this->db->get()->result_array();
		if(count($result) > 0) {
			foreach ($result as &$review) {
				if(floatval($review['admin_rating']) > 0) {
					$review['ExecFbAnswer'] = round((($review['ExecFbAnswer'] + $review['admin_rating']) / 2)); unset($review['admin_rating']);
				}
			}
			$rating['1'] = 0; $rating['2'] = 0; $rating['3'] = 0; $rating['4'] = 0; $rating['5'] = 0;
			foreach ($result as $row) {
				$rating[$row['ExecFbAnswer']] += 1;
			}
		} else {
			$rating = array();
		}
		$this->db->select('odetails.user_feedback_remarks AS remarks, odetails.user_feedback_rating AS admin_rating, odetails.ODate AS date, bikemodel.BikeModelName AS model, bikecompany.BikeCompanyName AS company, user.UserName AS name, user_feedback.ExecFbAnswer AS rating')->from('odetails');
		$this->db->join('oservicedetail', 'oservicedetail.OId = odetails.OId', 'left');
		$this->db->join('user_feedback', 'user_feedback.OId = odetails.OId');
		$this->db->join('user', 'user.UserId = odetails.UserId');
		$this->db->join('bikemodel', 'bikemodel.BikeModelId = odetails.BikeModelId');
		$this->db->join('bikecompany', 'bikecompany.BikeCompanyId = bikemodel.BikeCompanyId');
		$this->db->where('oservicedetail.ScId', $sc_id);
		$this->db->where('user_feedback_remarks IS NOT NULL')->where('user_feedback_remarks !=', '')->where('ExecFbQId', 3);
		$reviews = $this->db->get()->result_array();
		if(count($reviews) == 0) {
			$reviews = array();
		} else {
			foreach ($reviews as &$review) {
				if(floatval($review['admin_rating']) > 0) {
					$review['rating'] = round((($review['rating'] + $review['admin_rating']) / 2), 1); unset($review['admin_rating']);
				}
			}
		}
		$reviews = array();
		echo json_encode(array("ratings" => $rating, "overall" => $overall, "reviews" => $reviews));
	}
}