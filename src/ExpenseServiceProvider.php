<?php

namespace AroutinR\Expense;

use Illuminate\Support\ServiceProvider;

class ExpenseServiceProvider extends ServiceProvider
{
	public function register()
	{
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'expense');
	}

	public function boot()
	{
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('expense.php'),
            ], 'config');
        }
	}
}
