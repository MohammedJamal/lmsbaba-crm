<?php 
require(APPPATH.'/libraries/REST_Controller.php');
class Rest_app_setting extends REST_Controller
{
    private $access_token = '';
    public function __construct()
   	{
		parent::__construct();      
        $this->load->model(array("Client_model"));
        $this->access_token = get_access_token();
	}
    
    function app_setting_get()
    {
        $setting_info=$this->Client_model->get_app_setting(1);
        
        if(count($setting_info))
        {
            $data = array(
                'error'              => '',
                'row'               => $setting_info
                 );
        }
        else
        {
            $data = array(
                'error'              => 'No record found',
                'row'               => array()
                 );
        }
        $this->response($data, 200);
    }  
}
?>
