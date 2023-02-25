<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjobs extends CI_Controller {
	function __construct(){
		parent :: __construct();
		$this->load->model(array('Cronjobs_model','Customer_model','Indiamart_model','Source_model','Countries_model','Cities_model','States_model','Lead_model','History_model','User_model','Setting_model','Indiamart_setting_model','Tradeindia_setting_model','Tradeindia_model','Email_forwarding_setting_model','Client_model','Justdial_setting_model','Justdial_model','renewal_model','Sms_forwarding_setting_model','My_performance_model','Aajjo_setting_model','Aajjo_model'));
		
		$this->load->library('mail_lib');
		// echo ENVIRONMENT; die();
		
	}

	function test_im($client_id='17')
	{
		$arg=array();
		$arg['client_id']=$client_id;
		$arg['cronjobs_action']='';
		$client_db_info_list=$this->Client_model->get_all($arg);
		$return=array();
		if(count($client_db_info_list))
		{	
			foreach($client_db_info_list AS $client_info)
			{				
				$this->Cronjobs_model->initialise($client_info);
				$data_post=array(
					'text'=>'Hi',
					'created_at'=>date("Y-m-d H:i:s")
				);				
				$return=$this->Cronjobs_model->CreateCronTest($data_post);
				echo $return.'<br>';
				
			}		
		}	
		die("completed");	
	}

function test_sms($client_id='17')
{
	$arg=array();
	$arg['client_id']=$client_id;
	$arg['cronjobs_action']='indiamart';
	// print_r($arg); die();
	$client_db_info_list=$this->Client_model->get_all($arg);
	// print_r($client_db_info_list); die();
	$return=array();
	if(count($client_db_info_list))
	{	
		foreach($client_db_info_list AS $client_info)
		{
			$assigned_user_id=4;
			$lead_id=9;
			$customer_id=2;
			$sms_variable_info=array();			
			// SMS for Acknowledgement id 1
			$sms_forwarding_setting=$this->Sms_forwarding_setting_model->GetDetails(1,$client_info);
			if(count($sms_forwarding_setting)>0)
			{											
				$assigned_to_user_data=$this->User_model->get_employee_details($assigned_user_id,$client_info);
				$company=$this->Setting_model->GetCompanyData($client_info);
				$lead_info=$this->Lead_model->GetLeadData($lead_id,$client_info);
				$customer=$this->Customer_model->GetCustomerData($lead_info->customer_id,$client_info);
				$sms_variable_info=array("customer_id"=>$lead_info->customer_id,'company_id'=>1,'lead_id'=>$lead_id,'user_id'=>$assigned_user_id);
			}
			// print_r($sms_variable_info); die();
			// SMS ALERT
			if(count($sms_forwarding_setting))
			{			
				if($sms_forwarding_setting['is_sms_send']=='Y')
				{
					$m_mobile=get_manager_and_skip_manager_mobile_arr($assigned_user_id,$client_info);
					$default_template_auto_id=$sms_forwarding_setting['default_template_id'];
					$user_id=$this->session->userdata['admin_session_data']['user_id'];
					$sms_variable_info=array("customer_id"=>$lead_info->customer_id,'company_id'=>1,'lead_id'=>$lead_id,'user_id'=>$assigned_user_id);
					// --------------------
					// to sms send logic
					$sms_send_data=array();			
					$client_mobile='';
					if($sms_forwarding_setting['is_send_sms_to_client']=='Y')
					{
						$client_mobile=$customer->mobile;					
						$client_template_auto_id=($sms_forwarding_setting['send_sms_to_client_template_id'])?$sms_forwarding_setting['send_sms_to_client_template_id']:$default_template_auto_id;				
						$sms_send_data[]=array('mobile'=>$client_mobile,'template_auto_id'=>$client_template_auto_id);
					}	

					$relationship_manager_mobile='';
					if($sms_forwarding_setting['is_send_relationship_manager']=='Y')
					{
						$relationship_manager_mobile=get_value("mobile","user","id=".$assigned_user_id,$client_info);					
						$relationship_manager_template_auto_id=($sms_forwarding_setting['send_relationship_manager_template_id'])?$sms_forwarding_setting['send_relationship_manager_template_id']:$default_template_auto_id;						        	
						$sms_send_data[]=array('mobile'=>$relationship_manager_mobile,'template_auto_id'=>$relationship_manager_template_auto_id);
					}	
					
					$manager_mobile='';
					if($sms_forwarding_setting['is_send_manager']=='Y')
					{
						if($m_mobile['manager_mobile']!='')
						{		      
							$manager_mobile=$m_mobile['manager_mobile'];
							$manager_template_auto_id=($sms_forwarding_setting['send_manager_template_id'])?$sms_forwarding_setting['send_manager_template_id']:$default_template_auto_id;
							$sms_send_data[]=array('mobile'=>$manager_mobile,'template_auto_id'=>$manager_template_auto_id);
						}		        	
					}

					$skip_manager_mobile='';
					if($sms_forwarding_setting['is_send_skip_manager']=='Y')
					{
						if($m_mobile['skip_manager_mobile']!='')
						{		        		
							$skip_manager_mobile=$m_mobile['skip_manager_mobile'];
							$skip_manager_template_auto_id=($sms_forwarding_setting['send_skip_manager_template_id'])?$sms_forwarding_setting['send_skip_manager_template_id']:$default_template_auto_id;
							$sms_send_data[]=array('mobile'=>$skip_manager_mobile,'template_auto_id'=>$skip_manager_template_auto_id);
						}		        	
					}
					// to sms send logic	
					// --------------------
					$return[]=sms_send($sms_send_data,$sms_variable_info,$client_info);
				}
			}
		}
		print_r($return);
	}
	

}
function test_mail()
{	
	
	/*
	$files = array_diff(scandir(FCPATH .'application/views/admin/email_template/template'), array('.', '..'));
	foreach ($files as $file) {
	    echo $file;
	    echo'<br>';
	}
	$filename=FCPATH .'application/views/admin/email_template/template/enquiry_acknowledgement_view.php';
	if (file_exists($filename)){
	    echo "File exist.";
	}else{
	    echo "File does not exist.";
	}
	die('ok');
	

	
	$client_db_info_list=$this->Client_model->get_all();
	print_r($client_db_info_list); die();
	if(count($client_db_info_list))
	{	
		$i=1;
		foreach($client_db_info_list AS $client_info)
		{
			// ==============================================			
			$mail_data = array();        
	        $mail_data['from_mail']     = 'info@lmsbaba.com';
	        $mail_data['from_name']     = 'lmsbaba';
	        $mail_data['to_mail']       = 'arupporel123@gmail.com';
	        $mail_data['subject']       = 'test-'.$client_info->db_name;
	        $mail_data['message']       = 'testing';
	        $mail_data['attach']        = array();
	        // $r=$this->mail_lib->send_mail($mail_data,$client_info);

	        // if($r==true)
	        // {
	        // 	echo "<br>".$i.") sent for ".$client_info->db_name;
	        // }
	        // else
	        // {
	        // 	echo "<br>".$i.") not sent for ".$client_info->db_name;
	        // }

	        $get_smtp=$this->Setting_model->GetDefaultSmtpData($client_info);
	        print_r($get_smtp);
	        // print_r($client_info);
	        echo'<br>';
	        echo "<br>".$i.') '.$client_info->db_name;
	        $i++;
	        
			// ================================================
		}
	}
	*/
	echo $get_cid=$this->Client_model->get_cid_for_execute('get_lead_from_indiamart');
	
}	

function update_stage_and_status()
{		
	$arg=array();
	$arg['cronjobs_action']='stage_and_status';
	$arg['client_id']='';
	$client_db_info_list=$this->Client_model->get_all($arg);
	// print_r($client_db_info_list); die();
	if(count($client_db_info_list))
	{	
		foreach($client_db_info_list AS $client_info)
		{				
			// $this->load->model('Cronjobs_model');
			$this->Cronjobs_model->update_stage_and_status($client_info);

			// ==============================================

			$post_data=array(
					'client_id'=>$client_info->client_id,
                    'function_name'=>'update_stage_and_status',
                    'updated_db_name'=>$client_info->db_name,
                    'created_at'=>date("Y-m-d H:i:s")
                    );
			//$this->Client_model->create_cronjobs_log($post_data);

			
	        // $mail_data = array();        
	        // $mail_data['from_mail']     = 'info@lmsbaba.com';
	        // $mail_data['from_name']     = 'lmsbaba';
	        // $mail_data['to_mail']       = 'arupporel123@gmail.com';
	        // $mail_data['subject']       = 'Stage and Status update-'.$client_info->db_name;
	        // $mail_data['message']       = 'Stage and Status update for '.$client_info->db_name;
	        // $mail_data['attach']        = array();
	        // $r=$this->mail_lib->send_mail($mail_data,$client_info);
	        // if($r==true)
	        // {
	        // 	echo "<br>sent for ".$client_info->db_name;
	        // }
	        // else
	        // {
	        // 	echo '<br>not sent for '.$client_info->db_name;
	        // }
			// ================================================

			//sleep(5);	
		}
	}
	die('completed');
}

function get_lead_from_indiamart($client_id='')
{	
	if($client_id==''){
	$get_limit_offset=$this->Client_model->get_im_offset();	
	}
	else{
		die("not permission");
	}

	date_default_timezone_set('Asia/Kolkata');
	$current_datetime=date("Y-m-d");		
	
	$start_datetime = date("d-M-Y",strtotime("-1 days", strtotime($current_datetime)));
	$end_datetime=date("d-M-Y",strtotime($current_datetime));	
	$arg=array();
	$arg['client_id']=$client_id;
	$arg['cronjobs_action']='indiamart';
	// if($client_id==''){
		$arg['get_limit_offset']=$get_limit_offset;	
	// }
	// else{
	// 	$arg['get_limit_offset']='';
	// }
		
	$client_db_info_list=$this->Client_model->get_all($arg);
	if(count($client_db_info_list))
	{	
		foreach($client_db_info_list AS $client_info)
		{		
			$this->Cronjobs_model->initialise($client_info);
			$msg_log='';
			$flag=0;
			// ==================================================================================	
			$get_im_last_call=$this->Client_model->get_im_last_call($client_info->client_id);
			if($get_im_last_call)
			{
				$time1 = strtotime($get_im_last_call);
				$time2 = strtotime(date("Y-m-d H:i:s"));
				$time_interval_in_sec=($time2-$time1);
				
				
				if($time_interval_in_sec<350)
				{
					$msg_log='error_code_429#"CODE": 429. It is advised to hit this API once in every 5 minutes,but it seems that you have crossed this limit. Please try again after 5 minutes.';
					$flag=1;
				}
			}
			// ==================================================================================			
			
			if($flag==0)
			{
				$msg_log='';
				$msg_log='Cronjobs Hit|';
				// $indiamart_credentials=$this->Indiamart_setting_model->GetIndiamartCredentials($client_info);	
				$indiamart_credentials=$this->Cronjobs_model->indiamart_setting_GetIndiamartCredentials($client_info);			
				// Lead Acknowledgement id 1
				// $email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(1,$client_info);
				$email_forwarding_setting=$this->Cronjobs_model->email_forwarding_setting_GetDetails(1,$client_info);
				// SMS for Acknowledgement id 1
				// $sms_forwarding_setting=$this->Sms_forwarding_setting_model->GetDetails(1,$client_info);
				$sms_forwarding_setting=$this->Cronjobs_model->sms_forwarding_setting_GetDetails(1,$client_info);			
				
				if(count($indiamart_credentials)>0)
				{				
					// $this->Indiamart_model->truncate($client_info);
					$this->Cronjobs_model->im_truncate($client_info);
					foreach($indiamart_credentials AS $im)
					{
						$indiamart_setting_id=$im['id'];
						$is_old_version=$im['is_old_version'];					

						$GLUSR_MOBILE=$im['glusr_mobile'];
						$GLUSR_MOBILE_KEY=$im['glusr_mobile_key'];		
						
						if($is_old_version=='Y')
						{
							$url = "https://mapi.indiamart.com/wservce/enquiry/listing/GLUSR_MOBILE/".$GLUSR_MOBILE."/GLUSR_MOBILE_KEY/".$GLUSR_MOBILE_KEY."/Start_Time/".$start_datetime."/End_Time/".$end_datetime."/";	
							
							$useragent = 'PHP Client 1.0 (curl) ' . phpversion();
							$post_string="";
							$url_with_get=$url;
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $url_with_get);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
							curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
							curl_setopt($ch, CURLOPT_TIMEOUT, 30);
							$result = curl_exec($ch);
							curl_close($ch);
							$return_arr=json_decode($result);	
							
							$i=0;
							if(!isset($return_arr[0]->Error_Message))
							{
								foreach($return_arr as $row)
								{
									$post=array(
												'indiamart_setting_id'=>$indiamart_setting_id,
												'RN'=>$row->RN,
												'QUERY_ID'=>$row->QUERY_ID,
												'QTYPE'=>$row->QTYPE,
												'SENDERNAME'=>$row->SENDERNAME,
												'SENDEREMAIL'=>$row->SENDEREMAIL,
												'SUBJECT'=>$row->SUBJECT,
												'DATE_RE'=>$row->DATE_RE,
												'DATE_R'=>$row->DATE_R,
												'DATE_TIME_RE'=>$row->DATE_TIME_RE,
												'GLUSR_USR_COMPANYNAME'=>$row->GLUSR_USR_COMPANYNAME,
												'READ_STATUS'=>$row->READ_STATUS,
												'SENDER_GLUSR_USR_ID'=>$row->SENDER_GLUSR_USR_ID,
												'MOB'=>$row->MOB,
												'COUNTRY_FLAG'=>$row->COUNTRY_FLAG,
												'QUERY_MODID'=>$row->QUERY_MODID,
												'LOG_TIME'=>$row->LOG_TIME,
												'QUERY_MODREFID'=>$row->QUERY_MODREFID,
												'DIR_QUERY_MODREF_TYPE'=>$row->DIR_QUERY_MODREF_TYPE,
												'ORG_SENDER_GLUSR_ID'=>$row->ORG_SENDER_GLUSR_ID,
												'ENQ_MESSAGE'=>$row->ENQ_MESSAGE,
												'ENQ_ADDRESS'=>$row->ENQ_ADDRESS,
												'ENQ_CALL_DURATION'=>$row->ENQ_CALL_DURATION,
												'ENQ_RECEIVER_MOB'=>$row->ENQ_RECEIVER_MOB,
												'ENQ_CITY'=>$row->ENQ_CITY,
												'ENQ_STATE'=>$row->ENQ_STATE,
												'PRODUCT_NAME'=>$row->PRODUCT_NAME,
												'COUNTRY_ISO'=>$row->COUNTRY_ISO,
												'EMAIL_ALT'=>$row->EMAIL_ALT,
												'MOBILE_ALT'=>$row->MOBILE_ALT,
												'PHONE'=>$row->PHONE,
												'PHONE_ALT'=>$row->PHONE_ALT,
												'IM_MEMBER_SINCE'=>$row->IM_MEMBER_SINCE,
												'TOTAL_COUNT'=>$row->TOTAL_COUNT
												);
									// $this->Indiamart_model->insert($post,$client_info);
									$this->Cronjobs_model->im_insert($post,$client_info);
									$i++;				
								}
								$msg_log .='Old Version|Error code:200|Total Row Fetched:-'.$i;
							}
							else
							{
								$msg_log .='Old Version|Error message:-'.$return_arr[0]->Error_Message;
							}
						}
						else if($is_old_version=='N')
						{
							
							$url = "https://mapi.indiamart.com/wservce/crm/crmListing/v2/?glusr_crm_key=".$GLUSR_MOBILE_KEY."&start_time=".$start_datetime."&end_time=".$end_datetime;	
							
							$useragent = 'PHP Client 1.0 (curl) ' . phpversion();
							$post_string="";
							$url_with_get=$url;
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $url_with_get);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
							curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
							curl_setopt($ch, CURLOPT_TIMEOUT, 30);
							$result = curl_exec($ch);
							curl_close($ch);
							$return_arr=json_decode($result);	
							
							$i=0;						
							if($return_arr->CODE==200)
							{
								$TOTAL_COUNT=$return_arr->TOTAL_RECORDS;
								foreach($return_arr->RESPONSE as $row)
								{
									$DATE_RE=date('d M Y', strtotime($row->QUERY_TIME));
									$DATE_R=date('d-M-y', strtotime($row->QUERY_TIME));
									$DATE_TIME_RE=date('d-M-Y H:i:s A', strtotime($row->QUERY_TIME));								
									$subject=''.$row->QUERY_PRODUCT_NAME;
									$post=array(
											'indiamart_setting_id'=>$indiamart_setting_id,
											'RN'=>'',
											'QUERY_ID'=>$row->UNIQUE_QUERY_ID,
											'QTYPE'=>addslashes($row->QUERY_TYPE),
											'SENDERNAME'=>addslashes($row->SENDER_NAME),
											'SENDEREMAIL'=>addslashes($row->SENDER_EMAIL),
											'SUBJECT'=>addslashes($subject),
											'DATE_RE'=>$DATE_RE,
											'DATE_R'=>$DATE_R,
											'DATE_TIME_RE'=>$DATE_TIME_RE,
											'GLUSR_USR_COMPANYNAME'=>addslashes($row->SENDER_COMPANY),
											'READ_STATUS'=>'',
											'SENDER_GLUSR_USR_ID'=>'',
											'MOB'=>$row->SENDER_MOBILE,
											'COUNTRY_FLAG'=>'',
											'QUERY_MODID'=>'',
											'LOG_TIME'=>'',
											'QUERY_MODREFID'=>'',
											'DIR_QUERY_MODREF_TYPE'=>'',
											'ORG_SENDER_GLUSR_ID'=>'',
											'ENQ_MESSAGE'=>addslashes($row->QUERY_MESSAGE),
											'ENQ_ADDRESS'=>addslashes($row->SENDER_ADDRESS),
											'ENQ_CALL_DURATION'=>$row->CALL_DURATION,
											'ENQ_RECEIVER_MOB'=>$row->RECEIVER_MOBILE,
											'ENQ_CITY'=>addslashes($row->SENDER_CITY),
											'ENQ_STATE'=>addslashes($row->SENDER_STATE),
											'PRODUCT_NAME'=>addslashes($row->QUERY_PRODUCT_NAME),
											'COUNTRY_ISO'=>$row->SENDER_COUNTRY_ISO,
											'EMAIL_ALT'=>$row->SENDER_EMAIL_ALT,
											'MOBILE_ALT'=>$row->SENDER_MOBILE_ALT,
											'PHONE'=>'',
											'PHONE_ALT'=>'',
											'IM_MEMBER_SINCE'=>'',
											'TOTAL_COUNT'=>$TOTAL_COUNT
											);
									// $this->Indiamart_model->insert($post,$client_info);
									$this->Cronjobs_model->im_insert($post,$client_info);
									$i++;				
								}
								$msg_log .='New Version|Error code:200|Total Row Fetched:-'.$i;
							}
							else
							{
								$msg_log .='New Version|Error code:-'.$return_arr->CODE;
							}												
						}							
					}
					// ==========================================================================
					// UPDATE LAST IM API HIT
					$post_data=array();
					$post_data=array('last_indiamart_api_call_by_cronjobs'=>date("Y-m-d H:i:s"));
					$this->Client_model->update($post_data,$client_info->client_id);
					// UPDATE LAST IM API HIT
					// ==========================================================================
				}
				
				
				if(count($indiamart_credentials)>0)
				{			
					foreach($indiamart_credentials AS $credentials)
					{				
						// $get_im=$this->Indiamart_model->get_rows($credentials['id'],$client_info);	
						$get_im=$this->Cronjobs_model->im_get_rows($credentials['id'],$client_info);				
						if(count($get_im))
						{					
							if($credentials['assign_rule']=='1')
							{
								$indiamart_assign_to=unserialize($credentials['assign_to']);
								$assign_start=isset($credentials['assign_start'])?$credentials['assign_start']:0;
								$assign_end=(count($indiamart_assign_to)-1);
							}
							
							foreach($get_im as $im)
							{	

								$cust_email=$im['SENDEREMAIL'];
								$cust_mobile=end(explode("-", $im['MOB']));
								$im_query_id=$im['QUERY_ID'];
								//echo $im['MOB'].' / '.$cust_mobile.'<br>';
								$cust_arr=array(
									'email'=>$cust_email,
									'mobile'=>$cust_mobile,
									'im_query_id'=>$im_query_id
												);
								// $get_customer_decision=$this->Customer_model->get_decision($cust_arr,$client_info);
								$get_customer_decision=$this->Cronjobs_model->cust_get_decision($cust_arr,$client_info);

								// ------------------------
								// get message 
								$im_history_data=array('msg'=>$get_customer_decision['msg']);
								// $this->Indiamart_model->update($im_history_data,$im['id'],$client_info);
								$this->Cronjobs_model->im_update($im_history_data,$im['id'],$client_info);
								// ------------------------
								
								if($get_customer_decision['status']==TRUE)
								{
									
									$com_country_code='';
									$com_country_id='';
									if($im['COUNTRY_ISO'])
									{
										// $get_country=$this->Countries_model->get_country_by_iso($im['COUNTRY_ISO'],$client_info);
										$get_country=$this->Cronjobs_model->countries_get_country_by_iso($im['COUNTRY_ISO'],$client_info);
										if($get_country!=false)
										{
											$com_country_id=$get_country->id;
											$com_country_code=$get_country->phonecode;

										}
									}
									
									$indiamart_setting_id=$im['indiamart_setting_id'];
									$com_contact_person=($im['SENDERNAME'])?$im['SENDERNAME']:'Purchase Manager';
									$com_designation='Manager';
									$com_email=$cust_email;
									$com_alternate_email=$im['EMAIL_ALT'];
									$com_mobile_country_code=$com_country_code;
									$com_mobile=$cust_mobile;
									$com_alt_mobile_country_code=$com_country_code;
									$com_alternate_mobile=$im['MOBILE_ALT'];
									$com_landline_country_code=$com_country_code;
									$com_landline_std_code='';
									$landline_number=$im['PHONE'];
									$com_website='';
									$com_company_name=$im['GLUSR_USR_COMPANYNAME'];
									$com_address=$im['ENQ_ADDRESS'];
									$com_city_id='';
									if($im['ENQ_CITY'])
									{
										// $get_city_id=$this->Cities_model->get_city_id_by_name($im['ENQ_CITY'],$client_info);
										$get_city_id=$this->Cronjobs_model->cities_get_city_id_by_name($im['ENQ_CITY'],$client_info);
										if($get_city_id!=false)
										{
											$com_city_id=$get_city_id;
										}
									}
									$com_state_id='';	
									if($im['ENQ_STATE'])
									{
										// $get_state_id=$this->States_model->get_state_id_by_name($im['ENQ_STATE'],$client_info);
										$get_state_id=$this->Cronjobs_model->states_get_state_id_by_name($im['ENQ_STATE'],$client_info);
										if($get_state_id!=false)
										{
											$com_state_id=$get_state_id;
										}
									}				
									$com_zip='';
									$com_short_description='';
									if($im['QTYPE'])
									{
										if(strtoupper($im['QTYPE'])=='W'){
											$com_source_text='Indiamart';
										}
										else if(strtoupper($im['QTYPE'])=='B'){
											$com_source_text='Indiamart Buylead';
										}
										else if(strtoupper($im['QTYPE'])=='P'){
											$com_source_text='Indiamart Call Enquiry';
										}		
										else{
											$com_source_text='Indiamart';
										}	

										
										$com_source_text_tmp = str_replace(' ', '', strtolower($com_source_text));
										// $com_source_id=$this->Source_model->get_source_id_by_name($com_source_text_tmp,$client_info);
										$com_source_id=$this->Cronjobs_model->source_get_source_id_by_name($com_source_text_tmp,$client_info);
										if($com_source_id==0)
										{
											$post_source=array(
												'parent_id'=>0,
												'name'=>$com_source_text
												);
											// $com_source_id=$this->Source_model->add($post_source,$client_info);
											$com_source_id=$this->Cronjobs_model->source_add($post_source,$client_info);
										}
									}

									// -------------------
									// LEAD INFO
									$lead_title=$im['SUBJECT'];
									$lead_requirement='';
									if($im['PRODUCT_NAME']){
										$lead_requirement .='<B>Product Required:</B> '.$im['PRODUCT_NAME'].'<BR><BR>';
									}
									if($im['ENQ_MESSAGE']){
										$lead_requirement .=''.$im['ENQ_MESSAGE'].'<BR><BR>';
									}
									if($im['ENQ_CALL_DURATION']){
										$lead_requirement .='<B>Call Duration:</B> '.$im['ENQ_CALL_DURATION'].'<BR><BR>';
									}
									$lead_requirement .='<B>From:</B><BR>';
									if($im['SENDERNAME']){
										$lead_requirement .=''.$im['SENDERNAME'].'';
									}
									if($im['GLUSR_USR_COMPANYNAME']){
										$lead_requirement .='<BR>'.$im['GLUSR_USR_COMPANYNAME'].'<BR>';
									}

									if($im['ENQ_CITY']!='' || $im['ENQ_STATE']!='' || $im['COUNTRY_ISO']!='')
									{
										$lead_requirement .=($im['ENQ_CITY'])?$im['ENQ_CITY']:'';
										
										$lead_requirement .=($im['ENQ_STATE'])?', ':'';
										$lead_requirement .=($im['ENQ_STATE'])?$im['ENQ_STATE']:'';
									
										$lead_requirement .=($im['COUNTRY_ISO'])?', ':'';
										$lead_requirement .=($im['COUNTRY_ISO'])?$get_country->name:'';
									}

									if($im['SENDEREMAIL']!='' || $im['EMAIL_ALT']!='')
									{

										$lead_requirement .='<br>Email: ';
										if($im['SENDEREMAIL']){
											$lead_requirement .=''.$im['SENDEREMAIL'].'';
											$lead_requirement .=($im['EMAIL_ALT'])?', ':'';
										}										
										$lead_requirement .=($im['EMAIL_ALT'])?$im['EMAIL_ALT']:'';
										
										if($im['SENDEREMAIL']!='' || $im['EMAIL_ALT']!=''){
											$lead_requirement .='<br>';
										}
									}

									if($im['MOB']!='' || $im['MOBILE_ALT']!=''){

										$lead_requirement .='Mobile: ';
										$lead_requirement .=''.$im['MOB'].'';

										$lead_requirement .=($im['MOBILE_ALT'])?', ':'';
										$lead_requirement .=($im['MOBILE_ALT'])?$im['MOBILE_ALT']:'';
										if($im['MOB']!='' || $im['MOBILE_ALT']!=''){
											$lead_requirement .='<br>';
										}
									}

									if($im['PHONE']!='' || $im['PHONE_ALT']!='')
									{
										$lead_requirement .='Phone: ';
										$lead_requirement .=($im['PHONE'])?$im['PHONE']:'';
										$lead_requirement .=($im['PHONE_ALT'])?', ':'';
										$lead_requirement .=($im['PHONE_ALT'])?$im['PHONE_ALT']:'';
										$lead_requirement .='<br>';						
									}

									if($im['DATE_TIME_RE']!='')
									{
										$lead_requirement .='Date and Time: ';			
										$lead_requirement .=$im['DATE_TIME_RE'];
									}
									// LEAD INFO
									// -------------------

									// -------------------
									// RULE WISE USER
									if($credentials['assign_rule']=='1')
									{
										$assigned_user_id=isset($indiamart_assign_to[$assign_start])?$indiamart_assign_to[$assign_start]:$indiamart_assign_to[0];
									}
									else if($credentials['assign_rule']==5)// KEYWORD 
									{			
										$title_tmp=str_replace(' ', '', strtolower($lead_title));
										$lead_requirement_tmp=str_replace(' ', '', strtolower($lead_requirement));				
										$search_keyword=$title_tmp.''.$lead_requirement_tmp;								
										$arr=array();
										$arr['assign_rule']=$credentials['assign_rule'];
										$arr['indiamart_setting_id']=$im['indiamart_setting_id'];
										$arr['search_keyword']=$search_keyword;	
										// $assigned_user_id=$this->Indiamart_setting_model->get_keyword_rule5_wise_assigned_user_id($arr,$client_info);
										$assigned_user_id=$this->Cronjobs_model->indiamart_setting_get_keyword_rule5_wise_assigned_user_id($arr,$client_info);
									}
									else
									{

										if($credentials['assign_rule']==2)
										{
											$search_keyword=$com_country_id;
										}
										else if($credentials['assign_rule']==3)
										{
											$search_keyword=$com_state_id;
										}
										else if($credentials['assign_rule']==4)
										{
											$search_keyword=$com_city_id;
										}
										$arr=array();
										$arr['assign_rule']=$credentials['assign_rule'];
										$arr['indiamart_setting_id']=$im['indiamart_setting_id'];
										$arr['search_keyword']=$search_keyword;	
										// $assigned_user_id=$this->Indiamart_setting_model->get_rule_wise_assigned_user_id($arr,$client_info);
										$assigned_user_id=$this->Cronjobs_model->indiamart_setting_get_rule_wise_assigned_user_id($arr,$client_info);
									}				
									// RULE WISE USER			
									// ---------------------


									// ===================
									// CUSTOMER DETAILS
									$com_contact_person_tmp='';		
									$is_blacklist='N';
									if($get_customer_decision['msg']=='no_customer_exist')
									{

										$company_post_data=array(
											'assigned_user_id'=>$assigned_user_id,
											'contact_person'=>$com_contact_person,
											'designation'=>$com_designation,
											'email'=>$com_email,
											'alt_email'=>$com_alternate_email,
											'mobile_country_code'=>$com_mobile_country_code,
											'mobile'=>$com_mobile,
											'alt_mobile_country_code'=>$com_alt_mobile_country_code,
											'alt_mobile'=>$com_alternate_mobile,
											'landline_country_code'=>$com_landline_country_code,
											'landline_std_code'=>$com_landline_std_code,
											'landline_number'=>$landline_number,
											'office_country_code'=>$com_country_code,
											'website'=>$com_website,
											'company_name'=>$com_company_name,
											'address'=>$com_address,
											'city'=>$com_city_id,
											'state'=>$com_state_id,
											'country_id'=>$com_country_id,
											'zip'=>$com_zip,
											'create_date'=>date("Y-m-d H:i:s"),
											'short_description'=>$com_short_description,
											'source_id'=>$com_source_id,
											'modify_date'=>date('Y-m-d'),
											'status'=>'1'
										);
																		
										// $com_company_id=$this->Cronjobs_model->CreateCustomer($company_post_data,$client_info);
										$com_company_id=$this->Cronjobs_model->CreateCustomer($company_post_data,$client_info);
										
										if($credentials['assign_rule']=='1')
										{
											$assign_start++;
											if($assign_start>$assign_end)
											{
												$assign_start=0;
											}

											// ---------------------------------
											// update to setting table
											$tmpdata=array('assign_start'=>$assign_start);
											// $this->Indiamart_setting_model->EditIndiamartCredentials($tmpdata,$indiamart_setting_id,$client_info);
											$this->Cronjobs_model->indiamart_setting_EditIndiamartCredentials($tmpdata,$indiamart_setting_id,$client_info);
											// update to setting table
											// ----------------------------------
										}
										$com_contact_person_tmp=$com_contact_person;
										
									}
									else if($get_customer_decision['msg']=='one_customer_exist')
									{
										$com_company_id=$get_customer_decision['customer_id'];
										// $get_existing_com=$this->Customer_model->get_company_detail($com_company_id,$client_info);
										$get_existing_com=$this->Cronjobs_model->cust_get_company_detail($com_company_id,$client_info);
										$com_source_id=$get_existing_com['source_id'];
										$assigned_user_id=$get_existing_com['assigned_user_id'];
										$is_blacklist=($get_existing_com['is_blacklist'])?$get_existing_com['is_blacklist']:'N';
										$company_post_data=array(
											'contact_person'=>($get_existing_com['contact_person'])?$get_existing_com['contact_person']:$com_contact_person,
											'designation'=>($get_existing_com['designation'])?$get_existing_com['designation']:$com_designation,
											'alt_email'=>($get_existing_com['alt_email'])?$get_existing_com['alt_email']:$com_alternate_email,
											'alt_mobile_country_code'=>($get_existing_com['alt_mobile_country_code'])?$get_existing_com['alt_mobile_country_code']:$com_alt_mobile_country_code,
											'alt_mobile'=>($get_existing_com['alt_mobile'])?$get_existing_com['alt_mobile']:$com_alternate_mobile,
											'landline_country_code'=>($get_existing_com['landline_country_code'])?$get_existing_com['landline_country_code']:$com_landline_country_code,
											'landline_std_code'=>($get_existing_com['landline_std_code'])?$get_existing_com['landline_std_code']:$com_landline_std_code,
											'landline_number'=>($get_existing_com['landline_number'])?$get_existing_com['landline_number']:$landline_number,
											'website'=>($get_existing_com['website'])?$get_existing_com['website']:$com_website,
											'company_name'=>($get_existing_com['company_name'])?$get_existing_com['company_name']:$com_company_name,
											'address'=>($get_existing_com['address'])?$get_existing_com['address']:$com_address,
											'city'=>($get_existing_com['city']>0)?$get_existing_com['city']:$com_city_id,
											'state'=>($get_existing_com['state']>0)?$get_existing_com['state']:$com_state_id,
											'country_id'=>($get_existing_com['country_id']>0)?$get_existing_com['country_id']:$com_country_id,
											'zip'=>($get_existing_com['zip']>0)?$get_existing_com['zip']:$com_zip,
											'short_description'=>($get_existing_com['short_description'])?$get_existing_com['short_description']:$com_short_description,
											'source_id'=>($get_existing_com['source_id']>0)?$get_existing_com['source_id']:$com_source_id,
											'modify_date'=>date('Y-m-d'),
											'status'=>'1'
										);
										
										$this->Cronjobs_model->UpdateCustomer($company_post_data,$com_company_id,$client_info);
										
										$com_contact_person_tmp=($get_existing_com['contact_person'])?$get_existing_com['contact_person']:$com_contact_person;								
									}
									

									if($is_blacklist=='N')
									{									

										$lead_enq_date=date_display_format_to_db_format($im['DATE_RE']);
										
										$lead_post_data=array(
											'title'=>$lead_title,
											'customer_id'=>$com_company_id,
											'source_id'=>$com_source_id,
											'assigned_user_id'=>$assigned_user_id,
											'buying_requirement'=>$lead_requirement,
											'enquiry_date'=>$lead_enq_date,
											'followup_date'=>date('Y-m-d'),
											'create_date'=>date('Y-m-d'),
											'modify_date'=>date('Y-m-d'),
											'assigned_date'=>date('Y-m-d'),
											'status'=>'1',
											'current_stage_id'=>'1',
											'current_stage'=>'PENDING',
											'current_stage_wise_msg'=>'',
											'current_status_id'=>'1',
											'current_status'=>'WARM',
											'im_query_id'=>$im_query_id,
											'im_setting_id'=>$indiamart_setting_id
										);
										
										// $lead_id=$this->Lead_model->CreateLead($lead_post_data,$client_info);
										$lead_id=$this->Cronjobs_model->lead_CreateLead($lead_post_data,$client_info);
										
										if($lead_id)
										{
											
											// ---------------------------
											// PRODUCT TAGGED
											// if($im['PRODUCT_NAME'])
											// {
											// 	$lead_p_data=array(
											// 		'lead_id'=>$lead_id,
											// 		'name'=>$im['PRODUCT_NAME']
											// 	);
											// 	$this->Lead_model->CreateLeadWiseProductTag($lead_p_data,$client_info);
											// }
											
											// PRODUCT TAGGED
											// ---------------------------
											


											// Insert Stage Log
											$stage_post_data=array(
													'lead_id'=>$lead_id,
													'stage_id'=>'1',
													'stage'=>'PENDING',
													'stage_wise_msg'=>'',
													'create_datetime'=>date("Y-m-d H:i:s")
												);
											// $this->Lead_model->CreateLeadStageLog($stage_post_data,$client_info);
											$this->Cronjobs_model->lead_CreateLeadStageLog($stage_post_data,$client_info);
											
											// Insert Status Log
											$status_post_data=array(
													'lead_id'=>$lead_id,
													'status_id'=>'2',
													'status'=>'WARM',
													'create_datetime'=>date("Y-m-d H:i:s")
												);
											// $this->Lead_model->CreateLeadStatusLog($status_post_data,$client_info);
											$this->Cronjobs_model->lead_CreateLeadStatusLog($status_post_data,$client_info);
											

											$attach_filename='';
											$assigned_by_user_id=1;
											// -------------------------------------------------
											// ASSIGN LEAD LOG TABLE
											$post_data=array(
											'lead_id'=>$lead_id,
											'assigned_to_user_id'=>$assigned_user_id,
											'assigned_by_user_id'=>$assigned_by_user_id,
											'is_accepted'=>'Y',
											'assigned_datetime'=>date("Y-m-d H:i:s")
														);			
											// $this->Lead_model->create_lead_assigned_user_log($post_data,$client_info);
											$this->Cronjobs_model->lead_create_lead_assigned_user_log($post_data,$client_info);
											
										

											$update_by=1;				
											$date=date("Y-m-d H:i:s");				
											$ip_addr=$_SERVER['REMOTE_ADDR'];				
											$message="A new lead has been created as &quot;".$lead_title."&quot;";
											$comment_title=NEW_LEAD_CREATE_MANUAL;
											$historydata=array(
											'title'=>$comment_title,
											'lead_id'=>$lead_id,
											'comment'=>addslashes($message),
											'attach_file'=>$attach_filename,
											'create_date'=>$date,
											'user_id'=>$update_by,
											'ip_address'=>$ip_addr
															);
											// $this->History_model->CreateHistory($historydata,$client_info);
											$this->Cronjobs_model->history_CreateHistory($historydata,$client_info);

											
											if($email_forwarding_setting['is_mail_send']=='Y' || count($sms_forwarding_setting)>0)
											{											
												// $assigned_to_user_data=$this->User_model->get_employee_details($assigned_user_id,$client_info);
												$assigned_to_user_data=$this->Cronjobs_model->user_get_employee_details($assigned_user_id,$client_info);
												// $company=$this->Setting_model->GetCompanyData($client_info);
												$company=$this->Cronjobs_model->setting_GetCompanyData($client_info);
												// $lead_info=$this->Lead_model->GetLeadData($lead_id,$client_info);
												$lead_info=$this->Cronjobs_model->lead_GetLeadData($lead_id,$client_info);
												// $customer=$this->Customer_model->GetCustomerData($lead_info->customer_id,$client_info);
												$customer=$this->Cronjobs_model->cust_GetCustomerData($lead_info->customer_id,$client_info);
											}
											
											// MAIL ALERT
											
											if($email_forwarding_setting['is_mail_send']=='Y')
											{
												// ============================
												// Mail Acknowledgement & 
												// account manager update
												// START

												// TO CLIENT				
												$e_data=array();											
												// $assigned_to_user_data=$this->User_model->get_employee_details($assigned_user_id,$client_info);
												// $company=$this->Setting_model->GetCompanyData($client_info);
												// $lead_info=$this->Lead_model->GetLeadData($lead_id,$client_info);
												// $customer=$this->Customer_model->GetCustomerData($lead_info->customer_id,$client_info);


												$e_data['company']=$company;
												$e_data['assigned_to_user']=$assigned_to_user_data;
												$e_data['customer']=$customer;
												$e_data['lead_info']=$lead_info;
												$e_data['get_company_name_initials']=get_company_name_initials($client_info);
												$e_data['rander_company_address']=rander_company_address_cronjobs('email_template',$client_info);
												$e_data['client_info']=$client_info;
												$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_cronjobs_view', $e_data, true);
												
												$m_email=get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);

												// --------------------
												// to mail assign logic
												$to_mail_assign='';
												$to_mail='';
												if($email_forwarding_setting['is_send_mail_to_client']=='Y')
												{
													$to_mail=$customer->email;
													$to_mail_assign='client';
												}
												else
												{
													if($m_email['manager_email']!='' && $email_forwarding_setting['is_send_manager']=='Y')
													{
														$to_mail=$m_email['manager_email'];
														$to_mail_assign='manager';
													}
													else
													{
														if($m_email['skip_manager_email']!='' && $email_forwarding_setting['is_send_skip_manager']=='Y')
														{
															$to_mail=$m_email['skip_manager_email'];
															$to_mail_assign='skip_manager';
														}
													}
												}
												// to mail assign logic
												// --------------------

												$cc_mail_arr=array();
												$self_cc_mail=get_value("email","user","id=".$assigned_user_id,$client_info);
												$update_by_name=get_value("name","user","id=".$assigned_user_id,$client_info);
												// --------------------
												// cc mail assign logic
												if($email_forwarding_setting['is_send_relationship_manager']=='Y')
												{
													if($to_mail=='')
													{
														$to_mail=$self_cc_mail;
													}
													else
													{
														array_push($cc_mail_arr, $self_cc_mail);
													}		        	
												}

												if($email_forwarding_setting['is_send_manager']=='Y')
												{
													if($m_email['manager_email']!='' && $to_mail_assign!='manager')
													{		        		
														array_push($cc_mail_arr, $m_email['manager_email']);
													}		        	
												}

												if($email_forwarding_setting['is_send_skip_manager']=='Y')
												{
													if($m_email['skip_manager_email']!='' && $to_mail_assign!='skip_manager')
													{		        		
														array_push($cc_mail_arr, $m_email['skip_manager_email']);
													}		        	
												}
												$cc_mail='';
												$cc_mail=implode(",", $cc_mail_arr);
												// cc mail assign logic
												// --------------------
														
												if($to_mail)
												{
													$com_company_name_tmp=$company['name'];
													if($im['PRODUCT_NAME']!=NULL && $com_contact_person_tmp!='')
													{
														$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' for '.$im['PRODUCT_NAME'].' has been received - '.$com_company_name_tmp; 
													}
													else if($im['PRODUCT_NAME']!=NULL && $com_contact_person_tmp=='')
													{
														$mail_subject='Enquiry # '.$lead_id.' for '.$im['PRODUCT_NAME'].' has been received - '.$com_company_name_tmp; 
													}
													else if($im['PRODUCT_NAME']==NULL && $com_contact_person_tmp!='')
													{
														$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
													}
													else if($im['PRODUCT_NAME']==NULL && $com_contact_person_tmp=='')
													{
														$mail_subject='Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
													}
													else
													{
														$mail_subject='Your Enquiry # '.$lead_id.' has been acknowledged';
													}

													
													//$this->load->library('mail_lib');
													$m_d = array();
													// $m_d['from_mail']     = $company['email1'];
													// $m_d['from_name']     = $company['name'];
													$m_d['from_mail']     = $self_cc_mail;
													$m_d['from_name']     = $update_by_name;
													$m_d['to_mail']       = $to_mail;
													$m_d['cc_mail']       = $cc_mail;
													$m_d['subject']       = $mail_subject;
													$m_d['message']       = $template_str;
													$m_d['attach']        = array();
													$mail_return = $this->mail_lib->send_mail($m_d,$client_info);
												}
												
												// END
												// =============================
											}
											
											// SMS ALERT
											
											if(count($sms_forwarding_setting))
											{			
												if($sms_forwarding_setting['is_sms_send']=='Y')
												{
													$m_mobile=get_manager_and_skip_manager_mobile_arr($assigned_user_id,$client_info);
													$default_template_auto_id=$sms_forwarding_setting['default_template_id'];
													$user_id=$this->session->userdata['admin_session_data']['user_id'];
													$sms_variable_info=array("customer_id"=>$lead_info->customer_id,'company_id'=>1,'lead_id'=>$lead_id,'user_id'=>$assigned_user_id);
													// --------------------
													// to sms send logic
													$sms_send_data=array();			
													$client_mobile='';
													if($sms_forwarding_setting['is_send_sms_to_client']=='Y')
													{
														$client_mobile=$customer->mobile;					
														$client_template_auto_id=($sms_forwarding_setting['send_sms_to_client_template_id'])?$sms_forwarding_setting['send_sms_to_client_template_id']:$default_template_auto_id;				
														$sms_send_data[]=array('mobile'=>$client_mobile,'template_auto_id'=>$client_template_auto_id);
													}	

													$relationship_manager_mobile='';
													if($sms_forwarding_setting['is_send_relationship_manager']=='Y')
													{
														$relationship_manager_mobile=get_value("mobile","user","id=".$assigned_user_id,$client_info);					
														$relationship_manager_template_auto_id=($sms_forwarding_setting['send_relationship_manager_template_id'])?$sms_forwarding_setting['send_relationship_manager_template_id']:$default_template_auto_id;						        	
														$sms_send_data[]=array('mobile'=>$relationship_manager_mobile,'template_auto_id'=>$relationship_manager_template_auto_id);
													}	
													
													$manager_mobile='';
													if($sms_forwarding_setting['is_send_manager']=='Y')
													{
														if($m_mobile['manager_mobile']!='')
														{		      
															$manager_mobile=$m_mobile['manager_mobile'];
															$manager_template_auto_id=($sms_forwarding_setting['send_manager_template_id'])?$sms_forwarding_setting['send_manager_template_id']:$default_template_auto_id;
															$sms_send_data[]=array('mobile'=>$manager_mobile,'template_auto_id'=>$manager_template_auto_id);
														}		        	
													}

													$skip_manager_mobile='';
													if($sms_forwarding_setting['is_send_skip_manager']=='Y')
													{
														if($m_mobile['skip_manager_mobile']!='')
														{		        		
															$skip_manager_mobile=$m_mobile['skip_manager_mobile'];
															$skip_manager_template_auto_id=($sms_forwarding_setting['send_skip_manager_template_id'])?$sms_forwarding_setting['send_skip_manager_template_id']:$default_template_auto_id;
															$sms_send_data[]=array('mobile'=>$skip_manager_mobile,'template_auto_id'=>$skip_manager_template_auto_id);
														}		        	
													}
													// to sms send logic	
													// --------------------
													$return=sms_send($sms_send_data,$sms_variable_info,$client_info);
												}
											}	
																			
										}
									}
								}
								else
								{

								}
							}			
						}
					}
				}
				
				if(count($indiamart_credentials)>0)
				{
					$msg="Process completed to fetch leads from indiamart.";
					$status='success';
				}
				else
				{
					$msg="imdiamart credentials not available";
					$status='fail';
				}
				echo $status.'#'.$msg;			
				// ==========================================
								
			}	
			
			// ===============================================================
			$updated_db_name=$client_info->db_username.'/'.$client_info->db_password.'/'.$client_info->db_name;
			$post_data=array(
				'client_id'=>$client_info->client_id,
				'function_name'=>'get_lead_from_indiamart',
				'updated_db_name'=>$updated_db_name,
				'msg'=>$msg_log,
				'created_at'=>date("Y-m-d H:i:s")
				);		
			$this->Client_model->create_cronjobs_log($post_data);				
			// ===============================================================					
		}		
		// ================================================
		$offset_tmp=($get_limit_offset=='0')?'100':'0';
		$p_data=array('text'=>$offset_tmp);
		$this->Client_model->update_im_offset($p_data,1);
		// ===============================================		
	}	
	//die('completed');	
}	


function get_lead_from_indiamart_by_manual($client_id='')
{	
	if($client_id=='')
	{
		echo 'fail#Parameter is missing.';die();
	}

	// ==================================================================================	
	$get_im_last_call=$this->Client_model->get_im_last_call($client_id);
	if($get_im_last_call)
	{
		$time1 = strtotime($get_im_last_call);
		$time2 = strtotime(date("Y-m-d H:i:s"));
		$time_interval_in_sec=($time2-$time1);
		if($time_interval_in_sec<350)
		{
			echo 'error_code_429#"CODE": 429. It is advised to hit this API once in every 5 minutes,but it seems that you have crossed this limit. Please try again after 5 minutes.';die();
		}
	}
	// ==================================================================================
	

	date_default_timezone_set('Asia/Kolkata');
	$current_datetime=date("Y-m-d");		
	
	$start_datetime = date("d-M-Y",strtotime("-1 days", strtotime($current_datetime)));
	$end_datetime=date("d-M-Y",strtotime($current_datetime));	
	$arg=array();
	$arg['client_id']=$client_id;
	$arg['cronjobs_action']='indiamart';	
	$arg['get_limit_offset']='';		
	$client_db_info_list=$this->Client_model->get_all($arg);
	if(count($client_db_info_list))
	{	
		foreach($client_db_info_list AS $client_info)
		{		
			$this->Cronjobs_model->initialise($client_info);
			$msg_log='';
			$flag=0;
			// ==================================================================================	
			$get_im_last_call=$this->Client_model->get_im_last_call($client_info->client_id);
			if($get_im_last_call)
			{
				$time1 = strtotime($get_im_last_call);
				$time2 = strtotime(date("Y-m-d H:i:s"));
				$time_interval_in_sec=($time2-$time1);
				
				
				if($time_interval_in_sec<350)
				{
					$msg_log='error_code_429#"CODE": 429. It is advised to hit this API once in every 5 minutes,but it seems that you have crossed this limit. Please try again after 5 minutes.';
					$flag=1;
				}
			}
			// ==================================================================================			
			
			if($flag==0)
			{
				$msg_log='';
				$msg_log='Cronjobs Hit|';
				// $indiamart_credentials=$this->Indiamart_setting_model->GetIndiamartCredentials($client_info);	
				$indiamart_credentials=$this->Cronjobs_model->indiamart_setting_GetIndiamartCredentials($client_info);			
				// Lead Acknowledgement id 1
				// $email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(1,$client_info);
				$email_forwarding_setting=$this->Cronjobs_model->email_forwarding_setting_GetDetails(1,$client_info);
				// SMS for Acknowledgement id 1
				// $sms_forwarding_setting=$this->Sms_forwarding_setting_model->GetDetails(1,$client_info);
				$sms_forwarding_setting=$this->Cronjobs_model->sms_forwarding_setting_GetDetails(1,$client_info);			
				
				if(count($indiamart_credentials)>0)
				{				
					// $this->Indiamart_model->truncate($client_info);
					$this->Cronjobs_model->im_truncate($client_info);
					foreach($indiamart_credentials AS $im)
					{
						$indiamart_setting_id=$im['id'];
						$is_old_version=$im['is_old_version'];					

						$GLUSR_MOBILE=$im['glusr_mobile'];
						$GLUSR_MOBILE_KEY=$im['glusr_mobile_key'];		
						
						if($is_old_version=='Y')
						{
							$url = "https://mapi.indiamart.com/wservce/enquiry/listing/GLUSR_MOBILE/".$GLUSR_MOBILE."/GLUSR_MOBILE_KEY/".$GLUSR_MOBILE_KEY."/Start_Time/".$start_datetime."/End_Time/".$end_datetime."/";	
							
							$useragent = 'PHP Client 1.0 (curl) ' . phpversion();
							$post_string="";
							$url_with_get=$url;
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $url_with_get);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
							curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
							curl_setopt($ch, CURLOPT_TIMEOUT, 30);
							$result = curl_exec($ch);
							curl_close($ch);
							$return_arr=json_decode($result);	
							
							$i=0;
							if(!isset($return_arr[0]->Error_Message))
							{
								foreach($return_arr as $row)
								{
									$post=array(
												'indiamart_setting_id'=>$indiamart_setting_id,
												'RN'=>$row->RN,
												'QUERY_ID'=>$row->QUERY_ID,
												'QTYPE'=>$row->QTYPE,
												'SENDERNAME'=>$row->SENDERNAME,
												'SENDEREMAIL'=>$row->SENDEREMAIL,
												'SUBJECT'=>$row->SUBJECT,
												'DATE_RE'=>$row->DATE_RE,
												'DATE_R'=>$row->DATE_R,
												'DATE_TIME_RE'=>$row->DATE_TIME_RE,
												'GLUSR_USR_COMPANYNAME'=>$row->GLUSR_USR_COMPANYNAME,
												'READ_STATUS'=>$row->READ_STATUS,
												'SENDER_GLUSR_USR_ID'=>$row->SENDER_GLUSR_USR_ID,
												'MOB'=>$row->MOB,
												'COUNTRY_FLAG'=>$row->COUNTRY_FLAG,
												'QUERY_MODID'=>$row->QUERY_MODID,
												'LOG_TIME'=>$row->LOG_TIME,
												'QUERY_MODREFID'=>$row->QUERY_MODREFID,
												'DIR_QUERY_MODREF_TYPE'=>$row->DIR_QUERY_MODREF_TYPE,
												'ORG_SENDER_GLUSR_ID'=>$row->ORG_SENDER_GLUSR_ID,
												'ENQ_MESSAGE'=>$row->ENQ_MESSAGE,
												'ENQ_ADDRESS'=>$row->ENQ_ADDRESS,
												'ENQ_CALL_DURATION'=>$row->ENQ_CALL_DURATION,
												'ENQ_RECEIVER_MOB'=>$row->ENQ_RECEIVER_MOB,
												'ENQ_CITY'=>$row->ENQ_CITY,
												'ENQ_STATE'=>$row->ENQ_STATE,
												'PRODUCT_NAME'=>$row->PRODUCT_NAME,
												'COUNTRY_ISO'=>$row->COUNTRY_ISO,
												'EMAIL_ALT'=>$row->EMAIL_ALT,
												'MOBILE_ALT'=>$row->MOBILE_ALT,
												'PHONE'=>$row->PHONE,
												'PHONE_ALT'=>$row->PHONE_ALT,
												'IM_MEMBER_SINCE'=>$row->IM_MEMBER_SINCE,
												'TOTAL_COUNT'=>$row->TOTAL_COUNT
												);
									// $this->Indiamart_model->insert($post,$client_info);
									$this->Cronjobs_model->im_insert($post,$client_info);
									$i++;				
								}
								$msg_log .='Old Version|Error code:200|Total Row Fetched:-'.$i;
							}
							else
							{
								$msg_log .='Old Version|Error message:-'.$return_arr[0]->Error_Message;
							}
						}
						else if($is_old_version=='N')
						{
							
							$url = "https://mapi.indiamart.com/wservce/crm/crmListing/v2/?glusr_crm_key=".$GLUSR_MOBILE_KEY."&start_time=".$start_datetime."&end_time=".$end_datetime;	
							
							$useragent = 'PHP Client 1.0 (curl) ' . phpversion();
							$post_string="";
							$url_with_get=$url;
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $url_with_get);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
							curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
							curl_setopt($ch, CURLOPT_TIMEOUT, 30);
							$result = curl_exec($ch);
							curl_close($ch);
							$return_arr=json_decode($result);	
							
							$i=0;						
							if($return_arr->CODE==200)
							{
								$TOTAL_COUNT=$return_arr->TOTAL_RECORDS;
								foreach($return_arr->RESPONSE as $row)
								{
									$DATE_RE=date('d M Y', strtotime($row->QUERY_TIME));
									$DATE_R=date('d-M-y', strtotime($row->QUERY_TIME));
									$DATE_TIME_RE=date('d-M-Y H:i:s A', strtotime($row->QUERY_TIME));								
									$subject=''.$row->QUERY_PRODUCT_NAME;
									$post=array(
											'indiamart_setting_id'=>$indiamart_setting_id,
											'RN'=>'',
											'QUERY_ID'=>$row->UNIQUE_QUERY_ID,
											'QTYPE'=>addslashes($row->QUERY_TYPE),
											'SENDERNAME'=>addslashes($row->SENDER_NAME),
											'SENDEREMAIL'=>addslashes($row->SENDER_EMAIL),
											'SUBJECT'=>addslashes($subject),
											'DATE_RE'=>$DATE_RE,
											'DATE_R'=>$DATE_R,
											'DATE_TIME_RE'=>$DATE_TIME_RE,
											'GLUSR_USR_COMPANYNAME'=>addslashes($row->SENDER_COMPANY),
											'READ_STATUS'=>'',
											'SENDER_GLUSR_USR_ID'=>'',
											'MOB'=>$row->SENDER_MOBILE,
											'COUNTRY_FLAG'=>'',
											'QUERY_MODID'=>'',
											'LOG_TIME'=>'',
											'QUERY_MODREFID'=>'',
											'DIR_QUERY_MODREF_TYPE'=>'',
											'ORG_SENDER_GLUSR_ID'=>'',
											'ENQ_MESSAGE'=>addslashes($row->QUERY_MESSAGE),
											'ENQ_ADDRESS'=>addslashes($row->SENDER_ADDRESS),
											'ENQ_CALL_DURATION'=>$row->CALL_DURATION,
											'ENQ_RECEIVER_MOB'=>$row->RECEIVER_MOBILE,
											'ENQ_CITY'=>addslashes($row->SENDER_CITY),
											'ENQ_STATE'=>addslashes($row->SENDER_STATE),
											'PRODUCT_NAME'=>addslashes($row->QUERY_PRODUCT_NAME),
											'COUNTRY_ISO'=>$row->SENDER_COUNTRY_ISO,
											'EMAIL_ALT'=>$row->SENDER_EMAIL_ALT,
											'MOBILE_ALT'=>$row->SENDER_MOBILE_ALT,
											'PHONE'=>'',
											'PHONE_ALT'=>'',
											'IM_MEMBER_SINCE'=>'',
											'TOTAL_COUNT'=>$TOTAL_COUNT
											);
									// $this->Indiamart_model->insert($post,$client_info);
									$this->Cronjobs_model->im_insert($post,$client_info);
									$i++;				
								}
								$msg_log .='New Version|Error code:200|Total Row Fetched:-'.$i;
							}
							else
							{
								$msg_log .='New Version|Error code:-'.$return_arr->CODE;
							}												
						}							
					}
					// ==========================================================================
					// UPDATE LAST IM API HIT
					$post_data=array();
					$post_data=array('last_indiamart_api_call_by_cronjobs'=>date("Y-m-d H:i:s"));
					$this->Client_model->update($post_data,$client_info->client_id);
					// UPDATE LAST IM API HIT
					// ==========================================================================
				}
				
				
				if(count($indiamart_credentials)>0)
				{			
					foreach($indiamart_credentials AS $credentials)
					{				
						// $get_im=$this->Indiamart_model->get_rows($credentials['id'],$client_info);	
						$get_im=$this->Cronjobs_model->im_get_rows($credentials['id'],$client_info);				
						if(count($get_im))
						{					
							if($credentials['assign_rule']=='1')
							{
								$indiamart_assign_to=unserialize($credentials['assign_to']);
								$assign_start=isset($credentials['assign_start'])?$credentials['assign_start']:0;
								$assign_end=(count($indiamart_assign_to)-1);
							}
							
							foreach($get_im as $im)
							{	

								$cust_email=$im['SENDEREMAIL'];
								$cust_mobile=end(explode("-", $im['MOB']));
								$im_query_id=$im['QUERY_ID'];
								//echo $im['MOB'].' / '.$cust_mobile.'<br>';
								$cust_arr=array(
									'email'=>$cust_email,
									'mobile'=>$cust_mobile,
									'im_query_id'=>$im_query_id
												);
								// $get_customer_decision=$this->Customer_model->get_decision($cust_arr,$client_info);
								$get_customer_decision=$this->Cronjobs_model->cust_get_decision($cust_arr,$client_info);

								// ------------------------
								// get message 
								$im_history_data=array('msg'=>$get_customer_decision['msg']);
								// $this->Indiamart_model->update($im_history_data,$im['id'],$client_info);
								$this->Cronjobs_model->im_update($im_history_data,$im['id'],$client_info);
								// ------------------------
								
								if($get_customer_decision['status']==TRUE)
								{
									
									$com_country_code='';
									$com_country_id='';
									if($im['COUNTRY_ISO'])
									{
										// $get_country=$this->Countries_model->get_country_by_iso($im['COUNTRY_ISO'],$client_info);
										$get_country=$this->Cronjobs_model->countries_get_country_by_iso($im['COUNTRY_ISO'],$client_info);
										if($get_country!=false)
										{
											$com_country_id=$get_country->id;
											$com_country_code=$get_country->phonecode;

										}
									}
									
									$indiamart_setting_id=$im['indiamart_setting_id'];
									$com_contact_person=($im['SENDERNAME'])?$im['SENDERNAME']:'Purchase Manager';
									$com_designation='Manager';
									$com_email=$cust_email;
									$com_alternate_email=$im['EMAIL_ALT'];
									$com_mobile_country_code=$com_country_code;
									$com_mobile=$cust_mobile;
									$com_alt_mobile_country_code=$com_country_code;
									$com_alternate_mobile=$im['MOBILE_ALT'];
									$com_landline_country_code=$com_country_code;
									$com_landline_std_code='';
									$landline_number=$im['PHONE'];
									$com_website='';
									$com_company_name=$im['GLUSR_USR_COMPANYNAME'];
									$com_address=$im['ENQ_ADDRESS'];
									$com_city_id='';
									if($im['ENQ_CITY'])
									{
										// $get_city_id=$this->Cities_model->get_city_id_by_name($im['ENQ_CITY'],$client_info);
										$get_city_id=$this->Cronjobs_model->cities_get_city_id_by_name($im['ENQ_CITY'],$client_info);
										if($get_city_id!=false)
										{
											$com_city_id=$get_city_id;
										}
									}
									$com_state_id='';	
									if($im['ENQ_STATE'])
									{
										// $get_state_id=$this->States_model->get_state_id_by_name($im['ENQ_STATE'],$client_info);
										$get_state_id=$this->Cronjobs_model->states_get_state_id_by_name($im['ENQ_STATE'],$client_info);
										if($get_state_id!=false)
										{
											$com_state_id=$get_state_id;
										}
									}				
									$com_zip='';
									$com_short_description='';
									if($im['QTYPE'])
									{
										if(strtoupper($im['QTYPE'])=='W'){
											$com_source_text='Indiamart';
										}
										else if(strtoupper($im['QTYPE'])=='B'){
											$com_source_text='Indiamart Buylead';
										}
										else if(strtoupper($im['QTYPE'])=='P'){
											$com_source_text='Indiamart Call Enquiry';
										}		
										else{
											$com_source_text='Indiamart';
										}	

										
										$com_source_text_tmp = str_replace(' ', '', strtolower($com_source_text));
										// $com_source_id=$this->Source_model->get_source_id_by_name($com_source_text_tmp,$client_info);
										$com_source_id=$this->Cronjobs_model->source_get_source_id_by_name($com_source_text_tmp,$client_info);
										if($com_source_id==0)
										{
											$post_source=array(
												'parent_id'=>0,
												'name'=>$com_source_text
												);
											// $com_source_id=$this->Source_model->add($post_source,$client_info);
											$com_source_id=$this->Cronjobs_model->source_add($post_source,$client_info);
										}
									}

									// -------------------
									// LEAD INFO
									$lead_title=$im['SUBJECT'];
									$lead_requirement='';
									if($im['PRODUCT_NAME']){
										$lead_requirement .='<B>Product Required:</B> '.$im['PRODUCT_NAME'].'<BR><BR>';
									}
									if($im['ENQ_MESSAGE']){
										$lead_requirement .=''.$im['ENQ_MESSAGE'].'<BR><BR>';
									}
									if($im['ENQ_CALL_DURATION']){
										$lead_requirement .='<B>Call Duration:</B> '.$im['ENQ_CALL_DURATION'].'<BR><BR>';
									}
									$lead_requirement .='<B>From:</B><BR>';
									if($im['SENDERNAME']){
										$lead_requirement .=''.$im['SENDERNAME'].'';
									}
									if($im['GLUSR_USR_COMPANYNAME']){
										$lead_requirement .='<BR>'.$im['GLUSR_USR_COMPANYNAME'].'<BR>';
									}

									if($im['ENQ_CITY']!='' || $im['ENQ_STATE']!='' || $im['COUNTRY_ISO']!='')
									{
										$lead_requirement .=($im['ENQ_CITY'])?$im['ENQ_CITY']:'';
										
										$lead_requirement .=($im['ENQ_STATE'])?', ':'';
										$lead_requirement .=($im['ENQ_STATE'])?$im['ENQ_STATE']:'';
									
										$lead_requirement .=($im['COUNTRY_ISO'])?', ':'';
										$lead_requirement .=($im['COUNTRY_ISO'])?$get_country->name:'';
									}

									if($im['SENDEREMAIL']!='' || $im['EMAIL_ALT']!='')
									{

										$lead_requirement .='<br>Email: ';
										if($im['SENDEREMAIL']){
											$lead_requirement .=''.$im['SENDEREMAIL'].'';
											$lead_requirement .=($im['EMAIL_ALT'])?', ':'';
										}										
										$lead_requirement .=($im['EMAIL_ALT'])?$im['EMAIL_ALT']:'';
										
										if($im['SENDEREMAIL']!='' || $im['EMAIL_ALT']!=''){
											$lead_requirement .='<br>';
										}
									}

									if($im['MOB']!='' || $im['MOBILE_ALT']!=''){

										$lead_requirement .='Mobile: ';
										$lead_requirement .=''.$im['MOB'].'';

										$lead_requirement .=($im['MOBILE_ALT'])?', ':'';
										$lead_requirement .=($im['MOBILE_ALT'])?$im['MOBILE_ALT']:'';
										if($im['MOB']!='' || $im['MOBILE_ALT']!=''){
											$lead_requirement .='<br>';
										}
									}

									if($im['PHONE']!='' || $im['PHONE_ALT']!='')
									{
										$lead_requirement .='Phone: ';
										$lead_requirement .=($im['PHONE'])?$im['PHONE']:'';
										$lead_requirement .=($im['PHONE_ALT'])?', ':'';
										$lead_requirement .=($im['PHONE_ALT'])?$im['PHONE_ALT']:'';
										$lead_requirement .='<br>';						
									}

									if($im['DATE_TIME_RE']!='')
									{
										$lead_requirement .='Date and Time: ';			
										$lead_requirement .=$im['DATE_TIME_RE'];
									}
									// LEAD INFO
									// -------------------

									// -------------------
									// RULE WISE USER
									if($credentials['assign_rule']=='1')
									{
										$assigned_user_id=isset($indiamart_assign_to[$assign_start])?$indiamart_assign_to[$assign_start]:$indiamart_assign_to[0];
									}
									else if($credentials['assign_rule']==5)// KEYWORD 
									{			
										$title_tmp=str_replace(' ', '', strtolower($lead_title));
										$lead_requirement_tmp=str_replace(' ', '', strtolower($lead_requirement));				
										$search_keyword=$title_tmp.''.$lead_requirement_tmp;								
										$arr=array();
										$arr['assign_rule']=$credentials['assign_rule'];
										$arr['indiamart_setting_id']=$im['indiamart_setting_id'];
										$arr['search_keyword']=$search_keyword;	
										// $assigned_user_id=$this->Indiamart_setting_model->get_keyword_rule5_wise_assigned_user_id($arr,$client_info);
										$assigned_user_id=$this->Cronjobs_model->indiamart_setting_get_keyword_rule5_wise_assigned_user_id($arr,$client_info);
									}
									else
									{

										if($credentials['assign_rule']==2)
										{
											$search_keyword=$com_country_id;
										}
										else if($credentials['assign_rule']==3)
										{
											$search_keyword=$com_state_id;
										}
										else if($credentials['assign_rule']==4)
										{
											$search_keyword=$com_city_id;
										}
										$arr=array();
										$arr['assign_rule']=$credentials['assign_rule'];
										$arr['indiamart_setting_id']=$im['indiamart_setting_id'];
										$arr['search_keyword']=$search_keyword;	
										// $assigned_user_id=$this->Indiamart_setting_model->get_rule_wise_assigned_user_id($arr,$client_info);
										$assigned_user_id=$this->Cronjobs_model->indiamart_setting_get_rule_wise_assigned_user_id($arr,$client_info);
									}				
									// RULE WISE USER			
									// ---------------------


									// ===================
									// CUSTOMER DETAILS
									$com_contact_person_tmp='';		
									$is_blacklist='N';
									if($get_customer_decision['msg']=='no_customer_exist')
									{

										$company_post_data=array(
											'assigned_user_id'=>$assigned_user_id,
											'contact_person'=>$com_contact_person,
											'designation'=>$com_designation,
											'email'=>$com_email,
											'alt_email'=>$com_alternate_email,
											'mobile_country_code'=>$com_mobile_country_code,
											'mobile'=>$com_mobile,
											'alt_mobile_country_code'=>$com_alt_mobile_country_code,
											'alt_mobile'=>$com_alternate_mobile,
											'landline_country_code'=>$com_landline_country_code,
											'landline_std_code'=>$com_landline_std_code,
											'landline_number'=>$landline_number,
											'office_country_code'=>$com_country_code,
											'website'=>$com_website,
											'company_name'=>$com_company_name,
											'address'=>$com_address,
											'city'=>$com_city_id,
											'state'=>$com_state_id,
											'country_id'=>$com_country_id,
											'zip'=>$com_zip,
											'create_date'=>date("Y-m-d H:i:s"),
											'short_description'=>$com_short_description,
											'source_id'=>$com_source_id,
											'modify_date'=>date('Y-m-d'),
											'status'=>'1'
										);
																		
										// $com_company_id=$this->Cronjobs_model->CreateCustomer($company_post_data,$client_info);
										$com_company_id=$this->Cronjobs_model->CreateCustomer($company_post_data,$client_info);
										
										if($credentials['assign_rule']=='1')
										{
											$assign_start++;
											if($assign_start>$assign_end)
											{
												$assign_start=0;
											}

											// ---------------------------------
											// update to setting table
											$tmpdata=array('assign_start'=>$assign_start);
											// $this->Indiamart_setting_model->EditIndiamartCredentials($tmpdata,$indiamart_setting_id,$client_info);
											$this->Cronjobs_model->indiamart_setting_EditIndiamartCredentials($tmpdata,$indiamart_setting_id,$client_info);
											// update to setting table
											// ----------------------------------
										}
										$com_contact_person_tmp=$com_contact_person;
										
									}
									else if($get_customer_decision['msg']=='one_customer_exist')
									{
										$com_company_id=$get_customer_decision['customer_id'];
										// $get_existing_com=$this->Customer_model->get_company_detail($com_company_id,$client_info);
										$get_existing_com=$this->Cronjobs_model->cust_get_company_detail($com_company_id,$client_info);
										$com_source_id=$get_existing_com['source_id'];
										$assigned_user_id=$get_existing_com['assigned_user_id'];
										$is_blacklist=($get_existing_com['is_blacklist'])?$get_existing_com['is_blacklist']:'N';
										$company_post_data=array(
											'contact_person'=>($get_existing_com['contact_person'])?$get_existing_com['contact_person']:$com_contact_person,
											'designation'=>($get_existing_com['designation'])?$get_existing_com['designation']:$com_designation,
											'alt_email'=>($get_existing_com['alt_email'])?$get_existing_com['alt_email']:$com_alternate_email,
											'alt_mobile_country_code'=>($get_existing_com['alt_mobile_country_code'])?$get_existing_com['alt_mobile_country_code']:$com_alt_mobile_country_code,
											'alt_mobile'=>($get_existing_com['alt_mobile'])?$get_existing_com['alt_mobile']:$com_alternate_mobile,
											'landline_country_code'=>($get_existing_com['landline_country_code'])?$get_existing_com['landline_country_code']:$com_landline_country_code,
											'landline_std_code'=>($get_existing_com['landline_std_code'])?$get_existing_com['landline_std_code']:$com_landline_std_code,
											'landline_number'=>($get_existing_com['landline_number'])?$get_existing_com['landline_number']:$landline_number,
											'website'=>($get_existing_com['website'])?$get_existing_com['website']:$com_website,
											'company_name'=>($get_existing_com['company_name'])?$get_existing_com['company_name']:$com_company_name,
											'address'=>($get_existing_com['address'])?$get_existing_com['address']:$com_address,
											'city'=>($get_existing_com['city']>0)?$get_existing_com['city']:$com_city_id,
											'state'=>($get_existing_com['state']>0)?$get_existing_com['state']:$com_state_id,
											'country_id'=>($get_existing_com['country_id']>0)?$get_existing_com['country_id']:$com_country_id,
											'zip'=>($get_existing_com['zip']>0)?$get_existing_com['zip']:$com_zip,
											'short_description'=>($get_existing_com['short_description'])?$get_existing_com['short_description']:$com_short_description,
											'source_id'=>($get_existing_com['source_id']>0)?$get_existing_com['source_id']:$com_source_id,
											'modify_date'=>date('Y-m-d'),
											'status'=>'1'
										);
										
										$this->Cronjobs_model->UpdateCustomer($company_post_data,$com_company_id,$client_info);
										
										$com_contact_person_tmp=($get_existing_com['contact_person'])?$get_existing_com['contact_person']:$com_contact_person;								
									}
									

									if($is_blacklist=='N')
									{									

										$lead_enq_date=date_display_format_to_db_format($im['DATE_RE']);
										
										$lead_post_data=array(
											'title'=>$lead_title,
											'customer_id'=>$com_company_id,
											'source_id'=>$com_source_id,
											'assigned_user_id'=>$assigned_user_id,
											'buying_requirement'=>$lead_requirement,
											'enquiry_date'=>$lead_enq_date,
											'followup_date'=>date('Y-m-d'),
											'create_date'=>date('Y-m-d'),
											'modify_date'=>date('Y-m-d'),
											'assigned_date'=>date('Y-m-d'),
											'status'=>'1',
											'current_stage_id'=>'1',
											'current_stage'=>'PENDING',
											'current_stage_wise_msg'=>'',
											'current_status_id'=>'1',
											'current_status'=>'WARM',
											'im_query_id'=>$im_query_id,
											'im_setting_id'=>$indiamart_setting_id
										);
										
										// $lead_id=$this->Lead_model->CreateLead($lead_post_data,$client_info);
										$lead_id=$this->Cronjobs_model->lead_CreateLead($lead_post_data,$client_info);
										
										if($lead_id)
										{
											
											// ---------------------------
											// PRODUCT TAGGED
											// if($im['PRODUCT_NAME'])
											// {
											// 	$lead_p_data=array(
											// 		'lead_id'=>$lead_id,
											// 		'name'=>$im['PRODUCT_NAME']
											// 	);
											// 	$this->Lead_model->CreateLeadWiseProductTag($lead_p_data,$client_info);
											// }
											
											// PRODUCT TAGGED
											// ---------------------------
											


											// Insert Stage Log
											$stage_post_data=array(
													'lead_id'=>$lead_id,
													'stage_id'=>'1',
													'stage'=>'PENDING',
													'stage_wise_msg'=>'',
													'create_datetime'=>date("Y-m-d H:i:s")
												);
											// $this->Lead_model->CreateLeadStageLog($stage_post_data,$client_info);
											$this->Cronjobs_model->lead_CreateLeadStageLog($stage_post_data,$client_info);
											
											// Insert Status Log
											$status_post_data=array(
													'lead_id'=>$lead_id,
													'status_id'=>'2',
													'status'=>'WARM',
													'create_datetime'=>date("Y-m-d H:i:s")
												);
											// $this->Lead_model->CreateLeadStatusLog($status_post_data,$client_info);
											$this->Cronjobs_model->lead_CreateLeadStatusLog($status_post_data,$client_info);
											

											$attach_filename='';
											$assigned_by_user_id=1;
											// -------------------------------------------------
											// ASSIGN LEAD LOG TABLE
											$post_data=array(
											'lead_id'=>$lead_id,
											'assigned_to_user_id'=>$assigned_user_id,
											'assigned_by_user_id'=>$assigned_by_user_id,
											'is_accepted'=>'Y',
											'assigned_datetime'=>date("Y-m-d H:i:s")
														);			
											// $this->Lead_model->create_lead_assigned_user_log($post_data,$client_info);
											$this->Cronjobs_model->lead_create_lead_assigned_user_log($post_data,$client_info);
											
										

											$update_by=1;				
											$date=date("Y-m-d H:i:s");				
											$ip_addr=$_SERVER['REMOTE_ADDR'];				
											$message="A new lead has been created as &quot;".$lead_title."&quot;";
											$comment_title=NEW_LEAD_CREATE_MANUAL;
											$historydata=array(
											'title'=>$comment_title,
											'lead_id'=>$lead_id,
											'comment'=>addslashes($message),
											'attach_file'=>$attach_filename,
											'create_date'=>$date,
											'user_id'=>$update_by,
											'ip_address'=>$ip_addr
															);
											// $this->History_model->CreateHistory($historydata,$client_info);
											$this->Cronjobs_model->history_CreateHistory($historydata,$client_info);

											
											if($email_forwarding_setting['is_mail_send']=='Y' || count($sms_forwarding_setting)>0)
											{											
												// $assigned_to_user_data=$this->User_model->get_employee_details($assigned_user_id,$client_info);
												$assigned_to_user_data=$this->Cronjobs_model->user_get_employee_details($assigned_user_id,$client_info);
												// $company=$this->Setting_model->GetCompanyData($client_info);
												$company=$this->Cronjobs_model->setting_GetCompanyData($client_info);
												// $lead_info=$this->Lead_model->GetLeadData($lead_id,$client_info);
												$lead_info=$this->Cronjobs_model->lead_GetLeadData($lead_id,$client_info);
												// $customer=$this->Customer_model->GetCustomerData($lead_info->customer_id,$client_info);
												$customer=$this->Cronjobs_model->cust_GetCustomerData($lead_info->customer_id,$client_info);
											}
											
											// MAIL ALERT
											
											if($email_forwarding_setting['is_mail_send']=='Y')
											{
												// ============================
												// Mail Acknowledgement & 
												// account manager update
												// START

												// TO CLIENT				
												$e_data=array();											
												// $assigned_to_user_data=$this->User_model->get_employee_details($assigned_user_id,$client_info);
												// $company=$this->Setting_model->GetCompanyData($client_info);
												// $lead_info=$this->Lead_model->GetLeadData($lead_id,$client_info);
												// $customer=$this->Customer_model->GetCustomerData($lead_info->customer_id,$client_info);


												$e_data['company']=$company;
												$e_data['assigned_to_user']=$assigned_to_user_data;
												$e_data['customer']=$customer;
												$e_data['lead_info']=$lead_info;
												$e_data['get_company_name_initials']=get_company_name_initials($client_info);
												$e_data['rander_company_address']=rander_company_address_cronjobs('email_template',$client_info);
												$e_data['client_info']=$client_info;
												$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_cronjobs_view', $e_data, true);
												
												$m_email=get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);

												// --------------------
												// to mail assign logic
												$to_mail_assign='';
												$to_mail='';
												if($email_forwarding_setting['is_send_mail_to_client']=='Y')
												{
													$to_mail=$customer->email;
													$to_mail_assign='client';
												}
												else
												{
													if($m_email['manager_email']!='' && $email_forwarding_setting['is_send_manager']=='Y')
													{
														$to_mail=$m_email['manager_email'];
														$to_mail_assign='manager';
													}
													else
													{
														if($m_email['skip_manager_email']!='' && $email_forwarding_setting['is_send_skip_manager']=='Y')
														{
															$to_mail=$m_email['skip_manager_email'];
															$to_mail_assign='skip_manager';
														}
													}
												}
												// to mail assign logic
												// --------------------

												$cc_mail_arr=array();
												$self_cc_mail=get_value("email","user","id=".$assigned_user_id,$client_info);
												$update_by_name=get_value("name","user","id=".$assigned_user_id,$client_info);
												// --------------------
												// cc mail assign logic
												if($email_forwarding_setting['is_send_relationship_manager']=='Y')
												{
													if($to_mail=='')
													{
														$to_mail=$self_cc_mail;
													}
													else
													{
														array_push($cc_mail_arr, $self_cc_mail);
													}		        	
												}

												if($email_forwarding_setting['is_send_manager']=='Y')
												{
													if($m_email['manager_email']!='' && $to_mail_assign!='manager')
													{		        		
														array_push($cc_mail_arr, $m_email['manager_email']);
													}		        	
												}

												if($email_forwarding_setting['is_send_skip_manager']=='Y')
												{
													if($m_email['skip_manager_email']!='' && $to_mail_assign!='skip_manager')
													{		        		
														array_push($cc_mail_arr, $m_email['skip_manager_email']);
													}		        	
												}
												$cc_mail='';
												$cc_mail=implode(",", $cc_mail_arr);
												// cc mail assign logic
												// --------------------
														
												if($to_mail)
												{
													$com_company_name_tmp=$company['name'];
													if($im['PRODUCT_NAME']!=NULL && $com_contact_person_tmp!='')
													{
														$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' for '.$im['PRODUCT_NAME'].' has been received - '.$com_company_name_tmp; 
													}
													else if($im['PRODUCT_NAME']!=NULL && $com_contact_person_tmp=='')
													{
														$mail_subject='Enquiry # '.$lead_id.' for '.$im['PRODUCT_NAME'].' has been received - '.$com_company_name_tmp; 
													}
													else if($im['PRODUCT_NAME']==NULL && $com_contact_person_tmp!='')
													{
														$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
													}
													else if($im['PRODUCT_NAME']==NULL && $com_contact_person_tmp=='')
													{
														$mail_subject='Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
													}
													else
													{
														$mail_subject='Your Enquiry # '.$lead_id.' has been acknowledged';
													}

													
													//$this->load->library('mail_lib');
													$m_d = array();
													// $m_d['from_mail']     = $company['email1'];
													// $m_d['from_name']     = $company['name'];
													$m_d['from_mail']     = $self_cc_mail;
													$m_d['from_name']     = $update_by_name;
													$m_d['to_mail']       = $to_mail;
													$m_d['cc_mail']       = $cc_mail;
													$m_d['subject']       = $mail_subject;
													$m_d['message']       = $template_str;
													$m_d['attach']        = array();
													$mail_return = $this->mail_lib->send_mail($m_d,$client_info);
												}
												
												// END
												// =============================
											}
											
											// SMS ALERT
											
											if(count($sms_forwarding_setting))
											{			
												if($sms_forwarding_setting['is_sms_send']=='Y')
												{
													$m_mobile=get_manager_and_skip_manager_mobile_arr($assigned_user_id,$client_info);
													$default_template_auto_id=$sms_forwarding_setting['default_template_id'];
													$user_id=$this->session->userdata['admin_session_data']['user_id'];
													$sms_variable_info=array("customer_id"=>$lead_info->customer_id,'company_id'=>1,'lead_id'=>$lead_id,'user_id'=>$assigned_user_id);
													// --------------------
													// to sms send logic
													$sms_send_data=array();			
													$client_mobile='';
													if($sms_forwarding_setting['is_send_sms_to_client']=='Y')
													{
														$client_mobile=$customer->mobile;					
														$client_template_auto_id=($sms_forwarding_setting['send_sms_to_client_template_id'])?$sms_forwarding_setting['send_sms_to_client_template_id']:$default_template_auto_id;				
														$sms_send_data[]=array('mobile'=>$client_mobile,'template_auto_id'=>$client_template_auto_id);
													}	

													$relationship_manager_mobile='';
													if($sms_forwarding_setting['is_send_relationship_manager']=='Y')
													{
														$relationship_manager_mobile=get_value("mobile","user","id=".$assigned_user_id,$client_info);					
														$relationship_manager_template_auto_id=($sms_forwarding_setting['send_relationship_manager_template_id'])?$sms_forwarding_setting['send_relationship_manager_template_id']:$default_template_auto_id;						        	
														$sms_send_data[]=array('mobile'=>$relationship_manager_mobile,'template_auto_id'=>$relationship_manager_template_auto_id);
													}	
													
													$manager_mobile='';
													if($sms_forwarding_setting['is_send_manager']=='Y')
													{
														if($m_mobile['manager_mobile']!='')
														{		      
															$manager_mobile=$m_mobile['manager_mobile'];
															$manager_template_auto_id=($sms_forwarding_setting['send_manager_template_id'])?$sms_forwarding_setting['send_manager_template_id']:$default_template_auto_id;
															$sms_send_data[]=array('mobile'=>$manager_mobile,'template_auto_id'=>$manager_template_auto_id);
														}		        	
													}

													$skip_manager_mobile='';
													if($sms_forwarding_setting['is_send_skip_manager']=='Y')
													{
														if($m_mobile['skip_manager_mobile']!='')
														{		        		
															$skip_manager_mobile=$m_mobile['skip_manager_mobile'];
															$skip_manager_template_auto_id=($sms_forwarding_setting['send_skip_manager_template_id'])?$sms_forwarding_setting['send_skip_manager_template_id']:$default_template_auto_id;
															$sms_send_data[]=array('mobile'=>$skip_manager_mobile,'template_auto_id'=>$skip_manager_template_auto_id);
														}		        	
													}
													// to sms send logic	
													// --------------------
													$return=sms_send($sms_send_data,$sms_variable_info,$client_info);
												}
											}	
																			
										}
									}
								}
								else
								{

								}
							}			
						}
					}
				}
				
				if(count($indiamart_credentials)>0)
				{
					$msg="Process completed to fetch leads from indiamart.";
					$status='success';
				}
				else
				{
					$msg="imdiamart credentials not available";
					$status='fail';
				}
				echo $status.'#'.$msg;			
				// ==========================================
								
			}	
			
			// ===============================================================
			$post_data=array(
				'client_id'=>$client_info->client_id,
				'function_name'=>'get_lead_from_indiamart',
				'updated_db_name'=>$client_info->db_name,
				'msg'=>$msg_log,
				'created_at'=>date("Y-m-d H:i:s")
				);	
			// $this->Client_model->create_cronjobs_log($post_data);				
			// ===============================================================					
		}	
	}	
	//die('completed');	
}
/*
function get_lead_from_indiamart_by_manual($client_id='')
{	
	
	if($client_id=='')
	{
		echo 'fail#Parameter is missing.';die();
	}
	// ==================================================================================	
	$get_im_last_call=$this->Client_model->get_im_last_call($client_id);
	if($get_im_last_call)
	{
		$time1 = strtotime($get_im_last_call);
		$time2 = strtotime(date("Y-m-d H:i:s"));
		$time_interval_in_sec=($time2-$time1);
		if($time_interval_in_sec<350)
		{
			echo 'error_code_429#"CODE": 429. It is advised to hit this API once in every 5 minutes,but it seems that you have crossed this limit. Please try again after 5 minutes.';die();
		}
	}
	// ==================================================================================
	

	date_default_timezone_set('Asia/Kolkata');
	$current_datetime=date("Y-m-d");		
	
	$start_datetime = date("d-M-Y",strtotime("-1 days", strtotime($current_datetime)));
	$end_datetime=date("d-M-Y",strtotime($current_datetime));	
	$arg=array();
	$arg['client_id']=$client_id;
	$arg['cronjobs_action']='indiamart';	
	$arg['get_limit_offset']='';		
	$client_db_info_list=$this->Client_model->get_all($arg);	
	if(count($client_db_info_list))
	{	
		foreach($client_db_info_list AS $client_info)
		{			
			$this->Cronjobs_model->initialise($client_info);
			$msg_log='Manual Hit|';
			$indiamart_credentials=$this->Indiamart_setting_model->GetIndiamartCredentials($client_info);
			
			// Lead Acknowledgement id 1
			$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(1,$client_info);

			// SMS for Acknowledgement id 1
			$sms_forwarding_setting=$this->Sms_forwarding_setting_model->GetDetails(1,$client_info);
			
			
			if(count($indiamart_credentials)>0)
			{				
				$this->Indiamart_model->truncate($client_info);
				foreach($indiamart_credentials AS $im)
				{
					$indiamart_setting_id=$im['id'];
					$is_old_version=$im['is_old_version'];					

					$GLUSR_MOBILE=$im['glusr_mobile'];
					$GLUSR_MOBILE_KEY=$im['glusr_mobile_key'];		
					
					if($is_old_version=='Y')
					{
						$url = "https://mapi.indiamart.com/wservce/enquiry/listing/GLUSR_MOBILE/".$GLUSR_MOBILE."/GLUSR_MOBILE_KEY/".$GLUSR_MOBILE_KEY."/Start_Time/".$start_datetime."/End_Time/".$end_datetime."/";	
						
						$useragent = 'PHP Client 1.0 (curl) ' . phpversion();
						$post_string="";
						$url_with_get=$url;
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url_with_get);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
						curl_setopt($ch, CURLOPT_TIMEOUT, 30);
						$result = curl_exec($ch);
						curl_close($ch);
						$return_arr=json_decode($result);	
						
						$i=0;
						if(!isset($return_arr[0]->Error_Message))
						{
							foreach($return_arr as $row)
							{
								$post=array(
											'indiamart_setting_id'=>$indiamart_setting_id,
											'RN'=>$row->RN,
											'QUERY_ID'=>$row->QUERY_ID,
											'QTYPE'=>$row->QTYPE,
											'SENDERNAME'=>$row->SENDERNAME,
											'SENDEREMAIL'=>$row->SENDEREMAIL,
											'SUBJECT'=>$row->SUBJECT,
											'DATE_RE'=>$row->DATE_RE,
											'DATE_R'=>$row->DATE_R,
											'DATE_TIME_RE'=>$row->DATE_TIME_RE,
											'GLUSR_USR_COMPANYNAME'=>$row->GLUSR_USR_COMPANYNAME,
											'READ_STATUS'=>$row->READ_STATUS,
											'SENDER_GLUSR_USR_ID'=>$row->SENDER_GLUSR_USR_ID,
											'MOB'=>$row->MOB,
											'COUNTRY_FLAG'=>$row->COUNTRY_FLAG,
											'QUERY_MODID'=>$row->QUERY_MODID,
											'LOG_TIME'=>$row->LOG_TIME,
											'QUERY_MODREFID'=>$row->QUERY_MODREFID,
											'DIR_QUERY_MODREF_TYPE'=>$row->DIR_QUERY_MODREF_TYPE,
											'ORG_SENDER_GLUSR_ID'=>$row->ORG_SENDER_GLUSR_ID,
											'ENQ_MESSAGE'=>$row->ENQ_MESSAGE,
											'ENQ_ADDRESS'=>$row->ENQ_ADDRESS,
											'ENQ_CALL_DURATION'=>$row->ENQ_CALL_DURATION,
											'ENQ_RECEIVER_MOB'=>$row->ENQ_RECEIVER_MOB,
											'ENQ_CITY'=>$row->ENQ_CITY,
											'ENQ_STATE'=>$row->ENQ_STATE,
											'PRODUCT_NAME'=>$row->PRODUCT_NAME,
											'COUNTRY_ISO'=>$row->COUNTRY_ISO,
											'EMAIL_ALT'=>$row->EMAIL_ALT,
											'MOBILE_ALT'=>$row->MOBILE_ALT,
											'PHONE'=>$row->PHONE,
											'PHONE_ALT'=>$row->PHONE_ALT,
											'IM_MEMBER_SINCE'=>$row->IM_MEMBER_SINCE,
											'TOTAL_COUNT'=>$row->TOTAL_COUNT
											);
								$this->Indiamart_model->insert($post,$client_info);
								$i++;				
							}
							$msg_log .='Old Version|Error code:200|Total Row Fetched:-'.$i;
						}
						else
						{
							$msg_log .='Old Version|Error message:-'.$return_arr[0]->Error_Message;
						}
					}
					else if($is_old_version=='N')
					{
						
						$url = "https://mapi.indiamart.com/wservce/crm/crmListing/v2/?glusr_crm_key=".$GLUSR_MOBILE_KEY."&start_time=".$start_datetime."&end_time=".$end_datetime;	
						
						$useragent = 'PHP Client 1.0 (curl) ' . phpversion();
						$post_string="";
						$url_with_get=$url;
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url_with_get);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
						curl_setopt($ch, CURLOPT_TIMEOUT, 30);
						$result = curl_exec($ch);
						curl_close($ch);
						$return_arr=json_decode($result);	
						
						$i=0;						
						if($return_arr->CODE==200)
						{
							$TOTAL_COUNT=$return_arr->TOTAL_RECORDS;
							foreach($return_arr->RESPONSE as $row)
							{
								$DATE_RE=date('d M Y', strtotime($row->QUERY_TIME));
								$DATE_R=date('d-M-y', strtotime($row->QUERY_TIME));
								$DATE_TIME_RE=date('d-M-Y H:i:s A', strtotime($row->QUERY_TIME));								
								$subject=''.$row->QUERY_PRODUCT_NAME;
								$post=array(
										'indiamart_setting_id'=>$indiamart_setting_id,
										'RN'=>'',
										'QUERY_ID'=>$row->UNIQUE_QUERY_ID,
										'QTYPE'=>addslashes($row->QUERY_TYPE),
										'SENDERNAME'=>addslashes($row->SENDER_NAME),
										'SENDEREMAIL'=>addslashes($row->SENDER_EMAIL),
										'SUBJECT'=>addslashes($subject),
										'DATE_RE'=>$DATE_RE,
										'DATE_R'=>$DATE_R,
										'DATE_TIME_RE'=>$DATE_TIME_RE,
										'GLUSR_USR_COMPANYNAME'=>addslashes($row->SENDER_COMPANY),
										'READ_STATUS'=>'',
										'SENDER_GLUSR_USR_ID'=>'',
										'MOB'=>$row->SENDER_MOBILE,
										'COUNTRY_FLAG'=>'',
										'QUERY_MODID'=>'',
										'LOG_TIME'=>'',
										'QUERY_MODREFID'=>'',
										'DIR_QUERY_MODREF_TYPE'=>'',
										'ORG_SENDER_GLUSR_ID'=>'',
										'ENQ_MESSAGE'=>addslashes($row->QUERY_MESSAGE),
										'ENQ_ADDRESS'=>addslashes($row->SENDER_ADDRESS),
										'ENQ_CALL_DURATION'=>$row->CALL_DURATION,
										'ENQ_RECEIVER_MOB'=>$row->RECEIVER_MOBILE,
										'ENQ_CITY'=>addslashes($row->SENDER_CITY),
										'ENQ_STATE'=>addslashes($row->SENDER_STATE),
										'PRODUCT_NAME'=>addslashes($row->QUERY_PRODUCT_NAME),
										'COUNTRY_ISO'=>$row->SENDER_COUNTRY_ISO,
										'EMAIL_ALT'=>$row->SENDER_EMAIL_ALT,
										'MOBILE_ALT'=>$row->SENDER_MOBILE_ALT,
										'PHONE'=>'',
										'PHONE_ALT'=>'',
										'IM_MEMBER_SINCE'=>'',
										'TOTAL_COUNT'=>$TOTAL_COUNT
										);
								$this->Indiamart_model->insert($post,$client_info);
								$i++;				
							}
							$msg_log .='New Version|Error code:200|Total Row Fetched:-'.$i;
						}	
						else
						{
							$msg_log .='New Version|Error code:-'.$return_arr->CODE;
						}
											
					}
				}

				// ==========================================================================
				// UPDATE LAST IM API HIT
				$post_data=array();
				$post_data=array('last_indiamart_api_call_by_cronjobs'=>date("Y-m-d H:i:s"));
				$this->Client_model->update($post_data,$client_info->client_id);
				// UPDATE LAST IM API HIT
				// ==========================================================================
			}
			
			
			if(count($indiamart_credentials)>0)
			{			
				foreach($indiamart_credentials AS $credentials)
				{				
					$get_im=$this->Indiamart_model->get_rows($credentials['id'],$client_info);				
					if(count($get_im))
					{					
						if($credentials['assign_rule']=='1')
						{
							$indiamart_assign_to=unserialize($credentials['assign_to']);
							$assign_start=isset($credentials['assign_start'])?$credentials['assign_start']:0;
							$assign_end=(count($indiamart_assign_to)-1);
						}
						
						foreach($get_im as $im)
						{	

							$cust_email=$im['SENDEREMAIL'];
							$cust_mobile=end(explode("-", $im['MOB']));
							$im_query_id=$im['QUERY_ID'];
							//echo $im['MOB'].' / '.$cust_mobile.'<br>';
							$cust_arr=array(
								'email'=>$cust_email,
								'mobile'=>$cust_mobile,
								'im_query_id'=>$im_query_id
											);
							$get_customer_decision=$this->Customer_model->get_decision($cust_arr,$client_info);

							// ------------------------
							// get message 
							$im_history_data=array('msg'=>$get_customer_decision['msg']);
							$this->Indiamart_model->update($im_history_data,$im['id'],$client_info);
							// ------------------------
							
							if($get_customer_decision['status']==TRUE)
							{
								
								$com_country_code='';
								$com_country_id='';
								if($im['COUNTRY_ISO'])
								{
									$get_country=$this->Countries_model->get_country_by_iso($im['COUNTRY_ISO'],$client_info);
									if($get_country!=false)
									{
										$com_country_id=$get_country->id;
										$com_country_code=$get_country->phonecode;

									}
								}
								
								$indiamart_setting_id=$im['indiamart_setting_id'];
								$com_contact_person=($im['SENDERNAME'])?$im['SENDERNAME']:'Purchase Manager';
								$com_designation='Manager';
								$com_email=$cust_email;
								$com_alternate_email=$im['EMAIL_ALT'];
								$com_mobile_country_code=$com_country_code;
								$com_mobile=$cust_mobile;
								$com_alt_mobile_country_code=$com_country_code;
								$com_alternate_mobile=$im['MOBILE_ALT'];
								$com_landline_country_code=$com_country_code;
								$com_landline_std_code='';
								$landline_number=$im['PHONE'];
								$com_website='';
								$com_company_name=$im['GLUSR_USR_COMPANYNAME'];
								$com_address=$im['ENQ_ADDRESS'];
								$com_city_id='';
								if($im['ENQ_CITY'])
								{
									$get_city_id=$this->Cities_model->get_city_id_by_name($im['ENQ_CITY'],$client_info);
									if($get_city_id!=false)
									{
										$com_city_id=$get_city_id;
									}
								}
								$com_state_id='';	
								if($im['ENQ_STATE'])
								{
									$get_state_id=$this->States_model->get_state_id_by_name($im['ENQ_STATE'],$client_info);
									if($get_state_id!=false)
									{
										$com_state_id=$get_state_id;
									}
								}				
								$com_zip='';
								$com_short_description='';
								if($im['QTYPE'])
								{
									if(strtoupper($im['QTYPE'])=='W'){
										$com_source_text='Indiamart';
									}
									else if(strtoupper($im['QTYPE'])=='B'){
										$com_source_text='Indiamart Buylead';
									}
									else if(strtoupper($im['QTYPE'])=='P'){
										$com_source_text='Indiamart Call Enquiry';
									}		
									else{
										$com_source_text='Indiamart';
									}	

									
									$com_source_text_tmp = str_replace(' ', '', strtolower($com_source_text));
									$com_source_id=$this->Source_model->get_source_id_by_name($com_source_text_tmp,$client_info);
									if($com_source_id==0)
									{
										$post_source=array(
											'parent_id'=>0,
											'name'=>$com_source_text
											);
										$com_source_id=$this->Source_model->add($post_source,$client_info);
									}
								}

								// -------------------
								// LEAD INFO
								$lead_title=$im['SUBJECT'];
								$lead_requirement='';
								if($im['PRODUCT_NAME']){
									$lead_requirement .='<B>Product Required:</B> '.$im['PRODUCT_NAME'].'<BR><BR>';
								}
								if($im['ENQ_MESSAGE']){
									$lead_requirement .=''.$im['ENQ_MESSAGE'].'<BR><BR>';
								}
								if($im['ENQ_CALL_DURATION']){
									$lead_requirement .='<B>Call Duration:</B> '.$im['ENQ_CALL_DURATION'].'<BR><BR>';
								}
								$lead_requirement .='<B>From:</B><BR>';
								if($im['SENDERNAME']){
									$lead_requirement .=''.$im['SENDERNAME'].'';
								}
								if($im['GLUSR_USR_COMPANYNAME']){
									$lead_requirement .='<BR>'.$im['GLUSR_USR_COMPANYNAME'].'<BR>';
								}

								if($im['ENQ_CITY']!='' || $im['ENQ_STATE']!='' || $im['COUNTRY_ISO']!='')
								{
									$lead_requirement .=($im['ENQ_CITY'])?$im['ENQ_CITY']:'';
									
									$lead_requirement .=($im['ENQ_STATE'])?', ':'';
									$lead_requirement .=($im['ENQ_STATE'])?$im['ENQ_STATE']:'';
								
									$lead_requirement .=($im['COUNTRY_ISO'])?', ':'';
									$lead_requirement .=($im['COUNTRY_ISO'])?$get_country->name:'';
								}

								if($im['SENDEREMAIL']!='' || $im['EMAIL_ALT']!='')
								{

									$lead_requirement .='<br>Email: ';
									if($im['SENDEREMAIL']){
										$lead_requirement .=''.$im['SENDEREMAIL'].'';
										$lead_requirement .=($im['EMAIL_ALT'])?', ':'';
									}										
									$lead_requirement .=($im['EMAIL_ALT'])?$im['EMAIL_ALT']:'';
									
									if($im['SENDEREMAIL']!='' || $im['EMAIL_ALT']!=''){
										$lead_requirement .='<br>';
									}
								}

								if($im['MOB']!='' || $im['MOBILE_ALT']!=''){

									$lead_requirement .='Mobile: ';
									$lead_requirement .=''.$im['MOB'].'';

									$lead_requirement .=($im['MOBILE_ALT'])?', ':'';
									$lead_requirement .=($im['MOBILE_ALT'])?$im['MOBILE_ALT']:'';
									if($im['MOB']!='' || $im['MOBILE_ALT']!=''){
										$lead_requirement .='<br>';
									}
								}

								if($im['PHONE']!='' || $im['PHONE_ALT']!='')
								{
									$lead_requirement .='Phone: ';
									$lead_requirement .=($im['PHONE'])?$im['PHONE']:'';
									$lead_requirement .=($im['PHONE_ALT'])?', ':'';
									$lead_requirement .=($im['PHONE_ALT'])?$im['PHONE_ALT']:'';
									$lead_requirement .='<br>';						
								}

								if($im['DATE_TIME_RE']!='')
								{
									$lead_requirement .='Date and Time: ';			
									$lead_requirement .=$im['DATE_TIME_RE'];
								}
								// LEAD INFO
								// -------------------

								// -------------------
								// RULE WISE USER
								if($credentials['assign_rule']=='1')
								{
									$assigned_user_id=isset($indiamart_assign_to[$assign_start])?$indiamart_assign_to[$assign_start]:$indiamart_assign_to[0];
								}
								else if($credentials['assign_rule']==5)// KEYWORD 
								{			
									$title_tmp=str_replace(' ', '', strtolower($lead_title));
									$lead_requirement_tmp=str_replace(' ', '', strtolower($lead_requirement));				
									$search_keyword=$title_tmp.''.$lead_requirement_tmp;								
									$arr=array();
									$arr['assign_rule']=$credentials['assign_rule'];
									$arr['indiamart_setting_id']=$im['indiamart_setting_id'];
									$arr['search_keyword']=$search_keyword;	
									$assigned_user_id=$this->Indiamart_setting_model->get_keyword_rule5_wise_assigned_user_id($arr,$client_info);
								}
								else
								{

									if($credentials['assign_rule']==2)
									{
										$search_keyword=$com_country_id;
									}
									else if($credentials['assign_rule']==3)
									{
										$search_keyword=$com_state_id;
									}
									else if($credentials['assign_rule']==4)
									{
										$search_keyword=$com_city_id;
									}
									$arr=array();
									$arr['assign_rule']=$credentials['assign_rule'];
									$arr['indiamart_setting_id']=$im['indiamart_setting_id'];
									$arr['search_keyword']=$search_keyword;	
									$assigned_user_id=$this->Indiamart_setting_model->get_rule_wise_assigned_user_id($arr,$client_info);
								}				
								// RULE WISE USER			
								// ---------------------


								// ===================
								// CUSTOMER DETAILS
								$com_contact_person_tmp='';		
								$is_blacklist='N';
								if($get_customer_decision['msg']=='no_customer_exist')
								{

									$company_post_data=array(
										'assigned_user_id'=>$assigned_user_id,
										'contact_person'=>$com_contact_person,
										'designation'=>$com_designation,
										'email'=>$com_email,
										'alt_email'=>$com_alternate_email,
										'mobile_country_code'=>$com_mobile_country_code,
										'mobile'=>$com_mobile,
										'alt_mobile_country_code'=>$com_alt_mobile_country_code,
										'alt_mobile'=>$com_alternate_mobile,
										'landline_country_code'=>$com_landline_country_code,
										'landline_std_code'=>$com_landline_std_code,
										'landline_number'=>$landline_number,
										'office_country_code'=>$com_country_code,
										'website'=>$com_website,
										'company_name'=>$com_company_name,
										'address'=>$com_address,
										'city'=>$com_city_id,
										'state'=>$com_state_id,
										'country_id'=>$com_country_id,
										'zip'=>$com_zip,
										'create_date'=>date("Y-m-d H:i:s"),
										'short_description'=>$com_short_description,
										'source_id'=>$com_source_id,
										'modify_date'=>date('Y-m-d'),
										'status'=>'1'
									);
									//print_r($company_post_data); die();
									
									$com_company_id=$this->Cronjobs_model->CreateCustomer($company_post_data,$client_info);
									
									if($credentials['assign_rule']=='1')
									{
										$assign_start++;
										if($assign_start>$assign_end)
										{
											$assign_start=0;
										}

										// ---------------------------------
										// update to setting table
										$tmpdata=array('assign_start'=>$assign_start);
										$this->Indiamart_setting_model->EditIndiamartCredentials($tmpdata,$indiamart_setting_id,$client_info);
										// update to setting table
										// ----------------------------------
									}
									$com_contact_person_tmp=$com_contact_person;
									
								}
								else if($get_customer_decision['msg']=='one_customer_exist')
								{
									$com_company_id=$get_customer_decision['customer_id'];
									$get_existing_com=$this->Customer_model->get_company_detail($com_company_id,$client_info);
									$com_source_id=$get_existing_com['source_id'];
									$assigned_user_id=$get_existing_com['assigned_user_id'];
									$is_blacklist=($get_existing_com['is_blacklist'])?$get_existing_com['is_blacklist']:'N';
									$company_post_data=array(
										'contact_person'=>($get_existing_com['contact_person'])?$get_existing_com['contact_person']:$com_contact_person,
										'designation'=>($get_existing_com['designation'])?$get_existing_com['designation']:$com_designation,
										'alt_email'=>($get_existing_com['alt_email'])?$get_existing_com['alt_email']:$com_alternate_email,
										'alt_mobile_country_code'=>($get_existing_com['alt_mobile_country_code'])?$get_existing_com['alt_mobile_country_code']:$com_alt_mobile_country_code,
										'alt_mobile'=>($get_existing_com['alt_mobile'])?$get_existing_com['alt_mobile']:$com_alternate_mobile,
										'landline_country_code'=>($get_existing_com['landline_country_code'])?$get_existing_com['landline_country_code']:$com_landline_country_code,
										'landline_std_code'=>($get_existing_com['landline_std_code'])?$get_existing_com['landline_std_code']:$com_landline_std_code,
										'landline_number'=>($get_existing_com['landline_number'])?$get_existing_com['landline_number']:$landline_number,
										'website'=>($get_existing_com['website'])?$get_existing_com['website']:$com_website,
										'company_name'=>($get_existing_com['company_name'])?$get_existing_com['company_name']:$com_company_name,
										'address'=>($get_existing_com['address'])?$get_existing_com['address']:$com_address,
										'city'=>($get_existing_com['city']>0)?$get_existing_com['city']:$com_city_id,
										'state'=>($get_existing_com['state']>0)?$get_existing_com['state']:$com_state_id,
										'country_id'=>($get_existing_com['country_id']>0)?$get_existing_com['country_id']:$com_country_id,
										'zip'=>($get_existing_com['zip']>0)?$get_existing_com['zip']:$com_zip,
										'short_description'=>($get_existing_com['short_description'])?$get_existing_com['short_description']:$com_short_description,
										'source_id'=>($get_existing_com['source_id']>0)?$get_existing_com['source_id']:$com_source_id,
										'modify_date'=>date('Y-m-d'),
										'status'=>'1'
									);
									
									$this->Cronjobs_model->UpdateCustomer($company_post_data,$com_company_id,$client_info);
									
									$com_contact_person_tmp=($get_existing_com['contact_person'])?$get_existing_com['contact_person']:$com_contact_person;								
								}
								

								if($is_blacklist=='N')
								{									

									$lead_enq_date=date_display_format_to_db_format($im['DATE_RE']);
									
									$lead_post_data=array(
										'title'=>$lead_title,
										'customer_id'=>$com_company_id,
										'source_id'=>$com_source_id,
										'assigned_user_id'=>$assigned_user_id,
										'buying_requirement'=>$lead_requirement,
										'enquiry_date'=>$lead_enq_date,
										'followup_date'=>date('Y-m-d'),
										'create_date'=>date('Y-m-d'),
										'modify_date'=>date('Y-m-d'),
										'assigned_date'=>date('Y-m-d'),
										'status'=>'1',
										'current_stage_id'=>'1',
										'current_stage'=>'PENDING',
										'current_stage_wise_msg'=>'',
										'current_status_id'=>'1',
										'current_status'=>'WARM',
										'im_query_id'=>$im_query_id,
										'im_setting_id'=>$indiamart_setting_id
									);
									
									$lead_id=$this->Lead_model->CreateLead($lead_post_data,$client_info);
									
									if($lead_id)
									{
										
										// ---------------------------
										// PRODUCT TAGGED
										// if($im['PRODUCT_NAME'])
										// {
										// 	$lead_p_data=array(
										// 		'lead_id'=>$lead_id,
										// 		'name'=>$im['PRODUCT_NAME']
										// 	);
										// 	$this->Lead_model->CreateLeadWiseProductTag($lead_p_data,$client_info);
										// }
										
										// PRODUCT TAGGED
										// ---------------------------
										


										// Insert Stage Log
										$stage_post_data=array(
												'lead_id'=>$lead_id,
												'stage_id'=>'1',
												'stage'=>'PENDING',
												'stage_wise_msg'=>'',
												'create_datetime'=>date("Y-m-d H:i:s")
											);
										$this->Lead_model->CreateLeadStageLog($stage_post_data,$client_info);
										
										// Insert Status Log
										$status_post_data=array(
												'lead_id'=>$lead_id,
												'status_id'=>'2',
												'status'=>'WARM',
												'create_datetime'=>date("Y-m-d H:i:s")
											);
										$this->Lead_model->CreateLeadStatusLog($status_post_data,$client_info);
										

										$attach_filename='';
										$assigned_by_user_id=1;
										// -------------------------------------------------
										// ASSIGN LEAD LOG TABLE
										$post_data=array(
										'lead_id'=>$lead_id,
										'assigned_to_user_id'=>$assigned_user_id,
										'assigned_by_user_id'=>$assigned_by_user_id,
										'is_accepted'=>'Y',
										'assigned_datetime'=>date("Y-m-d H:i:s")
													);			
										$this->Lead_model->create_lead_assigned_user_log($post_data,$client_info);
										
									

										$update_by=1;				
										$date=date("Y-m-d H:i:s");				
										$ip_addr=$_SERVER['REMOTE_ADDR'];				
										$message="A new lead has been created as &quot;".$lead_title."&quot;";
										$comment_title=NEW_LEAD_CREATE_MANUAL;
										$historydata=array(
										'title'=>$comment_title,
										'lead_id'=>$lead_id,
										'comment'=>addslashes($message),
										'attach_file'=>$attach_filename,
										'create_date'=>$date,
										'user_id'=>$update_by,
										'ip_address'=>$ip_addr
														);
										$this->History_model->CreateHistory($historydata,$client_info);

										
										if($email_forwarding_setting['is_mail_send']=='Y' || count($sms_forwarding_setting)>0)
										{											
											$assigned_to_user_data=$this->User_model->get_employee_details($assigned_user_id,$client_info);
											$company=$this->Setting_model->GetCompanyData($client_info);
											$lead_info=$this->Lead_model->GetLeadData($lead_id,$client_info);
											$customer=$this->Customer_model->GetCustomerData($lead_info->customer_id,$client_info);
										}

										// MAIL ALERT
										if($email_forwarding_setting['is_mail_send']=='Y')
										{
											// ============================
											// Mail Acknowledgement & 
											// account manager update
											// START

											// TO CLIENT				
											$e_data=array();											
											// $assigned_to_user_data=$this->User_model->get_employee_details($assigned_user_id,$client_info);
											// $company=$this->Setting_model->GetCompanyData($client_info);
											// $lead_info=$this->Lead_model->GetLeadData($lead_id,$client_info);
											// $customer=$this->Customer_model->GetCustomerData($lead_info->customer_id,$client_info);


											$e_data['company']=$company;
											$e_data['assigned_to_user']=$assigned_to_user_data;
											$e_data['customer']=$customer;
											$e_data['lead_info']=$lead_info;
											$e_data['get_company_name_initials']=get_company_name_initials($client_info);
											$e_data['rander_company_address']=rander_company_address_cronjobs('email_template',$client_info);
											$e_data['client_info']=$client_info;
											$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_cronjobs_view', $e_data, true);
											
											$m_email=get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);

											// --------------------
											// to mail assign logic
											$to_mail_assign='';
											$to_mail='';
											if($email_forwarding_setting['is_send_mail_to_client']=='Y')
											{
												$to_mail=$customer->email;
												$to_mail_assign='client';
											}
											else
											{
												if($m_email['manager_email']!='' && $email_forwarding_setting['is_send_manager']=='Y')
												{
													$to_mail=$m_email['manager_email'];
													$to_mail_assign='manager';
												}
												else
												{
													if($m_email['skip_manager_email']!='' && $email_forwarding_setting['is_send_skip_manager']=='Y')
													{
														$to_mail=$m_email['skip_manager_email'];
														$to_mail_assign='skip_manager';
													}
												}
											}
											// to mail assign logic
											// --------------------

											$cc_mail_arr=array();
											$self_cc_mail=get_value("email","user","id=".$assigned_user_id,$client_info);
											$update_by_name=get_value("name","user","id=".$assigned_user_id,$client_info);
											// --------------------
											// cc mail assign logic
											if($email_forwarding_setting['is_send_relationship_manager']=='Y')
											{
												if($to_mail=='')
												{
													$to_mail=$self_cc_mail;
												}
												else
												{
													array_push($cc_mail_arr, $self_cc_mail);
												}		        	
											}

											if($email_forwarding_setting['is_send_manager']=='Y')
											{
												if($m_email['manager_email']!='' && $to_mail_assign!='manager')
												{		        		
													array_push($cc_mail_arr, $m_email['manager_email']);
												}		        	
											}

											if($email_forwarding_setting['is_send_skip_manager']=='Y')
											{
												if($m_email['skip_manager_email']!='' && $to_mail_assign!='skip_manager')
												{		        		
													array_push($cc_mail_arr, $m_email['skip_manager_email']);
												}		        	
											}
											$cc_mail='';
											$cc_mail=implode(",", $cc_mail_arr);
											// cc mail assign logic
											// --------------------
													
											if($to_mail)
											{
												$com_company_name_tmp=$company['name'];
												if($im['PRODUCT_NAME']!=NULL && $com_contact_person_tmp!='')
												{
													$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' for '.$im['PRODUCT_NAME'].' has been received - '.$com_company_name_tmp; 
												}
												else if($im['PRODUCT_NAME']!=NULL && $com_contact_person_tmp=='')
												{
													$mail_subject='Enquiry # '.$lead_id.' for '.$im['PRODUCT_NAME'].' has been received - '.$com_company_name_tmp; 
												}
												else if($im['PRODUCT_NAME']==NULL && $com_contact_person_tmp!='')
												{
													$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
												}
												else if($im['PRODUCT_NAME']==NULL && $com_contact_person_tmp=='')
												{
													$mail_subject='Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
												}
												else
												{
													$mail_subject='Your Enquiry # '.$lead_id.' has been acknowledged';
												}

												
												//$this->load->library('mail_lib');
												$m_d = array();
												// $m_d['from_mail']     = $company['email1'];
												// $m_d['from_name']     = $company['name'];
												$m_d['from_mail']     = $self_cc_mail;
												$m_d['from_name']     = $update_by_name;
												$m_d['to_mail']       = $to_mail;
												$m_d['cc_mail']       = $cc_mail;
												$m_d['subject']       = $mail_subject;
												$m_d['message']       = $template_str;
												$m_d['attach']        = array();
												$mail_return = $this->mail_lib->send_mail($m_d,$client_info);
											}
											
											// END
											// =============================
										}

										// SMS ALERT
										if(count($sms_forwarding_setting))
										{			
											if($sms_forwarding_setting['is_sms_send']=='Y')
											{
												$m_mobile=get_manager_and_skip_manager_mobile_arr($assigned_user_id,$client_info);
												$default_template_auto_id=$sms_forwarding_setting['default_template_id'];
												$user_id=$this->session->userdata['admin_session_data']['user_id'];
												$sms_variable_info=array("customer_id"=>$lead_info->customer_id,'company_id'=>1,'lead_id'=>$lead_id,'user_id'=>$assigned_user_id);
												// --------------------
												// to sms send logic
												$sms_send_data=array();			
												$client_mobile='';
												if($sms_forwarding_setting['is_send_sms_to_client']=='Y')
												{
													$client_mobile=$customer->mobile;					
													$client_template_auto_id=($sms_forwarding_setting['send_sms_to_client_template_id'])?$sms_forwarding_setting['send_sms_to_client_template_id']:$default_template_auto_id;				
													$sms_send_data[]=array('mobile'=>$client_mobile,'template_auto_id'=>$client_template_auto_id);
												}	

												$relationship_manager_mobile='';
												if($sms_forwarding_setting['is_send_relationship_manager']=='Y')
												{
													$relationship_manager_mobile=get_value("mobile","user","id=".$assigned_user_id,$client_info);					
													$relationship_manager_template_auto_id=($sms_forwarding_setting['send_relationship_manager_template_id'])?$sms_forwarding_setting['send_relationship_manager_template_id']:$default_template_auto_id;						        	
													$sms_send_data[]=array('mobile'=>$relationship_manager_mobile,'template_auto_id'=>$relationship_manager_template_auto_id);
												}	
												
												$manager_mobile='';
												if($sms_forwarding_setting['is_send_manager']=='Y')
												{
													if($m_mobile['manager_mobile']!='')
													{		      
														$manager_mobile=$m_mobile['manager_mobile'];
														$manager_template_auto_id=($sms_forwarding_setting['send_manager_template_id'])?$sms_forwarding_setting['send_manager_template_id']:$default_template_auto_id;
														$sms_send_data[]=array('mobile'=>$manager_mobile,'template_auto_id'=>$manager_template_auto_id);
													}		        	
												}

												$skip_manager_mobile='';
												if($sms_forwarding_setting['is_send_skip_manager']=='Y')
												{
													if($m_mobile['skip_manager_mobile']!='')
													{		        		
														$skip_manager_mobile=$m_mobile['skip_manager_mobile'];
														$skip_manager_template_auto_id=($sms_forwarding_setting['send_skip_manager_template_id'])?$sms_forwarding_setting['send_skip_manager_template_id']:$default_template_auto_id;
														$sms_send_data[]=array('mobile'=>$skip_manager_mobile,'template_auto_id'=>$skip_manager_template_auto_id);
													}		        	
												}
												// to sms send logic	
												// --------------------
												$return=sms_send($sms_send_data,$sms_variable_info,$client_info);
											}
										}									
									}
								}
							}
							else
							{

							}
						}			
					}
				}
			}
			
			if(count($indiamart_credentials)>0)
			{
				$msg="Process completed to fetch leads from indiamart.";
				$status='success';
			}
			else
			{
				$msg="imdiamart credentials not available";
				$status='fail';
			}
			echo $status.'#'.$msg;			
			// ==========================================			
			$post_data=array(
					'client_id'=>$client_info->client_id,
                    'function_name'=>'get_lead_from_indiamart',
                    'updated_db_name'=>$client_info->db_name,
					'msg'=>$msg_log,
                    'created_at'=>date("Y-m-d H:i:s")
                    );
			
			// $this->Client_model->create_cronjobs_log($post_data);			
		}		
	}	
	
	//die('completed');	
}
*/
function get_lead_from_tradeindia()
{	
	date_default_timezone_set('Asia/Kolkata');
	$current_datetime=date("Y-m-d");
	// $from_date = date("d-M-Y H:i:s",strtotime("-30 minutes", strtotime($current_datetime)));
	// $to_date=date("d-M-Y H:i:s",strtotime($current_datetime));		
	// $from_date='2020-10-01';
	// $to_date='2020-10-06';

	// $from_date=date("Y-m-d",strtotime("-1 days", strtotime($current_datetime)));
	$from_date=date("Y-m-d",strtotime($current_datetime));	
	$to_date=date("Y-m-d",strtotime($current_datetime));

	
	//$client_id=$this->Client_model->get_cid_for_execute('get_lead_from_tradeindia');
	

	$arg=array();
	$arg['cronjobs_action']='tradeindia';
	//$arg['client_id']=$client_id;
	$client_db_info_list=$this->Client_model->get_all($arg);
	// print_r($client_db_info_list); die();
	if(count($client_db_info_list))
	{	
		foreach($client_db_info_list AS $client_info)
		{
			
			//$company=get_company_profile();			
			$tradeindia_credentials=$this->Tradeindia_setting_model->GetCredentials($client_info);
			// print_r($tradeindia_credentials); die();

			// Lead Acknowledgement id 1
			$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(1,$client_info);
			
			if(count($tradeindia_credentials)>0)
			{
				$this->Tradeindia_model->truncate($client_info);

				foreach($tradeindia_credentials AS $ti)
				{
					$tradeindia_setting_id=$ti['id'];
					// $indiamart_assign_to=unserialize($im['assign_to']);

					$userid=$ti['userid'];
					$profileid=$ti['profileid'];	
					$ti_key=$ti['ti_key'];		
					// echo $url = "https://www.tradeindia.com/utils/my_inquiry.html?userid=".$userid."&profile_id=".$profileid."&key=".$ti_key."&from_date=".$from_date."&to_date=".$to_date;die();
					// $url = "https://www.tradeindia.com/utils/my_inquiry.html?userid=15036306&profile_id=31347065&key=572e8f62ad1b119f134e1c65bb3143f0&from_date=2021-05-20&to_date=2021-05-21"
					$url = "https://www.tradeindia.com/utils/my_inquiry.html";	
					// echo $url;die();
						
					$useragent = 'PHP Client 1.0 (curl) ' . phpversion();
					$post_string="userid=".$userid."&profile_id=".$profileid."&key=".$ti_key."&from_date=".$from_date."&to_date=".$to_date;
					$url_with_get=$url;
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url_with_get);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
					curl_setopt($ch, CURLOPT_TIMEOUT, 30);
					$result = curl_exec($ch);
					curl_close($ch);
					$return_tmp = $result;
					$return_arr=json_decode($return_tmp);	
					// print_r($return_arr);				
					// die('ok');
					$i=0;
					if(!isset($return_arr[0]->Error_Message))
					{
						if($return_arr)
						{
							
							// $needle_arr=get_tradeindia_email_exclude();
							foreach($return_arr as $row)
							{				
												
								$sender_email=trim($row->sender_email);
								$sender_mobile=$row->sender_mobile;
								$is_noreply='N';
								// -------------------------------------------------
								// EMAIL EXCLUDE STRING
								if($sender_email=='noreply@noreply.tradeindia.com'){
									$is_noreply='Y';
									$sender_email='';										
								}
								// if(count($needle_arr))
								// {							
									// foreach($needle_arr AS $needle)
									// {
									// 	if(strpos($sender_email,$needle)!==false){
									// 		$sender_email='';
									// 		break;
									// 	}
									// }
									// foreach($needle_arr AS $needle)
									// {
									// 	if($needle=='noreply@noreply.tradeindia.com'){
									// 		$is_noreply='Y';
									// 		break;
									// 	}
									// }									
								// }
								if($is_noreply=='Y')
								{
									$message=strip_tags($row->message);
									$mobile_arr=explode("+",$message);
									$mobile_str=$mobile_arr[1];
									$sender_mobile=substr($mobile_str,2, 10);
								}
								// EMAIL EXCLUDE STRING
								// -------------------------------------------------
								if($sender_email!='' || $sender_mobile!='')
								{
									$post=array(
											'tradeindia_setting_id'=>$tradeindia_setting_id,
											'source'=>$row->source,
											'receiver_name'=>$row->receiver_name,
											'address'=>$row->address,
											'generated_on'=>$row->generated,
											'generated_date'=>$row->generated_date,
											'generated_time'=>$row->generated_time,
											'sender_mobile'=>$sender_mobile,
											'sender_co'=>$row->sender_co,
											'receiver_uid'=>$row->receiver_uid,
											'inquiry_type'=>$row->inquiry_type,
											'sender_country'=>$row->sender_country,
											'ago_time'=>$row->ago_time,
											'message'=>nl2br($row->message),
											'sender_state'=>$row->sender_state,
											'subject'=>$row->subject,
											'sender_city'=>$row->sender_city,
											'product_source'=>$row->product_source,
											'view_status'=>$row->view_status,
											'rfi_id'=>$row->rfi_id,
											'month_slot'=>$row->month_slot,
											'sender_name'=>$row->sender_name,
											'sender_uid'=>$row->sender_uid,
											'sender'=>$row->sender,
											'receiver_mobile'=>$row->receiver_mobile,
											'product_name'=>$row->product_name,
											'receiver_co'=>$row->receiver_co,
											'sender_email'=>$sender_email,
											'sender_other_emails'=>$row->sender_other_emails,
											'website'=>$row->website,
											'landline_number'=>$row->landline_number
										);
									// print_r($post); die();
									$this->Tradeindia_model->insert($post,$client_info);
								}								
								$i++;				
							}
						}
						
					}
					// echo $i;die();
				}
			}	
					
			

			if(count($tradeindia_credentials)>0)
			{			
				foreach($tradeindia_credentials AS $credentials)
				{				
					$get_ti=$this->Tradeindia_model->get_rows($credentials['id'],$client_info);	
							
					if(count($get_ti))
					{					
						// $tradeindia_assign_to=unserialize($credentials['assign_to']);
						// $assign_start=isset($credentials['assign_start'])?$credentials['assign_start']:0;
						// $assign_end=(count($tradeindia_assign_to)-1);

						if($credentials['assign_rule']=='1')
						{
							$tradeindia_assign_to=unserialize($credentials['assign_to']);
							$assign_start=isset($credentials['assign_start'])?$credentials['assign_start']:0;
							$assign_end=(count($tradeindia_assign_to)-1);
						}

						foreach($get_ti as $ti)
						{						
							// echo $ti['message']; die();
							$cust_email=$ti['sender_email'];				
							$cust_mobile_tmp=end(explode("-", $ti['sender_mobile']));
							$cust_mobile=str_replace("+91","",$cust_mobile_tmp);
							$rfi_id=$ti['rfi_id'];
							//echo $im['MOB'].' / '.$cust_mobile.'<br>';
							$cust_arr=array(
											'email'=>$cust_email,
											'mobile'=>$cust_mobile,
											'rfi_id'=>$rfi_id
											);
							$get_customer_decision=$this->Customer_model->get_decision_for_indiamart($cust_arr,$client_info);

							// ------------------------
							// get message 
							$ti_history_data=array('msg'=>$get_customer_decision['msg']);
							$this->Tradeindia_model->update($ti_history_data,$ti['id'],$client_info);
							// ------------------------

							if($get_customer_decision['status']==TRUE)
							{
								
								$com_country_code='';
								$com_country_id='';
								if($ti['sender_country'])
								{
									$get_country=$this->Countries_model->get_country_by_name($ti['sender_country'],$client_info);
									if($get_country!=false)
									{
										$com_country_id=$get_country->id;
										$com_country_code=$get_country->phonecode;

									}
								}
								
								$assigned_user_id=isset($tradeindia_assign_to[$assign_start])?$tradeindia_assign_to[$assign_start]:$tradeindia_assign_to[0];
								$tradeindia_setting_id=$ti['tradeindia_setting_id'];
								//$com_contact_person=$ti['sender_name'];
								$com_contact_person=($ti['sender_name'])?$ti['sender_name']:'Purchase Manager';
								$com_designation='Manager';
								$com_email=$cust_email;
								$com_alternate_email=$ti['sender_other_emails'];
								$com_mobile_country_code=$com_country_code;
								$com_mobile=$cust_mobile;
								$com_alt_mobile_country_code=$com_country_code;
								$com_alternate_mobile='';
								$com_landline_country_code=$com_country_code;
								$com_landline_std_code='';
								$landline_number='';
								$com_website=$ti['website'];
								$com_company_name=$ti['sender_co'];
								$com_address=$ti['address'];
								$com_city_id='';
								if($ti['sender_city'])
								{
									$get_city_id=$this->Cities_model->get_city_id_by_name($ti['sender_city'],$client_info);
									if($get_city_id!=false)
									{
										$com_city_id=$get_city_id;
									}
								}
								$com_state_id='';	
								if($ti['sender_state'])
								{
									$get_state_id=$this->States_model->get_state_id_by_name($ti['sender_state'],$client_info);
									if($get_state_id!=false)
									{
										$com_state_id=$get_state_id;
									}
								}				
								$com_zip='';
								$com_short_description='';
								if($ti['source'])
								{
									if(strtoupper($ti['source'])=='PHONE_INQUIRY'){
										$com_source_text='Tradeindia Call Enquiry';
									}		
									else{
										$com_source_text='Tradeindia Enquiry';
									}	

									
									$com_source_text_tmp = str_replace(' ', '', strtolower($com_source_text));
									$com_source_id=$this->Source_model->get_source_id_by_name($com_source_text_tmp,$client_info);
									if($com_source_id==0)
									{
										$post_source=array(
															'parent_id'=>0,
															'name'=>$com_source_text
															);
										$com_source_id=$this->Source_model->add($post_source,$client_info);
									}
								}
								
								// -------------------
								// LEAD INFI
								// LEAD
								$lead_title=$ti['subject'];
								$lead_requirement='';
								$lead_requirement .='<h6><B>Lead Title:</B> '.$ti['subject'].'</h6>';							
								if($ti['product_name']){
									$lead_requirement .='<B>Product Name:</B> '.$ti['product_name'].'<BR>';
								}
								$lead_requirement .='<BR>';
								if($ti['message']){
									$lead_requirement .='<B>Buying Requirement:</B><BR>';
									$lead_requirement .=''.$ti['message'].'<BR>';
								}

								if($ti['address'])
								{
									$lead_requirement .='<B>Buyer\'s Address:</B>';
									$lead_requirement .=' '.$ti['address'].'';

									if($ti['sender_city']){
										$lead_requirement .=', '.$ti['sender_city'].'';
									}
									
									if($ti['sender_state']){
										$lead_requirement .=', '.$ti['sender_state'].'';
									}

									if($ti['sender_country']){
										$lead_requirement .=', '.$ti['sender_country'].'';
									}	
									$lead_requirement .='<BR>';									
								}
								else
								{
									$lead_requirement .='<B>Buyer\'s Address:</B>';	

									if($ti['sender_city']){
										$lead_requirement .=' '.$ti['sender_city'].'';
									}
									
									if($ti['sender_state']){
										$lead_requirement .=', '.$ti['sender_state'].'';
									}

									if($ti['sender_country']){
										$lead_requirement .=', '.$ti['sender_country'].'';
									}	
									$lead_requirement .='<BR>';	
								}
								
								if($ti['sender_email']){
									$lead_requirement .='<B>Buyer\'s Email:</B>';
									$lead_requirement .=' '.$ti['sender_email'].'';

									if($ti['sender_other_emails'])
									{
										$lead_requirement .='/'.$ti['sender_other_emails'].'';
									}
									$lead_requirement .='<BR>';								
								}

								if($ti['generated_date']){
									$lead_requirement .='<B>Lead Date:</B>';
									$lead_requirement .=' '.$ti['generated_date'].'<BR>';							
								}

								if($ti['generated_time']){
									$lead_requirement .='<B>Lead Time:</B>';
									$lead_requirement .=' '.$ti['generated_time'].'<BR>';							
								}

								if($ti['source']){
									$lead_requirement .='<B>Source:</B>';
									$lead_requirement .=' '.$ti['source'].'( '.$com_source_text.' )<BR>';							
								}

								if($ti['rfi_id']){
									$lead_requirement .='<B>Rfi id:</B>';
									$lead_requirement .=' '.$ti['rfi_id'].'<BR>';							
								}

								// if($ti['sender_uid']){
								// 	$lead_requirement .='<B>User id:</B>';
								// 	$lead_requirement .=' '.$ti['sender_uid'].'<BR>';							
								// }
								// LEAD INFO
								// --------------------

								// -------------------
								// RULE WISE USER
								if($credentials['assign_rule']=='1')
								{
									$assigned_user_id=isset($tradeindia_assign_to[$assign_start])?$tradeindia_assign_to[$assign_start]:$tradeindia_assign_to[0];
								}
								else if($credentials['assign_rule']==5)// KEYWORD 
								{			
									$title_tmp=str_replace(' ', '', strtolower($lead_title));
									$lead_requirement_tmp=str_replace(' ', '', strtolower($lead_requirement));				
									$search_keyword=$title_tmp.''.$lead_requirement_tmp;								
									$arr=array();
									$arr['assign_rule']=$credentials['assign_rule'];
									$arr['tradeindia_setting_id']=$tradeindia_setting_id;
									$arr['search_keyword']=$search_keyword;		
									$assigned_user_id=$this->Tradeindia_setting_model->get_keyword_rule5_wise_assigned_user_id($arr,$client_info);
								}
								else
								{

									if($credentials['assign_rule']==2)
									{
										$search_keyword=$com_country_id;
									}
									else if($credentials['assign_rule']==3)
									{
										$search_keyword=$com_state_id;
									}
									else if($credentials['assign_rule']==4)
									{
										$search_keyword=$com_city_id;
									}
									$arr=array();
									$arr['assign_rule']=$credentials['assign_rule'];
									$arr['tradeindia_setting_id']=$ti['tradeindia_setting_id'];
									$arr['search_keyword']=$search_keyword;	
									$assigned_user_id=$this->Tradeindia_setting_model->get_rule_wise_assigned_user_id($arr,$client_info);
								}				
								// RULE WISE USER			
								// ---------------------

								// ===================
								// CUSTOMER DETAILS
								$com_contact_person_tmp='';		
								$is_blacklist='N';					
								if($get_customer_decision['msg']=='no_customer_exist')
								{

									$company_post_data=array(
										'assigned_user_id'=>$assigned_user_id,
										'contact_person'=>$com_contact_person,
										'designation'=>$com_designation,
										'email'=>$com_email,
										'alt_email'=>$com_alternate_email,
										'mobile_country_code'=>$com_mobile_country_code,
										'mobile'=>$com_mobile,
										'alt_mobile_country_code'=>$com_alt_mobile_country_code,
										'alt_mobile'=>$com_alternate_mobile,
										'landline_country_code'=>$com_landline_country_code,
										'landline_std_code'=>$com_landline_std_code,
										'landline_number'=>$landline_number,
										'office_country_code'=>$com_country_code,
										'website'=>$com_website,
										'company_name'=>$com_company_name,
										'address'=>$com_address,
										'city'=>$com_city_id,
										'state'=>$com_state_id,
										'country_id'=>$com_country_id,
										'zip'=>$com_zip,
										'create_date'=>date("Y-m-d H:i:s"),
										'short_description'=>$com_short_description,
										'source_id'=>$com_source_id,
										'modify_date'=>date('Y-m-d'),
										'status'=>'1'
									);
									// print_r($company_post_data); die();
									$com_company_id=$this->Cronjobs_model->CreateCustomer($company_post_data,$client_info);

									// -------------------
									// RULE WISE USER
									if($credentials['assign_rule']=='1')
									{
										$assign_start++;
										if($assign_start>$assign_end)
										{
											$assign_start=0;
										}

										// ---------------------------------
										// update to setting table
										$tmpdata=array('assign_start'=>$assign_start);
										$this->Tradeindia_setting_model->EditCredentials($tmpdata,$tradeindia_setting_id,$client_info);
										// update to setting table
										// ----------------------------------
									}
									// RULE WISE USER
									// -------------------
									

									// $assign_start++;
									// if($assign_start>$assign_end)
									// {
									// 	$assign_start=0;
									// }

									// -------------------------------------------
									// update to setting table
									// $tmpdata=array('assign_start'=>$assign_start);
									// $this->Tradeindia_setting_model->EditCredentials($tmpdata,$tradeindia_setting_id,$client_info);
									// update to setting table
									// -------------------------------------------

									$com_contact_person_tmp=$com_contact_person;
									
								}
								else if($get_customer_decision['msg']=='one_customer_exist')
								{
									$com_company_id=$get_customer_decision['customer_id'];
									$get_existing_com=$this->Customer_model->get_company_detail($com_company_id,$client_info);
									$com_source_id=$get_existing_com['source_id'];
									$assigned_user_id=$get_existing_com['assigned_user_id'];
									$is_blacklist=($get_existing_com['is_blacklist'])?$get_existing_com['is_blacklist']:'N';
									$company_post_data=array(
										'contact_person'=>($get_existing_com['contact_person'])?$get_existing_com['contact_person']:$com_contact_person,
										'designation'=>($get_existing_com['designation'])?$get_existing_com['designation']:$com_designation,
										'alt_email'=>($get_existing_com['alt_email'])?$get_existing_com['alt_email']:$com_alternate_email,
										'alt_mobile_country_code'=>($get_existing_com['alt_mobile_country_code'])?$get_existing_com['alt_mobile_country_code']:$com_alt_mobile_country_code,
										'alt_mobile'=>($get_existing_com['alt_mobile'])?$get_existing_com['alt_mobile']:$com_alternate_mobile,
										'landline_country_code'=>($get_existing_com['landline_country_code'])?$get_existing_com['landline_country_code']:$com_landline_country_code,
										'landline_std_code'=>($get_existing_com['landline_std_code'])?$get_existing_com['landline_std_code']:$com_landline_std_code,
										'landline_number'=>($get_existing_com['landline_number'])?$get_existing_com['landline_number']:$landline_number,
										'website'=>($get_existing_com['website'])?$get_existing_com['website']:$com_website,
										'company_name'=>($get_existing_com['company_name'])?$get_existing_com['company_name']:$com_company_name,
										'address'=>($get_existing_com['address'])?$get_existing_com['address']:$com_address,
										'city'=>($get_existing_com['city']>0)?$get_existing_com['city']:$com_city_id,
										'state'=>($get_existing_com['state']>0)?$get_existing_com['state']:$com_state_id,
										'country_id'=>($get_existing_com['country_id']>0)?$get_existing_com['country_id']:$com_country_id,
										'zip'=>($get_existing_com['zip']>0)?$get_existing_com['zip']:$com_zip,
										'short_description'=>($get_existing_com['short_description'])?$get_existing_com['short_description']:$com_short_description,
										'source_id'=>($get_existing_com['source_id']>0)?$get_existing_com['source_id']:$com_source_id,
										'modify_date'=>date('Y-m-d'),
										'status'=>'1'
									);

									$this->Cronjobs_model->UpdateCustomer($company_post_data,$com_company_id,$client_info);

									$com_contact_person_tmp=($get_existing_com['contact_person'])?$get_existing_com['contact_person']:$com_contact_person;								
								}
								
								if($is_blacklist=='N')
								{
																

									$lead_enq_date=date("Y-m-d",$ti['generated_on']);

									$lead_post_data=array(
													'title'=>$lead_title,
													'customer_id'=>$com_company_id,
													'source_id'=>$com_source_id,
													'assigned_user_id'=>$assigned_user_id,
													'buying_requirement'=>$lead_requirement,
													'enquiry_date'=>$lead_enq_date,
													'followup_date'=>date('Y-m-d'),
													'create_date'=>date('Y-m-d'),
													'modify_date'=>date('Y-m-d'),
													'assigned_date'=>date('Y-m-d'),
													'status'=>'1',
													'current_stage_id'=>'1',
													'current_stage'=>'PENDING',
													'current_stage_wise_msg'=>'',
													'current_status_id'=>'1',
													'current_status'=>'WARM',
													'ti_sender_uid'=>$ti['sender_uid'],
													'ti_rfi_id'=>$ti['rfi_id'],
													'ti_setting_id'=>$tradeindia_setting_id
												);
									$lead_id=$this->Lead_model->CreateLead($lead_post_data,$client_info);
									if($lead_id)
									{
										// ---------------------------
										// PRODUCT TAGGED
										// if($ti['product_name'])
										// {
										// 	$lead_p_data=array(
										// 		'lead_id'=>$lead_id,
										// 		'name'=>$ti['product_name']
										// 	);
										// 	$this->Lead_model->CreateLeadWiseProductTag($lead_p_data,$client_info);
										// }								
										// PRODUCT TAGGED
										// ---------------------------
										
										// Insert Stage Log
										$stage_post_data=array(
												'lead_id'=>$lead_id,
												'stage_id'=>'1',
												'stage'=>'PENDING',
												'stage_wise_msg'=>'',
												'create_datetime'=>date("Y-m-d H:i:s")
											);
										$this->Lead_model->CreateLeadStageLog($stage_post_data,$client_info);

										// Insert Status Log
										$status_post_data=array(
												'lead_id'=>$lead_id,
												'status_id'=>'2',
												'status'=>'WARM',
												'create_datetime'=>date("Y-m-d H:i:s")
											);
										$this->Lead_model->CreateLeadStatusLog($status_post_data,$client_info);

										// echo $lead_requirement;
										// echo'<br>--------------<br>';
										$attach_filename='';
										$assigned_by_user_id=1;
										// -------------------------------------------------
										// ASSIGN LEAD LOG TABLE
										$post_data=array(
													'lead_id'=>$lead_id,
													'assigned_to_user_id'=>$assigned_user_id,
													'assigned_by_user_id'=>$assigned_by_user_id,
													'is_accepted'=>'Y',
													'assigned_datetime'=>date("Y-m-d H:i:s")
													);			
										$this->Lead_model->create_lead_assigned_user_log($post_data,$client_info);
										
									

										$update_by=1;				
										$date=date("Y-m-d H:i:s");				
										$ip_addr=$_SERVER['REMOTE_ADDR'];				
										$message="A new lead has been created as &quot;".$lead_title."&quot;";
										$comment_title=NEW_LEAD_CREATE_MANUAL;
										$historydata=array(
															'title'=>$comment_title,
															'lead_id'=>$lead_id,
															'comment'=>addslashes($message),
															'attach_file'=>$attach_filename,
															'create_date'=>$date,
															'user_id'=>$update_by,
															'ip_address'=>$ip_addr
														);
										$this->History_model->CreateHistory($historydata,$client_info);

										
										if($email_forwarding_setting['is_mail_send']=='Y')
										{
											// ============================
											// Mail Acknowledgement & 
											// account manager update
											// START

											// TO CLIENT				
											$e_data=array();
											//$user_id=$this->session->userdata['logged_in']['user_id'];
											$assigned_to_user_data=$this->User_model->get_employee_details($assigned_user_id,$client_info);
											$company=get_company_profile($client_info);	
											$lead_info=$this->Lead_model->GetLeadData($lead_id,$client_info);
											$customer=$this->Customer_model->GetCustomerData($lead_info->customer_id,$client_info);
											$e_data['company']=$company;
											$e_data['assigned_to_user']=$assigned_to_user_data;
											$e_data['customer']=$customer;
											$e_data['lead_info']=$lead_info;
											$e_data['client_info']=$client_info;
											
											// $template_str_tmp=FCPATH .'application/views/admin/email_template/template/enquiry_acknowledgement_cronjobs_view_'.$client_info->id.'.php';
											// if (file_exists($template_str_tmp)){
											// 	$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_cronjobs_view_'.$client_info->id, $e_data, true);
											// }else{
											// 	$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_cronjobs_view', $e_data, true);
											// }
											$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_cronjobs_view', $e_data, true);
											
											$m_email=get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);
											// --------------------
											// to mail assign logic
											$to_mail_assign='';
											$to_mail='';
											if($email_forwarding_setting['is_send_mail_to_client']=='Y')
											{
												$to_mail=$customer->email;
												$to_mail_assign='client';
											}
											else
											{
												if($m_email['manager_email']!='' && $email_forwarding_setting['is_send_manager']=='Y')
												{
													$to_mail=$m_email['manager_email'];
													$to_mail_assign='manager';
												}
												else
												{
													if($m_email['skip_manager_email']!='' && $email_forwarding_setting['is_send_skip_manager']=='Y')
													{
														$to_mail=$m_email['skip_manager_email'];
														$to_mail_assign='skip_manager';
													}
												}
											}
											// to mail assign logic
											// --------------------
											$cc_mail_arr=array();
											$self_cc_mail=get_value("email","user","id=".$assigned_user_id,$client_info);
											$update_by_name=get_value("name","user","id=".$assigned_user_id,$client_info);
											// --------------------
											// cc mail assign logic
											if($email_forwarding_setting['is_send_relationship_manager']=='Y')
											{
												if($to_mail=='')
												{
													$to_mail=$self_cc_mail;
												}
												else
												{
													array_push($cc_mail_arr, $self_cc_mail);
												}		        	
											}

											if($email_forwarding_setting['is_send_manager']=='Y')
											{
												if($m_email['manager_email']!='' && $to_mail_assign!='manager')
												{		        		
													array_push($cc_mail_arr, $m_email['manager_email']);
												}		        	
											}

											if($email_forwarding_setting['is_send_skip_manager']=='Y')
											{
												if($m_email['skip_manager_email']!='' && $to_mail_assign!='skip_manager')
												{		        		
													array_push($cc_mail_arr, $m_email['skip_manager_email']);
												}		        	
											}
											$cc_mail='';
											$cc_mail=implode(",", $cc_mail_arr);
											// cc mail assign logic
											// --------------------

											
														
											if($to_mail)
											{
												$com_company_name_tmp=$company['name'];
												if($ti['product_name']!=NULL && $com_contact_person_tmp!='')
												{
													$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' for '.$ti['product_name'].' has been received - '.$com_company_name_tmp; 
												}
												else if($ti['product_name']!=NULL && $com_contact_person_tmp=='')
												{
													$mail_subject='Enquiry # '.$lead_id.' for '.$ti['product_name'].' has been received - '.$com_company_name_tmp; 
												}
												else if($ti['product_name']==NULL && $com_contact_person_tmp!='')
												{
													$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
												}
												else if($ti['product_name']==NULL && $com_contact_person_tmp=='')
												{
													$mail_subject='Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
												}
												else
												{
													$mail_subject='Your Enquiry # '.$lead_id.' has been acknowledged';
												}

												
												//$this->load->library('mail_lib');
												$m_d = array();
												$m_d['from_mail']     = $self_cc_mail;
												$m_d['from_name']     = $update_by_name;
												$m_d['to_mail']       = $to_mail;
												$m_d['cc_mail']       = $cc_mail;
												$m_d['subject']       = $mail_subject;
												$m_d['message']       = $template_str;
												$m_d['attach']        = array();
												$mail_return = $this->mail_lib->send_mail($m_d,$client_info);
											}
											// END
											// =============================
										}
									}
								}
							}
							else
							{

							}
						}			
					}
				}
			}
			
			if($tradeindia_credentials>0)
			{
				$msg="Tradeindia credentials available.";
			}
			else
			{
				$msg="Tradeindia credentials not available";
			}
			
			// ==============================================

			$post_data=array(
					'client_id'=>$client_info->client_id,
                    'function_name'=>'get_lead_from_tradeindia',
                    'updated_db_name'=>$client_info->db_name,
					'msg'=>$msg,
                    'created_at'=>date("Y-m-d H:i:s")
                    );
			// $this->Client_model->create_cronjobs_log($post_data);

			
	        // $mail_data = array();        
	        // $mail_data['from_mail']     = 'info@lmsbaba.com';
	        // $mail_data['from_name']     = 'lmsbaba';
	        // $mail_data['to_mail']       = 'arupporel123@gmail.com';
	        // $mail_data['subject']       = 'Tradeindia -'.$client_info->db_name;
	        // $mail_data['message']       = 'Tradeindia for '.$client_info->db_name;
	        // $mail_data['attach']        = array();
	        // $r=$this->mail_lib->send_mail($mail_data,$client_info);
	        // if($r==true)
	        // {
	        // 	echo "<br>sent for ".$client_info->db_name;
	        // }
	        // else
	        // {
	        // 	echo '<br>not sent for '.$client_info->db_name;
	        // }
			// ================================================
			//sleep(5);	

		}
		die('Completed');
	}
	
}


function get_lead_from_aajjo($client_id='')
{	
	date_default_timezone_set('Asia/Kolkata');
	$current_datetime=date("Y-m-d");
	$from_date = date("Y-m-d",strtotime("-1 days", strtotime($current_datetime)));
	$to_date=date("Y-m-d",strtotime($current_datetime));	
	
	$arg=array();
	$arg['cronjobs_action']='aajjo';
	$arg['client_id']=$client_id;
	$client_db_info_list=$this->Client_model->get_all($arg);
	
	if(count($client_db_info_list))
	{	
		foreach($client_db_info_list AS $client_info)
		{				
			$aajjo_credentials=$this->Aajjo_setting_model->GetCredentials($client_info);		

			// Lead Acknowledgement id 1
			$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(1,$client_info);
			
			if(count($aajjo_credentials)>0)
			{
				// $this->Aajjo_model->truncate($client_info);

				foreach($aajjo_credentials AS $aj)
				{
					$aajjo_setting_id=$aj['id'];
					// $indiamart_assign_to=unserialize($im['assign_to']);

					$username=$aj['username'];					
					$aj_key=$aj['aj_key'];
					$url = "https://api.aajjo.com/api/cl/getleads?StartDate=$from_date";	
					// echo $url;die();
					$curl = curl_init();
					curl_setopt_array($curl, [
						CURLOPT_URL => $url,
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => "",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 30,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => "POST",
						CURLOPT_HTTPHEADER => [
							"Authorization: Basic $username:$aj_key",
						  'Content-Length: 0'
						],
					]);
					$response = curl_exec($curl);
					$err = curl_error($curl);
					curl_close($curl);
					if ($err) 
					{
						// echo "cURL Error #:" . $err;die('ok');
					} 
					else 
					{
						$return_tmp = $response;
						$return_arr=json_decode($return_tmp);
						// echo count($return_arr->Records); die('okkk123');
						$i=0;
						if(count($return_arr->Records)>0)
						{
							
							foreach($return_arr->Records as $row)
							{				
												
								$sender_email=trim($row->EmailID);
								$sender_mobile=$row->PhoneNumber;

								if($sender_email!='' || $sender_mobile!='')
								{
									$arr=array();
									$arr['created_date']=$row->CreatedDate;
									$is_already_exist=$this->Aajjo_model->is_record_already_exist($arr,$client_info);
									if($is_already_exist['exist']=='N')
									{
										$post=array(
											'aajjo_setting_id'=>$aajjo_setting_id,
											'contact_person'=>$row->ContactPerson,
											'product'=>$row->Product,
											'email_id'=>$row->EmailID,
											'phone_number'=>$row->PhoneNumber,
											'city'=>$row->City,
											'state_name'=>$row->StateName,
											'lead_address'=>$row->LeadAddress,
											'country_name'=>$row->CountryName,
											'lead_details'=>$row->LeadDetails,
											'created_date'=>$row->CreatedDate,
											'consumed_date'=>$row->ConsumedDate,
											'lead_type'=>$row->LeadType,
											'created_at'=>date("Y-m-d H:i:s")
										);
										// print_r($post); die();
										$this->Aajjo_model->insert($post,$client_info);
									}
									else
									{	
										if($is_already_exist['is_deleted']=='N')
										{
											$post=array('is_deleted'=>'Y');
											$this->Aajjo_model->update($post,$is_already_exist['id'],$client_info);
										}										
									}									
								}								
								$i++;				
							}
						}
						
					}				
					
				}
			}	
			
			

			if(count($aajjo_credentials)>0)
			{			
				foreach($aajjo_credentials AS $credentials)
				{				
					$get_aj=$this->Aajjo_model->get_rows($credentials['id'],$client_info);	
					
					if(count($get_aj))
					{
						if($credentials['assign_rule']=='1')
						{
							$aajjo_assign_to=unserialize($credentials['assign_to']);
							$assign_start=isset($credentials['assign_start'])?$credentials['assign_start']:0;
							$assign_end=(count($aajjo_assign_to)-1);
						}

						foreach($get_aj as $aj)
						{
							$cust_email=$aj['email_id'];				
							$cust_mobile_tmp=end(explode("-", $aj['phone_number']));
							$cust_mobile=str_replace("+91","",$cust_mobile_tmp);							
							
							$cust_arr=array(
											'email'=>$cust_email,
											'mobile'=>$cust_mobile
											);
							$get_customer_decision=$this->Customer_model->get_decision_for_aajjo($cust_arr,$client_info);

							// ------------------------
							// get message 
							$aj_history_data=array('msg'=>$get_customer_decision['msg']);
							$this->Aajjo_model->update($aj_history_data,$aj['id'],$client_info);
							// ------------------------

							if($get_customer_decision['status']==TRUE)
							{								
								$com_country_code='';
								$com_country_id='';
								if($aj['country_name'])
								{
									$get_country=$this->Countries_model->get_country_by_name($aj['country_name'],$client_info);
									if($get_country!=false)
									{
										$com_country_id=$get_country->id;
										$com_country_code=$get_country->phonecode;
									}
								}
								
								$assigned_user_id=isset($aajjo_assign_to[$assign_start])?$aajjo_assign_to[$assign_start]:$aajjo_assign_to[0];
								$aajjo_setting_id=$aj['aajjo_setting_id'];								
								$com_contact_person=($aj['contact_person'])?$aj['contact_person']:'Purchase Manager';
								$com_designation='Manager';
								$com_email=$cust_email;
								$com_alternate_email='';
								$com_mobile_country_code=$com_country_code;
								$com_mobile=$cust_mobile;
								$com_alt_mobile_country_code=$com_country_code;
								$com_alternate_mobile='';
								$com_landline_country_code=$com_country_code;
								$com_landline_std_code='';
								$landline_number='';
								$com_website='';
								$com_company_name='';
								$com_address=$aj['lead_address'];
								$com_city_id='';
								$com_state_id='';
								if($aj['city'])
								{
									$get_city_id=$this->Cities_model->get_city_id_by_name($aj['city'],$client_info);
									if($get_city_id!=false)
									{
										$com_city_id=$get_city_id;
										// GET STATE
										$city_info_tmp=$this->Cities_model->get_city_by_id($com_city_id,$client_info);
										if(count($city_info_tmp)>0)
										{
											$com_state_id=$city_info_tmp->state_id;
										}										
									}
								}
									
								if($aj['state_name']!='' && $com_state_id=='')
								{
									$get_state_id=$this->States_model->get_state_id_by_name($aj['state_name'],$client_info);
									if($get_state_id!=false)
									{
										$com_state_id=$get_state_id;
									}
								}				
								$com_zip='';
								$com_short_description='';
								// if($aj['source'])
								// {
									$com_source_text='Aajjo';
									$com_source_text_tmp = str_replace(' ', '', strtolower($com_source_text));
									$com_source_id=$this->Source_model->get_source_id_by_name($com_source_text_tmp,$client_info);
									if($com_source_id==0)
									{
										$post_source=array(
															'parent_id'=>0,
															'name'=>$com_source_text
															);
										$com_source_id=$this->Source_model->add($post_source,$client_info);
									}
								// }
								
								// -------------------
								// LEAD INFI
								// LEAD
								$lead_title=$aj['product'];
								$lead_requirement='';
								$lead_requirement .='<h6><B>Lead Title:</B> '.$aj['product'].'</h6>';							
								if($aj['product']){
									$lead_requirement .='<B>Product Name:</B> '.$aj['product'].'<BR>';
								}
								$lead_requirement .='<BR>';
								if($aj['lead_details']){
									$lead_requirement .='<B>Buying Requirement:</B><BR>';
									$lead_requirement .=''.$aj['lead_details'].'<BR>';
								}

								if($aj['lead_address'])
								{
									$lead_requirement .='<B>Buyer\'s Address:</B>';
									$lead_requirement .=' '.$aj['lead_address'].'';

									if($aj['city']){
										$lead_requirement .=', '.$aj['city'].'';
									}
									
									if($aj['state_name']){
										$lead_requirement .=', '.$aj['state_name'].'';
									}

									if($aj['country_name']){
										$lead_requirement .=', '.$aj['country_name'].'';
									}	
									$lead_requirement .='<BR>';									
								}
								else
								{
									$lead_requirement .='<B>Buyer\'s Address:</B>';	

									if($aj['city']){
										$lead_requirement .=' '.$aj['city'].'';
									}
									
									if($aj['state_name']){
										$lead_requirement .=', '.$aj['state_name'].'';
									}

									if($aj['country_name']){
										$lead_requirement .=', '.$aj['country_name'].'';
									}	
									$lead_requirement .='<BR>';	
								}
								
								if($aj['email_id']){
									$lead_requirement .='<B>Buyer\'s Email:</B>';
									$lead_requirement .=' '.$aj['email_id'].'';									
									$lead_requirement .='<BR>';								
								}

								if($aj['created_date']){
									$lead_requirement .='<B>Lead Date:</B>';
									$lead_requirement .=' '.date("d-M-Y g:i A",strtotime($aj['created_date'])).'<BR>';							
								}
								// LEAD INFO
								// --------------------

								// -------------------
								// RULE WISE USER
								if($credentials['assign_rule']=='1')
								{
									$assigned_user_id=isset($aajjo_assign_to[$assign_start])?$aajjo_assign_to[$assign_start]:$aajjo_assign_to[0];
								}
								else if($credentials['assign_rule']==5)// KEYWORD 
								{			
									$title_tmp=str_replace(' ', '', strtolower($lead_title));
									$lead_requirement_tmp=str_replace(' ', '', strtolower($lead_requirement));				
									$search_keyword=$title_tmp.''.$lead_requirement_tmp;								
									$arr=array();
									$arr['assign_rule']=$credentials['assign_rule'];
									$arr['aajjo_setting_id']=$aajjo_setting_id;
									$arr['search_keyword']=$search_keyword;		
									$assigned_user_id=$this->Aajjo_setting_model->get_keyword_rule5_wise_assigned_user_id($arr,$client_info);
								}
								else
								{

									if($credentials['assign_rule']==2)
									{
										$search_keyword=$com_country_id;
									}
									else if($credentials['assign_rule']==3)
									{
										$search_keyword=$com_state_id;
									}
									else if($credentials['assign_rule']==4)
									{
										$search_keyword=$com_city_id;
									}
									$arr=array();
									$arr['assign_rule']=$credentials['assign_rule'];
									$arr['aajjo_setting_id']=$ti['aajjo_setting_id'];
									$arr['search_keyword']=$search_keyword;	
									$assigned_user_id=$this->Aajjo_setting_model->get_rule_wise_assigned_user_id($arr,$client_info);
								}				
								// RULE WISE USER			
								// ---------------------

								// ===================
								// CUSTOMER DETAILS
								$com_contact_person_tmp='';		
								$is_blacklist='N';					
								if($get_customer_decision['msg']=='no_customer_exist')
								{

									$company_post_data=array(
										'assigned_user_id'=>$assigned_user_id,
										'contact_person'=>$com_contact_person,
										'designation'=>$com_designation,
										'email'=>$com_email,
										'alt_email'=>$com_alternate_email,
										'mobile_country_code'=>$com_mobile_country_code,
										'mobile'=>$com_mobile,
										'alt_mobile_country_code'=>$com_alt_mobile_country_code,
										'alt_mobile'=>$com_alternate_mobile,
										'landline_country_code'=>$com_landline_country_code,
										'landline_std_code'=>$com_landline_std_code,
										'landline_number'=>$landline_number,
										'office_country_code'=>$com_country_code,
										'website'=>$com_website,
										'company_name'=>$com_company_name,
										'address'=>$com_address,
										'city'=>$com_city_id,
										'state'=>$com_state_id,
										'country_id'=>$com_country_id,
										'zip'=>$com_zip,
										'create_date'=>date("Y-m-d H:i:s"),
										'short_description'=>$com_short_description,
										'source_id'=>$com_source_id,
										'modify_date'=>date('Y-m-d'),
										'status'=>'1'
									);
									// print_r($company_post_data); die();
									$com_company_id=$this->Cronjobs_model->CreateCustomer($company_post_data,$client_info);

									// -------------------
									// RULE WISE USER
									if($credentials['assign_rule']=='1')
									{
										$assign_start++;
										if($assign_start>$assign_end)
										{
											$assign_start=0;
										}

										// ---------------------------------
										// update to setting table
										$tmpdata=array('assign_start'=>$assign_start);
										$this->Aajjo_setting_model->EditCredentials($tmpdata,$tradeindia_setting_id,$client_info);
										// update to setting table
										// ----------------------------------
									}
									// RULE WISE USER
									// -------------------
									

									// $assign_start++;
									// if($assign_start>$assign_end)
									// {
									// 	$assign_start=0;
									// }

									// -------------------------------------------
									// update to setting table
									// $tmpdata=array('assign_start'=>$assign_start);
									// $this->Aajjo_setting_model->EditCredentials($tmpdata,$tradeindia_setting_id,$client_info);
									// update to setting table
									// -------------------------------------------

									$com_contact_person_tmp=$com_contact_person;
									
								}
								else if($get_customer_decision['msg']=='one_customer_exist')
								{
									$com_company_id=$get_customer_decision['customer_id'];
									$get_existing_com=$this->Customer_model->get_company_detail($com_company_id,$client_info);
									$com_source_id=$get_existing_com['source_id'];
									$assigned_user_id=$get_existing_com['assigned_user_id'];
									$is_blacklist=($get_existing_com['is_blacklist'])?$get_existing_com['is_blacklist']:'N';
									$company_post_data=array(
										'contact_person'=>($get_existing_com['contact_person'])?$get_existing_com['contact_person']:$com_contact_person,
										'designation'=>($get_existing_com['designation'])?$get_existing_com['designation']:$com_designation,
										'alt_email'=>($get_existing_com['alt_email'])?$get_existing_com['alt_email']:$com_alternate_email,
										'alt_mobile_country_code'=>($get_existing_com['alt_mobile_country_code'])?$get_existing_com['alt_mobile_country_code']:$com_alt_mobile_country_code,
										'alt_mobile'=>($get_existing_com['alt_mobile'])?$get_existing_com['alt_mobile']:$com_alternate_mobile,
										'landline_country_code'=>($get_existing_com['landline_country_code'])?$get_existing_com['landline_country_code']:$com_landline_country_code,
										'landline_std_code'=>($get_existing_com['landline_std_code'])?$get_existing_com['landline_std_code']:$com_landline_std_code,
										'landline_number'=>($get_existing_com['landline_number'])?$get_existing_com['landline_number']:$landline_number,
										'website'=>($get_existing_com['website'])?$get_existing_com['website']:$com_website,
										'company_name'=>($get_existing_com['company_name'])?$get_existing_com['company_name']:$com_company_name,
										'address'=>($get_existing_com['address'])?$get_existing_com['address']:$com_address,
										'city'=>($get_existing_com['city']>0)?$get_existing_com['city']:$com_city_id,
										'state'=>($get_existing_com['state']>0)?$get_existing_com['state']:$com_state_id,
										'country_id'=>($get_existing_com['country_id']>0)?$get_existing_com['country_id']:$com_country_id,
										'zip'=>($get_existing_com['zip']>0)?$get_existing_com['zip']:$com_zip,
										'short_description'=>($get_existing_com['short_description'])?$get_existing_com['short_description']:$com_short_description,
										'source_id'=>($get_existing_com['source_id']>0)?$get_existing_com['source_id']:$com_source_id,
										'modify_date'=>date('Y-m-d'),
										'status'=>'1'
									);

									$this->Cronjobs_model->UpdateCustomer($company_post_data,$com_company_id,$client_info);

									$com_contact_person_tmp=($get_existing_com['contact_person'])?$get_existing_com['contact_person']:$com_contact_person;								
								}
								
								if($is_blacklist=='N')
								{
																

									$lead_enq_date=date("Y-m-d",strtotime($aj['created_date']));

									$lead_post_data=array(
													'title'=>$lead_title,
													'customer_id'=>$com_company_id,
													'source_id'=>$com_source_id,
													'assigned_user_id'=>$assigned_user_id,
													'buying_requirement'=>$lead_requirement,
													'enquiry_date'=>$lead_enq_date,
													'followup_date'=>date('Y-m-d'),
													'create_date'=>date('Y-m-d'),
													'modify_date'=>date('Y-m-d'),
													'assigned_date'=>date('Y-m-d'),
													'status'=>'1',
													'current_stage_id'=>'1',
													'current_stage'=>'PENDING',
													'current_stage_wise_msg'=>'',
													'current_status_id'=>'1',
													'current_status'=>'WARM',
													'aj_setting_id'=>$aajjo_setting_id
												);
									$lead_id=$this->Lead_model->CreateLead($lead_post_data,$client_info);
									if($lead_id)
									{
										// ---------------------------
										// PRODUCT TAGGED
										// if($ti['product_name'])
										// {
										// 	$lead_p_data=array(
										// 		'lead_id'=>$lead_id,
										// 		'name'=>$ti['product_name']
										// 	);
										// 	$this->Lead_model->CreateLeadWiseProductTag($lead_p_data,$client_info);
										// }								
										// PRODUCT TAGGED
										// ---------------------------
										
										// Insert Stage Log
										$stage_post_data=array(
												'lead_id'=>$lead_id,
												'stage_id'=>'1',
												'stage'=>'PENDING',
												'stage_wise_msg'=>'',
												'create_datetime'=>date("Y-m-d H:i:s")
											);
										$this->Lead_model->CreateLeadStageLog($stage_post_data,$client_info);

										// Insert Status Log
										$status_post_data=array(
												'lead_id'=>$lead_id,
												'status_id'=>'2',
												'status'=>'WARM',
												'create_datetime'=>date("Y-m-d H:i:s")
											);
										$this->Lead_model->CreateLeadStatusLog($status_post_data,$client_info);

										// echo $lead_requirement;
										// echo'<br>--------------<br>';
										$attach_filename='';
										$assigned_by_user_id=1;
										// -------------------------------------------------
										// ASSIGN LEAD LOG TABLE
										$post_data=array(
													'lead_id'=>$lead_id,
													'assigned_to_user_id'=>$assigned_user_id,
													'assigned_by_user_id'=>$assigned_by_user_id,
													'is_accepted'=>'Y',
													'assigned_datetime'=>date("Y-m-d H:i:s")
													);			
										$this->Lead_model->create_lead_assigned_user_log($post_data,$client_info);
										
									

										$update_by=1;				
										$date=date("Y-m-d H:i:s");				
										$ip_addr=$_SERVER['REMOTE_ADDR'];				
										$message="A new lead has been created as &quot;".$lead_title."&quot;";
										$comment_title=NEW_LEAD_CREATE_MANUAL;
										$historydata=array(
															'title'=>$comment_title,
															'lead_id'=>$lead_id,
															'comment'=>addslashes($message),
															'attach_file'=>$attach_filename,
															'create_date'=>$date,
															'user_id'=>$update_by,
															'ip_address'=>$ip_addr
														);
										$this->History_model->CreateHistory($historydata,$client_info);

										
										if($email_forwarding_setting['is_mail_send']=='Y')
										{
											// ============================
											// Mail Acknowledgement & 
											// account manager update
											// START

											// TO CLIENT				
											$e_data=array();
											//$user_id=$this->session->userdata['logged_in']['user_id'];
											$assigned_to_user_data=$this->User_model->get_employee_details($assigned_user_id,$client_info);
											$company=get_company_profile($client_info);	
											$lead_info=$this->Lead_model->GetLeadData($lead_id,$client_info);
											$customer=$this->Customer_model->GetCustomerData($lead_info->customer_id,$client_info);
											$e_data['company']=$company;
											$e_data['assigned_to_user']=$assigned_to_user_data;
											$e_data['customer']=$customer;
											$e_data['lead_info']=$lead_info;
											$e_data['client_info']=$client_info;
											/*
											$template_str_tmp=FCPATH .'application/views/admin/email_template/template/enquiry_acknowledgement_cronjobs_view_'.$client_info->id.'.php';
											if (file_exists($template_str_tmp)){
												$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_cronjobs_view_'.$client_info->id, $e_data, true);
											}else{
												$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_cronjobs_view', $e_data, true);
											}*/
											$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_cronjobs_view', $e_data, true);
											
											$m_email=get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);
											// --------------------
											// to mail assign logic
											$to_mail_assign='';
											$to_mail='';
											if($email_forwarding_setting['is_send_mail_to_client']=='Y')
											{
												$to_mail=$customer->email;
												$to_mail_assign='client';
											}
											else
											{
												if($m_email['manager_email']!='' && $email_forwarding_setting['is_send_manager']=='Y')
												{
													$to_mail=$m_email['manager_email'];
													$to_mail_assign='manager';
												}
												else
												{
													if($m_email['skip_manager_email']!='' && $email_forwarding_setting['is_send_skip_manager']=='Y')
													{
														$to_mail=$m_email['skip_manager_email'];
														$to_mail_assign='skip_manager';
													}
												}
											}
											// to mail assign logic
											// --------------------
											$cc_mail_arr=array();
											$self_cc_mail=get_value("email","user","id=".$assigned_user_id,$client_info);
											$update_by_name=get_value("name","user","id=".$assigned_user_id,$client_info);
											// --------------------
											// cc mail assign logic
											if($email_forwarding_setting['is_send_relationship_manager']=='Y')
											{
												if($to_mail=='')
												{
													$to_mail=$self_cc_mail;
												}
												else
												{
													array_push($cc_mail_arr, $self_cc_mail);
												}		        	
											}

											if($email_forwarding_setting['is_send_manager']=='Y')
											{
												if($m_email['manager_email']!='' && $to_mail_assign!='manager')
												{		        		
													array_push($cc_mail_arr, $m_email['manager_email']);
												}		        	
											}

											if($email_forwarding_setting['is_send_skip_manager']=='Y')
											{
												if($m_email['skip_manager_email']!='' && $to_mail_assign!='skip_manager')
												{		        		
													array_push($cc_mail_arr, $m_email['skip_manager_email']);
												}		        	
											}
											$cc_mail='';
											$cc_mail=implode(",", $cc_mail_arr);
											// cc mail assign logic
											// --------------------

											
														
											if($to_mail)
											{
												$com_company_name_tmp=$company['name'];
												if($ti['product_name']!=NULL && $com_contact_person_tmp!='')
												{
													$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' for '.$ti['product_name'].' has been received - '.$com_company_name_tmp; 
												}
												else if($ti['product_name']!=NULL && $com_contact_person_tmp=='')
												{
													$mail_subject='Enquiry # '.$lead_id.' for '.$ti['product_name'].' has been received - '.$com_company_name_tmp; 
												}
												else if($ti['product_name']==NULL && $com_contact_person_tmp!='')
												{
													$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
												}
												else if($ti['product_name']==NULL && $com_contact_person_tmp=='')
												{
													$mail_subject='Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
												}
												else
												{
													$mail_subject='Your Enquiry # '.$lead_id.' has been acknowledged';
												}

												
												//$this->load->library('mail_lib');
												$m_d = array();
												$m_d['from_mail']     = $self_cc_mail;
												$m_d['from_name']     = $update_by_name;
												$m_d['to_mail']       = $to_mail;
												$m_d['cc_mail']       = $cc_mail;
												$m_d['subject']       = $mail_subject;
												$m_d['message']       = $template_str;
												$m_d['attach']        = array();
												$mail_return = $this->mail_lib->send_mail($m_d,$client_info);
											}
											// END
											// =============================
										}
									}
								}
							}
							else
							{

							}
						}			
					}
				}
			}
			
			if($tradeindia_credentials>0)
			{
				$msg="Aajjo credentials available.";
			}
			else
			{
				$msg="Aajjo credentials not available";
			}
			
			// ==============================================

			$post_data=array(
					'client_id'=>$client_info->client_id,
                    'function_name'=>'get_lead_from_aajjo',
                    'updated_db_name'=>$client_info->db_name,
                    'created_at'=>date("Y-m-d H:i:s")
                    );
			//$this->Client_model->create_cronjobs_log($post_data);

			
	        // $mail_data = array();        
	        // $mail_data['from_mail']     = 'info@lmsbaba.com';
	        // $mail_data['from_name']     = 'lmsbaba';
	        // $mail_data['to_mail']       = 'arupporel123@gmail.com';
	        // $mail_data['subject']       = 'Tradeindia -'.$client_info->db_name;
	        // $mail_data['message']       = 'Tradeindia for '.$client_info->db_name;
	        // $mail_data['attach']        = array();
	        // $r=$this->mail_lib->send_mail($mail_data,$client_info);
	        // if($r==true)
	        // {
	        // 	echo "<br>sent for ".$client_info->db_name;
	        // }
	        // else
	        // {
	        // 	echo '<br>not sent for '.$client_info->db_name;
	        // }
			// ================================================
			//sleep(5);	

		}
		die('Completed');
	}
	
}
	
function get_lead_from_justdial($client_id='')
{
	
	$arg=array();
	$arg['client_id']=$client_id;
	$arg['cronjobs_action']='justdial';	
	$client_db_info_list=$this->Client_model->get_all($arg);
	
	if(count($client_db_info_list))
	{	
		foreach($client_db_info_list AS $client_info)
		{			
			$justdial_credentials=$this->Justdial_setting_model->GetCredentials($client_info);			
			// Lead Acknowledgement id 1
			$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(1,$client_info);			
			
			if(count($justdial_credentials)>0)
			{			
				if($justdial_credentials['assign_rule']=='1')
				{
					$justdial_assign_to=unserialize($justdial_credentials['assign_to']);
					$assign_start=isset($justdial_credentials['assign_start'])?$justdial_credentials['assign_start']:0;
					$assign_end=(count($justdial_assign_to)-1);
				}

				// $justdial_assign_to=unserialize($justdial_credentials['assign_to']);				
				// $assign_start=isset($justdial_credentials['assign_start'])?$justdial_credentials['assign_start']:0;
				// $assign_end=(count($justdial_assign_to)-1);	

				$justdial_setting_id=$justdial_credentials['id'];
				$get_jd=$this->Justdial_model->get_rows($client_info);
				if(count($get_jd))
				{
					foreach($get_jd as $jd)
					{	

						$cust_email=$jd['email'];
						$cust_mobile=substr($jd['mobile'], -10);
						$im_query_id=$jd['leadid'];						
						$cust_arr=array(
							'email'=>$cust_email,
							'mobile'=>$cust_mobile
										);
						$get_customer_decision=$this->Customer_model->get_decision_jd($cust_arr,$client_info);

						// ------------------------
						// get message 
						$jd_history_data=array('msg'=>$get_customer_decision['msg']);
						$this->Justdial_model->update($jd_history_data,$jd['id'],$client_info);
						// ------------------------						
							
						if($get_customer_decision['status']==TRUE)
						{							

							// $assigned_user_id=isset($justdial_assign_to[$assign_start])?$justdial_assign_to[$assign_start]:$justdial_assign_to[0];	
							$com_country_code='91';
							$com_country_id='101';	
							$com_contact_person=($jd['name'])?$jd['name']:'Purchase Manager';
							$com_designation='Manager';
							$com_email=$cust_email;
							$com_alternate_email=$jd['email'];
							$com_mobile_country_code=$com_country_code;
							$com_mobile=$cust_mobile;
							$com_alt_mobile_country_code=$com_country_code;
							$com_alternate_mobile='';
							$com_landline_country_code=$com_country_code;
							$com_landline_std_code='';
							$landline_number=$jd['phone'];
							$com_website='';
							$com_company_name='';
							$com_address=$jd['area'];
							$com_city_id='';	
							$com_state_id='';								
									
							if($jd['city'])
							{
								$get_city_id=$this->Cities_model->get_city_id_by_name($jd['city'],$client_info);
								if($get_city_id!=false)
								{
									$com_city_id=$get_city_id;
									$get_city_info=$this->Cities_model->get_city_by_id($com_city_id,$client_info);
									$com_state_id=$get_city_info->state_id;
								}
							}
									
							$com_zip=$jd['pincode'];
							$com_short_description='';
							$com_source_text='JustDial';	
							$com_source_text_tmp = str_replace(' ', '', strtolower($com_source_text));
							$com_source_id=$this->Source_model->get_source_id_by_name($com_source_text_tmp,$client_info);

							if($com_source_id==0)
							{
								$post_source=array(
									'parent_id'=>0,
									'name'=>$com_source_text
									);
								$com_source_id=$this->Source_model->add($post_source,$client_info);
							}

							// -------------------
							// LEAD INFO
							// LEAD
							if($jd['category']){
								$lead_title='Requirement of '.$jd['category'].' from JustDial';
							}
							else{
								$lead_title='Lead from JustDial';
							}
								
							$lead_requirement='';								
							$lead_requirement .=$lead_title.'<BR><BR>';
							
							
							if($jd['leadid']){
								$lead_requirement .='<B>JD Lead id:</B> '.$jd['leadid'].'<BR><BR>';
							}
							if($jd['parentid']){
								$lead_requirement .='<B>JD Parent id:</B> '.$jd['parentid'].'<BR><BR>';
							}
							if($jd['leadtype']){
								$lead_requirement .='<B>Lead Type:</B> '.$jd['leadtype'].'<BR><BR>';
							}
							$lead_requirement .='<B>From:</B><BR>';
							if($jd['name']){
								$lead_requirement .=''.$jd['name'].'';
							}
							if($jd['mobile']){
								$lead_requirement .='<B>Mobile:</B> '.$jd['mobile'].'<BR><BR>';
							}
							if($jd['email']){
								$lead_requirement .='<B>Email:</B> '.$jd['email'].'<BR><BR>';
							}
							if($jd['phone']){
								$lead_requirement .='<B>Phone:</B> '.$jd['phone'].'<BR><BR>';
							}
							if($jd['city']){
								$lead_requirement .='<B>City:</B> '.$jd['city'].'<BR><BR>';
							}
							if($jd['area']){
								$lead_requirement .='<B>Area:</B> '.$jd['area'].'<BR><BR>';
							}
							if($jd['enq_date']){
								$lead_requirement .='<B>Date/Time:</B> '.$jd['enq_date'].' '.$js['time'].'<BR><BR>';
							}
							// LEAD INFO
							// -------------------
							// -------------------
							// RULE WISE USER
							if($justdial_credentials['assign_rule']=='1')
							{
								$assigned_user_id=isset($justdial_assign_to[$assign_start])?$justdial_assign_to[$assign_start]:$justdial_assign_to[0];
							}
							else if($justdial_credentials['assign_rule']==5)// KEYWORD 
							{			
								$title_tmp=str_replace(' ', '', strtolower($lead_title));
								$lead_requirement_tmp=str_replace(' ', '', strtolower($lead_requirement));				
								$search_keyword=$title_tmp.''.$lead_requirement_tmp;								
								$arr=array();
								$arr['assign_rule']=$justdial_credentials['assign_rule'];
								$arr['justdial_setting_id']=$justdial_setting_id;
								$arr['search_keyword']=$search_keyword;		
								$assigned_user_id=$this->Justdial_setting_model->get_keyword_rule5_wise_assigned_user_id($arr,$client_info);
							}
							else
							{
								if($justdial_credentials['assign_rule']==2)
								{
									$search_keyword=$com_country_id;
								}
								if($justdial_credentials['assign_rule']==3)
								{
									$search_keyword=$com_state_id;
								}
								if($justdial_credentials['assign_rule']==4)
								{
									$search_keyword=$com_city_id;
								}
								$arr=array();
								$arr['assign_rule']=$justdial_credentials['assign_rule'];
								$arr['justdial_setting_id']=$justdial_setting_id;
								$arr['search_keyword']=$search_keyword;	
								$assigned_user_id=$this->Justdial_setting_model->get_rule_wise_assigned_user_id($arr,$client_info);
							}				
							// RULE WISE USER			
							// ---------------------

							// ===================
							// CUSTOMER DETAILS
							$com_contact_person_tmp='';		
							$is_blacklist='N';
							if($get_customer_decision['msg']=='no_customer_exist')
							{
								$company_post_data=array(
									'assigned_user_id'=>$assigned_user_id,
									'contact_person'=>$com_contact_person,
									'designation'=>$com_designation,
									'email'=>$com_email,
									'alt_email'=>$com_alternate_email,
									'mobile_country_code'=>$com_mobile_country_code,
									'mobile'=>$com_mobile,
									'alt_mobile_country_code'=>$com_alt_mobile_country_code,
									'alt_mobile'=>$com_alternate_mobile,
									'landline_country_code'=>$com_landline_country_code,
									'landline_std_code'=>$com_landline_std_code,
									'landline_number'=>$landline_number,
									'office_country_code'=>$com_country_code,
									'website'=>$com_website,
									'company_name'=>$com_company_name,
									'address'=>$com_address,
									'city'=>$com_city_id,
									'state'=>$com_state_id,
									'country_id'=>$com_country_id,
									'zip'=>$com_zip,
									'create_date'=>date("Y-m-d H:i:s"),
									'short_description'=>$com_short_description,
									'source_id'=>$com_source_id,
									'modify_date'=>date('Y-m-d'),
									'status'=>'1'
								);								
								
								$com_company_id=$this->Cronjobs_model->CreateCustomer($company_post_data,$client_info);
								
								// -------------------
								// RULE WISE USER
								if($justdial_credentials['assign_rule']=='1')
								{
									$assign_start++;
									if($assign_start>$assign_end)
									{
										$assign_start=0;
									}

									// ---------------------------------
									// update to setting table
									$tmpdata=array('assign_start'=>$assign_start);
									$this->Justdial_setting_model->EditJustdialCredentials($tmpdata,$justdial_setting_id,$client_info);
									// update to setting table
									// ----------------------------------
								}
								// RULE WISE USER
								// -------------------
								

								// $assign_start++;
								// if($assign_start>$assign_end)
								// {
								// 	$assign_start=0;
								// }

								// ---------------------------------
								// update to setting table
								// $tmpdata=array('assign_start'=>$assign_start);
								// $this->Justdial_setting_model->EditJustdialCredentials($tmpdata,$justdial_setting_id,$client_info);
								// update to setting table
								// ----------------------------------

								$com_contact_person_tmp=$com_contact_person;
								
							}
							else if($get_customer_decision['msg']=='one_customer_exist')
							{
								$com_company_id=$get_customer_decision['customer_id'];
								$get_existing_com=$this->Customer_model->get_company_detail($com_company_id,$client_info);
								$com_source_id=$get_existing_com['source_id'];
								$assigned_user_id=$get_existing_com['assigned_user_id'];
								$is_blacklist=($get_existing_com['is_blacklist'])?$get_existing_com['is_blacklist']:'N';
								$company_post_data=array(
									'contact_person'=>($get_existing_com['contact_person'])?$get_existing_com['contact_person']:$com_contact_person,
									'designation'=>($get_existing_com['designation'])?$get_existing_com['designation']:$com_designation,
									'alt_email'=>($get_existing_com['alt_email'])?$get_existing_com['alt_email']:$com_alternate_email,
									'alt_mobile_country_code'=>($get_existing_com['alt_mobile_country_code'])?$get_existing_com['alt_mobile_country_code']:$com_alt_mobile_country_code,
									'alt_mobile'=>($get_existing_com['alt_mobile'])?$get_existing_com['alt_mobile']:$com_alternate_mobile,
									'landline_country_code'=>($get_existing_com['landline_country_code'])?$get_existing_com['landline_country_code']:$com_landline_country_code,
									'landline_std_code'=>($get_existing_com['landline_std_code'])?$get_existing_com['landline_std_code']:$com_landline_std_code,
									'landline_number'=>($get_existing_com['landline_number'])?$get_existing_com['landline_number']:$landline_number,
									'website'=>($get_existing_com['website'])?$get_existing_com['website']:$com_website,
									'company_name'=>($get_existing_com['company_name'])?$get_existing_com['company_name']:$com_company_name,
									'address'=>($get_existing_com['address'])?$get_existing_com['address']:$com_address,
									'city'=>($get_existing_com['city']>0)?$get_existing_com['city']:$com_city_id,
									'state'=>($get_existing_com['state']>0)?$get_existing_com['state']:$com_state_id,
									'country_id'=>($get_existing_com['country_id']>0)?$get_existing_com['country_id']:$com_country_id,
									'zip'=>($get_existing_com['zip']>0)?$get_existing_com['zip']:$com_zip,
									'short_description'=>($get_existing_com['short_description'])?$get_existing_com['short_description']:$com_short_description,
									'source_id'=>($get_existing_com['source_id']>0)?$get_existing_com['source_id']:$com_source_id,
									'modify_date'=>date('Y-m-d'),
									'status'=>'1'
								);
								
								$this->Cronjobs_model->UpdateCustomer($company_post_data,$com_company_id,$client_info);
								
								$com_contact_person_tmp=($get_existing_com['contact_person'])?$get_existing_com['contact_person']:$com_contact_person;								
							}								
								
							if($is_blacklist=='N')
							{							

								$lead_enq_date=date_display_format_to_db_format($jd['enq_date']);
									
								$lead_post_data=array(
									'title'=>$lead_title,
									'customer_id'=>$com_company_id,
									'source_id'=>$com_source_id,
									'assigned_user_id'=>$assigned_user_id,
									'buying_requirement'=>$lead_requirement,
									'enquiry_date'=>$lead_enq_date,
									'followup_date'=>date('Y-m-d'),
									'create_date'=>date('Y-m-d'),
									'modify_date'=>date('Y-m-d'),
									'assigned_date'=>date('Y-m-d'),
									'status'=>'1',
									'current_stage_id'=>'1',
									'current_stage'=>'PENDING',
									'current_stage_wise_msg'=>'',
									'current_status_id'=>'1',
									'current_status'=>'WARM'
								);
										
								$lead_id=$this->Lead_model->CreateLead($lead_post_data,$client_info);
									
								if($lead_id)
								{									
									// ---------------------------
									// PRODUCT TAGGED
									// if($jd['category'])
									// {
									// 	$lead_p_data=array(
									// 		'lead_id'=>$lead_id,
									// 		'name'=>$jd['category']
									// 	);
									// 	$this->Lead_model->CreateLeadWiseProductTag($lead_p_data,$client_info);
									// }									
									// PRODUCT TAGGED
									// ---------------------------

									// Insert Stage Log
									$stage_post_data=array(
											'lead_id'=>$lead_id,
											'stage_id'=>'1',
											'stage'=>'PENDING',
											'stage_wise_msg'=>'',
											'create_datetime'=>date("Y-m-d H:i:s")
										);
									$this->Lead_model->CreateLeadStageLog($stage_post_data,$client_info);
										
									// Insert Status Log
									$status_post_data=array(
											'lead_id'=>$lead_id,
											'status_id'=>'2',
											'status'=>'WARM',
											'create_datetime'=>date("Y-m-d H:i:s")
										);
									$this->Lead_model->CreateLeadStatusLog($status_post_data,$client_info);
										

									$attach_filename='';
									$assigned_by_user_id=1;
									// -----------------------------------
									// ASSIGN LEAD LOG TABLE
									$post_data=array(
									'lead_id'=>$lead_id,
									'assigned_to_user_id'=>$assigned_user_id,
									'assigned_by_user_id'=>$assigned_by_user_id,
									'is_accepted'=>'Y',
									'assigned_datetime'=>date("Y-m-d H:i:s")
												);			
									$this->Lead_model->create_lead_assigned_user_log($post_data,$client_info);
										
									

									$update_by=1;				
									$date=date("Y-m-d H:i:s");				
									$ip_addr=$_SERVER['REMOTE_ADDR'];				
									$message="A new lead has been created as &quot;".$lead_title."&quot;";
									$comment_title=NEW_LEAD_CREATE_MANUAL;
									$historydata=array(
									'title'=>$comment_title,
									'lead_id'=>$lead_id,
									'comment'=>addslashes($message),
									'attach_file'=>$attach_filename,
									'create_date'=>$date,
									'user_id'=>$update_by,
									'ip_address'=>$ip_addr
													);
									$this->History_model->CreateHistory($historydata,$client_info);

										
									if($email_forwarding_setting['is_mail_send']=='Y')
									{
										// ============================
										// Mail Acknowledgement & 
										// account manager update
										// START

										// TO CLIENT				
										$e_data=array();									
										$assigned_to_user_data=$this->User_model->get_employee_details($assigned_user_id,$client_info);										
										$company=$this->Setting_model->GetCompanyData($client_info);
										$lead_info=$this->Lead_model->GetLeadData($lead_id,$client_info);
										$customer=$this->Customer_model->GetCustomerData($lead_info->customer_id,$client_info);


										$e_data['company']=$company;
										$e_data['assigned_to_user']=$assigned_to_user_data;
										$e_data['customer']=$customer;
										$e_data['lead_info']=$lead_info;
										$e_data['get_company_name_initials']=get_company_name_initials($client_info);
										$e_data['rander_company_address']=rander_company_address_cronjobs('email_template',$client_info);
										$e_data['client_info']=$client_info;
										$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_cronjobs_view', $e_data, true);
										
										$m_email=get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);

										// --------------------
										// to mail assign logic
										$to_mail_assign='';
										$to_mail='';
										if($email_forwarding_setting['is_send_mail_to_client']=='Y')
										{
											$to_mail=$customer->email;
											$to_mail_assign='client';
										}
										else
										{
											if($m_email['manager_email']!='' && $email_forwarding_setting['is_send_manager']=='Y')
											{
												$to_mail=$m_email['manager_email'];
												$to_mail_assign='manager';
											}
											else
											{
												if($m_email['skip_manager_email']!='' && $email_forwarding_setting['is_send_skip_manager']=='Y')
												{
													$to_mail=$m_email['skip_manager_email'];
													$to_mail_assign='skip_manager';
												}
											}
										}
										// to mail assign logic
										// --------------------

										$cc_mail_arr=array();
										$self_cc_mail=get_value("email","user","id=".$assigned_user_id,$client_info);
										$update_by_name=get_value("name","user","id=".$assigned_user_id,$client_info);
										// --------------------
										// cc mail assign logic
										if($email_forwarding_setting['is_send_relationship_manager']=='Y')
										{
											if($to_mail=='')
											{
												$to_mail=$self_cc_mail;
											}
											else
											{
												array_push($cc_mail_arr, $self_cc_mail);
											}		        	
										}

										if($email_forwarding_setting['is_send_manager']=='Y')
										{
											if($m_email['manager_email']!='' && $to_mail_assign!='manager')
											{		        		
												array_push($cc_mail_arr, $m_email['manager_email']);
											}		        	
										}

										if($email_forwarding_setting['is_send_skip_manager']=='Y')
										{
											if($m_email['skip_manager_email']!='' && $to_mail_assign!='skip_manager')
											{		        		
												array_push($cc_mail_arr, $m_email['skip_manager_email']);
											}		        	
										}
										$cc_mail='arupporel123@gmail.com';
										$cc_mail=implode(",", $cc_mail_arr);
										// cc mail assign logic
										// --------------------
												
										if($to_mail)
										{
											$com_company_name_tmp=$company['name'];
											if($jd['category']!='' && $com_contact_person_tmp!='')
											{
												$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' for '.$jd['category'].' has been received - '.$com_company_name_tmp; 
											}
											else if($jd['category']!='' && $com_contact_person_tmp=='')
											{
												$mail_subject='Enquiry # '.$lead_id.' for '.$jd['category'].' has been received - '.$com_company_name_tmp; 
											}
											else if($jd['category']=='' && $com_contact_person_tmp!='')
											{
												$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
											}
											else if($jd['category']=='' && $com_contact_person_tmp=='')
											{
												$mail_subject='Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
											}
											else
											{
												$mail_subject='Your Enquiry # '.$lead_id.' has been acknowledged';
											}
											
											$m_d = array();										
											$m_d['from_mail']     = $self_cc_mail;
											$m_d['from_name']     = $update_by_name;
											$m_d['to_mail']       = $to_mail;
											$m_d['cc_mail']       = $cc_mail;
											$m_d['subject']       = $mail_subject;
											$m_d['message']       = $template_str;
											$m_d['attach']        = array();
											$mail_return = $this->mail_lib->send_mail($m_d,$client_info);
										}									
										// END
										// =============================
									}
								
									$this->Justdial_model->deleteTmp($jd['id'],$client_info);	
								}	
							}							
						}
						else
						{

						}
					}			
				}
			}
			
			if(count($justdial_credentials)>0)
			{
				$msg="Process completed to fetch leads from JustDial.";
				$status='success';
			}
			else
			{
				$msg="JustDial credentials not available";
				$status='fail';
			}
			echo $status.'#'.$msg;
			
			// ==========================================

			$post_data=array(
				'client_id'=>$client_info->client_id,
				'function_name'=>'get_lead_from_justdial',
				'updated_db_name'=>$client_info->db_name,
				'created_at'=>date("Y-m-d H:i:s")
          	);
			//$this->Client_model->create_cronjobs_log($post_data);			
	        
			// ============================================	
			
		}
	}	
	//die('completed');	
}

function get_lead_from_indiamart_backlog($client_id='')
{	
	date_default_timezone_set('Asia/Kolkata');
	$arg=array();
	$arg['client_id']=$client_id;
	$arg['cronjobs_action']='indiamart_backlog';
	// print_r($arg); die();
	$client_db_info_list=$this->Client_model->get_all($arg);
	// print_r($client_db_info_list); die();
	if(count($client_db_info_list))
	{	
		foreach($client_db_info_list AS $client_info)
		{			
			$current_datetime=$client_info->indiamart_backlog_initial_date;	
			$start_datetime_db_format = date("Y-m-d",strtotime("-1 days", strtotime($current_datetime)));
			$start_datetime = date("d-M-Y",strtotime("-1 days", strtotime($current_datetime)));
			$end_datetime=date("d-M-Y",strtotime($current_datetime));

			$indiamart_credentials=$this->Indiamart_setting_model->GetIndiamartCredentials($client_info);
			
			// Lead Acknowledgement id 1
			$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(1,$client_info);
			
			if(count($indiamart_credentials)>0)
			{				
				$update_post_data=array('indiamart_backlog_initial_date'=>$start_datetime_db_format);

				$this->Client_model->update($update_post_data,$client_info->id);
				$this->Indiamart_model->truncate($client_info);
				foreach($indiamart_credentials AS $im)
				{
					$indiamart_setting_id=$im['id'];					

					$GLUSR_MOBILE=$im['glusr_mobile'];
					$GLUSR_MOBILE_KEY=$im['glusr_mobile_key'];		
					$url = "https://mapi.indiamart.com/wservce/enquiry/listing/GLUSR_MOBILE/".$GLUSR_MOBILE."/GLUSR_MOBILE_KEY/".$GLUSR_MOBILE_KEY."/Start_Time/".$start_datetime."/End_Time/".$end_datetime."/";	
					
						
					$useragent = 'PHP Client 1.0 (curl) ' . phpversion();
					$post_string="";
					$url_with_get=$url;
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url_with_get);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
					curl_setopt($ch, CURLOPT_TIMEOUT, 30);
					$result = curl_exec($ch);
					curl_close($ch);
					$return_arr=json_decode($result);	
					//print_r($return_arr); die();
					$i=0;
					if(!isset($return_arr[0]->Error_Message))
					{
						foreach($return_arr as $row)
						{
							$post=array(
										'indiamart_setting_id'=>$indiamart_setting_id,
										'RN'=>$row->RN,
										'QUERY_ID'=>$row->QUERY_ID,
										'QTYPE'=>$row->QTYPE,
										'SENDERNAME'=>$row->SENDERNAME,
										'SENDEREMAIL'=>$row->SENDEREMAIL,
										'SUBJECT'=>$row->SUBJECT,
										'DATE_RE'=>$row->DATE_RE,
										'DATE_R'=>$row->DATE_R,
										'DATE_TIME_RE'=>$row->DATE_TIME_RE,
										'GLUSR_USR_COMPANYNAME'=>$row->GLUSR_USR_COMPANYNAME,
										'READ_STATUS'=>$row->READ_STATUS,
										'SENDER_GLUSR_USR_ID'=>$row->SENDER_GLUSR_USR_ID,
										'MOB'=>$row->MOB,
										'COUNTRY_FLAG'=>$row->COUNTRY_FLAG,
										'QUERY_MODID'=>$row->QUERY_MODID,
										'LOG_TIME'=>$row->LOG_TIME,
										'QUERY_MODREFID'=>$row->QUERY_MODREFID,
										'DIR_QUERY_MODREF_TYPE'=>$row->DIR_QUERY_MODREF_TYPE,
										'ORG_SENDER_GLUSR_ID'=>$row->ORG_SENDER_GLUSR_ID,
										'ENQ_MESSAGE'=>$row->ENQ_MESSAGE,
										'ENQ_ADDRESS'=>$row->ENQ_ADDRESS,
										'ENQ_CALL_DURATION'=>$row->ENQ_CALL_DURATION,
										'ENQ_RECEIVER_MOB'=>$row->ENQ_RECEIVER_MOB,
										'ENQ_CITY'=>$row->ENQ_CITY,
										'ENQ_STATE'=>$row->ENQ_STATE,
										'PRODUCT_NAME'=>$row->PRODUCT_NAME,
										'COUNTRY_ISO'=>$row->COUNTRY_ISO,
										'EMAIL_ALT'=>$row->EMAIL_ALT,
										'MOBILE_ALT'=>$row->MOBILE_ALT,
										'PHONE'=>$row->PHONE,
										'PHONE_ALT'=>$row->PHONE_ALT,
										'IM_MEMBER_SINCE'=>$row->IM_MEMBER_SINCE,
										'TOTAL_COUNT'=>$row->TOTAL_COUNT
										);
							$this->Indiamart_model->insert($post,$client_info);
							$i++;				
						}
					}
					
				}
			}
			
			if(count($indiamart_credentials)>0)
			{			
				foreach($indiamart_credentials AS $credentials)
				{				
					$get_im=$this->Indiamart_model->get_rows($credentials['id'],$client_info);				
					if(count($get_im))
					{					
						if($credentials['assign_rule']=='1')
						{
							$indiamart_assign_to=unserialize($credentials['assign_to']);
							$assign_start=isset($credentials['assign_start'])?$credentials['assign_start']:0;
							$assign_end=(count($indiamart_assign_to)-1);
						}
						
						foreach($get_im as $im)
						{	

							$cust_email=$im['SENDEREMAIL'];
							$cust_mobile=end(explode("-", $im['MOB']));
							$im_query_id=$im['QUERY_ID'];
							//echo $im['MOB'].' / '.$cust_mobile.'<br>';
							$cust_arr=array(
								'email'=>$cust_email,
								'mobile'=>$cust_mobile,
								'im_query_id'=>$im_query_id
											);
							$get_customer_decision=$this->Customer_model->get_decision($cust_arr,$client_info);

							// ------------------------
							// get message 
							$im_history_data=array('msg'=>$get_customer_decision['msg']);
							$this->Indiamart_model->update($im_history_data,$im['id'],$client_info);
							// ------------------------
							
							if($get_customer_decision['status']==TRUE)
							{
								
								$com_country_code='';
								$com_country_id='';
								if($im['COUNTRY_ISO'])
								{
									$get_country=$this->Countries_model->get_country_by_iso($im['COUNTRY_ISO'],$client_info);
									if($get_country!=false)
									{
										$com_country_id=$get_country->id;
										$com_country_code=$get_country->phonecode;

									}
								}
								

							

								
								$indiamart_setting_id=$im['indiamart_setting_id'];
								$com_contact_person=($im['SENDERNAME'])?$im['SENDERNAME']:'Purchase Manager';
								$com_designation='Manager';
								$com_email=$cust_email;
								$com_alternate_email=$im['EMAIL_ALT'];
								$com_mobile_country_code=$com_country_code;
								$com_mobile=$cust_mobile;
								$com_alt_mobile_country_code=$com_country_code;
								$com_alternate_mobile=$im['MOBILE_ALT'];
								$com_landline_country_code=$com_country_code;
								$com_landline_std_code='';
								$landline_number=$im['PHONE'];
								$com_website='';
								$com_company_name=$im['GLUSR_USR_COMPANYNAME'];
								$com_address=$im['ENQ_ADDRESS'];
								$com_city_id='';
								if($im['ENQ_CITY'])
								{
									$get_city_id=$this->Cities_model->get_city_id_by_name($im['ENQ_CITY'],$client_info);
									if($get_city_id!=false)
									{
										$com_city_id=$get_city_id;
									}
								}
								$com_state_id='';	
								if($im['ENQ_STATE'])
								{
									$get_state_id=$this->States_model->get_state_id_by_name($im['ENQ_STATE'],$client_info);
									if($get_state_id!=false)
									{
										$com_state_id=$get_state_id;
									}
								}				
								$com_zip='';
								$com_short_description='';
								if($im['QTYPE'])
								{
									if(strtoupper($im['QTYPE'])=='W'){
										$com_source_text='Indiamart';
									}
									else if(strtoupper($im['QTYPE'])=='B'){
										$com_source_text='Indiamart Buylead';
									}
									else if(strtoupper($im['QTYPE'])=='P'){
										$com_source_text='Indiamart Call Enquiry';
									}		
									else{
										$com_source_text='Indiamart';
									}	

									
									$com_source_text_tmp = str_replace(' ', '', strtolower($com_source_text));
									$com_source_id=$this->Source_model->get_source_id_by_name($com_source_text_tmp,$client_info);
									if($com_source_id==0)
									{
										$post_source=array(
											'parent_id'=>0,
											'name'=>$com_source_text
											);
										$com_source_id=$this->Source_model->add($post_source,$client_info);
									}
								}
								
								
								// -------------------
								// LEAD INFO
								// LEAD
								$lead_title=$im['SUBJECT'];
								$lead_requirement='';
								if($im['PRODUCT_NAME']){
									$lead_requirement .='<B>Product Required:</B> '.$im['PRODUCT_NAME'].'<BR><BR>';
								}
								if($im['ENQ_MESSAGE']){
									$lead_requirement .=''.$im['ENQ_MESSAGE'].'<BR><BR>';
								}
								if($im['ENQ_CALL_DURATION']){
									$lead_requirement .='<B>Call Duration:</B> '.$im['ENQ_CALL_DURATION'].'<BR><BR>';
								}
								$lead_requirement .='<B>From:</B><BR>';
								if($im['SENDERNAME']){
									$lead_requirement .=''.$im['SENDERNAME'].'';
								}
								if($im['GLUSR_USR_COMPANYNAME']){
									$lead_requirement .='<BR>'.$im['GLUSR_USR_COMPANYNAME'].'<BR>';
								}

								if($im['ENQ_CITY']!='' || $im['ENQ_STATE']!='' || $im['COUNTRY_ISO']!='')
								{
									$lead_requirement .=($im['ENQ_CITY'])?$im['ENQ_CITY']:'';
									
									$lead_requirement .=($im['ENQ_STATE'])?', ':'';
									$lead_requirement .=($im['ENQ_STATE'])?$im['ENQ_STATE']:'';
								
									$lead_requirement .=($im['COUNTRY_ISO'])?', ':'';
									$lead_requirement .=($im['COUNTRY_ISO'])?$get_country->name:'';
								}

								if($im['SENDEREMAIL']!='' || $im['EMAIL_ALT']!='')
								{

									$lead_requirement .='<br>Email: ';						
									$lead_requirement .=''.$im['SENDEREMAIL'].'';

									$lead_requirement .=($im['EMAIL_ALT'])?', ':'';
									$lead_requirement .=($im['EMAIL_ALT'])?$im['EMAIL_ALT']:'';
									
									if($im['SENDEREMAIL']!='' || $im['EMAIL_ALT']!=''){
										$lead_requirement .='<br>';
									}
								}

								if($im['MOB']!='' || $im['MOBILE_ALT']!=''){

									$lead_requirement .='Mobile: ';
									$lead_requirement .=''.$im['MOB'].'';

									$lead_requirement .=($im['MOBILE_ALT'])?', ':'';
									$lead_requirement .=($im['MOBILE_ALT'])?$im['MOBILE_ALT']:'';
									if($im['MOB']!='' || $im['MOBILE_ALT']!=''){
										$lead_requirement .='<br>';
									}
								}

								if($im['PHONE']!='' || $im['PHONE_ALT']!='')
								{
									$lead_requirement .='Phone: ';
									$lead_requirement .=($im['PHONE'])?$im['PHONE']:'';
									$lead_requirement .=($im['PHONE_ALT'])?', ':'';
									$lead_requirement .=($im['PHONE_ALT'])?$im['PHONE_ALT']:'';
									$lead_requirement .='<br>';						
								}

								if($im['DATE_TIME_RE']!='')
								{
									$lead_requirement .='Date and Time: ';			
									$lead_requirement .=$im['DATE_TIME_RE'];
								}
								// LEAD INFO
								// --------------------
								
								// -------------------
								// RULE WISE USER
								if($credentials['assign_rule']=='1')
								{
									$assigned_user_id=isset($indiamart_assign_to[$assign_start])?$indiamart_assign_to[$assign_start]:$indiamart_assign_to[0];
								}
								else if($credentials['assign_rule']==5)// KEYWORD 
								{			
									$title_tmp=str_replace(' ', '', strtolower($lead_title));
									$lead_requirement_tmp=str_replace(' ', '', strtolower($lead_requirement));				
									$search_keyword=$title_tmp.''.$lead_requirement_tmp;								
									$arr=array();
									$arr['assign_rule']=$credentials['assign_rule'];
									$arr['indiamart_setting_id']=$im['indiamart_setting_id'];
									$arr['search_keyword']=$search_keyword;	
									$assigned_user_id=$this->Indiamart_setting_model->get_keyword_rule5_wise_assigned_user_id($arr,$client_info);
								}
								else
								{

									if($credentials['assign_rule']==2)
									{
										$search_keyword=$com_country_id;
									}
									else if($credentials['assign_rule']==3)
									{
										$search_keyword=$com_state_id;
									}
									else if($credentials['assign_rule']==4)
									{
										$search_keyword=$com_city_id;
									}
									$arr=array();
									$arr['assign_rule']=$credentials['assign_rule'];
									$arr['indiamart_setting_id']=$im['indiamart_setting_id'];
									$arr['search_keyword']=$search_keyword;	
									$assigned_user_id=$this->Indiamart_setting_model->get_rule_wise_assigned_user_id($arr,$client_info);
								}								
								// RULE WISE USER			
								// ---------------------

								// ===================
								// CUSTOMER DETAILS
								$com_contact_person_tmp='';		
								$is_blacklist='N';
								if($get_customer_decision['msg']=='no_customer_exist')
								{

									$company_post_data=array(
										'assigned_user_id'=>$assigned_user_id,
										'contact_person'=>$com_contact_person,
										'designation'=>$com_designation,
										'email'=>$com_email,
										'alt_email'=>$com_alternate_email,
										'mobile_country_code'=>$com_mobile_country_code,
										'mobile'=>$com_mobile,
										'alt_mobile_country_code'=>$com_alt_mobile_country_code,
										'alt_mobile'=>$com_alternate_mobile,
										'landline_country_code'=>$com_landline_country_code,
										'landline_std_code'=>$com_landline_std_code,
										'landline_number'=>$landline_number,
										'office_country_code'=>$com_country_code,
										'website'=>$com_website,
										'company_name'=>$com_company_name,
										'address'=>$com_address,
										'city'=>$com_city_id,
										'state'=>$com_state_id,
										'country_id'=>$com_country_id,
										'zip'=>$com_zip,
										'create_date'=>date("Y-m-d H:i:s"),
										'short_description'=>$com_short_description,
										'source_id'=>$com_source_id,
										'modify_date'=>date('Y-m-d'),
										'status'=>'1'
									);
									//print_r($company_post_data); die();
									
									$com_company_id=$this->Cronjobs_model->CreateCustomer($company_post_data,$client_info);
									
									if($credentials['assign_rule']=='1')
									{
										$assign_start++;
										if($assign_start>$assign_end)
										{
											$assign_start=0;
										}

										// ---------------------------------
										// update to setting table
										$tmpdata=array('assign_start'=>$assign_start);
										$this->Indiamart_setting_model->EditIndiamartCredentials($tmpdata,$indiamart_setting_id,$client_info);
										// update to setting table
										// ----------------------------------
									}
									$com_contact_person_tmp=$com_contact_person;
									
								}
								else if($get_customer_decision['msg']=='one_customer_exist')
								{
									$com_company_id=$get_customer_decision['customer_id'];
									$get_existing_com=$this->Customer_model->get_company_detail($com_company_id,$client_info);
									$com_source_id=$get_existing_com['source_id'];
									$assigned_user_id=$get_existing_com['assigned_user_id'];
									$is_blacklist=($get_existing_com['is_blacklist'])?$get_existing_com['is_blacklist']:'N';
									$company_post_data=array(
										'contact_person'=>($get_existing_com['contact_person'])?$get_existing_com['contact_person']:$com_contact_person,
										'designation'=>($get_existing_com['designation'])?$get_existing_com['designation']:$com_designation,
										'alt_email'=>($get_existing_com['alt_email'])?$get_existing_com['alt_email']:$com_alternate_email,
										'alt_mobile_country_code'=>($get_existing_com['alt_mobile_country_code'])?$get_existing_com['alt_mobile_country_code']:$com_alt_mobile_country_code,
										'alt_mobile'=>($get_existing_com['alt_mobile'])?$get_existing_com['alt_mobile']:$com_alternate_mobile,
										'landline_country_code'=>($get_existing_com['landline_country_code'])?$get_existing_com['landline_country_code']:$com_landline_country_code,
										'landline_std_code'=>($get_existing_com['landline_std_code'])?$get_existing_com['landline_std_code']:$com_landline_std_code,
										'landline_number'=>($get_existing_com['landline_number'])?$get_existing_com['landline_number']:$landline_number,
										'website'=>($get_existing_com['website'])?$get_existing_com['website']:$com_website,
										'company_name'=>($get_existing_com['company_name'])?$get_existing_com['company_name']:$com_company_name,
										'address'=>($get_existing_com['address'])?$get_existing_com['address']:$com_address,
										'city'=>($get_existing_com['city']>0)?$get_existing_com['city']:$com_city_id,
										'state'=>($get_existing_com['state']>0)?$get_existing_com['state']:$com_state_id,
										'country_id'=>($get_existing_com['country_id']>0)?$get_existing_com['country_id']:$com_country_id,
										'zip'=>($get_existing_com['zip']>0)?$get_existing_com['zip']:$com_zip,
										'short_description'=>($get_existing_com['short_description'])?$get_existing_com['short_description']:$com_short_description,
										'source_id'=>($get_existing_com['source_id']>0)?$get_existing_com['source_id']:$com_source_id,
										'modify_date'=>date('Y-m-d'),
										'status'=>'1'
									);
									
									$this->Cronjobs_model->UpdateCustomer($company_post_data,$com_company_id,$client_info);
									
									$com_contact_person_tmp=($get_existing_com['contact_person'])?$get_existing_com['contact_person']:$com_contact_person;								
								}
								


								
								
								if($is_blacklist=='N')
								{
									$lead_enq_date=date_display_format_to_db_format($im['DATE_RE']);
								
									$lead_post_data=array(
										'title'=>$lead_title,
										'customer_id'=>$com_company_id,
										'source_id'=>$com_source_id,
										'assigned_user_id'=>$assigned_user_id,
										'buying_requirement'=>$lead_requirement,
										'enquiry_date'=>$lead_enq_date,
										'followup_date'=>date('Y-m-d'),
										'create_date'=>date('Y-m-d'),
										'modify_date'=>date('Y-m-d'),
										'assigned_date'=>date('Y-m-d'),
										'status'=>'1',
										'current_stage_id'=>'1',
										'current_stage'=>'PENDING',
										'current_stage_wise_msg'=>'',
										'current_status_id'=>'1',
										'current_status'=>'WARM',
										'im_query_id'=>$im_query_id,
										'im_setting_id'=>$indiamart_setting_id
									);
									
									$lead_id=$this->Lead_model->CreateLead($lead_post_data,$client_info);
									
									if($lead_id)
									{
										// ---------------------------
										// PRODUCT TAGGED
										// if($im['PRODUCT_NAME'])
										// {
										// 	$lead_p_data=array(
										// 		'lead_id'=>$lead_id,
										// 		'name'=>$im['PRODUCT_NAME']
										// 	);
										// 	$this->Lead_model->CreateLeadWiseProductTag($lead_p_data,$client_info);
										// }
										
										// PRODUCT TAGGED
										// ---------------------------
										


										// Insert Stage Log
										$stage_post_data=array(
												'lead_id'=>$lead_id,
												'stage_id'=>'1',
												'stage'=>'PENDING',
												'stage_wise_msg'=>'',
												'create_datetime'=>date("Y-m-d H:i:s")
											);
										$this->Lead_model->CreateLeadStageLog($stage_post_data,$client_info);
										
										// Insert Status Log
										$status_post_data=array(
												'lead_id'=>$lead_id,
												'status_id'=>'2',
												'status'=>'WARM',
												'create_datetime'=>date("Y-m-d H:i:s")
											);
										$this->Lead_model->CreateLeadStatusLog($status_post_data,$client_info);
										

										$attach_filename='';
										$assigned_by_user_id=1;
										// -------------------------------------------------
										// ASSIGN LEAD LOG TABLE
										$post_data=array(
										'lead_id'=>$lead_id,
										'assigned_to_user_id'=>$assigned_user_id,
										'assigned_by_user_id'=>$assigned_by_user_id,
										'is_accepted'=>'Y',
										'assigned_datetime'=>date("Y-m-d H:i:s")
													);			
										$this->Lead_model->create_lead_assigned_user_log($post_data,$client_info);
										
									

										$update_by=1;				
										$date=date("Y-m-d H:i:s");				
										$ip_addr=$_SERVER['REMOTE_ADDR'];				
										$message="A new lead has been created as &quot;".$lead_title."&quot;";
										$comment_title=NEW_LEAD_CREATE_MANUAL;
										$historydata=array(
										'title'=>$comment_title,
										'lead_id'=>$lead_id,
										'comment'=>addslashes($message),
										'attach_file'=>$attach_filename,
										'create_date'=>$date,
										'user_id'=>$update_by,
										'ip_address'=>$ip_addr
														);
										$this->History_model->CreateHistory($historydata,$client_info);

										
										if($email_forwarding_setting['is_mail_send']=='Y')
										{
											// ============================
											// Mail Acknowledgement & 
											// account manager update
											// START

											// TO CLIENT				
											$e_data=array();
											//$user_id=$this->session->userdata['logged_in']['user_id'];
											$assigned_to_user_data=$this->User_model->get_employee_details($assigned_user_id,$client_info);
											//$company=get_company_profile();	
											$company=$this->Setting_model->GetCompanyData($client_info);
											$lead_info=$this->Lead_model->GetLeadData($lead_id,$client_info);
											$customer=$this->Customer_model->GetCustomerData($lead_info->customer_id,$client_info);


											$e_data['company']=$company;
											$e_data['assigned_to_user']=$assigned_to_user_data;
											$e_data['customer']=$customer;
											$e_data['lead_info']=$lead_info;
											$e_data['get_company_name_initials']=get_company_name_initials($client_info);
											$e_data['rander_company_address']=rander_company_address_cronjobs('email_template',$client_info);
											$e_data['client_info']=$client_info;
											$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_cronjobs_view', $e_data, true);
											
											$m_email=get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);

											// --------------------
											// to mail assign logic
											$to_mail_assign='';
											$to_mail='';
											if($email_forwarding_setting['is_send_mail_to_client']=='Y')
											{
												$to_mail=$customer->email;
												$to_mail_assign='client';
											}
											else
											{
												if($m_email['manager_email']!='' && $email_forwarding_setting['is_send_manager']=='Y')
												{
													$to_mail=$m_email['manager_email'];
													$to_mail_assign='manager';
												}
												else
												{
													if($m_email['skip_manager_email']!='' && $email_forwarding_setting['is_send_skip_manager']=='Y')
													{
														$to_mail=$m_email['skip_manager_email'];
														$to_mail_assign='skip_manager';
													}
												}
											}
											// to mail assign logic
											// --------------------

											$cc_mail_arr=array();
											$self_cc_mail=get_value("email","user","id=".$assigned_user_id,$client_info);
											$update_by_name=get_value("name","user","id=".$assigned_user_id,$client_info);
											// --------------------
											// cc mail assign logic
											if($email_forwarding_setting['is_send_relationship_manager']=='Y')
											{
												if($to_mail=='')
												{
													$to_mail=$self_cc_mail;
												}
												else
												{
													array_push($cc_mail_arr, $self_cc_mail);
												}		        	
											}

											if($email_forwarding_setting['is_send_manager']=='Y')
											{
												if($m_email['manager_email']!='' && $to_mail_assign!='manager')
												{		        		
													array_push($cc_mail_arr, $m_email['manager_email']);
												}		        	
											}

											if($email_forwarding_setting['is_send_skip_manager']=='Y')
											{
												if($m_email['skip_manager_email']!='' && $to_mail_assign!='skip_manager')
												{		        		
													array_push($cc_mail_arr, $m_email['skip_manager_email']);
												}		        	
											}
											$cc_mail='';
											$cc_mail=implode(",", $cc_mail_arr);
											// cc mail assign logic
											// --------------------
													
											if($to_mail)
											{
												$com_company_name_tmp=$company['name'];
												if($im['PRODUCT_NAME']!=NULL && $com_contact_person_tmp!='')
												{
													$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' for '.$im['PRODUCT_NAME'].' has been received - '.$com_company_name_tmp; 
												}
												else if($im['PRODUCT_NAME']!=NULL && $com_contact_person_tmp=='')
												{
													$mail_subject='Enquiry # '.$lead_id.' for '.$im['PRODUCT_NAME'].' has been received - '.$com_company_name_tmp; 
												}
												else if($im['PRODUCT_NAME']==NULL && $com_contact_person_tmp!='')
												{
													$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
												}
												else if($im['PRODUCT_NAME']==NULL && $com_contact_person_tmp=='')
												{
													$mail_subject='Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
												}
												else
												{
													$mail_subject='Your Enquiry # '.$lead_id.' has been acknowledged';
												}

												
												//$this->load->library('mail_lib');
												$m_d = array();
												// $m_d['from_mail']     = $company['email1'];
												// $m_d['from_name']     = $company['name'];
												$m_d['from_mail']     = $self_cc_mail;
												$m_d['from_name']     = $update_by_name;
												$m_d['to_mail']       = $to_mail;
												$m_d['cc_mail']       = $cc_mail;
												$m_d['subject']       = $mail_subject;
												$m_d['message']       = $template_str;
												$m_d['attach']        = array();
												$mail_return = $this->mail_lib->send_mail($m_d,$client_info);
											}
											
											// END
											// =============================
										}
										
									}
								}								
							}
							else
							{

							}
						}			
					}
				}
			}
			
			if(count($indiamart_credentials)>0)
			{
				$msg="Process completed to fetch leads from indiamart backlog.";
				$status='success';
			}
			else
			{
				$msg="imdiamart credentials not available";
				$status='fail';
			}
			echo $status.'#'.$msg;
			
			// ==========================================

			$post_data=array(
					'client_id'=>$client_info->client_id,
                    'function_name'=>'get_lead_from_indiamart_backlog',
                    'updated_db_name'=>$client_info->db_name,
                    'created_at'=>date("Y-m-d H:i:s")
                    );
			//$this->Client_model->create_cronjobs_log($post_data);	        
			// ============================================	
			
		}
	}	
	//die('completed');	
}	

function create_lead_from_renewal($client_id='')
{	
	date_default_timezone_set('Asia/Kolkata');
	$current_datetime=date("Y-m-d");
	$status='';
	$msg='';
	$arg=array();
	$arg['client_id']=$client_id;
	$arg['cronjobs_action']='renewal';	
	$client_db_info_list=$this->Client_model->get_all($arg);
	
	if(count($client_db_info_list))
	{	
		foreach($client_db_info_list AS $client_info)
		{			

			$company=get_company_profile($client_info);
			// print_r($company); die();
			$default_currency_code=$company['default_currency_code'];
			// Lead Acknowledgement id 1
			$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(1,$client_info);


			$get_renewals=$this->renewal_model->get_renewal_list_by_today($current_datetime,$client_info);
			// print_r($get_renewals); die();

			if(count($get_renewals))
			{
				foreach($get_renewals AS $renewal)
				{
					

					// LEAD
					$lead_title='Renewal/AMC for '.$renewal->product_name_str;
					$lead_requirement='';

					$lead_requirement .='<B>Description:</B><BR>';
					if($renewal->description){
						$lead_requirement .=''.$renewal->description.'<BR><BR>';
					}

					$lead_requirement .='<B>Renewal Detail:</B><BR>';

					if($renewal->product_name_price_str)
					{

						$p_name_price_arr=explode(",", $renewal->product_name_price_str);
						if(count($p_name_price_arr))
						{
							foreach($p_name_price_arr AS $p_n_p_str)
							{
								$p_n_p_arr=explode("#", $p_n_p_str);
								$name=$p_n_p_arr[0];
								$price=$p_n_p_arr[1];
								$lead_requirement .=''.$name.'<BR>';
								$lead_requirement .='Renewal Amount: '.$default_currency_code.' '.number_format($price,2).'<BR>';
								$lead_requirement .='Renewal Date: '.date_db_format_to_display_format($renewal->renewal_date).'<BR><br>';
							}
						}						
					}

					$com_company_id=$renewal->cus_id;					
					$com_source_id=$renewal->cus_source_id;
					$assigned_user_id=$renewal->cus_assigned_user_id;
					$lead_enq_date=$renewal->next_follow_up_date;
					
					$lead_post_data=array(
					'title'=>$lead_title,
					'customer_id'=>$com_company_id,
					'source_id'=>$com_source_id,
					'assigned_user_id'=>$assigned_user_id,
					'buying_requirement'=>$lead_requirement,
					'enquiry_date'=>$lead_enq_date,
					'followup_date'=>date('Y-m-d'),
					'create_date'=>date('Y-m-d'),
					'modify_date'=>date('Y-m-d'),
					'assigned_date'=>date('Y-m-d'),
					'status'=>'1',
					'current_stage_id'=>'1',
					'current_stage'=>'PENDING',
					'current_stage_wise_msg'=>'',
					'current_status_id'=>'1',
					'current_status'=>'WARM'
					);
					
					$lead_id=$this->Lead_model->CreateLead($lead_post_data,$client_info);
					
					if($lead_id)
					{
						// ==================
						// update renewal
						$update_data=array('lead_id'=>$lead_id);
						$this->renewal_model->updateDetails($update_data,$renewal->id,$client_info);
						// update renewal
						// ==================
						
						// ---------------------------
						// PRODUCT TAGGED
						if($renewal->product_name_price_str)
						{

							$p_name_price_arr=explode(",", $renewal->product_name_price_str);
							if(count($p_name_price_arr))
							{
								foreach($p_name_price_arr AS $p_n_p_str)
								{
									$p_n_p_arr=explode("#", $p_n_p_str);
									$name=$p_n_p_arr[0];
									$price=$p_n_p_arr[1];
									$p_id=$p_n_p_arr[2];
									$lead_p_data=array(
										'lead_id'=>$lead_id,
										'name'=>$name,
										'product_id'=>$p_id,
										'tag_type'=>'L',
										'created_at'=>date("Y-m-d H:i:s")
									);
						    		$this->Lead_model->CreateLeadWiseProductTag($lead_p_data,$client_info);
								}
							}						
						}
						
						// PRODUCT TAGGED
						// ---------------------------
						


						// Insert Stage Log
						$stage_post_data=array(
								'lead_id'=>$lead_id,
								'stage_id'=>'1',
								'stage'=>'PENDING',
								'stage_wise_msg'=>'',
								'create_datetime'=>date("Y-m-d H:i:s")
							);
						$this->Lead_model->CreateLeadStageLog($stage_post_data,$client_info);
						
						// Insert Status Log
						$status_post_data=array(
								'lead_id'=>$lead_id,
								'status_id'=>'2',
								'status'=>'WARM',
								'create_datetime'=>date("Y-m-d H:i:s")
							);
						$this->Lead_model->CreateLeadStatusLog($status_post_data,$client_info);
						

						$attach_filename='';
						$assigned_by_user_id=1;
						// -------------------------------------------------
						// ASSIGN LEAD LOG TABLE
						$post_data=array(
						'lead_id'=>$lead_id,
						'assigned_to_user_id'=>$assigned_user_id,
						'assigned_by_user_id'=>$assigned_by_user_id,
						'is_accepted'=>'Y',
						'assigned_datetime'=>date("Y-m-d H:i:s")
									);			
						$this->Lead_model->create_lead_assigned_user_log($post_data,$client_info);
					
						$attach_filename='';
						$update_by=1;				
						$date=date("Y-m-d H:i:s");
						$ip_addr=$_SERVER['REMOTE_ADDR'];
						$message="A new lead has been created as &quot;".$lead_title."&quot;";
						$comment_title=NEW_LEAD_CREATE_MANUAL;
						$historydata=array(
						'title'=>$comment_title,
						'lead_id'=>$lead_id,
						'comment'=>addslashes($message),
						'attach_file'=>$attach_filename,
						'create_date'=>$date,
						'user_id'=>$update_by,
						'ip_address'=>$ip_addr
						);
						$this->History_model->CreateHistory($historydata,$client_info);
					}

				}

				$msg="Process completed to create leads from upcoming renewal.";
				$status='success';
			}
			else
			{
				$msg="Renewal/AMC not available for today";
				$status='fail';
			}			
		}
	}
	else
	{
		$msg="Renewal/AMC not activate. Please contact to admin.";
		$status='fail';
		
	}
	echo $status.'#'.$msg;

	// ===================================
	$post_data=array(
	'client_id'=>$client_info->client_id,
	'function_name'=>'create_lead_from_renewal',
	'updated_db_name'=>$client_info->db_name,
	'created_at'=>date("Y-m-d H:i:s")
	);
	//$this->Client_model->create_cronjobs_log($post_data);
	// ===================================
}

function generate_daily_report($client_id='')
{

	// date_default_timezone_set('Asia/Kolkata');
	$report_date=date('Y-m-d',strtotime("-1 days"));	
	$arg=array();
	$arg['client_id']=$client_id;
	$arg['cronjobs_action']='daily_report';	
	$client_db_info_list=$this->Client_model->get_all($arg);

	// print_r($client_db_info_list); die();
	if(count($client_db_info_list))
	{	
		foreach($client_db_info_list AS $client_info)
		{			
		
			$company=get_company_profile($client_info);
			$subject=$company['name'].' - LMS Day Report for '.date_db_format_to_display_format($report_date);

			$is_daily_report_send=$company['is_daily_report_send'];
			$daily_report_tomail=$company['daily_report_tomail'];
			$daily_report_mail_subject=$company['daily_report_mail_subject'];
			// print_r($company); die();
			$default_currency_code=$company['default_currency_code'];
			if($is_daily_report_send=='Y' && $daily_report_tomail!='' && $daily_report_mail_subject!='')
			{
			$set_return=$this->Cronjobs_model->set_daily_report($report_date,$client_info);

			if($set_return==true)
			{
				$get_report=$this->Cronjobs_model->get_daily_report('',$client_info);
				if(count($get_report))
				{						
					$new_lead_report=$get_report['new'];
					$updated_lead_report=$get_report['updated'];
					$stage_wise_report=$get_report['stage_wise_count'];
					$user_wise_new_leads=$get_report['user_wise_new_leads'];
					$user_wise_stage=$get_report['user_wise_stage'];
					// ============================
					// Mail Acknowledgement & 
					// account manager update
					// START

					// TO CLIENT				
					$e_data=array();
					$e_data['report_date']=$report_date;
					$e_data['new_lead_report']=$new_lead_report;
					$e_data['updated_lead_report']=$updated_lead_report;
					$e_data['stage_wise_report']=$stage_wise_report;
					$e_data['user_wise_new_leads']=$user_wise_new_leads;
					$e_data['user_wise_stage']=$user_wise_stage;
					$template_str = $this->load->view('admin/email_template/template/daily_report_cronjobs_view', $e_data, true);
					// echo $template_str; die('ok');
					$to_mail=$daily_report_tomail;
					$cc_mail='';			       
					if($to_mail)
					{
						// ----------------------
						// csv

						// Day Report - All
						$array =array();
						$array[] = array('Day Report - All');
						$array[] = array(
										'Date',
										date_db_format_to_display_format($report_date)
										);
						$array[] = array(
										'New Leads',
										$new_lead_report['new_lead_count']
										);
						$array[] = array(
										'Updated Leads',
										$updated_lead_report['updated_lead_count']
										);
						if(count($stage_wise_report))
						{ 
     						foreach($stage_wise_report AS $all)
     						{
        						if($all['stage_wise_lead_count']>0)
        						{ 
        							$array[] = array(
										$all['stage_name'],
										$all['stage_wise_lead_count']
										);
        						}
            				}
        				}
     		
     					// Day Report - Existing Buyer
     					$array[] = array('');
        				$array[] = array('Day Report - Existing Buyer');
        				$array[] = array(
										'Date',
										date_db_format_to_display_format($report_date)
										);
						$array[] = array(
										'New Leads',
										$new_lead_report['paying_customer_new_lead_count']
										);
						$array[] = array(
										'Updated Leads',
										$updated_lead_report['paying_customer_updated_lead_count']
										);
						if(count($stage_wise_report))
						{ 
     						foreach($stage_wise_report AS $all)
     						{
        						if($all['paying_customer_stage_wise_lead_count']>0)
        						{ 
        							$array[] = array(
										$all['stage_name'],
										$all['paying_customer_stage_wise_lead_count']
										);
        						}
            				}
        				}

        				// User Wise Day Report
     					$array[] = array('');
        				$array[] = array('User Wise Day Report on '.date_db_format_to_display_format($report_date));

        				$array[] = array(
										'User',
										'New',
										'Stage Wise Count (all/ existing buyer)'
										);
        				if(count($user_wise_new_leads)){
        					foreach($user_wise_new_leads AS $row){
        						$stage_wise_count_str='';
        						if(count($user_wise_stage[$report_date][$row['assigned_user_id']])>0 && isset($user_wise_stage[$report_date][$row['assigned_user_id']])){ $i=1;
        							foreach($user_wise_stage[$report_date][$row['assigned_user_id']] AS $row_status){
        								$seperator='';
        								if(count($user_wise_stage[$report_date][$row['assigned_user_id']])>$i)
                    					{
                    						$seperator="|";
                    					}

        								$stage_wise_count_str .=str_replace(' ', '', $row_status['stage_name']).':'.$row_status['stage_wise_lead_count'].'/'.$row_status['paying_customer_stage_wise_lead_count'].$seperator;
        								$i++;
        							}
        						}
        						$array[] = array(
								$row['assigned_user_name'],
								$row['new_lead_count'],
								$stage_wise_count_str
								);
        					}
        				}
        				// print_r($array); die();
						$tmpName=$client_info->id.'_Day_Report';
						$csvFileName = $tmpName.".csv";        
						$this->load->helper('csv'); 
						$this->load->helper('file');  
						$csv_content = array_to_csv($array);
						$file_path = file_upload_absolute_path().'assets/uploads/clients/log/';	
						if (!file_exists($file_path.$csvFileName)){
						} 
						else {
						  unlink($file_path.$csvFileName);
						}

						if (!write_file($file_path.$csvFileName, $csv_content))
						{
								// echo 'Unable to write the file';
							$attach='';
						}
						else
						{
							$attach=$file_path.$csvFileName;
								// echo 'File written!';
						}						
						// csv
						// ----------------------

						// $mail_subject=$daily_report_mail_subject;
						$mail_subject=$subject;			
						//$this->load->library('mail_lib');
						$m_d = array();
						$m_d['from_mail']     = $company['email1'];
						$m_d['from_name']     = $company['name'];
						$m_d['to_mail']       = $to_mail;
						$m_d['cc_mail']       = $cc_mail;
						$m_d['subject']       = $mail_subject;
						$m_d['message']       = $template_str;
						$m_d['attach']        = array($attach);
						$mail_return = $this->mail_lib->send_mail($m_d,$client_info);
					}				
					// END
					// =============================
				}

				$msg="Process completed to generate day report and sent to ".$to_mail;
				$status='success';
			}
			else
			{
				$msg="Report could not be generated..";
				$status='fail';
			}				
			}
			else
			{
			$msg="May be inactive or Missing parameter (tomail / mail subject)";
			$status='fail';
			}			
			echo $status.'#'.$msg;

			// ===================================
			$post_data=array(
			'client_id'=>$client_info->client_id,
			'function_name'=>'generate_daily_report',
			'updated_db_name'=>$client_info->db_name,
			'created_at'=>date("Y-m-d H:i:s")
			);
			//$this->Client_model->create_cronjobs_log($post_data);
			// ===================================
		}
	}
}

// ========================================================
// DASHBOARD SUMMARY REPORT

public function update_dashboard_report_test($client_id='')
{
	//$client_id=$this->Client_model->get_cid_for_execute('update_dashboard_report');
	$arg=array();
	$arg['cronjobs_action']='dashboard_report';
	$arg['client_id']=$client_id;
	$client_db_info_list=$this->Client_model->get_all($arg);
	//print_r($client_db_info_list); die();
	if(count($client_db_info_list))
	{	
		foreach($client_db_info_list AS $client_info)
		{
			// dashboard_summery_count_report (count /  percentage)
			//$this->Cronjobs_model->update_dashboard_summery_count_report($client_info);
			
			// dashboard_day_wise_sales_report
			$this->Cronjobs_model->update_dashboard_day_wise_sales_report($client_info);

			// dashboard_day_wise_product_vs_lead_report		
			//$this->Cronjobs_model->update_dashboard_day_wise_product_report($client_info);

			// dashboard_day_wise_source_report
			//$this->Cronjobs_model->update_dashboard_day_wise_source_report($client_info);
			
			// dashboard_day_wise_source_report
			//$this->Cronjobs_model->update_dashboard_day_wise_lost_reason_report($client_info);

			// ==============================================


			$post_data=array(
					'client_id'=>$client_info->client_id,
                    'function_name'=>'update_dashboard_report',
                    'updated_db_name'=>$client_info->db_name,
                    'created_at'=>date("Y-m-d H:i:s")
                    );
			//$this->Client_model->create_cronjobs_log($post_data);

			
	        // $mail_data = array();        
	        // $mail_data['from_mail']     = 'info@lmsbaba.com';
	        // $mail_data['from_name']     = 'lmsbaba';
	        // $mail_data['to_mail']       = 'arupporel123@gmail.com';
	        // $mail_data['subject']       = 'Dashboard report update-'.$client_info->db_name;
	        // $mail_data['message']       = 'Dashboard report update for '.$client_info->db_name;
	        // $mail_data['attach']        = array();
	        // $r=$this->mail_lib->send_mail($mail_data,$client_info);
	        // if($r==true)
	        // {
	        // 	echo "<br>sent for ".$client_info->db_name;
	        // }
	        // else
	        // {
	        // 	echo '<br>not sent for '.$client_info->db_name;
	        // }
			// ================================================	
			//sleep(5);	
		}
	}
	die("completed");			
}

public function update_dashboard_report($client_id='')
{
	//$client_id=$this->Client_model->get_cid_for_execute('update_dashboard_report');
	$arg=array();
	$arg['cronjobs_action']='dashboard_report';
	$arg['client_id']=$client_id;
	$client_db_info_list=$this->Client_model->get_all($arg);
	//print_r($client_db_info_list); die();
	if(count($client_db_info_list))
	{	
		foreach($client_db_info_list AS $client_info)
		{
			
			// dashboard_summery_count_report (count /  percentage)
			$this->Cronjobs_model->update_dashboard_summery_count_report($client_info);
			
			// dashboard_day_wise_sales_report
			$this->Cronjobs_model->update_dashboard_day_wise_sales_report($client_info);

			// dashboard_day_wise_product_vs_lead_report		
			$this->Cronjobs_model->update_dashboard_day_wise_product_report($client_info);

			// dashboard_day_wise_source_report
			$this->Cronjobs_model->update_dashboard_day_wise_source_report($client_info);
			
			// dashboard_day_wise_source_report
			$this->Cronjobs_model->update_dashboard_day_wise_lost_reason_report($client_info);
			
			// ==============================================


			$post_data=array(
					'client_id'=>$client_info->client_id,
                    'function_name'=>'update_dashboard_report',
                    'updated_db_name'=>$client_info->db_name,
                    'created_at'=>date("Y-m-d H:i:s")
                    );
			//$this->Client_model->create_cronjobs_log($post_data);

			
	        // $mail_data = array();        
	        // $mail_data['from_mail']     = 'info@lmsbaba.com';
	        // $mail_data['from_name']     = 'lmsbaba';
	        // $mail_data['to_mail']       = 'arupporel123@gmail.com';
	        // $mail_data['subject']       = 'Dashboard report update-'.$client_info->db_name;
	        // $mail_data['message']       = 'Dashboard report update for '.$client_info->db_name;
	        // $mail_data['attach']        = array();
	        // $r=$this->mail_lib->send_mail($mail_data,$client_info);
	        // if($r==true)
	        // {
	        // 	echo "<br>sent for ".$client_info->db_name;
	        // }
	        // else
	        // {
	        // 	echo '<br>not sent for '.$client_info->db_name;
	        // }
			// ================================================	
			//sleep(5);	
		}
	}
	die("completed");			
}
// DASHBOARD SUMMARY REPORT
// ========================================================
		
public function get_deal_won_products($month=1)
{		
	$client_db_info_list=$this->Client_model->get_all();
	if(count($client_db_info_list))
	{	
		foreach($client_db_info_list AS $client_info)
		{
			$this->Cronjobs_model->get_won_products($month,$client_info);
		}
	}
	die("completed");
}
	
public function get_products_wise_lead_count($month=1)
{		
	$client_db_info_list=$this->Client_model->get_all();
	if(count($client_db_info_list))
	{	
		foreach($client_db_info_list AS $client_info)
		{
			$this->Cronjobs_model->get_products_wise_lead($month,$client_info);
		}
	}
	die("completed");
}	

public function bulk_email_send()
{	    
	$msg='';	
	$status='';
	$client_db_info_list=$this->Client_model->get_all();
	if(count($client_db_info_list))
	{	
		foreach($client_db_info_list AS $client_info)
		{

			$get_rows=$this->Customer_model->get_bulk_mail_list('',$client_info);		
			//$this->load->library('mail_lib');		
			$deleted_id_arr=[];
			$deleted_file_arr=[];
			if(count($get_rows)>0)
			{			
				foreach($get_rows AS $row)
				{
					if($row['attach_filename_with_path'])
					{
						$attach_filename_with_path=	unserialize($row['attach_filename_with_path']);
					}
					else
					{
						$attach_filename_with_path=	array();
					}
							
					$mail_data = array();
			        $mail_data['from_mail']     = $row['from_email'];
			        $mail_data['from_name']     = $row['from_name'];
			        $mail_data['to_mail']       = $row['to_mail'];			        
			        $mail_data['subject']       = $row['subject'];
			        $mail_data['message']       = $row['body'];
			        $mail_data['attach']        = $attach_filename_with_path;
			        //$mail_return = $this->mail_lib->send_mail($mail_data,$client_info);

			        // -----------------------------------------
			        $bmData=array(
			        			'customer_id'=>$row['customer_id'],
			        			'email'=>$row['to_mail'],
			        			'sent_datetime'=>date("Y-m-d H:i:s")
			        			);
			        $this->Customer_model->insertBulkMailHistort($bmData,$client_info);
			        // -------------------------------------------

			        // -------------------------------------------
			        $updateData=array(
			        				'modify_date'=>date("Y-m-d H:i:s"),
			        				'last_mail_sent'=>date("Y-m-d H:i:s")
			        				);
			        $this->Cronjobs_model->UpdateCustomer($updateData,$row['customer_id'],$client_info);
			        // --------------------------------------------

			        array_push($deleted_id_arr, $row['id']);
			        array_push($deleted_file_arr, $attach_filename_with_path[0]);

			        // ===============================================
					// History					
					$history_data=array(
							'history_type'=>'E',
							'c_id'=>$row['customer_id'],
							'updated_by_user_id'=>$row['updated_by_user_id'],
							'ip_address'=>$this->input->ip_address(),
							'comment'=>'A bulk mail has been sent to '.$row['to_name'].'('.$row['to_mail'].') by '.$row['from_name'].'('.$row['from_email'].')',
							'created_at'=>date("Y-m-d")
							);
					$tmp_log_id=$this->Customer_model->company_history_add($history_data,$client_info);				
					
					$history_data_log=array(
								'company_update_history_log_id'=>$tmp_log_id,
								'from_mail'=>$row['from_email'],
								'from_name'=>$row['from_name'],
								'to_mail'=>$row['to_mail'],
								'cc_mail'=>'',					
								'subject'=>$row['subject'],
								'body'=>$row['body'],
								'attachment'=>$attach_filename_with_path[0]
								);
					$this->Customer_model->company_history_email_detail_add($history_data_log,$client_info);
					// History
					// ===============================================

			        sleep(3);				
				}

				foreach($get_rows AS $row)
				{
					if(in_array($row['id'], $deleted_id_arr))
					{
						$this->Customer_model->delete_bulk_mail_tmp($row['id'],$client_info);	
					}
				}
				// ------------------------------------------
				// remove attachment
				/*
				if(count(array_filter($deleted_file_arr)))
				{
					foreach(array_filter($deleted_file_arr) AS $file)
					{					
						if(file_exists($file))
		        		{
		        			@unlink($file);
		        		}
					}
				}	
				*/		
	        	// remove attachment
	        	// ------------------------------------------        			
			}	
		}
	}	
	die("completed");
}


function generate_performance_scorecard_approval_report($client_id='')
{	
	die('-----');
	date_default_timezone_set('Asia/Kolkata');
	// $current_datetime=date("Y-m-d");	
	$current_datetime='2022-07-31';
	$arg=array();
	$arg['client_id']=$client_id;
	$arg['cronjobs_action']='';
	// print_r($arg); die();
	$client_db_info_list=$this->Client_model->get_all($arg);
	// print_r($client_db_info_list); die();
	if(count($client_db_info_list))
	{	
		foreach($client_db_info_list AS $client_info)
		{
			$return=$this->My_performance_model->get_kpi_setting_user_wise_in_cronjobs($client_info);
			if(count($return))
			{
				foreach($return AS $row)
				{
					$post_data=array(
							'report_on'=>$current_datetime,
							'user_id'=>$row['user_id'],
							'set_target_by'=>$row['set_target_by'],
							'target_threshold'=>$row['target_threshold'],
							'target_threshold_for_x_consecutive_month'=>$row['target_threshold_for_x_consecutive_month'],
							'is_apply_pip'=>$row['is_apply_pip'],
							'is_apply_pli'=>$row['is_apply_pli'],
							'monthly_salary'=>$row['monthly_salary'],
							'pli_in'=>$row['pli_in'],
							'total_score_obtained'=>($row['total_score_obtained']>0)?$row['total_score_obtained']:0,
							'grace_score'=>0,
							'created_at'=>date("Y-m-d H:i:s")
					);
					$kpi_user_wise_report_id=$this->My_performance_model->add_kpi_user_wise_report_log($post_data,$client_info);
					if($kpi_user_wise_report_id)
					{
						if(count($row['details']))
						{
							foreach($row['details'] AS $detail)
							{
								$weighted_score=$detail['weighted_score']; 
								$target_achieved=$detail['achieved'];
								$target=$detail['target'];
								$score_obtained=($weighted_score*($target_achieved/$target*100)/100);
								$post_data=array(								
										'kpi_user_wise_report_log_id'=>$kpi_user_wise_report_id,
										'kpi_id'=>$detail['kpi_id'],
										'weighted_score'=>$detail['weighted_score'],
										'target'=>$detail['target'],
										'achieved'=>$detail['achieved'],
										'score_obtained'=>($score_obtained>0)?$score_obtained:0,
										'min_target_threshold'=>$detail['min_target_threshold']
								);
								$this->My_performance_model->add_kpi_user_wise_report_log_detail($post_data,$client_info);
							}
						}
						
					}
					
				}
			}
			// ==========================================
			$post_data=array(
					'client_id'=>$client_info->client_id,
                    'function_name'=>'get_lead_from_indiamart',
                    'updated_db_name'=>$client_info->db_name,
                    'created_at'=>date("Y-m-d H:i:s")
                    );
			//$this->Client_model->create_cronjobs_log($post_data);
			// ============================================	
			
		}
	}	
	die('completed');	
}

// ==========================================================
// FORCE DOWNLOAD FROM INDIAMART
function force_download_from_indiamart_ajax()
{ 	
	$session_data=$this->session->userdata('admin_session_data');
	$client_id=$session_data['client_id'];
    $return=$this->get_lead_from_indiamart_by_manual($client_id);	
}

function manual_download_from_indiamart()
{ 	die('no');
	// $client_id='204';
    // $return=$this->get_lead_from_indiamart($client_id);
	
}

// FORCE DOWNLOAD FROM INDIAMART
// ========================================================

// ======================================================
// FORCE DOWNLOAD FROM JustDial
function manual_download_from_justdial()
{ 
	$client_id=17;
  $return=$this->get_lead_from_justdial($client_id);
}
// FORCE DOWNLOAD FROM JustDial
// ======================================================


// ======================================================
// FORCE CREATE LEAD FROM RENEWAL
function force_create_lead_from_renewal_ajax()
{ 
	$session_data=$this->session->userdata('admin_session_data');
	$client_id=$session_data['client_id'];
    $return=$this->create_lead_from_renewal($client_id);
}
// FORCE CREATE LEAD FROM RENEWAL
// ======================================================

// ======================================================
// FORCE CREATE LEAD FROM RENEWAL
public function force_update_dashboard_report_ajax()
{ 
	//$session_data=$this->session->userdata('admin_session_data');
	//$client_id=$session_data['client_id'];
	$client_id=90;
	$return=$this->update_dashboard_report_test($client_id);
}
// FORCE CREATE LEAD FROM RENEWAL
// ======================================================



}