<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');


/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/********defined by Chanchal Baidya******************************/


defined('NEW_LEAD_CREATE_MAIL') OR define('NEW_LEAD_CREATE_MAIL', 'A New Lead Created From E-mail');
defined('SENT_TO_CLIENT') OR define('SENT_TO_CLIENT', 'Quotation sent to client');
defined('LEAD_GENERAL_UPDATE') OR define('LEAD_GENERAL_UPDATE', 'A New Comment Added');
defined('LEAD_BUYER_UPDATE') OR define('LEAD_BUYER_UPDATE', 'Contact Information Updated');
defined('OPPORTUNITY_CREATE') OR define('OPPORTUNITY_CREATE', 'A New Proposal Created');
defined('OPPORTUNITY_UPDATE') OR define('OPPORTUNITY_UPDATE', 'Proposal Updated');
//defined('QUOTATION_CREATE') OR define('QUOTATION_CREATE', 'A New Quotation Created');
defined('QUOTATION_PRODUCT_REMOVE') OR define('QUOTATION_PRODUCT_REMOVE', 'Product Removed From quotation');
defined('QUOTATION_PRODUCT_UPDATE') OR define('QUOTATION_PRODUCT_UPDATE', 'Product Updated In quotation');
defined('QUOTATION_SENT_BUYER') OR define('QUOTATION_SENT_BUYER', 'Quotation Sent To Buyer');
defined('QUOTATION_PRODUCT_PRICE_UPDATE') OR define('QUOTATION_PRODUCT_PRICE_UPDATE', 'Product Price Updated In Quotation');
defined('LEAD_TAGGED') OR define('LEAD_TAGGED', 'New Reply From Buyer Tag');
defined('CUSTOMER_ASSIGN_LEAD') OR define('CUSTOMER_ASSIGN_LEAD', 'New Reply From Buyer Tag');


// =======================================================
// CUSTOM QUOTATION 
defined('QUOTATION_CREATE') OR define('QUOTATION_CREATE', 'A new quotation created');
defined('QUOTATION_UPDATE') OR define('QUOTATION_UPDATE', 'Existing quotation updated');
defined('QUOTATION_COPY') OR define('QUOTATION_COPY', 'A copy of the existing quotation');
defined('QUOTATION_PDF_CREATE') OR define('QUOTATION_PDF_CREATE', 'A new quotation pdf created');
defined('NEW_LEAD_CREATE_MANUAL') OR define('NEW_LEAD_CREATE_MANUAL', 'A New Lead Created');

defined('NEW_LEAD_CREATE_IM') OR define('NEW_LEAD_CREATE_IM', 'A New Lead Created from Indiamart API');
defined('NEW_LEAD_CREATE_TI') OR define('NEW_LEAD_CREATE_TI', 'A New Lead Created from Tradeindia API');
defined('NEW_LEAD_CREATE_AJ') OR define('NEW_LEAD_CREATE_AJ', 'A New Lead Created from Aajjo API');
defined('NEW_LEAD_CREATE_JD') OR define('NEW_LEAD_CREATE_JD', 'A New Lead Created from JustDial');
defined('NEW_LEAD_CREATE_WEBSITE') OR define('NEW_LEAD_CREATE_WEBSITE', 'A New Lead Created from Website');
defined('NEW_LEAD_CREATE_RENEWAL') OR define('NEW_LEAD_CREATE_RENEWAL', 'A New Lead Created from Renewal');
defined('NEW_LEAD_CREATE_EI') OR define('NEW_LEAD_CREATE_EI', 'A New Lead Created from Exporterindia API');
defined('NEW_LEAD_CREATE_FB') OR define('NEW_LEAD_CREATE_FB', 'A New Lead Created from Facebook API');

defined('LEAD_UPDATE_MANUAL') OR define('LEAD_UPDATE_MANUAL', 'The Lead Updated');
defined('ACCOUNT_MANAGER_CHANGE') OR define('ACCOUNT_MANAGER_CHANGE', 'The account manager has been changed');
defined('QUOTATION_WISE_PO_UPLOAD') OR define('QUOTATION_WISE_PO_UPLOAD', 'PO has been uploaded');
defined('QUOTATION_WISE_PO_UPLOAD_UPDATED') OR define('QUOTATION_WISE_PO_UPLOAD_UPDATED', 'PO has been updated');

defined('PO_PAYMENT_TERMS_CREATE') OR define('PO_PAYMENT_TERMS_CREATE', 'PO payment terms have been created');
defined('PO_PAYMENT_TERMS_UPDATE') OR define('PO_PAYMENT_TERMS_UPDATE', 'PO payment terms have been updated');

defined('PO_PRO_FORMA_INVOICE_CREATE') OR define('PO_PRO_FORMA_INVOICE_CREATE', 'PO proforma invoice has been created');
defined('PO_PRO_FORMA_INVOICE_UPDATE') OR define('PO_PRO_FORMA_INVOICE_UPDATE', 'PO proforma invoice has been updated');

defined('PO_INVOICE_CREATE') OR define('PO_INVOICE_CREATE', 'PO invoice has been created');
defined('PO_INVOICE_UPDATE') OR define('PO_INVOICE_UPDATE', 'PO invoice has been updated');

defined('PO_UPDATE_COMMENT') OR define('PO_UPDATE_COMMENT', 'A PO New Comment Added');

defined('PO_CANCELED') OR define('PO_CANCELED', 'PO canceled');
// CUSTOM QUOTATION
// =======================================================
defined('DOWNLOAD_FROM_GMAIL') OR define('DOWNLOAD_FROM_GMAIL', 'Download From Gmail');

defined('CODEBASE_ENV') OR define('CODEBASE_ENV', 'dev');

defined('PRO_FORMA_INV_SENT_TO_CLIENT') OR define('PRO_FORMA_INV_SENT_TO_CLIENT', 'Proforma Invoice sent to client');
defined('INVOICE_SENT_TO_CLIENT') OR define('INVOICE_SENT_TO_CLIENT', 'Invoice sent to client');

defined('DB_HOSTNAME') OR define('DB_HOSTNAME', 'localhost:3306');
defined('DB_USERNAME') OR define('DB_USERNAME', 'root');
defined('DB_PASSWORD') OR define('DB_PASSWORD', '');
define("DB_NAME","",true);


defined('LEAD_SOURCE_CHANGE') OR define('LEAD_SOURCE_CHANGE', 'The Lead Source has been changed');
defined('LEAD_STATUS_CHANGE') OR define('LEAD_STATUS_CHANGE', 'The Lead Status has been changed');

defined('MEETING_ADD') OR define('MEETING_ADD', 'A meeting scheduled');
defined('MEETING_UPDATE') OR define('MEETING_UPDATE', 'Meeting updated');
defined('MEETING_RESHEDULE') OR define('MEETING_RESHEDULE', 'Meeting updated');
defined('MEETING_DISPOSE') OR define('MEETING_DISPOSE', 'Meeting completed');
defined('MEETING_CANCEL') OR define('MEETING_CANCEL', 'Meeting cancelled');

// ===================================================================
// mail fire
defined('MF_CHANGE_LEAD_ASSIGNEE') OR define('MF_CHANGE_LEAD_ASSIGNEE', 'CLA'); //Change Lead Assignee
defined('MF_ENQUIRY_REGRET') OR define('MF_ENQUIRY_REGRET', 'ER'); // mark deal lost
defined('MF_UPDATE_LEAD') OR define('MF_UPDATE_LEAD', 'UL'); // Update Lead
defined('MF_DAILY_REPORT') OR define('MF_DAILY_REPORT', 'DR'); // generate_daily_report mail
// mail fire
// ===================================================================

// ============================================================================
// LMSBABA APP
defined('LEAD_UPDATE_MANUAL_APP') OR define('LEAD_UPDATE_MANUAL_APP', 'The Lead Updated From Lmababa App');
defined('LEAD_GENERAL_UPDATE_APP') OR define('LEAD_GENERAL_UPDATE_APP', 'A New Comment Added From Lmsbaba App');
// LMSBABA APP
// ============================================================================

// ============================================================================
// ORDER MANAGEMENT
defined('OM_PRIORITY_CHANGE') OR define('OM_PRIORITY_CHANGE', 'The priority has been changed');
defined('OM_STAGE_CHANGE') OR define('OM_STAGE_CHANGE', 'The stage has been changed');
defined('OM_SUBMITTED_DOCUMENT') OR define('OM_SUBMITTED_DOCUMENT', 'The document has been submitted');
defined('OM_SUBMITTED_DOCUMENT_EDIT') OR define('OM_SUBMITTED_DOCUMENT_EDIT', 'The document has been updated');
defined('OM_SUBMITTED_DOCUMENT_DELETE') OR define('OM_SUBMITTED_DOCUMENT_DELETE', 'The document has been deleted');
defined('OM_DOWNLOAD') OR define('OM_DOWNLOAD', 'The document has been downloaded');
defined('OM_SPLIT') OR define('OM_SPLIT', 'The order has been split');
defined('OM_UPDATE_COMMENT') OR define('OM_UPDATE_COMMENT', 'A New Comment Added');
defined('OM_LOCK_CHANGE') OR define('OM_LOCK_CHANGE', 'The lock status has been changed');
// ORDER MANAGEMENT
// =============================================================================



// =============================================================================
// FACEBOOK
defined('FB_GRAPH_API_VERSION') OR define('FB_GRAPH_API_VERSION', 'v16.0');
defined('FB_APP_ID') OR define('FB_APP_ID', '1591257798016059');
defined('FB_APP_SECRET') OR define('FB_APP_SECRET', 'bfd82394d8ec979162e988e2e74f8af5');
// FACEBOOK
// =============================================================================


