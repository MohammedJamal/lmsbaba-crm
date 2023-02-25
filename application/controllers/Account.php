<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Account extends CI_Controller {
	private $api_access_token = '';	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model(array('Client_model','user_model','Setting_model'));
	}
	
	public function forget_password_ajax()
	{   
		
		$api_access_token=$this->input->post('client_id');	
	    $emp_id=$this->input->post('emp_id');	
		$client_id = $this->Client_model->get_id_from_token($api_access_token);
	    $client_info=$this->Client_model->get_details($client_id);
	    $row=$this->user_model->get_user_details($emp_id,$client_info);	 
	    
	    if(count($row))
	    {
	        $email=$row['email'];
	        $emp_name=$row['name'];
	        if($email)
	        {

	            $token=md5($email).'~'.base64_encode($client_id);
	            $this->load->library('mail_lib');
	            $company_data=$this->Setting_model->GetCompanyData($client_info);
	            // EMAIL CONTENT
	            $email_body='';
	            $email_body .='We have received a request to reset the password of Employee ID: '.$emp_id.'.<br>';
	            $email_body .='<a href="'.base_url().'account/reset_password/?key='.$token.'">Click Here to Reset Your Password</a>';

	            $e_data=array();     
	            $e_data['client_id']=$client_id;  
	            $e_data['client_info']=$client_info;   
	            $e_data['company']=$company_data;
	            $e_data['email_subject']='Reset Your Password';
	            $e_data['email_to']=$emp_name;
	            $e_data['email_body']=$email_body;
	            $template_str = $this->load->view('admin/email_template/template/reset_password_view', $e_data, true);     
	            $mail_data = array();
	            $mail_data['from_mail']     = $company_data['email1'];
	            $mail_data['from_name']     = $company_data['name'];
	            $mail_data['to_mail']       = $email;        
	            $mail_data['cc_mail']       = '';               
	            $mail_data['subject']       ='Password Reset Request';
	            $mail_data['message']       = $template_str;
	            $mail_data['attach']        = array();
	            $is_mail_sent=$this->mail_lib->send_mail($mail_data);
	            if($is_mail_sent){
	                $status='1';
	                $post_data=array('forget_key'=>$token);
	                $this->user_model->UpdateAdmin($post_data,$emp_id,$client_info);
	            }
	            else{
	                $status='3';
	            }
	        }
	        else
	        {
	            $status='2';
	        }
	        
	    }   
	    else
	    {
	        $status='2';
	    }    
	    	
	    echo $status;
		// $result['status'] = $status;		
		// echo json_encode($result);
        exit(0);
	    
	    
	}

	public function reset_password()
	{
		$key_str=$this->input->get('key');
		$key_arr=explode('~',$key_str);			
		$client_id=base64_decode($key_arr[1]);
		$data['key_str']=$key_str;
		if($key_str!='' && $client_id!='')
		{	
			$client_info=$this->Client_model->get_details($client_id);
			$data['client_info']=$client_info;
			$result=$this->user_model->KeyExist($key_str,$client_info);		
			if($result)
			{
				$data['client_id']=$client_id;
				$data['user_data']=$result;						
			}
		}
		else
		{	
			redirect(base_url());
		}				
		$this->load->view('admin/forget_password',$data);
	}

	public function update_password()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$key_str=$this->input->post('key');
			$client_id=$this->input->post('client_id');
			$client_info=$this->Client_model->get_details($client_id);
			$password=trim($this->input->post('password'));
			$confirm_password=trim($this->input->post('confirm_password'));	
			if($password!='' && $confirm_password!='')
			{
				if($password==$confirm_password)
				{
					$row=$this->user_model->KeyExist($key_str,$client_info);
					$id=$row['id'];
					$user_data=array('password'=>md5($password),'forget_key'=>'');
					$result=$this->user_model->UpdateAdmin($user_data,$id,$client_info);
					if($result)
					{
						$msg='Password successfully reset.';
						$this->session->set_flashdata('success_msg', $msg);
						
					}
					else
					{
						$msg='Password fail to reset.';
						$this->session->set_flashdata('error_msg', $msg);
					}						
				}
				else
				{
					$msg='Password and confirm password are not matching.';
					$this->session->set_flashdata('error_msg', $msg);
				}
					
			}
			else
			{
				$msg='All the fields are required.';
				$this->session->set_flashdata('error_msg', $msg);
			}
			redirect(base_url().'account/reset_password/?key='.$key_str);					
		}	
	}
}
