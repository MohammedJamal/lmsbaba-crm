<?php ini_set('max_execution_time', 0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Database_update extends CI_Controller {
	function __construct()
	{
		parent :: __construct();
		is_adminportal_logged_in();
		init_adminportal_element(); 
		$this->load->model(array("Client_model"));
	}

	public function index()
	{	
		$data = array(); 
		$data['get_all_client']=$this->Client_model->get_client_all();		
		$this->load->view('adminmaster/database_update_view',$data);
	}
	
	public function custom()
	{	
		$data = array(); 
		$data['get_all_client']=$this->Client_model->get_all();		
		$this->load->view('adminmaster/database_custom_update_view',$data);
	}

	public function migrate_permission()
	{	
		$data = array(); 
		$data['get_all_client']=$this->Client_model->get_all();		
		$this->load->view('adminmaster/database_migrate_permission_view',$data);
	}


	public function update_db_ajax()
	{
		$client_id_arr=$this->input->post('client_id');
		$query_script=$this->input->post('query_script');
		
		if(count($client_id_arr))
		{
			$this->Client_model->truncate_query_update_log();			
			foreach($client_id_arr AS $cid)
			{				
				$client_db_info=$this->Client_model->get_details($cid);
				$db_return=$this->Client_model->update_client_db($client_db_info,$query_script);				
				$u_data=array(
						'client_id'=>$cid,
						'db_name'=>$client_db_info->db_name,
						'msg'=>$db_return,
						'update_query'=>$query_script,
						'datetime'=>date("Y-m-d H:i:s")
						);
				$this->Client_model->add_query_update_log($u_data);				
			}
			$return['status']='success';
			$msg="Successfully updated all db...";			
		}
		else
		{
			$return['status']='success';
			$msg="No Client found!";
		}		
		$return['msg']=$msg;		
		echo json_encode($return);
		exit();				
	}
	
	public function update_custom_db_ajax()
	{
		$client_id_arr=$this->input->post('client_id');
		$query_script=$this->input->post('query_script');
		
		if(count($client_id_arr))
		{
			$this->Client_model->truncate_query_update_log();			
			foreach($client_id_arr AS $cid)
			{
				$client_db_info=$this->Client_model->get_details($cid);	
							
				$db_return=$this->Client_model->update_custom_client_db($client_db_info);
				//echo $db_return; die();
				$u_data=array(
						'client_id'=>$cid,
						'db_name'=>$client_db_info->db_name,
						'msg'=>$db_return,
						'update_query'=>$query_script,
						'datetime'=>date("Y-m-d H:i:s")
						);
				$this->Client_model->add_query_update_log($u_data);
			}
			$return['status']='success';
			$msg="Successfully updated all db...";
		}
		else
		{
			$return['status']='success';
			$msg="No Client found!";
		}
		
		
		$return['msg']=$msg;
		echo json_encode($return);
		exit();				
	}

	public function migrate_permission_ajax()
	{
		$client_id_arr=$this->input->post('client_id');		
		$html='';
		if(count($client_id_arr))
		{
			$this->Client_model->truncate_query_update_log();	
			// $html.='<table border="1">';		
			// $html.='<tr>';
			// $html.='<th>LMS ID</th>';
			// $html.='<th>Is Service Tagged?</th>';
			// $html.='<th>Status</th>';
			// $html.='</tr>';
			foreach($client_id_arr AS $cid)
			{
				
				$client_db_info=$this->Client_model->get_details($cid);	
				$html.='<table border="1">';
				$html.='<tr>';
				$html.='<th colspan="4"><h1>'.$client_db_info->name.'( LMS ID:-'.$cid.')</h1></th>';
				$html.='</tr>';	


				$html.='<tr>';
				$html.='<th>user</th>';
				$html.='<th>Other Permission</th>';
				$html.='<th>menu Permission</th>';
				$html.='<th>sub menu Permission</th>';
				$html.='</tr>';	
				$return_array=$this->Client_model->migrate_permission($client_db_info);
				// if($return_array>0)
				// {
				// 	$is_tagged='Y';
				// }
				// else
				// {
				// 	$is_tagged='N';
				// }
				// $is_active=($client_db_info->is_active=='Y')?'Active':'In-Active';
				// $html.='<tr>';
				// $html.='<td>LMS ID:-'.$cid.'</td>';
				// $html.='<td>'.$is_tagged.'</td>';
				// $html.='<td>'.$is_active.'</td>';
				// $html.='</tr>';	

				foreach($return_array AS $row)				
				{
					if($row['user_id']!='1')
					{
						$attribute_name=($row['attribute_name'])?$row['attribute_name']:'N/A';
						$menu_name=($row['menu_name'])?$row['menu_name']:'N/A';
						$sub_menu_str=($row['sub_menu_str'])?$row['sub_menu_str']:'N/A';
						$html.='<tr>';
						$html.='<td>'.$row['user_name'].'</td>';
						$html.='<td>'.$attribute_name.'('.$row['reserved_keyword'].')</td>';
						$html.='<td>'.$menu_name.'('.$row['menu_id'].')</td>';	
						$html.='<td>'.$sub_menu_str.'</td>';	
						$html.='</tr>';

						// Manage Vendors
						if($row['menu_id']=='4')
						{
							// manage_vendors_menu
							$query_script="INSERT INTO tbl_user_wise_permission (user_id,reserved_keyword) VALUES ('".$row['user_id']."','manage_vendors_menu')";
							$this->Client_model->update_client_db($client_db_info,$query_script);							
						}

						// Companies
						if($row['menu_id']=='5')
						{
							// manage_companies_menu
							$query_script="INSERT INTO tbl_user_wise_permission (user_id,reserved_keyword) VALUES ('".$row['user_id']."','manage_companies_menu')";
							$this->Client_model->update_client_db($client_db_info,$query_script);
						}

						// =========================================================================================================
						// leads
						if($row['menu_id']=='6')
						{
							// lead_menu
							$query_script="INSERT INTO tbl_user_wise_permission (user_id,reserved_keyword) VALUES ('".$row['user_id']."','lead_menu')";
							$this->Client_model->update_client_db($client_db_info,$query_script);

							// bulk_lead_upload_non_menu (no)
							// assignee_change_non_menu (yes)
							$query_script="INSERT INTO tbl_user_wise_permission (user_id,reserved_keyword) VALUES ('".$row['user_id']."','assignee_change_non_menu')";
							$this->Client_model->update_client_db($client_db_info,$query_script);
							// lead_source_change_non_menu (no)
						}
						if($row['reserved_keyword']=='online_quotation_builder')
						{
							// send_quotation_non_menu
							$query_script="INSERT INTO tbl_user_wise_permission (user_id,reserved_keyword) VALUES ('".$row['user_id']."','send_quotation_non_menu')";
							$this->Client_model->update_client_db($client_db_info,$query_script);
						}

						if($row['reserved_keyword']=='bulk_assignee_change')
						{
							// bulk_assignee_change_non_menu
							$query_script="INSERT INTO tbl_user_wise_permission (user_id,reserved_keyword) VALUES ('".$row['user_id']."','bulk_assignee_change_non_menu')";
							$this->Client_model->update_client_db($client_db_info,$query_script);
						}

						if($row['reserved_keyword']=='bulk_quotation_send')
						{
							// bulk_quotation_send_non_menu
							$query_script="INSERT INTO tbl_user_wise_permission (user_id,reserved_keyword) VALUES ('".$row['user_id']."','bulk_quotation_send_non_menu')";
							$this->Client_model->update_client_db($client_db_info,$query_script);
						}

						if($row['reserved_keyword']=='lead_download_report')
						{
							// lead_download_report_non_menu
							$query_script="INSERT INTO tbl_user_wise_permission (user_id,reserved_keyword) VALUES ('".$row['user_id']."','lead_download_report_non_menu')";
							$this->Client_model->update_client_db($client_db_info,$query_script);
						}
						// leads
						// =========================================================================================================


						// Products
						if($row['menu_id']=='7')
						{
							// manage_products_menu
							$query_script="INSERT INTO tbl_user_wise_permission (user_id,reserved_keyword) VALUES ('".$row['user_id']."','manage_products_menu')";
							$this->Client_model->update_client_db($client_db_info,$query_script);
							// add_product_menu
							$query_script="INSERT INTO tbl_user_wise_permission (user_id,reserved_keyword) VALUES ('".$row['user_id']."','add_product_menu')";
							$this->Client_model->update_client_db($client_db_info,$query_script);
							// edit_product_non_menu
							$query_script="INSERT INTO tbl_user_wise_permission (user_id,reserved_keyword) VALUES ('".$row['user_id']."','edit_product_non_menu')";
							$this->Client_model->update_client_db($client_db_info,$query_script);
						}

						// Manage Renewal/AMC
						if($row['menu_id']=='9')
						{
							// servicing_menu
							$query_script="INSERT INTO tbl_user_wise_permission (user_id,reserved_keyword) VALUES ('".$row['user_id']."','servicing_menu')";
							$this->Client_model->update_client_db($client_db_info,$query_script);							
						}
						
						// Manage Purchase Order
						if($row['menu_id']=='10')
						{
							// manage_purchase_orders_menu
							$query_script="INSERT INTO tbl_user_wise_permission (user_id,reserved_keyword) VALUES ('".$row['user_id']."','manage_purchase_orders_menu')";
							$this->Client_model->update_client_db($client_db_info,$query_script);
							// po_register_menu
							$query_script="INSERT INTO tbl_user_wise_permission (user_id,reserved_keyword) VALUES ('".$row['user_id']."','po_register_menu')";
							$this->Client_model->update_client_db($client_db_info,$query_script);
							// manage_proforma_invoice_menu
							$query_script="INSERT INTO tbl_user_wise_permission (user_id,reserved_keyword) VALUES ('".$row['user_id']."','manage_proforma_invoice_menu')";
							$this->Client_model->update_client_db($client_db_info,$query_script);
							// invoice_management_menu
							$query_script="INSERT INTO tbl_user_wise_permission (user_id,reserved_keyword) VALUES ('".$row['user_id']."','invoice_management_menu')";
							$this->Client_model->update_client_db($client_db_info,$query_script);
							// payment_followup_menu
							$query_script="INSERT INTO tbl_user_wise_permission (user_id,reserved_keyword) VALUES ('".$row['user_id']."','payment_followup_menu')";
							$this->Client_model->update_client_db($client_db_info,$query_script);
						}
					}			
				}
				$html.='</table">';
				/*				
				$db_return=$this->Client_model->update_client_db($client_db_info,$query_script);				
				$u_data=array(
						'client_id'=>$cid,
						'db_name'=>$client_db_info->db_name,
						'msg'=>$db_return,
						'update_query'=>$query_script,
						'datetime'=>date("Y-m-d H:i:s")
						);
				$this->Client_model->add_query_update_log($u_data);
				*/
			}
			$return['status']='success';
			$msg="Successfully updated all db...";
			// $html.='</table">';
		}
		else
		{
			$return['status']='success';
			$msg="No Client found!";
		}
		
		
		$return['msg']=$msg;
		$return['html']=$html;
		echo json_encode($return);
		exit();				
	}
}