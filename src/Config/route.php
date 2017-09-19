<?php

return [
    //后台侧栏前端路由注册
    'admin' => [
        [
          "path" => "/admin/omnipay/config",
          "name" => "admin.omnipay.config",
          "meta" => ["apiUrl" => route('api.admin.omnipay.config')],
          "component" => ["template" => "<bve-index/>"]
        ],
        [
          "path" => "/admin/omnipay/order",
          "name" => "admin.omnipay.order",
          "meta" => ["apiUrl" => route('api.admin.omnipay.order')],
          "component" => ["template" => "<bve-index/>"]
        ],
    ],
];
