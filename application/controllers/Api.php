<?php set_time_limit(0);
defined('BASEPATH') OR exit('No direct script access allowed');
class Api extends CI_Controller {
	private $api_access_token = '';	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model(array('User_model','Client_model','Autosol_model','Api_model'));
	}

	public function customer()
	{	
		$get_data=$_REQUEST;
		$token=$get_data['token'];
		$crm_key=$get_data['crm_key'];
		$start_date=$get_data['start_date'];
		$end_date=$get_data['end_date'];		
		// ---------------------------------------
		// CHECKING
		$error_count=0;
		$msg_error='';
		if($token=='' || $token==NULL)
		{
			$error_count++;
			$msg_error .='Token is missing.~';
		}
		if($crm_key=='' || $crm_key==NULL)
		{
			$error_count++;
			$msg_error .='CRM Key is missing.~';
		}
		if($start_date=='' || $start_date==NULL)
		{
			$error_count++;
			$msg_error .='Start date is missing.~';
		}
		if($end_date=='' || $end_date==NULL)
		{
			$error_count++;
			$msg_error .='End date is missing.~';
		}		
		
		// CHECKING
		// ---------------------------------------
		$client_info=$this->Client_model->get_details_by_token($token);
		if(count($client_info)){			
			$arg['client_info']=$client_info;
			$arg['api_name']='customer';
			$get_last_hit=$this->Api_model->get_last_api_hit_info($arg);			
			if(count($get_last_hit)){
				$last_hit_datetime=$get_last_hit['created_at'];
				$curr_datetime=date("Y-m-d H:i:s");			
				$seconds = strtotime($curr_datetime)-strtotime($last_hit_datetime);				
				if($seconds<300){
					$error_count++;
					$msg_error .='It is advised to hit this API once in every 5 minutes,but it seems that you have crossed this limit. Please try again after 5 minutes.~';
				}
			}
		}
		else{
			$error_count++;
			$msg_error .='Client is missing.~';
		}
		
		if($error_count==0)
		{			
			if(count($client_info))
			{					
				if($client_info->client_id=='84')
				{	
					// =========================================
					// insert to log
					$ip = $this->input->ip_address();
					$post_data=array(
						'api_name'=>'customer',
						'client_id'=>$client_info->client_id,
						'ip_address'=>$ip,
						'created_at'=>date("Y-m-d H:i:s")
					);					
					$this->Api_model->add_external_api_hit_log($post_data);
					// insert to log
					// =========================================
					$list_array=$this->Api_model->get_customer($client_info);
					header('Content-Type: application/json');					
					$json_pretty = json_encode($list_array, JSON_PRETTY_PRINT);
					echo $json_pretty;	
					
				}
				else
				{					
					$res_data=array(
						"CODE"=> 200,
						"STATUS"=> "SUCCESS",
						"MESSAGE"=> 'Restricted API',
						"TOTAL_RECORDS"=>0,
						'RESPONSE'=>'');					
					header('Content-Type: application/json');					
					$json_pretty = json_encode($res_data, JSON_PRETTY_PRINT);
					echo $json_pretty;	
				}				
			}
		}
		else
		{
			$msg_error=rtrim($msg_error,"~");
			$res_data=array(
				"CODE"=> 200,
				"STATUS"=> "SUCCESS",
				"MESSAGE"=> $msg_error,
				"TOTAL_RECORDS"=>0,
				'RESPONSE'=>'');			
			header('Content-Type: application/json');					
			$json_pretty = json_encode($res_data, JSON_PRETTY_PRINT);
			echo $json_pretty;	
		}	
	}

	public function request_for_user($token='')
	{		
		//$data = json_decode(file_get_contents('php://input'), true);
		$data = $_REQUEST;		
		// $source=trim($data['source']);
		// ---------------------------------------
		// CHECKING
		$error_count=0;
		$msg_error='';
		if($token=='' || $token==NULL)
		{
			$error_count++;
			$msg_error .='Token is missing...';
		}
		// CHECKING
		// ---------------------------------------
		if($error_count==0)
		{
			$client_info=$this->Client_model->get_details_by_token($token);

			if(count($client_info))
			{					
				if($client_info->client_id=='12')
				{	
					$synced_list=$this->Autosol_model->user_for_sync($client_info);
					// $url="http://localhost/autosol.app/capture/user";
					$url="https://myautosol.com/capture/user";
					// $url="http://lmssec.in/autosol/capture/user";
					
					$dataArray = array(
						'department_list'=>json_encode($synced_list['department_list']),
						'designation_list'=>json_encode($synced_list['designation_list']),
						'functional_area_list'=>json_encode($synced_list['functional_area_list']),
						'city_list'=>json_encode($synced_list['city_list']),
						'state_list'=>json_encode($synced_list['state_list']),
						'country_list'=>json_encode($synced_list['country_list']),
						'user_list'=>json_encode($synced_list['user_list'])
						);
						
					// print_r($dataArray); die();
					// $dataArray=array('user_list'=>json_encode($synced_list['user_list']));
					//print_r($dataArray); die('ok');
					$ch = curl_init();
					$data = http_build_query($dataArray);
					
					// $getUrl = $url."?".$data;
					$getUrl = $url;
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_URL, $getUrl);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_TIMEOUT, 80);
					curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
					$response = curl_exec($ch);
					if(!curl_errno($ch))
					{   
					    $info = curl_getinfo($ch);
					    echo'Curl sent';
					}
					else
					{    
					    $errmsg = curl_error($ch);
					    echo'fail';
					}
					curl_close($ch);
				}
				else
				{
					echo'fail';
				}				
			}
			echo'success';
		}
		else
		{
			echo $msg_error;
		}	
	}

	

	public function request_for_product($token='')
	{		
		//$data = json_decode(file_get_contents('php://input'), true);
		$data = $_REQUEST;		
		// $source=trim($data['source']);
		// ---------------------------------------
		// CHECKING
		$error_count=0;
		$msg_error='';
		if($token=='' || $token==NULL)
		{
			$error_count++;
			$msg_error .='Token is missing...';
		}
		// CHECKING
		// ---------------------------------------
		if($error_count==0)
		{
			$client_info=$this->Client_model->get_details_by_token($token);

			if(count($client_info))
			{					
				if($client_info->client_id=='12')
				{	
					$synced_list=$this->Autosol_model->product_for_sync($client_info);
					// $url="http://localhost/autosol.app/capture/product";
					$url="https://myautosol.com/capture/product";
					// $url="http://lmssec.in/autosol/capture/product";
					$dataArray = array(
					'category_list'=>json_encode($synced_list['category_list']),
					'currency_list'=>json_encode($synced_list['currency_list']),
					'unit_type_list'=>json_encode($synced_list['unit_type_list']),
					'product_list'=>json_encode($synced_list['product_list']),
					'product_image_list'=>json_encode($synced_list['product_image_list'])
					);
					// print_r($synced_list); die();
					// $dataArray=array('user_list'=>json_encode($synced_list['user_list']));
					// print_r($dataArray); die('ok');
					$ch = curl_init();
					$data = http_build_query($dataArray);
					
					// $getUrl = $url."?".$data;
					$getUrl = $url;
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_URL, $getUrl);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_TIMEOUT, 80);
					curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
					$response = curl_exec($ch);
					if(!curl_errno($ch))
					{   
					    $info = curl_getinfo($ch);
					    echo'success';
					}
					else
					{    
					    $errmsg = curl_error($ch);
					    echo'fail';
					}
					curl_close($ch);
				}
				else
				{
					echo'fail';
				}				
			}
			echo'success';
		}
		else
		{
			echo $msg_error;
		}	
	}

	public function po()
	{		
		$get_data=$_REQUEST;
		$token=$get_data['token'];
		$crm_key=$get_data['crm_key'];
		$start_date=$get_data['start_date'];
		$end_date=$get_data['end_date'];		
		// ---------------------------------------
		// CHECKING
		$error_count=0;
		$msg_error='';
		if($token=='' || $token==NULL)
		{
			$error_count++;
			$msg_error .='Token is missing.~';
		}
		if($crm_key=='' || $crm_key==NULL)
		{
			$error_count++;
			$msg_error .='CRM Key is missing.~';
		}
		if($start_date=='' || $start_date==NULL)
		{
			$error_count++;
			$msg_error .='Start date is missing.~';
		}
		if($end_date=='' || $end_date==NULL)
		{
			$error_count++;
			$msg_error .='End date is missing.~';
		}
		
		$msg_error=rtrim($msg_error,"~");
		
		// CHECKING
		// ---------------------------------------	
		
		if($error_count==0)
		{
			$client_info=$this->Client_model->get_details_by_token($token);
			
			if(count($client_info))
			{					
				if($client_info->client_id=='17')
				{	
					$synced_list=$this->Api_model->get_po($client_info);									
					
					$dataArray = array(
						"CODE"=> 200,
						"STATUS"=> "SUCCESS",
						"MESSAGE"=> "",
						"TOTAL_RECORDS"=> '',
						// 'lead_list'=>json_encode($synced_list['lead_list']),
						'lead_opportunity_list'=>$synced_list['lead_opportunity_list'],
						'quotation_list'=>$synced_list['quotation_list'],
						'lead_opportunity_wise_po_list'=>$synced_list['lead_opportunity_wise_po_list'],
						'po_pi_list'=>$synced_list['po_pi_list'],
						'po_pi_additional_charges_list'=>$synced_list['po_pi_additional_charges_list'],
						'po_pi_product'=>$synced_list['po_pi_product'],
						'po_inv'=>$synced_list['po_inv'],
						'po_inv_additional_charges'=>$synced_list['po_inv_additional_charges'],
						'po_inv_product'=>$synced_list['po_inv_product'],
						'po_payment_received_list'=>$synced_list['po_payment_received_list'],
						'po_payment_terms_list'=>$synced_list['po_payment_terms_list'],
						'po_payment_term_details_list'=>$synced_list['po_payment_term_details_list']
					);	
					header('Content-Type: application/json');					
					$json_pretty = json_encode($dataArray, JSON_PRETTY_PRINT);
					echo $json_pretty;	die();							
				}
				else
				{
					echo'fail';
				}				
			}
			echo'success';
		}
		else
		{
			echo $msg_error;
		}	
	}
}