<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessFcmNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $title;
    public $body;
    public $token;
    public $authToken;

    public function __construct($title, $body, $token, $authToken)
    {
        $this->title = $title;
        $this->body = $body;
        $this->token = $token;
        $this->authToken = $authToken;
    }

    
    public function handle()
    {
        $url="https://fcm.googleapis.com/v1/projects/bengalshop-a5316/messages:send";
        
        $client = new \GuzzleHttp\Client();
        
        $headers = [
             'Authorization'=>'Bearer '.$this->authToken,
             'Content-Type'=>'application/json'
        ];

        $notification = array(
            "message" => array(
                "token" =>$this->token,
                
                "notification" => array(
                    "title" => $this->title,
                    "body" => $this->body,
                    'image' => "https://images.unsplash.com/photo-1617791160588-241658c0f566?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1964&q=80"
                ),
                "webpush" => array(                    
                    "fcm_options" => array(
                        "link" => "/dummypage"
                    )
                )
            )
        );

        $response = $client->request('POST',$url, [
            'headers' => $headers,'json'=>($notification)
        ]);

        
    }
}
