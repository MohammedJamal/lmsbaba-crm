<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Privacy_policy extends CI_Controller {
	private $api_access_token = '';	
	public function __construct()
	{	
		parent::__construct();
	}
	public function index()
	{
		$data=array();
		$this->load->view('privacy_policy_view',$data);
	}	
}
