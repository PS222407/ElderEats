<?php

namespace App\Events;

use App\Mail\NewProductAdded;
use App\Models\Account;
use Exception;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AddProductScanned implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;
    public const NUMBER_OF_MINUTES = 0;

    public bool $needsToSendNotification = true;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $ean,
        public string $accountId,
        public bool   $productFound,
        public int    $amount,
    ) {
        $account = Account::with('usersConnected')->find($this->accountId);
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

        if (self::NUMBER_OF_MINUTES <= 0) {
            $this->needsToSendNotification = true;
        }
        if ($this->needsToSendNotification) {
            try {
                foreach ($account->usersConnected as $recipient) {
                    Mail::to($recipient)->send(new NewProductAdded());
                }
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
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
            new Channel('product-scanned-channel-' . $this->accountId),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'add-product';
    }
}
