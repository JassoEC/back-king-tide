<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'name',
        'last_name',
        'sur_name',
        'rfc',
        'birthday',
        'profile_picture',
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

    /**
     * Custom attributes that should be append.
     *
     * @var array<string, string>
     */

    protected $appends = ['profile_picture_path'];

    /**
     * Relationships
     */

    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * Mutators
     */

    public function setBirthdayAttribute($value)
    {
        return $this->attributes['birthday'] = Carbon::parse($value)->format('Y-m-d h:m:s');
    }

    /**
     * Getters
     *
     */

    public function getProfilePicturePathAttribute()
    {
        return $this->profile_picture ? "storage/" . app('profileImagesPath') . "/" . $this->profile_picture : null;
    }
    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->last_name} {$this->sur_name}";
    }
}
