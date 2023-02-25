<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Work_order extends CI_Controller {
	function __construct()
	{
		parent :: __construct();
		is_adminportal_logged_in();
		chk_access_menu_permission(10);
		init_adminportal_element();   
		$this->load->model(array("Adminportal_model","user_model","countries_model","states_model","cities_model","Client_model","Setting_model"));
	}

	public function index()
	{	
		$data = array(); 
		$data['topmenu_list']=get_permission_wise_menu_list();
		$this->load->view('adminmaster/work_order_view',$data);
	}

	public function get_users_list_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$argument = array();

			$account_type_id=$this->input->post('account_type');
			$is_active=$this->input->post('is_active');
			$argument['account_type_id']=$account_type_id;
			$argument['is_active']=$is_active;		
			
			$list['rows']=$this->Adminportal_model->get_work_order_list($argument);

			$html = $this->load->view('adminmaster/work_order_list_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;
	        echo json_encode($result);
	        exit(0);
		}
	}

	public function update_user_permission()
	{
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$this->Adminportal_model->set_menu_permission();            
			$this->session->set_flashdata('success_msg', 'User permission successfully Updated.');			
		}
		redirect(adminportal_url().'users','refresh');
	}

	public function update_work_order()
	{
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$admin_session_data = $this->session->userdata('adminportal_session_data'); 
	        $user_id = $admin_session_data['user_id'];
			$user_type = $admin_session_data['user_type'];
			
			$mode=$this->input->post('mode');
			$worder_id=$this->input->post('worder_id');

			$account_type_id=$this->input->post('account_type_id');
			$no_of_user=$this->input->post('no_of_user');
			$domain_name=$this->input->post('domain_name');
			$deal_value=$this->input->post('deal_value');
			$payment_terms=$this->input->post('payment_terms');
			$lead_id=$this->input->post('lead_id');
			$subscription_period=$this->input->post('subscription_period');
			$company_name=$this->input->post('company_name');
			$company_address=$this->input->post('company_address');
			$country_id=$this->input->post('country_id');
			$state_id=$this->input->post('state_id');
			$city_id=$this->input->post('city_id');
			$pin_code=$this->input->post('pin_code');
			$gst_number=$this->input->post('gst_number');
			$owner_name=$this->input->post('owner_name');
			$contact_person=$this->input->post('contact_person');
			$email_id=$this->input->post('email_id');
			$mobile_number=$this->input->post('mobile_number');
			$website=$this->input->post('website');
			$approve_status=$this->input->post('approve_status');
			$service_id=$this->input->post('service_id');
			$display_name=$this->input->post('display_name');
			$start_date=$this->input->post('start_date');
			$end_date=$this->input->post('end_date');
			$expiry_date=$this->input->post('expiry_date');

			$validation_chk=true;
			$err_msg='';

			if(trim($domain_name)=='' || preg_match("#^[a-z0-9]*$#", $domain_name)==false){
				$validation_chk=false;
				$err_msg="Please Enter LMSbaba URL Name (Allowed only small alphabet from a to z & Numeric Number)";
			} elseif(trim($deal_value)=='' || !is_numeric($deal_value)){
				$validation_chk=false;
				$err_msg="Please Enter Correct Deal Value (Allowed only Numeric Number)";
			} elseif(trim($lead_id)=='' || !is_numeric($lead_id)){
				$validation_chk=false;
				$err_msg="Please Enter Correct Lead ID (Allowed only Numeric Number)";
			} elseif(trim($subscription_period)==''){
				$validation_chk=false;
				$err_msg="Please Enter Subscription Period (In Months)";
			} elseif(trim($company_name)==''){
				$validation_chk=false;
				$err_msg="Please Enter Company Name";
			} elseif(trim($company_address)==''){
				$validation_chk=false;
				$err_msg="Please Enter Company Address";
			} elseif(trim($owner_name)==''){
				$validation_chk=false;
				$err_msg="Please Enter Owner Name";
			} elseif(trim($contact_person)==''){
				$validation_chk=false;
				$err_msg="Please Enter Contact Person Name";
			}  elseif(trim($email_id)=='' || !filter_var($email_id, FILTER_VALIDATE_EMAIL)){
				$validation_chk=false;
				$err_msg="Please Enter Correct Email ID";
			} elseif(trim($mobile_number)=='' || strlen($mobile_number)<10 || !is_numeric($mobile_number)){
				$validation_chk=false;
				$err_msg="Please Enter 10 digits Mobile Number (Allowed only Numeric Number)";
			} elseif(trim($display_name)==''){
				$validation_chk=false;
				$err_msg="Please Enter Service Title";
			} elseif(trim($start_date)==''){
				$validation_chk=false;
				$err_msg="Please Select Start Date";
			} elseif(trim($end_date)==''){
				$validation_chk=false;
				$err_msg="Please Select End Date";
			} elseif(trim($expiry_date)==''){
				$validation_chk=false;
				$err_msg="Please Select Expiry Date";
			}

			if($validation_chk==true){

				$chk_domain_name=$this->Client_model->chk_domain_name($domain_name);

				if($chk_domain_name==TRUE){
					$status_str='failed';  
					$html="Sorry! This LMSbaba URL already Exist";
				} else {

					$start_date = date('Y-m-d',strtotime($start_date));
					$end_date = date('Y-m-d',strtotime($end_date));
					$expiry_date = date('Y-m-d',strtotime($expiry_date));

					$admin_session_data = $this->session->userdata('adminportal_session_data'); 
	        		$user_id = $admin_session_data['user_id'];

					if($mode=='N'){
						$post_data=array(
							'created_by'=>$user_id,
							'account_type_id'=>$account_type_id,
							'no_of_user'=>$no_of_user,
							'domain_name'=>$domain_name,
							'deal_value'=>$deal_value,
							'payment_terms'=>$payment_terms,
							'lead_id'=>$lead_id,
							'subscription_period'=>$subscription_period,
							'company_name'=>$company_name,
							'company_address'=>$company_address,
							'country_id'=>$country_id,
							'state_id'=>$state_id,
							'city_id'=>$city_id,
							'pin_code'=>$pin_code,
							'gst_number'=>$gst_number,
							'owner_name'=>$owner_name,
							'contact_person'=>$contact_person,
							'email_id'=>$email_id,
							'mobile_number'=>$mobile_number,
							'website'=>$website,
							'service_id'=>$service_id,
							'display_name'=>$display_name,
							'start_date'=>$start_date,
							'end_date'=>$end_date,
							'expiry_date'=>$expiry_date,
							'approve_status'=>'P',
							'created_at'=>date("Y-m-d H:i:s")
							);
						$return=$this->Adminportal_model->insert_work_order($post_data);
						$this->session->set_flashdata('success_msg', 'New Work Order successfully Added.');
					} elseif($mode=='E'){

						$post_data=array(
							'updated_by'=>$user_id,
							'account_type_id'=>$account_type_id,
							'no_of_user'=>$no_of_user,
							'domain_name'=>$domain_name,
							'deal_value'=>$deal_value,
							'payment_terms'=>$payment_terms,
							'lead_id'=>$lead_id,
							'subscription_period'=>$subscription_period,
							'company_name'=>$company_name,
							'company_address'=>$company_address,
							'country_id'=>$country_id,
							'state_id'=>$state_id,
							'city_id'=>$city_id,
							'pin_code'=>$pin_code,
							'gst_number'=>$gst_number,
							'owner_name'=>$owner_name,
							'contact_person'=>$contact_person,
							'email_id'=>$email_id,
							'mobile_number'=>$mobile_number,
							'website'=>$website,
							'service_id'=>$service_id,
							'display_name'=>$display_name,
							'start_date'=>$start_date,
							'end_date'=>$end_date,
							'expiry_date'=>$expiry_date,
							'updated_at'=>date("Y-m-d H:i:s")
						);

						if($user_type=='Admin'){
							$post_data['approve_status']=$approve_status;
						}

						if($this->Adminportal_model->update_work_order($post_data,$worder_id)){
							
							if($user_type=='Admin' && $approve_status=='A'){
								$worder_details=$this->Adminportal_model->get_work_order_details($worder_id);

								$api_url = "https://app.lmsbaba.com/";
								$api_access_token = md5($worder_details->domain_name);
								//$db_username="admin356";
								//$db_password="wDZddi9nGX5mS2PZbGlW";
								$db_username='';
								$db_password='';
								$db_name="u412811690_";
								$insert_data=array(
									'account_type_id'=>$worder_details->account_type_id,
									'name'=>$worder_details->company_name,
									'domain_name'=>$worder_details->domain_name,
									'api_url'=>$api_url,
									'api_access_token'=>$api_access_token,
									'logo'=>"logo.png",
									'favicon'=>"favicon.ico",
									'db_username'=>$db_username,
									'db_password'=>$db_password,
									'db_name'=>$db_name,
									
									'no_of_user'=>$worder_details->no_of_user,
									'deal_value'=>$worder_details->deal_value,
									'payment_terms'=>$worder_details->payment_terms,
									'lead_id'=>$worder_details->lead_id,
									'subscription_period'=>$worder_details->subscription_period,
									'company_name'=>$worder_details->owner_name,
									'company_address'=>$worder_details->company_address,
									'country_id'=>$worder_details->country_id,
									'state_id'=>$worder_details->state_id,
									'city_id'=>$worder_details->city_id,
									'pin_code'=>$worder_details->pin_code,
									'gst_number'=>$worder_details->gst_number,
									'contact_person'=>$worder_details->contact_person,
									'website'=>$worder_details->website,

									'activity_status_type_id'=>5,
									'created_at'=>date("Y-m-d H:i:s"),
									'updated_at'=>date("Y-m-d H:i:s")
								);

								
								$client_id=$this->Client_model->insert_as_client($insert_data);
								if(trim($client_id)>0){

									$clientdata = array('db_name'=>$db_name.$client_id);
									$this->Client_model->update_client_assign_to_user($clientdata,$client_id);

									$workorderdata = array('client_tbl_id'=>$client_id);
									$this->Adminportal_model->update_work_order($workorderdata,$worder_id);

									
									$client_details = $this->Client_model->get_details($client_id);
									$client_post_data=array();
									$client_post_data=array(
										'name'=>$worder_details->company_name,
										'address'=>$worder_details->company_address,
										'city_id'=>$worder_details->city_id,
										'state_id'=>$worder_details->state_id,
										'country_id'=>$worder_details->country_id,
										'pin'=>$worder_details->pin_code,
										'gst_number'=>$worder_details->gst_number,
										'contact_person'=>$worder_details->contact_person,
										'ceo_name'=>$worder_details->owner_name,
										'email1'=>$worder_details->email_id,
										'mobile1'=>$worder_details->mobile_number,
										'website'=>$worder_details->website,
										'updated_at'=>date("Y-m-d H:i:s")
										);
									$this->Setting_model->UpdateCompany($client_post_data,1,$client_details);


									$service_info=$this->Client_model->get_service_row($service_id);

									$post_data=array();
									$post_data=array(
										'client_id'=>$client_id,
										'service_id'=>$service_id,
										'service_name'=>$service_info['name'],
										'total_price'=>$worder_details->deal_value,
										'created_at'=>date("Y-m-d H:i:s")
										);
									$service_order_id=$this->Client_model->add_client_wise_service_order($post_data);

									if(trim($service_order_id)!=''){
										$post_data=array();
										$post_data=array(
											'service_order_id'=>$service_order_id,
											'display_name'=>$worder_details->display_name,
											'no_of_user'=>$worder_details->no_of_user,
											'price'=>$worder_details->deal_value,
											'start_date'=>$worder_details->start_date,
											'end_date'=>$worder_details->end_date,
											'expiry_date'=>$worder_details->expiry_date,
											'service_call_type_id'=>1,
											'next_followup_date'=>date("Y-m-d H:i:s"),
											'is_active'=>'Y',
											'created_at'=>date("Y-m-d H:i:s")
											);
										$service_detail_id=$this->Client_model->add_client_wise_service_order_detail($post_data);

										if(trim($service_detail_id)!=''){
											$servicecall_data=array();
											$servicecall_data=array(
												'service_id'=>$service_detail_id,
												'service_call_type_id'=>1,
												'call_by_user_id'=>1,
												'scheduled_call_datetime'=>date("Y-m-d H:i:s"),
												'created_at'=>date("Y-m-d H:i:s")
												);
											
											$this->Client_model->add_service_wise_service_call($servicecall_data);

											$post_data=array();
											$post_data=array(
												'service_order_detail_id'=>$service_detail_id,
												'display_name'=>$worder_details->display_name,
												'no_of_user'=>$worder_details->no_of_user,
												'price'=>$worder_details->deal_value,
												'start_date'=>$worder_details->start_date,
												'end_date'=>$worder_details->end_date,
												'expiry_date'=>$worder_details->expiry_date,
												'is_active'=>'Y',
												'created_at'=>date("Y-m-d H:i:s")
												);
											$this->Client_model->add_client_wise_service_order_detail_log($post_data);


											// =========================
											// ACTIVITY LOG
											$activity_text .="<br>Service Name: ".$service_info['name'].'-'.$display_name;
											$activity_text .="<br>No of user: ".$worder_details->no_of_user;
											$activity_text .="<br>Amount: ".$worder_details->deal_value;
											$activity_text .="<br>Start Date: ".date_db_format_to_display_format($worder_details->start_date);
											$activity_text .="<br>End Date: ".date_db_format_to_display_format($worder_details->end_date);
											$activity_text .="<br>Expiry Date: ".date_db_format_to_display_format($worder_details->expiry_date);						
											$ip_address=$_SERVER['REMOTE_ADDR'];
											$activity_title=LOG_TITLE_CLIENT_SERVICE_ADD;
											
											$logdata=array(
														'user_id'=>1,
														'client_id'=>$client_id,
														'activity_title'=>$activity_title,								
														'activity_text'=>$activity_text,
														'ip_address'=>$ip_address,
														'created_at'=>date("Y-m-d H:i:s")
														);
											$this->Client_model->CreateActivityLog($logdata);
											// ACTIVITY LOG
											// =========================


										}
									}

								}
							}

							$this->session->set_flashdata('success_msg', 'Work Order details successfully Updated.');
						}
					}
						
						$status_str='success';  
						$html='SUCCESS';
				}

				
			} else {
				$status_str='failed';  
				$html=$err_msg;
			}

	        $result["status"] = $status_str;
	        $result["html"]=$html;
	        echo json_encode($result);
	        exit(0);
		}
	}

	public function get_add_work_order_view_ajax()
	{
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{	
			$list['account_type']=$this->Client_model->get_account_type();
			$list['service_list']=$this->Client_model->get_service();	
			$list['country_list']=$this->countries_model->GetCountriesList_maindb();
			$html = $this->load->view('adminmaster/work_order_add_popup_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;
	        echo json_encode($result);
	        exit(0);
		}
	}

	public function get_edit_work_order_view_ajax()
	{
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$worder_id=$this->input->post('worder');
			$worder_details=$this->Adminportal_model->get_work_order_details($worder_id);
			$list['worder_details']=$worder_details;
			$list['account_type']=$this->Client_model->get_account_type();
			$list['service_list']=$this->Client_model->get_service();
			$list['used_last_lms_id']=$this->Client_model->get_last_lmsid();
			$list['total_client_count']=$this->Client_model->total_client_count();
			$list['total_client_db_count']=$this->Client_model->total_client_db_count();
			$list['country_list']=$this->countries_model->GetCountriesList_maindb();
			$list['state_list']=$this->states_model->GetStatesList_maindb($worder_details->country_id);
			$list['city_list']=$this->cities_model->GetCitiesList_maindb($worder_details->state_id);

			$html = $this->load->view('adminmaster/work_order_edit_popup_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;
	        echo json_encode($result);
	        exit(0);
		}
	}

	public function delete_workorder()
	{
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$order_id=$this->input->post('uid');
			if($this->Adminportal_model->delete_work_order($order_id)==true){
				$status_str='success';
				$this->session->set_flashdata('success_msg', 'This Work Order successfully Deleted.');
			} else {
				$status_str='failed'; 
			}

	        $result["status"] = $status_str;
	        $result["html"]='';
	        echo json_encode($result);
	        exit(0);
		}
	}

	public function getstatelist()
	{
		$data=array();
		
		$country_id=$this->input->post('country_id');
		$selected_id=$this->input->post('selected_id');
		if($country_id!='')	
		{
			$state_list=$this->states_model->GetStatesList_maindb($country_id);
			if($state_list)
			{
				echo '<option value="">Select</option>';
				foreach($state_list as $state_data)
				{
					$select_option=($selected_id==$state_data->id)?"selected":"";
					echo '<option value="'.$state_data->id.'" '.$select_option.'>'.$state_data->name.'</option>';
				}
				
			}
			else
			{
				echo '';
			}
			
		}
		else
		{
			echo '';
		}
	}

	public function getcitylist()
	{
		$data=array();
		
		$state_id=$this->input->post('state_id');	
		$selected_id=$this->input->post('selected_id');
		if($state_id!='')	
		{
			$city_list=$this->cities_model->GetCitiesList_maindb($state_id);
			if($city_list)
			{
				echo '<option value="">Select</option>';
				foreach($city_list as $city_data)
				{
					$select_option=($selected_id==$city_data->id)?"selected":"";
					echo '<option value="'.$city_data->id.'" '.$select_option.'>'.$city_data->name.'</option>';
				}
				
			}
			else
			{
				
				echo '';
				
			}
			
		}
		else
		{
			
			echo '';
			
		}
		
		
		
	}

	
	
	

	

	

	
}