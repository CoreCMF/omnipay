<?php

return [
    'name' => 'Omnipay',
    'title' => '支付管理',
    'description' => '支付管理支持支付宝微信支付银联支付等等',
    'author' => 'BigRocs',
    'version' => 'v1.1.6',
    'serviceProvider' => CoreCMF\Omnipay\OmnipayServiceProvider::class,
    'install' => 'corecmf:omnipay:install',//安装artisan命令
    'uninstall' => 'corecmf:omnipay:uninstall',//卸载artisan命令
    'providers' => [
        Ignited\LaravelOmnipay\LaravelOmnipayServiceProvider::class,
        CoreCMF\Omnipay\Providers\EventServiceProvider::class,//事件服务
    ],
    'debug' => true,
];
