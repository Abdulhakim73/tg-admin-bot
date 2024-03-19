<?php
$start = json_encode([
    'resize_keyboard' => true,
    'keyboard' => [
        [
            ['text' => '/start']
        ]
    ]
]);

$menu = json_encode([
    'resize_keyboard' => true,
    'keyboard' => [
        [
            ['text' => '👤Users'],
            ['text' => '🧑🏼‍💼Admins'],
        ],
        [
            ['text' => '💬Channels'],
            ['text' => '📘Setting']
        ],
        [
            ['text' => '🚪Quite from panel'],
        ]
    ]
]);

$user_panel = json_encode([
    'resize_keyboard' => true,
    'keyboard' => [
        [
            ['text' => '🔴Block'],
            ['text' => '🟢Unblock'],
        ],
        [
            ['text' => '✍️Send message'],
            ['text' => '📬Forward message'],
        ],
        [
            ['text' => '📈Statistics']
        ],
        [
            ['text' => '⬅️Back']
        ]
    ]
]);

$back_from_block = json_encode([
    'resize_keyboard' => true,
    'keyboard' => [
        [
            ['text' => '🟡Back']
        ]
    ]
]);



$admins = json_encode([
    'resize_keyboard' => true,
    'keyboard' => [
        [
            ['text' => '👨‍💼Add admin'],
            ['text' => '🚶Kick from admin position']
        ],
        [
            ['text' => '⬅️Back']
        ]
    ]
]);

$channels = json_encode([
    'resize_keyboard' => true,
    'keyboard' => [
        [
            ['text' => "🔷Connect channel"],
            ['text' => "🔶Channel disconnection"],
        ],
        [
            ['text' => "✅Mandatory membership"],
        ],
        [
            ['text' => "⬅️Back"]
        ]
    ]
]);


$back_from_channel = json_encode([
    'resize_keyboard' => true,
    'keyboard' => [
        [
            ['text' => '🔵Back']
        ]
    ]
]);


