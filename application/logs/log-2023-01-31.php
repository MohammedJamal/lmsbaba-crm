<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2023-01-31 12:53:29 --> Severity: Warning --> Invalid argument supplied for foreach() D:\xampp\htdocs\lmsbaba_git\application\views\admin\order\po_register_view.php 236
ERROR - 2023-01-31 12:53:29 --> Severity: Warning --> Invalid argument supplied for foreach() D:\xampp\htdocs\lmsbaba_git\application\views\admin\order\po_register_view.php 303
ERROR - 2023-01-31 12:53:29 --> Severity: Warning --> Invalid argument supplied for foreach() D:\xampp\htdocs\lmsbaba_git\application\views\admin\order\po_register_view.php 340
ERROR - 2023-01-31 12:53:29 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable D:\xampp\htdocs\lmsbaba_git\application\views\admin\order\po_register_view.php 400
ERROR - 2023-01-31 13:26:21 --> Severity: Warning --> Invalid argument supplied for foreach() D:\xampp\htdocs\lmsbaba_git\application\views\admin\order\po_register_view.php 236
ERROR - 2023-01-31 13:26:21 --> Severity: Warning --> Invalid argument supplied for foreach() D:\xampp\htdocs\lmsbaba_git\application\views\admin\order\po_register_view.php 303
ERROR - 2023-01-31 13:26:21 --> Severity: Warning --> Invalid argument supplied for foreach() D:\xampp\htdocs\lmsbaba_git\application\views\admin\order\po_register_view.php 340
ERROR - 2023-01-31 13:26:21 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable D:\xampp\htdocs\lmsbaba_git\application\views\admin\order\po_register_view.php 400
ERROR - 2023-01-31 13:28:10 --> Severity: Warning --> Invalid argument supplied for foreach() D:\xampp\htdocs\lmsbaba_git\application\views\admin\order\po_register_view.php 236
ERROR - 2023-01-31 13:28:10 --> Severity: Warning --> Invalid argument supplied for foreach() D:\xampp\htdocs\lmsbaba_git\application\views\admin\order\po_register_view.php 303
ERROR - 2023-01-31 13:28:10 --> Severity: Warning --> Invalid argument supplied for foreach() D:\xampp\htdocs\lmsbaba_git\application\views\admin\order\po_register_view.php 340
ERROR - 2023-01-31 13:28:10 --> Severity: Warning --> count(): Parameter must be an array or an object that implements Countable D:\xampp\htdocs\lmsbaba_git\application\views\admin\order\po_register_view.php 400
ERROR - 2023-01-31 15:41:56 --> Severity: Compile Error --> Cannot redeclare Order_management_model::GetActiveStageWiseAssignFormList() D:\xampp\htdocs\lmsbaba_git\application\models\Order_management_model.php 1835
ERROR - 2023-01-31 15:42:00 --> Severity: Compile Error --> Cannot redeclare Order_management_model::GetActiveStageWiseAssignFormList() D:\xampp\htdocs\lmsbaba_git\application\models\Order_management_model.php 1835
ERROR - 2023-01-31 15:42:03 --> Severity: Compile Error --> Cannot redeclare Order_management_model::GetActiveStageWiseAssignFormList() D:\xampp\htdocs\lmsbaba_git\application\models\Order_management_model.php 1835
ERROR - 2023-01-31 15:42:04 --> Severity: Compile Error --> Cannot redeclare Order_management_model::GetActiveStageWiseAssignFormList() D:\xampp\htdocs\lmsbaba_git\application\models\Order_management_model.php 1835
ERROR - 2023-01-31 15:51:00 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 't1.sort' at line 12 - Invalid query: SELECT 
		t1.id,
		t1.user_ids,
		t1.link_keyword,
		t1.link_display_name,
		t1.link_description,
		t1.sort_order,		
		t1.is_active,
		t2.user_name_str
		FROM om_user_wise_permission_link AS t1 
		LEFT JOIN user AS t2 ON t2.id IN (t1.user_ids)
		WHERE t1.is_active='Y' AND t1.is_deleted='N' t1.sort
ERROR - 2023-01-31 15:51:02 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 't1.sort' at line 12 - Invalid query: SELECT 
		t1.id,
		t1.user_ids,
		t1.link_keyword,
		t1.link_display_name,
		t1.link_description,
		t1.sort_order,		
		t1.is_active,
		t2.user_name_str
		FROM om_user_wise_permission_link AS t1 
		LEFT JOIN user AS t2 ON t2.id IN (t1.user_ids)
		WHERE t1.is_active='Y' AND t1.is_deleted='N' t1.sort
ERROR - 2023-01-31 15:52:17 --> Query error: Unknown column 't2.user_name_str' in 'field list' - Invalid query: SELECT 
		t1.id,
		t1.user_ids,
		t1.link_keyword,
		t1.link_display_name,
		t1.link_description,
		t1.sort_order,		
		t1.is_active,
		t2.user_name_str
		FROM om_user_wise_permission_link AS t1 
		LEFT JOIN user AS t2 ON t2.id IN (t1.user_ids)
		WHERE t1.is_active='Y' AND t1.is_deleted='N' ORDER BY t1.sort 
ERROR - 2023-01-31 15:52:18 --> Query error: Unknown column 't2.user_name_str' in 'field list' - Invalid query: SELECT 
		t1.id,
		t1.user_ids,
		t1.link_keyword,
		t1.link_display_name,
		t1.link_description,
		t1.sort_order,		
		t1.is_active,
		t2.user_name_str
		FROM om_user_wise_permission_link AS t1 
		LEFT JOIN user AS t2 ON t2.id IN (t1.user_ids)
		WHERE t1.is_active='Y' AND t1.is_deleted='N' ORDER BY t1.sort 
ERROR - 2023-01-31 15:52:35 --> Query error: Unknown column 't2.user_name_str' in 'field list' - Invalid query: SELECT 
		t1.id,
		t1.user_ids,
		t1.link_keyword,
		t1.link_display_name,
		t1.link_description,
		t1.sort_order,		
		t1.is_active,
		t2.user_name_str
		FROM om_user_wise_permission_link AS t1 
		LEFT JOIN user AS t2 ON t2.id IN (t1.user_ids)
		WHERE t1.is_active='Y' AND t1.is_deleted='N' ORDER BY t1.sort_order 
ERROR - 2023-01-31 15:52:38 --> Query error: Unknown column 't2.user_name_str' in 'field list' - Invalid query: SELECT 
		t1.id,
		t1.user_ids,
		t1.link_keyword,
		t1.link_display_name,
		t1.link_description,
		t1.sort_order,		
		t1.is_active,
		t2.user_name_str
		FROM om_user_wise_permission_link AS t1 
		LEFT JOIN user AS t2 ON t2.id IN (t1.user_ids)
		WHERE t1.is_active='Y' AND t1.is_deleted='N' ORDER BY t1.sort_order 
ERROR - 2023-01-31 16:03:31 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'group_concat(t3.name ORDER BY t3.name) AS assigned_user_name
		FROM om_permi...' at line 8 - Invalid query: SELECT 
		t1.id,
		t1.link_keyword,
		t1.link_display_name,
		t1.link_description,
		t1.sort_order,		
		t1.is_active
		group_concat(t3.name ORDER BY t3.name) AS assigned_user_name
		FROM om_permission_link AS t1 
		LEFT JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
		WHERE t1.is_active='Y' AND t1.is_deleted='N' GROUP BY t1.id ORDER BY t1.sort_order
ERROR - 2023-01-31 16:03:32 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'group_concat(t3.name ORDER BY t3.name) AS assigned_user_name
		FROM om_permi...' at line 8 - Invalid query: SELECT 
		t1.id,
		t1.link_keyword,
		t1.link_display_name,
		t1.link_description,
		t1.sort_order,		
		t1.is_active
		group_concat(t3.name ORDER BY t3.name) AS assigned_user_name
		FROM om_permission_link AS t1 
		LEFT JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
		WHERE t1.is_active='Y' AND t1.is_deleted='N' GROUP BY t1.id ORDER BY t1.sort_order
ERROR - 2023-01-31 16:24:20 --> Query error: Unknown column 't1.om_permission_link_id' in 'where clause' - Invalid query: SELECT 
			t1.id,
			t1.name
			FROM user AS t1 
			WHERE t1.status='0' AND t1.om_permission_link_id='1' 
			AND t1.user_id NOT IN (SELECT user_id FROM om_permission_link_wise_assigned_user WHERE om_permission_link_id='1') 
			GROUP BY t1.id
ERROR - 2023-01-31 16:43:39 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) D:\xampp\htdocs\lmsbaba_git\application\models\Order_management_model.php 1888
ERROR - 2023-01-31 16:44:45 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) D:\xampp\htdocs\lmsbaba_git\application\models\Order_management_model.php 1888
ERROR - 2023-01-31 16:45:37 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) D:\xampp\htdocs\lmsbaba_git\application\models\Order_management_model.php 1888
ERROR - 2023-01-31 16:45:48 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) D:\xampp\htdocs\lmsbaba_git\application\models\Order_management_model.php 1888
ERROR - 2023-01-31 16:45:58 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) D:\xampp\htdocs\lmsbaba_git\application\models\Order_management_model.php 1888
ERROR - 2023-01-31 16:46:35 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) D:\xampp\htdocs\lmsbaba_git\application\models\Order_management_model.php 1888
ERROR - 2023-01-31 17:29:34 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_priority'
ERROR - 2023-01-31 17:29:34 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_split'
ERROR - 2023-01-31 17:29:34 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_lock_unlock'
ERROR - 2023-01-31 17:29:34 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_priority'
ERROR - 2023-01-31 17:29:34 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_split'
ERROR - 2023-01-31 17:29:34 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_lock_unlock'
ERROR - 2023-01-31 17:29:34 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_priority'
ERROR - 2023-01-31 17:29:34 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_split'
ERROR - 2023-01-31 17:29:34 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_lock_unlock'
ERROR - 2023-01-31 17:29:40 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_priority'
ERROR - 2023-01-31 17:29:40 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_split'
ERROR - 2023-01-31 17:29:40 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_lock_unlock'
ERROR - 2023-01-31 17:29:40 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_priority'
ERROR - 2023-01-31 17:29:40 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_split'
ERROR - 2023-01-31 17:29:40 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_lock_unlock'
ERROR - 2023-01-31 17:29:41 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_priority'
ERROR - 2023-01-31 17:29:41 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_split'
ERROR - 2023-01-31 17:29:41 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_lock_unlock'
ERROR - 2023-01-31 17:29:41 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_priority'
ERROR - 2023-01-31 17:29:41 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_split'
ERROR - 2023-01-31 17:29:41 --> Query error: Column 'id' in field list is ambiguous - Invalid query: SELECT id FROM om_permission_link AS t1 
				INNER JOIN om_permission_link_wise_assigned_user AS t2 ON t1.id=t2.om_permission_link_id 
				WHERE t2.user_id='1' AND t1.link_keyword='om_lock_unlock'
