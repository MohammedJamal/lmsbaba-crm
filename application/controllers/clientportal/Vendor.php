<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor extends CI_Controller {

	private $api_access_token = '';	 
	function __construct()
	{
		parent :: __construct();
		is_admin_logged_in();
		init_admin_element();		
		$this->load->model(array("vendor_model","countries_model","states_model","cities_model","menu_model","Product_model","lead_model"));
		// permission checking
		// if(is_permission_available($this->session->userdata('service_wise_menu')[6]['menu_list']['menu_keyword'])===false){ 
		// 	redirect(admin_url().'dashboard', 'refresh');
		// 	exit(0);
		// }
		// end
	}

	public function index(){
		// $data['total_vendors']=$this->vendor_model->getVendos('count');
		// $data['approved_vendors']=$this->vendor_model->getApprovedVendos('count');
		// $data['rejected_vendors']=$this->vendor_model->getRejectedVendos('count');
		// $data['premium_vendors']=$this->vendor_model->getPremiumVendos('count');
		// $data['blacklisted_vendors']=$this->vendor_model->getBlacklistedVendos('count');		
		// $this->load->view('admin/vendor/index',$data);

		$data=array();
		if($category){
			switch ($category) {
				case 'approved_vendors':
					$data['vendor']=$this->vendor_model->getApprovedVendos('list');	
					break;
				case 'rejected_vendors':
					$data['vendor']=$this->vendor_model->getRejectedVendos('list');	
					break;
				case 'premium_vendors':
					$data['vendor']=$this->vendor_model->getPremiumVendos('list');	
					break;
				case 'blacklisted_vendors':
					$data['vendor']=$this->vendor_model->getBlacklistedVendos('list');	
					break;
				/*default:
					$data['vendor']=$this->vendor_model->getVendos('list');	
				break;*/
			}
		}else{
			$search_keyword='';
			if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post'){			
				$search_keyword=$this->input->post('search_keyword');	
			}
			$data['vendor']=$this->vendor_model->searchVendor(trim($search_keyword));	
		}
   		$data['page_title'] = "Vendor";
		$data['page_keyword'] = "Vendor";
		$data['page_description'] = "Vendor";   		
   		$config['total_rows'] = count($data['vendor']);
		$config['per_page'] = 2;
		$this->pagination->initialize($config);	
   		$this->load->view('admin/vendor/manage',$data);
	}  

	public function manage($category=NULL){
		$data=array();
		if($category){
			switch ($category) {
				case 'approved_vendors':
					$data['vendor']=$this->vendor_model->getApprovedVendos('list');	
					break;
				case 'rejected_vendors':
					$data['vendor']=$this->vendor_model->getRejectedVendos('list');	
					break;
				case 'premium_vendors':
					$data['vendor']=$this->vendor_model->getPremiumVendos('list');	
					break;
				case 'blacklisted_vendors':
					$data['vendor']=$this->vendor_model->getBlacklistedVendos('list');	
					break;
				/*default:
					$data['vendor']=$this->vendor_model->getVendos('list');	
				break;*/
			}
		}else{
			$search_keyword='';
			if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post'){			
				$search_keyword=$this->input->post('search_keyword');	
			}
			$data['vendor']=$this->vendor_model->searchVendor(trim($search_keyword));	
		}
   		$data['page_title'] = "Vendor";
		$data['page_keyword'] = "Vendor";
		$data['page_description'] = "Vendor";   		
   		$config['total_rows'] = count($data['vendor']);
		$config['per_page'] = 2;
		$this->pagination->initialize($config);	
   		$this->load->view('admin/vendor/manage',$data);
   }

   public function view_vendor_detail_ajax(){
   		$id = $this->input->post('vid');
    	$vds_data=$this->vendor_model->GetVendorData($id);
    	$list['vds_data']=$vds_data;
    	$html = $this->load->view('admin/vendor/vendor_data_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0);
   }
	
	public function add()
    {
   		$data['page_title'] = "Manage Vendor";
		$data['page_keyword'] = "Manage Vendor";
		$data['page_description'] = "Manage Vendor";
   	 	$data['message']=null;
   	 	$data['error_msg'] = '';
   	 	$data['country_list']=$this->countries_model->GetCountriesList();
		$command=$this->input->post('command');
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			if ($this->vendor_model->validate_form_data() == TRUE)
			{
				$company_name=$this->input->post('company_name');
				$contact_person=$this->input->post('contact_person');
				$designation=$this->input->post('designation');
				$gst_number=$this->input->post('gst_number');
				$mobile=$this->input->post('mobile');
				$email=$this->input->post('email');
				$website=$this->input->post('website');	
				$address=$this->input->post('address');
				$country_id=$this->input->post('country_id');
				$state=$this->input->post('state');
				$city=$this->input->post('city');
				//$office_phone=$this->input->post('office_phone');
				//$zip=$this->input->post('zip');					
					
				$product_brochure=null;
				$company_brochure=null;
				
				$image_filename = '';
				/*
				if($_FILES['image']['tmp_name'])
				{
					$config = array(
						           'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/vendor/",
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
							$config['source_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/vendor/".$image_filename;
							$config['new_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/vendor/thumb/".$image_filename;
							$config['height']	= '120';
							$this->image_lib->initialize($config); 
							$this->image_lib->resize();							
					   }
				}
				*/

				/*
				$config = array(
					           'upload_path' => "uploads/vendor/",
					           'allowed_types' => "*",
					           'overwrite' => TRUE,
					           'max_size' => "2048000" // Can be set to particular file size , here it is 2 MB(2048 Kb)		          
		        				);
			    $this->load->library('upload', $config);
				$this->upload->initialize($config);
			   
				if($this->upload->do_upload('product_brochure'))
				{
				    $image['image_upload']=array('upload_data' => $this->upload->data()); //Image Upload
				 	       
					$product_brochure=$image['image_upload']['upload_data']['file_name']; //Image Name
				}
				*/
				
				$user_data=array('company_name'=>$company_name,
								 'contact_person'=>$contact_person,
								 'designation'=>$designation,
								 'gst_number'=>$gst_number,
								 'photo'=>$image_filename,
								 'mobile'=>$mobile,
								 'email'=>$email,
								 'website'=>$website,
								 'address'=>$address,
								 'country_id'=>$country_id,
					             'state'=>$state,
					             'city'=>$city,
							     'create_date'=>date('Y-m-d')
								 );
				$result=$this->vendor_model->CreateVendor($user_data);	
				if($result)
				{
					//CheckUserSpace();
					redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/vendor/edit/'.$result.'/?step=2');
				}
				else
				{
					$data['error_msg']="Please check all fields!";
				}
			}
			else
			{
					//$this->form_validation->set_error_delimiters('', '');
                    $msg = validation_errors(); // 'duplicate';
                    $data['error_msg'] = $msg;
                    //$this->session->set_flashdata('error_msg', $msg);                    
                    //redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/add_admin');
			}		
			
		}		
		$this->load->view('admin/vendor/add',$data);		
    } 
   
   public function edit($id=null)
   {
	  
   	 	$data['page_title'] = "Edit Vendor";
		$data['page_keyword'] = "Edit Vendor";
		$data['page_description'] = "Edit Vendor";
   	 	$val=$this->vendor_model->GetVendorData($id); 
   	 	$data['country_list']=$this->countries_model->GetCountriesList();
   	 	$data['vendor_id']=$id;
		$data['message']="";
		$data['error_msg'] = '';
   	 	if($val)
   	 	{
			$data['id']=$val->id;
			$data['company_name']=$val->company_name;
			$data['contact_person']=$val->contact_person;
			$data['designation']=$val->designation;
			$data['gst_number']=$val->gst_number;
			$data['photo']=$val->photo;
			$data['visiting_card_font']=$val->visiting_card_font;
			$data['visiting_card_back']=$val->visiting_card_back;
			$data['mobile']=$val->mobile;
			$data['office_phone']=$val->office_phone;
			$data['email']=$val->email;
			$data['address']=$val->address;
			$data['country_id']=$val->country_id;
			$data['status']=$val->status;
			if($data['country_id'])
			{
				$data['state_list']=$this->states_model->GetStatesList($val->country_id);
			}
			
			$data['state']=$val->state;
			if($data['state'])
			{
				$data['city_list']=$this->cities_model->GetCitiesList($val->state);
			}
			
			$data['city']=$val->city;
			$data['website']=$val->website;

			
			$data['zip']=$val->zip;
			$data['product_brochure']=$val->product_brochure;
			$data['company_brochure']=$val->company_brochure;		
		}
   	 	 
		$command=$this->input->post('command');
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{	
			if ($this->vendor_model->validate_form_data() == TRUE)
			{

				$err_msg="";
				$existing_image = $this->input->post('existing_image');
				$id=$this->input->post('id');
				$company_name=$this->input->post('company_name');
				$contact_person=$this->input->post('contact_person');
				$designation=$this->input->post('designation');
				$mobile=$this->input->post('mobile');		
				$email=$this->input->post('email');
				$website=$this->input->post('website');
				$address=$this->input->post('address');
				$country_id=$this->input->post('country_id');
				$state=$this->input->post('state');
				$city=$this->input->post('city');
				$status=$this->input->post('status');
				$gst_number=$this->input->post('gst_number');
				
				/*
				if($_FILES['image']['tmp_name'])
				{
					$image_filename=NULL;					
					$config = array(
								   'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/vendor/",
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

						$config['source_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/vendor/".$image_filename;
						$config['new_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/vendor/thumb/".$image_filename;
						$config['height']	= '120';
						$this->image_lib->initialize($config); 
						$this->image_lib->resize();

						$post_data_img=array(
		                 				'photo'=>$image_filename,
						 				'modify_date'=>date('Y-m-d H:i:s')
						 				);
						$result=$this->vendor_model->UpdateVendor($post_data_img,$id);	
						

			   			//#############################################################//    
				        //############## DELETE EXISTING IMAGE IF ANY #################//
				        if($existing_image!="")
				        {  
				        	@unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/vendor/'.$existing_image);
				            @unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/vendor/thumb/'.$existing_image);
				        }
				        //#############################################################//
				    }
				}
				*/
				$post_data=array('company_name'=>$company_name,
								 'contact_person'=>$contact_person,
								 'designation'=>$designation,
								 'mobile'=>$mobile,
								 'gst_number'=>$gst_number,		
								 'email'=>$email,
								 'website'=>$website,
								 'address'=>$address,
								 'country_id'=>$country_id,
				                 'state'=>$state,
				                 'city'=>$city,
				                 'status'=>$status,
							 	 'modify_date'=>date('Y-m-d')
							    );
				$result=$this->vendor_model->UpdateVendor($post_data,$id);					
				if($result)
				{
					// CheckUserSpace();
					$msg = "Record successfully updated..";
                    $this->session->set_flashdata('success_msg', $msg);
					redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/vendor/edit/'.$id.'/?step=2');
				}
				else
				{
					$data['error_msg']="Please check all fields!";
				}
				
				
			}
			else
			{
				//$this->form_validation->set_error_delimiters('', '');
                $msg = validation_errors(); // 'duplicate';
                // print_r($msg); die();
                $data['error_msg'] = $msg;
                //$this->session->set_flashdata('error_msg', $msg);                    
                //redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/user/add_admin');
			}
		}
		/*echo '<pre>';
		print_r($data);die;*/
		$this->load->view('admin/vendor/edit',$data);
		
   } 

   public function edit_visiting_card($id=null)
   {	 
		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			

			$err_msg="";
			$existing_visiting_card_font = $this->input->post('existing_visiting_card_font');
			$existing_visiting_card_back = $this->input->post('existing_visiting_card_back');
			$id=$this->input->post('id');
			
			if($_FILES['visiting_card_font']['tmp_name'])
			{

				$visiting_card_font=null;
				$config = array(
					           'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/vendor/",
					           'allowed_types' => "*",
					           'overwrite' => TRUE,
					           'max_size' => "2048000" // Can be set to particular file size , here it is 2 MB(2048 Kb)
					          
					         );
		       $this->load->library('upload', $config);
			   $this->upload->initialize($config);
		   
				if(!$this->upload->do_upload('visiting_card_font'))
				{
					echo $this->upload->display_errors();die('1');
				}
				else
				{
				    $image['image_upload']=array('upload_data' => $this->upload->data()); //Image Upload				 	       
					$visiting_card_font=$image['image_upload']['upload_data']['file_name']; //Image Name

					$post_data_img=array(
	                 				'visiting_card_font'=>$visiting_card_font,
					 				'modify_date'=>date('Y-m-d H:i:s')
					 				);
					$result=$this->vendor_model->UpdateVendor($post_data_img,$id);
					//CheckUserSpace();
					//#############################################################//    
			        //############## DELETE EXISTING IMAGE IF ANY #################//
			        if($existing_visiting_card_font!="")
			        {  
			        	@unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/vendor/'.$existing_visiting_card_font);
			        }
			        //#############################################################//
				}
				
			}
			
			if($_FILES['visiting_card_back']['tmp_name'])
			{

				$visiting_card_back=null;
				$config = array(
					           'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/vendor/",
					           'allowed_types' => "*",
					           'overwrite' => TRUE,
					           'max_size' => "2048000" // Can be set to particular file size , here it is 2 MB(2048 Kb)
					          
					         );
		       $this->load->library('upload', $config);
			   $this->upload->initialize($config);
		   
				if(!$this->upload->do_upload('visiting_card_back'))
				{
					echo $this->upload->display_errors();die('2');
				}
				else
				{
				    $image['image_upload']=array('upload_data' => $this->upload->data()); //Image Upload				 	       
					$visiting_card_back=$image['image_upload']['upload_data']['file_name']; //Image Name

					$post_data_img=array(
	                 				'visiting_card_back'=>$visiting_card_back,
					 				'modify_date'=>date('Y-m-d H:i:s')
					 				);
					$result=$this->vendor_model->UpdateVendor($post_data_img,$id);
					//CheckUserSpace();
					//#############################################################//    
			        //############## DELETE EXISTING IMAGE IF ANY #################//
			        if($existing_visiting_card_back!="")
			        {  
			        	@unlink('assets/uploads/clients/'.$this->session->userdata['admin_session_data']['client_id'].'/vendor/'.$existing_visiting_card_back);
			        }
			        //#############################################################//
				}
				
			}

			
			$msg = "Record successfully updated..";
            $this->session->set_flashdata('success_msg', $msg);
            //CheckUserSpace();
			redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/vendor/edit/'.$id.'/?step=3');
		}		
		$this->load->view('admin/vendor/edit',$data);		
   } 

   // AJAX PAGINATION START
    function vendor_product_list_ajax()
    { 
    	$v_id = $this->input->get('v_id');        
        $start = $this->input->get('page');         
             
        $this->load->library('pagination');
        $limit=10;
        $config = array(); 
       //$config['base_url'] =base_url('pages_ajax/show');
        $config['base_url'] ='#';
        $config['total_rows'] = $this->vendor_model->tagged_product_count($v_id); 
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

       //Tính start cho từng phân đoạn (limit) trong trường hợp hiện thị số trang thực tế $config['use_page_numbers'] = TRUE
       //$start = $this->uri->segment(3);
        $start=empty($start)?0:($start-1)*$limit;

        $list["lastStr"]  = '';
        $list['currency_list']=$this->lead_model->GetCurrencyList();
        $list['rows'] = $this->vendor_model->vendor_product_list($limit,$start,$v_id);
        $table = '';
        $table = $this->load->view('admin/vendor/get_ajax_view',$list,TRUE);
        //$table = $list['rows'];
        $data =array (
           "table"=>$table,
           "page"=>$page_link
            );
       
        $this->output->set_content_type('application/json');
        echo json_encode($data);
        
    }
    // AJAX PAGINATION END

    // =================================
    // AJAX VIEW FOR VENDOR TAG

    function save_upload_data()
    {
        /*$file_display_name = $this->input->post('file_display_name');
        $file_name = $this->input->post('file_name');
        $tmp_full_path   = trim('assets/admin/'.$this->input->post('full_path'));
        $full_path = (substr($tmp_full_path, -1)=='/')?$tmp_full_path:$tmp_full_path.'/';

        $postData['file_display_name']          = $file_display_name;
        $postData['file_name']                  = $file_name;
        $postData['full_path']                  = $full_path;
        $postData['file_uploaded_datetime']     = date("Y-m-d H:i:s");

        $chk_duplicate = $this->homemodel->chk_duplicate_file($postData);
        if($chk_duplicate==true)
        {
            $return = $this->homemodel->save_file($postData);
            $result["status"] = 'success';
        }
        else
        {
            $result["status"] = 'exist';
        }*/
        $result["status"] = 'success';
        echo json_encode($result);
        exit(0);
    }

    public function get_prodlist_for_tag_ajax()
	{		
		$vendor_id=$this->input->post('vendor_id');
		$method_name=$this->input->post('method_name');
		$data['method_name']=$method_name;
		if($method_name=='manage'){
			$data['product_list']=$this->Product_model->get_product_taggedInVendor($vendor_id);
		}
		else{
			$data['product_list']=$this->Product_model->get_product_notTaggedInVendor($vendor_id);
		}
		$this->load->view('admin/vendor/product_list_ajax',$data);
	}

	public function select_product_tagged_ajax()
	{
		//$data=NULL;
		$product_str=$this->input->post('product_str');
		$product_arr = explode(",", $product_str);
		$vendor_id=$this->input->post('vendor_id');

		//$data['product_data']=array();
		//$prod_arr=explode(',',$prod_id);
		$i=0;	
		if(count($product_arr))
		{
			for($i=0;$i<count($product_arr);$i++)
			{
				//if($prod_data!='')
				//{
					//$chk_prod_temp=$this->Product_model->TempProdExistCheck($prod_data,$user_id);
					//if(!$chk_prod_temp)
					//{
						//$data['product_data'][$i]=$this->Product_model->GetProductListById($prod_data);				
					
						$new_data=array('vendor_id'=>$vendor_id,
										'product_varient_id'=>$product_arr[$i],
										'created_datetime'=>date("Y-m-d H:i:s")
										);
						
						$create_data=$this->vendor_model->add_vendor_product($new_data);
						
						
					//}
				//}
				
			}
		}
		echo $this->vendor_model->tagged_product_count($vendor_id);
			
	}

	public function update_vendor_product_tag_ajax()
	{	$id=$this->input->post('id');
		$delivery_time=$this->input->post('delivery_time');
		$delivery_time_unit=$this->input->post('delivery_time_unit');

		$post_data = array('delivery_time'=>$delivery_time,
						   'delivery_time_unit'=>$delivery_time_unit,
						   'updated_datetime'=>date("Y-m-d H:i:s")
						  );
		$r =$this->vendor_model->update_vendor_product($post_data,$id);
		if($r==true){
			echo'success';
		}
		else{
			echo 'error';
		}
	}

	public function delete_vendor_product_tag_ajax()
	{	$id=$this->input->post('id');		
		$r =$this->vendor_model->delete_vendor_product($id);
		if($r==true){
			echo'success';
		}
		else{
			echo 'error';
		}
	}

	// AJAX VIEW FOR VENDOR TAG
	// ==================================

   
   
   public function delete($id)
   {
  		
  		$user_data=array('status'=>'1',
					     'modify_date'=>date('Y-m-d')
						);
									 
  		$result=$this->vendor_model->DeleteVendor($user_data,$id);	
			
		if($result)
		{
			$data['message']="Successfully deleted!";			
			redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/vendor/manage');
		}
		else
		{
			$data['message']="Sorry! can't delete";
			redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/vendor/manage');			
		}
   }

   function mail_send_to_vendor_ajax()
    {
    	$vdr_id = $this->input->post('vdr_id');
		$vdr_to_email = $this->input->post('vdr_to_email');
		$vdr_from_email = $this->input->post('vdr_from_email');
		$vdr_mail_subject = $this->input->post('vdr_mail_subject');
		$vdr_mail_body = $this->input->post('vdr_mail_body');
		
		$cus_data=$this->vendor_model->GetVendorData($vdr_id);
    	$company=get_company_profile();
		// ============================
		// Update Mail to client 
		// START
		$assigned_user_id=$this->session->userdata['admin_session_data']['user_id'];
		$cc_mail='';
		if($assigned_user_id!=1)
		{
			$self_cc_mail=get_value("email","user","id=".$assigned_user_id);
			$cc_mail=get_manager_and_skip_manager_email($assigned_user_id);
			$cc_mail=($cc_mail)?$cc_mail.','.$self_cc_mail:$self_cc_mail;
		}
		
		$attach_filename='';
		$this->load->library('upload', '');
		if($_FILES['vdr_attachment']['name'] != "")
		{
			$config=array();
			$config['upload_path'] = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/vendor/mail_attachment/";
			$config['allowed_types'] = "*";
			$config['max_size'] = '1000000'; //KB
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('vdr_attachment'))
			{
				//return $this->upload->display_errors();
			}
			else
			{
				$file_data = array('upload_data' => $this->upload->data());
				$attach_filename = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/vendor/mail_attachment/".$file_data['upload_data']['file_name'];			    
			}
		}
        
		// EMAIL CONTENT
	    $e_data=array();
		$e_data['company']=$company;
		$e_data['email_subject']=$vdr_mail_subject;
		$e_data['email_to']=$cus_data['contact_person'].'<br>'.$cus_data['company_name'];
		$e_data['email_body']=$vdr_mail_body;		
		$template_str = $this->load->view('admin/email_template/template/common_template_layout', $e_data, true);    

                	
        $to_mail=$com_to_email;	   
    	$this->load->library('mail_lib');
        $mail_data = array();        
        $mail_data['from_mail']     = $this->session->userdata['admin_session_data']['email'];
        $mail_data['from_name']     = $this->session->userdata['admin_session_data']['name'];
        $mail_data['to_mail']       = $to_mail;        
        $mail_data['cc_mail']       = $cc_mail;               
        $mail_data['subject']       = $vdr_mail_subject;
        $mail_data['message']       = $template_str;
        $mail_data['attach']        = array($attach_filename);
        $this->mail_lib->send_mail($mail_data);
		// MAIL SEND
		// ===============================================	
		$result['status'] = 'success';
        echo json_encode($result);
        exit(0);
    }
	
}
