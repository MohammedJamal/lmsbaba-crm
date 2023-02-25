<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Capture extends CI_Controller {
	private $api_access_token = '';	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model(array('Client_model','Countries_model','Customer_model','Cronjobs_model','Lead_model','History_model','User_model','Setting_model','Email_forwarding_setting_model','Source_model','Justdial_model','C2c_model','setting_model','Website_setting_model','Cities_model'));
		$this->load->library('mail_lib');
	}

	public function website($token='')
	{
		
		//$data = json_decode(file_get_contents('php://input'), true);
		$data = $_REQUEST;
		//$token=trim($data['token']);
		$source=trim($data['source']);
		$product_tag=trim($data['product_service']);
		$lead_requirement=trim($data['describe_requirement']);

		$com_email=trim($data['email']);
		$com_mobile=substr(trim($data['mobile']), -10);
		$com_contact_person=(trim($data['contact_person']))?trim($data['contact_person']):'Manager';
		$com_company_name=trim($data['company_name']);
		$com_country=(trim($data['country']))?trim($data['country']):'india';	
		$com_designation='';
		$com_alternate_email='';
		$com_alternate_mobile='';	
		$com_landline_std_code='';
		$landline_number='';
		$com_website='';
		$com_address='';
		$com_city=(trim($data['city']))?trim($data['city']):'';
		// $com_state_id='';
		$com_zip='';
		$com_short_description='';
		$lead_title='';
		$lead_enq_date=date("Y-m-d");

		// ---------------------------------------
		// CHECKING
		$error_count=0;
		$msg_error='';
		if($token=='' || $token==NULL)
		{
			$error_count++;
			$msg_error .='Token is missing...';
		}

		if(($com_email=='' || $com_email==NULL) && ($com_mobile=='' || $com_mobile==NULL))
		{
			$error_count++;
			$msg_error .='Email and Mobile both are missing...';
		}
		else
		{
			if($com_email)
			{
				if(validate_email($com_email)==false)
				{
					$error_count++;
					$msg_error .='Email('.$com_email.') is invalid...';
				}
			}

			if($com_mobile)
			{
				if(validate_mobile($com_mobile)==false)
				{
					$error_count++;
					$msg_error .='Mobile('.$com_mobile.') is invalid...';
				}
			}
		}

		if($com_country=='' || $com_country==NULL)
		{
			$error_count++;
			$msg_error .='Country is missing...';
		}

		if($lead_requirement=='' || $lead_requirement==NULL)
		{
			$error_count++;
			$msg_error .='Lead Requirement is missing...';
		}

		if($source=='' || $source==NULL)
		{
			$error_count++;
			$msg_error .='Source is missing...';
		}		
		
		// CHECKING
		// ---------------------------------------
		
		$client_info=$this->Client_model->get_details_by_token($token);
		if($client_info->is_website_capture_available=='N'){
			$error_count++;
			$msg_error .='Website capture facilities not available. Please contact to lmsbaba admin team...';
		}

		// ===============================================================
		// Cron log add			
		$post_data_log=array(
			'client_id'=>$client_info->client_id,
			'function_name'=>'website',
			'updated_db_name'=>$client_info->db_name,
			'script_start'=>date("H:i:s"),					
			'created_at'=>date("Y-m-d H:i:s")
			);		
		$update_capture_log_id=$this->Client_model->create_capture_log($post_data_log);	
		
		// Cron log add			
		// ===============================================================
		if($error_count==0)
		{			
			if(count($client_info))
			{	
				
				$this->Cronjobs_model->initialise($client_info);
				$cust_arr=array(
								'email'=>$com_email,
								'mobile'=>$com_mobile
								);
				$get_customer_decision=$this->Customer_model->get_decision_website($cust_arr,$client_info);
				
				
				if($get_customer_decision['status']==TRUE)
				{	
					if($com_city!='')
					{
						$com_city_tmp=str_replace(' ', '', strtolower($com_city));
						$city_state_arr=$this->Cities_model->get_city_id_and_state_id_by_name($com_city_tmp,$client_info);	
						$com_city_id=$city_state_arr['city_id'];
						$com_state_id=$city_state_arr['state_id'];		
					}
					else
					{
						$com_city_id='';
						$com_state_id='';	
					}

					// Lead Acknowledgement id 1
					// $email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(1,$client_info);		

					$com_country_info=$this->Countries_model->get_country_by_name($com_country,$client_info);
					
					if($com_country_info!=false)
					{
						$com_country_id=$com_country_info->id;
						$country_code=$com_country_info->phonecode;
						
						// -------------------
						// LEAD INFO
						if($product_tag)
						{
							// $lead_title='Requirement for '.$product_tag;
							$lead_title=''.$product_tag;
						}
						else
						{
							$lead_title='Requirement coming from '.$source;
						}
						// LEAD INFO
						// -------------------
						
						// -------------------
						// RULE WISE USER
						$get_assigned_info=$this->setting_model->get_website_api_assigned_info($client_info);
						
						if(count($get_assigned_info)>0){
							$website_api_setting_id=$get_assigned_info['id'];
							$api_assign_to=unserialize($get_assigned_info['assign_to']);
							$assign_start=isset($get_assigned_info['assign_start'])?$get_assigned_info['assign_start']:0;
							$assign_end=(count($api_assign_to)-1);
							if($get_assigned_info['assign_rule']=='1')
							{

								$assigned_user_id=isset($api_assign_to[$assign_start])?$api_assign_to[$assign_start]:$api_assign_to[0];							

								$assign_start++;
								if($assign_start>$assign_end)
								{
									$assign_start=0;
								}
								$tmpdata=array('assign_start'=>$assign_start);
								$this->setting_model->edit_website_api_assigned($tmpdata,$website_api_setting_id,$client_info);
							}
							else if($get_assigned_info['assign_rule']==5)// KEYWORD 
							{			
								$title_tmp=str_replace(' ', '', strtolower($lead_title));
								$lead_requirement_tmp=str_replace(' ', '', strtolower($lead_requirement));				
								$search_keyword=$title_tmp.''.$lead_requirement_tmp;								
								$arr=array();
								$arr['assign_rule']=$get_assigned_info['assign_rule'];
								$arr['web_setting_id']=$website_api_setting_id;
								$arr['search_keyword']=$search_keyword;	
								$assigned_user_id=$this->Website_setting_model->get_keyword_rule5_wise_assigned_user_id($arr,$client_info);
							}
							else
							{
								if($get_assigned_info['assign_rule']==2)
								{
									$search_keyword=$com_country_id;
								}
								else if($get_assigned_info['assign_rule']==3)
								{
									$search_keyword=$com_state_id;
								}
								else if($get_assigned_info['assign_rule']==4)
								{
									$search_keyword=$com_city_id;
								}
								if($search_keyword)
								{
									$arr=array();
									$arr['assign_rule']=$get_assigned_info['assign_rule'];
									$arr['web_setting_id']=$website_api_setting_id;
									$arr['search_keyword']=$search_keyword;	
									$assigned_user_id=$this->Website_setting_model->get_rule_wise_assigned_user_id($arr,$client_info);
								}
								else
								{
									$assigned_user_id=1; // common lead pool
								}
								
							}
						}
						else{
							$assigned_user_id='1';
						}
						// RULE WISE USER
						// -------------------
							
						
						
						$com_source_text_tmp = str_replace(' ', '', strtolower($source));
						$com_source_id=$this->Source_model->get_source_id_by_name($com_source_text_tmp,$client_info);
						if($com_source_id==0)
						{
							$post_source=array(
												'parent_id'=>0,
												'name'=>$com_source_text_tmp
												);
							$com_source_id=$this->Source_model->add($post_source,$client_info);
						}
						
						$is_blacklist='N';
						if($get_customer_decision['msg']=='no_customer_exist')
						{

							$company_post_data=array(
								'assigned_user_id'=>$assigned_user_id,
								'contact_person'=>$com_contact_person,
								'designation'=>$com_designation,
								'email'=>$com_email,
								'alt_email'=>$com_alternate_email,
								'mobile_country_code'=>$country_code,
								'mobile'=>$com_mobile,
								'alt_mobile_country_code'=>$country_code,
								'alt_mobile'=>$com_alternate_mobile,
								'landline_country_code'=>$country_code,
								'landline_std_code'=>$com_landline_std_code,
								'landline_number'=>$landline_number,
								'office_country_code'=>$country_code,
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
							
							// -------------------
							// RULE WISE USER
							if(count($get_assigned_info)>0)
							{
								if($get_assigned_info['assign_rule']=='1')
								{
									$assign_start++;
									if($assign_start>$assign_end)
									{
										$assign_start=0;
									}

									// ---------------------------------
									// update to setting table
									$tmpdata=array('assign_start'=>$assign_start);
									$this->Website_setting_model->EditWebsiteCredentials($tmpdata,$website_api_setting_id,$client_info);
									// update to setting table
									// ----------------------------------
								}
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
							// $this->Indiamart_setting_model->EditIndiamartCredentials($tmpdata,$indiamart_setting_id,$client_info);
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
								'email'=>($get_existing_com['email'])?$get_existing_com['email']:$com_email,
								'alt_email'=>($get_existing_com['alt_email'])?$get_existing_com['alt_email']:$com_alternate_email,
								'alt_mobile_country_code'=>($get_existing_com['alt_mobile_country_code'])?$get_existing_com['alt_mobile_country_code']:$com_alt_mobile_country_code,
								'mobile'=>($get_existing_com['mobile'])?$get_existing_com['mobile']:$com_mobile,
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
								// if($product_tag)
								// {
								// 	$lead_p_data=array(
								// 		'lead_id'=>$lead_id,
								// 		'name'=>$product_tag
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
								$date=date("Y-m-d h:i:s");				
								$ip_addr=$_SERVER['REMOTE_ADDR'];				
								$message="A new lead has been created as &quot;".$lead_title."&quot;";
								$comment_title=NEW_LEAD_CREATE_MANUAL;
								$historydata=array(
													'title'=>$comment_title,
													'lead_id'=>$lead_id,
													'comment'=>addslashes($message),
													'attach_file'=>'',
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
									$self_cc_mail=get_value("email","user","id='".$assigned_user_id."'",$client_info);
									$update_by_name=get_value("name","user","id='".$assigned_user_id."'",$client_info);
									
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
										if($product_tag!='' && $com_contact_person_tmp!='')
										{
											$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' for '.$im['PRODUCT_NAME'].' has been received - '.$com_company_name_tmp; 
										}
										else if($product_tag!='' && $com_contact_person_tmp=='')
										{
											$mail_subject='Enquiry # '.$lead_id.' for '.$im['PRODUCT_NAME'].' has been received - '.$com_company_name_tmp; 
										}
										else if($product_tag=='' && $com_contact_person_tmp!='')
										{
											$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
										}
										else if($product_tag=='' && $com_contact_person_tmp=='')
										{
											$mail_subject='Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
										}
										else
										{
											$mail_subject='Your Enquiry # '.$lead_id.' has been acknowledged';
										}
	
										
										// $this->load->library('mail_lib');
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
										//$mail_return = $this->mail_lib->send_mail($m_d);
										
									}
									
									// END
									// =============================
								}
								*/

								
								
							}
						}
					}
				}	
			}
			echo'success';
		}
		else
		{
			echo $msg_error;
		}

		// ===============================================================	
		if($update_capture_log_id>0){
			$post_data_log=array(
				'msg'=>$msg_error,
				'script_end'=>date("H:i:s"),
				);		
			$this->Client_model->update_capture_log($post_data_log,$update_capture_log_id);
		}			
		// ===============================================================

			
	}

	public function justdial($token2='')
	{		
		// https://lmsbaba.com/capture/justdial?token=77dc4d6dfa61d5acc1a7e192b457d45f&leadid=JD7ECF1AE36532&leadtype=company&prefix=&name=Mayank&mobile=9717274707&phone=&email=&date=2021-07-06&category=&area=Gurgaon&city=Delhi&brancharea=Okhla+Industrial+Area+Phase+1&dncmobile=1&dncphone=0&company=Tripti+Perfect+Airconditioning+Trading+Company&pincode=&time=14%3A14%3A34&branchpin=110020&parentid=PXX11.XX11.100928155641.K7Z8
		
		$received_data=$_GET;
		$token=($token2)?$token2:trim($received_data['token']);
		$com_email=trim($received_data['email']);
		$com_mobile=trim($received_data['mobile']);		
		// ---------------------------------------
		// CHECKING
		$error_count=0;
		$msg_error='';
		if($token=='' || $token==NULL)
		{
			$error_count++;
			$msg_error .='Token is missing...';
		}

		if(($com_email=='' || $com_email==NULL) && ($com_mobile=='' || $com_mobile==NULL))
		{
			$error_count++;
			$msg_error .='Email and Mobile both are missing...';
		}
		else
		{
			if($com_email)
			{
				if(validate_email($com_email)==false)
				{
					$error_count++;
					$msg_error .='Email('.$com_email.') is invalid...';
				}
			}

			if($com_mobile)
			{
				if(validate_mobile($com_mobile)==false)
				{
					$error_count++;
					$msg_error .='Mobile('.$com_mobile.') is invalid...';
				}
			}
		}		
		// CHECKING
		// ---------------------------------------

		$client_info=$this->Client_model->get_details_by_token($token);
		// ===============================================================
		// Cron log add			
		$post_data_log=array(
			'client_id'=>$client_info->client_id,
			'function_name'=>'justdial',
			'updated_db_name'=>$client_info->db_name,
			'script_start'=>date("H:i:s"),					
			'created_at'=>date("Y-m-d H:i:s")
			);		
		$update_capture_log_id=$this->Client_model->create_capture_log($post_data_log);	
		
		// Cron log add			
		// ===============================================================
		if($error_count==0)
		{
			
			
			if(count($client_info))
			{
				$postdata=array();
				$postdata=array(
								'leadid'=>$received_data['leadid'],
								'leadtype'=>$received_data['leadtype'],
								'prefix'=>$received_data['prefix'],
								'name'=>$received_data['name'],
								'mobile'=>$received_data['mobile'],
								'phone'=>$received_data['phone'],
								'email'=>$received_data['email'],
								'enq_date'=>$received_data['date'],
								'category'=>$received_data['category'],
								'area'=>$received_data['area'],
								'city'=>$received_data['city'],
								'brancharea'=>$received_data['brancharea'],
								'dncmobile'=>$received_data['dncmobile'],
								'dncphone'=>$received_data['dncphone'],
								'company'=>$received_data['company'],
								'pincode'=>$received_data['pincode'],
								'time'=>$received_data['time'],
								'branchpin'=>$received_data['branchpin'],
								'parentid'=>$received_data['parentid'],
								'created_at'=>date("Y-m-d H:i:s")
								);
				$this->Justdial_model->addTmp($postdata,$client_info);
			}
			
		}
		else
		{
			//echo $msg_error;
		}		
		echo 'RECEIVED';
		// ==============================================
		// ===============================================================	
		if($update_capture_log_id>0){
			$post_data_log=array(
				'msg'=>($msg_error)?$msg_error:'RECEIVED',
				'script_end'=>date("H:i:s"),
				);		
			$this->Client_model->update_capture_log($post_data_log,$update_capture_log_id);
		}			
		// ===============================================================

	}

	public function click2call()
	{	
		// https://app.lmsbaba.com/capture/click2call?uniquid=&call_date=&call_start=&call_end=&call_recording_url=&call_attempt_status=&call_type=&agent_mobile=&customer_mobile=&Client=&leadid=

		$received_data=$_GET;
		$data_str='';
		foreach($received_data AS $data_key=>$data_val)
		{
			$data_str .= $data_key.'=>'.$data_val.'<br>';
		}
		// ==========================================
		$mail_data = array();        
        $mail_data['from_mail'] = 'info@lmsbaba.com';
        $mail_data['from_name'] = 'lmsbaba';
        $mail_data['to_mail']   = 'arupporel123@gmail.com';
        $mail_data['subject']   = 'click2call-response';
        $mail_data['message']   = $data_str;
        $mail_data['attach']    = array();
        $r=$this->mail_lib->send_mail_default($mail_data);
        if($r==true){
        	
         }
        else{
        	
        }
          
		// ================================================
       	
				
		// ---------------------------------------
		// PARAMETER RECEIVED
			$leadid=trim($received_data['leadid']);
			$uniquid=trim($received_data['uniquid']);
	        $call_date=trim($received_data['call_date']);
	        $call_start=trim($received_data['call_start']);
	        $call_end=trim($received_data['call_end']);
	        $call_recording_url=trim($received_data['call_recording_url']);
	        $call_attempt_status=trim($received_data['call_attempt_status']);
	        $call_type=trim($received_data['call_type']);
	        // $agent_mobile=trim($received_data['agent_mobile']);
	        $agent_mobile = substr(trim($received_data['agent_mobile']), -10);

	        // $customer_mobile=trim($received_data['customer_mobile']);
	        $customer_mobile = substr(trim($received_data['customer_mobile']), -10);
	        $client=strtolower(trim($received_data['Client']));
	        // $escalation_number=trim($received_data['escalation_number']);
		// PARAMETER RECEIVED
		// ---------------------------------------
	    $client_info=$this->Client_model->get_details_by_domain_name($client);
	    
		if($leadid)
		{
			if(count($client_info))
			{
				$call_status='';
				if(strtoupper($call_attempt_status)=='ANSWER')
				{
					$call_status='Y';
				}
				else
				{
					$call_status='P';
				}
				$postdata=array();
				$postdata=array(
						'call_status'=>$call_status,
						'call_status_txt'=>$call_attempt_status,
						'call_type'=>$call_type,
						'call_datetime'=>$call_date,
						'exact_call_start'=>$call_start,
						'exact_call_end'=>$call_end,
						'call_recording_url'=>$call_recording_url,
						'uniquid'=>$uniquid,
						'updated_at'=>date("Y-m-d H:i:s")
					);				
				$this->C2c_model->update_lead_wise_c2c_log($postdata,$leadid,$client_info);
			}			
		}
		else
		{
			if(count($client_info))
			{
				$get_customer_info=$this->Customer_model->get_customer_detail_by_mobile($customer_mobile,$client_info);
					
				if(count($get_customer_info))
				{
					$customer_id=$get_customer_info['id'];
					$customer_contact_person=$get_customer_info['contact_person'];
					$assigned_user_id=$get_customer_info['assigned_user_id'];
					//$assigned_to_user_data=$this->User_model->get_employee_details($assigned_user_id,$client_info);
				}
				else
				{
					$customer_id='';
					$customer_contact_person='';
					$assigned_user_id='';
				}


				$call_status='';
				if(strtoupper($call_attempt_status)=='COMPLETECALLER' || strtoupper($call_attempt_status)=='COMPLETEAGENT' || strtoupper($call_attempt_status)=='ANSWER')
				{
					$call_status='Y';
				}
				else
				{
					$call_status='P';
				}
				$postdata=array();
				$postdata=array(
						'lead_id'=>'',
						'user_id'=>$assigned_user_id,
						'executive_mobile_number'=>$agent_mobile,
						'customer_id'=>$customer_id,
						'customer_contact_person'=>$customer_contact_person,
						'client_mobile_number'=>$customer_mobile,
						'call_status'=>$call_status,
						'call_status_txt'=>$call_attempt_status,
						'call_type'=>$call_type,
						'call_datetime'=>$call_date,
						'exact_call_start'=>$call_start,
						'exact_call_end'=>$call_end,
						'call_recording_url'=>$call_recording_url,
						'c2c_url'=>'',
						'uniquid'=>$uniquid,
						'created_at'=>date("Y-m-d H:i:s"),
						'updated_at'=>date("Y-m-d H:i:s")
					);				
				$this->C2c_model->add_lead_wise_c2c_log($postdata,$client_info);
			}	
		}		
		echo 'SUCCESS';
		// ==============================================
	}
}