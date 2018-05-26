<?php
class G6data_m extends G6_Model {
	protected $_table_name = 'g6data';
	protected $_primary_key = 'G6DataId';
	protected $_order_by = 'G6DataId';
	public function __construct() {
		parent::__construct();
	}
}