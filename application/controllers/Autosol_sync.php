<?php set_time_limit(0);
defined('BASEPATH') OR exit('No direct script access allowed');
class Autosol_sync extends CI_Controller {
	private $api_access_token = '';	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model(array('User_model','Client_model','Autosol_model'));
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

	public function request_for_customer($token='')
	{		
		//$data = json_decode(file_get_contents('php://input'), true);
		$data = $_REQUEST;
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
					$synced_list=$this->Autosol_model->customer_for_sync($client_info);
					//$url="http://localhost/autosol.app/capture/customer";
					$url="https://myautosol.com/capture/customer";
					// $url="http://lmssec.in/autosol/capture/customer";
					$dataArray = array(
						'customer_list'=>json_encode($synced_list['customer_list'])
					);		
					//echo"<pre>";print_r($synced_list['customer_list']);echo"</pre>"; die();
					$ch = curl_init();
					$data = http_build_query($dataArray);
					
					// $getUrl = $url."?".$data;
					$getUrl = $url;
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_URL, $getUrl);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_TIMEOUT, 0);
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

	public function request_for_po($token='')
	{		
		//$post_data = json_decode(file_get_contents('php://input'), true);
		$post_data = $_REQUEST;
		$last_lowp_id=$post_data['last_lowp_id'];
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
					$synced_list=$this->Autosol_model->po_for_sync($last_lowp_id,$client_info);
					// $url="http://localhost/autosol.app/capture/po";
					$url="https://myautosol.com/capture/po";
					// $url="http://lmssec.in/autosol/capture/po";
					$dataArray = array(
						// 'lead_list'=>json_encode($synced_list['lead_list']),
						'lead_opportunity_list'=>json_encode($synced_list['lead_opportunity_list']),
						'quotation_list'=>json_encode($synced_list['quotation_list']),
						'lead_opportunity_wise_po_list'=>json_encode($synced_list['lead_opportunity_wise_po_list']),
						'po_pi_list'=>json_encode($synced_list['po_pi_list']),
						'po_pi_additional_charges_list'=>json_encode($synced_list['po_pi_additional_charges_list']),
						'po_pi_product'=>json_encode($synced_list['po_pi_product']),
						'po_inv'=>json_encode($synced_list['po_inv']),
						'po_inv_additional_charges'=>json_encode($synced_list['po_inv_additional_charges']),
						'po_inv_product'=>json_encode($synced_list['po_inv_product']),
						'po_payment_received_list'=>json_encode($synced_list['po_payment_received_list']),
						'po_payment_terms_list'=>json_encode($synced_list['po_payment_terms_list']),
						'po_payment_term_details_list'=>json_encode($synced_list['po_payment_term_details_list'])
					);		
					//print_r($synced_list); die();
					$ch = curl_init();
					$data = http_build_query($dataArray);
					
					// $getUrl = $url."?".$data;
					$getUrl = $url;
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_URL, $getUrl);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_TIMEOUT, 0);
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
}