<?php
ini_set('display_errors', true);
error_reporting(E_ALL);
require_once "config.php";
require_once "database.php";
require_once "inc/key.php";


function bot($method, $params = [])
{
    $url = "https://api.telegram.org/bot" . API_KEY . "/" . $method;
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($params)
    ]);
    $res = curl_exec($curl);
    curl_close($curl);
    if (!curl_error($curl)) return json_decode($res, true);
}

$update = json_decode(file_get_contents("php://input"));


if (isset($update->message)) {
    $message = $update->message;
    $user_id = $message->from->id;
    $username = $message->from->username;
    $mid = $message->message_id;
    $text = $message->text;
}

if (isset($update->callback_query)) {
    $data = $update->callback_query->data;
    $user_id = $update->callback_query->message->chat->id;
    $type = $update->callback_query->message->chat->type;
    $mid = $update->callback_query->message->message_id;
    $first_name = $update->callback_query->from->first_name;
    $last_name = $update->callback_query->from->last_name;
    $fullname = $first_name . " " . $last_name;
    $callback_query = $update->callback_query->id;
}

$db = new DB();
#limit for blocked users
$block_users = $db->query("SELECT user_id FROM blocked_users WHERE user_id = '$user_id'")->fetch_assoc()['user_id'];
if($block_users){
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => '⚠️ Sorry, you are blocked from our bot!',
        ]);
        exit();
}


#admins go to this direction
$get_role = $db->query("SELECT role FROM users where user_id = '$user_id'")->fetch_assoc()['role'];
if ($get_role == 'admin') require_once "inc/admin_panel.php";
        
#check joined on database or not
if ($text == '/start') {
    $if_exists = $db->query("SELECT count(id) as count FROM users WHERE user_id = '$user_id'")->fetch_assoc()['count'];
    
    if ($if_exists == 0) {
        $db->query("INSERT INTO users (username, user_id, role) VALUES ('$username', '$user_id', 'user')");
        bot('sendMessage', [
            'chat_id' => $user_id,
            'text' => 'Now you are in our database',
            'parse_mode' => 'html',
            'reply_markup' => $start
        ]);
        
        bot('sendMessage', [
            'chat_id' => ADMIN_ID,
            'text' => "New user signed in your bot  id: <code> $user_id </code><code> $username </code>",
            'parse_mode' => 'html',
        ]);
    } else {
        bot('sendMessage', [
            'chat_id' => $user_id,
            'text' => 'We already added you',
            'parse_mode' => 'html',
            'reply_markup' => $start,
        ]);
    }
}





