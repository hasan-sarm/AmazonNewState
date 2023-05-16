<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
   protected $fillable=[
       'message',
       'product_id',
       'user_id',
   ];
   public function user()
   {
       return $this->belongsTo(User::class);
   }
}
