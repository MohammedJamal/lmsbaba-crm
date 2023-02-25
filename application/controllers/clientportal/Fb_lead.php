<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Fb_lead extends CI_Controller {	
	private $api_access_token = '';
	function __construct()
	{
		parent :: __construct();
		// is_admin_logged_in();
		init_admin_element(); 
		$this->load->model(array("Client_model","lead_model","customer_model","user_model","source_model","email_model","countries_model","states_model","cities_model","opportunity_model","Product_model","Opportunity_product_model","menu_model","history_model","vendor_model","Opportunity_model","Source_model","quotation_model","pre_define_comment_model","setting_model","Email_forwarding_setting_model","Setting_model","renewal_model","order_model","Sms_forwarding_setting_model","Whatsapp_forwarding_setting_model","meeting_model","App_model"));
		
	}
	 
	public function index()
	{

		$data=array();
		$this->load->view('admin/fb_lead/index',$data);
	}  

	public function webhooks()
	{
		// $data = json_decode(file_get_contents('php://input'), true);
		// $post=json_encode($data);		
		// $post_data=array('msg'=>$post);		
		// $this->Client_model->create_cronjobs_log($post_data);
		$challenge = $_REQUEST['hub_challenge'];
		$verify_token = $_REQUEST['hub_verify_token'];
		$str='';
		foreach($_REQUEST AS $val){
			$str .=$val.'~';
		}
		if ($verify_token === 'abc123') {			
			
			
			$post_data=array('msg'=>$str);		
			$this->Client_model->create_cronjobs_log($post_data);
			echo $challenge;
		}
	}

	public function webhook()
	{

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v16.0/105853551465693/leadgen_forms?fields=page%2Ctest_leads%7Bform_id%2Cfield_data%7D%2Cleads%7Bfield_data%2Cform_id%2Ccampaign_name%2Cad_id%2Cad_name%2Cadset_id%2Cadset_name%2Ccampaign_id%2Ccustom_disclaimer_responses%2Chome_listing%2Ccreated_time%7D%2Cname%2Cleads_count%2Cexpired_leads_count%2Cpage_id&access_token=EAAWnPZAPLZCDsBAFcZC0x5YdULg0cv3rseoMP7Usz3LBuzYmfTY6svZBJZBcuuvshXWCsBJuk7TQxoZBWRcrYJiZAZCR3VShfT4jQGD3QFrM4GkxrOURu3lZA4CiGAZBRTAXd1hkxQfQIyGU0ZAoRMOrZAmAXcZAn7bBMSWtYePMOeVsTEuxAug0YTQv8VMNrpZASP4Q0ZD');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$return = curl_exec($ch);
		if (curl_errno($ch)) {
		}
		curl_close($ch);
		$return_arr=json_decode($return);
		print_r($return_arr); die("ok");	

		// $ch = curl_init();
		// curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v16.0/leadgen_forms?fields=form_id&access_token=EAAWnPZAPLZCDsBAK4KTvZCbCoQq8TS1QhWgEZAzbPpMQhj6JoXDMSFFV9EBlSexzznnEFAzzowB7lQhZBzlfz1bMjVYXJmZBA3NN5ulDI0cZBG13Ky8ULOzydf7iWkZCjUMFZCBtnf4qwMzX3rZAJdcu9c3cZBXfTZC5Ml54BQRF13dt6Lb66FNZB7iTDquvth7lyS6A0Rv13RXHVD1wkbXvbkZCGt');
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// $return = curl_exec($ch);
		// if (curl_errno($ch)) {
		// 	echo 'Error:' . curl_error($ch);
		// }
		// curl_close($ch);
		// $return_arr=json_decode($return);
		// print_r($return_arr); die();
		
		// permanent user access token
		// $ch = curl_init();
		// curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v16.0/oauth/access_token?grant_type=fb_exchange_token&client_id=1591257798016059&client_secret=bfd82394d8ec979162e988e2e74f8af5&fb_exchange_token=EAAWnPZAPLZCDsBAPOcGpU6hsAmcPJN7AfwbX8FJWY9c623FIYutFHTuH6ocYxZCIjG9623tV2Ln3aCKlw5wDbuiNr3UCcGOemhE18UfZCLZAaBwKB9ZBkoFntnRRMTWgdZA9QBBototbdN0HlV8jyDzhxv7JsvMRKcrZCXXP8RZCI2XZAtSfmkCwolFc1TuOmjZCDZBf2iwFB5XHWc6ZBI4ZBWzxCc');
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// $result = curl_exec($ch);
		// if (curl_errno($ch)) {
		// 	echo 'Error:' . curl_error($ch);
		// }
		// curl_close($ch);
		// print_r($result); die("ok");	

	

		
		// $ch = curl_init();
		// curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/822325771444521?fields=access_token&access_token=EAAWnPZAPLZCDsBAATCWKkMSrTBr6HVefdU6AqSyDoiyLC0UPFyZCRTNGUrVSOSjrw74PpgZCZAVDPGZCEw1Yeoz9393jfqqZCn4n61Uhu5bZCezuXAqq3ZBjuWh6VunfLXMRUWd0bNRsnwL2Ax31goPXqkKZBp2I8gLzqZAxaZAPq82BEQMKLV2m3HzqvZCS1tZCwTcfrg2Y7nmZC4v0fw6aTXpzbZAw');
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// $result = curl_exec($ch);
		// if (curl_errno($ch)) {
		// 	echo 'Error:' . curl_error($ch);
		// }
		// curl_close($ch);
		// print_r($result); die("ok");


		

		
	}
	
	
	
}
?>