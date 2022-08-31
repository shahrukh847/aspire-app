<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Auth;
use DB;

class LoanValidate implements Rule
{
	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct($loan_id)
	{
		$this->loan_id = $loan_id;
	}

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value)
	{
		$loan = DB::table('loan_applications')
		->where('id', $this->loan_id)
		->where('user_id', Auth::user()->id)
		->where('loan_status', 1)
		->where('closed_status', 0)
		->first();

		if(empty($loan)){
			return false;
		}       
		return true;
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return 'The :attribute  is inappropriate, There is no Loan Found. or Loan is Closed';
	}
}
