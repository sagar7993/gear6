<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class G6_Log extends CI_Log {
	public function __construct() {
		parent::__construct();
	}
	public function write_log($level, $msg) {
		static $_ua;
		if($_ua === NULL) {
			$_ua[0] =& $this->load_ua_class();
		}
		if($_ua[0]->is_mobile('iphone')) {
			$msg =  '[iPhone]' . ' - ' . $msg;
		} elseif($_ua[0]->is_mobile('android')) {
			$msg =  '[Android]' . ' - ' . $msg;
		} elseif($_ua[0]->is_mobile()) {
			$msg =  '[Mobile]' . ' - ' . $msg;
		} elseif($_ua[0]->is_browser()) {
			$msg =  '[Website]' . ' - ' . $msg;
		} elseif($_ua[0]->is_robot()) {
			$msg =  '[Bot]' . ' - ' . $msg;
		}
		if ($this->_enabled === FALSE) {
			return FALSE;
		}
		$level = strtoupper($level);
		if (( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold)) && ! isset($this->_threshold_array[$this->_levels[$level]]))	{
			return FALSE;
		}
		$filepath = $this->_log_path.'log-'.date('Y-m-d').'.'.$this->_file_ext;
		$message = '';
		if ( ! file_exists($filepath)) {
			$newfile = TRUE;
			if ($this->_file_ext === 'php')	{
				$message .= "<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>\n\n";
			}
		}
		if ( ! $fp = @fopen($filepath, 'ab')) {
			return FALSE;
		}
		flock($fp, LOCK_EX);
		if (strpos($this->_date_fmt, 'u') !== FALSE) {
			$microtime_full = microtime(TRUE);
			$microtime_short = sprintf("%06d", ($microtime_full - floor($microtime_full)) * 1000000);
			$date = new DateTime(date('Y-m-d H:i:s.'.$microtime_short, $microtime_full));
			$date = $date->format($this->_date_fmt);
		} else {
			$date = date($this->_date_fmt);
		}
		$message .= $this->_format_line($level, $date, $msg);
		for ($written = 0, $length = strlen($message); $written < $length; $written += $result) {
			if (($result = fwrite($fp, substr($message, $written))) === FALSE) {
				break;
			}
		}
		flock($fp, LOCK_UN);
		fclose($fp);
		if (isset($newfile) && $newfile === TRUE) {
			chmod($filepath, $this->_file_permissions);
		}
		return is_int($result);
	}
	private function &load_ua_class() {
		static $_g6 = array();
		if (isset($_g6['ua'])) {
			return $_g6['ua'];
		}
		require_once(APPPATH . 'libraries/User_agent.php');
		$_g6['ua'] = new CI_User_agent();
		return $_g6['ua'];
	}
}