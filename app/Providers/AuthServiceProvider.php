<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Quote;
use App\Models\RecurringInvoice;
use App\Models\User;
use App\Policies\ClientPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\ProductPolicy;
use App\Policies\QuotePolicy;
use App\Policies\RecurringInvoicePolicy;
use Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Client::class => ClientPolicy::class,
        Product::class => ProductPolicy::class,
        Invoice::class => InvoicePolicy::class,
        RecurringInvoice::class => RecurringInvoicePolicy::class,
        Quote::class => QuotePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */

    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('users', function ($app, array $config) {
            return new MultiDatabaseUserProvider($this->app['hash'], $config['model']);
        });

        Auth::provider('contacts', function ($app, array $config) {
            return new MultiDatabaseUserProvider($this->app['hash'], $config['model']);

        });

        Gate::define('view-list', function ($user, $entity) {

            $entity = strtolower(class_basename($entity));

                return $user->hasPermission('view_' . $entity) || $user->isAdmin();

        });

    }

}
