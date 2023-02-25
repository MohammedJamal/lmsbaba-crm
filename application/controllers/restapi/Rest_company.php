<?php 
require(APPPATH.'/libraries/REST_Controller.php');
class Rest_company extends REST_Controller
{
    private $access_token = '';
    public function __construct()
   	{
		parent::__construct();      
        $this->load->model(array("meeting_model","Client_model","customer_model","lead_model","user_model","history_model"));
        $this->access_token = get_access_token();
	}


    /*
        List
    */
    function list_post()
    {   
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);            
            $user_id=$this->post('user_id');            
            $start=$this->post('start');
            $limit=$this->post('limit');            
            $search_str=$this->post('search_str');           

            $tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0,$client_info);
            array_push($tmp_u_ids, $user_id);
   		    $tmp_u_ids_str=implode(",", $tmp_u_ids); 

            $start=empty($start)?0:($start-1)*$limit;
            $arg=array();       
            $arg['user_id']=$user_id;
            $arg['user_id_str']=$tmp_u_ids_str;            
            $arg['start'] = $start;
            $arg['limit'] = $limit;              
            $arg['search_str'] = $search_str;
            
            $result=$this->customer_model->get_list_api($arg,$client_info);
            $result_count=$this->customer_model->get_list_count_api($arg,$client_info);
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
        details
    */
    function detail_post()
    {
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id = $this->post('user_id');
            $c_id = $this->post('c_id');
            $tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0,$client_info);
            array_push($tmp_u_ids, $user_id);
   		    $tmp_u_ids_str=implode(",", $tmp_u_ids);             
            $arg=array();  
            $arg['assigned_user']=$tmp_u_ids_str;           
            $arg['c_id']=$c_id;
            if($arg['c_id']!='')
            {
                $result=$this->customer_model->get_detail_api($arg,$client_info);
                $lead_result=$this->lead_model->get_customer_wise_lead_list($arg,$client_info); 
                $coutdata=$this->customer_model->get_customers_wise_lead_count($arg['c_id'],$client_info);
                
                $data = array(
                        'api_token_success'  => 1,
                        'api_action_success' => 1,
                        'api_syntax_success' => 1,
                        'error'          => '',
                        'lead_count' => $coutdata[0],
                        'company_row' => $result,
                        'company_wise_lead_rows' => $lead_result
                        
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
        Company Search by email/mobile
    */
    function search_by_email_or_mobile_post()
    {   
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);            
            $user_id=$this->post('user_id');      
            $cid=$this->post('cid');      
            $mobile=trim($this->post('mobile'));
            $email=trim($this->post('email'));            
                
            if($mobile=='' && $email=='')
            {
                $data = array(
                    'api_token_success'  => 0,
                    'api_action_success' => 1,
                    'api_syntax_success' => 1,
                    'error'          => 'Mobile / Email both are missing.',
                    'rows' => array()
                    ); 
                $this->response($data, 200);exit;
            }

            if($cid>0)
			{	
				$data['company']=$this->customer_model->company_search_to_add_lead_by_id($cid,$client_info);
				// $assigned_user_id=$data['company'][0]['assigned_user_id'];
				// $data['state_list']=$this->states_model->GetStatesList($data['company'][0]['country_id'],$client_info);
				// $data['city_list']=$this->cities_model->GetCitiesList($data['company'][0]['state'],$client_info);
				
			}
			else
			{
				if($mobile!='' || $email!='')
				{
					$data['company']=$this->customer_model->company_search_to_add_lead($mobile,$email,$client_info);
					// $assigned_user_id=$data['company'][0]['assigned_user_id'];
					
					if(count($data['company'])==1){
						// $data['state_list']=$this->states_model->GetStatesList($data['company'][0]['country_id'],$client_info);
						// $data['city_list']=$this->cities_model->GetCitiesList($data['company'][0]['state'],$client_info);
						if($data['company'][0]['is_blacklist']=='Y'){							
                            $data = array(
                                'api_token_success'  => 0,
                                'api_action_success' => 1,
                                'api_syntax_success' => 1,
                                'error'          => 'Customer blacklisted.',
                                'rows' => array()
                                ); 
                            $this->response($data, 200);exit;
						}					
					}
				}
				else
				{
					// $assigned_user_id=0;
				}
			}
            
            $data = array(
                'api_token_success'=>1,
                'api_action_success'=>1,
                'api_syntax_success'=>1,
                'error'=>'',
                'rows_count'=>count($data['company']),
                'rows'=>$data['company']
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
