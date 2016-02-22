<?php
include('getaccess.php');
include('apikey.json');

$curl = "C:\xampp\php\cacert.pem.txt";

echo("Begin Request..");
echo('<br /><br />');

$url = 'https://api.onlinefaxes.com/v2/fax/async/getfaxstatus?';
//use getAuthKeys or
$client_id = '';
$client_secret = '';

$accessToken = getAccessToken($client_id, $client_secret);

$faxId = '8444203542';

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
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_CAINFO, 'cacert.pem');
//Here for the access token
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ofx $accessToken'));

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