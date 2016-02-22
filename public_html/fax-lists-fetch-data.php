<?php
session_start(); 
//Get Fax List Test
//echo("Begin Request..");
//echo('<br /><br />');


//use getAuthKeys or
$client_id = 'f1e17b5763c4478cad0e99a7e1fffa8a';
$client_secret = 'GEcISEN-hyTOvMwiwF0RyZQbSUeyI9TaFNSYK3UF8Ck';

$ACCESS_TOKEN = getAccessToken($client_id, $client_secret);


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
    //var_dump($httpres);
    //echo('<br /><br />');
    //var_dump(curl_error($ch));
   // echo('<br /><br />');

    //close connection
    curl_close($ch);
    
    $response =  $json['access_token'];
    return $response;
  
}



$url = 'https://api.onlinefaxes.com/v2/fax/async/getfaxlist?';
if(isset($_GET['id'])){
$folderId = $_GET['id'];
} else {
$folderId = '1003';
}
$isdownloaded = 'true';

$fields = array(
  'folderId' => urlencode($folderId),
  'isdownloaded' => urlencode($isdownloaded)
 );

$fields_string = "";

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
$fields_string = str_replace('+','%20',$fields_string);
$fields_string = rtrim($fields_string, '&');

//set the url
$ch = curl_init();

//echo($url.$fields_string);
//echo('<br /><br />');

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
curl_setopt($ch, CURLOPT_CAINFO, '/home/anu888/ssl/certs/cacert.pem');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ofx '.$ACCESS_TOKEN));

//execute post
$result = curl_exec($ch);
$result =json_decode($result);

//echo '<pre>';
//print_r($result);
//echo('<br /><br />');

$httpres = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//var_dump($httpres);
//echo('<br /><br />');
//var_dump(curl_error($ch));
//echo('<br /><br />');

//close connection
curl_close($ch);

?>


<?php
//Delete Fax Test
if(isset($_GET['faxid'])){
$url = 'https://api.onlinefaxes.com/v2/fax/async/deletefax?';
$faxId = $_GET['faxid'];
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

//echo($url.$fields_string);
//echo('<br /><br />');

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
curl_setopt($ch, CURLOPT_CAINFO, '/home/anu888/ssl/certs/cacert.pem');
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ofx '.$ACCESS_TOKEN));

//execute post
$result = curl_exec($ch);
//echo($result);
//echo('<br /><br />');

$httpres = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//var_dump($httpres);
//echo('<br /><br />');
//var_dump(curl_error($ch));
//echo('<br /><br />');

//close connection
curl_close($ch);
echo "<script>location.href = 'fax-list.php'</script>";
}
?>

<?php
if(isset($_GET['dfaxid'])){
//Download Fax Test

echo("Begin Request..");
echo('<br /><br />');

$url = 'https://api.onlinefaxes.com/v2/fax/async/downloadfaxfile?';

$faxId = $_GET['dfaxid'];

$fields = array(
  'faxId' => urlencode($faxId)
 );

$fields_string = "";

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
$fields_string = str_replace('+','%20',$fields_string);
$fields_string = rtrim($fields_string, '&');

$file1 = realpath('Test.pdf');
$params  =  "--ABC1234"
    . "\r\n"
    . "Content-Disposition:form-data;name=File1;filename=Test.pdf"
    . "\r\n"
    . "Content-Type:image/pdf"
    . "\r\n\r\n"
    . file_get_contents($file1)
    . "\r\n";

$params  .= "--ABC1234--";


$request_headers    = array();
$request_headers[]  = 'Authorization: ofx '.$ACCESS_TOKEN;
$request_headers[]  = 'accept-encoding: gzip';
$request_headers[]  = 'Content-Type: application/x-www-form-urlencoded';
$request_headers[]  = 'Content-Length: ' . strlen($params);

//set the url
$ch = curl_init();

echo($url.$fields_string);
echo('<br /><br />');

curl_setopt($ch, CURLOPT_URL, $url.$fields_string);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_CAINFO, '/home/anu888/ssl/certs/cacert.pem');


//execute post
$result = curl_exec($ch);
echo($result);
echo('<br /><br />');

$httpres = curl_getinfo($ch, CURLINFO_HTTP_CODE);
var_dump($httpres);
echo('<br /><br />');
//var_dump(curl_error($ch));
//echo('<br /><br />');

//close connection
curl_close($ch);
}
?>

