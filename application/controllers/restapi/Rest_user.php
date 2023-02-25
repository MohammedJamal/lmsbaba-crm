<?php 
require(APPPATH.'/libraries/REST_Controller.php');
class Rest_user extends REST_Controller
{
    private $access_token = '';
    public function __construct()
   	{
		parent::__construct();      
        $this->load->model(array("User_model","Client_model","Setting_model"));
        $this->access_token = get_access_token();

	}

    

    /*
    validate
    */
    function user_validate_post()
    {   
        // if($this->post('token') == $this->access_token) // ACCESS TOKEN CHECKING
        if($this->post('token'))
        {
            $user_id=$this->post('user_id');
            $password=$this->post('password');
            $client_id=$this->post('client_id');
            if($user_id!='' && $password!='')
            {   
                $client_info=$this->Client_model->get_details($client_id);
                $r=$this->User_model->get_loggedin_info($user_id,md5($password),$client_info);
                if($r)
                {
                    $company_info=$this->Setting_model->GetCompanyData($client_info) ;
                    $company_info_tmp=array();
                    $company_info_tmp=array(
                                            'company_name'=>$company_info['name'],
                                            'company_location'=>$company_info['city_name']
                                            );	
                    $data = array(
                                'api_token_success'  => 1,
                                'api_syntax_success' => 1,
                                'api_action_success' => 1,
                                'error'              => '',
                                'row' => $r,
                                'company_info'=>$company_info_tmp
                                ); 
                }
                else
                {
                    $data = array(
                                'api_token_success'  => 1,
                                'api_syntax_success' => 0,
                                'api_action_success' => 1,
                                'error'              => 'User not found.'
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
  
}
?>
