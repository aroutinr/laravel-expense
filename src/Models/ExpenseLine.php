<?php

namespace AroutinR\Expense\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseLine extends Model
{
	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = [];

    /**
     * ExpenseLine constructor
     * 
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('expense.table_names.expense_lines'));
    }

    /**
     * Get the expense for this line
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}
