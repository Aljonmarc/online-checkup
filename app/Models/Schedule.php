<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    // Mass-assignable attributes
    protected $fillable = ['user_id', 'start_date', 'start_time', 'end_time', 'status', 'docname'];

    // Table name (optional if it follows convention)
    protected $table = 'schedules'; 

    // Primary key (optional if it’s 'id')
    protected $primaryKey = 'id';

    // Constants for status values
    const STATUS_AVAILABLE = 'available';
    const STATUS_TAKEN = 'already taken';



    public function user()
{
    return $this->belongsTo(User::class, 'user_id', 'id'); // Adjust column names as needed
}

}
