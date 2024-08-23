<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameTag extends Model
{
	public function game_detail()
	{
		return $this->hasOne('\App\Models\Game','id','game_id');
	}
}
