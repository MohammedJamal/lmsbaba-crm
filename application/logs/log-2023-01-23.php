<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2023-01-23 12:28:35 --> Query error: FUNCTION u412811690_17.get_om_priority_name_by_id does not exist - Invalid query: SELECT 
			t1.id,
			t1.lead_opportunity_wise_po_id,
			t1.expected_delivery_date,
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
			om_po_pi_wise_stage_tag.po_pi_split_id AS split_id,
			om_po_pi_wise_stage_tag.priority AS pi_priority,
			om_po_pi_wise_stage_tag.is_lock,
			om_stage.name AS stage_name,
			GROUP_CONCAT(om_stage_form_submitted.form_id) AS submitted_form_id
			FROM po_pro_forma_invoice AS t1 
			INNER JOIN lead_opportunity_wise_po AS t2 ON t1.lead_opportunity_wise_po_id=t2.id 			
			INNER JOIN lead_opportunity AS t3 ON t2.lead_opportunity_id=t3.id  			
			INNER JOIN lead ON t3.lead_id=lead.id 
			INNER JOIN customer AS cus ON cus.id=lead.customer_id 			
			LEFT JOIN countries ON countries.id=cus.country_id 
			LEFT JOIN states ON states.id=cus.state 
			LEFT JOIN cities ON cities.id=cus.city  			 
			LEFT JOIN om_po_pi_wise_stage_tag ON om_po_pi_wise_stage_tag.po_pi_id=t1.id 
			LEFT JOIN om_stage ON om_stage.id=om_po_pi_wise_stage_tag.stage_id 
			LEFT JOIN om_stage_form_submitted ON om_stage_form_submitted.po_pi_id=t1.id AND  
            om_stage_form_submitted.pro_forma_invoice_split_id = (CASE WHEN om_po_pi_wise_stage_tag.po_pi_split_id IS NOT NULL THEN om_po_pi_wise_stage_tag.po_pi_split_id ELSE '' END)   
			WHERE t1.pro_forma_no!='' AND t2.is_cancel='N' AND om_po_pi_wise_stage_tag.id IS NOT NULL  AND (t1.lead_opportunity_wise_po_id='Hei' || om_stage.name LIKE '%Hei%' || cus.company_name LIKE '%Hei%' || countries.name LIKE '%Hei%' || states.name LIKE '%Hei%' || cities.name LIKE '%Hei%' || get_om_priority_name_by_id(om_po_pi_wise_stage_tag.priority) LIKE '%Hei%') GROUP BY om_po_pi_wise_stage_tag.id  ORDER BY om_po_pi_wise_stage_tag.priority DESC,t1.id DESC  LIMIT 0,30
ERROR - 2023-01-23 17:41:34 --> Severity: Warning --> implode(): Invalid arguments passed D:\xampp\htdocs\lmsbaba_git\application\models\Indiamart_setting_model.php 187
ERROR - 2023-01-23 17:41:34 --> Severity: Warning --> implode(): Invalid arguments passed D:\xampp\htdocs\lmsbaba_git\application\models\Indiamart_setting_model.php 187
