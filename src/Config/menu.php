<?php

return [
    //系统配置
    'sidebar' => [
        [
            'path' => '',
            'title' => '支付管理',
            'icon' => 'fa fa-unlock-alt',
            'subMenus' => [
              [
                'path' => '/admin/omnipay/config',
                'title' => '支付管理',
                'icon' => 'fa fa-unlock-alt',
              ],
              [
                'path' => '/admin/omnipay/order',
                'title' => '订单管理',
                'icon' => 'fa fa-unlock-alt',
              ],
            ]
        ]
    ],
];
