<?php 
require(APPPATH.'/libraries/REST_Controller.php');
class Rest_lead extends REST_Controller
{
    private $access_token = '';
    public function __construct()
   	{
		parent::__construct();      
        $this->load->model(array("lead_model","User_model","history_model","Client_model"));
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
                        $comment_title=LEAD_UPDATE_MANUAL;
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
                                    $post_data=array(
                                    'user_id'=>$user_id,
                                    'name'=>$row['name'],
                                    'country_code'=>$row['country_code'],
                                    'number'=>$call_number,
                                    'call_start'=>$row['start_time_date'],
                                    'call_end'=>$row['end_time_date'],
                                    'bound_type'=>$row['bound_type'],
                                    'agent_mobile'=>$row['agent_mobile'],
                                    'status'=>$status,
                                    'status_wise_msg'=>$status_msg,
                                    'created_at'=>date("Y-m-d H:i:s")
                                    );                        
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
}
?>
