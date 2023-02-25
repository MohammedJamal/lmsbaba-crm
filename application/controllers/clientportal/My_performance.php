<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class My_performance extends CI_Controller {	
	private $api_access_token = '';
	function __construct()
	{
		parent :: __construct();
		is_admin_logged_in();
		init_admin_element();         
		$this->load->model(array("Setting_model","My_performance_model","user_model"));
		// permission checking
		// if(is_permission_available($this->session->userdata('service_wise_menu')[8]['menu_list']['menu_keyword'])===false){ 
		// 	redirect(admin_url().'dashboard', 'refresh');
		// 	exit(0);
		// }
		// end
	}

	public function index()
    {		
		$data['error_msg'] 			= "";
		$data['page_title'] 		= "My Performance";
		$data['page_keyword'] 		= "My Performance";
		$data['page_description']   = "My Performance"; 	
		$user_id=$this->session->userdata['admin_session_data']['user_id'];	
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
		// array_push($tmp_u_ids, $user_id);	
		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		// if($user_id==1){
		// 	$tmp_u_ids_str='';
		// }	
		$company_info=$this->Setting_model->GetCompanyData();    	 	
		$data['company']=$company_info;  
		$data['user_id']=$user_id;   
		$data['user_list']=$this->user_model->GetUserListAll('');
		$data['mps_kpi_user_list']=	$this->Setting_model->only_set_kpi_user_list();
		$data['ps_kpi_user_list']=	$this->Setting_model->only_report_wise_user_list($tmp_u_ids_str);
		$data['months']=get_months();
		$this->load->view('admin/my_performance/my_performance_view',$data);	
    }

	function rander_activity_log_list_ajax()
	{
		$user_id=$this->session->userdata['admin_session_data']['user_id'];	
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		
		
		$al_user=($this->input->get('al_user'))?$this->input->get('al_user'):$tmp_u_ids_str;
		$al_date_from=$this->input->get('al_date_from');
		$al_date_to=$this->input->get('al_date_to');

		if(($al_date_from=='' && $al_date_to=='') || ($al_date_from=='' || $al_date_to==''))
		{
			$al_date_from=date('Y-m-d', strtotime('-6 days'));
			$al_date_to=date("Y-m-d");
		}

		if($al_date_from!='' && $al_date_to!='')
		{
			$al_date_from=date_display_format_to_db_format($al_date_from);
			$al_date_to=date_display_format_to_db_format($al_date_to);
		}

		$argument_arr=array(
							'user_id'=>$al_user,
							'date_from'=>$al_date_from,
							'date_to'=>$al_date_to
							);		
							
		$list['rows']=$this->My_performance_model->GetActivityList($argument_arr);
	    $table = '';	    
	    $table = $this->load->view('admin/my_performance/activity_log_list_ajax',$list,TRUE);		
	    $data =array (
	       "table"=>$table
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	    exit;
	}

	function rander_user_wise_activity_log_breakup_list_ajax()
	{
		$user_id=$this->session->userdata['admin_session_data']['user_id'];		
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);

		
		$user_id_str=($this->input->get('user_id'))?$this->input->get('user_id'):$tmp_u_ids_str;
		$date=$this->input->get('date');		

		$argument_arr=array(
							'user_id'=>$user_id_str,
							'date'=>$date
							);		
							
		$list['rows']=$this->My_performance_model->GetUserWiseActivityList($argument_arr);
	    $html = '';	    
	    $html = $this->load->view('admin/my_performance/user_wise_activity_log_list_ajax',$list,TRUE);		
	    $data =array (
	       "html"=>$html
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	    exit;
	}

	function rander_location_tracking_ajax()
	{
		$user_id=$this->session->userdata['admin_session_data']['user_id'];		
		$lt_user=($this->input->get('lt_user'))?$this->input->get('lt_user'):$user_id;
		$lt_date_from=$this->input->get('lt_date_from');
		$lt_date_to=$this->input->get('lt_date_to');

		if(($lt_date_from=='' && $lt_date_to=='') || ($lt_date_from=='' || $lt_date_to==''))
		{
			$lt_date_from=date('Y-m-d', strtotime('-6 days'));
			$lt_date_to=date("Y-m-d");
		}

		if($lt_date_from!='' && $lt_date_to!='')
		{
			$lt_date_from=date_display_format_to_db_format($lt_date_from);
			$lt_date_to=date_display_format_to_db_format($lt_date_to);
		}

		$argument_arr=array(
							'user_id'=>$lt_user,
							'date_from'=>$lt_date_from,
							'date_to'=>$lt_date_to
							);		
							
		$list['rows']=$this->My_performance_model->GetLocationTracking($argument_arr);
		$company_info=$this->Setting_model->GetCompanyData();    	 	
		$list['company']=$company_info;
	    $html = '';	    
	    $html = $this->load->view('admin/my_performance/location_tracking_ajax',$list,TRUE);		
	    $data =array (
	       "html"=>$html
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	    exit;
	}

	function rander_my_performance_scorecard_ajax()
	{
		$my_user_id=$this->session->userdata['admin_session_data']['user_id'];	
		// $tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($my_user_id,0);
   		// array_push($tmp_u_ids, $my_user_id);
		// $tmp_u_ids_str=implode(",", $tmp_u_ids);

		$user_id=$this->input->get('user_id');
		$kpi_setting_id=$this->input->get('kpi_setting_id');

		$list['get_kpi_setting']=$this->Setting_model->get_kpi_setting($kpi_setting_id);
		$list['kpi_setting_id']=$kpi_setting_id;
		$kpi_setting_user_wise_row=$this->Setting_model->kpi_setting_user_wise_by_sid_and_user_id($kpi_setting_id,$user_id);
		$kpi_setting_user_wise_set_target_list=array();
		if(count($kpi_setting_user_wise_row)){
			$kpi_setting_user_wise_set_target_list=$this->Setting_model->kpi_setting_user_wise_set_target_by_kpi_setting_user_wise_id($kpi_setting_user_wise_row['id']);
		}
		$list['kpi_setting_user_wise_set_target_list']=$kpi_setting_user_wise_set_target_list;
		$list['user_info']=$this->user_model->sms_user_row($user_id);
		$list['kpi_setting_user_wise_row']=$kpi_setting_user_wise_row;
		$list['user_id']=$my_user_id;
		
		$list['report_logs']=$this->My_performance_model->kpi_user_wise_report_logs($my_user_id,'','');
		$list['kpi_definitions']=$this->My_performance_model->kpi_definitions();
	    $html = '';	    
	    $html = $this->load->view('admin/my_performance/my_performance_scorecard_ajax',$list,TRUE);		
	    $data =array (
	       "html"=>$html
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	    exit;
	}	

	function target_achieved_save_ajax()
	{
		$id=$this->input->get('id');
		$target_achieved=$this->input->get('target_achieved');
		$post_data=array(
						'achieved'=>$target_achieved,
						'updated_at'=>date("Y-m-d H:i:s")
						);									
		$return=$this->My_performance_model->target_achieved_update($post_data,$id);
		if($return==true){
			$status='success';
		}
		else{
			$status='fail';
		}
	    $data =array (
	       "status"=>$status
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	    exit;
	}

	function report_detail_view_ajax()
	{
		$user_id=$this->session->userdata['admin_session_data']['user_id'];		
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		$kpi_user_wise_report_log_id=$this->input->get('id');
		$listing_from=$this->input->get('listing_from');	

		$list=array();
		$list['user_id']=$user_id;
		$list['listing_from']=$listing_from;
		$list['report']=$this->My_performance_model->kpi_user_wise_report_logs_by_id($kpi_user_wise_report_log_id);
		$list['rows']=$this->My_performance_model->kpi_user_wise_report_log_details($kpi_user_wise_report_log_id);
		$list['user_info']=$this->user_model->sms_user_row($list['report']['user_id']);
	    $html = '';	    
	    $html = $this->load->view('admin/my_performance/report_detail_view_ajax',$list,TRUE);		
	    $data =array (
	       "html"=>$html
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	    exit;
	}

	function update_grace_score_in_report_log_ajax()
	{
		
		$kpi_user_wise_report_log_id=$this->input->get('kpi_user_wise_report_log_id');	
		$grace_score=$this->input->get('grace_score');	
		
		$post=array('grace_score'=>$grace_score);
		$this->My_performance_model->update_kpi_user_wise_report_log($post,$kpi_user_wise_report_log_id);

		$report=$this->My_performance_model->kpi_user_wise_report_logs_by_id($kpi_user_wise_report_log_id);
		$total_score=($report['total_score_obtained_after_min_threshold_apply']+$report['grace_score']);

		$pli=0;
		$pli=($report['pli_in']/100)*$report['monthly_salary'];
		$pli_earned=($pli/100)*($report['total_score_obtained_after_min_threshold_apply']+$report['grace_score']);

	    $data =array (
	       "status"=>'success',
		   'total_score'=>ceil($total_score),
		   'pli_earned'=>number_format($pli_earned,2)
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	    exit;
	}

	function approve_kpi_user_wise_report_log()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('comment_on_approved', 'Your Comment', 'required');

		if($this->form_validation->run() == TRUE)
		{
			$user_id=$this->session->userdata['admin_session_data']['user_id'];		
			$kpi_user_wise_report_log_id=$this->input->post('kpi_user_wise_report_log_id');
			$comment_on_approved=$this->input->post('comment_on_approved');
			$post=array(
						'is_self_approved'=>'Y',
						'self_approved_datetime'=>date("Y-m-d H:i:s"),
						'comment_on_approved'=>$comment_on_approved,
						'is_approved'=>'Y',
						'approved_datetime'=>date("Y-m-d H:i:s"),
						'approved_by'=>$user_id
					);
			$this->My_performance_model->update_kpi_user_wise_report_log($post,$kpi_user_wise_report_log_id);

			$report=$this->My_performance_model->kpi_user_wise_report_logs_by_id($kpi_user_wise_report_log_id);
			$approved_html='Approved by '.$report['approved_by_name'].' on '.datetime_db_format_to_display_format_ampm($report['approved_datetime']);
			

			$data =array (
						"status"=>'success',
						"approved_html"=>$approved_html,
						"msg"=>'You have saved the comment and confirmed approval also.'
						);	   				
			$this->output->set_content_type('application/json');
			echo json_encode($data); 
			exit;
		}
		else
		{
			/*$msg = validation_errors(); // 'duplicate';
			$msg=str_replace('<p>', "", $msg);
			$msg=str_replace('</p>', "", $msg);*/
			echo json_encode(['status'=>'error','msg'=>'All * fields are required']);exit;
            //$this->session->set_flashdata('error_msg', $msg);
		}	
		
		
	}

	function report_target_achieved_save_ajax()
	{
		$id=$this->input->get('id');
		$kpi_user_wise_report_log_id=$this->input->get('kpi_user_wise_report_log_id');
		$target_achieved=$this->input->get('target_achieved');
		$return_detail=$this->My_performance_model->kpi_user_wise_report_log_detail_row($id);		
		$score_obtained=($return_detail['weighted_score']*($target_achieved/$return_detail['target']*100)/100);

		$post_data=array(
						'achieved'=>$target_achieved,
						'score_obtained'=>$score_obtained
						);						
		$return=$this->My_performance_model->update_kpi_user_wise_report_log_detail($post_data,$id);

		// ----------------------------------------------------------------------------------------
		$report_detail_rows=$this->My_performance_model->kpi_user_wise_report_log_details($kpi_user_wise_report_log_id);
		
		$total_score_obtained=0;
		if(count($report_detail_rows))
		{
			foreach($report_detail_rows AS $row)
			{
				$total_score_obtained=($total_score_obtained+$row['score_obtained']);
			}
		}

		$post_data=array(
			'total_score_obtained'=>$total_score_obtained
			);
		$this->My_performance_model->update_kpi_user_wise_report_log($post_data,$kpi_user_wise_report_log_id);
		// ----------------------------------------------------------------------------------------
		if($return==true){
			$status='success';
		}
		else{
			$status='fail';
		}
	    $data =array (
	       "status"=>$status
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	    exit;
	}

	public function self_approve_kpi_user_wise_report_log()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('self_comment', 'Comment', 'required');
		
		if ($this->form_validation->run() == TRUE)
		{	
			
			$kpi_user_wise_report_log_id=$this->input->post('kpi_user_wise_report_log_id');
			$self_comment=$this->input->post('self_comment');
			
			$post_data=array(
							'self_comment'=>$self_comment,
							'is_self_approved'=>'Y',
							'self_approved_datetime'=>date("Y-m-d H:i:s")
							);			
			$this->My_performance_model->update_kpi_user_wise_report_log($post_data,$kpi_user_wise_report_log_id);
				
			$attach_filename='';
			$this->load->library('upload', '');
			if($_FILES['self_file_name']['name'] != "")
			{
				$config['upload_path'] = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/my_documents";
				$config['allowed_types'] = "gif|jpg|jpeg|png|pdf|doc|docx|txt|xlsx";
				$config['max_size'] = '1000000'; //KB
				$config['overwrite'] = FALSE; 
				$config['encrypt_name'] = TRUE; 
				$this->upload->initialize($config);
				if (!$this->upload->do_upload('self_file_name'))
				{
					//return $this->upload->display_errors();
					echo json_encode(['status'=>'error','msg'=>$this->upload->display_errors()]);
					exit(0);
				}
				else
				{
					$file_data = array('upload_data' => $this->upload->data());
					$attach_filename = $file_data['upload_data']['file_name'];	
					$post_data=array(
						'self_file_name'=>$attach_filename
						);								
					$this->My_performance_model->update_kpi_user_wise_report_log($post_data,$kpi_user_wise_report_log_id);		    
				}
			}			
			echo json_encode(['status'=>'','msg'=>'You have saved the record and confirmed self approval also.']);exit(0);
		}
		else
		{
			/*$msg = validation_errors(); // 'duplicate';
			$msg=str_replace('<p>', "", $msg);
			$msg=str_replace('</p>', "", $msg);*/
			echo json_encode(['status'=>'error','msg'=>'All * fields are required']);exit;
            //$this->session->set_flashdata('error_msg', $msg);
		}			
	}

	function rander_performance_scorecard_ajax()
	{
		$my_user_id=$this->session->userdata['admin_session_data']['user_id'];	
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($my_user_id,0);
   		// array_push($tmp_u_ids, $my_user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);

		$y_month=$this->input->get('month');
		$user_id=$this->input->get('user_id');
		$kpi_setting_id=$this->input->get('kpi_setting_id');

		$list['get_kpi_setting']=$this->Setting_model->get_kpi_setting($kpi_setting_id);
		$list['kpi_setting_id']=$kpi_setting_id;
		$kpi_setting_user_wise_row=$this->Setting_model->kpi_setting_user_wise_by_sid_and_user_id($kpi_setting_id,$user_id);
		$kpi_setting_user_wise_set_target_list=array();
		if(count($kpi_setting_user_wise_row)){
			$kpi_setting_user_wise_set_target_list=$this->Setting_model->kpi_setting_user_wise_set_target_by_kpi_setting_user_wise_id($kpi_setting_user_wise_row['id']);
		}
		$list['kpi_setting_user_wise_set_target_list']=$kpi_setting_user_wise_set_target_list;
		$list['user_info']=$this->user_model->sms_user_row($user_id);
		$list['kpi_setting_user_wise_row']=$kpi_setting_user_wise_row;
		$list['user_id']=$user_id;
		
		$list['report_logs']=$this->My_performance_model->kpi_user_wise_report_logs($user_id,$y_month,$tmp_u_ids_str);
		$list['kpi_definitions']=$this->My_performance_model->kpi_definitions();
	    $html = '';	    
	    $html = $this->load->view('admin/my_performance/performance_scorecard_ajax',$list,TRUE);		
	    $data =array (
	       "html"=>$html
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	    exit;
	}

	public function download($file='')
	{	
		if($file!='')
		{	
			$this->load->helper(array('download'));	
			$file_name = base64_decode($file);
			$pth    =   file_get_contents("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/my_documents/".$file_name);
			force_download($file_name, $pth); 
			exit;
		}
	}
}