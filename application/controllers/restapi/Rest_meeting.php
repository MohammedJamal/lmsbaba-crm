<?php 
require(APPPATH.'/libraries/REST_Controller.php');
class Rest_meeting extends REST_Controller
{
    private $access_token = '';
    public function __construct()
   	{
		parent::__construct();      
        $this->load->model(array("meeting_model","Client_model","customer_model","lead_model","user_model","history_model"));
        $this->access_token = get_access_token();
	}

    /*
        meeting dashboard count
    */
    function dashboard_count_post()
    {        
        // if($this->get('token') == $this->access_token) // ACCESS TOKEN CHECKING
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id = $this->post('user_id');  
            
            $tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0,$client_info);
            array_push($tmp_u_ids, $user_id);
   		    $tmp_u_ids_str=implode(",", $tmp_u_ids); 

            $arg=array();  
            $arg['assigned_user']=$user_id;
            $arg['user_id_str']=$tmp_u_ids_str;
            if($arg['assigned_user']!='')
            {                
                
                $result=$this->meeting_model->get_dashboard_count($arg,$client_info); 
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
        meeting details
    */
    function detail_post()
    {        
        // if($this->get('token') == $this->access_token) // ACCESS TOKEN CHECKING
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id = $this->post('user_id');
            $meeting_id = $this->post('meeting_id');
            // $tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0,$client_info);
            // array_push($tmp_u_ids, $user_id);
   		    // $tmp_u_ids_str=implode(",", $tmp_u_ids); 
            
            $arg=array();  
            // $arg['user_id']=$tmp_u_ids_str;
            $arg['user_id']='';
            $arg['meeting_id']=$meeting_id;
            if($arg['meeting_id']!='')
            {                
                
                $result=$this->meeting_model->GetDetails($arg,$client_info);

                $rows_data = array(
                    'meeting_id' => $result['id'],
                    'meeting_email' => $result['cust_contact_email'],
                    'meeting_mobile' => $result['cust_contact_number'],
                    'meeting_lead_id' => $result['lead_id'],
                    'meeting_lead_title' => $result['lead_title'],
                    'meeting_status_id' => $result['status_id'],
                    'meeting_status' => $result['status'],
                    'meeting_type' => ($result['meeting_type']=='O')?'Online':'Offline',
                    'meeting_agenda_type_id' => $result['meeting_agenda_type_id'],
                    'meeting_agenda_type_name' => $result['meeting_agenda_type_name'],
                    'meeting_purpose' => $result['meeting_Purpose'],
                    'meeting_url' => $result['meeting_url'],
                    'meeting_contact_person' => $result['cust_contact_person'],
                    'meeting_company_id' => $result['cust_company_id'],
                    'meeting_company_name' => $result['cust_company_name'],
                    'company_source_id' => $result['company_source_id'],
                    'company_assigned_user_id' => $result['company_assigned_user_id'],
                    'meeting_company_address' => $result['cust_contact_address'],
                    'meeting_agenda_type_name' => $result['meeting_agenda_type_name'],
                    'meeting_by' => $result['meeting_assigned_to'],
                    'meeting_with' => $result['meeting_with_before_checkin_time'],
                    'meeting_with_at_checkin_time' => $result['meeting_with_at_checkin_time'],
                    'meeting_start_datetime' => $result['meeting_schedule_start_datetime'],
                    'meeting_end_datetime' => $result['meeting_schedule_end_datetime'],
                    'meeting_updated_datetime' => $result['updated_at'],
                    'meeting_venue' => $result['meeting_venue'],
                    'meeting_remarks' => $result['discussion_points']
                    
                );

                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => '',
                        'rows' => $rows_data
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
        meeting lead add
    */
    function meeting_lead_add_post()
    {        
        // if($this->get('token') == $this->access_token) // ACCESS TOKEN CHECKING
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id = $this->post('user_id');
            
            $lead_title=$this->post('lead_title');
            $customer_id = $this->post('customer_id');
            $source_id = $this->post('source_id'); 
            $assigned_user_id = $this->post('assigned_user_id');
            $buying_requirement = $this->post('buying_requirement');;
            $enquiry_date = date('Y-m-d');
            $followup_date = date('Y-m-d');
            $followup_type_id = '0'; 
            $lead_enq_date = date('Y-m-d');
            $lead_follow_up_date = date('Y-m-d');

            if($lead_enq_date!=$lead_follow_up_date){
                $is_followup_date_changed='Y';
            }
            else{
                $is_followup_date_changed='N';
            }

            if($user_id!='' && $customer_id!='' && $source_id!='' && $assigned_user_id!='')
            {  

                $lead_post_data=array(
                    'title'=>$lead_title,
                    'customer_id'=>$customer_id,
                    'source_id'=>$source_id,
                    'assigned_user_id'=>$assigned_user_id,
                    'buying_requirement'=>$buying_requirement,
                    'enquiry_date'=>date('Y-m-d'),
                    'followup_date'=>date('Y-m-d'),
                    'is_followup_date_changed'=>$is_followup_date_changed,
                    'followup_type_id'=>$followup_type_id,
                    'create_date'=>date('Y-m-d'),
                    'modify_date'=>date('Y-m-d'),
                    'assigned_date'=>date('Y-m-d'),
                    'status'=>'1',
                    'attach_file'=>'',
                    'description'=>'',
                    'current_stage_id'=>'1',
                    'current_stage'=>'PENDING',
                    'current_stage_wise_msg'=>'',
                    'current_status_id'=>'1',
                    'current_status'=>'WARM'
                );

                $lead_id=$this->lead_model->CreateLead($lead_post_data,$client_info);   



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

        
                    $update_by=$user_id;				
                    $date=date("Y-m-d H:i:s");				
                    $ip_addr=$_SERVER['REMOTE_ADDR'];				
                    $message="A new lead has been created from LMSBaba app as &quot; Lead ID- ".$lead_id."&quot;";
                    $comment_title=NEW_LEAD_CREATE_MANUAL;
                    $historydata=array(
                                        'title'=>$comment_title,
                                        'lead_id'=>$lead_id,
                                        'comment'=>addslashes($message),
                                        'attach_file'=>'',
                                        'create_date'=>$date,
                                        'user_id'=>$update_by,
                                        'ip_address'=>$ip_addr
                                    );
                                    
                    $this->history_model->CreateHistory($historydata,$client_info);
        
                    
                    $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => '',
                        'lead_id' => $lead_id
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
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => 'Token not matching.',
                        'rows' => array()
                        ); 
        } 
              
        $this->response($data, 200);
    }

    /*
        Meeting list for dropdown
    */
    function list_post()
    {        
        // if($this->get('token') == $this->access_token) // ACCESS TOKEN CHECKING
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $listing_type=$this->post('listing_type');
            $user_id=$this->post('user_id');
            $status=$this->post('status');  
            $start=$this->post('start');
            $limit=$this->post('limit');
            $meeting_date=$this->post('meeting_date');
            $company_name=$this->post('company_name');
            $contact_person=$this->post('contact_person');
            $contact_mobile=$this->post('contact_mobile');

            $tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0,$client_info);
            array_push($tmp_u_ids, $user_id);
   		    $tmp_u_ids_str=implode(",", $tmp_u_ids); 

            $start=empty($start)?0:($start-1)*$limit;
            $arg=array();       
            $arg['user_id']=$user_id;
            $arg['user_id_str']=$tmp_u_ids_str;
            $arg['status']=$status;
            $arg['start'] = $start;
            $arg['limit'] = $limit;   
            $arg['listing_type'] = $listing_type;    
            $arg['meeting_date'] = $meeting_date;
            $arg['company_name'] = $company_name;
            $arg['contact_person'] = $contact_person;
            $arg['contact_mobile'] = $contact_mobile;

            $result=$this->meeting_model->GetList($arg,$client_info);
            $result_count=$this->meeting_model->GetListCount($arg,$client_info);
            $data = array(
                'api_token_success'=>1,
                'api_action_success'=>1,
                'api_syntax_success'=>1,
                'error'=>'',
                'rows_count'=>$result_count,
                'rows'=>$result
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

    /*
        Customer list for dropdown
    */
    function customer_list_post()
    {        
        // if($this->get('token') == $this->access_token) // ACCESS TOKEN CHECKING
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);           
            
            $arg=array();  
            $result=$this->customer_model->GetCustomerRows($client_info);
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
        Customer wise leads 
    */
    function customer_wise_lead_and_contact_person_post()
    {        
        // if($this->get('token') == $this->access_token) // ACCESS TOKEN CHECKING
        
        if($this->post('token'))
        {
            
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);           
            $customer_id=$this->post('customer_id');
            if($customer_id!='')
            {
                $arg=array();  
                $arg['customer_id']=$customer_id;
                
                $lead_result=$this->lead_model->GetCustomerWiseLeads($arg,$client_info);
                $meeting_with_result=$this->customer_model->GetCustomerWiseContactPerson($arg,$client_info);
                $customer_data=$this->customer_model->GetCustomerData($customer_id,$client_info);

                $meeting_with_result_fromcustomer = array(
                        'id'  => $customer_data->id,
                        'name' => $customer_data->contact_person,
                        'email' => $customer_data->email
                        );


                array_push($meeting_with_result,$meeting_with_result_fromcustomer);
                // asort($meeting_with_result);


                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'=>'',
                        'lead_rows'=>$lead_result,
                        'contact_person_rows'=>$meeting_with_result
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
        Meeting add
    */
    function add_post()
    {
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);


            $user_id=$this->post('user_id');
            $meeting_type=$this->post('meeting_type');  // P=>Physically Visit, O=>Online	
            $meeting_agenda_type_id=$this->post('meeting_agenda_type_id');
            $customer_id=$this->post('customer_id');
            $meeting_with_before_checkin_time=$this->post('meeting_with_before_checkin_time');
            $lead_id=$this->post('lead_id');
            $meeting_schedule_start_datetime=$this->post('meeting_schedule_start_datetime');
            $meeting_schedule_end_datetime=$this->post('meeting_schedule_end_datetime');
            $meeting_Purpose=$this->post('meeting_Purpose');
            $meeting_venue=$this->post('meeting_venue');
            $meeting_venue_latitude=$this->post('meeting_venue_latitude');
            $meeting_venue_longitude=$this->post('meeting_venue_longitude');
            $meeting_url_type=$this->post('meeting_url_type');
            $meeting_url=$this->post('meeting_url');

            
            
            if($user_id!='' && ($meeting_type=='P' || $meeting_type=='O') && $meeting_agenda_type_id!='' && $customer_id!='' && $meeting_with_before_checkin_time!='' && $lead_id!='' && $meeting_schedule_start_datetime!='' && $meeting_schedule_end_datetime!='')
            {
                
                $status_id=1;
                $status=get_value("name","meeting_status","id=".$status_id,$client_info);               
                
                $post_data=array(
                    'user_id'=>$user_id,
                    'meeting_type'=>$meeting_type,
                    'meeting_agenda_type_id'=>$meeting_agenda_type_id,
                    'customer_id'=>$customer_id,
                    'meeting_with_before_checkin_time'=>$meeting_with_before_checkin_time,
                    'lead_id'=>$lead_id,
                    'meeting_schedule_start_datetime'=>$meeting_schedule_start_datetime,
                    'meeting_schedule_end_datetime'=>$meeting_schedule_end_datetime,
                    'meeting_Purpose'=>$meeting_Purpose,
                    'meeting_venue'=>$meeting_venue,
                    'meeting_venue_latitude'=>$meeting_venue_latitude,
                    'meeting_venue_longitude'=>$meeting_venue_longitude,
                    'meeting_url_type'=>$meeting_url_type,
                    'meeting_url'=>$meeting_url,
                    'status_id'=>$status_id,
                    'status'=>$status,
                    'created_at'=>date("Y-m-d H:i:s"),
                    'updated_at'=>date("Y-m-d H:i:s")
                    );                          
                $result=$this->meeting_model->add($post_data,$client_info);
                if($result!=false)
                {
                    $post_data_log=array(
                        'meeting_id'=>$result,
                        'status_id'=>$status_id,
                        'status'=>$status,
                        'created_at'=>date("Y-m-d H:i:s")
                        );
                    $this->meeting_model->addStatusLog($post_data_log,$client_info);


                    
                    // =========================
                    // HISTORY CREATE
                    $meeting_assigned_user_name=get_value("name","user","id=".$user_id,$client_info);
                    $meeting_agenda_type=get_value("name","meeting_agenda_type","id=".$meeting_agenda_type_id,$client_info);
                    $company=$this->customer_model->get_company_detail($customer_id,$client_info);

                    $update_by=$user_id;
                    $ip_addr=$_SERVER['REMOTE_ADDR'];				
                    $message ="";
                    $message .=($company['company_name'])?"Company Name: ".$company['company_name']:"Company Name: N/A";
                    $message .=($meeting_type=='P')?" | Field Meeting":" | Online Meeting";
                    $message .=" | Meeting Assignd to: ".$meeting_assigned_user_name;
                    $message .=" | Meeting With: ".$meeting_with_before_checkin_time;
                    $message .=" | Meeting Type: ".$meeting_agenda_type;
                    $message .=" | Purpose of Meeting: ".$meeting_Purpose;
                    $message .=($meeting_type=='P')?' | Venue: '.$meeting_venue:' | Online Meeting URL: '.$meeting_url;
                    $message .=" | Meeting schedule start: ".datetime_db_format_to_display_format_ampm($meeting_schedule_start_datetime);
                    $message .=" | Meeting schedule end: ".datetime_db_format_to_display_format_ampm($meeting_schedule_end_datetime);
                    $message .=" | Status: ".$status;
                    $comment_title=MEETING_ADD;
                    $historydata=array(
                                'title'=>$comment_title,
                                'lead_id'=>$lead_id,
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

                $data = array(
                    'api_token_success'=>1,
                    'api_action_success'=>1,
                    'api_syntax_success'=>1,
                    'error'=>'',
                    'rows'=>$result
                );
            }
            else
            {
                $data = array(
                    'api_token_success'  => 0,
                    'api_action_success' => 1,
                    'api_syntax_success' => 1,
                    'error'          => 'Missing all parameters.'
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
        Meeting edit
    */
    function update_post()
    {
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);

            $id=$this->post('id');
            $user_id=$this->post('user_id');
            $meeting_type=$this->post('meeting_type');  // P=>Physically Visit, O=>Online	
            $meeting_agenda_type_id=$this->post('meeting_agenda_type_id');
            $customer_id=$this->post('customer_id');
            $meeting_with_before_checkin_time=$this->post('meeting_with_before_checkin_time');
            $lead_id=$this->post('lead_id');
            $meeting_schedule_start_datetime=$this->post('meeting_schedule_start_datetime');
            $meeting_schedule_end_datetime=$this->post('meeting_schedule_end_datetime');
            $meeting_Purpose=$this->post('meeting_Purpose');
            $meeting_venue=$this->post('meeting_venue');
            $meeting_venue_latitude=$this->post('meeting_venue_latitude');
            $meeting_venue_longitude=$this->post('meeting_venue_longitude');
            $meeting_url_type=$this->post('meeting_url_type');
            $meeting_url=$this->post('meeting_url');

            if($id!='' &&  $user_id!='' && ($meeting_type=='P' || $meeting_type=='O') && $meeting_agenda_type_id!='' && $customer_id!='' && $meeting_with_before_checkin_time!='' && $lead_id!='' && $meeting_schedule_start_datetime!='' && $meeting_schedule_end_datetime!='')
            {
                $post_data=array(
                    'user_id'=>$user_id,
                    'meeting_type'=>$meeting_type,
                    'meeting_agenda_type_id'=>$meeting_agenda_type_id,
                    'customer_id'=>$customer_id,
                    'meeting_with_before_checkin_time'=>$meeting_with_before_checkin_time,
                    'lead_id'=>$lead_id,
                    'meeting_schedule_start_datetime'=>$meeting_schedule_start_datetime,
                    'meeting_schedule_end_datetime'=>$meeting_schedule_end_datetime,
                    'meeting_Purpose'=>$meeting_Purpose,
                    'meeting_venue'=>$meeting_venue,
                    'meeting_venue_latitude'=>$meeting_venue_latitude,
                    'meeting_venue_longitude'=>$meeting_venue_longitude,
                    'meeting_url_type'=>$meeting_url_type,
                    'meeting_url'=>$meeting_url,                    
                    'updated_at'=>date("Y-m-d H:i:s")
                    );                                    
                $result=$this->meeting_model->update($post_data,$id,$client_info);
                if($result===true)
                {
                    $data = array(
                        'api_token_success'=>1,
                        'api_action_success'=>1,
                        'api_syntax_success'=>1,
                        'error'=>'',
                        'rows'=>$result
                    );
                }
                else
                {
                    $data = array(
                        'api_token_success'  => 0,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => 'Record not updated. Something wrong!!'
                        ); 
                }
                
            }
            else
            {
                $data = array(
                    'api_token_success'  => 0,
                    'api_action_success' => 1,
                    'api_syntax_success' => 1,
                    'error'          => 'Missing all parameters.'
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
        Meeting edit
    */
    function update_checkin_post()
    {
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);

            $id=$this->post('id');
            $checkin_datetime=$this->post('checkin_datetime');

            $meeting_venue_latitude=$this->post('meeting_venue_latitude');
            $meeting_venue_longitude=$this->post('meeting_venue_longitude');


            if($id!='' &&  $checkin_datetime!='')
            {
                $status_id=2;
                $status=get_value("name","meeting_status","id=".$status_id,$client_info);
                $post_data=array(
                    'status_id'=>$status_id,
                    'status'=>$status,
                    'meeting_venue_latitude'=>$meeting_venue_latitude,
                    'meeting_venue_longitude'=>$meeting_venue_longitude,
                    'checkin_datetime'=>$checkin_datetime,                    
                    'updated_at'=>date("Y-m-d H:i:s")
                    );                          
                $result=$this->meeting_model->update($post_data,$id,$client_info);
                if($result===true)
                {
                    $post_data_log=array(
                        'meeting_id'=>$result,
                        'status_id'=>$status_id,
                        'status'=>$status,
                        'created_at'=>date("Y-m-d H:i:s")
                        );
                    $this->meeting_model->addStatusLog($post_data_log,$client_info);
                }
                $data = array(
                    'api_token_success'=>1,
                    'api_action_success'=>1,
                    'api_syntax_success'=>1,
                    'error'=>'',
                    'rows'=>$result
                );
            }
            else
            {
                $data = array(
                    'api_token_success'  => 0,
                    'api_action_success' => 1,
                    'api_syntax_success' => 1,
                    'error'          => 'Missing all parameters.'
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
        Meeting edit
    */
    function update_checkout_post()
    {
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);

            $id=$this->post('id');
            $checkout_datetime=$this->post('checkout_datetime');
            $meeting_dispose_latitude=$this->post('meeting_dispose_latitude');
            $meeting_dispose_longitude=$this->post('meeting_dispose_longitude');
            $meeting_with_at_checkin_time=$this->post('meeting_with_at_checkin_time');
            $self_visited_or_visited_with_colleagues=$this->post('self_visited_or_visited_with_colleagues');
            $visited_colleagues=$this->post('visited_colleagues');
            $discussion_points=$this->post('discussion_points');

            
            if($id!='' &&  $checkout_datetime!='' && $meeting_with_at_checkin_time!='' && ($self_visited_or_visited_with_colleagues=='S' || $self_visited_or_visited_with_colleagues=='C') && $discussion_points!='')
            {
                if($self_visited_or_visited_with_colleagues=='C')
                {
                    if($visited_colleagues=='')
                    {
                        $data = array(
                            'api_token_success'  => 0,
                            'api_action_success' => 1,
                            'api_syntax_success' => 1,
                            'error' => 'Missing visited colleagues.'
                            ); 
                        $this->response($data, 200);
                        exit;
                    }
                }
                $status_id=3;
                $status=get_value("name","meeting_status","id=".$status_id,$client_info);
                $post_data=array(
                    'status_id'=>$status_id,
                    'status'=>$status,
                    'meeting_dispose_latitude'=>$meeting_dispose_latitude,
                    'meeting_dispose_longitude'=>$meeting_dispose_longitude,
                    'checkout_datetime'=>$checkout_datetime,
                    'meeting_with_at_checkin_time'=>$meeting_with_at_checkin_time,
                    'self_visited_or_visited_with_colleagues'=>$self_visited_or_visited_with_colleagues,
                    'visited_colleagues'=>$visited_colleagues,
                    'discussion_points'=>$discussion_points,
                    'updated_at'=>date("Y-m-d H:i:s")
                    );                          
                $result=$this->meeting_model->update($post_data,$id,$client_info);
                if($result===true)
                {
                    $post_data_log=array(
                        'meeting_id'=>$result,
                        'status_id'=>$status_id,
                        'status'=>$status,
                        'created_at'=>date("Y-m-d H:i:s")
                        );
                    $this->meeting_model->addStatusLog($post_data_log,$client_info);

                    if($meeting_dispose_latitude!='' && $meeting_dispose_longitude!='')
                    {
                        $meeting_row=$this->meeting_model->GetMeetingForLocationTrack($id,$client_info);
                        if(count($meeting_row)){
                            $post_data_log=array(
                                'user_id'=>$meeting_row['user_id'],
                                'latitude'=>$meeting_dispose_latitude,
                                'longitude'=>$meeting_dispose_longitude,
                                'address'=>$meeting_row['meeting_venue'],
                                'datetime'=>$meeting_row['checkout_datetime'],
                                'system_datetime'=>date("Y-m-d H:i:s"),
                                'tracking_from'=>'M'
                                );
                            $this->meeting_model->add_user_wise_geo_location_track($post_data_log,$client_info);
                        }                        
                    }
                }
                $data = array(
                    'api_token_success'=>1,
                    'api_action_success'=>1,
                    'api_syntax_success'=>1,
                    'error'=>'',
                    'rows'=>$result
                );
            }
            else
            {
                $data = array(
                    'api_token_success'  => 0,
                    'api_action_success' => 1,
                    'api_syntax_success' => 1,
                    'error'          => 'Missing all parameters.'
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
        Meeting edit
    */
    function update_cancelled_status_post()
    {
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);

            $id=$this->post('id');
            $cancellation_reason=$this->post('cancellation_reason');


            if($id!='' &&  $cancellation_reason!='')
            {
                $status_id=4;
                $status=get_value("name","meeting_status","id=".$status_id,$client_info);
                $post_data=array(
                    'status_id'=>$status_id,
                    'status'=>$status,
                    'cancellation_reason'=>$cancellation_reason,                    
                    'updated_at'=>date("Y-m-d H:i:s")
                    );                          
                $result=$this->meeting_model->update($post_data,$id,$client_info);
                if($result===true)
                {
                    $post_data_log=array(
                        'meeting_id'=>$result,
                        'status_id'=>$status_id,
                        'status'=>$status,
                        'cancellation_reason'=>$cancellation_reason,
                        'created_at'=>date("Y-m-d H:i:s")
                        );
                    $this->meeting_model->addStatusLog($post_data_log,$client_info);
                }
                $data = array(
                    'api_token_success'=>1,
                    'api_action_success'=>1,
                    'api_syntax_success'=>1,
                    'error'=>'',
                    'rows'=>$result
                );
            }
            else
            {
                $data = array(
                    'api_token_success'  => 0,
                    'api_action_success' => 1,
                    'api_syntax_success' => 1,
                    'error'          => 'Missing all parameters.'
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
        colleagues list 
    */
    function colleague_list_post()
    {        
        // if($this->get('token') == $this->access_token) // ACCESS TOKEN CHECKING
        
        if($this->post('token'))
        {
            
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);           
            $user_id=$this->post('user_id');
            $lead_id=$this->post('lead_id');
            if($user_id!='' && $lead_id!='')
            {                
                // $assigned_observer=get_value("assigned_observer","lead","id=".$lead_id,$client_info);    
                // $user_list=array();
                // $arg=array();  
                // $arg['user_id']=$user_id;                    
                // $manager_id=get_value("manager_id","user","id=".$user_id,$client_info);			
                // if($manager_id>0){
                //     $manager_skip_id=get_value("manager_id","user","id=".$manager_id,$client_info);
                // }
                // else{
                //     $manager_skip_id=$user_id;
                // }		
                // $tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($manager_skip_id,0,$client_info);
                // if($user_id==1){
                //     array_push($tmp_u_ids, $user_id);
                // }   		
                // if($assigned_observer>0){
                //     array_push($tmp_u_ids, $assigned_observer);
                // }
                // $tmp_u_ids_str=implode(",", $tmp_u_ids); 

                // $user_list=$this->user_model->GetUserList($tmp_u_ids_str,$client_info);  
                $user_list=$this->user_model->GetUserList('',$client_info);                
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'=>'',
                        'colleague_rows'=>$user_list
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
        meeting agenda type
    */
    function meeting_agenda_type_post()
    {        
        // if($this->get('token') == $this->access_token) // ACCESS TOKEN CHECKING
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);           
            
            $arg=array();  
            $result=$this->meeting_model->GetMeetingAgendaTypeRows($client_info);
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
        Customer wise contact person add 
    */
    function customer_wise_contact_person_add_post()
    {        
        // if($this->get('token') == $this->access_token) // ACCESS TOKEN CHECKING      
        
        if($this->post('token'))
        {
            
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);           
            $customer_id=$this->post('customer_id');
            $name=$this->post('name');
            $email=$this->post('email');
            $mobile=$this->post('mobile');

            
            if($customer_id!='' && $name!='' && ($email!='' || $mobile!=''))
            {                
                $post_data=array(
                                'customer_id'=>$customer_id,
                                'name'=>$name,
                                'email'=>$email,
                                'mobile'=>$mobile,
                                'created_at'=>date("Y-m-d H:i:s")
                            );                            
                $result=$this->customer_model->customer_wise_contact_person_add($post_data,$client_info);
                if($result==false)
                {
                    $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'=> 'Record not added!! Please try again later.'
                        );
                }
                else
                {
                    $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'=>''
                        ); 
                }
                
            }
            else
            {
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 0,
                        'error'=> 'Expected parameter missing.'
                        );
            }
                      

        }
        else
        {
            $data = array(
                        'api_token_success'  => 0,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => 'Token not matching.'
                        ); 
        } 
              
        $this->response($data, 200);
        exit;
        
    }

    /*
        Meeting list by Date
    */
    function list_by_date_post()
    {        
        // if($this->get('token') == $this->access_token) // ACCESS TOKEN CHECKING
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $date=$this->post('date');
            $arg=array();       
            $arg['user_id']=$user_id; 
            $arg['date']=$date;                          
            $result=$this->meeting_model->GetListBydate($arg,$client_info);
            $data = array(
                'api_token_success'=>1,
                'api_action_success'=>1,
                'api_syntax_success'=>1,
                'error'=>'',
                'rows'=>$result
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

    function list_by_year_month_post()
    {
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id=$this->post('user_id');
            $date=$this->post('date');
            $date_arr = explode("-",$date);
            
            $arg=array();       
            $arg['year']=$date_arr[0];
            $arg['month']=$date_arr[1];
            $arg['user_id']=$user_id;                  
            $result=$this->meeting_model->GetListByYearMonth($arg,$client_info);
            $data = array(
                'api_token_success'=>1,
                'api_action_success'=>1,
                'api_syntax_success'=>1,
                'error'=>'',
                'rows'=>$result
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
}
?>
