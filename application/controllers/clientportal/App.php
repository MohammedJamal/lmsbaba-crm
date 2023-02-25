<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {

	private $api_access_token = '';	 
	function __construct()
	{
		parent :: __construct();
		is_admin_logged_in();
		init_admin_element();		
		$this->load->model(array('order_model','user_model','lead_model','opportunity_model','Product_model','Setting_model','history_model','Email_forwarding_setting_model','Opportunity_model','quotation_model','customer_model','pre_define_comment_model'));
		
	}

	public function index()
	{
	} 

	function common_mail_send_modal()
    {        
    	$data=array();
    	$session_data = $this->session->userdata('admin_session_data');
    	$user_id = $session_data['user_id'];
        $data['company']=$this->Setting_model->GetCompanyData();         
        $company_name=$data['company']['name'];
        $data['user_list']=$this->user_model->GetUserListAll('');
        $data['mail_subject'] ="General Follow-up from ".$company_name;        
        $data['quick_reply_comments']=$this->pre_define_comment_model->GetLeadUpdatePreDefineComments($user_id,'LU');
		$data['quick_reply_count']=count($data['quick_reply_comments']);		
        echo $this->load->view('admin/app/common_mail_send_modal_view',$data,true);
    }
	
	function common_mail_send_confirm_ajax()
    {
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$company=$this->Setting_model->GetCompanyData();
			$session_data=$this->session->userdata('admin_session_data');
			$update_by=$this->session->userdata['admin_session_data']['user_id'];
			
			$is_company_brochure_attached_with_pfi=($this->input->post('is_company_brochure_attached_with_pfi'))?'Y':'N';
	        $to_mail_arr=$this->input->post('to_mail');
	        $to_mail=implode(",", $to_mail_arr);
 			$cc_mail_arr=$this->input->post('cc_mail');
			$cc_mail=implode(",", $cc_mail_arr);
 			$reply_email_body=trim($this->input->post('reply_email_body'));
 			
 			
 			//echo $is_company_brochure_attached_with_pfi;
 			//echo'<br>';
 			// print_r($to_mail);
 			//echo'<br>';
 			//print_r($cc_mail_arr);
 			//echo'<br>';
 			//echo $reply_email_body;
 			// die();
			if($reply_email_body=='Type your message here...')
			{
				$data =array (
                "status"=>'fail',
                "msg"=>'Message should not be bkank.',
                "return"=>''
	            );
				header('Content-Type: application/json');
				echo json_encode( $data );
				exit;
				
			}
			
	        if($to_mail)
	        {			        
		        // cc mail assign logic
		        // --------------------
				$mail_attached_arr=array();        	
					
				$po_proforma_info=$this->order_model->get_po_proforma_detail_lowpo_wise($lowp);
				// ============================
				// Mail START				
				$attach_file_path='';
				$template_str = '';
				$e_data = array();	
					
				$attach_filename=[];
				// LEAD ATTACH FILE UPLOAD
				/*$this->load->library('upload', '');
				if($_FILES['attach_file']['name'] != "")
				{
					$config['upload_path'] = "assets/uploads/clients/log/";
					$config['allowed_types'] = "gif|jpg|jpeg|png|pdf|doc|docx|csv|xlsx|xls";
					$config['overwrite'] = FALSE; 
					$config['encrypt_name'] = TRUE;
					$config['max_size'] = '1000000'; //KB
					$this->upload->initialize($config);
					if (!$this->upload->do_upload('attach_file'))
					{
						//echo $this->upload->display_errors();die();
					}
					else
					{
						$file_data = array('upload_data' => $this->upload->data());
						$attach_filename = $file_data['upload_data']['file_name'];
						array_push($mail_attached_arr, $config['upload_path'].$attach_filename);						
					}
				}*/			
				
				$this->load->library('upload', '');
				$upload_path="assets/uploads/clients/log/";
				if($_FILES['attach_file']['tmp_name'])
				{
					$dataInfo = array();
					$files = $_FILES;
					$cpt=count($_FILES['attach_file']['name']);
					
					
					for($i=0; $i<$cpt; $i++)
					{					
						$config = array(
							'upload_path' => $upload_path,
							'allowed_types' => "gif|jpg|jpeg|png|pdf|doc|docx|csv|xlsx|xls",                        
							'max_size' => "2048000",
							'overwrite'=>TRUE,
							);
						$this->upload->initialize($config);
						$_FILES['attach_file']['name']= $files['attach_file']['name'][$i];
						$_FILES['attach_file']['type']= $files['attach_file']['type'][$i];
						$_FILES['attach_file']['tmp_name']= $files['attach_file']['tmp_name'][$i];
						$_FILES['attach_file']['error']= $files['attach_file']['error'][$i];
						$_FILES['attach_file']['size']= $files['attach_file']['size'][$i];
						// $target_dir = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/lead/";
						// $target_file = $target_dir . basename($_FILES['lead_attach_file']['name']); 

						if (!$this->upload->do_upload('attach_file'))
						{
							// echo $this->upload->display_errors();die();
						}
						else
						{
							$dataInfo = $this->upload->data();
							$filename=$dataInfo['file_name']; 						
							$attach_filename[]=$filename;
							// array_push($mail_attached_arr,"assets/uploads/clients/log/".$filename);
							$mail_attached_arr[]=$upload_path.$filename;							
						}
						         	
					}
				}
				// end
				// ------------------------------
				// company brochure attachment
				if($is_company_brochure_attached_with_pfi=='Y')
				{
					if(isset($company['brochure_file']))
					{
						$company_brochure="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/company/brochure/".$company['brochure_file'];
						// array_push($mail_attached_arr, $company_brochure);
						$mail_attached_arr[]=$company_brochure;
					}
				}
				
				// company brochure attachment
				// ------------------------------
				$e_data='';
				// $template_str = $this->load->view('admin/email_template/template/quotation_sent_view', $e_data, true);
				$template_str=$reply_email_body;
				// mail start
				$this->load->library('mail_lib');
				$mail_data = array();
				$mail_data['from_mail']     = $company['email1'];
				$mail_data['from_name']     = $company['name'];
				$mail_data['to_mail']       = $to_mail;		        
				$mail_data['cc_mail']       = $cc_mail;
				$pfi_mail_subject=($this->input->post('mail_subject'))?$this->input->post('mail_subject'):"General Followup From ".$company_name;
				$mail_data['subject']       = $pfi_mail_subject;
				$mail_data['message']       = $template_str;
				$mail_data['attach']        = $mail_attached_arr;	
				// print_r($mail_data);die();			
				$mail_return = $this->mail_lib->send_mail($mail_data);
				// Mail
				// -----------------------------------------------------
				
				
				// -----------------------------------------------------
				// REMOVE EXISTING FILE				
				if(count($attach_filename))
				{
					for($j=0;$j<count($attach_filename);$j++)
					{						
						if(file_exists($config['upload_path'].$attach_filename[$j])){
							@unlink($config['upload_path'].$attach_filename[$j]);
						}else{}
					}
				}
				// REMOVE EXISTING FILE
				// -----------------------------------------------------
		
		
		        $status='success';
				$msg="Mail has been sent successfully";
				
				// create history
				/*
				$commnt="Proforma invoice(".$po_register_info->pro_forma_no.") sent to client by mail";
				$ip=$_SERVER['REMOTE_ADDR'];
				$date=date("Y-m-d h:i:s");	
				$update_by=$this->session->userdata['admin_session_data']['user_id'];
				$comment_title=PRO_FORMA_INV_SENT_TO_CLIENT;
				$historydata=array(
				'title'=>$comment_title,
				'lead_id'=>$lead_id,
				'comment'=>$commnt,
				'sent_mail_quotation_to_client_from_mail'=>$session_data['email'],
				'sent_mail_quotation_to_client_from_name'=>$session_data['name'],
				'mail_subject_of_sent_quotation_to_client'=>$pfi_mail_subject,
				'create_date'=>$date,
				'user_id'=>$update_by,
				'ip_address'=>$ip
				);
				$this->history_model->CreateHistory($historydata);*/		
				
	        }
	        else
	        {
	        	$status='fail';
	        	$msg="Please select any mail to send.";
	        }
	        
	        
	        $data =array (
                "status"=>$status,
                "msg"=>$msg,
                "return"=>''
	            );
	        header('Content-Type: application/json');
    		echo json_encode( $data );
    		exit;
    	}
        
    }

	public function change_password_ajax($id='')
    {
    	//is_admin_session_data();
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			if($this->password_validate_form_data() == TRUE)
	        {         

	            $err_msg="";
				//$lms_url=$this->session->userdata['admin_session_data']['lms_url'];
				//$company_name=$this->session->userdata['admin_session_data']['company_name'];
				$id=$this->session->userdata['admin_session_data']['user_id'];
				$new_password=trim($this->input->post('emp_password'));			
				$user_data_pass=array(
	             				'password'=>md5($new_password),
				 				'modify_date'=>date('Y-m-d H:i:s')
				 				);
				$this->user_model->UpdateAdmin($user_data_pass,$id);				
				// Mail Script
				// $message=reg_mail_user(base_url(),$name,$email,$new_password);		
				// $this->email->from('lmsbaba@maxbridgesolution.com', 'LMS Baba Webmaster');
				// $this->email->to($email);
				// $this->email->subject('LMS Baba Account Access');
				// $this->email->message($message);	
				// $this->email->send(); 
				// End	

	            $msg="Password successfully updated.";
	            $status_str='success';
	        }
	        else
	        {
	            $msg = validation_errors(); // 'duplicate';	           
	            $status_str='error';
	        }


					
	        $result["status"] = $status_str;
	        $result['msg']=$msg;
	        echo json_encode($result);
	        exit(0);  
			
		}	
		
    }

	function password_validate_form_data()
    { 
        $this->load->library('form_validation');
        $this->form_validation->set_rules('emp_password', 'password', 'required|min_length[6]|max_length[12]');
        $this->form_validation->set_rules('emp_confirm_password', 'password confirmation', 'required|matches[emp_password]');       

        if ($this->form_validation->run() == TRUE)
        {
          return TRUE;
        }
        else
        {
          return FALSE;
        }
    }

	public function change_username_ajax($id='')
    {
    	//is_admin_session_data();
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$id=trim($this->input->post('emp_id'));
			$emp_existing_username=trim($this->input->post('emp_existing_username'));
			$emp_username=trim($this->input->post('emp_username'));

			if($id=='')
			{
				$result["status"] ='error' ;
				$result['msg']='Employee id is missing.';
				echo json_encode($result);
				exit(0);
			}

			if($emp_username=='')
			{
				$result["status"] ='error' ;
				$result['msg']='Username is missing.';
				echo json_encode($result);
				exit(0);
			}

			if($emp_username==$emp_existing_username)
			{
				$result["status"] ='error' ;
				$result['msg']='Existing Username and new username same.';
				echo json_encode($result);
				exit(0);
			}

			$duplicate_check=$this->user_model->duplicate_username_chk($id,$emp_username);
			if($duplicate_check>0)
			{
				$result["status"] ='error' ;
				$result['msg']='User name already taken. Please try different User Name.';
				echo json_encode($result);
				exit(0);
			}		
						
			$user_data_pass=array(
							'username'=>$emp_username,
							'modify_date'=>date('Y-m-d H:i:s')
							);
			$this->user_model->UpdateAdmin($user_data_pass,$id);			  
			$result["status"] ='success' ;
			$result['msg']="Username successfully updated.";
			echo json_encode($result);
			exit(0);

		}	
		
    }

	public function download($file_name='',$file_path='')
	{			
		if($file_name!='' && $file_path!=''){
			$file_path=base64_decode($file_path);
			$this->load->helper(array('download'));			
			$pth    =   file_get_contents($file_path);
			force_download($file_name, $pth); 
			exit;
		}		
	}
}
