<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Features extends CI_Controller {
	private $api_access_token = '';	
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('Client_model'));
	}
	public function index()
	{
		$data=array();		
		$this->load->view('about_us_view',$data);		
	}	

	public function auto_lead_syncing()
	{
		$data=array();		
		$this->load->view('home-features/auto_lead_syncing_view',$data);		
	}

	public function capturing_call_logs()
	{
		$data=array();		
		$this->load->view('home-features/capturing_call_logs_view',$data);		
	}

	public function rule_based_auto_lead_distribution()
	{
		$data=array();		
		$this->load->view('home-features/rule_based_auto_lead_distribution_us_view',$data);		
	}

	public function customized_lead_staging_pipeline()
	{
		$data=array();		
		$this->load->view('home-features/customized_lead_staging_pipeline_view',$data);		
	}

	public function quick_quotation_builder()
	{
		$data=array();		
		$this->load->view('home-features/quick_quotation_builder_view',$data);		
	}

	public function lead_follow_up_alerts()
	{
		$data=array();		
		$this->load->view('home-features/lead_follow_up_alerts_view',$data);		
	}

	public function auto_managed_buyers_address_book()
	{
		$data=array();		
		$this->load->view('home-features/auto_managed_buyers_address_book_view',$data);		
	}

	public function lead_history()
	{
		$data=array();		
		$this->load->view('home-features/lead_history_view',$data);		
	}

	public function whatsAPP_calls_and_email_communication()
	{
		$data=array();		
		$this->load->view('home-features/whatsAPP_calls_and_email_communication_view',$data);		
	}

	public function auto_email_notifications()
	{
		$data=array();		
		$this->load->view('home-features/auto_email_notifications_view',$data);		
	}

	public function dashboard_and_analytics()
	{
		$data=array();		
		$this->load->view('home-features/dashboard_and_analytics_view',$data);		
	}

	public function repetitive_workflow_management()
	{
		$data=array();		
		$this->load->view('home-features/repetitive_workflow_management_view',$data);		
	}

	public function users_permission_management()
	{
		$data=array();		
		$this->load->view('home-features/users_permission_management_view',$data);		
	}

	public function business_whastapp_api_enabled()
	{
		$data=array();		
		$this->load->view('home-features/business_whastapp_api_enabled_view',$data);		
	}

	public function click_to_call_service_enabled()
	{
		$data=array();		
		$this->load->view('home-features/click_to_call_service_enabled_view',$data);		
	}

	public function sms_api_enabled()
	{
		$data=array();		
		$this->load->view('home-features/sms_api_enabled_view',$data);		
	}

	public function secured_smtp_enabled()
	{
		$data=array();		
		$this->load->view('home-features/secured_smtp_enabled_view',$data);		
	}

	public function purchase_order_management()
	{
		$data=array();		
		$this->load->view('home-features/purchase_order_management_view',$data);		
	}

	public function amc_renewal_management()
	{
		$data=array();		
		$this->load->view('home-features/amc_renewal_management_view',$data);		
	}

	public function meeting_event_calendar()
	{
		$data=array();		
		$this->load->view('home-features/meeting_event_calendar_view',$data);		
	}

		
}
