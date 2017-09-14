<?php

namespace CoreCMF\Omnipay\Http\Listeners;

use CoreCMF\Omnipay\Http\Models\Config;
/**
 * [SocialiteEventSubscriber 社会登录事件订阅者]
 */
class OmnipayEventSubscriber
{
    private $configModel;

    public function __construct(Config $configPro){
       $this->configModel = $configPro;
    }
    /**
     * [onBuilderTablePackage 后台模型table渲染处理]
     * @param  [type] $event [description]
     * @return [type]        [description]
     */
    public function onBuilderTablePackage($event)
    {
        $table = $event->table;
        if ($table->event == 'package') {
            $table->data->transform(function ($item, $key) {
                if ($item->name == 'Omnipay') {
                    // $item->rightButton = [
                    //     ['title'=>'云存储管理','apiUrl'=> route('api.omnipay.config.index'),'type'=>'info', 'icon'=>'fa fa-edit']
                    // ];
                }
                return $item;
            });
        }
    }
    /**
     * 为订阅者注册监听器.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'CoreCMF\Core\Events\BuilderTable',
            'CoreCMF\Omnipay\Http\Listeners\OmnipayEventSubscriber@onBuilderTablePackage'
        );
    }

}
