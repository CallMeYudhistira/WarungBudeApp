<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\RefillStock;
use App\Models\User;
use App\Notifications\ProductExpiredNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateExpiredProducts extends Command
{
    protected $signature = 'products:check-expired';
    protected $description = 'Cek produk yang expired hari ini, ubah status ke pending dan kirim notifikasi';

    public function handle()
    {
        $today = Carbon::today();
        $expiredProducts = ProductDetail::join('products', 'products.product_id', '=', 'product_details.product_id')->join('refill_stocks', 'refill_stocks.product_detail_id', '=', 'product_details.product_detail_id')->join('units', 'units.unit_id', '=', 'product_details.unit_id')
            ->whereDate('refill_stocks.expired_date', '<=', $today)
            ->where('status', '=', 'baik')
            ->select('refill_stocks.refill_stock_id', 'products.product_name', 'product_details.stock', 'refill_stocks.expired_date', 'refill_stocks.status', 'units.unit_name')
            ->get();

        if ($expiredProducts->isEmpty()) {
            $this->info('Tidak ada produk yang expired hari ini.');
            return;
        }

        foreach ($expiredProducts as $product) {
            RefillStock::where('refill_stock_id', $product->refill_stock_id)->update([
                'status' => 'pending',
            ]);

            $users = User::all();
            foreach ($users as $user) {
                $user->notify(new ProductExpiredNotification($product));
            }
        }

        $this->info('Produk expired telah diperbarui dan notifikasi dikirim.');
    }
}
