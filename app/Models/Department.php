<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'imagedep',
        'titledep',

       
    ];


    protected $table = 'departments'; 

    protected $primaryKey = 'id';
}
