<?php 
require(APPPATH.'/libraries/REST_Controller.php');
class Rest_client extends REST_Controller
{
    private $access_token = '';
    public function __construct()
   	{
		parent::__construct();      
        $this->load->model(array("Client_model"));
        $this->access_token = get_access_token();
	}
    
    function getDetail_post()
    {        
        $domain_name = rtrim($this->post('domain_name'), "/");
        //$domain_name=$this->post('domain_name');       
        $url=preg_replace('#^(http(s)?://)?w{3}\.#', '', $domain_name);
        $url = preg_replace("(^https?://)", "", $url );
        
        $url_arr=explode('/', $url);
        $domain_name=end($url_arr);

        $result=$this->Client_model->get_detail_from_domain_name($domain_name);
        if($result!=FALSE)
        {
           $data = array(
                'api_token_success'  => 1,
                'api_action_success' => 1,
                'api_syntax_success' => 1,
                'error'          => '',
                'client' => $result
                 ); 
        }
        else
        {
            $data = array(
                'api_token_success'  => 1,
                'api_action_success' => 0,
                'api_syntax_success' => 1,
                'error'          => '',
                'client' => array()
                 );
        }
        
        $this->response($data, 200);
    }   
}
?>
