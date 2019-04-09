<?php

namespace Adyns\ViewMaker;


use Illuminate\Support\ServiceProvider;

class ViewMakerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if($this->app->runningInConsole()){
            $this->commands([
                ViewMaker::class
            ]);
        }

    }
}
