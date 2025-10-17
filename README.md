Botkaplus Library for rubika bots.



# Botkaplus
  <img align="center" width="200" height="200" src="https://rubika.ir/static/images/logo.svg"/>
Botkaplus Library for rubika bots.

# 📦 نصب و راه‌ اندازی

نیازمندی‌ ها

· PHP 7.4 یا بالاتر
· فعال بودن curl
· توکن ربات روبیکا

# نصب

```php
// شامل کردن فایل‌های کتابخانه
composer require sinyor-ehsan/botkaplus
```

# شروع

```php
<?php

require "vendor/autoload.php";
use Botkaplus\BotClient;
use Botkaplus\Filters;
use Botkaplus\Message;

$token = "token_bot";

$bot = new BotClient($token);

$bot->onMessage(null, function(BotClient $bot, Message $message) {
        $message->reply_Message("hello from Botkaplus!");
        $bot->stopPropagation();
    }
);
$bot->runPolling();

?>
```

# شروع با webHook

```php
<?php

require "vendor/autoload.php";
use Botkaplus\BotClient;
use Botkaplus\Filters;
use Botkaplus\Message;

$token = "token_bot";
$inData = file_get_contents('php://input');
$Data = json_decode($inData);

$bot = new BotClient($token, $Data);

$bot->onMessage(Filters::text("hello"), function(BotClient $bot, Message $message) {
        $message->reply_Message("hello from Botkaplus!");
        $bot->stopPropagation();
    }
);
$bot->run();

?>
```

# ارسال اینلاین کیبورد
```php
use Botkaplus\KeypadInline;

$keypad = new KeypadInline();

// ردیف اول
$keypad->addRow([
    KeypadInline::simpleButton("100", "Botkaplus 1")
]);

// ردیف دوم
$keypad->addRow([
    KeypadInline::simpleButton("101", "Botkaplus 2"),
    KeypadInline::simpleButton("101", "Botkaplus 2")
]);

$inline_keypad = $keypad->build();
$message->reply_Message("send inline keypad!", $inline_keypad);
```

# ارسال اینلاین Button
```php
use Botkaplus\KeypadChat;

$chat_keypad = new KeypadChat();

// ردیف اول
$chat_keypad->addRow([
    KeypadChat::simpleButton("100", "Botkaplus 1")
]);

// ردیف دوم
$chat_keypad->addRow([
    KeypadChat::simpleButton("101", "Botkaplus 2"),
    KeypadChat::simpleButton("101", "Botkaplus 3")
]);

$chat_keypad->setResizeKeyboard(true);
$chat_keypad->setOnTimeKeyboard(true);

$chat_keypad = $chat_keypad->build();
$message->reply_Message("send chat keypad!", chat_keypad:$chat_keypad);
```

# ادامه ندادن به هندلرهای بعدی
```php
$bot->stopPropagation()
```

# فیلتر ترکیبی and
```php
$bot->onMessage(Filters::and(Filters::private(), Filters::command("start")), function(BotClient $bot, Message $message){
    $message->reply_Message("hello from Botkaplus to pv!");
});
```
# انواع فیلترها
```php
Filters::text("")
Filters::command("")
Filters::chatId("")
Filters::senderId("")
Filters::buttonId("")
Filters::private()
Filters::group()
Filters::channel()
Filters::or(...)
Filters::and(...)
Filters::not(...)
```
# تنظیم کامندها
```php
$bot->set_Commands([["command" => "start", "description" => "شروع ربات"], ["command" => "help", "description" => "راهنمای ربات"]]);
```
