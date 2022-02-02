<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['file', 'user_id'];

    /**
     * Custom attributes that should be append.
     *
     * @var array<string, string>
     */

    protected $appends = ['file_path'];

    /**
     * Getters
     *
     */

    public function getFilePathAttribute()
    {
        return "storage/" . app('filesPath') . "/{$this->file}";
    }
}
