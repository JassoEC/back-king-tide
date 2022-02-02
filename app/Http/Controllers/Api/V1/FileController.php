<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\FileRequest;
use App\Http\Requests\V1\ImageRequest;
use App\Models\User;
use App\Services\V1\FileService;

class FileController extends Controller
{

    public function storeProfileImage(FileService $service, ImageRequest $request, User $user)
    {
        $data = $service->storeProfileImage($request, $user);
    }

    public function storeResume(FileService $service, FileRequest $request)
    {
        $data = $service->storeResumeFile($request);

        return $data;
    }
}
