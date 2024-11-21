<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id', 'user_id', 'patient_name', 'status' , 'patient_phone' ,
        'start_date','start_time', 'end_time' , 'doc_name', 'admin_status', 'schedule_user_id'
        ,'reply'
    ];

    protected $table = 'bookings'; 

    protected $primaryKey = 'id';

    // Define the relationship with the Schedule model
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    // Define the relationship with the User model (the patient)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
