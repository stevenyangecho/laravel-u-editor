<?php namespace Stevenyangecho\UEditor;


use Illuminate\Support\ServiceProvider;
class LumenUEditorServiceProvider extends ServiceProvider
{


    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     *
     * @return void
     */
    public function boot()
    {

        $router = app('router');
        //need add auth
        $config = config('UEditorUpload.core.route', []);
        $config['namespace'] = __NAMESPACE__;

        //定义路由
        $router->group($config, function ($router) {
            $router->get('/laravel-u-editor-server/server', 'LumenController@server');
            $router->post('/laravel-u-editor-server/server', 'LumenController@server');
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {

    }

}
