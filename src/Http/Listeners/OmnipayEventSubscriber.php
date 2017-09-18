<?php

namespace CoreCMF\Omnipay\Http\Listeners;

/**
 * [OmnipayEventSubscriber 支付扩展包订阅器]
 */
class OmnipayEventSubscriber
{

    /**
     * [onBuilderTablePackage 后台模型table渲染处理]
     * @param  [type] $event [description]
     * @return [type]        [description]
     */
    public function onAdminMain($event)
    {
        // $main = $event->main;
        // if ($main->event == 'AdminMain') {
        //     // dd($main);
        // }
    }
    /**
     * 为订阅者注册监听器.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'CoreCMF\Core\Support\Events\BuilderMain',
            'CoreCMF\Omnipay\Http\Listeners\OmnipayEventSubscriber@onAdminMain'
        );
    }

}
