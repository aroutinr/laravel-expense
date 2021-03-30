<?php
namespace AroutinR\Expense\Interfaces;

use AroutinR\Expense\Models\Expense;
use AroutinR\Expense\Services\ExpenseService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ExpenseServiceInterface
{
    /**
     * Construct the ExpenseService class
     *
     * @return void
     */
    public function __construct();

    /**
     * Setup the ExpenseService with the Eloquent models.
     *
     * @param Model $vendor       Eloquent model.
     * @param Model $expenseable  Eloquent model.
     * @return ExpenseService
     */
    public function create(Model $vendor, Model $expenseable): ExpenseService;

    /**
     * Set the expense number
     *
     * @return ExpenseService
     */
    public function number(string $number): ExpenseService;

    /**
     * Set the expense currency
     *
     * @return ExpenseService
     */
    public function currency(string $currency): ExpenseService;

    /**
     * Set the expense date
     *
     * @return ExpenseService
     */
    public function date(string $date): ExpenseService;

    /**
     * Add a line type to the expense.
     *
     * @return ExpenseService
     */
    public function line(string $description, float $quantity, int $amount): ExpenseService;

    /**
     * Add multiple lines type to the expense
     *
     * @return ExpenseService
     */
    public function lines(array $expense_lines): ExpenseService;

    /**
     * Add custom fields to the expense
     * 
     * @return ExpenseService
     */
    public function customField(string $name, string $value): ExpenseService;

    /**
     * Add notes to the expense
     * 
     * @return ExpenseService
     */
    public function note(string $note): ExpenseService;

    /**
     * Save the expense to the database.
     *
     * @return Expense
     */
    public function save(): Expense;

    /**
     * Save the expense to the database and get the View instance for the expense.
     *
     * @return \Illuminate\View\View
     */
    public function saveAndView(): View;

    /**
     * Get the View instance for the expense.
     *
     * @param  Expense  $expense
     * @param  array  $data
     * @return \Illuminate\View\View
     */
    public function view(Expense $expense, array $data = []): View;
}
