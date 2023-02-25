<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
	function __construct()
	{
		parent :: __construct();
		is_adminportal_logged_in();
		chk_access_menu_permission(4);
		init_adminportal_element();   
		$this->load->model(array("Adminportal_model","User_model"));
	}

	public function index()
	{	
		$data = array(); 
		$data['topmenu_list']=get_permission_wise_menu_list();
		$this->load->view('adminmaster/users_view',$data);
	}

	public function get_users_list_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$argument = array();

			$account_type_id=$this->input->post('account_type');
			$is_active=$this->input->post('is_active');
			$argument['account_type_id']=$account_type_id;
			$argument['is_active']=$is_active;		
			
			$list['rows']=$this->Adminportal_model->get_users_list($argument);

			$html = $this->load->view('adminmaster/users_list_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;
	        echo json_encode($result);
	        exit(0);
		}
	}

	public function get_users_list_treeview_ajax()
	{
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$argument = array();

			$account_type_id=$this->input->post('account_type');
			$is_active=$this->input->post('is_active');
			$argument['account_type_id']=$account_type_id;
			$argument['is_active']=$is_active;		
			
			$list['rows']=$this->Adminportal_model->get_users_list_treeview($argument);

			$html = $this->load->view('adminmaster/users_list_tree_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;
	        echo json_encode($result);
	        exit(0);
		}
	}


	public function get_menu_permission_view_ajax()
	{
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$argument = array();

			$user_id=$this->input->post('uid');
			$argument['user_id']=$user_id;
			
			$list['menu_list_data']=$this->Adminportal_model->get_all_menu_with_element_list();
			//$list['permission_id']=$this->Adminportal_model->get_menu_permission_id_list($argument);
			$list['menu_permission_id']=$this->Adminportal_model->get_menu_permission_id_list($argument);
			$list['element_permission_id']=$this->Adminportal_model->get_element_permission_id_list($argument);
			$list['user_id']=$user_id;

            //$list='';

			$html = $this->load->view('adminmaster/users_wise_menu_permission_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;
	        echo json_encode($result);
	        exit(0);
		}
	}

	public function update_user_permission()
	{
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$this->Adminportal_model->set_menu_permission();            
			$this->session->set_flashdata('success_msg', 'User permission successfully Updated.');			
		}
		redirect(adminportal_url().'users','refresh');
	}

	public function update_users()
	{
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			
			
			$mode=$this->input->post('mode');
			$user_id=$this->input->post('uid');
			$manager_id=$this->input->post('mid');
			$user_name=$this->input->post('user_name');
			$email_id=$this->input->post('email_id');
			$mobile=$this->input->post('mobile');
			$log_password=$this->input->post('log_password');

			$validation_chk=true;
			$err_msg='';

			if(trim($user_name)==''){
				$validation_chk=false;
				$err_msg="Please Enter User Name";
			} elseif(trim($email_id)=='' || !filter_var($email_id, FILTER_VALIDATE_EMAIL)){
				$validation_chk=false;
				$err_msg="Please Enter Correct Email ID";
			} elseif(trim($mobile)=='' || strlen($mobile)<10 || !is_numeric($mobile)){
				$validation_chk=false;
				$err_msg="Please Enter 10 digits Mobile Number";
			} elseif($mode=='N' && (trim($log_password)=='' || strlen($log_password)<6)){
				$validation_chk=false;
				$err_msg="Please Enter min 6 digits Login Password";
			}

			if($mode=='E' && trim($log_password)!='' && strlen($log_password)<6){
				$validation_chk=false;
				$err_msg="Please Enter min 6 digits Login Password";
			}

			if($validation_chk==true){

				$email_check=$this->Adminportal_model->duplicate_user_email_check($email_id,$user_id);

				if($email_check>0){
					$status_str='failed';  
					$html="Sorry! This Email ID already Exist";
				} else {

					if($mode=='N'){
						$post_data=array(
							'user_type'=>"User",
							'manager_id'=>$manager_id,
							'name'=>$user_name,
							'email'=>$email_id,
							'password'=>md5($log_password),
							'mobile'=>$mobile,
							'create_date'=>date("Y-m-d")
							);
						$return=$this->Adminportal_model->insert_user($post_data);
						$this->session->set_flashdata('success_msg', 'New user successfully Added.');
					} elseif($mode=='E'){

						$post_data['name']=$user_name;
						$post_data['manager_id']=$manager_id;
						$post_data['email']=$email_id;
						$post_data['mobile']=$mobile;
						$post_data['modify_date']=date("Y-m-d G:i:s");

						if(trim($log_password)!=''){
							$post_data['password']=md5($log_password);
						}

						$return=$this->Adminportal_model->update_user($post_data,$user_id);
						$this->session->set_flashdata('success_msg', 'User details successfully Updated.');
					}
						

						$status_str='success';  
						$html='SUCCESS';
						
				}

				
			} else {
				$status_str='failed';  
				$html=$err_msg;
			}

	        $result["status"] = $status_str;
	        $result["html"]=$html;
	        echo json_encode($result);
	        exit(0);
		}
	}

	public function get_add_user_view_ajax()
	{
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{

			$list['user_list']=$this->Adminportal_model->get_manager_list();
			$html = $this->load->view('adminmaster/users_add_popup_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;
	        echo json_encode($result);
	        exit(0);
		}
	}

	public function get_edit_user_view_ajax()
	{
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$user_id=$this->input->post('uid');
			$list['user_list']=$this->Adminportal_model->get_manager_list($user_id);
			$list['user_details']=$this->Adminportal_model->get_user_details($user_id);

			$html = $this->load->view('adminmaster/users_edit_popup_view_ajax',$list,TRUE);
			$status_str='success';  
	        $result["status"] = $status_str;
	        $result["html"]=$html;
	        echo json_encode($result);
	        exit(0);
		}
	}

	public function delete_users()
	{
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$user_id=$this->input->post('uid');
			if($this->Adminportal_model->delete_user($user_id)==true){
				$status_str='success';
				$this->session->set_flashdata('success_msg', 'This User successfully Deleted.');
			} else {
				$status_str='failed'; 
			}

	        $result["status"] = $status_str;
	        $result["html"]='';
	        echo json_encode($result);
	        exit(0);
		}
	}

	
	
	

	

	

	
}