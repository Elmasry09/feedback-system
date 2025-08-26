<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Message;
use Twilio\Rest\Client;
use Illuminate\Http\Request;

class SendMessagesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $orders = Order::doesntHave('message')->get();

        $sid = env('TWILIO_SID');
        $token  = env('TWILIO_TOKEN');
        $twilio = new Client($sid, $token);
        if (!$orders) {
            return response()->json(['message' => 'No new orders to send messages to.'], 200);
        }
        foreach ($orders as $order) {
            try {
                $message = $twilio->messages
                    ->create(
                        'whatsapp:+2' . $order->phone, // send to
                        [
                            'from' => 'whatsapp:+14155238886',
                            'body' => "Hello dear {$order->name}, we hope you have a nice day. If you don't mind, we were hoping you would answer some of our questions. Thank you for your time and support. We really appreciate it. To answer, click here. http://localhost:5173/feedback/" . $order->slug
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
        return response()->json(['message' => 'Messages sent successfully.'], 200);
    }
}
