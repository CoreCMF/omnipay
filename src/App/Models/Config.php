<?php

namespace CoreCMF\Omnipay\App\Models;

use Schema;
use Illuminate\Database\Eloquent\Model;
use CoreCMF\Core\App\Models\Upload;

class Config extends Model
{
    public $table = 'omnipay_configs';

    protected $fillable = [];

    public function getStatusAttribute($value)
    {
        return (boolean)$value;
    }
    /**
     * [configRegister 注册配置信息]
     * @return [type] [description]
     */
    public function configRegister()
    {
        if (Schema::hasTable('omnipay_configs')) {
            $this->gatewayRegister();
        }
    }
    /**
     * [gatewayRegister 网关配置注册]
     * @return [type] [description]
     */
    public function gatewayRegister()
    {
        $this->where('status', true)->get()->map(function ($item) {
          //获取证书密钥文件路径
          $upload = new Upload();
          $private_key = $item->private_key? storage_path('app/'.$upload->getUploadWhereFirst($item->private_key)->path):null;
          $public_key = $item->public_key? storage_path('app/'.$upload->getUploadWhereFirst($item->public_key)->path):null;
          switch ($item->gateway) {
            case 'alipay':
              config(['laravel-omnipay.gateways.'.$item->gateway.'.driver' => $item->driver]);
                config(['laravel-omnipay.gateways.'.$item->gateway.'.options' => [
                  	'signType' => $item->other,
                    'appId'    => $item->app_id,
              			'sellerEmail'     => $item->seller_id,
              			'privateKey'      => $private_key,
              			'alipayPublicKey' => $public_key,
                    'returnUrl' => $item->return_url,
                    'notifyUrl' => $item->notify_url
                ]]);
            break;
            case 'wechat':
              config(['laravel-omnipay.gateways.'.$item->gateway.'.driver' => $item->driver]);
                config(['laravel-omnipay.gateways.'.$item->gateway.'.options' => [
                    'appId'    => $item->app_id,
                    'mchId'    => $item->seller_id,
                    'apiKey'   => $item->other,
                    'keyPath'  => $private_key,
                    'certPath' => $public_key,
                    'returnUrl' => $item->return_url,
                    'notifyUrl' => $item->notify_url,
                    'tradeType' => 'NATIVE',
                ]]);
            break;
            case 'unionpay':
              config(['laravel-omnipay.gateways.'.$item->gateway.'.driver' => $item->driver]);
                config(['laravel-omnipay.gateways.'.$item->gateway.'.options' => [
                    'merId' => $item->app_id,
              		  'certPath' => $private_key,
              		  'certPassword' => $item->other,
              		  'certDir'=> substr($public_key,0,strripos($public_key,'/')), //这里需要目录
              		  'returnUrl' => $item->return_url,
              		  'notifyUrl' => $item->notify_url
                ]]);
            break;
          }
        });
    }
}
