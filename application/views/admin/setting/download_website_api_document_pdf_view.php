<pre style="font-size:12px;">
&#60;?php
if(strtolower($_SERVER["REQUEST_METHOD"]) == 'post')
{
    $email=$_POST['email'];
    $mobile=$_POST['mobile'];
    $contact_person=$_POST['contact_person'];
    $company_name=$_POST['company_name'];
    $country=$_POST['country'];
    $city=$_POST['city'];
    $product_service=$_POST['product_service'];
    $describe_requirement=$_POST['describe_requirement'];

    $url = "<?php echo base_url(); ?>capture/website/<?php echo $this->session->userdata['admin_session_data']['id']; ?>";

    $dataArray = array(
                'email'=>$email,
                'mobile'=>$mobile,
                'contact_person'=>$contact_person,
                'company_name'=>$company_name,
                'country'=>$country,
                'city'=>$city,
                'product_service'=>$product_service,
                'describe_requirement'=>$describe_requirement,
                'source'=>'milpaints.com'
                );
    $ch = curl_init();
    $data = http_build_query($dataArray);
    $getUrl = $url;

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_URL, $getUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 80);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);

    $response = curl_exec($ch);

    if(!curl_errno($ch))
    {   
        $info = curl_getinfo($ch);
        $msg_success='lmsbaba API successfully submitted.';
        $msg_error='';
        $status='success';
    }
    else
    {    
        $errmsg = curl_error($ch);
        $msg_success='';
        $msg_error=$errmsg;
        $status='fail';
    }
    curl_close($ch);

    $response['status']=$status;
    $response['success_msg']=$msg_success;
    $response['error_msg']=$msg_error;
    echo json_encode($response);
    exit;	
}
?&#62; 
</pre>