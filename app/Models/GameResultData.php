<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameResultData extends Model
{
	protected $table = "game_result_data";

	public function game_details()
	{
		return $this->hasOne('\App\Models\Game','id','game_id');
	}

	public function user_details()
	{
		return $this->hasOne('\App\Models\User','id','user_id');
	}

	public function game_result_details()
	{
		return $this->hasOne('\App\Models\GameResult','id','game_result_id');
	}
}
