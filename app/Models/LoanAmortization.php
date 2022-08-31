<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanAmortization extends Model
{
	use HasFactory;

	protected $table = 'loan_amortization';

	protected $fillable = [
		'loan_id',
		'date',
		'payment_amount',
		'remaining_balance',   
	];

}