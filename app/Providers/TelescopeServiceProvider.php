<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Telescope::night();

        $this->hideSensitiveRequestDetails();

        Telescope::filter(function () {
            return true;
        });

        Telescope::tag(function (IncomingEntry $entry) {
            if ($entry->type == 'log') {
                if (isset($entry->content['message']) && explode(' ', $entry->content['message'])[0] == 'HTTP') {
                    $context = $entry->content['context'] ?? false;
                    if ($context) {
                        $uri = $context['request']['uri'] ?? false;
                        if ($uri) {
                            return [explode('?', $uri)[0]];
                        }
                    }
                }
            }

            return [];
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails()
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewTelescope', function ($user) {
            return in_array($user->email, [
                'superadmin@renmoney.com'
            ]);
        });
    }
}
