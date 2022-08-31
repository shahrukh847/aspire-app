<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Auth;
use DB;

class EmiValidate implements Rule
{
	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct($emi_id,$loan_id)
	{
		$this->emi_id = $emi_id;
		$this->loan_id = $loan_id;
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
		$data = DB::table('loan_amortization')
		->where('id', $this->emi_id)
		->where('loan_id', $this->loan_id)
		->where('user_id', Auth::user()->id)
		->first();

		if (empty($data)) {
			$this->message = "In Appropriate Data in EMI";
			return false;
		}

		if(  isset($data) && $data->payment_status == 'Paid' ){
			$this->message = "This Emi is Already paid" ;
			return false;
		}

		$check_previous_emi_status = DB::table('loan_amortization')
		->where('loan_id', $this->loan_id)
		->where('emi_order', '<' , $data->emi_order)
		->where('payment_status', 'Unpaid')
		->get();

		//dd()

		if( count($check_previous_emi_status) > 0 ){
			$this->message = "Please pay Emi in order Pay Earlier Emi First";
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
		return $this->message;
	}
}
