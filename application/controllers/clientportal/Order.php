<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

	private $api_access_token = '';	 
	function __construct()
	{
		parent :: __construct();
		is_admin_logged_in();
		init_admin_element();		
		$this->load->model(array('order_model','user_model','lead_model','opportunity_model','Product_model','Setting_model','history_model','Email_forwarding_setting_model','Opportunity_model','quotation_model','customer_model','pre_define_comment_model','Order_management_model'));
		// permission checking
		// if(is_permission_available($this->session->userdata('service_wise_menu')[4]['menu_list']['menu_keyword'])===false){ 
		// 	redirect(admin_url().'dashboard', 'refresh');
		// 	exit(0);
		// }
		// end
	}

	public function index()
	{
	} 

	public function po_register()
	{
		if($this->session->userdata('po_back_url_from_detail'))
		{
			$this->session->set_userdata('po_back_url_from_detail','');
		}
		$data=array();
		$this->load->view('admin/order/po_register_view',$data);
	} 
	// AJAX PAGINATION START
	function get_po_register_list_ajax()
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
	    $arg['filter_sort_by']=$this->input->get('filter_sort_by'); 
	   //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->order_model->get_po_register_list_count($arg);
	    // $config['total_rows'] =10;
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
	    
	    
    	$list['rows']=$this->order_model->get_po_register_list($arg);
    	// $list['c2c_credentials']=$this->Setting_model->GetC2cCredentialsDetailsByUser($user_id);
    			
	    $table = '';	
	    if($view_type=='grid')
	    {
	    	$table = $this->load->view('admin/order/po_register_grid_view_ajax',$list,TRUE);
	    }
	    else
	    {
	    	$table = $this->load->view('admin/order/po_register_list_view_ajax',$list,TRUE);
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
	
	function get_po_payment_html()
    {
    	$list=array();
    	$list['divid']=time();
        $list['po_payment_method']=$this->order_model->po_payment_method_list();
        $list['currency_list']=$this->lead_model->GetCurrencyList();
        $html = $this->load->view('admin/order/po_payment_html_ajax',$list,TRUE);
        $result["html"] = $html;
        echo json_encode($result);
        exit(0);        
	}

	function po_payment_terms_post_ajax()
    {
    	$status_str='fail';
    	$msg='Unknown error.';
    	$owp_id=$this->input->post('lead_opportunity_wise_po_id');
    	$po_payment_term_id=$this->input->post('po_payment_term_id');
    	$payment_type = $this->input->post('payment_type');

    	$po_register_info=$this->order_model->get_po_register_detail($owp_id);
    	$lead_id=$po_register_info->lead_id;
    	$lead_opportunity_id=$po_register_info->lead_opportunity_id;
    	$po_currency_type_id=$po_register_info->po_currency_type_id;
    	$po_currency_code=get_value("code","currency","id=".$po_currency_type_id);
    	if($payment_type=='F')
    	{
    	 	$f_payment_mode_id = $this->input->post('f_payment_mode_id');
    		$f_payment_date = $this->input->post('f_payment_date');
    		$f_currency_type = $this->input->post('f_currency_type');
    		$f_amount = $this->input->post('f_amount');
    		$f_narration = $this->input->post('f_narration');
    		
    		$chk_return=$this->order_model->chk_already_exist_lead_opp_wise($owp_id,'P');
			if($chk_return>0)
			{
				$this->order_model->delete_po_payment_terms_lead_opp_wise($owp_id);
				$po_payment_term_id='';
			}


    		if($po_payment_term_id)
    		{
    			$post_data=array(
				'total_amount'=>$f_amount,
				'updated_at'=>date("Y-m-d H:i:s")
				);
				$return=$this->order_model->updatePoPaymentTerms($post_data,$po_payment_term_id);
				
				if($return==true)
				{
					$post_data=array(
					'payment_mode_id'=>$f_payment_mode_id,
					'payment_date'=>date_display_format_to_db_format($f_payment_date),
					'currency_type'=>$f_currency_type,
					'amount'=>$f_amount,
					'narration'=>$f_narration,
					'updated_at'=>date("Y-m-d H:i:s")
					);
					$this->order_model->updatePoPaymentTermDetailByTermId($post_data,$po_payment_term_id);

					// history log					
					$update_by=$this->session->userdata['admin_session_data']['user_id'];
					$ip_addr=$_SERVER['REMOTE_ADDR'];		
					$comment_title=PO_PAYMENT_TERMS_UPDATE;
					$comment='';
					$comment .='Quotation ID: '.$lead_opportunity_id;
					$comment .= '&nbsp;|&nbsp;';
					$comment .='Quote No: '.$po_register_info->q_quote_no.'';
					$comment .= '&nbsp;|&nbsp;';
					$comment .='Payment Type: Full Payment';
					$comment .= '&nbsp;|&nbsp;';
					$comment .='Payment Date: '.$f_payment_date;
					
					$comment .='( '.$po_currency_code.' '.$f_amount.' )';
					if($f_narration)
					{
						$comment .='-'.$f_narration;
					}
					

					$historydata=array(
					'title'=>$comment_title,
					'lead_id'=>$lead_id,
					'comment'=>addslashes($comment),
					'create_date'=>date("Y-m-d H:i:s"),
					'user_id'=>$update_by,
					'ip_address'=>$ip_addr
					);
					$this->history_model->CreateHistory($historydata);

					$status_str='success';
					$msg='PO payment terms successfully saved.';
				}
				else
				{
					$msg='Oops! Recoed not updated.';
				}
    		}
    		else
    		{
    			$post_data=array(
				'lead_opportunity_wise_po_id'=>$owp_id,
				'payment_type'=>$payment_type,
				'total_amount'=>$f_amount,
				'created_at'=>date("Y-m-d H:i:s"),
				'updated_at'=>date("Y-m-d H:i:s")
				);
				// print_r($post_data); die();
				$pt_id=$this->order_model->CreatePoPaymentTerms($post_data);
				
				if($pt_id)
				{
					$po_payment_term_id=$pt_id;
					$post_data=array(
					'po_payment_term_id'=>$po_payment_term_id,
					'payment_mode_id'=>$f_payment_mode_id,
					'payment_date'=>date_display_format_to_db_format($f_payment_date),
					'currency_type'=>$f_currency_type,
					'amount'=>$f_amount,
					'narration'=>$f_narration,
					'created_at'=>date("Y-m-d H:i:s"),
					'updated_at'=>date("Y-m-d H:i:s")
					);
					$this->order_model->CreatePoPaymentTermDetails($post_data);


					// history log					
					$update_by=$this->session->userdata['admin_session_data']['user_id'];
					$ip_addr=$_SERVER['REMOTE_ADDR'];		
					$comment_title=PO_PAYMENT_TERMS_CREATE;
					$comment='';
					$comment .='Quotation ID: '.$lead_opportunity_id;
					$comment .= '&nbsp;|&nbsp;';
					$comment .='Quote No: '.$po_register_info->q_quote_no.'';
					$comment .= '&nbsp;|&nbsp;';
					$comment .='Payment Type: Full Payment';
					$comment .= '&nbsp;|&nbsp;';
					$comment .='Payment Date: '.$f_payment_date;
					$comment .='( '.$po_currency_code.' '.$f_amount.' )';
					if($f_narration)
					{
						$comment .='-'.$f_narration;
					}					

					$historydata=array(
					'title'=>$comment_title,
					'lead_id'=>$lead_id,
					'comment'=>addslashes($comment),
					'create_date'=>date("Y-m-d H:i:s"),
					'user_id'=>$update_by,
					'ip_address'=>$ip_addr
					);
					$this->history_model->CreateHistory($historydata);


					$status_str='success';
					$msg='PO payment terms successfully saved.';
				}
				else
				{
					$msg='Oops! Recoed not saved.';
				}
    		}
    		
    	}
    	else if($payment_type=='P')
    	{
    		$chk_return=$this->order_model->chk_already_exist_lead_opp_wise($owp_id,'F');
			if($chk_return>0)
			{
				$this->order_model->delete_po_payment_terms_lead_opp_wise($owp_id);
				$po_payment_term_id='';
			}

    		$payment_mode_id = $this->input->post('p_payment_mode_id');
    		$payment_date = $this->input->post('p_payment_date');
    		$currency_type = $this->input->post('p_currency_type');
    		$amount = $this->input->post('p_amount');
    		$narration = $this->input->post('p_narration');
    		// print_r($payment_mode_id);
    		if($po_payment_term_id)
    		{
    			$return=$this->order_model->deletePoPaymentTermDetailByTermId($po_payment_term_id); 
				
				if($return==true)
				{
					$total_amount=0;
					$p_str_for_h='';
					
					if(count($payment_mode_id))
					{
						$p_str_for_h .='&nbsp;|&nbsp;';
						for($i=0;$i<count($payment_mode_id);$i++)
						{
							$total_amount=$total_amount+$amount[$i];
							$post_data=array(
							'po_payment_term_id'=>$po_payment_term_id,
							'payment_mode_id'=>$payment_mode_id[$i],
							'payment_date'=>date_display_format_to_db_format($payment_date[$i]),
							'currency_type'=>$currency_type[$i],
							'amount'=>$amount[$i],
							'narration'=>$narration[$i],
							'created_at'=>date("Y-m-d H:i:s"),
							'updated_at'=>date("Y-m-d H:i:s")
							);
							$this->order_model->CreatePoPaymentTermDetails($post_data);

							
							$p_str_for_h .='Payment Date: ';
							$p_str_for_h .=$payment_date[$i];
							$p_str_for_h .='( ';
							$p_str_for_h .=$currency_type[$i].' '.$amount[$i];	
							$p_str_for_h .=' )';
							$p_str_for_h .=($narration[$i])?'-'.$narration[$i]:'';
							$p_str_for_h .='&nbsp;|&nbsp;';
						}
						$p_str_for_h = rtrim($p_str_for_h, "&nbsp;|&nbsp;");
					}				

					$post_data=array(
					'total_amount'=>$total_amount,
					'updated_at'=>date("Y-m-d H:i:s")
					);
					$return=$this->order_model->updatePoPaymentTerms($post_data,$po_payment_term_id);


					// history log					
					$update_by=$this->session->userdata['admin_session_data']['user_id'];
					$ip_addr=$_SERVER['REMOTE_ADDR'];		
					$comment_title=PO_PAYMENT_TERMS_UPDATE;
					$comment='';
					$comment .='Quotation ID: '.$lead_opportunity_id.'';
					$comment .='&nbsp;|&nbsp;';
					$comment .='Quote No: '.$po_register_info->q_quote_no.'';
					$comment .='&nbsp;|&nbsp;';
					$comment .='Payment Type: Part Payment';
					$comment .=''.$p_str_for_h.'';				
					

					$historydata=array(
					'title'=>$comment_title,
					'lead_id'=>$lead_id,
					'comment'=>addslashes($comment),
					'create_date'=>date("Y-m-d H:i:s"),
					'user_id'=>$update_by,
					'ip_address'=>$ip_addr
					);
					$this->history_model->CreateHistory($historydata);
					$status_str='success';
					$msg='PO payment terms successfully saved.';
				}
				else
				{
					$msg='Oops! Recoed not updated.';
				}
    		}
    		else
    		{
    			$post_data=array(
				'lead_opportunity_wise_po_id'=>$owp_id,
				'payment_type'=>$payment_type,
				'created_at'=>date("Y-m-d H:i:s"),
				'updated_at'=>date("Y-m-d H:i:s")
				);
				// print_r($post_data); die();
				$pt_id=$this->order_model->CreatePoPaymentTerms($post_data);
				
				if($pt_id)
				{
					$po_payment_term_id=$pt_id;
					$total_amount=0;
					$p_str_for_h='';
					
					if(count($payment_mode_id))
					{
						$p_str_for_h .='&nbsp;|&nbsp;';
						for($i=0;$i<count($payment_mode_id);$i++)
						{
							$total_amount=$total_amount+$amount[$i];
							$post_data=array(
							'po_payment_term_id'=>$po_payment_term_id,
							'payment_mode_id'=>$payment_mode_id[$i],
							'payment_date'=>date_display_format_to_db_format($payment_date[$i]),
							'currency_type'=>$currency_type[$i],
							'amount'=>$amount[$i],
							'narration'=>$narration[$i],
							'created_at'=>date("Y-m-d H:i:s"),
							'updated_at'=>date("Y-m-d H:i:s")
							);
							$this->order_model->CreatePoPaymentTermDetails($post_data);

							
							$p_str_for_h .='Payment Date: '.$payment_date[$i];
							$p_str_for_h .='( '.$currency_type[$i].' '.$amount[$i].' )';
							$p_str_for_h .=($narration[$i])?'-'.$narration[$i]:'';
							$p_str_for_h .='&nbsp;|&nbsp;';
						}
						$p_str_for_h = rtrim($p_str_for_h, "&nbsp;|&nbsp;");
					}
					
					$post_data=array(
					'total_amount'=>$total_amount,
					'updated_at'=>date("Y-m-d H:i:s")
					);
					$return=$this->order_model->updatePoPaymentTerms($post_data,$po_payment_term_id);


					// history log					
					$update_by=$this->session->userdata['admin_session_data']['user_id'];
					$ip_addr=$_SERVER['REMOTE_ADDR'];		
					$comment_title=PO_PAYMENT_TERMS_CREATE;
					$comment='';
					$comment .='Quotation ID: '.$lead_opportunity_id.'';
					$comment .='&nbsp;|&nbsp;';
					$comment .='Quote No: '.$po_register_info->q_quote_no.'';
					$comment .='&nbsp;|&nbsp;';
					$comment .='Payment Type: Part Payment';
					$comment .=''.$p_str_for_h.'';	

					

					$historydata=array(
					'title'=>$comment_title,
					'lead_id'=>$lead_id,
					'comment'=>addslashes($comment),
					'create_date'=>date("Y-m-d H:i:s"),
					'user_id'=>$update_by,
					'ip_address'=>$ip_addr
					);
					$this->history_model->CreateHistory($historydata);



					$status_str='success';
					$msg='PO payment terms successfully saved.';
				}
				else
				{
					$msg='Oops! Recoed not saved.';
				}
    		}
    	}        
		
		$lowp=$owp_id;
		// -----------------------------------------
		// payment received update in payment terms
		$pt_list=$this->order_model->get_payment_terms_by_lowp($lowp);
    	$pr_total_amount=$this->order_model->get_total_amount_received_by_lowp($lowp);
    	$total_amount_received=$pr_total_amount->total_amount_received;
    	
    	
    	if($pt_list)
    	{
    		$t_id=$pt_list[0]->t_id;
	    	$total_amount=$pt_list[0]->total_amount;
	    	if($t_id>0)
    		{
    			$pt_post=array(
					'total_payment_received'=>$total_amount_received,
					'total_balance_payment'=>($total_amount-$total_amount_received)
					);	    				
    			$this->order_model->updatePoPaymentTerms($pt_post,$t_id);
    		}
    		$i=0;
    		foreach($pt_list AS $pt)
    		{
    			if($i==0)
    			{
    				$u_post=array(
					'payment_received'=>'0',
					'balance_payment'=>'0'
					);
    				
    				$this->order_model->updatePoPaymentTermDetailByTermId($u_post,$pt->t_id);
    			}	    			
    			$td_id=$pt->td_id; 
    			$amount=$pt->amount;
    			$payment_received=$pt->payment_received;
    			$balance_payment=$pt->balance_payment;
    			
    			if(($amount<=$total_amount_received) && $total_amount_received>0)
    			{
    				$new_payment_received=$amount;
    				$new_balance_payment=0;
    				$total_amount_received=($total_amount_received-$amount);

    				

    				$post=array(
    							'payment_received'=>$new_payment_received,
    							'balance_payment'=>$new_balance_payment
    							);
    				
    				$this->order_model->updatePoPaymentTermDetailById($post,$td_id);	
    			}
    			else if(($amount>$total_amount_received) && $total_amount_received>0)
    			{
    				$new_payment_received=$total_amount_received;
    				$new_balance_payment=($amount-$total_amount_received); 
    				$total_amount_received=0;

    				$post=array(
    							'payment_received'=>$new_payment_received,
    							'balance_payment'=>$new_balance_payment
    							);
    				
    				$this->order_model->updatePoPaymentTermDetailById($post,$td_id);
    			}
    			else
    			{
    				$post=array(
    							'balance_payment'=>$amount
    							);
    				
    				$this->order_model->updatePoPaymentTermDetailById($post,$td_id);
    			}
    			$i++;			
    		}
    	}
    	// payment received update in payment terms
    	// -----------------------------------------
        
        $result['status']=$status_str;
        $result['po_payment_term_id']=$po_payment_term_id;
        $result['msg']=$msg;
        echo json_encode($result);
        exit(0);
    }

    function po_pro_forma_invoice_post_ajax()
    {	
    	$status_str='fail';
    	$msg='Unknown error.';
    	$owp_id=$this->input->post('lead_opportunity_wise_po_id');
    	$po_pro_forma_invoice_id=$this->input->post('po_pro_forma_invoice_id');
    	$po_invoice_id=$this->input->post('po_invoice_id');
    	$proforma_type=$this->input->post('proforma_type');

    	$pfi_pro_forma_no = $this->input->post('pfi_pro_forma_no');
    	$pfi_pro_forma_date = $this->input->post('pfi_pro_forma_date');
    	$pfi_due_date = $this->input->post('pfi_due_date');
    	$pfi_expected_delivery_date = $this->input->post('pfi_expected_delivery_date');
    	// $pfi_bill_to = $this->input->post('pfi_bill_to');
    	// $pfi_bill_from = $this->input->post('pfi_bill_from');
    	// $pfi_ship_to = $this->input->post('pfi_ship_to');
    	$pfi_terms_conditions = $this->input->post('pfi_terms_conditions');

    	$po_register_info=$this->order_model->get_po_register_detail($owp_id);
    	$lead_id=$po_register_info->lead_id;

    	$pro_forma_inv_mode=$this->order_model->get_pro_forma_inv_mode($po_pro_forma_invoice_id);  

    	$pro_forma_inv_info=$this->order_model->get_pro_forma_invoice($po_pro_forma_invoice_id);
    	$currency_type_code=$pro_forma_inv_info->currency_type;
    	// $total_pro_forma_inv_amount=$pro_forma_inv_info->total_pro_forma_inv_amount;


    	$po_custom_proforma_file_name='';
		$this->load->library('upload', '');
		if($_FILES['po_custom_proforma']['name'] != "")
		{
			$config2['upload_path'] = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/";
			$config2['allowed_types'] = "gif|jpg|jpeg|png|pdf|doc|docx|txt|xlsx";
			$config2['max_size'] = '1000000'; //KB
			$config2['overwrite'] = FALSE; 
			$config2['encrypt_name'] = TRUE; 
			$this->upload->initialize($config2);
			if (!$this->upload->do_upload('po_custom_proforma'))
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
			    $po_custom_proforma_file_name=$file_data['upload_data']['file_name'];
			    $post_data=array(
	    		'po_custom_proforma'=>$po_custom_proforma_file_name,
				'updated_at'=>date("Y-m-d H:i:s")
				);
				$this->order_model->updatePoFormaInvoice($post_data,$po_pro_forma_invoice_id);
			}
		}

		if($pro_forma_inv_mode=='update')
    	{
    		$post_data=array(
    		'proforma_type'=>$proforma_type,
			'pro_forma_no'=>$pfi_pro_forma_no,
			'pro_forma_date'=>date_display_format_to_db_format($pfi_pro_forma_date),
			'due_date'=>date_display_format_to_db_format($pfi_due_date),
			'expected_delivery_date'=>date_display_format_to_db_format($pfi_expected_delivery_date),
			'updated_at'=>date("Y-m-d H:i:s")
			);

			// history log					
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$update_by_name=get_value("name","user","id=".$update_by);
			$ip_addr=$_SERVER['REMOTE_ADDR'];		
			$comment_title=PO_PRO_FORMA_INVOICE_UPDATE;
			$comment='';
			$comment .='Proforma No. : #'.$pfi_pro_forma_no;
			$comment .='&nbsp;|&nbsp;';
			$comment .='Proforma Date: '.$pfi_pro_forma_date;
			$comment .='&nbsp;|&nbsp;';
			$comment .='Due Date: '.$pfi_due_date;
			$comment .='&nbsp;|&nbsp;';
			$comment .='Updated By: '.$update_by_name;
			
			// $comment .='Total Pro Forma Invoice Amount: '.$currency_type_code.' '.$total_pro_forma_inv_amount;		

			$historydata=array(
			'title'=>$comment_title,
			'lead_id'=>$lead_id,
			'comment'=>addslashes($comment),
			'create_date'=>date("Y-m-d H:i:s"),
			'user_id'=>$update_by,
			'ip_address'=>$ip_addr
			);
			$this->history_model->CreateHistory($historydata);
			// =========================
			// HISTORY CREATE ORDER MANAGEMNT
			$lead_opportunity_id=get_value("lead_opportunity_id","lead_opportunity_wise_po","id=".$owp_id);
			$source_id=get_value("source_id","lead","id=".$lead_id);	
			$historydata=array();
			$historydata=array(
				'lead_id'=>$lead_id,
				'lead_opportunity_id'=>$lead_opportunity_id,
				'lowp'=>$owp_id,
				'source_id'=>$source_id,
				'po_pi_id'=>$po_pro_forma_invoice_id,
				'title'=>$comment_title,						
				'comment'=>addslashes($comment),
				'updated_by'=>$update_by,
				'created_at'=>date("Y-m-d H:i:s"),						
				'ip_address'=>$ip_addr
				);
			$this->Order_management_model->CreateHistory($historydata);
			// HISTORY CREATE ORDER MANAGEMNT
			// =========================
			$msg='Proforma invoice successfully updated.';

    	}
    	else
    	{
    		$post_data=array(
    		'proforma_type'=>$proforma_type,
			'pro_forma_no'=>$pfi_pro_forma_no,
			'pro_forma_date'=>date_display_format_to_db_format($pfi_pro_forma_date),
			'due_date'=>date_display_format_to_db_format($pfi_due_date),
			'expected_delivery_date'=>date_display_format_to_db_format($pfi_expected_delivery_date),		
			'created_at'=>date("Y-m-d H:i:s"),
			'updated_at'=>date("Y-m-d H:i:s")
			);

			// history log					
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$update_by_name=get_value("name","user","id=".$update_by);
			$ip_addr=$_SERVER['REMOTE_ADDR'];		
			$comment_title=PO_PRO_FORMA_INVOICE_CREATE;
			$comment='';
			$comment .='Proforma No. : #'.$pfi_pro_forma_no;
			$comment .='&nbsp;|&nbsp;';
			$comment .='Proforma Date: '.$pfi_pro_forma_date;
			$comment .='&nbsp;|&nbsp;';
			$comment .='Due Date: '.$pfi_due_date;
			$comment .='&nbsp;|&nbsp;';
			$comment .='Created By: '.$update_by_name;
			
			// $comment .='Total Pro Forma Invoice Amount: '.$currency_type_code.' '.$total_pro_forma_inv_amount;	

			$historydata=array(
			'title'=>$comment_title,
			'lead_id'=>$lead_id,
			'comment'=>addslashes($comment),
			'create_date'=>date("Y-m-d H:i:s"),
			'user_id'=>$update_by,
			'ip_address'=>$ip_addr
			);
			$this->history_model->CreateHistory($historydata);
			// =========================
			// HISTORY CREATE ORDER MANAGEMNT
			$lead_opportunity_id=get_value("lead_opportunity_id","lead_opportunity_wise_po","id=".$owp_id);
			$source_id=get_value("source_id","lead","id=".$lead_id);	
			$historydata=array();
			$historydata=array(
				'lead_id'=>$lead_id,
				'lead_opportunity_id'=>$lead_opportunity_id,
				'lowp'=>$owp_id,
				'source_id'=>$source_id,
				'po_pi_id'=>$po_pro_forma_invoice_id,
				'title'=>$comment_title,						
				'comment'=>addslashes($comment),
				'updated_by'=>$update_by,
				'created_at'=>date("Y-m-d H:i:s"),						
				'ip_address'=>$ip_addr
				);
			$this->Order_management_model->CreateHistory($historydata);
			// HISTORY CREATE ORDER MANAGEMNT
			// =========================

			$msg='Proforma invoice successfully saved.';
    	}

    	$this->order_model->updatePoFormaInvoice($post_data,$po_pro_forma_invoice_id);

    	// --------------------------------
    	// --------------------------------
    	$inv_mode=$this->order_model->get_inv_mode($po_invoice_id);
    	if($inv_mode=='insert' && $po_invoice_id>0)
    	{
    		$inv_post=array(
    			'bill_from'=>$pro_forma_inv_info->bill_from,
    			'bill_to'=>$pro_forma_inv_info->bill_to,
    			'ship_to'=>$pro_forma_inv_info->ship_to,
    			'terms_conditions'=>$pro_forma_inv_info->terms_conditions,
    			'additional_note'=>$pro_forma_inv_info->additional_note,
    			'bank_detail_1'=>$pro_forma_inv_info->bank_detail_1,
    			'bank_detail_2'=>$pro_forma_inv_info->bank_detail_2,
    			'is_digital_signature_checked'=>$pro_forma_inv_info->is_digital_signature_checked,
    			'name_of_authorised_signature'=>$pro_forma_inv_info->name_of_authorised_signature,
    			'thanks_and_regards'=>$pro_forma_inv_info->thanks_and_regards,
    			'currency_type'=>$pro_forma_inv_info->currency_type,
    			'total_inv_amount'=>$pro_forma_inv_info->total_pro_forma_inv_amount
    		);

    		$this->order_model->updateInvoice($inv_post,$po_invoice_id);

    		$prod_list=$this->order_model->GetProFormaInvoiceProductList($owp_id);
    		$additional_charges_list=$this->order_model->GetProFormaInvoiceAdditionalCharges($owp_id);
    		
    		if(count($prod_list))
			{
				$this->order_model->DeleteInvoiceProductByInvId($po_invoice_id);
				foreach($prod_list AS $p)
				{
					// --------------
					// invoice product
					$p_data2=array(
						'po_invoice_id'=>$po_invoice_id,
						'product_id'=>$p->product_id,
						'product_name'=>$p->product_name,
						'product_sku'=>$p->product_sku,
						'unit'=>$p->unit,
						'unit_type'=>$p->unit_type,
						'quantity'=>$p->quantity,
						'price'=>$p->price,
						'discount'=>$p->discount,
						'is_discount_p_or_a'=>$p->is_discount_p_or_a,
						'gst'=>$p->gst,
						'created_at'=>date('Y-m-d H:i:s')
						);
					$this->order_model->CreateInvoiceProduct($p_data2);
				}
			}

			if(count($additional_charges_list))
			{
				$this->order_model->DeleteInvoiceAdditionalChargesByInvId($po_invoice_id);
				foreach($additional_charges_list AS $ac)
				{
					// --------------
					// invoice product
					$p_data2=array(
						'po_invoice_id'=>$po_invoice_id,
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
		// --------------------------------
    	// --------------------------------
        

        $post_proforma_info=$this->order_model->get_pro_forma_invoice($po_pro_forma_invoice_id);        
        if($proforma_type=='S' && $post_proforma_info->po_custom_proforma!='')
        {
        	$post_data=array(
	    		'po_custom_proforma'=>'',
				'updated_at'=>date("Y-m-d H:i:s")
				);
			$this->order_model->updatePoFormaInvoice($post_data,$po_pro_forma_invoice_id);
        }
        $po_custom_proforma='';
        if($proforma_type=='C' && $post_proforma_info->po_custom_proforma!='')
        {
        	$company_name_tmp=($po_register_info->cust_company_name)?$po_register_info->cust_company_name:$po_register_info->cust_contact_person;

            $f_name="PROFORMA INVOICE ".$pfi_pro_forma_no."-".date_format(date_create(date('Y-m-d')),'M y')."-".strtoupper($company_name_tmp);

        	$po_custom_proforma=base_url().$this->session->userdata['admin_session_data']['lms_url']."/order/download_po/".base64_encode("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/".$post_proforma_info->po_custom_proforma.'#'.$f_name);

        	$result['po_custom_proforma_file_name']=$post_proforma_info->po_custom_proforma;
        }
        else
        {
        	$po_custom_proforma=base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/download_pro_forma_inv/'.$owp_id);
        	$result['po_custom_proforma_file_name']='';
        }

        $result['po_custom_proforma']=$po_custom_proforma;
        $result['po_proforma_id']=$po_pro_forma_invoice_id;
        $result['status']='success';
        $result['msg']=$msg;
        $result['lowp']=$owp_id;
        echo json_encode($result);
        exit(0);
    }

    public function update_pfi_product_ajax()
	{
		$pid=$this->input->post('pid');
		$id=$this->input->post('id');
		$field=$this->input->post('field');
		$value=$this->input->post('value');	
		$pfi_id=$this->input->post('pfi_id');		
		$lowp=$this->input->post('lowp');
		// echo 'ID-'.$id.'/pfiID-'.$pfi_id.'/lowp-'.$lowp;die();
		$data_post=array(						
						$field=>$value
						);
		$update_data=$this->order_model->update_product($data_post,$id);

		if($update_data==true)
        	$result["status"] = 'success';
        else
        	$result["status"] = 'fail';
        

        $list=array();
        // $product_list=$this->quotation_model->GetQuotationProductList($quotation_id);
        $product_list=$this->order_model->GetProFormaInvoiceProductList($lowp);

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
			$item_price= ($output->price/$item_unit);
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
		// -------------------------------
		
		// $product=$this->quotation_model->GetQuotationProduct($quotation_id,$pid);
		$product=$this->order_model->GetProFormaInvoiceProduct($id);
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
		// $row_discount_amount=$item_total_amount*($item_discount_per/100);
		$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);
		$row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
		
		// =======================================
		// CALCULATE ADDITIONAL PRICE
		
		// $additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		$additional_charges_list=$this->order_model->GetProFormaInvoiceAdditionalCharges($lowp);
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

		// ---------------------------------
		$data_update=array(
								'total_pro_forma_inv_amount'=>$sub_total,
								'updated_at'=>date("Y-m-d H:i:s")
								);	
		$this->order_model->updatePoFormaInvoice($data_update,$pfi_id);
		// ------------------------------------
		$result['total_sale_price'] = number_format($row_final_price,2);
		$result["total_deal_value"]=number_format($sub_total,2);

		$result["total_price"]=number_format($total_price,2);
		$result["total_discount"]=number_format($total_discounted_price,2);
		$result["total_tax"]=$total_tax_price;
		$result["grand_total_round_off"]=number_format(round($sub_total),2);
		$result["number_to_word_final_amount"]=number_to_word(round($sub_total));
    	// $result["opportunity_id"] =$opportunity_id;
    	$result['pid']=$pid;
        echo json_encode($result);
        exit(0); 
	}

	public function del_pfi_product_ajax()
	{	
		$id=$this->input->post('id');
		$pfi_id=$this->input->post('pfi_id');
		$lowp=$this->input->post('lowp');
		$pid=$this->input->post('pid');
		
		$del_data=$this->order_model->DeleteProFormaInvoiceProduct($id);

		if($del_data==true)
        	$result["status"] = 'success';
        else
        	$result["status"] = 'fail';

        $list=array();
        // $product_list=$this->quotation_model->GetQuotationProductList($quotation_id);
        $product_list=$this->order_model->GetProFormaInvoiceProductList($lowp);

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
		// -------------------------------
		
		// $product=$this->quotation_model->GetQuotationProduct($quotation_id,$pid);
		$product=$this->order_model->GetProFormaInvoiceProduct($id);
		$item_gst_per= $product->gst;
		$item_sgst_per= ($item_gst_per/2);
		$item_cgst_per= ($item_gst_per/2); 
		$item_is_discount_p_or_a=$product->is_discount_p_or_a; 
		$item_discount_per=$product->discount; 
		$item_price= $product->price;
		$item_qty=$product->unit;
		$item_total_amount=($item_price*$item_qty);
		if($item_is_discount_p_or_a=='A'){
			$row_discount_amount=$item_discount_per;
		}
		else{
			$row_discount_amount=$item_total_amount*($item_discount_per/100);
		}	
		// $row_discount_amount=$item_total_amount*($item_discount_per/100);
		$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);
		$row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
		
		// =======================================
		// CALCULATE ADDITIONAL PRICE
		
		// $additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		$additional_charges_list=$this->order_model->GetProFormaInvoiceAdditionalCharges($lowp);
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
		$data_update=array(
						'total_pro_forma_inv_amount'=>$sub_total,
						'updated_at'=>date("Y-m-d H:i:s")
						);	
		$this->order_model->updatePoFormaInvoice($data_update,$pfi_id);
		// ------------------------------------

		$result['total_sale_price'] = number_format($row_final_price,2);
		$result["total_deal_value"]=number_format($sub_total,2);

		$result["total_price"]=number_format($total_price,2);
		$result["total_discount"]=number_format($total_discounted_price,2);
		$result["total_tax"]=$total_tax_price;
		$result["grand_total_round_off"]=number_format(round($sub_total),2);
		$result["number_to_word_final_amount"]=number_to_word(round($sub_total));
    	// $result["opportunity_id"] =$opportunity_id;
    	$result['pid']=$pid;
        echo json_encode($result);
        exit(0); 
	}

	public function update_pfi_additional_charges_ajax()
	{
		$pid=$this->input->post('pid');
		$id=$this->input->post('id');
		$field=$this->input->post('field');
		$value=$this->input->post('value');	
		$pfi_id=$this->input->post('pfi_id');		
		$lowp=$this->input->post('lowp');
		// echo 'ID-'.$id.'/pfiID-'.$pfi_id.'/lowp-'.$lowp;die();
		$data_post=array(						
						$field=>$value
						);
		$update_data=$this->order_model->update_additional_charges($data_post,$id);

		if($update_data==true)
        	$result["status"] = 'success';
        else
        	$result["status"] = 'fail';
        

        $list=array();
        // $product_list=$this->quotation_model->GetQuotationProductList($quotation_id);
        $product_list=$this->order_model->GetProFormaInvoiceProductList($lowp);

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
		// -------------------------------
		
		
		$product=$this->order_model->GetProFormaInvoiceAdditionalChargesRow($id);
		// print_r($product); die();
		$item_gst_per= $product->gst;
		$item_sgst_per= ($item_gst_per/2);
		$item_cgst_per= ($item_gst_per/2); 
		$item_is_discount_p_or_a=$product->is_discount_p_or_a; 
		$item_discount_per=$product->discount; 
		$item_price= $product->price;		
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
		$row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
		
		// =======================================
		// CALCULATE ADDITIONAL PRICE
		
		// $additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		$additional_charges_list=$this->order_model->GetProFormaInvoiceAdditionalCharges($lowp);
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

		// ---------------------------------
		$data_update=array(
								'total_pro_forma_inv_amount'=>$sub_total,
								'updated_at'=>date("Y-m-d H:i:s")
								);	
		$this->order_model->updatePoFormaInvoice($data_update,$pfi_id);
		// ------------------------------------
		$result['total_sale_price'] = number_format($row_final_price,2);
		$result["total_deal_value"]=number_format($sub_total,2);

		$result["total_price"]=number_format($total_price,2);
		$result["total_discount"]=number_format($total_discounted_price,2);
		$result["total_tax"]=$total_tax_price;
		$result["grand_total_round_off"]=number_format(round($sub_total),2);
		$result["number_to_word_final_amount"]=number_to_word(round($sub_total));
    	// $result["opportunity_id"] =$opportunity_id;
    	$result['pid']=$pid;
        echo json_encode($result);
        exit(0); 
	}

	public function del_pfi_additional_charges_ajax()
	{	
		$id=$this->input->post('id');
		$pfi_id=$this->input->post('pfi_id');
		$lowp=$this->input->post('lowp');
		$pid=$this->input->post('pid');
		
		$del_data=$this->order_model->DeleteProFormaInvoiceAdditionalCharges($id);

		if($del_data==true)
        	$result["status"] = 'success';
        else
        	$result["status"] = 'fail';

        $list=array();
        // $product_list=$this->quotation_model->GetQuotationProductList($quotation_id);
        $product_list=$this->order_model->GetProFormaInvoiceProductList($lowp);

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
		// -------------------------------
		
		// $product=$this->quotation_model->GetQuotationProduct($quotation_id,$pid);
		$product=$this->order_model->GetProFormaInvoiceAdditionalChargesRow($id);
		$item_gst_per= $product->gst;
		$item_sgst_per= ($item_gst_per/2);
		$item_cgst_per= ($item_gst_per/2); 
		$item_is_discount_p_or_a=$product->is_discount_p_or_a; 
		$item_discount_per=$product->discount; 
		$item_price= $product->price;
		$item_qty=$product->unit;
		$item_total_amount=($item_price*$item_qty);
		if($item_is_discount_p_or_a=='A'){
			$row_discount_amount=$item_discount_per;
		}
		else{
			$row_discount_amount=$item_total_amount*($item_discount_per/100);
		}	
		// $row_discount_amount=$item_total_amount*($item_discount_per/100);
		$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);
		$row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
		
		// =======================================
		// CALCULATE ADDITIONAL PRICE
		
		// $additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		$additional_charges_list=$this->order_model->GetProFormaInvoiceAdditionalCharges($lowp);
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
		$data_update=array(
						'total_pro_forma_inv_amount'=>$sub_total,
						'updated_at'=>date("Y-m-d H:i:s")
						);	
		$this->order_model->updatePoFormaInvoice($data_update,$pfi_id);
		// ------------------------------------

		$result['total_sale_price'] = number_format($row_final_price,2);
		$result["total_deal_value"]=number_format($sub_total,2);

		$result["total_price"]=number_format($total_price,2);
		$result["total_discount"]=number_format($total_discounted_price,2);
		$result["total_tax"]=$total_tax_price;
		$result["grand_total_round_off"]=number_format(round($sub_total),2);
		$result["number_to_word_final_amount"]=number_to_word(round($sub_total));
    	// $result["opportunity_id"] =$opportunity_id;
    	// $result['pid']=$pid;
        echo json_encode($result);
        exit(0); 
	}

	function addpopoprod_ajax()
	{
		$lead_opportunity_wise_po_id=$this->input->post('lead_opportunity_wise_po_id');
		$po_pro_forma_invoice_id=$this->input->post('po_pro_forma_invoice_id');
		$po_invoice_id=$this->input->post('po_invoice_id');
		$is_pfi_or_inv=$this->input->post('is_pfi_or_inv');
		$po_selected_prod_id=$this->input->post('po_selected_prod_id');
		if($po_selected_prod_id)
		{
			$is_discount_p_or_a='P';
			if($is_pfi_or_inv=='pfi')
			{
				$tmp_pro=$this->order_model->GetProFormaInvoiceProductList($lead_opportunity_wise_po_id);
				if(count($tmp_pro))
				{
					$is_discount_p_or_a=$tmp_pro[0]->is_discount_p_or_a;
				}
			}
			else if($is_pfi_or_inv=='inv')
			{
				$tmp_pro=$this->order_model->GetInvoiceProductList($lead_opportunity_wise_po_id);
				if(count($tmp_pro))
				{
					$is_discount_p_or_a=$tmp_pro[0]->is_discount_p_or_a;
				}
				
			}
			
			$prod_arr=explode(",", $po_selected_prod_id);
			$i=0;
			foreach($prod_arr as $prod_data)
			{				
				$product_data[$i]=$this->Product_model->GetProductListById($prod_data);
				
				if($is_pfi_or_inv=='pfi')
				{
					$product_name_tmp=$product_data[$i]->name;
					if($product_data[$i]->hsn_code)
					{
						$product_name_tmp .='<br><b>HSN Code:</b> '.$product_data[$i]->hsn_code;
					}
					if($product_data[$i]->code)
					{
						$product_name_tmp .='<br><b>Product Code:</b> '.$product_data[$i]->code;
					}
					if($product_data[$i]->description)
					{
						$product_name_tmp .='<br><br>'.$product_data[$i]->description;
					}
					$post_data=array(
						'po_pro_forma_invoice_id'=>$po_pro_forma_invoice_id,
						'product_id'=>$prod_data,
						'product_name'=>$product_name_tmp,
						'product_sku'=>$product_data[$i]->code,
						'unit'=>$product_data[$i]->unit,
						'unit_type'=>$product_data[$i]->unit_type_name,
						'price'=>$product_data[$i]->price,
						'is_discount_p_or_a'=>$is_discount_p_or_a,
						'gst'=>$product_data[$i]->gst_percentage,
						'created_at'=>date('Y-m-d H:i:s')
					);
					$this->order_model->CreatePoProFormaInvoiceProduct($post_data);
				}
				else if($is_pfi_or_inv=='inv')
				{
					$product_name_tmp=$product_data[$i]->name;
					if($product_data[$i]->hsn_code)
					{
						$product_name_tmp .='<br><b>HSN Code:</b> '.$product_data[$i]->hsn_code;
					}
					if($product_data[$i]->code)
					{
						$product_name_tmp .='<br><b>Product Code:</b> '.$product_data[$i]->code;
					}
					if($product_data[$i]->description)
					{
						$product_name_tmp .='<br><br>'.$product_data[$i]->description;
					}
					$post_data=array(
						'po_invoice_id'=>$po_invoice_id,
						'product_id'=>$prod_data,
						'product_name'=>$product_name_tmp,
						'product_sku'=>$product_data[$i]->code,
						'unit'=>$product_data[$i]->unit,
						'unit_type'=>$product_data[$i]->unit_type_name,
						'price'=>$product_data[$i]->price,
						'is_discount_p_or_a'=>$is_discount_p_or_a,
						'gst'=>$product_data[$i]->gst_percentage,
						'created_at'=>date('Y-m-d H:i:s')
					);
					$this->order_model->CreateInvoiceProduct($post_data);
				}
							
			}
		}

	}

	public function get_additional_charges_checkbox_view_ajax()
	{
		$data=array();
		$po_pro_forma_invoice_id=$this->input->post('po_pro_forma_invoice_id');
		$po_invoice_id=$this->input->post('po_invoice_id');
		$is_pfi_or_inv=$this->input->post('is_pfi_or_inv');
		
		$arg=array(
				'is_pfi_or_inv'=>$is_pfi_or_inv,
				'po_pro_forma_invoice_id'=>$po_pro_forma_invoice_id,
				'po_invoice_id'=>$po_invoice_id
			);
		// print_r($arg); die();
		$data['additional_charges_list']=$this->opportunity_model->get_additional_charges_except_po($arg);
		
		$this->load->view('admin/order/po_additional_charges_checkbox_view_ajax',$data);		
	}

	public function selected_additional_charges_added_ajax()
	{
		$data=NULL;
		$additional_charges=$this->input->post('additional_charges');
		$lead_opportunity_wise_po_id=$this->input->post('lead_opportunity_wise_po_id');
		$po_pro_forma_invoice_id=$this->input->post('po_pro_forma_invoice_id');
		$po_invoice_id=$this->input->post('po_invoice_id');
		$is_pfi_or_inv=$this->input->post('is_pfi_or_inv');

		$is_discount_p_or_a='P';
		if($is_pfi_or_inv=='pfi')
		{
			$tmp_pro=$this->order_model->GetProFormaInvoiceAdditionalCharges($lead_opportunity_wise_po_id);
			if(count($tmp_pro))
			{
				$is_discount_p_or_a=$tmp_pro[0]->is_discount_p_or_a;
			}
		}
		else if($is_pfi_or_inv=='inv')
		{
			$tmp_pro=$this->order_model->GetInvoiceAdditionalCharges($lead_opportunity_wise_po_id);
			if(count($tmp_pro))
			{
				$is_discount_p_or_a=$tmp_pro[0]->is_discount_p_or_a;
			}
			
		}


		$additional_charges_arr=explode(',',$additional_charges);		
		foreach($additional_charges_arr as $additional_charges_id)
		{			
			if($additional_charges_id!='')
			{				
				
				$tmp_data=$this->opportunity_model->get_additional_charges_by_id($additional_charges_id);

				if($is_pfi_or_inv=='pfi')
				{
					$data_post=array(
					'po_pro_forma_invoice_id'=>$po_pro_forma_invoice_id,
					'additional_charge_id'=>$additional_charges_id,
					'additional_charge_name'=>$tmp_data->name,
					'is_discount_p_or_a'=>$is_discount_p_or_a,
					'created_at'=>date("Y-m-d H:i:s")
								);
					// print_r($data_post);die();
					$this->order_model->CreatePoProFormaInvoiceAdditionalCharges($data_post);
				}
				else
				{
					$data_post=array(
					'po_invoice_id'=>$po_invoice_id,
					'additional_charge_id'=>$additional_charges_id,
					'additional_charge_name'=>$tmp_data->name,
					'is_discount_p_or_a'=>$is_discount_p_or_a,
					'created_at'=>date("Y-m-d H:i:s")
								);
					// print_r($data_post);die();
					$this->order_model->CreateInvoiceAdditionalCharges($data_post);
				}
				
			}			
		}
		
    	$result['msg'] = 'success';
        echo json_encode($result);
        exit(0);
        
	}

	function new_row_added_ajax()
	{
		$lead_opportunity_wise_po_id=$this->input->post('lead_opportunity_wise_po_id');
		$po_pro_forma_invoice_id=$this->input->post('po_pro_forma_invoice_id');
		$po_invoice_id=$this->input->post('po_invoice_id');
		$is_pfi_or_inv=$this->input->post('is_pfi_or_inv');
		$is_discount_p_or_a='P';
		if($is_pfi_or_inv=='pfi')
		{
			$tmp_pro=$this->order_model->GetProFormaInvoiceProductList($lead_opportunity_wise_po_id);
			if(count($tmp_pro))
			{
				$is_discount_p_or_a=$tmp_pro[0]->is_discount_p_or_a;
			}

			$post_data=array(
			'po_pro_forma_invoice_id'=>$po_pro_forma_invoice_id,
			'unit'=>1,
			'is_discount_p_or_a'=>$is_discount_p_or_a,
			'created_at'=>date('Y-m-d H:i:s')
			);
			// print_r($post_data); die();
			$this->order_model->CreatePoProFormaInvoiceProduct($post_data);
		}
		else if($is_pfi_or_inv=='inv')
		{
			$tmp_pro=$this->order_model->GetInvoiceProductList($lead_opportunity_wise_po_id);
			if(count($tmp_pro))
			{
				$is_discount_p_or_a=$tmp_pro[0]->is_discount_p_or_a;
			}

			$post_data=array(
			'po_invoice_id'=>$po_invoice_id,
			'unit'=>1,
			'is_discount_p_or_a'=>$is_discount_p_or_a,
			'created_at'=>date('Y-m-d H:i:s')
			);
			// print_r($post_data); die();
			$this->order_model->CreateInvoiceProduct($post_data);
			
		}

		$result['msg'] = 'success';
        echo json_encode($result);
        exit(0);
	}

	public function pfi_product_discount_type_update_ajax()
	{	
		$lowp=$this->input->post('lowp');
		$po_pro_forma_invoice_id=$this->input->post('po_pro_forma_invoice_id');
		$is_discount_p_or_a=$this->input->post('is_discount_p_or_a');

		$postData=array();
		$postData=array('is_discount_p_or_a'=>$is_discount_p_or_a);
		$r=$this->order_model->UpdateProFormaInvProdByProFormaInvId($postData,$po_pro_forma_invoice_id);

		$postData=array();
		$postData=array('is_discount_p_or_a'=>$is_discount_p_or_a);
		$r2=$this->order_model->UpdateProFormaInvAdditionalChargesByProFormaInvId($postData,$po_pro_forma_invoice_id);


        

    	$list=array();
    	$po_register_info=$this->order_model->get_po_register_detail($lowp);
    	$list['po_pro_forma_inv_info']=$this->order_model->get_po_pro_forma_invoice_detail_lowpo_wise($lowp);

    	$list['prod_list']=$this->order_model->GetProFormaInvoiceProductList($lowp);
    	$list['additional_charges_list']=$this->order_model->GetProFormaInvoiceAdditionalCharges($lowp);
    	$list['currency_list']=$this->lead_model->GetCurrencyList();
        $list['company']=get_company_profile();    
        $list['unit_type_list']=$this->Product_model->GetUnitList();
        $html = $this->load->view('admin/order/updated_pfi_product_list_ajax',$list,TRUE);


    	// --------------------------------
    	     
        $product_list=$this->order_model->GetProFormaInvoiceProductList($lowp);

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

        // -------------------------------
        
        // =======================================
        // CALCULATE ADDITIONAL PRICE
        
        $additional_charges_list=$this->order_model->GetProFormaInvoiceAdditionalCharges($lowp);
        
        if(count($additional_charges_list))
        {
            foreach($additional_charges_list as $charge)
            {

                $item_gst_per= $charge->gst;
                $item_sgst_per= ($item_gst_per/2);
                $item_cgst_per= ($item_gst_per/2); 
                $item_is_discount_p_or_a=$charge->is_discount_p_or_a; 
                $item_discount=$charge->discount; 
                // $item_discount_per=$charge->discount; 
                $item_price= $charge->price;
                $item_qty=1;

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
                $total_tax_price=$total_tax_price+$row_gst_amount;
                $total_discounted_price=$total_discounted_price+$row_discount_amount;   
            }
        }
        
        // CALCULATE ADDITIONAL PRICE
        // =======================================
        
        

        // ---------------------------------
        $data_update=array(
		'total_pro_forma_inv_amount'=>$sub_total,
		'updated_at'=>date("Y-m-d H:i:s")
		);	
		$this->order_model->updatePoFormaInvoice($data_update,$po_pro_forma_invoice_id);
        // ------------------------------------
        // $result['total_sale_price'] = number_format($row_final_price,2);
        // $result["total_deal_value"]=number_format($sub_total,2);

        $result["total_price"]=number_format($total_price,2);
        $result["total_discount"]=number_format($total_discounted_price,2);
        $result["total_tax"]=number_format($total_tax_price,2);
        $result["grand_total_round_off"]=number_format(round($sub_total),2);
        $result["number_to_word_final_amount"]=number_to_word(round($sub_total));
        // $result["opportunity_id"] =$opportunity_id;
        // --------------------------
        
    	$result["status"] = 'success';
    	$result['html'] = $html;
        echo json_encode($result);
        exit(0); 
	}

	public function pfi_update_currency_type_ajax()
	{
		$lowp=$this->input->post('lowp');
		$po_pro_forma_invoice_id=$this->input->post('po_pro_forma_invoice_id');
		$currency_type=$this->input->post('currency_type');			
		$currency_type_code=trim($this->input->post('currency_type_code'));
		// --------------------------------------
		$data_update=array('currency_type'=>$currency_type_code);
		$this->order_model->updatePoFormaInvoice($data_update,$po_pro_forma_invoice_id);
		// ---------------------------------------
		
		$result['status']='success';
        echo json_encode($result);
        exit(0); 
	}

	public function update_inv_product_ajax()
	{
		$pid=$this->input->post('pid');
		$id=$this->input->post('id');
		$field=$this->input->post('field');
		$value=$this->input->post('value');	
		$inv_id=$this->input->post('inv_id');		
		$lowp=$this->input->post('lowp');
		// echo 'ID-'.$id.'/pfiID-'.$pfi_id.'/lowp-'.$lowp;die();
		$data_post=array(						
						$field=>$value
						);
		$update_data=$this->order_model->update_inv_product($data_post,$id);

		if($update_data==true)
        	$result["status"] = 'success';
        else
        	$result["status"] = 'fail';
        

        $list=array();
        // $product_list=$this->quotation_model->GetQuotationProductList($quotation_id);
        $product_list=$this->order_model->GetInvoiceProductList($lowp);

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
			// $row_discount_amount=$item_total_amount*($item_discount_per/100);
			$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

			$row_final_price_tmp=($item_total_amount+$row_gst_amount-$row_discount_amount);
			$sub_total=$sub_total+$row_final_price_tmp;

			$total_price=$total_price+$item_total_amount;
			$total_discounted_price=$total_discounted_price+$row_discount_amount;
			$total_tax_price=$total_tax_price+$row_gst_amount;		
		}
		// -------------------------------
		 
		
		$product=$this->order_model->GetInvoiceProduct($id);
		$item_gst_per= $product->gst;
		$item_sgst_per= ($item_gst_per/2);
		$item_cgst_per= ($item_gst_per/2); 
		$item_is_discount_p_or_a=$product->is_discount_p_or_a; 
		$item_discount_per=$product->discount; 
		$item_unit= $product->unit;
		$item_price= ($product->price/$item_unit);
		$item_qty=$product->quantity;
		$item_total_amount=($item_price*$item_qty);
		if($item_is_discount_p_or_a=='A'){
			$row_discount_amount=$item_discount_per;
		}
		else{
			$row_discount_amount=$item_total_amount*($item_discount_per/100);
		}	
		// $row_discount_amount=$item_total_amount*($item_discount_per/100);
		$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);
		$row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
		
		// =======================================
		// CALCULATE ADDITIONAL PRICE
		
		// $additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		$additional_charges_list=$this->order_model->GetInvoiceAdditionalCharges($lowp);
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

		// ---------------------------------
		$data_update=array(
						'total_inv_amount'=>$sub_total,
						'updated_at'=>date("Y-m-d H:i:s")
						);	
		$this->order_model->updateInvoice($data_update,$inv_id);
		// ------------------------------------
		$result['total_sale_price'] = number_format($row_final_price,2);
		$result["total_deal_value"]=number_format($sub_total,2);

		$result["total_price"]=number_format($total_price,2);
		$result["total_discount"]=number_format($total_discounted_price,2);
		$result["total_tax"]=$total_tax_price;
		$result["grand_total_round_off"]=number_format(round($sub_total),2);
		$result["number_to_word_final_amount"]=number_to_word(round($sub_total));
    	// $result["opportunity_id"] =$opportunity_id;
    	$result['pid']=$pid;
        echo json_encode($result);
        exit(0); 
	}

	public function del_inv_product_ajax()
	{	
		$id=$this->input->post('id');
		$inv_id=$this->input->post('inv_id');
		$lowp=$this->input->post('lowp');
		$pid=$this->input->post('pid');
		
		$del_data=$this->order_model->DeleteInvoiceProduct($id);

		if($del_data==true)
        	$result["status"] = 'success';
        else
        	$result["status"] = 'fail';

        $list=array();        
        $product_list=$this->order_model->GetInvoiceProductList($lowp);

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
		// -------------------------------
		
		
		$product=$this->order_model->GetInvoiceProduct($id);
		$item_gst_per= $product->gst;
		$item_sgst_per= ($item_gst_per/2);
		$item_cgst_per= ($item_gst_per/2); 
		$item_is_discount_p_or_a=$product->is_discount_p_or_a; 
		$item_discount_per=$product->discount; 
		$item_price= $product->price;
		$item_qty=$product->unit;
		$item_total_amount=($item_price*$item_qty);
		if($item_is_discount_p_or_a=='A'){
			$row_discount_amount=$item_discount_per;
		}
		else{
			$row_discount_amount=$item_total_amount*($item_discount_per/100);
		}	
		// $row_discount_amount=$item_total_amount*($item_discount_per/100);
		$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);
		$row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
		
		// =======================================
		// CALCULATE ADDITIONAL PRICE
		
		// $additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		$additional_charges_list=$this->order_model->GetInvoiceAdditionalCharges($lowp);
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
		$data_update=array(
						'total_inv_amount'=>$sub_total,
						'updated_at'=>date("Y-m-d H:i:s")
						);	
		$this->order_model->updateInvoice($data_update,$inv_id);
		// ------------------------------------

		$result['total_sale_price'] = number_format($row_final_price,2);
		$result["total_deal_value"]=number_format($sub_total,2);

		$result["total_price"]=number_format($total_price,2);
		$result["total_discount"]=number_format($total_discounted_price,2);
		$result["total_tax"]=$total_tax_price;
		$result["grand_total_round_off"]=number_format(round($sub_total),2);
		$result["number_to_word_final_amount"]=number_to_word(round($sub_total));
    	// $result["opportunity_id"] =$opportunity_id;
    	$result['pid']=$pid;
        echo json_encode($result);
        exit(0); 
	}

	public function update_inv_additional_charges_ajax()
	{
		$pid=$this->input->post('pid');
		$id=$this->input->post('id');
		$field=$this->input->post('field');
		$value=$this->input->post('value');	
		$inv_id=$this->input->post('inv_id');		
		$lowp=$this->input->post('lowp');
		// echo 'ID-'.$id.'/pfiID-'.$pfi_id.'/lowp-'.$lowp;die();
		$data_post=array(						
						$field=>$value
						);
		$update_data=$this->order_model->update_inv_additional_charges($data_post,$id);

		if($update_data==true)
        	$result["status"] = 'success';
        else
        	$result["status"] = 'fail';
        

        $list=array();        
        $product_list=$this->order_model->GetInvoiceProductList($lowp);

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
		// -------------------------------
		
		
		$product=$this->order_model->GetInvoiceAdditionalChargesRow($id);
		// print_r($product); die();
		$item_gst_per= $product->gst;
		$item_sgst_per= ($item_gst_per/2);
		$item_cgst_per= ($item_gst_per/2); 
		$item_is_discount_p_or_a=$product->is_discount_p_or_a; 
		$item_discount_per=$product->discount; 
		$item_price= $product->price;
		// $item_qty=$product->unit;
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
		$row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
		
		// =======================================
		// CALCULATE ADDITIONAL PRICE
		
		// $additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		$additional_charges_list=$this->order_model->GetInvoiceAdditionalCharges($lowp);
		if(count($additional_charges_list))
		{
			foreach($additional_charges_list as $charge)
			{

				$item_gst_per= $charge->gst;
				$item_sgst_per= ($item_gst_per/2);
				$item_cgst_per= ($item_gst_per/2);  
				// $item_discount_per=$charge->discount; 
				$item_discount=$charge->discount; 


				$item_price= $charge->price;
				$item_qty=1;

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
				$total_tax_price=$total_tax_price+$row_gst_amount;
				$total_discounted_price=$total_discounted_price+$row_discount_amount;	
			}
		}

		// CALCULATE ADDITIONAL PRICE
		// =======================================
		
    	// $html = $this->load->view('admin/product/updated_product_selected_list_ajax',$list,TRUE);
    	// $result['html'] = $html;

		// ---------------------------------
		$data_update=array(
				'total_inv_amount'=>$sub_total,
				'updated_at'=>date("Y-m-d H:i:s")
				);	
		$this->order_model->updateInvoice($data_update,$inv_id);
		// ------------------------------------
		$result['total_sale_price'] = number_format($row_final_price,2);
		$result["total_deal_value"]=number_format($sub_total,2);

		$result["total_price"]=number_format($total_price,2);
		$result["total_discount"]=number_format($total_discounted_price,2);
		$result["total_tax"]=$total_tax_price;
		$result["grand_total_round_off"]=number_format(round($sub_total),2);
		$result["number_to_word_final_amount"]=number_to_word(round($sub_total));
    	// $result["opportunity_id"] =$opportunity_id;
    	$result['pid']=$pid;
        echo json_encode($result);
        exit(0); 
	}

	public function del_inv_additional_charges_ajax()
	{	
		$id=$this->input->post('id');
		$inv_id=$this->input->post('inv_id');
		$lowp=$this->input->post('lowp');
		$pid=$this->input->post('pid');
		
		$del_data=$this->order_model->DeleteInvoiceAdditionalCharges($id);

		if($del_data==true)
        	$result["status"] = 'success';
        else
        	$result["status"] = 'fail';

        $list=array();
        
        $product_list=$this->order_model->GetInvoiceProductList($lowp);

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
		// -------------------------------
		
		// $product=$this->quotation_model->GetQuotationProduct($quotation_id,$pid);
		$product=$this->order_model->GetInvoiceAdditionalChargesRow($id);
		$item_gst_per= $product->gst;
		$item_sgst_per= ($item_gst_per/2);
		$item_cgst_per= ($item_gst_per/2); 
		$item_is_discount_p_or_a=$product->is_discount_p_or_a; 
		$item_discount_per=$product->discount;
		$item_unit= $product->unit; 
		$item_price=($product->price/$item_unit);
		$item_qty=$product->quantity;
		$item_total_amount=($item_price*$item_qty);
		if($item_is_discount_p_or_a=='A'){
			$row_discount_amount=$item_discount_per;
		}
		else{
			$row_discount_amount=$item_total_amount*($item_discount_per/100);
		}	
		// $row_discount_amount=$item_total_amount*($item_discount_per/100);
		$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);
		$row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
		
		// =======================================
		// CALCULATE ADDITIONAL PRICE
		
		// $additional_charges_list=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
		$additional_charges_list=$this->order_model->GetInvoiceAdditionalCharges($lowp);
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
		$data_update=array(
						'total_inv_amount'=>$sub_total,
						'updated_at'=>date("Y-m-d H:i:s")
						);	
		$this->order_model->updatePoFormaInvoice($data_update,$inv_id);
		// ------------------------------------

		$result['total_sale_price'] = number_format($row_final_price,2);
		$result["total_deal_value"]=number_format($sub_total,2);

		$result["total_price"]=number_format($total_price,2);
		$result["total_discount"]=number_format($total_discounted_price,2);
		$result["total_tax"]=$total_tax_price;
		$result["grand_total_round_off"]=number_format(round($sub_total),2);
		$result["number_to_word_final_amount"]=number_to_word(round($sub_total));
    	// $result["opportunity_id"] =$opportunity_id;
    	// $result['pid']=$pid;
        echo json_encode($result);
        exit(0); 
	}

	public function inv_update_currency_type_ajax()
	{
		$lowp=$this->input->post('lowp');
		$po_invoice_id=$this->input->post('po_invoice_id');
		$currency_type=$this->input->post('currency_type');			
		$currency_type_code=trim($this->input->post('currency_type_code'));
		// --------------------------------------
		$data_update=array('currency_type'=>$currency_type_code);
		$this->order_model->updateInvoice($data_update,$po_invoice_id);
		// ---------------------------------------
		
		$result['status']='success';
        echo json_encode($result);
        exit(0); 
	}

	public function inv_product_discount_type_update_ajax()
	{	
		$lowp=$this->input->post('lowp');
		$po_invoice_id=$this->input->post('po_invoice_id');
		$is_discount_p_or_a=$this->input->post('is_discount_p_or_a');

		$postData=array();
		$postData=array('is_discount_p_or_a'=>$is_discount_p_or_a);
		$r=$this->order_model->UpdateInvProdByProFormaInvId($postData,$po_invoice_id);

		$postData=array();
		$postData=array('is_discount_p_or_a'=>$is_discount_p_or_a);
		$r2=$this->order_model->UpdateInvAdditionalChargesByProFormaInvId($postData,$po_invoice_id);


        

    	$list=array();
    	$po_register_info=$this->order_model->get_po_register_detail($lowp);
    	$list['po_inv_info']=$this->order_model->get_po_invoice_detail_lowpo_wise($lowp);

    	$list['prod_list']=$this->order_model->GetInvoiceProductList($lowp);
    	$list['additional_charges_list']=$this->order_model->GetInvoiceAdditionalCharges($lowp);
    	$list['currency_list']=$this->lead_model->GetCurrencyList();
        $list['company']=get_company_profile();     
        $list['unit_type_list']=$this->Product_model->GetUnitList();
        $html = $this->load->view('admin/order/updated_inv_product_list_ajax',$list,TRUE);


    	// --------------------------------
    	     
        $product_list=$this->order_model->GetInvoiceProductList($lowp);

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

        // -------------------------------
        
        // =======================================
        // CALCULATE ADDITIONAL PRICE
        
        $additional_charges_list=$this->order_model->GetInvoiceAdditionalCharges($lowp);
        
        if(count($additional_charges_list))
        {
            foreach($additional_charges_list as $charge)
            {

                $item_gst_per= $charge->gst;
                $item_sgst_per= ($item_gst_per/2);
                $item_cgst_per= ($item_gst_per/2); 
                $item_is_discount_p_or_a=$charge->is_discount_p_or_a; 
                $item_discount=$charge->discount; 
                // $item_discount_per=$charge->discount; 
                $item_price= $charge->price;
                $item_qty=1;

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
                $total_tax_price=$total_tax_price+$row_gst_amount;
                $total_discounted_price=$total_discounted_price+$row_discount_amount;   
            }
        }
        
        // CALCULATE ADDITIONAL PRICE
        // =======================================
        
        

        // ---------------------------------
        $data_update=array(
		'total_inv_amount'=>$sub_total,
		'updated_at'=>date("Y-m-d H:i:s")
		);	
		$this->order_model->updateInvoice($data_update,$po_invoice_id);
        // ------------------------------------
        // $result['total_sale_price'] = number_format($row_final_price,2);
        // $result["total_deal_value"]=number_format($sub_total,2);

        $result["total_price"]=number_format($total_price,2);
        $result["total_discount"]=number_format($total_discounted_price,2);
        $result["total_tax"]=$total_tax_price;
        $result["grand_total_round_off"]=number_format(round($sub_total),2);
        $result["number_to_word_final_amount"]=number_to_word(round($sub_total));
        // $result["opportunity_id"] =$opportunity_id;
        // --------------------------
        
    	$result["status"] = 'success';
    	$result['html'] = $html;
        echo json_encode($result);
        exit(0); 
	}

	function po_invoice_post_ajax()
    {
    	$status_str='fail';
    	$msg='Unknown error.';
    	$owp_id=$this->input->post('lead_opportunity_wise_po_id');
    	$po_invoice_id=$this->input->post('po_invoice_id');
    	$invoice_type=$this->input->post('invoice_type');

    	$invoice_no=$this->input->post('po_inv_no');
    	$invoice_date = $this->input->post('po_inv_date');
    	$due_date = $this->input->post('po_inv_due_date');
    	$expected_delivery_date = $this->input->post('po_inv_expected_delivery_date');
    	// $bill_to = $this->input->post('po_inv_bill_to');
    	// $bill_from = $this->input->post('po_inv_bill_from');
    	// $ship_to = $this->input->post('po_inv_ship_to');
    	// $terms_conditions = $this->input->post('inv_terms_conditions');

    	$po_register_info=$this->order_model->get_po_register_detail($owp_id);
    	$lead_id=$po_register_info->lead_id;
    	
    	$inv_mode=$this->order_model->get_inv_mode($po_invoice_id);  

    	$inv_info=$this->order_model->get_invoice($po_invoice_id);
    	$currency_type_code=$inv_info->currency_type;
    	// $total_inv_amount=$inv_info->total_inv_amount;

    	$$po_custom_invoice_file_name='';
		$this->load->library('upload', '');
		if($_FILES['po_custom_invoice']['name'] != "")
		{
			$config2['upload_path'] = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/";
			$config2['allowed_types'] = "gif|jpg|jpeg|png|pdf|doc|docx|txt|xlsx";
			$config2['max_size'] = '1000000'; //KB
			$config2['overwrite'] = FALSE; 
			$config2['encrypt_name'] = TRUE; 
			$this->upload->initialize($config2);
			if (!$this->upload->do_upload('po_custom_invoice'))
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
			    $po_custom_invoice_file_name=$file_data['upload_data']['file_name'];
			    $post_data=array(
	    		'po_custom_invoice'=>$po_custom_invoice_file_name,
				'updated_at'=>date("Y-m-d H:i:s")
				);
				$this->order_model->updateInvoice($post_data,$po_invoice_id);		

			}
		}

		if($inv_mode=='update')
    	{
    		$post_data=array(
    		'invoice_type'=>$invoice_type,
			'invoice_no'=>$invoice_no,
			'invoice_date'=>date_display_format_to_db_format($invoice_date),
			'due_date'=>date_display_format_to_db_format($due_date),
			'expected_delivery_date'=>date_display_format_to_db_format($expected_delivery_date),
			// 'bill_from'=>$bill_from,
			// 'bill_to'=>$bill_to,
			// 'ship_to'=>$ship_to,
			// 'terms_conditions'=>$terms_conditions,
			'updated_at'=>date("Y-m-d H:i:s")
			);

			// history log					
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$update_by_name=get_value("name","user","id=".$update_by);
			$ip_addr=$_SERVER['REMOTE_ADDR'];		
			$comment_title=PO_INVOICE_UPDATE;
			$comment='';
			$comment .='Invoice No. : #'.$invoice_no;
			$comment .=' | ';
			$comment .='Invoice Date: '.$invoice_date;
			$comment .=' | ';
			$comment .='Due Date: '.$due_date;	
			$comment .=' | ';
			$comment .='Updated By: '.$update_by_name;	
			// $comment .='Total Invoice Amount: '.$currency_type_code.' '.$total_inv_amount;	

			$historydata=array(
			'title'=>$comment_title,
			'lead_id'=>$lead_id,
			'comment'=>addslashes($comment),
			'create_date'=>date("Y-m-d H:i:s"),
			'user_id'=>$update_by,
			'ip_address'=>$ip_addr
			);
			$this->history_model->CreateHistory($historydata);

			$msg='Invoice successfully updated.';
    	}
    	else
    	{
    		$post_data=array(
    		'invoice_type'=>$invoice_type,
			'invoice_no'=>$invoice_no,
			'invoice_date'=>date_display_format_to_db_format($invoice_date),
			'due_date'=>date_display_format_to_db_format($due_date),
			'expected_delivery_date'=>date_display_format_to_db_format($expected_delivery_date),
			// 'bill_from'=>$bill_from,
			// 'bill_to'=>$bill_to,
			// 'ship_to'=>$ship_to,
			// 'terms_conditions'=>$terms_conditions,
			'created_at'=>date("Y-m-d H:i:s"),
			'updated_at'=>date("Y-m-d H:i:s")
			);

			// history log					
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$update_by_name=get_value("name","user","id=".$update_by);
			$ip_addr=$_SERVER['REMOTE_ADDR'];		
			$comment_title=PO_INVOICE_CREATE;
			$comment='';
			$comment .='Invoice No. : #'.$invoice_no;
			$comment .=' | ';
			$comment .='Invoice Date: '.$invoice_date;
			$comment .=' | ';
			$comment .='Due Date: '.$due_date;
			$comment .=' | ';
			$comment .='Updated By: '.$update_by_name;
			// $comment .=' | ';	
			// $comment .='Total Invoice Amount: '.$currency_type_code.' '.$total_inv_amount;	

			$historydata=array(
			'title'=>$comment_title,
			'lead_id'=>$lead_id,
			'comment'=>addslashes($comment),
			'create_date'=>date("Y-m-d H:i:s"),
			'user_id'=>$update_by,
			'ip_address'=>$ip_addr
			);
			$this->history_model->CreateHistory($historydata);

			$msg='Invoice successfully saved.';
    	}

    	$this->order_model->updateInvoice($post_data,$po_invoice_id);
        

    	$post_inv_info=$this->order_model->get_invoice($po_invoice_id);
        if($invoice_type=='S' && $post_inv_info->po_custom_invoice!='')
        {
        	$post_data=array(
	    		'po_custom_invoice'=>'',
				'updated_at'=>date("Y-m-d H:i:s")
				);
			$this->order_model->updateInvoice($post_data,$po_invoice_id);
        }

        $po_custom_invoice='';
        if($invoice_type=='C' && $post_inv_info->po_custom_invoice!='')
        {
        	$company_name_tmp=($po_register_info->cust_company_name)?$po_register_info->cust_company_name:$po_register_info->cust_contact_person;

            $f_name="INVOICE ".$invoice_no."-".date_format(date_create(date('Y-m-d')),'M y')."-".strtoupper($company_name_tmp);

        	$po_custom_invoice=base_url().$this->session->userdata['admin_session_data']['lms_url']."/order/download_po/".base64_encode("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/".$post_inv_info->po_custom_invoice.'#'.$f_name);
        	$result['po_custom_invoice_file_name']=$post_inv_info->po_custom_invoice;
        }
        else
        {
        	$po_custom_invoice=base_url($this->session->userdata['admin_session_data']['lms_url'].'/order/download_inv/'.$owp_id);
        	$result['po_custom_invoice_file_name']='';
        }
        $result['po_custom_invoice']=$po_custom_invoice;
        $result['po_invoice_id']=$po_invoice_id;
        $result['status']='success';
        $result['msg']=$msg;
        $result['lowp']=$owp_id;
        echo json_encode($result);
        exit(0);
    }

    public function download_po($file='')
	{	
		if($file!='')
		{	
			$this->load->helper(array('download'));	
			$file_name_str = base64_decode($file);
			$file_name_arr=explode("#", $file_name_str);			
			$file_name_with_full_path=$file_name_arr[0];			
			$file_ext=end(explode(".", $file_name_with_full_path));
			$file_name=$file_name_arr[1].'.'.$file_ext;
			$pth=file_get_contents($file_name_with_full_path);
			force_download($file_name, $pth); 
			exit;
		}
	}

	function po_wysiwyg_textarea_update_ajax()
    {
        $pfi_id=$this->input->post('pfi_id');
        $inv_id=$this->input->post('inv_id');


        $updated_field_name_arr=explode("#", $this->input->post('updated_field_name'));
        $updated_field_name=$updated_field_name_arr[0];

        $updated_field_id=$updated_field_name_arr[1];


        $updated_content=$this->input->post('updated_content');

        if($updated_field_name=='pfi_bill_to' || $updated_field_name=='pfi_bill_from' || $updated_field_name=='pfi_ship_to' ||  $updated_field_name=='pfi_bank_detail_1' || $updated_field_name=='pfi_bank_detail_2' || $updated_field_name=='pfi_terms_conditions' || $updated_field_name=='pfi_additional_note' || $updated_field_name=='pfi_name_of_authorised_signature' || $updated_field_name=='pfi_product_name' || $updated_field_name=='pfi_additional_charge_name' || $updated_field_name=='pfi_thanks_and_regards' || $updated_field_name=='pfi_is_digital_signature_checked')
        {
        	$updated_field_name_tmp = substr(trim($updated_field_name), 4);
        	
        	/*
        	if($updated_field_name=='pfi_bill_to'){
        		$updated_field_name='bill_to';
        	}
        	else if($updated_field_name=='pfi_bill_from'){
        		$updated_field_name='bill_from';
        	}
        	else if($updated_field_name=='pfi_ship_to'){
        		$updated_field_name='ship_to';
        	}
        	else if($updated_field_name=='pfi_bank_detail_1'){
        		$updated_field_name='bank_detail_1';
        	}
        	else if($updated_field_name=='pfi_bank_detail_2'){
        		$updated_field_name='bank_detail_2';
        	}
        	else if($updated_field_name=='pfi_terms_conditions'){
        		$updated_field_name='terms_conditions';
        	}
        	else if($updated_field_name=='pfi_additional_note'){
        		$updated_field_name='additional_note';
        	}
        	else if($updated_field_name=='pfi_name_of_authorised_signature'){
        		$updated_field_name='name_of_authorised_signature';
        	}
        	else if($updated_field_name=='pfi_thanks_and_regards'){
        		$updated_field_name='thanks_and_regards';
        	}
        	else if($updated_field_name=='pfi_is_digital_signature_checked'){
        		$updated_field_name='is_digital_signature_checked';
        	}
        	*/
        	$is_p_or_ac_update='N';
			// pro forma inv product update
			if($updated_field_name=='pfi_product_name' && $updated_field_id>0)
			{
				// $updated_field_name='product_name';
				$data = array(
					$updated_field_name_tmp=>$updated_content
				);
				$this->order_model->update_product($data,$updated_field_id);

				$is_p_or_ac_update='Y';
			}
			// pro forma inv additional charges update
			if($updated_field_name=='pfi_additional_charge_name' && $updated_field_id>0)
			{
				// $updated_field_name='additional_charge_name';
				$data = array(
					$updated_field_name_tmp=>$updated_content
				);
				$this->order_model->update_additional_charges($data,$updated_field_id);
				$is_p_or_ac_update='Y';
			}

			if($is_p_or_ac_update=='N')
			{
				$data = array(
						$updated_field_name_tmp=>$updated_content,
						'updated_at' =>date('Y-m-d H:i:s')
					);
	        	// print_r($data); die();
				$this->order_model->updatePoFormaInvoice($data,$pfi_id);
			}
        }
        else if($updated_field_name=='po_inv_bill_to' || $updated_field_name=='po_inv_bill_from' || $updated_field_name=='po_inv_ship_to' || $updated_field_name=='po_inv_terms_conditions' || $updated_field_name=='po_inv_bank_detail_1' || $updated_field_name=='po_inv_bank_detail_2' || $updated_field_name=='po_inv_additional_note' || $updated_field_name=='po_inv_name_of_authorised_signature' || $updated_field_name=='po_inv_product_name' || $updated_field_name=='po_inv_additional_charge_name' || $updated_field_name=='po_inv_thanks_and_regards' || $updated_field_name=='po_inv_is_digital_signature_checked')
        {
        	$updated_field_name_tmp = substr(trim($updated_field_name), 7);
        	/*
        	if($updated_field_name=='po_inv_bill_to'){
        		$updated_field_name='bill_to';
        	}
        	else if($updated_field_name=='po_inv_bill_from'){
        		$updated_field_name='bill_from';
        	}
        	else if($updated_field_name=='po_inv_ship_to'){
        		$updated_field_name='ship_to';
        	}
        	else if($updated_field_name=='po_inv_terms_conditions'){
        		$updated_field_name='terms_conditions';
        	}
        	else if($updated_field_name=='po_inv_bank_detail_1'){
        		$updated_field_name='bank_detail_1';
        	}
        	else if($updated_field_name=='po_inv_bank_detail_2'){
        		$updated_field_name='bank_detail_2';
        	}
        	else if($updated_field_name=='po_inv_additional_note'){
        		$updated_field_name='additional_note';
        	}
        	else if($updated_field_name=='po_inv_name_of_authorised_signature'){
        		$updated_field_name='name_of_authorised_signature';
        	}
			*/

			$is_p_or_ac_update='N';
			// pro forma inv product update
			if($updated_field_name=='po_inv_product_name' && $updated_field_id>0)
			{
				// $updated_field_name='product_name';
				$data = array(
					$updated_field_name_tmp=>$updated_content
				);
				$this->order_model->update_inv_product($data,$updated_field_id);
				$is_p_or_ac_update='Y';

			}
			// pro forma inv additional charges update
			if($updated_field_name=='po_inv_additional_charge_name' && $updated_field_id>0)
			{
				// $updated_field_name='additional_charge_name';
				$data = array(
					$updated_field_name_tmp=>$updated_content
				);
				$this->order_model->update_inv_additional_charges($data,$updated_field_id);

				$is_p_or_ac_update='Y';
			}

			if($is_p_or_ac_update=='N')
			{
				$data = array(
						$updated_field_name_tmp=>$updated_content,
						'updated_at' =>date('Y-m-d H:i:s')
					);
				$this->order_model->updateInvoice($data,$inv_id);
			}
        }

		// if($updated_field_name=='quotation_bank_details1' || $updated_field_name=='quotation_bank_details2') //quotation_time_company_setting_log
		// {
		// 	$data = array(
		// 		$updated_field_name=>$updated_content
		// 	);
		// 	$this->quotation_model->UpdateQuotationTimeCompanyInfoLogByQuotationId($data,$quotation_id);
		// }
		// else
		// {
		// 	if($updated_field_name=='quote_valid_until')
		// 	{
		// 		$updated_content=date_display_format_to_db_format($updated_content);
		// 	}        

		// 	$data = array(
		// 				$updated_field_name=>$updated_content,
		// 				'modify_date' =>date('Y-m-d H:i:s')
		// 			);
		// 	$this->quotation_model->UpdateQuotation($data,$quotation_id);
		// }
        // $msg = "Quation successfully updated!"; // 'duplicate';         
        //$this->session->set_flashdata('success_msg', $msg);
        $data =array ("status"=>'success');
        $this->output->set_content_type('application/json');
        echo json_encode($data);
    }

    public function preview_pro_forma_inv($lowp)
    {
    	$data=array();		
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		// $admin_session_data_user_data=$this->user_model->GetAdminData($user_id);
		// $data['admin_session_data_user_data']=$admin_session_data_user_data[0];	
		$data['company']=get_company_profile();
		$po_register_info=$this->order_model->get_po_register_detail($lowp);
		$data['po_register_info']=$po_register_info;
		$data['po_pro_forma_inv_info']=$this->order_model->get_po_pro_forma_invoice_detail_lowpo_wise($lowp);
		$data['prod_list']=$this->order_model->GetProFormaInvoiceProductList($lowp);
        $data['additional_charges_list']=$this->order_model->GetProFormaInvoiceAdditionalCharges($lowp);
		echo $this->load->view('admin/order/po_pro_forma_inv_rander_pdf_view',$data,TRUE);
    }
    public function download_pro_forma_inv($lowp)
    {		
    	$this->generate_pro_forma_inv_pdf($lowp,'D');
    }

    public function generate_pro_forma_inv_pdf($lowp,$action_type="")
    {
		// $session_data = $this->session->userdata('admin_session_data');	
		// if($session_data['client_id']=='238')
		// {
			
		// }	
		
    	$data=array();		
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		// $admin_session_data_user_data=$this->user_model->GetAdminData($user_id);
		// $data['admin_session_data_user_data']=$admin_session_data_user_data[0];	
		$data['company']=get_company_profile();
		$po_register_info=$this->order_model->get_po_register_detail($lowp);
		$data['po_register_info']=$po_register_info;
		$data['po_pro_forma_inv_info']=$this->order_model->get_po_pro_forma_invoice_detail_lowpo_wise($lowp);
		$data['prod_list']=$this->order_model->GetProFormaInvoiceProductList($lowp);
        $data['additional_charges_list']=$this->order_model->GetProFormaInvoiceAdditionalCharges($lowp);
		
    	// -----------------------------
	    // Generate PDF Script Start  
	    $company_name_tmp=($po_register_info->cust_company_name)?$po_register_info->cust_company_name:$po_register_info->cust_contact_person;
	    $pdfFileName = "PROFORMA INVOICE ".$data['po_pro_forma_inv_info']->pro_forma_no."-".date_format(date_create(date('Y-m-d')),'M y')."-".strtoupper($company_name_tmp).".pdf";
		// $pdfFilePath = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/".$pdfFileName;
		$pdfFilePath = "assets/uploads/clients/log/".$pdfFileName;     
		
		$pdf_html = $this->load->view('admin/order/po_pro_forma_inv_rander_pdf_view',$data,TRUE);
		
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
		
		$mpdf->SetTitle("Proforma Invoice");
        $mpdf->SetAuthor($data['company']['name']);	
        $mpdf->showWatermarkText = true;
        $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->SetDisplayMode('fullpage');		
        $mpdf->defaultfooterfontstyle='';
        $mpdf->defaultfooterfontsize=8;
        $mpdf->defaultfooterline=0;        
		
        $mpdf->WriteHTML($pdf_html);		
        // $mpdf->Output();die();        
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



    public function preview_invoice($lowp)
    {	
    	$data=array();		
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		// $admin_session_data_user_data=$this->user_model->GetAdminData($user_id);
		// $data['admin_session_data_user_data']=$admin_session_data_user_data[0];	
		$data['company']=get_company_profile();
		$po_register_info=$this->order_model->get_po_register_detail($lowp);
		$data['po_register_info']=$po_register_info;
		$data['po_inv_info']=$this->order_model->get_po_invoice_detail_lowpo_wise($lowp);
        $data['prod_list']=$this->order_model->GetInvoiceProductList($lowp);
        $data['additional_charges_list']=$this->order_model->GetInvoiceAdditionalCharges($lowp);	
		
        
		echo $this->load->view('admin/order/po_invoice_rander_pdf_view',$data,TRUE);
    }
    public function download_invoice($lowp)
    {
    	$this->generate_invoice_pdf($lowp,'D');
    }

    public function generate_invoice_pdf($lowp,$action_type="")
    {
    	$data=array();		
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		// $admin_session_data_user_data=$this->user_model->GetAdminData($user_id);
		// $data['admin_session_data_user_data']=$admin_session_data_user_data[0];	
		$data['company']=get_company_profile();
		$po_register_info=$this->order_model->get_po_register_detail($lowp);
		$data['po_register_info']=$po_register_info;
		$data['po_inv_info']=$this->order_model->get_po_invoice_detail_lowpo_wise($lowp);
        $data['prod_list']=$this->order_model->GetInvoiceProductList($lowp);
        $data['additional_charges_list']=$this->order_model->GetInvoiceAdditionalCharges($lowp);

    	// -----------------------------
	    // Generate PDF Script Start  
	    $company_name_tmp=($po_register_info->cust_company_name)?$po_register_info->cust_company_name:$po_register_info->cust_contact_person;	    
	    $pdfFileName = "INVOICE ".$data['po_inv_info']->invoice_no."-".date_format(date_create($data['po_inv_info']->invoice_date),'M y')."-".strtoupper($company_name_tmp).".pdf";
		// $pdfFilePath = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/quotation/".$pdfFileName;
		$pdfFilePath = "assets/uploads/clients/log/".$pdfFileName;     
		$pdf_html = $this->load->view('admin/order/po_invoice_rander_pdf_view',$data,TRUE);
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
		$mpdf->SetTitle("Invoice");
        $mpdf->SetAuthor($data['company']['name']);
        // $mpdf->SetWatermarkText($data['company']['name']);
        $mpdf->showWatermarkText = true;
        $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->SetDisplayMode('fullpage');
		//$stylesheet = file_get_contents(base_url().'styles/quotation_pdf.css'); // external css			
		//$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetDisplayMode('fullpage');
        $mpdf->defaultfooterfontstyle='';
        $mpdf->defaultfooterfontsize=8;
        $mpdf->defaultfooterline=0;
        //$mpdf->setFooter('Page {PAGENO} of {nb}');
        $mpdf->WriteHTML($pdf_html);
        // $mpdf->Output();die();
        
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






    public function invoice_management()
	{
		if($this->session->userdata('po_back_url_from_detail'))
		{
			$this->session->set_userdata('po_back_url_from_detail','');
		}
		$data=array();
		$data['get_fy']=get_fy_rows(5);
		$this->load->view('admin/order/invoice_management_view',$data);
	}
	// AJAX PAGINATION START
	function get_invoice_management_list_ajax()
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
	    $arg['filter_sort_by']=$this->input->get('filter_sort_by');
		
		$arg['sort_by_fy_start_date']=$this->input->get('sort_by_fy_start_date');
		$arg['sort_by_fy_end_date']=$this->input->get('sort_by_fy_end_date');
		$arg['filter_string_search']=$this->input->get('filter_string_search');
	   //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->order_model->get_invoice_management_list_count($arg);
	    // $config['total_rows'] =10;
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
	    
    	
    	
    			
	    $table = '';	
	    if($view_type=='grid')
	    {
	    	//$table = $this->load->view('admin/order/invoice_management_grid_view_ajax',$list,TRUE);
	    }
	    else
	    {
	    	$list['rows']=$this->order_model->get_invoice_management_list($arg);
	    	$table = $this->load->view('admin/order/invoice_management_list_view_ajax',$list,TRUE);
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

	public function download_invoice_management_csv()
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
	    $arg['assigned_user']=($this->input->get('filter_assigned_user'))?$this->input->get('filter_assigned_user'):$tmp_u_ids_str;		
	    $arg['filter_sort_by']=$this->input->get('filter_sort_by');
		
		$arg['sort_by_fy_start_date']=$this->input->get('sort_by_fy_start_date');
		$arg['sort_by_fy_end_date']=$this->input->get('sort_by_fy_end_date');
		$arg['filter_string_search']=$this->input->get('filter_string_search');	  
	    $total_rows = $this->order_model->get_invoice_management_list_count($arg);
	    $arg['limit']=$total_rows;
	    $arg['start']=0;  
    	$rows=$this->order_model->get_invoice_management_list($arg); 		
		
        $array[] = array('');
        $array[] = array(
                        'Invoice No.',
                        'Invoice Date',
                        'Company',
                        'Amount',
                        'Payment Terms',
						'Payment Received',
						'Balance Payment',
						'TDS Deduction'
                        );
        
        if(count($rows) > 0)
        {
            foreach ($rows as $row) 
            {				
				$payment_type='';
				if($row->payment_type=='F')
				{
				$payment_type='Full Payment';
				}
				else if($row->payment_type=='P')
				{
				$payment_type='Part Payment';
				}
				else
				{
				$payment_type='N/A';
				}
				$array[] = array(
								($row->invoice_no)?$row->invoice_no:'-',
                                ($row->invoice_date)?date_db_format_to_display_format($row->invoice_date):'-',
                                ($row->cus_company_name)?$row->cus_company_name:'-',
                                ($row->total_payble_amount)?number_format($row->total_payble_amount,2):'-',
                                $payment_type,
								($row->payment_received)?$row->currency_type.' '.number_format($row->payment_received,2):'-',
								number_format($row->total_payble_amount-$row->payment_received,2),
								($row->po_tds_percentage>0)?$row->po_tds_percentage.'%':'N/A'
                                );
            }
        }
		
        $tmpName='Invoice-Management-List';
        $tmpDate =  date("YmdHis");
        $csvFileName = $tmpName."_".$tmpDate.".csv";

        $this->load->helper('csv');
        //query_to_csv($array, TRUE, 'Attendance_list.csv');
        array_to_csv($array, $csvFileName);
        return TRUE;
    }

	public function payment_followup()
	{
		if($this->session->userdata('po_back_url_from_detail'))
		{
			$this->session->set_userdata('po_back_url_from_detail','');
		}
		$data=array();
		$this->load->view('admin/order/payment_followup_view',$data);
	}
	// AJAX PAGINATION START
	function get_payment_followup_list_ajax()
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
	    $arg['filter_sort_by']=$this->input->get('filter_sort_by'); 
		$arg['filter_string_search']=$this->input->get('filter_string_search'); 
	   //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    if($view_type=='grid')
	    {
	    	$config['total_rows'] = $this->order_model->get_payment_followup_group_list_count($arg);
	    }
	    else
	    {
	    	// $config['total_rows'] = $this->order_model->get_payment_followup_list_trans_count($arg);
	    }	    
	    // $config['total_rows'] =10;
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
    			
	    $table = '';	
	    if($view_type=='grid')
	    {
	    	$list['rows']=$this->order_model->get_payment_followup_group_list($arg);
	    	$table = $this->load->view('admin/order/payment_followup_group_view_ajax',$list,TRUE);
	    }
	    else
	    {
	    	// $list['rows']=$this->order_model->get_payment_followup_trans_list($arg);
	    	// $table = $this->load->view('admin/order/payment_followup_transactional_view_ajax',$list,TRUE);
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

	public function download_payment_followup_csv()
    {
    	$session_data=$this->session->userdata('admin_session_data');
	    $start = 0;
	    $view_type = $this->input->get('view_type');
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
	    $arg['filter_sort_by']=$this->input->get('filter_sort_by'); 
		$arg['filter_string_search']=$this->input->get('filter_string_search'); 	    
	    $limit = $this->order_model->get_payment_followup_group_list_count($arg); 
	    $arg['limit']=$limit;
	    $arg['start']=$start;    			
	    $rows=$this->order_model->get_payment_followup_group_list($arg);  
		

        $array[] = array('');
        $array[] = array(
                        'PO ID',
                        'Company',
                        'Payment Due Date',
                        'Amount Due',
                        'Payment Received',
						'Balance Payment Due',
						'Narration'
                        );
        
        if(count($rows) > 0)
        {
            foreach ($rows as $row) 
            {				
				$array[] = array(
								($row->lead_opportunity_wise_po_id)?$row->lead_opportunity_wise_po_id:'-',
                                ($row->cus_company_name)?$row->cus_company_name:'-',
                                ($row->payment_date)?date_db_format_to_display_format($row->payment_date):'N/A',
                                number_format($row->total_amount,2),
                                number_format($row->total_payment_received,2),
								number_format($row->total_balance_payment,2),
								($row->narration)?$row->narration:'-'
                                );
            }
        }
		
        $tmpName='Payment-Followup-List';
        $tmpDate =  date("YmdHis");
        $csvFileName = $tmpName."_".$tmpDate.".csv";

        $this->load->helper('csv');
        //query_to_csv($array, TRUE, 'Attendance_list.csv');
        array_to_csv($array, $csvFileName);
        return TRUE;
    }

	public function details($lowp='')
	{
		
		if(!$this->session->userdata('po_back_url_from_detail'))
		{
			$back_url=$_SERVER['HTTP_REFERER'];
			$this->session->set_userdata('po_back_url_from_detail',$back_url);
		}
		
		$data=array();
		$session_data=$this->session->userdata('admin_session_data');
		$user_id=$session_data['user_id'];
		$data['lowp']=$lowp;
		$data['po_register_info']=$this->order_model->get_po_register_detail($lowp);
		$data['c2c_credentials']=$this->Setting_model->GetC2cCredentialsDetailsByUser($user_id);		
		$this->load->view('admin/order/order_details_view',$data);
	}

	function pro_forma_inv_send_to_buyer_by_mail_ajax()
    {        
    	$data=array();
    	$session_data = $this->session->userdata('admin_session_data');
    	$user_id = $session_data['user_id'];
    	$lowp=$this->input->post('lowp');
        $data['po_register_info']=$this->order_model->get_po_register_detail($lowp); 
        $data['po_pro_forma_inv_info']=$this->order_model->get_po_pro_forma_invoice_detail_lowpo_wise($lowp);
        $data['company']=$this->Setting_model->GetCompanyData();
        $pro_forma_inv_no=$data['po_pro_forma_inv_info']->pro_forma_no; 
        $company_name=$data['company']['name'];
        $data['user_list']=$this->user_model->GetUserListAll('');
        $data['mail_subject'] ="Proforma Invoice (".$pro_forma_inv_no.") from ".$company_name;
   		$data['lowp']=$lowp;
        // $lead_id = $data['po_register_info']->lead_id;
        // $data['lead_data']=$this->lead_model->GetLeadData($lead_id);
        $data['quick_reply_comments']=$this->pre_define_comment_model->GetLeadUpdatePreDefineComments($user_id,'LU');
		$data['quick_reply_count']=count($data['quick_reply_comments']);		
        echo $this->load->view('admin/order/pro_forma_inv_send_html_view',$data,true);
    }

	function pro_forma_inv_send_to_buyer_by_mail_ajax_old()
  	{
  		$list=array();
        $lowp=$this->input->post('lowp');
        $list['po_register_info']=$this->order_model->get_po_register_detail($lowp); 
        $list['po_pro_forma_inv_info']=$this->order_model->get_po_pro_forma_invoice_detail_lowpo_wise($lowp);
            
        
        $list['company']=$this->Setting_model->GetCompanyData();	
        
        $pro_forma_inv_no=$list['po_pro_forma_inv_info']->pro_forma_no; 
        $company_name=$list['company']['name'];


        $list['user_list']=$this->user_model->GetUserListAll('');
        $list['mail_subject'] ="Proforma Invoice (".$pro_forma_inv_no.") from ".$company_name;
   		$list['lowp']=$lowp;
    	$html = $this->load->view('admin/order/pro_forma_inv_send_html_view',$list,TRUE);		

        $data =array(
                    "html"=>$html
                    );
        $this->output->set_content_type('application/json');
        echo json_encode($data);
    }

    function pfi_send_to_buyer_by_mail_confirm_ajax()
    {
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$company=$this->Setting_model->GetCompanyData();
			$session_data=$this->session->userdata('admin_session_data');
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$lowp=$this->input->post('lowp');
			$is_company_brochure_attached_with_pfi=($this->input->post('is_company_brochure_attached_with_pfi'))?'Y':'N';
	        $to_mail_arr=$this->input->post('to_mail');
	        $to_mail_str=implode(",", $to_mail_arr);
 			$cc_mail_tmp_arr=$this->input->post('cc_mail');
 			$reply_email_body=$this->input->post('reply_email_body');
 			
 			// echo $lowp;
 			// echo'<br>';
 			// echo $is_company_brochure_attached_with_pfi;
 			// echo'<br>';
 			// print_r($to_mail_arr);
 			// echo'<br>';
 			// print_r($cc_mail_tmp_arr);
 			// echo'<br>';
 			// echo $reply_email_body;
 			// die();
        	

	        if($to_mail_str)
	        {
	        	$po_register_info=$this->order_model->get_po_register_detail($lowp);
				$assigned_user_id=$po_register_info->assigned_user_id;			
				$lead_id=$po_register_info->lid;
				$m_email=get_manager_and_skip_manager_email_arr($assigned_user_id);
				$pro_forma_no=$po_register_info->pro_forma_no;
				$company_name=$company['name'];
		        // --------------------
		        // to mail assign logic
		        $to_mail_assign='';
		        $to_mail=$to_mail_str;
		        // to mail assign logic
		        // --------------------
		        $cc_mail_arr=array();
		        $self_cc_mail=get_value("email","user","id=".$assigned_user_id);
		        $update_by_name=get_value("name","user","id=".$assigned_user_id);
		        // --------------------
		        // cc mail assign logic
		        array_push($cc_mail_arr, $self_cc_mail);
			    array_push($cc_mail_arr, $m_email['manager_email']);
			    array_push($cc_mail_arr, $m_email['skip_manager_email']);
			        
			    $cc_mail_arr=array_unique(array_merge($cc_mail_tmp_arr,$cc_mail_arr));			        
		        $cc_mail_str='';
		        $cc_mail_str=implode(",", $cc_mail_arr);			        
		        // cc mail assign logic
		        // --------------------
				$mail_attached_arr=array();        	
				if($to_mail!='')
				{	
					$po_proforma_info=$this->order_model->get_po_proforma_detail_lowpo_wise($lowp);
					// ============================
					// Quotation Mail
					// START
					$attach_file_path='';
					if($po_proforma_info->proforma_type=='C')
					{
						if($po_proforma_info->po_custom_proforma)
						{
							$attach_file_path="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/".$po_proforma_info->po_custom_proforma;
						}
					}
					else
					{
						$attach_file_path=$this->generate_pro_forma_inv_pdf($lowp,'F');
					}
		        	
		        	if($attach_file_path)
		        	{
		        		array_push($mail_attached_arr, $attach_file_path);
		        	}

		        	// $attach_file_path=$this->generate_invoice_pdf($lowp,'F');
		        	// array_push($mail_attached_arr, $attach_file_path);
			        $template_str = '';
			        $e_data = array();				        
					// ------------------------------
		        	// company brochure attachment
		        	if($is_company_brochure_attached_with_pfi=='Y')
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
					
			        $assigned_to_user_data=$this->user_model->get_employee_details($assigned_user_id);
					$e_data='';
			        // $template_str = $this->load->view('admin/email_template/template/quotation_sent_view', $e_data, true);
			        $template_str=$reply_email_body;
			        // Developer mail start
			        $this->load->library('mail_lib');
			        $mail_data = array();
			        $mail_data['from_mail']     = $self_cc_mail;
		        	$mail_data['from_name']     = $update_by_name;
			        $mail_data['to_mail']       = $to_mail;		        
			        $mail_data['cc_mail']       = $cc_mail_str;
					$pfi_mail_subject=($this->input->post('pfi_mail_subject'))?$this->input->post('pfi_mail_subject'):"Proforma Invoice ( ".$pro_forma_no." ) from ".$company_name;
			        $mail_data['subject']       = $pfi_mail_subject;
			        $mail_data['message']       = $template_str;
			        $mail_data['attach']        = $mail_attached_arr;

			        $mail_return = $this->mail_lib->send_mail($mail_data);
			        // Quotation Mail
			        // -----------------------------------------------------
				}
		        $status='success';
				$msg="Proforma invoice has been sent successfully";
				
				// create history
				$commnt="Proforma invoice(".$po_register_info->pro_forma_no.") sent to client by mail";
				$ip=$_SERVER['REMOTE_ADDR'];
				$date=date("Y-m-d h:i:s");	
				$update_by=$this->session->userdata['admin_session_data']['user_id'];
				$comment_title=PRO_FORMA_INV_SENT_TO_CLIENT;
				$historydata=array(
				'title'=>$comment_title,
				'lead_id'=>$lead_id,
				'comment'=>$commnt,
				'sent_mail_quotation_to_client_from_mail'=>$session_data['email'],
				'sent_mail_quotation_to_client_from_name'=>$session_data['name'],
				'mail_subject_of_sent_quotation_to_client'=>$pfi_mail_subject,
				'create_date'=>$date,
				'user_id'=>$update_by,
				'ip_address'=>$ip
				);
				$this->history_model->CreateHistory($historydata);			
				
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


    function invoice_send_to_buyer_by_mail_ajax()
    {        
    	$data=array();
    	$session_data = $this->session->userdata('admin_session_data');
    	$user_id = $session_data['user_id'];
        $lowp=$this->input->post('lowp');
        $data['po_register_info']=$this->order_model->get_po_register_detail($lowp);
        $data['po_inv_info']=$this->order_model->get_po_invoice_detail_lowpo_wise($lowp);
            
        
        $data['company']=$this->Setting_model->GetCompanyData();	
        
        $inv_no=$data['po_inv_info']->invoice_no; 
        $company_name=$data['company']['name'];


        $data['user_list']=$this->user_model->GetUserListAll('');
        $data['mail_subject'] ="Invoice (".$inv_no.") from ".$company_name;
   		$data['lowp']=$lowp;
        // $lead_id = $data['po_register_info']->lead_id;
        // $data['lead_data']=$this->lead_model->GetLeadData($lead_id);
        $data['quick_reply_comments']=$this->pre_define_comment_model->GetLeadUpdatePreDefineComments($user_id,'LU');
		$data['quick_reply_count']=count($data['quick_reply_comments']);		
        echo $this->load->view('admin/order/invoice_send_html_view',$data,true);
    }
    function invoice_send_to_buyer_by_mail_ajax_old()
  	{
  		$list=array();
        $lowp=$this->input->post('lowp');
        $list['po_register_info']=$this->order_model->get_po_register_detail($lowp);
        $list['po_inv_info']=$this->order_model->get_po_invoice_detail_lowpo_wise($lowp);
            
        
        $list['company']=$this->Setting_model->GetCompanyData();	
        
        $inv_no=$list['po_inv_info']->invoice_no; 
        $company_name=$list['company']['name'];


        $list['user_list']=$this->user_model->GetUserListAll('');
        $list['mail_subject'] ="Invoice (".$inv_no.") from ".$company_name;
   		$list['lowp']=$lowp;
    	$html = $this->load->view('admin/order/invoice_send_html_view',$list,TRUE);		

        $data =array(
                    "html"=>$html
                    );
        $this->output->set_content_type('application/json');
        echo json_encode($data);
    }

    function invoice_send_to_buyer_by_mail_confirm_ajax()
    {
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$company=$this->Setting_model->GetCompanyData();
			$session_data=$this->session->userdata('admin_session_data');
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$lowp=$this->input->post('lowp');
			$is_company_brochure_attached_with_invoice=($this->input->post('is_company_brochure_attached_with_invoice'))?'Y':'N';
	        $to_mail_arr=$this->input->post('to_mail');
	        $to_mail_str=implode(",", $to_mail_arr);
 			$cc_mail_tmp_arr=$this->input->post('cc_mail');
 			$reply_email_body=$this->input->post('reply_email_body');
 			$po_inv_info=$this->order_model->get_po_invoice_detail_lowpo_wise($lowp);
 			// echo $lowp;
 			// echo'<br>';
 			// echo $is_company_brochure_attached_with_invoice;
 			// echo'<br>';
 			// print_r($to_mail_arr);
 			// echo'<br>';
 			// print_r($cc_mail_tmp_arr);
 			// echo'<br>';
 			// echo $reply_email_body;
 			// die();
 			
	        if($to_mail_str)
	        {
	        	$po_register_info=$this->order_model->get_po_register_detail($lowp);
	        	$invoice_no=$po_register_info->invoice_no;
	        	$company_name=$company['name'];
				$assigned_user_id=$po_register_info->assigned_user_id;			
				$lead_id=$po_register_info->lid;
				$m_email=get_manager_and_skip_manager_email_arr($assigned_user_id);
		        // --------------------
		        // to mail assign logic
		        $to_mail_assign='';
		        $to_mail=$to_mail_str;
		        // to mail assign logic
		        // --------------------
		        $cc_mail_arr=array();
		        $self_cc_mail=get_value("email","user","id=".$assigned_user_id);
		        $update_by_name=get_value("name","user","id=".$assigned_user_id);
		        // --------------------
		        // cc mail assign logic
		        array_push($cc_mail_arr, $self_cc_mail);
			    array_push($cc_mail_arr, $m_email['manager_email']);
			    array_push($cc_mail_arr, $m_email['skip_manager_email']);
			        
			    $cc_mail_arr=array_unique(array_merge($cc_mail_tmp_arr,$cc_mail_arr));			        
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
					$attach_file_path='';
					if($po_inv_info->invoice_type=='C')
					{
						if($po_inv_info->po_custom_invoice)
						{
							$attach_file_path="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/".$po_inv_info->po_custom_invoice;
						}
					}
					else
					{
						$attach_file_path=$this->generate_invoice_pdf($lowp,'F');
					}
		        	
		        	if($attach_file_path)
		        	{
		        		array_push($mail_attached_arr, $attach_file_path);
		        	}
		        	
			        $template_str = '';
			        $e_data = array();				        
					// ------------------------------
		        	// company brochure attachment
		        	if($is_company_brochure_attached_with_invoice=='Y')
		        	{
		        		
			        	if(isset($company['brochure_file']))
			        	{
			        		$company_brochure="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/brochure/".$company['brochure_file'];
			        		array_push($mail_attached_arr, $company_brochure);
			        	}
		        	}
		        	
		        	// company brochure attachment
		        	// ------------------------------
					
			        $assigned_to_user_data=$this->user_model->get_employee_details($assigned_user_id);
					$e_data='';
			        // $template_str = $this->load->view('admin/email_template/template/quotation_sent_view', $e_data, true);
			        $template_str=$reply_email_body;
			        // Developer mail start
			        $this->load->library('mail_lib');
			        $mail_data = array();
			        $mail_data['from_mail']     = $self_cc_mail;
		        	$mail_data['from_name']     = $update_by_name;
			        $mail_data['to_mail']       = $to_mail;		        
			        $mail_data['cc_mail']       = $cc_mail_str;
					$invoice_mail_subject=($this->input->post('invoice_mail_subject'))?$this->input->post('invoice_mail_subject'):"Invoice (".$invoice_no.") from ".$company_name;
			        $mail_data['subject']       = $invoice_mail_subject;
			        $mail_data['message']       = $template_str;
			        $mail_data['attach']        = $mail_attached_arr;

			        $mail_return = $this->mail_lib->send_mail($mail_data);
			        // Quotation Mail
			        // -----------------------------------------------------
				}
		        $status='success';
				$msg="Invoice has been sent successfully";
				
				// create history
				$commnt="invoice(".$po_register_info->invoice_no.") sent to client by mail";
				$ip=$_SERVER['REMOTE_ADDR'];
				$date=date("Y-m-d h:i:s");	
				$update_by=$this->session->userdata['admin_session_data']['user_id'];
				$comment_title=INVOICE_SENT_TO_CLIENT;
				$historydata=array(
				'title'=>$comment_title,
				'lead_id'=>$lead_id,
				'comment'=>$commnt,
				'sent_mail_quotation_to_client_from_mail'=>$session_data['email'],
				'sent_mail_quotation_to_client_from_name'=>$session_data['name'],
				'mail_subject_of_sent_quotation_to_client'=>$invoice_mail_subject,
				'create_date'=>$date,
				'user_id'=>$update_by,
				'ip_address'=>$ip
				);
				$this->history_model->CreateHistory($historydata);			
				
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

    function rander_po_pfi_product_view_ajax()
    {        
    	$data=array();
    	$lowp = $this->input->post('lowp');        
        $data['currency_list']=$this->lead_model->GetCurrencyList();
        $data['company']=get_company_profile();
        $data['lowp']=$lowp;
        $data['step']=$step;
        $po_register_info=$this->order_model->get_po_register_detail($lowp);
        $data['po_register_info']=$po_register_info;
        $data['po_pro_forma_inv_info']=$this->order_model->get_po_pro_forma_invoice_detail_lowpo_wise($lowp);
        $data['prod_list']=$this->order_model->GetProFormaInvoiceProductList($lowp);
        $data['additional_charges_list']=$this->order_model->GetProFormaInvoiceAdditionalCharges($lowp);        
        $data['unit_type_list']=$this->Product_model->GetUnitList();             
        $data['po_payment_method']=$this->order_model->po_payment_method_list();

        $result['html']=$this->load->view('admin/order/updated_pfi_product_list_ajax',$data,true);


        $product_list=$data['prod_list'];
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
		// -------------------------------		
		
		// =======================================
		// CALCULATE ADDITIONAL PRICE		
		
		$additional_charges_list=$data['additional_charges_list'];
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
        $result['total_sale_price'] = number_format($row_final_price,2);
		$result["total_deal_value"]=number_format($sub_total,2);

		$result["total_price"]=number_format($total_price,2);
		$result["total_discount"]=number_format($total_discounted_price,2);
		$result["total_tax"]=$total_tax_price;
		$result["grand_total_round_off"]=number_format(round($sub_total),2);
		$result["number_to_word_final_amount"]=number_to_word(round($sub_total));
        echo json_encode($result);
        exit(0); 
    }

    function rander_po_inv_product_view_ajax()
    {        
    	$data=array();
    	$lowp = $this->input->post('lowp');        
        $data['currency_list']=$this->lead_model->GetCurrencyList();
        $data['company']=get_company_profile();
        $data['lowp']=$lowp;
        $data['step']=$step;
        $po_register_info=$this->order_model->get_po_register_detail($lowp);
        $data['po_register_info']=$po_register_info;
        $data['po_inv_info']=$this->order_model->get_po_invoice_detail_lowpo_wise($lowp);  
    	$data['prod_list']=$this->order_model->GetInvoiceProductList($lowp);
    	$data['additional_charges_list']=$this->order_model->GetInvoiceAdditionalCharges($lowp);

        $data['unit_type_list']=$this->Product_model->GetUnitList();             
        $data['po_payment_method']=$this->order_model->po_payment_method_list();

        $result['html']=$this->load->view('admin/order/updated_inv_product_list_ajax',$data,true);


        $product_list=$data['prod_list'];
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
		// -------------------------------		
		
		// =======================================
		// CALCULATE ADDITIONAL PRICE		
		
		$additional_charges_list=$data['additional_charges_list'];
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
        $result['total_sale_price'] = number_format($row_final_price,2);
		$result["total_deal_value"]=number_format($sub_total,2);

		$result["total_price"]=number_format($total_price,2);
		$result["total_discount"]=number_format($total_discounted_price,2);
		$result["total_tax"]=$total_tax_price;
		$result["grand_total_round_off"]=number_format(round($sub_total),2);
		$result["number_to_word_final_amount"]=number_to_word(round($sub_total));
        echo json_encode($result);
        exit(0); 
    }

    function rander_payment_view_popup_ajax()
    {        
    	$data=array();
    	$lowp = $this->input->post('lowp');  
		$action_type = ($this->input->post('action_type'))?$this->input->post('action_type'):''; 
		$data['action_type']=$action_type;       
        $data['currency_list']=$this->lead_model->GetCurrencyList();
        $data['company']=get_company_profile();
        $data['lowp']=$lowp;
        $data['step']=$step;
        $po_register_info=$this->order_model->get_po_register_detail($lowp);
        $data['po_register_info']=$po_register_info;
        $data['po_pt_id']=$po_register_info->po_pt_id;
        $data['unit_type_list']=$this->Product_model->GetUnitList();             
        $data['po_payment_method']=$this->order_model->po_payment_method_list();
        $result['html']=$this->load->view('admin/order/payment_view_ajax',$data,true);		
        echo json_encode($result);
        exit(0); 
    }

    function rander_payment_ledger_view_ajax()
    {        
    	$data=array();
    	$lowp = $this->input->post('lowp');
    	$data['lowp']=$lowp;
        $po_register_info=$this->order_model->get_po_register_detail($lowp);
        $data['po_register_info']=$po_register_info;
        $data['po_pt_id']=$po_register_info->po_pt_id;  
        $data['get_payment_received']=$this->order_model->get_payment_received($lowp);      
        $result['html']=$this->load->view('admin/order/payment_ledger_view_ajax',$data,true);		
        echo json_encode($result);
        exit(0); 
    }

    function add_payment_ledger_ajax()
    {
    	$lowp=$this->input->post('lowp');
    	$date=$this->input->post('date');
    	$currency_type=$this->input->post('currency_type');   	
    	
    	$amount=$this->input->post('amount');
    	$payment_mode_id=$this->input->post('payment_mode_id');
    	$narration=$this->input->post('narration');

    	$post_arr=array(
    		'lead_opportunity_wise_po_id'=>$lowp,
			'received_date'=>date_display_format_to_db_format($date),
			'currency_type'=>$currency_type,
			'amount'=>$amount,
			'payment_mode_id'=>$payment_mode_id,
			'narration'=>$narration,
			'created_at'=>date("Y-m-d H:i:s")
    		);
    	// print_r($post_arr); die();
    	$r=$this->order_model->add_payment_received($post_arr);
    	if($r)
    	{
    		// -----------------------------------------
    		// payment received update in payment terms
    		$pt_list=$this->order_model->get_payment_terms_by_lowp($lowp);
	    	$pr_total_amount=$this->order_model->get_total_amount_received_by_lowp($lowp);
	    	$total_amount_received=$pr_total_amount->total_amount_received;
	    	
	    	
	    	if(count($pt_list))
	    	{
	    		$t_id=$pt_list[0]->t_id;
		    	$total_amount=$pt_list[0]->total_amount;
		    	if($t_id>0)
	    		{
	    			$pt_post=array(
						'total_payment_received'=>$total_amount_received,
						'total_balance_payment'=>($total_amount-$total_amount_received)
						);	    				
	    			$this->order_model->updatePoPaymentTerms($pt_post,$t_id);
	    		}
	    		$i=0;
	    		foreach($pt_list AS $pt)
	    		{
	    			if($i==0)
	    			{
	    				$u_post=array(
						'payment_received'=>'0',
						'balance_payment'=>'0'
						);
	    				
	    				$this->order_model->updatePoPaymentTermDetailByTermId($u_post,$pt->t_id);
	    			}	    			
	    			$td_id=$pt->td_id; 
	    			$amount=$pt->amount;
	    			$payment_received=$pt->payment_received;
	    			$balance_payment=$pt->balance_payment;
	    			
	    			if(($amount<=$total_amount_received) && $total_amount_received>0)
	    			{
	    				$new_payment_received=$amount;
	    				$new_balance_payment=0;
	    				$total_amount_received=($total_amount_received-$amount);

	    				

	    				$post=array(
	    							'payment_received'=>$new_payment_received,
	    							'balance_payment'=>$new_balance_payment
	    							);
	    				
	    				$this->order_model->updatePoPaymentTermDetailById($post,$td_id);	
	    			}
	    			else if(($amount>$total_amount_received) && $total_amount_received>0)
	    			{
	    				$new_payment_received=$total_amount_received;
	    				$new_balance_payment=($amount-$total_amount_received); 
	    				$total_amount_received=0;

	    				$post=array(
	    							'payment_received'=>$new_payment_received,
	    							'balance_payment'=>$new_balance_payment
	    							);
	    				
	    				$this->order_model->updatePoPaymentTermDetailById($post,$td_id);
	    			}
	    			else
	    			{
	    				$post=array(
	    							'balance_payment'=>$amount
	    							);
	    				
	    				$this->order_model->updatePoPaymentTermDetailById($post,$td_id);
	    			}
	    			$i++;			
	    		}
	    	}
	    	// payment received update in payment terms
	    	// -----------------------------------------
    		
    		$result['status']='success';		
    	}
    	else
    	{
    		$result['status']='fail';		
    	}
    	$result['msg']='';    	
        echo json_encode($result);
        exit(0);
    }
    function del_payment_ledger_ajax()
    {
    	$lowp=$this->input->post('lowp');
    	$id=$this->input->post('id');
    	$r=$this->order_model->del_payment_received($id);
    	if($r)
    	{
    		// -----------------------------------------
    		// payment received update in payment terms
    		$pt_list=$this->order_model->get_payment_terms_by_lowp($lowp);
	    	$pr_total_amount=$this->order_model->get_total_amount_received_by_lowp($lowp);
	    	$total_amount_received=$pr_total_amount->total_amount_received;
	    	
	    	
	    	if(count($pt_list))
	    	{
	    		$t_id=$pt_list[0]->t_id;
		    	$total_amount=$pt_list[0]->total_amount;
		    	if($t_id>0)
	    		{
	    			$pt_post=array(
						'total_payment_received'=>$total_amount_received,
						'total_balance_payment'=>($total_amount-$total_amount_received)
						);	    				
	    			$this->order_model->updatePoPaymentTerms($pt_post,$t_id);
	    		}
	    		$i=0;
	    		foreach($pt_list AS $pt)
	    		{
	    			if($i==0)
	    			{
	    				$u_post=array(
						'payment_received'=>'0',
						'balance_payment'=>'0'
						);
	    				
	    				$this->order_model->updatePoPaymentTermDetailByTermId($u_post,$pt->t_id);
	    			}	    			
	    			$td_id=$pt->td_id; 
	    			$amount=$pt->amount;
	    			$payment_received=$pt->payment_received;
	    			$balance_payment=$pt->balance_payment;
	    			
	    			if(($amount<=$total_amount_received) && $total_amount_received>0)
	    			{
	    				$new_payment_received=$amount;
	    				$new_balance_payment=0;
	    				$total_amount_received=($total_amount_received-$amount);

	    				

	    				$post=array(
	    							'payment_received'=>$new_payment_received,
	    							'balance_payment'=>$new_balance_payment
	    							);
	    				
	    				$this->order_model->updatePoPaymentTermDetailById($post,$td_id);	
	    			}
	    			else if(($amount>$total_amount_received) && $total_amount_received>0)
	    			{
	    				$new_payment_received=$total_amount_received;
	    				$new_balance_payment=($amount-$total_amount_received); 
	    				$total_amount_received=0;

	    				$post=array(
	    							'payment_received'=>$new_payment_received,
	    							'balance_payment'=>$new_balance_payment
	    							);
	    				
	    				$this->order_model->updatePoPaymentTermDetailById($post,$td_id);
	    			}
	    			else
	    			{
	    				$post=array(
	    							'balance_payment'=>$amount
	    							);
	    				
	    				$this->order_model->updatePoPaymentTermDetailById($post,$td_id);
	    			}
	    			$i++;			
	    		}
	    	}
	    	// payment received update in payment terms
	    	// -----------------------------------------
    		$result['status']='success';		
    	}
    	else
    	{
    		$result['status']='fail';		
    	}
    	$result['msg']='';    	
        echo json_encode($result);
        exit(0);
    }

    function rander_po_preview_popup()
    {
        $data=array();	
        $lowp=$this->input->post('lowp');	
		$data['lowp']=$lowp;
		$po_register_info=$this->order_model->get_po_register_detail($lowp);
		$data['po_register_info']=$po_register_info;
		echo $this->load->view('admin/order/rander_po_preview_popup_view',$data,TRUE);
    }

    function rander_pfi_preview_popup()
    {
        $data=array();	
        $lowp=$this->input->post('lowp');	
		$data['lowp']=$lowp;
		$po_register_info=$this->order_model->get_po_register_detail($lowp);
		$data['po_register_info']=$po_register_info;
		$data['po_pro_forma_inv_info']=$this->order_model->get_po_pro_forma_invoice_detail_lowpo_wise($lowp);
		echo $this->load->view('admin/order/rander_pfi_preview_popup_view',$data,TRUE);
    }

    function rander_inv_preview_popup()
    {
        $data=array();	
        $lowp=$this->input->post('lowp');	
		$data['lowp']=$lowp;
		$po_register_info=$this->order_model->get_po_register_detail($lowp);
		$data['po_register_info']=$po_register_info;
		$data['po_inv_info']=$this->order_model->get_po_invoice_detail_lowpo_wise($lowp);
		echo $this->load->view('admin/order/rander_inv_preview_popup_view',$data,TRUE);
    }

    public function del_po_file_ajax()
	{
		$lowp=$this->input->post('lowp');		
		$update_data=array(
						'file_path'=>'',
						'file_name'=>''
						);
		$del_data=$this->order_model->updateLeadOppWisePo($update_data,$lowp);

		if($del_data==true)
        	$result["status"] = 'success';
        else
        	$result["status"] = 'fail';      
        echo json_encode($result);
        exit(0); 
	}

	function update_po_upload_post_ajax()
    {	
    	$redirect_uri_str='';
    	$owp_id=$this->input->post('lead_opportunity_wise_po_id');
    	if($owp_id=='')
    	{
    		$status_str='fail';
	        $result['status']=$status_str;
	        $result['msg']='lowp id missing.';
	        echo json_encode($result);
	        exit(0);
    	}
    	$po_register_info=$this->order_model->get_po_register_detail($owp_id);
    	
    	$lead_opp_id = $po_register_info->lead_opportunity_id;    	
    	// -----------------------
    	// RENEWAL    	
    	/*$is_renewal_available=($this->input->post('is_renewal_available'))?'Y':'N';
    	
    	if($is_renewal_available=='Y')
    	{
    		$renewal_date=date_display_format_to_db_format($this->input->post('renewal_date'));
    		$renewal_follow_up_date=date_display_format_to_db_format($this->input->post('renewal_follow_up_date'));
    		$renewal_requirement = $this->input->post('renewal_requirement');

    		$renewal_product_str=$this->Opportunity_product_model->get_product_str($lead_opp_id);   	
    		$renewal_id = $this->input->post('renewal_id');
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
    		  		
    	}*/
    	// RENEWAL
    	// -----------------------    	
        $lead_id = $po_register_info->lead_id;
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

		$quotation_id=$po_register_info->q_id;
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
		'po_tds_percentage'=>$po_tds_percentage
		);
		$this->Opportunity_model->UpdateOportunityWisePo($post_data,$owp_id);


		// UPDATE LEAD STAGE/STATUS
		//    $update_lead_data = array(
		//    	'current_stage_id' =>'4',
		// 	'current_stage' =>'DEAL WON',
		// 	'current_stage_wise_msg' =>'',
		// 	'current_status_id'=>'2',
		// 	'current_status'=>'HOT',
		// 	'is_hotstar'=>'Y',
		// 	'modify_date'=>date("Y-m-d")
		// );								
		//$this->lead_model->UpdateLead($update_lead_data,$lead_id);
		// Insert Stage Log

		// $is_nego_exist=$this->lead_model->is_stage_exist_in_log($lead_id,9);
		// if($is_nego_exist=='N')
		// {
		// 	// STAGE NEGOTIATION
		// 	$stage_post_data=array(
		// 			'lead_id'=>$lead_id,
		// 			'stage_id'=>'9',
		// 			'stage'=>'NEGOTIATION',
		// 			'stage_wise_msg'=>'',
		// 			'create_datetime'=>date("Y-m-d H:i:s")
		// 		);
		// 	$this->lead_model->CreateLeadStageLog($stage_post_data);
		// }
		
		// STAGE DEAL WON	
		//      $stage_post_data=array(
		// 	'lead_id'=>$lead_id,
		// 	'stage_id'=>'4',
		// 	'stage'=>'DEAL WON',
		// 	'stage_wise_msg'=>'',
		// 	'create_datetime'=>date("Y-m-d H:i:s")
		// );
		//      $this->lead_model->CreateLeadStageLog($stage_post_data);

        // Insert Status Log
		//      $status_post_data=array(
			// 	'lead_id'=>$lead_id,
			// 	'status_id'=>'2',
			// 	'status'=>'HOT',
			// 	'create_datetime'=>date("Y-m-d H:i:s")
			// );
		//      $this->lead_model->CreateLeadStatusLog($status_post_data);


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
		$comment_title=QUOTATION_WISE_PO_UPLOAD_UPDATED;
		$q_title=get_value("opportunity_title","lead_opportunity","id=".$lead_opp_id);
		// $link ='<a href='.base_url().$this->session->userdata['admin_session_data']['lms_url'].'/lead/download_po/'.$owp_id.'><b>Download PO</b> <i class="fa fa-download" aria-hidden="true"></i></a>';
		$link='';
		$comment='Updated PO for quotation ('.$q_title.') '.$link;
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
		
        $status_str='success';
        $result['status']=$status_str;
        $result['lead_opportunity_wise_po_id']=$owp_id;
        $result['msg']='PO successfully updated.';
        $result['l_opp_id']=$lead_opp_id;
        $result['lid']=$lead_id;
        echo json_encode($result);
        exit(0);
    }

    public function create_po_update_comment_ajax()
	{		
		$lowp=$this->input->post('lowp_id');
		$po_register_info=$this->order_model->get_po_register_detail($lowp);
		
		$lead_id=$po_register_info->lead_id;
		$lead_info=$this->lead_model->GetLeadData($lead_id);
		
		$description=$this->input->post('po_uc_comment');
		//-------------- HISTORY ----------------------------------
		$history_text = '';
		$update_by=$this->session->userdata['admin_session_data']['user_id'];
		$ip_addr=$_SERVER['REMOTE_ADDR'];		
		$comment_title=PO_UPDATE_COMMENT;
		$history_text .= addslashes($description);
		// --------------------------------------------------------
		
		
		// LEAD ATTACH FILE UPLOAD		
		$attach_filename='';
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
        }	
		
		// end
		// =====================================================	

		
		
		

		//-------------- HISTORY ----------------------------------		
		$historydata=array(
						'title'=>$comment_title,
						'lead_id'=>$lead_id,
						'comment'=>$history_text,
						'attach_file'=>$attach_filename_with_path,
						'create_date'=>date("Y-m-d H:i:s"),
						'user_id'=>$update_by,
						'ip_address'=>$ip_addr
						);
		$this->history_model->CreateHistory($historydata);	
		// ----------------------------------------------------------	

		$data =array (
					   "status"=>'success',
					   "msg"=>'The po comment successfully updated.'
					);
		$this->output->set_content_type('application/json');
		echo json_encode($data);
		exit(0);		
	}

	function rander_dislike_stage_view_ajax()
    {        
    	$data=array();
    	$lowp=$this->input->post('lowp');
        // $session_data = $this->session->userdata('admin_session_data');
		// $user_id = $session_data['user_id'];
		// $data['user_id']=$user_id;
        // $data['user_list']=$this->user_model->GetUserListAll('');
		$data['lowp']=$lowp;		
		// $data['lead_data']=$this->lead_model->GetLeadData($lead_id);
		$data['regret_reason_list']=get_regret_reason();
		// $data['opportunity_list']=$this->opportunity_model->GetSentToClientStatusOpportunityListAll($lead_id);
        echo $this->load->view('admin/order/rander_dislike_stage_view_ajax',$data,true);
    }

    public function cancel_po_ajax()
	{ 
		//$list=array();
		$lowp=$this->input->post('lowp');
		$po_register_info=$this->order_model->get_po_register_detail($lowp);
		$lead_id=$po_register_info->lead_id;
		$lead_regret_reason_id=$this->input->post('lead_regret_reason_id');
		$lead_regret_reason=$this->input->post('lead_regret_reason');

		if($lead_id!="" && $lead_regret_reason_id!="" && $lead_regret_reason!="")
		{	

			$session_data = $this->session->userdata('admin_session_data');
			$user_id = $session_data['user_id'];
			//$list['user_id']=$user_id;
			$lead_info=$this->lead_model->GetLeadData($lead_id);		
			$company=get_company_profile();
			$assigned_user_id=$lead_info->assigned_user_id;
			$customer=$this->customer_model->GetCustomerData($lead_info->customer_id);
			$user_data=$this->user_model->get_employee_details($assigned_user_id);		
			//-------------- HISTORY ----------------------------------
			$history_text = '';
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			$ip_addr=$_SERVER['REMOTE_ADDR'];		
			$comment_title=LEAD_GENERAL_UPDATE;
			$history_text .= addslashes($lead_regret_reason);
			// -------------------------------
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
			else if($tmp_current_stage_id==9)
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
				'current_status_id'=>$changed_status_id,
				'current_status'=>$changed_status,
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
			$this->lead_model->CreateLeadStatusLog($status_post_data);


			$history_text .= '<br> Stage changed from <b>'.$lead_info->current_stage.'</b> to <b>'.$changed_stage.'</b>';
			$history_text .= '<br> Status changed from <b>'.$lead_info->current_status.'</b> to <b>'.$changed_status.'</b>';
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
			$regret_this_lead_mail_subject=($this->input->post('regret_this_lead_mail_subject'))?$this->input->post('regret_this_lead_mail_subject'):'Enquiry # '.$lead_id.' - Query/Update from your A/C Manager';

			$e_data=array();
			//$user_id=$this->session->userdata['admin_session_data']['user_id'];		

			$e_data['company']=$company;
			$e_data['assigned_to_user']=$user_data;
			$e_data['customer']=$customer;
			$e_data['lead_info']=$lead_info;
			$template_str = $this->load->view('admin/email_template/template/enquiry_regret_view', $e_data, true);			    

			//$to_mail=$user_data['email'];                	
			//$to_mail=$customer->email;
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
			//$this->load->library('mail_lib');
			if($to_mail!='' && $email_forwarding_setting['is_mail_send']=='Y')
			{
				$mail_data = array();
				//$mail_data['from_mail']     = $company['email1'];
				//$mail_data['from_name']     = $company['name'];
				$mail_data['from_mail']     = $session_data['email'];
				$mail_data['from_name']     = $session_data['name'];
				$mail_data['to_mail']       = $to_mail;        
				$mail_data['cc_mail']       = $cc_mail;               
				$mail_data['subject']       = $regret_this_lead_mail_subject;
				$mail_data['message']       = $template_str;
				$mail_data['attach']        = array();
				$this->mail_lib->send_mail($mail_data);
			}
			
			// MAIL SEND
			// =================================

			$regret_mail_from_mail=$session_data['email'];
			$regret_mail_from_name=$session_data['name'];
			//---------- HISTORY --------------	
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
			// --------------------------------
			
			// ------------------------------
			$update_by_name=get_value("name","user","id=".$update_by);
			$updatePo=array(
			'is_cancel'=>'Y',
			'cancelled_date'=>date('Y-m-d'),
			'cancelled_by'=>$update_by_name,
			'cancelled_by_id'=>$update_by
			);
			$this->order_model->updateLeadOppWisePo($updatePo,$lowp);

			
			$comment_title='';
			$history_text = '';
			// $update_by=$this->session->userdata['admin_session_data']['user_id'];
			// $update_by_name=get_value("name","user","id=".$update_by);
			// $ip_addr=$_SERVER['REMOTE_ADDR'];
			$comment_title=PO_CANCELED;	
			// $history_text .= '&nbsp;|&nbsp;';
			$history_text .= 'PO No.: '.$po_register_info->po_number;
			$history_text .= '&nbsp;|&nbsp;';
			$history_text .= 'PO Date: '.date_db_format_to_display_format($po_register_info->po_date);
			$history_text .= '&nbsp;|&nbsp;';
			$history_text .= 'Lead ID: '.$po_register_info->lid;
			if($lead_regret_reason)
			{
				$history_text .= '&nbsp;|&nbsp;';
				$history_text .= '<br> PO cancel Reasons: '.$lead_regret_reason;
			}
			$history_text .= '&nbsp;|&nbsp;';
			$history_text .= 'The status of Lead Id ('.$po_register_info->lid.') has been changed from Deal Won to Deal Lost.';
			if($po_register_info->pro_forma_no)
			{
				$history_text .= '&nbsp;|&nbsp;';
				$history_text .= 'Proforma Invoice('.$po_register_info->pro_forma_no.') cancelled.';
			}
			if($po_register_info->invoice_no)
			{
				$history_text .= '&nbsp;|&nbsp;';
				$history_text .= 'Invoice('.$po_register_info->invoice_no.') cancelled.';
			}
			if($po_register_info->po_pt_id)
			{
				$history_text .= '&nbsp;|&nbsp;';
				$history_text .= 'Payment terms & follow up cancelled.';
			}
			// $history_text .= 'Updated by: '.$update_by_name;

			$historydata=array(
			'title'=>$comment_title,
			'lead_id'=>$lead_id,
			'comment'=>$history_text,
			'create_date'=>date("Y-m-d H:i:s"),
			'user_id'=>$update_by,
			'ip_address'=>$ip_addr
						);
			$this->history_model->CreateHistory($historydata);	
			// ------------------------------

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

	public function po_tds_certificate_upload_ajax()
	{		
		$error_msg='';
		$success_msg='';
		$return_status='';
		$lowp=$this->input->post('lowp');		
		if($lowp)
		{			
			
			$m_y_tmp=date("m-y");		
			$no_tmp=$opportunity_id;
			$quote_no=$company_name_tmp.'-000'.$no_tmp.'-'.$m_y_tmp;
			// QUOTE NO. LOGIC - END
			$config = array(
				'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/po/",
				'allowed_types' => "*",
				'overwrite' => FALSE,
				'encrypt_name' => FALSE,
				//'file_name' => $quote_no.'-QUOTE.pdf',
				'max_size' => "2048000" 
				);
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
		   
			if (!$this->upload->do_upload('po_tds_certificate'))
			{
				$error_msg=$this->upload->display_errors();
				$return_status = 'fail';	
			}
			else
			{
				$data=array('upload_data' => $this->upload->data());
				$file_name = $data['upload_data']['file_name'];

				$update_data=array(
						'po_tds_certificate'=>$file_name
						);
				$this->order_model->updateLeadOppWisePo($update_data,$lowp);
				$success_msg="TDS certificate successfully uploaded.";
				$this->session->set_flashdata('success_msg', $msg);
				$return_status = 'success';	
			}	
		}
		$result["status"] = $return_status;
		$result["error_msg"] = $error_msg;
		$result["success_msg"] = $success_msg;
		echo json_encode($result);
		exit(0);
	}

	function rander_tds_certificate_view()
    {
        $data=array();	
        $lowp=$this->input->post('lowp');
		$po_register_info=$this->order_model->get_po_register_detail($lowp);
		$data['po_register_info']=$po_register_info;
		$data['lowp']=$lowp;
		echo $this->load->view('admin/order/rander_tds_certificate_view',$data,TRUE);
    }

    public function del_po_tds_certificate_ajax()
	{
		$lowp=$this->input->post('lowp');		
		$update_data=array(
						'po_tds_certificate'=>''
						);
		$del_data=$this->order_model->updateLeadOppWisePo($update_data,$lowp);

		if($del_data==true)
        	$result["status"] = 'success';
        else
        	$result["status"] = 'fail';      
        echo json_encode($result);
        exit(0); 
	}

	public function del_po_custom_invoice_ajax()
	{
		$lowp=$this->input->post('lowp');
		$inv_id=$this->input->post('inv_id');
		$update_data=array(
						'po_custom_invoice'=>'',
						'updated_at'=>date('Y-m-d H:i:s')
						);
		$del_data=$this->order_model->updateInvoice($update_data,$inv_id);

		if($del_data==true)
        	$result["status"] = 'success';
        else
        	$result["status"] = 'fail';      
        echo json_encode($result);
        exit(0); 
	}

	function rander_custom_invoice_view()
    {
        $data=array();	
        $inv_id=$this->input->post('inv_id');
        $inv_info=$this->order_model->get_invoice($inv_id);
		$data['po_inv_info']=$inv_info;
		$data['po_register_info']=$this->order_model->get_po_register_detail($inv_info->lead_opportunity_wise_po_id);
		echo $this->load->view('admin/order/rander_custom_invoice_view',$data,TRUE);
    }

    function rander_custom_proforma_view()
    {
        $data=array();	
        $proforma_id=$this->input->post('proforma_id');
        $po_proforma_info=$this->order_model->get_pro_forma_invoice($proforma_id);
		$data['po_proforma_info']=$po_proforma_info;
		$data['po_register_info']=$this->order_model->get_po_register_detail($po_proforma_info->lead_opportunity_wise_po_id);
		echo $this->load->view('admin/order/rander_custom_proforma_view',$data,TRUE);
    }

    public function del_po_custom_proforma_ajax()
	{
		$lowp=$this->input->post('lowp');
		$proforma_id=$this->input->post('proforma_id');

		$post_data=array(
	    		'po_custom_proforma'=>'',
				'updated_at'=>date("Y-m-d H:i:s")
				);
		$this->order_model->updatePoFormaInvoice($post_data,$proforma_id);

		if($del_data==true)
        	$result["status"] = 'success';
        else
        	$result["status"] = 'fail';      
        echo json_encode($result);
        exit(0); 
	}

	function rander_trans_payment_terms_ajax()
    {
    	$pt_id=$this->input->post('pt_id');
    	$arg['pt_id']=$pt_id;
    	$list['rows']=$this->order_model->get_payment_followup_split_list($arg);

    	$html = $this->load->view('admin/order/payment_followup_split_view_ajax',$list,TRUE);
    	$result['html']=$html;    
    	$result['msg']='';    	
        echo json_encode($result);
        exit(0);
    }
	
	public function proforma_invoice_management()
	{
		if($this->session->userdata('po_back_url_from_detail'))
		{
			$this->session->set_userdata('po_back_url_from_detail','');
		}
		$data=array();
		$this->load->view('admin/order/proforma_invoice_management_view',$data);
	}
	// AJAX PAGINATION START
	function get_proforma_invoice_management_list_ajax()
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
	    $arg['filter_sort_by']=$this->input->get('filter_sort_by'); 
	   //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->order_model->get_proforma_invoice_management_list_count($arg);
	    // $config['total_rows'] =10;
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
	    
    	
    	
    			
	    $table = '';	
	    if($view_type=='grid')
	    {
	    	//$table = $this->load->view('admin/order/invoice_management_grid_view_ajax',$list,TRUE);
	    }
	    else
	    {
	    	$list['rows']=$this->order_model->get_proforma_invoice_management_list($arg);
	    	$table = $this->load->view('admin/order/proforma_invoice_management_list_view_ajax',$list,TRUE);
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
}
