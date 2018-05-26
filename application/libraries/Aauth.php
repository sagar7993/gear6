<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Aauth {
	protected $denied_pages = array(
		0 => array(),
		1 => array("agregs", "apps", "mvendor", "offers", "pbs", "ecs", "pts", "vendor", "manageAdmin", "manageExecutive", "manageOffer", "reminders")
	);
	protected $denied_uris = array(
		0 => array(),
		1 => array("vfeedback", "preleases", "executiveLeave", "addadminreminder")
	);
	protected $denied_secs = array(
		0 => array(),
		1 => array("assignorders", "exportorderdata")
	);
	protected $profiles = array("Admin" => 0, "Customer Care" => 1);
	protected $current_role;
	private $CI;

	public function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->driver('session');
		$this->CI->load->model('admin_m');
		if ($this->CI->admin_m->loggedin() == TRUE) {
			$this->current_role = $this->profiles[$this->CI->session->userdata('a_role')];
		} else {
			$this->current_role = FALSE;
		}
	}
	public function check_page_access($access_uri, $redirect_uri = FALSE) {
		if($this->current_role && in_array($access_uri, $this->denied_pages[$this->current_role])) {
			if($redirect_uri) {
				redirect('/admin/' . $redirect_uri);
			} else {
				redirect('/admin');
			}
		}
	}
	public function check_uri_access($access_uri, $redirect_uri = FALSE) {
		if($this->current_role && in_array($access_uri, $this->denied_uris[$this->current_role])) {
			if($redirect_uri) {
				redirect('/admin/' . $redirect_uri);
			} else {
				redirect('/admin');
			}
		}
	}
	public function get_denied_uris() {
		return $this->denied_uris[$this->current_role];
	}
	public function get_denied_pages() {
		return $this->denied_pages[$this->current_role];
	}
	public function get_denied_sections() {
		return $this->denied_secs[$this->current_role];
	}
	public function check_amod($id) {
		$sel_user = $this->CI->admin_m->get_by(array('AdminId' => intval($id)), TRUE);
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