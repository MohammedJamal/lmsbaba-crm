<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Adminmaster extends CI_Controller {
	private $api_access_token = '';	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model(array('Client_model'));

	}
	
	public function client_add()
	{			
		$this->load->view('adminmaster/client_add_view',$data);
	}

	public function client_add_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			ini_set('max_execution_time', 600);
			$name=$this->input->post('name');

			//$this->Db_import_model->inport();

			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["msg"]='';	        
	        echo json_encode($result);
	        exit(0);
		}
	}	
	
}
