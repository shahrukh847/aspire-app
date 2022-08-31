<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\LoanAmortization;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Rules\LoanValidate;
use App\Rules\EmiValidate;
use App\Rules\EmiMinmax;
use Auth;
use Carbon\Carbon;

class PaymentController extends Controller
{

	public function repayLoan(Request $request) {

		
		try {
			// Transactions Begin
			DB::beginTransaction();

			//Custome rules checks in Rules Folder
			$validator = Validator::make($request->all(), [
				'loan_id' => ['required', new LoanValidate($request->loan_id)],
				'emi_id' => ['required', new EmiValidate($request->emi_id,$request->loan_id)],
				'amount' =>['required', new EmiMinmax($request->loan_id,$request->emi_id,$request->amount)],
			]);

			if ($validator->fails())
			{
				return response(['errors'=>$validator->errors()->all()], 422);
			}

			$loan = LoanApplication::where('id',$request->loan_id)->first();


			// if user paid Whole Amount All Emis is paid And Loan status is closed
			if ($request->amount ==  $loan->remaining_amount) {

				$loan->closed_status = 1;
				$loan->remaining_amount = 0;
				$loan->loan_closed_date = date('Y-m-d');
				$loan->save();

				LoanAmortization::where('loan_id','=',$request->loan_id)
								->update(['payment_status' => 'Paid' ]);

				LoanAmortization::where('emi_id','=',$request->emi_id)
								->update(['payment_amount' => $request->amount ]);
			} else {

				$emiAmount = LoanAmortization::where('id',$request->emi_id)->first()->emi;
				$remaining_amount = $loan->remaining_amount - $request->amount;


				LoanAmortization::where('id','=',$request->emi_id)
								->update([
									'payment_status' => 'Paid' , 
									'payment_amount' => $request->amount ,
									'payment_date' => date('Y-m-d')
								]);

				LoanApplication::where('id','=',$request->loan_id)
								->update([
									'remaining_amount' => $remaining_amount 
								]);

				
				// if Paid Amount is Greather then Emi Then We have to set Next upcoming Emi
				if ($request->amount > $emiAmount) {

					$remaining_emi = LoanAmortization::where('loan_id',$request->loan_id)
								->where('payment_status','Unpaid')
								->count();

					$New_Emi_Value = $remaining_amount / $remaining_emi;

					LoanAmortization::where('id', '!=', $request->emi_id)
						->where('loan_id', '=', $request->loan_id)
						->where('payment_status','=', 'Unpaid')
						->update([
							'emi' => round($New_Emi_Value,4)
						]);
				}
			}

			// Commit Database transactions
			DB::commit();
			return response()->json(['success' => true, 'msg' => 'Emi Successfully paid']);

		} catch (\Exception $ex) {
			// Roll Back if any error Occurs
			DB::rollBack();
			return response()->json(['success' => false, 'error' => $ex->getMessage()], 500);
		}
	}

}