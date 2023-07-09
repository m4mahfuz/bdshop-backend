<?php

namespace App\Http\Controllers;
 
use App\Models\Device;
use App\Jobs\ProcessFcmNotification;
use Illuminate\Http\Request;

class FirebaseNotificationController extends Controller
{
    public function getAuthToken() {
        //replace this with your actual path and file name
        $privateKeyFilePath = base_path()."/service-account-file.json";
        $googleClient = new \Google_Client();
        $googleClient->setAuthConfig($privateKeyFilePath);
        $googleClient->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $googleClient->refreshTokenWithAssertion();
        $token = $googleClient->getAccessToken();
        return $token['access_token'];
    }


    public function send_push_notification(Request $request){

        $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        // $sToken=$this->input->post('token');
        
        // $sToken=$request->input('token');

        $tokens = Device::tokens();
        // return $tokens[0]->token;
        $title=$request->input('title');
        $body=$request->input('body');        
        
        /*$url="https://fcm.googleapis.com/v1/projects/bengalshop-a5316/messages:send";
        
        $client = new \GuzzleHttp\Client();
        
        $headers = [
             'Authorization'=>'Bearer '.$this->getAuthToken(),
             'Content-Type'=>'application/json'
        ];*/

        // $notification = array(
        //     "message" => array(
        //         // "token" =>$sToken,
        //         "token" =>$tokens[0]->token,
        //         // "token" =>'cKyRP1qlOb6y4DLJbXu0hX:APA91bEfcfb0hiC569zF2Y3zXkG-yMSOPiHeRDAs9MrgH5oK85kHA4szJJSKbXKnj7uw2FQ7oef-RLZBLfA7qdr1HDsQ22v9oTO8Y6Z-RlHrwjvNt59cWn09a-CdW4XnowmKBbqAhWuY',
        //         "notification" => array(
        //             "title" => $title,
        //             "body" => $body,
        //             'image' => "https://images.unsplash.com/photo-1617791160588-241658c0f566?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1964&q=80"
        //         ),
        //         "webpush" => array(                    
        //             "fcm_options" => array(
        //                 "link" => "/dummypage"
        //             )
        //         )
        //     )
        // );

        // $response = $client->request('POST',$url, [
        //     'headers' => $headers,'json'=>($notification)
        // ]);


        // foreach ($tokens as $token) {
        //     $notification = array(
        //         "message" => array(
        //             "token" =>$token->token,
        //             "notification" => array(
        //                 "title" => $title,
        //                 "body" => $body,
        //                 'image' => "https://images.unsplash.com/photo-1617791160588-241658c0f566?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1964&q=80"
        //             ),
        //             "webpush" => array(                    
        //                 "fcm_options" => array(
        //                     "link" => "/dummypage"
        //                 )
        //             )
        //         )
        //     );

        //     $response = $client->request('POST',$url, [
        //         'headers' => $headers,'json'=>($notification)
        //     ]);
        // }
        $authToken = $this->getAuthToken();

        foreach ($tokens as $token) {
            ProcessFcmNotification::dispatch($title, $body, $token->token, $authToken);
        }

    }
}
