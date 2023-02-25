<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Terms_of_services extends CI_Controller {
	private $api_access_token = '';	
	public function __construct()
	{	
		parent::__construct();
	}
	public function index()
	{
		$data=array();
		$this->load->view('terms_of_services_view',$data);
	}	
}
