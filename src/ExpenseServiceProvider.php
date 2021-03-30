<?php

namespace AroutinR\Expense;

use AroutinR\Expense\Services\ExpensePaymentService;
use AroutinR\Expense\Services\ExpenseService;
use Illuminate\Support\ServiceProvider;

class ExpenseServiceProvider extends ServiceProvider
{
	public function register()
	{
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'expense');

        $this->app->bind('expense', function($app) {
            return new ExpenseService();
        });

        $this->app->bind('expense-payment', function($app) {
            return new ExpensePaymentService();
        });
	}

	public function boot()
	{
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-expense');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('expense.php'),
            ], 'config');

            if (! class_exists('CreateExpensesTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_expenses_table.php.stub' => database_path('migrations/2021_03_30_174505_create_expenses_table.php'),
                ], 'migrations');
            }

            if (! class_exists('CreateExpenseLinesTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_expense_lines_table.php.stub' => database_path('migrations/2021_03_30_174510_create_expense_lines_table.php'),
                ], 'migrations');
            }

            if (! class_exists('CreateExpensePaymentsTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_expense_payments_table.php.stub' => database_path('migrations/2021_03_30_174515_create_expense_payments_table.php'),
                ], 'migrations');
            }

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-expense'),
            ], 'views');
        }
	}
}
