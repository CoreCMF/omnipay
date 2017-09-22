<?php

return [

	// The default gateway to use
	'default' => 'paypal',

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
				'privateKey'      => 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCF87XPsYqzDw50lDedK72tZ6RNVNHwpyFN0Ou4zGxyvzVNA/j2NdM+HiqCg+pR2YCApeTvTJjG3f1JFTJLMp2GhSKXTogQ5Dpqgl3ZGRpvSWo02hIzM2FqEYH5Ibg52u596XzTuL0GI+dVcmjdkO+ZwTCsgXS3+rbVfzYUoenJEB6yf+TER0WlQur53Y4Z5wuXNbUE4k1vDHf38gZdCXWnTrmBDeXgyDlEYU6QirPf2b6jyTQEy6T/84Buyp9WdWlpEEi7lOZHsYbgaQHoKQEWE/WY6uh2jhL6HvxJ8MDnGdq7r/xAwzXi2J1WlUWuiypUnb2FuF5YVcebKR4IpTxPAgMBAAECggEAZD3hTTA774vQ8YUJqWDWbTImToivIzyvO6ChBoZUkfptVNkoMnhNQSsp/MnjTKVL+PosvLfBjPELXumx3XxfTg/LtxdZ0+o3nQU6XgDfO3DhPfQDsrU8rTe04E9PolwoJanZdEfHjRTJRbR8rXKNG67rUez+/79rm5G7Uu1oGsIOZeNn9lTEm3XJ+jAzzZmE5KSrU9B/dMvxPSesVOmynSoXjYiRlaXx23PhluONWLgalqx4I9DQzcLA71D4zCrMvEPh2GwLKxvaDpXDfoMMQ5vprteCcea6IQif26N60mxc2TrBaRrAXh3DD82jQ1MC0XDvScWK+YbVO+klcTpRYQKBgQDjjz2hqxOGk1NyFByXQDNMfOOgwSv3n4jfgNk7k1udkCFSXWzUOH4BeIOk9HKxGupFW3YvK2gnDy4nN3iNzWw0RNXuhFb9QI1dneh44ZHg0SeBMwQ49gsdKpTPp4zyxlR88SmtCePAJem4oNeJ6hMhbkdPSpdNtLX4U8Ttf+h8ywKBgQCWsX/d81LMnpYPWmpYzmZK4LpEPg4NdRbd7LRlqq8JZG0/bcqfinoTYC7F2EhYT1tm6koMDQ9IaeS8nHhsSpFFztmA7JwT4F+pLInAxZSAbM1RKRLhT/tu3p78fEnsSd2RtZsSCftTcyfjMs3QuaQ+6XMtbbxsXct0Jc4fpr3yDQKBgGD6uM9fc1DVFKj3fT/QcoxUZUYrTSuY5+IorWvAXzkFJc5OVoUTFlBWJfR04TQtPk4Urodnvll3FLpN36i/NTAJbgrah6AilJyjIWHy8BKZ0HY1YcVMxmvFq+nbhCwq7fLZbGN8ZX2MKtEuau0xvrluoCFgrp7FNw//01tMOa6FAoGAWWiPGT0ZT1Z4ajuXBYHQp3SWj47R/6INIzciAYA3wjbXqt6VbftXWs+icLhWlC7haDpyHJxTCP/rOw0LVhUYBHcqDZhVMmXZ3CIUyU9xQJFNcG4SSaSO5cUoR13m7k8VKzK5AwbxGx4j+GVYQBMlJD6Wm8mUFifAYvVbSuQ/prECgYEArzL8I86qoWmxl0yqU2TvZIfMSxWJeUVqsy0ExHTaI1ftbcFB4+uRWYz+brVOqg5aVoT5VkEotQ3rXQxJeXv0q6k4KvFkQkrtdiNfgWg4JD+A5UiG2xb0Zlol+NwvL0ohtHllx2W/MZ5Ltn8zGIZvcbrqY/51SxxNqTi03YRucoY=',
				'alipayPublicKey' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAruH561raPfR7mk3OFe/AKGsuir0oqfnwRekRtAo4EbliOlfSB2XAcQnyn6Wkc9bvRWkgGq6MJV2lVKRs114yyGz1MEhrjz8P1slp3KFnx/TwQgZSTGVH55BLNfB0cc+YA7/beTXHCOG4rQp8KPLURplkCMtuM/dQwS/6b/pF6dFHFhkZgXsHwtzK20jr6xVcT2Hk4tQGA1tfUSrskkj+CH61TSGfp5YkkfnieG3FEGfCjod0t37dCDKFNxD6EDOa10VqFtipLspo14PTDQmr3wQHCfZfmXqMdHtr2NMnIDYT4DCHhcUSI0VPMAohLbW6Y4Dm1JEkOyighLbrgY2qYQIDAQAB',
        'returnUrl' => 'http://corecmf.dev/Omnipay/alipay/callback',
        'notifyUrl' => 'http://corecmf.dev/Omnipay/alipay'
      ]
    ],
		'wechat' => [
		    'driver' => 'WechatPay',
		    'options' => [
		        'appId' => 'wxd18b8356c98591dc',
		        'mchId' => '10035597',
		        'apiKey' =>'237a9914f17b3041d60f9eee5406af2c',
						'tradeType' => 'NATIVE',
						'notifyUrl' => 'http://corecmf.dev/Omnipay/unionpay'
		    ]
		],
		'unionpay' => [
		    'driver' => 'UnionPay_Express',
		    'options' => [
		        'merId' => '777290058151800',
		        'certPath' => storage_path('app/certificates/unionpay/unionpay_acp.pfx'),
		        'certPassword' =>'000000',
		        'certDir'=> storage_path('app/certificates/unionpay'),
		        'returnUrl' => 'http://corecmf.dev/Omnipay/unionpay/callback',
		        'notifyUrl' => 'http://corecmf.dev/Omnipay/unionpay'
		    ]
		]
	]
];
