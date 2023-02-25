<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Downloads extends CI_Controller {
	function __construct()
	{
		parent :: __construct();
		is_adminportal_logged_in();
		chk_access_menu_permission(5);
		init_adminportal_element();   
		$this->load->model(array("Adminportal_model","User_model"));
	}

	public function index()
	{	
		$data = array(); 
		
		$data['topmenu_list']=get_permission_wise_menu_list();

		$this->load->view('adminmaster/downloads_view',$data);
	}

	public function get_client_list_ajax()
	{
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$argument = array();

			$account_type_id=$this->input->post('account_type');
			$is_active=$this->input->post('is_active');
			$argument['account_type_id']=$account_type_id;
			$argument['is_active']=$is_active;		
			
			$session_data=$this->session->userdata('adminportal_session_data');
			$user_id=$session_data['user_id'];
   			$user_type=$session_data['user_type'];
			$tmp_u_ids=$this->Adminportal_model->get_self_and_under_alluser_ids($user_id);
			$tmp_u_ids_str=implode(",", $tmp_u_ids);
			$argument['user_type']=$user_type;
			$argument['assigned_user']=$tmp_u_ids_str;


			$list['rows']=$this->Adminportal_model->get_downloads_client_list($argument);

			$html = $this->load->view('adminmaster/downloads_list_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;
	        echo json_encode($result);
	        exit(0);
		}
	}

	public function get_download_comment_log_list_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$cid=$this->input->post('cid');
			$list['comment_list']=$this->Adminportal_model->get_download_comment_list($cid);	
			$html = $this->load->view('adminmaster/download_wise_comment_log_list_view_ajax.php',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;	  

	        echo json_encode($result);
	        exit(0);
		}
	}

	public function get_call_comment_view_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			
			$list=array();
			$cid=$this->input->post('cid');
			$tid=$this->input->post('tid');
			$title=$this->input->post('title');
			$scid=$this->input->post('scid');

			if($cid!='')
			{	
				$list['client_row']=$this->Adminportal_model->get_inactive_client_details($cid);
				$list['update_type']=$this->Adminportal_model->get_update_type();
				$list['comment_list']=$this->Adminportal_model->get_download_comment_list($cid);	
				$list['client_id']=$cid;
				$list['call_type_id']=$tid;
				$list['call_type_name']=$title;
				$list['service_call_id']=$scid;
				$html=$this->load->view('adminmaster/get_downloads_edit_view_popup_ajax',$list,TRUE);					
				$status_str='success';			  
				$result["status"] = $status_str;	
				$result["html"] = $html;	  
				echo json_encode($result);
				exit(0);
			}			
		}
	}

	public function get_create_call_view_popup_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$list=array();
			$cid=$this->input->post('cid');
			$tid=7;
			$title="Downloads Calls";

			if($cid!='')
			{	
				$list['client_row']=$this->Adminportal_model->get_inactive_client_details($cid);
				$list['update_type']=$this->Adminportal_model->get_update_type();
				$list['comment_list']=$this->Adminportal_model->get_download_comment_list($cid);	
				$list['client_id']=$cid;
				$list['call_type_id']=$tid;
				$list['call_type_name']=$title;
				$html=$this->load->view('adminmaster/get_create_call_view_popup_ajax',$list,TRUE);					
				$status_str='success';			  
				$result["status"] = $status_str;	
				$result["html"] = $html;	  
				echo json_encode($result);
				exit(0);
			}			
		}
	}

	public function save_comment_update_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$client_id=$this->input->post('client_id');
			$client_update_type_id=$this->input->post('update_type_id');
			$call_done=$this->input->post('call_done');		
			$followup_date=$this->input->post('followup_date');
			$activity_text=$this->input->post('comment_text');
			$call_type_id=$this->input->post('call_type_id');
			$call_type_name=$this->input->post('call_type_name');
			$service_call_id=$this->input->post('service_call_id');
			$form_data = $this->input->post();
			
			if($client_id!='' && $client_update_type_id!='' && $activity_text!='' && ($call_done!='' OR $followup_date!=''))
			{
				$user_id=$this->session->userdata['adminportal_session_data']['user_id'];
				$ip_address=$_SERVER['REMOTE_ADDR'];
				$activity_type='';
				$activity_title=$call_type_name;
				$today=date("Y-m-d H:i:s");
				$post_data=array(
						'user_id'=>$user_id,
						'client_id'=>$client_id,
						'activity_type'=>$activity_type,
						'client_update_type_id'=>$client_update_type_id,
						'activity_title'=>$activity_title,
						'activity_text'=>$activity_text,
						'ip_address'=>$ip_address,
						'followup_date'=>$followup_date,
						'created_at'=>$today
						);
				$return=$this->Adminportal_model->insert_comment($post_data);				
				if($return==true)
				{

					if($call_done==1){
						$update_data=array(
							'call_by_user_id'=>$user_id,
							'actual_call_done_datetime'=>$today
						);
					} else {
						$update_data=array(
							'call_by_user_id'=>$user_id,
							'scheduled_call_datetime'=>$followup_date
						);
					}
					$this->Adminportal_model->service_call_update($update_data,$service_call_id);

					$status_str='success';
				}
				else
				{
					$status_str='Oops! Failed to Update.';
				}				  
					  
				
			} else {
				$status_str='Expected Data Missing';
			}	
			$result["status"] = $status_str;
			echo json_encode($result);
			exit(0);		
		}
	}

	public function create_downloads_call_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$client_id=$this->input->post('client_id');
			$client_update_type_id=2;
			$followup_date=$this->input->post('followup_date');
			$activity_text=$this->input->post('comment_text');
			$call_type_id=$this->input->post('call_type_id');
			$call_type_name=$this->input->post('call_type_name');
			$form_data = $this->input->post();
			
			if($client_id!='' && $client_update_type_id!='' && $activity_text!='' && $followup_date!='')
			{
				$user_id=$this->session->userdata['adminportal_session_data']['user_id'];
				$ip_address=$_SERVER['REMOTE_ADDR'];
				$activity_type='';
				$activity_title=$call_type_name;
				$today=date("Y-m-d H:i:s");
				$post_data=array(
						'user_id'=>$user_id,
						'client_id'=>$client_id,
						'activity_type'=>$activity_type,
						'client_update_type_id'=>$client_update_type_id,
						'activity_title'=>$activity_title,
						'activity_text'=>$activity_text,
						'ip_address'=>$ip_address,
						'followup_date'=>$followup_date,
						'created_at'=>$today
						);
				$return=$this->Adminportal_model->insert_comment($post_data);				
				if($return==true)
				{

					$insert_data=array(
						'client_id'=>$client_id,
						'service_call_type_id'=>$call_type_id,
						'call_by_user_id'=>$user_id,
						'scheduled_call_datetime'=>$followup_date,
						'comment'=>$activity_text,
						'form_data'=>json_encode($form_data),
						'created_at'=>$today
					);
					$this->Adminportal_model->insert_call_update($insert_data);

					$status_str='success';
				}
				else
				{
					$status_str='Oops! Failed to Update.';
				}				  
					  
				
			} else {
				$status_str='Expected Data Missing';
			}	
			$result["status"] = $status_str;
			echo json_encode($result);
			exit(0);		
		}
	}

	

	
}