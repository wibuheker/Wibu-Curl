<?php
require_once 'WibuCurl.php';

$curl = new Curl();
$curl::$URL = 'https://rintod.dev';
$curl::MakeRequests();
// $curl::SetHeaders(array("User-Agent: Mozilla")); // if u want set a headers
// $curl::Follow(); // If U want to follow location
// $curl::Cookies(); // if u want to save your session
// $curl::SetTimeout(5); // if u want set request timeout
$curl::GET(); // $curl::POST('user=user&pass=pass');
echo $curl::Response()->body; // Print body response
echo $curl::Response()->status_code; // Print Status code
print_r($curl::Response()->headers); /// Print all headers (array)
echo $curl::Response()->headers->location; // Print location headers
