<?php

namespace App\Models;
use App\Models\Faq;
use Illuminate\Database\Eloquent\Model as Eloquent;

class FaqCategory extends Eloquent
{
    protected $table = 'faq_categories';

    public function faq()
    {
    	return $this->hasMany('\App\Models\Faq','category_id','id');
    }
}
