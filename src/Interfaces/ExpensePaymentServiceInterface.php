<?php
namespace AroutinR\Expense\Interfaces;

use AroutinR\Expense\Models\ExpensePayment;
use AroutinR\Expense\Services\ExpensePaymentService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ExpensePaymentServiceInterface
{
    /**
     * Construct the ExpensePaymentService class
     *
     * @return void
     */
    public function __construct();

    /**
     * Setup the ExpensePaymentService with the Eloquent models.
     *
     * @param Model $expenseable Eloquent model.
     * @return ExpensePaymentService
     */
    public function create(Model $expense): ExpensePaymentService;

    /**
     * Set the payment date for the expense
     *
     * @return ExpensePaymentService
     */
    public function date(string $date): ExpensePaymentService;

    /**
     * Set the payment amount for the expense
     *
     * @return ExpensePaymentService
     */
    public function amount(int $amount): ExpensePaymentService;

    /**
     * Set the payment number for the expense
     *
     * @return ExpensePaymentService
     */
    public function number(string $number): ExpensePaymentService;

    /**
     * Set the payment method for the expense.
     *
     * @return ExpensePaymentService
     */
    public function method(string $method): ExpensePaymentService;

    /**
     * Set the payment reference for the expense
     *
     * @return ExpensePaymentService
     */
    public function reference(string $reference): ExpensePaymentService;

    /**
     * Save the expense payment to the database
     *
     * @return ExpensePayment
     */
    public function save(): ExpensePayment;

    /**
     * Save the expense payment to the database and get the View instance for the expense
     *
     * @return \Illuminate\View\View
     */
    public function saveAndView(): View;

    /**
     * Get the View instance for the expense.
     *
     * @param  ExpensePayment  $expensePayment
     * @param  array  $data
     * @return \Illuminate\View\View
     */
    public function view(ExpensePayment $expensePayment, array $data = []): View;
}
