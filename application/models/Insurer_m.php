<?php
class Insurer_m extends G6_Model {
	protected $_table_name = 'insurer';
	protected $_primary_key = 'InsurerId';
	protected $_order_by = 'InsurerName';
	public function __construct() {
		parent::__construct();
	}
}