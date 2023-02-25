<?php 
if ( !defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
 
class PreInitVariable {
     
    protected $CI;  
    protected $RTR;    
    // We'll use a constructor, as you can't directly call a function
    // from a property definition.
    public function __construct()
    {
        // Assign the CodeIgniter super-object
        $this->CI   =&get_instance();
        $this->RTR  =&load_class('Router');              
    }

    function set_client_db()
    {
        // client db
        // define("DB_USERNAME","",true);
        // define("DB_PASSWORD","",true);
        // define("DB_NAME","",true);

        // default db
        // $arr_conn[] = array("username"=>"root","password"=>'');
        // $arr_conn[] = array("username"=>"root","password"=>'');
        // $arr_conn[] = array("username"=>"root","password"=>'');
        // $arr_conn[] = array("username"=>"root","password"=>'');
        // $arr_conn[] = array("username"=>"root","password"=>'');
        // $tmp_arr=array("1","2","3","4");
        // $randIndex = array_rand($tmp_arr,1);
        // $connection_arr=$arr_conn["$randIndex"];
        // define("DB_DEFAULT_USERNAME",$connection_arr['username']);
        // define("DB_DEFAULT_PASSWORD",$connection_arr['password']);
    }
}
