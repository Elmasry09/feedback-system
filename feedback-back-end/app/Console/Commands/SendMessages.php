<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Message;
use Twilio\Rest\Client;
use Illuminate\Console\Command;

class SendMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:Send-Messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp messages to customers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::doesntHave('message')->get();

        $sid = env('TWILIO_SID');
        $token  = env('TWILIO_TOKEN');
        $twilio = new Client($sid, $token);
        foreach ($orders as $order) {
            try {
                $message = $twilio->messages
                    ->create(
                        'whatsapp:+2' . $order->phone, // send to
                        [
                            'from' => 'whatsapp:+14155238886',
                            'body' => "Hello dear {$order->name}, we hope you have a nice day. If you don't mind, we were hoping you would answer some of our questions. Thank you for your time and support. We really appreciate it.   
To answer, click here. http://localhost:5173/feedback/" . $order->slug
                        ]
                    );
                $sendMessage = Message::create([
                    'order_id' => $order->id,
                    'message_sid' => $message->sid
                ]);
            } catch (\Exception $e) {
                logger()->error("Failed to send message to {$order->name}: " . $e->getMessage());
            }
        }
    }
}
