<?php
function btn_edit ($uri) {
	return anchor($uri, '<span class="glyphicon glyphicon-pencil"></span> Edit');
}
function btn_delete ($uri) {
	return anchor($uri, '<span class="glyphicon glyphicon-remove"></span> Delete', array(
		'onclick' => "return confirm('You are about to delete a record. This cannot be undone. Are you sure?');"
	));
}
function hyper_button($uri, $text) {
	return anchor($uri, '<button type="button" class="btn btn-success">'.$text.'</button>');
}
function btn_add ($uri, $text) {
	return anchor($uri, '<i class="fa fa-link fa-fw"></i> ' . $text);
}
function convert_to_camel_case ($str) {
	return mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
}
function crypto_rand_secure($min, $max) {
	$range = $max - $min;
	if ($range < 1) return $min;
	$log = ceil(log($range, 2));
	$bytes = intval($log / 8) + 1;
	$bits = intval($log) + 1;
	$filter = intval(1 << $bits) - 1;
	do {
		$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
		$rnd = $rnd & $filter;
	} while ($rnd >= $range);
	return $min + $rnd;
}
function get_otp_key() {
	$charset = "0123456789";
	$key = '';
	$max = strlen($charset) - 1;
	for($i = 0; $i < 6; $i++) {
		$key .= $charset[crypto_rand_secure(0, $max)];
	}
	return $key;
}
function generateUniqueString($length) {
	$charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$key = '';
	$max = strlen($charset) - 1;
	for($i = 0; $i < $length; $i++) {
		$key .= $charset[crypto_rand_secure(0, $max)];
	}
	return $key;
}
function generateNumericPassword($length) {
	$charset = "01234567890123456789012345678901234567890123456789";
	$key = '';
	$max = strlen($charset) - 1;
	for($i = 0; $i < $length; $i++) {
		$key .= $charset[crypto_rand_secure(0, $max)];
	}
	return $key;
}
function generateOrderId($id, $s_id) {
	$charset = "0123456789";
	$max = strlen($charset) - 1;
	$key = 'GR';
	if ($s_id / 10 < 1) {
		$key .= '0' . $s_id;
	} else {
		$key .= $s_id;
	}
	$key .= $id;
	for($i = 0; $i < 8 - strlen($id); $i++) {
		$key .= $charset[crypto_rand_secure(0, $max)];
	}
	return $key;
}
function generateTrxnId($id) {
	$charset = "0123456789";
	$max = strlen($charset) - 1;
	$key = 'T';
	$key .= $id;
	for($i = 0; $i < 12 - strlen($id); $i++) {
		$key .= $charset[crypto_rand_secure(0, $max)];
	}
	return $key;
}
function generateTransactionId($id) {
	$charset = "0123456789";
	$max = strlen($charset) - 1;
	$key = 'TR';
	$key .= $id;
	for($i = 0; $i < 8 - strlen($id); $i++) {
		$key .= $charset[crypto_rand_secure(0, $max)];
	}
	return $key;
}
function generate_salted_hash($string, $salt) {
	return hash('sha512', $string . $salt);
}
function generate_hash($string) {
	return hash('sha512', $string);
}
function get_float_with_two_decimal_places($priceval) {
	return number_format(floatval($priceval), 2, '.', '');
}
function generate_referal_code($id, $name) {
	$name = preg_replace('/\s+/', '', $name);
	$escapes = array(" ", ".");
	$replaces   = array("", "");
	$name = str_replace($escapes, $replaces, $name);
	$ref_code = strtoupper(substr($name, 0, 5));
	$count = 13 - (strlen($ref_code) + strlen($id));
	for($i = 0; $i < $count; $i++) {
		$ref_code .= '0';
	}
	$ref_code .= $id;
	return $ref_code;
}
function generateCouponCode($length) {
	$charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$key = 'G6';
	$max = strlen($charset) - 1;
	for($i = 0; $i < ($length - 2); $i++) {
		$key .= $charset[crypto_rand_secure(0, $max)];
	}
	return $key;
}
function get_awss3_url($url) {
	return 'https://s3.ap-south-1.amazonaws.com/gear6cdn/' . $url;
}
function encrypt_oid($OId) {
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CFB);
	$iv = generateUniqueString(16);
	return base64_encode($iv . openssl_encrypt($OId, "AES-128-CFB", "1245435645yghsda5432fvasdfgwifsyghakrftksudfhukasdhiu*(&*^#E(*@#UIHR(*@)(*))OEFOWE)(THJGDFWIEOURI(#@&OEIJH@#(*YI(FJWO)FONVE(*#YRO#NY@OOOOE*WSX&YRO(@#$*Y#@ZRL*YXRL", 0, $iv));
}
function decrypt_oid($data) {
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CFB);
	$data = base64_decode($data);
	$iv = substr($data, 0, $iv_size);
	return openssl_decrypt(substr($data, $iv_size), "AES-128-CFB", "1245435645yghsda5432fvasdfgwifsyghakrftksudfhukasdhiu*(&*^#E(*@#UIHR(*@)(*))OEFOWE)(THJGDFWIEOURI(#@&OEIJH@#(*YI(FJWO)FONVE(*#YRO#NY@OOOOE*WSX&YRO(@#$*Y#@ZRL*YXRL", 0, $iv);	
}