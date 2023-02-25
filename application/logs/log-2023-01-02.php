<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2023-01-02 12:18:55 --> Severity: Warning --> Division by zero D:\xampp\htdocs\lmsbaba_git\application\controllers\clientportal\Dashboard_v2.php 196
ERROR - 2023-01-02 13:24:22 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable D:\xampp\htdocs\lmsbaba_git\application\views\admin\order_management\order_list_view_ajax.php 18
ERROR - 2023-01-02 15:57:53 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near '' at line 1 - Invalid query: SELECT po_pi_id FROM om_po_pi_wise_stage_tag WHERE id=
ERROR - 2023-01-02 15:59:08 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND om_history.updated_by='1' ORDER BY om_history.id DESC' at line 32 - Invalid query: SELECT 	
					om_history.title,				
					om_history.comment,
					om_history.created_at,
					om_history.ip_address,					
					quo.id AS quotation_id,
					quo.opportunity_title AS quotation_title,
					lead.title as lead_title,
					lead.customer_id as lead_customer_id,
					lead.assigned_user_id,
					lead.source_id,
					user.name as user_name,
					u2.name AS updated_by,
					cus.first_name as cus_first_name,
					cus.last_name as cus_last_name,
					cus.mobile as cus_mobile,
					source.name as source_name,
					source.alias_name as source_alias_name,
					p_invoice.proforma_type,					
					p_invoice.pro_forma_no,
					p_invoice.pro_forma_date,
					p_invoice.due_date AS pro_forma_due_date,
					p_invoice.expected_delivery_date AS pro_forma_expected_delivery_date
					FROM om_history 
					LEFT JOIN lead on om_history.lead_id=lead.id
					LEFT JOIN lead_opportunity AS quo on om_history.lead_opportunity_id=quo.id 
					INNER JOIN customer as cus on cus.id=lead.customer_id 
					INNER JOIN source on source.id=lead.source_id 
					LEFT JOIN user on user.id=lead.assigned_user_id
					LEFT JOIN user AS u2 on u2.id=om_history.updated_by 
					LEFT JOIN po_pro_forma_invoice AS p_invoice ON om_history.po_pi_id=p_invoice.id 
					WHERE 1=1  AND om_history.po_pi_id= AND om_history.updated_by='1' ORDER BY om_history.id DESC
ERROR - 2023-01-02 16:13:53 --> Severity: error --> Exception: Call to undefined method Order_model::GetTaggedPiListPagination_count() D:\xampp\htdocs\lmsbaba_git\application\controllers\clientportal\Order_management.php 139
ERROR - 2023-01-02 17:26:37 --> Query error: Unknown column 't2.is_cancel' in 'where clause' - Invalid query: SELECT 
		t1.id,
		t1.name,
		t1.sort,		
		t1.is_system_generated,
		t1.is_active,
		count(t2.po_pi_id) AS stage_wise_pi_count
		FROM om_stage AS t1 	
		LEFT JOIN om_po_pi_wise_stage_tag AS t2 ON t1.id=t2.stage_id	
		WHERE t1.is_active='Y' AND t2.is_cancel='N' AND t1.is_deleted='N' GROUP BY t1.id ORDER BY t1.sort
ERROR - 2023-01-02 17:26:39 --> Query error: Unknown column 't2.is_cancel' in 'where clause' - Invalid query: SELECT 
		t1.id,
		t1.name,
		t1.sort,		
		t1.is_system_generated,
		t1.is_active,
		count(t2.po_pi_id) AS stage_wise_pi_count
		FROM om_stage AS t1 	
		LEFT JOIN om_po_pi_wise_stage_tag AS t2 ON t1.id=t2.stage_id	
		WHERE t1.is_active='Y' AND t2.is_cancel='N' AND t1.is_deleted='N' GROUP BY t1.id ORDER BY t1.sort
