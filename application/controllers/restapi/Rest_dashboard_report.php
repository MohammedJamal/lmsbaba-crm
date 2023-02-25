<?php 
require(APPPATH.'/libraries/REST_Controller.php');
class Rest_dashboard_report extends REST_Controller
{
    private $access_token = '';
    public function __construct()
   	{
		parent::__construct();      
        $this->load->model(array("lead_model","User_model","history_model","Client_model"));
        $this->access_token = get_access_token();
	}

    /*
    Dashboard report count
    */
    function report_count_post()
    {        
        // if($this->get('token') == $this->access_token) // ACCESS TOKEN CHECKING
        if($this->post('token'))
        {
            $client_id=$this->post('client_id');
            $client_info=$this->Client_model->get_details($client_id);
            $user_id = $this->post('user_id');  
            $tmp_u_ids=$this->User_model->get_self_and_under_employee_ids($user_id,0,$client_info);
            array_push($tmp_u_ids, $user_id);
   		    $tmp_u_ids_str=implode(",", $tmp_u_ids); 
            $arg=array();  
            $arg['assigned_user']=$tmp_u_ids_str; 
            $arg['user_id']=$user_id; 

            if($arg['assigned_user']!='')
            {                
                $result=$this->lead_model->get_dashboard_report_count($arg,$client_info);
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
    
}
?>
