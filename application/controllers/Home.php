<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends CI_Controller {
	private $api_access_token = '';	
	public function __construct()
	{	
		parent::__construct();
		$this->load->model(array('Client_model'));
	}
	public function index()
	{
		$data=array();
		$ip = $this->input->ip_address();
		$data['ip_wise_country_code']=ip_info("$ip", "Country Code");
		$domain_name=$this->uri->segment(1);
		if($domain_name!='' && $domain_name!='home' && $domain_name!='terms_of_services' && $domain_name!='privacy_policy')
		{			
			//$get_client_id=$this->Client_model->get_id_from_domain_name($domain_name);
			$get_token=$this->Client_model->get_token_from_domain_name($domain_name);
			if($get_token!=FALSE){
				redirect("clientportal/login/$get_token");
			}
			else{
				$this->load->view('home_view',$data);
			}						
		}		
		else
		{
			if($domain_name=='terms_of_services')
			{
				$this->load->view('terms_of_services_view',$data);
			}
			else if($domain_name=='privacy_policy')
			{
				$this->load->view('privacy_policy_view',$data);
			}
			else
			{
				$this->load->view('home_view',$data);
			}			
		}		
	}	

	public function book_demo_add_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$book_demo_name=$this->input->post('book_demo_name');
			$book_demo_email=$this->input->post('book_demo_email');
			$book_demo_mobile=$this->input->post('book_demo_mobile');
			$book_demo_company_name=$this->input->post('book_demo_company_name');
			$book_demo_date_tmp=$this->input->post('book_demo_date');			
			$book_demo_date = date('d-M-Y', strtotime($book_demo_date_tmp));
			$book_demo_time=$this->input->post('book_demo_time');


			$data_post=array(
						'name'=>$book_demo_name,
						'email'=>$book_demo_email,
						'mobile'=>$book_demo_mobile,
						'company_name'=>$book_demo_company_name,
						'demo_datetime'=>$book_demo_date.' '.$book_demo_time,
						'comment'=>'',
						'created_at'=>date('Y-m-d H:i:s')
						);
			$this->Client_model->insertBookDemo($data_post);


			// ==============================================
			$contact_info='';
			$contact_info .='<p><b>Name: </b>'.$book_demo_name.'</p>';
			$contact_info .='<p><b>Email: </b>'.$book_demo_email.'</p>';
			$contact_info .='<p><b>Mobile: </b>'.$book_demo_mobile.'</p>';
			$contact_info .='<p><b>Company Name: </b>'.$book_demo_company_name.'</p>';
			if($book_demo_date){
				$contact_info .='<p><b>Demo datetime: </b>'.$book_demo_date.' '.$book_demo_time.'</p>';
			}			
			// if($book_demo_comment)
			// {
			// 	$contact_info .='<p><b>Comment: </b>'.$book_demo_comment.'</p>';
			// }
			
			$this->load->library('mail_lib');
			$m_d = array();
			$m_d['from_mail']     = 'info@lmsbaba.com';
			$m_d['from_name']     = 'LMSBABA';			
			$m_d['to_mail']       = 'shashi.narain@gmail.com';
					
			$m_d['subject']       = 'A request has been come for lmsbaba demo from '.$book_demo_name;
			$m_d['message']       = 'Hello Admin,<br> A new request has been come for lmsbaba demo. See below in details: '.$contact_info;
			$m_d['attach']        = array();
			$mail_return=$this->mail_lib->send_mail_default($m_d);

			if($mail_return==true)
			{
				$msg="Thank you. Your demo request successfully sent to our team. We will get back to you soon.";
				$status='success';
			}
			else
			{
				$msg="Mail not sent. Please try again leter.";
				$status='fail';
			}
			// ==============================================
			$result["status"] = $status;
	        $result['msg']=$msg;
	        echo json_encode($result);
	        exit(0);
		}
	}

	public function contact_us_add_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$contact_us_name=$this->input->post('contact_us_name');
			$contact_us_company=$this->input->post('contact_us_company');
			$contact_us_mobile=$this->input->post('contact_us_mobile');
			$contact_us_email=$this->input->post('contact_us_email');
			$contact_us_comment=$this->input->post('contact_us_comment');

			if($contact_us_name!='' && $contact_us_mobile!='' && $contact_us_email!='' && $contact_us_comment!='')
			{
				$data_post=array(
						'name'=>$contact_us_name,
						'company'=>$contact_us_company,
						'email'=>$contact_us_email,
						'mobile'=>$contact_us_mobile,
						'comment'=>$contact_us_comment,	
						'created_at'=>date('Y-m-d H:i:s')
						);
				$this->Client_model->insertcontactUs($data_post);
			}

			



			$contact_info='';
			$contact_info .='<p><b>Name: </b>'.$contact_us_name.'</p>';
			$contact_info .='<p><b>Company: </b>'.$contact_us_company.'</p>';
			$contact_info .='<p><b>Email: </b>'.$contact_us_email.'</p>';
			$contact_info .='<p><b>Mobile: </b>'.$contact_us_mobile.'</p>';
			$contact_info .='<p><b>Comment: </b>'.$contact_us_comment.'</p>';
			
			$this->load->library('mail_lib');
			$m_d = array();
			$m_d['from_mail']     = 'info@lmsbaba.com';
			$m_d['from_name']     = 'LMSBABA';			
			$m_d['to_mail']       = 'shashi.narain@gmail.com';
			
			$m_d['subject']       = 'A new contact mail has been come from '.$contact_us_name;
			$m_d['message']       = 'Hello Admin,<br> A new contact has been come from lmsbaba.com. See below information: '.$contact_info;
			$m_d['attach']        = array();
			$mail_return=$this->mail_lib->send_mail_default($m_d);

			if($mail_return==true)
			{
				$msg="Thank you. Your mail successfully sent to our team. We will get back to you soon.";
				$status='success';
			}
			else
			{
				$msg="Mail not sent. Please try again leter.";
				$status='fail';
			}
			$result["status"] = $status;
	        $result['msg']=$msg;
	        echo json_encode($result);
	        exit(0);
		}
	}

	public function app_download()
	{			
		$this->load->helper(array('download'));	
		$file_name = get_latest_app();
		$pth    =   file_get_contents("assets/".$file_name);
		force_download($file_name, $pth); 
		exit;
	}	
}
