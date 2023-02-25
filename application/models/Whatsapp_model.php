<?php
class Whatsapp_model extends CI_Model
{	
  private $client_db = '';
  private $fsas_db = '';
	function __construct() 
	{
        parent::__construct();
      	// $this->load->database();
      	$this->user_arr=array();
        $this->client_db = $this->load->database('client', TRUE);
        $this->fsas_db = $this->load->database('default', TRUE);
  }	

    function add($data)
	{
		if($this->client_db->insert('tbl_whatsapp',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}

	function add_detail($data)
	{
		if($this->client_db->insert('tbl_whatsapp_response',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}

	function add_test_response($data)
	{
		if($this->client_db->insert('response_test',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}
	}
}

?>