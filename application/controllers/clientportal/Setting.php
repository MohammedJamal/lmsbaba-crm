<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Setting extends CI_Controller {	
	private $api_access_token = '';
	function __construct()
	{
		parent :: __construct();
		is_admin_logged_in();
		init_admin_element();         
		$this->load->model(array("Setting_model","countries_model","states_model","cities_model","menu_model","user_model","Indiamart_setting_model","quotation_model","Gmail_for_sync_setting_model","Tradeindia_setting_model","Email_forwarding_setting_model","Justdial_setting_model","Currency_model","Sms_forwarding_setting_model","Sms_setting_model","department_model","Website_setting_model","Aajjo_setting_model","Whatsapp_setting_model","Whatsapp_forwarding_setting_model","Fb_setting_model","Exporterindia_setting_model"));
		
		// permission checking
		// if(is_permission_available($this->session->userdata('service_wise_menu')[0]['menu_list']['menu_keyword'])===false){ 
		// 	redirect(admin_url().'dashboard', 'refresh');
		// 	exit(0);
		// }
		// end
	}

	function get_im_credentials()
	{
		$id=$this->input->get('id');
		$row=$this->Indiamart_setting_model->GetIndiamartCredentialsDetails($id);
		
		$return['is_old_version'] = $row['is_old_version'];
		$return['account_name'] = $row['account_name'];
		$return['glusr_mobile'] = $row['glusr_mobile'];
		$return['glusr_mobile_key'] = $row['glusr_mobile_key'];
		$return['assign_to'] = unserialize($row['assign_to']);
		$return['assign_rule'] = $row['assign_rule'];

		echo json_encode($return);
		exit(0);
	}

	function delete_im()
	{
		$del_id=$this->input->get('id');
		$this->Indiamart_setting_model->DeleteIndiamartCredentials($del_id);
		$assign_rule=get_value("assign_rule","indiamart_setting","id=".$del_id);
		if($assign_rule!=1)
		{
			$this->Indiamart_setting_model->DeleteIndiamartCredentialsDetails($del_id);
		}

		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}

	
	
	function add_edit_indiamart_credentials()
    {
		$indiamart_setting_id = $this->input->post('indiamart_setting_id'); 
		$is_old_version = $this->input->post('is_old_version'); 
		$indiamart_account_name = $this->input->post('indiamart_account_name'); 
    	$indiamart_glusr_mobile = $this->input->post('indiamart_glusr_mobile'); 
		$indiamart_glusr_mobile_key = $this->input->post('indiamart_glusr_mobile_key');
		$assign_rule = $this->input->post('assign_rule');

		$indiamart_assign_to = $this->input->post('indiamart_assign_to');




		 
		if($assign_rule==1) // round-robin
		{
			if($is_old_version!='' && $indiamart_account_name!='' && $indiamart_glusr_mobile!='' && $indiamart_glusr_mobile_key!='' && count($indiamart_assign_to)>0)
			{
				$im_data=array(
					"is_old_version"=>$is_old_version,
					"account_name"=>$indiamart_account_name,
					"glusr_mobile"=>$indiamart_glusr_mobile,
					"glusr_mobile_key"=>$indiamart_glusr_mobile_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>serialize($indiamart_assign_to),
					"assign_start"=>0					
					);

				if($indiamart_setting_id!='')
				{		
					// --------------------------------- 
					$existing_assign_rule=get_value('assign_rule','indiamart_setting','id='.$indiamart_setting_id);	
					if($existing_assign_rule!=1)
					{
						$this->Indiamart_setting_model->DeleteIndiamartCredentialsDetails($indiamart_setting_id);
					}
					// ---------------------------------
					$this->Indiamart_setting_model->EditIndiamartCredentials($im_data,$indiamart_setting_id);
				}
				else
				{				
					$this->Indiamart_setting_model->AddIndiamartCredentials($im_data);
				}
				
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==2) // country
		{
			$rule_count = (int)$this->input->post('rule_count');
			$rule_activity_count = (int)$this->input->post('rule_activity_count');


			if($is_old_version!='' && $indiamart_account_name!='' && $indiamart_glusr_mobile!='' && $indiamart_glusr_mobile_key!='' && $rule_count>0 && $rule_activity_count>0)
			{

				$im_data=array(
					"is_old_version"=>$is_old_version,
					"account_name"=>$indiamart_account_name,
					"glusr_mobile"=>$indiamart_glusr_mobile,
					"glusr_mobile_key"=>$indiamart_glusr_mobile_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);

				if($indiamart_setting_id!='')
				{				
					$this->Indiamart_setting_model->EditIndiamartCredentials($im_data,$indiamart_setting_id);

					$this->Indiamart_setting_model->DeleteIndiamartCredentialsDetails($indiamart_setting_id);
					
				}
				else
				{				
					$indiamart_setting_id=$this->Indiamart_setting_model->AddIndiamartCredentials($im_data);
					
				}			
				

				if($indiamart_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('country_2_'.$i);
						$assign_to_arr = $this->input->post('indiamart_assign_to_2_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$im_details=array(
								"im_setting_id"=>$indiamart_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Indiamart_setting_model->AddIndiamartCredentialsDetails($im_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('indiamart_assign_to_2_other');
					$im_details=array(
								"im_setting_id"=>$indiamart_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Indiamart_setting_model->AddIndiamartCredentialsDetails($im_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==3) // state
		{
			$rule_count = (int)$this->input->post('rule_count');
			$rule_activity_count = (int)$this->input->post('rule_activity_count');


			if($is_old_version!='' && $indiamart_account_name!='' && $indiamart_glusr_mobile!='' && $indiamart_glusr_mobile_key!='' && $rule_count>0 && $rule_activity_count>0)
			{

				$im_data=array(
					"is_old_version"=>$is_old_version,
					"account_name"=>$indiamart_account_name,
					"glusr_mobile"=>$indiamart_glusr_mobile,
					"glusr_mobile_key"=>$indiamart_glusr_mobile_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);

				if($indiamart_setting_id!='')
				{				
					$this->Indiamart_setting_model->EditIndiamartCredentials($im_data,$indiamart_setting_id);

					$this->Indiamart_setting_model->DeleteIndiamartCredentialsDetails($indiamart_setting_id);
					
				}
				else
				{				
					$indiamart_setting_id=$this->Indiamart_setting_model->AddIndiamartCredentials($im_data);
					
				}			
				

				if($indiamart_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('state_3_'.$i);
						$assign_to_arr = $this->input->post('indiamart_assign_to_3_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$im_details=array(
								"im_setting_id"=>$indiamart_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Indiamart_setting_model->AddIndiamartCredentialsDetails($im_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('indiamart_assign_to_3_other');
					$im_details=array(
								"im_setting_id"=>$indiamart_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Indiamart_setting_model->AddIndiamartCredentialsDetails($im_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==4) // city
		{
			$rule_count = (int)$this->input->post('rule_count');
			$rule_activity_count = (int)$this->input->post('rule_activity_count');


			if($is_old_version!='' && $indiamart_account_name!='' && $indiamart_glusr_mobile!='' && $indiamart_glusr_mobile_key!='' && $rule_count>0 && $rule_activity_count>0)
			{

				$im_data=array(
					"is_old_version"=>$is_old_version,
					"account_name"=>$indiamart_account_name,
					"glusr_mobile"=>$indiamart_glusr_mobile,
					"glusr_mobile_key"=>$indiamart_glusr_mobile_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);

				if($indiamart_setting_id!='')
				{				
					$this->Indiamart_setting_model->EditIndiamartCredentials($im_data,$indiamart_setting_id);

					$this->Indiamart_setting_model->DeleteIndiamartCredentialsDetails($indiamart_setting_id);
					
				}
				else
				{				
					$indiamart_setting_id=$this->Indiamart_setting_model->AddIndiamartCredentials($im_data);
					
				}			
				

				if($indiamart_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('city_4_'.$i);
						$assign_to_arr = $this->input->post('indiamart_assign_to_4_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$im_details=array(
								"im_setting_id"=>$indiamart_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Indiamart_setting_model->AddIndiamartCredentialsDetails($im_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('indiamart_assign_to_4_other');
					$im_details=array(
								"im_setting_id"=>$indiamart_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Indiamart_setting_model->AddIndiamartCredentialsDetails($im_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==5) // keyword
		{
			$rule_count = (int)$this->input->post('rule_count');
			$rule_activity_count = (int)$this->input->post('rule_activity_count');


			if($is_old_version!='' && $indiamart_account_name!='' && $indiamart_glusr_mobile!='' && $indiamart_glusr_mobile_key!='' && $rule_count>0 && $rule_activity_count>0)
			{

				$im_data=array(
					"is_old_version"=>$is_old_version,
					"account_name"=>$indiamart_account_name,
					"glusr_mobile"=>$indiamart_glusr_mobile,
					"glusr_mobile_key"=>$indiamart_glusr_mobile_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);

				if($indiamart_setting_id!='')
				{				
					$this->Indiamart_setting_model->EditIndiamartCredentials($im_data,$indiamart_setting_id);

					$this->Indiamart_setting_model->DeleteIndiamartCredentialsDetails($indiamart_setting_id);
					
				}
				else
				{				
					$indiamart_setting_id=$this->Indiamart_setting_model->AddIndiamartCredentials($im_data);
					
				}			
				

				if($indiamart_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = explode(',',$this->input->post('keyword_5_'.$i));
						$assign_to_arr = $this->input->post('indiamart_assign_to_5_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$im_details=array(
								"im_setting_id"=>$indiamart_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Indiamart_setting_model->AddIndiamartCredentialsDetails($im_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('indiamart_assign_to_5_other');
					$im_details=array(
								"im_setting_id"=>$indiamart_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Indiamart_setting_model->AddIndiamartCredentialsDetails($im_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}

    	 
        $result["status"] = $status_str;       
        $result["html"]='';
        echo json_encode($result);
        exit(0); 
    }
	public function company($id='')
    {   		
		$data['error_msg'] 			= "";
   	 	$data['page_title'] 		= "Company Details";
		$data['page_keyword'] 		= "Company Details";
		$data['page_description']   = "Company Details"; 
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{			
			if ($this->company_validate_form_data() == TRUE)
			{	

				$id=$this->input->post('edit_id');
				$existing_profile_image = $this->input->post('existing_profile_image');

				$GetCompanyData=$this->Setting_model->GetCompanyData();
				
				$user_data=array(
							"name"=>$this->input->post('name'),
							"address"=>$this->input->post('address'),
							"city_id"=>$this->input->post('city_id'),
							"state_id" => $this->input->post('state_id'),
							"country_id"=>$this->input->post('country_id'),
							"pin"=>$this->input->post('pin'),
							"about_company"=>$this->input->post('about_company'),
							"gst_number"=>$this->input->post('gst_number'),
							"pan_number"=>$this->input->post('pan_number'),
							"default_currency"=>$this->input->post('default_currency'),
							"ceo_name"=>$this->input->post('ceo_name'),
							"contact_person"=>$this->input->post('contact_person'),
							"email1"=>$this->input->post('email1'),
							"email2"=>$this->input->post('email2'),
							"mobile1"=>$this->input->post('mobile1'),
							"mobile2"=>$this->input->post('mobile2'),
							"phone1"=>$this->input->post('phone1'),
							"phone2"=>$this->input->post('phone2'),
							"website"=>$this->input->post('website'),
							"quotation_cover_letter_body_text"=>$this->input->post('quotation_cover_letter_body_text'),
							"quotation_terms_and_conditions"=>$this->input->post('quotation_terms_and_conditions'),
							"quotation_cover_letter_footer_text"=>$this->input->post('quotation_cover_letter_footer_text'),
							"quotation_bank_details1"=>$this->input->post('quotation_bank_details1'),
							"quotation_bank_details2"=>$this->input->post('quotation_bank_details2'),
							"authorized_signatory"=>$this->input->post('authorized_signatory'),
							//"is_cronjobs_auto_regretted_on"=>($this->input->post('is_cronjobs_auto_regretted_on'))?'Y':'N',
							//"auto_regretted_day_interval"=>$this->input->post('auto_regretted_day_interval'),
							//"bank_credit_to"=>$this->input->post('bank_credit_to'),
							//"bank_name"=>$this->input->post('bank_name'),
							//"bank_acount_number"=>$this->input->post('bank_acount_number'),
							//"bank_branch_name"=>$this->input->post('bank_branch_name'),
							//"bank_branch_code"=>$this->input->post('bank_branch_code'),
							//"bank_ifsc_code"=>$this->input->post('bank_ifsc_code'),
							//"bank_swift_number"=>$this->input->post('bank_swift_number'),
							//"bank_telex"=>$this->input->post('bank_telex'),
							//"bank_address"=>$this->input->post('bank_address'),
							//"correspondent_bank_name"=>$this->input->post('correspondent_bank_name'),
							//"correspondent_bank_swift_number"=>$this->input->post('correspondent_bank_swift_number'),
							//"correspondent_account_number"=>$this->input->post('correspondent_account_number'),
							//"indiamart_glusr_mobile"=>$this->input->post('indiamart_glusr_mobile'),
							//"indiamart_glusr_mobile_key"=>$this->input->post('indiamart_glusr_mobile_key'),
							//"indiamart_assign_to"=>serialize($this->input->post('indiamart_assign_to')),
			                "updated_at"=>date('Y-m-d H:i:s')
							);
				
				$this->Setting_model->UpdateCompany($user_data,$id);	

				$this->load->library('upload', $config);
				$this->load->library('image_lib',''); //initialize image library

				if($_FILES['image']['tmp_name'])
				{
					$image_filename=NULL;					
					$config = array(
								   'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/",
								   'allowed_types' => "gif|jpg|png|jpeg",
								   'overwrite' => TRUE,
								   'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
								   // 'max_height' => "1768",
								   // 'max_width' => "10024"
								    );

					//$this->load->library('upload', $config);
				    //$this->load->library('image_lib',''); //initialize image library
				    $this->upload->initialize($config);
				    if (!$this->upload->do_upload('image')) 
				    {
				        $error = $this->upload->display_errors();
				        print_r($error);
						exit;
				        //$this->load->view('upload_form', $error);
				    } 
				    else
				    {
				    	$image['image_upload']=array('upload_data' => $this->upload->data()); //Image Upload							   
						$image_filename=$image['image_upload']['upload_data']['file_name']; //Image Name
											
						$config=NULL;

						$config['source_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/".$image_filename;
						$config['new_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/thumb/".$image_filename;
						$config['height']	= '120';
						
						$this->image_lib->initialize($config); 
						$this->image_lib->resize();

						$user_data_img=array(
		                 				'logo'=>$image_filename,
						 				'updated_at'=>date('Y-m-d H:i:s')
						 				);
						$result=$this->Setting_model->UpdateCompany($user_data_img,$id);
						
						
			   			//#############################################################//    
				        //############## DELETE EXISTING IMAGE IF ANY #################//
				        // if($existing_profile_image!="")
				        // {  
				        // 	@unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/company/logo/'.$existing_profile_image);
				        //     @unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/company/logo/thumb/'.$existing_profile_image);
				        // }
				        //#############################################################//
				    }
				}


				if($_FILES['digital_signature']['tmp_name'])
				{
					$image_filename=NULL;			
					$config=array();		
					$config = array(
				   'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/",
				   'allowed_types' => "gif|jpg|png|jpeg",
				   'overwrite' => FALSE,
				   'max_size' => "2048000", 
				   'encrypt_name'=>true,
				   // Can be set to particular file size , here it is 2 MB(2048 Kb)
								   // 'max_height' => "1768",
								   // 'max_width' => "10024"
					);

					
				    $this->upload->initialize($config);
				    if (!$this->upload->do_upload('digital_signature')) 
				    {
				        $error = $this->upload->display_errors();
				        print_r($error);
						exit;
				        //$this->load->view('upload_form', $error);
				    } 
				    else
				    {
				    	$image['image_upload']=array('upload_data' => $this->upload->data()); 
				    	//Image Upload
						$image_filename=$image['image_upload']['upload_data']['file_name']; //Image Name
						$user_data_img=array(
         				'digital_signature'=>$image_filename,
		 				'updated_at'=>date('Y-m-d H:i:s')
		 				);
						$result=$this->Setting_model->UpdateCompany($user_data_img,$id);
						
						
			   			//#############################################################//    
				        //############## DELETE EXISTING IMAGE IF ANY #################//
				        if($GetCompanyData['company_setting']!="")
				         {  
				         	@unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/company/logo/'.$GetCompanyData['company_setting']);
				        
				        }
				        //#############################################################//
				    }
				}				

				// --------------------------
				// reset company info session
				$this->session->unset_userdata('company_info','');
				$company_info=$this->Setting_model->GetCompanyData() ;
				$this->session->set_userdata('company_info',$company_info);

				$msg = "Record successfully updated..";
                $this->session->set_flashdata('success_msg', $msg);				
				redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/setting/company/1');
			}
			else
			{
				//$this->form_validation->set_error_delimiters('', '');
                $msg = validation_errors(); // 'duplicate';
                $data['error_msg'] = $msg;
                $this->session->set_flashdata('error_msg', $msg);                    
                //redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/add_employee');
			}			
		}		
		$company_info=$this->Setting_model->GetCompanyData();    	 	
   	 	$data['company']=$company_info;
   	 	$data['id']=$company_info['id'];		
		$data['country_list']=$this->countries_model->GetCountriesList();
		$data['state_list']=$this->states_model->GetStatesList($company_info['country_id']);
		$data['city_list']=$this->cities_model->GetCitiesList($company_info['state_id']);	
		$data['user_list']=$this->user_model->GetUserListAll('');
		$data['currency_list']=$this->Currency_model->GetList();
		
		// $data['lead_stage_list']=$this->Setting_model->GetActiveLeadStageList();
		$this->load->view('admin/setting/company_view',$data);		
    }

    function company_validate_form_data()
    {    	
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'company name', 'required');
        $this->form_validation->set_rules('address', 'address', 'required');
        $this->form_validation->set_rules('city_id', 'city', 'required');
        $this->form_validation->set_rules('state_id', 'state', 'required'); 
        $this->form_validation->set_rules('country_id', 'country', 'required'); 
        $this->form_validation->set_rules('pin', 'pin', 'required'); 
        $this->form_validation->set_rules('about_company', 'about company', 'required'); 
        $this->form_validation->set_rules('email1', 'Email 1', 'required|valid_email');
        $this->form_validation->set_rules('mobile1', 'Mobile 1', 'required');
        if ($this->form_validation->run() == TRUE)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function update_brochure()
	{
		$edit_id=$this->input->post('edit_id'); 
		$last_inserted_id='';
		$this->load->library('upload', '');
    	if($edit_id!='')
    	{    	
    		//################################################################//        
		    //############## DELETE EXISTING LOGO IMAGE IF ANY ###############//
	    	$existing_file=get_value("brochure_file","company_setting","id=".$edit_id);
		    if($existing_file!="")
		    {    
		        @unlink("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/brochure/".$existing_file);
		    }
		    //################################################################// 
	    	$post_data=array(
							"brochure_file"=>'',
			                "updated_at"=>date('Y-m-d H:i:s')
							);
				
			$this->Setting_model->UpdateCompany($post_data,$edit_id); 
    		

    		if($_FILES['pdf_files']['tmp_name']!='')
			{
			
				$config = array(
					'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/brochure/",
					'allowed_types' => "pdf",
					'overwrite' => TRUE,
					'encrypt_name' => FALSE, 
					'max_size' => "2048000" // Can be set to particular file size , here it is 2 MB(2048 Kb)
					);
				$dataInfo = array();
				$files = $_FILES;
				$cpt = count($_FILES['pdf_files']['name']);
				for($i=0; $i<$cpt; $i++)
				{           
					$_FILES['pdf_files']['name']= $files['pdf_files']['name'][$i];
					$_FILES['pdf_files']['type']= $files['pdf_files']['type'][$i];
					$_FILES['pdf_files']['tmp_name']= $files['pdf_files']['tmp_name'][$i];
					$_FILES['pdf_files']['error']= $files['pdf_files']['error'][$i];
					$_FILES['pdf_files']['size']= $files['pdf_files']['size'][$i];    
			
					$this->upload->initialize($config);
					//$this->upload->do_upload();
					if($this->upload->do_upload('pdf_files'))
					{							
						$dataInfo = $this->upload->data();							
						$filename=$dataInfo['file_name']; //Name

						$post_data=array(
							"brochure_file"=>$filename,
			                "updated_at"=>date('Y-m-d H:i:s')
							);
				
						$this->Setting_model->UpdateCompany($post_data,$edit_id);
					}						
				}
			}
	    	$status_str='success';
    	}
    	else
    	{
    		$status_str='id_missing';
    	}
		  
        $result["status"] = $status_str;
        $result['edit_id']=$edit_id;
        echo json_encode($result);
        exit(0);
	}

	function delete_existing_brochure()
    {
    	$id=$this->input->post('id'); 
    	if($id!='')
    	{
    		//################################################################//        
		    //############## DELETE EXISTING LOGO IMAGE IF ANY ###############//
		    $existing_file=get_value("brochure_file","company_setting","id=".$id);
		    if($existing_file!="")
		    {    
		        @unlink("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/brochure/".$existing_file);
		    }
		    //################################################################// 

	    	$post_data=array(
							"brochure_file"=>'',
			                "updated_at"=>date('Y-m-d H:i:s')
							);
				
			$this->Setting_model->UpdateCompany($post_data,$id);

			$status_str='success';  
    	}
    	else
    	{
    		$status_str='id_missing';
    	}
    	
        $result["status"] = $status_str;
        echo json_encode($result);
        exit(0);  
	}
	
	public function download_brochure($file='')
	{	
		if($file!='')
		{	
			$this->load->helper(array('download'));	
			$file_name = base64_decode($file);
			$pth    =   file_get_contents("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/brochure/".$file_name);
			force_download($file_name, $pth); 
			exit;
		}
	}

	function rander_domestic_terms_and_conditions_list_ajax()
	{
		$list['rows']=$this->quotation_model->get_terms_and_conditions('terms_and_conditions_domestic_quotation');		
		$table = '';
	    $table = $this->load->view('admin/setting/domestic_terms_and_conditions_list_view_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}
	function rander_international_terms_and_conditions_list_ajax()
	{
		$list['rows']=$this->quotation_model->get_terms_and_conditions('terms_and_conditions_export_quotation');		
		$table = '';
	    $table = $this->load->view('admin/setting/international_terms_and_conditions_list_view_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}
	function rander_im_credentials_list_ajax()
	{
		$list['rows']=$this->Indiamart_setting_model->GetIndiamartCredentials();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/indiamart_credentials_list_view_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function add_edit_domestic_terms()
    {
		$dterms_id = $this->input->post('dterms_id'); 
		$dterms_name = $this->input->post('dterms_name'); 
    	$dterms_value = $this->input->post('dterms_value'); 

		if($dterms_name!='' && $dterms_value!='')
		{
			$post_data=array(
				"name"=>$dterms_name,
				"value"=>$dterms_value
				);

			if($dterms_id!='')
			{				
				$this->Setting_model->EditDomesticterms($post_data,$dterms_id);
			}
			else
			{				
				$this->Setting_model->AddDomesticterms($post_data);
			}
			
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
	
	function get_domestic_terms()
	{
		$id=$this->input->get('id');
		$row=$this->Setting_model->get_domestic_terms_details($id);
		$return['id'] = $row['id'];
		$return['name'] = $row['name'];
		$return['value'] = $row['value'];
		echo json_encode($return);
		exit(0);
	}

	function delete_domestic_terms()
	{
		$del_id=$this->input->get('id');
		$this->Setting_model->DeleteDomesticTerms($del_id);
		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}

	

	function add_edit_international_terms()
    {
		$iterms_id = $this->input->post('iterms_id'); 
		$iterms_name = $this->input->post('iterms_name'); 
    	$iterms_value = $this->input->post('iterms_value'); 

		if($iterms_name!='' && $iterms_value!='')
		{
			$post_data=array(
				"name"=>$iterms_name,
				"value"=>$iterms_value
				);

			if($iterms_id!='')
			{				
				$this->Setting_model->EditInternationalterms($post_data,$iterms_id);
			}
			else
			{				
				$this->Setting_model->AddInternationalterms($post_data);
			}
			
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
	function get_international_terms()
	{
		$id=$this->input->get('id');
		$row=$this->Setting_model->get_international_terms_details($id);
		$return['id'] = $row['id'];
		$return['name'] = $row['name'];
		$return['value'] = $row['value'];
		echo json_encode($return);
		exit(0);
	}

	function delete_international_terms()
	{
		$del_id=$this->input->get('id');
		$this->Setting_model->DeleteInternationalTerms($del_id);
		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}

	function copy_dterms_to_iterms()
	{
		$dterms_id=$this->input->get('id');		
		$row=$this->Setting_model->get_domestic_terms_details($dterms_id);		
		$dterms_name = $row['name'];
		$dterms_value = $row['value'];

		$is_exist=$this->Setting_model->chk_name_is_exist_in_iterms($dterms_name);
		if($is_exist==0)
		{
			$post_data=array(
							"name"=>$dterms_name,
							"value"=>$dterms_value
							);
			$this->Setting_model->AddInternationalterms($post_data);
			$return['status'] = "success";
		}
		else
		{
			$return['status'] = "exist";
		}	
		
		echo json_encode($return);
		exit(0);
	}

	function copy_iterms_to_dterms()
	{
		$dterms_id=$this->input->get('id');		
		$row=$this->Setting_model->get_international_terms_details($dterms_id);		
		$dterms_name = $row['name'];
		$dterms_value = $row['value'];

		$is_exist=$this->Setting_model->chk_name_is_exist_in_dterms($dterms_name);
		if($is_exist==0)
		{
			$post_data=array(
							"name"=>$dterms_name,
							"value"=>$dterms_value
							);
			$this->Setting_model->AddDomesticterms($post_data);
			$return['status'] = "success";
		}
		else
		{
			$return['status'] = "exist";
		}	
		
		echo json_encode($return);
		exit(0);
	}

	// ======================================
	// Aajjo functionality
	function rander_aj_credentials_list_ajax()
	{	
		$list['rows']=$this->Aajjo_setting_model->GetCredentials();		
	    $table = '';	    
	    $table = $this->load->view('admin/setting/aajjo_credentials_list_view_ajax',$list,TRUE);		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function get_aj_credentials()
	{
		$id=$this->input->get('id');
		// $row=$this->Aajjo_setting_model->GetCredentialsDetails($id);
		$row=$this->Aajjo_setting_model->GetAajjoCredentialsDetails($id);
		$return['id'] = $row['id'];
		$return['account_name'] = $row['account_name'];
		$return['username'] = $row['username'];
		$return['aj_key'] = $row['aj_key'];
		$return['assign_to'] = unserialize($row['assign_to']);
		$return['assign_rule'] = $row['assign_rule'];
		echo json_encode($return);
		exit(0);
	}
	function delete_aj()
	{
		$del_id=$this->input->get('id');
		$this->Aajjo_setting_model->DeleteCredentials($del_id);
		$assign_rule=get_value("assign_rule","aajjo_setting","id=".$del_id);
		if($assign_rule!=1)
		{
			$this->Aajjo_setting_model->DeleteAajjoCredentialsDetails($del_id);
		}


		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}
	function add_edit_aajjo_credentials()
    {
		$aajjo_setting_id = $this->input->post('aajjo_setting_id'); 
		$aajjo_account_name = trim($this->input->post('aajjo_account_name'));
		$aajjo_username = trim($this->input->post('aajjo_username')); 
		$aajjo_key = trim($this->input->post('aajjo_key'));
		$aajjo_assign_to = $this->input->post('aajjo_assign_to');
		$assign_rule = $this->input->post('aj_assign_rule');
		

		if($assign_rule==1)
		{
			if($aajjo_account_name!='' && $aajjo_username!='' && $aajjo_key!='' && count($aajjo_assign_to)>0)
			{

				$aj_data=array(
					"account_name"=>$aajjo_account_name,
					"username"=>$aajjo_username,
					"aj_key"=>$aajjo_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>serialize($aajjo_assign_to),
					"assign_start"=>0					
					);
	
				if($aajjo_setting_id!='')
				{				
					// --------------------------------- 
					$existing_assign_rule=get_value('assign_rule','aajjo_setting','id='.$aajjo_setting_id);	
					if($existing_assign_rule!=1)
					{
						$this->Aajjo_setting_model->DeleteAajjoCredentialsDetails($aajjo_setting_id);
					}
					// ---------------------------------
					$this->Aajjo_setting_model->EditCredentials($aj_data,$aajjo_setting_id);
				}
				else
				{				
					$this->Aajjo_setting_model->AddCredentials($aj_data);
				}				
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==2)
		{
			$rule_count = (int)$this->input->post('aj_rule_count');
			$rule_activity_count = (int)$this->input->post('aj_rule_activity_count');

			
			if($aajjo_account_name!='' && $aajjo_username!='' && $aajjo_key!='' && $rule_count>0 && $rule_activity_count>0)
			{

				$aj_data=array(
					"account_name"=>$aajjo_account_name,
					"username"=>$aajjo_username,
					"aj_key"=>$aajjo_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);
				
				if($aajjo_setting_id!='')
				{				
					$this->Aajjo_setting_model->EditCredentials($aj_data,$aajjo_setting_id);
					$this->Aajjo_setting_model->DeleteAajjoCredentialsDetails($aajjo_setting_id);					
				}
				else
				{				
					$aajjo_setting_id=$this->Aajjo_setting_model->AddCredentials($aj_data);					
				}			
				

				if($aajjo_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('country_2_'.$i);
						$assign_to_arr = $this->input->post('aajjo_assign_to_2_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$aj_details=array(
								"aj_setting_id"=>$aajjo_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Aajjo_setting_model->AddAajjoCredentialsDetails($aj_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('aajjo_assign_to_2_other');
					$aj_details=array(
								"aj_setting_id"=>$aajjo_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Aajjo_setting_model->AddAajjoCredentialsDetails($aj_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==3)
		{
			$rule_count = (int)$this->input->post('aj_rule_count');
			$rule_activity_count = (int)$this->input->post('aj_rule_activity_count');

			
			if($aajjo_account_name!='' && $aajjo_username!='' && $aajjo_key!='' && $rule_count>0 && $rule_activity_count>0)
			{

				$aj_data=array(
					"account_name"=>$aajjo_account_name,
					"username"=>$aajjo_username,
					"aj_key"=>$aajjo_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);
				
				if($aajjo_setting_id!='')
				{				
					$this->Aajjo_setting_model->EditCredentials($aj_data,$aajjo_setting_id);
					$this->Aajjo_setting_model->DeleteAajjoCredentialsDetails($aajjo_setting_id);					
				}
				else
				{				
					$aajjo_setting_id=$this->Aajjo_setting_model->AddCredentials($aj_data);					
				}			
				

				if($aajjo_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('aj_state_3_'.$i);
						$assign_to_arr = $this->input->post('aajjo_assign_to_3_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$aj_details=array(
								"aj_setting_id"=>$aajjo_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Aajjo_setting_model->AddAajjoCredentialsDetails($aj_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('aajjo_assign_to_3_other');
					$aj_details=array(
								"aj_setting_id"=>$aajjo_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Aajjo_setting_model->AddAajjoCredentialsDetails($aj_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==4)
		{
			$rule_count = (int)$this->input->post('aj_rule_count');
			$rule_activity_count = (int)$this->input->post('aj_rule_activity_count');

			
			if($aajjo_account_name!='' && $aajjo_username!='' && $aajjo_key!='' && $rule_count>0 && $rule_activity_count>0)
			{

				$aj_data=array(
					"account_name"=>$aajjo_account_name,
					"username"=>$aajjo_username,
					"aj_key"=>$aajjo_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);
				
				if($aajjo_setting_id!='')
				{				
					$this->Aajjo_setting_model->EditCredentials($aj_data,$aajjo_setting_id);
					$this->Aajjo_setting_model->DeleteAajjoCredentialsDetails($aajjo_setting_id);					
				}
				else
				{				
					$aajjo_setting_id=$this->Aajjo_setting_model->AddCredentials($aj_data);					
				}			
				

				if($aajjo_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('city_4_'.$i);
						$assign_to_arr = $this->input->post('aajjo_assign_to_4_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$aj_details=array(
								"aj_setting_id"=>$aajjo_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Aajjo_setting_model->AddAajjoCredentialsDetails($aj_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('aajjo_assign_to_4_other');
					$aj_details=array(
								"aj_setting_id"=>$aajjo_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Aajjo_setting_model->AddAajjoCredentialsDetails($aj_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==5)
		{
			$rule_count = (int)$this->input->post('aj_rule_count');
			$rule_activity_count = (int)$this->input->post('aj_rule_activity_count');

			
			if($aajjo_account_name!='' && $aajjo_username!=''&& $aajjo_key!='' && $rule_count>0 && $rule_activity_count>0)
			{

				$aj_data=array(
					"account_name"=>$aajjo_account_name,
					"username"=>$aajjo_username,
					"aj_key"=>$aajjo_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);
				
				if($aajjo_setting_id!='')
				{				
					$this->Aajjo_setting_model->EditCredentials($aj_data,$aajjo_setting_id);
					$this->Aajjo_setting_model->DeleteAajjoCredentialsDetails($aajjo_setting_id);					
				}
				else
				{				
					$aajjo_setting_id=$this->Aajjo_setting_model->AddCredentials($aj_data);					
				}			
				

				if($aajjo_setting_id>0)
				{				
					

					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = explode(',',$this->input->post('keyword_5_'.$i));
						$assign_to_arr = $this->input->post('aajjo_assign_to_5_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$aj_details=array(
								"aj_setting_id"=>$aajjo_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Aajjo_setting_model->AddAajjoCredentialsDetails($aj_details);
						}
					}

					$state_arr = 'other';
					$assign_to_arr = $this->input->post('aajjo_assign_to_5_other');
					$aj_details=array(
								"aj_setting_id"=>$aajjo_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Aajjo_setting_model->AddAajjoCredentialsDetails($aj_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		
    	 
        $result["status"] = $status_str;       
        $result["html"]='';
        echo json_encode($result);
        exit(0); 
    }
	// Aajjo functionality
	// ======================================
	
	// ======================================
	// Trade India functionality

	function rander_ti_credentials_list_ajax()
	{	
		$list['rows']=$this->Tradeindia_setting_model->GetCredentials();		
	    $table = '';	    
	    $table = $this->load->view('admin/setting/tradeindia_credentials_list_view_ajax',$list,TRUE);		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function add_edit_tradeindia_credentials()
    {
		$tradeindia_setting_id = $this->input->post('tradeindia_setting_id'); 
		$tradeindia_account_name = trim($this->input->post('tradeindia_account_name'));
		$tradeindia_userid = trim($this->input->post('tradeindia_userid')); 
    	$tradeindia_profile_id = trim($this->input->post('tradeindia_profile_id'));
		$tradeindia_key = trim($this->input->post('tradeindia_key'));
		$tradeindia_assign_to = $this->input->post('tradeindia_assign_to');
		$assign_rule = $this->input->post('ti_assign_rule');
		

		if($assign_rule==1)
		{
			if($tradeindia_account_name!='' && $tradeindia_userid!='' && $tradeindia_profile_id!='' && $tradeindia_key!='' && count($tradeindia_assign_to)>0)
			{

				$ti_data=array(
					"account_name"=>$tradeindia_account_name,
					"userid"=>$tradeindia_userid,
					"profileid"=>$tradeindia_profile_id,
					"ti_key"=>$tradeindia_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>serialize($tradeindia_assign_to),
					"assign_start"=>0					
					);
	
				if($tradeindia_setting_id!='')
				{				
					// --------------------------------- 
					$existing_assign_rule=get_value('assign_rule','tradeindia_setting','id='.$tradeindia_setting_id);	
					if($existing_assign_rule!=1)
					{
						$this->Tradeindia_setting_model->DeleteTradeindiaCredentialsDetails($tradeindia_setting_id);
					}
					// ---------------------------------
					$this->Tradeindia_setting_model->EditCredentials($ti_data,$tradeindia_setting_id);
				}
				else
				{				
					$this->Tradeindia_setting_model->AddCredentials($ti_data);
				}				
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==2)
		{
			$rule_count = (int)$this->input->post('ti_rule_count');
			$rule_activity_count = (int)$this->input->post('ti_rule_activity_count');

			
			if($tradeindia_account_name!='' && $tradeindia_userid!='' && $tradeindia_profile_id!='' && $tradeindia_key!='' && $rule_count>0 && $rule_activity_count>0)
			{

				$ti_data=array(
					"account_name"=>$tradeindia_account_name,
					"userid"=>$tradeindia_userid,
					"profileid"=>$tradeindia_profile_id,
					"ti_key"=>$tradeindia_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);
				
				if($tradeindia_setting_id!='')
				{				
					$this->Tradeindia_setting_model->EditCredentials($ti_data,$tradeindia_setting_id);
					$this->Tradeindia_setting_model->DeleteTradeindiaCredentialsDetails($tradeindia_setting_id);					
				}
				else
				{				
					$tradeindia_setting_id=$this->Tradeindia_setting_model->AddCredentials($ti_data);					
				}			
				

				if($tradeindia_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('country_2_'.$i);
						$assign_to_arr = $this->input->post('tradeindia_assign_to_2_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$ti_details=array(
								"ti_setting_id"=>$tradeindia_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Tradeindia_setting_model->AddTradeindiaCredentialsDetails($ti_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('tradeindia_assign_to_2_other');
					$ti_details=array(
								"ti_setting_id"=>$tradeindia_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
								$this->Tradeindia_setting_model->AddTradeindiaCredentialsDetails($ti_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==3)
		{
			$rule_count = (int)$this->input->post('ti_rule_count');
			$rule_activity_count = (int)$this->input->post('ti_rule_activity_count');

			
			if($tradeindia_account_name!='' && $tradeindia_userid!='' && $tradeindia_profile_id!='' && $tradeindia_key!='' && $rule_count>0 && $rule_activity_count>0)
			{

				$ti_data=array(
					"account_name"=>$tradeindia_account_name,
					"userid"=>$tradeindia_userid,
					"profileid"=>$tradeindia_profile_id,
					"ti_key"=>$tradeindia_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);
				
				if($tradeindia_setting_id!='')
				{				
					$this->Tradeindia_setting_model->EditCredentials($ti_data,$tradeindia_setting_id);
					$this->Tradeindia_setting_model->DeleteTradeindiaCredentialsDetails($tradeindia_setting_id);					
				}
				else
				{				
					$tradeindia_setting_id=$this->Tradeindia_setting_model->AddCredentials($ti_data);					
				}			
				

				if($tradeindia_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('ti_state_3_'.$i);
						$assign_to_arr = $this->input->post('tradeindia_assign_to_3_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$ti_details=array(
								"ti_setting_id"=>$tradeindia_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Tradeindia_setting_model->AddTradeindiaCredentialsDetails($ti_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('tradeindia_assign_to_3_other');
					$ti_details=array(
								"ti_setting_id"=>$tradeindia_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
								$this->Tradeindia_setting_model->AddTradeindiaCredentialsDetails($ti_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==4)
		{
			$rule_count = (int)$this->input->post('ti_rule_count');
			$rule_activity_count = (int)$this->input->post('ti_rule_activity_count');

			
			if($tradeindia_account_name!='' && $tradeindia_userid!='' && $tradeindia_profile_id!='' && $tradeindia_key!='' && $rule_count>0 && $rule_activity_count>0)
			{

				$ti_data=array(
					"account_name"=>$tradeindia_account_name,
					"userid"=>$tradeindia_userid,
					"profileid"=>$tradeindia_profile_id,
					"ti_key"=>$tradeindia_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);
				
				if($tradeindia_setting_id!='')
				{				
					$this->Tradeindia_setting_model->EditCredentials($ti_data,$tradeindia_setting_id);
					$this->Tradeindia_setting_model->DeleteTradeindiaCredentialsDetails($tradeindia_setting_id);					
				}
				else
				{				
					$tradeindia_setting_id=$this->Tradeindia_setting_model->AddCredentials($ti_data);					
				}			
				

				if($tradeindia_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('city_4_'.$i);
						$assign_to_arr = $this->input->post('tradeindia_assign_to_4_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$ti_details=array(
								"ti_setting_id"=>$tradeindia_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Tradeindia_setting_model->AddTradeindiaCredentialsDetails($ti_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('tradeindia_assign_to_4_other');
					$ti_details=array(
								"ti_setting_id"=>$tradeindia_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
								$this->Tradeindia_setting_model->AddTradeindiaCredentialsDetails($ti_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==5)
		{
			$rule_count = (int)$this->input->post('ti_rule_count');
			$rule_activity_count = (int)$this->input->post('ti_rule_activity_count');

			
			if($tradeindia_account_name!='' && $tradeindia_userid!='' && $tradeindia_profile_id!='' && $tradeindia_key!='' && $rule_count>0 && $rule_activity_count>0)
			{

				$ti_data=array(
					"account_name"=>$tradeindia_account_name,
					"userid"=>$tradeindia_userid,
					"profileid"=>$tradeindia_profile_id,
					"ti_key"=>$tradeindia_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);
				
				if($tradeindia_setting_id!='')
				{				
					$this->Tradeindia_setting_model->EditCredentials($ti_data,$tradeindia_setting_id);
					$this->Tradeindia_setting_model->DeleteTradeindiaCredentialsDetails($tradeindia_setting_id);					
				}
				else
				{				
					$tradeindia_setting_id=$this->Tradeindia_setting_model->AddCredentials($ti_data);					
				}			
				

				if($tradeindia_setting_id>0)
				{				
					

					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = explode(',',$this->input->post('keyword_5_'.$i));
						$assign_to_arr = $this->input->post('tradeindia_assign_to_5_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$ti_details=array(
								"ti_setting_id"=>$tradeindia_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Tradeindia_setting_model->AddTradeindiaCredentialsDetails($ti_details);
						}
					}

					$state_arr = 'other';
					$assign_to_arr = $this->input->post('tradeindia_assign_to_5_other');
					$ti_details=array(
								"ti_setting_id"=>$tradeindia_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Tradeindia_setting_model->AddTradeindiaCredentialsDetails($ti_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		/*
		if($tradeindia_account_name!='' && $tradeindia_userid!='' && $tradeindia_profile_id!='' && $tradeindia_key!='' && count($tradeindia_assign_to)>0)
		{
			$ti_data=array(
				"account_name"=>$tradeindia_account_name,
				"userid"=>$tradeindia_userid,
				"profileid"=>$tradeindia_profile_id,
				"ti_key"=>$tradeindia_key,
				"assign_to"=>serialize($tradeindia_assign_to),
				"assign_start"=>0					
				);

			if($tradeindia_setting_id!='')
			{				
				$this->Tradeindia_setting_model->EditCredentials($ti_data,$tradeindia_setting_id);
			}
			else
			{				
				$this->Tradeindia_setting_model->AddCredentials($ti_data);
			}
			
			$status_str='success'; 
		}	
		else
		{
			$status_str='fail'; 
		}
		*/
    	 
        $result["status"] = $status_str;       
        $result["html"]='';
        echo json_encode($result);
        exit(0); 
    }

    function get_ti_credentials()
	{
		$id=$this->input->get('id');
		// $row=$this->Tradeindia_setting_model->GetCredentialsDetails($id);
		$row=$this->Tradeindia_setting_model->GetTradeindiaCredentialsDetails($id);
		$return['id'] = $row['id'];
		$return['account_name'] = $row['account_name'];
		$return['userid'] = $row['userid'];
		$return['profileid'] = $row['profileid'];
		$return['ti_key'] = $row['ti_key'];
		$return['assign_to'] = unserialize($row['assign_to']);
		$return['assign_rule'] = $row['assign_rule'];
		echo json_encode($return);
		exit(0);
	}

	function delete_ti()
	{
		$del_id=$this->input->get('id');
		$this->Tradeindia_setting_model->DeleteCredentials($del_id);
		$assign_rule=get_value("assign_rule","tradeindia_setting","id=".$del_id);
		if($assign_rule!=1)
		{
			$this->Tradeindia_setting_model->DeleteTradeindiaCredentialsDetails($del_id);
		}


		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}
	// Trade India functionality
	// ======================================

	// =========================================
	// justdial
	function rander_jd_credentials_list_ajax()
	{	
		$list['row']=$this->Justdial_setting_model->GetCredentials();		
	    $table = '';	    
	    $table = $this->load->view('admin/setting/justdial_credentials_list_view_ajax',$list,TRUE);		
	    $data =array (
	       "table"=>$table,
	       "row_count"=>count($list['row'])
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function get_jd_credentials()
	{

		$id=$this->input->get('id');
		// $row=$this->Justdial_setting_model->GetCredentials($id);
		$row=$this->Justdial_setting_model->GetJustdialCredentialsDetails($id);
		$return['id'] = $row['id'];
		$return['account_name'] = $row['account_name'];
		$return['assign_to'] = unserialize($row['assign_to']);
		$return['assign_rule'] = $row['assign_rule'];
		echo json_encode($return);
		exit(0);
	}

	function delete_jd()
	{
		$del_id=$this->input->get('id');
		$this->Justdial_setting_model->DeleteCredentials($del_id);
		$assign_rule=get_value("assign_rule","justdial_setting","id=".$del_id);
		if($assign_rule!=1)
		{
			$this->Justdial_setting_model->DeleteJustdialCredentialsDetails($del_id);
		}

		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}
	// justdial
	// =========================================

	// =========================================
	// website
	function get_web_credentials()
	{

		$id=$this->input->get('id');
		// $row=$this->Justdial_setting_model->GetCredentials($id);
		$row=$this->Website_setting_model->GetWebsiteCredentialsDetails($id);
		$return['id'] = $row['id'];
		$return['account_name'] = $row['account_name'];
		$return['assign_to'] = unserialize($row['assign_to']);
		$return['assign_rule'] = $row['assign_rule'];
		echo json_encode($return);
		exit(0);
	}
	function rander_web_credentials_list_ajax()
	{	
		$list['row']=$this->Website_setting_model->GetCredentials();		
	    $table = '';	    
	    $table = $this->load->view('admin/setting/website_credentials_list_view_ajax',$list,TRUE);		
	    $data =array (
	       "table"=>$table,
	       "row_count"=>count($list['row'])
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function delete_web()
	{
		$del_id=$this->input->get('id');
		$this->Website_setting_model->DeleteCredentials($del_id);
		$assign_rule=get_value("assign_rule","website_api_setting","id=".$del_id);
		if($assign_rule!=1)
		{
			$this->Website_setting_model->DeleteWebsiteCredentialsDetails($del_id);
		}

		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}
	// website
	// =========================================

	// ======================================
	// User Gmail sync functionality
	function rander_gmail_sync_list_ajax()
	{
		$list['rows']=$this->Gmail_for_sync_setting_model->GetAllList();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/gmail_for_sync_list_view_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function add_edit_user_gmail()
    {
		$user_gmail_id = $this->input->post('user_gmail_id'); 
		$gmail_address = trim($this->input->post('gmail_address'));     	
		$user_gmail_assign_to = $this->input->post('user_gmail_assign_to');
		


		if($gmail_address!='' && count($user_gmail_assign_to)>0)
		{
			$post_data=array(
				"user_id"=>$user_gmail_assign_to,
				"gmail_address"=>$gmail_address	
				);

			if($user_gmail_id!='')
			{			

				$row=$this->Gmail_for_sync_setting_model->GetDetails($user_gmail_id);				
				$user_already_exist=$this->Gmail_for_sync_setting_model->Chk_exist($user_gmail_assign_to,$row['user_id']);
				if($user_already_exist=='N')
				{
					$this->Gmail_for_sync_setting_model->Editdata($post_data,$user_gmail_id);
					$status_str='success'; 
				}
				else
				{
					$status_str='exist'; 
				}
			}
			else
			{		
				$user_already_exist=$this->Gmail_for_sync_setting_model->Chk_exist($user_gmail_assign_to,'');
				if($user_already_exist=='N')
				{
					$this->Gmail_for_sync_setting_model->AddData($post_data);
					$status_str='success'; 
				}
				else
				{
					$status_str='exist'; 
				}
				
			}
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

    function get_user_gmail()
	{
		$id=$this->input->get('id');
		$row=$this->Gmail_for_sync_setting_model->GetDetails($id);
		$return['id'] = $row['id'];		
		$return['gmail_address'] = $row['gmail_address'];
		$return['assign_to'] = $row['user_id'];
		echo json_encode($return);
		exit(0);
	}

	function delete_user_gmail()
	{
		$del_id=$this->input->get('id');
		$this->Gmail_for_sync_setting_model->Delete($del_id);
		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}

	// User Gmail sync functionality
	// ======================================

	public function app_download()
	{			
		$this->load->helper(array('download'));	
		$file_name = get_latest_app();
		$pth    =   file_get_contents("assets/".$file_name);
		force_download($file_name, $pth); 
		exit;
	}	


	function rander_email_forwarding_settings_ajax()
	{
		$list['company_info']=$this->Setting_model->GetCompanyData();
		$list['rows']=$this->Email_forwarding_setting_model->GetAllList();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/rander_email_forwarding_settings_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function rander_sms_forwarding_settings_ajax()
	{
		
		$list['company_info']=$this->Setting_model->GetCompanyData();
		$list['rows']=$this->Sms_forwarding_setting_model->GetAllList();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/rander_sms_forwarding_settings_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function rander_whatsapp_forwarding_settings_ajax()
	{
		
		$list['company_info']=$this->Setting_model->GetCompanyData();
		$list['rows']=$this->Whatsapp_forwarding_setting_model->GetAllList();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/rander_whatsapp_forwarding_settings_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function email_forwarding_settings_update()
	{
		$id=$this->input->get('id');
		$field=$this->input->get('field');
		$value=$this->input->get('value');

		$post_data=array($field=>$value);
		$this->Email_forwarding_setting_model->Editdata($post_data,$id);

		$row=$this->Email_forwarding_setting_model->GetDetails($id);
		
		if($field=='is_mail_send' && $value=='N')
		{
			$post_data2['is_send_relationship_manager']='N';
			$post_data2['is_send_manager']='N';
			$post_data2['is_send_skip_manager']='N';
			if($row['is_send_mail_to_client']!='D')
			{
				$post_data2['is_send_mail_to_client']='N';
			}
			// $post_data2=array(
			// 				'is_send_relationship_manager'=>'N',
			// 				'is_send_manager'=>'N',
			// 				'is_send_skip_manager'=>'N',
			// 				'is_send_mail_to_client'=>'N'
			// 				);
			$this->Email_forwarding_setting_model->Editdata($post_data2,$id);
		}
		else if($field=='is_mail_send' && $value=='Y')
		{
			$post_data2['is_send_relationship_manager']='Y';
			$post_data2['is_send_manager']='Y';
			$post_data2['is_send_skip_manager']='Y';
			// $post_data2=array(
			// 				'is_send_relationship_manager'=>'Y',
			// 				'is_send_manager'=>'Y',
			// 				'is_send_skip_manager'=>'Y'
			// 				);
			$this->Email_forwarding_setting_model->Editdata($post_data2,$id);
		}

		$return['status'] = 'success';
		echo json_encode($return);
		exit(0);
	}

	function daily_report_email_send_settings_update()
	{
		$is_daily_report_send=$this->input->get('is_daily_report_send');
		$daily_report_tomail=$this->input->get('daily_report_tomail');
		$daily_report_mail_subject=$this->input->get('daily_report_mail_subject');

		$post_data=array(
						"is_daily_report_send"=>$is_daily_report_send,
						"daily_report_tomail"=>$daily_report_tomail,
						"daily_report_mail_subject"=>$daily_report_mail_subject,
						"updated_at"=>date("Y-m-d H:i:s")
						);
		$this->Setting_model->UpdateCompany($post_data,1);

		$return['status'] = 'success';
		echo json_encode($return);
		exit(0);
	}

	function sms_forwarding_settings_update()
	{
		$id=$this->input->get('id');
		$field=$this->input->get('field');
		$value=$this->input->get('value');

		$post_data=array($field=>$value);//print_r($post_data); die();
		$this->Sms_forwarding_setting_model->Editdata($post_data,$id);

		$row=$this->Sms_forwarding_setting_model->GetDetails($id);
		
		if($field=='is_sms_send' && $value=='N')
		{
			$post_data2['is_send_relationship_manager']='N';
			$post_data2['is_send_manager']='N';
			$post_data2['is_send_skip_manager']='N';
			if($row['is_send_sms_to_client']!='D')
			{
				$post_data2['is_send_sms_to_client']='N';
			}
			// $post_data2=array(
			// 				'is_send_relationship_manager'=>'N',
			// 				'is_send_manager'=>'N',
			// 				'is_send_skip_manager'=>'N',
			// 				'is_send_mail_to_client'=>'N'
			// 				);
			$this->Sms_forwarding_setting_model->Editdata($post_data2,$id);
		}
		else if($field=='is_sms_send' && $value=='Y')
		{
			$post_data2['is_send_relationship_manager']='Y';
			$post_data2['is_send_manager']='Y';
			$post_data2['is_send_skip_manager']='Y';
			// $post_data2=array(
			// 				'is_send_relationship_manager'=>'Y',
			// 				'is_send_manager'=>'Y',
			// 				'is_send_skip_manager'=>'Y'
			// 				);
			$this->Sms_forwarding_setting_model->Editdata($post_data2,$id);
		}

		$return['status'] = 'success';
		echo json_encode($return);
		exit(0);
	}

	function add_edit_smtp_credentials()
    {
		$smtp_setting_id = $this->input->post('smtp_setting_id'); 
		$smtp_type = $this->input->post('smtp_type'); 
		$smtp_host = $this->input->post('smtp_host'); 
		$smtp_port = $this->input->post('smtp_port');
    	$smtp_username = $this->input->post('smtp_username'); 
		$smtp_password = $this->input->post('smtp_password');
		

		if($smtp_type!='' && $smtp_username!='' && $smtp_password!='')
		{
			

			if($smtp_setting_id!='')
			{			
				$smtp_data=array(
				"smtp_type"=>$smtp_type,
				"host"=>$smtp_host,
				"port"=>$smtp_port,
				"username"=>$smtp_username,
				"password"=>$smtp_password,
				"created_at"=>date("Y-m-d H:i:s"),
				"updated_at"=>date("Y-m-d H:i:s")		
				);

				$this->Setting_model->EditSmtpCredentials($smtp_data,$smtp_setting_id);
				$status_str='success'; 
			}
			else
			{			
				$is_smtp_type_exist=$this->Setting_model->is_smtp_type_exist($smtp_type);
				if($is_smtp_type_exist>0)
				{
					$status_str='exist'; 
				}
				else
				{
					$post_data=array('is_active'=>'N');
					$this->Setting_model->EditSmtpAllCredentials($post_data);
					
					$smtp_data=array(
					"smtp_type"=>$smtp_type,
					"host"=>$smtp_host,
					"port"=>$smtp_port,
					"username"=>$smtp_username,
					"password"=>$smtp_password,				
					"is_active"=>'Y',
					"created_at"=>date("Y-m-d H:i:s"),
					"updated_at"=>date("Y-m-d H:i:s")		
					);

					$this->Setting_model->AddSmtpCredentials($smtp_data);
					$status_str='success'; 
				}
				
			}
			
			
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

    function rander_smtp_credentials_list_ajax()
	{
		$list['rows']=$this->Setting_model->GetSmtpList();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/smtp_credentials_list_view_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	    exit;
	}

	function whatsapp_forwarding_settings_update()
	{
		$id=$this->input->get('id');
		$field=$this->input->get('field');
		$value=$this->input->get('value');

		$post_data=array($field=>$value);//print_r($post_data); die();
		$this->Whatsapp_forwarding_setting_model->Editdata($post_data,$id);

		$row=$this->Whatsapp_forwarding_setting_model->GetDetails($id);
		
		if($field=='is_whatsapp_send' && $value=='N')
		{
			$post_data2['is_send_relationship_manager']='N';
			$post_data2['is_send_manager']='N';
			$post_data2['is_send_skip_manager']='N';
			if($row['is_send_whatsapp_to_client']!='D')
			{
				$post_data2['is_send_whatsapp_to_client']='N';
			}
			// $post_data2=array(
			// 				'is_send_relationship_manager'=>'N',
			// 				'is_send_manager'=>'N',
			// 				'is_send_skip_manager'=>'N',
			// 				'is_send_mail_to_client'=>'N'
			// 				);
			$this->Whatsapp_forwarding_setting_model->Editdata($post_data2,$id);
		}
		else if($field=='is_whatsapp_send' && $value=='Y')
		{
			$post_data2['is_send_relationship_manager']='Y';
			$post_data2['is_send_manager']='Y';
			$post_data2['is_send_skip_manager']='Y';
			// $post_data2=array(
			// 				'is_send_relationship_manager'=>'Y',
			// 				'is_send_manager'=>'Y',
			// 				'is_send_skip_manager'=>'Y'
			// 				);
			$this->Whatsapp_forwarding_setting_model->Editdata($post_data2,$id);
		}

		$return['status'] = 'success';
		echo json_encode($return);
		exit(0);
	}
	function get_smtp_credentials()
	{
		$id=$this->input->get('id');
		$row=$this->Setting_model->GetSmtpCredentialsDetails($id);
		$return['id'] = $row['id'];
		$return['smtp_type'] = $row['smtp_type'];
		$return['host'] = $row['host'];
		$return['port'] = $row['port'];
		$return['username'] = $row['username'];
		$return['password'] = $row['password'];
		$return['is_active'] = $row['is_active'];
		echo json_encode($return);
		exit(0);
	}

	function delete_smtp()
	{
		$del_id=$this->input->get('id');
		$this->Setting_model->DeleteSmtpCredentials($del_id);
		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}

	function smtp_settings_update()
	{
		$id=$this->input->get('id');
		$field=$this->input->get('field');
		$value=$this->input->get('value');

		if($field=='is_active' && $value=='Y')
		{
			$post_data=array('is_active'=>'N');
			$this->Setting_model->EditSmtpAllCredentials($post_data);
		}
		$post_data=array();
		$post_data=array($field=>$value);
		$this->Setting_model->EditSmtpCredentials($post_data,$id);
		$return['status'] = 'success';
		echo json_encode($return);
		exit(0);
	}

	function get_user_info_ajax()
	{
		$user_id=$this->input->get('user_id');
		$user_info=$this->user_model->get_user_details($user_id);
		$return['name'] = $user_info['name'];
		$return['mobile'] = $user_info['mobile'];
		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}

	function rander_c2c_credentials_list_ajax()
	{
		$list['rows']=$this->Setting_model->GetC2cpList();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/c2c_credentials_list_view_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function add_edit_c2c_credentials()
    {
		$c2c_setting_id = $this->input->post('c2c_setting_id'); 
		$c2c_service_provider_id = $this->input->post('c2c_service_provider_id');
		$c2c_caller_name = $this->input->post('c2c_caller_name');
		$c2c_mobile = $this->input->post('c2c_mobile');
		$c2c_office_no = $this->input->post('c2c_office_no');
		$user_c2c_assign_to = $this->input->post('user_c2c_assign_to');

		if($c2c_mobile!='' && count($user_c2c_assign_to)>0)
		{
			$c2c_data=array(
				"c2c_service_provider_id"=>$c2c_service_provider_id,
				"user_id"=>$user_c2c_assign_to,
				"caller_name"=>$c2c_caller_name,
				"mobile"=>$c2c_mobile,
				"office_no"=>$c2c_office_no,
				"created_at"=>date("Y-m-d H:i:s"),
				"updated_at"=>date("Y-m-d H:i:s")			
				);

			if($c2c_setting_id!='')
			{				
				$this->Setting_model->EditC2cCredentials($c2c_data,$c2c_setting_id);
				$status_str='success';
			}
			else
			{			
				$is_user_exist=$this->Setting_model->is_user_exist_for_c2c($user_c2c_assign_to);
				if($is_user_exist>0)
				{
					$status_str='exist';
				}
				else
				{
					$this->Setting_model->AddC2cCredentials($c2c_data);
					$status_str='success';
				}				
			}
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

    function edit_c2c_credentials_setting()
    {
		$edit_id = $this->input->post('edit_id'); 
		$c2c_api_dial_url = $this->input->post('c2c_api_dial_url');
		$c2c_api_userid = $this->input->post('c2c_api_userid');
		$c2c_api_password = $this->input->post('c2c_api_password');
		$c2c_api_client_name = $this->input->post('c2c_api_client_name');

		if($c2c_api_dial_url!='' && $c2c_api_userid!='')
		{
			$post_data=array(
				"c2c_api_dial_url"=>$c2c_api_dial_url,
				"c2c_api_userid"=>$c2c_api_userid,
				"c2c_api_password"=>$c2c_api_password,
				"c2c_api_client_name"=>$c2c_api_client_name,
				"updated_at"=>date("Y-m-d H:i:s")			
				);
			$this->Setting_model->UpdateCompany($post_data,$edit_id);

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

    function get_c2c_credentials()
	{
		$id=$this->input->get('id');
		$row=$this->Setting_model->GetC2cCredentialsDetails($id);
		$return['id'] = $row['id'];
		$return['c2c_service_provider_id'] = $row['c2c_service_provider_id'];
		$return['caller_name'] = $row['caller_name'];
		$return['office_no'] = $row['office_no'];
		$return['user_id'] = $row['user_id'];
		$return['mobile'] = $row['mobile'];

		echo json_encode($return);
		exit(0);
	}

	function delete_c2c()
	{
		$del_id=$this->input->get('id');
		$this->Setting_model->DeleteC2cCredentials($del_id);
		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}

	function rander_im_rule_wise_view()
	{
		$rule_id=$this->input->get('rule_id');
		$im_s_id=$this->input->get('im_s_id');
		
		$data=array();

		if($im_s_id>0)
		{
			$data['row']=$this->Indiamart_setting_model->GetIndiamartCredentialsDetails($im_s_id);
			$data['rules']=$this->Indiamart_setting_model->get_rules($im_s_id);
		}
		else
		{
			$data['row']=array();
			$data['rules']=array();
		}
		
		$data['rule_id']=$rule_id;
		$data['im_s_id']=$im_s_id;
		$data['user_list']=$this->user_model->GetUserListAll('');
		// $data['state_list']=$this->states_model->GetStatesList('101');
	    $html = $this->load->view('admin/setting/im_rule_wise_view_ajax',$data,TRUE);
		
	    $return =array (
	       "html"=>$html,
	       "msg"=>""
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($return); 
		exit;
	}

	function rander_im_rule_wise_view_outer_div_ajax()
	{
		$im_s_id=$this->input->get('im_s_id');
		$cnt=$this->input->get('cnt');
		$rule_id=$this->input->get('rule_id');
		$mode=$this->input->get('mode');
		$data=array();
		if($im_s_id>0)
		{
			$data['rules']=$this->Indiamart_setting_model->get_rules($im_s_id);
		}
		else
		{
			$data['rules']=array();
		}
		$data['cnt']=$cnt;
		$data['rule_id']=$rule_id;
		$data['im_s_id']=$im_s_id;
		$data['user_list']=$this->user_model->GetUserListAll('');
		if($mode=='edit')
		{
			$index=($cnt-1);
			$existing_find_to_arr=unserialize($data['rules'][$index]['find_to']);
			$existing_find_to_str='';
			if(count($existing_find_to_arr))
			{
				$existing_find_to_str=implode(",",$existing_find_to_arr);
			}			
			if($rule_id=='2'){
				$data['country_list']=$this->countries_model->GetCountriesList($existing_find_to_str);	
			}
			else if($rule_id=='3'){
				$data['state_list']=$this->states_model->GetOnlyIndianStatesListByID($existing_find_to_str);	
			}
			else if($rule_id=='4'){
				$data['city_list']=$this->cities_model->GetAllIndianCitiesList($existing_find_to_str);
			}						
		}	
		else
		{
			$data['country_list']=array();		
			$data['state_list']=array();		
			$data['city_list']=array();
			$data['keywords']='';
		}	
				
		$data['mode']=$mode;
	    $html = $this->load->view('admin/setting/rander_im_rule_wise_view_outer_div_ajax',$data,TRUE);
		
	    $return =array (
	       "html"=>$html,
	       "msg"=>""
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($return); 
		exit;
	}

	function rander_ti_rule_wise_view()
	{
		$rule_id=$this->input->get('rule_id');
		$ti_s_id=$this->input->get('ti_s_id');
		
		$data=array();

		if($ti_s_id>0)
		{
			$data['row']=$this->Tradeindia_setting_model->GetTradeindiaCredentialsDetails($ti_s_id);
			$data['rules']=$this->Tradeindia_setting_model->get_rules($ti_s_id);
		}
		else
		{
			$data['row']=array();
			$data['rules']=array();
		}
		
		$data['rule_id']=$rule_id;
		$data['ti_s_id']=$ti_s_id;
		$data['user_list']=$this->user_model->GetUserListAll('');
		// $data['state_list']=$this->states_model->GetStatesList('101');
	    $html = $this->load->view('admin/setting/ti_rule_wise_view_ajax',$data,TRUE);
		// echo $html;die('ok');
		
	    $return =array (
	       "html"=>$html,
	       "msg"=>""
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($return); 
	}

	function rander_ti_rule_wise_view_outer_div_ajax()
	{
		$ti_s_id=$this->input->get('ti_s_id');
		$cnt=$this->input->get('cnt');
		$rule_id=$this->input->get('rule_id');
		$mode=$this->input->get('mode');
		$data=array();
		if($ti_s_id>0)
		{
			$data['rules']=$this->Tradeindia_setting_model->get_rules($ti_s_id);
		}
		else
		{
			$data['rules']=array();
		}
		$data['cnt']=$cnt;
		$data['rule_id']=$rule_id;
		$data['ti_s_id']=$ti_s_id;
		$data['user_list']=$this->user_model->GetUserListAll('');
		if($mode=='edit')
		{
			$index=($cnt-1);
			$existing_find_to_arr=unserialize($data['rules'][$index]['find_to']);
			$existing_find_to_str='';
			if(count($existing_find_to_arr))
			{
				$existing_find_to_str=implode(",",$existing_find_to_arr);
			}			
			if($rule_id=='2'){
				$data['country_list']=$this->countries_model->GetCountriesList($existing_find_to_str);	
			}
			else if($rule_id=='3'){
				$data['state_list']=$this->states_model->GetOnlyIndianStatesListByID($existing_find_to_str);	
			}
			else if($rule_id=='4'){
				$data['city_list']=$this->cities_model->GetAllIndianCitiesList($existing_find_to_str);
			}			
		}	
		else
		{
			$data['country_list']=array();		
			$data['state_list']=array();		
			$data['city_list']=array();
		}
		// $data['state_list']=$this->states_model->GetStatesList('101');
		$data['mode']=$mode;
	    $html = $this->load->view('admin/setting/rander_ti_rule_wise_view_outer_div_ajax',$data,TRUE);
		
	    $return =array (
	       "html"=>$html,
	       "msg"=>""
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($return); 
	}

	function rander_aj_rule_wise_view()
	{
		$rule_id=$this->input->get('rule_id');
		$aj_s_id=$this->input->get('aj_s_id');
		
		$data=array();

		if($aj_s_id>0)
		{
			$data['row']=$this->Aajjo_setting_model->GetAajjoCredentialsDetails($aj_s_id);
			$data['rules']=$this->Aajjo_setting_model->get_rules($aj_s_id);
		}
		else
		{
			$data['row']=array();
			$data['rules']=array();
		}
		
		$data['rule_id']=$rule_id;
		$data['aj_s_id']=$aj_s_id;
		$data['user_list']=$this->user_model->GetUserListAll('');
		// $data['state_list']=$this->states_model->GetStatesList('101');
	    $html = $this->load->view('admin/setting/aj_rule_wise_view_ajax',$data,TRUE);
		// echo $html;die('ok');
		
	    $return =array (
	       "html"=>$html,
	       "msg"=>""
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($return); 
	}

	function rander_aj_rule_wise_view_outer_div_ajax()
	{
		$aj_s_id=$this->input->get('aj_s_id');
		$cnt=$this->input->get('cnt');
		$rule_id=$this->input->get('rule_id');
		$mode=$this->input->get('mode');
		$data=array();
		if($aj_s_id>0)
		{
			$data['rules']=$this->Aajjo_setting_model->get_rules($aj_s_id);
		}
		else
		{
			$data['rules']=array();
		}
		$data['cnt']=$cnt;
		$data['rule_id']=$rule_id;
		$data['ti_s_id']=$ti_s_id;
		$data['user_list']=$this->user_model->GetUserListAll('');
		if($mode=='edit')
		{
			$index=($cnt-1);
			$existing_find_to_arr=unserialize($data['rules'][$index]['find_to']);
			$existing_find_to_str='';
			if(count($existing_find_to_arr))
			{
				$existing_find_to_str=implode(",",$existing_find_to_arr);
			}			
			if($rule_id=='2'){
				$data['country_list']=$this->countries_model->GetCountriesList($existing_find_to_str);	
			}
			else if($rule_id=='3'){
				$data['state_list']=$this->states_model->GetOnlyIndianStatesListByID($existing_find_to_str);	
			}
			else if($rule_id=='4'){
				$data['existing_find_to_str']=$existing_find_to_str;
				$data['city_list']=$this->cities_model->GetAllIndianCitiesList($existing_find_to_str);
			}			
		}	
		else
		{
			$data['country_list']=array();		
			$data['state_list']=array();		
			$data['city_list']=array();
		}
		// $data['state_list']=$this->states_model->GetStatesList('101');
		$data['mode']=$mode;
	    $html = $this->load->view('admin/setting/rander_aj_rule_wise_view_outer_div_ajax',$data,TRUE);
		
	    $return =array (
	       "html"=>$html,
	       "msg"=>""
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($return); 
	}

	function add_edit_justdial_credentials()
    {
		$justdial_setting_id = $this->input->post('justdial_setting_id'); 
		$justdial_account_name = 'JustDial API';		
		$justdial_assign_to = $this->input->post('justdial_assign_to');

		$assign_rule = $this->input->post('jd_assign_rule');

		if($assign_rule==1)
		{
			if(count($justdial_assign_to)>0)
			{

				$jd_data=array(
					"account_name"=>$justdial_account_name,
					"assign_rule"=>$assign_rule,
					"assign_to"=>serialize($justdial_assign_to),
					"assign_start"=>0					
					);
	
				if($justdial_setting_id!='')
				{			
					// --------------------------------- 
					$existing_assign_rule=get_value('assign_rule','justdial_setting','id='.$justdial_setting_id);	
					if($existing_assign_rule!=1)
					{
						$this->Justdial_setting_model->DeleteJustdialCredentialsDetails($justdial_setting_id);
					}
					// ---------------------------------	
					$this->Justdial_setting_model->EditJustdialCredentials($jd_data,$justdial_setting_id);
				}
				else
				{				
					$this->Justdial_setting_model->AddCredentials($jd_data);
				}								
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==2) //country
		{
			$rule_count = (int)$this->input->post('jd_rule_count');
			$rule_activity_count = (int)$this->input->post('jd_rule_activity_count');
			
			
			if($rule_count>0 && $rule_activity_count>0)
			{

				$jd_data=array(
					"account_name"=>$justdial_account_name,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);				
				
				if($justdial_setting_id!='')
				{				
					$this->Justdial_setting_model->EditJustdialCredentials($jd_data,$justdial_setting_id);
					$this->Justdial_setting_model->DeleteJustdialCredentialsDetails($justdial_setting_id);					
				}
				else
				{			
					$justdial_setting_id=$this->Justdial_setting_model->AddCredentials($jd_data);				
				}			
				
				
				if($justdial_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('country_2_'.$i);
						$assign_to_arr = $this->input->post('justdial_assign_to_2_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$jd_details=array(
								"jd_setting_id"=>$justdial_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Justdial_setting_model->AddJustdialCredentialsDetails($jd_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('justdial_assign_to_2_other');
					$jd_details=array(
								"jd_setting_id"=>$justdial_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Justdial_setting_model->AddJustdialCredentialsDetails($jd_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==3)
		{
			$rule_count = (int)$this->input->post('jd_rule_count');
			$rule_activity_count = (int)$this->input->post('jd_rule_activity_count');
			
			
			if($rule_count>0 && $rule_activity_count>0)
			{

				$jd_data=array(
					"account_name"=>$justdial_account_name,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);				
				
				if($justdial_setting_id!='')
				{				
					$this->Justdial_setting_model->EditJustdialCredentials($jd_data,$justdial_setting_id);
					$this->Justdial_setting_model->DeleteJustdialCredentialsDetails($justdial_setting_id);					
				}
				else
				{			
					$justdial_setting_id=$this->Justdial_setting_model->AddCredentials($jd_data);				
				}			
				
				
				if($justdial_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('jd_state_3_'.$i);
						$assign_to_arr = $this->input->post('justdial_assign_to_3_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$jd_details=array(
								"jd_setting_id"=>$justdial_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Justdial_setting_model->AddJustdialCredentialsDetails($jd_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('justdial_assign_to_3_other');
					$jd_details=array(
								"jd_setting_id"=>$justdial_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Justdial_setting_model->AddJustdialCredentialsDetails($jd_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==4)
		{
			$rule_count = (int)$this->input->post('jd_rule_count');
			$rule_activity_count = (int)$this->input->post('jd_rule_activity_count');
			
			
			if($rule_count>0 && $rule_activity_count>0)
			{

				$jd_data=array(
					"account_name"=>$justdial_account_name,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);				
				
				if($justdial_setting_id!='')
				{				
					$this->Justdial_setting_model->EditJustdialCredentials($jd_data,$justdial_setting_id);
					$this->Justdial_setting_model->DeleteJustdialCredentialsDetails($justdial_setting_id);					
				}
				else
				{			
					$justdial_setting_id=$this->Justdial_setting_model->AddCredentials($jd_data);				
				}			
				
				
				if($justdial_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('city_4_'.$i);
						$assign_to_arr = $this->input->post('justdial_assign_to_4_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$jd_details=array(
								"jd_setting_id"=>$justdial_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Justdial_setting_model->AddJustdialCredentialsDetails($jd_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('justdial_assign_to_4_other');
					$jd_details=array(
								"jd_setting_id"=>$justdial_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Justdial_setting_model->AddJustdialCredentialsDetails($jd_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==5)
		{
			$rule_count = (int)$this->input->post('jd_rule_count');
			$rule_activity_count = (int)$this->input->post('jd_rule_activity_count');
			
			
			if($rule_count>0 && $rule_activity_count>0)
			{

				$jd_data=array(
					"account_name"=>$justdial_account_name,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);				
				
				if($justdial_setting_id!='')
				{				
					$this->Justdial_setting_model->EditJustdialCredentials($jd_data,$justdial_setting_id);
					$this->Justdial_setting_model->DeleteJustdialCredentialsDetails($justdial_setting_id);					
				}
				else
				{			
					$justdial_setting_id=$this->Justdial_setting_model->AddCredentials($jd_data);				
				}			
				
				
				if($justdial_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = explode(',',$this->input->post('keyword_5_'.$i));
						$assign_to_arr = $this->input->post('justdial_assign_to_5_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$jd_details=array(
								"jd_setting_id"=>$justdial_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Justdial_setting_model->AddJustdialCredentialsDetails($jd_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('justdial_assign_to_5_other');
					$jd_details=array(
								"jd_setting_id"=>$justdial_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Justdial_setting_model->AddJustdialCredentialsDetails($jd_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}




		
		/*
		if(count($justdial_assign_to)>0)
		{
			$jd_data=array(
				"account_name"=>$justdial_account_name,
				"assign_rule"=>1,
				"assign_to"=>serialize($justdial_assign_to),
				"assign_start"=>0					
				);

			if($justdial_setting_id!='')
			{				
				$this->Justdial_setting_model->EditJustdialCredentials($jd_data,$justdial_setting_id);
			}
			else
			{				
				$this->Justdial_setting_model->AddCredentials($jd_data);
			}
			
			$status_str='success'; 
		}	
		else
		{
			$status_str='fail'; 
		}
		*/

    	 
        $result["status"] = $status_str;       
        $result["html"]='';
        echo json_encode($result);
        exit(0); 
    }

    function rander_lead_stage_list_ajax()
	{
		$list['rows']=$this->Setting_model->GetActiveLeadStageList();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/lead_stage_list_list_view_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function edit_lead_stage_setting()
    {
		$edit_id = $this->input->get('edit_id'); 
		$stage = $this->input->get('stage');
		
		if($edit_id!='' && $stage!='')
		{
			$post_data=array(
				"name"=>$stage			
				);
			$this->Setting_model->UpdateLeadStage($post_data,$edit_id);

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

    function delete_lead_stage()
	{
		$edit_id=$this->input->get('id');
		$post_data=array(
				"is_deleted"=>'Y'			
				);
		$return=$this->Setting_model->UpdateLeadStage($post_data,$edit_id);
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

	function add_lead_stage_setting()
    {
		$lead_stage_name = $this->input->get('lead_stage_name'); 
		$lead_stage_position = $this->input->get('lead_stage_position');
		$lead_stage_id_as_per_position = $this->input->get('lead_stage_id_as_per_position');
		
		if($lead_stage_name!='' && $lead_stage_position!='' && $lead_stage_id_as_per_position!='')
		{
			$get_row=$this->Setting_model->GetLeadStage($lead_stage_id_as_per_position);
			
			$get_sort_wise_list=$this->Setting_model->GetLeadStageSortWise($get_row->sort);
			
			$new_sort=($lead_stage_position==1)?($get_row->sort+1):($get_row->sort);
			$sort_tmp=($lead_stage_position==1)?($new_sort):($get_row->sort);
			$new_sort_wise_list=$this->Setting_model->GetLeadStageSortWise($sort_tmp);
			
			if(count($new_sort_wise_list))
			{
				foreach($new_sort_wise_list AS $row){
					$post_data_tmp=array(
						"sort"=>($row['sort']+1)
						);
					$this->Setting_model->UpdateLeadStage($post_data_tmp,$row['id']);
				}
			}
			$post_data=array(
						"name"=>$lead_stage_name,
						"class_name"=>'',
						"sort"=>$new_sort,
						"is_system_generated"=>'N',
						"is_active_lead"=>'Y'
						);
			$this->Setting_model->AddLeadStage($post_data);
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

    function resort_lead_stage()
    {
		$new_sort=$this->input->get('new_sort');
		$i = 1;
		foreach ($new_sort as $edit_id) 
		{
			$post_data=array(
				"sort"=>$i			
				);
			$this->Setting_model->UpdateLeadStage($post_data,$edit_id);
		    $i++;
		}		
		$status_str='success';		   	 
        $result["status"] = $status_str;
        echo json_encode($result);
        exit(0); 
    }

    function rander_my_document_ajax()
	{
		$list['rows']=$this->Setting_model->GetMyDocumentList();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/my_document_view_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function add_my_document()
    {
    	$msg='';
		$md_title = $this->input->post('md_title');
		if($md_title!='' && $_FILES['md_file']['tmp_name']!='')
		{
			if (!file_exists("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/my_documents/")) 
			{
				@mkdir("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/my_documents/", 0755, true);
			}
			$this->load->library('upload');
			$image_filename=NULL;				
			$config = array(
			'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/my_documents/",
			'allowed_types' => "gif|jpg|png|jpeg|pdf|doc|docx|xl|xls",
			'overwrite' => FALSE,
			'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
			// 'max_height' => "1768",
			// 'max_width' => "10024"
			);
			$this->upload->initialize($config);
		    if (!$this->upload->do_upload('md_file')) 
		    {
		        $error=$this->upload->display_errors('','');
		        //print_r($error);
				//exit;
		        //$this->load->view('upload_form', $error);
		        $status_str='fail';
		        $msg=$error;
		    } 
		    else
		    {
		    	$file_data=array('upload_data'=>$this->upload->data());
				$filename=$file_data['upload_data']['file_name']; 
				$post_data=array(
				"title"=>$md_title,
				"file_name"=>$filename,
				"created_on"=>date("Y-m-d H:i:s")
				);
				$this->Setting_model->AddMyDocument($post_data);			
				$status_str='success';
		    }
		}
		else
		{
			$status_str='fail'; 
		}   	 
        $result["status"]=$status_str; 
        $result["msg"]=$msg;
        echo json_encode($result);
        exit(0);
	}

	function delete_document()
	{
		$id=$this->input->get('id');	
		$get_info=$this->Setting_model->getMyDocument($id);	
		if($get_info['file_name']!="")
        {  
        	@unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/my_documents/'.$get_info['file_name']);
        }
		$return=$this->Setting_model->DeleteMyDocument($id);
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

	public function downloadMyDocument($file='')
	{	
		if($file!='')
		{	
			$this->load->helper(array('download'));	
			$file = base64_decode($file);
			$file_arr=explode("#",$file);
			$file_title=$file_arr[0];
			$file_name=$file_arr[1];
			$ext_arr=explode(".", $file_name);
			$ext=end($ext_arr);
			$pth=file_get_contents("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/my_documents/".$file_name);
			force_download($file_title.'.'.$ext, $pth); 
			exit;
		}
	}

	function rander_my_document_list_popup_ajax()
	{
		$list['rows']=$this->Setting_model->GetMyDocumentList();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/my_document_list_popup_view_ajax',$list,TRUE);
		
	    $data =array (
	       "html"=>$table
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function rander_my_document_for_quotation_ajax()
	{
		$list['rows']=$this->Setting_model->GetMyDocumenForQuotationtList();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/my_document_list_popup_view_for_quotation_ajax',$list,TRUE);
		
	    $data =array (
	       "html"=>$table
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function auto_regretted_save_submit()
    {
		$edit_id=$this->input->get('edit_id'); 
		$is_cronjobs_auto_regretted_on=$this->input->get('is_cronjobs_auto_regretted_on');
		$auto_regretted_day_interval=$this->input->get('auto_regretted_day_interval');		
		if($edit_id!='' && $is_cronjobs_auto_regretted_on!='' && $auto_regretted_day_interval!='')
		{			
			$post_data=array(
				"is_cronjobs_auto_regretted_on"=>$is_cronjobs_auto_regretted_on,
				"auto_regretted_day_interval"=>$auto_regretted_day_interval,
				"updated_at"=>date('Y-m-d H:i:s')
				);
			$this->Setting_model->UpdateCompany($post_data,$edit_id);
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

	public function general($id='')
    {
   		//is_logged_in();
   		// $com=get_company_profile();print_r($com);
		$data['error_msg'] 			= "";
   	 	$data['page_title'] 		= "Company Details";
		$data['page_keyword'] 		= "Company Details";
		$data['page_description']   = "Company Details"; 
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{			
			if ($this->company_validate_form_data() == TRUE)
			{	//print_r($this->input->post('indiamart_assign_to')); die();

				$id=$this->input->post('edit_id');
				$existing_profile_image = $this->input->post('existing_profile_image');

				$GetCompanyData=$this->Setting_model->GetCompanyData($id);
				$user_data=array(
							"name"=>$this->input->post('name'),
							"address"=>$this->input->post('address'),
							"city_id"=>$this->input->post('city_id'),
							"state_id" => $this->input->post('state_id'),
							"country_id"=>$this->input->post('country_id'),
							"pin"=>$this->input->post('pin'),
							"about_company"=>$this->input->post('about_company'),
							"gst_number"=>$this->input->post('gst_number'),
							"pan_number"=>$this->input->post('pan_number'),
							"default_currency"=>$this->input->post('default_currency'),
							"ceo_name"=>$this->input->post('ceo_name'),
							"contact_person"=>$this->input->post('contact_person'),
							"email1"=>$this->input->post('email1'),
							"email2"=>$this->input->post('email2'),
							"mobile1"=>$this->input->post('mobile1'),
							"mobile2"=>$this->input->post('mobile2'),
							"phone1"=>$this->input->post('phone1'),
							"phone2"=>$this->input->post('phone2'),
							"website"=>$this->input->post('website'),
							"quotation_cover_letter_body_text"=>$this->input->post('quotation_cover_letter_body_text'),
							"quotation_terms_and_conditions"=>$this->input->post('quotation_terms_and_conditions'),
							"quotation_cover_letter_footer_text"=>$this->input->post('quotation_cover_letter_footer_text'),
							"quotation_bank_details1"=>$this->input->post('quotation_bank_details1'),
							"quotation_bank_details2"=>$this->input->post('quotation_bank_details2'),
							"authorized_signatory"=>$this->input->post('authorized_signatory'),
							//"is_cronjobs_auto_regretted_on"=>($this->input->post('is_cronjobs_auto_regretted_on'))?'Y':'N',
							//"auto_regretted_day_interval"=>$this->input->post('auto_regretted_day_interval'),
							//"bank_credit_to"=>$this->input->post('bank_credit_to'),
							//"bank_name"=>$this->input->post('bank_name'),
							//"bank_acount_number"=>$this->input->post('bank_acount_number'),
							//"bank_branch_name"=>$this->input->post('bank_branch_name'),
							//"bank_branch_code"=>$this->input->post('bank_branch_code'),
							//"bank_ifsc_code"=>$this->input->post('bank_ifsc_code'),
							//"bank_swift_number"=>$this->input->post('bank_swift_number'),
							//"bank_telex"=>$this->input->post('bank_telex'),
							//"bank_address"=>$this->input->post('bank_address'),
							//"correspondent_bank_name"=>$this->input->post('correspondent_bank_name'),
							//"correspondent_bank_swift_number"=>$this->input->post('correspondent_bank_swift_number'),
							//"correspondent_account_number"=>$this->input->post('correspondent_account_number'),
							//"indiamart_glusr_mobile"=>$this->input->post('indiamart_glusr_mobile'),
							//"indiamart_glusr_mobile_key"=>$this->input->post('indiamart_glusr_mobile_key'),
							//"indiamart_assign_to"=>serialize($this->input->post('indiamart_assign_to')),
			                "updated_at"=>date('Y-m-d H:i:s')
							);
				
				$this->Setting_model->UpdateCompany($user_data,$id);	

				$this->load->library('upload', $config);
				$this->load->library('image_lib',''); //initialize image library

				if($_FILES['image']['tmp_name'])
				{
					$image_filename=NULL;					
					$config = array(
								   'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/",
								   'allowed_types' => "gif|jpg|png|jpeg",
								   'overwrite' => TRUE,
								   'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
								   // 'max_height' => "1768",
								   // 'max_width' => "10024"
								    );

					//$this->load->library('upload', $config);
				    //$this->load->library('image_lib',''); //initialize image library
				    $this->upload->initialize($config);
				    if (!$this->upload->do_upload('image')) 
				    {
				        $error = $this->upload->display_errors();
				        print_r($error);
						exit;
				        //$this->load->view('upload_form', $error);
				    } 
				    else
				    {
				    	$image['image_upload']=array('upload_data' => $this->upload->data()); //Image Upload							   
						$image_filename=$image['image_upload']['upload_data']['file_name']; //Image Name
											
						$config=NULL;

						$config['source_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/".$image_filename;
						$config['new_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/thumb/".$image_filename;
						$config['height']	= '120';
						
						$this->image_lib->initialize($config); 
						$this->image_lib->resize();

						$user_data_img=array(
		                 				'logo'=>$image_filename,
						 				'updated_at'=>date('Y-m-d H:i:s')
						 				);
						$result=$this->Setting_model->UpdateCompany($user_data_img,$id);
						
						
			   			//#############################################################//    
				        //############## DELETE EXISTING IMAGE IF ANY #################//
				        // if($existing_profile_image!="")
				        // {  
				        // 	@unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/company/logo/'.$existing_profile_image);
				        //     @unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/company/logo/thumb/'.$existing_profile_image);
				        // }
				        //#############################################################//
				    }
				}


				if($_FILES['digital_signature']['tmp_name'])
				{
					$image_filename=NULL;			
					$config=array();		
					$config = array(
				   'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/",
				   'allowed_types' => "gif|jpg|png|jpeg",
				   'overwrite' => FALSE,
				   'max_size' => "2048000", 
				   'encrypt_name'=>true,
				   // Can be set to particular file size , here it is 2 MB(2048 Kb)
								   // 'max_height' => "1768",
								   // 'max_width' => "10024"
					);

					
				    $this->upload->initialize($config);
				    if (!$this->upload->do_upload('digital_signature')) 
				    {
				        $error = $this->upload->display_errors();
				        print_r($error);
						exit;
				        //$this->load->view('upload_form', $error);
				    } 
				    else
				    {
				    	$image['image_upload']=array('upload_data' => $this->upload->data()); 
				    	//Image Upload
						$image_filename=$image['image_upload']['upload_data']['file_name']; //Image Name
						$user_data_img=array(
         				'digital_signature'=>$image_filename,
		 				'updated_at'=>date('Y-m-d H:i:s')
		 				);
						$result=$this->Setting_model->UpdateCompany($user_data_img,$id);
						
						
			   			//#############################################################//    
				        //############## DELETE EXISTING IMAGE IF ANY #################//
				        if($GetCompanyData['company_setting']!="")
				         {  
				         	@unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/company/logo/'.$GetCompanyData['company_setting']);
				        
				        }
				        //#############################################################//
				    }
				}				

				// --------------------------
				// reset company info session
				$this->session->unset_userdata('company_info','');
				$company_info=$this->Setting_model->GetCompanyData() ;
				$this->session->set_userdata('company_info',$company_info);


				$msg = "Record successfully updated..";
                $this->session->set_flashdata('success_msg', $msg);
				//CheckUserSpace();
				redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/setting/company/1');
			}
			else
			{
				//$this->form_validation->set_error_delimiters('', '');
                $msg = validation_errors(); // 'duplicate';
                $data['error_msg'] = $msg;
                $this->session->set_flashdata('error_msg', $msg);                    
                //redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/add_employee');
			}			
		}		
		$company_info=$this->Setting_model->GetCompanyData();    	 	
   	 	$data['company']=$company_info;
   	 	$data['id']=$company_info['id'];		
		$data['country_list']=$this->countries_model->GetCountriesList();
		$data['state_list']=$this->states_model->GetStatesList($company_info['country_id']);
		$data['city_list']=$this->cities_model->GetCitiesList($company_info['state_id']);	
		$data['user_list']=$this->user_model->GetUserListAll('');
		$data['currency_list']=$this->Currency_model->GetList();
		$data['sms_api_rows']=$this->Sms_setting_model->GetCredentials();
		$data['sms_forwarding_setting_rows']=$this->Sms_forwarding_setting_model->GetAllList();

		$data['whatsapp_api_rows']=$this->Whatsapp_setting_model->GetCredentials();
		$data['whatsapp_forwarding_setting_rows']=$this->Whatsapp_forwarding_setting_model->GetAllList();
		// $data['lead_stage_list']=$this->Setting_model->GetActiveLeadStageList();
		$this->load->view('admin/setting/general_view',$data);		
    }

	public function api($id='')
    {
		
   		//is_logged_in();
   		// $com=get_company_profile();print_r($com);
		$data['error_msg'] 			= "";
   	 	$data['page_title'] 		= "Company Details";
		$data['page_keyword'] 		= "Company Details";
		$data['page_description']   = "Company Details"; 
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{			
			if ($this->company_validate_form_data() == TRUE)
			{	//print_r($this->input->post('indiamart_assign_to')); die();

				$id=$this->input->post('edit_id');
				$existing_profile_image = $this->input->post('existing_profile_image');

				$GetCompanyData=$this->Setting_model->GetCompanyData($id);
				$user_data=array(
							"name"=>$this->input->post('name'),
							"address"=>$this->input->post('address'),
							"city_id"=>$this->input->post('city_id'),
							"state_id" => $this->input->post('state_id'),
							"country_id"=>$this->input->post('country_id'),
							"pin"=>$this->input->post('pin'),
							"about_company"=>$this->input->post('about_company'),
							"gst_number"=>$this->input->post('gst_number'),
							"pan_number"=>$this->input->post('pan_number'),
							"default_currency"=>$this->input->post('default_currency'),
							"ceo_name"=>$this->input->post('ceo_name'),
							"contact_person"=>$this->input->post('contact_person'),
							"email1"=>$this->input->post('email1'),
							"email2"=>$this->input->post('email2'),
							"mobile1"=>$this->input->post('mobile1'),
							"mobile2"=>$this->input->post('mobile2'),
							"phone1"=>$this->input->post('phone1'),
							"phone2"=>$this->input->post('phone2'),
							"website"=>$this->input->post('website'),
							"quotation_cover_letter_body_text"=>$this->input->post('quotation_cover_letter_body_text'),
							"quotation_terms_and_conditions"=>$this->input->post('quotation_terms_and_conditions'),
							"quotation_cover_letter_footer_text"=>$this->input->post('quotation_cover_letter_footer_text'),
							"quotation_bank_details1"=>$this->input->post('quotation_bank_details1'),
							"quotation_bank_details2"=>$this->input->post('quotation_bank_details2'),
							"authorized_signatory"=>$this->input->post('authorized_signatory'),
							//"is_cronjobs_auto_regretted_on"=>($this->input->post('is_cronjobs_auto_regretted_on'))?'Y':'N',
							//"auto_regretted_day_interval"=>$this->input->post('auto_regretted_day_interval'),
							//"bank_credit_to"=>$this->input->post('bank_credit_to'),
							//"bank_name"=>$this->input->post('bank_name'),
							//"bank_acount_number"=>$this->input->post('bank_acount_number'),
							//"bank_branch_name"=>$this->input->post('bank_branch_name'),
							//"bank_branch_code"=>$this->input->post('bank_branch_code'),
							//"bank_ifsc_code"=>$this->input->post('bank_ifsc_code'),
							//"bank_swift_number"=>$this->input->post('bank_swift_number'),
							//"bank_telex"=>$this->input->post('bank_telex'),
							//"bank_address"=>$this->input->post('bank_address'),
							//"correspondent_bank_name"=>$this->input->post('correspondent_bank_name'),
							//"correspondent_bank_swift_number"=>$this->input->post('correspondent_bank_swift_number'),
							//"correspondent_account_number"=>$this->input->post('correspondent_account_number'),
							//"indiamart_glusr_mobile"=>$this->input->post('indiamart_glusr_mobile'),
							//"indiamart_glusr_mobile_key"=>$this->input->post('indiamart_glusr_mobile_key'),
							//"indiamart_assign_to"=>serialize($this->input->post('indiamart_assign_to')),
			                "updated_at"=>date('Y-m-d H:i:s')
							);
				
				$this->Setting_model->UpdateCompany($user_data,$id);	

				$this->load->library('upload', $config);
				$this->load->library('image_lib',''); //initialize image library

				if($_FILES['image']['tmp_name'])
				{
					$image_filename=NULL;					
					$config = array(
								   'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/",
								   'allowed_types' => "gif|jpg|png|jpeg",
								   'overwrite' => TRUE,
								   'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
								   // 'max_height' => "1768",
								   // 'max_width' => "10024"
								    );

					//$this->load->library('upload', $config);
				    //$this->load->library('image_lib',''); //initialize image library
				    $this->upload->initialize($config);
				    if (!$this->upload->do_upload('image')) 
				    {
				        $error = $this->upload->display_errors();
				        print_r($error);
						exit;
				        //$this->load->view('upload_form', $error);
				    } 
				    else
				    {
				    	$image['image_upload']=array('upload_data' => $this->upload->data()); //Image Upload							   
						$image_filename=$image['image_upload']['upload_data']['file_name']; //Image Name
											
						$config=NULL;

						$config['source_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/".$image_filename;
						$config['new_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/thumb/".$image_filename;
						$config['height']	= '120';
						
						$this->image_lib->initialize($config); 
						$this->image_lib->resize();

						$user_data_img=array(
		                 				'logo'=>$image_filename,
						 				'updated_at'=>date('Y-m-d H:i:s')
						 				);
						$result=$this->Setting_model->UpdateCompany($user_data_img,$id);
						
						
			   			//#############################################################//    
				        //############## DELETE EXISTING IMAGE IF ANY #################//
				        // if($existing_profile_image!="")
				        // {  
				        // 	@unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/company/logo/'.$existing_profile_image);
				        //     @unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/company/logo/thumb/'.$existing_profile_image);
				        // }
				        //#############################################################//
				    }
				}


				if($_FILES['digital_signature']['tmp_name'])
				{
					$image_filename=NULL;			
					$config=array();		
					$config = array(
				   'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/logo/",
				   'allowed_types' => "gif|jpg|png|jpeg",
				   'overwrite' => FALSE,
				   'max_size' => "2048000", 
				   'encrypt_name'=>true,
				   // Can be set to particular file size , here it is 2 MB(2048 Kb)
								   // 'max_height' => "1768",
								   // 'max_width' => "10024"
					);

					
				    $this->upload->initialize($config);
				    if (!$this->upload->do_upload('digital_signature')) 
				    {
				        $error = $this->upload->display_errors();
				        print_r($error);
						exit;
				        //$this->load->view('upload_form', $error);
				    } 
				    else
				    {
				    	$image['image_upload']=array('upload_data' => $this->upload->data()); 
				    	//Image Upload
						$image_filename=$image['image_upload']['upload_data']['file_name']; //Image Name
						$user_data_img=array(
         				'digital_signature'=>$image_filename,
		 				'updated_at'=>date('Y-m-d H:i:s')
		 				);
						$result=$this->Setting_model->UpdateCompany($user_data_img,$id);
						
						
			   			//#############################################################//    
				        //############## DELETE EXISTING IMAGE IF ANY #################//
				        if($GetCompanyData['company_setting']!="")
				         {  
				         	@unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/company/logo/'.$GetCompanyData['company_setting']);
				        
				        }
				        //#############################################################//
				    }
				}				

				// --------------------------
				// reset company info session
				$this->session->unset_userdata('company_info','');
				$company_info=$this->Setting_model->GetCompanyData() ;
				$this->session->set_userdata('company_info',$company_info);


				$msg = "Record successfully updated..";
                $this->session->set_flashdata('success_msg', $msg);
				//CheckUserSpace();
				redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/setting/company/1');
			}
			else
			{
				//$this->form_validation->set_error_delimiters('', '');
                $msg = validation_errors(); // 'duplicate';
                $data['error_msg'] = $msg;
                $this->session->set_flashdata('error_msg', $msg);                    
                //redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/add_employee');
			}			
		}		
		$company_info=$this->Setting_model->GetCompanyData();    	 	
   	 	$data['company']=$company_info;
   	 	$data['id']=$company_info['id'];		
		$data['country_list']=$this->countries_model->GetCountriesList();
		$data['state_list']=$this->states_model->GetStatesList($company_info['country_id']);
		$data['city_list']=$this->cities_model->GetCitiesList($company_info['state_id']);	
		$data['user_list']=$this->user_model->GetUserListAll('');
		$data['currency_list']=$this->Currency_model->GetList();
		$data['sms_service_provider_list']=$this->Setting_model->GetSmsServiceProviderList();
		$data['sms_forwarding_setting_rows']=$this->Sms_forwarding_setting_model->GetAllList();
		$data['whatsapp_service_provider_list']=$this->Setting_model->GetWhatsappServiceProviderList();
		$data['whatsapp_forwarding_setting_rows']=$this->Whatsapp_forwarding_setting_model->GetAllList();

		$data['c2c_service_provider_list']=$this->Setting_model->GetC2cServiceProviderList();
		
		// $data['lead_stage_list']=$this->Setting_model->GetActiveLeadStageList();
		$this->load->view('admin/setting/api_view',$data);		
    }

	function rander_product_group_ajax()
	{
		$list['rows']=$this->Setting_model->GetProductGroupList();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/product_group_view_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function edit_product_group_setting()
    {
		$edit_id = $this->input->get('edit_id'); 
		$name = $this->input->get('name');
		
		if($edit_id!='' && $name!='')
		{
			$post_data=array(
				"name"=>$name			
				);
			$this->Setting_model->UpdateProductGroup($post_data,$edit_id);

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

	function delete_product_group()
	{
		$id=$this->input->get('id');		
		$return=$this->Setting_model->DeleteProductGroup($id);
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

	function add_product_group_setting()
    {
		$name = $this->input->get('name'); 
		
		if($name!='')
		{			
			$post_data=array(
						"parent_id"=>0,
						"name"=>$name,
						"is_active"=>'Y'
						);
			$this->Setting_model->AddProductGroup($post_data);
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


	function rander_product_category_ajax()
	{
		$list['group_list']=$this->Setting_model->GetProductGroupList();
		$list['rows']=$this->Setting_model->GetProductCategoryList();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/product_category_view_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function edit_product_category_setting()
    {
		$edit_id = $this->input->get('edit_id'); 
		$name = $this->input->get('name');
		
		if($edit_id!='' && $name!='')
		{
			$post_data=array(
				"name"=>$name			
				);
			$this->Setting_model->UpdateProductGroup($post_data,$edit_id);

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

	function add_product_category_setting()
    {
		$group_id = $this->input->get('group_id');
		$name = $this->input->get('name'); 
		
		if($name!='')
		{			
			$post_data=array(
						"parent_id"=>$group_id,
						"name"=>$name,
						"is_active"=>'Y'
						);
			$this->Setting_model->AddProductGroup($post_data);
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


	function rander_product_unit_type_ajax()
	{
		$list['rows']=$this->Setting_model->GetProductUnitTypeList();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/product_unit_type_view_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function edit_product_unit_type_setting()
    {
		$edit_id = $this->input->get('edit_id'); 
		$name = $this->input->get('name');
		
		if($edit_id!='' && $name!='')
		{
			$post_data=array(
				"type_name"=>$name			
				);
			$this->Setting_model->UpdateProductUnitType($post_data,$edit_id);

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

	function delete_product_unit_type()
	{
		$id=$this->input->get('id');		
		$return=$this->Setting_model->DeleteProductUnitType($id);
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

	function add_product_unit_type_setting()
    {
		$name = $this->input->get('name'); 
		
		if($name!='')
		{			
			$post_data=array(
						"type_name"=>$name
						);
			$this->Setting_model->AddProductUnitType($post_data);
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

	function rander_lead_source_ajax()
	{
		$list['rows']=$this->Setting_model->GetLeadSourceList();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/lead_source_view_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function edit_lead_source_setting()
    {
		$edit_id = $this->input->get('edit_id'); 
		$name = $this->input->get('name');
		
		if($edit_id!='' && $name!='')
		{
			$post_data=array(
				"name"=>$name			
				);
			$this->Setting_model->UpdateLeadSource($post_data,$edit_id);

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

	function delete_lead_source()
	{
		$id=$this->input->get('id');		
		$return=$this->Setting_model->DeleteLeadSource($id);
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

	function add_lead_source_setting()
    {
		$name = $this->input->get('name'); 
		
		if($name!='')
		{			
			$post_data=array(
						"name"=>$name
						);
			$this->Setting_model->AddLeadSource($post_data);
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

	function rander_lead_regret_reason_ajax()
	{
		$list['rows']=$this->Setting_model->GetLeadRegretReasonList();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/lead_regret_reason_view_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function edit_lead_regret_reason_setting()
    {
		$edit_id = $this->input->get('edit_id'); 
		$name = $this->input->get('name');
		
		if($edit_id!='' && $name!='')
		{
			$post_data=array(
				"name"=>$name			
				);
			$this->Setting_model->UpdateLeadRegretReason($post_data,$edit_id);

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

	function delete_lead_regret_reason()
	{
		$id=$this->input->get('id');		
		$return=$this->Setting_model->DeleteLeadRegretReason($id);
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

	function add_lead_regret_reason_setting()
    {
		$name = $this->input->get('name'); 
		
		if($name!='')
		{			
			$post_data=array(
						"name"=>$name
						);
			$this->Setting_model->AddLeadRegretReason($post_data);
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

	public function sms_template_change_view_rander_ajax()
	{
		$data=NULL;
		$id=$this->input->post('id');	
		$field=$this->input->post('field');	
		$data['api_list']=$this->Setting_model->GetSmsApiList();
		$data['template_row']=$this->Setting_model->GetSmsTemplate($id);
		$tid='';
		if($field!="" && $id!=""){
			$tid=get_value("$field","sms_forwarding_settings","id=".$id);
		}
		
		$data['edit_id']=$id;
		$data['field']=$field;
		$data['selected_tid']=$tid;
		$this->load->view('admin/setting/template_change_view_modal_ajax',$data);
	}	

	public function getsmstemplatelist()
	{
		$data=array();		
		$api_id=$this->input->post('api_id');
		$selected_id=$this->input->post('selected_id');
		
		if($api_id!='')	
		{
			$template_list=$this->Setting_model->GetSmsTemplateList($api_id);
			if($template_list)
			{
				echo '<option value="">Select</option>';
				foreach($template_list as $row)
				{
					$select_option=($selected_id==$row->id)?"selected":"";
					echo '<option value="'.$row->id.'" '.$select_option.'>'.$row->name.'</option>';
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

	function get_sms_template_view_ajax()
    {	
        $id=$this->input->post('id'); 
		$field=$this->input->post('field'); 
		$tid=get_value("$field","sms_forwarding_settings","id=".$id);
		$template_row=$this->Setting_model->GetSmsTemplate($tid);
		$last_comment='';
		$last_comment .='<p><u style="color:#e97630">API Name:</u> '.$template_row->api_name.'</p>';
		$last_comment .='<p><u style="color:#e97630">Name:</u> '.$template_row->name.'</p>';
		$last_comment .='<p><u style="color:#e97630">Template ID:</u> '.$template_row->template_id.'</p>';
		$last_comment .='<p><u style="color:#e97630">Template:</u> '.$template_row->text.'</p>';
		echo $last_comment;
    }

	function sms_forwarding_settings_template_update()
	{
		$id=$this->input->get('id');
		$field=$this->input->get('field');
		$value=$this->input->get('value');	

		$post_data=array($field=>$value);//print_r($post_data); die($id);
		$this->Sms_forwarding_setting_model->Editdata($post_data,$id);	

		$return['status'] = 'success';
		echo json_encode($return);
		exit(0);
	}

	function get_sms_template_del_ajax()
    {	
        $id=$this->input->post('id'); 
		$field=$this->input->post('field'); 		
		$post_data=array($field=>NULL);//print_r($post_data); die($id);
		$this->Sms_forwarding_setting_model->Editdata($post_data,$id);
		echo'success';		
    }


	// ======================================
	// SMS API functionality

	function rander_sms_credentials_list_ajax()
	{	
		$list['rows']=$this->Sms_setting_model->GetCredentials();		
	    $table = '';	    
	    $table = $this->load->view('admin/setting/sms_credentials_list_view_ajax',$list,TRUE);		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function get_sms_credentials()
	{
		$id=$this->input->get('id');
		$row=$this->Sms_setting_model->GetCredentialsDetails($id);
		$return['id'] = $row['id'];
		$return['sms_service_provider_id'] = $row['sms_service_provider_id'];
		$return['account_name'] = $row['name'];
		$return['sender'] = $row['sender'];
		$return['apikey'] = $row['apikey'];
		$return['entity_id'] = $row['entity_id'];
		echo json_encode($return);
		exit(0);
	}

	function delete_sms()
	{
		$del_id=$this->input->get('id');
		$this->Sms_setting_model->DeleteCredentials($del_id);
		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}
	

	function add_edit_sms_credentials()
    {
		$sms_setting_id=$this->input->post('sms_setting_id'); 
		$sms_service_provider_id=trim($this->input->post('sms_service_provider_id'));
		$sms_account_name=trim($this->input->post('sms_account_name'));
		$sms_sender=trim($this->input->post('sms_sender')); 
    	$sms_apikey=trim($this->input->post('sms_apikey'));
		$sms_entity_id=trim($this->input->post('sms_entity_id'));

		if($sms_account_name!='' && $sms_sender!='' && $sms_sender!='' && $sms_entity_id!='')
		{
			$sms_data=array(
				"sms_service_provider_id"=>$sms_service_provider_id,
				"name"=>$sms_account_name,
				"sender"=>$sms_sender,
				"apikey"=>$sms_apikey,
				"entity_id"=>$sms_entity_id			
				);
			// print_r($sms_data); die($sms_setting_id);
			if($sms_setting_id!='')
			{				
				$this->Sms_setting_model->EditCredentials($sms_data,$sms_setting_id);
			}
			else
			{				
				$this->Sms_setting_model->AddCredentials($sms_data);
			}
			
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

	public function sms_template_add_view_rander_ajax()
	{
		$data=NULL;
		$id=$this->input->post('id');	
		// $field=$this->input->post('field');	
		// $data['api_list']=$this->Setting_model->GetSmsApiList();
		// $data['template_row']=$this->Setting_model->GetSmsTemplate($id);
		// $tid='';
		// if($field!="" && $id!=""){
		// 	$tid=get_value("$field","sms_forwarding_settings","id=".$id);
		// }
		$data['api_row']=$this->Setting_model->GetSmsApi($id);
		$data['id']=$id;
		$this->load->view('admin/setting/template_add_view_modal_ajax',$data);
	}

	function add_edit_sms_template()
    {
		$sms_auto_template_id=$this->input->post('sms_auto_template_id'); 
		$sms_api_id=trim($this->input->post('sms_api_id'));
		$sms_name=trim($this->input->post('sms_t_name')); 
    	$sms_template_id=trim($this->input->post('sms_t_template_id'));
		$sms_text=trim($this->input->post('sms_t_text'));

		if($sms_api_id!='' && $sms_name!='' && $sms_template_id!='' && $sms_text!='')
		{
			$post_data=array(
				"sms_api_id"=>$sms_api_id,
				"template_id"=>$sms_template_id,
				"name"=>$sms_name,
				"text"=>$sms_text			
				);
			// print_r($post_data); die($sms_auto_template_id);
			if($sms_auto_template_id!='')
			{				
				$this->Sms_setting_model->EditTemplate($post_data,$sms_auto_template_id);
			}
			else
			{				
				$this->Sms_setting_model->AddTemplate($post_data);
			}
			
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

	function rander_sms_template_list_ajax()
	{	
		$sms_api_id=$this->input->get('sms_api_id'); 
		$list['rows']=$this->Sms_setting_model->GetTemplateList($sms_api_id);		
	    $table = '';	    
	    $table = $this->load->view('admin/setting/sms_template_list_view_ajax',$list,TRUE);		
	    $data =array (
	       "table"=>$table
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function get_sms_template()
	{
		$id=$this->input->get('id');
		$row=$this->Sms_setting_model->GetTemplateDetails($id);
		$return['id'] = $row['id'];
		$return['sms_api_id'] = $row['sms_api_id'];
		$return['template_id'] = $row['template_id'];
		$return['name'] = $row['name'];
		$return['text'] = $row['text'];
		echo json_encode($return);
		exit(0);
	}

	function delete_sms_template()
	{
		$del_id=$this->input->get('id');
		$this->Sms_setting_model->DeleteTemplate($del_id);
		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}
	// SMS functionality
	// ======================================	


	public function getwhatsapptemplatelist()
	{
		$data=array();		
		$api_id=$this->input->post('api_id');
		$selected_id=$this->input->post('selected_id');
		
		if($api_id!='')	
		{
			$template_list=$this->Setting_model->GetWhatsappTemplateList($api_id);
			if($template_list)
			{
				echo '<option value="">Select</option>';
				foreach($template_list as $row)
				{
					$select_option=($selected_id==$row->id)?"selected":"";
					echo '<option value="'.$row->id.'" '.$select_option.'>'.$row->name.'</option>';
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

	public function whatsapp_template_change_view_rander_ajax()
	{
		$data=NULL;
		$id=$this->input->post('id');	
		$field=$this->input->post('field');	
		$data['api_list']=$this->Setting_model->GetWhatsappApiList();
		$data['template_row']=array();
		$tid='';
		if($field!="" && $id!=""){
			$tid=get_value("$field","whatsapp_forwarding_settings","id=".$id);
			$data['template_row']=$this->Setting_model->GetWhatsappTemplate($tid);
		}
		
		$data['edit_id']=$id;
		$data['field']=$field;
		$data['selected_tid']=$tid;
		$this->load->view('admin/setting/whatsapp_template_change_view_modal_ajax',$data);
	}
	function whatsapp_forwarding_settings_template_update()
	{
		$id=$this->input->get('id');
		$field=$this->input->get('field');
		$value=$this->input->get('value');	

		$post_data=array($field=>$value);//print_r($post_data); die($id);
		$this->Whatsapp_forwarding_setting_model->Editdata($post_data,$id);	

		$return['status'] = 'success';
		echo json_encode($return);
		exit(0);
	}
	function get_whatsapp_template_view_ajax()
    {	
        $id=$this->input->post('id'); 
		$field=$this->input->post('field'); 
		$tid=get_value("$field","whatsapp_forwarding_settings","id=".$id);
		$template_row=$this->Setting_model->GetWhatsappTemplate($tid);
		$last_comment='';
		$last_comment .='<p><u style="color:#e97630">API Name:</u> '.$template_row->api_name.'</p>';
		$last_comment .='<p><u style="color:#e97630">Name:</u> '.$template_row->name.'</p>';
		$last_comment .='<p><u style="color:#e97630">Template ID:</u> '.$template_row->template_id.'</p>';
		if($template_row->template_variable){
			$last_comment .='<p><u style="color:#e97630">Template Variables:</u> '.$template_row->template_variable.'</p>';
		}
		
		echo $last_comment;
    }
	function get_whatsapp_template_del_ajax()
    {	
        $id=$this->input->post('id'); 
		$field=$this->input->post('field'); 		
		$post_data=array($field=>NULL);//print_r($post_data); die($id);
		$this->Whatsapp_forwarding_setting_model->Editdata($post_data,$id);
		echo'success';		
    }
	// ======================================
	// WhatsApp Functionality
	function rander_whatsapp_credentials_list_ajax()
	{	
		$list['rows']=$this->Whatsapp_setting_model->GetCredentials();		
	    $table = '';
	    $table = $this->load->view('admin/setting/whatsapp_credentials_list_view_ajax',$list,TRUE);		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function get_whatsapp_credentials()
	{
		$id=$this->input->get('id');
		$row=$this->Whatsapp_setting_model->GetCredentialsDetails($id);
		$return['id'] = $row['id'];
		$return['whatsapp_service_provider_id'] = $row['whatsapp_service_provider_id'];
		$return['account_name'] = $row['name'];
		$return['sender'] = $row['sender'];
		$return['apikey'] = $row['apikey'];
		$return['entity_id'] = $row['entity_id'];
		echo json_encode($return);
		exit(0);
	}

	function delete_whatsapp()
	{
		$del_id=$this->input->get('id');
		$this->Whatsapp_setting_model->DeleteCredentials($del_id);
		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}
	

	function add_edit_whatsapp_credentials()
    {
		$whatsapp_setting_id=$this->input->post('whatsapp_setting_id'); 
		$whatsapp_service_provider_id=trim($this->input->post('whatsapp_service_provider_id'));
		$whatsapp_account_name=trim($this->input->post('whatsapp_account_name'));
		$whatsapp_sender=trim($this->input->post('whatsapp_sender')); 
    	$whatsapp_apikey=trim($this->input->post('whatsapp_apikey'));

		if($whatsapp_account_name!='' && $whatsapp_sender!='' && $whatsapp_sender!='')
		{
			$post_data=array(
				"whatsapp_service_provider_id"=>$whatsapp_service_provider_id,
				"name"=>$whatsapp_account_name,
				"sender"=>$whatsapp_sender,
				"apikey"=>$whatsapp_apikey			
				);
			if($whatsapp_setting_id!='')
			{				
				$this->Whatsapp_setting_model->EditCredentials($post_data,$whatsapp_setting_id);
			}
			else
			{				
				$this->Whatsapp_setting_model->AddCredentials($post_data);
			}
			
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

	public function whatsapp_template_add_view_rander_ajax()
	{
		$data=NULL;
		$id=$this->input->post('id');	
		// $field=$this->input->post('field');	
		// $data['api_list']=$this->Setting_model->GetSmsApiList();
		// $data['template_row']=$this->Setting_model->GetSmsTemplate($id);
		// $tid='';
		// if($field!="" && $id!=""){
		// 	$tid=get_value("$field","sms_forwarding_settings","id=".$id);
		// }
		$data['api_row']=$this->Setting_model->GetWhatsappApi($id);
		$data['id']=$id;
		$this->load->view('admin/setting/whatsapp_template_add_view_modal_ajax',$data);
	}

	function add_edit_whatsapp_template()
    {
		$whatsapp_auto_template_id=$this->input->post('whatsapp_auto_template_id'); 
		$whatsapp_api_id=trim($this->input->post('whatsapp_api_id'));
		$whatsapp_name=trim($this->input->post('whatsapp_t_name')); 
    	$whatsapp_template_id=trim($this->input->post('whatsapp_t_template_id'));
		$whatsapp_template_variable=trim($this->input->post('whatsapp_t_template_variable'));
		$whatsapp_text=trim($this->input->post('whatsapp_t_text'));

		if($whatsapp_api_id!='' && $whatsapp_name!='' && $whatsapp_template_id!='')
		{
			$post_data=array(
				"whatsapp_api_id"=>$whatsapp_api_id,
				"template_id"=>$whatsapp_template_id,
				"name"=>$whatsapp_name,
				"template_variable"=>$whatsapp_template_variable,
				"text"=>$whatsapp_text			
				);
			// print_r($post_data); die($sms_auto_template_id);
			if($whatsapp_auto_template_id!='')
			{				
				$this->Whatsapp_setting_model->EditTemplate($post_data,$whatsapp_auto_template_id);
			}
			else
			{				
				$this->Whatsapp_setting_model->AddTemplate($post_data);
			}
			
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

	function rander_whatsapp_template_list_ajax()
	{	
		$whatsapp_api_id=$this->input->get('whatsapp_api_id'); 
		$list['rows']=$this->Whatsapp_setting_model->GetTemplateList($whatsapp_api_id);		
	    $table = '';	    
	    $table = $this->load->view('admin/setting/whatsapp_template_list_view_ajax',$list,TRUE);		
	    $data =array (
	       "table"=>$table
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function get_whatsapp_template()
	{
		$id=$this->input->get('id');
		$row=$this->Whatsapp_setting_model->GetTemplateDetails($id);
		$return['id'] = $row['id'];
		$return['whatsapp_api_id'] = $row['whatsapp_api_id'];
		$return['template_id'] = $row['template_id'];
		$return['name'] = $row['name'];
		$return['template_variable'] = $row['template_variable'];
		$return['text'] = $row['text'];
		echo json_encode($return);
		exit(0);
	}

	function delete_whatsapp_template()
	{
		$del_id=$this->input->get('id');
		$this->Whatsapp_setting_model->DeleteTemplate($del_id);
		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}

	// WhatsApp Functionality
	// ==========================================

	public function target($id='')
    {
   		//is_logged_in();
   		// $com=get_company_profile();print_r($com);
		$data['error_msg'] 			= "";
   	 	$data['page_title'] 		= "Target Setting";
		$data['page_keyword'] 		= "Target Setting";
		$data['page_description']   = "Target Setting"; 				
		$company_info=$this->Setting_model->GetCompanyData();    	 	
   	 	$data['company']=$company_info;
   	 	$data['id']=$company_info['id'];			
		$kpi_target_by=$this->Setting_model->kpi_target_by();
		$kpi_target_by_id_first=$this->Setting_model->kpi_target_by_id_first();
		$data['kpi_target_by_id_first']=$kpi_target_by_id_first['kpi_target_by_id'];
		$data['kpi_target_by']=$kpi_target_by['kpi_target_by'];

		
		$data['kpi_user_list']=	$this->Setting_model->kpi_user_list();
		$this->load->view('admin/setting/target_view',$data);		
    }

	function rander_kpi_user_option()
	{
		$list=array();
		$list['kpi_user_list']=	$this->Setting_model->kpi_user_list();
		$html = $this->load->view('admin/setting/rander_kpi_user_option_view',$list,TRUE);		
	    $data =array (
	       "html"=>$html
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
		exit;
	}

	function rander_kpi_setting_view_ajax()
	{
		$kpi_target_by=$this->input->get('kpi_target_by');
		// $kpi_setting_id=$this->input->get('kpi_setting_id');

		$list['rows']=array();
		if($kpi_target_by=='F'){
			$list['rows']=$this->Setting_model->GetFunctionalArea();
		}
		else if($kpi_target_by=='D'){
			$list['rows']=$this->Setting_model->GetDepartment();
		}
		else if($kpi_target_by=='U'){
			$list['rows']=$this->Setting_model->GetUser();	
		}
		else{
			
		}
		$list['kpi_target_by']=$kpi_target_by;	
		$list['kpi_rows']=$this->Setting_model->GetKpiList(); 
		// $list['kpi_setting_id']=$kpi_setting_id;	        
	    $html = $this->load->view('admin/setting/rander_kpi_setting_view_ajax',$list,TRUE);
		
	    $data =array (
	       "html"=>$html
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
		exit;
	}

	function rander_kpi_target_by_view_ajax()
	{
		$kpi_target_by=$this->input->get('kpi_target_by');
		$kpi_target_by_id=$this->input->get('kpi_target_by_id');
		// $kpi_setting_id=$this->input->get('kpi_setting_id');
		$list['kpi_rows']=$this->Setting_model->GetKpiList();
		$list['kpi_target_by']=$kpi_target_by;	 
		$list['kpi_target_by_id']=$kpi_target_by_id;
		if($kpi_target_by=='F' && $kpi_target_by_id!=''){
			$name=get_value("name","functional_area","id=".$kpi_target_by_id);
			$list['name']='Choose KPI for functional area: '.$name;
		}
		else if($kpi_target_by=='D'){			
			$name=get_value("category_name","category","id=".$kpi_target_by_id);
			$list['name']='Choose KPI for department: '.$name;
		}
		else if($kpi_target_by=='U'){			
			$name=get_value("name","user","id=".$kpi_target_by_id);
			$list['name']='Choose KPI for user: '.$name;	
		}
		else{
			
		}
		
		$list['get_kpi_setting_info']=$this->Setting_model->get_kpi_setting_info($kpi_target_by,$kpi_target_by_id);	        
	    $html = $this->load->view('admin/setting/rander_kpi_target_by_view_ajax',$list,TRUE);		
	    $data =array (
	       "html"=>$html
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
		exit;
	}

	function add_edit_kpi_setting()
    {
		$kpi_target_by=$this->input->get('kpi_target_by');
		$kpi_target_by_id=$this->input->get('kpi_target_by_id'); 
		$kpi_ids_str=$this->input->get('kpi_ids_str'); 
		// $kpi_names_str=$this->input->get('kpi_names_str');
		$kpi_names_str=''; 
		if($kpi_ids_str){
			$kpi_ids_arr=explode(",",$kpi_ids_str); 
			if(count($kpi_ids_arr))
			{
				for($i=0;$i<count($kpi_ids_arr);$i++)
				{
					$kpiid=$kpi_ids_arr[$i];
					$kpi_names_str .=get_value('name','kpi','id='.$kpiid).',';	
				}
			}
			$kpi_names_str=rtrim($kpi_names_str, ',');
		}		
		$kpi_target_by_existing=$this->Setting_model->kpi_target_by();
		// echo $kpi_target_by_existing.'!='.$kpi_target_by;die();
		if($kpi_target_by_existing['kpi_target_by']!=$kpi_target_by){
			$this->Setting_model->truncate_kpi_setting();
			$this->Setting_model->truncate_kpi_setting_user_wise();
			$this->Setting_model->truncate_kpi_setting_user_wise_set_target();
		}

		$get_kpi_setting_info=$this->Setting_model->get_kpi_setting_info($kpi_target_by,$kpi_target_by_id);

		if(count($get_kpi_setting_info)==0)
		{
			$post_data=array(
							'kpi_target_by'=>$kpi_target_by,
							'kpi_target_by_id'=>$kpi_target_by_id,
							'kpi_ids'=>$kpi_ids_str,
							'kpi_names'=>$kpi_names_str,
							'created_at'=>date("Y-m-d H:i:s"),
							'updated_at'=>date("Y-m-d H:i:s")
						    );
			$r=$this->Setting_model->add_kpi_setting($post_data);
			if($r)
			{				
				// if($kpi_target_by=='U')
				// {
				// 	$post_data=array('kpi_setting_id'=>$r,'user_id'=>$kpi_target_by_id);
				// 	$this->Setting_model->add_kpi_setting_user_wise($post_data);
				// }
				// else
				// {					
				// 	$get_user_list=$this->Setting_model->get_user_by_target($kpi_target_by,$kpi_target_by_id);
				// 	if(count($get_user_list))
				// 	{
				// 		foreach($get_user_list AS $row)
				// 		{
				// 			$post_data=array('kpi_setting_id'=>$r,'user_id'=>$row['id']);
				// 			$this->Setting_model->add_kpi_setting_user_wise($post_data);
				// 		}
				// 	}
				// }
			}
			$status_str='success';
		}
		else
		{
			if($kpi_ids_str!='')
			{
				$post_data=array(
					'kpi_target_by'=>$kpi_target_by,
					'kpi_target_by_id'=>$kpi_target_by_id,
					'kpi_ids'=>$kpi_ids_str,
					'kpi_names'=>$kpi_names_str,
					'updated_at'=>date("Y-m-d H:i:s")
					);
				$this->Setting_model->edit_kpi_setting($post_data,$get_kpi_setting_info['id']);							
			}
			else
			{
				$this->Setting_model->delete_kpi_setting($get_kpi_setting_info['id']);
				$this->Setting_model->delete_kpi_setting_user_wise($get_kpi_setting_info['id']);
			}			
			$status_str='success';
		}
    	
        $result["status"] = $status_str;  
        echo json_encode($result);
        exit(0); 
    }

	function chk_for_set_target()
	{
		
		$return=$this->Setting_model->kpi_target_by();		
	    $return =array (
	       "kpi_setting_count"=>count($return)
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($return); 
	}

	public function target_set()
    {
		$data['error_msg'] 			= "";
   	 	$data['page_title'] 		= "Set Target Setting";
		$data['page_keyword'] 		= "Set Target Setting";
		$data['page_description']   = "Set Target Setting"; 
		$data['kpi_target_by']=$this->Setting_model->kpi_target_by();
		$data['kpi_user_list']=	$this->Setting_model->kpi_user_list();
		$this->load->view('admin/setting/target_set_view',$data);		
    }

	function rander_user_by_kpi_type()
	{
		
		$kpi_target_by=$this->input->get('kpi_by');

		if($kpi_target_by=='F'){
		}
		else if($kpi_target_by=='D'){
		}
		else if($kpi_target_by=='U'){	
		}
		else{
			
		}
		$list['kpi_target_by_list']=$this->Setting_model->kpi_target_by_list($kpi_target_by);
		$list['kpi_target_by']=$kpi_target_by;	    
		$list['user_list']=($kpi_target_by=='U')?$list['kpi_target_by_list']:array();   
	    $html = $this->load->view('admin/setting/rander_user_by_kpi_type_view_ajax',$list,TRUE);		
	    $data =array (
	       "html"=>$html
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
		exit; 
	}

	function rander_user_by_kpi_id()
	{
		
		$kpi_target_by=$this->input->get('tmp_kpi_target_by');
		$kpi_setting_id=$this->input->get('kpi_setting_id');
		$id=$this->input->get('id');

		if($kpi_target_by=='F'){
		}
		else if($kpi_target_by=='D'){
		}
		else if($kpi_target_by=='U'){	
		}
		else{
			
		}
		$list['kpi_setting_id']=$kpi_setting_id;
		$list['user_list']=$this->Setting_model->get_user_by_target($kpi_target_by,$id);		  
	    $html = $this->load->view('admin/setting/rander_user_list_view_ajax',$list,TRUE);		
	    $data =array (
	       "html"=>$html,
		   "user_count"=>count($list['user_list'])
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
		exit; 
	}
	function rander_user_wise_kpi_set()
	{
		
		$user_id=$this->input->get('user_id');
		$kpi_setting_id=$this->input->get('kpi_setting_id');
		$html='';
		if($user_id!='' && $kpi_setting_id!='')
		{
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
			$html = $this->load->view('admin/setting/rander_user_wise_kpi_set_view_ajax',$list,TRUE);
		}
		else
		{
			$kpi_user_list=$this->Setting_model->kpi_user_list();			
			if(count($kpi_user_list))
			{
				foreach($kpi_user_list AS $row)
				{
					$kpi_setting_id=$row['kpi_setting_id'];
					$user_id=$row['id'];

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
					$html .=$this->load->view('admin/setting/rander_user_wise_kpi_set_view_ajax',$list,TRUE);

				}
			}
		}	

				
	    $data =array (
	       "html"=>$html
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
		exit; 
	}

	function set_kpi_for_user()
    {
		// print_r($_POST); die();
		$user_id=$this->input->post('uid');
		$kpi_setting_id=$this->input->post('kpi_setting_id_'.$user_id); 
		// $user_id=$this->input->post('set_kpi_user_id');		
		$set_target_by=$this->input->post('set_report_for_'.$user_id);

		$is_apply_pip=($this->input->post('is_apply_pip_'.$user_id))?$this->input->post('is_apply_pip_'.$user_id):'N';
		$total_target_threshold=$this->input->post('set_total_target_threshold_'.$user_id);
		$target_threshold_for_x_consecutive_month=$this->input->post('set_total_target_threshold_for_x_consecutive_month_'.$user_id);

		$is_apply_pli=($this->input->post('is_apply_pli_'.$user_id))?$this->input->post('is_apply_pli_'.$user_id):'N';
		$monthly_salary=$this->input->post('monthly_salary_'.$user_id);
		$pli_in=$this->input->post('pli_in_'.$user_id);

		$kpi_arr=$this->input->post('kpi');
		$weighted_score_arr=$this->input->post('weighted_score_'.$user_id);
		$target_arr=$this->input->post('target_'.$user_id);
		$min_target_threshold_arr=$this->input->post('min_target_threshold_'.$user_id);
		
		$kpi_setting_user_wise_row=$this->Setting_model->kpi_setting_user_wise_row($kpi_setting_id,$user_id);
		if(count($kpi_setting_user_wise_row))
		{
			$kpi_setting_user_wise_id=$kpi_setting_user_wise_row['id'];
			$post_data=array(
				'kpi_setting_id'=>$kpi_setting_id,
				'user_id'=>$user_id,
				'set_target_by'=>$set_target_by,
				'target_threshold'=>$total_target_threshold,
				'target_threshold_for_x_consecutive_month'=>$target_threshold_for_x_consecutive_month,
				'is_apply_pip'=>$is_apply_pip,
				'is_apply_pli'=>$is_apply_pli,
				'monthly_salary'=>$monthly_salary,
				'pli_in'=>$pli_in
			);
			$this->Setting_model->EditkpiSettingUserWise($post_data,$kpi_setting_user_wise_id);			

		}
		else
		{
			$post_data=array(
				'kpi_setting_id'=>$kpi_setting_id,
				'user_id'=>$user_id,
				'set_target_by'=>$set_target_by,
				'target_threshold'=>$total_target_threshold,
				'target_threshold_for_x_consecutive_month'=>$target_threshold_for_x_consecutive_month,
				'is_apply_pip'=>$is_apply_pip,
				'is_apply_pli'=>$is_apply_pli,
				'monthly_salary'=>$monthly_salary,
				'pli_in'=>$pli_in
			);
			$kpi_setting_user_wise_id=$this->Setting_model->AddkpiSettingUserWise($post_data);			
		}

		if($kpi_setting_user_wise_id)
		{
			if(count($weighted_score_arr))
			{
				for($i=0;$i<count($weighted_score_arr);$i++)
				{
					$kpi_id=$kpi_arr[$i];
					$weighted_score=$weighted_score_arr[$i];
					$target=$target_arr[$i];
					$min_target_threshold=$min_target_threshold_arr[$i];

					$kpi_setting_user_wise_set_target_row=$this->Setting_model->kpi_setting_user_wise_set_target_row($kpi_setting_user_wise_id,$kpi_id);

					if(count($kpi_setting_user_wise_set_target_row))
					{
						$post_data=array(
							'weighted_score'=>$weighted_score,
							'target'=>$target,
							'min_target_threshold'=>$min_target_threshold,
							'created_at'=>date("Y-m-d H:i:s"),
							'updated_at'=>date("Y-m-d H:i:s")
						);
						$this->Setting_model->EditkpiSettingUserWiseSetTarget($post_data,$kpi_setting_user_wise_set_target_row['id']);
					}
					else
					{
						$post_data=array(
							'kpi_setting_user_wise_id'=>$kpi_setting_user_wise_id,
							'kpi_id'=>$kpi_id,
							'weighted_score'=>$weighted_score,
							'target'=>$target,
							'min_target_threshold'=>$min_target_threshold,
							'created_at'=>date("Y-m-d H:i:s"),
							'updated_at'=>date("Y-m-d H:i:s")
						);
						$this->Setting_model->AddkpiSettingUserWiseSetTarget($post_data);
					}
					

				}
			}
		}

		$status_str='success';
        $result["status"] = $status_str;  
        echo json_encode($result);
        exit(0); 
    }

	function rander_kpi_list_ajax()
	{	
		$list['rows']=$this->Setting_model->GetKpis();		
	    $table = '';	    
	    $table = $this->load->view('admin/setting/kpi_list_view_ajax',$list,TRUE);		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
		exit;
	}

	function add_edit_key_performance_indicator()
    {
		$key_performance_indicator_id=$this->input->post('key_performance_indicator_id'); 
		$key_performance_indicator_name=trim($this->input->post('key_performance_indicator_name'));
		

		if($key_performance_indicator_name!='')
		{
			$post_data=array(
				"reserve_keyword"=>str_replace(' ', '', strtolower($key_performance_indicator_name)),
				"name"=>$key_performance_indicator_name,
				"is_system_generated"=>'N',
				"is_active"=>'Y',
				"is_deleted"=>'N'			
				);
			// print_r($post_data); die($sms_setting_id);
			if($key_performance_indicator_id!='')
			{				
				$this->Setting_model->Edit_key_performance_indicator($post_data,$key_performance_indicator_id);
			}
			else
			{				
				$this->Setting_model->Add_key_performance_indicator($post_data);
			}
			
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

	function get_key_performance_indicator()
	{
		$id=$this->input->get('id');
		$row=$this->Setting_model->Get_key_performance_indicator($id);
		$return['id'] = $row['id'];
		$return['name'] = $row['name'];
		echo json_encode($return);
		exit(0);
	}

	function delete_key_performance_indicator()
	{
		$del_id=$this->input->get('id');
		$this->Setting_model->Delete_key_performance_indicator($del_id);
		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}

	function auto_session_expire_save_submit()
    {
		$edit_id=$this->input->get('edit_id'); 
		$is_session_expire_for_idle=$this->input->get('is_session_expire_for_idle');
		$idle_time=$this->input->get('idle_time');		
		if($edit_id!='' && $is_session_expire_for_idle!='' && $idle_time!='')
		{			
			$post_data=array(
				"is_session_expire_for_idle"=>$is_session_expire_for_idle,
				"idle_time"=>$idle_time,
				"updated_at"=>date('Y-m-d H:i:s')
				);
			$this->Setting_model->UpdateCompany($post_data,$edit_id);
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

	function google_map_api_key_submit()
    {
		$edit_id=$this->input->get('edit_id'); 
		$google_map_api_key=$this->input->get('google_map_api_key');
		
		if($edit_id!='')
		{			
			$post_data=array(
				"google_map_api_key"=>$google_map_api_key,
				"updated_at"=>date('Y-m-d H:i:s')
				);
			$this->Setting_model->UpdateCompany($post_data,$edit_id);
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

	function rander_business_type_ajax()
	{
		$list['rows']=$this->Setting_model->GetBusinessTypeList();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/business_type_view_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function rander_employee_type_ajax()
	{
		$list['rows']=$this->Setting_model->GetEmployeeTypeList();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/employee_type_view_ajax',$list,TRUE);
		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function add_business_type_setting()
    {
		$name = $this->input->get('name'); 
		
		if($name!='')
		{			
			$post_data=array(
						"name"=>$name
						);
			$this->Setting_model->AddBusinessType($post_data);
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

	function edit_business_type_setting()
    {
		$edit_id = $this->input->get('edit_id'); 
		$name = $this->input->get('name');
		
		if($edit_id!='' && $name!='')
		{
			$post_data=array(
				"name"=>$name			
				);
			$this->Setting_model->UpdatedBusinessType($post_data,$edit_id);

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

	function delete_business_type()
	{
		$id=$this->input->get('id');		
		$return=$this->Setting_model->DeleteBusinessType($id);
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

	function copy_user_wise_kpi_set()
	{
		
		$to_uid=$this->input->get('to_uid');

		$html='';
		$list['user_list']=$this->Setting_model->user_list_for_copy_kpi($to_uid);
		$list['to_uid']=$to_uid;
	    $html = $this->load->view('admin/setting/user_list_for_kpi_copy_ajax',$list,TRUE);

	    $data =array (
	       "html"=>$html
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
		exit; 
	}

	function rander_menu_label_alias_list_ajax()
	{	
		$admin_session_data=$this->session->userdata('admin_session_data');
		$client_id=$admin_session_data['client_id'];
		$user_id=$admin_session_data['user_id'];
		$label_alias_file_url=file_upload_absolute_path().'assets/uploads/clients/'.$client_id.'/label_alias/default.txt';
		if (file_exists($label_alias_file_url)) {
			$label_alias_content=file_get_contents($label_alias_file_url);				
			$list['menu_alies_rows']=(array)json_decode($label_alias_content);
		}	
		else
		{
			// $list['rows']=get_menu_label_alias();	
		}
		$list['rows']=get_menu_label_alias();
	    $table = '';	    
	    $table = $this->load->view('admin/setting/menu_label_alias_list_view_ajax',$list,TRUE);		
	    $data =array (
	       "table"=>$table
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
		exit;
	}

	function update_menu_alias()
    {
		$admin_session_data=$this->session->userdata('admin_session_data');
		$client_id=$admin_session_data['client_id'];
		$user_id=$admin_session_data['user_id'];
		
		$menu_label_arr=$this->input->post('menu_label[]'); 
		
		$arr=array();
		if(count($menu_label_arr))
		{
			foreach($menu_label_arr AS $k=>$v)
			{				
				$arr['menu'][$k]=$v;
			}
		}
		$arr_json=json_encode($arr);

		// ====================================================================================
		$label_alias_file_path=file_upload_absolute_path().'assets/uploads/clients/'.$client_id.'/label_alias/';
		$file_name='default.txt';
		$label_alias_file_url=$label_alias_file_path.$file_name;		
		if (file_exists($label_alias_file_url)) {
		}
		else
		{
			@mkdir($label_alias_file_path, 0777, true);
		}
		// $oldMessage = '';
		// $deletedFormat = '';
		// $str=file_get_contents($label_alias_file_url);
		// $str=str_replace($oldMessage, $deletedFormat,$arr_json);
		file_put_contents($label_alias_file_url, $arr_json);		
		// ====================================================================================

		
		$status_str='success';    	 
        $result["status"] = $status_str;       
        $result["html"]='';
        echo json_encode($result);
        exit(0); 
    }


	public function download_website_api_document()
	{
		$data=array();
		// $company=$this->Setting_model->GetCompanyData(1);
		// $company=get_company_profile();
		// print_r($company); die();
		// -----------------------------
	    // Generate PDF Script Start 
		$pdf_html = $this->load->view('admin/setting/download_website_api_document_pdf_view',$data,TRUE);  
		$this->load->library('m_pdf'); //load mPDF library
		$mpdf = new mPDF();
		// $mpdf->fontdata["century-gothic"];
		$mpdf->showImageErrors = true;
		$mpdf->AddPage('P', // L - landscape, P - portrait 
            '', '', '', '',
            4, // margin_left
            4, // margin right
            4, // margin top
            0, // margin bottom
            0, // margin header
            4	// margin footer
           );		
		$mpdf->SetTitle("Website API");
        $mpdf->SetAuthor('lmsbaba.com');
        $mpdf->SetWatermarkText('lmsbaba.com');
        $mpdf->showWatermarkText = true;
        $mpdf->watermarkTextAlpha = 0.08;
        $mpdf->SetDisplayMode('fullpage');		
		$mpdf->SetDisplayMode('fullpage');
        $mpdf->defaultfooterfontstyle='';
        $mpdf->defaultfooterfontsize=8;
        $mpdf->defaultfooterline=0;
        $mpdf->WriteHTML($pdf_html);
        return $mpdf->Output('LMSBABA Website API.pdf', "D"); 
	}

	function rander_jd_rule_wise_view()
	{
		$rule_id=$this->input->get('rule_id');
		$jd_s_id=$this->input->get('jd_s_id');
		
		$data=array();

		if($jd_s_id>0)
		{
			$data['row']=$this->Justdial_setting_model->GetJustdialCredentialsDetails($jd_s_id);
			$data['rules']=$this->Justdial_setting_model->get_rules($jd_s_id);
		}
		else
		{
			$data['row']=array();
			$data['rules']=array();
		}
		
		$data['rule_id']=$rule_id;
		$data['jd_s_id']=$jd_s_id;
		$data['user_list']=$this->user_model->GetUserListAll('');
		// $data['state_list']=$this->states_model->GetStatesList('101');
	    $html = $this->load->view('admin/setting/jd_rule_wise_view_ajax',$data,TRUE);
		
	    $return =array (
	       "html"=>$html,
	       "msg"=>""
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($return); 
	}

	function rander_jd_rule_wise_view_outer_div_ajax()
	{
		$jd_s_id=$this->input->get('jd_s_id');
		$cnt=$this->input->get('cnt');
		$rule_id=$this->input->get('rule_id');
		$mode=$this->input->get('mode');
		$data=array();
		if($jd_s_id>0)
		{
			$data['rules']=$this->Justdial_setting_model->get_rules($jd_s_id);
		}
		else
		{
			$data['rules']=array();
		}
		$data['cnt']=$cnt;
		$data['rule_id']=$rule_id;
		$data['jd_s_id']=$jd_s_id;
		$data['user_list']=$this->user_model->GetUserListAll('');
		if($mode=='edit')
		{
			$index=($cnt-1);
			$existing_find_to_arr=unserialize($data['rules'][$index]['find_to']);
			$existing_find_to_str='';
			if(count($existing_find_to_arr))
			{
				$existing_find_to_str=implode(",",$existing_find_to_arr);
			}			
			if($rule_id=='2'){
				$data['country_list']=$this->countries_model->GetCountriesList($existing_find_to_str);	
			}
			else if($rule_id=='3'){
				$data['state_list']=$this->states_model->GetOnlyIndianStatesListByID($existing_find_to_str);	
			}
			else if($rule_id=='4'){
				$data['city_list']=$this->cities_model->GetAllIndianCitiesList($existing_find_to_str);
			}			
		}	
		else
		{
			$data['country_list']=array();		
			$data['state_list']=array();		
			$data['city_list']=array();
		}
		// $data['state_list']=$this->states_model->GetStatesList('101');
		$data['mode']=$mode;
	    $html = $this->load->view('admin/setting/rander_jd_rule_wise_view_outer_div_ajax',$data,TRUE);
		
	    $return =array (
	       "html"=>$html,
	       "msg"=>""
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($return); 
	}


	function rander_web_rule_wise_view()
	{
		$rule_id=$this->input->get('rule_id');
		$web_s_id=$this->input->get('web_s_id');
		
		$data=array();

		if($web_s_id>0)
		{
			$data['row']=$this->Website_setting_model->GetWebsiteCredentialsDetails($web_s_id);
			$data['rules']=$this->Website_setting_model->get_rules($web_s_id);
		}
		else
		{
			$data['row']=array();
			$data['rules']=array();
		}
		
		$data['rule_id']=$rule_id;
		$data['web_s_id']=$web_s_id;
		$data['user_list']=$this->user_model->GetUserListAll('');
		$data['state_list']=$this->states_model->GetStatesList('101');
	    $html = $this->load->view('admin/setting/web_rule_wise_view_ajax',$data,TRUE);
		
	    $return =array (
	       "html"=>$html,
	       "msg"=>""
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($return); 
	}


	function rander_web_rule_wise_view_outer_div_ajax()
	{
		$web_s_id=$this->input->get('web_s_id');
		$cnt=$this->input->get('cnt');
		$rule_id=$this->input->get('rule_id');
		$mode=$this->input->get('mode');
		$data=array();
		if($web_s_id>0)
		{
			$data['rules']=$this->Website_setting_model->get_rules($web_s_id);
		}
		else
		{
			$data['rules']=array();
		}
		$data['cnt']=$cnt;
		$data['rule_id']=$rule_id;
		$data['web_s_id']=$web_s_id;
		$data['user_list']=$this->user_model->GetUserListAll('');
		if($mode=='edit')
		{
			$index=($cnt-1);
			$existing_find_to_arr=unserialize($data['rules'][$index]['find_to']);
			$existing_find_to_str='';
			if(count($existing_find_to_arr))
			{
				$existing_find_to_str=implode(",",$existing_find_to_arr);
			}			
			if($rule_id=='2'){
				$data['country_list']=$this->countries_model->GetCountriesList($existing_find_to_str);	
			}
			else if($rule_id=='3'){
				$data['state_list']=$this->states_model->GetOnlyIndianStatesListByID($existing_find_to_str);	
			}
			else if($rule_id=='4'){
				$data['city_list']=$this->cities_model->GetAllIndianCitiesList($existing_find_to_str);
			}			
		}	
		else
		{
			$data['country_list']=array();		
			$data['state_list']=array();		
			$data['city_list']=array();
		}
		// $data['state_list']=$this->states_model->GetStatesList('101');
		$data['mode']=$mode;
	    $html = $this->load->view('admin/setting/rander_web_rule_wise_view_outer_div_ajax',$data,TRUE);
		
	    $return =array (
	       "html"=>$html,
	       "msg"=>""
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($return); 
	}

	function add_edit_website_credentials()
    {
		$website_setting_id = $this->input->post('website_setting_id'); 
		$website_account_name = 'Website API';		
		$website_assign_to = $this->input->post('website_assign_to');

		$assign_rule = $this->input->post('web_assign_rule');

		if($assign_rule==1)
		{
			if(count($website_assign_to)>0)
			{

				$web_data=array(
					"account_name"=>$website_account_name,
					"assign_rule"=>$assign_rule,
					"assign_to"=>serialize($website_assign_to),
					"assign_start"=>0					
					);
	
				if($website_setting_id!='')
				{			
					// --------------------------------- 
					$existing_assign_rule=get_value('assign_rule','website_api_setting','id='.$website_setting_id);	
					if($existing_assign_rule!=1)
					{
						$this->Website_setting_model->DeleteWebsiteCredentialsDetails($website_setting_id);
					}
					// ---------------------------------	
					$this->Website_setting_model->EditWebsiteCredentials($web_data,$website_setting_id);
				}
				else
				{				
					$this->Website_setting_model->AddCredentials($web_data);
				}								
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==2) // country
		{
			$rule_count = (int)$this->input->post('web_rule_count');
			$rule_activity_count = (int)$this->input->post('web_rule_activity_count');
			
			
			if($rule_count>0 && $rule_activity_count>0)
			{

				$web_data=array(
					"account_name"=>$website_account_name,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);				
				
				if($website_setting_id!='')
				{				
					$this->Website_setting_model->EditWebsiteCredentials($web_data,$website_setting_id);
					$this->Website_setting_model->DeleteWebsiteCredentialsDetails($website_setting_id);					
				}
				else
				{			
					$website_setting_id=$this->Website_setting_model->AddCredentials($web_data);				
				}			
				
				
				if($website_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('country_2_'.$i);
						$assign_to_arr = $this->input->post('website_assign_to_2_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$web_details=array(
								"web_setting_id"=>$website_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Website_setting_model->AddWebsiteCredentialsDetails($web_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('website_assign_to_2_other');
					$web_details=array(
								"web_setting_id"=>$website_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Website_setting_model->AddWebsiteCredentialsDetails($web_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==3)
		{
			$rule_count = (int)$this->input->post('web_rule_count');
			$rule_activity_count = (int)$this->input->post('web_rule_activity_count');
			
			
			if($rule_count>0 && $rule_activity_count>0)
			{

				$web_data=array(
					"account_name"=>$website_account_name,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);				
				
				if($website_setting_id!='')
				{				
					$this->Website_setting_model->EditWebsiteCredentials($web_data,$website_setting_id);
					$this->Website_setting_model->DeleteWebsiteCredentialsDetails($website_setting_id);					
				}
				else
				{			
					$website_setting_id=$this->Website_setting_model->AddCredentials($web_data);				
				}			
				
				
				if($website_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('web_state_3_'.$i);
						$assign_to_arr = $this->input->post('website_assign_to_3_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$web_details=array(
								"web_setting_id"=>$website_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Website_setting_model->AddWebsiteCredentialsDetails($web_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('website_assign_to_3_other');
					$web_details=array(
								"web_setting_id"=>$website_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Website_setting_model->AddWebsiteCredentialsDetails($web_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==4)
		{
			$rule_count = (int)$this->input->post('web_rule_count');
			$rule_activity_count = (int)$this->input->post('web_rule_activity_count');
			
			
			if($rule_count>0 && $rule_activity_count>0)
			{

				$web_data=array(
					"account_name"=>$website_account_name,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);				
				
				if($website_setting_id!='')
				{				
					$this->Website_setting_model->EditWebsiteCredentials($web_data,$website_setting_id);
					$this->Website_setting_model->DeleteWebsiteCredentialsDetails($website_setting_id);					
				}
				else
				{			
					$website_setting_id=$this->Website_setting_model->AddCredentials($web_data);				
				}			
				
				
				if($website_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('city_4_'.$i);
						$assign_to_arr = $this->input->post('website_assign_to_4_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$web_details=array(
								"web_setting_id"=>$website_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Website_setting_model->AddWebsiteCredentialsDetails($web_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('website_assign_to_4_other');
					$web_details=array(
								"web_setting_id"=>$website_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Website_setting_model->AddWebsiteCredentialsDetails($web_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==5)
		{
			$rule_count = (int)$this->input->post('web_rule_count');
			$rule_activity_count = (int)$this->input->post('web_rule_activity_count');
			
			
			if($rule_count>0 && $rule_activity_count>0)
			{

				$web_data=array(
					"account_name"=>$website_account_name,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);				
				
				if($website_setting_id!='')
				{				
					$this->Website_setting_model->EditWebsiteCredentials($web_data,$website_setting_id);
					$this->Website_setting_model->DeleteWebsiteCredentialsDetails($website_setting_id);					
				}
				else
				{			
					$website_setting_id=$this->Website_setting_model->AddCredentials($web_data);				
				}			
				
				
				if($website_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = explode(',',$this->input->post('keyword_5_'.$i));
						$assign_to_arr = $this->input->post('website_assign_to_5_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$web_details=array(
								"web_setting_id"=>$website_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Website_setting_model->AddWebsiteCredentialsDetails($web_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('website_assign_to_5_other');
					$web_details=array(
								"web_setting_id"=>$website_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Website_setting_model->AddWebsiteCredentialsDetails($web_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}


		

    	 
        $result["status"] = $status_str;       
        $result["html"]='';
        echo json_encode($result);
        exit(0); 
    }

	function get_select2_autocomplete()
	{
		// if(!isset($_GET['searchTerm'])){ 
		// 	$json = [];
		// }
		// else
		// {
			$search_str = $_GET['searchTerm'];
			$rule_id = $_GET['rule_id'];			
			if($rule_id==2){
				$rows=$this->countries_model->GetOnlyCountriesList($search_str);
			}
			else if($rule_id==3){
				$rows=$this->states_model->GetOnlyIndianStatesList($search_str);
			}
			else if($rule_id==4){
				$rows=$this->cities_model->GetOnlyIndianCitiesList($search_str);
			}			
			$json = [];
			foreach($rows AS $row)
			{
				$json[] = ['id'=>$row['id'], 'text'=>$row['name']];
			}
		// }	
		echo json_encode($json);
		exit;
	}

	function get_user_wise_kpi_target_for_copied()
	{		
		$uid=$this->input->get('from_uid');
		$html='';
		$rows=$this->Setting_model->get_user_wise_kpi_target_for_copied($uid);
	    $data =array (
	       "rows"=>$rows
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
		exit; 
	}

	function edit_employee_type_setting()
    {
		$edit_id = $this->input->get('edit_id'); 
		$name = $this->input->get('name');
		
		if($edit_id!='' && $name!='')
		{
			$post_data=array(
				"name"=>$name			
				);
			$this->Setting_model->UpdatedEmployeeType($post_data,$edit_id);

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

	function delete_employee_type()
	{
		$id=$this->input->get('id');		
		$return=$this->Setting_model->DeleteEmployeeType($id);
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

	function add_employee_type_setting()
    {
		$name = $this->input->get('name'); 
		
		if($name!='')
		{			
			$get_last_sort_order=$this->Setting_model->get_last_sort_order();
			$tmp_sort_order=($get_last_sort_order+1);
			$post_data=array(
						"name"=>$name,
						"sort_order"=>$tmp_sort_order,
						);
			$this->Setting_model->AddEmployeeType($post_data);
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

	public function branch_add_edit_view_rander_ajax()
	{
		$data=NULL;		
		$id=$this->input->post('id');					
		$data['id']=$id;			
		if($id){
			$data['row']=$this->user_model->GetBranchRow($id);
			$tmp_country_id=($data['row']['country_id'])?$data['row']['country_id']:$data['row']['cs_country_id'];
			$tmp_state_id=($data['row']['state_id'])?$data['row']['state_id']:$data['row']['cs_state_id'];
			$data['state_list']=$this->states_model->GetStatesList($tmp_country_id);
			$data['city_list']=$this->cities_model->GetCitiesList($tmp_state_id);
		}
		else{
			$data['row']=array();
			$data['state_list']=array();
			$data['city_list']=array();
		}		
		$data['country_list']=$this->countries_model->GetCountriesList();
		
		$this->load->view('admin/setting/add_edit_branch_modal_ajax',$data);
	}

	function add_edit_branch_ajax()
    {
    	$session_data=$this->session->userdata('admin_session_data');
    	$update_by=$this->session->userdata['admin_session_data']['user_id'];
		
    	
		$id = $this->input->post('branch_id');
    	$name = $this->input->post('branch_name');
    	$contact_person = $this->input->post('branch_contact_person');
    	$email = $this->input->post('branch_email');
    	$mobile = $this->input->post('branch_mobile');
		$address = $this->input->post('branch_address');
		$country_id = $this->input->post('branch_country_id');
		$state_id = $this->input->post('branch_state_id');
		$city_id = $this->input->post('branch_city_id');
    	$pin = $this->input->post('branch_pin');
        $gst = $this->input->post('branch_gst');

		if($id)
		{
			$post_data=array(
				'name'=>$name,
				'contact_person'=>$contact_person,
				'email'=>$email,			
				'mobile'=>$mobile,
				'address'=>$address,
				'country_id'=>$country_id,
				'state_id'=>$state_id,
				'city_id'=>$city_id,
				'pin'=>$pin,
				'gst'=>$gst,
				'updated_at'=>date("Y-m-d H:i:s")
				);
			$return=$this->Setting_model->branch_edit($post_data,$id);
			$msg='Record successfully updated.';
		}
		else
		{
			$post_data=array(
				'name'=>$name,
				'contact_person'=>$contact_person,
				'email'=>$email,			
				'mobile'=>$mobile,
				'address'=>$address,
				'country_id'=>$country_id,
				'state_id'=>$state_id,
				'city_id'=>$city_id,
				'pin'=>$pin,
				'gst'=>$gst,
				'updated_at'=>date("Y-m-d H:i:s"),
				'created_at'=>date("Y-m-d H:i:s")
				);
			$return=$this->Setting_model->branch_add($post_data);
			$msg='Record successfully added.';
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

	public function branch_list_view_rander_ajax()
	{
		$data=NULL;
		$data['branch_list']=$this->Setting_model->GetBranchList();
		$this->load->view('admin/setting/branch_list_modal_ajax',$data);
	}

	public function branch_delete_ajax()
	{
		$data=NULL;
		$id=$this->input->post('id');
		$this->Setting_model->branch_delete($id);
		echo'success';
	}

	function rander_facebook_connect_btn_ajax()
	{
		$id=$this->input->get('id'); 
		$list=array();
		$list['is_fb_connected']=$this->input->get('is_fb_connected'); 
		$list['fb_page_list']=$this->Setting_model->GetFbPageList();
		$list['row']=$this->Setting_model->GetFbFieldSet($id);		
		$list['fb_form_wise_lead_field_set_id']=$id;	
	    $html = '';	    
	    $html = $this->load->view('admin/setting/facebook_connect_btn_view_ajax',$list,TRUE);		
	    $data = array ("html"=>$html);	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
		exit;
	}

	function get_facebook_page_wise_form_list()
    {
		$is_fb_connected=$this->input->get('is_fb_connected'); 
		$fb_page_id=$this->input->get('fb_page_id'); 
		$fb_page_access_token=$this->input->get('fb_page_access_token');
		$html = '';	
		if($fb_page_id!='' && $fb_page_access_token!='' && $is_fb_connected!='')
		{	
			$list=array();
			if($is_fb_connected=='Y')
			{
				// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v16.0/'.$fb_page_id.'/leadgen_forms?fields=page%2Ctest_leads%7Bform_id%2Cfield_data%7D%2Cleads%7Bfield_data%2Cform_id%2Ccampaign_name%2Cad_id%2Cad_name%2Cadset_id%2Cadset_name%2Ccampaign_id%2Ccustom_disclaimer_responses%2Chome_listing%2Ccreated_time%7D%2Cname%2Cleads_count%2Cexpired_leads_count%2Cpage_id&access_token='.$fb_page_access_token);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$return = curl_exec($ch);
				if (curl_errno($ch)) {
					// $err_tmp= 'Error:' . curl_error($ch);
					// $result["status"] = 'fail';       
					// $result["msg"]=$err_tmp;
					// echo json_encode($result);
					// exit(0);
				}
				curl_close($ch);
				$return_arr=json_decode($return);
				$list['rows']=$return_arr->data;
			}
			else
			{
				$fb_form_wise_lead_field_set_id=$this->input->get('fb_form_wise_lead_field_set_id');
				$list['edit_row']=$this->Setting_model->GetFbFieldSet($fb_form_wise_lead_field_set_id);		
				$list['rows']=$this->Setting_model->GetFbPageWiseFormList($fb_page_id);
			}				
			$list['is_fb_connected']=$is_fb_connected;
			$list['fb_page_id']=$fb_page_id;
			$list['fb_page_access_token']=$fb_page_access_token;    
			$html = $this->load->view('admin/setting/rander_facebook_page_wise_form_view_ajax',$list,TRUE);
			
			$status_str='success';
		}	
		else
		{
			$status_str='fail'; 
		}    	 
        $result["status"] = $status_str;       
        $result["html"]=$html;
        echo json_encode($result);
        exit(0);
    }

	function get_facebook_form_wise_lead()
    {
		$is_fb_connected=$this->input->get('is_fb_connected'); 
		$fb_form_id=$this->input->get('fb_form_id'); 
		$fb_page_id=$this->input->get('fb_page_id');
		$fb_page_access_token=$this->input->get('fb_page_access_token');
		$html = '';	
		if($fb_form_id!='' && $fb_page_id!='' && $fb_page_access_token!='' && $is_fb_connected!='')
		{	
			$list=array();
			if($is_fb_connected=='Y'){
				// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v16.0/'.$fb_form_id.'/leads?access_token='.$fb_page_access_token);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

				$return = curl_exec($ch);
				if (curl_errno($ch)) {
					echo 'Error:' . curl_error($ch);
				}
				curl_close($ch);
				$return_arr=json_decode($return);
				$list['rows']=$return_arr->data;
			}
			else{
				$list['rows']=$this->Setting_model->GetFbFormWiseFieldList($fb_form_id);
			}						
			
			$list['is_fb_connected']=$is_fb_connected;
			$list['field_keyword']=$this->Setting_model->GetFbFieldKeywordList();			
			$list['fb_form_id']=$fb_form_id;
			$list['fb_page_id']=$fb_page_id;
			$list['fb_page_access_token']=$fb_page_access_token;    
	    	$html = $this->load->view('admin/setting/rander_facebook_form_wise_lead_view_ajax',$list,TRUE);
			$html2 = $this->load->view('admin/setting/rander_facebook_form_wise_lead_field_with_keyword_view_ajax',$list,TRUE);
			// die("ok");
			$status_str='success';
		}	
		else
		{
			$status_str='fail'; 
		}    	 
        $result["status"] = $status_str;       
        $result["html"]=$html;
		$result["html2"]=$html2;
        echo json_encode($result);
        exit(0);
    }

	function add_edit_fb_lead_field()
    {
		// print_r($_POST); die(); 
		$is_fb_connected=$this->input->post('is_fb_connected'); 
		$fb_user_access_token=$this->input->post('fb_user_access_token'); 
		$fb_user_id=$this->input->post('fb_user_id');
		$page_list_all=$this->input->post('page_list_all');
		$form_list_all=$this->input->post('form_list_all');
		$fb_field_arr=$this->input->post('fb_field');
		$fb_system_field_keyword_arr=$this->input->post('fb_system_field_keyword');
		$fb_page_id=$this->input->post('fb_page_id');
		$fb_form_id=$this->input->post('fb_form_id');
		$fb_page_access_token=$this->input->post('fb_page_access_token');
    	if($is_fb_connected=='Y'){				
			if($page_list_all!='' && $form_list_all!='' && $fb_page_id!='' && $fb_form_id!='' && $fb_page_access_token!='' && count($fb_field_arr)>0 && count($fb_system_field_keyword_arr)>0)
			{
				$row=$this->Setting_model->GetFbFieldSet($id);

				$GetFbFormWiseLeadFieldRow=$this->Setting_model->GetFbFormWiseLeadFieldRow($fb_form_id);
				// $this->Setting_model->truncate_form_table();
				// $this->Setting_model->truncate_lead_field_set_table();
				// $this->Setting_model->delete_page_wise_form($fb_page_id);
				if(count($GetFbFormWiseLeadFieldRow)){
					$this->Setting_model->delete_form_wise_lead_field_set($GetFbFormWiseLeadFieldRow['id']);
				}
				// ADD ALL PAGES
				$page_list_all_arr=explode("^",$page_list_all);
				if(count($page_list_all_arr)){
					foreach($page_list_all_arr AS $page_str){
						$page_arr=explode("~~",$page_str);
						$page_id=$page_arr[0];
						$page_name=$page_arr[1];
						$page_access_token=$page_arr[2];
						$is_page_exist=$this->Setting_model->is_page_exist($page_id);
						if($is_page_exist=='Y'){
							$data_post=array(
								'fb_name'=>$page_name,
								'fb_token'=>$page_access_token,
							);
							$this->Setting_model->UpdatePage($data_post,$page_id);
						}
						else{
							$data_post=array(
								'fb_id'=>$page_id,
								'fb_name'=>$page_name,
								'fb_token'=>$page_access_token,
							);
							$this->Setting_model->AddPage($data_post);
						}						
					}
				}

				// ADD PAGE WISE FOEMS
				$form_list_all_arr=explode("^",$form_list_all);
				if(count($form_list_all_arr)){
					foreach($form_list_all_arr AS $form_str){
						$form_arr=explode("~~",$form_str);
						$page_id=$form_arr[0];
						$form_id=$form_arr[1];
						$form_name=$form_arr[2];
						$is_form_exist=$this->Setting_model->is_form_exist($page_id,$form_id);
						if($is_form_exist=='Y'){
							$data_post=array(
								'form_name'=>$form_name,
							);
							$this->Setting_model->UpdateForm($data_post,$page_id,$form_id);
						}
						else{
							$data_post=array(
								'fb_page_id'=>$page_id,
								'form_id'=>$form_id,
								'form_name'=>$form_name,
							);
							$this->Setting_model->AddForm($data_post);
						}
						
					}
				}

				// --------------------------------------

				$update_post=array('is_default'=>'N');
				$this->Setting_model->EditFormWiseLeadField($update_post);

				$data_post=array(
					'fb_user_access_token'=>$fb_user_access_token,
					'fb_user_id'=>$fb_user_id,
					'fb_page_id'=>$fb_page_id,
					'fb_page_access_token'=>$fb_page_access_token,
					'fb_form_id'=>$fb_form_id,
					'is_default'=>'Y',
					'created_at'=>date("Y-m-d H:i:s"),
					'updated_at'=>date("Y-m-d H:i:s")
				);
				$last_id=$this->Setting_model->AddFormWiseLeadField($data_post);
				if($last_id){
					if(count($fb_field_arr)){
						$i=0;
						foreach($fb_field_arr AS $fb_field){
							$data_post=array(
								'form_wise_lead_field_set_id'=>$last_id,
								'fb_field_name'=>$fb_field,
								'system_field_name_keyword'=>$fb_system_field_keyword_arr[$i]
							);
							$this->Setting_model->AddFormWiseLeadFieldDetail($data_post);
							$i++;
						}
					}
					// =========================================================
					// API Call for Long-Lived Access Tokens	
							
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/'.FB_GRAPH_API_VERSION.'/oauth/access_token?grant_type=fb_exchange_token&client_id='.FB_APP_ID.'&client_secret='.FB_APP_SECRET.'&fb_exchange_token='.$fb_user_access_token);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$access_token_result = curl_exec($ch);
					if (curl_errno($ch)) {
						$fb_api_error='Error:' . curl_error($ch);						
						$result["status"] = 'fail';       
						$result["msg"]=$fb_api_error;
						echo json_encode($result);
						exit(0);
					}
					curl_close($ch);
					$access_token_return_arr=json_decode($access_token_result);	
					if(!isset($access_token_return_arr->error->code)){
						$fb_long_lived_user_access_token=$access_token_return_arr->access_token;
						if($fb_long_lived_user_access_token){
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/'.$fb_page_id.'?fields=access_token&access_token='.$fb_long_lived_user_access_token);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							$page_access_token_result = curl_exec($ch);
							if (curl_errno($ch)) {
								echo 'Error:' . curl_error($ch);
							}
							curl_close($ch);
							$page_access_token_result_arr=json_decode($page_access_token_result);	
							if(!isset($page_access_token_result_arr->error->code)){
								$update_data_post=array(
									'fb_long_lived_user_access_token'=>$fb_long_lived_user_access_token,
									'fb_long_lived_access_token'=>$page_access_token_result_arr->access_token,
									'updated_at'=>date("Y-m-d H:i:s")
								);
								$this->Setting_model->EditFormWiseLeadField($update_data_post,$last_id);
							}
							else{													
								$result["status"] = 'fail';       
								$result["msg"]=$page_access_token_result_arr->error->message;
								echo json_encode($result);
								exit(0);
							}							
						}
					}	
					else{
						$fb_api_error=$access_token_return_arr->error->message;						
						$result["status"] = 'fail';       
						$result["msg"]=$fb_api_error;
						echo json_encode($result);
						exit(0);
					}			
					// API Call for Long-Lived Access Tokens
					// =========================================================
				}
				// ------------------------------------------
			}
			else
			{
				
				$result["status"] = 'fail';       
				$result["msg"]='parameter missing!';
				echo json_encode($result);
        		exit(0); 
			}
		}
		else{
			
			$fb_form_wise_lead_field_set_info=$this->Setting_model->GetFbFormWiseLeadFieldRow($fb_form_id);
			$id=$fb_form_wise_lead_field_set_info['id'];			
			if(count($fb_field_arr)>0 && count($fb_system_field_keyword_arr)>0){
				$this->Setting_model->delete_lead_field_set_details_table($id);
				$i=0;
				foreach($fb_field_arr AS $fb_field){
					$data_post=array(
						'form_wise_lead_field_set_id'=>$id,
						'fb_field_name'=>$fb_field,
						'system_field_name_keyword'=>$fb_system_field_keyword_arr[$i]
					);
					$this->Setting_model->AddFormWiseLeadFieldDetail($data_post);
					$i++;
				}
			}
		}
		$status_str='success';
        $result["status"] = $status_str;       
        $result["html"]='';
        echo json_encode($result);
        exit(0); 
    }

	function get_facebook_form_list()
    {
		$is_fb_connected=$this->input->get('is_fb_connected'); 
		$list['is_fb_connected']=$is_fb_connected;
		$list['rows']=$this->Setting_model->GetFbFormFieldSetList();
		$html = $this->load->view('admin/setting/rander_facebook_form_list_view_ajax',$list,TRUE);	 
        $result["status"] = 'success';       
        $result["html"]=$html;
        echo json_encode($result);
        exit(0);
    }

	function change_default_fb_form_api()
    {        
        $id = $this->input->post('id');
        $curr_status = $this->input->post('curr_status');    
		
		$update=array(
			'is_default'=>'N'
			);
		$this->Setting_model->EditFormWiseLeadField($update);
        
        if($curr_status=='N')
        {
            $s='Y';
        }
        else
        {
            $s='N';
        }        

		$update=array(
			'is_default'=>$s,
			'updated_at'=>date("Y-m-d H:i:s")
			);
		$this->Setting_model->EditFormWiseLeadField($update,$id);
        $result["status"] = 'success';
        echo json_encode($result);
        exit(0);        
    }
	
	function delete_fb_form_api()
    {        
        $id = $this->input->post('id');
		// $row=$this->Setting_model->GetFbFieldSet($id);
		$this->Setting_model->delete_form_wise_lead_field_set($id);
        $result["status"] = 'success';
        echo json_encode($result);
        exit(0);        
    }

	function rander_fb_lead_assignment_list_ajax()
	{	
		$list['row']=$this->Fb_setting_model->GetCredentials();		
	    $table = '';	    
	    $table = $this->load->view('admin/setting/fb_lead_assignment_list_view_ajax',$list,TRUE);		
	    $data =array (
	       "table"=>$table,
	       "row_count"=>count($list['row'])
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}
	function get_fb_credentials()
	{

		$id=$this->input->get('id');
		// $row=$this->Justdial_setting_model->GetCredentials($id);
		$row=$this->Fb_setting_model->GetWebsiteCredentialsDetails($id);
		$return['id'] = $row['id'];
		$return['account_name'] = $row['account_name'];
		$return['assign_to'] = unserialize($row['assign_to']);
		$return['assign_rule'] = $row['assign_rule'];
		echo json_encode($return);
		exit(0);
	}
	function rander_fb_rule_wise_view()
	{
		$rule_id=$this->input->get('rule_id');
		$fb_s_id=$this->input->get('fb_s_id');
		
		$data=array();

		if($fb_s_id>0)
		{
			$data['row']=$this->Fb_setting_model->GetWebsiteCredentialsDetails($fb_s_id);
			$data['rules']=$this->Fb_setting_model->get_rules($fb_s_id);
		}
		else
		{
			$data['row']=array();
			$data['rules']=array();
		}
		
		$data['rule_id']=$rule_id;
		$data['fb_s_id']=$fb_s_id;
		$data['user_list']=$this->user_model->GetUserListAll('');
		$data['state_list']=$this->states_model->GetStatesList('101');
	    $html = $this->load->view('admin/setting/fb_rule_wise_view_ajax',$data,TRUE);
		
	    $return =array (
	       "html"=>$html,
	       "msg"=>""
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($return); 
	}

	function rander_fb_rule_wise_view_outer_div_ajax()
	{
		$fb_s_id=$this->input->get('fb_s_id');
		$cnt=$this->input->get('cnt');
		$rule_id=$this->input->get('rule_id');
		$mode=$this->input->get('mode');
		$data=array();
		if($web_s_id>0)
		{
			$data['rules']=$this->Fb_setting_model->get_rules($fb_s_id);
		}
		else
		{
			$data['rules']=array();
		}
		$data['cnt']=$cnt;
		$data['rule_id']=$rule_id;
		$data['fb_s_id']=$fb_s_id;
		$data['user_list']=$this->user_model->GetUserListAll('');
		if($mode=='edit')
		{
			$index=($cnt-1);
			$existing_find_to_arr=unserialize($data['rules'][$index]['find_to']);
			$existing_find_to_str='';
			if(count($existing_find_to_arr))
			{
				$existing_find_to_str=implode(",",$existing_find_to_arr);
			}			
			if($rule_id=='2'){
				$data['country_list']=$this->countries_model->GetCountriesList($existing_find_to_str);	
			}
			else if($rule_id=='3'){
				$data['state_list']=$this->states_model->GetOnlyIndianStatesListByID($existing_find_to_str);	
			}
			else if($rule_id=='4'){
				$data['city_list']=$this->cities_model->GetAllIndianCitiesList($existing_find_to_str);
			}			
		}	
		else
		{
			$data['country_list']=array();		
			$data['state_list']=array();		
			$data['city_list']=array();
		}
		// $data['state_list']=$this->states_model->GetStatesList('101');
		$data['mode']=$mode;
	    $html = $this->load->view('admin/setting/rander_fb_rule_wise_view_outer_div_ajax',$data,TRUE);
		
	    $return =array (
	       "html"=>$html,
	       "msg"=>""
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($return); 
	}
	function add_edit_facebook_credentials()
    {
		$fb_setting_id = $this->input->post('fb_setting_id'); 
		$facebook_account_name = 'FB Assignment Rule';		
		$facebook_assign_to = $this->input->post('facebook_assign_to');

		$assign_rule = $this->input->post('fb_assign_rule');

		if($assign_rule==1)
		{
			if(count($facebook_assign_to)>0)
			{

				$web_data=array(
					"account_name"=>$facebook_account_name,
					"assign_rule"=>$assign_rule,
					"assign_to"=>serialize($facebook_assign_to),
					"assign_start"=>0					
					);
				
				if($fb_setting_id!='')
				{			
					// --------------------------------- 
					$existing_assign_rule=get_value('assign_rule','fb_lead_assignment_rule_setting','id='.$fb_setting_id);	
					if($existing_assign_rule!=1)
					{
						$this->Fb_setting_model->DeleteWebsiteCredentialsDetails($fb_setting_id);
					}
					// ---------------------------------	
					$this->Fb_setting_model->EditWebsiteCredentials($web_data,$fb_setting_id);
				}
				else
				{				
					$this->Fb_setting_model->AddCredentials($web_data);
				}								
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==2) // country
		{
			$rule_count = (int)$this->input->post('web_rule_count');
			$rule_activity_count = (int)$this->input->post('web_rule_activity_count');
			
			
			if($rule_count>0 && $rule_activity_count>0)
			{

				$web_data=array(
					"account_name"=>$website_account_name,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);				
				
				if($website_setting_id!='')
				{				
					$this->Website_setting_model->EditWebsiteCredentials($web_data,$website_setting_id);
					$this->Website_setting_model->DeleteWebsiteCredentialsDetails($website_setting_id);					
				}
				else
				{			
					$website_setting_id=$this->Website_setting_model->AddCredentials($web_data);				
				}			
				
				
				if($website_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('country_2_'.$i);
						$assign_to_arr = $this->input->post('website_assign_to_2_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$web_details=array(
								"web_setting_id"=>$website_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Website_setting_model->AddWebsiteCredentialsDetails($web_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('website_assign_to_2_other');
					$web_details=array(
								"web_setting_id"=>$website_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Website_setting_model->AddWebsiteCredentialsDetails($web_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==3)
		{
			$rule_count = (int)$this->input->post('web_rule_count');
			$rule_activity_count = (int)$this->input->post('web_rule_activity_count');
			
			
			if($rule_count>0 && $rule_activity_count>0)
			{

				$web_data=array(
					"account_name"=>$website_account_name,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);				
				
				if($website_setting_id!='')
				{				
					$this->Website_setting_model->EditWebsiteCredentials($web_data,$website_setting_id);
					$this->Website_setting_model->DeleteWebsiteCredentialsDetails($website_setting_id);					
				}
				else
				{			
					$website_setting_id=$this->Website_setting_model->AddCredentials($web_data);				
				}			
				
				
				if($website_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('web_state_3_'.$i);
						$assign_to_arr = $this->input->post('website_assign_to_3_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$web_details=array(
								"web_setting_id"=>$website_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Website_setting_model->AddWebsiteCredentialsDetails($web_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('website_assign_to_3_other');
					$web_details=array(
								"web_setting_id"=>$website_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Website_setting_model->AddWebsiteCredentialsDetails($web_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==4)
		{
			$rule_count = (int)$this->input->post('web_rule_count');
			$rule_activity_count = (int)$this->input->post('web_rule_activity_count');
			
			
			if($rule_count>0 && $rule_activity_count>0)
			{

				$web_data=array(
					"account_name"=>$website_account_name,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);				
				
				if($website_setting_id!='')
				{				
					$this->Website_setting_model->EditWebsiteCredentials($web_data,$website_setting_id);
					$this->Website_setting_model->DeleteWebsiteCredentialsDetails($website_setting_id);					
				}
				else
				{			
					$website_setting_id=$this->Website_setting_model->AddCredentials($web_data);				
				}			
				
				
				if($website_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('city_4_'.$i);
						$assign_to_arr = $this->input->post('website_assign_to_4_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$web_details=array(
								"web_setting_id"=>$website_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Website_setting_model->AddWebsiteCredentialsDetails($web_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('website_assign_to_4_other');
					$web_details=array(
								"web_setting_id"=>$website_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Website_setting_model->AddWebsiteCredentialsDetails($web_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==5)
		{
			$rule_count = (int)$this->input->post('web_rule_count');
			$rule_activity_count = (int)$this->input->post('web_rule_activity_count');
			
			
			if($rule_count>0 && $rule_activity_count>0)
			{

				$web_data=array(
					"account_name"=>$website_account_name,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);				
				
				if($website_setting_id!='')
				{				
					$this->Website_setting_model->EditWebsiteCredentials($web_data,$website_setting_id);
					$this->Website_setting_model->DeleteWebsiteCredentialsDetails($website_setting_id);					
				}
				else
				{			
					$website_setting_id=$this->Website_setting_model->AddCredentials($web_data);				
				}			
				
				
				if($website_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = explode(',',$this->input->post('keyword_5_'.$i));
						$assign_to_arr = $this->input->post('website_assign_to_5_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$web_details=array(
								"web_setting_id"=>$website_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Website_setting_model->AddWebsiteCredentialsDetails($web_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('website_assign_to_5_other');
					$web_details=array(
								"web_setting_id"=>$website_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Website_setting_model->AddWebsiteCredentialsDetails($web_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}


		

    	 
        $result["status"] = $status_str;       
        $result["html"]='';
        echo json_encode($result);
        exit(0); 
    }

	function delete_fb()
	{
		$del_id=$this->input->get('id');
		$this->Fb_setting_model->DeleteCredentials($del_id);
		$assign_rule=get_value("assign_rule","fb_lead_assignment_rule_setting","id=".$del_id);
		if($assign_rule!=1)
		{
			$this->Fb_setting_model->DeleteWebsiteCredentialsDetails($del_id);
		}

		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}

	// ======================================
	// Exporter India functionality

	function rander_ei_credentials_list_ajax()
	{	
		$list['rows']=$this->Exporterindia_setting_model->GetCredentials();		
	    $table = '';	    
	    $table = $this->load->view('admin/setting/exporterindia_credentials_list_view_ajax',$list,TRUE);		
	    $data =array (
	       "table"=>$table
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data); 
	}

	function rander_ei_rule_wise_view()
	{
		$rule_id=$this->input->get('rule_id');
		$ti_s_id=$this->input->get('ti_s_id');
		
		$data=array();

		if($ti_s_id>0)
		{
			$data['row']=$this->Exporterindia_setting_model->GetTradeindiaCredentialsDetails($ti_s_id);
			$data['rules']=$this->Exporterindia_setting_model->get_rules($ti_s_id);
		}
		else
		{
			$data['row']=array();
			$data['rules']=array();
		}
		
		$data['rule_id']=$rule_id;
		$data['ti_s_id']=$ti_s_id;
		$data['user_list']=$this->user_model->GetUserListAll('');
		// $data['state_list']=$this->states_model->GetStatesList('101');
	    $html = $this->load->view('admin/setting/ei_rule_wise_view_ajax',$data,TRUE);
		// echo $html;die('ok');
		
	    $return =array (
	       "html"=>$html,
	       "msg"=>""
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($return); 
	}
	function rander_ei_rule_wise_view_outer_div_ajax()
	{
		$ti_s_id=$this->input->get('ti_s_id');
		$cnt=$this->input->get('cnt');
		$rule_id=$this->input->get('rule_id');
		$mode=$this->input->get('mode');
		$data=array();
		if($ti_s_id>0)
		{
			$data['rules']=$this->Exporterindia_setting_model->get_rules($ti_s_id);
		}
		else
		{
			$data['rules']=array();
		}
		$data['cnt']=$cnt;
		$data['rule_id']=$rule_id;
		$data['ti_s_id']=$ti_s_id;
		$data['user_list']=$this->user_model->GetUserListAll('');
		if($mode=='edit')
		{
			$index=($cnt-1);
			$existing_find_to_arr=unserialize($data['rules'][$index]['find_to']);
			$existing_find_to_str='';
			if(count($existing_find_to_arr))
			{
				$existing_find_to_str=implode(",",$existing_find_to_arr);
			}			
			if($rule_id=='2'){
				$data['country_list']=$this->countries_model->GetCountriesList($existing_find_to_str);	
			}
			else if($rule_id=='3'){
				$data['state_list']=$this->states_model->GetOnlyIndianStatesListByID($existing_find_to_str);	
			}
			else if($rule_id=='4'){
				$data['city_list']=$this->cities_model->GetAllIndianCitiesList($existing_find_to_str);
			}			
		}	
		else
		{
			$data['country_list']=array();		
			$data['state_list']=array();		
			$data['city_list']=array();
		}
		// $data['state_list']=$this->states_model->GetStatesList('101');
		$data['mode']=$mode;
	    $html = $this->load->view('admin/setting/rander_ei_rule_wise_view_outer_div_ajax',$data,TRUE);
		
	    $return =array (
	       "html"=>$html,
	       "msg"=>""
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($return); 
	}
	function add_edit_exporterindia_credentials()
    {
		$tradeindia_setting_id = $this->input->post('exporterindia_setting_id'); 
		$tradeindia_account_name = trim($this->input->post('exporterindia_account_name'));
		$tradeindia_userid = trim($this->input->post('exporterindia_userid')); 
    	$tradeindia_profile_id = trim($this->input->post('exporterindia_profile_id'));
		$tradeindia_key = trim($this->input->post('exporterindia_key'));
		$tradeindia_assign_to = $this->input->post('exporterindia_assign_to');
		$assign_rule = $this->input->post('ei_assign_rule');
		
		// echo $tradeindia_setting_id.'/'.$tradeindia_account_name.'/'.$tradeindia_userid.'/'.$tradeindia_profile_id.'/'.$tradeindia_key.'/'.$tradeindia_assign_to.'/'.$assign_rule;die();
		if($assign_rule==1)
		{
			if($tradeindia_account_name!='' && $tradeindia_userid!='' && $tradeindia_profile_id!='' && $tradeindia_key!='' && count($tradeindia_assign_to)>0)
			{

				$ti_data=array(
					"account_name"=>$tradeindia_account_name,
					"userid"=>$tradeindia_userid,
					"profileid"=>$tradeindia_profile_id,
					"ti_key"=>$tradeindia_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>serialize($tradeindia_assign_to),
					"assign_start"=>0					
					);
	
				if($tradeindia_setting_id!='')
				{				
					// --------------------------------- 
					$existing_assign_rule=get_value('assign_rule','exporterindia_setting','id='.$tradeindia_setting_id);	
					if($existing_assign_rule!=1)
					{
						$this->Exporterindia_setting_model->DeleteTradeindiaCredentialsDetails($tradeindia_setting_id);
					}
					// ---------------------------------
					$this->Exporterindia_setting_model->EditCredentials($ti_data,$tradeindia_setting_id);
				}
				else
				{				
					$this->Exporterindia_setting_model->AddCredentials($ti_data);
				}				
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==2)
		{
			$rule_count = (int)$this->input->post('ei_rule_count');
			$rule_activity_count = (int)$this->input->post('ei_rule_activity_count');

			
			if($tradeindia_account_name!='' && $tradeindia_userid!='' && $tradeindia_profile_id!='' && $tradeindia_key!='' && $rule_count>0 && $rule_activity_count>0)
			{

				$ti_data=array(
					"account_name"=>$tradeindia_account_name,
					"userid"=>$tradeindia_userid,
					"profileid"=>$tradeindia_profile_id,
					"ti_key"=>$tradeindia_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);
				
				if($tradeindia_setting_id!='')
				{				
					$this->Exporterindia_setting_model->EditCredentials($ti_data,$tradeindia_setting_id);
					$this->Exporterindia_setting_model->DeleteTradeindiaCredentialsDetails($tradeindia_setting_id);					
				}
				else
				{				
					$tradeindia_setting_id=$this->Exporterindia_setting_model->AddCredentials($ti_data);					
				}			
				

				if($tradeindia_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('country_2_'.$i);
						$assign_to_arr = $this->input->post('exporterindia_assign_to_2_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$ti_details=array(
								"ti_setting_id"=>$tradeindia_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Exporterindia_setting_model->AddTradeindiaCredentialsDetails($ti_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('exporterindia_assign_to_2_other');
					$ti_details=array(
								"ti_setting_id"=>$tradeindia_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
								$this->Exporterindia_setting_model->AddTradeindiaCredentialsDetails($ti_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==3)
		{
			$rule_count = (int)$this->input->post('ei_rule_count');
			$rule_activity_count = (int)$this->input->post('ei_rule_activity_count');

			
			if($tradeindia_account_name!='' && $tradeindia_userid!='' && $tradeindia_profile_id!='' && $tradeindia_key!='' && $rule_count>0 && $rule_activity_count>0)
			{

				$ti_data=array(
					"account_name"=>$tradeindia_account_name,
					"userid"=>$tradeindia_userid,
					"profileid"=>$tradeindia_profile_id,
					"ti_key"=>$tradeindia_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);
				
				if($tradeindia_setting_id!='')
				{				
					$this->Exporterindia_setting_model->EditCredentials($ti_data,$tradeindia_setting_id);
					$this->Exporterindia_setting_model->DeleteTradeindiaCredentialsDetails($tradeindia_setting_id);					
				}
				else
				{				
					$tradeindia_setting_id=$this->Exporterindia_setting_model->AddCredentials($ti_data);					
				}			
				

				if($tradeindia_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('ei_state_3_'.$i);
						$assign_to_arr = $this->input->post('exporterindia_assign_to_3_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$ti_details=array(
								"ti_setting_id"=>$tradeindia_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Exporterindia_setting_model->AddTradeindiaCredentialsDetails($ti_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('exporterindia_assign_to_3_other');
					$ti_details=array(
								"ti_setting_id"=>$tradeindia_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
								$this->Exporterindia_setting_model->AddTradeindiaCredentialsDetails($ti_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==4)
		{
			$rule_count = (int)$this->input->post('ei_rule_count');
			$rule_activity_count = (int)$this->input->post('ei_rule_activity_count');

			
			if($tradeindia_account_name!='' && $tradeindia_userid!='' && $tradeindia_profile_id!='' && $tradeindia_key!='' && $rule_count>0 && $rule_activity_count>0)
			{

				$ti_data=array(
					"account_name"=>$tradeindia_account_name,
					"userid"=>$tradeindia_userid,
					"profileid"=>$tradeindia_profile_id,
					"ti_key"=>$tradeindia_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);
				
				if($tradeindia_setting_id!='')
				{				
					$this->Exporterindia_setting_model->EditCredentials($ti_data,$tradeindia_setting_id);
					$this->Exporterindia_setting_model->DeleteTradeindiaCredentialsDetails($tradeindia_setting_id);					
				}
				else
				{				
					$tradeindia_setting_id=$this->Exporterindia_setting_model->AddCredentials($ti_data);					
				}			
				

				if($tradeindia_setting_id>0)
				{					
					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = $this->input->post('city_4_'.$i);
						$assign_to_arr = $this->input->post('exporterindia_assign_to_4_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$ti_details=array(
								"ti_setting_id"=>$tradeindia_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Exporterindia_setting_model->AddTradeindiaCredentialsDetails($ti_details);
						}
					}


					$state_arr = 'other';
					$assign_to_arr = $this->input->post('exporterindia_assign_to_4_other');
					$ti_details=array(
								"ti_setting_id"=>$tradeindia_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
								$this->Exporterindia_setting_model->AddTradeindiaCredentialsDetails($ti_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		else if($assign_rule==5)
		{
			$rule_count = (int)$this->input->post('ei_rule_count');
			$rule_activity_count = (int)$this->input->post('ei_rule_activity_count');

			
			if($tradeindia_account_name!='' && $tradeindia_userid!='' && $tradeindia_profile_id!='' && $tradeindia_key!='' && $rule_count>0 && $rule_activity_count>0)
			{

				$ti_data=array(
					"account_name"=>$tradeindia_account_name,
					"userid"=>$tradeindia_userid,
					"profileid"=>$tradeindia_profile_id,
					"ti_key"=>$tradeindia_key,
					"assign_rule"=>$assign_rule,
					"assign_to"=>'',
					"assign_start"=>0					
					);
				
				if($tradeindia_setting_id!='')
				{				
					$this->Exporterindia_setting_model->EditCredentials($ti_data,$tradeindia_setting_id);
					$this->Exporterindia_setting_model->DeleteTradeindiaCredentialsDetails($tradeindia_setting_id);					
				}
				else
				{				
					$tradeindia_setting_id=$this->Exporterindia_setting_model->AddCredentials($ti_data);					
				}			
				

				if($tradeindia_setting_id>0)
				{				
					

					for($i=1;$i<=$rule_activity_count;$i++)
					{
						$state_arr=array();
						$assign_to_arr=array();
						$state_arr = explode(',',$this->input->post('keyword_5_'.$i));
						$assign_to_arr = $this->input->post('exporterindia_assign_to_5_'.$i);

						if(count($state_arr)>0 && count($assign_to_arr)>0)
						{
							$ti_details=array(
								"ti_setting_id"=>$tradeindia_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
							$this->Exporterindia_setting_model->AddTradeindiaCredentialsDetails($ti_details);
						}
					}

					$state_arr = 'other';
					$assign_to_arr = $this->input->post('exporterindia_assign_to_5_other');
					$ti_details=array(
								"ti_setting_id"=>$tradeindia_setting_id,
								"assign_rule_id"=>$assign_rule,
								"find_to"=>serialize($state_arr),
								"assign_to"=>serialize($assign_to_arr),
								"assign_start"=>0
								);
					$this->Exporterindia_setting_model->AddTradeindiaCredentialsDetails($ti_details);
				}
				$status_str='success'; 
			}	
			else
			{
				$status_str='fail'; 
			}
		}
		/*
		if($tradeindia_account_name!='' && $tradeindia_userid!='' && $tradeindia_profile_id!='' && $tradeindia_key!='' && count($tradeindia_assign_to)>0)
		{
			$ti_data=array(
				"account_name"=>$tradeindia_account_name,
				"userid"=>$tradeindia_userid,
				"profileid"=>$tradeindia_profile_id,
				"ti_key"=>$tradeindia_key,
				"assign_to"=>serialize($tradeindia_assign_to),
				"assign_start"=>0					
				);

			if($tradeindia_setting_id!='')
			{				
				$this->Tradeindia_setting_model->EditCredentials($ti_data,$tradeindia_setting_id);
			}
			else
			{				
				$this->Tradeindia_setting_model->AddCredentials($ti_data);
			}
			
			$status_str='success'; 
		}	
		else
		{
			$status_str='fail'; 
		}
		*/
    	 
        $result["status"] = $status_str;       
        $result["html"]='';
        echo json_encode($result);
        exit(0); 
    }
	function get_ei_credentials()
	{
		$id=$this->input->get('id');
		$row=$this->Exporterindia_setting_model->GetTradeindiaCredentialsDetails($id);
		$return['id'] = $row['id'];
		$return['account_name'] = $row['account_name'];
		$return['userid'] = $row['userid'];
		$return['profileid'] = $row['profileid'];
		$return['ti_key'] = $row['ti_key'];
		$return['assign_to'] = unserialize($row['assign_to']);
		$return['assign_rule'] = $row['assign_rule'];
		echo json_encode($return);
		exit(0);
	}
	function delete_ei()
	{
		$del_id=$this->input->get('id');
		$this->Exporterindia_setting_model->DeleteCredentials($del_id);
		$assign_rule=get_value("assign_rule","exporterindia_setting","id=".$del_id);
		if($assign_rule!=1)
		{
			$this->Exporterindia_setting_model->DeleteTradeindiaCredentialsDetails($del_id);
		}
		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}
	// Exporter India functionality
	// ======================================
	
}