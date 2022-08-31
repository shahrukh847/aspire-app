<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DB;

class ApproveRejectLoan implements Rule
{

	protected $loan_status;
	protected $loan_id;

	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */


	public function __construct($loan_status,$loan_id)
	{
		$this->loan_status = $loan_status;
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
		
		$status = DB::table('loan_applications')
		->where('id', $this->loan_id)
		->value('loan_status');

		if($status != 0){
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
		return 'The :attribute Already Been Approved.';
	}
}
