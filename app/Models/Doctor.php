<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'docimage',
        'docroom',
        'docname',
        'docspec',
        'docphone',
        'docdep',

       
    ];


    protected $table = 'doctors'; 

    protected $primaryKey = 'id';
}
