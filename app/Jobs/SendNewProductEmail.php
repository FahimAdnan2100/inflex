<?php
// App\Jobs\SendNewProductEmail.php

namespace App\Jobs;


use App\Enums\UserType;
use App\Models\Admin\Product\product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Stmt\Foreach_;
use App\Mail\MyMail;

class SendNewProductEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product;

    /**
     * Create a new job instance.
     *
     * @param Product $product
     */
    public function __construct(product $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Logic to send email when a new product is created
        $product = $this->product;

        // Customize the email content or use a mailable class
        $emailContent = "A new product '{$product->name}' has been created.";

        $recipients = User::where('user_type', UserType::WebsiteUser)->select('email')->get();

        $recipients->each(function ($user) use ($emailContent) {
           
            Mail::to($user->email)->send(new MyMail($emailContent));
        });
    }
}
