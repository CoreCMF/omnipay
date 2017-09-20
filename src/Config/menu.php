<?php

return [
    //系统配置
    'sidebar' => [
        [
            'path' => '',
            'title' => '支付管理',
            'icon' => 'fa fa-cny',
            'subMenus' => [
              [
                'path' => '/admin/omnipay/config',
                'title' => '支付配置',
                'icon' => 'fa fa-ge',
              ],
              [
                'path' => '/admin/omnipay/order',
                'title' => '订单管理',
                'icon' => 'fa fa-first-order',
              ],
            ]
        ]
    ],
];
