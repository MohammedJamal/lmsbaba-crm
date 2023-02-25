<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_v2 extends CI_Controller {
	private $api_access_token = '';
	function __construct()
	{
		parent :: __construct();
		is_admin_logged_in();
		init_admin_element();
		$this->api_access_token = get_api_access_token();    
		$this->load->model(array("user_model","countries_model","states_model","cities_model","menu_model","product_model","lead_model","customer_model","Package_model","dashboard_v2_model","Source_model","Setting_model","Client_model"));	
		$this->load->model('Department_model','department_model');
	}

	public function index()
	{	
		// echo $this->input->ip_address();
		$data = array(); 
		$session_data = $this->session->userdata('admin_session_data');
		
		$user_id = $session_data['user_id'];
		$user_type = $session_data['user_type'];
		
		// ===========================================================
		// USER TREE VIEW
		if(strtolower($user_type)=='admin')
   		{
   			//$m_id=$this->user_model->get_manager_id($user_id);
   			$m_id=0;
   		}
   		else
   		{	
   			$m_id=$this->user_model->get_manager_id($user_id);
   		}
		$service_order_detail_ids_str='';
		$service_order_detail_ids_arr=array();
   		$service_order_detail_arr=$session_data['service_info']['all_available_service_order'];
		
		if(count($service_order_detail_arr))
		{
			foreach($service_order_detail_arr AS $service_order_detail)
			{				
				array_push($service_order_detail_ids_arr,$service_order_detail['id']);
			}
			$service_order_detail_ids_str=implode(",",$service_order_detail_ids_arr);
		}
		
	    $list['rows'] = $this->dashboard_v2_model->get_user_tree_list($user_id,0,$user_type,$service_order_detail_ids_str);
		$manager_id_arr = $this->dashboard_v2_model->get_manager_tree_list($user_id,0,$user_type,$service_order_detail_ids_str);
		$manager_id_str='';
		$m_list['rows'] =array();
		if(count($manager_id_arr)){
			$manager_id_str=implode(",",$manager_id_arr);
			
			$m_list['rows'] =$this->user_model->GetUserListByIds($manager_id_str);
		}
		
		// print_r($list['manager_rows']); die();
	    $list['user_id'] =	$user_id;    
	    $list['user_type'] =	$user_type;  
	    $data['user_list_treeview'] = $this->load->view('admin/dashboard_v2/user_tree_view_ajax',$list,TRUE);
		$data['manager_list_treeview'] = $this->load->view('admin/dashboard_v2/manager_tree_view_ajax',$m_list,TRUE);
		// USER TREE VIEW
		// ===========================================================
		
		// ===========================================================
		// COUNT SUMMARY REPORT
		// $data['company_count']=$this->customer_model->get_company_count();
		// $data['lead_count']=$this->lead_model->get_lead_count($user_type,$user_id);		
		// $data['lead_list']=$this->lead_model->get_lead_list_summery($user_type,$user_id);	

		
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type']; 
	    			
		
		$tmp_u_ids=$this->dashboard_v2_model->get_self_and_under_employee_ids($user_id,0,array(),$service_order_detail_ids_str);		
   		array_push($tmp_u_ids, $user_id);
   		$tmp_u_ids_str=implode(",", $tmp_u_ids);   		
		// $data['user_list']=$this->user_model->GetUserList($tmp_u_ids_str);

		$data['selected_user_id']=$tmp_u_ids_str;
		$data['currency_list']=$this->dashboard_v2_model->GetCurrencyList();
		$data['month_list']=get_months(12);
		$data['year_list']=get_fy(3);
		$data['source_list']=$this->Source_model->get_source();

		$company_info=$this->Setting_model->GetCompanyData();   
		$c2c_api_dial_url=$company_info['c2c_api_dial_url'];
		$c2c_api_userid=$company_info['c2c_api_userid'];
		$c2c_api_password=$company_info['c2c_api_password'];
		$c2c_api_client_name=$company_info['c2c_api_client_name']; 	
		if($c2c_api_dial_url!="" && $c2c_api_userid!="" && $c2c_api_password!="" && $c2c_api_client_name!="")
		{
		$c2c_is_active='Y';
		}
		else
		{
		$c2c_is_active='N';
		} 	
   	 	$data['c2c_is_active']=$c2c_is_active;
		$data['get_months']=get_months('24');
		$this->load->view('admin/dashboard_v2/dashboard_view',$data);
	}


	function get_dashboard_summery_count()
	{
	    
	    // FILTER DATA
		$arg['filter_is_count_or_percentage']=$this->input->get('filter_is_count_or_percentage');
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$list['is_count_or_percentage']=$arg['filter_is_count_or_percentage'];
    	$list['rows']=$this->dashboard_v2_model->get_dashboard_summery_count($arg);		
	    $html = '';	    
	    $html = $this->load->view('admin/dashboard_v2/rander_dashboard_summery_count_view_ajax',$list,TRUE);	
		
	    $data =array (
	       "html"=>$html
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function get_this_month_data()
	{
	
		$arg['selected_year_month']=$this->input->get('filter_selected_year_month');
		$year_month_arr= explode('-',$arg['selected_year_month']);
		$arg['selected_month']=$year_month_arr[1];
		$arg['selected_year']=$year_month_arr[0];
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');

		$list_data['data']=$this->dashboard_v2_model->get_this_month_data($arg);
		
		$html = '';
		$html = $this->load->view('admin/dashboard_v2/rander_this_month_data',$list_data,TRUE);
	    $data =array (
	       "html"=>$html
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function get_lead_pipeline_data()
	{
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
	    $list_data['data']=$this->dashboard_v2_model->get_lead_pipeline_data($arg);	

		$html = '';
		$html = $this->load->view('admin/dashboard_v2/rander_lead_pipeline_data.php',$list_data,TRUE);
	    $data =array (
	       "html"=>$html
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function get_opportunity_data()
	{
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
	    $list_data['data']=$this->dashboard_v2_model->get_opportunity_data($arg);	
		$list_data['currency_info']=$this->Setting_model->GetDefaultCurrency();
		$html = '';
		$html = $this->load->view('admin/dashboard_v2/rander_opportunity_data',$list_data,TRUE);
	    $data =array (
	       "html"=>$html
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function get_financial_review()
	{
		$data =array();
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
	    $result=$this->dashboard_v2_model->get_financial_review($arg);
		if(count($result))
		{
			// if($result[0]['total_month_1']>$result[0]['total_month_2'])
			// {
			// 	$p=$result[0]['total_month_1']-$result[0]['total_month_2'];
			// 	$since_last_month=($p*100)/$result[0]['total_month_1'];
			// 	$last_month_growth_type= 'P'; //Prpfit 
			// } else {
			// 	$l=$result[0]['total_month_2']-$result[0]['total_month_1'];
			// 	$since_last_month=($l*100)/$result[0]['total_month_2'];
			// 	$last_month_growth_type= 'L'; //Loss
			// }
			$since_last_month=(($result['total_month_1']/$result['total_month_2'])*100)-100;
			// if($since_last_month>0){
			// 	$last_month_growth_type= 'P'; //Prpfit
			// }
			// else{
			// 	$last_month_growth_type= 'L'; //Loss
			// }

			$list_data['data'] = array('since_last_month' => $since_last_month);
			$html = '';
			$html = $this->load->view('admin/dashboard_v2/rander_financial_review_data',$list_data,TRUE);
			$data = array(
				'html'=>$html,
				'month_name_1' => DateTime::createFromFormat('Ym', $result['month_1'])->format('M'),
				'month_name_2' => DateTime::createFromFormat('Ym', $result['month_2'])->format('M'),
				'month_name_3' => DateTime::createFromFormat('Ym', $result['month_3'])->format('M'),
				'month_name_4' => DateTime::createFromFormat('Ym', $result['month_4'])->format('M'),
				'month_name_5' => DateTime::createFromFormat('Ym', $result['month_5'])->format('M'),
				'month_name_6' => DateTime::createFromFormat('Ym', $result['month_6'])->format('M'),
				'month_value_1' => $result['total_month_1'],
				'month_value_2' => $result['total_month_2'],
				'month_value_3' => $result['total_month_3'],
				'month_value_4' => $result['total_month_4'],
				'month_value_5' => $result['total_month_5'],
				'month_value_6' => $result['total_month_6']
						
			); 
			// echo '<pre>';
			// print_r($data);
			// die;
		}
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function financial_review_download_csv()
	{
		$data =array();
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
	    $result=$this->dashboard_v2_model->get_financial_review($arg);
		if(count($result))
		{
			
			$month_name_1=DateTime::createFromFormat('Ym', $result['month_1'])->format('M, Y');
			$month_name_2=DateTime::createFromFormat('Ym', $result['month_2'])->format('M, Y');
			$month_name_3=DateTime::createFromFormat('Ym', $result['month_3'])->format('M, Y');
			$month_name_4=DateTime::createFromFormat('Ym', $result['month_4'])->format('M, Y');
			$month_name_5=DateTime::createFromFormat('Ym', $result['month_5'])->format('M, Y');
			$month_name_6=DateTime::createFromFormat('Ym', $result['month_6'])->format('M, Y');
			$month_value_1=$result['total_month_1'];
			$month_value_2=$result['total_month_2'];
			$month_value_3=$result['total_month_3'];
			$month_value_4=$result['total_month_4'];
			$month_value_5=$result['total_month_5'];
			$month_value_6=$result['total_month_6'];					
								
			$array[] = array('');
			$array[] = array($month_name_1,$month_value_1);
			$array[] = array($month_name_2,$month_value_2);
			$array[] = array($month_name_3,$month_value_3);
			$array[] = array($month_name_4,$month_value_4);
			$array[] = array($month_name_5,$month_value_5);
			$array[] = array($month_name_6,$month_value_6);

			// $array[] = array(
			// 				$month_name_1,
			// 				$month_name_2,
			// 				$month_name_3,
			// 				$month_name_4,
			// 				$month_name_5,
			// 				$month_name_6
			// 				);


			// $array[] = array(
			// 	$month_value_1,
			// 	$month_value_2,
			// 	$month_value_3,
			// 	$month_value_4,
			// 	$month_value_5,
			// 	$month_value_6
			// 	);
								
			$tmpName='Financial-Review';
			$tmpDate =  date("YmdHis");
			$csvFileName = $tmpName."_".$tmpDate.".csv";
			$this->load->helper('csv');        
			array_to_csv($array, $csvFileName);
			return TRUE;
		}
	}

	function get_sales_orders()
	{
		$data =array();
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
	    $result=$this->dashboard_v2_model->get_sales_orders($arg);
		if(count($result))
		{
			$list_data['data']=$result;
			$html = '';
			$html = $this->load->view('admin/dashboard_v2/rander_sales_orders_data',$list_data,TRUE);
			$data = array(
				'html'=>$html,
				'month_name_1' => DateTime::createFromFormat('Ym', $result[0]['month_1'])->format('M'),
				'month_name_2' => DateTime::createFromFormat('Ym', $result[0]['month_2'])->format('M'),
				'month_name_3' => DateTime::createFromFormat('Ym', $result[0]['month_3'])->format('M'),
				'month_name_4' => DateTime::createFromFormat('Ym', $result[0]['month_4'])->format('M'),
				'month_name_5' => DateTime::createFromFormat('Ym', $result[0]['month_5'])->format('M'),
				'month_name_6' => DateTime::createFromFormat('Ym', $result[0]['month_6'])->format('M'),
				'month_value_1' => $result[0]['total_month_1'],
				'month_value_2' => $result[0]['total_month_2'],
				'month_value_3' => $result[0]['total_month_3'],
				'month_value_4' => $result[0]['total_month_4'],
				'month_value_5' => $result[0]['total_month_5'],
				'month_value_6' => $result[0]['total_month_6']	
			);
		}		
		
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function get_sales_orders_download_csv()
	{
		$data =array();
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
	    $result=$this->dashboard_v2_model->get_sales_orders($arg);
		
		if(count($result))
		{
			$month_name_1=DateTime::createFromFormat('Ym', $result[0]['month_1'])->format('M, Y');
			$month_name_2=DateTime::createFromFormat('Ym', $result[0]['month_2'])->format('M, Y');
			$month_name_3=DateTime::createFromFormat('Ym', $result[0]['month_3'])->format('M, Y');
			$month_name_4=DateTime::createFromFormat('Ym', $result[0]['month_4'])->format('M, Y');
			$month_name_5=DateTime::createFromFormat('Ym', $result[0]['month_5'])->format('M, Y');
			$month_name_6=DateTime::createFromFormat('Ym', $result[0]['month_6'])->format('M, Y');
			$month_value_1=$result[0]['total_month_1'];
			$month_value_2=$result[0]['total_month_2'];
			$month_value_3=$result[0]['total_month_3'];
			$month_value_4=$result[0]['total_month_4'];
			$month_value_5=$result[0]['total_month_5'];
			$month_value_6=$result[0]['total_month_6'];	
			

			$array[] = array('');
			$array[] = array($month_name_1,$month_value_1);
			$array[] = array($month_name_2,$month_value_2);
			$array[] = array($month_name_3,$month_value_3);
			$array[] = array($month_name_4,$month_value_4);
			$array[] = array($month_name_5,$month_value_5);
			$array[] = array($month_name_6,$month_value_6);

			// $array[] = array(
			// 				$month_name_1,
			// 				$month_name_2,
			// 				$month_name_3,
			// 				$month_name_4,
			// 				$month_name_5,
			// 				$month_name_6
			// 				);
			// $array[] = array(
			// 	$month_value_1,
			// 	$month_value_2,
			// 	$month_value_3,
			// 	$month_value_4,
			// 	$month_value_5,
			// 	$month_value_6
			// 	);
								
			$tmpName='Sales-Order';
			$tmpDate =  date("YmdHis");
			$csvFileName = $tmpName."_".$tmpDate.".csv";
			$this->load->helper('csv');        
			array_to_csv($array, $csvFileName);
			return TRUE;
		}
	}

	function get_leads_opportunity()
	{
		$data =array();
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
	    $result=$this->dashboard_v2_model->get_leads_opportunity($arg);
		if(count($result))
		{
			$list_data['data']=$result;
			$html = '';
			$html = $this->load->view('admin/dashboard_v2/rander_leads_opportunity_data.php',$list_data,TRUE);

			$data = array(
				'html'=>$html,
				'month_name_1' => DateTime::createFromFormat('Ym', $result[0]['month_1'])->format('M'),
				'month_name_2' => DateTime::createFromFormat('Ym', $result[0]['month_2'])->format('M'),
				'month_name_3' => DateTime::createFromFormat('Ym', $result[0]['month_3'])->format('M'),
				'month_name_4' => DateTime::createFromFormat('Ym', $result[0]['month_4'])->format('M'),
				'month_name_5' => DateTime::createFromFormat('Ym', $result[0]['month_5'])->format('M'),
				'month_name_6' => DateTime::createFromFormat('Ym', $result[0]['month_6'])->format('M'),
				'month_value_1' => $result[0]['total_month_1'],
				'month_value_2' => $result[0]['total_month_2'],
				'month_value_3' => $result[0]['total_month_3'],
				'month_value_4' => $result[0]['total_month_4'],
				'month_value_5' => $result[0]['total_month_5'],
				'month_value_6' => $result[0]['total_month_6']	
			);

			// echo '<pre>';
			// print_r($data);
			// die;
		}	

	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function get_leads_opportunity_download_csv()
	{
		$data =array();
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
	    $result=$this->dashboard_v2_model->get_leads_opportunity($arg);
		if(count($result))
		{
			$month_name_1=DateTime::createFromFormat('Ym', $result[0]['month_1'])->format('M, Y');
			$month_name_2=DateTime::createFromFormat('Ym', $result[0]['month_2'])->format('M, Y');
			$month_name_3=DateTime::createFromFormat('Ym', $result[0]['month_3'])->format('M, Y');
			$month_name_4=DateTime::createFromFormat('Ym', $result[0]['month_4'])->format('M, Y');
			$month_name_5=DateTime::createFromFormat('Ym', $result[0]['month_5'])->format('M, Y');
			$month_name_6=DateTime::createFromFormat('Ym', $result[0]['month_6'])->format('M, Y');
			$month_value_1=$result[0]['total_month_1'];
			$month_value_2=$result[0]['total_month_2'];
			$month_value_3=$result[0]['total_month_3'];
			$month_value_4=$result[0]['total_month_4'];
			$month_value_5=$result[0]['total_month_5'];
			$month_value_6=$result[0]['total_month_6'];	
			

			$array[] = array('');
			$array[] = array($month_name_1,$month_value_1);
			$array[] = array($month_name_2,$month_value_2);
			$array[] = array($month_name_3,$month_value_3);
			$array[] = array($month_name_4,$month_value_4);
			$array[] = array($month_name_5,$month_value_5);
			$array[] = array($month_name_6,$month_value_6);

			// $array[] = array(
			// 				$month_name_1,
			// 				$month_name_2,
			// 				$month_name_3,
			// 				$month_name_4,
			// 				$month_name_5,
			// 				$month_name_6
			// 				);
			// $array[] = array(
			// 	$month_value_1,
			// 	$month_value_2,
			// 	$month_value_3,
			// 	$month_value_4,
			// 	$month_value_5,
			// 	$month_value_6
			// 	);
								
			$tmpName='Lead-Generation ';
			$tmpDate =  date("YmdHis");
			$csvFileName = $tmpName."_".$tmpDate.".csv";
			$this->load->helper('csv');        
			array_to_csv($array, $csvFileName);
			return TRUE;
		}
	}

	function get_sales_pipeline()
	{		
		$client_id=$this->session->userdata['admin_session_data']['client_id'];
		$arg=array();
		$client_db_info=$this->Client_model->get_details($client_id);		
		$arg['db_name']=$client_db_info->db_name;		
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');		
	    $result=$this->dashboard_v2_model->get_user_wise_sales_pipeline($arg);		
		/*
		$userid='';
		$unique_user='';
		$stage_list=array();
		for ($x = 0; $x <count($result); $x++) {

			// ---------- FOR STATGE LIST START ----------------------------
			if($x==0) $userid=$result[$x]['user_id']; 
			if($userid==$result[$x]['user_id']){
				$stage_list[]=[
					'id'=>$result[$x]['stage_id'],
					'name'=>$result[$x]['name']
				];
			}
			// ---------- FOR STATGE LIST END ----------------------------

			// ---------- FOR USER LIST START ----------------------------
			if($unique_user!=$result[$x]['user_id']){
				$unique_user = $result[$x]['user_id'];
				$user_list[]=[
					'id'=>$result[$x]['user_id'],
					'name'=>$result[$x]['user_name'],
					'photo'=>$result[$x]['user_image'],
					'designation'=>$result[$x]['user_designation']
				];
			} else {
				$unique_user = $result[$x]['user_id'];
			}
			// ---------- FOR USER LIST END ----------------------------

			// ---------- FOR SALES LIST START ----------------------------
				$sales_list[$result[$x]['user_id']][$result[$x]['stage_id']]=$result[$x]['total_lead'];
			// ---------- FOR SALES LIST END ----------------------------

		} 

		$list_data['stage_list']=$stage_list;
		$list_data['user_list']=$user_list;
		$list_data['sales_list']=$sales_list;
		*/
		$list_data['rows']=$result;
		$html = '';
		$html = $this->load->view('admin/dashboard_v2/rander_sales_pipeline_data',$list_data,TRUE);

		// echo '<pre>';
		// print_r($user_list);
		// print_r($sales_list);
		// die;		


		$data =array (
			"html"=>$html
			 );	
		// echo '<pre>';
		// print_r($data);
		// die;

	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function get_sales_pipeline_download_csv()
	{		
		$client_id=$this->session->userdata['admin_session_data']['client_id'];
		$arg=array();
		$client_db_info=$this->Client_model->get_details($client_id);		
		$arg['db_name']=$client_db_info->db_name;		
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');		
	    $rows=$this->dashboard_v2_model->get_user_wise_sales_pipeline($arg);
				
		$currency_info=$this->Setting_model->GetDefaultCurrency();		
		$array[] = array('');

		$title_tmp="Name,";
		if(count($rows)){ 
			$i=0;
			foreach($rows AS $row){ 
			if($i==0){					
					foreach($row AS $val){ 
						if($val){ 
							$title_tmp .=$val.","; 
						} 
					}  
					$title_tmp = rtrim($title_tmp,",");
					$array[] = explode(",",$title_tmp);
				}
				$i++;				
			}			
		}
		// print_r($array); die();
		if(count($rows)){ 
			
			$k=0;
			foreach($rows AS $row){ 
				$tmp_arr=array();
				if($k>0){
					$designation=($row['designation'])?' - '.$row['designation']:'';
					array_push($tmp_arr,$row['customer'].$designation);
					for($j=1;$j<=(count($row)-4);$j++){ 
						if(isset($row['stage_'.$j])){                            	;
						array_push($tmp_arr,$row['stage_'.$j]);
						}
					} 
					$array[] = $tmp_arr;
				}
				$k++;				
			}			
		}
		// print_r($array); die();			
		$tmpName='Sales-Pipeline';
		$tmpDate =  date("YmdHis");
		$csvFileName = $tmpName."_".$tmpDate.".csv";
		$this->load->helper('csv');        
		array_to_csv($array, $csvFileName);
		return TRUE;
		
		
	}


	function get_user_activity_report()
	{

	    // $start = $this->input->get('page'); 
	    // $this->load->library('pagination');
	    // $limit=10;
	    // $config = array();
	    $list=array();
	    $arg=array();
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');		
		// $config['base_url'] ='#';
		// $config['total_rows'] = $this->dashboard_v2_model->get_user_activity_report_count_v2($arg);
		// $config['per_page'] = $limit;
		// $config['uri_segment']=3;
		// $config['num_links'] = 1;
		// $config['use_page_numbers'] = TRUE;
		// $config['attributes'] = array('class' => 'v2_uar_report_page');
		// $config["full_tag_open"] = '<ul class="pagination">';
		// $config["full_tag_close"] = '</ul>';	
		// $config["first_link"] = "&laquo;";
		// $config["first_tag_open"] = "<li>";
		// $config["first_tag_close"] = "</li>";
		// $config["last_link"] = "&raquo;";
		// $config["last_tag_open"] = "<li>";
		// $config["last_tag_close"] = "</li>";
		// $config['next_link'] = '&gt;';
		// $config['next_tag_open'] = '<li>';
		// $config['next_tag_close'] = '<li>';
		// $config['prev_link'] = '&lt;';
		// $config['prev_tag_open'] = '<li>';
		// $config['prev_tag_close'] = '<li>';
		// $config['cur_tag_open'] = '<li class="active"><a href="#">';
		// $config['cur_tag_close'] = '</a></li>';
		// $config['num_tag_open'] = '<li>';
		// $config['num_tag_close'] = '</li>';
		// $start=empty($start)?0:($start-1)*$limit;
		// $arg['limit']=$limit;
		// $arg['start']=$start;			
		// $this->pagination->initialize($config);
		// $page_link = '';
		// $page_link = $this->pagination->create_links();
	    $list_data['data']=$this->dashboard_v2_model->get_user_activity_report_v2($arg);
		$list_data['currency_info']=$this->Setting_model->GetDefaultCurrency();
		$html = '';

		if($arg['filter_date_range_pre_define']!='')
		{			
			$to_date=date("Y-m-d");
			if($arg['filter_date_range_pre_define']=='TODAY')
			{
				$last_day=0;
			}
			if($arg['filter_date_range_pre_define']=='YESTERDAY')
			{
				$last_day=1;
				$to_date=date('Y-m-d', strtotime('-'.$last_day.' days'));	
			}
			if($arg['filter_date_range_pre_define']=='LAST7DAYS')
			{
				$last_day=7;
			}
			if($arg['filter_date_range_pre_define']=='LAST15DAYS')
			{
				$last_day=15;
			}
			if($arg['filter_date_range_pre_define']=='LAST30DAYS')
			{
				$last_day=30;
			}
			$from_date=date('Y-m-d', strtotime('-'.$last_day.' days'));			
		}
		else
		{
			if($arg['filter_date_range_user_define_from']!='' && $arg['filter_date_range_user_define_to']!='')
			{
				$from_date=date_display_format_to_db_format($arg['filter_date_range_user_define_from']);
				$to_date=date_display_format_to_db_format($arg['filter_date_range_user_define_to']);
			}
		}
		$list_data['from_date']=$from_date;
		$list_data['to_date']=$to_date;
		$html = $this->load->view('admin/dashboard_v2/rander_user_activity_report_data',$list_data,TRUE);	

		// PAGINATION COUNT INFO SHOW: START
		// $tmp_start=($start+1);		
		// $tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
		// $page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
		// PAGINATION COUNT INFO SHOW: END
		$data =array (
			"html"=>$html,
			// "page"=>$page_link,
			// "page_record_count_info"=>$page_record_count_info
			 );
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function get_user_activity_report_download_csv()
	{		
		
		$arg=array();
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');					
	    $rows=$this->dashboard_v2_model->get_user_activity_report_v2($arg);				
		$currency_info=$this->Setting_model->GetDefaultCurrency();		
		$array[] = array('');
		$array[] = array(
						'',
						'New Lead',
						'Calls',
						'meetings',
						'Updated',
						'Quoted',
						'Deal won',
						'Deal Lost',
						'Revenue'
						);

		if(count($rows) > 0)
		{
			foreach ($rows as $row) 
			{
				$designation=($row['designation'])?' - '.$row['designation']:'';
				$array[] = array(
								$row['name'].$designation,
								$row['total_new_lead'],
								$row['total_call_log'],
								$row['total_meeting'],
								$row['total_updated'],
								$row['total_quoted'],
								$row['total_dealwon'],
								$row['total_lost'],
								$currency_info['default_currency_code'].' '.number_format($row['total_revenue']),
								);
			}
		}			
		$tmpName='User-Activity-Report';
		$tmpDate =  date("YmdHis");
		$csvFileName = $tmpName."_".$tmpDate.".csv";
		$this->load->helper('csv');        
		array_to_csv($array, $csvFileName);
		return TRUE;
		
		
	}

	function get_daily_sales_report_v2()
	{

		// $start = $this->input->get('page'); 
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');

	    $result=$this->dashboard_v2_model->get_daily_sales_report_v2($arg);	

		// $daily_date='';
		// $unique_daily_date='';
		// $stage_list=array();
		// for ($x = 0; $x <count($result); $x++) {

		// 	// ---------- FOR STATGE LIST START ----------------------------
		// 	if($x==0) $daily_date=$result[$x]['daily_date']; 
		// 	if($daily_date==$result[$x]['daily_date']){
		// 		$stage_list[]=[
		// 			'id'=>$result[$x]['stage_id'],
		// 			'name'=>$result[$x]['name']
		// 		];
		// 	}
		// 	// ---------- FOR STATGE LIST END ----------------------------

		// 	// ---------- FOR USER LIST START ----------------------------
		// 	if($unique_daily_date!=$result[$x]['daily_date']){
		// 		$unique_daily_date = $result[$x]['daily_date'];
		// 		$daily_date_list[]=[
		// 			'daily_date'=>$result[$x]['daily_date']
		// 		];
		// 	} else {
		// 		$unique_daily_date = $result[$x]['daily_date'];
		// 	}
		// 	// ---------- FOR USER LIST END ----------------------------

		// 	// ---------- FOR SALES LIST START ----------------------------
		// 		$sales_list[$result[$x]['daily_date']][$result[$x]['stage_id']]=$result[$x]['total_lead'];
		// 	// ---------- FOR SALES LIST END ----------------------------
		// } 
		// $list_data['stage_list']=$stage_list;
		// $list_data['daily_date_list']=$daily_date_list;
		// $list_data['sales_list']=$sales_list;
		$list_data['data']=$result;
		$list_data['currency_info']=$this->Setting_model->GetDefaultCurrency();
		$html = '';
		$html = $this->load->view('admin/dashboard_v2/rander_daily_sales_report_data',$list_data,TRUE);

		// echo '<pre>';
		// print_r($user_list);
		// print_r($sales_list);
		// die;		


		$data =array (
			"html"=>$html
			 );	
		// echo '<pre>';
		// print_r($data);
		// die;

	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
		exit();
	}

	function get_daily_sales_report_v2_download_csv()
	{		
		
		$arg=array();
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');					
	    $rows=$this->dashboard_v2_model->get_daily_sales_report_v2($arg);	
		$currency_info=$this->Setting_model->GetDefaultCurrency();		
			
		$array[] = array('');
		$array[] = array(
						'',
						'New Lead',
						'Calls',
						'meetings',
						'Updated',
						'Quoted',
						'Deal won',
						'Deal Lost',
						'Revenue'
						);

		if(count($rows) > 0)
		{
			foreach ($rows as $row) 
			{
				
				$array[] = array(
								date_db_format_to_display_format($row['daily_date']),
								$row['total_new_lead'],
								$row['total_call_log'],
								$row['total_meeting'],
								$row['total_updated'],
								$row['total_quoted'],
								$row['total_dealwon'],
								$row['total_lost'],
								$currency_info['default_currency_code'].' '.number_format($row['total_revenue']),
								);
			}
		}			
		$tmpName='Daily-Sales-Report';
		$tmpDate =  date("YmdHis");
		$csvFileName = $tmpName."_".$tmpDate.".csv";
		$this->load->helper('csv');        
		array_to_csv($array, $csvFileName);
		return TRUE;
		
		
	}

	function get_lead_by_source_report()
	{

		// $start = $this->input->get('page'); 
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');
	    $result=$this->dashboard_v2_model->get_lead_by_source_report($arg);			
		$list_data['data']=$result;
		$list_data['currency_info']=$this->Setting_model->GetDefaultCurrency();
		$html = '';
		$html = $this->load->view('admin/dashboard_v2/rander_lead_by_source_report_data',$list_data,TRUE);
		$data =array (
			"html"=>$html
			 );	
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
		exit();
	}

	function get_lead_by_source_report_download_csv()
	{		
		
		$arg=array();
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');					
	    $rows=$this->dashboard_v2_model->get_lead_by_source_report($arg);	
		$currency_info=$this->Setting_model->GetDefaultCurrency();		
			
		$array[] = array('');
		$array[] = array(
						'',
						'New Lead',
						'Active Leads',
						'Quoted',
						'Deal Lost',
						'Deal won',
						'Revenue (INR)',						
						'Revenue (USD)',						
						'Conv.',
						);

		if(count($rows) > 0)
		{
			$total_po=0;
            foreach($rows AS $row){
                $total_po=$row['total_orders']+$total_po;
            }
			foreach ($rows as $row) 
			{
				// $INR_aov=($row['INR_revenue']>0)?ROUND($row['INR_revenue']/$row['is_won']):0;
				// $USD_aov=($row['USD_revenue']>0)?ROUND($row['USD_revenue']/$row['is_won']):0;
				$conversion=number_format(($row['is_won']/$row['total_lead']*100)).'%';
				$array[] = array(
								$row['source_name'],
								$row['total_lead'],
								$row['is_active'],
								$row['is_quoted'],
								$row['is_lost'],
								$row['is_won'],
								number_format($row['INR_revenue']),								
								number_format($row['USD_revenue']),								
								$conversion
								);
			}
		}			
		$tmpName='Leads-by-Source-Report';
		$tmpDate =  date("YmdHis");
		$csvFileName = $tmpName."_".$tmpDate.".csv";
		$this->load->helper('csv');        
		array_to_csv($array, $csvFileName);
		return TRUE;
		
		
	}

	function get_top_selling_produts()
	{
		$arg['limit']=5;
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');

	    $result=$this->dashboard_v2_model->get_top_selling_produts($arg);
		$list_data['rows']=$result;
		$list_data['currency_info']=$this->Setting_model->GetDefaultCurrency();
		$html = '';
		$html = $this->load->view('admin/dashboard_v2/rander_top_selling_produts_data',$list_data,TRUE);
		$data =array (
			"html"=>$html
			 );	
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function download_top_selling_products_csv()
    {	
		$arg['limit']='';	    
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');
		$rows=$this->dashboard_v2_model->get_top_selling_produts($arg);
		$currency_info=$this->Setting_model->GetDefaultCurrency();		
		$array[] = array('');
		$array[] = array(
						'Name',
						'Quoted',
						'Orders',
						'Revenue (INR)',
						'Revenue (USD)'
						);
		
		if(count($rows) > 0)
		{
			foreach ($rows as $row) 
			{
				$array[] = array(
								$row['name'],
								$row['total_quotation'],
								$row['total_po'],
								number_format($row['total_revenue_inr']),
								number_format($row['total_revenue_usd'])
								);
			}
		}		
		$tmpName='top_selling_products';
		$tmpDate =  date("YmdHis");
		$csvFileName = $tmpName."_".$tmpDate.".csv";
		$this->load->helper('csv');        
		array_to_csv($array, $csvFileName);
		return TRUE;
    }

	function get_latest_sales_orders()
	{
		$arg['limit']=5;
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
	    $result=$this->dashboard_v2_model->get_latest_sales_orders($arg);	
		$list_data['rows']=$result;
		$list_data['currency_info']=$this->Setting_model->GetDefaultCurrency();
		$html = '';
		$html = $this->load->view('admin/dashboard_v2/rander_latest_sales_orders_data',$list_data,TRUE);
		$data =array (
			"html"=>$html
			 );	
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	public function latest_sales_orders_download_csv()
    {    	
		$arg['limit']='';	    
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$rows=$this->dashboard_v2_model->get_latest_sales_orders($arg);
		$currency_info=$this->Setting_model->GetDefaultCurrency();		
		$array[] = array('');
		$array[] = array(
						'Company',
						'Po Date',
						'Assigned To',
						'Revenue ('.$currency_info['default_currency_code'].')'
						);
		
		if(count($rows) > 0)
		{
			foreach ($rows as $row) 
			{
				$array[] = array(
								($row['cust_company_name'])?$row['cust_company_name']:'N/A',
								date_db_format_to_display_format($row['po_date']),
								$row['assigned_user'],
								number_format($row['deal_value_as_per_purchase_order'])
								);
			}
		}		
		$tmpName='latest_sales_orders';
		$tmpDate =  date("YmdHis");
		$csvFileName = $tmpName."_".$tmpDate.".csv";
		$this->load->helper('csv');        
		array_to_csv($array, $csvFileName);
		return TRUE;
		
    }


	function get_leads_by_source()
	{

		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
	    $result=$this->dashboard_v2_model->get_leads_by_source($arg);	
		$html = '';
		$list_data['rows']=$result;
		$html = $this->load->view('admin/dashboard_v2/rander_leads_by_source_data',$list_data,TRUE);		
		$data =array (
			"html"=>$html
			 );			
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	function get_leads_by_source_download_csv()
	{
		$data =array();
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
	    $result=$this->dashboard_v2_model->get_leads_by_source($arg);
		
		if(count($result))
		{				
								
			$array[] = array('');
			$array[] = array(
							'Source',
							'Leads'
							);
			
			
			foreach ($result as $row) 
			{
				$array[] = array(
								($row['source_alias_name'])?$row['source_alias_name']:$row['source_name'],
								$row['lead_count'],
								);
			}				
								
			$tmpName='Leads-By-Source';
			$tmpDate =  date("YmdHis");
			$csvFileName = $tmpName."_".$tmpDate.".csv";
			$this->load->helper('csv');        
			array_to_csv($array, $csvFileName);
			return TRUE;
		}
	}

	function get_lead_lost_reasons()
	{

		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
	    $result=$this->dashboard_v2_model->get_lead_lost_reasons($arg);		
		$html = '';
		$list_data['rows']=$result;
		$html = $this->load->view('admin/dashboard_v2/rander_lead_lost_reasons_data',$list_data,TRUE);
		$data =array (
			"html"=>$html
			 );			
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function get_lead_lost_reasons_download_csv()
	{
		$data =array();
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
	    $result=$this->dashboard_v2_model->get_lead_lost_reasons($arg);
		
		if(count($result))
		{				
								
			$array[] = array('');
			$array[] = array(
							'Lead Lost Reasons',
							'Leads'
							);
			
			
			foreach ($result as $row) 
			{
				$array[] = array(
								$row['lost_reason'],
								$row['lead_lost_count'],
								);
			}				
								
			$tmpName='Lead-Lost-Reasons';
			$tmpDate =  date("YmdHis");
			$csvFileName = $tmpName."_".$tmpDate.".csv";
			$this->load->helper('csv');        
			array_to_csv($array, $csvFileName);
			return TRUE;
		}
	}

	function get_unfollowed_leads_by_users()
	{

		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
	    $result=$this->dashboard_v2_model->get_unfollowed_leads_by_user($arg);	
		$list_data['rows']=$result;
		$html = '';
		$html = $this->load->view('admin/dashboard_v2/rander_unfollowed_leads_by_users_data',$list_data,TRUE);
		$data =array (
			"html"=>$html
			 );	
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function get_unfollowed_leads_by_users_download_csv()
	{
		$data =array();
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
	    $result=$this->dashboard_v2_model->get_unfollowed_leads_by_user($arg);		
		if(count($result))
		{				
			$array[] = array('');
			$array[] = array(
							'Assigned User',
							'Leads'
							);
			foreach ($result as $row) 
			{
				$array[] = array(
								$row['assigned_user_name'],
								$row['lead_count'],
								);
			}				
								
			$tmpName='Unfollowed-Leads-By-Users';
			$tmpDate =  date("YmdHis");
			$csvFileName = $tmpName."_".$tmpDate.".csv";
			$this->load->helper('csv');        
			array_to_csv($array, $csvFileName);
			return TRUE;
		}
	}

	function get_top_performers_of_month()
	{

		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_selected_year_month']=$this->input->get('filter_selected_year_month');
	    $list['rows']=$this->dashboard_v2_model->get_top_performers_of_month($arg);	
		$list['currency_info']=$this->Setting_model->GetDefaultCurrency();
		$list['filter_selected_year_month']=$arg['filter_selected_year_month'];
		$html = '';
		$html = $this->load->view('admin/dashboard_v2/rander_top_performers_of_month_data',$list,TRUE);
		$data =array ("html"=>$html);	
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}



	function get_product_vs_leads()
	{
    	$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type'];
   		$limit=$this->input->get('limit');
   		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
   		if($limit>0)
   		{
   			$start=empty($start)?0:($start-1)*$limit;
		    $arg['limit']=$limit;
		    $arg['start']=$start;
   		}
   		else
   		{   			
		    $arg['limit']="";
		    $arg['start']="";
   		}
   		
   		// print_r($arg); die();
		$list['rows']=$this->dashboard_v2_model->get_product_vs_leads($arg);
	    $html = '';	    
	    $html = $this->load->view('admin/dashboard_v2/rander_product_vs_leads_view_ajax',$list,TRUE);
	    $data =array (
	       "html"=>$html
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit();
	}
	




	
	// AJAX PAGINATION START
	function get_business_report_weekly_ajax()
	{
	    $start = $this->input->get('page'); 
	    $this->load->library('pagination');
	    $limit=31;
	    $config = array();
	    $list=array();
	    $arg=array();
	    
	    // FILTER DATA
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_business_report_weekly_period']=$this->input->get('filter_business_report_weekly_period');
	    //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->dashboard_v2_model->get_business_report_weekly_count($arg);
	    $config['per_page'] = $limit;
	    $config['uri_segment']=3;
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
		$list['currency_list']=$this->dashboard_v2_model->GetCurrencyList();
    	$list['rows']=$this->dashboard_v2_model->get_business_report_weekly($arg);		
	    $table = '';	    
	    $table = $this->load->view('admin/dashboard_v2/rander_business_report_weekly_view_ajax',$list,TRUE);
	
		// PAGINATION COUNT INFO SHOW: START
		$tmp_start=($start+1);		
		$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
	    $page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
		// PAGINATION COUNT INFO SHOW: END
	    $data =array (
	       "table"=>$table,
	       "page"=>$page_link,
		   "page_record_count_info"=>$page_record_count_info
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	// AJAX PAGINATION END
	// AJAX PAGINATION START
	function get_business_report_monthly_ajax()
	{
	    $start = $this->input->get('page'); 
	    $this->load->library('pagination');
	    $limit=12;
	    $config = array();
	    $list=array();
	    $arg=array();
	    
	    // FILTER DATA
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_business_report_monthly_period']=$this->input->get('filter_business_report_monthly_period');
	    //$config['base_url'] =base_url('pages_ajax/show');
	    $config['base_url'] ='#';
	    $config['total_rows'] = $this->dashboard_v2_model->get_business_report_monthly_count($arg);
	    $config['per_page'] = $limit;
	    $config['uri_segment']=3;
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
		$list['currency_list']=$this->dashboard_v2_model->GetCurrencyList();
    	$list['rows']=$this->dashboard_v2_model->get_business_report_monthly($arg);		
	    $table = '';	    
	    $table = $this->load->view('admin/dashboard_v2/rander_business_report_monthly_view_ajax',$list,TRUE);
	
		// PAGINATION COUNT INFO SHOW: START
		$tmp_start=($start+1);		
		$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
	    $page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
		// PAGINATION COUNT INFO SHOW: END
	    $data =array (
	       "table"=>$table,
	       "page"=>$page_link,
		   "page_record_count_info"=>$page_record_count_info
	        );
	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}
	// AJAX PAGINATION END

	public function lead_health_chrt()
	{
		$time=strtotime(date('Y-m-d'));
		$month=date("F",$time);
		if($month<=6){
			$categories=array('JAN','FEB','MAR','APR','MAY','JUN');
		}else{
			$categories=array('JUL','AUG','SEP','OCT','NOV','DEC');
		}
        $data=$this->graph_model->graph_lead_health_anlys();
        $total=[];
        $regretted=[];
        $won=[];
        $lost=[];
        if(!empty($data)){
    		foreach ($data as $row) {
	        	$total[]=(int)$row->total;
	        	$regretted[]=(int)$row->regretted;
	        	$won[]=(int)$row->won;
	        	$lost[]=(int)$row->lost;
	        }
    	}
        $data=array(
        	array('name'=>'Total Leads','data'=> $total),
         	array('name'=> 'Regretted','data'=> $regretted), 
         	array('name'=> 'Deal Won','data'=> $won),
         	array('name'=> 'Deal Lost','data'=> $lost)
        );
        /*echo '<pre>';
        print_r($data);die;*/
        echo json_encode(['data'=>$data,'categories'=>$categories]);
	}

	public function lead_revenue_growth()
	{
		$time=strtotime(date('Y-m-d'));
		$month=date("F",$time);
		if($month<=6){
			$categories=array('JAN','FEB','MAR','APR','MAY','JUN');
		}else{
			$categories=array('JUL','AUG','SEP','OCT','NOV','DEC');
		}
        $data=$this->graph_model->lead_revenue_growth();
        $won=[];
        $revenue=[];
        $avg_order_value=[];
        if(!empty($data)){
	        foreach ($data as $row) {
	        	$won[]=(float)$row->won;
	        	$revenue[]=(float)$row->revenue;
	        	$avg_order_value[]=(float)$row->avg_order_value;
	        }
	    }
        $data=array(
         	array('name'=> 'Deal Won','data'=> $won),
         	array('name'=> 'Revenue(In Lac INR)','data'=> $revenue),
         	array('name'=> 'Avg. Order Value','data'=> $avg_order_value), 
        );
        /*echo '<pre>';
        print_r($data);die;*/
        echo json_encode(['data'=>$data,'categories'=>$categories]);
	}

	public function lead_source_analysis()
	{
		//$categories=array('INDIAMART','ALIBABA','TRADEINDIA','FACEBOOK','DIRECT','AGENCY');
		$categories=$this->graph_model->get_source_list();
        $data=$this->graph_model->lead_source_analysis();
        $won=[];
        $lost=[];
        $revenue=[];
        $in_process=[];
        if(!empty($data)){
    		foreach ($data as $row) {
	        	$won[]=(float)$row->won;
	        	$lost[]=(float)$row->lost;
	        	$in_process[]=(float)$row->in_process;
	        	$revenue[]=(float)$row->revenue;
	        }
    	}
        $data=array(
         	array('name'=> 'Deal Won','data'=> $won),
         	array('name'=> 'Deal Lost','data'=> $lost),
         	array('name'=> 'In Process','data'=> $in_process),
         	array('name'=> 'Revenue(In Lac INR)','data'=> $revenue)
        );
        /*echo '<pre>';
        print_r($data);die;*/
        echo json_encode(['data'=>$data,'categories'=>$categories]);
	}

	public function lead_to_conversion()
	{
		//$categories=array('INDIAMART','ALIBABA','TRADEINDIA','FACEBOOK','DIRECT','AGENCY');
		$categories=$this->graph_model->get_source_list();
        $data=$this->graph_model->lead_to_conversion();
        $count=[];
        if(!empty($data)){
    		foreach ($data as $row) {
	        	$count[]=(float)$row->count;
	        }
    	}
        $data=array(
         	array('name'=> 'Conversion','data'=> $count),
        );
        /*echo '<pre>';
        print_r($data);die;*/
        echo json_encode(['data'=>$data,'categories'=>$categories]);
	}

	public function lead_lost_reason(){
        $data=$this->graph_model->lead_lost_reason();
        $count=[];
		foreach ($data as $row) {
			if($row->count){
        		$count[]=array('name'=>$row->name,'y'=>(float)$row->count,'sliced'=>'true','selected'=>'true');
			}
        }
    	$data[]=array('name'=>'Percentage','colorByPoint'=>'true','data'=>$count);
        echo json_encode(['data'=>$data]);
	}

	function get_user_wise_pending_followup()
	{		
		$session_data=$this->session->userdata('admin_session_data');
   		$user_id=$session_data['user_id'];
   		$user_type=$session_data['user_type']; 
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($user_id,0);
   		array_push($tmp_u_ids, $user_id);
   		$tmp_u_ids_str=implode(",", $tmp_u_ids);
   		$arg['u_ids_str']=$tmp_u_ids_str;
   		$arg['al_stages']=$this->lead_model->get_type_wise_lead_stage('AL');
   		$arg['limit']='';
   		$list['u_ids_str']=$tmp_u_ids_str;
		$list['user_wise_pending_followup_count']=$this->dashboard_v2_model->get_user_wise_pending_followup($arg);
	    $html = $this->load->view('admin/dashboard_v2/rander_user_wise_pending_followup_view_ajax',$list,TRUE);
		//$html =$list['user_wise_pending_followup_count'];
	    $data =array (
	       "html"=>$html
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	}

	function get_base64_encode_for_lead_list_filter()
	{		
		$filter_selected_user_id=$this->input->get('filter_selected_user_id');
		$filter_by=$this->input->get('filter_by');
		$url_str=base64_encode($filter_selected_user_id.'#'.$filter_by);
	    $data =array (
	       "url_str"=>$url_str
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit;
	}



	function download_sales_pipeline_report_csv()
    {	
		
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');	
	    $result=$this->dashboard_v2_model->get_user_wise_sales_pipeline($arg);		
		$userid='';
		$unique_user='';
		$stage_list=array();
		for ($x = 0; $x <count($result); $x++) {

			// ---------- FOR STATGE LIST START ----------------------------
			if($x==0) $userid=$result[$x]['user_id']; 
			if($userid==$result[$x]['user_id']){
				$stage_list[]=[
					'id'=>$result[$x]['stage_id'],
					'name'=>$result[$x]['name']
				];
			}
			// ---------- FOR STATGE LIST END ----------------------------

			// ---------- FOR USER LIST START ----------------------------
			if($unique_user!=$result[$x]['user_id']){
				$unique_user = $result[$x]['user_id'];
				$user_list[]=[
					'id'=>$result[$x]['user_id'],
					'name'=>$result[$x]['user_name'],
					'photo'=>$result[$x]['user_image'],
					'designation'=>$result[$x]['user_designation']
				];
			} else {
				$unique_user = $result[$x]['user_id'];
			}
			// ---------- FOR USER LIST END ----------------------------

			// ---------- FOR SALES LIST START ----------------------------
				$sales_list[$result[$x]['user_id']][$result[$x]['stage_id']]=$result[$x]['total_lead'];
			// ---------- FOR SALES LIST END ----------------------------

		}

		$headtitle=array();
		$headtitle[]='USER NAME';
		$headtitle[]='USER DESIGNATION';
		foreach($stage_list AS $stage){ 
            $headtitle[]=$stage['name'];
        }


        $today_datetime=date('d-m-Y H:i:s');
        $array=array();        
        $array[] = array('Sales Pipeline Reports');						
        $array[] = array('');
		$array[] = $headtitle;

		foreach($user_list AS $user){ 
			$datalist=array();
			$datalist[]=$user['name'];
			$datalist[]=$user['designation'];
			foreach($stage_list AS $stage){
				$datalist[]=$sales_list[$user['id']][$stage['id']];
			}
			$array[] = $datalist;
		}				      

        $tmpName='Sales_Pipeline_Reports';
        $tmpDate =  $today_datetime;
        $csvFileName = $tmpName."_".$tmpDate.".csv";

        $this->load->helper('csv');
        //query_to_csv($array, TRUE, 'Attendance_list.csv');
        array_to_csv($array, $csvFileName);
        return TRUE;
    }

	

	function download_latest_sales_orders_csv()
    {	
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
	    //$result=$this->dashboard_v2_model->get_top_selling_products($arg);
		
		

		$headtitle=array();
		$headtitle[]='USER NAME';
		$headtitle[]='USER DESIGNATION';
		


        $today_datetime=date('d-m-Y H:i:s');
        $array=array();        
        $array[] = array('Latest Sales Orders');						
        $array[] = array('');
		$array[] = $headtitle;

					      

        $tmpName='Latest_Sales_Orders';
        $tmpDate =  $today_datetime;
        $csvFileName = $tmpName."_".$tmpDate.".csv";

        $this->load->helper('csv');
        //query_to_csv($array, TRUE, 'Attendance_list.csv');
        array_to_csv($array, $csvFileName);
        return TRUE;
    }

	function download_user_activity_report_v2_csv()
    {	
		$show_daterange_html_uar_v2=$this->input->get('show_daterange_html_uar_v2');
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');

		$arg['start']='';

	    $list_data=$this->dashboard_v2_model->get_user_activity_report_v2($arg);
		
		$headtitle=array(
			'USER NAME',
			'USER DESIGNATION',
			'NEW LEAD',
			'CALLS',
			'MEETINGS',
			'UPDATED',
			'QUOTED',
			'DEAL WON',
			'DEAL LOST',
			'REVENUE'
		);

        $today_datetime=date('d-m-Y H:i:s');
        $array=array();        
        $array[] = array('User Activity Reports '.$show_daterange_html_uar_v2.' ');						
        $array[] = array('');
		$array[] = $headtitle;

		foreach($list_data AS $user){
			$datalist=array(
				$user['name'],
				$user['user_designation'],
				$user['total_new_lead'],
				$user['total_call_log'],
				$user['total_meeting'],
				$user['total_updated'],
				$user['total_quoted'],
				$user['total_dealwon'],
				$user['total_lost'],
				$user['total_revenue'],
			);

			$array[] = $datalist;
		}					      

        $tmpName='User_Activity_Reports';
        $tmpDate =  $today_datetime;
        $csvFileName = $tmpName."_".$tmpDate.".csv";

        $this->load->helper('csv');
        //query_to_csv($array, TRUE, 'Attendance_list.csv');
        array_to_csv($array, $csvFileName);
        return TRUE;
    }

	function download_daily_sales_report_v2_csv()
    {	
		$show_daterange_html_dsr=$this->input->get('show_daterange_html_dsr');
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_date_range_pre_define']=$this->input->get('filter_date_range_pre_define');
		$arg['filter_date_range_user_define_from']=$this->input->get('filter_date_range_user_define_from');
		$arg['filter_date_range_user_define_to']=$this->input->get('filter_date_range_user_define_to');

	    $result=$this->dashboard_v2_model->get_daily_sales_report_v2($arg);	

		$daily_date='';
		$unique_daily_date='';
		$stage_list=array();
		for ($x = 0; $x <count($result); $x++) {

			// ---------- FOR STATGE LIST START ----------------------------
			if($x==0) $daily_date=$result[$x]['daily_date']; 
			if($daily_date==$result[$x]['daily_date']){
				$stage_list[]=[
					'id'=>$result[$x]['stage_id'],
					'name'=>$result[$x]['name']
				];
			}
			// ---------- FOR STATGE LIST END ----------------------------

			// ---------- FOR USER LIST START ----------------------------
			if($unique_daily_date!=$result[$x]['daily_date']){
				$unique_daily_date = $result[$x]['daily_date'];
				$daily_date_list[]=[
					'daily_date'=>$result[$x]['daily_date']
				];
			} else {
				$unique_daily_date = $result[$x]['daily_date'];
			}
			// ---------- FOR USER LIST END ----------------------------

			// ---------- FOR SALES LIST START ----------------------------
				$sales_list[$result[$x]['daily_date']][$result[$x]['stage_id']]=$result[$x]['total_lead'];
			// ---------- FOR SALES LIST END ----------------------------

		}
		
		$headtitle=array();
		$headtitle[]='DATE';
		foreach($stage_list AS $stage){ 
            $headtitle[]=$stage['name'];
        }

        $today_datetime=date('d-m-Y H:i:s');
        $array=array();        
        $array[] = array('Daily Sales Reports '.$show_daterange_html_dsr.' ');						
        $array[] = array('');
		$array[] = $headtitle;

		foreach($daily_date_list AS $datelist){
			$datalist=array();
			$datalist[]=date('d M Y', strtotime($datelist['daily_date']));
			foreach($stage_list AS $stage){
				$datalist[]=$sales_list[$datelist['daily_date']][$stage['id']];
			}
			$array[] = $datalist;
		}					      

        $tmpName='Daily_Sales_Reports';
        $tmpDate =  $today_datetime;
        $csvFileName = $tmpName."_".$tmpDate.".csv";

        $this->load->helper('csv');
        //query_to_csv($array, TRUE, 'Attendance_list.csv');
        array_to_csv($array, $csvFileName);
        return TRUE;
    }

	function rander_detail_report_view_popup_ajax()
    { 
		$list_data=array();
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_report']=$this->input->get('report');
		$filter1=$this->input->get('filter1');			
		if($arg['filter_report']=='user_wise_sales_pipeline_report'){
			$arg['filter1']=$this->dashboard_v2_model->get_stage_id_from_name($filter1);
		}
		else{
			$arg['filter1']=$filter1;
		}			
		$arg['filter2']=$this->input->get('filter2');
		
		$arg['filter_date_range_pre_define']=($this->input->get('filter_date_range_pre_define'))?$this->input->get('filter_date_range_pre_define'):'';
		$arg['filter_date_range_user_define_from']=($this->input->get('filter_date_range_user_define_from'))?$this->input->get('filter_date_range_user_define_from'):'';
		$arg['filter_date_range_user_define_to']=($this->input->get('filter_date_range_user_define_to'))?$this->input->get('filter_date_range_user_define_to'):'';
	    // $list_data['rows']=$this->dashboard_v2_model->get_daily_sales_report_detail($arg);
		// $list_data['currency_info']=$this->Setting_model->GetDefaultCurrency();	
		$list_data['filter_selected_user_id']=$arg['filter_selected_user_id'];
		$list_data['report']=$arg['filter_report'];
		$list_data['filter1']=$arg['filter1'];
		$list_data['filter2']=$arg['filter2'];
		$list_data['filter_date_range_pre_define']=$arg['filter_date_range_pre_define'];
		$list_data['filter_date_range_user_define_from']=$arg['filter_date_range_user_define_from'];
		$list_data['filter_date_range_user_define_to']=$arg['filter_date_range_user_define_to'];


		$html = '';
		$html = $this->load->view('admin/dashboard_v2/detail_report_view_popup_ajax',$list_data,TRUE);
		$data =array (
			"html"=>$html
			 );	

	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
		exit();
    }

	function rander_detail_report_list_view_popup_ajax()
    { 
		$start = $this->input->get('page');
		$this->load->library('pagination');
	    $limit=50;
	    $config = array();
		$list_data=array();
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_report']=$this->input->get('report');
		$filter1=$this->input->get('filter1');	
		$arg['filter1']=$filter1;
		// if($arg['filter_report']=='user_wise_sales_pipeline_report'){
		// 	$arg['filter1']=$this->dashboard_v2_model->get_stage_id_from_name($filter1);
		// }
		// else{
		// 	$arg['filter1']=$filter1;
		// }			
		$arg['filter2']=$this->input->get('filter2');
		
		$arg['filter_date_range_pre_define']=($this->input->get('filter_date_range_pre_define'))?$this->input->get('filter_date_range_pre_define'):'';
		$arg['filter_date_range_user_define_from']=($this->input->get('filter_date_range_user_define_from'))?$this->input->get('filter_date_range_user_define_from'):'';
		$arg['filter_date_range_user_define_to']=($this->input->get('filter_date_range_user_define_to'))?$this->input->get('filter_date_range_user_define_to'):'';
	    $arg['limit']='';
	    $arg['start']='';
		$config['base_url'] ='JavaScript:void(0)';
	    $config['total_rows'] = count($this->dashboard_v2_model->get_daily_sales_report_detail($arg));		
		// $config['total_rows'] = 3;
	    $config['per_page'] = $limit;
	    $config['uri_segment']=4;
	    $config['num_links'] = 1;
	    $config['use_page_numbers'] = TRUE;
	    $config['attributes'] = array('class' => 'dr_pagination_class');	    
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
		$list_data['filter1']=$filter1;
		$list_data['filter_report']=$arg['filter_report'];
		$list_data['rows']=$this->dashboard_v2_model->get_daily_sales_report_detail($arg);
		$list_data['currency_info']=$this->Setting_model->GetDefaultCurrency();
		$html = '';
		$html = $this->load->view('admin/dashboard_v2/detail_report_list_view_popup_ajax',$list_data,TRUE);

		// PAGINATION COUNT INFO SHOW: START
		$tmp_start=($start+1);		
		$tmp_limit=($limit<($config['total_rows']-$start))?($start+$limit):$config['total_rows'];
	    $page_record_count_info="Showing ".$tmp_start." to ".$tmp_limit." of ".$config['total_rows']." entries";
		// PAGINATION COUNT INFO SHOW: END
		
		$data =array (
			"html"=>$html,
			"page"=>$page_link,
		    "page_record_count_info"=>$page_record_count_info
			 );	

	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
		exit();
    }

	public function download_csv()
    {
    	$list_data=array();
		$arg['limit']='';
	    $arg['start']='';
		$arg['filter_selected_user_id']=$this->input->get('filter_selected_user_id');
		$arg['filter_report']=$this->input->get('report');
		$arg['filter1']=$this->input->get('filter1');				
		$arg['filter2']=$this->input->get('filter2');		
		$arg['filter_date_range_pre_define']=($this->input->get('filter_date_range_pre_define'))?$this->input->get('filter_date_range_pre_define'):'';
		$arg['filter_date_range_user_define_from']=($this->input->get('filter_date_range_user_define_from'))?$this->input->get('filter_date_range_user_define_from'):'';
		$arg['filter_date_range_user_define_to']=($this->input->get('filter_date_range_user_define_to'))?$this->input->get('filter_date_range_user_define_to'):'';
	    $rows_tmp=$this->dashboard_v2_model->get_daily_sales_report_detail($arg);
		if($arg['filter1']=='calls')
		{
			
			$ids_arr=array();
			$ids_str='';
			$rows=array();
			if(count($rows_tmp)){
				foreach($rows_tmp AS $row)
				{
					array_push($ids_arr,$row->id);
				}
				$ids_str=implode(",",$ids_arr);
				$arg2['ids']=$ids_str;
				$rows=$this->dashboard_v2_model->get_call_history_report_detail_csv_list($arg2);
			}			
			
			$array[] = array('');
			$array[] = array(
							'Is Paying Customer?',
							'Name',
							'Contact Number',
							'Call Type',
							'Call Start',
							'Call End',
							'Total Call Time (H:m:s)',
							'Assigned User',
							'Comment'
							);
			
			if(count($rows) > 0)
			{
				foreach ($rows as $row) 
				{
					
					$is_paying_customer=($row->is_paying_customer=='Y')?'Yes':'No';
					$name=($row->name)?$row->name:'-';
					$number=($row->number)?$row->number:'-';
					$bound_type=($row->bound_type)?ucwords($row->bound_type):'-';
					$call_start=($row->call_start)?datetime_db_format_to_display_format($row->call_start):'-';
					$call_end=($row->call_end)?datetime_db_format_to_display_format($row->call_end):'-';
					if($row->call_start!='' && $row->call_end!=''){
					$date_a = new DateTime($row->call_start);
					$date_b = new DateTime($row->call_end);
					$interval = date_diff($date_a,$date_b);
					$total_call_time=$interval->format('%h:%i:%s');
					}
					else{
					$total_call_time='-';
					}  					
					$assigned_user_name=($row->assigned_user_name)?$row->assigned_user_name:'-';
					$status_wise_msg=($row->status_wise_msg)?$row->status_wise_msg:'-';
					
					$array[] = array(
									$is_paying_customer,
									$name,
									$number,
									$bound_type,
									$call_start,
									$call_end,
									$total_call_time,
									$assigned_user_name,	
									$status_wise_msg
									);
				}
			}

			$tmpName='call_log_details_list';
			$tmpDate =  date("YmdHis");
			$csvFileName = $tmpName."_".$tmpDate.".csv";
			$this->load->helper('csv');
			array_to_csv($array, $csvFileName);
			return TRUE;
		}
		else
		{
			$lead_ids_arr=array();
			$lead_ids_str='';
			if(count($rows_tmp)){
				foreach($rows_tmp AS $row)
				{
					array_push($lead_ids_arr,$row['lead_id']);
				}
				$lead_ids_str=implode(",",$lead_ids_arr);
			}		
			$rows=array();
			$arg2['lead_ids']=$lead_ids_str;
			if($lead_ids_str){
				$rows=$this->dashboard_v2_model->get_csv_list($arg2);
				$priority_wise_stage=$this->lead_model->priority_wise_stage();
			}
			$array[] = array('');
			$array[] = array(
							'Lead ID',
							'Date',
							'Lead Title',
							'Requirement',
							'Assigned to',
							'User type',
							'User branch',
							'Country',
							'State',
							'City',
							'Last Updated',
							'Next Follow-up Date',
							'Stage',
							'Lead regret/Lost reason',
							'Status',
							'Source',
							'Company',
							'Contact person',
							'Email',
							'Mobile',
							'Closer date',
							'Currency',
							'Deal Value',
							'Repeat Client(Yes/No)'
							);
			
			if(count($rows) > 0)
			{
				foreach ($rows as $row) 
				{
					$enquiry_date = date_db_format_to_display_format($row->enquiry_date);
					$modify_date=date_db_format_to_display_format($row->modify_date);
					if($row->current_stage_id=='7' || $row->current_stage_id=='6' || $row->current_stage_id=='3' || $row->current_stage_id=='5' || $row->current_stage_id=='4')
					{
						$followup_date='--';
					}
					else
					{
						if($row->followup_date!='0000-00-00')
						{
							$followup_date= date_db_format_to_display_format($row->followup_date);
						}
						else
						{
						$followup_date='--';
						}
					}
					$proporal_count=($row->proposal>0)?$row->proposal:'N/A';				
					$last_deal_value_currency=($row->deal_value_as_per_purchase_order)?$row->deal_value_as_per_purchase_order_currency:(($row->last_deal_value)?$row->last_deal_value_currency:'');
					$last_deal_value=($row->deal_value_as_per_purchase_order)?$row->deal_value_as_per_purchase_order:(($row->last_deal_value)?$row->last_deal_value:'');
					
					if($row->assigned_user_branch_name!='' || $row->assigned_user_branch_name_cs!=''){
						$assigned_user_branch_name_tmp=($row->assigned_user_branch_name)?$row->assigned_user_branch_name:$row->assigned_user_branch_name_cs;
					}
					else{
						$assigned_user_branch_name_tmp='N/A';
					}
					
					$order_arr=explode(',',$row->orders);								
					$order_count=count(array_keys($order_arr, "4"));
					if($order_count>1){
						$repeat_client='Yes'; 
					}
					else{
						$repeat_client='No';
					}

					$deal_value='';
					$deal_value_currency_code='';
					if($row->quotation_matured_deal_value_as_per_purchase_order){
						$deal_value=round($row->quotation_matured_deal_value_as_per_purchase_order);
						$deal_value_currency_code=$row->quotation_matured_currency_code;
					}
					else{ 
						if($row->quotation_sent_deal_value){
							$deal_value=round($row->quotation_sent_deal_value);
							$deal_value_currency_code=$row->quotation_sent_currency_code;
						}
						else{
							$deal_value=($row->deal_value)?round($row->deal_value):'';
							$deal_value_currency_code=$row->deal_value_currency_code;
						}
					} 

					// ----------------------------
					// priority wise stage				
					if($row->current_stage_id!='1')
					{
						$stage_log_arr=array_unique(explode(',', $row->stage_logs));	
						$curr_stage_id=$row->current_stage_id; 
						$stage_count=0;
						if(count($priority_wise_stage['active_lead_stages_y']))
						{
							foreach($priority_wise_stage['active_lead_stages_y'] AS $stage)
							{
								if(in_array($stage['id'], $stage_log_arr)){
										$stage_count++;
								}
							}
						}
						$active_stage_text='';
						$k=1;		            	
						if(count($priority_wise_stage['active_lead_stages_y']))
						{
							foreach($priority_wise_stage['active_lead_stages_y'] AS $stage)
							{
								if(in_array($stage['id'], $stage_log_arr)){
									if($stage_count==$k)
									{		           
										if(($curr_stage_id=='3') || ($curr_stage_id=='5')){										
										}  
										else{										
											$active_stage_text=$stage['name'];
										}
									}
									else{
										$active_stage_text=$stage['name'];
									}
									$k++;							
								}
							}
						}
						if(count($priority_wise_stage['active_lead_stages_n']))
						{
							foreach($priority_wise_stage['active_lead_stages_n'] AS $stage)
							{
								if($curr_stage_id==$stage['id']){
									$active_stage_text=$stage['name'];
								}
								else{
								}							
							}
						}					
					}
					else{
						$active_stage_text='PENDING';
					}
					// priority wise stage
					// ----------------------------
					
					$array[] = array(
										$row->id,
										$enquiry_date,
										$row->title,
										$row->buying_requirement,
										$row->assigned_user_name,
										($row->assigned_user_employee_type)?$row->assigned_user_employee_type:'N/A',
										$assigned_user_branch_name_tmp,
										$row->cust_country_name,
										$row->cust_state_name,
										$row->cust_city_name,	
										$modify_date,
										$followup_date,                                
										$active_stage_text,
										$row->current_stage_wise_msg,
										$row->current_status,
										$row->source_name,
										$row->cus_company_name,
										$row->cus_contact_person, 
										($row->cus_email)?$row->cus_email:'N/A',
										($row->cus_mobile)?$row->cus_mobile:'N/A',
										($row->closer_date)?date_db_format_to_display_format($row->closer_date):'N/A',								
										($deal_value_currency_code)?$deal_value_currency_code:'N/A',
										($deal_value)?$deal_value:'N/A',
										$repeat_client
									);
				}
			}		
			$tmpName='lead_list';
			$tmpDate =  date("YmdHis");
			$csvFileName = $tmpName."_".$tmpDate.".csv";
			$this->load->helper('csv');        
			array_to_csv($array, $csvFileName);
			return TRUE;
		}
		
    }

	function get_users_by_managerId()
	{		
		$selected_mid=$this->input->get('selected_mid');
		$tmp_u_ids=array();		
		$tmp_u_ids=$this->user_model->get_self_and_under_employee_ids($selected_mid,0);
		array_push($tmp_u_ids, $selected_mid);
   		$tmp_u_ids_str=implode(",", $tmp_u_ids);
		$users=$this->user_model->GetUserListByIds($tmp_u_ids_str);
		$users_str='';
		if(count($users)){
			foreach($users AS $user){
				$users_str .=$user['id'].'~'.$user['name'].',';
			}
			$users_str=rtrim($users_str,',');
		}
	    $data =array (
	       "users"=>$users_str
	        );	   
	    $this->output->set_content_type('application/json');
	    echo json_encode($data);
	    exit;
	}


}