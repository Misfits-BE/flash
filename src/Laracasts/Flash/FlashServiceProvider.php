<?php

namespace Laracasts\Flash;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ServiceProvider;

class FlashServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Laracasts\Flash\SessionStore', 'Laracasts\Flash\LaravelSessionStore');

        $this->app->singleton('Laracasts\Flash\FlashNotifier', function ($app) {
            return new FlashNotifier($app->make('Laracasts\Flash\SessionStore'));
        });

        $this->app->alias('Laracasts\Flash\FlashNotifier', 'flash');

        RedirectResponse::macro('withFlash', function ($message = null, $level = 'info', $important = false) {
            $flash = app('flash');

            if (! is_null($message)) {
                $flash->message($message, $level);

                if ($important) {
                    $flash->important();
                }
            }

            return $this;
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../views', 'flash');

        $this->publishes([
            __DIR__ . '/../../views' => base_path('resources/views/vendor/flash')
        ]);
    }

}
