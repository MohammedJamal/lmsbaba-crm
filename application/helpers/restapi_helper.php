<?php 
function call_restapi($endpoint, $method = 'get', $post_vars = array(), $server = 'restapi/', $format = 'json')
{
    //examples of $endpoint: 'property/property_details/id/6', ''property/property_add'
    //$username = 'admin';
    //$password = '1234';
    //echo '<pre>'.base_url().$server . $endpoint . '/format/' . $format.'</pre>';
    $curl_handle = curl_init();      
    curl_setopt($curl_handle, CURLOPT_URL, base_url().$server . $endpoint . '/format/' . $format);

    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
    if (strtoupper($method) == 'POST') {
        curl_setopt($curl_handle, CURLOPT_POST, 1);
        curl_setopt($curl_handle, CURLOPT_SAFE_UPLOAD, false);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $post_vars);
    }
    
    if (strtoupper($method) == 'PUT') {
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "PUT");
        //curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $post_vars);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS,http_build_query($post_vars));
    }
    //curl_setopt($curl_handle, CURLOPT_USERPWD, $username . ':' . $password);
    $buffer = curl_exec($curl_handle);
    curl_close($curl_handle);
    return (array) json_decode($buffer,true);
}

function get_access_token()
{    
    return md5("LMSBABA");
}
?>