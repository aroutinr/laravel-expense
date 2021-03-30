<?php

namespace AroutinR\Expense\Traits;

use AroutinR\Expense\Models\Expense;

trait VendorHasExpense
{
    /**
     * Set the polymorphic relation
     *
     * @return mixed
     */
    public function expenses()
    {
        return $this->morphMany(Expense::class, 'vendor');
    }
}
