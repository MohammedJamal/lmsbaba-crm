<?php 
require(APPPATH.'/libraries/REST_Controller.php');
class Rest_lead extends REST_Controller
{
    private $access_token = '';
    public function __construct()
   	{
		parent::__construct();      

        $this->load->model(array("lead_model","User_model","history_model","Client_model","opportunity_model","Product_model","Setting_model","customer_model","Email_forwarding_setting_model","quotation_model","Opportunity_product_model","source_model","pre_define_comment_model","countries_model","Sms_forwarding_setting_model","App_model","Api_model"));

        $this->access_token = get_access_token();
	}
    
    /*
    Lead detail
    */
    function call_log_get()
    {        
        // if($this->get('token') == $this->access_token) // ACCESS TOKEN CHECKING
        if($this->get('token'))
        {
            $client_id=$this->get('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id = $this->get('user_id');  
            $call_status = $this->get('call_status');  
            $start=$this->get('start');
            $limit=$this->get('limit');
            $start=empty($start)?0:($start-1)*$limit;
            $arg=array();
            $arg['lead_id']='';   
            $arg['user_id']=$user_id;
            $arg['call_status']=$call_status;
            $arg['start'] = $start;
            $arg['limit'] = $limit; 

            if($arg['user_id']!='' && $arg['call_status']!='')
            {
                
                $result=$this->lead_model->get_call_log($arg,$client_info);
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => '',
                        'rows' => $result
                         );
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            }
            

        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => 'Token not matching.',
                        'rows' => array()
                        ); 
        }        
        $this->response($data, 200);
    }

    /*
    Lead detail
    */
    function call_log_info_post()
    {        
        // if($this->get('token') == $this->access_token) // ACCESS TOKEN CHECKING
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id = $this->post('user_id');  
            $call_status = $this->post('call_status');  
            $start=$this->post('start');
            $limit=$this->post('limit');
            $start=empty($start)?0:($start-1)*$limit;
            $arg=array();
            $arg['lead_id']='';   
            $arg['user_id']=$user_id;
            $arg['call_status']=$call_status;
            $arg['start'] = $start;
            $arg['limit'] = $limit; 

            if($arg['user_id']!='' && $arg['call_status']!='')
            {
                
                $result=$this->lead_model->get_call_log($arg,$client_info);
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => '',
                        'rows' => $result
                         );
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            }            

        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => 'Token not matching.',
                        'rows' => array()
                        ); 
        }        
        $this->response($data, 200);
    }

    /*
    call log update post data
    */
    function call_log_update_post()
    {
        // if($this->post('token') == $this->access_token) // ACCESS TOKEN CHECKING
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $dataArr['call_status']=$this->post('call_status');
            $dataArr['call_medium']=$this->post('call_medium');
            $dataArr['exact_call_start']=$this->post('exact_call_start');
            $dataArr['exact_call_end']=$this->post('exact_call_end');
            $dataArr['msg']=$this->post('msg');
            $dataArr['next_followup_date']=$this->post('next_followup_date');
            $dataArr['updated_at']=date("Y-m-d H:i:s");
            $id=$this->post('id');
            

            if($id!='' && $dataArr['call_status']!='' && $dataArr['exact_call_start']!='' && $dataArr['exact_call_end']!='')
            {
                $r=$this->lead_model->UpdateCallScheduleLog($dataArr,$id,$client_info);                

                if($r == true)
                {
                    // HISTORY UPDATE
                    if($dataArr['call_status']=='Y' || $dataArr['call_status']=='F')
                    {
                        $call_medium_txt=($dataArr['call_medium']=='W')?'Whatsapp':'SIM';                        
                        $row=$this->lead_model->get_call_log_detail($id,$client_info);
                        $user_id=$row['user_id'];
                        $lead_id=$row['lead_id']; 
                        $contact_person=$row['contact_person'];
                        $contact_number=$row['contact_number'];
                        $company_name=$row['company_name'];
                        
                        // -----------------------
                        // update lead table
                        $update_data=array(
                            'followup_date'=>$dataArr['next_followup_date'],
                            'is_followup_date_changed'=>'Y',
                            'modify_date'=>date("Y-m-d")
                            );
                        $this->lead_model->UpdateLead($update_data,$lead_id,$client_info);
                        // update lead table
                        // -----------------------
                        
                        // ----------------------
                        // CREATE LEAD HISTORY
                        $attach_filename='';
                        $update_by=$user_id;
                        $date=date("Y-m-d h:i:s");              
                        $ip_addr=$_SERVER['REMOTE_ADDR'];  
                        if($dataArr['call_status']=='Y')
                        {
                            $message="<b>Comment:</b> ".$dataArr['msg'];
                            $call_status_txt='Talked';
                        }   
                        else
                        {
                            $message="<b>Comment:</b> Reason - ".$dataArr['msg'];
                            $call_status_txt='Not Talked';
                        }                        
                        $comment_title=LEAD_UPDATE_MANUAL_APP;
                        $historydata=array(
                                            'title'=>$comment_title,
                                            'lead_id'=>$lead_id,
                                            'comment'=>$message,
                                            'attach_file'=>$attach_filename,
                                            'create_date'=>$date,
                                            'communication_type_id'=>3,
                                            'communication_type'=>'Call Update ('.$call_medium_txt.') / '.$call_status_txt,
                                            'next_followup_date'=>$dataArr['next_followup_date'],
                                            'create_date'=>date("Y-m-d H:i:s"),
                                            'user_id'=>$update_by,
                                            'ip_address'=>$ip_addr
                                        );
                        $this->history_model->CreateHistory($historydata,$client_info);  
                        // CREATE LEAD HISTORY
                        // ----------------------
                    }
                    $data = array(
                                'api_token_success'  => 1,
                                'api_syntax_success' => 1,
                                'api_action_success' => 1,
                                'error'              => ''
                                ); 
                }
                else
                {
                    $data = array(
                                'api_token_success'  => 1,
                                'api_syntax_success' => 0,
                                'api_action_success' => 1,
                                'error'              => 'Record has been failed to update.'
                                ); 
                }
            }
            else
            {
                $data = array(
                                'api_token_success'  => 1,
                                'api_syntax_success' => 0,
                                'api_action_success' => 1,
                                'error'              => 'Expected parameter missing.'
                                ); 
            }           
        
        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_syntax_success' => 1,
                        'api_action_success' => 1,
                        'error'              => 'Access denied! Token not matching.'
                        ); 
        }

        $this->response($data, 200);
    }

    /*
    call log add post data
    */
    
    function call_log_add_post()
    {
        // if($this->post('token') == $this->access_token) // ACCESS TOKEN CHECKING
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $dataArr['lead_id']=$this->post('lead_id');
            $dataArr['user_id']=$this->post('user_id');
            $dataArr['company_id']=$this->post('company_id');
            $dataArr['contact_person']=$this->post('contact_person');
            $dataArr['contact_number']=$this->post('contact_number');
            $dataArr['company_name']=$this->post('company_name');
            $dataArr['call_status']=$this->post('call_status');
            $dataArr['call_medium']=strtoupper($this->post('call_medium'));
            $dataArr['exact_call_start']=$this->post('exact_call_start');
            $dataArr['exact_call_end']=$this->post('exact_call_end');
            $dataArr['msg']=$this->post('msg');
            $dataArr['created_at']=date("Y-m-d H:i:s");
            $dataArr['updated_at']=date("Y-m-d H:i:s");
            
            if($dataArr['lead_id']!='' && $dataArr['user_id']!='' && $dataArr['company_id']!='' && $dataArr['contact_person']!='' && $dataArr['contact_number']!='' && $dataArr['company_name']!='' && $dataArr['call_status']!='' && $dataArr['call_medium']!='' && $dataArr['exact_call_start']!='' && $dataArr['exact_call_end']!='')
            {

                $r=$this->lead_model->CreateCallScheduleLog($dataArr,$client_info);
                if($r)
                {

                    $data = array(
                                'api_token_success'  => 1,
                                'api_syntax_success' => 1,
                                'api_action_success' => 1,
                                'error'              => ''
                                ); 
                }
                else
                {
                    $data = array(
                                'api_token_success'  => 1,
                                'api_syntax_success' => 0,
                                'api_action_success' => 1,
                                'error'              => 'Record has been failed to update.'
                                ); 
                }
            }
            else
            {
                $data = array(
                                'api_token_success'  => 1,
                                'api_syntax_success' => 0,
                                'api_action_success' => 1,
                                'error'              => 'Expected parameter missing.'
                                ); 
            }           
            
        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_syntax_success' => 1,
                        'api_action_success' => 1,
                        'error'              => 'Access denied! Token not matching.'
                        ); 
        }

        $this->response($data, 200);
    }
    
    /*
     call log delete
    */
    function call_log_delete_get()
    {
        // if($this->get('token') == $this->access_token) // ACCESS TOKEN CHECKING
        if($this->get('token'))
        {
            $client_id=$this->get('client_id');
            $client_info=$this->Client_model->get_details($client_id);
               $deleteID = $this->get('id');
               if($deleteID!="") // PARAMETER CHECKING
                {   
                    //$data_arr = array();
                    $result = $this->lead_model->DeleteCallScheduleLog($deleteID,$client_info);
                    $data = array(
                                'api_token_success'  => 1,
                                'api_action_success' => 1,
                                'api_syntax_success' => 1,
                                'error'              => '',
                                'rows'               => 1
                                 );
                }
                else
                {    
                    $data = array(
                                'api_token_success'  => 1,
                                'api_action_success' => 0,
                                'api_syntax_success' => 1,
                                'error'              => 'Expected parameter missing.',
                                'rows'               => array()
                                );
                }
        }
        else
        {
                    $data = array(
                                'api_token_success'  => 0,
                                'api_action_success' => 1,
                                'api_syntax_success' => 1,
                                'error'              => 'Token not matching.',
                                'rows'               => array()
                                ); 
        }
        $this->response($data, 200);
    }

    /*
    user token update post data for push message
    */
    function user_token_update_post()
    {
        // if($this->post('token') == $this->access_token) // ACCESS TOKEN CHECKING
        if($this->post('token'))
        {            
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);

            $dataArr['user_token']=$this->post('user_token');            
            $dataArr['modify_date']=date("Y-m-d H:i:s");
            $user_id=$this->post('user_id');
            if($user_id!='' && $dataArr['user_token']!='')
            {
                $r = $this->User_model->UpdateAdmin($dataArr,$user_id,$client_info);

                if($r == true)
                {

                    $data = array(
                                'api_token_success'  => 1,
                                'api_syntax_success' => 1,
                                'api_action_success' => 1,
                                'error'              => ''
                                ); 
                }
                else
                {
                    $data = array(
                                'api_token_success'  => 1,
                                'api_syntax_success' => 0,
                                'api_action_success' => 1,
                                'error'              => 'Record has been failed to update.'
                                ); 
                }
            }
            else
            {
                $data = array(
                                'api_token_success'  => 1,
                                'api_syntax_success' => 0,
                                'api_action_success' => 1,
                                'error'              => 'Expected parameter missing.'
                                ); 
            }           
        
        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_syntax_success' => 1,
                        'api_action_success' => 1,
                        'error'              => 'Access denied! Token not matching.'
                        ); 
        }

        $this->response($data, 200);
    } 


    /*
    mobile call history data for new lead
    */
    function call_history_post()
    {        
        // $this->response($this->post('call_history'), 200);
        
        try{ 
                if($this->post('token')!='' && $this->post('client_id')!='')
                {
                    $client_id=$this->post('client_id');
                    $client_info=$this->Client_model->get_details($client_id);
                    $dataArr['call_history']=$this->post('call_history');            
                    
                    if($dataArr['call_history'])
                    {
                        $user_id=$this->post('user_id');
                        $call_history_arr=$this->post('call_history');
                        // $call_history_arr=json_decode($call_history_str,true);
                        // $call_history_arr=$call_history_str;
                        // $this->response($call_history_arr, 200);
                        if(count($call_history_arr))
                        {
                            foreach($call_history_arr AS $row)
                            {       
                                
                                if($row['call_number'])
                                {
                                    $call_number=get_sync_call_number_filter($row['call_number']);
                                    $status=($row['start_time_date']==$row['end_time_date'])?'1':'0'; 
                                    $status_msg=($status=='1')?'Not Talked Calls':'';

                                    $call_start_arr=explode(".",$row['start_time_date']);
                                    $start_time_date=$call_start_arr[0];
                                    $call_end_arr=explode(".",$row['end_time_date']);
                                    $end_time_date=$call_end_arr[0];


                                    $post_data=array(
                                    'user_id'=>$user_id,
                                    'name'=>$row['name'],
                                    'country_code'=>$row['country_code'],
                                    'number'=>$call_number,
                                    'call_start'=>$start_time_date,
                                    'call_end'=>$end_time_date,
                                    'bound_type'=>$row['bound_type'],
                                    'agent_mobile'=>$row['agent_mobile'],
                                    'status'=>$status,
                                    'status_wise_msg'=>$status_msg,
                                    'created_at'=>date("Y-m-d H:i:s")
                                    );  
                                    $duplicate_chk=$this->lead_model->DuplicateChkCallHistoryForLead($post_data,$client_info); 
                                    if($duplicate_chk==true)
                                    {
                                        $r=$this->lead_model->AddCallHistoryForLead($post_data,$client_info);
                                        if($r==false)
                                        {
                                            $data = array(
                                            'api_token_success'  => 1,
                                            'api_syntax_success' => 0,
                                            'api_action_success' => 1,
                                            'error'              => 'Record not inserted.'
                                            ); 
                                        }
                                        else
                                        {
                                            $data = array(
                                            'api_token_success'  => 1,
                                            'api_syntax_success' => 1,
                                            'api_action_success' => 1,
                                            'error'              => ''
                                            ); 
                                        }
                                    }
                                }
                            }
                        }
                    }
                    else
                    {
                        $data = array(
                        'api_token_success'  => 1,
                        'api_syntax_success' => 0,
                        'api_action_success' => 1,
                        'error'              => 'Call log successfully updated.'
                        ); 
                    }
                }
                else
                {
                    $data = array(
                                'api_token_success'  => 0,
                                'api_syntax_success' => 1,
                                'api_action_success' => 1,
                                'error'              => 'Access denied! Token not matching.'
                                ); 
                }
                $this->response($data, 200);
                

        }catch(Exception $e){
            $this->response('error', 200);
            // $data = array(
            //     'api_token_success'  => 0,
            //     'api_syntax_success' => 0,
            //     'api_action_success' => 0,
            //     'error'              => 'Unknown error'
            //     );
            // $this->response($data, 200);
        }
    }
    /*
    mobile call history data for new lead
    */

    /*
    ----------------------------------------------------------------------------------------------
    Get Lead wise history list
    */
    function lead_wise_history_post()
    { 
        
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id = $this->post('user_id');  
            $lead_id = $this->post('lead_id'); 

            $arg=array();
            $arg['user_id']=$user_id; 
            $arg['lead_id']=$lead_id;
            if($arg['user_id']!='' && $arg['lead_id']!='')
            {
                $result=$this->lead_model->GetLeadCommentListAll($lead_id,'',$client_info);
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error' => '',
                        'rows' => $result
                        );
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            }
            

        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => 'Token not matching.',
                        'rows' => array()
                        ); 
        }        
        $this->response($data, 200);
        
    }
    /*
    Get Lead wise history list
    ----------------------------------------------------------------------------------------------
    */

    /*
    ----------------------------------------------------------------------------------------------
    Lead wise Whatsapp
    */

    function search_product_by_name_post()
    { 
        
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id = $this->post('user_id');      
            $search_p_name = $this->post('search_p_name');    
            $product_list=$this->Product_model->searchProducs($search_p_name,$client_info);                              
            $data = array(
                    'api_token_success'  => 1,
                    'api_action_success' => 1,
                    'api_syntax_success' => 1,
                    'error' => '',
                    'rows' => $product_list
                    );            

        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => 'Token not matching.',
                        'rows' => array()
                        ); 
        }        
        $this->response($data, 200);
        
    }

    function rander_whatsapp_popup_text_post()
    { 
        //products: 29, Lead id:28/1679
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id = $this->post('user_id');                         
            if($user_id)
            {
                $whatsapp_templates=$this->lead_model->get_webwhatsapp_template_list($user_id,$client_info);               
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error' => '',
                        'whatsapp_templates' => $whatsapp_templates
                        );
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            }
            

        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => 'Token not matching.',
                        'rows' => array()
                        ); 
        }        
        $this->response($data, 200);
        
    }

    function send_whatsapp_popup_text_post()
    { 
        //products: 29,28 / Lead id:1679
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $is_send_direct_message = $this->post('is_send_direct_message');  
            $user_id = $this->post('user_id');
            $product_ids = $this->post('product_ids');    
            $lead_id = $this->post('lead_id');            
            $whatsapp_template_id = $this->post('whatsapp_template_id');           
            $quote_text='';
            

            if($lead_id!='' && $user_id!='')
            {
                
                if($is_send_direct_message=='N')
                {
                    
                    $company=$this->Setting_model->GetCompanyData($client_info);                    
                   
                    if($product_ids!='' && $whatsapp_template_id=='') // Create Price List
                    {
                        $lead_info=$this->lead_model->GetLeadData($lead_id,$client_info);
                        $product_quote_arr=explode(',', $product_ids);
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
                                    $product_info=$this->Product_model->get_product($p,$client_info);
                                    
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
                    else if($product_ids=='' && $whatsapp_template_id!='') // Pre-Define Templates
                    {
                        $lead_data=$this->lead_model->GetLeadData($lead_id,$client_info);
                        $get_template=$this->lead_model->get_webwhatsapp_template($whatsapp_template_id,$client_info);
                        $latest_opportunity=$this->opportunity_model->GetLatestOpportunity($lead_id,$client_info);
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
                    
                        if(isset($latest_opportunity->id))
                        {
                            $html =str_replace($keyword_11,'#'.$latest_opportunity->id.' ('.$latest_opportunity->quote_no.')',$html);
                            $html =str_replace($keyword_12,date_db_format_to_display_format($latest_opportunity->quote_valid_until),$html);	
                            $html =str_replace($keyword_13,date_db_format_to_display_format($latest_opportunity->quotation_sent_on),$html);		
                        }                     
                        $quote_text =$html;
                        
                        

                    }
                    else
                    {
                        $data = array(
                            'api_token_success'  => 1,
                            'api_action_success' => 1,
                            'api_syntax_success' => 0,
                            'error'          => 'product_ids or whatsapp_template_id both are not accepted.'
                            );
                        $this->response($data, 200);
                    }
                    
                    
                }
                else if($is_send_direct_message=='Y')
                {
                    $quote_text ="Direct Message Initiated.";
                }
                
                
                // ----------------------
	        	// CREATE LEAD HISTORY
				$attach_filename='';
			    $update_by=$user_id;
				$date=date("Y-m-d H:i:s");				
				$ip_addr=$_SERVER['REMOTE_ADDR'];	
                $keyword='%0A';
                $whatsapp_txt =str_replace($keyword,'',$quote_text); 
                $message="<b>Whastapp Comment From Lmababa APP:</b> ".addslashes($whatsapp_txt);
                $comment_title=LEAD_UPDATE_MANUAL_APP;
                $historydata=array(
                    'title'=>$comment_title,
                    'lead_id'=>$lead_id,
                    'comment'=>$message,
                    'attach_file'=>$attach_filename,
                    'create_date'=>$date,
                    'communication_type_id'=>4,
                    'communication_type'=>'Whatsapp Update From Lmababa APP',
                    'user_id'=>$update_by,
                    'ip_address'=>$ip_addr
                );
                $this->history_model->CreateHistory($historydata,$client_info);	
				// CREATE LEAD HISTORY
				// ----------------------
                
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error' => '',
                        'whatsapp_text' => $quote_text
                        );
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            }
            

        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => 'Token not matching.',
                        'rows' => array()
                        ); 
        }        
        $this->response($data, 200);
        exit;
        
    }

    /*
    Lead wise Whatsapp
    ----------------------------------------------------------------------------------------------
    */

    /*
     ----------------------------------------------------------------------------------------------
    Reply Popup
    */
    function rander_cust_reply_popup_post()
    {        
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id = $this->post('user_id'); 
            $lead_id = $this->post('lead_id');                         
            if($lead_id)
            {
                $data=array(); 
                $lead_data=$this->lead_model->GetLeadData($lead_id,$client_info);               
                
                $to_mail=$lead_data->cus_email;
                $subject="Enquiry # ".$lead_data->id." - Query/Update from your A/C Manager";
                $row[0] = array('to_mail'=>$to_mail,'subject'=>$subject);
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error' => '',
                        'row' => $row
                        );
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            }
            

        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => 'Token not matching.',
                        'rows' => array()
                        ); 
        }        
        $this->response($data, 200);
        
    }

    function cust_reply_popup_confirm_post()
    {        
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id = $this->post('user_id'); 
            $lead_id = $this->post('lead_id');
            $reply_mail_to = $this->post('reply_mail_to');    
            $mail_to_client_mail_subject = $this->post('reply_mail_subject'); 
            $reply_email_body = $this->post('reply_email_body');    

            // $data = array(
            //     'api_token_success'  => 1,
            //     'api_action_success' => 1,
            //     'api_syntax_success' => 1,
            //     'error' => '',
            //     'row' => $lead_id.'/'.$user_id.'/'.$reply_mail_to.'/'.$mail_to_client_mail_subject.'/'.$reply_email_body
            //     );
            //     $this->response($data, 200);exit;

            if($lead_id!='' && $user_id!='' && $reply_mail_to!='' && $mail_to_client_mail_subject!='' && $reply_email_body!='')
            {
                $data=array();                
                $communication_type_id=2;
                $communication_type=get_value("title","communication_master","id=".$communication_type_id,$client_info);                 
                $is_company_brochure_attached_in_reply='N';
        
                $company=$this->Setting_model->GetCompanyData($client_info);       
                $lead_info=$this->lead_model->GetLeadData($lead_id,$client_info);
                $customer=$this->customer_model->GetCustomerData($lead_info->customer_id,$client_info);
                $assigned_user_id=$lead_info->assigned_user_id;
                $user_data=$this->User_model->get_employee_details($assigned_user_id,$client_info);
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
                            'upload_path' => "assets/uploads/clients/".$client_id."/company/lead/",
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
                                $attach_filename_with_path[]="assets/uploads/clients/".$client_id."/company/lead/".$filename;                                
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
                        $company_brochure="assets/uploads/clients/".$client_id."/company/brochure/".$company['brochure_file'];
                        $attach_filename_with_path[]=$company_brochure;
                    }
                }
                // company brochure attachment
                // ------------------------------

                $m_email=get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);
                $reply_mail_to_cc='';
                $reply_mail_to_cc_arr=array();
                if($reply_mail_to_cc)
                {
                    $reply_mail_to_cc_arr=explode(",", $reply_mail_to_cc);
                }
                $cc_mail_arr=$reply_mail_to_cc_arr;
                $self_cc_mail=get_value("email","user","id=".$assigned_user_id,$client_info);
                
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
                $mail_data['from_mail']     = $user_data['email'];
                $mail_data['from_name']     = $user_data['name'];
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

                $mail_to_client_from_mail=$user_data['email'];
                $mail_to_client_from_name=$user_data['name'];
                // MAIL SEND
                // ===============================================
                //-------------- HISTORY ----------------------------------
                $history_text = '';
                $update_by=$user_id;
                $ip_addr=$_SERVER['REMOTE_ADDR'];		
                $comment_title=LEAD_GENERAL_UPDATE_APP;
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
                $this->history_model->CreateHistory($historydata,$client_info);	
                // ----------------------------------------------------------

                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error' => '',
                        'row' => ''
                        );
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            }
            

        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => 'Token not matching.',
                        'rows' => array()
                        ); 
        }        
        $this->response($data, 200);
        
    }

    /*
    Reply Popup
    ----------------------------------------------------------------------------------------------
    */

    /*     ----------------------------------------------------------------------------------------------
    Update Popup
    */    

    function lead_update_popup_confirm_post()
    {        
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id = $this->post('user_id'); 
            $lead_id = $this->post('lead_id');
            $description = $this->post('description');            

            if($lead_id!='' && $description!='')
            {
                $history_text = '';
                $update_by=$user_id;
                $ip_addr=$_SERVER['REMOTE_ADDR'];		
                $comment_title=LEAD_GENERAL_UPDATE_APP;
                $history_text .= addslashes($description);

                $historydata=array(
                    'title'=>$comment_title,
                    'lead_id'=>$lead_id,
                    'comment'=>$history_text,
                    'mail_trail_html'=>'',
                    'mail_trail_ids'=>'',
                    'cc_to_employee'=>'',
                    'mail_to_client'=>'',
                    'mail_to_client_from_mail'=>'',
                    'mail_to_client_from_name'=>'',						
                    'regret_mail_from_mail'=>'',
                    'regret_mail_from_name'=>'',
                    'mail_subject_of_update_lead_mail_to_client'=>'',
                    'mail_subject_of_update_lead_regret_this_lead'=>'',
                    'attach_file'=>'',
                    'communication_type_id'=>'',
                    'communication_type'=>'',
                    'next_followup_date'=>'',
                    'create_date'=>date("Y-m-d H:i:s"),
                    'user_id'=>$update_by,
                    'ip_address'=>$ip_addr
                    );
                $this->history_model->CreateHistory($historydata,$client_info);
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error' => '',
                        'row' => ''
                        );
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            }
            

        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => 'Token not matching.',
                        'rows' => array()
                        ); 
        }        
        $this->response($data, 200);
        
    }

    /*
    Update Popup
    ----------------------------------------------------------------------------------------------
    */

     /*
    ----------------------------------------------------------------------------------------------
    Lead wise call
    */
    function initiated_call_post()
    {        
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $is_send_direct_message = $this->post('is_send_direct_message');  
            $user_id = $this->post('user_id');             
            $lead_id = $this->post('lead_id'); 

            if($lead_id!='' && $user_id!='')
            {
                $lead_title=get_value("title","lead","id=".$lead_id,$client_info);				
                // ----------------------
                // CREATE LEAD HISTORY    	
                $attach_filename='';
                $update_by=$user_id;
                $date=date("Y-m-d H:i:s");				
                $ip_addr=$_SERVER['REMOTE_ADDR'];				
                $message="A call has beed initiated from Lmsbaba APP for The lead (".$lead_title.") on ".date_db_format_to_display_format(date('Y-m-d'))."";		

                $comment_title=LEAD_UPDATE_MANUAL_APP;
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
                $this->history_model->CreateHistory($historydata,$client_info);
                // CREATE LEAD HISTORY
                // ----------------------
                
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error' => ''
                        );
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            }
            

        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => 'Token not matching.',
                        'rows' => array()
                        ); 
        }        
        $this->response($data, 200);
        exit;
        
    }

    /*
    Lead wise call
    ----------------------------------------------------------------------------------------------
    */

    /*
    ----------------------------------------------------------------------------------------------
    Stage Change (Thums Up)
    */
    function rander_stage_popup_post()
    {         
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);

            $user_id = $this->post('user_id');
            $lead_id = $this->post('lead_id');                         
            if($user_id!='' && $lead_id!='')
            {           
                $curr_stage_id==get_value("current_stage_id","lead","id=".$lead_id,$client_info);               
                $lead_stage_log=$this->lead_model->get_lead_wise_stage_log($lead_id,$client_info);
                // Get Custom stages 
                // --------------------------------------
                $custom_lead_stage_str='';
                $custom_lead_stage_info=get_custom_stage($client_info);
                if($custom_lead_stage_info['id_str']){
                    $custom_lead_stage_str=','.$custom_lead_stage_info['id_str'];
                }
                // --------------------------------------    	
                $stage_list=$this->opportunity_model->GetStageList('1,8,2,4'.$custom_lead_stage_str,$client_info);
                              
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error' => '',
                        'lead_stage_log' => $lead_stage_log,
                        'curr_stage_id'=>$curr_stage_id,
                        'stage_list'=>$stage_list
                        );
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            }
            

        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => 'Token not matching.',
                        'rows' => array()
                        ); 
        }        
        $this->response($data, 200);
    }


    /*
    ----------------------------------------------------------------------------------------------
    Stage Change (Thums Up)
    */
    function dislike_stage_list_popup_post()
    {         
        if($this->post('token'))
        { 
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id = $this->post('user_id');
            $lead_id = $this->post('lead_id');

            if($user_id!='' && $lead_id!='' && $client_id!='')
            {
                              
                $regret_reason_list=get_regret_reason($client_info);
                              
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error' => '',
                        'regret_reason_list' => $regret_reason_list
                        );
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            }
        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => 'Token not matching.',
                        'rows' => array()
                        ); 
        }        
        $this->response($data, 200);
        
    }



    // ===========================================================================================
    // Quotation 
    /*
    Lead wise Quotation list
    */
    function quotation_list_post()
    { 
        if($this->post('token'))
        {
            $lead_id = $this->post('lead_id'); 
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);            
            if($lead_id!='' && count($client_info)>0)
            {
                // $cus_data=$this->lead_model->GetLeadDataForQuotationPopup($lead_id,$client_info);
                $opportunity_list=$this->opportunity_model->GetOpportunityListAllForQuotationPopup($lead_id,$client_info);		
		        // $currency_list=$this->Product_model->GetCurrencyListForQuotationPopup($client_info);                
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => '',
                        'quotation_rows' => $opportunity_list
                         );
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            }
            

        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => 'Token not matching.',
                        'rows' => array()
                        ); 
        }        
        $this->response($data, 200);
    }

    /*
    Quotation add
    */
    function quotation_add_post()
    { 
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $dataArr['lead_id']=$this->post('lead_id');
            $dataArr['user_id']=$this->post('user_id');
            $dataArr['company_id']=$this->post('company_id');
            $dataArr['contact_person']=$this->post('contact_person');
            $dataArr['contact_number']=$this->post('contact_number');
            $dataArr['company_name']=$this->post('company_name');
            $dataArr['call_status']=$this->post('call_status');
            $dataArr['call_medium']=strtoupper($this->post('call_medium'));
            $dataArr['exact_call_start']=$this->post('exact_call_start');
            $dataArr['exact_call_end']=$this->post('exact_call_end');
            $dataArr['msg']=$this->post('msg');
            $dataArr['created_at']=date("Y-m-d H:i:s");
            $dataArr['updated_at']=date("Y-m-d H:i:s");
            
            if($dataArr['lead_id']!='' && $dataArr['user_id']!='' && $dataArr['company_id']!='' && $dataArr['contact_person']!='' && $dataArr['contact_number']!='' && $dataArr['company_name']!='' && $dataArr['call_status']!='' && $dataArr['call_medium']!='' && $dataArr['exact_call_start']!='' && $dataArr['exact_call_end']!='')
            {

                $r=$this->lead_model->CreateCallScheduleLog($dataArr,$client_info);
                if($r)
                {

                    $data = array(
                                'api_token_success'  => 1,
                                'api_syntax_success' => 1,
                                'api_action_success' => 1,
                                'error'              => ''
                                ); 
                }
                else
                {
                    $data = array(
                                'api_token_success'  => 1,
                                'api_syntax_success' => 0,
                                'api_action_success' => 1,
                                'error'              => 'Record has been failed to update.'
                                ); 
                }
            }
            else
            {
                $data = array(
                                'api_token_success'  => 1,
                                'api_syntax_success' => 0,
                                'api_action_success' => 1,
                                'error'              => 'Expected parameter missing.'
                                ); 
            }           
            
        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_syntax_success' => 1,
                        'api_action_success' => 1,
                        'error'              => 'Access denied! Token not matching.'
                        ); 
        }
        $this->response($data, 200);
    }

    // Quotation 
    // ===========================================================================================


    /*
    like_btn_confirm post data
    */
    
    function like_btn_confirm_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');

            $next_followup_history_txt='';
            $nf_date=date("Y-m-d H:i:s");
            $lead_id=$this->post('lead_id');
            $all_stage_id_str=$this->post('all_stage_id_str');
            $all_stage_id_arr=explode(",",$all_stage_id_str);
            $last_checked_stage=$this->post('checked_stage_id');
            $last_stage_id=$last_checked_stage;
            $result['is_q_exist']='';
            $result['loppid']='';
            
            if($client_id!='' && $user_id!='' && $lead_id!='' && $last_checked_stage!='')
            {

                if($last_stage_id=='4')
                {
                    $opportunity_list=$this->opportunity_model->GetSentToClientStatusOpportunityListAll($lead_id,$client_info);
    
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


                    $data = array(
                        'api_token_success'  => 1,
                        'api_syntax_success' => 0,
                        'api_action_success' => 1,
                        'error'              => $result
                        ); 
                }
                else
                {
                    
                    $is_deal_won_lead=$this->lead_model->is_deal_won_lead($lead_id,$client_info);
                    $lead_info=$this->lead_model->GetLeadData($lead_id,$client_info);
                    $get_prev_status=$this->lead_model->get_prev_status($lead_id,$client_info); 
                    
                    //$company=get_company_profile();
                    $assigned_user_id=$lead_info->assigned_user_id;
                    $customer=$this->customer_model->GetCustomerData($lead_info->customer_id,$client_info);
                    $user_data=$this->User_model->get_employee_details($assigned_user_id,$client_info);	
                    

                    //-------------- HISTORY ----------------------------------
                    $history_text = '';
                    $update_by=$user_id;
                    $ip_addr=$_SERVER['REMOTE_ADDR'];		
                    $comment_title=LEAD_GENERAL_UPDATE_APP;
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
                        $lead_opp_id=$this->create_quotation_and_get_opp_id($lead_id,$user_id,$client_info);	
                        if($lead_opp_id)
                        {
                            $post_data=array(
                                'status'=>2,
                                'modify_date'=>date("Y-m-d")
                                );
                            $this->opportunity_model->UpdateLeadOportunity($post_data,$lead_opp_id,$client_info);	
                        } 	
    
                        // UPDATE LEAD STAGE/STATUS
                        $update_lead_data = array(
                            'followup_date'=>$nf_date,
                            'modify_date'=>date("Y-m-d")
                        );								
                        $this->lead_model->UpdateLead($update_lead_data,$lead_id,$client_info);	
                            
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
                            $this->lead_model->UpdateLead($update_lead_data,$lead_id,$client_info);
                        }
                        
    
                        // Insert Stage Log
                        $stage_post_data=array(
                                'lead_id'=>$lead_id,
                                'stage_id'=>$changed_stage_id,
                                'stage'=>$changed_stage,
                                'stage_wise_msg'=>'',
                                'create_datetime'=>date("Y-m-d H:i:s")
                            );
                        $this->lead_model->CreateLeadStageLog($stage_post_data,$client_info);
    
                        if($lead_info->current_stage_id=='3' || $lead_info->current_stage_id=='5' || $lead_info->current_stage_id=='6' || $lead_info->current_stage_id=='7')
                        {
                            
                            if($changed_stage_id=='2')
                            {
                                // UPDATE LEAD STAGE/STATUS
                                $update_lead_data = array(
                                    'followup_date'=>$nf_date,
                                    'modify_date'=>date("Y-m-d")
                                );								
                                $this->lead_model->UpdateLead($update_lead_data,$lead_id,$client_info);
    
    
                                // Insert Status Log
                                $status_post_data=array();
                                $status_post_data=array(
                                        'lead_id'=>$lead_id,
                                        'status_id'=>'2',
                                        'status'=>'HOT',
                                        'create_datetime'=>date("Y-m-d H:i:s")
                                    );
                                
                            }
                            else
                            {
                                // UPDATE LEAD STAGE/STATUS
                                $update_lead_data = array(
                                    'followup_date'=>$nf_date,
                                    'modify_date'=>date("Y-m-d")
                                );								
                                $this->lead_model->UpdateLead($update_lead_data,$lead_id,$client_info);
    
                                // Insert Status Log
                                $status_post_data=array();
                                $status_post_data=array(
                                        'lead_id'=>$lead_id,
                                        'status_id'=>$get_prev_status->status_id,
                                        'status'=>$get_prev_status->status,
                                        'create_datetime'=>date("Y-m-d H:i:s")
                                    );
                            }
                            
                            $this->lead_model->remove_all_inactive_status_and_stage($lead_id,$client_info);						
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
                    $this->history_model->CreateHistory($historydata,$client_info);	
                    // ----------------------------------------------------------
                    // echo'qqqqqqqqqqq';
                    // die;
                    // $result['msg'] = '';
                    // $result['status'] = 'success';

                    $data = array(
                        'api_token_success'  => 1,
                        'api_syntax_success' => 1,
                        'api_action_success' => 1,
                        'error'              => ''
                        );
                }



            }
            else
            {
                $data = array(
                                'api_token_success'  => 1,
                                'api_syntax_success' => 0,
                                'api_action_success' => 1,
                                'error'              => 'Expected parameter missing.'
                                ); 
            }           
            
        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_syntax_success' => 1,
                        'api_action_success' => 1,
                        'error'              => 'Access denied! Token not matching.'
                        ); 
        }

        $this->response($data, 200);
    }

    function dislike_btn_confirm_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $lead_id=$this->input->post('lead_id');
            $lead_regret_reason_id=$this->input->post('lead_regret_reason_id');
            $lead_regret_reason=$this->input->post('lead_regret_reason');
            
            if($client_id!='' && $user_id!='' && $lead_id!='' && $lead_regret_reason_id!='' && $lead_regret_reason!='' )
            {
                
                $session_data_temp=$this->User_model->GetAdminData($user_id,$client_info);
                $session_data = $session_data_temp[0];
                
                $lead_info=$this->lead_model->GetLeadDataForQuotationPopup($lead_id,$client_info);
                $company=$this->Setting_model->GetCompanyDataDislike($client_info) ;
                $assigned_user_id=$lead_info->assigned_user_id;		
                	
                $customer=$this->customer_model->GetCustomerDataDislike($lead_info->customer_id,$client_info);	
                		
                $user_data=$this->User_model->get_employee_details_dislike($assigned_user_id,$client_info);
                
                //-------------- HISTORY ----------------------------------
                $history_text = '';
                $update_by=$user_id;
                $ip_addr=$_SERVER['REMOTE_ADDR'];		
                $comment_title=LEAD_GENERAL_UPDATE_APP;
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
                    'lost_reason'=>$lead_regret_reason_id,
                    'followup_date'=>'',
                    'is_hotstar'=>'N',
                    'modify_date'=>date("Y-m-d")
                );								
                $this->lead_model->UpdateLead($update_lead_data,$lead_id,$client_info);
                // Insert Stage Log
                $stage_post_data=array(
                        'lead_id'=>$lead_id,
                        'stage_id'=>$changed_stage_id,
                        'stage'=>$changed_stage,
                        'stage_wise_msg'=>$lead_regret_reason,
                        'create_datetime'=>date("Y-m-d H:i:s")
                    );
                $this->lead_model->CreateLeadStageLog($stage_post_data,$client_info);
    
                $history_text .= '<br> Stage changed from <b>'.$lead_info->current_stage.'</b> to <b>'.$changed_stage.'</b>';			
                if($lead_regret_reason)
                {
                    $history_text .= '<br> Lead Regret Reasons: '.$lead_regret_reason;
                }		
                // EMAIL CONTENT
                $this->load->library('mail_lib');		
                // Enquiry Regret Mail 5
                
                $email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(5,$client_info);
                
                $m_email=get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);
                
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
                $self_cc_mail=get_value("email","user","id=".$assigned_user_id,$client_info);     
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
                   
                    $post_data=array();
                    $post_data=array(
                            "mail_for"=>MF_ENQUIRY_REGRET,
                            "from_mail"=>$session_data->email,
                            "from_name"=>$session_data->name,
                            "to_mail"=>$to_mail,
                            "cc_mail"=>$cc_mail,
                            "subject"=>$regret_this_lead_mail_subject,
                            "message"=>$template_str,
                            "attach"=>'',
                            "created_at"=>date("Y-m-d H:i:s")
                    );
                    $this->App_model->mail_fire_add($post_data,$client_info);
                }
                
                // MAIL SEND
                // ===============================================
    
                $regret_mail_from_mail=$session_data->email;
                $regret_mail_from_name=$session_data->name;
                //-------------- HISTORY ----------------------------------	
                $mail_to_client='N';
                $mail_to_client_from_mail="";
                $mail_to_client_from_name="";
                $mail_to_client_mail_subject="";
                $communication_type_id=2;
                
                $communication_type=get_value("title","communication_master","id=".$communication_type_id,$client_info);

                
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
                $this->history_model->CreateHistory($historydata,$client_info);	
                // ----------------------------------------------------------
                
                // product tagged with quoted lead
                $prod_list=$this->lead_model->get_tagged_ps_list($lead_id,'L',$client_info);
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
                        $this->lead_model->CreateLeadWiseProductTag($lead_p_data,$client_info);
                    }
                }
    
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => ''
                    ); 

            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_syntax_success' => 0,
                        'api_action_success' => 1,
                        'error'              => 'Expected parameter missing.'
                        ); 
            }           
            
        }
        else
        {
            $data = array(
                    'api_token_success'  => 0,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => 'Access denied! Token not matching.'
                    ); 
        }

        $this->response($data, 200);
    }

    public function create_quotation_and_get_opp_id($lead_id,$user_id,$client_info)
	{		
		$error_msg='';
		$success_msg='';
		$return_status='';
		$lead_data=$this->lead_model->GetLeadData($lead_id,$client_info);
		$opportunity_title=$lead_data->title;
		$deal_value='';
		$currency_type=1;	
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
			$opportunity_id=$this->opportunity_model->CreateLeadOportunity($data_opportunity,$client_info);			
			$admin_session_data_user_data_tmp=$this->User_model->GetAdminData($user_id,$client_info);
			$admin_session_data_user_data_tmp_arr=json_decode(json_encode($admin_session_data_user_data_tmp),true);
			if(count($admin_session_data_user_data_tmp_arr)){
				$admin_session_data_user_data=$admin_session_data_user_data_tmp[0];
			}
			else{
				$admin_session_data_user_data=array();
			}
			
			$data['admin_session_data_user_data']=$admin_session_data_user_data;
			$data['opportunity_id']=$opportunity_id;
			$opportunity_data=$this->opportunity_model->GetOpportunityData($opportunity_id,$client_info);
			$data['opportunity_data']=$opportunity_data;					
			$data['lead_data']=$lead_data;
			$customer_data=$this->customer_model->GetCustomerData($lead_data->customer_id,$client_info);
			$data['customer_data']=$customer_data;
			$company_data=$this->Setting_model->GetCompanyData($client_info);
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
			$quotation_id=$this->quotation_model->CreateQuotation($quotation_post_data,$client_info);
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
			$this->quotation_model->CreateQuotationTimeCustomerLog($cust_log_post_data,$client_info);

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
			$this->quotation_model->CreateQuotationTimeCompanyInfoLog($company_info_log_post_data,$client_info);

			// INSERT TO COMPANY INFORMATION LOG TABLE
			// ================================================

			// ================================================
			// INSERT TO TERMS AND CONDITIONS LOG TABLE
			if($opportunity_data->currency_type_code=='INR')
				$table_name='terms_and_conditions_domestic_quotation';
			else
				$table_name='terms_and_conditions_export_quotation';

			$terms_condition_list=$this->quotation_model->get_terms_and_conditions($table_name,$client_info);
			if(count($terms_condition_list))
			{
				foreach($terms_condition_list as $term)
				{
					$term_log_post_data=array(	
											'quotation_id'=>$quotation_id,
											'name'=>$term->name,
											'value'=>$term->value
											);
					$this->quotation_model->CreateQuotationTimeTermsLog($term_log_post_data,$client_info);
				}
			}
			

			// INSERT TO TERMS AND CONDITIONS LOG TABLE
			// ============================================

			// =================================================
			// Create History log
			$update_by=$user_id;
			$date=date("Y-m-d H:i:s");
			$ip_addr = $this->input->ip_address();
			$message="A new Custom Quotation PDF (".$quote_no.") has been created.";
			$comment_title=QUOTATION_PDF_CREATE_APP;
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
			$this->history_model->CreateHistory($historydata,$client_info);	
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
			$this->lead_model->UpdateLead($update_lead_data,$lead_id,$client_info);
			// Insert Stage Log

			$is_prospect_exist=$this->lead_model->is_stage_exist_in_log($lead_id,8,$client_info);
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
				$this->lead_model->CreateLeadStageLog($stage_post_data,$client_info);	
			}
			

			// STAGE QUOTED
	        $stage_post_data=array(
					'lead_id'=>$lead_id,
					'stage_id'=>'2',
					'stage'=>'QUOTED',
					'stage_wise_msg'=>'',
					'create_datetime'=>date("Y-m-d H:i:s")
				);
	        $this->lead_model->CreateLeadStageLog($stage_post_data,$client_info);
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

    function send_quotation_popup_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $lead_id=$this->input->post('lead_id');
            
            if($client_id!='' && $user_id!='' && $lead_id!='')
            {

                $data=array();
                $data['flag']='';	
                $data['lead_id']=$lead_id;
                $data['user_id']=$user_id;
                $data['client_id']=$user_id;
                $data['cus_data']=$this->lead_model->GetLeadDataForQuotationPopup($lead_id,$client_info);			
                $data['opportunity_list']=$this->opportunity_model->GetOpportunityListAllForQuotationPopup($lead_id,$client_info);		
                $data['currency_list']=$this->Product_model->GetCurrencyListForQuotationPopup($client_info);
                		
                //$this->load->view('admin/lead/send_quotation_popup_view',$data);

                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'        => $data
                    ); 

            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function preview_quotation_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $opportunity_id=$this->input->post('opportunity_id');
            $quotation_id=$this->input->post('quotation_id');
            
            if($client_id!='' && $user_id!='' && $opportunity_id!='' && $quotation_id!='')
            {

                
                
                $data=array();
                $admin_session_data_user_data=$this->User_model->GetAdminData($user_id,$client_info);
                
                $admin_session_data_user_data_tmp_arr=json_decode(json_encode($admin_session_data_user_data),true);
                if(count($admin_session_data_user_data_tmp_arr)){
                    $data['admin_session_data_user_data']=$admin_session_data_user_data[0];
                }
                else{
                    $data['admin_session_data_user_data']=array();
                }
                
                $data['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id,$client_info);
                
                //print_r($data['quotation_data']); die();
                $data['quotation']=$data['quotation_data']['quotation'];
                $data['lead_opportunity']=$data['quotation_data']['lead_opportunity_data'];
                $data['company']=$data['quotation_data']['company_log'];
                $data['customer']=$data['quotation_data']['customer_log'];
                $data['terms']=$data['quotation_data']['terms_log_only_display_in_quotation'];
        
                $data['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id,$client_info);
                
                $data['opportunity_id']=$opportunity_id;
                $data['quotation_id']=$quotation_id;
                $data['selected_additional_charges']=$this->opportunity_model->get_selected_additional_charges($opportunity_id,$client_info);
                $data['curr_company']=$this->Setting_model->GetCompanyData($client_info);
                $data['download_url']='';
                //$this->load->view('admin/lead/preview_quotation',$data);





                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'        => $data
                    ); 

            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function download_quotation_post_BACKUP()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $opportunity_id=$this->input->post('opportunity_id');
            $quotation_id=$this->input->post('quotation_id');
            
            if($client_id!='' && $user_id!='' && $opportunity_id!='' && $quotation_id!='')
            { 
 
                $lead_id=get_value("lead_id","lead_opportunity","id=".$opportunity_id,$client_info);
                $quotation_data=$this->quotation_model->GetQuotationData($quotation_id,$client_info);

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
                    $this->lead_model->UpdateLead($update_lead_data,$lead_id,$client_info);
                    // Insert Stage Log
            
                    // STAGE PROSPECT
                    $stage_post_data=array(
                            'lead_id'=>$lead_id,
                            'stage_id'=>'8',
                            'stage'=>'PROSPECT',
                            'stage_wise_msg'=>'',
                            'create_datetime'=>date("Y-m-d H:i:s")
                        );
                    $this->lead_model->CreateLeadStageLog($stage_post_data,$client_info);
            
                    // STAGE QUOTED
                    $stage_post_data=array(
                            'lead_id'=>$lead_id,
                            'stage_id'=>'2',
                            'stage'=>'QUOTED',
                            'stage_wise_msg'=>'',
                            'create_datetime'=>date("Y-m-d H:i:s")
                        );
                    $this->lead_model->CreateLeadStageLog($stage_post_data,$client_info);
                    // Insert Status Log
                    $status_post_data=array(
                            'lead_id'=>$lead_id,
                            'status_id'=>'2',
                            'status'=>'HOT',
                            'create_datetime'=>date("Y-m-d H:i:s")
                        );
                    $this->lead_model->CreateLeadStatusLog($status_post_data,$client_info);
                    
                    $commnt="Quotation sent to client by Post";
                    $ip=$_SERVER['REMOTE_ADDR'];
                    $date=date("Y-m-d H:i:s");	
                    $update_by=$user_id;
                    $comment_title=SENT_TO_CLIENT_APP;
                    $historydata=array('title'=>$comment_title,'lead_id'=>$lead_id,'lead_opportunity_id'=>$opportunity_id,'comment'=>$commnt,'create_date'=>$date,'user_id'=>$update_by,'ip_address'=>$ip);

                    $this->history_model->CreateHistory($historydata,$client_info);

                    // Create KPI Log (Quotation Sent Count:4)
                    create_kpi_log(4,$update_by,'',date("Y-m-d H:i:s"),$client_info);

                    // product tagged with quoted lead
                    $prod_list=$this->quotation_model->GetQuotationProductList($quotation_id,$client_info);
                    if(count($prod_list))
                    {
                        foreach($prod_list AS $product)
                        {	
                            $p_name=get_value("name","product_varient","id=".$product->product_id,$client_info);
                            $lead_p_data=array(
                                'lead_id'=>$lead_id,
                                'lead_opportunity_id'=>$opportunity_id,
                                'quotation_id'=>$quotation_id,
                                'name'=>$p_name,
                                'product_id'=>$product->product_id,
                                'tag_type'=>'Q',
                                'created_at'=>date("Y-m-d H:i:s")
                            );
                            $this->lead_model->CreateLeadWiseProductTag($lead_p_data,$client_info);
                        }
                    }
                }

                
                //==================== generate_pdf ========================
                        
                $data=array();
                $admin_session_data_user_data=$this->User_model->GetAdminData($user_id,$client_info);

                $admin_session_data_user_data_tmp_arr=json_decode(json_encode($admin_session_data_user_data),true);
                if(count($admin_session_data_user_data_tmp_arr)){
                    $data['admin_session_data_user_data']=$admin_session_data_user_data[0];
                }
                else{
                    $data['admin_session_data_user_data']=array();
                }		
                $data['quotation_data']=$quotation_data;
                $data['quotation']=$data['quotation_data']['quotation'];
                $data['lead_opportunity']=$data['quotation_data']['lead_opportunity_data'];
                $data['company']=$data['quotation_data']['company_log'];
                $data['customer']=$data['quotation_data']['customer_log'];
                $data['terms']=$data['quotation_data']['terms_log_only_display_in_quotation'];

                $data['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id,$client_info);
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
                    $this->opportunity_model->UpdateLeadOportunity($post_data,$opportunity_id,$client_info);
                }
                // update status
                // =================================================== 

                // ===================================================
                // check is external quotation pdf uploaded
                $is_extermal_quote=$data['quotation']['is_extermal_quote'];
                if($is_extermal_quote=='Y')
                {
                    $file_name=$data['quotation']['file_name'];
                    $file_path="assets/uploads/clients/".$client_id."/quotation/".$file_name;
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
                $pdfFilePath = "assets/uploads/clients/".$client_id."/quotation/".$pdfFileName;

                $curr_date_time=date('Y-m-d H:i:s');
                $data['curr_datetime']=$curr_date_time;     
                $data['selected_additional_charges']=$this->opportunity_model->get_selected_additional_charges($opportunity_id,$client_info);
                $data['curr_company']=$this->Setting_model->GetCompanyData($client_info);
                

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


                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'        => $data
                    ); 

            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function download_quotation_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $opportunity_id=$this->input->post('opportunity_id');
            $quotation_id=$this->input->post('quotation_id');
            
            if($client_id!='' && $user_id!='' && $opportunity_id!='' && $quotation_id!='')
            { 

                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'        => 'https://dev.lmsbaba.com/clientportal/opportunity/download_quotation/'.$opportunity_id.'/'.$quotation_id
                    ); 

            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function clone_proporal_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $opportunity_id=$this->input->post('opportunity_id');
            $quotation_id=$this->input->post('quotation_id');
            
            if($client_id!='' && $user_id!='' && $opportunity_id!='' && $quotation_id!='')
            {
                
                $opportunity_data=$this->opportunity_model->GetOpportunityData($opportunity_id,$client_info);
                $quotation_data=$this->quotation_model->GetQuotationData($quotation_id,$client_info);
                
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
    
                $status=1; // Pending
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
                $new_opportunity_id=$this->opportunity_model->CreateLeadOportunity($data_opportunity,$client_info);	
                // INSERT TO OPPORTUNITY TABLE
                // ===========================================================
                // INSERT TO OPPORTUNITY WISE PRODUCT TABLE	
                $prod_list=array();		
                $prod_list=$this->opportunity_model->get_opportunity_product($opportunity_id,$client_info);
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
                    $this->Opportunity_product_model->CreateOportunityProduct($data_prd,$client_info);
                }
                // INSERT TO OPPORTUNITY WISE PRODUCT TABLE
                // ========================================================
    
    
                // ========================================================
                // INSERT TO OPPORTUNITY WISE ADDITIONAL CHARGES TABLE
                $additional_charges_arr=$this->opportunity_model->get_selected_additional_charges($opportunity_id,$client_info);			
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
                    $this->opportunity_model->create_opportunity_additional_charges($data_post,$client_info);
                                
                }
                // INSERT TO OPPORTUNITY WISE ADDITIONAL CHARGES TABLE
                // ========================================================
    
                // QUOTE NO. LOGIC - START
                $company_data=$this->Setting_model->GetCompanyData($client_info);
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
                $new_quotation_id=$this->quotation_model->CreateQuotation($quotation_post_data,$client_info);
                // INSERT TO QUOTE TABLE
                // ====================================================
    
                // =====================================================
                // INSERT TO QUOTATION WISE PRODUCT TABLE	
                $prod_list=array();
                $prod_list=$this->quotation_model->GetQuotationProductList($quotation_id,$client_info);		
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
                    $this->quotation_model->CreateQuotationProduct($quotation_product_data,$client_info);
    
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
                    $this->lead_model->CreateLeadWiseProductTag($lead_p_data,$client_info);
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
                $this->quotation_model->CreateQuotationTimeCustomerLog($cust_log_post_data,$client_info);
    
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
                $this->quotation_model->CreateQuotationTimeCompanyInfoLog($company_info_log_post_data,$client_info);
    
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
                        $this->quotation_model->CreateQuotationTimeTermsLog($term_log_post_data,$client_info);
                    }
                }		
    
                // INSERT TO TERMS AND CONDITIONS LOG TABLE
                // ===================================================
                // =================================================
                // Create History log
                $update_by=$user_id;
                $date=date("Y-m-d H:i:s");
                //$ip_addr=$_SERVER['REMOTE_ADDR'];
                $ip_addr = $this->input->ip_address();
                $message="A copy of the existing quotation &quot;".$opportunity_title.' - Copy'."&quot;";
                $comment_title=QUOTATION_COPY_APP;
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
                $this->history_model->CreateHistory($historydata,$client_info);	
                // Create History log	
                // =================================================			
                $msg='A copy of existing quotation "'.$opportunity_title.'" has been created!';
                

                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'        => $msg
                    ); 

            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function edit_qutation_view_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $lead_id=$this->post('lead_id');
            $opportunity_id=$this->post('opportunity_id');
            $quotation_id=$this->input->post('quotation_id');
            
            if($client_id!='' && $user_id!='' && $lead_id!='' && $opportunity_id!='' && $quotation_id!='')
            { 
                $error_msg='';
                $success_msg='';
                $return_status='';
                $data=array();
                
                $data['lead_id']=$lead_id;
                $data['enquiry_ref_id']=get_company_name_initials($client_info).' - '.$lead_id;
                $data['lead_data']=$this->lead_model->GetLeadData($lead_id,$client_info);	
                $data['opportunity_data']=$this->opportunity_model->GetOpportunityData($opportunity_id,$client_info);
                $data['opportunity_stage_list']=$this->opportunity_model->GetOpportunityStageListAll($client_info);	
                $data['source_list']=$this->source_model->GetSourceListAll($client_info);
                $data['currency_list']=$this->lead_model->GetCurrencyList($client_info);		
                $data['communication_list']=$this->lead_model->GetCommunicationList($client_info);
                $data['tmp_prod_list']=$this->Opportunity_product_model->GetOpportunityProductList($opportunity_id,$client_info);
        
                $admin_session_data_user_data=$this->User_model->GetAdminData($user_id,$client_info);
                $admin_session_data_user_data_tmp_arr=json_decode(json_encode($admin_session_data_user_data),true);
                $data['admin_session_data_user_data']=(count($admin_session_data_user_data_tmp_arr))?$admin_session_data_user_data[0]:array();
                $data['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id,$client_info);
                $data['quotation']=$data['quotation_data']['quotation'];
                if(isset($data['quotation']['letter_subject'])){
                    $data['quotation']['letter_subject']=str_replace("Enquity","Enquiry",$data['quotation']['letter_subject']);
                }
                $data['lead_opportunity']=$data['quotation_data']['lead_opportunity_data'];
                $data['company']=$data['quotation_data']['company_log'];
                $data['customer']=$data['quotation_data']['customer_log'];
                $data['terms']=$data['quotation_data']['terms_log'];				
        
                
                $data['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id,$client_info);
                $data['opportunity_id']=$opportunity_id;
                $data['quotation_id']=$quotation_id;
                $data['shipping_terms']=$this->opportunity_model->get_shipping_terms($client_info);
                $data['payment_terms']=$this->opportunity_model->get_payment_terms($client_info);
                $data['selected_additional_charges']=$this->opportunity_model->get_selected_additional_charges($opportunity_id,$client_info);	
        
                // Quotation Mail id 3		
                $data['email_forwarding_setting']=$this->Email_forwarding_setting_model->GetDetails(3,$client_info);
                $data['curr_company']=$this->Setting_model->GetCompanyData($client_info);
                $data['unit_type_list']=$this->Product_model->GetUnitList($client_info);	
                	
                //$this->load->view('admin/quotation/rander_automated_quotation_view_ajax',$data);

                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'        => $data
                    ); 

            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function qutation_send_to_buyer_by_mail_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $lead_id=$this->post('lead_id');
            $opportunity_id=$this->post('opportunity_id');
            $quotation_id=$this->post('quotation_id');
            $is_resend=$this->post('is_resend');
            
            if($client_id!='' && $user_id!='' && $lead_id!='' && $opportunity_id!='' && $quotation_id!='' && $is_resend!='')
            { 

                $lead_info=$this->lead_model->GetLeadData($lead_id,$client_info);
                $assigned_user_id=$lead_info->assigned_user_id;	
                $assigned_to_user_data=$this->User_model->get_employee_details($assigned_user_id,$client_info);
                $list=array();
                
                $list['assigned_to_user']=$assigned_to_user_data;
                $list['is_resend']=$is_resend;
                $list['opportunity_id']=$opportunity_id;
                $list['quotation_id']=$quotation_id;
                $list['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id,$client_info);
                $list['quotation']=$list['quotation_data']['quotation'];
                $list['lead_opportunity']=$list['quotation_data']['lead_opportunity_data'];
                $list['company']=$list['quotation_data']['company_log'];
                if($list['quotation_data']['quotation']['customer_id']){
                    $customer=$this->customer_model->GetQuotationWiseCustomerData($list['quotation_data']['quotation']['customer_id'],$client_info);			
                }
                else{
                    $customer=array();
                }
                $list['customer']=$customer;
                $list['user_list']=$this->User_model->GetUserListAll('',$client_info);
                $list['is_extermal_quote']=$list['quotation_data']['quotation']['is_extermal_quote'];

                $qid=$list['quotation_data']['quotation']['id'];
                $opportunity_id=$list['quotation_data']['quotation']['opportunity_id'];
                $d=explode('-', $list['quotation_data']['quotation']['quote_date']);
                $tmp_d=$d[0];
                $company_name=$list['quotation_data']['company_log']['name'];
                $list['mail_subject'] = "# ".$opportunity_id."/".$tmp_d." - Quote from ".$company_name." against your Enquiry";

                $list['curr_company']=$this->Setting_model->GetCompanyData($client_info);
                $list['quick_reply_comments']=$this->pre_define_comment_model->GetLeadUpdatePreDefineComments($user_id,'LU',$client_info);
                $list['quick_reply_count']=(isset($data['quick_reply_comments']))?count($data['quick_reply_comments']):0;

                // $html = $this->load->view('admin/lead/quotation_send_html_view',$list,TRUE);
                // $data =array ("html"=>$html);
                // $this->output->set_content_type('application/json');
                // echo json_encode($data);
                // exit;

                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'        => $list
                    ); 

            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function qutation_send_to_buyer_by_mail_confrim_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $lead_id=$this->post('lead_id');
            $opportunity_id=$this->post('opportunity_id');
            $quotation_id=$this->post('quotation_id');
            $is_resend=$this->post('is_resend');
            $update_by=$user_id;		
            $to_mail_arr=$this->post('to_mail');
            if(is_array($to_mail_arr)){
                $to_mail_str=implode(",", $to_mail_arr);
            } else {
                $to_mail_str=$to_mail_arr;
            }
            $cc_mail_tmp_arr=$this->post('cc_mail');
            $reply_email_body=$this->post('reply_email_body');
            $is_company_brochure_attached_in_quotation_tmp=($this->post('is_company_brochure_attached_in_quotation'))?'Y':'N';
            
            if($client_id!='' && $user_id!='' && $lead_id!='' && $opportunity_id!='' && $quotation_id!='' && $is_resend!='')
            { 
                $email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(3,$client_info);
			
                $session_data=$this->User_model->get_employee_details($user_id,$client_info);

                
                
    
                if($to_mail_str)
                {	        
                    $quotation_data=$this->quotation_model->GetQuotationData($quotation_id,$client_info);
                    $lead_info=$this->lead_model->GetLeadData($lead_id,$client_info);
                    $assigned_user_id=$lead_info->assigned_user_id;

                    
                    if($email_forwarding_setting['is_mail_send']=='Y')
                    {
                        $m_email=get_manager_and_skip_manager_email_arr($assigned_user_id,$client_info);
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
                        $self_cc_mail=get_value("email","user","id=".$assigned_user_id,$client_info);
                        $update_by_name=get_value("name","user","id=".$assigned_user_id,$client_info);
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
                            $this->load->library('upload', '');
                            if($_FILES['lead_attach_file']['tmp_name'])
                            {
                                $dataInfo = array();
                                $files = $_FILES;
                                $cpt = count($_FILES['lead_attach_file']['name']);
                                $config = array(
                                'upload_path' => "assets/uploads/clients/log/",
                                'file_ext_tolower'=>TRUE,
                                'encrypt_name'=>FALSE,
                                'allowed_types' => "gif|jpg|jpeg|png|pdf|doc|docx|xlsx|xlsb|xls|csv",                        
                                'max_size' => "1000000", //KB
                                'overwrite'=>FALSE, 
                                );
                    
                                $this->upload->initialize($config);
                                for($i=0; $i<$cpt; $i++)
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
                                        $attach_filename = $dataInfo['file_name']; //Image Name
                                        array_push($mail_attached_arr, $config['upload_path'].$attach_filename);
                                    }
                                                    
                                }
                            }
    
    
                            
                            //$attach_file_path=$this->generate_pdf($opportunity_id,$quotation_id,'F');
                            //array_push($mail_attached_arr, $attach_file_path);
    
    
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
                                                
                                                $product_brochure="assets/uploads/clients/".$client_id."/product/pdf/".$product->brochure;
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
                                    $company_brochure="assets/uploads/clients/".$client_id."/company/brochure/".$c['brochure_file'];
                                    array_push($mail_attached_arr, $company_brochure);
                                }
                            }
                            
                            // company brochure attachment
                            // ------------------------------
                            
                            $assigned_to_user_data=$this->User_model->get_employee_details($assigned_user_id,$client_info);
                            
                            $e_data['assigned_to_user']=$assigned_to_user_data;
                            $e_data['lead_info']=$lead_info;
    
    
                            $admin_session_data_user_data=$this->User_model->get_employee_details($user_id,$client_info);
                            $e_data['admin_session_data_user_data']=$admin_session_data_user_data;
                            $e_data['quotation']=$quotation_data['quotation'];
                            $e_data['lead_opportunity']=$quotation_data['lead_opportunity_data'];
                            $e_data['company']=$quotation_data['company_log'];
                            $e_data['customer']=$quotation_data['customer_log'];
                            $e_data['terms']=$quotation_data['terms_log_only_display_in_quotation'];
                            $e_data['product_list']=$quotation_data['product_list'];
                            $e_data['reply_email_body']=$reply_email_body;
                            //$template_str = $this->load->view('admin/email_template/template/quotation_sent_view', $e_data, true);
    
                            $qid=$e_data['quotation']['id'];
                            $opportunity_id=$e_data['quotation']['opportunity_id'];
                            $d=explode('-', $e_data['quotation']['quote_date']);
                            $tmp_d=$d[0];
                            $company_name=$e_data['company']['name'];
                            // Developer mail start
                            $this->load->library('mail_lib');
                            $mail_data = array();
                            $mail_data['from_mail']     = $self_cc_mail;
                            $mail_data['from_name']     = $update_by_name;
                            $mail_data['to_mail']       = $to_mail;		        
                            $mail_data['cc_mail']       = $cc_mail_str;
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
                    $update_by=$user_id;
                    $comment_title=SENT_TO_CLIENT_APP;
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
    
                    $this->history_model->CreateHistory($historydata,$client_info);
    
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
                







                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'        => $list
                    ); 

            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function qutation_send_to_buyer_by_whatsapp_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $lid=$this->post('lead_id');
            $oppid=$this->post('opportunity_id');
            $quotation_id=$this->post('quotation_id');
            $is_quoted=$this->post('is_quoted');
            
            if($client_id!='' && $user_id!='' && $lid!='' && $oppid!='' && $quotation_id!='' && $is_quoted!='')
            { 
                $lead_info=$this->lead_model->GetLeadData($lid,$client_info);
                $e_data['lead_info']=$lead_info;
                $assigned_user_id=$lead_info->assigned_user_id;	
                $assigned_to_user_data=$this->User_model->get_employee_details($assigned_user_id,$client_info);
                $e_data['assigned_to_user']=$assigned_to_user_data;
                $quotation_data=$this->quotation_model->GetQuotationData($quotation_id,$client_info);
                $admin_session_data_user_data=$this->User_model->get_employee_details($user_id,$client_info);
                $e_data['quotation']=$quotation_data['quotation'];
                $e_data['company']=$quotation_data['company_log'];
                $e_data['customer']=$quotation_data['customer_log'];
                //$template_str = $this->load->view('admin/lead/quotation_sent_by_whatsapp_view', $e_data, true);
                $e_data['recipient_mobile']='+'.$lead_info->cus_country_code.$lead_info->cus_mobile;
                
                if($is_quoted=='Y')
                {
        
                    // ===================================================
                    // Update Status
                    if($quotation_data['lead_opportunity_data']['status']==1)
                    {
                        $lead_id=$lid;
                        $update_lead_data = array(
                            'current_stage_id' =>'2',
                            'current_stage' =>'QUOTED',
                            'current_stage_wise_msg' =>'',
                            'modify_date'=>date("Y-m-d")
                        );								
                        $this->lead_model->UpdateLead($update_lead_data,$lead_id,$client_info);
                        // Insert Stage Log
        
                        // STAGE PROSPECT
                        $stage_post_data=array(
                                'lead_id'=>$lead_id,
                                'stage_id'=>'8',
                                'stage'=>'PROSPECT',
                                'stage_wise_msg'=>'',
                                'create_datetime'=>date("Y-m-d H:i:s")
                            );
                        $this->lead_model->CreateLeadStageLog($stage_post_data,$client_info);
        
                        // STAGE QUOTED
                        $stage_post_data=array(
                                'lead_id'=>$lead_id,
                                'stage_id'=>'2',
                                'stage'=>'QUOTED',
                                'stage_wise_msg'=>'',
                                'create_datetime'=>date("Y-m-d H:i:s")
                            );
                        $this->lead_model->CreateLeadStageLog($stage_post_data,$client_info);
                        // Insert Status Log
                        $status_post_data=array(
                                'lead_id'=>$lead_id,
                                'status_id'=>'2',
                                'status'=>'HOT',
                                'create_datetime'=>date("Y-m-d H:i:s")
                            );
                        
                        $commnt="Quotation sent to client by WhatsApp";
                        $ip=$_SERVER['REMOTE_ADDR'];
                        $date=date("Y-m-d H:i:s");	
                        $update_by=$user_id;
                        $comment_title=SENT_TO_CLIENT_APP;
                        $historydata=array('title'=>$comment_title,'lead_id'=>$lead_id,'lead_opportunity_id'=>$opportunity_id,'comment'=>$commnt,'create_date'=>$date,'user_id'=>$update_by,'ip_address'=>$ip);
                        $this->history_model->CreateHistory($historydata,$client_info);
                        $post_data=array(
                                        'status'=>2,
                                        'modify_date'=>date("Y-m-d")
                                        );
                        $this->opportunity_model->UpdateLeadOportunity($post_data,$oppid,$client_info);
        
                        // Create KPI Log (Quotation Sent Count:4)
                        create_kpi_log(4,$update_by,'',date("Y-m-d H:i:s"),$client_info);
        
                        // product tagged with quoted lead
                        $prod_list=$this->quotation_model->GetQuotationProductList($quotation_id,$client_info);
                        if(count($prod_list))
                        {
                            foreach($prod_list AS $product)
                            {	
                                $p_name=get_value("name","product_varient","id=".$product->product_id,$client_info);
                                $lead_p_data=array(
                                    'lead_id'=>$lead_id,
                                    'lead_opportunity_id'=>$oppid,
                                    'quotation_id'=>$quotation_id,
                                    'name'=>$p_name,
                                    'product_id'=>$product->product_id,
                                    'tag_type'=>'Q',
                                    'created_at'=>date("Y-m-d H:i:s")
                                );
                                $this->lead_model->CreateLeadWiseProductTag($lead_p_data,$client_info);
                            }
                        }
                        // --------------------
                    }
                    // update status
                    // =================================================== 
                }
                // $result["html"] = $template_str;
                // echo json_encode($result);
                // exit(0); 

                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'        => $e_data
                    ); 

            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function client_wise_country_list_post()
    {
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);

            if($client_id!='')
            {
                $country_list=$this->countries_model->GetCountriesList('',$client_info);
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'country_list'        => $country_list
                    ); 
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            } 
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function client_wise_lead_source_list_post()
    {
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);

            if($client_id!='')
            {
                $source_list=$this->source_model->GetSourceListAll($client_info);
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'source_list'        => $source_list
                    ); 
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            } 
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function assign_to_list_post()
    {
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');

            if($client_id!='' && $user_id!='')
            {

                if($user_id==1)
                {	   			
                    $m_id='0';
                }
                else
                {
                    $m_id=$this->User_model->get_manager_id($user_id,$client_info);
                }
                $user_ids = $this->User_model->get_under_employee_ids($m_id,0,$client_info);			
                array_push($user_ids, $user_id);
                $user_ids_str=implode(',', $user_ids);
                $user_list=$this->User_model->GetUserList($user_ids_str,$client_info);
                
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'user_list'          => $user_list
                ); 
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            } 
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function create_temp_product_list_post()
    {
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');

            if($client_id!='' && $user_id!='')
            {

                $data=NULL;
                $prod_id=$this->post('product_id');
                $opp_id=$this->post('opportunity_id');
                $data['product_data']=array();
                $prod_arr=explode(',',$prod_id);
                $i=0;
                $date=date('Y-m-d');
                foreach($prod_arr as $prod_data)
                {
                    if($prod_data!='')
                    {
                        $chk_prod_temp=$this->Product_model->TempProdExistCheck($prod_data,$user_id,$client_info);
                        if(!$chk_prod_temp)
                        {
                            $data['product_data'][$i]=$this->Product_model->GetProductListById($prod_data,$client_info);				
                            
                            $qtn=($data['product_data'][$i]->minimum_order_quantity>0)?$data['product_data'][$i]->minimum_order_quantity:'1';
                            $new_data=array(
                                'opportunity_id'=>$opp_id,
                                'user_id'=>$user_id,
                                'product_id'=>$prod_data,
                                'name'=>$data['product_data'][$i]->name,
                                'unit'=>$data['product_data'][$i]->unit,
                                'unit_type'=>$data['product_data'][$i]->unit_type,
                                'quantity'=>$qtn,
                                'price'=>$data['product_data'][$i]->price,
                                'currency_type'=>$data['product_data'][$i]->currency_type,
                                'discount'=>'',
                                'gst'=>$data['product_data'][$i]->gst_percentage,
                                'create_date'=>$date
                            );
                            
                            $create_data=$this->Product_model->CreateTempProduct($new_data,$client_info);
                            $i++;
                            
                        }
                        
                    }			
                }
                
                
                $temp_product_list=$this->Product_model->GetTempProductList($user_id,$client_info);
                $tmp_selected_prod_arr=array();
                foreach($temp_product_list as $prod)
                {
                    $code=($prod->code)?' - '.$prod->code:'';
                    $tmp_selected_prod_arr[]=	$prod->product_id.'@'.$prod->name.$code;	
                }

                // $data['tagged_product']=array();
                // $data['product_list']=array();
                //$result['html'] =$this->load->view('admin/product/product_list_ajax',$data,true);
                
                $data['selected_prod_id'] = $tmp_selected_prod_arr;

                
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'          => $data
                ); 
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            } 
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function get_created_temp_product_list_post()
    {
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');

            if($client_id!='' && $user_id!='')
            {
                $temp_pro['product_list']=$this->Product_model->GetTempProductList($user_id,$client_info);
                
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'          => $temp_pro
                ); 
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            } 
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function delete_product_from_created_temp_product_list_post()
    {
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $product_id=$this->post('product_id');

            if($client_id!='' && $user_id!='' && $product_id!='')
            {
                $this->Product_model->DeleteTempProduct($product_id,$user_id,$client_info);
                $temp_pro['available_product_list']=$this->Product_model->GetTempProductList($user_id,$client_info);
                
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'          => $temp_pro
                ); 
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            } 
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function generate_automated_quotation_post()
    {
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $lead_id=$this->post('lead_id');
            $currency_type=1;
            
            if($client_id!='' && $user_id!='' && $lead_id!='')
            {

              
                $lead_data=$this->lead_model->GetLeadData($lead_id,$client_info);
                if(!$lead_data){
                    
                    $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Something went wrong. Please try again.'
                        );
                    $this->response($data, 200);
                    exit(0);
                }

                $opportunity_title=$lead_data->title;
                $deal_value='';				
                $status=1; // Pending
                $tmp_prod_list=$this->Product_model->GetTempProductList($user_id,$client_info);

                if(count($tmp_prod_list))
                {
                    $data_opportunity=array(
                        'lead_id'=>$lead_id,
                        'opportunity_title'=>$opportunity_title,
                        'currency_type'=>$currency_type,
                        'status'=>$status,
                        'create_date'=>date("Y-m-d H:i:s"),
                        'modify_date'=>date("Y-m-d H:i:s")
                    );	

                    $create_opportunity=$this->opportunity_model->CreateLeadOportunity($data_opportunity,$client_info);	

                    if($create_opportunity==false){                        
                        $data = array(
                            'api_token_success'  => 1,
                            'api_action_success' => 1,
                            'api_syntax_success' => 0,
                            'error'          => 'Something went wrong. Please try again.'
                            );
                        $this->response($data, 200);
                        exit(0);
                    }
                    

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
                        $create_prod=$this->Opportunity_product_model->CreateOportunityProduct($data_prd,$client_info);
                    }
                    $this->Product_model->DeleteTempProduct('',$user_id,$client_info);
                    
                    // -----------------------------------------------------
                    // Update deal value
                    $data_opportunity_update=array(
                    'deal_value'=>$deal_value_tmp,
                    'modify_date'=>date("Y-m-d H:i:s")
                    );	
                    $this->opportunity_model->UpdateLeadOportunity($data_opportunity_update,$create_opportunity,$client_info);
                    
                    $admin_session_data_user_data_tmp=$this->User_model->GetAdminData($user_id,$client_info);
                    $admin_session_data_user_data_tmp_arr=json_decode(json_encode($admin_session_data_user_data_tmp),true);
                    if(count($admin_session_data_user_data_tmp_arr)){
                        $admin_session_data_user_data=$admin_session_data_user_data_tmp[0];
                    }
                    else{
                        $admin_session_data_user_data=array();
                    }
                    // $admin_session_data_user_data=($admin_session_data_user_data_tmp[0])?$admin_session_data_user_data_tmp[0]:array();
                    $data['admin_session_data_user_data']=$admin_session_data_user_data;

                    $opportunity_id=$create_opportunity;
                    $data['opportunity_id']=$opportunity_id;

                    $opportunity_data=$this->opportunity_model->GetOpportunityData($opportunity_id,$client_info);
                    $data['opportunity_data']=$opportunity_data;
                    // $lead_id=$opportunity_data->lead_id;
                    
                    $data['lead_data']=$lead_data;
                    $customer_data=$this->customer_model->GetCustomerData($lead_data->customer_id,$client_info);
                    $data['customer_data']=$customer_data;
                    $company_data=$this->Setting_model->GetCompanyData($client_info);
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
                    $quotation_id=$this->quotation_model->CreateQuotation($quotation_post_data,$client_info);

                    if($quotation_id){
                        $customer_id_in_created_quotation=get_value("customer_id","quotation","id=".$quotation_id);
                        if($customer_id_in_created_quotation=='0'){
                            if($lead_id!='' && $quotation_id!='' && $opportunity_id!=''){
                                $arg=array();
                                $arg['lead_id']=$lead_id;
                                $arg['quotation_id']=$quotation_id;
                                $arg['opportunity_id']=$opportunity_id;
                                $arg['table_arr']=array('lead_opportunity','opportunity_product','quotation');
                                $this->quotation_model->qutation_delete2($arg,$client_info);
                            }
                            $data = array(
                                'api_token_success'  => 1,
                                'api_action_success' => 1,
                                'api_syntax_success' => 0,
                                'error'          => 'Something went wrong. Please try again.'
                                );
                            $this->response($data, 200);
                            exit(0);
                        }
                    }					
                    
                    
                    $prod_list=$this->opportunity_model->get_opportunity_product($opportunity_id,$client_info);
                    $data['prod_list']=$prod_list;
                    
                    foreach($prod_list as $prod_data)
                    {			
                        $get_product=$this->Product_model->get_product($prod_data->product_id,$client_info);

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
                        $this->quotation_model->CreateQuotationProduct($quotation_product_data,$client_info);
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
                    $this->quotation_model->CreateQuotationTimeCustomerLog($cust_log_post_data,$client_info);

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
                    $this->quotation_model->CreateQuotationTimeCompanyInfoLog($company_info_log_post_data,$client_info);

                    // INSERT TO COMPANY INFORMATION LOG TABLE
                    // ======================================================

                    // ======================================================
                    // INSERT TO TERMS AND CONDITIONS LOG TABLE
                    if($opportunity_data->currency_type_code=='INR')
                        $table_name='terms_and_conditions_domestic_quotation';
                    else
                        $table_name='terms_and_conditions_export_quotation';

                    $terms_condition_list=$this->quotation_model->get_terms_and_conditions($table_name,$client_info);
                    if(count($terms_condition_list))
                    {
                        foreach($terms_condition_list as $term)
                        {
                            $term_log_post_data=array(	
                            'quotation_id'=>$quotation_id,
                            'name'=>$term->name,
                            'value'=>$term->value
                            );
                            $this->quotation_model->CreateQuotationTimeTermsLog($term_log_post_data,$client_info);
                        }
                    }
                    

                    // INSERT TO TERMS AND CONDITIONS LOG TABLE
                    // ===================================================
                    

                    // ===================================================
                    // Create History log
                    $update_by=$user_id;
                    $date=date("Y-m-d H:i:s");
                    $ip_addr = $this->input->ip_address();
                    $message="A new Automated Quotation PDF (".$quote_no.") has been created.";
                    $comment_title=QUOTATION_PDF_CREATE_APP;
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
                    $this->history_model->CreateHistory($historydata,$client_info);	
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
                            

                if($return_status=='success'){
                    $data = array(
                        'api_token_success'  => 1,
                        'api_syntax_success' => 1,
                        'api_action_success' => 1,
                        'error'              => '',
                        'result_data'        => $result
                    );
                } else {
                    $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => $error_msg
                        );
                }






                
                 
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            } 
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function delete_qutation_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $opportunity_id=$this->post('opportunity_id');
            $quotation_id=$this->post('quotation_id');
            
            if($client_id!='' && $user_id!='' && $opportunity_id!='' && $quotation_id!='')
            { 

                $lead_id=get_value("lead_id","lead_opportunity","id=".$opportunity_id,$client_info);
                $this->quotation_model->qutation_delete($lead_id,$quotation_id,$opportunity_id,$client_info);
                
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => ''
                    );
            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function delete_product_from_qutation_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $opportunity_id=$this->post('opportunity_id');
            $quotation_id=$this->post('quotation_id');
            $pid=$this->post('product_id');
            $id=$this->post('data_id');
            
            if($client_id!='' && $user_id!='' && $opportunity_id!='' && $quotation_id!='' && $pid!='' && $id!='')
            {  
                $this->opportunity_model->delete_opp_product_by_opp_and_pid($opportunity_id,$pid,$client_info);
                $del_data=$this->quotation_model->delete_product($id,$client_info);
        
                if($del_data==true){
                    $result["status"] = 'success';
                }
                else{
                    $result["status"] = 'fail';
                }
        
                $list=array();
                $product_list=$this->quotation_model->GetQuotationProductList($quotation_id,$client_info);
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
                // =======================================
                // CALCULATE ADDITIONAL PRICE
                
                $additional_charges_list=$this->opportunity_model->get_selected_additional_charges($opportunity_id,$client_info);
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
                $list['selected_additional_charges']=$additional_charges_list;
                $list['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id,$client_info);
        
                $list['currency_list']=$this->lead_model->GetCurrencyList();
                $list['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id,$client_info);
        
                $list['quotation_id']=$quotation_id;
                $list['opportunity_id']=$opportunity_id;
                $list['unit_type_list']=$this->Product_model->GetUnitList($client_info);  
                //$html = $this->load->view('admin/quotation/updated_product_selected_list_ajax',$list,TRUE);
                //$result['html'] = $html;
        
                // ------------------------------
                $data_opportunity_update=array(
                                        'deal_value'=>$sub_total,
                                        'modify_date'=>date("Y-m-d H:i:s")
                                        );	
                $this->opportunity_model->UpdateLeadOportunity($data_opportunity_update,$opportunity_id,$client_info);
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
                $result['all_list_data']=$list;





                
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'        => $result
                    );
            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function change_opportunity_currency_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $opportunity_id=$this->post('opportunity_id');
            $quotation_id=$this->post('quotation_id');
            $currency_type=$this->post('currency_type');
            
            if($client_id!='' && $user_id!='' && $opportunity_id!='' && $quotation_id!='' && $currency_type!='')
            { 
                // ---------------------------------------------------
                $data_update=array('currency_type'=>$currency_type);
                $this->opportunity_model->UpdateLeadOportunity($data_update,$opportunity_id,$client_info);
                // ---------------------------------------------------

                // ---------------------------------------------------
                $currency_code=get_value('code','currency','id='.$currency_type);
                $data_update2=array('currency_type'=>$currency_code);
                $this->quotation_model->UpdateQuotation($data_update2,$quotation_id,$client_info);
                // ---------------------------------------------------

                // ======================================================
                // INSERT TO TERMS AND CONDITIONS LOG TABLE
                if($currency_code=='INR')
                    $table_name='terms_and_conditions_domestic_quotation';
                else
                    $table_name='terms_and_conditions_export_quotation';

                $terms_condition_list=$this->quotation_model->get_terms_and_conditions($table_name,$client_info);
                $this->quotation_model->deleteQuotationTermsLog($quotation_id,$client_info);
                if(count($terms_condition_list))
                {
                    foreach($terms_condition_list as $term)
                    {
                        $term_log_post_data=array(	
                                                'quotation_id'=>$quotation_id,
                                                'name'=>$term->name,
                                                'value'=>$term->value
                                                );
                        $this->quotation_model->CreateQuotationTimeTermsLog($term_log_post_data,$client_info);
                    }
                }
                // INSERT TO TERMS AND CONDITIONS LOG TABLE
                // ===================================================


                // ---------------------------------------------------
                $opportunity_product_list=$this->opportunity_model->get_opportunity_product($opportunity_id,$client_info);	
                if(count($opportunity_product_list))
                {
                    foreach($opportunity_product_list as $opp_prod)
                    {			
                        $data_update_tmp=array('currency_type'=>$currency_type);
                        $this->Opportunity_product_model->update($data_update_tmp,$opp_prod->id,$client_info);
                    }
                }
                // ---------------------------------------------------

                if($currency_type!=1)
                {
                    $product_list=$this->quotation_model->GetQuotationProductList($quotation_id,$client_info);
                    if(count($product_list))
                    {
                        foreach($product_list as $output)
                        {
                            $data_post=array(						
                                        'gst'=>'0'
                                        );
                            $this->quotation_model->update_product($data_post,$output->id,$client_info);
                        }
                    }		

                    $additional_charges_list=$this->opportunity_model->get_selected_additional_charges($opportunity_id,$client_info);
                    if(count($additional_charges_list))
                    {
                        foreach($additional_charges_list as $charge)
                        {
                            $data_post=array(						
                                        'gst'=>'0'
                                        );
                            $this->opportunity_model->update_opportunity_additional_charges($data_post,$charge->id,$client_info);
                        }
                    }
                }

                $list=array();
                $product_list=array();
                $product_list=$this->quotation_model->GetQuotationProductList($quotation_id,$client_info);
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
                $additional_charges_list=$this->opportunity_model->get_selected_additional_charges($opportunity_id,$client_info);
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
                $list['prod_list']=$this->quotation_model->GetQuotationProductList($quotation_id,$client_info);
                $list['currency_list']=$this->lead_model->GetCurrencyList($client_info);
                $list['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id,$client_info);
                $list['unit_type_list']=$this->Product_model->GetUnitList($client_info);
                //$html = $this->load->view('admin/quotation/updated_product_selected_list_ajax',$list,TRUE);

                $list2['terms']=$list['quotation_data']['terms_log'];
                //$terms_html = $this->load->view('admin/quotation/updated_terms_log_ajax',$list2,TRUE);

                // ---------------------------------
                $data_opportunity_update=array(
                                        'deal_value'=>$sub_total,
                                        'modify_date'=>date("Y-m-d H:i:s")
                                        );	
                $this->opportunity_model->UpdateLeadOportunity($data_opportunity_update,$opportunity_id,$client_info);
                // -----------------------------------
                //$result['html'] = $html;
                $result['total_sale_price'] = number_format($row_final_price,2);
                $result["total_deal_value"]=number_format($sub_total,2);

                $result["total_price"]=number_format($total_price,2);
                $result["total_discount"]=number_format($total_discounted_price,2);
                $result["total_tax"]=number_format($total_tax_price,2);
                $result["grand_total_round_off"]=number_format(round($sub_total),2);
                $result["number_to_word_final_amount"]=number_to_word(round($sub_total));
                $result['currency_code']=$currency_code;
                $result['all_list_data']=$list;
                //$result['terms_html']=$terms_html;

                
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'        => $result
                    );
            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function product_discount_type_update_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $opportunity_id=$this->post('opportunity_id');
            $quotation_id=$this->post('quotation_id');
            $is_discount_p_or_a=$this->post('discount_type');
            
            if($client_id!='' && $user_id!='' && $opportunity_id!='' && $quotation_id!='' && $is_discount_p_or_a!='')
            { 
              
                $postData=array();
                $postData=array('is_discount_p_or_a'=>$is_discount_p_or_a);
                $r=$this->quotation_model->UpdateQuotationProductByQuotationId($postData,$quotation_id,$client_info);
        
                $postData=array();
                $postData=array('is_discount_p_or_a'=>$is_discount_p_or_a);
                $r2=$this->quotation_model->UpdateQuotationChargesByOppId($postData,$opportunity_id,$client_info);
        
        
                
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
                $product_list=$this->quotation_model->GetQuotationProductList($quotation_id,$client_info);
                
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
                
                $additional_charges_list=$this->opportunity_model->get_selected_additional_charges($opportunity_id,$client_info);
                
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
                $list['currency_list']=$this->lead_model->GetCurrencyList($client_info);
                $list['quotation_data']=$this->quotation_model->GetQuotationData($quotation_id,$client_info);
                $list['unit_type_list']=$this->Product_model->GetUnitList($client_info);
                //$html = $this->load->view('admin/quotation/updated_product_selected_list_ajax',$list,TRUE);
                
                
        
                // ---------------------------------
                $data_opportunity_update=array(
                                        'deal_value'=>$sub_total,
                                        'modify_date'=>date("Y-m-d H:i:s")
                                        );  
                $this->opportunity_model->UpdateLeadOportunity($data_opportunity_update,$opportunity_id,$client_info);
                // ------------------------------------
                $result['total_sale_price'] = number_format($row_final_price,2);
                $result["total_deal_value"]=number_format($sub_total,2);
        
                $result["total_price"]=number_format($total_price,2);
                $result["total_discount"]=number_format($total_discounted_price,2);
                $result["total_tax"]=number_format($total_tax_price,2);
                $result["grand_total_round_off"]=number_format(round($sub_total),2);
                $result["number_to_word_final_amount"]=number_to_word(round($sub_total));
                $result["opportunity_id"] =$opportunity_id;
                
                $result["status"] = 'success';
                $result["all_list_data"] = $list;
                //$result['html'] = $html;

                
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'        => $result
                    );
            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function update_quotation_product_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $opportunity_id=$this->post('opportunity_id');
            $quotation_id=$this->post('quotation_id');
            $pid=$this->post('product_id');
            $id=$this->post('data_id');
            $field=$this->post('update_field_name');
            $value=$this->post('update_field_value');
            
            if($client_id!='' && $user_id!='' && $opportunity_id!='' && $quotation_id!='' && $pid!='' && $id!='' && $field!='' && $value!='')
            {

                $data_post=array(						
                    $field=>$value
                    );
                $update_data=$this->quotation_model->update_product($data_post,$id,$client_info);

                if($update_data==true)
                    $result["status"] = 'success';
                else
                    $result["status"] = 'fail';
                        
                $list=array();
                $product_list=$this->quotation_model->GetQuotationProductList($quotation_id,$client_info);
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
                $product=$this->quotation_model->GetQuotationProduct($id,$client_info);
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
                
                $additional_charges_list=$this->opportunity_model->get_selected_additional_charges($opportunity_id,$client_info);
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
                $this->opportunity_model->UpdateLeadOportunity($data_opportunity_update,$opportunity_id,$client_info);
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


                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'        => $result
                    );
            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function quotation_letter_field_update_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $quotation_id=$this->post('quotation_id');
            $updated_field_id=$this->post('data_id');
            $updated_field_name=$this->post('update_field_name');
            $updated_content=$this->post('update_field_value');
            
            if($client_id!='' && $user_id!='' && $quotation_id!='' && $updated_field_name!='' && $updated_content!='' && ($updated_field_name!='product_name' || ($updated_field_name=='product_name' && $updated_field_id!='')))
            {
       
                if($updated_field_name=='quotation_bank_details1' || $updated_field_name=='quotation_bank_details2') //quotation_time_company_setting_log
                {
                    $data = array(
                        $updated_field_name=>$updated_content
                    );
                    $this->quotation_model->UpdateQuotationTimeCompanyInfoLogByQuotationId($data,$quotation_id,$client_info);
                }
                else if($updated_field_name=='product_name')
                {
                    $data = array(
                        $updated_field_name=>$updated_content
                    );
                    $update_data=$this->quotation_model->update_product($data,$updated_field_id,$client_info);
                }
                else if($updated_field_name=='additional_charge_name')
                {
                    $data=array(						
                                $updated_field_name=>$updated_content
                                );
                    $update_data=$this->opportunity_model->update_opportunity_additional_charges($data,$updated_field_id,$client_info);
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
                            $this->quotation_model->UpdateQuotationProductByQid($post_data,$quotation_id,$client_info);
                        }
                    }
        
                    if($updated_field_name=='is_product_youtube_url_show_in_quotation')
                    {	
                        if($updated_content=='N')
                        {
                            $post_data=array('is_youtube_video_url_show'=>'N');
                            $this->quotation_model->UpdateQuotationProductByQid($post_data,$quotation_id,$client_info);
                        }
                    }
        
                    if($updated_field_name=='is_product_brochure_attached_in_quotation')
                    {	
                        if($updated_content=='N')
                        {
                            $post_data=array('is_brochure_attached'=>'N');
                            $this->quotation_model->UpdateQuotationProductByQid($post_data,$quotation_id,$client_info);
                        }
                    }   
        
                    if($updated_field_name=='is_hide_gst_in_quotation')
                    {	
                        if($updated_content=='N')
                        {
                            $post_data=array('is_show_gst_extra_in_quotation'=>'N');
        
                            $this->quotation_model->UpdateQuotation($post_data,$quotation_id,$client_info);
                        }
                    }     
        
                    $data = array(
                                $updated_field_name=>$updated_content,
                                'modify_date' =>date('Y-m-d H:i:s')
                            );
                    $this->quotation_model->UpdateQuotation($data,$quotation_id,$client_info);
                }
                

                $result =array (
                                "status"=>"Quation successfully updated!"
                            );


                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'        => $result
                    );
            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function is_terms_show_in_letter_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $id=$this->post('data_id');
            $updated_field_name=$this->post('update_field_name');
            $updated_content=$this->post('update_field_value');
            
            if($client_id!='' && $user_id!='' && $id!='' && $updated_field_name!='' && $updated_content!='')
            {
                $data = array(
                            $updated_field_name=>$updated_content
                            );
                $this->quotation_model->UpdateQuotationTermsLog($data,$id,$client_info);
            
                $result =array (
                                "status"=>"Quation successfully updated!"
                            );


                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'        => $result
                    );
            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function update_next_followup_date_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $lead_id=$this->post('lead_id');
            $next_followup_date=$this->post('nfd_date'); 
            $next_followup_date=datetime_display_format_to_db_format_ampm($next_followup_date);
            
            if($client_id!='' && $user_id!='' && $lead_id!='' && $next_followup_date!='')
            {
                
                $lead_info=$this->lead_model->GetLeadData($lead_id,$client_info);
	       	
                //-------------- HISTORY ----------------------------------
                $history_text = '';
                $update_by=$user_id;
                $ip_addr=$_SERVER['REMOTE_ADDR'];		
                $comment_title=LEAD_GENERAL_UPDATE_APP;
                $history_text .= 'Next Follow-up date Updated';                
    
                // =======================================
                if($lead_info->current_stage_id=='3' || $lead_info->current_stage_id=='5' || $lead_info->current_stage_id=='6' || $lead_info->current_stage_id=='7')
                {
                    $get_prev_stage=$this->lead_model->get_prev_stage($lead_id,$client_info);
                    $get_prev_status=$this->lead_model->get_prev_status($lead_id,$client_info);
    
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
                    $this->lead_model->UpdateLead($update_lead_data,$lead_id,$client_info);
                    // Insert Stage Log
                    $stage_post_data=array();
                    $stage_post_data=array(
                            'lead_id'=>$lead_id,
                            'stage_id'=>$get_prev_stage->stage_id,
                            'stage'=>$get_prev_stage->stage,
                            'stage_wise_msg'=>'',
                            'create_datetime'=>date("Y-m-d H:i:s")
                        );
                    $this->lead_model->CreateLeadStageLog($stage_post_data,$client_info);
    
                    // Insert Status Log
                    $status_post_data=array();
                    $status_post_data=array(
                            'lead_id'=>$lead_id,
                            'status_id'=>$get_prev_status->status_id,
                            'status'=>$get_prev_status->status,
                            'create_datetime'=>date("Y-m-d H:i:s")
                        );                    
                    $this->lead_model->remove_all_inactive_status_and_stage($lead_id,$client_info);						
                    
                }
                // =======================================
    
                // UPDATE LEAD NEXT FOLLOW UP
                $update_lead_data=array();
                $update_lead_data = array(
                    'followup_date' =>$next_followup_date,
                    'is_followup_date_changed'=>'Y',
                    'modify_date'=>date("Y-m-d")
                );							
                $this->lead_model->UpdateLead($update_lead_data,$lead_id,$client_info);
    
                //-------------- HISTORY ----------------------------------		
                $communication_type_id=1;		
                $communication_type=get_value("title","communication_master","id=".$communication_type_id,$client_info);
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
                $this->history_model->CreateHistory($historydata,$client_info);	
                // ----------------------------------------------------------	
    
                $status_str='success';  
                $result["status"] = $status_str;
                $result["msg"]=$str;
                $result['lid']=$lead_id;
                $result['updated_nfd']=$next_followup_date;


                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'        => $result
                    );
            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }


    function company_wise_assign_list_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $lead_id=$this->post('lead_id');
            $company_id=$this->post('company_id');
            $currassigned_to=$this->input->post('current_assigned_to');

            if($client_id!='' && $user_id!='' && $lead_id!='' && $company_id!='')
            {  
                $lead_info=$this->lead_model->GetLeadData($lead_id,$client_info);
                $list=array();
                $list['assigned_observer']=$lead_info->assigned_observer;
                $list['currassigned_to']=$currassigned_to;
                $list['company_id']=$company_id;
                $list['lead_id']=$lead_id;
       
                $list['user_list']=$this->User_model->GetAllUsers('0',$client_info);


                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'        => $list
                    );
            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }


    function assign_to_update_post()
    {
        if($this->post('token'))
        {
            $dataArr=array();
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $lead_id=$this->post('lead_id');
            $company_id=$this->post('company_id');
            $assigned_to_user_id=$this->post('assigned_to');
            $assigned_observer=$this->post('observer');
            $is_mail_send_to_client=($this->post('is_mail_send_to_client'))?'Y':'N'; 


            if($client_id!='' && $user_id!='' && $company_id!='' && $assigned_to_user_id!='')
            {  
                
                  	
                $get_leads_by_cpmpany=$this->lead_model->get_leads_by_cpmpany($company_id,$client_info);

                $assigned_by_user_id=$user_id;
        
                $old_account_manager_id=get_value("assigned_user_id","customer","id=".$company_id,$client_info);
                if($old_account_manager_id>0)
                {
                    $old_account_manager=get_value("name","user","id=".$old_account_manager_id,$client_info);
                }
                else{
                    $old_account_manager='N/A';
                }
                   
        
                   if($assigned_to_user_id!='')
                   {
                
                    $new_account_manager=get_value("name","user","id=".$assigned_to_user_id,$client_info);
                    
                    $update_by=$user_id;
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
                            $this->lead_model->UpdateLead($updatedata,$l_id,$client_info);
        
                            // insert to log table
                            $post_data=array();
                            $post_data=array(
                                            'lead_id'=>$l_id,
                                            'assigned_to_user_id'=>$assigned_to_user_id,
                                            'assigned_by_user_id'=>$assigned_by_user_id,
                                            'is_accepted'=>'Y',
                                            'assigned_datetime'=>date("Y-m-d H:i:s")
                                            );			
                            $this->lead_model->create_lead_assigned_user_log($post_data,$client_info);
        
        
                            $ip_addr=$_SERVER['REMOTE_ADDR'];				
                            $message="Account manager has been changed from ".$old_account_manager."(Old) to ".$new_account_manager."(New)";
                            $comment_title=ACCOUNT_MANAGER_CHANGE_APP;
                            $historydata=array(
                                        'title'=>$comment_title,
                                        'lead_id'=>$l_id,
                                        'comment'=>addslashes($message),
                                        'attach_file'=>'',
                                        'create_date'=>date("Y-m-d H:i:s"),
                                        'user_id'=>$update_by,
                                        'ip_address'=>$ip_addr
                                            );
                            $this->history_model->CreateHistory($historydata,$client_info);
                            // HISTORY CREATE
                            // =========================
                        }
                    }
        
                    // company update
                    $company_post_data=array(
                            'assigned_user_id'=>$assigned_to_user_id,
                            'modify_date'=>date('Y-m-d')
                            );
                    $this->customer_model->UpdateCustomer($company_post_data,$company_id,$client_info);        
                    // company update
        
                    $session_data=$user_data=$this->User_model->get_employee_details($user_id,$client_info);
                    $email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(2,$client_info);
                    $sms_forwarding_setting=$this->Sms_forwarding_setting_model->GetDetails(2,$client_info);
        
                    if($email_forwarding_setting['is_mail_send']=='Y' || isset($sms_forwarding_setting))
                    {
        
                    }
        
                    // MAIL ALERT
                    if($email_forwarding_setting['is_mail_send']=='Y')
                    {				
                        $e_data=array();		
                        $assigned_to_user=$this->User_model->get_employee_details($assigned_to_user_id,$client_info);
                        $assigned_to_user_name=$assigned_to_user['name'];
                        $company=get_company_profile($client_info);	
                        //$lead_info=$this->lead_model->GetLeadData($lead_id);
                        $customer=$this->customer_model->GetCustomerData($company_id,$client_info);
                        $e_data['company']=$company;
                        $e_data['assigned_to_user']=$assigned_to_user;
                        $e_data['customer']=$customer;
                        //$e_data['lead_info']=$lead_info;
                        
                        $template_str = $this->load->view('admin/email_template/template/change_of_relationship_manager_view', $e_data, true);	        
        
                        $m_email=get_manager_and_skip_manager_email_arr($assigned_to_user_id,$client_info);
        
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
                        $self_cc_mail=get_value("email","user","id=".$update_by,$client_info);
                        
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
                        
        
                        if($to_mail!='' && $is_mail_send_to_client=='Y')
                        {
                            $post_data=array();
                            $post_data=array(
                                    "mail_for"=>MF_CHANGE_LEAD_ASSIGNEE_APP,
                                    "from_mail"=>$session_data['email'],
                                    "from_name"=>$session_data['name'],
                                    "to_mail"=>$to_mail,
                                    "cc_mail"=>$cc_mail,
                                    "subject"=>$company['name'].' - Introduction of New A/C Manager',
                                    "message"=>$template_str,
                                    "attach"=>'',
                                    "created_at"=>date("Y-m-d H:i:s")
                            );
                            $this->App_model->mail_fire_add($post_data,$client_info);
                        }
                        // END
                        // =============================
                    }
        
                   
                    
                }
                
                // observer update
                if($lead_id!='' && $assigned_observer!='')
                {
                    $updatedata=array();
                    $updatedata=array(
                                    'assigned_observer'=>$assigned_observer
                                    );
                    $this->lead_model->UpdateLead($updatedata,$lead_id,$client_info);
                }
                // observer update
                
                $status_str='success';  
                $result["status"] = $status_str;
                $result["assigned_to_user_name"]='';
                $result["return"]='';


                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 1,
                    'api_action_success' => 1,
                    'error'              => '',
                    'result_data'        => $result
                    );
            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }


    function company_wise_lead_list_post()
    {        
        if($this->post('token'))
        {
            
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $customer_id=$this->post('company_id');
            $show_data_type=$this->post('show_data_type');
            $page_start=$this->post('page_number');
            $page_limit=$this->post('show_per_page');
            

            if($client_id!='' && $user_id!='' && $customer_id!='' && $show_data_type!='')
            {
                $arg=array();  
                $arg['customer_id']=$customer_id;
                $arg['show_data_type']=$show_data_type;
                $page_start=empty($page_start)?0:($page_start-1);
                $arg['start']=$page_start;
                $arg['limit']=$page_limit;

                $priority_wise_stage=$this->lead_model->priority_wise_stage($client_info);
                $lead_result=$this->Api_model->GetCustomerWiseLeads($arg,$client_info);
                
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'=>'',
                        'priority_wise_stage'=>$priority_wise_stage,
                        'lead_rows'=>$lead_result
                        ); 
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'          => 'Expected parameter missing.'
                        );
            }
                      

        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => 'Token not matching.',
                        'rows' => array()
                        ); 
        } 
              
        $this->response($data, 200);
    }



    function edit_quotation_confirmation_display1_post()
    {
        
        if($this->post('token'))
        {
            $quote_date=$this->input->post('quote_date');
            $quote_no=$this->input->post('quote_no');
            $letter_to=$this->input->post('letter_to');
            $letter_subject=$this->input->post('letter_subject');
            $letter_body_text=$this->input->post('letter_body_text');

            $quotation_id=$this->input->post('quotation_id');
            $client_id=$this->post('client_id');

            if($quote_date!= '' && $quote_no!=''&& $letter_to!='' &&  $letter_subject!='' &&   $letter_body_text!='' && $quotation_id!='' && $client_id!='')
            { 
                $error_msg='';
                $success_msg='';
                $return_status='';
                $arg=array();
                $client_info=$this->Client_model->get_details($client_id); 
                $arg['quote_date']=$quote_date;
                $arg['quote_no']=$quote_no;
                $arg['letter_to']=$letter_to;
                $arg['letter_subject']=$letter_subject;
                $arg['letter_body_text']=$letter_body_text;

                $r = $this->quotation_model->UpdateQuotation($arg,$quotation_id,$client_info);

                    if($r == true)  
                    {

                            $status_str='success';  
                            $result["status"] = $status_str;
                            $result["return"]='';
                        
                        $data = array(
                            'api_token_success'  => 1,
                            'api_syntax_success' => 1,
                            'api_action_success' => 1,
                            'error'              => '',
                            'result_data'        => $result
                            ); 
                    }
                    else
                    {
                        $data = array(
                            'api_token_success'  => 1,
                            'api_syntax_success' => 0,
                            'api_action_success' => 1,
                            'error'              => 'Record has been failed to update.'
                            ); 
                    }
            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            }           
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function edit_quotation_confirmation_display2_post()
    {
        
        if($this->post('token'))
        {
            
            $client_id=$this->post('client_id'); //17
            $product_id=$this->input->post('productitem_id'); //1150
            $quotation_id=$this->input->post('quotation_id'); //586
            $opportunity_id=$this->post('opportunity_id'); //591

            $product_name=$this->input->post('product_name');
            
            $currency_type=$this->input->post('currency_type'); // FOR UNIT PRICE(INR, USD, EURO, GBP)
            $price=$this->input->post('price');
            $unit=$this->input->post('unit');
            $unit_type=$this->input->post('unit_type');
            
            $is_discount_p_or_a=$this->input->post('is_discount_p_or_a'); //FOR DISCOUNT P => %, A => Amt.
            $quantity=$this->input->post('quantity');
            $discount=$this->input->post('discount');
            $gst=$this->input->post('gst');

           
            if($product_name!= ''  && $currency_type!= ''  && $price!='' &&  $unit!='' &&   $unit_type!='' && $quantity!= '' &&  $discount!= '' &&  $gst!='' && $client_id!='' && $product_id!=''  && $quotation_id!='' && $opportunity_id!='' && $is_discount_p_or_a!= '')
            {
                
                $error_msg='';
                $success_msg='';
                $return_status='';
                $arg=array();
                $client_info=$this->Client_model->get_details($client_id);
                
                $arg['product_name']=$product_name;
                //$arg['currency_type']=$currency_type;
                $arg['price']=$price;
                $arg['unit']=$unit;
                $arg['unit_type']=$unit_type;
                $arg['is_discount_p_or_a']=$is_discount_p_or_a;
                $arg['quantity']=$quantity;
                $arg['discount']=$discount;
                $arg['gst']=$gst;

                
                
               //For Product Update
               $r = $this->quotation_model->update_product($arg,$product_id,$client_info);
               
               $list=array();
               $product_list=$this->quotation_model->GetQuotationProductList($quotation_id,$client_info);
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
                    if($item_is_discount_p_or_a=='A')
                    {
                        $row_discount_amount=$item_discount;
                    }
                    else
                    {
                        $row_discount_amount=$item_total_amount*($item_discount/100);
                    }			
			
                    $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

                    $row_final_price_tmp=($item_total_amount+$row_gst_amount-$row_discount_amount);
                    $sub_total=$sub_total+$row_final_price_tmp;

                    $total_price=$total_price+$item_total_amount;
                    $total_discounted_price=$total_discounted_price+$row_discount_amount;
                    $total_tax_price=$total_tax_price+$row_gst_amount;	
                }
                
                $product=$this->quotation_model->GetQuotationProduct($product_id,$client_info);
                $item_gst_per= $product->gst;
                $item_sgst_per= ($item_gst_per/2);
                $item_cgst_per= ($item_gst_per/2); 
                $item_is_discount_p_or_a=$product->is_discount_p_or_a; 
                $item_discount_per=$product->discount;
                $item_unit=$product->unit; 
                $item_price=($product->price/$item_unit);
                $item_qty=$product->quantity;
                $item_total_amount=($item_price*$item_qty);
                if($item_is_discount_p_or_a=='A')
                {
                    $row_discount_amount=$item_discount_per;
                }
                else
                {
                    $row_discount_amount=$item_total_amount*($item_discount_per/100);
                }	
                $row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

                $row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
                $row_final_amount=($item_total_amount-$row_discount_amount);
                
		        // CALCULATE ADDITIONAL PRICE
                $additional_charges_list=array();
                
                $additional_charges_list=$this->opportunity_model->get_selected_additional_charges($opportunity_id,$client_info);
                                
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
                
                
                    $data_opportunity_update=array(
                        'deal_value'=>$sub_total,
                        'modify_date'=>date("Y-m-d H:i:s")
                        );	
                $r1 = $this->opportunity_model->UpdateLeadOportunity($data_opportunity_update,$opportunity_id,$client_info);
                    $data_update=array('currency_type'=>$currency_type);
		        $r2 = $this->opportunity_model->UpdateLeadOportunity($data_update,$opportunity_id,$client_info);
                
                    $currency_code=get_value('code','currency','id='.$currency_type);
		            $data_update2=array('currency_type'=>$currency_code);
                $r3 = $this->quotation_model->UpdateQuotation($data_update2,$quotation_id,$client_info);
              
                
		        // INSERT TO TERMS AND CONDITIONS LOG TABLE
                if($currency_code=='INR')
                {
                    $table_name='terms_and_conditions_domestic_quotation';
                }
                else
                {
                    $table_name='terms_and_conditions_export_quotation';
                }
                $terms_condition_list=$this->quotation_model->get_terms_and_conditions($table_name,$client_info);
                $r5 = $this->quotation_model->deleteQuotationTermsLog($quotation_id,$client_info);
                if(count($terms_condition_list))
                {
                    foreach($terms_condition_list as $term)
                    {
                        $term_log_post_data=array(	
                                                'quotation_id'=>$quotation_id,
                                                'name'=>$term->name,
                                                'value'=>$term->value
                                                );
                        $r6 = $this->quotation_model->CreateQuotationTimeTermsLog($term_log_post_data,$client_info);
                    }
                }
                // INSERT TO TERMS AND CONDITIONS LOG TABLE
                $opportunity_product_list=$this->opportunity_model->get_opportunity_product($opportunity_id,$client_info);	
                if(count($opportunity_product_list))
                {
                    foreach($opportunity_product_list as $opp_prod)
                    {			
                        $data_update_tmp=array('currency_type'=>$currency_type);
                        $r7 = $this->Opportunity_product_model->update($data_update_tmp,$opp_prod->id,$client_info);
                    }
                }
                // ---------------------------------------------------
                
                if($currency_type!=1)
                {
                    $product_list=$this->quotation_model->GetQuotationProductList($quotation_id,$client_info);
                    if(count($product_list))
                    {
                        foreach($product_list as $output)
                        {
                            $data_post=array(						
                                        'gst'=>'0'
                                        );
                            $r8 = $this->quotation_model->update_product($data_post,$output->id,$client_info);
                        }
                    }		

                    $additional_charges_list=$this->opportunity_model->get_selected_additional_charges($opportunity_id,$client_info);
                    if(count($additional_charges_list))
                    {
                        foreach($additional_charges_list as $charge)
                        {
                            $data_post=array(						
                                        'gst'=>'0'
                                        );
                            $r9 = $this->opportunity_model->update_opportunity_additional_charges($data_post,$charge->id,$client_info);
                        }
                    }
                }

                $r10=$this->quotation_model->UpdateQuotationChargesByOppId($arg,$opportunity_id,$client_info);



                if($r == true)  
                {

                    $status_str='success';  
                    $result["status"] = $status_str;
                    $result["return"]='';
                        
                    $data = array(
                       'api_token_success'  => 1,
                       'api_syntax_success' => 1,
                       'api_action_success' => 1,
                       'error'              => '',
                       'result_data'        => $result
                        ); 
                }
                else
                {
                    
                    $data = array(
                        'api_token_success'  => 1,
                        'api_syntax_success' => 0,
                        'api_action_success' => 1,
                        'error'              => 'Record has been failed to update.'
                        ); 
                } 


            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            } 

                   
            
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
    }

    function edit_quotation_confirmation_display3_post()
    {
        
        if($this->post('token'))
        {
            $client_id=$this->post('client_id'); //17
            $quotation_id=$this->post('quotation_id'); //586
            $opportunity_id=$this->post('opportunity_id'); //591
           
            
           
            if($client_id!="" && $quotation_id!="" && $opportunity_id!="") // PARAMETER CHECKING
            {
                
                
                $client_info=$this->Client_model->get_details($client_id);
                $quotation_data=$this->quotation_model->GetQuotationData($quotation_id,$client_info);
                $product_list=$this->quotation_model->GetQuotationProductList($quotation_id,$client_info);
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
                
               

              
                $additional_charges_list=$this->opportunity_model->get_selected_additional_charges($opportunity_id,$client_info);
                
                
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
                //$result['total_sale_price'] = number_format($row_final_amount,2);
                //$result["total_deal_value"]=number_format($sub_total,2);

                $result['currency_type'] = $quotation_data['quotation']['currency_type'];
                $result["total_gross_amount"]=number_format($total_price,2);
                $result["total_discount"]=number_format($total_discounted_price,2);
                $result["total_tax"]=number_format($total_tax_price,2);
                $result["net_amount"]=number_format(round($sub_total),2);
                $result["number_to_word_final_amount"]=number_to_word(round($sub_total));


                $data = array(
                                'api_token_success'  => 1,
                                'api_action_success' => 1,
                                'api_syntax_success' => 1,
                                'error'          => '',
                                'rows' => $result
                           );
            }
            else
            {    
                $data = array(
                            'api_token_success'  => 1,
                            'api_action_success' => 0,
                            'api_syntax_success' => 1,
                            'error'              => 'Expected parameter missing.',
                            'rows'               => array()
                            );
            }
        }
        else
        {
                $data = array(
                            'api_token_success'  => 0,
                            'api_action_success' => 1,
                            'api_syntax_success' => 1,
                            'error'              => 'Token not matching.',
                            'rows'               => array()
                            ); 
        }
        $this->response($data, 200);
    }

    function edit_quotation_confirmation_display4_post()
    {
       
        if($this->post('token'))
        {
            $client_id=$this->post('client_id'); //17
            $quotation_id=$this->post('quotation_id'); //586

            if($client_id!="" && $quotation_id!="") // PARAMETER CHECKING
            {
                $result =array();
                $client_info=$this->Client_model->get_details($client_id);
                $result['terms_list']=$this->quotation_model->get_terms_log($quotation_id,'',$client_info);
                $quotationresult=$this->quotation_model->GetQuotationData($quotation_id,$client_info);
                $result['is_quotation_bank_details1_send'] = $quotationresult['quotation']['is_quotation_bank_details1_send'];
                $result['is_quotation_bank_details2_send'] = $quotationresult['quotation']['is_quotation_bank_details2_send'];
                $result['is_gst_number_show_in_quotation'] = $quotationresult['quotation']['is_gst_number_show_in_quotation'];
                $result['letter_terms_and_conditions'] = $quotationresult['quotation']['letter_terms_and_conditions'];
                $result['gst_number'] = $quotationresult['company_log']['gst_number'];
                $result['quotation_bank_details1'] = $quotationresult['company_log']['quotation_bank_details1'];
                $result['quotation_bank_details2'] = $quotationresult['company_log']['quotation_bank_details2'];
                $result['letter_thanks_and_regards'] = $quotationresult['quotation']['letter_thanks_and_regards'];
                
                $data = array(
                                'api_token_success'  => 1,
                                'api_action_success' => 1,
                                'api_syntax_success' => 1,
                                'error'          => '',
                                'rows' => $result
                           );
            }
            else
            {    
                $data = array(
                            'api_token_success'  => 1,
                            'api_action_success' => 0,
                            'api_syntax_success' => 1,
                            'error'              => 'Expected parameter missing.',
                            'rows'               => array()
                            );
            }
        }
        else
        {
                $data = array(
                            'api_token_success'  => 0,
                            'api_action_success' => 1,
                            'api_syntax_success' => 1,
                            'error'              => 'Token not matching.',
                            'rows'               => array()
                            ); 
        }
        $this->response($data, 200);
    }

    function edit_quotation_confirmation_display5_post()
    {

        if($this->post('token'))
        {
                       
            $client_id=$this->post('client_id'); //17
            $quotation_id=$this->input->post('quotation_id'); //586

            // For terms and conditions drop down start
            //Array([0] => Array( [id] => '' [text] => ''))
            $is_display_in_quotation=$this->input->post('termsandcondition');  
            //Array([0] => Array( [id] => '' [text] => ''))
            $is_display_in_quotationvalue=$this->input->post('termsandconditionvalue');
            // For terms and conditions drop down end
            
            
            
            
            $is_quotation_bank_details1_send=$this->input->post('is_quotation_bank_details1_send'); // Show quotation bank details1
            $is_quotation_bank_details2_send=$this->input->post('is_quotation_bank_details2_send'); // Show quotation bank details2
            $is_gst_number_show_in_quotation=$this->input->post('is_gst_number_show_in_quotation'); // Show GST in quotation
            $letter_terms_and_conditions=$this->input->post('letter_terms_and_conditions'); // Additional Notes
            $quotation_bank_details1=$this->input->post('quotation_bank_details1'); //Banker's Details 1
            $quotation_bank_details2=$this->input->post('quotation_bank_details2'); //Banker's Details 2
            $letter_footer_text=$this->input->post('letter_footer_text'); // Footer text
            $letter_thanks_and_regards=$this->input->post('letter_thanks_and_regards'); // Thanks and regards text
            
            if($quotation_id!='' && $client_id!='')
            {
                $arg=array();
                $termsdata= array();
                $client_info=$this->Client_model->get_details($client_id);
                
                
                if(is_array($is_display_in_quotation))
                {
                    if(empty($is_display_in_quotation)== false)
                    {
                        foreach ($is_display_in_quotation as $display_terms_quotation) 
                        {
                            $this->quotation_model->UpdateQuotationTermsLog(array('is_display_in_quotation' => $display_terms_quotation['text']),$display_terms_quotation['id'],$client_info); 
                        }
                    }
                   
                }
                
                if(is_array($is_display_in_quotationvalue))
                {
                    if(empty($is_display_in_quotationvalue)== false)
                    {
                        foreach ($is_display_in_quotationvalue as $display_terms_quotationvalue) 
                        {
                             $this->quotation_model->UpdateQuotationTermsLog(array('value' => $display_terms_quotationvalue['text']),$display_terms_quotationvalue['id'],$client_info); 
                        }
                    }
                   
                }
                

              
              
              
             


                if($letter_terms_and_conditions!='')
                {
                    $arg['letter_terms_and_conditions']=$letter_terms_and_conditions;
                }
                
                if($quotation_bank_details1!='')
                {
                    
                    $this->quotation_model->UpdateQuotationTimeCompanyInfoLogByQuotationId(array('quotation_bank_details1' => $quotation_bank_details1),$quotation_id,$client_info);
                }
                if($quotation_bank_details2!='')
                {
                    
                    $this->quotation_model->UpdateQuotationTimeCompanyInfoLogByQuotationId(array('quotation_bank_details2' => $quotation_bank_details2),$quotation_id,$client_info);
                }
                if($letter_footer_text!='')
                {
                    $arg['letter_footer_text']=$letter_footer_text;
                }
                
                if($letter_thanks_and_regards!='')
                {
                    $arg['letter_thanks_and_regards']=$letter_thanks_and_regards;
                }

                if($is_quotation_bank_details1_send!='')
                {
                    $arg['is_quotation_bank_details1_send'] = $is_quotation_bank_details1_send;
                }
                if($is_quotation_bank_details2_send!='')
                {
                    $arg['is_quotation_bank_details2_send'] = $is_quotation_bank_details2_send;
                }
                if($is_gst_number_show_in_quotation!='')
                {
                    $arg['is_gst_number_show_in_quotation'] = $is_gst_number_show_in_quotation;
                }
                    $arg['modify_date'] = date('Y-m-d H:i:s');
                
                $r = $this->quotation_model->UpdateQuotation($arg,$quotation_id,$client_info);

                
                   


                if($r == true)
                {
                    $status_str='success';  
                    $result["status"] = $status_str;
                    $result["return"]='';
            
                    $data = array(
                        'api_token_success'  => 1,
                        'api_syntax_success' => 1,
                        'api_action_success' => 1,
                        'error'              => '',
                        'result_data'        => $result
                        ); 
                }
                else
                {
                    $data = array(
                        'api_token_success'  => 1,
                        'api_syntax_success' => 0,
                        'api_action_success' => 1,
                        'error'              => 'Record has been failed to update.'
                        ); 
                }
                
                
            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_syntax_success' => 0,
                    'api_action_success' => 1,
                    'error'              => 'Expected parameter missing.'
                    ); 
            } 
        }
        else
        {
            $data = array(
                'api_token_success'  => 0,
                'api_syntax_success' => 1,
                'api_action_success' => 1,
                'error'              => 'Access denied! Token not matching.'
                ); 
        }

        $this->response($data, 200);
       
    }

    function add_product_in_existing_quotation_post()
    {
        
        if($this->post('token'))
        {
            $client_id=$this->post('client_id'); //17
            $lead_id=$this->post('lead_id'); //1694
            $quotation_id=$this->post('quotation_id'); //586
            $opportunity_id=$this->post('opportunity_id'); //591
            $user_id=$this->post('user_id'); //1
            $prod_id=$this->input->post('prod_id'); //28,15,14
            $prod_arr=explode(',',$prod_id);
           
            $i=0;
            $date=date('Y-m-d');
            $client_info=$this->Client_model->get_details($client_id);
            $data['product_data']=array();
          
            foreach($prod_arr as $prod_data)
            { 
                
                
                if($prod_data!='')
                {
                    
                    $chk_prod_temp=$this->Product_model->TempProdExistCheck($prod_data,$user_id,$client_info);
                    
                    if($chk_prod_temp==false)
                    {
                        
                        $data['product_data'][$i]=$this->Product_model->GetProductListById($prod_data,$client_info);				
                        
                        $qtn=($data['product_data'][$i]->minimum_order_quantity>0)?$data['product_data'][$i]->minimum_order_quantity:'1';
                        $new_data=array(
                            'opportunity_id'=>$opportunity_id,
                            'user_id'=>$user_id,
                            'product_id'=>$prod_data,
                            'name'=>$data['product_data'][$i]->name,
                            'unit'=>$data['product_data'][$i]->unit,
                            'unit_type'=>$data['product_data'][$i]->unit_type,
                            'quantity'=>$qtn,
                            'price'=>$data['product_data'][$i]->price,
                            'currency_type'=>$data['product_data'][$i]->currency_type,
                            'discount'=>'',
                            'gst'=>$data['product_data'][$i]->gst_percentage,
                            'create_date'=>$date
                        );
                        
                        $create_data=$this->Product_model->CreateTempProduct($new_data,$client_info);
                        $i++;
                        
                    }
                    
                }			
            }
            
            
            
            $tmp_prod_list=$this->Product_model->GetTempProductList($user_id,$client_info);
            if($client_id!= "" && $lead_id!= "" && $quotation_id!= "" && $opportunity_id!= "" && $user_id!= "" && $prod_id!="")
            {
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
                            $this->Opportunity_product_model->CreateOportunityProduct($data_prd,$client_info);
    
                            $get_product=$this->Product_model->get_product($tmp_prod_data->product_id,$client_info);
    
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
                            $this->quotation_model->CreateQuotationProduct($quotation_product_data,$client_info);
                        }
                    }
                    $this->Product_model->DeleteTempProduct('',$user_id,$client_info);
                    
                    $data_opportunity_update=array(
                    'deal_value'=>$deal_value_tmp,
                    'modify_date'=>date("Y-m-d H:i:s")
                    );	
                    $this->opportunity_model->UpdateLeadOportunity($data_opportunity_update,$opportunity_id,$client_info);
    
                    //$status_str='A new quotation ('.$opportunity_title.') has been created.';
                    $status_str='success';
    
                    $result["opportunity_id"] = $opportunity_id;
                    $result["quotation_id"]=$quotation_id;
                    $result["status"] = $status_str;
                    
                        
                    $data = array(
                            'api_token_success'  => 1,
                            'api_syntax_success' => 1,
                            'api_action_success' => 1,
                            'error'              => '',
                            'result_data'        => $result
                            ); 
    
                }	
                else
                {
                    
                    $data = array(
                        'api_token_success'  => 1,
                        'api_syntax_success' => 0,
                        'api_action_success' => 1,
                        'error'              => 'No product selected for the proposal.'
                        );
                }
            }
            else
            {
                $data = array(
                    'api_token_success'  => 1,
                    'api_action_success' => 0,
                    'api_syntax_success' => 1,
                    'error'              => 'Expected parameter missing.',
                    'rows'               => array()
                    );
    
            }
        }
        else
        {
                $data = array(
                            'api_token_success'  => 0,
                            'api_action_success' => 1,
                            'api_syntax_success' => 1,
                            'error'              => 'Token not matching.',
                            'rows'               => array()
                            ); 
        }
        $this->response($data, 200);
    
    }
  
}
?>
