<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	class Mail_lib 
	{
		var $obj;	

		function Mail_lib()
		{
			$this->obj =& get_instance();
		}
		
		
		public function send_mail($data=array(),$client_info=array())
		{
			$get_smtp=get_smtp($client_info);

			if($get_smtp['smtp_type']=='1')
			{
				// $config = Array(       
				// 				'protocol' => 'smtp',
				// 				'smtp_host' => 'smtp.hostinger.in',
				// 				'smtp_port' => 587,
				// 				'smtp_user' => $get_smtp['username'],
				// 				'smtp_pass' => $get_smtp['password'],
				// 				'smtp_timeout' => '4',
				// 				'mailtype'  => 'html',
				// 				'charset'   => 'iso-8859-1'
				// 				);
				// $from_mail_tmp=$get_smtp['username'];
			}
			else if($get_smtp['smtp_type']=='2')
			{
				$config = Array(       
							'protocol' => 'smtp',
							'smtp_host' => 'ssl://smtp.gmail.com',
							'smtp_port' => 465,
							'smtp_user' => $get_smtp['username'],
							'smtp_pass' => $get_smtp['password'],
							'smtp_timeout' => '4',
							'mailtype'  => 'html',
							'charset'   => 'iso-8859-1'
							);
				$from_mail_tmp=$get_smtp['username'];
			}
			else if($get_smtp['smtp_type']=='3')
			{
				$config = Array(       
							'protocol' => 'smtp',
							'smtp_host' => $get_smtp['host'],
							'smtp_port' => $get_smtp['port'],
							'smtp_user' => $get_smtp['username'],
							'smtp_pass' => $get_smtp['password'],
							'smtp_timeout' => '4',
							'mailtype'  => 'html',
							'charset'   => 'iso-8859-1'
							);
				$from_mail_tmp=$get_smtp['username'];
			}	
			else
			{
				// $config = Array(       
				// 				'protocol' => 'smtp',
				// 				'smtp_host' => 'smtp.hostinger.in',
				// 				'smtp_port' => 587,
				// 				'smtp_user' => 'systemadmin@lmsbaba.com',
				// 				'smtp_pass' => '!K5[!S1k',
				// 				'smtp_timeout' => '4',
				// 				'mailtype'  => 'html',
				// 				'charset'   => 'iso-8859-1'
				// 				);
				// $from_mail_tmp='systemadmin@lmsbaba.com';
			}
			
			if($get_smtp['smtp_type']=='2' || $get_smtp['smtp_type']=='3')
			{
				$this->obj->load->library('email',$config);			
				$from_name_tmp=trim($data['from_name']);
				//$from_mail_tmp = trim($data['from_mail']);
				$reply_to_mail=trim($data['from_mail']);
				$from_name	= $data['from_name'];
				$to_mail    = trim($data['to_mail']);
				$cc_mail    = (isset($data['cc_mail']))?trim($data['cc_mail']):'';
				$bcc_mail   = (isset($data['bcc_mail']))?trim($data['bcc_mail']):'';
				$subject    = trim($data['subject']);
				$message    = trim($data['message']);
				$attach_file = $data['attach'];
				//$attach_pdf = $data['attach_pdf'];			

				$this->obj->email->initialize($config);
				$this->obj->email->set_newline("\r\n");

				$this->obj->email->clear(TRUE);
				// $this->obj->email->from($from_mail, $from_name);
				$this->obj->email->from($from_mail_tmp, $from_name_tmp);
				$this->obj->email->to(strtolower($to_mail));
				if($cc_mail)
				{
					$this->obj->email->cc(strtolower($cc_mail));
				}
				if($bcc_mail)
				{
					$this->obj->email->bcc(strtolower($bcc_mail));
				}
				
				$this->obj->email->subject($subject); 
				$this->obj->email->message($message); 
				// $this->obj->email->set_header('From', $from_name.'<'.$from_mail.'>');
				$this->obj->email->set_header('From', $from_name_tmp.'<'.$from_mail_tmp.'>');
				$this->obj->email->reply_to($reply_to_mail);
				/* attachment */
				if(count($attach_file))
				{
					for($i=0;$i<count($attach_file);$i++)
					{
						$this->obj->email->attach($attach_file[$i]);
					}
				}
				/* end */
				
				//Send mail 
				if($this->obj->email->send())
				{ 
					return true;
					//echo"email sent","Email sent successfully."; 
				}
				else
				{
					return false;
					//echo "email Not sent","Error in sending Email."; 
				}
			}
			
		}

		public function send_mail_default($data=array(),$client_info=array())
		{
			

			// $config = Array(       
			// 				'protocol' => 'smtp',
			// 				'smtp_host' => 'smtp.hostinger.in',
			// 				'smtp_port' => 587,
			// 				'smtp_user' => 'systemadmin@lmsbaba.com',
			// 				'smtp_pass' => '!K5[!S1k',
			// 				'smtp_timeout' => '4',
			// 				'mailtype'  => 'html',
			// 				'charset'   => 'iso-8859-1'
			// 				);
			$config = Array(       
				'protocol' => 'smtp',
				'smtp_host' => 'ssl://smtp.gmail.com',
				'smtp_port' => 465,
				'smtp_user' => 'shashi.narain1@gmail.com',
				'smtp_pass' => 'jflxzoaaewsymqzu',
				'smtp_timeout' => '4',
				'mailtype'  => 'html',
				'charset'   => 'iso-8859-1'
				);
			$from_mail_tmp='shashi.narain1@gmail.com';
			
			$this->obj->load->library('email',$config);			
			$from_name_tmp=trim($data['from_name']);
			// $from_mail_tmp='systemadmin@lmsbaba.com';
			//$from_mail_tmp	= trim($data['from_mail']);
			$from_name	= $data['from_name'];
			$to_mail    = trim($data['to_mail']);
			$cc_mail    = (isset($data['cc_mail']))?trim($data['cc_mail']):'';
			$bcc_mail   = (isset($data['bcc_mail']))?trim($data['bcc_mail']):'';
			$subject    = trim($data['subject']);
			$message    = trim($data['message']);
			$attach_file = $data['attach'];
			//$attach_pdf = $data['attach_pdf'];			

			$this->obj->email->initialize($config);
    		$this->obj->email->set_newline("\r\n");

    		$this->obj->email->clear(TRUE);
			// $this->obj->email->from($from_mail, $from_name);
			$this->obj->email->from($from_mail_tmp, $from_name_tmp);
			$this->obj->email->to(strtolower($to_mail));
			if($cc_mail)
			{
				$this->obj->email->cc(strtolower($cc_mail));
			}
			if($bcc_mail)
			{
				$this->obj->email->bcc(strtolower($bcc_mail));
			}
			
			$this->obj->email->subject($subject); 
			$this->obj->email->message($message); 
			// $this->obj->email->set_header('From', $from_name.'<'.$from_mail.'>');
			$this->obj->email->set_header('From', $from_name_tmp.'<'.$from_mail_tmp.'>');
			$this->obj->email->reply_to($from_mail);
			/* attachment */
			if(count($attach_file))
			{
				for($i=0;$i<count($attach_file);$i++)
				{
					$this->obj->email->attach($attach_file[$i]);
				}
			}
			/* end */
			
			 //Send mail 
			if($this->obj->email->send())
			{ 
				return true;
				//echo"email sent","Email sent successfully."; 
			}
			else
			{
				return false;
				//echo "email Not sent","Error in sending Email."; 
			}
		}

		public function send_mail_cronjobs($data=array())
		{
			$get_smtp=$data['smtp_data'];
			if($get_smtp['smtp_type']=='1')
			{
				
			}
			else if($get_smtp['smtp_type']=='2')
			{
				$config = Array(       
							'protocol' => 'smtp',
							'smtp_host' => 'ssl://smtp.gmail.com',
							'smtp_port' => 465,
							'smtp_user' => $get_smtp['username'],
							'smtp_pass' => $get_smtp['password'],
							'smtp_timeout' => '4',
							'mailtype'  => 'html',
							'charset'   => 'iso-8859-1'
							);
				$from_mail_tmp=$get_smtp['username'];
			}
			else if($get_smtp['smtp_type']=='3')
			{
				$config = Array(       
							'protocol' => 'smtp',
							'smtp_host' => $get_smtp['host'],
							'smtp_port' => $get_smtp['port'],
							'smtp_user' => $get_smtp['username'],
							'smtp_pass' => $get_smtp['password'],
							'smtp_timeout' => '4',
							'mailtype'  => 'html',
							'charset'   => 'iso-8859-1'
							);
				$from_mail_tmp=$get_smtp['username'];
			}	
			else
			{
				
			}
			
			if($get_smtp['smtp_type']=='2' || $get_smtp['smtp_type']=='3')
			{
				$this->obj->load->library('email',$config);			
				$from_name_tmp=trim($data['from_name']);				
				$reply_to_mail=trim($data['from_mail']);
				$from_name	= $data['from_name'];
				$to_mail    = trim($data['to_mail']);
				$cc_mail    = (isset($data['cc_mail']))?trim($data['cc_mail']):'';
				$bcc_mail   = (isset($data['bcc_mail']))?trim($data['bcc_mail']):'';
				$subject    = trim($data['subject']);
				$message    = trim($data['message']);
				$attach_file = trim($data['attach']);	
				$this->obj->email->initialize($config);
				$this->obj->email->set_newline("\r\n");
				$this->obj->email->clear(TRUE);				
				$this->obj->email->from($from_mail_tmp, $from_name_tmp);
				$this->obj->email->to(strtolower($to_mail));
				if($cc_mail)
				{
					$this->obj->email->cc(strtolower($cc_mail));
				}
				if($bcc_mail)
				{
					$this->obj->email->bcc(strtolower($bcc_mail));
				}				
				$this->obj->email->subject($subject); 
				$this->obj->email->message($message); 				
				$this->obj->email->set_header('From', $from_name_tmp.'<'.$from_mail_tmp.'>');
				$this->obj->email->reply_to($reply_to_mail);
				/* attachment */
				if($attach_file)
				{
					$attach_file=explode(",",$attach_file);
					for($i=0;$i<count($attach_file);$i++)
					{
						$this->obj->email->attach($attach_file[$i]);
					}
				}
				/* end */				
				//Send mail 
				if($this->obj->email->send()){ 
					return true;
					//echo"email sent","Email sent successfully."; 
				}
				else{
					return false;
					//echo "email Not sent","Error in sending Email."; 
				}
			}
			
		}
	}

?>