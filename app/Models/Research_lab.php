<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Research_lab extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'description',
        'image',
       
    ];


    protected $table = 'research_labs'; 

    protected $primaryKey = 'id';
}
