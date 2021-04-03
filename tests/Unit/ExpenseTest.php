<?php

namespace AroutinR\Expense\Tests\Unit;

use AroutinR\Expense\Facades\Expense;
use AroutinR\Expense\Facades\ExpensePayment;
use AroutinR\Expense\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExpenseTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	public function expense_needs_a_vendor_and_expenseable_model()
	{
		$this->expectException('Exception');
		$this->expectExceptionCode(1);
		$this->expectExceptionMessage('You must add a Vendor and Expenseable model');

		Expense::line('Some description', 1, 10000)
			->save();
	}

	/** @test */
	public function expense_vendor_and_expenseable_has_to_be_model_instances()
	{
		$this->expectException('TypeError');

		Expense::create('Vendor', 'Expenseable')
			->line('Some description', 1, 10000)
			->save();
	}

	/** @test */
	public function expense_need_at_least_one_line_to_be_created()
	{
		$this->expectException('Exception');
		$this->expectExceptionCode(1);
		$this->expectExceptionMessage('You must add at least one expense line to the expense');

		Expense::create($this->vendor, $this->expenseable)
			->save();
	}

	/** @test */
	public function needs_to_pass_the_expense_to_be_update()
	{
		$this->expectException('ArgumentCountError');

		Expense::line('Some description', 1, 10000)
			->update();
	}

    /** @test */
    public function can_create_and_update_a_expense()
    {
    	$expense = Expense::create($this->vendor, $this->expenseable)
			->customField('Origin', 'Houston')
			->line('Some description', 1, 10000)
			->line('Another description', 1, 20000)
			->save();

    	$updatedExpense = Expense::create($this->vendor, $this->expenseable)
			->customField('Origin', 'Texas')
			->line('Some description', 2, 10000)
			->line('Another description', 2, 20000)
			->update($expense);

		$this->assertDatabaseCount('expense_lines', 2);
		$this->assertSame('Texas', $updatedExpense->custom_fields['Origin']);
		$this->assertSame(60000, $updatedExpense->balance);
	}

	/** @test */
	public function can_add_expense_line_to_the_expense()
	{
		$expense = Expense::create($this->vendor, $this->expenseable)
			->line('Some description', 1, 10000);

		$this->assertEquals('Some description', $expense->lines[0]['description']);
		$this->assertEquals(1, $expense->lines[0]['quantity']);
		$this->assertEquals(10000, $expense->lines[0]['amount']);
	}

	/** @test */
	public function can_add_multiple_expense_lines_to_the_expense()
	{
		$expense = Expense::create($this->vendor, $this->expenseable)
			->lines([
				[
					'quantity' => 1, 
					'amount' => 10000,
					'description' => 'Some description',
				],
				[
					'quantity' => 1, 
					'amount' => 20000,
					'description' => 'Another description'
				],
				[
					'quantity' => 2, 
					'amount' => 30000,
					'description' => 'Final description'
				]
			]);

		$this->assertEquals(10000, $expense->lines[0]['amount']);
		$this->assertEquals('Another description', $expense->lines[1]['description']);
		$this->assertEquals(2, $expense->lines[2]['quantity']);
	}

	/** @test */
	public function can_add_expense_line_to_the_expense_and_create_the_expense()
	{
		$expense = Expense::create($this->vendor, $this->expenseable)
			->line('Some description', 1, 10000)
			->line('Another description', 1, 10000)
			->save();

		$this->assertEquals('Some description', $expense->lines[0]['description']);
		$this->assertEquals(1, $expense->lines[0]['quantity']);
		$this->assertEquals(10000, $expense->lines[0]['amount']);

		$this->assertDatabaseCount('expenses', 1);
		$this->assertDatabaseCount('expense_lines', 2);
	}

	/** @test */
	public function can_read_expense_lines()
	{
		$expense = Expense::create($this->vendor, $this->expenseable)
			->line('Some description', 1, 10000)
			->line('Another description', 1, 20000)
			->line('Last description', 1, 30000)
			->save();

		$this->assertDatabaseCount('expenses', 1);
		$this->assertDatabaseCount('expense_lines', 3);
		$this->assertEquals('Some description', $expense->lines->first()->description);
		$this->assertEquals('Last description', $expense->lines->last()->description);
	}

	/** @test */
	public function quantity_field_can_accept_decimals()
	{
		$expense = Expense::create($this->vendor, $this->expenseable)
			->line('Line with decimal quantity', 2.15, 10000)
			->save();

		$this->assertDatabaseCount('expenses', 1);
		$this->assertDatabaseCount('expense_lines', 1);
		$this->assertEquals(2.15, $expense->lines()->first()->quantity);
		$this->assertEquals(21500, $expense->balance);
	}

	/** @test */
	public function two_decimals_format_for_quantity()
	{
		$expense = Expense::create($this->vendor, $this->expenseable)
			->line('Line with lot of decimal quantity', 2.155567, 10000)
			->save();

		$this->assertDatabaseCount('expenses', 1);
		$this->assertDatabaseCount('expense_lines', 1);
		$this->assertEquals(2.16, $expense->lines()->first()->quantity);
		$this->assertEquals(21600, $expense->balance);

	}

	/** @test */
	public function calculate_expense_amount()
	{
		$expense = Expense::create($this->vendor, $this->expenseable)
			->line('Some description', 1, 10000)
			->line('Another description', 1, 20000)
			->save();

		$this->assertSame(30000, $expense->amount);
	}

	/** @test */
	public function can_set_expense_number()
	{
		$expense = Expense::create($this->vendor, $this->expenseable)
			->number('EXPENSE-1234');

		$this->assertEquals('EXPENSE-1234', $expense->number);
	}

	/** @test */
	public function currency_can_be_changed()
	{
		$expense = Expense::create($this->vendor, $this->expenseable)
			->currency('EUR');

		$this->assertEquals('EUR', $expense->currency);
	}

	/** @test */
	public function date_can_be_changed()
	{
		$expense = Expense::create($this->vendor, $this->expenseable)
			->date('2021-03-01');

		$this->assertEquals('2021-03-01', $expense->date);
	}

    /** @test */
    public function can_add_notes_to_expense()
    {
    	$expense = Expense::create($this->vendor, $this->expenseable)
			->line('Some description', 1, 10000)
			->note('This is a note for the expense')
			->save();

		$this->assertSame('This is a note for the expense', $expense->note);
    }

	/** @test */
	public function currency_can_only_have_three_characters()
	{
		$this->expectException('Exception');
		$this->expectExceptionCode(1);
		$this->expectExceptionMessage('The currency should only be 3 characters long');

		$expense = Expense::create($this->vendor, $this->expenseable)
			->currency('USDS');
		$this->assertDontSeeText('USDS', $this->expense->currency);

		$expense = Expense::create($this->vendor, $this->expenseable)
			->currency('US');
		$this->assertDontSeeText('US', $this->expense->currency);

		$expense = Expense::create($this->vendor, $this->expenseable)
			->currency('$');
		$this->assertDontSeeText('$', $this->expense->currency);
	}

	/** @test */
	public function add_custom_fields_to_expense()
	{
		$expense = Expense::create($this->vendor, $this->expenseable)
			->customField('Custom', 'Field');

		$this->assertEquals('Field', $expense->customFields['Custom']);
	}

	/** @test */
	public function only_4_custom_fields_allowed()
	{
		$this->expectException('Exception');
		$this->expectExceptionCode(1);
		$this->expectExceptionMessage('You can add a maximum of 4 custom fields');

		$expense = Expense::create($this->vendor, $this->expenseable)
			->customField('Custom 1', 'Field 1')
			->customField('Custom 2', 'Field 2')
			->customField('Custom 3', 'Field 3')
			->customField('Custom 4', 'Field 4')
			->customField('Custom 5', 'Field 5');
	}

	/** @test */
	public function custom_fields_are_casted_to_array()
	{
		$expense = Expense::create($this->vendor, $this->expenseable)
			->line('Some description', 1, 10000)
			->customField('Custom', 'Field')
			->save();

		$this->assertEquals('Field', $expense->custom_fields['Custom']);
	}

    /** @test */
    public function test_expense_balance_attribute()
    {
    	$expense = Expense::create($this->vendor, $this->expenseable)
			->line('Some description', 1, 10000)
			->save();

		$this->assertSame(10000, $expense->balance);
    }

    /** @test */
    public function expense_services_properties_are_reseted_after_save()
    {
    	$expense = Expense::create($this->vendor, $this->expenseable);
		$expense->number('EXPENSE-1234');
		$expense->note('This is a note for the expense');
		$expense->customField('Origin', 'Houston');
		$expense->line('Some description', 1, 10000);
		$expense->line('Another description', 1, 20000);
		$expense->save();

		$this->assertNull($expense->vendor);
		$this->assertNull($expense->expenseable);
		$this->assertNull($expense->number);
		$this->assertNotNull($expense->currency);
		$this->assertNotNull($expense->date);
		$this->assertNull($expense->note);
		$this->assertEmpty($expense->lines);
		$this->assertEmpty($expense->customFields);
    }

    /** @test */
    public function can_render_a_expense_view()
    {
    	$expense = Expense::create($this->vendor, $this->expenseable)
			->customField('Origin', 'Houston')
			->line('Some description', 1, 10000)
			->line('Another description', 1, 20000)
			->saveAndView()
			->render(); // if view cannot be rendered will fail the test

        $this->assertTrue(true);
    }

	/** @test */
	public function can_make_a_payment()
	{
        $expense = Expense::create($this->vendor, $this->expenseable)
			->line('Some description', 1, 10000)
			->save();

		$payment = ExpensePayment::create($expense)
			->amount(10000)
			->save();

		$this->assertDatabaseCount('expense_payments', 1);
		$this->assertSame($expense->id, $payment->expense_id);
	}

	/** @test */
	public function payment_needs_a_expense_model()
	{
		$this->expectException('Exception');
		$this->expectExceptionCode(1);
		$this->expectExceptionMessage('You must add a Expense model');

		ExpensePayment::amount(10000)
			->save();
	}

	/** @test */
	public function can_create_multiple_payments()
	{
        $expense = Expense::create($this->vendor, $this->expenseable)
			->line('Some description', 1, 10000)
			->save();

		ExpensePayment::create($expense)
			->amount(5000)
			->save();

		$this->assertDatabaseCount('expense_payments', 1);
		$this->assertSame(5000, $expense->balance);

		ExpensePayment::create($expense)
			->amount(5000)
			->save();

		$this->assertDatabaseCount('expense_payments', 2);
		$this->assertSame(0, $expense->balance);
	}

	/** @test */
	public function payment_amount_cannot_be_more_than_the_expense_amount()
	{
		$this->expectException('Exception');
		$this->expectExceptionCode(1);
		$this->expectExceptionMessage('The payment amount cannot be higher than the expense amount');

        $expense = Expense::create($this->vendor, $this->expenseable)
			->line('Some description', 1, 10000)
			->save();

		ExpensePayment::create($expense)
			->amount(20000)
			->save();
	}

	/** @test */
	public function payment_amount_can_be_less_than_the_expense_amount()
	{
        $expense = Expense::create($this->vendor, $this->expenseable)
			->line('Some description', 1, 10000)
			->save();

		ExpensePayment::create($expense)
			->amount(3000)
			->save();

		$this->assertDatabaseCount('expense_payments', 1);
		$this->assertSame(7000, $expense->balance);
	}

	/** @test */
	public function can_create_a_payment_with_all_data()
	{
        $expense = Expense::create($this->vendor, $this->expenseable)
			->line('Some description', 1, 10000)
			->save();

		$payment = ExpensePayment::create($expense)
			->amount(10000)
			->number('PAYMENT-123')
			->method('Check')
			->reference('Check # 001122')
			->save();

		$this->assertDatabaseCount('expense_payments', 1);
		$this->assertSame(10000, $payment->amount);
		$this->assertSame('PAYMENT-123', $payment->number);
		$this->assertSame('Check', $payment->method);
		$this->assertSame('Check # 001122', $payment->reference);
	}

    /** @test */
    public function can_render_a_payment_view()
    {
        $expense = Expense::create($this->vendor, $this->expenseable)
			->line('Some description', 1, 10000)
			->save();

		$payment = ExpensePayment::create($expense)
			->amount(10000)
			->number('PAYMENT-123')
			->method('Check')
			->reference('Check # 001122')
			->saveAndView()
			->render(); // if view cannot be rendered will fail the test

        $this->assertTrue(true);
    }

    /** @test */
    public function expense_payment_services_properties_are_reseted_after_save()
    {
        $expense = Expense::create($this->vendor, $this->expenseable)
			->line('Some description', 1, 10000)
			->save();

		$payment = ExpensePayment::create($expense);
			$payment->amount(10000);
			$payment->number('PAYMENT-123');
			$payment->method('Check');
			$payment->reference('Check # 001122');
			$payment->save();

		$this->assertNotNull($payment->date);
		$this->assertNull($payment->amount);
		$this->assertNull($payment->number);
		$this->assertNull($payment->method);
		$this->assertNull($payment->reference);
    }

	/** @test */
	public function trait_customer_has_expenses_can_read_expenses()
	{
        Expense::create($this->vendor, $this->expenseable)
			->line('Some description', 1, 10000)
			->save();

        $this->assertCount(1, $this->vendor->expenses);
	}

    /** @test */
    public function trait_has_invoice_can_read_expenses()
    {
        Expense::create($this->vendor, $this->expenseable)
			->line('Some description', 1, 10000)
			->save();

        $this->assertCount(1, $this->expenseable->expenses);
    }
}
