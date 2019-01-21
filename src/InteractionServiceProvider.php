<?php

namespace Field\Interaction;

use Illuminate\Support\ServiceProvider;

class InteractionServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(Interaction $extension)
    {
        if (! Interaction::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'field-interaction');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-ext/field-interaction')],
                'field-interaction'
            );
        }

        $this->app->booted(function () {
            Interaction::routes(__DIR__.'/../routes/web.php');
        });
    }
}