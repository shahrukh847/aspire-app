<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DB;

class EmiMinmax implements Rule
{
	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct($loan_id,$emi_id,$amount)
	{
		$this->loan_id = $loan_id;
		$this->emi_id = $emi_id;
		$this->amount = $amount;
		$this->message = "";
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
		$remaining_amount = DB::table('loan_applications')
		->where('id', $this->loan_id)
		->value('remaining_amount');

		$Validate_Emi = DB::table('loan_applications')
		->where('id', $this->loan_id)
		->value('remaining_amount');

		if (isset($remaining_amount)) {
			
			if ($this->amount > $remaining_amount  ) {
				$this->message = "Emi Amount is greater then Total Loan Remaining Amount of " . $remaining_amount;
				return false;
			}

			$emi = DB::table('loan_amortization')
			->where('id', $this->emi_id)
			->first();

			if ($this->amount < $emi->emi ) {
				$this->message = "Emi Amount is Less then Emi Amount Minimum Amount is " . $emi->emi;
				return false;
			}
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
		return $this->message;
	}
}
