<?php 
$url = 'https://api.onlinefaxes.com/v2/oauth2/token?';
//use getAuthKeys or
$client_id = 'f1e17b5763c4478cad0e99a7e1fffa8a';
$client_secret = 'GEcISEN-hyTOvMwiwF0RyZQbSUeyI9TaFNSYK3UF8Ck';

function getAccessToken($CLIENT_ID,  $CLIENT_SECRET) {
   // echo("Begin Request..");
   // echo('<br /><br />');

    $url = 'https://api.onlinefaxes.com/v2/oauth2/token?';

    $fields_string = "client_id=".$CLIENT_ID."&client_secret=".$CLIENT_SECRET."&grant_type=client_credentials";

    //url-ify the data for the POST


    //set the url
    $ch = curl_init();

    //echo($url.$fields_string);
    //echo('<br /><br />');

    curl_setopt($ch, CURLOPT_URL, $url.$fields_string);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POST, count($fields_string));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_CAINFO, '/home/anu888/ssl/certs/cmpforyou_com_bb745_b18f5_1474245098_be669cd3269b5e0e7.crt');


    //execute post
    $result = curl_exec($ch);
   // echo($result);
  //  echo('<br /><br />');
    $json = json_decode($result, true);
  //  echo $json['access_token'];
 //   echo $json['expires_in'];

    $httpres = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    var_dump($httpres);
    echo('<br /><br />');
    //var_dump(curl_error($ch));
    echo('<br /><br />');

    //close connection
    curl_close($ch);
    
    $response =  $json['access_token'];
    return $response;
  
}

//use getAuthKeys or
$ACCESS_TOKEN = getAccessToken($client_id, $client_secret);


?>
<?php

echo("Begin Request..");
echo('<br /><br />');

$url = 'https://api.onlinefaxes.com/v2/fax/async/downloadfaxfile?';

$faxId = '208121515121';

$fields = array(
  'faxId' => urlencode($faxId)
 );

$fields_string = "";

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
$fields_string = str_replace('+','%20',$fields_string);
$fields_string = rtrim($fields_string, '&');

//set the url
$ch = curl_init();

echo($url.$fields_string);
echo('<br /><br />');

curl_setopt($ch, CURLOPT_URL, $url.$fields_string);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_CAINFO, '/home/anu888/ssl/certs/cmpforyou_com_bb745_b18f5_1474245098_be669cd3269b5e0e7.crt');


//execute post
$result = curl_exec($ch);
echo($result);
echo('<br /><br />');

$httpres = curl_getinfo($ch, CURLINFO_HTTP_CODE);
var_dump($httpres);
echo('<br /><br />');
var_dump(curl_error($ch));
echo('<br /><br />');

//close connection
curl_close($ch);

?>
