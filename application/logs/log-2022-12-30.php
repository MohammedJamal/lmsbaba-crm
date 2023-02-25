<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-12-30 11:57:50 --> Severity: Warning --> Invalid argument supplied for foreach() D:\xampp\htdocs\lmsbaba_git\application\views\admin\order\po_register_view.php 235
ERROR - 2022-12-30 11:57:50 --> Severity: Warning --> Invalid argument supplied for foreach() D:\xampp\htdocs\lmsbaba_git\application\views\admin\order\po_register_view.php 302
ERROR - 2022-12-30 11:57:50 --> Severity: Warning --> Invalid argument supplied for foreach() D:\xampp\htdocs\lmsbaba_git\application\views\admin\order\po_register_view.php 339
ERROR - 2022-12-30 11:57:50 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable D:\xampp\htdocs\lmsbaba_git\application\views\admin\order\po_register_view.php 399
ERROR - 2022-12-30 16:52:56 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '='N' AND om_po_pi_wise_stage_tag.id IS NOT NULL GROUP BY t1.lead_opportunity_...' at line 25 - Invalid query: SELECT 
			t1.id,
			t1.lead_opportunity_wise_po_id,
			t2.po_date,
			t2.is_cancel,
			t2.cancelled_date,
			t2.cancelled_by,
			cus.contact_person AS cus_contact_person,
			cus.company_name AS cus_company_name,	
			countries.name AS cust_country_name,
			states.name AS cust_state_name,
			cities.name AS cust_city_name,			
			om_po_pi_wise_stage_tag.id AS om_po_pi_wise_stage_tag_id,
			om_po_pi_wise_stage_tag.stage_id AS pi_stage_id,
			om_po_pi_wise_stage_tag.priority AS pi_priority
			FROM po_pro_forma_invoice AS t1 
			INNER JOIN lead_opportunity_wise_po AS t2 ON t1.lead_opportunity_wise_po_id=t2.id 			
			INNER JOIN lead_opportunity AS t3 ON t2.lead_opportunity_id=t3.id  			
			INNER JOIN lead ON t3.lead_id=lead.id 
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 			
			LEFT JOIN countries ON countries.id=cus.country_id 
			LEFT JOIN states ON states.id=cus.state 
			LEFT JOIN cities ON cities.id=cus.city  			 
			LEFT JOIN om_po_pi_wise_stage_tag ON om_po_pi_wise_stage_tag.po_pi_id=t1.id
			WHERE t1.pro_forma_no!='' AND t2.is_cancel=='N' AND om_po_pi_wise_stage_tag.id IS NOT NULL GROUP BY t1.lead_opportunity_wise_po_id ORDER BY om_po_pi_wise_stage_tag.priority DESC,t2.po_date ASC
ERROR - 2022-12-30 18:25:11 --> Query error: No database selected - Invalid query: SELECT `is_session_expire_for_idle`, `idle_time`
FROM `company_setting`
WHERE `id` = 1
ERROR - 2022-12-30 18:25:11 --> Query error: No database selected - Invalid query: SELECT `company_setting`.`id`, `company_setting`.`logo`, `company_setting`.`brochure_file`, `company_setting`.`name`, `company_setting`.`address`, `company_setting`.`city_id`, `company_setting`.`state_id`, `company_setting`.`country_id`, `company_setting`.`pin`, `company_setting`.`about_company`, `company_setting`.`gst_number`, `company_setting`.`pan_number`, `company_setting`.`default_currency`, `company_setting`.`ceo_name`, `company_setting`.`contact_person`, `company_setting`.`email1`, `company_setting`.`email2`, `company_setting`.`mobile1`, `company_setting`.`mobile2`, `company_setting`.`phone1`, `company_setting`.`phone2`, `company_setting`.`website`, `company_setting`.`quotation_cover_letter_body_text`, `company_setting`.`quotation_terms_and_conditions`, `company_setting`.`quotation_cover_letter_footer_text`, `company_setting`.`quotation_bank_details1`, `company_setting`.`quotation_bank_details2`, `company_setting`.`bank_credit_to`, `company_setting`.`bank_name`, `company_setting`.`bank_acount_number`, `company_setting`.`bank_branch_name`, `company_setting`.`bank_branch_code`, `company_setting`.`bank_ifsc_code`, `company_setting`.`bank_swift_number`, `company_setting`.`bank_telex`, `company_setting`.`bank_address`, `company_setting`.`correspondent_bank_name`, `company_setting`.`correspondent_bank_swift_number`, `company_setting`.`correspondent_account_number`, `company_setting`.`indiamart_glusr_mobile`, `company_setting`.`indiamart_glusr_mobile_key`, `company_setting`.`indiamart_assign_to`, `company_setting`.`indiamart_assign_start`, `company_setting`.`is_system_generated_enquiryid_logic`, `company_setting`.`enquiryid_initial`, `company_setting`.`c2c_api_dial_url`, `company_setting`.`c2c_api_userid`, `company_setting`.`c2c_api_password`, `company_setting`.`c2c_api_client_name`, `company_setting`.`is_daily_report_send`, `company_setting`.`daily_report_tomail`, `company_setting`.`daily_report_mail_subject`, `company_setting`.`digital_signature`, `company_setting`.`authorized_signatory`, `company_setting`.`is_cronjobs_auto_regretted_on`, `company_setting`.`auto_regretted_day_interval`, `company_setting`.`is_session_expire_for_idle`, `company_setting`.`idle_time`, `company_setting`.`google_map_api_key`, `company_setting`.`updated_at`, `countries`.`name` as `country_name`, `states`.`name` as `state_name`, `cities`.`name` as `city_name`, `currency`.`name` AS `default_currency_name`, `currency`.`code` AS `default_currency_code`
FROM `company_setting`
LEFT JOIN `countries` ON `countries`.`id` = `company_setting`.`country_id`
LEFT JOIN `states` ON `states`.`id` = `company_setting`.`state_id`
LEFT JOIN `cities` ON `cities`.`id` = `company_setting`.`city_id`
LEFT JOIN `currency` ON `currency`.`id` = `company_setting`.`default_currency`
WHERE `company_setting`.`id` = 1
ERROR - 2022-12-30 18:25:11 --> Query error: No database selected - Invalid query: SELECT 
			t1.id,
			t1.name 
			FROM countries AS t1  WHERE 1=1 
			ORDER BY t1.name
ERROR - 2022-12-30 18:25:11 --> Query error: No database selected - Invalid query: SELECT `id`, `name`, `country_id`
FROM `states`
WHERE `country_id` IS NULL
ERROR - 2022-12-30 18:25:11 --> Query error: No database selected - Invalid query: SELECT `id`, `name`, `state_id`
FROM `cities`
WHERE `state_id` IS NULL
ERROR - 2022-12-30 18:25:11 --> Query error: No database selected - Invalid query: SELECT `user`.*, `category`.`category_name` `dept_name`
FROM `user`
LEFT JOIN `category` ON `user`.`department_id` = `category`.`id`
WHERE `user`.`status` = '0'
ERROR - 2022-12-30 18:25:11 --> Query error: No database selected - Invalid query: SELECT id,
				name,
				code
				FROM currency WHERE 1=1  ORDER BY id ASC
ERROR - 2022-12-30 18:25:11 --> Query error: No database selected - Invalid query: SELECT t1.id,
				t1.sms_service_provider_id,
				t1.sender,
				t1.apikey,
				t1.entity_id,
				t1.name,
				t2.name AS service_provider 
				FROM sms_api AS t1 INNER JOIN sms_service_provider AS t2 ON t1.sms_service_provider_id=t2.id 
ERROR - 2022-12-30 18:25:11 --> Query error: No database selected - Invalid query: SELECT t1.id,
				t1.sms_name,
				t1.sms_keyword,
				t1.is_sms_send,
				t1.default_template_id,
				t1.is_send_sms_to_client,
				t1.send_sms_to_client_template_id,
				t1.is_send_relationship_manager,
				t1.send_relationship_manager_template_id,
				t1.is_send_manager,
				t1.send_manager_template_id,
				t1.is_send_skip_manager,
				t1.send_skip_manager_template_id 				
				FROM sms_forwarding_settings AS t1 WHERE t1.is_active='Y' ORDER BY t1.sort_order
ERROR - 2022-12-30 18:25:11 --> Query error: No database selected - Invalid query: SELECT t1.id,
				t1.whatsapp_service_provider_id,
				t1.sender,
				t1.apikey,
				t1.name,
				t2.name AS service_provider 
				FROM whatsapp_api AS t1 INNER JOIN whatsapp_service_provider AS t2 ON t1.whatsapp_service_provider_id=t2.id 
ERROR - 2022-12-30 18:25:11 --> Query error: No database selected - Invalid query: SELECT t1.id,
				t1.whatsapp_name,
				t1.whatsapp_keyword,
				t1.is_whatsapp_send,
				t1.default_template_id,
				t1.is_send_whatsapp_to_client,
				t1.send_whatsapp_to_client_template_id,
				t1.is_send_relationship_manager,
				t1.send_relationship_manager_template_id,
				t1.is_send_manager,
				t1.send_manager_template_id,
				t1.is_send_skip_manager,
				t1.send_skip_manager_template_id 				
				FROM whatsapp_forwarding_settings AS t1 WHERE t1.is_active='Y' ORDER BY t1.sort_order
ERROR - 2022-12-30 18:25:11 --> Query error: No database selected - Invalid query: SELECT id,variable_type,reserve_keyword,name FROM tbl_template_variable_list WHERE is_active='Y' ORDER BY variable_type,id 
