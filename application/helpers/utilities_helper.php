<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Utilities Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/helpers/history_helper.html
 */

// ------------------------------------------------------------------------




	
	function GetTotSize()
   {
   	 $CI=get_instance();
        $CI->load->database();
   		$dir="accounts/".$CI->session->userdata['logged_in']['lms_url']."/";
	  	$var= getDirectorySize($dir);
	   $size_folder=$var['size'];
	   $size=0;
	  $decimals = 2;  
		 $size_folder = $size_folder/(1024*1024);
	  
	 
        
        $dbName = $CI->db->database;
        $db='lms_baba_'.$CI->session->userdata['logged_in']['lms_url'];
        $dbName = $CI->db->escape($db);
        
        $sql = "SELECT table_schema AS db_name, sum( data_length + index_length ) / 1024 / 1024 AS db_size_mb 
                FROM information_schema.TABLES 
                WHERE table_schema = $dbName
                GROUP BY table_schema ;";
        
        $query = $CI->db->query($sql);
        
        if ($query->num_rows() == 1) {
            
           $row = $query->row(); 
           $size = $row->db_size_mb;
           
           
           
        } else {
            
            log_message('ERROR', "*** Unexpected number of rows returned " . ' | line ' . __LINE__ . ' of ' . __FILE__);
            show_error('Sorry, an error has occured.');
            
        }
        
        $tot_size=$size+$size_folder;
          $tot_size= number_format($tot_size,2);
          return $tot_size;
   
   }
   
   function getDirectorySize($path) 
	{ 
  $totalsize = 0; 
  $totalcount = 0; 
  $dircount = 0; 
  if ($handle = opendir ($path)) 
  { 
    while (false !== ($file = readdir($handle))) 
    { 
      $nextpath = $path . '/' . $file; 
      if ($file != '.' && $file != '..' && !is_link ($nextpath)) 
      { 
        if (is_dir ($nextpath)) 
        { 
          $dircount++; 
          $result = getDirectorySize($nextpath); 
          $totalsize += $result['size']; 
          $totalcount += $result['count']; 
          $dircount += $result['dircount']; 
        } 
        elseif (is_file ($nextpath)) 
        { 
          $totalsize += filesize ($nextpath); 
          $totalcount++; 
        } 
      } 
    } 
  } 
  closedir ($handle); 
  $total['size'] = $totalsize; 
  $total['count'] = $totalcount; 
  $total['dircount'] = $dircount; 
  return $total; 
} 


	function CheckUserSpace()
	{
  		$CI=get_instance();
      $CI->load->database();          
  		$size_limit= $CI->package_data['non_menu'][1]['calculative_value'];
  		$size_unit= $CI->package_data['non_menu'][1]['calculative_value_unit'];      
  		if($size_unit=='GB')
  		{
  			$size_limit=$size_limit*1024;
  		}

  		if($cur_size>=$size_limit)
  		{
  			$lms_url=$CI->session->userdata['logged_in']['lms_url'];
  			redirect($CI->config->base_url.$lms_url.'/error/size_error/');
  		}
  		else
  		{
  			return true;
  		}		
	}
	
