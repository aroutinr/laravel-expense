<?php

namespace AroutinR\Expense\Tests;

use AroutinR\Expense\ExpenseServiceProvider;
use AroutinR\Expense\Tests\Models\User;
use AroutinR\Expense\Tests\Models\Service;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public $vendor;
    public $expenseable;

	public function setUp(): void
	{
		parent::setUp();
		
		$this->withFactories(__DIR__.'/Database/factories');

        $this->vendor = factory(User::class)->create();
        $this->expenseable = factory(Service::class)->create();
	}

	protected function getPackageProviders($app)
	{
		return [
			ExpenseServiceProvider::class,
		];
	}

	protected function getEnvironmentSetUp($app)
	{
		include_once __DIR__ . '/Database/migrations/create_users_table.php.stub';
		(new \CreateUsersTable)->up();
		include_once __DIR__ . '/Database/migrations/create_services_table.php.stub';
		(new \CreateServicesTable)->up();
		include_once __DIR__ . '/../database/migrations/create_expenses_table.php.stub';
		(new \CreateExpensesTable)->up();
		include_once __DIR__ . '/../database/migrations/create_expense_lines_table.php.stub';
		(new \CreateExpenseLinesTable)->up();
		include_once __DIR__ . '/../database/migrations/create_expense_payments_table.php.stub';
		(new \CreateExpensePaymentsTable)->up();
	}
}
