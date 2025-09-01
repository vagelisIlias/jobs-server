<?php

declare(strict_types=1);

namespace App\Events\Api\UserRegistered;

use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserRegisteredEventMessage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public const MESSAGE = 'User registered successfully';

    public function __construct(
        public User $user,
        public string $message = self::MESSAGE
    ) {
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
