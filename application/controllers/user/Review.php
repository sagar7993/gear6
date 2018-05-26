<?php
class Review extends G6_Usercontroller {
	public function __construct() {
		parent::__construct();
		$this->load->model('bikecompany_m');
		$this->load->model('bikemodel_m');
		$this->load->model('service_m');
		$this->load->model('servicecenter_m');
		if ($this->is_query_set()) {
			if($this->input->cookie('servicetype') != 4 && $this->insert_additional_locations()) {
				redirect('/user/review');
			}
			$this->get_query_data();
		} else {
			redirect('/user/book');
		}
	}
	public function index () {
		$this->check_visitor_count();
		if ($this->input->cookie('servicetype') == 4) {
			redirect('/user/review/insReview');
		} else {
			$this->check_for_resc_order();
			$this->clear_coupon_session();
			$this->get_amenities();
			if($this->data['serid'] == 1) {
				$this->get_aservices();
			}
			$this->load->model('opaymtdetail_m');
			$this->data['paymt_options'] = $this->opaymtdetail_m->get_paymt_types();
			$this->load_loc_regnum();
			if ($this->data['is_logged_in'] == 0 && $this->validate_phone()) {
				$this->load->model('otp_m');
				$temp = $this->otp_m->is_otp_inserted();
				if ($temp) {
					$this->data['otp_val'] = $temp;
				} else {
					$this->data['otp_val'] = $this->otp_m->insert_otp();
				}
				$this->send_sms_request_to_api($this->input->cookie('phone'), "Your OTP is " . $this->data['otp_val'] . " for your mobile confirmation at gear6.in");
				$this->data['ph_num'] = $this->input->cookie('phone');
				$this->insert_user_phone();
			} elseif($this->data['is_logged_in'] == 0 && !$this->validate_phone()) {
				$this->data['open_blklogin_modal'] = 1;
			}
			if ($this->data['is_logged_in'] == 1) {
				$this->get_user_addresses();
				$this->data['u_name'] = $this->session->userdata('name');
				$this->data['u_email'] = $this->session->userdata('email');
				$this->data['ph_num'] = $this->session->userdata('phone');
			}
			$this->load->view('user/review', $this->data);
		}
	}
	public function insReview() {
		if ($this->input->cookie('servicetype') != 4) {
			redirect('/user/review');
		} else {
			$this->check_for_resc_order();
			$this->clear_coupon_session();
			$this->get_amenities();
			$this->load->model('opaymtdetail_m');
			$this->data['paymt_options'] = $this->opaymtdetail_m->get_paymt_types();
			$this->load_loc_regnum();
			$this->load->model('insurer_m');
			$this->data['insurers'] = $this->insurer_m->get();
			if ($this->data['is_logged_in'] == 1) {
				$this->get_user_addresses();
				$this->data['ph_num'] = $this->session->userdata('phone');
				$this->data['u_name'] = $this->session->userdata('name');
				$this->data['u_email'] = $this->session->userdata('email');
			}
			$this->load->view('user/insReview', $this->data);
		}
	}
	public function initiateTrxn() {
		if($_POST) {
			$this->load->model('odetails_m');
			$this->load->model('amenity_m');
			$slot_test = TRUE;
			if ($this->input->cookie('servicetype') == 1) {
				$slot_test = $this->expire_buffered_slot();
			}
			if($slot_test) {
				if ($this->data['is_logged_in'] == 0) {
					$usr = $this->user_m->create_user();
					$usr_id = $usr['UserId'];
					if(intval($this->input->cookie('servicetype')) != 3) {
						$user_addr_id = $this->user_m->updt_useraddr($usr_id, 1);
					} else {
						$user_addr_id = NULL;
					}
					$this->send_sms_request_to_api($usr['Phone'], 'You are successfully registered at gear6.in. Below are your login details. Login Id: ' . $usr['Phone'] . ' Password: ' . $usr['Pwd']);
				} else {
					$usr_id = $this->session->userdata('id');
					$usr['Phone'] = $this->session->userdata('phone');
					if (intval($this->input->post('addr')) != 0) {
						$user_addr_id = intval($this->input->post('addr'));
					} else {
						if(intval($this->input->cookie('servicetype')) != 3) {
							$user_addr_id = $this->user_m->updt_useraddr($usr_id);
						} else {
							$user_addr_id = NULL;
						}
					}
				}
				$amtys = '';
				if($usr_id) {
					$OId = $this->odetails_m->create_order($user_addr_id, $usr_id);
					if ($this->input->cookie('servicetype') != 3) {
						$amtys = $this->odetails_m->insert_amenities($OId);
						if($this->input->cookie('servicetype') == 1) {
							$this->odetails_m->insert_asers($OId);
						}
						$price = $this->amenity_m->insert_price_split($OId, $this->data['servicetype']);
						if($price < 0.01) {
							$price = 0;
						}
					} else {
						$price = 0;
					}
					if ($this->input->cookie('servicetype') == 4) {
						$this->odetails_m->insert_insurance($OId);
					}
					if(($this->input->cookie('servicetype') == 2 || $this->input->cookie('servicetype') == 3 || $this->input->cookie('servicetype') == 1) && $this->input->post('imageupload') != "") {
						$this->finalize_image_upload($this->input->post('imageupload'), $OId);
					}
					$this->odetails_m->insert_oservicedetail($OId, $amtys, $price);
					$this->clear_order_cookies();
					$this->clear_coupon_session();
					$this->set_query_cookie('oid', $OId);
					$this->set_query_cookie('is_new_order', 1);
					delete_cookie('is_porder_trxn');
					if($price >= 0.01 && $this->input->post('paymt') != "" && $this->input->post('paymt') != 'COD') {
						if(intval($this->input->post('paymtgtw')) == 1) {
							echo $OId;
						}
					} else {
						$redirect_url = site_url('user/result/showStatus/' . $OId);
						echo $redirect_url;
					}
				} else {
					echo site_url('user/review');
				}
			} else {
				echo site_url('user/book');
			}
		}
	}
	public function check_otp() {
		if($_POST) {
			$this->load->model('otp_m');
			echo $this->otp_m->check_otp($this->input->post('otp'));
		}
	}
	public function omedia_upload() {
		$this->load->library('upload', $this->upload_config());
		$count = 0;
		for($i = 1; $i <= 3; $i++) {
			if($this->upload->do_upload('uploadImage_' . $i)) {
				$file_data = $this->upload->data();
				$upload_data[$count]['name'] = $file_data['file_name'];
				if(boolval($file_data['is_image'])) {
					$upload_data[$count]['type'] = 'img';
				} else {
					$upload_data[$count]['type'] = 'vid';
				}
				$count += 1;
			}
		}
		if(isset($upload_data) && count($upload_data) > 0) {
			$this->upload_to_s3temp($upload_data);
			echo serialize($upload_data);
		} else {
			echo "";
		}
	}
	public function check_phone() {
		if($_POST) {
			if ($this->input->post('phone') != '') {
				if ($this->data['is_logged_in'] == 0) {
					$value = intval($this->user_m->is_unique_ph($this->input->post('phone')));
					if($value == 1) {
						$this->load->model('otp_m');
						$temp = $this->otp_m->is_otp_inserted();
						if ($temp) {
							$this->data['otp_val'] = $temp;
						} else {
							$this->data['otp_val'] = $this->otp_m->insert_otp();
						}
						$this->send_sms_request_to_api($this->input->cookie('phone'), "Your OTP is " . $this->data['otp_val'] . " for your mobile confirmation at gear6.in");
						$this->data['ph_num'] = $this->input->cookie('phone');
						$this->insert_user_phone();
					}
					echo $value;
				} else {
					echo 0;
				}
			}
		}
	}
	public function set_insurance_renewal_cookies() {
		if($_POST) {
			if ($this->input->post('phone') != '' && $this->input->post('slot') != '') {
				if ($this->data['is_logged_in'] == 0) {
					$this->set_query_cookie('phone', $this->input->post('phone'));
					$this->set_query_cookie('slot', $this->input->post('slot'));
				} else {
					delete_cookie('phone');
					$this->set_query_cookie('slot', $this->input->post('slot'));
				}
				echo 1;
			} else {
				echo 0;
			}
		} else {
			echo 0;
		}
	}
	public function get_prices($ajax = TRUE) {
		if($_POST) {
			if ($this->input->post('amtys') != '') {
				$amtys = explode(',', $this->input->post('amtys'));
			} else {
				$amtys = "";
			}
			if ($this->input->post('asers') != '') {
				$asers = explode(',', $this->input->post('asers'));
			} else {
				$asers = "";
			}
			$this->load->model('amenity_m');
			if(intval($this->data['serid']) != 3) {
				$this->set_query_cookie('area', $this->input->post('user_lc'));
			}
			$this->data['amprices'] = $this->amenity_m->get_order_est_price($amtys, $this->data['servicetype'], $asers);
			if($ajax) {
				$this->load->view('user/components/_pricedetails', $this->data);
			} else {
				return $this->data['amprices'];
			}
		}
	}
	public function get_free_service_discount() {
		if($_POST) {
			$this->load->model('servicecenter_m');
			$purchase_value = floatval($this->input->post('pprice'));
			$discount_amount = floatval($this->servicecenter_m->price_by_id($this->input->cookie('sc_id')));
			$output_data['fsvalue'] = $discount_amount + round(($discount_amount * 0.15), 2);
			$output_data['to_pay'] = $purchase_value - $output_data['fsvalue'];
			$this->session->set_userdata('to_pay', $output_data['to_pay']);
			$this->session->set_userdata('fsvalue', $output_data['fsvalue']);
			echo json_encode($output_data);
		}
	}
	private function insert_user_phone() {
		$phone = $this->db->select('*')->from('dropped_orders')->where('Phone', $this->data['ph_num'])->get()->row();
		$uphone['LocationName'] = $this->input->cookie('area');
		$uphone['CityId'] = intval($this->input->cookie('CityId'));
		$uphone['ServiceId'] = $this->input->cookie('servicetype');
		$uphone['ScId'] = $this->input->cookie('sc_id');
		$uphone['BikeCompanyId'] = $this->input->cookie('company');
		$uphone['BikeModelId'] = $this->input->cookie('model');
		if($phone) {
			$this->db->where('Phone', $phone->Phone)->update('dropped_orders', $uphone);
		} else {
			$uphone['Phone'] = $this->data['ph_num'];
			$this->db->insert('dropped_orders', $uphone);
		}
	}
	private function expire_buffered_slot() {
		$this->load->model('servicecenter_m');
		$remaining_slots = $this->servicecenter_m->check_if_slots_still_exists();
		$eff_slots = intval($remaining_slots['Slots']) - intval($remaining_slots['BufferedSlots']);
		$data['Status'] = 1;
		$this->db->where('SlotBufferId', intval($this->input->cookie('buffered_slot')));
		$this->db->update('slotsbuffer', $data);
		if($this->db->affected_rows() <= 0) {
			if($eff_slots > 0) {
				$data = array(
					'SlotId' => intval($remaining_slots['SlotId']),
					'Status' => 1
				);
				$this->db->insert('slotsbuffer', $data);
				return TRUE;
			} else {
				delete_cookie('slot');
				delete_cookie('sc_id');
				delete_cookie('buffered_slot');
				return FALSE;
			}
		}
		return TRUE;
	}
	private function check_visitor_count() {
		if($this->input->cookie('g6data2') == "" || $this->input->cookie('g6data2') === NULL) {
			$cookie = array(
				'name'   => 'g6data2',
				'value'  => 'iamhere2',
				'expire' => '600',
				'secure' => FALSE
			);
			$this->input->set_cookie($cookie);
			$this->load->model('g6data_m');
			$count = intval($this->g6data_m->get(1)->RevVisitCount);
			$ncount['RevVisitCount'] = $count + 1;
			$this->db->where('G6DataId', 1);
			$this->db->update('g6data', $ncount);
		}
	}
	private function check_for_resc_order() {
		if($this->input->cookie('res_order') != "") {
			$this->data['is_resc_order'] = 1;
		}
	}
	private function clear_coupon_session() {
		$this->session->unset_userdata('fcid');
		$this->session->unset_userdata('fdvalue');
		$this->session->unset_userdata('cid');
		$this->session->unset_userdata('cdvalue');
		$this->session->unset_userdata('to_pay');
		$this->session->unset_userdata('fsvalue');
	}
	private function get_amenities() {
		$this->load->model('amenity_m');
		$this->data['amenities'] = $this->amenity_m->get_amenities_by_service();
	}
	private function get_aservices() {
		$this->load->model('aservice_m');
		$this->data['aservices'] = $this->aservice_m->get_aservices_for_order(NULL, NULL, 0);
		$this->data['maservices'] = $this->aservice_m->get_aservices_for_order(NULL, NULL, 1);
	}
	private function get_user_addresses() {
		$this->data['user_addresses'] = $this->user_m->get_user_address_by_location();
		$this->data['bikedetails'] = $this->user_m->get_user_bike_num();
	}
	private function validate_phone() {
		if($this->data['is_logged_in'] == 0) {
			$ph = $this->input->cookie('phone');
			return $this->user_m->is_unique_ph($ph);
		} else {
			return FALSE;
		}
	}
	private function is_query_set() {
		if ($this->input->cookie('servicetype') == 2) {
			$cookies = array('area', 'servicetype', 'date_', 'date', 'company', 'model', 'slot', 'sc_id');
		} elseif ($this->input->cookie('servicetype') == 3) {
			$cookies = array('area', 'servicetype', 'date_', 'date', 'company', 'model', 'sc_ids', 'qtype');
		} elseif($this->input->cookie('servicetype') == 4) {
			$cookies = array('area', 'servicetype', 'date_', 'date', 'company', 'model');
		} else {
			$cookies = array('area', 'servicetype', 'date_', 'date', 'company', 'model', 'slot', 'sc_id', 'buffered_slot');
		}
		if ($this->data['is_logged_in'] == 0) {
			if($this->input->cookie('servicetype') != 4){
				$cookies[] = 'phone';
			}
		}
		$cookies[] = 'qlati';
		$cookies[] = 'qlongi';
		foreach ($cookies as $cookie) {
			if (!(bool)$this->input->cookie($cookie)) {
				return FALSE;
			}
		}
		return TRUE;
	}
	private function get_query_data() {
		$this->data['serid'] = intval($this->input->cookie('servicetype'));
		$this->data['servicetype'] = $this->service_m->get($this->input->cookie('servicetype'))->ServiceName;
		$this->data['servicedate'] = $this->input->cookie('date_');
		$this->data['company'] = $this->bikecompany_m->get($this->input->cookie('company'))->BikeCompanyName;
		$this->data['bikemodel'] = $this->bikemodel_m->get($this->input->cookie('model'))->BikeModelName;
		if ($this->input->cookie('servicetype') != 3) {
			if ($this->input->cookie('slot') > 12) {
				$temp_hr = intval($this->input->cookie('slot') - 12);
				$temp = (intval($this->input->cookie('slot') * 60) % 60);
				if($temp == 0) {
					$temp = '00';
				}
				$this->data['servicedate'] .= ' - ' . $temp_hr . ":" . $temp . "&nbsp;PM";
			} elseif ($this->input->cookie('slot') == 12) {
				$this->data['servicedate'] .= ' - ' . intval($this->input->cookie('slot')) . ":00&nbsp;PM";
			} else {
				$temp = (intval($this->input->cookie('slot') * 60) % 60);
				if($temp == 0) {
					$temp = '00';
				}
				$this->data['servicedate'] .= ' - ' . intval($this->input->cookie('slot')) . ":" . $temp . "&nbsp;AM";
			}
		}
		if ($this->input->cookie('servicetype') == 3) {
			$sc_ids = explode(',', $this->input->cookie('sc_ids'));
			foreach ($sc_ids as $sc_id) {
				$this->data['servicecenter'][] = $this->servicecenter_m->get(intval($sc_id))->ScName;
			}
		}
		elseif ($this->input->cookie('servicetype') == 4) {

		} else {
			$this->data['servicecenter'] = $this->servicecenter_m->get(intval($this->input->cookie('sc_id')))->ScName;
		}
	}
	private function load_loc_regnum() {
		if ($this->city_m->iscityset()) {
			$this->load->model('location_m');
			$areas = $this->location_m->locations_for_sc();
			if (count($areas) > 0) {
				$this->data['areas'] = '"' . implode('", "', $areas) . '"';
			}
		}
		$this->load->model('regnum_m');
		$regnums = $this->regnum_m->get_all_regnumvals();
		if (count($regnums) > 0) {
			$this->data['regnums'] = '"' . implode('", "', $regnums) . '"';
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
	private function upload_to_s3temp($uploaded_media) {
		foreach($uploaded_media as $file) {
			$from_file = realpath(APPPATH . '../html/uploads/temp');
			$from_file = rtrim($from_file, '/').'/';
			$from_file .= $file['name'];
			$to_file = 'temp/';
			$to_file .= $file['name'];
			$this->load->library('awssdk');
			$s3 = $this->awssdk->get_s3_instance();
			try {
				$s3->putObject([
					'Bucket' => 'gear6cdn',
					'Key'    => $to_file,
					'Body'   => fopen($from_file, 'r'),
					'ACL'    => 'public-read',
				]);
			} catch (Aws\Exception\S3Exception $e) {
			}
			unlink($from_file);
		}
	}
	private function finalize_image_upload($imgdata, $oid) {
		$uploaded_media = unserialize(stripslashes($imgdata));
		$count = 0;
		foreach($uploaded_media as $file) {
			$to_file = 'uploads/omedia/';
			$to_file .= $file['type'] . '/' . $file['name'];
			$this->load->library('awssdk');
			$s3 = $this->awssdk->get_s3_instance();
			try {
				$s3->copyObject(array(
					'Bucket'     => 'gear6cdn',
					'Key'        => $to_file,
					'CopySource' => 'gear6cdn/temp/' . $file['name'],
					'ACL'    => 'public-read',
				));
				$s3->deleteObject(array(
					'Bucket' => 'gear6cdn',
					'Key'    => 'temp/' . $file['name']
				));
			} catch (Aws\Exception\S3Exception $e) {
			}
			$data[$count]['OId'] = $oid;
			$data[$count]['FileType'] = $file['type'];
			$data[$count]['FileData'] = $file['name'];
			$data[$count]['MediaType'] = 'info';
			$count += 1;
		}
		$this->db->insert_batch('omedia', $data);
	}
	private function insert_additional_locations() {
		if ($this->input->cookie('servicetype') == 1 || $this->input->cookie('servicetype') == 2) {
			$sc_id = $this->input->cookie('sc_id');
		} elseif ($this->input->cookie('servicetype') == 3) {
			$sc_ids = explode(',', $this->input->cookie('sc_ids'));
			$sc_id = $sc_ids[0];
		}
		$new_city = $this->db->select('CityId')->from('scaddrsplit')->where('ScId', intval($sc_id))->get()->row_array();
		$city_id = intval($new_city['CityId']);
		$this->city_m->set_city($city_id);
		$lc_row = $this->db->select('LocationId')->from('location')->where('LocationName', $this->input->cookie('area'))->where('CityId', $city_id)->get()->row_array();
		if(!$lc_row) {
			$new_lc['LocationName'] = $this->input->cookie('area');
			$new_lc['Latitude'] = floatval($this->input->cookie('qlati'));
			$new_lc['Longitude'] = floatval($this->input->cookie('qlongi'));
			$new_lc['CityId'] = $city_id;
			$this->db->insert('location', $new_lc);
			return TRUE;
		} else {
			return FALSE;
		}
	}
	private function upload_config() {
		$config['allowed_types'] = 'gif|jpg|png|jpeg|mp4|webm|ogg';
		$config['upload_path'] = realpath(APPPATH . '../html/uploads/temp');
		$config['encrypt_name'] = TRUE;
		$config['remove_spaces'] = TRUE;
		$config['max_size'] = '5120';
		$config['overwrite'] = FALSE;
		return $config;
	}
	private function clear_order_cookies() {
		$cookies = array('area', 'servicetype', 'date_', 'date', 'company', 'model', 'slot', 'sc_id', 'buffered_slot', 'repdesc', 'sc_ids', 'phone', 'qtype', 'qlati', 'qlongi');
		foreach ($cookies as $cookie) {
			delete_cookie($cookie);
		}
	}
}