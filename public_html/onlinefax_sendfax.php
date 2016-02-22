<?php
function getAccessToken()
{

echo("Begin Request...");
echo('<br /><br />');

$url = 'https://api.onlinefaxes.com/v2/fax/async/sendfax/simplemodel';


$client_id = 'f1e17b5763c4478cad0e99a7e1fffa8a';
$client_secret = 'GEcISEN-hyTOvMwiwF0RyZQbSUeyI9TaFNSYK3UF8Ck';

$ACCESS_TOKEN = getAccessToken($client_id, $client_secret);

$senderName = 'deepak singh';
$senderCompanyName = 'Webicules Technology';
$faxSubject = 'Simple Fax';
$faxNotes = 'Test Simple Fax';
$recipientName = 'OFX';
$recipientFaxNo = '1234567890';

  $fields = array(
  'senderCompanyName' => urlencode($senderName),
  'senderCompanyName' => urlencode($senderCompanyName),
  'faxSubject' => urlencode($faxSubject),
  'faxNotes' => urlencode($faxNotes),
  'recipientName' => urlencode($recipientName),
  'recipientFaxNo' => urlencode($recipientFaxNo)
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

$file1 = realpath('DiagonalSand.jpg');
$file2 = realpath('Test.pdf');

$params  = "--ABC1234"
    . "\r\n"
    . "Content-Disposition:form-data;name=File1;filename=DiagonalSand.jpg"
    . "\r\n"
    . "Content-Type:image/jpeg"
    . "\r\n\r\n"
    . file_get_contents($file1)
    . "\r\n"
    . "--ABC1234"
    . "\r\n"
    . "Content-Disposition:form-data;name=File2;filename=Test.pdf"
    . "\r\n"
    . "Content-Type:application/pdf"
    . "\r\n\r\n"
    . file_get_contents($file2)
    . "\r\n"
    . "--ABC1234--";

$multipart_boundary = "ABC1234"; 

$request_headers    = array();
$request_headers[]  = 'Authorization: ofx '.$ACCESS_TOKEN;
$request_headers[]  = 'Content-Length: ' . strlen($params);
$request_headers[]  = 'Content-Type: multipart/form-data; boundary=' . $multipart_boundary;

// send the request now

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url.$fields_string);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_CAINFO, 'cacert.pem');

$httpres = curl_exec($ch);

var_dump($httpres);
echo('<br /><br />');
var_dump(curl_error($ch));
echo('<br /><br />');
curl_close($ch);

}


echo("Begin Request...");
echo('<br /><br />');

$url = 'https://api.onlinefaxes.com/v2/fax/async/sendfax/simplemodel';


$client_id = 'f1e17b5763c4478cad0e99a7e1fffa8a';
$client_secret = 'GEcISEN-hyTOvMwiwF0RyZQbSUeyI9TaFNSYK3UF8Ck';

echo $ACCESS_TOKEN = getAccessToken($client_id, $client_secret);

$senderName = 'Deepak';
$senderCompanyName = 'webicules technoplogy';
$faxSubject = 'Simple Fax';
$faxNotes = 'Test Simple Fax';
$recipientName = 'OFX';
$recipientFaxNo = '1234567890';

$fields = array(
  'senderCompanyName' => urlencode($senderName),
  'senderCompanyName' => urlencode($senderCompanyName),
  'faxSubject' => urlencode($faxSubject),
  'faxNotes' => urlencode($faxNotes),
  'recipientName' => urlencode($recipientName),
  'recipientFaxNo' => urlencode($recipientFaxNo),
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

$file1 = realpath('DiagonalSand.jpg');
$file2 = realpath('Test.pdf');

$params  = "--ABC1234"
    . "\r\n"
    . "Content-Disposition:form-data;name=File1;filename=DiagonalSand.jpg"
    . "\r\n"
    . "Content-Type:image/jpeg"
    . "\r\n\r\n"
    . file_get_contents($file1)
    . "\r\n"
    . "--ABC1234"
    . "\r\n"
    . "Content-Disposition:form-data;name=File2;filename=Test.pdf"
    . "\r\n"
    . "Content-Type:application/pdf"
    . "\r\n\r\n"
    . file_get_contents($file2)
    . "\r\n"
    . "--ABC1234--";

$multipart_boundary = "ABC1234"; 

$request_headers    = array();
$request_headers[]  = 'Authorization: ofx '.$ACCESS_TOKEN;
$request_headers[]  = 'Content-Length: ' . strlen($params);
$request_headers[]  = 'Content-Type: multipart/form-data; boundary=' . $multipart_boundary;

// send the request now

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url.$fields_string);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_AUTOREFERER, true); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_CAINFO, 'cacert.pem');

$httpres = curl_exec($ch);
echo "<pre>";print_r($httpres);
var_dump($httpres);
echo('<br /><br />');
var_dump(curl_error($ch));
echo('<br /><br />');
curl_close($ch);

?>
<button type="submit" name="submit" onClick="return getAccessToken();">Token</button>