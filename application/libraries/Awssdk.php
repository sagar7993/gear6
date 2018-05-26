<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Awssdk {
	public function __construct() {
		require_once('awsphpsdk/aws-autoloader.php');
	}
	public function get_s3_instance() {
		$credentials = new Aws\Credentials\Credentials('AKIAJAIWOCRGRWHWAN4Q', 'Nirldr8fVCKmwOFGDeIl2HELVnv/VkZJTRkIYvMk');
		$s3 = new Aws\S3\S3Client([
			'version'		=> 'latest',
			'region'		=> 'ap-south-1',
			'credentials'	=> $credentials
		]);
		return $s3;
	}
	public function get_ses_instance() {
		$credentials = new Aws\Credentials\Credentials('AKIAJAIWOCRGRWHWAN4Q', 'Nirldr8fVCKmwOFGDeIl2HELVnv/VkZJTRkIYvMk');
		$ses = new Aws\Ses\SesClient(array(
			'version'		=> 'latest',
			'region'		=> 'us-east-1',
			'credentials'	=> $credentials
		));
		return $ses;
	}
}