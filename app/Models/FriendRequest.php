<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    protected $fillable = ['send_by', 'send_to', 'status'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'send_by');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'send_to');
    }
}
