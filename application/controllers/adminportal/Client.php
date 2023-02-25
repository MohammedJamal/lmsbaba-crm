<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {
	function __construct()
	{
		parent :: __construct();
		is_adminportal_logged_in();
		chk_access_menu_permission(1);
		init_adminportal_element();   
		$this->load->model(array("Client_model","User_model","Adminportal_model"));
	}

	public function index()
	{	
		$data = array(); 	
		$data['account_type']=$this->Client_model->get_account_type();

		$data['topmenu_list']=get_permission_wise_menu_list();
		
		$this->load->view('adminmaster/client_view',$data);
	}

	public function get_client_liat_ajax()
	{
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$start = $this->input->post('page'); 
			//$start = 2; 
			$this->load->library('pagination');
			$limit=30;
			$config = array();
			$argument = array();

			$account_type_id=$this->input->post('account_type');
			$is_active=$this->input->post('is_active');
			$argument['account_type_id']=$account_type_id;
			$argument['is_active']=$is_active;

			$session_data=$this->session->userdata('adminportal_session_data');
			$user_id=$session_data['user_id'];
   			$user_type=$session_data['user_type'];
			$tmp_u_ids=$this->Adminportal_model->get_self_and_under_alluser_ids($user_id);
			$tmp_u_ids_str=implode(",", $tmp_u_ids);
			$argument['user_type']=$user_type;
			$argument['assigned_user']=$tmp_u_ids_str;

			//$config['base_url'] =base_url('pages_ajax/show');
			$config['base_url'] ='#';
			$config['total_rows'] = $this->Client_model->get_client_list_count($argument);	
			//$config['total_rows'] =150;    
			$config['per_page'] = $limit;
			$config['uri_segment']=4;
			$config['num_links'] = 5;
			$config['use_page_numbers'] = TRUE;
			$config['attributes'] = array('class' => 'client_list_page');
			$config["full_tag_open"] = '<ul class="pagination">';
			$config["full_tag_close"] = '</ul>';	
			$config["first_link"] = "&laquo;";
			$config["first_tag_open"] = "<li>";
			$config["first_tag_close"] = "</li>";
			$config["last_link"] = "&raquo;";
			$config["last_tag_open"] = "<li>";
			$config["last_tag_close"] = "</li>";
			$config['next_link'] = '&gt;';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '<li>';
			$config['prev_link'] = '&lt;';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '<li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';

			$start=empty($start)?0:($start-1)*$limit;
			$argument['limit']=$limit;
			$argument['start']=$start;	
			
			$this->pagination->initialize($config);
			$page_link = '';
			$page_link = $this->pagination->create_links();

			// PAGINATION COUNT INFO SHOW: START
			$tmp_start=($start+1);		
			$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
			$page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
			// PAGINATION COUNT INFO SHOW: END			
			
			$list['rows']=$this->Client_model->get_client_list($argument);
			$list['page']=$page_link;
			$list['page_record_count_info']=$page_record_count_info;
			$list['sl_start']=$tmp_start;
			$list['page_number']=$this->input->post('page');

			$list['user_list']=$this->Adminportal_model->get_manager_list();
			$html = $this->load->view('adminmaster/client_list_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;
	        echo json_encode($result);
	        exit(0);
		}
	}

	public function get_client_wise_comment_log_list_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$cid=$this->input->post('cid');
			$list['comment_list']=$this->Client_model->get_comment_list($cid);	
			$html = $this->load->view('adminmaster/client_wise_comment_log_list_view_ajax.php',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;	  

	        echo json_encode($result);
	        exit(0);
		}
	}

	public function get_client_wise_user_list_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			
			$client_id=$this->input->post('cid');
			$arg=array();
			$arg['client_id']=$client_id;
			$arg['cronjobs_action']='';
			// print_r($arg); die();
			$client_db_info_list=$this->Client_model->get_all($arg);
			$client_info=$client_db_info_list[0];
			$list['rows']=$this->User_model->get_client_wise_user_list_rows($client_info);
			//$list['package_info']=$this->Client_model->get_client_package_info($client_info);
			$html = $this->load->view('adminmaster/client_wise_user_list_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;	  

	        echo json_encode($result);
	        exit(0);
		}
	}

	public function update_expiry_date_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			// $arg=array();
			$cid=$this->input->post('cid');
			$expire_date=$this->input->post('expire_date');
			if($cid!='' && $expire_date!='')
			{
				$arg=array();
				$arg['client_id']=$cid;
				$arg['cronjobs_action']='';
				$client_db_info_list=$this->Client_model->get_all($arg);
				$client_info=$client_db_info_list[0];
				// print_r($client_info); die();
				// $client_info=$this->Client_model->get_details($cid);
				$expire_date_tmp=date_display_format_to_db_format($expire_date);
				$edit_id=31;
				$post_data=array('expire_date'=>$expire_date_tmp.' 00:00:00');
				$return=$this->Client_model->update_client_package($post_data,$edit_id,$client_info);				
				if($return==true)
				{
					$status_str='success';
				}
				else
				{
					$status_str='fail';
				}				  
				$result["status"] = $status_str;	  
				echo json_encode($result);
				exit(0);
			}
			
		}
	}

	public function get_client_edit_view_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$list=array();
			$cid=$this->input->post('cid');			
			if($cid!='')
			{	
				$list['client_row']=$this->Client_model->get_details($cid);
				$list['account_type']=$this->Client_model->get_account_type();	
				$list['client_id']=$cid;
				$html=$this->load->view('adminmaster/get_client_edit_view_popup_ajax',$list,TRUE);					
				$status_str='success';			  
				$result["status"] = $status_str;	
				$result["html"] = $html;	  
				echo json_encode($result);
				exit(0);
			}			
		}
	}

	public function save_client_edit_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$cid=$this->input->post('client_id');
			$account_type_id=$this->input->post('account_type_id');
			$c_name=$this->input->post('c_name');
			$company_name=$this->input->post('company_name');
			$contact_person=$this->input->post('contact_person');
			$c_is_active=$this->input->post('c_is_active');

			$user_id=$this->session->userdata['adminportal_session_data']['user_id'];
			$ip_address=$_SERVER['REMOTE_ADDR'];
			$today=date("Y-m-d H:i:s");
			
			if($cid!='' && $account_type_id!='' && $c_name!='')
			{
				$post_data=array(
					'account_type_id'=>$account_type_id,
					'name'=>$c_name,
					'company_name'=>$company_name,
					'contact_person'=>$contact_person,
					'is_active'=>$c_is_active,
					'updated_at'=>$today
					);
				$return=$this->Client_model->update($post_data,$cid);				
				if($return==true)
				{
					$status_str='success';
					if($c_is_active=='N'){
						$activity_text="Downloads call auto create when client status change";
						$subsql=" AND client_id='".$cid."' AND service_call_type_id='7' ";
						$calldetails=$this->Adminportal_model->get_active_service_call_details($subsql);
						if(!count($calldetails)>0){
							$insert_data=array(
								'client_id'=>$cid,
								'service_call_type_id'=>7,
								'call_by_user_id'=>$user_id,
								'scheduled_call_datetime'=>$today,
								'comment'=>$activity_text,
								'created_at'=>$today
							);
							$this->Adminportal_model->insert_call_update($insert_data);

							$activity_type='';
							$activity_title="Service Calls (Downloads Calls)";
							$post_data=array(
									'user_id'=>$user_id,
									'client_id'=>$cid,
									'activity_type'=>$activity_type,
									'client_update_type_id'=>2,
									'activity_title'=>$activity_title,
									'activity_text'=>$activity_text,
									'ip_address'=>$ip_address,
									'followup_date'=>$today,
									'created_at'=>$today
									);
							$return=$this->Adminportal_model->insert_comment($post_data);



						}
					} else {
						$activity_text="Downloads call auto completed when client status change";
						$subsql=" AND client_id='".$cid."' AND service_call_type_id='7' ";
						if($calldetails=$this->Adminportal_model->get_active_service_call_details($subsql)){

							$service_call_id=$calldetails->id;
							$update_data=array(
								'call_by_user_id'=>$user_id,
								'actual_call_done_datetime'=>$today
							);
							$this->Adminportal_model->service_call_update($update_data,$service_call_id);

							$activity_type='';
							$activity_title="Service Calls (Downloads Calls)";
							$post_data=array(
									'user_id'=>$user_id,
									'client_id'=>$cid,
									'activity_type'=>$activity_type,
									'client_update_type_id'=>2,
									'activity_title'=>$activity_title,
									'activity_text'=>$activity_text,
									'ip_address'=>$ip_address,
									'followup_date'=>$today,
									'created_at'=>$today
									);
							$return=$this->Adminportal_model->insert_comment($post_data);
						}
					}

				}
				else
				{
					$status_str='fail';
				}				  
				$result["status"] = $status_str;	  
				echo json_encode($result);
				exit(0);
			}			
		}
	}

	public function update_package_end_date_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			// $arg=array();
			$cid=$this->input->post('cid');
			$package_end_date=$this->input->post('package_end_date');
			if($cid!='' && $package_end_date!='')
			{
				$arg=array();
				$arg['client_id']=$cid;
				$arg['cronjobs_action']='';
				$client_db_info_list=$this->Client_model->get_all($arg);
				$client_info=$client_db_info_list[0];
				// print_r($client_info); die();
				// $client_info=$this->Client_model->get_details($cid);
				$package_end_date_tmp=date_display_format_to_db_format($package_end_date);
				$edit_id=31;
				$post_data=array('package_end_date'=>$package_end_date_tmp);
				$return=$this->Client_model->update_client_package($post_data,$edit_id,$client_info);				
				if($return==true)
				{
					$status_str='success';
				}
				else
				{
					$status_str='fail';
				}				  
				$result["status"] = $status_str;	  
				echo json_encode($result);
				exit(0);
			}
			
		}
	}

	public function update_package_price_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			// $arg=array();
			$cid=$this->input->post('cid');
			$package_price=$this->input->post('package_price');
			if($cid!='' && $package_price!='')
			{
				$arg=array();
				$arg['client_id']=$cid;
				$arg['cronjobs_action']='';
				$client_db_info_list=$this->Client_model->get_all($arg);
				$client_info=$client_db_info_list[0];
				$edit_id=31;
				$post_data=array('package_price'=>$package_price);
				$return=$this->Client_model->update_client_package($post_data,$edit_id,$client_info);				
				if($return==true)
				{
					$status_str='success';
				}
				else
				{
					$status_str='fail';
				}				  
				$result["status"] = $status_str;	  
				echo json_encode($result);
				exit(0);
			}
			
		}
	}

	
	public function change_client_assigned_multiple_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$assigned_to_user_id=$this->input->post('assigne_user_id');
			$client_id_str=$this->input->post('client_id_str');
			$client_id_arr=array_unique(explode(',', $client_id_str));

			$admin_session_data = $this->session->userdata('adminportal_session_data'); 
			$assigned_by_user_id = $admin_session_data['user_id'];

			if(count($client_id_arr)>0 && $assigned_to_user_id!='')
        	{
				foreach($client_id_arr AS $client_id)
				{
					$post_data=array();
					$post_data=array(
						'assigned_to_user_id'=>$assigned_to_user_id,
						'assigned_by_user_id'=>$assigned_by_user_id
					);
					$this->Client_model->update_client_assign_to_user($post_data,$client_id);
					
				}

				$status_str='success';
				$html="DONE";
			} else {
				$status_str='failed';
				$html="Parameter Missing";
			}
 
	        $result["status"] = $status_str;
	        $result["html"]=$html;	  

	        echo json_encode($result);
	        exit(0);
					
		}
	}

	public function change_client_assigned_single_view_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$client_id=$this->input->post('cid');
			$assigned_user_id=$this->input->post('exas_id');
			$list['client_id']=$client_id;
			$list['assigned_user_id']=$assigned_user_id;		
			$list['user_list']=$this->Adminportal_model->get_manager_list();

			$html = $this->load->view('adminmaster/client_assigned_single_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;
	        echo json_encode($result);
	        exit(0);
		}
	}

	
	public function change_client_assigned_single_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$client_id=$this->input->post('client_id');
			$assigned_to_user_id=$this->input->post('user_id');

			$admin_session_data = $this->session->userdata('adminportal_session_data'); 
			$assigned_by_user_id = $admin_session_data['user_id'];

			if($client_id!='' && $assigned_to_user_id!='')
        	{
				$post_data=array();
				$post_data=array(
					'assigned_to_user_id'=>$assigned_to_user_id,
					'assigned_by_user_id'=>$assigned_by_user_id
				);
				$this->Client_model->update_client_assign_to_user($post_data,$client_id);

				$status_str='success';
				$html="DONE";
			} else {
				$status_str='failed';
				$html="Parameter Missing";
			}

	        $result["status"] = $status_str;
	        $result["html"]=$html;	  

	        echo json_encode($result);
	        exit(0);
					
		}
	}

	

	// ======================================================
	// ######################################################
	public function detail($client_id='')
	{	
		if($client_id=='')
		{

		}

		$data = array(); 	
		$client_info=$this->Client_model->get_details($client_id);	
		$data['client_info']=$client_info;
		// $data['user_list']=$this->User_model->get_client_wise_user_list($client_info);
		$data['service_list']=$this->Client_model->get_service();	


		$data['topmenu_list']=get_permission_wise_menu_list();



		$this->load->view('adminmaster/client_detail_view',$data);
	}
	public function add_client_service_order_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$action=$this->input->post('action');
			$service_detail_id=$this->input->post('service_order_detail_id');
			$client_id=$this->input->post('client_id');
			$service_id=$this->input->post('service_id');
			$display_name=$this->input->post('display_name');
			$no_of_user=$this->input->post('no_of_user');
			$price=$this->input->post('price');
			$start_date=$this->input->post('start_date');
			$end_date=$this->input->post('end_date');
			$expiry_date=$this->input->post('expiry_date');
			$service_status=$this->input->post('service_status');

			
			if($client_id!='' &&  $display_name!='' &&  $price!='' && $start_date!='' && $end_date!='' && $expiry_date!='')
			{
				$start_date_tmp=date_display_format_to_db_format($start_date);
				$end_date_tmp=date_display_format_to_db_format($end_date);
				$expiry_date_tmp=date_display_format_to_db_format($expiry_date);


				$earlier=new DateTime($start_date_tmp);
				$later=new DateTime($end_date_tmp);
				$start_end_date_diff=$earlier->diff($later)->format("%r%a");
				if($start_end_date_diff<=0)
				{
					$result["status"] = 'fail';	
					$result["msg"] = 'End date should be greater than start date.';	  
					echo json_encode($result);
					exit(0);
				}

				$earlier2=new DateTime($start_date_tmp);
				$later2=new DateTime($expiry_date_tmp);
				$start_expiry_date_diff=$earlier2->diff($later2)->format("%r%a");
				if($start_expiry_date_diff<=0)
				{
					$result["status"] = 'fail';	
					$result["msg"] = 'Expiry date should be greater than start date.';	  
					echo json_encode($result);
					exit(0);
				}


				$earlier3=new DateTime($end_date_tmp);
				$later3=new DateTime($expiry_date_tmp);
				$end_expiry_date_diff=$earlier3->diff($later3)->format("%r%a");
				if($end_expiry_date_diff<0)
				{
					$result["status"] = 'fail';	
					$result["msg"] = 'Expiry date should be equal or greater than end date.';	  
					echo json_encode($result);
					exit(0);
				}

				
				if(strtolower($action)=='edit' && $service_detail_id!='')
				{
					$service_info=$this->Client_model->get_service_row($service_id);
					// ------------------------------------------------------------------------------------ 
					$activity_text='';
					$user_id=$this->session->userdata['adminportal_session_data']['user_id'];
					$user_name=$this->session->userdata['adminportal_session_data']['user_name'];
					// ------------------------------------------------------------------------------------

					$service_detail=$this->Client_model->get_service_order_detail($service_detail_id);
					$exist_price=$service_detail['price'];
					$exist_total_price=$service_detail['total_price']; 
					$service_order_id=$service_detail['service_order_id'];

					$post_data=array();
					$post_data=array(
						'display_name'=>$display_name,
						'no_of_user'=>$no_of_user,
						'price'=>$price,
						'start_date'=>$start_date_tmp,
						'end_date'=>$end_date_tmp,
						'expiry_date'=>$expiry_date_tmp,
						'is_active'=>$service_status,
						'updated_at'=>date("Y-m-d H:i:s")
						);

					if($this->Client_model->edit_client_wise_service_order_detail($post_data,$service_detail_id)){
						$main_price=($exist_total_price-$exist_price);
						$total_price=($main_price+$price);
						$post_data=array();
						$post_data=array(
							'total_price'=>$total_price,
							'created_at'=>date("Y-m-d H:i:s")
							);
						$this->Client_model->edit_client_wise_service_order($post_data,$service_order_id);

					}





					// ------------------------------------------------------------------------------------ 
					$activity_text .="The Service(".$service_info['name'].'-'.$display_name.") updated by ".$user_name." on ".date_db_format_to_display_format(date("Y-m-d H:i:s"));						
					// ------------------------------------------------------------------------------------
					
					
					$get_last_service_order_detail_log_id=$this->Client_model->get_last_service_order_detail_log_id($service_detail_id);
					if($get_last_service_order_detail_log_id!=0)
					{
						$post_data=array();
						$post_data=array(
							'display_name'=>$display_name,
							'no_of_user'=>$no_of_user,
							'price'=>$price,
							'start_date'=>$start_date_tmp,
							'end_date'=>$end_date_tmp,
							'expiry_date'=>$expiry_date_tmp,
							'is_active'=>$service_status,
							'updated_at'=>date("Y-m-d H:i:s")
							);
						$this->Client_model->edit_client_wise_service_order_detail_log($post_data,$get_last_service_order_detail_log_id);
					}
					

					// =========================
					// ACTIVITY LOG
					$activity_text .="<br>Service Name: ".$service_info['name'].'-'.$display_name;
					$activity_text .="<br>No of user: ".$no_of_user;
					$activity_text .="<br>Amount: ".$price;
					$activity_text .="<br>Start Date: ".date_db_format_to_display_format($start_date_tmp);
					$activity_text .="<br>End Date: ".date_db_format_to_display_format($end_date_tmp);
					$activity_text .="<br>Expiry Date: ".date_db_format_to_display_format($expiry_date_tmp);						
					$ip_address=$_SERVER['REMOTE_ADDR'];
					$activity_title=LOG_TITLE_CLIENT_SERVICE_EDIT;
					$logdata=array(
								'user_id'=>$user_id,
								'client_id'=>$client_id,	
								'activity_title'=>$activity_title,							
								'activity_text'=>$activity_text,
								'ip_address'=>$ip_address,
								'created_at'=>date("Y-m-d H:i:s")
								);
					$this->Client_model->CreateActivityLog($logdata);
					// ACTIVITY LOG
					// =========================

					$result["status"] = 'success';	  
					echo json_encode($result);
					exit(0);
				}
				else
				{
					if($service_detail_id!='')
					{
						$service_detail=$this->Client_model->get_service_order_detail($service_detail_id);
						$service_id=$service_detail['service_id']; 
						$no_of_user=$service_detail['no_of_user']; 
					}
					else
					{
						if($service_id!='' &&  $no_of_user!='')
						{

						}
						else
						{
							$result["status"] = 'fail';	  
							echo json_encode($result);
							exit(0);
						}
					}
					
					$service_info=$this->Client_model->get_service_row($service_id);
					$chk_client_wise_service_order=$this->Client_model->chk_client_wise_service_order($client_id,$service_id);
					if(count($chk_client_wise_service_order))
					{
						$service_order_id=$chk_client_wise_service_order['id'];	
						$total_price=($chk_client_wise_service_order['total_price']+$price);
						$post_data=array();
						$post_data=array(
							'total_price'=>$total_price,
							'created_at'=>date("Y-m-d H:i:s")
							);
						$this->Client_model->edit_client_wise_service_order($post_data,$service_order_id);

					}
					else
					{	
						$post_data=array();
						$post_data=array(
							'client_id'=>$client_id,
							'service_id'=>$service_id,
							'service_name'=>$service_info['name'],
							'total_price'=>$price,
							'created_at'=>date("Y-m-d H:i:s")
							);
						$service_order_id=$this->Client_model->add_client_wise_service_order($post_data);	
					}
								
					if($service_order_id!=false)
					{
						// ------------------------------------------------------------------------------------ 
						$activity_text='';
						$user_id=$this->session->userdata['adminportal_session_data']['user_id'];
						$user_name=$this->session->userdata['adminportal_session_data']['user_name'];
						// ------------------------------------------------------------------------------------ 
						if($service_detail_id)
						{
							$post_data=array();
							$post_data=array(							
								'price'=>$price,
								'end_date'=>$end_date_tmp,
								'expiry_date'=>$expiry_date_tmp
								);
							$this->Client_model->edit_client_wise_service_order_detail($post_data,$service_detail_id);

							// ------------------------------------------------------------------------------------ 
							$activity_text .="A Renew of Existing Service Created by ".$user_name." on ".date_db_format_to_display_format(date("Y-m-d H:i:s"));	
							$activity_title=LOG_TITLE_CLIENT_SERVICE_RENEW;					
							// ------------------------------------------------------------------------------------

						}
						else
						{
							$post_data=array();
							$post_data=array(
								'service_order_id'=>$service_order_id,
								'display_name'=>$display_name,
								'no_of_user'=>$no_of_user,
								'price'=>$price,
								'start_date'=>$start_date_tmp,
								'end_date'=>$end_date_tmp,
								'expiry_date'=>$expiry_date_tmp,
								'is_active'=>$service_status,
								'created_at'=>date("Y-m-d H:i:s")
								);
							$service_detail_id=$this->Client_model->add_client_wise_service_order_detail($post_data);

							$servicecall_data=array();
							$servicecall_data=array(
								'service_id'=>$service_detail_id,
								'service_call_type_id'=>1,
								'call_by_user_id'=>$user_id,
								'scheduled_call_datetime'=>date("Y-m-d H:i:s"),
								'created_at'=>date("Y-m-d H:i:s")
								);
							
							$this->Client_model->add_service_wise_service_call($servicecall_data);

							// ------------------------------------------------------------------------------------ 
							$activity_text .="A New Service Created by ".$user_name." on ".date_db_format_to_display_format(date("Y-m-d H:i:s"));	
							$activity_title=LOG_TITLE_CLIENT_SERVICE_ADD;					
							// ------------------------------------------------------------------------------------
						}
						

						if($service_detail_id!=false)
						{
							$post_data=array();
							$post_data=array(
								'service_order_detail_id'=>$service_detail_id,
								'display_name'=>$display_name,
								'no_of_user'=>$no_of_user,
								'price'=>$price,
								'start_date'=>$start_date_tmp,
								'end_date'=>$end_date_tmp,
								'expiry_date'=>$expiry_date_tmp,
								'is_active'=>$service_status,
								'created_at'=>date("Y-m-d H:i:s")
								);
							$this->Client_model->add_client_wise_service_order_detail_log($post_data);
							$status_str='success';

							// =========================
							// ACTIVITY LOG
							$activity_text .="<br>Service Name: ".$service_info['name'].'-'.$display_name;
							$activity_text .="<br>No of user: ".$no_of_user;
							$activity_text .="<br>Amount: ".$price;
							$activity_text .="<br>Start Date: ".date_db_format_to_display_format($start_date_tmp);
							$activity_text .="<br>End Date: ".date_db_format_to_display_format($end_date_tmp);
							$activity_text .="<br>Expiry Date: ".date_db_format_to_display_format($expiry_date_tmp);						
							$ip_address=$_SERVER['REMOTE_ADDR'];
							
							$logdata=array(
										'user_id'=>$user_id,
										'client_id'=>$client_id,
										'activity_title'=>$activity_title,								
										'activity_text'=>$activity_text,
										'ip_address'=>$ip_address,
										'created_at'=>date("Y-m-d H:i:s")
										);
							$this->Client_model->CreateActivityLog($logdata);
							// ACTIVITY LOG
							// =========================
						}
						else
						{
							$status_str='fail';
						}						
					}
					else
					{
						$status_str='fail';
					}	
							
					$result["status"] = $status_str;	  
					echo json_encode($result);
					exit(0);
				}				
			}			
		}
	}
	public function get_client_service_order_liat_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$list=array();
			$client_id=$this->input->post('client_id');			
			$list['rows']=$this->Client_model->get_client_wise_service_order_list($client_id);	
			$html = $this->load->view('adminmaster/client_wise_service_list_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;	  
	        echo json_encode($result);
	        exit(0);
		}
	}

	public function get_client_service_order_detail_log_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$list=array();
			$service_order_detail_id=$this->input->post('service_order_detail_id');			
			$list['rows']=$this->Client_model->get_client_wise_service_order_detail_log($service_order_detail_id);	
			$html = $this->load->view('adminmaster/client_wise_service_order_detail_log_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;	  
	        echo json_encode($result);
	        exit(0);
		}
	}
	// ######################################################
	// ======================================================

	// ======================================================
	// Manage User
	public function manage_user($client_id='')
	{	
		if($client_id=='')
		{

		}
		$data = array(); 	
		$client_info=$this->Client_model->get_details($client_id);	
		$data['client_info']=$client_info;	
		
		$data['topmenu_list']=get_permission_wise_menu_list();

		$this->load->view('adminmaster/manage_user_view',$data);
	}
	
	public function get_user_list_ajax()
	{
	    $start = $this->input->get('page');
		$client_id = $this->input->get('client_id');
		$client_info=$this->Client_model->get_details($client_id);
	    $arg=array();	   
	    $arg['filter_sort_by']=$this->input->get('filter_sort_by');
		$arg['client_info']=$client_info;
	    $this->load->library('pagination');
	    $limit=30;
	    $config = array();	

	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->User_model->get_client_wise_user_list_count($arg);	    
	    $config['per_page'] = $limit;
	    $config['uri_segment']=4;
	    $config['num_links'] = 1;
	    $config['use_page_numbers'] = TRUE;
	    $config['attributes'] = array('class' => 'myclass');	    
	    $config["full_tag_open"] = '<ul class="pagination">';
	    $config["full_tag_close"] = '</ul>';	
	    $config["first_link"] = "&laquo;";
	    $config["first_tag_open"] = "<li>";
	    $config["first_tag_close"] = "</li>";
	    $config["last_link"] = "&raquo;";
	    $config["last_tag_open"] = "<li>";
	    $config["last_tag_close"] = "</li>";
	    $config['next_link'] = '&gt;';
	    $config['next_tag_open'] = '<li>';
	    $config['next_tag_close'] = '<li>';
	    $config['prev_link'] = '&lt;';
	    $config['prev_tag_open'] = '<li>';
	    $config['prev_tag_close'] = '<li>';
	    $config['cur_tag_open'] = '<li class="active"><a href="#">';
	    $config['cur_tag_close'] = '</a></li>';
	    $config['num_tag_open'] = '<li>';
	    $config['num_tag_close'] = '</li>';
	    $this->pagination->initialize($config);
	    $page_link = '';
	    $page_link = $this->pagination->create_links();	   
	    $start=empty($start)?0:($start-1)*$limit;
	    $arg['limit']=$limit;
	    $arg['start']=$start;		
	    $list['rows']=$this->User_model->get_client_wise_user_list($arg);	
		$list['client_id']=$client_id; 		  
	    $table = '';
	    $table = $this->load->view('adminmaster/manage_user_view_ajax',$list,TRUE);
	    $data =array (
	       "table"=>$table,
	       "page"=>$page_link,
	       "search_str"=>$list['rows']
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
		exit;
	}

	public function manage_user_tag_service($client_id='')
	{	
		if($client_id=='')
		{

		}
		$data = array(); 	
		$data['client_id']=$client_id;
		$client_info=$this->Client_model->get_details($client_id);	
		$data['client_info']=$client_info;	
		$data['all_available_service']=$this->Client_model->get_service_order_by_client($client_id);
		$data['all_user']=$this->User_model->GetAllUsersNotInTaggedService('0','',$client_info);		
		$data['all_user_wise_service_order']=$this->User_model->all_user_wise_service_order($client_info);

		$data['topmenu_list']=get_permission_wise_menu_list();
		
		$this->load->view('adminmaster/manage_user_tag_service_view',$data);
	}
	function rander_service_list_ajax()
	{		
		$client_id=$this->input->get('client_id');
		$client_info=$this->Client_model->get_details($client_id);
	    $s_id=$this->input->get('s_id');
		$list=array();
		$list['s_id']=$s_id;
		$list['client_id']=$client_id;
		$all_available_service_order=$this->Client_model->get_client_wise_service_order_list($client_id);
		$list['all_available_service_order']=$all_available_service_order;
		$list['all_user']=$this->User_model->GetAllUsersNotInTaggedService('0',$s_id,$client_info);	
		$list['all_user_wise_service_order']=$this->User_model->all_user_wise_service_order($client_info);
		$html = $this->load->view('adminmaster/manage_user_tag_service_view_ajax',$list,TRUE);   		
	    $data =array (
	       "html"=>$html
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
		exit;
	}
	function tag_user_wise_service()
    {        
		
		$client_id=$this->input->post('client_id');;
		$client_info=$this->Client_model->get_details($client_id);
		$result["status"] = 'success';
		
        $user_id=$this->input->post('user_id');
		$sod_id_to=$this->input->post('sod_id_to');
		$sod_id_from=$this->input->post('sod_id_from');		
		// if($sod_id_to!='' && $sod_id_from==''){
		// 	$result["msg"] = 'Intitial to service';			
		// }
		// else if($sod_id_to=='' && $sod_id_from!=''){
		// 	$result["msg"] = 'Service to initial';	
		// }
		// else{
		// 	$result["msg"] = 'Service to service';		
		// }
		

		if($sod_id_to!='')
		{
			$all_available_service_order=$this->Client_model->get_client_wise_service_order_list($client_info->client_id);
			if(count($all_available_service_order))
			{
				foreach($all_available_service_order AS $all_available_service_order)
				{
					if($all_available_service_order['id']==$sod_id_to)
					{
						$no_of_user=$all_available_service_order['no_of_user'];
					}
				}
			}
			

			$existing_no_of_user=$this->User_model->existing_no_of_user_tagged_service($sod_id_to,$client_info);
			// $result["msg"]=$no_of_user.'/'.$existing_no_of_user;
			if($no_of_user>$existing_no_of_user)
			{
				if($sod_id_from!='')
				{
					$this->User_model->delete_existing_tagged_service($user_id,$sod_id_from,$client_info);
				}
				$sod_info=$this->Client_model->get_service_order_detail($sod_id_to);
				$post_data=array(
					'user_id'=>$user_id,
					'service_id'=>$sod_info['service_id'],
					'service_order_detail_id'=>$sod_id_to
					);				
				$this->User_model->CreateTagService($post_data,$client_info);
			}
			else
			{
				$result["status"] = 'fail';
				$result["msg"] = 'User Limit Exceeded. Please contact your LMSBaba servicing manager.';	
			}			
		} 
		else
		{
			if($sod_id_from!='')
			{
				$this->User_model->delete_existing_tagged_service($user_id,$sod_id_from,$client_info);
			}
		}
        
        echo json_encode($result);
        exit(0);        
    }

	public function manage_user_permission($client_id='',$user_id='')
	{	
		if($client_id=='')
		{

		}
		$client_info=$this->Client_model->get_details($client_id);
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{                
			$this->User_model->set_permission($client_info);            
			$this->session->set_flashdata('success_msg', 'User permission successfully updated.');			
			redirect(adminportal_url().'client/manage_user_permission/'.$client_id.'/'.$user_id,'refresh');			
		}

		$data = array(); 	
			
		$data['client_info']=$client_info;		
		$data['client_id']=$client_id;
		$data['user_id']=$user_id;
		$data["service_wise_menu"] = $this->User_model->get_service_wise_menu();
		$data['user_wise_permission_keyword_arr'] = $this->User_model->get_user_wise_permission($user_id,$client_info);

		$data['topmenu_list']=get_permission_wise_menu_list();


		$this->load->view('adminmaster/manage_user_permission_view',$data);
	}
	// Manage User
	// ======================================================
	
	
	
}