<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Bauth {
	protected $denied_uris = array(
		0 => array()
	);
	protected $profiles = array("HJ Admin" => 0);
	protected $current_role;
	private $CI;
	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->driver('session');
		$this->CI->load->model('tieups_m');
		if ($this->CI->tieups_m->loggedin() == TRUE) {
			$this->current_role = $this->profiles[$this->CI->session->userdata('b_role')];
		} else {
			$this->current_role = FALSE;
		}
	}
	public function check_access($access_uri, $redirect_uri = FALSE) {
		if($this->current_role && in_array($access_uri, $this->denied_uris[$this->current_role])) {
			if($redirect_uri) {
				redirect('/business/' . $redirect_uri);
			} else {
				redirect('/business');
			}
		}
	}
	public function get_denied_tabs() {
		return $this->denied_uris[$this->current_role];
	}
}