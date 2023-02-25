<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller 
{	 
	function __construct()
	{
		parent :: __construct();
		is_admin_logged_in();
		init_admin_element();  		
		$this->load->model(array("customer_model","user_model","source_model","email_model","lead_model","countries_model","states_model","cities_model","menu_model","history_model","Email_forwarding_setting_model","Sms_forwarding_setting_model","App_model"));
		// permission checking
		// if(is_permission_available($this->session->userdata('service_wise_menu')[2]['menu_list']['menu_keyword'])===false){ 
		// 	redirect(admin_url().'dashboard', 'refresh');
		// 	exit(0);
		// }
		// end
	}
	 
	public function index()
	{
		
		// $data['total_companies']=$this->customer_model->getCompanies('count');
		// $data['paying_companies']=$this->customer_model->getPayingCompanies('count');
		// $data['domestic_companies']=$this->customer_model->getDomesticCompanies('count');
		// $data['foreign_companies']=$this->customer_model->getForeignCompanies('count');		
		// $this->load->view('admin/customer/index',$data);

		$data=array();		
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];   		   		
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
   		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		$data['user_list']=$this->user_model->GetUserList($tmp_u_ids_str);

		$data['country_list']=$this->customer_model->get_country_list($tmp_u_ids_str);
		$data['source_list']=$this->source_model->GetSourceListAll();
		$data['cus_business_type_list']=$this->customer_model->get_business_type();
		$this->load->view('admin/customer/customer_list',$data);
	}  

	public function add($flag=NULL,$c_id=NULL)
	{
		$data=array();		
		$data['flag']='';
		$data['c_id']='';
		if(isset($flag))
		{
			$data['flag']=$flag;
		}
		if(isset($c_id))
		{
			$data['c_id']=$c_id;
		}
		//$data['user_list']=$this->user_model->GetUserListAll();		
		$data['mobile']='';
		$data['email']='';
		$data['company']=array();
		$data['is_add_a_new_lead_form_show']='N';
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$data['mobile']=$this->input->post('mobile');
			$data['email']=$this->input->post('email');
			$data['company']=$this->customer_model->company_search_to_add_lead($data['mobile'],$data['email']);
			$data['source_list']=$this->source_model->GetSourceListAll();
			$data['country_list']=$this->countries_model->GetCountriesList();
			$data['is_add_a_new_lead_form_show']='Y';

			
			// user list
			$session_data=$this->session->userdata('admin_session_data');
	   		$user_id=$session_data['user_id'];
	   		$user_type=$session_data['user_type'];
	   		if($user_type=='Admin')
	   		{
	   			//$m_id=$this->user_model->get_manager_id($user_id);
	   			$m_id=0;
	   		}
	   		else
	   		{
	   			$m_id=$this->user_model->get_manager_id($user_id);
	   		}
	        $user_ids = $this->user_model->get_under_employee_ids($m_id,0);
	        array_push($user_ids, $user_id);
	        $user_ids_str=implode(',', $user_ids);
	        $data['user_list']=$this->user_model->GetUserList($user_ids_str);
	        //$data['user_list']=$this->user_model->GetUserListAll('');
	        $data['user_id']=$user_id;
			//print_r($data['company']);
		}
		
		$this->load->view('admin/customer/add_customer',$data);
	}
	
	function add_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			// COMPANY INFO
			$com_company_id = $this->input->post('com_company_id');
			$com_company_name = $this->input->post('com_company_name');
			$com_contact_person = $this->input->post('com_contact_person');
			$com_designation = $this->input->post('com_designation');
			$com_email = $this->input->post('com_email');
			$com_alternate_email = $this->input->post('com_alternate_email');
			$com_mobile_country_code = $this->input->post('com_mobile_country_code');
			$com_mobile = $this->input->post('com_mobile');
			$com_alt_mobile_country_code = $this->input->post('com_alt_mobile_country_code');
			$com_alternate_mobile = $this->input->post('com_alternate_mobile');

			$com_landline_country_code = $this->input->post('com_landline_country_code');
			$com_landline_std_code = $this->input->post('com_landline_std_code');
			$landline_number = $this->input->post('landline_number');


			$com_address = $this->input->post('com_address');
			$com_country_id = ($this->input->post('com_country_id')>0)?$this->input->post('com_country_id'):$this->input->post('com_existing_country');
			$com_state_id = ($this->input->post('com_state_id')>0)?$this->input->post('com_state_id'):$this->input->post('com_existing_state');
			$com_city_id = ($this->input->post('com_city_id')>0)?$this->input->post('com_city_id'):$this->input->post('com_existing_city');
			$com_zip = $this->input->post('com_zip');
			$com_website = $this->input->post('com_website');
			$com_source_id = ($this->input->post('com_source_id')>0)?$this->input->post('com_source_id'):$this->input->post('com_existing_source');
			$com_short_description = $this->input->post('com_short_description');

			$company_post_data=array(
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
				'website'=>$com_website,
				'company_name'=>$com_company_name,
				'address'=>$com_address,
				'city'=>$com_city_id,
				'state'=>$com_state_id,
				'country_id'=>$com_country_id,
				'zip'=>$com_zip,
				'short_description'=>$com_short_description,
				'source_id'=>$com_source_id,
				'create_date'=>date('Y-m-d'),
				'modify_date'=>date('Y-m-d H:i:s'),
				'status'=>'1'				
			);

			
			if($com_company_id)
			{
				$this->customer_model->UpdateCustomer($company_post_data,$com_company_id);
				$msg="The company successfully updated.";
			}
			else
			{
				$com_company_id=$this->customer_model->CreateCustomer($company_post_data);
				$msg="A new company successfully added.";

				// -------------------------------------------------
				// gmail listing update
				/*
				$ids=$this->lead_model->get_gmail_inbox_overview_id_by_email($com_email);
	        	$id_arr=explode(",", $ids);
	        	if(count($id_arr))
	        	{
	        		$u_data=array(
	        						'customer_id'=>$com_company_id,
	        						'customer_exist_keyword'=>'one_customer_exist',
	        						'updated_at'=>date('Y-m-d H:i:s')
	        					);
	        		for ($i=0; $i < count($id_arr) ; $i++) { 
	        			$this->lead_model->gmail_overview_update($u_data,$id_arr[$i]);
	        		}
	        	}
	        	*/
	        	// gmail listing update
	        	// -------------------------------------------------
				
			}
			$status_str='success';
			$result["status"] = $status_str;
			$result['msg']=$msg;
			$result['data']=$company_post_data;
			echo json_encode($result);
			exit(0);
		}
	}

	public function add___bkp($flag=NULL)
	{
		$data['flag']='';
		if(isset($flag))
		{
			$data['flag']=$flag;
		}
		$data['country_list']=$this->countries_model->GetCountriesList();
		$data['source_list']=$this->source_model->GetSourceListAll();
		if($this->input->post('command')=='1')
		{
			// $first_name=$this->input->post('first_name');
			// $last_name=$this->input->post('last_name');
			$contact_person=$this->input->post('contact_person');
			$email=$this->input->post('email');
			$mobile=$this->input->post('mobile');
			$address=$this->input->post('address');
			$city=$this->input->post('city');
			$state=$this->input->post('state');
			$zip=$this->input->post('zip');
			$country_id=$this->input->post('country_id');
			$company_name=$this->input->post('company_name');
			$office_phone=$this->input->post('office_phone');
			$website=$this->input->post('website');
			$flag=$this->input->post('flag');
			
			$data=array(
						'contact_person'=>$contact_person,
						'email'=>$email,
						'mobile'=>$mobile,
						'address'=>$address,
						'city'=>$city,
						'state'=>$state,
						'zip'=>$zip,
						'country_id'=>$country_id,
						'create_date'=>date("Y-m-d"),
						'company_name'=>$company_name,
						'office_phone'=>$office_phone,
						'website'=>$website,
						'status'=>'1'
						);
			$result=$this->customer_model->CreateCustomer($data);	
			if($result)
			{
				
				CheckUserSpace();
				if($this->session->userdata['new_customer']['email']!='' || $this->session->userdata['new_customer']['mobile']!='')
				{
					$this->session->userdata['new_customer']['email']=$email;
					$this->session->userdata['new_customer']['mobile']=$mobile;
					
					redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/lead/add/1/'.$result);
				}
				else
				{
					redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/customer/manage/',$data);
				}
				
			}
		}		
		$this->load->view('admin/customer/add_customer',$data);
	}
	public function add_ajax____bkp()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			// COMPANY INFO
	        $com_company_name = $this->input->post('com_company_name');
	        $com_contact_person = $this->input->post('com_contact_person');
	        $com_designation = $this->input->post('com_designation');
	        $com_email = $this->input->post('com_email');
	        $com_alternate_email = $this->input->post('com_alternate_email');
	        $com_mobile = $this->input->post('com_mobile');
	        $com_alternate_mobile = $this->input->post('com_alternate_mobile');
	        $com_address = $this->input->post('com_address');
	        $com_country_id = ($this->input->post('com_country_id')>0)?$this->input->post('com_country_id'):$this->input->post('com_existing_country');
	        $com_state_id = ($this->input->post('com_state_id')>0)?$this->input->post('com_state_id'):$this->input->post('com_existing_state');
	        $com_city_id = ($this->input->post('com_city_id')>0)?$this->input->post('com_city_id'):$this->input->post('com_existing_city');
	        $com_zip = $this->input->post('com_zip');
	        $com_website = $this->input->post('com_website');
	        $com_source_id = ($this->input->post('com_source_id')>0)?$this->input->post('com_source_id'):$this->input->post('com_existing_source');
	        $com_short_description = $this->input->post('com_short_description');

			$company_post_data=array(
				'contact_person'=>$com_contact_person,
				'designation'=>$com_designation,
				'email'=>$com_email,
				'alt_email'=>$com_alternate_email,
				'mobile'=>$com_mobile,
				'alt_mobile'=>$com_alternate_mobile,
				'website'=>$com_website,
				'company_name'=>$com_company_name,
				'address'=>$com_address,
				'city'=>$com_city_id,
				'state'=>$com_state_id,
				'country_id'=>$com_country_id,
				'zip'=>$com_zip,
				'short_description'=>$com_short_description,
				'source_id'=>$com_source_id,
				'modify_date'=>date('Y-m-d'),
				'status'=>'1'
			);
			$com_company_id=$this->customer_model->CreateCustomer($company_post_data);
			
			$status_str='success';
	        $result["status"] = $status_str;
	        $result['msg']=$_POST;
	        echo json_encode($result);
	        exit(0);
		}
	}

	public function edit($id)
	{		
		$data=array();		
		if($this->input->post('command')=='1')
		{	
			$customer_id=$this->input->post('customer_id');
	        $com_company_name = $this->input->post('com_company_name');
	        $com_contact_person = $this->input->post('com_contact_person');
	        $com_designation = $this->input->post('com_designation');
	        $com_email = $this->input->post('com_email');
	        $com_alternate_email = $this->input->post('com_alternate_email');
	        $com_mobile = $this->input->post('com_mobile');
	        $com_alternate_mobile = $this->input->post('com_alternate_mobile');
	        $com_address = $this->input->post('com_address');
	        $com_country_id = ($this->input->post('com_country_id')>0)?$this->input->post('com_country_id'):$this->input->post('com_existing_country');
	        $com_state_id = ($this->input->post('com_state_id')>0)?$this->input->post('com_state_id'):$this->input->post('com_existing_state');
	        $com_city_id = ($this->input->post('com_city_id')>0)?$this->input->post('com_city_id'):$this->input->post('com_existing_city');
	        $com_zip = $this->input->post('com_zip');
	        $gst_number = $this->input->post('gst_number');
	        $com_website = $this->input->post('com_website');
	        $com_source_id = ($this->input->post('com_source_id')>0)?$this->input->post('com_source_id'):$this->input->post('com_existing_source');
	        $com_short_description = $this->input->post('com_short_description');
			
			$company_post_data=array(
				'contact_person'=>$com_contact_person,
				'designation'=>$com_designation,
				'email'=>$com_email,
				'alt_email'=>$com_alternate_email,
				'mobile'=>$com_mobile,
				'alt_mobile'=>$com_alternate_mobile,
				'website'=>$com_website,
				'company_name'=>$com_company_name,
				'address'=>$com_address,
				'city'=>$com_city_id,
				'state'=>$com_state_id,
				'country_id'=>$com_country_id,
				'zip'=>$com_zip,
				'gst_number'=>$gst_number,
				'short_description'=>$com_short_description,
				'source_id'=>$com_source_id,
				'modify_date'=>date('Y-m-d'),
				'status'=>'1'
			);
			$this->customer_model->UpdateCustomer($company_post_data,$customer_id);	
			if($result)
			{
				//CheckUserSpace();
				redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/customer/manage');
			}
			
			
		}
		
		$data['cus_data']=$this->customer_model->GetCustomerData($id);			
		$data['country_list']=$this->countries_model->GetCountriesList();
		$data['state_list']=$this->states_model->GetStatesList($data['cus_data']->country_id);
		$data['city_list']=$this->cities_model->GetCitiesList($data['cus_data']->state);		
		$data['source_list']=$this->source_model->GetSourceListAll();
		$data['cus_business_type_list']=$this->customer_model->get_business_type();
		$this->load->view('admin/customer/edit_customer',$data);
	}
	
	public function edit_ajax()
	{		
		$data=array();		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			// COMPANY INFO
			$customer_id=$this->input->post('customer_id');
	        $com_company_name = $this->input->post('com_company_name');
	        $com_contact_person = $this->input->post('com_contact_person');
	        $com_designation = $this->input->post('com_designation');
	        $com_email = $this->input->post('com_email');
	        $com_alternate_email = $this->input->post('com_alternate_email');
	        $com_mobile = $this->input->post('com_mobile');
	        $com_alternate_mobile = $this->input->post('com_alternate_mobile');
	        $com_address = $this->input->post('com_address');
	        $com_country_id = ($this->input->post('com_country_id')>0)?$this->input->post('com_country_id'):$this->input->post('com_existing_country');
	        $com_state_id = ($this->input->post('com_state_id')>0)?$this->input->post('com_state_id'):$this->input->post('com_existing_state');
	        $com_city_id = ($this->input->post('com_city_id')>0)?$this->input->post('com_city_id'):$this->input->post('com_existing_city');
	        $com_zip = $this->input->post('com_zip');
	        $gst_number = $this->input->post('gst_number');
	        $com_website = $this->input->post('com_website');
	        $com_source_id = ($this->input->post('com_source_id')>0)?$this->input->post('com_source_id'):$this->input->post('com_existing_source');
	        $com_short_description=$this->input->post('com_short_description');
			$com_business_type_id=$this->input->post('com_business_type_id');

			$company_post_data=array(
				'contact_person'=>$com_contact_person,
				'designation'=>$com_designation,
				'email'=>$com_email,
				'alt_email'=>$com_alternate_email,
				'mobile'=>$com_mobile,
				'alt_mobile'=>$com_alternate_mobile,
				'website'=>$com_website,
				'company_name'=>$com_company_name,
				'address'=>$com_address,
				'city'=>$com_city_id,
				'state'=>$com_state_id,
				'country_id'=>$com_country_id,
				'zip'=>$com_zip,
				'gst_number'=>$gst_number,
				'short_description'=>$com_short_description,
				'source_id'=>$com_source_id,
				'business_type_id'=>$com_business_type_id,
				'modify_date'=>date('Y-m-d'),
				'status'=>'1'
			);
			//print_r($company_post_data); die();
			$this->customer_model->UpdateCustomer($company_post_data,$customer_id);
			
			$status_str='success';
	        $result["status"] = $status_str;
	        $result['msg']=$_POST;
	        echo json_encode($result);
	        exit(0);
		}
	}
	
	// AJAX PAGINATION START
	function get_list_ajax()
	{
	    $start = $this->input->get('page');
	    $view_type = $this->input->get('view_type');
	    
	    $this->load->library('pagination');
	    $limit=30;
	    $config = array();
	    $list=array();
	    $arg=array();
		$session_data=$this->session->userdata('admin_session_data');
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
   		if($user_id==1){
   			array_push($tmp_u_ids, 0);
   		}
		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		   
	    // FILTER DATA
	    $arg['filter_search_str']=$this->input->get('filter_search_str');
	    $arg['filter_sort_by']=$this->input->get('filter_sort_by'); 
	    $arg['filter_created_from_date']=$this->input->get('filter_created_from_date');
		$arg['filter_created_to_date']=$this->input->get('filter_created_to_date');
		$arg['assigned_user']=($this->input->get('filter_assigned_user'))?$this->input->get('filter_assigned_user'):$tmp_u_ids_str;	
		$arg['filter_by_company_available_for']=$this->input->get('filter_by_company_available_for');
		$arg['filter_by_is_available_company_name']=$this->input->get('filter_by_is_available_company_name');
		$arg['filter_by_is_available_email']=$this->input->get('filter_by_is_available_email');
		$arg['filter_by_is_available_phone']=$this->input->get('filter_by_is_available_phone');
		$arg['filter_last_contacted']=$this->input->get('filter_last_contacted');
		$arg['filter_last_contacted_custom_date']=$this->input->get('filter_last_contacted_custom_date');
		$arg['filter_country']=$this->input->get('filter_country');
		$arg['filter_company_type']=$this->input->get('filter_company_type');
	    $arg['source_ids']=$this->input->get('filter_by_source');
		$arg['business_type_id']=$this->input->get('filter_business_type_id');

	   //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->customer_model->get_list_count($arg);
	    $config['per_page'] = $limit;
	    $config['uri_segment']=4;
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

    	$list['rows']=$this->customer_model->get_list($arg);	
    	$list['limit']=$limit;
	    $table = '';	    
	    $table = $this->load->view('admin/customer/list_customer_view_ajax',$list,TRUE);
	
		// PAGINATION COUNT INFO SHOW: START
		$tmp_start=($start+1);		
		$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
	    $page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
		// PAGINATION COUNT INFO SHOW: END
	    $data =array (
	       "table"=>$table,
	       "page"=>$page_link,
		   "page_record_count_info"=>$page_record_count_info,
		   "total_row_count"=>$config['total_rows']
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	// AJAX PAGINATION END
	
	public function manage($category=NULL)
	{
		$data=array();		
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];   		   		
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
   		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		$data['user_list']=$this->user_model->GetUserList($tmp_u_ids_str);

		$data['country_list']=$this->customer_model->get_country_list($tmp_u_ids_str);
		$data['source_list']=$this->source_model->GetSourceListAll();
		$this->load->view('admin/customer/customer_list',$data);		
	}

	public function delete($id)
	{
		$data=array();
		$del=$this->customer_model->DeleteCustomer($id);
		CheckUserSpace();
		redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/customer/manage');
		
	}
	
	public function GetCusData_ajax($id)
	{
		$data['cus_data']=$this->customer_model->GetCustomerData($id);
		$this->load->view('admin/customer/customer_data_ajax',$data);	
		
	}

	public function customer_edit_view_rander_ajax()
	{
		$data=NULL;
		$customer_id=$this->input->post('customer_id');			
		$data['customer_id']=$customer_id;		
		$data['cus_data']=$this->customer_model->GetCustomerData($customer_id);
		$data['country_list']=$this->countries_model->GetCountriesList();
		$data['state_list']=$this->states_model->GetStatesList($data['cus_data']->country_id);
		$data['city_list']=$this->cities_model->GetCitiesList($data['cus_data']->state);
		$data['business_type_list']=$this->customer_model->get_business_type();
		$data['source_list']=$this->source_model->GetSourceListAll();
		$this->load->view('admin/customer/edit_customer_modal_ajax',$data);
	}	

	function update_customer_ajax()
    {
    	$session_data=$this->session->userdata('admin_session_data');
    	$update_by=$this->session->userdata['admin_session_data']['user_id'];
    	$id = $this->input->post('customer_id');

    	$company_name = $this->input->post('company_name');
    	$country_id = $this->input->post('country_id');
    	$contact_person = $this->input->post('contact_person');
    	$designation = $this->input->post('designation');
    	$mobile = $this->input->post('mobile');
        $alt_mobile = $this->input->post('alt_mobile');
        $landline_std_code = $this->input->post('landline_std_code');
        $landline_number = $this->input->post('landline_number');
        $office_std_code = $this->input->post('office_std_code');        
        $office_phone = $this->input->post('office_phone');
        $email = $this->input->post('email');
        $alt_email = $this->input->post('alt_email');
        $address = $this->input->post('address');
        $state = $this->input->post('state_id');
        $city = $this->input->post('city_id');
        $zip = $this->input->post('zip');
        $gst_number = $this->input->post('gst_number');
        $website = $this->input->post('website');
        $short_description = $this->input->post('short_description');     
		$source_id = $this->input->post('source_id');
        $business_type_id = $this->input->post('business_type_id');
		$reference_name = $this->input->post('reference_name');
        $country_code='';
        if($country_id)
        {
        	$country_code=get_value("phonecode","countries","id=".$country_id);
        }
        

        $post_data=array(
        				'contact_person'=>$contact_person,
        				'designation'=>$designation,
        				'email'=>$email,
        				'alt_email'=>$alt_email,
        				'mobile_country_code'=>$country_code,
        				'mobile'=>$mobile,
        				'alt_mobile_country_code'=>$country_code,
        				'alt_mobile'=>$alt_mobile,
        				'landline_country_code'=>$country_code,
        				'landline_std_code'=>$landline_std_code,
        				'landline_number'=>$landline_number,
        				'office_country_code'=>$country_code,
        				'office_std_code'=>$office_std_code,
        				'office_phone'=>$office_phone,
        				'website'=>$website,
        				'company_name'=>$company_name,
        				'address'=>$address,
        				'city'=>$city,
        				'state'=>$state,
        				'country_id'=>$country_id,
        				'zip'=>$zip,
        				'gst_number'=>$gst_number,
        				'short_description'=>$short_description,
						'source_id'=>$source_id,
						'business_type_id'=>$business_type_id,
						'reference_name'=>$reference_name,
        				'modify_date'=>date("Y-m-d H:i:s")
        				);

		$tmp_log_id=$this->customer_model->UpdateCustomer($post_data,$id);
		
		if($tmp_log_id>0)
		{
			$user_data=$this->user_model->get_employee_details($update_by);
			$company=get_company_profile();	
			$customer=$this->customer_model->GetCustomerData($id);			
			$get_update_history_log=$this->customer_model->get_company_update_log_details_by_logid($tmp_log_id);
			
			// Company Information Change id 6
			$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(6);
			$m_email=get_manager_and_skip_manager_email_arr($update_by);
			if($email_forwarding_setting['is_mail_send']=='Y')
			{
				// ============================
				// Company Information Change mail
				// START
				// $cc_mail='';
				// $to_mail=get_value("email","user","id=".$update_by);
				// $cc_mail=get_manager_and_skip_manager_email($update_by);
				// $cc_mail=($cc_mail)?$cc_mail.','.$self_cc_mail:$self_cc_mail;

			    // --------------------
		        // to mail assign logic
		        $to_mail_assign='';
		        $to_mail='';
		        if($email_forwarding_setting['is_send_mail_to_client']=='Y')
		        {
		        	//$to_mail=$customer->email;
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
		        $self_cc_mail=get_value("email","user","id=".$update_by);
		        //$update_by_name=get_value("name","user","id=".$assigned_user_id);
		        // --------------------
		        // cc mail assign logic
		        if($email_forwarding_setting['is_send_relationship_manager']=='Y')
		        {
		        	array_push($cc_mail_arr, $self_cc_mail);
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

		        if($to_mail!='')
		        {
		        	// EMAIL CONTENT
				    $e_data=array();
					$e_data['company']=$company;
					$e_data['assigned_to_user']=$user_data;
					$e_data['customer']=$customer;
					$e_data['company_field_alias']=$this->config->item('company_field_alias');
					$e_data['update_history_log']=$get_update_history_log;
			        $e_data['company_id']=$id;
			        $e_data['cc_mail']=$cc_mail;
				    $template_str = $this->load->view('admin/email_template/template/company_information_change_update_view', $e_data, true);        
				    // LEAD ASSIGNED MAIL	    
					$this->load->library('mail_lib');
				    $mail_data = array();
				    $mail_data['from_mail']     = $session_data['email'];
				    $mail_data['from_name']     = $session_data['name'];
				    $mail_data['to_mail']       = $to_mail;        
				    $mail_data['cc_mail']       = $cc_mail;               
				    $mail_data['subject']       ='LMS Update: '.$customer->company_name.' # '.$customer->id.' / Info Changed';
				    $mail_data['message']       = $template_str;
				    $mail_data['attach']        = array();
				    $this->mail_lib->send_mail($mail_data);
					// MAIL SEND
					// ===============================================
		        }
				
			}
			
		}
		


    	$status_str='success';
    	$result['company_name']=$company_name;
    	$result['contact_person']=$contact_person;
    	$result['email']=$email;
    	$result['mobile']=$mobile;
    	$result['country']=$customer->country_name;
        $result["status"] = $status_str;
        $result['msg']=$id;
        echo json_encode($result);
        exit(0); 
    }
	
	function view_company_detail_ajax()
    {
    	$id = $this->input->post('cid');
    	$cus_data=$this->customer_model->GetCustomerData($id);
    	$list['cus_data']=$cus_data;
    	$html = $this->load->view('admin/customer/customer_data_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0);
    }

    function get_company_detail_ajax()
    {
    	$id = $this->input->post('cid');
    	$cus_data=$this->customer_model->get_company_detail($id);
		$result['row'] = $cus_data;
        echo json_encode($result);
        exit(0);
    }

    function view_company_history_ajax()
    {
    	$id = $this->input->post('cid');
    	$rows=$this->customer_model->get_company_history_log($id);
    	$list['field_name_arr']=array(
									'assigned_user_id'=>'Assigned user',
    								'contact_person'=>'Contact Person',
    								'designation'=>'Designation',
									'dob'=>'Birthday',
									'doa'=>'Marriage Anniversary',
    								'email'=>'Email',
    								'alt_email'=>'Alt. Email',
									'mobile_country_code'=>'Mobile country code',
    								'mobile'=>'Mobile',
    								'alt_mobile'=>'Alt. Mobile',
									'alt_mobile_country_code'=>'Alt. Mobile country code',
									'landline_country_code'=>'Phone country code',
									'landline_std_code'=>'Phone std code',
    								'office_phone'=>'Office Phone',
									'office_country_code'=>'Office Phone country code',
    								'website'=>'Website',
    								'company_name'=>'Company Name',
    								'address'=>'Address',
    								'city'=>'City',
    								'state'=>'State',
    								'country_id'=>'Country',
    								'zip'=>'Zip',
    								'gst_number'=>'GST Number',
    								'create_date'=>'Created On',
    								'short_description'=>'Short Description',
    								'source_id'=>'Source',
    								'modify_date'=>'Modified On',
    								'status'=>'Status',
									'is_blacklist'=>'Blacklist'
    								);
    	$list['rows']=$rows;
    	$html = $this->load->view('admin/customer/customer_history_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0);
    }
	
	function view_company_wise_lead_ajax()
    {
    	$id = $this->input->post('cid');
		$filter=$this->input->post('filter');
    	$rows=$this->customer_model->get_company_wise_lead($id,$filter);    	
    	$list['rows']=$rows;
    	$html = $this->load->view('admin/customer/customer_wise_lead_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0);
    }
	
	function mail_send_to_company_ajax()
    {
    	$customer_id = $this->input->post('customer_id');
		$com_to_email = $this->input->post('com_to_email');
		$com_from_email = $this->input->post('com_from_email');
		$com_mail_subject = $this->input->post('com_mail_subject');
		$com_mail_body = $this->input->post('com_mail_body');
		
		$cus_data=$this->customer_model->get_company_detail($customer_id);
    	$company=get_company_profile();
		// ============================
		// Update Mail to client 
		// START
		$assigned_user_id=$this->session->userdata['admin_session_data']['user_id'];
		$cc_mail='';
		if($assigned_user_id!=1)
		{
			$self_cc_mail=get_value("email","user","id=".$assigned_user_id);
			$cc_mail=get_manager_and_skip_manager_email($assigned_user_id);
			$cc_mail=($cc_mail)?$cc_mail.','.$self_cc_mail:$self_cc_mail;
		}
		
		$attach_filename='';
		$this->load->library('upload', '');
		if($_FILES['com_attachment']['name'] != "")
		{
			$config=array();
			$config['upload_path'] = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/mail_attachment/";
			$config['allowed_types'] = "*";
			$config['max_size'] = '1000000'; //KB
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('com_attachment'))
			{
				//return $this->upload->display_errors();
			}
			else
			{
				$file_data = array('upload_data' => $this->upload->data());
				$attach_filename = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/mail_attachment/".$file_data['upload_data']['file_name'];			    
			}
		}
        
		// EMAIL CONTENT
	    $e_data=array();
		$e_data['company']=$company;
		$e_data['email_subject']=$com_mail_subject;
		$e_data['email_to']=$cus_data['contact_person'].'<br>'.$cus_data['company_name'];
		$e_data['email_body']=$com_mail_body;		
		$template_str = $this->load->view('admin/email_template/template/common_template_layout', $e_data, true);    

                	
        $to_mail=$com_to_email;	   
    	$this->load->library('mail_lib');
        $mail_data = array();        
        $mail_data['from_mail']     = $this->session->userdata['admin_session_data']['email'];
        $mail_data['from_name']     = $this->session->userdata['admin_session_data']['name'];
        $mail_data['to_mail']       = $to_mail;        
        $mail_data['cc_mail']       = $cc_mail;               
        $mail_data['subject']       = $com_mail_subject.' - Update from your A/C Manager';
        $mail_data['message']       = $template_str;
        $mail_data['attach']        = array($attach_filename);
        $this->mail_lib->send_mail($mail_data);
		// MAIL SEND
		// ===============================================
    	
		// ===============================================
		// History
		$session_data=$this->session->userdata('admin_session_data');
		$updated_by_user_id=$session_data['user_id'];
		$history_data=array(
				'history_type'=>'E',
				'c_id'=>$customer_id,
				'updated_by_user_id'=>$updated_by_user_id,
				'ip_address'=>$this->input->ip_address(),
				'comment'=>'A mail has been sent to '.$cus_data['contact_person'].'('.$to_mail.') by '.$this->session->userdata['admin_session_data']['name'].'('.$this->session->userdata['admin_session_data']['email'].')',
				'created_at'=>date("Y-m-d")
				);
		$tmp_log_id=$this->customer_model->company_history_add($history_data);
		
		
		$history_data_log=array(
					'company_update_history_log_id'=>$tmp_log_id,
					'from_mail'=>$this->session->userdata['admin_session_data']['email'],
					'from_name'=>$this->session->userdata['admin_session_data']['name'],
					'to_mail'=>$to_mail,
					'cc_mail'=>$cc_mail,					
					'subject'=>$com_mail_subject.' - Update from your A/C Manager',
					'body'=>$com_mail_body,
					'attachment'=>$attach_filename
					);
		$this->customer_model->company_history_email_detail_add($history_data_log);
		// History
		// ===============================================
		
							
		$result['status'] = 'success';
        echo json_encode($result);
        exit(0);
    }
	
	
	function test_email_template()
	{
		$company=get_company_profile();
		$e_data=array();
		$e_data['company']=$company;
		$e_data['email_subject']='Test Subject';
		$e_data['email_to']='Test name<br>Test Company';
		$e_data['email_body']='Test Body';
		
		echo $template_str = $this->load->view('admin/email_template/template/common_template_layout', $e_data, true);	
	}
	
	public function download($file='')
	{	
		if($file!='')
		{	
			$this->load->helper(array('download'));	
			$file_name = base64_decode($file);
			$pth    =   file_get_contents($file_name);
			force_download($file_name, $pth); 
			exit;
		}
	}

	/*
	public function change_assigned_to_ajax()
	{
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];   		   		
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
   		$tmp_u_ids_str=implode(",", $tmp_u_ids);


		$company_id=$this->input->post('c_id');
		$currassigned_to=$this->input->post('currassigned_to');
		$admin_session_data_user_id=$this->session->userdata['admin_session_data']['user_id'];
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		if($user_type=='Admin')
   		{
   			//$m_id=$this->user_model->get_manager_id($user_id);
   			$m_id=0;
   		}
   		else
   		{
   			$m_id=$this->user_model->get_manager_id($user_id);
   		}   


		$list=array();
        $list['currassigned_to']=$currassigned_to;
        $list['company_id']=$company_id;
        // $list['lead_info']=$this->lead_model->GetLeadData($lead_id);	
        //$list['user_list']=$this->user_model->GetUserListAll();
        $user_ids = $this->user_model->get_under_employee_ids($user_id,0);
        array_push($user_ids, $user_id);
        $user_ids_str=implode(',', $user_ids);
        //$list['user_list']=$this->user_model->GetUserList($user_ids_str);
		$list['user_list']=$this->user_model->GetUserList($tmp_u_ids_str);
		$list['tmp_u_ids_str']=$tmp_u_ids_str;
    	$html = $this->load->view('admin/customer/change_assigned_to_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0); 
		
	}
	*/
	public function change_assigned_to_ajax()
	{
		/*
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];   
   		if($user_type=='Admin')
   		{
   			//$m_id=$this->user_model->get_manager_id($user_id);
   			$m_id=0;
   		}
   		else
   		{
   			$m_id=$this->user_model->get_manager_id($user_id);
   		}    				   		
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
   		$tmp_u_ids_str=implode(",", $tmp_u_ids);
   		*/
		$company_id=$this->input->post('c_id');
		$currassigned_to=$this->input->post('currassigned_to');
		$lead_id=$this->input->post('lid');
		$lead_info=$this->lead_model->GetLeadData($lead_id);
		$list=array();
		$list['assigned_observer']=$lead_info->assigned_observer;
        $list['currassigned_to']=$currassigned_to;
        $list['company_id']=$company_id;
        $list['lead_id']=$lead_id;
       
        $list['user_list']=$this->user_model->GetAllUsers('0');
        // $user_ids = $this->user_model->get_under_employee_ids($user_id,0);
        // array_push($user_ids, $user_id);
        // $user_ids_str=implode(',', $user_ids);
        //$list['user_list']=$this->user_model->GetUserList($user_ids_str);
		// $list['user_list']=$this->user_model->GetUserList($tmp_u_ids_str);
		// $list['tmp_u_ids_str']=$tmp_u_ids_str;

    	$html = $this->load->view('admin/customer/change_assigned_to_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0); 
		
	}

	public function update_change_assigned_to_ajax()
    {
    	$lead_id = $this->input->post('lead_id');
    	$assigned_observer=$this->input->post('observer');
		$company_id = $this->input->post('company_id'); 
		$is_mail_send_to_client=($this->input->post('is_mail_send_to_client'))?'Y':'N';   	
    	$get_leads_by_cpmpany=$this->lead_model->get_leads_by_cpmpany($company_id);
        $assigned_to_user_id = $this->input->post('assigned_to');
        
        $assigned_by_user_id=$this->session->userdata['admin_session_data']['user_id'];

        $old_account_manager_id=get_value("assigned_user_id","customer","id=".$company_id);
        if($old_account_manager_id>0)
        {
        	$old_account_manager=get_value("name","user","id=".$old_account_manager_id);
        	/*
        	if($old_account_manager_id==$assigned_to_user_id)
	        { 
		        $result["status"] = 'error';	        
		        $result["msg"]='Oops! Please change new assign user';
		        echo json_encode($result);
		        exit(0);
	        }
	        */
        }
        else{
        	$old_account_manager='N/A';
        }
       	

       	if($assigned_to_user_id!='')
       	{
        
			$new_account_manager=get_value("name","user","id=".$assigned_to_user_id);
			
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
	        if(count($get_leads_by_cpmpany)>0 && $assigned_to_user_id!='')
	        {
	        	foreach($get_leads_by_cpmpany as $lead)
	        	{
	        		$l_id=$lead['id'];
	        		// lead update
	        		$updatedata=array();
			        $updatedata=array(
			        				'assigned_user_id'=>$assigned_to_user_id,
			        				'assigned_date'=>date("Y-m-d")
			        				);
					$this->lead_model->UpdateLead($updatedata,$l_id);

					// insert to log table
					$post_data=array();
			        $post_data=array(
									'lead_id'=>$l_id,
									'assigned_to_user_id'=>$assigned_to_user_id,
									'assigned_by_user_id'=>$assigned_by_user_id,
									'is_accepted'=>'Y',
									'assigned_datetime'=>date("Y-m-d H:i:s")
									);			
					$this->lead_model->create_lead_assigned_user_log($post_data);


			        // =========================
			        // HISTORY CREATE
			        
					$ip_addr=$_SERVER['REMOTE_ADDR'];				
					$message="Account manager has been changed from ".$old_account_manager."(Old) to ".$new_account_manager."(New)";
					$comment_title=ACCOUNT_MANAGER_CHANGE;
					$historydata=array(
								'title'=>$comment_title,
								'lead_id'=>$l_id,
								'comment'=>addslashes($message),
								'attach_file'=>'',
								'create_date'=>date("Y-m-d H:i:s"),
								'user_id'=>$update_by,
								'ip_address'=>$ip_addr
									);
					$this->history_model->CreateHistory($historydata);
					// HISTORY CREATE
					// =========================
	        	}
	        }

	        // ================================================
	        // company update
	        $company_post_data=array(
					'assigned_user_id'=>$assigned_to_user_id,
					'modify_date'=>date('Y-m-d')
					);
			$this->customer_model->UpdateCustomer($company_post_data,$company_id);        
			// company update
			// ================================================
	              
	        

			$session_data=$this->session->userdata('admin_session_data');
			// Change of Relationship Manager id 2
			$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(2);
			// SMS for Acknowledgement id 2
			$sms_forwarding_setting=$this->Sms_forwarding_setting_model->GetDetails(2);

			// if($email_forwarding_setting['is_mail_send']=='Y' || count($sms_forwarding_setting)>0)
			if($email_forwarding_setting['is_mail_send']=='Y' || isset($sms_forwarding_setting))
			{

			}

			// MAIL ALERT
			if($email_forwarding_setting['is_mail_send']=='Y')
			{
				// ============================
				// Account Manager Change
				// START
				// TO CLIENT				
			    $e_data=array();		
		        $assigned_to_user=$this->user_model->get_employee_details($assigned_to_user_id);
				$assigned_to_user_name=$assigned_to_user['name'];
				$company=get_company_profile();	
				//$lead_info=$this->lead_model->GetLeadData($lead_id);
				$customer=$this->customer_model->GetCustomerData($company_id);
				$e_data['company']=$company;
				$e_data['assigned_to_user']=$assigned_to_user;
				$e_data['customer']=$customer;
				//$e_data['lead_info']=$lead_info;
				
		        $template_str = $this->load->view('admin/email_template/template/change_of_relationship_manager_view', $e_data, true);	        

		        $m_email=get_manager_and_skip_manager_email_arr($assigned_to_user_id);

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
		        $self_cc_mail=get_value("email","user","id=".$update_by);
		        
		        //$update_by_name=get_value("name","user","id=".$update_by);
		        // --------------------
		        // cc mail assign logic
		        if($email_forwarding_setting['is_send_relationship_manager']=='Y')
		        {
		        	array_push($cc_mail_arr, $self_cc_mail);
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
				// $cc_mail='';
				// $self_cc_mail=get_value("email","user","id=".$assigned_by_user_id);
				// $cc_mail=get_manager_and_skip_manager_email($assigned_to_user_id);
				// $cc_mail=($cc_mail)?$cc_mail.','.$self_cc_mail:$self_cc_mail;
				// $to_mail=$customer->email;

			    if($to_mail!='' && $is_mail_send_to_client=='Y')
			    {
			    	// $this->load->library('mail_lib');
			        // $m_d = array();
			        // $m_d['from_mail']     = $session_data['email'];
			        // $m_d['from_name']     = $session_data['name'];
			        // $m_d['to_mail']       = $to_mail;
			        // $m_d['cc_mail']       = $cc_mail;
			        // $m_d['subject']       = $company['name'].' - Introduction of New A/C Manager';
			        // $m_d['message']       = $template_str;
			        // $m_d['attach']        = array();
			        // $mail_return = $this->mail_lib->send_mail($m_d);
					$post_data=array();
					$post_data=array(
							"mail_for"=>MF_CHANGE_LEAD_ASSIGNEE,
							"from_mail"=>$session_data['email'],
							"from_name"=>$session_data['name'],
							"to_mail"=>$to_mail,
							"cc_mail"=>$cc_mail,
							"subject"=>$company['name'].' - Introduction of New A/C Manager',
							"message"=>$template_str,
							"attach"=>'',
							"created_at"=>date("Y-m-d H:i:s")
					);
					$this->App_model->mail_fire_add($post_data);
			    }
				// END
				// =============================
			}

			// SMS ALERT
			/*
			if(isset($sms_forwarding_setting['is_sms_send']))
			{
				if($sms_forwarding_setting['is_sms_send']=='Y')
				{				
					$m_mobile=get_manager_and_skip_manager_mobile_arr($assigned_to_user_id);
					$default_template_auto_id=$sms_forwarding_setting['default_template_id'];
					$user_id=$this->session->userdata['admin_session_data']['user_id'];
					$sms_variable_info=array("customer_id"=>$company_id,'company_id'=>1,'lead_id'=>$l_id,'user_id'=>$assigned_to_user_id);
					
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
						$relationship_manager_mobile=get_value("mobile","user","id=".$assigned_to_user_id);					
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
					$return=sms_send($sms_send_data,$sms_variable_info);
				}
			}
			*/
			
		}
		// -------------------------------
		// observer update
		if($lead_id!='' && $assigned_observer!='')
		{
			$updatedata=array();
	        $updatedata=array(
	        				'assigned_observer'=>$assigned_observer
	        				);
			$this->lead_model->UpdateLead($updatedata,$lead_id);
		}
		
		// observer update
		// -------------------------------
		
		

    	$status_str='success';  
        $result["status"] = $status_str;
        $result["assigned_to_user_name"]='';
        $result["return"]='';
        echo json_encode($result);
        exit(0); 
    }

    public function change_assigned_to_multiple_ajax()
	{		
		$company_id=$this->input->post('c_id_str');
		$lead_id=$this->input->post('l_id_str');
		$list=array();        
        $list['company_id']=$company_id;
        $list['lead_id']=$lead_id;
        $list['user_list']=$this->user_model->GetAllUsers('0');
    	$html = $this->load->view('admin/customer/change_assigned_to_multiple_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0); 		
	}

	public function update_change_assigned_to_multiple_ajax()
    {
		$company_ids_str = $this->input->post('company_id');
		$lead_ids_str = $this->input->post('lead_id'); 
		$is_mail_send_to_client=($this->input->post('is_mail_send_to_client'))?'Y':'N';
        $assigned_to_user_id = $this->input->post('assigned_to');
        $assigned_observer = $this->input->post('observer');
        $assigned_by_user_id=$this->session->userdata['admin_session_data']['user_id'];
        $company_id_arr=array_unique(explode(',', $company_ids_str));
        if(count($company_id_arr)>0 && $assigned_to_user_id!='')
        {
        	foreach($company_id_arr AS $company_id)
        	{
        		$get_leads_by_cpmpany=$this->lead_model->get_leads_by_cpmpany($company_id);
		        $old_account_manager_id=get_value("assigned_user_id","customer","id=".$company_id);
		        if($old_account_manager_id>0)
		        {
		        	$old_account_manager=get_value("name","user","id=".$old_account_manager_id);
		        	/*
		        	if($old_account_manager_id==$assigned_to_user_id)
			        { 
				        $result["status"] = 'error';	        
				        $result["msg"]='Oops! Please change new assign user';
				        echo json_encode($result);
				        exit(0);
			        }
			        */
		        }
		        else{
		        	$old_account_manager='N/A';
		        }
		       
				$new_account_manager=get_value("name","user","id=".$assigned_to_user_id);

				

		        if(count($get_leads_by_cpmpany))
		        {
		        	foreach($get_leads_by_cpmpany as $lead)
		        	{
		        		$l_id=$lead['id'];
		        		// lead update
		        		$updatedata=array();
				        $updatedata=array(
				        				'assigned_user_id'=>$assigned_to_user_id,
				        				'assigned_date'=>date("Y-m-d")
				        				);
						$this->lead_model->UpdateLead($updatedata,$l_id);

						// insert to log table
						$post_data=array();
				        $post_data=array(
										'lead_id'=>$l_id,
										'assigned_to_user_id'=>$assigned_to_user_id,
										'assigned_by_user_id'=>$assigned_by_user_id,
										'is_accepted'=>'Y',
										'assigned_datetime'=>date("Y-m-d H:i:s")
										);			
						$this->lead_model->create_lead_assigned_user_log($post_data);


				        // =========================
				        // HISTORY CREATE
				        $update_by=$this->session->userdata['admin_session_data']['user_id'];
						$ip_addr=$_SERVER['REMOTE_ADDR'];				
						$message="Account manager has been changed from ".$old_account_manager."(Old) to ".$new_account_manager."(New)";
						$comment_title=ACCOUNT_MANAGER_CHANGE;
						$historydata=array(
									'title'=>$comment_title,
									'lead_id'=>$l_id,
									'comment'=>addslashes($message),
									'attach_file'=>'',
									'create_date'=>date("Y-m-d H:i:s"),
									'user_id'=>$update_by,
									'ip_address'=>$ip_addr
										);
						$this->history_model->CreateHistory($historydata);
						// HISTORY CREATE
						// =========================
		        	}
		        }

		        // ================================================
		        // company update
		        $company_post_data=array(
						'assigned_user_id'=>$assigned_to_user_id,
						'modify_date'=>date('Y-m-d')
						);
				$this->customer_model->UpdateCustomer($company_post_data,$company_id);
				// company update
				// ================================================
		              
		        

				$session_data=$this->session->userdata('admin_session_data');
				// Change of Relationship Manager id 2
				$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(2);

				// SMS for Acknowledgement id 2
				$sms_forwarding_setting=$this->Sms_forwarding_setting_model->GetDetails(2);


				// MAIL ALERT
				if($email_forwarding_setting['is_mail_send']=='Y')
				{
					// ============================
					// Account Manager Change
					// START
					// TO CLIENT				
				    $e_data=array();		
			        $assigned_to_user=$this->user_model->get_employee_details($assigned_to_user_id);
					$assigned_to_user_name=$assigned_to_user['name'];
					$company=get_company_profile();	
					//$lead_info=$this->lead_model->GetLeadData($lead_id);
					$customer=$this->customer_model->GetCustomerData($company_id);
					$e_data['company']=$company;
					$e_data['assigned_to_user']=$assigned_to_user;
					$e_data['customer']=$customer;
					//$e_data['lead_info']=$lead_info;
			        $template_str = $this->load->view('admin/email_template/template/change_of_relationship_manager_view', $e_data, true);


			        $m_email=get_manager_and_skip_manager_email_arr($assigned_to_user_id);
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
			        $self_cc_mail=get_value("email","user","id=".$update_by);
			        //$update_by_name=get_value("name","user","id=".$update_by);
			        // --------------------
			        // cc mail assign logic
			        if($email_forwarding_setting['is_send_relationship_manager']=='Y')
			        {
			        	array_push($cc_mail_arr, $self_cc_mail);
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
					// $cc_mail='';
					// $self_cc_mail=get_value("email","user","id=".$assigned_by_user_id);
					// $cc_mail=get_manager_and_skip_manager_email($assigned_to_user_id);
					// $cc_mail=($cc_mail)?$cc_mail.','.$self_cc_mail:$self_cc_mail;
					// $to_mail=$customer->email;		    
				    if($to_mail!='' && $is_mail_send_to_client=='Y')
				    {
				    	$this->load->library('mail_lib');
				        $m_d = array();
				        $m_d['from_mail']     = $session_data['email'];
				        $m_d['from_name']     = $session_data['name'];
				        $m_d['to_mail']       = $to_mail;
				        $m_d['cc_mail']       = $cc_mail;
				        $m_d['subject']       = $company['name'].' - Introduction of New A/C Manager';
				        $m_d['message']       = $template_str;
				        $m_d['attach']        = array();
				        $mail_return = $this->mail_lib->send_mail($m_d);
				    }
					// END
					// =============================
				}

				// SMS ALERT			
				if(count($sms_forwarding_setting))
				{
					if($sms_forwarding_setting['is_sms_send']=='Y')
					{				
						$m_mobile=get_manager_and_skip_manager_mobile_arr($assigned_to_user_id);
						$default_template_auto_id=$sms_forwarding_setting['default_template_id'];
						$user_id=$this->session->userdata['admin_session_data']['user_id'];
						$sms_variable_info=array("customer_id"=>$company_id,'company_id'=>1,'lead_id'=>$l_id,'user_id'=>$assigned_to_user_id);
						
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
							$relationship_manager_mobile=get_value("mobile","user","id=".$assigned_to_user_id);					
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
						// print_r($sms_send_data); die('ok');
						$return=sms_send($sms_send_data,$sms_variable_info);
					}
				}
        	}
        }

        // --------------------
        // observer 
        $lead_id_arr=array_unique(explode(',', $lead_ids_str));
        if(count($lead_id_arr)>0 && $assigned_observer!='')
        {
        	foreach($lead_id_arr AS $lead_id)
        	{
        		$updatedata=array();
		        $updatedata=array(
		        				'assigned_observer'=>$assigned_observer
		        				);
				$this->lead_model->UpdateLead($updatedata,$lead_id);
        	}
        }

    	$status_str='success';  
        $result["status"] = $status_str;        
        echo json_encode($result);
        exit(0); 
    }


    function assign_to_update_of_company_table()
    {
    	$return= $this->customer_model->assign_to_update();
    	echo 'Total Updated rows : '.$return;
    }

	public function download_csv()
    {
    	
		
		$start = 0;
	    $view_type = $this->input->get('view_type');
	    
	    $this->load->library('pagination');
	    //$limit=30;
	    $config = array();
	    $list=array();
	    $arg=array();
		$session_data=$this->session->userdata('admin_session_data');
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
   		if($user_id==1){
   			array_push($tmp_u_ids, 0);
   		}
		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		   
	    // FILTER DATA
	    $arg['filter_search_str']=$this->input->get('filter_search_str');
	    $arg['filter_sort_by']=$this->input->get('filter_sort_by'); 
	    $arg['filter_created_from_date']=$this->input->get('filter_created_from_date');
		$arg['filter_created_to_date']=$this->input->get('filter_created_to_date');
		$arg['assigned_user']=($this->input->get('filter_assigned_user'))?$this->input->get('filter_assigned_user'):$tmp_u_ids_str;	
		$arg['filter_by_company_available_for']=$this->input->get('filter_by_company_available_for');
		$arg['filter_by_is_available_company_name']=$this->input->get('filter_by_is_available_company_name');
		$arg['filter_by_is_available_email']=$this->input->get('filter_by_is_available_email');
		$arg['filter_by_is_available_phone']=$this->input->get('filter_by_is_available_phone');
		$arg['filter_last_contacted']=$this->input->get('filter_last_contacted');
		$arg['filter_last_contacted_custom_date']=$this->input->get('filter_last_contacted_custom_date');
		$arg['filter_country']=$this->input->get('filter_country');
		$arg['filter_company_type']=$this->input->get('filter_company_type');
	    $arg['source_ids']=$this->input->get('filter_by_source');
		$arg['business_type_id']=$this->input->get('filter_business_type_id');

		$limit = $this->customer_model->get_list_count($arg);

	    $start=empty($start)?0:($start-1)*$limit;
	    $arg['limit']=$limit;
	    $arg['start']=$start;

    	$rows=$this->customer_model->get_list($arg);
	    
			    
	    //$table = $this->load->view('admin/customer/list_customer_view_ajax',$list,TRUE);
	
		
		
        $array[] = array('');
        $array[] = array(
                        'Customer ID',
                        'Company Name',
                        'Contact Person',
                        'Designation',
                        'Assigned To',
						'Leads',
						'Orders',
                        'Email',
                        'Mobile',
						'Address',
						'Country',
                        'State',
                        'City',
                        'Zip',
						'GST',
                        'Created On',
                        'Last Contacted On',
                        'Reference',
                        );
        
        if(count($rows) > 0)
        {
            foreach ($rows as $customer_data) 
            {
				
				$customer_id = $customer_data->id;
				$company_name = ($customer_data->company_name)?$customer_data->company_name:'NA';
				$contact_person = ($customer_data->contact_person)?$customer_data->contact_person:'N/A';
				$designation = ($customer_data->designation)?$customer_data->designation:'N/A';
				$assigned_user_name = ($customer_data->assigned_user_name)?$customer_data->assigned_user_name:'N/A';
				$lead_count = $customer_data->lead_count;
				$lead_won_count = $customer_data->lead_won_count;
				$email = ($customer_data->email)?$customer_data->email:'N/A';
				$mobile = ($customer_data->mobile)?$customer_data->mobile:'N/A';
				$address = ($customer_data->address)?$customer_data->address:'N/A';
				$country_name = ($customer_data->country_name)?$customer_data->country_name:'N/A';
				$state_name = ($customer_data->state_name)?$customer_data->state_name:'N/A';
				$city_name = ($customer_data->city_name)?$customer_data->city_name:'N/A';
				$zip = ($customer_data->zip)?$customer_data->zip:'N/A';
				$gst_number = ($customer_data->gst_number)?$customer_data->gst_number:'N/A';
				$create_date = ($customer_data->create_date!='0000-00-00')?date_db_format_to_display_format($customer_data->create_date):'N/A';
				$last_mail_sent = ($customer_data->last_mail_sent!='0000-00-00 00:00:00' && $customer_data->last_mail_sent!=null)?date_db_format_to_display_format($customer_data->last_mail_sent):'N/A';
				$reference_name = ($customer_data->reference_name)?$customer_data->reference_name:'N/A';
				
				$array[] = array(
							$customer_id,
							$company_name,
							$contact_person,
							$designation,
							$assigned_user_name,
							$lead_count,
							$lead_won_count,
							$email,
							$mobile,
							$address,
							$country_name,
							$state_name,
							$city_name,
							$zip,
							$gst_number,
							$create_date,
							$last_mail_sent,
							$reference_name
                                );
            }
        }
		
        $tmpName='customer_list';
        $tmpDate =  date("YmdHis");
        $csvFileName = $tmpName."_".$tmpDate.".csv";

        $this->load->helper('csv');
        //query_to_csv($array, TRUE, 'Attendance_list.csv');
        array_to_csv($array, $csvFileName);
        return TRUE;
    }

    function set_checked()
    {
    	$ids=trim($this->input->post('ids'));
    	$ids_arr=[];
    	if($ids){
    		$ids_arr=explode(",", $ids);
    	}    					
		$this->session->set_userdata('checked_customer_ids',$ids_arr);

		$data =array(
				    "msg"=>''
				   	);	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
    }	
    
	function set_all_checked()
	{
	    $start = 0;
	    $arg=array();	
	    $session_data=$this->session->userdata('admin_session_data');	
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);		   
	    // FILTER DATA
	    $arg['filter_search_str']=$this->input->post('filter_search_str');
	    $arg['filter_sort_by']=$this->input->post('filter_sort_by'); 
	    $arg['filter_created_from_date']=$this->input->post('filter_created_from_date');
		$arg['filter_created_to_date']=$this->input->post('filter_created_to_date');
		$arg['assigned_user']=($this->input->post('filter_assigned_user'))?$this->input->post('filter_assigned_user'):$tmp_u_ids_str;	
		$arg['filter_by_company_available_for']=$this->input->post('filter_by_company_available_for');
		$arg['filter_by_is_available_company_name']=$this->input->post('filter_by_is_available_company_name');
		$arg['filter_by_is_available_email']=$this->input->post('filter_by_is_available_email');
		$arg['filter_by_is_available_phone']=$this->input->post('filter_by_is_available_phone');
		$arg['filter_last_contacted']=$this->input->post('filter_last_contacted');
		$arg['filter_last_contacted_custom_date']=$this->input->post('filter_last_contacted_custom_date');
		$arg['filter_country']=$this->input->post('filter_country');
		$arg['filter_company_type']=$this->input->post('filter_company_type');
	    
	    $limit = $this->customer_model->get_list_count($arg);	    
	    $arg['limit']=$limit;
	    $arg['start']=$start;
    	$rows=$this->customer_model->get_list($arg); 

    	$ids_arr=[];
    	if($rows)
		{ 
			foreach($rows as $customer_data) 
			{ 
				array_push($ids_arr,$customer_data->id);
			}
		}
		$this->session->set_userdata('checked_customer_ids',$ids_arr);
	    $data =array(
			       		"msg"=>'',
			       		"checked_count"=>$limit
			        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	
	function set_all_unchecked()
	{
		$this->session->set_userdata('checked_customer_ids',array());
		$data =array(
			       		"msg"=>'',
			       		"checked_count"=>$limit
			        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	public function rander_selected_bulk_mail()
	{
		$lead_id=$this->input->post('lead_id');
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$ids_arr=$this->session->userdata('checked_customer_ids');
		$ids_str=implode(",", $ids_arr);
		$get_customer=$this->customer_model->get_email_by_id($ids_str);
		$list['rows']=$get_customer;			
	    $html = $this->load->view('admin/customer/rander_selected_bulk_mail_ajax',$list,TRUE);	    
	    $data =array (
	       "html"=>$html
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	public function bulk_email_test_send()
	{
		$msg='';	
		$status='';	
		$session_data=$this->session->userdata('admin_session_data');
		$from_name=$session_data['name'];
		$from_email=$this->input->post('bulk_email_from_email');
		$subject=$this->input->post('bulk_email_subject');
		$body=$this->input->post('bulk_email_body');
		$to_email=$this->input->post('bulk_email_to_email_test');
		$this->load->library('mail_lib');
		if($to_email!='')
		{		
			// -----------------------------------------------
			// Attached file upload 			
			$attach_filename=[];
			$attach_filename_with_path=array();
			$this->load->library('upload', '');
			if($_FILES['bulk_attach']['tmp_name'])
	        {
	        	$dataInfo = array();
	        	$files = $_FILES;
	            $cpt = count($_FILES['bulk_attach']['name']);
	            $config = array(
	            'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/mail_attachment/",
	            'allowed_types' => "gif|jpg|jpeg|png|pdf|doc|docx",                        
	            'max_size' => "2048000" 
	            );

	        	$this->upload->initialize($config);
	            for($i=0; $i<$cpt; $i++)
	            {	            	
            		$_FILES['bulk_attach']['name']= $files['bulk_attach']['name'];
	                $_FILES['bulk_attach']['type']= $files['bulk_attach']['type'];
	                $_FILES['bulk_attach']['tmp_name']= $files['bulk_attach']['tmp_name'];
	                $_FILES['bulk_attach']['error']= $files['bulk_attach']['error'];
	                $_FILES['bulk_attach']['size']= $files['bulk_attach']['size'];  

	            	$target_dir = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/mail_attachment/";
					$target_file = $target_dir . basename($_FILES['bulk_attach']['name']); 

	            	if (!$this->upload->do_upload('bulk_attach'))
	            	{
	            		$msg=$this->upload->display_errors();
	            	}
	                else
	                {
	                	$dataInfo = $this->upload->data();
	                    $filename=$dataInfo['file_name']; //Image Name
	                    $attach_filename[]=$filename;		                    
	                    $attach_filename_with_path[]="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/mail_attachment/".$filename;
	                    //$msg="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/mail_attachment/".$filename;
	                }  	
	            }
	        }
	        // Attached file upload
	        // ------------------------------------------------------
	        $mail_data = array();
	        $mail_data['from_mail']     = $from_email;
	        $mail_data['from_name']     = $from_name;
	        $mail_data['to_mail']       = $to_email;
	        $mail_data['subject']       = $subject;
	        $mail_data['message']       = $body;
	        $mail_data['attach']        = $attach_filename_with_path;
	        $mail_return = $this->mail_lib->send_mail($mail_data);
	        if($mail_return)
	        {
	        	$status='success';
	        	for($i=0;$i<count($attach_filename_with_path);$i++)
	        	{
	        		if(file_exists($attach_filename_with_path[$i]))
	        		{
	        			@unlink($attach_filename_with_path[$i]);
	        		}	        		
	        	}	        	
	        }
	        else
	        {
	        	$status='fail';
	        }
		}		

		$data =array (
			       "msg"=>$msg,
			       "status"=>$status,
			        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	public function bulk_email_store()
	{
	    $bulk_mail_id=time();
		$msg='';	
		$status='';	
		$session_data=$this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		$from_name=$session_data['name'];
		$from_email=$this->input->post('bulk_email_from_email');
		$subject=$this->input->post('bulk_email_subject');
		$body=$this->input->post('bulk_email_body');		

		$ids_arr=$this->session->userdata('checked_customer_ids');
		if(isset($ids_arr) && count($ids_arr)>0)
		{
			$ids_str=implode(",", $ids_arr);
			$get_customer=$this->customer_model->get_email_by_id($ids_str);
			if(count($get_customer)>0)
			{
				// -----------------------------------------------
				// Attached file upload 			
				$attach_filename=[];
				$attach_filename_with_path=array();
				$this->load->library('upload', '');
				if($_FILES['bulk_attach']['tmp_name'])
		        {
		        	$dataInfo = array();
		        	$files = $_FILES;
		            $cpt = count($_FILES['bulk_attach']['name']);
		            $config = array(
		            'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/mail_attachment/",
		            'allowed_types' => "gif|jpg|jpeg|png|pdf|doc|docx", 
		            'encrypt_name'=>TRUE
		            );

		            
		        	$this->upload->initialize($config);
		            for($i=0; $i<$cpt; $i++)
		            {	            	
	            		$_FILES['bulk_attach']['name']= $files['bulk_attach']['name'];
		                $_FILES['bulk_attach']['type']= $files['bulk_attach']['type'];
		                $_FILES['bulk_attach']['tmp_name']= $files['bulk_attach']['tmp_name'];
		                $_FILES['bulk_attach']['error']= $files['bulk_attach']['error'];
		                $_FILES['bulk_attach']['size']= $files['bulk_attach']['size'];  

		                
		            	//$target_dir = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/mail_attachment/";
						//$target_file = $target_dir . basename($_FILES['bulk_attach']['name']); 
		                
		            	if (!$this->upload->do_upload("bulk_attach"))
		            	{
		            		$msg=$this->upload->display_errors();
		            	}
		                else
		                {
		                	$dataInfo = $this->upload->data();
		                    $filename=$dataInfo['file_name']; //Image Name
		                    $attach_filename[]=$filename;		                    
		                    $attach_filename_with_path[]="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/mail_attachment/".$filename;
		                    $msg="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/mail_attachment/".$filename;
		                }  	
		                
		            }
		            
		        }
		        // Attached file upload
		        // ------------------------------------------------------

				foreach($get_customer AS $customer)
				{
					if($customer->email)
					{			
						if(count($attach_filename_with_path)>0)
						{
							$attach_path_tmp=serialize($attach_filename_with_path);
						}
						else
						{
							$attach_path_tmp="";
						}
							
						$postData=array(
								'bulk_mail_id'=>$bulk_mail_id,
								'updated_by_user_id'=>$user_id,
								'customer_id'=>$customer->id,
								'from_email'=>$from_email,
		        				'from_name'=>$from_name,
		        				'to_name'=>$customer->contact_person,
		        				'to_mail'=>$customer->email,
		        				'subject'=>$subject,
		        				'body'=>$body,
		        				'attach_filename_with_path'=>$attach_path_tmp,
		        				'created_at'=>date("Y-m-d H:i:s")
		        				);
						$this->customer_model->add_bulk_mail_to_be_send($postData);
					}
				}		
			}				
		}

		$status='success';
		$data =array (
					"bulk_mail_id"=>$bulk_mail_id,
			        "msg"=>$msg,
			        "status"=>$status,
			        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);		
	}

	public function bulk_email_send()
	{
	    
		$msg='';	
		$status='';			
		$bulk_mail_id=$this->input->get('bulk_mail_id');
		$get_rows=$this->customer_model->get_bulk_mail_list($bulk_mail_id);
		
		$this->load->library('mail_lib');		
		if(count($get_rows)>0)
		{
			foreach($get_rows AS $row)
			{
				$attach_filename_with_path=	unserialize($row['attach_filename_with_path']);		
				$mail_data = array();
		        $mail_data['from_mail']     = $row['from_email'];
		        $mail_data['from_name']     = $row['from_name'];
		        $mail_data['to_mail']       = $row['to_mail'];			        
		        $mail_data['subject']       = $row['subject'];
		        $mail_data['message']       = $row['body'];
		        $mail_data['attach']        = $attach_filename_with_path;
		        $mail_return = $this->mail_lib->send_mail($mail_data);

		        // -----------------------------------------
		        $bmData=array(
		        			'customer_id'=>$row['customer_id'],
		        			'email'=>$row['to_mail'],
		        			'sent_datetime'=>date("Y-m-d H:i:s")
		        			);
		        $this->customer_model->insertBulkMailHistort($bmData);
		        // -------------------------------------------

		        // -------------------------------------------
		        $updateData=array(
		        				'modify_date'=>date("Y-m-d H:i:s"),
		        				'last_mail_sent'=>date("Y-m-d H:i:s")
		        				);
		        $this->customer_model->UpdateCustomer($updateData,$row['customer_id']);
		        // --------------------------------------------
		        sleep(3);
				
			}

			// ------------------------------------------
			// remove attachment
			for($i=0;$i<count($attach_filename_with_path);$i++)
        	{
        		if(file_exists($attach_filename_with_path[$i]))
        		{
        			@unlink($attach_filename_with_path[$i]);
        		}	        		
        	}
        	// remove attachment
        	// ------------------------------------------				
		}	
		
		$this->customer_model->delete_bulk_mail_list($bulk_mail_id);
		$status='success';
		$data =array (
			       "msg"=>'',
			       "status"=>$status,
			        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);		
	}

	public function customer_add_view_rander_ajax()
	{
		$data=NULL;
		$id=$this->input->post('gmail_inbox_overview_id');		
		$gmail_data=$this->lead_model->get_gmail_data_detail($id);
		$data['tmp_com_source_id']=$this->lead_model->get_source_id(DOWNLOAD_FROM_GMAIL);
		$data['gmail_data']=$gmail_data;
		$data['source_list']=$this->source_model->GetSourceListAll();
		$data['country_list']=$this->countries_model->GetCountriesList();
				
		$this->load->view('admin/customer/add_customer_modal_ajax',$data);
	}
	/*
	function add_gmail_customer_ajax()
    {
    	$session_data=$this->session->userdata('admin_session_data');    
    	$user_id = $session_data['user_id'];	
        $contact_person = $this->input->post('contact_person');
        $designation = $this->input->post('designation');
        $email = $this->input->post('email');
        $alt_email = $this->input->post('alt_email');
        $mobile = $this->input->post('mobile');
        $alt_mobile = $this->input->post('alt_mobile');
        $office_phone = $this->input->post('office_phone');
        $company_name = $this->input->post('company_name');
        $address = $this->input->post('address');
        $city = $this->input->post('city_id');
        $state = $this->input->post('state_id');
        $country_id = $this->input->post('country_id');        
        $gst_number = $this->input->post('gst_number');
        $short_description = $this->input->post('short_description');
        //$update_by=$this->session->userdata['admin_session_data']['user_id'];
        $source_id=$this->lead_model->get_source_id(DOWNLOAD_FROM_GMAIL);


        $post_data=array(
        				'assigned_user_id'=>$user_id,
        				'contact_person'=>$contact_person,
        				'designation'=>$designation,
        				'email'=>$email,
        				'alt_email'=>$alt_email,
        				'mobile'=>$mobile,
        				'alt_mobile'=>$alt_mobile,
        				'office_phone'=>$office_phone,
        				'company_name'=>$company_name,
        				'address'=>$address,
        				'city'=>$city,
        				'state'=>$state,
        				'country_id'=>$country_id,        				
        				'gst_number'=>$gst_number,
        				'short_description'=>$short_description,
        				'source_id'=>$source_id,
        				'create_date'=>date("Y-m-d"),
        				'modify_date'=>date("Y-m-d H:i:s"),
        				'status'=>'1'
        				);
        $com_company_id=$this->customer_model->CreateCustomer($post_data);
        if($com_company_id)
        {
        	$ids=$this->lead_model->get_gmail_inbox_overview_id_by_email($email);
        	$id_arr=explode(",", $ids);
        	if(count($id_arr))
        	{
        		$u_data=array(
        						'customer_id'=>$com_company_id,
        						'customer_exist_keyword'=>'one_customer_exist',
        						'updated_at'=>date('Y-m-d H:i:s')
        					);
        		for ($i=0; $i < count($id_arr) ; $i++) { 
        			$this->lead_model->gmail_overview_update($u_data,$id_arr[$i]);
        		}
        	}
        	$status_str='success'; 
        }
        else
        {
        	$status_str='fail'; 
        }

    	   	
        $result["status"] = $status_str;  
        $result["msg"] = '';        
        echo json_encode($result);
        exit(0); 
    }
    */

    public function add_popup_view_ajax()
	{
		$data=array();
		$data['mobile']='';
		$data['email']='';
		$data['company']=array();
		

		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			

			$data['is_customer_basic_data_show']=$this->input->post('is_customer_basic_data_show');
			$data['is_search_box_show']=$this->input->post('is_search_box_show');
			$data['mobile']=$this->input->post('mobile');
			$data['email']=$this->input->post('email');

			$data['company']=$this->customer_model->company_search_to_add_lead($data['mobile'],$data['email']);
			
			$data['source_list']=$this->source_model->GetSourceListAll();
			$data['country_list']=$this->countries_model->GetCountriesList();
			$data['is_add_a_new_lead_form_show']='Y';

			
			// user list
			$session_data=$this->session->userdata('admin_session_data');
	   		$user_id=$session_data['user_id'];
	   		$user_type=$session_data['user_type'];
	   		if($user_type=='Admin')
	   		{
	   			//$m_id=$this->user_model->get_manager_id($user_id);
	   			$m_id=0;
	   		}
	   		else
	   		{
	   			$m_id=$this->user_model->get_manager_id($user_id);
	   		}
	        $user_ids = $this->user_model->get_under_employee_ids($m_id,0);
	        array_push($user_ids, $user_id);
	        $user_ids_str=implode(',', $user_ids);
	        $data['user_list']=$this->user_model->GetUserList($user_ids_str);
	        //$data['user_list']=$this->user_model->GetUserListAll('');
	        $data['user_id']=$user_id;
			//print_r($data['company']);
			
			if($data['is_search_box_show']!='Y' && count($data['company'])==1)
			{
				
				echo $data['company'][0]['id']."#".'id';exit;
			}


			if(count($data['company'])>1)
			{
				$data['is_add_a_new_lead_form_show']='N';
			}
			else
			{
				$data['is_add_a_new_lead_form_show']='Y';
			}
		}		
		$data['business_type_list']=$this->customer_model->get_business_type();
		$this->load->view('admin/customer/add_popup_view_ajax',$data);		
	}

	function add_customer_ajax()
    {
    	$session_data=$this->session->userdata('admin_session_data');
    	$update_by=$this->session->userdata['admin_session_data']['user_id'];
    	$id = $this->input->post('customer_id');


		$source_id = $this->input->post('com_source_id');
		$assigned_user_id = $this->input->post('assigned_user_id');
    	$company_name = $this->input->post('company_name');
    	$country_id = $this->input->post('country_id');
    	$contact_person = $this->input->post('contact_person');
    	$designation = $this->input->post('designation');
    	$mobile = $this->input->post('mobile');
        $alt_mobile = $this->input->post('alt_mobile');
        $landline_std_code = $this->input->post('landline_std_code');
        $landline_number = $this->input->post('landline_number');
        $office_std_code = $this->input->post('office_std_code');        
        $office_phone = $this->input->post('office_phone');
        $email = $this->input->post('email');
        $alt_email = $this->input->post('alt_email');
        $address = $this->input->post('address');
        $state = $this->input->post('state_id');
        $city = $this->input->post('city_id');
        $zip = $this->input->post('zip');
        $website = $this->input->post('website');
        $gst_number = $this->input->post('gst_number');
        $short_description = $this->input->post('short_description');      
		$business_type_id=$this->input->post('business_type_id');   
		$reference_name=$this->input->post('reference_name');     
        
        $country_code='';
        if($country_id)
        {
        	$country_code=get_value("phonecode","countries","id=".$country_id);
        }
        

        $post_data=array(
        				'assigned_user_id'=>$assigned_user_id,
        				'contact_person'=>$contact_person,
        				'designation'=>$designation,
        				'email'=>$email,
        				'alt_email'=>$alt_email,
        				'mobile_country_code'=>$country_code,
        				'mobile'=>$mobile,
        				'alt_mobile_country_code'=>$country_code,
        				'alt_mobile'=>$alt_mobile,
        				'landline_country_code'=>$country_code,
        				'landline_std_code'=>$landline_std_code,
        				'landline_number'=>$landline_number,
        				'office_country_code'=>$country_code,
        				'office_std_code'=>$office_std_code,
        				'office_phone'=>$office_phone,
        				'website'=>$website,
        				'company_name'=>$company_name,
        				'address'=>$address,
        				'city'=>$city,
        				'state'=>$state,
        				'country_id'=>$country_id,
        				'zip'=>$zip,
        				'gst_number'=>$gst_number,
        				'short_description'=>$short_description,
        				'source_id'=>$source_id,
						'business_type_id'=>$business_type_id,
        				'status'=>'1',
        				'modify_date'=>date("Y-m-d H:i:s"),
						'reference_name'=>$reference_name
        				);
        $com_company_id=$this->customer_model->CreateCustomer($post_data);

    	$status_str='success';    	
        $result["status"] = $status_str;
        $result['msg']='A new company successfully added.';
        echo json_encode($result);
        exit(0); 
    }

	function blacklist_toggle()
    {        
        $id = $this->input->post('id');
        $is_blacklist=get_value("is_blacklist","customer","id=".$id);      
        
        if($is_blacklist=='Y')
        {
            $is_blacklist_new='N';
        }
        else
        {
            $is_blacklist_new='Y';
        }        

        $update=array(
                    'is_blacklist'=>$is_blacklist_new,
                    'modify_date'=>date("Y-m-d H:i:s")
                    );
		
        $this->customer_model->UpdateCustomer($update,$id);
        $result["status"] = 'success';
        echo json_encode($result);
        exit(0);        
    }

	public function customer_contact_persion_add_edit_view_rander_ajax()
	{
		$data=NULL;
		$customer_id=$this->input->post('customer_id');	
		$id=$this->input->post('id');		
		$action_table=$this->input->post('action_table');			
		$data['customer_id']=$customer_id;		
		$data['id']=$id;	
		$data['action_table']=$action_table;
		$data['cus_data']=$this->customer_model->GetCustomerData($customer_id);
		if($id){
			$data['contact_persion']=$this->customer_model->customer_wise_contact_person_row($id);
		}
		else{
			$data['contact_persion']=array();
		}
		
		$this->load->view('admin/customer/add_edit_customer_contact_persion_modal_ajax',$data);
	}
	function add_edit_customer_contact_persion_ajax()
    {
    	$session_data=$this->session->userdata('admin_session_data');
    	$update_by=$this->session->userdata['admin_session_data']['user_id'];
		
		$action_table = $this->input->post('action_table');
    	$customer_id = $this->input->post('customer_id');
		$id = $this->input->post('id');
    	$name = $this->input->post('name');
    	$designation = $this->input->post('designation');
    	$email = $this->input->post('email');
    	$mobile = $this->input->post('mobile');
    	$dob = ($this->input->post('dob'))?date_display_format_to_db_format($this->input->post('dob')):'';
        $doa = ($this->input->post('doa'))?date_display_format_to_db_format($this->input->post('doa')):'';
        
		
		if(strtolower($action_table)=='c' && $customer_id!='')
		{
			$post_data=array(
				'contact_person'=>$name,
				'email'=>$email,			
				'mobile'=>$mobile,
				'dob'=>$dob,
				'doa'=>$doa,
				'designation'=>$designation,
				'modify_date'=>date("Y-m-d H:i:s")
				);
			$return=$this->customer_model->UpdateCustomer($post_data,$customer_id);
			$msg='Record successfully updated.';
		}
		else
		{
			if($id)
			{
				$post_data=array(
					'name'=>$name,
					'email'=>$email,			
					'mobile'=>$mobile,
					'dob'=>$dob,
					'doa'=>$doa,
					'designation'=>$designation,
					'updated_at'=>date("Y-m-d H:i:s")
					);
				$return=$this->customer_model->customer_wise_contact_person_edit($post_data,$id);
				$msg='Record successfully updated.';
			}
			else
			{
				$post_data=array(
					'customer_id'=>$customer_id,
					'name'=>$name,
					'email'=>$email,			
					'mobile'=>$mobile,
					'dob'=>$dob,
					'doa'=>$doa,
					'designation'=>$designation,
					'created_at'=>date("Y-m-d H:i:s"),
					'updated_at'=>date("Y-m-d H:i:s")
					);
				$return=$this->customer_model->customer_wise_contact_person_add($post_data);
				$msg='Record successfully added.';
			}
		}		

		if($return==false)
		{
			$status_str='fail'; 
			$msg='Unknown Error! Please try again later.';
		}
		else
		{
			$status_str='success';    	
		}    	
        $result["status"] = $status_str;
        $result['msg']=$msg;
		$result['id']=$id;
        echo json_encode($result);
        exit(0); 
    }
	public function customer_contact_persion_list_view_rander_ajax()
	{
		$data=NULL;
		$customer_id=$this->input->post('customer_id');	
		$id=$this->input->post('id');			
		$data['customer_id']=$customer_id;						
		$data['cus_data']=$this->customer_model->GetCustomerData($customer_id);
		$data['contact_persion_list']=$this->customer_model->customer_wise_contact_person_list($customer_id);
		$this->load->view('admin/customer/list_customer_contact_persion_modal_ajax',$data);
	}

	public function customer_contact_persion_delete_ajax()
	{
		$data=NULL;
		$customer_id=$this->input->post('customer_id');	
		$id=$this->input->post('id');
		$this->customer_model->delete_customer_contact_person($id);
		echo'success';
	}
}


