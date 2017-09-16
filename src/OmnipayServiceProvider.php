<?php

namespace CoreCMF\Omnipay;

use Omnipay;
use DB;
use Illuminate\Support\ServiceProvider;

class OmnipayServiceProvider extends ServiceProvider
{
    protected $commands = [
        \CoreCMF\Omnipay\Http\Console\InstallCommand::class,
        \CoreCMF\Omnipay\Http\Console\UninstallCommand::class,
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
        //配置路由
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        //迁移文件配置
        $this->loadMigrationsFrom(__DIR__.'/Databases/migrations');
        // 加载配置
        $this->mergeConfigFrom(__DIR__.'/Config/laravel-omnipay.php', 'laravel-omnipay');
        $this->mergeConfigFrom(__DIR__.'/Config/config.php', 'omnipay');
        $this->initService();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {

    }
    /**
     * 初始化服务
     */
    public function initService()
    {
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
