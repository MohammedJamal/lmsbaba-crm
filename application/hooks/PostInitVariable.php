<?php 
if ( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
 
class PostInitVariable {
     
    protected $CI;  
    protected $RTR;
    protected $accessable_function_arr = array();
    protected $class_name='';
    // We'll use a constructor, as you can't directly call a function
    // from a property definition.
    public function __construct()
    {   
        // Assign the CodeIgniter super-object
        $this->CI   =& get_instance();
        $this->RTR  =& load_class('Router');
        $this->parent_db = $this->CI->load->database('default', TRUE);
        $this->client_db = $this->CI->load->database('client', TRUE);
        $this->class_name=$this->RTR->fetch_class();        
    }

    function checkHaveControllerAccessPermission()
    {
        return true;
        // echo $this->class_name; die();
        //if($this->CI->router->fetch_class()=='adminmaster' )
        
        if($this->class_name=='adminmaster' || $this->class_name=='cronjobs' || $this->class_name=='home' || $this->class_name=='rest_client' || $this->class_name=='rest_lead' || $this->class_name=='rest_user')
        {
            return true;
        }
        else
        {
            $data["class"]      = $this->RTR->fetch_class();
            $data["method"]     = $this->RTR->fetch_method();
            $this->CI->accessable_function_arr  = array();
            $all_session = $this->CI->session->userdata();
            $logged_in_info = $all_session['admin_session_data'];
            
            $data['package_data'] = $this->get_package($data);
            
            
            if($data['package_data']!='package_expire')
            {   
                if($data['package_data']!='')
                {
                    $package_id = $data['package_data']['all'][0]['package_id'];
                    $package_order_id = $data['package_data']['all'][0]['package_order_id'];
               

                    if($logged_in_info['user_id'] == 1){
                        // ----------------------------------------------------
                        $available_ids = array(); 
                        $available_sub_menu_id_str = '';  
                        if(count($data['package_data']['menu']))
                        {
                            foreach($data['package_data']['menu'] as $menu)
                            {
                                $available_sub_menu_id_str.=$menu['sub_menu_ids'].',';
                                array_push($available_ids, $menu['menu_id']);
                            }
                        }
                        
                        $available_sub_menu_id_str = rtrim($available_sub_menu_id_str,",");
                        
                        $data['available_sub_menu_id_str'] = $available_sub_menu_id_str;
                        $data['available_menu_id_str'] = implode(",",$available_ids);
                        // -----------------------------------------------------

                        $get_user_access_data = $data['package_data'];
                    }
                    else{
                       
                        $get_user_access_data =  $this->get_user_permission($logged_in_info['user_id']);

                        // ----------------------------------------------------
                        $available_ids = array();  
                        if($get_user_access_data['menu'])
                        {
                            foreach($get_user_access_data['menu'] as $menu)
                            {
                                array_push($available_ids, $menu['menu_id']);
                            }
                        }
                        
                        $data['available_menu_id_str'] = implode(",",$available_ids);
                        // -----------------------------------------------------
                    }

                    if($data['available_menu_id_str']!=''){
                        $data["menu"]     = $this->get_menu($data);
                        
                    }
                    else{
                        $data["menu"]     = array();

                    }                
                }
                else
                {
                    $package_id = '';
                    $package_order_id = '';
                    $data["menu"] = array();
                    die("Package data not found!");
                }             
            }
            else
            {
                $package_id = '';
                $package_order_id = '';
                $data["menu"] = array();
                die("Package has been expired!");
            }

            //$this->CI->package_id=$package_id;
            //$this->CI->package_order_id=$package_order_id;
            //$this->CI->package_data=$data['package_data'];

            //Set User Session
            $this->CI->session->set_userdata('package_id',$package_id);
            $this->CI->session->set_userdata('package_order_id',$package_order_id);
            $this->CI->session->set_userdata('package_data',$data['package_data']);
            $this->CI->permission_data=$get_user_access_data;
            $this->CI->menu=$data["menu"];            
            return true;
        }
        
    }

    function get_menu( $data = array() )
    {
        $result     = array();
        $condStr    = '';
        $condSubStr= '';

        if($data['available_menu_id_str']!='')
        {
            $condStr .=" AND id IN (".$data['available_menu_id_str'].")";
        }

        if($data['available_sub_menu_id_str']!='')
        {
            $condSubStr .=" AND id IN (".$data['available_sub_menu_id_str'].")";
        }

        // PARENT DB
        $tbl_menu = $this->parent_db->dbprefix('tbl_menu');
        $tbl_sub_menu = $this->parent_db->dbprefix('tbl_sub_menu');
        $tbl_sub_menu_wise_method = $this->parent_db->dbprefix('tbl_sub_menu_wise_method_list');
        $sql = 'SELECT id,
                icon,
                menu_name,
                is_sub_menu_available,
                is_display_on_top,
                sort_order,
                is_active 
                FROM '.$tbl_menu.'  WHERE 1 = 1 '. $condStr .' AND is_active="Y" ORDER BY sort_order ASC'; 
        $query  = $this->parent_db->query($sql);        
        if ( $query->num_rows() > 0 ) 
        {
            $tempArr = array();         
            foreach( $query->result_array() as $row )
            {
                $tempSubArr = array(); 
                $sql2 = 'SELECT id,
                menu_id,
                sub_menu_name,
                link_name,
                is_available_on_top_menu,
                method_list,sort_order,
                is_display_on_user_permission,
                is_active 
                FROM '.$tbl_sub_menu.'  WHERE menu_id='.$row["id"].' '. $condSubStr .' AND is_active="Y" ORDER BY sort_order ASC';
                $query2  = $this->parent_db->query($sql2);
                if ( $query2->num_rows() > 0 ) 
                { 
                    foreach( $query2->result_array() as $row2 )
                    {
                        
                        $tempSubMethodArr = array(); 
                        $sql3 = 'SELECT id,
                        menu_id,
                        sub_menu_id,
                        method_display_name,
                        controller_name,
                        method_name,
                        is_display 
                        FROM '.$tbl_sub_menu_wise_method.'  WHERE menu_id='.$row["id"].' AND sub_menu_id='.$row2["id"].' ORDER BY id ASC'; 
                        $query3  = $this->parent_db->query($sql3);
                        if($query3->num_rows() > 0 ) 
                        { 
                            foreach( $query3->result_array() as $row3 )
                            {
                                $tempSubMethodArr[$row3["id"]] = array(
                                    "method_auto_id"=>$row3["id"], 
                                    "method_display_name"=>$row3["method_display_name"], 
                                    "controller_name"=>$row3["controller_name"],
                                    "method_name"=>$row3["method_name"],
                                    "is_display"=>$row3["is_display"]
                                    );
                            }
                        }

                    $tempSubArr[$row2["id"]] = array("menu_id"      => $row2["menu_id"], 
                                                    "sub_menu_id" => $row2["id"],
                                                    "sub_menu_name" => $row2["sub_menu_name"],
                                                    "link_name"     => $row2["link_name"],
                                                    "is_available_on_top_menu"     => $row2["is_available_on_top_menu"],
                                                    "method_list"   => $row2["method_list"],
                                                    "sort_order"    => $row2["sort_order"],
                                                    "access_permission"    => '',
                                                    "is_display_on_user_permission"    => $row2["is_display_on_user_permission"],
                                                    "sub_menu_wise_method"   => $tempSubMethodArr
                                                    );
                    }
                }          
                  
                $tempArr[$row["id"]]["menu"] = array(
                                                    "id"         => $row["id"],
                                                    "icon"       => $row["icon"], 
                                                    "menu_name"  => $row["menu_name"], 
                                                    "is_sub_menu_available"  => $row["is_sub_menu_available"], 
                                                    "is_display_on_top"  => $row["is_display_on_top"],
                                                    "sort_order" => $row["sort_order"],
                                                    "sub_menu"   => $tempSubArr
                                                    );

                $result = $tempArr;     
            }
        }
        return $result;
    }

    function get_package($data = array())
    {
        $sql = "SELECT id,
                package_name,
                package_price,
                purchased_datetime,
                expire_date 
                FROM tbl_package_order WHERE '".date("Y-m-d")."' BETWEEN DATE(purchased_datetime) AND DATE(expire_date) LIMIT 1";
        $query  = $this->client_db->query($sql);
        
        if($query->num_rows()>0)
        {
            $row = $query->row_array();
            $sql2 = "SELECT id,
                    package_order_id,
                    package_id, 
                    package_attribute_id,
                    attribute_name,
                    reserved_keyword, 
                    display_value,
                    calculative_value,
                    calculative_value_unit,
                    is_menu,
                    menu_id,
                    sub_menu_ids
                    FROM tbl_package_order_detail WHERE package_order_id='".$row['id']."'";
            $query2  = $this->client_db->query($sql2);
            if($query2->num_rows()>0)
            {
                $non_mennu_arr = array();
                $menu_arr = array();   
                $all = array();            
                foreach( $query2->result_array() as $row2)
                {
                    if($row2['is_menu']=='Y')
                    {                        
                        $menu_arr[] = array('menu_id'=>$row2['menu_id'],'sub_menu_ids'=>$row2['sub_menu_ids']);
                    }
                    else
                    {
                        $non_mennu_arr[] = array(
                                            'id'=>$row2['id'],
                                            'package_order_id'=>$row2['package_order_id'],
                                            'package_id'=>$row2['package_id'],
                                            'package_attribute_id'=>$row2['package_attribute_id'],
                                            'attribute_name'=>$row2['attribute_name'],
                                            'reserved_keyword'=>$row2['reserved_keyword'],
                                            'display_value'=>$row2['display_value'],
                                            'calculative_value'=>$row2['calculative_value'],
                                            'calculative_value_unit'=>$row2['calculative_value_unit']
                                            );
                    }
                    $all[] = array(
                            'id'=>$row2['id'],
                            'package_order_id'=>$row2['package_order_id'],
                            'package_id'=>$row2['package_id'],
                            'package_attribute_id'=>$row2['package_attribute_id'],
                            'attribute_name'=>$row2['attribute_name'],
                            'reserved_keyword'=>$row2['reserved_keyword'],
                            'display_value'=>$row2['display_value'],
                            'calculative_value'=>$row2['calculative_value'],
                            'calculative_value_unit'=>$row2['calculative_value_unit'],
                            'is_menu'=>$row2['is_menu'],
                            'menu_id'=>$row2['menu_id'],
                            'sub_menu_ids'=>$row2['sub_menu_ids']
                            );
                    
                }
                $final_arr = array('menu'=>$menu_arr,'non_menu'=>$non_mennu_arr,'all'=>$all);
                return $final_arr;
            }
            else
            {
                return '';
            }
        }
        else
        {
            return'package_expire';
        }
    }

    function get_user_permission($user_id)
    {
        
        $sql2 = "SELECT id,
        user_id,
        package_attribute_id,
        attribute_name,
        reserved_keyword,
        display_value,
        calculative_value,
        calculative_value_unit,
        is_menu,
        menu_id,
        sub_menu_ids 
        FROM tbl_user_permission WHERE user_id='".$user_id."'";
        $query2  = $this->client_db->query($sql2);
        if($query2->num_rows()>0)
        {
            $non_mennu_arr = array();
            $menu_arr = array();   
            $all = array();            
            foreach( $query2->result_array() as $row2)
            {
                if($row2['is_menu']=='Y')
                {                        
                    $menu_arr[] = array('menu_id'=>$row2['menu_id'],'sub_menu_ids'=>$row2['sub_menu_ids']);
                }
                else
                {
                    $non_mennu_arr[] = array(
                                        'package_attribute_id'=>$row2['package_attribute_id'],
                                        'attribute_name'=>$row2['attribute_name'],
                                        'reserved_keyword'=>$row2['reserved_keyword'],
                                        'display_value'=>$row2['display_value'],
                                        'calculative_value'=>$row2['calculative_value'],
                                        'calculative_value_unit'=>$row2['calculative_value_unit']
                                        );
                }
                $all[] = array(
                        'package_attribute_id'=>$row2['package_attribute_id'],
                        'attribute_name'=>$row2['attribute_name'],
                        'reserved_keyword'=>$row2['reserved_keyword'],
                        'display_value'=>$row2['display_value'],
                        'calculative_value'=>$row2['calculative_value'],
                        'calculative_value_unit'=>$row2['calculative_value_unit'],
                        'is_menu'=>$row2['is_menu'],
                        'menu_id'=>$row2['menu_id'],
                        'sub_menu_ids'=>$row2['sub_menu_ids']
                        );
                
            }
            $final_arr = array('menu'=>$menu_arr,'non_menu'=>$non_mennu_arr,'all'=>$all);
            return $final_arr;
        }
    }
}