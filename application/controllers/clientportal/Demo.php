<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Demo extends CI_Controller {

	private $api_access_token = '';	 
	function __construct()
	{
		parent :: __construct();		
		init_admin_element();		
		$this->load->model(array('order_model','user_model','lead_model','opportunity_model','Product_model','Setting_model','history_model','Sms_setting_model','customer_model'));
	}
	

    public function pdf()
    {	
    	$pdf_html = $this->load->view('admin/order/po_pro_forma_inv_rander_pdf_view',$data,TRUE);
    	$this->load->library('m_pdf'); 
		$mpdf = new mPDF();
		$mpdf->fontdata["century-gothic"];
		$mpdf->AddPage('P', // L - landscape, P - portrait 
                '', '', '', '',
                4, // margin_left
                4, // margin right
                4, // margin top
                4, // margin bottom
                4, // margin header
                4	// margin footer
               ); 
		$mpdf->WriteHTML($pdf_html);
		$mpdf->Output();
    }

    public function pdf_html()
    {	
    	echo $pdf_html = $this->load->view('admin/order/po_pro_forma_inv_rander_pdf_view',$data,TRUE);
    }

	public function sms_test()
	{

		$this->load->model(array('Sms_forwarding_setting_model','customer_model'));		
		$sms_forwarding_setting=$this->Sms_forwarding_setting_model->GetDetails(1);
		// echo count($sms_forwarding_setting); die();
		if($sms_forwarding_setting['is_sms_send']=='Y')
		{
			$customer=$this->customer_model->GetCustomerData(2);
			$assigned_user_id=4;
			$m_mobile=get_manager_and_skip_manager_mobile_arr($assigned_user_id);
			$default_template_auto_id=$sms_forwarding_setting['default_template_id'];
			$user_id=$this->session->userdata['admin_session_data']['user_id'];
			$sms_variable_info=array("customer_id"=>2,'company_id'=>1,'lead_id'=>9,'user_id'=>$assigned_user_id);
			// --------------------
			// to sms send logic
			$sms_send_data=array();			
			$client_mobile='';
			if($sms_forwarding_setting['is_send_sms_to_client']=='Y')
			{
				//$client_mobile=$customer->mobile;
				$client_mobile='9873599019';
				$client_template_auto_id=($sms_forwarding_setting['send_sms_to_client_template_id'])?$sms_forwarding_setting['send_sms_to_client_template_id']:$default_template_auto_id;				
				$sms_send_data[]=array('mobile'=>$client_mobile,'template_auto_id'=>$client_template_auto_id);
			}	

			$relationship_manager_mobile='';
			if($sms_forwarding_setting['is_send_relationship_manager']=='Y')
			{
				// $relationship_manager_mobile=get_value("mobile","user","id=".$assigned_user_id);
				$relationship_manager_mobile='8240822703';
				$relationship_manager_template_auto_id=($sms_forwarding_setting['send_relationship_manager_template_id'])?$sms_forwarding_setting['send_relationship_manager_template_id']:$default_template_auto_id;						        	
				$sms_send_data[]=array('mobile'=>$relationship_manager_mobile,'template_auto_id'=>$relationship_manager_template_auto_id);
			}	
			
			$manager_mobile='';
			if($sms_forwarding_setting['is_send_manager']=='Y')
			{
				if($m_mobile['manager_mobile']!='')
				{		      
					// $manager_mobile=$m_mobile['manager_mobile']; 		
					$manager_mobile='8240822703';	
					$manager_template_auto_id=($sms_forwarding_setting['send_manager_template_id'])?$sms_forwarding_setting['send_manager_template_id']:$default_template_auto_id;
					$sms_send_data[]=array('mobile'=>$manager_mobile,'template_auto_id'=>$manager_template_auto_id);
				}		        	
			}

			$skip_manager_mobile='';
			if($sms_forwarding_setting['is_send_skip_manager']=='Y')
			{
				if($m_mobile['skip_manager_mobile']!='')
				{		        		
					// $skip_manager_mobile=$m_mobile['skip_manager_mobile']; 	
					$skip_manager_mobile='9804560960';	
					$skip_manager_template_auto_id=($sms_forwarding_setting['send_skip_manager_template_id'])?$sms_forwarding_setting['send_skip_manager_template_id']:$default_template_auto_id;
					$sms_send_data[]=array('mobile'=>$skip_manager_mobile,'template_auto_id'=>$skip_manager_template_auto_id);
				}		        	
			}
			// to sms send logic	
			// --------------------

			$return=sms_send($sms_send_data,$sms_variable_info);
			print_r($return);
			// print_r($sms_send_data);
			// print_r($client_mobile.'/'.$relationship_manager_mobile.'/'.$manager_mobile.'/'.$skip_manager_mobile);
			//$this->sms_send($sms_send_data,$sms_variable_info);
		}
	}
	/*
	public function sms_send($sms_send_data=array(),$sms_variable_info=array())
	{	     

		if(count($sms_send_data))
		{
			// print_r($sms_send_data); die('aa');
			// $mobileno='8240822703';
			// $message_tmp='Hello #u_user_name# LeadID has been updated by you in LMS on #l_enquiry_date# Buyers Name #b_contact_person# Contact no #b_mobile# Company Name #b_company_name# City #b_city# Title #l_lead_title# Pinnacle';
			// echo $message=rander_sms_template($message_tmp,$sms_variable_info);die();
			// $smsGatewayUrl = 'http://api.pinnacle.in/index.php/sms/send/';	
			// $sender='PINLMS';	
			// $APIKey='f91baa-e753fc-4ac84c-e6b201-9e205e'; // Need to change
			// $EntityID='1501664220000010227'; //1501664220000010227 	
			// $tempid='1507164501444665239';		
			// $textmessage=urlencode($message);		
			// $api_element=$sender.'/'.$mobileno.'/'.$textmessage.'/TXT';
			// $api_params=$api_element.'?apikey='.$APIKey.'&dltentityid='.$EntityID.'&dlttempid='.$tempid.'';
			// $smsgatewaydata=$smsGatewayUrl.$api_params; 
			// $output=file_get_contents($smsgatewaydata);
			// print_r($output);die('sent');
			
			foreach($sms_send_data AS $sms_row)
			{
				if($sms_row['mobile']!='' && $sms_row['template_auto_id']!='')
				{
					$template_info=$this->Sms_setting_model->GetTemplateDetails($sms_row['template_auto_id']);						
					if($template_info['api_sms_service_provider_id']=='1') //Pinnacle Teleservices Pvt. Ltd.
					{
						$mobileno=$sms_row['mobile'];
						$message=$this->rander_sms_template($template_info['text'],$sms_variable_info);
						$smsGatewayUrl = 'http://api.pinnacle.in/index.php/sms/send/';	
						$sender=$template_info['api_sender'];	
						$APIKey=$template_info['api_apikey'];
						$EntityID=$template_info['api_entity_id'];
						$tempid=$template_info['template_id'];
						$textmessage=urlencode($message);		
						$api_element=$sender.'/'.$mobileno.'/'.$textmessage.'/TXT';
						$api_params=$api_element.'?apikey='.$APIKey.'&dltentityid='.$EntityID.'&dlttempid='.$tempid.'';
						$smsgatewaydata=$smsGatewayUrl.$api_params;
						$output=file_get_contents($smsgatewaydata);
						print_r($output);
					}
				}
			}
		}		
	}

	
	public function rander_sms_template($message='',$sms_variable_info=array())
	{
		$get_template_variable_list=get_template_variable_list();
		// print_r($sms_variable_info); die();
		if(count($get_template_variable_list)>0 && $message!='' && count($sms_variable_info)>0)
		{
			foreach($get_template_variable_list AS $variable)
			{
				if (strpos($message, $variable['reserve_keyword']) !== false) {
					if($variable['variable_type']=='buyer_details' && $sms_variable_info['customer_id']!='')
					{
						$customer_info=$this->customer_model->sms_customer_row($sms_variable_info['customer_id'],$client_info=array());
						$replaced_str=$customer_info[trim($variable['reserve_keyword'],'#')];
						if($replaced_str)
						{
							$message=str_replace($variable['reserve_keyword'],$replaced_str,$message);
						}
					}

					if($variable['variable_type']=='company' && $sms_variable_info['company_id']!='')
					{
						$company_info=$this->Setting_model->sms_company_row($client_info=array());
						$replaced_str=$company_info[trim($variable['reserve_keyword'],'#')];
						if($replaced_str)
						{
							$message=str_replace($variable['reserve_keyword'],$replaced_str,$message);
						}
					}

					if($variable['variable_type']=='lead_details' && $sms_variable_info['lead_id']!='')
					{
						$lead_info=$this->lead_model->sms_lead_row($sms_variable_info['lead_id'],$client_info=array());
						$replaced_str=$lead_info[trim($variable['reserve_keyword'],'#')];
						if($replaced_str)
						{
							$message=str_replace($variable['reserve_keyword'],$replaced_str,$message);
						}
					}

					if($variable['variable_type']=='user_details' && $sms_variable_info['user_id']!='')
					{
						$user_info=$this->user_model->sms_user_row($sms_variable_info['user_id'],$client_info=array());							
						$replaced_str=$user_info[trim($variable['reserve_keyword'],'#')];
						if($replaced_str)
						{
							$message=str_replace($variable['reserve_keyword'],$replaced_str,$message);
						}
					}
					//echo '> '.$variable['reserve_keyword'].' - '.$variable['variable_type'].'<br>';
				}
			}
		}

		return $message;
	}
	*/
}
