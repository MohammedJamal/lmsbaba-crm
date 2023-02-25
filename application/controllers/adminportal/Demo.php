<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Demo extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_adminportal_logged_in();
		chk_access_menu_permission(3);
		init_adminportal_element();
		$this->load->model(array("Adminportal_model", "User_model", "Client_model","cities_model"));
	}

	public function index()
	{
		$data = array();

		$data['topmenu_list'] = get_permission_wise_menu_list();

		$this->load->view('adminmaster/demo_view', $data);
	}

	public function get_demo_list_ajax()
	{

		if (strtolower($_SERVER["REQUEST_METHOD"]) == 'post') {
			$this->load->library('pagination');
			$argument = array();

			$filter_demo_type = $this->input->post('filter_demo_type');
			$filter_year = $this->input->post('filter_year');
			$filter_month = $this->input->post('filter_month');
			$argument['filter_demo_type'] = $filter_demo_type;
			$argument['filter_year'] = $filter_year;
			$argument['filter_month'] = $filter_month;

			$session_data = $this->session->userdata('adminportal_session_data');
			$user_id = $session_data['user_id'];
			$user_type = $session_data['user_type'];
			$tmp_u_ids = $this->Adminportal_model->get_self_and_under_alluser_ids($user_id);
			$tmp_u_ids_str = implode(",", $tmp_u_ids);
			//$argument['user_type'] = $user_type;
			//$argument['assigned_user'] = $tmp_u_ids_str;

			// PAGINATION COUNT INFO SHOW: START
			$tmp_start = ($start + 1);
			$tmp_limit = ($limit < ($config['total_rows'] - $start)) ? ($start + $limit) : $config['total_rows'];
			$page_record_count_info = "Showing " . $tmp_start . " to " . $tmp_limit . " of " . $config['total_rows'] . " entries";
			// PAGINATION COUNT INFO SHOW: END			
			
			
			$list['rows'] = $this->Adminportal_model->get_demo_list($argument);
			$list['sl_start'] = $tmp_start;
			$list['testfield'] = $this->input->post('page');
			$html = $this->load->view('adminmaster/demo_list_view_ajax', $list, TRUE);
			$status_str = 'success';
			$result["status"] = $status_str;
			$result["html"] = $html;
			//$result["html"]=$list['rows'];
			echo json_encode($result);
			exit(0);
		}
	}

	
	public function get_add_demo_view_ajax()
	{
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{	
			$list['account_type']=$this->Client_model->get_account_type();
			$list['service_list']=$this->Client_model->get_service();	
			//$list['country_list']=$this->countries_model->GetCountriesList_maindb();
			$list['city_list']=$this->Adminportal_model->GetAllIndianCitiesList();
			$list['lead_generation_list']=$this->Adminportal_model->GetAllLeadGenerationList();
			$list['get_users_list']=$this->Adminportal_model->get_users_list();
			
			$html = $this->load->view('adminmaster/demo_add_popup_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;
	        echo json_encode($result);
	        exit(0);
		}
	}

	



	public function get_demo_confirm_ajax()
	{
		if (strtolower($_SERVER["REQUEST_METHOD"]) == 'post') {



			$list = array();
			$id = $this->input->post('id');
			$title = $this->input->post('title');
			$lid = $this->input->post('lid');
			

			
			if ($id!='' && $lid!='')
			{
				$list['demo_row'] = $this->Adminportal_model->get_demo_done_details($id,$lid);
				$list['id'] = $id;
				$list['title'] = $title;
				$list['lid'] = $lid; 
				$html = $this->load->view('adminmaster/get_demo_confirm_edit_view_popup_ajax', $list, TRUE);
				$status_str = 'success';
				$result["status"] = $status_str;
				$result["html"] = $html;
				echo json_encode($result);
				exit(0);
			}
		}
	}



	public function get_demo_reschedule_ajax()
	{
		if (strtolower($_SERVER["REQUEST_METHOD"]) == 'post') {



			$list = array();
			$id = $this->input->post('id');
			$title = $this->input->post('title');
			$lid = $this->input->post('lid');
			

			
			if ($id!='' && $lid!='')
			{
				$list['demo_row'] = $this->Adminportal_model->get_demo_done_details($id,$lid);
				$list['id'] = $id;
				$list['title'] = $title;
				$list['lid'] = $lid; 
				$html = $this->load->view('adminmaster/get_demo_reschedule_edit_view_popup_ajax', $list, TRUE);
				$status_str = 'success';
				$result["status"] = $status_str;
				$result["html"] = $html;
				echo json_encode($result);
				exit(0);
			}
		}
	}








	public function save_demo_done_update_ajax()
	{
		if (strtolower($_SERVER["REQUEST_METHOD"]) == 'post') {
			
			
			$id = $this->input->post('id');
			$lead_id = $this->input->post('lead_id');
			$done_demo_date = $this->input->post('done_demo_date');
			$done_start_time = $this->input->post('done_start_time');
			$done_end_time = $this->input->post('done_end_time');
			$next_followup_date = $this->input->post('next_followup_date');
			$quotation_sent = $this->input->post('quotation_sent');
			$done_prospect = $this->input->post('done_prospect');

			$user_required = $this->input->post('user_required');
			$done_comment = $this->input->post('done_comment');

			if(trim($done_demo_date)!=''){
				$done_demo_date = date('Y-m-d',strtotime($done_demo_date));
			}
			if(trim($next_followup_date)!=''){
				$next_followup_date = date('Y-m-d',strtotime($next_followup_date));
			}
			
			if ($done_demo_date != '' && $done_start_time != '' && $done_end_time != '' && $next_followup_date != ''&& $quotation_sent != '' && $done_prospect !='' && $user_required !='' && $done_comment!='') 
			{
				$updated_Date = date("Y-m-d H:i:s");
				$update_data = array(
					'demo_date' => $done_demo_date,
					'demo_time' => $done_start_time,
					'end_time' => $done_end_time,
					'next_followup_date' => $next_followup_date,
					'quotation_sent' => $quotation_sent,
					'done_prospect' => $done_prospect,
					'comment' => $done_comment,
					'user_required' => $user_required,
					'status' => 'D',
					'updated_at' => $updated_Date
				);

				$user_id = $this->session->userdata['adminportal_session_data']['user_id'];
				//$ip_address = $_SERVER['REMOTE_ADDR'];
				
				//$today = date("Y-m-d H:i:s");
				
				
				if ($this->Adminportal_model->update_demo($update_data, $id)) {

					
					
					// START DEMO ACTIVITY LOG -----------------------------------------
					$activity_text .="<br>Lead Id: ".$lead_id;
					$activity_text .="<br>done_demo_date: ".date_db_format_to_display_format($done_demo_date);
					$activity_text .="<br>done_start_time: ".$done_start_time;
					$activity_text .="<br>done_end_time: ".$done_end_time;
					$activity_text .="<br>next_followup_date: ".$next_followup_date;
					$activity_text .="<br>quotation_sent: ".$quotation_sent;
					$activity_text .="<br>done_prospect: ".$done_prospect;
					$activity_text .="<br>done_comment: ".$done_comment;
					$activity_text .="<br>status: D";
					$activity_text .="<br>updated_at: ".$updated_Date;
					$ip_address=$_SERVER['REMOTE_ADDR'];
					$activity_title=LOG_DEMO_DONE_ADD;
											
					$logdata=array(
							'user_id'=>$user_id,
							'demo_id'=>$id,
							'lead_id'=>$lead_id,
							'demo_date' => $done_demo_date,
							'demo_time' => $done_start_time,
							'end_time' => $done_end_time,
							'activity_title'=>$activity_title,								
							'activity_text'=>$activity_text,
							'comment'=>$done_comment,
							'ip_address'=>$ip_address,
							'next_followup_date' => $next_followup_date,
							'status'=>'D',
							'created_at'=>date("Y-m-d H:i:s")
							);


					// END DEMO ACTIVITY LOG -----------------------------------------
					$this->Adminportal_model->CreateDemoActivityLog($logdata);
							
					

					$status_str = 'success';
				} else {
					$status_str = 'Oops! Failed to Update.';
				}

			} else {
				$status_str = 'Expected Data Missing';
			}
			$result["status"] = $status_str;
			echo json_encode($result);
			exit(0);
		}
	}


	public function save_demo_confirm_update_ajax()
	{
		if (strtolower($_SERVER["REQUEST_METHOD"]) == 'post') {
			
			
			$id = $this->input->post('id');
			$lead_id = $this->input->post('lead_id');
			
			$confirm_time = $this->input->post('confirm_time');
			
			if ($confirm_time != '') 
			{
				$updated_Date = date("Y-m-d");
				$update_data = array(
					'demo_time' => $confirm_time,
					'status' => 'CM',
					'updated_at' => $updated_Date
				);

				$user_id = $this->session->userdata['adminportal_session_data']['user_id'];
				//$ip_address = $_SERVER['REMOTE_ADDR'];
				
				//$today = date("Y-m-d H:i:s");
				
				
				if ($this->Adminportal_model->update_demo($update_data, $id)) {

					
					
					// START DEMO ACTIVITY LOG -----------------------------------------
					$activity_text .="<br>Lead Id: ".$lead_id;
					$activity_text .="<br>Lead Id: ".$lead_id;
					$activity_text .="<br>Demo_Id: ".$demo_id;
					$activity_text .="<br>confirm_time: ".$confirm_time;
					$activity_text .="<br>status: CM";
					$activity_text .="<br>updated_at: ".$updated_Date;
					$ip_address=$_SERVER['REMOTE_ADDR'];
					$activity_title=LOG_DEMO_CONFIRM_ADD;
											
					$logdata=array(
							'user_id'=>$user_id,
							'lead_id'=>$lead_id,
							'demo_id'=>$id,
							'activity_title'=>$activity_title,								
							'activity_text'=>$activity_text,
							'ip_address'=>$ip_address,
							'end_time'=>$confirm_time,
							'status' => 'CM',
							'created_at' => $updated_Date
							);


					// END DEMO ACTIVITY LOG -----------------------------------------
					$this->Adminportal_model->CreateDemoActivityLog($logdata);
							
					

					$status_str = 'success';
				} else {
					$status_str = 'Oops! Failed to Update.';
				}

			} else {
				$status_str = 'Expected Data Missing';
			}
			$result["status"] = $status_str;
			echo json_encode($result);
			exit(0);
		}
	}


	public function get_demo_list_view_ajax()
	{
		if (strtolower($_SERVER["REQUEST_METHOD"]) == 'post') {



			$list = array();
			$id = $this->input->post('id');
			$title = $this->input->post('title');
			$lid = $this->input->post('lid');
			

			
			if ($id!='' && $lid!='')
			{
				$list['demo_row'] = $this->Adminportal_model->get_demo_done_details($id,$lid);
				$list['id'] = $id;
				$list['title'] = $title;
				$list['lid'] = $lid; 
				$html = $this->load->view('adminmaster/get_demo_done_edit_view_popup_ajax', $list, TRUE);
				$status_str = 'success';
				$result["status"] = $status_str;
				$result["html"] = $html;
				echo json_encode($result);
				exit(0);
			}
		}
	}

	



	public function update_demo_order()
	{
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$admin_session_data = $this->session->userdata('adminportal_session_data'); 
	        $user_id = $admin_session_data['user_id'];
			$user_type = $admin_session_data['user_type'];

			$filter_demo_type=$this->input->post('filter_demo_type');
			$filter_year=$this->input->post('filter_year');
			$filter_month=$this->input->post('filter_month');
			
			$lead_id=$this->input->post('lead_id');
			$company_name=$this->input->post('company_name');
			$contact_person=$this->input->post('contact_person');
			$email_id=$this->input->post('email_id');
			$mobile=$this->input->post('mobile');
			$demo_date=$this->input->post('demo_date');
			$demo_time=$this->input->post('demo_time');
			$location=$this->input->post('location');
			$lead_generation_platform=$this->input->post('lead_generation_platform[]');
			$sales_person=$this->input->post('sales_person');
			$schedule_comment=$this->input->post('schedule_comment');

			$validation_chk=true;
			$err_msg='';
			
			if(trim($lead_id)=='' || !is_numeric($lead_id)){
				$validation_chk=false;
				$err_msg="Please Enter Lead Id (Allowed only Numeric Number)";
			}elseif(trim($company_name)==''){
				$validation_chk=false;
				$err_msg="Please Enter Company Name";
			} elseif(trim($contact_person)==''){
				$validation_chk=false;
				$err_msg="Please Enter Contact Person";
			}  elseif(trim($email_id)=='' || !filter_var($email_id, FILTER_VALIDATE_EMAIL)){
				$validation_chk=false;
				$err_msg="Please Enter Correct Email ID";
			} elseif(trim($mobile)==''){
				$validation_chk=false;
				$err_msg="Please Enter 10 digits Mobile Number (Allowed only Numeric Number)";
			}elseif(strlen($mobile)!=10){
				$validation_chk=false;
				$err_msg="Please Enter 10 digits Mobile Number (Allowed only Numeric Number)";
			}elseif(!is_numeric($mobile)){
				$validation_chk=false;
				$err_msg="Please Enter 10 digits Mobile Number (Allowed only Numeric Number)";
			}
			  elseif(trim($demo_date)==''){
				$validation_chk=false;
				$err_msg="Please Enter Demo Date";
			}  elseif(trim($demo_time)==''){
				$validation_chk=false;
				$err_msg="Please Enter Demo Time";
			}  elseif(trim($location)==''){
				$validation_chk=false;
				$err_msg="Please Select Location";
			}   elseif(empty($lead_generation_platform)==true){
				$validation_chk=false;
				$err_msg="Please Select Lead Generation Platform";
			}  elseif(trim($sales_person)==''){
				$validation_chk=false;
				$err_msg="Please Select Sales Person";
			}   elseif(trim($schedule_comment)==''){
				$validation_chk=false;
				$err_msg="Please Enter Schedule Comment";
			}
			
			if($validation_chk==true)
			{

					$demo_date = date('Y-m-d',strtotime($demo_date));
					$admin_session_data = $this->session->userdata('adminportal_session_data'); 
	        		$user_id = $admin_session_data['user_id'];
					if(empty($lead_generation_platform)==false)
					{
						$lead_generation_platform = implode(",",$lead_generation_platform);
					}
					//if($mode=='N'){
						$post_data=array(
							'lead_id'=>$lead_id,
							'company_name'=>$company_name,
							'contact_person'=>$contact_person,
							'email_id'=>$email_id,
							'mobile'=>$mobile,
							'demo_date'=>$demo_date,
							'demo_time'=>$demo_time,
							'lead_generation_platform'=>$lead_generation_platform,
							'sales_person'=>$sales_person,
							'location'=>$location,
							'status'=>'S',
							'comment'=>$schedule_comment,
							'is_active'=>'Y',
							'is_deleted'=>'N',
							'created_at'=>date("Y-m-d"),
							'created_by'=>$user_id
							);
							
							//user_required
						$return=$this->Adminportal_model->insert_demo($post_data);
						$this->session->set_flashdata('success_msg', 'New Scheduled Demo successfully Added.');
					

					// =========================
						// ACTIVITY LOG

						//'schedule_comment'=>$schedule_comment,

						$activity_text .="<br>Lead Id: ".$lead_id;
						$activity_text .="<br>Company Name: ".$company_name;
						$activity_text .="<br>Contact Person: ".$contact_person;
						$activity_text .="<br>Email Id: ".$email_id;
						$activity_text .="<br>Mobile: ".$mobile;
						$activity_text .="<br>Schedule Comment: ".$schedule_comment;
						
						$activity_text .="<br>Schedule Demo Date: ".date_db_format_to_display_format($demo_date);
						$activity_text .="<br>Demo Time: ".$demo_time;
						$activity_text .="<br>Lead Generation Platform: ".$lead_generation_platform;
						$activity_text .="<br>Sales Person: ".$sales_person;
						$activity_text .="<br>Location: ".$location;
						//$activity_text .="<br>Location: ".$location;
						$activity_text .="<br>Created By: ".$user_id;
						//$activity_text .="<br>User Required: ".$user_required;
						$ip_address=$_SERVER['REMOTE_ADDR'];
						$activity_title=LOG_DEMO_ADD;
											
						$logdata=array(
										'user_id'=>$user_id,
										'lead_id'=>$lead_id,
										'demo_id'=>$return,
										'activity_title'=>$activity_title,								
										'activity_text'=>$activity_text,
										'demo_date'=>$demo_date,
										'demo_time'=>$demo_time,
										'comment'=>$schedule_comment,
										'status'=>'S',
										'ip_address'=>$ip_address,
										'created_at'=>date("Y-m-d")
										);
									$this->Adminportal_model->CreateDemoActivityLog($logdata);
						// ACTIVITY LOG
						// =========================
						$this->session->set_flashdata('success_msg', 'Demo details successfully Updated.');
						
					//}
						
						$status_str='success';  
						$html='SUCCESS';
			} 
			else
			{
				$status_str='failed';  
				$html=$err_msg;
			}

	        $result["status"] = $status_str;
	        $result["html"]=$html;
	        echo json_encode($result);
	        exit(0);
		}
	}


	

}