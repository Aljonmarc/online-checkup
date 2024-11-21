<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'logo',
       
    ];


    protected $table = 'headers'; 

    protected $primaryKey = 'id';
}
