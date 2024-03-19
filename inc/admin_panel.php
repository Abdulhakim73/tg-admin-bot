<?php
$step = file_get_contents("inc/$user_id.txt") ?? null;
$act_pass = file_get_contents("inc/channel.txt") ?? null;

////////////----------PANEL----------//////////
if ($text == '/panel') {
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "👋 Main page. \n🆔 Admin: <code>$user_id</code> ",
        'parse_mode' => 'html',
        'reply_markup' => $menu,
    ]);
}

if ($text == "/migrate") {
    $db->query("CREATE TABLE IF NOT EXISTS blocked_users(
        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(32) NULL DEFAULT NULL,
        username VARCHAR(32) NOT NULL,
        role VARCHAR(32) NOT NULL
    )");

    $db->query("CREATE TABLE IF NOT EXISTS channels(
        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        channel_id VARCHAR(32) NULL DEFAULT NULL,
        title VARCHAR(32) NOT NULL,
        link VARCHAR(32) NOT NULL
    )");

    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "Migration successful",
        'parse_mode' => 'html',
    ]);
}


////////////----------USERS-PAGE----------/////////////////////////////////////////////////////////////
if ($text == "👤Users") {
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "👥 Users management section. \n🧑‍💻Admin: <code>$user_id</code>",
        'parse_mode' => 'html',
        'reply_markup' => $user_panel,
    ]);
}


////////////----------BLOCK----------//////////
if ($text == "🔴Block") {
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "🆔 Send me user's id",
        'parse_mode' => 'html',
        'reply_markup' => $back_from_block
    ]);
    file_put_contents("inc/{$user_id}.txt", 'dev.block');
    exit();
}

if ($step == "dev.block" and $text == "🟡Back") {
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "👥 Users management section. \n🧑‍💻Admin: <code>$user_id</code>",
        'parse_mode' => 'html',
        'reply_markup' => $user_panel,
    ]);

    if (file_exists("inc/$user_id.txt")) unlink("inc/$user_id.txt");
    exit();
}

if ($step == "dev.block" and isset($text)) {
    $get_user = $db->query("SELECT * FROM users WHERE user_id = '$text'")->fetch_assoc();

    if ($get_user != null) {
        $bl_user_id = $get_user['user_id'];
        $username = $get_user['username'];
        $role = $get_user['role'];
        $block_user = $db->query("INSERT INTO blocked_users (user_id, username, role) VALUES('$bl_user_id', '$username', '$role')");
        $db->query("DELETE FROM users WHERE user_id = '$bl_user_id'");

        // Sending message to blocking user
        bot('sendMessage', [
            'chat_id' => $bl_user_id,
            'text' => "🔴 You have been blocked by admin from our bot for some reason.",
            'parse_mode' => 'html',
        ]);

        // Sending message to admin
        bot('sendMessage', [
            'chat_id' => $user_id,
            'text' => "🔴 This user blocked successfully \n 🆔 <code>$bl_user_id</code>",
            'parse_mode' => 'html',
            'reply_markup' => $user_panel,
        ]);

        if (file_exists("inc/$user_id.txt")) unlink("inc/$user_id.txt");
        exit();
    } else {
        bot('sendMessage', [
            'chat_id' => $user_id,
            'text' => '❌ This user does not exist in the database. Please check and resend.',
            'parse_mode' => 'html',
            'reply_markup' => $back_from_block,
        ]);
    }
}




////////////----------UNBLOCK----------//////////
if ($text == '🟢Unblock') {
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "🆔 Send me user's id",
        'parse_mode' => 'html',
        'reply_markup' => $back_from_block
    ]);
    file_put_contents("inc/{$user_id}.txt", 'dev.unblock');
    exit();
}
if ($step == 'dev.unblock' and $text == "🟡Back") {
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "👥 Users management section. \n🧑‍💻Admin: <code>$user_id</code>",
        'parse_mode' => 'html',
        'reply_markup' => $user_panel,
    ]);
    if (file_exists("inc/$user_id.txt")) unlink("inc/$user_id.txt");
    exit();
}

if ($step == 'dev.unblock' and isset($text)) {
    $get_user = $db->query("SELECT * FROM blocked_users WHERE user_id = '$text'")->fetch_assoc();

    if ($get_user != null) {
        $bl_user_id = $get_user['user_id'];
        $username = $get_user['username'];
        $role = $get_user['role'];
        $db->query("INSERT INTO users (user_id, username, role) VALUES('$bl_user_id', '$username', '$role')");
        $db->query("DELETE FROM blocked_users WHERE user_id = '$bl_user_id'");

        #sendingMessage to user about unblocked
        bot('sendMessage', [
            'chat_id' => $user_id,
            'text' => " 🟢 This user unblocked successfully \n 🆔 <code>$bl_user_id</code> ",
            'parse_mode' => 'html',
            'reply_markup' => $user_panel,
        ]);

        bot('sendMessage', [
            'chat_id' => $bl_user_id,
            'text' => "🟢 You have been unblocked by admin!",
            'parse_mode' => 'html',
        ]);
        if (file_exists("inc/$user_id.txt")) unlink("inc/$user_id.txt");
        exit();
    } else {
        bot('sendMessage', [
            'chat_id' => $user_id,
            'text' => '❌ This user does not exist in the database. Please check and resend.1',
            'parse_mode' => 'html',
            'reply_markup' => $back_from_block,
        ]);
    }
}


////////////----------SEND-MESSAGE----------//////////
////if ($text == '✍️Send message') {
////    bot('sendMessage', [
////        'chat_id' => $user_id,
////        'text' => "✍️Send your message",
////        'parse_mode' => 'html',
////        'reply_markup' => $back_from_block,
////    ]);
////}
////
////if ($text == "🟡Back") {
////    bot('sendMessage', [
////        'chat_id' => $user_id,
////        'text' => "👥 Users management section. \n🧑‍💻Admin: <code>$user_id</code>",
////        'parse_mode' => 'html',
////        'reply_markup' => $user_panel,
////    ]);
////}
//
////////////----------FORWARD-MESSAGE----------//////////
////if ($text == '📬Forward message') {
////    bot('sendMessage', [
////        'chat_id' => $user_id,
////        'text' => "✍️Send your message",
////        'parse_mode' => 'html',
////        'reply_markup' => $back_from_block,
////    ]);
////}
////if ($text == "🟡Back") {
////    bot('sendMessage', [
////        'chat_id' => $user_id,
////        'text' => "👥 Users management section. \n🧑‍💻Admin: <code>$user_id</code>",
////        'parse_mode' => 'html',
////        'reply_markup' => $user_panel,
////    ]);
////}


////////////----------STATISTIC----------//////////
if ($text == '📈Statistics') {
    $table_1 = $db->query("SELECT count(*) as count FROM users")->fetch_assoc()['count'];
    $table_2 = $db->query("SELECT count(*) as count FROM blocked_users")->fetch_assoc()['count'];
    $all_users = $table_1 + $table_2;
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "📊Statistics of bot \n👥All users $all_users",
        'parse_mode' => 'html',
    ]);
}


////////////----------ADMIN-PAGE----------/////////////////////////////////////////////////////////////
if ($text == '🧑🏼‍💼Admins') {
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "👥 Admin management section. \n🧑‍💻Admin: <code>$user_id</code>",
        'parse_mode' => 'html',
        'reply_markup' => $admins,
    ]);
}
////////////----------ADD-ADMIN----------//////////
if ($text == "👨‍💼Add admin") {
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "🆔 Send me user's id",
        'parse_mode' => 'html',
        'reply_markup' => $back_from_block
    ]);
    file_put_contents("inc/{$user_id}.txt", "add.admin");
}
if ($step == "add.admin" and $text == "🟡Back") {
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "👥 Admin management section. \n🧑‍💻Admin: <code>$user_id</code>",
        'parse_mode' => 'html',
        'reply_markup' => $admins,
    ]);
    if (file_exists("inc/$user_id.txt")) unlink("inc/$user_id.txt");
    exit();
}
//swtich user to admin
if ($step == "add.admin" and isset($text)) {
    $get_user = $db->query("SELECT * FROM users WHERE user_id = '$text'")->fetch_assoc();
    if ($get_user != null) {
        $id = $get_user['user_id'];
        $role = $get_user['role'];
        if ($role == 'admin') {
            bot('sendMessage', [
                'chat_id' => $user_id,
                'text' => "This user already admin",
                'parse_mode' => 'html',
                'reply_markup' => $back_from_block
            ]);
        }
        if ($role == 'user') {
            $db->query("UPDATE users SET role = 'admin' WHERE user_id = '$id'");
            bot('sendMessage', [
                'chat_id' => $user_id,
                'text' => "This user <code>$id</code> switched to <code>admin</code> role",
                'parse_mode' => 'html',
                'reply_markup' => $admins
            ]);
            bot('sendMessage', [
                'chat_id' => $id,
                'text' => "Now you are in role <code>admin</code>",
                'parse_mode' => 'html',
                'reply_markup' => $admins
            ]);
            if (file_exists("inc/$user_id.txt")) unlink("inc/$user_id.txt");
            exit();
        }
        exit();
    } else {
        bot('sendMessage', [
            'chat_id' => $user_id,
            'text' => '❌ This user does not exist in the database. Please check and resend.',
            'parse_mode' => 'html',
            'reply_markup' => $back_from_block,
        ]);
    }
}

////////////----------KICK-ADMIN---------//////////
if ($text == "🚶Kick from admin position") {
    $admins = $db->query("SELECT user_id FROM users WHERE role = 'admin'");
    $t = 1;
    while ($row = mysqli_fetch_assoc($admins)) {
        $chn .= $t . ". " . $row['user_id'] . "\n";
        $chnk[] = ['text' => "$t", 'callback_data' => "kick_admin#{$row["user_id"]}"];
        $t++;
    }
    $chnk = array_chunk($chnk, 4);

    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "🗑Kick admin from this role\n$chn",
        'parse_mode' => 'html',
        'reply_markup' => json_encode([
            'inline_keyboard' => $chnk
        ])
    ]);
}
#action after clicking
if (mb_stripos($data, "kick_admin#") !== false) {
    $u_id = explode('#', $data)[1];
    $do_admin = $db->query("UPDATE users SET role = 'user' WHERE user_id = '$u_id'");

    $admins = $db->query("SELECT user_id FROM users WHERE role = 'admin'");
    $t = 1;
    while ($row = mysqli_fetch_assoc($admins)) {
        $chn .= $t . ". " . $row["user_id"] . "\n";
        $chnk[] = ['text' => "$t", 'callback_data' => "kick_admin#{$row["user_id"]}"];
        $t++;
    }
    $chnk = array_chunk($chnk, 4);

    bot('editMessageText', [
        'chat_id' => $user_id,
        'message_id' => $mid,
        'text' => "🗑Kick admin from this role\n$chn",
        'reply_markup' => json_encode([
            'inline_keyboard' => $chnk
        ])
    ]);
}


////////////----------CHANNEL----------/////////////////////////////////////////////////////////////
if ($text == '💬Channels') {
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "👥Channels section. \n🧑‍💻Admin: <code>$user_id</code>",
        'parse_mode' => 'html',
        'reply_markup' => $channels,
    ]);
}

////////////--------CONNECT-CHANNEL--------////////////
if ($text == "🔷Connect channel") {
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "✏ Send link of chanel.
         E.g. (username): @steps_to_learn_bot1
         E.g. (id): -100123451424",
        'parse_mode' => 'html',
        'disable_web_page_preview' => true,
        'reply_markup' => $back_from_channel,
    ]);
    file_put_contents("inc/{$user_id}.txt", "add_channel");
    exit();
}
#back
if ($step == 'add_channel' and $text == '🔵Back') {
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "👥Channels section. \n🧑‍💻Admin: <code>$user_id</code>",
        'parse_mode' => 'html',
        'reply_markup' => $channels,
    ]);
    if (file_exists("inc/$user_id.txt")) unlink("inc/$user_id.txt");
    exit();
}


if ($step == "add_channel" and mb_stripos($text, "https://t.me/") !== false) {
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "✏️ Yaxshi endi kanal idsini yuboring.
Telegram kanal uchun idni -10012441311 kabi yuboring. Url telegram kanal bo'lmasa unda 'no' so'zini yuboring.",
        'parse_mode' => 'html',
        'disable_web_page_preview' => true,
        'reply_markup' => $back_from_channel,
    ]);
    file_put_contents("inc/{$user_id}.txt", "add_channel#");
    exit();

    // if no
    $link = explode("#", $step)[1];
    if ($text == "no" and $step == $link) {
        $db->query("INSERT INTO channels (link) VALUES ('$link')");
        bot('sendMessage', [
            'chat_id' => $user_id,
            'text' => "✅ Kanal qo'shildi. 
📌 URL: $link
Eslatma! Ushbu botda majburiy a'zolik tekshirilmaydi. Ushbu url biror sayt yoki telegram bot bo'lishi mumkin.",
            'parse_mode' => 'html',
            'disable_web_page_preview' => true,
            'reply_markup' => $channels,
        ]);
        if (file_exists("inc/$user_id.txt")) unlink("inc/$user_id.txt");
        exit();
    }



    if (mb_stripos($step, "add_channel") and mb_stripos($text, "@") !== false or mb_stripos($step, "add_channel") and is_numeric($text) !== false) {
        $result = bot('getChat', [
            'chat_id' => $text,
        ]);
        $chan_id = trim(json_encode($result['result']['id']), '"');
        if ($chan_id != 'null') {
            $data_id = $db->query("SELECT channel_id FROM channels WHERE channel_id = '$chan_id'")->fetch_assoc()['channel_id'];
            if ($chan_id != $data_id) {
                //save channel
                $chan_title = trim(json_encode($result['result']['title']), '"');
                $chan_name = trim(json_encode($result['result']['username']), '"');
                $db->query("INSERT INTO channels (channel_id, title, link) VALUES ('$chan_id', '$chan_title', 'https://t.me/$chan_name')");

                bot('sendMessage', [
                    'chat_id' => $user_id,
                    'text' => "✅ Kanal qo'shildi.
🔰 Kanal: $chan_title
📌 URL: https://t.me/$chan_name
Eslatma! Bot kanalda admin emas, botni kanalga admin qilishingiz
shart chunki majburiy a'zolikda muommo kelib chiqishi mumkin.",
                    'parse_mode' => 'html',
                    'disable_web_page_preview' => true,
                    'reply_markup' => $channels,
                ]);

                if (file_exists("inc/$user_id.txt")) unlink("inc/$user_id.txt");
                exit();
            } else {
                bot('sendMessage', [
                    'chat_id' => $user_id,
                    'text' => "❌ Kanal allaqachon mavjud.",
                    'parse_mode' => 'html',
                    'reply_markup' => $back_from_channel,
                ]);
            }
            exit();
        } else {
            bot('sendMessage', [
                'chat_id' => $user_id,
                'text' => "❌ Ma'lumot noto'g'ri formatda yuborildi. Tekshirib qaytadan yuboring.
M-n(username): @steps_to_learn_bot1
M-n(id): -100123451424",
                'parse_mode' => 'html',
                'disable_web_page_preview' => true,
                'reply_markup' => $back_from_channel,
            ]);
        }
    }
}


////////////----------DISCONNECT-CHANNEL--------//////////
if ($text == '🔶Channel disconnection') {

    $links = $db->query("SELECT link FROM channels");
    $t = 1;
    while ($row1 = mysqli_fetch_assoc($links)) {
        $link .= $t . ". " . $row['link'] . "\n";
        $button_link[] = ['text' => "$t", ' callback_data' => "disc_channel#{$row["link"]}"];
        $t++;
    }
    $count_link = array_chunk($button_link, 4);

    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "🗑 Which channel do you wanna delete?\n $link",
        'disable_web_page_preview' => true,
        'parse_mode' => 'html',
        'reply_markup' => json_encode([
            'inline_keyboard' => $count_link
        ])
    ]);
}

if (mb_stripos($data, 'disc_channel#') !== false) {
    $find_chan = explode('#', $data)[1];
    $db->query("DELETE FROM channel WHERE link = '$find_chan'");

    $links = $db->query("SELECT link FROM channels");
    $t = 1;
    while ($row = mysqli_fetch_assoc($links)) {
        $link .= $t . ". " . $row['link'] . "\n";
        $button_link[] = ['text' => "$t", ' callback_data' => "disc_channel#{$row["link"]}"];
        $t++;
    }
    $button_link = array_chunk($button_link, 4);

    bot('editMessageText', [
        'chat_id' => $user_id,
        'message_id' => $mid,
        'text' => "🗑 Which channel do you wanna delete?\n$link",
        'reply_markup' => json_encode([
            'inline_keyboard' => $button_link
        ])
    ]);
}


///////////------------MANDATORY-MEMBERSHIP---------/////////////
if ($act_pass == "active" and $text == "✅Mandatory membership") {
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "💡 Mandatory membership: passive",
        'parse_mode' => 'html',
    ]);
    if (file_exists("step/channel.txt")) unlink("step/channel.txt");
    exit();
}

if ($text == '✅Mandatory membership') {
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "💡 Mandatory membership: active",
        'parse_mode' => 'html',
    ]);
    file_put_contents("step/channel.txt", 'active');
    exit();
}

////////////----------BACK----------//////////
if ($text == '⬅️Back') {
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "👋 Main page . \n🆔 Admin: <code > $user_id</code > ",
        'parse_mode' => 'html',
        'reply_markup' => $menu,
    ]);
}


if ($text == '🚪Quite from panel') {
    bot('sendMessage', [
        'chat_id' => $user_id,
        'text' => "👋 Main panel closed . \n🆔 Admin: <code > $user_id</code > ",
        'parse_mode' => 'html',
        'reply_markup' => $start,
    ]);
}
