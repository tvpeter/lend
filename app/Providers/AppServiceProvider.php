<?php

namespace App\Providers;

use App\Helpers\GuzzleRequestHandler;
use App\Loan;
use App\Observers\LoanObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\HandlerStack;
use GuzzleLogMiddleware\LogMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {   
        $this->app->bind('handlestack', function () {
            $stack = HandlerStack::create();
            $stack->push(new LogMiddleware(app('log'), new GuzzleRequestHandler()));

            return $stack;
        });
        
        Loan::observe(LoanObserver::class);
    }
}
