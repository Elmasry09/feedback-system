<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Twilio\Rest\Client;

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
        $previousDay = Carbon::yesterday();
        $orders = Order::whereDate('created_at', $previousDay)->get();

        $sid = env('TWILIO_SID');
        $token  = env('TWILIO_TOKEN');
        $twilio = new Client($sid, $token);
        foreach ($orders as $order) {
            try {
                $message = $twilio->messages
                    ->create('whatsapp:+2'. $order->phone, // send to
                        [
                            'from' => 'whatsapp:+14155238886',
                            'body' => 'hello '. $order->name,
                        ]
                    );
                sleep(5);
                $updated = $twilio->messages($message->sid)->fetch();
                logger()->info("Message sent to {$order->name} with status: {$updated->status}");

            } catch (\Exception $e) {
                logger()->error("Failed to send message to {$order->name}: ". $e->getMessage());
            }
        }
    }
}
