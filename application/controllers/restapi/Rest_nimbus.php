<?php 
require(APPPATH.'/libraries/REST_Controller.php');
class Rest_nimbus extends REST_Controller
{
    private $access_token = '';
    public function __construct()
   	{
		parent::__construct(); 
        $this->load->model(array("Client_model","lead_model"));
        $this->access_token = get_access_token();
	}
    function lead_info_get()
    {        
        // ec8bd783ef9d8f1092461753ab832c7b (noidabpo) // LMS ID : 293
        // 759b74ce43947f5f4c91aeddc3e5bad3 (dev)
        if($this->get('token')=='ec8bd783ef9d8f1092461753ab832c7b')
        {
            $token=$this->get('token');
            $client_id=$this->Client_model->get_id_from_token($token);            
            $client_info=$this->Client_model->get_details($client_id);
            $lead_id=$this->get('lead_id');
            if($lead_id!='')
            {                
                $row=$this->lead_model->GetLeadData($lead_id,$client_info);
                $lead_info=array('lead_title'=>$row->title,
                                'contact_person_name'=>$row->cus_contact_person,
                                'company_name'=>$row->cus_company_name,
                                'mobile'=>$row->cus_mobile,
                                'city'=>$row->cus_city,
                                'state'=>$row->cus_state,
                                'country'=>$row->cus_country
                            );
                $data = array(                        
                        'error'=>'',
                        'lead_info'=>$lead_info
                        ); 
            }
            else
            {
                $data = array(
                        'error'=> 'Expected parameter missing.'
                        );
            }
                      

        }
        else
        {
            $data = array(
                        'error'          => 'Token not matching.'
                        ); 
        } 
              
        $this->response($data, 200);
    }  
}
?>
