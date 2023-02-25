<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = 'error_404';
$route['translate_uri_dashes'] = FALSE;
$route['disclaimer'] = 'disclaimer/index/';
$route['privacy_policy'] = 'privacy_policy/index/';
$route['terms_of_services'] = 'terms_of_services/index/';
$route['clientportal/dashboard_old'] = 'clientportal/dashboard/index/';
$route['clientportal/dashboard'] = 'clientportal/dashboard_v2/index/';
$route['about-us'] = 'about_us/index/';
$route['contact-us'] = 'contact_us/index/';
$route['schedule-demo'] = 'schedule_demo/index/';
// Home Page Features
$route['auto-lead-syncing'] = 'features/auto_lead_syncing/';
$route['capturing-call-logs'] = 'features/capturing_call_logs/';
$route['rule-based-auto-lead-distribution'] = 'features/rule_based_auto_lead_distribution/';
$route['customized-lead-staging-pipeline'] = 'features/customized_lead_staging_pipeline/';
$route['quick-quotation-builder'] = 'features/quick_quotation_builder/';
$route['lead-follow-up-alerts'] = 'features/lead_follow_up_alerts/';
$route['auto-managed-buyers-address-book'] = 'features/auto_managed_buyers_address_book/';
$route['lead-history'] = 'features/lead_history/';
$route['whatsAPP-calls-and-email-communication'] = 'features/whatsAPP_calls_and_email_communication/';
$route['auto-email-notifications'] = 'features/auto_email_notifications/';
$route['dashboard-and-analytics'] = 'features/dashboard_and_analytics/';
$route['repetitive-workflow-management'] = 'features/repetitive_workflow_management/';
$route['users-permission-management'] = 'features/users_permission_management/';
$route['business-whastapp-api-enabled'] = 'features/business_whastapp_api_enabled/';
$route['click-to-call-service-enabled'] = 'features/click_to_call_service_enabled/';
$route['sms-api-enabled'] = 'features/sms_api_enabled/';
$route['secured-smtp-enabled'] = 'features/secured_smtp_enabled/';
$route['purchase-order-management'] = 'features/purchase_order_management/';
$route['amc-renewal-management'] = 'features/amc_renewal_management/';
$route['meeting-event-calendar'] = 'features/meeting_event_calendar/';
// Home page Features

$route['(:any)'] = 'home';
$route['clientportal/login/(:any)'] = 'clientportal/login/index';
$route['preview/(:any)'] = 'preview_quotation/index/';