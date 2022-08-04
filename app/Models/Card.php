<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

   public $guarded = [];


   public function category()
   {
       $this->belongsTo(Category::class);
   }
}
