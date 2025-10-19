<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\DB;

class CheckLowStock extends Command
{
    protected $signature = 'stock:check';
    protected $description = 'Cek produk yang stoknya di bawah 10 dan kirim notifikasi';

    public function handle()
    {
        DB::statement("TRUNCATE TABLE notifications");

        $lowStockProducts = Product::join('product_details', 'product_details.product_id', '=', 'products.product_id')->where('product_details.stock', '<', 10)->get();

        if ($lowStockProducts->isEmpty()) {
            $this->info('Tidak ada produk dengan stok rendah.');
            return;
        }

        $users = User::all();

        foreach ($lowStockProducts as $product) {
            foreach ($users as $user) {
                $user->notify(new LowStockNotification($product));
            }
        }

        $this->info('Notifikasi stok rendah telah dikirim.');
    }
}
