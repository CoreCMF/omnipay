<?php

namespace CoreCMF\Omnipay\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OmnipayController extends Controller
{

    private $gateway;

    public function __construct(){
        $this->gateway = resolve('omnipay');
        if (config('omnipay.sandbox')) {
            $this->gateway->sandbox();
        }
    }
    public function pay($service)
    {
        $this->gateway->gateway($service);
        $order = [
          'out_trade_no' => date('YmdHis') . mt_rand(1000,9999),
          'subject' => 'Alipay Test',
          'total_amount' => '0.01',
          'product_code' => 'FAST_INSTANT_TRADE_PAY',
        ];
        $response = $this->gateway->purchase()->setBizContent($order)->send();
        $response->redirect();
        dd($gateway);
        dd($service, $redirect);
        return ;
    }
    public function callback($service, Request $request)
    {
        $this->gateway->gateway($service);
        $options = [
            'params' => $request->all()
        ];

        $response = $this->gateway->completePurchase($options)->send();
        dd($response->isSuccessful(),$response->isPaid());
      $response =get_class_methods($response);
        dd($response);
    }
}
