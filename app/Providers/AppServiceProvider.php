<?php

namespace App\Providers;

use App\JiraArrayConfigurationInterface;
use Illuminate\Support\ServiceProvider;
use JiraRestApi\Configuration\ArrayConfiguration;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(JiraArrayConfigurationInterface::class, function () {
            return new ArrayConfiguration(
                [
                    'jiraHost' => config("jira.host"),
                    'jiraUser' => config("jira.user"),
                    'jiraPassword' => config("jira.password"),
                ]
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
