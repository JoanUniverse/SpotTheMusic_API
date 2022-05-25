<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserShort;


class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $primaryKey = 'id_message';
    public $timestamps = false;
    public static $snakeAttributes = false;

    protected $with=['userFrom', 'userTo'];

    public function userFrom() {
        return $this->belongsTo(UserShort::class,'userFrom','id_user');
    }

    public function userTo() {
        return $this->belongsTo(UserShort::class,'userTo','id_user');
    }
}
