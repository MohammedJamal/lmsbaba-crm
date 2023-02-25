<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> Illegal string offset 'ID' D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\classes\cssmgr.php 1302
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> Cannot assign an empty string to a string offset D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\classes\cssmgr.php 1302
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> Illegal string offset 'LANG' D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\classes\cssmgr.php 1307
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> Cannot assign an empty string to a string offset D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\classes\cssmgr.php 1307
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> Illegal string offset 'ID' D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\classes\cssmgr.php 1355
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> Illegal string offset 'LANG' D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\classes\cssmgr.php 1355
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 11:36:04 --> Severity: Warning --> A non-numeric value encountered D:\xampp\htdocs\lmsbaba_git\application\third_party\mpdf\mpdf.php 30648
ERROR - 2023-01-12 16:41:36 --> Severity: error --> Exception: syntax error, unexpected ';', expecting ')' D:\xampp\htdocs\lmsbaba_git\application\controllers\clientportal\Order_management.php 1773
ERROR - 2023-01-12 17:08:08 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM om_stage AS t1 	
		LEFT JOIN om_po_pi_wise_stage_tag AS t2 ON t1.id=t2....' at line 9 - Invalid query: SELECT 
		t1.id,
		t1.name,
		t1.sort,		
		t1.is_system_generated,
		t1.is_active,
		count(DISTINCT t2.po_pi_id) AS stage_wise_pi_count,
		-- SUM(if(lead_opportunity_wise_po.is_cancel!='Y',1,0)) AS stage_wise_pi_count
		FROM om_stage AS t1 	
		LEFT JOIN om_po_pi_wise_stage_tag AS t2 ON t1.id=t2.stage_id 
		LEFT JOIN po_pro_forma_invoice ON po_pro_forma_invoice.id=t2.po_pi_id 
		LEFT JOIN po_pro_forma_invoice_split AS t1_split ON po_pro_forma_invoice.id=t1_split.po_pro_forma_invoice_id
		LEFT JOIN lead_opportunity_wise_po ON lead_opportunity_wise_po.id=po_pro_forma_invoice.lead_opportunity_wise_po_id 	
		WHERE t1.is_active='Y' 
		-- AND lead_opportunity_wise_po.is_cancel!='Y' 
		-- AND t1.is_deleted='N' 
		-- AND po_pro_forma_invoice.pro_forma_no!=''  
		-- AND t2.id IS NOT NULL
		GROUP BY t1.id ORDER BY t1.sort
ERROR - 2023-01-12 17:08:10 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'FROM om_stage AS t1 	
		LEFT JOIN om_po_pi_wise_stage_tag AS t2 ON t1.id=t2....' at line 9 - Invalid query: SELECT 
		t1.id,
		t1.name,
		t1.sort,		
		t1.is_system_generated,
		t1.is_active,
		count(DISTINCT t2.po_pi_id) AS stage_wise_pi_count,
		-- SUM(if(lead_opportunity_wise_po.is_cancel!='Y',1,0)) AS stage_wise_pi_count
		FROM om_stage AS t1 	
		LEFT JOIN om_po_pi_wise_stage_tag AS t2 ON t1.id=t2.stage_id 
		LEFT JOIN po_pro_forma_invoice ON po_pro_forma_invoice.id=t2.po_pi_id 
		LEFT JOIN po_pro_forma_invoice_split AS t1_split ON po_pro_forma_invoice.id=t1_split.po_pro_forma_invoice_id
		LEFT JOIN lead_opportunity_wise_po ON lead_opportunity_wise_po.id=po_pro_forma_invoice.lead_opportunity_wise_po_id 	
		WHERE t1.is_active='Y' 
		-- AND lead_opportunity_wise_po.is_cancel!='Y' 
		-- AND t1.is_deleted='N' 
		-- AND po_pro_forma_invoice.pro_forma_no!=''  
		-- AND t2.id IS NOT NULL
		GROUP BY t1.id ORDER BY t1.sort
