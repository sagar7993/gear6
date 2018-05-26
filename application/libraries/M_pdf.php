<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once APPPATH.'/third_party/mpdf/mpdf.php'; 
class M_pdf {
	public $param; public $pdf; private $CI;
	public function __construct($param = '"en-GB-x","A4","","",0,0,0,0,0,0') {
		$this->param =$param;
		$this->CI =& get_instance();
		$this->pdf = new mPDF($this->param);
	}
	public function send_invoice($view, &$data, $OId) {
		$html = $this->CI->load->view($view, $data, true);
		$file_name = "invoice_" . $OId . ".pdf";
		$pdfFilePath = realpath(APPPATH . '../html/uploads/temp') . '/' . $file_name;
		$this->pdf->WriteHTML($html);
		$this->pdf->Output($pdfFilePath, "F");
		$this->send_invoice_email($data['uemail'], 'gear6.in Invoice for ' . $OId, 'rating', $data, $pdfFilePath);
		unlink($pdfFilePath);
	}
	private function send_invoice_email($to, $sub, $vname, &$data, $filepath) {
		/*$this->CI->load->library('email');
		$config['useragent'] = 'gear6.in';
		$config['protocol'] = 'smtp';
		$config['smtp_host'] = 'smtp.sendgrid.net';
		$config['smtp_user'] = 'gear6';
		$config['smtp_pass'] = 'gear6.in_bikes';
		$config['smtp_port'] = 587;
		$config['smtp_timeout'] = 30;
		$config['priority'] = 1;
		$config['mailtype'] = 'html';
		$this->CI->email->initialize($config);
		$this->CI->email->from('billing@gear6.in', 'gear6.in Billing');
		$this->CI->email->reply_to('support@gear6.in', 'gear6.in');
		$this->CI->email->to($to);
		$this->CI->email->cc('support@gear6.in'); 
		$this->CI->email->subject($sub);
		$this->CI->email->message($this->CI->load->view('emails/' . $vname, $data, TRUE));
		$this->CI->email->attach($filepath);
		$this->CI->email->send();*/
	}
}