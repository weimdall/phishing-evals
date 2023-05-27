<?php

// file execute by page load
if (!defined('OK_LOADME')) {
    die("^-^ REGL");
}

$phpver = phpversion();
if (7.2 <= $phpver) {
    $phpversionstr = "<i class='fa fa-fw fa-check text-success'></i> PHP {$phpver}</li>";
} else {
    $phpversionstr = "<i class='fa fa-fw fa-times text-danger'></i> PHP {$phpver}</li>";
    $doregsbtnsubmit = '';
}

$sqlver = find_SQL_Version();
if ($sqlver >= 5) {
    $sqlversionstr = "<i class='fa fa-fw fa-check text-success'></i> SQL {$sqlver}</li>";
} else {
    $sqlversionstr = "<i class='fa fa-fw fa-times text-danger'></i> SQL {$sqlver}</li>";
}

if (extension_loaded('ionCube Loader')) {
    $ioncubestr = "<i class='fa fa-fw fa-check text-success'></i> IonCube loader</li>";
} else {
    $ioncubestr = "<i class='fa fa-fw fa-times text-warning'></i> IonCube loader</li>";
    $doregsbtnsubmit = '';
}

if (function_exists('curl_init')) {
    $curlstr = "<i class='fa fa-fw fa-check text-success'></i> cUrl function</li>";
} else {
    $curlstr = "<i class='fa fa-fw fa-times text-danger'></i> cUrl function</li>";
    $doregsbtnsubmit = '';
}

if (function_exists('fsockopen')) {
    $fsockopenstr = "<i class='fa fa-fw fa-check text-success'></i> fSockOpen function</li>";
} else {
    $fsockopenstr = "<i class='fa fa-fw fa-times text-warning'></i> fSockOpen function</li>";
}

if (get_extension_funcs('json')) {
    $jsonstr = "<i class='fa fa-fw fa-check text-success'></i> JSON extension</li>";
} else {
    $jsonstr = "<i class='fa fa-fw fa-times text-danger'></i> JSON extension</li>";
    $doregsbtnsubmit = '';
}

if (get_extension_funcs('zlib')) {
    $zlibstr = "<i class='fa fa-fw fa-check text-success'></i> zLib extension</li>";
} else {
    $zlibstr = "<i class='fa fa-fw fa-times text-danger'></i> zLib extension</li>";
    $doregsbtnsubmit = '';
}

if (function_exists('gzencode')) {
    $gzencodestr = "<i class='fa fa-fw fa-check text-success'></i> gzEncode function</li>";
} else {
    $gzencodestr = "<i class='fa fa-fw fa-times text-warning'></i> gzEncode function</li>";
    $doregsbtnsubmit = '';
}

$showreg_server = <<<INI_HTML
        <ul class="list-unstyled">
            <li>{$phpversionstr}</li>
            <li>{$sqlversionstr}</li>
            <li>{$ioncubestr}</li>
            <li>{$curlstr}</li>
            <li>{$fsockopenstr}</li>
            <li>{$jsonstr}</li>
            <li>{$zlibstr}</li>
            <li>{$gzencodestr}</li>
        </ul>
INI_HTML;
