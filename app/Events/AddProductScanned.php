<?php

namespace App\Events;

use App\Models\Account;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Queue\SerializesModels;

class AddProductScanned implements ShouldBroadcast
{
    const NUMBER_OF_MINUTES = 10;

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public bool $needsToSendNotification = true;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $ean,
        public string $accountId,
        public bool $productFound
    ) {
        $account = Account::find($this->accountId);
        $lastSendAt = $account->notification_last_sent_at;
        if ($lastSendAt) {
            $minutes = $lastSendAt->diffInMinutes(now());
            $this->needsToSendNotification = $minutes > self::NUMBER_OF_MINUTES;
        }
        if ($this->needsToSendNotification) {
            $account->update([
                'notification_last_sent_at' => now(),
            ]);
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('product-scanned-channel-'.$this->accountId),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'add-product';
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'account_id' => $this->accountId,
        ]);
    }

}
