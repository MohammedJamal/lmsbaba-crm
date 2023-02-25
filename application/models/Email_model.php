<?php
class Email_model extends CI_Model
{
	private $client_db = '';
	private $fsas_db = '';
	function __construct() {
        parent::__construct();
		// $this->load->database();
		$this->user_arr=array();
		$this->client_db = $this->load->database('client', TRUE);
		$this->fsas_db = $this->load->database('default', TRUE);
    }

    

   

	public function GetLeadListAll()
	{
		$sql="select lead.*,user.name as user_name,cus.first_name as cus_first_name,cus.last_name as cus_last_name,cus.mobile as cus_mobile,source.name as source_name from lead inner join customer as cus on cus.id=lead.customer_id inner join source on source.id=lead.source_id inner join user on user.id=lead.assigned_user_id";

		$result=$this->client_db->query($sql);
		
		return $result->result();

	}
	

	
	function CreateEmail($data)
	{

		if($this->client_db->insert('email',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}

	}
	function UpdateEmail($data,$id)
	{

		$this->client_db->where('id',$id);

		if($this->client_db->update('email',$data))
		{
			return true;		  
		}   		
   		else
   		{
          return false;
   		}

	}
	
	public function GetEmailData($id)
	{
		$sql="select email.*,cus.id as customer_id from email left join customer as cus on cus.email=email.from_email where email.id='".$id."' ";

		$result=$this->client_db->query($sql);
		
		return $result->row();

	}
	
	public function CheckExistEmail($msg_no)
	{

		$this->client_db->from('email');

		$this->client_db->where('msg_no',$msg_no);

		//$this->client_db->where('status','0');	

		$result=$this->client_db->get();

		return $result->result();

	}
	
	
	public function GetEmailList($user_id)
	{

		$this->client_db->select('email.*,cus.id as cus_id');
		$this->client_db->from('email');
		
		$this->client_db->join('customer as cus', 'cus.id = email.id', 'left');
		
		$this->client_db->where('email.user_id',$user_id);

		$this->client_db->where('email.status','0');	
		
		$this->client_db->order_by('email.email_date', 'desc');

		$result=$this->client_db->get();

		return $result->result();

	}
	
	public function GetDeletedEmailList($user_id)
	{
		$status=array('3','4','5');
		$this->client_db->select('email.*,cus.id as cus_id');
		$this->client_db->from('email');
		
		$this->client_db->join('customer as cus', 'cus.id = email.id', 'left');
		
		$this->client_db->where('email.user_id',$user_id);

		$this->client_db->where_in('email.status',$status);	
		
		
		$this->client_db->order_by('email.id', 'desc');

		$result=$this->client_db->get();

		return $result->result();

	}
	
	

	function UpdateLead($data,$id)
	{

		$this->client_db->where('id',$id);

		if($this->client_db->update('lead',$data))
		{
			return true;
		}
		else
		{
			return false;
		}		

	}

	function DeleteEmail($id,$status)
	{
		$data=array('status'=>$status);
		$this->client_db->where('id',$id);

		if($this->client_db->update('email',$data))
		{
			return true;
		}
		else
		{
			return false;
		}		

	}
	function RepliedEmail($id)
	{
		$data=array('status'=>'2');
		$this->client_db->where('id',$id);

		if($this->client_db->update('email',$data))
		{
			return true;
		}
		else
		{
			return false;
		}		

	}
	
	function CreateEmailAttachment($data)
	{

		if($this->client_db->insert('email_attachment',$data))
   		{
           return true;
   		}
   		else
   		{
          return false;
   		}

	}
	
	public function GetEmailAttachmentList($email_id)
	{
		$this->client_db->from('email_attachment');
		
		$this->client_db->where('email_id',$email_id);

		$result=$this->client_db->get();

		return $result->result();

	}
	public function GetEmailAttachmentData($id)
	{
		$this->client_db->from('email_attachment');
		
		$this->client_db->where('id',$id);

		$result=$this->client_db->get();

		return $result->row();

	}

}

?>