<?php 
require(APPPATH.'/libraries/REST_Controller.php');
class Rest_geo_location extends REST_Controller
{
    private $access_token = '';
    public function __construct()
   	{
		parent::__construct();      
        $this->load->model(array("User_model","Client_model"));
        $this->access_token = get_access_token();

	}

    

    /*
    Get latitude and longitude
    */
    function lat_long_post()
    {        
        if($this->post('token'))
        {
            
            $client_id=$this->post('client_id');
            $user_id=$this->post('user_id');
            $latitude=$this->post('latitude');
            $longitude=$this->post('longitude');
            $address=$this->post('l_addresses');
            $datetime=$this->post('datetime'); // YYYY-mm-dd H:i;s
            if($client_id!='' && $user_id!='' && $latitude!='' && $longitude!='' && $datetime!='')
            {   
                $client_info=$this->Client_model->get_details($client_id);                
                $user_data=$this->User_model->get_user_details($user_id,$client_info); 
                if(count($user_data)>0)
                {
                    $post_data=array(
                                    'user_id'=>$user_id,
                                    'latitude'=>$latitude,
                                    'longitude'=>$longitude,
                                    'address'=>$address,
                                    'datetime'=>$datetime,
                                    'system_datetime'=>date("Y-m-d H:i:s")
                    );
                    $r=$this->User_model->addGeoLocation($post_data,$client_info);
                    if($r)
                    {
                        $data = array(
                            'api_token_success'  => 1,
                            'api_syntax_success' => 1,
                            'api_action_success' => 1,
                            'error'              => '',
                            'row' => ''
                            );
                    }
                    else
                    {
                        $data = array(
                            'api_token_success'  => 1,
                            'api_syntax_success' => 1,
                            'api_action_success' => 0,
                            'error'              => 'Insert error'
                            );
                    }
                     
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
