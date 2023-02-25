<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	private $api_access_token = '';
	function __construct()
	{
		parent :: __construct();
		is_admin_logged_in();
		init_admin_element();
		$this->api_access_token = get_api_access_token();    
		$this->load->model(array("user_model","countries_model","states_model","cities_model","menu_model","product_model","lead_model","customer_model","Package_model","dashboard_model","Source_model","Setting_model"));	
		$this->load->model('Department_model','department_model');
	}

	public function index()
	{	
		$data = array(); 
		$session_data = $this->session->userdata('admin_session_data');
		
		$user_id = $session_data['user_id'];
		$user_type = $session_data['user_type'];
		
		// ===========================================================
		// USER TREE VIEW
		if(strtolower($user_type)=='admin')
   		{
   			//$m_id=$this->user_model->get_manager_id($user_id);
   			$m_id=0;
   		}
   		else
   		{	
   			$m_id=$this->user_model->get_manager_id($user_id);
   		}
   		
	    $list['rows'] = $this->user_model->get_user_tree_list($user_id,0,$user_type);
	    $list['user_id'] =	$user_id;    
	    $list['user_type'] =	$user_type;  
	    $data['user_list_treeview'] = $this->load->view('admin/dashboard/user_tree_view_ajax',$list,TRUE);
		// USER TREE VIEW
		// ===========================================================
		
		// ===========================================================
		// COUNT SUMMARY REPORT
		// $data['company_count']=$this->customer_model->get_company_count();
		// $data['lead_count']=$this->lead_model->get_lead_count($user_type,$user_id);		
		// $data['lead_list']=$this->lead_model->get_lead_list_summery($user_type,$user_id);	

		
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type']; 
	    			
		
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);		
   		array_push($tmp_u_ids, $user_id);
   		$tmp_u_ids_str=implode(",", $tmp_u_ids);   		
		// $data['user_list']=$this->user_model->GetUserList($tmp_u_ids_str);

		$data['selected_user_id']=$tmp_u_ids_str;
		$data['currency_list']=$this->dashboard_model->GetCurrencyList();
		$data['month_list']=get_months(12);
		$data['year_list']=get_fy(3);
		$data['source_list']=$this->Source_model->get_source();

		$company_info=$this->Setting_model->GetCompanyData();   
		$c2c_api_dial_url=$company_info['c2c_api_dial_url'];
		$c2c_api_userid=$company_info['c2c_api_userid'];
		$c2c_api_password=$company_info['c2c_api_password'];
		$c2c_api_client_name=$company_info['c2c_api_client_name']; 	
		if($c2c_api_dial_url!="" && $c2c_api_userid!="" && $c2c_api_password!="" && $c2c_api_client_name!="")
		{
		$c2c_is_active='Y';
		}
		else
		{
		$c2c_is_active='N';
		} 	
   	 	$data['c2c_is_active']=$c2c_is_active;
		$this->load->view('admin/dashboard/dashboard_view',$data);
	}


	function get_dashboard_summery_count()
	{
	    
	    // FILTER DATA
		$arg['filter_is_count_or_percentage']=$this->input->get('filter_is_count_or_percentage');
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$list['is_count_or_percentage']=$arg['filter_is_count_or_percentage'];
    	$list['rows']=$this->dashboard_model->get_dashboard_summery_count($arg);		
	    $html = '';	    
	    $html = $this->load->view('admin/dashboard/rander_dashboard_summery_count_view_ajax',$list,TRUE);	
		
	    $data =array (
	       "html"=>$html
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	
	function get_latest_lead()
	{	    
	    // FILTER DATA		
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['start']=0;
		$arg['limit']=20;
    	$list['rows']=$this->dashboard_model->get_latest_lead($arg);		
	    $html = '';	    
	    $html = $this->load->view('admin/dashboard/rander_latest_lead_view_ajax',$list,TRUE);			
	    $data =array (
	       "html"=>$html
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	
	function get_pending_followup_lead()
	{
	    
	    // FILTER DATA		
		// $arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		// $arg['start']=0;
		// $arg['limit']=20;
		// $list['rows']=$this->dashboard_model->get_pending_followup_lead($arg);

		$limit=$this->input->get('limit');
    	$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type']; 
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
   		$tmp_u_ids_str=implode(",", $tmp_u_ids);
   		$arg['u_ids_str']=$tmp_u_ids_str;
   		$arg['al_stages']=$this->lead_model->get_type_wise_lead_stage('AL');
   		$arg['limit']=$limit;
   		$list['u_ids_str']=$tmp_u_ids_str;
		$list['user_wise_pending_followup_count']=$this->dashboard_model->get_user_wise_pending_followup($arg);

	    $html = '';	    
	    $html = $this->load->view('admin/dashboard/rander_pending_followup_lead_view_ajax',$list,TRUE);
	    $data =array (
	       "html"=>$html
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function get_unfollowed_leads_by_user()
	{
		// FILTER DATA
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');
		$list['rows']=$this->dashboard_model->get_unfollowed_leads_by_user($arg);
		// $session_data=$this->session->userdata('admin_session_data');
		// $user_id=$session_data['user_id'];
		// $user_type=$session_data['user_type']; 
		// $tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
		// array_push($tmp_u_ids, $user_id);
		// $tmp_u_ids_str=implode(",", $tmp_u_ids);
		// $list['u_ids_str']=$tmp_u_ids_str;
		// $list['rows']=$this->dashboard_model->get_unfollowed_leads_by_user($tmp_u_ids_str,$limit);

	    $html = '';	    
	    $html = $this->load->view('admin/dashboard/rander_unfollowed_leads_by_user_view_ajax',$list,TRUE);
	    $data =array (
	       "html"=>$html
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function get_product_vs_leads()
	{
    	$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		$limit=$this->input->get('limit');
   		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
   		if($limit>0)
   		{
   			$start=empty($start)?0:($start-1)*$limit;
		    $arg['limit']=$limit;
		    $arg['start']=$start;
   		}
   		else
   		{   			
		    $arg['limit']="";
		    $arg['start']="";
   		}
   		
   		// print_r($arg); die();
		$list['rows']=$this->dashboard_model->get_product_vs_leads($arg);
	    $html = '';	    
	    $html = $this->load->view('admin/dashboard/rander_product_vs_leads_view_ajax',$list,TRUE);
	    $data =array (
	       "html"=>$html
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit();
	}
	
	// AJAX PAGINATION START
	function get_user_activity_report_ajax()
	{
	    $start = $this->input->get('page'); 
	    $this->load->library('pagination');
	    $limit=10;
	    $config = array();
	    $list=array();
	    $arg=array();
	    
	    // FILTER DATA
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');
	    //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->dashboard_model->get_user_activity_report_count($arg);	    
	    $config['per_page'] = $limit;
	    $config['uri_segment']=4;
	    $config['num_links'] = 2;
	    $config['use_page_numbers'] = TRUE;
	   //$config['full_tag_open'] = '<div id="paginator" class="dataTables_paginate paging_bootstrap">';
	    $config['attributes'] = array('class' => 'uar_report_page');
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
		$list['rows']=$this->dashboard_model->get_user_activity_report($arg);	
		$list['currency_list']=$this->product_model->GetCurrencyList();		
	    $table = '';	    
	    $table = $this->load->view('admin/dashboard/rander_user_activity_report_view_ajax',$list,TRUE);
	
		// PAGINATION COUNT INFO SHOW: START
		$tmp_start=($start+1);		
		$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
	    $page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
		// PAGINATION COUNT INFO SHOW: END
	    $data =array (
	       "table"=>$table,
	       "page"=>$page_link,
		   "page_record_count_info"=>$page_record_count_info
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	// AJAX PAGINATION END

	// AJAX PAGINATION START
	function get_lead_source_vs_conversion_report_ajax()
	{
	    $start = $this->input->get('page'); 
	    $this->load->library('pagination');
	    $limit=10;
	    $config = array();
	    $list=array();
	    $arg=array();
	    
	    // FILTER DATA
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');
	    //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->dashboard_model->get_lead_source_vs_conversion_report_count($arg);	    
	    $config['per_page'] = $limit;
	    $config['uri_segment']=4;
	    $config['num_links'] = 2;
	    $config['use_page_numbers'] = TRUE;
	   //$config['full_tag_open'] = '<div id="paginator" class="dataTables_paginate paging_bootstrap">';
	    $config['attributes'] = array('class' => 'svc_report_page');
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
		$list['rows']=$this->dashboard_model->get_lead_source_vs_conversion_report($arg);	
		$list['currency_list']=$this->product_model->GetCurrencyList();		
	    $table = '';	    
	    $table = $this->load->view('admin/dashboard/rander_lead_source_vs_conversion_report_view_ajax',$list,TRUE);
	
		// PAGINATION COUNT INFO SHOW: START
		$tmp_start=($start+1);		
		$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
	    $page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
		// PAGINATION COUNT INFO SHOW: END
	    $data =array (
	       "table"=>$table,
	       "page"=>$page_link,
		   "page_record_count_info"=>$page_record_count_info
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	// AJAX PAGINATION END

	// AJAX PAGINATION START
	function get_lead_c2c_report_ajax()
	{
	    $list1=array();
	    $list2=array();
	    $arg=array();	    
	    // FILTER DATA
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');
		$list1['rows']=$this->dashboard_model->get_user_wise_c2c_report($arg);
		$list2['rows']=$this->dashboard_model->get_date_wise_c2c_report($arg);
	    $table1 = '';	   
	    $table2 = '';	    
	    $table1 = $this->load->view('admin/dashboard/rander_lead_c2c_report1_view_ajax',$list1,TRUE);
	    $table2 = $this->load->view('admin/dashboard/rander_lead_c2c_report2_view_ajax',$list2,TRUE);	
		
	    $data =array (
	       "table1"=>$table1,
	       "table2"=>$table2
	   	);
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	// AJAX PAGINATION END

	// AJAX PAGINATION START
	function get_lead_by_source_report_ajax()
	{
	    $start = $this->input->get('page'); 
	    $this->load->library('pagination');
	    $limit=30;
	    $config = array();
	    $list=array();
	    $arg=array();
	    
	    // FILTER DATA
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');
	    //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    //$config['total_rows'] = $this->dashboard_model->get_user_activity_report_count($arg);
	    $config['total_rows'] = 30;
	    $config['per_page'] = $limit;
	    $config['uri_segment']=3;
	    $config['num_links'] = 2;
	    $config['use_page_numbers'] = TRUE;
	   //$config['full_tag_open'] = '<div id="paginator" class="dataTables_paginate paging_bootstrap">';
	    $config['attributes'] = array('class' => 'sales_report_page');
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
		$list['rows']=$this->dashboard_model->get_lead_by_source_report($arg);	
		//$list['currency_list']=$this->product_model->GetCurrencyList();		
	    $table = '';	    
	    $table = $this->load->view('admin/dashboard/rander_lead_by_source_report_view_ajax',$list,TRUE);
	
		// PAGINATION COUNT INFO SHOW: START
		$tmp_start=($start+1);		
		$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
	    $page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
		// PAGINATION COUNT INFO SHOW: END
	    $data =array (
	       "table"=>$table,
	       "page"=>$page_link,
		   "page_record_count_info"=>$page_record_count_info
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	// AJAX PAGINATION END

	// AJAX PAGINATION START
	function get_lead_source_vs_quality_report_ajax()
	{
	    $start = $this->input->get('page'); 
	    $this->load->library('pagination');
	    $limit=30;
	    $config = array();
	    $list=array();
	    $arg=array();
	    
	    // FILTER DATA
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');
	    //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    //$config['total_rows'] = $this->dashboard_model->get_user_activity_report_count($arg);
	    $config['total_rows'] = 30;
	    $config['per_page'] = $limit;
	    $config['uri_segment']=3;
	    $config['num_links'] = 2;
	    $config['use_page_numbers'] = TRUE;
	   //$config['full_tag_open'] = '<div id="paginator" class="dataTables_paginate paging_bootstrap">';
	    $config['attributes'] = array('class' => 'sales_report_page');
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
		$list['rows']=$this->dashboard_model->get_lead_source_vs_quality_report($arg);	
		//$list['currency_list']=$this->product_model->GetCurrencyList();		
	    $table = '';	    
	    $table = $this->load->view('admin/dashboard/rander_lead_source_vs_quality_report_view_ajax',$list,TRUE);
	
		// PAGINATION COUNT INFO SHOW: START
		$tmp_start=($start+1);		
		$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
	    $page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
		// PAGINATION COUNT INFO SHOW: END
	    $data =array (
	       "table"=>$table,
	       "page"=>$page_link,
		   "page_record_count_info"=>$page_record_count_info
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	// AJAX PAGINATION END

	// AJAX PAGINATION START
	function get_lead_lost_reason_vs_lead_source_report_ajax()
	{
	    $start = $this->input->get('page'); 
	    $this->load->library('pagination');
	    $limit=30;
	    $config = array();
	    $list=array();
	    $arg=array();
	    
	    // FILTER DATA
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');
		$arg['filter_source_id']=$this->input->get('filter_source_id');
	    //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    //$config['total_rows'] = $this->dashboard_model->get_user_activity_report_count($arg);
	    $config['total_rows'] = 30;
	    $config['per_page'] = $limit;
	    $config['uri_segment']=3;
	    $config['num_links'] = 2;
	    $config['use_page_numbers'] = TRUE;
	   //$config['full_tag_open'] = '<div id="paginator" class="dataTables_paginate paging_bootstrap">';
	    $config['attributes'] = array('class' => 'sales_report_page');
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
		$list['rows']=$this->dashboard_model->get_lead_lost_reason_vs_lead_source_report($arg);	
		//$list['currency_list']=$this->product_model->GetCurrencyList();		
	    $table = '';
	    $list['source_list']=$this->Source_model->get_source();	    
	    $table = $this->load->view('admin/dashboard/rander_lead_lost_reason_vs_lead_source_report_view_ajax',$list,TRUE);
	
		// PAGINATION COUNT INFO SHOW: START
		$tmp_start=($start+1);		
		$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
	    $page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
		// PAGINATION COUNT INFO SHOW: END
	    $data =array (
	       "table"=>$table,
	       "page"=>$page_link,
		   "page_record_count_info"=>$page_record_count_info
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	// AJAX PAGINATION END

	// AJAX PAGINATION START
	function get_lead_vs_orders_report_ajax__old()
	{
	    $start = $this->input->get('page'); 
	    $this->load->library('pagination');
	    $limit=30;
	    $config = array();
	    $list=array();
	    $arg=array();
	    
	    // FILTER DATA
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');
	    //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    //$config['total_rows'] = $this->dashboard_model->get_user_activity_report_count($arg);
	    $config['total_rows'] = 30;
	    $config['per_page'] = $limit;
	    $config['uri_segment']=3;
	    $config['num_links'] = 2;
	    $config['use_page_numbers'] = TRUE;
	   //$config['full_tag_open'] = '<div id="paginator" class="dataTables_paginate paging_bootstrap">';
	    $config['attributes'] = array('class' => 'sales_report_page');
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
		$list['rows']=$this->dashboard_model->get_lead_by_source_report($arg);	
		//$list['currency_list']=$this->product_model->GetCurrencyList();		
	    $table = '';	    
	    $table = $this->load->view('admin/dashboard/rander_lead_vs_orders_report_view_ajax',$list,TRUE);
	
		// PAGINATION COUNT INFO SHOW: START
		$tmp_start=($start+1);		
		$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
	    $page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
		// PAGINATION COUNT INFO SHOW: END
	    $data =array (
	       "table"=>$table,
	       "page"=>$page_link,
		   "page_record_count_info"=>$page_record_count_info
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function get_lead_vs_orders_report_ajax()
	{	    
	    $list=array();
	    $arg=array();
	    
	    // FILTER DATA
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_daily_sales_report_group_by']=$this->input->get('filter_daily_sales_report_group_by');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');	    
		$list['group_by']=$arg['filter_daily_sales_report_group_by'];
		$list['rows']=$this->dashboard_model->get_lead_vs_order_report($arg);	
		//$list['currency_list']=$this->product_model->GetCurrencyList();		
	    $table = '';	
	    $table = $this->load->view('admin/dashboard/rander_lead_vs_orders_report_view_ajax',$list,TRUE); 		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	// AJAX PAGINATION END

	// AJAX PAGINATION START
	function get_daily_sales_report_ajax()
	{

	    $start = $this->input->get('page'); 
	    $this->load->library('pagination');
	    $limit=10;
	    $config = array();
	    $list=array();
	    $arg=array();
	    
	    // FILTER DATA
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_daily_sales_report_group_by']=$this->input->get('filter_daily_sales_report_group_by');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');
	    //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->dashboard_model->get_daily_sales_report_count($arg);
	    $config['per_page'] = $limit;
	    $config['uri_segment']=4;
	    $config['num_links'] = 2;
	    $config['use_page_numbers'] = TRUE;
	   //$config['full_tag_open'] = '<div id="paginator" class="dataTables_paginate paging_bootstrap">';
	    $config['attributes'] = array('class' => 'sales_report_page');
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
		$list['group_by']=$arg['filter_daily_sales_report_group_by'];
		$list['rows']=$this->dashboard_model->get_daily_sales_report($arg);	
		$list['currency_list']=$this->product_model->GetCurrencyList();		
	    $table = '';	    
	    $table = $this->load->view('admin/dashboard/rander_daily_sales_report_view_ajax',$list,TRUE);
	
		// PAGINATION COUNT INFO SHOW: START
		$tmp_start=($start+1);		
		$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
	    $page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
		// PAGINATION COUNT INFO SHOW: END
	    $data =array (
	       "table"=>$table,
	       "page"=>$page_link,
		   "page_record_count_info"=>$page_record_count_info
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	// AJAX PAGINATION END
	
	// AJAX PAGINATION START
	function get_business_report_weekly_ajax()
	{
	    $start = $this->input->get('page'); 
	    $this->load->library('pagination');
	    $limit=31;
	    $config = array();
	    $list=array();
	    $arg=array();
	    
	    // FILTER DATA
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_business_report_weekly_period']=$this->input->get('filter_business_report_weekly_period');
	    //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->dashboard_model->get_business_report_weekly_count($arg);
	    $config['per_page'] = $limit;
	    $config['uri_segment']=3;
	    $config['num_links'] = 1;
	    $config['use_page_numbers'] = TRUE;
	   //$config['full_tag_open'] = '<div id="paginator" class="dataTables_paginate paging_bootstrap">';
	    $config['attributes'] = array('class' => 'myclass','data-viewtype'=>$view_type);
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
		$list['currency_list']=$this->dashboard_model->GetCurrencyList();
    	$list['rows']=$this->dashboard_model->get_business_report_weekly($arg);		
	    $table = '';	    
	    $table = $this->load->view('admin/dashboard/rander_business_report_weekly_view_ajax',$list,TRUE);
	
		// PAGINATION COUNT INFO SHOW: START
		$tmp_start=($start+1);		
		$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
	    $page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
		// PAGINATION COUNT INFO SHOW: END
	    $data =array (
	       "table"=>$table,
	       "page"=>$page_link,
		   "page_record_count_info"=>$page_record_count_info
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	// AJAX PAGINATION END
	// AJAX PAGINATION START
	function get_business_report_monthly_ajax()
	{
	    $start = $this->input->get('page'); 
	    $this->load->library('pagination');
	    $limit=12;
	    $config = array();
	    $list=array();
	    $arg=array();
	    
	    // FILTER DATA
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_business_report_monthly_period']=$this->input->get('filter_business_report_monthly_period');
	    //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->dashboard_model->get_business_report_monthly_count($arg);
	    $config['per_page'] = $limit;
	    $config['uri_segment']=3;
	    $config['num_links'] = 1;
	    $config['use_page_numbers'] = TRUE;
	   //$config['full_tag_open'] = '<div id="paginator" class="dataTables_paginate paging_bootstrap">';
	    $config['attributes'] = array('class' => 'myclass','data-viewtype'=>$view_type);
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
		$list['currency_list']=$this->dashboard_model->GetCurrencyList();
    	$list['rows']=$this->dashboard_model->get_business_report_monthly($arg);		
	    $table = '';	    
	    $table = $this->load->view('admin/dashboard/rander_business_report_monthly_view_ajax',$list,TRUE);
	
		// PAGINATION COUNT INFO SHOW: START
		$tmp_start=($start+1);		
		$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
	    $page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
		// PAGINATION COUNT INFO SHOW: END
	    $data =array (
	       "table"=>$table,
	       "page"=>$page_link,
		   "page_record_count_info"=>$page_record_count_info
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	// AJAX PAGINATION END

	public function lead_health_chrt()
	{
		$time=strtotime(date('Y-m-d'));
		$month=date("F",$time);
		if($month<=6){
			$categories=array('JAN','FEB','MAR','APR','MAY','JUN');
		}else{
			$categories=array('JUL','AUG','SEP','OCT','NOV','DEC');
		}
        $data=$this->graph_model->graph_lead_health_anlys();
        $total=[];
        $regretted=[];
        $won=[];
        $lost=[];
        if(!empty($data)){
    		foreach ($data as $row) {
	        	$total[]=(int)$row->total;
	        	$regretted[]=(int)$row->regretted;
	        	$won[]=(int)$row->won;
	        	$lost[]=(int)$row->lost;
	        }
    	}
        $data=array(
        	array('name'=>'Total Leads','data'=> $total),
         	array('name'=> 'Regretted','data'=> $regretted), 
         	array('name'=> 'Deal Won','data'=> $won),
         	array('name'=> 'Deal Lost','data'=> $lost)
        );
        /*echo '<pre>';
        print_r($data);die;*/
        echo json_encode(['data'=>$data,'categories'=>$categories]);
	}

	public function lead_revenue_growth()
	{
		$time=strtotime(date('Y-m-d'));
		$month=date("F",$time);
		if($month<=6){
			$categories=array('JAN','FEB','MAR','APR','MAY','JUN');
		}else{
			$categories=array('JUL','AUG','SEP','OCT','NOV','DEC');
		}
        $data=$this->graph_model->lead_revenue_growth();
        $won=[];
        $revenue=[];
        $avg_order_value=[];
        if(!empty($data)){
	        foreach ($data as $row) {
	        	$won[]=(float)$row->won;
	        	$revenue[]=(float)$row->revenue;
	        	$avg_order_value[]=(float)$row->avg_order_value;
	        }
	    }
        $data=array(
         	array('name'=> 'Deal Won','data'=> $won),
         	array('name'=> 'Revenue(In Lac INR)','data'=> $revenue),
         	array('name'=> 'Avg. Order Value','data'=> $avg_order_value), 
        );
        /*echo '<pre>';
        print_r($data);die;*/
        echo json_encode(['data'=>$data,'categories'=>$categories]);
	}

	public function lead_source_analysis()
	{
		//$categories=array('INDIAMART','ALIBABA','TRADEINDIA','FACEBOOK','DIRECT','AGENCY');
		$categories=$this->graph_model->get_source_list();
        $data=$this->graph_model->lead_source_analysis();
        $won=[];
        $lost=[];
        $revenue=[];
        $in_process=[];
        if(!empty($data)){
    		foreach ($data as $row) {
	        	$won[]=(float)$row->won;
	        	$lost[]=(float)$row->lost;
	        	$in_process[]=(float)$row->in_process;
	        	$revenue[]=(float)$row->revenue;
	        }
    	}
        $data=array(
         	array('name'=> 'Deal Won','data'=> $won),
         	array('name'=> 'Deal Lost','data'=> $lost),
         	array('name'=> 'In Process','data'=> $in_process),
         	array('name'=> 'Revenue(In Lac INR)','data'=> $revenue)
        );
        /*echo '<pre>';
        print_r($data);die;*/
        echo json_encode(['data'=>$data,'categories'=>$categories]);
	}

	public function lead_to_conversion()
	{
		//$categories=array('INDIAMART','ALIBABA','TRADEINDIA','FACEBOOK','DIRECT','AGENCY');
		$categories=$this->graph_model->get_source_list();
        $data=$this->graph_model->lead_to_conversion();
        $count=[];
        if(!empty($data)){
    		foreach ($data as $row) {
	        	$count[]=(float)$row->count;
	        }
    	}
        $data=array(
         	array('name'=> 'Conversion','data'=> $count),
        );
        /*echo '<pre>';
        print_r($data);die;*/
        echo json_encode(['data'=>$data,'categories'=>$categories]);
	}

	public function lead_lost_reason(){
        $data=$this->graph_model->lead_lost_reason();
        $count=[];
		foreach ($data as $row) {
			if($row->count){
        		$count[]=array('name'=>$row->name,'y'=>(float)$row->count,'sliced'=>'true','selected'=>'true');
			}
        }
    	$data[]=array('name'=>'Percentage','colorByPoint'=>'true','data'=>$count);
        echo json_encode(['data'=>$data]);
	}

	function get_user_wise_pending_followup()
	{		
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type']; 
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
   		$tmp_u_ids_str=implode(",", $tmp_u_ids);
   		$arg['u_ids_str']=$tmp_u_ids_str;
   		$arg['al_stages']=$this->lead_model->get_type_wise_lead_stage('AL');
   		$arg['limit']='';
   		$list['u_ids_str']=$tmp_u_ids_str;
		$list['user_wise_pending_followup_count']=$this->dashboard_model->get_user_wise_pending_followup($arg);
	    $html = $this->load->view('admin/dashboard/rander_user_wise_pending_followup_view_ajax',$list,TRUE);
		//$html =$list['user_wise_pending_followup_count'];
	    $data =array (
	       "html"=>$html
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function get_base64_encode_for_lead_list_filter()
	{		
		$filter_selected_user_id=$this->input->get('filter_selected_user_id');
		$filter_by=$this->input->get('filter_by');
		$url_str=base64_encode($filter_selected_user_id.'#'.$filter_by);
	    $data =array (
	       "url_str"=>$url_str
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit;
	}

	function download_user_activity_report_csv()
    {
    	
    	$show_daterange_html_uar=$this->input->get('show_daterange_html_uar');
        // FILTER DATA
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');
		$total_rows = $this->dashboard_model->get_user_activity_report_count($arg);	
		$arg['limit']=$total_rows;
	    $arg['start']=0;	

		$rows=$this->dashboard_model->get_user_activity_report($arg);	
		$currency_list=$this->product_model->GetCurrencyList();       
        $today_datetime=date('Y-m-d H:i:s');
        $array=array();        
        $array[] = array('User Activity Reports '.$show_daterange_html_uar.' ');

        $revenue='';
        foreach($currency_list AS $currency)
        {
        	$revenue .=$currency->code.'/'; 
        }       
        $revenue_str=rtrim($revenue,'/');  
        $array[] = array('');
        $array[] = array(
                        'Users',
                        'Lead Assigned',
                        'Lead Updated',
						'Lead Quoted',
						'Lead Lost',
                        'Lead Won',
                        $revenue_str,                    
                        'Auto-Regreted',
                        'Auto-Deal Lost'
                        );
        
        
        if($total_rows > 0)
        {

            foreach ($rows as $row) 
            {
				$str=$row->total_revenue_wise_currency;
				$str_arr=explode("@",$str);
				$total_revenue_wise_currency_arr=array();
				foreach($str_arr AS $arr_val1)
				{					
					$arr2=explode(",",$arr_val1);
					$arr4=array();
					foreach($arr2 AS $arr_val2)
					{
						$arr3=explode("#",$arr_val2);
						$arr4=array($arr3[1]=>$arr3[0]);
						array_push($total_revenue_wise_currency_arr,$arr4);
					}
				}	

				$existing_rev_tmp='';
				if(count($currency_list)){
					foreach($currency_list AS $currency){
						$existing_rev=0;
						foreach($total_revenue_wise_currency_arr AS $rev_curr_val)
						{							
							if(isset($rev_curr_val[$currency->code]))
							{
								$existing_rev=$rev_curr_val[$currency->code]+$existing_rev;
							}
						}
						$existing_rev_tmp .= number_format($existing_rev,2).'/';
					}
					$existing_rev_tmp=rtrim($existing_rev_tmp,'/');  
				}
                $array[] = array(
                $row->assigned_user_name,
                $row->total_new_lead_count,
                $row->total_updated_lead_count,
                $row->total_quoted_lead_count,
				$row->total_deal_lost_lead_count,
				$row->total_deal_won_lead_count,
                $existing_rev_tmp,
                $row->total_auto_regretted_lead_count,  
                $row->total_auto_deal_lost_lead_count
                );
            }
        }       

        $tmpName='User_Activity_Reports';
        $tmpDate =  $today_datetime;
        $csvFileName = $tmpName."_".$tmpDate.".csv";

        $this->load->helper('csv');
        //query_to_csv($array, TRUE, 'Attendance_list.csv');
        array_to_csv($array, $csvFileName);
        return TRUE;
    }

    function download_lead_source_vs_conversion_report_csv()
    {
    	$show_daterange_html_svc=$this->input->get('show_daterange_html_svc');
    	// FILTER DATA
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');	
	    $total_rows = $this->dashboard_model->get_lead_source_vs_conversion_report_count($arg);		
		$arg['limit']=$total_rows;
	    $arg['start']=0;	    	
		$rows=$this->dashboard_model->get_lead_source_vs_conversion_report($arg);
		$currency_list=$this->product_model->GetCurrencyList();       
        $today_datetime=date('Y-m-d H:i:s');
        $array=array();        
        $array[] = array('Lead Source Vs. Conversion Reports '.$show_daterange_html_svc.' ');

          
        $array[] = array();        
		$array[2]=array();
		$array[2][]='Source';
		$array[2][]='Lead Assigned';
		$array[2][]='Lead Updated';
		$array[2][]='Lead Quoted';
		$array[2][]='Lead Lost';
		$array[2][]='Lead Won';
		foreach($currency_list AS $currency)
        {
			$array[2][]=$currency->code;
        } 		
		$array[2][]='Auto-Regreted';
		$array[2][]='Auto-Deal Lost';        
        
        if($total_rows > 0)
        {
			$index=3;
            foreach ($rows as $row) 
            {
				$str=$row->total_revenue_wise_currency;
				$str_arr=explode("@",$str);
				$total_revenue_wise_currency_arr=array();
				foreach($str_arr AS $arr_val1)
				{				
					$arr2=explode(",",$arr_val1);
					$arr4=array();
					foreach($arr2 AS $arr_val2)
					{
					   $arr3=explode("#",$arr_val2);
					   $arr4=array($arr3[1]=>$arr3[0]);
					   array_push($total_revenue_wise_currency_arr,$arr4);
					}				
				} 

				$array[$index][]=$row->source_name;
				$array[$index][]=$row->total_new_lead_count;
				$array[$index][]=$row->total_updated_lead_count;
				$array[$index][]=$row->total_quoted_lead_count;
				$array[$index][]=$row->total_deal_lost_lead_count;
				$array[$index][]=$row->total_deal_won_lead_count;				
				foreach($currency_list AS $currency)
				{
					$existing_rev=0;
					foreach($total_revenue_wise_currency_arr AS $rev_curr_val)
					{							
						if(isset($rev_curr_val[$currency->code]))
						{
							$existing_rev=$rev_curr_val[$currency->code]+$existing_rev;
						}
					}
					$array[$index][]=$existing_rev;					
				} 		
				$array[$index][]=$row->total_auto_regretted_lead_count;
				$array[$index][]=$row->total_auto_deal_lost_lead_count;  
				$index++;
            }
        }       

        $tmpName='Lead_Source_Vs_Conversion_Reports';
        $tmpDate =  $today_datetime;
        $csvFileName = $tmpName."_".$tmpDate.".csv";

        $this->load->helper('csv');
        //query_to_csv($array, TRUE, 'Attendance_list.csv');
        array_to_csv($array, $csvFileName);
        return TRUE;
    }

    function download_get_daily_sales_report_csv()
    {
    	$show_daterange_html_sr=$this->input->get('show_daterange_html_sr');

    	// FILTER DATA
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_daily_sales_report_group_by']=$this->input->get('filter_daily_sales_report_group_by');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');
	    $total_rows = $this->dashboard_model->get_daily_sales_report_count($arg);

	    $arg['limit']=$total_rows;
	    $arg['start']=0;
		$group_by=$arg['filter_daily_sales_report_group_by'];
		$rows=$this->dashboard_model->get_daily_sales_report($arg);			    	
		
		$currency_list=$this->product_model->GetCurrencyList();       
        $today_datetime=date('Y-m-d H:i:s');
        $array=array();        
        $array[] = array('Daily Sales Report '.$show_daterange_html_sr.' ');

        $revenue='';
        foreach($currency_list AS $currency)
        {
        	$revenue .=$currency->code.'/'; 
        }       
        $revenue_str=rtrim($revenue,'/'); 

        

        $array[] = array('');
        $array[] = array(
                        'Date',
                        'New Lead',
                        'Updated',
						'Quoted',
						'Regretted',
						'Deal Lost',
                        'Lead Won',
                        $revenue_str,                    
                        'Auto-Regreted',
                        'Auto-Deal Lost'
                        );
        
        
        if($total_rows > 0)
        {

            foreach ($rows as $row) 
            {
            	$date_str=($group_by=='D')?date_db_format_to_display_format($row->date_str):$row->date_str;


				$str=$row->total_revenue_wise_currency;
				$str_arr=explode("@",$str);
				$total_revenue_wise_currency_arr=array();
				foreach($str_arr AS $arr_val1)
				{				
					$arr2=explode(",",$arr_val1);
					$arr4=array();
					foreach($arr2 AS $arr_val2)
					{
					   $arr3=explode("#",$arr_val2);
					   $arr4=array($arr3[1]=>$arr3[0]);
					   array_push($total_revenue_wise_currency_arr,$arr4);
					}				
				} 	

				$existing_rev_tmp='';
				if(count($currency_list)){
					foreach($currency_list AS $currency){
						$existing_rev=0;
						foreach($total_revenue_wise_currency_arr AS $rev_curr_val)
						{							
							if(isset($rev_curr_val[$currency->code]))
							{
								$existing_rev=$rev_curr_val[$currency->code]+$existing_rev;
							}
						}
						$existing_rev_tmp .= number_format($existing_rev,2).'/';
					}
					$existing_rev_tmp=rtrim($existing_rev_tmp,'/');  
				}
                $array[] = array(
                $date_str,
                $row->total_new_lead_count,
                $row->total_updated_lead_count,
                $row->total_quoted_lead_count,
				$row->total_regretted_lead_count,
				$row->total_deal_lost_lead_count,
				$row->total_deal_won_lead_count,
                $existing_rev_tmp,
                $row->total_auto_regretted_lead_count,  
                $row->total_auto_deal_lost_lead_count
                );
            }
        }       

        $tmpName='Daily_Sales_Report';
        $tmpDate =  $today_datetime;
        $csvFileName = $tmpName."_".$tmpDate.".csv";

        $this->load->helper('csv');
        //query_to_csv($array, TRUE, 'Attendance_list.csv');
        array_to_csv($array, $csvFileName);
        return TRUE;
    }

    function get_user_wise_c2c_report_ajax()
	{	
		$list=array();	
		
		$uid=$this->input->get('uid');
		$filter_type=$this->input->get('filter_type');

		$list=array();	    
	    $arg=array();	    
	    // FILTER DATA
	    $arg['filter_type']=$filter_type;
	    $arg['filter_selected_user_id']=$uid;
		// $arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');
		$list['rows']=$this->dashboard_model->get_particular_user_wise_c2c_report($arg);
			    	    

		$html = $this->load->view('admin/dashboard/rander_user_wise_c2c_popup_view',$list,TRUE);
	    $data =array (
	       "html"=>$html
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function get_date_wise_c2c_report_ajax()
	{	
		$list=array();	
		
		$date=$this->input->get('date');
		$filter_type=$this->input->get('filter_type');
		
		$list=array();	    
	    $arg=array();	    
	    // FILTER DATA
	    $arg['filter_type']=$filter_type;
	    $arg['filter_selected_date']=$date;
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');
		$list['rows']=$this->dashboard_model->get_particular_date_wise_c2c_report($arg);
			    	    

		$html = $this->load->view('admin/dashboard/rander_date_wise_c2c_popup_view',$list,TRUE);
	    $data =array (
	       "html"=>$html
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
}