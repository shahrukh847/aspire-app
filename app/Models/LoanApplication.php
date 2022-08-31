<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    use HasFactory;

    protected $table = 'loan_applications';

    protected $fillable = [
        'user_id',
        'mode_of_payment',
        'loan_amount',
        'remaining_amount',
        'loan_duration',
        'processed_by',
        'purpose',
        'loan_status',
        'remarks',

    ];

    public function emis()
    {
        return $this->hasMany('App\Models\LoanAmortization', 'loan_id' ); 
    }


}
