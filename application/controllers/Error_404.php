<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Error_404 extends CI_Controller {
	private $api_access_token = '';	
	public function __construct()
	{	
		parent::__construct();
	}
	public function index()
	{
        $this->output->set_status_header('404');
		$data=array();
		$this->load->view('Error_page',$data);
	}	
}