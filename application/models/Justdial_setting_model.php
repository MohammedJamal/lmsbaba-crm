<?php
class Justdial_setting_model extends CI_Model
{
	private $client_db = '';
	private $fsas_db = '';
	private $class_name = '';
	function __construct() 
	{
        parent::__construct();
		// $this->load->database();
		$this->class_name=$this->router->fetch_class();
		$this->user_arr=array();
		$this->client_db = $this->load->database('client', TRUE);
		$this->fsas_db = $this->load->database('default', TRUE);
    }	

	

	public function EditJustdialCredentials($data,$id,$client_info=array())
	{
		if($this->class_name=='cronjobs')
		{
			$config['hostname'] = DB_HOSTNAME;
	        $config['username'] = $client_info->db_username;
	        $config['password'] = $client_info->db_password;
	        $config['database'] = $client_info->db_name;
	        $config['dbdriver'] = 'mysqli';
	        $config['dbprefix'] = '';
	        $config['pconnect'] = FALSE;
	        $config['db_debug'] = FALSE;
	        $config['cache_on'] = FALSE;
	        $config['cachedir'] = '';
	        $config['char_set'] = 'utf8';
	        $config['dbcollat'] = 'utf8_general_ci'; 
	        $this->client_db=$this->load->database($config,TRUE);
		}
		$this->client_db->where('id',$id);
		if($this->client_db->update('justdial_setting',$data))
		{			
			//return true;		
		}
		else
		{
			//return false;
		}	
	}

	
	public function DeleteCredentials($id)
	{
		$this->client_db->where('id', $id);
		$this->client_db->delete('justdial_setting');
		return true;
	}

	public function GetCredentials($client_info=array())
	{
		if($this->class_name=='cronjobs')
		{
			$config['hostname'] = DB_HOSTNAME;
	        $config['username'] = $client_info->db_username;
	        $config['password'] = $client_info->db_password;
	        $config['database'] = $client_info->db_name;
	        $config['dbdriver'] = 'mysqli';
	        $config['dbprefix'] = '';
	        $config['pconnect'] = FALSE;
	        $config['db_debug'] = FALSE;
	        $config['cache_on'] = FALSE;
	        $config['cachedir'] = '';
	        $config['char_set'] = 'utf8';
	        $config['dbcollat'] = 'utf8_general_ci'; 
	        $this->client_db=$this->load->database($config,TRUE);
		}

		$sql="SELECT id,
			account_name,
			assign_rule,
			assign_to,
			assign_start FROM justdial_setting LIMIT 1";
		$query = $this->client_db->query($sql,false);        
		//$last_query = $this->client_db->last_query();die();
		$return=array();
		if($query){
			if($query->num_rows())
			{
				foreach($query->result_array() AS $row)
				{
					$assign_to_arr==array();
					if($row['assign_to']){
						$assign_to_arr=unserialize($row['assign_to']);
					}
					$assign_to_atr='';
					if(count($assign_to_arr)){
						$assign_to_atr=implode(",",$assign_to_arr);
					}
					
					if($assign_to_atr)
					{
						$sql2="SELECT GROUP_CONCAT(name) AS assign_to_str from user WHERE id IN ($assign_to_atr)";
						$query2 = $this->client_db->query($sql2,false);
						$row2=$query2->row_array();
						$assign_to_name=$row2['assign_to_str'];
					}
					else
					{
						$assign_to_name='--';
					}
					
					$return=array(
							'id'=>$row['id'],
							'account_name'=>$row['account_name'],
							'assign_rule'=>$row['assign_rule'],
							'assign_to'=>$row['assign_to'],
							'assign_start'=>$row['assign_start'],
							'assign_to_str'=>$assign_to_name
						);
				}
			}
		}
		else{

		}
		
		return $return;
	}
	
	function AddCredentials($data)
	{
		if($this->client_db->insert('justdial_setting',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function get_rules($sid)
	{
		$sql="SELECT id,jd_setting_id,assign_rule_id,find_to,assign_to,assign_start FROM justdial_assign_rule_details WHERE jd_setting_id='".$sid."'";
		$query = $this->client_db->query($sql,false);
		if($query){
			return $query->result_array();
		}
		else{
			return array();
		}
		
	}

	public function GetJustdialCredentialsDetails($id)
	{
		$sql="SELECT 
		id,
		account_name,
		assign_rule,
		assign_to,
		assign_start 
		FROM justdial_setting WHERE id='".$id."'";
		$query = $this->client_db->query($sql,false);        
		//$last_query = $this->client_db->last_query();die();
		$return=array();
		if($query){
			if($query->num_rows())
			{
				foreach($query->result_array() AS $row)
				{
					$assign_to_arr=unserialize($row['assign_to']);
					$assign_to_atr=implode(",",$assign_to_arr);
					if($assign_to_atr)
					{
						$sql2="SELECT GROUP_CONCAT(name) AS assign_to_str from user WHERE id IN ($assign_to_atr)";
						$query2 = $this->client_db->query($sql2,false);        
						$row2=$query2->row_array();
						$assign_to_name=$row2['assign_to_str'];
					}
					else
					{
						$assign_to_name='--';
					}
					
					$return=array(
							'id'=>$row['id'],
							'account_name'=>$row['account_name'],
							'assign_rule'=>$row['assign_rule'],
							'assign_to'=>$row['assign_to'],
							'assign_start'=>$row['assign_start'],
							'assign_to_str'=>$assign_to_name
						);
				}
			}
		}
		else{

		}
		
		return $return;
	}

	public function DeleteJustdialCredentialsDetails($id)
	{
		$this->client_db->where('jd_setting_id', $id);
		$this->client_db->delete('justdial_assign_rule_details');
		return true;
	}

	function AddJustdialCredentialsDetails($data)
	{
		if($this->client_db->insert('justdial_assign_rule_details',$data))
   		{
           return $this->client_db->insert_id();
   		}
   		else
   		{
          return false;
   		}
	}

	public function get_rule_wise_assigned_user_id($arr,$client_info=array())
	{
		if($this->class_name=='cronjobs')
		{
			$config['hostname'] = DB_HOSTNAME;
	        $config['username'] = $client_info->db_username;
	        $config['password'] = $client_info->db_password;
	        $config['database'] = $client_info->db_name;
	        $config['dbdriver'] = 'mysqli';
	        $config['dbprefix'] = '';
	        $config['pconnect'] = FALSE;
	        $config['db_debug'] = FALSE;
	        $config['cache_on'] = FALSE;
	        $config['cachedir'] = '';
	        $config['char_set'] = 'utf8';
	        $config['dbcollat'] = 'utf8_general_ci'; 
	        $this->client_db=$this->load->database($config,TRUE);
		}

		$assign_rule=$arr['assign_rule'];
		$justdial_setting_id=$arr['justdial_setting_id'];
		$search_keyword=$arr['search_keyword'];

		$sql="SELECT 
			id,
			find_to,
			assign_to,
			assign_start 
			FROM justdial_assign_rule_details WHERE jd_setting_id='".$justdial_setting_id."' AND assign_rule_id='".$assign_rule."'";

		$query = $this->client_db->query($sql,false);        
		//$last_query = $this->client_db->last_query();die();
		$return=array();
		if($query){
			if($query->num_rows())
			{

				foreach($query->result_array() AS $row)
				{
					$id=$row['id'];
					$find_to=unserialize($row['find_to']);
					$assign_to=unserialize($row['assign_to']);
					$assign_start=$row['assign_start'];	
					$assign_end=(count($assign_to)-1);

					if(in_array($search_keyword, $find_to))
					{
						//$tmp_assigned_user_id=$assign_to[$assign_start];
						$assign_start++;
						if($assign_start>$assign_end)
						{
							$assign_start=0;
						}

						// $post_data=array('assign_start'=>$assign_start);
						// $this->client_db->where('id',$id);
						// $this->client_db->update('indiamart_assign_rule_details',$post_data);

						// return $tmp_assigned_user_id;
						break;
					}
					else
					{
						if($find_to=='other')
						{
							//$tmp_assigned_user_id=$assign_to[$assign_start];
							$assign_start++;
							if($assign_start>$assign_end)
							{
								$assign_start=0;
							}

							// $post_data=array('assign_start'=>$assign_start);
							// $this->client_db->where('id',$id);
							// $this->client_db->update('tradeindia_assign_rule_details',$post_data);

							// return $tmp_assigned_user_id;
							break;
						}
					}
				}

				$post_data=array('assign_start'=>$assign_start);
				// echo $id.':'.$assign_start.'<br>';
				// echo $assign_to[$assign_start].'<br>';
				$this->client_db->where('id',$id);
				$this->client_db->update('justdial_assign_rule_details',$post_data);	
				// echo $last_query = $this->client_db->last_query();die();		
				return $assign_to[$assign_start];
			}
		}
		else{
			return 0;
		}
				
	}

	public function get_keyword_rule5_wise_assigned_user_id($arr,$client_info=array())
	{
		if($this->class_name=='cronjobs')
		{
			$config['hostname'] = DB_HOSTNAME;
	        $config['username'] = $client_info->db_username;
	        $config['password'] = $client_info->db_password;
	        $config['database'] = $client_info->db_name;
	        $config['dbdriver'] = 'mysqli';
	        $config['dbprefix'] = '';
	        $config['pconnect'] = FALSE;
	        $config['db_debug'] = FALSE;
	        $config['cache_on'] = FALSE;
	        $config['cachedir'] = '';
	        $config['char_set'] = 'utf8';
	        $config['dbcollat'] = 'utf8_general_ci'; 
	        $this->client_db=$this->load->database($config,TRUE);
		}
		$assign_rule=$arr['assign_rule'];
		$justdial_setting_id=$arr['justdial_setting_id'];
		$search_keyword=$arr['search_keyword'];

		$sql="SELECT 
			id,
			find_to,
			assign_to,
			assign_start 
			FROM justdial_assign_rule_details WHERE jd_setting_id='".$justdial_setting_id."' AND assign_rule_id='".$assign_rule."'";
		$query = $this->client_db->query($sql,false);
		$return=array();
		if($query){
			if($query->num_rows())
			{
				foreach($query->result_array() AS $row)
				{
					$id=$row['id'];
					$find_to=unserialize($row['find_to']);
					$assign_to=unserialize($row['assign_to']);
					$assign_start=$row['assign_start'];	
					$assign_end=(count($assign_to)-1);
					if($find_to!='other')
					{         
						if(count($find_to))
						{
							for($i=0;$i<count($find_to);$i++)
							{        
								if(strpos($search_keyword, $find_to[$i]) !== false)
								{					
									$assign_start++;
									if($assign_start>$assign_end)
									{
										$assign_start=0;
									}
									break 2;
								}        
							} 
						}
					}
					else if($find_to=='other')
					{
						$assign_start++;
						if($assign_start>$assign_end)
						{
							$assign_start=0;
						}  
						break;					   
					}
				}
				$post_data=array('assign_start'=>$assign_start);
				$this->client_db->where('id',$id);
				$this->client_db->update('justdial_assign_rule_details',$post_data);	
				return $assign_to[$assign_start];
			}
		}
		else{
			return 0;
		}
		
	}
	
}
?>