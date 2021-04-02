<?php

namespace AroutinR\Expense\Services;

use AroutinR\Expense\Interfaces\ExpenseServiceInterface;
use AroutinR\Expense\Models\Expense;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class ExpenseService implements ExpenseServiceInterface
{
	public $vendor;
	public $expenseable;
	public $number;
	public $currency;
	public $date;
	public $lines = array();
	public $customFields = array();
	public $note;

	public function __construct()
	{
		$this->currency(config('expense.currency'));
		$this->date(now()->format('Y-m-d'));
	}

	public function create(Model $vendor, Model $expenseable): ExpenseService
	{
		$this->vendor = $vendor;
		$this->expenseable = $expenseable;

		return $this;
	}

	public function number(string $number): ExpenseService
	{
		$this->number = $number;

		return $this;
	}

	public function currency(string $currency): ExpenseService
	{
		if (Str::length($currency) === 3) {
			$this->currency = $currency;

			return $this;
		}

		throw new \Exception('The currency should only be 3 characters long', 1);
	}

	public function date(string $date): ExpenseService
	{
		$this->date = $date;

		return $this;
	}

	public function line(string $description, float $quantity, int $amount): ExpenseService
	{
		$this->lines[] = [
			'description' => $description,
			'quantity' => number_format($quantity, 2, '.', ''),
			'amount' => $amount,
		];

		return $this;
	}

	public function lines(array $expense_lines): ExpenseService
	{
		foreach ($expense_lines as $line) {
			$this->line(
				$line['description'], 
				$line['quantity'], 
				$line['amount']
			);
		}

		return $this;
	}

	public function customField(string $key, string $value): ExpenseService
	{
		if (count($this->customFields) === config('expense.custom_fields', 4)) {
			throw new \Exception('You can add a maximum of ' . config('expense.custom_fields', 4) .' custom fields', 1);
		}

		$this->customFields[$key] = $value;

		return $this;
	}

	public function note(string $note): ExpenseService
	{
		$this->note = $note;

		return $this;
	}

	public function save(): Expense
	{
		if (!$this->vendor || !$this->expenseable) {
			throw new \Exception('You must add a Vendor and Expenseable model', 1);
		}

		if (!count($this->lines)) {
			throw new \Exception('You must add at least one expense line to the expense', 1);
		}

		$expense = Expense::create([
			'vendor_type' => get_class($this->vendor),
			'vendor_id' => $this->vendor->id,
			'expenseable_type' => get_class($this->expenseable),
			'expenseable_id' => $this->expenseable->id,
			'number' => $this->number,
			'currency' => $this->currency,
			'date' => $this->date,
			'amount' => $this->calculateExpenseAmount(),
			'custom_fields' => $this->customFields,
			'note' => $this->note,
		]);

		$expense->lines()->createMany($this->lines);

		$this->resetExpenseService();

		return $expense;
	}

	public function saveAndView(array $data = []): \Illuminate\Contracts\View\View
	{
		$expense = $this->save();

		return $this->view($expense);
	}

	public function view(Expense $expense, array $data = []): \Illuminate\Contracts\View\View
	{
		return View::make('laravel-expense::expenses.expense-receipt', array_merge($data, [
			'expense' => $expense,
		]));
	}

	protected function calculateExpenseAmount(): int
	{
		$amount = 0;

		foreach ($this->lines as $line) {
			$amount += $line['quantity'] * $line['amount'];
		}

		return $amount;
	}

	protected function resetExpenseService(): void
	{
		$this->vendor = null;
		$this->expenseable = null;
		$this->number = null;
		$this->currency = config('expense.currency');
		$this->date = now()->format('Y-m-d');
		$this->lines = array();
		$this->customFields = array();
		$this->note = null;
	}
}
