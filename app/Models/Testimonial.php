<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    // Specify which fields can be mass assigned
    protected $fillable = [
        'user_id', // Foreign key for the authenticated user
        'message', // Testimonial message
    ];

    // Explicitly define the database table
    protected $table = 'testimonials';

    // Specify the primary key
    protected $primaryKey = 'id';

    // Enable timestamps for created_at and updated_at
    public $timestamps = true;

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
