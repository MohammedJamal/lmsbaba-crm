<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Renewal extends CI_Controller {	
	private $api_access_token = '';
	function __construct()
	{
		parent :: __construct();
		is_admin_logged_in();
		init_admin_element(); 
		$this->load->model(array("lead_model","customer_model","user_model","source_model","email_model","countries_model","states_model","cities_model","opportunity_model","Product_model","Opportunity_product_model","menu_model","history_model","vendor_model","Opportunity_model","Source_model","quotation_model","pre_define_comment_model","setting_model","Email_forwarding_setting_model","Setting_model","renewal_model"));
		// permission checking
		// if(is_permission_available($this->session->userdata('service_wise_menu')[5]['menu_list']['menu_keyword'])===false){ 
		// 	redirect(admin_url().'dashboard', 'refresh');
		// 	exit(0);
		// }
		// end
	}
	// AJAX PAGINATION START
	function get_list_ajax()
	{
		$session_data=$this->session->userdata('admin_session_data');
	    $start = $this->input->get('page');
	    $view_type = $this->input->get('view_type');
	    
	    $this->load->library('pagination');
	    $limit=30;
	    $config = array();
	    $list=array();
	    $arg=array();
		
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		   
	    // FILTER DATA
		$arg['assigned_user']=($this->input->get('filter_assigned_user'))?$this->input->get('filter_assigned_user'):$tmp_u_ids_str;
		$arg['by_keyword']=$this->input->get('by_keyword');
		$arg['renewal_from_date']=$this->input->get('renewal_from_date');
		$arg['renewal_to_date']=$this->input->get('renewal_to_date');
		$arg['followup_from_date']=$this->input->get('followup_from_date');
		$arg['followup_to_date']=$this->input->get('followup_to_date');
		$arg['lead_type']=$this->input->get('lead_type');	
	    $arg['sort_by']=$this->input->get('sort_by');

	   //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    if($view_type=='grid')
	    {
	    	$config['total_rows'] = $this->renewal_model->get_list_count($arg);
	    }
	    else
	    {
	    	$config['total_rows'] = $this->renewal_model->get_trans_list_count($arg);
	    } 
	    

	    $config['per_page'] = $limit;
	    $config['uri_segment']=4;
	    $config['num_links'] = 1;
	    $config['use_page_numbers'] = TRUE;
	    $config['attributes'] = array('class' => 'myclass','data-viewtype'=>$view_type);	    
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
	    // $list['priority_wise_stage']=$this->renewal_model->priority_wise_stage();
	    $table = '';
	    if($view_type=='grid')
	    {
	    	$list['rows']=$this->renewal_model->get_list($arg);
	    	$table = $this->load->view('admin/renewal/group_view_ajax',$list,TRUE);
	    }
	    else
	    {
	    	$list['rows']=$this->renewal_model->get_trans_list($arg);
	    	$table = $this->load->view('admin/renewal/trans_view_ajax',$list,TRUE);
	    }	    
	
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
	    exit;
	}
	// AJAX PAGINATION END
	public function manage()
	{
		
		$data=array();		
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
   		$tmp_u_ids_str=implode(",", $tmp_u_ids);
   				
		$assigned_user='';
		$opportunity_stage='';
		$opportunity_status='';
		$lead_to_date='';
		$lead_from_date='';
		$to_date='';
		$from_date='';	
		$search_keyword='';		
		$search_command=$this->input->post('search_command');		
		//if($search_command=='1')
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$assigned_user=$this->input->post('assigned_user');
			$opportunity_stage=$this->input->post('opportunity_stage');
			$opportunity_status=$this->input->post('opportunity_status');
			$lead_to_date=$this->input->post('lead_to_date');
			$lead_from_date=$this->input->post('lead_from_date');
			$search_keyword=$this->input->post('search_keyword');
		}		
		
		$session_array=array(
				         'assigned_user'=>($assigned_user)?$assigned_user:$tmp_u_ids_str,
				         'opportunity_stage'=>$opportunity_stage,
				         'opportunity_status'=>$opportunity_status,	
				         'lead_to_date'=>$lead_to_date,
				         'lead_from_date'=>$lead_from_date,
				         'search_keyword'=>$search_keyword
				     	);					
	    $this->session->set_userdata('lead_search',$session_array);
	    
		
		
		$data['user_list']=$this->user_model->GetUserList($tmp_u_ids_str);
		$data['opportunity_stage_list']=$this->opportunity_model->GetOpportunityStageListAll();	
		$data['opportunity_status_list']=$this->opportunity_model->GetOpportunityStatusListAll();	
		$data['me_and_my_team_user_ids']=$tmp_u_ids_str;
		$data['source_list']=$this->source_model->GetSourceListAll();

		$this->load->view('admin/renewal/renewal_view',$data);
		
	}

	public function add_ajax()
	{
		$data=array();
		$data['mobile']='';
		$data['email']='';
		$data['company']=array();
		

		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$cid=($this->input->post('cid'))?$this->input->post('cid'):'';

			$data['is_customer_basic_data_show']=$this->input->post('is_customer_basic_data_show');
			$data['is_search_box_show']=$this->input->post('is_search_box_show');
			$data['mobile']=$this->input->post('mobile');
			$data['email']=$this->input->post('email');

			if($cid>0)
			{
				$data['company']=$this->customer_model->company_search_to_add_lead_by_id($cid);
			}
			else
			{
				$data['company']=$this->customer_model->company_search_to_add_lead($data['mobile'],$data['email']);
			}
			
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
	        $user_list=$this->user_model->GetUserList($user_ids_str);


	        $assigned_user_id=$data['company'][0]['assigned_user_id'];
	        if(in_array($assigned_user_id,$user_ids))
	        {
	        	$data['user_list']=$user_list;
	        	$data['is_user_in_your_under']='Y';
	        }
	        else
	        {
	        	$data['user_list']=$this->user_model->GetAllUsers('0');
	        	$data['is_user_in_your_under']=($assigned_user_id)?'N':'';
	        }
	        

	        //$data['user_list']=$this->user_model->GetUserListAll('');
	        $data['user_id']=$user_id;
			//print_r($data['company']);
			if(count($data['company'])>1)
			{
				$data['is_add_a_new_lead_form_show']='N';
			}
			else
			{
				$data['is_add_a_new_lead_form_show']='Y';
			}
		}
		$data['product_list']=$this->Product_model->list_product_name();
		$this->load->view('admin/renewal/add_renewal_ajax',$data);		
	}

	function add_renewal_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$assigned_user_id = ($this->input->post('assigned_user_id')>0)?$this->input->post('assigned_user_id'):$this->input->post('existing_assigned_user_id');
			//$assigned_user_id=$this->input->post('assigned_user_id');

			// RENEWAL/AMC INFO		
			$product_tags_arr=$this->input->post('product_tags');
			// $renewal_amount=$this->input->post('renewal_amount');		
	        
	        $renewal_requirement = $this->input->post('renewal_requirement'); 
	        $renewal_date = date_display_format_to_db_format($this->input->post('renewal_date'));
	        $renewal_follow_up_date = date_display_format_to_db_format($this->input->post('renewal_follow_up_date'));

	        
	        // COMPANY INFO
	        $com_company_id = $this->input->post('com_company_id');
	        $com_company_name = $this->input->post('com_company_name');
	        $com_contact_person = $this->input->post('com_contact_person');
	        // $com_designation = $this->input->post('com_designation');
	        $com_email = $this->input->post('com_email');
	        // $com_alternate_email = $this->input->post('com_alternate_email');
	        $com_mobile_country_code = $this->input->post('com_mobile_country_code');

	        $com_mobile = $this->input->post('com_mobile');
	        $com_alt_mobile_country_code = $this->input->post('com_alt_mobile_country_code');
	        // $com_alternate_mobile = $this->input->post('com_alternate_mobile');

	        $com_landline_country_code = $this->input->post('com_landline_country_code');
	        // $com_landline_std_code = $this->input->post('com_landline_std_code');
	        // $landline_number = $this->input->post('landline_number');
	        // $com_office_std_code = $this->input->post('office_std_code');        
	        // $com_office_phone = $this->input->post('office_phone');

	        // $com_address = $this->input->post('com_address');
	        $com_country_id = ($this->input->post('com_country_id')>0)?$this->input->post('com_country_id'):$this->input->post('com_existing_country');
	        // $com_state_id = ($this->input->post('com_state_id')>0)?$this->input->post('com_state_id'):$this->input->post('com_existing_state');
	        // $com_city_id = ($this->input->post('com_city_id')>0)?$this->input->post('com_city_id'):$this->input->post('com_existing_city');
	        // $com_zip = $this->input->post('com_zip');
	        // $com_website = $this->input->post('com_website');
	        $selected_source_id = ($this->input->post('com_source_id')>0)?$this->input->post('com_source_id'):$this->input->post('com_existing_source');	        
	        // $com_short_description = $this->input->post('com_short_description');

	        $country_code='';
	        if($com_mobile_country_code=='' && $com_country_id>0)
	        {
	        	$country_code=get_value("phonecode","countries","id=".$com_country_id);
	        } 
	        else
	        {
	        	$country_code=$com_mobile_country_code;
	        }

	        $company_post_data=array(
	        	'assigned_user_id'=>$assigned_user_id,
				'contact_person'=>$com_contact_person,
				// 'designation'=>$com_designation,
				'email'=>$com_email,
				// 'alt_email'=>$com_alternate_email,
				'mobile_country_code'=>$country_code,
				'mobile'=>$com_mobile,
				'alt_mobile_country_code'=>$country_code,
				// 'alt_mobile'=>$com_alternate_mobile,
				'landline_country_code'=>$country_code,
				// 'landline_std_code'=>$com_landline_std_code,
				// 'landline_number'=>$landline_number,
				'office_country_code'=>$country_code,
				// 'office_std_code'=>$com_office_std_code,
				// 'office_phone'=>$com_office_phone,
				// 'website'=>$com_website,
				'company_name'=>$com_company_name,
				// 'address'=>$com_address,
				// 'city'=>$com_city_id,
				// 'state'=>$com_state_id,
				'country_id'=>$com_country_id,
				// 'zip'=>$com_zip,
				// 'short_description'=>$com_short_description,
				'source_id'=>$selected_source_id,
				'create_date'=>date('Y-m-d'),
				'modify_date'=>date('Y-m-d'),
				'status'=>'1'
			);
	        

	        if($com_company_id)
	        {
	        	$this->customer_model->UpdateCustomer($company_post_data,$com_company_id);
	        }
	        else
	        {
	        	$com_company_id=$this->customer_model->CreateCustomer($company_post_data);
	        } 
	        $renewal_post_data=array(
					'customer_id'=>$com_company_id,
					'product_id'=>implode(',',$product_tags_arr),
					'created_at'=>date('Y-m-d H:i:s'),
					'Updated_at'=>date('Y-m-d H:i:s')
					);
	        $reneal_id=$this->renewal_model->create($renewal_post_data);
	        if($reneal_id)
			{
				

				$renewal_detail_post_data=array(
					'renewal_id'=>$reneal_id,
					'next_follow_up_date'=>$renewal_follow_up_date,
					'renewal_date'=>$renewal_date,
					'description'=>$renewal_requirement,
					'created_at'=>date('Y-m-d H:i:s'),
					'Updated_at'=>date('Y-m-d H:i:s')
					);
	        	$renewal_detail_id=$this->renewal_model->createDetails($renewal_detail_post_data);

	        	if($renewal_detail_id)
	        	{
	        		if(count($product_tags_arr))
					{
						foreach($product_tags_arr AS $p)
						{
							$price=$this->input->post("product_price_tags_".$p);
							$p_name=get_value("name","product_varient","id=".$p);
							$renewal_p_data=array(
								'renewal_detail_id'=>$renewal_detail_id,
								'product_id'=>$p,
								'product_name'=>$p_name,
								'price'=>$price
							);
			        		$this->renewal_model->CreateRenewalWiseProductTag($renewal_p_data);
						}
					}


	        		$attach_filename='';
					// LEAD ATTACH FILE UPLOAD
					$this->load->library('upload', '');
					if($_FILES['renewal_attach_file']['name'] != "")
					{
						$config2['upload_path'] = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/lead/";
						$config2['allowed_types'] = "gif|jpg|jpeg|png|pdf";
						$config2['max_size'] = '1000000'; //KB
						$this->upload->initialize($config2);
						if (!$this->upload->do_upload('renewal_attach_file'))
						{
						    //return $this->upload->display_errors();
						}
						else
						{
						    $file_data = array('upload_data' => $this->upload->data());
						    $attach_filename = $file_data['upload_data']['file_name'];
						    $update_renewal_data = array(
								'attach_file' => $attach_filename,
								'Updated_at' => date("Y-m-d H:i:s")
							);								
							$this->renewal_model->updateDetails($update_renewal_data,$renewal_detail_id);
						}
					}
	        	}				
	            // end	    			   

			 	// $update_by=$this->session->userdata['admin_session_data']['user_id'];				
				// $date=date("Y-m-d h:i:s");				
				// $ip_addr=$_SERVER['REMOTE_ADDR'];				
				// $message="A new lead has been created as &quot;".$lead_title."&quot;";
				// $comment_title=NEW_LEAD_CREATE_MANUAL;
				// $historydata=array(
				// 					'title'=>$comment_title,
				// 					'lead_id'=>$lead_id,
				// 					'comment'=>addslashes($message),
				// 					'attach_file'=>$attach_filename,
				// 					'create_date'=>$date,
				// 					'user_id'=>$update_by,
				// 					'ip_address'=>$ip_addr
				// 				);
				//$this->history_model->CreateHistory($historydata);				
			}

			$status_str='success';
	        $result["status"] = $status_str;
	        $result['msg']='';	 
	        $result['company_id']=$com_company_id;    
	        echo json_encode($result);
	        exit(0);
		}
	}

	function delete_renewal_ajax()
    {        
    	$data=array();
        $id = $this->input->post('id');
        if($id)
        {
        	$this->renewal_model->delete($id);
        	echo 'success';
        }
        else
        {        	
        	echo 'Oops! Unable to delete the record.';
        }        
    }

    function get_renewal_history_ajax()
    {
    	$renewal_id = $this->input->post('renewal_id');
    	$rd_id = $this->input->post('rd_id');
    	$renewal=$this->renewal_model->get_renewal_row($renewal_id);
    	$renewal_details=$this->renewal_model->get_renewal_details_by_renewal_id($renewal_id,$rd_id);
    	// $renewal_products=$this->renewal_model->get_renewal_product_by_renewal_detail_id($rd_id);

    	$list['renewal_id']=$renewal_id;
    	$list['rd_id']=$rd_id;
    	$list['renewal']=$renewal;
    	$list['renewal_details']=$renewal_details;
    	// $list['renewal_products']=$renewal_products;
    	$list['company']=get_company_profile();
    	$html = $this->load->view('admin/renewal/renewal_history_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0);
    }

    function get_product_info_ajax()
	{
	    $pid = $this->input->get('pid');
	    $product_info=$this->Product_model->get_product($pid);
	    $list['product']=$product_info;
	    $html = $this->load->view('admin/renewal/selected_product_view_ajax',$list,TRUE);

	    $data =array (
	       "html"=>$html
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit;
	}

	function edit_view_ajax()
	{
		$rid = $this->input->get('rid');
		$rdid = $this->input->get('rdid');
		$list['renual_id']=$rid;	
		$list['renual_detail']=$this->renewal_model->get_renewal_detail_row($rdid);
		$list['product_list']=$this->Product_model->list_product_name();
		$html = $this->load->view('admin/renewal/edit_renewal_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0);
	}

	function edit_renewal_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			
			$edit_id=$this->input->post('edit_id');
			

			// RENEWAL/AMC INFO		
			$product_tags_arr=$this->input->post('product_tags');
			
	        $renewal_requirement = $this->input->post('renewal_requirement'); 
	        $renewal_date = date_display_format_to_db_format($this->input->post('renewal_date'));
	        $renewal_follow_up_date = date_display_format_to_db_format($this->input->post('renewal_follow_up_date'));	        
	        $renewal_detail_post_data=array(
					'next_follow_up_date'=>$renewal_follow_up_date,
					'renewal_date'=>$renewal_date,
					'description'=>$renewal_requirement,
					'Updated_at'=>date('Y-m-d H:i:s')
					);
	        $this->renewal_model->updateDetails($renewal_detail_post_data,$edit_id);
        	
    		if(count($product_tags_arr))
			{
				$this->renewal_model->product_delete_by_rdid($edit_id);
				foreach($product_tags_arr AS $p)
				{
					$price=$this->input->post("product_price_tags_".$p);
					$p_name=get_value("name","product_varient","id=".$p);
					$renewal_p_data=array(
						'renewal_detail_id'=>$edit_id,
						'product_id'=>$p,
						'product_name'=>$p_name,
						'price'=>$price
					);
	        		$this->renewal_model->CreateRenewalWiseProductTag($renewal_p_data);
				}
			}


    		$attach_filename='';
			// LEAD ATTACH FILE UPLOAD
			$this->load->library('upload', '');
			if($_FILES['renewal_attach_file']['name'] != "")
			{
				$config2['upload_path'] = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/lead/";
				$config2['allowed_types'] = "gif|jpg|jpeg|png|pdf";
				$config2['max_size'] = '1000000'; //KB
				$this->upload->initialize($config2);
				if (!$this->upload->do_upload('renewal_attach_file'))
				{
				    //return $this->upload->display_errors();
				}
				else
				{
				    $file_data = array('upload_data' => $this->upload->data());
				    $attach_filename = $file_data['upload_data']['file_name'];
				    $update_renewal_data = array(
						'attach_file' => $attach_filename,
						'Updated_at' => date("Y-m-d H:i:s")
					);								
					$this->renewal_model->updateDetails($update_renewal_data,$edit_id);
				}
			}        				
            // end	    			   

		 	// $update_by=$this->session->userdata['admin_session_data']['user_id'];				
			// $date=date("Y-m-d h:i:s");				
			// $ip_addr=$_SERVER['REMOTE_ADDR'];				
			// $message="A new lead has been created as &quot;".$lead_title."&quot;";
			// $comment_title=NEW_LEAD_CREATE_MANUAL;
			// $historydata=array(
			// 					'title'=>$comment_title,
			// 					'lead_id'=>$lead_id,
			// 					'comment'=>addslashes($message),
			// 					'attach_file'=>$attach_filename,
			// 					'create_date'=>$date,
			// 					'user_id'=>$update_by,
			// 					'ip_address'=>$ip_addr
			// 				);
			//$this->history_model->CreateHistory($historydata);				
			

			$status_str='success';
	        $result["status"] = $status_str;
	        $result['msg']='';    
	        echo json_encode($result);
	        exit(0);
		}
	}


	function delete_renewal_detail_ajax()
    {        
    	$data=array();
        $id = $this->input->post('id');
        if($id)
        {
        	$this->renewal_model->delete_detail($id);
        	echo 'success';
        }
        else
        {        	
        	echo 'Oops! Unable to delete the record.';
        }        
    }
	
}
?>