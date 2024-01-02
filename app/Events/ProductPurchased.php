<?php


namespace App\Events;

use App\Models\Admin\Product\Product;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductPurchased
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product;
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
