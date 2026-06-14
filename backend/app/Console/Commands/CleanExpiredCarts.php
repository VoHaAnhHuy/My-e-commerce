<?php

namespace App\Console\Commands;

use App\Models\Cart;
use Illuminate\Console\Command;

class CleanExpiredCarts extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'carts:clean-expired';

    /**
     * The console command description.
     */
    protected $description = 'Xóa các guest cart đã hết hạn (expires_at < now)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $count = Cart::where('status', 'active')
            ->whereNotNull('cart_token')
            ->whereNull('user_id')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        $this->info("Đã đánh dấu {$count} guest cart(s) là expired.");

        // Xóa cart items của các cart đã expired (tuỳ chọn, có thể giữ để phân tích)
        // Cart::where('status', 'expired')->each(fn ($cart) => $cart->items()->delete());

        return Command::SUCCESS;
    }
}
