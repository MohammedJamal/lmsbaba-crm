<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Order_management extends CI_Controller {	
	private $api_access_token = '';
	function __construct()
	{
		parent :: __construct();
		is_admin_logged_in();
		init_admin_element();         
		$this->load->model(array("Setting_model","user_model","Order_management_model","order_model"));
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
		$this->load->view('admin/order_management/order_management_view',$data);	
    }

	function rander_settings_ajax()
	{
		$user_id=$this->session->userdata['admin_session_data']['user_id'];	
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		$list=array();
	    $html = '';	    
	    $html = $this->load->view('admin/order_management/setting_view_ajax',$list,TRUE);		
	    $data =array (
	       "html"=>$html
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	    exit;
	}

	function rander_orders_ajax()
	{
		$start = $this->input->get('page');
	    $view_type = $this->input->get('view_type');
		// ------------------------------------------------------------------------
		$untagged_pi_list=$this->Order_management_model->GetUnTaggedPiList();		
		if(count($untagged_pi_list)){
			foreach($untagged_pi_list AS $val){			
				
				$post_data=array(
						"stage_id"=>'1',
						"po_pi_id"=>$val['id'],
						"updated_by"=>$val['l_assigned_user_id'],
						"created_at"=>date("Y-m-d H:i:s"),
						"updated_at"=>date("Y-m-d H:i:s")
				);
				$r=$this->Order_management_model->add_pi_stage_tag($post_data);
				if($r){
					$post_data_log=array(
						"om_po_pi_wise_stage_tag_id"=>$r,
						"stage_id"=>'1',
						"po_pi_id"=>$val['id'],
						"priority"=>'1',
						"updated_by"=>$val['l_assigned_user_id'],
						"created_at"=>date("Y-m-d H:i:s")
						);
					$this->Order_management_model->add_pi_stage_tag_log($post_data_log);
				}				
			}
		}
		// ------------------------------------------------------------------------
		

		if($view_type=='om_list')
		{
			$list['page']=$start;
			$list['view_type']=$view_type;
			$html = '';	    
			$html = $this->load->view('admin/order_management/order_list_view_ajax',$list,TRUE);		
			$data =array ("html"=>$html);
		}
		else if($view_type=='om_grid')
		{
			$user_id=$this->session->userdata['admin_session_data']['user_id'];	
			$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
			array_push($tmp_u_ids, $user_id);
			$tmp_u_ids_str=implode(",", $tmp_u_ids);
			$list=array();
			$list['active_stage_list']=$this->Order_management_model->GetActiveStageList();
			$list['pi_wise_stage_list']=$this->Order_management_model->GetTaggedPiWiseStageList();
			// $list['tagged_pi_list']=$this->Order_management_model->GetTaggedPiList();
			$list['stage_wise_assigned_users']=$this->Order_management_model->get_stage_wise_assigned_user();
			$list['user_id']=$user_id;
			
			$html = '';	    
			$html = $this->load->view('admin/order_management/order_grid_view_ajax',$list,TRUE);		
			$data =array ("html"=>$html);
		}
			   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	    exit;
	}

	function rander_orders_stage_wise_ajax()
	{
		$page=$this->input->get('page');
		$start = $this->input->get('page');
	    $view_type = $this->input->get('view_type');
		$stage_id = $this->input->get('sid');

		
	    $limit=30;	    
	    $list=array();
	    $arg=array();
		$arg['stage_id']=$stage_id;
		$user_id=$this->session->userdata['admin_session_data']['user_id'];	
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);	   
	    $config['total_rows'] = $this->Order_management_model->GetTaggedPiListPagination_count($arg);	    
	    $start=empty($start)?0:($start-1)*$limit;
	    $arg['limit']=$limit;
	    $arg['start']=$start;
		$list=array();		
		$list['rows']=$this->Order_management_model->GetTaggedPiListPagination($arg);			
		$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
		$list['stage_wise_assigned_users']=$this->Order_management_model->get_stage_wise_assigned_user();
		$list['user_id']=$user_id;
		$list['stage_id']=$stage_id;		
		$list['active_stage_list']=$this->Order_management_model->GetActiveStageList();
		$list['is_priority_link_available']=is_om_link_available('om_priority');
		$list['is_split_link_available']=is_om_link_available('om_split');
		$list['is_lock_unloc_link_available']=is_om_link_available('om_lock_unlock');

		$html = '';	    
		$html = $this->load->view('admin/order_management/order_grid_view_stage_wise_ajax',$list,TRUE);	
		$last_page_count=$page;		
		if($tmp_limit<$config['total_rows']){
			$page_tmp=(($page==0)?1:$page)+1;
			$is_pagination_active='Y';			
		}
		else{
			$page_tmp=$last_page_count;
			$is_pagination_active='N';
		}	
		$data =array ("html"=>$html,"page_number"=>$page_tmp,"is_pagination_active"=>$is_pagination_active,"msg"=>''.$page_tmp);
			   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	    exit;
	}

	function rander_orders_list_ajax()
	{
		$start = $this->input->get('page');		
	    $view_type = $this->input->get('view_type');
		$stage_id = $this->input->get('stage_id');
		$filter_string_search = $this->input->get('filter_string_search');
		$this->load->library('pagination');
	    $limit=30;
	    $config = array();
	    $list=array();
	    $arg=array();
		$arg['stage_id']=$stage_id;
		$arg['string_search']=$filter_string_search;
		$user_id=$this->session->userdata['admin_session_data']['user_id'];	
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);

		//$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->Order_management_model->GetTaggedPiListPagination_count($arg);
	    // $config['total_rows'] =30;
	    $config['per_page'] = $limit;
	    $config['uri_segment']=4;
	    $config['num_links'] = 1;
	    $config['use_page_numbers'] = TRUE;
	   //$config['full_tag_open'] = '<div id="paginator" class="dataTables_paginate paging_bootstrap">';
	    $config['attributes'] = array('class' => 'om_myclass','data-viewtype'=>$view_type);
	   //$config['full_tag_close'] = '</div>';
	   //$config['prev_link'] = '&lt;Previous';
	   //$config['next_link'] = 'Next&gt;';
	    
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

	    $this->pagination->initialize($config);
	    $page_link = '';
	    $page_link = $this->pagination->create_links();
	    $start=empty($start)?0:($start-1)*$limit;
	    $arg['limit']=$limit;
	    $arg['start']=$start;
		$list=array();		
		$list['rows']=$this->Order_management_model->GetTaggedPiListPagination($arg);
		// PAGINATION COUNT INFO SHOW: START
		$tmp_start=($start+1);		
		$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
	    $page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
		// PAGINATION COUNT INFO SHOW: END
		$list['stage_wise_assigned_users']=$this->Order_management_model->get_stage_wise_assigned_user();
		$list['is_priority_link_available']=is_om_link_available('om_priority');
		$list['user_id']=$user_id;
		$list['stage_id']=$stage_id;
		$html = '';	    
		$html = $this->load->view('admin/order_management/order_list_ch_view_ajax',$list,TRUE);		
		$data =array (
			"html"=>$html,
			"page"=>$page_link,
			"page_record_count_info"=>$page_record_count_info
			);
			   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	    exit;
	}

	function rander_om_stage_list_ajax()
	{
		$list['rows']=$this->Order_management_model->GetActiveStageList();
	    $table = '';	    
	    $table = $this->load->view('admin/order_management/om_stage_list_view_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function resort_om_stage()
    {
		$new_sort=$this->input->get('new_sort');
		$i = 1;
		foreach ($new_sort as $edit_id) 
		{
			$post_data=array(
				"sort"=>$i			
				);
			$this->Order_management_model->UpdateStage($post_data,$edit_id);
		    $i++;
		}		
		$status_str='success';		   	 
        $result["status"] = $status_str;
        echo json_encode($result);
        exit(0); 
    }

	function add_om_stage_setting()
    {
		$om_stage_name = $this->input->get('om_stage_name'); 
		$om_stage_position = $this->input->get('om_stage_position');
		$om_stage_id_as_per_position = $this->input->get('om_stage_id_as_per_position');
		
		if($om_stage_name!='' && $om_stage_position!='' && $om_stage_id_as_per_position!='')
		{
			$get_row=$this->Order_management_model->GetStage($om_stage_id_as_per_position);
			
			$get_sort_wise_list=$this->Order_management_model->GetStageSortWise($get_row->sort);
			
			$new_sort=($om_stage_position==1)?($get_row->sort+1):($get_row->sort);
			$sort_tmp=($om_stage_position==1)?($new_sort):($get_row->sort);
			$new_sort_wise_list=$this->Order_management_model->GetStageSortWise($sort_tmp);
			
			if(count($new_sort_wise_list))
			{
				foreach($new_sort_wise_list AS $row){
					$post_data_tmp=array(
						"sort"=>($row['sort']+1)
						);
					$this->Order_management_model->UpdateStage($post_data_tmp,$row['id']);
				}
			}
			$post_data=array(
						"name"=>$om_stage_name,
						"sort"=>$new_sort,
						"is_system_generated"=>'N',
						"is_active"=>'Y'
						);
			$this->Order_management_model->AddStage($post_data);
			$status_str='success';
		}	
		else
		{
			$status_str='fail'; 
		}    	 
        $result["status"] = $status_str;       
        $result["html"]='';
        echo json_encode($result);
        exit(0); 
    }

	function edit_stage_setting()
    {
		$edit_id = $this->input->get('edit_id'); 
		$stage = $this->input->get('stage');
		
		if($edit_id!='' && $stage!='')
		{
			$post_data=array(
				"name"=>$stage			
				);
			$this->Order_management_model->UpdateStage($post_data,$edit_id);

			$status_str='success';
		}	
		else
		{
			$status_str='fail'; 
		}

    	 
        $result["status"] = $status_str;       
        $result["html"]='';
        echo json_encode($result);
        exit(0); 
    }

	function delete_stage()
	{
		$msg="";
		$status_str="";
		$edit_id=$this->input->get('id');
		$isStageExistInTag=$this->Order_management_model->isStageExistInTag($edit_id);
		if($isStageExistInTag=='N'){
			$post_data=array(
				"is_deleted"=>'Y'			
				);
			$return=$this->Order_management_model->UpdateStage($post_data,$edit_id);			
			if($return==true){
				$status_str = "success";
			}
			else{
				$status_str = "fail";
			}	
		}
		else{
			$status_str = "fail";
			$msg="Some order(s) already exist in the stage. Please remove all order from the stage to deleted the stage.";
		}
		
		
		$result["status"] = $status_str;
		$result["msg"] = $msg;  
        echo json_encode($result);
		exit(0);
	}

	function rander_om_stage_wise_user_assign_ajax()
	{
		$list['rows']=$this->Order_management_model->GetActiveStageWiseAssignUserList();
	    $table = '';	    
	    $table = $this->load->view('admin/order_management/om_stage_wise_user_assign_list_view_ajax.php',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	public function tag_assign_user_to_stage_view_rander_ajax()
	{
		$data=NULL;
		$stage_id=$this->input->post('stage_id');			
		$data['stage_id']=$stage_id;
		$all_Service_tagged_user_ids=$this->user_model->GetAllUsersIdsThoseTaggedInService(3);		
		$my_array=explode(",",$all_Service_tagged_user_ids);
		$already_assigned_user_ids=$this->Order_management_model->GetAlreadyAssignedUserIdsByTheStage($stage_id);		
		$to_remove=explode(",",$already_assigned_user_ids);		
		$user_array = array_diff($my_array, $to_remove);
		$user_ids=implode(",",$user_array);		
		$data['user_list']=$this->Order_management_model->GetUsersByIds($user_ids);
		$this->load->view('admin/order_management/tag_user_to_stage_view_modal_ajax',$data);
	}	
	function tag_assign_user_to_stage_update()
	{
		$stage_id=$this->input->get('stage_id');
		$stage_user_id=$this->input->get('stage_user_id');
		if($stage_user_id!='' && $stage_id!=''){
			$stage_user_id_arr=explode(",",$stage_user_id);
			if(count($stage_user_id_arr)){
				foreach($stage_user_id_arr AS $val){
					$post_data=array(
						"stage_id"=>$stage_id,
						"user_id"=>$val,
						"created_at"=>date("Y-m-d H:i:s")
						);
					$this->Order_management_model->add_stage_wise_assigned_user($post_data);
				}
			}
		}
		$return['status'] = 'success';
		echo json_encode($return);
		exit(0);
	}
	public function untag_assign_user_to_stage_view_rander_ajax()
	{
		$data=NULL;
		$stage_id=$this->input->post('stage_id');			
		$data['stage_id']=$stage_id;
		$already_assigned_user_ids=$this->Order_management_model->GetAlreadyAssignedUserIdsByTheStage($stage_id);
		$data['user_list']=$this->Order_management_model->GetAssignedUserIdsByTheStage($already_assigned_user_ids);
		$this->load->view('admin/order_management/untag_user_to_stage_view_modal_ajax',$data);
	}

	function untag_assign_user_to_stage_update()
	{
		$stage_id=$this->input->get('stage_id');
		$user_id=$this->input->get('user_id');
		if($user_id!='' && $stage_id!=''){
			$this->Order_management_model->delete_stage_wise_assigned_user_by_stageIdAndUserId($stage_id,$user_id);
		}
		$return['status'] = 'success';
		echo json_encode($return);
		exit(0);
	}

	function pi_stage_change_update()
	{
		$user_id=$this->session->userdata['admin_session_data']['user_id'];	
		$pi_id=$this->input->get('pi_id');
		$pfi_split_id=$this->input->get('pfi_split_id');
		$current_stage_id=$this->input->get('current_stage_id');
		$prev_stage_id=$this->input->get('prev_stage_id');

		
		if($current_stage_id!=$prev_stage_id){
			if($pi_id!='' && $current_stage_id>0){
				// $this->Order_management_model->delete_existing_pi_stage($pi_id,$prev_stage_id);
				
				$get_id=$this->Order_management_model->is_existing_pi_stage($pi_id,$pfi_split_id);
				if($get_id>0)
				{
					$post_data=array(
						"stage_id"=>$current_stage_id,
						"updated_by"=>$user_id,
						"updated_at"=>date("Y-m-d H:i:s")
						);
					$last_inserted_id=$this->Order_management_model->update_pi_stage_tag($post_data,$get_id);
					$last_inserted_id=$get_id;
				}
				else{
					$post_data=array(
						"stage_id"=>$current_stage_id,
						"po_pi_id"=>$pi_id,
						"po_pi_split_id"=>$pfi_split_id,
						"updated_by"=>$user_id,
						"created_at"=>date("Y-m-d H:i:s")
						);
					$last_inserted_id=$this->Order_management_model->add_pi_stage_tag($post_data);
				}
				
				if($last_inserted_id!=false){
					$priority=$this->Order_management_model->GetExistingPiPriority($last_inserted_id);
					$post_data_log=array(
						"om_po_pi_wise_stage_tag_id"=>$last_inserted_id,
						"stage_id"=>$current_stage_id,
						"po_pi_id"=>$pi_id,
						"po_pi_split_id"=>$pfi_split_id,
						"priority"=>$priority,
						"updated_by"=>$user_id,
						"created_at"=>date("Y-m-d H:i:s")
						);
					$this->Order_management_model->add_pi_stage_tag_log($post_data_log);
	
					
					// =========================
					// HISTORY CREATE
					$current_stage=get_value("name","om_stage","id=".$current_stage_id);
					$prev_stage=get_value("name","om_stage","id=".$prev_stage_id);
					// $pi_id=get_value("po_pi_id","om_po_pi_wise_stage_tag","id=".$po_pi_wise_stage_tag_id);
					$h_data=$this->Order_management_model->GetDataForHistoryByPi($pi_id);
					$update_by=$this->session->userdata['admin_session_data']['user_id'];
					$ip_addr=$_SERVER['REMOTE_ADDR'];				
					$message="Stage has been changed from ".$prev_stage." to ".$current_stage." on ".datetime_db_format_to_display_format_ampm(date('Y-m-d H:i:s'));
					$comment_title=OM_STAGE_CHANGE;
					$historydata=array(
								'lead_id'=>$h_data['lead_id'],
								'lead_opportunity_id'=>$h_data['lead_opportunity_id'],
								'lowp'=>$h_data['lowp'],
								'source_id'=>$h_data['source_id'],
								'po_pi_id'=>$h_data['po_pi_id'],
								'title'=>$comment_title,						
								'comment'=>addslashes($message),
								'updated_by'=>$update_by,
								'created_at'=>date("Y-m-d H:i:s"),						
								'ip_address'=>$ip_addr
								);
					$this->Order_management_model->CreateHistory($historydata);
					// HISTORY CREATE
					// =========================
				}
			}			
		}
		$return['status'] = 'success';
		echo json_encode($return);
		exit(0);
		
	}

	function rander_om_stage_form_list_ajax()
	{
		$list['rows']=$this->Order_management_model->GetStageFormList();
	    $table = '';	    
	    $table = $this->load->view('admin/order_management/om_stage_form_list_view_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}
	function add_om_stage_form_setting()
    {
		$om_form_name = $this->input->get('om_form_name'); 	
		$om_form_is_mandatory = strtoupper($this->input->get('om_form_is_mandatory')); 	
		
		if($om_form_name!='')
		{
			$post_data=array(
						"name"=>$om_form_name,
						"is_mandatory"=>$om_form_is_mandatory,
						"is_active"=>'Y',
						"is_deleted"=>'N'
						);
			$this->Order_management_model->AddStageForm($post_data);
			$status_str='success';
		}	
		else
		{
			$status_str='fail'; 
		}    	 
        $result["status"] = $status_str;       
        $result["html"]='';
        echo json_encode($result);
        exit(0); 
    }
	function edit_stage_form_setting()
    {
		$edit_id = $this->input->get('edit_id'); 
		$form_name = $this->input->get('form_name');
		
		if($edit_id!='' && $form_name!='')
		{
			$post_data=array(
				"name"=>$form_name			
				);
			$this->Order_management_model->UpdateStageForm($post_data,$edit_id);

			$status_str='success';
		}	
		else
		{
			$status_str='fail'; 
		}

    	 
        $result["status"] = $status_str;       
        $result["html"]='';
        echo json_encode($result);
        exit(0); 
    }

	function delete_stage_form()
	{
		$edit_id=$this->input->get('id');
		$post_data=array(
				"is_deleted"=>'Y'			
				);
		$return=$this->Order_management_model->UpdateStageForm($post_data,$edit_id);
		if($return==true){
			$status_str = "success";
		}
		else{
			$status_str = "fail";
		}	
		
		$result["status"] = $status_str;  
        echo json_encode($result);
		exit(0);
	}
	public function stage_form_field_set_popup_view_rander_ajax()
	{
		$data=NULL;
		$id=$this->input->post('id');			
		$data['form_id']=$id;		
		$data['form_row']=$this->Order_management_model->GetStageFormRow($id);
		$data['rows']=$this->Order_management_model->GetStageFormFieldsList($id);
		$html=$this->load->view('admin/order_management/stage_form_field_set_view_modal_ajax',$data,true);
		$result["popup_title"] = 'Stage Form Set for - '.$data['form_row']['name'];  
		$result["html"] = $html;  
        echo json_encode($result);
		exit(0);
	}	

	function add_om_stage_form_fields_setting()
    {
		$form_id = $this->input->post('form_id'); 
		$om_form_input_type = $this->input->post('om_form_input_type'); 	
		$om_form_label = $this->input->post('om_form_label'); 	
		$om_form_is_mandatory = $this->input->post('om_form_is_mandatory');
		// $option_key = $this->input->post('option_key');
		$option_key = $this->input->post('option_value');
		$option_value = $this->input->post('option_value'); 	
		$option_k_v_arr=array();
		if($form_id==''){
			$result["status"] = 'fail';  
			$result["msg"]='Unknown Error.';
			echo json_encode($result);
			exit(0); 
		}

		if($om_form_input_type==''){
			$result["status"] = 'fail';  
			$result["msg"]='Input Type should not be blank.';
			echo json_encode($result);
			exit(0); 
		}
		else{
			if($om_form_input_type=='R' || $om_form_input_type=='S' || $om_form_input_type=='CB'){
				$flag=0;				
				if(count($option_key)){
					foreach($option_key AS $tmp_v){
						if($tmp_v==''){							
							$flag++;
							break;
						}
					}
				}

				if(count($option_value)){
					foreach($option_value AS $tmp_v){
						if($tmp_v==''){							
							$flag++;
							break;
						}
					}
				}
				
				if($flag>0){
					$result["status"] = 'fail';  
					$result["msg"]='Value should not be blank.';
					echo json_encode($result);
					exit(0); 
				}
				else{
					if(count($option_key)){
						$i=0;
						foreach($option_key AS $tmp_v){
							$option_k_v_arr[$tmp_v]=$option_value[$i];
							$i++;
						}
					}
				}
			}
		}
		if($om_form_label==''){
			$result["status"] = 'fail';  
			$result["msg"]='Input Label should not be blank.';
			echo json_encode($result);
			exit(0); 
		}
		// print_r($option_k_v_arr); die();
		$last_sort=$this->Order_management_model->GetLastSortFormFields($form_id);
		$new_sort=($last_sort+1);
		$post_data=array(
			"form_id"=>$form_id,
			"input_type"=>$om_form_input_type,
			"label"=>$om_form_label,
			"option_with_value"=>(count($option_k_v_arr))?serialize($option_k_v_arr):'',
			"is_mandatory"=>$om_form_is_mandatory,
			"sort"=>$new_sort,
			"is_active"=>'Y',
			"is_deleted"=>'N'
			);
		$this->Order_management_model->AddStageFormFields($post_data);	

        $result["status"] = 'success';       
        $result["html"]='';
		$result["msg"]='-'.$om_form_input_type;
        echo json_encode($result);
        exit(0); 
    }

	function resort_om_stage_form_fields()
    {
		$new_sort=$this->input->get('new_sort');
		$i = 1;
		foreach ($new_sort as $edit_id) 
		{
			$post_data=array(
				"sort"=>$i			
				);
			$this->Order_management_model->UpdateStageFormFields($post_data,$edit_id);
		    $i++;
		}		
		$status_str='success';		   	 
        $result["status"] = $status_str;
        echo json_encode($result);
        exit(0); 
    }

	function delete_stage_form_fields()
	{
		$edit_id=$this->input->get('id');
		$post_data=array(
				"is_deleted"=>'Y'			
				);
		$return=$this->Order_management_model->UpdateStageFormFields($post_data,$edit_id);
		if($return==true){
			$status_str = "success";
		}
		else{
			$status_str = "fail";
		}	
		
		$result["status"] = $status_str;  
        echo json_encode($result);
		exit(0);
	}

	function edit_stage_form_fields_popup_ajax()
	{
		$edit_id=$this->input->get('id');
		$form_id=$this->input->get('form_id');
		$list=array();
		$list['form_row']=$this->Order_management_model->GetStageFormRow($form_id);
		$list['row']=$this->Order_management_model->GetFormFieldByIDRow($edit_id);
		$list['edit_id']=$edit_id;
		$list['form_id']=$form_id;
		$html = $this->load->view('admin/order_management/om_stage_wise_form_field_edit_view_ajax',$list,TRUE);	
		
		$result["popup_title"] = 'Edit Form Field for '.$list['form_row']['name']; 
		$result["html"] = $html;  
        echo json_encode($result);
		exit(0);
	}

	function edit_om_stage_form_fields_setting()
    {
		$form_id = $this->input->post('form_id'); 
		$edit_id = $this->input->post('edit_id');
		$om_form_input_type = $this->input->post('om_form_input_type'); 	
		$om_form_label = $this->input->post('om_form_label'); 	
		$om_form_is_mandatory = $this->input->post('om_form_is_mandatory');
		// $option_key = $this->input->post('option_key');
		$option_key = $this->input->post('option_value');
		$option_value = $this->input->post('option_value'); 	
		$option_k_v_arr=array();
		if($form_id==''){
			$result["status"] = 'fail';  
			$result["msg"]='Unknown Error.';
			echo json_encode($result);
			exit(0); 
		}

		if($om_form_input_type==''){
			$result["status"] = 'fail';  
			$result["msg"]='Input Type should not be blank.';
			echo json_encode($result);
			exit(0); 
		}
		else{
			if($om_form_input_type=='R' || $om_form_input_type=='S' || $om_form_input_type=='CB'){
				$flag=0;				
				if(count($option_key)){
					foreach($option_key AS $tmp_v){
						if($tmp_v==''){							
							$flag++;
							break;
						}
					}
				}

				if(count($option_value)){
					foreach($option_value AS $tmp_v){
						if($tmp_v==''){							
							$flag++;
							break;
						}
					}
				}
				
				if($flag>0){
					$result["status"] = 'fail';  
					$result["msg"]='Value should not be blank.';
					echo json_encode($result);
					exit(0); 
				}
				else{
					if(count($option_key)){
						$i=0;
						foreach($option_key AS $tmp_v){
							$option_k_v_arr[$tmp_v]=$option_value[$i];
							$i++;
						}
					}
				}
			}
		}
		if($om_form_label==''){
			$result["status"] = 'fail';  
			$result["msg"]='Input Label should not be blank.';
			echo json_encode($result);
			exit(0); 
		}
		// print_r($option_k_v_arr); die();
		// $last_sort=$this->Order_management_model->GetLastSortFormFields($form_id);
		// $new_sort=($last_sort+1);
		$post_data=array(
			"label"=>$om_form_label,
			"option_with_value"=>(count($option_k_v_arr))?serialize($option_k_v_arr):'',
			"is_mandatory"=>$om_form_is_mandatory
			);
		$this->Order_management_model->UpdateStageFormFields($post_data,$edit_id);	

        $result["status"] = 'success';       
        $result["html"]='';
		$result["msg"]='-'.$om_form_input_type;
        echo json_encode($result);
        exit(0); 
    }

	function rander_om_stage_wise_form_assign_ajax()
	{
		$list['rows']=$this->Order_management_model->GetActiveStageWiseAssignFormList();
	    $table = '';	    
	    $table = $this->load->view('admin/order_management/om_stage_wise_form_assign_list_view_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
		exit;
	}

	function rander_om_user_wise_permission_ajax()
	{
		$list['rows']=$this->Order_management_model->GetActivePermissionLinkList();
	    $table = '';	    
	    $table = $this->load->view('admin/order_management/om_user_wise_permission_list_view_ajax',$list,TRUE);		
	    $data =array (
	       "table"=>$table
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
		exit;
	}

	public function tag_assign_form_to_stage_view_rander_ajax()
	{
		$data=NULL;
		$stage_id=$this->input->post('stage_id');			
		$data['stage_id']=$stage_id;
		$already_assigned_form_ids=$this->Order_management_model->GetAlreadyAssignedFormIdsByTheStage($stage_id);
		
		$data['form_list']=$this->Order_management_model->GetNotAssignedFormIdsByTheStage($already_assigned_form_ids);
		$this->load->view('admin/order_management/tag_form_to_stage_view_modal_ajax',$data);
	}
	function tag_assign_form_to_stage_update()
	{
		$stage_id=$this->input->get('stage_id');
		$stage_form_id=$this->input->get('stage_form_id');
		if($stage_form_id!='' && $stage_id!=''){
			$stage_form_id_arr=explode(",",$stage_form_id);
			if(count($stage_form_id_arr)){
				foreach($stage_form_id_arr AS $val){
					if($val){
						$chk_duplicate=$this->Order_management_model->chk_duplicate_stage_wise_assigned_form($stage_id,$val);
						if($chk_duplicate==0){
							$post_data=array(
								"stage_id"=>$stage_id,
								"form_id"=>$val,
								"created_at"=>date("Y-m-d H:i:s")
								);
							$this->Order_management_model->add_stage_wise_assigned_form($post_data);
						}
					}
										
				}
			}
		}
		$return['status'] = 'success';
		echo json_encode($return);
		exit(0);
	}

	public function untag_assign_form_to_stage_view_rander_ajax()
	{
		$data=NULL;
		$stage_id=$this->input->post('stage_id');			
		$data['stage_id']=$stage_id;
		$already_assigned_form_ids=$this->Order_management_model->GetAlreadyAssignedFormIdsByTheStage($stage_id);

		$data['form_list']=$this->Order_management_model->GetAssignedFormIdsByTheStage($already_assigned_form_ids);
		$this->load->view('admin/order_management/untag_form_to_stage_view_modal_ajax',$data);
	}
	function untag_assign_form_to_stage_update()
	{
		$stage_id=$this->input->get('stage_id');
		$form_id=$this->input->get('form_id');
		if($form_id!='' && $stage_id!=''){
			$this->Order_management_model->delete_stage_wise_assigned_form_by_stageIdAndUserId($stage_id,$form_id);
		}
		$return['status'] = 'success';
		echo json_encode($return);
		exit(0);
	}

	public function om_detail_view_rander_ajax()
	{
		$session_data=$this->session->userdata('admin_session_data');
		$user_id=$session_data['user_id'];
		$data=NULL;
		$lowp=$this->input->post('lowp');	
		$pfi=$this->input->post('pfi');	
		$stage_id=$this->input->post('stage_id');
		$po_pi_wise_stage_tag_id=$this->input->post('po_pi_wise_stage_tag_id');
		$pfi_split_id=$this->input->post('pfi_split_id');
		// if($pfi_split_id){
		// 	$data['prod_list']=$this->Order_management_model->GetSplitProFormaInvoiceProductList($pfi_split_id);
		// }
		// else{
		// 	$data['prod_list']=$this->order_model->GetProFormaInvoiceProductList($lowp);
		// }
		
		
		$data['lowp']=$lowp;
		$data['pfi']=$pfi;
		$data['pfi_split_id']=$pfi_split_id;
		$data['po_pi_wise_stage_tag_id']=$po_pi_wise_stage_tag_id;
		$data['po_register_info']=$this->order_model->get_po_register_detail($lowp);
		$data['c2c_credentials']=$this->Setting_model->GetC2cCredentialsDetailsByUser($user_id);
		$data['assigned_form_list']=$this->Order_management_model->GetFormByTheStage($stage_id);

		$data['pi_tag_row']=$this->Order_management_model->GetPiWithTag($pfi,$pfi_split_id);
		$data['all_stage_rows']=$this->Order_management_model->GetActiveStageRows();
		$data['completed_stage_ids']=$this->Order_management_model->GetPiCompletedStageIds($data['pi_tag_row']['om_po_pi_wise_stage_tag_id']);
		$data['order_history']=$this->Order_management_model->GetOrderHistory(array('po_pi_id'=>$pfi,'user_id'=>$user_id));

		$data['is_quotation_link_available']=is_om_link_available('om_quotation');
		$data['is_purchase_order_link_available']=is_om_link_available('om_purchase_order');
		$data['is_proforma_invoice_link_available']=is_om_link_available('om_proforma_invoice');
		$data['is_invoice_link_available']=is_om_link_available('om_invoice');
		$data['is_payment_ledger_link_available']=is_om_link_available('om_payment_ledger');

		$html=$this->load->view('admin/order_management/om_detail_view_ajax',$data,true);
		
		$data=array("html"=>$html);		
		$this->output->set_content_type('application/json');
		echo json_encode($data);
		exit;
	}

	public function om_form_wise_fields_view_rander_ajax()
	{
		$list=NULL;
		$f_id=$this->input->post('f_id');
		$proforma_invoice_id=$this->input->post('proforma_invoice_id');
		$pfi_split_id=$this->input->post('pfi_split_id');
		$is_already_exist=$this->Order_management_model->ChkIsFirmAlreadyExist($f_id,$proforma_invoice_id,$pfi_split_id);
		if($is_already_exist>0){
			$data =array (
				"status"=>'fail',
				"msg"=>'The Form already exist.'
				);
			
			 $this->output->set_content_type('application/json');
			 echo json_encode($data); exit;
		}
		$list['form_wise_field_list']=$this->Order_management_model->GetActiveStageFormFieldsList($f_id);
		$list['form_id']=$f_id;		
		$html=$this->load->view('admin/order_management/om_form_wise_fields_view_ajax',$list,true);
		$data =array (
			"status"=>'success',
			"html"=>$html,
			"msg"=>''
			);
		
		 $this->output->set_content_type('application/json');
		 echo json_encode($data); exit;
	}

	function om_stage_wise_doc_submit()
    {
		$session_data=$this->session->userdata('admin_session_data');
		$user_id=$session_data['user_id'];
		// print_r($_POST);die();
		$post_arr=array();
		$post_arr=$_POST;
		$form_id=$post_arr['form_id'];
		$proforma_invoice_id=$post_arr['proforma_invoice_id'];
		$om_custom_form_field=$post_arr['om_custom_form_field'];
		$po_pi_wise_stage_tag_id=$post_arr['po_pi_wise_stage_tag_id'];
		$pfi_split_id=$post_arr['pfi_split_id'];
		
		if($om_custom_form_field){			

			$chk_duplicate=$this->Order_management_model->chk_duplicate_om_stage_form_submitted($form_id,$proforma_invoice_id,$pfi_split_id);
			if($chk_duplicate==0){
				$fields_arr=array();
				$fields_arr=explode('!***!',$om_custom_form_field);
				if(count($fields_arr)){
					$form_data=$this->Order_management_model->GetStageFormById($form_id);				
					$post_data=array(
						"form_id"=>$form_id,
						"name"=>$form_data['name'],
						"user_id"=>$user_id,
						"po_pi_id"=>$proforma_invoice_id,
						"po_pi_wise_stage_tag_id"=>$po_pi_wise_stage_tag_id,
						"pro_forma_invoice_split_id"=>$pfi_split_id
						);
					$om_stage_form_submitted_id=$this->Order_management_model->AddStageFormSubmitted($post_data);					

					$get_form_fields=$this->Order_management_model->GetStageFormFieldsList($form_id);
					
					if($om_stage_form_submitted_id!=false)
					{
						if(count($get_form_fields)){
							foreach($get_form_fields AS $ff){
								$post_data=array(
									"om_stage_form_field_id"=>$ff['id'],
									"om_stage_form_submitted_id"=>$om_stage_form_submitted_id,
									"input_type"=>$ff['input_type'],
									"label"=>$ff['label'],
									"option_with_value"=>$ff['option_with_value'],
									"is_mandatory"=>$ff['is_mandatory'],
									"sort"=>$ff['sort']
									);
								$this->Order_management_model->AddStageFormFieldsSubmitted($post_data);
							}
						}
						
						foreach($fields_arr AS $field){
							$field_arr=explode('~',$field);				
							$om_stage_form_field_id=$field_arr[0];
							$om_stage_form_field_value=$field_arr[1];	
							$om_stage_form_field_name=$field_arr[2];
							
													
							if(count($om_stage_form_field_id!='' && $om_stage_form_field_value!='')){

								$input_type_tmp=get_value("input_type","om_stage_form_field_submitted","om_stage_form_field_id=".$om_stage_form_field_id);
								
								if($input_type_tmp=='F'){
									// $result["msg"]=$om_stage_form_field_id.'/'.$om_stage_form_field_name.'/'.$input_type_tmp;
									// echo json_encode($result);
									// exit(0);

									if($_FILES[$om_stage_form_field_name]['tmp_name'])
									{
										$this->load->library('upload', '');
										$config2['upload_path'] = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/";
										$config2['allowed_types'] = "gif|jpg|jpeg|png|pdf|doc|docx|xls|ppt|txt|wav|mp3|mp4|mpg|mov|wmv";
										$config2['max_size'] = '1000000'; //KB
										$this->upload->initialize($config2);
										
										
										if (!$this->upload->do_upload($om_stage_form_field_name))
										{
											$msg= $this->upload->display_errors();
											$result["status"] = 'fail';       
											$result["html"]='';
											$result["msg"]=$msg;
											echo json_encode($result);
											exit(0);
										}
										else
										{
											$file_data = array('upload_data' => $this->upload->data());
											$attach_filename = $file_data['upload_data']['file_name'];									
											$om_stage_form_field_value=$config2['upload_path'].$attach_filename;
										}
									}
									
								}

								$post_data=array(							
										"submitted_value"=>$om_stage_form_field_value
									);
								$this->Order_management_model->UpdateStageFormFieldsSubmittedBy_om_stage_form_field_id($post_data,$om_stage_form_field_id,$om_stage_form_submitted_id);
							}				
						}
					}
					
					// $pi_id=get_value("po_pi_id","om_po_pi_wise_stage_tag","id=".$po_pi_wise_stage_tag_id);
					$h_data=$this->Order_management_model->GetDataForHistoryByPi($proforma_invoice_id);
					// =========================
					// HISTORY CREATE
					$update_by=$this->session->userdata['admin_session_data']['user_id'];
					$ip_addr=$_SERVER['REMOTE_ADDR'];				
					$message="Document(".$form_data['name'].") has been submitted on ".datetime_db_format_to_display_format_ampm(date('Y-m-d H:i:s'));
					$comment_title=OM_SUBMITTED_DOCUMENT;
					$historydata=array(
								'lead_id'=>$h_data['lead_id'],
								'lead_opportunity_id'=>$h_data['lead_opportunity_id'],
								'lowp'=>$h_data['lowp'],
								'source_id'=>$h_data['source_id'],
								'po_pi_id'=>$h_data['po_pi_id'],
								'title'=>$comment_title,						
								'comment'=>addslashes($message),
								'updated_by'=>$update_by,
								'created_at'=>date("Y-m-d H:i:s"),						
								'ip_address'=>$ip_addr
								);
					$this->Order_management_model->CreateHistory($historydata);
					// HISTORY CREATE
					// =========================
				}
			}
			
		}
		

        $result["status"] = 'success';       
        $result["html"]='';
		$result["msg"]='';
        echo json_encode($result);
        exit(0); 
    }

	public function om_proforma_wise_document_list_view_rander_ajax()
	{
		$session_data=$this->session->userdata('admin_session_data');
		$user_id=$session_data['user_id'];
		$data=NULL;
		$proforma_id=$this->input->post('proforma_id');
		$pfi_split_id=$this->input->post('pfi_split_id');
		$data['rows']=$this->Order_management_model->GetPIWiseDocumentList($proforma_id,$pfi_split_id);
		$data['proforma_id']=$proforma_id;
		$data['user_id']=$user_id;		
		$html=$this->load->view('admin/order_management/om_proforma_wise_document_list_view_ajax',$data,true);
		$data =array (
			"html"=>$html
			);
		
		 $this->output->set_content_type('application/json');
		 echo json_encode($data); exit;
	}

	public function document_view_ajax()
	{
		$data=NULL;
		$id=$this->input->post('id');			
		$data['id']=$id;	
		// $data['po_pi_id']=get_value("po_pi_id","om_stage_form_submitted","id=".$id);	
		$data['form_wise_field_list']=$this->Order_management_model->GetSubmittedFormFieldsList($id);
		$html=$this->load->view('admin/order_management/document_view_modal_ajax',$data,true);
		
        $result["html"]=$html;
		$result["title"]=$data['form_wise_field_list'][0]['name'];		
        echo json_encode($result);
        exit(0); 
	}

	public function document_edit_view_ajax()
	{
		$data=NULL;
		$id=$this->input->post('id');			
		$data['id']=$id;
		$data['po_pi_id']=get_value("po_pi_id","om_stage_form_submitted","id=".$id);
		$data['form_wise_field_list']=$this->Order_management_model->GetSubmittedFormFieldsList($id);
		$html=$this->load->view('admin/order_management/document_edit_view_modal_ajax',$data,true);
		$result["html"]=$html;	
		$result["title"]=$data['form_wise_field_list'][0]['name'];		
        echo json_encode($result);
        exit(0); 
	}

	function om_stage_wise_doc_submit_edit()
    {
		$session_data=$this->session->userdata('admin_session_data');
		$user_id=$session_data['user_id'];
		// print_r($_POST);die();
		$post_arr=array();
		$post_arr=$_POST;		
		$po_pi_id=$post_arr['po_pi_id'];
		$om_stage_form_submitted_id=$post_arr['om_stage_form_submitted_id'];
		$om_custom_form_field=$post_arr['om_custom_form_field_edit'];
		if($om_custom_form_field){
			$fields_arr=array();
			$fields_arr=explode('!***!',$om_custom_form_field);
			if(count($fields_arr)){
				
				foreach($fields_arr AS $field){
					$field_arr=explode('~',$field);				
					$id=$field_arr[0];
					$field_value=$field_arr[1];	
					$form_field_name=$field_arr[2];		
							
					if(count($id!='' && $field_value!='')){						
						$input_type_tmp=get_value("input_type","om_stage_form_field_submitted","id=".$id);
							
						if($input_type_tmp=='F'){
							// $result["msg"]=$form_field_name;
							// echo json_encode($result);
							// exit(0);
							if($_FILES[$form_field_name]['tmp_name'])
							{
								$this->load->library('upload', '');
								$config2['upload_path'] = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/";
								$config2['allowed_types'] = "gif|jpg|jpeg|png|pdf|doc|docx|xls|ppt|txt|wav|mp3|mp4|mpg|mov|wmv";
								$config2['max_size'] = '1000000'; //KB
								$this->upload->initialize($config2);						
								
								if (!$this->upload->do_upload($form_field_name))
								{
									$msg= $this->upload->display_errors();
									$result["status"] = 'fail';       
									$result["html"]='';
									$result["msg"]=$msg;
									echo json_encode($result);
									exit(0);
								}
								else
								{
									$file_data = array('upload_data' => $this->upload->data());
									$attach_filename = $file_data['upload_data']['file_name'];									
									$field_value=$config2['upload_path'].$attach_filename;

									$existing_document_path=get_value("submitted_value","om_stage_form_field_submitted","id=".$id);
									if(file_exists($existing_document_path)){						
										@unlink($existing_document_path);
									}else{
										// echo 'file not found';
									}

									$post_data=array(							
										"submitted_value"=>$field_value
										);
									$this->Order_management_model->UpdateStageFormFieldsSubmitted($post_data,$id);
								}
							}							
						}
						else{
							$post_data=array(							
								"submitted_value"=>$field_value
								);
							$this->Order_management_model->UpdateStageFormFieldsSubmitted($post_data,$id);
						}
						
						
					}					
				}

				$form_name=get_value("name","om_stage_form_submitted","id=".$om_stage_form_submitted_id);
				$h_data=$this->Order_management_model->GetDataForHistoryByPi($po_pi_id);
				// =========================
				// HISTORY CREATE
				$update_by=$this->session->userdata['admin_session_data']['user_id'];
				$ip_addr=$_SERVER['REMOTE_ADDR'];				
				$message="Document(".$form_name.") has been updated on ".datetime_db_format_to_display_format_ampm(date('Y-m-d H:i:s'));
				$comment_title=OM_SUBMITTED_DOCUMENT_EDIT;
				$historydata=array(
							'lead_id'=>$h_data['lead_id'],
							'lead_opportunity_id'=>$h_data['lead_opportunity_id'],
							'lowp'=>$h_data['lowp'],
							'source_id'=>$h_data['source_id'],
							'po_pi_id'=>$h_data['po_pi_id'],
							'title'=>$comment_title,						
							'comment'=>addslashes($message),
							'updated_by'=>$update_by,
							'created_at'=>date("Y-m-d H:i:s"),						
							'ip_address'=>$ip_addr
							);
				$this->Order_management_model->CreateHistory($historydata);
				// HISTORY CREATE
				// =========================
			}
		}
        $result["status"] = 'success';       
        $result["html"]='';
		$result["msg"]='';
        echo json_encode($result);
        exit(0); 
    }

	public function document_delete_ajax()
	{
		$data=NULL;
		$id=$this->input->post('id');
		// echo $om_stage_form_submitted_id=get_value("om_stage_form_submitted_id","om_stage_form_field_submitted","id=".$id);	die();
		$get_form_submitted_row=$this->Order_management_model->get_om_stage_form_submitted_row($id);			
		$doc_list=$this->Order_management_model->get_submitted_document_list_by_om_stage_form_submitted_id($id);			
		if(count($doc_list)){
			foreach($doc_list AS $doc){
				if($doc['input_type']=='F'){
					$document_path=$doc['submitted_value'];
			        if(file_exists($document_path)){
						
					    @unlink($document_path);
					}else{
					    // echo 'file not found';
					}
				}
			}

			
		}		
		$r=$this->Order_management_model->delete_submitted_document($id);
		if($r){
			// $pi_id=get_value("po_pi_id","om_po_pi_wise_stage_tag","id=".$po_pi_wise_stage_tag_id);
			$h_data=$this->Order_management_model->GetDataForHistoryByPi($get_form_submitted_row['po_pi_id']);
			// =========================
			// HISTORY CREATE
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$ip_addr=$_SERVER['REMOTE_ADDR'];				
			$message="Document(".$get_form_submitted_row['name'].") has been deleted on ".datetime_db_format_to_display_format_ampm(date('Y-m-d H:i:s'));
			$comment_title=OM_SUBMITTED_DOCUMENT_DELETE;
			$historydata=array(
						'lead_id'=>$h_data['lead_id'],
						'lead_opportunity_id'=>$h_data['lead_opportunity_id'],
						'lowp'=>$h_data['lowp'],
						'source_id'=>$h_data['source_id'],
						'po_pi_id'=>$h_data['po_pi_id'],
						'title'=>$comment_title,						
						'comment'=>addslashes($message),
						'updated_by'=>$update_by,
						'created_at'=>date("Y-m-d H:i:s"),						
						'ip_address'=>$ip_addr
						);
			$this->Order_management_model->CreateHistory($historydata);
			// HISTORY CREATE
			// =========================
		}		
		$result["status"]='success';			
        echo json_encode($result);
        exit(0); 
	}

	public function download($file='')
	{	
		if($file!='')
		{	
			$this->load->helper(array('download'));	
			$file_name = base64_decode($file);
			$file_name_arr=explode("/",$file_name);
			$file_name_tmp=end($file_name_arr);
			$pth    =   file_get_contents($file_name);
			force_download($file_name_tmp, $pth); 
			exit;
		}
	}

	function change_pi_priority_ajax()
	{
		$po_pi_wise_stage_tag_id=$this->input->get('po_pi_wise_stage_tag_id');
		$get_existing_priority=$this->Order_management_model->GetExistingPiPriority($po_pi_wise_stage_tag_id);
		
		if($get_existing_priority=='1'){
			$new_priority='2';
		}
		else{
			$new_priority='1';
		}
		$post_data=array(
				"priority"=>$new_priority			
				);
		
		$return=$this->Order_management_model->update_po_pi_wise_stage_tag($post_data,$po_pi_wise_stage_tag_id);
		if($return==true){

			$pi_id=get_value("po_pi_id","om_po_pi_wise_stage_tag","id=".$po_pi_wise_stage_tag_id);
			$h_data=$this->Order_management_model->GetDataForHistoryByPi($pi_id);
			// =========================
			// HISTORY CREATE
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$ip_addr=$_SERVER['REMOTE_ADDR'];				
			$message="Priority has been changed from ".get_om_priority_name_by_id($get_existing_priority)." to ".get_om_priority_name_by_id($new_priority)." on ".datetime_db_format_to_display_format_ampm(date('Y-m-d H:i:s'));
			$comment_title=OM_PRIORITY_CHANGE;
			$historydata=array(
						'lead_id'=>$h_data['lead_id'],
						'lead_opportunity_id'=>$h_data['lead_opportunity_id'],
						'lowp'=>$h_data['lowp'],
						'source_id'=>$h_data['source_id'],
						'po_pi_id'=>$h_data['po_pi_id'],
						'title'=>$comment_title,						
						'comment'=>addslashes($message),
						'updated_by'=>$update_by,
						'created_at'=>date("Y-m-d H:i:s"),						
						'ip_address'=>$ip_addr
						);
			$this->Order_management_model->CreateHistory($historydata);
			// HISTORY CREATE
			// =========================
			$status_str = "success";
		}
		else{
			$status_str = "fail";
		}	
		
		$result["status"] = $status_str;  
        echo json_encode($result);
		exit(0);
	}

	public function set_history_ajax()
	{
		$list=array();
		$pi_id = $this->input->post('pfi');
    	$history_text = $this->input->post('history_text');

		
		$h_data=$this->Order_management_model->GetDataForHistoryByPi($pi_id);
		// =========================
		// HISTORY CREATE
		$update_by=$this->session->userdata['admin_session_data']['user_id'];
		$ip_addr=$_SERVER['REMOTE_ADDR'];				
		$message=$history_text." on ".datetime_db_format_to_display_format_ampm(date('Y-m-d H:i:s'));
		$comment_title=OM_DOWNLOAD;
		$historydata=array(
					'lead_id'=>$h_data['lead_id'],
					'lead_opportunity_id'=>$h_data['lead_opportunity_id'],
					'lowp'=>$h_data['lowp'],
					'source_id'=>$h_data['source_id'],
					'po_pi_id'=>$h_data['po_pi_id'],
					'title'=>$comment_title,						
					'comment'=>addslashes($message),
					'updated_by'=>$update_by,
					'created_at'=>date("Y-m-d H:i:s"),						
					'ip_address'=>$ip_addr
					);
		$this->Order_management_model->CreateHistory($historydata);
		// HISTORY CREATE
		// =========================
		$result['status'] = 'success';
        echo json_encode($result);
        exit(0); 
	}

	public function order_history_rander_ajax()
	{
		$session_data=$this->session->userdata('admin_session_data');
		$user_id=$session_data['user_id'];
		$list=array();
		$pi_id=$this->input->post('pi_id');	
		$list['order_history']=$this->Order_management_model->GetOrderHistory(array('po_pi_id'=>$pi_id,'user_id'=>$user_id));
		$html=$this->load->view('admin/order_management/order_history_view_ajax',$list,true);
		
		$data=array("html"=>$html);		
		$this->output->set_content_type('application/json');
		echo json_encode($data);
		exit;
	}

	public function rander_split_order_view_ajax()
	{
		$list=array();
		$lowp = $this->input->post('lowp');
    	$pfi = $this->input->post('pfi');
		$po_pi_wise_stage_tag_id = $this->input->post('po_pi_wise_stage_tag_id');
		$pfi_split_id = $this->input->post('pfi_split_id');
		$list['po_pro_forma_inv_info']=$this->order_model->get_po_pro_forma_invoice_detail_lowpo_wise($lowp);	
		if($pfi_split_id){
			$list['prod_list']=$this->Order_management_model->GetSplitProFormaInvoiceProductList($pfi_split_id);
		}
		else{
			$list['prod_list']=$this->order_model->GetProFormaInvoiceProductList($lowp);
		}
		
        // $list['additional_charges_list']=$this->order_model->GetProFormaInvoiceAdditionalCharges($lowp);
		$list['pfi']=$pfi;
		$list['pfi_split_id']=$pfi_split_id;
		$list['lowp']=$lowp;
		$html=$this->load->view('admin/order_management/split_order_view_ajax',$list,true);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0); 
	}

	public function save_split_order_ajax()
	{
		$session_data=$this->session->userdata('admin_session_data');
		$user_id=$session_data['user_id'];
		$list=array();
		$lowp = $this->input->post('lowp');
    	$pfi = $this->input->post('pfi');
		$existing_pfi_split_id = $this->input->post('pfi_split_id');
		$po_pro_forma_invoice_product_ids_arr = explode(",",$this->input->post('po_pro_forma_invoice_product_ids'));
    	$po_pro_forma_invoice_product_ids_checked_arr = explode(",",$this->input->post('po_pro_forma_invoice_product_ids_checked'));

		$p_q_id_arr = explode(",",$this->input->post('p_q_ids'));
    	$p_q_checked_arr = explode(",",$this->input->post('p_q_checked'));
		$p_q_existing_arr = explode(",",$this->input->post('p_q_existing_arr'));
		
		
		if($lowp>0 && $pfi>0 && count($p_q_id_arr)>0 && count($p_q_checked_arr)>0 && count($p_q_existing_arr)>0)
		{
			$is_split=get_value("is_split","po_pro_forma_invoice","id=".$pfi);
			$get_priority=$this->Order_management_model->get_existing_priority_by_pfi($pfi);
			if($is_split='N' && $existing_pfi_split_id=='')
			{
				// ===============================================
				// $om_po_pi_wise_stage_tag_info=$this->Order_management_model->get_om_po_pi_wise_stage_tag_by_pi($pfi);
				
				// ===============================================
				$pf_post=array('is_split'=>'Y','updated_at'=>date("Y-m-d H:i:s"));
				$this->order_model->updatePoFormaInvoice($pf_post,$pfi);

				$post=array(
					'po_pro_forma_invoice_id'=>$pfi,
					'lead_opportunity_wise_po_id'=>$lowp,
					'created_at'=>date("Y-m-d H:i:s"),
					'updated_at'=>date("Y-m-d H:i:s")
				);
				$pf_split_id=$this->Order_management_model->AddProformaSplit($post);
				if($pf_split_id!=false)
				{
					$this->Order_management_model->delete_om_po_pi_wise_stage_tag_by_pi($pfi);
					$post_data_pst=array(
						"stage_id"=>'1',
						"po_pi_id"=>$pfi,
						"po_pi_split_id"=>$pf_split_id,
						"updated_by"=>$user_id,
						"created_at"=>date("Y-m-d H:i:s"),
						"updated_at"=>date("Y-m-d H:i:s")
					);
					$r_pst=$this->Order_management_model->add_pi_stage_tag($post_data_pst);
					if($r_pst){
					$post_data_log_pst=array(
						"om_po_pi_wise_stage_tag_id"=>$r_pst,
						"stage_id"=>'1',
						"po_pi_id"=>$pfi,
						"po_pi_split_id"=>$pf_split_id,
						"priority"=>$get_priority,
						"updated_by"=>$user_id,
						"created_at"=>date("Y-m-d H:i:s")
						);
					$this->Order_management_model->add_pi_stage_tag_log($post_data_log_pst);
					}

					if(count($p_q_existing_arr))
					{
						$i=0;
						foreach($p_q_existing_arr AS $pq_e)
						{
							$pq_e_arr=explode("~",$pq_e);
							$pq_arr=explode("~",$p_q_id_arr[$i]);
							$po_pro_forma_invoice_product_id=$pq_e_arr[0];
							$quantity=($pq_arr[1]==0)?$pq_e_arr[1]:($pq_e_arr[1]-$pq_arr[1]);
							if($quantity>0)
							{
								$post=array(
									'po_pro_forma_invoice_split_id'=>$pf_split_id,
									'po_pro_forma_invoice_id'=>$pfi,
									'po_pro_forma_invoice_product_id'=>$po_pro_forma_invoice_product_id,
									'quantity'=>$quantity,
									'created_at'=>date("Y-m-d H:i:s")
								);		
								$this->Order_management_model->AddProformaProductSplit($post);
							}
							$i++;
						}
					}									
				}

				$post=array(
					'po_pro_forma_invoice_id'=>$pfi,
					'lead_opportunity_wise_po_id'=>$lowp,
					'created_at'=>date("Y-m-d H:i:s"),
					'updated_at'=>date("Y-m-d H:i:s")
				);
				$pf_split_id=$this->Order_management_model->AddProformaSplit($post);
				if($pf_split_id!=false)
				{
					
					$post_data_pst=array(
						"stage_id"=>'1',
						"po_pi_id"=>$pfi,
						"po_pi_split_id"=>$pf_split_id,
						"updated_by"=>$user_id,
						"created_at"=>date("Y-m-d H:i:s"),
						"updated_at"=>date("Y-m-d H:i:s")
					);
					$r_pst=$this->Order_management_model->add_pi_stage_tag($post_data_pst);
					if($r_pst){
					$post_data_log_pst=array(
						"om_po_pi_wise_stage_tag_id"=>$r_pst,
						"stage_id"=>'1',
						"po_pi_id"=>$pfi,
						"po_pi_split_id"=>$pf_split_id,
						"priority"=>$get_priority,
						"updated_by"=>$user_id,
						"created_at"=>date("Y-m-d H:i:s")
						);
					$this->Order_management_model->add_pi_stage_tag_log($post_data_log_pst);
					}

					if(count($p_q_checked_arr))
					{
						$i=0;
						foreach($p_q_checked_arr AS $pq_checked)
						{
							$pq_checked_arr=explode("~",$pq_checked);				
							$po_pro_forma_invoice_product_id=$pq_checked_arr[0];
							$quantity=$pq_checked_arr[1];
							if($quantity>0)
							{
								$post=array(
									'po_pro_forma_invoice_split_id'=>$pf_split_id,
									'po_pro_forma_invoice_id'=>$pfi,
									'po_pro_forma_invoice_product_id'=>$po_pro_forma_invoice_product_id,
									'quantity'=>$quantity,
									'created_at'=>date("Y-m-d H:i:s")
								);
								$this->Order_management_model->AddProformaProductSplit($post);	
							}
							$i++;
						}
					}					
				}

				if($pf_split_id!=false)
				{
					
					
					
				}
			}
			else
			{
				
				$this->Order_management_model->delete_ExistingSplitProFormaInvoiceInfo($existing_pfi_split_id);

				$post=array(
					'po_pro_forma_invoice_id'=>$pfi,
					'lead_opportunity_wise_po_id'=>$lowp,
					'created_at'=>date("Y-m-d H:i:s"),
					'updated_at'=>date("Y-m-d H:i:s")
				);
				$pf_split_id=$this->Order_management_model->AddProformaSplit($post);
				if($pf_split_id!=false)
				{
					$this->Order_management_model->delete_om_po_pi_wise_stage_tag_by_pi_split_id($existing_pfi_split_id);
					$post_data_pst=array(
						"stage_id"=>'1',
						"po_pi_id"=>$pfi,
						"po_pi_split_id"=>$pf_split_id,
						"updated_by"=>$user_id,
						"created_at"=>date("Y-m-d H:i:s"),
						"updated_at"=>date("Y-m-d H:i:s")
					);
					$r_pst=$this->Order_management_model->add_pi_stage_tag($post_data_pst);
					if($r_pst){
					$post_data_log_pst=array(
						"om_po_pi_wise_stage_tag_id"=>$r_pst,
						"stage_id"=>'1',
						"po_pi_id"=>$pfi,
						"po_pi_split_id"=>$pf_split_id,
						"priority"=>$get_priority,
						"updated_by"=>$user_id,
						"created_at"=>date("Y-m-d H:i:s")
						);
					$this->Order_management_model->add_pi_stage_tag_log($post_data_log_pst);
					}
					if(count($p_q_existing_arr))
					{
						$i=0;
						foreach($p_q_existing_arr AS $pq_e)
						{
							$pq_e_arr=explode("~",$pq_e);
							$pq_arr=explode("~",$p_q_id_arr[$i]);
							$po_pro_forma_invoice_product_id=$pq_e_arr[0];
							$quantity=($pq_arr[1]==0)?$pq_e_arr[1]:($pq_e_arr[1]-$pq_arr[1]);
							if($quantity>0)
							{
								$post=array(
									'po_pro_forma_invoice_split_id'=>$pf_split_id,
									'po_pro_forma_invoice_id'=>$pfi,
									'po_pro_forma_invoice_product_id'=>$po_pro_forma_invoice_product_id,
									'quantity'=>$quantity,
									'created_at'=>date("Y-m-d H:i:s")
								);		
								$this->Order_management_model->AddProformaProductSplit($post);
							}
							$i++;
						}
					}									
				}

				$post=array(
					'po_pro_forma_invoice_id'=>$pfi,
					'lead_opportunity_wise_po_id'=>$lowp,
					'created_at'=>date("Y-m-d H:i:s"),
					'updated_at'=>date("Y-m-d H:i:s")
				);
				$pf_split_id=$this->Order_management_model->AddProformaSplit($post);
				if($pf_split_id!=false)
				{
					$post_data_pst=array(
						"stage_id"=>'1',
						"po_pi_id"=>$pfi,
						"po_pi_split_id"=>$pf_split_id,
						"updated_by"=>$user_id,
						"created_at"=>date("Y-m-d H:i:s"),
						"updated_at"=>date("Y-m-d H:i:s")
					);
					$r_pst=$this->Order_management_model->add_pi_stage_tag($post_data_pst);
					if($r_pst){
					$post_data_log_pst=array(
						"om_po_pi_wise_stage_tag_id"=>$r_pst,
						"stage_id"=>'1',
						"po_pi_id"=>$pfi,
						"po_pi_split_id"=>$pf_split_id,
						"priority"=>$get_priority,
						"updated_by"=>$user_id,
						"created_at"=>date("Y-m-d H:i:s")
						);
					$this->Order_management_model->add_pi_stage_tag_log($post_data_log_pst);
					}
					
					if(count($p_q_checked_arr))
					{
						$i=0;
						foreach($p_q_checked_arr AS $pq_checked)
						{
							$pq_checked_arr=explode("~",$pq_checked);				
							$po_pro_forma_invoice_product_id=$pq_checked_arr[0];
							$quantity=$pq_checked_arr[1];
							if($quantity>0)
							{
								$post=array(
									'po_pro_forma_invoice_split_id'=>$pf_split_id,
									'po_pro_forma_invoice_id'=>$pfi,
									'po_pro_forma_invoice_product_id'=>$po_pro_forma_invoice_product_id,
									'quantity'=>$quantity,
									'created_at'=>date("Y-m-d H:i:s")
								);
								$this->Order_management_model->AddProformaProductSplit($post);	
							}
							$i++;
						}
					}					
				}
			}	

			// =========================
			// HISTORY CREATE ORDER MANAGEMNT
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			// $update_by_name=get_value("name","user","id=".$update_by);
			$ip_addr=$_SERVER['REMOTE_ADDR'];		
			$comment_title=OM_SPLIT;
			$comment='';
			$comment .="The order('".$lowp."') has been split on ".datetime_db_format_to_display_format_ampm(date('Y-m-d H:i:s'));
			$lead_opportunity_id=get_value("lead_opportunity_id","lead_opportunity_wise_po","id=".$lowp);
			$lead_id=get_value("lead_id","lead_opportunity","id=".$lowp);
			$source_id=get_value("source_id","lead","id=".$lead_id);	
			$historydata=array();
			$historydata=array(
				'lead_id'=>$lead_id,
				'lead_opportunity_id'=>$lead_opportunity_id,
				'lowp'=>$lowp,
				'source_id'=>$source_id,
				'po_pi_id'=>$pfi,
				'title'=>$comment_title,						
				'comment'=>addslashes($comment),
				'updated_by'=>$update_by,
				'created_at'=>date("Y-m-d H:i:s"),						
				'ip_address'=>$ip_addr
				);
			$this->Order_management_model->CreateHistory($historydata);
			// HISTORY CREATE ORDER MANAGEMNT
			// =========================
		}				
		$result['msg'] = '';
		$result['status'] = 'success';
        echo json_encode($result);
        exit(0); 
	}

	public function rander_deliverables_view_ajax()
	{
		$data=NULL;
		$lowp=$this->input->post('lowp');	
		$pfi_split_id=$this->input->post('pfi_split_id');
		if($pfi_split_id){
			$data['prod_list']=$this->Order_management_model->GetSplitProFormaInvoiceProductList($pfi_split_id);
		}
		else{
			$data['prod_list']=$this->order_model->GetProFormaInvoiceProductList($lowp);
		}
		$html=$this->load->view('admin/order_management/pfi_deliverables_view_modal_ajax',$data,true);
		
        $result["html"]=$html;
		$result["title"]='Deliverables';		
        echo json_encode($result);
        exit(0); 
	}

	public function rander_stage_wise_listing_view_ajax()
	{
		$data=NULL;
		$stage_id=$this->input->post('stage_id');
		$stage_name=$this->input->post('stage_name');	

		
		$data['stage_id']=$stage_id;
		$html=$this->load->view('admin/order_management/stage_wise_order_listing_view_modal_ajax',$data,true);		
        $result["html"]=$html;
		$result["title"]='Order List for the stage ('.$stage_name.')';		
        echo json_encode($result);
        exit(0); 
	}

	function change_is_mandatory()
    {        
        $id = $this->input->post('id');
        $curr_status = $this->input->post('curr_status');       
        
        if($curr_status=='Y')
        {
            $s='N';
        }
        else
        {
            $s='Y';
        }        

        $update=array(
                    'is_mandatory'=>$s
                    );
        $this->Order_management_model->UpdateStageForm($update,$id);
        $result["status"] = 'success';
        echo json_encode($result);
        exit(0);        
    }

	public function create_om_update_comment_ajax()
	{		
		$po_pi_id=$this->input->post('proforma_invoice_id');
		$description=trim($this->input->post('om_comment'));
		
		$h_data=$this->Order_management_model->GetDataForHistoryByPi($po_pi_id);
		// =========================
		// HISTORY CREATE
		$update_by=$this->session->userdata['admin_session_data']['user_id'];
		$ip_addr=$_SERVER['REMOTE_ADDR'];				
		$message="".$description."";
		$comment_title=OM_UPDATE_COMMENT;
		$historydata=array(
					'lead_id'=>$h_data['lead_id'],
					'lead_opportunity_id'=>$h_data['lead_opportunity_id'],
					'lowp'=>$h_data['lowp'],
					'source_id'=>$h_data['source_id'],
					'po_pi_id'=>$h_data['po_pi_id'],
					'title'=>$comment_title,						
					'comment'=>addslashes($message),
					'updated_by'=>$update_by,
					'created_at'=>date("Y-m-d H:i:s"),						
					'ip_address'=>$ip_addr
					);
		$this->Order_management_model->CreateHistory($historydata);
		// HISTORY CREATE
		// =========================
		
		
		// LEAD ATTACH FILE UPLOAD		
		/*$attach_filename='';
		$attach_filename_with_path='';
		$this->load->library('upload', '');
		if($_FILES['po_uc_file']['tmp_name'])
        {
        	$dataInfo = array();
        	$files = $_FILES;
            $cpt = count($_FILES['po_uc_file']['name']);
            $config = array(
            'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/",
            'allowed_types' => "gif|jpg|jpeg|png|pdf|doc|docx|mp3|mp4",                        
            'max_size' => "2048000" 
            );

        	$this->upload->initialize($config);
            for($i=0; $i<$cpt; $i++)
            {
            	if(!in_array($i, $removed_attach_file_arr))
            	{
            		$_FILES['po_uc_file']['name']= $files['po_uc_file']['name'][$i];
	                $_FILES['po_uc_file']['type']= $files['po_uc_file']['type'][$i];
	                $_FILES['po_uc_file']['tmp_name']= $files['po_uc_file']['tmp_name'][$i];
	                $_FILES['po_uc_file']['error']= $files['po_uc_file']['error'][$i];
	                $_FILES['po_uc_file']['size']= $files['po_uc_file']['size'][$i];  

	            	$target_dir = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/";
					$target_file = $target_dir . basename($_FILES['po_uc_file']['name']); 

	            	if (!$this->upload->do_upload('po_uc_file'))
	            	{
	            		// echo $this->upload->display_errors();
	            		// die();
	            	}
	                else
	                {
	                	$dataInfo = $this->upload->data();
	                    $filename=$dataInfo['file_name']; //Image Name
	                    $attach_filename=$filename;
	                    
	                    $attach_filename_with_path="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/".$filename;
	                    
	                }
            	}            	
            }
        }*/			
		// end
		// =====================================================	

		

		$data =array (
					   "status"=>'success',
					   "msg"=>'The comment successfully updated.'
					);
		$this->output->set_content_type('application/json');
		echo json_encode($data);
		exit(0);		
	}

	function change_pi_lock_status_ajax()
	{
		$po_pi_wise_stage_tag_id=$this->input->get('po_pi_wise_stage_tag_id');
		$get_existing_priority=$this->Order_management_model->GetExistingPiLockStatus($po_pi_wise_stage_tag_id);
		
		if($get_existing_priority=='Y'){
			$new_priority='N';
			
		}
		else{
			$new_priority='Y';
		}
		$post_data=array(
				"is_lock"=>$new_priority			
				);
		
		$return=$this->Order_management_model->update_po_pi_wise_stage_tag($post_data,$po_pi_wise_stage_tag_id);
		if($return==true){

			$pi_id=get_value("po_pi_id","om_po_pi_wise_stage_tag","id=".$po_pi_wise_stage_tag_id);
			$h_data=$this->Order_management_model->GetDataForHistoryByPi($pi_id);
			// =========================
			// HISTORY CREATE
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$ip_addr=$_SERVER['REMOTE_ADDR'];
			if($new_priority=='Y'){
				$message="The Order has been locked on ".datetime_db_format_to_display_format_ampm(date('Y-m-d H:i:s'));
			}	
			else{
				$message="The Order has been un-locked on ".datetime_db_format_to_display_format_ampm(date('Y-m-d H:i:s'));
			}				
			
			$comment_title=OM_LOCK_CHANGE;
			$historydata=array(
						'lead_id'=>$h_data['lead_id'],
						'lead_opportunity_id'=>$h_data['lead_opportunity_id'],
						'lowp'=>$h_data['lowp'],
						'source_id'=>$h_data['source_id'],
						'po_pi_id'=>$h_data['po_pi_id'],
						'title'=>$comment_title,						
						'comment'=>addslashes($message),
						'updated_by'=>$update_by,
						'created_at'=>date("Y-m-d H:i:s"),						
						'ip_address'=>$ip_addr
						);
			$this->Order_management_model->CreateHistory($historydata);
			// HISTORY CREATE
			// =========================
			$status_str = "success";
		}
		else{
			$status_str = "fail";
		}	
		
		$result["status"] = $status_str;  
        echo json_encode($result);
		exit(0);
	}

	public function tag_permission_link_wise_assign_user_view_rander_ajax()
	{
		$data=NULL;
		$permission_link_id=$this->input->post('permission_link_id');			
		$data['permission_link_id']=$permission_link_id;		
		$data['user_list']=$this->Order_management_model->GetUserWhoAreNotAssignedToPermissionLink($permission_link_id);
		$this->load->view('admin/order_management/tag_permission_link_wise_assign_user_view_modal_ajax',$data);
	}

	function tag_assign_user_to_link_update()
	{
		$permission_link_id=$this->input->get('permission_link_id');
		$user_id=$this->input->get('user_id');
		if($user_id!='' && $permission_link_id!=''){
			$user_id_arr=explode(",",$user_id);
			if(count($user_id_arr)){
				foreach($user_id_arr AS $val){
					if($val){
						$chk_duplicate=$this->Order_management_model->chk_duplicate_link_wise_assigned_user($permission_link_id,$val);
						if($chk_duplicate==0){
							$post_data=array(
								"om_permission_link_id"=>$permission_link_id,
								"user_id"=>$val,
								"created_at"=>date("Y-m-d H:i:s")
								);
							$this->Order_management_model->add_link_wise_assigned_user($post_data);
						}
					}
										
				}
			}
		}
		$return['status'] = 'success';
		echo json_encode($return);
		exit(0);
	}

	public function untag_permission_link_wise_assign_user_view_rander_ajax()
	{
		$data=NULL;
		$permission_link_id=$this->input->post('permission_link_id');			
		$data['permission_link_id']=$permission_link_id;		
		$data['user_list']=$this->Order_management_model->GetUserWhoAreAssignedToPermissionLink($permission_link_id);
		$this->load->view('admin/order_management/untag_permission_link_wise_assign_user_view_modal_ajax',$data);
	}

	function untag_assign_user_to_link_update()
	{
		$permission_link_id=$this->input->get('permission_link_id');
		$user_id=$this->input->get('user_id');
		if($permission_link_id!='' && $user_id!=''){
			$this->Order_management_model->delete_link_wise_assigned_user_by_linkIdAndUserId($permission_link_id,$user_id);
		}
		$return['status'] = 'success';
		echo json_encode($return);
		exit(0);
	}
	
}