<?php

return [

	// The default gateway to use
	'default' => 'alipay',

	// Add in each gateway here
	'gateways' => [
		'paypal' => [
			'driver'  => 'PayPal_Express',
			'options' => [
				'solutionType'   => '',
				'landingPage'    => '',
				'headerImageUrl' => ''
			]
		],
		'alipay' => [
      'driver' => 'Alipay_AopPage',
      'options' => [
				'signType' => 'RSA2',
        'appId' => '2016080900197401',
				'sellerEmail'     => 'vyiuhx5367@sandbox.com',
				'privateKey'      => storage_path('app/certificates/alipay/private_key.pem'),
				'alipayPublicKey' => storage_path('app/certificates/alipay/alipay_public_key.pem'),
        'returnUrl' => 'http://corecmf.dev/Omnipay/alipay/callback',
        'notifyUrl' => 'http://corecmf.dev/Omnipay/alipay'
      ]
    ]

	]
];
