<?php

namespace CoreCMF\Omnipay\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OmnipayController extends Controller
{


    public function __construct(){

    }
    public function pay($service)
    {
        $gateway = resolve('omnipay')->gateway($service)->sandbox();
        $order = [
          'out_trade_no' => date('YmdHis') . mt_rand(1000,9999),
          'subject' => 'Alipay Test',
          'total_amount' => '0.01',
          'product_code' => 'FAST_INSTANT_TRADE_PAY',
        ];
        $response = $gateway->purchase()->setBizContent($order)->send();
        $response->redirect();
        dd($gateway);
        dd($service, $redirect);
        return ;
    }
    public function callback($service, Request $request)
    {
        $gateway = resolve('omnipay')->gateway($service)->sandbox();
        $options = [
            'params' => $request->all()
        ];

        $response = $gateway->completePurchase($options)->send();
        dd($response->isSuccessful(),$response->isPaid());
      $response =get_class_methods($response);
        dd($response);
    }
}
