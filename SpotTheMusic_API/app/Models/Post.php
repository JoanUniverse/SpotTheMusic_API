<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserShort;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $primaryKey = 'id_post';
    public $timestamps = false;

    protected $with=['user'];

    public function user() {
        return $this->belongsTo(UserShort::class,'user','id_user');
    }
}
