<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Disclaimer extends CI_Controller {
	private $api_access_token = '';	
	public function __construct()
	{	
		parent::__construct();
	}
	public function index()
	{
		$data=array();
		$this->load->view('disclaimer_view',$data);
	}	
}
