<?php 

return [
	'sslcommerz' => [
		//'projectPath' => env('PROJECT_PATH'),
	    // For Sandbox, use "https://sandbox.sslcommerz.com"
	    // For Live, use "https://securepay.sslcommerz.com"
	    'apiDomain' => env("SSLCOMMERZ_API_DOMAIN_URL", "https://sandbox.sslcommerz.com"),
	    'apiCredentials' => [
	        'store_id' => env("SSLCOMMERZ_STORE_ID"),
	        'store_password' => env("SSLCOMMERZ_STORE_PASSWORD"),
	    ],
	    'apiUrl' => [
	        'make_payment' => "/gwprocess/v4/api.php",
	        'transaction_status' => "/validator/api/merchantTransIDvalidationAPI.php",
	        'order_validate' => "/validator/api/validationserverAPI.php",
	        'make_refund' => "/validator/api/merchantTransIDvalidationAPI.php",
	        'refund_status' => "/validator/api/merchantTransIDvalidationAPI.php",
	    ],
	    'connect_from_localhost' => env("SSLCOMMERZ_IS_LOCALHOST", true), // For Sandbox, use "true", For Live, use "false"
	    'success_url' => '/api/payments/success',
	    'failed_url' => '/api/payments/fail',
	    'cancel_url' => '/api/payments/cancel',	    
	    //'ipn_url' => '/api/payments/ipn',
	    'ipn_url' => '/api/payments/ipn',
	],
	'client' => [
		'url' => env('APP_CLIENT_URL')
	]
];