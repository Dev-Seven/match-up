<?php

namespace App\Console\Commands;

use App\Models\PlayedGameTag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;

class PremiumExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'premium:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Premium expired code';

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
        $currentDateTime = Date('Y-m-d H:i:s',strtotime('now'));

        $UserList = User::where('is_premium_purchase',1);
        $UserList = $UserList->where('premium_purchase_expire_date','<',$currentDateTime);
        $UserList = $UserList->get();

        if(!empty($UserList) && count($UserList) > 0)
        {
            foreach($UserList as $key => $value)
            {
                $UserDetails = User::where('id',$value->id)->first();
                $UserDetails->is_premium_purchase = 0;
                $UserDetails->premium_purchase_expire_date = null;
                $UserDetails->save();
            }
        }

        $yesterday = date('Y-m-d H:i:s',strtotime("-1 days"));
        $PlayedGameTag = PlayedGameTag::where('created_at','<=',$yesterday)->delete();
    }
}
