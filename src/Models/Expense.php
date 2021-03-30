<?php

namespace AroutinR\Expense\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
	/**
	 * The attributes that aren't mass assignable
	 *
	 * @var array
	 */
	protected $guarded = [];

    /**
     * The attributes that should be cast
     *
     * @var array
     */
    protected $casts = [
        'custom_fields' => 'array'
    ];

    /**
     * Expense constructor
     * 
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('expense.table_names.expenses'));
    }

    /**
     * Get the expense vendor
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function vendor()
    {
        return $this->morphTo();
    }

    /**
     * Get the expense expenseable
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function expenseable()
    {
        return $this->morphTo();
    }

    /**
     * Get the lines for this expense
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lines()
    {
        return $this->hasMany(ExpenseLine::class);
    }

    /**
     * Get the payments related to this expense
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(ExpensePayment::class);
    }

    /**
     * Get the expense balance
     *
     * @return  integer  balance
     */
    public function getBalanceAttribute()
    {
        return $this->amount - $this->payments()->sum('amount');
    }
}
