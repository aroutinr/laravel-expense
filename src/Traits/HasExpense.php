<?php

namespace AroutinR\Expense\Traits;

use AroutinR\Expense\Models\Expense;

trait HasExpense
{
    /**
     * Set the polymorphic relation
     *
     * @return mixed
     */
    public function expenses()
    {
        return $this->morphMany(Expense::class, 'expenseable');
    }
}
