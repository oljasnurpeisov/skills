<?php

namespace App\Api\V1\Classes;

class Message
{
    public $message;
    public $code;
    public $content;

    public function __construct($message = "", $code = 200, $content = null)
    {
        $this->message = $message;
        $this->code = $code;
        $this->content = $content;
    }
}
