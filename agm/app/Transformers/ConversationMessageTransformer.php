<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class ConversationMessageTransformer extends TransformerAbstract
{
    public function transform($message)
    {
        return [
            'id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'sender_id' => $message->sender_id,
            'body' => $message->body,
            'type' => $message->type,
            'is_mine' => $message->is_mine,
            'created_at' => $message->created_at ? $message->created_at->timestamp : null,
            'updated_at' => $message->updated_at ? $message->updated_at->timestamp : null,
            'sender' => $message->sender
        ];
    }

    public function transforms($items)
    {
        $list = [];

        foreach ($items as $item) {
            array_push($list, $this->transform($item));
        }
        return $list;
    }
}
