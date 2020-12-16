<?php

namespace App\Api\V1\Transformers;

use App\Api\V1\Classes\Message;
use League\Fractal\TransformerAbstract;


class MessageTransformer extends TransformerAbstract
{
    public function transform(Message $message)
    {
        return [
            "statusCode" => $message->code,
            "message" => $message->message,
            "content" => $message->content,
        ];
    }
}
