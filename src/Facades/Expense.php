<?php

namespace AroutinR\Expense\Facades;

use Illuminate\Support\Facades\Facade;

class Expense extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'expense';
    }
}
