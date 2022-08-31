<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\LoanAmortization;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Rules\ApproveRejectLoan;
use Auth;
use Carbon\Carbon;
use Log;

class LoanController extends Controller
{
	// Users Loan application API By Shahrookh Shaikh
	public function applyLoan(Request $request) {
	
		$validator = Validator::make($request->all(), [
			'mode_of_payment' => 'required|string|max:10',
			'loan_amount' => 'required|integer',
			'loan_duration' => 'required|integer',
			'purpose' => 'required|string|max:150',
		]);

		if ($validator->fails())
		{
			return response(['errors'=>$validator->errors()->all()], 422);
		}

		$request['user_id'] = Auth::user()->id;
		$request['remaining_amount'] = $request->loan_amount;
		$loan = LoanApplication::create($request->toArray());
		$response = ['Success' => true,'msg' => 'Loan Application Submitted Successfully'];
		return response($response, 200);
	}

	public function approveReectLoan(Request $request) {

		try {

			DB::beginTransaction();

				$validator = Validator::make($request->all(), [
					'loan_id' => 'required',
					'loan_status' =>['required', new ApproveRejectLoan($request->loan_status,$request->loan_id)],
				]);

				if ($validator->fails())
				{
					return response(['errors'=>$validator->errors()->all()], 422);
				}

				$loan = LoanApplication::where('id',$request->loan_id)->first();
				$loan->loan_status = ($request->loan_status  == "approved") ? 1 : 0;
				$loan->remarks = $request->remarks;
				$loan->processed_by = Auth::user()->id;
				$loan->save();

				if ($request->loan_status == "approved") {

					$emi =  $loan->loan_amount / $loan->loan_duration;
					$reminder = $loan->loan_amount - (round($emi,4) * $loan->loan_duration);

					for ($i=1; $i <= $loan->loan_duration; $i++) { 

						$amortization = new LoanAmortization(); 
						$amortization->loan_id = $loan->id;
						$amortization->user_id = $loan->user_id;
						$amortization->emi_order = $i;

						if ( $i == $loan->loan_duration) {
							$emi = $emi + $reminder;
						}

						$amortization->emi = round($emi,4);

						if ($loan->mode_of_payment == 'weekly') {
							$daysToAdd = 7 * $i;
							$emiDate = Carbon::now()->addDays($daysToAdd);
						} else {
							$emiDate = Carbon::now()->addMonth();
						}
						$amortization->emi_date = $emiDate;
						$amortization->save();
					}

					$response = ['Success' => true,'msg' => 'Loan Application Accepted Successfully'];
				} else {
					$response = ['Success' => true,'msg' => 'Loan Application Rejected Successfully'];
				}

			DB::commit();

			return response($response, 200);

		} catch (\Exception $ex) {
			DB::rollBack();
			return response()->json(['success' => false, 'error' => $ex->getMessage()], 500);
		}
	}

	public function loanView(Request $request) {

		try {

			$loans = LoanApplication::where('user_id',Auth::user()->id)
				->where('loan_status',1)
				->get();

			if (empty($loans)) {
				$msg = "There is No Loan  Found";
			} else {
				$msg = "Loan Applications Retrived Successfully";
			}
			$response = ['Success' => true, 'msg' => $msg, 'data' => $loans];
			return response($response, 200);

		} catch (\Exception $ex) {
			
			return response()->json(['success' => false, 'error' => $ex->getMessage()], 500);
		}
	}

	public function singleLoanDetail(Request $request) {

		try {
			$loan = LoanApplication::with('emis')
				->where('id',$request->loan_id)
				->where('user_id',Auth::user()->id)
				->where('loan_status', 1)
				->first();

			if (empty($loan)) {
				$msg = "There is No Loan  Found";
			} else {
				$msg = "Loan with Emi Retrived Successfully";
			}
					
			$response = ['Success' => true, 'msg' => $msg,'data' => $loan];
			return response($response, 200);

		} catch (\Exception $ex) {
			return response()->json(['success' => false, 'error' => $ex->getMessage()], 500);
		}
	}
}