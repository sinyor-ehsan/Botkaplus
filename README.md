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
$keypad = new KeypadInline();

    // ردیف اول
$keypad->addRow([
  KeypadInline::simpleButton("100", "test")
]);

    // ردیف دوم
$keypad->addRow([
  KeypadInline::simpleButton("101", "test 2"),
  KeypadInline::simpleButton("101", "test 3")
]);

$inline_keypad = $keypad->build();
```
