<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjobs extends CI_Controller {
	function __construct(){
		parent :: __construct();
		$this->load->model(array('Cronjobs_model','Customer_model','Indiamart_model','Source_model','Countries_model','Cities_model','States_model','Lead_model','History_model','User_model','Setting_model','Indiamart_setting_model','Tradeindia_setting_model','Tradeindia_model','Email_forwarding_setting_model','Client_model','Justdial_setting_model','Justdial_model','renewal_model','Sms_forwarding_setting_model','My_performance_model','Aajjo_setting_model','Aajjo_model'));
		
		$this->load->library('mail_lib');
		// echo ENVIRONMENT; die();
		
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

	function get_lead_from_indiamart_get($client_id='')
	{	
		try {

			if($client_id==''){				
				$get_limit_offset='';	
			}
			else{
				$get_limit_offset='';
				die('Restricted');
			}
			date_default_timezone_set('Asia/Kolkata');
			$current_datetime=date("Y-m-d");
			$start_datetime = date("d-M-Y",strtotime("-1 days", strtotime($current_datetime)));
			$end_datetime=date("d-M-Y",strtotime($current_datetime));	
			$arg=array();
			$arg['client_id']=$client_id;
			$arg['cronjobs_action']='indiamart';	
			$arg['get_limit_offset']=$get_limit_offset;
			$client_db_info_list=$this->Client_model->get_all($arg);
			// print_r($client_db_info_list); die();
			if(count($client_db_info_list))
			{
				foreach($client_db_info_list AS $client_info)
				{				
					try {
							// ===============================================================
							// Cron log add			
							// $post_data=array(
							// 	'client_id'=>$client_info->client_id,
							// 	'function_name'=>'get_lead_from_indiamart',
							// 	'updated_db_name'=>$client_info->db_name,
							// 	'script_start'=>date("H:i:s"),
							// 	'created_at'=>date("Y-m-d H:i:s")
							// 	);		
							// $update_cronjobs_log_id=$this->Client_model->create_cronjobs_log($post_data);	
							// if(!$update_cronjobs_log_id){
							// 	throw new Exception("cron id not exist");
							// }
							// Cron log add			
							// ===============================================================
							
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
								$indiamart_credentials=$this->Cronjobs_model->indiamart_setting_GetIndiamartCredentials($client_info);									
								if(count($indiamart_credentials)>0)
								{
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
											// echo $url; die('old');
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
											// echo $url; die('new');
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
							// $post_data=array(
							// 	'msg'=>$msg_log,
							// 	'script_end'=>date("H:i:s"),
							// 	);		
							// $this->Client_model->update_cronjobs_log($post_data,$update_cronjobs_log_id);				
							// ===============================================================
							
					}
					catch(Exception $e) {
						echo 'Message: ' .$e->getMessage();die();
					}
					finally{
						echo $client_info->db_name.':Success~~~';
					}
				}				
				$this->get_lead_from_indiamart_set('');
			}

			
			
		}
		catch(Exception $e) {
			echo 'Message: ' .$e->getMessage();die();
		}
		finally{
			echo '======All-success====';
		}
	}

	function get_lead_from_indiamart_set($client_id='')
	{	
		try {

			if($client_id==''){				
				$get_limit_offset='';	
			}
			else{
				$get_limit_offset='';
				die('Restricted');
			}				
			$arg=array();
			$arg['client_id']=$client_id;
			$arg['cronjobs_action']='indiamart';	
			$arg['get_limit_offset']=$get_limit_offset;
			$client_db_info_list=$this->Client_model->get_all($arg);
			
			if(count($client_db_info_list))
			{
				foreach($client_db_info_list AS $client_info)
				{				
					try {
							// ===============================================================
							// Cron log add			
							// $post_data=array(
							// 	'client_id'=>$client_info->client_id,
							// 	'function_name'=>'get_lead_from_indiamart',
							// 	'updated_db_name'=>$client_info->db_name,
							// 	'script_start'=>date("H:i:s"),
							// 	'created_at'=>date("Y-m-d H:i:s")
							// 	);		
							// $update_cronjobs_log_id=$this->Client_model->create_cronjobs_log($post_data);	
							// if(!$update_cronjobs_log_id){
							// 	throw new Exception("cron id not exist");
							// }
							// Cron log add			
							// ===============================================================
							
							$this->Cronjobs_model->initialise($client_info);
							$msg_log='';
							$flag=0;									
							
							if($flag==0)
							{
								$msg_log='';
								$msg_log='Cronjobs Hit For Set|';				
								$indiamart_credentials=$this->Cronjobs_model->indiamart_setting_GetIndiamartCredentials($client_info);			
								// Lead Acknowledgement id 1				
								$email_forwarding_setting=$this->Cronjobs_model->email_forwarding_setting_GetDetails(1,$client_info);
								// SMS for Acknowledgement id 1				
								// $sms_forwarding_setting=$this->Cronjobs_model->sms_forwarding_setting_GetDetails(1,$client_info);
								
								if(count($indiamart_credentials)>0)
								{			
									foreach($indiamart_credentials AS $credentials)
									{
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
												$cust_arr=array(
													'email'=>$cust_email,
													'mobile'=>$cust_mobile,
													'im_query_id'=>$im_query_id
																);								
												$get_customer_decision=$this->Cronjobs_model->cust_get_decision($cust_arr,$client_info);

												// ------------------------
												// get message 
												$im_history_data=array('msg'=>$get_customer_decision['msg']);								
												$this->Cronjobs_model->im_update($im_history_data,$im['id'],$client_info);
												// ------------------------
												
												if($get_customer_decision['status']==TRUE)
												{
													
													$com_country_code='';
													$com_country_id='';
													if($im['COUNTRY_ISO'])
													{										
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
														$get_city_id=$this->Cronjobs_model->cities_get_city_id_by_name($im['ENQ_CITY'],$client_info);
														if($get_city_id!=false)
														{
															$com_city_id=$get_city_id;
														}
													}
													$com_state_id='';	
													if($im['ENQ_STATE'])
													{										
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
														$com_source_id=$this->Cronjobs_model->source_get_source_id_by_name($com_source_text_tmp,$client_info);
														if($com_source_id==0)
														{
															$post_source=array(
																'parent_id'=>0,
																'name'=>$com_source_text
																);											
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
															$this->Cronjobs_model->indiamart_setting_EditIndiamartCredentials($tmpdata,$indiamart_setting_id,$client_info);
															// update to setting table
															// ----------------------------------
														}
														$com_contact_person_tmp=$com_contact_person;
														
													}
													else if($get_customer_decision['msg']=='one_customer_exist')
													{
														$com_company_id=$get_customer_decision['customer_id'];										
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
															$this->Cronjobs_model->lead_CreateLeadStageLog($stage_post_data,$client_info);
															
															// Insert Status Log
															$status_post_data=array(
																	'lead_id'=>$lead_id,
																	'status_id'=>'2',
																	'status'=>'WARM',
																	'create_datetime'=>date("Y-m-d H:i:s")
																);											
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
															$this->Cronjobs_model->lead_create_lead_assigned_user_log($post_data,$client_info);
															
															$assigned_user_name='';
															if($assigned_user_id){
																$assigned_user_name=$this->Cronjobs_model->GetUserName($assigned_user_id);
															}

															$update_by=1;				
															$date=date("Y-m-d H:i:s");				
															$ip_addr=$_SERVER['REMOTE_ADDR'];				
															$message="A new lead has been created as &quot;".$lead_title."&quot;.";
															if($assigned_user_name){
																$message .=" The lead assigned to ".$assigned_user_name."";
															}
															$comment_title=NEW_LEAD_CREATE_IM;
															$historydata=array(
															'title'=>$comment_title,
															'lead_id'=>$lead_id,
															'comment'=>addslashes($message),
															'attach_file'=>$attach_filename,
															'create_date'=>$date,
															'user_id'=>$update_by,
															'ip_address'=>$ip_addr
															);											
															$this->Cronjobs_model->history_CreateHistory($historydata,$client_info);
															
															
															// if((isset($email_forwarding_setting['is_mail_send']) && $email_forwarding_setting['is_mail_send']=='Y') || count($sms_forwarding_setting)>0)
															// if(count($sms_forwarding_setting))
															if((isset($email_forwarding_setting['is_mail_send']) && $email_forwarding_setting['is_mail_send']=='Y'))
															{
																$assigned_to_user_data=$this->Cronjobs_model->user_get_employee_details($assigned_user_id,$client_info);
																$company=$this->Cronjobs_model->setting_GetCompanyData($client_info);
																$lead_info=$this->Cronjobs_model->lead_GetLeadData($lead_id,$client_info);												
																$customer=$this->Cronjobs_model->cust_GetCustomerData($lead_info->customer_id,$client_info);
																$get_user_info=$this->Cronjobs_model->get_user_info($assigned_user_id);
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
																$e_data['company']=$company;
																$e_data['assigned_to_user']=$assigned_to_user_data;
																$e_data['customer']=$customer;
																$e_data['lead_info']=$lead_info;
																$e_data['get_company_name_initials']=$this->Cronjobs_model->get_company_name_initials($client_info);
																$e_data['rander_company_address']=$this->Cronjobs_model->rander_company_address_cronjobs();
																$e_data['client_info']=$client_info;
																$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_cronjobs_view', $e_data, true);
																$m_email=$this->Cronjobs_model->get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);	
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
																$self_cc_mail='';
																$update_by_name='';
																	
																if(count($get_user_info)){
																	$self_cc_mail=$get_user_info['email'];
																	$update_by_name=$get_user_info['name'];
																}
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
																if(is_array($cc_mail_arr)) { $cc_mail=implode(",", $cc_mail_arr); } else { $cc_mail=$cc_mail_arr; }
																// cc mail assign logic
																// --------------------
																
																if(trim($to_mail))
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
																	$post_data=array();
																	$post_data=array(
																			"mail_for"=>'IM',
																			"from_mail"=>$self_cc_mail,
																			"from_name"=>$update_by_name,
																			"to_mail"=>$to_mail,
																			"cc_mail"=>$cc_mail,
																			"subject"=>$mail_subject,
																			"message"=>$template_str,
																			"attach"=>'',
																			"created_at"=>date("Y-m-d H:i:s")
																	);
																	$this->Cronjobs_model->cronjobs_mail_fire_add($post_data);

																	// if($client_info->client_id=='265' || $client_info->client_id=='2' || $client_info->client_id=='3' || $client_info->client_id=='206' || $client_info->client_id=='236')
																	// {
																	// 	$post_data=array();
																	// 	$post_data=array(
																	// 		'client_id'=>$client_info->client_id,
																	// 		'function_name'=>'Auto-mail fire at the time of lead coming from IM./FRom:'.$self_cc_mail.'/TO:'.$to_mail.'/CC:'.$cc_mail,
																	// 		'updated_db_name'=>$client_info->db_name,
																	// 		'msg'=>$mail_subject,	
																	// 		'created_at'=>date("Y-m-d H:i:s"),												
																	// 		'script_start'=>date("H:i:s"),
																	// 		'script_end'=>date("H:i:s")													
																	// 		);
																	// 	$this->Client_model->create_cronjobs_log($post_data);
																	// }
																}
																
																// END
																// =============================																
															}
															
															// SMS ALERT											
															/*if(count($sms_forwarding_setting))
															{			
																if($sms_forwarding_setting['is_sms_send']=='Y')
																{																	
																	$m_mobile=$this->Cronjobs_model->get_manager_and_skip_manager_mobile_arr($assigned_user_id,$client_info);
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
																		if(count($get_user_info)){
																			$relationship_manager_mobile=$get_user_info['mobile'];
																		}																							
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
															}*/														
														}
													}													
												}
												else{}
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
							// $post_data=array(
							// 	'msg'=>$msg_log,
							// 	'script_end'=>date("H:i:s"),
							// 	);		
							// $this->Client_model->update_cronjobs_log($post_data,$update_cronjobs_log_id);				
							// ===============================================================
							
					}
					catch(Exception $e) {
						echo 'Message: ' .$e->getMessage();die();
					}
					finally{
						echo $client_info->db_name.':Success~~~';
					}
				}
				// $this->get_lead_from_indiamart_mail_fire();		
			}
			
		}
		catch(Exception $e) {
			echo 'Message: ' .$e->getMessage();die();
		}
		finally{
			echo '======All-success====';
		}
	}

	/*function get_lead_from_indiamart_mail_fire()
	{
		try {					
			$arg=array();
			$arg['client_id']='';
			$arg['cronjobs_action']='indiamart';	
			$arg['get_limit_offset']='';
			$client_db_info_list=$this->Client_model->get_all($arg);
			// print_r($client_db_info_list); die();
			if(count($client_db_info_list))
			{
				foreach($client_db_info_list AS $client_info)
				{				
					try {
							// ===============================================================
							// Cron log add			
							// $post_data=array(
							// 	'client_id'=>$client_info->client_id,
							// 	'function_name'=>'get_lead_from_indiamart',
							// 	'updated_db_name'=>$client_info->db_name,
							// 	'script_start'=>date("H:i:s"),
							// 	'created_at'=>date("Y-m-d H:i:s")
							// 	);		
							// $update_cronjobs_log_id=$this->Client_model->create_cronjobs_log($post_data);	
							// if(!$update_cronjobs_log_id){
							// 	throw new Exception("cron id not exist");
							// }
							// Cron log add			
							// ===============================================================							
							$this->Cronjobs_model->initialise($client_info);
							$msg_log='';
							$flag=0;									
							
							if($flag==0)
							{
								$smtp_data=$this->Cronjobs_model->GetSmtpData();
								if(count($smtp_data)>0)
								{
									$smtp_data_tmp=$smtp_data;
								}	
								else
								{
									$smtp_data_tmp=$this->Cronjobs_model->GetDefaultSmtpData();
								}	

								$msg_log='';
								$msg_log='Cronjobs Hit For Mail Fire';																	
								$get_im_mail=$this->Cronjobs_model->cronjobs_mail_fire_rows('IM');		
								$i=0;								
								if(count($get_im_mail))
								{
									foreach($get_im_mail as $im_mail)
									{										
										// ============================
										// Mail Acknowledgement & 
										// account manager update
										// START																			
										$m_d['smtp_data']     = $smtp_data_tmp;											
										$m_d['from_mail']     = $im_mail['from_mail'];
										$m_d['from_name']     = $im_mail['from_name'];
										$m_d['to_mail']       = $im_mail['to_mail'];									
										$m_d['cc_mail']       = $im_mail['cc_mail'];
										// $m_d['bcc_mail']      = 'saswatiporel123@gmail.com';
										$m_d['subject']       = $im_mail['subject'];
										$m_d['message']       = $im_mail['message'];
										$m_d['attach']        = array();
										if($im_mail['to_mail']){
											$this->mail_lib->send_mail_cronjobs($m_d);
										}										
										// END
										// =============================
										$i++;
									}			
								}
								$this->Cronjobs_model->cronjobs_mail_fire_truncate('IM');
							}
							// ===============================================================			
							// $post_data=array(
							// 	'msg'=>$msg_log.'- Mail sent count:'.$i,
							// 	'script_end'=>date("H:i:s"),
							// 	);		
							// $this->Client_model->update_cronjobs_log($post_data,$update_cronjobs_log_id);				
							// ===============================================================
					}
					catch(Exception $e) {
						echo 'Message: ' .$e->getMessage();die();
					}
					finally{
						echo $client_info->db_name.':Success~~~';
					}
				}
			}
			
		}
		catch(Exception $e) {
			echo 'Message: ' .$e->getMessage();die();
		}
		finally{
			echo '======All-success====';
		}
		
	}*/

	function action_mail_fire()
	{
		try {					
			$arg=array();
			$arg['client_id']='';
			$arg['cronjobs_action']='';	
			$arg['get_limit_offset']='';
			$client_db_info_list=$this->Client_model->get_all($arg);			
			if(count($client_db_info_list))
			{
				foreach($client_db_info_list AS $client_info)
				{				
					try {													
							$this->Cronjobs_model->initialise($client_info);
							$msg_log='';
							$flag=0;
							
							if($flag==0)
							{	
								$smtp_data=$this->Cronjobs_model->GetSmtpData();
								if($smtp_data){
									$smtp_data_tmp=$smtp_data;
								}	
								else{
									$smtp_data_tmp=$this->Cronjobs_model->GetDefaultSmtpData();
								}
								$msg_log='';
								$get_mail=array();																									
								$get_mail=$this->Cronjobs_model->get_mail_fire_rows(20);
								$i=0;						
								if(count($get_mail))
								{
									$msg_log .="|";
									foreach($get_mail as $mail)
									{
										if($mail['id']!='' && $mail['to_mail']!='')
										{
											$affected=$this->Cronjobs_model->cronjobs_mail_fire_delete($mail['id']);
											if($affected==true)
											{
												$m_d=array();																				
												$m_d['smtp_data']     = $smtp_data_tmp;											
												$m_d['from_mail']     = $mail['from_mail'];
												$m_d['from_name']     = $mail['from_name'];
												$m_d['to_mail']       = $mail['to_mail'];									
												$m_d['cc_mail']       = $mail['cc_mail'];																																																				
												$m_d['subject']       = $mail['subject'];
												$m_d['message']       = $mail['message'];
												$m_d['attach']        = $mail['attach'];
												$this->mail_lib->send_mail_cronjobs($m_d);
												$msg_log .=$mail['mail_for'].'-Mail Fire~';												
												// if($client_info->client_id=='265' || $client_info->client_id=='2' || $client_info->client_id=='3' || $client_info->client_id=='206' || $client_info->client_id=='236')
												// {
													// $post_data=array(
													// 	'client_id'=>$client_info->client_id,
													// 	'function_name'=>'Deleted ID:-'.$mail['id'],
													// 	'updated_db_name'=>$client_info->db_name,
													// 	'msg'=>$mail['subject'],	
													// 	'created_at'=>date("Y-m-d H:i:s"),												
													// 	'script_start'=>date("H:i:s"),
													// 	'script_end'=>date("H:i:s")													
													// 	);
													// $this->Client_model->create_cronjobs_log($post_data);
												// }
											}											
											$i++;											
										}
									}	
									$msg_log .="|";		
								}
							}							
					}
					catch(Exception $e) {
						echo 'Message: ' .$e->getMessage();die();
					}
					finally{
						echo $client_info->db_name.':Success~~~';
					}
				}
			}			
		}
		catch(Exception $e) {
			echo 'Message: ' .$e->getMessage();die();
		}
		finally{
			echo '======All-success====';
		}		
	}

	function delete_mail_junk_data()
	{
		try {					
			$arg=array();
			$arg['client_id']='';
			$arg['cronjobs_action']='';	
			$arg['get_limit_offset']='';
			$client_db_info_list=$this->Client_model->get_all($arg);			
			if(count($client_db_info_list))
			{
				foreach($client_db_info_list AS $client_info)
				{				
					try {							
							$this->Cronjobs_model->initialise($client_info);
							$msg_log='';
							$flag=0;							
							if($flag==0)
							{
								$msg_log='';																									
								$this->Cronjobs_model->delete_mail_junK_data();
							}							
					}
					catch(Exception $e) {
						echo 'Message: ' .$e->getMessage();die();
					}
					finally{
						echo $client_info->db_name.':Success~~~';
					}
				}
			}			
		}
		catch(Exception $e) {
			echo 'Message: ' .$e->getMessage();die();
		}
		finally{
			echo '======All-success====';
		}		
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
				
				if($flag==0)
				{
					$msg_log='';
					$msg_log='Cronjobs Hit|';					
					$indiamart_credentials=$this->Cronjobs_model->indiamart_setting_GetIndiamartCredentials($client_info);					
					if(count($indiamart_credentials)>0)
					{
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
				}									
			}
			$this->get_lead_from_indiamart_by_manual_set($client_id);			
		}
	}

	function get_lead_from_indiamart_by_manual_set($client_id='')
	{	
		if($client_id=='')
		{
			echo 'fail#Parameter is missing.';die();
		}
			
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
				
				if($flag==0)
				{
					$msg_log='';
					$msg_log='Cronjobs Manual Hit|';					
					$indiamart_credentials=$this->Cronjobs_model->indiamart_setting_GetIndiamartCredentials($client_info);			
					// Lead Acknowledgement id 1					
					$email_forwarding_setting=$this->Cronjobs_model->email_forwarding_setting_GetDetails(1,$client_info);
					// SMS for Acknowledgement id 1					
					$sms_forwarding_setting=$this->Cronjobs_model->sms_forwarding_setting_GetDetails(1,$client_info);					
					if(count($indiamart_credentials)>0)
					{			
						foreach($indiamart_credentials AS $credentials)
						{
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
									$cust_arr=array(
										'email'=>$cust_email,
										'mobile'=>$cust_mobile,
										'im_query_id'=>$im_query_id
													);									
									$get_customer_decision=$this->Cronjobs_model->cust_get_decision($cust_arr,$client_info);

									// ------------------------
									// get message 
									$im_history_data=array('msg'=>$get_customer_decision['msg']);									
									$this->Cronjobs_model->im_update($im_history_data,$im['id'],$client_info);
									// ------------------------
									
									if($get_customer_decision['status']==TRUE)
									{
										
										$com_country_code='';
										$com_country_id='';
										if($im['COUNTRY_ISO'])
										{											
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
											$get_city_id=$this->Cronjobs_model->cities_get_city_id_by_name($im['ENQ_CITY'],$client_info);
											if($get_city_id!=false)
											{
												$com_city_id=$get_city_id;
											}
										}
										$com_state_id='';	
										if($im['ENQ_STATE'])
										{											
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
											$com_source_id=$this->Cronjobs_model->source_get_source_id_by_name($com_source_text_tmp,$client_info);
											if($com_source_id==0)
											{
												$post_source=array(
													'parent_id'=>0,
													'name'=>$com_source_text
													);												
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
												$this->Cronjobs_model->indiamart_setting_EditIndiamartCredentials($tmpdata,$indiamart_setting_id,$client_info);
												// update to setting table
												// ----------------------------------
											}
											$com_contact_person_tmp=$com_contact_person;
											
										}
										else if($get_customer_decision['msg']=='one_customer_exist')
										{
											$com_company_id=$get_customer_decision['customer_id'];											
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
												$this->Cronjobs_model->lead_CreateLeadStageLog($stage_post_data,$client_info);
												
												// Insert Status Log
												$status_post_data=array(
														'lead_id'=>$lead_id,
														'status_id'=>'2',
														'status'=>'WARM',
														'create_datetime'=>date("Y-m-d H:i:s")
													);												
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
												
												$this->Cronjobs_model->lead_create_lead_assigned_user_log($post_data,$client_info);

												$assigned_user_name='';
												if($assigned_user_id){
													$assigned_user_name=$this->Cronjobs_model->GetUserName($assigned_user_id);
												}

												$update_by=1;				
												$date=date("Y-m-d H:i:s");				
												$ip_addr=$_SERVER['REMOTE_ADDR'];				
												$message="A new lead has been created as &quot;".$lead_title."&quot;";
												if($assigned_user_name){
													$message .=" The lead assigned to ".$assigned_user_name."";
												}
												$comment_title=NEW_LEAD_CREATE_IM;
												$historydata=array(
														'title'=>$comment_title,
														'lead_id'=>$lead_id,
														'comment'=>addslashes($message),
														'attach_file'=>$attach_filename,
														'create_date'=>$date,
														'user_id'=>$update_by,
														'ip_address'=>$ip_addr
														);												
												$this->Cronjobs_model->history_CreateHistory($historydata,$client_info);

												
												if($email_forwarding_setting['is_mail_send']=='Y' || count($sms_forwarding_setting)>0)
												{
													$assigned_to_user_data=$this->Cronjobs_model->user_get_employee_details($assigned_user_id,$client_info);													
													$company=$this->Cronjobs_model->setting_GetCompanyData($client_info);													
													$lead_info=$this->Cronjobs_model->lead_GetLeadData($lead_id,$client_info);													
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
													$e_data['company']=$company;
													$e_data['assigned_to_user']=$assigned_to_user_data;
													$e_data['customer']=$customer;
													$e_data['lead_info']=$lead_info;
													// $e_data['get_company_name_initials']=get_company_name_initials($client_info);
													// $e_data['rander_company_address']=rander_company_address_cronjobs('email_template',$client_info);
													$e_data['get_company_name_initials']=$this->Cronjobs_model->get_company_name_initials($client_info);
													$e_data['rander_company_address']=$this->Cronjobs_model->rander_company_address_cronjobs();
													$e_data['client_info']=$client_info;
													$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_cronjobs_view', $e_data, true);
													
													// $m_email=get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);
													$m_email=$this->Cronjobs_model->get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);

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

														$post_data=array();
														$post_data=array(
																"mail_for"=>'IM',
																"from_mail"=>$self_cc_mail,
																"from_name"=>$update_by_name,
																"to_mail"=>$to_mail,
																"cc_mail"=>$cc_mail,
																"subject"=>$mail_subject,
																"message"=>$template_str,
																"attach"=>'',
																"created_at"=>date("Y-m-d H:i:s")
														);
														$this->Cronjobs_model->cronjobs_mail_fire_add($post_data);

														// $m_d = array();														
														// $m_d['from_mail']     = $self_cc_mail;
														// $m_d['from_name']     = $update_by_name;
														// $m_d['to_mail']       = $to_mail;
														// $m_d['cc_mail']       = $cc_mail;
														// $m_d['subject']       = $mail_subject;
														// $m_d['message']       = $template_str;
														// $m_d['attach']        = array();
														// $mail_return = $this->mail_lib->send_mail($m_d,$client_info);
													}
													
													// END
													// =============================
												}
												
												// SMS ALERT												
												// if(count($sms_forwarding_setting))
												if($sms_forwarding_setting)
												{			
													if($sms_forwarding_setting['is_sms_send']=='Y')
													{
														// $m_mobile=get_manager_and_skip_manager_mobile_arr($assigned_user_id,$client_info);
														$m_mobile=$this->Cronjobs_model->get_manager_and_skip_manager_mobile_arr($assigned_user_id,$client_info);
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
				// $post_data=array(
				// 	'client_id'=>$client_info->client_id,
				// 	'function_name'=>'get_lead_from_indiamart',
				// 	'updated_db_name'=>$client_info->db_name,
				// 	'msg'=>$msg_log,
				// 	'created_at'=>date("Y-m-d H:i:s")
				// 	);	
				// $this->Client_model->create_cronjobs_log($post_data);				
				// ===============================================================					
			}	
		}
	}

	function get_lead_from_tradeindia_get($client_id='')
	{	
		date_default_timezone_set('Asia/Kolkata');
		$current_datetime=date("Y-m-d");	
		$from_date=date("Y-m-d",strtotime($current_datetime));	
		$to_date=date("Y-m-d",strtotime($current_datetime));
		$arg=array();
		$arg['cronjobs_action']='tradeindia';
		$arg['client_id']=$client_id;
		$client_db_info_list=$this->Client_model->get_all($arg);
		if(count($client_db_info_list))
		{	
			foreach($client_db_info_list AS $client_info)
			{
				$this->Cronjobs_model->initialise($client_info);
				$tradeindia_credentials=$this->Cronjobs_model->tradeindia_setting_GetCredentials($client_info);	
				// Lead Acknowledgement id 1
				if(count($tradeindia_credentials)>0)
				{
					
					$this->Cronjobs_model->tradeindia_truncate($client_info);
					foreach($tradeindia_credentials AS $ti)
					{
						$tradeindia_setting_id=$ti['id'];					
						$userid=$ti['userid'];
						$profileid=$ti['profileid'];	
						$ti_key=$ti['ti_key'];
						$url = "https://www.tradeindia.com/utils/my_inquiry.html";	
						
							
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
						
						$i=0;
						if(!isset($return_arr[0]->Error_Message))
						{
							if(is_array($return_arr))
							{
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
										$message=nl2br($row->message);
										$message=strip_tags($message,'<br>');
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
												'message'=>$message,
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
										$this->Cronjobs_model->tradeindia_insert($post,$client_info);
									}								
									$i++;				
								}
							}
							
						}
						
					}
				}				
			}
			
			$this->get_lead_from_tradeindia_set('');
		}	
	}

	function get_lead_from_tradeindia_set($client_id='')
	{			
		$arg=array();
		$arg['cronjobs_action']='tradeindia';
		$arg['client_id']=$client_id;
		$client_db_info_list=$this->Client_model->get_all($arg);
		if(count($client_db_info_list))
		{	
			foreach($client_db_info_list AS $client_info)
			{
				$this->Cronjobs_model->initialise($client_info);
				$tradeindia_credentials=$this->Cronjobs_model->tradeindia_setting_GetCredentials($client_info);	
				// Lead Acknowledgement id 1				
				$email_forwarding_setting=$this->Cronjobs_model->email_forwarding_setting_GetDetails(1,$client_info);			

				if(count($tradeindia_credentials)>0)
				{			
					foreach($tradeindia_credentials AS $credentials)
					{
						$get_ti=$this->Cronjobs_model->tradeindia_get_rows($credentials['id'],$client_info);
						if(count($get_ti))
						{
							if($credentials['assign_rule']=='1')
							{
								$tradeindia_assign_to=unserialize($credentials['assign_to']);
								$assign_start=isset($credentials['assign_start'])?$credentials['assign_start']:0;
								$assign_end=(count($tradeindia_assign_to)-1);
							}

							foreach($get_ti as $ti)
							{
								$cust_email=$ti['sender_email'];				
								$cust_mobile_tmp=end(explode("-", $ti['sender_mobile']));
								$cust_mobile=str_replace("+91","",$cust_mobile_tmp);
								$rfi_id=$ti['rfi_id'];							
								$cust_arr=array(
												'email'=>$cust_email,
												'mobile'=>$cust_mobile,
												'rfi_id'=>$rfi_id
												);								
								$get_customer_decision=$this->Cronjobs_model->customer_get_decision_for_ti($cust_arr,$client_info);
								

								// ------------------------
								// get message 
								$ti_history_data=array('msg'=>$get_customer_decision['msg']);								
								$this->Cronjobs_model->tradeindia_update($ti_history_data,$ti['id'],$client_info);
								// ------------------------

								if($get_customer_decision['status']==TRUE)
								{
									
									$com_country_code='';
									$com_country_id='';
									if($ti['sender_country'])
									{										
										$get_country=$this->Cronjobs_model->countries_get_country_by_name($ti['sender_country'],$client_info);
										if($get_country!=false)
										{
											$com_country_id=$get_country->id;
											$com_country_code=$get_country->phonecode;

										}
									}
									
									$assigned_user_id=isset($tradeindia_assign_to[$assign_start])?$tradeindia_assign_to[$assign_start]:$tradeindia_assign_to[0];
									$tradeindia_setting_id=$ti['tradeindia_setting_id'];								
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
										$get_city_id=$this->Cronjobs_model->cities_get_city_id_by_name($ti['sender_city'],$client_info);
										if($get_city_id!=false)
										{
											$com_city_id=$get_city_id;
										}
									}
									$com_state_id='';	
									if($ti['sender_state'])
									{										
										$get_state_id=$this->Cronjobs_model->states_get_state_id_by_name($ti['sender_state'],$client_info);
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
										$com_source_id=$this->Cronjobs_model->source_get_source_id_by_name($com_source_text_tmp,$client_info);
										if($com_source_id==0)
										{
											$post_source=array(
																'parent_id'=>0,
																'name'=>$com_source_text
																);											
											$com_source_id=$this->Cronjobs_model->source_add($post_source,$client_info);
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
										$assigned_user_id=$this->Cronjobs_model->tradeindia_setting_get_keyword_rule5_wise_assigned_user_id($arr,$client_info);
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
										$assigned_user_id=$this->Cronjobs_model->tradeindia_setting_get_rule_wise_assigned_user_id($arr,$client_info);
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
											$this->Cronjobs_model->tradeindia_setting_EditCredentials($tmpdata,$tradeindia_setting_id,$client_info);
											// update to setting table
											// ----------------------------------
										}
										// RULE WISE USER
										// -------------------
										$com_contact_person_tmp=$com_contact_person;
										
									}
									else if($get_customer_decision['msg']=='one_customer_exist')
									{
										$com_company_id=$get_customer_decision['customer_id'];										
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
										
										$lead_id=$this->Cronjobs_model->lead_CreateLead($lead_post_data,$client_info);
										if($lead_id)
										{										
											
											// Insert Stage Log
											$stage_post_data=array(
													'lead_id'=>$lead_id,
													'stage_id'=>'1',
													'stage'=>'PENDING',
													'stage_wise_msg'=>'',
													'create_datetime'=>date("Y-m-d H:i:s")
												);
											
											$this->Cronjobs_model->lead_CreateLeadStageLog($stage_post_data,$client_info);

											// Insert Status Log
											$status_post_data=array(
													'lead_id'=>$lead_id,
													'status_id'=>'2',
													'status'=>'WARM',
													'create_datetime'=>date("Y-m-d H:i:s")
												);
											
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
											
											$this->Cronjobs_model->lead_create_lead_assigned_user_log($post_data,$client_info);
											
										
											$assigned_user_name='';
											if($assigned_user_id){
												$assigned_user_name=$this->Cronjobs_model->GetUserName($assigned_user_id);
											}
											$update_by=1;				
											$date=date("Y-m-d H:i:s");				
											$ip_addr=$_SERVER['REMOTE_ADDR'];				
											$message="A new lead has been created as &quot;".$lead_title."&quot;";
											if($assigned_user_name){
												$message .=" The lead assigned to ".$assigned_user_name."";
											}
											$comment_title=NEW_LEAD_CREATE_TI;
											$historydata=array(
																'title'=>$comment_title,
																'lead_id'=>$lead_id,
																'comment'=>addslashes($message),
																'attach_file'=>$attach_filename,
																'create_date'=>$date,
																'user_id'=>$update_by,
																'ip_address'=>$ip_addr
															);
											
											$this->Cronjobs_model->history_CreateHistory($historydata,$client_info);

											
											if($email_forwarding_setting['is_mail_send']=='Y')
											{
												// ============================
												// Mail Acknowledgement & 
												// account manager update
												// START

												// TO CLIENT				
												$e_data=array();
												
												
												$assigned_to_user_data=$this->Cronjobs_model->user_get_employee_details($assigned_user_id,$client_info);												
												$company=$this->Cronjobs_model->setting_GetCompanyData($client_info);												
												$lead_info=$this->Cronjobs_model->lead_GetLeadData($lead_id,$client_info);												
												$customer=$this->Cronjobs_model->cust_GetCustomerData($lead_info->customer_id,$client_info);
												$e_data['company']=$company;
												$e_data['assigned_to_user']=$assigned_to_user_data;
												$e_data['customer']=$customer;
												$e_data['lead_info']=$lead_info;
												$e_data['client_info']=$client_info;											
												
												$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_cronjobs_view', $e_data, true);
												
												// $m_email=get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);
												$m_email=$this->Cronjobs_model->get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);
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
													
													$post_data=array();
													$post_data=array(
															"mail_for"=>'TI',
															"from_mail"=>$self_cc_mail,
															"from_name"=>$update_by_name,
															"to_mail"=>$to_mail,
															"cc_mail"=>$cc_mail,
															"subject"=>$mail_subject,
															"message"=>$template_str,
															"attach"=>'',
															"created_at"=>date("Y-m-d H:i:s")
													);
													$this->Cronjobs_model->cronjobs_mail_fire_add($post_data);

													// $m_d = array();
													// $m_d['from_mail']     = $self_cc_mail;
													// $m_d['from_name']     = $update_by_name;
													// $m_d['to_mail']       = $to_mail;
													// $m_d['cc_mail']       = $cc_mail;
													// $m_d['subject']       = $mail_subject;
													// $m_d['message']       = $template_str;
													// $m_d['attach']        = array();
													// $mail_return = $this->mail_lib->send_mail($m_d,$client_info);


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
				// $post_data=array(
				// 		'client_id'=>$client_info->client_id,
				// 		'function_name'=>'get_lead_from_tradeindia',
				// 		'updated_db_name'=>$client_info->db_name,
				// 		'msg'=>$msg,
				// 		'created_at'=>date("Y-m-d H:i:s")
				// 		);
				// $this->Client_model->create_cronjobs_log($post_data);
			}		
		}	
	}

	function get_lead_from_aajjo_get($client_id='')
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
				$this->Cronjobs_model->initialise($client_info);					
				$aajjo_credentials=$this->Cronjobs_model->aajjo_setting_GetCredentials($client_info);				
				if(count($aajjo_credentials)>0)
				{					
					$this->Cronjobs_model->aajjo_truncate($client_info);
					foreach($aajjo_credentials AS $aj)
					{
						$aajjo_setting_id=$aj['id'];
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
										$this->Cronjobs_model->aajjo_insert($post,$client_info);
									}								
									$i++;				
								}
							}							
						}
					}
				}				
			}
			$this->get_lead_from_aajjo_set('');		
		}		
	}

	function get_lead_from_aajjo_set($client_id='')
	{
		
		$arg=array();
		$arg['cronjobs_action']='aajjo';
		$arg['client_id']=$client_id;
		$client_db_info_list=$this->Client_model->get_all($arg);
		
		if(count($client_db_info_list))
		{	
			foreach($client_db_info_list AS $client_info)
			{				
				$this->Cronjobs_model->initialise($client_info);					
				$aajjo_credentials=$this->Cronjobs_model->aajjo_setting_GetCredentials($client_info);
				// Lead Acknowledgement id 1				
				$email_forwarding_setting=$this->Cronjobs_model->email_forwarding_setting_GetDetails(1,$client_info);				
				if(count($aajjo_credentials)>0)
				{			
					foreach($aajjo_credentials AS $credentials)
					{
						$get_aj=$this->Cronjobs_model->aajjo_get_rows($credentials['id'],$client_info);	
						
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
								$aj_created_date=$aj['created_date'];							
								
								$cust_arr=array(
												'aj_created_date'=>$aj_created_date,
												'email'=>$cust_email,
												'mobile'=>$cust_mobile
												);								
								$get_customer_decision=$this->Cronjobs_model->cust_get_decision_for_aajjo($cust_arr,$client_info);

								// ------------------------
								// get message 
								$aj_history_data=array('msg'=>$get_customer_decision['msg']);								
								$this->Cronjobs_model->aajjo_update($aj_history_data,$aj['id'],$client_info);
								// ------------------------

								if($get_customer_decision['status']==TRUE)
								{								
									$com_country_code='';
									$com_country_id='';
									if($aj['country_name'])
									{
										$get_country=$this->Cronjobs_model->countries_get_country_by_name($aj['country_name'],$client_info);
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
										$get_city_id=$this->Cronjobs_model->cities_get_city_id_by_name($aj['city'],$client_info);
										if($get_city_id!=false)
										{
											$com_city_id=$get_city_id;
											// GET STATE											
											$city_info_tmp=$this->Cronjobs_model->cities_get_city_by_id($com_city_id,$client_info);
											if($city_info_tmp)
											{
												$com_state_id=$city_info_tmp->state_id;
											}										
										}
									}
										
									if($aj['state_name']!='' && $com_state_id=='')
									{										
										$get_state_id=$this->Cronjobs_model->states_get_state_id_by_name($aj['state_name'],$client_info);
										if($get_state_id!=false)
										{
											$com_state_id=$get_state_id;
										}
									}				
									$com_zip='';
									$com_short_description='';
									
									$com_source_text='Aajjo';
									$com_source_text_tmp = str_replace(' ', '', strtolower($com_source_text));
									
									$com_source_id=$this->Cronjobs_model->source_get_source_id_by_name($com_source_text_tmp,$client_info);
									if($com_source_id==0)
									{
										$post_source=array(
														'parent_id'=>0,
														'name'=>$com_source_text
														);										
										$com_source_id=$this->Cronjobs_model->source_add($post_source,$client_info);
									}
									
									
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
										$assigned_user_id=$this->Cronjobs_model->aajjo_setting_get_keyword_rule5_wise_assigned_user_id($arr,$client_info);
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
										$arr['aajjo_setting_id']=$aj['aajjo_setting_id'];
										$arr['search_keyword']=$search_keyword;	
										$assigned_user_id=$this->Cronjobs_model->aajjo_setting_get_rule_wise_assigned_user_id($arr,$client_info);
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
											$this->Cronjobs_model->aajjo_setting_EditCredentials($tmpdata,$tradeindia_setting_id,$client_info);
											// update to setting table
											// ----------------------------------
										}
										// RULE WISE USER
										// -------------------		
										$com_contact_person_tmp=$com_contact_person;
										
									}
									else if($get_customer_decision['msg']=='one_customer_exist')
									{
										$com_company_id=$get_customer_decision['customer_id'];										
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
														'aj_setting_id'=>$aajjo_setting_id,
														'aj_created_date'=>$aj_created_date
													);
										
										$lead_id=$this->Cronjobs_model->lead_CreateLead($lead_post_data,$client_info);
										if($lead_id)
										{
											
											
											// Insert Stage Log
											$stage_post_data=array(
													'lead_id'=>$lead_id,
													'stage_id'=>'1',
													'stage'=>'PENDING',
													'stage_wise_msg'=>'',
													'create_datetime'=>date("Y-m-d H:i:s")
												);											
											$this->Cronjobs_model->lead_CreateLeadStageLog($stage_post_data,$client_info);

											// Insert Status Log
											$status_post_data=array(
													'lead_id'=>$lead_id,
													'status_id'=>'2',
													'status'=>'WARM',
													'create_datetime'=>date("Y-m-d H:i:s")
												);											
											$this->Cronjobs_model->lead_CreateLeadStatusLog($status_post_data,$client_info);

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
											$this->Cronjobs_model->lead_create_lead_assigned_user_log($post_data,$client_info);
											
										
											$assigned_user_name='';
											if($assigned_user_id){
												$assigned_user_name=$this->Cronjobs_model->GetUserName($assigned_user_id);
											}
											$update_by=1;				
											$date=date("Y-m-d H:i:s");				
											$ip_addr=$_SERVER['REMOTE_ADDR'];				
											$message="A new lead has been created as &quot;".$lead_title."&quot;";
											if($assigned_user_name){
												$message .=" The lead assigned to ".$assigned_user_name."";
											}
											$comment_title=NEW_LEAD_CREATE_AJ;
											$historydata=array(
																'title'=>$comment_title,
																'lead_id'=>$lead_id,
																'comment'=>addslashes($message),
																'attach_file'=>$attach_filename,
																'create_date'=>$date,
																'user_id'=>$update_by,
																'ip_address'=>$ip_addr
															);											
											$this->Cronjobs_model->history_CreateHistory($historydata,$client_info);

											
											if($email_forwarding_setting['is_mail_send']=='Y')
											{
												// ============================
												// Mail Acknowledgement & 
												// account manager update
												// START

												// TO CLIENT				
												$e_data=array();
												$assigned_to_user_data=$this->Cronjobs_model->user_get_employee_details($assigned_user_id,$client_info);												
												$company=$this->Cronjobs_model->setting_GetCompanyData($client_info);												
												$lead_info=$this->Cronjobs_model->lead_GetLeadData($lead_id,$client_info);												
												$customer=$this->Cronjobs_model->cust_GetCustomerData($lead_info->customer_id,$client_info);
												$e_data['company']=$company;
												$e_data['assigned_to_user']=$assigned_to_user_data;
												$e_data['customer']=$customer;
												$e_data['lead_info']=$lead_info;
												$e_data['client_info']=$client_info;
												
												$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_cronjobs_view', $e_data, true);
												
												// $m_email=get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);
												$m_email=$this->Cronjobs_model->get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);
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
													$post_data=array();
													$post_data=array(
															"mail_for"=>'AJ',
															"from_mail"=>$self_cc_mail,
															"from_name"=>$update_by_name,
															"to_mail"=>$to_mail,
															"cc_mail"=>$cc_mail,
															"subject"=>$mail_subject,
															"message"=>$template_str,
															"attach"=>'',
															"created_at"=>date("Y-m-d H:i:s")
													);
													$this->Cronjobs_model->cronjobs_mail_fire_add($post_data);

													// $m_d = array();
													// $m_d['from_mail']     = $self_cc_mail;
													// $m_d['from_name']     = $update_by_name;
													// $m_d['to_mail']       = $to_mail;
													// $m_d['cc_mail']       = $cc_mail;
													// $m_d['subject']       = $mail_subject;
													// $m_d['message']       = $template_str;
													// $m_d['attach']        = array();
													// $mail_return = $this->mail_lib->send_mail($m_d,$client_info);
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

				// $post_data=array(
				// 		'client_id'=>$client_info->client_id,
				// 		'function_name'=>'get_lead_from_aajjo',
				// 		'updated_db_name'=>$client_info->db_name,
				// 		'created_at'=>date("Y-m-d H:i:s")
				// 		);
				// $this->Client_model->create_cronjobs_log($post_data);
			}		
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
				$this->Cronjobs_model->initialise($client_info);
				// $justdial_credentials=$this->Justdial_setting_model->GetCredentials($client_info);	
				$justdial_credentials=$this->Cronjobs_model->justdial_setting_GetCredentials($client_info);		
				// Lead Acknowledgement id 1
				// $email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(1,$client_info);
				$email_forwarding_setting=$this->Cronjobs_model->email_forwarding_setting_GetDetails(1,$client_info);			
				
				if(count($justdial_credentials)>0)
				{			
					if($justdial_credentials['assign_rule']=='1')
					{
						$justdial_assign_to=unserialize($justdial_credentials['assign_to']);
						$assign_start=isset($justdial_credentials['assign_start'])?$justdial_credentials['assign_start']:0;
						$assign_end=(count($justdial_assign_to)-1);
					}

					$justdial_setting_id=$justdial_credentials['id'];
					// $get_jd=$this->Justdial_model->get_rows($client_info);
					$get_jd=$this->Cronjobs_model->justdial_get_rows($client_info);
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
							// $get_customer_decision=$this->Customer_model->get_decision_jd($cust_arr,$client_info);
							$get_customer_decision=$this->Cronjobs_model->cust_get_decision_jd($cust_arr,$client_info);

							// ------------------------
							// get message 
							$jd_history_data=array('msg'=>$get_customer_decision['msg']);
							// $this->Justdial_model->update($jd_history_data,$jd['id'],$client_info);
							$this->Cronjobs_model->justdial_update($jd_history_data,$jd['id'],$client_info);
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
									// $get_city_id=$this->Cities_model->get_city_id_by_name($jd['city'],$client_info);
									$get_city_id=$this->Cronjobs_model->cities_get_city_id_by_name($jd['city'],$client_info);
									if($get_city_id!=false)
									{
										$com_city_id=$get_city_id;
										// $get_city_info=$this->Cities_model->get_city_by_id($com_city_id,$client_info);
										$get_city_info=$this->Cronjobs_model->cities_get_city_by_id($com_city_id,$client_info);
										$com_state_id=$get_city_info->state_id;
									}
								}
										
								$com_zip=$jd['pincode'];
								$com_short_description='';
								$com_source_text='JustDial';	
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
									// $assigned_user_id=$this->Justdial_setting_model->get_keyword_rule5_wise_assigned_user_id($arr,$client_info);
									$assigned_user_id=$this->Cronjobs_model->justdial_setting_get_keyword_rule5_wise_assigned_user_id($arr,$client_info);
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
									// $assigned_user_id=$this->Justdial_setting_model->get_rule_wise_assigned_user_id($arr,$client_info);
									$assigned_user_id=$this->Cronjobs_model->justdial_setting_get_rule_wise_assigned_user_id($arr,$client_info);
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
										// $this->Justdial_setting_model->EditJustdialCredentials($tmpdata,$justdial_setting_id,$client_info);
										$this->Cronjobs_model->justdial_setting_EditJustdialCredentials($tmpdata,$justdial_setting_id,$client_info);
										// update to setting table
										// ----------------------------------
									}
									// RULE WISE USER
									// -------------------

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
											
									// $lead_id=$this->Lead_model->CreateLead($lead_post_data,$client_info);
									$lead_id=$this->Cronjobs_model->lead_CreateLead($lead_post_data,$client_info);
										
									if($lead_id)
									{									
										

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
										// -----------------------------------
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
										$comment_title=NEW_LEAD_CREATE_JD;
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

											
										if($email_forwarding_setting['is_mail_send']=='Y')
										{
											// ============================
											// Mail Acknowledgement & 
											// account manager update
											// START

											// TO CLIENT				
											$e_data=array();									
											// $assigned_to_user_data=$this->User_model->get_employee_details($assigned_user_id,$client_info);
											$assigned_to_user_data=$this->Cronjobs_model->user_get_employee_details($assigned_user_id,$client_info);										
											// $company=$this->Setting_model->GetCompanyData($client_info);
											$company=$this->Cronjobs_model->setting_GetCompanyData($client_info);
											// $lead_info=$this->Lead_model->GetLeadData($lead_id,$client_info);
											$lead_info=$this->Cronjobs_model->lead_GetLeadData($lead_id,$client_info);
											// $customer=$this->Customer_model->GetCustomerData($lead_info->customer_id,$client_info);
											$customer=$this->Cronjobs_model->cust_GetCustomerData($lead_info->customer_id,$client_info);


											$e_data['company']=$company;
											$e_data['assigned_to_user']=$assigned_to_user_data;
											$e_data['customer']=$customer;
											$e_data['lead_info']=$lead_info;
											// $e_data['get_company_name_initials']=get_company_name_initials($client_info);
											// $e_data['rander_company_address']=rander_company_address_cronjobs('email_template',$client_info);
											$e_data['get_company_name_initials']=$this->Cronjobs_model->get_company_name_initials();
											$e_data['rander_company_address']=$this->Cronjobs_model->rander_company_address_cronjobs();
											$e_data['client_info']=$client_info;
											$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_cronjobs_view', $e_data, true);
											
											// $m_email=get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);
											$m_email=$this->Cronjobs_model->get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);

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
									
										// $this->Justdial_model->deleteTmp($jd['id'],$client_info);
										$this->Cronjobs_model->justdial_deleteTmp($jd['id'],$client_info);	
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

	function get_lead_from_indiamart_backlog($client_id='274')
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
				$this->Cronjobs_model->initialise($client_info);

				$current_datetime=$client_info->indiamart_backlog_initial_date;	
				$start_datetime_db_format = date("Y-m-d",strtotime("-1 days", strtotime($current_datetime)));
				$start_datetime = date("d-M-Y",strtotime("-1 days", strtotime($current_datetime)));
				$end_datetime=date("d-M-Y",strtotime($current_datetime));

				$indiamart_credentials=$this->Indiamart_setting_model->GetIndiamartCredentials($client_info);
				
				// Lead Acknowledgement id 1
				// $email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(1,$client_info);
				
				if(count($indiamart_credentials)>0)
				{				
					$update_post_data=array('indiamart_backlog_initial_date'=>$start_datetime_db_format);

					$this->Client_model->update($update_post_data,$client_info->id);
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
							// echo $url; die('old');
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
							// echo $url; die('new');
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
											'create_date'=>$lead_enq_date,
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
											$comment_title=NEW_LEAD_CREATE_IM;
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
											/*
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
												// $e_data['get_company_name_initials']=get_company_name_initials($client_info);
												// $e_data['rander_company_address']=rander_company_address_cronjobs('email_template',$client_info);
												$e_data['get_company_name_initials']=$this->Cronjobs_model->get_company_name_initials();
												$e_data['rander_company_address']=$this->Cronjobs_model->rander_company_address_cronjobs();
												$e_data['client_info']=$client_info;
												$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_cronjobs_view', $e_data, true);
												
												// $m_email=get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);
												$m_email=$this->Cronjobs_model->get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);

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
											*/											
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
							$comment_title=NEW_LEAD_CREATE_RENEWAL;
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
		$report_date=date('Y-m-d',strtotime("-1 days"));	
		$arg=array();
		$arg['client_id']=$client_id;
		$arg['cronjobs_action']='daily_report';	
		$client_db_info_list=$this->Client_model->get_all($arg);
		
		if(count($client_db_info_list))
		{	
			foreach($client_db_info_list AS $client_info)
			{			
				$this->Cronjobs_model->initialise($client_info);

				$company=$this->Cronjobs_model->setting_GetCompanyData($client_info);				
				$subject=$company['name'].' - LMS Day Report for '.date_db_format_to_display_format($report_date);

				$is_daily_report_send=$company['is_daily_report_send'];
				$daily_report_tomail=$company['daily_report_tomail'];
				$daily_report_mail_subject=$company['daily_report_mail_subject'];
				
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
									$attach='';
								}
								else
								{
									$attach=$file_path.$csvFileName;
								}						
								// csv
								// ----------------------								
								$mail_subject=$subject;	
								// $smtp_data=$this->Cronjobs_model->GetSmtpData();
								// if(count($smtp_data)>0){
								// 	$smtp_data_tmp=$smtp_data;
								// }	
								// else{
								// 	$smtp_data_tmp=$this->Cronjobs_model->GetDefaultSmtpData();
								// }								
								// $m_d = array();
								// $m_d['smtp_data']     = $smtp_data_tmp;
								// $m_d['from_mail']     = $company['email1'];
								// $m_d['from_name']     = $company['name'];								
								// // $m_d['to_mail']       = $to_mail;
								// $m_d['to_mail']       = 'saswatiporel123@gmail.com';
								// if($client_info->client_id=='230'){
								// 	// $m_d['bcc_mail']  = 'shashi.narain@gmail.com,arupporel123@gmail.com';
								// }							
								// $m_d['cc_mail']       = $cc_mail;
								// $m_d['subject']       = $mail_subject;
								// $m_d['message']       = $template_str;
								// $m_d['attach']        = $attach;
								// $this->mail_lib->send_mail_cronjobs($m_d);
								$post_data=array();
								$post_data=array(
										"mail_for"=>MF_DAILY_REPORT,
										"from_mail"=>$company['email1'],
										"from_name"=>$company['name'],
										"to_mail"=>$to_mail,
										"cc_mail"=>$cc_mail,
										"subject"=>$mail_subject,
										"message"=>$template_str,
										"attach"=>$attach,
										"created_at"=>date("Y-m-d H:i:s")
								);
								$this->Cronjobs_model->cronjobs_mail_fire_add($post_data);
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
				// $post_data=array(
				// 'client_id'=>$client_info->client_id,
				// 'function_name'=>'generate_daily_report',
				// 'updated_db_name'=>$client_info->db_name,
				// 'msg'=>'',
				// 'script_start'=>date("H:i:s"),		
				// 'script_end'=>date("H:i:s"),					
				// 'created_at'=>date("Y-m-d H:i:s")
				// );
				// $this->Client_model->create_cronjobs_log($post_data);
				// ===================================
			}
		}
	}

	function get_lead_from_facebook($client_id='',$user_id='')
	{	
		if($client_id=='' || $user_id=='')
		{
			echo 'fail#Parameter(Client/ User ID) is missing.'; die();
		}		
		
		date_default_timezone_set('Asia/Kolkata');
		$current_datetime=date("Y-m-d");		
		
		$start_datetime = date("d-M-Y",strtotime("-1 days", strtotime($current_datetime)));
		$end_datetime=date("d-M-Y",strtotime($current_datetime));	
		$arg=array();
		$arg['client_id']=$client_id;
		$arg['cronjobs_action']='';	
		$arg['get_limit_offset']='';		
		$client_db_info_list=$this->Client_model->get_all($arg);
		// $this->get_lead_from_facebook_set($client_id);die();
		if(count($client_db_info_list))
		{	
			foreach($client_db_info_list AS $client_info)
			{		
				$this->Cronjobs_model->initialise($client_info);
				$msg_log='';
				$flag=0;
				if($flag==0)
				{					
					$fb_credentials=$this->Cronjobs_model->get_facebook_credentials();
					if(count($fb_credentials)>0)
					{
						$fb_page_id=$fb_credentials['fb_page_id'];
						$fb_form_id=$fb_credentials['fb_form_id'];
						$fb_page_access_token=$fb_credentials['fb_page_access_token'];
						// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v16.0/'.$fb_page_id.'/leadgen_forms?fields=leads%7Bad_id%2Cad_name%2Cadset_id%2Cadset_name%2Ccampaign_id%2Ccampaign_name%2Ccreated_time%2Cfield_data%2Cform_id%2Cid%2Cis_organic%2Cplatform%7D&access_token='.$fb_page_access_token);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$result = curl_exec($ch);
						if (curl_errno($ch)) {
							echo 'Error:' . curl_error($ch);
						}
						curl_close($ch);						
						$return_arr=json_decode($result);						
						if(!isset($return_arr->error->code)){
							if(count($return_arr->data)){
								// $this->Cronjobs_model->fb_truncate();
								$this->Cronjobs_model->delete_lead_tmp_by_user($user_id);
								foreach($return_arr->data AS $data){
									if(count($data->leads->data)){
										foreach($data->leads->data AS $form_data){
											if($form_data->form_id==$fb_form_id){												
												$post_data=array(
													'fb_id'=>$form_data->id,
													'ad_id'=>$form_data->ad_id,
													'ad_name'=>$form_data->ad_name,
													'adset_id'=>$form_data->adset_id,
													'adset_name'=>$form_data->adset_name,
													'campaign_id'=>$form_data->campaign_id,
													'campaign_name'=>$form_data->campaign_name,
													'created_time'=>$form_data->created_time,
													'fb_form_id'=>$form_data->form_id,
													'fb_is_organic'=>$form_data->is_organic,
													'fb_platform'=>$form_data->platform,
													'user_id'=>$user_id
												);
												$tmp_id=$this->Cronjobs_model->addFacebookTmp($post_data);	
												if($tmp_id){
													if(count($form_data->field_data)){
														foreach($form_data->field_data AS $field){
															if($field->name!='' && $field->values[0]!=''){
																$post_field=array(
																	'fb_lead_tmp_id'=>$tmp_id,
																	'fb_field_name'=>$field->name,
																	'fb_field_value'=>$field->values[0],
																	'user_id'=>$user_id
																);																
																$this->Cronjobs_model->addFacebookFieldTmp($post_field);
															}															
														}
													}
												}
											}
										}
									}
								}
								$this->get_lead_from_facebook_set($client_id);
								echo 'success#Facebook form leads are captured.'; die();
							}
						}
						else{
							// $msg_log .='Error Message:-'.$return_arr->error->message.'|Error code:-'.$return_arr->error->code;
							echo 'fail#'.$return_arr->error->message; die();
						}			
					}							
				}									
			}		
		}
	}
	function get_lead_from_facebook_set($client_id='')
	{	
		if($client_id=='')
		{
			echo 'fail#Parameter is missing.';die();
		}
			
		$arg=array();
		$arg['client_id']=$client_id;
		$arg['cronjobs_action']='';	
		$arg['get_limit_offset']='';		
		$client_db_info_list=$this->Client_model->get_all($arg);		
		if(count($client_db_info_list))
		{	
			foreach($client_db_info_list AS $client_info)
			{	
				$this->Cronjobs_model->initialise($client_info);
				$msg_log='';
				$flag=0;
				if($flag==0)
				{				
					$fb_field_and_keyword_k_v=array();
					$fb_credentials=$this->Cronjobs_model->get_facebook_credentials();
					if(count($fb_credentials)){
						$fb_field_name_arr=explode(",",$fb_credentials['fb_field_name_str']);
						$system_field_name_keyword_arr=explode(",",$fb_credentials['system_field_name_keyword_str']);
						$i=0;
						foreach($fb_field_name_arr AS $fb_field_name){
							$fb_field_and_keyword_k_v[$fb_field_name]=$system_field_name_keyword_arr[$i];
							$i++;
						}
					}
					
					$fb_leads=$this->Cronjobs_model->fb_leads_tmp();										
					if(count($fb_leads)>0)
					{			
						foreach($fb_leads AS $fb)
						{
							$cust_name='';
							$cust_email='';
							$cust_mobile='';							
							$cust_company_name='';
							$cust_description='';
							$cust_city='';
							$cust_state='';
							$cust_country='';
							$cust_pin='';

							$fb_lead_field_and_value_k_v=array();
							$fb_lead_field_name_arr=explode(",",$fb['fb_field_name_str']);
							$fb_lead_field_value_arr=explode("~~~",$fb['fb_field_value_str']);
							if(count($fb_lead_field_name_arr)){								
								$j=0;
								foreach($fb_lead_field_name_arr AS $fb_lead_field_name){
									$fb_lead_field_and_value_k_v[$fb_lead_field_name]=$fb_lead_field_value_arr[$j];
									$j++;
								}
							}

							if(count($fb_field_and_keyword_k_v)){
								foreach($fb_field_and_keyword_k_v AS $tmp_k=>$tmp_val){
									if($tmp_val=='name'){
										if(isset($fb_lead_field_and_value_k_v[$tmp_k])){
											$cust_name=$cust_email=$fb_lead_field_and_value_k_v[$tmp_k];;
										}										
										
									}

									if($tmp_val=='email'){
										if(isset($fb_lead_field_and_value_k_v[$tmp_k])){
											$cust_email=$cust_email=$fb_lead_field_and_value_k_v[$tmp_k];;
										}										
										
									}

									if($tmp_val=='phone_number'){
										if(isset($fb_lead_field_and_value_k_v[$tmp_k])){
											$cust_mobile=substr($fb_lead_field_and_value_k_v[$tmp_k], -10);
										}										
									}

									if($tmp_val=='company_name'){
										if(isset($fb_lead_field_and_value_k_v[$tmp_k])){
											$cust_company_name=$cust_email=$fb_lead_field_and_value_k_v[$tmp_k];;
										}										
										
									}

									if($tmp_val=='description'){
										if(isset($fb_lead_field_and_value_k_v[$tmp_k])){
											$cust_description=$cust_email=$fb_lead_field_and_value_k_v[$tmp_k];;
										}										
										
									}

									if($tmp_val=='city'){
										if(isset($fb_lead_field_and_value_k_v[$tmp_k])){
											$cust_city=$cust_email=$fb_lead_field_and_value_k_v[$tmp_k];;
										}										
										
									}

									if($tmp_val=='state'){
										if(isset($fb_lead_field_and_value_k_v[$tmp_k])){
											$cust_state=$cust_email=$fb_lead_field_and_value_k_v[$tmp_k];;
										}										
										
									}

									if($tmp_val=='country'){
										if(isset($fb_lead_field_and_value_k_v[$tmp_k])){
											$cust_country=$cust_email=$fb_lead_field_and_value_k_v[$tmp_k];;
										}										
										
									}

									if($tmp_val=='pin'){
										if(isset($fb_lead_field_and_value_k_v[$tmp_k])){
											$cust_pin=$cust_email=$fb_lead_field_and_value_k_v[$tmp_k];;
										}										
										
									}
								}
							}
							// print_r($cust_email.'/'.$cust_mobile); 

							
							$fb_id=$fb['fb_id'];									
							$cust_arr=array(
								'email'=>$cust_email,
								'mobile'=>$cust_mobile,
								'fb_lead_id'=>$fb_id
								);									
							$get_customer_decision=$this->Cronjobs_model->cust_get_decision_fb($cust_arr);

							// ------------------------
							// get message 
							$fb_history_data=array('msg'=>$get_customer_decision['msg']);									
							$this->Cronjobs_model->fb_lead_tmp_update($fb_history_data,$fb['id']);
							// ------------------------
							
							if($get_customer_decision['status']==TRUE)
							{
								
								$com_country_code='';
								$com_country_id='';
								if($cust_country)
								{											
									$get_country=$this->Cronjobs_model->countries_get_country_by_name($cust_country);
									if($get_country!=false)
									{
										$com_country_id=$get_country->id;
										$com_country_code=$get_country->phonecode;

									}
								}
								
								$facebook_lead_id=$fb['fb_id'];
								$com_contact_person=($cust_name)?$cust_name:'Purchase Manager';
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
								$com_company_name=$cust_company_name;
								$com_address='';
								$com_city_id='';
								if($cust_city)
								{											
									$get_city_id=$this->Cronjobs_model->cities_get_city_id_by_name($cust_city);
									if($get_city_id!=false)
									{
										$com_city_id=$get_city_id;
									}
								}
								$com_state_id='';	
								if($cust_state)
								{											
									$get_state_id=$this->Cronjobs_model->states_get_state_id_by_name($cust_state,$client_info);
									if($get_state_id!=false)
									{
										$com_state_id=$get_state_id;
									}
								}				
								$com_zip='';
								$com_short_description='';
								if($fb['fb_platform'])
								{
									if(strtoupper($fb['fb_platform'])=='fb'){
										$com_source_text='Facebook';
									}
									else if(strtoupper($fb['fb_platform'])=='ig'){
										$com_source_text='Instagram';
									}	
									else{
										$com_source_text='Facebook';
									}

									$com_source_text_tmp = str_replace(' ', '', strtolower($com_source_text));											
									$com_source_id=$this->Cronjobs_model->source_get_source_id_by_name($com_source_text_tmp,$client_info);
									if($com_source_id==0)
									{
										$post_source=array(
											'parent_id'=>0,
											'name'=>$com_source_text
											);												
										$com_source_id=$this->Cronjobs_model->source_add($post_source,$client_info);
									}
								}

								// -------------------
								// LEAD INFO
								$tmp_uname=($cust_name)?' from '.$cust_name:'';
								$lead_title='Facebook / Insta Lead'.$tmp_uname;

								$lead_requirement='';
								if($lead_title){
									$lead_requirement .=$lead_title.'<BR><BR>';
								}
								if($cust_mobile){
									$lead_requirement .='<B>Mobile:</B>'.$cust_mobile.'<BR><BR>';
								}
								if($cust_email){
									$lead_requirement .='<B>Email:</B> '.$cust_email.'<BR><BR>';
								}
								if($facebook_lead_id){
									$lead_requirement .='<B>FB Lead ID:</B> '.$facebook_lead_id.'<BR><BR>';
								}
								if($fb['campaign_name']){
									$lead_requirement .='<B>Campaign name:</B> '.$fb['campaign_name'].'<BR><BR>';
								}
								if($fb['fb_platform']){
									$lead_requirement .='<B>Platform:</B> '.$fb['fb_platform'].'<BR><BR>';
								}
								// LEAD INFO
								// -------------------

								// -------------------
								// RULE WISE USER
								$assigned_user_id=1;
								/*
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
									$assigned_user_id=$this->Cronjobs_model->indiamart_setting_get_rule_wise_assigned_user_id($arr,$client_info);
								}	
								*/			
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
									/*
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
										$this->Cronjobs_model->indiamart_setting_EditIndiamartCredentials($tmpdata,$indiamart_setting_id,$client_info);
										// update to setting table
										// ----------------------------------
									}
									*/
									$com_contact_person_tmp=$com_contact_person;
									
								}
								else if($get_customer_decision['msg']=='one_customer_exist')
								{
									$com_company_id=$get_customer_decision['customer_id'];											
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

									$lead_enq_date=date_display_format_to_db_format($fb['created_time']);											
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
										'fb_lead_id'=>$facebook_lead_id
									);											
									
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
										$this->Cronjobs_model->lead_CreateLeadStageLog($stage_post_data,$client_info);
										
										// Insert Status Log
										$status_post_data=array(
												'lead_id'=>$lead_id,
												'status_id'=>'2',
												'status'=>'WARM',
												'create_datetime'=>date("Y-m-d H:i:s")
											);												
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
										
										$this->Cronjobs_model->lead_create_lead_assigned_user_log($post_data,$client_info);

										$assigned_user_name='';
										if($assigned_user_id){
											$assigned_user_name=$this->Cronjobs_model->GetUserName($assigned_user_id);
										}

										$update_by=1;				
										$date=date("Y-m-d H:i:s");				
										$ip_addr=$_SERVER['REMOTE_ADDR'];				
										$message="A new lead has been created as &quot;".$lead_title."&quot;";
										if($assigned_user_name){
											$message .=" The lead assigned to ".$assigned_user_name."";
										}
										$comment_title=NEW_LEAD_CREATE_FB;
										$historydata=array(
												'title'=>$comment_title,
												'lead_id'=>$lead_id,
												'comment'=>addslashes($message),
												'attach_file'=>$attach_filename,
												'create_date'=>$date,
												'user_id'=>$update_by,
												'ip_address'=>$ip_addr
												);												
										$this->Cronjobs_model->history_CreateHistory($historydata,$client_info);

										
										// if($email_forwarding_setting['is_mail_send']=='Y' || count($sms_forwarding_setting)>0)
										// {
										// 	$assigned_to_user_data=$this->Cronjobs_model->user_get_employee_details($assigned_user_id,$client_info);													
										// 	$company=$this->Cronjobs_model->setting_GetCompanyData($client_info);													
										// 	$lead_info=$this->Cronjobs_model->lead_GetLeadData($lead_id,$client_info);													
										// 	$customer=$this->Cronjobs_model->cust_GetCustomerData($lead_info->customer_id,$client_info);
										// }
										
										// MAIL ALERT												
										// if($email_forwarding_setting['is_mail_send']=='Y')
										// {
										// 	// ============================
										// 	// Mail Acknowledgement & 
										// 	// account manager update
										// 	// START

										// 	// TO CLIENT				
										// 	$e_data=array();
										// 	$e_data['company']=$company;
										// 	$e_data['assigned_to_user']=$assigned_to_user_data;
										// 	$e_data['customer']=$customer;
										// 	$e_data['lead_info']=$lead_info;
										// 	$e_data['get_company_name_initials']=$this->Cronjobs_model->get_company_name_initials($client_info);
										// 	$e_data['rander_company_address']=$this->Cronjobs_model->rander_company_address_cronjobs();
										// 	$e_data['client_info']=$client_info;
										// 	$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_cronjobs_view', $e_data, true);
											
										// 	// $m_email=get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);
										// 	$m_email=$this->Cronjobs_model->get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);

										// 	// --------------------
										// 	// to mail assign logic
										// 	$to_mail_assign='';
										// 	$to_mail='';
										// 	if($email_forwarding_setting['is_send_mail_to_client']=='Y')
										// 	{
										// 		$to_mail=$customer->email;
										// 		$to_mail_assign='client';
										// 	}
										// 	else
										// 	{
										// 		if($m_email['manager_email']!='' && $email_forwarding_setting['is_send_manager']=='Y')
										// 		{
										// 			$to_mail=$m_email['manager_email'];
										// 			$to_mail_assign='manager';
										// 		}
										// 		else
										// 		{
										// 			if($m_email['skip_manager_email']!='' && $email_forwarding_setting['is_send_skip_manager']=='Y')
										// 			{
										// 				$to_mail=$m_email['skip_manager_email'];
										// 				$to_mail_assign='skip_manager';
										// 			}
										// 		}
										// 	}
										// 	// to mail assign logic
										// 	// --------------------

										// 	$cc_mail_arr=array();
										// 	$self_cc_mail=get_value("email","user","id=".$assigned_user_id,$client_info);
										// 	$update_by_name=get_value("name","user","id=".$assigned_user_id,$client_info);
										// 	// --------------------
										// 	// cc mail assign logic
										// 	if($email_forwarding_setting['is_send_relationship_manager']=='Y')
										// 	{
										// 		if($to_mail=='')
										// 		{
										// 			$to_mail=$self_cc_mail;
										// 		}
										// 		else
										// 		{
										// 			array_push($cc_mail_arr, $self_cc_mail);
										// 		}		        	
										// 	}

										// 	if($email_forwarding_setting['is_send_manager']=='Y')
										// 	{
										// 		if($m_email['manager_email']!='' && $to_mail_assign!='manager')
										// 		{		        		
										// 			array_push($cc_mail_arr, $m_email['manager_email']);
										// 		}		        	
										// 	}

										// 	if($email_forwarding_setting['is_send_skip_manager']=='Y')
										// 	{
										// 		if($m_email['skip_manager_email']!='' && $to_mail_assign!='skip_manager')
										// 		{		        		
										// 			array_push($cc_mail_arr, $m_email['skip_manager_email']);
										// 		}		        	
										// 	}
										// 	$cc_mail='';
										// 	$cc_mail=implode(",", $cc_mail_arr);
										// 	// cc mail assign logic
										// 	// --------------------
													
										// 	if($to_mail)
										// 	{
										// 		$com_company_name_tmp=$company['name'];
										// 		if($im['PRODUCT_NAME']!=NULL && $com_contact_person_tmp!='')
										// 		{
										// 			$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' for '.$im['PRODUCT_NAME'].' has been received - '.$com_company_name_tmp; 
										// 		}
										// 		else if($im['PRODUCT_NAME']!=NULL && $com_contact_person_tmp=='')
										// 		{
										// 			$mail_subject='Enquiry # '.$lead_id.' for '.$im['PRODUCT_NAME'].' has been received - '.$com_company_name_tmp; 
										// 		}
										// 		else if($im['PRODUCT_NAME']==NULL && $com_contact_person_tmp!='')
										// 		{
										// 			$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
										// 		}
										// 		else if($im['PRODUCT_NAME']==NULL && $com_contact_person_tmp=='')
										// 		{
										// 			$mail_subject='Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
										// 		}
										// 		else
										// 		{
										// 			$mail_subject='Your Enquiry # '.$lead_id.' has been acknowledged';
										// 		}

										// 		$post_data=array();
										// 		$post_data=array(
										// 				"mail_for"=>'IM',
										// 				"from_mail"=>$self_cc_mail,
										// 				"from_name"=>$update_by_name,
										// 				"to_mail"=>$to_mail,
										// 				"cc_mail"=>$cc_mail,
										// 				"subject"=>$mail_subject,
										// 				"message"=>$template_str,
										// 				"attach"=>'',
										// 				"created_at"=>date("Y-m-d H:i:s")
										// 		);
										// 		$this->Cronjobs_model->cronjobs_mail_fire_add($post_data);
										// 	}
											
										// 	// END
										// 	// =============================
										// }
										
										// SMS ALERT
										// if($sms_forwarding_setting)
										// {			
										// 	if($sms_forwarding_setting['is_sms_send']=='Y')
										// 	{
												
										// 		$m_mobile=$this->Cronjobs_model->get_manager_and_skip_manager_mobile_arr($assigned_user_id,$client_info);
										// 		$default_template_auto_id=$sms_forwarding_setting['default_template_id'];
										// 		$user_id=$this->session->userdata['admin_session_data']['user_id'];
										// 		$sms_variable_info=array("customer_id"=>$lead_info->customer_id,'company_id'=>1,'lead_id'=>$lead_id,'user_id'=>$assigned_user_id);
										// 		// --------------------
										// 		// to sms send logic
										// 		$sms_send_data=array();			
										// 		$client_mobile='';
										// 		if($sms_forwarding_setting['is_send_sms_to_client']=='Y')
										// 		{
										// 			$client_mobile=$customer->mobile;					
										// 			$client_template_auto_id=($sms_forwarding_setting['send_sms_to_client_template_id'])?$sms_forwarding_setting['send_sms_to_client_template_id']:$default_template_auto_id;				
										// 			$sms_send_data[]=array('mobile'=>$client_mobile,'template_auto_id'=>$client_template_auto_id);
										// 		}	

										// 		$relationship_manager_mobile='';
										// 		if($sms_forwarding_setting['is_send_relationship_manager']=='Y')
										// 		{
										// 			$relationship_manager_mobile=get_value("mobile","user","id=".$assigned_user_id,$client_info);					
										// 			$relationship_manager_template_auto_id=($sms_forwarding_setting['send_relationship_manager_template_id'])?$sms_forwarding_setting['send_relationship_manager_template_id']:$default_template_auto_id;						        	
										// 			$sms_send_data[]=array('mobile'=>$relationship_manager_mobile,'template_auto_id'=>$relationship_manager_template_auto_id);
										// 		}	
												
										// 		$manager_mobile='';
										// 		if($sms_forwarding_setting['is_send_manager']=='Y')
										// 		{
										// 			if($m_mobile['manager_mobile']!='')
										// 			{		      
										// 				$manager_mobile=$m_mobile['manager_mobile'];
										// 				$manager_template_auto_id=($sms_forwarding_setting['send_manager_template_id'])?$sms_forwarding_setting['send_manager_template_id']:$default_template_auto_id;
										// 				$sms_send_data[]=array('mobile'=>$manager_mobile,'template_auto_id'=>$manager_template_auto_id);
										// 			}		        	
										// 		}

										// 		$skip_manager_mobile='';
										// 		if($sms_forwarding_setting['is_send_skip_manager']=='Y')
										// 		{
										// 			if($m_mobile['skip_manager_mobile']!='')
										// 			{		        		
										// 				$skip_manager_mobile=$m_mobile['skip_manager_mobile'];
										// 				$skip_manager_template_auto_id=($sms_forwarding_setting['send_skip_manager_template_id'])?$sms_forwarding_setting['send_skip_manager_template_id']:$default_template_auto_id;
										// 				$sms_send_data[]=array('mobile'=>$skip_manager_mobile,'template_auto_id'=>$skip_manager_template_auto_id);
										// 			}		        	
										// 		}
										// 		// to sms send logic	
										// 		// --------------------
										// 		$return=sms_send($sms_send_data,$sms_variable_info,$client_info);
										// 	}
										// }							
									}
								}
							}
							else
							{

							}	
								
							
						}						
						$msg="Process completed to fetch leads from facebook.";
						$status='success';
					}
					else
					{
						$msg="Facebook leads not available";
						$status='fail';
					}
					echo $status.'#'.$msg;			
					// ==========================================									
				}
				// ===============================================================
				// $post_data=array(
				// 	'client_id'=>$client_info->client_id,
				// 	'function_name'=>'get_lead_from_indiamart',
				// 	'updated_db_name'=>$client_info->db_name,
				// 	'msg'=>$msg_log,
				// 	'created_at'=>date("Y-m-d H:i:s")
				// 	);	
				// $this->Client_model->create_cronjobs_log($post_data);				
				// ===============================================================					
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
	$current_datetime='2022-09-30';
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

function force_download_from_fb_ajax()
{ 	
	$session_data=$this->session->userdata('admin_session_data');
	$client_id=$session_data['client_id'];
	$user_id=$session_data['user_id'];
    $return=$this->get_lead_from_facebook($client_id,$user_id);	
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
