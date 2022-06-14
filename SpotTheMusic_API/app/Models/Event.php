<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['id_event', 'name', 'description', 'location', 'artist', 'picture', 'event_date', 'price'];
    protected $table = 'events';
    protected $primaryKey = 'id_event';
    public $timestamps = false;
}
