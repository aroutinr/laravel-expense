<?php

namespace AroutinR\Expense\Tests\Models;

use AroutinR\Expense\Traits\HasExpense;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
	use HasExpense;
	/**
	* The attributes that aren't mass assignable.
	*
	* @var array
	*/
	protected $guarded = [];
}