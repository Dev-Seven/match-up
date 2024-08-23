<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
	//protected $table = 'ratings';

	public function user()
	{
		return $this->hasOne('\App\Models\User','id','user_id');
	}

	public function game()
	{
		return $this->hasOne('\App\Models\Game','id','game_id');
	}
}
