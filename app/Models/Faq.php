<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Models\FaqCategory;

class Faq extends Eloquent
{
    protected $table = 'faqs';

    public function faqCategory(){

        return $this->hasOne(FaqCategory::class, 'id', 'category_id');
    }
}
