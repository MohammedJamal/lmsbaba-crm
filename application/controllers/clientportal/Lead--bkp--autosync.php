<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Lead extends CI_Controller {	
	private $api_access_token = '';
	function __construct()
	{
		parent :: __construct();
		is_admin_logged_in();
		init_admin_element(); 
		$this->load->model(array("lead_model","customer_model","user_model","source_model","email_model","countries_model","states_model","cities_model","opportunity_model","Product_model","Opportunity_product_model","menu_model","history_model","vendor_model","Opportunity_model","Source_model","quotation_model","pre_define_comment_model","setting_model","Email_forwarding_setting_model","Setting_model","renewal_model","order_model","Sms_forwarding_setting_model","Whatsapp_forwarding_setting_model","meeting_model","App_model"));
		// permission checking
		// if(is_permission_available($this->session->userdata('service_wise_menu')[1]['menu_list']['menu_keyword'])===false){ 
		// 	redirect(admin_url().'dashboard', 'refresh');
		// 	exit(0);
		// }
		// end
	}
	 
	
	
	function test_template_view()
    {
    	$session_data=$this->session->userdata('admin_session_data');
    	$this->load->library('mail_lib');
        $mail_data = array();
        $mail_data['from_mail']     = $session_data['email'];
        $mail_data['from_name']     = $session_data['name'];
        //$mail_data['to_mail']       = $customer->email;
        $mail_data['to_mail']       = 'arupporel123@gmail.com';
        $mail_data['subject']       = 'Testing mail.';
        $mail_data['message']       = "Testing";
        $mail_data['attach']        = array();
        $mail_return = $this->mail_lib->send_mail($mail_data);
        if($mail_return)
        	die("sent");
    	else
    		die("not sent");


	    $e_data=array();    

		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$admin_session_data_user_data=$this->user_model->get_employee_details($user_id);
		$company=get_company_profile();	
		$lead_info=$this->lead_model->GetLeadData(38);
		$customer=$this->customer_model->GetCustomerData($lead_info->customer_id);
		//print_r($company);
		$e_data['company']=$company;
		$e_data['assigned_to_user']=$admin_session_data_user_data;
		$e_data['customer']=$customer;
		$e_data['lead_info']=$lead_info;
		$e_data['name']='Shashi Narain';
		$e_data['user_id']=1;
		$e_data['password']=123456;
		/*echo '<pre>';
		print_r($lead_info);die;*/
        //$template_str = $this->load->view('admin/email_template/template/quotation_sent_view', $e_data, true);
		$template_str ='Testing';
         // LEAD ASSIGNED MAIL
        

        $this->load->library('mail_lib');
        $mail_data = array();
        $mail_data['from_mail']     = $session_data['email'];
        $mail_data['from_name']     = $session_data['name'];
        //$mail_data['to_mail']       = $customer->email;
        $mail_data['to_mail']       = 'arupporel123@gmail.com';
        $mail_data['subject']       = 'New lead ( '.$title.' ) has been assigned.';
        $mail_data['message']       = $template_str;
        $mail_data['attach']        = array();
        $mail_return = $this->mail_lib->send_mail($mail_data);
    	echo $template_str ;


    }

    public function change_assigned_to_ajax()
	{
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		//array_push($tmp_u_ids, $user_id);
   		$tmp_u_ids_str=implode(",", $tmp_u_ids);


		$lead_id=$this->input->post('lead_id');
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
        $list['lead_id']=$lead_id;
        $list['lead_info']=$this->lead_model->GetLeadData($lead_id);	
        //$list['user_list']=$this->user_model->GetUserListAll();
        $user_ids = $this->user_model->get_under_employee_ids($user_id,0);
        array_push($user_ids, $user_id);
        $user_ids_str=implode(',', $user_ids);
        //$list['user_list']=$this->user_model->GetUserList($user_ids_str);
		$list['user_list']=$this->user_model->GetUserList($tmp_u_ids_str);
    	$html = $this->load->view('admin/lead/change_assigned_to_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0); 
		
	}

	function update_change_assigned_to_ajax()
    {
    	$lead_id = $this->input->post('lead_id');
    	$company_id=get_value("customer_id","lead","id=".$lead_id);
    	$get_leads_by_cpmpany=$this->lead_model->get_leads_by_cpmpany($company_id);
        $assigned_to_user_id = $this->input->post('assigned_to');
        $assigned_by_user_id=$this->session->userdata['admin_session_data']['user_id'];

        $old_account_manager_id=get_value("assigned_user_id","lead","id=".$lead_id);
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
        	}
        }

        $old_account_manager=get_value("name","user","id=".$old_account_manager_id);
        $new_account_manager=get_value("name","user","id=".$assigned_to_user_id);
        // =========================
        // HISTORY CREATE
        $update_by=$this->session->userdata['admin_session_data']['user_id'];
		$ip_addr=$_SERVER['REMOTE_ADDR'];				
		$message="Account manager has been changed from ".$old_account_manager."(Old) to ".$new_account_manager."(New)";
		$comment_title=ACCOUNT_MANAGER_CHANGE;
		$historydata=array(
					'title'=>$comment_title,
					'lead_id'=>$lead_id,
					'comment'=>addslashes($message),
					'attach_file'=>'',
					'create_date'=>date("Y-m-d H:i:s"),
					'user_id'=>$update_by,
					'ip_address'=>$ip_addr
						);
		$this->history_model->CreateHistory($historydata);
		// HISTORY CREATE
		// =========================
        

		$session_data=$this->session->userdata('admin_session_data');
		// ============================
		// Account Manager Change
		// START
		// TO CLIENT				
	    $e_data=array();		
        $assigned_to_user=$this->user_model->get_employee_details($assigned_to_user_id);
		$assigned_to_user_name=$assigned_to_user['name'];
		$company=get_company_profile();	
		$lead_info=$this->lead_model->GetLeadData($lead_id);
		$customer=$this->customer_model->GetCustomerData($lead_info->customer_id);
		$e_data['company']=$company;
		$e_data['assigned_to_user']=$assigned_to_user;
		$e_data['customer']=$customer;
		$e_data['lead_info']=$lead_info;
        $template_str = $this->load->view('admin/email_template/template/change_of_relationship_manager_view', $e_data, true);
        $cc_mail='';
        $self_cc_mail=get_value("email","user","id=".$assigned_by_user_id);
        $cc_mail=get_manager_and_skip_manager_email($assigned_to_user_id);
        $cc_mail=($cc_mail)?$cc_mail.','.$self_cc_mail:$self_cc_mail;
	    $to_mail=$customer->email;		    
	    if($to_mail)
	    {
	    	$this->load->library('mail_lib');
	        $m_d = array();
	        $m_d['from_mail']     = $session_data['email'];
	        $m_d['from_name']     = $session_data['name'];
	        $m_d['to_mail']       = $to_mail;
	        $m_d['cc_mail']       = $cc_mail;
	        $m_d['subject']       = 'Enquiry # '.$lead_id.' - Introduction of new Account Manager';
	        $m_d['message']       = $template_str;
	        $m_d['attach']        = array();
	        $mail_return = $this->mail_lib->send_mail($m_d);
	    }
		// END
		// =============================

    	$status_str='success';  
        $result["status"] = $status_str;
        $result["assigned_to_user_name"]=$assigned_to_user_name;
        $result["return"]=$lead_info;
        echo json_encode($result);
        exit(0); 
    }


    public function add_ajax()
	{
		$data=array();
		$data['mobile']='';
		$data['email']='';
		$data['company']=array();
		
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$data['state_list']=array();
			$data['city_list']=array();
				
			$cid=($this->input->post('cid'))?$this->input->post('cid'):'';
			$call_sync_id=($this->input->post('call_sync_id'))?$this->input->post('call_sync_id'):'';
			

			$data['is_customer_basic_data_show']=$this->input->post('is_customer_basic_data_show');
			$data['is_search_box_show']=$this->input->post('is_search_box_show');
			$data['mobile']=trim($this->input->post('mobile'));
			$data['email']=trim($this->input->post('email'));
			
			if($call_sync_id>0){				
				$data['call_sync_id']=$call_sync_id;
				$data['call_sync_info']=$this->lead_model->get_sync_call_one($call_sync_id);
				$data['mobile']=$data['call_sync_info']->number;
				$data['call_sync_com_source_id']=$this->lead_model->call_sync_com_source_id();
				if($data['call_sync_info']->country_code){
					$country_code_tmp=str_replace("+", "", $data['call_sync_info']->country_code);
					$data['call_sync_com_country_id']=$this->lead_model->call_sync_com_country_id($country_code_tmp);
					$data['state_list']=$this->states_model->GetStatesList($data['call_sync_com_country_id']);
				}
				else{
					$data['call_sync_com_country_id']='';
				}
				
			}
			else{
				$data['call_sync_info']=array();
			}
			
			if($cid>0)
			{	
				$data['company']=$this->customer_model->company_search_to_add_lead_by_id($cid);
				$assigned_user_id=$data['company'][0]['assigned_user_id'];
				$data['state_list']=$this->states_model->GetStatesList($data['company'][0]['country_id']);
				$data['city_list']=$this->cities_model->GetCitiesList($data['company'][0]['state']);
				
			}
			else
			{
				if($data['mobile']!='' || $data['email']!='')
				{
					$data['company']=$this->customer_model->company_search_to_add_lead($data['mobile'],$data['email']);
					$assigned_user_id=$data['company'][0]['assigned_user_id'];
					
					if(count($data['company'])==1){
						$data['state_list']=$this->states_model->GetStatesList($data['company'][0]['country_id']);
						$data['city_list']=$this->cities_model->GetCitiesList($data['company'][0]['state']);
						if($data['company'][0]['is_blacklist']=='Y'){
							echo'blacklist';exit;
						}					
					}
				}
				else
				{
					$assigned_user_id=0;
				}
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
	   			$m_id='0';
	   		}
	   		else
	   		{
	   			$m_id=$this->user_model->get_manager_id($user_id);
	   		}
	        $user_ids = $this->user_model->get_under_employee_ids($m_id,0);			
	        array_push($user_ids, $user_id);
	        $user_ids_str=implode(',', $user_ids);
	        $user_list=$this->user_model->GetUserList($user_ids_str);
	        
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
	        $data['user_id']=$user_id;			
			if(count($data['company'])>1)
			{
				$data['is_add_a_new_lead_form_show']='N';
			}
			else
			{
				$data['is_add_a_new_lead_form_show']='Y';
			}
		}
		
		$this->load->view('admin/lead/add_lead_ajax',$data);		
	}

	public function add($flag=NULL,$c_id=NULL)
	{
		redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/lead/manage/?action=add');
		/*
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
		$this->load->view('admin/lead/add_lead',$data);
		*/
	}

public function add_source_ajax()
{
	if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
	{
		$err_msg="";	

		$source=trim($this->input->post('source'));	
		if($source)
		{
			$post_data=array('name'=>$source);
			$chk=get_value("name","source","name='".$source."'");
			if($chk=="")
			{
				$this->Source_model->add($post_data);
				$status_str='success';
				$msg='Record successfully added.';
			}
			else
			{
				$status_str='error';
				$msg='Oops!Source already exist in our system.';
			}
		}	
		else
		{
			$status_str='error';
			$msg='Oops! Source should not be blank.';
		}	
		
		
		
		$data=array();
		$data['source_list']=$this->Source_model->GetSourceListAll();
		$options = $this->load->view('admin/lead/get_source_options',$data, true);

        $result["status"] = $status_str;
        $result['msg']=$msg;
        $result['options']=$options;
        echo json_encode($result);
        exit(0);
	}	
}

function add_lead_ajax()
{
	if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
	{
		//print_r($_POST);
		$assigned_user_id=$this->input->post('assigned_user_id');
		// $get_immidiate_manager_id=$this->user_model->get_manager_id($assigned_user_id);
		
		// LEAD INFO		
		$product_tags_arr=$this->input->post('product_tags');
		// $lead_title=$this->input->post('lead_title');
		$lead_title='';
		
        
        $lead_requirement = $this->input->post('lead_requirement'); 
        $lead_enq_date = date_display_format_to_db_format($this->input->post('lead_enq_date'));
        $lead_follow_up_date = date_display_format_to_db_format($this->input->post('lead_follow_up_date'));
        $lead_follow_up_type=($this->input->post('lead_follow_up_type'))?$this->input->post('lead_follow_up_type'):'';
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
        $com_office_std_code = $this->input->post('office_std_code');        
        $com_office_phone = $this->input->post('office_phone');

        $com_address = $this->input->post('com_address');
        $com_country_id = ($this->input->post('com_country_id')>0)?$this->input->post('com_country_id'):$this->input->post('com_existing_country');
        $com_state_id = ($this->input->post('com_state_id')>0)?$this->input->post('com_state_id'):$this->input->post('com_existing_state');
        $com_city_id = ($this->input->post('com_city_id')>0)?$this->input->post('com_city_id'):$this->input->post('com_existing_city');
        $com_zip = $this->input->post('com_zip');
        $com_website = $this->input->post('com_website');

        $selected_source_id = ($this->input->post('com_source_id')>0)?$this->input->post('com_source_id'):$this->input->post('com_existing_source');
        $existing_com_source_id=0;
        if($com_company_id)
        {
        	$existing_com_source_id=get_value("source_id","customer","id=".$com_company_id);
        }
        if($existing_com_source_id>0)
        {
        	$com_source_id = $existing_com_source_id;
        }
        else
        {
        	$com_source_id = $selected_source_id;
        }
        $lead_source_id = $selected_source_id;
        // $com_source_id = ($this->input->post('com_source_id')>0)?$this->input->post('com_source_id'):$this->input->post('com_existing_source');
        $com_short_description = $this->input->post('com_short_description');

        $country_code='';
        if($com_mobile_country_code=='' && $com_country_id>0)
        {
        	$country_code=get_value("phonecode","countries","id=".$com_country_id);
        	// $com_mobile_country_code=$country_code;
        	// $com_alt_mobile_country_code=$country_code;
        	// $com_landline_country_code=$country_code;        	
        } 
        else
        {
        	$country_code=$com_mobile_country_code;
        } 
        
		

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
			'office_std_code'=>$com_office_std_code,
			'office_phone'=>$com_office_phone,
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
			'modify_date'=>date('Y-m-d'),
			'status'=>'1'
		);
        

        if($com_company_id)
        {
        	$this->customer_model->UpdateCustomer($company_post_data,$com_company_id);

        	// =================================================
			// CREATE COMPANY HISTORY LOG
			// $update_by=$this->session->userdata['admin_session_data']['user_id'];
			// $date=date("Y-m-d H:i:s");
			// $ip_addr = $this->input->ip_address();
			// $message="A copy of the existing quotation &quot;".$opportunity_title.' - Copy'."&quot;";
			// $comment_title=QUOTATION_COPY;
			// $historydata=array(
			// 				'comment'=>addslashes($message),
			// 				'create_date'=>$date,
			// 				'updated_by_user_id'=>$update_by,
			// 				'ip_address'=>$ip_addr
			// 				);
			// $this->history_model->CreateHistory($historydata);	
			// CREATE COMPANY HISTORY LOG
			// =================================================
        }
        else
        {
        	$com_company_id=$this->customer_model->CreateCustomer($company_post_data);

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
        

        
		if($lead_enq_date!=$lead_follow_up_date){
			$is_followup_date_changed='Y';
		}
		else{
			$is_followup_date_changed='N';
		}

        $lead_post_data=array(
			'title'=>$lead_title,
			'customer_id'=>$com_company_id,
			'source_id'=>$lead_source_id,
			'assigned_user_id'=>$assigned_user_id,
			'buying_requirement'=>$lead_requirement,
			'enquiry_date'=>$lead_enq_date,
			'followup_date'=>$lead_follow_up_date,
			'is_followup_date_changed'=>$is_followup_date_changed,
			'followup_type_id'=>$lead_follow_up_type,
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
        $lead_id=$this->lead_model->CreateLead($lead_post_data);        
		
        if($lead_id)
		{
			if(count($product_tags_arr))
			{
				foreach($product_tags_arr AS $p)
				{
					$p_arr=explode("~",$p);
					$p_name=$p_arr[0];
					$p_id=$p_arr[1];						
					$lead_title .=$p_name.', ';	
					if(isset($p_id))
					{
						$lead_p_data=array(
							'lead_id'=>$lead_id,
							'name'=>$p_name,
							'product_id'=>$p_id,
							'tag_type'=>'L',
							'created_at'=>date("Y-m-d H:i:s")
						);
						$this->lead_model->CreateLeadWiseProductTag($lead_p_data);
					}			
				}
				$lead_title=rtrim($lead_title,", ");

				// Update Lead
				$lead_post_data=array('title'=>$lead_title);
				$this->lead_model->UpdateLead($lead_post_data,$lead_id);  
			}	

			// if(count($product_tags_arr))
			// {
			// 	foreach($product_tags_arr AS $p)
			// 	{
			// 		$p_name=get_value("name","product_varient","id=".$p);					
			// 		$lead_p_data=array(
			// 			'lead_id'=>$lead_id,
			// 			'name'=>$p_name,
			// 			'product_id'=>$p,
			// 			'tag_type'=>'L',
			// 			'created_at'=>date("Y-m-d H:i:s")
			// 		);
	        // 		$this->lead_model->CreateLeadWiseProductTag($lead_p_data);
			// 	}
			// }

			// Insert Stage Log
	        $stage_post_data=array(
					'lead_id'=>$lead_id,
					'stage_id'=>'1',
					'stage'=>'PENDING',
					'stage_wise_msg'=>'',
					'create_datetime'=>date("Y-m-d H:i:s")
				);
	        $this->lead_model->CreateLeadStageLog($stage_post_data);

	        // Insert Status Log
	        $status_post_data=array(
					'lead_id'=>$lead_id,
					'status_id'=>'2',
					'status'=>'WARM',
					'create_datetime'=>date("Y-m-d H:i:s")
				);
	        $this->lead_model->CreateLeadStatusLog($status_post_data);


			$attach_filename='';
			// LEAD ATTACH FILE UPLOAD
			$this->load->library('upload', '');
			if($_FILES['lead_attach_file']['name'] != "")
			{
				$config2['upload_path'] = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/lead/";
				$config2['allowed_types'] = "gif|jpg|jpeg|png|pdf";
				$config2['max_size'] = '1000000'; //KB
				$this->upload->initialize($config2);
				if (!$this->upload->do_upload('lead_attach_file'))
				{
				    //return $this->upload->display_errors();
				}
				else
				{
				    $file_data = array('upload_data' => $this->upload->data());
				    $attach_filename = $file_data['upload_data']['file_name'];
				    $update_lead_data = array(
						'attach_file' => $attach_filename,
						'modify_date' => date("Y-m-d")
					);								
					$this->lead_model->UpdateLead($update_lead_data,$lead_id);
				}
			}
            // end


			$assigned_by_user_id=$this->session->userdata['admin_session_data']['user_id'];
			// -------------------------------------------------
			// ASSIGN LEAD LOG TABLE
			$post_data=array(
						'lead_id'=>$lead_id,
						'assigned_to_user_id'=>$assigned_user_id,
						'assigned_by_user_id'=>$assigned_by_user_id,
						'is_accepted'=>'Y',
						'assigned_datetime'=>date("Y-m-d H:i:s")
						);			
		    $this->lead_model->create_lead_assigned_user_log($post_data);
		    
		   

		    $update_by=$this->session->userdata['admin_session_data']['user_id'];				
			$date=date("Y-m-d H:i:s");				
			$ip_addr=$_SERVER['REMOTE_ADDR'];				
			$message="A new lead has been created as &quot;".$lead_title."&quot;";
			$comment_title=NEW_LEAD_CREATE_MANUAL;
			$historydata=array(
								'title'=>$comment_title,
								'lead_id'=>$lead_id,
								'comment'=>addslashes($message),
								'attach_file'=>$attach_filename,
								'create_date'=>$date,
								'user_id'=>$update_by,
								'ip_address'=>$ip_addr
							);
			$this->history_model->CreateHistory($historydata);

			// Lead Acknowledgement id 1
			$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(1);
			// SMS for Acknowledgement id 1
			$sms_forwarding_setting=$this->Sms_forwarding_setting_model->GetDetails(1);
			// WhatsApp for Acknowledgement id 1
			$whatsapp_forwarding_setting=$this->Whatsapp_forwarding_setting_model->GetDetails(1);

			if($email_forwarding_setting['is_mail_send']=='Y' || count($sms_forwarding_setting)>0 || count($whatsapp_forwarding_setting)>0)
			{
				//$user_id=$this->session->userdata['admin_session_data']['user_id'];
				$assigned_to_user_data=$this->user_model->get_employee_details($assigned_user_id);
				$company=get_company_profile();	
				$lead_info=$this->lead_model->GetLeadData($lead_id);
				$customer=$this->customer_model->GetCustomerData($lead_info->customer_id);
			}

			// MAIL ALERT
			if($email_forwarding_setting['is_mail_send']=='Y')
			{
				// ============================
				// Mail Acknowledgement & 
				// account manager update
				// START

				// TO CLIENT				
			    $e_data=array();				
				$e_data['company']=$company;
				$e_data['assigned_to_user']=$assigned_to_user_data;
				$e_data['customer']=$customer;
				$e_data['lead_info']=$lead_info;
				$e_data['product_tags_str']=implode(', ',$product_tags_arr);
		        // $template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_view', $e_data, true);

		        
				$template_str_tmp=FCPATH .'application/views/admin/email_template/template/enquiry_acknowledgement_view_'.$this->session->userdata['admin_session_data']['client_id'].'.php';
				if (file_exists($template_str_tmp)){
				    $template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_view_'.$this->session->userdata['admin_session_data']['client_id'], $e_data, true);
				}else{
				    $template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_view', $e_data, true);
				}
		        $m_email=get_manager_and_skip_manager_email_arr($assigned_user_id);
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
		        $self_cc_mail=get_value("email","user","id=".$assigned_user_id);
		        $update_by_name=get_value("name","user","id=".$assigned_user_id);
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
		        /*
		        $cc_mail='';
		        $self_cc_mail=get_value("email","user","id=".$update_by);
		        $update_by_name=get_value("name","user","id=".$update_by);
		        $cc_mail=get_manager_and_skip_manager_email($assigned_user_id);
		        $cc_mail=($cc_mail)?$cc_mail.','.$self_cc_mail:$self_cc_mail;
			    $to_mail=$customer->email;
			    */
			    if($to_mail)
			    {
			    	$get_company_detail=$this->customer_model->get_company_detail($com_company_id);		    	
			    	if($get_company_detail['contact_person']!='')
					{
						$mail_subject=$get_company_detail['contact_person'].', Your Enquiry # '.$lead_id.' has been received - '.$company['name']; 
					}
					else
					{
						$mail_subject='Your Enquiry # '.$lead_id.' has been acknowledged';
					}

			    	$this->load->library('mail_lib');
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
					$m_mobile=get_manager_and_skip_manager_mobile_arr($assigned_user_id);
					$default_template_auto_id=$sms_forwarding_setting['default_template_id'];
					$user_id=$this->session->userdata['admin_session_data']['user_id'];
					$sms_variable_info=array("customer_id"=>$lead_info->customer_id,'company_id'=>1,'lead_id'=>$lead_id,'user_id'=>$assigned_user_id);
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
						$relationship_manager_mobile=get_value("mobile","user","id=".$assigned_user_id);					
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

			// WhatsApp ALERT
			if(count($whatsapp_forwarding_setting))
			{			
				if($whatsapp_forwarding_setting['is_whatsapp_send']=='Y')
				{				
					$m_mobile=get_manager_and_skip_manager_mobile_arr($assigned_user_id);
					$default_template_auto_id=$whatsapp_forwarding_setting['default_template_id'];
					$user_id=$this->session->userdata['admin_session_data']['user_id'];
					$whatsapp_variable_info=array("customer_id"=>$lead_info->customer_id,'company_id'=>1,'lead_id'=>$lead_id,'user_id'=>$assigned_user_id);
					// --------------------
					// to WhatsApp send logic
					$whatsapp_send_data=array();			
					$client_mobile='';
					if($whatsapp_forwarding_setting['is_send_whatsapp_to_client']=='Y')
					{
						$client_mobile=$customer->mobile;					
						$client_template_auto_id=($whatsapp_forwarding_setting['send_whatsapp_to_client_template_id'])?$whatsapp_forwarding_setting['send_whatsapp_to_client_template_id']:$default_template_auto_id;				
						$whatsapp_send_data[]=array('mobile'=>$client_mobile,'template_auto_id'=>$client_template_auto_id);
					}	

					$relationship_manager_mobile='';
					if($whatsapp_forwarding_setting['is_send_relationship_manager']=='Y')
					{
						$relationship_manager_mobile=get_value("mobile","user","id=".$assigned_user_id);					
						$relationship_manager_template_auto_id=($whatsapp_forwarding_setting['send_relationship_manager_template_id'])?$whatsapp_forwarding_setting['send_relationship_manager_template_id']:$default_template_auto_id;						        	
						$whatsapp_send_data[]=array('mobile'=>$relationship_manager_mobile,'template_auto_id'=>$relationship_manager_template_auto_id);
					}	
					
					$manager_mobile='';
					if($whatsapp_forwarding_setting['is_send_manager']=='Y')
					{
						if($m_mobile['manager_mobile']!='')
						{		      
							$manager_mobile=$m_mobile['manager_mobile'];
							$manager_template_auto_id=($whatsapp_forwarding_setting['send_manager_template_id'])?$whatsapp_forwarding_setting['send_manager_template_id']:$default_template_auto_id;
							$whatsapp_send_data[]=array('mobile'=>$manager_mobile,'template_auto_id'=>$manager_template_auto_id);
						}		        	
					}

					$skip_manager_mobile='';
					if($whatsapp_forwarding_setting['is_send_skip_manager']=='Y')
					{
						if($m_mobile['skip_manager_mobile']!='')
						{		        		
							$skip_manager_mobile=$m_mobile['skip_manager_mobile'];
							$skip_manager_template_auto_id=($whatsapp_forwarding_setting['send_skip_manager_template_id'])?$whatsapp_forwarding_setting['send_skip_manager_template_id']:$default_template_auto_id;
							$whatsapp_send_data[]=array('mobile'=>$skip_manager_mobile,'template_auto_id'=>$skip_manager_template_auto_id);
						}		        	
					}
					// to WhatsApp send logic	
					// --------------------					
					// $header=$whatsapp_forwarding_setting['whatsapp_name'];
					$header='Your Enquiry '.$lead_id.' has been acknowledged';
					$return=send_template_message_by_whatsapp($header,$whatsapp_send_data,$whatsapp_variable_info);
				}
			}
		}

		$call_sync_id=($this->input->post('call_sync_id'))?$this->input->post('call_sync_id'):'';

		if($call_sync_id)
		{
			$post_data = array(
				'tagged_lead_id'=>$lead_id,
				'status' =>'2'
			);										
			$this->lead_model->update_sync_call($post_data,$call_sync_id);
		}
		
		$result["call_sync_id"] = $call_sync_id;
		$status_str='success';
        $result["status"] = $status_str;
        $result['msg']=$company_post_data;
        $result['lead_id']=$lead_id;
        $result['company_id']=$com_company_id;
        echo json_encode($result);
        exit(0);
	}
}

	
	public function exist_cus_assign()
	{
		
		if($this->input->post('command')=='1')
		{
			$customer_id=$this->input->post('customer_id');
			$source_id='1';
			$assigned_user_id=$this->input->post('assigned_to');
			$mail_id=$this->input->post('mail_id');			
			
			$mail_data=$this->email_model->GetEmailData($mail_id);
			
			$enquiry_date = date("Y-m-d");			
			$ip=$_SERVER['REMOTE_ADDR'];
			$createdata=array('customer_id'=>$customer_id,'title'=>addslashes($mail_data->subject),'source_id'=>$source_id,'assigned_user_id'=>$assigned_user_id,'enquiry_date'=>$enquiry_date,'create_date'=>date("Y-m-d"),'modify_date'=>date("Y-m-d"),'assigned_date'=>date("Y-m-d"),'status'=>'1');
			
			$lead_id=$this->lead_model->CreateLead($createdata);			
			$notification='A New Lead Added By '.$this->session->userdata['admin_session_data']['name'];
				$createnotificationdata=array('notification'=>$notification,'status'=>'0','create_date'=>date("Y-m-d H:i:s"));
				$this->notification_model->CreateNotification($createnotificationdata);
			
			
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
				
			$date=date("Y-m-d H:i:s");
			
			$ip_addr=$_SERVER['REMOTE_ADDR'];
			
			$message="New reply got from buyer over mail";
			$comment_title=NEW_LEAD_CREATE_MAIL;
			$historydata=array('lead_id'=>$lead_id,'comment'=>addslashes($mail_data->message),'create_date'=>$date,'user_id'=>$update_by,'ip_address'=>$ip_addr);
			$this->history_model->CreateHistory($historydata);	
			
			
			$update_status=$this->email_model->RepliedEmail($mail_id);
			
			
			echo '1';
			exit;
			
			
			
		}
		
		
		
		
	}
	
	
	
	public function lead_tag()
	{
		
		
		if($this->input->post('command')=='1')
		{
			$customer_id=$this->input->post('customer_id');
			
			$lead_id=$this->input->post('lead_id');	
					
			$mail_id=$this->input->post('mail_id');			
			$assigned_user_id=$this->input->post('assigned_user_id');			
			
			$mail_data=$this->email_model->GetEmailData($mail_id);
			
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
				
			$date=date("Y-m-d H:i:s");
			
			$ip_addr=$_SERVER['REMOTE_ADDR'];
			
			$message="New reply got from buyer over mail";
			$comment_title=LEAD_TAGGED;
			$historydata=array('title'=>$comment_title,'lead_id'=>$lead_id,'comment'=>addslashes($message),'create_date'=>$date,'user_id'=>$update_by,'ip_address'=>$ip_addr);
			$this->history_model->CreateHistory($historydata);	
			
			
			$update_status=$this->email_model->RepliedEmail($mail_id);
			
			$reply=$this->input->post('reply');
			if($reply!='' || $reply!=NULL)
			{
			
				$this->email->from('lmsbaba@maxbridgesolution.com', 'LMS Baba Webmaster');
				$this->email->to($mail_data->from_email);
				$this->email->subject(addslashes($mail_data->subject));
				$this->email->message($reply);	
				$this->email->send(); 					
				
			}	
			
			
			echo '1';
			exit;
			
			
			
		}
		else if($this->input->post('command')=='2')
		{
			
			
			$lead_id=$this->input->post('lead_id');	
			$description=$this->input->post('description');	
			$email=$this->input->post('email');	
			$mobile=$this->input->post('mobile');	
					
			
			$date=date("Y-m-d H:i:s");	
			$ip=$_SERVER['REMOTE_ADDR'];
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$createcommentdata=array('lead_id'=>$lead_id,'comment'=>addslashes($mail_data->message),'create_date'=>$date,'user_id'=>$update_by,'ip_address'=>$ip);
						
			$result=$this->lead_model->CreateLeadComment($createcommentdata);	
			
			$lead_data=$this->lead_model->GetLeadData($lead_id);
			/*if($lead_data->cus_mobile!=$mobile && $mobile!='')
			{
				$data=array('office_phone'=>$mobile);
				$result_mob=$this->customer_model->UpdateCustomer($data,$lead_data->cus_id);
			}*/
			if($lead_data->cus_email!=$email && $email!='')
			{
				CheckUserSpace();	
				$data=array('alt_email'=>$email);
				$result_mob=$this->customer_model->UpdateCustomer($data,$lead_data->cus_id);
			}
			if($result_mob)
			{
				
			
				/*$update_by=$this->session->userdata['admin_session_data']['user_id'];
				$update_by_name=$this->session->userdata['admin_session_data']['name'];
				$date=date("Y-m-d H:i:s");
				$body_date=date("d-M-y h:i:s A");
				$ip_addr=$_SERVER['REMOTE_ADDR'];
				$history_comment=LeadBuyerUpdate($lead_data->title,$update_by_name,$body_date);
				$historydata=array('lead_id'=>$lead_id,'comment'=>addslashes($history_comment),'create_date'=>$date,'user_id'=>$update_by,'ip_address'=>$ip_addr);
				$this->history_model->CreateHistory($historydata);*/
			}
			echo '1';
			exit;
			
			
		}
		
	}
	
	
	public function original_quotation_view_rander_ajax()
	{
		$data=NULL;
		$lead_id=$this->input->post('lead_id');	
		$data['lead_id']=$lead_id;		
		$data['lead_data']=$this->lead_model->GetLeadData($lead_id);	
		$this->load->view('admin/lead/original_quotation_modal_ajax',$data);
	}

	public function edit($id)
	{
	
		$data=array();
		$data['flag']='';	
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$this->Product_model->DeleteTempProductByUser($user_id);
		//if($this->input->post('command')=='1')
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$customer_id=$this->input->post('customer_id');
			$source_id=$this->input->post('source');
			$assigned_user_id=$this->input->post('assigned_to');
			$enquiry_date=$this->input->post('enquiry_date');
			$description=$this->input->post('description');			
			$title=$this->input->post('title');
			$lead_id=$this->input->post('lead_id');
			$enquiry_date = date("Y-m-d", strtotime($enquiry_date));
			$updatedata=array('customer_id'=>$customer_id,'title'=>$title,'source_id'=>$source_id,'assigned_user_id'=>$assigned_user_id,'description'=>$description,'enquiry_date'=>$enquiry_date,'modify_date'=>date("Y-m-d"),'status'=>'1');
			$result=$this->lead_model->UpdateLead($updatedata,$lead_id);	
			if($result)
			{				
				/*$date=date("Y-m-d H:i:s");
				$ip_addr=$_SERVER['REMOTE_ADDR'];
				$body_date=date("d-M-y h:i:s A");
				$update_by_name=$this->session->userdata['admin_session_data']['name'];
				$history_comment=LeadGeneralUpdate($title,$update_by_name,$body_date);
				$historydata=array('title'=>NEW_LEAD_CREATE_MANUAL'lead_id'=>$lead_id,'comment'=>addslashes($history_comment),'create_date'=>$date,'user_id'=>$user_id,'ip_address'=>$ip_addr);
				$this->history_model->CreateHistory($historydata);*/
				//CheckUserSpace();	
				redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/lead/manage');
			}
		}
		
		$data['lead_id']=$id;
		$data['lead_last_updated_date']=$this->lead_model->get_lead_last_updated_date($id);
		$data['cus_data']=$this->lead_model->GetLeadData($id);	
		$data['opportunity_stage_list']=$this->opportunity_model->GetOpportunityStageListAll();	
		// Quotation List
		$data['opportunity_list']=$this->opportunity_model->GetOpportunityListAll($id);
		
		$data['tmp_prod_list']=$this->Product_model->GetTempProductList($user_id);		
		
		$data['user_list']=$this->user_model->GetUserListAll('');	
		$data['source_list']=$this->source_model->GetSourceListAll();
		$data['currency_list']=$this->lead_model->GetCurrencyList();
		$data['country_list']=$this->countries_model->GetCountriesList();
		$data['state_list']=$this->states_model->GetStatesList($data['cus_data']->country_id);
		$data['city_list']=$this->cities_model->GetCitiesList($data['cus_data']->state);
		
		$data['comment_list']=$this->lead_model->GetLeadCommentListAll($id,'');	
		$data['communication_list']=$this->lead_model->GetCommunicationList();	


		$data['vendor_key_val']=$this->vendor_model->get_vendor_key_val();
		$data['currency_list']=$this->Product_model->GetCurrencyList();
		$data['unit_type_list']=$this->Product_model->GetUnitList();
		$data['led_prev_id']=$this->lead_model->prev_next_id($id,'prev');
		$data['led_next_id']=$this->lead_model->prev_next_id($id,'next');
		$l_data['lead_id']=$id;		
		$l_data['lead_data']=$this->lead_model->GetLeadData($id);	
		$data['org_lead']=$this->load->view('admin/lead/original_quotation_modal_ajax',$l_data,TRUE);
		$data['regret_reason_list']=get_regret_reason();
		$data['user_id']=$user_id;
		
		$this->load->view('admin/lead/edit_lead',$data);
		//die;
	}

	public function rander_lead_update_pre_define_comment()
	{
		//$user_id=$this->input->post('user_id');
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$list['rows']=$this->pre_define_comment_model->GetLeadUpdatePreDefineComments($user_id);			
	    $html = $this->load->view('admin/lead/rander_lead_update_pre_define_comment_ajax',$list,TRUE);	    
	    $data =array (
	       "html"=>$html
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit();
	}

	public function add_lead_update_pre_define_comment()
	{
		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		//$user_id=$this->input->post('user_id');
		$title=$this->input->post('title');
		$description=$this->input->post('description');
		$return='';
		if($title!='' && $description!='')
		{
			$post=array(
					'user_id'=>$user_id,
					'title'=>$title,
					'comment'=>$description
					);
			$return=$this->pre_define_comment_model->add($post);			
			if($return)
			{
				$msg='Record successfully added';
				$status='success';
			}	
			else
			{
				$msg='System fail to add record';
				$status='fail';
			}
		}
		else
		{
			$msg='All fields required';
			$status='fail';
		}
		    
	    $data =array (
		   "msg"=>$msg,
		   "status"=>$status,
		   "id"=>$return,
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	public function delete_lead_update_pre_define_comment()
	{
		$id=$this->input->post('id');	
		
		$return=$this->pre_define_comment_model->delete($id);			
		if($return)
		{
			$msg='Record successfully deleted';
			$status='success';
		}	
		else
		{
			$msg='System fail to delete the record';
			$status='fail';
		}    
	    $data =array (
		   "msg"=>$msg,
		   "status"=>$status
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	public function update_lead_update_pre_define_comment()
	{
		$id=$this->input->post('id');		
		$title=$this->input->post('title');
		$description=$this->input->post('description');
		$post=array(
					'title'=>$title,
					'comment'=>$description
					);
		$return=$this->pre_define_comment_model->edit($post,$id);			
		if($return)
		{
			$msg='Record successfully updated';
			$status='success';
		}	
		else
		{
			$msg='System fail to update the record';
			$status='fail';
		}    
	    $data =array (
		   "msg"=>$msg,
		   "status"=>$status
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	public function rander_history_for_lead_update()
	{
		$lead_id=$this->input->post('lead_id');
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$list['rows']=$this->lead_model->GetHistoryForLeadUpdateComments($lead_id,$user_id);			
	    $html = $this->load->view('admin/lead/rander_history_for_lead_update_ajax',$list,TRUE);	    
	    $data =array (
	       "html"=>$html,
	       "history_count"=>count($list['rows']),
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	public function rander_mail_trail_for_mail_to_client()
	{
		$lead_id=$this->input->post('lead_id');
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$list['rows']=$this->lead_model->GetHistoryForLeadUpdateComments($lead_id,$user_id);			
	    $html = $this->load->view('admin/lead/rander_mail_trail_for_mail_to_client_ajax',$list,TRUE);	    
	    $data =array (
	       "html"=>$html,
	       "history_count"=>count($list['rows']),
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	public function edit_ajax()
	{
		
		$data=array();
		$data['flag']='';	
		
		
		$customer_id=$this->input->post('customer_id');
		$source_id=$this->input->post('source');
		$assigned_user_id=$this->input->post('assigned_to');
		$enquiry_date=$this->input->post('enquiry_date');
		$description=$this->input->post('description');
		
		$title=$this->input->post('title');
		$lead_id=$this->input->post('lead_id');
		
		$enquiry_date = date("Y-m-d", strtotime($enquiry_date));
		
		
		$updatedata=array('customer_id'=>$customer_id,'title'=>$title,'source_id'=>$source_id,'assigned_user_id'=>$assigned_user_id,'description'=>$description,'enquiry_date'=>$enquiry_date,'status'=>'1');
		$result=$this->lead_model->UpdateLead($updatedata,$lead_id);	
		$ip=$_SERVER['REMOTE_ADDR'];
		$createcommentdata=array('lead_id'=>$lead_id,'comment'=>addslashes($description),'create_date'=>$enquiry_date,'ip_address'=>$ip);
		
		$result=$this->lead_model->CreateLeadComment($createcommentdata);	
		
		
			
		$update_by=$this->session->userdata['admin_session_data']['user_id'];
		$date=date("Y-m-d H:i:s");
		$ip_addr=$_SERVER['REMOTE_ADDR'];
		$history_comment=LeadGeneralUpdate($title,$update_by,$date);
		$historydata=array('lead_id'=>$lead_id,'comment'=>addslashes($history_comment),'create_date'=>$date,'user_id'=>$update_by,'ip_address'=>$ip_addr);
		$this->history_model->CreateHistory($historydata);
				
				
		$data['cus_data']=$result=$this->lead_model->GetLeadData($lead_id);	
		
		$data['user_list']=$this->user_model->GetUserListAll();
		$data['source_list']=$this->source_model->GetSourceListAll();
		
		$data['country_list']=$this->countries_model->GetCountriesList();
		$data['state_list']=$this->states_model->GetStatesList($data['cus_data']->country_id);
		$data['city_list']=$this->cities_model->GetCitiesList($data['cus_data']->state);
		$this->load->view('admin/lead/edit_lead_ajax',$data);
	}
	
	public function create_lead_comment_ajax()
	{
		
		$lead_id=$this->input->post('lead_id');			
		$lead_info=$this->lead_model->GetLeadData($lead_id);
		// echo $lead_info->current_stage_id; die();
		$description=$this->input->post('general_description');
		$mail_to_client=($this->input->post('mail_to_client'))?'Y':'N';		
		$mark_cc_mail_arr=$this->input->post('mark_cc_mail');
		$client_not_interested=($this->input->post('client_not_interested'))?'Y':'N';
		$lead_regret_reason=$this->input->post('lead_regret_reason');
		$lead_regret_reason_id=$this->input->post('lead_regret_reason_id');
		$communication_type_id=$this->input->post('communication_type');		
		$communication_type=get_value("title","communication_master","id=".$communication_type_id);
		$next_followup_date=$this->input->post('next_followup_date');
		$next_followup_type_id=($this->input->post('next_followup_type_id'))?$this->input->post('next_followup_type_id'):'';
		$lead_comments_for_mail_trail_str=$this->input->post('lead_comments_for_mail_trail');

		$mail_trail_html='';
		$mail_trail_html_tmp='';
		if($lead_comments_for_mail_trail_str)
		{	
			$list['rows']=$this->lead_model->GetLeadCommentsById($lead_comments_for_mail_trail_str);				
		    $mail_trail_html .= $this->load->view('admin/lead/rander_mail_trail_ajax',$list,TRUE);
		    $mail_trail_html_tmp .='<br>';
		    $mail_trail_html_tmp .=$mail_trail_html;
		}


		$mail_to_client_mail_subject='';
		$regret_this_lead_mail_subject='';
		

		//-------------- HISTORY ----------------------------------
		$history_text = '';
		$update_by=$this->session->userdata['admin_session_data']['user_id'];
		$ip_addr=$_SERVER['REMOTE_ADDR'];		
		$comment_title=LEAD_GENERAL_UPDATE;
		$history_text .= addslashes($description);
		// --------------------------------------------------------

		if($next_followup_date)
		{	
			$next_followup_date=datetime_display_format_to_db_format_ampm($next_followup_date);
			// =======================================
			if(($lead_info->current_stage_id=='3' || $lead_info->current_stage_id=='5' || $lead_info->current_stage_id=='6' || $lead_info->current_stage_id=='7') && $client_not_interested=='N')
			{

				$get_prev_stage=$this->lead_model->get_prev_stage($lead_id);
				$get_prev_status=$this->lead_model->get_prev_status($lead_id);

				$history_text .= '<br> Stage changed from <b>'.$lead_info->current_stage.'</b> to <b>'.$get_prev_stage->stage.'</b>';
				$history_text .= '<br> Status changed from <b>'.$lead_info->current_status.'</b> to <b>'.$get_prev_status->status.'</b>';

				// UPDATE LEAD STAGE/STATUS
				$update_lead_data=array();
				$update_lead_data = array(
					'current_stage_id' =>$get_prev_stage->stage_id,
					'current_stage' =>$get_prev_stage->stage,
					'current_stage_wise_msg' =>'',
					// 'current_status_id'=>$get_prev_status->status_id,
					// 'current_status'=>$get_prev_status->status,
					'modify_date'=>date("Y-m-d")
				);								
				$this->lead_model->UpdateLead($update_lead_data,$lead_id);
				// Insert Stage Log
				$stage_post_data=array();
				$stage_post_data=array(
						'lead_id'=>$lead_id,
						'stage_id'=>$get_prev_stage->stage_id,
						'stage'=>$get_prev_stage->stage,
						'stage_wise_msg'=>'',
						'create_datetime'=>date("Y-m-d H:i:s")
					);
				$this->lead_model->CreateLeadStageLog($stage_post_data);

				// Insert Status Log
				$status_post_data=array();
				$status_post_data=array(
						'lead_id'=>$lead_id,
						'status_id'=>$get_prev_status->status_id,
						'status'=>$get_prev_status->status,
						'create_datetime'=>date("Y-m-d H:i:s")
					);
				// $this->lead_model->CreateLeadStatusLog($status_post_data);

				// ----------------------------------------
				// REMOVE ALL STAGE OF 
				// REGRETTED(3),
				// DEAL LOST(5),
				// AUTO-REGRETTED(6),
				// AUTO DEAL LOST(7) 
				// FROM LOG
				// AND REMOVE ALL STATUS OF
				// COLD(3),
				// NOT FOLLOWED(4)
				// ----------------------------------------
				$this->lead_model->remove_all_inactive_status_and_stage($lead_id);						
				// ----------------------------------------
				// END
				// ----------------------------------------

			}
			// =======================================
			// UPDATE LEAD NEXT FOLLOW UP
			if($lead_info->is_followup_date_changed=='N'){
				if($lead_info->followup_date!=$next_followup_date){
					$is_followup_date_changed='Y';				
				}
				else{
					$is_followup_date_changed='N';
				}
			}
			else{
				$is_followup_date_changed='Y';
			}
			
			
			$update_lead_data=array();
			$update_lead_data = array(
				'followup_date' =>$next_followup_date,
				'is_followup_date_changed'=>$is_followup_date_changed,
				'modify_date'=>date("Y-m-d")
			);		
			$this->lead_model->UpdateLead($update_lead_data,$lead_id);
		}
		
		// LEAD ATTACH FILE UPLOAD
		$lead_attach_file_removed=$this->input->post('lead_attach_file_removed');
		if($lead_attach_file_removed!='')
		{
			$removed_attach_file_arr=explode(",", $lead_attach_file_removed);
		}
		else
		{
			$removed_attach_file_arr=array(3);
		}
		$attach_filename=[];
		$attach_filename_with_path=array();
		$this->load->library('upload', '');
		if($_FILES['lead_attach_file']['tmp_name'])
        {
        	$dataInfo = array();
        	$files = $_FILES;
            $cpt = count($_FILES['lead_attach_file']['name']);
            $config = array(
            'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/lead/",
			'file_ext_tolower'=>TRUE,
            'allowed_types' => "gif|jpg|jpeg|png|pdf|doc|docx|mp3|mp4|mpeg|amr|wav|xlsx",                        
            'max_size' => "2048000",
			'overwrite'=>FALSE, 
            );

        	$this->upload->initialize($config);
            for($i=0; $i<$cpt; $i++)
            {
            	if(!in_array($i, $removed_attach_file_arr))
            	{
            		$_FILES['lead_attach_file']['name']= $files['lead_attach_file']['name'][$i];
	                $_FILES['lead_attach_file']['type']= $files['lead_attach_file']['type'][$i];
	                $_FILES['lead_attach_file']['tmp_name']= $files['lead_attach_file']['tmp_name'][$i];
	                $_FILES['lead_attach_file']['error']= $files['lead_attach_file']['error'][$i];
	                $_FILES['lead_attach_file']['size']= $files['lead_attach_file']['size'][$i];
	            	if (!$this->upload->do_upload('lead_attach_file'))
	            	{
	            		// echo $this->upload->display_errors();
	            		// die();
	            	}
	                else
	                {
	                	$dataInfo = $this->upload->data();
	                    $filename=$dataInfo['file_name']; //Image Name
	                    $attach_filename[]=$filename;	                    
	                    $attach_filename_with_path[]="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/lead/".$filename;
	                    
	                }
            	}            	
            }
        }
		
		
		// end
		// =====================================================
		$admin_session_data_user_data=$this->user_model->get_employee_details($update_by);
		$company=get_company_profile();	
		
		$assigned_user_id=$lead_info->assigned_user_id;
		$customer=$this->customer_model->GetCustomerData($lead_info->customer_id);
		$user_data=$this->user_model->get_employee_details($assigned_user_id);

		
		$session_data=$this->session->userdata('admin_session_data');
		$this->load->library('mail_lib');

		
		// Enquiry Regret Mail 5
		$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(5);
		$m_email=get_manager_and_skip_manager_email_arr($assigned_user_id);

		$regret_mail_from_mail='';
		$regret_mail_from_name='';
		if($client_not_interested=='Y')
		{
			
			$regret_this_lead_mail_subject=($this->input->post('regret_this_lead_mail_subject'))?$this->input->post('regret_this_lead_mail_subject'):'Enquiry # '.$lead_id.' - Query/Update from your A/C Manager';
			$tmp_current_stage_id=$lead_info->current_stage_id;
			if($tmp_current_stage_id==1)
			{
				$changed_stage_id=3;
				$changed_stage='REGRETTED';

				$changed_status_id=3;
				$changed_status='COLD';
			}
			else if($tmp_current_stage_id==2)
			{
				$changed_stage_id=5;
				$changed_stage='DEAL LOST';

				$changed_status_id=3;
				$changed_status='COLD';
			}
			else if($tmp_current_stage_id==4)
			{
				$changed_stage_id=5;
				$changed_stage='DEAL LOST';

				$changed_status_id=3;
				$changed_status='COLD';
			}
			else
			{
				$changed_stage_id=3;
				$changed_stage='REGRETTED';

				$changed_status_id=3;
				$changed_status='COLD';
			}
			// UPDATE LEAD STAGE/STATUS
			$update_lead_data = array(
				'current_stage_id' =>$changed_stage_id,
				'current_stage' =>$changed_stage,
				'current_stage_wise_msg' =>$lead_regret_reason,
				// 'current_status_id'=>$changed_status_id,
				// 'current_status'=>$changed_status,
				'lost_reason'=>$lead_regret_reason_id,
				'followup_date'=>'',
				'is_hotstar'=>'N',
				'modify_date'=>date("Y-m-d")
			);								
			$this->lead_model->UpdateLead($update_lead_data,$lead_id);
			// Insert Stage Log
			$stage_post_data=array(
					'lead_id'=>$lead_id,
					'stage_id'=>$changed_stage_id,
					'stage'=>$changed_stage,
					'stage_wise_msg'=>$lead_regret_reason,
					'create_datetime'=>date("Y-m-d H:i:s")
				);
			$this->lead_model->CreateLeadStageLog($stage_post_data);

			// Insert Status Log
			$status_post_data=array(
					'lead_id'=>$lead_id,
					'status_id'=>$changed_status_id,
					'status'=>$changed_status,
					'create_datetime'=>date("Y-m-d H:i:s")
				);
			// $this->lead_model->CreateLeadStatusLog($status_post_data);


			$history_text .= '<br> Stage changed from <b>'.$lead_info->current_stage.'</b> to <b>'.$changed_stage.'</b>';
			// $history_text .= '<br> Status changed from <b>'.$lead_info->current_status.'</b> to <b>'.$changed_status.'</b>';
			if($lead_regret_reason)
			{
				$history_text .= '<br> Lead Regret Reasons: '.$lead_regret_reason;
			}
			$lead_info=$this->lead_model->GetLeadData($lead_id);
			
			// EMAIL CONTENT
			$e_data=array();
			$e_data['company']=$company;
			$e_data['assigned_to_user']=$user_data;
			$e_data['customer']=$customer;
			$e_data['lead_info']=$lead_info;
			$template_str = $this->load->view('admin/email_template/template/enquiry_regret_view', $e_data, true);				
			// LEAD ASSIGNED MAIL	  

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
	        $self_cc_mail=get_value("email","user","id=".$assigned_user_id);
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
			if(trim($to_mail)!='' && $email_forwarding_setting['is_mail_send']=='Y')
			{
				// $mail_data = array();
				// $mail_data['from_mail']     = $session_data['email'];
				// $mail_data['from_name']     = $session_data['name'];
				// $mail_data['to_mail']       = $to_mail;        
				// $mail_data['cc_mail']       = $cc_mail;               
				// $mail_data['subject']       = $regret_this_lead_mail_subject;
				// $mail_data['message']       = $template_str;
				// $mail_data['attach']        = array();
				// $this->mail_lib->send_mail($mail_data);
				$post_data=array();
				$post_data=array(
						"mail_for"=>MF_UPDATE_LEAD,
						"from_mail"=>$session_data['email'],
						"from_name"=>$session_data['name'],
						"to_mail"=>$to_mail,
						"cc_mail"=>$cc_mail,
						"subject"=>$regret_this_lead_mail_subject,
						"message"=>$template_str,
						"attach"=>'',
						"created_at"=>date("Y-m-d H:i:s")
				);
				$this->App_model->mail_fire_add($post_data);
			}
			
			// MAIL SEND
			// ===============================================

			$regret_mail_from_mail=$session_data['email'];
			$regret_mail_from_name=$session_data['name'];

			// product tagged with quoted lead
			$prod_list=$this->lead_model->get_tagged_ps_list($lead_id,'L');
			if(count($prod_list))
			{
				foreach($prod_list AS $product)
				{
					$lead_p_data=array(
						'lead_id'=>$lead_id,
						'name'=>$product['name'],
						'product_id'=>$product['product_id'],
						'tag_type'=>'LL',
						'created_at'=>date("Y-m-d H:i:s")
					);
					$this->lead_model->CreateLeadWiseProductTag($lead_p_data);
				}
			}
			// --------------------

		}

		// Lead Update id 9
		$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(9);
		$m_email=get_manager_and_skip_manager_email_arr($assigned_user_id);


		$mail_to_client_from_mail='';
		$mail_to_client_from_name='';
		if($mail_to_client=='Y')
		{    	
			$mail_to_client_mail_subject=($this->input->post('mail_to_client_mail_subject'))?$this->input->post('mail_to_client_mail_subject'):'Enquiry # '.$lead_id.' - Query/Update from your A/C Manager';
						
			$cc_mail_arr=array();
	        $self_cc_mail=get_value("email","user","id=".$assigned_user_id);	        
	        // --------------------
	        // cc mail assign logic
	        if($email_forwarding_setting['is_send_relationship_manager']=='Y')
	        {
	        	array_push($cc_mail_arr, $self_cc_mail);
	        }

	        if($email_forwarding_setting['is_send_manager']=='Y')
	        {
	        	if($m_email['manager_email']!='')
	        	{		        		
	        		array_push($cc_mail_arr, $m_email['manager_email']);
	        	}		        	
	        }

	        if($email_forwarding_setting['is_send_skip_manager']=='Y')
	        {
	        	if($m_email['skip_manager_email']!='')
	        	{		        		
	        		array_push($cc_mail_arr, $m_email['skip_manager_email']);
	        	}		        	
	        }
	        $cc_mail='';
	        $cc_mail=implode(",", $cc_mail_arr);
	        // cc mail assign logic
	        // --------------------

			// EMAIL CONTENT
			$e_data=array();		
			$e_data['company']=$company;
			$e_data['assigned_to_user']=$user_data;
			$e_data['customer']=$customer;
			$e_data['lead_info']=$lead_info;
			$e_data['updated_comments']=addslashes($description).$mail_trail_html_tmp;
			$template_str = $this->load->view('admin/email_template/template/update_reply_to_customers_view', $e_data, true);			    

			$to_mail='';               	
			$to_mail=$customer->email;
			// LEAD ASSIGNED MAIL	 
			// $mail_data = array();			
			// $mail_data['from_mail']     = $session_data['email'];
			// $mail_data['from_name']     = $session_data['name'];
			// $mail_data['to_mail']       = $to_mail;        
			// $mail_data['cc_mail']       = $cc_mail;               
			// $mail_data['subject']       = $mail_to_client_mail_subject;
			// $mail_data['message']       = $template_str;			
			if(count($attach_filename_with_path)>0)
			{
				// $mail_data['attach'] = $attach_filename_with_path;
				$attach_tmp=serialize($attach_filename_with_path);
			}
			else
			{
				// $mail_data['attach'] = array();
				$attach_tmp='';
			}
			// $this->mail_lib->send_mail($mail_data);
			if(trim($to_mail))
			{
				$post_data=array();
				$post_data=array(
						"mail_for"=>MF_UPDATE_LEAD,
						"from_mail"=>$session_data['email'],
						"from_name"=>$session_data['name'],
						"to_mail"=>$to_mail,
						"cc_mail"=>$cc_mail,
						"subject"=>$mail_to_client_mail_subject,
						"message"=>$template_str,
						"attach"=>$attach_tmp,
						"created_at"=>date("Y-m-d H:i:s")
				);
				$this->App_model->mail_fire_add($post_data);
			}
			

			$mail_to_client_from_mail=$session_data['email'];
			$mail_to_client_from_name=$session_data['name'];
			// MAIL SEND
			// ===============================================
		}

		
		$cc_mail_tmp="";
		if($mark_cc_mail_arr)
		{	
			$to_mail='';
			$cc_mail_tmp=implode(",", $mark_cc_mail_arr);
			$to_mail=$cc_mail_tmp;
			$cc_mail_arr=array();
	        $self_cc_mail=get_value("email","user","id=".$assigned_user_id);	        
	        // --------------------
	        // cc mail assign logic
	        if($email_forwarding_setting['is_send_relationship_manager']=='Y')
	        {
	        	array_push($cc_mail_arr, $self_cc_mail);
	        }

	        if($email_forwarding_setting['is_send_manager']=='Y')
	        {
	        	if($m_email['manager_email']!='')
	        	{		        		
	        		array_push($cc_mail_arr, $m_email['manager_email']);
	        	}		        	
	        }

	        if($email_forwarding_setting['is_send_skip_manager']=='Y')
	        {
	        	if($m_email['skip_manager_email']!='')
	        	{		        		
	        		array_push($cc_mail_arr, $m_email['skip_manager_email']);
	        	}		        	
	        }
	        $cc_mail='';
	        $cc_mail=implode(",", $cc_mail_arr);
	        // cc mail assign logic
	        // --------------------
			
			// EMAIL CONTENT
			$e_data=array();	
			$e_data['last_lead_comment']=$this->lead_model->last_lead_comment($lead_id);
			$e_data['get_quotation_count']=$this->opportunity_model->get_quotation_count($lead_id);

			$e_data['company']=$company;
			$e_data['assigned_to_user']=$user_data;
			$e_data['customer']=$customer;
			$e_data['lead_info']=$lead_info;
			$e_data['updated_comments']=addslashes($description).$mail_trail_html_tmp;
			$template_str = $this->load->view('admin/email_template/template/update_mail_to_employee_view', $e_data, true);        
			// LEAD ASSIGNED MAIL   
			
			// $mail_data = array();			
			// $mail_data['from_mail']     = $session_data['email'];
			// $mail_data['from_name']     = $session_data['name'];
			// $mail_data['to_mail']       = $to_mail;        
			// $mail_data['cc_mail']       = $cc_mail;               
			// $mail_data['subject']       ='LMS Update: '.$customer->company_name.' # '.$customer->id.'/ Lead ID# '.$lead_id;
			// $mail_data['message']       = $template_str;
			// $mail_data['attach']        = array();
			// $this->mail_lib->send_mail($mail_data);
			if(trim($to_mail))
			{
				$post_data=array();
				$post_data=array(
						"mail_for"=>MF_UPDATE_LEAD,
						"from_mail"=>$session_data['email'],
						"from_name"=>$session_data['name'],
						"to_mail"=>$to_mail,
						"cc_mail"=>$cc_mail,
						"subject"=>'LMS Update: '.$customer->company_name.' # '.$customer->id.'/ Lead ID# '.$lead_id,
						"message"=>$template_str,
						"attach"=>'',
						"created_at"=>date("Y-m-d H:i:s")
				);
				$this->App_model->mail_fire_add($post_data);
			}			
			// MAIL SEND
			// ===============================================
		}


		
		if($email_forwarding_setting['is_mail_send']=='Y')
		{
			// ============================
			// When ever lead will be updated
			// START

			// EMAIL CONTENT
			$e_data=array();		
			$e_data['last_lead_comment']=$this->lead_model->last_lead_comment($lead_id);
			$e_data['get_quotation_count']=$this->opportunity_model->get_quotation_count($lead_id);
			$e_data['company']=$company;
			$e_data['assigned_to_user']=$user_data;
			$e_data['customer']=$customer;
			$e_data['lead_info']=$lead_info;
			$e_data['updated_comments']=addslashes($description).$mail_trail_html_tmp;
			$template_str = $this->load->view('admin/email_template/template/update_mail_to_employee_view', $e_data, true); 


			
	        // --------------------
	        // to mail assign logic
	        $to_mail_assign='';
	        $to_mail='';
	        if($email_forwarding_setting['is_send_relationship_manager']=='Y')
	        {
	        	$to_mail=get_value("email","user","id=".$assigned_user_id);
	        	$to_mail_assign='self';
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
	        // --------------------
	        // cc mail assign logic
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
			
			if(trim($to_mail))
			{
				// LEAD ASSIGNED MAIL
				// $mail_data = array();				
				// $mail_data['from_mail']     = $session_data['email'];
				// $mail_data['from_name']     = $session_data['name'];
				// $mail_data['to_mail']       = $to_mail;        
				// $mail_data['cc_mail']       = $cc_mail;               
				// $mail_data['subject']       ='LMS Update: '.$customer->company_name.' # Lead ID# '.$lead_id;
				// $mail_data['message']       = $template_str;
				// $mail_data['attach']        = array();
				// $this->mail_lib->send_mail($mail_data);

				$post_data=array();
				$post_data=array(
						"mail_for"=>MF_UPDATE_LEAD,
						"from_mail"=>$session_data['email'],
						"from_name"=>$session_data['name'],
						"to_mail"=>$to_mail,
						"cc_mail"=>$cc_mail,
						"subject"=>'LMS Update: '.$customer->company_name.' # Lead ID# '.$lead_id,
						"message"=>$template_str,
						"attach"=>'',
						"created_at"=>date("Y-m-d H:i:s")
				);
				$this->App_model->mail_fire_add($post_data);
			}
			
			// MAIL SEND
			// ===============================================
		}
		

		//-------------- HISTORY ----------------------------------		
		$historydata=array(
						'title'=>$comment_title,
						'lead_id'=>$lead_id,
						'comment'=>$history_text,
						'mail_trail_html'=>$mail_trail_html,
						'mail_trail_ids'=>$lead_comments_for_mail_trail_str,
						'cc_to_employee'=>$cc_mail_tmp,
						'mail_to_client'=>$mail_to_client,
						'mail_to_client_from_mail'=>$mail_to_client_from_mail,
						'mail_to_client_from_name'=>$mail_to_client_from_name,						
						'regret_mail_from_mail'=>$regret_mail_from_mail,
						'regret_mail_from_name'=>$regret_mail_from_name,
						'mail_subject_of_update_lead_mail_to_client'=>$mail_to_client_mail_subject,
						'mail_subject_of_update_lead_regret_this_lead'=>$regret_this_lead_mail_subject,
						'attach_file'=>implode("|$|",$attach_filename),
						'communication_type_id'=>$communication_type_id,
						'communication_type'=>$communication_type,
						'next_followup_date'=>$next_followup_date,
						'create_date'=>date("Y-m-d H:i:s"),
						'user_id'=>$update_by,
						'ip_address'=>$ip_addr
						);
		$this->history_model->CreateHistory($historydata);	
		// ----------------------------------------------------------	

		$data =array (
					   "status"=>'success',
					   "msg"=>'The lead successfully updated.'
					);
		$this->output->set_content_type('application/json');
		echo json_encode($data);
		exit(0);
	}
	
	// AJAX PAGINATION START
	function get_list_ajax()
	{
		$session_data=$this->session->userdata('admin_session_data');
	    $start = $this->input->get('page');
	    $view_type = $this->input->get('view_type');
	    
	    $this->load->library('pagination');
	    $limit=20;
	    $config = array();
	    $list=array();
	    $arg=array();
		
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		   
	    // FILTER DATA
		$arg['filter_search_str']=$this->input->get('filter_search_str');
		$arg['lead_from_date']=$this->input->get('filter_lead_from_date');
		$arg['lead_to_date']=$this->input->get('filter_lead_to_date');
		$arg['date_filter_by']=$this->input->get('filter_date_filter_by');
		$arg['assigned_user']=($this->input->get('filter_assigned_user'))?$this->input->get('filter_assigned_user'):$tmp_u_ids_str;
		$arg['lead_applicable_for']=$this->input->get('filter_lead_applicable_for');
		$arg['filter_lead_type']=$this->input->get('filter_lead_type');
		$arg['filter_lead_type_wise_stages']='';
		/*if($arg['filter_lead_type'])
		{
			$arg['filter_lead_type_wise_stages']=$this->lead_model->get_type_wise_lead_stage($arg['filter_lead_type']);
		}*/

		$arg['opportunity_stage_filter_type']=$this->input->get('filter_opportunity_stage_filter_type');
		$arg['opportunity_stage']=$this->input->get('filter_opportunity_stage');
	    $arg['opportunity_status']=$this->input->get('filter_opportunity_status');
	    $arg['source_ids']=$this->input->get('filter_by_source');
		$arg['is_hotstar']=$this->input->get('filter_is_hotstar');
		$arg['pending_followup']=$this->input->get('filter_pending_followup');
		$arg['pending_followup_for']=$this->input->get('filter_pending_followup_for');
	    $arg['filter_sort_by']=$this->input->get('filter_sort_by'); 
	    $arg['filter_search_by_id']=$this->input->get('filter_search_by_id'); 
	    $arg['filter_like_dsc']=$this->input->get('filter_like_dsc'); 	    
		$arg['filter_common_lead_pool']=$this->input->get('filter_common_lead_pool');
		$arg['filter_followup']=$this->input->get('filter_followup');
		$arg['assigned_observer']=$user_id;
		$arg['filter_lead_observer']=$this->input->get('filter_lead_observer');
		if($arg['filter_followup']=='AL')
		{
			$arg['filter_lead_type']='AL';
		}
		//echo $arg['filter_like_dsc'].'/'.$arg['pending_followup_for'].'/'.$arg['filter_followup'].'/'.$arg['pending_followup'].'/'.$arg['filter_common_lead_pool']; die();
		$arg['filter_non_active_stages']=$this->lead_model->get_type_wise_lead_stage('NAL');
		if($arg['filter_like_dsc']!='' || $arg['pending_followup_for']!='' || $arg['filter_followup']!='' || $arg['pending_followup']!='' || $arg['filter_common_lead_pool']!='N' || $arg['filter_followup']=='AL')
		{	
			$arg['filter_lead_type_wise_stages']=$this->lead_model->get_type_wise_lead_stage('AL');
			
		}
		else
		{
			$arg['filter_lead_type_wise_stages']=$this->lead_model->get_type_wise_lead_stage($arg['filter_lead_type']);
			
		}
		
		//echo $arg['filter_lead_type_wise_stages']; die();
		//print_r($arg); die();
		//$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->lead_model->get_list_count($arg);
		//$config['total_rows'] = 30;
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
	    // $list['priority_wise_stage_key_val']=$this->lead_model->priority_wise_stage_key_val();
	    $list['priority_wise_stage']=$this->lead_model->priority_wise_stage();
	    //print_r($arg); die();
    	$list['rows']=$this->lead_model->get_list($arg);
    	$list['c2c_credentials']=$this->Setting_model->GetC2cCredentialsDetailsByUser($user_id);
		
	    $table = '';	
	    if($view_type=='grid')
	    {
	    	$table = $this->load->view('admin/lead/grid_view_ajax',$list,TRUE);
	    }
	    else
	    {
	    	$table = $this->load->view('admin/lead/list_view_ajax',$list,TRUE);
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
		$data['source_list']=$this->source_model->GetSourceListAllOrderByDesc();

		$arg['active_stages']=$this->lead_model->get_type_wise_lead_stage('AL');
		$data['is_common_lead_pool_available']=$this->lead_model->is_common_lead_pool_available($arg);
		$data['is_observer_available']=$this->lead_model->is_observer_available($arg);
		$data['currency_list']=$this->Product_model->GetCurrencyList();
		$this->load->view('admin/lead/lead_view',$data);
		
	}

	public function delete($id)
	{
		$data=array();
		$del=$this->lead_model->DeleteLead($id);
		CheckUserSpace();	
		redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/lead/manage');
		
	}
	
	public function download_lead()
	{
		$this->load->view('admin/lead/download_lead');
	}
	
	
	
	public function GetEmailList($flag=NULL)
	{
		
		if(isset($flag))
		{
			$data['flag']=$flag;
		}
		else
		{
			$flag=NULL;
		}
		$data['country_list']=$this->countries_model->GetCountriesList();
		$data['mail_list']=$this->email_model->GetEmailList($this->session->userdata['admin_session_data']['user_id']);
		$data['user_list']=$this->user_model->GetUserListAll();
		$this->load->view('admin/lead/download_lead_list',$data);
	}
	
	public function DeletedList($flag=NULL)
	{
		
		
		if(isset($flag))
		{
			$data['flag']=$flag;
		}
		else
		{
			$flag=NULL;
		}
		$data['country_list']=$this->countries_model->GetCountriesList();
		$data['mail_list']=$this->email_model->GetDeletedEmailList($this->session->userdata['admin_session_data']['user_id']);
		$data['user_list']=$this->user_model->GetUserListAll();
		$this->load->view('admin/lead/deleted_mail_list',$data);
	}
	
	public function getstatelist()
	{
		$data=array();
		
		$country_id=$this->input->post('country_id');
		$selected_id=$this->input->post('selected_id');
		if($country_id!='')	
		{
			$state_list=$this->states_model->GetStatesList($country_id);
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
			$city_list=$this->cities_model->GetCitiesList($state_id);
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
	
	public function GetTagLeadList()
	{
		
		$email=$this->input->post('email');		
		$data['mail_id']=$this->input->post('mail_id');	
		$data['i']=$this->input->post('row_id');	
		$data['cus_list']=$this->customer_model->GetCustomerListByEmail($email);		
		$this->load->view('admin/lead/getleadlist',$data);
		
	}
	
	public function modalbody()
	{
		$email=$this->input->post('email_id');		
		
		$data['i']=$this->input->post('row_id');
		$data['output']=$this->email_model->GetEmailData($email);		
		$data['attachment_list']=$this->email_model->GetEmailAttachmentList($email);	
			
		$this->load->view('admin/lead/modalbody',$data);
		
	}
	
	public function download_attachment($attch_id)
	{
		
		$attch_data=$this->email_model->GetEmailAttachmentData($attch_id);
		
		$headr_arr=explode('--^^--',$attch_data->email_header);
		
		foreach($headr_arr as $headr_dt)
		{
			$headr_arr_dt=explode('--^--',$headr_dt);
			if($headr_arr_dt[0]!='')
			{
				header($headr_arr_dt[0].':'.$headr_arr_dt[1]);
			}
			
		}
		echo $attch_data->attachment_file;
		
		exit;
		
		
	}
	
	
	public function LeadCommentList($lead_id)
	{
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
		$data['lead_data']=$this->lead_model->GetLeadData($lead_id);
		$data['company_name']=get_value("company_name","customer","id=".$data['lead_data']->customer_id);		
		$data['comment_list']=$this->lead_model->GetLeadCommentListAll($lead_id,($user_id>1)?$user_id:'');		
		$this->load->view('admin/lead/comment',$data);
	}
	
	public function EmailDelete()
	{
		
		$message=$this->input->post('reply');
		$message.="<br/> Reply from ".$this->session->userdata['admin_session_data']['name']." Via LMS BABA";
		$buyer_email=$this->input->post('buyer_email');
		$id=$this->input->post('mail_id');
		$delete_type=$this->input->post('delete_type');
		$maildata=$this->email_model->GetEmailData($id); 
		$this->email->from('lmsbaba@maxbridgesolution.com', 'LMS Baba Webmaster');
		$this->email->to($buyer_email);
		$this->email->subject(addslashes($maildata->subject));
		$this->email->message($message);	
		$this->email->send(); 
		
		
		$maildata_create=$this->email_model->DeleteEmail($id,$delete_type); 
		CheckUserSpace();
		redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/lead/getemaillist/');
	}
	
	public function EmailDelete_Multiple()
	{
		
		$message=$this->input->post('reply');
		$buyer_email=$this->input->post('buyer_email');
		$mail_id=$this->input->post('mail_id');
		$mail_id=explode(',',$mail_id);
		$delete_type=$this->input->post('delete_type');
		
		$all_email=explode(',',$buyer_email);
		$x=0;
		foreach($all_email as $email_data)
		{
			if($email_data!='')
			{
				$maildata=$this->email_model->GetEmailData($mail_id[$x]); 
				$message.="<br/> Reply from ".$this->session->userdata['admin_session_data']['name']." Via LMS BABA";
				
				$this->email->from('lmsbaba@maxbridgesolution.com', 'LMS Baba Webmaster');
				$this->email->to($email_data);
				$this->email->subject(addslashes($maildata->subject));
				$this->email->message($message);	
				$this->email->send(); 
				
				$maildata_create=$this->email_model->DeleteEmail($mail_id[$x],$delete_type); 
				$x++;
			}
		
		}
		CheckUserSpace();	
		redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/lead/getemaillist/');
	}
	
	
	function gmail_list($code=NULL)
	{
		
		$credentialsPath=expandHomeDirectory(CREDENTIALS_PATH);
		@unlink($credentialsPath);
		$_REQUEST['code']= $_GET['code'];		
        $client = getClient();
		
		
		$service = new Google_Service_Gmail($client);
		$user = 'me';

		// Print the labels in the user's account.
		//listLabels($service, $user);
		//die('ok');
		// Get the messages in the user's account.


		$email_rows=$this->email_model->GetEmailList($this->session->userdata['admin_session_data']['user_id']);
	
		$sync_time=$email_rows[0]->last_sync_time;
		$date = new DateTime($sync_time);
							
		$sync_time= $date->format('Y-m-d');
		$date1=date_create($sync_time);
		$date2=date_create(date('Y-m-d'));
		$diff=date_diff($date1,$date2);
		$day_count = $diff->format("%a");
		
		if(count($email_rows)==0)
		{
			$messages = listMessages($service, $user,'100','',$day_count);
			//print_r($messages); die('ok');
		}
		else
		{
			if($day_count>=1)
			{
				$messages = listMessages($service, $user,'100', '',$day_count);
			}
			else
			{
				$messages = listMessages($service, $user,'0', '',$day_count);
			}
			
			
		}

		

		foreach ($messages as $message) 
		{
				//print 'Message with ID: ' . $message->getId() . "\n";
				//echo "<pre>";
				//print_r($message);
				
				//$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
				
				$msgObj = getMessage($service, $user, $message->getId());
				
			    //echo "<pre>";				
				//print_r($msgObj->getFilename());
				
				$headerArr = getHeaderArr($msgObj->getPayload()->getHeaders());
				
				
				$abcd1=explode("smtp.mailfrom=",$headerArr['ARC-Authentication-Results']);
				 $abcd2=explode(";",$abcd1[1]);
				 $from=$abcd2[0];
				 $subject=(empty($headerArr['Subject']) ? '': $headerArr['Subject']);
				 $to=(empty($headerArr['Delivered-To']) ? '': $headerArr['Delivered-To']);
				 $bodyArr = getBody($msgObj->getPayload()->getParts());
				$messages=(empty($bodyArr[1]) ? '' : $bodyArr[1]);
	
	
	
	
   
    
    
				$chk_mail=$this->email_model->CheckExistEmail($message->getId());
				if(!$chk_mail)
				{
					$date = new DateTime($headerArr['Date']);
					$date_time= $date->format('Y-m-d H:i:s');
					
					$mail_data=array('subject'=>$subject,'user_id'=>$this->session->userdata['admin_session_data']['user_id'], 'from_email'=>$from,'to_email'=>$to,'message'=>$messages,'email_date'=>$date_time,'msg_no'=>$message->getId(),'seen'=>'1','last_sync_time'=>date("Y-m-d H:i:s"));
					$maildata_create=$this->email_model->CreateEmail($mail_data);    
					
					//To get email attachment
					
					$gmailparts=$msgObj->getPayload()->getParts();
				$is_attach=0;
				$ig=0;
				if(count($gmailparts)>0)
				{
					foreach($gmailparts AS $pts)
					{
						if($ig!=0)
						{
							$email_id=$maildata_create;
							$atch_filename=$pts->filename;
							$attachmentId=$pts['modelData']['body']->attachmentId;
							if($attachmentId!="" && $atch_filename!="")
							{
								$headr_dtl='';
								foreach($pts['modelData']['headers'] AS $atch_header)
								{
									$headr_dtl=$headr_dtl.$atch_header['name']."--^--".$atch_header['value']."--^^--";
								}
								
								$is_attach=1;
								$attachmentData= $service->users_messages_attachments->get('me', $message->getId(), $attachmentId);
								
								$decodedData = strtr($attachmentData->data, array('-' => '+', '_' => '/'));
								//file_put_contents($atch_filename, base64_decode($decodedData));
								$attch_data=array('email_id'=>$email_id,'email_header'=>$headr_dtl, 'attachment_file'=>base64_decode($decodedData), 'file_name'=>$atch_filename);
								$mailattachment_create=$this->email_model->CreateEmailAttachment($attch_data);
							}
							
							
						}
						$ig++;
					}
				}
				if($is_attach==1)
				{
					$mail_data=array('is_attach'=>'1');
					$maildata_create=$this->email_model->UpdateEmail($mail_data,$maildata_create);
				}
	    
			}
			else
			{
				//break;
			}		
		}
		//CheckUserSpace();
		redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/lead/getemaillist');
		
		
	}



	// ==========================================================
	// ==========================================================
	function quotation_stage_update_ajax()
    {
        $stage_id=$this->input->post('stage_id');
        $opportunity_id=$this->input->post('opportunity_id');

        $data = array(
                    'opportunity_stage'=>$stage_id,
                    'modify_date' =>date('Y-m-d H:i:s')
                );
        $this->opportunity_model->UpdateLeadOportunity($data,$opportunity_id);

        // ---------------------
        // Comments inserted
        $stage_name=get_value("name","opportunity_stage","id=".$stage_id);
        $lead_id=get_value("lead_id","lead_opportunity","id=".$opportunity_id);        	
		$ip=$this->input->ip_address();
		$update_by=$this->session->userdata['admin_session_data']['user_id'];
		$lead_log_data=array(
								'lead_id'=>$lead_id,
								'lead_opportunity_id'=>$opportunity_id,
								'comment'=>'Quotation stage changed to "'.$stage_name.'"',
								'create_date'=>date('Y-m-d H:i:s'),
								'user_id'=>$update_by,
								'ip_address'=>$ip);	
		inserted_lead_comment_log($lead_log_data);
		// Comments inserted
		// ---------------------

        $msg = "Quation stage successfully updated!"; // 'duplicate';         
        $this->session->set_flashdata('success_msg', $msg);

        $data =array (
                        "status"=>'success'
                    );
        $this->output->set_content_type('application/json');
        echo json_encode($data);
    }
	// ==========================================================	
	// ==========================================================

	
	// ==========================================================
	// check opportunity product added or not
	public function check_temp_product_ajax()
	{
		
		$user_id=$this->session->userdata['admin_session_data']['user_id'];	
		$temp_product_list=$this->Product_model->GetTempProductList($user_id);
		if(count($temp_product_list))
		{
			$msg='EXIST';
		}
		else
		{
			$msg='NOT_EXIST';
		}
    	$result['msg'] = $msg;
        echo json_encode($result);
        exit(0);
        
	}
	// check opportunity product added or not
	// ==========================================================

	// ==========================================================
	// check opportunity product added or not
	public function check_opportunity_product_ajax()
	{		
		//$user_id=$this->session->userdata['admin_session_data']['user_id'];	
		$opportunity_id=$this->input->post('opportunity_id');
		$prod_list=$this->Opportunity_model->get_opportunity_product($opportunity_id);
		if(count($prod_list))
		{
			$msg='EXIST';
		}
		else
		{
			$msg='NOT_EXIST';
		}
    	$result['msg'] = $msg;
        echo json_encode($result);
        exit(0);
        
	}
	// check opportunity product added or not
	// ==========================================================	

	public function download($file='')
	{	
		if($file!='')
		{	
			$this->load->helper(array('download'));	
			$file_name = base64_decode($file);
			$pth    =   file_get_contents("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/lead/".$file_name);
			force_download($file_name, $pth); 
			exit;
		}
	}

	function view_lead_history_ajax()
    {
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
    	$lead_id = $this->input->post('lid');  
    	$lead_data=$this->lead_model->GetLeadData($lead_id);
		$company_name=get_value("company_name","customer","id=".$lead_data->customer_id);  	
    	//$comment_list=$this->lead_model->GetLeadCommentListAll($lead_id,($user_id>1)?$user_id:'');

    	$comment_list=$this->lead_model->GetLeadCommentListAll($lead_id,'');	

    	$list['comment_list']=$comment_list;
    	$list['lead_data']=$lead_data;
    	$list['company_name']=$company_name;
    	$html = $this->load->view('admin/lead/lead_history_ajax',$list,TRUE);
    	$result['title']='History Log of "'.$lead_data->title.'". (Company Name: "'.$company_name.'")';
		$result['html'] = $html;
        echo json_encode($result);
        exit(0);
    }


    function po_upload_post_ajax()
    {
    	$redirect_uri_str='';
    	$owp_id='';
    	$lead_opp_id = $this->input->post('po_lead_opp_id');    	
    	// -----------------------
    	// RENEWAL    	
    	$is_renewal_available=($this->input->post('is_renewal_available'))?'Y':'N';
    	
    	if($is_renewal_available=='Y')
    	{
    		$renewal_date=date_display_format_to_db_format($this->input->post('renewal_date'));
    		$renewal_follow_up_date=date_display_format_to_db_format($this->input->post('renewal_follow_up_date'));
    		$renewal_requirement = $this->input->post('renewal_requirement');

    		$renewal_product_str=$this->Opportunity_product_model->get_product_str($lead_opp_id);   	
    		$renewal_id = $this->input->post('renewal_id');
    		// $renewal_detail_id = $this->input->post('renewal_detail_id');
    		if($renewal_id=='')
    		{
    			$renewal_customer_id = $this->input->post('renewal_customer_id');

				$renewal_post_data=array(
					'customer_id'=>$renewal_customer_id,
					'product_id'=>'',
					'created_at'=>date('Y-m-d H:i:s'),
					'Updated_at'=>date('Y-m-d H:i:s')
					);
	        	$renewal_id=$this->renewal_model->create($renewal_post_data);
    		}

    		if($renewal_id)
    		{
    			$renewal_detail_post_data=array(
					'renewal_id'=>$renewal_id,
					'next_follow_up_date'=>$renewal_follow_up_date,
					'renewal_date'=>$renewal_date,
					'description'=>$renewal_requirement,
					'created_at'=>date('Y-m-d H:i:s'),
					'Updated_at'=>date('Y-m-d H:i:s')
					);
	        	$renewal_detail_id=$this->renewal_model->createDetails($renewal_detail_post_data);

	        	if($renewal_product_str!='' && $renewal_detail_id>0)
		    	{
		    		$renewal_product_arr=explode(",", $renewal_product_str);
		    		if(count($renewal_product_arr))
		    		{
		    			$p_tmp_arr=array();
		    			foreach($renewal_product_arr AS $p_str)
		    			{
		    				$p_arr=explode("#", $p_str);
		    				$p_id=$p_arr[0];
		    				$p_name=$p_arr[1];
		    				$p_price=$p_arr[2];
		    				array_push($p_tmp_arr,$p_id);

		    				$renewal_p_data=array(
										'renewal_detail_id'=>$renewal_detail_id,
										'product_id'=>$p_id,
										'product_name'=>$p_name,
										'price'=>$p_price
									);
					        $this->renewal_model->CreateRenewalWiseProductTag($renewal_p_data);
		    			}
		    		}
		    	}
		    	
    		}
    		  		
    	}
    	// RENEWAL
    	// -----------------------
    	

    	
        $lead_id = $this->input->post('po_lead_id');
    	// $lead_opp_id =71;
    	// $lead_id =28;

        $file = $this->input->post('po_upload_file');
        $mark_cc_mail_arr = $this->input->post('po_upload_cc_to_employee');
        $sent_ack_to_client=($this->input->post('po_upload_sent_ack_to_client'))?'Y':'N';
        $describe_comments = $this->input->post('po_upload_describe_comments');
        $po_number=$this->input->post('po_number');
        $po_date=date_display_format_to_db_format($this->input->post('po_date'));
        $deal_value_as_per_purchase_order = $this->input->post('deal_value_as_per_purchase_order');

        $deal_value_currency_type = $this->input->post('currency_type');

        $is_po_tds_applicable=($this->input->post('is_po_tds_applicable'))?'Y':'N';
        if($is_po_tds_applicable=='Y'){
        	$po_tds_percentage = $this->input->post('po_tds_percentage');
        }
        else{
        	$po_tds_percentage ='';
        }

        // LEAD ATTACH FILE UPLOAD
		$attach_filename='';
		$this->load->library('upload', '');
		if($_FILES['po_upload_file']['name'] != "")
		{
			$config2['upload_path'] = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/";
			$config2['allowed_types'] = "gif|jpg|jpeg|png|pdf|doc|docx|txt|xlsx";
			$config2['max_size'] = '1000000'; //KB
			$config2['overwrite'] = FALSE; 
			$config2['encrypt_name'] = TRUE; 
			$this->upload->initialize($config2);
			if (!$this->upload->do_upload('po_upload_file'))
			{
			    //return $this->upload->display_errors();
			    $status_str='error';
		        $result['status']=$status_str;
		        $result['msg']=$this->upload->display_errors();
		        echo json_encode($result);
		        exit(0);
			}
			else
			{
			    $file_data = array('upload_data' => $this->upload->data());
			    $attach_filename = $file_data['upload_data']['file_name'];			    
			}
		}

		$quotation_id=get_value("id","quotation","opportunity_id=".$lead_opp_id);
		$quotation_data=$this->quotation_model->GetQuotationData($quotation_id);

		$updated_by=$this->session->userdata['admin_session_data']['user_id'];
		$company=get_company_profile();	
		$lead_info=$this->lead_model->GetLeadData($quotation_data['lead_opportunity_data']['lead_id']);
		//$lead_info=$this->lead_model->GetLeadData($lead_id);
		//$customer=$this->customer_model->GetCustomerData($lead_info->customer_id);
		$assigned_user_id=$lead_info->assigned_user_id;	
		$assigned_to_user=$this->user_model->get_employee_details($assigned_user_id);
		$quotation_info=$this->Opportunity_model->get_opportunity_details($lead_opp_id);
		$session_data=$this->session->userdata('admin_session_data');
		$this->load->library('mail_lib');
		
		// PO Recdeived Acknowledment 4
		$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(4);
		$m_email=get_manager_and_skip_manager_email_arr($updated_by);

        if($sent_ack_to_client=='Y' && $email_forwarding_setting['is_mail_send']=='Y')
	    {	    	
			// ============================
			// Update Mail to client 
			// START
			// $cc_mail='';
			// $self_cc_mail=get_value("email","user","id=".$updated_by);
			// $cc_mail=get_manager_and_skip_manager_email($assigned_user_id);
			// $cc_mail=($cc_mail)?$cc_mail.','.$self_cc_mail:$self_cc_mail;

	        

			// EMAIL CONTENT
		    $e_data=array();		
			//$e_data['company']=$company;
			$e_data['assigned_to_user']=$assigned_to_user;
			//$e_data['customer']=$customer;
			$e_data['lead_info']=$lead_info;
			$e_data['quotation_info']=$quotation_info;
			$e_data['quotation']=$quotation_data['quotation'];
			$e_data['lead_opportunity']=$quotation_data['lead_opportunity_data'];
			$e_data['company']=$quotation_data['company_log'];
			$e_data['customer']=$quotation_data['customer_log'];
			$e_data['terms']=$quotation_data['terms_log_only_display_in_quotation'];
			$e_data['product_list']=$quotation_data['product_list'];
			$e_data['po_number']=$po_number;
			$e_data['po_date']=$po_date;
	        $template_str = $this->load->view('admin/email_template/template/po_received_acknowledment_view', $e_data, true);			    
	        //$to_mail='';
	        //$to_mail=$user_data['email'];                	
	        //$to_mail=$quotation_data['customer_log']['email'];
		    // LEAD ASSIGNED MAIL	    
	    	
	    	// --------------------
	        // to mail assign logic
	        $to_mail_assign='';
	        $to_mail='';
	        if($email_forwarding_setting['is_send_mail_to_client']=='Y')
	        {
	        	$to_mail=$quotation_data['customer_log']['email'];
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
	        $self_cc_mail=get_value("email","user","id=".$updated_by);
	        //$update_by_name=get_value("name","user","id=".$updated_by);
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
	        	$mail_data = array();
		        $mail_data['from_mail']     = $session_data['email'];
		        $mail_data['from_name']     = $session_data['name'];
		        $mail_data['to_mail']       = $to_mail;        
		        $mail_data['cc_mail']       = $cc_mail;               
		        $mail_data['subject']       ='Thank you for the Purchase Order No ['.$po_number.']';
		        $mail_data['message']       = $template_str;
		        $mail_data['attach']        = array();
		        $this->mail_lib->send_mail($mail_data);
	        }
	        
			// MAIL SEND
			// ===============================================
		}
		
		// PO received appreciation mail to employee 8
		$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(8);
		$m_email=get_manager_and_skip_manager_email_arr($updated_by);
        //if(count($mark_cc_mail_arr))
        if($email_forwarding_setting['is_mail_send']=='Y')
	    {
	    	$cc_mail_tmp=implode(",", $mark_cc_mail_arr);
	    	$cc_to_employee=$cc_mail_tmp;	    	
	    	//$to_mail=$cc_mail_tmp;
	    	$self_cc_mail=get_value("email","user","id=".$assigned_user_id);
	        $update_by_name=get_value("name","user","id=".$assigned_user_id);

	    	// --------------------
	        // to mail assign logic
	        $to_mail_assign='';
	        $to_mail='';
	        if($email_forwarding_setting['is_send_relationship_manager']=='Y')
	        {
	        	$to_mail=$self_cc_mail;
	        	$to_mail_assign='self';
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
	    	// ============================
			// When CC will be marked to employees at the time of lead update
			// START
			//$cc_mail='';
			// $self_mail=get_value("email","user","id=".$updated_by);			
			// $cc_mail=get_manager_and_skip_manager_email($assigned_user_id);
			// $cc_mail=($cc_mail)?$cc_mail.','.$cc_mail_tmp:$cc_mail_tmp;
			// $to_mail=$self_mail;	
			// EMAIL CONTENT	  

			$cc_mail_arr=array();	        
	        // --------------------
	        // cc mail assign logic
	        if($email_forwarding_setting['is_send_relationship_manager']=='Y')
	        {
	        	array_push($cc_mail_arr, $self_cc_mail);
	        }

	        if($email_forwarding_setting['is_send_manager']=='Y')
	        {
	        	if($m_email['manager_email']!='')
	        	{		        		
	        		array_push($cc_mail_arr, $m_email['manager_email']);
	        	}		        	
	        }

	        if($email_forwarding_setting['is_send_skip_manager']=='Y')
	        {
	        	if($m_email['skip_manager_email']!='')
	        	{		        		
	        		array_push($cc_mail_arr, $m_email['skip_manager_email']);
	        	}		        	
	        }
	        $cc_mail='';
	        $cc_mail=implode(",", $cc_mail_arr);
	        if($cc_mail_tmp)
	        {
	        	$cc_mail=$cc_mail.','.$cc_mail_tmp;
	        }
	        // cc mail assign logic
	        // --------------------      
		    
			$e_data=array();		
			//$e_data['company']=$company;
			$e_data['assigned_to_user']=$assigned_to_user;
			//$e_data['customer']=$customer;
			$e_data['lead_info']=$lead_info;
			$e_data['quotation_info']=$quotation_info;
			$e_data['quotation']=$quotation_data['quotation'];
			$e_data['lead_opportunity']=$quotation_data['lead_opportunity_data'];
			$e_data['company']=$quotation_data['company_log'];
			$e_data['customer']=$quotation_data['customer_log'];
			$e_data['terms']=$quotation_data['terms_log_only_display_in_quotation'];
			$e_data['product_list']=$quotation_data['product_list'];
			$e_data['po_number']=$po_number;
			$e_data['po_date']=$po_date;
	        $template_str = $this->load->view('admin/email_template/template/po_received_acknowledment_to_employee_view', $e_data, true); 
	        
	        if($to_mail)
	        {
	        	// LEAD ASSIGNED MAIL
		        $mail_data = array();
		        $mail_data['from_mail']     = $session_data['email'];
		        $mail_data['from_name']     = $session_data['name'];
		        $mail_data['to_mail']       = $to_mail;        
		        $mail_data['cc_mail']       = $cc_mail;               
		        $mail_data['subject']       ='Hurray!! '.$assigned_to_user['name'].' has received a PO';	        
		        $mail_data['message']       = $template_str;
		        $mail_data['attach']        = array();
		        $this->mail_lib->send_mail($mail_data);
	        }		    
			// MAIL SEND
			// ===============================================
	    }
	    
		

		$post_data=array(
		'lead_opportunity_id'=>$lead_opp_id,
		'file_path'=>"assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/",
		'file_name'=>$attach_filename,
		'cc_to_employee'=>$cc_to_employee.','.$self_mail,
		'is_send_acknowledgement_to_client'=>$sent_ack_to_client,
		'comments'=>$describe_comments,
		'po_number'=>$po_number,
		'po_date'=>$po_date,
		'po_tds_percentage'=>$po_tds_percentage,
		'create_date'=>date("Y-m-d H:i:s")
		);
		$owp_id=$this->Opportunity_model->CreateOportunityWisePo($post_data);


		// UPDATE LEAD STAGE/STATUS
	    $update_lead_data = array(
	    	'current_stage_id' =>'4',
			'current_stage' =>'DEAL WON',
			'current_stage_wise_msg' =>'',
			'current_status_id'=>'2',
			'current_status'=>'HOT',
			'modify_date'=>date("Y-m-d")
		);								
		$this->lead_model->UpdateLead($update_lead_data,$lead_id);
		// Insert Stage Log

		// $is_nego_exist=$this->lead_model->is_stage_exist_in_log($lead_id,9);
		$is_nego_exist='Y';
		if($is_nego_exist=='N')
		{
			// STAGE NEGOTIATION
			$stage_post_data=array(
					'lead_id'=>$lead_id,
					'stage_id'=>'9',
					'stage'=>'NEGOTIATION',
					'stage_wise_msg'=>'',
					'create_datetime'=>date("Y-m-d H:i:s")
				);
			$this->lead_model->CreateLeadStageLog($stage_post_data);
		}
		
		// STAGE DEAL WON	
        $stage_post_data=array(
				'lead_id'=>$lead_id,
				'stage_id'=>'4',
				'stage'=>'DEAL WON',
				'stage_wise_msg'=>'',
				'create_datetime'=>date("Y-m-d H:i:s")
			);
        $this->lead_model->CreateLeadStageLog($stage_post_data);

        // Insert Status Log
        $status_post_data=array(
				'lead_id'=>$lead_id,
				'status_id'=>'2',
				'status'=>'HOT',
				'create_datetime'=>date("Y-m-d H:i:s")
			);
        $this->lead_model->CreateLeadStatusLog($status_post_data);


       	// LEAD OPPORTUNITY STATUS UPDATE
       	if($quotation_data['quotation']['is_extermal_quote']=='Y')
       	{
       		$data_opportunity=array(
       			'deal_value'=>$deal_value_as_per_purchase_order,
				'deal_value_as_per_purchase_order'=>$deal_value_as_per_purchase_order,
				'currency_type'=>$deal_value_currency_type,
				'status'=>4,
				'modify_date'=>date("Y-m-d H:i:s")
			);
       	}
       	else
       	{
       		$data_opportunity=array(
				'deal_value_as_per_purchase_order'=>$deal_value_as_per_purchase_order,
				'currency_type'=>$deal_value_currency_type,
				'status'=>4,
				'modify_date'=>date("Y-m-d H:i:s")
			);
       	}
		
		$this->Opportunity_model->UpdateLeadOportunity($data_opportunity,$lead_opp_id);


		// history log
		$update_by=$this->session->userdata['admin_session_data']['user_id'];
		$ip_addr=$_SERVER['REMOTE_ADDR'];		
		$comment_title=QUOTATION_WISE_PO_UPLOAD;
		$q_title=get_value("opportunity_title","lead_opportunity","id=".$lead_opp_id);
		// $link ='<a href='.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/lead/download_po/'.$owp_id.'><b>Download PO</b> <i class="fa fa-download" aria-hidden="true"></i></a>';
		$link='';
		$comment='PO uploaded for quotation ('.$q_title.') '.$link;
		$historydata=array(
							'title'=>$comment_title,
							'lead_id'=>$lead_id,
							'comment'=>addslashes($comment),
							'attach_file'=>'',
							'communication_type'=>'',
							'next_followup_date'=>'',
							'create_date'=>date("Y-m-d H:i:s"),
							'user_id'=>$update_by,
							'ip_address'=>$ip_addr
							);
		$this->history_model->CreateHistory($historydata);

		// product tagged with quoted lead
		$prod_list=$this->quotation_model->GetQuotationProductList($quotation_id);
		if(count($prod_list))
		{
			foreach($prod_list AS $product)
			{	
				$p_name=get_value("name","product_varient","id=".$product->product_id);
				$lead_p_data=array(
					'lead_id'=>$lead_id,
					'lead_opportunity_id'=>$lead_opp_id,
					'quotation_id'=>$quotation_id,
					'name'=>$p_name,
					'product_id'=>$product->product_id,
					'tag_type'=>'W',
					'created_at'=>date("Y-m-d H:i:s")
				);
				$this->lead_model->CreateLeadWiseProductTag($lead_p_data);
			}
		}
		// --------------------
		// ---------------------------------------
		// update to pro-forma-invoice and invoice
		/*
		$q_product_list=$this->quotation_model->GetQuotationProductList($quotation_id);
		$q_additional_charges_list=$this->opportunity_model->get_selected_additional_charges($lead_opp_id);
		if(count($q_product_list) || count($q_additional_charges_list))
		{
			$address_str='';
			$address_str .=$company['name'];
			$address_str .='<br>';
			$address_str .=$company['address'];			
			if(trim($company['address']) && trim($company['city_name'])){$address_str .=', ';}
			if(trim($company['city_name'])){$address_str .= $company['city_name'];}
			if(trim($company['city_name']) && trim($company['state_name'])){$address_str .= ', ';}
			if(trim($company['state_name'])){$address_str .=$company['state_name'].' - '.$company['pin'];}
			if(trim($company['state_name']) && trim($company['country_name'])){$address_str .= ', ';}
			if(trim($company['country_name'])){$address_str .=$company['country_name'];}

			$pro_inv_data=array(
						'lead_opportunity_wise_po_id'=>$owp_id,
						'bill_from'=>$address_str,
						'bill_to'=>$quotation_data['quotation']['letter_to'],
						'terms_conditions'=>$quotation_data['quotation']['letter_terms_and_conditions'],
						'currency_type'=>$quotation_data['quotation']['currency_type'],
						'created_at'=>date("Y-m-d H:i:s"),
						'updated_at'=>date("Y-m-d H:i:s")
						);
			$pro_forma_inv_id=$this->order_model->CreatePoProFormaInvoice($pro_inv_data);

			$inv_data=array(
						'lead_opportunity_wise_po_id'=>$owp_id,
						'bill_from'=>$address_str,
						'bill_to'=>$quotation_data['quotation']['letter_to'],
						'terms_conditions'=>$quotation_data['quotation']['letter_terms_and_conditions'],
						'currency_type'=>$quotation_data['quotation']['currency_type'],
						'created_at'=>date("Y-m-d H:i:s"),
						'updated_at'=>date("Y-m-d H:i:s")
						);
			$inv_id=$this->order_model->CreateInvoice($inv_data);
		}

		if(count($q_product_list))
		{
			if($pro_forma_inv_id!='' && $inv_id!='')
			{
				foreach($q_product_list AS $p)
				{
					// --------------
					// pro forma invoice
					$p_data=array(
						'po_pro_forma_invoice_id'=>$pro_forma_inv_id,
						'product_id'=>$p->product_id,
						'product_name'=>$p->product_name,
						'product_sku'=>$p->product_sku,
						'unit'=>$p->unit,
						'unit_type'=>$p->unit_type,
						'price'=>$p->price,
						'discount'=>$p->discount,
						'is_discount_p_or_a'=>$p->is_discount_p_or_a,
						'gst'=>$p->gst,
						'created_at'=>date('Y-m-d H:i:s')
						);
					$this->order_model->CreatePoProFormaInvoiceProduct($p_data);

					// --------------
					// invoice
					$p_data2=array(
						'po_invoice_id'=>$inv_id,
						'product_id'=>$p->product_id,
						'product_name'=>$p->product_name,
						'product_sku'=>$p->product_sku,
						'unit'=>$p->unit,
						'unit_type'=>$p->unit_type,
						'price'=>$p->price,
						'discount'=>$p->discount,
						'is_discount_p_or_a'=>$p->is_discount_p_or_a,
						'gst'=>$p->gst,
						'created_at'=>date('Y-m-d H:i:s')
						);
					$this->order_model->CreateInvoiceProduct($p_data2);
				}
			}			
		}

		if(count($q_additional_charges_list))
		{
			if($pro_forma_inv_id!='' && $inv_id!='')
			{
				foreach($q_additional_charges_list AS $ac)
				{
					// --------------
					// pro forma invoice
					$p_data=array(
						'po_pro_forma_invoice_id'=>$pro_forma_inv_id,
						'additional_charge_id'=>$ac->additional_charge_id,
						'additional_charge_name'=>$ac->additional_charge_name,
						'price'=>$ac->price,
						'discount'=>$ac->discount,
						'is_discount_p_or_a'=>$ac->is_discount_p_or_a,
						'gst'=>$ac->gst,
						'created_at'=>date('Y-m-d H:i:s')
						);
					$this->order_model->CreatePoProFormaInvoiceAdditionalCharges($p_data);

					// --------------
					// invoice
					$p_data2=array(
						'po_invoice_id'=>$inv_id,
						'additional_charge_id'=>$ac->additional_charge_id,
						'additional_charge_name'=>$ac->additional_charge_name,
						'price'=>$ac->price,
						'discount'=>$ac->discount,
						'is_discount_p_or_a'=>$ac->is_discount_p_or_a,
						'gst'=>$ac->gst,
						'created_at'=>date('Y-m-d H:i:s')
						);
					$this->order_model->CreateInvoiceAdditionalCharges($p_data2);
				}
			}			
		}
		*/
		// update to pro-forma-invoice and invoice
		// ---------------------------------------
		$redirect_uri_str='action=po_edit&lid='.$lead_id.'&l_opp_id='.$lead_opp_id.'&step=2&lowp='.$owp_id;
        $status_str='success';
        $result['status']=$status_str;
        $result['lead_opportunity_wise_po_id']=$owp_id;
        $result['msg']='PO successfully uploaded.';
        $result['redirect_uri_str']=$redirect_uri_str;
        echo json_encode($result);
        exit(0);
    }

    public function download_po($id='')
	{	
		if($id!='')
		{	
			$file_name=get_value("file_name","lead_opportunity_wise_po","id=".$id);
			$file_path=get_value("file_path","lead_opportunity_wise_po","id=".$id);
			$this->load->helper(array('download'));				
			$pth    =   file_get_contents($file_path.$file_name);
			force_download($file_name, $pth); 
			exit;
		}
	}


	function po_mail_test()
	{
		// the message
		$msg = "First line of text\nSecond line of text";
		// use wordwrap() if lines are longer than 70 characters
		$msg = wordwrap($msg,70);
		// send email
		$r=mail("arup@maxbridgesolution.com","My subject",$msg);
		if($r)
        {
        	$msg2="sent";
        }
        else
        {
        	$msg2='not sent';
        }

        die($msg2.'- 123');

		$this->load->library('Mail_lib');
        $mail_data = array();        
        $mail_data['from_mail']     = 'arupporel9089@gmail.com';
        $mail_data['from_name']     = 'admin';
        $mail_data['to_mail']       = 'arupporel123@gmail.com';        
        $mail_data['cc_mail']       = '';               
        $mail_data['subject']       = 'test';
        $mail_data['message']       = 'testing';
        $mail_data['attach']        = array();
        $r=$this->mail_lib->send_mail($mail_data);
        if($r==true)
        {
        	die("sent");
        }
        else
        {
        	die('not sent');
        }

        die('ok');
    	$lead_opp_id =71;
    	$lead_id =28;        
        $po_date='2020/05/20'; 
		$quotation_id=get_value("id","quotation","opportunity_id=".$lead_opp_id);
		$quotation_data=$this->quotation_model->GetQuotationData($quotation_id);
		$updated_by=$this->session->userdata['admin_session_data']['user_id'];
		$company=get_company_profile();	
		$lead_info=$this->lead_model->GetLeadData($quotation_data['lead_opportunity_data']['lead_id']);		
		$assigned_user_id=$lead_info->assigned_user_id;	
		$assigned_to_user=$this->user_model->get_employee_details($assigned_user_id);
		$quotation_info=$this->Opportunity_model->get_opportunity_details($lead_opp_id);		
		$this->load->library('mail_lib');
    		
    	// ============================
		// When CC will be marked to employees at the time of lead update
		// START
		$cc_mail='';
		//$self_mail=get_value("email","user","id=".$updated_by);
		$self_mail='arupporel123@gmail.com';
        $cc_mail=get_manager_and_skip_manager_email($assigned_user_id);
        //$cc_mail=($cc_mail)?$cc_mail.','.$cc_mail_tmp:$cc_mail_tmp;
        $to_mail=$self_mail;	
		// EMAIL CONTENT

	    
		$e_data=array();		
		//$e_data['company']=$company;
		$e_data['assigned_to_user']=$assigned_to_user;
		//$e_data['customer']=$customer;
		$e_data['lead_info']=$lead_info;
		$e_data['quotation_info']=$quotation_info;
		$e_data['quotation']=$quotation_data['quotation'];
		$e_data['lead_opportunity']=$quotation_data['lead_opportunity_data'];
		$e_data['company']=$quotation_data['company_log'];
		$e_data['customer']=$quotation_data['customer_log'];
		$e_data['terms']=$quotation_data['terms_log_only_display_in_quotation'];
		$e_data['product_list']=$quotation_data['product_list'];
		$e_data['po_number']=$po_number;
		$e_data['po_date']=$po_date;
        echo $template_str = $this->load->view('admin/email_template/template/po_received_acknowledment_to_employee_view', $e_data, true); 
        
             
	    // LEAD ASSIGNED MAIL
	    $this->load->library('mail_lib');   
        $mail_data = array();
        $mail_data['from_mail']     = 'info.arupkumarporel@gmail.com';
        $mail_data['from_name']     = 'Admin';
        $mail_data['to_mail']       = $to_mail;        
        //$mail_data['cc_mail']       = $cc_mail;               
        //$mail_data['subject']       ='Hurray!! '.$assigned_to_user['name'].' has received a PO';
        $mail_data['subject']       ='PO mail test';
        $mail_data['message']       = $template_str;
        $mail_data['attach']        = array();
        $r=$this->mail_lib->send_mail($mail_data);
		// MAIL SEND
		// ===============================================
		if($r)
		{
			$msg="sent";
		}
		else
		{
			$msg="Not sent";
		}
	    die($msg);
	}


	function change_status_hotstar()
    {
        $id = $this->input->post('id');        
        $is_hotstar=get_value("is_hotstar","lead","id=".$id);
        if($is_hotstar=='N')
        	$s='Y';
        else
        	$s='N';

        $update=array(
                    'is_hotstar'=>$s,
                    'modify_date'=>date("Y-m-d H:i:s")
                    );
        $this->lead_model->UpdateLead($update,$id);
        //$result['msg']=$id.' - '.$is_hotstar.' / '.$s;
        $result['curr_star_status']=$s;
        $result["status"] = 'success';
        echo json_encode($result);
        exit(0);        
	}
	

	function test_mail()
	{
		// ============================
		// Mail Acknowledgement & 
		// account manager update
		// START


		// $lead_comments_for_mail_trail_str='980,981';
		// if($lead_comments_for_mail_trail_str)
		// {	
		// 	$list['rows']=$this->lead_model->GetLeadCommentsById($lead_comments_for_mail_trail_str);	
		//     echo $mail_trail_html = $this->load->view('admin/lead/rander_mail_trail_ajax',$list,TRUE);
		//     die();
		// }



		// TO CLIENT				
		$e_data=array();
		$assigned_user_id=28;
		$lead_id=81;
		$update_by=1;
		$assigned_to_user_data=$this->user_model->get_employee_details($assigned_user_id);
		$company=get_company_profile();	
		$lead_info=$this->lead_model->GetLeadData($lead_id);
		$customer=$this->customer_model->GetCustomerData($lead_info->customer_id);
		$e_data['company']=$company;
		$e_data['assigned_to_user']=$assigned_to_user_data;
		$e_data['customer']=$customer;
		$e_data['lead_info']=$lead_info;
		//echo $template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_view', $e_data, true);
		echo $template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_view_labhgroup', $e_data, true);
		die();
		//echo $template_str = $this->load->view('admin/email_template/template/create_lead_auto_reply_template_layout', $e_data, true);die();

		$cc_mail='';
		$self_cc_mail=get_value("email","user","id=".$update_by);
		$update_by_name=get_value("name","user","id=".$update_by);
		$cc_mail=get_manager_and_skip_manager_email($assigned_user_id);
		$cc_mail=($cc_mail)?$cc_mail.','.$self_cc_mail:$self_cc_mail;
		//$to_mail=$customer->email;	
		$to_mail='arupporel123@gmail.com';		    
		if($to_mail)
		{
			$this->load->library('mail_lib');
			$m_d = array();
			// $m_d['from_mail']     = $company['email1'];
			// $m_d['from_name']     = $company['name'];
			$m_d['from_mail']     = $self_cc_mail;
			$m_d['from_name']     = $update_by_name;
			$m_d['to_mail']       = $to_mail;
			$m_d['cc_mail']       = $cc_mail;
			$m_d['subject']       = 'Your Enquiry # '.$lead_id.' has been acknowledged';
			$m_d['message']       = $template_str;
			$m_d['attach']        = array();//print_r($m_d); die();
			$mail_return = $this->mail_lib->send_mail($m_d);
		}
		// END
		// =============================
	}
	
	public function rander_update_lead_view_ajax()
	{ 
		$list=array();
		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		$list['user_id']=$user_id;
		$leadid=$this->input->post('leadid');
		$list['cus_data']=$this->lead_model->GetLeadData($leadid);
        $list['communication_list']=$this->lead_model->GetCommunicationList();
        $list['user_list']=$this->user_model->GetUserListAll('');
		$list['lead_id']=$leadid;		
		$list['lead_data']=$this->lead_model->GetLeadData($leadid);
		$list['regret_reason_list']=get_regret_reason();
    	$html = $this->load->view('admin/lead/popup_update_lead_view_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0); 
		
	}

	public function fb_ig_csv_upload_and_import_ajax()
	{		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$status = 'fail';
			$status='';
			$error_msg='';
			$success_msg='';
			$file_name='';	
			// If file uploaded
            if(is_uploaded_file($_FILES['fb_ig_csv_file']['tmp_name']))
            { 
            				
				$session_data = $this->session->userdata('admin_session_data');
				$user_id = $session_data['user_id'];

            	$file_name=time();
				$config = array(
					'upload_path' => "assets/",
					'allowed_types' => "csv",
					'overwrite' => TRUE,
					'encrypt_name' => FALSE,
					//'file_name' => $file_name.'.csv',
					'max_size' => "2048000" 
					);
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
			   
				if (!$this->upload->do_upload('fb_ig_csv_file'))
				{
					$error_msg=strip_tags($this->upload->display_errors());
					$status = 'fail';	
				}
				else
				{
					$data = array('upload_data' => $this->upload->data());
					$file_name = $data['upload_data']['file_name'];

					$csvData = file_get_contents("assets/".$file_name);
					$lines = explode(PHP_EOL, $csvData);
					$array = array();
					foreach ($lines as $line) 
					{
					    $array[] = str_getcsv($line);
					}
					@unlink("assets/".$file_name);
					$error_flag=0;
					if(count($array))
					{
						//$this->lead_model->truncate_fb_ig();
						$this->lead_model->delete_fb_ig_tmp_by_user($user_id);
						$i=0;
						foreach($array AS $row)
						{
							if($i==0)
							{
								if($row[0]=='id' && 
									$row[1]=='created_time' && 
									$row[2]=='ad_id' && 
									$row[3]=='ad_name' && 
									$row[4]=='adset_id' && 
									$row[5]=='adset_name' && 
									$row[6]=='campaign_id' && 
									$row[7]=='campaign_name' && 
									$row[8]=='form_id' && 
									$row[9]=='form_name' && 
									$row[10]=='is_organic' && 
									$row[11]=='platform' && 
									$row[12]=='your_mob._no.' && 
									$row[13]=='full_name' && 
									$row[14]=='email' && 
									$row[15]=='phone_number' && 
									$row[16]=='city' && 
									$row[17]=='assigned_user_employee_id')
								{

								}
								else
								{
									$first_row_error_log='';
									if($row[0]!='id')
									{
										$first_row_error_log .='A1, ';
									}
									if($row[1]!='created_time')
									{
										$first_row_error_log .='B1, ';
									}
									if($row[2]!='ad_id')
									{
										$first_row_error_log .='C1, ';
									}
									if($row[3]!='ad_name')
									{
										$first_row_error_log .='D1, ';
									}
									if($row[4]!='adset_id')
									{
										$first_row_error_log .='E1, ';
									}
									if($row[5]!='adset_name')
									{
										$first_row_error_log .='F1, ';
									}
									if($row[6]!='campaign_id')
									{
										$first_row_error_log .='G1, ';
									}
									if($row[7]!='campaign_name')
									{
										$first_row_error_log .='H1, ';
									}
									if($row[8]!='form_id')
									{
										$first_row_error_log .='I1, ';
									}
									if($row[9]!='form_name')
									{
										$first_row_error_log .='J1, ';
									}
									if($row[10]!='is_organic')
									{
										$first_row_error_log .='K1, ';
									}
									if($row[11]!='platform')
									{
										$first_row_error_log .='L1, ';
									}
									if($row[12]!='your_mob._no.')
									{
										$first_row_error_log .='M1, ';
									}
									if($row[13]!='full_name')
									{
										$first_row_error_log .='N1, ';
									}
									if($row[14]!='email')
									{
										$first_row_error_log .='O1, ';
									}
									if($row[15]!='phone_number')
									{
										$first_row_error_log .='P1, ';
									}
									if($row[16]!='city')
									{
										$first_row_error_log .='Q1, ';
									}
									if($row[17]!='assigned_user_employee_id')
									{
										$first_row_error_log .='R1, ';
									}
									$first_row_error_log=rtrim($first_row_error_log,", ");
									$error_in=($first_row_error_log)?' Error in :'.$first_row_error_log:'';
									$error_msg='Error on csv heading, please maintain the order of the heading and also give the exact name as defined in sample of csv.'.$error_in;
									$status = 'Error_heading';
									$error_flag++;
									break;
								}
							}
							
							if($i>0)
							{
								if($row[0]=='' && 
									$row[1]=='' && 
									$row[2]=='' && 
									$row[3]=='' && 
									$row[4]=='' && 
									$row[5]=='' && 
									$row[6]=='' && 
									$row[7]=='' && 
									$row[8]=='' && 
									$row[9]=='' && 
									$row[10]=='' && 
									$row[11]=='' && 
									$row[12]=='' && 
									$row[13]=='' && 
									$row[14]=='' && 
									$row[15]=='' && 
									$row[16]=='' && 
									$row[17]=='')
								{

								}
								else
								{
									$assigned_user_employee_id=($row[17]>0)?$row[17]:'1';
									$data=array(
											'user_id'=>$user_id,
											'uploaded_datetime'=>date("Y-m-d H:i:s"),
											'id'=>$row[0],
											'created_time'=>$row[1],
											'ad_id'=>$row[2],
											'ad_name'=>$row[3],
											'adset_id'=>$row[4],
											'adset_name'=>$row[5],
											'campaign_id'=>$row[6],
											'campaign_name'=>$row[7],
											'form_id'=>$row[8],
											'form_name'=>$row[9],
											'is_organic'=>$row[10],
											'platform'=>$row[11],
											'your_mob_no'=>$row[12],
											'full_name'=>$row[13],
											'email'=>$row[14],
											'phone_number'=>$row[15],
											'city'=>$row[16],
											'assigned_user_employee_id'=>$assigned_user_employee_id
											);
									$this->lead_model->add_fb_ig($data);
								}								
							}
							$i++;
						}
					}

					if($error_flag==0)
					{
						$fb_ig_data_chk=$this->lead_model->fb_ig_data_chk($user_id);
					
						if(count($fb_ig_data_chk)==0)
						{						
							$this->add_fb_ig_data($user_id);						
							// data insert to lead and company table
							$status = 'success';
						}
						else
						{
							$error_msg='Error on csv data, please see the error log.';
							$status = 'Error_log';
						}
					}
																
				}				
            }
            else
            {
            	$error_msg='Error on file upload, please try again.';
            	$status = 'fail';	
            }			
				
			$success_msg=$file_name;
			$result["status"] = $status;
			$result["error_msg"] = $error_msg;
			$result["success_msg"] = $success_msg;
			$result['file_name']=$file_name;		
			echo json_encode($result);
			exit(0);
		}		
	}

	public function download_fb_ig_sample()
	{
		$file_name='IG_FB_SAMPLE.csv';
		$file_path='assets/images/';
		if ( ! file_exists($file_path.$file_name))
	    {
	        echo 'file missing';
	    }
	    else
	    {
	        header('HTTP/1.1 200 OK');
	        header('Cache-Control: no-cache, must-revalidate');
	        header("Pragma: no-cache");
	        header("Expires: 0");
	        header("Content-type: text/csv");
	        header("Content-Disposition: attachment; filename=$file_name");
	        readfile($file_path . $file_name);
	        exit;
	    }
	}

	public function add_fb_ig_data($user_id)
	{
		$rows=$this->lead_model->get_fb_ig_tmp_list($user_id);
		if(count($rows))
		{
			foreach($rows AS $row)
			{
				$cust_arr=array(
								'email'=>$row['email'],
								'mobile'=>$row['your_mob_no']
								);
				$get_customer_decision=$this->lead_model->get_decision($cust_arr);
				if($get_customer_decision['status']==TRUE)
				{
					$city_info=$this->lead_model->get_city_by_name($row['city']);
					$city_id=$city_info['id'];
					$state_id=$city_info['state_id'];
					$country_id=get_value("country_id","states","id=".$state_id);
					$country_code=get_value("phonecode","countries","id=".$country_id);
					$alternate_mobile='';
					// if($row['phone_number'])
					// {
					// 	$tmp_mobile=trim($row['phone_number']);
					// 	$alternate_mobile = substr($tmp_mobile, -10);
					// }
					
					$assigned_user_id=$row['assigned_user_employee_id'];
					$com_contact_person=trim($row['full_name']);
					$com_designation='Manager';
					$com_email=trim($row['email']);
					$com_alternate_email='';
					$com_mobile_country_code=$country_code;
					$com_mobile=trim($row['your_mob_no']);
					$com_alt_mobile_country_code=$country_code;
					$com_alternate_mobile=$alternate_mobile;
					$com_landline_country_code=$country_code;
					$com_landline_std_code='';
					$landline_number='';
					$com_website='';
					$com_company_name=trim($row['full_name']);
					$com_address='';
					$com_city_id=$city_id;
					$com_state_id=$state_id;
					$com_country_id=$country_id;
					$com_zip='';
					$com_short_description='';
					$get_source_name=(trim(strtolower($row['platform']))=='fb')?'Facebook':'Instagram';
					$com_source_id=$this->lead_model->get_source_id($get_source_name);
					$fb_ig_id=$row['id'];
					// ===================
					// CUSTOMER DETAILS
					$com_contact_person_tmp='';							
					if($get_customer_decision['msg']=='no_customer_exist')
					{

						$company_post_data=array(
							'assigned_user_id'=>$assigned_user_id,
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
							'create_date'=>date("Y-m-d H:i:s"),
							'short_description'=>$com_short_description,
							'source_id'=>$com_source_id,
							'modify_date'=>date('Y-m-d'),
							'status'=>'1'
						);
						
						$com_company_id=$this->customer_model->CreateCustomer($company_post_data);
						$com_contact_person_tmp=$com_contact_person;
						
					}
					else if($get_customer_decision['msg']=='one_customer_exist')
					{
						$com_company_id=$get_customer_decision['customer_id'];
						$get_existing_com=$this->customer_model->get_company_detail($com_company_id);
						$com_source_id=$get_existing_com['source_id'];
						$assigned_user_id=$get_existing_com['assigned_user_id'];
						$company_post_data=array(
							'contact_person'=>($get_existing_com['contact_person'])?$get_existing_com['contact_person']:$com_contact_person,
							'designation'=>($get_existing_com['designation'])?$get_existing_com['designation']:$com_designation,
							'alt_email'=>($get_existing_com['alt_email'])?$get_existing_com['alt_email']:$com_alternate_email,
							'alt_mobile_country_code'=>($get_existing_com['alt_mobile_country_code'])?$get_existing_com['alt_mobile_country_code']:$com_alt_mobile_country_code,
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

						$this->customer_model->UpdateCustomer($company_post_data,$com_company_id);

						$com_contact_person_tmp=($get_existing_com['contact_person'])?$get_existing_com['contact_person']:$com_contact_person;								
					}


					// LEAD
					$lead_title=$com_contact_person_tmp.' has asked about your product';
					$source=(trim(strtolower($row['platform']))=='fb')?'Facebook Ad':'Instagram Ad';	
					$lead_requirement ='';				
					$lead_requirement .='Hi,<br>I am interested in your product. Pl. contact me.<BR><BR>';
					$lead_requirement .='<B>Source:</B> '.$source.'<BR>';
					$lead_requirement .='<B>Ad Name:</B> '.trim($row['ad_name']).'<BR>';
					$lead_requirement .='<B>Campaign Name:</B> '.trim($row['campaign_name']).'<BR>';
					$lead_requirement .='<B><u>From:</u></B><BR>';
					$lead_requirement .='<B>Name:</B> '.trim($row['full_name']).'';
					$lead_requirement .='<B>Email:</B> '.trim($row['email']).'';
					$phone1=trim($row['your_mob_no']);
					$phone2=trim($row['phone_number']);
					if($phone1){
						$lead_requirement .='<B>Phone1:</B> '.$phone1.'';
					}
					if($phone2){
						$lead_requirement .='<B>Phone2:</B> '.$phone2.'';
					}					
					$lead_requirement .='<B>Date & Time:</B> '.trim($row['created_time']).'';
					$lead_enq_date=substr($row['created_time'],0,10);
					//$lead_enq_date=date_display_format_to_db_format($row['created_time']);

					$lead_chk=$this->lead_model->lead_chk_by_fbigId($fb_ig_id);
					if($lead_chk==TRUE)
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
									// 'current_status_id'=>'1',
									// 'current_status'=>'WARM',
									'im_query_id'=>'',
									'im_setting_id'=>'',
									'fb_ig_id'=>$fb_ig_id
								);
						$lead_id=$this->lead_model->CreateLead($lead_post_data);
						
						if($lead_id)
						{

							// Insert Stage Log
							$stage_post_data=array(
									'lead_id'=>$lead_id,
									'stage_id'=>'1',
									'stage'=>'PENDING',
									'stage_wise_msg'=>'',
									'create_datetime'=>date("Y-m-d H:i:s")
								);
							$this->lead_model->CreateLeadStageLog($stage_post_data);

							// Insert Status Log
							$status_post_data=array(
									'lead_id'=>$lead_id,
									'status_id'=>'2',
									'status'=>'WARM',
									'create_datetime'=>date("Y-m-d H:i:s")
								);
							// $this->lead_model->CreateLeadStatusLog($status_post_data);


							$attach_filename='';
							//$assigned_by_user_id=1;
							// -------------------------------------------------
							// ASSIGN LEAD LOG TABLE
							$post_data=array(
										'lead_id'=>$lead_id,
										'assigned_to_user_id'=>$assigned_user_id,
										'assigned_by_user_id'=>$user_id,
										'is_accepted'=>'Y',
										'assigned_datetime'=>date("Y-m-d H:i:s")
										);			
							$this->lead_model->create_lead_assigned_user_log($post_data);

							$update_by=$user_id;				
							$date=date("Y-m-d H:i:s");				
							$ip_addr=$_SERVER['REMOTE_ADDR'];				
							$message="A new lead has been created as &quot;".$lead_title."&quot;";
							$comment_title=NEW_LEAD_CREATE_MANUAL;
							$historydata=array(
												'title'=>$comment_title,
												'lead_id'=>$lead_id,
												'comment'=>addslashes($message),
												'attach_file'=>$attach_filename,
												'create_date'=>$date,
												'user_id'=>$update_by,
												'ip_address'=>$ip_addr
											);
							$this->history_model->CreateHistory($historydata);

							// ============================
							// Mail Acknowledgement & 
							// account manager update
							// START

							// TO CLIENT
							/*
							$e_data=array();							
							$assigned_to_user_data=$this->user_model->get_employee_details($assigned_user_id);
							$company=get_company_profile();	
							$lead_info=$this->lead_model->GetLeadData($lead_id);
							$customer=$this->customer_model->GetCustomerData($lead_info->customer_id);
							$e_data['company']=$company;
							$e_data['assigned_to_user']=$assigned_to_user_data;
							$e_data['customer']=$customer;
							$e_data['lead_info']=$lead_info;
							$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_view', $e_data, true);

							$cc_mail='';
							$self_cc_mail=get_value("email","user","id=".$update_by);
							$update_by_name=get_value("name","user","id=".$update_by);							
							$cc_mail=$self_cc_mail;
							$to_mail=$customer->email;	
							if($to_mail)
							{
								$com_company_name_tmp=$company['name'];
								if($com_contact_person_tmp)
								{
									$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
								}
								else
								{
									$mail_subject='Your Enquiry # '.$lead_id.' has been acknowledged';
								}
								
								$this->load->library('mail_lib');
								$m_d = array();								
								$m_d['from_mail']     = $self_cc_mail;
								$m_d['from_name']     = $update_by_name;
								$m_d['to_mail']       = $to_mail;
								$m_d['cc_mail']       = $cc_mail;
								$m_d['subject']       = $mail_subject;
								$m_d['message']       = $template_str;
								$m_d['attach']        = array();
								$mail_return = $this->mail_lib->send_mail($m_d);
								if($mail_return)
								{
									
								}
							}
							*/
							// END
							// =============================
							// update fb_ig_tmp table
							$data_tmp=array('lead_id'=>$lead_id,'is_added_as_lead'=>'Y');
							$this->lead_model->update_fb_ig_tmp($data_tmp,$row['tmp_id']);
						}
					}
				}
			}
			//$this->lead_model->delete_fb_ig_tmp_by_user($user_id);
		}		
	}

	public function get_fb_ig_error_log_ajax()
	{
		$uploaded_csv_file_name=$this->input->post('uploaded_csv_file_name');
		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		$list=array(); 
		$list['error_log']=$this->lead_model->fb_ig_data_chk($user_id);
		$list['rows']=$this->lead_model->get_fb_ig_tmp_list($user_id);
		$total_error=0;
		$total_rows=count($list['rows']);
		foreach($list['rows'] AS $row)
		{
			if(count($list['error_log'][$row['tmp_id']]))
			{				
				$total_error++;
			}
		}
		$list['total_error']=$total_error;
		$list['total_rows']=$total_rows;
		$list['uploaded_csv_file_name']=$uploaded_csv_file_name;
    	$html = $this->load->view('admin/lead/rander_fb_ig_error_log_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0); 
	}

	public function download_lead_upload_sample()
	{
		$file_name='LEAD_UPLOAD_SAMPLE.csv';
		$file_path='assets/images/';
		if ( ! file_exists($file_path.$file_name))
	    {
	        echo 'file missing';
	    }
	    else
	    {
	        header('HTTP/1.1 200 OK');
	        header('Cache-Control: no-cache, must-revalidate');
	        header("Pragma: no-cache");
	        header("Expires: 0");
	        header("Content-type: text/csv");
	        header("Content-Disposition: attachment; filename=$file_name");
	        readfile($file_path . $file_name);
	        exit;
	    }
	}

	public function csv_upload_and_import_ajax()
	{		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$status = 'fail';
			$status='';
			$error_msg='';
			$success_msg='';
			$file_name='';	
			// If file uploaded
            if(is_uploaded_file($_FILES['csv_file']['tmp_name']))
            { 
            			
				$session_data = $this->session->userdata('admin_session_data');
				$user_id = $session_data['user_id'];

            	$file_name=time();
				$config = array(
					'upload_path' => "assets/",
					'allowed_types' => "csv|CSV",
					'overwrite' => TRUE,
					'encrypt_name' => FALSE,
					//'file_name' => $file_name.'.csv',
					//'max_size' => "2048000" 
					);
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
			    
				if (!$this->upload->do_upload('csv_file'))
				{	
					$error_msg=strip_tags($this->upload->display_errors());
					$status = 'fail';	
				}
				else
				{	
					$data = array('upload_data' => $this->upload->data());
					$file_name = $data['upload_data']['file_name'];

					$csvData = file_get_contents("assets/".$file_name);
					$lines = explode(PHP_EOL, $csvData);
					$array = array();
					foreach ($lines as $line) 
					{
					    $array[] = str_getcsv($line);
					}
					@unlink("assets/".$file_name);
					$error_flag=0;

					if(count($array))
					{	
						//$this->lead_model->truncate_fb_ig();
						$this->lead_model->delete_lead_csv_upload_tmp_by_user($user_id);

						$i=0;
						foreach($array AS $row)
						{
							$f1=trim($row[0]);
							$f2=trim($row[1]);
							$f3=trim($row[2]);
							$f4=trim($row[3]);
							$f5=trim($row[4]);
							$f6=trim($row[5]);
							$f7=trim($row[6]);
							$f8=trim($row[7]);
							$f9=trim($row[8]);
							$f10=trim($row[9]);
							$f11=trim($row[10]);
							$f12=trim($row[11]);
							$f13=trim($row[12]);
							$f14=trim($row[13]);
							$f15=trim($row[14]);
							$f16=trim($row[15]);
							$f17=trim($row[16]);
							$f18=trim($row[17]);
							$f19=trim($row[18]);
							$f20=trim($row[19]);
							$f21=trim($row[20]);
							
							if($i==0)
							{
								if(strtolower($f1)=='lead_title' && 
									strtolower($f2)=='lead_describe_requirement' && 
									strtolower($f3)=='lead_enquiry_date' && 
									strtolower($f4)=='lead_next_followup_date' && 
									strtolower($f5)=='lead_source' && 
									strtolower($f6)=='assigned_user_employee_id' && 
									strtolower($f7)=='company_name' && 
									strtolower($f8)=='company_contact_person' && 
									strtolower($f9)=='company_contact_person_designation' && 
									strtolower($f10)=='company_email' && 
									strtolower($f11)=='company_alternate_email' && 
									strtolower($f12)=='company_mobile' && 
									strtolower($f13)=='company_alternate_mobile' && 
									strtolower($f14)=='company_landline_number' && 
									strtolower($f15)=='company_address' && 
									strtolower($f16)=='company_city' && 
									strtolower($f17)=='company_zip' && 
									strtolower($f18)=='company_website' && 
									strtolower($f19)=='company_short_description' && 
									strtolower($f20)=='reference_name' && 
									strtolower($f21)=='company_country')
								{

								}
								else
								{
									$first_row_error_log='';
									if(strtolower($f1)!='lead_title')
									{
										$first_row_error_log .='A1, ';
									}
									if(strtolower($f2)!='lead_describe_requirement')
									{
										$first_row_error_log .='B1, ';
									}
									if(strtolower($f3)!='lead_enquiry_date')
									{
										$first_row_error_log .='C1, ';
									}
									if(strtolower($f4)!='lead_next_followup_date')
									{
										$first_row_error_log .='D1, ';
									}
									if(strtolower($f5)!='lead_source')
									{
										$first_row_error_log .='E1, ';
									}
									if(strtolower($f6)!='assigned_user_employee_id')
									{
										$first_row_error_log .='F1, ';
									}
									if(strtolower($f7)!='company_name')
									{
										$first_row_error_log .='G1, ';
									}
									if(strtolower($f8)!='company_contact_person')
									{
										$first_row_error_log .='H1, ';
									}
									if(strtolower($f9)!='company_contact_person_designation')
									{
										$first_row_error_log .='I1, ';
									}
									if(strtolower($f10)!='company_email')
									{
										$first_row_error_log .='J1, ';
									}
									if(strtolower($f11)!='company_alternate_email')
									{
										$first_row_error_log .='K1, ';
									}
									if(strtolower($f12)!='company_mobile')
									{
										$first_row_error_log .='L1, ';
									}
									if(strtolower($f13)!='company_alternate_mobile')
									{
										$first_row_error_log .='M1, ';
									}
									if(strtolower($f14)!='company_landline_number')
									{
										$first_row_error_log .='N1, ';
									}
									if(strtolower($f15)!='company_address')
									{
										$first_row_error_log .='O1, ';
									}
									if(strtolower($f16)!='company_city')
									{
										$first_row_error_log .='P1, ';
									}
									if(strtolower($f17)!='company_zip')
									{
										$first_row_error_log .='Q1, ';
									}
									if(strtolower($f18)!='company_website')
									{
										$first_row_error_log .='R1, ';
									}
									if(strtolower($f19)!='company_short_description')
									{
										$first_row_error_log .='S1, ';
									}
									if(strtolower($f20)!='reference_name')
									{
										$first_row_error_log .='T1, ';
									}
									if(strtolower($f21)!='company_country')
									{
										$first_row_error_log .='U1, ';
									}
									$first_row_error_log=rtrim($first_row_error_log,", ");
									$error_in=($first_row_error_log)?' Error in :'.$first_row_error_log:'';
									$error_msg='Error on csv heading, please maintain the order of the heading and also give the exact name as defined in sample of csv.'.$error_in;
									$status = 'Error_heading';
									$error_flag++;
									break;
								}
							}
							
							if($i>0)
							{
								if($f1=='' && 
									$f2=='' && 
									$f3=='' && 
									$f4=='' && 
									$f5=='' && 
									$f6=='' && 
									$f7=='' && 
									$f8=='' && 
									$f9=='' && 
									$f10=='' && 
									$f11=='' && 
									$f12=='' && 
									$f13=='' && 
									$f14=='' && 
									$f15=='' && 
									$f16=='' && 
									$f17=='' && 
									$f18=='' && 
									$f19=='' && 
									$f20=='' && 
									$f21=='')
								{

								}
								else
								{

									$assigned_user_employee_id=($f6>0)?$f6:'0';
									$lead_enquiry_date_tmp='';
									$lead_next_followup_date_tmp='';
									if($f3!='')
									{
										$lead_enquiry_date_tmp=date_csv_format_to_db_format($f3);
									}
									if($f4!='')
									{
										$lead_next_followup_date_tmp=date_csv_format_to_db_format($f4);
									}
									
									
									$data=array(	
											'lead_title'=>$f1,
											'lead_describe_requirement'=>$f2,
											'lead_enquiry_date'=>$lead_enquiry_date_tmp,
											'lead_next_followup_date'=>$lead_next_followup_date_tmp,
											'lead_source'=>$f5,
											'assigned_user_employee_id'=>$assigned_user_employee_id,
											'company_name'=>$f7,
											'company_contact_person'=>$f8,
											'company_contact_person_designation'=>$f9,
											'company_email'=>$f10,
											'company_alternate_email'=>$f11,
											'company_mobile'=>$f12,
											'company_alternate_mobile'=>$f13,
											'company_landline_number'=>$f14,
											'company_address'=>$f15,
											'company_city'=>$f16,
											'company_country'=>$f21,
											'company_zip'=>$f17,
											'company_website'=>$f18,
											'company_short_description'=>$f19,
											'reference_name'=>$f20,
											'user_id'=>$user_id,
											'uploaded_datetime'=>date("Y-m-d H:i:s")
											);
									// print_r($data); die();
									$this->lead_model->add_csv_upload_tmp($data);
								}								
							}
							$i++;
						}
						//die("ok");
					}

					if($error_flag==0)
					{
						$csv_upload_data_chk=$this->lead_model->csv_upload_tmp_data_chk($user_id);					
						if(count($csv_upload_data_chk)==0)
						{						
							$this->add_upload_csv_data($user_id);
							// data insert to lead and company table
							$status = 'success';
						}
						else
						{
							$error_msg='Error on csv data, please see the error log.';
							$status = 'Error_log';
						}
					}
																
				}				
            }
            else
            {
            	$error_msg='Error on file upload, please try again.';
            	$status = 'fail';	
            }			
				
			$success_msg=$file_name;
			$result["status"] = $status;
			$result["error_msg"] = $error_msg;
			$result["success_msg"] = $success_msg;
			$result['file_name']=$file_name;		
			echo json_encode($result);
			exit(0);
		}		
	}

	public function add_upload_csv_data($user_id)
	{
		$rows=$this->lead_model->get_csv_upload_tmp_list($user_id);
		if(count($rows))
		{
			foreach($rows AS $row)
			{
				$cust_arr=array(
								'email'=>$row['company_email'],
								'mobile'=>$row['company_mobile']
								);
				$get_customer_decision=$this->lead_model->get_decision($cust_arr);
				if($get_customer_decision['status']==TRUE)
				{
					
					$city_id=0;
					$state_id=0;
					$country_id=0;
					$country_code=0;
					if($row['company_city'])
					{
						$city_info=$this->lead_model->get_city_by_name($row['company_city']);
						if(count($city_info))
						{
							$city_id=$city_info['id'];
							$state_id=$city_info['state_id'];
							$country_id=get_value("country_id","states","id=".$state_id);
							$country_code=get_value("phonecode","countries","id=".$country_id);
						}
						else
						{
							if($row['company_country'])
							{
								$country_info=$this->lead_model->get_country_by_name($row['company_country']);
								if(count($country_info))
								{
									$country_id=$country_info['id'];
									$country_code=$country_info['phonecode'];
								}							
							}
						}												
					}
					else
					{						
						if($row['company_country'])
						{
							$country_info=$this->lead_model->get_country_by_name($row['company_country']);
							if(count($country_info))
							{
								$country_id=$country_info['id'];
								$country_code=$country_info['phonecode'];
							}							
						}						
					}
					
					//$alternate_mobile='';
					// if($row['phone_number'])
					// {
					// 	$tmp_mobile=trim($row['phone_number']);
					// 	$alternate_mobile = substr($tmp_mobile, -10);
					// }
					
					$assigned_user_id=$row['assigned_user_employee_id'];
					$com_contact_person=$row['company_contact_person'];
					$com_designation=($row['company_contact_person_designation'])?$row['company_contact_person_designation']:'Manager';
					$com_email=$row['company_email'];
					$com_alternate_email=$row['company_alternate_email'];
					$com_mobile_country_code=$country_code;
					$com_mobile=$row['company_mobile'];
					$com_alt_mobile_country_code=$country_code;
					$com_alternate_mobile=$row['company_alternate_mobile'];
					$com_landline_country_code=$country_code;
					$com_landline_std_code='';
					$landline_number=$row['company_landline_number'];
					$com_website=$row['company_website'];
					$com_company_name=$row['company_name'];
					$com_address=$row['company_address'];
					$com_city_id=$city_id;
					$com_state_id=$state_id;
					$com_country_id=$country_id;
					$com_zip=$row['company_zip'];
					$com_short_description=$row['company_short_description'];
					$com_reference_name=$row['reference_name'];
					$get_source_name=strtolower($row['lead_source']);
					$lead_source_id=$this->lead_model->get_source_id($get_source_name);	
					$com_source_id=$lead_source_id;					
					// ===================
					// CUSTOMER DETAILS
					$com_contact_person_tmp='';							
					if($get_customer_decision['msg']=='no_customer_exist')
					{

						$company_post_data=array(
							'assigned_user_id'=>$assigned_user_id,
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
							'create_date'=>date("Y-m-d H:i:s"),
							'short_description'=>$com_short_description,
							'reference_name'=>$com_reference_name,
							'source_id'=>$com_source_id,
							'modify_date'=>date('Y-m-d'),
							'status'=>'1'
						);
						
						$com_company_id=$this->customer_model->CreateCustomer($company_post_data);
						$com_contact_person_tmp=$com_contact_person;
						
					}
					else if($get_customer_decision['msg']=='one_customer_exist')
					{
						$com_company_id=$get_customer_decision['customer_id'];
						$get_existing_com=$this->customer_model->get_company_detail($com_company_id);
						$com_source_id=$get_existing_com['source_id'];
						// $assigned_user_id=$get_existing_com['assigned_user_id'];

						$tmp_email=($get_existing_com['email'])?$get_existing_com['email']:$com_email;
						$tmp_com_alternate_email=$get_existing_com['alt_email'];
						if($get_existing_com['alt_email']!=''){
							$tmp_com_alternate_email=$get_existing_com['alt_email'];
						}
						else{
							if($get_existing_com['email']!='' && ($get_existing_com['email']!=$com_email)){
								$tmp_com_alternate_email=$com_email;
							}
						}

						$tmp_mobile=($get_existing_com['mobile'])?$get_existing_com['mobile']:$com_mobile;
						$tmp_com_alternate_mobile=$get_existing_com['alt_mobile'];
						if($get_existing_com['alt_mobile']!=''){
							$tmp_com_alternate_mobile=$get_existing_com['alt_mobile'];
						}
						else{
							if($get_existing_com['mobile']!='' && ($get_existing_com['mobile']!=$com_mobile)){
								$tmp_com_alternate_mobile=$com_mobile;
							}
						}

						$company_post_data=array(
							'contact_person'=>($get_existing_com['contact_person'])?$get_existing_com['contact_person']:$com_contact_person,
							'designation'=>($get_existing_com['designation'])?$get_existing_com['designation']:$com_designation,
							'email'=>$tmp_email,
							'alt_email'=>$tmp_com_alternate_email,
							// 'alt_email'=>($get_existing_com['alt_email'])?$get_existing_com['alt_email']:$com_alternate_email,
							'alt_mobile_country_code'=>($get_existing_com['alt_mobile_country_code'])?$get_existing_com['alt_mobile_country_code']:$com_alt_mobile_country_code,							
							'mobile'=>$tmp_mobile,
							'alt_mobile'=>$tmp_com_alternate_mobile,
							// 'alt_mobile'=>($get_existing_com['alt_mobile'])?$get_existing_com['alt_mobile']:$com_alternate_mobile,
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
							'short_description'=>($com_short_description)?$com_short_description:$get_existing_com['short_description'],
							'reference_name'=>($get_existing_com['reference_name'])?$get_existing_com['reference_name']:$com_reference_name,
							'source_id'=>($get_existing_com['source_id']>0)?$get_existing_com['source_id']:$com_source_id,
							'modify_date'=>date('Y-m-d'),
							'status'=>'1'
						);

						$this->customer_model->UpdateCustomer($company_post_data,$com_company_id);

						$com_contact_person_tmp=($get_existing_com['contact_person'])?$get_existing_com['contact_person']:$com_contact_person;								
					}


					// LEAD
					$lead_title=$row['lead_title'];						
					$lead_requirement =$row['lead_describe_requirement'];					
					// $lead_requirement .='Hi,<br>I am interested in your product. Pl. contact me.<BR><BR>';
					// $lead_requirement .='<B>Source:</B> '.$get_source_name.'<BR>';
					// $lead_requirement .='<B>Ad Name:</B> '.trim($row['ad_name']).'<BR>';
					// $lead_requirement .='<B>Campaign Name:</B> '.trim($row['campaign_name']).'<BR>';
					// $lead_requirement .='<B>Date & Time:</B> '.trim($row['created_time']).'';

					$lead_enq_date=($row['lead_enquiry_date']!='0000-00-00')?$row['lead_enquiry_date']:date("Y-m-d");
					//$lead_enq_date=date_display_format_to_db_format($row['created_time']);
					$followup_date=($row['lead_next_followup_date']!='0000-00-00')?$row['lead_next_followup_date']:date("Y-m-d");

					//$lead_chk=$this->lead_model->lead_chk_by_fbigId($fb_ig_id);
					//if($lead_chk==TRUE)
					//{
						// $get_immidiate_manager_id=0;
						// if($assigned_user_id>0){
						// 	$get_immidiate_manager_id=$this->user_model->get_manager_id($assigned_user_id);
						// }
						$lead_post_data=array(
								'title'=>$lead_title,
								'customer_id'=>$com_company_id,
								'source_id'=>$lead_source_id,
								'assigned_user_id'=>$assigned_user_id,
								'buying_requirement'=>$lead_requirement,
								'enquiry_date'=>$lead_enq_date,
								'followup_date'=>$followup_date,
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
						$lead_id=$this->lead_model->CreateLead($lead_post_data);
						
						if($lead_id)
						{
							// Insert Stage Log
							$stage_post_data=array(
									'lead_id'=>$lead_id,
									'stage_id'=>'1',
									'stage'=>'PENDING',
									'stage_wise_msg'=>'',
									'create_datetime'=>date("Y-m-d H:i:s")
								);
							$this->lead_model->CreateLeadStageLog($stage_post_data);

							// Insert Status Log
							$status_post_data=array(
									'lead_id'=>$lead_id,
									'status_id'=>'2',
									'status'=>'WARM',
									'create_datetime'=>date("Y-m-d H:i:s")
								);
							$this->lead_model->CreateLeadStatusLog($status_post_data);


							$attach_filename='';
							//$assigned_by_user_id=1;
							// -------------------------------------------------
							// ASSIGN LEAD LOG TABLE
							$post_data=array(
										'lead_id'=>$lead_id,
										'assigned_to_user_id'=>$assigned_user_id,
										'assigned_by_user_id'=>$user_id,
										'is_accepted'=>'Y',
										'assigned_datetime'=>date("Y-m-d H:i:s")
										);			
							$this->lead_model->create_lead_assigned_user_log($post_data);

							$update_by=$user_id;				
							$date=date("Y-m-d H:i:s");				
							$ip_addr=$_SERVER['REMOTE_ADDR'];				
							$message="A new lead has been created as &quot;".$lead_title."&quot;";
							$comment_title=NEW_LEAD_CREATE_MANUAL;
							$historydata=array(
												'title'=>$comment_title,
												'lead_id'=>$lead_id,
												'comment'=>addslashes($message),
												'attach_file'=>$attach_filename,
												'create_date'=>$date,
												'user_id'=>$update_by,
												'ip_address'=>$ip_addr
											);
							$this->history_model->CreateHistory($historydata);

							// ============================
							// Mail Acknowledgement & 
							// account manager update
							// START

							// TO CLIENT
							/*
							$e_data=array();							
							$assigned_to_user_data=$this->user_model->get_employee_details($assigned_user_id);
							$company=get_company_profile();	
							$lead_info=$this->lead_model->GetLeadData($lead_id);
							$customer=$this->customer_model->GetCustomerData($lead_info->customer_id);
							$e_data['company']=$company;
							$e_data['assigned_to_user']=$assigned_to_user_data;
							$e_data['customer']=$customer;
							$e_data['lead_info']=$lead_info;
							$template_str = $this->load->view('admin/email_template/template/enquiry_acknowledgement_view', $e_data, true);

							$cc_mail='';
							$self_cc_mail=get_value("email","user","id=".$update_by);
							$update_by_name=get_value("name","user","id=".$update_by);							
							$cc_mail=$self_cc_mail;
							$to_mail=$customer->email;	
							if($to_mail)
							{
								$com_company_name_tmp=$company['name'];
								if($com_contact_person_tmp)
								{
									$mail_subject=$com_contact_person_tmp.', Your Enquiry # '.$lead_id.' has been received - '.$com_company_name_tmp; 
								}
								else
								{
									$mail_subject='Your Enquiry # '.$lead_id.' has been acknowledged';
								}
								
								$this->load->library('mail_lib');
								$m_d = array();								
								$m_d['from_mail']     = $self_cc_mail;
								$m_d['from_name']     = $update_by_name;
								$m_d['to_mail']       = $to_mail;
								$m_d['cc_mail']       = $cc_mail;
								$m_d['subject']       = $mail_subject;
								$m_d['message']       = $template_str;
								$m_d['attach']        = array();
								$mail_return = $this->mail_lib->send_mail($m_d);
								if($mail_return)
								{
									
								}
							}
							*/
							// END
							// =============================
							// update fb_ig_tmp table
							$data_tmp=array('lead_id'=>$lead_id,'is_added_as_lead'=>'Y');
							$this->lead_model->update_csv_upload_tmp($data_tmp,$row['tmp_id']);
						}
					//}
				}
			}
			//$this->lead_model->delete_fb_ig_tmp_by_user($user_id);
		}		
	}

	public function get_upload_csv_error_log_ajax()
	{
		$uploaded_csv_file_name=$this->input->post('uploaded_csv_file_name');
		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		$list=array(); 
		$list['error_log']=$this->lead_model->csv_upload_tmp_data_chk($user_id);
		$list['rows']=$this->lead_model->get_csv_upload_tmp_list($user_id);
		$total_error=0;
		$total_rows=count($list['rows']);
		foreach($list['rows'] AS $row)
		{
			if(count($list['error_log'][$row['tmp_id']]))
			{				
				$total_error++;
			}
		}
		$list['total_error']=$total_error;
		$list['total_rows']=$total_rows;
		$list['uploaded_csv_file_name']=$uploaded_csv_file_name;
    	$html = $this->load->view('admin/lead/rander_upload_csv_error_log_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0); 
	}

	public function download_from_gmail_ajax_bkp_imap()
	{		
		$list=array();
		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];

		$rows=[];
		
		if (! function_exists('imap_open')) 
		{
		    echo "IMAP is not configured.";
		    exit();
		} 
		else 
		{
			$gmail_username='lmsbabadev@gmail.com';
			$gmail_password='Nopass123';
			
			$inbox = imap_open('{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX',$gmail_username,$gmail_password) or die('Cannot connect to Gmail: ' . imap_last_error());
			//$emailData = imap_search($inbox,'SUBJECT "Quote"');
			// grab emails 
			$emailData = imap_search($inbox,'ALL');

			if(! empty($emailData)) 
	        { 

	        	// put the newest emails on top 
				rsort($emailData);
	            $i=0;
	            foreach ($emailData as $key => $value) 
	            {
	            	$overview = imap_fetch_overview($inbox,$value,0);
			        // $message_date = new DateTime($overview[0]->date);
			        // $date = $message_date->format('Ymd');
			        //$message = imap_fetchbody($inbox,$value,1);
			        //$message = imap_fetchbody($inbox,$value,2);
			        //$attached_message = imap_fetchbody($inbox, $value, '1.1'); 
			        //$non_attached_message = strip_tags(quoted_printable_decode(imap_fetchbody($inbox,$value,'2'))); // Non attachment
			        $structure = imap_fetchstructure($inbox, $value);
			        // Get email address
					$header = imap_header($inbox, $value); // get first mails header
					
					if(isset($structure->parts) && is_array($structure->parts) && isset($structure->parts[1])) 
					{
			            $part = $structure->parts[1];		            
			            if($part->encoding == 3)
			            {		            	
			            	$message = imap_fetchbody($inbox, $value, '1.1');
			            }
			            else
			            {
			            	$message = quoted_printable_decode(imap_fetchbody($inbox,$value,2)); // Non attachment
			            }
		        	}

		        	//print_r($overview);
	            	
	            	$is_exist=$this->lead_model->is_exist_uid($overview[0]->uid);
	            	if($is_exist=='N')
	            	{
	            		$customer_id=0;
	            		$email_tmp=$header->from[0]->mailbox.'@'.$header->from[0]->host;
				        $cust_arr=array(
							'email'=>$email_tmp,
							'mobile'=>''
							);					        
				        $get_customer_decision=$this->lead_model->get_decision($cust_arr);
						if($get_customer_decision['status']==TRUE)
						{
							if($get_customer_decision['msg']=='one_customer_exist')
							{
								$customer_id=$get_customer_decision['customer_id'];
							}	

							$customer_exist_keyword=$get_customer_decision['msg'];
							// if($get_customer_decision['msg']=='no_customer_exist' || $get_customer_decision['msg']=='one_customer_exist'){
							// 	$customer_exist_keyword=$get_customer_decision['msg'];

							// }	
							// else
							// {
							// 	$customer_exist_keyword='multiple_customer_exist';
							// }					
						}
						else
						{
							$customer_exist_keyword='multiple_customer_exist';
						}

	            		$overview_post=array(
        							'uid'=>$overview[0]->uid,
        							'msgno'=>$overview[0]->msgno,
        							'subject'=>$overview[0]->subject,
        							'from_name'=>$overview[0]->from,
        							'to_mail'=>$overview[0]->to,
        							'date'=>$overview[0]->date,
        							'message_id'=>$overview[0]->message_id,
        							'size'=>$overview[0]->size,
        							'recent'=>$overview[0]->recent,
        							'flagged'=>$overview[0]->flagged,
        							'answered'=>$overview[0]->answered,
        							'deleted'=>$overview[0]->deleted,
        							'seen'=>$overview[0]->seen,
        							'draft'=>$overview[0]->draft,
        							'udate'=>$overview[0]->udate,
        							'message'=>$message,
        							'user_id'=>$user_id,
        							'customer_id'=>$customer_id,
        							'customer_exist_keyword'=>$customer_exist_keyword,
        							'created_at'=>date("Y-m-d H:i:s"),
        							'updated_at'=>date("Y-m-d H:i:s"),
        							'msg'=>$get_customer_decision['msg'].'/'.$header->from[0]->mailbox.'@'.$header->from[0]->host
        							);
	            		$overview_auto_id=$this->lead_model->gmail_overview_add($overview_post);
	            		if($overview_auto_id)
	            		{

	            			$header_post=array(
	            							'overview_id'=>$overview_auto_id,
	            							'date'=>$header->date,
	            							'subject'=>$header->subject,
	            							'toaddress'=>$header->toaddress,
	            							'to_mailbox'=>$header->to[0]->mailbox,
	            							'to_host'=>$header->to[0]->host,
	            							'fromaddress'=>$header->fromaddress,
	            							'from_personal'=>$header->from[0]->personal,
	            							'from_mailbox'=>$header->from[0]->mailbox,
	            							'from_host'=>$header->from[0]->host,
	            							'reply_toaddress'=>$header->reply_toaddress,
	            							'reply_to_personal'=>$header->reply_to[0]->personal,
	            							'reply_to_mailbox'=>$header->reply_to[0]->mailbox,
	            							'reply_to_host'=>$header->reply_to[0]->host,
	            							'senderaddress'=>$header->senderaddress,
	            							'sender_personal'=>$header->sender[0]->personal,
	            							'sender_mailbox'=>$header->sender[0]->mailbox,
	            							'sender_host'=>$header->sender[0]->host,
	            							'recent'=>$header->Recent,
	            							'unseen'=>$header->Unseen,
	            							'flagged'=>$header->Flagged,
	            							'answered'=>$header->Answered,
	            							'deleted'=>$header->Deleted,
	            							'draft'=>$header->Draft,
	            							'msgno'=>$header->Msgno,
	            							'mailDate'=>$header->MailDate,
	            							'size'=>$header->Size,
	            							'udate'=>$header->udate
	            							  );
	            			$this->lead_model->gmail_header_add($header_post);

	            			// -----------------------
	            			// ATTACHMENT SCRIPT
	            			$attachments = [];
					        if(isset($structure->parts) && count($structure->parts)) 
					        {
					            for($i = 0; $i < count($structure->parts); $i++) 
					            {
					                $attachments[$i] = array(
					                        'is_attachment' => false,
					                        'filename' => '',
					                        'name' => '',
					                        'attachment' => ''
					                    );
					                if($structure->parts[$i]->ifdparameters) 
					                {
					                    foreach($structure->parts[$i]->dparameters as $object) 
					                    {
					                        if(strtolower($object->attribute) == 'filename') 
					                        {
					                            $attachments[$i]['is_attachment'] = true;
					                            $attachments[$i]['filename'] = $object->value;
					                        }
					                    }
					                }

					                if($structure->parts[$i]->ifparameters) 
					                {
					                    foreach($structure->parts[$i]->parameters as $object) 
					                    {
					                        if(strtolower($object->attribute) == 'name') 
					                        {
					                            $attachments[$i]['is_attachment'] = true;
					                            $attachments[$i]['name'] = $object->value;
					                        }
					                    }
					                }

					                if($attachments[$i]['is_attachment']) 
					                {
					                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $value, $i+1);                           
					                    if($structure->parts[$i]->encoding == 3) //3 = BASE64 encoding
					                    { 
					                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
					                    }
					                    elseif($structure->parts[$i]->encoding == 4) //4 = QUOTED-PRINTABLE encoding
					                    { 
					                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
					                    }
					                }
					            }
					            $filename_arr=[];
					            foreach($attachments as $attachment)//iterate through each attachment and save it
					            {
					                if($attachment['is_attachment'] == 1)
					                {
					                    $filename = $attachment['name'];                        
					                    if(empty($filename)) $filename = $attachment['filename'];
					                    if(empty($filename)) $filename = time() . ".dat";
					                    $new_fileName = $date.'-'.$value.'-'.$filename;                        
					                    //if(!in_array($new_fileName, $fName))
					                    //{
					                        $folder="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/mail_attachment/";
					                        if(!is_dir($folder))mkdir($folder);
					                        $fp = fopen("./". $folder ."/". $date . "-". $value."-". $filename, "w+");
					                        fwrite($fp, $attachment['attachment']);
					                        fclose($fp);
					                    //}
					                    //array_push($filename_arr, $filename);

					                    $attachment_post=array(
	            							'overview_id'=>$overview_auto_id,
	            							'file_name'=>$filename,
	            							'file_full_path'=>$folder.$filename
	            							  );
	            						$this->lead_model->gmail_attachment_add($attachment_post);
					                }
					            }
					        }
					        
					        //imap_mail_move($inbox,$overview[0]->msgno,'xyz_label'); 
					        //imap_expunge($inbox); 

					        // ATTACHMENT SCRIPT
					        // -----------------------     
	            		}
	            	}
	            }
	        }
	        imap_close($inbox);
		}
		
		$list['rows']=$this->lead_model->get_gmail_data($user_id);
		$html = $this->load->view('admin/lead/rander_download_from_gmail_ajax',$list,TRUE);		
		$result['html'] = $html;
		$result['msg'] = '';
        echo json_encode($result);
        exit(0); 
	}

	private function include_gmail_api_files()
    {    	
        require APPPATH.'libraries/gmail_api_vendor/autoload.php';
        require_once APPPATH.'libraries/Gmail_api_connection.php';           
    }


    public function gmail_api_return()
    {
    	//echo'Gmail Token has been created,';
    	$conn=new Gmail_api_connection();
    	$data=array();
    	$this->load->view('admin/lead/gmail_api_return_view',$data);
    }

	public function sync_gmail()
	{		
		//$this->include_gmail_api_files();
		//$this->load->library('gmail_api');
		//$this->gmail_api->readLabels();
		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		$rows=[];
		$conn=new Gmail_api_connection();
        if($conn->is_connected())
        { 	
        	// die("connected");
            // require_once("gmail.php");
            require_once APPPATH.'libraries/Gmail_api.php';             
            $gmailApiObj=new Gmail_api($conn->get_client());
            // $gmail_data=$gmailApiObj->readLabels();
            $sync_account_info=$this->setting_model->get_all_gmail_for_sync($user_id);
            $arg=array();
            $arg['last_sync_at']=$sync_account_info->last_sync_at;
            // print_r($arg); die();
            $gmail_data=$gmailApiObj->listData($arg);
            // print_r($gmail_data); die('okkkkk12');
            if(count($gmail_data))
            {	
            	$this->db->close();
				$this->db->initialize();
            	foreach($gmail_data AS $gmail)
            	{
            		$is_exist=$this->lead_model->is_exist_message_id($gmail['message_id']);
            		if($is_exist=='N')
            		{
            			$customer_id=0;
            			$to=$gmail['to'];
            			$to_email_info=get_gmail_mails_decode($to);
            			$to_email_str=$to_email_info['mail_str'];
            			$to_email_arr=$to_email_info['mail_arr'];
            			//$to_email = preg_replace('/(.*)<(.*)>(.*)/sm', '\2', $to);
            			$from=$gmail['from'];            			
            			$from_email_info=get_gmail_mails_decode($from);
            			$from_email_str=$from_email_info['mail_str'];
            			$from_email_arr=$from_email_info['mail_arr'];
            			//$from_email = preg_replace('/(.*)<(.*)>(.*)/sm', '\2', $from);
            			/*
            			$cust_arr=array(
							'email'=>$from_email,
							'mobile'=>''
							);					        
				        $get_customer_decision=$this->lead_model->get_decision_for_gmail($cust_arr);
						if($get_customer_decision['status']==TRUE)
						{							
							$customer_id=$get_customer_decision['customer_id'];
							$customer_exist_keyword=$get_customer_decision['msg'];					
												
						}
						else
						{
							$customer_exist_keyword='no_customer_exist';
						}
						*/
						$overview_post=array(
        							'message_id'=>$gmail['message_id'],
        							'history_id'=>$gmail['history_id'],
        							'internal_date'=>$gmail['internal_date'],
        							'label_ids'=>$gmail['label_ids'],
        							'thread_id'=>$gmail['thread_id'],
        							'snippet'=>$gmail['snippet'],
        							'from_name'=>$from,
        							'from_mail'=>$from_email_str,
        							'to_name'=>$to,
        							'to_mail'=>$to_email_str,
        							'subject'=>$gmail['subject'],
        							'date'=>$gmail['date'],
        							'body_text'=>$gmail['body_text'],
        							'body_html'=>$gmail['body_html'],
        							'user_id'=>$user_id,
        							'created_at'=>date("Y-m-d H:i:s"),
        							'updated_at'=>date("Y-m-d H:i:s"),
        							'msg'=>$get_customer_decision['msg'].'/'.$from_email
        							);
	            		$gmail_data_auto_id=$this->lead_model->gmail_data_add($overview_post);
	            		if($gmail_data_auto_id)
	            		{
	            			// ------------------------
	            			if($from_email_str)
	            			{
	            				$f_email_arr=explode(',', $from_email_str);
	            				if(count($f_email_arr))
	            				{
	            					foreach($f_email_arr AS $fm)
	            					{
	            						$fmail_post=array(
	        							'gmail_data_id'=>$gmail_data_auto_id,
	        							'thread_id'=>$gmail['thread_id'],
	        							'mail'=>$fm,
	        							'mail_type'=>'F'
	        							);
	        							$this->lead_model->gmail_data_mail_add($fmail_post);
	            					}
	            				}
	            			}
	            			if($to_email_str)
	            			{
	            				$t_email_arr=explode(',', $to_email_str);
	            				if(count($t_email_arr))
	            				{
	            					foreach($t_email_arr AS $tm)
	            					{
	            						$tmail_post=array(
	        							'gmail_data_id'=>$gmail_data_auto_id,
	        							'thread_id'=>$gmail['thread_id'],
	        							'mail'=>$tm,
	        							'mail_type'=>'T'
	        							);
	        							$this->lead_model->gmail_data_mail_add($tmail_post);
	            					}
	            				}
	            			}
	            			// ------------------------

	            			if(count($gmail['attachment']))
	            			{
	            				foreach($gmail['attachment'] AS $attach)
	            				{
	            					$attachment_post=array(
            							'gmail_data_id'=>$gmail_data_auto_id,
            							'file_name'=>$attach['file_name'],
            							'file_path'=>$attach['file_path'],
            							'file_full_path'=>$attach['full_file_path']
            							  );
            						$this->lead_model->gmail_data_attachment_add($attachment_post);
	            				}
	            			}

	            			$existing_lead_id=$this->lead_model->get_existing_lead_by_thread($gmail['thread_id']);
		            		if($existing_lead_id>0)
		            		{
		            			$update=array(
				                	'lead_id'=>$existing_lead_id
				                );
				    			$this->lead_model->gmail_data_update($update,$gmail_data_auto_id);
		            		}
	            		}	            		
	            		/*
	            		$update=array(
			                'is_read'=>'N',
			                'customer_id'=>$customer_id,
        					'customer_exist_keyword'=>$customer_exist_keyword
			                );
			    		$this->lead_model->gmail_data_update_by_thread_id($update,$gmail['thread_id']);
			    		*/
			    		$update=array(
			                'is_read'=>'N'
			                );
			    		$this->lead_model->gmail_data_update_by_thread_id($update,$gmail['thread_id']);
            		}
            	}
            }
            //$is_connected='Y';
            $token_link='';
        }
        else
        {        	
            $token_link=$conn->unauthenticated_data();
        }
        return $token_link;	         
	}

	public function gmail_api_connection_check()
	{		
		$syncEmail = get_sync_email_account();
		if($syncEmail)
		{
			$conn=new Gmail_api_connection();
	        if($conn->is_connected())
	        { 	
	        	$token_link='';            
	        }
	        else
	        {
	            $token_link=$conn->unauthenticated_data();
	        }
	        return $token_link;
		}
				
	}

	public function download_from_gmail($is_sync='N')
	{		
		$data=array();
		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		// $post_data=array('last_sync_at'=>date("Y-m-d H:i:s"));
		// $this->setting_model->update_gmail_sync_by_user($user_id,$post_data);
		// $sync_account_info=$this->setting_model->get_all_gmail_for_sync($user_id);

		// $arg=array();
		// $arg['user_id']=$user_id;	
		// $data['rows']=$this->lead_model->get_gmail_data($arg);
		// $is_gmail_connected='N';
		$get_token_create_link=$this->gmail_api_connection_check();				
		if($get_token_create_link!='')
		{
			$is_gmail_connected='N';
		}
		else
		{
			$is_gmail_connected='Y';
		}		
		$data['is_sync']=$is_sync;
		$data['is_gmail_connected']=$is_gmail_connected;
		$data['syncEmail'] = get_sync_email_account();
		$this->load->view('admin/lead/download_from_gmail',$data);
	}

	// AJAX PAGINATION START
	function get_list_from_gmail_ajax()
	{
		$get_token_create_link='';
		$start = $this->input->get('page');
		$sync_from_gmail=$this->input->get('sync_from_gmail');
		$session_data = $this->session->userdata('admin_session_data');	
		$user_id=$session_data['user_id'];

		// $sync_from_gmail='N';		
		if($sync_from_gmail=='Y')
		{
			$get_token_create_link=$this->gmail_api_connection_check();		
			if($get_token_create_link!='')
			{
				// $data =array (
				// "token_link"=>$get_token_create_link
				// );		   
			    // $this->output->set_content_type('application/json');
			    // echo json_encode($data);		    
			    // exit();
			}
			else
			{
				$post_data=array('last_sync_at'=>date("Y-m-d H:i:s"));
				$this->setting_model->update_gmail_sync_by_user($user_id,$post_data);
				$this->sync_gmail();
				
				// $return=$this->sync_gmail();
				// if($return!='')
				// {
				// 	$data =array (
				//        "token_link"=>$return
				//     );		   
				//     $this->output->set_content_type('application/json');
				//     echo json_encode($data);
				//     exit();
				// }
			}
		}
		
		
	    
	     

	    $this->load->library('pagination');
	    
	    $limit=30;
	    $config = array();
	    $list=array();
	    $arg=array();		    
	    // FILTER DATA
	    $is_lead_responses=$this->input->get('filter_is_lead_responses');
		$arg['filter_search_str']=$this->input->get('filter_by_str');
		$arg['filter_by_from']=$this->input->get('filter_by_from');
		$arg['filter_by_to']=$this->input->get('filter_by_to');
		$arg['filter_by_subject']=$this->input->get('filter_by_subject');
		$arg['filter_by_date']=$this->input->get('filter_by_date');
		$arg['filter_by_date_to']=$this->input->get('filter_by_date_to');
		$arg['user_id']=$user_id;			
		if($is_lead_responses=='Y')
		{
			$arg['is_show_linked']="Y";
		}
		else
		{
			$arg['is_show_linked']="N";
		}
		

	   //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->lead_model->get_gmail_count($arg);
	    $config['per_page'] = $limit;
	    $config['uri_segment']=3;
	    $config['num_links'] = 1;
	    $config['use_page_numbers'] = TRUE;
	    $config['display_pages'] = FALSE; 
	    $config['first_link'] = false;
		$config['last_link'] = false;
	   //$config['full_tag_open'] = '<div id="paginator" class="dataTables_paginate paging_bootstrap">';
	    $config['attributes'] = array('class' => 'myclass');
	   //$config['full_tag_close'] = '</div>';
	   // $config['prev_link'] = '&lt;Previous';
	   // $config['next_link'] = 'Next&gt;';
	    
	    $config["full_tag_open"] = '<ul class="pagination gmail-pagi">';
	    $config["full_tag_close"] = '</ul>';	
	    // $config["first_link"] = "&laquo;";
	    $config["first_tag_open"] = "<li>";
	    $config["first_tag_close"] = "</li>";
	    // $config["last_link"] = "&raquo;";
	    $config["last_tag_open"] = "<li>";
	    $config["last_tag_close"] = "</li>";
	    $config['next_link'] = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
	    $config['next_tag_open'] = '<li>';
	    $config['next_tag_close'] = '<li>';
	    $config['prev_link'] = '<i class="fa fa-angle-left" aria-hidden="true"></i>';
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

    	$list['rows']=$this->lead_model->get_gmail_list($arg);		
	    $table = '';	    
	    $table = $this->load->view('admin/lead/gmail_list_view_ajax',$list,TRUE);
	
		// PAGINATION COUNT INFO SHOW: START
		$tmp_start=($start+1);		
		$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
	    $page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
		// PAGINATION COUNT INFO SHOW: END


	    $thread_id_arr=array();
		foreach($list['rows'] AS $row)
		{
			$thread_id_arr[]=$row['thread_id'];
		}						
		$this->session->set_userdata('gmail_thread_list',$thread_id_arr);	

		$get_response_count=$this->lead_model->get_gmail_response_count($arg);	
	    $data =array (
	       "table"=>$table,
	       "page"=>$page_link,
		   "page_record_count_info"=>$page_record_count_info,
		   "token_link"=>$get_token_create_link,
		   "get_response_count"=>$get_response_count		   
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	// AJAX PAGINATION END

	public function sync_other_account_ajax()
	{
		$get_token_create_link='';
		$get_token_create_link=$this->gmail_api_connection_check();
		//$gmail_data=$this->gmail_profile('me');print_r($gmail_data); die();
		$data =array (
	       "token_link"=>$get_token_create_link
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit;
	}
	public function sync_logout_account_ajax()
	{
		// $folder="assets/gmail_api";
		$file=get_gmail_token();
        if(file_exists($file)){
		    @unlink($file);
		    $msg='removed';
		}else{
		    // echo 'file not found';
		    $msg='not_removed';
		}

		$data =array (
	       "msg"=>$msg
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	public function download_from_gmail_detail($thread_id)
	{			
		$data=array();
		// echo get_sync_email_account();die();
		$session_data = $this->session->userdata('admin_session_data');	
		$user_id=$session_data['user_id'];
		$arg['user_id']=$user_id;
		$arg['customer_exist_keyword']="";
		$arg['thread_id']=$thread_id;
		$data['get_all_thread_mails']=$this->lead_model->get_all_mails_from_gmail_by_thread_id($arg);
		//print_r($data['get_all_thread_mails']);// die();
		$get_info=
		$data['start_mail']=$this->lead_model->get_first_gmail_message_by_thread_id($thread_id);
		$data['last_mail']=$this->lead_model->get_last_gmail_message_by_thread_id($thread_id);
		$data['rows']=$this->lead_model->get_gmail_detail_by_thread_id($thread_id);
		// print_r($data['start_mail']); die('ok');	
		$emails=$data['get_all_thread_mails']['mails'];
		$sync_email_account=array($this->setting_model->get_gmail_for_sync($user_id));
		$emails=array_diff( $emails, $sync_email_account );
		//print_r($emails); die();		
		$data['contact_list']=$this->customer_model->get_customers_by_emails($emails);
		$data['thread_id']=$thread_id;
		// print_r($data['contact_list']); die();
		$contact_email_arr=array();
		$non_contact_email_arr=array();
		if(count($data['contact_list'])>0)
		{
			foreach($data['contact_list'] AS $cl)
			{
				array_push($contact_email_arr, $cl['email']);
			}
		}
		
		$contact_email_arr=array_unique($contact_email_arr);		
		$non_contact_email_arr = array_values(array_filter(array_diff(array_merge($emails, $contact_email_arr), array_intersect($emails, $contact_email_arr))));
		$data['contact_email_arr']=$contact_email_arr;
		$data['non_contact_email_arr']=$non_contact_email_arr;
		// print_r($contact_email_arr);
		// print_r($non_contact_email_arr); 
		// die();

		// $data['last_mail']=$this->lead_model->get_last_gmail_message_by_thread_id($thread_id);
		if($data['start_mail']['lead_id']>0)
		{
			$lead_id=$data['start_mail']['lead_id'];
			$lead_data=$this->lead_model->GetLeadData($data['start_mail']['lead_id']);
			$data['opportunity_list']=$this->opportunity_model->GetOpportunityListAll($lead_id);
		}
		else
		{
			$lead_id='';
			$lead_data=array();
			$data['opportunity_list']=array();
		}
		
		$data['lead_id']=$lead_id;
		$data['lead_data']=$lead_data;

		// echo $data['last_mail']['is_read']; die();
		if($data['last_mail']['is_read']=='N')
        {
        	$update=array(
                'is_read'=>'Y',
                'updated_at'=>date("Y-m-d H:i:s")
                );
        	// echo $thread_id; die();
    		$this->lead_model->gmail_data_update_by_thread_id($update,$thread_id);
        }

        $get_token_create_link=$this->gmail_api_connection_check();		
		if($get_token_create_link!='')
		{
			$is_gmail_connected='N';
		}
		else
		{
			$is_gmail_connected='Y';
		}		
		$data['is_gmail_connected']=$is_gmail_connected;
		// print_r($data['start_mail']); die();
		if($data['start_mail']['lead_id']>0)
		{
			$is_linked='Y';
		}
		else
		{
			$is_linked='N';
		}
		// echo $is_linked; die();
		$gmail_thread_list=$this->session->userdata('gmail_thread_list');		
		$data['previous_thread']=$this->lead_model->get_prev_thread($gmail_thread_list,$thread_id);
		$data['next_thread']=$this->lead_model->get_next_thread($gmail_thread_list,$thread_id);
		$this->load->view('admin/lead/download_from_gmail_detail',$data);
	}

	public function download_gmail_attachment($file_path='')
	{	
		if($file_path!='')
		{	
			$this->load->helper(array('download'));	
			$file_path_tmp = base64_decode($file_path);
			$file_name=end(explode("/", $file_path_tmp));			
			force_download($file_name, $file_path_tmp); 
			exit;
		}
	}

	public function gmail_lead_add_view_rander_ajax()
	{
		$data=NULL;
		$id=$this->input->post('gmail_inbox_overview_id');		
		$gmail_data=$this->lead_model->get_gmail_data_detail($id);
		$data['tmp_com_source_id']=$this->lead_model->get_source_id(DOWNLOAD_FROM_GMAIL);
		$data['gmail_data']=$gmail_data;
		$data['source_list']=$this->source_model->GetSourceListAll();
		$data['country_list']=$this->countries_model->GetCountriesList();	

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
        $data['assigned_user_id']=($user_id!=1)?$user_id:'';        
		$this->load->view('admin/lead/gmail_add_lead_modal_ajax',$data);
	}

	function delete_gmail_lead()
    {        
        $ids_str = $this->input->post('ids'); 
        $status = $this->input->post('status');  
        $msg='';          
        if($ids_str)
        {
        	$ids_arr=explode(",", $ids_str);
        	foreach($ids_arr as $id)
        	{      
        		/*  
        		// ------------------------------
        		// unlink EXISTING file 
        		
        		$get_ids_str=$this->lead_model->get_gmail_ids_from_thread_id($id); 
        		$attachments=$this->lead_model->get_attachments_by_gmail_ids($get_ids_str);
        		$file_arr=explode(",", $attachments['file_name']);

                for($i=0;$i<count($file_arr);$i++)
                {
                	
                	$folder="assets/gmail_api";
			        if(file_exists($folder ."/". $file_arr[$i])){
					    @unlink($folder ."/". $file_arr[$i]);
					}else{
					    // echo 'file not found';
					}
                }
                // unlink EXISTING file
                // ------------------------------ 
        		$this->lead_model->gmail_data_delete_by_thread_id($id);
				*/
        		$update=array(
        			'snippet'=>'',
        			'from_name'=>'',
        			'from_mail'=>'',
        			'to_name'=>'',
        			'to_mail'=>'',
        			'body_text'=>'',
        			'body_html'=>'',
        			'subject'=>'',
        			'date'=>'',
        			'deleted_status'=>strtoupper($status),
                    'is_deleted'=>'Y',
                    'updated_at'=>date("Y-m-d H:i:s"),
                    'deleted_datetime'=>date("Y-m-d H:i:s")
                    );
        		$this->lead_model->gmail_data_update_by_thread_id($update,$id);

        		$this->lead_model->gmail_data_mail_delete_by_thread_id($id);
        	}
        }        
        $result["status"] = 'success';
        $result['msg']=$msg;
        echo json_encode($result);
        exit(0);        
    }

    function change_gmail_read_status()
    {        
        $ids_str = $this->input->post('ids');
        $curr_status = $this->input->post('curr_status');       
        
        if($curr_status=='N')
        {
            $s='Y';
        }
        else
        {
            $s='N';
        }
                    
        if($ids_str)
        {
        	$ids_arr=explode(",", $ids_str);
        	foreach($ids_arr as $id)
        	{
        		$update=array(
                    'is_read'=>$s,
                    'updated_at'=>date("Y-m-d H:i:s")
                    );
        		$this->lead_model->gmail_data_update_by_thread_id($update,$id);
        	}
        }  
        
        $result["status"] = 'success';
        echo json_encode($result);
        exit(0);        
    }

    public function download_from_gmail_response()
	{		
		$data=array();
		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		// $arg=array();
		// $arg['user_id']=$user_id;	
		// $data['rows']=$this->lead_model->get_gmail_data($arg);
		$this->load->view('admin/lead/download_from_gmail_response',$data);
	}
	// AJAX PAGINATION START
	function get_list_from_gmail_response_ajax()
	{
	    $start = $this->input->get('page');
	    $session_data = $this->session->userdata('admin_session_data');	
		$user_id=$session_data['user_id']; 
	    $this->load->library('pagination');
	    $limit=30;
	    $config = array();
	    $list=array();
	    $arg=array();		    
	    // FILTER DATA
		// $arg['filter_search_str']=$this->input->get('filter_search_str');
		$arg['user_id']=$user_id;
		$arg['customer_exist_keyword']="'one_customer_exist','multiple_customer_exist'";	
	   //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->lead_model->get_gmail_data_count($arg);
	    $config['per_page'] = $limit;
	    $config['uri_segment']=3;
	    $config['num_links'] = 1;
	    $config['use_page_numbers'] = TRUE;
	   //$config['full_tag_open'] = '<div id="paginator" class="dataTables_paginate paging_bootstrap">';
	    $config['attributes'] = array('class' => 'myclass');
	   //$config['full_tag_close'] = '</div>';
	   // $config['prev_link'] = '&lt;Previous';
	   // $config['next_link'] = 'Next&gt;';
	    
	    $config["full_tag_open"] = '<ul class="pagination">';
	    $config["full_tag_close"] = '</ul>';	
	    $config["first_link"] = "&laquo;";
	    $config["first_tag_open"] = "<li>";
	    $config["first_tag_close"] = "</li>";
	    $config["last_link"] = "&raquo;";
	    $config["last_tag_open"] = "<li>";
	    $config["last_tag_close"] = "</li>";
	    $config['next_link'] = '<i class="fa fa-angle-right" aria-hidden="true"></i>';
	    $config['next_tag_open'] = '<li>';
	    $config['next_tag_close'] = '<li>';
	    $config['prev_link'] = '<i class="fa fa-angle-left" aria-hidden="true"></i>';
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

    	$list['rows']=$this->lead_model->get_gmail_data($arg);		
	    $table = '';	    
	    $table = $this->load->view('admin/lead/gmail_response_list_view_ajax',$list,TRUE);
	
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
	
	public function gmail_reply()
	{
		$reply_thread_id=$this->input->post('reply_thread_id');
		$reply_mail_subject=$this->input->post('reply_mail_subject');
		$reply_mail_to=$this->input->post('reply_mail_to');	
		$reply_mail_to_cc=$this->input->post('reply_mail_to_cc');		
		//$reply_mail_from=$this->input->post('reply_mail_from');
		$reply_email_body=$this->input->post('reply_email_body');
				

		$conn=new Gmail_api_connection();
        if($conn->is_connected())
        { 	
        	$msg=$this->gmail_send_mail($reply_mail_to,$reply_mail_to_cc,$reply_thread_id,$reply_mail_subject,$reply_email_body); 
        }    

        $status='success';
        $data =array (
			        "msg"=>$msg,
			        "status"=>$status,
			        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit;    
        

	}

	
	

	function set_call_schedule_from_app_ajax()
    {
    	$msg='';
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
    	$lead_id = $this->input->post('lid');  
    	$lead_data=$this->lead_model->GetLeadData($lead_id);

    	$is_accept=$this->lead_model->is_accept_call_request($user_id,$lead_id);
    	if($is_accept=='Y')
    	{
    		$notification_post=array('lead_id'=>$lead_id,
					'user_id'=>$user_id,
					'company_id'=>$lead_data->customer_id,
					'contact_person'=>$lead_data->cus_contact_person,
					'contact_number'=>$lead_data->cus_mobile,
					'company_name'=>($lead_data->cus_company_name)?$lead_data->cus_company_name:'N/A',
					'call_status'=>'N'
					);
    		$post=array('lead_id'=>$lead_id,
					'user_id'=>$user_id,
					'company_id'=>$lead_data->customer_id,
					'contact_person'=>$lead_data->cus_contact_person,
					'contact_number'=>'+'.$lead_data->cus_country_code.$lead_data->cus_mobile,
					'company_name'=>($lead_data->cus_company_name)?$lead_data->cus_company_name:'N/A',
					'call_status'=>'N',
					'created_at'=>date("Y-m-d H:i:s"),
					'updated_at'=>date("Y-m-d H:i:s")
					);
    		$return=$this->lead_model->CreateCallScheduleLog($post);

    		$lead_title=get_value("title","lead","id=".$lead_id);				
        	// ----------------------
        	// CREATE LEAD HISTORY
        	/*
			$attach_filename='';
		    $update_by=$this->session->userdata['admin_session_data']['user_id'];
			$date=date("Y-m-d H:i:s");				
			$ip_addr=$_SERVER['REMOTE_ADDR'];				
			$message="A call has beed initiated through LMS Baba Mobile App for The lead (".$lead_title.") on ".date_db_format_to_display_format(date('Y-m-d'))."";		

			$comment_title=LEAD_UPDATE_MANUAL;
			$historydata=array(
								'title'=>$comment_title,
								'lead_id'=>$lead_id,
								'comment'=>$message,
								'attach_file'=>$attach_filename,
								'create_date'=>$date,
								'communication_type_id'=>3,
								'communication_type'=>'Call Update',
								'user_id'=>$update_by,
								'ip_address'=>$ip_addr
							);
			$this->history_model->CreateHistory($historydata);	
			*/
			// CREATE LEAD HISTORY
			// ----------------------
    	}
    	else
    	{
    		$return=true;
    	}
		


    	// =====================================================
    	// Android push nitification script
    	$device_id=get_value('user_token','user','id='.$user_id);
    	$message='A new phone number added in your list to call';
    	$title='LMSBABA Click to call your customer';
    	if($device_id)
    	{
    		$msg=push_notification_android($device_id,$title,$message,$notification_post);
    	}
    	// Android push nitification script
    	// =====================================================
    	
    	
    	if($return!=false)
    	{
    		$status='success';
    	}
    	else
    	{
    		$status='fail';
    	}
    	
		$result['msg'] =$msg;
		$result['status'] = $status;
        echo json_encode($result);
        exit(0);
    }

    public function mail_check()
    {
    	/*
    	$to_name='Developer';
		$to_email = "arupporel123@gmail.com";
		$subject = "My subject";
		$txt = "Hello world!";
		// $from_name='Admin';
		// $from_email='nilsoft_d@yahoo.co.in';
		// $headers = "From: shashi_nar@yahoo.com" . "\r\n" .
		// "CC: nilsoft_d@yahoo.co.in";
		//$headers = "From: \"".$from_name."\" <".$from_email.">\r\n";
		$headers = 'From: admin@lmsbaba.com ' . "\r\n" .
				    'Reply-To: nilsoft_d@yahoo.co.in' . "\r\n" .
				    'X-Mailer: PHP/' . phpversion();
    	$headers .= "To: \"".$to_name."\" <".$to_email.">\r\n";
		// $headers .= "Return-Path: <".$from_email.">\n";
	    $headers .= "MIME-Version: 1.0\r\n";
	    $headers .= "Content-Type: text/HTML; charset=ISO-8859-1\r\n";
		$r=mail($to_email,$subject,$txt,$headers);
		if($r)
		{
			echo'Sent';
		}
		else
		{
			echo'Not sent';
		}		
		die("ok");
		*/
    	$company=get_company_profile();	
    	$com_company_id=480;
    	$customer=$this->customer_model->GetCustomerData($com_company_id);
    	$update_by=$this->session->userdata['admin_session_data']['user_id'];
    	$cc_mail='';
        $self_cc_mail=get_value("email","user","id=".$update_by);
        $update_by_name=get_value("name","user","id=".$update_by);
	    // $to_mail=$customer->email;	    
	    $to_mail='arupporel123@gmail.com';
	    if($to_mail)
	    {
	    	
	    	$get_company_detail=$this->customer_model->get_company_detail($com_company_id);		    	
	    	if($get_company_detail['contact_person']!='')
			{
				$mail_subject=$get_company_detail['contact_person'].', Your Enquiry # '.$lead_id.' has been received - '.$company['name']; 
			}
			else
			{
				$mail_subject='Your Enquiry # '.$lead_id.' has been acknowledged';
			}

	    	$this->load->library('mail_lib');
	        $m_d = array();
	        $m_d['from_mail']     = 'admin@lmsbaba.com';
	        $m_d['from_name']     = 'Chemerange';
	        // $m_d['from_mail']     = $self_cc_mail;
	        // $m_d['from_name']     = $update_by_name;
	        $m_d['to_mail']       = $to_mail;
	        $m_d['cc_mail']       = $cc_mail;
	        $m_d['subject']       = $mail_subject;
	        $m_d['message']       = 'testing';
	        $m_d['attach']        = array();
	        // print_r($m_d); die();
	        $mail_return = $this->mail_lib->send_mail($m_d);
	        if($mail_return)
	        {
	        	print_r($m_d);
	        	echo'Sent';
	        }
	        else
	        {
	        	echo'Not sent';
	        }
	    }
    }

    

    function get_lead_id_from_customers()
    {        
        $cust_id = $this->input->post('cust_id');
        $filter_by_stage = $this->input->post('filter_by_stage');
        $lid=$this->lead_model->get_lead_id_from_customers($cust_id,$filter_by_stage,'','');
        $result["lid"] = $lid;
        echo json_encode($result);
        exit(0);        
    }

    function rander_lead_view_popup_ajax()
    {        
    	$data=array();
        $id = $this->input->post('lid');
        $filter_by_stage = $this->input->post('filter_by_stage');
        $is_linked_view = $this->input->post('is_linked_view');
        $data['is_linked_view']=$is_linked_view;
        $data['lead_data']=$this->lead_model->GetLeadData($id);	
        $cust_id=$data['lead_data']->cus_id;
        $data['all_ids']=$this->lead_model->get_all_lead_id_from_customers($cust_id,$filter_by_stage);
        $data['id']=$id;
        // $data['next_id']=$this->lead_model->get_lead_id_from_customers($cust_id,$filter_by_stage,$id,'next');
        // $data['previous_id']=$this->lead_model->get_lead_id_from_customers($cust_id,$filter_by_stage,$id,'previous');
        $data['filter_by_stage']=$filter_by_stage;
        $data['next_follow_by']=$this->lead_model->get_next_follow_by();
        echo $this->load->view('admin/lead/rander_lead_view_popup_ajax',$data,true);
    }

    function rander_comment_update_lead_view_popup_ajax()
    {        
    	$data=array();
        $id = $this->input->post('lid');
        $thread_id = $this->input->post('thread_id');
        $is_linked_view = $this->input->post('is_linked_view');
        $data['is_linked_view']=$is_linked_view;
        $filter_by_stage = '';
        $data['lead_data']=$this->lead_model->GetLeadData($id);	
        $cust_id=$data['lead_data']->cus_id;
        $data['all_ids']=$this->lead_model->get_all_lead_id_from_customers($cust_id,$filter_by_stage);
        $data['id']=$id;
        // $data['next_id']=$this->lead_model->get_lead_id_from_customers($cust_id,$filter_by_stage,$id,'next');
        // $data['previous_id']=$this->lead_model->get_lead_id_from_customers($cust_id,$filter_by_stage,$id,'previous');
        $data['filter_by_stage']=$filter_by_stage;
        $data['next_follow_by']=$this->lead_model->get_next_follow_by();        // user list
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
        $data['last_mail']=$this->lead_model->get_last_gmail_message_by_thread_id($thread_id);
        $data['user_list']=$this->user_model->GetUserList($user_ids_str);
        $data['opportunity_list']=$this->opportunity_model->GetSentToClientStatusOpportunityListAll($id);
        echo $this->load->view('admin/lead/rander_comment_update_lead_view_popup_ajax',$data,true);
    }

    function edit_lead_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			
			$lead_id=$this->input->post('lead_id');
			// LEAD INFO
			$lead_title=$this->input->post('lead_title');
	        // $lead_requirement = $this->input->post('lead_requirement'); 
			$lead_requirement = strip_tags($this->input->post('lead_requirement_text'),'<br>'); 
	        $lead_follow_up_date = date_display_format_to_db_format($this->input->post('lead_follow_up_date'));
	        $lead_follow_up_type = $this->input->post('lead_follow_up_type');
	        if($lead_id)
			{
				$lead_post_data=array(
									'title'=>$lead_title,	
									'buying_requirement'=>$lead_requirement,
									'followup_date'=>$lead_follow_up_date,
									'followup_type_id'=>$lead_follow_up_type,
									'modify_date'=>date('Y-m-d'),
								);
	        	$this->lead_model->UpdateLead($lead_post_data,$lead_id);

	        	// ----------------------
	        	// CREATE LEAD HISTORY
				$attach_filename='';
			    $update_by=$this->session->userdata['admin_session_data']['user_id'];
				$date=date("Y-m-d H:i:s");				
				$ip_addr=$_SERVER['REMOTE_ADDR'];		
				$message="The lead Title/ Describe Requirements has been updated on ".date_db_format_to_display_format(date('Y-m-d'))."";		
				// $message="The lead (".$lead_title.") has been updated on ".date_db_format_to_display_format(date('Y-m-d')).", from gmail response ";
				$comment_title=LEAD_UPDATE_MANUAL;
				$historydata=array(
									'title'=>$comment_title,
									'lead_id'=>$lead_id,
									'comment'=>addslashes($message),
									'attach_file'=>$attach_filename,
									'create_date'=>$date,
									'user_id'=>$update_by,
									'ip_address'=>$ip_addr
								);
				$this->history_model->CreateHistory($historydata);	
				// CREATE LEAD HISTORY
				// ----------------------
	        				
			}
			$status_str='success';
	        $result["status"] = $status_str;
	        $result['msg']='';
	        echo json_encode($result);
	        exit(0);
		}
	}

	function get_quotation_id_from_leads()
    {        
        $cust_id = $this->input->post('cust_id');
        $lead_ids = $this->input->post('lead_ids');
        $id=$this->opportunity_model->get_quotation_id_from_leads($lead_ids);
        $result["id"] = $id;
        echo json_encode($result);
        exit(0);        
    }

	function rander_quotation_view_popup_ajax()
    {        
    	$data=array();
        $id = $this->input->post('id');
        $lead_ids = $this->input->post('lead_ids');
        $data['lead_ids']=$lead_ids;
        $data['row']=$this->opportunity_model->GetOpportunityDetails($id);	
        // $cust_id=$data['lead_data']->cus_id;
        $data['all_ids']=$this->opportunity_model->get_all_quotation_id_from_leads($lead_ids);
        $data['id']=$id;   
		$data['lead_data']=$this->lead_model->GetLeadData($lead_ids);
        echo $this->load->view('admin/lead/rander_quotation_view_popup_ajax',$data,true);
    }

    function rander_create_lead_view_popup_ajax()
    {        
    	$data=array();
        $cust_id = $this->input->post('cust_id');
        $thread_id = $this->input->post('thread_id');
        $customer_email = $this->input->post('customer_email');
        $customer_name = $this->input->post('customer_name');    
        $data['customer_email']=$customer_email;    
        $data['customer_name']=$customer_name;    
        $data['customer']=$this->customer_model->get_company_detail($cust_id);
        $data['source_list']=$this->source_model->GetSourceListAll();
		$data['country_list']=$this->countries_model->GetCountriesList();
		$data['customer_id']=$cust_id;

		if($customer['source_id'])
		{
			$data['selected_source']=$data['customer']['source_id'];
		}
		else
		{
			$data['selected_source']=1;
		}

		// user list
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		/*
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
		*/
        $tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
   		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		$data['user_list']=$this->user_model->GetUserList($tmp_u_ids_str);
		$data['user_id']=$user_id;

        $data['next_follow_by']=$this->lead_model->get_next_follow_by();

        $data['start_mail']=$this->lead_model->get_first_gmail_message_by_thread_id($thread_id);
		// $data['thread_list']=$this->lead_model->get_gmail_detail_by_thread_id($thread_id);

		$data['last_mail']=$this->lead_model->get_last_gmail_message_by_thread_id($thread_id);
		$data['thread_id']=$thread_id;
        echo $this->load->view('admin/lead/rander_create_lead_view_popup_ajax',$data,true);
    }

    public function lead_update_to_gmail_thread()
    {
    	$lead_id = $this->input->post('lead_id');
    	$thread_id = $this->input->post('thread_id');
    	$data=array('lead_id'=>$lead_id);
    	$this->lead_model->gmail_data_update_by_thread_id($data,$thread_id);
    	$result["status"] = 'success';
        echo json_encode($result);
        exit(0); 
    }

    public function rander_web_whatsapp_popup()
	{
		$list=array();
		$lead_id = $this->input->post('lead_id');
    	$cust_id = $this->input->post('cust_id');
    	$lead_data=$this->lead_model->GetLeadData($lead_id);
    	$latest_opportunity=$this->opportunity_model->GetLatestOpportunity($lead_id);
    	$list['latest_opportunity']=$latest_opportunity;
    	$list['lead_data']=$lead_data;
    	$list['lead_id']=$lead_id;
    	$list['cust_id']=$cust_id;
    	$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
    	$list['whatsapp_templates']=$this->lead_model->get_webwhatsapp_template_list($user_id);
    	$html = $this->load->view('admin/lead/rander_web_whatsapp_popup_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0); 
	}

	public function rander_web_whatsapp_template_html()
	{
		$list=array();
		$lead_id = $this->input->post('lead_id');
    	$id = $this->input->post('id');
    	$lead_data=$this->lead_model->GetLeadData($lead_id);
    	$get_template=$this->lead_model->get_webwhatsapp_template($id);
    	$latest_opportunity=$this->opportunity_model->GetLatestOpportunity($lead_id);

    	$company=get_company_profile();
    	$keyword_1='{{CUSTOMER_CONTACT_PERSON}}';
		$keyword_2='{{LEAD_ENQUIRY_DATE}}';
		$keyword_3='{{LEAD_SOURCE}}';
		$keyword_4='{{USER_NAME}}'; 
		$keyword_5='{{USER_MOBILE}}';
		$keyword_6='{{USER_EMAIL}}';
		$keyword_7='{{COMPANY_NAME}}';
		$keyword_8='{{COMPANY_ADDRESS}}';
		$keyword_9='{{CUSTOMER_EMAIL}}';
		$keyword_10='{{LEAD_ID}}';
		$keyword_11='{{QUOTATION_ID}}';
		$keyword_12='{{QUOTATION_EXPIRE_DATE}}';
		$keyword_13='{{QUOTATION_SENT_DATE}}';

		$html="";
		$description=$get_template['description'];
		$company_address=$company['city_name'].', '.$company['country_name'];
		$html =str_replace($keyword_1,$lead_data->cus_contact_person,$description); 
		$html =str_replace($keyword_2,date_db_format_to_display_format($lead_data->enquiry_date),$html); 
		$html =str_replace($keyword_3,$lead_data->source_name,$html);
		if($lead_data->user_gender=='M'){
			// $html =str_replace('His','His',$html);
		}
		else if($lead_data->user_gender=='F'){
			$html =str_replace('His','Her',$html);
		}
		else{
			$html =str_replace('His','The',$html);
		}		
		$html =str_replace($keyword_4,$lead_data->user_name,$html);
		$html =str_replace($keyword_5,$lead_data->user_mobile,$html);
		$html =str_replace($keyword_6,$lead_data->user_email,$html);
		$html =str_replace($keyword_7,$company['name'],$html);
		$html =str_replace($keyword_8,$company_address,$html);
		$html =str_replace($keyword_9,$lead_data->cus_email,$html);
		$html =str_replace($keyword_10,'#'.$lead_id,$html); 
		// if(count($latest_opportunity))
		if(isset($latest_opportunity->id))
		{
			$html =str_replace($keyword_11,'#'.$latest_opportunity->id.' ('.$latest_opportunity->quote_no.')',$html);
			$html =str_replace($keyword_12,date_db_format_to_display_format($latest_opportunity->quote_valid_until),$html);	
			$html =str_replace($keyword_13,date_db_format_to_display_format($latest_opportunity->quotation_sent_on),$html);		
		}   	
		$result['html'] = $html;
        echo json_encode($result);
        exit(0); 
	}

	function web_whatsapp_sent_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$followup_date = $this->input->post('followup_date');
			$is_history_update = $this->input->post('is_history_update');
			$mobile_whatsapp_status = $this->input->post('mobile_whatsapp_status');
			$whatsapp_number = $this->input->post('whatsapp_number');
			$lead_id = $this->input->post('lead_id');
    		$cust_id = $this->input->post('cust_id');
    		$whatsapp_txt = $this->input->post('whatsapp_txt');		
			$lead_data=$this->lead_model->GetLeadData($lead_id);
			$followup_date_tmp='';
			if($followup_date){
				$followup_date_tmp=datetime_display_format_to_db_format_ampm($followup_date);
			}
			
	        if($lead_id!='' && $whatsapp_txt!='' && $is_history_update=='Y')
			{	
				$lead_title=get_value("title","lead","id=".$lead_id);				

	        	// ----------------------
	        	// CREATE LEAD HISTORY
				$attach_filename='';
			    $update_by=$this->session->userdata['admin_session_data']['user_id'];
				$date=date("Y-m-d H:i:s");				
				$ip_addr=$_SERVER['REMOTE_ADDR'];	
				if($mobile_whatsapp_status=='2')
				{
					$message= "<b>Comment:</b> $whatsapp_number is not a Whatsapp Number.";
				}	
				else
				{
					$keyword='%0A';
					$whatsapp_txt =str_replace($keyword,'',$whatsapp_txt); 
					$message="<b>Whastapp Comment:</b> ".addslashes($whatsapp_txt);
				}		

				
				$comment_title=LEAD_UPDATE_MANUAL;
				if($followup_date!='')
				{
					// $followup_date_tmp=datetime_display_format_to_db_format_ampm($followup_date);
					$historydata=array(
								'title'=>$comment_title,
								'lead_id'=>$lead_id,
								'comment'=>$message,
								'attach_file'=>$attach_filename,
								'create_date'=>$date,
								'communication_type_id'=>4,
								'communication_type'=>'Whatsapp Update',
								'next_followup_date'=>$followup_date_tmp,
								'user_id'=>$update_by,
								'ip_address'=>$ip_addr
							);
				}
				else
				{
					$historydata=array(
								'title'=>$comment_title,
								'lead_id'=>$lead_id,
								'comment'=>$message,
								'attach_file'=>$attach_filename,
								'create_date'=>$date,
								'communication_type_id'=>4,
								'communication_type'=>'Whatsapp Update',
								'user_id'=>$update_by,
								'ip_address'=>$ip_addr
							);
				}

				
				$this->history_model->CreateHistory($historydata);	
				// CREATE LEAD HISTORY
				// ----------------------				        				
			}

			if($lead_id!='' && $followup_date!='')
			{				
				$existing_is_followup_date_changed=get_value("is_followup_date_changed","lead","id=".$lead_id);
				if($existing_is_followup_date_changed=='N')
				{					
					$existing_followup_date=get_value("followup_date","lead","id=".$lead_id);
					if($existing_followup_date!=$followup_date_tmp)
					{
						$is_followup_date_changed='Y';
					}
					else
					{
						$is_followup_date_changed='N';
					}

					$update_lead=array(
										'is_followup_date_changed'=>$is_followup_date_changed,
										'modify_date'=>date("Y-m-d")
									);
					$this->lead_model->UpdateLead($update_lead,$lead_id);
				}

				
				
			}
			if($mobile_whatsapp_status>0)
			{
				$update_data=array(
						'mobile_whatsapp_status'=>$mobile_whatsapp_status,
						'modify_date'=>date("Y-m-d H:i:s")
								);
				$this->customer_model->UpdateCustomer($update_data,$cust_id);
			}

			$update_lead=array(
				'followup_date'=>$followup_date_tmp,
				'modify_date'=>date("Y-m-d")
			);
			$this->lead_model->UpdateLead($update_lead,$lead_id);

			$status_str='success';
	        $result["status"] = $status_str;
	        $result['recipient_mobile']='+'.$lead_data->cus_country_code.$lead_data->cus_mobile;
	        // $whatsapp_txt=str_replace('%0A','5',$whatsapp_txt);

	        $result["lead_id"] = $lead_id;
	        $result["cust_id"] = $cust_id;
	        $result["whatsapp_txt"] = $whatsapp_txt;
	        echo json_encode($result);
	        exit(0);
		}
	}

	public function save_web_whatsapp_template()
	{
		$list=array();
		$t_title = $this->input->post('t_title');
    	$t_desc = $this->input->post('t_desc');  	
    	$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$postData=array('name'=>$t_title,'description'=>str_replace("<BR>","%0A",$t_desc),'user_id'=>$user_id);
    	$id=$this->lead_model->add_web_whatsapp_template($postData);
    	
		$result['status'] = 'success';
		$result['id'] = $id;
        echo json_encode($result);
        exit(0); 
	}

	public function delete_web_whatsapp_template()
	{
		$id = $this->input->post('id');
		$this->lead_model->delete_web_whatsapp_template($id);
		$result['status'] = 'success';		
        echo json_encode($result);
        exit(0); 
	}

	function rander_regret_lead_view_popup_ajax()
    {        
    	$data=array();
        $id = $this->input->post('lid');
        $thread_id = $this->input->post('thread_id');
        $is_linked_view = $this->input->post('is_linked_view');
        $data['is_linked_view']=$is_linked_view;
        $filter_by_stage = '';
        $data['lead_data']=$this->lead_model->GetLeadData($id);	
        $cust_id=$data['lead_data']->cus_id;
        $data['all_ids']=$this->lead_model->get_all_lead_id_from_customers($cust_id,$filter_by_stage);
        $data['id']=$id;
        // $data['next_id']=$this->lead_model->get_lead_id_from_customers($cust_id,$filter_by_stage,$id,'next');
        // $data['previous_id']=$this->lead_model->get_lead_id_from_customers($cust_id,$filter_by_stage,$id,'previous');
        $data['filter_by_stage']=$filter_by_stage;
        $data['next_follow_by']=$this->lead_model->get_next_follow_by();        // user list
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
        $data['last_mail']=$this->lead_model->get_last_gmail_message_by_thread_id($thread_id);
        $data['user_list']=$this->user_model->GetUserList($user_ids_str);
        $data['regret_reason_list']=get_regret_reason();
        echo $this->load->view('admin/lead/rander_regret_lead_view_popup_ajax',$data,true);
    }

    function rander_po_upload_view_popup_ajax()
    {        
    	$data=array();
    	$step = ($this->input->post('step'))?$this->input->post('step'):'';
    	$lowp = ($this->input->post('lowp'))?$this->input->post('lowp'):'';

        $id = $this->input->post('lid');
        $l_opp_id = $this->input->post('l_opp_id');
        $data['is_back_show'] = $this->input->post('is_back_show');

        $data['lead_data']=$this->lead_model->GetLeadData($id);	
        $cust_id=$data['lead_data']->cus_id;        
        $data['id']=$id; 
        $data['opp_id']=$l_opp_id;
        $data['opportunity_data']=$this->opportunity_model->GetOpportunity($l_opp_id);
        $data['product_list']=$this->opportunity_model->get_opportunity_product($l_opp_id);
         $data['additional_charges_list']=$this->opportunity_model->get_additional_charges($l_opp_id);

        $opportunity_list=$this->opportunity_model->GetSentToClientStatusOpportunityListAll($id);
        if(count($opportunity_list)>1)
        {
        	$data['is_multiple_quotation']='Y';
        }
        else
        {
        	$data['is_multiple_quotation']='N';
        }
        
        $data['currency_list']=$this->lead_model->GetCurrencyList();
        $data['company']=get_company_profile();
        $data['lowp']=$lowp;
        $data['step']=$step;
        

        if($lowp>0)
        {
        	$po_register_info=$this->order_model->get_po_register_detail($lowp);
        	//print_r($po_register_info); die();
        	$chkPoRelatedRecord=$this->order_model->chkPoRelatedRecord($lowp);
        	if($chkPoRelatedRecord['is_pro_forma_inv_exist']=='N' || $chkPoRelatedRecord['is_inv_exist']=='N')
        	{
        		$lead_opp_id=$po_register_info->lead_opportunity_id;
        		$company=$data['company'];

        		$quotation_id=get_value("id","quotation","opportunity_id=".$lead_opp_id);
				$quotation_data=$this->quotation_model->GetQuotationData($quotation_id);

				$q_product_list=$this->quotation_model->GetQuotationProductList($quotation_id);
				$q_additional_charges_list=$this->opportunity_model->get_selected_additional_charges($lead_opp_id);

				$address_str='';
				$address_str .='<b>'.$company['name'].'</b>';
				$address_str .='<br>';
				$address_str .=$company['address'];			
				if(trim($company['address']) && trim($company['city_name'])){$address_str .=', ';}
				if(trim($company['city_name'])){$address_str .= $company['city_name'];}
				if(trim($company['city_name']) && trim($company['state_name'])){$address_str .= ', ';}
				if(trim($company['state_name'])){$address_str .=$company['state_name'].' - '.$company['pin'];}
				if(trim($company['state_name']) && trim($company['country_name'])){$address_str .= ', ';}
				if(trim($company['country_name'])){$address_str .=$company['country_name'];}
				if(trim($company['gst_number'])){$address_str .='<br><b>GST</b>: '.$company['gst_number'];}


				$bill_to =$quotation_data['quotation']['letter_to'];
				//if(trim($quotation_data['customer_log']['gst_number'])){$bill_to .='<br><b>GST</b>: '.$quotation_data['customer_log']['gst_number'];}

				$ship_to =$quotation_data['quotation']['letter_to'];
				//if(trim($quotation_data['customer_log']['gst_number'])){$ship_to .='<br><b>GST</b>: '.$quotation_data['customer_log']['gst_number'];}

				$name_of_authorised_signature=$company['authorized_signatory'];
				$thanks_and_regards=$quotation_data['quotation']['letter_thanks_and_regards'];
				// -----------------
				$term_str='';
				if(count($quotation_data['terms_log_only_display_in_quotation']))
				{					
					foreach($quotation_data['terms_log_only_display_in_quotation'] AS $term)
					{
						$term_str .='<b>'.$term['name'].'</b> : '.$term['value'].'<br>';
					}
				}

				// ----------------
				if($chkPoRelatedRecord['is_pro_forma_inv_exist']=='N')
	        	{
					$pro_inv_data=array(
					'lead_opportunity_wise_po_id'=>$lowp,
					'bill_from'=>$address_str,
					'bill_to'=>$bill_to,
					'ship_to'=>$ship_to,
					'bank_detail_1'=>$company['quotation_bank_details1'],
					'bank_detail_2'=>$company['quotation_bank_details2'],
					'terms_conditions'=>$term_str,
					'additional_note'=>$quotation_data['quotation']['letter_terms_and_conditions'],
					'name_of_authorised_signature'=>$name_of_authorised_signature,
					'thanks_and_regards'=>$thanks_and_regards,
					'currency_type'=>$quotation_data['quotation']['currency_type'],
					'created_at'=>date("Y-m-d H:i:s"),
					'updated_at'=>date("Y-m-d H:i:s")
					);
					$pro_forma_inv_id=$this->order_model->CreatePoProFormaInvoice($pro_inv_data);
					if($pro_forma_inv_id)
					{

						if(count($q_product_list))
						{
							foreach($q_product_list AS $p)
							{
								$product_name_tmp=$p->product_name;
								// if($p->hsn_code)
								// {
								// 	$product_name_tmp .='<br><b>HSN Code:</b> '.$p->hsn_code;
								// }
								// if($p->description)
								// {
								// 	$product_name_tmp .='<br>'.$p->description;
								// }
								// --------------
								// pro forma inv product
								$p_data=array(
									'po_pro_forma_invoice_id'=>$pro_forma_inv_id,
									'product_id'=>$p->product_id,
									'product_name'=>$product_name_tmp,
									'product_sku'=>$p->product_sku,
									'unit'=>$p->unit,
									'unit_type'=>$p->unit_type,
									'quantity'=>$p->quantity,
									'price'=>$p->price,
									'discount'=>$p->discount,
									'is_discount_p_or_a'=>$p->is_discount_p_or_a,
									'gst'=>$p->gst,
									'created_at'=>date('Y-m-d H:i:s'),
									'sort'=>$p->sort
									);
								$this->order_model->CreatePoProFormaInvoiceProduct($p_data);
								
							}
						}

						if(count($q_additional_charges_list))
						{
							
							foreach($q_additional_charges_list AS $ac)
							{
								// --------------
								// pro forma invoice
								$p_data=array(
									'po_pro_forma_invoice_id'=>$pro_forma_inv_id,
									'additional_charge_id'=>$ac->additional_charge_id,
									'additional_charge_name'=>$ac->additional_charge_name,
									'price'=>$ac->price,
									'discount'=>$ac->discount,
									'is_discount_p_or_a'=>$ac->is_discount_p_or_a,
									'gst'=>$ac->gst,
									'created_at'=>date('Y-m-d H:i:s'),
									'sort'=>$ac->sort
									);
								$this->order_model->CreatePoProFormaInvoiceAdditionalCharges($p_data);
								
							}	
						}
					}
	        	}

	        	if($chkPoRelatedRecord['is_inv_exist']=='N')
	        	{
	        		$inv_data=array(
					'lead_opportunity_wise_po_id'=>$lowp,
					'bill_from'=>$address_str,
					'bill_to'=>$bill_to,
					'ship_to'=>$ship_to,
					'bank_detail_1'=>$company['quotation_bank_details1'],
					'bank_detail_2'=>$company['quotation_bank_details2'],
					'terms_conditions'=>$term_str,
					'additional_note'=>$quotation_data['quotation']['letter_terms_and_conditions'],
					'name_of_authorised_signature'=>$name_of_authorised_signature,
					'thanks_and_regards'=>$thanks_and_regards,
					'currency_type'=>$quotation_data['quotation']['currency_type'],
					'created_at'=>date("Y-m-d H:i:s"),
					'updated_at'=>date("Y-m-d H:i:s")
					);
					$inv_id=$this->order_model->CreateInvoice($inv_data);
					if($inv_id)
					{
						if(count($q_product_list))
						{						
							foreach($q_product_list AS $p)
							{
								$product_name_tmp=$p->product_name;
								// if($p->hsn_code)
								// {
								// 	$product_name_tmp .='<br><b>HSN Code:</b> '.$p->hsn_code;
								// }
								// if($p->description)
								// {
								// 	$product_name_tmp .='<br>'.$p->description;
								// }
								// --------------
								// invoice product
								$p_data2=array(
									'po_invoice_id'=>$inv_id,
									'product_id'=>$p->product_id,
									'product_name'=>$product_name_tmp,
									'product_sku'=>$p->product_sku,
									'unit'=>$p->unit,
									'unit_type'=>$p->unit_type,
									'quantity'=>$p->quantity,
									'price'=>$p->price,
									'discount'=>$p->discount,
									'is_discount_p_or_a'=>$p->is_discount_p_or_a,
									'gst'=>$p->gst,
									'created_at'=>date('Y-m-d H:i:s'),
									'sort'=>$p->sort
									);
								$this->order_model->CreateInvoiceProduct($p_data2);
							}		
						}

						if(count($q_additional_charges_list))
						{
							foreach($q_additional_charges_list AS $ac)
							{
								// --------------
								// invoice product
								$p_data2=array(
									'po_invoice_id'=>$inv_id,
									'additional_charge_id'=>$ac->additional_charge_id,
									'additional_charge_name'=>$ac->additional_charge_name,
									'price'=>$ac->price,
									'discount'=>$ac->discount,
									'is_discount_p_or_a'=>$ac->is_discount_p_or_a,
									'gst'=>$ac->gst,
									'created_at'=>date('Y-m-d H:i:s'),
									'sort'=>$ac->sort
									);
								$this->order_model->CreateInvoiceAdditionalCharges($p_data2);
							}		
						}
					}
	        	}
        	}    

        	$last_sys_pro_no=get_invoice_no('P');
        	$last_sys_inv_no=get_invoice_no('I');
        	$data['system_proforma_no']=($last_sys_pro_no+1);
        	$data['system_invoice_no']=($last_sys_inv_no+1);
        	$data['po_pro_forma_inv_info']=$this->order_model->get_po_pro_forma_invoice_detail_lowpo_wise($lowp);
        	$data['po_pt_id']=$po_register_info->po_pt_id;
        	$data['prod_list']=$this->order_model->GetProFormaInvoiceProductList($lowp);
        	$data['additional_charges_list']=$this->order_model->GetProFormaInvoiceAdditionalCharges($lowp);


        	$data['po_inv_info']=$this->order_model->get_po_invoice_detail_lowpo_wise($lowp);  
        	$data['inv_prod_list']=$this->order_model->GetInvoiceProductList($lowp);
        	$data['inv_additional_charges_list']=$this->order_model->GetInvoiceAdditionalCharges($lowp);
        }
        else
        {
        	$po_register_info=array();
        	$data['system_proforma_no']='';
        	$data['system_invoice_no']='';
        	$data['po_pro_forma_inv_info']=array();
        	$data['po_pt_id']='';
        	$data['prod_list']=array();
        	$data['additional_charges_list']=array();

        	$data['po_inv_info']=array();
        	$data['inv_prod_list']=array();
        	$data['inv_additional_charges_list']=array();
        }


        // ----------------------------
        // CURRENT CUSTOMER ADDRESS
        $cus_bill_to_tmp='';
        $cus_bill_to_tmp .='<b>'.$data['lead_data']->cus_contact_person.'</b><br>';
        $cus_bill_to_tmp .=($data['lead_data']->cus_company_name)?'<b>'.$data['lead_data']->cus_company_name.'</b><br>':'';
        $cus_bill_to_tmp .=($data['lead_data']->cus_address)?$data['lead_data']->cus_address.'<br>':'';
        $cus_bill_to_tmp .=($data['lead_data']->cus_city)?$data['lead_data']->cus_city.'':'';
		$cus_bill_to_tmp .=($data['lead_data']->cus_zip)?' - '.$data['lead_data']->cus_zip.', ':'';
        $cus_bill_to_tmp .=($data['lead_data']->cus_state)?$data['lead_data']->cus_state.', ':'';
        $cus_bill_to_tmp .=($data['lead_data']->cus_country)?$data['lead_data']->cus_country.'<br>':'';		
        $cus_bill_to_tmp .=($data['lead_data']->cus_email)?'<b>Email:</b> '.$data['lead_data']->cus_email.'<br>':'';
        $cus_bill_to_tmp .=($data['lead_data']->cus_mobile)?'<b>Mobile:</b> +'.$data['lead_data']->cus_mobile_country_code.'-'.$data['lead_data']->cus_mobile.'<br>':'';
        $cus_bill_to_tmp .=($data['lead_data']->cus_gst_number)?'<b>GST:</b> '.$data['lead_data']->cus_gst_number.'<br>':'';
        $data['curr_customer_bill_to']=$cus_bill_to_tmp;
        // CURRENT CUSTOMER ADDRESS
        // ----------------------------
        
        $data['unit_type_list']=$this->Product_model->GetUnitList();
        $quotation_id=$data['opportunity_data']->q_id;
        $data['quotation_id']=$quotation_id;        
        $data['po_payment_method']=$this->order_model->po_payment_method_list();
        $data['po_register_info']=$po_register_info;
        echo $this->load->view('admin/lead/rander_po_upload_lead_view_popup_ajax',$data,true);
    }

    function rander_quotation_list_view_popup_ajax()
    {        
    	$data=array();
        $id = $this->input->post('lid');
        $data['is_back_show'] = $this->input->post('is_back_show');
        $data['id']=$id;
        $data['lead_data']=$this->lead_model->GetLeadData($id);	
        $data['opportunity_list']=$this->opportunity_model->GetSentToClientStatusOpportunityListAll($id);
        echo $this->load->view('admin/lead/rander_quotation_list_view_popup_ajax',$data,true);
    }


    function rander_reply_box_view_popup_ajax()
    {        
    	$data=array();
        $id = $this->input->post('id');
        $to_mail = $this->input->post('to_mail');
        $from_mail = $this->input->post('from_mail');
        $gmail_data=$this->lead_model->get_gmail_detail($id);

        $to_mail_arr=explode(',', $to_mail);
        if(count($to_mail_arr)>1)
        {
        	$to_mail_tmp=$to_mail_arr[0];        	
			$cc_mail_tmp = array_diff($to_mail_arr, array($to_mail_tmp));
			$cc_mail =implode(",", $cc_mail_tmp);

        }
        else
        {
        	$to_mail_tmp=$to_mail;
        	$cc_mail ="";
        }
        $data['gmail_data']=$gmail_data;
        $lead_id=$gmail_data['lead_id'];
        $data['lead_id']=$lead_id;
        $data['to_mail']=$to_mail_tmp;
        $data['cc_mail']=$cc_mail;
        $data['from_mail']=$from_mail;
        $data['subject_mail']=$gmail_data['subject'];
        $data['thread_id']=$gmail_data['thread_id'];
        $data['body_html']=$gmail_data['body_html'];
        $data['lead_data']=$this->lead_model->GetLeadData($lead_id);	
        $data['opportunity_list']=$this->opportunity_model->GetSentToClientStatusOpportunityListAll($lead_id);
        echo $this->load->view('admin/lead/rander_reply_box_view_popup_ajax',$data,true);
    }
    public function gmail_send_mail($reply_mail_to,$reply_mail_to_cc,$reply_thread_id,$reply_mail_subject,$reply_email_body)
	{	
		$conn=new Gmail_api_connection();
		require_once APPPATH.'libraries/Gmail_api.php';
		$gmailApiObj=new Gmail_api($conn->get_client());       
        $gmail_data=$gmailApiObj->sendMessage($reply_mail_to,$reply_mail_to_cc,$reply_thread_id,$reply_mail_subject,$reply_email_body);
        return $gmail_data;
        /*
		$conn=new Gmail_api_connection();
		if($conn->is_connected())
		{ 	
			// die("connected");
			// require_once("gmail.php");
			require_once APPPATH.'libraries/Gmail_api.php';
			$gmailApiObj=new Gmail_api($conn->get_client());
			// $gmail_data=$gmailApiObj->readLabels();
			$gmail_data=$gmailApiObj->sendMessage($reply_mail_to,$reply_mail_from,$reply_mail_subject,$reply_email_body);
			//print_r($gmail_data);exit;
		}	
		else
		{
			echo'Not sent';
		}  
	    exit;
	    */
	}
	

	function rander_compose_mail_box_view_popup_ajax()
    {        
    	$data=array();        
        echo $this->load->view('admin/lead/rander_compose_mail_box_view_popup_ajax',$data,true);
    }

	public function gmail_profile()
	{	
		

		 $conn=new Gmail_api_connection();
       //if($conn->is_connected())
        { 	
        	// die("connected");
            // require_once("gmail.php");
            require_once APPPATH.'libraries/Gmail_api.php';             
           
			$gmailApiObj=new Gmail_api($conn->get_client());
            // $gmail_data=$gmailApiObj->readLabels();
           $gmail_data=$gmailApiObj->getProfile('me');

			print_r($gmail_data);exit;
		}	
	    /* else
	        {
	        	echo 'Not sent';
	        }  */
	    exit;
	}

	public function gmail_compose_mail()
	{	
		$msg='';
		$mail_to=$this->input->post('mail_to');
		$mail_to_cc=$this->input->post('mail_to_cc');	
		$mail_subject=$this->input->post('mail_subject');
		$compose_email_body=$this->input->post('compose_email_body');
		//$msg=$mail_to.' / '.$mail_to_cc.' / '.$mail_subject.' / '.$compose_email_body;
		$conn=new Gmail_api_connection();
        if($conn->is_connected())
        { 	

        	// $msg=$this->gmail_send_mail($reply_mail_to,$reply_mail_to_cc,$reply_thread_id,$reply_mail_subject,$reply_email_body);
        	require_once APPPATH.'libraries/Gmail_api.php';
        	$arg=array();
        	$arg['mail_to']=$mail_to; 
        	$arg['mail_to_cc']=$mail_to_cc; 
        	$arg['mail_subject']=$mail_subject; 
        	$arg['mail_body']=$compose_email_body; 
        	$gmailApiObj=new Gmail_api($conn->get_client());       
        	$gmail_data=$gmailApiObj->composeMessage($arg);
        	$status='success';
        	$msg=$gmail_data;
        } 
        else
        {
        	$status='not_connected';
        } 
        $data =array (
			        "msg"=>$msg,
			        "status"=>$status,
			        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit; 
	    /*
		$conn=new Gmail_api_connection();
		require_once APPPATH.'libraries/Gmail_api.php';
		$gmailApiObj=new Gmail_api($conn->get_client());       
        $gmail_data=$gmailApiObj->composeMessage();
        return $gmail_data;
        */

        
        
	}

	public function gmail_compose()
	{
		
				

		$conn=new Gmail_api_connection();
        if($conn->is_connected())
        { 	
        	$msg=$this->gmail_send_mail($reply_mail_to,$reply_mail_to_cc,$reply_thread_id,$reply_mail_subject,$reply_email_body); 
        }    

        $status='success';
        $data =array (
			        "msg"=>$msg,
			        "status"=>$status,
			        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit;    
        

	}

	public function get_country_code_ajax()
	{	
		$cid=$this->input->post('cid');
		$country_code=get_value("phonecode","countries","id=".$cid);
    	$result['country_code'] = $country_code;
        echo json_encode($result);
        exit(0); 
	}

	function rander_cust_reply_box_view_popup_ajax()
    {        
    	$data=array();
    	$session_data = $this->session->userdata('admin_session_data');
    	$user_id = $session_data['user_id'];
        $lead_id = $this->input->post('lead_id');        
        $data['lead_data']=$this->lead_model->GetLeadData($lead_id);
        $data['company']=get_company_profile();	

        $data['quick_reply_comments']=$this->pre_define_comment_model->GetLeadUpdatePreDefineComments($user_id,'LU');
		$data['quick_reply_count']=count($data['quick_reply_comments']);


        echo $this->load->view('admin/lead/rander_cust_reply_box_view_popup_ajax',$data,true);
    }

    public function cust_reply_sent_ajax()
	{
		$session_data=$this->session->userdata('admin_session_data');
		$communication_type_id=2;
		$communication_type=get_value("title","communication_master","id=".$communication_type_id);
		$lead_id=$this->input->post('lead_id');
		$reply_mail_to=$this->input->post('reply_mail_to');	
		$mail_to_client_mail_subject=$this->input->post('reply_mail_subject');
		$reply_email_body=$this->input->post('reply_email_body');
		$is_company_brochure_attached_in_reply=$this->input->post('is_company_brochure_attached_in_reply');

		$company=get_company_profile();	
		$lead_info=$this->lead_model->GetLeadData($lead_id);
		$customer=$this->customer_model->GetCustomerData($lead_info->customer_id);
		$assigned_user_id=$lead_info->assigned_user_id;
		$user_data=$this->user_model->get_employee_details($assigned_user_id);


		$attach_filename=[];
		$attach_filename_with_path=array();
		$this->load->library('upload', '');
		if($_FILES['lead_attach_file']['tmp_name'])
		{
			$dataInfo = array();
			$files = $_FILES;
		    $cpt = count($_FILES['lead_attach_file']['name']);
			
		    for($i=0; $i<$cpt; $i++)
		    {
		    	//if(!in_array($i, $removed_attach_file_arr))
		    	//{
					$new_name = time().'-'.$files['lead_attach_file']['name'][$i];
					$config = array(
					'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/lead/",
					'allowed_types' => "gif|jpg|jpeg|png|pdf|doc|docx|csv|xlsx|xls",                        
					'max_size' => "2048000",
					// 'overwrite'=>FALSE,
					'file_name'=>$new_name,
					);
					$this->upload->initialize($config);

		    		$_FILES['lead_attach_file']['name']= $files['lead_attach_file']['name'][$i];
		            $_FILES['lead_attach_file']['type']= $files['lead_attach_file']['type'][$i];
		            $_FILES['lead_attach_file']['tmp_name']= $files['lead_attach_file']['tmp_name'][$i];
		            $_FILES['lead_attach_file']['error']= $files['lead_attach_file']['error'][$i];
		            $_FILES['lead_attach_file']['size']= $files['lead_attach_file']['size'][$i];
		        	

		        	if (!$this->upload->do_upload('lead_attach_file'))
		        	{
		        		//$this->upload->display_errors();
		        	}
		            else
		            {
		            	$dataInfo = $this->upload->data();
		                $filename=$dataInfo['file_name']; //Image Name
		                $attach_filename[]=$filename;
		                
		                $attach_filename_with_path[]="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/lead/".$filename;
		                
		            }
		    	//}            	
		    }
		}

		// ------------------------------
		// company brochure attachment
		if($is_company_brochure_attached_in_reply=='Y')
		{
        	if(isset($company['brochure_file']))
        	{
        		$company_brochure="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/brochure/".$company['brochure_file'];
        		//array_push($mail_attached_arr, $company_brochure);
        		$attach_filename_with_path[]=$company_brochure;
        	}
		}
		// company brochure attachment
		// ------------------------------
		
		$m_email=get_manager_and_skip_manager_email_arr($assigned_user_id);
		$reply_mail_to_cc=$this->input->post('reply_mail_to_cc');
		$reply_mail_to_cc_arr=array();
		if($reply_mail_to_cc)
		{
			$reply_mail_to_cc_arr=explode(",", $reply_mail_to_cc);
		}
		$cc_mail_arr=$reply_mail_to_cc_arr;
        $self_cc_mail=get_value("email","user","id=".$assigned_user_id);
        
        // --------------------
        // cc mail assign logic
        // array_push($cc_mail_arr, $self_cc_mail);
	    if($m_email['manager_email']!='')
    	{		        		
    		// array_push($cc_mail_arr, $m_email['manager_email']);
    	}

	    if($m_email['skip_manager_email']!='')
    	{		        		
    		// array_push($cc_mail_arr, $m_email['skip_manager_email']);
    	}
        $cc_mail='';
        $cc_mail=implode(",", $cc_mail_arr);
        // cc mail assign logic
        // --------------------

		// EMAIL CONTENT
		$e_data=array();		
		$e_data['company']=$company;
		$e_data['assigned_to_user']=$user_data;
		$e_data['customer']=$customer;
		$e_data['lead_info']=$lead_info;
		$e_data['updated_comments']=addslashes($reply_email_body);
		$template_str = $this->load->view('admin/email_template/template/update_reply_to_customers_view', $e_data, true);			    

		$to_mail='';
		//$to_mail=$user_data['email'];                	
		$to_mail=$customer->email;
		// LEAD ASSIGNED MAIL	    
		//$this->load->library('mail_lib');
		$mail_data = array();
		// $mail_data['from_mail']     = $admin_session_data_user_data['email'];
		// $mail_data['from_name']     = $admin_session_data_user_data['name'];
		$mail_data['from_mail']     = $session_data['email'];
		$mail_data['from_name']     = $session_data['name'];
		$mail_data['to_mail']       = $to_mail;        
		$mail_data['cc_mail']       = $cc_mail;               
		$mail_data['subject']       = $mail_to_client_mail_subject;
		$mail_data['message']       = $template_str;		
		if(count($attach_filename_with_path)>0)
		{
			$mail_data['attach'] = $attach_filename_with_path;
		}
		else
		{
			$mail_data['attach'] = array();
		}		
		$this->load->library('mail_lib');
		$this->mail_lib->send_mail($mail_data);

		$mail_to_client_from_mail=$session_data['email'];
		$mail_to_client_from_name=$session_data['name'];
		// MAIL SEND
		// ===============================================
		//-------------- HISTORY ----------------------------------
		$history_text = '';
		$update_by=$this->session->userdata['admin_session_data']['user_id'];
		$ip_addr=$_SERVER['REMOTE_ADDR'];		
		$comment_title=LEAD_GENERAL_UPDATE;
		$history_text .= addslashes($reply_email_body);
		// --------------------------------------------------------

		//-------------- HISTORY ----------------------------------		
		$historydata=array(
						'title'=>$comment_title,
						'lead_id'=>$lead_id,
						'comment'=>$history_text,
						'mail_trail_html'=>'',
						'mail_trail_ids'=>'',
						'cc_to_employee'=>$cc_mail,
						'mail_to_client'=>'Y',
						'mail_to_client_from_mail'=>$mail_to_client_from_mail,
						'mail_to_client_from_name'=>$mail_to_client_from_name,						
						'regret_mail_from_mail'=>'',
						'regret_mail_from_name'=>'',
						'mail_subject_of_update_lead_mail_to_client'=>$mail_to_client_mail_subject,
						'mail_subject_of_update_lead_regret_this_lead'=>'',
						'attach_file'=>implode("|$|",$attach_filename),
						'communication_type_id'=>$communication_type_id,
						'communication_type'=>$communication_type,
						'next_followup_date'=>$lead_info->followup_date,
						'create_date'=>date("Y-m-d H:i:s"),
						'user_id'=>$update_by,
						'ip_address'=>$ip_addr
						);
		$this->history_model->CreateHistory($historydata);	
		// ----------------------------------------------------------	
        $status='success';
        $data =array (
			        "msg"=>'',
			        "status"=>$status,
			        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit;  
	}

	function rander_lead_update_view_popup_ajax()
    {    

        $list=array();
		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		$list['user_id']=$user_id;
		$leadid=$this->input->post('lid');		
        $list['user_list']=$this->user_model->GetUserListAll('');
		$list['lead_id']=$leadid;		
		$list['lead_data']=$this->lead_model->GetLeadData($leadid);
		$list['opportunity_list']=$this->opportunity_model->GetSentToClientStatusOpportunityListAll($leadid);
		$list['next_follow_by']=$this->lead_model->get_next_follow_by(); 

		
		$list['quick_reply_comments']=$this->pre_define_comment_model->GetLeadUpdatePreDefineComments($user_id,'LU');
		$list['quick_reply_count']=count($list['quick_reply_comments']);

		echo $this->load->view('admin/lead/rander_lead_update_lead_view_popup_ajax',$list,true);
    }

    function rander_deal_lost_lead_view_popup_ajax()
    {        
    	/*
    	$data=array();
        $id = $this->input->post('lid');
        $thread_id = $this->input->post('thread_id');
        $is_linked_view = $this->input->post('is_linked_view');
        $data['is_linked_view']=$is_linked_view;
        $filter_by_stage = '';
        $data['lead_data']=$this->lead_model->GetLeadData($id);	
        $cust_id=$data['lead_data']->cus_id;
        $data['all_ids']=$this->lead_model->get_all_lead_id_from_customers($cust_id,$filter_by_stage);
        $data['id']=$id;
        // $data['next_id']=$this->lead_model->get_lead_id_from_customers($cust_id,$filter_by_stage,$id,'next');
        // $data['previous_id']=$this->lead_model->get_lead_id_from_customers($cust_id,$filter_by_stage,$id,'previous');
        $data['filter_by_stage']=$filter_by_stage;
        $data['next_follow_by']=$this->lead_model->get_next_follow_by();        // user list
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
        $data['last_mail']=$this->lead_model->get_last_gmail_message_by_thread_id($thread_id);
        $data['user_list']=$this->user_model->GetUserList($user_ids_str);
        $data['regret_reason_list']=get_regret_reason();
        */
        $list=array();
		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		$list['user_id']=$user_id;
		$leadid=$this->input->post('lid');
		//$list['cus_data']=$this->lead_model->GetLeadData($leadid);
        //$list['communication_list']=$this->lead_model->GetCommunicationList();
        $list['user_list']=$this->user_model->GetUserListAll('');
		$list['lead_id']=$leadid;		
		$list['lead_data']=$this->lead_model->GetLeadData($leadid);
		$list['regret_reason_list']=get_regret_reason();
		$list['opportunity_list']=$this->opportunity_model->GetSentToClientStatusOpportunityListAll($leadid);
		$list['next_follow_by']=$this->lead_model->get_next_follow_by();
        echo $this->load->view('admin/lead/rander_deal_lost_lead_view_popup_ajax',$list,true);
    }

    // ==========================================================
    // PO UPLOAD WITHOUT QUOTATION
    function rander_po_upload_view_popup_without_quotation_ajax()
    {        
    	$data=array();
        $id = $this->input->post('lid');
        $l_opp_id = $this->input->post('l_opp_id');
        $data['is_back_show'] = $this->input->post('is_back_show');
        
        $data['lead_data']=$this->lead_model->GetLeadData($id);	
        $cust_id=$data['lead_data']->cus_id;        
        $data['id']=$id; 
        $data['opp_id']=$l_opp_id;       

        $opportunity_list=$this->opportunity_model->GetSentToClientStatusOpportunityListAll($id);
        if(count($opportunity_list)>1)
        {
        	$data['is_multiple_quotation']='Y';
        }
        else
        {
        	$data['is_multiple_quotation']='N';
        }       
        $data['currency_list']=$this->lead_model->GetCurrencyList();
        $data['company']=get_company_profile(); 
        echo $this->load->view('admin/lead/rander_po_upload_lead_view_popup_without_quotation_ajax',$data,true);
    }
    function po_upload_post_without_quotation_ajax()
    {
    	$redirect_uri_str='';
    	$owp_id='';
    	$lead_id = $this->input->post('po_lead_id');
    	$lead_opp_id=$this->create_quotation_and_get_opp_id($lead_id);
    	if($lead_id!='' && $lead_opp_id!='')
    	{
    		// -----------------------
	    	// RENEWAL    	
	    	$is_renewal_available=($this->input->post('is_renewal_available'))?'Y':'N';
	    	
	    	if($is_renewal_available=='Y')
	    	{
	    		$renewal_date=date_display_format_to_db_format($this->input->post('renewal_date'));
	    		$renewal_follow_up_date=date_display_format_to_db_format($this->input->post('renewal_follow_up_date'));
	    		$renewal_requirement = $this->input->post('renewal_requirement');
	    			
	    		$renewal_id = $this->input->post('renewal_id');
	    		$existing_renewal_detail_id = $this->input->post('renewal_detail_id');

	    		if($renewal_id=='')
	    		{
	    			$renewal_customer_id = $this->input->post('renewal_customer_id');

					$renewal_post_data=array(
						'customer_id'=>$renewal_customer_id,
						'created_at'=>date('Y-m-d H:i:s'),
						'Updated_at'=>date('Y-m-d H:i:s')
						);
		        	$renewal_id=$this->renewal_model->create($renewal_post_data);
	    		}

	    		if($renewal_id)
	    		{
	    			$renewal_detail_post_data=array(
						'renewal_id'=>$renewal_id,
						'next_follow_up_date'=>$renewal_follow_up_date,
						'renewal_date'=>$renewal_date,
						'description'=>$renewal_requirement,
						'created_at'=>date('Y-m-d H:i:s'),
						'Updated_at'=>date('Y-m-d H:i:s')
						);
		        	$renewal_detail_id=$this->renewal_model->createDetails($renewal_detail_post_data);

		        	$renewal_product_str=$this->renewal_model->get_product_str($existing_renewal_detail_id);   

		        	if($renewal_product_str!='' && $renewal_detail_id>0)
			    	{
			    		$renewal_product_arr=explode(",", $renewal_product_str);
			    		if(count($renewal_product_arr))
			    		{
			    			$p_tmp_arr=array();
			    			foreach($renewal_product_arr AS $p_str)
			    			{
			    				$p_arr=explode("#", $p_str);
			    				$p_id=$p_arr[0];
			    				$p_name=$p_arr[1];
			    				$p_price=$p_arr[2];
			    				array_push($p_tmp_arr,$p_id);

			    				$renewal_p_data=array(
											'renewal_detail_id'=>$renewal_detail_id,
											'product_id'=>$p_id,
											'product_name'=>$p_name,
											'price'=>$p_price
										);
						        $this->renewal_model->CreateRenewalWiseProductTag($renewal_p_data);
			    			}
			    		}
			    	}			    	
	    		}
	    		  		
	    	}
	    	// RENEWAL
	    	// -----------------------
    		
	        $file = $this->input->post('po_upload_file');
	        $mark_cc_mail_arr = $this->input->post('po_upload_cc_to_employee');
	        $sent_ack_to_client=($this->input->post('po_upload_sent_ack_to_client'))?'Y':'N';
			$is_po_tds_applicable=($this->input->post('is_po_tds_applicable'))?'Y':'N';
			if($is_po_tds_applicable=='Y'){
				$po_tds_percentage = $this->input->post('po_tds_percentage');
			}
			else{
				$po_tds_percentage ='';
			}

	        $describe_comments = $this->input->post('po_upload_describe_comments');
	        $po_number=$this->input->post('po_number');
	        $po_date=date_display_format_to_db_format($this->input->post('po_date'));
	        $deal_value_as_per_purchase_order = $this->input->post('deal_value_as_per_purchase_order');

	        $deal_value_currency_type = $this->input->post('currency_type');

	        // LEAD ATTACH FILE UPLOAD
			$attach_filename='';
			$this->load->library('upload', '');
			if($_FILES['po_upload_file']['name'] != "")
			{
				$config2['upload_path'] = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/";
				$config2['allowed_types'] = "gif|jpg|jpeg|png|pdf|doc|docx|txt|xlsx";
				$config2['max_size'] = '1000000'; //KB
				$config2['overwrite'] = FALSE; 
				$config2['encrypt_name'] = TRUE; 
				$this->upload->initialize($config2);
				if (!$this->upload->do_upload('po_upload_file'))
				{
				    //return $this->upload->display_errors();
				    $status_str='error';
			        $result['status']=$status_str;
			        $result['msg']=$this->upload->display_errors();
			        echo json_encode($result);
			        exit(0);
				}
				else
				{
				    $file_data = array('upload_data' => $this->upload->data());
				    $attach_filename = $file_data['upload_data']['file_name'];			    
				}
			}

			$quotation_id=get_value("id","quotation","opportunity_id=".$lead_opp_id);
			$quotation_data=$this->quotation_model->GetQuotationData($quotation_id);

			$updated_by=$this->session->userdata['admin_session_data']['user_id'];
			$company=get_company_profile();	
			$lead_info=$this->lead_model->GetLeadData($quotation_data['lead_opportunity_data']['lead_id']);
			
			$assigned_user_id=$lead_info->assigned_user_id;	
			$assigned_to_user=$this->user_model->get_employee_details($assigned_user_id);
			$quotation_info=$this->Opportunity_model->get_opportunity_details($lead_opp_id);
			$session_data=$this->session->userdata('admin_session_data');
			$this->load->library('mail_lib');
			
			// PO Recdeived Acknowledment 4
			$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(4);
			$m_email=get_manager_and_skip_manager_email_arr($updated_by);

	        if($sent_ack_to_client=='Y' && $email_forwarding_setting['is_mail_send']=='Y')
		    {	    	
				// ============================
				// Update Mail to client 
				// START
				// $cc_mail='';
				// $self_cc_mail=get_value("email","user","id=".$updated_by);
				// $cc_mail=get_manager_and_skip_manager_email($assigned_user_id);
				// $cc_mail=($cc_mail)?$cc_mail.','.$self_cc_mail:$self_cc_mail;

		        

				// EMAIL CONTENT
			    $e_data=array();		
				//$e_data['company']=$company;
				$e_data['assigned_to_user']=$assigned_to_user;
				//$e_data['customer']=$customer;
				$e_data['lead_info']=$lead_info;
				$e_data['quotation_info']=$quotation_info;
				$e_data['quotation']=$quotation_data['quotation'];
				$e_data['lead_opportunity']=$quotation_data['lead_opportunity_data'];
				$e_data['company']=$quotation_data['company_log'];
				$e_data['customer']=$quotation_data['customer_log'];
				$e_data['terms']=$quotation_data['terms_log_only_display_in_quotation'];
				$e_data['product_list']=$quotation_data['product_list'];
				$e_data['po_number']=$po_number;
				$e_data['po_date']=$po_date;
		        $template_str = $this->load->view('admin/email_template/template/po_received_acknowledment_view', $e_data, true);			    
		        //$to_mail='';
		        //$to_mail=$user_data['email'];                	
		        //$to_mail=$quotation_data['customer_log']['email'];
			    // LEAD ASSIGNED MAIL	    
		    	
		    	// --------------------
		        // to mail assign logic
		        $to_mail_assign='';
		        $to_mail='';
		        if($email_forwarding_setting['is_send_mail_to_client']=='Y')
		        {
		        	$to_mail=$quotation_data['customer_log']['email'];
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
		        $self_cc_mail=get_value("email","user","id=".$updated_by);
		        //$update_by_name=get_value("name","user","id=".$updated_by);
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
		        	$mail_data = array();
			        $mail_data['from_mail']     = $session_data['email'];
			        $mail_data['from_name']     = $session_data['name'];
			        $mail_data['to_mail']       = $to_mail;        
			        $mail_data['cc_mail']       = $cc_mail;               
			        $mail_data['subject']       ='Thank you for the Purchase Order No ['.$po_number.']';
			        $mail_data['message']       = $template_str;
			        $mail_data['attach']        = array();
			        $this->mail_lib->send_mail($mail_data);
		        }
		        
				// MAIL SEND
				// ===============================================
			}
			
			// PO received appreciation mail to employee 8
			$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(8);
			$m_email=get_manager_and_skip_manager_email_arr($updated_by);
	        //if(count($mark_cc_mail_arr))
	        if($email_forwarding_setting['is_mail_send']=='Y')
		    {
		    	$cc_mail_tmp=implode(",", $mark_cc_mail_arr);
		    	$cc_to_employee=$cc_mail_tmp;	    	
		    	//$to_mail=$cc_mail_tmp;
		    	$self_cc_mail=get_value("email","user","id=".$assigned_user_id);
		        $update_by_name=get_value("name","user","id=".$assigned_user_id);

		    	// --------------------
		        // to mail assign logic
		        $to_mail_assign='';
		        $to_mail='';
		        if($email_forwarding_setting['is_send_relationship_manager']=='Y')
		        {
		        	$to_mail=$self_cc_mail;
		        	$to_mail_assign='self';
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
		    	// ============================
				// When CC will be marked to employees at the time of lead update
				// START
				//$cc_mail='';
				// $self_mail=get_value("email","user","id=".$updated_by);			
				// $cc_mail=get_manager_and_skip_manager_email($assigned_user_id);
				// $cc_mail=($cc_mail)?$cc_mail.','.$cc_mail_tmp:$cc_mail_tmp;
				// $to_mail=$self_mail;	
				// EMAIL CONTENT	  

				$cc_mail_arr=array();	        
		        // --------------------
		        // cc mail assign logic
		        if($email_forwarding_setting['is_send_relationship_manager']=='Y')
		        {
		        	array_push($cc_mail_arr, $self_cc_mail);
		        }

		        if($email_forwarding_setting['is_send_manager']=='Y')
		        {
		        	if($m_email['manager_email']!='')
		        	{		        		
		        		array_push($cc_mail_arr, $m_email['manager_email']);
		        	}		        	
		        }

		        if($email_forwarding_setting['is_send_skip_manager']=='Y')
		        {
		        	if($m_email['skip_manager_email']!='')
		        	{		        		
		        		array_push($cc_mail_arr, $m_email['skip_manager_email']);
		        	}		        	
		        }
		        $cc_mail='';
		        $cc_mail=implode(",", $cc_mail_arr);
		        if($cc_mail_tmp)
		        {
		        	$cc_mail=$cc_mail.','.$cc_mail_tmp;
		        }
		        // cc mail assign logic
		        // --------------------      
			    
				$e_data=array();		
				//$e_data['company']=$company;
				$e_data['assigned_to_user']=$assigned_to_user;
				//$e_data['customer']=$customer;
				$e_data['lead_info']=$lead_info;
				$e_data['quotation_info']=$quotation_info;
				$e_data['quotation']=$quotation_data['quotation'];
				$e_data['lead_opportunity']=$quotation_data['lead_opportunity_data'];
				$e_data['company']=$quotation_data['company_log'];
				$e_data['customer']=$quotation_data['customer_log'];
				$e_data['terms']=$quotation_data['terms_log_only_display_in_quotation'];
				$e_data['product_list']=$quotation_data['product_list'];
				$e_data['po_number']=$po_number;
				$e_data['po_date']=$po_date;
		        $template_str = $this->load->view('admin/email_template/template/po_received_acknowledment_to_employee_view', $e_data, true); 
		        
		        if($to_mail)
		        {
		        	// LEAD ASSIGNED MAIL
			        $mail_data = array();
			        $mail_data['from_mail']     = $session_data['email'];
			        $mail_data['from_name']     = $session_data['name'];
			        $mail_data['to_mail']       = $to_mail;        
			        $mail_data['cc_mail']       = $cc_mail;               
			        $mail_data['subject']       ='Hurray!! '.$assigned_to_user['name'].' has received a PO';	        
			        $mail_data['message']       = $template_str;
			        $mail_data['attach']        = array();
			        $this->mail_lib->send_mail($mail_data);
		        }		    
				// MAIL SEND
				// ===============================================
		    }	    
			

			$post_data=array(
			'lead_opportunity_id'=>$lead_opp_id,
			'file_path'=>"assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/",
			'file_name'=>$attach_filename,
			'cc_to_employee'=>$cc_to_employee.','.$self_mail,
			'is_send_acknowledgement_to_client'=>$sent_ack_to_client,
			'comments'=>$describe_comments,
			'po_number'=>$po_number,
			'po_date'=>$po_date,
			'po_tds_percentage'=>$po_tds_percentage,
			'create_date'=>date("Y-m-d H:i:s")
			);
			$owp_id=$this->Opportunity_model->CreateOportunityWisePo($post_data);


			// UPDATE LEAD STAGE/STATUS
		    $update_lead_data = array(
		    	'current_stage_id' =>'4',
				'current_stage' =>'DEAL WON',
				'current_stage_wise_msg' =>'',
				'current_status_id'=>'2',
				'current_status'=>'HOT',
				'is_hotstar'=>'Y',
				'modify_date'=>date("Y-m-d")
			);								
			$this->lead_model->UpdateLead($update_lead_data,$lead_id);
			// Insert Stage Log

			//$is_nego_exist=$this->lead_model->is_stage_exist_in_log($lead_id,9);
			$is_nego_exist='Y';
			if($is_nego_exist=='N')
			{
				// STAGE NEGOTIATION
				$stage_post_data=array(
						'lead_id'=>$lead_id,
						'stage_id'=>'9',
						'stage'=>'NEGOTIATION',
						'stage_wise_msg'=>'',
						'create_datetime'=>date("Y-m-d H:i:s")
					);
				//$this->lead_model->CreateLeadStageLog($stage_post_data);
			}
			
			// STAGE DEAL WON
			$stage_post_data=array();
	        $stage_post_data=array(
					'lead_id'=>$lead_id,
					'stage_id'=>'4',
					'stage'=>'DEAL WON',
					'stage_wise_msg'=>'',
					'create_datetime'=>date("Y-m-d H:i:s")
				);
	        $this->lead_model->CreateLeadStageLog($stage_post_data);

	        // Insert Status Log
	        $status_post_data=array(
					'lead_id'=>$lead_id,
					'status_id'=>'2',
					'status'=>'HOT',
					'create_datetime'=>date("Y-m-d H:i:s")
				);
	        $this->lead_model->CreateLeadStatusLog($status_post_data);


	       	// LEAD OPPORTUNITY STATUS UPDATE
	       	if($quotation_data['quotation']['is_extermal_quote']=='Y')
	       	{
	       		$data_opportunity=array(
	       			'deal_value'=>$deal_value_as_per_purchase_order,
					'deal_value_as_per_purchase_order'=>$deal_value_as_per_purchase_order,
					'currency_type'=>$deal_value_currency_type,
					'status'=>4,
					'modify_date'=>date("Y-m-d H:i:s")
				);
	       	}
	       	else
	       	{
	       		$data_opportunity=array(
					'deal_value_as_per_purchase_order'=>$deal_value_as_per_purchase_order,
					'currency_type'=>$deal_value_currency_type,
					'status'=>4,
					'modify_date'=>date("Y-m-d H:i:s")
				);
	       	}
			
			$this->Opportunity_model->UpdateLeadOportunity($data_opportunity,$lead_opp_id);


			// history log
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$ip_addr=$_SERVER['REMOTE_ADDR'];		
			$comment_title=QUOTATION_WISE_PO_UPLOAD;
			$q_title=get_value("opportunity_title","lead_opportunity","id=".$lead_opp_id);
			
			$link ='';
			$comment='PO uploaded for quotation ('.$q_title.') '.$link;
			$historydata=array(
								'title'=>$comment_title,
								'lead_id'=>$lead_id,
								'comment'=>addslashes($comment),
								'attach_file'=>'',
								'communication_type'=>'',
								'next_followup_date'=>'',
								'create_date'=>date("Y-m-d H:i:s"),
								'user_id'=>$update_by,
								'ip_address'=>$ip_addr
								);
			$this->history_model->CreateHistory($historydata);

			// product tagged with quoted lead
			$prod_list=$this->lead_model->get_tagged_ps_list($lead_id,'L');
			if(count($prod_list))
			{
				foreach($prod_list AS $product)
				{
					$lead_p_data=array(
						'lead_id'=>$lead_id,
						'lead_opportunity_id'=>$lead_opp_id,
						'quotation_id'=>$quotation_id,
						'name'=>$product['name'],
						'product_id'=>$product['product_id'],
						'tag_type'=>'W',
						'created_at'=>date("Y-m-d H:i:s")
					);
					$this->lead_model->CreateLeadWiseProductTag($lead_p_data);
				}
			}
			// --------------------
			// ---------------------------------
			// update to pro-forma-invoice and invoice
			// $q_product_list=$this->quotation_model->GetQuotationProductList($quotation_id);
			// $q_additional_charges_list=$this->opportunity_model->get_selected_additional_charges($lead_opp_id);
			/*
			$address_str='';
			$address_str .=$company['name'];
			$address_str .='<br>';
			$address_str .=$company['address'];			
			if(trim($company['address']) && trim($company['city_name'])){$address_str .=', ';}
			if(trim($company['city_name'])){$address_str .= $company['city_name'];}
			if(trim($company['city_name']) && trim($company['state_name'])){$address_str .= ', ';}
			if(trim($company['state_name'])){$address_str .=$company['state_name'].' - '.$company['pin'];}
			if(trim($company['state_name']) && trim($company['country_name'])){$address_str .= ', ';}
			if(trim($company['country_name'])){$address_str .=$company['country_name'];}

			$pro_inv_data=array(
						'lead_opportunity_wise_po_id'=>$owp_id,
						'bill_from'=>$address_str,
						'bill_to'=>$quotation_data['quotation']['letter_to'],
						'terms_conditions'=>$quotation_data['quotation']['letter_terms_and_conditions'],
						'currency_type'=>$quotation_data['quotation']['currency_type'],
						'created_at'=>date("Y-m-d H:i:s"),
						'updated_at'=>date("Y-m-d H:i:s")
						);
			$pro_forma_inv_id=$this->order_model->CreatePoProFormaInvoice($pro_inv_data);

			$inv_data=array(
						'lead_opportunity_wise_po_id'=>$owp_id,
						'bill_from'=>$address_str,
						'bill_to'=>$quotation_data['quotation']['letter_to'],
						'terms_conditions'=>$quotation_data['quotation']['letter_terms_and_conditions'],
						'currency_type'=>$quotation_data['quotation']['currency_type'],
						'created_at'=>date("Y-m-d H:i:s"),
						'updated_at'=>date("Y-m-d H:i:s")
						);
			$inv_id=$this->order_model->CreateInvoice($inv_data);
			*/	
			// update to pro-forma-invoice and invoice
			// ----------------------------------
	        $status_str='success';
    	}
    	else
    	{
    		$status_str='fail';
    	}
    	
    	$redirect_uri_str='action=po_edit&lid='.$lead_id.'&l_opp_id='.$lead_opp_id.'&step=2&lowp='.$owp_id;

        $result['status']=$status_str;
        $result['lead_opportunity_wise_po_id']=$owp_id;
        $result['msg']='PO successfully uploaded.';
        $result['redirect_uri_str']=$redirect_uri_str;
        echo json_encode($result);
        exit(0);
    }
    public function create_quotation_and_get_opp_id($lead_id)
	{		
		$error_msg='';
		$success_msg='';
		$return_status='';
		//$lead_id=$this->input->post('lead_id');
		$lead_data=$this->lead_model->GetLeadData($lead_id);
		$opportunity_title=$lead_data->title;
		$deal_value='';
		$currency_type=1;				
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$status=1; // Pending
		if($lead_id)
		{
			$data_opportunity=array(
								'lead_id'=>$lead_id,
								'opportunity_title'=>$opportunity_title,
								'deal_value'=>$deal_value,
								'currency_type'=>$currency_type,
								'status'=>$status,
								'create_date'=>date("Y-m-d H:i:s"),
								'modify_date'=>date("Y-m-d H:i:s")
								);	
			$opportunity_id=$this->Opportunity_model->CreateLeadOportunity($data_opportunity);			
			$admin_session_data_user_data_tmp=$this->user_model->GetAdminData($user_id);
			$admin_session_data_user_data_tmp_arr=json_decode(json_encode($admin_session_data_user_data_tmp),true);
			if(count($admin_session_data_user_data_tmp_arr)){
				$admin_session_data_user_data=$admin_session_data_user_data_tmp[0];
			}
			else{
				$admin_session_data_user_data=array();
			}
			
			$data['admin_session_data_user_data']=$admin_session_data_user_data;
			$data['opportunity_id']=$opportunity_id;
			$opportunity_data=$this->Opportunity_model->GetOpportunityData($opportunity_id);
			$data['opportunity_data']=$opportunity_data;					
			$data['lead_data']=$lead_data;
			$customer_data=$this->customer_model->GetCustomerData($lead_data->customer_id);
			$data['customer_data']=$customer_data;
			$company_data=$this->Setting_model->GetCompanyData();
			$data['company_data']=$company_data;

			// QUOTE NO. LOGIC - START
			//$company_name_tmp = substr(strtoupper($company_data['name']),0,3);
			$words = explode(" ", $company_data['name']);
			$company_name_tmp = "";
			foreach ($words as $w) {
				$company_name_tmp .= strtoupper($w[0]);
			}
			$m_y_tmp=date("m-y");		
			$no_tmp=$opportunity_id;
			$quote_no=$company_name_tmp.'-000'.$no_tmp.'-'.$m_y_tmp;
			// QUOTE NO. LOGIC - END
			$file_name = '';
			$quote_valid_until=date('Y-m-d', strtotime("+30 days"));
			$letter_to="";
			if($customer_data->contact_person){
				$letter_to .='<b>'.$customer_data->contact_person.'</b>';
			}
			if($customer_data->company_name){
				$letter_to .='<br><b>'.$customer_data->company_name.'</b>';
			}
			if($customer_data->address){
				$letter_to .='<br>'.$customer_data->address;
			}
			if($customer_data->city_name!='' || $customer_data->state_name!='' || $customer_data->country_name!=''){
				$letter_to .="<br>";
				if($customer_data->city_name){
					$letter_to .=$customer_data->city_name;
				}
				if(trim($customer_data->city_name) && trim($customer_data->state_name)){
					$letter_to .=', ';
				}
				if($customer_data->state_name){
					$letter_to .=$customer_data->state_name;
				}
				if(trim($customer_data->state_name) && trim($customer_data->country_name)){
					$letter_to .=', ';
				}
				if($customer_data->country_name){
					$letter_to .=$customer_data->country_name;
				}
				
			}
			if($customer_data->email){
				$letter_to .='<br><b>Email: </b>'.$customer_data->email;
			}
			if($customer_data->mobile){
				$letter_to .='<br><b>Mobile: </b>';
				if($customer_data->mobile_country_code){
					$letter_to .='+'.$customer_data->mobile_country_code.'-';
				}
				$letter_to .=$customer_data->mobile;
			}

			$letter_subject=$opportunity_data->opportunity_title.' (Enquiry Dated: '.date_db_format_to_display_format($opportunity_data->create_date).')';
			$letter_body_text=$company_data['quotation_cover_letter_body_text'];
			$letter_footer_text=$company_data['quotation_cover_letter_footer_text'];
			$letter_terms_and_conditions=$company_data['quotation_terms_and_conditions'];		
			$letter_thanks_and_regards=$admin_session_data_user_data->name.'<br>Mobile:'.$admin_session_data_user_data->mobile.'<br>Email:'.$admin_session_data_user_data->email;
			
			// ==================================================
			// INSERT TO QUOTE TABLE
			$quotation_post_data=array(	
						'opportunity_id'=>$opportunity_id,
						'customer_id'=>$lead_data->customer_id,
						'quote_no'=>$quote_no,
						'quote_date'=>date("Y-m-d"),
						'quote_valid_until'=>$quote_valid_until,
						'is_extermal_quote'=>'Y',
						'file_name'=>$file_name,
						'currency_type'=>$opportunity_data->currency_type_code,
						'letter_to'=>$letter_to,
						'letter_subject'=>$letter_subject,
						'letter_body_text'=>$letter_body_text,
						'letter_footer_text'=>$letter_footer_text,
						'letter_terms_and_conditions'=>$letter_terms_and_conditions,
						'letter_thanks_and_regards'=>$letter_thanks_and_regards,
						'create_date'=>date("Y-m-d H:i:s"),
						'modify_date'=>date("Y-m-d H:i:s")
						);
			$quotation_id=$this->quotation_model->CreateQuotation($quotation_post_data);
			// INSERT TO QUOTE TABLE
			// ================================================

			// =======================================
			// INSERT TO CUSTOMER LOG TABLE

			$cust_log_post_data=array(	
						'quotation_id'=>$quotation_id,
						'first_name'=>$customer_data->first_name,
						'last_name'=>$customer_data->last_name,
						'contact_person'=>$customer_data->contact_person,
						'designation'=>$customer_data->designation,
						'email'=>$customer_data->email,
						'alt_email'=>$customer_data->alt_email,
						'mobile'=>$customer_data->mobile,
						'alt_mobile'=>$customer_data->alt_mobile,
						'office_phone'=>$customer_data->office_phone,
						'website'=>$customer_data->website,
						'company_name'=>$customer_data->company_name,
						'address'=>$customer_data->address,
						'city'=>$customer_data->city_name,
						'state'=>$customer_data->state_name,
						'country'=>$customer_data->country_name,
						'zip'=>$customer_data->zip,
						'gst_number'=>$customer_data->gst_number
						);
			$this->quotation_model->CreateQuotationTimeCustomerLog($cust_log_post_data);

			// INSERT TO CUSTOMER LOG TABLE
			// ===============================================

			// ====================================================
			// INSERT TO COMPANY INFORMATION LOG TABLE
				
			$company_info_log_post_data=array(	
						'quotation_id'=>$quotation_id,
						'logo'=>$company_data['logo'],
						'name'=>$company_data['name'],
						'address'=>$company_data['address'],
						'city'=>$company_data['city_name'],
						'state'=>$company_data['state_name'],
						'country'=>$company_data['country_name'],
						'pin'=>$company_data['pin'],
						'about_company'=>$company_data['about_company'],
						'gst_number'=>$company_data['gst_number'],
						'pan_number'=>$company_data['pan_number'],
						'ceo_name'=>$company_data['ceo_name'],
						'contact_person'=>$company_data['contact_person'],
						'email1'=>$company_data['email1'],
						'email2'=>$company_data['email2'],
						'mobile1'=>$company_data['mobile1'],
						'mobile2'=>$company_data['mobile2'],
						'phone1'=>$company_data['phone1'],
						'phone2'=>$company_data['phone2'],
						'website'=>$company_data['website'],
						'quotation_cover_letter_body_text'=>$company_data['quotation_cover_letter_body_text'],
						'quotation_terms_and_conditions'=>$company_data['quotation_terms_and_conditions'],
						'quotation_cover_letter_footer_text'=>$company_data['quotation_cover_letter_footer_text'],
						'bank_credit_to'=>$company_data['bank_credit_to'],
						'bank_name'=>$company_data['bank_name'],
						'bank_acount_number'=>$company_data['bank_acount_number'],
						'bank_branch_name'=>$company_data['bank_branch_name'],
						'bank_branch_code'=>$company_data['bank_branch_code'],
						'bank_ifsc_code'=>$company_data['bank_ifsc_code'],
						'bank_swift_number'=>$company_data['bank_swift_number'],
						'bank_telex'=>$company_data['bank_telex'],
						'bank_address'=>$company_data['bank_address'],
						'correspondent_bank_name'=>$company_data['correspondent_bank_name'],
						'correspondent_bank_swift_number'=>$company_data['correspondent_bank_swift_number'],
						'correspondent_account_number'=>$company_data['correspondent_account_number']
						);
			$this->quotation_model->CreateQuotationTimeCompanyInfoLog($company_info_log_post_data);

			// INSERT TO COMPANY INFORMATION LOG TABLE
			// ================================================

			// ================================================
			// INSERT TO TERMS AND CONDITIONS LOG TABLE
			if($opportunity_data->currency_type_code=='INR')
				$table_name='terms_and_conditions_domestic_quotation';
			else
				$table_name='terms_and_conditions_export_quotation';

			$terms_condition_list=$this->quotation_model->get_terms_and_conditions($table_name);
			if(count($terms_condition_list))
			{
				foreach($terms_condition_list as $term)
				{
					$term_log_post_data=array(	
											'quotation_id'=>$quotation_id,
											'name'=>$term->name,
											'value'=>$term->value
											);
					$this->quotation_model->CreateQuotationTimeTermsLog($term_log_post_data);
				}
			}
			

			// INSERT TO TERMS AND CONDITIONS LOG TABLE
			// ============================================

			// =================================================
			// Create History log
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$date=date("Y-m-d H:i:s");
			$ip_addr = $this->input->ip_address();
			$message="A new Custom Quotation PDF (".$quote_no.") has been created.";
			$comment_title=QUOTATION_PDF_CREATE;
			$historydata=array(
							'title'=>$comment_title,
							'lead_id'=>$lead_id,
							'lead_opportunity_id'=>$opportunity_id,
							'comment'=>addslashes($message),
							'create_date'=>$date,
							'user_id'=>$update_by,
							'ip_address'=>$ip_addr
							);
			//inserted_lead_comment_log($historydata);
			$this->history_model->CreateHistory($historydata);	
			// Create History log	
			// =================================================

			// =================================================
			// UPDATE LEAD STAGE/STATUS
		    $update_lead_data = array(
		    	'current_stage_id' =>'2',
				'current_stage' =>'QUOTED',
				'current_stage_wise_msg' =>'',
				// 'current_status_id'=>'2',
				// 'current_status'=>'HOT',
				'modify_date'=>date("Y-m-d")
			);								
			$this->lead_model->UpdateLead($update_lead_data,$lead_id);
			// Insert Stage Log

			$is_prospect_exist=$this->lead_model->is_stage_exist_in_log($lead_id,8);
			if($is_prospect_exist=='N')
			{
				// STAGE PROSPECT
				$stage_post_data=array(
						'lead_id'=>$lead_id,
						'stage_id'=>'8',
						'stage'=>'PROSPECT',
						'stage_wise_msg'=>'',
						'create_datetime'=>date("Y-m-d H:i:s")
					);
				$this->lead_model->CreateLeadStageLog($stage_post_data);	
			}
			

			// STAGE QUOTED
	        $stage_post_data=array(
					'lead_id'=>$lead_id,
					'stage_id'=>'2',
					'stage'=>'QUOTED',
					'stage_wise_msg'=>'',
					'create_datetime'=>date("Y-m-d H:i:s")
				);
	        $this->lead_model->CreateLeadStageLog($stage_post_data);
	        // Insert Status Log
	        $status_post_data=array(
					'lead_id'=>$lead_id,
					'status_id'=>'2',
					'status'=>'HOT',
					'create_datetime'=>date("Y-m-d H:i:s")
				);
	        // $this->lead_model->CreateLeadStatusLog($status_post_data);
			// UPDATE LEAD STAGE/STATUS
			// =================================================
			return $opportunity_id;
		}
		else
		{
			return '';
		}
	}
	// PO UPLOAD WITHOUT QUOTATION
	// ==========================================================
    
	// ==========================================================
	// LIKE AND DISLIKE (STAGE CHANGE)
	function rander_like_stage_view_ajax()
    {        
    	$data=array();
    	$lead_id=$this->input->post('lid');
    	// $data['lead_data']=$this->lead_model->GetLeadData($lead_id);
		$data['curr_stage_id']==get_value("current_stage_id","lead","id=".$lead_id);
    	$data['lead_id']=$lead_id;
    	$data['row']=$this->lead_model->get_lead_wise_stage_log($lead_id);
    	// Get Custom stages 
    	// --------------------------------------
    	$custom_lead_stage_str='';
    	$custom_lead_stage_info=get_custom_stage();
    	if($custom_lead_stage_info['id_str']){
    		$custom_lead_stage_str=','.$custom_lead_stage_info['id_str'];
    	}
    	// --------------------------------------    	
        $data['like_stage_list']=$this->opportunity_model->GetStageList('1,8,2,4'.$custom_lead_stage_str);
        echo $this->load->view('admin/lead/rander_like_stage_view_ajax',$data,true);
    }
    function rander_dislike_stage_view_ajax()
    {        
    	$data=array();
    	$lead_id=$this->input->post('lid');
        $session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		$data['user_id']=$user_id;
        // $data['user_list']=$this->user_model->GetUserListAll('');
		$data['lead_id']=$lead_id;		
		// $data['lead_data']=$this->lead_model->GetLeadData($lead_id);
		$data['regret_reason_list']=get_regret_reason();
		// $data['opportunity_list']=$this->opportunity_model->GetSentToClientStatusOpportunityListAll($lead_id);
        echo $this->load->view('admin/lead/rander_dislike_stage_view_ajax',$data,true);
    }

    public function update_lead_stage_to_lost_ajax()
	{ 
		$lead_id=$this->input->post('lead_id');
		$lead_regret_reason_id=$this->input->post('lead_regret_reason_id');
		$lead_regret_reason=$this->input->post('lead_regret_reason');

		if($lead_id!="" && $lead_regret_reason_id!="" && $lead_regret_reason!="")
		{
			$session_data = $this->session->userdata('admin_session_data');
			$user_id = $session_data['user_id'];
			// $list['user_id']=$user_id;
			// $lead_info=$this->lead_model->GetLeadData($lead_id);
			// $company=get_company_profile();
			// $customer=$this->customer_model->GetCustomerData($lead_info->customer_id);
			// $user_data=$this->user_model->get_employee_details($assigned_user_id);	
			$lead_info=$this->lead_model->GetLeadDataForQuotationPopup($lead_id);
			$company=$this->Setting_model->GetCompanyDataDislike() ;
			$assigned_user_id=$lead_info->assigned_user_id;			
			$customer=$this->customer_model->GetCustomerDataDislike($lead_info->customer_id);			
			$user_data=$this->user_model->get_employee_details_dislike($assigned_user_id);		
			//-------------- HISTORY ----------------------------------
			$history_text = '';
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$ip_addr=$_SERVER['REMOTE_ADDR'];		
			$comment_title=LEAD_GENERAL_UPDATE;
			$history_text .= addslashes($lead_regret_reason);
			// --------------------------------------------------------
			$tmp_current_stage_id=$lead_info->current_stage_id;
			if($tmp_current_stage_id==1)
			{
				$changed_stage_id=3;
				$changed_stage='REGRETTED';

				$changed_status_id=3;
				$changed_status='COLD';
			}
			else if($tmp_current_stage_id==2)
			{
				$changed_stage_id=5;
				$changed_stage='DEAL LOST';

				$changed_status_id=3;
				$changed_status='COLD';
			}
			else if($tmp_current_stage_id==4)
			{
				$changed_stage_id=5;
				$changed_stage='DEAL LOST';

				$changed_status_id=3;
				$changed_status='COLD';
			}
			// else if($tmp_current_stage_id==9)
			// {
			// 	$changed_stage_id=5;
			// 	$changed_stage='DEAL LOST';

			// 	$changed_status_id=3;
			// 	$changed_status='COLD';
			// }
			else
			{
				$changed_stage_id=3;
				$changed_stage='REGRETTED';

				$changed_status_id=3;
				$changed_status='COLD';
			}
			// UPDATE LEAD STAGE/STATUS
			$update_lead_data = array(
				'current_stage_id' =>$changed_stage_id,
				'current_stage' =>$changed_stage,
				'current_stage_wise_msg' =>$lead_regret_reason,
				// 'current_status_id'=>$changed_status_id,
				// 'current_status'=>$changed_status,
				'lost_reason'=>$lead_regret_reason_id,
				'followup_date'=>'',
				'is_hotstar'=>'N',
				'modify_date'=>date("Y-m-d")
			);								
			$this->lead_model->UpdateLead($update_lead_data,$lead_id);
			// Insert Stage Log
			$stage_post_data=array(
					'lead_id'=>$lead_id,
					'stage_id'=>$changed_stage_id,
					'stage'=>$changed_stage,
					'stage_wise_msg'=>$lead_regret_reason,
					'create_datetime'=>date("Y-m-d H:i:s")
				);
			$this->lead_model->CreateLeadStageLog($stage_post_data);

			// Insert Status Log
			// $status_post_data=array(
			// 		'lead_id'=>$lead_id,
			// 		'status_id'=>$changed_status_id,
			// 		'status'=>$changed_status,
			// 		'create_datetime'=>date("Y-m-d H:i:s")
			// 	);
			// $this->lead_model->CreateLeadStatusLog($status_post_data);

			$history_text .= '<br> Stage changed from <b>'.$lead_info->current_stage.'</b> to <b>'.$changed_stage.'</b>';			
			if($lead_regret_reason)
			{
				$history_text .= '<br> Lead Regret Reasons: '.$lead_regret_reason;
			}		
			// EMAIL CONTENT
			$this->load->library('mail_lib');		
			// Enquiry Regret Mail 5
			$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(5);
			$m_email=get_manager_and_skip_manager_email_arr($assigned_user_id);

			$regret_mail_from_mail='';
			$regret_mail_from_name='';
			$regret_this_lead_mail_subject=($this->input->post('regret_this_lead_mail_subject'))?$this->input->post('regret_this_lead_mail_subject'):'Enquiry # '.$lead_id.' - Your enquiry has been regretted';

			$e_data=array();
			$e_data['company']=$company;
			$e_data['assigned_to_user']=$user_data;
			$e_data['customer']=$customer;
			$e_data['lead_info']=$lead_info;
			$template_str = $this->load->view('admin/email_template/template/enquiry_regret_view', $e_data, true);			
			// LEAD ASSIGNED MAIL
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
	        $self_cc_mail=get_value("email","user","id=".$assigned_user_id);	        
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
			if(trim($to_mail)!='' && $email_forwarding_setting['is_mail_send']=='Y')
			{
				// $mail_data = array();				
				// $mail_data['from_mail']     = $session_data['email'];
				// $mail_data['from_name']     = $session_data['name'];
				// $mail_data['to_mail']       = $to_mail;        
				// $mail_data['cc_mail']       = $cc_mail;               
				// $mail_data['subject']       = $regret_this_lead_mail_subject;
				// $mail_data['message']       = $template_str;
				// $mail_data['attach']        = array();
				// $this->mail_lib->send_mail($mail_data);
				$post_data=array();
				$post_data=array(
						"mail_for"=>MF_ENQUIRY_REGRET,
						"from_mail"=>$session_data['email'],
						"from_name"=>$session_data['name'],
						"to_mail"=>$to_mail,
						"cc_mail"=>$cc_mail,
						"subject"=>$regret_this_lead_mail_subject,
						"message"=>$template_str,
						"attach"=>'',
						"created_at"=>date("Y-m-d H:i:s")
				);
				$this->App_model->mail_fire_add($post_data);
			}
			
			// MAIL SEND
			// ===============================================

			$regret_mail_from_mail=$session_data['email'];
			$regret_mail_from_name=$session_data['name'];
			//-------------- HISTORY ----------------------------------	
			$mail_to_client='N';
			$mail_to_client_from_mail="";
			$mail_to_client_from_name="";
			$mail_to_client_mail_subject="";
			$communication_type_id=2;
			$communication_type=get_value("title","communication_master","id=".$communication_type_id);
			$next_followup_date="";
				
			$historydata=array(
							'title'=>$comment_title,
							'lead_id'=>$lead_id,
							'comment'=>$history_text,
							'mail_trail_html'=>'',
							'mail_trail_ids'=>'',
							'cc_to_employee'=>'',
							'mail_to_client'=>$mail_to_client,
							'mail_to_client_from_mail'=>$mail_to_client_from_mail,
							'mail_to_client_from_name'=>$mail_to_client_from_name,						
							'regret_mail_from_mail'=>$regret_mail_from_mail,
							'regret_mail_from_name'=>$regret_mail_from_name,
							'mail_subject_of_update_lead_mail_to_client'=>$mail_to_client_mail_subject,
							'mail_subject_of_update_lead_regret_this_lead'=>$regret_this_lead_mail_subject,
							'attach_file'=>"",
							'communication_type_id'=>$communication_type_id,
							'communication_type'=>$communication_type,
							'next_followup_date'=>$next_followup_date,
							'create_date'=>date("Y-m-d H:i:s"),
							'user_id'=>$update_by,
							'ip_address'=>$ip_addr
							);
			$this->history_model->CreateHistory($historydata);	
			// ----------------------------------------------------------
			
			// product tagged with quoted lead
			$prod_list=$this->lead_model->get_tagged_ps_list($lead_id,'L');
			if(count($prod_list))
			{
				foreach($prod_list AS $product)
				{
					$lead_p_data=array(
						'lead_id'=>$lead_id,
						'name'=>$product['name'],
						'product_id'=>$product['product_id'],
						'tag_type'=>'LL',
						'created_at'=>date("Y-m-d H:i:s")
					);
					$this->lead_model->CreateLeadWiseProductTag($lead_p_data);
				}
			}
			// --------------------

			$result['msg'] = '';
			$result['status'] = 'success';
		}
		else
		{
			$result['msg'] = 'Missing fields';
			$result['status'] = 'error';
		}
		
        echo json_encode($result);
        exit(0); 
		
	}

	public function update_lead_stage_ajax()
	{
		$next_followup_history_txt='';
		$nf_date=date("Y-m-d H:i:s");
		$lead_id=$this->input->post('lead_id');
		$all_stage_id_str=$this->input->post('all_stage_id_str');
		$all_stage_id_arr=explode(",",$all_stage_id_str);
		// $last_stage_id=$this->input->post('last_stage_id');
		$last_checked_stage=$this->input->post('last_checked_stage');
		$last_stage_id=$last_checked_stage;
		$result['is_q_exist']='';
	    $result['loppid']='';

		if($lead_id!='')
		{	
			if($last_stage_id=='4') //DEAL WON
			{
				//$lead_data=$this->lead_model->GetLeadData($lead_id);
				$opportunity_list=$this->opportunity_model->GetSentToClientStatusOpportunityListAll($lead_id);

				$is_show_po='N';
	            $opp_id='';
	            if(count($opportunity_list)>0){
	              foreach($opportunity_list as $opp)
	              {
	                if($opp->status==2){
	                  if(count($opportunity_list)==1)
	                  {
	                      $opp_id=$opp->id;
	                  }
	                  $is_show_po='Y';
	                  break;
	                }
	              }
	            }
	            $result['is_q_exist']=$is_show_po;
	            $result['loppid']=$opp_id;
	            $result['msg'] = '';
				$result['status'] = 'deal_won';
			}
			else
			{
				$session_data = $this->session->userdata('admin_session_data');
				$user_id = $session_data['user_id'];
				$is_deal_won_lead=$this->lead_model->is_deal_won_lead($lead_id);
				$lead_info=$this->lead_model->GetLeadData($lead_id);
				//$get_prev_stage=$this->lead_model->get_prev_stage($lead_id); 
				$get_prev_status=$this->lead_model->get_prev_status($lead_id); 

				$company=get_company_profile();
				$assigned_user_id=$lead_info->assigned_user_id;
				$customer=$this->customer_model->GetCustomerData($lead_info->customer_id);
				$user_data=$this->user_model->get_employee_details($assigned_user_id);		
				//-------------- HISTORY ----------------------------------
				$history_text = '';
				$update_by=$this->session->userdata['admin_session_data']['user_id'];
				$ip_addr=$_SERVER['REMOTE_ADDR'];		
				$comment_title=LEAD_GENERAL_UPDATE;
				//$history_text .= '';
				// --------------------------------------------------------
				$tmp_current_status_id=$lead_info->current_status_id;
				$tmp_current_stage_id=$lead_info->current_stage_id;
				$changed_stage_id=$last_stage_id;
				

				$is_quoted_exist='N';
				if($changed_stage_id!=$last_checked_stage)
				{
					if(in_array(2,$all_stage_id_arr)){
						$is_quoted_exist='Y';
					}
					else{
						$is_quoted_exist='N';
					}
				}


				if($changed_stage_id=='2' && $is_quoted_exist=='N') //QUOTED
				{
					$changed_stage='QUOTED';					
					$lead_opp_id=$this->create_quotation_and_get_opp_id($lead_id);	
					if($lead_opp_id)
					{
						$post_data=array(
							'status'=>2,
							'modify_date'=>date("Y-m-d")
							);
						$this->Opportunity_model->UpdateLeadOportunity($post_data,$lead_opp_id);	
					} 	

					// UPDATE LEAD STAGE/STATUS
					$update_lead_data = array(
						'followup_date'=>$nf_date,
						'modify_date'=>date("Y-m-d")
					);								
					$this->lead_model->UpdateLead($update_lead_data,$lead_id);	
						
					$next_followup_history_txt=" AND Next Followup Date is ".datetime_db_format_to_display_format_ampm($nf_date);
				}
				else
				{
					$changed_stage_id=$last_checked_stage;
					$changed_stage=get_value('name','opportunity_stage','id='.$changed_stage_id);				

					
					if($is_deal_won_lead=='N'){
						// UPDATE LEAD STAGE/STATUS
						$update_lead_data = array(
							'current_stage_id' =>$changed_stage_id,
							'current_stage' =>$changed_stage,
							'modify_date'=>date("Y-m-d")
						);								
						$this->lead_model->UpdateLead($update_lead_data,$lead_id);
					}
					

					// Insert Stage Log
					$stage_post_data=array(
							'lead_id'=>$lead_id,
							'stage_id'=>$changed_stage_id,
							'stage'=>$changed_stage,
							'stage_wise_msg'=>'',
							'create_datetime'=>date("Y-m-d H:i:s")
						);
					$this->lead_model->CreateLeadStageLog($stage_post_data);

					if($lead_info->current_stage_id=='3' || $lead_info->current_stage_id=='5' || $lead_info->current_stage_id=='6' || $lead_info->current_stage_id=='7')
					{
						
						// if($changed_stage_id=='2' || $changed_stage_id=='9')
						if($changed_stage_id=='2')
						{
							// UPDATE LEAD STAGE/STATUS
							$update_lead_data = array(
								// 'current_status_id' =>'2',
								// 'current_status' =>'HOT',
								'followup_date'=>$nf_date,
								'modify_date'=>date("Y-m-d")
							);								
							$this->lead_model->UpdateLead($update_lead_data,$lead_id);


							// Insert Status Log
							$status_post_data=array();
							$status_post_data=array(
									'lead_id'=>$lead_id,
									'status_id'=>'2',
									'status'=>'HOT',
									'create_datetime'=>date("Y-m-d H:i:s")
								);
							// $this->lead_model->CreateLeadStatusLog($status_post_data);
							
						}
						else
						{
							// UPDATE LEAD STAGE/STATUS
							$update_lead_data = array(
								// 'current_status_id' =>$get_prev_status->status_id,
								// 'current_status' =>$get_prev_status->status,
								'followup_date'=>$nf_date,
								'modify_date'=>date("Y-m-d")
							);								
							$this->lead_model->UpdateLead($update_lead_data,$lead_id);

							// Insert Status Log
							$status_post_data=array();
							$status_post_data=array(
									'lead_id'=>$lead_id,
									'status_id'=>$get_prev_status->status_id,
									'status'=>$get_prev_status->status,
									'create_datetime'=>date("Y-m-d H:i:s")
								);
							// $this->lead_model->CreateLeadStatusLog($status_post_data);
						}
						// ----------------------------------------
						// REMOVE ALL STAGE OF 
						// REGRETTED(3),
						// DEAL LOST(5),
						// AUTO-REGRETTED(6),
						// AUTO DEAL LOST(7) 
						// FROM LOG
						// AND REMOVE ALL STATUS OF
						// COLD(3),
						// NOT FOLLOWED(4)
						// ----------------------------------------
						$this->lead_model->remove_all_inactive_status_and_stage($lead_id);						
						// ----------------------------------------
						// END
						// ----------------------------------------
						$next_followup_history_txt=" AND Next Followup Date is ".datetime_db_format_to_display_format_ampm($nf_date);
					}
				}

				$history_text .= '<br> Stage changed from <b>'.$lead_info->current_stage.'</b> to <b>'.$changed_stage.'</b> on '.date_db_format_to_display_format(date("Y-m-d H:i:s")).$next_followup_history_txt;		

				
				//-------------- HISTORY ----------------------------------	
				
					
				$historydata=array(
								'title'=>$comment_title,
								'lead_id'=>$lead_id,
								'comment'=>$history_text,
								'mail_trail_html'=>'',
								'mail_trail_ids'=>'',
								'cc_to_employee'=>'',
								'mail_to_client'=>'N',
								'mail_to_client_from_mail'=>'',
								'mail_to_client_from_name'=>'',						
								'regret_mail_from_mail'=>'',
								'regret_mail_from_name'=>'',
								'mail_subject_of_update_lead_mail_to_client'=>'',
								'mail_subject_of_update_lead_regret_this_lead'=>'',
								'attach_file'=>"",
								'communication_type_id'=>'',
								'communication_type'=>'',
								'next_followup_date'=>($next_followup_history_txt)?$nf_date:'',
								'create_date'=>date("Y-m-d H:i:s"),
								'user_id'=>$update_by,
								'ip_address'=>$ip_addr
								);
				$this->history_model->CreateHistory($historydata);	
				// ----------------------------------------------------------
				
				$result['msg'] = '';
				$result['status'] = 'success';
			}
		}
		else
		{					
			
			$result['msg'] = '';
			$result['status'] = 'fail';
		}
		
		echo json_encode($result);
        exit(0); 
	}
    // LIKE AND DISLIKE (STAGE CHANGE)
    // ==========================================================


    // ==========================================================
    // LEAD WISE PRODUCT/SERVICES TAGGED
    function delete_tagged_ps_ajax()
    {        
    	$data=array();
        $id = $this->input->post('id');
		$this->lead_model->delete_tagged_ps($id);
		echo 'success';
    }
    public function view_add_tagged_ps_ajax()
	{
		$data=array();
		$data['lead_id']=$this->input->post('lead_id');
		// $get_existing_tagged=$this->lead_model->get_tagged_ps($data['lead_id']);	
		// $data['existing_tagged']=$get_existing_tagged;
		// $data['product_list']=$this->Product_model->list_product_name();
		// $data['tagged_product_list']=$this->lead_model->list_tagged_product_name();		
		$this->load->view('admin/lead/add_lead_wise_tagged_ps_ajax',$data);		
	}
	function add_tagged_ps_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			// LEAD INFO		
			$lead_id=$this->input->post('lead_id');			
			$product_tags_arr=$this->input->post('product_tags');
			if(count($product_tags_arr))
			{
				foreach($product_tags_arr AS $p)
				{
					$is_exist_tagged=$this->lead_model->is_exist_tagged_ps_by_leadid($lead_id,$p,'L');
					if($is_exist_tagged=='N')
					{
						$p_name=get_value("name","product_varient","id=".$p);	
						$lead_p_data=array(
							'lead_id'=>$lead_id,
							'name'=>$p_name,
							'product_id'=>$p,
							'tag_type'=>'L',
							'created_at'=>date("Y-m-d H:i:s")
						);
						$this->lead_model->CreateLeadWiseProductTag($lead_p_data);
					}					
				}
			}
			$status_str='success';
	        $result["status"] = $status_str;	        
	        $result['lead_id']=$lead_id;
	        echo json_encode($result);
	        exit(0);
		}
	}

	public function get_lead_tagged_product_ajax()
	{
		$lead_id=$this->input->post('lead_id');
		$opp_id=$this->input->post('opp_id');
		if($opp_id){
			$tagged_product=$this->Opportunity_product_model->get_tagged_opportunity_product($opp_id);
		}
		else{
			$tagged_product=$this->lead_model->get_tagged_ps_list($lead_id,'L');
		}
		
		$tagged_p_str='';
		if(count($tagged_product))
		{
			foreach($tagged_product AS $product)
			{
				$tagged_p_str .=$product['product_id'].',';
			}
			$tagged_p_str= rtrim($tagged_p_str,",");;	
		}
		// print_r(explode(',',$tagged_p_str));die();
		echo $tagged_p_str;
	}
    // LEAD WISE PRODUCT/SERVICES TAGGED
    // ==========================================================


    // ==========================================================
    // LEAD C2C
    function set_c2c_using_api_ajax()
    {
    	$msg='';
    	// $company=get_company_profile();
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
    	$lead_id = $this->input->post('lid'); 
    	$c2c_cust_mobile = $this->input->post('cust_mobile'); 
    	$c2c_cust_id = $this->input->post('cust_id'); 
    	$c2c_contact_person = $this->input->post('contact_person'); 
    	$c2c_user_mobile = $this->input->post('user_mobile'); 
    	$c2c_user_id = $this->input->post('user_id'); 
		$c2c_settings_id = $this->input->post('c2c_settings_id');		
		$c2c_info=$this->Setting_model->GetC2cCredentialsDetailsById($c2c_settings_id);
    	$lead_data=$this->lead_model->GetLeadData($lead_id);
    	// $api_url=$company['c2c_api_dial_url'];
    	// $c2c_userid=$company['c2c_api_userid'];
    	// $c2c_password=$company['c2c_api_password'];
    	// $c2c_client_name=$company['c2c_api_client_name'];
		$api_full_url=''; 
		if($c2c_info['c2c_service_provider_id']=='1')
		{
			
			$post=array('lead_id'=>$lead_id,
						'user_id'=>$user_id,
						'executive_mobile_number'=>$c2c_user_mobile,
						'customer_id'=>$c2c_cust_id,
						'customer_contact_person'=>$c2c_contact_person,
						'client_mobile_number'=>$c2c_cust_mobile,
						'call_status'=>'P',
						'call_status_txt'=>'Hit the C2C URL.',
						'c2c_url'=>$api_full_url,
						'created_at'=>date("Y-m-d H:i:s"),
						'updated_at'=>date("Y-m-d H:i:s")
						);
			$return=$this->lead_model->CreateC2CLog($post);					
			if($return!=false)
			{
				// $client_info=$session_data['client_info'];
				// $token=$client_info->api_access_token;			
				
				$api_url=$c2c_info['c2c_api_dial_url'];
				$c2c_userid=$c2c_info['c2c_api_userid'];
				$c2c_password=$c2c_info['c2c_api_password'];
				$c2c_client_name=$c2c_info['c2c_api_client_name'];
				$api_full_url=$api_url.'?Extension='.$c2c_user_mobile.'&DialNumber='.$c2c_cust_mobile.'&userid='.$c2c_userid.'&password='.$c2c_password.'&uniquid='.$return.'&Client='.$c2c_client_name.'&leadid='.$return;
				

				$post_update=array(
					'c2c_url'=>$api_full_url,
					'updated_at'=>date("Y-m-d H:i:s")
					);
				$this->lead_model->UpdateC2CLog($post_update,$return);
				
				$status='success';
			}
			else
			{
				$status='fail';
			}
		}	
		else if($c2c_info['c2c_service_provider_id']=='2')
		{  
			$arrgument=array(
							'url'=>$c2c_info['c2c_api_dial_url'],
							'agent_number'=>$c2c_info['office_no'],
							'destination_number'=>$c2c_cust_mobile,
							'authorization_token'=>$c2c_info['c2c_token']);
			
			$c2c_return=tata_call_by_c2c($arrgument);
			$status='success';
		}

		if($status=='success')
		{
			$lead_title=get_value("title","lead","id=".$lead_id);				
			// ----------------------
			// CREATE LEAD HISTORY    	
			$attach_filename='';
			$update_by=$user_id;
			$date=date("Y-m-d H:i:s");				
			$ip_addr=$_SERVER['REMOTE_ADDR'];				
			$message="A call has beed initiated through ".$c2c_info['service_provider_name']." API for The lead (".$lead_title.") on ".date_db_format_to_display_format(date('Y-m-d'))."";		

			$comment_title=LEAD_UPDATE_MANUAL;
			$historydata=array(
								'title'=>$comment_title,
								'lead_id'=>$lead_id,
								'comment'=>$message,
								'attach_file'=>$attach_filename,
								'create_date'=>$date,
								'communication_type_id'=>3,
								'communication_type'=>'Call Update',
								'user_id'=>$update_by,
								'ip_address'=>$ip_addr
							);
			$this->history_model->CreateHistory($historydata);
			// CREATE LEAD HISTORY
			// ----------------------
		}
		
    	
		$result['msg'] =$c2c_return['message'];
		$result['status'] = $status;
		$result['api_url']=$api_full_url;
		$result['c2c_service_provider_id']=$c2c_info['c2c_service_provider_id'];
		$result['c2c_http_code'] =$c2c_return['http_code'];
        echo json_encode($result);
        exit(0);
    }
    // LEAD C2C
    // ==========================================================

    public function update_next_followup_date_ajax()
    {
		$next_followup_history_txt='';
		
		$next_followup_date=$this->input->post('nfd_date'); 
		$lead_id=$this->input->post('nfd_lead_id');
		//echo $str=$next_followup_date.'~'.$lead_id;	die();	
		$next_followup_date=datetime_display_format_to_db_format_ampm($next_followup_date);
		if($next_followup_date!="" && $lead_id!="")
		{
			$lead_info=$this->lead_model->GetLeadData($lead_id);
	       	
			//-------------- HISTORY ----------------------------------
			$history_text = '';
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$ip_addr=$_SERVER['REMOTE_ADDR'];		
			$comment_title=LEAD_GENERAL_UPDATE;
			$history_text .= 'Next Follow-up date Updated';
			// --------------------------------------------------------
			//$next_followup_date=date_display_format_to_db_format($next_followup_date);
			

			// =======================================
			if($lead_info->current_stage_id=='3' || $lead_info->current_stage_id=='5' || $lead_info->current_stage_id=='6' || $lead_info->current_stage_id=='7')
			{

				$get_prev_stage=$this->lead_model->get_prev_stage($lead_id);
				$get_prev_status=$this->lead_model->get_prev_status($lead_id);

				$history_text .= '<br> Stage changed from <b>'.$lead_info->current_stage.'</b> to <b>'.$get_prev_stage->stage.'</b>';
				$history_text .= '<br> Status changed from <b>'.$lead_info->current_status.'</b> to <b>'.$get_prev_status->status.'</b>';

				// UPDATE LEAD STAGE/STATUS
				$update_lead_data=array();
				$update_lead_data = array(
					'current_stage_id' =>$get_prev_stage->stage_id,
					'current_stage' =>$get_prev_stage->stage,
					'current_stage_wise_msg' =>'',
					// 'current_status_id'=>$get_prev_status->status_id,
					// 'current_status'=>$get_prev_status->status,
					'modify_date'=>date("Y-m-d")
				);								
				$this->lead_model->UpdateLead($update_lead_data,$lead_id);
				// Insert Stage Log
				$stage_post_data=array();
				$stage_post_data=array(
						'lead_id'=>$lead_id,
						'stage_id'=>$get_prev_stage->stage_id,
						'stage'=>$get_prev_stage->stage,
						'stage_wise_msg'=>'',
						'create_datetime'=>date("Y-m-d H:i:s")
					);
				$this->lead_model->CreateLeadStageLog($stage_post_data);

				// Insert Status Log
				$status_post_data=array();
				$status_post_data=array(
						'lead_id'=>$lead_id,
						'status_id'=>$get_prev_status->status_id,
						'status'=>$get_prev_status->status,
						'create_datetime'=>date("Y-m-d H:i:s")
					);
				// $this->lead_model->CreateLeadStatusLog($status_post_data);
				
				// ----------------------------------------
				// REMOVE ALL STAGE OF 
				// REGRETTED(3),
				// DEAL LOST(5),
				// AUTO-REGRETTED(6),
				// AUTO DEAL LOST(7) 
				// FROM LOG
				// AND REMOVE ALL STATUS OF
				// COLD(3),
				// NOT FOLLOWED(4)
				// ----------------------------------------
				$this->lead_model->remove_all_inactive_status_and_stage($lead_id);						
				// ----------------------------------------
				// END
				// ----------------------------------------
			}
			// =======================================

			// UPDATE LEAD NEXT FOLLOW UP
			$update_lead_data=array();
			$update_lead_data = array(
				'followup_date' =>$next_followup_date,
				'is_followup_date_changed'=>'Y',
				'modify_date'=>date("Y-m-d")
			);							
			$this->lead_model->UpdateLead($update_lead_data,$lead_id);

			//-------------- HISTORY ----------------------------------		
			$communication_type_id=1;		
			$communication_type=get_value("title","communication_master","id=".$communication_type_id);
			$historydata=array(
							'title'=>$comment_title,
							'lead_id'=>$lead_id,
							'comment'=>$history_text,					
							'communication_type_id'=>$communication_type_id,
							'communication_type'=>$communication_type,
							'next_followup_date'=>$next_followup_date,
							'create_date'=>date("Y-m-d H:i:s"),
							'user_id'=>$update_by,
							'ip_address'=>$ip_addr
							);
			$this->history_model->CreateHistory($historydata);	
			// ----------------------------------------------------------	

	    	$status_str='success';  
	        $result["status"] = $status_str;
	        $result["msg"]=$str;
	        $result['lid']=$lead_id;
	        //$result['updated_nfd']=date_db_format_to_display_format($next_followup_date);;
			$result['updated_nfd']=$next_followup_date;
	        echo json_encode($result);
	        exit(0); 
		}
		else
		{
			$status_str='fail';  
	        $result["status"] = $status_str;
	        $result["msg"]='';
	        echo json_encode($result);
	        exit(0); 
		}		
    }

    public function download_csv()
    {
    	$session_data=$this->session->userdata('admin_session_data');
	    $start = 0;    
	    $arg=array();
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		   
	    // FILTER DATA
		$arg['filter_search_str']=$this->input->get('filter_search_str');
		$arg['lead_from_date']=$this->input->get('filter_lead_from_date');
		$arg['lead_to_date']=$this->input->get('filter_lead_to_date');
		$arg['date_filter_by']=$this->input->get('filter_date_filter_by');
		$arg['assigned_user']=($this->input->get('filter_assigned_user'))?$this->input->get('filter_assigned_user'):$tmp_u_ids_str;
		$arg['lead_applicable_for']=$this->input->get('filter_lead_applicable_for');
		$arg['filter_lead_type']=$this->input->get('filter_lead_type');
		$arg['filter_lead_type_wise_stages']='';
		/*if($arg['filter_lead_type'])
		{
			$arg['filter_lead_type_wise_stages']=$this->lead_model->get_type_wise_lead_stage($arg['filter_lead_type']);
		}*/

		$arg['opportunity_stage_filter_type']=$this->input->get('filter_opportunity_stage_filter_type');
		$arg['opportunity_stage']=$this->input->get('filter_opportunity_stage');
	    $arg['opportunity_status']=$this->input->get('filter_opportunity_status');
	    $arg['source_ids']=$this->input->get('filter_by_source');
		$arg['is_hotstar']=$this->input->get('filter_is_hotstar');
		$arg['pending_followup']=$this->input->get('filter_pending_followup');
		$arg['pending_followup_for']=$this->input->get('filter_pending_followup_for');
	    $arg['filter_sort_by']=$this->input->get('filter_sort_by'); 
	    $arg['filter_search_by_id']=$this->input->get('filter_search_by_id'); 
	    $arg['filter_like_dsc']=$this->input->get('filter_like_dsc'); 	    
		$arg['filter_common_lead_pool']=$this->input->get('filter_common_lead_pool');
		$arg['filter_followup']=$this->input->get('filter_followup');
		$arg['assigned_observer']=$user_id;
		$arg['filter_lead_observer']=$this->input->get('filter_lead_observer');
		if($arg['filter_followup']=='AL')
		{
			$arg['filter_lead_type']='AL';
		}
		//echo $arg['filter_like_dsc'].'/'.$arg['pending_followup_for'].'/'.$arg['filter_followup'].'/'.$arg['pending_followup'].'/'.$arg['filter_common_lead_pool']; die();
		$arg['filter_non_active_stages']=$this->lead_model->get_type_wise_lead_stage('NAL');
		if($arg['filter_like_dsc']!='' || $arg['pending_followup_for']!='' || $arg['filter_followup']!='' || $arg['pending_followup']!='' || $arg['filter_common_lead_pool']!='N' || $arg['filter_followup']=='AL')
		{	
			$arg['filter_lead_type_wise_stages']=$this->lead_model->get_type_wise_lead_stage('AL');
			
		}
		else
		{
			$arg['filter_lead_type_wise_stages']=$this->lead_model->get_type_wise_lead_stage($arg['filter_lead_type']);
			
		}
	    
	    $limit = $this->lead_model->get_csv_list_count($arg);
		//$limit =140;
	    $start=empty($start)?0:($start-1)*$limit;
	    $arg['limit']=$limit;
	    $arg['start']=$start;
	    // $list['priority_wise_stage_key_val']=$this->lead_model->priority_wise_stage_key_val();
	    
    	$rows=$this->lead_model->get_csv_list($arg);
        // print_r($rows); die();
		$priority_wise_stage=$this->lead_model->priority_wise_stage();

		
        $array[] = array('');
        $array[] = array(
                        'Lead ID',
                        'Date',
                        'Lead Title',
                        'Requirement',
                        'Assigned to',
						'User type',
						'User branch',
                        'Country',
                        'State',
                        'City',
						'Last Updated',
						'Next Follow-up Date',
                        // 'Total Deal Value',
                        'Stage',
                        // 'Comment',
						'Lead regret/Lost reason',
                        'Status',
                        'Source',
                        'Company',
                        'Contact person',
                        'Email',
                        'Mobile',
						'Closer date',
						'Currency',
						'Deal Value',
						'Repeat Client(Yes/No)',
						'Last History'
                        );
        
        if(count($rows) > 0)
        {
            foreach ($rows as $row) 
            {
				/*
				$lc_arr=explode("^",$row->lc_str);
				$last_comment='';
				if($lc_arr[0]){
					$last_comment .='Comment: '.str_replace('&quot;','" ',str_replace('#','For ',strip_tags($lc_arr[0])));
				}
				if($lc_arr[1]){
					$last_comment .=' | Date: '.date("d-M-Y h:i:s A", strtotime($lc_arr[1]));
				}
				if($row->assigned_user_name){
					$last_comment .=' | Update By: '.$row->assigned_user_name;
				}
				*/
				$last_comment=($row->lastComment)?str_replace('#','For ',strip_tags($row->lastComment)):'N/A';
				
				
                $enquiry_date = date_db_format_to_display_format($row->enquiry_date);
                $modify_date=date_db_format_to_display_format($row->modify_date);
				// if($row->current_stage_id==1 || $row->current_stage_id==8 || $row->current_stage_id==2 || $row->current_stage_id==9)

				if($row->current_stage_id=='7' || $row->current_stage_id=='6' || $row->current_stage_id=='3' || $row->current_stage_id=='5' || $row->current_stage_id=='4')
				{
					$followup_date='--';
				}
				else
				{
					if($row->followup_date!='0000-00-00')
					{
						$followup_date= date_db_format_to_display_format($row->followup_date);
					}
					else
					{
					  $followup_date='--';
					}
				}
				// if($row->current_stage_id==1 || $row->current_stage_id==8 || $row->current_stage_id==2)
				// {
				// 	if($row->followup_date!='0000-00-00')
				// 	{
				// 		$followup_date= date_db_format_to_display_format($row->followup_date);
				// 	}
				// 	else
				// 	{
				// 	  $followup_date='--';
				// 	}
				// }
				// else
				// {
				// 	$followup_date='--';
				// }

				$proporal_count=($row->proposal>0)?$row->proposal:'N/A';
				//$total_deal_value=($row->total_deal_value>0)?$row->total_deal_value:'N/A';
				// $total_deal_value='N/A';
				// if deal won then po valur other wise deal value
				$last_deal_value_currency=($row->deal_value_as_per_purchase_order)?$row->deal_value_as_per_purchase_order_currency:(($row->last_deal_value)?$row->last_deal_value_currency:'');
				$last_deal_value=($row->deal_value_as_per_purchase_order)?$row->deal_value_as_per_purchase_order:(($row->last_deal_value)?$row->last_deal_value:'');
				// $last_deal_value=($row->deal_value_as_per_purchase_order)?$row->deal_value_as_per_purchase_order_currency.' '.$row->deal_value_as_per_purchase_order:(($row->last_deal_value)?$row->last_deal_value_currency.' '.$row->last_deal_value:'');

				if($row->assigned_user_branch_name!='' || $row->assigned_user_branch_name_cs!=''){
					$assigned_user_branch_name_tmp=($row->assigned_user_branch_name)?$row->assigned_user_branch_name:$row->assigned_user_branch_name_cs;
				}
				else{
					$assigned_user_branch_name_tmp='N/A';
				}
				
				$order_arr=explode(',',$row->orders);								
				$order_count=count(array_keys($order_arr, "4"));
				if($order_count>1){
					$repeat_client='Yes'; 
				}
				else{
					$repeat_client='No';
				}

				$deal_value='';
				$deal_value_currency_code='';
				if($row->quotation_matured_deal_value_as_per_purchase_order){
					$deal_value=round($row->quotation_matured_deal_value_as_per_purchase_order);
					$deal_value_currency_code=$row->quotation_matured_currency_code;
				}
				else{ 
					if($row->quotation_sent_deal_value){
						$deal_value=round($row->quotation_sent_deal_value);
						$deal_value_currency_code=$row->quotation_sent_currency_code;
					}
					else{
						$deal_value=($row->deal_value)?round($row->deal_value):'';
						$deal_value_currency_code=$row->deal_value_currency_code;
					}
				} 

				// ----------------------------
				// priority wise stage				
				if($row->current_stage_id!='1')
				{
					$stage_log_arr=array_unique(explode(',', $row->stage_logs));	
					$curr_stage_id=$row->current_stage_id; 
					$stage_count=0;
					if(count($priority_wise_stage['active_lead_stages_y']))
					{
						foreach($priority_wise_stage['active_lead_stages_y'] AS $stage)
						{
							if(in_array($stage['id'], $stage_log_arr)){
									$stage_count++;
							}
						}
					}
					$active_stage_text='';
					$k=1;		            	
					if(count($priority_wise_stage['active_lead_stages_y']))
					{
						foreach($priority_wise_stage['active_lead_stages_y'] AS $stage)
						{
							if(in_array($stage['id'], $stage_log_arr)){
								if($stage_count==$k)
								{		           
									if(($curr_stage_id=='3') || ($curr_stage_id=='5')){										
									}  
									else{										
										$active_stage_text=$stage['name'];
									}
								}
								else{
									$active_stage_text=$stage['name'];
								}
								$k++;							
							}
						}
					}
					if(count($priority_wise_stage['active_lead_stages_n']))
					{
						foreach($priority_wise_stage['active_lead_stages_n'] AS $stage)
						{
							if($curr_stage_id==$stage['id']){
								$active_stage_text=$stage['name'];
							}
							else{
							}							
						}
					}					
				}
				else{
					$active_stage_text='PENDING';
				}
				// priority wise stage
				// ----------------------------
				
				$array[] = array(
                                $row->id,
                                $enquiry_date,
                                $row->title,
                                $row->buying_requirement,
                                $row->assigned_user_name,
								($row->assigned_user_employee_type)?$row->assigned_user_employee_type:'N/A',
								$assigned_user_branch_name_tmp,
                                $row->cust_country_name,
                                $row->cust_state_name,
                                $row->cust_city_name,	
								$modify_date,
                                $followup_date,
                                // $total_deal_value, 
                                // $row->current_stage,
								$active_stage_text,
                                $row->current_stage_wise_msg,
                                $row->current_status,
                                $row->source_name,
                                $row->cus_company_name,
                                $row->cus_contact_person, 
                                ($row->cus_email)?$row->cus_email:'N/A',
                                ($row->cus_mobile)?$row->cus_mobile:'N/A',
								($row->closer_date)?date_db_format_to_display_format($row->closer_date):'N/A',
								// ($last_deal_value_currency)?$last_deal_value_currency:'N/A',
								// ($last_deal_value)?$last_deal_value:'N/A',
								($deal_value_currency_code)?$deal_value_currency_code:'N/A',
								($deal_value)?$deal_value:'N/A',
								$repeat_client,
								$last_comment
                                );
            }
        }
		
        $tmpName='lead_list';
        $tmpDate =  date("YmdHis");
        $csvFileName = $tmpName."_".$tmpDate.".csv";

        $this->load->helper('csv');
        //query_to_csv($array, TRUE, 'Attendance_list.csv');
        array_to_csv($array, $csvFileName);
        return TRUE;
    }

    // ------------------------------------
    // CALL LOG LEADS
    public function manage_sync_call()
	{
		$data=array();
		$this->load->view('admin/lead/manage_sync_call_view',$data);		
	}
	// AJAX PAGINATION START
	function get_sync_call_list_ajax()
	{
		$session_data=$this->session->userdata('admin_session_data');
	    $start = $this->input->get('page');
	    $view_type = $this->input->get('view_type');
	    
	    $this->load->library('pagination');
	    $limit=30;
	    $config = array();
	    $list=array();
	    $arg=array();
		$arg['filter_sort_by']=$this->input->get('filter_sort_by');
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		   
	    // FILTER DATA	
	    $arg['assigned_user']=($this->input->get('filter_assigned_user'))?$this->input->get('filter_assigned_user'):$tmp_u_ids_str;		
	    $arg['filter_sort_by']=$this->input->get('filter_sort_by');

	    $arg['filter_sync_call_filter_by_keyword']=$this->input->get('filter_sync_call_filter_by_keyword'); 
	    $arg['filter_sync_call_from_date']=$this->input->get('filter_sync_call_from_date'); 
	    $arg['filter_sync_call_to_date']=$this->input->get('filter_sync_call_to_date'); 
	    $arg['filter_sync_call_call_type']=$this->input->get('filter_sync_call_call_type'); 
	    $arg['filter_sync_call_buyer_type']=$this->input->get('filter_sync_call_buyer_type'); 

	   //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->lead_model->get_sync_call_list_count($arg);	    
	    
	    $config['per_page'] = $limit;
	    $config['uri_segment']=4;
	    $config['num_links'] = 1;
	    $config['use_page_numbers'] = TRUE;
	   //$config['full_tag_open'] = '<div id="paginator" class="dataTables_paginate paging_bootstrap">';
	    $config['attributes'] = array('class' => 'myclass_sync_call','data-viewtype'=>$view_type);
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
	    $table = '';
		$list['tmp_u_ids_arr']=explode(",",$tmp_u_ids_str);	
	    $list['rows']=$this->lead_model->get_sync_call_list($arg);	
		$list['priority_wise_stage']=$this->lead_model->priority_wise_stage();
	    if($view_type=='grid')
	    {	
	    	//$table = $this->load->view('admin/order/payment_followup_group_view_ajax',$list,TRUE);
	    }
	    else
	    {	    	
	    	$table = $this->load->view('admin/lead/manage_sync_call_view_ajax',$list,TRUE);
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


	function update_auto_tagged_call_list_with_lead_ajax()
	{
		$session_data=$this->session->userdata('admin_session_data');
	    $view_type = $this->input->get('view_type');

	    $list=array();
	    $arg=array();
		$arg['filter_sort_by']=$this->input->get('filter_sort_by');
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		   
	    // FILTER DATA	
	    $arg['assigned_user']=($this->input->get('filter_assigned_user'))?$this->input->get('filter_assigned_user'):$tmp_u_ids_str;		
	    $arg['filter_sort_by']=$this->input->get('filter_sort_by');

	    $arg['filter_sync_call_filter_by_keyword']=$this->input->get('filter_sync_call_filter_by_keyword'); 
	    $arg['filter_sync_call_from_date']=$this->input->get('filter_sync_call_from_date'); 
	    $arg['filter_sync_call_to_date']=$this->input->get('filter_sync_call_to_date'); 
	    $arg['filter_sync_call_call_type']=$this->input->get('filter_sync_call_call_type'); 
	    $arg['filter_sync_call_buyer_type']=$this->input->get('filter_sync_call_buyer_type'); 

	    
		$tmp_u_ids_arr=explode(",",$tmp_u_ids_str);	
	    $rows=$this->lead_model->get_sync_call_list($arg);
		$priority_wise_stage=$this->lead_model->priority_wise_stage();
	    	
	    //$table = $this->load->view('admin/lead/manage_sync_call_view_ajax',$list,TRUE);

		echo'<pre>';
		//print_r($list);



		if(count($rows)){ 
			foreach($rows AS $row){
			 
			 
				if($row->current_stage_id!='1')
				{ 
					$stage_log_arr=array_unique(explode(',', $row->stage_id_log_str));	
					$curr_stage_id=$row->current_stage_id;
					$stage_count=0;
					if(count($priority_wise_stage['active_lead_stages_y']))
					{
					foreach($priority_wise_stage['active_lead_stages_y'] AS $stage)
					{
						if(in_array($stage['id'], $stage_log_arr)){
							$stage_count++;
						}
					}
					}
					$active_stage_text='';
					$active_stage_id='';
					$k=1;		            	
					if(count($priority_wise_stage['active_lead_stages_y']))
					{
					foreach($priority_wise_stage['active_lead_stages_y'] AS $stage)
					{
						if(in_array($stage['id'], $stage_log_arr))
						{
						if($stage_count==$k)
						{		           
							if(($curr_stage_id=='3') || ($curr_stage_id=='5'))
							{
							// $is_active_stage='';
							}  
							else
							{
							if(in_array('4', $stage_log_arr)){ 
								// $is_active_stage='active--';
								}
								else{
								// $is_active_stage='active';
								
								}
								$active_stage_text=$stage['name'];
								$active_stage_id=$stage['id'];
							}
						}
						else
						{
							// $is_active_stage='';
							$active_stage_text=$stage['name'];
							$active_stage_id=$stage['id'];
						}
						$k++;			            		
						}
					}
					}
			
					if(count($priority_wise_stage['active_lead_stages_n']))
					{
					foreach($priority_wise_stage['active_lead_stages_n'] AS $stage)
					{
						if($curr_stage_id==$stage['id']){
						// $lead_lost_show='';
						$active_stage_text=$stage['name'];
						$active_stage_id=$stage['id'];
						}
						else{
						// $lead_lost_show='hide';
						}			            		
					}
					}		     
				} 
				else
				{
				if($row->lead_str){
					$active_stage_text='PENDING';
					$active_stage_id=1;
				}
				else{
					$active_stage_text='';
					$active_stage_id='';
				}
				}  
				
				
				if($row->cust_mobile!=''){
			
				if($active_stage_id=='3' || $active_stage_id=='5' || $active_stage_id=='6' || $active_stage_id=='7'){
					//echo $bg_class='bg-danger';
				}
				else{

					$id=$row->id;
					$number=$row->number;
					//$row=$this->lead_model->get_sync_call_row($id);

					$get_lead_list=array();
					$arg=array();
					$arg['active_lead_stages']=$this->lead_model->get_type_wise_lead_stage('');
					$arg['number']=$number;
					$get_lead_list=$this->lead_model->get_lead_list_from_number($arg);

					//print_r($get_lead_list);

					if(count($get_lead_list)){
						$post_data=array();
						$post_data=array(
							'status'=>'3'
							);
						$r=$this->lead_model->update_sync_call($post_data,$id);
						if($r==true){
							foreach($get_lead_list AS $lead){

								
								$lead_for_call_update = $lead->id;
								echo $id.'__'.$number.'___'.$lead_for_call_update;
								echo'<br>';

								$lead_id=$lead->id;
								$row=$this->lead_model->get_sync_call_row($id);

								$lead_info=$this->lead_model->GetLeadData($lead_id);
								$status_wise_msg="Follow-up call for Lead &quot;".$lead_info->title." (#".$lead_id.")&quot;.  call start time: ".datetime_db_format_to_display_format($row->call_start)." and call end time: ".datetime_db_format_to_display_format($row->call_end);
								$post_data=array();
								$post_data=array(
												'call_history_id'=>$id,
												'tagged_lead_id'=>$lead_id,
												'status_wise_msg'=>$status_wise_msg,
												'created_at'=>date("Y-m-d H:i:s")
												);
								$r=$this->lead_model->add_sync_call_tagged_lead_wise($post_data);
								if($r==true){

									$update_by=$this->session->userdata['admin_session_data']['user_id'];		
									$date=date("Y-m-d H:i:s");				
									$ip_addr=$_SERVER['REMOTE_ADDR'];
									$message=$status_wise_msg;
									$comment_title=LEAD_UPDATE_MANUAL;
									$historydata=array(
														'title'=>$comment_title,
														'lead_id'=>$lead_id,
														'comment'=>addslashes($message),
														'attach_file'=>'',
														'create_date'=>$date,
														'user_id'=>$update_by,
														'ip_address'=>$ip_addr
													);
									$this->history_model->CreateHistory($historydata);

									$status='success';    
									$msg='';
								}


							}
						}

				    }
					
					//echo $bg_class='bg-success';
				}
				}
				else{
				//echo $bg_class='';
				}

			}
		}


		
	    $data =array (
	       "update_status"=>'success'
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit;
	}




	public function delete_row_ajax()
	{ 
		
		$id_str=$this->input->post('id');
		if($id_str!="")
		{		
			$id_arr=explode(",", $id_str);	
			if(count($id_arr))
			{
				// $post_data = array(
				// 	'is_deleted' =>'Y'
				// );
				foreach($id_arr AS $id)
				{							
					// $this->lead_model->update_sync_call($post_data,$id);
					$return=$this->lead_model->delete_sync_call($id);
				}
				$result['msg'] = '';
				$result['status'] = 'success';
			}
			else
			{
				$result['msg'] = 'No record deleted.';
				$result['status'] = 'error';
			}		
			
		}
		else
		{
			$result['msg'] = 'Unknown Error.';
			$result['status'] = 'error';
		}

        echo json_encode($result);
        exit(0);
	}

	public function add_row_as_lead_ajax()
	{ 
		
		$id=$this->input->post('id');
		if($id!="")
		{		
			$post_data = array(
				'is_deleted' =>'Y'
			);										
			$this->lead_model->update_sync_call($post_data,$id);
			// $row=$this->lead_model->get_sync_call_row($id);
			// $result['name']=$row->name;
			// $result['mobile']=$row->number;
			// $result['country_code']=$row->country_code;
			$result['msg'] = '';
			$result['status'] = 'success';		
			
		}
		else
		{
			$result['msg'] = 'Unknown Error.';
			$result['status'] = 'error';
		}

        echo json_encode($result);
        exit(0);
	}

	public function sync_call_user_wise_report_download_csv()
    {
    	$session_data=$this->session->userdata('admin_session_data');
	    $start = 0;    
	    $arg=array();
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		   
	    // FILTER DATA
		// $arg['filter_search_str']=$this->input->get('filter_search_str');
        
        $array[] = array('Call Log Report');
        $array[] = array(
                        'Date',
                        'Total Talk Time',
                        'Productive Talk Time',
                        'Total Calls',
                        'Outgoing Calls',
						'Incoming Calls',
						'Personal Calls',
                        'Business Calls',
                        'Missing Opportunities',
                        'New Leads Created',                        
                        'Leads Updated',
                        'New Leads from Paying Buyers',
                        'New Leads from Free Buyers',
                        'Service Calls'
                        );
        $tmpName='Call-Log-Report';
        $tmpDate =  date("YmdHis");
        $csvFileName = $tmpName."-".$tmpDate.".csv";
        $this->load->helper('csv');
        //query_to_csv($array, TRUE, 'Attendance_list.csv');
        array_to_csv($array, $csvFileName);
        return TRUE;
    }

    public function report_sync_call()
	{
		$data=array();	
		$session_data=$this->session->userdata('admin_session_data');	
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);	
		$data['user_id']=$user_id;
		$data['user_list']=$this->user_model->GetUserList($tmp_u_ids_str);

		// ==========================
		//get the current year
		$Startyear=date('Y');
		$endYear=$Startyear-10;
		// set start and end year range i.e the start year
		$yearArray = range($Startyear,$endYear);
		$data['yearArray']=$yearArray;
		$data['Startyear']=$Startyear;
		// ==========================		
		$data['CurrMonth']= date('m');
		$data['CurrDate']= date_db_format_to_display_format(date('Y-m-d'));

		$this->load->view('admin/lead/report_sync_call_view',$data);		
	}
	// AJAX PAGINATION START
	function get_sync_call_report_list_ajax()
	{
		$session_data=$this->session->userdata('admin_session_data');
	    $list=array();
	    $arg=array();		
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
		 		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		   
	    // FILTER DATA	
	    $arg['filter_sort_by']=$this->input->get('filter_sort_by');
	    $arg['assigned_user']=($this->input->get('filter_assigned_user'))?$this->input->get('filter_assigned_user'):$tmp_u_ids_str;
	    $arg['filter_scr_year']=$this->input->get('filter_scr_year'); 
	    $arg['filter_scr_month']=$this->input->get('filter_scr_month');	
		
		$arg['filter_scr_from_date']=($this->input->get('filter_scr_from_date'))?date_display_format_to_db_format($this->input->get('filter_scr_from_date')):''; 
	    $arg['filter_scr_to_date']=($this->input->get('filter_scr_to_date'))?date_display_format_to_db_format($this->input->get('filter_scr_to_date')):'';	
	    $table = '';
	    $list['rows']=$this->lead_model->get_sync_call_report_list($arg);	
	    $table = $this->load->view('admin/lead/report_sync_call_view_ajax',$list,TRUE);	
		
	    $data =array ("table"=>$table);
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit;
	}

	public function popup_action_row_ajax()
	{ 
		
		$id=$this->input->post('id');
		$row=$this->lead_model->get_sync_call_row($id);

		$get_lead_list=array();
		$arg=array();
		$arg['active_lead_stages']=$this->lead_model->get_type_wise_lead_stage('');
		$arg['number']=$row->number;
		$get_lead_list=$this->lead_model->get_lead_list_from_number($arg);
		$list=array();
		$list['row']=$row;
		$list['get_lead_list']=$get_lead_list;
		$html = $this->load->view('admin/lead/rander_call_history_popup_action_ajax',$list,TRUE);	

		$status='success';    
		$msg='';
		$title='Call History Number-'.$row->number;
	    $data =array (
	       	"html"=>$html,
	       	"status"=>$status,
	       	"msg"=>$msg,
	       	"title"=>$title
	        );
        $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit();
	}

	public function add_as_other_business_ajax()
	{
		$id=$this->input->post('id');
		$status_wise_msg=$this->input->post('status_wise_msg');
		$post_data=array(
						'status'=>'4',
						'status_wise_msg'=>$status_wise_msg
						);
		$r=$this->lead_model->update_sync_call($post_data,$id);
		if($r==true){
			$status='success';    
			$msg='';
		}
		else{
			$status='fail';    
			$msg='Oops! Could not change status.';
		}			
	    $data =array (	       	
	       	"status"=>$status,
	       	"msg"=>$msg
	        );
        $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit();
	}

	public function call_history_update_lead_ajax()
	{
		$id=$this->input->post('id');
		$lead_id=$this->input->post('lead_for_call_update');
		$row=$this->lead_model->get_sync_call_row($id);

		$lead_info=$this->lead_model->GetLeadData($lead_id);
		$status_wise_msg="Follow-up call for Lead &quot;".$lead_info->title." (#".$lead_id.")&quot;.  call start time: ".datetime_db_format_to_display_format($row->call_start)." and call end time: ".datetime_db_format_to_display_format($row->call_end);
		$post_data=array(
						'tagged_lead_id'=>$lead_id,
						'status'=>'3',
						'status_wise_msg'=>$status_wise_msg
						);
		$r=$this->lead_model->update_sync_call($post_data,$id);
		if($r==true){

			$update_by=$this->session->userdata['admin_session_data']['user_id'];		
			$date=date("Y-m-d H:i:s");				
			$ip_addr=$_SERVER['REMOTE_ADDR'];
			$message=$status_wise_msg;
			$comment_title=LEAD_UPDATE_MANUAL;
			$historydata=array(
								'title'=>$comment_title,
								'lead_id'=>$lead_id,
								'comment'=>addslashes($message),
								'attach_file'=>'',
								'create_date'=>$date,
								'user_id'=>$update_by,
								'ip_address'=>$ip_addr
							);
			$this->history_model->CreateHistory($historydata);

			$status='success';    
			$msg='';
		}
		else{
			$status='fail';    
			$msg='Oops! Could not change status.';
		}			
	    $data =array (	       	
	       	"status"=>$status,
	       	"msg"=>$msg
	        );
        $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit();
	}

	public function rander_call_history_report_detail_ajax()
	{ 
		
		$session_data=$this->session->userdata('admin_session_data');
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
		 		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);

		$date=$this->input->post('date');
		$type=$this->input->post('type');
		$lead_id=($this->input->post('lead_id'))?$this->input->post('lead_id'):'';
		$is_delete_icon_show=($lead_id)?'N':'Y';
		$arg=array();
		$arg['lead_id']=$lead_id;
		$arg['date']=$date;
		$arg['type']=$type;
		$arg['assigned_user']=($this->input->post('filter_assigned_user'))?$this->input->post('filter_assigned_user'):$tmp_u_ids_str;
		$list=array();		
		$list['rows']=$this->lead_model->get_call_history_report_detail_list($arg);
		$list['is_delete_icon_show']=$is_delete_icon_show;
		$html = $this->load->view('admin/lead/rander_call_history_report_detail_ajax',$list,TRUE);	

		$status='success';    
		$msg='';
		if($arg['type']=='talked_call'){
			$title='Total Calls List';				
		}
		else if($arg['type']=='not_talked_call'){
			$title='Not Total Calls List';
		}
		else if($arg['type']=='unique_call'){
			$title='Unique Calls List';
		}
		else if($arg['type']=='outgoing_call'){
			$title='Outgoing Calls List';
		}
		else if($arg['type']=='incoming_call'){
			$title='Incoming Calls List';
		}
		else if($arg['type']=='missing_opportunities_call'){
			$title='Missing Opportunities Calls List';
		}
		else if($arg['type']=='new_lead_call'){
			$title='New Leads Created Calls List';
		}
		else if($arg['type']=='sales_service_call'){
			$title='Sales/ Service Calls List';
		}
		else if($arg['type']=='other_business_call'){
			$title='Business Calls List';
		}
		else{
			$title='Total Calls List';
		}
		
	    $data =array (
	       	"html"=>$html,
	       	"status"=>$status,
	       	"msg"=>$msg,
	       	"title"=>$title
	        );
        $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit();
	}
	// AJAX PAGINATION END
	// CALL LOG LEADS
	// =====================================
    	
    // =====================================
    // Product Quote
    public function add_product_quote_view_ajax()
	{
		$data=array();
		
		$is_mail_or_whatsapp=$this->input->post('is_mail_or_whatsapp');
		$data['product_list']=$this->Product_model->list_product_name();
		$data['is_mail_or_whatsapp']=$is_mail_or_whatsapp;
		$this->load->view('admin/lead/add_product_quote_view_ajax',$data);		
	}
	function add_product_quote_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$msg='';
			$company=get_company_profile();	
			$product_quote_arr=explode(',', $this->input->post('product_ids'));
			$lead_id=$this->input->post('lead_id');
			$is_mail_or_whatsapp=$this->input->post('is_mail_or_whatsapp');
			
			$lead_info=$this->lead_model->GetLeadData($lead_id);
			$quote_text='';
			if($is_mail_or_whatsapp=='whatsapp')
			{
				$quote_text .='*To,*<br>';
				$quote_text .='*'.$lead_info->cus_contact_person.'*<br>';
				$quote_text .=($lead_info->cus_company_name)?$lead_info->cus_company_name.', ':'';
				$quote_text .=($lead_info->cus_city)?$lead_info->cus_city.', ':'';
				$quote_text .=($lead_info->cus_state)?$lead_info->cus_state.', ':'';
				$quote_text .=($lead_info->cus_country)?$lead_info->cus_country.'<br><br>':'';

				$quote_text .='With reference to your enquiry dated '.date_db_format_to_display_format(date("Y-m-d")).', I am sharing the product price list for your reference:<br>';

				if(count($product_quote_arr))
				{
					$quote_text .='<br>---------------------------------<br>';
					foreach($product_quote_arr AS $p)
					{
						if($p)
						{
							$product_info=$this->Product_model->get_product($p);
							
							$quote_text .=($product_info['name'])?'*Product Name:* '.$product_info['name'].'<br>':'';
							
							$quote_text .=($product_info['price'])?'*Sales Price:* '.$product_info['currency_code'].' '.$product_info['price'].'<br>':'';
							$quote_text .=($product_info['price'])?'*Unit:* '.$product_info['unit'].' '.$product_info['unit_type_name'].'<br><br>':'';
							$quote_text .=($product_info['description'])?'*Description:*<br> '.strip_tags($product_info['description']).'<br>':'';
							// if(strtoupper($product_info['currency_code'])=='INR'){
							// 	$quote_text .='**GST: Extra';
							// }
							$quote_text .='---------------------------------<br>';
						}					
					}
				}
				$quote_text .='_Taxes will be applicable as per the norms._<br><br>';
				$quote_text .='Looking forward for your earliest reply.<br><br>';

				$quote_text .='Regards<br>';
				$quote_text .='*'.trim($lead_info->user_name).'*<br>';
				$quote_text .='*'.trim($company['name']).'*';

				$quote_text=str_replace('<br>','%0A<br>',$quote_text);
				$quote_text=str_replace('<br>',PHP_EOL,$quote_text);
				$quote_text=str_replace('?',':',$quote_text);
				$quote_text=str_replace('&','and',$quote_text);
			}
			else
			{
				$quote_text .='With reference to your enquiry dated '.date_db_format_to_display_format(date("Y-m-d")).', I am sharing the product price list for your reference:<br>';

				if(count($product_quote_arr))
				{
					$quote_text .='<br>--------------------------------------------------------<br>';
					foreach($product_quote_arr AS $p)
					{
						if($p)
						{
							$product_info=$this->Product_model->get_product($p);
							
							$quote_text .=($product_info['name'])?'<b>Product Name:</b> '.$product_info['name'].'<br>':'';
							// $quote_text .=($product_info['description'])?$product_info['description'].'<br><br>':'';
							$quote_text .=($product_info['price'])?'<b>Sales Price:</b> '.$product_info['currency_code'].' '.$product_info['price'].'<br>':'';
							$quote_text .=($product_info['price'])?'<b>Unit:</b> '.$product_info['unit'].' '.$product_info['unit_type_name'].'<br><br>':'';
							$quote_text .=($product_info['description'])?'<b>Description:</b><br> '.$product_info['description'].'<br>':'';
							// if(strtoupper($product_info['currency_code'])=='INR'){
							// 	$quote_text .='**GST: Extra';
							// }
							
							$quote_text .='--------------------------------------------------------<br>';
						}					
					}
				}

				$quote_text .='<i>Taxes will be applicable as per the norms.</i><br><br>';
				$quote_text .='Looking forward for your earliest reply.<br><br>';
			}
			

			
			$status_str='success';
	        $result["status"] = $status_str;
	        $result['msg']=$msg;
	        $result['quote_text']=$quote_text;
	        $this->output->set_content_type('application/json');
	        echo json_encode($result);
	        exit(0);
		}
	}
	// Product Quote
	// =====================================


	// =====================================
	// SEARCH PRODUCT
	public function search_product_view_ajax()
	{
		$list=array();	
		$get_option=
		// $list['group_list']=$this->Group_wise_category_model->get_list('0');
		$html=$this->load->view('admin/lead/search_product_view_ajax',$list,TRUE);		
        $result['html']=$html;
        $this->output->set_content_type('application/json');
        echo json_encode($result);
        exit(0);	
	}
	public function search_product_list_view_ajax()
	{
		$list=array();		
		$searchtype=$this->input->post('searchtype');
		$selected_p_ids=$this->input->post('selected_p_ids');
		$search_p_name='';
		$search_p_group='';
		$search_p_category='';
		if($searchtype=='keyword'){
			$search_p_name=$this->input->post('search_p_name');			
		}
		else if($searchtype=='category'){			
			$search_p_group=$this->input->post('search_p_group');
			$search_p_category=$this->input->post('search_p_category');
		}

		$arg=array();
		$arg['not_in_ids']=$selected_p_ids;
		$arg['search_product']='';
	    $arg['status']='0';	    
		$arg['filter_search_str']=$search_p_name;
		$arg['filter_group_id']=$search_p_group;
		$arg['filter_cate_id']=$search_p_category;
	    $arg['filter_aproved']='Y';
	    $arg['filter_disabled']='';
	    $arg['filter_disabled_reason']='';
	    $arg['filter_with_image']='';
	    $arg['filter_with_brochure']='';
	    $arg['filter_with_youtube_video']='';
	    $arg['filter_with_gst']='';
	    $arg['filter_with_hsn_code']='';	    
	    $arg['filter_product_available_for']='';
	    $arg['filter_sort_by']='';
		$arg['limit']=$this->Product_model->get_list_count($arg);
	    $arg['start']=0;
		$list['product_list']=$this->Product_model->get_list($arg);

		$html=$this->load->view('admin/lead/search_product_list_view_ajax',$list,TRUE);	
		$result['html']=$html;
		$this->output->set_content_type('application/json');
        echo json_encode($result);
        exit(0);	
	}
	// SEARCH PRODUCT
	// =====================================
	
    public function observer_remove_ajax()
	{
		$lead_id=$this->input->post('lid');	
		if($lead_id!='')
		{
			$updatedata=array();
	        $updatedata=array(
				'assigned_observer'=>NULL
			);
			$this->lead_model->UpdateLead($updatedata,$lead_id);
		}	
		$result['status']='success';
        echo json_encode($result);
        exit(0); 
	}
	
	function get_latest_lead_history_ajax()
    {	
        $lid=$this->input->post('lid'); 
		$latest_lead_history=$this->lead_model->get_latest_lead_history($lid);	
			
		$last_comment='';
		
		if($latest_lead_history['comment']){
		$last_comment .='<b>Comment:</b> <em>'.str_replace('&quot;','` ',str_replace('#','For ',strip_tags($latest_lead_history['comment']))).'</em>';
		}
		if($latest_lead_history['create_date']){
		$last_comment .='<br><b>Date:</b> <em>'.date("d-M-Y h:i:s A", strtotime($latest_lead_history['create_date'])).'</em>';
		}
		if($latest_lead_history['assigned_user_name']){
		$last_comment .='<br><b>Updated By:</b> <em>'.$latest_lead_history['assigned_user_name'].'</em>';
		}
		else{
			
		}
		echo $last_comment;
    }
	
	public function download_call_log_csv()
    {
		$session_data=$this->session->userdata('admin_session_data');
	    $list=array();
	    $arg=array();		
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
		 		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		   
	    // FILTER DATA	
	    $arg['filter_sort_by']=$this->input->get('filter_sort_by');
	    $arg['assigned_user']=($this->input->get('filter_assigned_user'))?$this->input->get('filter_assigned_user'):$tmp_u_ids_str;
	    $arg['filter_scr_year']=$this->input->get('filter_scr_year'); 
	    $arg['filter_scr_month']=$this->input->get('filter_scr_month');			
		$arg['filter_scr_from_date']=($this->input->get('filter_scr_from_date'))?date_display_format_to_db_format($this->input->get('filter_scr_from_date')):''; 
	    $arg['filter_scr_to_date']=($this->input->get('filter_scr_to_date'))?date_display_format_to_db_format($this->input->get('filter_scr_to_date')):'';	

	    $table = '';
	    $rows=$this->lead_model->get_sync_call_report_list($arg);
        
        $array[] = array('');
        $array[] = array(
                        'Date',
                        'Total Talk - (H:m:s)',
                        'Total Calls',
                        'Talked',
                        'Not Talked',
                        'Unique',
                        'Outgoing',
                        'Incoming',
						'Missing Opportunities',
						'New Leads Created',
                        'Sales/ Service',
                        'Business Call'
                        );
        
        if(count($rows) > 0)
        {
            foreach ($rows as $row) 
            {
				
				$date=($row->call_start)?date_db_format_to_display_format($row->call_start):'-';
				$total_talked_time_in_second=($row->total_talked_time_in_second)?gmdate("H:i:s", $row->total_talked_time_in_second):'-';
				$total_call_count=($row->total_call_count)?$row->total_call_count:'-';
				$talked_call_count=($row->talked_call_count)?$row->talked_call_count:'-';
				$not_talked_call_count=($row->not_talked_call_count)?$row->not_talked_call_count:'-';
				$unique_call_count=($row->unique_call_count)?$row->unique_call_count:'-';
				$outgoing_call_count=($row->outgoing_call_count)?$row->outgoing_call_count:'-';
				$incoming_call_count=($row->incoming_call_count)?$row->incoming_call_count:'-';
				$missing_opportunities_call_count=($row->missing_opportunities_call_count)?$row->missing_opportunities_call_count:'-';
				$new_leads_created_call_count=($row->new_leads_created_call_count)?$row->new_leads_created_call_count:'-';
				$sales_service_call_count=($row->sales_service_call_count)?$row->sales_service_call_count:'-';
				$other_business_count=($row->other_business_count)?$row->other_business_count:'-';

                $array[] = array(
                                $date,
                                $total_talked_time_in_second,
                                $total_call_count,
                                $talked_call_count,
                                $not_talked_call_count,
                                $unique_call_count,
                                $outgoing_call_count,
                                $incoming_call_count,	
								$missing_opportunities_call_count,
                                $new_leads_created_call_count,
                                $sales_service_call_count, 
                                $other_business_count
                                );
            }
        }

        $tmpName='call_log_list';
        $tmpDate =  date("YmdHis");
        $csvFileName = $tmpName."_".$tmpDate.".csv";

        $this->load->helper('csv');
        //query_to_csv($array, TRUE, 'Attendance_list.csv');
        array_to_csv($array, $csvFileName);
        return TRUE;
    }
	
	public function download_call_log_details_csv()
    {
		$session_data=$this->session->userdata('admin_session_data');
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
		 		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);

		$date=$this->input->get('date');
		$type=$this->input->get('type');
		$arg=array();
		$arg['date']=$date;
		$arg['type']=$type;
		$arg['assigned_user']=($this->input->get('filter_assigned_user'))?$this->input->get('filter_assigned_user'):$tmp_u_ids_str;
				
		$rows=$this->lead_model->get_call_history_report_detail_list($arg);
        
        $array[] = array('');
        $array[] = array(
						'Is Paying Customer?',
                        'Name',
                        'Contact Number',
                        'Call Type',
                        'Call Start',
                        'Call End',
                        'Total Call Time (H:m:s)',
                        'Assigned User',
                        'Comment'
                        );
        
        if(count($rows) > 0)
        {
            foreach ($rows as $row) 
            {
				
				$is_paying_customer=($row->is_paying_customer=='Y')?'Yes':'No';
				$name=($row->name)?$row->name:'-';
				$number=($row->number)?$row->number:'-';
				$bound_type=($row->bound_type)?ucwords($row->bound_type):'-';
				$call_start=($row->call_start)?datetime_db_format_to_display_format($row->call_start):'-';
				$call_end=($row->call_end)?datetime_db_format_to_display_format($row->call_end):'-';
				if($row->call_start!='' && $row->call_end!=''){
				  $date_a = new DateTime($row->call_start);
				  $date_b = new DateTime($row->call_end);
				  $interval = date_diff($date_a,$date_b);
				  $total_call_time=$interval->format('%h:%i:%s');
				}
				else{
				  $total_call_time='-';
				}  					
				$assigned_user_name=($row->assigned_user_name)?$row->assigned_user_name:'-';
				$status_wise_msg=($row->status_wise_msg)?$row->status_wise_msg:'-';
				
                $array[] = array(
                                $is_paying_customer,
                                $name,
                                $number,
                                $bound_type,
                                $call_start,
                                $call_end,
                                $total_call_time,
                                $assigned_user_name,	
								$status_wise_msg
                                );
            }
        }

        $tmpName='call_log_details_list';
        $tmpDate =  date("YmdHis");
        $csvFileName = $tmpName."_".$tmpDate.".csv";

        $this->load->helper('csv');
        //query_to_csv($array, TRUE, 'Attendance_list.csv');
        array_to_csv($array, $csvFileName);
        return TRUE;
    }
	
	function rander_html_for_quotation_sent_by_whatsapp_ajax()
    {
        $lid=$this->input->post('lid'); 
		$oppid=$this->input->post('oppid'); 
		$quotation_id=$this->input->post('qid'); 
		$is_quoted=$this->input->post('is_quoted');
		
		
		$lead_info=$this->lead_model->GetLeadData($lid);
		$e_data['lead_info']=$lead_info;
		$assigned_user_id=$lead_info->assigned_user_id;	
		$assigned_to_user_data=$this->user_model->get_employee_details($assigned_user_id);
		$e_data['assigned_to_user']=$assigned_to_user_data;
		$quotation_data=$this->quotation_model->GetQuotationData($quotation_id);
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$admin_session_data_user_data=$this->user_model->get_employee_details($user_id);
		//$e_data['admin_session_data_user_data']=$admin_session_data_user_data;
		$e_data['quotation']=$quotation_data['quotation'];
		//$e_data['lead_opportunity']=$quotation_data['lead_opportunity_data'];
		$e_data['company']=$quotation_data['company_log'];
		$e_data['customer']=$quotation_data['customer_log'];
		//$e_data['terms']=$quotation_data['terms_log_only_display_in_quotation'];
		//$e_data['product_list']=$quotation_data['product_list'];
		//$e_data['reply_email_body']=$reply_email_body;
		$template_str = $this->load->view('admin/lead/quotation_sent_by_whatsapp_view', $e_data, true);
		$result['recipient_mobile']='+'.$lead_info->cus_country_code.$lead_info->cus_mobile;
		
		if($is_quoted=='Y')
		{

			// ===================================================
			// Update Status
			if($quotation_data['lead_opportunity_data']['status']==1)
			{
				$lead_id=$lid;
				// UPDATE LEAD STAGE/STATUS
				$update_lead_data = array(
					'current_stage_id' =>'2',
					'current_stage' =>'QUOTED',
					'current_stage_wise_msg' =>'',
					// 'current_status_id'=>'2',
					// 'current_status'=>'HOT',
					'modify_date'=>date("Y-m-d")
				);								
				$this->lead_model->UpdateLead($update_lead_data,$lead_id);
				// Insert Stage Log

				// STAGE PROSPECT
				$stage_post_data=array(
						'lead_id'=>$lead_id,
						'stage_id'=>'8',
						'stage'=>'PROSPECT',
						'stage_wise_msg'=>'',
						'create_datetime'=>date("Y-m-d H:i:s")
					);
				$this->lead_model->CreateLeadStageLog($stage_post_data);

				// STAGE QUOTED
				$stage_post_data=array(
						'lead_id'=>$lead_id,
						'stage_id'=>'2',
						'stage'=>'QUOTED',
						'stage_wise_msg'=>'',
						'create_datetime'=>date("Y-m-d H:i:s")
					);
				$this->lead_model->CreateLeadStageLog($stage_post_data);
				// Insert Status Log
				$status_post_data=array(
						'lead_id'=>$lead_id,
						'status_id'=>'2',
						'status'=>'HOT',
						'create_datetime'=>date("Y-m-d H:i:s")
					);
				// $this->lead_model->CreateLeadStatusLog($status_post_data);
				
				$commnt="Quotation sent to client by WhatsApp";
				$ip=$_SERVER['REMOTE_ADDR'];
				$date=date("Y-m-d H:i:s");	
				$update_by=$this->session->userdata['admin_session_data']['user_id'];
				$comment_title=SENT_TO_CLIENT;
				$historydata=array('title'=>$comment_title,'lead_id'=>$lead_id,'lead_opportunity_id'=>$opportunity_id,'comment'=>$commnt,'create_date'=>$date,'user_id'=>$update_by,'ip_address'=>$ip);
				$this->history_model->CreateHistory($historydata);
				$post_data=array(
								'status'=>2,
								'modify_date'=>date("Y-m-d")
								);
				$this->Opportunity_model->UpdateLeadOportunity($post_data,$oppid);

				// Create KPI Log (Quotation Sent Count:4)
				create_kpi_log(4,$update_by,'',date("Y-m-d H:i:s"));

				// product tagged with quoted lead
				$prod_list=$this->quotation_model->GetQuotationProductList($quotation_id);
				if(count($prod_list))
				{
					foreach($prod_list AS $product)
					{	
						$p_name=get_value("name","product_varient","id=".$product->product_id);
						$lead_p_data=array(
							'lead_id'=>$lead_id,
							'lead_opportunity_id'=>$oppid,
							'quotation_id'=>$quotation_id,
							'name'=>$p_name,
							'product_id'=>$product->product_id,
							'tag_type'=>'Q',
							'created_at'=>date("Y-m-d H:i:s")
						);
						$this->lead_model->CreateLeadWiseProductTag($lead_p_data);
					}
				}
				// --------------------
			}
			// update status
			// =================================================== 
		}
        $result["html"] = $template_str;
        echo json_encode($result);
        exit(0);        
	}

	public function send_quotation_popup($id='')
	{	
		if($id){
			$data=array();
			$data['flag']='';	
			$user_id=$this->session->userdata['admin_session_data']['user_id'];			
			$data['lead_id']=$id;		
			// $data['cus_data']=$this->lead_model->GetLeadData($id);
			// $data['opportunity_list']=$this->opportunity_model->GetOpportunityListAll($id);
			// $data['currency_list']=$this->Product_model->GetCurrencyList();
			$data['cus_data']=$this->lead_model->GetLeadDataForQuotationPopup($id);			
			$data['opportunity_list']=$this->opportunity_model->GetOpportunityListAllForQuotationPopup($id);		
			$data['currency_list']=$this->Product_model->GetCurrencyListForQuotationPopup();
			$l_data['lead_id']=$id;
			$data['user_id']=$user_id;		
			$this->load->view('admin/lead/send_quotation_popup_view',$data);			
		}
		else{
			die("ID should not be blank !!!!");
		}
		
	}

	public function lead_title_desc_edit_view_rander_ajax()
	{
		$data=NULL;
		$lead_id=$this->input->post('lead_id');	
		$data['lead_id']=$lead_id;		
		$data['lead_data']=$this->lead_model->GetLeadData($lead_id);	
		$this->load->view('admin/lead/lead_title_desc_edit_modal_ajax',$data);
	}

	function get_lead_product_select2_autocomplete()
	{
		// if(!isset($_GET['searchTerm'])){ 
		// 	$json = [];
		// }
		// else
		// {
			$search_str = $_GET['searchTerm'];						
			$rows=$this->Product_model->GetOnlyProductList($search_str);			
			$json = [];
			foreach($rows AS $row)
			{
				$json[] = ['id'=>$row['name'].'~'.$row['id'], 'text'=>$row['name']];
			}
		// }	
		echo json_encode($json);
		exit;
	}

	function get_product_select2_autocomplete()
	{
		// if(!isset($_GET['searchTerm'])){ 
		// 	$json = [];
		// }
		// else
		// {
			$search_str = $_GET['searchTerm'];						
			$rows=$this->Product_model->GetOnlyProductList($search_str);			
			$json = [];
			foreach($rows AS $row)
			{
				$json[] = ['id'=>$row['id'], 'text'=>$row['name']];
			}
		// }	
		echo json_encode($json);
		exit;
	}

	

	// ==============================================================
	// note
	function rander_note_html()
    {
		$session_data=$this->session->userdata('admin_session_data');
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);		
		
		$form_id=$this->input->post('id');
        $lead_id = $this->input->post('lead_id');
		$parent_nid = $this->input->post('nid');		
		$nids='';
		if($parent_nid)
		{
			$nids=$parent_nid;
			$note_ids_arr=array();
			array_push($note_ids_arr,$parent_nid);
			$note_ids_arr=$this->lead_model->get_under_note_ids($parent_nid,$lead_id);
			if(count($note_ids_arr))
			{
				$nids=implode(",",$note_ids_arr);
				$nids=$parent_nid.','.$nids;
			}			
		}
		
		$list['id']=$form_id;
		$list['lead_id']=$lead_id;
		
		// $list['note_list']=$this->lead_model->get_note_tree($lead_id);

		$arg=array();
		$arg['self_and_under_user_id']='';
		$arg['lead_id']=$lead_id;
		$arg['nids']=$nids;
		$noteArray=$this->lead_model->get_note_rows($arg);
        $rows = array();
        if(count($noteArray))
        {
            $i=0;
            foreach($noteArray as $id=>$cats)
            {                    
                $rows[$i]=array(
								'id'=>$id,
								'note'=>$cats['note'],
								'parent_id'=>$cats['parent_id'],
								'parent_note'=>$cats['parent_note'],
								'created_at'=>$cats['created_at'], 
								'user_name'=>$cats['user_name'],
								'form_id'=>$form_id,
								'lead_id'=>$lead_id
								);
                $i++;                    
            }
        }	
		$list['note_list_html'] = $this->common_functions->rander_note_html($rows);		
		$html = $this->load->view('admin/lead/note_view_ajax',$list,TRUE);
		
		$return=$this->lead_model->update_note_seen_status($user_id,$lead_id);
		$is_seen_updated=($return==true)?'Y':'N';
        $result['html']=$html;
		$result['is_seen_updated']=$is_seen_updated;
        echo json_encode($result);
        exit(0);        
	}

	public function add_note_ajax()
	{
		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];	
		$parentid=$this->input->post('parentid');	
		$lead_id=$this->input->post('lead_id');
		$note=$this->input->post('note');
		
		$return='';
		if($lead_id!='' && $note!='')
		{
			$to_user_id=0;
			$is_seen='Y';
			if($parentid!='' && $parentid>0)
			{
				$to_user_id=get_value("user_id","lead_wise_note","id=".$parentid);
				$is_seen='N';
			}
			
			$post=array(
					'parent_id'=>$parentid,
					'lead_id'=>$lead_id,
					'user_id'=>$user_id,
					'to_user_id'=>$to_user_id,
					'is_seen'=>$is_seen,
					'note'=>$note,
					'created_at'=>date("Y-m-d H:i:s")
					);
			$return=$this->lead_model->add_note($post);			
			if($return)
			{
				$msg='Record successfully added';
				$status='success';
			}	
			else
			{
				$msg='System fail to add record';
				$status='fail';
			}
		}
		else
		{
			$msg='All fields required';
			$status='fail';
		}

		$arg=array();
		$arg['self_and_under_user_id']='';
		$arg['lead_id']=$lead_id;
		$note_list=$this->lead_model->get_note_rows($arg);
	    $data =array (
		   "msg"=>$msg,
		   "status"=>$status,
		   "id"=>$return,
		   "note_count"=>count($note_list),
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	public function note()
	{
		$data=array();
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
   		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		$data['user_list']=$this->user_model->GetUserList($tmp_u_ids_str);
		// $note_ids_arr=$this->lead_model->get_under_note_ids('35');
		// echo $nids=implode(",",$note_ids_arr);die();
		$this->load->view('admin/lead/manage_note_view',$data);
	}
	// AJAX PAGINATION START
	function get_note_list_ajax()
	{
		$session_data=$this->session->userdata('admin_session_data');
	    $start = $this->input->get('page');
	    $view_type = $this->input->get('view_type');
		$filter_by_keyword = $this->input->get('filter_by_keyword');
		$filter_note_added_by = $this->input->get('filter_note_added_by');
		$filter_lead_assign_to = $this->input->get('filter_lead_assign_to');
		$filter_note_from_date = $this->input->get('filter_note_from_date');
	    $filter_note_to_date = $this->input->get('filter_note_to_date');
		$filter_show_all_unread_reply = $this->input->get('filter_show_all_unread_reply');

		

	    $this->load->library('pagination');
	    
	    $config = array();
	    $list=array();
	    $arg=array();
		$arg['filter_sort_by']=$this->input->get('filter_sort_by');
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		   
	    // FILTER DATA	
		$arg['self_and_under_user_id']=$tmp_u_ids_str;	
	    $arg['filter_sort_by']=$this->input->get('filter_sort_by');	     
		$arg['parent_id']='0';
		$arg['filter_by_keyword']=$filter_by_keyword;
		$arg['filter_note_added_by']=$filter_note_added_by;
		$arg['filter_lead_assign_to']=$filter_lead_assign_to;
		$arg['filter_note_from_date']=$filter_note_from_date;
		$arg['filter_note_to_date']=$filter_note_to_date;

	   //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->lead_model->get_note_list_count($arg);	    
	    if($filter_show_all_unread_reply=='Y'){
			$limit=$config['total_rows'];
		}
		else{
			$limit=30;
		}
	    $config['per_page'] = $limit;
	    $config['uri_segment']=4;
	    $config['num_links'] = 1;
	    $config['use_page_numbers'] = TRUE;
	   //$config['full_tag_open'] = '<div id="paginator" class="dataTables_paginate paging_bootstrap">';
	    $config['attributes'] = array('class' => 'myclass_note','data-viewtype'=>$view_type);
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
	    $table = '';
	    $list['rows']=$this->lead_model->get_note_list($arg);	
	    if($view_type=='grid')
	    {	    	
	    	// $table = $this->load->view('admin/order/payment_followup_group_view_ajax',$list,TRUE);
	    }
	    else
	    {	    	
	    	$table = $this->load->view('admin/lead/manage_note_view_ajax',$list,TRUE);
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
	// note
	// ==============================================================

	public function change_source_ajax()
	{
		
		$company_id=$this->input->post('c_id');
		$currsource=$this->input->post('currsource');
		$lead_id=$this->input->post('lid');
		$lead_info=$this->lead_model->GetLeadData($lead_id);
		$list=array();		
        $list['currsource']=$currsource;
        $list['company_id']=$company_id;
        $list['lead_id']=$lead_id;       
        $list['source_list']=$this->source_model->GetSourceListAll();
    	$html = $this->load->view('admin/lead/change_source_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0); 
		
	}
	public function update_lead_source_ajax()
    {
    	$lead_id = $this->input->post('lead_id');
    	$source_id=$this->input->post('lead_source');
		$company_id = $this->input->post('company_id'); 
        $lead_info=$this->lead_model->GetLeadData($lead_id);
		$old_source_id=$lead_info->source_id;
        $assigned_by_user_id=$this->session->userdata['admin_session_data']['user_id'];

		$updatedata=array();
		$updatedata=array(
						'source_id'=>$source_id,
						'modify_date'=>date("Y-m-d")
						);
		$this->lead_model->UpdateLead($updatedata,$lead_id);

		$old_lead_source=get_value("name","source","id=".$old_source_id);
		$new_lead_source=get_value("name","source","id=".$source_id);

		// =========================
		// HISTORY CREATE	
		$update_by=$this->session->userdata['admin_session_data']['user_id'];	
		$ip_addr=$_SERVER['REMOTE_ADDR'];				
		$message="Lead Source has been changed from ".$old_lead_source."(Old) to ".$new_lead_source."(New)";
		$comment_title=LEAD_SOURCE_CHANGE;
		$historydata=array(
					'title'=>$comment_title,
					'lead_id'=>$lead_id,
					'comment'=>addslashes($message),
					'attach_file'=>'',
					'create_date'=>date("Y-m-d H:i:s"),
					'user_id'=>$update_by,
					'ip_address'=>$ip_addr
						);
		$this->history_model->CreateHistory($historydata);
		// HISTORY CREATE
		// =========================

    	$status_str='success';  
        $result["status"] = $status_str;
        $result["return"]='';
        echo json_encode($result);
        exit(0); 
    }

	public function change_closer_date_lead_value_ajax()
    {
    	//is_admin_session_data();
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$lead_id=trim($this->input->post('cddv_lead_id'));
			$lead_closer_date=trim($this->input->post('lead_closer_date'));
			$lead_deal_value=trim($this->input->post('lead_deal_value'));
			$lead_currency_code=trim($this->input->post('lead_currency_code'));
			
            $flag=0;
			if($lead_id=='')
			{
				$result["status"] ='error' ;
				$result['msg']='Lead id is missing.';
				echo json_encode($result);
				exit(0);
			}

			if($lead_closer_date==''){
				$flag++;				
			}

			if($lead_deal_value==''){
				$flag++;
			}			
			
			if($flag==2)
			{
				$result["status"] ='error' ;
				$result['msg']='Closer date / Deal Value both are missing.';
				echo json_encode($result);
				exit(0);
			}
			
						
			$data_post=array(
							'closer_date'=>date_display_format_to_db_format($lead_closer_date),
							'deal_value'=>$lead_deal_value,
							'deal_value_currency_code'=>$lead_currency_code,
							'modify_date'=>date('Y-m-d')
							);
			$this->lead_model->UpdateLead($data_post,$lead_id);			  
			$result["status"] ='success' ;
			$result['msg']="Closer date/ Deal value successfully updated.";
			echo json_encode($result);
			exit(0);

		}	
		
    }
	public function change_lead_status_ajax()
	{		
		$company_id=$this->input->post('c_id');
		$lead_id=$this->input->post('lid');
		$lead_info=$this->lead_model->GetLeadData($lead_id);
		$list=array();		
        $list['company_id']=$company_id;
        $list['lead_id']=$lead_id;       
		$list['curr_status_id']=get_value("current_status_id","lead","id=".$lead_id);
        $list['status_list']=$this->lead_model->GetAllOppStatus();
    	$html = $this->load->view('admin/lead/change_lead_status_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0); 
		
	}

	public function update_lead_status_ajax()
    {
    	$lead_id=$this->input->post('lead_id');
    	$status_id=$this->input->post('status_id');        
        $status=get_value("name","opportunity_status","id=".$status_id);
		$old_status=get_value("current_status","lead","id=".$lead_id);
       	if($lead_id!='' && $status_id!='')
       	{
			// lead update
			$updatedata=array();
			$updatedata=array(
							'current_status_id'=>$status_id,
							'current_status'=>$status,
							'modify_date'=>date("Y-m-d")
							);
			$this->lead_model->UpdateLead($updatedata,$lead_id);

			// Insert Status Log
			$status_post_data=array();
			$status_post_data=array(
					'lead_id'=>$lead_id,
					'status_id'=>$status_id,
					'status'=>$status,
					'create_datetime'=>date("Y-m-d H:i:s")
				);
			$this->lead_model->CreateLeadStatusLog($status_post_data);

			// =========================
			// HISTORY CREATE			
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$ip_addr=$_SERVER['REMOTE_ADDR'];				
			$message="Lead Status changed from ".$old_status."(Old) to ".$status."(New)";
			$comment_title=LEAD_STATUS_CHANGE;
			$historydata=array(
						'title'=>$comment_title,
						'lead_id'=>$lead_id,
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
		$status_str='success';
    	$status_str='success';  
        $result["status"] = $status_str;
        echo json_encode($result);
        exit(0); 
    }

	// =======================================================================
	// MEETING

	public function meeting_scheduled_list_by_date_ajax()
	{
		
		$selected_meeting_date=$this->input->post('selected_meeting_date');	
		$selected_user_id=$this->input->post('selected_user_id');
		$user_name=get_value("name","user","id=".$selected_user_id);
		$list=array();		
        $list['selected_meeting_date']=date_db_format_to_display_format($selected_meeting_date);        
        $list['selected_user_id']=$selected_user_id;
		$list['selected_user_name']=$user_name;
		

		$arg=array();       
		$arg['user_id']=$selected_user_id; 
		$arg['date']=$selected_meeting_date;                          
		$list['meeting_list_by_date']=$this->meeting_model->GetListBydate($arg);        
    	$html = $this->load->view('admin/lead/meeting_others_appointment_dates_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0); 		
	}

	public function meeting_schedule_view_popup_rander_ajax()
	{
		$lead_id=$this->input->post('lead_id');	
		$c_id=$this->input->post('c_id');
		$m_id=$this->input->post('m_id');	
		$lead_info=$this->lead_model->GetLeadData($lead_id);
		$assigned_observer=$lead_info->assigned_observer;

		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
		$manager_id=get_value("manager_id","user","id=".$user_id);	
		$manager_skip_id=0;
		$manager_skip2_id=0;
		$manager_skip3_id=0;
		// $manager_skip4_id=0;		
		if($manager_id>0){
			$manager_skip_id=get_value("manager_id","user","id=".$manager_id); // immidiate manager
			if($manager_skip_id>0){
				$manager_skip2_id=get_value("manager_id","user","id=".$manager_skip_id); 
				if($manager_skip2_id>0){
					$manager_skip3_id=get_value("manager_id","user","id=".$manager_skip2_id); 
					// if($manager_skip3_id>0){
					// 	$manager_skip4_id=get_value("manager_id","user","id=".$manager_skip3_id); 
					// }
				}				
			}			
		}
		else{
			// $manager_skip_id=$user_id;
			$manager_id=$user_id;
		}		
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($manager_id,0);
		if($user_id==1){
			array_push($tmp_u_ids, $user_id);
		}   	

		if($manager_id>0){
			array_push($tmp_u_ids, $manager_id);
		}
		if($manager_skip_id>0){
			array_push($tmp_u_ids, $manager_skip_id);
		}
		if($manager_skip2_id>0){
			array_push($tmp_u_ids, $manager_skip2_id);
		}
		if($manager_skip3_id>0){
			array_push($tmp_u_ids, $manager_skip3_id);
		}
		// if($manager_skip4_id>0){
		// 	array_push($tmp_u_ids, $manager_skip4_id);
		// }
		if($assigned_observer){
			array_push($tmp_u_ids, $assigned_observer);
		}
   		$tmp_u_ids_str=implode(",", $tmp_u_ids);	

		$meeting_info=array();
		if($m_id){
			$meeting_info=$this->meeting_model->GetRow($m_id);
		}
		$list=array();		
        $list['lead_info']=$lead_info;  
		$list['meeting_info']=$meeting_info;      
        $list['lead_id']=$lead_id;     
		$list['c_id']=$c_id;    
		$list['id']=$m_id;
		$list['cus_data']=$this->customer_model->GetCustomerData($c_id);
		$list['contact_persion_list']=$this->customer_model->customer_wise_contact_person_list($c_id);   
		$list['meeting_agenda_type_list']=$this->meeting_model->GetMeetingAgendaTypeRows();     
		$list['user_list']=$this->user_model->GetUserList($tmp_u_ids_str);      
    	$html = $this->load->view('admin/lead/meeting_schedule_view_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0); 		
	}

	function meeting_validate_form_data()
	{ 
		$this->load->library('form_validation');
		$this->form_validation->set_rules('meeting_type', 'Meeting method', 'trim|required');
		if ($this->form_validation->run() == TRUE)
		{
		  return TRUE;
		}
		else
		{
		  return FALSE;
		}
	}
	public function meeting_add_edit_ajax()
    {
    	//is_admin_session_data();
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$user_id=$this->session->userdata['admin_session_data']['user_id'];
			$meeting_assigned_user_id=trim($this->input->post('meeting_assigned_user_id'));
			$id=trim($this->input->post('id'));
			$lead_id=trim($this->input->post('lead_id'));
			$customer_id=trim($this->input->post('c_id'));
			$selected_meeting_date_display_format=strtoupper(trim($this->input->post('selected_meeting_date')));
			$meeting_method=strtoupper(trim($this->input->post('meeting_type')));
			$meeting_schedule_start_time=trim($this->input->post('meeting_schedule_start_time'));
			$meeting_schedule_end_time=trim($this->input->post('meeting_schedule_end_time'));
			// $meeting_with_before_checkin_time=trim($this->input->post('meeting_with_before_checkin_time'));
			$meeting_with_before_checkin_time=trim($this->input->post('meeting_with_before_checkin_time_selected'));
			$meeting_agenda_type_id=trim($this->input->post('meeting_agenda_type_id'));
			$meeting_Purpose=trim($this->input->post('meeting_Purpose'));
			$online_meeting_platform=strtoupper(trim($this->input->post('online_meeting_platform')));
			$online_meeting_meeting_url=trim($this->input->post('online_meeting_meeting_url'));
			$meeting_venue=trim($this->input->post('meeting_venue'));
			$meeting_venue_latitude=trim($this->input->post('meeting_venue_latitude'));
			$meeting_venue_longitude=trim($this->input->post('meeting_venue_longitude'));
			
			if($this->meeting_validate_form_data() == TRUE)
			{
				if($selected_meeting_date_display_format=='')
				{
					$result["status"] ='error' ;
					$result['msg']='Select meeting date.';
					echo json_encode($result);
					exit(0);
				}

				if($meeting_method=='')
				{
					$result["status"] ='error' ;
					$result['msg']='Select meeting method.';
					echo json_encode($result);
					exit(0);
				}
				if($meeting_schedule_start_time=='')
				{
					$result["status"] ='error' ;
					$result['msg']='Meeting start time should not be blank.';
					echo json_encode($result);
					exit(0);
				}
				if($meeting_schedule_end_time=='')
				{
					$result["status"] ='error' ;
					$result['msg']='Meeting end time should not be blank.';
					echo json_encode($result);
					exit(0);
				}
				if(date("H:i:s", strtotime($meeting_schedule_start_time))>=date("H:i:s", strtotime($meeting_schedule_end_time)))
				{
					$result["status"] ='error' ;
					$result['msg']='Meeting end time should not be less than or equal to start time.';
					echo json_encode($result);
					exit(0);
				}
				if($meeting_assigned_user_id=='')
				{
					$result["status"] ='error' ;
					$result['msg']='Meeting Assigne To should not be blank.';
					echo json_encode($result);
					exit(0);
				}

				if($meeting_with_before_checkin_time=='')
				{
					$result["status"] ='error' ;
					$result['msg']='Meeting with should not be blank.';
					echo json_encode($result);
					exit(0);
				}
				if($meeting_agenda_type_id=='')
				{
					$result["status"] ='error' ;
					$result['msg']='Select meeting type.';
					echo json_encode($result);
					exit(0);
				}
				if($meeting_Purpose=='')
				{
					$result["status"] ='error' ;
					$result['msg']='Remarks should not be blank.';
					echo json_encode($result);
					exit(0);
				}

				if($meeting_method=='P')
				{
					if($meeting_venue=='')
					{
						$result["status"] ='error' ;
						$result['msg']='Venue should not be blank.';
						echo json_encode($result);
						exit(0);
					}
				}

				if($meeting_method=='O')
				{
					// if($online_meeting_platform=='O')
					// {
						// if($online_meeting_meeting_url=='')
						// {
						// 	$result["status"] ='error' ;
						// 	$result['msg']='Online meeting URL should not be blank.';
						// 	echo json_encode($result);
						// 	exit(0);
						// }
					// }
				}	
				
				$meeting_with_name_str='';
				$meeting_with_email_str='';			
				if($meeting_with_before_checkin_time){
					$meeting_with_before_checkin_time_arr=explode(",",$meeting_with_before_checkin_time);
					if(count($meeting_with_before_checkin_time_arr))
					{
						foreach($meeting_with_before_checkin_time_arr AS $val){
							$val_arr=explode("#",$val);
							$meeting_with_name_str .=$val_arr[0].',';
							$meeting_with_email_str .=$val_arr[1].',';							
						}
						$meeting_with_name_str=rtrim($meeting_with_name_str, ',');
						$meeting_with_email_str=rtrim($meeting_with_email_str, ',');
					}					
				}


				$selected_meeting_date=date_display_format_to_db_format($selected_meeting_date_display_format);
				$meeting_schedule_start_datetime_tmp=$selected_meeting_date.' '.date("H:i:s", strtotime($meeting_schedule_start_time));
				$meeting_schedule_end_datetime_tmp=$selected_meeting_date.' '.date("H:i:s", strtotime($meeting_schedule_end_time));


				

				$status_id=1;
                $status=get_value("name","meeting_status","id=".$status_id);
				if($id!='') // reshedule
				{			
					// $status_id=5;
                	// $status=get_value("name","meeting_status","id=".$status_id);		
					$post_data=array(						
						'user_id'=>$meeting_assigned_user_id,
						'meeting_type'=>$meeting_method,
						'meeting_agenda_type_id'=>$meeting_agenda_type_id,
						'customer_id'=>$customer_id,
						'meeting_with_before_checkin_time'=>$meeting_with_name_str,
						'lead_id'=>$lead_id,
						'meeting_schedule_start_datetime'=>$meeting_schedule_start_datetime_tmp,
						'meeting_schedule_end_datetime'=>$meeting_schedule_end_datetime_tmp,
						'meeting_Purpose'=>$meeting_Purpose,
						'meeting_venue'=>$meeting_venue,
						'meeting_venue_latitude'=>$meeting_venue_latitude,
						'meeting_venue_longitude'=>$meeting_venue_longitude,
						'meeting_url_type'=>$online_meeting_platform,
						'meeting_url'=>$online_meeting_meeting_url,
						// 'status_id'=>$status_id,
						// 'status'=>$status,
						'updated_at'=>date("Y-m-d H:i:s")
						);								
					$return=$this->meeting_model->update($post_data,$id);
					// if($return==true)
					// {
					// 	$post_data_log=array(
					// 		'meeting_id'=>$id,
					// 		'status_id'=>$status_id,
					// 		'status'=>$status,
					// 		'created_at'=>date("Y-m-d H:i:s")
					// 		);
					// 	$this->meeting_model->addStatusLog($post_data_log);
					// }	
				}
				else
				{
					// $status_id=1;
                	// $status=get_value("name","meeting_status","id=".$status_id);
					$post_data=array(
						'meeting_scheduled_by_user_id'=>$user_id,
						'user_id'=>$meeting_assigned_user_id,
						'meeting_type'=>$meeting_method,
						'meeting_agenda_type_id'=>$meeting_agenda_type_id,
						'customer_id'=>$customer_id,
						'meeting_with_before_checkin_time'=>$meeting_with_name_str,
						'lead_id'=>$lead_id,
						'meeting_schedule_start_datetime'=>$meeting_schedule_start_datetime_tmp,
						'meeting_schedule_end_datetime'=>$meeting_schedule_end_datetime_tmp,
						'meeting_Purpose'=>$meeting_Purpose,
						'meeting_venue'=>$meeting_venue,
						'meeting_venue_latitude'=>$meeting_venue_latitude,
						'meeting_venue_longitude'=>$meeting_venue_longitude,
						'meeting_url_type'=>$online_meeting_platform,
						'meeting_url'=>$online_meeting_meeting_url,
						'status_id'=>$status_id,
						'status'=>$status,
						'created_at'=>date("Y-m-d H:i:s"),
						'updated_at'=>date("Y-m-d H:i:s")
						);						
					$return=$this->meeting_model->add($post_data);
					if($return!=false)
					{
						$post_data_log=array(
							'meeting_id'=>$return,
							'status_id'=>$status_id,
							'status'=>$status,
							'created_at'=>date("Y-m-d H:i:s")
							);
						$this->meeting_model->addStatusLog($post_data_log);
					}	
				}

				$meeting_assigned_user_name=get_value("name","user","id=".$meeting_assigned_user_id);
				$meeting_agenda_type=get_value("name","meeting_agenda_type","id=".$meeting_agenda_type_id);
				$company=$this->customer_model->get_company_detail($customer_id);
				// =========================
				// HISTORY CREATE
				$update_by=$this->session->userdata['admin_session_data']['user_id'];
				$ip_addr=$_SERVER['REMOTE_ADDR'];				
				$message ="";
				$message .=($company['company_name'])?"Company Name: ".$company['company_name']:"Company Name: N/A";
				$message .=($meeting_method=='P')?" | Field Meeting":" | Online Meeting";
				$message .=" | Meeting Assignd to: ".$meeting_assigned_user_name;
				$message .=" | Meeting With: ".$meeting_with_name_str;
				$message .=" | Meeting Type: ".$meeting_agenda_type;
				$message .=" | Purpose of Meeting: ".$meeting_Purpose;
				$message .=($meeting_method=='P')?' | Venue: '.$meeting_venue:' | Online Meeting URL: '.$online_meeting_meeting_url;
				$message .=" | Meeting schedule start: ".datetime_db_format_to_display_format_ampm($meeting_schedule_start_datetime_tmp);
				$message .=" | Meeting schedule end: ".datetime_db_format_to_display_format_ampm($meeting_schedule_end_datetime_tmp);
				$message .=" | Status: ".$status;
				$comment_title=($id)?MEETING_RESHEDULE:MEETING_ADD;
				$historydata=array(
							'title'=>$comment_title,
							'lead_id'=>$lead_id,
							'comment'=>addslashes($message),
							'attach_file'=>'',
							'create_date'=>date("Y-m-d H:i:s"),
							'user_id'=>$update_by,
							'ip_address'=>$ip_addr
								);
				$this->history_model->CreateHistory($historydata);
				// HISTORY CREATE
				// =========================

				$session_data=$this->session->userdata('admin_session_data');
   				$user_id=$session_data['user_id'];
				$meeting_assigned_user_email=get_value("email","user","id=".$meeting_assigned_user_id);
				$logged_in_user_email=get_value("email","user","id=".$user_id);

				$email_txt='';
				$email_txt .='Dear Sir,<br><br>';
				$email_txt .='Thanks for the meeting appointment. Please find the meeting details bellow:<br>';
				$email_txt .='<b>Meeting Date and Time:</b> '.datetime_db_format_to_display_format_ampm($meeting_schedule_start_datetime_tmp).'<br>';				
				$email_txt .=($meeting_method=='P')?"<b>Meeting Type:</b> Field Meeting<br>":"<b>Meeting Type:</b> Online Meeting<br>";
				$email_txt .=($meeting_method=='P')?'<b>Meeting Venue:</b> '.$meeting_venue.'<br>':'<b>Online Meeting URL:</b> '.$online_meeting_meeting_url.'<br>';
				$email_txt .='<br><br>Regards<br>'.$meeting_assigned_user_name;


				$result["txt"] =$email_txt; 
				$result["mail_subject"] ='Meeting Appointment @ '.datetime_db_format_to_display_format_ampm($meeting_schedule_start_datetime_tmp).' - '.$meeting_schedule_end_time; 
				$result["mail_to"] =$meeting_with_email_str;
				$result["mail_form"] =$meeting_assigned_user_email.','.$logged_in_user_email;
				$result["status"] ='success' ;
				$result['msg']="Meeting successfully saved.";
				echo json_encode($result);
				exit(0);
			}
			else
			{
				// $this->form_validation->set_error_delimiters('', '');
	        	// $msg = validation_errors(); // 'duplicate';
	            // $this->session->set_flashdata('error_msg', $msg);
				$result["status"] ='error' ;
				$result['msg']='Select meeting method.';
				echo json_encode($result);
				exit(0);
			}
		}	
		
    }
	public function manage_meeting()
	{		
		$data=array();		
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		// $user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
   		$tmp_u_ids_str=implode(",", $tmp_u_ids); 
		$data['meeting_list']=$this->meeting_model->GetListByUserForEventCalendar($tmp_u_ids_str,$user_id);
		$meeting_list_obj = json_encode ($data['meeting_list']);
		$data['meeting_list_obj']=$meeting_list_obj;
		$this->load->view('admin/lead/meeting_view',$data);		
	}

	public function rander_calendar_view_ajax()
	{
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		// $user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
   		$tmp_u_ids_str=implode(",", $tmp_u_ids); 
		$data['meeting_list']=$this->meeting_model->GetListByUserForEventCalendar($tmp_u_ids_str,$user_id);
		$meeting_list_obj = json_encode ($data['meeting_list']);
		$list['meeting_list_obj']=$meeting_list_obj;
    	// $html = $this->load->view('admin/lead/calendar_view_ajax',$list,TRUE);
		$result['meeting_list_obj'] = $meeting_list_obj;
        echo json_encode($result);
        exit(0); 		
	}

	public function meeting_detail_with_edit_view_popup_rander_ajax()
	{
		$list=array();	
		$id=$this->input->post('m_id');	
		// $status=$this->input->post('status');
		$list['row']=$this->meeting_model->GetRow($id);
		$assigned_observer=$list['row']['assigned_observer'];
		// $session_data=$this->session->userdata('admin_session_data');
   		// $user_id=$session_data['user_id'];   		
		// $manager_id=get_value("manager_id","user","id=".$user_id);			
		// if($manager_id>0){
		// 	$manager_skip_id=get_value("manager_id","user","id=".$manager_id);
		// }
		// else{
		// 	$manager_skip_id=$user_id;
		// }		
		// $tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($manager_skip_id,0);
		// if($user_id==1){
		// 	array_push($tmp_u_ids, $user_id);
		// }   		
		// if($assigned_observer){
		// 	array_push($tmp_u_ids, $assigned_observer);
		// }
		// $tmp_u_ids_str=implode(",", $tmp_u_ids);		
		
		$list['id']=$id;
		$list['status']=$list['row']['status_id'];
		$c_id=$list['row']['customer_id'];
		$list['user_list']=$this->user_model->GetUserList(''); 		
		$list['cus_data']=$this->customer_model->GetCustomerData($c_id);
		$list['contact_persion_list']=$this->customer_model->customer_wise_contact_person_list($c_id);
    	$html = $this->load->view('admin/lead/meeting_detail_with_edit_view_popup_rander_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0); 		
	}

	public function meeting_single_field_edit_ajax()
    {    	
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{						
			$field_name=trim($this->input->post('field_name'));
			$id=trim($this->input->post('id'));
			if($id=='')
			{
				$result["status"] ='error' ;
				$result['msg']='id is missing.';
				echo json_encode($result);
				exit(0);
			}
			
			if($field_name=='meeting_venue'){
				$meeting_venue=trim($this->input->post('meeting_venue'));
				$post_data=array(
					'meeting_venue'=>$meeting_venue,
					'updated_at'=>date("Y-m-d H:i:s")
				);
			}
			if($field_name=='meeting_url'){
				$meeting_url=trim($this->input->post('meeting_url'));
				$post_data=array(
					'meeting_url'=>$meeting_url,
					'updated_at'=>date("Y-m-d H:i:s")
				);
			}
			if($field_name=='meeting_schedule_start_datetime'){
				$meeting_schedule_start_time=get_value("meeting_schedule_start_datetime","meeting","id=".$id);
				$meeting_schedule_end_time=get_value("meeting_schedule_end_datetime","meeting","id=".$id);

				$meeting_schedule_start_time_tmp=date("H:i:s",strtotime($meeting_schedule_start_time));
				$meeting_schedule_end_time_tmp=date("H:i:s",strtotime($meeting_schedule_end_time));

				$meeting_schedule_start_datetime=date_display_format_to_db_format(trim($this->input->post('meeting_schedule_start_datetime')));
				$meeting_schedule_start_datetime_tmp=$meeting_schedule_start_datetime.' '.$meeting_schedule_start_time_tmp;
				$meeting_schedule_end_datetime_tmp=$meeting_schedule_start_datetime.' '.$meeting_schedule_end_time_tmp;
				
				
				$post_data=array(
					'meeting_schedule_start_datetime'=>$meeting_schedule_start_datetime_tmp,
					'meeting_schedule_end_datetime'=>$meeting_schedule_end_datetime_tmp,
					'updated_at'=>date("Y-m-d H:i:s")
				);
			}
			if($field_name=='meeting_schedule_start_time'){
				$meeting_schedule_start_date=get_value("meeting_schedule_start_datetime","meeting","id=".$id);
				$meeting_schedule_start_date_tmp=date("Y-m-d",strtotime($meeting_schedule_start_date));

				$meeting_schedule_start_time=time_display_format_to_db_format_ampm(trim($this->input->post('meeting_schedule_start_time')));
				$meeting_schedule_end_time=time_display_format_to_db_format_ampm(trim($this->input->post('meeting_schedule_end_time')));

				$meeting_schedule_start_datetime_tmp=$meeting_schedule_start_date_tmp.' '.$meeting_schedule_start_time;
				$meeting_schedule_end_datetime_tmp=$meeting_schedule_start_date_tmp.' '.$meeting_schedule_end_time;				
				
				$post_data=array(
					'meeting_schedule_start_datetime'=>$meeting_schedule_start_datetime_tmp,
					'meeting_schedule_end_datetime'=>$meeting_schedule_end_datetime_tmp,
					'updated_at'=>date("Y-m-d H:i:s")
				);
			}
			$this->meeting_model->update($post_data,$id);
			// $mstatus=get_value("status","meeting","id=".$id);
			$mstatusId=get_value("status_id","meeting","id=".$id);
			$result["mstatus"]=$mstatusId;
			$result["status"]='success' ;
			$result['msg']="Meeting successfully saved.";
			echo json_encode($result);
			exit(0);
		}
    }

	public function meeting_cancelled_ajax()
    {    	
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$id=trim($this->input->post('id'));
			$cancellation_reason=trim($this->input->post('cancellation_reason'));
			if($id=='')
			{
				$result["status"] ='error' ;
				$result['msg']='id is missing.';
				echo json_encode($result);
				exit(0);
			}
			if($cancellation_reason=='')
			{
				$result["status"] ='error' ;
				$result['msg']='Reason For Cancellation is missing.';
				echo json_encode($result);
				exit(0);
			}
			$status_id=4;
			$status=get_value("name","meeting_status","id=".$status_id);
			$post_data=array(
				'status_id'=>$status_id,
				'status'=>$status,
				'cancellation_reason'=>$cancellation_reason,                    
				'updated_at'=>date("Y-m-d H:i:s")
				);  
			$last_id=$this->meeting_model->update($post_data,$id);
			if($last_id===true)
			{
				$post_data_log=array(
					'meeting_id'=>$last_id,
					'status_id'=>$status_id,
					'status'=>$status,
					'cancellation_reason'=>$cancellation_reason,
					'created_at'=>date("Y-m-d H:i:s")
					);
				$this->meeting_model->addStatusLog($post_data_log);

				// =========================
				// HISTORY CREATE
				$lead_id=get_value("lead_id","meeting","id=".$id);
				$customer_id=get_value("customer_id","meeting","id=".$id);
				$company=$this->customer_model->get_company_detail($customer_id);
				$update_by=$this->session->userdata['admin_session_data']['user_id'];
				$ip_addr=$_SERVER['REMOTE_ADDR'];				
				$message ="";
				$message .=($company['company_name'])?"Company Name: ".$company['company_name']:"Company Name: N/A";
				$message .=" | Meeting Status: ".$status;									
				$message .=" | Cancellation Reason: ".$cancellation_reason;				
				$comment_title=MEETING_CANCEL;
				$historydata=array(
							'title'=>$comment_title,
							'lead_id'=>$lead_id,
							'comment'=>addslashes($message),
							'attach_file'=>'',
							'create_date'=>date("Y-m-d H:i:s"),
							'user_id'=>$update_by,
							'ip_address'=>$ip_addr
								);
				$this->history_model->CreateHistory($historydata);
				// HISTORY CREATE
				// =========================

				$result["mstatus"]=strtolower($status) ;
				$result["status"]='success' ;
				$result['msg']="Meeting status successfully updated.";
				echo json_encode($result);
				exit(0);
			}
			else
			{
				$result["status"] ='error' ;
				$result['msg']='Unlnown error! Please try again later.';
				echo json_encode($result);
				exit(0);
			}
		}
    }

	public function meeting_finished_ajax()
    {    	
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$id=trim($this->input->post('id'));
			$checkin_date=date_display_format_to_db_format(trim($this->input->post('checkin_date')));
			$checkin_time=trim($this->input->post('checkin_time'));
			$checkout_time=trim($this->input->post('checkout_time'));
			$meeting_with_at_checkin_time=trim($this->input->post('meeting_with_at_checkin_time'));
			$self_visited_or_visited_with_colleagues=trim($this->input->post('self_visited_or_visited_with_colleagues'));
			$visited_colleagues=trim($this->input->post('visited_colleagues_selected'));
			$discussion_points=trim($this->input->post('discussion_points'));

			// $result["status"] ='error' ;
			// $result['msg']=$visited_colleagues;
			// echo json_encode($result);
			// exit(0);
			if($id=='')
			{
				$result["status"] ='error' ;
				$result['msg']='id is missing.';
				echo json_encode($result);
				exit(0);
			}
			if($checkin_time=='')
			{
				$result["status"] ='error' ;
				$result['msg']='Check-in Time is missing.';
				echo json_encode($result);
				exit(0);
			}
			if($checkout_time=='')
			{
				$result["status"] ='error' ;
				$result['msg']='Check-out Time is missing.';
				echo json_encode($result);
				exit(0);
			}
			
			if(date("H:i:s", strtotime($checkin_time))>=date("H:i:s", strtotime($checkout_time)))
			{
				$result["status"] ='error' ;
				$result['msg']='Check-out Time should not be less than or equal to Check-in Time.';
				echo json_encode($result);
				exit(0);
			}
			if($meeting_with_at_checkin_time=='')
			{
				$result["status"] ='error' ;
				$result['msg']='Meeting With is missing.';
				echo json_encode($result);
				exit(0);
			}
			if($self_visited_or_visited_with_colleagues=='')
			{
				$result["status"] ='error' ;
				$result['msg']='Self visit/ visited with other  is missing.';
				echo json_encode($result);
				exit(0);
			}
			else
			{
				if(strtoupper($self_visited_or_visited_with_colleagues)=='C')
				{
					if($visited_colleagues==''){
						$result["status"] ='error' ;
						$result['msg']='Visited with colleague is missing.';
						echo json_encode($result);
						exit(0);
					}
				}
			}
			if($discussion_points=='')
			{
				$result["status"] ='error' ;
				$result['msg']='Discussion Points is missing.';
				echo json_encode($result);
				exit(0);
			}

			$status_id=3;
			$status=get_value("name","meeting_status","id=".$status_id);
			// $meeting_schedule_start_datetime=get_value("meeting_schedule_start_datetime","meeting","id=".$id);
			// $meeting_schedule_start_date=date("Y-m-d", strtotime($meeting_schedule_start_datetime));
			$checkin_datetime=$checkin_date.' '.date("H:i:s", strtotime($checkin_time));
			$checkout_datetime=$checkin_date.' '.date("H:i:s", strtotime($checkout_time));	
			$get_user_name='';		
			if($self_visited_or_visited_with_colleagues=='S'){
				$visited_colleagues='';
			}
			else{
				if($visited_colleagues){
					$get_user_name=$this->meeting_model->get_user_name_by_id($visited_colleagues);
				}
			}
			$post_data=array(
				'status_id'=>$status_id,
				'status'=>$status,
				'checkin_datetime'=>$checkin_datetime,   
				'checkout_datetime'=>$checkout_datetime,              
				'meeting_with_at_checkin_time'=>$meeting_with_at_checkin_time,  
				'self_visited_or_visited_with_colleagues'=>$self_visited_or_visited_with_colleagues,   
				'visited_colleagues'=>$get_user_name,              
				'discussion_points'=>$discussion_points,                    
				'updated_at'=>date("Y-m-d H:i:s")
				);  
			$last_id=$this->meeting_model->update($post_data,$id);
			if($last_id===true)
			{
				$post_data_log=array(
					'meeting_id'=>$last_id,
					'status_id'=>$status_id,
					'status'=>$status,
					'cancellation_reason'=>$cancellation_reason,
					'created_at'=>date("Y-m-d H:i:s")
					);
				$this->meeting_model->addStatusLog($post_data_log);

				if($visited_colleagues){
					$visited_colleagues_arr=explode(",",$visited_colleagues);
					if(count($visited_colleagues_arr))
					{
						foreach($visited_colleagues_arr AS $val){
							$post_data_u=array(
								'meeting_id'=>'1',
								'user_id'=>$val
								);
								$this->meeting_model->addMeetingVisitedUser($post_data_u);
						}
					}					
				}


				
				// =========================
				// HISTORY CREATE
				$lead_id=get_value("lead_id","meeting","id=".$id);
				$customer_id=get_value("customer_id","meeting","id=".$id);
				$company=$this->customer_model->get_company_detail($customer_id);
				$update_by=$this->session->userdata['admin_session_data']['user_id'];
				$ip_addr=$_SERVER['REMOTE_ADDR'];				
				$message ="";
				$message .=($company['company_name'])?"Company Name: ".$company['company_name']:"Company Name: N/A";	
				$message .=" | Meeting Status: ".$status;							
				$message .=" | Check-in datetime: ".datetime_db_format_to_display_format_ampm($checkin_datetime);
				$message .=" | Check-out datetime: ".datetime_db_format_to_display_format_ampm($checkout_datetime);
				$message .=" | Meeting With: ".$meeting_with_at_checkin_time;
				$message .=($self_visited_or_visited_with_colleagues=='S')?" | Self visit":" | Visited With: ".$get_user_name;
				$message .=" | Discussion Points:: ".$discussion_points;
				$comment_title=MEETING_DISPOSE;
				$historydata=array(
							'title'=>$comment_title,
							'lead_id'=>$lead_id,
							'comment'=>addslashes($message),
							'attach_file'=>'',
							'create_date'=>date("Y-m-d H:i:s"),
							'user_id'=>$update_by,
							'ip_address'=>$ip_addr
								);
				$this->history_model->CreateHistory($historydata);
				// HISTORY CREATE
				// =========================

				$result["mstatus"]=strtolower($status) ;
				$result["status"]='success' ;
				$result['msg']="Meeting status successfully updated.";
				echo json_encode($result);
				exit(0);
			}
			else
			{
				$result["status"] ='error' ;
				$result['msg']='Unlnown error! Please try again later.';
				echo json_encode($result);
				exit(0);
			}
		}
    }

	function rander_meeting_report_view_popup_ajax()
    {        
    	$data=array();    	
        $lead_id = $this->input->post('lead_id');
		$data['status_list']=$this->meeting_model->GetMeetingStatus();
		$data['purpose_list']=$this->meeting_model->GetMeetingAgendaType();
		$data['user_list']=$this->user_model->GetUserListAll();
		$data['lead_id']=($lead_id)?$lead_id:'';
		$data['meeting_checkin_date']=($this->input->post("meeting_checkin_date"))?date_db_format_to_display_format($this->input->post("meeting_checkin_date")):'';
		$data['meeting_checkout_date']=($this->input->post("meeting_checkout_date"))?date_db_format_to_display_format($this->input->post("meeting_checkout_date")):'';
		$data['meeting_user_id']=($this->input->post("meeting_user_id"))?$this->input->post("meeting_user_id"):'';
		$data['meeting_status']=($this->input->post("meeting_user_id"))?'3':'';
        echo $this->load->view('admin/lead/meeting_report_view_popup_ajax',$data,true);
    }

	function rander_meeting_report_list_ajax()
    { 
		$session_data=$this->session->userdata('admin_session_data');
	    $start = $this->input->get('page');
	   
	    $status='success';
		$msg='';
	    $this->load->library('pagination');
	    $limit=30;
	    $config = array();
	    $list=array();
	    $arg=array();		
		$user_id=$session_data['user_id'];
   		// $user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		   
	    // FILTER DATA		
		$arg['user_id']=$user_id; 
		$arg['filter_sort_by']=$this->input->get('filter_sort_by'); 
		$arg['filter_by_lead_id']=$this->input->get('filter_by_lead_id');
		$arg['filter_by_keyword']=$this->input->get('filter_by_keyword'); 
		$arg['filter_by_user_id']=($this->input->get('filter_by_user_id'))?$this->input->get('filter_by_user_id'):$tmp_u_ids_str; 
		$arg['filter_by_status_id']=$this->input->get('filter_by_status_id');
		$arg['filter_by_meeting_type']=$this->input->get('filter_by_meeting_type');
		$arg['filter_by_meeting_agenda_type_id']=$this->input->get('filter_by_meeting_agenda_type_id');
		$arg['filter_by_self_visited_or_visited_with_colleagues']=$this->input->get('filter_by_self_visited_or_visited_with_colleagues');
		$arg['filter_by_start_date']=($this->input->get('filter_by_start_date'))?date_display_format_to_db_format($this->input->get('filter_by_start_date')):'';
		$arg['filter_by_end_date']=($this->input->get('filter_by_end_date'))?date_display_format_to_db_format($this->input->get('filter_by_end_date')):'';
		if($arg['filter_by_start_date']!='' && $arg['filter_by_end_date']!=''){
			if($arg['filter_by_end_date']<$arg['filter_by_start_date']){
				$status='fail';
				$msg='Start date should not be less than end date.';
			}
		}
	    $config['base_url'] ='JavaScript:void(0)';
	    $config['total_rows'] = $this->meeting_model->GetMeetingReportCount($arg);		
		// $config['total_rows'] = 30;
	    $config['per_page'] = $limit;
	    $config['uri_segment']=4;
	    $config['num_links'] = 1;
	    $config['use_page_numbers'] = TRUE;
	    $config['attributes'] = array('class' => 'meeting_pagination_class','data-viewtype'=>$view_type);	    
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
    	$list['rows']=$this->meeting_model->GetMeetingReport($arg);		
	    $table = '';		    
	    $table = $this->load->view('admin/lead/meeting_report_list_ajax',$list,true);
		// PAGINATION COUNT INFO SHOW: START
		$tmp_start=($start+1);		
		$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
	    $page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
		// PAGINATION COUNT INFO SHOW: END
	    $data =array (
		   "status"=>$status,
		   "msg"=>$msg,
	       "table"=>$table,
	       "page"=>$page_link,
		   "page_record_count_info"=>$page_record_count_info
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
    }
	public function download_meeting_report_csv()
    {
		$session_data=$this->session->userdata('admin_session_data');
		$arg=array();		
		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		// FILTER DATA	
		$arg['user_id']=$user_id; 	
		$arg['filter_sort_by']=$this->input->get('filter_sort_by'); 
		$arg['filter_by_keyword']=$this->input->get('filter_by_keyword'); 
		$arg['filter_by_user_id']=($this->input->get('filter_by_user_id'))?$this->input->get('filter_by_user_id'):$tmp_u_ids_str; 
		$arg['filter_sort_by']=$this->input->get('filter_sort_by'); 
		$arg['filter_by_lead_id']=$this->input->get('filter_by_lead_id');
		$arg['filter_by_keyword']=$this->input->get('filter_by_keyword'); 		
		$arg['filter_by_status_id']=$this->input->get('filter_by_status_id');
		$arg['filter_by_meeting_type']=$this->input->get('filter_by_meeting_type');
		$arg['filter_by_meeting_agenda_type_id']=$this->input->get('filter_by_meeting_agenda_type_id');
		$arg['filter_by_self_visited_or_visited_with_colleagues']=$this->input->get('filter_by_self_visited_or_visited_with_colleagues');
		$arg['filter_by_start_date']=($this->input->get('filter_by_start_date'))?date_display_format_to_db_format($this->input->get('filter_by_start_date')):'';
		$arg['filter_by_end_date']=($this->input->get('filter_by_end_date'))?date_display_format_to_db_format($this->input->get('filter_by_end_date')):'';
		

	    // $config['base_url'] ='JavaScript:void(0)';
	    $total_rows = $this->meeting_model->GetMeetingReportCount($arg);
		$arg['limit']=$total_rows;
	    $arg['start']=0;
    	$rows=$this->meeting_model->GetMeetingReport($arg);	
        $array[] = array('');
        $array[] = array(
						'Lead ID',
						'Company Name',
						'City',
						'State',
						'Country',
                        'Meeting schedule date',
						'Meeting date',
                        'Status',
                        'Meeting By',
                        'Mode',
                        'Check-in',
						'Check-out',
						'Purpose of Meeting',
                        'Meeting with',
                        'Venue',                        
						'Visited with',
						'Disposition Comments',
						'Meeting Updated on',
						'Location'
                        );
        
        if(count($rows) > 0)
        {
            foreach ($rows as $row) 
            {
				if($row['status_id']=='4'){
					$disposition_comments=($row['cancellation_reason'])?$row['cancellation_reason']:'N/A';
				} 
				else{
					$disposition_comments=($row['discussion_points'])?$row['discussion_points']:'N/A';
				}

				$array[] = array(
								($row['lead_id'])?$row['lead_id']:'N/A',
								($row['cust_company_name'])?$row['cust_company_name']:'N/A',
								($row['cust_city_name'])?$row['cust_city_name']:'N/A',
								($row['cust_state_name'])?$row['cust_state_name']:'N/A',
								($row['cust_country_name'])?$row['cust_country_name']:'N/A',
								($row['meeting_schedule_start_datetime']!='0000-00-00 00:00:00')?date_db_format_to_display_format($row['meeting_schedule_start_datetime']):'N/A',
								($row['checkin_datetime'])?date_db_format_to_display_format($row['checkin_datetime']):'N/A',
                                ($row['status'])?$row['status']:'N/A',
                                $row['user'],
                                ($row['meeting_type']=='P')?'Visit':'Online',
								($row['checkin_datetime'])?time_db_format_to_display_format_ampm($row['checkin_datetime']):'N/A',
                                ($row['checkout_datetime'])?time_db_format_to_display_format_ampm($row['checkout_datetime']):'N/A',
                                ($row['meeting_Purpose'])?$row['meeting_Purpose']:'N/A',
								($row['self_visited_or_visited_with_colleagues']=='S')?'Self':$row['visited_colleagues'],
                                ($row['meeting_type']=='P')?($row['meeting_venue'])?$row['meeting_venue']:'N/A':'N/A',                                
								($row['meeting_with_at_checkin_time'])?$row['meeting_with_at_checkin_time']:'N/A',
                                $disposition_comments,
								($row['updated_at'])?date_db_format_to_display_format($row['updated_at']):'N/A',
								($row['meeting_dispose_latitude'])?$row['meeting_dispose_latitude'].'-'.$row['meeting_dispose_longitude']:'N/A',
                                );
            }
        }
		
        $tmpName='meeting_report';
        $tmpDate =  date("YmdHis");
        $csvFileName = $tmpName."_".$tmpDate.".csv";
        $this->load->helper('csv');
        //query_to_csv($array, TRUE, 'Attendance_list.csv');
        array_to_csv($array, $csvFileName);
        return TRUE;
    }
	// MEETING
	// =======================================================================
	
}
?>