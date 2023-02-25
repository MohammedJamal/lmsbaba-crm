<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class About_us extends CI_Controller {
	private $api_access_token = '';	
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('Client_model'));
	}
	public function index()
	{
		$data=array();		
		$this->load->view('about_us_view',$data);		
	}	

		
}
