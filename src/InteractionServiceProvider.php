<?php

namespace Field\Interaction;

use Illuminate\Support\ServiceProvider;

class InteractionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
	
	
    public function boot()
    {
        //
        if ($this->app->runningInConsole()) {
        	
        	$this->publishes([
        			__DIR__.'/../resource/js/' => public_path('vendor/interaction'),
        	], 'interaction');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
