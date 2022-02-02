<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\FileRequest;
use App\Http\Requests\V1\ImageRequest;
use App\Http\Resources\V1\FileResource;
use App\Http\Resources\V1\UserResource;
use App\Services\V1\FileService;

class FileController extends Controller
{

    /**
     * Update profile picture of user with file given
     *
     * @param \App\Services\V1\FileService $service
     * @param \App\Http\Requests\V1\ImageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function storeProfileImage(FileService $service, ImageRequest $request)
    {
        $data = $service->storeProfileImage($request);
        return new UserResource($data);
    }

    /**
     * Store a new resume file
     *
     * @param \App\Services\V1\FileService $service
     * @param \App\Http\Requests\V1\FileRequest $request
     * @return \Illuminate\Http\Response
     */
    public function storeResume(FileService $service, FileRequest $request)
    {
        $data = $service->storeResumeFile($request);
        return new FileResource($data);
    }
}
