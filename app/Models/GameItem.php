<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameItem extends Model
{
	public function item_detail()
	{
		return $this->hasOne('\App\Models\Item','id','item_id');
	}

	public function game_detail()
	{
		return $this->hasOne('\App\Models\Game','id','game_id');
	}
}
