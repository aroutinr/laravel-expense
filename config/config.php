<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Table Names
    |--------------------------------------------------------------------------
    |
    | Define the table names for the package
    |
    */

    'table_names' => [
        'expenses' => 'expenses',
        'expense_lines' => 'expense_lines',
        'expense_payments' => 'expense_payments',
    ],

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | Define the currency for the package. 
    | You can change it while making a new expense.
    | Only 3 characters
    |
    */

    'currency' => 'USD',

    /*
    |--------------------------------------------------------------------------
    | Custom Fields
    |--------------------------------------------------------------------------
    |
    | Define the customs fields that you want to use with your expense
    |
    */

    'custom_fields' => 4,

    /*
    |--------------------------------------------------------------------------
    | Expense info
    |--------------------------------------------------------------------------
    |
    | Set your Expense info for the expense receipt
    |
    */

    'info' => [
        'name' => env('APP_NAME', 'Laravel Expense'),
        'address' => 'Laravel Expense Address',
        'contact' => 'Phone: +1(234)567-8900 / email: info@laravel-expense.test',
        'url' => env('APP_URL', 'http://laravel-expense.test'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Expense format
    |--------------------------------------------------------------------------
    |
    | Define the number format for the expense 
    | Decimals quantity, decimal separator and thousand seperator
    |
    */

    'format' => [
        'decimals' => 2,
        'decimal_separator' => '.',
        'thousand_seperator' => ',',
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment methods
    |--------------------------------------------------------------------------
    |
    | Define the payment methods for your expenses
    |
    */

    'payment_methods' => [
        'Cash', 'Check', 'Transfer'
    ],

];