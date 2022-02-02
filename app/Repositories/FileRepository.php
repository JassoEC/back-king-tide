<?php

namespace App\Repositories;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;

class FileRepository extends BaseRepository
{
    public function __construct(File $file)
    {
        parent::__construct($file);
    }

    /**
     * Get the lastest file by user
     *
     * @param integer $userId
     * @return Model
     */
    public function getFirstByUser(int $userId): ?Model
    {
        return $this
            ->model
            ->where('user_id', $userId)
            ->first();
    }
}
