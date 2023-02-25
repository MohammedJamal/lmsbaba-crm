<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends CI_Controller {	
	var $pcategory;
	private $api_access_token = '';
	function __construct()
	{
		parent :: __construct();
		is_admin_logged_in();
		init_admin_element();       
		$this->load->model(array("user_model","countries_model","states_model","cities_model","menu_model","product_model","lead_model","customer_model","Package_model","Email_forwarding_setting_model","Setting_model","Client_model"));	
		$this->load->model('Department_model','department_model');
		// permission checking
		
		// if(is_permission_available($this->session->userdata('service_wise_menu')[7]['menu_list']['menu_keyword'])===false){ 
		// 	redirect(admin_url().'dashboard', 'refresh');
		// 	exit(0);
		// }
		// end
	}	



	// ==========================================================
	// ============ DEPARTMENT SECTION START ====================
	// ==========================================================

	
	function validate_department_form_data()
	{ 
	    $this->load->library('form_validation');	   
	    $this->form_validation->set_rules('category_name', 'name', 'required');
	    if ($this->form_validation->run() == TRUE)
	    {
	      return TRUE;
	    }
	    else
	    {
	      return FALSE;
	    }
	}

	public function manage_department($id='')
	{
		// is_admin_session_data();
		$data['page_title'] = "Admin";
		$data['page_keyword'] = "Admin";
		$data['page_description'] = "Admin";   		
		//$data['rows']=$this->user_model->GetUserDepartmentListAll('User');

		global $pcategory; //define a global variable for display the menu list in tree view format
        $data = array();
        $data['edit_id']=$id;
        if($id!=''){
            $data['row'] = $this->department_model->get_details($id);
        }        
        else
        {
            $data['row'] = array();
        }

        ##----------------- post data submitted -----------------------##
        //submitted form data	
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{	
			//validate form data
			if($this->validate_department_form_data() == TRUE)
			{    
                $edit_id=$this->input->post('edit_id');
                if($edit_id=='')
                {
                    //save data
                    $return = $this->department_model->add();
                    if($return=="success")
                    {                       
                        $msg="Department added successfully.";
                        $this->session->set_flashdata('success_msg', $msg);
                        redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_department');
                    }
                }
                else
                {
                    //save data
                    $return = $this->department_model->edit($edit_id);
                    if($return=="edit_success")
                    { 
                        $msg="Department has been updated successfully.";
                        $this->session->set_flashdata('success_msg', $msg);
                        redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_department');
                    }
                }
				
			 }
			else
			{	
				$msg = validation_errors(); // 'duplicate';
                $this->session->set_flashdata('error_msg', $msg);
                redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_department');
			}
			
		}
        ##-------------------------------------------------------------##
        
        ##----------------- department listing -----------------------##
        $treeArray=$this->department_model->get_nlevel_array();        
        $data['tree_html_view']="";        
        if(count($treeArray) >0)
        {  
            $this->create_tree($treeArray, 0 , 0, -1);
            $data['tree_html_view'] = $pcategory;
        }
        ##-----------------------------------------------------------##

		$this->load->view('admin/user/manage_department',$data);
	}

    // Department tree html view
    function create_tree($array,$currentParent,$currLevel=0,$prevLevel=-1) 
    {
        global $pcategory; //define a global variable for display the menu list in tree view format
        $i=0;
        foreach ($array as $itemId => $dtls) 
        {
            if($currentParent == $dtls['parent_id']) 
            {
                if ($currLevel > $prevLevel) $pcategory .= '<ol class="dd-list">';
                if ($currLevel == $prevLevel) $pcategory .='</li>';

                if(is_method_available('user','edit_department')==TRUE){
                $category_edit_link='<a class="cat_edit" onclick="update_category('.$itemId.');"><i class="fa fa-pencil tree_action" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit department"></i></a>';

                $dragable_html='<div class="dd-handle dd3-handle">Drag</div>';
            	}
            	else
            	{
            		$category_edit_link='<i class="fa fa-pencil tree_action" data-original-title="Not Applicable" data-toggle="tooltip" data-placement="left" style="text-decoration: line-through;"></i>';
            	}

            	if(is_method_available('user','delete_department')==TRUE){
                $category_delete_link='<a onclick="delete_record('.$itemId.');"><i class="fa fa-fw fa-trash-o tree_action" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete department"></i></a>';

            	}
            	else
            	{
            		$category_delete_link='<i class="fa fa-fw fa-trash-o tree_action " data-toggle="tooltip" data-placement="left" title="" data-original-title="Not Applicable"  style="text-decoration: line-through;"></i>';
            	}

                $pcategory .='<li class="dd-item dd3-item" data-id="'.$itemId.'"> '.$dragable_html.' <div class="dd3-content"><div id="sp_pg_'.$itemId.'" style="float:left;">'.ucwords($dtls['category_name']).' &nbsp;<span class="badge badge-secondary bg-info" id="">'.$dtls['emp_count'].'</span>'.'</div><div></div>'.$category_delete_link.$category_edit_link.' </div>';


                if($currLevel > $prevLevel)
                { 
                        $prevLevel = $currLevel;
                }

                $currLevel++; 
                $this->create_tree($array, $itemId, $currLevel, $prevLevel);
                $currLevel--;     

            }
            $i++;
        }
        if ($currLevel == $prevLevel) $pcategory .="</li></ol>";
    }
    
    // Add Department ajax
    function add_department_ajax()
    {
    	$department_name=$this->input->post('category_name');
    	$is_same_level=$this->input->post('is_same_level');
    	$p_id=$this->input->post('parent_id');
    	$parent_id=$this->input->post('category_id');
    	
    	if(isset($is_same_level))
    	{
    		$is_same_level='N';
    	}
    	else
    	{
    		$is_same_level='Y';
    	}

    	if($is_same_level=='N' && $p_id!='')
    	{
    		$listing_pos=$this->department_model->get_listing_position($p_id);
    		$postdata = array(
						'category_name'=> $department_name,
						'parent_id'   => $p_id,
                        'listing_position'  => $listing_pos,
                        'date_added'=>date("Y-m-d H:i:s"),
                        'date_modified'=>date("Y-m-d H:i:s")
						);

    	}
    	else
    	{
    		$listing_pos=$this->department_model->get_listing_position($parent_id);
    		$postdata = array(
						'category_name'=> $department_name,
						'parent_id'   => $parent_id,
                        'listing_position'  => $listing_pos,
                        'date_added'=>date("Y-m-d H:i:s"),
                        'date_modified'=>date("Y-m-d H:i:s")
						);
    	}
    	$return=$this->department_model->addDepartment($postdata);
    	//$return=1;
    	if($return)
        {               
        	$flag='success';        
            $msg="Department added successfully.";
        }
        else
        {
        	$flag='fail';
        	$msg="Oops! Fail to insert department.";
        }
    	$data =array (
           "msg"=>$msg,
           "status"=>$flag,
           "return"=>$postdata
            );
       	
        $this->output->set_content_type('application/json');
        echo json_encode($data);
    }

    // Department add view
    function ajax_load_add_department()
    {	
    	$pid=($this->input->post('edit_parent_cat_id'))?$this->input->post('edit_parent_cat_id'):0;
        $data =array();
        ##-------------------------------------------------------##
        ##-Prepare tree view of existing category into dropdown list -##
        $treeArray=$this->department_model->get_nlevel_array();
        $options = array(); //initializing array
        if(count($treeArray))
        {
            $i=0;
            foreach($treeArray as $id=>$cats)
            {                    
                $options[$i]=array(
                                    'id'        =>  $id,
                                    'name'      =>  $cats['category_name'],
                                    'parent_id' =>  $cats['parent_id'] 
                                   );
                $i++;                    
            }
        }
        
        $params=array(
                    'selectbox_name'     =>'category_id',
                    'class'       =>'custom-select form-control',
                    'default_select_value'  => $pid,
                    'default_select_text'   => 'Select Parent Department',
                    'multiple'              => FALSE
                    );
        
        $selected_id=$this->input->post('edit_parent_cat_id');
        $cat_id=$this->input->post('edit_id');
        if($cat_id!=''){
            $data['row'] = $this->department_model->get_details($cat_id);
        }        
        else
        {
            $data['row'] = array();
        }
        
        $data['tree_view_dropdown'] = $this->common_functions->get_category_tree($options,array($selected_id),array(),$params);
		// print_r($data['tree_view_dropdown'] ); 
		// die('ok');
        ##------------------------------------------------------------##
        
        $category_add_view = $this->load->view('admin/user/add_department_ajax', $data, true);
        
        echo $category_add_view;
        
    }


    function ajax_load_add_category()
    {
    	$pid=($this->input->post('pid'))?$this->input->post('pid'):0;
        $data =array();
        ##-------------------------------------------------------##
        ##-Prepare tree view of existing category into dropdown list -##
        $treeArray=$this->department_model->get_nlevel_array();
        $options = array(); //initializing array
        if(count($treeArray))
        {
            $i=0;
            foreach($treeArray as $id=>$cats)
            {                    
                $options[$i]=array(
                                    'id'        =>  $id,
                                    'name'      =>  $cats['category_name'],
                                    'parent_id' =>  $cats['parent_id'] 
                                   );
                $i++;                    
            }
        }
        
        $params=array(
                    'selectbox_name'     =>'category_id',
                    'class'       =>'custom-select form-control',
                    'default_select_value'  => $pid,
                    'default_select_text'   => 'Select as a parent',
                    'multiple'              => FALSE
                    );
        
        $selected_id=$this->input->post('edit_parent_cat_id');
        $cat_id=$this->input->post('edit_id');
        if($cat_id!=''){
            $data['row'] = $this->department_model->get_details($cat_id);
        }        
        else
        {
            $data['row'] = array();
        }

        $data['tree_view_dropdown'] = $this->common_functions->get_category_tree($options,array($selected_id),array(),$params);
        ##------------------------------------------------------------##
        
        $category_add_view = $this->load->view('admin/user/add_department_ajax2', $data, true);
        
        echo $category_add_view;
        
    }

    // add department by ajax
    function ajax_save_category()
    {
        $data = array();
        $outputstring=str_replace('\\','',$this->input->post('nestable-output'));
	
        if($outputstring != '')
        {
                $nodeArray=json_decode($outputstring,true);
                $this->department_model->update_category_order($nodeArray,0);
        }	       
    }

    // delete department
    function delete_department($id)
	{	
        $arr_val=array();
        $return_val = $this->department_model->delete($id);
		
        if($return_val=="subcat_exists"){
            $msg = 'Please remove sub-department under this department.';
            $this->session->set_flashdata('error_msg', $msg);
        }
        elseif($return_val=="product_exists"){
            $msg = 'Please remove all the employee(s) under the department.';
            $this->session->set_flashdata('error_msg', $msg);
        }
        else{
            $msg = 'Department has been deleted successfully.';
            $this->session->set_flashdata('success_msg', $msg);
        }      
         
        redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_department');      
	}
    
	

	// ==========================================================
	// ============ DEPARTMENT SECTION END ======================
	// ==========================================================

    

    // ==========================================================
	// ============ DESIGNATION SECTION START ===================
	// ==========================================================

    function validate_designation_form_data()
	{ 
	    $this->load->library('form_validation');	   
	    $this->form_validation->set_rules('name', 'name', 'required');
	    if ($this->form_validation->run() == TRUE)
	    {
	      return TRUE;
	    }
	    else
	    {
	      return FALSE;
	    }
	}

	public function manage_designation($id='')
	{
		// is_admin_session_data();
		$data['page_title'] = "Admin";
		$data['page_keyword'] = "Admin";
		$data['page_description'] = "Admin";   		
		$data['rows']=$this->user_model->GetUserDesignationListAll();
		$data['id']=$id;	
		if($id>0)
		{
			$data['label']='Edit';
			$row=$this->user_model->GetDesignation($id); 
			$data['name']=$row->name;
			$data['action']=base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/edit_designation/'.$id;
		}	
		else
		{
			$data['label']='Add';
			$data['name']='';
			$data['action']=base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/add_designation';
		}
		$this->load->view('admin/user/manage_designation',$data);
	}

	public function add_designation()
   	{
		//is_admin_session_data();
   		$data['page_title'] = "Manage Department";
		$data['page_keyword'] = "Manage Department";
		$data['page_description'] = "Manage Department";
		$data['error_msg'] = '';
   	 	$data['message']=null;
   	 	$data['country_list']=$this->countries_model->GetCountriesList();
   	 	$data['currency_list'] = $this->product_model->GetCurrencyList(); 
		$command=$this->input->post('command');
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			if($this->validate_designation_form_data() == TRUE)
			{    
				$name=$this->input->post('name');				
				$postArr=array(
							'name'=>$name,
			                'created_at'=>date('Y-m-d H:i:s'),
			                'updated_at'=>date('Y-m-d H:i:s')
							);
				$result=$this->user_model->addDesignation($postArr);	
				if($result)
				{ 
					
					$msg = "One record successfully inserted..";
                	$this->session->set_flashdata('success_msg', $msg);
					redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_designation');
					
				}
				else
				{						
					$msg = "Oops! No record inserted. Try again.."; // 'duplicate';
                	$this->session->set_flashdata('error_msg', $msg);                    
                	redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_designation');
				}
			}
			else
			{

			   //$this->form_validation->set_error_delimiters('', '');
                $msg = validation_errors(); // 'duplicate';
                $data['error_msg'] = $msg;
                //$this->session->set_flashdata('error_msg', $msg);                    
                redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_designation');
			}
			
		}	
		// $this->load->view('admin/user/add_designation',$data);
    }

    public function edit_designation($id=null)
    {
   		//is_admin_session_data();
		$data['error_msg'] 			= "";
   	 	$data['page_title'] 		= "Edit Admin";
		$data['page_keyword'] 		= "Edit Admin";
		$data['page_description']   = "Edit Admin";   
		$data['submitted_from']   = $this->router->fetch_method();	 	
   	 	$data['currency_list'] = $this->product_model->GetCurrencyList(); 
		$command=$this->input->post('command');

		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{	
			if ($this->validate_designation_form_data() == TRUE)
			{	
				$name=$this->input->post('name');
				$edit_id=$this->input->post('edit_id');

				$postArr=array(
             				'name'=>$name,
			 				'updated_at'=>date('Y-m-d H:i:s')
			 				);
				$result=$this->user_model->editDesignation($edit_id,$postArr);		
				if($result==true)
				{					
					$msg = "One record successfully updated..";
                	$this->session->set_flashdata('success_msg', $msg);
					redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_designation');					
				}
				else
				{						
					echo $msg = "Oops! No record inserted. Try again.."; // 'duplicate';
                	$this->session->set_flashdata('error_msg', $msg);                    
                	redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_designation/'.$edit_id);
				} 
			}
			else
			{	
				$msg = validation_errors(); // 'duplicate';
                $data['error_msg'] = $msg;
                //$this->session->set_flashdata('error_msg', $msg);                    
                redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_designation/'.$edit_id);
			}			
		}		
		// $data['row']=$this->user_model->GetDesignation($id); 
		// $this->load->view('admin/user/edit_designation',$data);		
    }

    public function delete_designation($id)
    {
   		// is_admin_session_data();
		if($id !='')
		{
			$is_user_exist=$this->user_model->designation_wise_user_count($id);
			if($is_user_exist==0)
			{
				$postArr=array(
         				'is_deleted'=>'Y',
		 				'updated_at'=>date('Y-m-d H:i:s')
		 				);
				$result=$this->user_model->editDesignation($id,$postArr);		
				if($result==true)
				{					
					$msg = "One record successfully deleted..";
	            	$this->session->set_flashdata('success_msg', $msg);
					redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_designation');					
				}
				else
				{						
					$msg = "Oops! No record deleted. Try again.."; // 'duplicate';
	            	$this->session->set_flashdata('error_msg', $msg);
	            	redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_designation');
				} 
			}
			else
			{
				$msg = "Oops! Please remove all the employee(s) under the designation."; // 'duplicate';
	            $this->session->set_flashdata('error_msg', $msg);
	            redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_designation');
			}
						
		}	
		else
		{
			redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_designation');
		}	
    }

    // Designation add ajax view
    function ajax_load_add_designation()
    {
        $data =array();
        echo $this->load->view('admin/user/add_designation_ajax', $data, true);
        
    }

    // Add Designation ajax
    function add_designation_ajax()
    {
    	if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			if($this->validate_designation_form_data() == TRUE)
			{
				$name=$this->input->post('name');				
				$postArr=array(
							'name'=>$name,
			                'created_at'=>date('Y-m-d H:i:s'),
			                'updated_at'=>date('Y-m-d H:i:s')
							);
				$result=$this->user_model->addDesignation($postArr);	
				if($result)
				{					
					$flag='success';        
            		$msg="Department added successfully.";					
				}
				else
				{						
					$flag='fail';
        			$msg="Oops! Fail to insert department.";
				}
			}
			else
			{
			   //$this->form_validation->set_error_delimiters('', '');
                $msg = validation_errors(); // 'duplicate';
                $flag='fail';
        		$msg=$msg;
			}
			
			$data =array (
	           "msg"=>$msg,
	           "status"=>$flag
            );
       	
	        $this->output->set_content_type('application/json');
	        echo json_encode($data);
		}    	
    }
	// ==========================================================
	// ============ DESIGNATION SECTION END =====================
	// ==========================================================


	// ==========================================================
	// ============ FUNCTIONAL AREA SECTION START ===============
	// ==========================================================

    function validate_functional_area_form_data()
	{ 
	    $this->load->library('form_validation');	   
	    $this->form_validation->set_rules('name', 'name', 'required');
	    if ($this->form_validation->run() == TRUE)
	    {
	      return TRUE;
	    }
	    else
	    {
	      return FALSE;
	    }
	}

	public function manage_functional_area($id='')
	{
		// is_admin_session_data();
		$data['page_title'] = "Admin";
		$data['page_keyword'] = "Admin";
		$data['page_description'] = "Admin";   		
		$data['rows']=$this->user_model->GetUserFunctionalAreaListAll();

		$data['id']=$id;	
		if($id>0)
		{
			$data['label']='Edit';
			$row=$this->user_model->GetFunctionalArea($id); 
			$data['name']=$row->name;
			$data['action']=base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/edit_functional_area/'.$id;
		}	
		else
		{
			$data['label']='Add';
			$data['name']='';
			$data['action']=base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/add_functional_area';
		}		
		$this->load->view('admin/user/manage_functional_area',$data);
	}

	public function add_functional_area()
   	{
		// is_admin_session_data();
   		$data['page_title'] = "Manage Department";
		$data['page_keyword'] = "Manage Department";
		$data['page_description'] = "Manage Department";
		$data['error_msg'] = '';
   	 	$data['message']=null;
   	 	//$data['country_list']=$this->countries_model->GetCountriesList();
   	 	//$data['currency_list'] = $this->product_model->GetCurrencyList(); 
		//$command=$this->input->post('command');
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			if($this->validate_functional_area_form_data() == TRUE)
			{
				$name=$this->input->post('name');				
				$postArr=array(
							'name'=>$name,
			                'created_at'=>date('Y-m-d H:i:s'),
			                'updated_at'=>date('Y-m-d H:i:s')
							);
				$result=$this->user_model->addFunctionalArea($postArr);	
				if($result)
				{ 
					
					$msg = "One record successfully inserted..";
                	$this->session->set_flashdata('success_msg', $msg);
					redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_functional_area');
					
				}
				else
				{						
					$msg = "Oops! No record inserted. Try again.."; // 'duplicate';
                	$this->session->set_flashdata('error_msg', $msg);                    
                	redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_functional_area');
				}
			}
			else
			{
			   //$this->form_validation->set_error_delimiters('', '');
                $msg = validation_errors(); // 'duplicate';
                $data['error_msg'] = $msg;
                //$this->session->set_flashdata('error_msg', $msg);                    
                redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_functional_area');
			}
			
		}		
		// $this->load->view('admin/user/add_functional_area',$data);		
    }

    public function edit_functional_area($id=null)
    {
   		// is_admin_session_data();

		$data['error_msg'] 			= "";
   	 	$data['page_title'] 		= "Edit Admin";
		$data['page_keyword'] 		= "Edit Admin";
		$data['page_description']   = "Edit Admin";   
		//$data['submitted_from']   = $this->router->fetch_method();	 	
   	 	//$data['currency_list'] = $this->product_model->GetCurrencyList(); 
		//$command=$this->input->post('command');

		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{	
			if ($this->validate_functional_area_form_data() == TRUE)
			{	
				$name=$this->input->post('name');
				$edit_id=$this->input->post('edit_id');

				$postArr=array(
             				'name'=>$name,
			 				'updated_at'=>date('Y-m-d H:i:s')
			 				);
				$result=$this->user_model->editFunctionalArea($edit_id,$postArr);		
				if($result==true)
				{					
					$msg = "One record successfully updated..";
                	$this->session->set_flashdata('success_msg', $msg);
					redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_functional_area');					
				}
				else
				{						
					echo $msg = "Oops! No record inserted. Try again.."; // 'duplicate';
                	$this->session->set_flashdata('error_msg', $msg);                    
                	redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_functional_area/'.$edit_id);
				} 
			}
			else
			{	
				$msg = validation_errors(); // 'duplicate';
                $data['error_msg'] = $msg;
                //$this->session->set_flashdata('error_msg', $msg);                    
                redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_functional_area/'.$edit_id);
			}			
		}
		
		// $data['row']=$this->user_model->GetFunctionalArea($id); 
		// $this->load->view('admin/user/edit_functional_area',$data);		
    }

    public function delete_functional_area($id)
    {
   		// is_admin_session_data();
		if($id !='')
		{
			$is_user_exist=$this->user_model->functional_area_wise_user_count($id);

			if($is_user_exist==0)
			{
				$postArr=array(
         				'is_deleted'=>'Y',
		 				'updated_at'=>date('Y-m-d H:i:s')
		 				);
				$result=$this->user_model->editFunctionalArea($id,$postArr);		
				if($result==true)
				{					
					$msg = "One record successfully deleted..";
	            	$this->session->set_flashdata('success_msg', $msg);
					redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_functional_area');					
				}
				else
				{						
					echo $msg = "Oops! No record deleted. Try again.."; // 'duplicate';
	            	$this->session->set_flashdata('error_msg', $msg);                    
	            	redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_functional_area');
				} 	
			}
			else
			{
				echo $msg = "Oops! Please remove all the employee(s) under the functional area."; // 'duplicate';
	            $this->session->set_flashdata('error_msg', $msg);                    
	            redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_functional_area');
			}					
		}	
		else
		{
			redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_functional_area');
		}	
    }

    // Functional Area add ajax view
    function ajax_load_add_functional_area()
    {
        $data =array();
        echo $this->load->view('admin/user/add_functional_area_ajax', $data, true);
        
    }

    // Add Functional Area ajax
    function add_functional_area_ajax()
    {
    	if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			if($this->validate_functional_area_form_data() == TRUE)
			{
				$name=$this->input->post('name');				
				$postArr=array(
							'name'=>$name,
			                'created_at'=>date('Y-m-d H:i:s'),
			                'updated_at'=>date('Y-m-d H:i:s')
							);
				$result=$this->user_model->addFunctionalArea($postArr);	
				if($result)
				{					
					$flag='success';        
            		$msg="Functional area added successfully.";					
				}
				else
				{						
					$flag='fail';
        			$msg="Oops! Fail to insert functional area.";
				}
			}
			else
			{
			   //$this->form_validation->set_error_delimiters('', '');
                $msg = validation_errors(); // 'duplicate';
                $flag='fail';
        		$msg=$msg;
			}
			
			$data =array (
	           "msg"=>$msg,
	           "status"=>$flag
            );
       	
	        $this->output->set_content_type('application/json');
	        echo json_encode($data);
		}    	
    }
	// ==========================================================
	// ============ FUNCTIONAL AREA SECTION END =================
	// ==========================================================



	// ==========================================================
	// ============ EMPLOYEE SECTION START ======================
	// ==========================================================

	
    public function manage_employee($view_type='')
    {
   		
   		$data['page_title'] = "Admin";
		$data['page_keyword'] = "Admin";
		$data['page_description'] = "Admin";   		
   		//$data['admin']=$this->user_model->GetUserListAll();
		if($view_type=='t')
		{
			$data['view_type']='tree';
		}
		else
		{
			$data['view_type']='list';
		}
   		$this->load->view('admin/user/manage_employee',$data);
    }

    // AJAX PAGINATION START
   

    public function set_manager_ajax()
    {
    	$id=$this->input->get('auto_id');
    	$manager_auto_id=$this->input->get('manager_auto_id');
    	if($manager_auto_id>0)
    	{
    		$update=array(
                    'manager_id'=>$manager_auto_id,
                    'modify_date'=>date("Y-m-d H:i:s")
                    );
	        $this->user_model->UpdateAdmin($update,$id);
	        $result["status"] = 'success';
    	}
    	else
    	{
    		$result["status"] = 'fail';
    	}    	
        echo json_encode($result);
        exit(0);
    }

    public function get_employee_tree_list_ajax()
	{	     
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];		

   		if(strtolower($user_type)=='admin')
   		{
   			//$m_id=$this->user_model->get_manager_id($user_id);
   			$m_id=0;
   		}
   		else
   		{	
   			$m_id=$this->user_model->get_manager_id($user_id);
   		}  
   		
	    $list['rows'] = $this->user_model->get_employee_tree_list($user_id,0,$user_type);
	    $list['user_id'] =	$user_id;    
	    $list['user_type'] =	$user_type;    
	    $html = '';
	    $html = $this->load->view('admin/user/manage_employee_tree_view_ajax',$list,TRUE);
	    $data =array (
	       "html"=>$html
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	// get employee detail by clicking from tree view
	public function get_employee_details_ajax()
	{	     
		//$session_data=$this->session->userdata('admin_session_data');
		$eid=$this->input->get('eid');
	    $list['row'] = $this->user_model->get_employee_details($eid);
	    $html = '';
	    $html = $this->load->view('admin/user/employee_details_view_ajax',$list,TRUE);
	    $data =array (
	       "html"=>$html,
	       "title"=>$list['row']['name'].' ( Emp. ID -'.$list['row']['id'].')'
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	public function get_employee_list_ajax()
	{
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];

   		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
		$tmp_u_ids_str=implode(",", $tmp_u_ids);

	    $start = $this->input->get('page');
	    $arg=array();
	    
	    $arg['user_ids']=$tmp_u_ids_str;
	    $arg['search_name']=$this->input->get('search_name');
	    $arg['search_user_or_admin']=$this->input->get('search_user_or_admin');
	    $arg['search_status']=$this->input->get('search_status');
	    $arg['manager_auto_id']=$this->input->get('manager_auto_id');
	    $arg['filter_sort_by']=$this->input->get('filter_sort_by');
		$arg['is_show_only_active_user']=$this->input->get('is_show_only_active_user');
	    $this->load->library('pagination');
	    $limit=30;
	    $config = array();

	   //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->user_model->get_employee_list_count($arg);
	    
	    $config['per_page'] = $limit;
	    $config['uri_segment']=4;
	    $config['num_links'] = 1;
	    $config['use_page_numbers'] = TRUE;
	   //$config['full_tag_open'] = '<div id="paginator" class="dataTables_paginate paging_bootstrap">';
	    $config['attributes'] = array('class' => 'myclass');
	   //$config['full_tag_close'] = '</div>';
	   //$config['prev_link'] = '&lt;Previous';
	   //$config['next_link'] = 'Next&gt;';
	    
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

	   //$config['use_page_numbers'] = TRUE
	   //$start = $this->uri->segment(3);
	    $start=empty($start)?0:($start-1)*$limit;

	    $arg['limit']=$limit;
	    $arg['start']=$start;

	    $list['rows'] = $this->user_model->get_employee_list($arg);
	    $list['user_id']=$user_id;
	    $list['user_type']=$user_type;
	    $table = '';
	    $table = $this->load->view('admin/user/manage_employee_view_ajax',$list,TRUE);
	    $data =array (
	       "table"=>$table,
	       "page"=>$page_link,
	       "search_str"=>$list['rows']
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	// AJAX PAGINATION END

	function change_status_employee()
    {        
        $id = $this->input->post('id');
        $curr_status = $this->input->post('curr_status');       
        
        if($curr_status=='0')
        {
            $s='1';
        }
        else
        {
            $s='0';
        }        

        $update=array(
                    'status'=>$s,
                    'modify_date'=>date("Y-m-d H:i:s")
                    );
        $this->user_model->UpdateAdmin($update,$id);
        $result["status"] = 'success';
        echo json_encode($result);
        exit(0);        
    }


    // Department select dropdown html view
    function ajax_load_department_dropdown_html()
    {    
    	$selected_value=$this->input->post('selected_value');
        ##-------------------------------------------------------##
        ##-Prepare tree view of existing department into dropdown list -##
        $treeArray=$this->department_model->get_nlevel_array();
        $options = array(); //initializing array
        if(count($treeArray))
        {
            $i=0;
            foreach($treeArray as $id=>$cats)
            {                    
                $options[$i]=array(
                                    'id'        =>  $id,
                                    'name'      =>  $cats['category_name'],
                                    'parent_id' =>  $cats['parent_id'] 
                                   );
                $i++;                    
            }
        }        
        $params=array(
                    'selectbox_name'     =>'department_id',
                    'class'       =>'custom-select form-control',
                    'default_select_value'  => $selected_value,
                    'default_select_text'   => 'Select Department',
                    'multiple'              => FALSE
                    );        
        $selected_id=$data['department_id'];
        echo $this->common_functions->get_category_tree($options,array($selected_id),array(),$params);
        ##------------------------------------------------------------##
    }

    // Designation select dropdown html view
    function ajax_reset_department_select_option()
    {   
       $data=array(); 	
       $parent_id=$this->input->post('parent_id');
       $data['emp_id']=$this->input->post('emp_id');
       $data['parent_id']=$parent_id;
       $data['level']=$this->input->post('level');
       $data['department_key_value']=$this->department_model->get_key_val($parent_id);

       if(count($data['department_key_value'])){
       		echo $this->load->view('admin/user/department_select_option_ajax', $data, true);
       }
       else{
       	echo'';
       }      
        
    }
    // Designation select dropdown html view
    function ajax_load_child_department_dropdown_html()
    {   
       $data=array(); 	
       $parent_id=$this->input->post('parent_id');
       $data['emp_id']=$this->input->post('emp_id');
       $data['parent_id']=$parent_id;
       $data['level']=$this->input->post('level');
       $data['department_key_value']=$this->department_model->get_key_val($parent_id);

       if(count($data['department_key_value'])){
       		echo $this->load->view('admin/user/child_department_dropdown_view_ajax', $data, true);
       }
       else{
       	echo'';
       }      
        
    }

    // Manager select dropdown html view
    function ajax_load_manager_dropdown_html()
    {   
		$data=array();
		$data['selected_value']=$this->input->post('selected_value');
		$end_did=$this->input->post('department_id');
		$emp_id=$this->input->post('emp_id');
        $tmp_u_ids_arr=$this->user_model->get_self_and_under_employee_ids($emp_id,0);

        array_push($tmp_u_ids_arr,$emp_id);
        $tmp_u_ids_str=implode(',',$tmp_u_ids_arr);
		//$data['designation_key_val']=$this->user_model->get_designation_key_val();
		
		$all_selected_ids_arr=$this->department_model->get_all_selected_ids_arr($end_did);
		$remove = array(0);
		$tmp_arr = array_diff($all_selected_ids_arr, $remove);
		$final_selected_id_arr=array_reverse($tmp_arr);
		array_push($final_selected_id_arr, $end_did);
		$data['all_selected_ids_arr']=$final_selected_id_arr;
		$data['selected_parent_id']=$final_selected_id_arr[0];
		$data['all_department_arr']=$this->department_model->get_terr_array($final_selected_id_arr);
		$all_selected_department_arr=array();
		foreach($data['all_department_arr'] as $dept)
		{
			array_push($all_selected_department_arr, $dept['selected_id']);
		}
		$data['all_selected_department_arr']=$all_selected_department_arr;
		$all_selected_department_str=implode(",", $all_selected_department_arr);
		$data['all_selected_department_str']=$all_selected_department_str;
		// $data['department_wise_employee_key_val']=$this->user_model->get_department_wise_employee_key_val($all_selected_department_str,$emp_id);

        $filter_data=array('except_emp_ids'=>$tmp_u_ids_str);
        $data['department_wise_employee_key_val']=$this->user_model->get_employee_employee_key_val($filter_data);

		echo $this->load->view('admin/user/manager_dropdown_view_ajax', $data, true);        
    }

    // Designation select dropdown html view
    function ajax_load_designation_dropdown_html()
    {   
       $data=array(); 	
       $data['selected_value']=$this->input->post('selected_value');
       $data['designation_key_val']=$this->user_model->get_designation_key_val();
       echo $this->load->view('admin/user/designation_dropdown_view_ajax', $data, true);
        
    }

    // Functional Area select dropdown html view
    function ajax_load_functional_area_dropdown_html()
    {    
    	$data=array();
    	$data['selected_value']=$this->input->post('selected_value');
    	$data['functional_area_key_val']=$this->user_model->get_functional_area_key_val();
    	echo $this->load->view('admin/user/functional_area_dropdown_view_ajax', $data, true);
        
    }

    public function add_employee()
   	{
		// is_admin_session_data();
   		$data['page_title'] = "Manage Admin";
		$data['page_keyword'] = "Manage Admin";
		$data['page_description'] = "Manage Admin";
		$data['error_msg'] = '';
   	 	$data['message']=null;
   	 	$data['country_list']=$this->countries_model->GetCountriesList();
   	 	$data['currency_list'] = $this->product_model->GetCurrencyList(); 
   	 	//$data['department_list']=$this->user_model->GetUserDepartmentListAll();

		$command=$this->input->post('command');
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			if ($this->user_model->validate_form_data() == TRUE)
			{
				$employee_type_id=$this->input->post('employee_type_id');
				$branch_id=$this->input->post('branch_id');
				$department_id_arr=array_filter($this->input->post('department_id'));
				$department_id=end($department_id_arr);
				$manager_id=$this->input->post('manager_id');
				$designation_id=$this->input->post('designation_id');
				$functional_area_id=$this->input->post('functional_area_id');
				$user_type=$this->input->post('user_type');
				$name=$this->input->post('name');
				$email=$this->input->post('email');
				$mobile=$this->input->post('mobile');
				$joining_date=$this->input->post('joining_date');
				$date_of_birth=$this->input->post('date_of_birth');
				$salary=$this->input->post('salary');
				$salary_currency_code=$this->input->post('salary_currency_code');
				$pan=$this->input->post('pan');
				$aadhar=$this->input->post('aadhar');
				$personal_email=$this->input->post('personal_email');
				$personal_mobile=$this->input->post('personal_mobile');
				$gender=$this->input->post('gender');
				$marital_status=$this->input->post('marital_status');
				$marriage_anniversary=($this->input->post('marriage_anniversary'))?$this->input->post('marriage_anniversary'):'';
				$spouse_name=($this->input->post('spouse_name'))?$this->input->post('spouse_name'):'';
				$address=$this->input->post('address');
				$country_id=$this->input->post('country_id');
				$state=$this->input->post('state');
				$city=$this->input->post('city');
				$username=$this->input->post('username');
				$password=$this->input->post('password');			
				
				$company_industry_id='1';				
				$image_filename=null;
					
				$config = array(
		           'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/admin/",
		           'allowed_types' => "gif|jpg|png|jpeg|pdf",
		           'overwrite' => TRUE,
		           'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
		           'max_height' => "1768",
		           'max_width' => "10024"
		        );
		       $this->load->library('upload', $config);
		       $this->load->library('image_lib',''); //initialize image library
			   $this->upload->initialize($config);
		   
				if($this->upload->do_upload('image'))
				{
				    $image['image_upload']=array('upload_data' => $this->upload->data()); //Image Upload				 	       
					$image_filename=$image['image_upload']['upload_data']['file_name']; //Image Name					
					$config=NULL;									
					$config['source_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/admin/".$image_filename;
					$config['new_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/admin/thumb/".$image_filename;
					$config['height']	= '120';
					$this->image_lib->initialize($config); 
					$this->image_lib->resize();
					
				}


				// $date=date_create($joining_date);
				// $joining_date= date_format($date,"Y-m-d");

				// $date=date_create($date_of_birth);
				// $date_of_birth= date_format($date,"Y-m-d");

				// $date=date_create($marriage_anniversary);
				// $marriage_anniversary= date_format($date,"Y-m-d");

				$joining_date=date_display_format_to_db_format($joining_date);
				$date_of_birth= date_display_format_to_db_format($date_of_birth);
				$marriage_anniversary= date_display_format_to_db_format($marriage_anniversary);
				
				//$date=date_create($next_appraisal_date);
				//$next_appraisal_date= date_format($date,"Y-m-d");					
				
				$lms_url=$this->session->userdata['admin_session_data']['lms_url'];
				$company_name=$this->session->userdata['admin_session_data']['company_name'];
				$user_data=array(
								'username'=>$username,
								'employee_type_id'=>$employee_type_id,
								'branch_id'=>$branch_id,
								'department_id'=>$department_id,
								'manager_id'=>$manager_id,
								'designation_id'=>$designation_id,
								'functional_area_id'=>$functional_area_id,
								'user_type'=>$user_type,
								'name'=>$name,
								'email'=>$email,
								'mobile'=>$mobile,
								'joining_date'=>$joining_date,
								'date_of_birth'=>$date_of_birth,
								'salary'=>$salary,
								'salary_currency_code'=>$salary_currency_code,
								'pan'=>$pan,
								'aadhar'=>$aadhar,
								'personal_email'=>$personal_email,
								'personal_mobile'=>$personal_mobile,
								'gender'=>$gender,
								'marital_status'=>$marital_status,
								'marriage_anniversary'=>$marriage_anniversary,
								'spouse_name'=>$spouse_name,
								'address'=>$address,
								'country_id'=>$country_id,
								'state'=>$state,
								'city'=>$city,
								'password'=>md5($password),
								'photo'=>$image_filename,
				                'lms_url'=>$lms_url,
				                'company_name'=>$company_name,
				                'create_date'=>date('Y-m-d')
								);
				// print_r($user_data); die();
				$result=$this->user_model->CreateAdmin($user_data);	
				if($result)
				{
					//CheckUserSpace();
					$session_data=$this->session->userdata('admin_session_data');
					$update_by=$this->session->userdata['admin_session_data']['user_id'];
					// New User Creation Mail id 7
					$email_forwarding_setting=$this->Email_forwarding_setting_model->GetDetails(7);
					$m_email=get_manager_and_skip_manager_email_arr($update_by);

					if($email_forwarding_setting['is_mail_send']=='Y')
					{
						$company=get_company_profile();
						$e_data['company']=$company;
						$e_data['assigned_to_user']=$admin_session_data_user_data;
						$e_data['customer']=$customer;
						$e_data['name']=$name;
						$e_data['gender']=$gender;
						$e_data['user_id']=$result;
						$e_data['password']=$password;
						$e_data['username']=$username;

				        $template_str = $this->load->view('admin/email_template/template/user_create', $e_data, true);

				        $cc_mail_arr=array();
				        $self_cc_mail=get_value("email","user","id=".$update_by);
				        //$update_by_name=get_value("name","user","id=".$assigned_user_id);
				        // --------------------
				        // cc mail assign logic
				        if($email_forwarding_setting['is_send_relationship_manager']=='Y')
				        {
				        	array_push($cc_mail_arr, $self_cc_mail);
				        }

				        if($email_forwarding_setting['is_send_manager']=='Y')
				        {
				        	if($m_email['manager_email']!='')
				        	{		        		
				        		array_push($cc_mail_arr, $m_email['manager_email']);
				        	}		        	
				        }

				        if($email_forwarding_setting['is_send_skip_manager']=='Y')
				        {
				        	if($m_email['skip_manager_email']!='')
				        	{		        		
				        		array_push($cc_mail_arr, $m_email['skip_manager_email']);
				        	}		        	
				        }
				        $cc_mail='';
				        $cc_mail=implode(",", $cc_mail_arr);
				        // cc mail assign logic
				        // -------------------- 

				        $this->load->library('mail_lib');
				        $m_d = array();
				        //$m_d['from_mail']     = $this->session->userdata['admin_session_data']['email'];
				        //$m_d['from_name']     = $company_name;
				        $m_d['from_mail']     = $session_data['email'];
	    				$m_d['from_name']     = $session_data['name'];
				        $m_d['to_mail']       = $personal_email;
				        $m_d['cc_mail']       = $cc_mail; 
				        $m_d['subject']       = 'Welcome to '.$company_name;
				        $m_d['message']       = $template_str;
				        $m_d['attach']        = array();
				        $mail_return = $this->mail_lib->send_mail($m_d);
					}
					
					$msg = "One record successfully inserted..";
                	$this->session->set_flashdata('success_msg', $msg);
					redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_employee');
					
				}
				else
				{						
					$msg = "Oops! No record inserted. Try again.."; // 'duplicate';
                	$this->session->set_flashdata('error_msg', $msg);                    
                	redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/add_employee');
				}
			}
			else
			{
			   //$this->form_validation->set_error_delimiters('', '');
                $msg = validation_errors(); // 'duplicate';
                $data['error_msg'] = $msg;
                //$this->session->set_flashdata('error_msg', $msg);                    
                //redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/add_employee');
			}
			
		}		

		##-------------------------------------------------------##
        ##-Prepare tree view of existing department into dropdown list -##
        /*$treeArray=$this->department_model->get_nlevel_array();
        $options = array(); //initializing array
        if(count($treeArray))
        {
            $i=0;
            foreach($treeArray as $id=>$cats)
            {                    
                $options[$i]=array(
                                    'id'        =>  $id,
                                    'name'      =>  $cats['category_name'],
                                    'parent_id' =>  $cats['parent_id'] 
                                   );
                $i++;                    
            }
        }        
        $params=array(
                    'selectbox_name'     =>'department_id',
                    'class'       =>'custom-select form-control',
                    'default_select_value'  => '',
                    'default_select_text'   => 'Select Department',
                    'multiple'              => FALSE
                    );        
        $selected_id=$data['department_id'];
        $data['tree_view_dropdown'] = $this->common_functions->get_category_tree($options,array($selected_id),array(),$params);*/
        ##------------------------------------------------------------##

        //$data['designation_key_val']=$this->user_model->get_designation_key_val();
        //$data['functional_area_key_val']=$this->user_model->get_functional_area_key_val();
        $data['department_0_level_key_value']=$this->department_model->get_key_val(0);
		$data['employee_type']=$this->user_model->GetEmployeeType();
		$data['branch_list']=$this->user_model->GetBranchList();
		$this->load->view('admin/user/add_employee',$data);		
    }

	public function edit_employee($id=null)
	{
		// is_admin_session_data();

		$data['error_msg'] 			= "";
		$data['page_title'] 		= "Edit Admin";
		$data['page_keyword'] 		= "Edit Admin";
		$data['page_description']   = "Edit Admin";   
		$data['submitted_from']   = $this->router->fetch_method();	 	
		 	$data['currency_list'] = $this->product_model->GetCurrencyList(); 
		 	//$data['department_list']=$this->user_model->GetUserDepartmentListAll();
		//$command=$this->input->post('command');

		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$submitted_from = $this->input->post('submitted_from');
			if ($this->user_model->validate_form_data() == TRUE)
			{
				//$department_id=($this->input->post('department_id'))?$this->input->post('department_id'):$this->input->post('parent_id');
				$employee_type_id=$this->input->post('employee_type_id');
				$branch_id=$this->input->post('branch_id');
				$is_department_change=$this->input->post('is_department_change');
				$department_id_arr=array_filter($this->input->post('department_id'));
				$department_id=end($department_id_arr);
				$manager_id=$this->input->post('manager_id');
				$designation_id=$this->input->post('designation_id');
				$functional_area_id=$this->input->post('functional_area_id');
				$existing_profile_image = $this->input->post('existing_profile_image');
				$user_type=$this->input->post('user_type');
				$name=$this->input->post('name');
				$email=$this->input->post('email');
				$mobile=$this->input->post('mobile');
				$joining_date=$this->input->post('joining_date');
				$date_of_birth=$this->input->post('date_of_birth');
				$salary=$this->input->post('salary');
				$salary_currency_code=$this->input->post('salary_currency_code');
				$pan=$this->input->post('pan');
				$aadhar=$this->input->post('aadhar');
				$personal_email=$this->input->post('personal_email');	
				$personal_mobile=$this->input->post('personal_mobile');	
				$gender=$this->input->post('gender');
				$marital_status=$this->input->post('marital_status');
				$marriage_anniversary=$this->input->post('marriage_anniversary');
				$spouse_name=$this->input->post('spouse_name');
				$address=$this->input->post('address');
				$country_id=$this->input->post('country_id');
				$state=$this->input->post('state');
				$city=$this->input->post('city');				
				$company_industry_id='1';			
				$err_msg="";
				$lms_url=$this->session->userdata['admin_session_data']['lms_url'];
				$company_name=$this->session->userdata['admin_session_data']['company_name'];
				$id=$this->input->post('id');
				$new_password=trim($this->input->post('password'));				
				
				
					
				if($err_msg=="")
				{	

					$joining_date=date_display_format_to_db_format($joining_date);
					$date_of_birth= date_display_format_to_db_format($date_of_birth);
					$marriage_anniversary= date_display_format_to_db_format($marriage_anniversary);

					if($is_department_change=='Y'){
						$user_data=array(
								'employee_type_id'=>$employee_type_id,
								'branch_id'=>$branch_id,
								'department_id'=>$department_id,
								'manager_id'=>$manager_id,
								'designation_id'=>$designation_id,
								'functional_area_id'=>$functional_area_id,
								'user_type'=>$user_type,
								'name'=>$name,
								'email'=>$email,
								'designation'=>$designation,
								'mobile'=>$mobile,
								'joining_date'=>$joining_date,
								'date_of_birth'=>$date_of_birth,
								'salary'=>$salary,
								'salary_currency_code'=>$salary_currency_code,
								'pan'=>$pan,
								'aadhar'=>$aadhar,
								'personal_email'=>$personal_email,
								'personal_mobile'=>$personal_mobile,
								'gender'=>$gender,
								'marital_status'=>$marital_status,
								'marriage_anniversary'=>$marriage_anniversary,
								'spouse_name'=>$spouse_name,
								'address'=>$address,
								'country_id'=>$country_id,
								'state'=>$state,
								'city'=>$city,
								'lms_url'=>$lms_url,
								'company_name'=>$company_name,									
								'modify_date'=>date('Y-m-d H:i:s')
								);
					}
					else
					{
						$user_data=array(
								'employee_type_id'=>$employee_type_id,
								'branch_id'=>$branch_id,
								'manager_id'=>$manager_id,
								'designation_id'=>$designation_id,
								'functional_area_id'=>$functional_area_id,
								'user_type'=>$user_type,
								'name'=>$name,
								'email'=>$email,
								'designation'=>$designation,
								'mobile'=>$mobile,
								'joining_date'=>$joining_date,
								'date_of_birth'=>$date_of_birth,
								'salary'=>$salary,
								'salary_currency_code'=>$salary_currency_code,
								'pan'=>$pan,
								'aadhar'=>$aadhar,
								'personal_email'=>$personal_email,
								'personal_mobile'=>$personal_mobile,
								'gender'=>$gender,
								'marital_status'=>$marital_status,
								'marriage_anniversary'=>$marriage_anniversary,
								'spouse_name'=>$spouse_name,
								'address'=>$address,
								'country_id'=>$country_id,
								'state'=>$state,
								'city'=>$city,
								'lms_url'=>$lms_url,
								'company_name'=>$company_name,									
								'modify_date'=>date('Y-m-d H:i:s')
								);
					}
					
					//print_r($user_data); die();
					$this->user_model->UpdateAdmin($user_data,$id);	

					if($_FILES['image']['tmp_name'])
					{
						$image_filename=NULL;					
						$config = array(
									   'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/admin/",
									   'allowed_types' => "gif|jpg|png|jpeg|pdf",
									   'overwrite' => TRUE,
									   'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
									   // 'max_height' => "1768",
									   // 'max_width' => "10024"
									    );

						$this->load->library('upload', $config);
					    $this->load->library('image_lib',''); //initialize image library
					    $this->upload->initialize($config);

					    if (!$this->upload->do_upload('image')) 
					    {
					        $error = $this->upload->display_errors();
					        print_r($error);
							exit;
					        //$this->load->view('upload_form', $error);
					    } 
					    else
					    {
					    	$image['image_upload']=array('upload_data' => $this->upload->data()); //Image Upload							   
							$image_filename=$image['image_upload']['upload_data']['file_name']; //Image Name							
							$config=NULL;

							$config['source_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/admin/".$image_filename;
							$config['new_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/admin/thumb/".$image_filename;
							$config['height']	= '120';
							$this->image_lib->initialize($config); 
							$this->image_lib->resize();

							$user_data_img=array(
			                 				'photo'=>$image_filename,
							 				'modify_date'=>date('Y-m-d H:i:s')
							 				);
							$result=$this->user_model->UpdateAdmin($user_data_img,$id);	
							$session_array=array('photo'=>$image_filename);	

							if($this->session->userdata['admin_session_data']['user_id']==$id){
				   				$this->session->userdata['admin_session_data']['personal_photo']=$image_filename;
							}
							
							if($this->session->userdata['admin_session_data']['user_id']=='1'){
				   				$this->session->userdata['admin_session_data']['photo']=$image_filename;
							}

				   			//#############################################################//    
					        //############## DELETE EXISTING IMAGE IF ANY #################//
					        if($existing_profile_image!="")
					        {  
					        	@unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/admin/'.$existing_profile_image);
					            @unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/admin/thumb/'.$existing_profile_image);
					        }
					        //#############################################################//
					    }
					}

						

					$msg = "Record successfully updated..";
                    $this->session->set_flashdata('success_msg', $msg);

                    if($this->session->userdata['admin_session_data']['user_id']==$id){
						$this->session->userdata['admin_session_data']['name']=$name;
					}



                    if($new_password!="")
                    {
                        $user_data_pass=array(
                                        'password'=>md5($new_password),
                                        'modify_date'=>date('Y-m-d H:i:s')
                                        );
                        $this->user_model->UpdateAdmin($user_data_pass,$id);
                        
                        // Mail Script
                        
                        $session_data=$this->session->userdata('admin_session_data');
                        $company=get_company_profile(); 
                        $e_data['company']=$company;
                        $e_data['email_to']=$name;
                        $e_data['email_subject']='Username/Password';
                        $e_data['email_body']='Your account login details<br/><br/><B>Username/ID:</B> '.$id.' <B>Password:</B> '.$new_password;

                        $template_str = $this->load->view('admin/email_template/template/common_template_layout', $e_data, true);
                        $this->load->library('mail_lib');
                        $m_d = array();                 
                        $m_d['from_mail']     = $session_data['email'];
                        $m_d['from_name']     = $session_data['name'];
                        $m_d['to_mail']       = $email;
                        $m_d['subject']       = 'Username/Password';
                        $m_d['message']       = $template_str;
                        $m_d['attach']        = array();
                        $mail_return = $this->mail_lib->send_mail($m_d);
                        // End
                    }
					redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/edit_employee/'.$id);
					// CheckUserSpace();
					// if($submitted_from=='manage_store')
				    //             {
				                	                    
				    //             	redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_store');
				    //             }
				    //             else
				    //             {
				    //             	redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_employee');
				    //             }
					
				
				}
				else
				{
					$data['message']=$err_msg;					
				}
			}
			else
			{
				//$this->form_validation->set_error_delimiters('', '');
                $msg = validation_errors(); // 'duplicate';
                $data['error_msg'] = $msg;
                if($submitted_from=='manage_store')
                {
                	$this->session->set_flashdata('error_msg', $msg);                    
                	redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_store');
                }
                //$this->session->set_flashdata('error_msg', $msg);                    
                //redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/add_employee');
			}			
		}
			
		$admin_data['data']=$this->user_model->GetAdminData($id);

		$data['admin_id']=$id;
		$data['message']="";
	 	if($admin_data['data'])
	 	{
	   	 	foreach($admin_data['data'] as $val)
			{
				$data['id']=$val->id;
				$data['username']=$val->username;
				$data['employee_type_id']=$val->employee_type_id;
				$data['branch_id']=$val->branch_id;
				$data['department_id']=$val->department_id;
				$data['manager_id']=$val->manager_id;
				$data['designation_id']=$val->designation_id;
				$data['functional_area_id']=$val->functional_area_id;
				$data['name']=$val->name;			
				$data['company_name']=$val->company_name;
				$data['mobile']=$val->mobile;
				$data['password']=$val->password;
				$data['email']=$val->email;
				$data['photo']=$val->photo;
				$data['personal_email']=$val->personal_email;
				$data['personal_mobile']=$val->personal_mobile;
				$data['gender']=$val->gender;	
				$data['user_type']=$val->user_type;
				$data['address']=$val->address;
				$data['country_id']=$val->country_id;
				$data['state']=$val->state;
				$data['city']=$val->city;
				$data['website']=$val->website;
				$data['company_profile']=$val->company_profile;
				$data['date_of_birth']=$val->date_of_birth;
				$data['marital_status']=$val->marital_status;
				$data['marriage_anniversary']=$val->marriage_anniversary;
				$data['spouse_name']=$val->spouse_name;
				$data['salary']=$val->salary;
				$data['salary_currency_code']=$val->salary_currency_code;
				$data['pan']=$val->pan;
				$data['aadhar']=$val->aadhar;
				$data['joining_date']=$val->joining_date;
				$data['next_appraisal_date']=$val->next_appraisal_date;
				$data['sales_target_revenue']=$val->sales_target_revenue;
				$data['sales_target_revenue_type']=$val->sales_target_revenue_type;				
				$data['sales_target_no_of_deal']=$val->sales_target_no_of_deal;
				$data['target_setting']=$val->target_setting;

				
			}
		}

			
		$data['country_list']=$this->countries_model->GetCountriesList();
		$data['state_list']=$this->states_model->GetStatesList($data['country_id']);
		$data['city_list']=$this->cities_model->GetCitiesList($data['state']);	

		
	    $end_did=$data['department_id'];
	    $all_selected_ids_arr=$this->department_model->get_all_selected_ids_arr($end_did);
	    //print_r($all_selected_ids_arr); die();
	    $remove = array(0);
	    $tmp_arr = array_diff($all_selected_ids_arr, $remove);
	    $final_selected_id_arr=array_reverse($tmp_arr);
	    array_push($final_selected_id_arr, $end_did);
	    $data['all_selected_ids_arr']=$final_selected_id_arr;
	    $data['selected_parent_id']=$final_selected_id_arr[0];

	    $data['all_department_arr']=$this->department_model->get_terr_array($final_selected_id_arr);

	    $data['admin_session_data_data']=$session_data=$this->session->userdata('admin_session_data');
	    $data['user_id']=$this->session->userdata['admin_session_data']['user_id'];
		$data['department_0_level_key_value']=$this->department_model->get_key_val(0);
		$data['employee_type']=$this->user_model->GetEmployeeType();
		$data['branch_list']=$this->user_model->GetBranchList();
		$this->load->view('admin/user/edit_employee',$data);		
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

    function ajax_load_existing_department_edit_view()
    {
    	$end_did=$this->input->post('end_did');    	
    	$data=array();
    	$data['department_key_value']=$this->department_model->get_key_val(0);

    	$all_selected_ids_arr=$this->department_model->get_all_selected_ids_arr($end_did);
	    $remove = array(0);
	    $tmp_arr = array_diff($all_selected_ids_arr, $remove);
	    $final_selected_id_arr=array_reverse($tmp_arr);
	    array_push($final_selected_id_arr, $end_did);
	    $data['all_selected_ids_arr']=$final_selected_id_arr;
    	$html=$this->load->view('admin/user/department_edit_view_ajax', $data, true);
    	
    	$data =array (
           "html"=>$html
            );
       	
        $this->output->set_content_type('application/json');
        echo json_encode($data);
    }

    public function delete_employee($id)
    {
  		is_admin_session_data();
  		$user_data=array('status'=>'2',
					     'modify_date'=>date('Y-m-d')
						);									 
  		$result=$this->user_model->DeleteAdmin($user_data,$id);	
			
		if($result)
		{	
			$msg = "Employee successfully deleted!";
            $this->session->set_flashdata('success_msg', $msg);
			redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_employee');			
		}
		else
		{
			$msg="Sorry! can't delete";		
			$this->session->set_flashdata('error_msg', $msg); 	
			redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_employee');
		}
    }

    // Duplicate persinal email check
    function personal_email_duplicate_check()
    {
    	if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$personal_email=$this->input->post('personal_email');
			$return = $this->user_model->duplicate_personal_email_check($personal_email);

			if($return==0)
			{
				$msg='';
				$status=0;
			}
			else
			{
				$msg='Oops! Email already exist.';
				$status=1;
			}

			$data =array (
	           "msg"=>$msg,
	           "status"=>$status
            );
       	
	        $this->output->set_content_type('application/json');
	        echo json_encode($data);
		}    	
    }

    // Duplicate persinal mobile check
    function personal_mobile_duplicate_check()
    {
    	if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$personal_mobile=$this->input->post('personal_mobile');
			$return = $this->user_model->duplicate_personal_mobile_check($personal_mobile);

			if($return==0)
			{
				$msg='';
				$status=0;
			}
			else
			{
				$msg='Oops! Mobile already exist.';
				$status=1;
			}

			$data =array (
	           "msg"=>$msg,
	           "status"=>$status
            );
       	
	        $this->output->set_content_type('application/json');
	        echo json_encode($data);
		}    	
    }

    // Manage permission
    public function manage_permission($id)
    {
   		
		$data['success_msg'] = '';
		$data['error_msg'] = '';
   		$data['page_title'] = "User Permission";
		$data['page_keyword'] = "User Permission";
		$data['page_description'] = "User Permission";

		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{    
            $session_data = $this->session->userdata('admin_session_data');
			$this->user_model->set_permission();

            $this->session->unset_userdata('permission_data','');
            $get_user_access_data =  $this->user_model->get_user_permission($id);
            $this->session->set_userdata('permission_data',$get_user_access_data);


			$this->session->set_flashdata('success_msg', 'User permission successfully updated.');			
			redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/manage_permission/'.$id,'refresh');			
		}

		$data['user_id']=$id;
		$data['user_data']=$this->user_model->GetAdminData($id);  
		$data['user_permission'] = $this->user_model->get_user_permission($id); 
		$data['chk_controller_method'] = $this->user_model->get_user_permission_method($id); 
		$data['chk_controller_method2'] = $this->user_model->get_user_permission_method2($id); 
		$data['admin_level_attribute'] = $this->config->item('admin_level_attribute');

		$data['user_wise_permission_keyword_arr'] = $this->user_model->get_user_wise_permission($id); 

		// ----------------------- MENU -----------------------------
        $available_ids = array();  
        foreach($data['user_permission']['menu'] as $menu)
        {
            array_push($available_ids, $menu['menu_id']);
        }
        $data['available_menu_id_str'] = implode(",",$available_ids);
        $data['available_menu_id_arr'] = $available_ids;
        // ------------------------ MENU -----------------------------

        // ----------------------- NON MENU -----------------------------
        $available_attr_ids = array();  
        foreach($data['user_permission']['non_menu'] as $non_menu)
        {
            array_push($available_attr_ids, $non_menu['package_attribute_id']);
        }
        $data['available_non_menu_id_str'] = implode(",",$available_attr_ids);
        $data['available_non_menu_id_arr'] = $available_attr_ids;
        // ------------------------ NON MENU -----------------------------

		$data['non_menu_disable_id_arr'] = get_non_menu_disable_ids();
   		$this->load->view('admin/user/manage_user_permission',$data);
    }

    // ==========================================================
	// ============ EMPLOYEE SECTION END ========================
	// ==========================================================


	// ==========================================================
	// ============ USER SECTION START ==========================
	// ==========================================================

	public function dashboard()
	{
		is_admin_session_data();
		redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/dashboard/');
	} 

	public function dashboard2()
	{
		is_admin_session_data();
		$data = array(); 
		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		$user_type = $session_data['user_type'];


		// ===========================================================
		// COUNT SUMMARY REPORT
		$total_active_leads = $this->lead_model->active_leads_summary($user_type,$user_id); 

		$action_pending = ($total_active_leads['total_active_leads']-$total_active_leads['total_action_leads']);
		$action_pending_per = ($action_pending/$total_active_leads['total_active_leads'])*100;
		$data['total_active_leads'] = $total_active_leads['total_active_leads'];
		$data['action_pending_per'] = round($action_pending_per);


		$frm_date = date('Y-m-d', strtotime(date('Y-m-d') .' -7 day'));
		$to_date  = date('Y-m-d');

		$followup_summary = $this->lead_model->followup_summary_last_7days($user_type,$user_id,$frm_date,$to_date);

		$followup_per = ($followup_summary['not_followup']/$followup_summary['followup'])*100;
		$data['total_followup_leads'] = $followup_summary['followup'];
		$data['not_followup_per'] = $followup_per;

		$converted_leads = $this->lead_model->converted_leads_summary($user_type,$user_id);		
		$converted_leads_per = ($converted_leads['converted_count']/$converted_leads['total_count'])*100;
		$not_converted_leads_per = ($converted_leads['not_converted_count']/$converted_leads['total_count'])*100;
		$data['total_converted_leads'] = ($converted_leads['converted_count'])?$converted_leads['converted_count']:0;
		$data['converted_leads_per'] = round($converted_leads_per);
		$data['total_notConverted_leads'] = ($converted_leads['not_converted_count'])?$converted_leads['not_converted_count']:0;
		$data['not_converted_leads_per'] = round($not_converted_leads_per);
		// COUNT SUMMARY REPORT
		// ===========================================================


		// ===========================================================
		// My Calendar / Appointments

		$data['my_calender'] = $this->lead_model->my_calender_summary($user_type,$user_id);
		$data['customer_contacts'] = $this->customer_model->customer_contacts_summary($user_type,$user_id);

		

		// My Calendar / Appointments
		// ===========================================================

		$this->load->view('dashboard2',$data);
	}

	public function upgrade()
	{
		is_admin_session_data();
		$data = array(); 
		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		$user_type = $session_data['user_type'];
		
		$data['package_list']=$this->Package_model->GetPackageList();
		$data['package_attribute_list']=$this->Package_model->GetPackageAttributeList();
		$data['package_name_list']=$this->Package_model->GetPackageAttributeNameList();
		$data['package_current_data']=$this->Package_model->GetCurrentPackageData();

		$this->load->view('upgrade',$data);
	}

	public function change_package($id)
	{
		is_admin_session_data();
		$data = array(); 
		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		$user_type = $session_data['user_type'];	
		
		$package_current_data=$this->Package_model->GetCurrentPackageData();
		
		$package_data=$this->Package_model->GetPackageData($id);
		$expiry_datetime=$package_current_data->expire_date;
		if($package_current_data->package_name!=$package_data->package_name)
		{
		
			if($package_current_data->package_name=='Free')
			{
				$this->Package_model->UpdatePackageUser();
				$purchased_datetime=date('Y-m-d H:i:s');
			
				$expiry_datetime=date('Y-m-d H:i:s', strtotime($purchased_datetime. ' + 1 years'));
			}
			else
			{
				$purchased_datetime=date('Y-m-d H:i:s', strtotime($expiry_datetime. ' + 1 days'));
			
				$expiry_datetime=date('Y-m-d H:i:s', strtotime($expiry_datetime. ' + 1 years'));
			}
						
						
			$pckg_data=array('package_name'=>$package_data->package_name,'package_price'=>$package_data->package_price,'purchased_datetime'=>$purchased_datetime,'expire_date'=>$expiry_datetime);
			$new_pckg=$this->Package_model->CreatePackageUser($pckg_data);
			
			$package_attr_list=$this->Package_model->GetAttributeListByPackage($id);
			foreach($package_attr_list as $package_attr_data)
			{
				if($package_attr_data->reserved_keyword=='')
				{
					$package_attr_data->reserved_keyword='';
				}
				if($package_attr_data->attribute_name=='')
				{
					$package_attr_data->attribute_name='';
				}
				$pckg_attr_data=array('package_order_id'=>$new_pckg,'package_id'=>$package_attr_data->package_id,'package_attribute_id'=>$package_attr_data->package_attribute_id,'attribute_name'=>$package_attr_data->attribute_name,'reserved_keyword'=>$package_attr_data->reserved_keyword,'display_value'=>$package_attr_data->display_value,'calculative_value'=>$package_attr_data->calculative_value,'calculative_value_unit'=>$package_attr_data->calculative_value_unit,'is_menu'=>$package_attr_data->is_menu,'menu_id'=>$package_attr_data->menu_id,'sub_menu_ids'=>$package_attr_data->sub_menu_ids);
				
				$new_pckg_attr=$this->Package_model->CreatePackageAttribute($pckg_attr_data);
			}
						
		}
		$lms_url=$this->session->userdata['admin_session_data']['lms_url'];
		redirect(base_url().$lms_url.'/user/upgrade/');
	} 
	
	public function package_autochange()
	{
		is_admin_session_data();
		$data = array();
		$id='1'; 
		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		$user_type = $session_data['user_type'];
		
		$package_current_data=$this->Package_model->GetCurrentPackageData();
		
		$package_data=$this->Package_model->GetPackageData($id);
		$expiry_datetime=$package_current_data->expire_date;
		if($package_data->package_name)
		$purchased_datetime=date('Y-m-d H:i:s', strtotime($expiry_datetime. ' + 1 days'));
		
		$expiry_datetime=date('Y-m-d H:i:s', strtotime($expiry_datetime. ' + 1 years'));
		
		$pckg_data=array('package_name'=>$package_data->package_name,'package_price'=>$package_data->package_price,'purchased_datetime'=>$purchased_datetime,'expire_date'=>$expiry_datetime);
		$new_pckg=$this->Package_model->CreatePackageUser($pckg_data);
		
		$package_attr_list=$this->Package_model->GetAttributeListByPackage($id);
		foreach($package_attr_list as $package_attr_data)
		{
			if($package_attr_data->reserved_keyword=='')
			{
				$package_attr_data->reserved_keyword='';
			}
			if($package_attr_data->attribute_name=='')
			{
				$package_attr_data->attribute_name='';
			}
			$pckg_attr_data=array('package_order_id'=>$new_pckg,'package_id'=>$package_attr_data->package_id,'package_attribute_id'=>$package_attr_data->package_attribute_id,'attribute_name'=>$package_attr_data->attribute_name,'reserved_keyword'=>$package_attr_data->reserved_keyword,'display_value'=>$package_attr_data->display_value,'calculative_value'=>$package_attr_data->calculative_value,'calculative_value_unit'=>$package_attr_data->calculative_value_unit,'is_menu'=>$package_attr_data->is_menu,'menu_id'=>$package_attr_data->menu_id,'sub_menu_ids'=>$package_attr_data->sub_menu_ids);
			
			$new_pckg_attr=$this->Package_model->CreatePackageAttribute($pckg_attr_data);
		}
					
		$lms_url=$this->session->userdata['admin_session_data']['lms_url'];
		redirect(base_url().$lms_url.'/user/upgrade/');
	} 

	
	
    public function manage_store()
   	{	die('o');
	   	is_admin_session_data();
   	 	$data['page_title'] = "Manage Store";
		$data['page_keyword'] = "Manage Store";
		$data['page_description'] = "Manage Store";
		$data['submitted_from']   = $this->router->fetch_method();		
		$admin_data['data']=$this->user_model->GetSuperAdminData();   	 	
   	 	$data['currency_list'] = $this->product_model->GetCurrencyList(); 
		$data['message']="";
   	 	if($admin_data['data'])
   	 	{
	   	 	foreach($admin_data['data'] as $val)
			{
				$data['admin_id']=$val->id;
				$data['id']=$val->id;
				$data['name']=$val->name;	
				$data['designation']=$val->designation;				
				$data['company_name']=$val->company_name;
				$data['mobile']=$val->mobile;
				$data['password']=$val->password;
				$data['email']=$val->email;
				$data['photo']=$val->photo;
				$data['gender']=$val->gender;	
				$data['user_type']=$val->user_type;
				$data['address']=$val->address;
				$data['country_id']=$val->country_id;
				$data['state']=$val->state;
				$data['city']=$val->city;
				$data['website']=$val->website;
				$data['company_profile']=$val->company_profile;
				$data['date_of_birth']=$val->date_of_birth;
				$data['marital_status']=$val->marital_status;
				$data['marriage_anniversary']=$val->marriage_anniversary;
				$data['spouse_name']=$val->spouse_name;
				$data['salary']=$val->salary;
				$data['salary_currency_code']=$val->salary_currency_code;
				$data['joining_date']=$val->joining_date;
				$data['next_appraisal_date']=$val->next_appraisal_date;
				$data['sales_target_revenue']=$val->sales_target_revenue;
				$data['sales_target_revenue_type']=$val->sales_target_revenue_type;				
				$data['sales_target_no_of_deal']=$val->sales_target_no_of_deal;
				$data['target_setting']=$val->target_setting;
				
			}
		}
		
		$data['country_list']=$this->countries_model->GetCountriesList();
		$data['state_list']=$this->states_model->GetStatesList($data['country_id']);
		$data['city_list']=$this->cities_model->GetCitiesList($data['state']);
		
		$this->load->view('admin/edit_admin',$data);
		
    }

    

    public function user_list_ajax()
    {
   		is_admin_session_data();
   		$data['page_title'] = "Admin";
		$data['page_keyword'] = "Admin";
		$data['page_description'] = "Admin";   		
   		$data['admin']=$this->user_model->GetUserListAll();		
   		$this->load->view('admin/user_list_ajax',$data);
    }

        

	public function profile_update()
	{
		$command=$this->input->get('command');
		if($command=='1')
		{
			$id=$this->input->get('id');
			$email=$this->input->get('email');
			$user_type=$this->input->get('user_type');
			$mobile=$this->input->get('mobile');
			$company_name=$this->input->get('company_name');
			$address=$this->input->get('address');
			$country_id=$this->input->get('country_id');
			$state=$this->input->get('state');
			$city=$this->input->get('city');
			$website=$this->input->get('website');
			
			$user_data=array(
		                 'company_name'=>$company_name,
						 'mobile'=>$mobile,					 
		                 'email'=>$email,	                 
		                 'address'=>$address,
		                 'country_id'=>$country_id,
		                 'state'=>$state,
		                 'city'=>$city,
		                 'website'=>$website,		                 
						 'modify_date'=>date('Y-m-d')
						 );
						 
			
			$result=$this->user_model->UpdateAdmin($user_data,$id);	
			//CheckUserSpace();
			redirect(base_url().'error/'.$this->session->userdata['admin_session_data']['lms_url'].'/limitexceed/');
			
			if($result)
			{
				echo '<div class="col-sm-4"><h4>'.$company_name.'</h4></div><div class="col-sm-4" style="border-left:3px solid #c0c0c0;"><p>'.$address.'</p></div><div class="col-sm-4" style="border-left:3px solid #c0c0c0;"><ul><li><img src="'.base_url('images/phone_img.png').'"/> '.$mobile.'</li><li><img src="'.base_url('images/mail_img.png').'"/> '.$email.'</li><li><img src="'.base_url('images/call_img.png').'"/> '.$website.'</li></ul></div>';
			}
			else
			{
				echo '2';
			}
		}
		else
		{
			redirect(base_url().'dashboard/'.$this->session->userdata['admin_session_data']['lms_url']);
		}
	}

	public function reset_profile_pic2($id=null)
    {
		$id=$this->session->userdata['admin_session_data']['user_id'];
		if($err_msg=="")
		{
			
			$image_filename=NULL;			
			$config = array(
		   'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/admin/",
		   'allowed_types' => "gif|jpg|png|jpeg|pdf",
		   'overwrite' => TRUE,
		   'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
		   'max_height' => "1768",
		   'max_width' => "10024"
		 	);
		   $this->load->library('upload', $config);
		   $this->upload->initialize($config);
			  
		   
				if($this->upload->do_upload('image'))
				{
					$image['image_upload']=array('upload_data' => $this->upload->data()); //Image Upload						   
					$image_filename=$image['image_upload']['upload_data']['file_name']; //Image Name					
					$config=NULL;								
					$config['source_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/admin/".$image_filename;
					$config['new_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/admin/thumb/".$image_filename;				
					$config['height']	= '120';
					$this->image_lib->initialize($config);
					$this->image_lib->resize();					
					$user_data=array(
		                 'photo'=>$image_filename,		                 
						 'modify_date'=>date('Y-m-d')
						 );					
			   		$this->session->userdata['admin_session_data']['photo']=$image_filename;
			   		$result=$this->user_model->UpdateAdmin($user_data,$id);	
			   	
				}
				
				if($result)
				{					
					echo "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/admin/thumb/".$image_filename;					
				}
		}
		else
		{
			echo "2";
			
		}
    } 

    public function reset_profile_pic($id=null)
    {
		$id=$this->session->userdata['admin_session_data']['user_id'];
		if($_FILES['image']['tmp_name'])
		{
			$image_filename=NULL;					
			$config = array(
						   'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/admin/",
						   'allowed_types' => "gif|jpg|png|jpeg|pdf",
						   'overwrite' => TRUE,
						   'max_size' => "2048000", // Can be set to particular file size , here it is 2 MB(2048 Kb)
						   'max_height' => "1768",
						   'max_width' => "10024"
						    );
			$this->load->library('upload', $config);
		    $this->load->library('image_lib',''); //initialize image library
		    $this->upload->initialize($config);
		    if($this->upload->do_upload('image'))
		    {
		    	$image['image_upload']=array('upload_data' => $this->upload->data()); //Image Upload							   
				$image_filename=$image['image_upload']['upload_data']['file_name']; //Image Name							
				$config=NULL;
				$config['source_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/admin/".$image_filename;
				$config['new_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/admin/thumb/".$image_filename;
				$config['height']	= '120';
				$this->image_lib->initialize($config); 
				$this->image_lib->resize();
				$user_data_img=array(
                 				'photo'=>$image_filename,
				 				'modify_date'=>date('Y-m-d H:i:s')
				 				);
				$result=$this->user_model->UpdateAdmin($user_data_img,$id);	
				$session_array=array('photo'=>$image_filename);	
				CheckUserSpace();
				if($this->session->userdata['admin_session_data']['user_id']==$id){
	   				$this->session->userdata['admin_session_data']['personal_photo']=$image_filename;
				}
				
				if($this->session->userdata['admin_session_data']['user_id']=='1'){
	   				$this->session->userdata['admin_session_data']['photo']=$image_filename;
				}
				
				echo base_url()."assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/admin/thumb/".$image_filename;

	   			//#############################################################//    
		        //############## DELETE EXISTING IMAGE IF ANY #################//
		        if($existing_profile_image!="")
		        {  
		        	@unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/admin/'.$existing_profile_image);
		            @unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/admin/thumb/'.$existing_profile_image);
		        }
		        //#############################################################//
		    }
		}
    } 

    
   
    public function update_notification()
    {
   		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		$notification_list=$this->notification_model->GetUnreadNotificationList($user_id);
		foreach($notification_list as $notification_data)
		{
			$data=array('read_date'=>date('Y-m-d H:i:s'),'notification_id'=>$notification_data->noti_id,'read_user_id'=>$user_id);
			$this->notification_model->CreateUserNotification($data);
		}   		
   		return true;
    }   
   
	public function load_more_notification()
	{
		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		$limit = $this->input->post('limit');
		$offset = $this->input->post('offset');

		$data['notification_list']=$this->notification_model->GetNotificationList($offset,0,$user_id);

		$this->load->view('notification_ajax',$data);      
    }
	
function delete_profile_pic()
{
	if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
	{
		$id=$this->input->post('id');	
		$existing_profile_image=get_value("photo","user","id=".$id);
		$user_data_img=array(
				'photo'=>'',
				'modify_date'=>date('Y-m-d H:i:s')
				);
		$return=$this->user_model->UpdateAdmin($user_data_img,$id);				
		if($return==true)
		{
			@unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/admin/'.$existing_profile_image);
	        @unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/admin/thumb/'.$existing_profile_image);
			$msg='Image successfully deleted.';
			$status=0;
		}
		else
		{
			$msg='Image can not deleted.';
			$status=1;
		}		
		$data =array (
           "msg"=>$msg,
           "status"=>$status
        );
   	
        $this->output->set_content_type('application/json');
        echo json_encode($data);
	}    	
}

public function download_csv()
{	
	$session_data=$this->session->userdata('admin_session_data');
	$user_id=$session_data['user_id'];
	$user_type=$session_data['user_type'];
	$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
	array_push($tmp_u_ids, $user_id);
	$tmp_u_ids_str=implode(",", $tmp_u_ids);
	$start=0;
	$arg=array();	    
	$arg['user_ids']=$tmp_u_ids_str;
	$arg['search_name']=$this->input->get('search_name');
	$arg['search_user_or_admin']=$this->input->get('search_user_or_admin');
	$arg['search_status']=$this->input->get('search_status');
	$arg['manager_auto_id']=$this->input->get('manager_auto_id');
	$arg['filter_sort_by']=$this->input->get('filter_sort_by');
	$limit=$this->user_model->get_employee_list_count($arg);
	$start=empty($start)?0:($start-1)*$limit;
	$arg['limit']=$limit;
	$arg['start']=$start;
	$rows = $this->user_model->get_employee_list($arg);	
	//print_r($rows); die('ok');
	
	$array[] = array('');
	$array[] = array(
					'User ID',
					'Employee Type',
					'Name',
					'Official Email',
					'Official Mobile',
					'Department',
					'Manager',
					'Designation',
					'Functional Area',
					'Joining Date',
					'Date of Birth',
					'Salary',
					'PAN No',
					'Aashar Card',
					'Personal Email',
					'Personal Mobile',
					'Gender',
					'Marital Status',
					'Marriage Anniversary',
					'Spouse Name',
					'Address',
					'Country',
					'State',
					'City',
					'Status'
					);
	
	if(count($rows) > 0)
	{
		foreach ($rows as $row) 
		{
			$currency_code=($row['salary'])?''.$row['salary_currency_code'].'':'';
			$array[] = array(
							$row['id'],
							($row['employee_type'])?$row['employee_type']:'N/A',
							($row['name'])?$row['name']:'N/A',
							($row['email'])?$row['email']:'N/A',
							($row['mobile'])?$row['mobile']:'N/A',
							($row['dept_name'])?$row['dept_name']:'N/A',
							($row['manager_name'])?$row['manager_name']:'N/A',
							($row['designation_name'])?$row['designation_name']:'N/A',
							($row['functional_area_name'])?$row['functional_area_name']:'N/A',
							($row['joining_date'])?date_db_format_to_display_format($row['joining_date']):'N/A',
							($row['date_of_birth'])?date_db_format_to_display_format($row['date_of_birth']):'N/A',	
							($row['salary'])?$currency_code.' '.$row['salary']:'N/A',
							($row['pan'])?$row['pan']:'N/A',
							($row['aadhar'])?$row['aadhar']:'N/A', 
							($row['personal_email'])?$row['personal_email']:'N/A',
							($row['personal_mobile'])?$row['personal_mobile']:'N/A',
							($row['gender'])?($row['gender']=='M')?'Male':'Female':'N/A',
							($row['marital_status'])?$row['marital_status']:'N/A',
							($row['marriage_anniversary'])?date_db_format_to_display_format($row['marriage_anniversary']):'N/A',
							($row['spouse_name'])?$row['spouse_name']:'N/A',
							($row['address'])?$row['address']:'N/A', 
							($row['country_name'])?$row['country_name']:'N/A',
							($row['state_name'])?$row['state_name']:'N/A',
							($row['city_name'])?$row['city_name']:'N/A',
							($row['status']==0)?'Active':'In-Active'
						);
		}
	}
	//print_r($array); die();
	$tmpName='user_list';
	$tmpDate =  date("YmdHis");
	$csvFileName = $tmpName."_".$tmpDate.".csv";

	$this->load->helper('csv');
	//query_to_csv($array, TRUE, 'Attendance_list.csv');
	array_to_csv($array, $csvFileName);
	return TRUE;
}



// =============================================
// ======= USER SECTION END ====================
// ============================================= 

	public function tag_service($id='')
	{		
		$data['page_title'] = "Admin";
		$data['page_keyword'] = "Admin";
		$data['page_description'] = "Admin"; 	
		$admin_session_data=$this->session->userdata('admin_session_data');
		// print_r($admin_session_data); die();
		$data['service_info']=$admin_session_data['service_info'];
		$data['all_user']=$this->user_model->GetAllUsersNotInTaggedService('0');
		$data['all_user_wise_service_order']=$this->user_model->all_user_wise_service_order();
		$this->load->view('admin/user/tag_service_view',$data);
	}

	function tag_user_wise_service($client_info=array())
    {        
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
			if(count($client_info))
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
			}
			else
			{
				$admin_session_data=$this->session->userdata('admin_session_data');
				$service_info=$admin_session_data['service_info'];
				if(count($service_info['all_available_service_order']))
				{
					foreach($service_info['all_available_service_order'] AS $all_available_service_order)
					{
						if($all_available_service_order['id']==$sod_id_to)
						{
							$no_of_user=$all_available_service_order['no_of_user'];
						}
					}
				}
			}
			

			$existing_no_of_user=$this->user_model->existing_no_of_user_tagged_service($sod_id_to,$client_info);
			// $result["msg"]=$no_of_user.'/'.$existing_no_of_user;
			if($no_of_user>$existing_no_of_user)
			{
				if($sod_id_from!='')
				{
					$this->user_model->delete_existing_tagged_service($user_id,$sod_id_from,$client_info);
				}
				$sod_info=$this->Client_model->get_service_order_detail($sod_id_to);
				$post_data=array(
					'user_id'=>$user_id,
					'service_id'=>$sod_info['service_id'],
					'service_order_detail_id'=>$sod_id_to
					);				
				$this->user_model->CreateTagService($post_data,$client_info);
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
				$this->user_model->delete_existing_tagged_service($user_id,$sod_id_from,$client_info);
			}
		}
        
        echo json_encode($result);
        exit(0);        
    }

	function rander_service_list_ajax()
	{
		$admin_session_data=$this->session->userdata('admin_session_data');
	    $s_id=$this->input->get('s_id');
		$list=array();
		$list['s_id']=$s_id;
		$list['service_info']=$admin_session_data['service_info'];
		$list['all_user']=$this->user_model->GetAllUsersNotInTaggedService('0',$s_id);
		$list['all_user_wise_service_order']=$this->user_model->all_user_wise_service_order();
		$html = $this->load->view('admin/user/tag_service_view_ajax',$list,TRUE);   		
	    $data =array (
	       "html"=>$html
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
}
