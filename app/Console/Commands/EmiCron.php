<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LoanAmortization;

class EmiCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emi:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        info("Cron Job running at ". now());
  
       
      
        //$emis = LoanAmortization::where('date' = Carbon::now('Y-m-d'));
  
        // if (!empty($emis)) {
        //     foreach ($users as $key => $user) {
        //         if(!User::where('email', $user['email'])->exists() ){
        //             User::create([
        //                 'name' => $user['name'],
        //                 'email' => $user['email'],
        //                 'password' => bcrypt('123456789')
        //             ]);
        //         }
        //     }
        // }
  
        return 0;
    }
}
