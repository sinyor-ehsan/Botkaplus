<?php
namespace Filters;

class Filters {
    
    public static function text($expectedText = null) {
        return new class($expectedText) {
            private $expectedText;

            public function __construct($expectedText) {
                $this->expectedText = $expectedText;
            }

            public function match($message) {
                if (!isset($message->new_message?->text)) return false;

                return $this->expectedText === null || $this->expectedText === ''
                    || trim($message->new_message->text) === $this->expectedText;
            }
        };
    }

    public static function command($expectedCommand = null) {
        return new class($expectedCommand) {
            private $expectedCommand;

            public function __construct($expectedCommand) {
                $this->expectedCommand = $expectedCommand;
            }

            public function match($message) {
                // if (!isset($message->new_message->text)) return f
                if (!isset($message->new_message) || !isset($message->new_message->text)) return false;

                $text = trim($message->new_message->text);

                // اگر پیام با / شروع نمی‌شه، کامند نیست
                if (strpos($text, '/') !== 0) return false;

                // اگر هیچ کامندی مشخص نشده، هر کامندی رو قبول کن
                if ($this->expectedCommand === null || $this->expectedCommand === '') {
                    return true;
                }

                // فقط کامند دقیق رو قبول کن (مثلاً /start)
                return $text === '/' . ltrim($this->expectedCommand, '/');
            }
        };
    }

    public static function buttonId($expectedId = null) {
        return new class($expectedId) {
            private $expectedId;

            public function __construct($expectedId) {
                $this->expectedId = $expectedId;
            }

            public function match($message) {
                $buttonId = $message->aux_data->button_id ?? null;

                return $this->expectedId === null || $this->expectedId === ''
                    || $buttonId === $this->expectedId;
            }
        };
    }

    public static function chatId(array $allowed_ids): object {
        return new class($allowed_ids) {
            private array $allowed_ids;

            public function __construct(array $allowed_ids) {
                $this->allowed_ids = $allowed_ids;
            }

            public function match($message): bool {
                return isset($message->chat_id) && in_array($message->chat_id, $this->allowed_ids);
            }
        };
    }

    public static function senderId(array $allowed_ids): object {
        return new class($allowed_ids) {
            private array $allowed_ids;

            public function __construct(array $allowed_ids) {
                $this->allowed_ids = $allowed_ids;
            }

            public function match($message): bool {
                return isset($message->new_message) &&
                    isset($message->new_message->sender_id) &&
                    in_array($message->new_message->sender_id, $this->allowed_ids);
            }
        };
    }

    public static function private(): object {
        return new class {
            public function match($message): bool {
                return isset($message->chat_id) && str_starts_with($message->chat_id, "b0");
            }
        };
    }

    public static function group(): object {
        return new class {
            public function match($message): bool {
                return isset($message->chat_id) && str_starts_with($message->chat_id, "g0");
            }
        };
    }

    public static function channel(): object {
        return new class {
            public function match($message): bool {
                return isset($message->chat_id) && str_starts_with($message->chat_id, "c0");
            }
        };
    }

    // فیلتر ترکیبی and. همه فیلتر ها برقرار باشن
    public static function and(...$filters) {
        return new class($filters) {
            private $filters;

            public function __construct($filters) {
                $this->filters = $filters;
            }

            public function match($message) {
                foreach ($this->filters as $filter) {
                    if (!$filter->match($message)) {
                        return false;
                    }
                }
                return true;
            }
        };
    }

    // فیلتر ترکیبی or. یک یا چند فیلتر برقرار باشد.
    public static function or(...$filters) {
        return new class($filters) {
            private $filters;

            public function __construct($filters) {
                $this->filters = $filters;
            }

            public function match($message) {
                foreach ($this->filters as $filter) {
                    if ($filter->match($message)) {
                        return true;
                    }
                }
                return false;
            }
        };
    }

    //فیلتر ترکیبی not. اگر فیلتر های دیگر اجرا نشود این اجرا میکند.
    // $bot->onMessage(Filters::not(Filters::command()), function($bot, Message $message) {
    //     $message->replyMessage("این پیام کامند نبود ✅");
    //     });
    public static function not($filter) {
        return new class($filter) {
            private $filter;

            public function __construct($filter) {
                $this->filter = $filter;
            }

            public function match($message) {
                return !$this->filter->match($message);
            }
        };
    }

}