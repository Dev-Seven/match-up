<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
	public function game_items()
	{
		return $this->hasMany('\App\Models\GameItem','game_id','id');
	}
}
