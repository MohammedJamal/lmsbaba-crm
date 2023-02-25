<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['pre_controller'] = array(
				'class'    => 'PreInitVariable',
				'function' => 'set_client_db',
				'filename' => 'PreInitVariable.php',
				'filepath' => 'hooks',
				'params'   => array()
				);
/*
$hook['post_controller_constructor'] = array(
        'class'    => 'PostInitVariable',
		'function' => 'checkHaveControllerAccessPermission',
		'filename' => 'PostInitVariable.php',
		'filepath' => 'hooks',
		'params'   => array()
	);
*/

// $hook['post_controller'] = array(
// 							'class'    => 'PostInitVariable',
// 							'function' => 'overwrite_client_db',
// 							'filename' => 'PostInitVariable.php',
// 							'filepath' => 'hooks',
// 							'params'   => array()
// 						);
