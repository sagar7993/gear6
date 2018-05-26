<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auth {
	protected $denied_uris = array(
		0 => array(),
		1 => array("contact"),
		2 => array("contact", "services", "price-chart", "pickup", "musers", "payment", "onexcl", 'aservices')
	);
	protected $profiles = array("Admin" => 0, "Super User" => 1, "User" => 2);
	protected $current_role;
	private $CI;

	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->driver('session');
		$this->CI->load->model('vendor_m');
		if ($this->CI->vendor_m->loggedin() == TRUE) {
			$this->current_role = $this->profiles[$this->CI->session->userdata('v_role')];
		} else {
			$this->current_role = FALSE;
		}
	}
	public function check_access($access_uri, $redirect_uri = FALSE) {
		if($this->current_role && in_array($access_uri, $this->denied_uris[$this->current_role])) {
			if($redirect_uri) {
				redirect('/vendor/' . $redirect_uri);
			} else {
				redirect('/vendor');
			}
		}
	}
	public function get_denied_tabs() {
		return $this->denied_uris[$this->current_role];
	}
	public function check_vmod($id) {
		$sel_user = $this->CI->vendor_m->get_by(array('VendorId' => intval($id), 'ScId' => intval($this->CI->session->userdata('v_sc_id'))), TRUE);
		if($sel_user) {
			if($this->profiles[$sel_user->UserPrivilege] <= $this->current_role) {
				return FALSE;
			} else {
				return TRUE;
			}
		} else {
			return FALSE;
		}
	}
	public function check_privilege($priv) {
		$sel_priv = $this->profiles[$priv];
		if($sel_priv <= $this->current_role) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
}