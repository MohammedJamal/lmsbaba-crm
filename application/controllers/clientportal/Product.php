<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {	
	 
	function __construct()
	{
		parent :: __construct();
		is_admin_logged_in();
		init_admin_element(); 		
		$this->load->model(array("Product_attribute_model","Product_model","vendor_model","Opportunity_product_model","menu_model","history_model","Opportunity_model","Group_wise_category_model","lead_model"));
		// permission checking
		// if(is_permission_available($this->session->userdata('service_wise_menu')[3]['menu_list']['menu_keyword'])===false){ 
		// 	redirect(admin_url().'dashboard', 'refresh');
		// 	exit(0);
		// }
		// end
	}


	public function index(){
		// $data['product_count']=$this->Product_model->getProductsCount('count');		
		// $data['with_single_price']=0;
		// $data['with_vendors']=$this->Product_model->getVendosProducts('count');
		// $data['with_photo']=$this->Product_model->getPhotoProducts('count');
		// $data['with_brochure']=$this->Product_model->getBrochureProducts('count');		
		// $this->load->view('admin/product/index',$data);		
		$data=array();		
		$data['disabled_reason_list']=$this->Product_model->GetDisabledReasonKeyVal();
		$data['product_count']=$this->Product_model->getProductsCount();	
		$data['currency_list']=$this->Product_model->GetCurrencyList();
		$data['category_list']=$this->Setting_model->GetProductCategoryList();	
		$data['unit_type_list']=$this->Setting_model->GetProductUnitTypeList();
		$this->load->view('admin/product/product_view',$data);
	}
	public function manage($category=NULL){
		$data=array();		
		$data['disabled_reason_list']=$this->Product_model->GetDisabledReasonKeyVal();
		$data['product_count']=$this->Product_model->getProductsCount();		
		$data['currency_list']=$this->Product_model->GetCurrencyList();
		$data['category_list']=$this->Setting_model->GetProductCategoryList();	
		$data['unit_type_list']=$this->Setting_model->GetProductUnitTypeList();
		$this->load->view('admin/product/product_view',$data);
	}

	// AJAX PAGINATION START
	function get_list_ajax()
	{
	    $start = $this->input->get('page');
	    $view_type = $this->input->get('view_type');
	    
	    $this->load->library('pagination');
	    $limit=30;
	    $config = array();
	    $list=array();
	    $arg=array();
	    $arg['search_product']=$this->input->get('search_product');
	    $arg['status']=$this->input->get('status');
	    
		$arg['filter_search_str']=$this->input->get('filter_search_str');
	    $arg['filter_aproved']=$this->input->get('filter_aproved');
	    $arg['filter_disabled']=$this->input->get('filter_disabled');
	    $arg['filter_disabled_reason']=$this->input->get('filter_disabled_reason');
		
		$arg['filter_group_id']=$this->input->get('filter_group_id');
		$arg['filter_cate_id']=$this->input->get('filter_cate_id');

	    $arg['filter_with_image']=$this->input->get('filter_with_image');
	    $arg['filter_with_brochure']=$this->input->get('filter_with_brochure');
	    $arg['filter_with_youtube_video']=$this->input->get('filter_with_youtube_video');
	    $arg['filter_with_gst']=$this->input->get('filter_with_gst');
	    $arg['filter_with_hsn_code']=$this->input->get('filter_with_hsn_code');
	    
	    $arg['filter_product_available_for']=$this->input->get('filter_product_available_for');
	    $arg['filter_sort_by']=$this->input->get('filter_sort_by');

	   //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->Product_model->get_list_count($arg);
	    $config['per_page'] = $limit;
	    $config['uri_segment']=4;
	    $config['num_links'] = 1;
	    $config['use_page_numbers'] = TRUE;
	   //$config['full_tag_open'] = '<div id="paginator" class="dataTables_paginate paging_bootstrap">';
	    $config['attributes'] = array('class' => 'myclass','data-viewtype'=>$view_type);
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

	    $start=empty($start)?0:($start-1)*$limit;
	    $arg['limit']=$limit;
	    $arg['start']=$start;

    	$list['rows']=$this->Product_model->get_list($arg);
	    $table = '';
	    if($view_type=='grid')
	    {
	    	$table = $this->load->view('admin/product/product_grid_view_ajax',$list,TRUE);
	    }
	    else
	    {
	    	$table = $this->load->view('admin/product/product_list_view_ajax',$list,TRUE);
	    }
	    
		$product_count=$this->Product_model->getProductsCount();   
	    $data =array (
					"table"=>$table,
					"page"=>$page_link,
					"approved_count"=>$product_count['approved_count'],
					"disabled_count"=>$product_count['disabled_count']
	        		);
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	// AJAX PAGINATION END

	// -------------------------------------------------------------------------
	//  VALIDATION FUNCTION 
	function validate_form_data()
	{ 
		$this->load->library('form_validation');
		$this->form_validation->set_rules('group_id', 'Group', 'required');
		$this->form_validation->set_rules('cate_id', 'Category', 'required');
		$this->form_validation->set_rules('name', 'name', 'required');
		//$this->form_validation->set_rules('code', 'code', 'required');
		//$this->form_validation->set_rules('hsn_code', 'HSN code', 'required');
		$this->form_validation->set_rules('price', 'sale price', 'required');
		$this->form_validation->set_rules('currency_type', 'currency', 'required');		
		$this->form_validation->set_rules('unit', 'unit', 'required');
		$this->form_validation->set_rules('unit_type', 'unit type price', 'required');
		// $this->form_validation->set_rules('description', 'Short description', 'required');
		$this->form_validation->set_rules('product_available_for', 'Available For', 'required');
		//$this->form_validation->set_rules('minimum_order_quantity', 'Min. Order Quantity', 'required');
		if ($this->form_validation->run() == TRUE)
		{
		  return TRUE;
		}
		else
		{
		  return FALSE;
		}
	}
	// VALIDATION FUNCTION 
	// ----------------------------------------------------------------------------

	public function addProduct_ajax(){

		$data['name']=$this->input->post('product');
		$data['code']=substr($data['name'],0,3).rand(1,9999999);
		$vendor_str = ($this->uri->segment(3))?$this->uri->segment(3):''; 
		$vendor_id = '';
		if($vendor_str!='')
		{
			$vendor = explode("_",$vendor_str);
			$vendor_id = $vendor[1];
		}
		$data['vendor_str']=$vendor_str;
		$data['vendor_id']=$vendor_id;
		$data['vendor_list']=$this->vendor_model->GetVendorListAll();
		$data['unit_type_list']=$this->Product_model->GetUnitList();
		$data['currency_list']=$this->Product_model->GetCurrencyList();
		$data['vendor_key_val']=$this->vendor_model->get_vendor_key_val();
		//CheckUserSpace();	
		$this->load->view('admin/product/add_product_ajax',$data);
	}

	public function selectVendors()
	{
		$existing_vendors=$this->input->post('existing_vendors');
		$existing_vendor_str='';
		$vendors_str=trim($existing_vendors,'^');
		if($vendors_str)
		{
			$vendors_arr=array();
			$vendors=explode("^",$vendors_str);
			foreach ($vendors as $vendor) 
			{
				$vdr=explode("_", $vendor);
				$vdr[0]=trim($vdr[0],'@');
				$vdr_arr=array(
					'vendor_id'=>$vdr[0],	
					'price'=>$vdr[1],
					'currency_type'=>$vdr[2],
					'unit'=>$vdr[3],
					'unit_type'=>$vdr[4]
				);
				$vendors_arr[$vdr[0]]=$vdr_arr;
				$existing_vendor_str .="'".$vdr[0]."',";
			}	
			$existing_vendor_str=rtrim($existing_vendor_str,",");				
		}
		
		$data['existing_vendors']=$vendors_arr;
		$data['unit_type_list']=$this->Product_model->GetUnitList();
		$data['currency_list']=$this->Product_model->GetCurrencyList();
		if($this->input->post()){
			$search_keyword=$this->input->post('search_keyword');
			$filter_arr=array('search_keyword'=>$search_keyword,'existing_vendors'=>$existing_vendor_str);
			$data['vendor_key_val']=$this->vendor_model->GetVendorListFilter($filter_arr);
		}else{
			$data['vendor_key_val']=$this->vendor_model->get_vendor_key_val();
		}
		$this->load->view('admin/product/select_vendors',$data);
	}

	public function add_ajax(){
			
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 'name', 'required');
		$this->form_validation->set_rules('currency_type', 'currency', 'required');
		$this->form_validation->set_rules('price', 'sale price', 'required');
		$this->form_validation->set_rules('unit', 'unit', 'required');
		$this->form_validation->set_rules('unit_type', 'unit type price', 'required');
		if ($this->form_validation->run() == TRUE)
		{
			$this->load->library('upload', '');
            $this->load->library('image_lib', '');
			$name=$this->input->post('name');
			$code=$this->input->post('code');
			$description=$this->input->post('description');
			$price=$this->input->post('price');
			$unit=$this->input->post('unit');
			$currency_type=$this->input->post('currency_type');
			$unit_type=$this->input->post('unit_type');
			$data_prd_varient=array('parent_id'=>'0','name'=>$name,'code'=>$code,'unit_type'=>$unit_type,'currency_type'=>$currency_type,'description'=>$description,'price'=>$price,'unit'=>$unit,'date_added'=>date("Y-m-d H:i:s"));
			$create_prod_varient=$this->Product_model->CreateProductVarient($data_prd_varient);
			if($_FILES['image_files']['tmp_name']!=''){
				$config = array(
					'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/",
					'allowed_types' => "gif|jpg|png|jpeg|pdf",
					'overwrite' => FALSE,
					'encrypt_name' => TRUE, 
					'max_size' => "2048000"
				);
				$dataInfo = array();
				$files = $_FILES;
				$cpt = count($_FILES['image_files']['name']);
				for($i=0; $i<$cpt; $i++){        
					$_FILES['image_files']['name']= $files['image_files']['name'][$i];
					$_FILES['image_files']['type']= $files['image_files']['type'][$i];
					$_FILES['image_files']['tmp_name']= $files['image_files']['tmp_name'][$i];
					$_FILES['image_files']['error']= $files['image_files']['error'][$i];
					$_FILES['image_files']['size']= $files['image_files']['size'][$i];    
					$this->upload->initialize($config);
					if($this->upload->do_upload('image_files')){						
						$dataInfo = $this->upload->data();						
						$image_filename=$dataInfo['file_name']; //Image Name			
						if($image_filename){
							$config=NULL;							
							$config['source_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/".$image_filename;
							$config['new_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/thumb/".$image_filename;	
							$config['height']	= '120';
							$this->image_lib->initialize($config); 
							$this->image_lib->resize();
						}
						$data = array(
							'varient_id' => $create_prod_varient,
							'file_name' => $image_filename
						);
						$result_set = $this->Product_model->CreateProductImage($data);	
					}
				}
			}	
			if($_FILES['pdf_files']['tmp_name']){
				$config = array(
					'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/pdf/",
					'allowed_types' => "pdf",
					'overwrite' => TRUE,
					'encrypt_name' => FALSE, 
					'max_size' => "2048000"
				);
				$dataInfo = array();
				$files = $_FILES;
				$cpt = count($_FILES['pdf_files']['name']);
				for($i=0; $i<$cpt; $i++){           
					$_FILES['pdf_files']['name']= $files['pdf_files']['name'][$i];
					$_FILES['pdf_files']['type']= $files['pdf_files']['type'][$i];
					$_FILES['pdf_files']['tmp_name']= $files['pdf_files']['tmp_name'][$i];
					$_FILES['pdf_files']['error']= $files['pdf_files']['error'][$i];
					$_FILES['pdf_files']['size']= $files['pdf_files']['size'][$i];    
					$this->upload->initialize($config);
					$this->upload->do_upload();
					if($this->upload->do_upload('pdf_files')){						
						$dataInfo = $this->upload->data();						
						$image_filename=$dataInfo['file_name']; //Image Name
						$data = array(
							'varient_id' => $create_prod_varient,
							'file_name' => $image_filename
						);								
						$this->Product_model->CreateProductPDF($data);
					}								
				}
			}
			
			if($create_prod_varient){
				$vendors_str=trim($this->input->post('vendors'),'^');
				if($vendors_str){
					$vendors_arr=array();
					$vendors=explode("^",$vendors_str);
					foreach ($vendors as $vendor) {
						$vdr=explode("_", $vendor);
						$vdr[0]=trim($vdr[0],'@');
						$chk_tagged=$this->Product_model->chk_already_tagged($vdr[0],$create_prod_varient);
				        if($chk_tagged==0){
				        	$vdr_arr=array(
		        				'vendor_id'=>$vdr[0],
		        				'product_varient_id'=>$create_prod_varient,
		        				'delivery_time'=>'',
		        				'delivery_time_unit'=>'',						
								'price'=>$vdr[1],
								'currency_type'=>$vdr[2],
								'unit'=>$vdr[3],
								'unit_type'=>$vdr[4],
								'created_datetime'=>date("Y-m-d H:i:s"),
								'updated_datetime'=>date("Y-m-d H:i:s")
		        			);
		        			$vendors_arr[]=$vdr_arr;
				        }
					}
					$res=$this->vendor_model->add_batch_vendor_product($vendors_arr);
					if($res){
						echo json_encode(['status'=>'success','Product added successfully.']);
					}else{
						echo json_encode(['status'=>'success','Product added successfully.But Vendor not tagged!']);
					}
				}else{
					echo json_encode(['status'=>'success','Product added successfully.']);
				}
			}else{
				echo json_encode(['status'=>'error','Something went wrong there! Please try again.']);
			}
		}else{
			/*$msg = validation_errors(); // 'duplicate';
			$msg=str_replace('<p>', "", $msg);
			$msg=str_replace('</p>', "", $msg);*/
			echo json_encode(['status'=>'error','msg'=>'All * fields are required']);
            //$this->session->set_flashdata('error_msg', $msg);
		}			
	}

	public function addProductVendors(){
		$create_prod_varient=$this->input->post('product_id');
		if($create_prod_varient){
			$vendors_str=trim($this->input->post('vendors'),'^');
			if($vendors_str){
				$vendors_arr=array();
				$vendors=explode("^",$vendors_str);
				foreach ($vendors as $vendor) {
					$vdr=explode("_", $vendor);
					$vdr[0]=trim($vdr[0],'@');
					$chk_tagged=$this->Product_model->chk_already_tagged($vdr[0],$create_prod_varient);
			        if($chk_tagged==0){
			        	$vdr_arr=array(
	        				'vendor_id'=>$vdr[0],
	        				'product_varient_id'=>$create_prod_varient,
	        				'delivery_time'=>'',
	        				'delivery_time_unit'=>'',						
							'price'=>$vdr[1],
							'currency_type'=>$vdr[2],
							'unit'=>$vdr[3],
							'unit_type'=>$vdr[4],
							'created_datetime'=>date("Y-m-d H:i:s"),
							'updated_datetime'=>date("Y-m-d H:i:s")
	        			);
	        			$vendors_arr[]=$vdr_arr;
			        }
				}
				$res=$this->vendor_model->add_batch_vendor_product($vendors_arr);
				if($res){
					echo json_encode(['status'=>'success','Product added successfully.']);
				}else{
					echo json_encode(['status'=>'success','Product added successfully.But Vendor not tagged!']);
				}
			}else{
				echo json_encode(['status'=>'success','Product added successfully.']);
			}
		}else{
			echo json_encode(['status'=>'error','Something went wrong there! Please try again.']);
		}
	}

	public function add()
	{
		$data['step']='1';
		$data['flag']='';
		$vendor_str = ($this->uri->segment(3))?$this->uri->segment(3):''; 
		$vendor_id = '';
		if($vendor_str!='')
		{
			$vendor = explode("_",$vendor_str);
			$vendor_id = $vendor[1];
		}
		$data['vendor_str']=$vendor_str;
		$data['vendor_id']=$vendor_id;

			
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			
			if($this->validate_form_data() == TRUE)
			{
				$this->load->library('upload', '');
                $this->load->library('image_lib', '');


				$step=$this->input->post('step');
				$name=$this->input->post('name');
				$code=$this->input->post('code');
				$description=$this->input->post('description');
				$price=$this->input->post('price');
				$unit=$this->input->post('unit');
				
				$currency_type=$this->input->post('currency_type');
				$unit_type=$this->input->post('unit_type');
				
								
				$data_prd_varient=array('parent_id'=>'0','name'=>$name,'code'=>$code,'unit_type'=>$unit_type,'currency_type'=>$currency_type,'description'=>$description,'price'=>$price,'unit'=>$unit);
				
				$create_prod_varient=$this->Product_model->CreateProductVarient($data_prd_varient);
				
				if($_FILES['image_files']['tmp_name']!='')
				{

					$config = array(
									'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/",
									'allowed_types' => "gif|jpg|png|jpeg|pdf",
									'overwrite' => FALSE,
									'encrypt_name' => TRUE, 
									'max_size' => "2048000"
								);
					$dataInfo = array();
					$files = $_FILES;
					$cpt = count($_FILES['image_files']['name']);
					for($i=0; $i<$cpt; $i++)
					{        
						$_FILES['image_files']['name']= $files['image_files']['name'][$i];
						$_FILES['image_files']['type']= $files['image_files']['type'][$i];
						$_FILES['image_files']['tmp_name']= $files['image_files']['tmp_name'][$i];
						$_FILES['image_files']['error']= $files['image_files']['error'][$i];
						$_FILES['image_files']['size']= $files['image_files']['size'][$i];    
						
						$this->upload->initialize($config);
						if($this->upload->do_upload('image_files'))
						{						
							$dataInfo = $this->upload->data();						
							$image_filename=$dataInfo['file_name']; //Image Name			
							if($image_filename)
							{
								$config=NULL;							
								$config['source_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/".$image_filename;
								$config['new_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/thumb/".$image_filename;	
								$config['height']	= '120';
								$this->image_lib->initialize($config); 
								$this->image_lib->resize();
							}
							$data = array(
								'varient_id' => $create_prod_varient,
								'file_name' => $image_filename
							 );
							 $result_set = $this->Product_model->CreateProductImage($data);	
				
						}
					}
				}	
						

				if($_FILES['pdf_files']['tmp_name'])
				{
					
					$config = array(
						'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/pdf/",
						'allowed_types' => "pdf",
						'overwrite' => TRUE,
						'encrypt_name' => FALSE, 
						'max_size' => "2048000"
						);
						$dataInfo = array();
						$files = $_FILES;
						$cpt = count($_FILES['pdf_files']['name']);
						for($i=0; $i<$cpt; $i++)
						{           
							$_FILES['pdf_files']['name']= $files['pdf_files']['name'][$i];
							$_FILES['pdf_files']['type']= $files['pdf_files']['type'][$i];
							$_FILES['pdf_files']['tmp_name']= $files['pdf_files']['tmp_name'][$i];
							$_FILES['pdf_files']['error']= $files['pdf_files']['error'][$i];
							$_FILES['pdf_files']['size']= $files['pdf_files']['size'][$i];    
					
							$this->upload->initialize($config);
							$this->upload->do_upload();
							if($this->upload->do_upload('pdf_files'))
							{						
								$dataInfo = $this->upload->data();						
								$image_filename=$dataInfo['file_name']; //Image Name
							    
								$data = array(
									'varient_id' => $create_prod_varient,
									'file_name' => $image_filename
								);								
								$this->Product_model->CreateProductPDF($data);
							}								
						}
				}
				

				if($create_prod_varient)
				{
					$this->session->set_flashdata('flag', 'sn');
					$url=$this->session->userdata['admin_session_data']['lms_url'];
					redirect($url.'/product/edit_step2/'.$create_prod_varient);
				}
				else
				{
					$url=$this->session->userdata['admin_session_data']['lms_url'];
					redirect($url.'/product/');
				}
				
			}
			else
			{
				$msg = validation_errors(); // 'duplicate';
	            $this->session->set_flashdata('error_msg', $msg);
			}		
			 
		}
		
		$data['vendor_list']=$this->vendor_model->GetVendorListAll();
		$data['unit_type_list']=$this->Product_model->GetUnitList();
		$data['currency_list']=$this->Product_model->GetCurrencyList();
		$data['vendor_key_val']=$this->vendor_model->get_vendor_key_val();
		//CheckUserSpace();	
		$this->load->view('admin/product/add_product',$data);
	}


	public function edit($id)
	{
		$data['step']='1';
		$data['flag']='';		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{			
			if($this->validate_form_data() == TRUE)
			{
				$this->load->library('upload', '');
	            $this->load->library('image_lib', '');
				$edit_id=$this->input->post('edit_id');
				$name=$this->input->post('name');
				$code=$this->input->post('code');
				$currency_type=$this->input->post('currency_type');
				$price=$this->input->post('price');
				$unit=$this->input->post('unit');
				$unit_type=$this->input->post('unit_type');	
				$description=$this->input->post('description');
				
				
				$data_prd_varient=array(
										'name'=>$name,
										'currency_type'=>$currency_type,
										'unit_type'=>$unit_type,
										'description'=>$description,
										'code'=>$code,
										'price'=>$price,
										'unit'=>$unit,
										'date_modified'=>date("Y-m-d H:i:s")
										);
				$this->Product_model->UpdateProductVarient($data_prd_varient,$edit_id);
				
			    $this->session->set_flashdata('flag', 's');
				$url=$this->session->userdata['admin_session_data']['lms_url'];
				redirect($url.'/product/edit_step2/'.$edit_id);
			}
			else
			{
				$msg = validation_errors(); // 'duplicate';
	            $this->session->set_flashdata('error_msg', $msg);
			}			
		}
		$data['edit_id']=$id;
		$data['row']=$this->Product_model->GetProductDetail($id);
		//print_r($data['row']); die();
		$data['unit_type_list']=$this->Product_model->GetUnitList();
		$data['currency_list']=$this->Product_model->GetCurrencyList();		
		$this->load->view('admin/product/edit_product',$data);
	}

	public function edit_step2($id)
	{
		$data['step']='2';
		$data['flag']='';
		$data['product_data']=$this->Product_model->GetSKUData($id);		
		$data['product_name']=get_value("name","product_varient","id=".$id);
		$data['edit_id']=$id;
		$data['row']=$this->Product_model->GetSKUData($id);
		$data['unit_type_list']=$this->Product_model->GetUnitList();
		$data['currency_list']=$this->Product_model->GetCurrencyList();	
		$data['vendor_key_val']=$this->vendor_model->get_vendor_key_val();	
		$this->load->view('admin/product/edit_product',$data);
	}

	function add_product_wise_dendor_ajax()
    {
    	$existing_product_id = $this->input->post('existing_product_id');
        $pwv_vendor_id = $this->input->post('pwv_vendor_id'); 
        $pwv_price = $this->input->post('pwv_price');  
        $pwv_currency_type = $this->input->post('pwv_currency_type');  
        $pwv_unit = $this->input->post('pwv_unit');  
        $pwv_unit_type = $this->input->post('pwv_unit_type');  
        //$success_str=$pwv_vendor_id.' / '.$pwv_price.' / '.$pwv_currency_type.' / '.$pwv_unit.' / '.$pwv_unit_type;   
        $chk_already_tagged=$this->Product_model->chk_already_tagged($pwv_vendor_id,$existing_product_id);

        if($chk_already_tagged==0)
        {
        	$post_data=array(
        				'vendor_id'=>$pwv_vendor_id,
        				'product_varient_id'=>$existing_product_id,
        				'delivery_time'=>'',
        				'delivery_time_unit'=>'',						
						'price'=>$pwv_price,
						'currency_type'=>$pwv_currency_type,
						'unit'=>$pwv_unit,
						'unit_type'=>$pwv_unit_type,
						'created_datetime'=>date("Y-m-d H:i:s"),
						'updated_datetime'=>date("Y-m-d H:i:s")
        				);
	        $return=$this->vendor_model->add_vendor_product($post_data);
	        $status_str='success';
        }
        else
        {
        	$status_str='already_exist';
        }
        
        $result["status"] = $status_str;
        echo json_encode($result);
        exit(0);  
    }

	// AJAX PAGINATION START
	function get_product_wise_vendor_list_ajax()
	{
	    $start = $this->input->get('page');
	    $this->load->library('pagination');
	    $limit=10;
	    $arg=array();
	    $arg['search_existing_product_id']=$this->input->get('search_existing_product_id');
	   
	    
	    $config = array();
	    //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='JavaScript:void(0);';  
	    $config['total_rows'] = $this->Product_model->get_product_wise_vendor_list_count($arg);
	    $config['per_page'] = $limit;
	    $config['uri_segment']=4;
	    $config['num_links'] = 3;
	    $config['display_pages'] = TRUE;
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

	   //Tính start cho từng phân đoạn (limit) trong trường hợp hiện thị số trang thực tế $config['use_page_numbers'] = TRUE
	   //$start = $this->uri->segment(3);
	    $start=empty($start)?0:($start-1)*$limit;
	    $arg['start']=$start;
	    $arg['limit']=$limit;
	    $list['rows'] = $this->Product_model->get_product_wise_vendor_list($arg);
	    $list['search_order_status'] =$arg['search_order_status'];
	    $list['unit_type_list']=$this->Product_model->GetUnitList();
		$list['currency_list']=$this->Product_model->GetCurrencyList();
	    $table = '';
	    $table = $this->load->view('admin/product/product_wise_vendor_view_ajax',$list,TRUE);
	    $data =array (
	       "table"=>$table,
	       "page"=>$page_link,
	       "row_count"=>$config['total_rows']
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    
	    
	}
	// AJAX PAGINATION END

	function delete_product_tagged_vendor()
	{
		$del_id_str=$this->input->get('del_id_str');
		$del_id_arr= explode(",", $del_id_str);
		for($i=0;$i<count($del_id_arr);$i++)
		{
		    $edit_id=$del_id_arr[$i];
		    $data = array(
		                   'is_deleted'=>'Y',
		                   'updated_datetime'=>date('Y-m-d H:i:s')
		                  );                
		    $this->vendor_model->update_vendor_product($data,$edit_id);
		}
		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	}  

	function update_product_tagged_vendor()
	{
		$ptb_price=$this->input->get('ptb_price');
		$ptb_currency_type=$this->input->get('ptb_currency_type');
		$ptb_unit=$this->input->get('ptb_unit');
		$ptb_unit_type=$this->input->get('ptb_unit_type');
		$edit_id=$this->input->get('id');
		$data = array(
	                   'price'=>$ptb_price,
	                   'currency_type'=>$ptb_currency_type,
	                   'unit'=>$ptb_unit,
	                   'unit_type'=>$ptb_unit_type,
	                   'updated_datetime'=>date('Y-m-d H:i:s')
	                  );                
	    $this->vendor_model->update_vendor_product($data,$edit_id);
		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	} 

	function add_vendor_ajax()
    {
    	$product_varient_id=$this->input->post('v_product_varient_id');
        $company_name = $this->input->post('v_company_name'); 
        $contact_person = $this->input->post('v_contact_person');
        $designation = $this->input->post('v_designation');
        $mobile = $this->input->post('v_mobile');
        $email = $this->input->post('v_email');
        $address = $this->input->post('v_address');  


        $price = $this->input->post('v_price');  
        $currency_type = $this->input->post('v_currency_type');  
        $unit = $this->input->post('v_unit');  
        $unit_type = $this->input->post('v_unit_type');  

        $post_data=array('company_name'=>$company_name,
						 'contact_person'=>$contact_person,								 
						 'designation'=>$designation,
						 'mobile'=>$mobile,
						 'email'=>$email,
						 'address'=>$address,
					     'create_date'=>date('Y-m-d')
						 );
		$vendor_id=$this->vendor_model->CreateVendor($post_data);

		$tagged_data=array('vendor_id'=>$vendor_id,
						'product_varient_id'=>$product_varient_id,
						'price'=>$price,
						'currency_type'=>$currency_type,
						'unit'=>$unit,
						'unit_type'=>$unit_type,
						'created_datetime'=>date("Y-m-d H:i:s")
						);					
		$this->vendor_model->add_vendor_product($tagged_data); 
		$status_str='success';  
        $result["status"] = $status_str;
        echo json_encode($result);
        exit(0);  
    }

	

	function add_image()
	{
		$product_id=$this->input->post('edit_id'); 
		$last_inserted_id='';
		$this->load->library('upload', '');
	    $this->load->library('image_lib', '');
    	if($product_id!='')
    	{    	
    		// ----------------------------------
    		// remove existing images 
    		$existing_images=$this->Product_model->GetProductWiseImagesList($product_id);
    		if(count($existing_images))
    		{
    			foreach($existing_images as $val)
    			{
					$this->delete_image($val['id']);
    			}
    		}
    		// remove existing images
    		// ----------------------------------
    		

    		if($_FILES['image_files']['tmp_name']!='')
			{
			
				$config = array(
							'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/",
							'allowed_types' => "gif|jpg|png|jpeg|pdf",
							'overwrite' => FALSE,
							'encrypt_name' => TRUE, 
							'max_size' => "2048000" // Can be set to particular file size , here it is 2 MB(2048 Kb)
							);
				$dataInfo = array();
				$files = $_FILES;
				$cpt = count($_FILES['image_files']['name']);
				for($i=0; $i<$cpt; $i++)
				{        
					$_FILES['image_files']['name']= $files['image_files']['name'][$i];
					$_FILES['image_files']['type']= $files['image_files']['type'][$i];
					$_FILES['image_files']['tmp_name']= $files['image_files']['tmp_name'][$i];
					$_FILES['image_files']['error']= $files['image_files']['error'][$i];
					$_FILES['image_files']['size']= $files['image_files']['size'][$i];    
					
					$this->upload->initialize($config);
					if($this->upload->do_upload('image_files'))
					{							
						$dataInfo = $this->upload->data();							
						$image_filename=$dataInfo['file_name']; //Image Name
		
						if($image_filename)
						{
							$config=NULL;
							
							$config['source_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/".$image_filename;
							$config['new_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/thumb/".$image_filename;					
							$config['height']	= '120';
							$this->image_lib->initialize($config);
							$this->image_lib->resize();
						}
						$data = array(
							'varient_id' => $product_id,
							'file_name' => $image_filename
						 );
					 	$last_inserted_id = $this->Product_model->CreateProductImage($data);
					}
				}
			}
	    	$status_str='success';
    	}
    	else
    	{
    		$status_str='id_missing';
    	}
		  
        $result["status"] = $status_str;
        $result['last_inserted_id']=$last_inserted_id;
        echo json_encode($result);
        exit(0);
	}

	function delete_image($id)
    {    	
    	if($id!='')
    	{
    		//################################################################//        
		    //############## DELETE EXISTING LOGO IMAGE IF ANY ###############//
	    	$existing_file=get_value("file_name","product_images","id=".$id);
		    if($existing_file!="")
		    {    
		        @unlink("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/".$existing_file);
		        @unlink("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/thumb/".$existing_file);
		    }
		    //################################################################// 

	    	$this->Product_model->RemoveImage($id);	    	
    	} 
    }

	function delete_existing_image()
    {
    	$id=$this->input->post('id'); 
    	if($id!='')
    	{
    		//################################################################//        
		    //############## DELETE EXISTING LOGO IMAGE IF ANY ###############//
	    	$existing_file=get_value("file_name","product_images","id=".$id);
		    if($existing_file!="")
		    {    
		        @unlink("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/".$existing_file);
		        @unlink("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/thumb/".$existing_file);
		    }
		    //################################################################// 

	    	$this->Product_model->RemoveImage($id);
	    	$status_str='success';
    	}
    	else
    	{
    		$status_str='id_missing';
    	}
		  
        $result["status"] = $status_str;
        echo json_encode($result);
        exit(0);  
    }

    function add_pdf()
	{
		$product_id=$this->input->post('edit_id'); 
		$last_inserted_id='';
		$this->load->library('upload', '');
    	if($product_id!='')
    	{    	
    		// ----------------------------------
    		// remove existing images 
    		$existing_pdf=$this->Product_model->GetProductWisePdfList($product_id);
    		if(count($existing_pdf))
    		{
    			foreach($existing_pdf as $val)
    			{
					$this->delete_pdf($val['id']);
    			}
    		}
    		// remove existing images
    		// ----------------------------------
    		

    		if($_FILES['pdf_files']['tmp_name']!='')
			{
			
				$config = array(
					'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/pdf/",
					'allowed_types' => "pdf",
					'overwrite' => FALSE,
					'encrypt_name' => FALSE, 
					'max_size' => "2048000" // Can be set to particular file size , here it is 2 MB(2048 Kb)
					);
				$dataInfo = array();
				$files = $_FILES;
				$cpt = count($_FILES['pdf_files']['name']);
				for($i=0; $i<$cpt; $i++)
				{           
					$_FILES['pdf_files']['name']= $files['pdf_files']['name'][$i];
					$_FILES['pdf_files']['type']= $files['pdf_files']['type'][$i];
					$_FILES['pdf_files']['tmp_name']= $files['pdf_files']['tmp_name'][$i];
					$_FILES['pdf_files']['error']= $files['pdf_files']['error'][$i];
					$_FILES['pdf_files']['size']= $files['pdf_files']['size'][$i];    
			
					$this->upload->initialize($config);
					//$this->upload->do_upload();
					if($this->upload->do_upload('pdf_files'))
					{							
						$dataInfo = $this->upload->data();							
						$image_filename=$dataInfo['file_name']; //Name					
						$data = array(
							'varient_id' => $product_id,
							'file_name' => $image_filename
						 );
						 $last_inserted_id = $this->Product_model->CreateProductPDF($data);
					}						
				}
			}
	    	$status_str='success';
    	}
    	else
    	{
    		$status_str='id_missing';
    	}
		  
        $result["status"] = $status_str;
        $result['last_inserted_id']=$last_inserted_id;
        echo json_encode($result);
        exit(0);
	}

	function delete_pdf($id)
    {    	
    	if($id!='')
    	{
	    	//################################################################//        
		    //############## DELETE EXISTING LOGO IMAGE IF ANY ###############//
	    	$existing_file=get_value("file_name","product_pdf","id=".$id);
		    if($existing_file!="")
		    {    
		        @unlink("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/pdf/".$existing_file);
		    }
		    //################################################################// 
	    	$this->Product_model->RemovePdf($id);    	
    	} 
    }

    function delete_existing_pdf()
    {
    	$id=$this->input->post('id'); 
    	if($id!='')
    	{
    		//################################################################//        
		    //############## DELETE EXISTING LOGO IMAGE IF ANY ###############//
	    	$existing_file=get_value("file_name","product_pdf","id=".$id);
		    if($existing_file!="")
		    {    
		        @unlink("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/pdf/".$existing_file);
		    }
		    //################################################################// 

	    	$this->Product_model->RemovePdf($id); 	

			$status_str='success';  
    	}
    	else
    	{
    		$status_str='id_missing';
    	}
    	
        $result["status"] = $status_str;
        echo json_encode($result);
        exit(0);  
    }

    function redirect_url($to_method='')
	{
		$this->session->set_userdata('referer_url',$_SERVER['HTTP_REFERER']);
		if($to_method=='add')
		{
			$url=$this->session->userdata['admin_session_data']['lms_url'];
			redirect($url.'/product/add');
		}
	}

    public function getprodlist_ajax()
	{
		$data=NULL;
		$searchtype=$this->input->post('searchtype');
		$search_group='';
		$search_category='';
		$search_keyword='';
		if($searchtype=='category')
		{
			$search_group=$this->input->post('search_p_group_q');
			$search_category=$this->input->post('search_p_category_q');	
		}
		else if($searchtype=='keyword')
		{
			$search_keyword=$this->input->post('search_keyword');
		}		
		$opportunity_id=$this->input->post('opportunity_id');
		$quotation_id=$this->input->post('quotation_id');
		$lead_id=$this->input->post('lead_id');
		$data['search_keyword']=$search_keyword;
		if($quotation_id!='')
		{
			//$get_selected_product_id=$this->Product_model->get_product_ids_by_opportunity_id($opportunity_id);
			$get_selected_product_id=$this->Product_model->get_product_ids_by_quotation_id($quotation_id);
			$temp_prod_id=implode(',', $get_selected_product_id);
		}
		else
		{
			$temp_prod_id=$this->input->post('temp_prod_id');
			//$temp_prod_id= substr($temp_prod_id, 0, -1);
			$temp_prod_id= rtrim($temp_prod_id,",");;	
		}
		
		$tagged_product=$this->lead_model->get_tagged_ps_list($lead_id,'L');

		$search_data=array(
						'temp_prod_id'=>$temp_prod_id,
						'lead_id'=>$lead_id,
						'search_keyword'=>$search_keyword,
						'search_group'=>$search_group,
						'search_category'=>$search_category
						);
		$data['temp_prod_id']=$temp_prod_id;
		$data['tagged_product']=$tagged_product;
		$data['product_list']=$this->Product_model->GetProductListFilter($search_data);

		$this->load->view('admin/product/product_list_ajax',$data);	
		// if($opportunity_id!='')
		// {
		// 	$data['opportunity_id']=$opportunity_id;
		// 	$this->load->view('admin/product/product_list_update_ajax',$data);
		// }
		// else
		// {
		// 	$this->load->view('admin/product/product_list_ajax',$data);	
		// }		
	}	

	public function getprodlistupdate_ajax()
	{
		$data=NULL;
		$search_keyword=$this->input->post('search_keyword');
		$data['opportunity_id']=$this->input->post('opportunity_id');
		$lead_id=$this->input->post('lead_id');
		// $temp_prod_id=$this->input->post('temp_prod_id');
		// $temp_prod_id= substr($temp_prod_id, 0, -1);
		$get_selected_product_id=$this->Product_model->get_product_ids_by_opportunity_id($data['opportunity_id']);
		//$temp_prod_id=implode(',', $get_selected_product_id);
		//$data['get_selected_product_id']=$temp_prod_id;

		$search_data=array(
						//'temp_prod_id'=>$temp_prod_id,
						'lead_id'=>$lead_id,
						'search_keyword'=>$search_keyword
						);

		$data['product_list']=$this->Product_model->GetProductListFilter($search_data);


		//$data['product_list']=$this->Product_model->GetProductListNotFilter($temp_prod_id);
		$this->load->view('admin/product/product_list_update_ajax',$data);
		//$this->load->view('admin/product/product_list_ajax',$data);
	}

	public function selectleadprodupdate_ajax()
	{
		$data=NULL;
		$prod_id=$this->input->post('prod_id');
		$opportunity_id=$this->input->post('opportunity_id');
		$data['opportunity_id']=$opportunity_id;
		$data['product_data']=array();
		$prod_arr=explode(',',$prod_id);
		$i=0;
		$date=date('Y-m-d');
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		
		foreach($prod_arr as $prod_data)
		{			
			if($prod_data!='')
			{				
				
				$chk_prod=$this->Opportunity_product_model->ProdExistCheck($prod_data,$opportunity_id);
				if(!$chk_prod)
				{					
					$data['product_data'][$i]=$this->Product_model->GetProductListById($prod_data);
					$data_prd=array(
									'opportunity_id'=>$opportunity_id,
									'product_id'=>$prod_data,
									'unit'=>$data['product_data'][$i]->unit,
									'unit_type'=>$data['product_data'][$i]->unit_type_id,
									'price'=>$data['product_data'][$i]->price,
									'currency_type'=>$data['product_data'][$i]->currency_type,
									'discount'=>'',
									'create_date'=>$date
									);					
					$create_opp_prod=$this->Opportunity_product_model->CreateOportunityProduct($data_prd);					
					$i++;
				}				
			}			
		}
		if($create_opp_prod)
		{
			$opportunity_data=$this->Opportunity_model->GetOpportunityData($opportunity_id);	
			$date=date("Y-m-d h:i:s");
			$ip_addr=$_SERVER['REMOTE_ADDR'];
			$message="&quot;".$opportunity_data->title."&quot; sent to buyer.";
			$lead_id=$opportunity_data->lead_id;
			$comment_title=QUOTATION_PRODUCT_UPDATE;
			$historydata=array(
								'title'=>$comment_title,
								'lead_id'=>$lead_id,
								'comment'=>addslashes($message),
								'create_date'=>$date,
								'user_id'=>$user_id,
								'ip_address'=>$ip_addr
								);
			$this->history_model->CreateHistory($historydata);
		}
		
		// $data['product_list']=$this->Opportunity_product_model->GetOpportunityProductList($opportunity_id);
		// $data['currency_list']=$this->Product_model->GetCurrencyList();
		// $data['unit_type_list']=$this->Product_model->GetUnitList();
		// $this->load->view('admin/product/product_selected_list_update_ajax',$data);

		$list=array();
		$list['opportunity_id']=$opportunity_id;
        $list['product_list']=$this->Opportunity_product_model->GetOpportunityProductList($opportunity_id);
        $list['selected_additional_charges']=$this->Opportunity_model->get_selected_additional_charges($opportunity_id);
    	$html = $this->load->view('admin/product/updated_product_selected_list_ajax',$list,TRUE);
    	$result['html'] = $html;
        echo json_encode($result);
        exit(0);
        
	}
	// ===============================================================
	// ===============================================================























	
	
	
	public function getskulist_ajax()
	{
		$data=NULL;
		$parent_id=$this->input->post('parent_id');
		$data['parent_id']=$parent_id;
		$data['sku_list']=$this->Product_model->GetSKUListAll($parent_id);
		$this->load->view('admin/product/sku_list_ajax',$data);
	}
	public function getskudata_ajax($varient_id)
	{
		$data=NULL;
		$varient_id=$this->input->post('varient_id');
		$data['sku_data']=$this->Product_model->GetSKUData($varient_id);
		$this->load->view('admin/product/sku_data_ajax',$data);
	}
	
	public function del_tmp_prod_ajax()
	{
		$prod_id=$this->input->post('prod_id');
		$user_id=$this->session->userdata['admin_session_data']['user_id'];		
		$this->Product_model->DeleteTempProduct($prod_id,$user_id);		
		// $data['product_list']=$this->Product_model->GetTempProductList($user_id);		
		// $this->load->view('admin/product/product_selected_list_ajax',$data);
		
	}
	
	public function update_temp_selected_product_ajax()
	{
		$pid=$this->input->post('pid');
		$field=$this->input->post('field');
		$value=$this->input->post('value');
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$data_post=array(						
						$field=>$value
						);
		$update_data=$this->Product_model->UpdateTempProductOnChangeAjax($data_post,$pid,$user_id);
		if($update_data==true)
        	$result["status"] = 'success';
        else
        	$result["status"] = 'fail';

        //$result['status']=$data_post;
		// $list=array();
		// $list['product_list']=$this->Product_model->GetTempProductList($user_id);
		// $list['currency_list']=$this->Product_model->GetCurrencyList();
		// $list['unit_type_list']=$this->Product_model->GetUnitList();
    	//$html = $this->load->view('admin/product/product_selected_list_ajax',$list,TRUE);

        $product_list=$this->Product_model->GetTempProductList($user_id);
        $sub_total=0;
		foreach($product_list as $output)
		{
			$item_gst_per= $output->gst;
			$item_sgst_per= ($item_gst_per/2);
			$item_cgst_per= ($item_gst_per/2);  
			$item_discount_per=$output->discount; 
			$item_price= $output->price;
			$item_qty=$output->unit;
			
			$item_total_amount=($item_price*$item_qty);
			$row_discount_amount=$item_total_amount*($item_discount_per/100);
			$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);

			$row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);
			$sub_total=$sub_total+$row_final_price;			
		}

		// -------------------------------
		$product=$this->Product_model->GetTempProduct($user_id,$pid);
		$item_gst_per= $product->gst;
		$item_sgst_per= ($item_gst_per/2);
		$item_cgst_per= ($item_gst_per/2);  
		$item_discount_per=$product->discount; 
		$item_price= $product->price;
		$item_qty=$product->unit;
		$item_total_amount=($item_price*$item_qty);
		$row_discount_amount=$item_total_amount*($item_discount_per/100);
		$row_gst_amount=($item_total_amount-$row_discount_amount)*($item_gst_per/100);
		$row_final_price=($item_total_amount+$row_gst_amount-$row_discount_amount);

    	//$result['html'] = $html;
    	$result['total_sale_price'] = number_format($row_final_price,2);
    	$result['sub_total'] = number_format($sub_total,2);
        echo json_encode($result);
        exit(0); 
	}

	
	public function update_quantity_ajax()
	{
		$prod_id=$this->input->post('prod_id');
		$quantity=$this->input->post('quantity');
		$price=$this->input->post('price');
		$discount=$this->input->post('discount');		
		$user_id=$this->session->userdata['admin_session_data']['user_id'];		
		$update_data=$this->Product_model->UpdateTempProduct($quantity,$price,$discount,$prod_id,$user_id);			
		$data['product_list']=$this->Product_model->GetTempProductList($user_id);		
		$this->load->view('admin/product/product_selected_list_ajax',$data);		
	}
	
	public function selectleadprod_ajax()
	{
		$data=NULL;
		$prod_id=$this->input->post('prod_id');
		$opp_id=$this->input->post('opp_id');
		$data['product_data']=array();
		$prod_arr=explode(',',$prod_id);
		$i=0;
		$date=date('Y-m-d');
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		foreach($prod_arr as $prod_data)
		{
			if($prod_data!='')
			{
				$chk_prod_temp=$this->Product_model->TempProdExistCheck($prod_data,$user_id);
				if(!$chk_prod_temp)
				{
					$data['product_data'][$i]=$this->Product_model->GetProductListById($prod_data);				
					
					$qtn=($data['product_data'][$i]->minimum_order_quantity>0)?$data['product_data'][$i]->minimum_order_quantity:'1';
					$new_data=array(
						'opportunity_id'=>$opp_id,
						'user_id'=>$this->session->userdata['admin_session_data']['user_id'],
						'product_id'=>$prod_data,
						'name'=>$data['product_data'][$i]->name,
						'unit'=>$data['product_data'][$i]->unit,
						'unit_type'=>$data['product_data'][$i]->unit_type,
						'quantity'=>$qtn,
						'price'=>$data['product_data'][$i]->price,
						'currency_type'=>$data['product_data'][$i]->currency_type,
						'discount'=>'',
						'gst'=>$data['product_data'][$i]->gst_percentage,
						'create_date'=>$date
					);
					
					$create_data=$this->Product_model->CreateTempProduct($new_data);
					$i++;
					
				}
				
			}			
		}
		
		// $data['product_list']=$this->Product_model->GetTempProductList($user_id);
		// $data['currency_list']=$this->Product_model->GetCurrencyList();
		// $data['unit_type_list']=$this->Product_model->GetUnitList();
		//$this->load->view('admin/product/product_selected_list_ajax',$data);
		
		$temp_product_list=$this->Product_model->GetTempProductList($user_id);
		$tmp_selected_prod_arr=array();
		foreach($temp_product_list as $prod)
		{
			$code=($prod->code)?' - '.$prod->code:'';
			$tmp_selected_prod_arr[]=	$prod->product_id.'@'.$prod->name.$code;	
		}

		
		// $lead_id=$this->input->post('lead_id');
		// $data=NULL;
		// $tagged_product=$this->lead_model->get_tagged_ps_list($lead_id,'L');
		// $search_data=array(
		// 				'temp_prod_id'=>'',
		// 				'lead_id'=>$lead_id,
		// 				'search_keyword'=>'',
		// 				'search_group'=>'',
		// 				'search_category'=>''
		// 				);
		// $data['temp_prod_id']=$temp_prod_id;
		// $data['tagged_product']=$tagged_product;
		// $data['product_list']=$this->Product_model->GetProductListFilter($search_data);
		
		$data['tagged_product']=array();
		$data['product_list']=array();
		$result['html'] =$this->load->view('admin/product/product_list_ajax',$data,true);
		
		$result['selected_prod_id'] = $tmp_selected_prod_arr;
        echo json_encode($result);
        exit(0);
	}
	
	
	
	public function update_prod_price_ajax()
	{
		$data=NULL;
		$price=$this->input->post('price');
		$prod_id=$this->input->post('prod_id');
		$data=array('price'=>$price);		
		$data['product_list']=$this->Product_model->UpdateProductVarient($data,$prod_id);
		$this->load->view('admin/product/product_list_ajax',$data);
	}
	
	public function addleadprod_ajax()
	{
		$data=NULL;
		$lead_id=$this->input->post('lead_id');
		$prod_id=$this->input->post('prod_id');
		$create_date=date('Y-m-d');
		
		$create_data=array('lead_id'=>$lead_id,'product_id'=>$prod_id,'unit'=>'1','unit_type'=>'1','price'=>'200','create_date'=>$create_date);
		
		$data['product_list']=$this->Product_model->CreateOpportunityProduct($create_data);
		$this->load->view('admin/product/product_list_ajax',$data);
	}
	
	
	public function getattributevalue()
	{
		$attribute_id=$this->input->post('attribute_id');
		$data['val_ids']=$this->input->post('values');
		$data['attribute_list']=$this->Product_attribute_model->GetAttributeList($attribute_id);
		$this->load->view('admin/product/ajax_get_attribute_val',$data); 
	}
	
	public function remove_image()
	{
		$image_id=$this->input->post('image_id');
		$product_id=$this->input->post('product_id');
		$remove=$this->Product_model->RemoveImage($image_id);
		//CheckUserSpace();
		$data['image_list']=$this->Product_model->GetProductImageList($product_id);
		$this->load->view('admin/product/ajax_get_imagelist',$data); 
	}


	public function del_prod_ajax()
	{		
		$parent_id=$this->input->post('id');
		$data['step']='3';
		$data['flag']='d';
		$updt_data=array('status'=>'1');
		$del_prod=$this->Product_model->DeleteProductVarient($updt_data,$parent_id);
		//CheckUserSpace();
		$data['sku_list']=$this->Product_model->GetProductSKUList($parent_id);
		$data['product_data']=$this->Product_model->GetSKUData($parent_id);		
		$data['attribute_list']=$this->Product_attribute_model->GetAttributeListDropdown();
		$data['unit_type_list']=$this->Product_model->GetUnitList();
		$data['currency_list']=$this->Product_model->GetCurrencyList();		
		$this->load->view('admin/product/del_product_ajax',$data);
	}
	
	public function del_variant_ajax()
	{		
		$id=$this->input->post('id');		
		$data['product_data']=$this->Product_model->GetSKUData($id);
		$parent_id=$data['product_data']->parent_id;
		$data['step']='3';
		$data['flag']='d';
		$updt_data=array('status'=>'1');
		$del_prod=$this->Product_model->DeleteVarient($updt_data,$id);
		$data['sku_list']=$this->Product_model->GetProductSKUList($parent_id);
		$this->load->view('admin/product/del_product_ajax',$data);
	}


	function add_product_ajax()
    {
        $p_vendor_id = $this->input->post('p_vendor_id'); 
        $p_name = $this->input->post('p_name');  
        $success_str=$p_vendor_id.' / '.$p_name;    
        $result["status"] = $success_str;
        echo json_encode($result);
        exit(0);  
    }

    public function download_brochure($file='')
	{	
		if($file!='')
		{	
			$this->load->helper(array('download'));	
			$file_name = base64_decode($file);
			$pth    =   file_get_contents("assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/pdf/".$file_name);
			force_download($file_name, $pth); 
			exit;
		}
	}

	function view_product_detail_ajax()
    {
    	$id = $this->input->post('cid');
    	$row=$this->Product_model->get_details($id);
    	$list['row']=$row;
    	$html = $this->load->view('admin/product/product_details_popup_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0);
    }

    function change_status()
    {        
        $id = $this->input->post('id');
        $curr_status = $this->input->post('curr_status');
        $session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];	
        if($curr_status==0)
        {
            $s=1;
            $disabled_reason = $this->input->post('disabled_reason'); 
        	$disabled_reason_text=get_value('title','product_disabled_reason','id='.$disabled_reason);
        }
        else
        {
            $s=0;
            $disabled_reason=0;
            $disabled_reason_text='';
        }

        $update=array(
                    'status'=>$s,
                    'product_last_modified_by'=>$user_id,
                    'disabled_reason'=>$disabled_reason,
                    'disabled_reason_text'=>$disabled_reason_text,
                    'date_modified'=>date("Y-m-d H:i:s")
                    );
        $this->Product_model->UpdateProductVarient($update,$id);
        $product_count=$this->Product_model->getProductsCount();
        $result["status"] = 'success';
        $result["approved_count"] = $product_count['approved_count'];
        $result["disabled_count"] = $product_count['disabled_count'];

        echo json_encode($result);
        exit(0);        
    }

    function delete_product()
    {        
        $id = $this->input->get('id');        
        $session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];	
        
        $update=array(
                    'is_deleted'=>'Y',
                    'product_last_modified_by'=>$user_id,
                    'date_modified'=>date("Y-m-d H:i:s")
                    );
        $this->Product_model->UpdateProductVarient($update,$id);
        $result["status"] = 'success';
        echo json_encode($result);
        exit(0);        
    }

    function get_product_edit_popup_view()
	{
	    $product_id = $this->input->get('product_id');
	    $editsection = $this->input->get('editsection');
	    $list=array();
	    $list['editsection']=$editsection;
	    $list['row']=$this->Product_model->GetProductDetail($product_id);
	    $html = $this->load->view('admin/product/product_edit_popup_view',$list,TRUE);

	    $data =array (
	       "html"=>$html
	    );
	   	
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function update_product()
    {
    	$msg='';
    	$p_editsection 	= $this->input->post('p_editsection');
    	$p_id 			= $this->input->post('p_id');
    	if($p_editsection=='description')
    	{
    		$p_name 				= $this->input->post('p_name');
	        $p_short_description 	= $this->input->post('p_short_description');
	        $p_long_description		= $this->input->post('p_long_description');
	        $session_data = $this->session->userdata('admin_session_data');
			$user_id = $session_data['user_id'];	
	        

	        $update=array(
	                    'name'=>$p_name,
	                    'description'=>$p_short_description,
	                    'long_description'=>$p_long_description,
	                    'product_last_modified_by'=>$user_id,
	                    'date_modified'=>date("Y-m-d H:i:s")
	                    );
	        $this->Product_model->UpdateProductVarient($update,$p_id);
    	}

    	if($p_editsection=='youtube')
    	{
    		$p_youtube_video 	= $this->input->post('p_youtube_video');
	        $session_data = $this->session->userdata('admin_session_data');
			$user_id = $session_data['user_id'];	
	        

	        $update=array(
	                    'youtube_video'=>$p_youtube_video,
	                    'product_last_modified_by'=>$user_id,
	                    'date_modified'=>date("Y-m-d H:i:s")
	                    );
	        $this->Product_model->UpdateProductVarient($update,$p_id);
    	}
        
        if($p_editsection=='brochures')
    	{    		
	        $session_data = $this->session->userdata('admin_session_data');
			$user_id = $session_data['user_id'];	
	        
	        if($_FILES['pdf_files']['tmp_name'])
	        {
	        	$this->Product_model->DeleteProductPDF($p_id);

				$config = array(
					'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/pdf/",
					'allowed_types' => "pdf",
					'overwrite' => TRUE,
					'encrypt_name' => FALSE, 
					'max_size' => "2048000"
				);
				$dataInfo = array();
				$files = $_FILES;
				$cpt = count($_FILES['pdf_files']['name']);
				for($i=0; $i<$cpt; $i++)
				{           
					$_FILES['pdf_files']['name']= $files['pdf_files']['name'][$i];
					$_FILES['pdf_files']['type']= $files['pdf_files']['type'][$i];
					$_FILES['pdf_files']['tmp_name']= $files['pdf_files']['tmp_name'][$i];
					$_FILES['pdf_files']['error']= $files['pdf_files']['error'][$i];
					$_FILES['pdf_files']['size']= $files['pdf_files']['size'][$i];    
					$this->upload->initialize($config);
					$this->upload->do_upload();
					if($this->upload->do_upload('pdf_files'))
					{						
						$dataInfo = $this->upload->data();						
						$image_filename=$dataInfo['file_name']; //Image Name
						$data = array(
							'varient_id' => $p_id,
							'file_name' => $image_filename
						);								
						$this->Product_model->CreateProductPDF($data);
					}								
				}
			}

	        $update=array(	                    
	                    'product_last_modified_by'=>$user_id,
	                    'date_modified'=>date("Y-m-d H:i:s")
	                    );
	        $this->Product_model->UpdateProductVarient($update,$p_id);
    	}

    	if($p_editsection=='image')
    	{
    		$session_data = $this->session->userdata('admin_session_data');
			$user_id = $session_data['user_id'];
    		//$img_index 	= $this->input->post('img_index');
	        //$image_files='image_files_'.$p_id.'_'.$img_index;
	        $image_files='image_files'; 
	       
	        if(isset($_FILES[$image_files]['tmp_name']))
	        {
	        	$this->load->library('upload', '');
	            $this->load->library('image_lib', '');
				if($_FILES[$image_files]['tmp_name'])
		        {
		            $dataInfo = array();
		            $files = $_FILES;
		            $cpt = count($_FILES[$image_files]['name']);
		            for($i=0; $i<$cpt; $i++)
		            {      
		                $config = array(
		                        'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/",
		                        'allowed_types' => "gif|jpg|png|jpeg",
		                        'overwrite' => FALSE,
		                        'encrypt_name' => TRUE, 
		                        'max_size' => "2048000" // Can be set to particular file size , here it is 2 MB(2048 Kb)
		                        );
		                        
		                $_FILES[$image_files]['name']= $files[$image_files]['name'][$i];
		                $_FILES[$image_files]['type']= $files[$image_files]['type'][$i];
		                $_FILES[$image_files]['tmp_name']= $files[$image_files]['tmp_name'][$i];
		                $_FILES[$image_files]['error']= $files[$image_files]['error'][$i];
		                $_FILES[$image_files]['size']= $files[$image_files]['size'][$i];    
		                
		                $this->upload->initialize($config);
		                if(!$this->upload->do_upload($image_files))
		                {
		                	$msg=$this->upload->display_errors();
		                }
		                else
		                {
		                    $dataInfo = $this->upload->data();                        
		                    $image_filename=$dataInfo['file_name']; //Image Name	                    
		                    $admin_session_data = $this->session->userdata('admin_session_data'); 
		                    $session_id = $admin_session_data['session_id'];
		                    if($image_filename)
		                    {
		                        //###################################################//
		                        //########### CHECK UPLOAD IMAGE DIMENSION ##########//

		                        // $imgarr= $this->config->item('image_valid_dimensions');
		                        // $imgarr_data=$imgarr["product_image_medium"];
		                        // $data_explode=explode("|",$imgarr_data);

		                        // $define_image_width=$data_explode[0];
		                        // $define_image_height=$data_explode[1];
		                        $define_image_width='100';
		                        $define_image_height='100';

		                        //########### CHECK UPLOAD IMAGE DIMENSION ##########//
		                        //###################################################//

		                        $config=NULL;                            
		                        $config['source_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/".$image_filename;
		                        $config['new_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/thumb/".$image_filename;                 
		                        $config['height']   = $define_image_height;
		                        $this->image_lib->initialize($config);
		                        $this->image_lib->resize();
		                    }     

		                    // ----------------------------------
		                    // insert
		                    $post_data = array(
								'varient_id' => $p_id,
								'file_name' => $image_filename
							);
							$return = $this->Product_model->CreateProductImage($post_data);	
							// insert   
							// ----------------------------------
		                     if($return!=false)
		                     {
		                     	// ----------------------------------
			                    // update main table
								$update=array(	                    
						                 'product_last_modified_by'=>$user_id,
						                 'date_modified'=>date("Y-m-d H:i:s")
						                 );
								$this->Product_model->UpdateProductVarient($update,$p_id);
								// update main table
								// ----------------------------------
		                     }
							
		                }
		                
		            }   
		        }
			}
    	}
    	

		$result["status"] = 'success';
		$result["msg"] = $msg;
		echo json_encode($result);
		exit(0);
       
    }

    function update_product_image()
    {
    	$p_editsection 	= $this->input->post('p_editsection');
    	$p_id 			= $this->input->post('p_id');    	    		
        $session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];	
        $img_index 	= $this->input->post('img_index');
        //$image_files='image_files_'.$p_id.'_'.$img_index;
        $image_files='image_files'; 
        $msg='';
        if(isset($_FILES[$image_files]['tmp_name']))
        {
        	$this->load->library('upload', '');
            $this->load->library('image_lib', '');
			if($_FILES[$image_files]['tmp_name'])
	        {
	            $dataInfo = array();
	            $files = $_FILES;
	            $cpt = count($_FILES[$image_files]['name']);
	            for($i=0; $i<$cpt; $i++)
	            {      
	                $config = array(
	                        'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/",
	                        'allowed_types' => "gif|jpg|png|jpeg",
	                        'overwrite' => FALSE,
	                        'encrypt_name' => TRUE, 
	                        'max_size' => "2048000" // Can be set to particular file size , here it is 2 MB(2048 Kb)
	                        );
	                        
	                $_FILES[$image_files]['name']= $files[$image_files]['name'][$i];
	                $_FILES[$image_files]['type']= $files[$image_files]['type'][$i];
	                $_FILES[$image_files]['tmp_name']= $files[$image_files]['tmp_name'][$i];
	                $_FILES[$image_files]['error']= $files[$image_files]['error'][$i];
	                $_FILES[$image_files]['size']= $files[$image_files]['size'][$i];    
	                
	                $this->upload->initialize($config);
	                if(!$this->upload->do_upload($image_files))
	                {
	                	$msg=$this->upload->display_errors();
	                }
	                else
	                {
	                    $dataInfo = $this->upload->data();                        
	                    $image_filename=$dataInfo['file_name']; //Image Name	                    
	                    $admin_session_data = $this->session->userdata('admin_session_data'); 
	                    $session_id = $admin_session_data['session_id'];
	                    if($image_filename)
	                    {
	                        //###################################################//
	                        //########### CHECK UPLOAD IMAGE DIMENSION ##########//

	                        // $imgarr= $this->config->item('image_valid_dimensions');
	                        // $imgarr_data=$imgarr["product_image_medium"];
	                        // $data_explode=explode("|",$imgarr_data);

	                        // $define_image_width=$data_explode[0];
	                        // $define_image_height=$data_explode[1];
	                        $define_image_width='100';
	                        $define_image_height='100';

	                        //########### CHECK UPLOAD IMAGE DIMENSION ##########//
	                        //###################################################//

	                        $config=NULL;                            
	                        $config['source_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/".$image_filename;
	                        $config['new_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/thumb/".$image_filename;                 
	                        $config['height']   = $define_image_height;
	                        $this->image_lib->initialize($config);
	                        $this->image_lib->resize();
	                    }     

	                    // ----------------------------------
	                    // insert
	                    $post_data = array(
							'varient_id' => $p_id,
							'file_name' => $image_filename
						);
						$return = $this->Product_model->CreateProductImage($post_data);	
						// insert   
						// ----------------------------------
	                     if($return!=false)
	                     {
	                     	// ----------------------------------
		                    // update main table
							$update=array(	                    
					                 'product_last_modified_by'=>$user_id,
					                 'date_modified'=>date("Y-m-d H:i:s")
					                 );
							$this->Product_model->UpdateProductVarient($update,$p_id);
							// update main table
							// ----------------------------------
	                     }
						
	                }
	                
	            }   
	        }
			

				
	    	

			$result["status"] = 'success';
			echo json_encode($result);
			exit(0);
		}
		
    }

    function delete_youtube_video()
	{
		$p_id=$this->input->get('id');		
        $session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];	
        $update=array(
                    'youtube_video'=>'',
                    'product_last_modified_by'=>$user_id,
                    'date_modified'=>date("Y-m-d H:i:s")
                    );
        $this->Product_model->UpdateProductVarient($update,$p_id);
		$return['status'] = "success";
		echo json_encode($return);
		exit(0);
	} 

	function delete_brochure()
	{
		$p_id=$this->input->get('id');		
        $session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];

		$r=$this->Product_model->DeleteProductPDF($p_id);
		if($r==true)
		{
			$update=array(
                    'product_last_modified_by'=>$user_id,
                    'date_modified'=>date("Y-m-d H:i:s")
                    );
        	$this->Product_model->UpdateProductVarient($update,$p_id);
        	$status="success";
		}
		else
		{
			$status="fail";
		}
        
		$return['status'] = $status;
		echo json_encode($return);
		exit(0);
	} 


	function test_mail()
	{
		$this->load->library('mail_lib');
        $mail_data = array();
        $mail_data['from_mail']     = 'info@lmsbaba.com';
        $mail_data['from_name']     = 'LMS BABA';        
        $mail_data['to_mail']       = 'arupporel123@gmail.com';
        $mail_data['subject']       = 'Testing mail.';
        $mail_data['message']       = "Testing--3";
        $mail_data['attach']        = array();
        $mail_return = $this->mail_lib->send_mail($mail_data);
        if($mail_return)
        	die("sent");
    	else
    		die("not sent");
	}

	function add_group_category()
    {
    	$parent_id = $this->input->post('parent_id');
        $cat_name = $this->input->post('cat_name'); 
        $post_data=array(
			'parent_id'=>$parent_id,
			'name'=>$cat_name
			);
		$this->Group_wise_category_model->add($post_data);
		$status_str='success';
		//$result["status"] = $status_str.' / '.$parent_id.' / '.$cat_name;
		$result["status"] = $status_str;
		$result["msg"] = ($parent_id==0)?'The group successfully added':'The category successfully added';
        echo json_encode($result);
        exit(0);  
	}
	
	function rander_group_cat_option()
	{	
		$parent_id = $this->input->post('parent_id');
		$selected_id = ($this->input->post('selected_id'))?$this->input->post('selected_id'):'';
		$get_option=$this->Group_wise_category_model->get_list($parent_id);
		$list['is_group']=($parent_id==0)?'Y':'N';
		$list['option_list']=$get_option;
		$list['selected_id']=$selected_id;
		$option = $this->load->view('admin/product/group_cat_option_view_ajax',$list,TRUE);
		$result["option_rander"] = $option;
        echo json_encode($result);
        exit(0);  
	}

	function rander_group_cat_add_view()
	{	
		$view_type = $this->input->post('view_type');		
		$group_option_list=$this->Group_wise_category_model->get_list(0);
		$list['view_type']=$view_type;
		$list['group_option_list']=$group_option_list;
		$html = $this->load->view('admin/product/rander_group_cat_add_view_ajax',$list,TRUE);
		$result["html"] = $html;
        echo json_encode($result);
        exit(0);  
	}

	public function add_edit_ajax()
	{			
		
		$success_str='';
		if($this->validate_form_data() == TRUE)
		{
			$this->load->library('upload', '');
			$this->load->library('image_lib', '');
			$product_id=$this->input->post('product_id');
			
			$group_id=$this->input->post('group_id');
			$cate_id=$this->input->post('cate_id');
			$name=$this->input->post('name');
			$code=$this->input->post('code');
			$hsn_code=$this->input->post('hsn_code');
			$gst_percentage=$this->input->post('gst_percentage');
			$price=$this->input->post('price');
			$currency_type=$this->input->post('currency_type');
			$unit=$this->input->post('unit');
			$unit_type=$this->input->post('unit_type');
			$youtube_video=$this->input->post('youtube_video');
			$description=$this->input->post('description');
			$long_description=$this->input->post('long_description');
			$product_available_for=$this->input->post('product_available_for');
			$minimum_order_quantity=$this->input->post('minimum_order_quantity');
			$vendor_productvarient_tag=$this->input->post('vendor_productvarient_tag');
			$session_data = $this->session->userdata('admin_session_data');
			$user_id = $session_data['user_id'];
	
			if($product_id=='')
			{
				$data_prd_varient=array(
									'parent_id'=>'0',
									'group_id'=>$group_id,
									'cate_id'=>$cate_id,
									'name'=>$name,
									'currency_type'=>$currency_type,
									'unit_type'=>$unit_type,
									'description'=>$description,
									'long_description'=>$long_description,
									'code'=>$code,
									'price'=>$price,
									'unit'=>$unit,
									'minimum_order_quantity'=>$minimum_order_quantity,
									'gst_percentage'=>$gst_percentage,
									'hsn_code'=>$hsn_code,
									'youtube_video'=>$youtube_video,
									'product_available_for'=>$product_available_for,
									'product_added_by'=>$user_id,
									'product_last_modified_by'=>$user_id,
									'date_modified'=>date("Y-m-d H:i:s"),
									'date_added'=>date("Y-m-d H:i:s")
								);
				$create_prod_varient=$this->Product_model->CreateProductVarient($data_prd_varient);
			}
			else
			{
				$create_prod_varient=$product_id;
				$data_prd_varient=array(
									'group_id'=>$group_id,
									'cate_id'=>$cate_id,
									'name'=>$name,
									'currency_type'=>$currency_type,
									'unit_type'=>$unit_type,
									'description'=>$description,
									'long_description'=>$long_description,
									'code'=>$code,
									'price'=>$price,
									'unit'=>$unit,
									'minimum_order_quantity'=>$minimum_order_quantity,
									'gst_percentage'=>$gst_percentage,
									'hsn_code'=>$hsn_code,
									'youtube_video'=>$youtube_video,
									'product_available_for'=>$product_available_for,
									'product_added_by'=>$user_id,
									'product_last_modified_by'=>$user_id,
									'date_modified'=>date("Y-m-d H:i:s")
								);
				$this->Product_model->UpdateProductVarient($data_prd_varient,$product_id);
			}
			
								
			if($_FILES['image_files']['tmp_name']!='')
			{
				$config = array(
					'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/",
					'allowed_types' => "gif|jpg|png|jpeg|pdf",
					'overwrite' => FALSE,
					'encrypt_name' => TRUE, 
					'max_size' => "2048000"
				);
				$dataInfo = array();
				$files = $_FILES;
				$cpt = count($_FILES['image_files']['name']);
				for($i=0; $i<$cpt; $i++){        
					$_FILES['image_files']['name']= $files['image_files']['name'][$i];
					$_FILES['image_files']['type']= $files['image_files']['type'][$i];
					$_FILES['image_files']['tmp_name']= $files['image_files']['tmp_name'][$i];
					$_FILES['image_files']['error']= $files['image_files']['error'][$i];
					$_FILES['image_files']['size']= $files['image_files']['size'][$i];    
					$this->upload->initialize($config);
					if($this->upload->do_upload('image_files')){						
						$dataInfo = $this->upload->data();						
						$image_filename=$dataInfo['file_name']; //Image Name			
						if($image_filename){
							$config=array();							
							$config['source_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/".$image_filename;
							$config['new_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/thumb/".$image_filename;	
							$config['height']	= '120';
							$this->image_lib->initialize($config); 
							$this->image_lib->resize();
						}
						$data = array(
							'varient_id' => $create_prod_varient,
							'file_name' => $image_filename
						);
						$result_set = $this->Product_model->CreateProductImage($data);	
					}
				}
			}
			
			if($_FILES['image_files2']['tmp_name']!='')
			{
				$config = array(
					'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/",
					'allowed_types' => "gif|jpg|png|jpeg|pdf",
					'overwrite' => FALSE,
					'encrypt_name' => TRUE, 
					'max_size' => "2048000"
				);
				$dataInfo = array();
				$files = $_FILES;
				$cpt = count($_FILES['image_files2']['name']);
				for($i=0; $i<$cpt; $i++){        
					$_FILES['image_files2']['name']= $files['image_files2']['name'][$i];
					$_FILES['image_files2']['type']= $files['image_files2']['type'][$i];
					$_FILES['image_files2']['tmp_name']= $files['image_files2']['tmp_name'][$i];
					$_FILES['image_files2']['error']= $files['image_files2']['error'][$i];
					$_FILES['image_files2']['size']= $files['image_files2']['size'][$i];    
					$this->upload->initialize($config);
					if($this->upload->do_upload('image_files2')){						
						$dataInfo = $this->upload->data();						
						$image_filename=$dataInfo['file_name']; //Image Name			
						if($image_filename){
							$config=array();							
							$config['source_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/".$image_filename;
							$config['new_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/thumb/".$image_filename;	
							$config['height']	= '120';
							$this->image_lib->initialize($config); 
							$this->image_lib->resize();
						}
						$data = array(
							'varient_id' => $create_prod_varient,
							'file_name' => $image_filename
						);
						$result_set = $this->Product_model->CreateProductImage($data);	
					}
				}
			}

			if($_FILES['image_files3']['tmp_name']!='')
			{
				$config = array(
					'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/",
					'allowed_types' => "gif|jpg|png|jpeg",
					'overwrite' => FALSE,
					'encrypt_name' => TRUE, 
					'max_size' => "2048000"
				);
				$dataInfo = array();
				$files = $_FILES;
				$cpt = count($_FILES['image_files3']['name']);
				for($i=0; $i<$cpt; $i++){        
					$_FILES['image_files3']['name']= $files['image_files3']['name'][$i];
					$_FILES['image_files3']['type']= $files['image_files3']['type'][$i];
					$_FILES['image_files3']['tmp_name']= $files['image_files3']['tmp_name'][$i];
					$_FILES['image_files3']['error']= $files['image_files3']['error'][$i];
					$_FILES['image_files3']['size']= $files['image_files3']['size'][$i];    
					$this->upload->initialize($config);
					if($this->upload->do_upload('image_files3')){						
						$dataInfo = $this->upload->data();						
						$image_filename=$dataInfo['file_name']; //Image Name			
						if($image_filename){
							$config=array();							
							$config['source_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/".$image_filename;
							$config['new_image']  = "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/thumb/".$image_filename;	
							$config['height']	= '120';
							$this->image_lib->initialize($config); 
							$this->image_lib->resize();
						}
						$data = array(
							'varient_id' => $create_prod_varient,
							'file_name' => $image_filename
						);
						$result_set = $this->Product_model->CreateProductImage($data);	
					}
				}
			}

			if($_FILES['pdf_files']['tmp_name'])
			{
				$config = array(
					'upload_path' => "assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/pdf/",
					'allowed_types' => "pdf",
					'overwrite' => TRUE,
					'encrypt_name' => FALSE, 
					'max_size' => "2048000"
				);
				$dataInfo = array();
				$files = $_FILES;
				$cpt = count($_FILES['pdf_files']['name']);
				for($i=0; $i<$cpt; $i++){           
					$_FILES['pdf_files']['name']= $files['pdf_files']['name'][$i];
					$_FILES['pdf_files']['type']= $files['pdf_files']['type'][$i];
					$_FILES['pdf_files']['tmp_name']= $files['pdf_files']['tmp_name'][$i];
					$_FILES['pdf_files']['error']= $files['pdf_files']['error'][$i];
					$_FILES['pdf_files']['size']= $files['pdf_files']['size'][$i];    
					$this->upload->initialize($config);
					$this->upload->do_upload();
					if($this->upload->do_upload('pdf_files')){						
						$dataInfo = $this->upload->data();						
						$image_filename=$dataInfo['file_name']; //Image Name
						$data = array(
							'varient_id' => $create_prod_varient,
							'file_name' => $image_filename
						);								
						$this->Product_model->CreateProductPDF($data);
					}								
				}
			}
			
			if($create_prod_varient)
			{
				$this->Product_model->remove_already_tagged($create_prod_varient);
				$vendors_str=trim($this->input->post('vendors'),'^');
				if($vendors_str)
				{					
					$vendors_arr=array();
					$vendors=explode("^",$vendors_str);
					foreach ($vendors as $vendor) 
					{
						$vdr=explode("_", $vendor);
						$vdr[0]=trim($vdr[0],'@');
						$chk_tagged=$this->Product_model->chk_already_tagged($vdr[0],$create_prod_varient);
						if($chk_tagged==0)
						{
							$vdr_arr=array(
								'vendor_id'=>$vdr[0],
								'product_varient_id'=>$create_prod_varient,
								'delivery_time'=>'',
								'delivery_time_unit'=>'',						
								'price'=>$vdr[1],
								'currency_type'=>$vdr[2],
								'unit'=>$vdr[3],
								'unit_type'=>$vdr[4],
								'created_datetime'=>date("Y-m-d H:i:s"),
								'updated_datetime'=>date("Y-m-d H:i:s")
							);
							$vendors_arr[]=$vdr_arr;
						}
					}
					if(count($vendors_arr))
					{
						$res=$this->vendor_model->add_batch_vendor_product($vendors_arr);
						if($res)
						{
							$msg='Product saved successfully.';
						}
						else
						{
							$msg='Product saved successfully.But Vendor not tagged!';
						}
					}
					else
					{
						$msg='Product saved successfully.';
					}
					
				}
				else
				{
					$msg='Product and vendor saved successfully.';
				}
			}
			else
			{				
				$msg='Product saved successfully but no vendor tagged with the product.';
			}

			$status='success';
			//$msg='Product added successfully.';
		}
		else
		{
			$status='error';
			$msg = validation_errors(); // 'duplicate';
			//$this->session->set_flashdata('error_msg', $msg);
		}

		$result["status"] = $status;
		$result["msg"] = $msg;
		// $result['postdata']=$data_prd_varient;
		echo json_encode($result);
		exit(0);
							
	}
	
	function delete_image_ajax()
    {        
        $id = $this->input->post('img_id'); 
        $this->delete_image($id);
        $result["status"] = 'success';
        echo json_encode($result);
        exit(0);        
    }
	
	function delete_pdf_ajax()
    {        
        $id = $this->input->post('pdf_id'); 
        $this->delete_pdf($id);
        $result["status"] = 'success';
        echo json_encode($result);
        exit(0);        
    }
	
	function bulk_product_update_list()
    {
        $action = $this->input->post('action');
	    $arg['filter_by']=$action;
		$field='';
		$field_label='';
		if($action=='with_gst')
		{
			$field='gst_percentage';
			$field_label='GST %';
		}
		else if($action=='without_gst')
		{
			$field='gst_percentage';
			$field_label='GST %';
		}
		else if($action=='with_code')
		{
			$field='code';
			$field_label='Product Code';
		}
		else if($action=='without_code')
		{
			$field='code';
			$field_label='Product Code';
		}
		else if($action=='with_hsn')
		{
			$field='hsn_code';
			$field_label='HSN Code';
		}
		else if($action=='without_hsn')
		{
			$field='hsn_code';
			$field_label='HSN Code';
		}
		else if($action=='with_price')
		{
			$field='price';
			$field_label='Selling Price';
		}
		else if($action=='without_price')
		{
			$field='price';
			$field_label='Selling Price';
		}
		
    	$list['rows']=$this->Product_model->get_list_for_bulk_update($arg);
		$list['field']=$field;
		$list['field_label']=$field_label;
		$html = $this->load->view('admin/product/product_list_bulk_update_view_ajax',$list,TRUE);
        $result["status"] = 'success';
		$result["html"] = $html;
        echo json_encode($result);
        exit(0);        
    }
	
	function bulk_product_update()
	{
		$id = $this->input->post('id');
		$field = $this->input->post('field');
		$field_value = $this->input->post('field_value');
		$existing_value=get_value("$field","product_varient","id=".$id);
		$validate=TRUE;
		$msg='';
		$result["status"] = 'success';	
		if($existing_value!=$field_value)
		{
			if($field_value=='')
			{
				$validate=FALSE;
				$msg='Field value should not be blank..';	 
			}
			else
			{
				if($field=='hsn_code')
				{				
					// Validate alphanumeric
					if (ctype_alnum($field_value)) {
						// Valid
						$validate=TRUE;
					} else {
						// Invalid
						$validate=FALSE;
						$msg='The input value is not alphanumeric..';
					}
				}
				if($field=='code')
				{				
					$validate=TRUE;
				}
				else
				{
					if(is_numeric($field_value))
					{  					
						$validate=TRUE;
					}
					else
					{
						$validate=FALSE;
						$msg='The input value is not numeric..';					
					}
				}				
			}
			
			
			
			if($validate==TRUE)
			{
	
				$data_update=array(
							"$field"=>$field_value,
							'date_modified'=>date("Y-m-d H:i:s")
							);
				$this->Product_model->UpdateProductVarient($data_update,$id);
				$result["is_updated"] = 'Y';				
			}
			else
			{
				$result["status"] = 'fail';				           
			}
		}
		else
		{
			$result["is_updated"] = 'N';
		}
			
			
		
		$result["msg"] = $msg;
        echo json_encode($result);
        exit(0);
	}
	
	public function tag_selected_vendor_ajax()
	{
		$success_str='';
		$product_id=$this->input->post('product_id');
		$vendors_str=trim($this->input->post('vendors'),'^');
		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		if($product_id>0 && $vendors_str!='')
		{
			$data_prd_varient=array(
							'product_added_by'=>$user_id,
							'product_last_modified_by'=>$user_id,
							'date_modified'=>date("Y-m-d H:i:s")
						);
			$this->Product_model->UpdateProductVarient($data_prd_varient,$product_id);
		
			$this->Product_model->remove_already_tagged($product_id);
			$vendors_arr=array();
			$vendors=explode("^",$vendors_str);
			foreach ($vendors as $vendor) 
			{
				$vdr=explode("_", $vendor);
				$vdr[0]=trim($vdr[0],'@');
				$chk_tagged=$this->Product_model->chk_already_tagged($vdr[0],$product_id);
				if($chk_tagged==0)
				{
					$vdr_arr=array(
						'vendor_id'=>$vdr[0],
						'product_varient_id'=>$product_id,
						'delivery_time'=>'',
						'delivery_time_unit'=>'',						
						'price'=>$vdr[1],
						'currency_type'=>$vdr[2],
						'unit'=>$vdr[3],
						'unit_type'=>$vdr[4],
						'created_datetime'=>date("Y-m-d H:i:s"),
						'updated_datetime'=>date("Y-m-d H:i:s")
					);
					$vendors_arr[]=$vdr_arr;
				}
			}
			if(count($vendors_arr))
			{
				$res=$this->vendor_model->add_batch_vendor_product($vendors_arr);
				if($res)
				{
					$msg='Vendor successfully tagged.';
				}					
			}
			else
			{
				$msg='';
			}
			
		}
		else
		{				
			$msg='';
		}

		$status='success';
		//$msg='Product added successfully.';
		$result["status"] = $status;
		$result["msg"] = $msg;
		echo json_encode($result);
		exit(0);
							
	}
	
	function view_product_wise_vendor_list_ajax()
    {
    	$id = $this->input->post('pid');
    	$argument['product_id']=$id;
    	$list['rows'] = $this->Product_model->get_product_wise_vendor_list_all($argument);
    	$html = $this->load->view('admin/product/product_wise_vendor_list_modal_view_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0);
    }

    public function copy($copy_pid='')
	{		
		if($copy_pid)
		{
			$row=$this->Product_model->GetProductDetail($copy_pid);			
			if(count($row)>0)
			{
				$name_arr=explode(" ",$row->name);
				$unique_no=substr(number_format(time() * rand(),0,'',''),0,6);
				$group_id=$row->group_id;
				$cate_id=$row->cate_id;
				$name=$row->name;
				$code=$name_arr[0].$unique_no;
				$hsn_code=$row->hsn_code;
				$gst_percentage=$row->gst_percentage;
				$price=$row->price;
				$currency_type=$row->currency_type;
				$unit=$row->unit;
				$unit_type=$row->unit_type;
				$youtube_video=$row->youtube_video;
				$description=$row->description;
				$long_description=$row->long_description;
				$product_available_for=$row->product_available_for;
				$minimum_order_quantity=$row->minimum_order_quantity;
				$vendor_productvarient_tag=$row->v_tag_str;
				$session_data=$this->session->userdata('admin_session_data');
				$user_id=$session_data['user_id'];

				$data_prd_varient=array(
										'parent_id'=>'0',
										'group_id'=>$group_id,
										'cate_id'=>$cate_id,
										'name'=>$name,
										'currency_type'=>$currency_type,
										'unit_type'=>$unit_type,
										'description'=>$description,
										'long_description'=>$long_description,
										'code'=>$code,
										'price'=>$price,
										'unit'=>$unit,
										'minimum_order_quantity'=>$minimum_order_quantity,
										'gst_percentage'=>$gst_percentage,
										'hsn_code'=>$hsn_code,
										'youtube_video'=>$youtube_video,
										'product_available_for'=>$product_available_for,
										'product_added_by'=>$user_id,
										'product_last_modified_by'=>$user_id,
										'date_modified'=>date("Y-m-d H:i:s"),
										'date_added'=>date("Y-m-d H:i:s")
									);	

				$create_prod_varient=$this->Product_model->CreateProductVarient($data_prd_varient);	
				
				$image_id=array();
				$image_name=array();
				$iamges_str=trim($row->image_files,'^');
				if($iamges_str)
				{
					$images=explode("^",$iamges_str);
					foreach ($images as $image) 
					{
						$image_arr=explode("_", $image);	
						$image_id[]=$image_arr[0];
						$image_name[]=$image_arr[1];
					}		
				}
				
				if($image_name[0])
				{
					$flag=0;
					$image_filename=$image_name[0];
					$new_image_filename=$unique_no.''.$image_filename;
					// NORMAL UMAGE
					$source_file="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/".$image_filename;

					$target_file="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/".$new_image_filename;

					if (file_exists($source_file)){
					    @copy($source_file,$target_file);
					    $flag=1;
					}else{
					    //echo "File does not exist.";
					}

					// THUMB IMAGE 
					$source_file_thumb="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/thumb/".$image_filename;

					$target_file_thumb="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/thumb/".$new_image_filename;

					if (file_exists($source_file_thumb)){
					    @copy($source_file_thumb,$target_file_thumb);
					    $flag=1;
					}else{
					    //echo "File does not exist.";
					}	

					if($flag==1)
					{
						$data = array(
							'varient_id' => $create_prod_varient,
							'file_name' => $new_image_filename
						);
						$result_set = $this->Product_model->CreateProductImage($data);
					}					
				}

				if($image_name[1])
				{
					$flag=0;
					$image_filename=$image_name[1];
					$new_image_filename=$unique_no.''.$image_filename;
					// NORMAL IMAGE
					$source_file="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/".$image_filename;

					$target_file="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/".$new_image_filename;

					if (file_exists($source_file)){
					    copy($source_file,$target_file);
					    $flag=1;
					}else{
					    //echo "File does not exist.";
					}

					// THUMB IMAGE 
					$source_file_thumb="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/thumb/".$image_filename;

					$target_file_thumb="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/thumb/".$new_image_filename;

					if (file_exists($source_file_thumb)){
					    copy($source_file_thumb,$target_file_thumb);
					    $flag=1;
					}else{
					    //echo "File does not exist.";
					}	

					if($flag==1)
					{
						$data = array(
							'varient_id' => $create_prod_varient,
							'file_name' => $new_image_filename
						);
						$result_set = $this->Product_model->CreateProductImage($data);
					}	
				}

				if($image_name[2])
				{
					$flag=0;
					$image_filename=$image_name[2];
					$new_image_filename=$unique_no.''.$image_filename;
					// NORMAL IMAGE
					$source_file="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/".$image_filename;

					$target_file="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/".$new_image_filename;

					if (file_exists($source_file)){
					    copy($source_file,$target_file);
					    $flag=1;
					}else{
					    //echo "File does not exist.";
					}

					// THUMB IMAGE 
					$source_file_thumb="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/thumb/".$image_filename;

					$target_file_thumb="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/thumb/".$new_image_filename;

					if (file_exists($source_file_thumb)){
					    copy($source_file_thumb,$target_file_thumb);
					    $flag=1;
					}else{
					    //echo "File does not exist.";
					}

					if($flag==1)
					{
						$data = array(
							'varient_id' => $create_prod_varient,
							'file_name' => $new_image_filename
						);
						$result_set = $this->Product_model->CreateProductImage($data);
					}		
				}

				if($row->pdf_file_name)
				{
					$flag=0;
					$pdf_filename=$row->pdf_file_name;
					$new_pdf_filename=$unique_no.''.$pdf_filename;			
					$pdf_source_file="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/pdf/".$pdf_filename;

					$pdf_target_file="assets/uploads/clients/".$this->session->userdata['admin_session_data']['client_id']."/product/pdf/".$new_pdf_filename;

					if (file_exists($pdf_source_file)){
					    copy($pdf_source_file,$pdf_target_file);
					    $flag=1;
					}else{
					    //echo "File does not exist.";
					}

					if($flag==1)
					{
						$data = array(
							'varient_id' => $create_prod_varient,
							'file_name' => $new_pdf_filename
						);								
						$this->Product_model->CreateProductPDF($data);
					}
					
				}		
				
				if($create_prod_varient)
				{
					$this->Product_model->remove_already_tagged($create_prod_varient);
					$vendors_str=trim($row->v_tag_str,'^');
					if($vendors_str)
					{					
						$vendors_arr=array();
						$vendors=explode("^",$vendors_str);

						foreach ($vendors as $vendor) 
						{
							$vdr=explode("_", $vendor);
							$vdr[0]=trim($vdr[0],'@');
							$chk_tagged=$this->Product_model->chk_already_tagged($vdr[0],$create_prod_varient);
							if($chk_tagged==0)
							{
								$vdr_arr=array(
									'vendor_id'=>$vdr[0],
									'product_varient_id'=>$create_prod_varient,
									'delivery_time'=>'',
									'delivery_time_unit'=>'',						
									'price'=>$vdr[1],
									'currency_type'=>$vdr[2],
									'unit'=>$vdr[3],
									'unit_type'=>$vdr[4],
									'created_datetime'=>date("Y-m-d H:i:s"),
									'updated_datetime'=>date("Y-m-d H:i:s")
								);
								$vendors_arr[]=$vdr_arr;
							}
						}
						if(count($vendors_arr))
						{
							$res=$this->vendor_model->add_batch_vendor_product($vendors_arr);					
						}
					}			
				}
				redirect(base_url().$this->session->userdata['admin_session_data']['lms_url'].'/product/edit/'.$create_prod_varient);
			}			
		}
	}

	

	public function getpoprodlist_ajax()
	{
		$data=NULL;
		$search_keyword=$this->input->post('search_keyword');
		$po_pro_forma_invoice_id=$this->input->post('po_pro_forma_invoice_id');
		$po_invoice_id=$this->input->post('po_invoice_id');		
		$is_pfi_or_inv=$this->input->post('is_pfi_or_inv');	

		$data['search_keyword']=$search_keyword;
		
		$temp_prod_id=$this->input->post('po_selected_prod_id');
		if($temp_prod_id)
		{
			$temp_prod_id= rtrim($temp_prod_id,",");
		}			
		


		$search_data=array(
						'po_pro_forma_invoice_id'=>$po_pro_forma_invoice_id,
						'po_invoice_id'=>$po_invoice_id,
						'search_keyword'=>$search_keyword,
						'is_pfi_or_inv'=>$is_pfi_or_inv,
						'temp_prod_id'=>$temp_prod_id
						);	
		// print_r($search_data);die();	
		$data['product_list']=$this->Product_model->GetProductListFilter($search_data);

		$this->load->view('admin/product/po_product_list_ajax',$data);
			
	}

	public function selectpoprod_ajax()
	{
		$data=NULL;
		$prod_id=$this->input->post('prod_id');
		$data['product_data']=array();
		$prod_arr=explode(',',$prod_id);
		$i=0;
		$date=date('Y-m-d');
		$user_id=$this->session->userdata['admin_session_data']['user_id'];
		$tmp_selected_prod_arr=array();
		foreach($prod_arr as $prod_data)
		{
			if($prod_data!='')
			{
				$data['product_data'][$i]=$this->Product_model->GetProductListById($prod_data);	

				$code=($data['product_data'][$i]->code)?' - '.$data['product_data'][$i]->code:'';
				$tmp_selected_prod_arr[]=	$prod_data.'@'.$data['product_data'][$i]->name.$code;
			}			
		}	
		

		$result['selected_prod_id'] = $tmp_selected_prod_arr;
        echo json_encode($result);
        exit(0);
	}

	public function csv_upload_and_import_ajax()
	{		
		if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
		{
			$status = 'fail';
			$status='';
			$error_msg='';
			$success_msg='';
			$file_name='';	
			// If file uploaded
            if(is_uploaded_file($_FILES['csv_file']['tmp_name']))
            { 
            			
				$session_data = $this->session->userdata('admin_session_data');
				$user_id = $session_data['user_id'];

            	$file_name=time();
				$config = array(
					'upload_path' => "assets/",
					'allowed_types' => "csv|CSV",
					'overwrite' => TRUE,
					'encrypt_name' => FALSE,
					//'file_name' => $file_name.'.csv',
					//'max_size' => "2048000" 
					);
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
			    
				if (!$this->upload->do_upload('csv_file'))
				{	
					$error_msg=strip_tags($this->upload->display_errors());
					$status = 'fail';	
				}
				else
				{	
					$data = array('upload_data' => $this->upload->data());
					$file_name = $data['upload_data']['file_name'];

					$csvData = file_get_contents("assets/".$file_name);
					$lines = explode(PHP_EOL, $csvData);
					$array = array();
					foreach ($lines as $line) 
					{
					    $array[] = str_getcsv($line);
					}
					@unlink("assets/".$file_name);
					$error_flag=0;

					if(count($array))
					{							
						$this->Product_model->delete_lead_csv_upload_tmp_by_user($user_id);

						$i=0;
						foreach($array AS $row)
						{
							$f1=trim($row[0]);
							$f2=trim($row[1]);
							$f3=trim($row[2]);
							$f4=trim($row[3]);
							$f5=trim($row[4]);
							$f6=trim($row[5]);
							$f7=trim($row[6]);
							$f8=trim($row[7]);
							$f9=trim($row[8]);
							$f10=trim($row[9]);
							$f11=trim($row[10]);
							$f12=trim($row[11]);
							$f13=trim($row[12]);						
							
							if($i==0)
							{
								if(strtolower($f1)=='category' && 
									strtolower($f2)=='name' && 
									strtolower($f3)=='currency_code' && 
									strtolower($f4)=='unit_type' && 
									strtolower($f5)=='description' && 
									strtolower($f6)=='code' && 
									strtolower($f7)=='price' && 
									strtolower($f8)=='unit' && 
									strtolower($f9)=='minimum_order_quantity' && 
									strtolower($f10)=='gst_percentage' && 
									strtolower($f11)=='hsn_code' && 
									strtolower($f12)=='youtube_video' && 
									strtolower($f13)=='product_available_for')
								{

								}
								else
								{
									$first_row_error_log='';
									if(strtolower($f1)!='category')
									{
										$first_row_error_log .='A1, ';
									}
									if(strtolower($f2)!='name')
									{
										$first_row_error_log .='B1, ';
									}
									if(strtolower($f3)!='currency_code')
									{
										$first_row_error_log .='C1, ';
									}
									if(strtolower($f4)!='unit_type')
									{
										$first_row_error_log .='D1, ';
									}
									if(strtolower($f5)!='description')
									{
										$first_row_error_log .='E1, ';
									}
									if(strtolower($f6)!='code')
									{
										$first_row_error_log .='F1, ';
									}
									if(strtolower($f7)!='price')
									{
										$first_row_error_log .='G1, ';
									}
									if(strtolower($f8)!='unit')
									{
										$first_row_error_log .='H1, ';
									}
									if(strtolower($f9)!='minimum_order_quantity')
									{
										$first_row_error_log .='I1, ';
									}
									if(strtolower($f10)!='gst_percentage')
									{
										$first_row_error_log .='J1, ';
									}
									if(strtolower($f11)!='hsn_code')
									{
										$first_row_error_log .='K1, ';
									}
									if(strtolower($f12)!='youtube_video')
									{
										$first_row_error_log .='L1, ';
									}
									if(strtolower($f13)!='product_available_for')
									{
										$first_row_error_log .='M1, ';
									}
									
									$first_row_error_log=rtrim($first_row_error_log,", ");
									$error_in=($first_row_error_log)?' Error in :'.$first_row_error_log:'';
									$error_msg='Error on csv heading, please maintain the order of the heading and also give the exact name as defined in sample of csv.'.$error_in;
									$status = 'Error_heading';
									$error_flag++;
									break;
								}
							}
							
							if($i>0)
							{
								if($f1=='' && 
									$f2=='' && 
									$f3=='' && 
									$f4=='' && 
									$f5=='' && 
									$f6=='' && 
									$f7=='' && 
									$f8=='' && 
									$f9=='' && 
									$f10=='' && 
									$f11=='' && 
									$f12=='' && 
									$f13=='')
								{

								}
								else
								{
									$group_id='';
									$cate_id=$f1;
									$name=$f2;
									$currency_type=$f3;
									$unit_type=$f4;
									$description=$f5;	
									$code=$f6;
									$price=$f7;
									$unit=$f8;
									$minimum_order_quantity=$f9;	
									$gst_percentage=$f10;
									$hsn_code=$f11;
									$youtube_video=$f12;
									$product_available_for=strtoupper($f13);
																		
									$data_prd_varient=array(										
										'group_id'=>$group_id,
										'cate_id'=>$cate_id,
										'name'=>$name,
										'currency_type'=>$currency_type,
										'unit_type'=>$unit_type,
										'description'=>$description,										
										'code'=>$code,
										'price'=>$price,
										'unit'=>$unit,
										'minimum_order_quantity'=>$minimum_order_quantity,
										'gst_percentage'=>$gst_percentage,
										'hsn_code'=>$hsn_code,
										'youtube_video'=>$youtube_video,
										'product_available_for'=>$product_available_for,										
										'uploaded_datetime'=>date("Y-m-d H:i:s"),
										'uploaded_by'=>$user_id
									);	
																	
									$this->Product_model->add_csv_upload_tmp($data_prd_varient);																	
								}								
							}
							$i++;
						}						
					}

					if($error_flag==0)
					{
						$csv_upload_data_chk=$this->Product_model->csv_upload_tmp_data_chk($user_id);					
						if(count($csv_upload_data_chk)==0)
						{						
							$this->add_upload_csv_data($user_id);
							// data insert to lead and company table
							$status = 'success';
						}
						else
						{
							$error_msg='Error on csv data, please see the error log.';
							$status = 'Error_log';
						}
					}
																
				}				
            }
            else
            {
            	$error_msg='Error on file upload, please try again.';
            	$status = 'fail';	
            }			
				
			$success_msg=$file_name;
			$result["status"] = $status;
			$result["error_msg"] = $error_msg;
			$result["success_msg"] = $success_msg;
			$result['file_name']=$file_name;		
			echo json_encode($result);
			exit(0);
		}		
	}

	public function add_upload_csv_data($user_id)
	{
		$rows=$this->Product_model->get_csv_upload_tmp_list($user_id);
		if(count($rows))
		{
			foreach($rows AS $row)
			{
				$cate_info=$this->Product_model->get_category_info_by_id($row['cate_id']);																
				$cate_id_tmp=$cate_info['id'];
				$group_id_tmp=$cate_info['group_id'];
				$currency_type_tmp=$this->Product_model->get_currency_id_by_code($row['currency_type']);
				$unit_type_tmp=$this->Product_model->get_unit_type_id_by_name($row['unit_type']);	

				$group_id=$group_id_tmp;
				$cate_id=$cate_id_tmp;
				$name=$row['name'];
				$code=$row['code'];
				$hsn_code=$row['hsn_code'];
				$gst_percentage=$row['gst_percentage'];
				$price=$row['price'];
				$currency_type=$currency_type_tmp;
				$unit=$row['unit'];
				$unit_type=$unit_type_tmp;
				$youtube_video=$row['youtube_video'];
				$description=$row['description'];									
				$product_available_for=strtoupper($row['product_available_for']);
				$minimum_order_quantity=$row['minimum_order_quantity'];	
				$user_id = $row['uploaded_by'];	
												
				$data_prd_varient=array(
					'parent_id'=>'0',
					'group_id'=>$group_id,
					'cate_id'=>$cate_id,
					'name'=>$name,
					'currency_type'=>$currency_type,
					'unit_type'=>$unit_type,
					'description'=>$description,
					'long_description'=>'',
					'code'=>$code,
					'price'=>$price,
					'unit'=>$unit,
					'minimum_order_quantity'=>$minimum_order_quantity,
					'gst_percentage'=>$gst_percentage,
					'hsn_code'=>$hsn_code,
					'youtube_video'=>$youtube_video,
					'product_available_for'=>$product_available_for,
					'product_added_by'=>$user_id,
					'product_last_modified_by'=>$user_id,
					'date_modified'=>date("Y-m-d H:i:s"),
					'date_added'=>date("Y-m-d H:i:s")
				);
				$pid=$this->Product_model->CreateProductVarient($data_prd_varient);
				
				$data_tmp=array('added_product_id'=>$pid,'is_added_as_product'=>'Y');
				$this->Product_model->update_csv_upload_tmp($data_tmp,$row['tmp_id']);
			}
			
		}		
	}

	public function download_lead_upload_sample()
	{
		$file_name='PRODUCT_UPLOAD_SAMPLE.csv';
		$file_path='assets/images/';
		if ( ! file_exists($file_path.$file_name))
	    {
	        echo 'file missing';
	    }
	    else
	    {
	        header('HTTP/1.1 200 OK');
	        header('Cache-Control: no-cache, must-revalidate');
	        header("Pragma: no-cache");
	        header("Expires: 0");
	        header("Content-type: text/csv");
	        header("Content-Disposition: attachment; filename=$file_name");
	        readfile($file_path . $file_name);
	        exit;
	    }
	}

	public function get_upload_csv_error_log_ajax()
	{
		$uploaded_csv_file_name=$this->input->post('uploaded_csv_file_name');
		$session_data = $this->session->userdata('admin_session_data');
		$user_id = $session_data['user_id'];
		$list=array(); 
		$list['error_log']=$this->Product_model->csv_upload_tmp_data_chk($user_id);
		$list['rows']=$this->Product_model->get_csv_upload_tmp_list($user_id);
		$total_error=0;
		$total_rows=count($list['rows']);
		foreach($list['rows'] AS $row)
		{
			if(count($list['error_log'][$row['tmp_id']]))
			{				
				$total_error++;
			}
		}
		$list['total_error']=$total_error;
		$list['total_rows']=$total_rows;
		$list['uploaded_csv_file_name']=$uploaded_csv_file_name;
    	$html = $this->load->view('admin/product/rander_upload_csv_error_log_ajax',$list,TRUE);
		$result['html'] = $html;
        echo json_encode($result);
        exit(0); 
	}
}
