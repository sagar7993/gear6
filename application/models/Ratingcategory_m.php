<?php
class Ratingcategory_m extends G6_Model {
	protected $_table_name = 'ratingcategory';
	protected $_primary_key = 'RcId';
	protected $_order_by = 'RcId';
	public function __construct() {
		parent::__construct();
	}
	public function get_catwise_ratings($sc_id) {
		$this->db->select('*');
		$this->db->from('rating');
		$this->db->where('ScId', $sc_id);
		$this->db->order_by('RcId', 'asc');
		$this->db->limit(5);
		$query = $this->db->get();
		$result = $query->result_array();
		if (count($result) < 5) {
			return NULL;
		} else {
			return $result;
		}
	}
	public function get_all_feedbacks($admin_flag = FALSE) {
		if(!$admin_flag) {
			$this->db->select('ratingsplit.ScId, ratingsplit.OId, ratingsplit.RcId, ratingsplit.RatingValue, feedbacksc.Feedback, user.UserId, user.Phone, user.UserName');
		} else {
			$this->db->select('ratingsplit.ScId, servicecenter.ScName, ratingsplit.OId, ratingsplit.RcId, ratingsplit.RatingValue, feedbacksc.Feedback, user.UserId, user.Phone, user.UserName');
		}
		$this->db->from('ratingsplit');
		$this->db->join('feedbacksc', 'feedbacksc.OId = ratingsplit.OId AND feedbacksc.ScId = ratingsplit.ScId', 'left');
		$this->db->join('odetails', 'odetails.OId = ratingsplit.OId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		if(!$admin_flag) {
			$this->db->where('ratingsplit.ScId', intval($this->session->userdata('v_sc_id')));
		} else {
			$this->db->join('servicecenter', 'servicecenter.ScId = ratingsplit.ScId', 'left');
			if(intval($this->session->userdata('a_city_id')) > 0) {
				$this->db->where('odetails.CityId', intval($this->session->userdata('a_city_id')));
			}
		}
		$this->db->order_by('ratingsplit.OId');
		$this->db->order_by('ratingsplit.RtSplitId', 'desc');
		$query = $this->db->get();
		$results = $query->result_array();
		if(count($results) < 5) {
			return NULL;
		} else {
			$final_results = array(array());
			$count = 0;
			$totalcount = 0;
			$rcats = $this->get();
			$final_results[0]['Rating'] = 0;
			foreach($results as $result) {
				$final_results[$count]['OId'] = $result['OId'];
				$final_results[$count]['UserId'] = $result['UserId'];
				$final_results[$count]['ScId'] = $result['ScId'];
				$final_results[$count]['UserName'] = $result['UserName'];
				$final_results[$count]['Phone'] = $result['Phone'];
				$final_results[$count]['Feedback'] = $result['Feedback'];
				if($admin_flag) {
					$final_results[$count]['ScName'] = convert_to_camel_case($result['ScName']);
				}
				$final_results[$count]['Rating'] += intval($result['RatingValue']) * floatval($rcats[$result['RcId'] - 1]->Weight);
				$totalcount += 1;
				if((count($results) > $totalcount) && ($result['OId'] != $results[$totalcount]['OId'] || ($result['OId'] == $results[$totalcount]['OId'] && $result['ScId'] != $results[$totalcount]['ScId']))) {
					$count += 1;
					$final_results[$count]['Rating'] = 0;
				}
			}
			return $final_results;
		}
	}
	public function get_fback_by_oid($OId, $sc_id = NULL) {
		if($sc_id === NULL) {
			$this->db->select('user.UserId, user.UserName, RcId, RatingValue, Feedback, Remarks');
		} else {
			$this->db->select('RcId, RatingValue, Feedback, Remarks, servicecenter.ScName');
		}
		$this->db->from('ratingsplit');
		$this->db->join('odetails', 'odetails.OId = ratingsplit.OId', 'left');
		$this->db->join('user', 'user.UserId = odetails.UserId', 'left');
		$this->db->join('feedbacksc', 'feedbacksc.OId = ratingsplit.OId AND feedbacksc.ScId = ratingsplit.ScId', 'left');
		if($sc_id === NULL) {
			$this->db->where('ratingsplit.ScId', intval($this->session->userdata('v_sc_id')));
		} else {
			$this->db->join('servicecenter', 'servicecenter.ScId = ratingsplit.ScId', 'left');
			$this->db->where('ratingsplit.ScId', intval($sc_id));
		}
		$this->db->where('ratingsplit.OId', $OId);
		$this->db->order_by('RcId', 'asc');
		$this->db->limit(5);
		$query = $this->db->get();
		$result = $query->result_array();
		if(count($result) < 5) {
			return NULL;
		} else {
			return $result;
		}
	}
}