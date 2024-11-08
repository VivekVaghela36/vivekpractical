<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function detail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function sentFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'send_by');
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'send_to')->where('status', 'pending');
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skill');
    }

    // Utility to check if user has already sent a request
    public function hasSentFriendRequestTo($userId)
    {
        return $this->sentFriendRequests()->where('send_to', $userId)->exists();
    }

    // Utility to check if user has received a request from someone
    public function hasReceivedFriendRequestFrom($userId)
    {
        return $this->receivedFriendRequests()->where('send_by', $userId)->exists();
    }
}
