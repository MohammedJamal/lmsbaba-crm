<?php
class Db_import_model extends CI_Model
{
  private $client_db = '';
  private $fsas_db = '';
	function __construct() 
  {
    parent::__construct();
		//$this->load->database();	
    $this->client_db = $this->load->database('client', TRUE);
    $this->fsas_db = $this->load->database('default', TRUE);	
  }

  public function add($data)
  {
    if($this->client_db->insert('product',$data))
      {
           return $this->client_db->insert_id();
      }
      else
      {
          return false;
      }
  }
  
  public function edit($id,$data)
  {
    $this->client_db->where('product_id',$id);
    if($this->client_db->update('product',$data))
      {
           return true;
      }
      else
      {
          return false;
      }
  }

  

  public function GetAllRecord()
  {
      $this->client_db->select('*',false);
      $this->client_db->from('test');
      //$this->client_db->where('is_deleted','N');
      $result=$this->client_db->get();
      //echo $this->client_db->last_query(); die();
      return $result->result();
  }  
}
?>