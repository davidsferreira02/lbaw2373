<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentLike implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $comment_id;

    // Here you create the message to be sent when the event is triggered.
    public function __construct($comment_id) {
        $this->task_id = $comment_id;
        $this->message = 'You like a comment ' . $comment_id;
    }

    // You should specify the name of the channel created in Pusher.
    public function broadcastOn() {
        return 'tutorial02';
    }

    // You should specify the name of the generated notification.
    public function broadcastAs() {
        return 'notification-postlike';
    }
}
