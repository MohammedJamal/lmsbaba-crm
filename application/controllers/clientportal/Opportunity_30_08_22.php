<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Opportunity extends CI_Controller {
	
	 private $api_access_token = '';
	 function __construct(){
		parent :: __construct();
		is_admin_logged_in();
		init_admin_element(); 
		$this->load->model(array("Opportunity_model","Product_model","Opportunity_product_model","lead_model","source_model","user_model","menu_model","countries_model","states_model","cities_model","quotation_model","history_model","Setting_model","Customer_model","Email_forwarding_setting_model","pre_define_comment_model"));

		if(isset($this->session->userdata['admin_session_data']['user_id']))
		{
			$this->user_menu_list=$this->menu_model->GetMenuListByUser($this->session->userdata['admin_session_data']['user_id']) ;
     	}
     			

	}
	
	public function add()
	{		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{	
			$lead_id=$this->input->post('lead_id');
			$opportunity_title=$this->input->post('opportunity_title');
			$deal_value=$this->input->post('deal_value');
			$currency_type=$this->input->post('currency_type_new');				
			$user_id=$this->session->userdata['admin_session_data']['user_id'];
			$status=1; // Pending
			$tmp_prod_list=$this->Product_model->GetTempProductList($user_id);

			if(count($tmp_prod_list))
			{
				$data_opportunity=array(
									'lead_id'=>$lead_id,
									'opportunity_title'=>$opportunity_title,
									// 'deal_value'=>str_replace(',',"",$deal_value),
									'currency_type'=>$currency_type,
									'status'=>$status,
									'create_date'=>date("Y-m-d H:i:s"),
									'modify_date'=>date("Y-m-d H:i:s")
									);	
				$create_opportunity=$this->Opportunity_model->CreateLeadOportunity($data_opportunity);	

				$deal_value_tmp=0; 
				foreach($tmp_prod_list as $tmp_prod_data)
				{
					// ------------------------------------------------
					// calculated value
					$item_gst_per= $tmp_prod_data->gst;
					$item_sgst_per= ($item_gst_per/2);
					$item_cgst_per= ($item_gst_per/2);  
					$item_discount_per=$tmp_prod_data->discount; 
					$item_price= $tmp_prod_data->price;
					$item_qty=$tmp_prod_data->unit;
					
					$item_total_amount=($item_price*$item_qty);
					$row_discount_amount=$item_total_amount*($item_discount_per/100);
					$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

					$row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
					$deal_value_tmp=$deal_value_tmp+$row_final_price;		
					// calculated value
					// ------------------------------------------------

					$data_prd=array(
									'opportunity_id'=>$create_opportunity,
									'product_id'=>$tmp_prod_data->product_id,
									'unit'=>$tmp_prod_data->unit,
									'unit_type'=>$tmp_prod_data->unit_type,
									'price'=>$tmp_prod_data->price,
									'currency_type'=>$currency_type,
									'discount'=>$tmp_prod_data->discount,
									'gst'=>$tmp_prod_data->gst,
									'create_date'=>$tmp_prod_data->create_date
									);					
					$create_prod=$this->Opportunity_product_model->CreateOportunityProduct($data_prd);	
					$delete_temp_prod=$this->Product_model->DeleteTempProduct($tmp_prod_data->product_id,$user_id);
				}
				
				// -----------------------------------------------------
				// Update deal value
				$data_opportunity_update=array(
									'deal_value'=>$deal_value_tmp,
									'modify_date'=>date("Y-m-d H:i:s")
									);	
				$this->Opportunity_model->UpdateLeadOportunity($data_opportunity_update,$create_opportunity);	


				// =================================================
				// Create History log
				$update_by=$this->session->userdata['admin_session_data']['user_id'];
				$date=date("Y-m-d H:i:s");
				//$ip_addr=$_SERVER['REMOTE_ADDR'];
				$ip_addr = $this->input->ip_address();
				$message="A new quotation has been created as &quot;".$opportunity_title."&quot;";
				$comment_title=QUOTATION_CREATE;
				$historydata=array(
								'title'=>$comment_title,
								'lead_id'=>$lead_id,
								'lead_opportunity_id'=>$create_opportunity,
								'comment'=>addslashes($message),
								'create_date'=>$date,
								'user_id'=>$update_by,
								'ip_address'=>$ip_addr
								);
				//inserted_lead_comment_log($historydata);
				$this->history_model->CreateHistory($historydata);	
				// Create History log	
				// =================================================
				
				$msg='A new quotation ('.$opportunity_title.') has been created.';
				$this->session->set_flashdata('success_msg', $msg);
			}
			else
			{
				$msg='No product selected for the proposal.';
				$this->session->set_flashdata('error_msg', $msg);
			}
			//CheckUserSpace();
			$url=$this->session->userdata['admin_session_data']['lms_url'];
			redirect($url.'/lead/edit/'.$lead_id);
		}
	}	
	
	public function edit()
	{
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$lead_id=$this->input->post('lead_id_update');
			$opportunity_id=$this->input->post('opportunity_id');
			$opportunity_title=$this->input->post('opportunity_title');
			//$source_id=$this->input->post('source_type');
			//$deal_value=$this->input->post('deal_value');
			$currency_type=$this->input->post('currency_type_update');

			// ---------------------------------------------------------
			// deal value calculate
			$get_app_product=$this->Opportunity_product_model->GetOpportunityProducts($opportunity_id);
			$deal_value_tmp1=0;
			if(count($get_app_product))
			{
				foreach($get_app_product AS $product)
				{

					$item_gst_per= $product->gst;
					$item_sgst_per= ($item_gst_per/2);
					$item_cgst_per= ($item_gst_per/2);  
					$item_discount_per=$product->discount; 
					$item_price= $product->price;
					$item_qty=$product->unit;
					
					$item_total_amount=($item_price*$item_qty);
					$row_discount_amount=$item_total_amount*($item_discount_per/100);
					$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

					$row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
					$deal_value_tmp1=$deal_value_tmp1+$row_final_price;		
					
				}
			}

			$get_additional_charges=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
			$deal_value_tmp2=0;
			if(count($get_additional_charges))
			{
				foreach($get_additional_charges AS $charge)
				{
					$item_discount_per2= $charge->discount;
					$item_gst_per2= $charge->gst;
					$item_total_amount2=$charge->price;
					$row_discount_amount2=$item_total_amount2*($item_discount_per2/100);
					$row_gst_amount2=($item_total_amount2-$row_discount_amount2)*($item_gst_per2/100);

					$row_final_price2=($item_total_amount2+$row_gst_amount2-$row_discount_amount2);
					$deal_value_tmp2=$deal_value_tmp2+$row_final_price2;
				}
			}			
			$deal_value=($deal_value_tmp1+$deal_value_tmp2);
			// ----------------------------------------------------------
			$status=1;		
			$data_opportunity=array('lead_id'=>$lead_id,
									'opportunity_title'=>$opportunity_title,
									'deal_value'=>$deal_value,
									'currency_type'=>$currency_type,
									'status'=>$status,
									'modify_date'=>date("Y-m-d H:i:s")
									);	
			/*echo '<pre>';
			print_r($data_opportunity);die;*/
			$update_opportunity=$this->Opportunity_model->UpdateLeadOportunity($data_opportunity,$opportunity_id);
			
			
			//CheckUserSpace();
			// =================================================
			// Create History log
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$date=date("Y-m-d H:i:s");
			$ip_addr = $this->input->ip_address();
			$message="&quot;".$opportunity_title."&quot; quotation has been updated.";
			$comment_title=QUOTATION_UPDATE;
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

			$msg='The quotation ('.$opportunity_title.') successfully updated.';
			$this->session->set_flashdata('success_msg', $msg);

			$url=$this->session->userdata['admin_session_data']['lms_url'];
			redirect($url.'/lead/edit/'.$lead_id);			
		}
	}	
	
	public function details_ajax()
	{
		$data=array();
		$opportunity_id=$this->input->post('opportunity_id');
		$data['lead_id']=$this->input->post('lead_id');
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$data['opportunity_id']=$opportunity_id;
		$data['opportunity_data']=$this->Opportunity_model->GetOpportunityData($opportunity_id);
		$data['opportunity_stage_list']=$this->Opportunity_model->GetOpportunityStageListAll();	
		$data['source_list']=$this->source_model->GetSourceListAll();
		$data['currency_list']=$this->lead_model->GetCurrencyList();
		//$data['tmp_prod_list']=$this->Product_model->GetTempAndOppProductList($user_id,$opportunity_id);
		$data['communication_list']=$this->lead_model->GetCommunicationList();
		$data['tmp_prod_list']=$this->Opportunity_product_model->GetOpportunityProductList($opportunity_id);

		$data['selected_additional_charges']=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		
		$this->load->view('admin/lead/opportunity_details_ajax',$data);
	}
	
	public function generate($opportunity_id)
	{
		$data=array();		
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$admin_session_data_user_data_tmp=$this->user_model->GetAdminData($user_id);
		$admin_session_data_user_data=$admin_session_data_user_data_tmp[0];
		$data['admin_session_data_user_data']=$admin_session_data_user_data;

		$data['opportunity_id']=$opportunity_id;

		$opportunity_data=$this->Opportunity_model->GetOpportunityData($opportunity_id);
		$data['opportunity_data']=$opportunity_data;
		$lead_id=$opportunity_data->lead_id;
		$lead_data=$this->lead_model->GetLeadData($opportunity_data->lead_id);
		$data['lead_data']=$lead_data;
		$customer_data=$this->Customer_model->GetCustomerData($lead_data->customer_id);
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
		// $get_last_quote_no=$this->quotation_model->get_last_quote_no();
		// if($get_last_quote_no)
		// {
		// 	$get_last_quote_no_arr=explode("-", $get_last_quote_no);
		// 	$no_tmp=($get_last_quote_no_arr[1]+1);
		// }
		// else
		// {
		// 	$no_tmp=1;
		// }
		$no_tmp=$opportunity_id;
		$quote_no=$company_name_tmp.'-000'.$no_tmp.'-'.$m_y_tmp;
		// QUOTE NO. LOGIC - END		
		
		$quote_valid_until=date('Y-m-d', strtotime("+30 days"));
		//$letter_to=$customer_data->contact_person.'<br>'.$customer_data->address.'<br>'.$customer_data->city_name.','.$customer_data->state_name.'-'.$customer_data->zip.','.$customer_data->country_name.'<br>Email:'.$customer_data->email.'<br>Mobile:'.$customer_data->mobile;
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
		
		$is_quotation_bank_details1_send=($company_data['quotation_bank_details1'])?'Y':'N';
		$is_quotation_bank_details2_send=($company_data['quotation_bank_details2'])?'Y':'N';
		$is_gst_number_show_in_quotation=($company_data['gst_number'])?'Y':'N';
		
		// ================================================================
		// INSERT TO QUOTE TABLE
		$quotation_post_data=array(	
							'opportunity_id'=>$opportunity_id,
							'customer_id'=>$lead_data->customer_id,
							'quote_no'=>$quote_no,
							'quote_date'=>date("Y-m-d"),
							'quote_valid_until'=>$quote_valid_until,
							'currency_type'=>$opportunity_data->currency_type_code,
							'letter_to'=>$letter_to,
							'letter_subject'=>$letter_subject,
							'letter_body_text'=>$letter_body_text,
							'letter_footer_text'=>$letter_footer_text,
							'letter_terms_and_conditions'=>$letter_terms_and_conditions,
							'letter_thanks_and_regards'=>$letter_thanks_and_regards,
							'is_quotation_bank_details1_send'=>$is_quotation_bank_details1_send,
							'is_quotation_bank_details2_send'=>$is_quotation_bank_details2_send,
							'is_gst_number_show_in_quotation'=>$is_gst_number_show_in_quotation,
							'create_date'=>date("Y-m-d H:i:s"),
							'modify_date'=>date("Y-m-d H:i:s")
							);
		$quotation_id=$this->quotation_model->CreateQuotation($quotation_post_data);
		// INSERT TO QUOTE TABLE
		// ================================================================
		

		// ================================================================
		// INSERT TO QUOTATION WISE PRODUCT TABLE
		//$prod_list=$this->Product_model->GetTempAndOppProductList($user_id,$opportunity_id);
		$prod_list=$this->Opportunity_model->get_opportunity_product($opportunity_id);
		$data['prod_list']=$prod_list;
		
		foreach($prod_list as $prod_data)
		{			
			$quotation_product_data=array(
										'quotation_id'=>$quotation_id,
										'product_id'=>$prod_data->product_id,
										'product_name'=>$prod_data->p_name,
										'product_sku'=>$prod_data->p_code,
										'unit'=>$prod_data->unit,
										'unit_type'=>$prod_data->unit_type_name,
										'price'=>$prod_data->price,
										'discount'=>$prod_data->discount,
										'gst'=>$prod_data->gst
										);
			$this->quotation_model->CreateQuotationProduct($quotation_product_data);
		}

		// INSERT TO QUOTATION WISE PRODUCT TABLE
		// ================================================================


		// ================================================================
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
		// ================================================================

		// ================================================================
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
							'quotation_bank_details1'=>$company_data['quotation_bank_details1'],
							'quotation_bank_details2'=>$company_data['quotation_bank_details2'],
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
		// ================================================================

		// ================================================================
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
		// ================================================================
		

		// =================================================
		// Create History log
		$update_by=$this->session->userdata['admin_session_data']['user_id'];
		$date=date("Y-m-d H:i:s");
		$ip_addr = $this->input->ip_address();
		$message="A new Automated Quotation PDF (".$quote_no.") has been created.";
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

		$msg="A new Quotation PDF (".$quote_no.") has been created.";
		$this->session->set_flashdata('success_msg', $msg);
		//CheckUserSpace();
		redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/opportunity/generate_view/'.$opportunity_id.'/'.$quotation_id);		
	}
	
	// ==========================================================
	// ==========================================================
	function quotation_letter_field_update_ajax()
    {
        $quotation_id=$this->input->post('quotation_id');
        // $updated_field_name=$this->input->post('updated_field_name');
        $updated_content=$this->input->post('updated_content');

        $updated_field_name_arr=explode("#", $this->input->post('updated_field_name'));
        $updated_field_name=$updated_field_name_arr[0];
        $updated_field_id=$updated_field_name_arr[1];

		if($updated_field_name=='quotation_bank_details1' || $updated_field_name=='quotation_bank_details2') //quotation_time_company_setting_log
		{
			$data = array(
				$updated_field_name=>$updated_content
			);
			$this->quotation_model->UpdateQuotationTimeCompanyInfoLogByQuotationId($data,$quotation_id);
		}
		else if($updated_field_name=='product_name')
		{
			$data = array(
				$updated_field_name=>$updated_content
			);
			$update_data=$this->quotation_model->update_product($data,$updated_field_id);
		}
		else if($updated_field_name=='additional_charge_name')
		{
			$data=array(						
						$updated_field_name=>$updated_content
						);
			$update_data=$this->Opportunity_model->update_opportunity_additional_charges($data,$updated_field_id);
		}
		else
		{
			if($updated_field_name=='quote_valid_until')
			{
				$updated_content=date_display_format_to_db_format($updated_content);
			}

			if($updated_field_name=='quote_date')
			{	
				$updated_content=date_display_format_to_db_format($updated_content);
			} 

			if($updated_field_name=='is_product_image_show_in_quotation')
			{	
				if($updated_content=='N')
				{
					$post_data=array('image_for_show'=>NULL);
					$this->quotation_model->UpdateQuotationProductByQid($post_data,$quotation_id);
				}
			}

			if($updated_field_name=='is_product_youtube_url_show_in_quotation')
			{	
				if($updated_content=='N')
				{
					$post_data=array('is_youtube_video_url_show'=>'N');
					$this->quotation_model->UpdateQuotationProductByQid($post_data,$quotation_id);
				}
			}

			if($updated_field_name=='is_product_brochure_attached_in_quotation')
			{	
				if($updated_content=='N')
				{
					$post_data=array('is_brochure_attached'=>'N');
					$this->quotation_model->UpdateQuotationProductByQid($post_data,$quotation_id);
				}
			}   

			if($updated_field_name=='is_hide_gst_in_quotation')
			{	
				if($updated_content=='N')
				{
					$post_data=array('is_show_gst_extra_in_quotation'=>'N');

					$this->quotation_model->UpdateQuotation($post_data,$quotation_id);
				}
			}     

			$data = array(
						$updated_field_name=>$updated_content,
						'modify_date' =>date('Y-m-d H:i:s')
					);
			$this->quotation_model->UpdateQuotation($data,$quotation_id);
		}
        
       

        $msg = "Quation successfully updated!"; // 'duplicate';         
        //$this->session->set_flashdata('success_msg', $msg);

        $data =array (
                        "status"=>'success'
                    );
        $this->output->set_content_type('application/json');
        echo json_encode($data);
    }

    function quotation_product_update_ajax()
    {
        $quotation_pid=$this->input->post('quotation_pid');
        $updated_field_name=$this->input->post('updated_field_name');
        $updated_content=$this->input->post('updated_content');

        if($updated_field_name=='image_for_show')
        {
        	$quotation_pid_str=$this->input->post('quotation_pid');
        	$quotation_pid_arr=explode('_', $quotation_pid_str);
        	$quotation_pid=$quotation_pid_arr[0];
        	$product_images_id=$quotation_pid_arr[1];
        	$qp_row=$this->quotation_model->GetQuotationProductRow($quotation_pid);
        	$existing_img_arr=array();

        	if($qp_row->image_for_show){ 
        		$existing_img_arr=@unserialize($qp_row->image_for_show);
				if ($existing_img_arr !== false){} 
				else {
				    $existing_img_arr=array();
				}       		
        	}
        	
        	if($updated_content=='Y')
        	{
        		array_push($existing_img_arr, $product_images_id);
        	}
        	else
        	{
				if (($key = array_search($product_images_id, $existing_img_arr)) !== FALSE) {
				  unset($existing_img_arr[$key]);
				}
        	}
        	
        	if(count($existing_img_arr)){
        		$updated_content=serialize($existing_img_arr);
        	}
        	else{
        		$updated_content=NULL;
        	}        	
        }

		$data=array(
					$updated_field_name=>$updated_content
				);
		$this->quotation_model->UpdateQuotationProduct($data,$quotation_pid);
        
       

        $msg = "Quation product successfully updated!"; // 'duplicate';         
        //$this->session->set_flashdata('success_msg', $msg);

        $data =array (
                        "status"=>'success'
                    );
        $this->output->set_content_type('application/json');
        echo json_encode($data);
    }

    function is_terms_show_in_letter_ajax()
    {
        $id=$this->input->post('id');
        $updated_field_name=$this->input->post('updated_field_name');
        $updated_content=$this->input->post('updated_content');             

        $data = array(
                    $updated_field_name=>$updated_content
                	);
        $this->quotation_model->UpdateQuotationTermsLog($data,$id);
       

        $msg = "Quation successfully updated!"; // 'duplicate';         
        $this->session->set_flashdata('success_msg', $msg);

        $data =array (
                        "status"=>'success'
                    );
        $this->output->set_content_type('application/json');
        echo json_encode($data);
    }
	// ==========================================================	
	// ==========================================================
	public function generate_upload_view()
	{		

		$data=array();
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$url=base_url().$this->session->userdata['admin_session_data']['lms_url'];
		
		$doc_name=$this->input->post('doc_name');
		$quotation_id=$this->input->post('quotation_id'); 
		$opportunity_id=$this->input->post('opportunity_id');
		 
				
		$config = [
        'upload_path'   => './uploads/brochure/',
        'allowed_types' => 'txt|doc|docx|pdf',
        'max_size'      => 2048000,
        'max_width'     => 1024, //Mainly goes with images only
        'max_heigth'    => 768,
    ];

    $this->load->library('upload');
	$this->upload->initialize($config);

    if (!$this->upload->do_upload('userfile')) 
    {
        echo $error = ['error' => $this->upload->display_errors()];
		exit;
        //$this->load->view('upload_form', $error);
    } 
    else 
    {
          $data = array('upload_data' => $this->upload->data());
          $image_filename=$data['upload_data']['file_name']; 
		  		
		  //$result = $this->lead_model->UploadDoc($user_id,$doc_name,$quotation_id,$opportunity_id,$image_filename);
			
			redirect($url.'/opportunity/generate_view/'.$opportunity_id.'/'.$quotation_id);	
    }		   
		
		redirect($url.'/opportunity/generate_view/'.$opportunity_id.'/'.$quotation_id);		
	}
	
	public function generate_view($opportunity_id,$quotation_id)
	{		

		$data=array();
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$admin_session_data_user_data=$this->user_model->GetAdminData($user_id);
		$data['admin_session_data_user_data']=$admin_session_data_user_data[0];
		$data['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id);
		//print_r($data['quotation_data']); die();
		$data['quotation']=$data['quotation_data']['quotation'];
		if(isset($data['quotation']['letter_subject'])){
			$data['quotation']['letter_subject']=str_replace("Enquity","Enquiry",$data['quotation']['letter_subject']);
		}
		$data['lead_opportunity']=$data['quotation_data']['lead_opportunity_data'];
		$data['company']=$data['quotation_data']['company_log'];
		$data['customer']=$data['quotation_data']['customer_log'];
		$data['terms']=$data['quotation_data']['terms_log'];

		$data['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id);
		$data['opportunity_id']=$opportunity_id;
		$data['quotation_id']=$quotation_id;
		$data['shipping_terms']=$this->Opportunity_model->get_shipping_terms();
		$data['payment_terms']=$this->Opportunity_model->get_payment_terms();
		$data['selected_additional_charges']=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		/*echo '<pre>';
		print_r($data['quotation']);die;*/

		// Quotation Mail id 3		
		$data['email_forwarding_setting']=$this->Email_forwarding_setting_model->GetDetails(3);
		$this->load->view('admin/lead/generate_opportunity',$data);		
	}
	
	public function preview_quotation($opportunity_id,$quotation_id)
	{	
		$data=array();		
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$admin_session_data_user_data=$this->user_model->GetAdminData($user_id);
		$data['admin_session_data_user_data']=$admin_session_data_user_data[0];
		$data['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id);
		//print_r($data['quotation_data']); die();
		$data['quotation']=$data['quotation_data']['quotation'];
		$data['lead_opportunity']=$data['quotation_data']['lead_opportunity_data'];
		$data['company']=$data['quotation_data']['company_log'];
		$data['customer']=$data['quotation_data']['customer_log'];
		$data['terms']=$data['quotation_data']['terms_log_only_display_in_quotation'];

		$data['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id);

		$data['opportunity_id']=$opportunity_id;
		$data['quotation_id']=$quotation_id;
		$data['selected_additional_charges']=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		$data['curr_company']=$this->Setting_model->GetCompanyData();
		$data['download_url']='';
		$this->load->view('admin/lead/preview_quotation',$data);
		// $this->load->view('admin/lead/preview_quotation_old',$data);
		
	}
		

	

	// ==========================================================
	// ==========================================================

	public function generate_pdf($opportunity_id,$quotation_id,$action_type="")
	{
		$data=array();		
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$admin_session_data_user_data=$this->user_model->GetAdminData($user_id);
		$data['admin_session_data_user_data']=$admin_session_data_user_data[0];
		$data['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id);
		//print_r($data['quotation_data']); die();
		$data['quotation']=$data['quotation_data']['quotation'];
		$data['lead_opportunity']=$data['quotation_data']['lead_opportunity_data'];
		$data['company']=$data['quotation_data']['company_log'];
		$data['customer']=$data['quotation_data']['customer_log'];
		$data['terms']=$data['quotation_data']['terms_log_only_display_in_quotation'];

		$data['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id);
		$data['opportunity_id']=$opportunity_id;
		$data['quotation_id']=$quotation_id;

		// ===================================================
		// Update Status
		if($data['lead_opportunity']['status']==1)
		{
			$post_data=array(
							'status'=>2,
							'modify_date'=>date("Y-m-d")
							);
			$this->Opportunity_model->UpdateLeadOportunity($post_data,$opportunity_id);
		}
		// update status
		// =================================================== 

		// ===================================================
		// check is external quotation pdf uploaded
		$is_extermal_quote=$data['quotation']['is_extermal_quote'];
		if($is_extermal_quote=='Y')
		{
			$file_name=$data['quotation']['file_name'];
			$file_path="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/".$file_name;
			if($action_type=='F')
			{
				return base_url().$file_path;
			}
			else if($action_type=='D')
			{	
				$this->load->helper('download');				
				$tmp_path    =   file_get_contents(base_url().$file_path);
				$tmp_name    =   $file_name;
				//force_download($file_name, $file_path);
				force_download($tmp_name, $tmp_path);
				return true;
			}
		}
		// check is external quotation pdf uploaded
		// ===================================================
		

		// -----------------------------
	    // Generate PDF Script Start  
	    $pdfFileName = $data['quotation']['quote_no']."-QUOTE.pdf"; 
		//$pdfFilePath = "accounts/lmsportal/quotation/".$pdfFileName;
		$pdfFilePath = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/".$pdfFileName;
		
        $curr_date_time=date('Y-m-d H:i:s');
        $data['curr_datetime']=$curr_date_time;     
        $data['selected_additional_charges']=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
        $data['curr_company']=$this->Setting_model->GetCompanyData();
        
		$pdf_html = $this->load->view('admin/lead/quotation_pdf_view',$data,TRUE);
        //$pdf_html = $this->load->view('admin/lead/preview_quotation',$data,TRUE);
		// $pdf_html = $this->load->view('admin/lead/quotation_rander_pdf_view',$data,TRUE);
		// echo $pdf_html; die('ok');
		$this->load->library('m_pdf'); //load mPDF library
		$mpdf = new mPDF();
		$mpdf->fontdata["century-gothic"];
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
		// Footer Start					
		// $footer = '<div style="bottom: 10px; text-align: right; width: 100%;font-size: 11px;">Page {PAGENO} of {nb}</div>';
		// Footer End
		//$mpdf->SetHTMLFooter($footer,'E');
		//$mpdf->SetHTMLFooter($footer,'O');
		$mpdf->SetTitle("QUOTATION");
        $mpdf->SetAuthor($data['company']['name']);
        $mpdf->SetWatermarkText($data['company']['name']);
        $mpdf->showWatermarkText = true;
        $mpdf->watermarkTextAlpha = 0.08;
        $mpdf->SetDisplayMode('fullpage');
		//$stylesheet = file_get_contents(base_url().'styles/quotation_pdf.css'); // external css			
		//$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetDisplayMode('fullpage');
        $mpdf->defaultfooterfontstyle='';
        $mpdf->defaultfooterfontsize=8;
        $mpdf->defaultfooterline=0;
        //$mpdf->setFooter('Page {PAGENO} of {nb}');
        $mpdf->WriteHTML($pdf_html);
        // echo $mpdf->Output();die();
        if($action_type=='F')
        {

        	// -----------------------------
	        // REMOVE EXISTING PDF     
			$existing_pdf = base_url().$pdfFilePath;
			if (file_exists($existing_pdf)) {
				@unlink($existing_pdf);
			} else {}
		    // REMOVE EXISTING PDF 
			//echo file_upload_absolute_path().$pdfFilePath; die();			
        	$mpdf->Output($pdfFilePath, "F");        	
        	return $pdfFilePath;
        }
        else if($action_type=='D')
        {
        	return $mpdf->Output($pdfFileName, "D");
        }
        else
        {	
        	return $mpdf->Output();
        }
        
        
	}
	public function download_quotation($opportunity_id,$quotation_id)
	{
		$lead_id=get_value("lead_id","lead_opportunity","id=".$opportunity_id);
		$quotation_data=$this->quotation_model->GetQuotationData($quotation_id);
		if($quotation_data['lead_opportunity_data']['status']==1)
		{
			// UPDATE LEAD STAGE/STATUS
			$update_lead_data = array(
				'current_stage_id' =>'2',
				'current_stage' =>'QUOTED',
				'current_stage_wise_msg' =>'',
				'current_status_id'=>'2',
				'current_status'=>'HOT',
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
			$this->lead_model->CreateLeadStatusLog($status_post_data);
			
			$commnt="Quotation sent to client by Post";
			$ip=$_SERVER['REMOTE_ADDR'];
			$date=date("Y-m-d H:i:s");	
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$comment_title=SENT_TO_CLIENT;
			$historydata=array('title'=>$comment_title,'lead_id'=>$lead_id,'lead_opportunity_id'=>$opportunity_id,'comment'=>$commnt,'create_date'=>$date,'user_id'=>$update_by,'ip_address'=>$ip);
			$this->history_model->CreateHistory($historydata);

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
						'lead_opportunity_id'=>$opportunity_id,
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
        
		$this->generate_pdf($opportunity_id,$quotation_id,'D');
	}

	function qutation_send_to_buyer_by_mail_ajax()
  	{
		$session_data = $this->session->userdata('admin_session_data');
    	$user_id = $session_data['user_id'];
        $quotation_id=$this->input->post('quotation_id');
        $opportunity_id=$this->input->post('opp_id');
        $lead_id=get_value("lead_id","lead_opportunity","id=".$opportunity_id);
		$lead_info=$this->lead_model->GetLeadData($lead_id);
		$assigned_user_id=$lead_info->assigned_user_id;	
		$assigned_to_user_data=$this->user_model->get_employee_details($assigned_user_id);
        $list=array();
		
		$list['assigned_to_user']=$assigned_to_user_data;
		$list['is_resend']=($this->input->post('is_resend'))?$this->input->post('is_resend'):'N';
        $list['opportunity_id']=$opportunity_id;
        $list['quotation_id']=$quotation_id;
        $list['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id);
		$list['quotation']=$list['quotation_data']['quotation'];
		$list['lead_opportunity']=$list['quotation_data']['lead_opportunity_data'];
		$list['company']=$list['quotation_data']['company_log'];
		$list['customer']=$list['quotation_data']['customer_log'];
		$list['user_list']=$this->user_model->GetUserListAll('');
		$list['is_extermal_quote']=$list['quotation_data']['quotation']['is_extermal_quote'];

		$qid=$list['quotation_data']['quotation']['id'];
		$opportunity_id=$list['quotation_data']['quotation']['opportunity_id'];
		$d=explode('-', $list['quotation_data']['quotation']['quote_date']);
		$tmp_d=$d[0];
		$company_name=$list['quotation_data']['company_log']['name'];
		// $list['mail_subject'] = "Quote # ".$opportunity_id."/".$tmp_d." - Offer / Quote from ".$company_name;
		$list['mail_subject'] = "# ".$opportunity_id."/".$tmp_d." - Quote from ".$company_name." against your Enquiry";

		$list['curr_company']=$this->Setting_model->GetCompanyData();
		$list['quick_reply_comments']=$this->pre_define_comment_model->GetLeadUpdatePreDefineComments($user_id,'LU');
		$list['quick_reply_count']=count($data['quick_reply_comments']);
    	$html = $this->load->view('admin/lead/quotation_send_html_view',$list,TRUE);
        $data =array ("html"=>$html);
        $this->output->set_content_type('application/json');
        echo json_encode($data);
		exit;
    }

    function qutation_send_to_buyer_by_mail_confirm_ajax()
    {
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{			
			// Quotation Mail id 3
			$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(3);
			
			$session_data=$this->session->userdata('admin_session_data');
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
    		$quotation_id=$this->input->post('quotation_id');
			$opportunity_id=$this->input->post('opportunity_id');			
	        $to_mail_arr=$this->input->post('to_mail');
	        $to_mail_str=implode(",", $to_mail_arr);
 			$cc_mail_tmp_arr=$this->input->post('cc_mail');
			$is_resend=$this->input->post('is_resend');
			$reply_email_body=$this->input->post('reply_email_body');
			$is_company_brochure_attached_in_quotation_tmp=($this->input->post('is_company_brochure_attached_in_quotation'))?'Y':'N';
	        //echo $is_company_brochure_attached_in_quotation_tmp; die();
			//$cc_mail_str=implode(",", $cc_mail_arr);	        
	        //$self_cc_mail=get_value("email","user","id=".$update_by);
	        // $cc_mail_tmp=get_manager_and_skip_manager_email($update_by);
	        // $cc_mail_tmp=($cc_mail_tmp)?$cc_mail_tmp.','.$self_cc_mail:$self_cc_mail;
	        // $cc_mail_str=($cc_mail_str)?$cc_mail_str.','.$cc_mail_tmp:$cc_mail_tmp;     
 			
	        

	        if($to_mail_str)
	        {	        
	        	$quotation_data=$this->quotation_model->GetQuotationData($quotation_id);	
	        	$lead_id=$quotation_data['lead_opportunity_data']['lead_id'];
	        	$lead_info=$this->lead_model->GetLeadData($lead_id);
				$assigned_user_id=$lead_info->assigned_user_id;
				if($email_forwarding_setting['is_mail_send']=='Y')
				{
					$m_email=get_manager_and_skip_manager_email_arr($assigned_user_id);
			        // --------------------
			        // to mail assign logic
			        $to_mail_assign='';
			        $to_mail='';
			        if($email_forwarding_setting['is_send_mail_to_client']=='Y')
			        {
			        	$to_mail=$to_mail_str;
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
			        if($cc_mail_tmp_arr)
			        {
			        	$cc_mail_arr =  array_unique(array_merge($cc_mail_tmp_arr,$cc_mail_arr));
			        }			        
			        
			        $cc_mail_str='';
			        $cc_mail_str=implode(",", $cc_mail_arr);			        
			        // cc mail assign logic
			        // --------------------
					$mail_attached_arr=array();        	
					if($to_mail!='')
					{	
						$attach_filename='';
						// LEAD ATTACH FILE UPLOAD
						$this->load->library('upload', '');
						if($_FILES['attach_file']['name'] != "")
						{
							$config['upload_path'] = "assets/uploads/clients/log/";
							$config['allowed_types'] = "gif|jpg|jpeg|png|pdf|doc|docx|xlsx|xlsb|xls|csv";
							$config['overwrite'] = FALSE; 
							$config['encrypt_name'] = FALSE;
							$config['max_size'] = '1000000'; //KB
							$this->upload->initialize($config);
							if (!$this->upload->do_upload('attach_file'))
							{
								//echo $this->upload->display_errors();die();
							}
							else
							{
								$file_data = array('upload_data' => $this->upload->data());
								$attach_filename = $file_data['upload_data']['file_name'];
								array_push($mail_attached_arr, $config['upload_path'].$attach_filename);						
							}
						}
						//print_r($mail_attached_arr); die();
						// ============================
						// Quotation Mail
						// START
			        	$attach_file_path=$this->generate_pdf($opportunity_id,$quotation_id,'F');
			        	array_push($mail_attached_arr, $attach_file_path);


				        $template_str = '';
				        $e_data = array();

				        
						// $lead_id=$quotation_data['lead_opportunity_data']['lead_id'];
						// ------------------------------
			        	// product brochure attachment
			        	if($quotation_data['quotation']['is_product_brochure_attached_in_quotation']=='Y')
			        	{
			        		if(count($quotation_data['product_list']))
			        		{
			        			foreach($quotation_data['product_list'] as $product)
			        			{
									if($product->is_brochure_attached=='Y')
									{
										if(isset($product->brochure))
										{
											
											$product_brochure="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/pdf/".$product->brochure;
											array_push($mail_attached_arr, $product_brochure);
										}			        					
			        				}
			        			}
			        		}
			        	}
			        	// product brochure attachment
			        	// ------------------------------

				        // ------------------------------
			        	// company brochure attachment
			        	if($quotation_data['quotation']['is_company_brochure_attached_in_quotation']=='Y' || $is_company_brochure_attached_in_quotation_tmp=='Y')
			        	{
			        		$c=get_company_profile();
				        	if(isset($c['brochure_file']))
				        	{
				        		$company_brochure="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/brochure/".$c['brochure_file'];
				        		array_push($mail_attached_arr, $company_brochure);
				        	}
			        	}
			        	
			        	// company brochure attachment
			        	// ------------------------------
						// $lead_info=$this->lead_model->GetLeadData($quotation_data['lead_opportunity_data']['lead_id']);
						// $assigned_user_id=$lead_info->assigned_user_id;
				        $assigned_to_user_data=$this->user_model->get_employee_details($assigned_user_id);
				        
						//$company=get_company_profile();	
						//$e_data['company']=$company;
						$e_data['assigned_to_user']=$assigned_to_user_data;
						// $customer=$this->customer_model->GetCustomerData($quotation_data['quotation']['customer_id']);
						// $e_data['customer']=$customer;
						$e_data['lead_info']=$lead_info;


						$user_id=$this->session->userdata['admin_session_data']['user_id'];
						$admin_session_data_user_data=$this->user_model->get_employee_details($user_id);
						$e_data['admin_session_data_user_data']=$admin_session_data_user_data;
						$e_data['quotation']=$quotation_data['quotation'];
						$e_data['lead_opportunity']=$quotation_data['lead_opportunity_data'];
						$e_data['company']=$quotation_data['company_log'];
						$e_data['customer']=$quotation_data['customer_log'];
						$e_data['terms']=$quotation_data['terms_log_only_display_in_quotation'];
						$e_data['product_list']=$quotation_data['product_list'];
						$e_data['reply_email_body']=$reply_email_body;
				        $template_str = $this->load->view('admin/email_template/template/quotation_sent_view', $e_data, true);

				        $qid=$e_data['quotation']['id'];
				        $opportunity_id=$e_data['quotation']['opportunity_id'];
				        $d=explode('-', $e_data['quotation']['quote_date']);
				        $tmp_d=$d[0];
				        $company_name=$e_data['company']['name'];
				        // Developer mail start
				        $this->load->library('mail_lib');
				        $mail_data = array();
				        // $mail_data['from_mail']     = $session_data['email'];
				        // $mail_data['from_name']     = $session_data['name'];
				        $mail_data['from_mail']     = $self_cc_mail;
			        	$mail_data['from_name']     = $update_by_name;
				        // $mail_data['from_mail']     = $e_data['admin_session_data_user_data']['email'];
				        // $mail_data['from_name']     = $e_data['admin_session_data_user_data']['name'];
				        $mail_data['to_mail']       = $to_mail;		        
				        $mail_data['cc_mail']       = $cc_mail_str;
						//$mail_data['subject']       = $quotation_data['quotation']['letter_subject'];
						// $mail_subject_of_sent_quotation_to_client=($this->input->post('mail_subject_of_sent_quotation_to_client'))?$this->input->post('mail_subject_of_sent_quotation_to_client'):"Quote # ".$opportunity_id."/".$tmp_d." - Offer / Quote from ".$company_name;
						// $mail_subject_of_sent_quotation_to_client=($this->input->post('mail_subject_of_sent_quotation_to_client'))?$this->input->post('mail_subject_of_sent_quotation_to_client'):"# ".$opportunity_id."/".$tmp_d." - Quote from ".$company_name." against your Enquiry";
						$mail_subject_of_sent_quotation_to_client=($this->input->post('mail_subject'))?$this->input->post('mail_subject'):"# ".$opportunity_id."/".$tmp_d." - Quote from ".$company_name." against your Enquiry";
				        
						$mail_data['subject']       = $mail_subject_of_sent_quotation_to_client;
				        $mail_data['message']       = $template_str;
				        $mail_data['attach']        = $mail_attached_arr;

				        $mail_return = $this->mail_lib->send_mail($mail_data);
				        // Quotation Mail
				        // -----------------------------------------------------
						
						// -----------------------------------------------------
						// REMOVE EXISTING FILE
						if(file_exists($config['upload_path'].$attach_filename)){
							@unlink($config['upload_path'].$attach_filename);
						}else{}
						// REMOVE EXISTING FILE
						// -----------------------------------------------------
					}		        	
				}
        	

		        $status='success';
				$msg="Quotation has been sent successfully";
				
				// create history
				$commnt="Quotation sent to client by mail";
				$ip=$_SERVER['REMOTE_ADDR'];
				$date=date("Y-m-d H:i:s");	
				$update_by=$this->session->userdata['admin_session_data']['user_id'];
				$comment_title=SENT_TO_CLIENT;
				$historydata=array(
									'title'=>$comment_title,
									'lead_id'=>$lead_id,
									'lead_opportunity_id'=>$opportunity_id,
									'comment'=>$commnt,
									'sent_mail_quotation_to_client_from_mail'=>$session_data['email'],
									'sent_mail_quotation_to_client_from_name'=>$session_data['name'],
									'mail_subject_of_sent_quotation_to_client'=>$mail_subject_of_sent_quotation_to_client,
									'create_date'=>$date,
									'user_id'=>$update_by,
									'ip_address'=>$ip
								);

				$this->history_model->CreateHistory($historydata);

				// Create KPI Log (Quotation Sent Count:4)
				create_kpi_log(4,$update_by,'',date("Y-m-d H:i:s"));
				
				if($is_resend=='N')
				{ 
					// UPDATE LEAD STAGE/STATUS
					$update_lead_data = array(
						'current_stage_id' =>'2',
						'current_stage' =>'QUOTED',
						'current_stage_wise_msg' =>'',
						'current_status_id'=>'2',
						'current_status'=>'HOT',
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
					$stage_post_data=array();
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
					$this->lead_model->CreateLeadStatusLog($status_post_data);

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
								'lead_opportunity_id'=>$opportunity_id,
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
	        }
	        else
	        {
	        	$status='fail';
	        	$msg="Please select any mail to send.";
	        }
	        
	        
	        $data =array (
	                        "status"=>$status,
	                        "msg"=>$msg,
	                        "return"=>''
	                    );
	        header('Content-Type: application/json');
    		echo json_encode( $data );
    		exit;
    	}
        
    }


    function test_template_view()
    {
    	$quotation_data=$this->quotation_model->GetQuotationData(78);
		//print_r($data['quotation_data']); die();
		$e_data=array();
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		//$admin_session_data_user_data=$this->user_model->GetAdminData($user_id);
		//$e_data['admin_session_data_user_data']=$admin_session_data_user_data[0];
		$admin_session_data_user_data=$this->user_model->get_employee_details($user_id);
		$e_data['admin_session_data_user_data']=$admin_session_data_user_data;

		$e_data['quotation']=$quotation_data['quotation'];
		$e_data['lead_opportunity']=$quotation_data['lead_opportunity_data'];
		$e_data['company']=$quotation_data['company_log'];//print_r($e_data['company']);
		$e_data['customer']=$quotation_data['customer_log'];
		$e_data['terms']=$quotation_data['terms_log_only_display_in_quotation'];
		$e_data['product_list']=$quotation_data['product_list'];
    	
    	$template_str = $this->load->view('admin/email_template/template/quotation_template_layout', $e_data, true);
    	//$template_str = $this->load->view('email_template/template/auto_reply_template_layout', $e_data, true);
    	 echo $template_str ;
    }
	// ==========================================================	
	// ==========================================================

    public function product_discount_type_update_ajax()
	{	
		$quotation_id=$this->input->post('quotation_id');
		$opportunity_id=$this->input->post('opportunity_id');
		$is_discount_p_or_a=$this->input->post('is_discount_p_or_a');

		$postData=array();
		$postData=array('is_discount_p_or_a'=>$is_discount_p_or_a);
		$r=$this->quotation_model->UpdateQuotationProductByQuotationId($postData,$quotation_id);

		$postData=array();
		$postData=array('is_discount_p_or_a'=>$is_discount_p_or_a);
		$r2=$this->quotation_model->UpdateQuotationChargesByOppId($postData,$opportunity_id);


        
		/*
    	$list=array();
        $list['opportunity_id']=$opportunity_id;
        $list['quotation_id']=$quotation_id;
        $list['selected_additional_charges']=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
        $list['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id);
        $list['currency_list']=$this->lead_model->GetCurrencyList();
        $list['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id);
    	$html = $this->load->view('admin/quotation/updated_product_selected_list_ajax',$list,TRUE);
		*/
    	// --------------------------------
    	$list=array();
        $product_list=$this->quotation_model->GetQuotationProductList($quotation_id);
        
        $sub_total=0;
        $total_price=0;
        $total_discounted_price=0;
        foreach($product_list as $output)
        {
            $item_gst_per= $output->gst;
            $item_sgst_per= ($item_gst_per/2);
            $item_cgst_per= ($item_gst_per/2); 
            $item_is_discount_p_or_a=$output->is_discount_p_or_a; 
            $item_discount=$output->discount;
            $item_unit= $output->unit; 
            $item_price=($output->price/$item_unit);
            $item_qty=$output->quantity;
            $item_total_amount=($item_price*$item_qty);
            if($item_is_discount_p_or_a=='A'){
                $row_discount_amount=$item_discount;
            }
            else{
                $row_discount_amount=$item_total_amount*($item_discount/100);
            }           
            // $row_discount_amount=$item_total_amount*($item_discount_per/100);
            $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

            $row_final_price_tmp=($item_total_amount+$row_gst_amount-$row_discount_amount);
            $sub_total=$sub_total+$row_final_price_tmp;

            $total_price=$total_price+$item_total_amount;
            $total_discounted_price=$total_discounted_price+$row_discount_amount;
            $total_tax_price=$total_tax_price+$row_gst_amount;      
        }
        
        
        // =======================================
        // CALCULATE ADDITIONAL PRICE
        
        $additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
        
        if(count($additional_charges_list))
        {
            foreach($additional_charges_list as $charge)
            {

                $item_gst_per= $charge->gst;
                $item_sgst_per= ($item_gst_per/2);
                $item_cgst_per= ($item_gst_per/2);  
                $item_discount_per=$charge->discount; 
                $item_price= $charge->price;
                $item_qty=1;

                $item_total_amount=($item_price*$item_qty);
                $row_discount_amount=$item_total_amount*($item_discount_per/100);
                $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

                $row_final_price_tmp=($item_total_amount+$row_gst_amount-$row_discount_amount);
                $sub_total=$sub_total+$row_final_price_tmp; 

                $total_price=$total_price+$item_total_amount;
                $total_tax_price=$total_tax_price+$row_gst_amount;
                $total_discounted_price=$total_discounted_price+$row_discount_amount;   
            }
        }
		
        // CALCULATE ADDITIONAL PRICE
        // =======================================
        
        $list=array();
        $list['opportunity_id']=$opportunity_id;
        $list['quotation_id']=$quotation_id;        
        $list['selected_additional_charges']=$additional_charges_list;
        $list['prod_list']=$product_list;
        $list['currency_list']=$this->lead_model->GetCurrencyList();
        $list['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id);
        $list['unit_type_list']=$this->Product_model->GetUnitList();
        $html = $this->load->view('admin/quotation/updated_product_selected_list_ajax',$list,TRUE);
        
        

        // ---------------------------------
        $data_opportunity_update=array(
                                'deal_value'=>$sub_total,
                                'modify_date'=>date("Y-m-d H:i:s")
                                );  
        $this->Opportunity_model->UpdateLeadOportunity($data_opportunity_update,$opportunity_id);
        // ------------------------------------
        /*$result['total_sale_price'] = number_format($row_final_price,2);
        $result["total_deal_value"]=number_format($sub_total,2);

        $result["total_price"]=number_format($total_price,2);
        $result["total_discount"]=number_format($total_discounted_price,2);
        $result["total_tax"]=number_format($total_tax_price,2);
        $result["grand_total_round_off"]=number_format(round($sub_total),2);
        $result["number_to_word_final_amount"]=number_to_word(round($sub_total));
        $result["opportunity_id"] =$opportunity_id;
        // --------------------------
        */
    	$result["status"] = 'success';
    	$result['html'] = $html;
        echo json_encode($result);
        exit(0); 
	}

	public function del_prod_update_ajax()
	{	
		$id=$this->input->post('id');
		$opportunity_id=$this->input->post('opportunity_id');
		$del_data=$this->Opportunity_product_model->delete($id);
		if($del_data==true)
        	$result["status"] = 'success';
        else
        	$result["status"] = 'fail';

        
        $list=array();
        $list['opportunity_id']=$opportunity_id;
        $list['product_list']=$this->Opportunity_product_model->GetOpportunityProductList($opportunity_id);
        $list['selected_additional_charges']=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
    	$html = $this->load->view('admin/product/updated_product_selected_list_ajax',$list,TRUE);
    	$result['html'] = $html;
        echo json_encode($result);
        exit(0); 
	}
	public function update_sgst()
	{
		
		$sgst=$this->input->post('sgst');
		$quotation_id=$this->input->post('quotation_id');
		
		$data=array('sgst'=>$sgst);
		
		
		$updt_data=$this->quotation_model->UpdateQuotation($data,$quotation_id);
		CheckUserSpace();
	}
	
	public function update_cgst()
	{
		
		$cgst=$this->input->post('cgst');
		$quotation_id=$this->input->post('quotation_id');
		
		$data=array('cgst'=>$cgst);
		
		$updt_data=$this->quotation_model->UpdateQuotation($data,$quotation_id);
		CheckUserSpace();
	}
	
	public function update_disc()
	{
		
		$disc=$this->input->post('disc');
		$quotation_id=$this->input->post('quotation_id');
		
		$data=array('discount'=>$disc);
		
		$updt_data=$this->quotation_model->UpdateQuotation($data,$quotation_id);
		CheckUserSpace();
	}
		
	public function update_opportunity_prod_ajax()
	{
		$prod_id=$this->input->post('prod_id');
		$quantity=$this->input->post('quantity');
		$price=$this->input->post('price');
		$discount=$this->input->post('discount');
		$opp_id=$this->input->post('opp_id');
		$currency_id=$this->input->post('currency_id');
		
		$update_data=$this->Opportunity_product_model->UpdateOportunityProduct($quantity,$price,$discount,$prod_id,$currency_id,$opp_id);
		
		$opportunity_data=$this->Opportunity_model->GetOpportunityData($opp_id);
		$date=date("Y-m-d H:i:s");
		$ip_addr=$_SERVER['REMOTE_ADDR'];
		$message="Product Price updated in &quot;".$opportunity_data->opportunity_title."&quot;";
		$lead_id=$opportunity_data->lead_id;
		$comment_title=QUOTATION_PRODUCT_PRICE_UPDATE;
		$historydata=array('title'=>$comment_title,'lead_id'=>$lead_id,'comment'=>addslashes($message),'create_date'=>$date,'user_id'=>$user_id,'ip_address'=>$ip_addr);
		$this->history_model->CreateHistory($historydata);
			CheckUserSpace();
		$data['product_list']=$this->Opportunity_product_model->GetOpportunityProductList($opp_id);
		$data['communication_list']=$this->lead_model->GetCommunicationList();
		$this->load->view('admin/product/product_selected_list_update_ajax',$data);
		
	}

	public function update_opportunity_product_ajax()
	{
		$pid=$this->input->post('pid');
		$id=$this->input->post('id');
		$field=$this->input->post('field');
		$value=$this->input->post('value');		
		$data_post=array(						
						$field=>$value
						);
		$update_data=$this->Opportunity_product_model->update($data_post,$id);

		if($update_data==true)
        	$result["status"] = 'success';
        else
        	$result["status"] = 'fail';

        $opportunity_id=get_value("opportunity_id","opportunity_product","id=".$id);
        //$result['status']=$data_post;
        $list=array();
        $product_list=$this->Opportunity_product_model->GetOpportunityProductList($opportunity_id);
        $sub_total=0;
		foreach($product_list as $output)
		{
			$item_gst_per= $output->gst;
			$item_sgst_per= ($item_gst_per/2);
			$item_cgst_per= ($item_gst_per/2);  
			$item_discount_per=$output->discount; 
			$item_price= $output->price;
			$item_qty=$output->unit;

			$item_total_amount=($item_price*$item_qty);
			$row_discount_amount=$item_total_amount*($item_discount_per/100);
			$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

			$row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
			$sub_total=$sub_total+$row_final_price;			
		}

		// -------------------------------
		$product=$this->Opportunity_product_model->GetOpportunityProduct($opportunity_id,$pid);
		$item_gst_per= $product->gst;
		$item_sgst_per= ($item_gst_per/2);
		$item_cgst_per= ($item_gst_per/2);  
		$item_discount_per=$product->discount; 
		$item_price= $product->price;
		$item_qty=$product->unit;
		$item_total_amount=($item_price*$item_qty);
		$row_discount_amount=$item_total_amount*($item_discount_per/100);
		$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);
		$row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);

		// =======================================
		// CALCULATE ADDITIONAL PRICE
		
		$additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		if(count($additional_charges_list))
		{
			foreach($additional_charges_list as $charge)
			{

				$item_discount_per2= $charge->discount;
				$item_gst_per2= $charge->gst;
				$item_total_amount2=$charge->price;
				$row_discount_amount2=$item_total_amount2*($item_discount_per2/100);
				$row_gst_amount2=($item_total_amount2-$row_discount_amount2)*($item_gst_per2/100);

				$row_final_price2=($item_total_amount2+$row_gst_amount2-$row_discount_amount2);
				$sub_total=$sub_total+$row_final_price2;	
			}
		}
		// CALCULATE ADDITIONAL PRICE
		// =======================================

    	// $html = $this->load->view('admin/product/updated_product_selected_list_ajax',$list,TRUE);
    	// $result['html'] = $html;
    	$result["opportunity_id"] =$opportunity_id;
    	$result['total_sale_price'] = number_format($row_final_price,2);
    	$result['sub_total'] = number_format($sub_total,2);
    	$result['pid']=$pid;
        echo json_encode($result);
        exit(0); 
	}
	
	

	public function getquotelist_ajax()
	{		
		$opportunity_id=$this->input->post('opportunity_id');		
		$data['quotation_list']=$this->quotation_model->GetQuotationList($opportunity_id);
		$this->load->view('admin/lead/quotation_list_ajax',$data);		
	}
		
	public function del_prod_generate_ajax()
	{
		$prod_id=$this->input->post('prod_id');
		$opportunity_id=$this->input->post('opportunity_id');
		
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		
		$del_data=$this->Product_model->DeleteTempProduct($prod_id,$user_id);
		$del_data=$this->Opportunity_product_model->DeleteOppProduct($prod_id,$opportunity_id);
		
		
		$data['product_list']=$this->Product_model->GetTempAndOppProductList($user_id,$opportunity_id);
		$data['currency_list']=$this->Product_model->GetCurrencyList();
		$data['unit_type_list']=$this->Product_model->GetUnitList();
		$this->load->view('admin/product/product_selected_list_generate_ajax',$data);
		
	}
		
	public function AttachQuotationAjax()
	{ 
		$data['opportunity_id']=$opportunity_id;
		$data['quotation_id']=$quotation_id;
	}
	// public function save_download($opportunity_id,$quotation_id)
	// { 
	// 	$data['quotation']=$this->quotation_model->GetQuotationData($quotation_id);
	// 	//this the the PDF filename that user will get to download
	// 	$pdfFilePath =$data['quotation']->quote_no.".pdf";
	// 	$filename="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/".$pdfFilePath;
	// 	if (!file_exists($filename)) 
	// 	{
	// 		//load mPDF library
	// 		$this->load->library('m_pdf');
	// 		//load mPDF library
	// 		$data=array();		
	// 		$user_id=$this->session->userdata['admin_session_data']['user_id'];
	// 		$data['opportunity_id']=$opportunity_id;
	// 		$data['quotation_id']=$quotation_id;
	// 		$data['opportunity_data']=$this->Opportunity_model->GetOpportunityData($opportunity_id);
	// 		$data['customer_data']=$this->lead_model->GetLeadData($data['opportunity_data']->lead_id);
	// 		$data['opportunity_stage_list']=$this->Opportunity_model->GetOpportunityStageListAll();
	// 		$data['message']="";
	// 		$admin_data['data']=$this->user_model->GetSuperAdminData();				
	//    	 	if($admin_data['data'])
	//    	 	{
	// 	   	 	foreach($admin_data['data'] as $val)
	// 			{
	// 				$data['admin_id']=$val->id;
	// 				$data['id']=$val->id;
	// 				$data['name']=$val->name;				
	// 				$data['company_name']=$val->company_name;
	// 				$data['mobile']=$val->mobile;
	// 				$data['password']=$val->password;
	// 				$data['email']=$val->email;
	// 				$data['photo']=$val->photo;
	// 				$data['user_type']=$val->user_type;
	// 				$data['address']=$val->address;
	// 				$data['country_id']=$val->country_id;
	// 				$data['state']=$val->state;
	// 				$data['city']=$val->city;
	// 				$data['website']=$val->website;
	// 				$data['company_profile']=$val->company_profile;
	// 			}
	// 		}
	// 		$data['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id);
	// 		$data['quotation']=$this->quotation_model->GetQuotationData($quotation_id);
	// 		$html=$this->load->view('pdf_output',$data,true); //load the pdf_output.php by passing our data and get all data in $html varriable.
	// 		$create_date=date('Y-m-d');
	// 		$last_id=$this->quotation_model->LastQuotationID($opportunity_id);
	//    	 	$last_id=$last_id->id+1;			
	// 		$words = explode(" ", $data['company_name']);
	// 		$comp_name_initials = "";
	// 		foreach ($words as $w) {
	// 		  $comp_name_initials .= $w[0]; 
	// 		}
	// 		$quote_no='LB-'.$comp_name_initials.'-'.$opportunity_id.'-'.$last_id;
	// 		$pdf_create_data=array('opportunity_id'=>$opportunity_id,'quote_no'=>$quote_no,'create_date'=>$create_date);
	// 		$pdf_create=$this->quotation_model->CreateQuotationPDF($pdf_create_data);
	// 		//actually, you can pass mPDF parameter on this load() function
	// 		$pdf = $this->m_pdf->load();
	// 		$stylesheet = file_get_contents(base_url('styles/build.css'));
	// 		//generate the PDF!
	// 		$pdf->WriteHTML($stylesheet,1);
	// 		$pdf->WriteHTML($html,2);
	// 		//offer it to user via browser download! (The PDF won't be saved on your server HDD)
	// 	    $pdf->Output("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/".$pdfFilePath, "F");	
	// 		//$pdf->Output($pdfFilePath, "D");
	// 		$pdf->Output($pdfFilePath, "I");
	// 	} 
	// 	else
	// 	{
	// 		$filepath="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/".$pdfFilePath;
	// 		header('Content-Description: File Transfer');
	//         header('Content-Type: application/octet-stream');
	//         header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
	//         header('Expires: 0');
	//         header('Cache-Control: must-revalidate');
	//         header('Pragma: public');
	//         header('Content-Length: ' . filesize($filepath));
	//         flush(); // Flush system output buffer
	//         readfile($filepath);
	//         exit;
	// 	}		 	
	// }	
	
	// public function save_download_old($opportunity_id,$quotation_id)
	// { 
		
		
		
		
			
	// 	$data['source_list']=$this->source_model->GetSourceListAll();
	// 	$data['currency_list']=$this->lead_model->GetCurrencyList();
		
		
		
		
	// 	$data['quotation']=$this->quotation_model->GetQuotationData($quotation_id);
	
		
		
		
	// 	//this the the PDF filename that user will get to download
	// 	$pdfFilePath =$data['quotation']->quote_no.".pdf";
	// 	$filename="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/".$pdfFilePath;
		
	// 	//load mPDF library
	// 	$this->load->library('m_pdf');
	// 	//load mPDF library


	// 	$data=array();		
	// 	$user_id=$this->session->userdata['admin_session_data']['user_id'];
	// 	$data['opportunity_id']=$opportunity_id;
	// 	$data['quotation_id']=$quotation_id;
	// 	$data['opportunity_data']=$this->Opportunity_model->GetOpportunityData($opportunity_id);	
		
	// 	$data['customer_data']=$this->lead_model->GetLeadData($data['opportunity_data']->lead_id);
		
		
	// 	$data['opportunity_stage_list']=$this->Opportunity_model->GetOpportunityStageListAll();	
		
		
	// 	$data['message']="";
		
		
	// 	$admin_data['data']=$this->user_model->GetSuperAdminData(); 
   	 	
   	 	
		
 //   	 	if($admin_data['data'])
 //   	 	{
	//    	 	foreach($admin_data['data'] as $val)
	// 		{
	// 			$data['admin_id']=$val->id;
	// 			$data['id']=$val->id;
	// 			$data['name']=$val->name;				
	// 			$data['company_name']=$val->company_name;
	// 			$data['mobile']=$val->mobile;
	// 			$data['password']=$val->password;
	// 			$data['email']=$val->email;
	// 			$data['photo']=$val->photo;
	// 			$data['user_type']=$val->user_type;
	// 			$data['address']=$val->address;
	// 			$data['country_id']=$val->country_id;
	// 			$data['state']=$val->state;
	// 			$data['city']=$val->city;
	// 			$data['website']=$val->website;
	// 			$data['company_profile']=$val->company_profile;
				
				
				
	// 		}
	// 	}
		
		
		
		
		
		
		
	
	// 	$data['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id);

		
	// 	$data['quotation']=$this->quotation_model->GetQuotationData($quotation_id);
	// 	$html=$this->load->view('pdf_output',$data,true); //load the pdf_output.php by passing our data and get all data in $html varriable.
	// 	$create_date=date('Y-m-d');
	// 	$last_id=$this->quotation_model->LastQuotationID($opportunity_id);
 //   	 	$last_id=$last_id->id+1;
		
	// 	$words = explode(" ", $data['company_name']);
	// 	$comp_name_initials = "";

	// 	foreach ($words as $w) {
	// 	  $comp_name_initials .= $w[0]; 
	// 	}
	// 	$quote_no='LB-'.$comp_name_initials.'-'.$opportunity_id.'-'.$last_id;

	// 	$pdf_create_data=array('opportunity_id'=>$opportunity_id,'quote_no'=>$quote_no,'create_date'=>$create_date);
	// 	$pdf_create=$this->quotation_model->CreateQuotationPDF($pdf_create_data);
		
	// 	//actually, you can pass mPDF parameter on this load() function
	// 	$pdf = $this->m_pdf->load();
	// 	$stylesheet = file_get_contents(base_url('styles/build.css'));


	// 	//generate the PDF!
	// 	$pdf->WriteHTML($stylesheet,1);
	// 	$pdf->WriteHTML($html,2);
	// 	//offer it to user via browser download! (The PDF won't be saved on your server HDD)
		
		
	//     $pdf->Output("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/".$pdfFilePath, "F");
		
		
	// 	//$pdf->Output($pdfFilePath, "D");
	// 	$pdf->Output($pdfFilePath, "I");
		
		 	
	// }	
	/*
	public function send_buyer()
	{ 
		$command=$this->input->post('command');
		
		if($command)
		{
			$opportunity_id=$this->input->post('opportunity_id');
			$quotation_id=$this->input->post('quotation_id');
			$mail_body=$this->input->post('mail_body');
			//load mPDF library
		$this->load->library('m_pdf');
		//load mPDF library


		$data=array();		
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		
		$data['opportunity_id']=$opportunity_id;
		$data['quotation_id']=$quotation_id;
		$opportunity_data=$this->Opportunity_model->GetOpportunityData($opportunity_id);	
		
		$data['customer_data']=$this->lead_model->GetLeadData($opportunity_data->lead_id);
		
		
		$data['opportunity_stage_list']=$this->Opportunity_model->GetOpportunityStageListAll();	
		
		
		$data['message']="";
		
		
		
		$admin_data['data']=$this->user_model->GetSuperAdminData(); 
   	 	
   	 	
		
   	 	if($admin_data['data'])
   	 	{
	   	 	foreach($admin_data['data'] as $val)
			{
				$data['admin_id']=$val->id;
				$data['id']=$val->id;
				$data['name']=$val->name;				
				$data['company_name']=$val->company_name;
				$data['mobile']=$val->mobile;
				$data['password']=$val->password;
				$data['email']=$val->email;
				$data['photo']=$val->photo;
				$data['user_type']=$val->user_type;
				$data['address']=$val->address;
				$data['country_id']=$val->country_id;
				$data['state']=$val->state;
				$data['city']=$val->city;
				$data['website']=$val->website;
				$data['company_profile']=$val->company_profile;
				
				
				
			}
		}
		
		
		
		
		
		
		$data['quotation']=$this->quotation_model->GetQuotationData($quotation_id);
	
		$data['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id);

		
		$html=$this->load->view('pdf_output',$data,true); //load the pdf_output.php by passing our data and get all data in $html varriable.

		//this the the PDF filename that user will get to download
		$pdfFilePath =$data['quotation']->quote_no.".pdf";

		
		$filename="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/".$pdfFilePath;
		if (!file_exists($filename)) {
			$create_date=date('Y-m-d');
			$last_id=$this->quotation_model->LastQuotationID($opportunity_id);
	   	 	$last_id=$last_id->id+1;
			
			$words = explode(" ", $data['company_name']);
			$comp_name_initials = "";

			foreach ($words as $w) {
			  $comp_name_initials .= $w[0]; 
			}
			$quote_no='LB-'.$comp_name_initials.'-'.$opportunity_id.'-'.$last_id;
			$pdf_create_data=array('opportunity_id'=>$opportunity_id,'quote_no'=>$quote_no,'create_date'=>$create_date);
			$pdf_create=$this->quotation_model->CreateQuotationPDF($pdf_create_data);
		//actually, you can pass mPDF parameter on this load() function
		$pdf = $this->m_pdf->load();
		$stylesheet = file_get_contents(base_url('styles/build.css'));


		//generate the PDF!
		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);
		//offer it to user via browser download! (The PDF won't be saved on your server HDD)
		
		
	    	$pdf->Output("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/".$pdfFilePath, "F");
		
				}
				
				
			
			$subject=strip_tags($data['quotation']->subject);
			$subject=str_replace('Subject:','',$subject);
			 $this->email->set_mailtype("html");
	          $this->email->from('lmsbaba@maxbridgesolution.com');
	          $this->email->to($data['quotation']->email);
	          $this->email->cc($data['email']); 
	          $this->email->subject($subject);
	          $this->email->message($mail_body);
	          $this->email->attach($filename);
	          $this->email->send();
	         
	         $data=array('status'=>'1');
				
				$result=$this->quotation_model->UpdateQuotation($data,$quotation_id);
				
				
				
				

			$date=date("Y-m-d H:i:s");
			$ip_addr=$_SERVER['REMOTE_ADDR'];
			$message="&quot;".$data['quotation']->quote_no."&quot; sent to buyer.";
			$lead_id=$opportunity_data->lead_id;
			$comment_title=QUOTATION_SENT_BUYER;
			$historydata=array('title'=>$comment_title,'lead_id'=>$lead_id,'comment'=>addslashes($message),'create_date'=>$date,'user_id'=>$user_id,'ip_address'=>$ip_addr);
			$this->history_model->CreateHistory($historydata);
				
				$msg="Quotation sent to buyer successfully";
				$url=base_url().$this->session->userdata['admin_session_data']['lms_url'];
				$this->session->set_flashdata('success_msg', $msg);       
				CheckUserSpace();   
				redirect($url.'/opportunity/preview_quotation/'.$opportunity_id.'/'.$quotation_id);	
			
		}
	}
	*/
	/*
	public function download($opportunity_id,$quotation_id)
	{ 
		$data['quotation']=$this->quotation_model->GetQuotationData($quotation_id);
		//this the the PDF filename that user will get to download
		$pdfFilePath =$data['quotation']->quote_no.".pdf";
		$filename="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/".$pdfFilePath;
		if (!file_exists($filename)) {
		//load mPDF library
		$this->load->library('m_pdf');
		//load mPDF library


		$data=array();		
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$data['opportunity_id']=$opportunity_id;
		$data['quotation_id']=$quotation_id;
		$data['opportunity_data']=$this->Opportunity_model->GetOpportunityData($opportunity_id);	
		
		$data['customer_data']=$this->lead_model->GetLeadData($data['opportunity_data']->lead_id);
		
		$data['opportunity_stage_list']=$this->Opportunity_model->GetOpportunityStageListAll();	
		
		$data['message']="";
		
		$admin_data['data']=$this->user_model->GetSuperAdminData(); 
		
   	 	if($admin_data['data'])
   	 	{
	   	 	foreach($admin_data['data'] as $val)
			{
				$data['admin_id']=$val->id;
				$data['id']=$val->id;
				$data['name']=$val->name;				
				$data['company_name']=$val->company_name;
				$data['mobile']=$val->mobile;
				$data['password']=$val->password;
				$data['email']=$val->email;
				$data['photo']=$val->photo;
				$data['user_type']=$val->user_type;
				$data['address']=$val->address;
				$data['country_id']=$val->country_id;
				$data['state']=$val->state;
				$data['city']=$val->city;
				$data['website']=$val->website;
				$data['company_profile']=$val->company_profile;
				

			}
		}
		
		
		
		
		
		
		$data['quotation']=$this->quotation_model->GetQuotationData($quotation_id);
	
		$data['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id);

		
		$html=$this->load->view('pdf_output',$data,true); //load the pdf_output.php by passing our data and get all data in $html varriable.

		//this the the PDF filename that user will get to download
		$pdfFilePath =$data['quotation']->quote_no.".pdf";

		
		//actually, you can pass mPDF parameter on this load() function
		$pdf = $this->m_pdf->load();
		$stylesheet = file_get_contents(base_url('styles/build.css'));


		//generate the PDF!
		$pdf->WriteHTML($stylesheet,1);
		$pdf->WriteHTML($html,2);
		//offer it to user via browser download! (The PDF won't be saved on your server HDD)
		
		
		$content=$pdf->Output($pdfFilePath, "D");
		}		
		else
		{
			
			$filepath="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/".$pdfFilePath;
			header('Content-Description: File Transfer');
	        header('Content-Type: application/octet-stream');
	        header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
	        header('Expires: 0');
	        header('Cache-Control: must-revalidate');
	        header('Pragma: public');
	        header('Content-Length: ' . filesize($filepath));
	        flush(); // Flush system output buffer
	        readfile($filepath);
	        exit;
		}
		 
		 	
	}
	*/
	public function UpdateFromQuotationAjax()
	{
		
			$quotation_id=$this->input->get('quotation_id');
			$customer_id=$this->input->get('customer_id');
			$first_name=$this->input->get('first_name');
			$last_name=$this->input->get('last_name');			
			$mobile=$this->input->get('mobile');
			$email=$this->input->get('email');
			$address=$this->input->get('address');
			$city=$this->input->get('city');
			$state=$this->input->get('state');
			$zip=$this->input->get('zip');
			$country_id=$this->input->get('country_id');
			
			
			$data=array('customer_id'=>$customer_id,'first_name'=>$first_name,'last_name'=>$last_name,'mobile'=>$mobile,'address'=>$address,'city'=>$city,'state'=>$state,'zip'=>$zip,'country'=>$country_id);
			
			$result=$this->quotation_model->UpdateQuotation($data,$quotation_id);	
			if($result)
			{
				echo '<h6><strong>To,</strong></h6><h6><strong>'.$first_name.' '.$last_name.'</strong></h6><p>'.$address.' '.$city.' '.$state.'</p><p>Mobile: '.$mobile.'</p><p>Email: '.$email.'</p>';
				
				
			}
			else
			{
				echo '2';
			}
	}
	
	public function UpdateSubjectQuotationAjax()
	{
		
			$quotation_id=$this->input->post('quotation_id');
			$subject=$this->input->post('subject');
			
			
			
			$data=array('subject'=>$subject);
			
			$result=$this->quotation_model->UpdateQuotation($data,$quotation_id);	
			if($result)
			{
				
				echo '1';
				
				
			}
			else
			{
				echo '2';
			}
	}
	
	public function UpdateTermsConditionAjax()
	{
		
			$quotation_id=$this->input->post('quotation_id');
			$terms=$this->input->post('terms');
			
			
			
			$data=array('save_terms_conditions'=>$terms);
			
			$result=$this->quotation_model->UpdateQuotation($data,$quotation_id);	
			if($result)
			{
				echo '1';
			}
			else
			{
				echo '2';
			}
	}
	
	public function UpdateCompQuotationAjax()
	{
		
			$quotation_id=$this->input->post('quotation_id');
			$about_company=$this->input->post('about_company');
			
			
			
			$data=array('about_company'=>$about_company);
			
			$result=$this->quotation_model->UpdateQuotation($data,$quotation_id);	
			if($result)
			{
				echo '1';				
			}
			else
			{
				echo '2';
			}
	}

	public function quotation_pdf_upload_ajax()
	{		
		$opportunity_id=$this->input->post('opp_id');

		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$admin_session_data_user_data_tmp=$this->user_model->GetAdminData($user_id);
		$admin_session_data_user_data=$admin_session_data_user_data_tmp[0];
		$data['admin_session_data_user_data']=$admin_session_data_user_data;
		$data['opportunity_id']=$opportunity_id;
		$opportunity_data=$this->Opportunity_model->GetOpportunityData($opportunity_id);
		$data['opportunity_data']=$opportunity_data;
		$lead_id=$opportunity_data->lead_id;
		$lead_data=$this->lead_model->GetLeadData($opportunity_data->lead_id);
		$data['lead_data']=$lead_data;
		$customer_data=$this->Customer_model->GetCustomerData($lead_data->customer_id);
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
		// $get_last_quote_no=$this->quotation_model->get_last_quote_no();
		// if($get_last_quote_no)
		// {
		// 	$get_last_quote_no_arr=explode("-", $get_last_quote_no);
		// 	$no_tmp=($get_last_quote_no_arr[1]+1);
		// }
		// else
		// {
		// 	$no_tmp=1;
		// }
		$no_tmp=$opportunity_id;
		$quote_no=$company_name_tmp.'-000'.$no_tmp.'-'.$m_y_tmp;
		// QUOTE NO. LOGIC - END

		$config = array(
			'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/",
			'allowed_types' => "pdf",
			'overwrite' => FALSE,
			'encrypt_name' => FALSE,
			'file_name' => $quote_no.'-QUOTE.pdf',
			'max_size' => "2048000" 
			);
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
	   
		if (!$this->upload->do_upload('pdf_file'))
		{
			$error_msg=$this->upload->display_errors();
			$status = 'fail';	
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$file_name = $data['upload_data']['file_name'];

				
			
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
			
			// ================================================================
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
			// ================================================================

			// ================================================================
			// INSERT TO QUOTATION WISE PRODUCT TABLE
			//$prod_list=$this->Product_model->GetTempAndOppProductList($user_id,$opportunity_id);
			$prod_list=$this->Opportunity_model->get_opportunity_product($opportunity_id);
			$data['prod_list']=$prod_list;
			
			foreach($prod_list as $prod_data)
			{			
				$quotation_product_data=array(
											'quotation_id'=>$quotation_id,
											'product_id'=>$prod_data->product_id,
											'product_name'=>$prod_data->p_name,
											'product_sku'=>$prod_data->p_code,
											'unit'=>$prod_data->unit,
											'unit_type'=>$prod_data->unit_type_name,
											'price'=>$prod_data->price,
											'discount'=>$prod_data->discount,
											'gst'=>$prod_data->gst
											);
				$this->quotation_model->CreateQuotationProduct($quotation_product_data);
			}

			// INSERT TO QUOTATION WISE PRODUCT TABLE
			// ================================================================

			// ================================================================
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
			// ================================================================

			// ================================================================
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
			// ================================================================

			// ================================================================
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
			// ================================================================

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

			$success_msg="A new Quotation PDF (".$quote_no.") has been created.";
			$this->session->set_flashdata('success_msg', $msg);
			$status = 'success';	
		}
			
		$success_msg=$file_name;
		$result["status"] = $status;
		$result["error_msg"] = $error_msg;
		$result["success_msg"] = $success_msg;
		$result["quotation_id"] = $quotation_id;
		$result["opportunity_id"] = $opportunity_id;
		echo json_encode($result);
		exit(0);
	}
	
	public function UpdateCoverLetterQuotationAjax()
	{
		
			$quotation_id=$this->input->post('quotation_id');
			$cover_letter=$this->input->post('cover_letter');
			
			
			
			$data=array('cover_letter'=>$cover_letter);
			
			$result=$this->quotation_model->UpdateQuotation($data,$quotation_id);	
			if($result)
			{
				echo '1';				
			}
			else
			{
				echo '2';
			}
	}
	public function UpdateFecilateQuotationAjax()
	{
		
		$quotation_id=$this->input->post('quotation_id');
		$fecilate_text=$this->input->post('fecilate_text');
		
		$data=array('fecilate_text'=>$fecilate_text);
		
		$result=$this->quotation_model->UpdateQuotation($data,$quotation_id);	
		if($result)
		{
			echo '1';				
		}
		else
		{
			echo '2';
		}
	}
	
	public function UpdateSincereQuotationAjax()
	{
		
		$quotation_id=$this->input->get('quotation_id');
		
		$sincere_name=$this->input->get('sincere_name');
		$sincere_mobile=$this->input->get('sincere_mobile');
		$sincere_email=$this->input->get('sincere_email');
		$sincere_address=$this->input->get('sincere_address');
		
		$data=array('sincere_name'=>$sincere_name,'sincere_mobile'=>$sincere_mobile,'sincere_email'=>$sincere_email,'sincere_address'=>$sincere_address);
		
		$result=$this->quotation_model->UpdateQuotation($data,$quotation_id);	
		if($result)
		{
			echo '1';				
		}
		else
		{
			echo '2';
		}
	}
	public function UpdateTermsQuotationAjax()
	{
		
		$quotation_id=$this->input->post('quotation_id');
		
		$terms_condition=$this->input->post('terms_condition');
		
		$data=array('terms_condition'=>$terms_condition);
		
		$result=$this->quotation_model->UpdateQuotation($data,$quotation_id);	
		if($result)
		{
			echo '1';				
		}
		else
		{
			echo '2';
		}
	}
	
	public function UpdateProductNameQuotationAjax()
	{
		
		
		$id=$this->input->post('product_id');
		
		$product_name=$this->input->post('product_name');
		
		
		$data=array('product_name'=>$product_name);
		
		$result=$this->quotation_model->UpdateQuotationProduct($data,$id);	
		if($result)
		{
			echo '1';				
		}
		else
		{
			echo '2';
		}
	}
	
	public function UpdateValidQuotationAjax()
	{
		
		$quotation_id=$this->input->post('quotation_id');
		
		$valid_until=$this->input->post('valid_until');		
		$valid_until=date("Y-m-d", strtotime($valid_until));
		$data=array('valid_until'=>$valid_until);
		
		$result=$this->quotation_model->UpdateQuotation($data,$quotation_id);	
		if($result)
		{
			//echo '1';				
		}
		else
		{
			//echo '2';
		}
	}
	public function UpdateEnquiryQuotationAjax()
	{
		
		$quotation_id=$this->input->post('quotation_id');
		
		$enquiry_date=$this->input->post('enquiry_date');		
		$enquiry_date=date("Y-m-d", strtotime($enquiry_date));
		$data=array('enquiry_date'=>$enquiry_date);
		
		$result=$this->quotation_model->UpdateQuotation($data,$quotation_id);	
		if($result)
		{
			//echo '1';				
		}
		else
		{
			//echo '2';
		}
	}
	
	public function GetBankDetailsAjax()
	{
		
		
		$quotation_id=$this->input->post('quotation_id');
		
		$flag=$this->input->post('flag');
		if($flag=='1')
		{
			$bank_data=$this->quotation_model->GetBankDetails();	
			$data=array('name'=>$bank_data->name,'ac_no'=>$bank_data->ac_no,'bank_name'=>$bank_data->bank_name,'branch_name'=>$bank_data->branch_name,'ifsc_code'=>$bank_data->ifsc_code,'swift_code'=>$bank_data->swift_code);
			
			$result=$this->quotation_model->UpdateQuotation($data,$quotation_id);	
			
			if($result)
			{
				echo '1';				
			}
			else
			{
				echo '2';
			}
			
		}
		else
		{
			
			$data=array('name'=>'','ac_no'=>'','bank_name'=>'','branch_name'=>'','ifsc_code'=>'','swift_code'=>'');
			
			$result=$this->quotation_model->UpdateQuotation($data,$quotation_id);	
			
			if($result)
			{
				echo '1';				
			}
			else
			{
				echo '2';
			}
		}
	}
	public function GetStatutoryAjax()
	{
		
		
		$quotation_id=$this->input->post('quotation_id');
		
		$flag=$this->input->post('flag');
		if($flag=='1')
		{
			$statutory_data=$this->quotation_model->GetStatutoryDetails();	
			$data=array('gstn'=>$statutory_data->gstn,'pan'=>$statutory_data->pan);
			
			$result=$this->quotation_model->UpdateQuotation($data,$quotation_id);	
			
			if($result)
			{
				echo '1';				
			}
			else
			{
				echo '2';
			}
			
		}
		else
		{
			
			$data=array('gstn'=>'','pan'=>'');
			
			$result=$this->quotation_model->UpdateQuotation($data,$quotation_id);	
			
			if($result)
			{
				echo '1';				
			}
			else
			{
				echo '2';
			}
		}
	}
	
	
	public function generate_test_pdf()
	{
		// -----------------------------
        // Generate PDF Script Start
    	//ini_set('memory_limit', '-1');
        $pdfFileName = "test.pdf";

        // REMOVE EXISTING PDF
        $existing_pdf = base_url()."assets/pdf_download/".$pdfFileName;
		if (file_exists($existing_pdf)) {
			@unlink($existing_pdf);
		} else {
		    //echo "The file $existing_pdf does not exist";
		}
	    // REMOVE EXISTING PDF

        //this the the PDF filename that user will get to download
		$pdfFilePath = "accounts/lmsportal/quotation/".$pdfFileName;
		$data = array();
        $curr_date_time=date('Y-m-d H:i:s');
        $data['curr_datetime']=$curr_date_time;     
		//load the view and saved it into $html variable
		$pdf_html = $this->load->view('admin/quotation/pdf_view',$data,TRUE);
		//echo $pdf_html; die();
		$this->load->library('m_pdf'); //load mPDF library
		$mpdf = new mPDF();
		$mpdf->fontdata["century-gothic"];
		$mpdf->AddPage('P', // L - landscape, P - portrait 
                        '', '', '', '',
                        4, // margin_left
                        4, // margin right
                        4, // margin top
                        0, // margin bottom
                        0, // margin header
                        4	// margin footer
                       ); 

		// Footer Start					
		$footer = '';
		// Footer End

		$mpdf->SetHTMLFooter($footer,'E');
		$mpdf->SetHTMLFooter($footer,'O');

		$mpdf->SetTitle("Inspection Check List");
        $mpdf->SetAuthor($company_name);
        $mpdf->SetWatermarkText($company_name);
        $mpdf->showWatermarkText = true;
        //$this->m_pdf->pdf->watermark_font = 'DejaVuSansCondensed';
        $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->SetDisplayMode('fullpage');

		$stylesheet = file_get_contents(base_url().'styles/quotation_pdf.css'); // external css
		// $stylesheet2 = file_get_contents('assets/agentportal/check_list_pdf/css/style.css'); // external css			
		$mpdf->WriteHTML($stylesheet,1);
		// $mpdf->WriteHTML($stylesheet2,1);
		$mpdf->SetDisplayMode('fullpage');
        $mpdf->defaultfooterfontstyle='';
        $mpdf->defaultfooterfontsize=8;
        $mpdf->defaultfooterline=0;
        $mpdf->setFooter('Page {PAGENO} of {nb}');
        $mpdf->WriteHTML($pdf_html);
        //$mpdf->Output($pdfFilePath, "F");
        //$mpdf->Output($pdfFileName, "D");
        $mpdf->Output();
	}

	public function get_additional_charges_checkbox_view_ajax()
	{
		$data=array();
		$opportunity_id=$this->input->post('opp_id');
		$quotation_id=$this->input->post('q_id');
		$data['opportunity_id']=$opportunity_id;
		$data['quotation_id']=$quotation_id;
		$data['additional_charges_list']=$this->Opportunity_model->get_additional_charges($opportunity_id);
		$data['selected_additional_charges']=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		$data['unit_type_list']=$this->Product_model->GetUnitList();
		$this->load->view('admin/quotation/additional_charges_checkbox_view_ajax',$data);		
	}	

	

	

	public function rander_regret_reason_ajax()
	{
		$data=array();
		$list['rows']=get_regret_reason();
		$lead_id=$this->input->post('lead_id');
		$list['mail_subject']='Enquiry # '.$lead_id.' - Query/Update from your A/C Manager';
    	$html = $this->load->view('admin/lead/rander_regret_reason_ajax',$list,TRUE);
    	$result['html'] = $html;
        echo json_encode($result);
        exit(0); 	
	}	


	// =============================================
	// CREATE CUSTOM QUOTATION
	public function pdf_upload_for_custom_quotation_ajax()
	{		
		$error_msg='';
		$success_msg='';
		$return_status='';
		$deal_value='';
		$lead_id=$this->input->post('lead_id');
		$deal_value=$this->input->post('deal_value');
		$currency_type=($this->input->post('currency_type'))?$this->input->post('currency_type'):1;
		$lead_data=$this->lead_model->GetLeadData($lead_id);
		$opportunity_title=$lead_data->title;
		
		// $currency_type=1;				
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$status=1; // Pending
		if($lead_id)
		{
			$data_opportunity=array(
								'lead_id'=>$lead_id,
								'opportunity_title'=>$opportunity_title,
								'deal_value'=>$deal_value,
								'deal_value_as_per_purchase_order'=>$deal_value,
								'currency_type'=>$currency_type,
								'status'=>$status,
								'create_date'=>date("Y-m-d H:i:s"),
								'modify_date'=>date("Y-m-d H:i:s")
								);	
			$opportunity_id=$this->Opportunity_model->CreateLeadOportunity($data_opportunity);			
			$admin_session_data_user_data_tmp=$this->user_model->GetAdminData($user_id);
			$admin_session_data_user_data=$admin_session_data_user_data_tmp[0];
			$data['admin_session_data_user_data']=$admin_session_data_user_data;
			$data['opportunity_id']=$opportunity_id;
			$opportunity_data=$this->Opportunity_model->GetOpportunityData($opportunity_id);
			$data['opportunity_data']=$opportunity_data;					
			$data['lead_data']=$lead_data;
			$customer_data=$this->Customer_model->GetCustomerData($lead_data->customer_id);
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
			$config = array(
				'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/",
				'allowed_types' => "pdf",
				'overwrite' => FALSE,
				'encrypt_name' => FALSE,
				//'file_name' => $quote_no.'-QUOTE.pdf',
				'max_size' => "2048000" 
				);
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
		   
			if (!$this->upload->do_upload('pdf_file'))
			{
				$error_msg=$this->upload->display_errors();
				$this->Opportunity_model->delete_lead_opportunity($opportunity_id);	
				$return_status = 'fail';	
			}
			else
			{
				$data = array('upload_data' => $this->upload->data());
				$file_name = $data['upload_data']['file_name'];		
				
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

				$success_msg="A new Custom Quotation PDF (".$quote_no.") has been created.";
				$this->session->set_flashdata('success_msg', $msg);
				$return_status = 'success';	
			}	
		}			
		
		$result["status"] = $return_status;
		$result["error_msg"] = $error_msg;
		$result["success_msg"] = $success_msg;
		$result["quotation_id"] = $quotation_id;
		$result["opportunity_id"] = $opportunity_id;
		echo json_encode($result);
		exit(0);
	}

	public function pdf_upload_for_custom_quotation_bulk_ajax()
	{		
		$error_msg='';
		$success_msg='';
		$return_status='';
		$lead_ids=$this->input->post('lead_ids');

		$lead_id_arr=explode(",",$lead_ids);
		$quotation_id_arr=array();
		$opportunity_id_arr=array();
		$currency_type=1;				
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$status=1; // Pending
		
		if(count($lead_id_arr))
		{
			$this->load->library('upload', '');
			$config = array(
			'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/",
			'allowed_types' => "pdf",
			'overwrite' => TRUE,
			'encrypt_name' => FALSE,
			//'file_name' => $quote_no.'-QUOTE.pdf',
			'max_size' => "2048000" 
			);
			$this->upload->initialize($config);
				   
			if (!$this->upload->do_upload('pdf_file_bulk'))
			{
				$error_msg=$this->upload->display_errors();
				$return_status = 'fail';
				$result["status"] = $return_status;
				$result["error_msg"] = $error_msg;
				$result["success_msg"] = '';
				$result["quotation_id_str"] = '';
				$result["opportunity_id_str"] = '';
				echo json_encode($result);
				exit(0);
			}		
			else
			{
				$data = array('upload_data' => $this->upload->data());
				$file_name = $data['upload_data']['file_name'];
			

				foreach($lead_id_arr AS $lead_id)
				{
					$lead_data=$this->lead_model->GetLeadData($lead_id);
					$opportunity_title=$lead_data->title;
					$deal_value='';				
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
						array_push($opportunity_id_arr, $opportunity_id);

						$admin_session_data_user_data_tmp=$this->user_model->GetAdminData($user_id);
						$admin_session_data_user_data=$admin_session_data_user_data_tmp[0];
						$data['admin_session_data_user_data']=$admin_session_data_user_data;
						$data['opportunity_id']=$opportunity_id;
						$opportunity_data=$this->Opportunity_model->GetOpportunityData($opportunity_id);
						$data['opportunity_data']=$opportunity_data;					
						$data['lead_data']=$lead_data;
						$customer_data=$this->Customer_model->GetCustomerData($lead_data->customer_id);
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
						
						// $this->upload->initialize($config);	
						// if (!$this->upload->do_upload('pdf_file_bulk'))
						// {
						// 	$error_msg=$this->upload->display_errors();
						// 	$this->Opportunity_model->delete_lead_opportunity($opportunity_id);	
						// 	$return_status = 'fail';	
						// 	break;
						// }
						// else
						// {
							// $data = array('upload_data' => $this->upload->data());
							// $file_name = $data['upload_data']['file_name'];		
							
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
							array_push($quotation_id_arr, $quotation_id);
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
						// }	
					}

				}

				$success_msg="Bulk quotation have been created.";
				$this->session->set_flashdata('success_msg', $success_msg);
				$return_status = 'success';
			}
		}
		else
		{
			$return_status = 'fail';
			$error_msg='No lead available.';
		}
					
		
		$result["status"] = $return_status;
		$result["error_msg"] = $error_msg;
		$result["success_msg"] = $success_msg;
		$result["quotation_id_str"] = implode('#', $quotation_id_arr);
		$result["opportunity_id_str"] = implode('#', $opportunity_id_arr);
		echo json_encode($result);
		exit(0);
	}

	function bulk_qutation_send_to_buyer_by_mail_ajax()
  {      
  		// ini_set('max_execution_time', 0);
  		$quotation_id_str=$this->input->post('quotation_id_str');
      $opportunity_id_str=$this->input->post('opp_id_str');
      
      $quotation_id_arr=explode("#", $quotation_id_str);
      $opportunity_id_arr=explode("#", $opportunity_id_str);
      
      $list=array();
      $list['opportunity_id_str']=$opportunity_id_str;
      $list['quotation_id_str']=$quotation_id_str;

      $list['first_opportunity_id']=$opportunity_id_arr[0];
      $list['first_quotation_id']=$quotation_id_arr[0];
      //$send_to_email='';
      $send_to_email_arr=array();
      if(count($opportunity_id_arr))
      {
      	for($i=0;$i<count($opportunity_id_arr);$i++)
      	{
      		$quotation_id=$quotation_id_arr[$i];
      		$opportunity_id=$opportunity_id_arr[$i];
      		$quotation_data=$this->quotation_model->GetQuotationData($quotation_id);
      		$customer=$quotation_data['customer_log']; 
      		//$send_to_email .= $customer['email'].',';
      		array_push($send_to_email_arr, $customer['email']); 
      		if($customer['alt_email'])
      		{
      			//$send_to_email .= $customer['alt_email'].',';
      			array_push($send_to_email_arr, $customer['alt_email']);
      		}
      		     		
      	}
      }
      $list['send_to_email_arr']=$send_to_email_arr;
      //$list['send_to_email']=rtrim($send_to_email,",");
      $list['is_extermal_quote']='Y';
			$list['curr_company']=$this->Setting_model->GetCompanyData();		
    	$html = $this->load->view('admin/lead/bulk_quotation_send_html_view',$list,TRUE);		

      $data =array (
                    "html"=>$html
                  );
      $this->output->set_content_type('application/json');
      echo json_encode($data);
      exit;
  }
  function bulk_qutation_send_to_buyer_by_mail_confirm_ajax()
  {
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{			
			$this->load->library('mail_lib');
			// Quotation Mail id 3
			$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(3);
			
			$session_data=$this->session->userdata('admin_session_data');
			$update_by=$this->session->userdata['admin_session_data']['user_id'];

			$is_company_brochure_attached_in_quotation=$this->input->post('is_company_brochure_attached_in_quotation');
			$opportunity_id_str=$this->input->post('opportunity_id_str');
			$quotation_id_str=$this->input->post('quotation_id_str');

			if($is_company_brochure_attached_in_quotation=='Y'){
				$c=get_company_profile();
			}
				
			$user_id=$this->session->userdata['admin_session_data']['user_id'];
			$admin_session_data_user_data=$this->user_model->get_employee_details($user_id);

			$error_count=0;
			$to_mail_arr=array();
			$quotation_id_arr=explode("#", $quotation_id_str);
			$opportunity_id_arr=explode("#", $opportunity_id_str);
			if(count($quotation_id_arr))
			{
				for($i=0;$i<count($quotation_id_arr);$i++)
				{
					$to_mail_str='';
					$quotation_id=$quotation_id_arr[$i];
					$opportunity_id=$opportunity_id_arr[$i];
					$quotation_data=$this->quotation_model->GetQuotationData($quotation_id);
      		$customer=$quotation_data['customer_log'];
      		$to_mail_str .=$customer['email'];      		
      		if($customer['alt_email'])
      		{
      			$to_mail_str .=','.$customer['alt_email'];
      		}


					if($to_mail_str)
				  {	        
				  	//$quotation_data=$this->quotation_model->GetQuotationData($quotation_id);	
				  	$lead_id=$quotation_data['lead_opportunity_data']['lead_id'];
				  	$lead_info=$this->lead_model->GetLeadData($lead_id);
						$assigned_user_id=$lead_info->assigned_user_id;
						if($email_forwarding_setting['is_mail_send']=='Y')
						{
							$m_email=get_manager_and_skip_manager_email_arr($assigned_user_id);
				      // --------------------
				      // to mail assign logic
				      $to_mail_assign='';
				      $to_mail='';
				      if($email_forwarding_setting['is_send_mail_to_client']=='Y')
				      {
				      	$to_mail=$to_mail_str;
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
				      if($cc_mail_tmp_arr)
				      {
				      	$cc_mail_arr =  array_unique(array_merge($cc_mail_tmp_arr,$cc_mail_arr));
				      }			        
				      
				      $cc_mail_str='';
				      $cc_mail_str=implode(",", $cc_mail_arr);			        
				      // cc mail assign logic
				      // --------------------
							$mail_attached_arr=array();        	
							if($to_mail!='')
							{	
								// ============================
								// Quotation Mail
								// START
					        	$attach_file_path=$this->generate_pdf($opportunity_id,$quotation_id,'F');
					        	array_push($mail_attached_arr, $attach_file_path);


						        $template_str = '';
						        $e_data = array();

						        
								// $lead_id=$quotation_data['lead_opportunity_data']['lead_id'];
								// ------------------------------
			        	// product brochure attachment
			        	/*
			        	if($quotation_data['quotation']['is_product_brochure_attached_in_quotation']=='Y')
			        	{
			        		if(count($quotation_data['product_list']))
			        		{
			        			foreach($quotation_data['product_list'] as $product)
			        			{
			        				if(isset($product->brochure))
			        				{
			        					$product_brochure="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/pdf/".$product->brochure;
			        					array_push($mail_attached_arr, $product_brochure);
			        				}
			        			}
			        		}
			        	}
			        	*/
			        	// product brochure attachment
			        	// ------------------------------

				        // ------------------------------
			        	// company brochure attachment
			        	if($is_company_brochure_attached_in_quotation=='Y')
			        	{
			        		
				        	if(isset($c['brochure_file']))
				        	{
				        		$company_brochure="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/brochure/".$c['brochure_file'];
				        		array_push($mail_attached_arr, $company_brochure);
				        	}
			        	}
			        	
			        	// company brochure attachment
			        	// ------------------------------
								
						    $assigned_to_user_data=$this->user_model->get_employee_details($assigned_user_id);
								
								$e_data['assigned_to_user']=$assigned_to_user_data;								
								$e_data['lead_info']=$lead_info;


								
								$e_data['admin_session_data_user_data']=$admin_session_data_user_data;
								$e_data['quotation']=$quotation_data['quotation'];
								$e_data['lead_opportunity']=$quotation_data['lead_opportunity_data'];
								$e_data['company']=$quotation_data['company_log'];
								$e_data['customer']=$quotation_data['customer_log'];
								$e_data['terms']=$quotation_data['terms_log_only_display_in_quotation'];
								$e_data['product_list']=$quotation_data['product_list'];
						    $template_str = $this->load->view('admin/email_template/template/quotation_sent_view', $e_data, true);

				        $qid=$e_data['quotation']['id'];
				        $opportunity_id=$e_data['quotation']['opportunity_id'];
				        $d=explode('-', $e_data['quotation']['quote_date']);
				        $tmp_d=$d[0];
				        $company_name=$e_data['company']['name'];
				        // Developer mail start
				        
				        $mail_data = array();
						    $mail_data['from_mail']=$self_cc_mail;
					      $mail_data['from_name']=$update_by_name;
				        $mail_data['to_mail']=$to_mail;	
				        $mail_data['cc_mail']=$cc_mail_str;
						// $mail_subject_of_sent_quotation_to_client="Quote # ".$opportunity_id."/".$tmp_d." - Offer / Quote from ".$company_name;
						$mail_subject_of_sent_quotation_to_client="# ".$opportunity_id."/".$tmp_d." - Quote from ".$company_name." against your Enquiry";						
						$mail_data['subject']=$mail_subject_of_sent_quotation_to_client;
				        $mail_data['message']=$template_str;
				        $mail_data['attach']=$mail_attached_arr;
				        $mail_return=$this->mail_lib->send_mail($mail_data);
						    // Quotation Mail
						    // ---------------------------------
							}        	
						}
				    
						
						// create history
						$commnt="Quotation sent to client by mail";
						$ip=$_SERVER['REMOTE_ADDR'];
						$date=date("Y-m-d H:i:s");	
						$update_by=$this->session->userdata['admin_session_data']['user_id'];
						$comment_title=SENT_TO_CLIENT;
						$historydata=array(
											'title'=>$comment_title,
											'lead_id'=>$lead_id,
											'lead_opportunity_id'=>$opportunity_id,
											'comment'=>$commnt,
											'sent_mail_quotation_to_client_from_mail'=>$session_data['email'],
											'sent_mail_quotation_to_client_from_name'=>$session_data['name'],
											'mail_subject_of_sent_quotation_to_client'=>$mail_subject_of_sent_quotation_to_client,
											'create_date'=>$date,
											'user_id'=>$update_by,
											'ip_address'=>$ip
										);

						$this->history_model->CreateHistory($historydata);

						// UPDATE LEAD STAGE/STATUS
						$update_lead_data = array(
							'current_stage_id' =>'2',
							'current_stage' =>'QUOTED',
							'current_stage_wise_msg' =>'',
							'current_status_id'=>'2',
							'current_status'=>'HOT',
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
						$stage_post_data=array();
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
						$this->lead_model->CreateLeadStatusLog($status_post_data);
				  }
				  else
				  {
				  	$error_count++;
				  	$status='fail';
				  	$msg="Please select any mail to send.";
				  	break;
				  }
				}
			}

			if($error_count==0)
			{
				$status='success';
				$msg="Quotation has been sent successfully";
			}
			$data =array (
	                "status"=>$status,
	                "msg"=>$msg,
	                "return"=>''
	            	);

			header('Content-Type: application/json');
			echo json_encode( $data );
			exit;
		}        
	}

	public function clone_proporal($opportunity_id='',$quotation_id='')
	{		
		if($opportunity_id!='' && $quotation_id!='')
		{
			$opportunity_data=$this->Opportunity_model->GetOpportunityData($opportunity_id);
			$quotation_data=$this->quotation_model->GetQuotationData($quotation_id);
			// $prod_list=$this->quotation_model->GetQuotationProductList($quotation_id);
			//$additional_charges=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
			$quotation_info=$quotation_data['quotation'];
			$company_log_info=$quotation_data['company_log'];
			$customer_log_info=$quotation_data['customer_log'];
			$terms_log_info=$quotation_data['terms_log'];
			$terms_log_only_display_in_quotation_info=$quotation_data['terms_log_only_display_in_quotation'];
			
			$lead_id=$opportunity_data->lead_id;
			$source_id=$opportunity_data->source_id;
			$opportunity_title=$opportunity_data->opportunity_title;
			$deal_value=$opportunity_data->deal_value;
			$deal_value_as_per_purchase_order=$opportunity_data->deal_value_as_per_purchase_order;
			$currency_type=$opportunity_data->currency_type;

			$user_id=$this->session->userdata['admin_session_data']['user_id'];
			$status=1; // Pending
			// print_r($opportunity_data); die('111');	
			// ===========================================================
			// INSERT TO OPPORTUNITY TABLE
			$data_opportunity=array(
			'lead_id'=>$lead_id,
			'source_id'=>$source_id,
			'opportunity_title'=>$opportunity_title.' - Copy',
			'deal_value'=>$deal_value,
			'deal_value_as_per_purchase_order'=>$deal_value_as_per_purchase_order,
			'currency_type'=>$currency_type,
			'status'=>$status,
			'create_date'=>date("Y-m-d H:i:s"),
			'modify_date'=>date("Y-m-d H:i:s")
			);
			$new_opportunity_id=$this->Opportunity_model->CreateLeadOportunity($data_opportunity);	
			// INSERT TO OPPORTUNITY TABLE
			// ==========================================================

			// ===========================================================
			// INSERT TO OPPORTUNITY WISE PRODUCT TABLE	
			$prod_list=array();		
			$prod_list=$this->Opportunity_model->get_opportunity_product($opportunity_id);
			foreach($prod_list as $prod_data)
			{			
				$data_prd=array(
				'opportunity_id'=>$new_opportunity_id,
				'product_id'=>$prod_data->product_id,
				'unit'=>$prod_data->unit,
				'unit_type'=>$prod_data->unit_type,
				'quantity'=>$prod_data->quantity,
				'price'=>$prod_data->price,
				'currency_type'=>$currency_type,
				'discount'=>$prod_data->discount,
				'gst'=>$prod_data->gst,
				'create_date'=>date("Y-m-d")
				);
				$this->Opportunity_product_model->CreateOportunityProduct($data_prd);
			}
			// INSERT TO OPPORTUNITY WISE PRODUCT TABLE
			// ========================================================


			// ========================================================
			// INSERT TO OPPORTUNITY WISE ADDITIONAL CHARGES TABLE
			$additional_charges_arr=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);			
			foreach($additional_charges_arr as $additional_charges)
			{
				$data_post=array(
				'opportunity_id'=>$new_opportunity_id,
				'additional_charge_id'=>$additional_charges->additional_charge_id,
				'additional_charge_name'=>$additional_charges->additional_charge_name,
				'price'=>$additional_charges->price,
				'discount'=>$additional_charges->discount,
				'gst'=>$additional_charges->gst,
				'create_date'=>date("Y-m-d H:i:s")
				);
				$this->Opportunity_model->create_opportunity_additional_charges($data_post);
							
			}
			// INSERT TO OPPORTUNITY WISE ADDITIONAL CHARGES TABLE
			// ========================================================

			// QUOTE NO. LOGIC - START
			$company_data=$this->Setting_model->GetCompanyData();
			//$company_name_tmp = substr(strtoupper($company_data['name']),0,3);
			$words = explode(" ", $company_data['name']);
			$company_name_tmp = "";
			foreach ($words as $w) {
			  $company_name_tmp .= strtoupper($w[0]);
			}
			$m_y_tmp=date("m-y");				
			$no_tmp=$new_opportunity_id;
			$quote_no=$company_name_tmp.'-000'.$no_tmp.'-'.$m_y_tmp;
			// QUOTE NO. LOGIC - END

			// ====================================================
			// INSERT TO QUOTE TABLE			

			$quotation_post_data=array(	
			'opportunity_id'=>$new_opportunity_id,
			'customer_id'=>$quotation_info['customer_id'],
			'quote_no'=>$quote_no,
			'quote_title'=>$quotation_info['quote_title'],
			'quote_date'=>$quotation_info['quote_date'],
			'quote_valid_until'=>$quotation_info['quote_valid_until'],
			'currency_type'=>$quotation_info['currency_type'],
			'is_product_image_show_in_quotation'=>$quotation_info['is_product_image_show_in_quotation'],
			'is_product_youtube_url_show_in_quotation'=>$quotation_info['is_product_youtube_url_show_in_quotation'],
			'is_product_brochure_attached_in_quotation'=>$quotation_info['is_product_brochure_attached_in_quotation'],
			'is_hide_total_net_amount_in_quotation'=>$quotation_info['is_hide_total_net_amount_in_quotation'],
			'is_hide_gst_in_quotation'=>$quotation_info['is_hide_gst_in_quotation'],
			'is_show_gst_extra_in_quotation'=>$quotation_info['is_show_gst_extra_in_quotation'],
			'letter_to'=>$quotation_info['letter_to'],
			'letter_subject'=>$quotation_info['letter_subject'],
			'letter_body_text'=>$quotation_info['letter_body_text'],
			'letter_footer_text'=>$quotation_info['letter_footer_text'],
			'letter_terms_and_conditions'=>$quotation_info['letter_terms_and_conditions'],
			'letter_thanks_and_regards'=>$quotation_info['letter_thanks_and_regards'],
			'is_quotation_bank_details1_send'=>$quotation_info['is_quotation_bank_details1_send'],
			'is_quotation_bank_details2_send'=>$quotation_info['is_quotation_bank_details2_send'],
			'is_gst_number_show_in_quotation'=>$quotation_info['is_gst_number_show_in_quotation'],
			'create_date'=>date("Y-m-d H:i:s"),
			'modify_date'=>date("Y-m-d H:i:s")
			);
			$new_quotation_id=$this->quotation_model->CreateQuotation($quotation_post_data);
			// INSERT TO QUOTE TABLE
			// ====================================================

			// =====================================================
			// INSERT TO QUOTATION WISE PRODUCT TABLE	
			$prod_list=array();
			$prod_list=$this->quotation_model->GetQuotationProductList($quotation_id);		
			foreach($prod_list as $prod_data)
			{			
				$quotation_product_data=array(
							'quotation_id'=>$new_quotation_id,
							'product_id'=>$prod_data->product_id,
							'product_name'=>$prod_data->product_name,
							'product_sku'=>$prod_data->product_sku,
							'unit'=>$prod_data->unit,
							'unit_type'=>$prod_data->unit_type,
							'quantity'=>$prod_data->quantity,
							'price'=>$prod_data->price,
							'discount'=>$prod_data->discount,
							'gst'=>$prod_data->gst
							);
				$this->quotation_model->CreateQuotationProduct($quotation_product_data);

				$p_name=get_value("name","product_varient","id=".$prod_data->product_id);
				$lead_p_data=array(
					'lead_id'=>$lead_id,
					'lead_opportunity_id'=>$new_opportunity_id,
					'quotation_id'=>$new_quotation_id,
					'name'=>$p_name,
					'product_id'=>$prod_data->product_id,
					'tag_type'=>'Q',
					'created_at'=>date("Y-m-d H:i:s")
				);
				$this->lead_model->CreateLeadWiseProductTag($lead_p_data);
			}
			// INSERT TO QUOTATION WISE PRODUCT TABLE
			// ====================================================
			
			// ====================================================
			// INSERT TO CUSTOMER LOG TABLE
			
			$cust_log_post_data=array(	
								'quotation_id'=>$new_quotation_id,
								'first_name'=>$customer_log_info['first_name'],
								'last_name'=>$customer_log_info['last_name'],
								'contact_person'=>$customer_log_info['contact_person'],
								'designation'=>$customer_log_info['designation'],
								'email'=>$customer_log_info['email'],
								'alt_email'=>$customer_log_info['alt_email'],
								'mobile'=>$customer_log_info['mobile'],
								'alt_mobile'=>$customer_log_info['alt_mobile'],
								'office_phone'=>$customer_log_info['office_phone'],
								'website'=>$customer_log_info['website'],
								'company_name'=>$customer_log_info['company_name'],
								'address'=>$customer_log_info['address'],
								'city'=>$customer_log_info['city'],
								'state'=>$customer_log_info['state'],
								'country'=>$customer_log_info['country'],
								'zip'=>$customer_log_info['zip'],
								'gst_number'=>$customer_log_info['gst_number']
								);
			$this->quotation_model->CreateQuotationTimeCustomerLog($cust_log_post_data);

			// INSERT TO CUSTOMER LOG TABLE
			// ======================================================
			
			// ======================================================
			// INSERT TO COMPANY INFORMATION LOG TABLE				
			$company_info_log_post_data=array(	
								'quotation_id'=>$new_quotation_id,
								'logo'=>$company_log_info['logo'],
								'name'=>$company_log_info['name'],
								'address'=>$company_log_info['address'],
								'city'=>$company_log_info['city'],
								'state'=>$company_log_info['state'],
								'country'=>$company_log_info['country'],
								'pin'=>$company_log_info['pin'],
								'about_company'=>$company_log_info['about_company'],
								'gst_number'=>$company_log_info['gst_number'],
								'pan_number'=>$company_log_info['pan_number'],
								'ceo_name'=>$company_log_info['ceo_name'],
								'contact_person'=>$company_log_info['contact_person'],
								'email1'=>$company_log_info['email1'],
								'email2'=>$company_log_info['email2'],
								'mobile1'=>$company_log_info['mobile1'],
								'mobile2'=>$company_log_info['mobile2'],
								'phone1'=>$company_log_info['phone1'],
								'phone2'=>$company_log_info['phone2'],
								'website'=>$company_log_info['website'],
								'quotation_cover_letter_body_text'=>$company_log_info['quotation_cover_letter_body_text'],
								'quotation_terms_and_conditions'=>$company_log_info['quotation_terms_and_conditions'],
								'quotation_cover_letter_footer_text'=>$company_log_info['quotation_cover_letter_footer_text'],
								'quotation_bank_details1'=>$company_log_info['quotation_bank_details1'],
								'quotation_bank_details2'=>$company_log_info['quotation_bank_details2'],
								'bank_credit_to'=>$company_log_info['bank_credit_to'],
								'bank_name'=>$company_log_info['bank_name'],
								'bank_acount_number'=>$company_log_info['bank_acount_number'],
								'bank_branch_name'=>$company_log_info['bank_branch_name'],
								'bank_branch_code'=>$company_log_info['bank_branch_code'],
								'bank_ifsc_code'=>$company_log_info['bank_ifsc_code'],
								'bank_swift_number'=>$company_log_info['bank_swift_number'],
								'bank_telex'=>$company_log_info['bank_telex'],
								'bank_address'=>$company_log_info['bank_address'],
								'correspondent_bank_name'=>$company_log_info['correspondent_bank_name'],
								'correspondent_bank_swift_number'=>$company_log_info['correspondent_bank_swift_number'],
								'correspondent_account_number'=>$company_log_info['correspondent_account_number']
								);
			$this->quotation_model->CreateQuotationTimeCompanyInfoLog($company_info_log_post_data);

			// INSERT TO COMPANY INFORMATION LOG TABLE
			// ======================================================
			
			// ======================================================
			// INSERT TO TERMS AND CONDITIONS LOG TABLE

			if(count($terms_log_info))
			{
				foreach($terms_log_info as $term)
				{
					$term_log_post_data=array(	
					'quotation_id'=>$new_quotation_id,
					'name'=>$term['name'],
					'value'=>$term['value'],
					'is_display_in_quotation'=>$term['is_display_in_quotation']
					);
					$this->quotation_model->CreateQuotationTimeTermsLog($term_log_post_data);
				}
			}		

			// INSERT TO TERMS AND CONDITIONS LOG TABLE
			// ===================================================
			// =================================================
			// Create History log
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$date=date("Y-m-d H:i:s");
			//$ip_addr=$_SERVER['REMOTE_ADDR'];
			$ip_addr = $this->input->ip_address();
			$message="A copy of the existing quotation &quot;".$opportunity_title.' - Copy'."&quot;";
			$comment_title=QUOTATION_COPY;
			$historydata=array(
							'title'=>$comment_title,
							'lead_id'=>$lead_id,
							'lead_opportunity_id'=>$new_opportunity_id,
							'comment'=>addslashes($message),
							'create_date'=>$date,
							'user_id'=>$update_by,
							'ip_address'=>$ip_addr
							);
			//inserted_lead_comment_log($historydata);
			$this->history_model->CreateHistory($historydata);	
			// Create History log	
			// =================================================			
			$msg='A copy of existing quotation "'.$opportunity_title.'" has been created!';
			$this->session->set_flashdata('success_msg', $msg);
			$url=$this->session->userdata['admin_session_data']['lms_url'];
			//redirect($url.'/lead/edit/'.$lead_id);
			redirect($url.'/lead/send_quotation_popup/'.$lead_id);			
		}
	}

	public function clone_proporal_old($lead_opportunity_id)
	{
		
		$opportunity_data=$this->Opportunity_model->GetOpportunityData($lead_opportunity_id);
		
		//print_r($opportunity_data); die();
		$lead_id=$opportunity_data->lead_id;
		$source_id=$opportunity_data->source_id;
		$opportunity_title=$opportunity_data->opportunity_title;
		$deal_value=$opportunity_data->deal_value;
		$deal_value_as_per_purchase_order=$opportunity_data->deal_value_as_per_purchase_order;
		$currency_type=$opportunity_data->currency_type;

		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$status=1; // Pending

		// $opportunity_id=$this->input->post('opportunity_id');
		// $id=$this->input->post('id');
		// $field=$this->input->post('field');
		// $value=$this->input->post('value');		
		
		// ================================================================
		// INSERT TO OPPORTUNITY TABLE
		$data_opportunity=array(
								'lead_id'=>$lead_id,
								'source_id'=>$source_id,
								'opportunity_title'=>$opportunity_title.' - Copy',
								'deal_value'=>$deal_value,
								'deal_value_as_per_purchase_order'=>$deal_value_as_per_purchase_order,
								'currency_type'=>$currency_type,
								'status'=>$status,
								'create_date'=>date("Y-m-d H:i:s"),
								'modify_date'=>date("Y-m-d H:i:s")
								);	
		//print_r($data_opportunity);
		$new_opportunity_id=$this->Opportunity_model->CreateLeadOportunity($data_opportunity);	
		// INSERT TO OPPORTUNITY TABLE
		// ================================================================
		


		// ================================================================
		// INSERT TO OPPORTUNITY WISE PRODUCT TABLE
		
		$prod_list=$this->Opportunity_model->get_opportunity_product($lead_opportunity_id);
		foreach($prod_list as $prod_data)
		{			
			$data_prd=array(
							'opportunity_id'=>$new_opportunity_id,
							'product_id'=>$prod_data->product_id,
							'unit'=>$prod_data->unit,
							'unit_type'=>$prod_data->unit_type,
							'price'=>$prod_data->price,
							'currency_type'=>$currency_type,
							'discount'=>$prod_data->discount,
							'gst'=>$prod_data->gst,
							'create_date'=>date("Y-m-d")
							);
			$this->Opportunity_product_model->CreateOportunityProduct($data_prd);
		}

		// INSERT TO OPPORTUNITY WISE PRODUCT TABLE
		// ================================================================
		$additional_charges_arr=$this->Opportunity_model->get_selected_additional_charges($lead_opportunity_id);
		/*echo '<pre>';
		print_r($additional_charges_arr);die;*/
		foreach($additional_charges_arr as $additional_charges)
		{			
				
			$tmp_data=$this->Opportunity_model->get_additional_charges_by_id($additional_charges_id);
			$data_post=array(
							'opportunity_id'=>$new_opportunity_id,
							'additional_charge_id'=>$additional_charges->id,
							'additional_charge_name'=>$additional_charges->additional_charge_name,
							'price'=>$additional_charges->price,
							'discount'=>$additional_charges->discount,
							'gst'=>$additional_charges->gst,
							'create_date'=>date("Y-m-d H:i:s")
							);
			$this->Opportunity_model->create_opportunity_additional_charges($data_post);
						
		}

		// =================================================
		// Create History log
		$update_by=$this->session->userdata['admin_session_data']['user_id'];
		$date=date("Y-m-d H:i:s");
		//$ip_addr=$_SERVER['REMOTE_ADDR'];
		$ip_addr = $this->input->ip_address();
		$message="A copy of the existing quotation &quot;".$opportunity_title.' - Copy'."&quot;";
		$comment_title=QUOTATION_COPY;
		$historydata=array(
						'title'=>$comment_title,
						'lead_id'=>$lead_id,
						'lead_opportunity_id'=>$new_opportunity_id,
						'comment'=>addslashes($message),
						'create_date'=>$date,
						'user_id'=>$update_by,
						'ip_address'=>$ip_addr
						);
		//inserted_lead_comment_log($historydata);
		$this->history_model->CreateHistory($historydata);	
		// Create History log	
		// =================================================

		
		$msg='A copy of existing quotation "'.$opportunity_title.'" has been created!';
		$this->session->set_flashdata('success_msg', $msg);
		$url=$this->session->userdata['admin_session_data']['lms_url'];
		redirect($url.'/lead/edit/'.$lead_id);
	}

	public function add_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$error_msg='';
			$success_msg='';
			$return_status='';
			$user_id=$this->session->userdata['admin_session_data']['user_id'];
			$lead_id=$this->input->post('lead_id');
			$action_type=$this->input->post('action_type');		
			$currency_type=1;
			if($action_type=='add')
			{
				$lead_data=$this->lead_model->GetLeadData($lead_id);
				$opportunity_title=$lead_data->title;
				$deal_value='';				
				$status=1; // Pending
				$tmp_prod_list=$this->Product_model->GetTempProductList($user_id);
				if(count($tmp_prod_list))
				{
					$data_opportunity=array(
					'lead_id'=>$lead_id,
					'opportunity_title'=>$opportunity_title,
					// 'deal_value'=>str_replace(',',"",$deal_value),
					'currency_type'=>$currency_type,
					'status'=>$status,
					'create_date'=>date("Y-m-d H:i:s"),
					'modify_date'=>date("Y-m-d H:i:s")
										);	
					$create_opportunity=$this->Opportunity_model->CreateLeadOportunity($data_opportunity);	

					$deal_value_tmp=0; 
					foreach($tmp_prod_list as $tmp_prod_data)
					{
						// ------------------------------------------------
						// calculated value
						$item_gst_per= $tmp_prod_data->gst;
						$item_sgst_per= ($item_gst_per/2);
						$item_cgst_per= ($item_gst_per/2);  
						$item_discount_per=$tmp_prod_data->discount; 
						$item_unit=$tmp_prod_data->unit;
						// $item_price= $tmp_prod_data->price;
						$item_price= ($tmp_prod_data->price/$item_unit);
						$item_qty=$tmp_prod_data->quantity;					
						$item_total_amount=($item_price*$item_qty);
						$row_discount_amount=$item_total_amount*($item_discount_per/100);
						$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

						$row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
						$deal_value_tmp=$deal_value_tmp+$row_final_price;		
						// calculated value
						// ------------------------------------------------
						$data_prd=array(
						'opportunity_id'=>$create_opportunity,
						'product_id'=>$tmp_prod_data->product_id,
						'unit'=>$tmp_prod_data->unit,
						'unit_type'=>$tmp_prod_data->unit_type,
						'quantity'=>$tmp_prod_data->quantity,
						'price'=>$tmp_prod_data->price,
						'currency_type'=>$currency_type,
						'discount'=>$tmp_prod_data->discount,
						'gst'=>$tmp_prod_data->gst,
						'create_date'=>$tmp_prod_data->create_date
							);					
						$create_prod=$this->Opportunity_product_model->CreateOportunityProduct($data_prd);
					}
					$this->Product_model->DeleteTempProduct('',$user_id);
					
					// -----------------------------------------------------
					// Update deal value
					$data_opportunity_update=array(
					'deal_value'=>$deal_value_tmp,
					'modify_date'=>date("Y-m-d H:i:s")
					);	
					$this->Opportunity_model->UpdateLeadOportunity($data_opportunity_update,$create_opportunity);
					
					$admin_session_data_user_data_tmp=$this->user_model->GetAdminData($user_id);
					$admin_session_data_user_data=$admin_session_data_user_data_tmp[0];
					$data['admin_session_data_user_data']=$admin_session_data_user_data;

					$opportunity_id=$create_opportunity;
					$data['opportunity_id']=$opportunity_id;

					$opportunity_data=$this->Opportunity_model->GetOpportunityData($opportunity_id);
					$data['opportunity_data']=$opportunity_data;
					$lead_id=$opportunity_data->lead_id;
					//$lead_data=$this->lead_model->GetLeadData($opportunity_data->lead_id);
					$data['lead_data']=$lead_data;
					$customer_data=$this->Customer_model->GetCustomerData($lead_data->customer_id);
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
					
					$quote_valid_until=date('Y-m-d', strtotime("+30 days"));
					$letter_to="<b>To,</b><br>";
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
						if($customer_data->zip){							
							$letter_to .=' - '.$customer_data->zip;
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

					if($customer_data->gst_number){
						$letter_to .='<br><b>GST: </b>';
						$letter_to .=$customer_data->gst_number;
					}

					$letter_subject='<b>Subject:</b> '.$opportunity_data->opportunity_title.' (Enquiry Dated: '.date_db_format_to_display_format($opportunity_data->create_date).')';
					$letter_body_text='<b>Dear Sir/Ma’am,</b><br>'.$company_data['quotation_cover_letter_body_text'];
					$letter_footer_text=$company_data['quotation_cover_letter_footer_text'];
					$letter_terms_and_conditions=$company_data['quotation_terms_and_conditions'];		
					$letter_thanks_and_regards=$admin_session_data_user_data->name.'<br>Mobile:'.$admin_session_data_user_data->mobile.'<br>Email:'.$admin_session_data_user_data->email;
					
					$is_quotation_bank_details1_send=($company_data['quotation_bank_details1'])?'Y':'N';
					$is_quotation_bank_details2_send=($company_data['quotation_bank_details2'])?'Y':'N';
					$is_gst_number_show_in_quotation=($company_data['gst_number'])?'Y':'N';

					$name_of_authorised_signature=$company_data['authorized_signatory'];

					// ================================================================
					// INSERT TO QUOTE TABLE
					$quotation_post_data=array(	
					'opportunity_id'=>$opportunity_id,
					'customer_id'=>$lead_data->customer_id,
					'quote_title'=>'Quotation',
					'quote_no'=>$quote_no,
					'quote_date'=>date("Y-m-d"),
					'quote_valid_until'=>$quote_valid_until,
					'currency_type'=>$opportunity_data->currency_type_code,
					'letter_to'=>$letter_to,
					'letter_subject'=>$letter_subject,
					'letter_body_text'=>$letter_body_text,
					'letter_footer_text'=>$letter_footer_text,
					'letter_terms_and_conditions'=>$letter_terms_and_conditions,
					'letter_thanks_and_regards'=>$letter_thanks_and_regards,
					'name_of_authorised_signature'=>$name_of_authorised_signature,
					'is_quotation_bank_details1_send'=>$is_quotation_bank_details1_send,
					'is_quotation_bank_details2_send'=>$is_quotation_bank_details2_send,
					'is_gst_number_show_in_quotation'=>$is_gst_number_show_in_quotation,
					'create_date'=>date("Y-m-d H:i:s"),
					'modify_date'=>date("Y-m-d H:i:s")
					);
					$quotation_id=$this->quotation_model->CreateQuotation($quotation_post_data);
					// INSERT TO QUOTE TABLE
					// ======================================================
					

					// =====================================================
					// INSERT TO QUOTATION WISE PRODUCT TABLE
					//$prod_list=$this->Product_model->GetTempAndOppProductList($user_id,$opportunity_id);
					$prod_list=$this->Opportunity_model->get_opportunity_product($opportunity_id);
					$data['prod_list']=$prod_list;
					
					foreach($prod_list as $prod_data)
					{			
						$get_product=$this->Product_model->get_product($prod_data->product_id);

						$product_name_tmp='<b>'.$get_product['name'].'</b>';
						if($get_product['hsn_code'])
						{
							$product_name_tmp .='<br><b>HSN Code:</b> '.$get_product['hsn_code'];
						}
						if($get_product['code'])
						{
							$product_name_tmp .='<br><b>Product Code:</b> '.$get_product['code'];
						}
						if($get_product['description'])
						{
							$product_name_tmp .='<br><br>'.$get_product['description'];
						}

						$quotation_product_data=array(
						'quotation_id'=>$quotation_id,
						'product_id'=>$prod_data->product_id,
						'product_name'=>$product_name_tmp,
						'product_sku'=>$prod_data->p_code,
						'unit'=>$prod_data->unit,
						'unit_type'=>$prod_data->unit_type_name,
						'quantity'=>$prod_data->quantity,
						'price'=>$prod_data->price,
						'discount'=>$prod_data->discount,
						'gst'=>$prod_data->gst
						);
						$this->quotation_model->CreateQuotationProduct($quotation_product_data);
					}

					// INSERT TO QUOTATION WISE PRODUCT TABLE
					// ====================================================


					// ====================================================
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
					// ======================================================

					// ======================================================
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
										'quotation_bank_details1'=>$company_data['quotation_bank_details1'],
										'quotation_bank_details2'=>$company_data['quotation_bank_details2'],
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
					// ======================================================

					// ======================================================
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
					// ===================================================
					

					// ===================================================
					// Create History log
					$update_by=$this->session->userdata['admin_session_data']['user_id'];
					$date=date("Y-m-d H:i:s");
					$ip_addr = $this->input->ip_address();
					$message="A new Automated Quotation PDF (".$quote_no.") has been created.";
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
					
					$success_msg='A new quotation ('.$opportunity_title.') has been created.';
					$return_status='success';
				}
				else
				{
					$error_msg='No product selected for the proposal.';
					$return_status='fail';
				}
				$result["status"] = $return_status;
				$result["error_msg"] = $error_msg;
				$result["success_msg"] = $success_msg;
				$result["opportunity_id"] = $create_opportunity;
				$result["quotation_id"]=$quotation_id;
				echo json_encode($result);
				exit(0);
			}
			else if($action_type=='edit')
			{
				$quotation_id=$this->input->post('quotation_id');
				$opportunity_id=$this->input->post('opportunity_id');
				$tmp_prod_list=$this->Product_model->GetTempProductList($user_id);
				if(count($tmp_prod_list))
				{
					$deal_value_tmp=get_value('deal_value','lead_opportunity','id='.$opportunity_id); 

					$is_hide_gst_in_quotation='N';
					if($quotation_id>0)
					{
						$is_hide_gst_in_quotation=get_value('is_hide_gst_in_quotation','quotation','id='.$quotation_id);
					}
					

					foreach($tmp_prod_list as $tmp_prod_data)
					{
						if($opportunity_id!=$tmp_prod_data->opportunity_id)
						{
							// ------------------------------------------------
							// calculated value
							$item_gst_per= $tmp_prod_data->gst;
							$item_sgst_per= ($item_gst_per/2);
							$item_cgst_per= ($item_gst_per/2);  
							$item_discount_per=$tmp_prod_data->discount; 
							$item_unit=$tmp_prod_data->unit;
							$item_price= ($tmp_prod_data->price/$item_unit);
							// $item_price= $tmp_prod_data->price;
							$item_qty=$tmp_prod_data->quantity;					
							$item_total_amount=($item_price*$item_qty);
							$row_discount_amount=$item_total_amount*($item_discount_per/100);
							$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

							$row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
							$deal_value_tmp=$deal_value_tmp+$row_final_price;		
							// calculated value
							// ------------------------------------------------
							$data_prd=array(
							'opportunity_id'=>$opportunity_id,
							'product_id'=>$tmp_prod_data->product_id,
							'unit'=>$tmp_prod_data->unit,
							'unit_type'=>$tmp_prod_data->unit_type,
							'quantity'=>$tmp_prod_data->quantity,
							'price'=>$tmp_prod_data->price,
							'currency_type'=>$currency_type,
							'discount'=>$tmp_prod_data->discount,
							'gst'=>($is_hide_gst_in_quotation=='N')?$tmp_prod_data->gst:0,
							'create_date'=>$tmp_prod_data->create_date
							);					
							$this->Opportunity_product_model->CreateOportunityProduct($data_prd);

							$get_product=$this->Product_model->get_product($tmp_prod_data->product_id);

							$product_name_tmp='<b>'.$get_product['name'].'</b>';
							if($get_product['hsn_code'])
							{
								$product_name_tmp .='<br><b>HSN Code:</b> '.$get_product['hsn_code'];
							}
							if($get_product['code'])
							{
								$product_name_tmp .='<br><b>Product Code:</b> '.$get_product['code'];
							}
							if($get_product['description'])
							{
								$product_name_tmp .='<br><br>'.$get_product['description'];
							}

							$quotation_product_data=array(
							'quotation_id'=>$quotation_id,
							'product_id'=>$tmp_prod_data->product_id,
							'product_name'=>$product_name_tmp,
							'product_sku'=>$get_product['code'],
							'unit'=>$tmp_prod_data->unit,
							'unit_type'=>$tmp_prod_data->unit_type_name,
							'quantity'=>$tmp_prod_data->quantity,
							'price'=>$tmp_prod_data->price,
							'discount'=>$tmp_prod_data->discount,
							'gst'=>($is_hide_gst_in_quotation=='N')?$tmp_prod_data->gst:0
							);
							$this->quotation_model->CreateQuotationProduct($quotation_product_data);
						}
					}
					$this->Product_model->DeleteTempProduct('',$user_id);

					$data_opportunity_update=array(
					'deal_value'=>$deal_value_tmp,
					'modify_date'=>date("Y-m-d H:i:s")
					);	
					$this->Opportunity_model->UpdateLeadOportunity($data_opportunity_update,$opportunity_id);

					$success_msg='A new quotation ('.$opportunity_title.') has been created.';
					$return_status='success';
				}	
				else
				{
					$error_msg='No product selected for the proposal.';
					$return_status='fail';
				}			
					

				$result["status"] = $return_status;
				$result["error_msg"] = $error_msg;
				$result["success_msg"] = $success_msg;
				$result["opportunity_id"] = $opportunity_id;
				$result["quotation_id"]=$quotation_id;
				echo json_encode($result);
				exit(0);	
			}
		}
	}	

	public function edit_ajax()
	{
		$error_msg='';
		$success_msg='';
		$return_status='';
		$data=array();	
		$opportunity_id=$this->input->post('opp_id');
		$quotation_id=$this->input->post('quotation_id');
		$lead_id=$this->input->post('lead_id');
		$data['lead_id']=$this->input->post('lead_id');
		$data['lead_data']=$this->lead_model->GetLeadData($lead_id);	
		$data['opportunity_data']=$this->Opportunity_model->GetOpportunityData($opportunity_id);
		$data['opportunity_stage_list']=$this->Opportunity_model->GetOpportunityStageListAll();	
		$data['source_list']=$this->source_model->GetSourceListAll();
		$data['currency_list']=$this->lead_model->GetCurrencyList();		
		$data['communication_list']=$this->lead_model->GetCommunicationList();
		$data['tmp_prod_list']=$this->Opportunity_product_model->GetOpportunityProductList($opportunity_id);

		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$admin_session_data_user_data=$this->user_model->GetAdminData($user_id);
		$data['admin_session_data_user_data']=$admin_session_data_user_data[0];
		$data['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id);
		$data['quotation']=$data['quotation_data']['quotation'];
		if(isset($data['quotation']['letter_subject'])){
			$data['quotation']['letter_subject']=str_replace("Enquity","Enquiry",$data['quotation']['letter_subject']);
		}
		$data['lead_opportunity']=$data['quotation_data']['lead_opportunity_data'];
		$data['company']=$data['quotation_data']['company_log'];
		$data['customer']=$data['quotation_data']['customer_log'];
		$data['terms']=$data['quotation_data']['terms_log'];				

		// $data['terms_domestic']==$this->quotation_model->get_terms_and_conditions('terms_and_conditions_domestic_quotation');
		// $data['terms_export']==$this->quotation_model->get_terms_and_conditions('terms_and_conditions_export_quotation');

		$data['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id);
		$data['opportunity_id']=$opportunity_id;
		$data['quotation_id']=$quotation_id;
		$data['shipping_terms']=$this->Opportunity_model->get_shipping_terms();
		$data['payment_terms']=$this->Opportunity_model->get_payment_terms();
		$data['selected_additional_charges']=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);	

		// Quotation Mail id 3		
		$data['email_forwarding_setting']=$this->Email_forwarding_setting_model->GetDetails(3);
		$data['currency_list']=$this->lead_model->GetCurrencyList();
		$data['curr_company']=$this->Setting_model->GetCompanyData();
		$data['unit_type_list']=$this->Product_model->GetUnitList();		
		$this->load->view('admin/quotation/rander_automated_quotation_view_ajax',$data);
		// $this->load->view('admin/quotation/rander_automated_quotation_view_ajax_old',$data);
	}

	public function del_quotation_product_ajax()
	{	
		$id=$this->input->post('id');
		$quotation_id=$this->input->post('quotation_id');
		$opportunity_id=$this->input->post('opportunity_id');
		$pid=$this->input->post('pid');
		$this->Opportunity_model->delete_opp_product_by_opp_and_pid($opportunity_id,$pid);
		$del_data=$this->quotation_model->delete_product($id);

		if($del_data==true){
        	$result["status"] = 'success';
		}
        else{
        	$result["status"] = 'fail';
        }

        $list=array();
        $product_list=$this->quotation_model->GetQuotationProductList($quotation_id);
        $sub_total=0;
        $total_price=0;
        $total_discounted_price=0;
        $is_product_image_available='N';
        $is_product_youtube_video_available='N';
		$is_product_brochure_available='N'; 
		$img_flag=0;
		$youtube_video_flag=0;
		$brochure_flag=0; 
		foreach($product_list as $output)
		{
			$item_gst_per= $output->gst;
			$item_sgst_per= ($item_gst_per/2);
			$item_cgst_per= ($item_gst_per/2);  
			$item_discount_per=$output->discount;
			$item_unit= $output->unit; 
			$item_price=($output->price/$item_unit); 
			// $item_price= $output->price;
			$item_qty=$output->quantity;
			$item_total_amount=($item_price*$item_qty);
			$row_discount_amount=$item_total_amount*($item_discount_per/100);
			$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

			$row_final_price_tmp=($item_total_amount+$row_gst_amount-$row_discount_amount);
			$sub_total=$sub_total+$row_final_price_tmp;

			$total_price=$total_price+$item_total_amount;
			$total_discounted_price=$total_discounted_price+$row_discount_amount;
			$total_tax_price=$total_tax_price+$row_gst_amount;	

			if($output->image!='' && $img_flag==0){
				$is_product_image_available='Y';
				$img_flag=1;
			}
			if($output->youtube_video!='' && $youtube_video_flag==0){
		        $is_product_youtube_video_available='Y';
		        $youtube_video_flag=1;
		    }
			if($output->brochure!='' && $brochure_flag==0){
				$is_product_brochure_available='Y';
				$brochure_flag=1;
			}	
		}
		// -------------------------------
		/*
		$product=$this->quotation_model->GetQuotationProduct($quotation_id,$pid);
		$item_gst_per= $product->gst;
		$item_sgst_per= ($item_gst_per/2);
		$item_cgst_per= ($item_gst_per/2);  
		$item_discount_per=$product->discount; 
		$item_price= $product->price;
		$item_qty=$product->unit;
		$item_total_amount=($item_price*$item_qty);
		$row_discount_amount=$item_total_amount*($item_discount_per/100);
		$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);
		$row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
		*/
		// =======================================
		// CALCULATE ADDITIONAL PRICE
		
		$additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		if(count($additional_charges_list))
		{
			foreach($additional_charges_list as $charge)
			{

				$item_gst_per= $charge->gst;
				$item_sgst_per= ($item_gst_per/2);
				$item_cgst_per= ($item_gst_per/2);  
				$item_discount_per=$charge->discount; 
				$item_price= $charge->price;
				$item_qty=1;

				$item_total_amount=($item_price*$item_qty);
				$row_discount_amount=$item_total_amount*($item_discount_per/100);
				$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

				$row_final_price_tmp=($item_total_amount+$row_gst_amount-$row_discount_amount);
				$sub_total=$sub_total+$row_final_price_tmp; 

				$total_price=$total_price+$item_total_amount;
				$total_tax_price=$total_tax_price+$row_gst_amount;
				$total_discounted_price=$total_discounted_price+$row_discount_amount;	
			}
		}

		// CALCULATE ADDITIONAL PRICE
		// =======================================
		
    	// $html = $this->load->view('admin/product/updated_product_selected_list_ajax',$list,TRUE);
    	// $result['html'] = $html;

        $list=array();      
        
        // $list['selected_additional_charges']=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
        $list['selected_additional_charges']=$additional_charges_list;
        $list['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id);

        $list['currency_list']=$this->lead_model->GetCurrencyList();
        $list['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id);

        $list['quotation_id']=$quotation_id;
        $list['opportunity_id']=$opportunity_id;
        $list['unit_type_list']=$this->Product_model->GetUnitList();  
    	$html = $this->load->view('admin/quotation/updated_product_selected_list_ajax',$list,TRUE);
    	$result['html'] = $html;

    	// ------------------------------
    	$data_opportunity_update=array(
								'deal_value'=>$sub_total,
								'modify_date'=>date("Y-m-d H:i:s")
								);	
		$this->Opportunity_model->UpdateLeadOportunity($data_opportunity_update,$opportunity_id);
		// --------------------------
    	$result['total_sale_price'] = number_format($row_final_price,2);
		$result["total_deal_value"]=number_format($sub_total,2);

		$result["total_price"]=number_format($total_price,2);
		$result["total_discount"]=number_format($total_discounted_price,2);
		$result["total_tax"]=number_format($total_tax_price,2);
		$result["grand_total_round_off"]=number_format(round($sub_total),2);
		$result["number_to_word_final_amount"]=number_to_word(round($sub_total));

		$result['is_product_image_available'] =$is_product_image_available;
		$result['is_product_youtube_video_available'] =$is_product_youtube_video_available;
		$result['is_product_brochure_available'] =$is_product_brochure_available;
        echo json_encode($result);
        exit(0); 
	}

	public function del_additional_charges_update_ajax()
	{	
		$id=$this->input->post('id');
		$opportunity_id=$this->input->post('opportunity_id');
		$quotation_id=$this->input->post('quotation_id');
		$del_data=$this->Opportunity_model->delete_opportunity_additional_charges($id);
		if($del_data==true)
        	$result["status"] = 'success';
        else
        	$result["status"] = 'fail';

        // =======================================
        // GET SUB TOTAL PRICE
        $list=array();
        $product_list=$this->quotation_model->GetQuotationProductList($quotation_id);
        $sub_total=0;
        $total_price=0;
        $total_discounted_price=0;
		foreach($product_list as $output)
		{
			$item_gst_per= $output->gst;
			$item_sgst_per= ($item_gst_per/2);
			$item_cgst_per= ($item_gst_per/2);  
			$item_discount_per=$output->discount; 
			$item_unit=$output->unit;
	        $item_price=($output->price/$item_unit);
	        $item_qty=$output->quantity;

			$item_total_amount=($item_price*$item_qty);
			$row_discount_amount=$item_total_amount*($item_discount_per/100);
			$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

			$row_final_price_tmp=($item_total_amount+$row_gst_amount-$row_discount_amount);
			$sub_total=$sub_total+$row_final_price_tmp;

			$total_price=$total_price+$item_total_amount;
			$total_discounted_price=$total_discounted_price+$row_discount_amount;
			$total_tax_price=$total_tax_price+$row_gst_amount;		
		} 
		// GET SUB TOTAL PRICE
		// =======================================        
		
		// =======================================
		// CALCULATE ADDITIONAL PRICE
		$get_charges=$this->Opportunity_model->get_selected_additional_charges_by_id($id);

		$row_additional_discount_amount=($get_charges->price)*($get_charges->discount/100);	
		$row_additional_gst_amount=($get_charges->price-$row_additional_discount_amount)*($get_charges->gst/100);	
		$row_final_price=($get_charges->price+$row_additional_gst_amount-$row_additional_discount_amount);

		$additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		if(count($additional_charges_list))
		{
			foreach($additional_charges_list as $charge)
			{

				$item_gst_per= $charge->gst;
				$item_sgst_per= ($item_gst_per/2);
				$item_cgst_per= ($item_gst_per/2);  
				$item_discount_per=$charge->discount; 
				$item_price= $charge->price;
				$item_qty=1;

				$item_total_amount=($item_price*$item_qty);
				$row_discount_amount=$item_total_amount*($item_discount_per/100);
				$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

				$row_final_price_tmp=($item_total_amount+$row_gst_amount-$row_discount_amount);
				$sub_total=$sub_total+$row_final_price_tmp; 

				$total_price=$total_price+$item_total_amount;
				$total_tax_price=$total_tax_price+$row_gst_amount;
				$total_discounted_price=$total_discounted_price+$row_discount_amount;
			}
		}
		// CALCULATE ADDITIONAL PRICE
		// =======================================
		

        $list=array();
        $list['opportunity_id']=$opportunity_id;
        $list['quotation_id']=$quotation_id;        
		$list['selected_additional_charges']=$additional_charges_list;
        $list['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id);
        $list['currency_list']=$this->lead_model->GetCurrencyList();
        $list['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id);
        $list['unit_type_list']=$this->Product_model->GetUnitList();
    	$html = $this->load->view('admin/quotation/updated_product_selected_list_ajax',$list,TRUE);

    	// ------------------------------
    	$data_opportunity_update=array(
								'deal_value'=>$sub_total,
								'modify_date'=>date("Y-m-d H:i:s")
								);	
		$this->Opportunity_model->UpdateLeadOportunity($data_opportunity_update,$opportunity_id);
		// --------------------------

    	$result['html'] = $html;
    	$result['total_sale_price'] = number_format($row_final_price,2);
		$result["total_deal_value"]=number_format($sub_total,2);
		$result["total_price"]=number_format($total_price,2);
		$result["total_discount"]=number_format($total_discounted_price,2);
		$result["total_tax"]=number_format($total_tax_price,2);
		$result["grand_total_round_off"]=number_format(round($sub_total),2);
		$result["number_to_word_final_amount"]=number_to_word(round($sub_total));		
    	$result["opportunity_id"] =$opportunity_id;   	   	
    	$result['pid']=$id;
        echo json_encode($result);
        exit(0); 
	}

	public function update_quotation_product_ajax()
	{
		$pid=$this->input->post('pid');
		$id=$this->input->post('id');
		$field=$this->input->post('field');
		$value=$this->input->post('value');	
		$quotation_id=$this->input->post('quotation_id');
		$opportunity_id=$this->input->post('opportunity_id');
		$data_post=array(						
						$field=>$value
						);
		$update_data=$this->quotation_model->update_product($data_post,$id);

		if($update_data==true)
        	$result["status"] = 'success';
        else
        	$result["status"] = 'fail';
                
        $list=array();
        $product_list=$this->quotation_model->GetQuotationProductList($quotation_id);
        // echo'<pre>';
        // print_r($product_list);die();
        $sub_total=0;
        $total_price=0;
        $total_discounted_price=0;
		foreach($product_list as $output)
		{
			$item_gst_per= $output->gst;
			$item_sgst_per= ($item_gst_per/2);
			$item_cgst_per= ($item_gst_per/2); 
			$item_is_discount_p_or_a=$output->is_discount_p_or_a; 
			$item_discount=$output->discount; 
			$item_unit=$output->unit;
			$item_price=($output->price/$item_unit);
			$item_qty=$output->quantity;
			$item_total_amount=($item_price*$item_qty);
			if($item_is_discount_p_or_a=='A'){
				$row_discount_amount=$item_discount;
			}
			else{
				$row_discount_amount=$item_total_amount*($item_discount/100);
			}			
			
			$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

			$row_final_price_tmp=($item_total_amount+$row_gst_amount-$row_discount_amount);
			$sub_total=$sub_total+$row_final_price_tmp;

			$total_price=$total_price+$item_total_amount;
			$total_discounted_price=$total_discounted_price+$row_discount_amount;
			$total_tax_price=$total_tax_price+$row_gst_amount;		
		}
		// -------------------------------
		//echo $quotation_id.'=='.$pid; die('ok');
		//$product=$this->quotation_model->GetQuotationProduct($quotation_id,$pid);
		$product=$this->quotation_model->GetQuotationProduct($id);
		$item_gst_per= $product->gst;
		$item_sgst_per= ($item_gst_per/2);
		$item_cgst_per= ($item_gst_per/2); 
		$item_is_discount_p_or_a=$product->is_discount_p_or_a; 
		$item_discount_per=$product->discount;
		$item_unit=$product->unit; 
		$item_price=($product->price/$item_unit);
		$item_qty=$product->quantity;
		$item_total_amount=($item_price*$item_qty);
		if($item_is_discount_p_or_a=='A'){
			$row_discount_amount=$item_discount_per;
		}
		else{
			$row_discount_amount=$item_total_amount*($item_discount_per/100);
		}	
		$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

		$row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
		$row_final_amount=($item_total_amount-$row_discount_amount);
		
		// =======================================
		// CALCULATE ADDITIONAL PRICE
		
		$additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		if(count($additional_charges_list))
		{
			foreach($additional_charges_list as $charge)
			{

				$item_gst_per= $charge->gst;
				$item_sgst_per= ($item_gst_per/2);
				$item_cgst_per= ($item_gst_per/2);  
				$item_discount_per=$charge->discount; 
				$item_price= $charge->price;
				$item_qty=1;

				$item_total_amount=($item_price*$item_qty);
				$row_discount_amount=$item_total_amount*($item_discount_per/100);
				$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

				$row_final_price_tmp=($item_total_amount+$row_gst_amount-$row_discount_amount);
				$sub_total=$sub_total+$row_final_price_tmp; 

				$total_price=$total_price+$item_total_amount;
				$total_tax_price=$total_tax_price+$row_gst_amount;
				$total_discounted_price=$total_discounted_price+$row_discount_amount;	
			}
		}

		// CALCULATE ADDITIONAL PRICE
		// =======================================
		
    	

		// ---------------------------------
		$data_opportunity_update=array(
								'deal_value'=>$sub_total,
								'modify_date'=>date("Y-m-d H:i:s")
								);	
		$this->Opportunity_model->UpdateLeadOportunity($data_opportunity_update,$opportunity_id);
		// ------------------------------------
		// $result['total_sale_price']=number_format($row_final_price,2);
		$result['total_sale_price']=number_format($row_final_amount,2);

		$result["total_deal_value"]=number_format($sub_total,2);
		$result["total_price"]=number_format($total_price,2);
		$result["total_discount"]=number_format($total_discounted_price,2);
		$result["total_tax"]=number_format($total_tax_price,2);
		$result["grand_total_round_off"]=number_format(round($sub_total),2);
		$result["number_to_word_final_amount"]=number_to_word(round($sub_total));
    	$result["opportunity_id"]=$opportunity_id;
    	$result['pid']=$pid;
        echo json_encode($result);
        exit(0); 
	}

	public function update_opportunity_additional_charges_ajax()
	{
		$opportunity_id=$this->input->post('opportunity_id');
		$quotation_id=$this->input->post('quotation_id');
		$id=$this->input->post('id');
		$field=$this->input->post('field');
		$value=$this->input->post('value');		
		$data_post=array(						
						$field=>$value
						);
		$update_data=$this->Opportunity_model->update_opportunity_additional_charges($data_post,$id);

		if($update_data==true)
        	$result["status"] = 'success';
        else
        	$result["status"] = 'fail'; 

        // =======================================
        // GET SUB TOTAL PRICE
        $list=array();
        $product_list=$this->quotation_model->GetQuotationProductList($quotation_id);
        $sub_total=0;
        $total_price=0;
        $total_discounted_price=0;
		foreach($product_list as $output)
		{
			$item_gst_per= $output->gst;
			$item_sgst_per= ($item_gst_per/2);
			$item_cgst_per= ($item_gst_per/2); 
			$item_is_discount_p_or_a=$output->is_discount_p_or_a;  
			$item_discount_per=$output->discount; 
			$item_unit=$output->unit;
	        $item_price=($output->price/$item_unit);
	        $item_qty=$output->quantity;

			$item_total_amount=($item_price*$item_qty);
			if($item_is_discount_p_or_a=='A'){
				$row_discount_amount=$item_discount_per;
			}
			else{
				$row_discount_amount=$item_total_amount*($item_discount_per/100);
			}
			// $row_discount_amount=$item_total_amount*($item_discount_per/100);
			$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

			$row_final_price_tmp=($item_total_amount+$row_gst_amount-$row_discount_amount);
			$sub_total=$sub_total+$row_final_price_tmp;

			$total_price=$total_price+$item_total_amount;
			$total_discounted_price=$total_discounted_price+$row_discount_amount;
			$total_tax_price=$total_tax_price+$row_gst_amount;		
		} 
		// GET SUB TOTAL PRICE
		// =======================================        
		
		// =======================================
		// CALCULATE ADDITIONAL PRICE
		$get_charges=$this->Opportunity_model->get_selected_additional_charges_by_id($id);

		if($get_charges->is_discount_p_or_a=='A')
		{
			$row_additional_discount_amount=$get_charges->discount;
		}
		else
		{
			$row_additional_discount_amount=($get_charges->price)*($get_charges->discount/100);
		}
			
		$row_additional_gst_amount=($get_charges->price-$row_additional_discount_amount)*($get_charges->gst/100);	
		$row_final_price=($get_charges->price+$row_additional_gst_amount-$row_additional_discount_amount);
		$row_final_amount=($get_charges->price-$row_additional_discount_amount);

		$additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		if(count($additional_charges_list))
		{
			foreach($additional_charges_list as $charge)
			{

				$item_gst_per= $charge->gst;
				$item_sgst_per= ($item_gst_per/2);
				$item_cgst_per= ($item_gst_per/2);  
				$item_is_discount_p_or_a=$charge->is_discount_p_or_a;
				$item_discount_per=$charge->discount; 
				$item_price= $charge->price;
				$item_qty=1;

				$item_total_amount=($item_price*$item_qty);
				if($item_is_discount_p_or_a=='A'){
					$row_discount_amount=$item_discount_per;
				}
				else{
					$row_discount_amount=$item_total_amount*($item_discount_per/100);
				}
				// $row_discount_amount=$item_total_amount*($item_discount_per/100);
				$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

				$row_final_price_tmp=($item_total_amount+$row_gst_amount-$row_discount_amount);
				$sub_total=$sub_total+$row_final_price_tmp; 

				$total_price=$total_price+$item_total_amount;
				$total_tax_price=$total_tax_price+$row_gst_amount;
				$total_discounted_price=$total_discounted_price+$row_discount_amount;
			}
		}
		// CALCULATE ADDITIONAL PRICE
		// =======================================
		// $result['total_sale_price'] = $total_sale_price;
		// $result['sub_total'] = number_format($sub_total,2);

		// ----------------------------------------------
		$data_opportunity_update=array(
								'deal_value'=>$sub_total,
								'modify_date'=>date("Y-m-d H:i:s")
								);	
		$this->Opportunity_model->UpdateLeadOportunity($data_opportunity_update,$opportunity_id);
		// ------------------------------------------------

		// $result['total_sale_price'] = number_format($row_final_price,2);
		$result['total_sale_price'] = number_format($row_final_amount,2);
		$result["total_deal_value"]=number_format($sub_total,2);
		$result["total_price"]=number_format($total_price,2);
		$result["total_discount"]=number_format($total_discounted_price,2);
		$result["total_tax"]=number_format($total_tax_price,2);
		$result["grand_total_round_off"]=number_format(round($sub_total),2);
		$result["number_to_word_final_amount"]=number_to_word(round($sub_total));		
    	$result["opportunity_id"] =$opportunity_id;   	   	
    	$result['pid']=$id;
        echo json_encode($result);
        exit(0); 
	}

	public function update_opportunity_currency_ajax()
	{
		$currency_type=$this->input->post('currency_type');			
		$quotation_id=$this->input->post('quotation_id');
		$opportunity_id=$this->input->post('opportunity_id');

		// ---------------------------------------------------
		$data_update=array('currency_type'=>$currency_type);
		$this->Opportunity_model->UpdateLeadOportunity($data_update,$opportunity_id);
		// ---------------------------------------------------

		// ---------------------------------------------------
		$currency_code=get_value('code','currency','id='.$currency_type);
		$data_update2=array('currency_type'=>$currency_code);
		$this->quotation_model->UpdateQuotation($data_update2,$quotation_id);
		// ---------------------------------------------------

		// ======================================================
		// INSERT TO TERMS AND CONDITIONS LOG TABLE
		if($currency_code=='INR')
			$table_name='terms_and_conditions_domestic_quotation';
		else
			$table_name='terms_and_conditions_export_quotation';

		$terms_condition_list=$this->quotation_model->get_terms_and_conditions($table_name);
		$this->quotation_model->deleteQuotationTermsLog($quotation_id);
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
		// ===================================================


		// ---------------------------------------------------
		$opportunity_product_list=$this->Opportunity_model->get_opportunity_product($opportunity_id);	
		if(count($opportunity_product_list))
		{
			foreach($opportunity_product_list as $opp_prod)
			{			
				$data_update_tmp=array('currency_type'=>$currency_type);
				$this->Opportunity_product_model->update($data_update_tmp,$opp_prod->id);
			}
		}
		// ---------------------------------------------------

		if($currency_type!=1)
		{
			$product_list=$this->quotation_model->GetQuotationProductList($quotation_id);
			if(count($product_list))
			{
				foreach($product_list as $output)
				{
					$data_post=array(						
								'gst'=>'0'
								);
					$this->quotation_model->update_product($data_post,$output->id);
				}
			}		

			$additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
			if(count($additional_charges_list))
			{
				foreach($additional_charges_list as $charge)
				{
					$data_post=array(						
								'gst'=>'0'
								);
					$this->Opportunity_model->update_opportunity_additional_charges($data_post,$charge->id);
				}
			}
		}

        $list=array();
        $product_list=array();
        $product_list=$this->quotation_model->GetQuotationProductList($quotation_id);
        $sub_total=0;
        $total_price=0;
        $total_discounted_price=0;
		foreach($product_list as $output)
		{
			$item_gst_per= $output->gst;
			$item_sgst_per= ($item_gst_per/2);
			$item_cgst_per= ($item_gst_per/2); 
			$item_is_discount_p_or_a=$output->is_discount_p_or_a;   
			$item_discount_per=$output->discount; 
			$item_unit= $output->unit; 
			$item_price=($output->price/$item_unit);
			// $item_price= $output->price;
			$item_qty=$output->quantity;

			$item_total_amount=($item_price*$item_qty);
			if($item_is_discount_p_or_a=='A'){
              $row_discount_amount=$item_discount_per;
            }
            else{
              $row_discount_amount=$item_total_amount*($item_discount_per/100);
            }
			// $row_discount_amount=$item_total_amount*($item_discount_per/100);
			$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

			$row_final_price_tmp=($item_total_amount+$row_gst_amount-$row_discount_amount);
			$sub_total=$sub_total+$row_final_price_tmp;

			$total_price=$total_price+$item_total_amount;
			$total_discounted_price=$total_discounted_price+$row_discount_amount;
			$total_tax_price=$total_tax_price+$row_gst_amount;		
		}
		// -------------------------------	
		
		
		// =======================================
		// CALCULATE ADDITIONAL PRICE
		$additional_charges_list=array();
		$additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		if(count($additional_charges_list))
		{
			foreach($additional_charges_list as $charge)
			{

				$item_gst_per= $charge->gst;
				$item_sgst_per= ($item_gst_per/2);
				$item_cgst_per= ($item_gst_per/2); 
				$item_is_discount_p_or_a=$charge->is_discount_p_or_a; 
				$item_discount_per=$charge->discount; 
				$item_price= $charge->price;
				$item_qty=1;

				$item_total_amount=($item_price*$item_qty);
				if($item_is_discount_p_or_a=='A'){
                  $row_discount_amount=$item_discount_per;
                }
                else{
                  $row_discount_amount=$item_total_amount*($item_discount_per/100);
                }
				$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

				$row_final_price_tmp=($item_total_amount+$row_gst_amount-$row_discount_amount);
				$sub_total=$sub_total+$row_final_price_tmp; 

				$total_price=$total_price+$item_total_amount;
				$total_tax_price=$total_tax_price+$row_gst_amount;
				$total_discounted_price=$total_discounted_price+$row_discount_amount;	
			}
		}

		// CALCULATE ADDITIONAL PRICE
		// =======================================

		$list=array();
        $list['opportunity_id']=$opportunity_id;
        $list['quotation_id']=$quotation_id;        
        $list['selected_additional_charges']=$additional_charges_list;
        $list['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id);
        $list['currency_list']=$this->lead_model->GetCurrencyList();
        $list['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id);
        $list['unit_type_list']=$this->Product_model->GetUnitList();
    	$html = $this->load->view('admin/quotation/updated_product_selected_list_ajax',$list,TRUE);

    	 $list2['terms']=$list['quotation_data']['terms_log'];
    	$terms_html = $this->load->view('admin/quotation/updated_terms_log_ajax',$list2,TRUE);

    	// ---------------------------------
    	$data_opportunity_update=array(
								'deal_value'=>$sub_total,
								'modify_date'=>date("Y-m-d H:i:s")
								);	
		$this->Opportunity_model->UpdateLeadOportunity($data_opportunity_update,$opportunity_id);
		// -----------------------------------
    	$result['html'] = $html;
    	$result['total_sale_price'] = number_format($row_final_price,2);
		$result["total_deal_value"]=number_format($sub_total,2);

		$result["total_price"]=number_format($total_price,2);
		$result["total_discount"]=number_format($total_discounted_price,2);
		$result["total_tax"]=number_format($total_tax_price,2);
		$result["grand_total_round_off"]=number_format(round($sub_total),2);
		$result["number_to_word_final_amount"]=number_to_word(round($sub_total));
		$result['currency_code']=$currency_code;
		$result['terms_html']=$terms_html;

        echo json_encode($result);
        exit(0); 
	}

	public function selected_additional_charges_added_ajax()
	{
		$data=NULL;
		$additional_charges=$this->input->post('additional_charges');
		$opportunity_id=$this->input->post('opportunity_id');
		$quotation_id=$this->input->post('quotation_id');
		$data['opportunity_id']=$opportunity_id;
		$additional_charges_arr=explode(',',$additional_charges);				
		foreach($additional_charges_arr as $additional_charges_id)
		{			
			if($additional_charges_id!='')
			{				
				
				$tmp_data=$this->Opportunity_model->get_additional_charges_by_id($additional_charges_id);
				$data_post=array(
								'opportunity_id'=>$opportunity_id,
								'additional_charge_id'=>$additional_charges_id,
								'additional_charge_name'=>$tmp_data->name,
								'price'=>'',
								'gst'=>'',
								'create_date'=>date("Y-m-d H:i:s")
								);
				$this->Opportunity_model->create_opportunity_additional_charges($data_post);
			}			
		}

		$list=array();
        $list['opportunity_id']=$opportunity_id;
        $list['quotation_id']=$quotation_id;
        $list['selected_additional_charges']=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
        $list['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id);
        $list['currency_list']=$this->lead_model->GetCurrencyList();
        $list['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id);
        $list['unit_type_list']=$this->Product_model->GetUnitList();
    	$html = $this->load->view('admin/quotation/updated_product_selected_list_ajax',$list,TRUE);
    	$result['html'] = $html;
    	$result['msg'] = 'success';
        echo json_encode($result);
        exit(0);
        
	}

	function new_row_added_ajax()
	{
		$quotation_id=$this->input->post('quotation_id');
		$opportunity_id=$this->input->post('opportunity_id');

		$is_discount_p_or_a='P';
		$tmp_pro=$this->quotation_model->GetQuotationProductList($quotation_id);
		$last_sort=0;
		if(count($tmp_pro))
		{
			$is_discount_p_or_a=$tmp_pro[0]->is_discount_p_or_a;
			foreach($tmp_pro as $product)
      		{
				$last_sort=$product->sort;				
			}			
		}
		$last_sort=($last_sort+1);

		$post_data=array(
			'quotation_id'=>$quotation_id,
			'unit'=>1,
			'is_discount_p_or_a'=>$is_discount_p_or_a,
			'sort'=>$last_sort
		);		
		$this->quotation_model->CreateQuotationProduct($post_data);

		$list=array();
        $list['opportunity_id']=$opportunity_id;
        $list['quotation_id']=$quotation_id;        
        $list['selected_additional_charges']=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
        $list['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id);
        $list['currency_list']=$this->lead_model->GetCurrencyList();
        $list['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id);
        $list['unit_type_list']=$this->Product_model->GetUnitList();
        $html = $this->load->view('admin/quotation/updated_product_selected_list_ajax',$list,TRUE);
		$result['msg']='success';
		$result['html']=$html;
        echo json_encode($result);
        exit(0);
	}

	public function upload_quotation_photo_ajax()
	{		
		$error_msg='';
		$success_msg='';
		$return_status='';
		$quotation_id=$this->input->post('quotation_id');
		if($quotation_id)
		{
			$config = array(
				'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/",
				'allowed_types'=>"png|jpg|jpeg|gif",
				'overwrite'=>FALSE,
				'encrypt_name'=>TRUE,
				//'file_name'=> $quote_no.'-QUOTE.pdf',
				'max_size' => "2048000" 
				);
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
		   
			if (!$this->upload->do_upload('q_photo'))
			{
				$error_msg=$this->upload->display_errors();
				$return_status = 'fail';	
			}
			else
			{
				$data=array('upload_data' => $this->upload->data());
				$file_name=$data['upload_data']['file_name'];
				$post_data=array(
				'quotation_id'=>$quotation_id,
				'title'=>'',
				'file_name'=>$file_name
				);
				$this->quotation_model->addQuotationPhoto($post_data);

				$success_msg="A photo added to Quotation (".$quote_no.").";
				$this->session->set_flashdata('success_msg', $msg);
				$return_status = 'success';	
			}
				
		}			
		
		$result["status"]=$return_status;
		$result["error_msg"]=$error_msg;
		$result["success_msg"]=$success_msg;
		echo json_encode($result);
		exit(0);
	}

	public function rander_quotation_wise_photo_ajax()
	{
		$quotation_id=$this->input->post('quotation_id');
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$list['rows']=$this->quotation_model->GetQuotationPhoto($quotation_id);			
	    $html = $this->load->view('admin/quotation/rander_quotation_wise_photo_ajax',$list,TRUE);	    
	    $data =array (
	       "html"=>$html
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit();
	}

	public function del_q_photo_ajax()
	{
		$id=$this->input->post('id');
		$row=$this->quotation_model->GetQuotationPhotoRow($id);
		$return=$this->quotation_model->DelQuotationPhoto($id);
		if($return==true){

			if($row->file_name)
			{
				@unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/quotation/'.$row->file_name);
			}
			echo'success';
		}
		else{
			echo'fail';
		}
	}

	function quotation_photo_update_ajax()
    {
        $id=$this->input->post('id');
        $updated_field_name=$this->input->post('updated_field_name');
        $updated_content=$this->input->post('updated_content');

		$data=array(
			$updated_field_name=>$updated_content
		);
		$this->quotation_model->UpdateQuotationPhoto($data,$id);

        $msg = "Quation photo successfully updated!"; // 'duplicate';         
        //$this->session->set_flashdata('success_msg', $msg);
        $data=array("status"=>'success');
        $this->output->set_content_type('application/json');
        echo json_encode($data);
    }

    function add_q_photo_from_myDocument_ajax()
	{
		$quotation_id=$this->input->post('quotation_id');
		$selected_id_to_add=$this->input->post('selected_id_to_add');
		
		if(count($selected_id_to_add))
		{
			foreach($selected_id_to_add AS $id)
			{
				$get_info=$this->Setting_model->getMyDocument($id);	
				$file_name=$get_info['file_name'];
				$title=$get_info['title'];		
				$source = 'assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/my_documents/'.$file_name;

				$destination = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/".$file_name;
				if( !copy($source, $destination) ) {
					// echo "File can't be copied! \n";
				}
				else {
					$post_data=array(
						'quotation_id'=>$quotation_id,
						'title'=>$title,
						'file_name'=>$file_name
					);
					$this->quotation_model->addQuotationPhoto($post_data);
				}
			}
		}
		
		$result['msg']='success';
		$result['html']=$html;
        echo json_encode($result);
        exit(0);
	}

	function qutation_delete_ajax()
  	{

        $quotation_id=$this->input->post('quotation_id');
        $opportunity_id=$this->input->post('opp_id');
        $lead_id=get_value("lead_id","lead_opportunity","id=".$opportunity_id);
        $this->quotation_model->qutation_delete($lead_id,$quotation_id,$opportunity_id);
        $msg="";
        $status="success";
        $data =array(
        	"msg"=>$msg,
        	"status"=>$status
        );
        $this->output->set_content_type('application/json');
        echo json_encode($data);
    }

    public function quotation_hide_gst_zero()
	{		
		$quotation_id=$this->input->post('quotation_id');
		$opportunity_id=$this->input->post('opportunity_id');
		
		$product_list=$this->quotation_model->GetQuotationProductList($quotation_id);
		if(count($product_list))
		{
			foreach($product_list as $output)
			{
				$data_post=array(						
							'gst'=>'0'
							);
				$this->quotation_model->update_product($data_post,$output->id);
			}
		}		

		$additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		if(count($additional_charges_list))
		{
			foreach($additional_charges_list as $charge)
			{
				$data_post=array(						
							'gst'=>'0'
							);
				$this->Opportunity_model->update_opportunity_additional_charges($data_post,$charge->id);
			}
		}
		

        $list=array();
        $product_list=array();
        $product_list=$this->quotation_model->GetQuotationProductList($quotation_id);
        $sub_total=0;
        $total_price=0;
        $total_discounted_price=0;
		foreach($product_list as $output)
		{
			$item_gst_per= $output->gst;
			$item_sgst_per= ($item_gst_per/2);
			$item_cgst_per= ($item_gst_per/2); 
			$item_is_discount_p_or_a=$output->is_discount_p_or_a;   
			$item_discount_per=$output->discount; 
			$item_unit= $output->unit; 
			$item_price=($output->price/$item_unit);
			// $item_price= $output->price;
			$item_qty=$output->quantity;

			$item_total_amount=($item_price*$item_qty);
			if($item_is_discount_p_or_a=='A'){
              $row_discount_amount=$item_discount_per;
            }
            else{
              $row_discount_amount=$item_total_amount*($item_discount_per/100);
            }
			// $row_discount_amount=$item_total_amount*($item_discount_per/100);
			$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

			$row_final_price_tmp=($item_total_amount+$row_gst_amount-$row_discount_amount);
			$sub_total=$sub_total+$row_final_price_tmp;

			$total_price=$total_price+$item_total_amount;
			$total_discounted_price=$total_discounted_price+$row_discount_amount;
			$total_tax_price=$total_tax_price+$row_gst_amount;		
		}
		// -------------------------------	
		
		
		// =======================================
		// CALCULATE ADDITIONAL PRICE
		$additional_charges_list=array();
		$additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		if(count($additional_charges_list))
		{
			foreach($additional_charges_list as $charge)
			{

				$item_gst_per= $charge->gst;
				$item_sgst_per= ($item_gst_per/2);
				$item_cgst_per= ($item_gst_per/2); 
				$item_is_discount_p_or_a=$charge->is_discount_p_or_a; 
				$item_discount_per=$charge->discount; 
				$item_price= $charge->price;
				$item_qty=1;

				$item_total_amount=($item_price*$item_qty);
				if($item_is_discount_p_or_a=='A'){
                  $row_discount_amount=$item_discount_per;
                }
                else{
                  $row_discount_amount=$item_total_amount*($item_discount_per/100);
                }
				$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

				$row_final_price_tmp=($item_total_amount+$row_gst_amount-$row_discount_amount);
				$sub_total=$sub_total+$row_final_price_tmp; 

				$total_price=$total_price+$item_total_amount;
				$total_tax_price=$total_tax_price+$row_gst_amount;
				$total_discounted_price=$total_discounted_price+$row_discount_amount;	
			}
		}

		// CALCULATE ADDITIONAL PRICE
		// =======================================

		$list=array();
        $list['opportunity_id']=$opportunity_id;
        $list['quotation_id']=$quotation_id;        
        $list['selected_additional_charges']=$additional_charges_list;
        $list['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id);
        $list['currency_list']=$this->lead_model->GetCurrencyList();
        $list['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id);
        $list['unit_type_list']=$this->Product_model->GetUnitList();
    	$html = $this->load->view('admin/quotation/updated_product_selected_list_ajax',$list,TRUE);

    	// $list2['terms']=$list['quotation_data']['terms_log'];
    	// $terms_html = $this->load->view('admin/quotation/updated_terms_log_ajax',$list2,TRUE);

    	// ---------------------------------
    	$data_opportunity_update=array(
		'deal_value'=>$sub_total,
		'modify_date'=>date("Y-m-d H:i:s")
		);	
		$this->Opportunity_model->UpdateLeadOportunity($data_opportunity_update,$opportunity_id);
		// -----------------------------------
    	$result['html'] = $html;
    	$result['total_sale_price'] = number_format($row_final_price,2);
		$result["total_deal_value"]=number_format($sub_total,2);

		$result["total_price"]=number_format($total_price,2);
		$result["total_discount"]=number_format($total_discounted_price,2);
		$result["total_tax"]=number_format($total_tax_price,2);
		$result["grand_total_round_off"]=number_format(round($sub_total),2);
		$result["number_to_word_final_amount"]=number_to_word(round($sub_total));
        echo json_encode($result);
        exit(0); 
	}

	function resort_quotation_product()
    {
		$new_sort=$this->input->get('new_sort');
		$i = 1;
		foreach ($new_sort as $edit_id) 
		{
			$post_data=array(
				"sort"=>$i			
				);
			$this->quotation_model->update_product($post_data,$edit_id);
		    $i++;
		}		
		$status_str='success';		   	 
        $result["status"] = $status_str;
        echo json_encode($result);
        exit(0); 
    }

	function resort_quotation_additional_charges()
    {
		$new_sort=$this->input->get('new_sort');
		$i = 1;
		foreach ($new_sort as $edit_id) 
		{
			$post_data=array(
				"sort"=>$i			
				);
			$this->quotation_model->update_additional_charges($post_data,$edit_id);
		    $i++;
		}		
		$status_str='success';		   	 
        $result["status"] = $status_str;
        echo json_encode($result);
        exit(0); 
    }

	public function rander_quotation_product_ajax()
	{	
		$quotation_id=$this->input->post('quotation_id');
		$opportunity_id=$this->input->post('opportunity_id');
    	// --------------------------------
    	$list=array();
        $product_list=$this->quotation_model->GetQuotationProductList($quotation_id);
        
        $sub_total=0;
        $total_price=0;
        $total_discounted_price=0;
        foreach($product_list as $output)
        {
            $item_gst_per= $output->gst;
            $item_sgst_per= ($item_gst_per/2);
            $item_cgst_per= ($item_gst_per/2); 
            $item_is_discount_p_or_a=$output->is_discount_p_or_a; 
            $item_discount=$output->discount;
            $item_unit= $output->unit; 
            $item_price=($output->price/$item_unit);
            $item_qty=$output->quantity;
            $item_total_amount=($item_price*$item_qty);
            if($item_is_discount_p_or_a=='A'){
                $row_discount_amount=$item_discount;
            }
            else{
                $row_discount_amount=$item_total_amount*($item_discount/100);
            }           
            // $row_discount_amount=$item_total_amount*($item_discount_per/100);
            $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

            $row_final_price_tmp=($item_total_amount+$row_gst_amount-$row_discount_amount);
            $sub_total=$sub_total+$row_final_price_tmp;

            $total_price=$total_price+$item_total_amount;
            $total_discounted_price=$total_discounted_price+$row_discount_amount;
            $total_tax_price=$total_tax_price+$row_gst_amount;      
        }
        
        
        // =======================================
        // CALCULATE ADDITIONAL PRICE
        
        $additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
        
        if(count($additional_charges_list))
        {
            foreach($additional_charges_list as $charge)
            {

                $item_gst_per= $charge->gst;
                $item_sgst_per= ($item_gst_per/2);
                $item_cgst_per= ($item_gst_per/2);  
                $item_discount_per=$charge->discount; 
                $item_price= $charge->price;
                $item_qty=1;

                $item_total_amount=($item_price*$item_qty);
                $row_discount_amount=$item_total_amount*($item_discount_per/100);
                $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

                $row_final_price_tmp=($item_total_amount+$row_gst_amount-$row_discount_amount);
                $sub_total=$sub_total+$row_final_price_tmp; 

                $total_price=$total_price+$item_total_amount;
                $total_tax_price=$total_tax_price+$row_gst_amount;
                $total_discounted_price=$total_discounted_price+$row_discount_amount;   
            }
        }
		
        // CALCULATE ADDITIONAL PRICE
        // =======================================
        
        $list=array();
        $list['opportunity_id']=$opportunity_id;
        $list['quotation_id']=$quotation_id;        
        $list['selected_additional_charges']=$additional_charges_list;
        $list['prod_list']=$product_list;
        $list['currency_list']=$this->lead_model->GetCurrencyList();
        $list['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id);
        $list['unit_type_list']=$this->Product_model->GetUnitList();
        $html = $this->load->view('admin/quotation/updated_product_selected_list_ajax',$list,TRUE);
        
    	$result["status"] = 'success';
    	$result['html'] = $html;
        echo json_encode($result);
        exit(0); 
	}
}
