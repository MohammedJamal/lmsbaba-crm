<?php 
require(APPPATH.'/libraries/REST_Controller.php');
class Rest_meeting extends REST_Controller
{
    private $access_token = '';
    public function __construct()
   	{
		parent::__construct();      
        $this->load->model(array("meeting_model","Client_model","customer_model","lead_model","user_model"));
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
            
            $arg=array();  
            $arg['assigned_user']=$user_id;
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
            
            $start=empty($start)?0:($start-1)*$limit;
            $arg=array();       
            $arg['user_id']=$user_id;
            $arg['status']=$status;
            $arg['start'] = $start;
            $arg['limit'] = $limit;   
            $arg['listing_type'] = $listing_type;            
            $result=$this->meeting_model->GetList($arg,$client_info);
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


            if($id!='' &&  $checkin_datetime!='')
            {
                $status_id=2;
                $status=get_value("name","meeting_status","id=".$status_id,$client_info);
                $post_data=array(
                    'status_id'=>$status_id,
                    'status'=>$status,
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
            if($user_id!='')
            {
                $user_list=array();
                $arg=array();  
                $arg['user_id']=$user_id;
                $tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0,$client_info);                
                // array_push($tmp_u_ids, $user_id);
                $tmp_u_ids_str=implode(",", $tmp_u_ids);                
                $user_list=$this->user_model->GetUserList($tmp_u_ids_str,$client_info);                
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
}
?>
