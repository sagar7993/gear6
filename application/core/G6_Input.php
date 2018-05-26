<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class G6_Input extends CI_Input {
	public function __construct() {
		parent::__construct();
	}
	public function ip_address() {
		if ($this->ip_address !== FALSE) {
			return $this->ip_address;
		}
		$this->ip_address = $this->server('HTTP_X_FORWARDED_FOR');
		if(!$this->valid_ip($this->ip_address)) {
			$this->ip_address = $this->server('REMOTE_ADDR');
		}
		if (!$this->valid_ip($this->ip_address)) {
			return $this->ip_address = '0.0.0.0';
		}
		return $this->ip_address;
	}
}