<?php

namespace CoreCMF\Omnipay;

use Redis;
use Route;
use Illuminate\Support\ServiceProvider;
use CoreCMF\Omnipay\App\Models\Config;
use CoreCMF\Core\Support\Browser;

class OmnipayServiceProvider extends ServiceProvider
{
    protected $commands = [
        \CoreCMF\Omnipay\App\Console\InstallCommand::class,
        \CoreCMF\Omnipay\App\Console\UninstallCommand::class,
    ];
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //加载artisan commands
        $this->commands($this->commands);
        //迁移文件配置
        $this->loadMigrationsFrom(__DIR__.'/Databases/migrations');
        $this->publishes([
            __DIR__.'/../resources/mixes/vue-omnipay/dist/vendor/' => public_path('vendor'),
        ], 'omnipay');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->middleware();
        $this->initService();
    }
    /**
     * 初始化服务
     */
    public function initService()
    {
        //配置路由
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/Routes/api.php');
        // 加载配置
        $this->mergeConfigFrom(__DIR__.'/Config/config.php', 'omnipay');//组件配置信息
        $this->mergeConfigFrom(__DIR__.'/Config/menu.php', 'omnipay-menu');//菜单
        $this->mergeConfigFrom(__DIR__.'/Config/route.php', 'omnipay-route');//前端路由
        $this->mergeConfigFrom(__DIR__.'/Config/laravel-omnipay.php', 'laravel-omnipay');

        $config = new Config();
        $config->configRegister();//注册配置信息
        $this->browserConfig();
        //注册providers服务
        $this->registerProviders();
    }
    /**
     * 注册引用服务
     */
    public function registerProviders()
    {
        $providers = config('omnipay.providers');
        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }
    //注册webOmnipay中间件
    public function middleware()
    {
        Route::pushMiddlewareToGroup('webOmnipay', \App\Http\Middleware\EncryptCookies::class);
        Route::pushMiddlewareToGroup('webOmnipay', \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class);
        // Route::pushMiddlewareToGroup('webOmnipay', \Illuminate\Session\Middleware\AuthenticateSession::class);
        Route::pushMiddlewareToGroup('webOmnipay', \Illuminate\Session\Middleware\StartSession::class);
        Route::pushMiddlewareToGroup('webOmnipay', \Illuminate\View\Middleware\ShareErrorsFromSession::class);
        Route::pushMiddlewareToGroup('webOmnipay', \Illuminate\Routing\Middleware\SubstituteBindings::class);
        Route::pushMiddlewareToGroup('webOmnipay', \CoreCMF\Omnipay\App\Http\Middleware\VerifyCsrfToken::class);
    }
    protected function browserConfig()
    {
        $browser = new Browser();
        // 手机配置
        if ($browser->isMobile()) {
            config(['laravel-omnipay.gateways.alipay.driver' => 'Alipay_AopWap']);
        }
        // 微信端配置
        if ($browser->isWechat()) {
            config(['laravel-omnipay.gateways.wechat.options.tradeType' => 'JSAPI']);
        }
    }
}
