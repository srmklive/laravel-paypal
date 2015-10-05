<?php namespace Srmklive\PayPal;

/**
 * Class PayPalServiceProvider
 * @package Srmklive\PayPal
 */

use Illuminate\Support\ServiceProvider;

class PayPalServiceProvider extends ServiceProvider
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
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
            __DIR__. '/../config/config.php' => config_path('paypal.php'),
        ]);

        // Publish Lang Files
        $this->publishes([
            __DIR__. '/../lang/en/cart.php' => realpath(config_path() . '/../resources/lang/en/cart.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerPayPal();

        $this->mergeConfig();
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerPayPal()
    {
        $this->app->singleton('paypal', function () {
            return new ExpressCheckout();
        });
    }

    /**
     * Merges user's and paypal's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__. '/../config/config.php', 'paypal_express'
        );
    }
}
