<?php

namespace App\Listeners;

use App\Events\ProductPurchased;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateProductQuantity implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param ProductPurchased $event
     * @return void
     */
    public function handle(ProductPurchased $event)
    {
        $product = $event->product;

        if ($product->quantity > 1) {
            $newQuantity = $product->quantity - 1;
        }

        $product->update(['quantity' => $newQuantity]);
        
    }
}
