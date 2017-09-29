<?php

namespace CoreCMF\Omnipay;

use Omnipay;
use DB;
use Illuminate\Support\ServiceProvider;
use CoreCMF\Omnipay\App\Models\Config;

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
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
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
}
