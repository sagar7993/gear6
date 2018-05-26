<?php
	class Servicecenterholiday_m extends G6_Model {
		protected $_table_name = 'service_center_holidays';
		protected $_primary_key = 'ScHId';
		protected $_order_by = 'ScHId';
		public function __construct() {
			parent::__construct();
		}
	}
?>