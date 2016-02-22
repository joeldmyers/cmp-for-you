//Get Access Token

<?php

function getAuthKeys(){
  echo("Begin Request..");
    echo('<br /><br />');

    $url = 'http://www.cmpforyou.com/APIKey.json';

    //url-ify the data for the POST


    //set the url
    $ch = curl_init();

    echo($url);
    echo('<br /><br />');

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    /*if HTTPS
    * curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
    * curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    * curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    * curl_setopt($ch, CURLOPT_CAINFO, 'cacert.pem');
    */


    //execute post
    $result = curl_exec($ch);
    echo($result);
    echo('<br /><br />');
    $json = json_decode($result, true);
    echo $json['client_id'];
    echo $json['client_secret'];

    $httpres = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    var_dump($httpres);
    echo('<br /><br />');
    var_dump(curl_error($ch));
    echo('<br /><br />');

    //close connection
    curl_close($ch);
    
    $client_id =  $json['client_id'];
    $client_secret =  $json['client_secret'];
  
    if ($client_secret != null && $client_secret != "") {
      getAccessToken($client_id,  $client_secret);
      
    } else {
      return null;
    }
 
}

function getAccessToken($CLIENT_ID,  $CLIENT_SECRET) {
    echo("Begin Request..");
    echo('<br /><br />');

    $url = 'https://api.onlinefaxes.com/v2/oauth2/token';

    $fields_string = "client_id=".$CLIENT_ID." &client_secret=".$CLIENT_SECRET."&grant_type=client_credentials";

    //url-ify the data for the POST


    //set the url
    $ch = curl_init();

    echo($url.$fields_string);
    echo('<br /><br />');

    curl_setopt($ch, CURLOPT_URL, $url.$fields_string);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POST, count($fields_string));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_CAINFO, 'cacert.pem');


    //execute post
    $result = curl_exec($ch);
    echo($result);
    echo('<br /><br />');
    $json = json_decode($result, true);
    echo $json['access_token'];
    echo $json['expires_in'];

    $httpres = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    var_dump($httpres);
    echo('<br /><br />');
    var_dump(curl_error($ch));
    echo('<br /><br />');

    //close connection
    curl_close($ch);
    
    $response =  $json['access_token'];
    return $response;
  
}

?>