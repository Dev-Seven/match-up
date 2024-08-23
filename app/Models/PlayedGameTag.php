<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayedGameTag extends Model
{
	protected $table = 'played_game_tags';
	public function game_detail()
	{
		return $this->hasOne('\App\Models\Game','id','game_id');
	}
}
