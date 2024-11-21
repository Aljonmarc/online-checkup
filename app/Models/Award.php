<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'year',
        'description',
       
    ];


    protected $table = 'awards'; 

    protected $primaryKey = 'id';
}
