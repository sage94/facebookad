<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{
    protected $fillable = [
       'title', 'description', 'address','website'
   ];
}
