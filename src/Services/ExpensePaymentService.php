<?php

namespace AroutinR\Expense\Services;

use AroutinR\Expense\Interfaces\ExpensePaymentServiceInterface;
use AroutinR\Expense\Models\Expense;
use AroutinR\Expense\Models\ExpensePayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class ExpensePaymentService implements ExpensePaymentServiceInterface
{
	public $expense;
	public $date;
	public $amount;
	public $number;
	public $method;
	public $reference;

	public function __construct()
	{
		$this->date(now()->format('Y-m-d'));
	}

	public function create(Model $expense): ExpensePaymentService
	{
		$this->expense = $expense;

		return $this;
	}

	public function date(string $date): ExpensePaymentService
	{
		$this->date = $date;

		return $this;
	}

	public function amount(int $amount): ExpensePaymentService
	{
		$this->amount = $amount;

		return $this;
	}

	public function number(string $number): ExpensePaymentService
	{
		$this->number = $number;

		return $this;
	}

	public function method(string $method): ExpensePaymentService
	{
		$this->method = $method;

		return $this;
	}

	public function reference(string $reference): ExpensePaymentService
	{
		$this->reference = $reference;

		return $this;
	}

	public function save(): ExpensePayment
	{
		if (!$this->expense) {
			throw new \Exception('You must add a Expense model', 1);
		}

		if ($this->amount > $this->expense->balance) {
			throw new \Exception("The payment amount cannot be higher than the expense amount", 1);
		}

		$payment = $this->expense->payments()->create([
			'date' => $this->date,
			'amount' => $this->amount,
			'number' => $this->number,
			'method' => $this->method,
			'reference' => $this->reference,
		]);

		$this->resetExpensePaymentService();

		return $payment;
	}

	public function saveAndView(array $data = []): \Illuminate\Contracts\View\View
	{
		$payment = $this->save();

		return $this->view($payment);
	}

	public function view(ExpensePayment $payment, array $data = []): \Illuminate\Contracts\View\View
	{
		return View::make('laravel-expense::expenses.expense-payment-receipt', array_merge($data, [
			'payment' => $payment,
		]));
	}

	protected function resetExpensePaymentService()
	{
		$this->date = now()->format('Y-m-d');
		$this->amount = null;
		$this->number = null;
		$this->method = null;
		$this->reference = null;
	}
}
