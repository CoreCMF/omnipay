<?php

namespace CoreCMF\Omnipay\App\Listeners;

/**
 * [OmnipayEventSubscriber 支付扩展包订阅器]
 */
class OmnipayEventSubscriber
{

    /**
     * [onAdminMain 后台前端路由注册 侧栏菜单注册]
     * @param  [type] $event [description]
     * @return [type]        [description]
     */
    public function onAdminMain($event)
    {
        $main = $event->main;
        switch ($main->event) {
          case 'adminMain':
            //后台前端路由注册
            $main->routes->transform(function ($item, $key) use ($main) {
                if ($item->get('name') == 'admin') {
                    foreach (config('omnipay-route.admin') as $key => $route) {
                        $item->get('children')->push($route);
                    }
                }
                return $item;
            });
            break;
          case 'adminTop':
            //后台顶部导航
            break;
          case 'adminSidebar':
            //后台侧栏导航
            $main->addMenus(config('omnipay-menu.sidebar'));//增加侧栏
            break;
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
            'CoreCMF\Core\Support\Events\BuilderMain',
            'CoreCMF\Omnipay\App\Listeners\OmnipayEventSubscriber@onAdminMain'
        );
    }
}
