<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserShort;

class Song extends Model
{
    use HasFactory;

    protected $table = 'songs';
    protected $primaryKey = 'id_song';
    public $timestamps = false;
    public static $snakeAttributes = false;

    protected $with=['artist'];

    public function artist() {
        return $this->belongsTo(UserShort::class,'artist','id_user');
    }
}
