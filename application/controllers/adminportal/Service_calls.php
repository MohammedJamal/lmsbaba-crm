<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_calls extends CI_Controller {
	function __construct()
	{
		parent :: __construct();
		is_adminportal_logged_in();
		chk_access_menu_permission(7);
		init_adminportal_element();   
		$this->load->model(array("Client_model","User_model","Adminportal_model"));
	}

	public function index()
	{	
		$data = array(); 	
		$data['account_type']=$this->Client_model->get_account_type();

		$data['topmenu_list']=get_permission_wise_menu_list();
		$data['service_call_type']=$this->Adminportal_model->get_service_call_type_list('1,2,3,4');
		$this->load->view('adminmaster/service_calls_view',$data);
	}

	public function get_all_client_wise_service_list_ajax()
	{
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$start = $this->input->post('page'); 
			//$start = 2; 
			$this->load->library('pagination');
			$limit=30;
			$config = array();
			$argument = array();

			$account_type_id=$this->input->post('account_type');
			$is_active=$this->input->post('is_active');
			$call_type=$this->input->post('call_type');
			$selected_date=$this->input->post('selected_date');
			$comp_name_id=$this->input->post('comp_name_id');
			$argument['account_type_id']=$account_type_id;
			$argument['is_active']=$is_active;
			$argument['call_type']=$call_type;
			$argument['selected_date']=$selected_date;
			$argument['comp_name_id']=$comp_name_id;
			

			$session_data=$this->session->userdata('adminportal_session_data');
			$user_id=$session_data['user_id'];
   			$user_type=$session_data['user_type'];
			$tmp_u_ids=$this->Adminportal_model->get_self_and_under_alluser_ids($user_id);
			$tmp_u_ids_str=implode(",", $tmp_u_ids);
			$argument['user_type']=$user_type;
			$argument['assigned_user']=$tmp_u_ids_str;

			//$config['base_url'] =base_url('pages_ajax/show');
			$config['base_url'] ='#';
			$config['total_rows'] = $this->Adminportal_model->get_all_service_calls_list_count($argument);	
			//$config['total_rows'] =150;    
			$config['per_page'] = $limit;
			$config['uri_segment']=4;
			$config['num_links'] = 5;
			$config['use_page_numbers'] = TRUE;
			$config['attributes'] = array('class' => 'client_list_page');
			$config["full_tag_open"] = '<ul class="pagination">';
			$config["full_tag_close"] = '</ul>';	
			$config["first_link"] = "&laquo;";
			$config["first_tag_open"] = "<li>";
			$config["first_tag_close"] = "</li>";
			$config["last_link"] = "&raquo;";
			$config["last_tag_open"] = "<li>";
			$config["last_tag_close"] = "</li>";
			$config['next_link'] = '&gt;';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '<li>';
			$config['prev_link'] = '&lt;';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '<li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';

			$start=empty($start)?0:($start-1)*$limit;
			$argument['limit']=$limit;
			$argument['start']=$start;	
			
			$this->pagination->initialize($config);
			$page_link = '';
			$page_link = $this->pagination->create_links();

			// PAGINATION COUNT INFO SHOW: START
			$tmp_start=($start+1);		
			$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
			$page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
			// PAGINATION COUNT INFO SHOW: END			
			
			$list['rows']=$this->Adminportal_model->get_all_service_calls_list($argument);
			$list['page']=$page_link;
			$list['page_record_count_info']=$page_record_count_info;
			$list['sl_start']=$tmp_start;
			$list['service_call_type']=$this->Adminportal_model->get_service_call_type_list('1,2,3,4');
			$html = $this->load->view('adminmaster/service_calls_all_list_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;
	        echo json_encode($result);
	        exit(0);
		}

	}

	public function get_client_wise_comment_log_list_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$cid=$this->input->post('cid');
			$list['comment_list']=$this->Client_model->get_comment_list($cid);	
			$html = $this->load->view('adminmaster/client_wise_comment_log_list_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;	  

	        echo json_encode($result);
	        exit(0);
		}
	}

	public function get_call_log_list_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$cid=$this->input->post('cid');
			$sid=$this->input->post('sid');
			//$arg['client_id']=$cid;
			$arg['service_id']=$sid;
			$list['client_row']=$this->Adminportal_model->get_service_call_details($cid,$sid);
			$list['comment_list']=$this->Adminportal_model->get_service_call_list($arg);	
			$html = $this->load->view('adminmaster/service_calls_log_list_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;	  

	        echo json_encode($result);
	        exit(0);
		}
	}

	public function get_tagged_user_list_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			
			$client_id=$this->input->post('cid');
			$arg=array();
			$arg['client_id']=$client_id;
			$arg['cronjobs_action']='';
			// print_r($arg); die();
			$client_db_info_list=$this->Adminportal_model->get_all($arg);
			$client_info=$client_db_info_list[0];
			$list['rows']=$this->User_model->get_client_wise_user_list_rows($client_info);
			$html = $this->load->view('adminmaster/renewal_wise_user_list_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;	  

	        echo json_encode($result);
	        exit(0);
		}
	}

	public function get_client_edit_view_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$list=array();
			$cid=$this->input->post('cid');			
			if($cid!='')
			{	
				$list['client_row']=$this->Client_model->get_details($cid);
				$list['account_type']=$this->Client_model->get_account_type();	
				$list['client_id']=$cid;
				$html=$this->load->view('adminmaster/get_client_edit_view_popup_ajax',$list,TRUE);					
				$status_str='success';			  
				$result["status"] = $status_str;	
				$result["html"] = $html;	  
				echo json_encode($result);
				exit(0);
			}			
		}
	}

    public function get_call_comment_view_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$list=array();
			$cid=$this->input->post('cid');
			$sid=$this->input->post('sid');
			$tid=$this->input->post('tid');
			$title=$this->input->post('title');
			$scid=$this->input->post('scid');
			if($cid!='')
			{	
				$list['client_row']=$this->Adminportal_model->get_service_call_details($cid,$sid);
				$list['update_type']=$this->Adminportal_model->get_update_type();
				$list['comment_list']=$this->Client_model->get_comment_list($cid);	
				$list['client_id']=$cid;
				$list['service_id']=$sid;
				$list['call_type_id']=$tid;
				$list['call_type_name']=$title;
				$list['service_call_id']=$scid;
				$html=$this->load->view('adminmaster/get_call_comment_view_popup_ajax',$list,TRUE);					
				$status_str='success';
				$result["status"] = $status_str;	
				$result["html"] = $html;	  
				echo json_encode($result);
				exit(0);
			}			
		}
	}

    public function save_call_comment_update_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$client_id=$this->input->post('client_id');
			$client_update_type_id=$this->input->post('update_type_id');
			$call_done=$this->input->post('call_done');
			$service_id=$this->input->post('service_id');			
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
						$this->Adminportal_model->service_call_update($update_data,$service_call_id);

						if($call_type_id==1) {
							$next_call_type_id=2;
							$scheduled_call_datetime=date('Y-m-d H:i:s',strtotime("+3 day", strtotime($today)));
						} elseif($call_type_id==2) {
							$next_call_type_id=3;
							$scheduled_call_datetime=date('Y-m-d H:i:s',strtotime("+7 day", strtotime($today)));
						} elseif($call_type_id==3) {
							$next_call_type_id=4;
							$scheduled_call_datetime=date('Y-m-d H:i:s',strtotime("+15 day", strtotime($today)));
						} elseif($call_type_id==4) {
							$next_call_type_id=4;
							$scheduled_call_datetime=date('Y-m-d H:i:s',strtotime("+15 day", strtotime($today)));
						}

						if($call_type_id!=7){
							$insert_data=array(
								'client_id'=>$client_id,
								'service_id'=>$service_id,
								'service_call_type_id'=>$next_call_type_id,
								'call_by_user_id'=>$user_id,
								'scheduled_call_datetime'=>$scheduled_call_datetime,
								'comment'=>$activity_text,
								'form_data'=>json_encode($form_data),
								'created_at'=>$today
							);
							$this->Adminportal_model->insert_call_update($insert_data);
						}

						$followup_date = $scheduled_call_datetime;
                        $call_type_id = $next_call_type_id;
						
					} else {
						$update_data=array(
							'call_by_user_id'=>$user_id,
							'scheduled_call_datetime'=>$followup_date
						);
						$this->Adminportal_model->service_call_update($update_data,$service_call_id);
					}
					
					$status_str='success';


					if($call_type_id==1 || $call_type_id==2 || $call_type_id==3){
						$update_data=array(
							'service_call_type_id'=>$call_type_id,
							'next_followup_date'=>$followup_date
						);
						$this->Adminportal_model->service_update($update_data,$service_id);
					}



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

	public function update_service_call_list()
	{
		$rows=$this->Adminportal_model->update_service_call_list();
		echo date('Y-m-d H:i:s',strtotime("+0 day", strtotime('2022-10-26')));
		echo'<pre>';
			print_r($rows);
		echo'</pre>';
	}


	
}