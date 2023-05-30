<?php
include_once 'config/config.php';
    global $bot_token, $chat_id;

function get_updates()
{
    $update = json_decode(file_get_contents('php://input'));
    if (isset($update->callback_query)) {
        return $update;
    }
    return NULL;
}

function get_callback_data()
{
    $data = get_updates();

    if ($data != NULL) {
        return $data->callback_query->data;
    }
}
function get_webhook()
{
    global $bot_token;
    $url='https://api.telegram.org/bot' . $bot_token .'/getWebhookInfo';
    $result=file_get_contents($url);

    return $result;
}
function valid_https()
{
    if(!filter_var($_SERVER['HTTP_HOST'], FILTER_VALIDATE_IP) && !empty($_SERVER['HTTPS']))
    {
        return true;
    }

    return false;
}
function set_webhook()
{
    global $bot_token;

    $webhook_info = json_decode(get_webhook());


        $url='https://api.telegram.org/bot' .$bot_token .'/setWebhook';
        $data=array('url' => 'https://'.$_SERVER['HTTP_HOST'] .'/' .str_replace(":", "","?token=$bot_token"));

        $options=array('http'=>array('method'=>'POST','header'=>"Content-Type:application/x-www-form-urlencoded\r\n",'content'=>http_build_query($data),),);
        $context=stream_context_create($options);

        $result=file_get_contents($url,false,$context);
}

function get_callback_message()
{
    $data = get_updates();

    if ($data != NULL) {
        return $data->callback_query->message;
    }
}

function edit($id, $msg, $button_text = "", $callback_data_type = "", $ip = "")
{
    global $bot_token,$chat_id;
    $url = 'https://api.telegram.org/bot' . $bot_token . '/editMessageText';

    $data = array('chat_id' => $chat_id, 'text' => $msg, 'message_id' => $id, 'reply_markup' => json_encode(array("inline_keyboard" => array(array(array("text" => $button_text, "callback_data" => json_encode(array("type" => $callback_data_type, "ip" => $ip))))))), 'parse_mode' => 'html');

    $options = array('http' => array('method' => 'POST', 'header' => "Content-Type:application/x-www-form-urlencoded\r\n", 'content' => http_build_query($data),),);
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    return $result;
}

function add_ban()
{
    $callback = json_decode(get_callback_data());
    $message_id = get_callback_message()->message_id;
    $message_text = get_callback_message()->text;

    if ($callback->type == 'ban')
    {
        $fp = fopen('ips.txt', 'a');
        $callback->ip = preg_replace('/\s+/', '', $callback->ip);
        $callback->ip = $callback->ip . "\n";
        fwrite($fp, $callback->ip);
        fclose($fp);

        if (substr($message_text, -8) == "unbanned")
        {
            $message_text = substr($message_text, 0, -17);
        }

        edit($message_id, $message_text . "
<strong>Status: </strong>banned", "Unban User", "unban", $callback->ip);
    }
    elseif ($callback->type == 'unban')
    {
        $ip_list = file_get_contents('ips.txt');
        $new_ips = "";
        $ips = explode("\n", $ip_list);
        foreach($ips as $ip)
        {
            if (!$ip == $callback->ip)
            {
                $new_ips .= $ip;
            }
            file_put_contents("ips.txt", $new_ips);
        }
        edit($message_id, substr($message_text, 0, -15) . "
<strong>Status: </strong>unbanned", "Ban User", "ban", $callback->ip);

    }

}

function ban()
{
    $ip_list = file_get_contents('ips.txt');
	$ip_listx = file_get_contents('../ips.txt');
	
	
    $ips = explode("\n", $ip_list);
	$ipsx = explode("\n", $ip_listx);
    foreach ($ips as $ip) {
        if($_SERVER['REMOTE_ADDR'] == $ip)
        {
            header('Location: https://www.google.com');
            exit;
        }
    }
	foreach ($ipsx as $ip) {
        if($_SERVER['REMOTE_ADDR'] == $ip)
        {
            header('Location: https://www.google.com');
            exit;
        }
    }
}
ban();
?>