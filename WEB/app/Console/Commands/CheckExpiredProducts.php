<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductDetail;
use App\Models\User;
use App\Notifications\ExpiredSoonNotification;
use Carbon\Carbon;

class CheckExpiredProducts extends Command
{
    protected $signature = 'expired:check';
    protected $description = 'Cek produk yang akan kedaluwarsa dalam 10 hari dan kirim notifikasi';

    public function handle()
    {
        $today = Carbon::today();
        $threshold = $today->copy()->addDays(10);

        $soonToExpireProducts = ProductDetail::join('products', 'products.product_id', '=', 'product_details.product_id')->join('refill_stocks', 'refill_stocks.product_detail_id', '=', 'product_details.product_detail_id')
            ->whereBetween('refill_stocks.expired_date', [$today, $threshold])
            ->select('products.product_name', 'product_details.stock', 'refill_stocks.expired_date')
            ->get();

        if ($soonToExpireProducts->isEmpty()) {
            $this->info('Tidak ada produk yang mendekati tanggal kedaluwarsa.');
            return;
        }

        $users = User::all();

        foreach ($soonToExpireProducts as $product) {
            foreach ($users as $user) {
                $user->notify(new ExpiredSoonNotification($product));
            }
        }

        $this->info('Notifikasi produk yang mendekati kedaluwarsa telah dikirim.');
    }
}
