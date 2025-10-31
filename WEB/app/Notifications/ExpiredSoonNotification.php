<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ExpiredSoonNotification extends Notification
{
    use Queueable;

    public $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Produk Akan Kedaluwarsa',
            'message' => "Produk \"{$this->product->product_name}\" dengan satuan \"{$this->product->unit_name}\" akan kedaluwarsa pada " . Carbon::parse($this->product->expired_date)->translatedFormat('l, d/F/Y'),
        ];
    }
}
