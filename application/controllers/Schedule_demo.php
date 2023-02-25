<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Schedule_demo extends CI_Controller {
	private $api_access_token = '';	
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('Client_model'));
	}
	public function index()
	{
		$data=array();		
		$this->load->view('schedule_demo_view',$data);		
	}	

		
}
