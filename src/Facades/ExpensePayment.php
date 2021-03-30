<?php

namespace AroutinR\Expense\Facades;

use Illuminate\Support\Facades\Facade;

class ExpensePayment extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'expense-payment';
    }
}
