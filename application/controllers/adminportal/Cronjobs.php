<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronjobs extends CI_Controller {
	function __construct(){
		parent :: __construct();
		$this->load->model(array("Adminportal_model","User_model","Client_model"));
	}

	public function get_and_set_inactive_client_call()
	{
		exit(0);
	}

	public function get_and_complete_inactive_client_call()
	{
		exit(0);
	}

	public function get_and_set_client_activity_status_BACKUP_30_11_2022()
	{
		// $log  = date("F j, Y, h:i:s A")." --- START - get_and_set_client_activity_status CRONJOB FILE".PHP_EOL;
		// file_put_contents('./adminportallog_'.date("d-m-Y").'.log', $log, FILE_APPEND);
		echo"<pre>";
		$today=date("Y-m-d H:i:s");
		$client_list=$this->Client_model->get_only_all_client_list();
		if(count($client_list) && is_array($client_list)){
			foreach($client_list AS $client){
				
				print_r($client);
				$client_id=$client['client_id'];
				$activity_status_type_id='';
				$login_status='';
				$next_followup_date='';
				$service_call_type_id=4;

				if($client['is_active']=='N') {
					$activity_status_type_id=7;
					$next_followup_date='';
				} else {

					if(trim($client['not_logged_day'])!='' && $client['not_logged_day']<=7){
						$login_status='Y';
						$activity_status_type_id=5;
						$service_call_type_id=4;

					} else {
						$login_status='N';
						$activity_status_type_id=6;
						$service_call_type_id=5;
					}
					
					if($client['service_end_day']>0 && $client['service_end_day']<=60){
					
						$service_call_type_id=6;
						if($login_status=='Y') $activity_status_type_id=1;
						elseif($login_status=='N') $activity_status_type_id=2;
						
					} elseif($client['service_end_day']<=0){
					
						$service_call_type_id=6;
						if($login_status=='Y') $activity_status_type_id=3;
						elseif($login_status=='N') $activity_status_type_id=4;
						
					}
				}
				
				echo $activity_status_type_id;
				echo'<br>';
				
				// START CLIENT UPDATE -----------------------------------------
				$client_update_data=array();
				$client_update_data['activity_status_type_id']=$activity_status_type_id;



				if($client['is_active']!='N') {

					$subsql=" AND service_call_type_id IN(4,5,6) AND client_id='".$client_id."' ";
					$call_list=$this->Adminportal_model->get_all_active_service_call_list($subsql);
					//$followup_date=date('Y-m-d H:i:s',strtotime("+15 day", strtotime($today)));
					$followup_date=date('Y-m-d H:i:s');
					if(count($call_list) && is_array($call_list)){
						foreach($call_list AS $calls){

							// $service_callid=$calls['id'];
							// $update_data=array(
							// 	'scheduled_call_datetime'=>$followup_date
							// );
							// $this->Adminportal_model->service_call_update($update_data,$service_callid);

							///$client_update_data['next_followup_date']=$calls['scheduled_call_datetime'];

						}
					} else {

						$service_call_type_name="";
						if($service_call_type_id==4) $service_call_type_name="Service ";
						elseif($service_call_type_id==5) $service_call_type_name="Inactive Client ";
						elseif($service_call_type_id==6) $service_call_type_name="Renewal ";

						$activity_text=$service_call_type_name." call auto created from Cronjob";
						$insert_data=array(
							'client_id'=>$client_id,
							'service_call_type_id'=>$service_call_type_id,
							'scheduled_call_datetime'=>$followup_date,
							'comment'=>$activity_text,
							'created_at'=>date('Y-m-d H:i:s')
						);
						$this->Adminportal_model->insert_call_update($insert_data);
						$client_update_data['next_followup_date']=$followup_date;

					}

					
				}




				$this->Client_model->update($client_update_data,$client_id);
				// END CLIENT UPDATE -----------------------------------------

			}
		}
		echo"</pre>";

		// $log  = date("F j, Y, h:i:s A")." --- END - get_and_set_client_activity_status CRONJOB FILE".PHP_EOL;
		// file_put_contents('./adminportallog_'.date("d-m-Y").'.log', $log, FILE_APPEND);

		exit(0);
	}

	public function get_and_set_client_activity_status()
	{
		// $log  = date("F j, Y, h:i:s A")." --- START - get_and_set_client_activity_status CRONJOB FILE".PHP_EOL;
		// file_put_contents('./adminportallog_'.date("d-m-Y").'.log', $log, FILE_APPEND);
		echo"<pre>";
		$today=date("Y-m-d H:i:s");
		$client_list=$this->Client_model->get_only_all_client_list();
		if(count($client_list) && is_array($client_list)){
			foreach($client_list AS $client){
				
				print_r($client);
				$client_id=$client['client_id'];
				$activity_status_type_id='';
				$login_status='';
				$next_followup_date='';
				$service_call_type_id=4;
				$activity_type='';

				if($client['is_active']=='N') {
					$activity_status_type_id=7;
					$next_followup_date='';
				} else {

					if(trim($client['not_logged_day'])!='' && $client['not_logged_day']<=7){
						$login_status='Y';
						$activity_status_type_id=5;
						$activity_type='A';
						$service_call_type_id=4;

					} else {
						$login_status='N';
						$activity_status_type_id=6;
						$activity_type='I';
						$service_call_type_id=5;
					}
					
					if($client['service_end_day']>0 && $client['service_end_day']<=60){
					
						$service_call_type_id=6;
						if($login_status=='Y') {
							$activity_status_type_id=1;
							$activity_type='A';
						}
						elseif($login_status=='N') {
							$activity_status_type_id=2;
							$activity_type='I';
						}
						
					} elseif($client['service_end_day']<=0){
					
						$service_call_type_id=6;
						if($login_status=='Y') {
							$activity_status_type_id=3;
							$activity_type='A';
						}
						elseif($login_status=='N') {
							$activity_status_type_id=4;
							$activity_type='I';
						}
						
					}
				}
				
				echo $activity_status_type_id;
				echo'<br>';
				
				// START CLIENT UPDATE -----------------------------------------
				$client_update_data=array();
				$client_update_data['activity_status_type_id']=$activity_status_type_id;



				if($client['is_active']!='N') {

					$subsql=" AND service_call_type_id IN(4,5,6) AND client_id='".$client_id."' ";
					$call_list=$this->Adminportal_model->get_all_active_service_call_list($subsql);
					//$followup_date=date('Y-m-d H:i:s',strtotime("+15 day", strtotime($today)));
					$followup_date=date('Y-m-d H:i:s');
					if(count($call_list) && is_array($call_list)){
						foreach($call_list AS $calls){

							// $service_callid=$calls['id'];
							// $update_data=array(
							// 	'scheduled_call_datetime'=>$followup_date
							// );
							// $this->Adminportal_model->service_call_update($update_data,$service_callid);

							///$client_update_data['next_followup_date']=$calls['scheduled_call_datetime'];

						}
					} else {

						$service_call_type_name="";
						if($service_call_type_id==4) $service_call_type_name="Service ";
						elseif($service_call_type_id==5) $service_call_type_name="Inactive Client ";
						elseif($service_call_type_id==6) $service_call_type_name="Renewal ";

						$activity_text=$service_call_type_name." call auto created from Cronjob";
						$insert_data=array(
							'client_id'=>$client_id,
							'service_call_type_id'=>$service_call_type_id,
							'scheduled_call_datetime'=>$followup_date,
							'comment'=>$activity_text,
							'created_at'=>date('Y-m-d H:i:s')
						);
						$this->Adminportal_model->insert_call_update($insert_data);
						$client_update_data['next_followup_date']=$followup_date;

					}

					$lastlog_condition=" client_id='".$client_id."' ";
					$lastlog_dtls=$this->Adminportal_model->get_client_wise_last_active_inactive_log_row($lastlog_condition);
					
					if($activity_type=='A' && ($lastlog_dtls->activity_type=='' || $lastlog_dtls->activity_type=='I')){

						$insert_data=array(
							'client_id'=>$client_id,
							'activity_type'=>$activity_type,
							'activity_date'=>$client['last_login'],
							'created_at'=>date('Y-m-d H:i:s')
						);
						$this->Adminportal_model->insert_client_active_inactive_log($insert_data);

					} elseif($activity_type=='I' && ($lastlog_dtls->activity_type=='' || $lastlog_dtls->activity_type=='A')) {
						
						$insert_data=array(
							'client_id'=>$client_id,
							'activity_type'=>$activity_type,
							'activity_date'=>$client['last_login'],
							'created_at'=>date('Y-m-d H:i:s')
						);
						$this->Adminportal_model->insert_client_active_inactive_log($insert_data);
					} elseif($activity_type=='A' && $lastlog_dtls->activity_type=='A'){
						
						$last_login=date('Y-m-d',strtotime($client['last_login']));
						$activity_date=date('Y-m-d',strtotime($lastlog_dtls->activity_date));
						if($last_login!=$activity_date){
							$insert_data=array(
								'client_id'=>$client_id,
								'activity_type'=>$activity_type,
								'activity_date'=>$client['last_login'],
								'created_at'=>date('Y-m-d H:i:s')
							);
							$this->Adminportal_model->insert_client_active_inactive_log($insert_data);
						}

					}
				}




				$this->Client_model->update($client_update_data,$client_id);
				// END CLIENT UPDATE -----------------------------------------

			}
		}
		echo"</pre>";

		// $log  = date("F j, Y, h:i:s A")." --- END - get_and_set_client_activity_status CRONJOB FILE".PHP_EOL;
		// file_put_contents('./adminportallog_'.date("d-m-Y").'.log', $log, FILE_APPEND);

		exit(0);
	}


	public function create_manual_service_call_list()
	{
		echo"<pre>";
		$today=date("Y-m-d H:i:s");
		$service_list=$this->Adminportal_model->create_manual_service_call_list();
		if(count($service_list) && is_array($service_list)){
			echo count($service_list);
			foreach($service_list AS $service){
				
				$service_id = $service['service_order_detail_id'];
				print_r($service);


				// $update_data=array(
				// 	'service_call_type_id'=>1,
				// 	'next_followup_date'=>$service['start_date']
				// );
				// $this->Adminportal_model->service_update($update_data,$service_id);



			}
		}
		echo"</pre>";

		

		exit(0);
	}

	
}
